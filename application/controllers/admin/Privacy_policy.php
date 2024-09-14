<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Privacy_policy extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model('Setting_model');
        if (!has_permissions('read', 'privacy_policy')) {
            $this->session->set_flashdata('authorize_flag', PERMISSION_ERROR_MSG);
            redirect('admin/home', 'refresh');
        }
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = FORMS . 'privacy-policy';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Privacy Policy | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Privacy Policy | ' . $settings['app_name'];
            $this->data['privacy_policy'] = get_settings('privacy_policy');
            $this->data['shipping_policy'] = get_settings('shipping_policy');
            $this->data['return_policy'] = get_settings('return_policy');
            $this->data['quality_policy'] = get_settings('quality_policy');
            $this->data['pricing_policy'] = get_settings('pricing_policy');
            $this->data['delivery_policy'] = get_settings('delivery_policy');
            $this->data['payment_policy'] = get_settings('payment_policy');
            $this->data['security_policy'] = get_settings('security_policy');
            $this->data['partnering_policy'] = get_settings('partnering_policy');
            $this->data['licensing_policy'] = get_settings('licensing_policy');
            $this->data['terms_n_condition'] = get_settings('terms_conditions');
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }


    public function update_privacy_policy_settings()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            if (print_msg(!has_permissions('update', 'privacy_policy'), PERMISSION_ERROR_MSG, 'privacy_policy')) {
                return false;
            }

            $this->form_validation->set_rules('terms_n_conditions_input_description', 'Terms and Condition Description', 'trim|required|xss_clean');
            $this->form_validation->set_rules('privacy_policy_input_description', 'Privay Policy Description', 'trim|required|xss_clean');
            $this->form_validation->set_rules('shipping_policy_input_description', 'Shipping Policy Description', 'trim|required|xss_clean');
            $this->form_validation->set_rules('return_policy_input_description', 'Return Policy Description', 'trim|required|xss_clean');
            $this->form_validation->set_rules('quality_policy_input_description', 'Quality Policy Description', 'trim|required|xss_clean');
            $this->form_validation->set_rules('pricing_policy_input_description', 'Pricing Policy Description', 'trim|required|xss_clean');
            $this->form_validation->set_rules('delivery_policy_input_description', 'Delivery Policy Description', 'trim|required|xss_clean');
            $this->form_validation->set_rules('payment_policy_input_description', 'Payment Policy Description', 'trim|required|xss_clean');
            $this->form_validation->set_rules('security_policy_input_description', 'Security Policy Description', 'trim|required|xss_clean');
            $this->form_validation->set_rules('partnering_policy_input_description', 'Partnering Policy Description', 'trim|required|xss_clean');
            $this->form_validation->set_rules('licensing_policy_input_description', 'Licensing Policy Description', 'trim|required|xss_clean');


            if (!$this->form_validation->run()) {

                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $this->Setting_model->update_privacy_policy($_POST);
                $this->Setting_model->update_shipping_policy($_POST);
                $this->Setting_model->update_return_policy($_POST);
                $this->Setting_model->update_quality_policy($_POST);
                $this->Setting_model->update_pricing_policy($_POST);
                $this->Setting_model->update_delivery_policy($_POST);
                $this->Setting_model->update_payment_policy($_POST);
                $this->Setting_model->update_security_policy($_POST);
                $this->Setting_model->update_partnering_policy($_POST);
                $this->Setting_model->update_licensing_policy($_POST);
                $this->Setting_model->update_terms_n_condtions($_POST);
                $this->response['error'] = false;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = 'System Setting Updated Successfully';
                print_r(json_encode($this->response));
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function privacy_policy_page()
    {
        $settings = get_settings('system_settings', true);
        $this->data['title'] = 'Privacy Policy | ' . $settings['app_name'];
        $this->data['meta_description'] = 'Privacy Policy | ' . $settings['app_name'];
        $this->data['privacy_policy'] = get_settings('privacy_policy');
        $this->load->view('admin/pages/view/privacy-policy', $this->data);
    }

    public function terms_and_conditions_page()
    {
        $settings = get_settings('system_settings', true);
        $this->data['title'] = 'Terms & Conditions | ' . $settings['app_name'];
        $this->data['meta_description'] = 'Terms & Conditions | ' . $settings['app_name'];
        $this->data['terms_and_conditions'] = get_settings('terms_conditions');
        $this->load->view('admin/pages/view/terms-and-conditions', $this->data);
    }
    
    public function shipping_policy_page()
    {
        $settings = get_settings('system_settings', true);
        $this->data['title'] = 'Shipping Policy | ' . $settings['app_name'];
        $this->data['meta_description'] = 'Shipping Policy | ' . $settings['app_name'];
        $this->data['privacy_policy'] = get_settings('shipping_policy');
        $this->load->view('admin/pages/view/privacy-policy', $this->data);
    }
    
    public function return_policy_page()
    {
        $settings = get_settings('system_settings', true);
        $this->data['title'] = 'Return Policy | ' . $settings['app_name'];
        $this->data['meta_description'] = 'Return Policy | ' . $settings['app_name'];
        $this->data['privacy_policy'] = get_settings('return_policy');
        $this->load->view('admin/pages/view/privacy-policy', $this->data);
    }
    
    public function quality_policy_page()
    {
        $settings = get_settings('system_settings', true);
        $this->data['title'] = 'Quality Policy | ' . $settings['app_name'];
        $this->data['meta_description'] = 'Quality Policy | ' . $settings['app_name'];
        $this->data['privacy_policy'] = get_settings('quality_policy');
        $this->load->view('admin/pages/view/privacy-policy', $this->data);
    }
    
    public function pricing_policy_page()
    {
        $settings = get_settings('system_settings', true);
        $this->data['title'] = 'Pricing Policy | ' . $settings['app_name'];
        $this->data['meta_description'] = 'Pricing Policy | ' . $settings['app_name'];
        $this->data['privacy_policy'] = get_settings('pricing_policy');
        $this->load->view('admin/pages/view/privacy-policy', $this->data);
    }
    
    public function delivery_policy_page()
    {
        $settings = get_settings('system_settings', true);
        $this->data['title'] = 'Delivery Policy | ' . $settings['app_name'];
        $this->data['meta_description'] = 'Delivery Policy | ' . $settings['app_name'];
        $this->data['privacy_policy'] = get_settings('delivery_policy');
        $this->load->view('admin/pages/view/privacy-policy', $this->data);
    }
    
    public function payment_policy_page()
    {
        $settings = get_settings('system_settings', true);
        $this->data['title'] = 'Payment Policy | ' . $settings['app_name'];
        $this->data['meta_description'] = 'Payment Policy | ' . $settings['app_name'];
        $this->data['privacy_policy'] = get_settings('payment_policy');
        $this->load->view('admin/pages/view/privacy-policy', $this->data);
    }
    
    public function security_policy_page()
    {
        $settings = get_settings('system_settings', true);
        $this->data['title'] = 'Security Policy | ' . $settings['app_name'];
        $this->data['meta_description'] = 'Security Policy | ' . $settings['app_name'];
        $this->data['privacy_policy'] = get_settings('security_policy');
        $this->load->view('admin/pages/view/privacy-policy', $this->data);
    }
    
    public function partnering_policy_page()
    {
        $settings = get_settings('system_settings', true);
        $this->data['title'] = 'Partnering Policy | ' . $settings['app_name'];
        $this->data['meta_description'] = 'Partnering Policy | ' . $settings['app_name'];
        $this->data['privacy_policy'] = get_settings('partnering_policy');
        $this->load->view('admin/pages/view/privacy-policy', $this->data);
    }
}
