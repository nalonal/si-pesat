<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Home
 *
 * @author Amir <amirsanni@gmail.com>
 * @date 20th Jan, 2016
 */
class Home extends CI_Controller {

    public function __construct(){
        parent::__construct();
        
        if(!empty($_SESSION['admin_id'])){
            redirect('dashboard');
        }
    }
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    
    public function index() {
        $this->output->set_header('Access-Control-Allow-Origin: *');
        $this->load->view('home');
    }
    
    
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
    
    
    /**
     * 
     */
    public function login(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('email', 'E-mail', ['required', 'trim', 'valid_email']);
        $this->form_validation->set_rules('password', 'Password', ['required']);
        
        if($this->form_validation->run() !== FALSE){
            $givenEmail = strtolower(set_value('email'));
            $givenPassword = set_value('password');
            
            $passwordInDb = $this->genmod->getTableCol('admin', 'password', 'email', $givenEmail);
            $account_status = $this->genmod->getTableCol('admin', 'account_status', 'email', $givenEmail);
            $deleted = $this->genmod->getTableCol('admin', 'deleted', 'email', $givenEmail);
        
            //verify password if $passwordInDb has a value (i.e. is set)
            $verifiedPassword = $passwordInDb ? password_verify($givenPassword, $passwordInDb) : FALSE;
            
            //allow log in if password and email matches and admin's account has not been suspended or deleted
            if($verifiedPassword && $account_status != 0 && $deleted != 1){
                $this->load->model('admin');
                
                //set session details
                $admin_info = $this->admin->get_admin_info($givenEmail);
                
                if($admin_info){
                    foreach($admin_info as $get){
                        $admin_id = $get->id;
                        
                        $_SESSION['admin_id'] = $admin_id;
                        $_SESSION['admin_email'] = $givenEmail;
                        $_SESSION['admin_role'] = $get->role;
                        $_SESSION['admin_initial'] = strtoupper(substr($get->first_name, 0, 1));
                        $_SESSION['admin_name'] = $get->first_name . " " . $get->last_name;
                    }
                    
                    //update user's last log in time
                    $this->admin->update_last_login($admin_id);
                }
                
                $json['status'] = 1;//set status to return
            }
            
            else{//if password is not correct
                $json['msg'] = "Incorrect email and password combination";
                $json['status'] = 0;
            }
        }
        
        else{//if form validation fails            
            $json['msg'] = "One or more required fields are empty or not correctly filled";
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
}