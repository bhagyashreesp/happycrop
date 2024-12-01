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
        $this->load->model(['cart_model', 'category_model', 'address_model', 'order_model', 'Transaction_model', 'Account_model']);
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
            $this->data['join_date'] = date('d/m/Y', strtotime($this->data['user']->created_at));
            $is_seller               = (int)$this->ion_auth->is_seller();

            $this->data['orders_total_amt'] = 0;
            $this->data['total_orders'] = 0;
            $this->data['in_process_orders'] = 0;
            $this->data['shipped_orders'] = 0;
            $this->data['issue_raised_orders'] = 0;
            $this->data['cancelled_orders'] = 0;
            $this->data['delivered_orders'] = 0;
            if (!$is_seller) {
                $this->data['retailer_data'] = $this->db->get_where('retailer_data', array('user_id' => $this->data['user_id']))->row_array();
                $this->data['orders_total_amt'] = retailer_orders_total($this->data['user_id']);
                $this->data['total_orders'] = retailer_orders_count("", $this->data['user_id']);
                $this->data['in_process_orders'] = retailer_orders_count(array('received', 'payment_demand', 'payment_ack', 'schedule_delivery', 'send_payment_confirmation',), $this->data['user_id']);
                $this->data['shipped_orders'] = retailer_orders_count("send_invoice", $this->data['user_id']);
                $this->data['issue_raised_orders'] = retailer_orders_count(array("complaint", "complaint_msg"), $this->data['user_id']);
                $this->data['cancelled_orders'] = retailer_orders_count("cancelled", $this->data['user_id']);
                $this->data['delivered_orders'] = retailer_orders_count(array("delivered", "send_mfg_payment_ack", "send_mfg_payment_confirmation"), $this->data['user_id']);
            }

            $this->data['sliders'] = get_sliders('', '', '', 2);

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
                'max_size' => 8000,
            ];
            if (isset($avatar_files['profile_img']) && !empty($avatar_files['profile_img']['name']) && isset($avatar_files['profile_img']['name'])) {
                $other_img = $this->upload;
                $other_img->initialize($config);

                if (isset($avatar_files['profile_img']) && !empty($avatar_files['profile_img']['name']) && isset($_POST['old_profile_img']) && !empty($_POST['old_profile_img'])) {
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
            } else {
                $avatar_doc = $this->input->post('old_profile_img', true);
            }

            $designation = $this->input->post('designation', true);
            $alternate_mobile = $this->input->post('alternate_mobile', true);
            $alternate_email = $this->input->post('alternate_email', true);
            $landline        = $this->input->post('landline', true);
            $alternate_landline = $this->input->post('alternate_landline', true);

            $user_details = ['username' => $this->input->post('username'), 'email' => $this->input->post('email'), 'profile_img' => (!empty($avatar_doc)) ? $avatar_doc : null, 'designation' => $designation, 'alternate_mobile' => $alternate_mobile, 'alternate_email' => $alternate_email, 'landline' => $landline, 'alternate_landline' => $alternate_landline];
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
        if (!$this->ion_auth->logged_in()) {
            redirect(base_url());
        } else {
            $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->data['user_id']   = $this->ion_auth->get_user_id();

            if ($is_seller) {
                $this->data['seller_data'] = $this->db->get_where('seller_data', array('user_id' => $this->data['user_id']))->row();
            } else {
                $this->data['seller_data'] = $this->db->get_where('retailer_data', array('user_id' => $this->data['user_id']))->row();
            }

            $this->data['is_seller'] = $is_seller;
            $this->data['main_page'] = 'business_details';
            $this->data['title']     = 'Business Details | ' . $this->data['web_settings']['site_title'];

            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
    }

    public function save_step4()
    {
        if (!$this->ion_auth->logged_in()) {
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

            if ($is_seller) {
                $seller_data = ['company_name' => $this->input->post('company_name'), 'store_name' => $this->input->post('company_name'), 'slug' => create_unique_slug($this->input->post('company_name', true), 'seller_data'), 'brand_name_1' => $this->input->post('brand_name_1'), 'brand_name_2' => $this->input->post('brand_name_2'), 'brand_name_3' => $this->input->post('brand_name_3'), 'plot_no' => $this->input->post('plot_no'), 'street_locality' => $this->input->post('street_locality'), 'landmark' => $this->input->post('landmark'), 'pin' => $this->input->post('pin'), 'city' => $this->input->post('city'), 'state' => $this->input->post('state')];
                $seller_data = escape_array($seller_data);
                $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');

                $this->response['redirect_to'] = ''; // base_url('my-account/bank-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Business Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
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
                if (isset($logo_files['logo']) && !empty($logo_files['logo']['name']) && isset($logo_files['logo']['name'])) {
                    $other_img = $this->upload;
                    $other_img->initialize($config);

                    if (isset($logo_files['logo']) && !empty($logo_files['logo']['name']) && isset($_POST['old_logo']) && !empty($_POST['old_logo'])) {
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
                } else {
                    $logo_doc = $this->input->post('old_logo', true);
                }

                $retailer_data = ['company_name' => $this->input->post('company_name'), 'store_name' => $this->input->post('company_name'), 'slug' => create_unique_slug($this->input->post('company_name', true), 'retailer_data'), 'year_establish' => $this->input->post('year_establish'), 'owner_name' => $this->input->post('owner_name'), 'logo' => $logo_doc, 'shop_name' => $this->input->post('shop_name'), 'plot_no' => $this->input->post('plot_no'), 'street_locality' => $this->input->post('street_locality'), 'landmark' => $this->input->post('landmark'), 'pin' => $this->input->post('pin'), 'city_id' => $this->input->post('city_id'), 'state_id' => $this->input->post('state_id'), 'storage_shop_name' => $this->input->post('storage_shop_name'), 'storage_plot_no' => $this->input->post('storage_plot_no'), 'storage_street_locality' => $this->input->post('storage_street_locality'), 'storage_landmark' => $this->input->post('storage_landmark'), 'storage_pin' => $this->input->post('storage_pin'), 'storage_city_id' => $this->input->post('storage_city_id'), 'storage_state_id' => $this->input->post('storage_state_id'), 'website_url' => $this->input->post('website_url'), 'google_business_url' => $this->input->post('google_business_url'), 'facebook_business_url' => $this->input->post('facebook_business_url'), 'instagram_business_url' => $this->input->post('instagram_business_url')];
                $retailer_data = escape_array($retailer_data);
                $this->db->set($retailer_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');

                $retailer_info = $this->db->get_where('retailer_data', array('user_id' => $this->input->post('user_id')))->row();

                $identity_column = $this->config->item('identity', 'ion_auth');
                $user_info       = $this->ion_auth->user()->row();

                $check1 = $this->db->get_where('addresses', array('user_id' => $user_info->id, 'add_order' => 1))->row_array();

                if ($check1) {
                    $address_1_data = ['name' => $user_info->username, 'mobile' => $user_info->mobile, 'alternate_mobile' => $user_info->alternate_mobile, 'type' => 'office', 'shop_name' => $this->input->post('shop_name'), 'plot_no' => $this->input->post('plot_no'), 'street_locality' => $this->input->post('street_locality'), 'address' => $this->input->post('plot_no') . ' ' . $this->input->post('street_locality') . ' ' . $this->input->post('landmark'), 'landmark' => $this->input->post('landmark'), 'city_id' => $this->input->post('city_id'), 'state_id' => $this->input->post('state_id'), 'pincode' => $this->input->post('pin'), 'add_order' => 1];
                    $address_1_data = escape_array($address_1_data);
                    $this->db->set($address_1_data)->where('user_id', $user_info->id)->where('add_order', 1)->update('addresses');
                } else {
                    $address_1_data = ['name' => $user_info->username, 'mobile' => $user_info->mobile, 'alternate_mobile' => $user_info->alternate_mobile, 'user_id' => $user_info->id, 'type' => 'office', 'shop_name' => $this->input->post('shop_name'), 'plot_no' => $this->input->post('plot_no'), 'street_locality' => $this->input->post('street_locality'), 'address' => $this->input->post('plot_no') . ' ' . $this->input->post('street_locality') . ' ' . $this->input->post('landmark'), 'landmark' => $this->input->post('landmark'), 'city_id' => $this->input->post('city_id'), 'state_id' => $this->input->post('state_id'), 'pincode' => $this->input->post('pin'), 'add_order' => 1];
                    $address_1_data = escape_array($address_1_data);

                    $this->db->insert('addresses', $address_1_data);
                }

                $check2 = $this->db->get_where('addresses', array('user_id' => $user_info->id, 'add_order' => 2))->row_array();

                if ($check2) {
                    $address_2_data = ['name' => $user_info->username, 'mobile' => $user_info->mobile, 'alternate_mobile' => $user_info->alternate_mobile, 'type' => 'storage', 'shop_name' => $this->input->post('storage_shop_name'), 'plot_no' => $this->input->post('storage_plot_no'), 'street_locality' => $this->input->post('storage_street_locality'), 'address' => $this->input->post('storage_plot_no') . ' ' . $this->input->post('storage_street_locality') . ' ' . $this->input->post('storage_landmark'), 'landmark' => $this->input->post('storage_landmark'), 'city_id' => $this->input->post('storage_city_id'), 'state_id' => $this->input->post('storage_state_id'), 'pincode' => $this->input->post('storage_pin'), 'add_order' => 2];
                    $address_2_data = escape_array($address_2_data);
                    $this->db->set($address_2_data)->where('user_id', $user_info->id)->where('add_order', 2)->update('addresses');
                } else {
                    $address_2_data = ['name' => $user_info->username, 'mobile' => $user_info->mobile, 'alternate_mobile' => $user_info->alternate_mobile, 'user_id' => $user_info->id, 'type' => 'storage', 'shop_name' => $this->input->post('storage_shop_name'), 'plot_no' => $this->input->post('storage_plot_no'), 'street_locality' => $this->input->post('storage_street_locality'), 'address' => $this->input->post('storage_plot_no') . ' ' . $this->input->post('storage_street_locality') . ' ' . $this->input->post('storage_landmark'), 'landmark' => $this->input->post('storage_landmark'), 'city_id' => $this->input->post('storage_city_id'), 'state_id' => $this->input->post('storage_state_id'), 'pincode' => $this->input->post('storage_pin'), 'add_order' => 2];
                    $address_2_data = escape_array($address_2_data);

                    $this->db->insert('addresses', $address_2_data);
                }

                $this->response['redirect_to'] = ''; // base_url('my-account/bank-details/'.$is_seller);
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
        $cities   = $this->db->select("*")->from("cities")->where('state_id', $state_id)->get()->result();
        foreach ($cities as $city) {
            $html  .= '<option value="' . $city->id . '">' . $city->name . '</option>';
        }
        echo $html;
        die;
    }

    public function bank_details($is_seller = 0)
    {
        if (!$this->ion_auth->logged_in()) {
            redirect(base_url());
        } else {
            $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->data['user_id']   = $this->ion_auth->get_user_id();

            if ($is_seller) {
                $this->data['seller_data'] = $this->db->get_where('seller_data', array('user_id' => $this->data['user_id']))->row();
            } else {
                $this->data['seller_data'] = $this->db->get_where('retailer_data', array('user_id' => $this->data['user_id']))->row();
            }

            $this->data['is_seller'] = $is_seller;
            $this->data['main_page'] = 'bank_details';
            $this->data['title'] = 'Bank Details | ' . $this->data['web_settings']['site_title'];

            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
    }

    public function save_step5()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect(base_url());
        }

        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->data['user_id']   = $this->ion_auth->get_user_id();

        $is_seller = $this->input->post('is_seller');
        $seller_info = array();
        if ($is_seller) {
            $seller_info = $this->db->get_where('seller_data', array('user_id' => $this->data['user_id']))->row_array();
        } else {
            $seller_info = $this->db->get_where('retailer_data', array('user_id' => $this->data['user_id']))->row_array();
        }

        $this->form_validation->set_rules('account_name', 'Account Holder Name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('account_number', 'Account Number', 'required|xss_clean|trim|matches[confirm_account_number]');
        $this->form_validation->set_rules('confirm_account_number', 'Confirm Account Number', 'required');
        $this->form_validation->set_rules('bank_code', 'IFSC Code', 'required|xss_clean|trim');
        $this->form_validation->set_rules('account_type', 'Account Type', 'required|xss_clean|trim');
        $this->form_validation->set_rules('bank_name', 'Bank Name', 'required|xss_clean|trim');

        if (empty($_FILES['cancelled_cheque']['name']) && $seller_info['cancelled_cheque'] == '') {
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

            if ($is_seller) {
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
                        if (isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
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


                if (!empty($images_new_name_arr)) {
                    $seller_data = ['cancelled_cheque' => $images_new_name_arr[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }

                $this->response['redirect_to'] = ''; // base_url('my-account/gst-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Bank Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            } else {
                $seller_data = ['account_name' => $this->input->post('account_name'), 'account_number' => $this->input->post('account_number'), 'bank_code' => $this->input->post('bank_code'), 'account_type' => $this->input->post('account_type'), 'bank_name' => $this->input->post('bank_name'), 'bank_branch' => $this->input->post('bank_branch'), 'bank_city' => $this->input->post('bank_city'), 'bank_state' => $this->input->post('bank_state')];
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


                if (!empty($images_new_name_arr)) {
                    $seller_data = ['cancelled_cheque' => $images_new_name_arr[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                }

                $this->response['redirect_to'] = ''; // base_url('my-account/gst-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Bank Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
        }
    }

    public function gst_details($is_seller = 0)
    {
        if (!$this->ion_auth->logged_in()) {
            redirect(base_url());
        } else {
            $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->data['user_id']   = $this->ion_auth->get_user_id();

            if ($is_seller) {
                $this->data['seller_data'] = $this->db->get_where('seller_data', array('user_id' => $this->data['user_id']))->row();
            } else {
                $this->data['seller_data'] = $this->db->get_where('retailer_data', array('user_id' => $this->data['user_id']))->row();
            }

            $this->data['is_seller'] = $is_seller;
            $this->data['main_page'] = 'gst_details';
            $this->data['title'] = 'GST | ' . $this->data['web_settings']['site_title'];

            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
    }

    public function save_step6()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect(base_url());
        }

        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->data['user_id']   = $this->ion_auth->get_user_id();

        $is_seller = $this->input->post('is_seller');

        $seller_info = array();
        if ($is_seller) {
            $seller_info = $this->db->get_where('seller_data', array('user_id' => $this->data['user_id']))->row_array();
        } else {
            $seller_info = $this->db->get_where('retailer_data', array('user_id' => $this->data['user_id']))->row_array();
        }

        $this->form_validation->set_rules('have_gst_no', 'Have GST/Dont Have GST', 'required|xss_clean|trim');

        if ($_POST['have_gst_no'] == 1) {
            $this->form_validation->set_rules('gst_no', 'GST Number', 'required|xss_clean|trim');

            if (empty($_FILES['gst_no_photo']['name']) && empty($_FILES['gst_no_pdf']['name']) && $seller_info['gst_no_photo'] == '' && $seller_info['gst_no_pdf'] == '') {
                $this->form_validation->set_rules('gst_no_photo', 'GST Number Photo / PDF', 'required');
            }
        }

        if ($_POST['have_gst_no'] == 2) {
            $this->form_validation->set_rules('pan_number', 'PAN Number', 'required|xss_clean|trim');

            if (empty($_FILES['pan_no_photo']['name']) && empty($_FILES['pan_no_pdf']['name']) && $seller_info['pan_no_photo'] == '' && $seller_info['pan_no_pdf'] == '') {
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

            if ($is_seller) {
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


                if (!empty($_FILES['gst_no_photo']['name']) && isset($_FILES['gst_no_photo']['name'])) {
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
                        if (isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
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


                if (!empty($images_new_name_arr)) {
                    $seller_data = ['gst_no_photo' => $images_new_name_arr[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }

                if (!file_exists(FCPATH . SELLER_GST_PDF_PATH)) {
                    mkdir(FCPATH . SELLER_GST_PDF_PATH, 0777);
                }

                $temp_array = array();
                $files = $_FILES;
                $pdfs_new_name_arr = array();
                $pdfs_info_error = "";
                $allowed_media_types = implode('|', allowed_media_types());
                $config = [
                    'upload_path' =>  FCPATH . SELLER_GST_PDF_PATH,
                    'allowed_types' => $allowed_media_types,
                    'max_size' => 8000,
                ];


                if (!empty($_FILES['gst_no_pdf']['name']) && isset($_FILES['gst_no_pdf']['name'])) {
                    $other_image_cnt = count($_FILES['gst_no_pdf']['name']);
                    $other_pdf = $this->upload;
                    $other_pdf->initialize($config);


                    if (!empty($_FILES['gst_no_pdf']['name'])) {

                        $_FILES['temp_image']['name'] = $files['gst_no_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files['gst_no_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['gst_no_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['gst_no_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files['gst_no_pdf']['size'];
                        if (!$other_pdf->do_upload('temp_image')) {
                            $pdfs_info_error = 'attachments :' . $pdfs_info_error . ' ' . $other_pdf->display_errors();
                        } else {
                            $temp_array = $other_pdf->data();
                            $pdfs_new_name_arr[] = SELLER_GST_PDF_PATH . $temp_array['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files['gst_no_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files['gst_no_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['gst_no_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['gst_no_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files['gst_no_pdf']['size'];
                        if (!$other_pdf->do_upload('temp_image')) {
                            $pdfs_info_error = $other_pdf->display_errors();
                        }
                    }

                    //Deleting Uploaded attachments if any overall error occured
                    if ($pdfs_info_error != NULL || !$this->form_validation->run()) {
                        if (isset($pdfs_new_name_arr) && !empty($pdfs_new_name_arr || !$this->form_validation->run())) {
                            foreach ($pdfs_new_name_arr as $key => $val) {
                                unlink(FCPATH . SELLER_GST_PDF_PATH . $pdfs_new_name_arr[$key]);
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


                if (!empty($pdfs_new_name_arr)) {
                    $seller_data = ['gst_no_pdf' => $pdfs_new_name_arr[0]];
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


                if (!empty($_FILES['pan_no_photo']['name']) && isset($_FILES['pan_no_photo']['name'])) {
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
                        if (isset($images_new_name_arr2) && !empty($images_new_name_arr2 || !$this->form_validation->run())) {
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


                if (!empty($images_new_name_arr2)) {
                    $seller_data = ['pan_no_photo' => $images_new_name_arr2[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }

                if (!file_exists(FCPATH . SELLER_PAN_PDF_PATH)) {
                    mkdir(FCPATH . SELLER_PAN_PDF_PATH, 0777);
                }

                $temp_array2 = array();
                $files2 = $_FILES;
                $pdfs_new_name_arr2 = array();
                $pdfs_info_error2 = "";
                $allowed_media_types2 = implode('|', allowed_media_types());
                $config2 = [
                    'upload_path' =>  FCPATH . SELLER_PAN_PDF_PATH,
                    'allowed_types' => $allowed_media_types2,
                    'max_size' => 8000,
                ];


                if (!empty($_FILES['pan_no_pdf']['name']) && isset($_FILES['pan_no_pdf']['name'])) {
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
                            $pdfs_new_name_arr2[] = SELLER_PAN_PDF_PATH . $temp_array2['file_name'];
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
                        if (isset($pdfs_new_name_arr2) && !empty($pdfs_new_name_arr2 || !$this->form_validation->run())) {
                            foreach ($pdfs_new_name_arr2 as $key => $val) {
                                unlink(FCPATH . SELLER_PAN_PDF_PATH . $pdfs_new_name_arr2[$key]);
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


                if (!empty($pdfs_new_name_arr2)) {
                    $seller_data = ['pan_no_pdf' => $pdfs_new_name_arr2[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }

                $this->response['redirect_to'] = ''; // base_url('my-account/license-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'GST Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            } else {
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


                if (!empty($_FILES['gst_no_photo']['name']) && isset($_FILES['gst_no_photo']['name'])) {
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
                        if (isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
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


                if (!empty($images_new_name_arr)) {
                    $seller_data = ['gst_no_photo' => $images_new_name_arr[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
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


                if (!empty($_FILES['gst_no_pdf']['name']) && isset($_FILES['gst_no_pdf']['name'])) {
                    $other_image_cnt = count($_FILES['gst_no_pdf']['name']);
                    $other_pdf = $this->upload;
                    $other_pdf->initialize($config);


                    if (!empty($_FILES['gst_no_pdf']['name'])) {

                        $_FILES['temp_image']['name'] = $files['gst_no_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files['gst_no_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['gst_no_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['gst_no_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files['gst_no_pdf']['size'];
                        if (!$other_pdf->do_upload('temp_image')) {
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
                        if (!$other_pdf->do_upload('temp_image')) {
                            $pdfs_info_error = $other_pdf->display_errors();
                        }
                    }

                    //Deleting Uploaded attachments if any overall error occured
                    if ($pdfs_info_error != NULL || !$this->form_validation->run()) {
                        if (isset($pdfs_new_name_arr) && !empty($pdfs_new_name_arr || !$this->form_validation->run())) {
                            foreach ($pdfs_new_name_arr as $key => $val) {
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


                if (!empty($pdfs_new_name_arr)) {
                    $retailer_data = ['gst_no_pdf' => $pdfs_new_name_arr[0]];
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


                if (!empty($_FILES['pan_no_photo']['name']) && isset($_FILES['pan_no_photo']['name'])) {
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
                        if (isset($images_new_name_arr2) && !empty($images_new_name_arr2 || !$this->form_validation->run())) {
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


                if (!empty($images_new_name_arr2)) {
                    $seller_data = ['pan_no_photo' => $images_new_name_arr2[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
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


                if (!empty($_FILES['pan_no_pdf']['name']) && isset($_FILES['pan_no_pdf']['name'])) {
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
                        if (isset($pdfs_new_name_arr2) && !empty($pdfs_new_name_arr2 || !$this->form_validation->run())) {
                            foreach ($pdfs_new_name_arr2 as $key => $val) {
                                unlink(FCPATH . RETAILER_PAN_PDF_PATH . $pdfs_new_name_arr2[$key]);
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


                if (!empty($pdfs_new_name_arr2)) {
                    $retailer_data = ['pan_no_pdf' => $pdfs_new_name_arr2[0]];
                    $retailer_data = escape_array($retailer_data);
                    $this->db->set($retailer_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                }

                $this->response['redirect_to'] = ''; // base_url('my-account/license-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'GST Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
        }
    }

    public function license_details($is_seller = 0)
    {
        if (!$this->ion_auth->logged_in()) {
            redirect(base_url());
        } else {
            $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->data['user_id']   = $this->ion_auth->get_user_id();

            if ($is_seller) {
                $this->data['seller_data'] = $this->db->get_where('seller_data', array('user_id' => $this->data['user_id']))->row();
            } else {
                $this->data['seller_data'] = $this->db->get_where('retailer_data', array('user_id' => $this->data['user_id']))->row();
            }

            $this->data['is_seller'] = $is_seller;
            $this->data['main_page'] = 'license_details';
            $this->data['title'] = 'License | ' . $this->data['web_settings']['site_title'];

            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
    }

    public function save_step7()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect(base_url());
        }

        $is_seller = $this->input->post('is_seller');

        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->data['user_id']   = $this->ion_auth->get_user_id();

        $seller_info = array();
        if ($is_seller) {
            $seller_info = $this->db->get_where('seller_data', array('user_id' => $this->data['user_id']))->row_array();
        } else {
            $seller_info = $this->db->get_where('retailer_data', array('user_id' => $this->data['user_id']))->row_array();
        }

        $flag = 0;
        if ($_POST['have_fertilizer_license'] == 1) {
            $this->form_validation->set_rules('fertilizer_license_no', 'Fertilizer License Number', 'required|xss_clean|trim');
            $this->form_validation->set_rules('fert_lic_expiry_date', 'Fertilizer License Expiry Date', 'required|xss_clean|trim');
            if (empty($_FILES['fertilizer_license_photo']['name']) && $seller_info['fertilizer_license_photo'] == '') {
                $this->form_validation->set_rules('fertilizer_license_photo', 'Fertilizer License Photo', 'required');
            }
            $flag = 1;
        }

        if ($_POST['have_pesticide_license_no'] == 1) {
            $this->form_validation->set_rules('pesticide_license_no', 'Pesticide License Number', 'required|xss_clean|trim');
            $this->form_validation->set_rules('pest_lic_expiry_date', 'Pesticide License Expiry Date', 'required|xss_clean|trim');
            if (empty($_FILES['pesticide_license_photo']['name']) && $seller_info['pesticide_license_photo'] == '') {
                $this->form_validation->set_rules('pesticide_license_photo', 'Pesticide License Photo', 'required|xss_clean|trim');
            }
            $flag = 1;
        }

        if ($_POST['have_seeds_license_no'] == 1) {
            $this->form_validation->set_rules('seeds_license_no', 'Seeds License Number', 'required|xss_clean|trim');
            $this->form_validation->set_rules('seeds_lic_expiry_date', 'Seeds License Expiry Date', 'required|xss_clean|trim');
            if (empty($_FILES['seeds_license_photo']['name']) && $seller_info['seeds_license_photo'] == '') {
                $this->form_validation->set_rules('seeds_license_photo', 'Seeds License Photo', 'required|xss_clean|trim');
            }
            $flag = 1;
        }

        if ($_POST['have_agri_serv_license_no'] == 1) {
            $this->form_validation->set_rules('have_agri_serv_license_no', 'Agri Services License Number', 'required|xss_clean|trim');
            $this->form_validation->set_rules('agri_serv_lic_expiry_date', 'Agri Services License Expiry Date', 'required|xss_clean|trim');
            if (empty($_FILES['agri_serv_license_photo']['name']) && empty($_FILES['agri_serv_license_pdf']['name']) && $seller_info['agri_serv_license_photo'] == '' && $seller_info['agri_serv_license_pdf'] == '') {
                $this->form_validation->set_rules('agri_serv_license_photo', 'Agri Services License Photo / PDF', 'required|xss_clean|trim');
            }
            $flag = 1;
        }

        if ($_POST['have_agri_equip_license_no'] == 1) {
            $this->form_validation->set_rules('have_agri_equip_license_no', 'Agri Equipments License Number', 'required|xss_clean|trim');
            $this->form_validation->set_rules('agri_equip_lic_expiry_date', 'Agri Equipments License Expiry Date', 'required|xss_clean|trim');
            if (empty($_FILES['agri_equip_license_photo']['name']) && empty($_FILES['agri_equip_license_pdf']['name']) && $seller_info['agri_equip_license_photo'] == '' && $seller_info['agri_equip_license_pdf'] == '') {
                $this->form_validation->set_rules('agri_equip_license_photo', 'Agri Equipments License Photo / PDF', 'required|xss_clean|trim');
            }
            $flag = 1;
        }

        if (!$this->form_validation->run() && $flag == 1) {
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

            if ($is_seller) {
                $seller_data = ['have_fertilizer_license' => $this->input->post('have_fertilizer_license'), 'fertilizer_license_no' => $this->input->post('fertilizer_license_no'), 'have_pesticide_license_no' => $this->input->post('have_pesticide_license_no'), 'pesticide_license_no' => $this->input->post('pesticide_license_no'), 'have_seeds_license_no' => $this->input->post('have_seeds_license_no'), 'seeds_license_no' => $this->input->post('seeds_license_no'), 'have_agri_serv_license_no' => $this->input->post('have_agri_serv_license_no'), 'agri_serv_license_no' => $this->input->post('agri_serv_license_no'), 'agri_serv_lic_expiry_date' => $this->input->post('agri_serv_lic_expiry_date'), 'have_agri_equip_license_no' => $this->input->post('have_agri_equip_license_no'), 'agri_equip_license_no' => $this->input->post('agri_equip_license_no'), 'agri_equip_lic_expiry_date' => $this->input->post('agri_equip_lic_expiry_date'), 'is_finish' => $this->input->post('is_finish')];
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


                if (!empty($_FILES['fertilizer_license_photo']['name']) && isset($_FILES['fertilizer_license_photo']['name'])) {
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
                        if (isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
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


                if (!empty($images_new_name_arr)) {
                    $seller_data = ['fertilizer_license_photo' => $images_new_name_arr[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }


                if (!file_exists(FCPATH . SELLER_FERT_LIC_PDF_PATH)) {
                    mkdir(FCPATH . SELLER_FERT_LIC_PDF_PATH, 0777);
                }

                $temp_array = array();
                $files = $_FILES;
                $images_new_name_arr = array();
                $images_info_error = "";
                $allowed_media_types = implode('|', allowed_media_types());
                $config = [
                    'upload_path' =>  FCPATH . SELLER_FERT_LIC_PDF_PATH,
                    'allowed_types' => $allowed_media_types,
                    'max_size' => 8000,
                ];


                if (!empty($_FILES['fertilizer_license_pdf']['name']) && isset($_FILES['fertilizer_license_pdf']['name'])) {
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
                            $images_info_error = 'attachments :' . $images_info_error . ' ' . $other_pdf->display_errors();
                        } else {
                            $temp_array = $other_pdf->data();
                            resize_review_images($temp_array, FCPATH . SELLER_FERT_LIC_PDF_PATH);
                            $images_new_name_arr[] = SELLER_FERT_LIC_PDF_PATH . $temp_array['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files['fertilizer_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files['fertilizer_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files['fertilizer_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files['fertilizer_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files['fertilizer_license_pdf']['size'];
                        if (!$other_pdf->do_upload('temp_image')) {
                            $images_info_error = $other_pdf->display_errors();
                        }
                    }

                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error != NULL || !$this->form_validation->run()) {
                        if (isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr as $key => $val) {
                                unlink(FCPATH . SELLER_FERT_LIC_PDF_PATH . $images_new_name_arr[$key]);
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


                if (!empty($images_new_name_arr)) {
                    $seller_data = ['fertilizer_license_pdf' => $images_new_name_arr[0]];
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


                if (!empty($_FILES['pesticide_license_photo']['name']) && isset($_FILES['pesticide_license_photo']['name'])) {
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
                        if (isset($images_new_name_arr2) && !empty($images_new_name_arr2 || !$this->form_validation->run())) {
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


                if (!empty($images_new_name_arr2)) {
                    $seller_data = ['pesticide_license_photo' => $images_new_name_arr2[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }

                if (!file_exists(FCPATH . SELLER_PEST_LIC_PDF_PATH)) {
                    mkdir(FCPATH . SELLER_PEST_LIC_PDF_PATH, 0777);
                }

                $temp_array2 = array();
                $files2 = $_FILES;
                $pdf_new_name_arr2 = array();
                $pdf_info_error2 = "";
                $allowed_media_types2 = implode('|', allowed_media_types());
                $config2 = [
                    'upload_path' =>  FCPATH . SELLER_PEST_LIC_PDF_PATH,
                    'allowed_types' => $allowed_media_types2,
                    'max_size' => 8000,
                ];


                if (!empty($_FILES['pesticide_license_pdf']['name']) && isset($_FILES['pesticide_license_pdf']['name'])) {
                    $other_image_cnt2 = count($_FILES['pesticide_license_pdf']['name']);
                    $other_pdf2 = $this->upload;
                    $other_pdf2->initialize($config2);


                    if (!empty($_FILES['pesticide_license_pdf']['name'])) {

                        $_FILES['temp_image']['name'] = $files2['pesticide_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files2['pesticide_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files2['pesticide_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files2['pesticide_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files2['pesticide_license_pdf']['size'];
                        if (!$other_pdf2->do_upload('temp_image')) {
                            $pdf_info_error2 = 'attachments :' . $pdf_info_error2 . ' ' . $other_pdf2->display_errors();
                        } else {
                            $temp_array2 = $other_pdf2->data();
                            $pdf_new_name_arr2[] = SELLER_PEST_LIC_PDF_PATH . $temp_array2['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files2['pesticide_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files2['pesticide_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files2['pesticide_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files2['pesticide_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files2['pesticide_license_pdf']['size'];
                        if (!$other_pdf2->do_upload('temp_image')) {
                            $pdf_info_error2 = $other_pdf2->display_errors();
                        }
                    }

                    //Deleting Uploaded attachments if any overall error occured
                    if ($pdf_info_error2 != NULL || !$this->form_validation->run()) {
                        if (isset($pdf_new_name_arr2) && !empty($pdf_new_name_arr2 || !$this->form_validation->run())) {
                            foreach ($pdf_new_name_arr2 as $key => $val) {
                                unlink(FCPATH . SELLER_PEST_LIC_PDF_PATH . $pdf_new_name_arr2[$key]);
                            }
                        }
                    }
                }

                if ($pdf_info_error2 != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $pdf_info_error2;
                    print_r(json_encode($this->response));
                    return false;
                }


                if (!empty($pdf_new_name_arr2)) {
                    $seller_data = ['pesticide_license_pdf' => $pdf_new_name_arr2[0]];
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


                if (!empty($_FILES['seeds_license_photo']['name']) && isset($_FILES['seeds_license_photo']['name'])) {
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
                        if (isset($images_new_name_arr3) && !empty($images_new_name_arr3 || !$this->form_validation->run())) {
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


                if (!empty($images_new_name_arr3)) {
                    $seller_data = ['seeds_license_photo' => $images_new_name_arr3[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }

                if (!file_exists(FCPATH . SELLER_SEEDS_LIC_PDF_PATH)) {
                    mkdir(FCPATH . SELLER_SEEDS_LIC_PDF_PATH, 0777);
                }

                $temp_array3 = array();
                $files3 = $_FILES;
                $pdf_new_name_arr3 = array();
                $pdf_info_error3 = "";
                $allowed_media_types3 = implode('|', allowed_media_types());
                $config3 = [
                    'upload_path' =>  FCPATH . SELLER_SEEDS_LIC_PDF_PATH,
                    'allowed_types' => $allowed_media_types3,
                    'max_size' => 8000,
                ];


                if (!empty($_FILES['seeds_license_pdf']['name']) && isset($_FILES['seeds_license_pdf']['name'])) {
                    $other_image_cnt3 = count($_FILES['seeds_license_pdf']['name']);
                    $other_pdf3 = $this->upload;
                    $other_pdf3->initialize($config3);


                    if (!empty($_FILES['seeds_license_pdf']['name'])) {

                        $_FILES['temp_image']['name'] = $files3['seeds_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files3['seeds_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files3['seeds_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files3['seeds_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files3['seeds_license_pdf']['size'];
                        if (!$other_pdf3->do_upload('temp_image')) {
                            $pdf_info_error3 = 'attachments :' . $pdf_info_error3 . ' ' . $other_pdf3->display_errors();
                        } else {
                            $temp_array3 = $other_pdf3->data();
                            $pdf_new_name_arr3[] = SELLER_SEEDS_LIC_PDF_PATH . $temp_array3['file_name'];
                        }
                    } else {
                        $_FILES['temp_image']['name'] = $files3['seeds_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files3['seeds_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files3['seeds_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files3['seeds_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files3['seeds_license_pdf']['size'];
                        if (!$other_pdf3->do_upload('temp_image')) {
                            $pdf_info_error3 = $other_pdf3->display_errors();
                        }
                    }

                    //Deleting Uploaded attachments if any overall error occured
                    if ($pdf_info_error3 != NULL || !$this->form_validation->run()) {
                        if (isset($pdf_new_name_arr3) && !empty($pdf_new_name_arr3 || !$this->form_validation->run())) {
                            foreach ($pdf_new_name_arr3 as $key => $val) {
                                unlink(FCPATH . SELLER_SEEDS_LIC_PDF_PATH . $pdf_new_name_arr3[$key]);
                            }
                        }
                    }
                }

                if ($pdf_info_error3 != NULL) {
                    $this->response['error'] = true;
                    $this->response['message'] =  $pdf_info_error3;
                    print_r(json_encode($this->response));
                    return false;
                }


                if (!empty($pdf_new_name_arr3)) {
                    $seller_data = ['seeds_license_pdf' => $pdf_new_name_arr3[0]];
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


                if (!empty($_FILES['o_form_photo']['name']) && isset($_FILES['o_form_photo']['name'])) {
                    $other_image_cnt4 = count($_FILES['o_form_photo']['name']);
                    $other_img4 = $this->upload;
                    $other_img4->initialize($config4);


                    if (!empty($_FILES['o_form_photo']['name'])) {

                        $_FILES['temp_image']['name'] = $files4['o_form_photo']['name'];
                        $_FILES['temp_image']['type'] = $files4['o_form_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files4['o_form_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files4['o_form_photo']['error'];
                        $_FILES['temp_image']['size'] = $files4['o_form_photo']['size'];
                        if (!$other_img4->do_upload('temp_image')) {
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
                        if (isset($images_new_name_arr4) && !empty($images_new_name_arr4 || !$this->form_validation->run())) {
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


                if (!empty($images_new_name_arr4)) {
                    $seller_data = ['o_form_photo' => $images_new_name_arr4[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }

                if (!file_exists(FCPATH . SELLER_AGRI_SERV_LIC_IMG_PATH)) {
                    mkdir(FCPATH . SELLER_AGRI_SERV_LIC_IMG_PATH, 0777);
                }

                $temp_array5 = array();
                $files5 = $_FILES;
                $images_new_name_arr5 = array();
                $images_info_error5 = "";
                $allowed_media_types5 = implode('|', allowed_media_types());
                $config5 = [
                    'upload_path' =>  FCPATH . SELLER_AGRI_SERV_LIC_IMG_PATH,
                    'allowed_types' => $allowed_media_types5,
                    'max_size' => 8000,
                ];


                if (!empty($_FILES['agri_serv_license_photo']['name']) && isset($_FILES['agri_serv_license_photo']['name'])) {
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
                            resize_review_images($temp_array5, FCPATH . SELLER_AGRI_SERV_LIC_IMG_PATH);
                            $images_new_name_arr5[] = SELLER_AGRI_SERV_LIC_IMG_PATH . $temp_array5['file_name'];
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
                        if (isset($images_new_name_arr5) && !empty($images_new_name_arr5 || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr5 as $key => $val) {
                                unlink(FCPATH . SELLER_AGRI_SERV_LIC_IMG_PATH . $images_new_name_arr5[$key]);
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


                if (!empty($images_new_name_arr5)) {
                    $seller_data = ['agri_serv_license_photo' => $images_new_name_arr5[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }

                if (!file_exists(FCPATH . SELLER_AGRI_SERV_LIC_PDF_PATH)) {
                    mkdir(FCPATH . SELLER_AGRI_SERV_LIC_PDF_PATH, 0777);
                }

                $temp_array5 = array();
                $files5 = $_FILES;
                $pdfs_new_name_arr5 = array();
                $pdfs_info_error5 = "";
                $allowed_media_types5 = implode('|', allowed_media_types());
                $config5 = [
                    'upload_path' =>  FCPATH . SELLER_AGRI_SERV_LIC_PDF_PATH,
                    'allowed_types' => $allowed_media_types5,
                    'max_size' => 8000,
                ];


                if (!empty($_FILES['agri_serv_license_pdf']['name']) && isset($_FILES['agri_serv_license_pdf']['name'])) {
                    $other_pdf_cnt5 = count($_FILES['agri_serv_license_pdf']['name']);
                    $other_pdf5 = $this->upload;
                    $other_pdf5->initialize($config5);


                    if (!empty($_FILES['agri_serv_license_pdf']['name'])) {

                        $_FILES['temp_image']['name'] = $files5['agri_serv_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files5['agri_serv_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files5['agri_serv_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files5['agri_serv_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files5['agri_serv_license_pdf']['size'];
                        if (!$other_pdf5->do_upload('temp_image')) {
                            $pdfs_info_error5 = 'attachments :' . $pdfs_info_error5 . ' ' . $other_pdf5->display_errors();
                        } else {
                            $temp_array5 = $other_pdf5->data();
                            $pdfs_new_name_arr5[] = SELLER_AGRI_SERV_LIC_PDF_PATH . $temp_array5['file_name'];
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
                        if (isset($pdfs_new_name_arr5) && !empty($pdfs_new_name_arr5 || !$this->form_validation->run())) {
                            foreach ($pdfs_new_name_arr5 as $key => $val) {
                                unlink(FCPATH . SELLER_AGRI_SERV_LIC_PDF_PATH . $pdfs_new_name_arr5[$key]);
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


                if (!empty($pdfs_new_name_arr5)) {
                    $seller_data = ['agri_serv_license_pdf' => $pdfs_new_name_arr5[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }

                if (!file_exists(FCPATH . SELLER_AGRI_EQUIP_LIC_IMG_PATH)) {
                    mkdir(FCPATH . SELLER_AGRI_EQUIP_LIC_IMG_PATH, 0777);
                }

                $temp_array6 = array();
                $files6 = $_FILES;
                $images_new_name_arr6 = array();
                $images_info_error6 = "";
                $allowed_media_types6 = implode('|', allowed_media_types());
                $config6 = [
                    'upload_path' =>  FCPATH . SELLER_AGRI_EQUIP_LIC_IMG_PATH,
                    'allowed_types' => $allowed_media_types6,
                    'max_size' => 8000,
                ];


                if (!empty($_FILES['agri_equip_license_photo']['name']) && isset($_FILES['agri_equip_license_photo']['name'])) {
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
                            resize_review_images($temp_array6, FCPATH . SELLER_AGRI_EQUIP_LIC_IMG_PATH);
                            $images_new_name_arr6[] = SELLER_AGRI_EQUIP_LIC_IMG_PATH . $temp_array6['file_name'];
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
                        if (isset($images_new_name_arr6) && !empty($images_new_name_arr6 || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr6 as $key => $val) {
                                unlink(FCPATH . SELLER_AGRI_EQUIP_LIC_IMG_PATH . $images_new_name_arr6[$key]);
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


                if (!empty($images_new_name_arr6)) {
                    $seller_data = ['agri_equip_license_photo' => $images_new_name_arr6[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }

                if (!file_exists(FCPATH . SELLER_AGRI_EQUIP_LIC_PDF_PATH)) {
                    mkdir(FCPATH . SELLER_AGRI_EQUIP_LIC_PDF_PATH, 0777);
                }

                $temp_array6 = array();
                $files6 = $_FILES;
                $pdfs_new_name_arr6 = array();
                $pdfs_info_error6 = "";
                $allowed_media_types6 = implode('|', allowed_media_types());
                $config6 = [
                    'upload_path' =>  FCPATH . SELLER_AGRI_EQUIP_LIC_PDF_PATH,
                    'allowed_types' => $allowed_media_types6,
                    'max_size' => 8000,
                ];


                if (!empty($_FILES['agri_equip_license_pdf']['name']) && isset($_FILES['agri_equip_license_pdf']['name'])) {
                    $other_pdf_cnt6 = count($_FILES['agri_equip_license_pdf']['name']);
                    $other_pdf6 = $this->upload;
                    $other_pdf6->initialize($config6);


                    if (!empty($_FILES['agri_equip_license_pdf']['name'])) {

                        $_FILES['temp_image']['name'] = $files6['agri_equip_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files6['agri_equip_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files6['agri_equip_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files6['agri_equip_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files6['agri_equip_license_pdf']['size'];
                        if (!$other_pdf6->do_upload('temp_image')) {
                            $pdfs_info_error6 = 'attachments :' . $pdfs_info_error6 . ' ' . $other_pdf6->display_errors();
                        } else {
                            $temp_array6 = $other_pdf6->data();
                            $pdfs_new_name_arr6[] = SELLER_AGRI_EQUIP_LIC_PDF_PATH . $temp_array6['file_name'];
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
                        if (isset($pdfs_new_name_arr6) && !empty($pdfs_new_name_arr6 || !$this->form_validation->run())) {
                            foreach ($pdfs_new_name_arr6 as $key => $val) {
                                unlink(FCPATH . SELLER_AGRI_EQUIP_LIC_PDF_PATH . $pdfs_new_name_arr6[$key]);
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


                if (!empty($pdfs_new_name_arr6)) {
                    $seller_data = ['agri_equip_license_pdf' => $pdfs_new_name_arr6[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                }

                $this->response['redirect_to'] = ''; // base_url('my-account/license-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'License Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            } else {
                $seller_data = ['have_fertilizer_license' => $this->input->post('have_fertilizer_license'), 'fertilizer_license_no' => $this->input->post('fertilizer_license_no'), 'fertilizer_license_no' => $this->input->post('fertilizer_license_no'), 'fert_lic_issue_date' => $this->input->post('fert_lic_issue_date'), 'fert_lic_expiry_date' => $this->input->post('fert_lic_expiry_date'), 'have_pesticide_license_no' => $this->input->post('have_pesticide_license_no'),  'pesticide_license_no' => $this->input->post('pesticide_license_no'), 'pest_lic_issue_date' => $this->input->post('pest_lic_issue_date'), 'pest_lic_expiry_date' => $this->input->post('pest_lic_expiry_date'),  'have_seeds_license_no' => $this->input->post('have_seeds_license_no'), 'seeds_license_no' => $this->input->post('seeds_license_no'), 'seeds_lic_issue_date' => $this->input->post('seeds_lic_issue_date'), 'seeds_lic_expiry_date' => $this->input->post('seeds_lic_expiry_date'), 'have_agri_serv_license_no' => $this->input->post('have_agri_serv_license_no'), 'agri_serv_license_no' => $this->input->post('agri_serv_license_no'), 'agri_serv_lic_expiry_date' => $this->input->post('agri_serv_lic_expiry_date'), 'have_agri_equip_license_no' => $this->input->post('have_agri_equip_license_no'), 'agri_equip_license_no' => $this->input->post('agri_equip_license_no'), 'agri_equip_lic_expiry_date' => $this->input->post('agri_equip_lic_expiry_date'), 'is_finish' => $this->input->post('is_finish')];
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


                if (!empty($_FILES['fertilizer_license_photo']['name']) && isset($_FILES['fertilizer_license_photo']['name'])) {
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
                        if (isset($images_new_name_arr) && !empty($images_new_name_arr || !$this->form_validation->run())) {
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


                if (!empty($images_new_name_arr)) {
                    $seller_data = ['fertilizer_license_photo' => $images_new_name_arr[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
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


                if (!empty($_FILES['fertilizer_license_pdf']['name']) && isset($_FILES['fertilizer_license_pdf']['name'])) {
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
                        if (isset($pdf_new_name_arr) && !empty($pdf_new_name_arr || !$this->form_validation->run())) {
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


                if (!empty($pdf_new_name_arr)) {
                    $seller_data = ['fertilizer_license_pdf' => $pdf_new_name_arr[0]];
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


                if (!empty($_FILES['pesticide_license_photo']['name']) && isset($_FILES['pesticide_license_photo']['name'])) {
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
                        if (isset($images_new_name_arr2) && !empty($images_new_name_arr2 || !$this->form_validation->run())) {
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


                if (!empty($images_new_name_arr2)) {
                    $seller_data = ['pesticide_license_photo' => $images_new_name_arr2[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
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


                if (!empty($_FILES['pesticide_license_pdf']['name']) && isset($_FILES['pesticide_license_pdf']['name'])) {
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
                        if (isset($pdfs_new_name_arr2) && !empty($pdfs_new_name_arr2 || !$this->form_validation->run())) {
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


                if (!empty($pdfs_new_name_arr2)) {
                    $seller_data = ['pesticide_license_pdf' => $pdfs_new_name_arr2[0]];
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


                if (!empty($_FILES['seeds_license_photo']['name']) && isset($_FILES['seeds_license_photo']['name'])) {
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
                        if (isset($images_new_name_arr3) && !empty($images_new_name_arr3 || !$this->form_validation->run())) {
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


                if (!empty($images_new_name_arr3)) {
                    $seller_data = ['seeds_license_photo' => $images_new_name_arr3[0]];
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


                if (!empty($_FILES['o_form_photo']['name']) && isset($_FILES['o_form_photo']['name'])) {
                    $other_image_cnt4 = count($_FILES['o_form_photo']['name']);
                    $other_img4 = $this->upload;
                    $other_img4->initialize($config4);


                    if (!empty($_FILES['o_form_photo']['name'])) {

                        $_FILES['temp_image']['name'] = $files4['o_form_photo']['name'];
                        $_FILES['temp_image']['type'] = $files4['o_form_photo']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files4['o_form_photo']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files4['o_form_photo']['error'];
                        $_FILES['temp_image']['size'] = $files4['o_form_photo']['size'];
                        if (!$other_img4->do_upload('temp_image')) {
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
                        if (isset($images_new_name_arr4) && !empty($images_new_name_arr4 || !$this->form_validation->run())) {
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


                if (!empty($images_new_name_arr4)) {
                    $seller_data = ['o_form_photo' => $images_new_name_arr4[0]];
                    $seller_data = escape_array($seller_data);
                    $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
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


                if (!empty($_FILES['seeds_license_pdf']['name']) && isset($_FILES['seeds_license_pdf']['name'])) {
                    $other_pdf_cnt3 = count($_FILES['seeds_license_pdf']['name']);
                    $other_pdf3 = $this->upload;
                    $other_pdf3->initialize($config3);


                    if (!empty($_FILES['seeds_license_pdf']['name'])) {

                        $_FILES['temp_image']['name'] = $files3['seeds_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files3['seeds_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files3['seeds_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files3['seeds_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files3['seeds_license_pdf']['size'];
                        if (!$other_pdf3->do_upload('temp_image')) {
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
                        if (isset($pdfs_new_name_arr3) && !empty($pdfs_new_name_arr3 || !$this->form_validation->run())) {
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


                if (!empty($pdfs_new_name_arr3)) {
                    $seller_data = ['seeds_license_pdf' => $pdfs_new_name_arr3[0]];
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


                if (!empty($_FILES['agri_serv_license_photo']['name']) && isset($_FILES['agri_serv_license_photo']['name'])) {
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
                        if (isset($images_new_name_arr5) && !empty($images_new_name_arr5 || !$this->form_validation->run())) {
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


                if (!empty($images_new_name_arr5)) {
                    $retailer_data = ['agri_serv_license_photo' => $images_new_name_arr5[0]];
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


                if (!empty($_FILES['agri_serv_license_pdf']['name']) && isset($_FILES['agri_serv_license_pdf']['name'])) {
                    $other_pdf_cnt5 = count($_FILES['agri_serv_license_pdf']['name']);
                    $other_pdf5 = $this->upload;
                    $other_pdf5->initialize($config5);


                    if (!empty($_FILES['agri_serv_license_pdf']['name'])) {

                        $_FILES['temp_image']['name'] = $files5['agri_serv_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files5['agri_serv_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files5['agri_serv_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files5['agri_serv_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files5['agri_serv_license_pdf']['size'];
                        if (!$other_pdf5->do_upload('temp_image')) {
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
                        if (isset($pdfs_new_name_arr5) && !empty($pdfs_new_name_arr5 || !$this->form_validation->run())) {
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


                if (!empty($pdfs_new_name_arr5)) {
                    $retailer_data = ['agri_serv_license_pdf' => $pdfs_new_name_arr5[0]];
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


                if (!empty($_FILES['agri_equip_license_photo']['name']) && isset($_FILES['agri_equip_license_photo']['name'])) {
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
                        if (isset($images_new_name_arr6) && !empty($images_new_name_arr6 || !$this->form_validation->run())) {
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


                if (!empty($images_new_name_arr6)) {
                    $retailer_data = ['agri_equip_license_photo' => $images_new_name_arr6[0]];
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


                if (!empty($_FILES['agri_equip_license_pdf']['name']) && isset($_FILES['agri_equip_license_pdf']['name'])) {
                    $other_pdf_cnt6 = count($_FILES['agri_equip_license_pdf']['name']);
                    $other_pdf6 = $this->upload;
                    $other_pdf6->initialize($config6);


                    if (!empty($_FILES['agri_equip_license_pdf']['name'])) {

                        $_FILES['temp_image']['name'] = $files6['agri_equip_license_pdf']['name'];
                        $_FILES['temp_image']['type'] = $files6['agri_equip_license_pdf']['type'];
                        $_FILES['temp_image']['tmp_name'] = $files6['agri_equip_license_pdf']['tmp_name'];
                        $_FILES['temp_image']['error'] = $files6['agri_equip_license_pdf']['error'];
                        $_FILES['temp_image']['size'] = $files6['agri_equip_license_pdf']['size'];
                        if (!$other_pdf6->do_upload('temp_image')) {
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
                        if (isset($pdfs_new_name_arr6) && !empty($pdfs_new_name_arr6 || !$this->form_validation->run())) {
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


                if (!empty($pdfs_new_name_arr6)) {
                    $retailer_data = ['agri_equip_license_pdf' => $pdfs_new_name_arr6[0]];
                    $retailer_data = escape_array($retailer_data);
                    $this->db->set($retailer_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');
                }

                $this->response['redirect_to'] = ''; // base_url('my-account/license-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'License Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            }
        }
    }

    public function business_card($is_seller = 0)
    {
        if (!$this->ion_auth->logged_in()) {
            redirect(base_url());
        } else {
            $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->data['user_id']   = $this->ion_auth->get_user_id();

            if ($is_seller) {
                $this->data['seller_data'] = $this->db->get_where('seller_data', array('user_id' => $this->data['user_id']))->row();
            } else {
                $this->data['seller_data'] = $this->db->get_where('retailer_data', array('user_id' => $this->data['user_id']))->row();
            }

            $this->data['is_seller'] = $is_seller;
            $this->data['main_page'] = 'business_card';
            $this->data['title'] = 'Business Card | ' . $this->data['web_settings']['site_title'];

            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
    }

    public function save_step8()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect(base_url());
        } else {
            $is_seller = $this->input->post('is_seller');

            if ($is_seller) {
                /*$seller_data = ['company_name' => $this->input->post('company_name'), 'brand_name_1' => $this->input->post('brand_name_1'), 'brand_name_2' => $this->input->post('brand_name_2'), 'brand_name_3' => $this->input->post('brand_name_3'), 'plot_no' => $this->input->post('plot_no'), 'street_locality' => $this->input->post('street_locality'), 'landmark' => $this->input->post('landmark'), 'pin' => $this->input->post('pin'), 'city' => $this->input->post('city'), 'state' => $this->input->post('state')];
                $seller_data = escape_array($seller_data);
                $this->db->set($seller_data)->where('user_id', $this->input->post('user_id'))->update('seller_data');
                */
                $this->response['redirect_to'] = ''; // base_url('my-account/bank-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Business Details Updated Succesfully';
                echo json_encode($this->response);
                return false;
            } else {
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
                if (isset($business_card_front_files['business_card_front']) && !empty($business_card_front_files['business_card_front']['name']) && isset($business_card_front_files['business_card_front']['name'])) {
                    $other_img = $this->upload;
                    $other_img->initialize($config);

                    if (isset($business_card_front_files['business_card_front']) && !empty($business_card_front_files['business_card_front']['name']) && isset($_POST['old_business_card_front']) && !empty($_POST['old_business_card_front'])) {
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
                } else {
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
                if (isset($business_card_back_files['business_card_back']) && !empty($business_card_back_files['business_card_back']['name']) && isset($business_card_back_files['business_card_back']['name'])) {
                    $other_img = $this->upload;
                    $other_img->initialize($config);

                    if (isset($business_card_back_files['business_card_back']) && !empty($business_card_back_files['business_card_back']['name']) && isset($_POST['old_business_card_back']) && !empty($_POST['old_business_card_back'])) {
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
                } else {
                    $business_card_back_doc = $this->input->post('old_business_card_back', true);
                }

                $retailer_data = ['business_card_front' => $business_card_front_doc, 'business_card_back' => $business_card_back_doc];
                $retailer_data = escape_array($retailer_data);
                $this->db->set($retailer_data)->where('user_id', $this->input->post('user_id'))->update('retailer_data');

                $this->response['redirect_to'] = ''; // base_url('my-account/bank-details/'.$is_seller);
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
            if ($condition == 1) {
                $status = array('received', 'payment_demand', 'payment_ack', 'schedule_delivery', 'send_payment_confirmation',);
            } else if ($condition == 2) {
                $status = array('send_invoice');
            } else if ($condition == 3) {
                $status = array("complaint", "complaint_msg");
            } else if ($condition == 4) {
                $status = array("cancelled");
            } else if ($condition == 5) {
                $status = array("delivered", "send_mfg_payment_ack", "send_mfg_payment_confirmation");
            } else if ($condition == 6) {
                $status = array('payment_demand', 'send_payment_confirmation', 'send_invoice', 'complaint_msg');
            }

            $this->data['condition'] = $condition;

            $user_id = $this->ion_auth->get_user_id();
            $this->data['orders_total_amt'] = 0;
            $this->data['total_orders'] = 0;
            $this->data['in_process_orders'] = 0;
            $this->data['shipped_orders'] = 0;
            $this->data['issue_raised_orders'] = 0;
            $this->data['cancelled_orders'] = 0;
            $this->data['delivered_orders'] = 0;



            $this->data['retailer_data']        = $this->db->get_where('retailer_data', array('user_id' => $user_id))->row_array();
            $this->data['orders_total_amt']     = retailer_orders_total($user_id);
            $this->data['total_orders']         = retailer_orders_count("", $user_id);
            $this->data['in_process_orders']    = retailer_orders_count(array('received', 'payment_demand', 'payment_ack', 'schedule_delivery', 'send_payment_confirmation',), $user_id);
            $this->data['shipped_orders']       = retailer_orders_count("send_invoice", $user_id);
            $this->data['issue_raised_orders']  = retailer_orders_count(array("complaint", "complaint_msg"), $user_id);
            $this->data['cancelled_orders']     = retailer_orders_count("cancelled", $user_id);
            $this->data['delivered_orders']     = retailer_orders_count(array("delivered", "send_mfg_payment_ack", "send_mfg_payment_confirmation"), $user_id);


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
            if ($condition == 1) {
                $status = array('received', 'payment_demand', 'payment_ack', 'schedule_delivery', 'send_payment_confirmation',);
            } else if ($condition == 2) {
                $status = array('send_invoice');
            } else if ($condition == 3) {
                $status = array("complaint", "complaint_msg");
            } else if ($condition == 4) {
                $status = array("cancelled");
            } else if ($condition == 5) {
                $status = array("delivered", "send_mfg_payment_ack", "send_mfg_payment_confirmation");
            } else if ($condition == 6) {
                $status = array('payment_demand', 'send_payment_confirmation', 'send_invoice', 'complaint_msg');
            }

            return $this->order_model->get_order_list($this->data['user']->id, $status);
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function orderitemdetail($order_item_id = 0, $order_id = 0)
    {
        if ($this->ion_auth->logged_in()) {

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
            if ($order['order_data'][0]['payment_method'] == "Bank Transfer") {
                $bank_transfer = fetch_details(['order_id' => $order['order_data'][0]['id']], 'order_bank_transfer');
            }
            if (!empty($order)) {
                $orderState = fetch_details(["id" => $order["order_data"][0]["address_id"]], "addresses");
                $userdetails = fetch_details(["id" => $order["order_data"][0]["user_id"]], "users");
                if (!empty($orderState)) {
                    $order["order_data"][0]["state"] = $orderState[0]["state"];
                }
                if (!empty($userdetails)) {
                    $order["order_data"][0]["email"] = $userdetails[0]["email"];
                }
            }
            $order_items = $this->common_model->getRecords("order_items", '', '', 'id desc', 1);
            $receipt_no =  "RC00" . ($order_items[0]["id"] + 1);

            $this->data['receipt_no'] = $receipt_no;



            $this->data['bank_transfer'] = $bank_transfer;

            $this->data['order'] = $order['order_data'][0];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    public function order_query()
    {

        if ($this->ion_auth->logged_in()) {
            $bank_transfer = array();
            $this->data['main_page'] = 'order-query';
            $this->data['title'] = 'Orders | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Orders, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Orders | ' . $this->data['web_settings']['meta_description'];
            $order_id = $this->uri->segment(3);
            $order = fetch_orders($order_id, NULL, false, false, 1, NULL, NULL, NULL, NULL);
            $orderQuery = $this->common_model->getRecords("tbl_order_queries", "*", array("order_id" => $order_id));
            $this->data["order_id"] = $order_id;
            $this->data["orderQuery"] = $orderQuery;
            $this->data["user_id"] = $this->session->userdata('user_id');
            if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller()) {
                $this->data["to_user_id"] = $order['order_data'][0]['user_id'];
            } else {
                $this->data["to_user_id"] = $order['order_data'][0]['seller_id'];
            }
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        }
    }
    public function add_order_query()
    {
        $postdata = $this->input->post();
        $queryArr["order_id"] = $postdata["order_id"];
        $queryArr["from_user_id"] = $postdata["from_user_id"];
        $queryArr["to_user_id"] = $postdata["to_user_id"];
        $queryArr["message"] = $postdata["message"];
        $ins_purchase_details = $this->db->insert("tbl_order_queries", $queryArr);
        $addquery = $this->db->insert_id();
        if ($addquery) {
            $response["status"] = true;
            $response["message"] = "Data added successfully";
        } else {
            $response["status"] = false;
            $response["message"] = "Please try again later";
        }
        echo json_encode($response);
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
            if (trim($_POST['status']) != 'returned' && $this->response['error'] == false) {
                process_refund($_POST['order_item_id'], trim($_POST['status']), 'order_items');
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

    public function get_address_list($type = '')
    {
        if ($this->ion_auth->logged_in()) {
            return $this->address_model->get_address_list($this->data['user']->id, $type);
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
            $areas = fetch_details(['id' => $area_id], 'areas', 'zipcode_id');
            $zipcodes = fetch_details(['id' => $areas[0]['zipcode_id']], 'zipcodes', 'zipcode');
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
        if ($this->ion_auth->logged_in()) {
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
                if ($this->Order_model->add_complaint($data)) {

                    if ($order_item_id) {
                        $order_item_info = $this->db->get_where('order_items', array('id' => $order_item_id))->row_array();

                        $status = json_decode(stripallslashes($order_item_info['status']));
                        array_push($status, array('complaint', date('d-m-Y h:i:sa')));

                        $order_item_up = ['active_status' => 'complaint', 'status' => json_encode($status)];
                        $order_item_up = escape_array($order_item_up);
                        $this->db->set($order_item_up)->where('id', $order_item_id)->update('order_items');
                    } else {
                        $this->db->select('a.id, a.status, a.active_status');
                        $this->db->from('order_items as a');
                        $this->db->where('a.order_id', $order_id);
                        $this->db->where_not_in('a.active_status', array('delivered', 'cancelled'));
                        $query = $this->db->get();
                        $order_items_info = $query->result_array();

                        if ($order_items_info) {
                            foreach ($order_items_info as $order_item_info) {
                                $status = json_decode(stripallslashes($order_item_info['status']));
                                if ($status != null) {
                                    array_push($status, array('complaint', date('d-m-Y h:i:sa')));
                                } else {
                                    $status =  array(array('complaint', date("d-m-Y h:i:sa")));
                                }

                                $update_item_data = array('active_status' => 'complaint', 'status' => json_encode($status));
                                update_details($update_item_data, ['id' => $order_item_info['id']], 'order_items');
                            }
                        }
                    }

                    $this->db->update('orders', array('order_status' => 'complaint'), array('id' => $order_id));

                    $system_settings = get_settings('system_settings', true);
                    $order      = fetch_details(['id' => $order_id], 'orders');
                    $this->db->select('a.id, a.seller_id, a.status, a.active_status');
                    $this->db->from('order_items as a');
                    $this->db->where('a.order_id', $order_id);

                    $query = $this->db->get();
                    $order_items_info = $query->result_array();
                    $user_id    = $order[0]['user_id'];
                    $user = fetch_details(['id' => $order[0]['user_id']], 'users');

                    $retailer_store_name = fetch_details(['user_id' => $user_id], 'retailer_data', 'company_name');
                    $retailer_store_name = $retailer_store_name[0]['company_name'];

                    $seller_email = fetch_details(['id' => $order_items_info[0]['seller_id']], 'users', 'email');
                    $seller_store_name = fetch_details(['user_id' => $order_items_info[0]['seller_id']], 'seller_data', 'company_name');
                    $seller_store_name = $seller_store_name[0]['company_name'];

                    $this->db->select('*');
                    $this->db->from('order_item_complaints');
                    $this->db->where('order_id', $order_id);
                    $query = $this->db->get();
                    $order_item_complaints = $query->result_array();

                    if ($order_item_complaints) {
                        $complaint_text = '<table border="1" class="es-content-body" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:100%;margin-top:15px;margin-bottom:0px;">
                                           <tr  bgcolor="#efefef" style="Margin:0;padding-top:10px;padding-bottom:10px;background-color:#EFEFEF">
                                                <th style="width: 8%;">Sr. No.</th>
                                                <th>Your Concern</th>
                                                <th style="width: 18%;">Image</th>
                                            </tr>';
                        $i_count = 1;
                        foreach ($order_item_complaints as $order_item_complaint) {
                            $complaint_text .= '<tr class="bg-white text-dark">
                                                    <td align="center">' . $i_count . '</td>
                                                    <td>' . $order_item_complaint['concern'] . '</td>
                                                    <td>';

                            if (file_exists($order_item_complaint['attachments']) && $order_item_complaint['attachments']) {
                                $complaint_text .= '<a href="' . base_url() . $order_item_complaint['attachments'] . '" target="_blank">
                                    <img src="' . base_url() . $order_item_complaint['attachments'] . '" alt="" style="width: 100px;" />
                                </a>';
                            }

                            $complaint_text .= '</td></tr>';

                            $i_count++;
                        }
                        $complaint_text .= '</table>';
                    }

                    if ($user[0]['email'] != '') {
                        $html_text  = '<p>Dear ' . ucfirst($retailer_store_name);
                        $html_text .= '<p style="margin-bottom:0px;">You have raised an issue report regarding order #HC-A' . $order_id . '. According to your report, there have been discrepancies in the fulfillment of this order.</p>';
                        $html_text .= '<p>Kindly check the issue status of this order below.</p>';
                        $html_text .= $complaint_text;


                        $note_text  = '<p style="margin-top:0px;">We take this matter seriously and are committed to resolving it promptly and to the satisfaction of our valued retailer. Our top priority is to ensure that our retailers receive the correct and undamaged products they have ordered.</p>';
                        $note_text .= '<p>We understand the importance of resolving these issues promptly and maintaining a strong and reliable partnership with our retailers and manufacturers. To address this matter, we have initiated the following steps:</p>';
                        $note_text .= '<p><b>1. Investigation:</b> We will conduct a thorough investigation to determine the root cause of these issues and prevent them from happening in the future.</p>';
                        $note_text .= '<p><b>2. Communication:</b> Our team will reach out to you to gather more details about the problems they have encountered and work closely with them to understand the full extent of the issue.</p>';
                        $note_text .= '<p><b>3. Resolution:</b> We are committed to resolving these issues within 5 working days.</p>';
                        $note_text .= '<p><b>4. Preventive Measures:</b> We will implement measures to prevent similar issues from occurring in future orders.</p>';
                        $note_text .= '<p>Your cooperation in addressing and resolving this matter is highly appreciated. If you have any specific information or recommendations regarding this issue, please feel free to share them with us. We will keep you updated on the progress of this issue resolution.</p>';

                        $order_info = array(
                            'subject'           => 'Order #HC-A' . $order_id . ' Order Issue',
                            'user_msg'          => $html_text,
                            'note'              => $note_text,
                        );
                        send_mail2($user[0]['email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    }

                    if ($seller_email[0]['email'] != '') {
                        $html_text  = '<p>Dear ' . ucfirst($seller_store_name);
                        $html_text .= '<p style="margin-bottom:0px;">We have received an issue report regarding order #HC-A' . $order_id . ' from our valued retailer, ' . ucfirst($retailer_store_name) . '. According to their report, there have been discrepancies in the fulfillment of this order.</p>';
                        $html_text .= '<p>Kindly check the issue status of this order below.</p>';
                        $html_text .= $complaint_text;


                        $note_text  = '<p style="margin-top:0px;">We take this matter seriously and are committed to resolving it promptly and to the satisfaction of our valued retailer. Our top priority is to ensure that our retailers receive the correct and undamaged products they have ordered.</p>';
                        $note_text .= '<p>We understand the importance of resolving these issues promptly and maintaining a strong and reliable partnership with our retailers. To address this matter, we have initiated the following steps:</p>';
                        $note_text .= '<p><b>1. Investigation:</b> We will conduct a thorough investigation to determine the root cause of these issues and prevent them from happening in the future.</p>';
                        $note_text .= '<p><b>2. Communication:</b> Our team will reach out to Retailer to gather more details about the problems they have encountered and work closely with them to understand the full extent of the issue.</p>';
                        $note_text .= '<p><b>3. Resolution:</b> We are committed to resolving these issues within 5 working days to the satisfaction of Retailer.</p>';
                        $note_text .= '<p><b>4. Preventive Measures:</b> We will implement measures to prevent similar issues from occurring in future orders.</p>';
                        $note_text .= '<p>Your cooperation in addressing and resolving this matter is highly appreciated. If you have any specific information or recommendations regarding this issue, please feel free to share them with us. We will keep you updated on the progress of this issue resolution.</p>';

                        $order_info = array(
                            'subject'           => 'Order #HC-A' . $order_id . ' Order Issue',
                            'user_msg'          => $html_text,
                            'note'              => $note_text,
                        );
                        send_mail2($seller_email[0]['email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    }

                    if (isset($system_settings['support_email']) && !empty($system_settings['support_email'])) {
                        $html_text  = '<p>Dear Admin';
                        $html_text .= '<p style="margin-bottom:0px;">We have received an issue report regarding order #HC-A' . $order_id . ' from our valued retailer, ' . ucfirst($retailer_store_name) . '. According to their report, there have been discrepancies in the fulfillment of this order.</p>';
                        $html_text .= '<p>Kindly check the issue status of this order below.</p>';
                        $html_text .= $complaint_text;

                        $note_text  = '<p style="margin-top:0px;">Kindly connect with Retailer and manufacturer to find out the reason for issue occurred and try to resolve it.</p>';

                        $order_info = array(
                            'subject'           => 'Order #HC-A' . $order_id . ' Order Issue',
                            'user_msg'          => $html_text,
                            'note'              => $note_text,
                        );
                        send_mail2($system_settings['support_email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    }

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
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }


    function send_service_status()
    {
        if ($this->ion_auth->logged_in()) {
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

                if (empty($order)) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Order not found!";
                    $this->response['data'] = [];
                    print_r(json_encode($this->response));
                    return false;
                }


                $this->db->update('orders', array('order_status' => $status, 'last_updated' => date('Y-m-d H:i:s')), array('id' => $order_id));

                $order_item_stages = array('order_id' => $order_id, 'status' => $status,);
                $this->db->insert('order_item_stages', $order_item_stages);

                $this->db->select('a.id, a.seller_id, a.status, a.active_status');
                $this->db->from('order_items as a');
                $this->db->where('a.order_id', $order_id);
                $this->db->where_not_in('a.active_status', array('delivered', 'cancelled'));
                $query = $this->db->get();
                $order_items_info = $query->result_array();

                if ($order_items_info) {
                    foreach ($order_items_info as $order_item_info) {
                        $_status = json_decode(stripallslashes($order_item_info['status']));
                        if ($_status != null) {

                            array_push($_status, array($status, date('d-m-Y h:i:sa')));
                        } else {
                            $_status =  array(array($status, date("d-m-Y h:i:sa")));
                        }

                        $update_item_data = array('active_status' => $status, 'status' => json_encode($_status));
                        update_details($update_item_data, ['id' => $order_item_info['id']], 'order_items');
                    }
                }


                $this->response['redirect_to'] = ''; // base_url('my-account/bank-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Thanks for the update';
                $this->response['html']    = $status;
                $this->response['order_id'] = $order_id;
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

    function send_order_status()
    {
        if ($this->ion_auth->logged_in()) {
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
                $add_status = $this->input->post('add_status');

                $order      = fetch_details(['id' => $order_id], 'orders', 'id');

                if (empty($order)) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Order not found!";
                    $this->response['data'] = [];
                    print_r(json_encode($this->response));
                    return false;
                }

                if ($add_status == 'issue_resolved') {
                    $this->db->update('orders', array('order_status' => $add_status, 'last_updated' => date('Y-m-d H:i:s')), array('id' => $order_id));

                    $order_item_stages = array('order_id' => $order_id, 'status' => $add_status,);
                    $this->db->insert('order_item_stages', $order_item_stages);

                    $this->db->select('a.id, a.status, a.active_status');
                    $this->db->from('order_items as a');
                    $this->db->where('a.order_id', $order_id);
                    $this->db->where_not_in('a.active_status', array('delivered', 'cancelled'));
                    $query = $this->db->get();
                    $order_items_info = $query->result_array();

                    if ($order_items_info) {
                        foreach ($order_items_info as $order_item_info) {
                            $_status = json_decode(stripallslashes($order_item_info['status']));
                            if ($_status != null) {

                                array_push($_status, array($add_status, date('d-m-Y h:i:sa')));
                            } else {
                                $_status =  array(array($add_status, date("d-m-Y h:i:sa")));
                            }

                            $update_item_data = array('active_status' => $add_status, 'status' => json_encode($_status));
                            update_details($update_item_data, ['id' => $order_item_info['id']], 'order_items');
                        }
                    }
                }

                $this->db->update('orders', array('order_status' => $status, 'last_updated' => date('Y-m-d H:i:s')), array('id' => $order_id));

                $order_item_stages = array('order_id' => $order_id, 'status' => $status,);
                $this->db->insert('order_item_stages', $order_item_stages);

                $this->db->select('a.id, a.seller_id, a.status, a.active_status');
                $this->db->from('order_items as a');
                $this->db->where('a.order_id', $order_id);
                $this->db->where_not_in('a.active_status', array('delivered', 'cancelled'));
                $query = $this->db->get();
                $order_items_info = $query->result_array();

                if ($order_items_info) {
                    foreach ($order_items_info as $order_item_info) {
                        $_status = json_decode(stripallslashes($order_item_info['status']));
                        if ($_status != null) {

                            array_push($_status, array($status, date('d-m-Y h:i:sa')));
                        } else {
                            $_status =  array(array($status, date("d-m-Y h:i:sa")));
                        }

                        $update_item_data = array('active_status' => $status, 'status' => json_encode($_status));
                        update_details($update_item_data, ['id' => $order_item_info['id']], 'order_items');
                    }
                }

                if ($status == 'delivered') {
                    $system_settings = get_settings('system_settings', true);
                    $order      = fetch_details(['id' => $order_id], 'orders');
                    $user_id    = $order[0]['user_id'];
                    $user = fetch_details(['id' => $order[0]['user_id']], 'users');

                    $retailer_store_name = fetch_details(['user_id' => $user_id], 'retailer_data', 'company_name');
                    $retailer_store_name = $retailer_store_name[0]['company_name'];

                    $seller_email = fetch_details(['id' => $order_items_info[0]['seller_id']], 'users', 'email');
                    $seller_store_name = fetch_details(['user_id' => $order_items_info[0]['seller_id']], 'seller_data', 'company_name');
                    $seller_store_name = $seller_store_name[0]['company_name'];

                    $this->db->select('id');
                    $this->db->from('order_item_stages');
                    $this->db->where('status', 'issue_resolved');
                    $this->db->where('order_id', $order_id);
                    $q = $this->db->get();
                    $rw = $q->row_array();

                    if ($rw['id']) {
                        //retailer
                        if ($user[0]['email'] != '') {
                            $html_text  = '<p>Hello ' . ucfirst($retailer_store_name) . ',</p>';
                            $html_text .= '<p style="text-indent:50px;margin-bottom: 0;">Thank you for choosing Happycrop for your recent order. We are delighted to inform you that issue related to order has been successfully resolved, and we hope you are satisfied with the service.</p>';
                            $html_text .= '<p style="text-indent:50px;margin-bottom: 0;">Your patience and cooperation throughout this process were greatly appreciated.</p>';
                            $html_text .= '<p style="text-indent:50px;margin-bottom: 0;">To better understand your experience and to continuously improve our services, we kindly request your feedback. Your opinion matters to us, and we value your thoughts. Taking a few moments to write a review about your purchase would be greatly appreciated.</p>';

                            $order_info = array(
                                'subject'    => 'Order #HC-A' . $order_id . ' - Updates from Happycrop',
                                'user_msg' => $html_text,
                            );

                            send_mail2($user[0]['email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                        }

                        //seller
                        if ($seller_email[0]["email"] != '') {
                            $html_text  = '<p>Hello ' . ucfirst($seller_store_name) . ',</p>';
                            $html_text .= '<p style="text-indent:50px;margin-bottom: 0;"><b>We are delighted to inform you that issue related to order has been successfully resolved. The payment release will be initiated by Happycrop within 48 hrs. We hope you are satisfied with the experience of Happycrop platform.</b></p>';
                            $html_text .= '<p style="text-indent:50px;margin-bottom: 0;"><b>Your patience and cooperation throughout this process were greatly appreciated.</b></p>';
                            $html_text .= '<p style="text-indent:50px;margin-bottom: 0;">To better understand your experience and to continuously improve our services, we kindly request your feedback. Your opinion matters to us, and we value your thoughts. Taking a few moments to write a review about your recent sales  would be greatly appreciated.</p>';

                            $order_info = array(
                                'subject'    => 'Order #HC-A' . $order_id . ' - Updates from Happycrop',
                                'user_msg' => $html_text,
                            );

                            send_mail2($seller_email[0]["email"], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                        }

                        //admin
                        if (isset($system_settings['support_email']) && !empty($system_settings['support_email'])) {
                            $html_text  = '<p><b>Hello Admin,</b></p>';
                            $html_text .= '<p style="text-indent:50px;"><b>The order from retailer - ' . ucfirst($retailer_store_name) . '</b> has been successfully delivered.</p>';
                            $html_text .= '<p style="text-indent:50px;margin-bottom: 0;">Once order get delivered, Happycrop must observe that retailer closing the order by acknowledging its completion. This helps us maintain accurate records and ensures that our systems are up to date. By closing the order, Happycrop should  confirm through call that retailer have received the materials correctly and are satisfied with the purchase.</p>';

                            $order_info = array(
                                'subject'    => 'Order #HC-A' . $order_id . ' - Updates from Happycrop',
                                'user_msg' => $html_text,
                            );

                            send_mail2($system_settings['support_email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                        }
                    } else {
                        //retailer
                        if ($user[0]['email'] != '') {
                            $html_text  = '<p>Hello ' . ucfirst($retailer_store_name) . ',</p>';
                            $html_text .= '<p style="text-indent:50px;">Thank you for choosing  Happycrop for your recent order. We are delighted to inform you that your order has been successfully delivered, and we hope you are satisfied with the materials you received.</p>';
                            $html_text .= '<p style="text-indent:50px;margin-bottom: 0;">To better understand your experience and to continuously improve our services, we kindly request your feedback. Your opinion matters to us, and we value your thoughts. Taking a few moments to write a review about your purchase would be greatly appreciated.</p>';

                            $order_info = array(
                                'subject'    => 'Order #HC-A' . $order_id . ' - Updates from Happycrop',
                                'user_msg' => $html_text,
                            );

                            send_mail2($user[0]['email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                        }

                        //seller
                        if ($seller_email[0]["email"] != '') {
                            $html_text  = '<p>Hello ' . ucfirst($seller_store_name) . ',</p>';
                            $html_text .= '<p style="text-indent:50px;"><b>Thank you for choosing Happycrop for your recent order. We are delighted to inform you that your order has been successfully delivered. The payment release will be initiated by Happycrop within 48 hrs. We hope you are satisfied with the experience of Happycrop platform.</b></p>';
                            $html_text .= '<p style="text-indent:50px;margin-bottom: 0;">To better understand your experience and to continuously improve our services, we kindly request your feedback. Your opinion matters to us, and we value your thoughts. Taking a few moments to write a review about your recent sales  would be greatly appreciated.</p>';

                            $order_info = array(
                                'subject'    => 'Order #HC-A' . $order_id . ' - Updates from Happycrop',
                                'user_msg' => $html_text,
                            );

                            send_mail2($seller_email[0]["email"], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                        }

                        //admin
                        if (isset($system_settings['support_email']) && !empty($system_settings['support_email'])) {
                            $html_text  = '<p><b>Hello Admin,</b></p>';
                            $html_text .= '<p style="text-indent:50px;"><b>The order from retailer - ' . ucfirst($retailer_store_name) . '</b> has been successfully delivered.</p>';
                            $html_text .= '<p style="text-indent:50px;margin-bottom: 0;">Once order get delivered, Happycrop must observe that retailer closing the order by acknowledging its completion. This helps us maintain accurate records and ensures that our systems are up to date. By closing the order, Happycrop should  confirm through call that retailer have received the materials correctly and are satisfied with the purchase.</p>';

                            $order_info = array(
                                'subject'    => 'Order #HC-A' . $order_id . ' - Updates from Happycrop',
                                'user_msg' => $html_text,
                            );

                            send_mail2($system_settings['support_email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                        }
                    }
                }

                $this->response['redirect_to'] = ''; // base_url('my-account/bank-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Thanks for the update';
                $this->response['html']    = $status;
                $this->response['order_id'] = $order_id;
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

    function approveBulkQty()
    {
        if ($this->ion_auth->logged_in()) {
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

                $order_stage_info = $this->db->get_where('order_item_stages', array('id' => $order_stage_id))->row_array();
                $order_item_ids   = explode(",", $order_stage_info['ids']);

                if ($order_item_ids) {
                    foreach ($order_item_ids as $order_item_id) {
                        $order_item_info = $this->db->get_where('order_items', array('id' => $order_item_id))->row_array();

                        $status = json_decode(stripallslashes($order_item_info['status']));

                        if ($status != null) {
                            array_push($status, array('qty_approved', date('d-m-Y h:i:sa')));
                        } else {
                            $status =  array(array('qty_approved', date("d-m-Y h:i:sa")));
                        }

                        $order_item = ['active_status' => 'qty_approved', 'status' => json_encode($status)];
                        $order_item = escape_array($order_item);
                        $this->db->set($order_item)->where('id', $order_item_id)->update('order_items');
                    }

                    $order_item_stages = array('order_id' => $order_id, 'order_item_id' => 0, 'ids' => implode(',', $order_item_ids), 'status' => 'qty_approved',);
                    $this->db->insert('order_item_stages', $order_item_stages);

                    $update_data = array('order_status' => 'qty_approved');
                    update_details($update_data, ['id' => $order_id], 'orders');

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

                    $this->response['redirect_to'] = ''; // base_url('my-account/bank-details/'.$is_seller);
                    $this->response['error'] = false;
                    $this->response['message'] = 'Thanks for approval';
                    $this->response['html']    = 'qty_approved';
                    //$this->response['order_item_id']= $order_item_id;
                    echo json_encode($this->response);
                    return false;
                }
            }
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    function approveQty()
    {
        if ($this->ion_auth->logged_in()) {
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

                if ($status != null) {
                    array_push($status, array('qty_approved', date('d-m-Y h:i:sa')));
                } else {
                    $status =  array(array('qty_approved', date("d-m-Y h:i:sa")));
                }

                $order_item = ['active_status' => 'qty_approved', 'status' => json_encode($status)];
                $order_item = escape_array($order_item);
                $this->db->set($order_item)->where('id', $order_item_id)->update('order_items');

                $order_item_stages = array('order_id' => $order_id, 'order_item_id' => $order_item_id, 'status' => 'qty_approved',);
                $this->db->insert('order_item_stages', $order_item_stages);

                $update_data = array('order_status' => 'qty_approved');
                update_details($update_data, ['id' => $order_id], 'orders');

                $this->response['redirect_to'] = ''; // base_url('my-account/bank-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Thanks for approval';
                $this->response['html']    = 'qty_approved';
                $this->response['order_item_id'] = $order_item_id;
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
        if ($this->ion_auth->logged_in()) {
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

                $order_stage_info = $this->db->get_where('order_item_stages', array('id' => $order_stage_id))->row_array();
                //$order_item_ids   = explode(",",$order_stage_info['ids']);

                $order_items = $this->db->get_where('order_items', array('order_id' => $order_id))->result_array();
                $order_item_ids = array();
                if ($order_items) {
                    foreach ($order_items as $order_item) {
                        $order_item_ids[] = $order_item['id'];
                    }
                }

                if ($order_item_ids) {
                    foreach ($order_item_ids as $order_item_id) {
                        $order_item_info = $this->db->get_where('order_items', array('id' => $order_item_id))->row_array();

                        $status = json_decode(stripallslashes($order_item_info['status']));
                        if ($status != null) {
                            array_push($status, array('cancelled', date('d-m-Y h:i:sa')));
                        } else {
                            $status =  array(array('cancelled', date("d-m-Y h:i:sa")));
                        }

                        $order_item = ['active_status' => 'cancelled', 'status' => json_encode($status)];
                        $order_item = escape_array($order_item);
                        $this->db->set($order_item)->where('id', $order_item_id)->update('order_items');
                    }

                    $order_item_stages = array('order_id' => $order_id, 'order_item_id' => 0, 'ids' => implode(',', $order_item_ids), 'status' => 'qty_rejected',);
                    $this->db->insert('order_item_stages', $order_item_stages);

                    $this->db->select('a.id');
                    $this->db->from('order_items as a');
                    $this->db->where('a.active_status !=', 'cancelled');
                    //$this->db->where('a.id !=', $order_item_id);
                    $this->db->where('a.order_id', $order_id);
                    $query      = $this->db->get();
                    $results    = $query->result_array();

                    if (count($results) <= 0) {
                        $update_data = array('order_status' => 'cancelled');
                        update_details($update_data, ['id' => $order_id], 'orders');
                    }

                    $order    = fetch_details(['id' => $order_id], 'orders');
                    $order_r  = fetch_orders($order_id, $order[0]['user_id'], false, false, 1, NULL, NULL, NULL, NULL);
                    $this->db->select('a.id, a.seller_id, a.status, a.active_status');
                    $this->db->from('order_items as a');
                    $this->db->where('a.order_id', $order_id);

                    $query = $this->db->get();
                    $order_items_info = $query->result_array();

                    $system_settings = get_settings('system_settings', true);

                    $user_id    = $order[0]['user_id'];
                    $user = fetch_details(['id' => $order[0]['user_id']], 'users');

                    $retailer_store_name = fetch_details(['user_id' => $user_id], 'retailer_data', 'company_name');
                    $retailer_store_name = $retailer_store_name[0]['company_name'];

                    $seller_email = fetch_details(['id' => $order_items_info[0]['seller_id']], 'users', 'email');
                    $seller_store_name = fetch_details(['user_id' => $order_items_info[0]['seller_id']], 'seller_data', 'company_name');
                    $seller_store_name = $seller_store_name[0]['company_name'];

                    if ($user[0]['email'] != '') {
                        $html_text  = '<p>Dear ' . ucfirst($retailer_store_name) . ',</p>';
                        $html_text .= '<p>You have cancelled the order #HC-A' . $order_id . ', which was placed with us for the following item(s):</p>';

                        $note_text  = '<p>We understand the importance of fulfilling orders promptly and efficiently, and we sincerely apologize for any inconvenience this may cause.</p>';
                        $note_text .= '<p>We deeply regret this cancellation, as we value our partnership with you and the importance of meeting your requirements. Rest assured, we are taking proactive steps to prevent such occurrences in the future and to ensure smoother transactions moving forward.</p>';
                        $note_text .= '<p>Our customer care executive will connect with you shortly to understand the details of your concern and take necessary steps toward its resolution.</p>';

                        $order_info = array(
                            'order'             => $order_r['order_data'][0],
                            'subject'           => 'Order #HC-A' . $order_id . ' Order rejected by Retailer',
                            'user_msg'          => $html_text,
                            'note'              => $note_text,
                            'show_address_amt'  => false,
                        );
                        send_mail2($user[0]['email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    }

                    if ($seller_email[0]['email'] != '') {
                        $html_text  = '<p>Dear ' . ucfirst($seller_store_name) . ',</p>';
                        $html_text .= '<p>We regret to inform you that the retailer has decided to cancel order #HC-A' . $order_id . ', which was placed with us for the following item(s):</p>';

                        $note_text  = '<p>We understand the importance of fulfilling orders promptly and efficiently, and we sincerely apologize for any inconvenience this may cause. </p>';
                        $note_text .= '<p>We deeply regret this cancellation, as we value our partnership with you and the importance of meeting your requirements. Rest assured, we are taking proactive steps to prevent such occurrences in the future and to ensure smoother transactions moving forward.</p>';

                        $order_info = array(
                            'order'             => $order_r['order_data'][0],
                            'subject'           => 'Order #HC-A' . $order_id . ' Order rejected by Retailer',
                            'user_msg'          => $html_text,
                            'note'              => $note_text,
                            'show_address_amt'  => false,
                        );
                        send_mail2($seller_email[0]['email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    }

                    if (isset($system_settings['support_email']) && !empty($system_settings['support_email'])) {
                        $html_text  = '<p>Dear Admin,</p>';
                        $html_text .= '<p>We regret to inform you that the retailer has decided to cancel order #HC-A' . $order_id . ', which was placed with us for the following item(s):</p>';

                        $note_text  = '<p>Kindly connect with retailer to find the reason for cancellation of order and try to resolve it.</p>';

                        $order_info = array(
                            'order'             => $order_r['order_data'][0],
                            'subject'           => 'Order #HC-A' . $order_id . ' Order rejected by Retailer',
                            'user_msg'          => $html_text,
                            'note'              => $note_text,
                            'show_address_amt'  => false,
                        );
                        send_mail2($system_settings['support_email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    }

                    $this->response['redirect_to']  = ''; // base_url('my-account/bank-details/'.$is_seller);
                    $this->response['error']        = false;
                    $this->response['message']      = 'Thanks for the update';
                    $this->response['html']         = 'qty_rejected';
                    //$this->response['order_item_id']= $order_item_id;
                    echo json_encode($this->response);
                    return false;
                }
            }
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function rejectQty()
    {
        if ($this->ion_auth->logged_in()) {
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
                if ($status != null) {
                    array_push($status, array('cancelled', date('d-m-Y h:i:sa')));
                } else {
                    $status =  array(array('cancelled', date("d-m-Y h:i:sa")));
                }

                $order_item = ['active_status' => 'cancelled', 'status' => json_encode($status)];
                $order_item = escape_array($order_item);
                $this->db->set($order_item)->where('id', $order_item_id)->update('order_items');

                $order_item_stages = array('order_id' => $order_id, 'order_item_id' => $order_item_id, 'status' => 'qty_rejected',);
                $this->db->insert('order_item_stages', $order_item_stages);

                $this->db->select('a.id');
                $this->db->from('order_items as a');
                $this->db->where('a.active_status !=', 'cancelled');
                $this->db->where('a.id !=', $order_item_id);
                $this->db->where('a.order_id', $order_id);
                $query      = $this->db->get();
                $results    = $query->result_array();

                if (count($results) <= 0) {
                    $update_data = array('order_status' => 'cancelled');
                    update_details($update_data, ['id' => $order_id], 'orders');
                }

                $this->response['redirect_to']  = ''; // base_url('my-account/bank-details/'.$is_seller);
                $this->response['error']        = false;
                $this->response['message']      = 'Thanks for the update';
                $this->response['html']         = 'qty_rejected';
                $this->response['order_item_id'] = $order_item_id;
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
    public function payment_receipt($order_id, $view = "")
    {
        $order = fetch_orders($order_id, NULL, false, false, 1, NULL, NULL, NULL, NULL);
        $this->db->distinct();
        $this->db->select('a.seller_id, b.username, b.mobile, b.email, b.mfg_no, c.company_name, c.gst_no, c.fertilizer_license_no, c.pesticide_license_no, c.seeds_license_no, c.account_name, c.account_number, c.bank_name, c.bank_code, c.bank_city, c.bank_branch, c.bank_state, c.plot_no, c.street_locality, c.landmark, cc.name, city, s.name as state, c.pin');
        $this->db->from('order_items as a');
        $this->db->join('users as b', 'a.seller_id = b.id', 'left');
        $this->db->join('seller_data as c', 'a.seller_id = c.user_id', 'left');
        $this->db->join('states as s', 'c.state_id = s.id', 'left');
        $this->db->join('cities as cc', 'c.city_id = cc.id', 'left');
        $this->db->where('a.order_id', $order_id);
        $query = $this->db->get();

        $manufactures = $query->result_array();


        if (!empty($order)) {
            $orderState = fetch_details(["id" => $order["order_data"][0]["address_id"]], "addresses");
            $userdetails = fetch_details(["id" => $order["order_data"][0]["user_id"]], "users");
            if (!empty($orderState)) {
                $order["order_data"][0]["state"] = $orderState[0]["state"];
            }
            if (!empty($userdetails)) {
                $order["order_data"][0]["email"] = $userdetails[0]["email"];
            }
        }

        $pdfdata['manufacture'] = $manufactures[0];
        $pdfdata['order'] = $order['order_data'];
        $pdfdata['view'] = $view;
        $pdfdata['settings'] = $this->data['settings'];


        return $this->load->view('front-end/happycrop/pages/payment_receipt.php', $pdfdata);
    }
    public function generatepdf()
    {
        $this->load->library('pdf');

        $order_id = "100300";

        $order = fetch_orders($order_id, NULL, false, false, 1, NULL, NULL, NULL, NULL);
        $this->db->distinct();
        $this->db->select('a.seller_id, b.username, b.mobile, b.email, b.mfg_no, c.company_name, c.gst_no, c.fertilizer_license_no, c.pesticide_license_no, c.seeds_license_no, c.account_name, c.account_number, c.bank_name, c.bank_code, c.bank_city, c.bank_branch, c.bank_state, c.plot_no, c.street_locality, c.landmark, cc.name, city, s.name as state, c.pin');
        $this->db->from('order_items as a');
        $this->db->join('users as b', 'a.seller_id = b.id', 'left');
        $this->db->join('seller_data as c', 'a.seller_id = c.user_id', 'left');
        $this->db->join('states as s', 'c.state_id = s.id', 'left');
        $this->db->join('cities as cc', 'c.city_id = cc.id', 'left');
        $this->db->where('a.order_id', $order_id);
        $query = $this->db->get();

        $manufactures = $query->result_array();


        if (!empty($order)) {
            $orderState = fetch_details(["id" => $order["order_data"][0]["address_id"]], "addresses");
            $userdetails = fetch_details(["id" => $order["order_data"][0]["user_id"]], "users");
            if (!empty($orderState)) {
                $order["order_data"][0]["state"] = $orderState[0]["state"];
            }
            if (!empty($userdetails)) {
                $order["order_data"][0]["email"] = $userdetails[0]["email"];
            }
        }
        $getterms = $this->common_model->getRecords('terms_conditions', '*', array("user_id" => $order["order_data"][0]["seller_id"]));
        $order_item_stages = $this->common_model->getRecords('order_item_stages', '*', array("order_id" => $order_id), "id desc", 1);


        $pdfdata['manufacture'] = $manufactures[0];
        $pdfdata['order'] = $order['order_data'];
        $pdfdata['getterms'] = $getterms;
        $pdfdata['view'] = $view;
        $pdfdata['dchallan'] = $dchallan;
        $pdfdata['order_item_stages'] = $order_item_stages;


        $jsonhtml = $this->load->view('front-end/happycrop/pages/tax-invoice.php', $pdfdata, true);
        // $jsonhtml = $this->tax_invoice("100298", "view");


        // $jsonhtml = json_decode($this->input->post('json_data'))->html;
        // $html = 



        $this->pdf->set_option('isHtml5ParserEnabled', true);
        $this->pdf->set_option('isRemoteEnabled', true);
        $this->pdf->loadHtml($jsonhtml);

        $this->pdf->setPaper(array(0, 0, 612, 792), 'portrait');
        $this->pdf->render();
        $dompdf = $this->pdf->output();

        $encodedPdfData = base64_encode($dompdf);
        echo $encodedPdfData;

        // $folder_path = 'uploads/generatedpdf/';

        // if (!is_dir($folder_path)) {
        //     mkdir($folder_path, 0777, TRUE);
        // }
        // $this->load->library('pdf');
        // $pdf = $this->pdf->createPDF($html, "invoice", true, '', TRUE, $folder_path);

    }
    public function purchase_invoice($order_id, $purchase_order = "")
    {

        $order = fetch_orders($order_id, NULL, false, false, 1, NULL, NULL, NULL, NULL);
        $this->db->distinct();
        $this->db->select('a.seller_id, b.username, b.mobile, b.email, b.mfg_no, c.company_name, c.gst_no, c.fertilizer_license_no, c.pesticide_license_no, c.seeds_license_no, c.account_name, c.account_number, c.bank_name, c.bank_code, c.bank_city, c.bank_branch, c.bank_state, c.plot_no, c.street_locality, c.landmark, cc.name, city, s.name as state, c.pin');
        $this->db->from('order_items as a');
        $this->db->join('users as b', 'a.seller_id = b.id', 'left');
        $this->db->join('seller_data as c', 'a.seller_id = c.user_id', 'left');
        $this->db->join('states as s', 'c.state_id = s.id', 'left');
        $this->db->join('cities as cc', 'c.city_id = cc.id', 'left');
        $this->db->where('a.order_id', $order_id);
        $query = $this->db->get();

        $manufactures = $query->result_array();


        if (!empty($order)) {
            $orderState = fetch_details(["id" => $order["order_data"][0]["address_id"]], "addresses");
            $userdetails = fetch_details(["id" => $order["order_data"][0]["user_id"]], "users");
            $sellerdetails = fetch_details(["user_id" => $order["order_data"][0]["seller_id"]], "seller_data");
            if (!empty($orderState)) {
                $order["order_data"][0]["state"] = $orderState[0]["state"];
            }
            if (!empty($userdetails)) {
                $order["order_data"][0]["email"] = $userdetails[0]["email"];
            }
            if (!empty($sellerdetails)) {
                $order["order_data"][0]["logo"] = $sellerdetails[0]["logo"];
            }
            $getterms = $this->common_model->getRecords('terms_conditions', '*', array("user_id" => $order["order_data"][0]["seller_id"]));
            $productDetails = $this->common_model->getRecords('products', '*', array("id" => $order["order_data"][0]["product_id"]));
            if (!empty($productDetails)) {

                $order["order_data"][0]["hsn_no"] = $userdetails[0]["hsn_no"];
            }
        }
        $getterms = $this->common_model->getRecords('terms_conditions', '*', array("user_id" => $order["order_data"][0]["seller_id"]));
        $order_item_stages = $this->common_model->getRecords('order_item_stages', '*', array("order_id" => $order_id), "id desc", 1);

        $pdfdata['manufacture'] = $manufactures[0];
        $pdfdata['order'] = $order['order_data'][0];
        $pdfdata['getterms'] = $getterms;
        $pdfdata['view'] = $view;
        $pdfdata['purchase_order'] = $purchase_order;
        $pdfdata['order_item_stages'] = $order_item_stages;
        $pdfdata['settings'] = $this->data['settings'];


        return $this->load->view('front-end/happycrop/pages/purchase-order.php', $pdfdata);
    }
    public function tax_invoice($order_id, $view = "", $dchallan = "")
    {

        $order = fetch_orders($order_id, NULL, false, false, 1, NULL, NULL, NULL, NULL);
        $this->db->distinct();
        $this->db->select('a.seller_id, b.username, b.mobile, b.email, b.mfg_no, c.company_name, c.gst_no, c.fertilizer_license_no, c.pesticide_license_no, c.seeds_license_no, c.account_name, c.account_number, c.bank_name, c.bank_code, c.bank_city, c.bank_branch, c.bank_state, c.plot_no, c.street_locality, c.landmark, cc.name, city, s.name as state, c.pin');
        $this->db->from('order_items as a');
        $this->db->join('users as b', 'a.seller_id = b.id', 'left');
        $this->db->join('seller_data as c', 'a.seller_id = c.user_id', 'left');
        $this->db->join('states as s', 'c.state_id = s.id', 'left');
        $this->db->join('cities as cc', 'c.city_id = cc.id', 'left');
        $this->db->where('a.order_id', $order_id);
        $query = $this->db->get();

        $manufactures = $query->result_array();


        if (!empty($order)) {
            $orderState = fetch_details(["id" => $order["order_data"][0]["address_id"]], "addresses");
            $userdetails = fetch_details(["id" => $order["order_data"][0]["user_id"]], "users");
            $sellerdetails = fetch_details(["user_id" => $order["order_data"][0]["seller_id"]], "seller_data");

            if (!empty($orderState)) {
                $order["order_data"][0]["state"] = $orderState[0]["state"];
            }
            if (!empty($userdetails)) {
                $order["order_data"][0]["email"] = $userdetails[0]["email"];
            }
            if (!empty($sellerdetails)) {
                $order["order_data"][0]["logo"] = $sellerdetails[0]["logo"];
            }
            $productDetails = $this->common_model->getRecords('products', '*', array("id" => $order["order_data"][0]["product_id"]));
            if (!empty($productDetails)) {

                $order["order_data"][0]["hsn_no"] = $userdetails[0]["hsn_no"];
            }
        }
        $getterms = $this->common_model->getRecords('terms_conditions', '*', array("user_id" => $order["order_data"][0]["seller_id"]));
        $order_item_stages = $this->common_model->getRecords('order_item_stages', '*', array("order_id" => $order_id), "id desc", 1);


        $pdfdata['manufacture'] = $manufactures[0];
        $pdfdata['order'] = $order['order_data'];
        $pdfdata['getterms'] = $getterms;
        $pdfdata['view'] = $view;
        $pdfdata['dchallan'] = $dchallan;
        $pdfdata['order_item_stages'] = $order_item_stages;
        $pdfdata['settings'] = $this->data['settings'];


        return $this->load->view('front-end/happycrop/pages/tax-invoice.php', $pdfdata);
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
            if ($this->Order_model->add_payment_demand($data)) {

                if ($order_item_id) {
                    $order_item_info = $this->db->get_where('order_items', array('id' => $order_item_id))->row_array();

                    $status = json_decode(stripallslashes($order_item_info['status']));
                    array_push($status, array('payment_ack', date('d-m-Y h:i:sa')));

                    $order_item_up = ['active_status' => 'payment_ack', 'status' => json_encode($status)];
                    $order_item_up["receipt_no"] = $this->input->post("receipt_no");
                    $order_item_up["transaction_id"] = $this->input->post("transaction_no");
                    $order_item_up["description"] = $this->input->post("description");
                    $order_item_up = escape_array($order_item_up);
                    $this->db->set($order_item_up)->where('id', $order_item_id)->update('order_items');
                } else {
                    $this->db->select('a.id, a.seller_id, a.status, a.active_status');
                    $this->db->from('order_items as a');
                    $this->db->where('a.order_id', $order_id);
                    $this->db->where_not_in('a.active_status', array('delivered', 'cancelled'));
                    $query = $this->db->get();
                    $order_items_info = $query->result_array();

                    if ($order_items_info) {
                        foreach ($order_items_info as $order_item_info) {
                            $status = json_decode(stripallslashes($order_item_info['status']));
                            if ($status != null) {
                                array_push($status, array('payment_ack', date('d-m-Y h:i:sa')));
                            } else {
                                $status =  array(array('payment_ack', date("d-m-Y h:i:sa")));
                            }

                            $update_item_data = array('active_status' => 'payment_ack', 'status' => json_encode($status));
                            $update_item_data["receipt_no"] = $this->input->post("receipt_no");
                            $update_item_data["transaction_id"] = $this->input->post("transaction_no");
                            $update_item_data["description"] = $this->input->post("description");
                            update_details($update_item_data, ['id' => $order_item_info['id']], 'order_items');
                        }
                    }
                }

                $this->db->update('orders', array('order_status' => 'payment_ack', 'last_updated' => date('Y-m-d H:i:s')), array('id' => $order_id));

                $order      = fetch_details(['id' => $order_id], 'orders');
                $system_settings = get_settings('system_settings', true);
                $user_id    = $order[0]['user_id'];
                $user = fetch_details(['id' => $order[0]['user_id']], 'users');

                $retailer_store_name = fetch_details(['user_id' => $user_id], 'retailer_data', 'company_name');
                $retailer_store_name = $retailer_store_name[0]['company_name'];

                $seller_email = fetch_details(['id' => $order_items_info[0]['seller_id']], 'users', 'email');
                $seller_store_name = fetch_details(['user_id' => $order_items_info[0]['seller_id']], 'seller_data', 'company_name');
                $seller_store_name = $seller_store_name[0]['company_name'];

                if ($user[0]['email'] != '') {
                    $html_text  = '<p>Hello ' . ucfirst($retailer_store_name) . ',</p>';
                    $html_text .= '<p>Transection details are shared with Happycrop. You will receive confirmation mail in next 48 hrs.</p>';

                    $order_info = array(
                        'subject'    => 'Order #HC-A' . $order_id . ' - Updates',
                        'user_msg' => $html_text,
                    );

                    send_mail2($user[0]['email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                }

                //seller
                if ($seller_email[0]["email"] != '') {
                    $html_text  = '<p>Hello ' . ucfirst($seller_store_name) . ',</p>';
                    $html_text .= '<p><b>We are happy to inform you that the payment is made by  ' . ucfirst($retailer_store_name) . ' and Transaction details are shared with Happycrop. Happycrop will confirm the transaction details within 48 hrs and send you the notification.</b></p>';

                    $order_info = array(
                        'subject'    => 'Order #HC-A' . $order_id . ' - Updates from Retailer',
                        'user_msg' => $html_text,
                    );

                    send_mail2($seller_email[0]["email"], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                }

                //admin
                if (isset($system_settings['support_email']) && !empty($system_settings['support_email'])) {
                    $html_text  = '<p><b>Hello Admin,</b></p>';
                    $html_text .= '<p><b>The Retailer - ' . ucfirst($retailer_store_name) . ' has made the payment and transaction details are shared with Happycrop. Please confirm the payment details and send the notification.</b></p>';

                    $order_info = array(
                        'subject'    => 'Order #HC-A' . $order_id . ' - Updates from Retailer',
                        'user_msg' => $html_text,
                    );
                    send_mail2($system_settings['support_email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                }


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
                $this->response['encodedPdfData'] = $encodedPdfData;
                $this->response['error'] = false;
                $this->response['message'] =  'Payment Acknowledgement Receipt Added Successfully!';
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = (!empty($data)) ? $data : [];
                $redirect_url = base_url() . 'my-account/payment-receipt/' . $order_id;
                // redirect($redirect_url);
                $this->response['redirect_url'] = $redirect_url;

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


    public function manage_storage_address()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['type']      = 'storage';
            $this->data['main_page'] = 'address';
            $this->data['title'] = 'Storage Address | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Storage Address, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Storage Address | ' . $this->data['web_settings']['meta_description'];
            $this->data['cities'] = get_cities();
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function modal_add_address($id = 0)
    {
        if ($this->ion_auth->logged_in()) {
            ob_clean();

            $address_info = array();

            if ($id) {
                $address_info = $this->db->get_where('addresses', array('id' => $id))->row();
            }

            $this->data['address_info'] = $address_info;

            $this->load->view('front-end/' . THEME . '/pages/modal_add_address', $this->data);
        } else {
            ob_clean();
            $content  = '<div class="alert alert-warning">You are not authorised.</div>';
            $buttons  = '<button type="button" data-dismiss="modal" class="btn btn-default btn-danger">Close</button>';

            $response = array("html" => $content, "buttons" => $buttons);

            echo json_encode($response);
        }
    }

    function modal_save_address()
    {
        ob_clean();
        if ($this->ion_auth->logged_in()) {

            $user_id    = $this->ion_auth->get_user_id();
            $user       = $this->ion_auth->user()->row();
            //$retailer_data  = $this->db->get_where('retailer_data', array('user_id'=>$user_id))->row();


            $id               = $this->input->post('id');
            $shop_name        = $this->input->post('shop_name');
            $plot_no          = $this->input->post('plot_no');
            $street_locality  = $this->input->post('street_locality');
            $landmark         = $this->input->post('landmark');
            $state_id         = $this->input->post('state_id');
            $city_id          = $this->input->post('city_id');
            $pin              = $this->input->post('pin');

            $type             = $this->input->post('type');

            if ($id) {
                $data = array(
                    'user_id'           => $user_id,
                    'name'              => $user->username,
                    'type'              => $type,
                    'mobile'            => $user->mobile,
                    //'alternate_mobile'  => $user->alternate_mobile,
                    'shop_name'         => $shop_name,
                    'plot_no'           => $plot_no,
                    'street_locality'   => $street_locality,
                    'landmark'          => $landmark,
                    'address'           => $plot_no . ' ' . $street_locality . ' ' . $landmark,
                    'state_id'          => $state_id,
                    'city_id'           => $city_id,
                    'pincode'           => $pin,
                );
                $data = escape_array($data);
                $save = $this->db->update('addresses', $data, array('id' => $id));

                $address_info = $this->db->get_where('addresses', array('id' => $id))->row();

                if ($address_info->add_order == 2) {
                    $_data = ['storage_shop_name' => $shop_name, 'storage_plot_no' => $plot_no, 'storage_street_locality' => $street_locality, 'storage_landmark' => $landmark, 'storage_pin' => $pin, 'storage_city_id' => $city_id, 'storage_state_id' => $state_id,];
                    $_data = escape_array($_data);
                    $this->db->set($_data)->where('user_id', $user_id)->update('retailer_data');
                }

                if ($save) {
                    $response = array('msg' => 'Address updated successfully.');
                    echo json_encode($response);
                } else {
                    $response = array('msg' => 'Address not updated.');
                    echo json_encode($response);
                }
            } else {
                $data = array(
                    'user_id'           => $user_id,
                    'name'              => $user->username,
                    'type'              => $type,
                    'mobile'            => $user->mobile,
                    //'alternate_mobile'  => $user->alternate_mobile,
                    'shop_name'         => $shop_name,
                    'plot_no'           => $plot_no,
                    'street_locality'   => $street_locality,
                    'landmark'          => $landmark,
                    'address'           => $plot_no . ' ' . $street_locality . ' ' . $landmark,
                    'state_id'          => $state_id,
                    'city_id'           => $city_id,
                    'pincode'           => $pin,
                    'add_order'         => 3,
                );
                $data = escape_array($data);
                $this->db->insert('addresses', $data);
                $id = $this->db->insert_id();

                if ($id) {
                    $response = array('msg' => 'Address added successfully.');
                    echo json_encode($response);
                } else {
                    $response = array('msg' => 'Address not added.');
                    echo json_encode($response);
                }
            }
        }
    }

    function emailTest()
    {
        if ($this->ion_auth->logged_in()) {

            $user_id    = $this->ion_auth->get_user_id();
            $system_settings = get_settings('system_settings', true);


            //$html = '<!doctype html><html lang="en"><head><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title>Happy Crop</title></head><body><p>Hi there,</p><p>Sometimes you just want to send a simple HTML email with a simple design and clear call to action. This is it.</p></body></html>';

            //$r = send_mail2('vaibhav.svmindlogic@gmail.com', 'Your Registration is Under Review', $html);
            //var_dump($r);die;
            //var_dump($this->load->view('admin/pages/view/enmail-comb.php', $order_info, TRUE));die;


            //$r = send_mail2('vaibhav.svmindlogic@gmail.com', 'Under Review', 'We extend a warm welcome to you as part of our thriving community.');
            //var_dump($r);   die;
            /*$html_text = '<p>Congratulations! We are excited to inform you that your registration on Happycrop, the innovative B2B agri input platform, has been successfully completed. We extend a warm welcome to you as part of our thriving community.</p>';
            $order_info = array(
                'subject'        => 'Welcome to Happycrop: Your Registration is Under Review',
                'user_msg'       => $html_text,
                'show_foot_note' => false,
            );
            
            

            $r = send_mail2('vaibhav.svmindlogic@gmail.com', 'Welcome to Happycrop: Your Registration is Under Review', $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
            
            var_dump($r); */

            //send_mail3('vaibhav.svmindlogic@gmail.com', 'Under Review', 'We extend a warm welcome to you as part of our thriving community.');
            /*
            $html_text  = '<p style="margin-bottom:0px;">Dear,</p>';
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
            
            send_mail2('vaibhav.svmindlogic@gmail.com', 'Welcome to Happycrop: Your Registration is Under Review', $html_text);
            /*
            $user_id    = 129;*/

            $user = fetch_details(['id' => $user_id], 'users');

            $seller = fetch_details(['user_id' => $user_id], 'seller_data');


            if ($user[0]['reg_mail_sent'] == 0 || 1) {
                //to manufacturer
                /**/
                if ($user[0]['email'] != '') {
                    $html_text  = '<p style="margin-bottom:0px;">Dear ' . $seller[0]['company_name'] . ',</p>';
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

                    //$r = send_mail2('vaibhav@svmindlogic.com', 'Welcome to Happycrop: Your Registration is Under Review', $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    $r = send_mail2('sachin@svmindlogic.com,sachin.svmindlogic@gmail.com,sales@happycrop.in,donotreply@happycrop.in,sales@happycrop.in', 'Welcome to Happycrop: Your Registration is Under Review', $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    //$r = send_mail2('mandala4042@godaddy.com', 'Welcome to Happycrop: Your Registration is Under Review', $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    //$r = send_mail2('sachin.svmindlogic@gmail.com,rctechsup12@gmail.com,sales@happycrop.in', 'Welcome to Happycrop: Your Registration is Under Review', $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    //$r = send_mail2('test-x0kfwixdr@srv1.mail-tester.com', 'Welcome to Happycrop: Your Registration is Under Review', $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    var_dump($user[0]['email'], $r);
                    die;
                }
                /**/

                /*
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
                */
            }

            /*
            $order_id = 100234;
            
            $order      = fetch_details(['id' => $order_id], 'orders');
            $this->db->select('a.id, a.seller_id, a.status, a.active_status');
            $this->db->from('order_items as a');
            $this->db->where('a.order_id', $order_id);
            
            $query = $this->db->get();
            $order_items_info = $query->result_array(); 
            $user_id    = $order[0]['user_id'];
            $user = fetch_details(['id' => $order[0]['user_id']], 'users');
    
            $retailer_store_name = fetch_details(['user_id' => $user_id], 'retailer_data', 'company_name');
            $retailer_store_name = $retailer_store_name[0]['company_name'];
            
            $seller_email = fetch_details(['id' => $order_items_info[0]['seller_id']], 'users', 'email');
            $seller_store_name = fetch_details(['user_id' => $order_items_info[0]['seller_id']], 'seller_data', 'company_name');
            $seller_store_name = $seller_store_name[0]['company_name'];
            
            $this->db->select('*');
            $this->db->from('order_item_complaints');
            $this->db->where('order_id',$order_id);
            $query = $this->db->get();
            $order_item_complaints = $query->result_array();
            
            if($order_item_complaints)
            {
                $complaint_text = '<table border="1" class="es-content-body" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:100%;margin-top:15px;margin-bottom:0px;">
                                   <tr  bgcolor="#efefef" style="Margin:0;padding-top:10px;padding-bottom:10px;background-color:#EFEFEF">
                                        <th style="width: 8%;">Sr. No.</th>
                                        <th>Your Concern</th>
                                        <th style="width: 18%;">Image</th>
                                    </tr>';
                $i_count = 1;
                foreach($order_item_complaints as $order_item_complaint)
                {
                    $complaint_text .= '<tr class="bg-white text-dark">
                                            <td align="center">'.$i_count.'</td>
                                            <td>'.$order_item_complaint['concern'].'</td>
                                            <td>';
                                                
                    if(file_exists($order_item_complaint['attachments']) && $order_item_complaint['attachments'])
                    {
                        $complaint_text .= '<a href="'.base_url().$order_item_complaint['attachments'].'" target="_blank">
                            <img src="'.base_url().$order_item_complaint['attachments'].'" alt="" style="width: 100px;" />
                        </a>';
                        
                    }
                    
                    $complaint_text .= '</td></tr>';
                    
                    $i_count++;
                }
                $complaint_text .= '</table>';
                
            }
            
            if($user[0]['email']!='')
            {
                $html_text  = '<p>Dear '.ucfirst($retailer_store_name);
                $html_text .= '<p style="margin-bottom:0px;">You have raised an issue report regarding order #HC-A'. $order_id . '. According to your report, there have been discrepancies in the fulfillment of this order.</p>';
                $html_text .= '<p>Kindly check the issue status of this order below.</p>';
                $html_text .= $complaint_text;
                
                
                $note_text  = '<p style="margin-top:0px;">We take this matter seriously and are committed to resolving it promptly and to the satisfaction of our valued retailer. Our top priority is to ensure that our retailers receive the correct and undamaged products they have ordered.</p>';
                $note_text .= '<p>We understand the importance of resolving these issues promptly and maintaining a strong and reliable partnership with our retailers and manufacturers. To address this matter, we have initiated the following steps:</p>';
                $note_text .= '<p><b>1. Investigation:</b> We will conduct a thorough investigation to determine the root cause of these issues and prevent them from happening in the future.</p>';
                $note_text .= '<p><b>2. Communication:</b> Our team will reach out to you to gather more details about the problems they have encountered and work closely with them to understand the full extent of the issue.</p>';
                $note_text .= '<p><b>3. Resolution:</b> We are committed to resolving these issues within 5 working days.</p>';
                $note_text .= '<p><b>4. Preventive Measures:</b> We will implement measures to prevent similar issues from occurring in future orders.</p>';
                $note_text .= '<p>Your cooperation in addressing and resolving this matter is highly appreciated. If you have any specific information or recommendations regarding this issue, please feel free to share them with us. We will keep you updated on the progress of this issue resolution.</p>';

                $order_info = array(
                    'subject'           => 'Order #HC-A'. $order_id . ' Order Issue',
                    'user_msg'          => $html_text,
                    'note'              => $note_text,
                ); 
                send_mail2('vaibhav.svmindlogic@gmail.com', 'Happycrop Order Updates - Order #HC-A'. $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
            }
            
            if($seller_email[0]['email']!='')
            {
                $html_text  = '<p>Dear '.ucfirst($seller_store_name);
                $html_text .= '<p style="margin-bottom:0px;">We regret to inform you that we have received an issue report regarding order #HC-A'. $order_id . ' from our valued retailer, '.ucfirst($retailer_store_name).'. According to their report, there have been discrepancies in the fulfillment of this order.</p>';
                $html_text .= '<p>Kindly check the issue status of this order below.</p>';
                $html_text .= $complaint_text;
                
                
                $note_text  = '<p style="margin-top:0px;">We take this matter seriously and are committed to resolving it promptly and to the satisfaction of our valued retailer. Our top priority is to ensure that our retailers receive the correct and undamaged products they have ordered.</p>';
                $note_text .= '<p>We understand the importance of resolving these issues promptly and maintaining a strong and reliable partnership with our retailers. To address this matter, we have initiated the following steps:</p>';
                $note_text .= '<p><b>1. Investigation:</b> We will conduct a thorough investigation to determine the root cause of these issues and prevent them from happening in the future.</p>';
                $note_text .= '<p><b>2. Communication:</b> Our team will reach out to Retailer to gather more details about the problems they have encountered and work closely with them to understand the full extent of the issue.</p>';
                $note_text .= '<p><b>3. Resolution:</b> We are committed to resolving these issues within 5 working days to the satisfaction of Retailer.</p>';
                $note_text .= '<p><b>4. Preventive Measures:</b> We will implement measures to prevent similar issues from occurring in future orders.</p>';
                $note_text .= '<p>Your cooperation in addressing and resolving this matter is highly appreciated. If you have any specific information or recommendations regarding this issue, please feel free to share them with us. We will keep you updated on the progress of this issue resolution.</p>';

                $order_info = array(
                    'subject'           => 'Order #HC-A'. $order_id . ' Order Issue',
                    'user_msg'          => $html_text,
                    'note'              => $note_text,
                ); 
                send_mail2('vaibhav.svmindlogic@gmail.com', 'Happycrop Order Updates - Order #HC-A'. $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
            }
            
            if(isset($system_settings['support_email']) && !empty($system_settings['support_email'])) 
            {
                $html_text  = '<p>Dear Admin';
                $html_text .= '<p style="margin-bottom:0px;">We have received an issue report regarding order #HC-A'. $order_id . ' from our valued retailer, '.ucfirst($retailer_store_name).'. According to their report, there have been discrepancies in the fulfillment of this order.</p>';
                $html_text .= '<p>Kindly check the issue status of this order below.</p>';
                $html_text .= $complaint_text;
                
                $note_text  = '<p style="margin-top:0px;">Kindly connect with Retailer and manufacturer to find out the reason for issue occurred and try to resolve it.</p>';
                
                $order_info = array(
                    'subject'           => 'Order #HC-A'. $order_id . ' Order Issue',
                    'user_msg'          => $html_text,
                    'note'              => $note_text,
                ); 
                send_mail2('vaibhav.svmindlogic@gmail.com', 'Happycrop Order Updates - Order #HC-A'. $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
            }
            
            /*$order      = fetch_details(['id' => $order_id], 'orders');
            $this->db->select('a.id, a.seller_id, a.status, a.active_status');
            $this->db->from('order_items as a');
            $this->db->where('a.order_id', $order_id);
            $query = $this->db->get();
            $order_items_info = $query->result_array(); 
            
            $user_id = $order[0]['user_id'];
            $user    = fetch_details(['id' => $order[0]['user_id']], 'users');
    
            $retailer_store_name = fetch_details(['user_id' => $user_id], 'retailer_data', 'company_name');
            $retailer_store_name = $retailer_store_name[0]['company_name'];
            
            $seller_email       = fetch_details(['id' => $order_items_info[0]['seller_id']], 'users', 'email');
            $seller_store_name  = fetch_details(['user_id' => $order_items_info[0]['seller_id']], 'seller_data', 'company_name');
            $seller_store_name  = $seller_store_name[0]['company_name'];
            
            $this->db->select('*');
            $this->db->from('order_item_complaint_messages');
            $this->db->where('order_id',$order_id);
            $query = $this->db->get();
            $complaints_messages = $query->result_array();
            
            if($complaints_messages)
            {
                $complaint_text = '<table border="1" class="es-content-body" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:100%;margin-top:15px;margin-bottom:0px;">
                                   <tr  bgcolor="#efefef" style="Margin:0;padding-top:10px;padding-bottom:10px;background-color:#EFEFEF">
                                        <th style="width: 8%;">Sr. No.</th>
                                        <th>Message</th>
                                        <th style="width: 18%;">Image</th>
                                    </tr>';
                $i_count = 1;
                foreach($complaints_messages as $complaints_message)
                {
                    $complaint_text .= '<tr class="bg-white text-dark">
                                            <td align="center">'.$i_count.'</td>
                                            <td>'.$complaints_message['message'].'</td>
                                            <td>';
                                                
                    if(file_exists($complaints_message['attachments']) && $complaints_message['attachments'])
                    {
                        $complaint_text .= '<a href="'.base_url().$complaints_message['attachments'].'" target="_blank">
                            <img src="'.base_url().$complaints_message['attachments'].'" alt="" style="width: 100px;" />
                        </a>';
                        
                    }
                    
                    $complaint_text .= '</td></tr>';
                    
                    $i_count++;
                }
                $complaint_text .= '</table>';
            }
            
            if($user[0]['email']!='')
            {
                $html_text  = '<p>Dear '.ucfirst($retailer_store_name);
                $html_text .= '<p style="margin-bottom:0px;">We would like to inform you that the issue with your recent order #HC-A'. $order_id . '. has been successfully addressed by Happycrop. Your patience and cooperation throughout this process were greatly appreciated.</p>';
                $html_text .= '<p style="margin-bottom:0px;">We kindly request you to confirm by logging into www.happycrop.in., if issue get resolved.</p>';
                $html_text .= '<p style="margin-bottom:0px;">Our comment on the related issue as below.</p>';
                $html_text .= $complaint_text;
                
                
                $note_text  = '<p style="margin-top:0px;">We understand the inconvenience that the previous issue may have caused you, and we sincerely apologize for any disruption to your experience with us. We appreciate your patience and cooperation during this resolution process. It is essential for us to ensure that you receive the correct products in perfect condition, and we are committed to maintaining the highest standards in our services.</p>';
                $note_text .= '<p>Once again, we apologize for any inconvenience you may have experienced, and we appreciate your continued trust in our services. We look forward to serving you in the future and providing you with the exceptional experience you deserve.</p>';
                
                $order_info = array(
                    'subject'           => 'Order #HC-A'. $order_id . ' Issue Resolved',
                    'user_msg'          => $html_text,
                    'note'              => $note_text,
                ); 
                send_mail2('vaibhav.svmindlogic@gmail.com', 'Happycrop Order Updates - Order #HC- A'. $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
            }
            
            if($seller_email[0]['email']!='')
            {
                $html_text  = '<p>Dear '.ucfirst($seller_store_name);
                $html_text .= '<p style="margin-bottom:0px;">We would like to inform you that the issue with your recent order #HC-A'. $order_id . ' has been successfully addressed by Happycrop. Your patience and cooperation throughout this process were greatly appreciated.</p>';
                $html_text .= '<p style="margin-bottom:0px;">Waiting for retailer confirmation.</p>';
                $html_text .= '<p style="margin-bottom:0px;">Our comment on the related issue as below.</p>';
                $html_text .= $complaint_text;
                
                $note_text  = '<p style="margin-top:0px;">We understand the inconvenience that the previous issue may have caused you, and we sincerely apologize for any disruption to your experience with us. We appreciate your patience and cooperation during this resolution process.</p>';

                $order_info = array(
                    'subject'           => 'Order #HC-A'. $order_id . ' Issue Resolved',
                    'user_msg'          => $html_text,
                    'note'              => $note_text,
                ); 
                send_mail2('vaibhav.svmindlogic@gmail.com', 'Happycrop Order Updates - Order #HC- A'. $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
            }
            
            if(isset($system_settings['support_email']) && !empty($system_settings['support_email'])) 
            {
                $html_text  = '<p>Dear Admin';
                $html_text .= '<p style="margin-bottom:0px;">We would like to inform you that the issue with the recent order #HC-A'. $order_id . ' has been successfully resolved.</p>';
                $html_text .= '<p style="margin-bottom:0px;">Our comment on the related issue as below.</p>';
                $html_text .= $complaint_text;
                
                $note_text  = '<p style="margin-top:0px;">Kindly release the payment according to issue status.</p>';
                
                $order_info = array(
                    'subject'           => 'Order #HC-A'. $order_id . ' Issue Resolved',
                    'user_msg'          => $html_text,
                    'note'              => $note_text,
                ); 
                send_mail2('vaibhav.svmindlogic@gmail.com', 'Happycrop Order Updates - Order #HC- A'. $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
            }
            
            
            
            /*$order_id = 100109;
            $order = fetch_orders($order_id, $user_id, false, false, 1, NULL, NULL, NULL, NULL);
            
            $seller_email = fetch_details(['id' => $order['order_data'][0]['seller_id']], 'users', 'email');
            $seller_store_name = fetch_details(['user_id' => $order['order_data'][0]['seller_id']], 'seller_data', 'company_name');
            
            $seller_order_info = array(
                'order'      => $order['order_data'][0],
                //'order_data' => $overall_total,
                'subject'    => 'New order #HC-A' . $last_order_id.' received',
                //'user_data'  => $user[0],
                'system_settings' => $system_settings,
                'user_msg'   => 'Hello ' . ucfirst($seller_store_name[0]["company_name"]) . ', New order received @ ' . $system_settings['app_name'] . ' so please check it. Order summaries are as followed',
                'show_retailer_name' => 1,
                'show_retailer_contact' => 1,
                'show_retailer_gstn' => 1,
                'show_seller_info' => 1,
                //'otp_msg'    => 'Here is your OTP. Please, give it to delivery boy only while getting your order.',
            );
            
            //send_mail2($seller_email[0]["email"], 'New order #HC-A' . $last_order_id.' received', $this->load->view('admin/pages/view/order-email-template.php', $seller_order_info, TRUE));
            send_mail2('vaibhav.svmindlogic@gmail.com', 'New order #HC-A' . $last_order_id.' received', $this->load->view('admin/pages/view/order-email-template.php', $seller_order_info, TRUE));
            */
            /*
            
            $user = fetch_details(['id' => $user_id], 'users');
            
            $overall_order_data = array(
                'order'      => $order['order_data'][0],
                //'order_data' => $overall_total,
                'subject'    => 'Order received successfully',
                'user_data'  => $user[0],
                'system_settings' => $system_settings,
                'user_msg'   => 'Hello, Dear ' . ucfirst($user[0]['username']) . ', We have received your order successfully. Your order summaries are as followed',
                //'otp_msg'    => 'Here is your OTP. Please, give it to delivery boy only while getting your order.',
            );
        
        
            //send_mail2('sachin.svmindlogic@gmail.com','Email Test','<p>Email for Testing Purpose</p>');
            send_mail2($user[0]['email'], 'Order received successfully', $this->load->view('admin/pages/view/order-email-template.php', $overall_order_data, TRUE));
            
            if (isset($system_settings['support_email']) && !empty($system_settings['support_email'])) {
                //send_mail($system_settings['support_email'], 'New order placed ID #' . $last_order_id, 'New order received for ' . $system_settings['app_name'] . ' please process it.');
                $seller_order_info = array(
                    'order'      => $order['order_data'][0],
                    //'order_data' => $overall_total,
                    'subject'    => 'New order #HC-A' . $last_order_id.' received',
                    //'user_data'  => $user[0],
                    'system_settings' => $system_settings,
                    'user_msg'   => 'Hello Admin, New order received @ ' . $system_settings['app_name'] . ' so please check it. Order summaries are as followed',
                    //'otp_msg'    => 'Here is your OTP. Please, give it to delivery boy only while getting your order.',
                );
                send_mail2($system_settings['support_email'], 'New order #HC-A' . $last_order_id.' received', $this->load->view('admin/pages/view/order-email-template.php', $seller_order_info, TRUE));
            }
            
            */
        }
    }

    public function fetch_orders()
    {
        if ($this->ion_auth->logged_in()) {
            $sales[] = array();

            $user_id    = $this->ion_auth->get_user_id();

            $month_res = $this->db->select('SUM(sub_total) AS total_sale,DATE_FORMAT(date_added,"%b") AS month_name ')
                ->where('user_id', $user_id)
                ->group_by('year(CURDATE()),MONTH(date_added)')
                ->order_by('year(CURDATE()),MONTH(date_added)')
                ->get('`order_items`')->result_array();

            $month_wise_sales['total_sale'] = array_map('intval', array_column($month_res, 'total_sale'));
            $month_wise_sales['month_name'] = array_column($month_res, 'month_name');

            $sales[0] = $month_wise_sales;
            $d = strtotime("today");
            $start_week = strtotime("last sunday midnight", $d);
            $end_week = strtotime("next saturday", $d);
            $start = date("Y-m-d", $start_week);
            $end = date("Y-m-d", $end_week);
            $week_res = $this->db->select("DATE_FORMAT(date_added, '%d-%b') as date, SUM(sub_total) as total_sale")
                ->where('user_id', $user_id)
                ->where("date(date_added) >='$start' and date(date_added) <= '$end' ")
                ->group_by('day(date_added)')->get('`order_items`')->result_array();

            $week_wise_sales['total_sale'] = array_map('intval', array_column($week_res, 'total_sale'));
            $week_wise_sales['week'] = array_column($week_res, 'date');

            $sales[1] = $week_wise_sales;

            //echo $this->db->last_query();die;

            $day_res = $this->db->select("DAY(date_added) as date, SUM(sub_total) as total_sale")
                ->where('user_id', $user_id)
                ->where('date_added >= DATE_SUB(CURDATE(), INTERVAL 29 DAY)')
                ->group_by('day(date_added)')->order_by('date_added', 'asc')->get('`order_items`')->result_array();
            $day_wise_sales['total_sale'] = array_map('intval', array_column($day_res, 'total_sale'));
            $day_wise_sales['day'] = array_column($day_res, 'date');

            //echo $this->db->last_query();die;

            $sales[2] = $day_wise_sales;
            print_r(json_encode($sales));
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function fetch_orders_count()
    {
        if ($this->ion_auth->logged_in()) {
            $sales[] = array();
            $user_id = $this->ion_auth->get_user_id();

            $month_res = $this->db->select('count(DISTINCT a.id) AS total_sale,DATE_FORMAT(a.date_added,"%b") AS month_name ')
                ->join('`order_items` as b', 'a.id = b.order_id')
                ->where('a.user_id', $user_id)
                ->group_by('year(CURDATE()),MONTH(a.date_added)')
                ->order_by('year(CURDATE()),MONTH(a.date_added)')
                ->get('`orders` as a')->result_array();
            $month_wise_sales['total_sale'] = array_map('intval', array_column($month_res, 'total_sale'));
            $month_wise_sales['month_name'] = array_column($month_res, 'month_name');

            $sales[0] = $month_wise_sales;
            $d = strtotime("today");
            $start_week = strtotime("last sunday midnight", $d);
            $end_week = strtotime("next saturday", $d);
            $start = date("Y-m-d", $start_week);
            $end = date("Y-m-d", $end_week);
            $week_res = $this->db->select("DATE_FORMAT(a.date_added, '%d-%b') as date, count(DISTINCT a.id) as total_sale")
                ->join('`order_items` as b', 'a.id = b.order_id')
                ->where('a.user_id', $user_id)
                ->where("date(a.date_added) >='$start' and date(a.date_added) <= '$end' ")
                ->group_by('day(a.date_added)')->get('`orders` as a')->result_array();
            //echo $this->db->last_query();die;
            $week_wise_sales['total_sale'] = array_map('intval', array_column($week_res, 'total_sale'));
            $week_wise_sales['week'] = array_column($week_res, 'date');

            $sales[1] = $week_wise_sales;

            $day_res = $this->db->select("DAY(a.date_added) as date, count(DISTINCT a.id) as total_sale")
                ->join('`order_items` as b', 'a.id = b.order_id')
                ->where('a.user_id', $user_id)
                ->where('a.date_added >= DATE_SUB(CURDATE(), INTERVAL 29 DAY)')
                ->group_by('day(a.date_added)')->order_by('a.date_added', 'asc')->get('`orders` as a')->result_array();
            $day_wise_sales['total_sale'] = array_map('intval', array_column($day_res, 'total_sale'));
            $day_wise_sales['day'] = array_column($day_res, 'date');

            //echo $this->db->last_query();die;

            $sales[2] = $day_wise_sales;
            print_r(json_encode($sales));
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function accounts()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'accounts';
            $this->data['title'] = 'Accounts | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }


    public function get_order_account_list()
    {
        if ($this->ion_auth->logged_in()) {
            return $this->order_model->get_order_account_list($this->data['user']->id, $status);
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }
    public function get_order_account_list_filter()
    {
        if ($this->ion_auth->logged_in()) {
            return $this->order_model->get_order_account_list_filter($this->data['user']->id, $status);
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function statements()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'statements';
            $this->data['title'] = 'Statements | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function get_order_statement_list()
    {
        if ($this->ion_auth->logged_in()) {

            return $this->order_model->get_order_statement_list($this->data['user']->id);
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }
    public function items()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'items';
            $this->data['title'] = 'Items | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    public function get_items_account_list()
    {
        if ($this->ion_auth->logged_in()) {
            return $this->order_model->get_items_account_list($this->data['user']->id);
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }
    public function expenses()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'expenses';
            $this->data['title'] = 'Expenses | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    public function statement_detail()
    {
        if ($this->ion_auth->logged_in()) {
            if (isset($_GET['seller_id']) && round($_GET['seller_id'])) {
                $this->data['main_page'] = 'statement_detail';
                $this->data['title'] = 'Statements | ' . $this->data['web_settings']['site_title'];
                $this->data['keywords'] = $this->data['web_settings']['meta_keywords'];
                $this->data['description'] = $this->data['web_settings']['meta_description'];

                $this->data['seller_id']    = $_GET['seller_id'];
                $this->data['seller_info']  = $this->order_model->get_seller_data_info($this->data['seller_id']);

                $this->load->view('front-end/' . THEME . '/template', $this->data);
            } else {
                redirect(base_url('my-account/statements'), 'refresh');
            }
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function view_retailer_statement_details($seller_id)
    {
        if ($this->ion_auth->logged_in()) {

            return $this->order_model->get_retailer_statement_details($this->data['user']->id, $seller_id);
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function webinars()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'webinars';
            $this->data['title'] = 'Webinars | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function get_front_webinar_list()
    {
        if ($this->ion_auth->logged_in()) {
            $this->load->model(['webinar_model']);
            return $this->webinar_model->get_front_webinar_list();
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }


    public function assign_numbers_to_users()
    {
        /*
        $this->db->select('a.id, b.group_id');
        $this->db->from('users as a');
        $this->db->join('users_groups as b','a.id = b.user_id');
        $this->db->join('retailer_data sd', ' sd.user_id = a.id');
        $this->db->where('b.group_id', 2);
        $this->db->order_by('a.id','ASC');
        $query = $this->db->get();
        
        $rows = $query->result_array();
        
        if($rows)
        {
            foreach($rows as $row)
            {
                $this->db->select('a.ret_no');
                $this->db->from('hp_numbers as a');
                $this->db->where('a.id', 1);
                $query      = $this->db->get();
                $no_info    = $query->row_array();
                $ret_fee_no = $no_info['ret_no'] + 1;
                
                $_no_data = array('ret_no'=>$ret_fee_no);
                $this->db->update('hp_numbers', $_no_data, array('id'=>1));
                
                $_no_data2 = array('ret_no'=>$ret_fee_no);
                $this->db->update('users', $_no_data2, array('id'=>$row['id']));
            }
        }
        
        $this->db->select('a.id, b.group_id');
        $this->db->from('users as a');
        $this->db->join('users_groups as b','a.id = b.user_id');
        $this->db->join('seller_data sd', ' sd.user_id = a.id');
        $this->db->where('b.group_id', 4);
        $this->db->order_by('a.id','ASC');
        $query = $this->db->get();
        
        $rows = $query->result_array();
        
        if($rows)
        {
            foreach($rows as $row)
            {
                $this->db->select('a.mfg_no');
                $this->db->from('hp_numbers as a');
                $this->db->where('a.id', 1);
                $query      = $this->db->get();
                $no_info    = $query->row_array();
                $mfg_fee_no = $no_info['mfg_no'] + 1;
                
                $_no_data = array('mfg_no'=>$mfg_fee_no);
                $this->db->update('hp_numbers', $_no_data, array('id'=>1));
                
                $_no_data2 = array('mfg_no'=>$mfg_fee_no);
                $this->db->update('users', $_no_data2, array('id'=>$row['id']));   
            }
        }
        */
    }

    public function assign_numbers_to_products()
    {
        /*
        $this->db->select('a.id, b.is_service_category');
        $this->db->from('products as a');
        $this->db->join('super_categories as b','a.super_category_id = b.id');
        $this->db->join('categories c', 'a.category_id=c.id');
        $this->db->join('product_variants as d', 'd.product_id = a.id');
        $this->db->where('b.status', 1);
        $this->db->group_by('a.id');
        $this->db->order_by('a.id','ASC');
        $query = $this->db->get();
        
        $rows = $query->result_array();
        
        if($rows)
        {
            foreach($rows as $row)
            {
                if($row['is_service_category'])
                {
                    $this->db->select('a.serv_no');
                    $this->db->from('hp_numbers as a');
                    $this->db->where('a.id', 1);
                    $query      = $this->db->get();
                    $no_info    = $query->row_array();
                    $serv_no    = $no_info['serv_no'] + 1;
                    
                    $_no_data = array('serv_no'=>$serv_no);
                    $this->db->update('hp_numbers', $_no_data, array('id'=>1));
                    
                    $_no_data2 = array('serv_no'=>$serv_no);
                    $this->db->update('products', $_no_data2, array('id'=>$row['id']));
                }
                else
                {
                    $this->db->select('a.pro_no');
                    $this->db->from('hp_numbers as a');
                    $this->db->where('a.id', 1);
                    $query      = $this->db->get();
                    $no_info    = $query->row_array();
                    $pro_no     = $no_info['pro_no'] + 1;
                    
                    $_no_data = array('pro_no'=>$pro_no);
                    $this->db->update('hp_numbers', $_no_data, array('id'=>1));
                    
                    $_no_data2 = array('pro_no'=>$pro_no);
                    $this->db->update('products', $_no_data2, array('id'=>$row['id']));
                }
            }
        }
        */
    }
    public function addexpense()
    {
        $expenseData["user_id"] = $this->session->userdata('user_id');
        $expenseData["created_by"] = $this->session->userdata('user_id');
        $expenseData["expense_category"] = $this->input->post('expense_category');
        $expenseData["expense_number"] = $this->input->post('expense_number');
        $expenseData["date"] = $this->input->post('date');
        $expenseData["payment_type"] = $this->input->post('payment_type');
        $expenseData["total"] = $this->input->post('total');
        $expenseData["paid_amount"] = $this->input->post('paid_amount');
        $expenseData["description"] = $this->input->post('descritpion');

        $upload_path = 'uploads/expense/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, TRUE);
        }
        $config = [
            'upload_path' =>  FCPATH . $upload_path,
            'allowed_types' => 'jpg|png|jpeg|gif',
            'max_size' => 8000,
        ];
        if (isset($_FILES['add_image']) && !empty($_FILES['add_image']['name']) && isset($_FILES['add_image']['name'])) {
            $other_img = $this->upload;
            $other_img->initialize($config);
            if (!empty($_FILES['add_image']['name'])) {

                $_FILES['temp_image']['name'] = $_FILES['add_image']['name'];
                $_FILES['temp_image']['type'] = $_FILES['add_image']['type'];
                $_FILES['temp_image']['tmp_name'] = $_FILES['add_image']['tmp_name'];
                $_FILES['temp_image']['error'] = $_FILES['add_image']['error'];
                $_FILES['temp_image']['size'] = $_FILES['add_image']['size'];
                if (!$other_img->do_upload('temp_image')) {
                    $avatar_error = 'Images :' . $avatar_error . ' ' . $other_img->display_errors();
                } else {
                    $temp_array_avatar = $other_img->data();
                    resize_review_images($temp_array_avatar, FCPATH . $upload_path);
                    $avatar_doc  = $upload_path . $temp_array_avatar['file_name'];
                    $expenseData["image"] = $avatar_doc;
                }
            } else {
                $_FILES['temp_image']['name'] = $_FILES['add_image']['name'];
                $_FILES['temp_image']['type'] = $_FILES['add_image']['type'];
                $_FILES['temp_image']['tmp_name'] = $_FILES['add_image']['tmp_name'];
                $_FILES['temp_image']['error'] = $_FILES['add_image']['error'];
                $_FILES['temp_image']['size'] = $_FILES['add_image']['size'];
                if (!$other_img->do_upload('temp_image')) {
                    $avatar_error = $other_img->display_errors();
                }
            }
        }
        $config1 = [
            'upload_path' =>  FCPATH . $upload_path,
            'allowed_types' => '*',
            'max_size' => 8000,
        ];
        if (isset($_FILES['add_document']) && !empty($_FILES['add_document']['name']) && isset($_FILES['add_document']['name'])) {
            $other_img = $this->upload;
            $other_img->initialize($config1);
            if (!empty($_FILES['add_document']['name'])) {

                $_FILES['temp_image']['name'] = $_FILES['add_document']['name'];
                $_FILES['temp_image']['type'] = $_FILES['add_document']['type'];
                $_FILES['temp_image']['tmp_name'] = $_FILES['add_document']['tmp_name'];
                $_FILES['temp_image']['error'] = $_FILES['add_document']['error'];
                $_FILES['temp_image']['size'] = $_FILES['add_document']['size'];
                if (!$other_img->do_upload('temp_image')) {
                    $avatar_error = 'Images :' . $avatar_error . ' ' . $other_img->display_errors();
                } else {
                    $temp_array_avatar = $other_img->data();
                    resize_review_images($temp_array_avatar, FCPATH . $upload_path);
                    $document  = $upload_path . $temp_array_avatar['file_name'];
                    $expenseData["document"] = $document;
                }
            } else {
                $_FILES['temp_image']['name'] = $_FILES['add_document']['name'];
                $_FILES['temp_image']['type'] = $_FILES['add_document']['type'];
                $_FILES['temp_image']['tmp_name'] = $_FILES['add_document']['tmp_name'];
                $_FILES['temp_image']['error'] = $_FILES['add_document']['error'];
                $_FILES['temp_image']['size'] = $_FILES['add_document']['size'];
                if (!$other_img->do_upload('temp_image')) {
                    $avatar_error = $other_img->display_errors();
                }
            }
        }


        $this->db->insert('expenses', $expenseData);
        $expensed_id = $this->db->insert_id();
        $item_count = $this->input->post('item_count');
        if ($item_count) {

            for ($i = 0; $i <= $this->input->post('item_count'); $i++) {
                $namestring = "name_" . $i;
                $quantity = "quantity_" . $i;
                $price = "price_" . $i;
                $amount = "amount_" . $i;
                if ($this->input->post($namestring) != "") {
                    $expenseitems["expense_id"] = $expensed_id;
                    $expenseitems["name"] = $this->input->post($namestring);
                    $expenseitems["quantity"] = $this->input->post($quantity);
                    $expenseitems["price_unit"] = $this->input->post($price);
                    $expenseitems["amount"] = $this->input->post($amount);
                    $expenseitems["created_by"] = $this->session->userdata('user_id');
                    $this->db->insert('expenses_items', $expenseitems);
                }
            }
        }

        // Redirect to the previous page
        if (isset($_SERVER['HTTP_REFERER'])) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            // Fallback to a default page if HTTP_REFERER is not set
            redirect('my-account/expenses');
        }
    }
    public function get_expense_list()
    {
        $user_id = $this->session->userdata('user_id');
        if ($this->ion_auth->logged_in()) {
            return $this->Account_model->get_expense_list($user_id);
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }
    public function expense_details($expense_id)
    {

        $expenseDetails = $this->common_model->getRecords("expenses", '*', array('id' => $expense_id));
        if (!empty($expenseDetails)) {
            $expenseItems = $this->common_model->getRecords("expenses_items", '*', array('expense_id' => $expense_id));
            $retailerData = $this->common_model->getRecords("retailer_data", '*', array('user_id' => $expenseDetails[0]["user_id"]));
            if (!empty($expenseItems)) {
                $expenseDetails[0]['items'] = $expenseItems;
            } else {
                $expenseDetails[0]['items'] = array();
            }
            if (!empty($retailerData)) {
                $expenseDetails[0]['retailer'] = $retailerData[0];
            }
        }

        $pdfdata['expenseDetails'] = $expenseDetails;
        $pdfdata['settings'] = $this->data['settings'];

        return $this->load->view('front-end/happycrop/pages/expense_view.php', $pdfdata);
    }
    public function purchasebill()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'purchasebill';
            $this->data['title'] = 'purchasebill | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    public function purchaseout()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'purchaseout';
            $this->data['title'] = 'purchaseout | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    public function purchaseorder()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'purchaseorder';
            $this->data['title'] = 'purchaseorder | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    public function addcustomer()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'addcustomer';
            $this->data['title'] = 'addcustomer | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    public function addquickbillcustomer()
    {
        $postData = $this->input->post();
        $customerData["customer_name"] = $postData["name"];
        $customerData["biiling_address"] = $postData["billing_address"];
        $customerData["phone_number"] = $postData["phone_number"];
        $customerData["shipping_address"] = $postData["shipping_address"];
        $customerData["user_id"] = $this->session->userdata('user_id');
        $this->db->insert('tbl_quick_bill_customers', $customerData);
        $customer_id = $this->db->insert_id();
        redirect('my-account/quickbill');
    }
    public function add_quickbill()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['getcustomerlist'] = $this->common_model->getRecords("tbl_quick_bill_customers",'*', array('user_id'=>$this->session->userdata('user_id')));
            $this->data['main_page'] = 'add_quickbill';
            $this->data['title'] = 'add_quickbill | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    public function save_quickbill(){
        $postData = $this->input->post();
        $quickbillData["user_id"] = $this->session->userdata('user_id');
        $quickbillData["customer_id"] = $postData["customer_id"];
        $quickbillData["payment_mode"] = $postData["payment_mode"];
        $quickbillData["subtotal"] = $postData["subtotal"];
        $quickbillData["amount_received"] = $postData["amount_received"];
        $quickbillData["balance"] = $postData["balance"];
        $quickbillData["discount"] = $postData["discount_total"];
        $quickbillData["tax_applied"] = $postData["tax_applied_total"];
        $quickbillData["remark"] = $postData["remark"];
        $quickbillData["total_amt"] = $postData["total_amt"];
        $quickbillData["user_id"] = $this->session->userdata('user_id');
        $this->db->insert('quick_bill_products', $quickbillData);
        $bill_id = $this->db->insert_id();
        
        $item_count = $this->input->post('item_count');
        if ($item_count) {

            for ($i = 1; $i <= $this->input->post('item_count'); $i++) {
                $item_code = "item_code_" . $i;
                $namestring = "item_name_" . $i;
                $quantity = "quantity_" . $i;
                $price = "price_" . $i;
                $discount = "discount_" . $i;
                $tax_applied = "tax_applied_". $i;
                $amount = "total_" . $i;
                if ($this->input->post($namestring) != "") {
                    $expenseitems["bill_id"] = $bill_id;
                    $expenseitems["item_code"] = $this->input->post($item_code);
                    $expenseitems["item_name"] = $this->input->post($namestring);
                    $expenseitems["quantity"] = $this->input->post($quantity);
                    $expenseitems["price"] = $this->input->post($price);
                    $expenseitems["discount"] = $this->input->post($discount);
                    $expenseitems["tax_applied"] = $this->input->post($tax_applied);
                    $expenseitems["total"] = $this->input->post($amount);
                    $expenseitems["user_id"] = $this->session->userdata('user_id');
                    $this->db->insert('tbl_quick_bill_products', $expenseitems);
                  
                }
            }
        }
        redirect('my-account/quickbill');
    }
    public function quickbill()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['getcustomerlist'] = $this->common_model->getRecords("tbl_quick_bill_customers",'*', array('user_id'=>$this->session->userdata('user_id')));
            $this->data['main_page'] = 'quickbill';
            $this->data['title'] = 'quickbill | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    public function get_quick_list()
    {
        $user_id = $this->session->userdata('user_id');
        if ($this->ion_auth->logged_in()) {
            return $this->Account_model->get_quick_list($user_id);
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }
    public function quickbill_details($bill_id)
    {
        $this->db->select('e.*,cust.customer_name,cust.phone_number,cust.biiling_address,cust.shipping_address');
        $this->db->where('e.id',$bill_id);
        $this->db->from('quick_bill_products e');
        $this->db->join('tbl_quick_bill_customers cust', 'cust.id = e.customer_id', 'left');
        $result = $this->db->get()->result_array();

        if (!empty($result)) {
            if ($result[0]["payment_mode"] == "1") {
                $result[0]["payment_mode"] = "cash";
            } else if ($result[0]["payment_mode"] == "1") {
                $result[0]["payment_mode"] = "UPI";
            } else {
                $result[0]["payment_mode"] = "Bank Transfer";
            }
            $expenseItems = $this->common_model->getRecords("tbl_quick_bill_products", '*', array('bill_id' => $bill_id));
            if (!empty($expenseItems)) {
                $result[0]['items'] = $expenseItems;
            } else {
                $result[0]['items'] = array();
            }
        }

        $pdfdata['result'] = $result;
        $pdfdata['settings'] = $this->data['settings'];

        return $this->load->view('front-end/happycrop/pages/quickbillview.php', $pdfdata);
    }
}
