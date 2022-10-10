<?php

defined('BASEPATH') OR exit('');

/**
 * Description of Transaction
 *
 * @author Amir <amirsanni@gmail.com>
 * @date 27th RabAwwal, 1437A.H (8th Jan., 2016)
 */
class Transaction extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     */

    /**
     * Get all transactions
     * @param type $orderBy
     * @param type $orderFormat
     * @param type $start
     * @param type $limit
     * @return boolean
     */
    public function getAll($orderBy, $orderFormat, $start, $limit) {
        if ($this->db->platform() == "sqlite3") {
            $q = "SELECT transactions.ref, transactions.totalMoneySpent, transactions.modeOfPayment, transactions.staffId,
                transactions.transDate, transactions.lastUpdated, transactions.amountTendered, transactions.changeDue,
                admin.first_name || ' ' || admin.last_name AS 'staffName', SUM(transactions.quantity) AS 'quantity',
                transactions.cust_name, transactions.cust_phone, transactions.cust_email
                FROM transactions
                LEFT OUTER JOIN admin ON transactions.staffId = admin.id
                GROUP BY ref
                ORDER BY {$orderBy} {$orderFormat}
                LIMIT {$limit} OFFSET {$start}";

            $run_q = $this->db->query($q);
        }
        else {
            $this->db->limit($limit, $start);
            $this->db->order_by($orderBy, $orderFormat);
        
            $run_q = $this->db->get('transactions');
        }

        if($run_q->num_rows() > 0){
            return $run_q->result();
        }
        
        else{
            return FALSE;
        }
    }

    /*
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     */

    /**
     * 
     * @param type $_iN item Name
     * @param type $_iC item Code
     * @param type $desc Desc
     * @param type $q quantity bought
     * @param type $_up unit price
     * @param type $_tp total price
     * @param type $_tas total amount spent
     * @param type $_at amount tendered
     * @param type $_cd change due
     * @param type $_mop mode of payment
     * @param type $_tt transaction type whether (sale{1} or return{2})
     * @param type $ref
     * @param float $_va VAT Amount
     * @param float $_vp VAT Percentage
     * @param float $da Discount Amount
     * @param float $dp Discount Percentage
     * @param {string} $cn Customer Name
     * @param {string} $cp Customer Phone
     * @param {string} $ce Customer Email
     * @return boolean
     */
    public function add($_iN, $_iC, $desc, $q, $jenis, $cn, $ref, $harga) {
        $totalPrice = $harga*$q;

        $data = ['itemName' => $_iN, 'itemCode' => $_iC, 'description' => $desc, 'quantity' => $q, 'cust_name'=>$cn, 'ref'=>$ref, 'pengguna'=>$_SESSION['admin_name'], 'unitPrice'=>$harga, 'totalPrice'=>$totalPrice];

        if($jenis == "transit"){
            $data['transit'] = 1;
        }
        //set the datetime based on the db driver in use
        $this->db->platform() == "sqlite3" ?
            $this->db->set('transDate', "datetime('now')", FALSE) :
            $this->db->set('transDate', "NOW()", FALSE);

        $this->db->insert('transactions', $data);

        $keluar = $q;

        if ($this->db->affected_rows()) {
            //mulai logika
            $q = "SELECT * FROM items WHERE code = ?";
            $run_q = $this->db->query($q, [$_iC]);
        
            if($run_q->num_rows() > 0){
                $hasil = $run_q->result();
            }
            foreach($hasil as $get){
               $nilai['kategori'] = $get->kategori;
               $jumlah = $get->quantity;
            }
            if($jenis == "transit"){
                $saldo = $jumlah;
            }
            else{
                $saldo = $jumlah-$keluar;
            }
            $data2 = ['id_barang'=>$_iC, 'nama_barang'=>$_iN, 'jenis'=>$nilai['kategori'], 'aksi'=>"Pemakaian", 'saldo'=>$saldo, 'keluar' => $keluar, 'unit'=>$cn, 'admin'=> $_SESSION['admin_name']];

            if($jenis == "transit"){
                $data2['transit']=1;
            }

            $this->db->platform() == "mysqli" 
                 ? 
             $this->db->set('tanggal', "datetime('now')", FALSE) 
                 : 
             $this->db->set('tanggal', "NOW()", FALSE);

             $this->db->insert('kartu_stok', $data2);

            return $this->db->insert_id();
        }
        else {
            return FALSE;
        }
    }


    public function transitadd($_iN, $_iC, $desc, $q,$jenis, $ref) {
        $data = ['itemName' => $_iN, 'itemCode' => $_iC, 'description' => $desc, 'quantity' => $q,'ref'=>$ref, 'pengguna'=>$_SESSION['admin_name']];

        if($jenis == "transit"){
            $data['transit'] = 1;
        }

        //set the datetime based on the db driver in use
        $this->db->platform() == "sqlite3" ?
        $this->db->set('transDate', "datetime('now')", FALSE) :
        $this->db->set('transDate', "NOW()", FALSE);

        $this->db->insert('transactions', $data);

        $keluar = $q;

        if ($this->db->affected_rows()) {
            // //mulai logika
            $q = "SELECT * FROM items WHERE code = ?";
            $run_q = $this->db->query($q, [$_iC]);

            if($run_q->num_rows() > 0){
                $hasil = $run_q->result();
            }
            foreach($hasil as $get){
               $nilai['kategori'] = $get->kategori;
               $jumlah = $get->quantity;
           }

           $data2 = ['id_barang'=>$_iC, 'nama_barang'=>$_iN, 'jenis'=>$nilai['kategori'], 'aksi'=>"Pemakaian", 'saldo'=>$jumlah-$keluar, 'keluar' => $keluar, 'unit'=>$cn, 'admin'=> $_SESSION['admin_name']];
           if($jenis == "transit"){
                $data2['transit'] = 1;
            }

            $this->db->platform() == "mysqli" 
            ? 
            $this->db->set('tanggal', "datetime('now')", FALSE) 
            : 
            $this->db->set('tanggal', "NOW()", FALSE);

            $this->db->insert('kartu_stok', $data2);

            // //end
        return $this->db->insert_id();
    }
    else {
        return FALSE;
    }
}

    /*
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     */

    /**
     * Primarily used t check whether a prticular ref exists in db
     * @param type $ref
     * @return boolean
     */
    public function isRefExist($ref) {
        $q = "SELECT DISTINCT ref FROM transactions WHERE ref = ?";

        $run_q = $this->db->query($q, [$ref]);

        if ($run_q->num_rows() > 0) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    /*
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     */

    public function transSearch($value) {
        $q = "SELECT * FROM transactions 
            WHERE 
            itemName LIKE '%".$this->db->escape_like_str($value)."%'
            || 
            ref LIKE '%".$this->db->escape_like_str($value)."%'
            || 
            cust_name LIKE '%".$this->db->escape_like_str($value)."%'";
        
        $run_q = $this->db->query($q, [$value, $value]);

        if ($run_q->num_rows() > 0) {
            return $run_q->result();
        }
        else {
            return FALSE;
        }
    }

    public function transsearchdate($value) {
        $q = "SELECT * FROM transactions 
            WHERE 
            transDate  LIKE '%".$this->db->escape_like_str($value)."%'";
        
        $run_q = $this->db->query($q, [$value, $value]);

        if ($run_q->num_rows() > 0) {
            return $run_q->result();
        }
        else {
            return FALSE;
        }
    }

    /*
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     */

    /**
     * Get all transactions with a particular ref
     * @param type $ref
     * @return boolean
     */
    public function gettransinfo($ref) {
        $q = "SELECT * FROM transactions WHERE ref = ?";

        $run_q = $this->db->query($q, [$ref]);

        if ($run_q->num_rows() > 0) {
            return $run_q->result_array();
        }
        else {
            return FALSE;
        }
    }

    /*
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     */

    /**
     * selects the total number of transactions done so far
     * @return boolean
     */
    public function totalTransactions() {
        $q = "SELECT count(DISTINCT REF) as 'totalTrans' FROM transactions";

        $run_q = $this->db->query($q);

        if ($run_q->num_rows() > 0) {
            foreach ($run_q->result() as $get) {
                return $get->totalTrans;
            }
        }
        else {
            return FALSE;
        }
    }

    /*
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     */

    /**
     * Calculates the total amount earned today
     * @return boolean
     */
    public function totalEarnedToday() {
        $q = "SELECT totalMoneySpent FROM transactions WHERE DATE(transDate) = CURRENT_DATE GROUP BY ref";

        $run_q = $this->db->query($q);

        if ($run_q->num_rows()) {
            $totalEarnedToday = 0;

            foreach ($run_q->result() as $get) {
                $totalEarnedToday += $get->totalMoneySpent;
            }

            return $totalEarnedToday;
        }
        else {
            return FALSE;
        }
    }

    /*
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     */

    //Not in use yet
    public function totalEarnedOnDay($date) {
        $q = "SELECT SUM(totalPrice) as 'totalEarnedToday' FROM transactions WHERE DATE(transDate) = {$date}";

        $run_q = $this->db->query($q);

        if ($run_q->num_rows() > 0) {
            foreach ($run_q->result() as $get) {
                return $get->totalEarnedToday;
            }
        }
        else {
            return FALSE;
        }
    }

    /*
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     * *******************************************************************************************************************************
     */
    
    public function getDateRange($hasilUnit,$from_date, $to_date){
        if ($this->db->platform() == "sqlite3") {
            $q = "SELECT transactions.ref, transactions.totalMoneySpent, transactions.modeOfPayment, transactions.staffId,
                transactions.transDate, transactions.lastUpdated, transactions.amountTendered, transactions.changeDue,
                admin.first_name || ' ' || admin.last_name AS 'staffName', SUM(transactions.quantity) AS 'quantity',
                transactions.cust_name, transactions.cust_phone, transactions.cust_email
                FROM transactions
                LEFT OUTER JOIN admin ON transactions.staffId = admin.id
                WHERE 
                date(transactions.transDate) >= {$from_date} AND date(transactions.transDate) <= {$to_date}
                GROUP BY ref
                ORDER BY transactions.transId DESC";

            $run_q = $this->db->query($q);
        }
        
        else {
            $this->db->select('transactions.transId,transactions.itemName,transactions.itemCode,transactions.quantity, transactions.totalPrice,transactions.ref, transactions.totalMoneySpent, transactions.modeOfPayment, transactions.staffId,
                    transactions.transDate, transactions.lastUpdated, transactions.amountTendered, transactions.changeDue,
                    CONCAT_WS(" ", admin.first_name, admin.last_name) AS "staffName",
                    transactions.cust_name, transactions.cust_phone, transactions.cust_email');

            $this->db->select_sum('transactions.quantity');

            $this->db->join('admin', 'transactions.staffId = admin.id', 'LEFT');

            if($hasilUnit == "*"){
                $parameter = ['DATE(transactions.transDate) >= ' => $from_date, 'DATE(transactions.transDate) <= ' => $to_date];
            }
            else{
                $parameter = ['DATE(transactions.transDate) >= ' => $from_date, 'DATE(transactions.transDate) <= ' => $to_date, 'transactions.cust_name'=>$hasilUnit];   
            }
            
            // $this->db->where("DATE(transactions.transDate) >= ", $from_date);
            // $this->db->where("DATE(transactions.transDate) <= ", $to_date);
            $this->db->where($parameter);

            $this->db->order_by('transactions.transId', 'DESC');

            $this->db->group_by('ref');

            $run_q = $this->db->get('transactions');
        }
        
        return $run_q->num_rows() ? $run_q->result() : FALSE;
    }


    public function getData($table){
            $run_q = $this->db->get($table);
            return $run_q->num_rows() ? $run_q->result() : FALSE;
    }





}
