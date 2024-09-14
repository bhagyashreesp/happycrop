<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->model(['Profile_model']);
    }
    
    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $settings = get_settings('system_settings', true);
            $user_id = $this->session->userdata('user_id');
            $this->data['identity_column'] = $identity_column;
            $this->data['main_page'] = FORMS . 'seller_profile';
            $this->data['title'] = 'Manufacturer Profile | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Manufacturer Profile | ' . $settings['app_name'];
                
            $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->data['user_id']   = $this->ion_auth->get_user_id();
            
            $this->data['seller_data'] = $this->db->get_where('seller_data', array('user_id'=>$this->data['user_id']))->row();
            
            $this->data['is_seller'] = $is_seller;
                
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/home', 'refresh');
        }
    }
    
    public function save_basic_details()
    {
        $identity_column = $this->config->item('identity', 'ion_auth');
        $identity = $this->session->userdata('identity');
        $user = $this->ion_auth->user()->row();
        if ($identity_column == 'email') {
            $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim|valid_email|edit_unique[users.email.' . $user->id . ']');
        } else {
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|xss_clean|trim|numeric|edit_unique[users.mobile.' . $user->id . ']');
        }
        $this->form_validation->set_rules('username', 'Username', 'required|xss_clean|trim');

        if (!empty($_POST['old']) || !empty($_POST['new']) || !empty($_POST['new_confirm'])) {
            $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
            $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');
        }

        $tables = $this->config->item('tables', 'ion_auth');
        if (!$this->form_validation->run()) {
            if (validation_errors()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                echo json_encode($this->response);
                return false;
                exit();
            }
            if ($this->session->flashdata('message')) {
                $this->response['error'] = false;
                $this->response['message'] = $this->session->flashdata('message');
                echo json_encode($this->response);
                return false;
                exit();
            }
        } else {

            if (!empty($_POST['old']) || !empty($_POST['new']) || !empty($_POST['new_confirm'])) {
                if (!$this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'))) {
                    // if the login was un-successful
                    $this->response['error'] = true;
                    $this->response['message'] = $this->ion_auth->errors();
                    echo json_encode($this->response);
                    return false;
                }
            }
            $user_details = ['username' => $this->input->post('username'), 'email' => $this->input->post('email')];
            $user_details = escape_array($user_details);
            $this->db->set($user_details)->where($identity_column, $identity)->update($tables['login_users']);
            $this->response['redirect_to'] = '';
            $this->response['error'] = false;
            $this->response['message'] = 'Basic Details Updated Succesfully';
            echo json_encode($this->response);
            return false;
        }
    }
    
    public function bussiness_details()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $settings = get_settings('system_settings', true);
            $user_id = $this->session->userdata('user_id');
            $this->data['identity_column'] = $identity_column;
            $this->data['main_page'] = FORMS . 'seller_bussiness_details';
            $this->data['title'] = 'Manufacturer Profile | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Manufacturer Profile | ' . $settings['app_name'];
                
            $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->data['user_id']   = $this->ion_auth->get_user_id();
            
            $this->data['seller_data'] = $this->db->get_where('seller_data', array('user_id'=>$this->data['user_id']))->row();
            
            $this->data['is_seller'] = $is_seller;
                
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/home', 'refresh');
        }
    }
    
    function save_bussiness_details()
    {
        $is_seller = $this->input->post('is_seller');
        $this->form_validation->set_rules('company_name', 'Company Name', 'required|xss_clean|trim');
        if (!$this->form_validation->run()) {
            if (validation_errors()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                echo json_encode($this->response);
                return false;
                exit();
            }
            if ($this->session->flashdata('message')) {
                $this->response['error'] = false;
                $this->response['message'] = $this->session->flashdata('message');
                echo json_encode($this->response);
                return false;
                exit();
            }
        } else {
            
            $seller_data = ['company_name' => $this->input->post('company_name'), 'brand_name_1' => $this->input->post('brand_name_1'), 'brand_name_2' => $this->input->post('brand_name_2'), 'brand_name_3' => $this->input->post('brand_name_3'), 'plot_no' => $this->input->post('plot_no'), 'street_locality' => $this->input->post('street_locality'), 'landmark' => $this->input->post('landmark'), 'pin' => $this->input->post('pin'), 'city' => $this->input->post('city'), 'state' => $this->input->post('state')];
            $seller_data = escape_array($seller_data);
            $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
            
            $this->response['redirect_to'] ='';
            $this->response['error'] = false;
            $this->response['message'] = 'Business Details Updated Succesfully';
            echo json_encode($this->response);
            return false;
            
        }
    }
    
    public function bank_details()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $settings = get_settings('system_settings', true);
            $user_id = $this->session->userdata('user_id');
            $this->data['identity_column'] = $identity_column;
            $this->data['main_page'] = FORMS . 'seller_bank_details';
            $this->data['title'] = 'Manufacturer Profile | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Manufacturer Profile | ' . $settings['app_name'];
                
            $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->data['user_id']   = $this->ion_auth->get_user_id();
            
            $this->data['seller_data'] = $this->db->get_where('seller_data', array('user_id'=>$this->data['user_id']))->row();
            
            $this->data['is_seller'] = $is_seller;
                
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/home', 'refresh');
        }
    }
    
    public function save_bank_details()
    {
        $is_seller = $this->input->post('is_seller');
        $this->form_validation->set_rules('account_number', 'Account Number', 'required|xss_clean|trim|matches[confirm_account_number]');
        $this->form_validation->set_rules('confirm_account_number', 'Confirm Account Number', 'required');
        if (!$this->form_validation->run()) {
            if (validation_errors()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                echo json_encode($this->response);
                return false;
                exit();
            }
            if ($this->session->flashdata('message')) {
                $this->response['error'] = false;
                $this->response['message'] = $this->session->flashdata('message');
                echo json_encode($this->response);
                return false;
                exit();
            }
        } else {
            
            $seller_data = ['account_number' => $this->input->post('account_number'), 'bank_code' => $this->input->post('bank_code'), 'bank_name' => $this->input->post('bank_name'), 'bank_branch' => $this->input->post('bank_branch'), 'bank_city' => $this->input->post('bank_city'), 'bank_state' => $this->input->post('bank_state')];
            $seller_data = escape_array($seller_data);
            $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
            
            $this->response['redirect_to'] = '';
            $this->response['error'] = false;
            $this->response['message'] = 'Bank Details Updated Succesfully';
            echo json_encode($this->response);
            return false;
            
        }
    }
    
    public function gst_details()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $settings = get_settings('system_settings', true);
            $user_id = $this->session->userdata('user_id');
            $this->data['identity_column'] = $identity_column;
            $this->data['main_page'] = FORMS . 'seller_gst_details';
            $this->data['title'] = 'Manufacturer Profile | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Manufacturer Profile | ' . $settings['app_name'];
                
            $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->data['user_id']   = $this->ion_auth->get_user_id();
            
            $this->data['seller_data'] = $this->db->get_where('seller_data', array('user_id'=>$this->data['user_id']))->row();
            
            $this->data['is_seller'] = $is_seller;
                
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/home', 'refresh');
        }
    }
    
    public function save_gst_details()
    {
        $is_seller = $this->input->post('is_seller');
        $this->form_validation->set_rules('have_gst_no', 'Have GST/Dont Have GST', 'required|xss_clean|trim');
        
        if($_POST['have_gst_no'] == 1) {
            $this->form_validation->set_rules('gst_no', 'GST Number', 'required|xss_clean|trim');
        }
        
        if($_POST['have_gst_no'] == 2) {
            $this->form_validation->set_rules('pan_number', 'PAN Number', 'required|xss_clean|trim');
        }
        
        if (!$this->form_validation->run()) {
            if (validation_errors()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                echo json_encode($this->response);
                return false;
                exit();
            }
            if ($this->session->flashdata('message')) {
                $this->response['error'] = false;
                $this->response['message'] = $this->session->flashdata('message');
                echo json_encode($this->response);
                return false;
                exit();
            }
        } else {
            
            $seller_data = ['have_gst_no' => $this->input->post('have_gst_no'), 'gst_no' => $this->input->post('gst_no'), 'pan_number' => $this->input->post('pan_number')];
            $seller_data = escape_array($seller_data);
            $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
            
            $this->response['redirect_to'] = '';
            $this->response['error'] = false;
            $this->response['message'] = 'GST Details Updated Succesfully';
            echo json_encode($this->response);
            return false;
            
        }
    }
    
    public function license_details()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $settings = get_settings('system_settings', true);
            $user_id = $this->session->userdata('user_id');
            $this->data['identity_column'] = $identity_column;
            $this->data['main_page'] = FORMS . 'seller_license_details';
            $this->data['title'] = 'Manufacturer Profile | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Manufacturer Profile | ' . $settings['app_name'];
                
            $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->data['user_id']   = $this->ion_auth->get_user_id();
            
            $this->data['seller_data'] = $this->db->get_where('seller_data', array('user_id'=>$this->data['user_id']))->row();
            
            $this->data['is_seller'] = $is_seller;
                
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/home', 'refresh');
        }
    }
    
    public function save_license_details()
    {
        $is_seller = $this->input->post('is_seller');
        
        $flag = 0;
        if($_POST['have_fertilizer_license'] == 1) {
            $this->form_validation->set_rules('fertilizer_license_no', 'Fertilizer License Number', 'required|xss_clean|trim');
            $flag = 1;
        }
        
        if($_POST['have_pesticide_license_no'] == 1) {
            $this->form_validation->set_rules('pesticide_license_no', 'Pesticide License Number', 'required|xss_clean|trim');
            $flag = 1;
        }
        
        if($_POST['have_seeds_license_no'] == 1) {
            $this->form_validation->set_rules('seeds_license_no', 'Seeds License Number', 'required|xss_clean|trim');
            $flag = 1;
        }
        
        if(!$this->form_validation->run() && $flag == 1) {
            if (validation_errors()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                echo json_encode($this->response);
                return false;
                exit();
            }
            if ($this->session->flashdata('message')) {
                $this->response['error'] = false;
                $this->response['message'] = $this->session->flashdata('message');
                echo json_encode($this->response);
                return false;
                exit();
            }
        } else {
            
            $seller_data = ['have_fertilizer_license' => $this->input->post('have_fertilizer_license'), 'fertilizer_license_no' => $this->input->post('fertilizer_license_no'), 'have_pesticide_license_no' => $this->input->post('have_pesticide_license_no'), 'pesticide_license_no' => $this->input->post('pesticide_license_no'), 'have_seeds_license_no' => $this->input->post('have_seeds_license_no'), 'seeds_license_no' => $this->input->post('seeds_license_no'),'is_finish' => $this->input->post('is_finish')];
            $seller_data = escape_array($seller_data);
            
            $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
            
            $this->response['redirect_to'] ='';
            $this->response['error'] = false;
            $this->response['message'] = 'License Details Updated Succesfully';
            echo json_encode($this->response);
            return false;
            
        }
    }
    
}
