<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Home
 *
 * @author Amir <amirsanni@gmail.com>
 * @date 20th Rab. Awwal, 1437AH
 * @date 1st Jan, 2016
 */
class Employees extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        
        $this->genlib->checkLogin();
        
        $this->genlib->superOnly();
    }
    
    
    public function index(){
        $data['pageContent'] = $this->load->view('employees', '', TRUE);
        $data['pageTitle'] = "Employees";
        
        $this->load->view('main', $data);
    }

        /**
     * FUNGSI BUATAN SENDIRI -> TUJUAN UNTUK MENGETAHUI JUMLAH BARANG YANG KURANG
     */
  
    public function kurang(){
        $this->load->model(['item','obatdb']);
        //ini untuk menghitung obat
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        $totalItems = $this->db->count_all('obat');
        $this->load->library('pagination');
        $pageNumber = $this->uri->segment(3, 0);
        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 1000;
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;

        $allItems = $this->obatdb->getAll($orderBy, $orderFormat, $start, $limit);
        $kurang = []; //mencari data yang kurang
        $no = 0;
        foreach($allItems as $get):
            if($get->quantity <= $get->minimal){
                $kurang[$no] = $get->name;
            }
            $no++;
        endforeach;

        $hasil = count($kurang);
        $data['obat'] = $hasil;

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
}