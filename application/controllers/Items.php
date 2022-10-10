<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Items
 *
 * @author Amir <amirsanni@gmail.com>
 * @date 31st Dec, 2015
 */
class Items extends CI_Controller{

    public function __construct(){
        parent::__construct();
        
        $this->genlib->checkLogin();
        $this->load->library('pdf');
        
        $this->load->model(['item']);
    }
    
    /**
     * 
     */
    public function index(){

        $itemData['items'] = $this->item->getActiveKategori('kategori', 'ASC');//get items with at least one qty left, to be used when doing a new transaction
        $itemData['units'] = $this->item->getActiveUnit('id', 'ASC');//get items with at least one qty left, to be used when doing a new transaction

        $data['pageContent'] = $this->load->view('items/items', $itemData, TRUE);
        $data['pageTitle'] = "Alat Tulis Kantor";

        /*buat dashboard*/
        $temp = $this->kurang();
        $data['item'] = count($temp);
        $data['datakurang'] = $temp;
        /*end buat dashboard*/

        $this->load->view('main', $data);
    }


/**
 ////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////
 * KOMPLEKS CODINGAN UNTUK FUNGSI : PRINT DOKUMENT
 * TANGGAL PEMBUATAN              : 16 SEPT 2018
 * KETERANGAN                     : -
 ////////////////////////////////////////////////////////////////////////
 ////////////////////////////////////////////////////////////////////////
 **/

function dokumen(){
        //logika web
    $tanggal = date("d-m-Y h:i:s a");
    $admin = $_SESSION['admin_name'];
    $dir = base_url();
    $file_direktori = $this->config->file_direktori();
    $pdf = new FPDF('l','mm','A5');
    $pdf->AddPage();
        // setting jenis font yang akan digunakan
    $pdf->SetTitle('Laporan Pengadaan Barang');

    $pdf->Image($dir.'public/images/logo_black.png', 75,9,60,15);
        //DARI TRYOUT
        $pdf->SetFont('Arial','B',14);  //memilih font Arial Bold dengan ukuran 16pt
        $pdf->Text(70,32,"Laporan Pengadaan Barang");

        $pdf->SetFont('Arial','B',9);  //memilih font Arial Bold dengan ukuran 16pt
        $pdf->Text(10,40,"Tanggal   :");
        $pdf->Text(30,40,$tanggal);

        $pdf->Text(127,40,"Administrator : ");
        $pdf->Text(150,40,$admin);

        $pdf->SetFont('Arial','B',8);
        $pdf -> SetY(45);
        $pdf -> SetX(3);
        $pdf->Cell(8,7,'No',1,'C',1);
        $pdf->Cell(28,7,'Barcode',1,'L',2);
        $pdf->Cell(30,7,'Kode Barang',1,'L',2);
        $pdf->Cell(42,7,'Nama Barang',1,'L',2);
        $pdf->Cell(32,7,'Kategori',1,'C',1);
        $pdf->Cell(15,7,'Satuan',1,'C',1);
        $pdf->Cell(15,7,'Stok Ada',1,'C',1);
        $pdf->Cell(17,7,'Stok Aman',1,'C',1);
        $pdf->Cell(15,7,'Stok Peng.',1,'C',1);

        $itemdb = $this->db->get('items')->result();
        $no = 1;
        foreach ($itemdb as $row){
            $stokaman = $row->minimal + ceil($row->minimal * 0.1);
            if(($row->quantity <= $row->minimal || ($row->quantity <= ($stokaman) && $row->quantity >= $row->minimal)) && ($row->pengadaan == 1)){
                $pdf->Ln();
                $pdf -> SetX(3);
                $pdf->SetFont('Arial','',8);
                $pdf->Cell(8,7,$no,1,'C',1);
                $pdf->Cell(28,7,$row->barcode,1,'L',2);
                $pdf->Cell(30,7,$row->code,1,'L',2);
                $pdf->Cell(42,7,$row->name,1,'L',2);
                $pdf->Cell(32,7,$row->kategori,1,'C',1);
                $pdf->Cell(15,7,$row->satuan,1,'C',1);
                $pdf->Cell(15,7,$row->quantity,1,'C',1);
                $pdf->Cell(17,7,$row->minimal,1,'C',1);
                $pdf->Cell(15,7,$row->banyak,1,'C',1);
                $no++;
            }
        }
        $filename= $file_direktori.'dokumen/pengadaan/'.$tanggal.'.pdf';
        $this->item->laporanPengadaan($admin, $filename);
        $pdf->Output($filename,'F');
    }

    
    /**
     * FUNGSI BUATAN SENDIRI -> TUJUAN UNTUK MENGETAHUI JUMLAH BARANG YANG KURANG
     */
    public function suspend(){
        $this->genlib->ajaxOnly();
        $barang_id = $this->input->post('_aId');
        $new_status = $this->genmod->gettablecol('items', 'pengadaan', 'id', $barang_id) == 1 ? 0 : 1;
        $done = $this->item->suspend($barang_id, $new_status);
        
        $json['status'] = $done ? 1 : 0;
        $json['_ns'] = $new_status;
        $json['_aId'] = $barang_id;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }




