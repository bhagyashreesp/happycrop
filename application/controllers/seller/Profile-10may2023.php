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
            
            $this->data['is_seller'] = (int)$this->ion_auth->is_seller();
                
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
        $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim|valid_email');
        
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
            $this->data['seller_brands'] = $this->db->get_where('seller_brands', array('user_id'=>$this->data['user_id']))->result_array();
            
            $this->data['is_seller'] = (int)$this->ion_auth->is_seller();
                
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
            
            $seller_data = ['company_name' => $this->input->post('company_name'), 'store_name' => $this->input->post('company_name'), 'slug' => create_unique_slug($this->input->post('company_name', true), 'seller_data'), 'year_establish' => $this->input->post('year_establish'), 'owner_name' => $this->input->post('owner_name'), 'logo' => $logo_doc, 'ownership_type' => $this->input->post('ownership_type'), 'annual_turnover' => $this->input->post('annual_turnover'), 'number_of_employees' => $this->input->post('number_of_employees'), 'plot_no' => $this->input->post('plot_no'), 'street_locality' => $this->input->post('street_locality'), 'landmark' => $this->input->post('landmark'), 'pin' => $this->input->post('pin'), 'city_id' => $this->input->post('city_id'), 'state_id' => $this->input->post('state_id'),'website_url'=>$this->input->post('website_url'),'google_business_url'=>$this->input->post('google_business_url'),'facebook_business_url'=>$this->input->post('facebook_business_url'),'instagram_business_url'=>$this->input->post('instagram_business_url'),'min_order_value'=>$this->input->post('min_order_value')];
            $seller_data = escape_array($seller_data);
            $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
            
            $this->response['redirect_to'] ='';
            $this->response['error'] = false;
            $this->response['message'] = 'Business Details Updated Succesfully';
            echo json_encode($this->response);
            return false;
            
        }
    }
    
    public function addBrandName()
    {
        if($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) 
        {
            $this->form_validation->set_rules('brand_name', 'Brand Name', 'required|xss_clean|trim');
            if (!$this->form_validation->run()) 
            {
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
            } 
            else 
            {
                $user_id   = $this->ion_auth->get_user_id();
            
                $_data = ['brand_name' => $this->input->post('brand_name'), 'user_id' => $user_id];
                $_data = escape_array($_data);
                $this->db->insert('seller_brands', $_data);
                
                $brands_html    = '';
                $seller_brands  = $this->db->get_where('seller_brands', array('user_id'=>$user_id))->result_array();
                
                ob_clean();
                if($seller_brands) 
                { 
                    foreach($seller_brands as $seller_brand)
                    {
                        $brands_html    .= ' 
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Brand Name</label>
                                    <input type="text" class="form-control" placeholder="Brand Name" id="brand_name_'.$seller_brand['id'].'" name="brand_name_'.$seller_brand['id'].'" value="'.$seller_brand['brand_name'].'"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group pt-2">
                                    <br />
                                    <button id="btn_brand_'.$seller_brand['id'].'" type="button" class="btn btn-primary" onclick="updateBrandName('.$seller_brand['id'].');">Update</button>
                                </div>
                            </div>
                        </div>';  
                    }
                }
                
                $this->response['brands_html'] = $brands_html;
                $this->response['error'] = false;
                $this->response['message'] = 'Brand Added Succesfully';
                echo json_encode($this->response);
                return false;
            }
        }
    }
    
    
    public function updateBrandName()
    {
        if($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) 
        {
            $this->form_validation->set_rules('id', 'ID', 'required|xss_clean|trim');
            $this->form_validation->set_rules('brand_name', 'Brand Name', 'required|xss_clean|trim');
            if (!$this->form_validation->run()) 
            {
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
            } 
            else 
            {
                $user_id   = $this->ion_auth->get_user_id();
            
                $_data = ['brand_name' => $this->input->post('brand_name'), 'user_id' => $user_id];
                $_data = escape_array($_data);
                $this->db->set($_data)->where('id', $this->input->post('id'))->update('seller_brands');
                
                $this->response['error'] = false;
                $this->response['message'] = 'Brand Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
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
            
            $this->data['is_seller'] = (int)$this->ion_auth->is_seller();
                
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/home', 'refresh');
        }
    }
    
    public function save_bank_details()
    {
        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->data['user_id']   = $this->ion_auth->get_user_id();
        
        $is_seller = $this->input->post('is_seller');
        $seller_info = array();
        if($is_seller)
        {
            $seller_info = $this->db->get_where('seller_data', array('user_id'=>$this->data['user_id']))->row_array();
        }
        
        $this->form_validation->set_rules('account_name', 'Account Holder Name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('account_number', 'Account Number', 'required|xss_clean|trim|matches[confirm_account_number]');
        $this->form_validation->set_rules('confirm_account_number', 'Confirm Account Number', 'required');
        $this->form_validation->set_rules('bank_code', 'IFSC Code', 'required|xss_clean|trim');
        $this->form_validation->set_rules('account_type', 'Account Type', 'required|xss_clean|trim');
        $this->form_validation->set_rules('bank_name', 'Bank Name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('bank_branch', 'Branch', 'required|xss_clean|trim');
        $this->form_validation->set_rules('bank_city', 'City', 'required|xss_clean|trim');
        $this->form_validation->set_rules('bank_state', 'State', 'required|xss_clean|trim');
        
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
            
            $seller_data = ['account_name' => $this->input->post('account_name'), 'account_number' => $this->input->post('account_number'), 'bank_code' => $this->input->post('bank_code'), 'account_type' => $this->input->post('account_type'), 'bank_name' => $this->input->post('bank_name'), 'bank_branch' => $this->input->post('bank_branch'), 'bank_city' => $this->input->post('bank_city'), 'bank_state' => $this->input->post('bank_state')];
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
            
            $this->data['is_seller'] = (int)$this->ion_auth->is_seller();
                
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/home', 'refresh');
        }
    }
    
    public function save_gst_details()
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
            
            $seller_data = ['have_gst_no' => $this->input->post('have_gst_no'), 'gst_no' => $this->input->post('gst_no'), 'pan_number' => $this->input->post('pan_number'), 'tan_number' => $this->input->post('tan_number'), 'cin_number' => $this->input->post('cin_number'), 'iec_number' => $this->input->post('iec_number')];
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
            
            $this->data['is_seller'] = (int)$this->ion_auth->is_seller();
                
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/home', 'refresh');
        }
    }
    
    public function save_license_details()
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
            
            $seller_data = ['have_fertilizer_license' => $this->input->post('have_fertilizer_license'), 'fertilizer_license_no' => $this->input->post('fertilizer_license_no'), 'fertilizer_license_no' => $this->input->post('fertilizer_license_no'), 'fert_lic_issue_date' => $this->input->post('fert_lic_issue_date'), 'fert_lic_expiry_date' => $this->input->post('fert_lic_expiry_date'),'have_pesticide_license_no' => $this->input->post('have_pesticide_license_no'),  'pesticide_license_no' => $this->input->post('pesticide_license_no'), 'pest_lic_issue_date' => $this->input->post('pest_lic_issue_date'), 'pest_lic_expiry_date' => $this->input->post('pest_lic_expiry_date'),  'have_seeds_license_no' => $this->input->post('have_seeds_license_no'), 'seeds_license_no' => $this->input->post('seeds_license_no'), 'seeds_lic_issue_date' => $this->input->post('seeds_lic_issue_date'), 'seeds_lic_expiry_date' => $this->input->post('seeds_lic_expiry_date'), 'is_finish' => $this->input->post('is_finish')];
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
            
            if (!file_exists(FCPATH . SELLER_OFORM_IMG_PATH)) {
                mkdir(FCPATH . SELLER_OFORM_IMG_PATH, 0777);
            }
            
            $temp_array4 = array();
            $files4 = $_FILES;
            $images_new_name_arr4 = array();
            $images_info_error4 = "";
            $allowed_media_types4 = implode('|', allowed_media_types());
            $config4 = [
                'upload_path' =>  FCPATH . SELLER_OFORM_IMG_PATH,
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
                        resize_review_images($temp_array4, FCPATH . SELLER_OFORM_IMG_PATH);
                        $images_new_name_arr4[] = SELLER_OFORM_IMG_PATH . $temp_array4['file_name'];
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
                            unlink(FCPATH . SELLER_OFORM_IMG_PATH . $images_new_name_arr4[$key]);
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
                $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
            }
            
            
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
            
            $this->data['is_seller'] = (int)$this->ion_auth->is_seller();
                
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
    
    public function about_us()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $settings = get_settings('system_settings', true);
            $user_id = $this->session->userdata('user_id');
            $this->data['identity_column'] = $identity_column;
            $this->data['main_page'] = FORMS . 'seller_about_us';
            $this->data['title'] = 'Manufacturer Profile | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Manufacturer Profile | ' . $settings['app_name'];
                
            $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->data['user_id']   = $this->ion_auth->get_user_id();
            
            $this->data['seller_data'] = $this->db->get_where('seller_data', array('user_id'=>$this->data['user_id']))->row();
            
            $this->data['is_seller'] = (int)$this->ion_auth->is_seller();
                
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/home', 'refresh');
        }
    }
    
    public function save_about_us()
    {
        $is_seller = $this->input->post('is_seller');
        
        $this->data['user']     = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->data['user_id']  = $this->ion_auth->get_user_id();
        
        $seller_info = array();
        if($is_seller)
        {
            $seller_info = $this->db->get_where('seller_data', array('user_id'=>$this->data['user_id']))->row_array();
        }
        else
        {
            $seller_info = $this->db->get_where('retailer_data', array('user_id'=>$this->data['user_id']))->row_array();
        }
        
        $seller_data = ['about_us' => $this->input->post('about_us'), 'infrastructure' => $this->input->post('infrastructure'), 'quality_compliance' => $this->input->post('quality_compliance'), 'awards_recognition' => $this->input->post('awards_recognition')];
        $seller_data = escape_array($seller_data);
        $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
        
        if (!file_exists(FCPATH . SELLER_QUALITY_COMPLIANCE_FILE_PATH)) {
            mkdir(FCPATH . SELLER_QUALITY_COMPLIANCE_FILE_PATH, 0777);
        }

        $temp_array = array();
        $files = $_FILES;
        $images_new_name_arr = array();
        $images_info_error = "";
        $allowed_media_types = implode('|', allowed_media_types());
        $config = [
            'upload_path' =>  FCPATH . SELLER_QUALITY_COMPLIANCE_FILE_PATH,
            'allowed_types' => $allowed_media_types,
            'max_size' => 8000,
        ];


        if(!empty($_FILES['quality_compliance_file']['name']) && isset($_FILES['quality_compliance_file']['name'])) 
        {
            $other_image_cnt = count($_FILES['quality_compliance_file']['name']);
            $other_img = $this->upload;
            $other_img->initialize($config);


            if (!empty($_FILES['quality_compliance_file']['name'])) {

                $_FILES['temp_image']['name'] = $files['quality_compliance_file']['name'];
                $_FILES['temp_image']['type'] = $files['quality_compliance_file']['type'];
                $_FILES['temp_image']['tmp_name'] = $files['quality_compliance_file']['tmp_name'];
                $_FILES['temp_image']['error'] = $files['quality_compliance_file']['error'];
                $_FILES['temp_image']['size'] = $files['quality_compliance_file']['size'];
                if (!$other_img->do_upload('temp_image')) {
                    $images_info_error = 'attachments :' . $images_info_error . ' ' . $other_img->display_errors();
                } else {
                    $temp_array = $other_img->data();
                    resize_review_images($temp_array, FCPATH . SELLER_QUALITY_COMPLIANCE_FILE_PATH);
                    $images_new_name_arr[] = SELLER_QUALITY_COMPLIANCE_FILE_PATH . $temp_array['file_name'];
                }
            } else {
                $_FILES['temp_image']['name'] = $files['quality_compliance_file']['name'];
                $_FILES['temp_image']['type'] = $files['quality_compliance_file']['type'];
                $_FILES['temp_image']['tmp_name'] = $files['quality_compliance_file']['tmp_name'];
                $_FILES['temp_image']['error'] = $files['quality_compliance_file']['error'];
                $_FILES['temp_image']['size'] = $files['quality_compliance_file']['size'];
                if (!$other_img->do_upload('temp_image')) {
                    $images_info_error = $other_img->display_errors();
                }
            }
            
            //Deleting Uploaded attachments if any overall error occured
            if ($images_info_error != NULL || !$this->form_validation->run()) {
                if(isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
                    foreach ($images_new_name_arr as $key => $val) {
                        unlink(FCPATH . SELLER_QUALITY_COMPLIANCE_FILE_PATH . $images_new_name_arr[$key]);
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
            $seller_data = ['quality_compliance_file'=>$images_new_name_arr[0]];
            $seller_data = escape_array($seller_data);
            $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
        }
        
        if (!file_exists(FCPATH . SELLER_AWARDS_RECOGNITION_FILE_PATH)) {
            mkdir(FCPATH . SELLER_AWARDS_RECOGNITION_FILE_PATH, 0777);
        }
        
        $temp_array2 = array();
        $files2 = $_FILES;
        $images_new_name_arr2 = array();
        $images_info_error2 = "";
        $allowed_media_types2 = implode('|', allowed_media_types());
        $config2 = [
            'upload_path' =>  FCPATH . SELLER_AWARDS_RECOGNITION_FILE_PATH,
            'allowed_types' => $allowed_media_types2,
            'max_size' => 8000,
        ];


        if (!empty($_FILES['awards_recognition_file']['name']) && isset($_FILES['awards_recognition_file']['name'])) 
        {
            $other_image_cnt2 = count($_FILES['awards_recognition_file']['name']);
            $other_img2 = $this->upload;
            $other_img2->initialize($config2);


            if (!empty($_FILES['awards_recognition_file']['name'])) {

                $_FILES['temp_image']['name'] = $files2['awards_recognition_file']['name'];
                $_FILES['temp_image']['type'] = $files2['awards_recognition_file']['type'];
                $_FILES['temp_image']['tmp_name'] = $files2['awards_recognition_file']['tmp_name'];
                $_FILES['temp_image']['error'] = $files2['awards_recognition_file']['error'];
                $_FILES['temp_image']['size'] = $files2['awards_recognition_file']['size'];
                if (!$other_img2->do_upload('temp_image')) {
                    $images_info_error2 = 'attachments :' . $images_info_error2 . ' ' . $other_img2->display_errors();
                } else {
                    $temp_array2 = $other_img2->data();
                    resize_review_images($temp_array2, FCPATH . SELLER_AWARDS_RECOGNITION_FILE_PATH);
                    $images_new_name_arr2[] = SELLER_AWARDS_RECOGNITION_FILE_PATH . $temp_array2['file_name'];
                }
            } else {
                $_FILES['temp_image']['name'] = $files2['awards_recognition_file']['name'];
                $_FILES['temp_image']['type'] = $files2['awards_recognition_file']['type'];
                $_FILES['temp_image']['tmp_name'] = $files2['awards_recognition_file']['tmp_name'];
                $_FILES['temp_image']['error'] = $files2['awards_recognition_file']['error'];
                $_FILES['temp_image']['size'] = $files2['awards_recognition_file']['size'];
                if (!$other_img2->do_upload('temp_image')) {
                    $images_info_error2 = $other_img2->display_errors();
                }
            }
            
            //Deleting Uploaded attachments if any overall error occured
            if ($images_info_error2 != NULL || !$this->form_validation->run()) {
                if(isset($images_new_name_arr2) && !empty($images_new_name_arr2 || !$this->form_validation->run())) {
                    foreach ($images_new_name_arr2 as $key => $val) {
                        unlink(FCPATH . SELLER_AWARDS_RECOGNITION_FILE_PATH . $images_new_name_arr2[$key]);
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
            $seller_data = ['awards_recognition_file'=>$images_new_name_arr2[0]];
            $seller_data = escape_array($seller_data);
            $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
        }
        
        
        $this->response['redirect_to'] = base_url('seller/profile/about_us');
        $this->response['error'] = false;
        $this->response['message'] = 'About Info Updated Succesfully';
        echo json_encode($this->response);
        return false;   
    }
    
}
