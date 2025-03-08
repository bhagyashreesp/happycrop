<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_account_model extends CI_Model
{
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
    public function get_external_parties_list($user_id)
    {
        $offset = 0;
        $limit = 10;

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }

        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        $this->db->select('e.*');
        $this->db->from('external_parties e');
        // $this->db->join('expenses_items ei', 'e.id = ei.expense_id', 'left');


        // Apply filters (e.g., user_id, order status)
        if (!empty($user_id)) {
            $this->db->where_in('e.user_id', $user_id);
        }


        if ($this->input->get('search_field')) {
            $search = trim($this->input->get('search_field', true));
            $this->db->group_start();
            $this->db->or_like('e.id', $search);
            $this->db->or_like('e.party_name', $search);
            $this->db->or_like('e.mobile', $search);
            $this->db->group_end();
        }

        // Group by product ID
        $this->db->group_by('e.id');
        $count_query = clone $this->db;
        $total = $count_query->get()->num_rows();


        // Sorting and limiting
        $this->db->order_by('e.id', 'DESC'); // Example: order by total quantity sold
        // $total = $this->db->get()->result_array();

        $this->db->limit($limit, $offset);

        $result = $this->db->get()->result_array();

        // Prepare response
        $response = array();
        foreach ($result as $key => $row) {
            $action = '<a href="' . base_url() . 'my-account/external-parties/' . $row['id'] . '" class="" title="View">View</a>';
            $actionseller = '<a href="' . base_url() . 'seller/orders/external-parties/' . $row['id'] . '" class="" title="View">View</a>';
            $response[] = array(
                'id' => $key + 1,
                'party' => $row["id"],
                'party_name' => $row['party_name'],
                'mobile' => $row['mobile'],
                'email' => $row['email'],
                'address' => $row['address'],
                'gst' => $row["gst"],
                'fertilizer_licence_no' => $row["fertilizer_licence_no"],
                'pesticide_licence_no' => $row["pesticide_licence_no"],
                'action' => $action,
                'actionseller' => $actionseller,

            );
        }


        print_r(json_encode(array('total' => $total, 'rows' => $response)));
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
    public function view_seller_account_items($user_id)
    {
        $offset = 0;
        $limit = 10;

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }

        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        $this->db->select('p.* , c.name as category_name,v.mrp,v.price,sc.is_service_category');
        $this->db->from('products p')
            ->join('product_variants v ', ' p.id = v.product_id', 'left')
            ->join('categories c ', ' p.category_id = c.id ', 'left')
            ->join('super_categories as sc', 'p.super_category_id = sc.id');


        // Apply filters (e.g., user_id, order status)
        if (!empty($user_id)) {
            $this->db->where_in('p.seller_id', $user_id);
        }


        if ($this->input->get('search_field')) {
            $search = trim($this->input->get('search_field', true));
            $this->db->group_start();
            $this->db->or_like('p.id', $search);
            $this->db->or_like('p.pro_no', $search);
            $this->db->or_like('c.name', $search);
            $this->db->or_like('p.name', $search);
            // $this->db->or_like('p.short_description', $search);
            $this->db->group_end();
        }

        // Group by product ID
        $this->db->group_by('p.id');
        $count_query = clone $this->db;
        $total = $count_query->get()->num_rows();


        // Sorting and limiting
        $this->db->order_by('p.date_added', 'DESC'); // Example: order by total quantity sold
        // $total = $this->db->get()->result_array();

        $this->db->limit($limit, $offset);

        $result = $this->db->get()->result_array();

        // Prepare response
        $response = array();
        foreach ($result as $key => $row) {
            $product_view_url = '<a target="_blank" href="' . base_url() . 'products/details/' . $row['slug'] . '">View</a>';
            if ($row['image'] != "" && isFileExists($row['image'])) {
                $image = isFileExists($row['image']);
            } else {
                $image = "-";
            }


            $response[] = array(
                'product_id' => ($row['is_service_category']) ? 'HCS' . str_pad($row['serv_no'], 6, "0", STR_PAD_LEFT) : 'HCP' . str_pad($row['pro_no'], 6, "0", STR_PAD_LEFT),
                'name' => $row["name"],
                'hsn_no' => $row['hsn_no'],
                'category_name' => $row['category_name'],
                'price' => $row['price'],
                'mrp' => $row['mrp'],
                'gst' => ($row["price"] * $row['tax'] / 100) . "(%)",
                'product_view_url' => $product_view_url,
                'image' => $image,

            );
        }


        print_r(json_encode(array('total' => $total, 'rows' => $response)));
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
            $this->db->where_in('o.user_id', $user_id);
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
        foreach ($result as $key => $row) {
            $product_view_url = '<a target="_blank" href="' . base_url() . 'products/details/' . $row['slug'] . '">View</a>';

            $response[] = array(
                'id' => $key + 1,
                'product_id' => $row["product_id"],
                'product_name' => $row['product_name'],
                'hsn_no' => $row['hsn_no'],
                'category_name' => $row['category_name'],
                'price' => $row['price'],
                'mrp' => $row['mrp'],
                'gst' => ($row["price"] * $row['tax_percent'] / 100) . "(%)",
                'product_view_url' => $product_view_url,

            );
        }

        print_r(json_encode(array('total' => $total, 'rows' => $response)));
    }
    public function get_external_salepurchasebill_ist($status = "")
    {
        $offset = 0;
        $limit = 10;

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }

        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }
        if (isset($_GET['order_status'])) {
            $order_status = $_GET['order_status'];
        }
        if (isset($_GET['retailer_type'])) {
            $retailer_type = $_GET['retailer_type'];
        }

        $this->db->select('e.*');
        $this->db->from('external_purchase_bill e');
        
        if ($order_status != "") {
            $this->db->where('e.type', $order_status);
        } else {
            $this->db->where('e.type', "1");
        }
        if ($retailer_type != "") {
            $this->db->where('e.retailer_type', $retailer_type);
        } else {
            $this->db->where('e.retailer_type', "1");
        }


        if ($this->input->get('search_field')) {
            $search = trim($this->input->get('search_field', true));
            $this->db->group_start();
            $this->db->or_like('e.id', $search);
            $this->db->or_like('e.party_name', $search);
            $this->db->or_like('e.order_number', $search);
            $this->db->or_like('e.date', $search);
            $this->db->group_end();
        }

        // Group by product ID
        $this->db->group_by('e.id');
        $count_query = clone $this->db;
        $total = $count_query->get()->num_rows();


        // Sorting and limiting
        $this->db->order_by('e.date', 'DESC'); // Example: order by total quantity sold
        // $total = $this->db->get()->result_array();
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $this->db->where(" DATE(e.date) >= DATE('" . $_GET['start_date'] . "') ");
            $this->db->where(" DATE(e.date) <= DATE('" . $_GET['end_date'] . "') ");
        }
        $this->db->limit($limit, $offset);

        $result = $this->db->get()->result_array();

        // Prepare response
        $response = array();
        foreach ($result as $key => $row) {
            if ($row['image'] != "" && isFileExists($row['image'])) {
                $image = '<a target="_blank" href="' . base_url() .  $row['image'] . '">View</a>';
            } else {
                $image = "-";
            }
            if ($row['document'] != "" && isFileExists($row['document'])) {
                $document = '<a target="_blank" href="' . base_url() .  $row['document'] . '">View</a>';
            } else {
                $document = "-";
            }
            $invoice_receipt = '<a href="' . base_url("my-account/ext-tax-invoice/") . $row['id'] . "/view" . '" target="_blank">View</a>';
            $delivery_challan = '<a href="' . base_url("my-account/ext-tax-invoice/") . $row['id'] . "/view/1" . '" target="_blank">View</a>';
            $purchase_order = '<a href="' . base_url("my-account/ext-purchase-order/") . $row['id'] . "/1" . '" target="_blank">View</a>';
            $sale_order = '<a href="' . base_url("my-account/ext-purchase-order/") . $row['id']  . '" target="_blank">View</a>';
            $this->db->select_sum('amount');
            $this->db->from('external_products');
            $this->db->where('purchase_id', $row["id"]);
            $this->db->where('type', "1");
            $query = $this->db->get();

            $total_amount = $query->row()->amount;
            $amount = 0;
            $response[] = array(
                'id' => $key + 1,
                'purchase_id' => $row["id"],
                'invoice_number' => $row['invoice_number'],
                'order_number' => $row['order_number'],
                'party_name' => $row['party_name'],
                'date' => date('d-m-Y', strtotime($row['date'])),
                'amount' =>  "Rs. " . $total_amount,
                'invoice_receipt' => $invoice_receipt,
                'purchase_order' => $purchase_order,
                'delivery_challan' => $delivery_challan,
                'sale_order' => $sale_order,

            );
        }


        print_r(json_encode(array('total' => $total, 'rows' => $response)));
    }
    public function get_seller_account_orders_list_filter(
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
            ->join('order_item_mfg_payment_ack as mfg_ack', 'o.id = mfg_ack.order_id', 'left')
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
                $count_res->where('stg.status', $_GET['order_status']);
                if ($_GET['order_status'] == 'delivered') {
                    $count_res->where_not_in("o.id", $issue_order_ids);
                }
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $count_res->where_in('oi.active_status', $status);
        }

        // $count_res->where('o.is_service_category', 0);

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
            ->join('order_item_mfg_payment_ack as mfg_ack', 'o.id = mfg_ack.order_id', 'left')
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
                $search_res->where('stg.status', $_GET['order_status']);

                if ($_GET['order_status'] == 'delivered') {
                    $search_res->where_not_in("o.id", $issue_order_ids);
                }
            }
        }

        if (isset($status) &&  is_array($status) &&  count($status) > 0) {
            $status = array_map('trim', $status);
            $search_res->where_in('oi.active_status', $status);
        }

        // $search_res->where('o.is_service_category', 0);

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
                $tempRow['payment_receipt'] = '<a href="' . base_url("my-account/payment-receipt/") . $row['id'] . "/view" . '" target="_blank">View</a>';
                $tempRow['invoice_receipt'] =  '<a href="' . base_url("my-account/tax-invoice/") . $row['id'] . "/view" . '" target="_blank">View</a>';
                $tempRow['delivery_challan'] =  '<a href="' . base_url("my-account/tax-invoice/") . $row['id'] . "/view/1" . '" target="_blank">View</a>';
                $tempRow['hc_receipt'] =  '<a href="' . base_url("seller/orders/paymentreceipt/") . $row['id'] . "/view" . '" target="_blank">View</a>';
                $tempRow['purchase_order'] = '<a href="' . base_url("my-account/purchase-invoice/") . $row['id'] . '" target="_blank">View</a>';


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
    public function get_external_purchaseout_list()
    {
        $offset = 0;
        $limit = 10;

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }

        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }
        
        if (isset($_GET['retailer_type'])) {
            $retailer_type = $_GET['retailer_type'];
        }

        $this->db->select('e.*');
        $this->db->from('external_payment_out e');
        
        if ($retailer_type) {
            $this->db->where('e.retailer_type', $retailer_type);

        }else{
            $this->db->where('e.retailer_type', '1');

        }



        if ($this->input->get('search_field')) {
            $search = trim($this->input->get('search_field', true));
            $this->db->group_start();
            $this->db->or_like('e.id', $search);
            $this->db->or_like('e.party_name', $search);
            $this->db->or_like('e.order_number', $search);
            $this->db->or_like('e.date', $search);
            $this->db->group_end();
        }

        // Group by product ID
        $this->db->group_by('e.id');
        $count_query = clone $this->db;
        $total = $count_query->get()->num_rows();


        // Sorting and limiting
        $this->db->order_by('e.date', 'DESC'); // Example: order by total quantity sold
        // $total = $this->db->get()->result_array();
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $this->db->where(" DATE(e.date) >= DATE('" . $_GET['start_date'] . "') ");
            $this->db->where(" DATE(e.date) <= DATE('" . $_GET['end_date'] . "') ");
        }
        $this->db->limit($limit, $offset);

        $result = $this->db->get()->result_array();

        // Prepare response
        $response = array();
        foreach ($result as $key => $row) {

            if ($row['transaction_receipt'] != "" && isFileExists($row['transaction_receipt'])) {
                $transaction_receipt = '<a target="_blank" href="' . base_url() .  $row['transaction_receipt'] . '">View</a>';
            } else {
                $transaction_receipt = "-";
            }
            $invoice_receipt = '<a href="' . base_url("my-account/ext-payment-receipt/") . $row['id'] . "/view" . '" target="_blank">View</a>';

            $response[] = array(
                'id' => $key + 1,
                'purchase_id' => $row["id"],
                'invoice_number' => $row['order_number'],
                'party_name' => $row['party_name'],
                'date' => date('d-m-Y', strtotime($row['date'])),
                'amount' =>  "Rs. " . $row['received'],
                'payment_receipt' => $invoice_receipt,

            );
        }


        print_r(json_encode(array('total' => $total, 'rows' => $response)));
    }
    public function get_external_purchasereturn_list()
    {
        $offset = 0;
        $limit = 10;

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }

        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }
        if (isset($_GET['retailer_type'])) {
            $retailer_type = $_GET['retailer_type'];
        }

        $this->db->select('e.*');
        $this->db->from('external_purchase_return e');
       
        if ($retailer_type) {
            $this->db->where('e.retailer_type', $retailer_type);

        }else{
            $this->db->where('e.retailer_type', '1');

        }


        if ($this->input->get('search_field')) {
            $search = trim($this->input->get('search_field', true));
            $this->db->group_start();
            $this->db->or_like('e.id', $search);
            $this->db->or_like('e.party_name', $search);
            $this->db->or_like('e.order_number', $search);
            $this->db->or_like('e.date', $search);
            $this->db->group_end();
        }

        // Group by product ID
        $this->db->group_by('e.id');
        $count_query = clone $this->db;
        $total = $count_query->get()->num_rows();


        // Sorting and limiting
        $this->db->order_by('e.date', 'DESC'); // Example: order by total quantity sold
        // $total = $this->db->get()->result_array();
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {

            $this->db->where(" DATE(e.date) >= DATE('" . $_GET['start_date'] . "') ");
            $this->db->where(" DATE(e.date) <= DATE('" . $_GET['end_date'] . "') ");
        }
        $this->db->limit($limit, $offset);

        $result = $this->db->get()->result_array();

        // Prepare response
        $response = array();
        foreach ($result as $key => $row) {
            if ($row['image'] != "" && isFileExists($row['image'])) {
                $image = '<a target="_blank" href="' . base_url() .  $row['image'] . '">View</a>';
            } else {
                $image = "-";
            }
            if ($row['document'] != "" && isFileExists($row['document'])) {
                $document = '<a target="_blank" href="' . base_url() .  $row['document'] . '">View</a>';
            } else {
                $document = "-";
            }
            $debit_note = '<a href="' . base_url("my-account/ext-debit-note/") . $row['id'] . "/view" . '" target="_blank">View</a>';
            $this->db->select_sum('amount');
            $this->db->from('external_products');
            $this->db->where('purchase_id', $row["id"]);
            $this->db->where('type', "2");
            $query = $this->db->get();

            $total_amount = $query->row()->amount;
            $amount = 0;
            $response[] = array(
                'id' => $key + 1,
                'purchase_id' => $row["id"],
                'return_number' => $row['return_number'],
                'seller_name' => $row['seller_name'],
                'payment_type' => $row['payment_type'],
                'date' => date('d-m-Y', strtotime($row['date'])),
                'total' =>  "Rs. " . $total_amount,
                'debit_note' => $debit_note,

            );
        }


        print_r(json_encode(array('total' => $total, 'rows' => $response)));
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
}
