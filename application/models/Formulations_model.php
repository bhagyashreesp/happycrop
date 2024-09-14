<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Formulations_model extends CI_Model
{
    function add_formulation($data)
    {

        $data = escape_array($data);
        $formulation_data = [
            'parent_id' => $data['parent_id'],
            'category_id' => $data['category_id'],
            'formulation_id' => $data['formulation_id'],
            'description' => $data['description'],
        ];

        if (isset($data['edit_formulation'])) {
            $this->db->set($formulation_data)->where('id', $data['edit_formulation'])->update('formulations');
        } else {
            $this->db->insert('formulations', $formulation_data);
        }
    }
    public function get_formulation_list()
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
            $multipleWhere = ['id' => $search, 'description' => $search];
        }

        $count_res = $this->db->select(' COUNT(formulations.id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }
        $count_res->join('categories', ' formulations.formulation_id = categories.id ');
        $city_count = $count_res->get('formulations')->result_array();

        foreach ($city_count as $row) {
            $total = $row['total'];
        }

        
        $search_res = $this->db->select(' formulations.*, categories.name as formulation_name ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }
        $search_res->join('categories', ' formulations.formulation_id = categories.id ');
        $city_search_res = $search_res->order_by($sort, "asc")->limit($limit, $offset)->get('formulations')->result_array();
        
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($city_search_res as $row) {
            $row = output_escaping($row);

            $operate = ' <a href="javascript:void(0)" class="edit_btn btn btn-primary btn-xs mr-1 mb-1" title="Edit" data-id="' . $row['id'] . '" data-url="admin/formulations/"><i class="fa fa-pen"></i></a>';
            $operate .= ' <a  href="javascript:void(0)" class="btn btn-danger btn-xs mr-1 mb-1" title="Delete" data-id="' . $row['id'] . '" id="delete-formulation" ><i class="fa fa-trash"></i></a>';
            $tempRow['id'] = $row['id'];
            $tempRow['formulation_name'] = $row['formulation_name'];
            $tempRow['description'] = $row['description'];
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
}
