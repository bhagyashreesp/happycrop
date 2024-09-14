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
            $this->load->view('admin/template', $this->data);
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

    public function view_retailers()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            return $this->Retailer_model->get_retailers_list();
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
