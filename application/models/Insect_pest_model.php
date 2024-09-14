<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Insect_pest_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }
    public function get_insect_pests($id = NULL, $limit = '', $offset = '', $sort = 'row_order', $order = 'ASC', $has_child_or_item = 'true', $slug = '', $ignore_status = '', $seller_id = '')
    {
        $level = 0;
        if ($ignore_status == 1) {
            $where = (isset($id) && !empty($id)) ? ['c1.id' => $id] : [];
        } else {
            $where = (isset($id) && !empty($id)) ? ['c1.id' => $id, 'c1.status' => 1] : ['c1.status' => 1];
        }

        $this->db->select('c1.*');
        $this->db->where($where);
        if (!empty($slug)) {
            $this->db->where('c1.slug', $slug);
        }
        
        if (!empty($limit) || !empty($offset)) {
            $this->db->offset($offset);
            $this->db->limit($limit);
        }

        $this->db->order_by($sort, $order);

        $parent = $this->db->get('insect_pests c1');
        $insect_pests = $parent->result();
        //echo $this->db->last_query();die;
        
        $count_res = $this->db->count_all_results('insect_pests c1');
        $i = 0;
        foreach ($insect_pests as $p_cat) {
            //$insect_pests[$i]->children = $this->sub_insect_pests($p_cat->id, $level);
            $insect_pests[$i]->text = output_escaping($p_cat->name);
            $insect_pests[$i]->name = output_escaping($insect_pests[$i]->name);
            $insect_pests[$i]->state = ['opened' => true];
            $insect_pests[$i]->icon = "jstree-folder";
            $insect_pests[$i]->level = $level;
            $insect_pests[$i]->image = get_image_url($insect_pests[$i]->image, 'thumb', 'sm');
            $insect_pests[$i]->banner = get_image_url($insect_pests[$i]->banner, 'thumb', 'md');
            $i++;
        }
        if (isset($insect_pests[0])) {
            $insect_pests[0]->total = $count_res;
        }
        return  json_decode(json_encode($insect_pests), 1);
    }

    public function get_seller_insect_pests($seller_id)
    {
        $level = 0;
        $this->db->select('insect_pest_ids');
        $where = 'user_id = ' . $seller_id;
        $this->db->where($where);
        $result = $this->db->get('seller_data')->result_array();
        $count_res = $this->db->count_all_results('seller_data');
        $result = explode(",", $result[0]['insect_pest_ids']);
        $insect_pests =  fetch_details("", 'insect_pests', '*', "", "", "", "", "id", $result);
        $i = 0;
        foreach ($insect_pests as $p_cat) {
            //$insect_pests[$i]['children'] = $this->sub_insect_pests($p_cat['id'], $level);
            $insect_pests[$i]['text'] = output_escaping($p_cat['name']);
            $insect_pests[$i]['name'] = output_escaping($insect_pests[$i]['name']);
            $insect_pests[$i]['state'] = ['opened' => true];
            $insect_pests[$i]['icon'] = "jstree-folder";
            $insect_pests[$i]['level'] = $level;
            $insect_pests[$i]['image'] = get_image_url($insect_pests[$i]['image'], 'thumb', 'md');
            $insect_pests[$i]['banner'] = get_image_url($insect_pests[$i]['banner'], 'thumb', 'md');
            $i++;
        }
        if (isset($insect_pests[0])) {
            $insect_pests[0]['total'] = $count_res;
        }
        return  $insect_pests;
    }

    public function sub_insect_pests($id, $level)
    {
        $level = $level + 1;
        $this->db->select('c1.*');
        $this->db->from('insect_pests c1');
        $this->db->where(['c1.parent_id' => $id, 'c1.status' => 1]);
        $child = $this->db->get();
        $insect_pests = $child->result();
        $i = 0;
        foreach ($insect_pests as $p_cat) {

            $insect_pests[$i]->children = $this->sub_insect_pests($p_cat->id, $level);
            $insect_pests[$i]->text = output_escaping($p_cat->name);
            $insect_pests[$i]->state = ['opened' => true];
            $insect_pests[$i]->level = $level;
            $insect_pests[$i]->image = get_image_url($insect_pests[$i]->image, 'thumb', 'md');
            $insect_pests[$i]->banner = get_image_url($insect_pests[$i]->banner, 'thumb', 'md');
            $i++;
        }
        return $insect_pests;
    }


    public function delete_insect_pest($id)
    {
        $this->db->trans_start();
        $id = escape_array($id);
        $this->db->set('status', NULL)->where('id', $id)->update('insect_pests');
        $this->db->trans_complete();
        $response = FALSE;
        if ($this->db->trans_status() === TRUE) {
            $response = TRUE;
            $this->db->set('insect_pest_id', '1')->where('insect_pest_id', $id)->update('products');
        }
        return $response;
    }


    public function get_insect_pest_list($seller_id = NULL)
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $multipleWhere = '';
        $where = ['status !=' => NULL];

        if (isset($_GET['id']))
            $where['parent_id'] = $_GET['id'];
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
            $multipleWhere = ['`id`' => $search, '`name`' => $search];
        }
        

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }

        if (isset($seller_id) && $seller_id != "") {
            $count_res->where_in('id', $cat_ids);
        }

        $cat_count = $count_res->get('insect_pests')->result_array();
        foreach ($cat_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select(' * ');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $cat_search_res = $search_res->order_by($sort, "asc")->limit($limit, $offset)->get('insect_pests')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($cat_search_res as $row) {

            if (!$this->ion_auth->is_seller()) {
                $operate = '<a href="' . base_url('admin/insect_pest/create_insect_pest' . '?edit_id=' . $row['id']) . '" class=" btn btn-success btn-xs mr-1 mb-1" title="Edit" data-id="' . $row['id'] . '" data-url="admin/insect_pest/create_insect_pest"><i class="fa fa-pen"></i></a>';
                $operate .= '<a class="delete-insect_pest btn btn-danger btn-xs mr-1 mb-1" title="Delete" href="javascript:void(0)" data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
            }
            if ($row['status'] == '1') {
                $tempRow['status'] = '<a class="badge badge-success text-white" >Active</a>';
                if (!$this->ion_auth->is_seller()) {
                    $operate .= '<a class="btn btn-warning btn-xs update_active_status mr-1" data-table="insect_pests" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye-slash"></i></a>';
                }
            } else {
                $tempRow['status'] = '<a class="badge badge-danger text-white" >Inactive</a>';
                if (!$this->ion_auth->is_seller()) {
                    $operate .= '<a class="btn btn-primary mr-1 btn-xs update_active_status" data-table="insect_pests" href="javascript:void(0)" title="Active" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye"></i></a>';
                }
            }

            $tempRow['id'] = $row['id'];
            $tempRow['name'] = '<a href="' . base_url() . 'admin/insect_pest?id=' . $row['id'] . '">' . output_escaping($row['name']) . '</a>';

            if (empty($row['image']) || file_exists(FCPATH  . $row['image']) == FALSE) {
                $row['image'] = base_url() . NO_IMAGE;
                $row['image_main'] = base_url() . NO_IMAGE;
            } else {
                $row['image_main'] = base_url($row['image']);
                $row['image'] = get_image_url($row['image'], 'thumb', 'sm');
            }
            $tempRow['image'] = "<a href='" . $row['image_main'] . "' data-toggle='lightbox' data-gallery='gallery'> <img src='" . $row['image'] . "' class='h-25' ></a>";

            if (empty($row['banner']) || file_exists(FCPATH  . $row['banner']) == FALSE) {
                $row['banner'] = base_url() . NO_IMAGE;
                $row['banner_main'] = base_url() . NO_IMAGE;
            } else {
                $row['banner_main'] = base_url($row['banner']);
                $row['banner'] = get_image_url($row['banner'], 'thumb', 'sm');
            }
            $tempRow['banner'] = "<a href='" . $row['banner_main'] . "' data-toggle='lightbox' data-gallery='gallery'> <img src='" . $row['banner'] . "' class='img-fluid w-50'></a>";

            if (!$this->ion_auth->is_seller()) {
                $tempRow['operate'] = $operate;
            }
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }

    public function add_insect_pest($data)
    {
        $data = escape_array($data);

        $cat_data = [
            'name'              => $data['insect_pest_input_name'],
            'super_category_id' => ($data['super_category_id'] == NULL && isset($data['super_category_id'])) ? '0' : $data['super_category_id'],
            'sub_category_id'   => ($data['sub_category_id'] == NULL && isset($data['sub_category_id'])) ? '0' : $data['sub_category_id'],
            'slug'              => create_unique_slug($data['insect_pest_input_name'], 'insect_pests'),
            'status'            => '1',
        ];

        if (isset($data['edit_insect_pest'])) {
            unset($cat_data['status']);
            if (isset($data['insect_pest_input_image'])) {
                $cat_data['image'] = $data['insect_pest_input_image'];
            }

            $cat_data['banner'] = (isset($data['banner'])) ? $data['banner'] : '';

            $this->db->set($cat_data)->where('id', $data['edit_insect_pest'])->update('insect_pests');
        } else {
            if (isset($data['insect_pest_input_image'])) {
                $cat_data['image'] = $data['insect_pest_input_image'];
            }
            if (isset($data['banner'])) {
                $cat_data['banner'] = (isset($data['banner']) && !empty($data['banner'])) ? $data['banner'] : '';
            }
            $this->db->insert('insect_pests', $cat_data);
        }
    }
}
