<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Formulations extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model(['Formulations_model', 'category_model']);
        if (!has_permissions('read', 'formulation')) {
            $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
            redirect('admin/home', 'refresh');
        }
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'formulations';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Formulations Management | ' . $settings['app_name'];
            $this->data['meta_description'] = ' Formulations Management  | ' . $settings['app_name'];
            $this->data['categories'] = $this->category_model->get_categories();
            if (isset($_GET['edit_id'])) {
                $formulation_data = fetch_details(['id' => $_GET['edit_id']], 'formulations');
                $this->data['fetched_data'] = $formulation_data;
            }
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }


    public function add_formulation()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

            if (isset($_POST['edit_formulation'])) {
                if (print_msg(!has_permissions('update', 'formulation'), PERMISSION_ERROR_MSG, 'formulation')) {
                    return false;
                }
            } else {
                if (print_msg(!has_permissions('create', 'formulation'), PERMISSION_ERROR_MSG, 'formulation')) {
                    return false;
                }
            }

            $this->form_validation->set_rules('title', ' Title ', 'trim|required|xss_clean');
            $this->form_validation->set_rules('short_description', ' Short Description ', 'trim|required|xss_clean');
            
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
            } else {

                if (isset($_POST['edit_formulation'])) {

                    if (is_exist(['title' => $_POST['title']], 'formulations', $_POST['edit_formulation'])) {
                        $response["error"]   = true;
                        $response['csrfName'] = $this->security->get_csrf_token_name();
                        $response['csrfHash'] = $this->security->get_csrf_hash();
                        $response["message"] = "Title Already Exists !";
                        $response["data"] = array();
                        echo json_encode($response);
                        return false;
                    }
                } else {
                    if (is_exist(['title' => $_POST['title']], 'formulations')) {
                        $response["error"]   = true;
                        $response['csrfName'] = $this->security->get_csrf_token_name();
                        $response['csrfHash'] = $this->security->get_csrf_hash();
                        $response["message"] = "Title Already Exists !";
                        $response["data"] = array();
                        echo json_encode($response);
                        return false;
                    }
                }

                $this->Formulations_model->add_formulation($_POST);
                $this->response['error'] = false;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $message = (isset($_POST['edit_formulation'])) ? 'Category Block Updated Successfully' : 'Category Block Added Successfully';
                $this->response['message'] = $message;
            }
            print_r(json_encode($this->response));
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function formulation_order()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'formulation-order';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Category Block Order | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Category Block Order | ' . $settings['app_name'];
            $formulations = $this->db->select('*')->order_by('row_order')->get('formulations')->result_array();
            $this->data['formulation_result'] = $formulations;
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function update_formulation_order()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $this->response['error'] = true;
                $this->response['message'] = DEMO_VERSION_MSG;
                echo json_encode($this->response);
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                return false;
                exit();
            }
            $i = 0;
            $temp = array();
            foreach ($_GET['formulation_id'] as $row) {
                $temp[$row] = $i;
                $data = [
                    'row_order' => $i
                ];
                $data = escape_array($data);
                $this->db->where(['id' => $row])->update('formulations', $data);
                $i++;
            }
            echo json_encode(array('error'=>false,'message'=>'Category Block Ordering Successful'));
        } else {
            redirect('admin/login', 'refresh');
        }
    }


    public function get_formulation_list()
    {

        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            return $this->Formulations_model->get_formulation_list();
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function delete_formulation()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

            if (print_msg(!has_permissions('delete', 'formulation'), PERMISSION_ERROR_MSG, 'formulation', false)) {
                return false;
            }
            if (delete_details(['id' => $_GET['id']], 'sections') == TRUE) {
                $this->response['error'] = false;
                $this->response['message'] = 'Deleted Succesfully';
                print_r(json_encode($this->response));
            } else {
                $this->response['error'] = false;
                $this->response['message'] = 'Something Went Wrong';
                print_r(json_encode($this->response));
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
