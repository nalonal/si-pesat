<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Customer
 *
 * @author Amir <amirsanni@gmail.com>
 * @date 4th RabThaani, 1437AH (15th Jan, 2016)
 */
class Item extends CI_Model{
    public function __construct(){
        parent::__construct();
    }
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    public function getAll($orderBy, $orderFormat, $start=0, $limit=''){
        $this->db->limit($limit, $start);
        $this->db->order_by($orderBy, $orderFormat);
        
        $run_q = $this->db->get('items');
        
        if($run_q->num_rows() > 0){
            return $run_q->result();
        }
        
        else{
            return FALSE;
        }
    }


    public function laporangetAll($orderBy, $orderFormat, $start=0, $limit=''){
        $this->db->limit(10, $start);
        $this->db->order_by("id", "desc");
        
        $run_q = $this->db->get('laporan_pengadaan');
        
        if($run_q->num_rows() > 0){
            return $run_q->result();
        }
        
        else{
            return FALSE;
        }
    }
    
    /*
    ********************************************************************************************************************************
    */
    
    
    /**
     * 
     * @param type $itemName
     * @param type $itemQuantity
     * @param type $itemPrice
     * @param type $itemDescription
     * @param type $itemCode
     * @return boolean
     */
    public function add($itemName,$itemKategori,$itemCode,$itemNup,$itemKondisi,$itemUnit,$barCode,$itemDescription,$itemTahun,$itemLokasi,$itemPIC){
        $data = [
            'name'=>$itemName, 
            'kategori'=>$itemKategori, 
            'code'=>$itemCode, 
            'nup'=>$itemNup,
            'kondisi'=>$itemKondisi,
            'unit'=>$itemUnit,
            'tahun'=>$itemTahun,
            'barcode'=>$barCode,
            'description'=>$itemDescription, 
            'lokasi'=>$itemLokasi, 
            'pic'=>$itemPIC, 
            'pengadaan'=>1,
            'aktivasi'=>1
        ];
        
        //set the datetime based on the db driver in use
        $this->db->platform() == "mysqli" 
        ? 
        $this->db->set('dateAdded', "datetime('now')", FALSE) 
        : 
        $this->db->set('dateAdded', "NOW()", FALSE);
        
        $this->db->insert('items', $data);
        
        // if($this->db->insert_id()){
        //     $data2 = ['id_barang'=>$itemCode, 'nama_barang'=>$itemName, 'jenis'=>$kategori, 'aksi'=>"Stok Baru", 'saldo'=>$itemQuantity, 'admin'=> $_SESSION['admin_name']];
        //     $this->db->platform() == "mysqli" 
        //     ? 
        //     $this->db->set('tanggal', "datetime('now')", FALSE) 
        //     : 
        //     $this->db->set('tanggal', "NOW()", FALSE);

        //     $this->db->insert('kartu_stok', $data2);
        //     return $this->db->insert_id();
        // }
        
        // else{
        //     return FALSE;
        // }
    }
    

