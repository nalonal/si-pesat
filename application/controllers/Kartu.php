<?php
defined('BASEPATH') OR exit('');
require_once 'functions.php';
/**
 * Description of Transactions
 *
 * @author Amir <amirsanni@gmail.com>
 * @date 31st Dec, 2015
 */
class Kartu extends CI_Controller{
    private $total_before_discount = 0, $discount_amount = 0, $vat_amount = 0, $eventual_total = 0;
    
    public function __construct(){
        parent::__construct();
        $this->genlib->checkLogin();
        $this->load->library('pdf');
        $this->load->model(['kartudb', 'item','transaction']);
    }
    


    
    public function index(){
        $transData['items'] = $this->item->getActiveItems('name', 'ASC');//get items with at least one qty left, to be used when doing a new transaction
        
        $data['pageContent'] = $this->load->view('kartu/kartu', $transData, TRUE);
        $data['pageTitle'] = "Cetak Kartu";
                /*buat dashboard*/
        $temp = $this->kurang();
        $data['item'] = $temp['item'];
        /*end buat dashboard*/
        
        $this->load->view('main', $data);
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

        /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

        /**
     * FUNGSI BUATAN SENDIRI -> TUJUAN UNTUK MENGETAHUI JUMLAH BARANG YANG KURANG
     */
  
    public function kurang(){
        $this->load->model(['item']);
        //ini untuk menghitung oba
        //batas

        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        $totalItems = $this->db->count_all('items');
        $this->load->library('pagination');
        $pageNumber = $this->uri->segment(3, 0);
        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 1000;
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;

        $allItems = $this->item->getAll($orderBy, $orderFormat, $start, $limit);

        $kurang = []; //mencari data yang kurang
        $no = 0;
        foreach($allItems as $get):
            if($get->quantity <= $get->minimal){
                $kurang[$no] = $get->name;
            }
            $no++;
        endforeach;

        $hasil = count($kurang);
        $data['item'] = $hasil;

        return $data;
    }


    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * latr_ = "Load All Transactions"
     */
    public function latr_(){
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "transId";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "DESC";
        
        //count the total number of transaction group (grouping by the ref) in db
        $totalTransactions = $this->kartudb->totalTransactions();
        
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri
    
        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalTransactions, "kartudb/latr_", $limit, ['onclick'=>'return latr_(this.href);']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        //get all transactions from db
        $data['allTransactions'] = $this->kartudb->getAll($orderBy, $orderFormat, $start, $limit);
        $data['range'] = $totalTransactions > 0 ? ($start+1) . "-" . ($start + count($data['allTransactions'])) . " of " . $totalTransactions : "";
        $data['links'] = $this->pagination->create_links();//page links
        $data['sn'] = $start+1;
        
        $json['transTable'] = $this->load->view('kartu/kartutable', $data, TRUE);//get view with populated transactions table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    
    /**
     * nso_ = "New Sales Order"
     */
    public function nso_(){
        $this->genlib->ajaxOnly();
        
        $arrOfItemsDetails = json_decode($this->input->post('_aoi', TRUE));  
        $cust_name = $this->input->post('cn', TRUE);

        
        /*
         * Loop through the arrOfItemsDetails and ensure each item's details has not been manipulated
         * The unitPrice must match the item's unit price in db, the totPrice must match the unitPrice*qty
         * The cumAmount must also match the total of all totPrice in the arr in addition to the amount of 
         * VAT (based on the vat percentage) and minus the $discount_percentage (if available)
         */
        
        //$allIsWell = $this->validateItemsDet($arrOfItemsDetails, $cumAmount, $_at, $vatPercentage, $discount_percentage);
        
        //if($allIsWell){//insert each sales order into db, generate receipt and return info to client
            
            //will insert info into db and return transaction's receipt
            $returnedData = $this->insertTrToDb($arrOfItemsDetails, $cust_name);
        if($returnedData){
            $json['status'] = $returnedData ? 1 : 0;
            $json['msg'] = $returnedData ? "Transaction successfully processed" : 
                    "Unable to process your request at this time. Pls try again later "
                    . "or contact technical department for assistance";
            $json['transReceipt'] = $returnedData['transReceipt'];
            
            $json['totalEarnedToday'] = number_format($this->kartudb->totalEarnedToday());
            
            //add into eventlog
            //function header: addevent($event, $eventRowIdOrRef, $eventDesc, $eventTable, $staffId) in 'genmod'
            $eventDesc = count($arrOfItemsDetails). " items totalling &#8358;". number_format($cumAmount, 2)
                    ." with reference number {$returnedData['transRef']} was purchased";
            
            $this->genmod->addevent("New Transaction", $returnedData['transRef'], $eventDesc, 'transactions', $this->session->admin_id);
       }
        
        else{//return error msg
            $json['status'] = 0;
            $json['msg'] = "Transaction could not be processed. Please ensure there are no errors. Thanks";
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * Validates the details of items sent from client to prevent manipulation
     * @param type $arrOfItemsInfo
     * @param type $cumAmountFromClient
     * @param type $amountTendered
     * @param type $vatPercentage
     * @param type $discount_percentage
     * @return boolean
     */
    private function validateItemsDet($arrOfItemsInfo, $cumAmountFromClient, $amountTendered, $vatPercentage, $discount_percentage){
        $error = 0;
        
        //loop through the item's info and validate each
        //return error if at least one seems suspicious (i.e. fails validation)
        foreach ($arrOfItemsInfo as $get){
            $itemCode = $get->_iC;//use this to get the item's unit price, then multiply it with the qty sent from client
            $qtyToBuy = $get->qty;
            $unitPriceFromClient = $get->unitPrice;
            $unitPriceInDb = $this->genmod->gettablecol('items', 'unitPrice', 'code', $itemCode);
            $totPriceFromClient = $get->totalPrice;
            
            //ensure both unit price matches
            $unitPriceInDb == $unitPriceFromClient ? "" : $error++;
            
            $expectedTotPrice = round($qtyToBuy*$unitPriceInDb, 2);//calculate expected totPrice
            
            //ensure both matches
            $expectedTotPrice == $totPriceFromClient ? "" : $error++;
            
            //no need to validate others, just break out of the loop if one fails validation
            if($error > 0){
                return FALSE;
            }
            
            $this->total_before_discount += $expectedTotPrice;
        }
        
        /**
         * We need to save the total price before tax, tax amount, total price after tax, discount amount, eventual total
         */
        
        $expectedCumAmount = $this->total_before_discount;
        
        //now calculate the discount amount (if there is discount) based on the discount percentage and subtract it(discount amount) 
        //from $total_before_discount
        if($discount_percentage){
            $this->discount_amount = $this->getDiscountAmount($expectedCumAmount, $discount_percentage);

            $expectedCumAmount = round($expectedCumAmount - $this->discount_amount, 2);
        }
        
        //add VAT amount to $expectedCumAmount is VAT percentage is set
        if($vatPercentage){
            //calculate vat amount using $vatPercentage and add it to $expectedTotPrice
            $this->vat_amount = $this->getVatAmount($expectedCumAmount, $vatPercentage);

            //now add the vat amount to expected total price
            $expectedCumAmount = round($expectedCumAmount + $this->vat_amount, 2);
        }        
        
        //check if cum amount also matches and ensure amount tendered is not less than $expectedCumAmount
        if(($expectedCumAmount != $cumAmountFromClient) || ($expectedCumAmount > $amountTendered)){
            return FALSE;
        }
        
        //if code execution reaches here, it means all is well
        $this->eventual_total = $expectedCumAmount;
        return TRUE;
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * 
     * @param type $arrOfItemsDetails
     * @param type $_mop
     * @param type $_at
     * @param type $cumAmount
     * @param type $_cd
     * @param type $vatAmount
     * @param type $vatPercentage
     * @param type $discount_amount
     * @param type $discount_percentage
     * @param type $cust_name
     * @param type $cust_phone
     * @param type $cust_email
     * @return boolean
     */
    private function insertTrToDb($arrOfItemsDetails, $cust_name){
        $allTransInfo = [];//to hold info of all items' in transaction
        
        //generate random string to use as transaction ref
        //keep regeneration the ref if generated ref exist in db
        do{
            $ref = strtoupper(generateRandomCode('numeric', 6, 10, ""));
        }
        
        while($this->kartudb->isRefExist($ref));
        
        //loop through the items' details and insert them one by one
        //start transaction
        $this->db->trans_start();

        foreach($arrOfItemsDetails as $get){
            $itemCode = $get->_iC;
            $itemName = $this->genmod->getTableCol('items', 'name', 'code', $itemCode);
            $qtySold = $get->qty;//qty selected for item in loop
            $unitPrice = $get->unitPrice;//unit price of item in loop
            $totalPrice = $get->totalPrice;//total price for item in loop

            /*
             * add transaction to db
             * function header: add($_iN, $_iC, $desc, $q, $_up, $_tp, $_tas, $_at, $_cd, $_mop, $_tt, $ref, $_va, $_vp, $da, $dp, $cn, $cp, $ce)
             */
            $transId = $this->kartudb->add($itemName, $itemCode, "", $qtySold, $unitPrice, $totalPrice, $cust_name, $ref);
            
            $allTransInfo[$transId] = ['itemName'=>$itemName, 'quantity'=>$qtySold, 'unitPrice'=>$unitPrice, 'totalPrice'=>$totalPrice];
            
            //update item quantity in db by removing the quantity bought
            //function header: decrementItem($itemId, $numberToRemove)
            $this->item->decrementItem($itemCode, $qtySold);
        }

        $this->db->trans_complete();//end transaction

        //ensure there was no error
        //works in production since db_debug would have been turned off
        if($this->db->trans_status() === FALSE){
            return false;
        }
        
        else{
            $dataToReturn = [];
            
            //get transaction date in db, to be used on the receipt. It is necessary since date and time must matc
            $dateInDb = $this->genmod->getTableCol('transactions', 'transDate', 'transId', $transId);
            
            //generate receipt to return
            $dataToReturn['transReceipt'] = $this->genTransReceipt($allTransInfo, $cumAmount, $_at, $_cd, $ref, $dateInDb, $_mop, $vatAmount, $vatPercentage, $discount_amount, $discount_percentage, $cust_name, $cust_phone, $cust_email);
            $dataToReturn['transRef'] = $ref;
            
            return $dataToReturn;
        }
    }

    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    /**
     * 
     * @param type $allTransInfo
     * @param type $cumAmount
     * @param type $_at
     * @param type $_cd
     * @param type $ref
     * @param type $transDate
     * @param type $_mop
     * @param type $vatAmount
     * @param type $vatPercentage
     * @param type $discount_amount
     * @param type $discount_percentage
     * @param type $cust_name
     * @param type $cust_phone
     * @param type $cust_email
     * @return type
     */
    private function genTransReceipt($allTransInfo, $cumAmount, $_at, $_cd, $ref, $transDate, $_mop, $vatAmount, $vatPercentage, 
        $discount_amount, $discount_percentage, $cust_name, $cust_phone, $cust_email){
        $data['allTransInfo'] = $allTransInfo;
        $data['cumAmount'] = $cumAmount;
        $data['amountTendered'] = $_at;
        $data['changeDue'] = $_cd;
        $data['ref'] = $ref;
        $data['transDate'] = $transDate;
        $data['_mop'] = $_mop;
        $data['vatAmount'] = $vatAmount;
        $data['vatPercentage'] = $vatPercentage;
        $data['discountAmount'] = $discount_amount;
        $data['discountPercentage'] = $discount_percentage;
        $data['cust_name'] = $cust_name;
        $data['cust_phone'] = $cust_phone;
        $data['cust_email'] = $cust_email;
        
        //generate and return receipt
        $transReceipt = $this->load->view('kartu/kartureceipt', $data, TRUE);
        
        return $transReceipt;
    }
    
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * vtr_ = "View transaction's receipt"
     * Used when a transaction's ref is clicked
     */
    public function vtr_(){
        $this->genlib->ajaxOnly();
        
        $ref = $this->input->post('ref');
        
        $transInfo = $this->kartudb->getTransInfo($ref);
        
        //loop through the transInfo to get needed info
        if($transInfo){
            $json['status'] = 1;
            
            $cumAmount = $transInfo[0]['totalMoneySpent'];
            $amountTendered = $transInfo[0]['amountTendered'];
            $changeDue = $transInfo[0]['changeDue'];
            $transDate = $transInfo[0]['transDate'];
            $modeOfPayment = $transInfo[0]['modeOfPayment'];
            $vatAmount = $transInfo[0]['vatAmount'];
            $vatPercentage = $transInfo[0]['vatPercentage'];
            $discountAmount = $transInfo[0]['discount_amount'];
            $discountPercentage = $transInfo[0]['discount_percentage'];
            $cust_name = $transInfo[0]['cust_name'];
            $cust_phone = $transInfo[0]['cust_phone'];
            $cust_email = $transInfo[0]['cust_email'];
            
            $json['transReceipt'] = $this->genTransReceipt($transInfo, $cumAmount, $amountTendered, $changeDue, $ref, 
                $transDate, $modeOfPayment, $vatAmount, $vatPercentage, $discountAmount, $discountPercentage, $cust_name,
                $cust_phone, $cust_email);
        }
        
        else{
            $json['status'] = 0;
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * Calculates the amount of VAT
     * @param type $cumAmount the total amount to calculate the VAT from
     * @param type $vatPercentage the percentage of VAT
     * @return type
     */
    private function getVatAmount($cumAmount, $vatPercentage){
        $vatAmount = ($vatPercentage/100) * $cumAmount;

        return $vatAmount;
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * Calculates the amount of Discount
     * @param type $cum_amount the total amount to calculate the discount from
     * @param type $discount_percentage the percentage of discount
     * @return type
     */
    private function getDiscountAmount($cum_amount, $discount_percentage){
        $discount_amount = ($discount_percentage/100) * $cum_amount;

        return $discount_amount;
    }
    
    /*
    ****************************************************************************************************************************
    ****************************************************************************************************************************
    ****************************************************************************************************************************
    ****************************************************************************************************************************
    ****************************************************************************************************************************
    */
    
    public function report($tanggal,$nomor){        
        $new_tanggal = $this->sistem->kalender($tanggal);
        $data['tanggal'] = $new_tanggal;
        $nomor = explode("-", $nomor);
        $data['nomor'] = implode("/", $nomor);
        $data['allTransactions'] = $this->transaction->getData("items");
        $data['semuaKategori'] = $this->transaction->getData("kategori");
        $this->load->view('kartu/kartuReport', $data);
    }




    public function laporanexcel(){
        // create file name

        $tanggal = date("Y-m-d");
        $new_tanggal = $this->sistem->kalender($tanggal);
        $tanggal = explode(",", $new_tanggal);
        $fileName = $tanggal[1].'.xlsx';  
        // load excel library
        $this->load->library('excel');
       // $empInfo = $this->export->employeeList();
        $objPHPExcel = new PHPExcel();



        /* Ini buat sytle */
        $tulisan = array(
            'font'  => array(
            //'bold'  => true,
            //'color' => array('rgb' => 'FF0000'),
            'size'  => 12,
            'name'  => 'Arial'
        ));

        $label = array(
            'font'  => array(
            'bold'  => true,
            //'color' => array('rgb' => 'FF0000'),
            'size'  => 12,
            'name'  => 'Arial'
        ));

        $header = array(
            'font'  => array(
            //'bold'  => true,
            //'color' => array('rgb' => 'FF0000'),
            'size'  => 12,
            'name'  => 'Arial',
            ),
            'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );


        $isitengah = array(
            'font'  => array(
            //'bold'  => true,
            //'color' => array('rgb' => 'FF0000'),
            'size'  => 12,
            'name'  => 'Arial',
            ),
            'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );


        $isikiri = array(
            'font'  => array(
            //'bold'  => true,
            //'color' => array('rgb' => 'FF0000'),
            'size'  => 12,
            'name'  => 'Arial',
            ),
            'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            )
        );
        /*end*/

        $sheet = $objPHPExcel->getActiveSheet();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Lampiran');
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Hasil Opname Fisik Barang Persediaan');
        $objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Hari, Tanggal :'.$new_tanggal);
        $sheet->getStyle("A1:A3")->applyFromArray($tulisan);

        // $style = array(
        // 'alignment' => array(
        //     'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        // )
        // );

        // $borderstyle = array(
        //         'borders' => array(
        //         'allborders' => array(
        //         'style' => PHPExcel_Style_Border::BORDER_THIN
        //         )
        //     )
        // );
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(50);

        $objPHPExcel->getActiveSheet()->SetCellValue('A5', 'No.');
        $objPHPExcel->getActiveSheet()->SetCellValue('B5', 'Kode Barang');

        $sheet->getRowDimension('5')->setRowHeight(40);
        $sheet->getColumnDimension('A')->setWidth(5);

        $objPHPExcel->getActiveSheet()->SetCellValue('C5', 'Nama Barang');
        $objPHPExcel->getActiveSheet()->SetCellValue('D5', 'Satuan');
        $objPHPExcel->getActiveSheet()->SetCellValue('E5', 'Jumlah');
        $sheet->getStyle("A5:E5")->applyFromArray($header);
        //$sheet->getStyle('A5:E5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('A5:F5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        $objPHPExcel->getActiveSheet()->SetCellValue('A6', "(1)");
        $objPHPExcel->getActiveSheet()->SetCellValue('B6', "(2)");
        $objPHPExcel->getActiveSheet()->SetCellValue('C6', "(3)");
        $objPHPExcel->getActiveSheet()->SetCellValue('D6', "(4)");
        $objPHPExcel->getActiveSheet()->SetCellValue('E6', "(5)");
        $sheet->getStyle('A6:F6')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        /*style*/
        $sheet->getStyle('A6')->applyFromArray($isitengah);
        $sheet->getStyle('B6')->applyFromArray($isitengah);
        $sheet->getStyle('C6')->applyFromArray($isitengah);
        $sheet->getStyle('D6')->applyFromArray($isitengah);
        $sheet->getStyle('E6')->applyFromArray($isitengah);
        /*endstyle*/


        //$excel->getActiveSheet()->getRowDimension($numrow)->setRowHeight(20);
        // $sheet->getStyle("B5")->applyFromArray($style);
        // $sheet->getStyle("B5")->applyFromArray($styleArray);
        // $sheet->getStyle("B5")->applyFromArray($borderstyle);
        //mengambil nilai data
        $allTransactions = $this->transaction->getData("items");
        $semuaKategori = $this->transaction->getData("kategori");

        // set Row
        $rowCount = 7;
        $sn = 1;

        $settingpagebreak = 1;
        $flag = 1;
        foreach($semuaKategori as $nilai){
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $nilai->kategori);
            $sheet->getStyle('B' . $rowCount)->applyFromArray($label);
            foreach($allTransactions as $get){
                if($get->kategori == $nilai->kategori){
                    $rowCount++;
                    if($flag == 1 && $settingpagebreak == 49){
                        $flag = 0;
                        $settingpagebreak = 0;
                        $objPHPExcel->getActiveSheet()->setBreak( 'A' . $rowCount , PHPExcel_Worksheet::BREAK_ROW );
                    }
                    if($settingpagebreak == 54){
                        $settingpagebreak = 0;
                        $objPHPExcel->getActiveSheet()->setBreak( 'A' . $rowCount , PHPExcel_Worksheet::BREAK_ROW );
                    }

                    if($settingpagebreak == 0 ){
                        $rowCount++;
                        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, "(1)");
                        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, "(2)");
                        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, "(3)");
                        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, "(4)");
                        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, "(5)");
                        $sheet->getStyle('A' . $rowCount . ':F' . $rowCount)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        /*style*/
                        $sheet->getStyle('A' . $rowCount)->applyFromArray($isitengah);
                        $sheet->getStyle('B' . $rowCount)->applyFromArray($isitengah);
                        $sheet->getStyle('C' . $rowCount)->applyFromArray($isitengah);
                        $sheet->getStyle('D' . $rowCount)->applyFromArray($isitengah);
                        $sheet->getStyle('E' . $rowCount)->applyFromArray($isitengah);
                        /*endstyle*/
                        $rowCount++;

                        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $sn);
                        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $get->code);
                        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $get->name);
                        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $get->satuan);
                        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $get->quantity);
                        $sheet->getStyle('A' . $rowCount . ':F' . $rowCount)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                        /*style*/
                        $sheet->getStyle('A' . $rowCount)->applyFromArray($isitengah);
                        $sheet->getStyle('B' . $rowCount)->applyFromArray($isikiri);
                        $sheet->getStyle('C' . $rowCount)->applyFromArray($isikiri);
                        $sheet->getStyle('D' . $rowCount)->applyFromArray($isitengah);
                        $sheet->getStyle('E' . $rowCount)->applyFromArray($isitengah);
                        /*endstyle*/
                    }
                    else{
                        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $sn);
                        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $get->code);
                        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $get->name);
                        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $get->satuan);
                        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $get->quantity);
                        $sheet->getStyle('A' . $rowCount . ':F' . $rowCount)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                        /*style*/
                        $sheet->getStyle('A' . $rowCount)->applyFromArray($isitengah);
                        $sheet->getStyle('B' . $rowCount)->applyFromArray($isikiri);
                        $sheet->getStyle('C' . $rowCount)->applyFromArray($isikiri);
                        $sheet->getStyle('D' . $rowCount)->applyFromArray($isitengah);
                        $sheet->getStyle('E' . $rowCount)->applyFromArray($isitengah);
                        /*endstyle*/
                    }
                    $sn++;
                    $settingpagebreak++;
                }
            }
            $rowCount++;
            $sn = 1;
        }

            //set title pada sheet (me rename nama sheet)
            $objPHPExcel->getActiveSheet()->setTitle('Excel Pertama');
 
            //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5          
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            //sesuaikan headernya 
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            //ubah nama file saat diunduh
            $namafilename = "Opname-".date("dmY")."";
            $isiheader = "Content-Disposition: attachment;filename=".$namafilename.".xlsx";
            header($isiheader);
            //unduh file
            $objWriter->save("php://output");
 


        // $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        // $objWriter->save($fileName);


        // download file
        //header("Content-Type: application/vnd.ms-excel");
        ///redirect(HTTP_UPLOAD_IMPORT_PATH.$fileName); 



        /*dari yaji

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Data Penelitian KEBAK.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
        */       
    }


    /**
     ////////////////////////////////////////////////////////////////////////
     ////////////////////////////////////////////////////////////////////////
     * KOMPLEKS CODINGAN UNTUK FUNGSI : UNTUK PRINT DOKUMENT PHP
     * TANGGAL PEMBUATAN              : 
     * KETERANGAN                     : 
     ////////////////////////////////////////////////////////////////////////
     ////////////////////////////////////////////////////////////////////////
     **/
    function dokumen(){
        $url = explode("/", $this->uri->uri_string());
        if(!$url[2]){
            header("Location: ".base_url()."items");
        }
        $kodebarang = $url[2];
        $data['kodebarang'] = $kodebarang;
        $q = "SELECT * FROM items WHERE code = ?";
        $run_q = $this->db->query($q, [$kodebarang]);
        
        if($run_q->num_rows() > 0){
            $hasilbarang = $run_q->result();
        }
        else{
            header("Location: ".base_url()."items");
        }
        foreach($hasilbarang as $get){
            $data['kategori'] = $get->kategori;
            $data['namabarang'] = $get->name;
            $data['satuan'] = $get->satuan;
        }
        $tanggal = date("d-m-Y h:i:s a");
        //$admin = $_SESSION['admin_name'];
        $dir = base_url();

        $pdf = new FPDF('l','mm','A5');
        $pdf->AddPage();
        // setting jenis font yang akan digunakan
        $pdf->SetTitle('Kartu Stok Persediaan');

        $pdf->Image($dir.'public/images/logo_black.png', 75,9,60,15);
        $pdf->SetFont('Arial','B',14);  //memilih font Arial Bold dengan ukuran 16pt
        $pdf->Text(75,32,"Kartu Stok Persediaan");

        $pdf->SetFont('Arial','B',9);  //memilih font Arial Bold dengan ukuran 16pt
        $pdf->Text(10,40,"Indeks");
        $pdf->Text(40,40,": ".$data['kategori']);
        $pdf->Text(10,45,"Kode Barang");
        $pdf->Text(40,45,": ".$kodebarang);
        $pdf->Text(10,50,"Jenis Barang");
        $pdf->Text(40,50,": ".$data['namabarang']);
        $pdf->Text(10,55,"Satuan");
        $pdf->Text(40,55,": ".$data['satuan']);

        $pdf->SetFont('Arial','B',8);
        $pdf -> SetY(60);
        $pdf -> SetX(10);
        $pdf->Cell(8,7,'No',1,'C',1);
        $pdf->Cell(35,7,'Tanggal',1,'L',2);
        $pdf->Cell(30,7,'Waktu',1,'L',2);
        // $pdf->Cell(42,7,'Unit',1,'L',2);
        $pdf->Cell(15,7,'Masuk',1,'C',1);
        $pdf->Cell(15,7,'Keluar',1,'C',1);
        $pdf->Cell(15,7,'Saldo',1,'C',1);
        $pdf->Cell(30,7,'Administrator',1,'C',1);

        $kartudb = $this->kartudb->getData($kodebarang);
        //$kartudb = $this->db->get('kartu_stok')->result();
        if($kartudb > 0){

        $no = 1;
        foreach ($kartudb as $rows){
            if($rows->transit == 0){
                $waktu = explode(" ", $rows->tanggal);
                $pdf->Ln();
                $pdf -> SetX(10);
                $pdf->Cell(8,7,$no,1,'C',1);
                $pdf->Cell(35,7,$waktu[0],1,'L',2);
                $pdf->Cell(30,7,$waktu[1],1,'L',2);
                // $pdf->Cell(42,7,$rows->unit,1,'L',2);
                $pdf->Cell(15,7,$rows->masuk,1,'C',1);
                $pdf->Cell(15,7,$rows->keluar,1,'C',1);
                $pdf->Cell(15,7,$rows->saldo,1,'C',1);
                $pdf->Cell(30,7,$rows->admin,1,'C',1);
                $no++;
            }

        }
    }
        else{//kalau tidak ada data
            $pdf->Ln();
            $pdf -> SetX(3);
            $pdf->Cell(200,7,'tidak ada data',1,'C',1);
        }

        $pdf->Output();
    }

    function semua(){
        $qr = "SELECT * FROM items";
        $run_q_datasemuacode1 = $this->db->query($qr);
        $run_q_datasemuacode = $run_q_datasemuacode1->result();
        $pdf = new FPDF('l','mm','A5');
        foreach($run_q_datasemuacode as $getnilai){
            $kodebarang = $getnilai->code;
            $data['kodebarang'] = $kodebarang;
            $data['kategori'] = $getnilai->kategori;
            $data['namabarang'] = $getnilai->name;
            $data['satuan'] = $getnilai->satuan;

            $tanggal = date("d-m-Y h:i:s a");
            $dir = base_url();

            $pdf->AddPage();
            $pdf->SetTitle('Kartu Stok Persediaan');

            $pdf->Image($dir.'public/images/logo_black.png', 75,9,60,15);

            $pdf->SetFont('Arial','B',14);  //memilih font Arial Bold dengan ukuran 16pt
            $pdf->Text(75,32,"Kartu Stok Persediaan");

            $pdf->SetFont('Arial','B',9);  //memilih font Arial Bold dengan ukuran 16pt
            $pdf->Text(10,40,"Indeks");
            $pdf->Text(40,40,": ".$data['kategori']);
            $pdf->Text(10,45,"Kode Barang");
            $pdf->Text(40,45,": ".$kodebarang);
            $pdf->Text(10,50,"Jenis Barang");
            $pdf->Text(40,50,": ".$data['namabarang']);
            $pdf->Text(10,55,"Satuan");
            $pdf->Text(40,55,": ".$data['satuan']);

            $pdf->SetFont('Arial','B',8);
            $pdf -> SetY(60);
            $pdf -> SetX(10);
            $pdf->Cell(8,7,'No',1,'C',1);
            $pdf->Cell(35,7,'Tanggal',1,'L',2);
            $pdf->Cell(30,7,'Aksi',1,'L',2);
            $pdf->Cell(42,7,'Unit',1,'L',2);
            $pdf->Cell(15,7,'Masuk',1,'C',1);
            $pdf->Cell(15,7,'Keluar',1,'C',1);
            $pdf->Cell(15,7,'Saldo',1,'C',1);
            $pdf->Cell(30,7,'Administrator',1,'C',1);

            $kartudb = $this->kartudb->getData($kodebarang);
            //$kartudb = $this->db->get('kartu_stok')->result();
            if($kartudb > 0){
                $no = 1;
                foreach ($kartudb as $rows){
                    if($rows->transit == 0){
                        $pdf->Ln();
                        $pdf->SetX(10);
                        $pdf->Cell(8,7,$no,1,'C',1);
                        $pdf->Cell(35,7,$rows->tanggal,1,'L',2);
                        $pdf->Cell(30,7,$rows->aksi,1,'L',2);
                        $pdf->Cell(42,7,$rows->unit,1,'L',2);
                        $pdf->Cell(15,7,$rows->masuk,1,'C',1);
                        $pdf->Cell(15,7,$rows->keluar,1,'C',1);
                        $pdf->Cell(15,7,$rows->saldo,1,'C',1);
                        $pdf->Cell(30,7,$rows->admin,1,'C',1);
                        $no++;
                    }

                }
            }
            else{//kalau tidak ada data
                $pdf->Ln();
                $pdf -> SetX(3);
                $pdf->Cell(200,7,'tidak ada data',1,'C',1);
            }

        }
        $pdf->Output();
    }
}
