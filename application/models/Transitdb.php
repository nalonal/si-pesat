<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Customer
 *
 * @author Amir <amirsanni@gmail.com>
 * @date 4th RabThaani, 1437AH (15th Jan, 2016)
 */
class Transitdb extends CI_Model{
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
        
        $run_q = $this->db->get('barang_transit');
        
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
     * 
     * @param type $itemName
     * @param type $itemQuantity
     * @param type $itemPrice
     * @param type $itemDescription
     * @param type $itemCode
     * @return boolean
     */
    public function add($itemName, $itemQuantity, $itemPrice, $kategori, $itemDescription, $itemCode, $barCode, $itemMinimal, $itemSatuan, $itemLokasi){
        $data = ['name'=>$itemName, 'quantity'=>$itemQuantity, 'kategori'=>$kategori, 'unitPrice'=>$itemPrice, 'description'=>$itemDescription, 'code'=>$itemCode, 'barcode'=>$barCode, 'minimal'=>$itemMinimal, 'satuan'=>$itemSatuan, 'lokasi'=>$itemLokasi];
                
        //set the datetime based on the db driver in use
        $this->db->platform() == "mysqli" 
                ? 
        $this->db->set('dateAdded', "datetime('now')", FALSE) 
                : 
        $this->db->set('dateAdded', "NOW()", FALSE);
        
        $hasil = $this->db->insert('barang_transit', $data);
        
        if($hasil){
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
        $q = "SELECT * FROM barang_transit 
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
        $q = "UPDATE barang_transit SET quantity = quantity + ? WHERE id = ?";
        
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
        $q = "UPDATE barang_transit SET quantity = quantity - ? WHERE code = ?";
        
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
    
    
   public function newstock($itemId, $qty){
       $q = "UPDATE barang_transit SET quantity = quantity + $qty WHERE id = ?";
       
       $this->db->query($q, [$itemId]);
       
       if($this->db->affected_rows()){
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
   
   public function deficit($itemId, $qty){
       $q = "UPDATE barang_transit SET quantity = quantity - $qty WHERE id = ?";
       $this->db->query($q, [$itemId]);
       if($this->db->affected_rows()){
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
       $this->db->update('barang_transit', $data);
       return TRUE;
   }

   //pindah item submit
    public function pindahitem($itemId, $itemName, $itemDesc, $itemPrice, $itemKategori, $itemQuantity, $itemMinimal, $barCode, $itemSatuan, $itemLokasi, $itemCode){

        //mulai masuk logikanya
        $q = "SELECT * FROM items WHERE code = ?";
        $run_q = $this->db->query($q, [$itemCode]);
        
        if($run_q->num_rows() > 0){ //mencari apakah data sudah ada atau tidak di tabel items / data definitif
            $hasil = $run_q->result();

            foreach($hasil as $get){
               $jumlah = $get->quantity;
               $id = $get->id;
            }


            //mulai mengupdate jumlah data
            $jumlah = ['quantity'=>$jumlah + $itemQuantity];
            $this->db->where('id', $id);
            $this->db->update('items',$jumlah);

            //apabila berhasil, akan menghapus dari database item transit
            $this->db->where('id', $itemId)->delete('barang_transit');

            return TRUE;
        }

        else{ //kalau tidak ada, buat data base dengan ketentuan yang sama
            $data = ['name'=>$itemName, 'quantity'=>$itemQuantity, 'kategori'=>$itemKategori, 'unitPrice'=>$itemPrice, 'description'=>$itemDescription, 'code'=>$itemCode, 'barcode'=>$barCode, 'minimal'=>$itemMinimal, 'satuan'=>$itemSatuan, 'lokasi'=>$itemLokasi, 'pengadaan'=>1];
                
                //set the datetime based on the db driver in use
                $this->db->platform() == "mysqli" 
                        ? 
                $this->db->set('dateAdded', "datetime('now')", FALSE) 
                        : 
                $this->db->set('dateAdded', "NOW()", FALSE);
                
                $this->db->insert('items', $data);
            $this->db->where('id', $itemId)->delete('barang_transit');
        }

       

       
       return TRUE;
   }

   //end
   
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
        
        $run_q = $this->db->get('barang_transit');
        
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

        $run_q = $this->db->get('barang_transit');
        
        return $run_q->num_rows() ? $run_q->row() : FALSE;
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    public function getItemsCumTotal(){
        $this->db->select("SUM(unitPrice*quantity) as cumPrice");

        $run_q = $this->db->get('barang_transit');
        
        return $run_q->num_rows() ? $run_q->row()->cumPrice : FALSE;
    }



   
}