    public function pengadaanBarang(){
        $this->genlib->ajaxOnly();
        $barang_id = $this->input->post('_aId');
        $jumlah    = $this->input->post('jumlah');


        $done = $this->item->suspendPengadaan($barang_id, $jumlah);
        
        $json['status']         = $done ? 1 : 0;
        $json['_jumlah']        = $jumlah;
        $json['_aId']           = $barang_id;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }





    /////////////
    public function kurang(){
        $this->load->model(['item']);
        $allItems = $this->item->getAll("name", "ASC", 0, "");

        $kurang = []; //mencari data yang kurang
        $no = 0;
        foreach($allItems as $get):
            if($get->quantity <= $get->minimal){
                $kurang[$no] = $get->name;
            }
            $no++;
        endforeach;
        return $kurang;
    }


    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */


    public function pengadaanlilt(){
        $this->genlib->ajaxOnly();
        
        $this->load->helper('text');
        
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        
        //count the total number of items in db
       
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri

        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        

        /**
         *
         * FUNGSI INI UNTUK MENGHITUNG JUMLAH DATA UNTUK PAGINATION sehingga data yang ditampilkan hanya untuk barang yang dalam kategori warning/danger
         *
         */
        $data['allItems']  = $this->item->getAll($orderBy, $orderFormat, $start, '');
        $totalItems = 0;
        foreach($data['allItems'] as $get):
            $stokaman = $get->minimal + ceil($get->minimal * 0.1);
            $penambahan = $stokaman - $get->quantity;
            if($get->quantity <= $get->minimal || ($get->quantity <= ($stokaman) && $get->quantity >= $get->minimal)){
                $totalItems++;
            }
        endforeach;

        $config = $this->genlib->setPaginationConfig($totalItems, "items/pengadaanlilt", $limit, ['onclick'=>'return lilt(this.href);']);
        
        $this->pagination->initialize($config);//initialize the library class
        //end

        //get all items from db
        
        $data['range']     = $totalItems > 0 ? "Showing " . ($start+1) . "-" . ($start + count($data['allItems'])) . " of " . $totalItems : "";
        $data['links']     = $this->pagination->create_links();//page links
        $data['sn']        = $start+1;
        $data['cum_total'] = $this->item->getItemsCumTotal();
        
        $json['itemsListTable'] = $this->load->view('items/pengadaanlisttable', $data, TRUE);//get view with populated items table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    /////////////////////////////////////////
    /////////////////////////////////////////





    /////////////////////////////////////////
    /////////////////////////////////////////

   public function datatransitdefisit(){
        $this->genlib->ajaxOnly();

        $this->load->library('form_validation');
        //$this->form_validation->set_error_delimiters('', '');
        $itemId = $this->input->post('_iId', TRUE);
        $itemQuantity = $this->input->post('itemQuantity', TRUE);
        $itemCode = $this->input->post('itemCode', TRUE);

        $updated = $this->item->prosespindahitem($itemId, $itemQuantity, $itemCode);

        if($updated){

            $json['status'] = 1;
        }
        else{
            $json['status'] = 0;
        }
       
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }







      /**
     * "lilt" = "load Items List Table"
     */

      public function lilt(){
        $this->genlib->ajaxOnly();
        
        $this->load->helper('text');
        
        $temp = $this->kurang();
        $data['datakurang'] = $temp;

        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";

        //count the total number of items in db
        $totalItems = $this->db->count_all('items');
        
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri

        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalItems, "items/lilt", $limit, ['onclick'=>'return lilt(this.href);']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        //get all items from db
        $data['allItems'] = $this->item->getAll($orderBy, $orderFormat, $start, $limit);
        $data['range'] = $totalItems > 0 ? "Showing " . ($start+1) . "-" . ($start + count($data['allItems'])) . " of " . $totalItems : "";
        $data['links'] = $this->pagination->create_links();//page links
        $data['sn'] = $start+1;
        $data['cum_total'] = $this->item->getItemsCumTotal();
        
        $json['itemsListTable'] = $this->load->view('items/itemslisttable', $data, TRUE);//get view with populated items table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function obatlilt(){
        $this->genlib->ajaxOnly();
        
        $this->load->helper('text');
        
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        
        //count the total number of items in db
        $totalItems = $this->db->count_all('obat');
        
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri

        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalItems, "items/obatlilt", $limit, ['onclick'=>'return lilt(this.href);']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        //get all items from db
        $data['allItems'] = $this->item->getAll($orderBy, $orderFormat, $start, $limit);
        $data['range'] = $totalItems > 0 ? "Showing " . ($start+1) . "-" . ($start + count($data['allItems'])) . " of " . $totalItems : "";
        $data['links'] = $this->pagination->create_links();//page links
        $data['sn'] = $start+1;
        $data['cum_total'] = $this->item->getItemsCumTotal();
        
        $json['itemsListTable'] = $this->load->view('obat/obatlisttable', $data, TRUE);//get view with populated items table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    
    
    public function add(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('itemName', 'Item name', ['required', 'trim', 'max_length[80]', 'is_unique[items.name]'],
            ['required'=>"required"]);
        $this->form_validation->set_rules('itemCode', 'Item Code', ['required', 'trim', 'max_length[20]', 'is_unique[items.code]'], 
            ['required'=>"required", 'is_unique'=>"There is already an item with this code"]);
        
        if($this->form_validation->run() !== FALSE){
            $this->db->trans_start();//start transaction
            
            /**
             * insert info into db
             * function header: add($itemName, $itemQuantity, $itemPrice, $itemDescription, $itemCode)
             */
            $insertedId = $this->item->add(
                set_value('itemName'), 
                set_value('itemKategori'), 
                set_value('itemCode'), 
                set_value('itemNup'), 
                set_value('itemKondisi'), 
                set_value('itemUnit'),
                set_value('barCode'), 
                set_value('itemDescription'),
                set_value('itemTahun'),
                set_value('itemLokasi'),
                set_value('itemPIC')
                
            );

            // $itemName = set_value('itemName');
            // $itemQty = set_value('itemQuantity');
            // $itemPrice = "&#8358;".number_format(set_value('itemPrice'), 2);
            
            // //insert into eventlog
            // //function header: addevent($event, $eventRowId, $eventDesc, $eventTable, $staffId)
            // $desc = "Addition of {$itemQty} quantities of a new item '{$itemName}' with a unit price of {$itemPrice} to stock";
            
            // $insertedId ? $this->genmod->addevent("Creation of new item", $insertedId, $desc, "items", $this->session->admin_id) : "";
            
            $this->db->trans_complete();
            
            $json = $this->db->trans_status() !== FALSE ? 
            ['status'=>1, 'msg'=>"Item successfully added"] 
            : 
            ['status'=>0, 'msg'=>"Oops! Unexpected server error! Please contact administrator for help. Sorry for the embarrassment"];
        }
        
        else{
            //return all error messages
            $json = $this->form_validation->error_array();//get an array of all errors
            
            $json['msg'] = "Silahkan lengkapi data Anda";
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
     * Primarily used to check whether an item already has a particular random code being generated for a new item
     * @param type $selColName
     * @param type $whereColName
     * @param type $colValue
     */
    public function gettablecol($selColName, $whereColName, $colValue){
        $a = $this->genmod->gettablecol('items', $selColName, $whereColName, $colValue);
        
        $json['status'] = $a ? 1 : 0;
        $json['colVal'] = $a;
        
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
     * 
     */

    public function lihatbarcode(){
        $json['status'] = 0;
        $barcode = $this->input->post('barcode', TRUE);
        if($barcode){
            $item_info = $this->item->getItemInfo(['barcode'=>$barcode], ['code']);
            if($item_info){
                $json['nilaicode'] = $item_info->code;
                $json['status'] = 1;
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }





    public function gcoandqty(){
        $json['status'] = 0;
        
        $itemCode = $this->input->get('_iC', TRUE);
        
        if($itemCode){
            $item_info = $this->item->getItemInfo(['code'=>$itemCode], ['quantity', 'unitPrice', 'description','transit','aktivasi']);

            if($item_info){
                $json['availQty'] = (int)$item_info->quantity;
                $json['unitPrice'] = $item_info->unitPrice;
                $json['transit'] = $item_info->transit;
                $json['aktivasi'] = $item_info->aktivasi;
                $json['description'] = $item_info->description;
                $json['status'] = 1;
            }
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
    
    
    public function updatestock(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('_iId', 'Item ID', ['required', 'trim', 'numeric'], ['required'=>"required"]);
        $this->form_validation->set_rules('_upType', 'Update type', ['required', 'trim', 'in_list[newStock,deficit]'], ['required'=>"required"]);
        $this->form_validation->set_rules('qty', 'Quantity', ['required', 'trim', 'numeric'], ['required'=>"required"]);
        $this->form_validation->set_rules('desc', 'Update Description', ['required', 'trim']);
        
        if($this->form_validation->run() !== FALSE){
            //update stock based on the update type
            $updateType = set_value('_upType');
            $itemId = set_value('_iId');
            $qty = set_value('qty');
            $desc = set_value('desc');
            $jenis = set_value('jenis');
            
            $this->db->trans_start();
            
            $updated = $updateType === "deficit" 
            ? 
            $this->item->deficit($itemId, $qty, $desc) 
            : 
            $this->item->newstock($itemId, $qty, $jenis);
            
            //add event to log if successful
            $stockUpdateType = $updateType === "deficit" ? "Deficit" : "New Stock";
            
            $event = "Stock Update ($stockUpdateType)";
            
            $action = $updateType === "deficit" ? "removed from" : "added to";//action that happened
            
            $eventDesc = "<p>{$qty} quantities of {$this->genmod->gettablecol('items', 'name', 'id', $itemId)} was {$action} stock</p>
            Reason: <p>{$desc}</p>";
            
            //function header: addevent($event, $eventRowId, $eventDesc, $eventTable, $staffId)
            $updated ? $this->genmod->addevent($event, $itemId, $eventDesc, "items", $this->session->admin_id) : "";
            
            $this->db->trans_complete();//end transaction
            
            $json['status'] = $this->db->trans_status() !== FALSE ? 1 : 0;
            $json['msg'] = $updated ? "Stock successfully updated" : "Unable to update stock at this time. Please try again later";
        }
        
        else{
            $json['status'] = 0;
            $json['msg'] = "One or more required fields are empty or not correctly filled";
            $json = $this->form_validation->error_array();
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
   
   public function edit(){
    $this->genlib->ajaxOnly();

    $this->load->library('form_validation');

    $this->form_validation->set_error_delimiters('', '');

    $this->form_validation->set_rules('_iId', 'Item ID', ['required', 'trim', 'numeric']);
    $this->form_validation->set_rules('itemName', 'Item Name', ['required', 'trim', 
        'callback_crosscheckName['.$this->input->post('_iId', TRUE).']'], ['required'=>'required']);
    $this->form_validation->set_rules('itemCode', 'Item Code', ['required', 'trim', 
        'callback_crosscheckCode['.$this->input->post('_iId', TRUE).']'], ['required'=>'required']);
    $this->form_validation->set_rules('itemPrice', 'Item Unit Price', ['required', 'trim', 'numeric']);
    $this->form_validation->set_rules('itemDesc', 'Item Description', ['trim']);

    if($this->form_validation->run() !== FALSE){
        $itemId = set_value('_iId');
        $itemDesc = set_value('itemDesc');
        $itemPrice = set_value('itemPrice');
        $itemName = set_value('itemName');
        $barCode = set_value('barCode');
        $itemKategori = set_value('itemKategori');
        $itemQuantity = set_value('itemQuantity');
        $itemMinimal = set_value('itemMinimal');
        $itemSatuan = set_value('itemSatuan');
        $itemLokasi = set_value('itemLokasi');

        $itemCode = $this->input->post('itemCode', TRUE);

            //update item in db
        $updated = $this->item->edit($itemId, $itemName, $itemDesc, $itemPrice, $itemKategori, $itemQuantity, $itemMinimal, $barCode,$itemSatuan,$itemLokasi);

        $json['status'] = $updated ? 1 : 0;

            //add event to log
            //function header: addevent($event, $eventRowId, $eventDesc, $eventTable, $staffId)
        $desc = "Details of item with code '$itemCode' was updated";

        $this->genmod->addevent("Item Update", $itemId, $desc, 'items', $this->session->admin_id);
    }

    else{
        $json['status'] = 0;
        $json = $this->form_validation->error_array();
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

   public function crosscheckName($itemName, $itemId){
        //check db to ensure name was previously used for the item we are updating
    $itemWithName = $this->genmod->getTableCol('items', 'id', 'name', $itemName);

        //if item name does not exist or it exist but it's the name of current item
    if(!$itemWithName || ($itemWithName == $itemId)){
        return TRUE;
    }

        else{//if it exist
            $this->form_validation->set_message('crosscheckName', 'There is an item with this name');

            return FALSE;
        }
    }
    
    /*
    ********************************************************************************************************************************
    *****************************************************************************************************
    */
    
    
    /**
     * 
     * @param type $item_code
     * @param type $item_id
     * @return boolean
     */
    public function crosscheckCode($item_code, $item_id){
        //check db to ensure item code was previously used for the item we are updating
        $item_with_code = $this->genmod->getTableCol('items', 'id', 'code', $item_code);
        
        //if item code does not exist or it exist but it's the code of current item
        if(!$item_with_code || ($item_with_code == $item_id)){
            return TRUE;
        }
        
        else{//if it exist
            $this->form_validation->set_message('crosscheckCode', 'There is an item with this code');

            return FALSE;
        }
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    
    public function delete(){
        $this->genlib->ajaxOnly();
        
        $json['status'] = 0;
        $item_id = $this->input->post('i', TRUE);
        
        if($item_id){
            $this->db->where('id', $item_id)->delete('items');
            
            $json['status'] = 1;
        }
        
        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    public function laporanlilt(){
        $this->genlib->ajaxOnly();
        
        $this->load->helper('text');
        
        //set the sort order
        $orderBy                = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "id";
        $orderFormat            = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        
        //count the total number of items in db
        $totalItems             = $this->db->count_all('laporan_pengadaan');
        
        $this->load->library('pagination');
        
        $pageNumber             = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri

        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalItems, "items/laporanlilt", $limit, ['onclick'=>'return lilt(this.href);']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        //get all items from db
        $data['allItems']       = $this->item->laporangetAll($orderBy, $orderFormat, $start, $limit);
        $data['range']          = $totalItems > 0 ? "Showing " . ($start+1) . "-" . ($start + count($data['allItems'])) . " of " . $totalItems : "";
        $data['links']          = $this->pagination->create_links();//page links
        $data['sn']             = $start+1;
        $data['cum_total']      = $this->item->getItemsCumTotal();
        
        $json['itemsListTable'] = $this->load->view('items/laporanlisttable', $data, TRUE);//get view with populated items table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    public function deletelaporan(){
        $this->genlib->ajaxOnly();
        
        $json['status']         = 0;
        $item_id                = $this->input->post('i', TRUE);
        $alamatDokumen          = $this->input->post('alamatDokumen', TRUE);
        
        if($item_id){
            $this->db->where('id', $item_id)->delete('laporan_pengadaan');
            if (file_exists($alamatDokumen)) {
                unlink($alamatDokumen);
                $json['status'] = 1;
            } 
        }
        
        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    /**
     ///////////////////////////////////////////////////////////////////////////////////////////////
     ///////////////////////////////////////////////////////////////////////////////////////////////
     * KOMPLEKS CODINGAN UNTUK FUNGSI : KATEGORI ITEM
     * TANGGAL PEMBUATAN              : 15 SEPTEMBER 2019
     * KETERANGAN                     : FUNGSI BARU UNTUK MENAMBAHKAN KATEGORI PADA ITEM BARANG
     //////////////////////////////////////////////////////////////////////////////////////////////
     //////////////////////////////////////////////////////////////////////////////////////////////
     **/

    public function addkategori(){ //menambah kategori
        $this->genlib->ajaxOnly();
        $kategori = $this->input->post('kategori');
        $cek = $this->db->query("SELECT * FROM kategori where kategori='$kategori'")->num_rows();
        if ($cek <= 0){
            $done = $this->item->tambahKategori($kategori);
        }
        $json['status'] = $done ? 1 : 0;      

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function kategorililt(){ //menampilkan kategori dalam tabel
        $this->genlib->ajaxOnly();

        $this->load->helper('text');

            //set the sort order
        $orderBy                = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "id";
        $orderFormat            = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
            //count the total number of items in db
        $totalItems             = $this->db->count_all('kategori');

        $this->load->library('pagination');

        $pageNumber             = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri

        $limit                  = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start                  = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalItems, "items/kategorililt", $limit, ['onclick'=>'return lilt(this.href);']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        //get all items from db
        $data['allItems']       = $this->item->kategorigetAll($orderBy, $orderFormat, $start, $limit);
        $data['range']          = $totalItems > 0 ? "Showing " . ($start+1) . "-" . ($start + count($data['allItems'])) . " of " . $totalItems : "";
        $data['links']          = $this->pagination->create_links();//page links
        $data['sn']             = $start+1;
        //$data['cum_total']      = $this->item->getItemsCumTotal();
        
        $json['itemsListTable'] = $this->load->view('items/kategorilisttable', $data, TRUE);//get view with populated items table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function deletekategori(){
        $this->genlib->ajaxOnly();
        $json['status']         = 0;
        $item_id                = $this->input->post('i', TRUE);
        if($item_id){
            $this->db->where('id', $item_id)->delete('kategori');
            $json['status'] = 1;
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }



    /**
     ///////////////////////////////////////////////////////////////////////////////////////////////
     ///////////////////////////////////////////////////////////////////////////////////////////////
     * KOMPLEKS CODINGAN UNTUK FUNGSI : UNIT SATUAN
     * TANGGAL PEMBUATAN              : 19 OKT 2019
     * KETERANGAN                     : FUNGSI BARU UNTUK MENAMBAHKAN UNIT
     //////////////////////////////////////////////////////////////////////////////////////////////
     //////////////////////////////////////////////////////////////////////////////////////////////
     **/

    public function addunit(){ //menambah kategori
        $this->genlib->ajaxOnly();
        $unit = $this->input->post('unit');
        $cek = $this->db->query("SELECT * FROM satuan_unit where satuan='$unit'")->num_rows();
        if ($cek <= 0){
            $done = $this->item->tambahUnit($unit);
        }
        $json['status'] = $done ? 1 : 0;      

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    public function unitlilt(){ //menampilkan kategori dalam tabel
        $this->genlib->ajaxOnly();

        $this->load->helper('text');

            //set the sort order
        $orderBy                = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "id";
        $orderFormat            = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
            //count the total number of items in db
        $totalItems             = $this->db->count_all('satuan_unit');

        $this->load->library('pagination');

        $pageNumber             = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri

        $limit                  = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start                  = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalItems, "items/unitlilt", $limit, ['onclick'=>'return lilt(this.href);']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        //get all items from db
        $data['allItems']       = $this->item->unitgetAll($orderBy, $orderFormat, $start, $limit);
        $data['range']          = $totalItems > 0 ? "Showing " . ($start+1) . "-" . ($start + count($data['allItems'])) . " of " . $totalItems : "";
        $data['links']          = $this->pagination->create_links();//page links
        $data['sn']             = $start+1;
        //$data['cum_total']      = $this->item->getItemsCumTotal();
        
        $json['itemsListTable'] = $this->load->view('items/unitlisttable', $data, TRUE);//get view with populated items table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function deleteunit(){
        $this->genlib->ajaxOnly();
        $json['status']         = 0;
        $item_id                = $this->input->post('i', TRUE);
        if($item_id){
            $this->db->where('id', $item_id)->delete('satuan_unit');
            $json['status'] = 1;
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    /**
     ////////////////////////////////////////////////////////////////////////
     ////////////////////////////////////////////////////////////////////////
     * KOMPLEKS CODINGAN UNTUK FUNGSI : AKTIVASI DATA BARANG
     * TANGGAL PEMBUATAN              : 16 SEPTEMBER 2018
     * KETERANGAN                     : -
     ////////////////////////////////////////////////////////////////////////
     ////////////////////////////////////////////////////////////////////////
     **/

    public function pindahitem(){
        $this->genlib->ajaxOnly();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('_iId', 'Item ID', ['required', 'trim', 'numeric']);
        $this->form_validation->set_rules('itemName', 'Item Name', ['required', 'trim'], ['required'=>'required']);
        $this->form_validation->set_rules('itemCode', 'Item Code', ['required', 'trim'], ['required'=>'required']);
        $this->form_validation->set_rules('itemDesc', 'Item Description', ['trim']);
        
        if($this->form_validation->run() !== FALSE){
            $itemId = set_value('_iId');
            $itemDesc = set_value('itemDesc');
            $itemPrice = set_value('itemPrice');
            $itemName = set_value('itemName');
            $barCode = set_value('barCode');
            $itemKategori = set_value('itemKategori');
            $itemQuantity = set_value('itemQuantity');
            $itemMinimal = set_value('itemMinimal');
            $itemSatuan = set_value('itemSatuan');
            $itemLokasi = set_value('itemLokasi');

            $itemCode = $this->input->post('itemCode', TRUE);
            
            //update item in db
            $updated = $this->item->pindahitem($itemId, $itemName, $itemDesc, $itemPrice, $itemKategori, $itemQuantity, $itemMinimal, $barCode,$itemSatuan,$itemLokasi, $itemCode);
            
            $json['status'] = $updated ? 1 : 0;
            
            //add event to log
            //function header: addevent($event, $eventRowId, $eventDesc, $eventTable, $staffId)
            $desc = "Details of item with code '$itemCode' was updated";
            
            $this->genmod->addevent("Item Update", $itemId, $desc, 'items', $this->session->admin_id);
        }
        
        else{
            $json['status'] = 0;
            $json = $this->form_validation->error_array();
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }



 

}