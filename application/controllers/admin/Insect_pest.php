<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Insect_pest extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation', 'upload']);
        $this->load->helper(['url', 'language', 'file']);
        $this->load->model(['insect_pest_model']);

        if (!has_permissions('read', 'insect_pests')) {
            $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
            redirect('admin/home', 'refresh');
        }
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'manage-insect_pest';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Insect / Pest Management | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Insect / Pest Management | ' . $settings['app_name'];
            $id = $this->input->get('id', true);
            if (isset($id) && !empty($id)) {
                $this->data['base_insect_pest_url'] = base_url() . 'admin/insect_pest/insect_pest_list?id=' . $id;
            } else {
                $this->data['base_insect_pest_url']  = base_url() . 'admin/insect_pest/insect_pest_list';
            }
            
            $this->data['insect_pest_result'] = $this->insect_pest_model->get_insect_pests();
            
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function get_insect_pests()
    {
        $ignore_status = isset($_GET['ignore_status']) && $_GET['ignore_status'] == 1 ? 1 : '';
        $seller_id = (isset($_GET['seller_id']) && !empty($_GET['seller_id'])) ? $_GET['seller_id'] : 0;
        $response['data'] = $this->data['insect_pest_result'] = $this->insect_pest_model->get_insect_pests(NULL, '', '', 'row_order', 'ASC', 'true', '', $ignore_status, $seller_id);
        echo json_encode($response);
        return;
    }

    public function get_seller_insect_pests()
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
            $response['data'] = $this->insect_pest_model->get_seller_insect_pests($seller_id);
            echo json_encode($response);
            return;
        }
    }


    public function create_insect_pest()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = FORMS . 'insect_pest';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) ? 'Edit Insect / Pest | ' . $settings['app_name'] : 'Add Insect / Pest | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Add Insect / Pest , Create Insect / Pest | ' . $settings['app_name'];
            if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                $this->data['fetched_data'] = fetch_details(['id' => $_GET['edit_id']], 'insect_pests');
            }

            $this->data['insect_pests'] = $this->insect_pest_model->get_insect_pests();

            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function insect_pest_order()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'insect_pest-order';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Insect / Pest Order | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Insect / Pest Order | ' . $settings['app_name'];
            $this->data['insect_pests'] = $this->insect_pest_model->get_insect_pests();
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    function delete_insect_pest()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

            if (print_msg(!has_permissions('delete', 'insect_pests'), PERMISSION_ERROR_MSG, 'insect_pests')) {
                return false;
            }


            if ($this->insect_pest_model->delete_insect_pest($_GET['id']) == TRUE) {
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

    public function insect_pest_list()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            return $this->insect_pest_model->get_insect_pest_list();
        } else {
            redirect('admin/login', 'refresh');
        }
    }



    public function add_insect_pest()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

            if (isset($_POST['edit_insect_pest'])) {
                if (print_msg(!has_permissions('update', 'insect_pests'), PERMISSION_ERROR_MSG, 'insect_pests')) {
                    return false;
                }
            } else {
                if (print_msg(!has_permissions('create', 'insect_pests'), PERMISSION_ERROR_MSG, 'insect_pests')) {
                    return false;
                }
            }

            $this->form_validation->set_rules('insect_pest_input_name', 'Insect / Pest Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('banner', 'Banner', 'trim|xss_clean');

            if (isset($_POST['edit_insect_pest'])) {

                $this->form_validation->set_rules('insect_pest_input_image', 'Image', 'trim|xss_clean');
            } else {
                $this->form_validation->set_rules('insect_pest_input_image', 'Image', 'trim|required|xss_clean', array('required' => 'Insect / Pest image is required'));
            }


            if (!$this->form_validation->run()) {

                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {

                $this->insect_pest_model->add_insect_pest($_POST);
                $this->response['error'] = false;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $message = (isset($_POST['edit_insect_pest'])) ? 'Insect / Pest Updated Successfully' : 'Insect / Pest Added Successfully';
                $this->response['message'] = $message;
                print_r(json_encode($this->response));
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function update_insect_pest_order()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (print_msg(!has_permissions('update', 'insect_pest_order'), PERMISSION_ERROR_MSG, 'insect_pest_order', false)) {
                return false;
            }
            $i = 0;
            $temp = array();
            foreach ($_GET['insect_pest_id'] as $row) {
                $temp[$row] = $i;
                $data = [
                    'row_order' => $i
                ];
                $data = escape_array($data);
                $this->db->where(['id' => $row])->update('insect_pests', $data);
                $i++;
            }

            $response['error'] = false;
            $response['message'] = 'Insect / Pest Order Saved !';

            print_r(json_encode($response));
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
