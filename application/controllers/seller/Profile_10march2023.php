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
                'max_size' => 2000,
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
            
            $designation = $this->input->post('designation', true);
            $alternate_mobile = $this->input->post('alternate_mobile', true);
            $alternate_email = $this->input->post('alternate_email', true);
            $landline        = $this->input->post('landline',true);
            $alternate_landline = $this->input->post('alternate_landline',true);
            
            $user_details = ['username' => $this->input->post('username'), 'email' => $this->input->post('email'), 'profile_img' => (!empty($avatar_doc)) ? $avatar_doc : null,'designation'=>$designation, 'alternate_mobile'=>$alternate_mobile, 'alternate_email'=> $alternate_email,'landline'=>$landline,'alternate_landline'=>$alternate_landline];
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
            
            if (!file_exists(FCPATH . SELLER_LOGO_PATH)) {
                mkdir(FCPATH . SELLER_LOGO_PATH, 0777);
            }
            
            //process store logo
            $temp_array_logo = $logo_doc = array();
            $logo_files = $_FILES;
            $logo_error = "";
            $config = [
                'upload_path' =>  FCPATH . SELLER_LOGO_PATH,
                'allowed_types' => 'jpg|png|jpeg|gif',
                'max_size' => 2000,
            ];
            if(isset($logo_files['logo']) && !empty($logo_files['logo']['name']) && isset($logo_files['logo']['name'])) {
                $other_img = $this->upload;
                $other_img->initialize($config);

                if(isset($logo_files['logo']) && !empty($logo_files['logo']['name']) && isset($_POST['old_logo']) && !empty($_POST['old_logo'])) {
                    $old_logo = explode('/', $this->input->post('old_logo', true));
                    delete_images(SELLER_LOGO_PATH, $old_logo[2]);
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
                        resize_review_images($temp_array_logo, FCPATH . SELLER_LOGO_PATH);
                        $logo_doc  = SELLER_LOGO_PATH . $temp_array_logo['file_name'];
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
                            unlink(FCPATH . SELLER_LOGO_PATH . $logo_doc[$key]);
                        }
                    }
                }
            }
            else
            {
                $logo_doc = $this->input->post('old_logo', true);
            }
            
            $seller_data = ['company_name' => $this->input->post('company_name'), 'store_name' => $this->input->post('company_name'), 'slug' => create_unique_slug($this->input->post('company_name', true), 'seller_data'), 'year_establish' => $this->input->post('year_establish'), 'owner_name' => $this->input->post('owner_name'), 'logo' => $logo_doc, 'brand_name_1' => $this->input->post('brand_name_1'), 'brand_name_2' => $this->input->post('brand_name_2'), 'brand_name_3' => $this->input->post('brand_name_3'), 'plot_no' => $this->input->post('plot_no'), 'street_locality' => $this->input->post('street_locality'), 'landmark' => $this->input->post('landmark'), 'pin' => $this->input->post('pin'), 'city_id' => $this->input->post('city_id'), 'state_id' => $this->input->post('state_id'),'website_url'=>$this->input->post('website_url'),'google_business_url'=>$this->input->post('google_business_url'),'facebook_business_url'=>$this->input->post('facebook_business_url'),'instagram_business_url'=>$this->input->post('instagram_business_url'),'min_order_value'=>$this->input->post('min_order_value')];
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
            
            $seller_data = ['have_fertilizer_license' => $this->input->post('have_fertilizer_license'), 'fertilizer_license_no' => $this->input->post('fertilizer_license_no'), 'fertilizer_license_no' => $this->input->post('fertilizer_license_no'), 'fert_lic_issue_date' => $this->input->post('fert_lic_issue_date'), 'fert_lic_expiry_date' => $this->input->post('fert_lic_expiry_date'),'have_pesticide_license_no' => $this->input->post('have_pesticide_license_no'),  'pesticide_license_no' => $this->input->post('pesticide_license_no'), 'pest_lic_issue_date' => $this->input->post('pest_lic_issue_date'), 'pest_lic_expiry_date' => $this->input->post('pest_lic_expiry_date'),  'have_seeds_license_no' => $this->input->post('have_seeds_license_no'), 'seeds_license_no' => $this->input->post('seeds_license_no'), 'seeds_lic_issue_date' => $this->input->post('seeds_lic_issue_date'), 'seeds_lic_expiry_date' => $this->input->post('seeds_lic_expiry_date'), 'is_finish' => $this->input->post('is_finish')];
            $seller_data = escape_array($seller_data);
            
            $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
            
            $this->response['redirect_to'] ='';
            $this->response['error'] = false;
            $this->response['message'] = 'License Details Updated Succesfully';
            echo json_encode($this->response);
            return false;
            
        }
    }
    
    public function business_card()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $settings = get_settings('system_settings', true);
            $user_id = $this->session->userdata('user_id');
            $this->data['identity_column'] = $identity_column;
            $this->data['main_page'] = FORMS . 'seller_business_card';
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
    
    public function save_business_card()
    {
        if (!file_exists(FCPATH . SELLER_BUSINESS_CARD_FRONT_PATH)) {
            mkdir(FCPATH . SELLER_BUSINESS_CARD_FRONT_PATH, 0777);
        }
        
        //process store business_card_front
        $temp_array_business_card_front = $business_card_front_doc = array();
        $business_card_front_files = $_FILES;
        $business_card_front_error = "";
        $config = [
            'upload_path' =>  FCPATH . SELLER_BUSINESS_CARD_FRONT_PATH,
            'allowed_types' => 'jpg|png|jpeg|gif',
            'max_size' => 2000,
        ];
        if(isset($business_card_front_files['business_card_front']) && !empty($business_card_front_files['business_card_front']['name']) && isset($business_card_front_files['business_card_front']['name'])) {
            $other_img = $this->upload;
            $other_img->initialize($config);

            if(isset($business_card_front_files['business_card_front']) && !empty($business_card_front_files['business_card_front']['name']) && isset($_POST['old_business_card_front']) && !empty($_POST['old_business_card_front'])) {
                $old_business_card_front = explode('/', $this->input->post('old_business_card_front', true));
                delete_images(SELLER_BUSINESS_CARD_FRONT_PATH, $old_business_card_front[2]);
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
                    resize_review_images($temp_array_business_card_front, FCPATH . SELLER_BUSINESS_CARD_FRONT_PATH);
                    $business_card_front_doc  = SELLER_BUSINESS_CARD_FRONT_PATH . $temp_array_business_card_front['file_name'];
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
                        unlink(FCPATH . SELLER_BUSINESS_CARD_FRONT_PATH . $business_card_front_doc[$key]);
                    }
                }
            }
        }
        else
        {
            $business_card_front_doc = $this->input->post('old_business_card_front', true);
        }
        
        if (!file_exists(FCPATH . SELLER_BUSINESS_CARD_BACK_PATH)) {
            mkdir(FCPATH . SELLER_BUSINESS_CARD_BACK_PATH, 0777);
        }
        
        //process store business_card_back
        $temp_array_business_card_back = $business_card_back_doc = array();
        $business_card_back_files = $_FILES;
        $business_card_back_error = "";
        $config = [
            'upload_path' =>  FCPATH . SELLER_BUSINESS_CARD_BACK_PATH,
            'allowed_types' => 'jpg|png|jpeg|gif',
            'max_size' => 2000,
        ];
        if(isset($business_card_back_files['business_card_back']) && !empty($business_card_back_files['business_card_back']['name']) && isset($business_card_back_files['business_card_back']['name'])) {
            $other_img = $this->upload;
            $other_img->initialize($config);

            if(isset($business_card_back_files['business_card_back']) && !empty($business_card_back_files['business_card_back']['name']) && isset($_POST['old_business_card_back']) && !empty($_POST['old_business_card_back'])) {
                $old_business_card_back = explode('/', $this->input->post('old_business_card_back', true));
                delete_images(SELLER_BUSINESS_CARD_BACK_PATH, $old_business_card_back[2]);
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
                    resize_review_images($temp_array_business_card_back, FCPATH . SELLER_BUSINESS_CARD_BACK_PATH);
                    $business_card_back_doc  = SELLER_BUSINESS_CARD_BACK_PATH . $temp_array_business_card_back['file_name'];
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
                        unlink(FCPATH . SELLER_BUSINESS_CARD_BACK_PATH . $business_card_back_doc[$key]);
                    }
                }
            }
        }
        else
        {
            $business_card_back_doc = $this->input->post('old_business_card_back', true);
        }
        
        $seller_data = ['business_card_front' => $business_card_front_doc, 'business_card_back' => $business_card_back_doc];
        $seller_data = escape_array($seller_data);
        $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
        
        $this->response['redirect_to'] = '';// base_url('my-account/bank-details/'.$is_seller);
        $this->response['error'] = false;
        $this->response['message'] = 'Business Card Updated Succesfully';
        echo json_encode($this->response);
        return false;
    }
    
}
