<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Super_category extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation', 'upload']);
        $this->load->helper(['url', 'language', 'file']);
        $this->load->model(['super_category_model']);

        if (!has_permissions('read', 'super_categories')) {
            $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
            redirect('admin/home', 'refresh');
        }
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'manage-super_category';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Super Category Management | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Super_category Management | ' . $settings['app_name'];
            $id = $this->input->get('id', true);
            if (isset($id) && !empty($id)) {
                $this->data['base_super_category_url'] = base_url() . 'admin/super_category/super_category_list?id=' . $id;
            } else {
                $this->data['base_super_category_url']  = base_url() . 'admin/super_category/super_category_list';
            }
            $this->data['super_category_result'] = $this->super_category_model->get_super_categories();
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function get_super_categories()
    {
        $ignore_status = isset($_GET['ignore_status']) && $_GET['ignore_status'] == 1 ? 1 : '';
        $seller_id = (isset($_GET['seller_id']) && !empty($_GET['seller_id'])) ? $_GET['seller_id'] : 0;
        $response['data'] = $this->data['super_category_result'] = $this->super_category_model->get_super_categories(NULL, '', '', 'row_order', 'ASC', 'true', '', $ignore_status, $seller_id);
        echo json_encode($response);
        return;
    }

    public function get_seller_super_categories()
    {
        $this->form_validation->set_data($this->input->get());
        $this->form_validation->set_rules('seller_id', 'Seller ID', 'trim|numeric|required|xss_clean');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return;
        } else {
            $seller_id = $this->input->get('seller_id', true);
            $ignore_status = isset($_GET['ignore_status']) && $_GET['ignore_status'] == 1 ? 1 : '';
            $response['data'] = $this->super_category_model->get_seller_super_categories($seller_id);
            echo json_encode($response);
            return;
        }
    }


    public function create_super_category()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = FORMS . 'super_category';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) ? 'Edit Super Category | ' . $settings['app_name'] : 'Add Super Category | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Add Super Category , Create Super Category | ' . $settings['app_name'];
            if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                $this->data['fetched_data'] = fetch_details(['id' => $_GET['edit_id']], 'super_categories');
            }

            $this->data['super_categories'] = $this->super_category_model->get_super_categories();

            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function super_category_order()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'super_category-order';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Super Category Order | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Super Category Order | ' . $settings['app_name'];
            $this->data['super_categories'] = $this->super_category_model->get_super_categories();
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    function delete_super_category()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

            if (print_msg(!has_permissions('delete', 'super_categories'), PERMISSION_ERROR_MSG, 'super_categories')) {
                return false;
            }


            if ($this->super_category_model->delete_super_category($_GET['id']) == TRUE) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = 'Deleted Succesfully';
                print_r(json_encode($this->response));
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function super_category_list()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            return $this->super_category_model->get_super_category_list();
        } else {
            redirect('admin/login', 'refresh');
        }
    }



    public function add_super_category()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

            if (isset($_POST['edit_super_category'])) {
                if (print_msg(!has_permissions('update', 'super_categories'), PERMISSION_ERROR_MSG, 'super_categories')) {
                    return false;
                }
            } else {
                if (print_msg(!has_permissions('create', 'super_categories'), PERMISSION_ERROR_MSG, 'super_categories')) {
                    return false;
                }
            }

            $this->form_validation->set_rules('super_category_input_name', 'Super Category Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('banner', 'Banner', 'trim|xss_clean');

            if (isset($_POST['edit_super_category'])) {

                $this->form_validation->set_rules('super_category_input_image', 'Image', 'trim|xss_clean');
            } else {
                $this->form_validation->set_rules('super_category_input_image', 'Image', 'trim|required|xss_clean', array('required' => 'Super Category image is required'));
            }


            if (!$this->form_validation->run()) {

                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {

                $this->super_category_model->add_super_category($_POST);
                $this->response['error'] = false;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $message = (isset($_POST['edit_super_category'])) ? 'Super Category Updated Successfully' : 'Super Category Added Successfully';
                $this->response['message'] = $message;
                print_r(json_encode($this->response));
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function update_super_category_order()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (print_msg(!has_permissions('update', 'super_category_order'), PERMISSION_ERROR_MSG, 'super_category_order', false)) {
                return false;
            }
            $i = 0;
            $temp = array();
            foreach ($_GET['super_category_id'] as $row) {
                $temp[$row] = $i;
                $data = [
                    'row_order' => $i
                ];
                $data = escape_array($data);
                $this->db->where(['id' => $row])->update('super_categories', $data);
                $i++;
            }

            $response['error'] = false;
            $response['message'] = 'Super Category Order Saved !';

            print_r(json_encode($response));
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
