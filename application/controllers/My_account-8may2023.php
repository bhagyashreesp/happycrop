<?php
defined('BASEPATH') or exit('No direct script access allowed');

class My_account extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation', 'pagination', 'upload']);
        $this->load->helper(['url', 'language', 'file']);
        $this->load->model(['cart_model', 'category_model', 'address_model', 'order_model', 'Transaction_model']);
        $this->lang->load('auth');
        $this->data['is_logged_in'] = ($this->ion_auth->logged_in()) ? 1 : 0;
        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->data['settings'] = get_settings('system_settings', true);
        $this->data['web_settings'] = get_settings('web_settings', true);
        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();
    }


    public function index()
    {
        if ($this->data['is_logged_in']) {
            $this->data['main_page'] = 'dashboard';
            $this->data['title'] = 'Dashboard | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Dashboard, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Dashboard | ' . $this->data['web_settings']['meta_description'];
            
            $this->data['user']      = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->data['user_id']   = $this->ion_auth->get_user_id();
            $is_seller               = (int)$this->ion_auth->is_seller();
            
            $this->data['orders_total_amt'] = 0;
            $this->data['total_orders'] = 0;
            $this->data['in_process_orders'] = 0;
            $this->data['shipped_orders'] = 0;
            $this->data['issue_raised_orders'] = 0;
            $this->data['cancelled_orders'] = 0;
            $this->data['delivered_orders'] = 0;
            if(!$is_seller)
            {
                $this->data['retailer_data'] = $this->db->get_where('retailer_data', array('user_id'=>$this->data['user_id']))->row_array();
                $this->data['orders_total_amt'] = retailer_orders_total($this->data['user_id']);
                $this->data['total_orders'] = retailer_orders_count("", $this->data['user_id']);
                $this->data['in_process_orders'] = retailer_orders_count(array('received','payment_demand','payment_ack','schedule_delivery','send_payment_confirmation',), $this->data['user_id']);
                $this->data['shipped_orders'] = retailer_orders_count("send_invoice", $this->data['user_id']);
                $this->data['issue_raised_orders'] = retailer_orders_count(array("complaint","complaint_msg"), $this->data['user_id']);
                $this->data['cancelled_orders'] = retailer_orders_count("cancelled", $this->data['user_id']);
                $this->data['delivered_orders'] = retailer_orders_count(array("delivered","send_mfg_payment_ack","send_mfg_payment_confirmation"), $this->data['user_id']);
            }
            
            $this->data['sliders'] = get_sliders();
            
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    
    public function basic_profile()
    {
        if ($this->ion_auth->logged_in()) {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $this->data['users'] = $this->ion_auth->user()->row();
            $this->data['identity_column'] = $identity_column;
            $this->data['main_page'] = 'basic_profile';
            $this->data['is_seller'] = (int)$this->ion_auth->is_seller();
            $this->data['title'] = 'Profile | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    
    public function save_step3()
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
    
    public function business_details($is_seller = 0)
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
            $this->data['main_page'] = 'business_details';
            $this->data['title']     = 'Business Details | ' . $this->data['web_settings']['site_title'];
            
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
    }
    
    public function save_step4()
    {
        if(!$this->ion_auth->logged_in()) {
            redirect(base_url());
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
            
            if($is_seller)
            {
                $seller_data = ['company_name' => $this->input->post('company_name'), 'store_name' => $this->input->post('company_name'), 'slug' => create_unique_slug($this->input->post('company_name', true), 'seller_data'), 'brand_name_1' => $this->input->post('brand_name_1'), 'brand_name_2' => $this->input->post('brand_name_2'), 'brand_name_3' => $this->input->post('brand_name_3'), 'plot_no' => $this->input->post('plot_no'), 'street_locality' => $this->input->post('street_locality'), 'landmark' => $this->input->post('landmark'), 'pin' => $this->input->post('pin'), 'city' => $this->input->post('city'), 'state' => $this->input->post('state')];
                $seller_data = escape_array($seller_data);
                $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                
                $this->response['redirect_to'] = '';// base_url('my-account/bank-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Business Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
            else
            {
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
                    'max_size' => 2000,
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
                
                $retailer_data = ['company_name' => $this->input->post('company_name'), 'store_name' => $this->input->post('company_name'), 'slug' => create_unique_slug($this->input->post('company_name', true), 'retailer_data'), 'year_establish' => $this->input->post('year_establish'), 'owner_name' => $this->input->post('owner_name'), 'logo' => $logo_doc, 'shop_name' => $this->input->post('shop_name'), 'plot_no' => $this->input->post('plot_no'), 'street_locality' => $this->input->post('street_locality'), 'landmark' => $this->input->post('landmark'), 'pin' => $this->input->post('pin'), 'city_id' => $this->input->post('city_id'), 'state_id' => $this->input->post('state_id'), 'storage_shop_name' => $this->input->post('storage_shop_name'),'storage_plot_no' => $this->input->post('storage_plot_no'), 'storage_street_locality' => $this->input->post('storage_street_locality'), 'storage_landmark' => $this->input->post('storage_landmark'), 'storage_pin' => $this->input->post('storage_pin'), 'storage_city_id' => $this->input->post('storage_city_id'), 'storage_state_id' => $this->input->post('storage_state_id'),'website_url'=>$this->input->post('website_url'),'google_business_url'=>$this->input->post('google_business_url'),'facebook_business_url'=>$this->input->post('facebook_business_url'),'instagram_business_url'=>$this->input->post('instagram_business_url')];
                $retailer_data = escape_array($retailer_data);
                $this->db->set($retailer_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                
                $retailer_info = $this->db->get_where('retailer_data', array('user_id'=>$this->input->post('user_id')))->row();
                
                $identity_column = $this->config->item('identity', 'ion_auth');
                $user_info       = $this->ion_auth->user()->row();
            
                $check1 = $this->db->get_where('addresses', array('user_id'=>$user_info->id,'add_order'=>1))->row_array();
                
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
            
        }
    }
    
    public function getCities()
    {
        ob_clean();
        $state_id = $this->input->post('state_id');
        $html     = "<option value=''>Select</option>";
        $cities   = $this->db->select("*")->from("cities")->where('state_id',$state_id)->get()->result();
        foreach($cities as $city)
        {
            $html  .='<option value="'. $city->id.'">'.$city->name.'</option>';
        }
        echo $html;die;
    }
    
    public function bank_details($is_seller = 0)
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
            $this->data['main_page'] = 'bank_details';
            $this->data['title'] = 'Bank Details | ' . $this->data['web_settings']['site_title'];
            
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
    }
    
    public function save_step5()
    {
        if(!$this->ion_auth->logged_in()) {
            redirect(base_url());
        }
        
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
                $seller_data = ['account_name'=>$this->input->post('account_name'),'account_number' => $this->input->post('account_number'), 'bank_code' => $this->input->post('bank_code'), 'account_type' => $this->input->post('account_type'), 'bank_name' => $this->input->post('bank_name'), 'bank_branch' => $this->input->post('bank_branch'), 'bank_city' => $this->input->post('bank_city'), 'bank_state' => $this->input->post('bank_state')];
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
                
                $this->response['redirect_to'] = '';// base_url('my-account/gst-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Bank Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
            else
            {
                $seller_data = ['account_name'=>$this->input->post('account_name'),'account_number' => $this->input->post('account_number'), 'bank_code' => $this->input->post('bank_code'), 'account_type' => $this->input->post('account_type'), 'bank_name' => $this->input->post('bank_name'), 'bank_branch' => $this->input->post('bank_branch'), 'bank_city' => $this->input->post('bank_city'), 'bank_state' => $this->input->post('bank_state')];
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
                
                $this->response['redirect_to'] = '';// base_url('my-account/gst-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Bank Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
            
        }
    }
    
    public function gst_details($is_seller = 0)
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
            $this->data['main_page'] = 'gst_details';
            $this->data['title'] = 'GST | ' . $this->data['web_settings']['site_title'];
            
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
    }
    
    public function save_step6()
    {
        if(!$this->ion_auth->logged_in()) {
            redirect(base_url());
        }
        
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
                
                $this->response['redirect_to'] = '';// base_url('my-account/license-details/'.$is_seller);
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
                
                $this->response['redirect_to'] = '';// base_url('my-account/license-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'GST Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
        }
    }
    
    public function license_details($is_seller = 0)
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
            $this->data['main_page'] = 'license_details';
            $this->data['title'] = 'License | ' . $this->data['web_settings']['site_title'];
            
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
    }
    
    public function save_step7()
    {
        if(!$this->ion_auth->logged_in()) {
            redirect(base_url());
        }
        
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
                $seller_data = ['have_fertilizer_license' => $this->input->post('have_fertilizer_license'), 'fertilizer_license_no' => $this->input->post('fertilizer_license_no'), 'have_pesticide_license_no' => $this->input->post('have_pesticide_license_no'), 'pesticide_license_no' => $this->input->post('pesticide_license_no'), 'have_seeds_license_no' => $this->input->post('have_seeds_license_no'), 'seeds_license_no' => $this->input->post('seeds_license_no'),'is_finish' => $this->input->post('is_finish')];
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
                
                $this->response['redirect_to'] = '';// base_url('my-account/license-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'License Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
            else
            {
                $seller_data = ['have_fertilizer_license' => $this->input->post('have_fertilizer_license'), 'fertilizer_license_no' => $this->input->post('fertilizer_license_no'), 'fertilizer_license_no' => $this->input->post('fertilizer_license_no'), 'fert_lic_issue_date' => $this->input->post('fert_lic_issue_date'), 'fert_lic_expiry_date' => $this->input->post('fert_lic_expiry_date'),'have_pesticide_license_no' => $this->input->post('have_pesticide_license_no'),  'pesticide_license_no' => $this->input->post('pesticide_license_no'), 'pest_lic_issue_date' => $this->input->post('pest_lic_issue_date'), 'pest_lic_expiry_date' => $this->input->post('pest_lic_expiry_date'),  'have_seeds_license_no' => $this->input->post('have_seeds_license_no'), 'seeds_license_no' => $this->input->post('seeds_license_no'), 'seeds_lic_issue_date' => $this->input->post('seeds_lic_issue_date'), 'seeds_lic_expiry_date' => $this->input->post('seeds_lic_expiry_date'), 'is_finish' => $this->input->post('is_finish')];
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
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                }
                
                $this->response['redirect_to'] = '';// base_url('my-account/license-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'License Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
            
        }
    }
    
    public function business_card($is_seller = 0)
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
            $this->data['main_page'] = 'business_card';
            $this->data['title'] = 'Business Card | ' . $this->data['web_settings']['site_title'];
            
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
    }
    
    public function save_step8()
    {
        if(!$this->ion_auth->logged_in()) {
            redirect(base_url());
        }
        else
        {
            $is_seller = $this->input->post('is_seller');
            
            if($is_seller)
            {
                /*$seller_data = ['company_name' => $this->input->post('company_name'), 'brand_name_1' => $this->input->post('brand_name_1'), 'brand_name_2' => $this->input->post('brand_name_2'), 'brand_name_3' => $this->input->post('brand_name_3'), 'plot_no' => $this->input->post('plot_no'), 'street_locality' => $this->input->post('street_locality'), 'landmark' => $this->input->post('landmark'), 'pin' => $this->input->post('pin'), 'city' => $this->input->post('city'), 'state' => $this->input->post('state')];
                $seller_data = escape_array($seller_data);
                $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                */
                $this->response['redirect_to'] = '';// base_url('my-account/bank-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Business Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
            else
            {
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
                    'max_size' => 2000,
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
                    'max_size' => 2000,
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
            
        }
    }

    public function profile()
    {
        if ($this->ion_auth->logged_in()) {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $this->data['users'] = $this->ion_auth->user()->row();
            $this->data['identity_column'] = $identity_column;
            $this->data['main_page'] = 'profile';
            $this->data['title'] = 'Profile | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function orders($condition = 0)
    {
        if ($this->ion_auth->logged_in()) {
            
            $status = false;
            if($condition == 1)
            {
                $status = array('received','payment_demand','payment_ack','schedule_delivery','send_payment_confirmation',);
            }
            else if($condition == 2)
            {
                $status = array('send_invoice');
            }
            else if($condition == 3)
            {
                $status = array("complaint","complaint_msg");
            }
            else if($condition == 4)
            {
                $status = array("cancelled");
            }
            else if($condition == 5)
            {
                $status = array("delivered","send_mfg_payment_ack","send_mfg_payment_confirmation");
            }
            
            $this->data['condition'] = $condition;
            
            $this->data['main_page'] = 'orders';
            $this->data['title'] = 'Orders | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Orders, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Orders | ' . $this->data['web_settings']['meta_description'];
            
            $total = fetch_orders(false, $this->data['user']->id, $status, false, 1, NULL, NULL, NULL, NULL);
                        
            $limit = 10;
            $config['base_url'] = base_url('my-account/orders');
            $config['total_rows'] = $total['total'];
            $config['per_page'] = $limit;
            $config['num_links'] = 2;
            $config['use_page_numbers'] = TRUE;
            $config['reuse_query_string'] = TRUE;
            $config['page_query_string'] = FALSE;
            $config['uri_segment'] = 3;
            $config['attributes'] = array('class' => 'page-link');

            $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
            $config['full_tag_close'] = '</ul>';

            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_link'] = 'First';
            $config['first_tag_close'] = '</li>';

            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_link'] = 'Last';
            $config['last_tag_close'] = '</li>';

            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_link'] = '<i class="fa fa-arrow-left"></i>';
            $config['prev_tag_close'] = '</li>';

            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_link'] = '<i class="fa fa-arrow-right"></i>';
            $config['next_tag_close'] = '</li>';

            $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
            $config['cur_tag_close'] = '</a></li>';

            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';

            $page_no = (empty($this->uri->segment(3))) ? 1 : $this->uri->segment(3);
            if (!is_numeric($page_no)) {
                redirect(base_url('my-account/orders'));
            }
            $offset = ($page_no - 1) * $limit;
            $this->pagination->initialize($config);
            $this->data['links'] =  $this->pagination->create_links();
            $this->data['orders'] = fetch_orders(false, $this->data['user']->id, $status, false, $limit, $offset, 'date_added', 'DESC', NULL);
            $this->data['payment_methods'] = get_settings('payment_method', true);
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    
    public function order_row_style()
    {
        $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
    }
    
    public function get_order_list($condition = 0)
    {
        if ($this->ion_auth->logged_in()) {
            $status = array();
            if($condition == 1)
            {
                $status = array('received','payment_demand','payment_ack','schedule_delivery','send_payment_confirmation',);
            }
            else if($condition == 2)
            {
                $status = array('send_invoice');
            }
            else if($condition == 3)
            {
                $status = array("complaint","complaint_msg");
            }
            else if($condition == 4)
            {
                $status = array("cancelled");
            }
            else if($condition == 5)
            {
                $status = array("delivered","send_mfg_payment_ack","send_mfg_payment_confirmation");
            }
            return $this->order_model->get_order_list($this->data['user']->id, $status);
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }
    
    public function orderitemdetail($order_item_id = 0, $order_id=0)
    {
        if($this->ion_auth->logged_in()) {
            
            $this->data['main_page'] = 'orderitem-details';
            $this->data['title'] = 'Orders | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Orders, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Orders | ' . $this->data['web_settings']['meta_description'];
            
            $order = fetch_orders($order_id, $this->data['user']->id, false, false, 1, NULL, NULL, NULL, NULL);
            if (!isset($order['order_data']) || empty($order['order_data'])) {
                redirect(base_url('my-account/orders'));
            }        
            $this->data['order'] = $order['order_data'][0];
            $this->data['order_id'] = $order_id;
            $this->data['order_item_id'] = $order_item_id;    
            $this->load->view('front-end/' . THEME . '/template', $this->data);
            
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function order_details()
    {
        if ($this->ion_auth->logged_in()) {
            $bank_transfer = array();
            $this->data['main_page'] = 'order-details';
            $this->data['title'] = 'Orders | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Orders, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Orders | ' . $this->data['web_settings']['meta_description'];
            $order_id = $this->uri->segment(3);
            $order = fetch_orders($order_id, $this->data['user']->id, false, false, 1, NULL, NULL, NULL, NULL);            
            if (!isset($order['order_data']) || empty($order['order_data'])) {
                redirect(base_url('my-account/orders'));
            }
            $this->data['order'] = $order['order_data'][0];
            if($order['order_data'][0]['payment_method'] == "Bank Transfer"){
                $bank_transfer = fetch_details(['order_id' => $order['order_data'][0]['id']], 'order_bank_transfer');
            }
            $this->data['bank_transfer'] = $bank_transfer; 
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function order_invoice($order_id)
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = VIEW . 'api-order-invoice';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Invoice Management |' . $settings['app_name'];
            $this->data['meta_description'] = 'Invoice Management | ' . $this->data['web_settings']['meta_description'];;
            if (isset($order_id) && !empty($order_id)) {
                $res = $this->order_model->get_order_details(['o.id' => $order_id], true);
                if (!empty($res)) {
                    $items = [];
                    $promo_code = [];
                    if (!empty($res[0]['promo_code'])) {
                        $promo_code = fetch_details(['promo_code' => trim($res[0]['promo_code'])], 'promo_codes');
                    }
                    foreach ($res as $row) {
                        $row = output_escaping($row);
                        $temp['product_id'] = $row['product_id'];
                        $temp['product_variant_id'] = $row['product_variant_id'];
                        $temp['pname'] = $row['pname'];
                        $temp['quantity'] = $row['quantity'];
                        $temp['discounted_price'] = $row['discounted_price'];
                        $temp['tax_percent'] = $row['tax_percent'];
                        $temp['tax_amount'] = $row['tax_amount'];
                        $temp['price'] = $row['price'];
                        $temp['delivery_boy'] = $row['delivery_boy'];
                        $temp['active_status'] = $row['oi_active_status'];
                        array_push($items, $temp);
                    }
                    $this->data['order_detls'] = $res;
                    $this->data['items'] = $items;
                    $this->data['promo_code'] = $promo_code;
                    $this->data['print_btn_enabled'] = true;
                    $this->data['settings'] = get_settings('system_settings', true);
                    $this->load->view('admin/invoice-template', $this->data);
                } else {
                    redirect(base_url(), 'refresh');
                }
            } else {
                redirect(base_url(), 'refresh');
            }
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function update_order_item_status()
    {
        $this->form_validation->set_rules('order_item_id', 'Order item id', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean|in_list[cancelled,returned]');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $this->response = $this->order_model->update_order_item($_POST['order_item_id'], trim($_POST['status']));
            if (trim($_POST['status']) != 'returned' && $this->response['error']==false) {
                process_refund($_POST['order_item_id'], trim($_POST['status']),'order_items');
            }
            if ($this->response['error'] == false && trim($_POST['status']) == 'cancelled') {
                $data = fetch_details(['id' => $_POST['order_item_id']], 'order_items', 'product_variant_id,quantity');
                update_stock($data[0]['product_variant_id'], $data[0]['quantity'], 'plus');
            }
        }
        print_r(json_encode($this->response));
    }

    public function update_order()
    {
        $this->form_validation->set_rules('order_id', 'Order id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean|in_list[cancelled,returned]');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = validation_errors();
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        } else {
            $res = validate_order_status($_POST['order_id'], $_POST['status'], 'orders');
            if ($res['error']) {
                $this->response['error'] = (isset($res['return_request_flag'])) ? false : true;
                $this->response['message'] = $res['message'];
                $this->response['data'] = $res['data'];
                print_r(json_encode($this->response));
                return false;
            }
            if ($this->order_model->update_order(['status' => $_POST['status']], ['id' => $_POST['order_id']], true)) {
                $this->order_model->update_order(['active_status' => $_POST['status']], ['id' => $_POST['order_id']], false);
                if ($this->order_model->update_order(['status' => $_POST['status']], ['order_id' => $_POST['order_id']], true, 'order_items')) {
                    $this->order_model->update_order(['active_status' => $_POST['status']], ['order_id' => $_POST['order_id']], false, 'order_items');
                    process_refund($_POST['order_id'], $_POST['status'], 'orders');
                    if (trim($_POST['status'] == 'cancelled')) {
                        $data = fetch_details(['order_id' => $_POST['order_id']], 'order_items', 'product_variant_id,quantity');
                        $product_variant_ids = [];
                        $qtns = [];
                        foreach ($data as $d) {
                            array_push($product_variant_ids, $d['product_variant_id']);
                            array_push($qtns, $d['quantity']);
                        }

                        update_stock($product_variant_ids, $qtns, 'plus');
                    }
                    $this->response['error'] = false;
                    $this->response['message'] = 'Order Updated Successfully';
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
            }
        }
    }

    public function notifications()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'notifications';
            $this->data['title'] = 'Notification | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Notification, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Notification | ' . $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function manage_address()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'address';
            $this->data['title'] = 'Address | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Address, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Address | ' . $this->data['web_settings']['meta_description'];
            $this->data['cities'] = get_cities();
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function wallet()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'wallet';
            $this->data['title'] = 'Wallet | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Wallet, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Wallet | ' . $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function transactions()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'transactions';
            $this->data['title'] = 'Transactions | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Transactions, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Transactions | ' . $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function add_address()
    {
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('type', 'Type', 'trim|xss_clean');
            $this->form_validation->set_rules('country_code', 'Country Code', 'trim|xss_clean');
            $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|numeric|xss_clean|required');
            $this->form_validation->set_rules('alternate_mobile', 'Alternative Mobile', 'trim|numeric|xss_clean');
            $this->form_validation->set_rules('address', 'Address', 'trim|xss_clean|required');
            $this->form_validation->set_rules('landmark', 'Landmark', 'trim|xss_clean');
            $this->form_validation->set_rules('area_id', 'Area', 'trim|xss_clean|required');
            $this->form_validation->set_rules('city_id', 'City', 'trim|xss_clean|required');
            $this->form_validation->set_rules('pincode', 'Pincode', 'trim|numeric|xss_clean|required');
            $this->form_validation->set_rules('state', 'State', 'trim|xss_clean|required');
            $this->form_validation->set_rules('country', 'Country', 'trim|xss_clean|required');
            $this->form_validation->set_rules('latitude', 'Latitude', 'trim|xss_clean');
            $this->form_validation->set_rules('longitude', 'Longitude', 'trim|xss_clean');

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }

            $arr = $this->input->post(null, true);
            $arr['user_id'] = $this->data['user']->id;
            $this->address_model->set_address($arr);
            $res = $this->address_model->get_address($this->data['user']->id, false, true);
            $this->response['error'] = false;
            $this->response['message'] = 'Address Added Successfully';
            $this->response['data'] = $res;
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function edit_address()
    {
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('id', 'Id', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('type', 'Type', 'trim|xss_clean');
            $this->form_validation->set_rules('country_code', 'Country Code', 'trim|xss_clean');
            $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|numeric|xss_clean');
            $this->form_validation->set_rules('alternate_mobile', 'Alternative Mobile', 'trim|numeric|xss_clean');
            $this->form_validation->set_rules('address', 'Address', 'trim|xss_clean');
            $this->form_validation->set_rules('landmark', 'Landmark', 'trim|xss_clean');
            $this->form_validation->set_rules('area_id', 'Area', 'trim|xss_clean');
            $this->form_validation->set_rules('city_id', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('pincode', 'Pincode', 'trim|numeric|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|xss_clean');
            $this->form_validation->set_rules('country', 'Country', 'trim|xss_clean');

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            $this->address_model->set_address($_POST);
            $res = $this->address_model->get_address(null, $_POST['id'], true);
            $this->response['error'] = false;
            $this->response['message'] = 'Address updated Successfully';
            $this->response['data'] = $res;
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    //delete_address
    public function delete_address()
    {
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('id', 'Id', 'trim|required|numeric|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            $this->address_model->delete_address($_POST);
            $this->response['error'] = false;
            $this->response['message'] = 'Address Deleted Successfully';
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    //set default_address
    public function set_default_address()
    {
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('id', 'Id', 'trim|required|numeric|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            $_POST['is_default'] = true;
            $this->address_model->set_address($_POST);
            $this->response['error'] = false;
            $this->response['message'] = 'Set as default successfully';
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    //get_address
    public function get_address()
    {
        if ($this->ion_auth->logged_in()) {
            $res = $this->address_model->get_address($this->data['user']->id);
            $is_default_counter = array_count_values(array_column($res, 'is_default'));

            // if (!isset($is_default_counter['1']) && !empty($res)) {
            //     update_details(['is_default' => '1'], ['id' => $res[0]['id']], 'addresses');
            //     $res = $this->address_model->get_address($this->data['user']->id);
            // }
            if (!empty($res)) {
                $this->response['error'] = false;
                $this->response['message'] = 'Address Retrieved Successfully';
                $this->response['data'] = $res;
            } else {
                $this->response['error'] = true;
                $this->response['message'] = "No Details Found !";
                $this->response['data'] = array();
            }
            print_r(json_encode($this->response));
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function get_address_list()
    {
        if ($this->ion_auth->logged_in()) {
            return $this->address_model->get_address_list($this->data['user']->id);
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function get_areas()
    {
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('city_id', 'City Id', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
                return false;
            }

            $city_id = $this->input->post('city_id', true);
            $areas = fetch_details(['city_id' => $city_id], 'areas');
            if (empty($areas)) {
                $this->response['error'] = true;
                $this->response['message'] = "No Areas found for this City.";
                print_r(json_encode($this->response));
                return false;
            }
            $this->response['error'] = false;
            $this->response['data'] = $areas;
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function favorites()
    {
        if ($this->data['is_logged_in']) {
            $this->data['main_page'] = 'favorites';
            $this->data['title'] = 'Dashboard | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Dashboard, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Dashboard | ' . $this->data['web_settings']['meta_description'];
            $this->data['products'] = get_favorites($this->data['user']->id);
            $this->data['settings'] = get_settings('system_settings', true);
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function manage_favorites()
    {
        if ($this->data['is_logged_in']) {
            $this->form_validation->set_rules('product_id', 'Product Id', 'trim|numeric|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                $this->response['data'] = array();
            } else {
                $data = [
                    'user_id' => $this->data['user']->id,
                    'product_id' => $this->input->post('product_id', true),
                ];
                if (is_exist($data, 'favorites')) {
                    $this->db->delete('favorites', $data);
                    $this->response['error']   = false;
                    $this->response['message'] = "Product removed from favorite !";
                    print_r(json_encode($this->response));
                    return false;
                }
                $data = escape_array($data);
                $this->db->insert('favorites', $data);
                $this->response['error'] = false;
                $this->response['message'] = 'Product Added to favorite';
                print_r(json_encode($this->response));
                return false;
            }
        } else {
            $this->response['error'] = true;
            $this->response['message'] = "Login First to Add Products in Favorite List.";
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function get_transactions()
    {
        if ($this->ion_auth->logged_in()) {
            return $this->Transaction_model->get_transactions_list($this->data['user']->id);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function get_wallet_transactions()
    {
        if ($this->ion_auth->logged_in()) {
            return $this->Transaction_model->get_transactions_list($this->data['user']->id);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function get_zipcode()
    {
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('area_id', 'Area Id', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
                return false;
            }

            $area_id = $this->input->post('area_id', true);
            $areas = fetch_details(['id' => $area_id], 'areas','zipcode_id');
            $zipcodes = fetch_details(['id' => $areas[0]['zipcode_id']], 'zipcodes','zipcode');
            if (empty($areas)) {
                $this->response['error'] = true;
                $this->response['message'] = "No Zipcodes found for this area.";
                print_r(json_encode($this->response));
                return false;
            }
            $this->response['error'] = false;
            $this->response['data'] = $zipcodes;
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }
    
    function send_complaint()
    {
        if($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('order_id', 'order_id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('concern', 'Concern', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $order_id   = $this->input->post('order_id', true);
                $concern    = $this->input->post('concern', true);
    
                $order = fetch_details(['id' => $order_id], 'orders', 'id');
                
                if (empty($order)) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Order not found!";
                    $this->response['data'] = [];
                    print_r(json_encode($this->response));
                    return false;
                }
                
                if (!file_exists(FCPATH . COMPLAINT_IMG_PATH)) {
                    mkdir(FCPATH . COMPLAINT_IMG_PATH, 0777);
                }
    
                $temp_array = array();
                $files = $_FILES;
                $images_new_name_arr = array();
                $images_info_error = "";
                $allowed_media_types = implode('|', allowed_media_types());
                $config = [
                    'upload_path' =>  FCPATH . COMPLAINT_IMG_PATH,
                    'allowed_types' => $allowed_media_types,
                    'max_size' => 8000,
                ];
    
    
                if (!empty($_FILES['attachments']['name'][0]) && isset($_FILES['attachments']['name'])) {
                    $other_image_cnt = count($_FILES['attachments']['name']);
                    $other_img = $this->upload;
                    $other_img->initialize($config);
    
                    for ($i = 0; $i < $other_image_cnt; $i++) {
    
                        if (!empty($_FILES['attachments']['name'][$i])) {
    
                            $_FILES['temp_image']['name'] = $files['attachments']['name'][$i];
                            $_FILES['temp_image']['type'] = $files['attachments']['type'][$i];
                            $_FILES['temp_image']['tmp_name'] = $files['attachments']['tmp_name'][$i];
                            $_FILES['temp_image']['error'] = $files['attachments']['error'][$i];
                            $_FILES['temp_image']['size'] = $files['attachments']['size'][$i];
                            if (!$other_img->do_upload('temp_image')) {
                                $images_info_error = 'attachments :' . $images_info_error . ' ' . $other_img->display_errors();
                            } else {
                                $temp_array = $other_img->data();
                                resize_review_images($temp_array, FCPATH . COMPLAINT_IMG_PATH);
                                $images_new_name_arr[$i] = COMPLAINT_IMG_PATH . $temp_array['file_name'];
                            }
                        } else {
                            $_FILES['temp_image']['name'] = $files['attachments']['name'][$i];
                            $_FILES['temp_image']['type'] = $files['attachments']['type'][$i];
                            $_FILES['temp_image']['tmp_name'] = $files['attachments']['tmp_name'][$i];
                            $_FILES['temp_image']['error'] = $files['attachments']['error'][$i];
                            $_FILES['temp_image']['size'] = $files['attachments']['size'][$i];
                            if (!$other_img->do_upload('temp_image')) {
                                $images_info_error = $other_img->display_errors();
                            }
                        }
                    }
                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error != NULL || !$this->form_validation->run()) {
                        if (isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr as $key => $val) {
                                unlink(FCPATH . COMPLAINT_IMG_PATH . $images_new_name_arr[$key]);
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
                
                $data = array(
                    'order_id'      => $order_id,
                    'concern'       => $concern,
                    'attachments'   => $images_new_name_arr,
                );
                
                $this->load->model('Order_model');
                if($this->Order_model->add_complaint($data)) {
                    
                    if($order_item_id)
                    {
                        $order_item_info = $this->db->get_where('order_items', array('id' => $order_item_id))->row_array();
                        
                        $status = json_decode(stripallslashes($order_item_info['status']));
                        array_push($status,array('complaint',date('d-m-Y h:i:sa')));
                        
                        $order_item_up = ['active_status' =>'complaint','status'=>json_encode($status)];
                        $order_item_up = escape_array($order_item_up);
                        $this->db->set($order_item_up)->where('id', $order_item_id)->update('order_items');
                    }
                    else
                    {
                        $this->db->select('a.id, a.status, a.active_status');
                        $this->db->from('order_items as a');
                        $this->db->where('a.order_id', $order_id);
                        $this->db->where_not_in('a.active_status', array('delivered','cancelled'));
                        $query = $this->db->get();
                        $order_items_info = $query->result_array(); 
                        
                        if($order_items_info)
                        {
                            foreach($order_items_info as $order_item_info)
                            {
                                $status = json_decode(stripallslashes($order_item_info['status']));
                                if($status!=null)
                                {
                                    array_push($status, array('complaint', date('d-m-Y h:i:sa')));
                                }
                                else
                                {
                                    $status =  array(array('complaint', date("d-m-Y h:i:sa")));
                                }
                                
                                $update_item_data = array('active_status'=>'complaint','status'=>json_encode($status));
                                update_details($update_item_data,['id'=>$order_item_info['id']],'order_items');
                                     
                            }
                        }
                    }
                    
                    $this->db->update('orders', array('order_status' => 'complaint'), array('id' => $order_id));
    
                    // $result = fetch_details(['order_id' => $order_id], 'order_bank_transfer');
                    /* Send notification */
                    /*$settings = get_settings('system_settings', true);
                    $app_name = isset($settings['app_name']) && !empty($settings['app_name']) ? $settings['app_name'] : '';
                    $user_roles = fetch_details("", "user_permissions", '*', '',  '', '', '');
                    foreach ($user_roles as $user) {
                        $user_res = fetch_details(['id' => $user['user_id']], 'users', 'fcm_id');
                        $fcm_ids[0][] = $user_res[0]['fcm_id'];
                    }
                    if (!empty($fcm_ids)) {
                        $fcmMsg = array(
                            'title' => "You have new order item payment acknowledgement",
                            'body' => 'Hello Dear Admin you have new order item payment acknowledgement. Order ID #' . $order_id . ' AND Order Item ID #'.$order_item_id.' please take note of it! Thank you. Regards ' . $app_name . '',
                            'type' => "payment_ack",
                        );
                        send_notification($fcmMsg, $fcm_ids);
                    }*/
                    $this->response['error'] = false;
                    $this->response['message'] =  'Your Concern Added Successfully!';
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = (!empty($data)) ? $data : [];
                    print_r(json_encode($this->response));
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] =  'Your Concern Was Not Added';
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = (!empty($this->response['data'])) ? $this->response['data'] : [];
                    print_r(json_encode($this->response));
                }
                
            }
        }
        else 
        {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }
    
    function send_order_status()
    {
        if($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('order_id', 'order_id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $order_id   = $this->input->post('order_id', true);
                $status     = $this->input->post('status', true);
                $order      = fetch_details(['id' => $order_id], 'orders', 'id');
                
                if(empty($order)) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Order not found!";
                    $this->response['data'] = [];
                    print_r(json_encode($this->response));
                    return false;
                }
                
                $this->db->update('orders', array('order_status' => $status,'last_updated'=>date('Y-m-d H:i:s')), array('id' => $order_id));
                
                $order_item_stages = array('order_id' => $order_id,'status' => $status,);
                $this->db->insert('order_item_stages', $order_item_stages);
                
                $this->db->select('a.id, a.status, a.active_status');
                $this->db->from('order_items as a');
                $this->db->where('a.order_id', $order_id);
                $this->db->where_not_in('a.active_status', array('delivered','cancelled'));
                $query = $this->db->get();
                $order_items_info = $query->result_array(); 
                
                if($order_items_info)
                {
                    foreach($order_items_info as $order_item_info)
                    {
                        $_status = json_decode(stripallslashes($order_item_info['status']));
                        if($_status!=null)
                        {

                            array_push($_status, array($status, date('d-m-Y h:i:sa')));
                        }
                        else
                        {
                            $_status =  array(array($status, date("d-m-Y h:i:sa")));
                        }
                        
                        $update_item_data = array('active_status'=>$status,'status'=>json_encode($_status));
                        update_details($update_item_data,['id'=>$order_item_info['id']],'order_items');
                             
                    }
                }
                                
                $this->response['redirect_to'] = '';// base_url('my-account/bank-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Thanks for the update';
                $this->response['html']    = $status;
                $this->response['order_id']= $order_id;
                echo json_encode($this->response);
                return false;
            }
        }
        else 
        {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }
    
    function approveBulkQty()
    {
        if($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('order_id', 'order_id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('order_stage_id', 'order stage id', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $order_id       = $this->input->post('order_id', true);
                $order_stage_id  = $this->input->post('order_stage_id', true);
                
                $order_stage_info = $this->db->get_where('order_item_stages', array('id'=>$order_stage_id))->row_array();
                $order_item_ids   = explode(",",$order_stage_info['ids']);
                
                if($order_item_ids)
                {
                    foreach($order_item_ids as $order_item_id)
                    {
                        $order_item_info = $this->db->get_where('order_items', array('id' => $order_item_id))->row_array();
                        
                        $status = json_decode(stripallslashes($order_item_info['status']));
                        
                        if($status!=null)
                        {
                            array_push($status,array('qty_approved',date('d-m-Y h:i:sa')));
                        }
                        else
                        {
                            $status =  array(array('qty_approved', date("d-m-Y h:i:sa")));
                        }
                        
                        $order_item = ['active_status' =>'qty_approved','status'=>json_encode($status)];
                        $order_item = escape_array($order_item);
                        $this->db->set($order_item)->where('id', $order_item_id)->update('order_items');
                    }
                    
                    $order_item_stages = array('order_id' => $order_id,'order_item_id'=>0,'ids'=>implode(',',$order_item_ids),'status' => 'qty_approved',);
                    $this->db->insert('order_item_stages', $order_item_stages);
                    
                    $update_data = array('order_status'=>'qty_approved');
                    update_details($update_data,['id'=>$order_id],'orders');
                    
                    /*
                    $this->db->select('a.id, a.status, a.active_status');
                    $this->db->from('order_items as a');
                    $this->db->where('a.order_id', $order_id);
                    $this->db->where_not_in('a.active_status', array('delivered','cancelled'));
                    $query = $this->db->get();
                    $order_items_info = $query->result_array(); 
                    
                    if($order_items_info)
                    {
                        foreach($order_items_info as $order_item_info)
                        {
                            $status = json_decode(stripallslashes($order_item_info['status']));
                            if($status!=null)
                            {
                                array_push($status, array('payment_demand', date('d-m-Y h:i:sa')));
                            }
                            else
                            {
                                $status =  array(array('payment_demand', date("d-m-Y h:i:sa")));
                            }
                            
                            $update_item_data = array('active_status'=>'payment_demand','status'=>json_encode($status));
                            update_details($update_item_data,['id'=>$order_item_info['id']],'order_items');
                                 
                        }
                    }
                    
                    $this->db->update('orders', array('order_status' => 'payment_demand'), array('id' => $order_id));
                    
                    $order_item_stages = array('order_id' => $order_id,'status' => 'payment_demand',);
                    $this->db->insert('order_item_stages', $order_item_stages);
                    
                    */
                    
                    $this->response['redirect_to'] = '';// base_url('my-account/bank-details/'.$is_seller);
                    $this->response['error'] = false;
                    $this->response['message'] = 'Thanks for approval';
                    $this->response['html']    = 'qty_approved';
                    //$this->response['order_item_id']= $order_item_id;
                    echo json_encode($this->response);
                    return false;
                }
                
                
            }
        }
        else 
        {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
        
    }
    
    function approveQty()
    {
        if($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('order_id', 'order_id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('order_item_id', 'order item id', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $order_id       = $this->input->post('order_id', true);
                $order_item_id  = $this->input->post('order_item_id', true);
                
                $order_item_info = $this->db->get_where('order_items', array('id' => $order_item_id))->row_array();
                
                $status = json_decode(stripallslashes($order_item_info['status']));
                
                if($status!=null)
                {
                    array_push($status,array('qty_approved',date('d-m-Y h:i:sa')));
                }
                else
                {
                    $status =  array(array('qty_approved', date("d-m-Y h:i:sa")));
                }
                
                $order_item = ['active_status' =>'qty_approved','status'=>json_encode($status)];
                $order_item = escape_array($order_item);
                $this->db->set($order_item)->where('id', $order_item_id)->update('order_items');
                
                $order_item_stages = array('order_id' => $order_id,'order_item_id'=>$order_item_id,'status' => 'qty_approved',);
                $this->db->insert('order_item_stages', $order_item_stages);
                
                $update_data = array('order_status'=>'qty_approved');
                update_details($update_data,['id'=>$order_id],'orders');
                
                $this->response['redirect_to'] = '';// base_url('my-account/bank-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Thanks for approval';
                $this->response['html']    = 'qty_approved';
                $this->response['order_item_id']= $order_item_id;
                echo json_encode($this->response);
                return false;
                
                
            }
            
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }
    
    public function rejectBulkQty()
    {
        if($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('order_id', 'order_id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('order_stage_id', 'order stage id', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $order_id       = $this->input->post('order_id', true);
                $order_stage_id = $this->input->post('order_stage_id', true);
                
                $order_stage_info = $this->db->get_where('order_item_stages', array('id'=>$order_stage_id))->row_array();
                //$order_item_ids   = explode(",",$order_stage_info['ids']);
                
                $order_items = $this->db->get_where('order_items',array('order_id'=>$order_id))->result_array();
                $order_item_ids = array();
                if($order_items)
                {
                    foreach($order_items as $order_item)
                    {
                        $order_item_ids[] = $order_item['id'];
                    }
                }
                
                if($order_item_ids)
                {
                    foreach($order_item_ids as $order_item_id)
                    {
                        $order_item_info = $this->db->get_where('order_items', array('id' => $order_item_id))->row_array();
                
                        $status = json_decode(stripallslashes($order_item_info['status']));
                        if($status!=null)
                        {
                            array_push($status,array('cancelled',date('d-m-Y h:i:sa')));
                        }
                        else
                        {
                            $status =  array(array('cancelled', date("d-m-Y h:i:sa")));
                        }
                        
                        $order_item = ['active_status' =>'cancelled','status'=>json_encode($status)];
                        $order_item = escape_array($order_item);
                        $this->db->set($order_item)->where('id', $order_item_id)->update('order_items');
                    }
                    
                    $order_item_stages = array('order_id' => $order_id,'order_item_id'=>0,'ids'=>implode(',',$order_item_ids),'status' => 'qty_rejected',);
                    $this->db->insert('order_item_stages', $order_item_stages);
                    
                    $this->db->select('a.id');
                    $this->db->from('order_items as a');
                    $this->db->where('a.active_status !=', 'cancelled');
                    //$this->db->where('a.id !=', $order_item_id);
                    $this->db->where('a.order_id', $order_id);
                    $query      = $this->db->get();
                    $results    = $query->result_array(); 
                    
                    if(count($results)<=0)
                    {
                        $update_data = array('order_status'=>'cancelled');
                        update_details($update_data,['id'=>$order_id],'orders');
                    }       
                       
                    $this->response['redirect_to']  = '';// base_url('my-account/bank-details/'.$is_seller);
                    $this->response['error']        = false;
                    $this->response['message']      = 'Thanks for the update';
                    $this->response['html']         = 'qty_rejected';
                    //$this->response['order_item_id']= $order_item_id;
                    echo json_encode($this->response);
                    return false;
                }
            }
        }
        else 
        {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }
    
    public function rejectQty()
    {
        if($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('order_id', 'order_id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('order_item_id', 'order item id', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $order_id       = $this->input->post('order_id', true);
                $order_item_id  = $this->input->post('order_item_id', true);
                
                $order_item_info = $this->db->get_where('order_items', array('id' => $order_item_id))->row_array();
                
                $status = json_decode(stripallslashes($order_item_info['status']));
                if($status!=null)
                {
                    array_push($status,array('cancelled',date('d-m-Y h:i:sa')));
                }
                else
                {
                    $status =  array(array('cancelled', date("d-m-Y h:i:sa")));
                }
                
                $order_item = ['active_status' =>'cancelled','status'=>json_encode($status)];
                $order_item = escape_array($order_item);
                $this->db->set($order_item)->where('id', $order_item_id)->update('order_items');
                
                $order_item_stages = array('order_id' => $order_id,'order_item_id'=>$order_item_id,'status' => 'qty_rejected',);
                $this->db->insert('order_item_stages', $order_item_stages);
                
                $this->db->select('a.id');
                $this->db->from('order_items as a');
                $this->db->where('a.active_status !=', 'cancelled');
                $this->db->where('a.id !=', $order_item_id);
                $this->db->where('a.order_id', $order_id);
                $query      = $this->db->get();
                $results    = $query->result_array(); 
                
                if(count($results)<=0)
                {
                    $update_data = array('order_status'=>'cancelled','last_updated'=>date('Y-m-d H:i:s'));
                    update_details($update_data,['id'=>$order_id],'orders');
                }       
                   
                $this->response['redirect_to']  = '';// base_url('my-account/bank-details/'.$is_seller);
                $this->response['error']        = false;
                $this->response['message']      = 'Thanks for the update';
                $this->response['html']         = 'qty_rejected';
                $this->response['order_item_id']= $order_item_id;
                echo json_encode($this->response);
                return false;
            }
            
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }
    
    public function send_payment_receipt()
    {
        $this->form_validation->set_rules('order_id', 'Order Id', 'trim|required|numeric|xss_clean');
        //$this->form_validation->set_rules('order_item_id', 'Order Item Id', 'trim|required|numeric|xss_clean');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $order_id = $this->input->post('order_id', true);
            $order_item_id = $this->input->post('order_item_id', true);

            $order = fetch_details(['id' => $order_id], 'orders', 'id');
            $order_item = fetch_details(['id' => $order_item_id], 'order_items', 'id');
            
            if (empty($order)) {
                $this->response['error'] = true;
                $this->response['message'] = "Order not found!";
                $this->response['data'] = [];
                print_r(json_encode($this->response));
                return false;
            }
            
            /*if (empty($order_item)) {
                $this->response['error'] = true;
                $this->response['message'] = "Order Item not found!";
                $this->response['data'] = [];
                print_r(json_encode($this->response));
                return false;
            }*/
            
            if (!file_exists(FCPATH . PAYMENT_DEMAND_IMG_PATH)) {
                mkdir(FCPATH . PAYMENT_DEMAND_IMG_PATH, 0777);
            }

            $temp_array = array();
            $files = $_FILES;
            $images_new_name_arr = array();
            $images_info_error = "";
            $allowed_media_types = implode('|', allowed_media_types());
            $config = [
                'upload_path' =>  FCPATH . PAYMENT_DEMAND_IMG_PATH,
                'allowed_types' => $allowed_media_types,
                'max_size' => 8000,
            ];


            if (!empty($_FILES['attachments']['name'][0]) && isset($_FILES['attachments']['name'])) {
                $other_image_cnt = count($_FILES['attachments']['name']);
                $other_img = $this->upload;
                $other_img->initialize($config);

                for ($i = 0; $i < $other_image_cnt; $i++) {

                    if (!empty($_FILES['attachments']['name'][$i])) {

                        $_FILES['temp_image']['name'] = $files['attachments']['name'][$i];
                        $_FILES['temp_image']['type'] = $files['attachments']['type'][$i];
                        $_FILES['temp_image']['tmp_name'] = $files['attachments']['tmp_name'][$i];
                        $_FILES['temp_image']['error'] = $files['attachments']['error'][$i];
                        $_FILES['temp_image']['size'] = $files['attachments']['size'][$i];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = 'attachments :' . $images_info_error . ' ' . $other_img->display_errors();
                        } else {
                            $temp_array = $other_img->data();
                            resize_review_images($temp_array, FCPATH . PAYMENT_DEMAND_IMG_PATH);
                            $images_new_name_arr[$i] = PAYMENT_DEMAND_IMG_PATH . $temp_array['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files['attachments']['name'][$i];
                        $_FILES['temp_image']['type'] = $files['attachments']['type'][$i];
                        $_FILES['temp_image']['tmp_name'] = $files['attachments']['tmp_name'][$i];
                        $_FILES['temp_image']['error'] = $files['attachments']['error'][$i];
                        $_FILES['temp_image']['size'] = $files['attachments']['size'][$i];
                        if (!$other_img->do_upload('temp_image')) {
                            $images_info_error = $other_img->display_errors();
                        }
                    }
                }
                //Deleting Uploaded attachments if any overall error occured
                if ($images_info_error != NULL || !$this->form_validation->run()) {
                    if (isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
                        foreach ($images_new_name_arr as $key => $val) {
                            unlink(FCPATH . PAYMENT_DEMAND_IMG_PATH . $images_new_name_arr[$key]);
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
            $data = array(
                'order_id'      => $order_id,
                'order_item_id' => $order_item_id,
                'attachments'   => $images_new_name_arr,
            );
            
            $this->load->model('Order_model');
            if($this->Order_model->add_payment_demand($data)) {
                
                if($order_item_id)
                {
                    $order_item_info = $this->db->get_where('order_items', array('id' => $order_item_id))->row_array();
                    
                    $status = json_decode(stripallslashes($order_item_info['status']));
                    array_push($status,array('payment_ack',date('d-m-Y h:i:sa')));
                    
                    $order_item_up = ['active_status' =>'payment_ack','status'=>json_encode($status)];
                    $order_item_up = escape_array($order_item_up);
                    $this->db->set($order_item_up)->where('id', $order_item_id)->update('order_items');
                }
                else
                {
                    $this->db->select('a.id, a.status, a.active_status');
                    $this->db->from('order_items as a');
                    $this->db->where('a.order_id', $order_id);
                    $this->db->where_not_in('a.active_status', array('delivered','cancelled'));
                    $query = $this->db->get();
                    $order_items_info = $query->result_array(); 
                    
                    if($order_items_info)
                    {
                        foreach($order_items_info as $order_item_info)
                        {
                            $status = json_decode(stripallslashes($order_item_info['status']));
                            if($status!=null)
                            {
                                array_push($status, array('payment_ack', date('d-m-Y h:i:sa')));
                            }
                            else
                            {
                                $status =  array(array('payment_ack', date("d-m-Y h:i:sa")));
                            }
                            
                            $update_item_data = array('active_status'=>'payment_ack','status'=>json_encode($status));
                            update_details($update_item_data,['id'=>$order_item_info['id']],'order_items');
                                 
                        }
                    }
                }
                
                $this->db->update('orders', array('order_status' => 'payment_ack'), array('id' => $order_id));

                // $result = fetch_details(['order_id' => $order_id], 'order_bank_transfer');
                /* Send notification */
                /*$settings = get_settings('system_settings', true);
                $app_name = isset($settings['app_name']) && !empty($settings['app_name']) ? $settings['app_name'] : '';
                $user_roles = fetch_details("", "user_permissions", '*', '',  '', '', '');
                foreach ($user_roles as $user) {
                    $user_res = fetch_details(['id' => $user['user_id']], 'users', 'fcm_id');
                    $fcm_ids[0][] = $user_res[0]['fcm_id'];
                }
                if (!empty($fcm_ids)) {
                    $fcmMsg = array(
                        'title' => "You have new order item payment acknowledgement",
                        'body' => 'Hello Dear Admin you have new order item payment acknowledgement. Order ID #' . $order_id . ' AND Order Item ID #'.$order_item_id.' please take note of it! Thank you. Regards ' . $app_name . '',
                        'type' => "payment_ack",
                    );
                    send_notification($fcmMsg, $fcm_ids);
                }*/
                $this->response['error'] = false;
                $this->response['message'] =  'Payment Acknowledgement Receipt Added Successfully!';
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = (!empty($data)) ? $data : [];
                print_r(json_encode($this->response));
            } else {
                $this->response['error'] = true;
                $this->response['message'] =  'Payment Acknowledgement Receipt Was Not Added';
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = (!empty($this->response['data'])) ? $this->response['data'] : [];
                print_r(json_encode($this->response));
            }
        }
    }
    
    public function subscriptions()
    {
        $this->data['main_page'] = 'subscriptions';
        $this->data['title'] = 'Subscriptions | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Subscriptions, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Subscriptions | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Subscriptions | ' . $this->data['web_settings']['site_title'];
        //$this->data['about_us'] = get_settings('about_us');
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }
    
    public function settings()
    {
        if ($this->ion_auth->logged_in()) {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $this->data['users'] = $this->ion_auth->user()->row();
            $this->data['identity_column'] = $identity_column;
            $this->data['main_page'] = 'settings';
            $this->data['is_seller'] = (int)$this->ion_auth->is_seller();
            $this->data['title'] = 'Settings | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Settings, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Settings | ' . $this->data['web_settings']['meta_description'];
            $this->data['meta_description'] = 'Settings | ' . $this->data['web_settings']['site_title'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    
    public function change_password()
    {
        $identity_column = $this->config->item('identity', 'ion_auth');
        $identity = $this->session->userdata('identity');
        $user = $this->ion_auth->user()->row();
            
        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');
    
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
            
            $this->response['redirect_to'] = '';
            $this->response['error'] = false;
            $this->response['message'] = 'Password Updated Succesfully';
            echo json_encode($this->response);
            return false;
        }
    }
}
