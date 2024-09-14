<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Category_blocks_model extends CI_Model
{
    function add_category_block($data)
    {

        $data = escape_array($data);
        $category_block_data = [
            'title'             => $data['title'],
            'short_description' => $data['short_description'],
            'super_category'    => $data['super_category'],
            'show_type'         => $data['show_type'],
            'main_category'     => $data['main_category'],
            'sub_categories'    => (isset($data['sub_categories']) && !empty($data['sub_categories'])) ? implode(',', $data['sub_categories']) : null,
        ];

        if (isset($data['edit_category_block'])) {
            $this->db->set($category_block_data)->where('id', $data['edit_category_block'])->update('category_blocks');
        } else {
            $this->db->insert('category_blocks', $category_block_data);
        }
    }
    public function get_category_block_list()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'u.id';
        $order = 'ASC';
        $multipleWhere = '';

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
            $multipleWhere = ['id' => $search, 'title' => $search, 'short_description' => $search];
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $city_count = $count_res->get('category_blocks')->result_array();

        foreach ($city_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $city_search_res = $search_res->order_by($sort, "asc")->limit($limit, $offset)->get('category_blocks')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($city_search_res as $row) {
            $row = output_escaping($row);

            $operate = ' <a href="javascript:void(0)" class="edit_btn btn btn-primary btn-xs mr-1 mb-1" title="Edit" data-id="' . $row['id'] . '" data-url="admin/Category_blocks/"><i class="fa fa-pen"></i></a>';
            $operate .= ' <a  href="javascript:void(0)" class="btn btn-danger btn-xs mr-1 mb-1 delete-category_block" title="Delete" data-id="' . $row['id'] . '" id="delete-category_block" ><i class="fa fa-trash"></i></a>';
            $tempRow['id'] = $row['id'];
            $tempRow['title'] = $row['title'];
            //$tempRow['short_description'] = $row['short_description'];
            $tempRow['date'] = $row['date_added'];
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
}
