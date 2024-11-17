<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Orders extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model('Order_model');
        $this->load->model('Account_model');
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->data['main_page'] = TABLES . 'manage-orders';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'View Orders | ' . $settings['app_name'];
            $this->data['meta_description'] = ' View Order  | ' . $settings['app_name'];

            $this->data['page_title'] = 'Orders';

            $user_id = $this->session->userdata('user_id');
            $orders_count['total_orders']       = mfg_orders_count("", $user_id);

            $orders_count['new_orders']         = mfg_orders_count(array('received'), $user_id);
            $orders_count['in_process_orders']  = mfg_orders_count(array('payment_demand', 'payment_ack', 'schedule_delivery', 'send_payment_confirmation',), $user_id);
            $orders_count['shipped_orders']     = mfg_orders_count("send_invoice", $user_id);
            $orders_count['issue_raised_orders'] = mfg_orders_count(array("complaint", "complaint_msg"), $user_id);
            $orders_count['cancelled_orders']   = mfg_orders_count(array("cancelled"), $user_id);
            $orders_count['delivered_orders']   = mfg_orders_count(array("delivered", "send_mfg_payment_ack", "send_mfg_payment_confirmation"), $user_id);

            $this->data['status_counts'] = $orders_count;

            $this->data['about_us'] = get_settings('about_us');
            $this->data['curreny'] = get_settings('currency');
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }


    public function show_orders($condition = 0)
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->data['main_page'] = TABLES . 'manage-orders';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'View Orders | ' . $settings['app_name'];
            $this->data['meta_description'] = ' View Order  | ' . $settings['app_name'];

            $this->data['page_title'] = 'Orders';

            if ($condition == 1) {
                $this->data['page_title'] = 'In Process Orders';
            } else if ($condition == 2) {
                $this->data['page_title'] = 'Shipped Orders';
            } else if ($condition == 3) {
                $this->data['page_title'] = 'Issue Raised Orders';
            } else if ($condition == 4) {
                $this->data['page_title'] = 'Cancelled Orders';
            } else if ($condition == 5) {
                $this->data['page_title'] = 'Delivered Orders';
            } else if ($condition == 6) {
                $this->data['page_title'] = 'New Orders';
            } else if ($condition == 7) {
                $this->data['page_title'] = 'Action Advised Orders';
            }

            $this->data['about_us'] = get_settings('about_us');
            $this->data['curreny'] = get_settings('currency');
            $this->data['condition'] = $condition;
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function view_orders()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $deliveryBoyId = $this->ion_auth->get_user_id();
            return $this->Order_model->get_orders_list($deliveryBoyId);
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function view_seller_orders()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $seller_id = $this->ion_auth->get_user_id();
            return $this->Order_model->get_seller_orders_list($seller_id);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function view_order_items()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $seller_id = $this->ion_auth->get_user_id();
            return $this->Order_model->get_order_items_list(NULL, 0, 10, 'oi.id', 'DESC', $seller_id);
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function edit_orders()
    {

        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {

            $bank_transfer = array();
            $this->data['main_page'] = FORMS . 'edit-orders';
            $settings = get_settings('system_settings', true);

            $this->data['title'] = 'View Order | ' . $settings['app_name'];
            $this->data['meta_description'] = 'View Order | ' . $settings['app_name'];
            $seller_id = $this->session->userdata('user_id');
            $res = $this->Order_model->get_order_details(['o.id' => $_GET['edit_id'], 'oi.seller_id' => $seller_id]);

            if (is_exist(['id' => $res[0]['address_id']], 'addresses')) {
                /*
                $area_id = fetch_details(['id' => $res[0]['address_id']], 'addresses', 'area_id');
                if (!empty($area_id)) {
                    $zipcode_id = fetch_details(['id' => $area_id[0]['area_id']], 'areas', 'zipcode_id');
                    $this->data['delivery_res'] = $this->db->where(['ug.group_id' => '3', 'u.active' => 1])->where('find_in_set(' . $zipcode_id[0]['zipcode_id'] . ', u.serviceable_zipcodes)!=', 0)->join('users_groups ug', 'ug.user_id = u.id')->get('users u')->result_array();
                }*/
            } else {
                $this->data['delivery_res'] = $this->db->where(['ug.group_id' => '3', 'u.active' => 1])->join('users_groups ug', 'ug.user_id = u.id')->get('users u')->result_array();
            }
            if ($res[0]['payment_method'] == "bank_transfer") {
                $bank_transfer = fetch_details(['order_id' => $res[0]['order_id']], 'order_bank_transfer');
            }
            if (isset($_GET['edit_id']) && !empty($_GET['edit_id']) && !empty($res) && is_numeric($_GET['edit_id'])) {
                $items = [];
                foreach ($res as $row) {
                    $temp['id'] = $row['order_item_id'];
                    $temp['product_id'] = $row['product_id'];
                    $temp['item_otp'] = $row['item_otp'];
                    $temp['tracking_id'] = $row['tracking_id'];
                    $temp['courier_agency'] = $row['courier_agency'];
                    $temp['url'] = $row['url'];
                    $temp['product_variant_id'] = $row['product_variant_id'];
                    $temp['product_type'] = $row['type'];
                    $temp['pname'] = ($row['product_name'] != '') ? $row['product_name'] : $row['pname'];
                    $temp['quantity'] = $row['quantity'];
                    $temp['is_cancelable'] = $row['is_cancelable'];
                    $temp['is_returnable'] = $row['is_returnable'];
                    $temp['tax_amount'] = $row['tax_amount'];
                    $temp['discounted_price'] = $row['discounted_price'];
                    $temp['mrp'] = $row['mrp'];
                    $temp['special_price_per_item'] = $row['special_price_per_item'];
                    $temp['price'] = $row['price'];
                    $temp['standard_price'] = $row['standard_price'];
                    $temp['row_price'] = $row['row_price'];
                    $temp['active_status'] = $row['oi_active_status'];
                    $temp['product_image'] = $row['product_image'];
                    $temp['product_variants'] = get_variants_values_by_id($row['product_variant_id']);
                    $temp['schedule_delivery_date'] = $row['schedule_delivery_date'];
                    $temp['packing_size'] = $row['packing_size'];
                    $temp['pv_packing_size'] = $row['pv_packing_size'];
                    $temp['unit'] = $row['unit'];
                    $temp['pv_unit'] = $row['pv_unit'];
                    $temp['carton_qty'] = $row['carton_qty'];
                    $temp['pv_carton_qty'] = $row['pv_carton_qty'];
                    $temp['minimum_order_quantity'] = $row['minimum_order_quantity'];
                    $temp['tax_percentage'] = $row['tax_percentage'];

                    $temp['mfg_date'] = $row['mfg_date'];
                    $temp['exp_date'] = $row['exp_date'];
                    $temp['batch_no'] = $row['batch_no'];

                    array_push($items, $temp);
                }
                if (!empty($res)) {
                    $orderState = fetch_details(["id" => $res[0]["address_id"]], "addresses");
                    $userdetails = fetch_details(["id" => $res[0]["user_id"]], "users");
                    if (!empty($orderState)) {
                        $res[0]["state"] = $orderState[0]["state"];
                    }
                    if (!empty($userdetails)) {
                        $res[0]["email"] = $userdetails[0]["email"];
                    }


                    $this->db->distinct();
                    $this->db->select('a.seller_id, b.username, b.mobile, b.email, b.mfg_no, c.company_name, c.gst_no, c.fertilizer_license_no, c.pesticide_license_no, c.seeds_license_no, c.account_name, c.account_number, c.bank_name, c.bank_code, c.bank_city, c.bank_branch, c.bank_state, c.plot_no, c.street_locality, c.landmark, cc.name, city, s.name as state, c.pin');
                    $this->db->from('order_items as a');
                    $this->db->join('users as b', 'a.seller_id = b.id', 'left');
                    $this->db->join('seller_data as c', 'a.seller_id = c.user_id', 'left');
                    $this->db->join('states as s', 'c.state_id = s.id', 'left');
                    $this->db->join('cities as cc', 'c.city_id = cc.id', 'left');
                    $this->db->where('a.order_id', $res[0]['id']);
                    $query = $this->db->get();

                    $manufactures = $query->result_array();
                    if (!empty($manufactures)) {
                        $this->data['manufacture'] = $manufactures[0];
                    }
                }

                $getterms = $this->common_model->getRecords('terms_conditions', '*', array("user_id" => $order["order_data"][0]["seller_id"]));

                $this->data['getterms'] = $getterms;
                $this->data['order_detls'] = $res;
                $this->data['bank_transfer'] = $bank_transfer;
                $this->data['items'] = $items;
                $this->data['seller_id'] = $seller_id;
                $this->data['settings'] = get_settings('system_settings', true);
                $this->load->view('seller/template', $this->data);
            } else {
                redirect('seller/orders/', 'refresh');
            }
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function termscondition()
    {

        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {

            $bank_transfer = array();
            $this->data['main_page'] = FORMS . 'terms-conditions';
            $settings = get_settings('system_settings', true);

            $this->data['title'] = 'View Order | ' . $settings['app_name'];
            $this->data['meta_description'] = 'View Order | ' . $settings['app_name'];
            $seller_id = $this->session->userdata('user_id');
            $getterms = $this->common_model->getRecords('terms_conditions', '*', array("user_id" => $order["order_data"][0]["seller_id"]));


            $this->data["getterms"] = $getterms;
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }
    public function gettermsdetails($id)
    {
        $getterms = $this->common_model->getRecords('terms_conditions', '*', array("id" => $id));
        if(!empty($getterms)) {
            $response["status"] = true;
            $response["data"] = $getterms;
        }else {
            $response["status"] = false;
            $response["message"] = "No data found";
        }
        echo json_encode($response);


    }
    public function termdelete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete("terms_conditions");
        redirect('seller/orders/termscondition', 'refresh');

    }
    public function termsconditionsave()
    {
        $postData = $this->input->post();
        $termsArr["terms_conditions"] = $postData["terms_cond"];
        $termsArr["user_id"] = $this->session->userdata('user_id');
        if (isset($postData["term_id"]) && $postData["term_id"] != "") {
            $this->db->where('id', $postData['term_id']);
            $updateterms = $this->db->update('terms_conditions', $termsArr);
        } else {
            $ins_purchase_details = $this->db->insert("terms_conditions", $termsArr);
            $updateterms = $this->db->insert_id();
        }
        if ($updateterms) {
            $response["status"] = true;
            $response["message"] = "Data saved successfully";
        } else {
            $response["status"] = false;
            $response["message"] = "Please tr again later";
        }
        echo json_encode($response);
    }

    public function paymentreceipt($order_id, $view = "")
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


            return $this->load->view('admin/pages/view/manufacture-payment-receipt.php', $pdfdata);
        
    }
    /* To update the status of particular order item */
    public function update_order_status()
    {

        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {

            $this->form_validation->set_rules('order_item_id[]', 'Order Item ID', 'trim|required|xss_clean');
            $this->form_validation->set_rules('deliver_by', 'Delvery Boy Id', 'trim|numeric|xss_clean');
            $this->form_validation->set_rules('status', 'Status', 'trim|xss_clean|in_list[qty_update,qty_approved,qty_rejected,payment_demand,payment_ack,schedule_delivery,received,processed,shipped,delivered,cancelled,returned]');

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = strip_tags(validation_errors());
                $this->response['data'] = array();
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($this->response));
                return false;
            }
            $order_itam_ids = $_POST['order_item_id'];
            $order_items = fetch_details("", 'order_items', '*', "", "", "", "", "id", $order_itam_ids);
            if (isset($_POST['status']) && !empty($_POST['status']) && $_POST['status'] == 'delivered') {
                if (!get_seller_permission($order_items[0]['seller_id'], "view_order_otp")) {
                    $this->response['error'] = true;
                    $this->response['message'] = 'You are not allowed to update delivered status on the item.';
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
            }
            if (empty($order_items)) {
                $this->response['error'] = true;
                $this->response['message'] = 'No Order Item Found';
                $this->response['data'] = array();
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($this->response));
                return false;
            }

            if (count($order_itam_ids) != count($order_items)) {
                $this->response['error'] = true;
                $this->response['message'] = 'Some item was not found on status update';
                $this->response['data'] = array();
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($this->response));
                return false;
            }
            // delivery boy update here
            $message = '';
            $delivery_boy_updated = 0;
            $delivery_boy_id = (isset($_POST['deliver_by']) && !empty(trim($_POST['deliver_by']))) ? $this->input->post('deliver_by', true) : 0;
            if (!empty($delivery_boy_id)) {
                $delivery_boy = fetch_details(['id' => trim($delivery_boy_id)], 'users', '*');
                if (empty($delivery_boy)) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Invalid Delivery Boy";
                    $this->response['data'] = array();
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    print_r(json_encode($this->response));
                    return false;
                } else {
                    $current_delivery_boys = fetch_details("", 'order_items', 'delivery_boy_id', "", "", "", "", "id", $order_itam_ids);
                    $settings = get_settings('system_settings', true);
                    $app_name = isset($settings['app_name']) && !empty($settings['app_name']) ? $settings['app_name'] : '';
                    if (isset($current_delivery_boys[0]['delivery_boy_id']) && !empty($current_delivery_boys[0]['delivery_boy_id'])) {
                        $user_res = fetch_details("", 'users', 'fcm_id,username', "", "", "", "", "id", array_column($current_delivery_boys, "delivery_boy_id"));
                    } else {
                        $user_res = fetch_details(['id' => $delivery_boy_id], 'users', 'fcm_id,username');
                    }

                    $fcm_ids = array();
                    if (isset($user_res[0]) && !empty($user_res[0])) {
                        $current_delivery_boy = array_column($current_delivery_boys, "delivery_boy_id");
                        if (!empty($current_delivery_boy[0]) && count($current_delivery_boy) > 1) {
                            for ($i = 0; $i < count($current_delivery_boys); $i++) {
                                $fcmMsg = array(
                                    'title' => "Order status updated",
                                    'body' => 'Hello Dear ' . $user_res[$i]['username'] . ' order status updated to ' . $_POST['status'] . ' for order ID #' . $order_items[0]['order_id'] . ' assigned to you please take note of it! Thank you. Regards ' . $app_name . '',
                                    'type' => "order"
                                );
                                if (!empty($user_res[$i]['fcm_id'])) {
                                    $fcm_ids[0][] = $user_res[$i]['fcm_id'];
                                }
                            }
                            $message = 'Delivery Boy Updated.';
                            $delivery_boy_updated = 1;
                        } else {
                            if (isset($current_delivery_boys[0]['delivery_boy_id']) && $current_delivery_boys[0]['delivery_boy_id'] == $_POST['deliver_by']) {
                                $fcmMsg = array(
                                    'title' => "Order status updated.",
                                    'body' => 'Hello Dear ' . $user_res[0]['username'] . ' order status updated to ' . $_POST['status'] . ' for order ID #' . $order_items[0]['order_id'] . ' assigned to you please take note of it! Thank you. Regards ' . $app_name . '',
                                    'type' => "order"
                                );
                                $message = 'Delivery Boy Updated';
                                $delivery_boy_updated = 1;
                            } else {
                                $fcmMsg = array(
                                    'title' => "You have new order to deliver",
                                    'body' => 'Hello Dear ' . $user_res[0]['username'] . ' you have new order to be deliver order ID #' . $order_items[0]['order_id'] . ' please take note of it! Thank you. Regards ' . $app_name . '',
                                    'type' => "order"
                                );
                                $message = 'Delivery Boy Updated.';
                                $delivery_boy_updated = 1;
                            }
                            if (!empty($user_res[0]['fcm_id'])) {
                                $fcm_ids[0][] = $user_res[0]['fcm_id'];
                            }
                        }
                    }
                    if (!empty($fcm_ids)) {
                        send_notification($fcmMsg, $fcm_ids);
                    }
                    if ($this->Order_model->update_order(['delivery_boy_id' => $delivery_boy_id], $order_itam_ids, false, 'order_items')) {
                        $delivery_error = false;
                    }
                }
            }



            $item_ids = implode(",", $_POST['order_item_id']);
            $res = validate_order_status($item_ids, $_POST['status']);

            if ($res['error']) {
                $this->response['error'] = $delivery_boy_updated == 1 ? false : true;
                $this->response['message'] = (isset($_POST['status']) && !empty($_POST['status'])) ? $message . $res['message'] :  $message;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }

            if (!empty($order_items)) {
                for ($j = 0; $j < count($order_items); $j++) {
                    $order_item_id = $order_items[$j]['id'];
                    /* velidate bank transfer method status */
                    $order_method = fetch_details(['id' => $order_items[$j]['order_id']], 'orders', 'payment_method');
                    if ($order_method[0]['payment_method'] == 'bank_transfer') {
                        $bank_receipt = fetch_details(['order_id' => $order_items[$j]['order_id']], 'order_bank_transfer');
                        $transaction_status = fetch_details(['order_id' => $order_items[$j]['order_id']], 'transactions', 'status');
                        if (empty($bank_receipt) || strtolower($transaction_status[$j]['status']) != 'success' || $bank_receipt[0]['status'] == "0" || $bank_receipt[0]['status'] == "1") {
                            $this->response['error'] = true;
                            $this->response['message'] = "Order item status can not update, Bank verification is remain from transactions for this order.";
                            $this->response['csrfName'] = $this->security->get_csrf_token_name();
                            $this->response['csrfHash'] = $this->security->get_csrf_hash();
                            $this->response['data'] = array();
                            print_r(json_encode($this->response));
                            return false;
                        }
                    }

                    // processing order items
                    $order_item_res = $this->db->select(' * , (Select count(id) from order_items where order_id = oi.order_id ) as order_counter ,(Select count(active_status) from order_items where active_status ="cancelled" and order_id = oi.order_id ) as order_cancel_counter , (Select count(active_status) from order_items where active_status ="returned" and order_id = oi.order_id ) as order_return_counter,(Select count(active_status) from order_items where active_status ="delivered" and order_id = oi.order_id ) as order_delivered_counter , (Select count(active_status) from order_items where active_status ="processed" and order_id = oi.order_id ) as order_processed_counter , (Select count(active_status) from order_items where active_status ="shipped" and order_id = oi.order_id ) as order_shipped_counter , (Select status from orders where id = oi.order_id ) as order_status ')
                        ->where(['id' => $order_item_id])
                        ->get('order_items oi')->result_array();

                    if ($this->Order_model->update_order(['status' => $_POST['status']], ['id' => $order_item_res[0]['id']], true, 'order_items')) {
                        $this->Order_model->update_order(['active_status' => $_POST['status']], ['id' => $order_item_res[0]['id']], false, 'order_items');
                        process_refund($order_item_res[0]['id'], $_POST['status'], 'order_items');
                        if (trim($_POST['status']) == 'cancelled' || trim($_POST['status']) == 'returned') {
                            $data = fetch_details(['id' => $order_item_id], 'order_items', 'product_variant_id,quantity');
                            update_stock($data[0]['product_variant_id'], $data[0]['quantity'], 'plus');
                        }
                        if (($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_cancel_counter']) + 1 && $_POST['status'] == 'cancelled') ||  ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_return_counter']) + 1 && $_POST['status'] == 'returned') || ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_delivered_counter']) + 1 && $_POST['status'] == 'delivered') || ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_processed_counter']) + 1 && $_POST['status'] == 'processed') || ($order_item_res[0]['order_counter'] == intval($order_item_res[0]['order_shipped_counter']) + 1 && $_POST['status'] == 'shipped')) {
                            /* process the refer and earn */
                            $user = fetch_details(['id' => $order_item_res[0]['order_id']], 'orders', 'user_id');
                            $user_id = $user[0]['user_id'];
                            $response = process_referral_bonus($user_id, $order_item_res[0]['order_id'], $_POST['status']);
                        }
                    }

                    $order_item_stages = array('order_id' => $order_item_res[0]['order_id'], 'order_item_id' => $order_item_id, 'status' => $_POST['status'],);
                    $this->db->insert('order_item_stages', $order_item_stages);
                }
                $settings = get_settings('system_settings', true);
                $app_name = isset($settings['app_name']) && !empty($settings['app_name']) ? $settings['app_name'] : '';
                $user_res = fetch_details(['id' => $user_id], 'users', 'username,fcm_id');
                $fcm_ids = array();
                if (!empty($user_res[0]['fcm_id'])) {
                    $fcmMsg = array(
                        'title' => "Order status updated",
                        'body' => 'Hello Dear ' . $user_res[0]['username'] . ' order status updated to ' . $_POST['status'] . ' for your order ID #' . $order_item_res[0]['id'] . ' please take note of it! Thank you for shopping with us. Regards ' . $app_name . '',
                        'type' => "order"
                    );

                    $fcm_ids[0][] = $user_res[0]['fcm_id'];
                    send_notification($fcmMsg, $fcm_ids);
                }


                $this->response['error'] = false;
                $this->response['message'] = 'Status Updated Successfully';
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access not allowed!';
            $this->response['csrfName'] = $this->security->get_csrf_token_name();
            $this->response['csrfHash'] = $this->security->get_csrf_hash();
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function get_order_tracking()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            return $this->Order_model->get_order_tracking_list();
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function update_order_tracking()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->form_validation->set_rules('courier_agency', 'courier_agency', 'trim|required|xss_clean');
            $this->form_validation->set_rules('tracking_id', 'tracking_id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('url', 'url', 'trim|required|xss_clean');
            $this->form_validation->set_rules('order_id', 'order_id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('order_item_id', 'order item id', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $order_id = $this->input->post('order_id', true);
                $order_item_id = $this->input->post('order_item_id', true);
                $courier_agency = $this->input->post('courier_agency', true);
                $tracking_id = $this->input->post('tracking_id', true);
                $url = $this->input->post('url', true);
                $data = array(
                    'order_id' => $order_id,
                    'order_item_id' => $order_item_id,
                    'courier_agency' => $courier_agency,
                    'tracking_id' => $tracking_id,
                    'url' => $url,
                );
                if (is_exist(['order_item_id' => $order_item_id, 'order_id' => $order_id], 'order_tracking', null)) {
                    if (update_details($data, ['order_id' => $order_id, 'order_item_id' => $order_item_id], 'order_tracking') == TRUE) {
                        $this->response['error'] = false;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['message'] = "Tracking details Update Successfuly.";
                    } else {
                        $this->response['error'] = true;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['message'] = "Not Updated. Try again later.";
                    }
                } else {
                    if (insert_details($data, 'order_tracking')) {
                        $this->response['error'] = false;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['message'] = "Tracking details Insert Successfuly.";
                    } else {
                        $this->response['error'] = true;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['message'] = "Not Inserted. Try again later.";
                    }
                }
                print_r(json_encode($this->response));
            }
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    function order_tracking()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->data['main_page'] = TABLES . 'order-tracking';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Order Tracking | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Order Tracking | ' . $settings['app_name'];
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    function updateBulkQty()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {

            $this->form_validation->set_rules('order_id', 'order_id', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $new_qtys = $old_qtys = array();
                $order_id       = $this->input->post('order_id');
                $order_item_ids  = $this->input->post('order_item_ids');

                $order_info     = $this->db->get_where('orders', array('id' => $order_id))->row_array();

                if ($order_info['order_status'] == 'delivered') {
                    $this->response['error'] = true;
                    $this->response['message'] = "You are not allowed to update delivered item.";
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }

                if ($order_info['order_status'] == 'cancelled') {
                    $this->response['error'] = true;
                    $this->response['message'] = "You are not allowed to update cancelled item.";
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }

                if ($order_item_ids) {

                    foreach ($order_item_ids as $order_item_id) {
                        $qty    = $this->input->post('quantity_' . $order_item_id);

                        $order_item_info = $this->db->get_where('order_items', array('order_id' => $order_id, 'id' => $order_item_id))->row_array();

                        if ($qty == $order_item_info['quantity']) {
                            if (($key = array_search($order_item_id, $order_item_ids)) !== false) {
                                unset($order_item_ids[$key]);
                            }
                        } else {
                            $new_qtys[] = $qty;
                            $old_qtys[] = $order_item_info['quantity'];

                            $active_status  = $order_item_info['active_status'];
                            $order_price    = $order_item_info['price'];
                            $order_sub_total = $order_item_info['price'] * $order_item_info['quantity'];

                            if ($qty > $order_item_info->quantity) {
                                $check_current_stock_status = validate_stock($order_item_info['product_variant_id'], ($qty - $order_item_info->quantity));
                                if ($check_current_stock_status['error'] == true) {
                                    $this->response['error'] = true;
                                    $this->response['message'] = $check_current_stock_status['message'];
                                    $this->response['data'] = array();
                                    print_r(json_encode($this->response));
                                    return false;
                                }
                            }

                            if ($active_status == 'delivered') {
                                $this->response['error'] = true;
                                $this->response['message'] = "You are not allowed to update delivered item.";
                                $this->response['data'] = array();
                                print_r(json_encode($this->response));
                                return false;
                            }

                            if ($active_status == 'cancelled') {
                                $this->response['error'] = true;
                                $this->response['message'] = "You are not allowed to update cancelled item.";
                                $this->response['data'] = array();
                                print_r(json_encode($this->response));
                                return false;
                            }

                            $new_sub_total = $qty * $order_item_info['price'];
                            $order_total   = ($order_info['total'] - $order_sub_total) + $new_sub_total;
                            $final_total   = ($order_info['final_total'] - $order_sub_total) + $new_sub_total;
                            $total_payable = ($order_info['total_payable'] - $order_sub_total) + $new_sub_total;


                            //$status = json_decode(stripallslashes($order_item_info['status']));
                            //array_push($status,array('qty_update',date('d-m-Y h:i:sa')));

                            $update_item_data = array('quantity' => $qty, 'sub_total' => $new_sub_total); //,'active_status'=>'qty_update','status'=>json_encode($status)
                            update_details($update_item_data, ['id' => $order_item_id], 'order_items');

                            $update_order_data = array('final_total' => $final_total, 'total_payable' => $total_payable, 'total' => $order_total, 'last_updated' => date('Y-m-d H:i:s')); //,'order_status'=>'qty_update'
                            update_details($update_order_data, ['id' => $order_id], 'orders');
                        }
                    }

                    if ($order_item_ids) {
                        $order_item_stages = array('order_id' => $order_id, 'order_item_id' => 0, 'ids' => implode(',', $order_item_ids), 'new_qtys' => implode(',', $new_qtys), 'old_qtys' => implode(',', $old_qtys), 'status' => 'qty_update',);
                        $this->db->insert('order_item_stages', $order_item_stages);

                        $this->response['error'] = false;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['message'] = "Quantity updated successfully.";

                        print_r(json_encode($this->response));
                    } else {
                        $this->response['error'] = false;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['message'] = "Quantity Confirmed.";

                        print_r(json_encode($this->response));
                    }
                }
            }
        }
    }

    function updateQty()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {

            $this->form_validation->set_rules('qty', 'Quantity', 'trim|required|xss_clean');
            $this->form_validation->set_rules('order_id', 'order_id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('order_item_id', 'order item id', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {

                $qty            = $this->input->post('qty');
                $order_item_id  = $this->input->post('order_item_id');
                $order_id       = $this->input->post('order_id');

                $order_info     = $this->db->get_where('orders', array('id' => $order_id))->row_array();
                $order_item_info = $this->db->get_where('order_items', array('order_id' => $order_id, 'id' => $order_item_id))->row_array();

                $active_status  = $order_item_info['active_status'];
                $order_price    = $order_item_info['price'];
                $order_sub_total = $order_item_info['price'] * $order_item_info['quantity'];

                if ($qty > $order_item_info->quantity) {
                    $check_current_stock_status = validate_stock($order_item_info['product_variant_id'], ($qty - $order_item_info->quantity));
                    if ($check_current_stock_status['error'] == true) {
                        $this->response['error'] = true;
                        $this->response['message'] = $check_current_stock_status['message'];
                        $this->response['data'] = array();
                        print_r(json_encode($this->response));
                        return false;
                    }
                }

                if ($active_status == 'delivered') {
                    $this->response['error'] = true;
                    $this->response['message'] = "You are not allowed to update delivered item.";
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }

                if ($active_status == 'cancelled') {
                    $this->response['error'] = true;
                    $this->response['message'] = "You are not allowed to update cancelled item.";
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }

                $new_sub_total = $qty * $order_item_info['price'];
                $order_total   = ($order_info['total'] - $order_sub_total) + $new_sub_total;
                $final_total   = ($order_info['final_total'] - $order_sub_total) + $new_sub_total;
                $total_payable = ($order_info['total_payable'] - $order_sub_total) + $new_sub_total;


                $status = json_decode(stripallslashes($order_item_info['status']));
                array_push($status, array('qty_update', date('d-m-Y h:i:sa')));

                $update_item_data = array('quantity' => $qty, 'sub_total' => $new_sub_total, 'active_status' => 'qty_update', 'status' => json_encode($status));
                update_details($update_item_data, ['id' => $order_item_id], 'order_items');

                $update_order_data = array('final_total' => $final_total, 'total_payable' => $total_payable, 'total' => $order_total, 'order_status' => 'qty_update', 'last_updated' => date('Y-m-d H:i:s'));
                update_details($update_order_data, ['id' => $order_id], 'orders');

                $order_item_stages = array('order_id' => $order_id, 'order_item_id' => $order_item_id, 'status' => 'qty_update',);
                $this->db->insert('order_item_stages', $order_item_stages);

                $this->response['error'] = false;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = "Quantity updated successfully.";

                print_r(json_encode($this->response));
            }
        }
    }

    function send_delivery_schedule()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->form_validation->set_rules('order_id', 'Order', 'trim|required|xss_clean');
            $this->form_validation->set_rules('schedule_delivery_date', 'Schedule Date', 'trim|required|xss_clean');

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $order_id  = $this->input->post('order_id');
                $order_item_id  = $this->input->post('order_item_id');
                $schedule_delivery_date  = $this->input->post('schedule_delivery_date');

                if ($order_item_id) {
                    $update_item_data = array('schedule_delivery_date' => $schedule_delivery_date);
                    update_details($update_item_data, ['id' => $order_item_id], 'order_items');
                }

                $order_item_stages = array('order_id' => $order_id, 'order_item_id' => $order_item_id, 'status' => 'schedule_delivery',);
                $query = $this->db->get_where('order_item_stages', $verify_data);

                if ($query->num_rows() < 1) {
                    $this->db->insert('order_item_stages', $order_item_stages);
                }
                $update_data = array('order_status' => 'schedule_delivery', 'schedule_delivery_date' => $schedule_delivery_date, 'last_updated' => date('Y-m-d H:i:s'));
                update_details($update_data, ['id' => $order_id], 'orders');


                $this->db->select('a.id, a.status, a.active_status');
                $this->db->from('order_items as a');
                $this->db->where('a.order_id', $order_id);
                $this->db->where_not_in('a.active_status', array('delivered', 'cancelled'));
                $query = $this->db->get();
                $order_items_info = $query->result_array();

                if ($order_items_info) {
                    foreach ($order_items_info as $order_item_info) {
                        $status = array();
                        if ($order_item_info['status'] != null) {
                            $status = json_decode(stripallslashes($order_item_info['status']));
                            array_push($status, array('schedule_delivery', date('d-m-Y h:i:sa')));
                        } else {
                            $status = array(array('schedule_delivery', date("d-m-Y h:i:sa")));
                        }

                        $update_item_data = array('active_status' => 'schedule_delivery', 'status' => json_encode($status));
                        update_details($update_item_data, ['id' => $order_item_info['id']], 'order_items');
                    }
                }

                $this->response['error'] = false;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = "Delivery Date updated successfully.";

                print_r(json_encode($this->response));
            }
        }
    }

    public function send_payment_confirmation()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->form_validation->set_rules('order_id', 'Order Id', 'trim|required|numeric|xss_clean');
            //$this->form_validation->set_rules('order_item_id', 'Order Item', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
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

                if (!file_exists(FCPATH . PAYMENT_CONFIRMATION_IMG_PATH)) {
                    mkdir(FCPATH . PAYMENT_CONFIRMATION_IMG_PATH, 0777);
                }

                $temp_array = array();
                $files = $_FILES;
                $images_new_name_arr = array();
                $images_info_error = "";
                $allowed_media_types = implode('|', allowed_media_types());
                $config = [
                    'upload_path' =>  FCPATH . PAYMENT_CONFIRMATION_IMG_PATH,
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
                                resize_review_images($temp_array, FCPATH . PAYMENT_CONFIRMATION_IMG_PATH);
                                $images_new_name_arr[$i] = PAYMENT_CONFIRMATION_IMG_PATH . $temp_array['file_name'];
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
                                unlink(FCPATH . PAYMENT_CONFIRMATION_IMG_PATH . $images_new_name_arr[$key]);
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

                if ($this->Order_model->add_payment_confirmation($data)) {

                    //$order_item_info = $this->db->get_where('order_items', array('id' => $order_item_id))->row_array();

                    //$status = json_decode($order_item_info['status']);
                    //array_push($status,array('payment_ack',date('d-m-Y h:i:sa')));

                    //$order_item_up = ['active_status' =>'payment_ack','status'=>json_encode($status)];
                    //$order_item_up = escape_array($order_item_up);
                    //$this->db->set($order_item_up)->where('id', $order_item_id)->update('order_items');


                    // $result = fetch_details(['order_id' => $order_id], 'order_bank_transfer');

                    $this->response['error'] = false;
                    $this->response['message'] =  'Payment Confirmation Added Successfully!';
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = (!empty($data)) ? $data : [];
                    print_r(json_encode($this->response));
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] =  'Payment Confirmation Was Not Added';
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = (!empty($this->response['data'])) ? $this->response['data'] : [];
                    print_r(json_encode($this->response));
                }
            }
        }
    }

    public function out_of_stock()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->form_validation->set_rules('order_id', 'Order Id', 'trim|required|numeric|xss_clean');
            //$this->form_validation->set_rules('order_item_id', 'Order Item', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $order_id = $this->input->post('order_id', true);

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

                    $order_item_stages = array('order_id' => $order_id, 'order_item_id' => 0, 'ids' => implode(',', $order_item_ids), 'status' => 'out_of_stock',);
                    $this->db->insert('order_item_stages', $order_item_stages);

                    $this->db->select('a.id');
                    $this->db->from('order_items as a');
                    $this->db->where('a.active_status !=', 'cancelled');
                    //$this->db->where('a.id !=', $order_item_id);
                    $this->db->where('a.order_id', $order_id);
                    $query      = $this->db->get();
                    $results    = $query->result_array();

                    if (count($results) <= 0) {
                        $update_data = array('order_status' => 'cancelled', 'last_updated' => date('Y-m-d H:i:s'));
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
                        $html_text .= '<p>We regret to inform you that we\'ve had to cancel your order #HC-A' . $order_id . ' for the following item(s): This cancellation was necessitated by the temporary unavailability of these items in supplier inventory. We sincerely apologize for any inconvenience this may have caused.</p>';

                        $order_info = array(
                            'order'             => $order_r['order_data'][0],
                            'subject'           => 'Order #HC-A' . $order_id . ' Out of Stock Material',
                            'user_msg'          => $html_text,
                            'note'              => '<p>We appreciate your understanding in this matter and look forward to serving you better in the future.</p>',
                            'show_address_amt'  => false,
                        );
                        send_mail2($user[0]['email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    }

                    if ($seller_email[0]['email'] != '') {
                        $html_text  = '<p>Dear ' . ucfirst($seller_store_name) . ',</p>';
                        $html_text .= '<p>You have canceled your order #HC-A' . $order_id . ' for the following item(s) due to shortage of products in your inventory.</p>';

                        $order_info = array(
                            'order'             => $order_r['order_data'][0],
                            'subject'           => 'Order #HC-A' . $order_id . ' Out of Stock Material',
                            'user_msg'          => $html_text,
                            'note'              => '<p>To avoid any future order cancellations, we kindly request that you maintain an appropriate inventory.</p>',
                            'show_address_amt'  => false,
                        );
                        send_mail2($seller_email[0]['email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    }

                    if (isset($system_settings['support_email']) && !empty($system_settings['support_email'])) {
                        $html_text  = '<p>Dear Admin,</p>';
                        $html_text .= '<p>' . ucfirst($seller_store_name) . ' have canceled the order #HC-A' . $order_id . ' for the following item(s) due to shortage of products in their inventory.</p>';

                        $order_info = array(
                            'order'             => $order_r['order_data'][0],
                            'subject'           => 'Order #HC-A' . $order_id . ' Out of Stock Material',
                            'user_msg'          => $html_text,
                            'show_address_amt'  => false,
                        );
                        send_mail2($system_settings['support_email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    }

                    $this->response['redirect_to']  = ''; // base_url('my-account/bank-details/'.$is_seller);
                    $this->response['error']        = false;
                    $this->response['message']      = 'Order Cancelled';

                    //$this->response['order_item_id']= $order_item_id;
                    echo json_encode($this->response);
                    return false;
                }
            }
        }
    }

    public function send_invoice()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->form_validation->set_rules('order_id', 'Order Id', 'trim|required|numeric|xss_clean');
            //$this->form_validation->set_rules('order_item_id', 'Order Item', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
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

                $status = 'shipped';
                $this->db->update('orders', array('order_status' => $status, 'last_updated' => date('Y-m-d H:i:s')), array('id' => $order_id));

                $order_item_stages = array('order_id' => $order_id, 'status' => $status,);
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

                            array_push($_status, array($status, date('d-m-Y h:i:sa')));
                        } else {
                            $_status =  array(array($status, date("d-m-Y h:i:sa")));
                        }

                        $update_item_data = array('active_status' => $status, 'status' => json_encode($_status));
                        update_details($update_item_data, ['id' => $order_item_info['id']], 'order_items');
                    }
                }

                /*if (empty($order_item)) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Order Item not found!";
                    $this->response['data'] = [];
                    print_r(json_encode($this->response));
                    return false;
                }*/

                if (!file_exists(FCPATH . INVOICE_IMG_PATH)) {
                    mkdir(FCPATH . INVOICE_IMG_PATH, 0777);
                }

                $temp_array = array();
                $files = $_FILES;
                $images_new_name_arr = array();
                $images_info_error = "";
                $allowed_media_types = implode('|', allowed_media_types());
                $config = [
                    'upload_path' =>  FCPATH . INVOICE_IMG_PATH,
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
                                resize_review_images($temp_array, FCPATH . INVOICE_IMG_PATH);
                                $images_new_name_arr[$i] = INVOICE_IMG_PATH . $temp_array['file_name'];
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
                                unlink(FCPATH . INVOICE_IMG_PATH . $images_new_name_arr[$key]);
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


                /************ EWay Bill ***************/
                if (!file_exists(FCPATH . EWAY_BILL_IMG_PATH)) {
                    mkdir(FCPATH . EWAY_BILL_IMG_PATH, 0777);
                }

                $temp_array2 = array();
                $files2 = $_FILES;
                $images_new_name_arr2 = array();
                $images_info_error2 = "";
                $allowed_media_types = implode('|', allowed_media_types());
                $config = [
                    'upload_path' =>  FCPATH . EWAY_BILL_IMG_PATH,
                    'allowed_types' => $allowed_media_types,
                    'max_size' => 8000,
                ];


                if (!empty($_FILES['attachments2']['name'][0]) && isset($_FILES['attachments2']['name'])) {
                    $other_image_cnt2 = count($_FILES['attachments2']['name']);
                    $other_img2 = $this->upload;
                    $other_img2->initialize($config);

                    for ($i = 0; $i < $other_image_cnt2; $i++) {

                        if (!empty($_FILES['attachments2']['name'][$i])) {

                            $_FILES['temp_image2']['name'] = $files['attachments2']['name'][$i];
                            $_FILES['temp_image2']['type'] = $files['attachments2']['type'][$i];
                            $_FILES['temp_image2']['tmp_name'] = $files['attachments2']['tmp_name'][$i];
                            $_FILES['temp_image2']['error'] = $files['attachments2']['error'][$i];
                            $_FILES['temp_image2']['size'] = $files['attachments2']['size'][$i];
                            if (!$other_img2->do_upload('temp_image2')) {
                                $images_info_error2 = 'attachments :' . $images_info_error2 . ' ' . $other_img2->display_errors();
                            } else {
                                $temp_array2 = $other_img2->data();
                                resize_review_images($temp_array2, FCPATH . EWAY_BILL_IMG_PATH);
                                $images_new_name_arr2[$i] = EWAY_BILL_IMG_PATH . $temp_array2['file_name'];
                            }
                        } else {
                            $_FILES['temp_image2']['name'] = $files['attachments2']['name'][$i];
                            $_FILES['temp_image2']['type'] = $files['attachments2']['type'][$i];
                            $_FILES['temp_image2']['tmp_name'] = $files['attachments2']['tmp_name'][$i];
                            $_FILES['temp_image2']['error'] = $files['attachments2']['error'][$i];
                            $_FILES['temp_image2']['size'] = $files['attachments2']['size'][$i];
                            if (!$other_img2->do_upload('temp_image2')) {
                                $images_info_error2 = $other_img2->display_errors();
                            }
                        }
                    }
                    //Deleting Uploaded attachments if any overall error occured
                    if ($images_info_error2 != NULL || !$this->form_validation->run()) {
                        if (isset($images_new_name_arr2) && !empty($images_new_name_arr2 || !$this->form_validation->run())) {
                            foreach ($images_new_name_arr2 as $key => $val) {
                                unlink(FCPATH . EWAY_BILL_IMG_PATH . $images_new_name_arr2[$key]);
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

                /*********** End EWay Bill ***********/


                $data = array(
                    'order_id'      => $order_id,
                    'order_item_id' => $order_item_id,
                    'attachments'   => $images_new_name_arr,
                    'attachments2'  => $images_new_name_arr2,
                );

                if ($this->Order_model->add_invoice($data)) {

                    //$order_item_info = $this->db->get_where('order_items', array('id' => $order_item_id))->row_array();

                    //$status = json_decode($order_item_info['status']);
                    //array_push($status,array('payment_ack',date('d-m-Y h:i:sa')));

                    //$order_item_up = ['active_status' =>'payment_ack','status'=>json_encode($status)];
                    //$order_item_up = escape_array($order_item_up);
                    //$this->db->set($order_item_up)->where('id', $order_item_id)->update('order_items');


                    // $result = fetch_details(['order_id' => $order_id], 'order_bank_transfer');

                    $order      = fetch_details(['id' => $order_id], 'orders');
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
                        $html_text  = '<p>Hello ' . ucfirst($retailer_store_name) . ',</p>';
                        $html_text .= '<p style="text-indent: 50px;">E-way bill and invoices received from seller. Your order is in transit, please make sure timely unloading of material.</p>';
                        $html_text .= '<p style="text-indent: 50px;">Once order get delivered, As a next step, we kindly request that you close the order by acknowledging its completion. This helps us maintain accurate records and ensures that our systems are up to date. By closing the order, you confirm that you have received the materials and are satisfied with your purchase.</p>';
                        $html_text .= '<p style="text-indent: 50px;margin-bottom: 0;">If you have any concerns or issues regarding the order, please raise an issue on your portal. We are here to assist you and resolve any problems you may encounter.</p>';

                        $order_info = array(
                            'subject'    => 'Order #HC-A' . $order_id . ' - Updates from Seller',
                            'user_msg' => $html_text,
                        );

                        send_mail2($user[0]['email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    }

                    if ($seller_email[0]['email'] != '') {
                        $html_text  = '<p>Hello ' . ucfirst($seller_store_name) . ',</p>';
                        $html_text .= '<p style="text-indent: 50px;margin-bottom: 0;"><b>You have sent E-way bill and invoices to Happycrop and Retailer. Please make sure timely delivery of material.</b></p>';

                        $order_info = array(
                            'subject'           => 'Order #HC-A' . $order_id . ' Updates from Seller',
                            'user_msg'          => $html_text,
                            'note'              => '<p>Please note - In case of incomplete order, damaged products or incorrect order, manufacturer bound to resolve the issue / refund the amount against particular concern.</p>',
                        );
                        send_mail2($seller_email[0]['email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    }

                    //admin
                    if (isset($system_settings['support_email']) && !empty($system_settings['support_email'])) {
                        $html_text  = '<p><b>Hello Admin,</b></p>';
                        $html_text .= '<p><b>The Seller has sent E-way bill and invoices to Happycrop and Retailer. The (' . ucfirst($retailer_store_name) . ') order is in transit, please make sure timely unloading of material.</b></p>';
                        $html_text .= '<p style="margin-bottom: 0;">Once order get delivered, Happycrop must observe that retailer closing the order by acknowledging its completion. This helps us maintain accurate records and ensures that our systems are up to date. By closing the order, Happycrop should  confirm through call that retailer have received the materials correctly and are satisfied with the purchase.</p>';

                        $order_info = array(
                            'subject'    => 'Order #HC-A' . $order_id . ' - Updates from Seller',
                            'user_msg' => $html_text,
                        );

                        send_mail2($system_settings['support_email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    }

                    $this->response['error'] = false;
                    $this->response['message'] =  'Invoice Added Successfully!';
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = (!empty($data)) ? $data : [];
                    print_r(json_encode($this->response));
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] =  'Invoice Was Not Added';
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = (!empty($this->response['data'])) ? $this->response['data'] : [];
                    print_r(json_encode($this->response));
                }
            }
        }
    }

    public function requestPayment()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->form_validation->set_rules('order_id', 'Order Id', 'trim|required|numeric|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $order_id   = $this->input->post('order_id', true);
                $order      = fetch_details(['id' => $order_id], 'orders');

                if (empty($order)) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Order not found!";
                    $this->response['data'] = [];
                    print_r(json_encode($this->response));
                    return false;
                }

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
                            array_push($status, array('payment_demand', date('d-m-Y h:i:sa')));
                        } else {
                            $status =  array(array('payment_demand', date("d-m-Y h:i:sa")));
                        }

                        $update_item_data = array('active_status' => 'payment_demand', 'status' => json_encode($status));
                        update_details($update_item_data, ['id' => $order_item_info['id']], 'order_items');
                    }
                }

                $this->db->update('orders', array('order_status' => 'payment_demand', 'last_updated' => date('Y-m-d H:i:s')), array('id' => $order_id));

                $order_item_stages = array('order_id' => $order_id, 'status' => 'payment_demand',);
                $this->db->insert('order_item_stages', $order_item_stages);

                /*$system_settings = get_settings('system_settings', true);
                $user_id    = $order[0]['user_id'];
                $user = fetch_details(['id' => $order[0]['user_id']], 'users');
                
                if($user)
                {
                    $overall_order_data = array(
                        'order'             => array(),
                        'subject'           => 'Payment request received',
                        'system_settings'   => $system_settings,
                        'user_msg'          => 'Hello, Dear ' . ucfirst($user[0]['username']) . ', Manufacturer reviewed your order #HC-A' . $order[0]['id'].' and requested payment.',
                    );
                    send_mail2($user[0]['email'], 'Payment request received', $this->load->view('admin/pages/view/order-email-template.php', $overall_order_data, TRUE));
                }*/

                $system_settings = get_settings('system_settings', true);
                $user_id    = $order[0]['user_id'];
                $user = fetch_details(['id' => $order[0]['user_id']], 'users');

                $retailer_store_name = fetch_details(['user_id' => $user_id], 'retailer_data', 'company_name');
                $retailer_store_name = $retailer_store_name[0]['company_name'];

                $seller_email = fetch_details(['id' => $order_items_info[0]['seller_id']], 'users', 'email');
                $seller_store_name = fetch_details(['user_id' => $order_items_info[0]['seller_id']], 'seller_data', 'company_name');
                $seller_store_name = $seller_store_name[0]['company_name'];


                ob_start();
?>
                <p>Hello <?php echo ucfirst($retailer_store_name); ?>,</p>

                <p><b>Manufacturer reviewed your order</b><br />
                    <b>For order details please visit </b> <a href="https://www.happycrop.in">www.Happycrop.in</a><br>
                    <b>Tentative delivery date <?php echo date('d/m/Y', strtotime($order[0]['schedule_delivery_date']));  ?></b><br>
                    <b>Payment requested by manufacturer is <?php echo $settings['currency'] ?> <?php echo $order[0]['final_total'];  ?>/-</b>
                </p>
                <p>We kindly request that you submit the payment of rs. <?php echo $order[0]['final_total'];  ?>/- to Happycrop account. The Happycrop account details are mentioned below. We value your business and look forward to your prompt payment. Your cooperation in this matter is greatly appreciated.</p>
                <p style="margin: 0;"><b>BANK ACCOUNT DETAILS</b></p>
                <p style="margin: 0;"><b>HAPPYCROP AGRI AND BIOTECH LLP</b></p>
                <p style="margin: 0;"><b>BHUDARGAD BRANCH</b></p>
                <p style="margin: 0;"><b>A/C : 40616444587</b></p>
                <p style="margin: 0;"><b>IFSC : SBIN0000570</b></p>

                <p><b>Note - This order will be auto cancelled, if the action not taken within 5 days from the date of payment request from seller.</b></p>

                <p style="margin: 0px;">Please note that the estimated delivery date provided above is subject to change, and we will notify you in case of any updates.</p>

                <?php
                $html_text = ob_get_contents();
                ob_end_clean();

                if ($user) {
                    $overall_order_data = array(
                        'order'             => array(),
                        'subject'           => 'Order #HC-A' . $order_id . ' - Updates from Seller',
                        'system_settings'   => $system_settings,
                        'user_msg'          => $html_text,
                    );
                    send_mail2($user[0]['email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $overall_order_data, TRUE));
                }

                if ($seller_email != '') {
                    ob_start();
                ?>
                    <p>Hello <?php echo ucfirst($seller_store_name); ?>,</p>

                    <p style="margin-left : 0px;margin-top : 0px;margin-bottom : 0px"><b>You have reviewed the order. For order details please visit</b> <a href="https://www.happycrop.in"> www.Happycrop.in</a></p>
                    <p style="margin-left : 40px;margin-top : 0px;margin-bottom : 0px"><b>You have confirmed quantity </b></p>
                    <p style="margin-left : 40px;margin-top : 0px;margin-bottom : 0px"><b>Scheduled delivery date <?php echo date('d/m/Y', strtotime($order[0]['schedule_delivery_date']));  ?></b></p>
                    <p style="margin-left : 40px;margin-top : 0px;margin-bottom : 0px"><b>Payment request sent by you is <?php echo $settings['currency'] ?> <?php echo $order[0]['final_total'];  ?>/-</b></p>

                    <p><b>Note - This order will be auto cancelled, if the action not taken by retailer within 5 days from the date of payment request from seller.</b></p>
                <?php
                    $html_text = ob_get_contents();
                    ob_end_clean();

                    $seller_request = array(
                        'order'             => array(),
                        'subject'           => 'Order #HC-A' . $order_id . ' - Updates from Seller',
                        'system_settings'   => $system_settings,
                        'user_msg'          => $html_text,
                    );
                    send_mail2($seller_email[0]["email"], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $seller_request, TRUE));
                }

                if (isset($system_settings['support_email']) && !empty($system_settings['support_email'])) {
                    //admin
                    ob_start();
                ?>
                    <p>Hello Admin,</p>

                    <p style="margin-left : 0px;margin-top : 0px;margin-bottom : 0px"><b>Manufacturer / Seller reviewed the order. </b></p>
                    <p style="margin-left : 50px;margin-top : 0px;margin-bottom : 0px"><b>Confirmed Quantity </b></p>
                    <p style="margin-left : 50px;margin-top : 0px;margin-bottom : 0px"><b>Tentative delivery date <?php echo date('d/m/Y', strtotime($order[0]['schedule_delivery_date']));  ?></b></p>
                    <p style="margin-left : 50px;margin-top : 0px;margin-bottom : 0px"><b>Payment requested by manufacturer is "₹<?php echo $order[0]['final_total'];  ?>"/-</b></p>
                    <p style="margin-left : 0px;margin-top : 0px;margin-bottom : 0px"><b>For order details please visit <a href="https://www.happycrop.in">www.Happycrop.in</a> </b></p>

                    <p><?php echo ucfirst($seller_store_name); ?> requested <?php echo ucfirst($retailer_store_name); ?> to submit the payment of <?php echo $settings['currency'] ?> <?php echo $order[0]['final_total'];  ?>/- to Happycrop account. The Happycrop account details are mentioned below. </p>

                    <p style="margin-left : 0px;margin-top : 0px;margin-bottom : 0px"><b>BANK ACCOUNT DETAILS </b></p>
                    <p style="margin-left : 0px;margin-top : 0px;margin-bottom : 0px"><b>HAPPYCROP AGRI AND BIOTECH LLP </b></p>
                    <p style="margin-left : 0px;margin-top : 0px;margin-bottom : 0px"><b>BHUDARGAD BRANCH</b></p>
                    <p style="margin-left : 0px;margin-top : 0px;margin-bottom : 0px"><b>A/C - 40616444587</b></p>
                    <p style="margin-left : 0px;margin-top : 0px;margin-bottom : 0px"><b>IFSC - SBIN0000570 </b></p>

                    <p><b>Note - This order will be auto cancelled, if the action not taken within 5 days from the date of payment request from seller.</b></p>
                    <p>Please note that the estimated delivery date provided above is subject to change, and we will notify you in case of any updates.</p>
<?php
                    $html_text = ob_get_contents();
                    ob_end_clean();


                    $order_info = array(
                        'subject'    => 'Order #HC-A' . $order_id . ' - Updates from Seller',
                        'user_msg' => $html_text,
                    );

                    send_mail2($system_settings['support_email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                }


                $this->response['redirect_to'] = ''; // base_url('my-account/bank-details/'.$is_seller);
                $this->response['error'] = false;
                $this->response['message'] = 'Thanks for the update';
                $this->response['html']    = 'payment_demand';
                $this->response['order_id'] = $order_id;
                echo json_encode($this->response);
                return false;
            }
        }
    }

    public function send_order_status()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->form_validation->set_rules('order_id', 'Order Id', 'trim|required|numeric|xss_clean');
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
        }
    }

    public function send_mfg_payment_confirmation_form()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->form_validation->set_rules('order_id', 'Order Id', 'trim|required|numeric|xss_clean');
            //$this->form_validation->set_rules('order_item_id', 'Order Item', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                $order_id = $this->input->post('order_id', true);
                //$order_item_id = $this->input->post('order_item_id', true);

                $order = fetch_details(['id' => $order_id], 'orders', 'id');
                //$order_item = fetch_details(['id' => $order_item_id], 'order_items', 'id');

                if (empty($order)) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Order not found!";
                    $this->response['data'] = [];
                    print_r(json_encode($this->response));
                    return false;
                }


                if (!file_exists(FCPATH . PAYMENT_MFG_CONFIRMATION_IMG_PATH)) {
                    mkdir(FCPATH . PAYMENT_MFG_CONFIRMATION_IMG_PATH, 0777);
                }

                $temp_array = array();
                $files = $_FILES;
                $images_new_name_arr = array();
                $images_info_error = "";
                $allowed_media_types = implode('|', allowed_media_types());
                $config = [
                    'upload_path' =>  FCPATH . PAYMENT_MFG_CONFIRMATION_IMG_PATH,
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
                                resize_review_images($temp_array, FCPATH . PAYMENT_MFG_CONFIRMATION_IMG_PATH);
                                $images_new_name_arr[$i] = PAYMENT_MFG_CONFIRMATION_IMG_PATH . $temp_array['file_name'];
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
                                unlink(FCPATH . PAYMENT_MFG_CONFIRMATION_IMG_PATH . $images_new_name_arr[$key]);
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

                if ($this->Order_model->add_mfg_payment_confirmation($data)) {

                    $order      = fetch_details(['id' => $order_id], 'orders');
                    $this->db->select('a.id, a.seller_id, a.status, a.active_status');
                    $this->db->from('order_items as a');
                    $this->db->where('a.order_id', $order_id);

                    $query = $this->db->get();
                    $order_items_info = $query->result_array();

                    $system_settings = get_settings('system_settings', true);

                    $seller_email = fetch_details(['id' => $order_items_info[0]['seller_id']], 'users', 'email');
                    $seller_store_name = fetch_details(['user_id' => $order_items_info[0]['seller_id']], 'seller_data', 'company_name');
                    $seller_store_name = $seller_store_name[0]['company_name'];

                    //seller
                    if ($seller_email[0]["email"] != '') {
                        $html_text  = '<p><b>Hello ' . ucfirst($seller_store_name) . '</b>,</p>';
                        $html_text .= '<p style="text-indent:50px;margin-bottom: 0;"><b>You have confirmed the payment made by Happycrop and the order is now closed.</b></p>';

                        $order_info = array(
                            'subject'    => 'Order #HC-A' . $order_id . ' - Updates from Seller',
                            'user_msg' => $html_text,
                        );

                        send_mail2($seller_email[0]["email"], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    }

                    //admin
                    if (isset($system_settings['support_email']) && !empty($system_settings['support_email'])) {
                        $html_text  = '<p><b>Hello Admin,</b></p>';
                        $html_text .= '<p style="text-indent:50px;margin-bottom: 0;"><b>The Manufacturer have confirmed the payment made by Happycrop and Sent the payment receipt to Happycrop. The order is now closed.</p>';

                        $order_info = array(
                            'subject'    => 'Order #HC-A' . $order_id . ' - Updates from Seller',
                            'user_msg' => $html_text,
                        );

                        send_mail2($system_settings['support_email'], 'Happycrop Order Updates - Order #HC-A' . $order_id, $this->load->view('admin/pages/view/order-email-template.php', $order_info, TRUE));
                    }

                    $this->response['error'] = false;
                    $this->response['message'] =  'Payment Confirmation Added Successfully!';
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = (!empty($data)) ? $data : [];
                    print_r(json_encode($this->response));
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] =  'Payment Confirmation Was Not Added';
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = (!empty($this->response['data'])) ? $this->response['data'] : [];
                    print_r(json_encode($this->response));
                }
            }
        }
    }

    public function accounts()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->data['page_title'] = 'Accounts';
            $settings = get_settings('system_settings', true);
            $this->data['main_page'] = TABLES . 'manage-accounts';

            $this->data['title'] = 'Accounts | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Accounts  | ' . $settings['app_name'];
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function view_seller_account_orders()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $seller_id = $this->ion_auth->get_user_id();
          
            // return $this->Order_model->get_seller_account_orders_list($seller_id);
            return $this->Account_model->get_seller_account_orders_list($seller_id);
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    public function statements()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->data['page_title'] = 'Statements';
            $settings = get_settings('system_settings', true);
            $this->data['main_page'] = TABLES . 'manage-statements';

            $this->data['title'] = 'Statements | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Statements  | ' . $settings['app_name'];

            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }


    public function view_seller_statement_orders()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $seller_id = $this->ion_auth->get_user_id();
            return $this->Order_model->get_seller_statement_orders_list($seller_id);
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function statement_detail()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {

            if (isset($_GET['retailer_id']) && round($_GET['retailer_id'])) {
                $this->data['page_title'] = 'Statements';
                $settings = get_settings('system_settings', true);
                $this->data['main_page'] = TABLES . 'manage-statement-detail';

                $this->data['retailer_id'] = $_GET['retailer_id'];

                $this->data['retailer_info'] = $this->Order_model->get_retailer_data_info($this->data['retailer_id']);

                $this->data['title'] = 'Statements | ' . $settings['app_name'];
                $this->data['meta_description'] = 'Statements  | ' . $settings['app_name'];

                $this->load->view('seller/template', $this->data);
            } else {
                redirect('seller/orders/statements', 'refresh');
            }
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function view_seller_retailer_statements($retailer_id)
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $seller_id = $this->ion_auth->get_user_id();
            return $this->Order_model->get_seller_retailer_statements_list($seller_id, $retailer_id);
        } else {
            redirect('seller/login', 'refresh');
        }
    }

    public function send_statement()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->form_validation->set_rules('from_date', 'From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('to_date', 'To Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('retailer_id', 'Retailer', 'trim|required|numeric|xss_clean');

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
            } else {
                if (!file_exists(FCPATH . STATEMENT_PATH)) {
                    mkdir(FCPATH . STATEMENT_PATH, 0777);
                }

                $temp_array = array();
                $files = $_FILES;
                $images_new_name_arr = array();
                $images_info_error = "";
                $allowed_media_types = implode('|', allowed_media_types());
                $config = [
                    'upload_path'   =>  FCPATH . STATEMENT_PATH,
                    'allowed_types' => $allowed_media_types,
                    'max_size'      => 8000,
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
                                resize_review_images($temp_array, FCPATH . STATEMENT_PATH);
                                $images_new_name_arr[$i] = STATEMENT_PATH . $temp_array['file_name'];
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
                                unlink(FCPATH . STATEMENT_PATH . $images_new_name_arr[$key]);
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

                $seller_id   = $this->ion_auth->get_user_id();
                $from_date   = $this->input->post('from_date');
                $to_date     = $this->input->post('to_date');
                $retailer_id = $this->input->post('retailer_id');

                $data = array(
                    'from_date'     => $from_date,
                    'to_date'       => $to_date,
                    'retailer_id'   => $retailer_id,
                    'seller_id'     => $seller_id,
                    'attachments'   => $images_new_name_arr,
                );

                if ($this->Order_model->add_statement($data)) {

                    $this->response['error'] = false;
                    $this->response['message'] =  'Statement Added Successfully!';
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = (!empty($data)) ? $data : [];
                    print_r(json_encode($this->response));
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] =  'Statement Was Not Added';
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = (!empty($this->response['data'])) ? $this->response['data'] : [];
                    print_r(json_encode($this->response));
                }
            }
        }
    }

    public function update_mfg_exp_batch()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->form_validation->set_rules('mfg_date', 'mfg_date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('exp_date', 'exp_date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('batch_no', 'batch_no', 'trim|required|xss_clean');
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
                $mfg_date       = $this->input->post('mfg_date', true);
                $exp_date       = $this->input->post('exp_date', true);
                $batch_no       = $this->input->post('batch_no', true);
                $data = array(
                    'order_id'      => $order_id,
                    'id'            => $order_item_id,
                    'mfg_date'      => $mfg_date,
                    'exp_date'      => $exp_date,
                    'batch_no'      => $batch_no,
                );
                if (is_exist(['id' => $order_item_id, 'order_id' => $order_id], 'order_items', null)) {
                    if (update_details($data, ['order_id' => $order_id, 'id' => $order_item_id], 'order_items') == TRUE) {
                        $this->response['error'] = false;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['message'] = "Product details Update Successfuly.";
                    } else {
                        $this->response['error'] = true;
                        $this->response['csrfName'] = $this->security->get_csrf_token_name();
                        $this->response['csrfHash'] = $this->security->get_csrf_hash();
                        $this->response['message'] = "Not Updated. Try again later.";
                    }
                }
                print_r(json_encode($this->response));
            }
        } else {
            redirect('seller/login', 'refresh');
        }
    }
    public function items()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->data['page_title'] = 'Items';
            $settings = get_settings('system_settings', true);
            $this->data['main_page'] = TABLES . 'manage-items';
            $this->data['title'] = 'Items | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Accounts  | ' . $settings['app_name'];
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }
    public function view_seller_account_items()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $seller_id = $this->ion_auth->get_user_id();
          
            return $this->Account_model->view_seller_account_items($seller_id);
        } else {
            redirect('admin/login', 'refresh');
        }
    }
    public function expenses()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_seller() && ($this->ion_auth->seller_status() == 1 || $this->ion_auth->seller_status() == 0)) {
            $this->data['page_title'] = 'Expenses';
            $settings = get_settings('system_settings', true);
            $this->data['main_page'] = TABLES . 'manage-expenses';
            $this->data['title'] = 'Expenses | ' . $settings['app_name'];
            $this->data['meta_description'] = 'Expenses  | ' . $settings['app_name'];
            $this->load->view('seller/template', $this->data);
        } else {
            redirect('seller/login', 'refresh');
        }
    }
}