    public function add2($itemName, $itemQuantity, $itemPrice, $kategori, $itemDescription, $itemCode, $barCode, $itemMinimal, $itemSatuan, $itemLokasi){
        $data = ['name'=>$itemName, 'quantity'=>$itemQuantity, 'kategori'=>$kategori, 'unitPrice'=>$itemPrice, 'description'=>$itemDescription, 'code'=>$itemCode, 'barcode'=>$barCode, 'minimal'=>$itemMinimal, 'satuan'=>$itemSatuan, 'lokasi'=>$itemLokasi, 'pengadaan'=>1];
        

        // $itemName,$itemKategori,$itemCode,$itemNup,$itemKondisi,$itemUnit,$barCode,$itemDescription


        if(!$itemName || !$itemQuantity || !$kategori || !$itemPrice || !$itemCode || !$barCode || !$itemMinimal || !$itemSatuan || !$itemLokasi){
            $data['aktivasi'] = 0;
        }
        else{
            $data['aktivasi'] =1;
        }
        //set the datetime based on the db driver in use
        $this->db->platform() == "mysqli" 
        ? 
        $this->db->set('dateAdded', "datetime('now')", FALSE) 
        : 
        $this->db->set('dateAdded', "NOW()", FALSE);
        
        $this->db->insert('items', $data);
        
        if($this->db->insert_id()){
            $data2 = ['id_barang'=>$itemCode, 'nama_barang'=>$itemName, 'jenis'=>$kategori, 'aksi'=>"Stok Baru", 'saldo'=>$itemQuantity, 'admin'=> $_SESSION['admin_name']];
            $this->db->platform() == "mysqli" 
            ? 
            $this->db->set('tanggal', "datetime('now')", FALSE) 
            : 
            $this->db->set('tanggal', "NOW()", FALSE);

            $this->db->insert('kartu_stok', $data2);
            return $this->db->insert_id();
        }
        
        else{
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
    
    /**
     * 
     * @param type $value
     * @return boolean
     */
    public function itemsearch($value){
        $q = "SELECT * FROM items 
        WHERE 
        name LIKE '%".$this->db->escape_like_str($value)."%'
        || 
        code LIKE '%".$this->db->escape_like_str($value)."%'
        || 
        barcode LIKE '%".$this->db->escape_like_str($value)."%'";
        
        $run_q = $this->db->query($q, [$value, $value]);
        
        if($run_q->num_rows() > 0){
            return $run_q->result();
        }
        
        else{
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
    
    /**
     * To add to the number of an item in stock
     * @param type $itemId
     * @param type $numberToadd
     * @return boolean
     */
    public function incrementItem($itemId, $numberToadd){
        $q = "UPDATE items SET quantity = quantity + ? WHERE id = ?";
        
        $this->db->query($q, [$numberToadd, $itemId]);
        
        if($this->db->affected_rows() > 0){
            return TRUE;
        }
        
        else{
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
    
    public function decrementItem($itemCode, $numberToRemove){
        $q = "UPDATE items SET quantity = quantity - ? WHERE code = ?";
        
        $this->db->query($q, [$numberToRemove, $itemCode]);
        
        if($this->db->affected_rows() > 0){
            return TRUE;
        }
        
        else{
            return FALSE;
        }
    }


    public function decrementTransit($itemCode, $numberToRemove){
        $q = "UPDATE items SET transit = transit - ? WHERE code = ?";
        
        $this->db->query($q, [$numberToRemove, $itemCode]);
        
        if($this->db->affected_rows() > 0){
            return TRUE;
        }
        
        else{
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
    
    
    public function newstock($itemId, $qty, $jenis){

        if($jenis == 'transit'){
            $q = "UPDATE items SET transit = transit + $qty WHERE id = ?";
        }
        else{
            $q = "UPDATE items SET quantity = quantity + $qty WHERE id = ?";
        }

        $this->db->query($q, [$itemId]);

        if($this->db->affected_rows()){

            $code = "B2";

            $q = "SELECT * FROM items WHERE id = ?";

            $run_q = $this->db->query($q, [$itemId]);

            if($run_q->num_rows() > 0){
                $hasil = $run_q->result();
            }

            foreach($hasil as $get){
             $nilai['nama'] = $get->name;
             $nilai['kategori'] = $get->kategori;
             $nilai['code'] = $get->code;
             $nilai['jumlah'] = $get->quantity;
            }

         $data2 = ['id_barang'=>$nilai['code'], 'nama_barang'=>$nilai['nama'], 'jenis'=>$nilai['kategori'], 'aksi'=>"Penambahan", 'masuk'=>$qty, 'saldo'=>$nilai['jumlah'], 'admin'=> $_SESSION['admin_name']];
        if($jenis != "definitif"){
            $data2['transit'] = 1;
        }

         $this->db->platform() == "mysqli" 
         ? 
         $this->db->set('tanggal', "datetime('now')", FALSE) 
         : 
         $this->db->set('tanggal', "NOW()", FALSE);

         $this->db->insert('kartu_stok', $data2);
            //akhir


         return TRUE;
     }

     else{
         return FALSE;
     }
 }


 //   public function newtransit($itemId, $qty, $jenis){

 //        $q = "UPDATE items SET transit = transit + $qty WHERE id = ?";

 //        $this->db->query($q, [$itemId]);

 //        if($this->db->affected_rows()){

 //            $code = "B2";

 //            $q = "SELECT * FROM items WHERE id = ?";

 //            $run_q = $this->db->query($q, [$itemId]);

 //            if($run_q->num_rows() > 0){
 //                $hasil = $run_q->result();
 //            }

 //            foreach($hasil as $get){
 //             $nilai['nama'] = $get->name;
 //             $nilai['kategori'] = $get->kategori;
 //             $nilai['code'] = $get->code;
 //             $nilai['jumlah'] = $get->quantity;
 //            }

 //         $data2 = ['id_barang'=>$nilai['code'], 'nama_barang'=>$nilai['nama'], 'jenis'=>$nilai['kategori'], 'aksi'=>"Penambahan", 'masuk'=>$qty, 'saldo'=>$nilai['jumlah'], 'admin'=> $_SESSION['admin_name']];

 //            $data2['transit'] = 1;

 //         $this->db->platform() == "mysqli" 
 //         ? 
 //         $this->db->set('tanggal', "datetime('now')", FALSE) 
 //         : 
 //         $this->db->set('tanggal', "NOW()", FALSE);

 //         $this->db->insert('kartu_stok', $data2);
 //            //akhir


 //         return TRUE;
 //     }

 //     else{
 //         return FALSE;
 //     }
 // }

   /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
   
   public function deficit($itemId, $qty){
     $q = "UPDATE items SET quantity = quantity - $qty WHERE id = ?";
     $this->db->query($q, [$itemId]);
     if($this->db->affected_rows()){
                        //mulai logika
        $code = "B2";

        $q = "SELECT * FROM items WHERE id = ?";
        
        $run_q = $this->db->query($q, [$itemId]);
        
        if($run_q->num_rows() > 0){
            $hasil = $run_q->result();
        }

        foreach($hasil as $get){
         $nilai['nama'] = $get->name;
         $nilai['kategori'] = $get->kategori;
         $nilai['code'] = $get->code;
         $nilai['jumlah'] = $get->quantity;
     }

     $data2 = ['id_barang'=>$nilai['code'], 'nama_barang'=>$nilai['nama'], 'jenis'=>$nilai['kategori'], 'aksi'=>"Pengurangan", 'keluar'=>$qty, 'saldo'=>$nilai['jumlah'], 'admin'=> $_SESSION['admin_name']];
     $this->db->platform() == "mysqli" 
     ? 
     $this->db->set('tanggal', "datetime('now')", FALSE) 
     : 
     $this->db->set('tanggal', "NOW()", FALSE);

     $this->db->insert('kartu_stok', $data2);
            //akhir

     return TRUE;
 }
 
 else{
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
   
   /**
    * 
    * @param type $itemId
    * @param type $itemName
    * @param type $itemDesc
    * @param type $itemPrice
    */


   public function edit($itemId, $itemName, $itemDesc, $itemPrice, $itemKategori, $itemQuantity, $itemMinimal, $barCode, $itemSatuan, $itemLokasi){
     $data = ['name'=>$itemName, 'unitPrice'=>$itemPrice, 'description'=>$itemDesc, 'kategori'=>$itemKategori, 'quantity'=>$itemQuantity, 'minimal'=>$itemMinimal, 'barcode'=>$barCode, 'satuan'=>$itemSatuan, 'lokasi'=>$itemLokasi];
     
     $this->db->where('id', $itemId);
     $this->db->update('items', $data);
     
     return TRUE;
 }
 
   /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
   
   public function getActiveItems($orderBy, $orderFormat){
    $this->db->order_by($orderBy, $orderFormat);
    
    $this->db->where('quantity >=', 1);
    
    $run_q = $this->db->get('items');
    
    if($run_q->num_rows() > 0){
        return $run_q->result();
    }
    
    else{
        return FALSE;
    }
}

// public function transitgetActiveItems($orderBy, $orderFormat){
//     $this->db->order_by($orderBy, $orderFormat);
    
//     $this->db->where('quantity >=', 1);
    
//     $run_q = $this->db->get('barang_transit');
    
//     if($run_q->num_rows() > 0){
//         return $run_q->result();
//     }
    
//     else{
//         return FALSE;
//     }
// }

    public function semuaUnit($orderBy, $orderFormat){
        //$this->db->limit($limit, $start);
        $this->db->order_by($orderBy, $orderFormat);
        $run_q = $this->db->get('satuan_unit');
        
        if($run_q->num_rows() > 0){
            return $run_q->result();
        }
        
        else{
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

    /**
     * array $where_clause
     * array $fields_to_fetch
     * 
     * return array | FALSE
     */
    public function getItemInfo($where_clause, $fields_to_fetch){
        $this->db->select($fields_to_fetch);
        
        $this->db->where($where_clause);

        $run_q = $this->db->get('items');
        
        return $run_q->num_rows() ? $run_q->row() : FALSE;
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************
    ********************************************************************************************************************************
    */

    public function getItemsCumTotal(){
        $this->db->select("SUM(unitPrice*quantity) as cumPrice");

        $run_q = $this->db->get('items');
        
        return $run_q->num_rows() ? $run_q->row()->cumPrice : FALSE;
    }


    public function suspend($barang_id, $new_status){       
        $this->db->where('id', $barang_id);
        $this->db->update('items', ['pengadaan'=>$new_status]);

        if($this->db->affected_rows()){
            return TRUE;
        }

        else{
            return FALSE;
        }
    }


    public function suspendPengadaan($barang_id, $jumlah){       
        $this->db->where('id', $barang_id);
        $this->db->update('items', ['banyak'=>$jumlah]);

        if($this->db->affected_rows()){
            return TRUE;
        }

        else{
            return FALSE;
        }
    }


    public function laporanPengadaan($administrator, $file_name){       
        $data = ['administrator'=>$administrator, 'direktori'=>$file_name];
        $this->db->insert('laporan_pengadaan', $data);
        return $this->db->insert_id();
    }

    /**
     ////////////////////////////////////////////////////////////////////////
     ////////////////////////////////////////////////////////////////////////
     * KOMPLEKS CODINGAN UNTUK FUNGSI : KATEGORI BARANG
     * TANGGAL PEMBUATAN              : 15 SEPT 2018
     * KETERANGAN                     : CRUD tabel kategori

     DATABASE 'kategori' = id(int,ai), kategori(varchar), tanggal(timestamp), admin(varchar)
     ////////////////////////////////////////////////////////////////////////
     ////////////////////////////////////////////////////////////////////////
     **/

     public function tambahKategori($kategori){//menambahkan kategori barang
        $admin = $_SESSION['admin_name'];    
        $data = ['kategori'=>$kategori, 'admin'=>$admin];
        $this->db->insert('kategori', $data);
        return $this->db->insert_id();
    }

    public function kategorigetAll($orderBy, $orderFormat, $start=0, $limit=''){ //menampilkan kategori barang pada tabel
        $this->db->limit(10, $start);
        $this->db->order_by("kategori", "asc");
        
        $run_q = $this->db->get('kategori');
        
        if($run_q->num_rows() > 0){
            return $run_q->result();
        }
        
        else{
            return FALSE;
        }
    }

    public function getActiveKategori($orderBy, $orderFormat){
        /**
         *
         * Berfungsi ketika user akan memilih kategori dalam bentuk dropdown
         * Untuk menampilkan kategori dalam tabel 'kategori'
         *
         */
        $this->db->order_by($orderBy, $orderFormat);
        $run_q = $this->db->get('kategori');

        if($run_q->num_rows() > 0){
            return $run_q->result();
        }
        else{
            return FALSE;
        }
    }



    /**
     ////////////////////////////////////////////////////////////////////////
     ////////////////////////////////////////////////////////////////////////
     * KOMPLEKS CODINGAN UNTUK FUNGSI : DAFTAR UNIT
     * TANGGAL PEMBUATAN              : 19 OKT 2018
     * KETERANGAN                     : CRUD tabel unit

     DATABASE 'satuan_unit' = id(int,ai), kategori(varchar), tanggal(timestamp), admin(varchar)
     ////////////////////////////////////////////////////////////////////////
     ////////////////////////////////////////////////////////////////////////
     **/

     public function tambahUnit($unit){//menambahkan kategori barang  
        $data = ['satuan'=>$unit];
        $this->db->insert('satuan_unit', $data);
        return $this->db->insert_id();
    }

    public function unitgetAll($orderBy, $orderFormat, $start=0, $limit=''){ //menampilkan kategori barang pada tabel
        $this->db->limit(10, $start);
        $this->db->order_by("satuan", "asc");
        
        $run_q = $this->db->get('satuan_unit');
        
        if($run_q->num_rows() > 0){
            return $run_q->result();
        }
        
        else{
            return FALSE;
        }
    }

    public function getActiveUnit($orderBy, $orderFormat){
        $this->db->order_by($orderBy, $orderFormat);
        $run_q = $this->db->get('satuan_unit');

        if($run_q->num_rows() > 0){
            return $run_q->result();
        }
        else{
            return FALSE;
        }
    }

    /**
     ////////////////////////////////////////////////////////////////////////
     ////////////////////////////////////////////////////////////////////////
     * KOMPLEKS CODINGAN UNTUK FUNGSI : AKTIVASI BARANG
     * TANGGAL PEMBUATAN              : 16 SEPTEMBER 2018
     * KETERANGAN                     : 
     ////////////////////////////////////////////////////////////////////////
     ////////////////////////////////////////////////////////////////////////
     **/

    public function pindahitem($itemId, $itemName, $itemDesc, $itemPrice, $itemKategori, $itemQuantity, $itemMinimal, $barCode, $itemSatuan, $itemLokasi, $itemCode){

        $data = ['name'=>$itemName, 'unitPrice'=>$itemPrice, 'description'=>$itemDesc, 'kategori'=>$itemKategori, 'quantity'=>$itemQuantity, 'minimal'=>$itemMinimal, 'barcode'=>$barCode, 'satuan'=>$itemSatuan, 'lokasi'=>$itemLokasi];

        //mulai masuk logikanya
        $q = "SELECT * FROM items WHERE code = ?";
        $run_q = $this->db->query($q, [$itemCode]);
        
        if($run_q->num_rows() > 0){ //mencari apakah data sudah ada atau tidak di tabel items / data definitif
            $hasil = $run_q->result();
            foreach($hasil as $get){
               $id = $get->id;
            }
            //mulai mengupdate jumlah data
            $data['aktivasi'] = 1;
            $this->db->where('id', $id);
            $this->db->update('items',$data);
            return TRUE;
        }

   }


   /**
    ////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////
    * KOMPLEKS CODINGAN UNTUK FUNGSI : PROSES PERPINDAHAN ITEM DARI TRANSIT KE DEFENITIF
    * TANGGAL PEMBUATAN              : 16 SEPTEMBER 2018
    * KETERANGAN                     : 
    ////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////
    **/

   public function prosespindahitem($itemId,$itemQuantity,$itemCode){

    $this->db->where('id', $itemId);
    $this->db->update('items', ['transit'=>0]);

    if($this->db->affected_rows()){
        $q = "SELECT * FROM kartu_stok WHERE id_barang = ? AND transit=?";
        $run_q = $this->db->query($q, [$itemCode, 1]);

        $q2 = "SELECT * FROM items WHERE code = ?";
        $run_q2 = $this->db->query($q2, [$itemCode]);

        if($run_q2->num_rows() > 0){ //items / data definitif
            $hasil2 = $run_q2->result();
            foreach($hasil2 as $get2):
                $quantity = $get2->quantity;
            endforeach;
        }
        else{
            $quantity = 0;
        }

        $jago  = $run_q->result();
        foreach($jago as $get){

                    //mencari quantity
            $nomor = $get->no;
            $jenis = $get->jenis;
            $aksi = $get->aksi;
            $masuk = $get->masuk;
            $keluar = $get->keluar;
            $nama_barang = $get->nama_barang;
            $jenis = $get->jenis;
            $unit = $get->unit;
            $admin = $get->admin;

            if($masuk != 0){
               $quantity = $quantity + $masuk; 
            }
            else{
                $quantity = $quantity - $keluar;
            }

            $data2 = ['id_barang'=>$itemCode, 'nama_barang'=>$nama_barang, 'jenis'=>$jenis, 'aksi'=>$aksi, 'saldo'=>$quantity, 'keluar' => $keluar, 'masuk'=>$masuk, 'unit'=>$unit, 'admin'=> $admin];

            $this->db->platform() == "mysqli" 
                 ? 
             $this->db->set('tanggal', "datetime('now')", FALSE) 
                 : 
             $this->db->set('tanggal', "NOW()", FALSE);

             $this->db->insert('kartu_stok', $data2);
             $this->db->insert_id();

            $this->db->where('no', $nomor);
            $this->db->update('kartu_stok', ['transit'=>2]);
        }


        $this->db->where('id', $itemId);
        $this->db->update('items', ['quantity'=>$quantity]);

        return TRUE;
    }
    else{
        return FALSE;
    }
    }


}