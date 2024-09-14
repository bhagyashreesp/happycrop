<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->model(['Home_model', 'Order_model']);
    }
    
    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $user_id = $this->session->userdata('user_id');
            $user_res = $this->db->select('balance,username,mfg_no,created_at')->where('id', $user_id)->get('users')->result_array(); 
            $this->data['main_page'] = FORMS . 'home';
            $settings = get_settings('system_settings', true);
            $this->data['curreny'] = get_settings('currency');
            $this->data['title'] = 'Manufacturer Panel | ' . $settings['app_name'];
            $this->data['order_counter'] = orders_count("", $user_id);
            $this->data['user_counter'] = (get_seller_permission($this->ion_auth->get_user_id(), 'customer_privacy')) ? $this->Home_model->count_new_users() : 0;

            $this->data['mfg_no']  = ($user_res[0]['mfg_no']) ? 'HCM'.str_pad($user_res[0]['mfg_no'],4,"0",STR_PAD_LEFT) : '';
            $this->data['balance'] = ($user_res[0]['balance'] == NULL) ? 0 : $user_res[0]['balance'];
            $this->data['products'] = $this->Home_model->count_products($user_id);
            $this->data['username'] =  $user_res[0]['username'];
            $this->data['join_date'] = date('d/m/Y', strtotime($user_res[0]['created_at']));
            $this->data['ratings'] =  fetch_details(['user_id' => $user_id],"seller_data","rating,no_of_ratings");
            $this->data['meta_description'] = 'Seller Panel | ' . $settings['app_name'];
            $this->data['count_products_low_status'] = $this->Home_model->count_products_stock_low_status($user_id);
            $this->data['count_products_availability_status'] = $this->Home_model->count_products_availability_status($user_id);
            
            $orders_count['total_sales']        = mfg_orders_total($user_id);
            $orders_count['total_orders']       = mfg_orders_count("",$user_id);
            
            $orders_count['new_orders']         = mfg_orders_count(array('received'), $user_id);
            $orders_count['in_process_orders']  = mfg_orders_count(array('payment_demand','payment_ack','schedule_delivery','send_payment_confirmation',), $user_id);
            $orders_count['shipped_orders']     = mfg_orders_count("send_invoice", $user_id);
            $orders_count['issue_raised_orders']= mfg_orders_count(array("complaint","complaint_msg"), $user_id);
            $orders_count['cancelled_orders']   = mfg_orders_count(array("cancelled"), $user_id);
            $orders_count['delivered_orders']   = mfg_orders_count(array("delivered","send_mfg_payment_ack","send_mfg_payment_confirmation"), $user_id);
            
            $this->data['status_counts'] = $orders_count;
            
            $this->data['seller_data'] = $this->db->get_where('seller_data', array('user_id'=>$user_id))->row_array();
            
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }
    
    public function seller_recent_orders()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $seller_id = $this->ion_auth->get_user_id();
            return $this->Order_model->get_recent_order_items_list(NULL,0,3,'oi.id','DESC',$seller_id);
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    /*public function profile()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $settings = get_settings('system_settings', true);
            $user_id = $this->session->userdata('user_id');
            $this->data['identity_column'] = $identity_column;
            $this->data['main_page'] = FORMS . 'profile';
            $this->data['title'] = 'Seller Profile | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Seller Profile | ' . $settings['app_name'];
            $this->data['fetched_data'] = $this->db->select(' u.*,sd.* ')
                ->join('users_groups ug', ' ug.user_id = u.id ')
                ->join('seller_data sd', ' sd.user_id = u.id ')
                ->where(['ug.group_id' => '4', 'ug.user_id' => $user_id])
                ->get('users u')
                ->result_array();
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/home', 'refresh');
        }
    }*/

    public function update_status()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            /*if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $this->response['error'] = true;
                $this->response['message'] = DEMO_VERSION_MSG;
                echo json_encode($this->response);
                return false;
                exit();
            }*/
            if ($_GET['status'] == '1') {
                $_GET['status'] = 0;
            } else if ($_GET['status'] == '0') {
                $_GET['status'] = 1;
            }
            $this->db->trans_start();
            if ($_GET['table'] == 'users') {
                $this->db->set('active', $this->db->escape($_GET['status']));
            } else {
                $this->db->set('status', $this->db->escape($_GET['status']));
            }

            $this->db->where('id', $_GET['id'])->update($_GET['table']);
            $this->db->trans_complete();
            $error = false;
            $message = str_replace('_', ' ', $_GET['table']);
            if ($this->db->trans_status() === true) {
                $error = true;
            }
            $response['error'] = $error;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = $message;
            print_r(json_encode($response));
        } else {
            redirect('seller/login', 'refresh');
        }
    }
    
    public function update_product_stock_status()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            /*if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $this->response['error'] = true;
                $this->response['message'] = DEMO_VERSION_MSG;
                echo json_encode($this->response);
                return false;
                exit();
            }*/
            if ($_GET['product_stock_status'] == '1') {
                $_GET['product_stock_status'] = 0;
            } else if ($_GET['product_stock_status'] == '0') {
                $_GET['product_stock_status'] = 1;
            }
            
            $this->db->trans_start();
            $this->db->set('product_stock_status', $this->db->escape($_GET['product_stock_status']));
            $this->db->where('id', $_GET['id'])->update($_GET['table']);
            $this->db->trans_complete();
            
            $error = false;
            $message = str_replace('_', ' ', $_GET['table']);
            if ($this->db->trans_status() === true) {
                $error = true;
            }
            
            $response['error'] = $error;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = $message;
            print_r(json_encode($response));
        } else {
            redirect('seller/login', 'refresh');
        }
    }
    
    public function fetch_orders_count()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $sales[] = array();
            $user_id = $this->ion_auth->get_user_id();

            $month_res = $this->db->select('count(DISTINCT a.id) AS total_sale,DATE_FORMAT(a.date_added,"%b") AS month_name ')
                ->join('`order_items` as b','a.id = b.order_id')
                ->where('b.seller_id', $user_id)
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
                ->join('`order_items` as b','a.id = b.order_id')
                ->where('b.seller_id', $user_id)
                ->where("date(a.date_added) >='$start' and date(a.date_added) <= '$end' ")
                ->group_by('day(a.date_added)')->get('`orders` as a')->result_array();
            //echo $this->db->last_query();die;
            $week_wise_sales['total_sale'] = array_map('intval', array_column($week_res, 'total_sale'));
            $week_wise_sales['week'] = array_column($week_res, 'date');

            $sales[1] = $week_wise_sales;

            $day_res = $this->db->select("DAY(a.date_added) as date, count(DISTINCT a.id) as total_sale")
                ->join('`order_items` as b','a.id = b.order_id')
                ->where('b.seller_id', $user_id)
                ->where('a.date_added >= DATE_SUB(CURDATE(), INTERVAL 29 DAY)')
                ->group_by('day(a.date_added)')->order_by('a.date_added','asc')->get('`orders` as a')->result_array();
            $day_wise_sales['total_sale'] = array_map('intval', array_column($day_res, 'total_sale'));
            $day_wise_sales['day'] = array_column($day_res, 'date');
            
            //echo $this->db->last_query();die;

            $sales[2] = $day_wise_sales;
            print_r(json_encode($sales));
        } else {
            redirect('seller/login', 'refresh');
        }
    }
    
    public function fetch_sales()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $sales[] = array();
            $user_id = $this->ion_auth->get_user_id();

            $month_res = $this->db->select('SUM(sub_total) AS total_sale,DATE_FORMAT(date_added,"%b") AS month_name ')
                ->where('seller_id', $user_id)
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
                ->where('seller_id', $user_id)
                ->where("date(date_added) >='$start' and date(date_added) <= '$end' ")
                ->group_by('day(date_added)')->get('`order_items`')->result_array();

            $week_wise_sales['total_sale'] = array_map('intval', array_column($week_res, 'total_sale'));
            $week_wise_sales['week'] = array_column($week_res, 'date');

            $sales[1] = $week_wise_sales;

            $day_res = $this->db->select("DAY(date_added) as date, SUM(sub_total) as total_sale")
                ->where('seller_id', $user_id)
                ->where('date_added >= DATE_SUB(CURDATE(), INTERVAL 29 DAY)')
                ->group_by('day(date_added)')->order_by('date_added','asc')->get('`order_items`')->result_array();
            $day_wise_sales['total_sale'] = array_map('intval', array_column($day_res, 'total_sale'));
            $day_wise_sales['day'] = array_column($day_res, 'date');
            
            //echo $this->db->last_query();die;

            $sales[2] = $day_wise_sales;
            print_r(json_encode($sales));
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function category_wise_product_count()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $user_id = $this->session->userdata('user_id');
            $this->db->select('category_ids');
            $where = 'user_id = ' . $user_id;
            $this->db->where($where);
            $result = $this->db->get('seller_data')->result_array();
            $result = explode(",", $result[0]['category_ids']);
        
            $res = $this->db->select('c.name as name,count(c.id) as counter')->group_Start()->where_in('c.id', $result)->group_End()->where(['p.status' => '1', 'c.status' => '1'])->join('products p', 'p.category_id=c.id')->group_by('c.id')->get('categories c')->result_array();
            $result = array();
            $result[0][] = 'Task';
            $result[0][] = 'Hours per Day';
            array_walk($res, function ($v, $k) use (&$result) {
                $result[$k + 1][] = $v['name'];
                $result[$k + 1][] = intval($v['counter']);
            });
            echo json_encode(array_values($result));
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function logout()
    {
        $this->ion_auth->logout();
        //redirect('seller/login', 'refresh');
        redirect('register/step2/1','refresh');
    }
}
