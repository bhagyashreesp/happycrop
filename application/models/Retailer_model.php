<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Retailer_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }
    
    function add_custom_retailer($data, $profile = [], $com_data = [])
    {
        $data = escape_array($data);
        $profile = (!empty($profile)) ? escape_array($profile) : [];
        $com_data = (!empty($com_data)) ? escape_array($com_data) : [];
        
        $retailer_data = [
            'user_id' => $data['user_id'],
            'status'  => (isset($data['status']) && $data['status'] != "") ? $data['status'] : 2,
        ];
        
        if (!empty($profile)) {
            
            //$password = $this->ion_auth_model->hash_password($profile['password']);

            $retailer_profile = [
                'username' => $profile['name'],
                'email' => $profile['email'],
                //'password' => $password,
                'mobile' => $profile['mobile'],
                'address' => $profile['address'],
                'latitude' => $profile['latitude'],
                'longitude' => $profile['longitude'],
            ];
        }
        
        if (isset($data['edit_retailer_data_id'])) {
            if ($this->db->set($retailer_profile)->where('id', $data['user_id'])->update('users')) {
                $this->db->set($retailer_data)->where('id', $data['edit_retailer_data_id'])->update('retailer_data');
                return true;
            } else {
                return false;
            }
        } else {
            
            $this->db->insert('retailer_data', $retailer_data);
            $insert_id = $this->db->insert_id();
            
            if (!empty($insert_id)) {
                return  $insert_id;
            } else {
                return false;
            }
        }
    }
    
    function add_retailer($data, $profile = [], $com_data = [])
    {
        $data = escape_array($data);
        $profile = (!empty($profile)) ? escape_array($profile) : [];
        $com_data = (!empty($com_data)) ? escape_array($com_data) : [];
        
        $retailer_data = [
            'user_id' => $data['user_id'],
            'national_identity_card' => $data['national_identity_card'],
            'address_proof' => $data['address_proof'],
            'logo' => $data['store_logo'],
            'status' => (isset($data['status']) && $data['status'] != "") ? $data['status'] : 2,
            'pan_number' => $data['pan_number'],
            'tax_number' => $data['tax_number'],
            'tax_name' => $data['tax_name'],
            'bank_name' => $data['bank_name'],
            'bank_code' => $data['bank_code'],
            'account_name' => $data['account_name'],
            'account_number' => $data['account_number'],
            'store_description' => $data['store_description'],
            'store_url' => $data['store_url'],
            'store_name' => $data['store_name'],
            'commission' => (isset($data['global_commission']) && $data['global_commission'] != "") ? $data['global_commission'] : 0,
            'category_ids' => (isset($data['categories']) && $data['categories'] != "") ? $data['categories'] : null,
            'permissions' => (isset($data['permissions']) && $data['permissions'] != "") ? json_encode($data['permissions']) : null,
            'slug' => $data['slug']
        ];
        if (isset($data['categories']) && $data['categories'] == "retailer_profile") {
            unset($retailer_data['category_ids']);
            unset($retailer_data['permissions']);
        }

        if (!empty($profile)) {
            
            //$password = $this->ion_auth_model->hash_password($profile['password']);

            $retailer_profile = [
                'username' => $profile['name'],
                'email' => $profile['email'],
                //'password' => $password,
                'mobile' => $profile['mobile'],
                'address' => $profile['address'],
                'latitude' => $profile['latitude'],
                'longitude' => $profile['longitude'],
            ];
        }
        if (isset($data['edit_retailer_data_id'])) {
            if (!empty($com_data)) {
                // process update commissions and categories
                for ($i = 0; $i < count($com_data); $i++) {
                    $where = "";
                    if (is_exist(['category_id' => $com_data[$i]['category_id'], 'retailer_id' => $com_data[$i]['retailer_id']], 'retailer_commission')) {
                        $update_com = ['commission' => $com_data[$i]['commission']];
                        $where = "category_id = " . $com_data[$i]['category_id'] . " and retailer_id=" . $com_data[$i]['retailer_id'];
                        $this->db->set($update_com)->where($where)->update('retailer_commission');
                    } else {
                        $this->db->insert('retailer_commission', $com_data[$i]);
                    }
                }
            }
            if ($this->db->set($retailer_profile)->where('id', $data['user_id'])->update('users')) {
                $this->db->set($retailer_data)->where('id', $data['edit_retailer_data_id'])->update('retailer_data');
                return true;
            } else {
                return false;
            }
        } else {
            if (!empty($com_data)) {
                $this->db->insert_batch('retailer_commission', $com_data);
            }
            $this->db->insert('retailer_data', $retailer_data);
            $insert_id = $this->db->insert_id();
            if (!empty($insert_id)) {
                return  $insert_id;
            } else {
                return false;
            }
        }
    }

    function create_slug($data)
    {
        $data = escape_array($data);
        $this->db->set($data)->where('id', $data['id'])->update('retailer_data');
    }

    function get_retailers_list($status = '')
    {
        $offset = 0;
        $limit = 10;
        $sort = 'u.id';
        $order = 'DESC';
        $multipleWhere = '';
        $where = ['u.active' => 1];

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "u.id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = ['u.`id`' => $search, 'u.`username`' => $search, 'u.`email`' => $search, 'u.`mobile`' => $search, 'u.`address`' => $search, 'u.`balance`' => $search];
        }
        
        if(isset($_GET['search_field']) and $_GET['search_field'] != '') {
            $search_field = $_GET['search_field'];
            $multipleWhere = ['u.`id`' => $search_field, 
                              'u.`username`' => $search_field, 
                              'u.`email`' => $search_field, 
                              'u.`mobile`' => $search_field, 
                              'sd.`company_name`' => $search_field, 
                              'ct.`name`' => $search_field];
        }

        $count_res = $this->db->select(' COUNT(u.id) as `total` ')->join('users_groups ug', ' ug.user_id = u.id ')->join('retailer_data sd', ' sd.user_id = u.id ')->join('cities ct', ' sd.city_id = ct.id ','left');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_start();
            $count_res->or_like($multipleWhere);
            $count_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $where['ug.group_id'] = '2';
            $count_res->where($where);
        }
        
        if($status != '')
        {
           $count_res->where('sd.status', $status);
        }

        $offer_count = $count_res->get('users u')->result_array();
        foreach ($offer_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' u.*,sd.*, ct.name as city_name ')->join('users_groups ug', ' ug.user_id = u.id ')->join('retailer_data sd', ' sd.user_id = u.id ')->join('cities ct', ' sd.city_id = ct.id ','left');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $where['ug.group_id'] = '2';
            $search_res->where($where);
        }
        
        if($status != '')
        {
           $search_res->where('sd.status', $status);
        }

        $offer_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('users u')->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($offer_search_res as $row) {
            $row = output_escaping($row);
            //$operate = " <a href='manage-new-retailer?edit_id=" . $row['user_id'] . "' data-id=" . $row['user_id'] . " class='btn btn-success btn-xs mr-1 mb-1' title='Edit' ><i class='fa fa-pen'></i></a>";
            $operate = " <a href='".base_url()."admin/retailers/edit-retailer?edit_id=" . $row['user_id'] . "' data-id=" . $row['user_id'] . " class='btn btn-success btn-xs mr-1 mb-1' title='Edit' ><i class='fa fa-pen'></i></a>";
            $operate .= '<a  href="javascript:void(0)" class="delete-retailers btn btn-danger btn-xs mr-1 mb-1" title="Delete"   data-id="' . $row['user_id'] . '" ><i class="fa fa-trash"></i></a>';
            if ($row['status'] == '1' || $row['status'] == '0' || $row['status'] == '2') {
                $operate .= '<a  href="javascript:void(0)" class="remove-retailers btn btn-warning btn-xs mr-1 mb-1" title="Remove Retailer"  data-id="' . $row['user_id'] . '" data-retailer_status="' . $row['status'] . '" ><i class="fas fa-user-slash"></i></a>';
            } else if ($row['status'] == '7') {
                $operate .= '<a  href="javascript:void(0)" class="remove-retailers btn btn-primary btn-xs mr-1 mb-1" title="Restore Retailer"  data-id="' . $row['user_id'] . '" data-retailer_status="' . $row['status'] . '" ><i class="fas fa-user"></i></a>';
            }
            $tempRow['id'] = $row['id'];
            $tempRow['ret_no'] = 'HCR'.str_pad($row['ret_no'],5,"0", STR_PAD_LEFT);
            $tempRow['name'] = $row['username'];
            $tempRow['email'] = $row['email'];
            $tempRow['mobile'] = $row['mobile'];
            $tempRow['address'] = $row['address'];
            $tempRow['company_name'] = $row['company_name'];
            $tempRow['city_name'] = $row['city_name'];
            //$tempRow['store_url'] = $row['store_url'];
            //$tempRow['store_description'] = $row['store_description'];
            $tempRow['account_number'] = $row['account_number'];
            $tempRow['account_name'] = $row['account_name'];
            $tempRow['bank_code'] = $row['bank_code'];
            $tempRow['bank_name'] = $row['bank_name'];
            $tempRow['latitude'] = $row['latitude'];
            $tempRow['longitude'] = $row['longitude'];
            $tempRow['tax_name'] = $row['tax_name'];
            $tempRow['rating'] = ' <p> (' . intval($row['rating']) . '/' . $row['no_of_ratings'] . ') </p>';;
            $tempRow['tax_number'] = $row['tax_number'];
            $tempRow['pan_number'] = $row['pan_number'];

            // retailer status
            if ($row['status'] == 2)
                $tempRow['status'] = "<label class='badge badge-warning'>Not-Approved</label>";
            else if ($row['status'] == 1)
                $tempRow['status'] = "<label class='badge badge-success'>Approved</label>";
            else if ($row['status'] == 0)
                $tempRow['status'] = "<label class='badge badge-danger'>Deactive</label>";
            else if ($row['status'] == 7)
                $tempRow['status'] = "<label class='badge badge-danger'>Removed</label>";

            $tempRow['category_ids'] = $row['category_ids'];

            //$row['logo'] = base_url() . $row['logo'];
            //$tempRow['logo'] = '<div class="mx-auto product-image"><a href=' . $row['logo'] . ' data-toggle="lightbox" data-gallery="gallery"><img src=' . $row['logo'] . ' class="img-fluid rounded"></a></div>';

            //$row['national_identity_card'] = get_image_url($row['national_identity_card']);
            //$tempRow['national_identity_card'] = '<div class="mx-auto product-image"><a href=' . $row['national_identity_card'] . ' data-toggle="lightbox" data-gallery="gallery"><img src=' . $row['national_identity_card'] . ' class="img-fluid rounded"></a></div>';

            //$row['address_proof'] = get_image_url($row['address_proof']);
           // $tempRow['address_proof'] = '<div class="mx-auto product-image"><a href=' . $row['address_proof'] . ' data-toggle="lightbox" data-gallery="gallery"><img src=' . $row['address_proof'] . ' class="img-fluid rounded"></a></div>';

            //$tempRow['permissions'] = $row['permissions'];
            //$tempRow['balance'] =  $row['balance'] == null || $row['balance'] == 0 || empty($row['balance']) ? "0" : $row['balance'];
            $tempRow['date'] = $row['created_at'];
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    function update_balance($amount, $retailer_id, $action)
    {
        /**
         * @param
         * action = deduct / add
         */

        if ($action == "add") {
            $this->db->set('balance', 'balance+' . $amount, FALSE);
        } elseif ($action == "deduct") {
            $this->db->set('balance', 'balance-' . $amount, FALSE);
        }
        return $this->db->where('id', $retailer_id)->update('users');
    }
    public function get_retailers($zipcode_id = "", $limit = NULL, $offset = '', $sort = 'u.id', $order = 'DESC', $search = NULL, $filter = [])
    {
        $multipleWhere = '';
        $where = ['u.active' => 1, 'sd.status' => 1, ' p.status' => 1];
        if (isset($filter) && !empty($filter['slug']) && $filter['slug'] != "") {
            $where['sd.slug'] = $filter['slug'];
        }

        if (isset($search) and $search != '') {
            $multipleWhere = ['u.`id`' => $search, 'u.`username`' => $search, 'u.`email`' => $search, 'u.`mobile`' => $search, 'u.`address`' => $search, 'u.`balance`' => $search, 'sd.`store_name`' => $search, 'sd.`store_url`' => $search, 'sd.`store_description`' => $search];
        }

        $count_res = $this->db->select(' COUNT(DISTINCT u.id) as `total` ')->join('users_groups ug', ' ug.user_id = u.id ')->join('retailer_data sd', ' sd.user_id = u.id ')->join('products p', ' p.retailer_id = u.id ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_start();
            $count_res->or_like($multipleWhere);
            $count_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $where['ug.group_id'] = '4';
            $count_res->where($where);
        }
        if (isset($zipcode_id) && !empty($zipcode_id) && $zipcode_id != "") {
            $this->db->group_Start();
            $where2 = "((deliverable_type='2' and FIND_IN_SET('$zipcode_id', deliverable_zipcodes)) or deliverable_type = '1') OR (deliverable_type='3' and NOT FIND_IN_SET('$zipcode_id', deliverable_zipcodes)) ";
            $this->db->where($where2);
            $this->db->group_End();
        }

        $offer_count = $count_res->get('users u')->result_array();
        foreach ($offer_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' u.*,sd.*,u.id as retailer_id ')->join('users_groups ug', ' ug.user_id = u.id ')->join('retailer_data sd', ' sd.user_id = u.id ')->join('products p', ' p.retailer_id = u.id ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $where['ug.group_id'] = '4';
            $search_res->where($where);
        }

        if (isset($zipcode_id) && !empty($zipcode_id) && $zipcode_id != "") {
            $this->db->group_Start();
            $where2 = "((deliverable_type='2' and FIND_IN_SET('$zipcode_id', deliverable_zipcodes)) or deliverable_type = '1') OR (deliverable_type='3' and NOT FIND_IN_SET('$zipcode_id', deliverable_zipcodes)) ";
            $this->db->where($where2);
            $this->db->group_End();
        }

        $offer_search_res = $search_res->group_by('u.id')->order_by($sort, $order)->limit($limit, $offset)->get('users u')->result_array();

        $bulkData = array();
        $bulkData['error'] = (empty($offer_search_res)) ? true : false;
        $bulkData['message'] = (empty($offer_search_res)) ? 'Retailer(s) does not exist' : 'Retailer retrieved successfully';
        $bulkData['total'] = (empty($offer_search_res)) ? 0 : $total;
        $rows = $tempRow = array();

        foreach ($offer_search_res as $row) {
            $row = output_escaping($row);
            $tempRow['retailer_id'] = $row['retailer_id'];
            $tempRow['retailer_name'] = $row['username'];
            $tempRow['email'] = $row['email'];
            $tempRow['mobile'] = $row['mobile'];
            $tempRow['slug'] = $row['slug'];
            $tempRow['retailer_rating'] = $row['rating'];
            $tempRow['no_of_ratings'] = $row['no_of_ratings'];
            $tempRow['store_name'] = $row['store_name'];
            $tempRow['store_url'] = $row['store_url'];
            $tempRow['store_description'] = $row['store_description'];
            $tempRow['retailer_profile'] = base_url() . $row['logo'];
            $tempRow['balance'] =  $row['balance'] == null || $row['balance'] == 0 || empty($row['balance']) ? "0" : number_format($row['balance'], 2);
            $rows[] = $tempRow;
        }
        $bulkData['data'] = $rows;
        if (!empty($bulkData)) {
            return $bulkData;
        } else {
            return $bulkData;
        }
    }

    public function get_retailer_commission_data($id)
    {
        $data = $this->db->select("sc.*,c.name")
            ->join('categories c', 'c.id = sc.category_id')
            ->where('retailer_id', $id)
            ->order_by('category_id', 'ASC')
            ->get('retailer_commission sc')->result_array();

        if (!empty($data)) {
            return $data;
        } else {
            return false;
        }
    }

    function settle_retailer_commission()
    {
        $date = date('Y-m-d');
        $settings = get_settings('system_settings', true);
        $where = "oi.active_status='delivered' AND is_credited=0 and  DATE_ADD(DATE_FORMAT(oi.date_added, '%Y-%m-%d'), INTERVAL " . $settings['max_product_return_days'] . " DAY) = '" . $date . "'";
        // $where = "oi.active_status='delivered' AND is_credited=0 and  DATE_ADD(DATE_FORMAT(oi.date_added, '%Y-%m-%d'), INTERVAL " . $settings['max_product_return_days'] . " DAY) = '2021-08-25'";
        $data = $this->db->select("c.id as category_id, oi.id,date(oi.date_added) as order_date,oi.order_id,oi.product_variant_id,oi.retailer_id,oi.sub_total ")
            ->join('product_variants pv', 'pv.id=oi.product_variant_id', 'left')
            ->join('products p', 'p.id=pv.product_id')
            ->join('categories c', 'p.category_id=c.id')
            ->where($where)
            ->get('order_items oi')->result_array();
        $wallet_updated = false;
        foreach ($data as $row) {
            $cat_com = fetch_details(['retailer_id' => $row['retailer_id'], 'category_id' => $row['category_id']], 'retailer_commission', 'commission');
            if (!empty($cat_com)) {
                $commission_pr = $cat_com[0]['commission'];
            } else {
                $global_comm = fetch_details(['user_id' => $row['retailer_id']], 'retailer_data', 'commission');
                $commission_pr = $global_comm[0]['commission'];
            }

            $commission_amt = $row['sub_total'] / 100 * $commission_pr;
            $transfer_amt = $row['sub_total'] - $commission_amt;

            $response = update_wallet_balance('credit', $row['retailer_id'], $transfer_amt, 'Commission Amount Credited for Order Item ID  : ' . $row['id']);
            if ($response['error'] == false) {
                update_details(['is_credited' => 1], ['id' => $row['id']], 'order_items');
                $wallet_updated = true;
            } else {
                $wallet_updated = false;
            }
        }
        if ($wallet_updated == TRUE) {
            $retailer_ids = array_values(array_unique(array_column($data, "retailer_id")));
            foreach ($retailer_ids as $retailer) {
                $settings = get_settings('system_settings', true);
                $app_name = isset($settings['app_name']) && !empty($settings['app_name']) ? $settings['app_name'] : '';
                $user_res = fetch_details(['id' => $retailer], 'users', 'username,fcm_id,email');
                send_mail($user_res[0]['email'], 'Commission Amount Credited', 'Commission Amount Credited, which orders are delivered. Please take note of it! Regards ' . $app_name . '');
                $fcm_ids = array();
                if (!empty($user_res[0]['fcm_id'])) {
                    $fcmMsg = array(
                        'title' => "Commission Amount Credited",
                        'body' => 'Hello Dear ' . $user_res[0]['username'] . ' Commission Amount Credited, which orders are delivered. Please take note of it! Regards ' . $app_name . '',
                        'type' => "commission"
                    );
                    $fcm_ids[0][] = $user_res[0]['fcm_id'];
                    send_notification($fcmMsg, $fcm_ids);
                }
            }
        }
    }
}
