<?php defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->lang->load('auth');
        $this->data['is_logged_in'] = ($this->ion_auth->logged_in()) ? 1 : 0;
        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();
        $this->data['settings'] = get_settings('system_settings', true);
        $this->data['web_settings'] = get_settings('web_settings', true);
    }
    
    public function index()
    {
        if($this->ion_auth->logged_in()) {
            redirect(base_url());
        }
        $this->data['main_page'] = 'register';
        $this->data['title'] = 'Register | ' . $this->data['web_settings']['site_title'];
        
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function register_user()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Mail', 'trim|required|xss_clean|valid_email|is_unique[users.email]', array('is_unique' => ' The email is already registered . Please login'));
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean|min_length[5]|numeric|is_unique[users.mobile]', array('is_unique' => ' The mobile number is already registered . Please login'));
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();
        if (!$this->form_validation->run()) {
            //$this->response['error'] = true;
            //$this->response['message'] = strip_tags(validation_errors());
            //$this->response['data'] = array();
            redirect(base_url().'register');
        } else {
            
            $identity_column = $this->config->item('identity', 'ion_auth');
            $email = strtolower($this->input->post('email'));
            $mobile = $this->input->post('mobile');
            $identity = ($identity_column == 'mobile') ? $mobile : $email;
            $password = $this->input->post('password');

            $additional_data = [
                'username' => $this->input->post('name'),
                'mobile' => $this->input->post('mobile'),
                'active' => 1
            ];

            $res = $this->ion_auth->register($identity, $password, $email, $additional_data, ['2']);
            update_details(['active' => 1], [$identity_column => $identity], 'users');
            $data = $this->db->select('u.id,u.username,u.email,u.mobile,c.name as city_name,a.name as area_name')->where([$identity_column => $identity])->join('cities c', 'c.id=u.city', 'left')->join('areas a', 'a.city_id=c.id', 'left')->group_by('email')->get('users u')->result_array();

            /*$this->response['error'] = false;
            $this->response['message'] = 'Registered Successfully';
            $this->response['data'] = $data;*/
            
            $login = $this->ion_auth->login($this->input->post('mobile'), $this->input->post('password'));
            if ($login) {
                $data = fetch_details(['mobile' => $this->input->post('mobile', true)], 'users');
                $username = $this->session->set_userdata('username', $data[0]['username']);
                redirect(base_url());
            }
            
        }
        print_r(json_encode($this->response));
    }
}
