<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');

class Order_model extends CI_Model
{
    public function get_retailer_statement_details(
        $retailer_id = NULL,
        $seller_id = NULL,
        $offset = 0,
        $limit = 10,
        $sort = " a.id ",
        $order = 'DESC'
    ) {

        $search_field = '';

        if (isset($_GET['search_field'])) {
            $search_field = $_GET['search_field'];
        }

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $filters = [
                'rd.company_name' => $search,
            ];
        }

        if ($search_field != '') {
            $filters = [
                'rd.company_name' => trim($search_field),
            ];
        }

        $count_res = $this->db->select('COUNT(a.id) as total', false)
            ->join(' `users` u', 'u.id= a.retailer_id', 'left')
            ->join('users us ', ' us.id = a.seller_id', 'left')
            ->join('retailer_data rd ', ' rd.user_id = a.retailer_id', 'left')
            ->join('seller_data sd ', ' sd.user_id = a.seller_id', 'left');

        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }

        if (isset($seller_id)) {
            $count_res->where("a.seller_id", $seller_id);
        }

        if (isset($retailer_id)) {
            $count_res->where("a.retailer_id", $retailer_id);
        }

        $_count = $count_res->get('`retailer_statements` a')->row_array();
        //echo $count_res->last_query();die; 

        //$total = count($product_count);
        $total = $_count['total'];

        $search_res = $this->db->select('a.*, u.username, rd.company_name as retailer_name')
            ->join(' `users` u', 'u.id= a.retailer_id', 'left')
            ->join('users us ', ' us.id = a.seller_id', 'left')
            ->join('retailer_data rd ', ' rd.user_id = a.retailer_id', 'left')
            ->join('seller_data sd ', ' sd.user_id = a.seller_id', 'left');

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }

        if (isset($seller_id)) {
            $search_res->where("a.seller_id", $seller_id);
        }

        if (isset($retailer_id)) {
            $search_res->where("a.retailer_id", $retailer_id);
        }

        $user_details = $search_res->order_by($sort, "DESC")->limit($limit, $offset)->get('`retailer_statements` a')->result_array();
        //echo $search_res->last_query();die;

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($user_details as $row) {

            $temp = '';
            $tempRow['id']          = $row['id'];
            $tempRow['retailer_id'] = $row['retailer_id'];
            $tempRow['seller_id']   = $row['seller_id'];
            $tempRow['name']        = $row['retailer_name'];
            $tempRow['from_date']   = date('d/m/Y', strtotime($row['from_date']));
            $tempRow['to_date']     = date('d/m/Y', strtotime($row['to_date']));
            $tempRow['created_date'] = date('d/m/Y g:i A', strtotime($row['created_date']));

            $operate = '';
            if (file_exists($row['attachments']) && $row['attachments'] != '') {
                $operate .= '<a href="' . base_url($row['attachments']) . '" class="" title="View" target="_blank">View</a>';
            }

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_order_statement_list(
        $retailer_id = NULL,
        $offset = 0,
        $limit = 10,
        $sort = " o.id ",
        $order = 'DESC'
    ) {
        $search_field = '';

        if (isset($_GET['search_field'])) {
            $search_field = $_GET['search_field'];
        }

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['seller_id'])) {
            $seller_id = $_GET['seller_id'];
        }

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $filters = [
                'sd.company_name' => $search,
            ];
        }

        if ($search_field != '') {
            $filters = [
                'sd.company_name' => trim($search_field),
            ];
        }

        $count_res = $this->db->select(' DISTINCT us.id as seller_id, us.username, us.email,us.mobile,s.name as state_name,sd.company_name as mfg_name', false)
        ->join(' `users` u', 'u.id= o.user_id', 'left')
        ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
        ->join('users us ', ' us.id = oi.seller_id', 'left')
        ->join('retailer_data rd ', ' rd.user_id = o.user_id', 'left')
        ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
        ->join('states s ', ' sd.state_id = s.id', 'left');

        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }

        if (isset($retailer_id)) {
            $count_res->where("oi.user_id", $retailer_id);
        }

        $count_res->where('o.is_service_category', 0);

        $count_res->group_by('o.id');

        $product_count = $count_res->get('`orders` o')->result_array();

        $total = count($product_count);

        $search_res = $this->db->select(' DISTINCT us.id as seller_id, us.username, us.email,us.mobile,s.name as state_name,sd.company_name as mfg_name', false)
        ->join(' `users` u', 'u.id= o.user_id', 'left')
        ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
        ->join('users us ', ' us.id = oi.seller_id', 'left')
        ->join('retailer_data rd ', ' rd.user_id = o.user_id', 'left')
        ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
        ->join('states s ', ' sd.state_id = s.id', 'left');

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }

        if (isset($retailer_id)) {
            $count_res->where("oi.user_id", $retailer_id);
        }

        $search_res->where('o.is_service_category', 0);

        $user_details = $search_res->group_by('o.id')->order_by($sort, "DESC")->limit($limit, $offset)->get('`orders` o')->result_array();
        //echo $search_res->last_query();die;

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($user_details as $row) {

            $items1 = '';
            $temp = '';

            $tempRow['seller_id'] = $row['seller_id'];
            $tempRow['seller']    = $row['mfg_name'];
            $tempRow['email']    = $row['email'];
            $tempRow['mobile']    = $row['mobile'];
            $tempRow['state_name']    = $row['state_name'];

            $operate = '<a href="' . base_url('my_account/statement_detail') . '?seller_id=' . $row['seller_id'] . '" class="" title="View">View</a>';

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_seller_data_info($seller_id)
    {
        $this->db->select('a.company_name, a.plot_no, a.street_locality, a.landmark, a.pin, a.have_gst_no, a.gst_no, a.pan_number, a.have_fertilizer_license, a.fertilizer_license_no, a.have_pesticide_license_no, a.pesticide_license_no, a.have_seeds_license_no, a.seeds_license_no');
        $this->db->from('seller_data as a');
        $this->db->where('a.user_id', $seller_id);
        $query = $this->db->get();
        //echo $this->db->last_query();die;

        return $query->row_array();
    }

    public function get_retailer_data_info($retailer_id)
    {
        $this->db->select('a.company_name, a.shop_name, a.plot_no, a.street_locality, a.landmark, a.pin, a.have_gst_no, a.gst_no, a.pan_number, a.have_fertilizer_license, a.fertilizer_license_no, a.have_pesticide_license_no, a.pesticide_license_no, a.have_seeds_license_no, a.seeds_license_no');
        $this->db->from('retailer_data as a');
        $this->db->where('a.user_id', $retailer_id);
        $query = $this->db->get();

        return $query->row_array();
    }
    public function get_seller_retailer_statements_list(
        $seller_id = NULL,
        $retailer_id = NULL,
        $offset = 0,
        $limit = 10,
        $sort = " a.id ",
        $order = 'DESC'
    ) {
        $search_field = '';

        if (isset($_GET['search_field'])) {
            $search_field = $_GET['search_field'];
        }

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $filters = [
                'rd.company_name' => $search,
            ];
        }

        if ($search_field != '') {
            $filters = [
                'rd.company_name' => trim($search_field),
            ];
        }

        $count_res = $this->db->select('COUNT(a.id) as total', false)
            ->join(' `users` u', 'u.id= a.retailer_id', 'left')
            ->join('users us ', ' us.id = a.seller_id', 'left')
            ->join('retailer_data rd ', ' rd.user_id = a.retailer_id', 'left')
            ->join('seller_data sd ', ' sd.user_id = a.seller_id', 'left');

        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }

        if (isset($seller_id)) {
            $count_res->where("a.seller_id", $seller_id);
        }

        if (isset($retailer_id)) {
            $count_res->where("a.retailer_id", $retailer_id);
        }

        $_count = $count_res->get('`retailer_statements` a')->row_array();
        //echo $count_res->last_query();die; 

        //$total = count($product_count);
        $total = $_count['total'];

        $search_res = $this->db->select('a.*, u.username, rd.company_name as retailer_name')
            ->join(' `users` u', 'u.id= a.retailer_id', 'left')
            ->join('users us ', ' us.id = a.seller_id', 'left')
            ->join('retailer_data rd ', ' rd.user_id = a.retailer_id', 'left')
            ->join('seller_data sd ', ' sd.user_id = a.seller_id', 'left');

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }

        if (isset($seller_id)) {
            $search_res->where("a.seller_id", $seller_id);
        }

        if (isset($retailer_id)) {
            $search_res->where("a.retailer_id", $retailer_id);
        }

        $user_details = $search_res->order_by($sort, "DESC")->limit($limit, $offset)->get('`retailer_statements` a')->result_array();
        //echo $search_res->last_query();die;

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($user_details as $row) {

            $temp = '';
            $tempRow['id']          = $row['id'];
            $tempRow['retailer_id'] = $row['retailer_id'];
            $tempRow['seller_id']   = $row['seller_id'];
            $tempRow['name']        = $row['retailer_name'];
            $tempRow['from_date']   = date('d/m/Y', strtotime($row['from_date']));
            $tempRow['to_date']     = date('d/m/Y', strtotime($row['to_date']));
            $tempRow['created_date'] = date('d/m/Y g:i A', strtotime($row['created_date']));

            $operate = '';
            if (file_exists($row['attachments']) && $row['attachments'] != '') {
                $operate .= '<a href="' . base_url($row['attachments']) . '" class="btn btn-primary btn-xs mr-1 mb-1" title="View" target="_blank">View</a>';
            }

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_seller_statement_orders_list(
        $seller_id = NULL,
        $offset = 0,
        $limit = 10,
        $sort = " o.id ",
        $order = 'DESC'
    ) {

        $search_field = '';

        if (isset($_GET['search_field'])) {
            $search_field = $_GET['search_field'];
        }

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['seller_id'])) {
            $seller_id = $_GET['seller_id'];
        }

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $filters = [
                'rd.company_name' => $search,
            ];
        }

        if ($search_field != '') {
            $filters = [
                'rd.company_name' => trim($search_field),
            ];
        }

        $count_res = $this->db->select('DISTINCT o.user_id', false)
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('retailer_data rd ', ' rd.user_id = o.user_id', 'left')
            ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
            ->join('states s ', ' rd.state_id = s.id', 'left');



        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }

        if (isset($seller_id)) {
            $count_res->where("oi.seller_id", $seller_id);
        }

        if (isset($_GET['user_id']) && $_GET['user_id'] != null) {
            $count_res->where("o.user_id", $_GET['user_id']);
        }

        $count_res->where('o.is_service_category', 0);

        $count_res->group_by('o.id');

        $product_count = $count_res->get('`orders` o')->result_array();

        $total = count($product_count);

        $search_res = $this->db->select(' DISTINCT o.user_id , u.username,u.email,u.mobile, rd.company_name as retailer_name,s.name as state_name', false)
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('retailer_data rd ', ' rd.user_id = o.user_id', 'left')
            ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
            ->join('states s ', ' rd.state_id = s.id', 'left');



        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }

        if (isset($seller_id)) {
            $search_res->where("oi.seller_id", $seller_id);
        }

        if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $search_res->where("o.user_id", $_GET['user_id']);
        }

        $search_res->where('o.is_service_category', 0);

        $user_details = $search_res->group_by('o.id')->order_by($sort, "DESC")->limit($limit, $offset)->get('`orders` o')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        
        
        foreach ($user_details as $row) {

            $items1 = '';
            $temp = '';
            $company_name = implode(",", array_values(array_unique(array_column($items, "company_name"))));

            $tempRow['user_id'] = $row['user_id'];
            $tempRow['name'] = $row['retailer_name'];
            $tempRow['email'] = $row['email'];
            $tempRow['mobile'] = $row['mobile'];
            $tempRow['state_name'] = $row['state_name'];

            $operate = '<a href="' . base_url('seller/orders/statement_detail') . '?retailer_id=' . $row['user_id'] . '" class="btn btn-primary btn-xs mr-1 mb-1" title="View">View</a>';

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_account_orders_list(
        $delivery_boy_id = NULL,
        $offset = 0,
        $limit = 10,
        $sort = " o.id ",
        $order = 'ASC'
    ) {
        $this->db->select('order_id');
        $this->db->from('order_item_stages');
        $this->db->where('status', 'issue_resolved');
        $q              = $this->db->get();
        $issue_orders   = $q->result_array();

        $issue_order_ids = array();
        if ($issue_orders) {
            $issue_order_ids = array_column($issue_orders, 'order_id');
        }

        $search_field = '';

        if (isset($_GET['search_field'])) {
            $search_field = $_GET['search_field'];
        }

        $condition = '';
        $status = false;

        if (isset($_GET['condition'])) {
            $condition = $_GET['condition'];

            if ($condition == 1) {
                $status = array('payment_demand', 'payment_ack', 'schedule_delivery', 'send_payment_confirmation',);
            } else if ($condition == 2) {
                $status = array('send_invoice');
            } else if ($condition == 3) {
                $status = array("complaint", "complaint_msg");
            } else if ($condition == 4) {
                $status = array("cancelled");
            } else if ($condition == 5) {
                $status = array("delivered", "send_mfg_payment_ack", "send_mfg_payment_confirmation");
            } else if ($condition == 6) {
                $status = array('received');
            } else if ($condition == 7) {
                $status = array('payment_ack', 'complaint', 'delivered');
            }
        }

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['seller_id'])) {
            $seller_id = $_GET['seller_id'];
        }

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $search = str_replace('HC-A', '', $search);

            $filters = [
                'u.username' => $search,
                //'db.username' => $search,
                'u.email' => $search,
                'o.id' => $search,
                'o.mobile' => $search,
                'o.address' => $search,
                'o.wallet_balance' => $search,
                'o.total' => $search,
                'o.final_total' => $search,
                'o.total_payable' => $search,
                'o.payment_method' => $search,
                'o.delivery_charge' => $search,
                'o.delivery_time' => $search,
                'o.order_status' => $search,
                //'o.status' => $search,
                //'o.active_status' => $search,
                'date_added' => $search,
                'rd.company_name' => $search,
            ];
        }

        if ($search_field != '') {
            $order_id_search = trim(preg_replace('/[^0-9]/', '', $search_field));

            $filters = [
                'rd.company_name' => trim($search_field),
                'sd.company_name' => trim($search_field),
            ];

            if ($order_id_search != '') {
                $filters = [
                    'o.id' => $order_id_search
                ];
            }
        }

        $count_res = $this->db->select(' COUNT(o.id) as `total` ')
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('retailer_data rd ', ' rd.user_id = o.user_id', 'left')
            ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
            ->join('addresses ad ', ' ad.id = o.billing_address_id', 'left')
            ->join('cities ct ', ' ct.id = ad.city_id', 'left')
            ->join('order_item_payment_confirmation as op', 'o.id = op.order_id', 'left')
            ->join('order_item_invoice as inv', 'o.id = inv.order_id', 'left')
            ->join('order_item_mfg_payment_ack as mfg_ack', 'o.id = mfg_ack.order_id', 'left');
        //->join('users db ', ' db.id = oi.delivery_boy_id', 'left');
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $count_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }

        if (isset($seller_id)) {
            $count_res->where("oi.seller_id", $seller_id);
        }

        /*if (isset($delivery_boy_id)) {
            $count_res->where("oi.delivery_boy_id", $delivery_boy_id);
        }*/

        if (isset($_GET['user_id']) && $_GET['user_id'] != null) {
            $count_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            if ($_GET['order_status'] == 'issue_closed') {
                $count_res->where('o.order_status', 'delivered');

                if ($_GET['order_status'] == 'issue_closed') {
                    $count_res->where_in("o.id", $issue_order_ids);
                }
            } else {
                $count_res->where('o.order_status', $_GET['order_status']);
                if ($_GET['order_status'] == 'delivered') {
                    $count_res->where_not_in("o.id", $issue_order_ids);
                }
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $count_res->where_in('oi.active_status', $status);
        }

        $count_res->where('o.is_service_category', 0);

        $count_res->group_by('o.id');

        $product_count = $count_res->get('`orders` o')->result_array();

        /*foreach ($product_count as $row) {
            $total = $row['total'];
        }*/

        $total = count($product_count);

        $search_res = $this->db->select(' o.* , u.username, rd.company_name as retailer_name, sd.company_name as mfg_name, sd.slug as seller_slug, ct.name as city_name, op.attachments as payment_receipt, inv.attachments as invoice_receipt, mfg_ack.attachments as hc_receipt')
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('retailer_data rd ', ' rd.user_id = o.user_id', 'left')
            ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
            ->join('addresses ad ', ' ad.id = o.billing_address_id', 'left')
            ->join('cities ct ', ' ct.id = ad.city_id', 'left')
            ->join('order_item_payment_confirmation as op', 'o.id = op.order_id', 'left')
            ->join('order_item_invoice as inv', 'o.id = inv.order_id', 'left')
            ->join('order_item_mfg_payment_ack as mfg_ack', 'o.id = mfg_ack.order_id', 'left');
        //->join('users db ', ' db.id = oi.delivery_boy_id', 'left');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }

        if (isset($seller_id)) {
            $search_res->where("oi.seller_id", $seller_id);
        }

        /*if (isset($delivery_boy_id)) {
            $search_res->where("oi.delivery_boy_id", $delivery_boy_id);
        }*/

        if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $search_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            if ($_GET['order_status'] == 'issue_closed') {
                $search_res->where('o.order_status', 'delivered');

                if ($_GET['order_status'] == 'issue_closed') {
                    $search_res->where_in("o.id", $issue_order_ids);
                }
            } else {
                $search_res->where('o.order_status', $_GET['order_status']);

                if ($_GET['order_status'] == 'delivered') {
                    $search_res->where_not_in("o.id", $issue_order_ids);
                }
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $search_res->where_in('oi.active_status', $status);
        }

        $search_res->where('o.is_service_category', 0);

        $user_details = $search_res->group_by('o.id')->order_by($sort, "DESC")->limit($limit, $offset)->get('`orders` o')->result_array();

        $i = 0;
        foreach ($user_details as $row) {
            $user_details[$i]['items'] = $this->db->select('oi.*,p.name as name,p.id as product_id, u.username as uname, us.username as seller, sd.company_name ')
                ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
                ->join('products p ', ' p.id = v.product_id ', 'left')
                ->join('users u ', ' u.id = oi.user_id', 'left')
                ->join('users us ', ' us.id = oi.seller_id', 'left')
                ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
                ->where('oi.order_id', $row['id'])
                ->get(' `order_items` oi  ')->result_array();
            ++$i;
        }

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $tota_amount = 0;
        $final_tota_amount = 0;
        $currency_symbol = get_settings('currency');

        //$order_msg = array('received'=>'Order Received','qty_update'=>'Quantity updated and approval request sent.','qty_approved'=>'Quantity approval accepted by retailer.','payment_demand'=>'Payment request sent.','payment_ack'=>'Transaction details Received.','schedule_delivery'=>'Order Scheduled.','shipped'=>'Order shipped.','send_invoice'=>'Invoices sent.','delivered'=>'Order Closed.','cancelled'=>'Order cancelled.');
        $order_msg = array('received' => 'Order Received', 'qty_update' => 'Quantity updated and approval request sent.', 'qty_approved' => 'Quantity approval accepted by retailer.', 'payment_demand' => 'Payment request sent.', 'payment_ack' => 'Transaction details received from retailer.', 'send_payment_confirmation' => 'Payment confirmation sent to retailer.', 'schedule_delivery' => 'Order Scheduled.', 'shipped' => 'Order shipped.', 'send_invoice' => 'E-way bill and invoices sent to retailer.', 'complaint' => 'Retailer raised his concern.', 'delivered' => 'Order delivered successfully.', 'cancelled' => 'Order cancelled.', 'send_mfg_payment_ack' => 'Transaction details shared with manufacturer.', 'send_mfg_payment_confirmation' => 'Payment receipt received.', 'complaint_msg' => 'Issue details shared by Happycrop', 'service_completed' => 'Service Completed');

        foreach ($user_details as $row) {
            
            
            if (!empty($row['items'])) {
                $items = $row['items'];
                $items1 = '';
                $temp = '';
                $total_amt = 0;
                //$seller = implode(",", array_values(array_unique(array_column($items, "seller"))));
                $company_name = implode(",", array_values(array_unique(array_column($items, "company_name"))));

                foreach ($items as $item) {
                    $product_variants = get_variants_values_by_id($item['product_variant_id']);
                    $variants = isset($product_variants[0]['variant_values']) && !empty($product_variants[0]['variant_values']) ? str_replace(',', ' | ', $product_variants[0]['variant_values']) : '-';
                    $temp .= "<b>ID :</b>" . $item['id'] . "<b> Product Variant Id :</b> " . $item['product_variant_id'] . "<b> Variants :</b> " . $variants . "<b> Name : </b>" . $item['name'] . " <b>Price : </b>" . $item['price'] . " <b>QTY : </b>" . $item['quantity'] . " <b>Subtotal : </b>" . $item['quantity'] * $item['price'] . "<br>------<br>";
                    $total_amt += $item['sub_total'];
                }

                $items1 = $temp;
                $discounted_amount = $row['total'] * $row['items'][0]['discount'] / 100;
                $final_total = $row['total'] - $discounted_amount;
                $discount_in_rupees = $row['total'] - $final_total;
                $discount_in_rupees = floor($discount_in_rupees);
                $tempRow['id'] = 'HC-A' . $row['id'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['name'] = $row['retailer_name']; //$row['items'][0]['uname'];
                /*if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $tempRow['mobile'] = str_repeat("X", strlen($row['mobile']) - 3) . substr($row['mobile'], -3);
                } else {*/
                $tempRow['mobile'] = $row['mobile'];
                //}
                $tempRow['delivery_charge'] = $currency_symbol . ' ' . $row['delivery_charge'];
                $tempRow['items'] = $items1;
                $tempRow['sellers'] = '<a href="' . base_url('products?seller=' . $row['seller_slug']) . '">' . $company_name . '</a>'; //$seller;
                $tempRow['total'] = $currency_symbol . ' ' . $row['total'];
                $tota_amount += intval($row['total']);
                $tempRow['wallet_balance'] = $currency_symbol . ' ' . $row['wallet_balance'];
                $tempRow['discount'] = $currency_symbol . ' ' . $discount_in_rupees . '(' . $row['items'][0]['discount'] . '%)';
                $tempRow['promo_discount'] = $currency_symbol . ' ' . $row['promo_discount'];
                $tempRow['promo_code'] = $row['promo_code'];
                $tempRow['notes'] = $row['notes'];
                $tempRow['qty'] = $row['items'][0]['quantity'];
                $tempRow['final_total'] = $currency_symbol . ' ' . $row['total_payable'];
                $final_total = $row['final_total'] - $row['wallet_balance'] - $row['promo_discount'] - $row['discount'];
                $tempRow['final_total'] = $currency_symbol . ' ' . $final_total;
                $final_tota_amount += intval($row['final_total']);
                $tempRow['deliver_by'] = $row['delivery_boy'];
                $tempRow['payment_method'] = $row['payment_method'];
                $tempRow['address'] = output_escaping(str_replace('\r\n', '</br>', $row['address']));
                $tempRow['city_name'] = $row['city_name'];
                $tempRow['delivery_date'] = $row['delivery_date'];
                $tempRow['delivery_time'] = $row['delivery_time'];
                $tempRow['date_added'] = date('d-m-Y', strtotime($row['date_added']));

                $tempRow['schedule_delivery_date'] = ($row['schedule_delivery_date'] != null && $row['schedule_delivery_date'] != '0000-00-00') ?  date('d-m-Y', strtotime($row['schedule_delivery_date'])) : '';

                $this->db->select('id');
                $this->db->from('order_item_stages');
                $this->db->where('status', 'issue_resolved');
                $this->db->where('order_id', $row['id']);
                $q = $this->db->get();
                $rw = $q->row_array();

                if ($rw['id'] && $row['order_status'] == 'delivered') {
                    $order_msg['delivered'] = 'Issue resolved. Make payment.'; //'Order closed.';//$order_msg['send_mfg_payment_ack'] = $order_msg['send_mfg_payment_confirmation'] = 
                } else {
                    $order_msg['delivered'] = 'Order delivered.'; //$order_msg['send_mfg_payment_ack'] = $order_msg['send_mfg_payment_confirmation'] = 
                }

                $tempRow['order_status'] = $order_msg[$row['order_status']];

                // $tempRow['payment_receipt'] = (file_exists($row['payment_receipt']) && $row['payment_receipt']!='') ? '<a href="'.base_url($row['payment_receipt']).'" target="_blank">View / Download</a>' : '';
                // $tempRow['invoice_receipt'] = (file_exists($row['invoice_receipt']) && $row['invoice_receipt']!='') ? '<a href="'.base_url($row['invoice_receipt']).'" target="_blank">View / Download</a>' : '';
                // $tempRow['hc_receipt'] = (file_exists($row['hc_receipt']) && $row['hc_receipt']!='') ? '<a href="'.base_url($rowstatus[=>''hc_receipt']).'" target="_blank">View / Download</a>' : '';
                $hc_receipt = $this->common_model->getRecords("order_item_stages", '*', array('order_id' => $row["id"],'status'=>'send_mfg_payment_ack'));
                $payment_ack = $this->common_model->getRecords("order_item_stages", '*', array('order_id' => $row["id"],'status'=>'payment_ack'));
                $send_invoice = $this->common_model->getRecords("order_item_stages", '*', array('order_id' => $row["id"],'status'=>'send_invoice'));

                $tempRow['payment_receipt'] = (!empty($payment_ack)) ? '<a href="' . base_url("my-account/payment-receipt/") . $row['id'] . "/view" . '" target="_blank">View / Download</a>' : '';
                $tempRow['invoice_receipt'] = (!empty($send_invoice)) ? '<a href="' . base_url("my-account/tax-invoice/") . $row['id'] . "/view" . '" target="_blank">View / Download</a>' : '';
                $tempRow['hc_receipt'] = (!empty($hc_receipt)) ? '<a href="' . base_url("seller/orders/paymentreceipt/") . $row['id'] . "/view" . '" target="_blank">View / Download</a>' : '';

                /*
                $tempRow['color_state'] = '';
                if($row['order_status'] == 'delivered')
                {
                    if($rw['id'])
                    {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    }
                    else
                    {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                }
                else if($row['order_status'] == 'service_completed')
                {
                    $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                }
                else if($row['order_status'] == 'send_mfg_payment_ack')
                {
                    if($rw['id'])
                    {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    }
                    else
                    {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                }
                else if($row['order_status'] == 'send_mfg_payment_confirmation')
                {
                    if($rw['id'])
                    {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    }
                    else
                    {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                }
                else if($row['order_status'] == 'cancelled')
                {
                    $tempRow['color_state'] = '<span class="cancelled-state"><i class="fa fa-circle"></i></span>';
                }
                else if($row['order_status'] == 'complaint')
                {
                    $tempRow['color_state'] = '<span class="issue-state"><i class="fa fa-circle"></i></span>';
                }
                else if($row['order_status'] == 'complaint_msg')
                {
                    $tempRow['color_state'] = '<span class="issue-state"><i class="fa fa-circle"></i></span>';
                }
                else
                {
                    $tempRow['color_state'] = '<span class="active-state"><i class="fa fa-circle"></i></span>';
                }
                */
                $tempRow['last_updated']   = ($row['last_updated'] != null) ? date('d-m-Y', strtotime($row['last_updated'])) : '';

                $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['id'] . '" class="btn btn-primary btn-xs mr-1 mb-1" title="View" >View Details</a>';
                if (!$this->ion_auth->is_delivery_boy()) {
                    $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View" >View Details</a>';
                    //$operate .= '<a href="javascript:void(0)" class="delete-orders btn btn-danger btn-xs mr-1 mb-1" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i></a>';
                    //$operate .= '<a href="' . base_url() . 'admin/invoice?edit_id=' . $row['id'] . '" class="btn btn-info btn-xs mr-1 mb-1" title="Invoice" ><i class="fa fa-file"></i></a>';
                    //$operate .= ' <a href="javascript:void(0)" class="edit_order_tracking btn btn-success btn-xs mr-1 mb-1" title="Order Tracking" data-order_id="' . $row['id'] . '"  data-target="#order-tracking-modal" data-toggle="modal"><i class="fa fa-map-marker-alt"></i></a>';
                } else {
                    $operate = '<a href=' . base_url('delivery_boy/orders/edit_orders') . '?edit_id=' . $row['id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View">View Details</a>';
                }
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_seller_account_orders_list(
        $seller_id = NULL,
        $offset = 0,
        $limit = 10,
        $sort = " o.id ",
        $order = 'DESC'
    ) {

        $this->db->select('order_id');
        $this->db->from('order_item_stages');
        $this->db->where('status', 'issue_resolved');
        $q              = $this->db->get();
        $issue_orders = $q->result_array();

        $issue_order_ids = array();
        if ($issue_orders) {
            $issue_order_ids = array_column($issue_orders, 'order_id');
        }

        $search_field = '';

        if (isset($_GET['search_field'])) {
            $search_field = $_GET['search_field'];
        }

        $condition = '';
        $status = false;

        if (isset($_GET['condition'])) {
            $condition = $_GET['condition'];

            if ($condition == 1) {
                $status = array('payment_demand', 'payment_ack', 'schedule_delivery', 'send_payment_confirmation',);
            } else if ($condition == 2) {
                $status = array('send_invoice');
            } else if ($condition == 3) {
                $status = array("complaint", "complaint_msg");
            } else if ($condition == 4) {
                $status = array("cancelled");
            } else if ($condition == 5) {
                $status = array("delivered", "send_mfg_payment_ack", "send_mfg_payment_confirmation");
            } else if ($condition == 6) {
                $status = array('received');
            } else if ($condition == 7) {
                $status = array('received', 'send_payment_confirmation', 'send_mfg_payment_ack');
            }
        }


        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $search = str_replace('HC-A', '', $search);

            $filters = [
                'u.username' => $search,
                //'db.username' => $search,
                'u.email' => $search,
                'o.id' => $search,
                'o.mobile' => $search,
                'o.address' => $search,
                'o.billing_address' => $search,
                'o.wallet_balance' => $search,
                'o.total' => $search,
                'o.final_total' => $search,
                'o.total_payable' => $search,
                'o.payment_method' => $search,
                'o.delivery_charge' => $search,
                'o.delivery_time' => $search,
                'o.order_status' => $search,
                //'o.active_status' => $search,
                'o.date_added' => $search,
                'rd.company_name' => $search,
            ];
        }

        if ($search_field != '') {
            $order_id_search = trim(preg_replace('/[^0-9]/', '', $search_field));

            $filters = [
                'rd.company_name' => trim($search_field),
            ];

            if ($order_id_search != '') {
                $filters = [
                    'o.id' => $order_id_search
                ];
            }
        }


        $count_res = $this->db->distinct()->select('o.id')
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('retailer_data rd ', ' rd.user_id = o.user_id', 'left')
            ->join('addresses ad ', ' ad.id = o.billing_address_id', 'left')
            ->join('cities ct ', ' ct.id = ad.city_id', 'left')
            ->join('order_item_payment_confirmation as op', 'o.id = op.order_id', 'left')
            ->join('order_item_invoice as inv', 'o.id = inv.order_id', 'left')
            ->join('order_item_mfg_payment_ack as mfg_ack', 'o.id = mfg_ack.order_id', 'left');
        //->join('users db ', ' db.id = oi.delivery_boy_id', 'left');
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $count_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }

        if (isset($seller_id)) {
            $count_res->where("oi.seller_id", $seller_id);
        }

        if (isset($_GET['user_id']) && $_GET['user_id'] != null) {
            $count_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            if ($_GET['order_status'] == 'issue_closed') {
                $count_res->where('o.order_status', 'delivered');
                //$count_res->where('o.order_status', 'send_mfg_payment_confirmation');
                if ($_GET['order_status'] == 'issue_closed') {
                    $count_res->where_in("o.id", $issue_order_ids);
                }
            } else {
                $count_res->where('o.order_status', $_GET['order_status']);
                if ($_GET['order_status'] == 'delivered') {
                    $count_res->where_not_in("o.id", $issue_order_ids);
                }
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $count_res->where_in('oi.active_status', $status);
        }

        $count_res->where('o.is_service_category', 0);

        $count_res->group_by('o.id');

        //var_dump($count_res, $filters);

        $product_count = $count_res->get('`orders` o')->result_array();
        //var_dump($count_res);die;

        //foreach ($product_count as $row) {
        $total = count($product_count); //$row['total'];
        //}

        $search_res = $this->db->select(' o.* , u.username, rd.company_name as retailer_name, ct.name as city_name, op.attachments as payment_receipt, inv.attachments as invoice_receipt, mfg_ack.attachments as hc_receipt') //, db.username as delivery_boy
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('retailer_data rd ', ' rd.user_id = o.user_id', 'left')
            ->join('addresses ad ', ' ad.id = o.billing_address_id', 'left')
            ->join('cities ct ', ' ct.id = ad.city_id', 'left')
            ->join('order_item_payment_confirmation as op', 'o.id = op.order_id', 'left')
            ->join('order_item_invoice as inv', 'o.id = inv.order_id', 'left')
            ->join('order_item_mfg_payment_ack as mfg_ack', 'o.id = mfg_ack.order_id', 'left');
        //->join('users db ', ' db.id = oi.delivery_boy_id', 'left');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }

        if (isset($seller_id)) {
            $search_res->where("oi.seller_id", $seller_id);
        }

        if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $search_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            if ($_GET['order_status'] == 'issue_closed') {
                $search_res->where('o.order_status', 'delivered');
                //$search_res->where('o.order_status', 'send_mfg_payment_confirmation'); 
                if ($_GET['order_status'] == 'issue_closed') {
                    $search_res->where_in("o.id", $issue_order_ids);
                }
            } else {
                $search_res->where('o.order_status', $_GET['order_status']);

                if ($_GET['order_status'] == 'delivered') {
                    $search_res->where_not_in("o.id", $issue_order_ids);
                }
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $search_res->where_in('oi.active_status', $status);
        }

        $search_res->where('o.is_service_category', 0);

        $user_details = $search_res->group_by('o.id')->order_by($sort, "DESC")->limit($limit, $offset)->get('`orders` o')->result_array();

        //echo $this->db->last_query();die;

        $i = 0;
        foreach ($user_details as $row) {
            $user_details[$i]['items'] = $this->db->select('oi.*,p.name as name,p.id as product_id, u.username as uname, us.username as seller, sd.company_name ')
                ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
                ->join('products p ', ' p.id = v.product_id ', 'left')
                ->join('users u ', ' u.id = oi.user_id', 'left')
                ->join('users us ', ' us.id = oi.seller_id', 'left')
                ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
                ->where('oi.order_id', $row['id'])
                ->get(' `order_items` oi  ')->result_array();
            ++$i;
        }

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $tota_amount = 0;
        $final_tota_amount = 0;
        $currency_symbol = get_settings('currency');
        $or_state = array('delivered', 'cancelled');

        $order_msg = array('received' => 'Order Received', 'qty_update' => 'Quantity updated and approval request sent.', 'qty_approved' => 'Quantity approval accepted by retailer.', 'payment_demand' => 'Payment request sent.', 'payment_ack' => 'Retailer shared transaction details with Happycrop.', 'send_payment_confirmation' => 'Payment confirmation sent to retailer by Happycrop.', 'schedule_delivery' => 'Order Scheduled.', 'shipped' => 'Order shipped.', 'send_invoice' => 'E-way bill and invoices sent to retailer.', 'complaint' => 'Retailer raised his concern.', 'delivered' => 'Your order delivered successfully.', 'cancelled' => 'Order cancelled.', 'send_mfg_payment_ack' => 'Transaction details received from Happycrop.', 'send_mfg_payment_confirmation' => 'Payment confirmation sent to Happycrop.', 'complaint_msg' => 'Issue details shared by Happycrop', 'service_completed' => 'Service Completed');

        //$sr_no = 1;
        foreach ($user_details as $row) {
            if (!empty($row['items'])) {
                $items = $row['items'];
                $items1 = '';
                $temp = '';
                $total_amt = 0;
                //$seller = implode(",", array_values(array_unique(array_column($items, "seller"))));
                $company_name = implode(",", array_values(array_unique(array_column($items, "company_name"))));

                foreach ($items as $item) {
                    $product_variants = get_variants_values_by_id($item['product_variant_id']);
                    $variants = isset($product_variants[0]['variant_values']) && !empty($product_variants[0]['variant_values']) ? str_replace(',', ' | ', $product_variants[0]['variant_values']) : '-';
                    $temp .= "<b>ID :</b>" . $item['id'] . "<b> Product Variant Id :</b> " . $item['product_variant_id'] . "<b> Variants :</b> " . $variants . "<b> Name : </b>" . $item['name'] . " <b>Price : </b>" . $item['price'] . " <b>QTY : </b>" . $item['quantity'] . " <b>Subtotal : </b>" . $item['quantity'] * $item['price'] . "<br>------<br>";
                    $total_amt += $item['sub_total'];
                }

                $items1 = $temp;
                $discounted_amount = $row['total'] * $row['items'][0]['discount'] / 100;
                $final_total = $row['total'] - $discounted_amount;
                $discount_in_rupees = $row['total'] - $final_total;
                $discount_in_rupees = floor($discount_in_rupees);
                //$tempRow['sr_no']   = $offset + $sr_no;
                //$sr_no++;
                $tempRow['id'] = 'HC-A' . $row['id'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['name'] = $row['retailer_name']; //$row['items'][0]['uname'];
                /*if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $tempRow['mobile'] = str_repeat("X", strlen($row['mobile']) - 3) . substr($row['mobile'], -3);
                } else {*/
                $tempRow['mobile'] = $row['mobile'];
                //}
                $tempRow['delivery_charge'] = $currency_symbol . ' ' . $row['delivery_charge'];
                $tempRow['items'] = $items1;
                $tempRow['sellers'] = $company_name; //$seller;
                $tempRow['total'] = $currency_symbol . ' ' . $row['total'];
                $tota_amount += intval($row['total']);
                $tempRow['wallet_balance'] = $currency_symbol . ' ' . $row['wallet_balance'];
                $tempRow['discount'] = $currency_symbol . ' ' . $discount_in_rupees . '(' . $row['items'][0]['discount'] . '%)';
                $tempRow['promo_discount'] = $currency_symbol . ' ' . $row['promo_discount'];
                $tempRow['promo_code'] = $row['promo_code'];
                $tempRow['notes'] = $row['notes'];
                $tempRow['qty'] = $row['items'][0]['quantity'];
                $tempRow['final_total'] = $currency_symbol . ' ' . $row['total_payable'];
                $final_total = $row['final_total'] - $row['wallet_balance'] - $row['promo_discount'] - $row['discount'];
                $tempRow['final_total'] = $currency_symbol . ' ' . $final_total;
                $final_tota_amount += intval($row['final_total']);
                $tempRow['deliver_by'] = $row['delivery_boy'];
                $tempRow['payment_method'] = $row['payment_method'];
                $tempRow['billing_address'] = output_escaping(str_replace('\r\n', '</br>', $row['billing_address']));
                $tempRow['address'] = output_escaping(str_replace('\r\n', '</br>', $row['address']));
                $tempRow['city_name'] = $row['city_name'];
                $tempRow['delivery_date'] = $row['delivery_date'];
                $tempRow['delivery_time'] = $row['delivery_time'];
                $tempRow['date_added'] = date('d-m-Y', strtotime($row['date_added']));
                $tempRow['schedule_delivery_date'] = ($row['schedule_delivery_date'] != null && $row['schedule_delivery_date'] != '0000-00-00') ?  date('d-m-Y', strtotime($row['schedule_delivery_date'])) : '';

                $this->db->select('id');
                $this->db->from('order_item_stages');
                $this->db->where('status', 'issue_resolved');
                $this->db->where('order_id', $row['id']);
                $q = $this->db->get();
                $rw = $q->row_array();

                if ($rw['id'] && $row['order_status'] == 'delivered') {
                    $order_msg['delivered'] = 'Issue resolved '; //'Order closed.';//$order_msg['send_mfg_payment_ack'] = $order_msg['send_mfg_payment_confirmation'] = 
                } else {
                    $order_msg['delivered'] = 'Order delivered.'; //$order_msg['send_mfg_payment_ack'] = $order_msg['send_mfg_payment_confirmation'] = 
                }

                $tempRow['order_status'] = $order_msg[$row['order_status']];

                // $tempRow['payment_receipt'] = (file_exists($row['payment_receipt']) && $row['payment_receipt']!='') ? '<a href="'.base_url($row['payment_receipt']).'" target="_blank">View / Download</a>' : '';
                // $tempRow['invoice_receipt'] = (file_exists($row['invoice_receipt']) && $row['invoice_receipt']!='') ? '<a href="'.base_url($row['invoice_receipt']).'" target="_blank">View / Download</a>' : '';
                // $tempRow['hc_receipt'] = (file_exists($row['hc_receipt']) && $row['hc_receipt']!='') ? '<a href="'.base_url($row['hc_receipt']).'" target="_blank">View / Download</a>' : '';
                $tempRow['payment_receipt'] = (file_exists($row['payment_receipt']) && $row['payment_receipt'] != '') ? '<a href="' . base_url("my-account/payment-receipt/") . $row['id'] . "/view" . '" target="_blank">View / Download</a>' : '';
                $tempRow['invoice_receipt'] = (file_exists($row['invoice_receipt']) && $row['invoice_receipt'] != '') ? '<a href="' . base_url("my-account/tax-invoice/") . $row['id'] . "/view" . '" target="_blank">View / Download</a>' : '';
                $tempRow['hc_receipt'] = (file_exists($row['hc_receipt']) && $row['hc_receipt'] != '') ? '<a href="' . base_url("seller/orders/paymentreceipt/") . $row['id'] . "/view" . '" target="_blank">View / Download</a>' : '';


                /*$tempRow['color_state'] = '';
                if($row['order_status'] == 'delivered')
                {
                    if($rw['id'])
                    {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    }
                    else
                    {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                }
                else if($row['order_status'] == 'service_completed')
                {
                    $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                }
                else if($row['order_status'] == 'send_mfg_payment_ack')
                {
                    if($rw['id'])
                    {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    }
                    else
                    {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                }
                else if($row['order_status'] == 'send_mfg_payment_confirmation')
                {
                    if($rw['id'])
                    {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    }
                    else
                    {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                }
                else if($row['order_status'] == 'cancelled')
                {
                    $tempRow['color_state'] = '<span class="cancelled-state"><i class="fa fa-circle"></i></span>';
                }
                else if($row['order_status'] == 'complaint')
                {
                    $tempRow['color_state'] = '<span class="issue-state"><i class="fa fa-circle"></i></span>';
                }
                else if($row['order_status'] == 'complaint_msg')
                {
                    $tempRow['color_state'] = '<span class="issue-state"><i class="fa fa-circle"></i></span>';
                }
                else
                {
                    $tempRow['color_state'] = '<span class="active-state"><i class="fa fa-circle"></i></span>';
                }*/

                $tempRow['last_updated']   = ($row['last_updated'] != null) ? date('d-m-Y', strtotime($row['last_updated'])) : '';

                //$operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['id'] . '" class="btn btn-primary btn-xs mr-1 mb-1" title="View" ><i class="fa fa-eye"></i></a>';
                $operate = '';
                if (!$this->ion_auth->is_delivery_boy()) {
                    $operate = '<a href=' . base_url('seller/orders/edit_orders') . '?edit_id=' . $row['id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View" >View Details</a>';
                    //$operate .= '<a href="javascript:void(0)" class="delete-orders btn btn-danger btn-xs mr-1 mb-1" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i></a>';
                    //$operate .= '<a href="' . base_url() . 'admin/invoice?edit_id=' . $row['id'] . '" class="btn btn-info btn-xs mr-1 mb-1" title="Invoice" ><i class="fa fa-file"></i></a>';
                    //$operate .= ' <a href="javascript:void(0)" class="edit_order_tracking btn btn-success btn-xs mr-1 mb-1" title="Order Tracking" data-order_id="' . $row['id'] . '"  data-target="#order-tracking-modal" data-toggle="modal"><i class="fa fa-map-marker-alt"></i></a>';
                } else {
                    //$operate = '<a href=' . base_url('delivery_boy/orders/edit_orders') . '?edit_id=' . $row['id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View"><i class="fa fa-eye"></i></a>';

                }
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_order_account_list($user_id = '', $status = array())
    {
        $search_field = '';
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $multipleWhere = '';

        if (isset($_GET['search_field'])) {
            $search_field = $_GET['search_field'];
        }

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $filters = [
                'us.username' => $search,
                'sd.company_name' => $search,
                'us.email' => $search,
                'o.id' => $search,
                'o.mobile' => $search,
                'o.address' => $search,
                'o.wallet_balance' => $search,
                'o.total' => $search,
                'o.final_total' => $search,
                'o.total_payable' => $search,
                'o.payment_method' => $search,
                //'o.delivery_charge' => $search,
                //'o.delivery_time' => $search,
                //'o.status' => $search,
                'o.order_status' => $search,
                'o.date_added' => $search
            ];
        }

        if ($search_field != '') {
            $order_id_search = trim(preg_replace('/[^0-9]/', '', $search_field));

            $filters = [
                'sd.company_name' => trim($search_field),
            ];

            if ($order_id_search != '') {
                $filters = [
                    'o.id' => $order_id_search
                ];
            }
        }

        $count_res = $this->db->distinct()->select('o.id')
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
            ->join('order_item_payment_confirmation as op', 'o.id = op.order_id', 'left')
            ->join('order_item_invoice as inv', 'o.id = inv.order_id', 'left');
        //->join('users db ', ' db.id = oi.delivery_boy_id', 'left');
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $count_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }



        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }



        if (isset($seller_id)) {
            $count_res->where("oi.seller_id", $seller_id);
        }

        if (isset($user_id) && $user_id != null) {
            $count_res->where("o.user_id", $user_id);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            if ($_GET['order_status'] == 'delivered') {
                $count_res->where(" (o.order_status = 'delivered' OR o.order_status = 'send_mfg_payment_ack' OR o.order_status ='send_mfg_payment_confirmation') ");
                $count_res->where_not_in("o.id", $issue_order_ids);
            } else if ($_GET['order_status'] == 'issue_closed') {
                $count_res->where(" (o.order_status = 'delivered' OR o.order_status = 'send_mfg_payment_ack' OR o.order_status ='send_mfg_payment_confirmation') ");
                $count_res->where_in("o.id", $issue_order_ids);
            } else {
                $count_res->where('o.order_status', $_GET['order_status']);
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $count_res->where_in('oi.active_status', $status);
        }

        $count_res->where('o.is_service_category', 0);

        $count_res->group_by('o.id');

        $product_count = $count_res->get('`orders` o')->result_array();

        //foreach ($product_count as $row) {
        $total = count($product_count); //$row['total'];
        //}

        $search_res = $this->db->select(' o.* , u.username, sd.company_name, sd.slug as seller_slug, op.attachments as retailer_pay_confirm, inv.attachments as retailer_invoice') //, db.username as delivery_boy
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
            ->join('order_item_payment_confirmation as op', 'o.id = op.order_id', 'left')
            ->join('order_item_invoice as inv', 'o.id = inv.order_id', 'left');
        //->join('users db ', ' db.id = oi.delivery_boy_id', 'left');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }


        if (isset($user_id) && !empty($user_id)) {
            $search_res->where("o.user_id", $user_id);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            if ($_GET['order_status'] == 'delivered') {
                $search_res->where(" (o.order_status = 'delivered' OR o.order_status = 'send_mfg_payment_ack' OR o.order_status ='send_mfg_payment_confirmation') ");
                $search_res->where_not_in("o.id", $issue_order_ids);
            } else if ($_GET['order_status'] == 'issue_closed') {
                $search_res->where(" (o.order_status = 'delivered' OR o.order_status = 'send_mfg_payment_ack' OR o.order_status ='send_mfg_payment_confirmation') ");
                $search_res->where_in("o.id", $issue_order_ids);
            } else {
                $search_res->where('o.order_status', $_GET['order_status']);
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $search_res->where_in('oi.active_status', $status);
        }

        $search_res->where('o.is_service_category', 0);

        $user_details = $search_res->group_by('o.id')->order_by($sort, $order)->limit($limit, $offset)->get('`orders` o')->result_array();

        //echo $this->db->last_query();die;

        $i = 0;
        foreach ($user_details as $row) {
            $user_details[$i]['items'] = $this->db->select('oi.*,p.name as name,p.id as product_id, u.username as uname, us.username as seller, sd.company_name ')
                ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
                ->join('products p ', ' p.id = v.product_id ', 'left')
                ->join('users u ', ' u.id = oi.user_id', 'left')
                ->join('users us ', ' us.id = oi.seller_id', 'left')
                ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
                ->where('oi.order_id', $row['id'])
                ->get(' `order_items` oi  ')->result_array();
            ++$i;
        }

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $tota_amount = 0;
        $final_tota_amount = 0;
        $currency_symbol = get_settings('currency');
        $or_state = array('delivered', 'cancelled');

        $order_msg = array('received' => 'Order Placed', 'qty_update' => 'Quantity updated and approval request received.', 'qty_approved' => 'Quantity approval accepted by you.', 'payment_demand' => 'Payment request received.', 'payment_ack' => 'Transaction details shared.', 'schedule_delivery' => 'Order Scheduled.', 'shipped' => 'Order shipped.', 'send_invoice' => 'Order is in transit, E-way bill and invoices received from manufacturer.', 'delivered' => 'Order delivered.', 'cancelled' => 'Order cancelled.', 'complaint' => 'Issue Raised', 'send_payment_confirmation' => 'Payment receipt received', 'send_mfg_payment_ack' => 'Order delivered.', 'send_mfg_payment_confirmation' => 'Order delivered.', 'complaint_msg' => 'Issue details shared by Happycrop.', 'service_completed' => 'Service Completed');

        //$sr_no = 1;
        foreach ($user_details as $row) {
            if (!empty($row['items'])) {
                $items = $row['items'];
                $items1 = '';
                $temp = '';
                $total_amt = 0;
                //$seller = implode(",", array_values(array_unique(array_column($items, "seller"))));
                $company_name = implode(",", array_values(array_unique(array_column($items, "company_name"))));

                foreach ($items as $item) {
                    $product_variants = get_variants_values_by_id($item['product_variant_id']);
                    $variants = isset($product_variants[0]['variant_values']) && !empty($product_variants[0]['variant_values']) ? str_replace(',', ' | ', $product_variants[0]['variant_values']) : '-';
                    $temp .= "<b>ID :</b>" . $item['id'] . "<b> Product Variant Id :</b> " . $item['product_variant_id'] . "<b> Variants :</b> " . $variants . "<b> Name : </b>" . $item['name'] . " <b>Price : </b>" . $item['price'] . " <b>QTY : </b>" . $item['quantity'] . " <b>Subtotal : </b>" . $item['quantity'] * $item['price'] . "<br>------<br>";
                    $total_amt += $item['sub_total'];
                }

                $items1 = $temp;
                $discounted_amount = $row['total'] * $row['items'][0]['discount'] / 100;
                $final_total = $row['total'] - $discounted_amount;
                $discount_in_rupees = $row['total'] - $final_total;
                $discount_in_rupees = floor($discount_in_rupees);
                //$tempRow['sr_no']   = $offset + $sr_no;
                //$sr_no++;
                $tempRow['id'] = 'HC-A' . $row['id'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['name'] = $row['items'][0]['uname'];
                /*if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $tempRow['mobile'] = str_repeat("X", strlen($row['mobile']) - 3) . substr($row['mobile'], -3);
                } else {*/
                $tempRow['mobile'] = $row['mobile'];
                //}
                $tempRow['delivery_charge'] = $currency_symbol . ' ' . $row['delivery_charge'];
                $tempRow['items'] = $items1;
                $tempRow['sellers'] = '<a href="' . base_url('products?seller=' . $row['seller_slug']) . '">' . $company_name . '</a>'; //$seller;
                $tempRow['total'] = $currency_symbol . ' ' . $row['total'];
                $tota_amount += intval($row['total']);
                $tempRow['wallet_balance'] = $currency_symbol . ' ' . $row['wallet_balance'];
                $tempRow['discount'] = $currency_symbol . ' ' . $discount_in_rupees . '(' . $row['items'][0]['discount'] . '%)';
                $tempRow['promo_discount'] = $currency_symbol . ' ' . $row['promo_discount'];
                $tempRow['promo_code'] = $row['promo_code'];
                $tempRow['notes'] = $row['notes'];
                $tempRow['qty'] = $row['items'][0]['quantity'];
                $tempRow['final_total'] = $currency_symbol . ' ' . $row['total_payable'];
                $final_total = $row['final_total'] - $row['wallet_balance'] - $row['promo_discount'] - $row['discount'];
                $tempRow['final_total'] = $currency_symbol . ' ' . $final_total;
                $final_tota_amount += intval($row['final_total']);
                $tempRow['deliver_by'] = $row['delivery_boy'];
                $tempRow['payment_method'] = $row['payment_method'];
                $tempRow['billing_address'] = output_escaping(str_replace('\r\n', '</br>', $row['billing_address']));
                $tempRow['address'] = output_escaping(str_replace('\r\n', '</br>', $row['address']));
                $tempRow['delivery_date'] = $row['delivery_date'];
                $tempRow['delivery_time'] = $row['delivery_time'];
                $tempRow['date_added'] = date('d-m-Y', strtotime($row['date_added']));
                $tempRow['schedule_delivery_date'] = ($row['schedule_delivery_date'] != null && $row['schedule_delivery_date'] != '0000-00-00') ?  date('d-m-Y', strtotime($row['schedule_delivery_date'])) : '';

                $tempRow['last_updated']   = ($row['last_updated'] != null) ? date('d-m-Y', strtotime($row['last_updated'])) : '';


                // $tempRow['payment_receipt'] = (file_exists($row['retailer_pay_confirm']) && $row['retailer_pay_confirm']!='') ? '<a href="'.base_url($row['retailer_pay_confirm']).'" target="_blank">View / Download</a>' : '';
                // $tempRow['invoice_receipt'] = (file_exists($row['retailer_invoice']) && $row['retailer_invoice']!='') ? '<a href="'.base_url($row['retailer_invoice']).'" target="_blank">View / Download</a>' : '';

                $tempRow['payment_receipt'] = (file_exists($row['retailer_pay_confirm']) && $row['retailer_pay_confirm'] != '') ? '<a href="' . base_url("my-account/payment-receipt/") . $row['id'] . "/view" . '" target="_blank">View / Download</a>' : '';
                $tempRow['invoice_receipt'] = (file_exists($row['retailer_invoice']) && $row['retailer_invoice'] != '') ? '<a href="' . base_url("my-account/tax-invoice/") . $row['id'] . "/view" . '" target="_blank">View / Download</a>' : '';

                $this->db->select('id');
                $this->db->from('order_item_stages');
                $this->db->where('status', 'issue_resolved');
                $this->db->where('order_id', $row['id']);
                $q = $this->db->get();
                $rw = $q->row_array();

                if ($rw['id']) {
                    $order_msg['delivered'] = $order_msg['send_mfg_payment_ack'] = $order_msg['send_mfg_payment_confirmation'] = 'Order closed.';
                } else {
                    $order_msg['delivered'] = $order_msg['send_mfg_payment_ack'] = $order_msg['send_mfg_payment_confirmation'] = 'Order delivered.';
                }

                $tempRow['order_status'] = $order_msg[$row['order_status']];

                /*$tempRow['color_state'] = '';
                if($row['order_status'] == 'delivered')
                {
                    if($rw['id'])
                    {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    }
                    else
                    {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                }
                else if($row['order_status'] == 'service_completed')
                {
                    $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                }
                else if($row['order_status'] == 'send_mfg_payment_ack')
                {
                    if($rw['id'])
                    {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    }
                    else
                    {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                }
                else if($row['order_status'] == 'send_mfg_payment_confirmation')
                {
                    if($rw['id'])
                    {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    }
                    else
                    {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                }
                else if($row['order_status'] == 'cancelled')
                {
                    $tempRow['color_state'] = '<span class="cancelled-state"><i class="fa fa-circle"></i></span>';
                }
                else if($row['order_status'] == 'complaint')
                {
                    $tempRow['color_state'] = '<span class="issue-state"><i class="fa fa-circle"></i></span>';
                }
                else if($row['order_status'] == 'complaint_msg')
                {
                    $tempRow['color_state'] = '<span class="issue-state"><i class="fa fa-circle"></i></span>';
                }
                else
                {
                    $tempRow['color_state'] = '<span class="active-state"><i class="fa fa-circle"></i></span>';
                }
                */

                //$operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['id'] . '" class="btn btn-primary btn-xs mr-1 mb-1" title="View" ><i class="fa fa-eye"></i></a>';
                $operate = '';
                //if (!$this->ion_auth->is_delivery_boy()) {
                $operate = '<a href="' . base_url('my-account/order-details/') . $row['id'] . '" class="btn btn-primary btn-sm mr-1 mb-1" title="View" >View Details</a>';
                $operate .= '<a onclick="re_order_the_order(' . $row['id'] . ')" href="javascript:void(0);" class="btn btn-secondary btn-sm mr-1 mb-1" title="View" ><i class="fas fa-refresh"></i></a>';
                //$operate .= '<a href="javascript:void(0)" class="delete-orders btn btn-danger btn-xs mr-1 mb-1" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i></a>';
                //$operate .= '<a href="' . base_url() . 'admin/invoice?edit_id=' . $row['id'] . '" class="btn btn-info btn-xs mr-1 mb-1" title="Invoice" ><i class="fa fa-file"></i></a>';
                //$operate .= ' <a href="javascript:void(0)" class="edit_order_tracking btn btn-success btn-xs mr-1 mb-1" title="Order Tracking" data-order_id="' . $row['id'] . '"  data-target="#order-tracking-modal" data-toggle="modal"><i class="fa fa-map-marker-alt"></i></a>';
                //} else {
                //$operate = '<a href=' . base_url('delivery_boy/orders/edit_orders') . '?edit_id=' . $row['id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View"><i class="fa fa-eye"></i></a>';

                //}
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    public function get_order_account_list_filter($user_id = '', $status = array())
    {
        $search_field = '';
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $multipleWhere = '';

        if (isset($_GET['search_field'])) {
            $search_field = $_GET['search_field'];
        }

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $filters = [
                'us.username' => $search,
                'sd.company_name' => $search,
                'us.email' => $search,
                'o.id' => $search,
                'o.mobile' => $search,
                'o.address' => $search,
                'o.wallet_balance' => $search,
                'o.total' => $search,
                'o.final_total' => $search,
                'o.total_payable' => $search,
                'o.payment_method' => $search,
                //'o.delivery_charge' => $search,
                //'o.delivery_time' => $search,
                //'o.status' => $search,
                'o.order_status' => $search,
                'o.date_added' => $search
            ];
        }

        if ($search_field != '') {
            $order_id_search = trim(preg_replace('/[^0-9]/', '', $search_field));

            $filters = [
                'sd.company_name' => trim($search_field),
            ];

            if ($order_id_search != '') {
                $filters = [
                    'o.id' => $order_id_search
                ];
            }
        }

        $count_res = $this->db->distinct()->select('o.id')
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
            ->join('order_item_payment_confirmation as op', 'o.id = op.order_id', 'left')
            ->join('order_item_invoice as inv', 'o.id = inv.order_id', 'left')
            ->join('order_item_stages as stg', 'o.id = stg.order_id', 'left');
        //->join('users db ', ' db.id = oi.delivery_boy_id', 'left');
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $count_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }



        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }



        if (isset($seller_id)) {
            $count_res->where("oi.seller_id", $seller_id);
        }

        if (isset($user_id) && $user_id != null) {
            $count_res->where("o.user_id", $user_id);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            if ($_GET['order_status'] == 'delivered') {
                $count_res->where(" (o.order_status = 'delivered' OR o.order_status = 'send_mfg_payment_ack' OR o.order_status ='send_mfg_payment_confirmation') ");
                $count_res->where_not_in("o.id", $issue_order_ids);
            } else if ($_GET['order_status'] == 'issue_closed') {
                $count_res->where(" (o.order_status = 'delivered' OR o.order_status = 'send_mfg_payment_ack' OR o.order_status ='send_mfg_payment_confirmation') ");
                $count_res->where_in("o.id", $issue_order_ids);
            } else {
                $count_res->where('stg.status', $_GET['order_status']);
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $count_res->where_in('oi.active_status', $status);
        }

        $count_res->where('o.is_service_category', 0);

        $count_res->group_by('o.id');

        $product_count = $count_res->get('`orders` o')->result_array();

        //foreach ($product_count as $row) {
        $total = count($product_count); //$row['total'];
        //}

        $search_res = $this->db->select(' o.* ,stg.status, u.username, sd.company_name, sd.slug as seller_slug, op.attachments as retailer_pay_confirm, inv.attachments as retailer_invoice') //, db.username as delivery_boy
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
            ->join('order_item_payment_confirmation as op', 'o.id = op.order_id', 'left')
            ->join('order_item_invoice as inv', 'o.id = inv.order_id', 'left')
            ->join('order_item_stages as stg', 'o.id = stg.order_id', 'left');

        //->join('users db ', ' db.id = oi.delivery_boy_id', 'left');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }


        if (isset($user_id) && !empty($user_id)) {
            $search_res->where("o.user_id", $user_id);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            if ($_GET['order_status'] == 'delivered') {
                $search_res->where(" (o.order_status = 'delivered' OR o.order_status = 'send_mfg_payment_ack' OR o.order_status ='send_mfg_payment_confirmation') ");
                $search_res->where_not_in("o.id", $issue_order_ids);
            } else if ($_GET['order_status'] == 'issue_closed') {
                $search_res->where(" (o.order_status = 'delivered' OR o.order_status = 'send_mfg_payment_ack' OR o.order_status ='send_mfg_payment_confirmation') ");
                $search_res->where_in("o.id", $issue_order_ids);
            } else {
                $search_res->where('stg.status', $_GET['order_status']);
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $search_res->where_in('oi.active_status', $status);
        }

        $search_res->where('o.is_service_category', 0);

        $user_details = $search_res->group_by('o.id')->order_by($sort, $order)->limit($limit, $offset)->get('`orders` o')->result_array();

        //echo $this->db->last_query();die;

        $i = 0;
        foreach ($user_details as $row) {
            $user_details[$i]['items'] = $this->db->select('oi.*,p.name as name,p.id as product_id, u.username as uname, us.username as seller, sd.company_name ')
                ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
                ->join('products p ', ' p.id = v.product_id ', 'left')
                ->join('users u ', ' u.id = oi.user_id', 'left')
                ->join('users us ', ' us.id = oi.seller_id', 'left')
                ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
                ->where('oi.order_id', $row['id'])
                ->get(' `order_items` oi  ')->result_array();
            ++$i;
        }

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $tota_amount = 0;
        $final_tota_amount = 0;
        $currency_symbol = get_settings('currency');
        $or_state = array('delivered', 'cancelled');

        $order_msg = array('received' => 'Order Placed', 'qty_update' => 'Quantity updated and approval request received.', 'qty_approved' => 'Quantity approval accepted by you.', 'payment_demand' => 'Payment request received.', 'payment_ack' => 'Transaction details shared.', 'schedule_delivery' => 'Order Scheduled.', 'shipped' => 'Order shipped.', 'send_invoice' => 'Order is in transit, E-way bill and invoices received from manufacturer.', 'delivered' => 'Order delivered.', 'cancelled' => 'Order cancelled.', 'complaint' => 'Issue Raised', 'send_payment_confirmation' => 'Payment receipt received', 'send_mfg_payment_ack' => 'Order delivered.', 'send_mfg_payment_confirmation' => 'Order delivered.', 'complaint_msg' => 'Issue details shared by Happycrop.', 'service_completed' => 'Service Completed');

        //$sr_no = 1;
        foreach ($user_details as $row) {
            if (!empty($row['items'])) {
                $items = $row['items'];
                $items1 = '';
                $temp = '';
                $total_amt = 0;
                //$seller = implode(",", array_values(array_unique(array_column($items, "seller"))));
                $company_name = implode(",", array_values(array_unique(array_column($items, "company_name"))));

                foreach ($items as $item) {
                    $product_variants = get_variants_values_by_id($item['product_variant_id']);
                    $variants = isset($product_variants[0]['variant_values']) && !empty($product_variants[0]['variant_values']) ? str_replace(',', ' | ', $product_variants[0]['variant_values']) : '-';
                    $temp .= "<b>ID :</b>" . $item['id'] . "<b> Product Variant Id :</b> " . $item['product_variant_id'] . "<b> Variants :</b> " . $variants . "<b> Name : </b>" . $item['name'] . " <b>Price : </b>" . $item['price'] . " <b>QTY : </b>" . $item['quantity'] . " <b>Subtotal : </b>" . $item['quantity'] * $item['price'] . "<br>------<br>";
                    $total_amt += $item['sub_total'];
                }

                $items1 = $temp;
                $discounted_amount = $row['total'] * $row['items'][0]['discount'] / 100;
                $final_total = $row['total'] - $discounted_amount;
                $discount_in_rupees = $row['total'] - $final_total;
                $discount_in_rupees = floor($discount_in_rupees);
                //$tempRow['sr_no']   = $offset + $sr_no;
                //$sr_no++;
                $tempRow['id'] = 'HC-A' . $row['id'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['name'] = $row['items'][0]['uname'];
                /*if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $tempRow['mobile'] = str_repeat("X", strlen($row['mobile']) - 3) . substr($row['mobile'], -3);
                } else {*/
                $tempRow['mobile'] = $row['mobile'];
                //}
                $tempRow['delivery_charge'] = $currency_symbol . ' ' . $row['delivery_charge'];
                $tempRow['items'] = $items1;
                $tempRow['sellers'] = '<a href="' . base_url('products?seller=' . $row['seller_slug']) . '">' . $company_name . '</a>'; //$seller;
                $tempRow['total'] = $currency_symbol . ' ' . $row['total'];
                $tota_amount += intval($row['total']);
                $tempRow['wallet_balance'] = $currency_symbol . ' ' . $row['wallet_balance'];
                $tempRow['discount'] = $currency_symbol . ' ' . $discount_in_rupees . '(' . $row['items'][0]['discount'] . '%)';
                $tempRow['promo_discount'] = $currency_symbol . ' ' . $row['promo_discount'];
                $tempRow['promo_code'] = $row['promo_code'];
                $tempRow['notes'] = $row['notes'];
                $tempRow['qty'] = $row['items'][0]['quantity'];
                $tempRow['final_total'] = $currency_symbol . ' ' . $row['total_payable'];
                $final_total = $row['final_total'] - $row['wallet_balance'] - $row['promo_discount'] - $row['discount'];
                $tempRow['final_total'] = $currency_symbol . ' ' . $final_total;
                $final_tota_amount += intval($row['final_total']);
                $tempRow['deliver_by'] = $row['delivery_boy'];
                $tempRow['payment_method'] = $row['payment_method'];
                $tempRow['billing_address'] = output_escaping(str_replace('\r\n', '</br>', $row['billing_address']));
                $tempRow['address'] = output_escaping(str_replace('\r\n', '</br>', $row['address']));
                $tempRow['delivery_date'] = $row['delivery_date'];
                $tempRow['delivery_time'] = $row['delivery_time'];
                $tempRow['date_added'] = date('d-m-Y', strtotime($row['date_added']));
                $tempRow['schedule_delivery_date'] = ($row['schedule_delivery_date'] != null && $row['schedule_delivery_date'] != '0000-00-00') ?  date('d-m-Y', strtotime($row['schedule_delivery_date'])) : '';

                $tempRow['last_updated']   = ($row['last_updated'] != null) ? date('d-m-Y', strtotime($row['last_updated'])) : '';


                // $tempRow['payment_receipt'] = (file_exists($row['retailer_pay_confirm']) && $row['retailer_pay_confirm']!='') ? '<a href="'.base_url($row['retailer_pay_confirm']).'" target="_blank">View / Download</a>' : '';
                // $tempRow['invoice_receipt'] = (file_exists($row['retailer_invoice']) && $row['retailer_invoice']!='') ? '<a href="'.base_url($row['retailer_invoice']).'" target="_blank">View / Download</a>' : '';

                $tempRow['payment_receipt'] = '<a href="' . base_url("my-account/payment-receipt/") . $row['id'] . "/view" . '" target="_blank">View</a>';
                $tempRow['invoice_receipt'] = '<a href="' . base_url("my-account/tax-invoice/") . $row['id'] . "/view" . '" target="_blank">View</a>';
                $tempRow['purchase_order'] = '<a href="' . base_url("my-account/purchase-invoice/") . $row['id'] . "/1" . '" target="_blank">View</a>';

                $this->db->select('id');
                $this->db->from('order_item_stages');
                $this->db->where('status', 'issue_resolved');
                $this->db->where('order_id', $row['id']);
                $q = $this->db->get();
                $rw = $q->row_array();

                if ($rw['id']) {
                    $order_msg['delivered'] = $order_msg['send_mfg_payment_ack'] = $order_msg['send_mfg_payment_confirmation'] = 'Order closed.';
                } else {
                    $order_msg['delivered'] = $order_msg['send_mfg_payment_ack'] = $order_msg['send_mfg_payment_confirmation'] = 'Order delivered.';
                }

                $tempRow['order_status'] = $order_msg[$row['order_status']];

                /*$tempRow['color_state'] = '';
                if($row['order_status'] == 'delivered')
                {
                    if($rw['id'])
                    {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    }
                    else
                    {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                }
                else if($row['order_status'] == 'service_completed')
                {
                    $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                }
                else if($row['order_status'] == 'send_mfg_payment_ack')
                {
                    if($rw['id'])
                    {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    }
                    else
                    {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                }
                else if($row['order_status'] == 'send_mfg_payment_confirmation')
                {
                    if($rw['id'])
                    {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    }
                    else
                    {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                }
                else if($row['order_status'] == 'cancelled')
                {
                    $tempRow['color_state'] = '<span class="cancelled-state"><i class="fa fa-circle"></i></span>';
                }
                else if($row['order_status'] == 'complaint')
                {
                    $tempRow['color_state'] = '<span class="issue-state"><i class="fa fa-circle"></i></span>';
                }
                else if($row['order_status'] == 'complaint_msg')
                {
                    $tempRow['color_state'] = '<span class="issue-state"><i class="fa fa-circle"></i></span>';
                }
                else
                {
                    $tempRow['color_state'] = '<span class="active-state"><i class="fa fa-circle"></i></span>';
                }
                */

                //$operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['id'] . '" class="btn btn-primary btn-xs mr-1 mb-1" title="View" ><i class="fa fa-eye"></i></a>';
                $operate = '';
                //if (!$this->ion_auth->is_delivery_boy()) {
                $operate = '<a href="' . base_url('my-account/order-details/') . $row['id'] . '" class="btn btn-primary btn-sm mr-1 mb-1" title="View" >View Details</a>';
                $operate .= '<a onclick="re_order_the_order(' . $row['id'] . ')" href="javascript:void(0);" class="btn btn-secondary btn-sm mr-1 mb-1" title="View" ><i class="fas fa-refresh"></i></a>';
                //$operate .= '<a href="javascript:void(0)" class="delete-orders btn btn-danger btn-xs mr-1 mb-1" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i></a>';
                //$operate .= '<a href="' . base_url() . 'admin/invoice?edit_id=' . $row['id'] . '" class="btn btn-info btn-xs mr-1 mb-1" title="Invoice" ><i class="fa fa-file"></i></a>';
                //$operate .= ' <a href="javascript:void(0)" class="edit_order_tracking btn btn-success btn-xs mr-1 mb-1" title="Order Tracking" data-order_id="' . $row['id'] . '"  data-target="#order-tracking-modal" data-toggle="modal"><i class="fa fa-map-marker-alt"></i></a>';
                //} else {
                //$operate = '<a href=' . base_url('delivery_boy/orders/edit_orders') . '?edit_id=' . $row['id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View"><i class="fa fa-eye"></i></a>';

                //}
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_order_list($user_id = '', $status = array())
    {

        $this->db->select('order_id');
        $this->db->from('order_item_stages');
        $this->db->where('status', 'issue_resolved');
        $q              = $this->db->get();
        $issue_orders   = $q->result_array();

        $issue_order_ids = array();
        if ($issue_orders) {
            $issue_order_ids = array_column($issue_orders, 'order_id');
        }

        $search_field = '';
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $multipleWhere = '';

        if (isset($_GET['search_field'])) {
            $search_field = $_GET['search_field'];
        }

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $filters = [
                'us.username' => $search,
                'sd.company_name' => $search,
                'us.email' => $search,
                'o.id' => $search,
                'o.mobile' => $search,
                'o.address' => $search,
                'o.wallet_balance' => $search,
                'o.total' => $search,
                'o.final_total' => $search,
                'o.total_payable' => $search,
                'o.payment_method' => $search,
                //'o.delivery_charge' => $search,
                //'o.delivery_time' => $search,
                //'o.status' => $search,
                'o.order_status' => $search,
                'o.date_added' => $search
            ];
        }

        if ($search_field != '') {
            $order_id_search = trim(preg_replace('/[^0-9]/', '', $search_field));

            $filters = [
                'sd.company_name' => trim($search_field),
            ];

            if ($order_id_search != '') {
                $filters = [
                    'o.id' => $order_id_search
                ];
            }
        }

        $count_res = $this->db->distinct()->select('o.id')
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left');
        //->join('users db ', ' db.id = oi.delivery_boy_id', 'left');
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $count_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }



        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }



        if (isset($seller_id)) {
            $count_res->where("oi.seller_id", $seller_id);
        }

        if (isset($user_id) && $user_id != null) {
            $count_res->where("o.user_id", $user_id);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            if ($_GET['order_status'] == 'delivered') {
                $count_res->where(" (o.order_status = 'delivered' OR o.order_status = 'send_mfg_payment_ack' OR o.order_status ='send_mfg_payment_confirmation') ");
                $count_res->where_not_in("o.id", $issue_order_ids);
            } else if ($_GET['order_status'] == 'issue_closed') {
                $count_res->where(" (o.order_status = 'delivered' OR o.order_status = 'send_mfg_payment_ack' OR o.order_status ='send_mfg_payment_confirmation') ");
                $count_res->where_in("o.id", $issue_order_ids);
            } else {
                $count_res->where('o.order_status', $_GET['order_status']);
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $count_res->where_in('oi.active_status', $status);
        }

        $count_res->group_by('o.id');

        $product_count = $count_res->get('`orders` o')->result_array();

        //foreach ($product_count as $row) {
        $total = count($product_count); //$row['total'];
        //}

        $search_res = $this->db->select(' o.* , u.username, sd.company_name, sd.slug as seller_slug') //, db.username as delivery_boy
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left');
        //->join('users db ', ' db.id = oi.delivery_boy_id', 'left');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }


        if (isset($user_id) && !empty($user_id)) {
            $search_res->where("o.user_id", $user_id);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            if ($_GET['order_status'] == 'delivered') {
                $search_res->where(" (o.order_status = 'delivered' OR o.order_status = 'send_mfg_payment_ack' OR o.order_status ='send_mfg_payment_confirmation') ");
                $search_res->where_not_in("o.id", $issue_order_ids);
            } else if ($_GET['order_status'] == 'issue_closed') {
                $search_res->where(" (o.order_status = 'delivered' OR o.order_status = 'send_mfg_payment_ack' OR o.order_status ='send_mfg_payment_confirmation') ");
                $search_res->where_in("o.id", $issue_order_ids);
            } else {
                $search_res->where('o.order_status', $_GET['order_status']);
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $search_res->where_in('oi.active_status', $status);
        }

        $user_details = $search_res->group_by('o.id')->order_by($sort, $order)->limit($limit, $offset)->get('`orders` o')->result_array();

        //echo $this->db->last_query();die;

        $i = 0;
        foreach ($user_details as $row) {
            $user_details[$i]['items'] = $this->db->select('oi.*,p.name as name,p.id as product_id, u.username as uname, us.username as seller, sd.company_name ')
                ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
                ->join('products p ', ' p.id = v.product_id ', 'left')
                ->join('users u ', ' u.id = oi.user_id', 'left')
                ->join('users us ', ' us.id = oi.seller_id', 'left')
                ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
                ->where('oi.order_id', $row['id'])
                ->get(' `order_items` oi  ')->result_array();
            ++$i;
        }

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $tota_amount = 0;
        $final_tota_amount = 0;
        $currency_symbol = get_settings('currency');
        $or_state = array('delivered', 'cancelled');

        $order_msg = array('received' => 'Order Placed', 'qty_update' => 'Quantity updated and approval request received.', 'qty_approved' => 'Quantity approval accepted by you.', 'payment_demand' => 'Payment request received.', 'payment_ack' => 'Transaction details shared.', 'schedule_delivery' => 'Order Scheduled.', 'shipped' => 'Order shipped.', 'send_invoice' => 'Order is in transit, E-way bill and invoices received from manufacturer.', 'delivered' => 'Order delivered.', 'cancelled' => 'Order cancelled.', 'complaint' => 'Issue Raised', 'send_payment_confirmation' => 'Payment receipt received', 'send_mfg_payment_ack' => 'Order delivered.', 'send_mfg_payment_confirmation' => 'Order delivered.', 'complaint_msg' => 'Issue details shared by Happycrop.', 'service_completed' => 'Service Completed');

        //$sr_no = 1;
        foreach ($user_details as $row) {
            if (!empty($row['items'])) {
                $items = $row['items'];
                $items1 = '';
                $temp = '';
                $total_amt = 0;
                //$seller = implode(",", array_values(array_unique(array_column($items, "seller"))));
                $company_name = implode(",", array_values(array_unique(array_column($items, "company_name"))));

                foreach ($items as $item) {
                    $product_variants = get_variants_values_by_id($item['product_variant_id']);
                    $variants = isset($product_variants[0]['variant_values']) && !empty($product_variants[0]['variant_values']) ? str_replace(',', ' | ', $product_variants[0]['variant_values']) : '-';
                    $temp .= "<b>ID :</b>" . $item['id'] . "<b> Product Variant Id :</b> " . $item['product_variant_id'] . "<b> Variants :</b> " . $variants . "<b> Name : </b>" . $item['name'] . " <b>Price : </b>" . $item['price'] . " <b>QTY : </b>" . $item['quantity'] . " <b>Subtotal : </b>" . $item['quantity'] * $item['price'] . "<br>------<br>";
                    $total_amt += $item['sub_total'];
                }

                $items1 = $temp;
                $discounted_amount = $row['total'] * $row['items'][0]['discount'] / 100;
                $final_total = $row['total'] - $discounted_amount;
                $discount_in_rupees = $row['total'] - $final_total;
                $discount_in_rupees = floor($discount_in_rupees);
                //$tempRow['sr_no']   = $offset + $sr_no;
                //$sr_no++;
                $tempRow['id'] = 'HC-A' . $row['id'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['name'] = $row['items'][0]['uname'];
                /*if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $tempRow['mobile'] = str_repeat("X", strlen($row['mobile']) - 3) . substr($row['mobile'], -3);
                } else {*/
                $tempRow['mobile'] = $row['mobile'];
                //}
                $tempRow['delivery_charge'] = $currency_symbol . ' ' . $row['delivery_charge'];
                $tempRow['items'] = $items1;
                $tempRow['sellers'] = '<a href="' . base_url('products?seller=' . $row['seller_slug']) . '">' . $company_name . '</a>'; //$seller;
                $tempRow['total'] = $currency_symbol . ' ' . $row['total'];
                $tota_amount += intval($row['total']);
                $tempRow['wallet_balance'] = $currency_symbol . ' ' . $row['wallet_balance'];
                $tempRow['discount'] = $currency_symbol . ' ' . $discount_in_rupees . '(' . $row['items'][0]['discount'] . '%)';
                $tempRow['promo_discount'] = $currency_symbol . ' ' . $row['promo_discount'];
                $tempRow['promo_code'] = $row['promo_code'];
                $tempRow['notes'] = $row['notes'];
                $tempRow['qty'] = $row['items'][0]['quantity'];
                $tempRow['final_total'] = $currency_symbol . ' ' . $row['total_payable'];
                $final_total = $row['final_total'] - $row['wallet_balance'] - $row['promo_discount'] - $row['discount'];
                $tempRow['final_total'] = $currency_symbol . ' ' . $final_total;
                $final_tota_amount += intval($row['final_total']);
                $tempRow['deliver_by'] = $row['delivery_boy'];
                $tempRow['payment_method'] = $row['payment_method'];
                $tempRow['billing_address'] = output_escaping(str_replace('\r\n', '</br>', $row['billing_address']));
                $tempRow['address'] = output_escaping(str_replace('\r\n', '</br>', $row['address']));
                $tempRow['delivery_date'] = $row['delivery_date'];
                $tempRow['delivery_time'] = $row['delivery_time'];
                $tempRow['date_added'] = date('d-m-Y', strtotime($row['date_added']));
                $tempRow['schedule_delivery_date'] = ($row['schedule_delivery_date'] != null && $row['schedule_delivery_date'] != '0000-00-00') ?  date('d-m-Y', strtotime($row['schedule_delivery_date'])) : '';

                $tempRow['last_updated']   = ($row['last_updated'] != null) ? date('d-m-Y', strtotime($row['last_updated'])) : '';


                $this->db->select('id');
                $this->db->from('order_item_stages');
                $this->db->where('status', 'issue_resolved');
                $this->db->where('order_id', $row['id']);
                $q = $this->db->get();
                $rw = $q->row_array();

                if ($rw['id']) {
                    $order_msg['delivered'] = $order_msg['send_mfg_payment_ack'] = $order_msg['send_mfg_payment_confirmation'] = 'Order closed.';
                } else {
                    $order_msg['delivered'] = $order_msg['send_mfg_payment_ack'] = $order_msg['send_mfg_payment_confirmation'] = 'Order delivered.';
                }

                $tempRow['order_status'] = $order_msg[$row['order_status']];
                $tempRow['color_state'] = '';
                if ($row['order_status'] == 'delivered') {
                    if ($rw['id']) {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    } else {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                } else if ($row['order_status'] == 'service_completed') {
                    $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                } else if ($row['order_status'] == 'send_mfg_payment_ack') {
                    if ($rw['id']) {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    } else {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                } else if ($row['order_status'] == 'send_mfg_payment_confirmation') {
                    if ($rw['id']) {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    } else {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                } else if ($row['order_status'] == 'cancelled') {
                    $tempRow['color_state'] = '<span class="cancelled-state"><i class="fa fa-circle"></i></span>';
                } else if ($row['order_status'] == 'complaint') {
                    $tempRow['color_state'] = '<span class="issue-state"><i class="fa fa-circle"></i></span>';
                } else if ($row['order_status'] == 'complaint_msg') {
                    $tempRow['color_state'] = '<span class="issue-state"><i class="fa fa-circle"></i></span>';
                } else {
                    $tempRow['color_state'] = '<span class="active-state"><i class="fa fa-circle"></i></span>';
                }
                //$operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['id'] . '" class="btn btn-primary btn-xs mr-1 mb-1" title="View" ><i class="fa fa-eye"></i></a>';
                $operate = '';
                //if (!$this->ion_auth->is_delivery_boy()) {
                $operate = '<a href="' . base_url('my-account/order-details/') . $row['id'] . '" class="btn btn-primary btn-sm mr-1 mb-1" title="View" >View Details</a>';
                $operate .= '<a onclick="re_order_the_order(' . $row['id'] . ')" href="javascript:void(0);" class="btn btn-secondary btn-sm mr-1 mb-1" title="View" ><i class="fas fa-refresh"></i></a>';
                //$operate .= '<a href="javascript:void(0)" class="delete-orders btn btn-danger btn-xs mr-1 mb-1" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i></a>';
                //$operate .= '<a href="' . base_url() . 'admin/invoice?edit_id=' . $row['id'] . '" class="btn btn-info btn-xs mr-1 mb-1" title="Invoice" ><i class="fa fa-file"></i></a>';
                //$operate .= ' <a href="javascript:void(0)" class="edit_order_tracking btn btn-success btn-xs mr-1 mb-1" title="Order Tracking" data-order_id="' . $row['id'] . '"  data-target="#order-tracking-modal" data-toggle="modal"><i class="fa fa-map-marker-alt"></i></a>';
                //} else {
                //$operate = '<a href=' . base_url('delivery_boy/orders/edit_orders') . '?edit_id=' . $row['id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View"><i class="fa fa-eye"></i></a>';

                //}
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
        }
        /*if (!empty($user_details)) {
            //$tempRow['sr_no'] = '-';
            $tempRow['id'] = '-';
            $tempRow['user_id'] = '-';
            $tempRow['name'] = '-';
            $tempRow['mobile'] = '-';
            $tempRow['delivery_charge'] = '-';
            $tempRow['items'] = '-';
            $tempRow['sellers'] = '-';
            $tempRow['total'] = '<span class="badge badge-danger">' . $currency_symbol . ' ' . $tota_amount . '</span>';
            $tempRow['wallet_balance'] = '-';
            $tempRow['discount'] = '-';
            $tempRow['qty'] = '-';
            $tempRow['final_total'] = '<span class="badge badge-danger">' . $currency_symbol . ' ' . $final_tota_amount . '</span>';
            $tempRow['deliver_by'] = '-';
            $tempRow['payment_method'] = '-';
            $tempRow['address'] = '-';
            $tempRow['billing_address'] = '-';
            $tempRow['delivery_time'] = '-';
            $tempRow['status'] = '-';
            $tempRow['active_status'] = '-';
            $tempRow['wallet_balance'] = '-';
            $tempRow['date_added'] = '-';
            $tempRow['operate'] = '-';
            $tempRow['schedule_delivery_date'] = '-';
            $tempRow['order_status'] = '-';
            $tempRow['last_updated'] = '-';
            $tempRow['color_state'] = '';
            array_push($rows, $tempRow);
        }*/
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function update_order($set, $where, $isjson = false, $table = 'order_items')
    {
        $set = escape_array($set);

        if ($isjson == true) {
            $field = array_keys($set); // active_status
            $current_status = $set[$field[0]]; //processed

            $res = fetch_details($where, $table, '*');
            $priority_status = [
                'received' => 0,
                'qty_update' => 1,
                'qty_approved' => 2,
                'qty_rejected' => 3,
                'payment_demand' => 4,
                'payment_ack' => 5,
                'processed' => 6,
                'shipped' => 7,
                'delivered' => 8,
                'cancelled' => 9,
                'returned' => 10,
            ];
            if (count($res) >= 1) {
                $i = 0;
                foreach ($res  as $row) {
                    $set = array();
                    $temp = array();
                    $active_status = array();
                    $active_status[$i] = json_decode($row['status'], 1);
                    $current_selected_status = end($active_status[$i]);
                    $temp = $active_status[$i];
                    $cnt = count($temp);
                    $currTime = date('Y-m-d H:i:s');
                    $min_value = (!empty($temp)) ? $priority_status[$current_selected_status[0]] : -1;
                    $max_value = $priority_status[$current_status];
                    if ($current_status == 'returned'  || $current_status == 'cancelled') {
                        $temp[$cnt] = [$current_status, $currTime];
                    } else {
                        foreach ($priority_status  as $key => $value) {
                            if ($value > $min_value && $value <= $max_value) {
                                $temp[$cnt] = [$key, $currTime];
                            }
                            ++$cnt;
                        }
                    }

                    $set = [$field[0] => json_encode(array_values($temp))];
                    $this->db->trans_start();
                    $this->db->set($set)->where(['id' => $row['id']])->update($table);
                    $this->db->trans_complete();

                    //echo "<pre>";
                    ///var_dump( $current_selected_status);

                    //echo " - ". $this->db->last_query();die;

                    $response = FALSE;
                    if ($this->db->trans_status() === TRUE) {
                        $response = TRUE;
                    }
                    /* give commission to the delivery boy if the order is delivered */
                    if ($current_status == 'delivered') {
                        $order = fetch_details($where, 'order_items', 'delivery_boy_id,order_id,sub_total');
                        $order_final_total = fetch_details('id=' . $order[0]['order_id'], 'orders', 'final_total');
                        if (!empty($order)) {
                            $delivery_boy_id = $order[0]['delivery_boy_id'];
                            if ($delivery_boy_id > 0) {
                                $delivery_boy = fetch_details("id=$delivery_boy_id", 'users', 'bonus');
                                $final_total = $order[0]['sub_total'];
                                $commission = 0;
                                $settings = get_settings('system_settings', true);
                                $commission = (isset($delivery_boy[0]['bonus']) && $delivery_boy[0]['bonus'] > 0) ? $delivery_boy[0]['bonus'] : $settings['delivery_boy_bonus_percentage'];
                                $commission = $final_total * ($commission / 100);
                                if ($commission > $final_total) {
                                    $commission = $final_total;
                                }
                                /* commission must be greater then zero to be credited into the account */
                                if ($commission > 0) {
                                    $this->load->model("transaction_model");
                                    $transaction_data = [
                                        'transaction_type' => "wallet",
                                        'user_id' => $delivery_boy_id,
                                        'order_id' => $row['id'],
                                        'type' => "credit",
                                        'txn_id' => "",
                                        'amount' => $commission,
                                        'status' => "success",
                                        'message' => "Order delivery bonus for order item ID: #" . $row['id'],
                                    ];
                                    $this->transaction_model->add_transaction($transaction_data);
                                    $this->load->model('customer_model');
                                    $this->customer_model->update_balance($commission, $delivery_boy_id, 'add');
                                }
                            }
                        }
                    }
                    ++$i;
                }
                return $response;
            }
        } else {
            $this->db->trans_start();
            if (isset($set['delivery_boy_id']) && !empty($set['delivery_boy_id'])) {
                $this->db->set($set)->where_in('id', $where)->update($table);
            } else {
                $this->db->set($set)->where($where)->update($table);
            }
            $this->db->trans_complete();
            $response = FALSE;
            if ($this->db->trans_status() === TRUE) {
                $response = TRUE;
            }
            return $response;
        }
    }

    public function update_order_item($id, $status, $return_request = 0)
    {
        if ($return_request == 0) {
            $res = validate_order_status($id, $status);
            if ($res['error']) {
                $response['error'] = (isset($res['return_request_flag'])) ? false : true;
                $response['message'] = $res['message'];
                $response['data'] = $res['data'];
                return $response;
            }
        }


        $order_item_details = fetch_details(['id' => $id], 'order_items', 'order_id');
        $order_details =  fetch_orders($order_item_details[0]['order_id']);
        if (!empty($order_details) && !empty($order_item_details)) {

            $order_details = $order_details['order_data'];
            $order_items_details = $order_details[0]['order_items'];
            $key = array_search($id, array_column($order_items_details, 'id'));
            $order_id = $order_details[0]['id'];
            $user_id = $order_details[0]['user_id'];
            $order_counter = $order_items_details[$key]['order_counter'];
            $order_cancel_counter = $order_items_details[$key]['order_cancel_counter'];
            $order_return_counter = $order_items_details[$key]['order_return_counter'];
            $user_res = fetch_details(['id' => $user_id], 'users', 'fcm_id');
            $fcm_ids = array();
            if (!empty($user_res[0]['fcm_id'])) {
                $fcm_ids[0][] = $user_res[0]['fcm_id'];
            }


            if ($this->update_order(['status' => $status], ['id' => $id], true, 'order_items')) {
                $this->order_model->update_order(['active_status' => $status], ['id' => $id], false, 'order_items');
            }

            $response['error'] = false;
            $response['message'] = 'Status Updated Successfully';
            $response['data'] = array();
            return $response;
        }
    }

    public function place_order($data)
    {
        $data = escape_array($data);

        $CI = &get_instance();
        $CI->load->model('Address_model');

        $response = array();
        $user = fetch_details(['id' => $data['user_id']], 'users');
        $product_variant_id = explode(',', $data['product_variant_id']);
        $quantity = explode(',', $data['quantity']);

        $check_current_stock_status = validate_stock($product_variant_id, $quantity);

        if (isset($check_current_stock_status['error']) && $check_current_stock_status['error'] == true) {
            return json_encode($check_current_stock_status);
        }

        /* Calculating Final Total */

        $total = 0;
        $product_variant = $this->db->select('pv.*,tax.percentage as tax_percentage,tax.title as tax_name,p.seller_id,p.name as product_name,p.is_prices_inclusive_tax')
            ->join('products p ', 'pv.product_id=p.id', 'left')
            ->join('categories c', 'p.category_id = c.id', 'left')
            ->join('`taxes` tax', 'tax.id = p.tax', 'LEFT')
            ->where_in('pv.id', $product_variant_id)->order_by('FIELD(pv.id,' . $data['product_variant_id'] . ')')->get('product_variants pv')->result_array();



        if (!empty($product_variant)) {

            $system_settings = get_settings('system_settings', true);
            $seller_ids = array_values(array_unique(array_column($product_variant, "seller_id")));

            /* check for single seller permission */
            if ($system_settings['is_single_seller_order'] == '1') {
                if (isset($seller_ids) && count($seller_ids) > 1) {
                    $response['error'] = true;
                    $response['message'] = 'Only one seller products are allow in one order.';
                    return $response;
                }
            }

            $delivery_charge = isset($data['delivery_charge']) && !empty($data['delivery_charge']) ? $data['delivery_charge'] : 0;
            $gross_total = 0;
            $cart_data = [];
            for ($i = 0; $i < count($product_variant); $i++) {
                $pv_price[$i] = ($product_variant[$i]['special_price'] > 0 && $product_variant[$i]['special_price'] != null) ? $product_variant[$i]['special_price'] : $product_variant[$i]['price'];
                $tax_percentage[$i] = (isset($product_variant[$i]['tax_percentage']) && intval($product_variant[$i]['tax_percentage']) > 0 && $product_variant[$i]['tax_percentage'] != null) ? $product_variant[$i]['tax_percentage'] : '0';
                if ((isset($product_variant[$i]['is_prices_inclusive_tax']) && $product_variant[$i]['is_prices_inclusive_tax'] == 0) || (!isset($product_variant[$i]['is_prices_inclusive_tax'])) && $tax_percentage[$i] > 0) {
                    $tax_amount[$i] = $pv_price[$i] * ($tax_percentage[$i] / 100);
                    $pv_price[$i] = $pv_price[$i] + $tax_amount[$i];
                }

                $pv_mrp[$i] = $product_variant[$i]['mrp'];
                $pv_special_price_per_item[$i] = $product_variant[$i]['special_price_per_item'];
                $pv_standard_price[$i] =  $product_variant[$i]['price'];
                $subtotal[$i] = ($pv_price[$i])  * $quantity[$i];
                $pro_name[$i] = $product_variant[$i]['product_name'];
                $variant_info = get_variants_values_by_id($product_variant[$i]['id']);
                $product_variant[$i]['variant_name'] = (isset($variant_info[0]['variant_values']) && !empty($variant_info[0]['variant_values'])) ? $variant_info[0]['variant_values'] : "";

                $tax_percentage[$i] = (!empty($product_variant[$i]['tax_percentage'])) ? $product_variant[$i]['tax_percentage'] : 0;
                if ($tax_percentage[$i] != NUll && $tax_percentage[$i] > 0) {
                    $tax_amount[$i] = round($subtotal[$i] *  $tax_percentage[$i] / 100, 2);
                } else {
                    $tax_amount[$i] = 0;
                    $tax_percentage[$i] = 0;
                }
                $gross_total += $subtotal[$i];
                $total += $subtotal[$i];
                $total = round($total, 2);
                $gross_total  = round($gross_total, 2);

                array_push($cart_data, array(
                    'name' => $pro_name[$i],
                    'tax_amount' => $tax_amount[$i],
                    'qty' => $quantity[$i],
                    'sub_total' => $subtotal[$i],
                ));
            }
            $system_settings = get_settings('system_settings', true);

            /* Calculating Promo Discount */
            if (isset($data['promo_code']) && !empty($data['promo_code'])) {

                $promo_code = validate_promo_code($data['promo_code'], $data['user_id'], $data['final_total']);

                if ($promo_code['error'] == false) {

                    if ($promo_code['data'][0]['discount_type'] == 'percentage') {
                        $promo_code_discount =  floatval($total  * $promo_code['data'][0]['discount'] / 100);
                    } else {
                        $promo_code_discount = $promo_code['data'][0]['discount'];
                        // $promo_code_discount = floatval($total - $promo_code['data'][0]['discount']);
                    }
                    if ($promo_code_discount <= $promo_code['data'][0]['max_discount_amount']) {
                        $total = floatval($total) - $promo_code_discount;
                    } else {
                        $total = floatval($total) - $promo_code['data'][0]['max_discount_amount'];
                        $promo_code_discount = $promo_code['data'][0]['max_discount_amount'];
                    }
                } else {
                    return $promo_code;
                }
            }

            $final_total = $total + $delivery_charge;
            $final_total = round($final_total, 2);

            /* Calculating Wallet Balance */
            $total_payable = $final_total;
            if ($data['is_wallet_used'] == '1' && $data['wallet_balance_used'] <= $final_total) {

                /* function update_wallet_balance($operation,$user_id,$amount,$message="Balance Debited") */
                $wallet_balance = update_wallet_balance('debit', $data['user_id'], $data['wallet_balance_used'], "Used against Order Placement");
                if ($wallet_balance['error'] == false) {
                    $total_payable -= $data['wallet_balance_used'];
                    $Wallet_used = true;
                } else {
                    $response['error'] = true;
                    $response['message'] = $wallet_balance['message'];
                    return $response;
                }
            } else {
                if ($data['is_wallet_used'] == 1) {
                    $response['error'] = true;
                    $response['message'] = 'Wallet Balance should not exceed the total amount';
                    return $response;
                }
            }

            $status = (isset($data['active_status'])) ? $data['active_status'] : 'received';
            $order_data = [
                'user_id' => $data['user_id'],
                'mobile' => $data['mobile'],
                'total' => $gross_total,
                'promo_discount' => (isset($promo_code_discount) && $promo_code_discount != NULL) ? $promo_code_discount : '0',
                'total_payable' => $total_payable,
                'delivery_charge' => $delivery_charge,
                'is_delivery_charge_returnable' => $data['is_delivery_charge_returnable'],
                'wallet_balance' => (isset($Wallet_used) && $Wallet_used == true) ? $data['wallet_balance_used'] : '0',
                'final_total' => $final_total,
                'discount' => '0',
                'payment_method' => $data['payment_method'],
                'promo_code' => (isset($data['promo_code'])) ? $data['promo_code'] : ' ',
                'order_status' => $status,
                'last_updated' => $data['last_updated'],
            ];
            if (isset($data['address_id']) && !empty($data['address_id'])) {
                $order_data['address_id'] = $data['address_id'];
            }

            if (isset($data['billing_address_id']) && !empty($data['billing_address_id'])) {
                $order_data['billing_address_id'] = $data['billing_address_id'];
            }

            if (isset($data['delivery_date']) && !empty($data['delivery_date']) && !empty($data['delivery_time']) && isset($data['delivery_time'])) {
                $order_data['delivery_date'] = date('Y-m-d', strtotime($data['delivery_date']));
                $order_data['delivery_time'] = $data['delivery_time'];
            }
            $address_data = $CI->address_model->get_address('', $data['address_id'], true);
            if (!empty($address_data)) {
                $order_data['latitude'] = $address_data[0]['latitude'];
                $order_data['longitude'] = $address_data[0]['longitude'];
                $order_data['address'] = (!empty($address_data[0]['address'])) ? $address_data[0]['address'] . ', ' : '';
                $order_data['address'] .= (!empty($address_data[0]['landmark'])) ? $address_data[0]['landmark'] . ', ' : '';
                $order_data['address'] .= (!empty($address_data[0]['area'])) ? $address_data[0]['area'] . ', ' : '';
                $order_data['address'] .= (!empty($address_data[0]['city'])) ? $address_data[0]['city'] . ', ' : '';
                $order_data['address'] .= (!empty($address_data[0]['state'])) ? $address_data[0]['state'] . ', ' : '';
                $order_data['address'] .= (!empty($address_data[0]['country'])) ? $address_data[0]['country'] . ', ' : '';
                $order_data['address'] .= (!empty($address_data[0]['pincode'])) ? $address_data[0]['pincode'] : '';


                $update_default_0 = array('is_default' => 0);
                $this->db->update('addresses', $update_default_0, array('user_id' => $address_data[0]['user_id']));

                $update_default_1 = array('is_default' => 1);
                $this->db->update('addresses', $update_default_1, array('id' => $address_data[0]['id']));
            }

            $billing_address_data = $CI->address_model->get_address('', $data['billing_address_id'], true);
            if (!empty($address_data)) {
                $order_data['billing_address'] = (!empty($billing_address_data[0]['address'])) ? $billing_address_data[0]['address'] . ', ' : '';
                $order_data['billing_address'] .= (!empty($billing_address_data[0]['landmark'])) ? $billing_address_data[0]['landmark'] . ', ' : '';
                $order_data['billing_address'] .= (!empty($billing_address_data[0]['area'])) ? $billing_address_data[0]['area'] . ', ' : '';
                $order_data['billing_address'] .= (!empty($billing_address_data[0]['city'])) ? $billing_address_data[0]['city'] . ', ' : '';
                $order_data['billing_address'] .= (!empty($billing_address_data[0]['state'])) ? $billing_address_data[0]['state'] . ', ' : '';
                $order_data['billing_address'] .= (!empty($billing_address_data[0]['country'])) ? $billing_address_data[0]['country'] . ', ' : '';
                $order_data['billing_address'] .= (!empty($billing_address_data[0]['pincode'])) ? $billing_address_data[0]['pincode'] : '';
            }

            $order_data['mobile'] = (!empty($address_data[0]['mobile'])) ? $address_data[0]['mobile'] : $data['mobile'];
            if (!empty($_POST['latitude']) && !empty($_POST['longitude'])) {
                $order_data['latitude'] = $_POST['latitude'];
                $order_data['longitude'] = $_POST['longitude'];
            }
            $order_data['notes'] = $data['order_note'];
            $order_data['is_service_category'] = $data['is_service_category'];
            $this->db->insert('orders', $order_data);
            $last_order_id = $this->db->insert_id();

            for ($i = 0; $i < count($product_variant); $i++) {
                $otp = mt_rand(100000, 999999);
                $product_variant_data[$i] = [
                    'user_id' => $data['user_id'],
                    'order_id' => $last_order_id,
                    'seller_id' => $product_variant[$i]['seller_id'],
                    'product_name' => $product_variant[$i]['product_name'],
                    'variant_name' => $product_variant[$i]['variant_name'],
                    'product_variant_id' => $product_variant[$i]['id'],
                    'packing_size' => $product_variant[$i]['packing_size'],
                    'unit_id' => $product_variant[$i]['unit_id'],
                    'carton_qty' => $product_variant[$i]['carton_qty'],
                    'total_weight' => $product_variant[$i]['total_weight'],
                    'quantity' => $quantity[$i],
                    'mrp' => $pv_mrp[$i],
                    'special_price_per_item' => $pv_special_price_per_item[$i],
                    'standard_price' => $pv_standard_price[$i],
                    'price' => $pv_price[$i],
                    'tax_percent' => $tax_percentage[$i],
                    'tax_amount' => 0,
                    'sub_total' => $subtotal[$i],
                    'status' =>  json_encode(array(array($status, date("d-m-Y h:i:sa")))),
                    'active_status' => $status,
                    'otp' => ($system_settings['is_delivery_boy_otp_setting_on'] == '1') ? $otp : 0
                ];

                $this->db->insert('order_items', $product_variant_data[$i]);
                $last_order_item_id = $this->db->insert_id();

                $order_item_stages = array('order_id' => $last_order_id, 'order_item_id' => $last_order_item_id, 'status' => $status,);
                $this->db->insert('order_item_stages', $order_item_stages);
            }
            $product_variant_ids = explode(',', $data['product_variant_id']);

            $qtns = explode(',', $data['quantity']);
            update_stock($product_variant_ids, $qtns);

            $overall_total = array(
                'total_amount' => array_sum($subtotal),
                'delivery_charge' => $delivery_charge,
                'tax_amount' => array_sum($tax_amount),
                'tax_percentage' => array_sum($tax_percentage),
                'discount' =>  $order_data['promo_discount'],
                'wallet' =>  $order_data['wallet_balance'],
                'final_total' =>  $order_data['final_total'],
                'total_payable' =>  $order_data['total_payable'],
                'otp' => $otp,
                'address' => (isset($order_data['address'])) ? $order_data['address'] : '',
                'payment_method' => $data['payment_method']
            );
            /*if (trim(strtolower($data['payment_method'])) != 'paypal' || trim(strtolower($data['payment_method'])) != 'stripe') {
                $overall_order_data = array(
                    'cart_data' => $cart_data,
                    'order_data' => $overall_total,
                    'subject' => 'Order received successfully',
                    'user_data' => $user[0],
                    'system_settings' => $system_settings,
                    'user_msg' => 'Hello, Dear ' . ucfirst($user[0]['username']) . ', We have received your order successfully. Your order summaries are as followed',
                    'otp_msg' => 'Here is your OTP. Please, give it to delivery boy only while getting your order.',
                );
                if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 1) {
                    $system_settings = get_settings('system_settings', true);
                    if (isset($system_settings['support_email']) && !empty($system_settings['support_email'])) {
                        send_mail($system_settings['support_email'], 'New order placed ID #' . $last_order_id, 'New order received for ' . $system_settings['app_name'] . ' please process it.');
                    }
                    for ($i = 0; $i < count($seller_ids); $i++) {
                        $seller_email = fetch_details(['id' => $seller_ids[$i]], 'users', 'email');
                        $seller_store_name = fetch_details(['user_id' => $seller_ids[$i]], 'seller_data', 'store_name');
                        send_mail($seller_email[0]['email'], 'New order placed ID #' . $last_order_id, 'New order received for ' . $seller_store_name[0]['store_name'] . ' please process it.');
                    }
                }

                send_mail($user[0]['email'], 'Order received successfully', $this->load->view('admin/pages/view/email-template.php', $overall_order_data, TRUE));
            }*/

            //send_mail2($user[0]['email'], 'Order received successfully', $this->load->view('admin/pages/view/email-template.php', $overall_order_data, TRUE));

            $retailer_store_name = fetch_details(['user_id' => $data['user_id']], 'retailer_data', 'company_name');
            $retailer_store_name = $retailer_store_name[0]['company_name'];

            $system_settings = get_settings('system_settings', true);

            $html_text = '<p>Hello ' . ucfirst($retailer_store_name) . ',</p>';

            $html_text .= '<p>Thank you for choosing Happycrop! We are pleased to inform you that your order has been successfully placed and waiting for updates from seller.</p>';

            $html_text .= '<p style="margin-top: 0px;">Below are the details of your order:</p>';

            $order = fetch_orders($last_order_id, $data['user_id'], false, false, 1, NULL, NULL, NULL, NULL);

            $overall_order_data = array(
                'order'      => $order['order_data'][0],
                //'order_data' => $overall_total,
                'subject'    => 'Order #HC-A' . $last_order_id . ' placed successfully',
                'user_data'  => $user[0],
                'system_settings' => $system_settings,
                'user_msg'   => $html_text,
                //'otp_msg'    => 'Here is your OTP. Please, give it to delivery boy only while getting your order.',
            );
            send_mail2($user[0]['email'], 'Happycrop Order Updates - Order #HC-A' . $last_order_id, $this->load->view('admin/pages/view/order-email-template.php', $overall_order_data, TRUE));

            if (isset($system_settings['support_email']) && !empty($system_settings['support_email'])) {

                $html_text = '<p>Hello Admin,</p>';

                $html_text .= '<p>Welcome! New order placed by ' . ucfirst($retailer_store_name) . '. Please check below order details.</p>';

                $note = '<p><b>Note - Action needs to be taken within 5 days from order otherwise the order will be auto cancelled.</b></p>';

                //send_mail($system_settings['support_email'], 'New order placed ID #' . $last_order_id, 'New order received for ' . $system_settings['app_name'] . ' please process it.');
                $admin_order_info = array(
                    'order'      => $order['order_data'][0],
                    //'order_data' => $overall_total,
                    'subject'    => 'Order #HC-A' . $last_order_id . ' New order received',
                    //'user_data'  => $user[0],
                    'system_settings' => $system_settings,
                    'user_msg'   => $html_text,
                    //'otp_msg'    => 'Here is your OTP. Please, give it to delivery boy only while getting your order.',
                    'note' => $note,
                    'show_retailer_name' => true,
                    'show_retailer_gstn' => true,
                    'show_retailer_contact' => true,
                    'show_seller_info'   => true,
                );
                send_mail2($system_settings['support_email'], 'Happycrop Order Updates - Order #HC-A' . $last_order_id, $this->load->view('admin/pages/view/order-email-template.php', $admin_order_info, TRUE));
            }

            $seller_email = fetch_details(['id' => $order['order_data'][0]['seller_id']], 'users', 'email');
            $seller_store_name = fetch_details(['user_id' => $order['order_data'][0]['seller_id']], 'seller_data', 'company_name');

            if ($seller_email[0]["email"] != '') {
                ob_start();
?>
                <p>Hello <?php echo ucfirst($seller_store_name[0]['company_name']); ?>,</p>

                <p style="margin-left : 0px;margin-top : 0px;margin-bottom : 0px">Welcome to Happycrop! We are pleased to inform you that you have received the order from <?php echo ucfirst($retailer_store_name); ?> and waiting for updates from you </p>
                <p style="margin-left : 40px;margin-top : 0px;margin-bottom : 0px">Kindly, 1. Update the quantity if </p>
                <p style="margin-left : 80px;margin-top : 0px;margin-bottom : 0px">2. Schedule the delivery date </p>
                <p style="margin-left : 80px;margin-top : 0px;margin-bottom : 0px">3. Send payment request </p>
                <p style="margin-left : 0px;margin-top : 0px;margin-bottom : 0px">Below are the details of the order you received:</p>
<?php

                $html_text = ob_get_contents();
                ob_end_clean();

                $note = '<p><b>Note - Action needs to be taken within 5 days from order otherwise the order will be auto cancelled.</b></p>';

                //send_mail($seller_email[0]['email'], 'New order placed ID #' . $last_order_id, 'New order received for ' . $seller_store_name[0]['store_name'] . ' please process it.');
                $seller_order_info = array(
                    'order'      => $order['order_data'][0],
                    //'order_data' => $overall_total,
                    'subject'    => 'Order #HC-A' . $last_order_id . ' New order received',
                    //'user_data'  => $user[0],
                    'system_settings' => $system_settings,
                    'user_msg'   => $html_text,
                    'note' => $note,
                    'show_retailer_name' => true,
                    'show_retailer_gstn' => true,
                    'show_retailer_contact' => false,
                    'show_seller_info'   => false,
                    //'otp_msg'    => 'Here is your OTP. Please, give it to delivery boy only while getting your order.',

                );
                send_mail2($seller_email[0]["email"], 'Happycrop Order Updates - Order #HC-A' . $last_order_id, $this->load->view('admin/pages/view/order-email-template.php', $seller_order_info, TRUE));
            }

            /*$system_settings = get_settings('system_settings', true);
            
            $order = fetch_orders($last_order_id, $data['user_id'], false, false, 1, NULL, NULL, NULL, NULL);
            
            $overall_order_data = array(
                'order'      => $order['order_data'][0],
                //'order_data' => $overall_total,
                'subject'    => 'Order #HC-A' . $last_order_id.' received successfully',
                'user_data'  => $user[0],
                'system_settings' => $system_settings,
                'user_msg'   => 'Hello, Dear ' . ucfirst($user[0]['username']) . ', We have received your order #HC-A' . $last_order_id.' successfully. Your order summaries are as followed',
                //'otp_msg'    => 'Here is your OTP. Please, give it to delivery boy only while getting your order.',
            );
            send_mail2($user[0]['email'], 'Order received successfully', $this->load->view('admin/pages/view/order-email-template.php', $overall_order_data, TRUE));
            
            if (isset($system_settings['support_email']) && !empty($system_settings['support_email'])) {
                //send_mail($system_settings['support_email'], 'New order placed ID #' . $last_order_id, 'New order received for ' . $system_settings['app_name'] . ' please process it.');
                $seller_order_info = array(
                    'order'      => $order['order_data'][0],
                    //'order_data' => $overall_total,
                    'subject'    => 'New order #HC-A' . $last_order_id.' received',
                    //'user_data'  => $user[0],
                    'system_settings' => $system_settings,
                    'user_msg'   => 'Hello Admin, New order #HC-A' . $last_order_id.' received @ ' . $system_settings['app_name'] . ' so please check it. Order summaries are as followed',
                    //'otp_msg'    => 'Here is your OTP. Please, give it to delivery boy only while getting your order.',
                );
                send_mail2($system_settings['support_email'], 'New order #HC-A' . $last_order_id.' received', $this->load->view('admin/pages/view/order-email-template.php', $seller_order_info, TRUE));
            }
            
            $seller_email = fetch_details(['id' => $order['order_data'][0]['seller_id']], 'users', 'email');
            $seller_store_name = fetch_details(['user_id' => $order['order_data'][0]['seller_id']], 'seller_data', 'company_name');
            //send_mail($seller_email[0]['email'], 'New order placed ID #' . $last_order_id, 'New order received for ' . $seller_store_name[0]['store_name'] . ' please process it.');
            $seller_order_info = array(
                'order'      => $order['order_data'][0],
                //'order_data' => $overall_total,
                'subject'    => 'New order #HC-A' . $last_order_id.' received',
                //'user_data'  => $user[0],
                'system_settings' => $system_settings,
                'user_msg'   => 'Hello ' . ucfirst($seller_store_name[0]["company_name"]) . ', New order #HC-A' . $last_order_id.' received @ ' . $system_settings['app_name'] . ' so please check it. Order summaries are as followed',
                //'otp_msg'    => 'Here is your OTP. Please, give it to delivery boy only while getting your order.',
            );
            
            send_mail2($seller_email[0]["email"], 'New order #HC-A' . $last_order_id.' received', $this->load->view('admin/pages/view/order-email-template.php', $seller_order_info, TRUE));
            */

            $this->cart_model->remove_from_cart($data);


            $user_balance = fetch_details(['id' => $data['user_id']], 'users', 'balance');

            $response['error'] = false;
            $response['message'] = 'Order Placed Successfully';
            $response['order_id'] = $last_order_id;
            $response['order_item_data'] = $product_variant_data;
            $response['balance'] = $user_balance;
            return $response;
        } else {
            $user_balance = fetch_details(['id' => $data['user_id']], 'users', 'balance');

            $response['error'] = true;
            $response['message'] = "Product(s) Not Found!";
            $response['balance'] = $user_balance;
            return $response;
        }
    }

    public function get_order_details($where = NULL, $status = false, $seller_id = NULL)
    {
        $res = $this->db->select('oi.*, sd.company_name as seller_name, u.username, u.ret_no, r.company_name, r.gst_no as r_gst_no, r.fertilizer_license_no, r.pesticide_license_no, r.seeds_license_no, oi.mrp,ot.courier_agency,ot.tracking_id,ot.url,oi.otp as item_otp,a.name as user_name,oi.id as order_item_id,p.*,v.product_id,o.*,o.id as order_id,o.total as order_total,o.wallet_balance,oi.active_status as oi_active_status,u.email,u.username as uname,oi.status as order_status,p.name as pname,p.type,p.image as product_image,p.is_prices_inclusive_tax,(SELECT username FROM users db where db.id=oi.delivery_boy_id ) as delivery_boy, v.packing_size as pv_packing_size, uu.unit as pv_unit, v.carton_qty as pv_carton_qty, tax.percentage as tax_percentage, uuu.unit as unit ')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('users u ', ' u.id = oi.user_id', 'left')
            ->join('retailer_data r ', ' r.user_id = oi.user_id', 'left')
            ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
            ->join('orders o ', 'o.id=oi.order_id', 'left')
            ->join('order_tracking ot ', 'ot.order_item_id=oi.id', 'left')
            ->join('addresses a', 'a.id=o.address_id', 'left')
            //->join('unit_sizes usz', 'usz.id=p.unit_size_id', 'left')
            ->join('units uu', 'v.unit_id = uu.id', 'left')
            ->join('units uuu', 'oi.unit_id = uuu.id', 'left')
            ->join('`taxes` tax', 'tax.id = p.tax', 'LEFT');

        if (isset($where) && $where != NULL) {
            $res->where($where);
            if ($status == true) {
                $res->group_Start()
                    ->where_not_in(' `oi`.active_status ', array('cancelled', 'returned'))
                    ->group_End();
            }
        }
        if (!isset($where) && $status == true) {
            $res->where_not_in(' `oi`.active_status ', array('cancelled', 'returned'));
        }
        $order_result = $res->get(' `order_items` oi')->result_array();
        if (!empty($order_result)) {
            for ($i = 0; $i < count($order_result); $i++) {
                $order_result[$i] = output_escaping($order_result[$i]);
            }
        }
        //echo $this->db->last_query();die;
        //var_dump($order_result);die;
        return $order_result;
    }

    public function get_seller_orders_list(
        $seller_id = NULL,
        $offset = 0,
        $limit = 10,
        $sort = " o.id ",
        $order = 'DESC'
    ) {

        $this->db->select('order_id');
        $this->db->from('order_item_stages');
        $this->db->where('status', 'issue_resolved');
        $q              = $this->db->get();
        $issue_orders = $q->result_array();

        $issue_order_ids = array();
        if ($issue_orders) {
            $issue_order_ids = array_column($issue_orders, 'order_id');
        }

        $search_field = '';

        if (isset($_GET['search_field'])) {
            $search_field = $_GET['search_field'];
        }

        $condition = '';
        $status = false;

        if (isset($_GET['condition'])) {
            $condition = $_GET['condition'];

            if ($condition == 1) {
                $status = array('payment_demand', 'payment_ack', 'schedule_delivery', 'send_payment_confirmation',);
            } else if ($condition == 2) {
                $status = array('send_invoice');
            } else if ($condition == 3) {
                $status = array("complaint", "complaint_msg");
            } else if ($condition == 4) {
                $status = array("cancelled");
            } else if ($condition == 5) {
                $status = array("delivered", "send_mfg_payment_ack", "send_mfg_payment_confirmation");
            } else if ($condition == 6) {
                $status = array('received');
            } else if ($condition == 7) {
                $status = array('received', 'send_payment_confirmation', 'send_mfg_payment_ack');
            }
        }


        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $search = str_replace('HC-A', '', $search);

            $filters = [
                'u.username' => $search,
                //'db.username' => $search,
                'u.email' => $search,
                'o.id' => $search,
                'o.mobile' => $search,
                'o.address' => $search,
                'o.billing_address' => $search,
                'o.wallet_balance' => $search,
                'o.total' => $search,
                'o.final_total' => $search,
                'o.total_payable' => $search,
                'o.payment_method' => $search,
                'o.delivery_charge' => $search,
                'o.delivery_time' => $search,
                'o.order_status' => $search,
                //'o.active_status' => $search,
                'o.date_added' => $search,
                'rd.company_name' => $search,
            ];
        }

        if ($search_field != '') {
            $order_id_search = trim(preg_replace('/[^0-9]/', '', $search_field));

            $filters = [
                'rd.company_name' => trim($search_field),
            ];

            if ($order_id_search != '') {
                $filters = [
                    'o.id' => $order_id_search
                ];
            }
        }


        $count_res = $this->db->distinct()->select('o.id')
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('retailer_data rd ', ' rd.user_id = o.user_id', 'left')
            ->join('addresses ad ', ' ad.id = o.billing_address_id', 'left')
            ->join('cities ct ', ' ct.id = ad.city_id', 'left');
        //->join('users db ', ' db.id = oi.delivery_boy_id', 'left');
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $count_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }

        if (isset($seller_id)) {
            $count_res->where("oi.seller_id", $seller_id);
        }

        if (isset($_GET['user_id']) && $_GET['user_id'] != null) {
            $count_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            if ($_GET['order_status'] == 'issue_closed') {
                $count_res->where('o.order_status', 'delivered');
                //$count_res->where('o.order_status', 'send_mfg_payment_confirmation');
                if ($_GET['order_status'] == 'issue_closed') {
                    $count_res->where_in("o.id", $issue_order_ids);
                }
            } else {
                $count_res->where('o.order_status', $_GET['order_status']);
                if ($_GET['order_status'] == 'delivered') {
                    $count_res->where_not_in("o.id", $issue_order_ids);
                }
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $count_res->where_in('oi.active_status', $status);
        }

        $count_res->group_by('o.id');

        //var_dump($count_res, $filters);

        $product_count = $count_res->get('`orders` o')->result_array();
        //var_dump($count_res);die;

        //foreach ($product_count as $row) {
        $total = count($product_count); //$row['total'];
        //}

        $search_res = $this->db->select(' o.* , u.username, rd.company_name as retailer_name, ct.name as city_name') //, db.username as delivery_boy
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('retailer_data rd ', ' rd.user_id = o.user_id', 'left')
            ->join('addresses ad ', ' ad.id = o.billing_address_id', 'left')
            ->join('cities ct ', ' ct.id = ad.city_id', 'left');
        //->join('users db ', ' db.id = oi.delivery_boy_id', 'left');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }

        if (isset($seller_id)) {
            $search_res->where("oi.seller_id", $seller_id);
        }

        if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $search_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            if ($_GET['order_status'] == 'issue_closed') {
                $search_res->where('o.order_status', 'delivered');
                //$search_res->where('o.order_status', 'send_mfg_payment_confirmation'); 
                if ($_GET['order_status'] == 'issue_closed') {
                    $search_res->where_in("o.id", $issue_order_ids);
                }
            } else {
                $search_res->where('o.order_status', $_GET['order_status']);

                if ($_GET['order_status'] == 'delivered') {
                    $search_res->where_not_in("o.id", $issue_order_ids);
                }
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $search_res->where_in('oi.active_status', $status);
        }

        $user_details = $search_res->group_by('o.id')->order_by($sort, "DESC")->limit($limit, $offset)->get('`orders` o')->result_array();

        //echo $this->db->last_query();die;

        $i = 0;
        foreach ($user_details as $row) {
            $user_details[$i]['items'] = $this->db->select('oi.*,p.name as name,p.id as product_id, u.username as uname, us.username as seller, sd.company_name ')
                ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
                ->join('products p ', ' p.id = v.product_id ', 'left')
                ->join('users u ', ' u.id = oi.user_id', 'left')
                ->join('users us ', ' us.id = oi.seller_id', 'left')
                ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
                ->where('oi.order_id', $row['id'])
                ->get(' `order_items` oi  ')->result_array();
            ++$i;
        }

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $tota_amount = 0;
        $final_tota_amount = 0;
        $currency_symbol = get_settings('currency');
        $or_state = array('delivered', 'cancelled');

        $order_msg = array('received' => 'Order Received', 'qty_update' => 'Quantity updated and approval request sent.', 'qty_approved' => 'Quantity approval accepted by retailer.', 'payment_demand' => 'Payment request sent.', 'payment_ack' => 'Retailer shared transaction details with Happycrop.', 'send_payment_confirmation' => 'Payment confirmation sent to retailer by Happycrop.', 'schedule_delivery' => 'Order Scheduled.', 'shipped' => 'Order shipped.', 'send_invoice' => 'E-way bill and invoices sent to retailer.', 'complaint' => 'Retailer raised his concern.', 'delivered' => 'Your order delivered successfully.', 'cancelled' => 'Order cancelled.', 'send_mfg_payment_ack' => 'Transaction details received from Happycrop.', 'send_mfg_payment_confirmation' => 'Payment confirmation sent to Happycrop.', 'complaint_msg' => 'Issue details shared by Happycrop', 'service_completed' => 'Service Completed');

        //$sr_no = 1;
        foreach ($user_details as $row) {
            if (!empty($row['items'])) {
                $items = $row['items'];
                $items1 = '';
                $temp = '';
                $total_amt = 0;
                //$seller = implode(",", array_values(array_unique(array_column($items, "seller"))));
                $company_name = implode(",", array_values(array_unique(array_column($items, "company_name"))));

                foreach ($items as $item) {
                    $product_variants = get_variants_values_by_id($item['product_variant_id']);
                    $variants = isset($product_variants[0]['variant_values']) && !empty($product_variants[0]['variant_values']) ? str_replace(',', ' | ', $product_variants[0]['variant_values']) : '-';
                    $temp .= "<b>ID :</b>" . $item['id'] . "<b> Product Variant Id :</b> " . $item['product_variant_id'] . "<b> Variants :</b> " . $variants . "<b> Name : </b>" . $item['name'] . " <b>Price : </b>" . $item['price'] . " <b>QTY : </b>" . $item['quantity'] . " <b>Subtotal : </b>" . $item['quantity'] * $item['price'] . "<br>------<br>";
                    $total_amt += $item['sub_total'];
                }

                $items1 = $temp;
                $discounted_amount = $row['total'] * $row['items'][0]['discount'] / 100;
                $final_total = $row['total'] - $discounted_amount;
                $discount_in_rupees = $row['total'] - $final_total;
                $discount_in_rupees = floor($discount_in_rupees);
                //$tempRow['sr_no']   = $offset + $sr_no;
                //$sr_no++;
                $tempRow['id'] = 'HC-A' . $row['id'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['name'] = $row['retailer_name']; //$row['items'][0]['uname'];
                /*if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $tempRow['mobile'] = str_repeat("X", strlen($row['mobile']) - 3) . substr($row['mobile'], -3);
                } else {*/
                $tempRow['mobile'] = $row['mobile'];
                //}
                $tempRow['delivery_charge'] = $currency_symbol . ' ' . $row['delivery_charge'];
                $tempRow['items'] = $items1;
                $tempRow['sellers'] = $company_name; //$seller;
                $tempRow['total'] = $currency_symbol . ' ' . $row['total'];
                $tota_amount += intval($row['total']);
                $tempRow['wallet_balance'] = $currency_symbol . ' ' . $row['wallet_balance'];
                $tempRow['discount'] = $currency_symbol . ' ' . $discount_in_rupees . '(' . $row['items'][0]['discount'] . '%)';
                $tempRow['promo_discount'] = $currency_symbol . ' ' . $row['promo_discount'];
                $tempRow['promo_code'] = $row['promo_code'];
                $tempRow['notes'] = $row['notes'];
                $tempRow['qty'] = $row['items'][0]['quantity'];
                $tempRow['final_total'] = $currency_symbol . ' ' . $row['total_payable'];
                $final_total = $row['final_total'] - $row['wallet_balance'] - $row['promo_discount'] - $row['discount'];
                $tempRow['final_total'] = $currency_symbol . ' ' . $final_total;
                $final_tota_amount += intval($row['final_total']);
                $tempRow['deliver_by'] = $row['delivery_boy'];
                $tempRow['payment_method'] = $row['payment_method'];
                $tempRow['billing_address'] = output_escaping(str_replace('\r\n', '</br>', $row['billing_address']));
                $tempRow['address'] = output_escaping(str_replace('\r\n', '</br>', $row['address']));
                $tempRow['city_name'] = $row['city_name'];
                $tempRow['delivery_date'] = $row['delivery_date'];
                $tempRow['delivery_time'] = $row['delivery_time'];
                $tempRow['date_added'] = date('d-m-Y', strtotime($row['date_added']));
                $tempRow['schedule_delivery_date'] = ($row['schedule_delivery_date'] != null && $row['schedule_delivery_date'] != '0000-00-00') ?  date('d-m-Y', strtotime($row['schedule_delivery_date'])) : '';

                $this->db->select('id');
                $this->db->from('order_item_stages');
                $this->db->where('status', 'issue_resolved');
                $this->db->where('order_id', $row['id']);
                $q = $this->db->get();
                $rw = $q->row_array();

                if ($rw['id'] && $row['order_status'] == 'delivered') {
                    $order_msg['delivered'] = 'Issue resolved '; //'Order closed.';//$order_msg['send_mfg_payment_ack'] = $order_msg['send_mfg_payment_confirmation'] = 
                } else {
                    $order_msg['delivered'] = 'Order delivered.'; //$order_msg['send_mfg_payment_ack'] = $order_msg['send_mfg_payment_confirmation'] = 
                }

                $tempRow['order_status'] = $order_msg[$row['order_status']];
                $tempRow['color_state'] = '';
                if ($row['order_status'] == 'delivered') {
                    if ($rw['id']) {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    } else {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                } else if ($row['order_status'] == 'service_completed') {
                    $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                } else if ($row['order_status'] == 'send_mfg_payment_ack') {
                    if ($rw['id']) {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    } else {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                } else if ($row['order_status'] == 'send_mfg_payment_confirmation') {
                    if ($rw['id']) {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    } else {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                } else if ($row['order_status'] == 'cancelled') {
                    $tempRow['color_state'] = '<span class="cancelled-state"><i class="fa fa-circle"></i></span>';
                } else if ($row['order_status'] == 'complaint') {
                    $tempRow['color_state'] = '<span class="issue-state"><i class="fa fa-circle"></i></span>';
                } else if ($row['order_status'] == 'complaint_msg') {
                    $tempRow['color_state'] = '<span class="issue-state"><i class="fa fa-circle"></i></span>';
                } else {
                    $tempRow['color_state'] = '<span class="active-state"><i class="fa fa-circle"></i></span>';
                }

                $tempRow['last_updated']   = ($row['last_updated'] != null) ? date('d-m-Y', strtotime($row['last_updated'])) : '';

                //$operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['id'] . '" class="btn btn-primary btn-xs mr-1 mb-1" title="View" ><i class="fa fa-eye"></i></a>';
                $operate = '';
                if (!$this->ion_auth->is_delivery_boy()) {
                    $operate = '<a href=' . base_url('seller/orders/edit_orders') . '?edit_id=' . $row['id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View" >View Details</a>';
                    //$operate .= '<a href="javascript:void(0)" class="delete-orders btn btn-danger btn-xs mr-1 mb-1" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i></a>';
                    //$operate .= '<a href="' . base_url() . 'admin/invoice?edit_id=' . $row['id'] . '" class="btn btn-info btn-xs mr-1 mb-1" title="Invoice" ><i class="fa fa-file"></i></a>';
                    //$operate .= ' <a href="javascript:void(0)" class="edit_order_tracking btn btn-success btn-xs mr-1 mb-1" title="Order Tracking" data-order_id="' . $row['id'] . '"  data-target="#order-tracking-modal" data-toggle="modal"><i class="fa fa-map-marker-alt"></i></a>';
                } else {
                    //$operate = '<a href=' . base_url('delivery_boy/orders/edit_orders') . '?edit_id=' . $row['id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View"><i class="fa fa-eye"></i></a>';

                }
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
        }
        /*if (!empty($user_details)) {
            //$tempRow['sr_no'] = '-';
            $tempRow['id'] = '-';
            $tempRow['user_id'] = '-';
            $tempRow['name'] = '-';
            $tempRow['mobile'] = '-';
            $tempRow['delivery_charge'] = '-';
            $tempRow['items'] = '-';
            $tempRow['sellers'] = '-';
            $tempRow['total'] = '<span class="badge badge-danger">' . $currency_symbol . ' ' . $tota_amount . '</span>';
            $tempRow['wallet_balance'] = '-';
            $tempRow['discount'] = '-';
            $tempRow['qty'] = '-';
            $tempRow['final_total'] = '<span class="badge badge-danger">' . $currency_symbol . ' ' . $final_tota_amount . '</span>';
            $tempRow['deliver_by'] = '-';
            $tempRow['payment_method'] = '-';
            $tempRow['address'] = '-';
            $tempRow['billing_address'] = '-';
            $tempRow['delivery_time'] = '-';
            $tempRow['status'] = '-';
            $tempRow['active_status'] = '-';
            $tempRow['wallet_balance'] = '-';
            $tempRow['date_added'] = '-';
            $tempRow['operate'] = '-';
            $tempRow['schedule_delivery_date'] = '-';
            $tempRow['order_status'] = '-';
            $tempRow['last_updated'] = '-';
            $tempRow['city_name'] = '-';
            $tempRow['color_state'] = '-';
            array_push($rows, $tempRow);
        }*/
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_orders_list(
        $delivery_boy_id = NULL,
        $offset = 0,
        $limit = 10,
        $sort = " o.id ",
        $order = 'ASC'
    ) {
        $this->db->select('order_id');
        $this->db->from('order_item_stages');
        $this->db->where('status', 'issue_resolved');
        $q              = $this->db->get();
        $issue_orders   = $q->result_array();

        $issue_order_ids = array();
        if ($issue_orders) {
            $issue_order_ids = array_column($issue_orders, 'order_id');
        }

        $search_field = '';

        if (isset($_GET['search_field'])) {
            $search_field = $_GET['search_field'];
        }

        $condition = '';
        $status = false;

        if (isset($_GET['condition'])) {
            $condition = $_GET['condition'];

            if ($condition == 1) {
                $status = array('payment_demand', 'payment_ack', 'schedule_delivery', 'send_payment_confirmation',);
            } else if ($condition == 2) {
                $status = array('send_invoice');
            } else if ($condition == 3) {
                $status = array("complaint", "complaint_msg");
            } else if ($condition == 4) {
                $status = array("cancelled");
            } else if ($condition == 5) {
                $status = array("delivered", "send_mfg_payment_ack", "send_mfg_payment_confirmation");
            } else if ($condition == 6) {
                $status = array('received');
            } else if ($condition == 7) {
                $status = array('payment_ack', 'complaint', 'delivered');
            }
        }

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['seller_id'])) {
            $seller_id = $_GET['seller_id'];
        }

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $search = str_replace('HC-A', '', $search);

            $filters = [
                'u.username' => $search,
                //'db.username' => $search,
                'u.email' => $search,
                'o.id' => $search,
                'o.mobile' => $search,
                'o.address' => $search,
                'o.wallet_balance' => $search,
                'o.total' => $search,
                'o.final_total' => $search,
                'o.total_payable' => $search,
                'o.payment_method' => $search,
                'o.delivery_charge' => $search,
                'o.delivery_time' => $search,
                'o.order_status' => $search,
                //'o.status' => $search,
                //'o.active_status' => $search,
                'date_added' => $search,
                'rd.company_name' => $search,
            ];
        }

        if ($search_field != '') {
            $order_id_search = trim(preg_replace('/[^0-9]/', '', $search_field));

            $filters = [
                'rd.company_name' => trim($search_field),
                'sd.company_name' => trim($search_field),
            ];

            if ($order_id_search != '') {
                $filters = [
                    'o.id' => $order_id_search
                ];
            }
        }

        $count_res = $this->db->select(' COUNT(o.id) as `total` ')
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('retailer_data rd ', ' rd.user_id = o.user_id', 'left')
            ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
            ->join('addresses ad ', ' ad.id = o.billing_address_id', 'left')
            ->join('cities ct ', ' ct.id = ad.city_id', 'left');
        //->join('users db ', ' db.id = oi.delivery_boy_id', 'left');
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $count_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }

        if (isset($seller_id)) {
            $count_res->where("oi.seller_id", $seller_id);
        }

        /*if (isset($delivery_boy_id)) {
            $count_res->where("oi.delivery_boy_id", $delivery_boy_id);
        }*/

        if (isset($_GET['user_id']) && $_GET['user_id'] != null) {
            $count_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            if ($_GET['order_status'] == 'issue_closed') {
                $count_res->where('o.order_status', 'delivered');

                if ($_GET['order_status'] == 'issue_closed') {
                    $count_res->where_in("o.id", $issue_order_ids);
                }
            } else {
                $count_res->where('o.order_status', $_GET['order_status']);
                if ($_GET['order_status'] == 'delivered') {
                    $count_res->where_not_in("o.id", $issue_order_ids);
                }
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $count_res->where_in('oi.active_status', $status);
        }

        $count_res->group_by('o.id');

        $product_count = $count_res->get('`orders` o')->result_array();

        /*foreach ($product_count as $row) {
            $total = $row['total'];
        }*/

        $total = count($product_count);

        $search_res = $this->db->select(' o.* , u.username, rd.company_name as retailer_name, sd.company_name as mfg_name, sd.slug as seller_slug, ct.name as city_name')
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('retailer_data rd ', ' rd.user_id = o.user_id', 'left')
            ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
            ->join('addresses ad ', ' ad.id = o.billing_address_id', 'left')
            ->join('cities ct ', ' ct.id = ad.city_id', 'left');
        //->join('users db ', ' db.id = oi.delivery_boy_id', 'left');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }

        if (isset($seller_id)) {
            $search_res->where("oi.seller_id", $seller_id);
        }

        /*if (isset($delivery_boy_id)) {
            $search_res->where("oi.delivery_boy_id", $delivery_boy_id);
        }*/

        if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $search_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            if ($_GET['order_status'] == 'issue_closed') {
                $search_res->where('o.order_status', 'delivered');

                if ($_GET['order_status'] == 'issue_closed') {
                    $search_res->where_in("o.id", $issue_order_ids);
                }
            } else {
                $search_res->where('o.order_status', $_GET['order_status']);

                if ($_GET['order_status'] == 'delivered') {
                    $search_res->where_not_in("o.id", $issue_order_ids);
                }
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $search_res->where_in('oi.active_status', $status);
        }

        $user_details = $search_res->group_by('o.id')->order_by($sort, "DESC")->limit($limit, $offset)->get('`orders` o')->result_array();

        $i = 0;
        foreach ($user_details as $row) {
            $user_details[$i]['items'] = $this->db->select('oi.*,p.name as name,p.id as product_id, u.username as uname, us.username as seller, sd.company_name ')
                ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
                ->join('products p ', ' p.id = v.product_id ', 'left')
                ->join('users u ', ' u.id = oi.user_id', 'left')
                ->join('users us ', ' us.id = oi.seller_id', 'left')
                ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
                ->where('oi.order_id', $row['id'])
                ->get(' `order_items` oi  ')->result_array();
            ++$i;
        }

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $tota_amount = 0;
        $final_tota_amount = 0;
        $currency_symbol = get_settings('currency');

        //$order_msg = array('received'=>'Order Received','qty_update'=>'Quantity updated and approval request sent.','qty_approved'=>'Quantity approval accepted by retailer.','payment_demand'=>'Payment request sent.','payment_ack'=>'Transaction details Received.','schedule_delivery'=>'Order Scheduled.','shipped'=>'Order shipped.','send_invoice'=>'Invoices sent.','delivered'=>'Order Closed.','cancelled'=>'Order cancelled.');
        $order_msg = array('received' => 'Order Received', 'qty_update' => 'Quantity updated and approval request sent.', 'qty_approved' => 'Quantity approval accepted by retailer.', 'payment_demand' => 'Payment request sent.', 'payment_ack' => 'Transaction details received from retailer.', 'send_payment_confirmation' => 'Payment confirmation sent to retailer.', 'schedule_delivery' => 'Order Scheduled.', 'shipped' => 'Order shipped.', 'send_invoice' => 'E-way bill and invoices sent to retailer.', 'complaint' => 'Retailer raised his concern.', 'delivered' => 'Order delivered successfully.', 'cancelled' => 'Order cancelled.', 'send_mfg_payment_ack' => 'Transaction details shared with manufacturer.', 'send_mfg_payment_confirmation' => 'Payment receipt received.', 'complaint_msg' => 'Issue details shared by Happycrop', 'service_completed' => 'Service Completed');

        foreach ($user_details as $row) {
            if (!empty($row['items'])) {
                $items = $row['items'];
                $items1 = '';
                $temp = '';
                $total_amt = 0;
                //$seller = implode(",", array_values(array_unique(array_column($items, "seller"))));
                $company_name = implode(",", array_values(array_unique(array_column($items, "company_name"))));

                foreach ($items as $item) {
                    $product_variants = get_variants_values_by_id($item['product_variant_id']);
                    $variants = isset($product_variants[0]['variant_values']) && !empty($product_variants[0]['variant_values']) ? str_replace(',', ' | ', $product_variants[0]['variant_values']) : '-';
                    $temp .= "<b>ID :</b>" . $item['id'] . "<b> Product Variant Id :</b> " . $item['product_variant_id'] . "<b> Variants :</b> " . $variants . "<b> Name : </b>" . $item['name'] . " <b>Price : </b>" . $item['price'] . " <b>QTY : </b>" . $item['quantity'] . " <b>Subtotal : </b>" . $item['quantity'] * $item['price'] . "<br>------<br>";
                    $total_amt += $item['sub_total'];
                }

                $items1 = $temp;
                $discounted_amount = $row['total'] * $row['items'][0]['discount'] / 100;
                $final_total = $row['total'] - $discounted_amount;
                $discount_in_rupees = $row['total'] - $final_total;
                $discount_in_rupees = floor($discount_in_rupees);
                $tempRow['id'] = 'HC-A' . $row['id'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['name'] = $row['retailer_name']; //$row['items'][0]['uname'];
                /*if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $tempRow['mobile'] = str_repeat("X", strlen($row['mobile']) - 3) . substr($row['mobile'], -3);
                } else {*/
                $tempRow['mobile'] = $row['mobile'];
                //}
                $tempRow['delivery_charge'] = $currency_symbol . ' ' . $row['delivery_charge'];
                $tempRow['items'] = $items1;
                $tempRow['sellers'] = '<a href="' . base_url('products?seller=' . $row['seller_slug']) . '">' . $company_name . '</a>'; //$seller;
                $tempRow['total'] = $currency_symbol . ' ' . $row['total'];
                $tota_amount += intval($row['total']);
                $tempRow['wallet_balance'] = $currency_symbol . ' ' . $row['wallet_balance'];
                $tempRow['discount'] = $currency_symbol . ' ' . $discount_in_rupees . '(' . $row['items'][0]['discount'] . '%)';
                $tempRow['promo_discount'] = $currency_symbol . ' ' . $row['promo_discount'];
                $tempRow['promo_code'] = $row['promo_code'];
                $tempRow['notes'] = $row['notes'];
                $tempRow['qty'] = $row['items'][0]['quantity'];
                $tempRow['final_total'] = $currency_symbol . ' ' . $row['total_payable'];
                $final_total = $row['final_total'] - $row['wallet_balance'] - $row['promo_discount'] - $row['discount'];
                $tempRow['final_total'] = $currency_symbol . ' ' . $final_total;
                $final_tota_amount += intval($row['final_total']);
                $tempRow['deliver_by'] = $row['delivery_boy'];
                $tempRow['payment_method'] = $row['payment_method'];
                $tempRow['address'] = output_escaping(str_replace('\r\n', '</br>', $row['address']));
                $tempRow['city_name'] = $row['city_name'];
                $tempRow['delivery_date'] = $row['delivery_date'];
                $tempRow['delivery_time'] = $row['delivery_time'];
                $tempRow['date_added'] = date('d-m-Y', strtotime($row['date_added']));

                $tempRow['schedule_delivery_date'] = ($row['schedule_delivery_date'] != null && $row['schedule_delivery_date'] != '0000-00-00') ?  date('d-m-Y', strtotime($row['schedule_delivery_date'])) : '';

                $this->db->select('id');
                $this->db->from('order_item_stages');
                $this->db->where('status', 'issue_resolved');
                $this->db->where('order_id', $row['id']);
                $q = $this->db->get();
                $rw = $q->row_array();

                if ($rw['id'] && $row['order_status'] == 'delivered') {
                    $order_msg['delivered'] = 'Issue resolved. Make payment.'; //'Order closed.';//$order_msg['send_mfg_payment_ack'] = $order_msg['send_mfg_payment_confirmation'] = 
                } else {
                    $order_msg['delivered'] = 'Order delivered.'; //$order_msg['send_mfg_payment_ack'] = $order_msg['send_mfg_payment_confirmation'] = 
                }

                $tempRow['order_status'] = $order_msg[$row['order_status']];

                $tempRow['color_state'] = '';
                if ($row['order_status'] == 'delivered') {
                    if ($rw['id']) {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    } else {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                } else if ($row['order_status'] == 'service_completed') {
                    $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                } else if ($row['order_status'] == 'send_mfg_payment_ack') {
                    if ($rw['id']) {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    } else {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                } else if ($row['order_status'] == 'send_mfg_payment_confirmation') {
                    if ($rw['id']) {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    } else {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                } else if ($row['order_status'] == 'cancelled') {
                    $tempRow['color_state'] = '<span class="cancelled-state"><i class="fa fa-circle"></i></span>';
                } else if ($row['order_status'] == 'complaint') {
                    $tempRow['color_state'] = '<span class="issue-state"><i class="fa fa-circle"></i></span>';
                } else if ($row['order_status'] == 'complaint_msg') {
                    $tempRow['color_state'] = '<span class="issue-state"><i class="fa fa-circle"></i></span>';
                } else {
                    $tempRow['color_state'] = '<span class="active-state"><i class="fa fa-circle"></i></span>';
                }

                $tempRow['last_updated']   = ($row['last_updated'] != null) ? date('d-m-Y', strtotime($row['last_updated'])) : '';

                $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['id'] . '" class="btn btn-primary btn-xs mr-1 mb-1" title="View" >View Details</a>';
                if (!$this->ion_auth->is_delivery_boy()) {
                    $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View" >View Details</a>';
                    //$operate .= '<a href="javascript:void(0)" class="delete-orders btn btn-danger btn-xs mr-1 mb-1" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i></a>';
                    //$operate .= '<a href="' . base_url() . 'admin/invoice?edit_id=' . $row['id'] . '" class="btn btn-info btn-xs mr-1 mb-1" title="Invoice" ><i class="fa fa-file"></i></a>';
                    //$operate .= ' <a href="javascript:void(0)" class="edit_order_tracking btn btn-success btn-xs mr-1 mb-1" title="Order Tracking" data-order_id="' . $row['id'] . '"  data-target="#order-tracking-modal" data-toggle="modal"><i class="fa fa-map-marker-alt"></i></a>';
                } else {
                    $operate = '<a href=' . base_url('delivery_boy/orders/edit_orders') . '?edit_id=' . $row['id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View">View Details</a>';
                }
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
        }
        /*if (!empty($user_details)) {
            $tempRow['id'] = '-';
            $tempRow['user_id'] = '-';
            $tempRow['name'] = '-';
            $tempRow['mobile'] = '-';
            $tempRow['delivery_charge'] = '-';
            $tempRow['items'] = '-';
            $tempRow['sellers'] = '-';
            $tempRow['total'] = '<span class="badge badge-danger">' . $currency_symbol . ' ' . $tota_amount . '</span>';
            $tempRow['wallet_balance'] = '-';
            $tempRow['discount'] = '-';
            $tempRow['qty'] = '-';
            $tempRow['final_total'] = '<span class="badge badge-danger">' . $currency_symbol . ' ' . $final_tota_amount . '</span>';
            $tempRow['deliver_by'] = '-';
            $tempRow['payment_method'] = '-';
            $tempRow['address'] = '-';
            $tempRow['delivery_time'] = '-';
            $tempRow['status'] = '-';
            $tempRow['active_status'] = '-';
            $tempRow['wallet_balance'] = '-';
            $tempRow['date_added'] = '-';
            $tempRow['operate'] = '-';
            $tempRow['order_status'] = '-';
            $tempRow['last_updated'] = '-';
            $tempRow['city_name'] = '-';
            $tempRow['schedule_delivery_date'] = '-';
            $tempRow['color_state'] = '-';
            array_push($rows, $tempRow);
        }*/
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_order_items_list($delivery_boy_id = NULL, $offset = 0, $limit = 10, $sort = " o.id ", $order = 'ASC', $seller_id = NULL)
    {
        $customer_privacy = false;
        if (isset($seller_id) && $seller_id != "") {
            $customer_privacy = get_seller_permission($seller_id, 'customer_privacy');
        }

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $filters = [
                'un.username' => $search,
                'u.username' => $search,
                'us.username' => $search,
                'un.email' => $search,
                'oi.id' => $search,
                'o.mobile' => $search,
                'o.address' => $search,
                'oi.sub_total' => $search,
                'o.delivery_time' => $search,
                'oi.active_status' => $search,
                'date_added' => $search
            ];
        }

        $count_res = $this->db->select(' COUNT(o.id) as `total` ')
            ->join(' `users` u', 'u.id= oi.delivery_boy_id', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join(' `orders` o', 'o.id= oi.order_id')
            ->join('users un ', ' un.id = o.user_id', 'left');
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $count_res->where(" DATE(oi.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(oi.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }

        if (isset($delivery_boy_id)) {
            $count_res->where("oi.delivery_boy_id", $delivery_boy_id);
        }

        if (isset($seller_id) && $seller_id != "") {
            $count_res->where("oi.seller_id", $seller_id);
        }

        if (isset($_GET['user_id']) && $_GET['user_id'] != null) {
            $count_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            $count_res->where('oi.active_status', $_GET['order_status']);
        }

        $product_count = $count_res->get('order_items oi')->result_array();
        foreach ($product_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' o.id as order_id,oi.id as order_item_id,o.*,oi.*,ot.courier_agency,ot.tracking_id,ot.url, u.username as delivery_boy, un.username as username,us.username as seller_name')
            ->join('users u', 'u.id= oi.delivery_boy_id', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('order_tracking ot ', ' ot.order_item_id = oi.id', 'left')
            ->join('orders o', 'o.id= oi.order_id')
            ->join('users un ', ' un.id = o.user_id', 'left');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(oi.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(oi.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }

        if (isset($delivery_boy_id)) {
            $search_res->where("oi.delivery_boy_id", $delivery_boy_id);
        }

        if (isset($seller_id) && $seller_id != "") {
            $search_res->where("oi.seller_id", $seller_id);
        }

        if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $search_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            $search_res->where('oi.active_status', $_GET['order_status']);
        }

        $user_details = $search_res->order_by($sort, "DESC")->limit($limit, $offset)->get('order_items oi')->result_array();
        //echo $this->db->last_query();die;
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $tota_amount = 0;
        $final_tota_amount = 0;
        $currency_symbol = get_settings('currency');
        $count = 1;
        foreach ($user_details as $row) {
            $temp = '';
            if (!empty($row['items'][0]['order_status'])) {
                $status = json_decode($row['items'][0]['order_status'], 1);
                foreach ($status as $st) {
                    $temp .= @$st[0] . " : " . @$st[1] . "<br>------<br>";
                }
            }

            if (trim($row['active_status']) == 'awaiting') {
                $active_status = '<label class="badge badge-secondary">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'received') {
                $active_status = '<label class="badge badge-primary">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'qty_update') {
                $active_status = '<label class="badge badge-warning">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'qty_approved') {
                $active_status = '<label class="badge badge-success">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'processed') {
                $active_status = '<label class="badge badge-info">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'shipped') {
                $active_status = '<label class="badge badge-warning">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'delivered') {
                $active_status = '<label class="badge badge-success">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'returned' || $row['active_status'] == 'cancelled') {
                $active_status = '<label class="badge badge-danger">' . $row['active_status'] . '</label>';
            }

            $status = $temp;
            $tempRow['id'] = $count;
            $tempRow['order_id'] = $row['order_id'];
            $tempRow['order_item_id'] = $row['order_item_id'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['seller_id'] = $row['seller_id'];
            $tempRow['notes'] = (isset($row['notes']) && !empty($row['notes'])) ? $row['notes'] : "";
            $tempRow['username'] = $row['username'];
            $tempRow['seller_name'] = $row['seller_name'];
            $tempRow['is_credited'] = ($row['is_credited']) ? '<label class="badge badge-success">Credited</label>' : '<label class="badge badge-danger">Not Credited</label>';
            $tempRow['product_name'] = $row['product_name'];
            $tempRow['product_name'] .= (!empty($row['variant_name'])) ? '(' . $row['variant_name'] . ')' : "";
            if ((ALLOW_MODIFICATION == 0 && !defined(ALLOW_MODIFICATION)) || ($this->ion_auth->is_seller() && $customer_privacy == false)) {
                $tempRow['mobile'] = str_repeat("X", strlen($row['mobile']) - 3) . substr($row['mobile'], -3);
            } else {
                $tempRow['mobile'] = $row['mobile'];
            }
            $tempRow['sub_total'] = $currency_symbol . ' ' . $row['sub_total'];
            $tempRow['quantity'] = $row['quantity'];
            $final_tota_amount += intval($row['sub_total']);
            $tempRow['delivery_boy'] = $row['delivery_boy'];
            $tempRow['delivery_boy_id'] = $row['delivery_boy_id'];
            $tempRow['product_variant_id'] = $row['product_variant_id'];
            $tempRow['delivery_date'] = $row['delivery_date'];
            $tempRow['delivery_time'] = $row['delivery_time'];
            $tempRow['courier_agency'] = (isset($row['courier_agency']) && !empty($row['courier_agency'])) ?  $row['courier_agency'] : "";
            $tempRow['tracking_id'] = (isset($row['tracking_id']) && !empty($row['tracking_id'])) ? $row['tracking_id'] : "";
            $tempRow['url'] = (isset($row['url']) && !empty($row['url'])) ? $row['url'] : "";
            $tempRow['status'] = $status;
            $tempRow['active_status'] = $active_status;
            $tempRow['date_added'] = date('d-m-Y', strtotime($row['date_added']));
            $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['order_id'] . '" class="btn btn-primary btn-xs mr-1 mb-1" title="View" ><i class="fa fa-eye"></i></a>';
            if ($this->ion_auth->is_delivery_boy()) {
                $operate = '<a href=' . base_url('delivery_boy/orders/edit_orders') . '?edit_id=' . $row['order_id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View"><i class="fa fa-eye"></i></a>';
            } else if ($this->ion_auth->is_seller()) {
                $operate = '<a href=' . base_url('seller/orders/edit_orders') . '?edit_id=' . $row['order_id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View"><i class="fa fa-eye"></i></a>';
                //$operate .= '<a href="' . base_url() . 'seller/invoice?edit_id=' . $row['order_id'] . '" class="btn btn-info btn-xs mr-1 mb-1" title="Invoice" ><i class="fa fa-file"></i></a>';
                //$operate .= ' <a href="javascript:void(0)" class="edit_order_tracking btn btn-success btn-xs mr-1 mb-1" title="Order Tracking" data-order_id="' . $row['order_id'] . '" data-order_item_id="' . $row['order_item_id'] . '" data-courier_agency="' . $row['courier_agency'] . '"  data-tracking_id="' . $row['tracking_id'] . '" data-url="' . $row['url'] . '" data-target="#transaction_modal" data-toggle="modal"><i class="fa fa-map-marker-alt"></i></a>';
            } else if ($this->ion_auth->is_admin()) {
                $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['order_id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View" ><i class="fa fa-eye"></i></a>';
                $operate .= '<a href="javascript:void(0)" class="delete-order-items btn btn-danger btn-xs mr-1 mb-1" data-id=' . $row['order_item_id'] . ' title="Delete" ><i class="fa fa-trash"></i></a>';
                //$operate .= '<a href="' . base_url() . 'admin/invoice?edit_id=' . $row['order_id'] . '" class="btn btn-info btn-xs mr-1 mb-1" title="Invoice" ><i class="fa fa-file"></i></a>';
                //$operate .= ' <a href="javascript:void(0)" class="edit_order_tracking btn btn-success btn-xs mr-1 mb-1" title="Order Tracking" data-order_id="' . $row['order_id'] . '" data-order_item_id="' . $row['order_item_id'] . '" data-courier_agency="' . $row['courier_agency'] . '"  data-tracking_id="' . $row['tracking_id'] . '" data-url="' . $row['url'] . '" data-target="#transaction_modal" data-toggle="modal"><i class="fa fa-map-marker-alt"></i></a>';
            } else {
                $operate = "";
            }


            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }
        if (!empty($user_details)) {
            $tempRow['id'] = 1;
            $tempRow['order_id'] = '-';
            $tempRow['order_item_id'] = '-';
            $tempRow['user_id'] = '-';
            $tempRow['seller_id'] = '-';
            $tempRow['username'] = '-';
            $tempRow['seller_name'] = '-';
            $tempRow['is_credited'] = '-';
            $tempRow['mobile'] = '-';
            $tempRow['delivery_charge'] = '-';
            $tempRow['product_name'] = '-';
            $tempRow['sub_total'] = '<span class="badge badge-danger">' . $currency_symbol . ' ' . $final_tota_amount . '</span>';
            $tempRow['discount'] = '-';
            $tempRow['quantity'] = '-';
            $tempRow['delivery_boy'] = '-';
            $tempRow['delivery_time'] = '-';
            $tempRow['status'] = '-';
            $tempRow['active_status'] = '-';
            $tempRow['date_added'] = '-';
            $tempRow['operate'] = '-';
            array_push($rows, $tempRow);
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_recent_order_items_list($delivery_boy_id = NULL, $offset = 0, $limit = 10, $sort = " o.id ", $order = 'ASC', $seller_id = NULL)
    {
        $customer_privacy = false;
        if (isset($seller_id) && $seller_id != "") {
            $customer_privacy = get_seller_permission($seller_id, 'customer_privacy');
        }

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $filters = [
                'un.username' => $search,
                'u.username' => $search,
                'us.username' => $search,
                'un.email' => $search,
                'oi.id' => $search,
                'o.mobile' => $search,
                'o.address' => $search,
                'oi.sub_total' => $search,
                'o.delivery_time' => $search,
                'oi.active_status' => $search,
                'date_added' => $search
            ];
        }

        $count_res = $this->db->select(' COUNT(o.id) as `total` ')
            ->join(' `users` u', 'u.id= oi.delivery_boy_id', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join(' `orders` o', 'o.id= oi.order_id')
            ->join('users un ', ' un.id = o.user_id', 'left');
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $count_res->where(" DATE(oi.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(oi.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }

        if (isset($delivery_boy_id)) {
            $count_res->where("oi.delivery_boy_id", $delivery_boy_id);
        }

        if (isset($seller_id) && $seller_id != "") {
            $count_res->where("oi.seller_id", $seller_id);
        }

        if (isset($_GET['user_id']) && $_GET['user_id'] != null) {
            $count_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            $count_res->where('oi.active_status', $_GET['order_status']);
        }

        $product_count = $count_res->get('order_items oi')->result_array();
        foreach ($product_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' o.id as order_id,oi.id as order_item_id,o.*,oi.*,ot.courier_agency,ot.tracking_id,ot.url, u.username as delivery_boy, un.username as username,us.username as seller_name')
            ->join('users u', 'u.id= oi.delivery_boy_id', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('order_tracking ot ', ' ot.order_item_id = oi.id', 'left')
            ->join('orders o', 'o.id= oi.order_id')
            ->join('users un ', ' un.id = o.user_id', 'left');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(oi.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(oi.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }

        if (isset($delivery_boy_id)) {
            $search_res->where("oi.delivery_boy_id", $delivery_boy_id);
        }

        if (isset($seller_id) && $seller_id != "") {
            $search_res->where("oi.seller_id", $seller_id);
        }

        if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $search_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            $search_res->where('oi.active_status', $_GET['order_status']);
        }

        $user_details = $search_res->order_by($sort, "DESC")->limit($limit, $offset)->get('order_items oi')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $tota_amount = 0;
        $final_tota_amount = 0;
        $currency_symbol = get_settings('currency');
        $count = 1;
        foreach ($user_details as $row) {
            $temp = '';
            if (!empty($row['items'][0]['order_status'])) {
                $status = json_decode($row['items'][0]['order_status'], 1);
                foreach ($status as $st) {
                    $temp .= @$st[0] . " : " . @$st[1] . "<br>------<br>";
                }
            }

            if (trim($row['active_status']) == 'awaiting') {
                $active_status = '<label class="badge badge-secondary">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'received') {
                $active_status = '<label class="badge badge-primary">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'processed') {
                $active_status = '<label class="badge badge-info">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'shipped') {
                $active_status = '<label class="badge badge-warning">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'delivered') {
                $active_status = '<label class="badge badge-success">' . $row['active_status'] . '</label>';
            }
            if ($row['active_status'] == 'returned' || $row['active_status'] == 'cancelled') {
                $active_status = '<label class="badge badge-danger">' . $row['active_status'] . '</label>';
            }

            $status = $temp;
            $tempRow['id'] = $count;
            $tempRow['order_id'] = $row['order_id'];
            $tempRow['order_item_id'] = $row['order_item_id'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['seller_id'] = $row['seller_id'];
            $tempRow['notes'] = (isset($row['notes']) && !empty($row['notes'])) ? $row['notes'] : "";
            $tempRow['username'] = $row['username'];
            $tempRow['seller_name'] = $row['seller_name'];
            $tempRow['is_credited'] = ($row['is_credited']) ? '<label class="badge badge-success">Credited</label>' : '<label class="badge badge-danger">Not Credited</label>';
            $tempRow['product_name'] = $row['product_name'];
            $tempRow['product_name'] .= (!empty($row['variant_name'])) ? '(' . $row['variant_name'] . ')' : "";
            if ((ALLOW_MODIFICATION == 0 && !defined(ALLOW_MODIFICATION)) || ($this->ion_auth->is_seller() && $customer_privacy == false)) {
                $tempRow['mobile'] = str_repeat("X", strlen($row['mobile']) - 3) . substr($row['mobile'], -3);
            } else {
                $tempRow['mobile'] = $row['mobile'];
            }
            $tempRow['sub_total'] = $currency_symbol . ' ' . $row['sub_total'];
            $tempRow['quantity'] = $row['quantity'];
            $final_tota_amount += intval($row['sub_total']);
            $tempRow['delivery_boy'] = $row['delivery_boy'];
            $tempRow['delivery_boy_id'] = $row['delivery_boy_id'];
            $tempRow['product_variant_id'] = $row['product_variant_id'];
            $tempRow['delivery_date'] = $row['delivery_date'];
            $tempRow['delivery_time'] = $row['delivery_time'];
            $tempRow['courier_agency'] = (isset($row['courier_agency']) && !empty($row['courier_agency'])) ?  $row['courier_agency'] : "";
            $tempRow['tracking_id'] = (isset($row['tracking_id']) && !empty($row['tracking_id'])) ? $row['tracking_id'] : "";
            $tempRow['url'] = (isset($row['url']) && !empty($row['url'])) ? $row['url'] : "";
            $tempRow['status'] = $status;
            $tempRow['active_status'] = $active_status;
            $tempRow['date_added'] = date('d-m-Y', strtotime($row['date_added']));
            $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['order_id'] . '" class="btn btn-primary btn-xs mr-1 mb-1" title="View" ><i class="fa fa-eye"></i></a>';
            if ($this->ion_auth->is_delivery_boy()) {
                $operate = '<a href=' . base_url('delivery_boy/orders/edit_orders') . '?edit_id=' . $row['order_id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View"><i class="fa fa-eye"></i></a>';
            } else if ($this->ion_auth->is_seller()) {
                $operate = '<a href=' . base_url('seller/orders/edit_orders') . '?edit_id=' . $row['order_id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View"><i class="fa fa-eye"></i></a>';
                //$operate .= '<a href="' . base_url() . 'seller/invoice?edit_id=' . $row['order_id'] . '" class="btn btn-info btn-xs mr-1 mb-1" title="Invoice" ><i class="fa fa-file"></i></a>';
                //$operate .= ' <a href="javascript:void(0)" class="edit_order_tracking btn btn-success btn-xs mr-1 mb-1" title="Order Tracking" data-order_id="' . $row['order_id'] . '" data-order_item_id="' . $row['order_item_id'] . '" data-courier_agency="' . $row['courier_agency'] . '"  data-tracking_id="' . $row['tracking_id'] . '" data-url="' . $row['url'] . '" data-target="#transaction_modal" data-toggle="modal"><i class="fa fa-map-marker-alt"></i></a>';
            } else if ($this->ion_auth->is_admin()) {
                $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['order_id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View" ><i class="fa fa-eye"></i></a>';
                $operate .= '<a href="javascript:void(0)" class="delete-order-items btn btn-danger btn-xs mr-1 mb-1" data-id=' . $row['order_item_id'] . ' title="Delete" ><i class="fa fa-trash"></i></a>';
                //$operate .= '<a href="' . base_url() . 'admin/invoice?edit_id=' . $row['order_id'] . '" class="btn btn-info btn-xs mr-1 mb-1" title="Invoice" ><i class="fa fa-file"></i></a>';
                //$operate .= ' <a href="javascript:void(0)" class="edit_order_tracking btn btn-success btn-xs mr-1 mb-1" title="Order Tracking" data-order_id="' . $row['order_id'] . '" data-order_item_id="' . $row['order_item_id'] . '" data-courier_agency="' . $row['courier_agency'] . '"  data-tracking_id="' . $row['tracking_id'] . '" data-url="' . $row['url'] . '" data-target="#transaction_modal" data-toggle="modal"><i class="fa fa-map-marker-alt"></i></a>';
            } else {
                $operate = "";
            }


            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }
        if (!empty($user_details)) {
            $tempRow['id'] = 1;
            $tempRow['order_id'] = '-';
            $tempRow['order_item_id'] = '-';
            $tempRow['user_id'] = '-';
            $tempRow['seller_id'] = '-';
            $tempRow['username'] = '-';
            $tempRow['seller_name'] = '-';
            $tempRow['is_credited'] = '-';
            $tempRow['mobile'] = '-';
            $tempRow['delivery_charge'] = '-';
            $tempRow['product_name'] = '-';
            $tempRow['sub_total'] = '<span class="badge badge-danger">' . $currency_symbol . ' ' . $final_tota_amount . '</span>';
            $tempRow['discount'] = '-';
            $tempRow['quantity'] = '-';
            $tempRow['delivery_boy'] = '-';
            $tempRow['delivery_time'] = '-';
            $tempRow['status'] = '-';
            $tempRow['active_status'] = '-';
            $tempRow['date_added'] = '-';
            $tempRow['operate'] = '-';
            array_push($rows, $tempRow);
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }


    public function add_bank_transfer_proof($data)
    {
        $data = escape_array($data);
        for ($i = 0; $i < count($data['attachments']); $i++) {
            $order_data = [
                'order_id' => $data['order_id'],
                'attachments' => $data['attachments'][$i],
            ];
            $this->db->insert('order_bank_transfer', $order_data);
        }
        return true;
    }

    public function add_payment_demand($data)
    {
        $data = escape_array($data);
        $ids = array();
        for ($i = 0; $i < count($data['attachments']); $i++) {
            $order_data = [
                'order_id'      => $data['order_id'],
                'order_item_id' => $data['order_item_id'],
                'attachments'   => $data['attachments'][$i],
            ];
            $this->db->insert('order_item_payment_demand', $order_data);
            $ids[] = $this->db->insert_id();
        }

        $order_item_stages = array('order_id' => $data['order_id'], 'order_item_id' => $data['order_item_id'], 'status' => 'payment_ack', 'type' => 'payment_demand', 'ids' => implode(',', $ids));
        $this->db->insert('order_item_stages', $order_item_stages);

        return true;
    }

    public function add_complaint($data)
    {
        $data = escape_array($data);
        $ids = array();
        if (count($data['attachments'])) {
            for ($i = 0; $i < count($data['attachments']); $i++) {
                $order_data = [
                    'order_id'      => $data['order_id'],
                    'concern'       => $data['concern'],
                    'attachments'   => $data['attachments'][$i],
                ];
                $this->db->insert('order_item_complaints', $order_data);
                $ids[] = $this->db->insert_id();
            }
        } else {
            $order_data = [
                'order_id'      => $data['order_id'],
                'concern'       => $data['concern'],
            ];
            $this->db->insert('order_item_complaints', $order_data);
            $ids[] = $this->db->insert_id();
        }

        $order_item_stages = array('order_id' => $data['order_id'], 'order_item_id' => 0, 'status' => 'complaint', 'type' => 'complaint', 'ids' => implode(',', $ids));
        $this->db->insert('order_item_stages', $order_item_stages);

        return true;
    }

    public function add_complaint_msg($data)
    {
        $data = escape_array($data);
        $ids = array();
        if (count($data['attachments'])) {
            for ($i = 0; $i < count($data['attachments']); $i++) {
                $order_data = [
                    'order_id'      => $data['order_id'],
                    'message'       => $data['message'],
                    'attachments'   => $data['attachments'][$i],
                ];
                $this->db->insert('order_item_complaint_messages', $order_data);
                $ids[] = $this->db->insert_id();
            }
        } else {
            $order_data = [
                'order_id'      => $data['order_id'],
                'message'       => $data['message'],
            ];
            $this->db->insert('order_item_complaint_messages', $order_data);
            $ids[] = $this->db->insert_id();
        }

        $order_item_stages = array('order_id' => $data['order_id'], 'order_item_id' => 0, 'status' => 'complaint_msg', 'type' => 'complaint_msg', 'ids' => implode(',', $ids));
        $this->db->insert('order_item_stages', $order_item_stages);

        return true;
    }


    public function add_payment_confirmation($data)
    {
        $data = escape_array($data);
        $ids = array();
        for ($i = 0; $i < count($data['attachments']); $i++) {
            $order_data = [
                'order_id'      => $data['order_id'],
                'order_item_id' => $data['order_item_id'],
                'attachments'   => $data['attachments'][$i],
            ];
            $this->db->insert('order_item_payment_confirmation', $order_data);
            $ids[] = $this->db->insert_id();
        }

        $order_item_stages = array('order_id' => $data['order_id'], 'order_item_id' => $data['order_item_id'], 'status' => 'send_payment_confirmation', 'type' => 'send_payment_confirmation', 'ids' => implode(',', $ids));
        $this->db->insert('order_item_stages', $order_item_stages);

        $this->db->select('a.id, a.status, a.active_status');
        $this->db->from('order_items as a');
        $this->db->where('a.order_id', $data['order_id']);

        if ($data['order_item_id']) {
            $this->db->where('a.order_item_id', $data['order_item_id']);
        }

        $this->db->where_not_in('a.active_status', array('delivered', 'cancelled'));
        $query = $this->db->get();
        $order_items_info = $query->result_array();

        if ($order_items_info) {
            foreach ($order_items_info as $order_item_info) {
                $status = json_decode(stripallslashes($order_item_info['status']));
                if ($status != null) {
                    array_push($status, array('send_payment_confirmation', date('d-m-Y h:i:sa')));
                } else {
                    $status =  array(array('send_payment_confirmation', date("d-m-Y h:i:sa")));
                }

                $update_item_data = array('active_status' => 'send_payment_confirmation', 'status' => json_encode($status));
                update_details($update_item_data, ['id' => $order_item_info['id']], 'order_items');
            }
        }

        $this->db->update('orders', array('order_status' => 'send_payment_confirmation', 'last_updated' => date('Y-m-d H:i:s')), array('id' => $data['order_id']));

        return true;
    }

    public function add_mfg_payment_ack($data)
    {
        $data = escape_array($data);
        $ids = array();
        for ($i = 0; $i < count($data['attachments']); $i++) {
            $order_data = [
                'order_id'      => $data['order_id'],
                'order_item_id' => $data['order_item_id'],
                'attachments'   => $data['attachments'][$i],
            ];
            $this->db->insert('order_item_mfg_payment_ack', $order_data);
            $ids[] = $this->db->insert_id();
        }

        $order_item_stages = array('order_id' => $data['order_id'], 'order_item_id' => $data['order_item_id'], 'status' => 'send_mfg_payment_ack', 'type' => 'send_mfg_payment_ack', 'ids' => implode(',', $ids));
        $this->db->insert('order_item_stages', $order_item_stages);

        $this->db->select('a.id, a.status, a.active_status');
        $this->db->from('order_items as a');
        $this->db->where('a.order_id', $data['order_id']);

        if ($data['order_item_id']) {
            $this->db->where('a.order_item_id', $data['order_item_id']);
        }

        //$this->db->where_not_in('a.active_status', array('delivered','cancelled'));
        $query = $this->db->get();
        $order_items_info = $query->result_array();

        if ($order_items_info) {
            foreach ($order_items_info as $order_item_info) {
                $status = json_decode(stripallslashes($order_item_info['status']));
                if ($status != null) {
                    array_push($status, array('send_mfg_payment_ack', date('d-m-Y h:i:sa')));
                } else {
                    $status =  array(array('send_mfg_payment_ack', date("d-m-Y h:i:sa")));
                }

                $update_item_data = array('active_status' => 'send_mfg_payment_ack', 'status' => json_encode($status));
                $update_item_data["admin_transaction_id"] = $data["transaction_id"];
                update_details($update_item_data, ['id' => $order_item_info['id']], 'order_items');
            }
        }

        $this->db->update('orders', array('order_status' => 'send_mfg_payment_ack', 'last_updated' => date('Y-m-d H:i:s')), array('id' => $data['order_id']));

        return true;
    }

    public function add_mfg_payment_confirmation($data)
    {
        $data = escape_array($data);
        $ids = array();
        for ($i = 0; $i < count($data['attachments']); $i++) {
            $order_data = [
                'order_id'      => $data['order_id'],
                'order_item_id' => $data['order_item_id'],
                'attachments'   => $data['attachments'][$i],
            ];
            $this->db->insert('order_item_mfg_payment_confirmation', $order_data);
            $ids[] = $this->db->insert_id();
        }

        $order_item_stages = array('order_id' => $data['order_id'], 'order_item_id' => $data['order_item_id'], 'status' => 'send_mfg_payment_confirmation', 'type' => 'send_mfg_payment_confirmation', 'ids' => implode(',', $ids));
        $this->db->insert('order_item_stages', $order_item_stages);

        $this->db->select('a.id, a.status, a.active_status');
        $this->db->from('order_items as a');
        $this->db->where('a.order_id', $data['order_id']);

        if ($data['order_item_id']) {
            $this->db->where('a.order_item_id', $data['order_item_id']);
        }

        //$this->db->where_not_in('a.active_status', array('delivered','cancelled'));
        $query = $this->db->get();
        $order_items_info = $query->result_array();

        if ($order_items_info) {
            foreach ($order_items_info as $order_item_info) {
                $status = json_decode(stripallslashes($order_item_info['status']));
                if ($status != null) {
                    array_push($status, array('send_mfg_payment_confirmation', date('d-m-Y h:i:sa')));
                } else {
                    $status =  array(array('send_mfg_payment_confirmation', date("d-m-Y h:i:sa")));
                }

                $update_item_data = array('active_status' => 'send_mfg_payment_confirmation', 'status' => json_encode($status));
                update_details($update_item_data, ['id' => $order_item_info['id']], 'order_items');
            }
        }

        $this->db->update('orders', array('order_status' => 'send_mfg_payment_confirmation', 'last_updated' => date('Y-m-d H:i:s')), array('id' => $data['order_id']));

        return true;
    }

    public function add_statement($data)
    {
        $data = escape_array($data);
        $ids = array();
        for ($i = 0; $i < count($data['attachments']); $i++) {
            $order_data = [
                'from_date'     => $data['from_date'],
                'to_date'       => $data['to_date'],
                'retailer_id'   => $data['retailer_id'],
                'seller_id'     => $data['seller_id'],
                'attachments'   => $data['attachments'][$i],
                'created_date'  => date('Y-m-d H:i:s'),
            ];
            $this->db->insert('retailer_statements', $order_data);
            $ids[] = $this->db->insert_id();
        }
        return true;
    }

    public function add_invoice($data)
    {
        $data = escape_array($data);
        $ids = $ids2 = array();
        for ($i = 0; $i < count($data['attachments']); $i++) {
            $order_data = [
                'order_id'      => $data['order_id'],
                'order_item_id' => $data['order_item_id'],
                'attachments'   => $data['attachments'][$i],
            ];
            $this->db->insert('order_item_invoice', $order_data);
            $ids[] = $this->db->insert_id();
        }

        for ($i = 0; $i < count($data['attachments2']); $i++) {
            $order_data = [
                'order_id'      => $data['order_id'],
                'order_item_id' => $data['order_item_id'],
                'attachments'   => $data['attachments2'][$i],
            ];
            $this->db->insert('order_item_eway_bill', $order_data);
            $ids2[] = $this->db->insert_id();
        }

        $order_item_stages = array('order_id' => $data['order_id'], 'order_item_id' => $data['order_item_id'], 'status' => 'send_invoice', 'type' => 'send_invoice', 'ids' => implode(',', $ids), 'ids2' => implode(',', $ids2));
        $this->db->insert('order_item_stages', $order_item_stages);

        $this->db->select('a.id, a.status, a.active_status');
        $this->db->from('order_items as a');
        $this->db->where('a.order_id', $data['order_id']);

        if ($data['order_item_id']) {
            $this->db->where('a.order_item_id', $data['order_item_id']);
        }

        $this->db->where_not_in('a.active_status', array('delivered', 'cancelled'));
        $query = $this->db->get();
        $order_items_info = $query->result_array();

        if ($order_items_info) {
            foreach ($order_items_info as $order_item_info) {
                $status = json_decode(stripallslashes($order_item_info['status']));
                if ($status != null) {
                    array_push($status, array('send_invoice', date('d-m-Y h:i:sa')));
                } else {
                    $status =  array(array('send_invoice', date("d-m-Y h:i:sa")));
                }

                $update_item_data = array('active_status' => 'send_invoice', 'status' => json_encode($status));
                update_details($update_item_data, ['id' => $order_item_info['id']], 'order_items');
            }
        }

        $this->db->update('orders', array('order_status' => 'send_invoice', 'last_updated' => date('Y-m-d H:i:s')), array('id' => $data['order_id']));

        return true;
    }

    public function get_order_tracking_list()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';
        $multipleWhere = '';
        $where = [];

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = ['`id`' => $search, '`order_id`' => $search, '`tracking_id`' => $search, 'courier_agency' => $search, 'order_item_id' => $search, 'url' => $search];
        }
        if (isset($_GET['order_id']) and $_GET['order_id'] != '') {
            $where = ['order_id' => $_GET['order_id']];
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->db->group_Start();
            $count_res->or_like($multipleWhere);
            $this->db->group_End();
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }


        $txn_count = $count_res->get('order_tracking')->result_array();

        foreach ($txn_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->db->group_Start();
            $search_res->or_like($multipleWhere);
            $this->db->group_End();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $txn_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('order_tracking')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($txn_search_res as $row) {
            $row = output_escaping($row);
            if ($this->ion_auth->is_seller()) {
                $operate = '<a href=' . base_url('seller/orders/edit_orders') . '?edit_id=' . $row['order_id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View Order" ><i class="fa fa-eye"></i></a>';
            } else {
                $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['order_id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View Order" ><i class="fa fa-eye"></i></a>';
            }

            $tempRow['id'] = $row['id'];
            $tempRow['order_id'] = $row['order_id'];
            $tempRow['order_item_id'] = $row['order_item_id'];
            $tempRow['courier_agency'] = $row['courier_agency'];
            $tempRow['tracking_id'] = $row['tracking_id'];
            $tempRow['url'] = $row['url'];
            $tempRow['date'] = $row['date_created'];
            $tempRow['operate'] = $operate;

            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function get_order_tracking($limit = "", $offset = '', $sort = 'id', $order = 'DESC', $search = NULL)
    {

        $multipleWhere = '';

        if (isset($search) and $search != '') {
            $multipleWhere = ['id' => $search, 'order_id' => $search, 'tracking_id' => $search, 'courier_agency' => $search, 'order_item_id' => $search, 'url' => $search];
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }
        $attr_count = $count_res->get('order_tracking')->result_array();

        foreach ($attr_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select('*');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $city_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('order_tracking')->result_array();
        $bulkData = array();
        $bulkData['error'] = (empty($city_search_res)) ? true : false;
        $bulkData['message'] = (empty($city_search_res)) ? 'Order Tracking details does not exist' : 'Order Tracking details are retrieve successfully';
        $bulkData['total'] = (empty($city_search_res)) ? 0 : $total;
        $rows = $tempRow = array();

        foreach ($city_search_res as $row) {
            $tempRow['id'] = $row['id'];
            $tempRow['order_id'] = $row['order_id'];
            $tempRow['order_item_id'] = $row['order_item_id'];
            $tempRow['courier_agency'] = $row['courier_agency'];
            $tempRow['tracking_id'] = $row['tracking_id'];
            $tempRow['url'] = $row['url'];
            $tempRow['date'] = $row['date_created'];
            $rows[] = $tempRow;
        }
        $bulkData['data'] = $rows;
        print_r(json_encode($bulkData));
    }
    public function get_items_account_list($user_id = '', $status = array())
    {
        $offset = 0;
        $limit = 10;

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }

        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        $this->db->select('p.id as product_id,p.slug,p.hsn_no, p.name as product_name, SUM(oi.quantity) as total_quantity, 
                       SUM(oi.sub_total) as total_earned, sd.company_name as seller_name,c.name as category_name,v.price,v.mrp,oi.tax_percent');
        $this->db->from('order_items oi');
        $this->db->join('product_variants v', 'oi.product_variant_id = v.id', 'left');
        $this->db->join('products p', 'v.product_id = p.id', 'left');
        $this->db->join('users us', 'us.id = oi.seller_id', 'left');
        $this->db->join('seller_data sd', 'sd.user_id = oi.seller_id', 'left');
        $this->db->join('orders o', 'oi.order_id = o.id', 'left');
        $this->db->join('categories c', 'p.category_id = c.id', 'left');

        // Apply filters (e.g., user_id, order status)
        if (!empty($user_id)) {
            $this->db->where('o.user_id', $user_id);
        }

        if (!empty($status) && is_array($status)) {
            $this->db->where_in('oi.active_status', $status);
        }

        // Add any additional filtering as needed
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $this->db->where("DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "')");
            $this->db->where("DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "')");
        }
        if (isset($_GET['search_field']) && !empty($_GET['search_field'])) {
            $search = $_GET['search_field'];
            $this->db->group_start(); // Start grouping search conditions
            $this->db->or_like('p.id', $search);
            $this->db->or_like('p.name', $search);
            $this->db->or_like('c.name', $search);
            $this->db->or_like('sd.company_name', $search);
            $this->db->or_like('p.hsn_no', $search);
            $this->db->or_like('c.name', $search);
            $this->db->or_like('v.price', $search);
            $this->db->or_like('v.mrp', $search);
            $this->db->group_end(); // End grouping search conditions
        }
        // Group by product ID
        $this->db->group_by('p.id');
        $count_query = clone $this->db;
        $total = $count_query->get()->num_rows();
    

        // Sorting and limiting
        $this->db->order_by('total_quantity', 'DESC'); // Example: order by total quantity sold
        // $total = $this->db->get()->result_array();
 
        $this->db->limit($limit, $offset);

        $result = $this->db->get()->result_array();

        // Prepare response
        $response = array();
        foreach ($result as $key=>$row) {
            $product_view_url = '<a target="_blank" href="' . base_url() . 'products/details/' . $row['slug'] . '">View</a>';
           
            $response[] = array(
                'id' => $key+1,
                'product_id' => $row["product_id"],
                'product_name' => $row['product_name'],
                'hsn_no' => $row['hsn_no'],
                'category_name' => $row['category_name'],
                'price' => $row['price'],
                'mrp' => $row['mrp'],
                'gst' => ($row["price"] * $row['tax_percent'] / 100)."(%)",
                'product_view_url' => $product_view_url,

            );
        }

        print_r(json_encode(array('total' => $total, 'rows' => $response)));
    }
    public function get_admin_account_orders_list(
        $delivery_boy_id = NULL,
        $offset = 0,
        $limit = 10,
        $sort = " o.id ",
        $order = 'ASC'
    ) {
        $this->db->select('order_id');
        $this->db->from('order_item_stages');
        $this->db->where('status', 'issue_resolved');
        $q              = $this->db->get();
        $issue_orders   = $q->result_array();

        $issue_order_ids = array();
        if ($issue_orders) {
            $issue_order_ids = array_column($issue_orders, 'order_id');
        }

        $search_field = '';

        if (isset($_GET['search_field'])) {
            $search_field = $_GET['search_field'];
        }

        $condition = '';
        $status = false;

        if (isset($_GET['condition'])) {
            $condition = $_GET['condition'];

            if ($condition == 1) {
                $status = array('payment_demand', 'payment_ack', 'schedule_delivery', 'send_payment_confirmation',);
            } else if ($condition == 2) {
                $status = array('send_invoice');
            } else if ($condition == 3) {
                $status = array("complaint", "complaint_msg");
            } else if ($condition == 4) {
                $status = array("cancelled");
            } else if ($condition == 5) {
                $status = array("delivered", "send_mfg_payment_ack", "send_mfg_payment_confirmation");
            } else if ($condition == 6) {
                $status = array('received');
            } else if ($condition == 7) {
                $status = array('payment_ack', 'complaint', 'delivered');
            }
        }

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['seller_id'])) {
            $seller_id = $_GET['seller_id'];
        }

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];

            $search = str_replace('HC-A', '', $search);

            $filters = [
                'u.username' => $search,
                //'db.username' => $search,
                'u.email' => $search,
                'o.id' => $search,
                'o.mobile' => $search,
                'o.address' => $search,
                'o.wallet_balance' => $search,
                'o.total' => $search,
                'o.final_total' => $search,
                'o.total_payable' => $search,
                'o.payment_method' => $search,
                'o.delivery_charge' => $search,
                'o.delivery_time' => $search,
                'o.order_status' => $search,
                //'o.status' => $search,
                //'o.active_status' => $search,
                'date_added' => $search,
                'rd.company_name' => $search,
            ];
        }

        if ($search_field != '') {
            $order_id_search = trim(preg_replace('/[^0-9]/', '', $search_field));

            $filters = [
                'rd.company_name' => trim($search_field),
                'sd.company_name' => trim($search_field),
            ];

            if ($order_id_search != '') {
                $filters = [
                    'o.id' => $order_id_search
                ];
            }
        }

        $count_res = $this->db->select(' COUNT(o.id) as `total` ')
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('retailer_data rd ', ' rd.user_id = o.user_id', 'left')
            ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
            ->join('addresses ad ', ' ad.id = o.billing_address_id', 'left')
            ->join('cities ct ', ' ct.id = ad.city_id', 'left')
            ->join('order_item_payment_confirmation as op', 'o.id = op.order_id', 'left')
            ->join('order_item_invoice as inv', 'o.id = inv.order_id', 'left')
            ->join('order_item_mfg_payment_ack as mfg_ack', 'o.id = mfg_ack.order_id', 'left')
            ->join('order_item_stages as ois', 'oi.order_id = ois.order_id', 'left');
            $count_res->where("ois.status",'send_mfg_payment_ack');

        //->join('users db ', ' db.id = oi.delivery_boy_id', 'left');
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $count_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $count_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $this->db->group_Start();
            $count_res->or_like($filters);
            $this->db->group_End();
        }

        if (isset($seller_id)) {
            $count_res->where("oi.seller_id", $seller_id);
        }

        /*if (isset($delivery_boy_id)) {
            $count_res->where("oi.delivery_boy_id", $delivery_boy_id);
        }*/

        if (isset($_GET['user_id']) && $_GET['user_id'] != null) {
            $count_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            if ($_GET['order_status'] == 'issue_closed') {
                $count_res->where('o.order_status', 'delivered');

                if ($_GET['order_status'] == 'issue_closed') {
                    $count_res->where_in("o.id", $issue_order_ids);
                }
            } else {
                $count_res->where('o.order_status', $_GET['order_status']);
                if ($_GET['order_status'] == 'delivered') {
                    $count_res->where_not_in("o.id", $issue_order_ids);
                }
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $count_res->where_in('oi.active_status', $status);
        }

        $count_res->where('o.is_service_category', 0);

        $count_res->group_by('o.id');

        $product_count = $count_res->get('`orders` o')->result_array();

        /*foreach ($product_count as $row) {
            $total = $row['total'];
        }*/

        $total = count($product_count);

        $search_res = $this->db->select(' o.* , u.username, rd.company_name as retailer_name, sd.company_name as mfg_name, sd.slug as seller_slug, ct.name as city_name, op.attachments as payment_receipt, inv.attachments as invoice_receipt, mfg_ack.attachments as hc_receipt,ois.status as mfg_status')
            ->join(' `users` u', 'u.id= o.user_id', 'left')
            ->join(' `order_items` oi', 'oi.order_id= o.id', 'left')
            ->join('users us ', ' us.id = oi.seller_id', 'left')
            ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
            ->join('products p ', ' p.id = v.product_id ', 'left')
            ->join('retailer_data rd ', ' rd.user_id = o.user_id', 'left')
            ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
            ->join('addresses ad ', ' ad.id = o.billing_address_id', 'left')
            ->join('cities ct ', ' ct.id = ad.city_id', 'left')
            ->join('order_item_payment_confirmation as op', 'o.id = op.order_id', 'left')
            ->join('order_item_invoice as inv', 'o.id = inv.order_id', 'left')
            ->join('order_item_mfg_payment_ack as mfg_ack', 'o.id = mfg_ack.order_id', 'left')
            ->join('order_item_stages as ois', 'oi.order_id = ois.order_id', 'left');
            $count_res->where("ois.status",'send_mfg_payment_ack');
        //->join('users db ', ' db.id = oi.delivery_boy_id', 'left');

        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $search_res->where(" DATE(o.date_added) >= DATE('" . $_GET['start_date'] . "') ");
            $search_res->where(" DATE(o.date_added) <= DATE('" . $_GET['end_date'] . "') ");
        }

        if (isset($filters) && !empty($filters)) {
            $search_res->group_Start();
            $search_res->or_like($filters);
            $search_res->group_End();
        }

        if (isset($seller_id)) {
            $search_res->where("oi.seller_id", $seller_id);
        }

        /*if (isset($delivery_boy_id)) {
            $search_res->where("oi.delivery_boy_id", $delivery_boy_id);
        }*/

        if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
            $search_res->where("o.user_id", $_GET['user_id']);
        }

        if (isset($_GET['order_status']) && !empty($_GET['order_status'])) {
            if ($_GET['order_status'] == 'issue_closed') {
                $search_res->where('o.order_status', 'delivered');

                if ($_GET['order_status'] == 'issue_closed') {
                    $search_res->where_in("o.id", $issue_order_ids);
                }
            } else {
                $search_res->where('o.order_status', $_GET['order_status']);

                if ($_GET['order_status'] == 'delivered') {
                    $search_res->where_not_in("o.id", $issue_order_ids);
                }
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $search_res->where_in('oi.active_status', $status);
        }

        $search_res->where('o.is_service_category', 0);

        $user_details = $search_res->group_by('o.id')->order_by($sort, "DESC")->limit($limit, $offset)->get('`orders` o')->result_array();

        $i = 0;
        foreach ($user_details as $row) {
            $user_details[$i]['items'] = $this->db->select('oi.*,p.name as name,p.id as product_id, u.username as uname, us.username as seller, sd.company_name ')
                ->join('product_variants v ', ' oi.product_variant_id = v.id', 'left')
                ->join('products p ', ' p.id = v.product_id ', 'left')
                ->join('users u ', ' u.id = oi.user_id', 'left')
                ->join('users us ', ' us.id = oi.seller_id', 'left')
                ->join('seller_data sd ', ' sd.user_id = oi.seller_id', 'left')
                ->where('oi.order_id', $row['id'])
                ->get(' `order_items` oi  ')->result_array();
            ++$i;
        }

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $tota_amount = 0;
        $final_tota_amount = 0;
        $currency_symbol = get_settings('currency');

        //$order_msg = array('received'=>'Order Received','qty_update'=>'Quantity updated and approval request sent.','qty_approved'=>'Quantity approval accepted by retailer.','payment_demand'=>'Payment request sent.','payment_ack'=>'Transaction details Received.','schedule_delivery'=>'Order Scheduled.','shipped'=>'Order shipped.','send_invoice'=>'Invoices sent.','delivered'=>'Order Closed.','cancelled'=>'Order cancelled.');
        $order_msg = array('received' => 'Order Received', 'qty_update' => 'Quantity updated and approval request sent.', 'qty_approved' => 'Quantity approval accepted by retailer.', 'payment_demand' => 'Payment request sent.', 'payment_ack' => 'Transaction details received from retailer.', 'send_payment_confirmation' => 'Payment confirmation sent to retailer.', 'schedule_delivery' => 'Order Scheduled.', 'shipped' => 'Order shipped.', 'send_invoice' => 'E-way bill and invoices sent to retailer.', 'complaint' => 'Retailer raised his concern.', 'delivered' => 'Order delivered successfully.', 'cancelled' => 'Order cancelled.', 'send_mfg_payment_ack' => 'Transaction details shared with manufacturer.', 'send_mfg_payment_confirmation' => 'Payment receipt received.', 'complaint_msg' => 'Issue details shared by Happycrop', 'service_completed' => 'Service Completed');

        foreach ($user_details as $row) {
            if (!empty($row['items'])) {
                $items = $row['items'];
                $items1 = '';
                $temp = '';
                $total_amt = 0;
                //$seller = implode(",", array_values(array_unique(array_column($items, "seller"))));
                $company_name = implode(",", array_values(array_unique(array_column($items, "company_name"))));

                foreach ($items as $item) {
                    $product_variants = get_variants_values_by_id($item['product_variant_id']);
                    $variants = isset($product_variants[0]['variant_values']) && !empty($product_variants[0]['variant_values']) ? str_replace(',', ' | ', $product_variants[0]['variant_values']) : '-';
                    $temp .= "<b>ID :</b>" . $item['id'] . "<b> Product Variant Id :</b> " . $item['product_variant_id'] . "<b> Variants :</b> " . $variants . "<b> Name : </b>" . $item['name'] . " <b>Price : </b>" . $item['price'] . " <b>QTY : </b>" . $item['quantity'] . " <b>Subtotal : </b>" . $item['quantity'] * $item['price'] . "<br>------<br>";
                    $total_amt += $item['sub_total'];
                }

                $items1 = $temp;
                $discounted_amount = $row['total'] * $row['items'][0]['discount'] / 100;
                $final_total = $row['total'] - $discounted_amount;
                $discount_in_rupees = $row['total'] - $final_total;
                $discount_in_rupees = floor($discount_in_rupees);
                $tempRow['id'] = 'HC-A' . $row['id'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['name'] = $row['retailer_name']; //$row['items'][0]['uname'];
                /*if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $tempRow['mobile'] = str_repeat("X", strlen($row['mobile']) - 3) . substr($row['mobile'], -3);
                } else {*/
                $tempRow['mobile'] = $row['mobile'];
                //}
                $tempRow['delivery_charge'] = $currency_symbol . ' ' . $row['delivery_charge'];
                $tempRow['items'] = $items1;
                $tempRow['sellers'] = '<a href="' . base_url('products?seller=' . $row['seller_slug']) . '">' . $company_name . '</a>'; //$seller;
                $tempRow['total'] = $currency_symbol . ' ' . $row['total'];
                $tota_amount += intval($row['total']);
                $tempRow['wallet_balance'] = $currency_symbol . ' ' . $row['wallet_balance'];
                $tempRow['discount'] = $currency_symbol . ' ' . $discount_in_rupees . '(' . $row['items'][0]['discount'] . '%)';
                $tempRow['promo_discount'] = $currency_symbol . ' ' . $row['promo_discount'];
                $tempRow['promo_code'] = $row['promo_code'];
                $tempRow['notes'] = $row['notes'];
                $tempRow['qty'] = $row['items'][0]['quantity'];
                $tempRow['final_total'] = $currency_symbol . ' ' . $row['total_payable'];
                $final_total = $row['final_total'] - $row['wallet_balance'] - $row['promo_discount'] - $row['discount'];
                $tempRow['final_total'] = $currency_symbol . ' ' . $final_total;
                $final_tota_amount += intval($row['final_total']);
                $tempRow['deliver_by'] = $row['delivery_boy'];
                $tempRow['payment_method'] = $row['payment_method'];
                $tempRow['address'] = output_escaping(str_replace('\r\n', '</br>', $row['address']));
                $tempRow['city_name'] = $row['city_name'];
                $tempRow['delivery_date'] = $row['delivery_date'];
                $tempRow['delivery_time'] = $row['delivery_time'];
                $tempRow['date_added'] = date('d-m-Y', strtotime($row['date_added']));

                $tempRow['schedule_delivery_date'] = ($row['schedule_delivery_date'] != null && $row['schedule_delivery_date'] != '0000-00-00') ?  date('d-m-Y', strtotime($row['schedule_delivery_date'])) : '';

                $this->db->select('id');
                $this->db->from('order_item_stages');
                $this->db->where('status', 'issue_resolved');
                $this->db->where('order_id', $row['id']);
                $q = $this->db->get();
                $rw = $q->row_array();

                if ($rw['id'] && $row['order_status'] == 'delivered') {
                    $order_msg['delivered'] = 'Issue resolved. Make payment.'; //'Order closed.';//$order_msg['send_mfg_payment_ack'] = $order_msg['send_mfg_payment_confirmation'] = 
                } else {
                    $order_msg['delivered'] = 'Order delivered.'; //$order_msg['send_mfg_payment_ack'] = $order_msg['send_mfg_payment_confirmation'] = 
                }

                $tempRow['order_status'] = $order_msg[$row['order_status']];

                // $tempRow['payment_receipt'] = (file_exists($row['payment_receipt']) && $row['payment_receipt']!='') ? '<a href="'.base_url($row['payment_receipt']).'" target="_blank">View / Download</a>' : '';
                // $tempRow['invoice_receipt'] = (file_exists($row['invoice_receipt']) && $row['invoice_receipt']!='') ? '<a href="'.base_url($row['invoice_receipt']).'" target="_blank">View / Download</a>' : '';
                // $tempRow['hc_receipt'] = (file_exists($row['hc_receipt']) && $row['hc_receipt']!='') ? '<a href="'.base_url($row['hc_receipt']).'" target="_blank">View / Download</a>' : '';
                $tempRow['payment_receipt'] = (file_exists($row['payment_receipt']) && $row['payment_receipt'] != '') ? '<a href="' . base_url("my-account/payment-receipt/") . $row['id'] . "/view" . '" target="_blank">View / Download</a>' : '';
                $tempRow['invoice_receipt'] = (file_exists($row['invoice_receipt']) && $row['invoice_receipt'] != '') ? '<a href="' . base_url("my-account/tax-invoice/") . $row['id'] . "/view" . '" target="_blank">View / Download</a>' : '';
                $tempRow['hc_receipt'] = '<a href="' . base_url("seller/orders/paymentreceipt/") . $row['id'] . "/view" . '" target="_blank">View / Download</a>';

                /*
                $tempRow['color_state'] = '';
                if($row['order_status'] == 'delivered')
                {
                    if($rw['id'])
                    {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    }
                    else
                    {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                }
                else if($row['order_status'] == 'service_completed')
                {
                    $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                }
                else if($row['order_status'] == 'send_mfg_payment_ack')
                {
                    if($rw['id'])
                    {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    }
                    else
                    {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                }
                else if($row['order_status'] == 'send_mfg_payment_confirmation')
                {
                    if($rw['id'])
                    {
                        $tempRow['color_state'] = '<span class="issue-resolved-state"><i class="fa fa-check"></i></span>';
                    }
                    else
                    {
                        $tempRow['color_state'] = '<span class="delivered-state"><i class="fa fa-check"></i></span>';
                    }
                }
                else if($row['order_status'] == 'cancelled')
                {
                    $tempRow['color_state'] = '<span class="cancelled-state"><i class="fa fa-circle"></i></span>';
                }
                else if($row['order_status'] == 'complaint')
                {
                    $tempRow['color_state'] = '<span class="issue-state"><i class="fa fa-circle"></i></span>';
                }
                else if($row['order_status'] == 'complaint_msg')
                {
                    $tempRow['color_state'] = '<span class="issue-state"><i class="fa fa-circle"></i></span>';
                }
                else
                {
                    $tempRow['color_state'] = '<span class="active-state"><i class="fa fa-circle"></i></span>';
                }
                */
                $tempRow['last_updated']   = ($row['last_updated'] != null) ? date('d-m-Y', strtotime($row['last_updated'])) : '';

                $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['id'] . '" class="btn btn-primary btn-xs mr-1 mb-1" title="View" >View Details</a>';
                if (!$this->ion_auth->is_delivery_boy()) {
                    $operate = '<a href=' . base_url('admin/orders/edit_orders') . '?edit_id=' . $row['id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View" >View Details</a>';
                    //$operate .= '<a href="javascript:void(0)" class="delete-orders btn btn-danger btn-xs mr-1 mb-1" data-id=' . $row['id'] . ' title="Delete" ><i class="fa fa-trash"></i></a>';
                    //$operate .= '<a href="' . base_url() . 'admin/invoice?edit_id=' . $row['id'] . '" class="btn btn-info btn-xs mr-1 mb-1" title="Invoice" ><i class="fa fa-file"></i></a>';
                    //$operate .= ' <a href="javascript:void(0)" class="edit_order_tracking btn btn-success btn-xs mr-1 mb-1" title="Order Tracking" data-order_id="' . $row['id'] . '"  data-target="#order-tracking-modal" data-toggle="modal"><i class="fa fa-map-marker-alt"></i></a>';
                } else {
                    $operate = '<a href=' . base_url('delivery_boy/orders/edit_orders') . '?edit_id=' . $row['id'] . ' class="btn btn-primary btn-xs mr-1 mb-1" title="View">View Details</a>';
                }
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
}
