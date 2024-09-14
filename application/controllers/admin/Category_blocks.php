<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category_blocks extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model(['Category_blocks_model', 'category_model']);
        if (!has_permissions('read', 'category_block')) {
            $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
            redirect('admin/home', 'refresh');
        }
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'category_blocks';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Category Blocks Management | ' . $settings['app_name'];
            $this->data['meta_description'] = ' Category Blocks Management  | ' . $settings['app_name'];
            $this->data['super_categories'] = $this->category_model->get_super_categories();
            $this->data['categories'] = $this->category_model->get_categories();
            if (isset($_GET['edit_id'])) {
                $category_block_data = fetch_details(['id' => $_GET['edit_id']], 'category_blocks');
                $this->data['fetched_data'] = $category_block_data;
            }
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }


    public function add_category_block()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

            if (isset($_POST['edit_category_block'])) {
                if (print_msg(!has_permissions('update', 'category_block'), PERMISSION_ERROR_MSG, 'category_block')) {
                    return false;
                }
            } else {
                if (print_msg(!has_permissions('create', 'category_block'), PERMISSION_ERROR_MSG, 'category_block')) {
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

                if (isset($_POST['edit_category_block'])) {

                    if (is_exist(['title' => $_POST['title']], 'category_blocks', $_POST['edit_category_block'])) {
                        $response["error"]   = true;
                        $response['csrfName'] = $this->security->get_csrf_token_name();
                        $response['csrfHash'] = $this->security->get_csrf_hash();
                        $response["message"] = "Title Already Exists !";
                        $response["data"] = array();
                        echo json_encode($response);
                        return false;
                    }
                } else {
                    if (is_exist(['title' => $_POST['title']], 'category_blocks')) {
                        $response["error"]   = true;
                        $response['csrfName'] = $this->security->get_csrf_token_name();
                        $response['csrfHash'] = $this->security->get_csrf_hash();
                        $response["message"] = "Title Already Exists !";
                        $response["data"] = array();
                        echo json_encode($response);
                        return false;
                    }
                }

                $this->Category_blocks_model->add_category_block($_POST);
                $this->response['error'] = false;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $message = (isset($_POST['edit_category_block'])) ? 'Category Block Updated Successfully' : 'Category Block Added Successfully';
                $this->response['message'] = $message;
            }
            print_r(json_encode($this->response));
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function category_block_order()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'category_block-order';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Category Block Order | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Category Block Order | ' . $settings['app_name'];
            $category_blocks = $this->db->select('*')->order_by('row_order')->get('category_blocks')->result_array();
            $this->data['category_block_result'] = $category_blocks;
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function update_category_block_order()
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
            foreach ($_GET['category_block_id'] as $row) {
                $temp[$row] = $i;
                $data = [
                    'row_order' => $i
                ];
                $data = escape_array($data);
                $this->db->where(['id' => $row])->update('category_blocks', $data);
                $i++;
            }
            echo json_encode(array('error'=>false,'message'=>'Category Block Ordering Successful'));
        } else {
            redirect('admin/login', 'refresh');
        }
    }


    public function get_category_block_list()
    {

        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            return $this->Category_blocks_model->get_category_block_list();
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function delete_category_block()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

            if (print_msg(!has_permissions('delete', 'category_block'), PERMISSION_ERROR_MSG, 'category_block', false)) {
                return false;
            }
            if (delete_details(['id' => $_GET['id']], 'category_blocks') == TRUE) {
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
