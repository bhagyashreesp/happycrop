<?php defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->lang->load('auth');
        $this->data['is_logged_in'] = ($this->ion_auth->logged_in()) ? 1 : 0;
        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();
        $this->data['settings'] = get_settings('system_settings', true);
        $this->data['web_settings'] = get_settings('web_settings', true);
    }
    
    public function index()
    {
        if($this->ion_auth->logged_in()) {
            redirect(base_url());
        }
        $this->data['main_page'] = 'register_step1';
        $this->data['sliders'] = get_sliders('', '', '', 4);
        $this->data['title'] = 'Register | ' . $this->data['web_settings']['site_title'];
        
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    
    public function step2($is_seller = 0)
    {
        if($this->ion_auth->logged_in()) {
            if($this->ion_auth->is_seller() && $this->ion_auth->seller_status() == 2)
            {
                redirect(base_url().'register/step4/'.$is_seller);
            }
            else if($this->ion_auth->is_seller() && $this->ion_auth->seller_status() == 1)
            {
                redirect(base_url().'seller/home');
            }
            else
            {
                redirect(base_url());
            }
        }
        $this->data['is_seller'] = $is_seller;
        $this->data['main_page'] = 'register_step2';
        /*if($is_seller)
        {
            $this->data['sliders'] = get_sliders('', '', '', 3);
        }
        else
        {
            $this->data['sliders'] = get_sliders('', '', '', 2);
        }*/
        $this->data['sliders'] = get_sliders('', '', '', 4);
        $this->data['title'] = 'Register | ' . $this->data['web_settings']['site_title'];
        
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    
    public function step3($is_seller = 0)
    {
        if(!$this->ion_auth->logged_in()) {
            //redirect(base_url());
            $this->data['is_seller'] = $is_seller;
            $this->data['main_page'] = 'register_step3';
            $this->data['title'] = 'Register | ' . $this->data['web_settings']['site_title'];
            
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
        else
        {
            $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->data['user_id']   = $this->ion_auth->get_user_id();
            
            if($is_seller)
            {
                $this->data['seller_data'] = $this->db->get_where('seller_data', array('user_id'=>$this->data['user_id']))->row();
            }
            else
            {
                $this->data['seller_data'] = $this->db->get_where('retailer_data', array('user_id'=>$this->data['user_id']))->row();
            }
            
            $this->data['is_seller'] = $is_seller;
            $this->data['main_page'] = 'basic_details';
            $this->data['title'] = 'Basic Details | ' . $this->data['web_settings']['site_title'];
            
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
    }
    
    public function save_step3()
    {
        $identity_column = $this->config->item('identity', 'ion_auth');
        $identity = $this->session->userdata('identity');
        $user = $this->ion_auth->user()->row();
        //if ($identity_column == 'email') {
            $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim|valid_email|edit_unique[users.email.' . $user->id . ']');
        //} else {
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|xss_clean|trim|numeric|edit_unique[users.mobile.' . $user->id . ']');
        //}
        $this->form_validation->set_rules('username', 'Username', 'required|xss_clean|trim');

        if (!empty($_POST['old']) || !empty($_POST['new']) || !empty($_POST['new_confirm'])) {
            $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
            $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');
        }
        
        //$this->form_validation->set_rules('agree_terms_privacy', 'Agree Terms', 'required|xss_clean|trim');

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
    
    public function step4($is_seller = 0)
    {
        if(!$this->ion_auth->logged_in()) {
            redirect(base_url());
        }
        else
        {
            $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->data['user_id']   = $this->ion_auth->get_user_id();
            
            if($is_seller)
            {
                $this->data['seller_data'] = $this->db->get_where('seller_data', array('user_id'=>$this->data['user_id']))->row();
            }
            else
            {
                $this->data['seller_data'] = $this->db->get_where('retailer_data', array('user_id'=>$this->data['user_id']))->row();
            }
            $this->data['is_seller'] = $is_seller;
            $this->data['main_page'] = 'register_step4';
            $this->data['title']     = 'Business Details | ' . $this->data['web_settings']['site_title'];
            
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
    }
    
    public function save_step4()
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
            
            if($is_seller)
            {
                
                $seller_data = ['company_name' => $this->input->post('company_name'), 'store_name' => $this->input->post('company_name'), 'slug' => create_unique_slug($this->input->post('company_name', true), 'seller_data'), 'brand_name_1' => $this->input->post('brand_name_1'), 'brand_name_2' => $this->input->post('brand_name_2'), 'brand_name_3' => $this->input->post('brand_name_3'), 'plot_no' => $this->input->post('plot_no'), 'street_locality' => $this->input->post('street_locality'), 'landmark' => $this->input->post('landmark'), 'pin' => $this->input->post('pin'), 'city_id' => $this->input->post('city_id'), 'state_id' => $this->input->post('state_id')];
                $seller_data = escape_array($seller_data);
                $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                
                $identity_column = $this->config->item('identity', 'ion_auth');
                $user_info       = $this->ion_auth->user()->row();
                
                $check1 = $this->db->get_where('addresses', array('user_id'=>$user_info->id,'add_order'=>1))->row_array();
                
                if($check1)
                {
                    $address_1_data = ['name'=>$user_info->username, 'mobile'=> $user_info->mobile, 'alternate_mobile'=> $user_info->alternate_mobile, 'type'=>'office','address'=>$this->input->post('plot_no').' '.$this->input->post('street_locality').' '.$this->input->post('landmark'), 'landmark'=> $this->input->post('landmark'), 'city_id' => $this->input->post('city_id'), 'state_id' => $this->input->post('state_id'), 'pincode' => $this->input->post('pin'),'add_order'=>1,'is_default'=>1];
                    $address_1_data = escape_array($address_1_data);
                    $this->db->set($address_1_data)->where('user_id', $user_info->id)->where('add_order',1)->update('addresses');
                }
                else
                {
                    $address_1_data = ['name'=>$user_info->username, 'mobile'=> $user_info->mobile, 'alternate_mobile'=> $user_info->alternate_mobile, 'user_id'=>$user_info->id,'type'=>'office','address'=>$this->input->post('plot_no').' '.$this->input->post('street_locality').' '.$this->input->post('landmark'), 'landmark'=> $this->input->post('landmark'), 'city_id' => $this->input->post('city_id'), 'state_id' => $this->input->post('state_id'), 'pincode' => $this->input->post('pin'),'add_order'=>1];
                    $address_1_data = escape_array($address_1_data);
                    
                    $this->db->insert('addresses', $address_1_data);
                }
                
                $this->response['redirect_to'] = base_url('register/step5/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Business Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
            else
            {
                $retailer_data = ['company_name' => $this->input->post('company_name'), 'store_name' => $this->input->post('company_name'), 'slug' => create_unique_slug($this->input->post('company_name', true), 'retailer_data'), 'brand_name_1' => $this->input->post('brand_name_1'), 'brand_name_2' => $this->input->post('brand_name_2'), 'brand_name_3' => $this->input->post('brand_name_3'), 'plot_no' => $this->input->post('plot_no'), 'street_locality' => $this->input->post('street_locality'), 'landmark' => $this->input->post('landmark'), 'pin' => $this->input->post('pin'), 'city_id' => $this->input->post('city_id'), 'state_id' => $this->input->post('state_id')];
                $retailer_data = escape_array($retailer_data);
                $this->db->set($retailer_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                
                $identity_column = $this->config->item('identity', 'ion_auth');
                $user_info       = $this->ion_auth->user()->row();
                
                $check1 = $this->db->get_where('addresses', array('user_id'=>$user_info->id,'add_order'=>1))->row_array();
                
                if($check1)
                {
                    $address_1_data = ['name'=>$user_info->username, 'mobile'=> $user_info->mobile, 'alternate_mobile'=> $user_info->alternate_mobile, 'type'=>'office','address'=>$this->input->post('plot_no').' '.$this->input->post('street_locality').' '.$this->input->post('landmark'), 'landmark'=> $this->input->post('landmark'), 'city_id' => $this->input->post('city_id'), 'state_id' => $this->input->post('state_id'), 'pincode' => $this->input->post('pin'),'add_order'=>1];
                    $address_1_data = escape_array($address_1_data);
                    $this->db->set($address_1_data)->where('user_id', $user_info->id)->where('add_order',1)->update('addresses');
                }
                else
                {
                    $address_1_data = ['name'=>$user_info->username, 'mobile'=> $user_info->mobile, 'alternate_mobile'=> $user_info->alternate_mobile, 'user_id'=>$user_info->id,'type'=>'office','address'=>$this->input->post('plot_no').' '.$this->input->post('street_locality').' '.$this->input->post('landmark'), 'landmark'=> $this->input->post('landmark'), 'city_id' => $this->input->post('city_id'), 'state_id' => $this->input->post('state_id'), 'pincode' => $this->input->post('pin'),'add_order'=>1];
                    $address_1_data = escape_array($address_1_data);
                    
                    $this->db->insert('addresses', $address_1_data);
                }
                
                $this->response['redirect_to'] = base_url('register/step5/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Business Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
            
        }
    }
    
    public function step5($is_seller = 0)
    {
        if(!$this->ion_auth->logged_in()) {
            redirect(base_url());
        }
        else
        {
            $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->data['user_id']   = $this->ion_auth->get_user_id();
            
            if($is_seller)
            {
                $this->data['seller_data'] = $this->db->get_where('seller_data', array('user_id'=>$this->data['user_id']))->row();
            }
            else
            {
                $this->data['seller_data'] = $this->db->get_where('retailer_data', array('user_id'=>$this->data['user_id']))->row();
            }
            
            $this->data['is_seller'] = $is_seller;
            $this->data['main_page'] = 'register_step5';
            $this->data['title'] = 'Register | ' . $this->data['web_settings']['site_title'];
            
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
    }
    
    public function save_step5()
    {
        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->data['user_id']   = $this->ion_auth->get_user_id();
        
        $is_seller = $this->input->post('is_seller');
        
        $seller_info = array();
        if($is_seller)
        {
            $seller_info = $this->db->get_where('seller_data', array('user_id'=>$this->data['user_id']))->row_array();
        }
        else
        {
            $seller_info = $this->db->get_where('retailer_data', array('user_id'=>$this->data['user_id']))->row_array();
        }
        
        $this->form_validation->set_rules('account_name', 'Account Holder Name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('account_number', 'Account Number', 'required|xss_clean|trim|matches[confirm_account_number]');
        $this->form_validation->set_rules('confirm_account_number', 'Confirm Account Number', 'required');
        $this->form_validation->set_rules('bank_code', 'IFSC Code', 'required|xss_clean|trim');
        $this->form_validation->set_rules('account_type', 'Account Type', 'required|xss_clean|trim');
        $this->form_validation->set_rules('bank_name', 'Bank Name', 'required|xss_clean|trim');
        
        if(empty($_FILES['cancelled_cheque']['name']) && $seller_info['cancelled_cheque'] == '')
        {
            $this->form_validation->set_rules('cancelled_cheque', 'Cancelled cheque', 'required');
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
            
            if($is_seller)
            {
                $seller_data = ['account_name'=>$this->input->post('account_name'),'account_number' => $this->input->post('account_number'), 'bank_code' => $this->input->post('bank_code'),'account_type'=> $this->input->post('account_type'), 'bank_name' => $this->input->post('bank_name'), 'bank_branch' => $this->input->post('bank_branch'), 'bank_city' => $this->input->post('bank_city'), 'bank_state' => $this->input->post('bank_state')];
                $seller_data = escape_array($seller_data);
                $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                
                
                if (!file_exists(FCPATH . SELLER_CANCELLED_CHEQUE_IMG_PATH)) {
                    mkdir(FCPATH . SELLER_CANCELLED_CHEQUE_IMG_PATH, 0777);
                }
    
                $temp_array = array();
                $files = $_FILES;
                $images_new_name_arr = array();
                $images_info_error = "";
                $allowed_media_types = implode('|', allowed_media_types());
                $config = [
                    'upload_path' =>  FCPATH . SELLER_CANCELLED_CHEQUE_IMG_PATH,
                    'allowed_types' => $allowed_media_types,
                    'max_size' => 8000,
                ];
    
    
                if (!empty($_FILES['cancelled_cheque']['name']) && isset($_FILES['cancelled_cheque']['name'])) {
                    $other_image_cnt = count($_FILES['cancelled_cheque']['name']);
                    $other_img = $this->upload;
                    $other_img->initialize($config);
    

                    if (!empty($_FILES['cancelled_cheque']['name'])) {

                        $_FILES['temp_image']['name'] = $files['cancelled_cheque']['name'];
                        $_FILES['temp_image']['type'] = $files['cancelled_cheque']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['cancelled_cheque']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['cancelled_cheque']['error'];
                        $_FILES['temp_image']['size'] = $files['cancelled_cheque']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = 'attachments :' . $images_info_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array = $other_img->data();
                            resize_review_images($temp_array, FCPATH . SELLER_CANCELLED_CHEQUE_IMG_PATH);
                            $images_new_name_arr[] = SELLER_CANCELLED_CHEQUE_IMG_PATH . $temp_array['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files['cancelled_cheque']['name'];
                        $_FILES['temp_image']['type'] = $files['cancelled_cheque']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['cancelled_cheque']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['cancelled_cheque']['error'];
                        $_FILES['temp_image']['size'] = $files['cancelled_cheque']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = $other_img->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error != NULL || !$this->form_validation->run()) {
                        if(isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr as $key => $val) {
                                unlink(FCPATH . SELLER_CANCELLED_CHEQUE_IMG_PATH . $images_new_name_arr[$key]);
                            }
                        }
                    }
                    
                    
                }
                
                if ($images_info_error != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $images_info_error;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($images_new_name_arr))
                {
                    $seller_data = ['cancelled_cheque'=>$images_new_name_arr[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }
                
                
                $this->response['redirect_to'] = base_url('register/step6/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Bank Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
            else
            {
                $seller_data = ['account_name'=>$this->input->post('account_name'),'account_number' => $this->input->post('account_number'), 'bank_code' => $this->input->post('bank_code'),'account_type'=> $this->input->post('account_type'), 'bank_name' => $this->input->post('bank_name'), 'bank_branch' => $this->input->post('bank_branch'), 'bank_city' => $this->input->post('bank_city'), 'bank_state' => $this->input->post('bank_state')];
                $seller_data = escape_array($seller_data);
                $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                
                if (!file_exists(FCPATH . RETAILER_CANCELLED_CHEQUE_IMG_PATH)) {
                    mkdir(FCPATH . RETAILER_CANCELLED_CHEQUE_IMG_PATH, 0777);
                }
    
                $temp_array = array();
                $files = $_FILES;
                $images_new_name_arr = array();
                $images_info_error = "";
                $allowed_media_types = implode('|', allowed_media_types());
                $config = [
                    'upload_path' =>  FCPATH . RETAILER_CANCELLED_CHEQUE_IMG_PATH,
                    'allowed_types' => $allowed_media_types,
                    'max_size' => 8000,
                ];
    
    
                if (!empty($_FILES['cancelled_cheque']['name']) && isset($_FILES['cancelled_cheque']['name'])) {
                    $other_image_cnt = count($_FILES['cancelled_cheque']['name']);
                    $other_img = $this->upload;
                    $other_img->initialize($config);
    

                    if (!empty($_FILES['cancelled_cheque']['name'])) {

                        $_FILES['temp_image']['name'] = $files['cancelled_cheque']['name'];
                        $_FILES['temp_image']['type'] = $files['cancelled_cheque']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['cancelled_cheque']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['cancelled_cheque']['error'];
                        $_FILES['temp_image']['size'] = $files['cancelled_cheque']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = 'attachments :' . $images_info_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array = $other_img->data();
                            resize_review_images($temp_array, FCPATH . RETAILER_CANCELLED_CHEQUE_IMG_PATH);
                            $images_new_name_arr[] = RETAILER_CANCELLED_CHEQUE_IMG_PATH . $temp_array['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files['cancelled_cheque']['name'];
                        $_FILES['temp_image']['type'] = $files['cancelled_cheque']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['cancelled_cheque']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['cancelled_cheque']['error'];
                        $_FILES['temp_image']['size'] = $files['cancelled_cheque']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = $other_img->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error != NULL || !$this->form_validation->run()) {
                        if (isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr as $key => $val) {
                                unlink(FCPATH . RETAILER_CANCELLED_CHEQUE_IMG_PATH . $images_new_name_arr[$key]);
                            }
                        }
                    }
                    
                    
                }
                
                if ($images_info_error != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $images_info_error;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($images_new_name_arr))
                {
                    $seller_data = ['cancelled_cheque'=>$images_new_name_arr[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                }
                
                $this->response['redirect_to'] = base_url('register/step6/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Bank Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
            
        }
    }
    
    public function step6($is_seller = 0)
    {
        if(!$this->ion_auth->logged_in()) {
            redirect(base_url());
        }
        else
        {
            $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->data['user_id']   = $this->ion_auth->get_user_id();
            
            if($is_seller)
            {
                $this->data['seller_data'] = $this->db->get_where('seller_data', array('user_id'=>$this->data['user_id']))->row();
            }
            else
            {
                $this->data['seller_data'] = $this->db->get_where('retailer_data', array('user_id'=>$this->data['user_id']))->row();
            }
            
            $this->data['is_seller'] = $is_seller;
            $this->data['main_page'] = 'register_step6';
            $this->data['title'] = 'GST | ' . $this->data['web_settings']['site_title'];
            
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
    }
    
    public function save_step6()
    {
        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->data['user_id']   = $this->ion_auth->get_user_id();
        
        $is_seller = $this->input->post('is_seller');
        
        $seller_info = array();
        if($is_seller)
        {
            $seller_info = $this->db->get_where('seller_data', array('user_id'=>$this->data['user_id']))->row_array();
        }
        else
        {
            $seller_info = $this->db->get_where('retailer_data', array('user_id'=>$this->data['user_id']))->row_array();
        }
        
        $this->form_validation->set_rules('have_gst_no', 'Have GST/Dont Have GST', 'required|xss_clean|trim');
        
        if($_POST['have_gst_no'] == 1) {
            $this->form_validation->set_rules('gst_no', 'GST Number', 'required|xss_clean|trim');
            
            if(empty($_FILES['gst_no_photo']['name']) && $seller_info['gst_no_photo'] == '')
            {
                $this->form_validation->set_rules('gst_no_photo', 'GST Number Photo', 'required');
            }
        }
        
        if($_POST['have_gst_no'] == 2) {
            $this->form_validation->set_rules('pan_number', 'PAN Number', 'required|xss_clean|trim');
            
            if(empty($_FILES['pan_no_photo']['name']) && $seller_info['pan_no_photo'] == '')
            {
                $this->form_validation->set_rules('pan_no_photo', 'PAN Number Photo', 'required');
            }
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
            
            if($is_seller)
            {
                $seller_data = ['have_gst_no' => $this->input->post('have_gst_no'), 'gst_no' => $this->input->post('gst_no'), 'pan_number' => $this->input->post('pan_number')];
                $seller_data = escape_array($seller_data);
                $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                
                if (!file_exists(FCPATH . SELLER_GST_IMG_PATH)) {
                    mkdir(FCPATH . SELLER_GST_IMG_PATH, 0777);
                }
    
                $temp_array = array();
                $files = $_FILES;
                $images_new_name_arr = array();
                $images_info_error = "";
                $allowed_media_types = implode('|', allowed_media_types());
                $config = [
                    'upload_path' =>  FCPATH . SELLER_GST_IMG_PATH,
                    'allowed_types' => $allowed_media_types,
                    'max_size' => 8000,
                ];
    
    
                if(!empty($_FILES['gst_no_photo']['name']) && isset($_FILES['gst_no_photo']['name'])) 
                {
                    $other_image_cnt = count($_FILES['gst_no_photo']['name']);
                    $other_img = $this->upload;
                    $other_img->initialize($config);
    

                    if (!empty($_FILES['gst_no_photo']['name'])) {

                        $_FILES['temp_image']['name'] = $files['gst_no_photo']['name'];
                        $_FILES['temp_image']['type'] = $files['gst_no_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['gst_no_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['gst_no_photo']['error'];
                        $_FILES['temp_image']['size'] = $files['gst_no_photo']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = 'attachments :' . $images_info_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array = $other_img->data();
                            resize_review_images($temp_array, FCPATH . SELLER_GST_IMG_PATH);
                            $images_new_name_arr[] = SELLER_GST_IMG_PATH . $temp_array['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files['gst_no_photo']['name'];
                        $_FILES['temp_image']['type'] = $files['gst_no_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['gst_no_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['gst_no_photo']['error'];
                        $_FILES['temp_image']['size'] = $files['gst_no_photo']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = $other_img->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error != NULL || !$this->form_validation->run()) {
                        if(isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr as $key => $val) {
                                unlink(FCPATH . SELLER_GST_IMG_PATH . $images_new_name_arr[$key]);
                            }
                        }
                    }
                }
                
                if ($images_info_error != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $images_info_error;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($images_new_name_arr))
                {
                    $seller_data = ['gst_no_photo'=>$images_new_name_arr[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }
                
                if (!file_exists(FCPATH . SELLER_PAN_IMG_PATH)) {
                    mkdir(FCPATH . SELLER_PAN_IMG_PATH, 0777);
                }
                
                $temp_array2 = array();
                $files2 = $_FILES;
                $images_new_name_arr2 = array();
                $images_info_error2 = "";
                $allowed_media_types2 = implode('|', allowed_media_types());
                $config2 = [
                    'upload_path' =>  FCPATH . SELLER_PAN_IMG_PATH,
                    'allowed_types' => $allowed_media_types2,
                    'max_size' => 8000,
                ];
    
    
                if (!empty($_FILES['pan_no_photo']['name']) && isset($_FILES['pan_no_photo']['name'])) 
                {
                    $other_image_cnt2 = count($_FILES['pan_no_photo']['name']);
                    $other_img2 = $this->upload;
                    $other_img2->initialize($config2);
    

                    if (!empty($_FILES['pan_no_photo']['name'])) {

                        $_FILES['temp_image']['name'] = $files2['pan_no_photo']['name'];
                        $_FILES['temp_image']['type'] = $files2['pan_no_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files2['pan_no_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files2['pan_no_photo']['error'];
                        $_FILES['temp_image']['size'] = $files2['pan_no_photo']['size'];
                        if (!$other_img2->do_upload('temp_image')) {
                            $images_info_error2 = 'attachments :' . $images_info_error2 . ' ' . $other_img2->display_errors();
                        } else {
                            $temp_array2 = $other_img2->data();
                            resize_review_images($temp_array2, FCPATH . SELLER_PAN_IMG_PATH);
                            $images_new_name_arr2[] = SELLER_PAN_IMG_PATH . $temp_array2['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files2['pan_no_photo']['name'];
                        $_FILES['temp_image']['type'] = $files2['pan_no_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files2['pan_no_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files2['pan_no_photo']['error'];
                        $_FILES['temp_image']['size'] = $files2['pan_no_photo']['size'];
                        if (!$other_img2->do_upload('temp_image')) {
                            $images_info_error2 = $other_img2->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error2 != NULL || !$this->form_validation->run()) {
                        if(isset($images_new_name_arr2) && !empty($images_new_name_arr2 || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr2 as $key => $val) {
                                unlink(FCPATH . SELLER_PAN_IMG_PATH . $images_new_name_arr2[$key]);
                            }
                        }
                    }
                }
                
                if ($images_info_error2 != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $images_info_error2;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($images_new_name_arr2))
                {
                    $seller_data = ['pan_no_photo'=>$images_new_name_arr2[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }
                
                $this->response['redirect_to'] = base_url('register/step7/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'GST Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
            else
            {
                $seller_data = ['have_gst_no' => $this->input->post('have_gst_no'), 'gst_no' => $this->input->post('gst_no'), 'pan_number' => $this->input->post('pan_number')];
                $seller_data = escape_array($seller_data);
                $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                
                
                
                if (!file_exists(FCPATH . RETAILER_GST_IMG_PATH)) {
                    mkdir(FCPATH . RETAILER_GST_IMG_PATH, 0777);
                }
    
                $temp_array = array();
                $files = $_FILES;
                $images_new_name_arr = array();
                $images_info_error = "";
                $allowed_media_types = implode('|', allowed_media_types());
                $config = [
                    'upload_path' =>  FCPATH . RETAILER_GST_IMG_PATH,
                    'allowed_types' => $allowed_media_types,
                    'max_size' => 8000,
                ];
    
    
                if (!empty($_FILES['gst_no_photo']['name']) && isset($_FILES['gst_no_photo']['name'])) 
                {
                    $other_image_cnt = count($_FILES['gst_no_photo']['name']);
                    $other_img = $this->upload;
                    $other_img->initialize($config);
    

                    if (!empty($_FILES['gst_no_photo']['name'])) {

                        $_FILES['temp_image']['name'] = $files['gst_no_photo']['name'];
                        $_FILES['temp_image']['type'] = $files['gst_no_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['gst_no_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['gst_no_photo']['error'];
                        $_FILES['temp_image']['size'] = $files['gst_no_photo']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = 'attachments :' . $images_info_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array = $other_img->data();
                            resize_review_images($temp_array, FCPATH . RETAILER_GST_IMG_PATH);
                            $images_new_name_arr[] = RETAILER_GST_IMG_PATH . $temp_array['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files['gst_no_photo']['name'];
                        $_FILES['temp_image']['type'] = $files['gst_no_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['gst_no_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['gst_no_photo']['error'];
                        $_FILES['temp_image']['size'] = $files['gst_no_photo']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = $other_img->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error != NULL || !$this->form_validation->run()) {
                        if(isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr as $key => $val) {
                                unlink(FCPATH . RETAILER_GST_IMG_PATH . $images_new_name_arr[$key]);
                            }
                        }
                    }
                }
                
                if ($images_info_error != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $images_info_error;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($images_new_name_arr))
                {
                    $seller_data = ['gst_no_photo'=>$images_new_name_arr[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                }
                
                if (!file_exists(FCPATH . RETAILER_PAN_IMG_PATH)) {
                    mkdir(FCPATH . RETAILER_PAN_IMG_PATH, 0777);
                }
                
                $temp_array2 = array();
                $files2 = $_FILES;
                $images_new_name_arr2 = array();
                $images_info_error2 = "";
                $allowed_media_types2 = implode('|', allowed_media_types());
                $config2 = [
                    'upload_path' =>  FCPATH . RETAILER_PAN_IMG_PATH,
                    'allowed_types' => $allowed_media_types2,
                    'max_size' => 8000,
                ];
    
    
                if (!empty($_FILES['pan_no_photo']['name']) && isset($_FILES['pan_no_photo']['name'])) 
                {
                    $other_image_cnt2 = count($_FILES['pan_no_photo']['name']);
                    $other_img2 = $this->upload;
                    $other_img2->initialize($config2);
    

                    if (!empty($_FILES['pan_no_photo']['name'])) {

                        $_FILES['temp_image']['name'] = $files2['pan_no_photo']['name'];
                        $_FILES['temp_image']['type'] = $files2['pan_no_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files2['pan_no_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files2['pan_no_photo']['error'];
                        $_FILES['temp_image']['size'] = $files2['pan_no_photo']['size'];
                        if (!$other_img2->do_upload('temp_image')) {
                            $images_info_error2 = 'attachments :' . $images_info_error2 . ' ' . $other_img2->display_errors();
                        } else {
                            $temp_array2 = $other_img2->data();
                            resize_review_images($temp_array2, FCPATH . RETAILER_PAN_IMG_PATH);
                            $images_new_name_arr2[] = RETAILER_PAN_IMG_PATH . $temp_array2['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files2['pan_no_photo']['name'];
                        $_FILES['temp_image']['type'] = $files2['pan_no_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files2['pan_no_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files2['pan_no_photo']['error'];
                        $_FILES['temp_image']['size'] = $files2['pan_no_photo']['size'];
                        if (!$other_img2->do_upload('temp_image')) {
                            $images_info_error2 = $other_img2->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error2 != NULL || !$this->form_validation->run()) {
                        if(isset($images_new_name_arr2) && !empty($images_new_name_arr2 || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr2 as $key => $val) {
                                unlink(FCPATH . RETAILER_PAN_IMG_PATH . $images_new_name_arr2[$key]);
                            }
                        }
                    }
                }
                
                if ($images_info_error2 != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $images_info_error2;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($images_new_name_arr2))
                {
                    $seller_data = ['pan_no_photo'=>$images_new_name_arr2[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                }
                
                $this->response['redirect_to'] = base_url('register/step7/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'GST Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
        }
    }
    
    public function step7($is_seller = 0)
    {
        if(!$this->ion_auth->logged_in()) {
            redirect(base_url());
        }
        else
        {
            $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->data['user_id']   = $this->ion_auth->get_user_id();
            
            if($is_seller)
            {
                $this->data['seller_data'] = $this->db->get_where('seller_data', array('user_id'=>$this->data['user_id']))->row();
            }
            else
            {
                $this->data['seller_data'] = $this->db->get_where('retailer_data', array('user_id'=>$this->data['user_id']))->row();
            }
            
            $this->data['is_seller'] = $is_seller;
            $this->data['main_page'] = 'register_step7';
            $this->data['title'] = 'License | ' . $this->data['web_settings']['site_title'];
            
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
    }
    
    public function save_step7()
    {
        $is_seller = $this->input->post('is_seller');
        
        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->data['user_id']   = $this->ion_auth->get_user_id();
        
        $seller_info = array();
        if($is_seller)
        {
            $seller_info = $this->db->get_where('seller_data', array('user_id'=>$this->data['user_id']))->row_array();
        }
        else
        {
            $seller_info = $this->db->get_where('retailer_data', array('user_id'=>$this->data['user_id']))->row_array();
        }
        
        $flag = 0;
        if($_POST['have_fertilizer_license'] == 1) {
            $this->form_validation->set_rules('fertilizer_license_no', 'Fertilizer License Number', 'required|xss_clean|trim');
            $this->form_validation->set_rules('fert_lic_expiry_date', 'Fertilizer License Expiry Date', 'required|xss_clean|trim');
            if(empty($_FILES['fertilizer_license_photo']['name']) && $seller_info['fertilizer_license_photo'] == '')
            {
                $this->form_validation->set_rules('fertilizer_license_photo', 'Fertilizer License Photo', 'required');
            }
            $flag = 1;
        }
        
        if($_POST['have_pesticide_license_no'] == 1) {
            $this->form_validation->set_rules('pesticide_license_no', 'Pesticide License Number', 'required|xss_clean|trim');
            $this->form_validation->set_rules('pest_lic_expiry_date', 'Pesticide License Expiry Date', 'required|xss_clean|trim');
            if(empty($_FILES['pesticide_license_photo']['name']) && $seller_info['pesticide_license_photo'] == '')
            {
                $this->form_validation->set_rules('pesticide_license_photo', 'Pesticide License Photo', 'required|xss_clean|trim');
            }
            $flag = 1;
        }
        
        if($_POST['have_seeds_license_no'] == 1) {
            $this->form_validation->set_rules('seeds_license_no', 'Seeds License Number', 'required|xss_clean|trim');
            $this->form_validation->set_rules('seeds_lic_expiry_date', 'Seeds License Expiry Date', 'required|xss_clean|trim');
            if(empty($_FILES['seeds_license_photo']['name']) && $seller_info['seeds_license_photo'] == '')
            {
                $this->form_validation->set_rules('seeds_license_photo', 'Seeds License Photo', 'required|xss_clean|trim');
            }
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
            
            if($is_seller)
            {
                $seller_data = ['have_fertilizer_license' => $this->input->post('have_fertilizer_license'), 'fertilizer_license_no' => $this->input->post('fertilizer_license_no'),'fert_lic_expiry_date'=>$this->input->post('fert_lic_expiry_date'), 'have_pesticide_license_no' => $this->input->post('have_pesticide_license_no'), 'pesticide_license_no' => $this->input->post('pesticide_license_no'),'pest_lic_expiry_date'=>$this->input->post('pest_lic_expiry_date'), 'have_seeds_license_no' => $this->input->post('have_seeds_license_no'), 'seeds_license_no' => $this->input->post('seeds_license_no'),'seeds_lic_expiry_date'=>$this->input->post('seeds_lic_expiry_date'),'is_finish' => $this->input->post('is_finish')];
                $seller_data = escape_array($seller_data);
                $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                
                if (!file_exists(FCPATH . SELLER_FERT_LIC_IMG_PATH)) {
                    mkdir(FCPATH . SELLER_FERT_LIC_IMG_PATH, 0777);
                }
    
                $temp_array = array();
                $files = $_FILES;
                $images_new_name_arr = array();
                $images_info_error = "";
                $allowed_media_types = implode('|', allowed_media_types());
                $config = [
                    'upload_path' =>  FCPATH . SELLER_FERT_LIC_IMG_PATH,
                    'allowed_types' => $allowed_media_types,
                    'max_size' => 8000,
                ];
    
    
                if(!empty($_FILES['fertilizer_license_photo']['name']) && isset($_FILES['fertilizer_license_photo']['name'])) 
                {
                    $other_image_cnt = count($_FILES['fertilizer_license_photo']['name']);
                    $other_img = $this->upload;
                    $other_img->initialize($config);
    

                    if (!empty($_FILES['fertilizer_license_photo']['name'])) {

                        $_FILES['temp_image']['name'] = $files['fertilizer_license_photo']['name'];
                        $_FILES['temp_image']['type'] = $files['fertilizer_license_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['fertilizer_license_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['fertilizer_license_photo']['error'];
                        $_FILES['temp_image']['size'] = $files['fertilizer_license_photo']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = 'attachments :' . $images_info_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array = $other_img->data();
                            resize_review_images($temp_array, FCPATH . SELLER_FERT_LIC_IMG_PATH);
                            $images_new_name_arr[] = SELLER_FERT_LIC_IMG_PATH . $temp_array['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files['fertilizer_license_photo']['name'];
                        $_FILES['temp_image']['type'] = $files['fertilizer_license_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['fertilizer_license_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['fertilizer_license_photo']['error'];
                        $_FILES['temp_image']['size'] = $files['fertilizer_license_photo']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = $other_img->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error != NULL || !$this->form_validation->run()) {
                        if(isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr as $key => $val) {
                                unlink(FCPATH . SELLER_FERT_LIC_IMG_PATH . $images_new_name_arr[$key]);
                            }
                        }
                    }
                }
                
                if ($images_info_error != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $images_info_error;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($images_new_name_arr))
                {
                    $seller_data = ['fertilizer_license_photo'=>$images_new_name_arr[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }
                
                if (!file_exists(FCPATH . SELLER_PEST_LIC_IMG_PATH)) {
                    mkdir(FCPATH . SELLER_PEST_LIC_IMG_PATH, 0777);
                }
                
                $temp_array2 = array();
                $files2 = $_FILES;
                $images_new_name_arr2 = array();
                $images_info_error2 = "";
                $allowed_media_types2 = implode('|', allowed_media_types());
                $config2 = [
                    'upload_path' =>  FCPATH . SELLER_PEST_LIC_IMG_PATH,
                    'allowed_types' => $allowed_media_types2,
                    'max_size' => 8000,
                ];
    
    
                if (!empty($_FILES['pesticide_license_photo']['name']) && isset($_FILES['pesticide_license_photo']['name'])) 
                {
                    $other_image_cnt2 = count($_FILES['pesticide_license_photo']['name']);
                    $other_img2 = $this->upload;
                    $other_img2->initialize($config2);
    

                    if (!empty($_FILES['pesticide_license_photo']['name'])) {

                        $_FILES['temp_image']['name'] = $files2['pesticide_license_photo']['name'];
                        $_FILES['temp_image']['type'] = $files2['pesticide_license_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files2['pesticide_license_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files2['pesticide_license_photo']['error'];
                        $_FILES['temp_image']['size'] = $files2['pesticide_license_photo']['size'];
                        if (!$other_img2->do_upload('temp_image')) {
                            $images_info_error2 = 'attachments :' . $images_info_error2 . ' ' . $other_img2->display_errors();
                        } else {
                            $temp_array2 = $other_img2->data();
                            resize_review_images($temp_array2, FCPATH . SELLER_PEST_LIC_IMG_PATH);
                            $images_new_name_arr2[] = SELLER_PEST_LIC_IMG_PATH . $temp_array2['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files2['pesticide_license_photo']['name'];
                        $_FILES['temp_image']['type'] = $files2['pesticide_license_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files2['pesticide_license_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files2['pesticide_license_photo']['error'];
                        $_FILES['temp_image']['size'] = $files2['pesticide_license_photo']['size'];
                        if (!$other_img2->do_upload('temp_image')) {
                            $images_info_error2 = $other_img2->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error2 != NULL || !$this->form_validation->run()) {
                        if(isset($images_new_name_arr2) && !empty($images_new_name_arr2 || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr2 as $key => $val) {
                                unlink(FCPATH . SELLER_PEST_LIC_IMG_PATH . $images_new_name_arr2[$key]);
                            }
                        }
                    }
                }
                
                if ($images_info_error2 != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $images_info_error2;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($images_new_name_arr2))
                {
                    $seller_data = ['pesticide_license_photo'=>$images_new_name_arr2[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }
                
                
                if (!file_exists(FCPATH . SELLER_SEEDS_LIC_IMG_PATH)) {
                    mkdir(FCPATH . SELLER_SEEDS_LIC_IMG_PATH, 0777);
                }
                
                $temp_array3 = array();
                $files3 = $_FILES;
                $images_new_name_arr3 = array();
                $images_info_error3 = "";
                $allowed_media_types3 = implode('|', allowed_media_types());
                $config3 = [
                    'upload_path' =>  FCPATH . SELLER_SEEDS_LIC_IMG_PATH,
                    'allowed_types' => $allowed_media_types3,
                    'max_size' => 8000,
                ];
    
    
                if (!empty($_FILES['seeds_license_photo']['name']) && isset($_FILES['seeds_license_photo']['name'])) 
                {
                    $other_image_cnt3 = count($_FILES['seeds_license_photo']['name']);
                    $other_img3 = $this->upload;
                    $other_img3->initialize($config3);
    

                    if (!empty($_FILES['seeds_license_photo']['name'])) {

                        $_FILES['temp_image']['name'] = $files3['seeds_license_photo']['name'];
                        $_FILES['temp_image']['type'] = $files3['seeds_license_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files3['seeds_license_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files3['seeds_license_photo']['error'];
                        $_FILES['temp_image']['size'] = $files3['seeds_license_photo']['size'];
                        if (!$other_img3->do_upload('temp_image')) {
                            $images_info_error3 = 'attachments :' . $images_info_error3 . ' ' . $other_img3->display_errors();
                        } else {
                            $temp_array3 = $other_img3->data();
                            resize_review_images($temp_array3, FCPATH . SELLER_SEEDS_LIC_IMG_PATH);
                            $images_new_name_arr3[] = SELLER_SEEDS_LIC_IMG_PATH . $temp_array3['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files3['seeds_license_photo']['name'];
                        $_FILES['temp_image']['type'] = $files3['seeds_license_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files3['seeds_license_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files3['seeds_license_photo']['error'];
                        $_FILES['temp_image']['size'] = $files3['seeds_license_photo']['size'];
                        if (!$other_img3->do_upload('temp_image')) {
                            $images_info_error3 = $other_img3->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error3 != NULL || !$this->form_validation->run()) {
                        if(isset($images_new_name_arr3) && !empty($images_new_name_arr3 || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr3 as $key => $val) {
                                unlink(FCPATH . SELLER_SEEDS_LIC_IMG_PATH . $images_new_name_arr3[$key]);
                            }
                        }
                    }
                }
                
                if ($images_info_error3 != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $images_info_error3;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($images_new_name_arr3))
                {
                    $seller_data = ['seeds_license_photo'=>$images_new_name_arr3[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }
                
                /****** Manufacturer Mail ******/

                $user_id    = $this->ion_auth->get_user_id();
                
                $system_settings = get_settings('system_settings', true);
                $user = fetch_details(['id' => $user_id], 'users');
    
                $seller = fetch_details(['user_id' => $user_id], 'seller_data');
                
                if ($user[0]['reg_mail_sent']==0)
                {   
                    //to manufacturer
                    if($user[0]['email']!='')
                    {
                        $html_text  = '<p style="margin-bottom:0px;">Dear '. $seller[0]['company_name'] .',</p>';
                        $html_text  .= '<p>Welcome to Happycrop, and thank you for registering on our platform. We are thrilled to have you join our growing community of agricultural industry leaders.</p>';
                        $html_text  .= '<p>Rest assured that our team is working diligently to review your registration. This process typically takes between 24 to 48 working hours. Once your registration is verified, you will receive a confirmation email, and you will be all set to access the full range of benefits and opportunities that Happycrop offers.</p>';
        
                        $html_text  .= '<p style="margin-top: 5px; margin-bottom:0px;">Heres a glimpse of what you can look forward to as a Happycrop member:</p>';
                        $html_text  .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>1.Increased Visibility:  </b>Showcase your agricultural products to a wide and diverse audience of retailers and farmers.</p>';
                        $html_text  .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>2.Streamlined Operations: </b>Enjoy simplified transactions, efficient order management, and reduced administrative work.</p>';
                        $html_text  .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>3.Market Insights: </b>Gain access to valuable market insights and trends to make informed business decisions.</p>';
                        $html_text  .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>4.Dedicated Support: </b>Our support team is here to assist you at every step of your journey on Happycrop.</p>';
                       
                        $html_text  .= '<p>Thank you for choosing Happycrop as your platform of choice. We are committed to helping you succeed in the agriculture input industry.</p>';
        
                        $html_text  .= '<p>If you have any immediate questions or need assistance during this process, please feel free to reach out to our support team at <a href="mailto:support@happycrop.in">support@happycrop.in</a> or +91 9975548343. We are here to make your onboarding experience as smooth as possible.</p>';
        
                        $html_text  .= '<p>Once again, welcome to Happycrop. We are looking forward to having you as part of our community.</p>';
        
                        $html_text  .= '<p style="margin-bottom:0px;">Best regards,</p>';
                        $html_text  .= '<p style="margin-top:0px;">Happycrop Support Team</p>';
                        
                        $order_info = array(
                            'subject'        => 'Welcome to Happycrop: Your Registration is Under Review',
                            'user_msg'       => $html_text,
                            'show_foot_note' => false,
                        );
            
                        send_mail2($user[0]['email'], 'Welcome to Happycrop: Your Registration is Under Review', $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    }
                    
                    //to admin
                    if($system_settings['support_email']!='')
                    {
                        $html_text  = '<p style="margin-bottom:0px;">Dear Happycrop Team,</p>';
                        $html_text  .= '<p style="margin-top: 0px;">We have received a new Manufacturer registration that has been submitted through our Happycrop platform.</p>';
        
                        $html_text  .= '<p style="margin-top: 5px; margin-bottom:0px;"><b>Registration Details:</b></p>';
                        $html_text  .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>Name: '. $user[0]['username'] .'</b></p>';
                        $html_text  .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>Email Address: '. $user[0]['email'] .'</b></p>';
                        $html_text  .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>Phone Number: '. $user[0]['mobile'] .'</b></p>';
                        $html_text  .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>Company Name: '. $seller[0]['company_name'] .'</b></p>';
        
                        $html_text  .= '<p style="margin-top: 5px;">We kindly request you to take the necessary steps to respond to this registration promptly. If you require any additional information kindly revert to the manufacturer email address or contact on manufacturer phone number.</p>';
        
        
                        $html_text .= '<p style="margin-bottom:0px;">Best Regards,</p>';
                        $html_text .= '<p style="margin-top: 0px;">Happycrop Support Team</p>';
                       
                        
                        $order_info = array(
                            'subject'        => 'New Manufacturer registration Received on Happycrop Platform',
                            'user_msg'       => $html_text,
                            'show_foot_note' => false,
                        );
                        //send_mail2($system_settings['support_email'], 'New Manufacturer registration Received on Happycrop Platform', $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                         send_mail2('support@happycrop.in', 'New Manufacturer registration Received on Happycrop Platform', $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
               
                    }
    
                    $email_sent = ['reg_mail_sent' => 1];
                    $email_sent = escape_array($email_sent);
                    $this->db->set($email_sent)->where('id', $user_id)->update('users');
                }
    
                /****** END Manufacturer Mail ******/
                
                $this->response['redirect_to'] = base_url('register/step7/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'License Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
            else
            {
                $seller_data = ['have_fertilizer_license' => $this->input->post('have_fertilizer_license'), 'fertilizer_license_no' => $this->input->post('fertilizer_license_no'),'fert_lic_expiry_date'=>$this->input->post('fert_lic_expiry_date'), 'have_pesticide_license_no' => $this->input->post('have_pesticide_license_no'), 'pesticide_license_no' => $this->input->post('pesticide_license_no'),'pest_lic_expiry_date'=>$this->input->post('pest_lic_expiry_date'), 'have_seeds_license_no' => $this->input->post('have_seeds_license_no'), 'seeds_license_no' => $this->input->post('seeds_license_no'),'seeds_lic_expiry_date'=>$this->input->post('seeds_lic_expiry_date'),'is_finish' => $this->input->post('is_finish')];
                $seller_data = escape_array($seller_data);
                $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                
                
                if (!file_exists(FCPATH . RETAILER_FERT_LIC_IMG_PATH)) {
                    mkdir(FCPATH . RETAILER_FERT_LIC_IMG_PATH, 0777);
                }
    
                $temp_array = array();
                $files = $_FILES;
                $images_new_name_arr = array();
                $images_info_error = "";
                $allowed_media_types = implode('|', allowed_media_types());
                $config = [
                    'upload_path' =>  FCPATH . RETAILER_FERT_LIC_IMG_PATH,
                    'allowed_types' => $allowed_media_types,
                    'max_size' => 8000,
                ];
    
    
                if(!empty($_FILES['fertilizer_license_photo']['name']) && isset($_FILES['fertilizer_license_photo']['name'])) 
                {
                    $other_image_cnt = count($_FILES['fertilizer_license_photo']['name']);
                    $other_img = $this->upload;
                    $other_img->initialize($config);
    

                    if (!empty($_FILES['fertilizer_license_photo']['name'])) {

                        $_FILES['temp_image']['name'] = $files['fertilizer_license_photo']['name'];
                        $_FILES['temp_image']['type'] = $files['fertilizer_license_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['fertilizer_license_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['fertilizer_license_photo']['error'];
                        $_FILES['temp_image']['size'] = $files['fertilizer_license_photo']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = 'attachments :' . $images_info_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array = $other_img->data();
                            resize_review_images($temp_array, FCPATH . RETAILER_FERT_LIC_IMG_PATH);
                            $images_new_name_arr[] = RETAILER_FERT_LIC_IMG_PATH . $temp_array['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files['fertilizer_license_photo']['name'];
                        $_FILES['temp_image']['type'] = $files['fertilizer_license_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['fertilizer_license_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['fertilizer_license_photo']['error'];
                        $_FILES['temp_image']['size'] = $files['fertilizer_license_photo']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = $other_img->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error != NULL || !$this->form_validation->run()) {
                        if(isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr as $key => $val) {
                                unlink(FCPATH . RETAILER_FERT_LIC_IMG_PATH . $images_new_name_arr[$key]);
                            }
                        }
                    }
                }
                
                if ($images_info_error != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $images_info_error;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($images_new_name_arr))
                {
                    $seller_data = ['fertilizer_license_photo'=>$images_new_name_arr[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                }
                
                if (!file_exists(FCPATH . RETAILER_PEST_LIC_IMG_PATH)) {
                    mkdir(FCPATH . RETAILER_PEST_LIC_IMG_PATH, 0777);
                }
                
                $temp_array2 = array();
                $files2 = $_FILES;
                $images_new_name_arr2 = array();
                $images_info_error2 = "";
                $allowed_media_types2 = implode('|', allowed_media_types());
                $config2 = [
                    'upload_path' =>  FCPATH . RETAILER_PEST_LIC_IMG_PATH,
                    'allowed_types' => $allowed_media_types2,
                    'max_size' => 8000,
                ];
    
    
                if (!empty($_FILES['pesticide_license_photo']['name']) && isset($_FILES['pesticide_license_photo']['name'])) 
                {
                    $other_image_cnt2 = count($_FILES['pesticide_license_photo']['name']);
                    $other_img2 = $this->upload;
                    $other_img2->initialize($config2);
    

                    if (!empty($_FILES['pesticide_license_photo']['name'])) {

                        $_FILES['temp_image']['name'] = $files2['pesticide_license_photo']['name'];
                        $_FILES['temp_image']['type'] = $files2['pesticide_license_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files2['pesticide_license_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files2['pesticide_license_photo']['error'];
                        $_FILES['temp_image']['size'] = $files2['pesticide_license_photo']['size'];
                        if (!$other_img2->do_upload('temp_image')) {
                            $images_info_error2 = 'attachments :' . $images_info_error2 . ' ' . $other_img2->display_errors();
                        } else {
                            $temp_array2 = $other_img2->data();
                            resize_review_images($temp_array2, FCPATH . RETAILER_PEST_LIC_IMG_PATH);
                            $images_new_name_arr2[] = RETAILER_PEST_LIC_IMG_PATH . $temp_array2['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files2['pesticide_license_photo']['name'];
                        $_FILES['temp_image']['type'] = $files2['pesticide_license_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files2['pesticide_license_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files2['pesticide_license_photo']['error'];
                        $_FILES['temp_image']['size'] = $files2['pesticide_license_photo']['size'];
                        if (!$other_img2->do_upload('temp_image')) {
                            $images_info_error2 = $other_img2->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error2 != NULL || !$this->form_validation->run()) {
                        if(isset($images_new_name_arr2) && !empty($images_new_name_arr2 || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr2 as $key => $val) {
                                unlink(FCPATH . RETAILER_PEST_LIC_IMG_PATH . $images_new_name_arr2[$key]);
                            }
                        }
                    }
                }
                
                if ($images_info_error2 != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $images_info_error2;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($images_new_name_arr2))
                {
                    $seller_data = ['pesticide_license_photo'=>$images_new_name_arr2[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                }
                
                
                if (!file_exists(FCPATH . RETAILER_SEEDS_LIC_IMG_PATH)) {
                    mkdir(FCPATH . RETAILER_SEEDS_LIC_IMG_PATH, 0777);
                }
                
                $temp_array3 = array();
                $files3 = $_FILES;
                $images_new_name_arr3 = array();
                $images_info_error3 = "";
                $allowed_media_types3 = implode('|', allowed_media_types());
                $config3 = [
                    'upload_path' =>  FCPATH . RETAILER_SEEDS_LIC_IMG_PATH,
                    'allowed_types' => $allowed_media_types3,
                    'max_size' => 8000,
                ];
    
    
                if (!empty($_FILES['seeds_license_photo']['name']) && isset($_FILES['seeds_license_photo']['name'])) 
                {
                    $other_image_cnt3 = count($_FILES['seeds_license_photo']['name']);
                    $other_img3 = $this->upload;
                    $other_img3->initialize($config3);
    

                    if (!empty($_FILES['seeds_license_photo']['name'])) {

                        $_FILES['temp_image']['name'] = $files3['seeds_license_photo']['name'];
                        $_FILES['temp_image']['type'] = $files3['seeds_license_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files3['seeds_license_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files3['seeds_license_photo']['error'];
                        $_FILES['temp_image']['size'] = $files3['seeds_license_photo']['size'];
                        if (!$other_img3->do_upload('temp_image')) {
                            $images_info_error3 = 'attachments :' . $images_info_error3 . ' ' . $other_img3->display_errors();
                        } else {
                            $temp_array3 = $other_img3->data();
                            resize_review_images($temp_array3, FCPATH . RETAILER_SEEDS_LIC_IMG_PATH);
                            $images_new_name_arr3[] = RETAILER_SEEDS_LIC_IMG_PATH . $temp_array3['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files3['seeds_license_photo']['name'];
                        $_FILES['temp_image']['type'] = $files3['seeds_license_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files3['seeds_license_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files3['seeds_license_photo']['error'];
                        $_FILES['temp_image']['size'] = $files3['seeds_license_photo']['size'];
                        if (!$other_img3->do_upload('temp_image')) {
                            $images_info_error3 = $other_img3->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error3 != NULL || !$this->form_validation->run()) {
                        if(isset($images_new_name_arr3) && !empty($images_new_name_arr3 || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr3 as $key => $val) {
                                unlink(FCPATH . RETAILER_SEEDS_LIC_IMG_PATH . $images_new_name_arr3[$key]);
                            }
                        }
                    }
                }
                
                if ($images_info_error3 != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $images_info_error3;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($images_new_name_arr3))
                {
                    $seller_data = ['seeds_license_photo'=>$images_new_name_arr3[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                }
                
                /****** Retailer Mail ******/
                $user_id    = $this->ion_auth->get_user_id();
                
                $system_settings = get_settings('system_settings', true);
                $user = fetch_details(['id' => $user_id], 'users');
    
                $retailer = fetch_details(['user_id' => $user_id], 'retailer_data');
                
                if ($user[0]['reg_mail_sent']==0)
                {
                    //to retailer
                    if($user[0]['email']!='')
                    {
                        $html_text  = '<p style="margin-bottom:0px;">Dear '. $retailer[0]['company_name'] .',</p>';
                        $html_text  .= '<p>Welcome to Happycrop, the future of agricultural trading! We are thrilled that you have chosen to register with us.</p>';
                        $html_text  .= '<p>Your interest in Happycrop demonstrates your commitment to modernizing and streamlining the agricultural input industry. We are equally committed to providing you with a top-notch experience. Your registration is currently under review to ensure the accuracy of the information provided.</p>';
                        
                        $html_text  .= '<p style="margin-top: 5px; margin-bottom:0px;">Here are a few things you can expect:</p>';
                       
                        $html_text  .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>1.Verification Process:  </b>Our team is dedicated to ensuring the authenticity and credibility of all our members. Your registration will be confirmed within 24 to 48 working hours after the successful verification of your submitted details.</p>';
                        
                        $html_text  .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>2.Access to a Thriving Marketplace: </b>Upon verification, you will gain access to a dynamic marketplace where you can discover new opportunities, connect with manufacturers, and explore a wide range of agricultural products.</p>';
        
                        $html_text  .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>3.Simplified Transactions: </b>Happycrop simplifies the trading process. You can manage your products, manage orders, and streamline transactions with ease.</p>';
        
                        $html_text  .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>4.Support at Your Fingertips: </b>Our customer support team is here to assist you. If you have any questions or need assistance, please dont hesitate to contact us at <a href="mailto:support@happycrop.in">support@happycrop.in</a> or +91 9975548343.</p>';
                        $html_text  .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>5.Stay Informed: </b>We will keep you updated on platform news, best practices, and market insights to help you make informed decisions.</p>';
        
                        $html_text  .= '<p>Thank you for choosing Happycrop. We believe that together, we can revolutionize the agricultural input industry. Your trust in us is deeply appreciated, and we look forward to welcoming you fully onto our platform.</p>';
        
                        $html_text  .= '<p>Should you have any immediate questions or require assistance during the registration process, please feel free to contact us. Your success is our priority.</p>';
        
                        $html_text  .= '<p style="margin-bottom:0px;">Best regards,</p>';
                        $html_text  .= '<p style="margin-top:0px;">Happycrop Support Team</p>';
                        
                        $order_info = array(
                            'subject'        => 'Welcome to Happycrop: Your Registration is Under Review',
                            'user_msg'       => $html_text,
                            'show_foot_note' => false,
                        );
            
                        send_mail2($user[0]['email'], 'Welcome to Happycrop: Your Registration is Under Review', $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                   
                    }
    
                    //to admin
                    if($system_settings['support_email']!='')
                    {
                        $html_text  = '<p style="margin-bottom:0px;">Dear Happycrop Team,</p>';
                        $html_text  .= '<p style="margin-top: 0px;">We have received a new Retailer registration that has been submitted through our Happycrop platform.</p>';
        
                        $html_text  .= '<p style="margin-top: 5px; margin-bottom:0px;"><b>Registration Details:</b></p>';
                        $html_text  .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>Name: '. $user[0]['username'] .'</b></p>';
                        $html_text  .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>Email Address: '. $user[0]['email'] .'</b></p>';
                        $html_text  .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>Phone Number: '. $user[0]['mobile'] .'</b></p>';
                        $html_text  .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>Shop Name: '. $retailer[0]['company_name'] .'</b></p>';
        
                        $html_text  .= '<p style="margin-top: 5px;">We kindly request you to take the necessary steps to respond to this registration promptly. If you require any additional information kindly revert to the retailer email address or contact on retailer phone number.</p>';
        
        
                        $html_text .= '<p style="margin-top: 5px; margin-bottom:0px;">Best Regards,</p>';
                        $html_text .= '<p style="margin-top: 0px;">Happycrop Support Team</p>';
                       
                        
                        $order_info = array(
                            'subject'        => 'New Retailer registration Received on Happycrop Platform',
                            'user_msg'       => $html_text,
                            'show_foot_note' => false,
                        );
            
                        //send_mail2($system_settings['support_email'], 'New Retailer registration Received on Happycrop Platform', $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                        send_mail2('support@happycrop.in', 'New Retailer registration Received on Happycrop Platform', $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    }
    
                    $email_sent = ['reg_mail_sent' => 1];
                    $email_sent = escape_array($email_sent);
                    $this->db->set($email_sent)->where('id', $user_id)->update('users');
    
                }    
                
                $this->response['redirect_to'] = base_url('register/step7/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'License Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
            
        }
    }

    public function login_check()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'home';
            $this->data['title'] = 'Login Panel | ' . $this->data['settings']['app_name'];
            $this->data['meta_description'] = 'Login Panel | ' . $this->data['settings']['app_name'];

            $identity_column = $this->config->item('identity', 'ion_auth');
            if ($identity_column == 'mobile') {
                $this->form_validation->set_rules('mobile', 'Mobile', 'trim|numeric|required|xss_clean');
            } elseif ($identity_column == 'email') {
                $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
            } else {
                $this->form_validation->set_rules('identity', 'Identity', 'trim|required|xss_clean');
            }
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

            $login = $this->ion_auth->login($this->input->post('mobile'), $this->input->post('password'));
            if ($login) {
                $data = fetch_details(['mobile' => $this->input->post('mobile', true)], 'users');
                $username = $this->session->set_userdata('username', $data[0]['username']);
                $this->response['error'] = false;
                $this->response['message'] = 'Login Succesfully';
                echo json_encode($this->response);
                return false;
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'Mobile Number or Password is wrong.';
                echo json_encode($this->response);
                return false;
            }
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'You are already logged in.';
            echo json_encode($this->response);
            return false;
        }
    }

    public function logout()
    {
        $this->ion_auth->logout();
        redirect('home', 'refresh');
    }

    public function update_user()
    {
        /*if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0  && $_POST['mobile'] == "9876543210") {
            $this->response['error'] = true;
            $this->response['message'] = DEMO_VERSION_MSG;
            echo json_encode($this->response);
            return false;
            exit();
        }*/

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
            $this->response['error'] = false;
            $this->response['message'] = 'Profile Update Succesfully';
            echo json_encode($this->response);
            return false;
        }
    }
    
    function forgot_password($is_seller = 0)
    {
        if(!$this->ion_auth->logged_in()) 
        {
            $this->data['is_seller'] = $is_seller;
            $this->data['main_page'] = 'forgot_password';
            $this->data['sliders'] = get_sliders('', '', '', 1);
            $this->data['title'] = 'Forgot Password | ' . $this->data['web_settings']['site_title'];
            
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
        else
        {
            redirect(base_url(),'refresh');
        }
    }
    
    function reset_password()
    {
        $is_seller = $this->input->post('is_seller', 0);
        
        $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim|valid_email');
        
        // setting validation rules by checking whether identity is username or email
        /*if ($this->config->item('identity', 'ion_auth') != 'email') {
            $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
        } else {
            $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
        }*/

        if (!$this->form_validation->run()) {
            
            $this->data['type'] = $this->config->item('identity', 'ion_auth');
            // setup the input
            $this->data['identity'] = [
                'name' => 'identity',
                'id' => 'identity',
            ];

            if ($this->config->item('identity', 'ion_auth') != 'email') {
                $this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
            } else {
                $this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
            }

            // set any errors and display the form
            $response['error'] = true;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            echo json_encode($response);
            return false;
        } else {
            $identity_column = $this->config->item('identity', 'ion_auth');
            //$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();
            $identity = $this->ion_auth->where('email', $this->input->post('email'))->users()->row();

            if (empty($identity)) {

                if ($this->config->item('identity', 'ion_auth') != 'email') {
                    $this->ion_auth->set_error('forgot_password_identity_not_found');
                } else {
                    $this->ion_auth->set_error('forgot_password_email_not_found');
                }
                
                $response['error'] = true;
                $response['redirect_to'] = '';//base_url("register/forgot-password/".$is_seller);
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = 'Sorry your account not found.';
                echo json_encode($response);
                return false;
                
                //$this->session->set_flashdata('message', $this->ion_auth->errors());
                //redirect("register/forgot-password/".$is_seller, 'refresh');
            }

            // run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password2($identity->{$this->config->item('identity', 'ion_auth')}, $is_seller);

            if ($forgotten) {
                
                // if there were no errors
                $response['error'] = false;
                $response['redirect_to'] = '';
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = $this->ion_auth->messages();
                echo json_encode($response);
                return false;
            } else {
                $response['error'] = true;
                $response['redirect_to'] = '';
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = $this->ion_auth->errors();
                echo json_encode($response);
                return false;
            }
        }
    }
    
    public function set_password($code = NULL, $is_seller = 0)
    {
        if(!$this->ion_auth->logged_in()) 
        {
            if (!$code) {
                redirect(base_url());
            }
            $this->data['user'] = $this->ion_auth->forgotten_password_check($code);
            if ($this->data['user']) {
                $settings = get_settings('system_settings', true);
                $this->data['main_page'] = 'set_password';
                $this->data['title'] = 'Reset Password |' . $settings['app_name'];
                $this->data['meta_description'] = 'Reset Password |' . $settings['app_name'];
                
                $this->data['is_seller'] = $is_seller;
                $this->data['code']      = $code;
                
                $this->load->view('front-end/' . THEME . '/template', $this->data);
            } else {
                redirect(base_url(), 'refresh');
            }
        } 
        else 
        {
            redirect(base_url(), 'refresh');
        }
    }
    
    public function set_password2($code = NULL)
    {
        if(!$code) {
            redirect("/", 'refresh');
        }
        
        $is_seller = $this->input->post('is_seller',0);

        $this->data['title'] = $this->lang->line('reset_password_heading');

        $user = $this->ion_auth->forgotten_password_check($code);
        
        if ($user) {
            // if the code is valid then display the password reset form

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() === FALSE) {
                
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
                
                $this->session->set_flashdata('message', $this->data['message']);
                redirect('register/set-password/'. $code.'/'.$is_seller, 'refresh');
                
            } else {
                $identity = $user->{$this->config->item('identity', 'ion_auth')};

                // do we have a valid request?
                if($user->id != $this->input->post('user_id')) {

                    // something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($identity);

                    //show_error($this->lang->line('error_csrf'));
                    $this->session->set_flashdata('message', $this->lang->line('error_csrf'));
                    redirect("register/step2/".$is_seller, 'refresh');
                } else {
                    // finally change the password
                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change) {
                        // if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        redirect("register/step2/".$is_seller, 'refresh');
                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('register/set-password/'. $code.'/'.$is_seller, 'refresh');
                    }
                }
            }
        } else {
            // if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("register/forgot-password/".$is_seller, 'refresh');
        }
    }
}
