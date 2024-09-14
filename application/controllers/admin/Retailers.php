<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Retailers extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation', 'upload']);
        $this->load->helper(['url', 'language', 'file']);
        $this->load->model('Retailer_model');
        if (!has_permissions('read', 'retailer')) {
            $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
            redirect('admin/home', 'refresh');
        }
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'manage-retailer';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Retailer Management | ' . $settings['app_name'];
            $this->data['meta_description'] = ' Retailer Management  | ' . $settings['app_name'];
            
            $this->data['page_title'] = 'Retailers';
            
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }
    
    public function edit_retailer()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) 
        {
            $this->data['main_page'] = FORMS . 'retailer_profile';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Edit Retailer | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Edit Retailer | ' . $settings['app_name'];
            
            if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                
                $seller_id = $_GET['edit_id'];
                
                $this->data['user_id'] = $seller_id;
                
                $this->data['seller_data']  = $this->db->get_where('seller_data', array('user_id'=>$seller_id))->row();
                $this->data['is_seller']    = 0;
                
                $this->data['user'] = $this->db->select(' u.* ')
                    ->join('users_groups ug', ' ug.user_id = u.id ')
                    ->where(['ug.group_id' => '2', 'ug.user_id' => $_GET['edit_id']])
                    ->get('users u')
                    ->row();
                
                $this->load->view('admin/template', $this->data);
            }
        }
        else 
        {
            redirect('admin/login', 'refresh');
        }
    }
    
    
    public function save_basic_details()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (isset($_POST['edit_retailer'])) {
                if (print_msg(!has_permissions('update', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            } else {
                if (print_msg(!has_permissions('create', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            }
            
            $this->form_validation->set_rules('username', 'Contact Person Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean|min_length[5]');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|xss_clean|trim|numeric|edit_unique[users.mobile.' . $_POST['edit_retailer'] . ']');
            
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
                
                $user_id = $_POST['edit_retailer'];
                
                if (!file_exists(FCPATH . USER_AVATAR_PATH)) {
                    mkdir(FCPATH . USER_AVATAR_PATH, 0777);
                }
                
                //process store avatar
                $temp_array_avatar = $avatar_doc = array();
                $avatar_files = $_FILES;
                $avatar_error = "";
                $config = [
                    'upload_path' =>  FCPATH . USER_AVATAR_PATH,
                    'allowed_types' => 'jpg|png|jpeg|gif',
                    'max_size' => 8000,
                ];
                if(isset($avatar_files['profile_img']) && !empty($avatar_files['profile_img']['name']) && isset($avatar_files['profile_img']['name'])) {
                    $other_img = $this->upload;
                    $other_img->initialize($config);
    
                    if(isset($avatar_files['profile_img']) && !empty($avatar_files['profile_img']['name']) && isset($_POST['old_profile_img']) && !empty($_POST['old_profile_img'])) {
                        $old_avatar = explode('/', $this->input->post('old_profile_img', true));
                        delete_images(USER_AVATAR_PATH, $old_avatar[2]);
                    }
    
                    if (!empty($avatar_files['profile_img']['name'])) {
    
                        $_FILES['temp_image']['name'] = $avatar_files['profile_img']['name'];
                        $_FILES['temp_image']['type'] = $avatar_files['profile_img']['type'];
                        $_FILES['temp_image']['tmp_name'] = $avatar_files['profile_img']['tmp_name'];
                        $_FILES['temp_image']['error'] = $avatar_files['profile_img']['error'];
                        $_FILES['temp_image']['size'] = $avatar_files['profile_img']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $avatar_error = 'Images :' . $avatar_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array_avatar = $other_img->data();
                            resize_review_images($temp_array_avatar, FCPATH . USER_AVATAR_PATH);
                            $avatar_doc  = USER_AVATAR_PATH . $temp_array_avatar['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $avatar_files['profile_img']['name'];
                        $_FILES['temp_image']['type'] = $avatar_files['profile_img']['type'];
                        $_FILES['temp_image']['tmp_name'] = $avatar_files['profile_img']['tmp_name'];
                        $_FILES['temp_image']['error'] = $avatar_files['profile_img']['error'];
                        $_FILES['temp_image']['size'] = $avatar_files['profile_img']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $avatar_error = $other_img->display_errors();
                        }
                    }
                    //Deleting Uploaded Images if any overall error occured
                    if ($avatar_error != NULL || !$this->form_validation->run()) {
                        if (isset($avatar_doc) && !empty($avatar_doc || !$this->form_validation->run())) {
                            foreach ($avatar_doc as $key => $val) {
                                unlink(FCPATH . USER_AVATAR_PATH . $avatar_doc[$key]);
                            }
                        }
                    }
                }
                else
                {
                    $avatar_doc = $this->input->post('old_profile_img', true);
                }
                
                $alternate_mobile = $this->input->post('alternate_mobile', true);
                $alternate_email = $this->input->post('alternate_email', true);
                
                $user_details = ['username' => $this->input->post('username'), 'email' => $this->input->post('email'), 'profile_img' => (!empty($avatar_doc)) ? $avatar_doc : null,'alternate_mobile'=>$alternate_mobile, 'alternate_email'=> $alternate_email,];
                $user_details = escape_array($user_details);
                $this->db->set($user_details)->where('id', $user_id)->update($tables['login_users']);
                
                $this->response['redirect_to'] = '';
                $this->response['error'] = false;
                $this->response['message'] = 'Basic Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
            
            
        } else {
            redirect('admin/login', 'refresh');
        }
    }
    
    
    public function bussiness_details()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) 
        {
            $this->data['main_page'] = FORMS . 'retailer_bussiness_details';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Edit Retailer | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Edit Retailer | ' . $settings['app_name'];
            
            if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                
                $retailer_id = $_GET['edit_id'];
                
                $this->data['user_id'] = $retailer_id;
                
                $this->data['retailer_data']  = $this->db->get_where('retailer_data', array('user_id'=>$retailer_id))->row();
                
                $this->data['is_seller']    = 0;
                
                $this->load->view('admin/template', $this->data);
            }
        }
        else 
        {
            redirect('admin/login', 'refresh');
        }
    }
    
    public function save_bussiness_details()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (isset($_POST['edit_retailer'])) {
                if (print_msg(!has_permissions('update', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            } else {
                if (print_msg(!has_permissions('create', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            }
            
            $is_seller = $this->input->post('is_seller');
            $this->form_validation->set_rules('company_name', 'Firm / Shop Name', 'required|xss_clean|trim');
            $this->form_validation->set_rules('year_establish', 'Year of Establishment', 'required|xss_clean|trim');
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
                
                if (!file_exists(FCPATH . RETAILER_LOGO_PATH)) {
                    mkdir(FCPATH . RETAILER_LOGO_PATH, 0777);
                }
                
                //process store logo
                $temp_array_logo = $logo_doc = array();
                $logo_files = $_FILES;
                $logo_error = "";
                $config = [
                    'upload_path' =>  FCPATH . RETAILER_LOGO_PATH,
                    'allowed_types' => 'jpg|png|jpeg|gif',
                    'max_size' => 8000,
                ];
                if(isset($logo_files['logo']) && !empty($logo_files['logo']['name']) && isset($logo_files['logo']['name'])) {
                    $other_img = $this->upload;
                    $other_img->initialize($config);
    
                    if(isset($logo_files['logo']) && !empty($logo_files['logo']['name']) && isset($_POST['old_logo']) && !empty($_POST['old_logo'])) {
                        $old_logo = explode('/', $this->input->post('old_logo', true));
                        delete_images(RETAILER_LOGO_PATH, $old_logo[2]);
                    }
    
                    if (!empty($logo_files['logo']['name'])) {
    
                        $_FILES['temp_image']['name'] = $logo_files['logo']['name'];
                        $_FILES['temp_image']['type'] = $logo_files['logo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $logo_files['logo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $logo_files['logo']['error'];
                        $_FILES['temp_image']['size'] = $logo_files['logo']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $logo_error = 'Images :' . $logo_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array_logo = $other_img->data();
                            resize_review_images($temp_array_logo, FCPATH . RETAILER_LOGO_PATH);
                            $logo_doc  = RETAILER_LOGO_PATH . $temp_array_logo['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $logo_files['logo']['name'];
                        $_FILES['temp_image']['type'] = $logo_files['logo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $logo_files['logo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $logo_files['logo']['error'];
                        $_FILES['temp_image']['size'] = $logo_files['logo']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $logo_error = $other_img->display_errors();
                        }
                    }
                    //Deleting Uploaded Images if any overall error occured
                    if ($logo_error != NULL || !$this->form_validation->run()) {
                        if (isset($logo_doc) && !empty($logo_doc || !$this->form_validation->run())) {
                            foreach ($logo_doc as $key => $val) {
                                unlink(FCPATH . RETAILER_LOGO_PATH . $logo_doc[$key]);
                            }
                        }
                    }
                }
                else
                {
                    $logo_doc = $this->input->post('old_logo', true);
                }
                
                $user_id = $this->input->post('user_id');
                
                $retailer_data = ['company_name' => $this->input->post('company_name'), 'store_name' => $this->input->post('company_name'), 'slug' => create_unique_slug($this->input->post('company_name', true), 'retailer_data'), 'year_establish' => $this->input->post('year_establish'), 'owner_name' => $this->input->post('owner_name'), 'logo' => $logo_doc, 'shop_name' => $this->input->post('shop_name'), 'plot_no' => $this->input->post('plot_no'), 'street_locality' => $this->input->post('street_locality'), 'landmark' => $this->input->post('landmark'), 'pin' => $this->input->post('pin'), 'city_id' => $this->input->post('city_id'), 'state_id' => $this->input->post('state_id'), 'storage_shop_name' => $this->input->post('storage_shop_name'),'storage_plot_no' => $this->input->post('storage_plot_no'), 'storage_street_locality' => $this->input->post('storage_street_locality'), 'storage_landmark' => $this->input->post('storage_landmark'), 'storage_pin' => $this->input->post('storage_pin'), 'storage_city_id' => $this->input->post('storage_city_id'), 'storage_state_id' => $this->input->post('storage_state_id'),'website_url'=>$this->input->post('website_url'),'google_business_url'=>$this->input->post('google_business_url'),'facebook_business_url'=>$this->input->post('facebook_business_url'),'instagram_business_url'=>$this->input->post('instagram_business_url')];
                $retailer_data = escape_array($retailer_data);
                $this->db->set($retailer_data)->where('user_id', $user_id)->update('retailer_data');
                
                $retailer_info = $this->db->get_where('retailer_data', array('user_id'=>$user_id))->row();
                
                $identity_column = $this->config->item('identity', 'ion_auth');
                $user_info  = $this->db->get_where('users',array('id'=>$user_id))->row();
            
                $check1 = $this->db->get_where('addresses', array('user_id'=>$user_id,'add_order'=>1))->row_array();
                
                if($check1)
                {
                    $address_1_data = ['name'=>$user_info->username, 'mobile'=> $user_info->mobile, 'alternate_mobile'=> $user_info->alternate_mobile, 'type'=>'office', 'shop_name' => $this->input->post('shop_name'),'address'=>$this->input->post('plot_no').' '.$this->input->post('street_locality').' '.$this->input->post('landmark'), 'landmark'=> $this->input->post('landmark'), 'city_id' => $this->input->post('city_id'), 'state_id' => $this->input->post('state_id'), 'pincode' => $this->input->post('pin'),'add_order'=>1];
                    $address_1_data = escape_array($address_1_data);
                    $this->db->set($address_1_data)->where('user_id', $user_info->id)->where('add_order',1)->update('addresses');
                }
                else
                {
                    $address_1_data = ['name'=>$user_info->username, 'mobile'=> $user_info->mobile, 'alternate_mobile'=> $user_info->alternate_mobile, 'user_id'=>$user_info->id,'type'=>'office', 'shop_name' => $this->input->post('shop_name'),'address'=>$this->input->post('plot_no').' '.$this->input->post('street_locality').' '.$this->input->post('landmark'), 'landmark'=> $this->input->post('landmark'), 'city_id' => $this->input->post('city_id'), 'state_id' => $this->input->post('state_id'), 'pincode' => $this->input->post('pin'),'add_order'=>1];
                    $address_1_data = escape_array($address_1_data);
                    
                    $this->db->insert('addresses', $address_1_data);
                }
                
                $check2 = $this->db->get_where('addresses', array('user_id'=>$user_info->id,'add_order'=>2))->row_array();
                
                if($check2)
                {
                    $address_2_data = ['name'=>$user_info->username, 'mobile'=> $user_info->mobile, 'alternate_mobile'=> $user_info->alternate_mobile,'type'=>'storage', 'shop_name' => $this->input->post('storage_shop_name'),'address'=>$this->input->post('storage_plot_no').' '.$this->input->post('storage_street_locality').' '.$this->input->post('storage_landmark'), 'landmark'=> $this->input->post('storage_landmark'), 'city_id' => $this->input->post('storage_city_id'), 'state_id' => $this->input->post('storage_state_id'), 'pincode' => $this->input->post('storage_pin'),'add_order'=>2];
                    $address_2_data = escape_array($address_2_data);
                    $this->db->set($address_2_data)->where('user_id', $user_info->id)->where('add_order',2)->update('addresses');
                }
                else
                {
                    $address_2_data = ['name'=>$user_info->username, 'mobile'=> $user_info->mobile, 'alternate_mobile'=> $user_info->alternate_mobile,'user_id'=>$user_info->id,'type'=>'storage', 'shop_name' => $this->input->post('storage_shop_name'),'address'=>$this->input->post('storage_plot_no').' '.$this->input->post('storage_street_locality').' '.$this->input->post('storage_landmark'), 'landmark'=> $this->input->post('storage_landmark'), 'city_id' => $this->input->post('storage_city_id'), 'state_id' => $this->input->post('storage_state_id'), 'pincode' => $this->input->post('storage_pin'),'add_order'=>2];
                    $address_2_data = escape_array($address_2_data);
                    
                    $this->db->insert('addresses', $address_2_data);
                }
                
                $this->response['redirect_to'] = '';// base_url('my-account/bank-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Business Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }
    
    public function bank_details()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) 
        {
            $this->data['main_page'] = FORMS . 'retailer_bank_details';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Edit Retailer | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Edit Retailer | ' . $settings['app_name'];
            
            if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                
                $retailer_id = $_GET['edit_id'];
                
                $this->data['user_id'] = $retailer_id;
                
                $this->data['retailer_data']  = $this->db->get_where('retailer_data', array('user_id'=>$retailer_id))->row();
                
                $this->data['is_seller']    = 0;
                
                $this->load->view('admin/template', $this->data);
            }
        }
        else 
        {
            redirect('admin/login', 'refresh');
        }
    }
    
    public function save_bank_details()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (isset($_POST['edit_retailer'])) {
                if (print_msg(!has_permissions('update', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            } else {
                if (print_msg(!has_permissions('create', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            }
            
            $user_id   = $this->input->post('user_id');
            $is_seller = $this->input->post('is_seller');
            $seller_info = array();
            if(!$is_seller)
            {
                $seller_info = $this->db->get_where('retailer_data', array('user_id'=>$user_id))->row_array();
            }
            else
            {
                return true;
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
                $seller_data = ['account_name'=>$this->input->post('account_name'),'account_number' => $this->input->post('account_number'), 'bank_code' => $this->input->post('bank_code'), 'account_type' => $this->input->post('account_type'), 'bank_name' => $this->input->post('bank_name'), 'bank_branch' => $this->input->post('bank_branch'), 'bank_city' => $this->input->post('bank_city'), 'bank_state' => $this->input->post('bank_state')];
                $seller_data = escape_array($seller_data);
                $this->db->set($seller_data)->where('user_id', $user_id)->update('retailer_data');
                
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
                    $this->db->set($seller_data)->where('user_id', $user_id)->update('retailer_data');
                }
                
                $this->response['redirect_to'] = '';// base_url('my-account/gst-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Bank Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
        }
        else 
        {
            redirect('admin/login', 'refresh');
        }
    }
    
    
    public function gst_details()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) 
        {
            $this->data['main_page'] = FORMS . 'retailer_gst_details';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Edit Retailer | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Edit Retailer | ' . $settings['app_name'];
            
            if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                
                $retailer_id = $_GET['edit_id'];
                
                $this->data['user_id'] = $retailer_id;
                
                $this->data['retailer_data']  = $this->db->get_where('retailer_data', array('user_id'=>$retailer_id))->row();
                
                $this->data['is_seller']    = 0;
                
                $this->load->view('admin/template', $this->data);
            }
        }
        else 
        {
            redirect('admin/login', 'refresh');
        }
    }
    
    public function save_gst_details()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (isset($_POST['edit_retailer'])) {
                if (print_msg(!has_permissions('update', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            } else {
                if (print_msg(!has_permissions('create', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            }
            
            $user_id   = $this->input->post('user_id');
            $is_seller = $this->input->post('is_seller');
            
            $seller_info = array();
            if(!$is_seller)
            {
                $seller_info = $this->db->get_where('retailer_data', array('user_id'=>$user_id))->row_array();
            }
            else
            {
                return true;
            }
            
            $this->form_validation->set_rules('have_gst_no', 'Have GST/Dont Have GST', 'required|xss_clean|trim');
        
            if($_POST['have_gst_no'] == 1) {
                $this->form_validation->set_rules('gst_no', 'GST Number', 'required|xss_clean|trim');
                
                if(empty($_FILES['gst_no_photo']['name']) && empty($_FILES['gst_no_pdf']['name']) && $seller_info['gst_no_photo'] == '' && $seller_info['gst_no_pdf'] == '')
                {
                    $this->form_validation->set_rules('gst_no_photo', 'GST Number Photo / PDF', 'required');
                }
            }
            
            if($_POST['have_gst_no'] == 2) {
                $this->form_validation->set_rules('pan_number', 'PAN Number', 'required|xss_clean|trim');
                
                if(empty($_FILES['pan_no_photo']['name']) && empty($_FILES['pan_no_pdf']['name']) && $seller_info['pan_no_photo'] == '' && $seller_info['pan_no_pdf'] == '')
                {
                    $this->form_validation->set_rules('pan_no_photo', 'PAN Number Photo / PDF', 'required');
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
                
                $seller_data = ['have_gst_no' => $this->input->post('have_gst_no'), 'gst_no' => $this->input->post('gst_no'), 'pan_number' => $this->input->post('pan_number')];
                $seller_data = escape_array($seller_data);
                $this->db->set($seller_data)->where('user_id', $user_id)->update('retailer_data');
                
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
                    $this->db->set($seller_data)->where('user_id', $user_id)->update('retailer_data');
                }
                
                if (!file_exists(FCPATH . RETAILER_GST_PDF_PATH)) {
                    mkdir(FCPATH . RETAILER_GST_PDF_PATH, 0777);
                }
                
                $temp_array = array();
                $files = $_FILES;
                $pdfs_new_name_arr = array();
                $pdfs_info_error = "";
                $allowed_media_types = implode('|', allowed_media_types());
                $config = [
                    'upload_path' =>  FCPATH . RETAILER_GST_PDF_PATH,
                    'allowed_types' => $allowed_media_types,
                    'max_size' => 8000,
                ];
                
                
                if(!empty($_FILES['gst_no_pdf']['name']) && isset($_FILES['gst_no_pdf']['name'])) 
                {
                    $other_image_cnt = count($_FILES['gst_no_pdf']['name']);
                    $other_pdf = $this->upload;
                    $other_pdf->initialize($config);
                
                
                    if(!empty($_FILES['gst_no_pdf']['name'])) {
                
                        $_FILES['temp_image']['name'] = $files['gst_no_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files['gst_no_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['gst_no_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['gst_no_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files['gst_no_pdf']['size'];
                        if(!$other_pdf->do_upload('temp_image')) {
                            $pdfs_info_error = 'attachments :' . $pdfs_info_error . ' ' . $other_pdf->display_errors();
                        } else {
                            $temp_array = $other_pdf->data();
                            $pdfs_new_name_arr[] = RETAILER_GST_PDF_PATH . $temp_array['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files['gst_no_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files['gst_no_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['gst_no_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['gst_no_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files['gst_no_pdf']['size'];
                        if(!$other_pdf->do_upload('temp_image')) {
                            $pdfs_info_error = $other_pdf->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if($pdfs_info_error != NULL || !$this->form_validation->run()) {
                        if(isset($pdfs_new_name_arr) && !empty($pdfs_new_name_arr || !$this->form_validation->run())) {
                            foreach($pdfs_new_name_arr as $key => $val) {
                                unlink(FCPATH . RETAILER_GST_PDF_PATH . $pdfs_new_name_arr[$key]);
                            }
                        }
                    }
                }
                
                if ($pdfs_info_error != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $pdfs_info_error;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($pdfs_new_name_arr))
                {
                    $retailer_data = ['gst_no_pdf'=>$pdfs_new_name_arr[0]];
                    $retailer_data = escape_array($retailer_data);
                    $this->db->set($retailer_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
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
                    $this->db->set($seller_data)->where('user_id', $user_id)->update('retailer_data');
                }
                
                if (!file_exists(FCPATH . RETAILER_PAN_PDF_PATH)) {
                    mkdir(FCPATH . RETAILER_PAN_PDF_PATH, 0777);
                }
                
                $temp_array2 = array();
                $files2 = $_FILES;
                $pdfs_new_name_arr2 = array();
                $pdfs_info_error2 = "";
                $allowed_media_types2 = implode('|', allowed_media_types());
                $config2 = [
                    'upload_path' =>  FCPATH . RETAILER_PAN_PDF_PATH,
                    'allowed_types' => $allowed_media_types2,
                    'max_size' => 8000,
                ];
                
                
                if (!empty($_FILES['pan_no_pdf']['name']) && isset($_FILES['pan_no_pdf']['name'])) 
                {
                    $other_pdf_cnt2 = count($_FILES['pan_no_pdf']['name']);
                    $other_pdf2 = $this->upload;
                    $other_pdf2->initialize($config2);
                
                
                    if (!empty($_FILES['pan_no_pdf']['name'])) {
                
                        $_FILES['temp_image']['name'] = $files2['pan_no_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files2['pan_no_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files2['pan_no_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files2['pan_no_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files2['pan_no_pdf']['size'];
                        if (!$other_pdf2->do_upload('temp_image')) {
                            $pdfs_info_error2 = 'attachments :' . $pdfs_info_error2 . ' ' . $other_pdf2->display_errors();
                        } else {
                            $temp_array2 = $other_pdf2->data();
                            $pdfs_new_name_arr2[] = RETAILER_PAN_PDF_PATH . $temp_array2['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files2['pan_no_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files2['pan_no_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files2['pan_no_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files2['pan_no_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files2['pan_no_pdf']['size'];
                        if (!$other_pdf2->do_upload('temp_image')) {
                            $pdfs_info_error2 = $other_pdf2->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($pdfs_info_error2 != NULL || !$this->form_validation->run()) {
                        if(isset($pdfs_new_name_arr2) && !empty($pdfs_new_name_arr2 || !$this->form_validation->run())) {
                            foreach($pdfs_new_name_arr2 as $key => $val) {
                                unlink(FCPATH . RETAILER_PAN_PDF_PATH . $pdfs_new_name_arr2[$key]);
                            }
                        }
                    }
                }
                
                if($pdfs_info_error2 != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $pdfs_info_error2;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($pdfs_new_name_arr2))
                {
                    $retailer_data = ['pan_no_pdf'=>$pdfs_new_name_arr2[0]];
                    $retailer_data = escape_array($retailer_data);
                    $this->db->set($retailer_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                }
                
                $this->response['redirect_to'] = '';// base_url('my-account/license-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'GST Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
                
            }
        }
        else 
        {
            redirect('admin/login', 'refresh');
        }
    }
    
    
    public function license_details()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) 
        {
            $this->data['main_page'] = FORMS . 'retailer_license_details';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Edit Retailer | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Edit Retailer | ' . $settings['app_name'];
            
            if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                
                $retailer_id = $_GET['edit_id'];
                
                $this->data['user_id'] = $retailer_id;
                
                $this->data['retailer_data']  = $this->db->get_where('retailer_data', array('user_id'=>$retailer_id))->row();
                
                $this->data['is_seller']    = 0;
                
                $this->load->view('admin/template', $this->data);
            }
        }
        else 
        {
            redirect('admin/login', 'refresh');
        }
    }
    
    public function save_license_details()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (isset($_POST['edit_retailer'])) {
                if (print_msg(!has_permissions('update', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            } else {
                if (print_msg(!has_permissions('create', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            }
            
            $is_seller = $this->input->post('is_seller');
            $user_id   = $this->input->post('user_id');
            
            $seller_info = array();
            if(!$is_seller)
            {
                $seller_info = $this->db->get_where('retailer_data', array('user_id'=>$user_id))->row_array();
            }
            else
            {
                return true;
            }
            
            $flag = 0;
            if($_POST['have_fertilizer_license'] == 1) {
                $this->form_validation->set_rules('fertilizer_license_no', 'Fertilizer License Number', 'required|xss_clean|trim');
                $this->form_validation->set_rules('fert_lic_expiry_date', 'Fertilizer License Expiry Date', 'required|xss_clean|trim');
                if(empty($_FILES['fertilizer_license_photo']['name']) && empty($_FILES['fertilizer_license_pdf']['name']) && $seller_info['fertilizer_license_photo'] == '' && $seller_info['fertilizer_license_pdf'] == '')
                {
                    $this->form_validation->set_rules('fertilizer_license_photo', 'Fertilizer License Photo / PDF', 'required');
                }
                $flag = 1;
            }
            
            if($_POST['have_pesticide_license_no'] == 1) {
                $this->form_validation->set_rules('pesticide_license_no', 'Pesticide License Number', 'required|xss_clean|trim');
                $this->form_validation->set_rules('pest_lic_expiry_date', 'Pesticide License Expiry Date', 'required|xss_clean|trim');
                if(empty($_FILES['pesticide_license_photo']['name']) && empty($_FILES['pesticide_license_pdf']['name']) && $seller_info['pesticide_license_photo'] == '' && $seller_info['pesticide_license_pdf'] == '')
                {
                    $this->form_validation->set_rules('pesticide_license_photo', 'Pesticide License Photo / PDF', 'required|xss_clean|trim');
                }
                $flag = 1;
            }
            
            if($_POST['have_seeds_license_no'] == 1) {
                $this->form_validation->set_rules('seeds_license_no', 'Seeds License Number', 'required|xss_clean|trim');
                $this->form_validation->set_rules('seeds_lic_expiry_date', 'Seeds License Expiry Date', 'required|xss_clean|trim');
                if(empty($_FILES['seeds_license_photo']['name']) && empty($_FILES['seeds_license_pdf']['name']) && $seller_info['seeds_license_photo'] == '' && $seller_info['seeds_license_pdf'] == '')
                {
                    $this->form_validation->set_rules('seeds_license_photo', 'Seeds License Photo / PDF', 'required|xss_clean|trim');
                }
                $flag = 1;
            }
                            
            if($_POST['have_agri_serv_license_no'] == 1) {
                $this->form_validation->set_rules('have_agri_serv_license_no', 'Agri Services License Number', 'required|xss_clean|trim');
                $this->form_validation->set_rules('agri_serv_lic_expiry_date', 'Agri Services License Expiry Date', 'required|xss_clean|trim');
                if(empty($_FILES['agri_serv_license_photo']['name']) && empty($_FILES['agri_serv_license_pdf']['name']) && $seller_info['agri_serv_license_photo'] == '' && $seller_info['agri_serv_license_pdf'] == '')
                {
                    $this->form_validation->set_rules('agri_serv_license_photo', 'Agri Services License Photo / PDF', 'required|xss_clean|trim');
                }
                $flag = 1;
            }
            
            if($_POST['have_agri_equip_license_no'] == 1) {
                $this->form_validation->set_rules('have_agri_equip_license_no', 'Agri Equipments License Number', 'required|xss_clean|trim');
                $this->form_validation->set_rules('agri_equip_lic_expiry_date', 'Agri Equipments License Expiry Date', 'required|xss_clean|trim');
                if(empty($_FILES['agri_equip_license_photo']['name']) && empty($_FILES['agri_equip_license_pdf']['name']) && $seller_info['agri_equip_license_photo'] == '' && $seller_info['agri_equip_license_pdf'] == '')
                {
                    $this->form_validation->set_rules('agri_equip_license_photo', 'Agri Equipments License Photo / PDF', 'required|xss_clean|trim');
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
                $seller_data = ['have_fertilizer_license' => $this->input->post('have_fertilizer_license'), 'fertilizer_license_no' => $this->input->post('fertilizer_license_no'), 'fertilizer_license_no' => $this->input->post('fertilizer_license_no'), 'fert_lic_issue_date' => $this->input->post('fert_lic_issue_date'), 'fert_lic_expiry_date' => $this->input->post('fert_lic_expiry_date'),'have_pesticide_license_no' => $this->input->post('have_pesticide_license_no'),  'pesticide_license_no' => $this->input->post('pesticide_license_no'), 'pest_lic_issue_date' => $this->input->post('pest_lic_issue_date'), 'pest_lic_expiry_date' => $this->input->post('pest_lic_expiry_date'),  'have_seeds_license_no' => $this->input->post('have_seeds_license_no'), 'seeds_license_no' => $this->input->post('seeds_license_no'), 'seeds_lic_issue_date' => $this->input->post('seeds_lic_issue_date'), 'seeds_lic_expiry_date' => $this->input->post('seeds_lic_expiry_date'), 'have_agri_serv_license_no' => $this->input->post('have_agri_serv_license_no'), 'agri_serv_license_no' => $this->input->post('agri_serv_license_no'), 'agri_serv_lic_expiry_date' => $this->input->post('agri_serv_lic_expiry_date'), 'have_agri_equip_license_no' => $this->input->post('have_agri_equip_license_no'), 'agri_equip_license_no' => $this->input->post('agri_equip_license_no'), 'agri_equip_lic_expiry_date' => $this->input->post('agri_equip_lic_expiry_date'), 'is_finish' => $this->input->post('is_finish')];
                $seller_data = escape_array($seller_data);
                $this->db->set($seller_data)->where('user_id', $user_id)->update('retailer_data');
                
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
                    $this->db->set($seller_data)->where('user_id', $user_id)->update('retailer_data');
                }
                
                if (!file_exists(FCPATH . RETAILER_FERT_LIC_PDF_PATH)) {
                    mkdir(FCPATH . RETAILER_FERT_LIC_PDF_PATH, 0777);
                }
    
                $temp_array = array();
                $files = $_FILES;
                $pdf_new_name_arr = array();
                $pdf_info_error = "";
                $allowed_media_types = implode('|', allowed_media_types());
                $config = [
                    'upload_path' =>  FCPATH . RETAILER_FERT_LIC_PDF_PATH,
                    'allowed_types' => $allowed_media_types,
                    'max_size' => 8000,
                ];
    
    
                if(!empty($_FILES['fertilizer_license_pdf']['name']) && isset($_FILES['fertilizer_license_pdf']['name'])) 
                {
                    $other_pdf_cnt = count($_FILES['fertilizer_license_pdf']['name']);
                    $other_pdf = $this->upload;
                    $other_pdf->initialize($config);
    

                    if (!empty($_FILES['fertilizer_license_pdf']['name'])) {

                        $_FILES['temp_image']['name'] = $files['fertilizer_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files['fertilizer_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['fertilizer_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['fertilizer_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files['fertilizer_license_pdf']['size'];
                        if (!$other_pdf->do_upload('temp_image')) {
                            $pdf_info_error = 'attachments :' . $pdf_info_error . ' ' . $other_pdf->display_errors();
                        } else {
                            $temp_array = $other_pdf->data();
                            $pdf_new_name_arr[] = RETAILER_FERT_LIC_PDF_PATH . $temp_array['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files['fertilizer_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files['fertilizer_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['fertilizer_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['fertilizer_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files['fertilizer_license_pdf']['size'];
                        if (!$other_pdf->do_upload('temp_image')) {
                            $pdf_info_error = $other_pdf->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($pdf_info_error != NULL || !$this->form_validation->run()) {
                        if(isset($pdf_new_name_arr) && !empty($pdf_new_name_arr || !$this->form_validation->run())) {
                            foreach ($pdf_new_name_arr as $key => $val) {
                                unlink(FCPATH . RETAILER_FERT_LIC_PDF_PATH . $pdf_new_name_arr[$key]);
                            }
                        }
                    }
                }
                
                if ($pdf_info_error != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $pdf_info_error;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($pdf_new_name_arr))
                {
                    $seller_data = ['fertilizer_license_pdf'=>$pdf_new_name_arr[0]];
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
                    $this->db->set($seller_data)->where('user_id', $user_id)->update('retailer_data');
                }
                
                if (!file_exists(FCPATH . RETAILER_PEST_LIC_PDF_PATH)) {
                    mkdir(FCPATH . RETAILER_PEST_LIC_PDF_PATH, 0777);
                }
                
                $temp_array2 = array();
                $files2 = $_FILES;
                $pdfs_new_name_arr2 = array();
                $pdfs_info_error2 = "";
                $allowed_media_types2 = implode('|', allowed_media_types());
                $config2 = [
                    'upload_path' =>  FCPATH . RETAILER_PEST_LIC_PDF_PATH,
                    'allowed_types' => $allowed_media_types2,
                    'max_size' => 8000,
                ];
    
    
                if (!empty($_FILES['pesticide_license_pdf']['name']) && isset($_FILES['pesticide_license_pdf']['name'])) 
                {
                    $other_pdf_cnt2 = count($_FILES['pesticide_license_pdf']['name']);
                    $other_pdf2 = $this->upload;
                    $other_pdf2->initialize($config2);
    

                    if (!empty($_FILES['pesticide_license_pdf']['name'])) {

                        $_FILES['temp_image']['name'] = $files2['pesticide_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files2['pesticide_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files2['pesticide_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files2['pesticide_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files2['pesticide_license_pdf']['size'];
                        if (!$other_pdf2->do_upload('temp_image')) {
                            $pdfs_info_error2 = 'attachments :' . $pdfs_info_error2 . ' ' . $other_pdf2->display_errors();
                        } else {
                            $temp_array2 = $other_pdf2->data();
                            $pdfs_new_name_arr2[] = RETAILER_PEST_LIC_PDF_PATH . $temp_array2['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files2['pesticide_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files2['pesticide_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files2['pesticide_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files2['pesticide_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files2['pesticide_license_pdf']['size'];
                        if (!$other_pdf2->do_upload('temp_image')) {
                            $pdfs_info_error2 = $other_pdf2->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($pdfs_info_error2 != NULL || !$this->form_validation->run()) {
                        if(isset($pdfs_new_name_arr2) && !empty($pdfs_new_name_arr2 || !$this->form_validation->run())) {
                            foreach ($pdfs_new_name_arr2 as $key => $val) {
                                unlink(FCPATH . RETAILER_PEST_LIC_PDF_PATH . $pdfs_new_name_arr2[$key]);
                            }
                        }
                    }
                }
                
                if ($pdfs_info_error2 != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $pdfs_info_error2;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($pdfs_new_name_arr2))
                {
                    $seller_data = ['pesticide_license_pdf'=>$pdfs_new_name_arr2[0]];
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
                    $this->db->set($seller_data)->where('user_id', $user_id)->update('retailer_data');
                }
                
                if (!file_exists(FCPATH . RETAILER_OFORM_IMG_PATH)) {
                    mkdir(FCPATH . RETAILER_OFORM_IMG_PATH, 0777);
                }
                
                $temp_array4 = array();
                $files4 = $_FILES;
                $images_new_name_arr4 = array();
                $images_info_error4 = "";
                $allowed_media_types4 = implode('|', allowed_media_types());
                $config4 = [
                    'upload_path' =>  FCPATH . RETAILER_OFORM_IMG_PATH,
                    'allowed_types' => $allowed_media_types4,
                    'max_size' => 8000,
                ];
    
    
                if (!empty($_FILES['o_form_photo']['name']) && isset($_FILES['o_form_photo']['name'])) 
                {
                    $other_image_cnt4 = count($_FILES['o_form_photo']['name']);
                    $other_img4 = $this->upload;
                    $other_img4->initialize($config4);
    

                    if (!empty($_FILES['o_form_photo']['name'])) {

                        $_FILES['temp_image']['name'] = $files4['o_form_photo']['name'];
                        $_FILES['temp_image']['type'] = $files4['o_form_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files4['o_form_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files4['o_form_photo']['error'];
                        $_FILES['temp_image']['size'] = $files4['o_form_photo']['size'];
                        if(!$other_img4->do_upload('temp_image')) {
                            $images_info_error4 = 'attachments :' . $images_info_error4 . ' ' . $other_img4->display_errors();
                        } else {
                            $temp_array4 = $other_img4->data();
                            resize_review_images($temp_array4, FCPATH . RETAILER_OFORM_IMG_PATH);
                            $images_new_name_arr4[] = RETAILER_OFORM_IMG_PATH . $temp_array4['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files4['o_form_photo']['name'];
                        $_FILES['temp_image']['type'] = $files4['o_form_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files4['o_form_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files4['o_form_photo']['error'];
                        $_FILES['temp_image']['size'] = $files4['o_form_photo']['size'];
                        if (!$other_img4->do_upload('temp_image')) {
                            $images_info_error4 = $other_img4->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error4 != NULL || !$this->form_validation->run()) {
                        if(isset($images_new_name_arr4) && !empty($images_new_name_arr4 || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr4 as $key => $val) {
                                unlink(FCPATH . RETAILER_OFORM_IMG_PATH . $images_new_name_arr4[$key]);
                            }
                        }
                    }
                }
                
                if ($images_info_error4 != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $images_info_error4;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($images_new_name_arr4))
                {
                    $seller_data = ['o_form_photo'=>$images_new_name_arr4[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $user_id)->update('retailer_data');
                }
                
                if (!file_exists(FCPATH . RETAILER_SEEDS_LIC_PDF_PATH)) {
                    mkdir(FCPATH . RETAILER_SEEDS_LIC_PDF_PATH, 0777);
                }
                
                $temp_array3 = array();
                $files3 = $_FILES;
                $pdfs_new_name_arr3 = array();
                $pdfs_info_error3 = "";
                $allowed_media_types3 = implode('|', allowed_media_types());
                $config3 = [
                    'upload_path' =>  FCPATH . RETAILER_SEEDS_LIC_PDF_PATH,
                    'allowed_types' => $allowed_media_types3,
                    'max_size' => 8000,
                ];
    
    
                if (!empty($_FILES['seeds_license_pdf']['name']) && isset($_FILES['seeds_license_pdf']['name'])) 
                {
                    $other_pdf_cnt3 = count($_FILES['seeds_license_pdf']['name']);
                    $other_pdf3 = $this->upload;
                    $other_pdf3->initialize($config3);
    

                    if (!empty($_FILES['seeds_license_pdf']['name'])) {

                        $_FILES['temp_image']['name'] = $files3['seeds_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files3['seeds_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files3['seeds_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files3['seeds_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files3['seeds_license_pdf']['size'];
                        if(!$other_pdf3->do_upload('temp_image')) {
                            $pdfs_info_error3 = 'attachments :' . $pdfs_info_error3 . ' ' . $other_pdf3->display_errors();
                        } else {
                            $temp_array3 = $other_pdf3->data();
                            $pdfs_new_name_arr3[] = RETAILER_SEEDS_LIC_PDF_PATH . $temp_array3['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files3['seeds_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files3['seeds_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files3['seeds_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files3['seeds_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files3['seeds_license_pdf']['size'];
                        if (!$other_pdf3->do_upload('temp_image')) {
                            $pdfs_info_error3 = $other_pdf3->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($pdfs_info_error3 != NULL || !$this->form_validation->run()) {
                        if(isset($pdfs_new_name_arr3) && !empty($pdfs_new_name_arr3 || !$this->form_validation->run())) {
                            foreach ($pdfs_new_name_arr3 as $key => $val) {
                                unlink(FCPATH . RETAILER_SEEDS_LIC_PDF_PATH . $pdfs_new_name_arr3[$key]);
                            }
                        }
                    }
                }
                
                if ($pdfs_info_error3 != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $pdfs_info_error3;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($pdfs_new_name_arr3))
                {
                    $seller_data = ['seeds_license_pdf'=>$pdfs_new_name_arr3[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                }
                                
                if (!file_exists(FCPATH . RETAILER_AGRI_SERV_LIC_IMG_PATH)) {
                    mkdir(FCPATH . RETAILER_AGRI_SERV_LIC_IMG_PATH, 0777);
                }
                
                $temp_array5 = array();
                $files5 = $_FILES;
                $images_new_name_arr5 = array();
                $images_info_error5 = "";
                $allowed_media_types5 = implode('|', allowed_media_types());
                $config5 = [
                    'upload_path' =>  FCPATH . RETAILER_AGRI_SERV_LIC_IMG_PATH,
                    'allowed_types' => $allowed_media_types5,
                    'max_size' => 8000,
                ];
    
    
                if (!empty($_FILES['agri_serv_license_photo']['name']) && isset($_FILES['agri_serv_license_photo']['name'])) 
                {
                    $other_image_cnt5 = count($_FILES['agri_serv_license_photo']['name']);
                    $other_img5 = $this->upload;
                    $other_img5->initialize($config5);
    

                    if (!empty($_FILES['agri_serv_license_photo']['name'])) {

                        $_FILES['temp_image']['name'] = $files5['agri_serv_license_photo']['name'];
                        $_FILES['temp_image']['type'] = $files5['agri_serv_license_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files5['agri_serv_license_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files5['agri_serv_license_photo']['error'];
                        $_FILES['temp_image']['size'] = $files5['agri_serv_license_photo']['size'];
                        if (!$other_img5->do_upload('temp_image')) {
                            $images_info_error5 = 'attachments :' . $images_info_error5 . ' ' . $other_img5->display_errors();
                        } else {
                            $temp_array5 = $other_img5->data();
                            resize_review_images($temp_array5, FCPATH . RETAILER_AGRI_SERV_LIC_IMG_PATH);
                            $images_new_name_arr5[] = RETAILER_AGRI_SERV_LIC_IMG_PATH . $temp_array5['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files5['agri_serv_license_photo']['name'];
                        $_FILES['temp_image']['type'] = $files5['agri_serv_license_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files5['agri_serv_license_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files5['agri_serv_license_photo']['error'];
                        $_FILES['temp_image']['size'] = $files5['agri_serv_license_photo']['size'];
                        if (!$other_img5->do_upload('temp_image')) {
                            $images_info_error5 = $other_img5->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error5 != NULL || !$this->form_validation->run()) {
                        if(isset($images_new_name_arr5) && !empty($images_new_name_arr5 || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr5 as $key => $val) {
                                unlink(FCPATH . RETAILER_AGRI_SERV_LIC_IMG_PATH . $images_new_name_arr5[$key]);
                            }
                        }
                    }
                }
                
                if ($images_info_error5 != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $images_info_error5;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($images_new_name_arr5))
                {
                    $retailer_data = ['agri_serv_license_photo'=>$images_new_name_arr5[0]];
                    $retailer_data = escape_array($retailer_data);
                    $this->db->set($retailer_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                }
                
                if (!file_exists(FCPATH . RETAILER_AGRI_SERV_LIC_PDF_PATH)) {
                    mkdir(FCPATH . RETAILER_AGRI_SERV_LIC_PDF_PATH, 0777);
                }
                
                $temp_array5 = array();
                $files5 = $_FILES;
                $pdfs_new_name_arr5 = array();
                $pdfs_info_error5 = "";
                $allowed_media_types5 = implode('|', allowed_media_types());
                $config5 = [
                    'upload_path' =>  FCPATH . RETAILER_AGRI_SERV_LIC_PDF_PATH,
                    'allowed_types' => $allowed_media_types5,
                    'max_size' => 8000,
                ];
    
    
                if (!empty($_FILES['agri_serv_license_pdf']['name']) && isset($_FILES['agri_serv_license_pdf']['name'])) 
                {
                    $other_pdf_cnt5 = count($_FILES['agri_serv_license_pdf']['name']);
                    $other_pdf5 = $this->upload;
                    $other_pdf5->initialize($config5);
    

                    if (!empty($_FILES['agri_serv_license_pdf']['name'])) {

                        $_FILES['temp_image']['name'] = $files5['agri_serv_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files5['agri_serv_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files5['agri_serv_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files5['agri_serv_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files5['agri_serv_license_pdf']['size'];
                        if(!$other_pdf5->do_upload('temp_image')) {
                            $pdfs_info_error5 = 'attachments :' . $pdfs_info_error5 . ' ' . $other_pdf5->display_errors();
                        } else {
                            $temp_array5 = $other_pdf5->data();
                            $pdfs_new_name_arr5[] = RETAILER_AGRI_SERV_LIC_PDF_PATH . $temp_array5['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files5['agri_serv_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files5['agri_serv_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files5['agri_serv_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files5['agri_serv_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files5['agri_serv_license_pdf']['size'];
                        if (!$other_pdf5->do_upload('temp_image')) {
                            $pdfs_info_error5 = $other_pdf5->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($pdfs_info_error5 != NULL || !$this->form_validation->run()) {
                        if(isset($pdfs_new_name_arr5) && !empty($pdfs_new_name_arr5 || !$this->form_validation->run())) {
                            foreach ($pdfs_new_name_arr5 as $key => $val) {
                                unlink(FCPATH . RETAILER_AGRI_SERV_LIC_PDF_PATH . $pdfs_new_name_arr5[$key]);
                            }
                        }
                    }
                }
                
                if ($pdfs_info_error5 != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $pdfs_info_error5;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($pdfs_new_name_arr5))
                {
                    $retailer_data = ['agri_serv_license_pdf'=>$pdfs_new_name_arr5[0]];
                    $retailer_data = escape_array($retailer_data);
                    $this->db->set($retailer_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                }
                
                if (!file_exists(FCPATH . RETAILER_AGRI_EQUIP_LIC_IMG_PATH)) {
                    mkdir(FCPATH . RETAILER_AGRI_EQUIP_LIC_IMG_PATH, 0777);
                }
                
                $temp_array6 = array();
                $files6 = $_FILES;
                $images_new_name_arr6 = array();
                $images_info_error6 = "";
                $allowed_media_types6 = implode('|', allowed_media_types());
                $config6 = [
                    'upload_path' =>  FCPATH . RETAILER_AGRI_EQUIP_LIC_IMG_PATH,
                    'allowed_types' => $allowed_media_types6,
                    'max_size' => 8000,
                ];
    
    
                if (!empty($_FILES['agri_equip_license_photo']['name']) && isset($_FILES['agri_equip_license_photo']['name'])) 
                {
                    $other_image_cnt6 = count($_FILES['agri_equip_license_photo']['name']);
                    $other_img6 = $this->upload;
                    $other_img6->initialize($config6);
    

                    if (!empty($_FILES['agri_equip_license_photo']['name'])) {

                        $_FILES['temp_image']['name'] = $files6['agri_equip_license_photo']['name'];
                        $_FILES['temp_image']['type'] = $files6['agri_equip_license_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files6['agri_equip_license_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files6['agri_equip_license_photo']['error'];
                        $_FILES['temp_image']['size'] = $files6['agri_equip_license_photo']['size'];
                        if (!$other_img6->do_upload('temp_image')) {
                            $images_info_error6 = 'attachments :' . $images_info_error6 . ' ' . $other_img6->display_errors();
                        } else {
                            $temp_array6 = $other_img6->data();
                            resize_review_images($temp_array6, FCPATH . RETAILER_AGRI_EQUIP_LIC_IMG_PATH);
                            $images_new_name_arr6[] = RETAILER_AGRI_EQUIP_LIC_IMG_PATH . $temp_array6['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files6['agri_equip_license_photo']['name'];
                        $_FILES['temp_image']['type'] = $files6['agri_equip_license_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files6['agri_equip_license_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files6['agri_equip_license_photo']['error'];
                        $_FILES['temp_image']['size'] = $files6['agri_equip_license_photo']['size'];
                        if (!$other_img6->do_upload('temp_image')) {
                            $images_info_error6 = $other_img6->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error6 != NULL || !$this->form_validation->run()) {
                        if(isset($images_new_name_arr6) && !empty($images_new_name_arr6 || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr6 as $key => $val) {
                                unlink(FCPATH . RETAILER_AGRI_EQUIP_LIC_IMG_PATH . $images_new_name_arr6[$key]);
                            }
                        }
                    }
                }
                
                if ($images_info_error6 != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $images_info_error6;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($images_new_name_arr6))
                {
                    $retailer_data = ['agri_equip_license_photo'=>$images_new_name_arr6[0]];
                    $retailer_data = escape_array($retailer_data);
                    $this->db->set($retailer_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                }
                
                if (!file_exists(FCPATH . RETAILER_AGRI_EQUIP_LIC_PDF_PATH)) {
                    mkdir(FCPATH . RETAILER_AGRI_EQUIP_LIC_PDF_PATH, 0777);
                }
                
                $temp_array6 = array();
                $files6 = $_FILES;
                $pdfs_new_name_arr6 = array();
                $pdfs_info_error6 = "";
                $allowed_media_types6 = implode('|', allowed_media_types());
                $config6 = [
                    'upload_path' =>  FCPATH . RETAILER_AGRI_EQUIP_LIC_PDF_PATH,
                    'allowed_types' => $allowed_media_types6,
                    'max_size' => 8000,
                ];
    
    
                if (!empty($_FILES['agri_equip_license_pdf']['name']) && isset($_FILES['agri_equip_license_pdf']['name'])) 
                {
                    $other_pdf_cnt6 = count($_FILES['agri_equip_license_pdf']['name']);
                    $other_pdf6 = $this->upload;
                    $other_pdf6->initialize($config6);
    

                    if (!empty($_FILES['agri_equip_license_pdf']['name'])) {

                        $_FILES['temp_image']['name'] = $files6['agri_equip_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files6['agri_equip_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files6['agri_equip_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files6['agri_equip_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files6['agri_equip_license_pdf']['size'];
                        if(!$other_pdf6->do_upload('temp_image')) {
                            $pdfs_info_error6 = 'attachments :' . $pdfs_info_error6 . ' ' . $other_pdf6->display_errors();
                        } else {
                            $temp_array6 = $other_pdf6->data();
                            $pdfs_new_name_arr6[] = RETAILER_AGRI_EQUIP_LIC_PDF_PATH . $temp_array6['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files6['agri_equip_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files6['agri_equip_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files6['agri_equip_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files6['agri_equip_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files6['agri_equip_license_pdf']['size'];
                        if (!$other_pdf6->do_upload('temp_image')) {
                            $pdfs_info_error6 = $other_pdf6->display_errors();
                        }
                    }
                    
                    //Deleting Uploaded attachments if any overall error occured
                    if ($pdfs_info_error6 != NULL || !$this->form_validation->run()) {
                        if(isset($pdfs_new_name_arr6) && !empty($pdfs_new_name_arr6 || !$this->form_validation->run())) {
                            foreach ($pdfs_new_name_arr6 as $key => $val) {
                                unlink(FCPATH . RETAILER_AGRI_EQUIP_LIC_PDF_PATH . $pdfs_new_name_arr6[$key]);
                            }
                        }
                    }
                }
                
                if ($pdfs_info_error6 != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $pdfs_info_error6;
                    print_r(json_encode($this->response));
                    return false;
                }
                
                
                if(!empty($pdfs_new_name_arr6))
                {
                    $retailer_data = ['agri_equip_license_pdf'=>$pdfs_new_name_arr6[0]];
                    $retailer_data = escape_array($retailer_data);
                    $this->db->set($retailer_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                }
                
                $this->response['redirect_to'] = base_url('admin/retailers/license_details?edit_id='.$user_id);
                $this->response['error'] = false;
                $this->response['message'] = 'License Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
        }
        else 
        {
            redirect('admin/login', 'refresh');
        }
    }
    
    public function business_card()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) 
        {
            $this->data['main_page'] = FORMS . 'retailer_business_card';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Edit Retailer | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Edit Retailer | ' . $settings['app_name'];
            
            if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                
                $retailer_id = $_GET['edit_id'];
                
                $this->data['user_id'] = $retailer_id;
                
                $this->data['retailer_data']  = $this->db->get_where('retailer_data', array('user_id'=>$retailer_id))->row();
                
                $this->data['is_seller']    = 0;
                
                $this->load->view('admin/template', $this->data);
            }
        }
        else 
        {
            redirect('admin/login', 'refresh');
        }
    }
    
    public function save_business_card()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (isset($_POST['edit_retailer'])) {
                if (print_msg(!has_permissions('update', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            } else {
                if (print_msg(!has_permissions('create', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            }
            
            if (!file_exists(FCPATH . RETAILER_BUSINESS_CARD_FRONT_PATH)) {
                mkdir(FCPATH . RETAILER_BUSINESS_CARD_FRONT_PATH, 0777);
            }
            
            //process store business_card_front
            $temp_array_business_card_front = $business_card_front_doc = array();
            $business_card_front_files = $_FILES;
            $business_card_front_error = "";
            $config = [
                'upload_path' =>  FCPATH . RETAILER_BUSINESS_CARD_FRONT_PATH,
                'allowed_types' => 'jpg|png|jpeg|gif',
                'max_size' => 8000,
            ];
            if(isset($business_card_front_files['business_card_front']) && !empty($business_card_front_files['business_card_front']['name']) && isset($business_card_front_files['business_card_front']['name'])) {
                $other_img = $this->upload;
                $other_img->initialize($config);

                if(isset($business_card_front_files['business_card_front']) && !empty($business_card_front_files['business_card_front']['name']) && isset($_POST['old_business_card_front']) && !empty($_POST['old_business_card_front'])) {
                    $old_business_card_front = explode('/', $this->input->post('old_business_card_front', true));
                    delete_images(RETAILER_BUSINESS_CARD_FRONT_PATH, $old_business_card_front[2]);
                }

                if (!empty($business_card_front_files['business_card_front']['name'])) {

                    $_FILES['temp_image']['name'] = $business_card_front_files['business_card_front']['name'];
                    $_FILES['temp_image']['type'] = $business_card_front_files['business_card_front']['type'];
                    $_FILES['temp_image']['tmp_name'] = $business_card_front_files['business_card_front']['tmp_name'];
                    $_FILES['temp_image']['error'] = $business_card_front_files['business_card_front']['error'];
                    $_FILES['temp_image']['size'] = $business_card_front_files['business_card_front']['size'];
                    if (!$other_img->do_upload('temp_image')) {
                        $business_card_front_error = 'Images :' . $business_card_front_error . ' ' . $other_img->display_errors();
                    } else {
                        $temp_array_business_card_front = $other_img->data();
                        resize_review_images($temp_array_business_card_front, FCPATH . RETAILER_BUSINESS_CARD_FRONT_PATH);
                        $business_card_front_doc  = RETAILER_BUSINESS_CARD_FRONT_PATH . $temp_array_business_card_front['file_name'];
                    }
                } else {
                    $_FILES['temp_image']['name'] = $business_card_front_files['business_card_front']['name'];
                    $_FILES['temp_image']['type'] = $business_card_front_files['business_card_front']['type'];
                    $_FILES['temp_image']['tmp_name'] = $business_card_front_files['business_card_front']['tmp_name'];
                    $_FILES['temp_image']['error'] = $business_card_front_files['business_card_front']['error'];
                    $_FILES['temp_image']['size'] = $business_card_front_files['business_card_front']['size'];
                    if (!$other_img->do_upload('temp_image')) {
                        $business_card_front_error = $other_img->display_errors();
                    }
                }
                //Deleting Uploaded Images if any overall error occured
                if ($business_card_front_error != NULL || !$this->form_validation->run()) {
                    if (isset($business_card_front_doc) && !empty($business_card_front_doc || !$this->form_validation->run())) {
                        foreach ($business_card_front_doc as $key => $val) {
                            unlink(FCPATH . RETAILER_BUSINESS_CARD_FRONT_PATH . $business_card_front_doc[$key]);
                        }
                    }
                }
            }
            else
            {
                $business_card_front_doc = $this->input->post('old_business_card_front', true);
            }
            
            if (!file_exists(FCPATH . RETAILER_BUSINESS_CARD_BACK_PATH)) {
                mkdir(FCPATH . RETAILER_BUSINESS_CARD_BACK_PATH, 0777);
            }
            
            //process store business_card_back
            $temp_array_business_card_back = $business_card_back_doc = array();
            $business_card_back_files = $_FILES;
            $business_card_back_error = "";
            $config = [
                'upload_path' =>  FCPATH . RETAILER_BUSINESS_CARD_BACK_PATH,
                'allowed_types' => 'jpg|png|jpeg|gif',
                'max_size' => 8000,
            ];
            if(isset($business_card_back_files['business_card_back']) && !empty($business_card_back_files['business_card_back']['name']) && isset($business_card_back_files['business_card_back']['name'])) {
                $other_img = $this->upload;
                $other_img->initialize($config);

                if(isset($business_card_back_files['business_card_back']) && !empty($business_card_back_files['business_card_back']['name']) && isset($_POST['old_business_card_back']) && !empty($_POST['old_business_card_back'])) {
                    $old_business_card_back = explode('/', $this->input->post('old_business_card_back', true));
                    delete_images(RETAILER_BUSINESS_CARD_BACK_PATH, $old_business_card_back[2]);
                }

                if (!empty($business_card_back_files['business_card_back']['name'])) {

                    $_FILES['temp_image']['name'] = $business_card_back_files['business_card_back']['name'];
                    $_FILES['temp_image']['type'] = $business_card_back_files['business_card_back']['type'];
                    $_FILES['temp_image']['tmp_name'] = $business_card_back_files['business_card_back']['tmp_name'];
                    $_FILES['temp_image']['error'] = $business_card_back_files['business_card_back']['error'];
                    $_FILES['temp_image']['size'] = $business_card_back_files['business_card_back']['size'];
                    if (!$other_img->do_upload('temp_image')) {
                        $business_card_back_error = 'Images :' . $business_card_back_error . ' ' . $other_img->display_errors();
                    } else {
                        $temp_array_business_card_back = $other_img->data();
                        resize_review_images($temp_array_business_card_back, FCPATH . RETAILER_BUSINESS_CARD_BACK_PATH);
                        $business_card_back_doc  = RETAILER_BUSINESS_CARD_BACK_PATH . $temp_array_business_card_back['file_name'];
                    }
                } else {
                    $_FILES['temp_image']['name'] = $business_card_back_files['business_card_back']['name'];
                    $_FILES['temp_image']['type'] = $business_card_back_files['business_card_back']['type'];
                    $_FILES['temp_image']['tmp_name'] = $business_card_back_files['business_card_back']['tmp_name'];
                    $_FILES['temp_image']['error'] = $business_card_back_files['business_card_back']['error'];
                    $_FILES['temp_image']['size'] = $business_card_back_files['business_card_back']['size'];
                    if (!$other_img->do_upload('temp_image')) {
                        $business_card_back_error = $other_img->display_errors();
                    }
                }
                //Deleting Uploaded Images if any overall error occured
                if ($business_card_back_error != NULL || !$this->form_validation->run()) {
                    if (isset($business_card_back_doc) && !empty($business_card_back_doc || !$this->form_validation->run())) {
                        foreach ($business_card_back_doc as $key => $val) {
                            unlink(FCPATH . RETAILER_BUSINESS_CARD_BACK_PATH . $business_card_back_doc[$key]);
                        }
                    }
                }
            }
            else
            {
                $business_card_back_doc = $this->input->post('old_business_card_back', true);
            }
            
            $retailer_data = ['business_card_front' => $business_card_front_doc, 'business_card_back' => $business_card_back_doc];
            $retailer_data = escape_array($retailer_data);
            $this->db->set($retailer_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
            
            $this->response['redirect_to'] = '';// base_url('my-account/bank-details/'.$is_seller);
            $this->response['error'] = false;
            $this->response['message'] = 'Business Card Updated Succesfully';
            echo json_encode($this->response);
            return false;
            
        }
        else 
        {
            redirect('admin/login', 'refresh');
        }
    }
    
    public function settings()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) 
        {
            $this->data['main_page'] = FORMS . 'retailer_settings';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Edit Retailer | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Edit Retailer | ' . $settings['app_name'];
            
            if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                
                $retailer_id = $_GET['edit_id'];
                
                $this->data['user_id'] = $retailer_id;
                
                $this->data['retailer_data']  = $this->db->get_where('retailer_data', array('user_id'=>$retailer_id))->row();
                $this->data['is_seller']    = 0;
                
                $this->load->view('admin/template', $this->data);
            }
        }
        else 
        {
            redirect('admin/login', 'refresh');
        }
    }
    
    public function save_settings()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (isset($_POST['edit_retailer'])) {
                if (print_msg(!has_permissions('update', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            } else {
                if (print_msg(!has_permissions('create', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            }
            
            $user_id = $this->input->post('user_id');
            $status = $this->input->post('status', true);
            $system_settings = get_settings('system_settings', true);
            $user = fetch_details(['id' => $user_id], 'users');
            $retailer = fetch_details(['user_id' => $user_id], 'retailer_data');
            
            $retailer_data = array('status' => $this->input->post('status', true),);
            $this->db->set($retailer_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
            
            if($status == 1 && $retailer[0]['status']!=1)
            {
                if($user[0]['email']!='')
                {
                    $html_text  = '<p style="margin-bottom:0px;">Dear '. $retailer[0]['company_name'] .',</p>';
                    $html_text  .= '<p>Congratulations! We\'re excited to inform you that your registration on Happycrop, the innovative B2B agri input platform, has been successfully completed. We extend a warm welcome to you as part of our thriving community.</p>';
                    $html_text .= '<p style="margin-top:20px;">Discover a World of Opportunities on Happycrop:</p>';
                    $html_text .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>Diverse Product Range:</b> Access a wide variety of high-quality agri inputs from reputable suppliers.</p>';
                    $html_text .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>Reliable Suppliers:</b> Gain exposure to a network of trusted manufacturers and suppliers, expanding your product options.</p>';
                    $html_text .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>Efficient Order Management:</b> Seamlessly manage orders, track deliveries, and handle invoices in one user-friendly platform.</p>';
                    $html_text .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>Increased Profitability:</b> Access to cost effective products and promotions leads to improved profitability.</p>';
                    $html_text .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>Market Insights:</b> Stay informed about industry trends and product availability, enabling better purchasing decisions.</p>';
                    $html_text .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>User-Friendly Interface:</b> Navigate our intuitive dashboard to browse products, place orders, and streamline your agri input procurement process.</p>';
                    $html_text .= '<p style="margin-top:20px;">Your Next Steps:</p>';
                    $html_text .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>1. Complete Your Profile:</b> Log in to your Happycrop account and fill in your profile details. A complete profile helps suppliers understand your business better.</p>';
                    $html_text .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>2. Browse and Connect:</b> Explore our extensive product listings, and connect with reputable suppliers for your agri input needs.</p>';
                    $html_text .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>3. Place Orders:</b> Select products, place orders, and track deliveries conveniently through the platform.</p>';
                    $html_text .= '<p style="margin-bottom:0px;margin-top: 2px;"><b>4. Stay Informed:</b> Keep an eye on your dashboard for updates, orders, and market trends. Staying informed ensures you\'re always ahead. We\'re excited to have you as part of the Happycrop community. Should you need assistance, have questions, or wish to explore the full potential of our platform, our team is here to support you every step of the way.</p>';
                    $html_text .= '<p style="margin-top:20px;">Do not reply to this mail as this is system generated email and serves as a confirmation of your Registration.</p>';
                    $html_text .= '<p>If you have any questions or need further assistance regarding your registration, please do not hesitate to contact our customer support team at <a href="mailto:support@happycrop.in" target="_blank">support@happycrop.in</a>. Our dedicated representatives are available on +91 9975548343 at 9 AM to 6 PM.</p>';
                    $html_text .= '<p>Thank you once again for choosing Happycrop. We value your trust and look forward to fulfilling your requirements.</p>';
                    $html_text .= '<p style="margin-bottom: 5px;">Best Regards,</p>';
                    $html_text .= '<p style="margin-top: 5px;margin-bottom: 5px;">Happycrop</p>';
                    $html_text .= '<a href="https://www.happycrop.in" target="_blank">www.Happycrop.in</a>';
                    
                    $order_info = array(
                        'subject'        => 'Your Registration is confirmed!',
                        'user_msg'       => $html_text,
                        'show_foot_note' => false,
                    );
        
                    send_mail2($user[0]['email'], 'Welcome Aboard: Your Happycrop Registration is successfully completed!', $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                }
            }
            
            $this->response['redirect_to'] = '';// base_url('my-account/bank-details/'.$is_seller);
            $this->response['error'] = false;
            $this->response['message'] = 'Settings Updated Succesfully';
            echo json_encode($this->response);
            return false;
            
        } else {
            redirect('admin/login', 'refresh');
        }
    }
    
    
    public function manage_new_retailer()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = FORMS . 'new_retailer';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Add Retailer | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Add Retailer | ' . $settings['app_name'];
            $this->data['categories'] = $this->category_model->get_categories();
            if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                $this->data['title'] = 'Update Retailer | ' . $settings['app_name'];
                $this->data['meta_description'] = 'Update Retailer | ' . $settings['app_name'];
                $this->data['fetched_data'] = $this->db->select(' u.*,sd.* ')
                    ->join('users_groups ug', ' ug.user_id = u.id ')
                    ->join('retailer_data sd', ' sd.user_id = u.id ')
                    ->where(['ug.group_id' => '2', 'ug.user_id' => $_GET['edit_id']])
                    ->get('users u')
                    ->result_array();
            }
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }
    
    public function add_new_retailer()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

            if (isset($_POST['edit_retailer'])) {
                if (print_msg(!has_permissions('update', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            } else {
                if (print_msg(!has_permissions('create', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            }

            $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Mail', 'trim|required|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean|min_length[5]');
            if (!isset($_POST['edit_retailer'])) {
                $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
                $this->form_validation->set_rules('confirm_password', 'Confirm password', 'trim|required|matches[password]|xss_clean');
            }
            //$this->form_validation->set_rules('company_name', 'Company Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');
            

            if (!$this->form_validation->run()) {

                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {

                // process images of retailer

                if (!file_exists(FCPATH . SELLER_DOCUMENTS_PATH)) {
                    mkdir(FCPATH . SELLER_DOCUMENTS_PATH, 0777);
                }

                //process store logo
                $temp_array_logo = $store_logo_doc = array();
                $logo_files = $_FILES;
                $store_logo_error = "";
                $config = [
                    'upload_path' =>  FCPATH . SELLER_DOCUMENTS_PATH,
                    'allowed_types' => 'jpg|png|jpeg|gif',
                    'max_size' => 8000,
                ];
                if (isset($logo_files['store_logo']) && !empty($logo_files['store_logo']['name']) && isset($logo_files['store_logo']['name'])) {
                    $other_img = $this->upload;
                    $other_img->initialize($config);

                    if (isset($_POST['edit_retailer']) && !empty($_POST['edit_retailer']) && isset($_POST['old_store_logo']) && !empty($_POST['old_store_logo'])) {
                        $old_logo = explode('/', $this->input->post('old_store_logo', true));
                        delete_images(SELLER_DOCUMENTS_PATH, $old_logo[2]);
                    }

                    if (!empty($logo_files['store_logo']['name'])) {

                        $_FILES['temp_image']['name'] = $logo_files['store_logo']['name'];
                        $_FILES['temp_image']['type'] = $logo_files['store_logo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $logo_files['store_logo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $logo_files['store_logo']['error'];
                        $_FILES['temp_image']['size'] = $logo_files['store_logo']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $store_logo_error = 'Images :' . $store_logo_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array_logo = $other_img->data();
                            resize_review_images($temp_array_logo, FCPATH . SELLER_DOCUMENTS_PATH);
                            $store_logo_doc  = SELLER_DOCUMENTS_PATH . $temp_array_logo['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $logo_files['store_logo']['name'];
                        $_FILES['temp_image']['type'] = $logo_files['store_logo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $logo_files['store_logo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $logo_files['store_logo']['error'];
                        $_FILES['temp_image']['size'] = $logo_files['store_logo']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $store_logo_error = $other_img->display_errors();
                        }
                    }
                    //Deleting Uploaded Images if any overall error occured
                    if ($store_logo_error != NULL || !$this->form_validation->run()) {
                        if (isset($store_logo_doc) && !empty($store_logo_doc || !$this->form_validation->run())) {
                            foreach ($store_logo_doc as $key => $val) {
                                unlink(FCPATH . SELLER_DOCUMENTS_PATH . $store_logo_doc[$key]);
                            }
                        }
                    }
                }

                if ($store_logo_error != NULL) {
                    $this->response['error'] = true;
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['message'] =  $store_logo_error;
                    print_r(json_encode($this->response));
                    return;
                }

                //process national_identity_card
                $temp_array_id_card = $id_card_doc = array();
                $id_card_files = $_FILES;
                $id_card_error = "";
                $config = [
                    'upload_path' =>  FCPATH . SELLER_DOCUMENTS_PATH,
                    'allowed_types' => 'jpg|png|jpeg|gif',
                    'max_size' => 8000,
                ];
                if (isset($id_card_files['national_identity_card']) &&  !empty($id_card_files['national_identity_card']['name']) && isset($id_card_files['national_identity_card']['name'])) {
                    $other_img = $this->upload;
                    $other_img->initialize($config);

                    if (isset($_POST['edit_retailer']) && !empty($_POST['edit_retailer']) && isset($_POST['old_national_identity_card']) && !empty($_POST['old_national_identity_card'])) {
                        $old_national_identity_card = explode('/', $this->input->post('old_national_identity_card', true));
                        delete_images(SELLER_DOCUMENTS_PATH, $old_national_identity_card[2]);
                    }

                    if (!empty($id_card_files['national_identity_card']['name'])) {

                        $_FILES['temp_image']['name'] = $id_card_files['national_identity_card']['name'];
                        $_FILES['temp_image']['type'] = $id_card_files['national_identity_card']['type'];
                        $_FILES['temp_image']['tmp_name'] = $id_card_files['national_identity_card']['tmp_name'];
                        $_FILES['temp_image']['error'] = $id_card_files['national_identity_card']['error'];
                        $_FILES['temp_image']['size'] = $id_card_files['national_identity_card']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $id_card_error = 'Images :' . $id_card_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array_id_card = $other_img->data();
                            resize_review_images($temp_array_id_card, FCPATH . SELLER_DOCUMENTS_PATH);
                            $id_card_doc  = SELLER_DOCUMENTS_PATH . $temp_array_id_card['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $id_card_files['national_identity_card']['name'];
                        $_FILES['temp_image']['type'] = $id_card_files['national_identity_card']['type'];
                        $_FILES['temp_image']['tmp_name'] = $id_card_files['national_identity_card']['tmp_name'];
                        $_FILES['temp_image']['error'] = $id_card_files['national_identity_card']['error'];
                        $_FILES['temp_image']['size'] = $id_card_files['national_identity_card']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $id_card_error = $other_img->display_errors();
                        }
                    }
                    //Deleting Uploaded Images if any overall error occured
                    if ($id_card_error != NULL || !$this->form_validation->run()) {
                        if (isset($id_card_doc) && !empty($id_card_doc || !$this->form_validation->run())) {
                            foreach ($id_card_doc as $key => $val) {
                                unlink(FCPATH . SELLER_DOCUMENTS_PATH . $id_card_doc[$key]);
                            }
                        }
                    }
                }

                if ($id_card_error != NULL) {
                    $this->response['error'] = true;
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['message'] =  $id_card_error;
                    print_r(json_encode($this->response));
                    return;
                }

                //process address_proof
                $temp_array_proof = $proof_doc = array();
                $proof_files = $_FILES;
                $proof_error = "";
                $config = [
                    'upload_path' =>  FCPATH . SELLER_DOCUMENTS_PATH,
                    'allowed_types' => 'jpg|png|jpeg|gif',
                    'max_size' => 8000,
                ];
                if (isset($proof_files['address_proof']) && !empty($proof_files['address_proof']['name']) && isset($proof_files['address_proof']['name'])) {
                    $other_img = $this->upload;
                    $other_img->initialize($config);

                    if (isset($_POST['edit_retailer']) && !empty($_POST['edit_retailer']) && isset($_POST['old_address_proof']) && !empty($_POST['old_address_proof'])) {
                        $old_address_proof = explode('/', $this->input->post('old_address_proof', true));
                        delete_images(SELLER_DOCUMENTS_PATH, $old_address_proof[2]);
                    }

                    if (!empty($proof_files['address_proof']['name'])) {

                        $_FILES['temp_image']['name'] = $proof_files['address_proof']['name'];
                        $_FILES['temp_image']['type'] = $proof_files['address_proof']['type'];
                        $_FILES['temp_image']['tmp_name'] = $proof_files['address_proof']['tmp_name'];
                        $_FILES['temp_image']['error'] = $proof_files['address_proof']['error'];
                        $_FILES['temp_image']['size'] = $proof_files['address_proof']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $proof_error = 'Images :' . $proof_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array_proof = $other_img->data();
                            resize_review_images($temp_array_proof, FCPATH . SELLER_DOCUMENTS_PATH);
                            $proof_doc  = SELLER_DOCUMENTS_PATH . $temp_array_proof['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $proof_files['address_proof']['name'];
                        $_FILES['temp_image']['type'] = $proof_files['address_proof']['type'];
                        $_FILES['temp_image']['tmp_name'] = $proof_files['address_proof']['tmp_name'];
                        $_FILES['temp_image']['error'] = $proof_files['address_proof']['error'];
                        $_FILES['temp_image']['size'] = $proof_files['address_proof']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $proof_error = $other_img->display_errors();
                        }
                    }
                    //Deleting Uploaded Images if any overall error occured
                    if ($proof_error != NULL || !$this->form_validation->run()) {
                        if (isset($proof_doc) && !empty($proof_doc || !$this->form_validation->run())) {
                            foreach ($proof_doc as $key => $val) {
                                unlink(FCPATH . SELLER_DOCUMENTS_PATH . $proof_doc[$key]);
                            }
                        }
                    }
                }

                if ($proof_error != NULL) {
                    $this->response['error'] = true;
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['message'] =  $proof_error;
                    print_r(json_encode($this->response));
                    return;
                }

                /*$categories = "";
                // process categories
                if (isset($_POST['commission_data']) && !empty($_POST['commission_data'])) {

                    $commission_data = json_decode($this->input->post('commission_data'), true);
                    if (!is_array($commission_data['category_id'])) {
                        $categories = $commission_data['category_id'];
                    } else {
                        if (count($commission_data['category_id']) >= 2) {
                            $categories = implode(",", array_unique($commission_data['category_id']));
                        }
                    }
                }*/

                // process permissions of retailers
                $permmissions = array();
                $permmissions['require_products_approval'] = (isset($_POST['require_products_approval'])) ? 1 : 0;
                $permmissions['customer_privacy'] = (isset($_POST['customer_privacy'])) ? 1 : 0;
                $permmissions['view_order_otp'] = (isset($_POST['view_order_otp'])) ? 1 : 0;
                $permmissions['assign_delivery_boy'] = (isset($_POST['assign_delivery_boy'])) ? 1 : 0;

                if (isset($_POST['edit_retailer'])) {
                    /*if (!isset($_POST['commission_data']) && empty($_POST['commission_data'])) {
                        $category_ids = fetch_details(['id' => $this->input->post('edit_retailer_data_id', true)], "retailer_data", "category_ids");
                        $categories = $category_ids[0]['category_ids'];
                    }*/
                    $retailer_data = array(
                        'user_id' => $this->input->post('edit_retailer', true),
                        'edit_retailer_data_id' => $this->input->post('edit_retailer_data_id', true),
                        'status' => $this->input->post('status', true),
                        'company_name' => $this->input->post('company_name', true),
                        'brand_name_1' => $this->input->post('brand_name_1', true),
                        'brand_name_2' => $this->input->post('brand_name_2', true),
                        'brand_name_3' => $this->input->post('brand_name_3', true),
                        'plot_no' => $this->input->post('plot_no', true),
                        'street_locality' => $this->input->post('street_locality', true),
                        'landmark' => $this->input->post('landmark', true),
                        'pin' => $this->input->post('pin', true),
                        'city' => $this->input->post('city', true),
                        'state' => $this->input->post('state', true),
                        'account_number' => $this->input->post('account_number', true),
                        'account_name' => $this->input->post('account_name', true),
                        'bank_code' => $this->input->post('bank_code', true),
                        'bank_name' => $this->input->post('bank_name', true),
                        'bank_branch' => $this->input->post('bank_branch', true),
                        'bank_city' => $this->input->post('bank_city', true),
                        'bank_state' => $this->input->post('bank_state', true),
                        'have_gst_no' => $this->input->post('have_gst_no', true),
                        'gst_no' => $this->input->post('gst_no', true),
                        'pan_number' => $this->input->post('pan_number', true),
                        'have_fertilizer_license' => $this->input->post('have_fertilizer_license', true),
                        'fertilizer_license_no' => $this->input->post('fertilizer_license_no', true),
                        'have_pesticide_license_no' => $this->input->post('have_pesticide_license_no', true),
                        'pesticide_license_no' => $this->input->post('pesticide_license_no', true),
                        'have_seeds_license_no' => $this->input->post('have_seeds_license_no', true),
                        'seeds_license_no' => $this->input->post('seeds_license_no', true),
                        'permissions' => $permmissions,
                        //'slug' => create_unique_slug($this->input->post('store_name', true), 'retailer_data')
                    );
                    $retailer_profile = array(
                        'name' => $this->input->post('name', true),
                        'email' => $this->input->post('email', true),
                        'mobile' => $this->input->post('mobile', true),
                        'password' => $this->input->post('password', true),
                        'address' => $this->input->post('address', true),
                        'latitude' => $this->input->post('latitude', true),
                        'longitude' => $this->input->post('longitude', true)
                    );


                    if ($this->Retailer_model->add_retailer($retailer_data, $retailer_profile, $com_data)) {
                        $this->response['error'] = false;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $message = 'Retailer Update Successfully';
                        $this->response['message'] = $message;
                        print_r(json_encode($this->response));
                    } else {
                        $this->response['error'] = true;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['message'] = "Retailer data was not updated";
                        print_r(json_encode($this->response));
                    }
                } else {

                    if (!$this->form_validation->is_unique($_POST['mobile'], 'users.mobile') || !$this->form_validation->is_unique($_POST['email'], 'users.email')) {
                        $response["error"]   = true;
                        $response["message"] = "Email or mobile already exists !";
                        $response['csrfName'] = $this->security->get_csrf_token_name();
                        $response['csrfHash'] = $this->security->get_csrf_hash();
                        $response["data"] = array();
                        echo json_encode($response);
                        return false;
                    }

                    $identity_column = $this->config->item('identity', 'ion_auth');
                    $email = strtolower($this->input->post('email'));
                    $mobile = $this->input->post('mobile');
                    $identity = ($identity_column == 'mobile') ? $mobile : $email;
                    $password = $this->input->post('password');

                    $additional_data = [
                        'username' => $this->input->post('name', true),
                        'address' => $this->input->post('address', true),
                        'latitude' => $this->input->post('latitude', true),
                        'longitude' => $this->input->post('longitude', true),
                    ];

                    $this->ion_auth->register($identity, $password, $email, $additional_data, ['4']);
                    if (update_details(['active' => 1], [$identity_column => $identity], 'users')) {
                        $user_id = fetch_details(['mobile' => $mobile], 'users', 'id');
                        

                        $data = array(
                            'user_id' => $user_id[0]['id'],
                            'company_name' => $this->input->post('company_name', true),
                            'brand_name_1' => $this->input->post('brand_name_1', true),
                            'brand_name_2' => $this->input->post('brand_name_2', true),
                            'brand_name_3' => $this->input->post('brand_name_3', true),
                            'plot_no' => $this->input->post('plot_no', true),
                            'street_locality' => $this->input->post('street_locality', true),
                            'landmark' => $this->input->post('landmark', true),
                            'pin' => $this->input->post('pin', true),
                            'city' => $this->input->post('city', true),
                            'state' => $this->input->post('state', true),
                            'account_number' => $this->input->post('account_number', true),
                            'account_name' => $this->input->post('account_name', true),
                            'bank_code' => $this->input->post('bank_code', true),
                            'bank_name' => $this->input->post('bank_name', true),
                            'bank_branch' => $this->input->post('bank_branch', true),
                            'bank_city' => $this->input->post('bank_city', true),
                            'bank_state' => $this->input->post('bank_state', true),
                            'have_gst_no' => $this->input->post('have_gst_no', true),
                            'gst_no' => $this->input->post('gst_no', true),
                            'pan_number' => $this->input->post('pan_number', true),
                            'have_fertilizer_license' => $this->input->post('have_fertilizer_license', true),
                            'fertilizer_license_no' => $this->input->post('fertilizer_license_no', true),
                            'have_pesticide_license_no' => $this->input->post('have_pesticide_license_no', true),
                            'pesticide_license_no' => $this->input->post('pesticide_license_no', true),
                            'have_seeds_license_no' => $this->input->post('have_seeds_license_no', true),
                            'seeds_license_no' => $this->input->post('seeds_license_no', true),
                            'permissions' => $permmissions,
                        );
                        $insert_id = $this->Retailer_model->add_retailer($data, [], $com_data);
                        if (!empty($insert_id)) {
                            $this->response['error'] = false;
                            $this->response['csrfName'] = $this->security->get_csrf_token_name();
                            $this->response['csrfHash'] = $this->security->get_csrf_hash();
                            $this->response['message'] = 'Retailer Added Successfully';
                            print_r(json_encode($this->response));
                        } else {
                            $this->response['error'] = true;
                            $this->response['csrfName'] = $this->security->get_csrf_token_name();
                            $this->response['csrfHash'] = $this->security->get_csrf_hash();
                            $this->response['message'] = "Retailer data was not added";
                            print_r(json_encode($this->response));
                        }
                    } else {
                        $this->response['error'] = true;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $message = (isset($_POST['edit_retailer'])) ? 'Retailer not Updated' : 'Retailer not Added.';
                        $this->response['message'] = $message;
                        print_r(json_encode($this->response));
                    }
                }
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function manage_retailer()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = FORMS . 'retailer';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Add Retailer | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Add Retailer | ' . $settings['app_name'];
            $this->data['categories'] = $this->category_model->get_categories();
            if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                $this->data['title'] = 'Update Retailer | ' . $settings['app_name'];
                $this->data['meta_description'] = 'Update Retailer | ' . $settings['app_name'];
                $this->data['fetched_data'] = $this->db->select(' u.*,sd.* ')
                    ->join('users_groups ug', ' ug.user_id = u.id ')
                    ->join('retailer_data sd', ' sd.user_id = u.id ')
                    ->where(['ug.group_id' => '4', 'ug.user_id' => $_GET['edit_id']])
                    ->get('users u')
                    ->result_array();
            }
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function approved()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'manage-retailer';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Retailer Management | ' . $settings['app_name'];
            $this->data['meta_description'] = ' Retailer Management  | ' . $settings['app_name'];
            
            $this->data['page_title'] = 'Approved Retailers';
            $this->data['status'] = 1;
            
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function unapproved()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'manage-retailer';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Retailer Management | ' . $settings['app_name'];
            $this->data['meta_description'] = ' Retailer Management  | ' . $settings['app_name'];
            
            $this->data['page_title'] = 'Unapproved Retailers';
            $this->data['status'] = 2;
            
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function view_retailers($status = '')
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            return $this->Retailer_model->get_retailers_list($status);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function remove_retailers()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

            if (print_msg(!has_permissions('delete', 'retailer'), PERMISSION_ERROR_MSG, 'retailer', false)) {
                return true;
            }

            if (!isset($_GET['id']) && empty($_GET['id'])) {
                $this->response['error'] = true;
                $this->response['message'] = 'Retailer id is required';
                print_r(json_encode($this->response));
                return;
                exit();
            }
            $all_status = [0, 1, 2, 7];
            $status = $this->input->get('status', true);
            $id = $this->input->get('id', true);
            if (!in_array($status, $all_status)) {
                $this->response['error'] = true;
                $this->response['message'] = 'Invalid status';
                print_r(json_encode($this->response));
                return;
                exit();
            }
            if ($status == 2) {
                $this->response['error'] = true;
                $this->response['message'] = 'First approve this Retailer from edit retailer.';
                print_r(json_encode($this->response));
                return;
                exit();
            }
            $status = ($status == 7) ? 1 : (($status == 1) ? 7 : 1);

            if (update_details(['status' => $status], ['user_id' => $id], 'retailer_data') == TRUE) {
                $this->response['error'] = false;
                $this->response['message'] = 'Retailer removed succesfully';
                print_r(json_encode($this->response));
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'Something Went Wrong';
                print_r(json_encode($this->response));
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function delete_retailers()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

            if (print_msg(!has_permissions('delete', 'retailer'), PERMISSION_ERROR_MSG, 'retailer', false)) {
                return true;
            }

            if (!isset($_GET['id']) && empty($_GET['id'])) {
                $this->response['error'] = true;
                $this->response['message'] = 'Retailer id is required';
                print_r(json_encode($this->response));
                return;
                exit();
            }
            $id = $this->input->get('id', true);
            $delete = array(
                //"media" => 0,
                //"payment_requests" => 0,
                //"products" => 0,
                //"product_attributes" => 0,
                "order_items" => 0,
                "orders" => 0,
                //"order_bank_transfer" => 0,
                //"retailer_commission" => 0,
                "retailer_data" => 0,
                "users"=>0,
            );
            
            

            /*$retailer_media = fetch_details(['user_id' => $id], 'retailer_data', 'id,logo,national_identity_card,address_proof');

            if (!empty($retailer_media)) {
                unlink(FCPATH . $retailer_media[0]['logo']);
                unlink(FCPATH . $retailer_media[0]['national_identity_card']);
                unlink(FCPATH . $retailer_media[0]['address_proof']);
            }*/

            /*if (update_details(['retailer_id' => 0], ['retailer_id' => $id], 'media')) {
                $delete['media'] = 1;
            }*/

            /* check for retur requesst if retailer's product have */
            /*$return_req = $this->db->where(['p.retailer_id' => $id])->join('products p', 'p.id=rr.product_id')->get('return_requests rr')->result_array();
            if (!empty($return_req)) {
                $this->response['error'] = true;
                $this->response['message'] = 'Retailer could not be deleted.Either found some order items which has return request.Finalize those before deleting it';
                print_r(json_encode($this->response));
                return;
                exit();
            }
            $pr_ids = fetch_details(['retailer_id' => $id], "products", "id");
            if (delete_details(['retailer_id' => $id], 'products')) {
                $delete['products'] = 1;
            }
            foreach ($pr_ids as $row) {
                if (delete_details(['product_id' => $row['id']], 'product_attributes')) {
                    $delete['product_attributes'] = 1;
                }
            }*/

            /* check order items */
            $order_items = fetch_details(['user_id' => $id], 'order_items', 'id,order_id');
            if (delete_details(['user_id' => $id], 'order_items')) {
                $delete['order_items'] = 1;
            }
            if (!empty($order_items)) {
                $res_order_id = array_values(array_unique(array_column($order_items, "order_id")));
                for ($i = 0; $i < count($res_order_id); $i++) {
                    $orders = $this->db->where('oi.user_id != ' . $id . ' and oi.order_id=' . $res_order_id[$i])->join('orders o', 'o.id=oi.order_id', 'right')->get('order_items oi')->result_array();
                    if (empty($orders)) {
                        // delete orders
                        if (delete_details(['user_id' => $id], 'order_items')) {
                            $delete['order_items'] = 1;
                        }
                        if (delete_details(['id' => $res_order_id[$i]], 'orders')) {
                            $delete['orders'] = 1;
                        }
                        if (delete_details(['order_id' => $res_order_id[$i]], 'order_bank_transfer')) {
                            $delete['order_bank_transfer'] = 1;
                        }
                    }
                }
            } else {
                $delete['order_items'] = 1;
                $delete['orders'] = 1;
                $delete['order_bank_transfer'] = 1;
            }
            if (!empty($res_order_id)) {

                if (delete_details(['id' => $res_order_id[$i]], 'orders')) {
                    $delete['orders'] = 1;
                }
            } else {
                $delete['orders'] = 1;
            }

            /*if (delete_details(['retailer_id' => $id], 'retailer_commission')) {
                $delete['retailer_commission'] = 1;
            }*/
            if(delete_details(['user_id' => $id], 'retailer_data')) {
                $delete['retailer_data'] = 1;
            }
            
            if(delete_details(['id' => $id], 'users')) {
                $delete['users'] = 1;
            }

            $deleted = FALSE;
            if (!in_array(0, $delete)) {
                $deleted = TRUE;
            }

            if($deleted == TRUE) {
                $this->response['error'] = false;
                $this->response['message'] = 'Retailer deleted successfully';
                print_r(json_encode($this->response));
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'Something Went Wrong';
                print_r(json_encode($this->response));
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }


    public function add_retailer()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

            if (isset($_POST['edit_retailer'])) {
                if (print_msg(!has_permissions('update', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            } else {
                if (print_msg(!has_permissions('create', 'retailer'), PERMISSION_ERROR_MSG, 'retailer')) {
                    return true;
                }
            }

            $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Mail', 'trim|required|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean|min_length[5]');
            if (!isset($_POST['edit_retailer'])) {
                $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
                $this->form_validation->set_rules('confirm_password', 'Confirm password', 'trim|required|matches[password]|xss_clean');
            }
            $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
            $this->form_validation->set_rules('store_name', 'Store Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('tax_name', 'Tax Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('tax_number', 'Tax Number', 'trim|required|xss_clean');
            $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');
            if (!isset($_POST['edit_retailer'])) {
                if (isset($_POST['global_commission']) && empty($_POST['global_commission'])) {
                    $this->form_validation->set_rules('commission_data', 'Category Commission data or Global Commission is missing', 'trim|required|xss_clean');
                }
            }

            if (!isset($_POST['edit_retailer'])) {
                $this->form_validation->set_rules('store_logo', 'Store Logo', 'trim|xss_clean');
                $this->form_validation->set_rules('national_identity_card', 'National Identity Card', 'trim|xss_clean');
                $this->form_validation->set_rules('address_proof', 'Address Proof', 'trim|xss_clean');
            }

            if (!$this->form_validation->run()) {

                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {

                // process images of retailer

                if (!file_exists(FCPATH . SELLER_DOCUMENTS_PATH)) {
                    mkdir(FCPATH . SELLER_DOCUMENTS_PATH, 0777);
                }

                //process store logo
                $temp_array_logo = $store_logo_doc = array();
                $logo_files = $_FILES;
                $store_logo_error = "";
                $config = [
                    'upload_path' =>  FCPATH . SELLER_DOCUMENTS_PATH,
                    'allowed_types' => 'jpg|png|jpeg|gif',
                    'max_size' => 8000,
                ];
                if (isset($logo_files['store_logo']) && !empty($logo_files['store_logo']['name']) && isset($logo_files['store_logo']['name'])) {
                    $other_img = $this->upload;
                    $other_img->initialize($config);

                    if (isset($_POST['edit_retailer']) && !empty($_POST['edit_retailer']) && isset($_POST['old_store_logo']) && !empty($_POST['old_store_logo'])) {
                        $old_logo = explode('/', $this->input->post('old_store_logo', true));
                        delete_images(SELLER_DOCUMENTS_PATH, $old_logo[2]);
                    }

                    if (!empty($logo_files['store_logo']['name'])) {

                        $_FILES['temp_image']['name'] = $logo_files['store_logo']['name'];
                        $_FILES['temp_image']['type'] = $logo_files['store_logo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $logo_files['store_logo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $logo_files['store_logo']['error'];
                        $_FILES['temp_image']['size'] = $logo_files['store_logo']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $store_logo_error = 'Images :' . $store_logo_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array_logo = $other_img->data();
                            resize_review_images($temp_array_logo, FCPATH . SELLER_DOCUMENTS_PATH);
                            $store_logo_doc  = SELLER_DOCUMENTS_PATH . $temp_array_logo['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $logo_files['store_logo']['name'];
                        $_FILES['temp_image']['type'] = $logo_files['store_logo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $logo_files['store_logo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $logo_files['store_logo']['error'];
                        $_FILES['temp_image']['size'] = $logo_files['store_logo']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $store_logo_error = $other_img->display_errors();
                        }
                    }
                    //Deleting Uploaded Images if any overall error occured
                    if ($store_logo_error != NULL || !$this->form_validation->run()) {
                        if (isset($store_logo_doc) && !empty($store_logo_doc || !$this->form_validation->run())) {
                            foreach ($store_logo_doc as $key => $val) {
                                unlink(FCPATH . SELLER_DOCUMENTS_PATH . $store_logo_doc[$key]);
                            }
                        }
                    }
                }

                if ($store_logo_error != NULL) {
                    $this->response['error'] = true;
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['message'] =  $store_logo_error;
                    print_r(json_encode($this->response));
                    return;
                }

                //process national_identity_card
                $temp_array_id_card = $id_card_doc = array();
                $id_card_files = $_FILES;
                $id_card_error = "";
                $config = [
                    'upload_path' =>  FCPATH . SELLER_DOCUMENTS_PATH,
                    'allowed_types' => 'jpg|png|jpeg|gif',
                    'max_size' => 8000,
                ];
                if (isset($id_card_files['national_identity_card']) &&  !empty($id_card_files['national_identity_card']['name']) && isset($id_card_files['national_identity_card']['name'])) {
                    $other_img = $this->upload;
                    $other_img->initialize($config);

                    if (isset($_POST['edit_retailer']) && !empty($_POST['edit_retailer']) && isset($_POST['old_national_identity_card']) && !empty($_POST['old_national_identity_card'])) {
                        $old_national_identity_card = explode('/', $this->input->post('old_national_identity_card', true));
                        delete_images(SELLER_DOCUMENTS_PATH, $old_national_identity_card[2]);
                    }

                    if (!empty($id_card_files['national_identity_card']['name'])) {

                        $_FILES['temp_image']['name'] = $id_card_files['national_identity_card']['name'];
                        $_FILES['temp_image']['type'] = $id_card_files['national_identity_card']['type'];
                        $_FILES['temp_image']['tmp_name'] = $id_card_files['national_identity_card']['tmp_name'];
                        $_FILES['temp_image']['error'] = $id_card_files['national_identity_card']['error'];
                        $_FILES['temp_image']['size'] = $id_card_files['national_identity_card']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $id_card_error = 'Images :' . $id_card_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array_id_card = $other_img->data();
                            resize_review_images($temp_array_id_card, FCPATH . SELLER_DOCUMENTS_PATH);
                            $id_card_doc  = SELLER_DOCUMENTS_PATH . $temp_array_id_card['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $id_card_files['national_identity_card']['name'];
                        $_FILES['temp_image']['type'] = $id_card_files['national_identity_card']['type'];
                        $_FILES['temp_image']['tmp_name'] = $id_card_files['national_identity_card']['tmp_name'];
                        $_FILES['temp_image']['error'] = $id_card_files['national_identity_card']['error'];
                        $_FILES['temp_image']['size'] = $id_card_files['national_identity_card']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $id_card_error = $other_img->display_errors();
                        }
                    }
                    //Deleting Uploaded Images if any overall error occured
                    if ($id_card_error != NULL || !$this->form_validation->run()) {
                        if (isset($id_card_doc) && !empty($id_card_doc || !$this->form_validation->run())) {
                            foreach ($id_card_doc as $key => $val) {
                                unlink(FCPATH . SELLER_DOCUMENTS_PATH . $id_card_doc[$key]);
                            }
                        }
                    }
                }

                if ($id_card_error != NULL) {
                    $this->response['error'] = true;
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['message'] =  $id_card_error;
                    print_r(json_encode($this->response));
                    return;
                }

                //process address_proof
                $temp_array_proof = $proof_doc = array();
                $proof_files = $_FILES;
                $proof_error = "";
                $config = [
                    'upload_path' =>  FCPATH . SELLER_DOCUMENTS_PATH,
                    'allowed_types' => 'jpg|png|jpeg|gif',
                    'max_size' => 8000,
                ];
                if (isset($proof_files['address_proof']) && !empty($proof_files['address_proof']['name']) && isset($proof_files['address_proof']['name'])) {
                    $other_img = $this->upload;
                    $other_img->initialize($config);

                    if (isset($_POST['edit_retailer']) && !empty($_POST['edit_retailer']) && isset($_POST['old_address_proof']) && !empty($_POST['old_address_proof'])) {
                        $old_address_proof = explode('/', $this->input->post('old_address_proof', true));
                        delete_images(SELLER_DOCUMENTS_PATH, $old_address_proof[2]);
                    }

                    if (!empty($proof_files['address_proof']['name'])) {

                        $_FILES['temp_image']['name'] = $proof_files['address_proof']['name'];
                        $_FILES['temp_image']['type'] = $proof_files['address_proof']['type'];
                        $_FILES['temp_image']['tmp_name'] = $proof_files['address_proof']['tmp_name'];
                        $_FILES['temp_image']['error'] = $proof_files['address_proof']['error'];
                        $_FILES['temp_image']['size'] = $proof_files['address_proof']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $proof_error = 'Images :' . $proof_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array_proof = $other_img->data();
                            resize_review_images($temp_array_proof, FCPATH . SELLER_DOCUMENTS_PATH);
                            $proof_doc  = SELLER_DOCUMENTS_PATH . $temp_array_proof['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $proof_files['address_proof']['name'];
                        $_FILES['temp_image']['type'] = $proof_files['address_proof']['type'];
                        $_FILES['temp_image']['tmp_name'] = $proof_files['address_proof']['tmp_name'];
                        $_FILES['temp_image']['error'] = $proof_files['address_proof']['error'];
                        $_FILES['temp_image']['size'] = $proof_files['address_proof']['size'];
                        if (!$other_img->do_upload('temp_image')) {
                            $proof_error = $other_img->display_errors();
                        }
                    }
                    //Deleting Uploaded Images if any overall error occured
                    if ($proof_error != NULL || !$this->form_validation->run()) {
                        if (isset($proof_doc) && !empty($proof_doc || !$this->form_validation->run())) {
                            foreach ($proof_doc as $key => $val) {
                                unlink(FCPATH . SELLER_DOCUMENTS_PATH . $proof_doc[$key]);
                            }
                        }
                    }
                }

                if ($proof_error != NULL) {
                    $this->response['error'] = true;
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['message'] =  $proof_error;
                    print_r(json_encode($this->response));
                    return;
                }

                $categories = "";
                // process categories
                if (isset($_POST['commission_data']) && !empty($_POST['commission_data'])) {

                    $commission_data = json_decode($this->input->post('commission_data'), true);
                    if (!is_array($commission_data['category_id'])) {
                        $categories = $commission_data['category_id'];
                    } else {
                        if (count($commission_data['category_id']) >= 2) {
                            $categories = implode(",", array_unique($commission_data['category_id']));
                        }
                    }
                }

                // process permissions of retailers
                $permmissions = array();
                $permmissions['require_products_approval'] = (isset($_POST['require_products_approval'])) ? 1 : 0;
                $permmissions['customer_privacy'] = (isset($_POST['customer_privacy'])) ? 1 : 0;
                $permmissions['view_order_otp'] = (isset($_POST['view_order_otp'])) ? 1 : 0;
                $permmissions['assign_delivery_boy'] = (isset($_POST['assign_delivery_boy'])) ? 1 : 0;

                if (isset($_POST['edit_retailer'])) {
                    if (!isset($_POST['commission_data']) && empty($_POST['commission_data'])) {
                        $category_ids = fetch_details(['id' => $this->input->post('edit_retailer_data_id', true)], "retailer_data", "category_ids");
                        $categories = $category_ids[0]['category_ids'];
                    }
                    $retailer_data = array(
                        'user_id' => $this->input->post('edit_retailer', true),
                        'edit_retailer_data_id' => $this->input->post('edit_retailer_data_id', true),
                        'address_proof' => (!empty($proof_doc)) ? $proof_doc : $this->input->post('old_address_proof', true),
                        'national_identity_card' => (!empty($id_card_doc)) ? $id_card_doc : $this->input->post('old_national_identity_card', true),
                        'store_logo' => (!empty($store_logo_doc)) ? $store_logo_doc : $this->input->post('old_store_logo', true),
                        'status' => $this->input->post('status', true),
                        'pan_number' => $this->input->post('pan_number', true),
                        'tax_number' => $this->input->post('tax_number', true),
                        'tax_name' => $this->input->post('tax_name', true),
                        'bank_name' => $this->input->post('bank_name', true),
                        'bank_code' => $this->input->post('bank_code', true),
                        'account_name' => $this->input->post('account_name', true),
                        'account_number' => $this->input->post('account_number', true),
                        'store_description' => $this->input->post('store_description', true),
                        'store_url' => $this->input->post('store_url', true),
                        'store_name' => $this->input->post('store_name', true),
                        'global_commission' => (isset($_POST['global_commission']) && !empty($_POST['global_commission'])) ? $this->input->post('global_commission', true) : 0,
                        'categories' => $categories,
                        'permissions' => $permmissions,
                        'slug' => create_unique_slug($this->input->post('store_name', true), 'retailer_data')
                    );
                    $retailer_profile = array(
                        'name' => $this->input->post('name', true),
                        'email' => $this->input->post('email', true),
                        'mobile' => $this->input->post('mobile', true),
                        'password' => $this->input->post('password', true),
                        'address' => $this->input->post('address', true),
                        'latitude' => $this->input->post('latitude', true),
                        'longitude' => $this->input->post('longitude', true)
                    );

                    $com_data = array();
                    if (isset($_POST['commission_data']) && !empty($_POST['commission_data'])) {
                        $commission_data = json_decode($this->input->post('commission_data'), true);
                        if (count($commission_data['category_id']) > 2) {
                            $cat_array = array_unique($commission_data['category_id']);
                            foreach ($commission_data['commission'] as $key => $val) {
                                if (!array_key_exists($key, $cat_array)) unset($commission_data['commission'][$key]);
                            }
                            $cat_array = array_values($cat_array);
                            $com_array = array_values($commission_data['commission']);
                            
                            for ($i = 0; $i < count($cat_array); $i++) {
                                $tmp['retailer_id'] = $this->input->post('edit_retailer', true);
                                $tmp['category_id'] = $cat_array[$i];
                                $tmp['commission'] = $com_array[$i];
                                $com_data[] = $tmp;
                            }
                        } else {
                            $com_data[0] = array(
                                "retailer_id" => $this->input->post('edit_retailer', true),
                                "category_id" => $commission_data['category_id'],
                                "commission" => $commission_data['commission'],
                            );
                        }
                    }

                    if ($this->Retailer_model->add_retailer($retailer_data, $retailer_profile, $com_data)) {
                        $this->response['error'] = false;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $message = 'Retailer Update Successfully';
                        $this->response['message'] = $message;
                        print_r(json_encode($this->response));
                    } else {
                        $this->response['error'] = true;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['message'] = "Retailer data was not updated";
                        print_r(json_encode($this->response));
                    }
                } else {

                    if (!$this->form_validation->is_unique($_POST['mobile'], 'users.mobile') || !$this->form_validation->is_unique($_POST['email'], 'users.email')) {
                        $response["error"]   = true;
                        $response["message"] = "Email or mobile already exists !";
                        $response['csrfName'] = $this->security->get_csrf_token_name();
                        $response['csrfHash'] = $this->security->get_csrf_hash();
                        $response["data"] = array();
                        echo json_encode($response);
                        return false;
                    }

                    $identity_column = $this->config->item('identity', 'ion_auth');
                    $email = strtolower($this->input->post('email'));
                    $mobile = $this->input->post('mobile');
                    $identity = ($identity_column == 'mobile') ? $mobile : $email;
                    $password = $this->input->post('password');

                    $additional_data = [
                        'username' => $this->input->post('name', true),
                        'address' => $this->input->post('address', true),
                        'latitude' => $this->input->post('latitude', true),
                        'longitude' => $this->input->post('longitude', true),
                    ];

                    $this->ion_auth->register($identity, $password, $email, $additional_data, ['4']);
                    if (update_details(['active' => 1], [$identity_column => $identity], 'users')) {
                        $user_id = fetch_details(['mobile' => $mobile], 'users', 'id');
                        $com_data = array();
                        if (isset($_POST['commission_data']) && !empty($_POST['commission_data'])) {

                            $commission_data = json_decode($this->input->post('commission_data'), true);

                            if (is_array($commission_data['category_id'])) {
                                if (count($commission_data['category_id']) >= 2) {
                                    $cat_array = array_unique($commission_data['category_id']);
                                    foreach ($commission_data['commission'] as $key => $val) {
                                        if (!array_key_exists($key, $cat_array)) unset($commission_data['commission'][$key]);
                                    }
                                    $cat_array = array_values($cat_array);
                                    $com_array = array_values($commission_data['commission']);

                                    for ($i = 0; $i < count($cat_array); $i++) {
                                        $tmp['retailer_id'] = $user_id[0]['id'];
                                        $tmp['category_id'] = $cat_array[$i];
                                        $tmp['commission'] = $com_array[$i];
                                        $com_data[] = $tmp;
                                    }
                                } else {
                                    $com_data[0] = array(
                                        "retailer_id" => $user_id[0]['id'],
                                        "category_id" => $commission_data['category_id'],
                                        "commission" => $commission_data['commission'],
                                    );
                                }
                            } else {
                                $com_data[0] = array(
                                    "retailer_id" => $user_id[0]['id'],
                                    "category_id" => $commission_data['category_id'],
                                    "commission" => $commission_data['commission'],
                                );
                            }
                        } else {
                            $category_ids = fetch_details(null, 'categories', 'id');
                            $categories = implode(",", array_column($category_ids, "id"));
                        }

                        $data = array(
                            'user_id' => $user_id[0]['id'],
                            'address_proof' => (!empty($proof_doc)) ? $proof_doc : null,
                            'national_identity_card' => (!empty($id_card_doc)) ? $id_card_doc : null,
                            'store_logo' => (!empty($store_logo_doc)) ? $store_logo_doc : null,
                            'status' => $this->input->post('status', true),
                            'pan_number' => $this->input->post('pan_number', true),
                            'tax_number' => $this->input->post('tax_number', true),
                            'tax_name' => $this->input->post('tax_name', true),
                            'bank_name' => $this->input->post('bank_name', true),
                            'bank_code' => $this->input->post('bank_code', true),
                            'account_name' => $this->input->post('account_name', true),
                            'account_number' => $this->input->post('account_number', true),
                            'store_description' => $this->input->post('store_description', true),
                            'store_url' => $this->input->post('store_url', true),
                            'store_name' => $this->input->post('store_name', true),
                            'global_commission' => (isset($_POST['global_commission']) && !empty($_POST['global_commission'])) ? $this->input->post('global_commission', true) : 0,                            'categories' => $categories,
                            'permissions' => $permmissions,
                            'categories' => $categories,
                            'slug' => create_unique_slug($this->input->post('store_name', true), 'retailer_data')
                        );
                        $insert_id = $this->Retailer_model->add_retailer($data, [], $com_data);
                        if (!empty($insert_id)) {
                            $this->response['error'] = false;
                            $this->response['csrfName'] = $this->security->get_csrf_token_name();
                            $this->response['csrfHash'] = $this->security->get_csrf_hash();
                            $this->response['message'] = 'Retailer Added Successfully';
                            print_r(json_encode($this->response));
                        } else {
                            $this->response['error'] = true;
                            $this->response['csrfName'] = $this->security->get_csrf_token_name();
                            $this->response['csrfHash'] = $this->security->get_csrf_hash();
                            $this->response['message'] = "Retailer data was not added";
                            print_r(json_encode($this->response));
                        }
                    } else {
                        $this->response['error'] = true;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $message = (isset($_POST['edit_retailer'])) ? 'Retailer not Updated' : 'Retailer not Added.';
                        $this->response['message'] = $message;
                        print_r(json_encode($this->response));
                    }
                }
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function get_retailer_commission_data()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $result = array();
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $id = $this->input->post('id', true);
                $result = $this->Retailer_model->get_retailer_commission_data($id);
            } else {
                $result = fetch_details("", 'categories', 'id,name');
            }
            if (empty($result)) {
                $this->response['error'] = true;
                $this->response['message'] = "No category & commission data found for retailer.";
                $this->response['data'] = [];
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($this->response));
                return false;
            } else {
                $this->response['error'] = false;
                $this->response['data'] = $result;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($this->response));
                return false;
            }
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            $this->response['data'] = [];
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function create_slug()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $tmpRow = $update_batch = array();
            $retailers = fetch_details('slug IS NULL', 'retailer_data', 'id,store_name');
            if (!empty($retailers)) {
                foreach ($retailers as $row) {
                    $tmpRow['id'] = $row['id'];
                    $tmpRow['slug'] = create_unique_slug($row['store_name'], 'retailer_data');
                    $this->Retailer_model->create_slug($tmpRow);
                }
                $this->response['error'] = false;
                $this->response['message'] = "Slug Created Successfully.";
                $this->response['data'] = [];
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($this->response));
                return false;
            } else {
                $this->response['error'] = true;
                $this->response['message'] = 'Already Created No need to create again.';
                $this->response['data'] = [];
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($this->response));
                return false;
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
