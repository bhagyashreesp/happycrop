<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Webinar_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }
    
    function add_webinar($data)
    {
        $description = $data['description'];
        $data = escape_array($data);
        $webinar_data = [
            'title'         => $data['title'],
            'date'          => $data['date'],
            'time'          => $data['time'],
            'date_time'     => $data['date'].' '. $data['time'].':00',
            'speakers'      => $data['speakers'],
            'organization'  => $data['organization'],
            'join_link'     => $data['join_link'],
            'recording_link'=> $data['recording_link'],
            'description'   => $description,
            'status'        => $data['status'],
        ];

        if(isset($data['edit_id'])) {
            $webinar_data['updated_by']       = $this->ion_auth->get_user_id();
            $webinar_data['updated_group_id'] = ($this->ion_auth->is_admin()) ? 1 : 2;
            $webinar_data['updated_date']     = date('Y-m-d H:i:s');
            
            $this->db->set($webinar_data)->where('id', $data['edit_id'])->update('webinars');
        } else {
            $webinar_data['user_id']      = $this->ion_auth->get_user_id();
            $webinar_data['group_id']     = ($this->ion_auth->is_admin()) ? 1 : 2;
            $webinar_data['created_date'] = date('Y-m-d H:i:s');
            
            $this->db->insert('webinars', $webinar_data);
        }
    }
    
    function create_slug($data)
    {
        $data = escape_array($data);
        $this->db->set($data)->where('id', $data['id'])->update('webinars');
    }

    function get_webinars_list()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'w.id';
        $order = 'DESC';
        $multipleWhere = '';
        //$where = ['u.active' => 1];

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "w.id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = ['w.`id`' => $search, 'w.`title`' => $search,'w.`speakers`' => $search];
        }
        
        if(isset($_GET['search_field']) and $_GET['search_field'] != '') {
            $search_field = $_GET['search_field'];
            $multipleWhere = ['w.`id`' => $search_field, 
                              'w.`title`' => $search_field,];
        }

        $count_res = $this->db->select(' COUNT(w.id) as `total` ')->join('users u', ' w.user_id = u.id ')->join('users_groups ug', ' ug.user_id = u.id ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_start();
            $count_res->or_like($multipleWhere);
            $count_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            //$where['ug.group_id'] = '2';
            //$count_res->where($where);
        }
        
        if($status != '')
        {
           $count_res->where('w.status', $status);
        }

        $offer_count = $count_res->get('webinars w')->result_array();
        
        foreach ($offer_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' w.*, u.username ')->join('users u', ' w.user_id = u.id ')->join('users_groups ug', ' ug.user_id = u.id ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            //$where['ug.group_id'] = '2';
            //$search_res->where($where);
        }
        
        if($status != '')
        {
           $search_res->where('w.status', $status);
        }
        

        $offer_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('webinars w')->result_array();
        
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($offer_search_res as $row) {
            $row = output_escaping($row);
            //$operate = " <a href='manage-new-webinar?edit_id=" . $row['user_id'] . "' data-id=" . $row['user_id'] . " class='btn btn-success btn-xs mr-1 mb-1' title='Edit' ><i class='fa fa-pen'></i></a>";
            $operate = " <a href='manage-new-webinar?edit_id=" . $row['id'] . "' data-id=" . $row['id'] . " class='btn btn-success btn-xs mr-1 mb-1' title='Edit' ><i class='fa fa-pen'></i></a>";
            $operate .= '<a  href="javascript:void(0)" class="delete-webinar btn btn-danger btn-xs mr-1 mb-1" title="Delete" data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
            
            $tempRow['id'] = $row['id'];
            $tempRow['title'] = $row['title'];
            $tempRow['date'] = date('d/m/Y', strtotime($row['date']));
            $tempRow['time'] = date('g:i A', strtotime($row['date_time']));
            $tempRow['speakers'] = $row['speakers'];
            $tempRow['organization'] = $row['organization'];
            $tempRow['join_link'] = $row['join_link'];
            $tempRow['username'] = $row['username'];
            $tempRow['company_name'] = $row['company_name'];
            
            // webinar status
            if ($row['status'] == 1)
                $tempRow['status'] = "<label class='badge badge-success'>Active</label>";
            else if ($row['status'] == 0)
                $tempRow['status'] = "<label class='badge badge-danger'>Inactive</label>";
            else if ($row['status'] == 7)
                $tempRow['status'] = "<label class='badge badge-danger'>Removed</label>";

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    
    function get_seller_webinars_list($seller_id)
    {
        $offset = 0;
        $limit = 10;
        $sort = 'w.id';
        $order = 'DESC';
        $multipleWhere = '';
        //$where = ['u.active' => 1];

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "w.id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = ['w.`id`' => $search, 'w.`title`' => $search,'w.`speakers`' => $search];
        }
        
        if(isset($_GET['search_field']) and $_GET['search_field'] != '') {
            $search_field = $_GET['search_field'];
            $multipleWhere = ['w.`id`' => $search_field, 
                              'w.`title`' => $search_field,];
        }
        
        

        $count_res = $this->db->select(' COUNT(w.id) as `total` ')->join('users u', ' w.user_id = u.id ')->join('users_groups ug', ' ug.user_id = u.id ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_start();
            $count_res->or_like($multipleWhere);
            $count_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            //$where['ug.group_id'] = '2';
            //$count_res->where($where);
        }
        
        if($status != '')
        {
           $count_res->where('w.status', $status);
        }
        
        if(isset($seller_id)) {
            $count_res->where('w.user_id', $seller_id);
        }

        $offer_count = $count_res->get('webinars w')->result_array();
        
        foreach ($offer_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' w.*, u.username ')->join('users u', ' w.user_id = u.id ')->join('users_groups ug', ' ug.user_id = u.id ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            //$where['ug.group_id'] = '2';
            //$search_res->where($where);
        }
        
        if($status != '')
        {
           $search_res->where('w.status', $status);
        }
        
        if(isset($seller_id)) {
            $search_res->where('w.user_id', $seller_id);
        }
        
        $offer_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('webinars w')->result_array();
        
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($offer_search_res as $row) {
            $row = output_escaping($row);
            //$operate = " <a href='manage-new-webinar?edit_id=" . $row['user_id'] . "' data-id=" . $row['user_id'] . " class='btn btn-success btn-xs mr-1 mb-1' title='Edit' ><i class='fa fa-pen'></i></a>";
            $operate = " <a href='manage-new-webinar?edit_id=" . $row['id'] . "' data-id=" . $row['id'] . " class='btn btn-success btn-xs mr-1 mb-1' title='Edit' ><i class='fa fa-pen'></i></a>";
            $operate .= '<a  href="javascript:void(0)" class="delete-webinar btn btn-danger btn-xs mr-1 mb-1" title="Delete" data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
            
            $tempRow['id'] = $row['id'];
            $tempRow['title'] = $row['title'];
            $tempRow['date'] = date('d/m/Y', strtotime($row['date']));
            $tempRow['time'] = date('g:i A', strtotime($row['date_time']));
            $tempRow['speakers'] = $row['speakers'];
            $tempRow['organization'] = $row['organization'];
            $tempRow['join_link'] = $row['join_link'];
            $tempRow['username'] = $row['username'];
            $tempRow['company_name'] = $row['company_name'];
            
            // webinar status
            if ($row['status'] == 1)
                $tempRow['status'] = "<label class='badge badge-success'>Active</label>";
            else if ($row['status'] == 0)
                $tempRow['status'] = "<label class='badge badge-danger'>Inactive</label>";
            else if ($row['status'] == 7)
                $tempRow['status'] = "<label class='badge badge-danger'>Removed</label>";

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    
    public function get_front_webinar_list()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'w.id';
        $order = 'ASC';
        $multipleWhere = '';
        //$where = ['u.active' => 1];

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "w.id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = ['w.`id`' => $search, 'w.`title`' => $search,'w.`speakers`' => $search];
        }
        
        if(isset($_GET['search_field']) and $_GET['search_field'] != '') {
            $search_field = $_GET['search_field'];
            $multipleWhere = ['w.`id`' => $search_field, 
                              'w.`title`' => $search_field,];
        }

        $count_res = $this->db->select(' COUNT(w.id) as `total` ')->join('users u', ' w.user_id = u.id ')->join('users_groups ug', ' ug.user_id = u.id ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_start();
            $count_res->or_like($multipleWhere);
            $count_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            //$where['ug.group_id'] = '2';
            //$count_res->where($where);
        }
        
        $count_res->where('w.status', 1);
        $count_res->where('w.date >=', date('Y-m-d'));
        
        $offer_count = $count_res->get('webinars w')->result_array();
        
        foreach ($offer_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' w.*, u.username ')->join('users u', ' w.user_id = u.id ')->join('users_groups ug', ' ug.user_id = u.id ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            //$where['ug.group_id'] = '2';
            //$search_res->where($where);
        }
        
        $search_res->where('w.status', 1);
        $search_res->where('w.date >=', date('Y-m-d'));
        
        $offer_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('webinars w')->result_array();
                
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($offer_search_res as $row) {
            $row = output_escaping($row);
            //$operate = " <a href='manage-new-webinar?edit_id=" . $row['user_id'] . "' data-id=" . $row['user_id'] . " class='btn btn-success btn-xs mr-1 mb-1' title='Edit' ><i class='fa fa-pen'></i></a>";
            //$operate = " <a href='manage-new-webinar?edit_id=" . $row['id'] . "' data-id=" . $row['id'] . " class='btn btn-success btn-xs mr-1 mb-1' title='Edit' ><i class='fa fa-pen'></i></a>";
            //$operate .= '<a  href="javascript:void(0)" class="delete-webinar btn btn-danger btn-xs mr-1 mb-1" title="Delete" data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
            
            $tempRow['id'] = $row['id'];
            $tempRow['title'] = $row['title'];
            $tempRow['date'] = date('d/m/Y', strtotime($row['date']));
            $tempRow['time'] = date('g:i A', strtotime($row['date_time']));
            $tempRow['speakers'] = $row['speakers'];
            $tempRow['organization'] = $row['organization'];
            $tempRow['join_link'] = $row['join_link'];
            $tempRow['description'] = $row['description'];
            $tempRow['username'] = $row['username'];
            $tempRow['company_name'] = $row['company_name'];
            
            $actions = '';
            
            $tempRow['description'] .= ($this->valid_URL($row['join_link'])) ? '<br/><a class="btn btn-primary btn-sm mr-1 mb-1" href="'.$row['join_link'].'" target="_blank">Join</a>' : '';
            
            $tempRow['actions'] = $actions;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    
    function valid_URL($url){
        return preg_match('%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu', $url);
    }
}
