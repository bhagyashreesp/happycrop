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
}
