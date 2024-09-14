<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Webinars extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation', 'upload']);
        $this->load->helper(['url', 'language', 'file']);
        $this->load->model('Webinar_model');
        
        $this->load->library('ckeditor');
        $this->ckeditor->basePath = base_url().'assets/ckeditor/';
        $this->ckeditor->config['language'] = 'en';
        $this->ckeditor->config['width']    = '100%';
        $this->ckeditor->config['height']   = '250px'; 
        
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'manage-webinar';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Webinar Management | ' . $settings['app_name'];
            $this->data['meta_description'] = ' Webinar Management  | ' . $settings['app_name'];
            
            $this->data['page_title'] = 'Webinars';
            
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }
    
    public function view_webinars()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            return $this->Webinar_model->get_webinars_list();
        } else {
            redirect('admin/login', 'refresh');
        }
    }
    
    public function manage_new_webinar()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = FORMS . 'new_webinar';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Add Webinar | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Add Webinar | ' . $settings['app_name'];
            $this->data['categories'] = $this->category_model->get_categories();
            if(isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                $this->data['title'] = 'Update Webinar | ' . $settings['app_name'];
                $this->data['meta_description'] = 'Update Webinar | ' . $settings['app_name'];
                
                $this->data['fetched_data'] = $this->db->select(' w.*, u.username ')
                    ->join('users u', ' w.user_id = u.id ')
                    ->join('users_groups ug', ' ug.user_id = u.id ')
                    ->where(['w.id' => $_GET['edit_id']])
                    ->get('webinars w')
                    ->result_array();
                    
            }
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }
    
    public function add_new_webinar()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

            $this->form_validation->set_rules('title', 'Webinar Title', 'trim|required|xss_clean');
            $this->form_validation->set_rules('date', 'Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('time', 'Time', 'trim|required|xss_clean');
            $this->form_validation->set_rules('speakers', 'Speakers', 'trim|required|xss_clean');
            $this->form_validation->set_rules('organization', 'organization', 'trim|required|xss_clean');
            //$this->form_validation->set_rules('description', 'Details', 'trim|required|xss_clean');

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {

                $this->Webinar_model->add_webinar($_POST);
                $this->response['error'] = false;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                
                if($_POST['edit_id'])
                {
                    $this->response['message'] = 'Webinar Details Updated Successfully';
                }
                else
                {
                    $this->response['message'] = 'Webinar Details Added Successfully';
                }
                
                print_r(json_encode($this->response));
                
                
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    
    public function delete_webinar()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (delete_details(['id' => $_GET['id']], 'webinars') == TRUE) {
                $response['error'] = false;
                $response['message'] = 'Deleted Succesfully';
            } else {
                $response['error'] = true;
                $response['message'] = 'Something Went Wrong';
            }
            print_r(json_encode($response));
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
