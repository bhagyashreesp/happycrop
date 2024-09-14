<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Super_category_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'function_helper']);
    }
    public function get_super_categories($id = NULL, $limit = '', $offset = '', $sort = 'row_order', $order = 'ASC', $has_child_or_item = 'true', $slug = '', $ignore_status = '', $seller_id = '')
    {
        $level = 0;
        if ($ignore_status == 1) {
            $where = (isset($id) && !empty($id)) ? ['c1.id' => $id] : ['c1.parent_id' => 0];
        } else {
            $where = (isset($id) && !empty($id)) ? ['c1.id' => $id, 'c1.status' => 1] : ['c1.parent_id' => 0, 'c1.status' => 1];
        }

        $this->db->select('c1.*');
        $this->db->where($where);
        if (!empty($slug)) {
            $this->db->where('c1.slug', $slug);
        }
        if ($has_child_or_item == 'false') {
            $this->db->join('super_categories c2', 'c2.parent_id = c1.id', 'left');
            $this->db->join('products p', ' p.super_category_id = c1.id', 'left');
            $this->db->group_start();
            $this->db->or_where(['c1.id ' => ' p.super_category_id ', ' c2.parent_id ' => ' c1.id '], NULL, FALSE);
            $this->db->group_End();
            $this->db->group_by('c1.id');
        }

        if (!empty($limit) || !empty($offset)) {
            $this->db->offset($offset);
            $this->db->limit($limit);
        }

        $this->db->order_by($sort, $order);

        $parent = $this->db->get('super_categories c1');
        $super_categories = $parent->result();
        $count_res = $this->db->count_all_results('super_categories c1');
        $i = 0;
        foreach ($super_categories as $p_cat) {
            $super_categories[$i]->children = $this->sub_super_categories($p_cat->id, $level);
            $super_categories[$i]->text = output_escaping($p_cat->name);
            $super_categories[$i]->name = output_escaping($super_categories[$i]->name);
            $super_categories[$i]->state = ['opened' => true];
            $super_categories[$i]->icon = "jstree-folder";
            $super_categories[$i]->level = $level;
            $super_categories[$i]->image = get_image_url($super_categories[$i]->image, 'thumb', 'sm');
            $super_categories[$i]->banner = get_image_url($super_categories[$i]->banner, 'thumb', 'md');
            $i++;
        }
        if (isset($super_categories[0])) {
            $super_categories[0]->total = $count_res;
        }
        return  json_decode(json_encode($super_categories), 1);
    }

    public function get_seller_super_categories($seller_id)
    {
        $level = 0;
        $this->db->select('super_category_ids');
        $where = 'user_id = ' . $seller_id;
        $this->db->where($where);
        $result = $this->db->get('seller_data')->result_array();
        $count_res = $this->db->count_all_results('seller_data');
        $result = explode(",", $result[0]['super_category_ids']);
        $super_categories =  fetch_details("", 'super_categories', '*', "", "", "", "", "id", $result);
        $i = 0;
        foreach ($super_categories as $p_cat) {
            $super_categories[$i]['children'] = $this->sub_super_categories($p_cat['id'], $level);
            $super_categories[$i]['text'] = output_escaping($p_cat['name']);
            $super_categories[$i]['name'] = output_escaping($super_categories[$i]['name']);
            $super_categories[$i]['state'] = ['opened' => true];
            $super_categories[$i]['icon'] = "jstree-folder";
            $super_categories[$i]['level'] = $level;
            $super_categories[$i]['image'] = get_image_url($super_categories[$i]['image'], 'thumb', 'md');
            $super_categories[$i]['banner'] = get_image_url($super_categories[$i]['banner'], 'thumb', 'md');
            $i++;
        }
        if (isset($super_categories[0])) {
            $super_categories[0]['total'] = $count_res;
        }
        return  $super_categories;
    }

    public function sub_super_categories($id, $level)
    {
        $level = $level + 1;
        $this->db->select('c1.*');
        $this->db->from('super_categories c1');
        $this->db->where(['c1.parent_id' => $id, 'c1.status' => 1]);
        $child = $this->db->get();
        $super_categories = $child->result();
        $i = 0;
        foreach ($super_categories as $p_cat) {

            $super_categories[$i]->children = $this->sub_super_categories($p_cat->id, $level);
            $super_categories[$i]->text = output_escaping($p_cat->name);
            $super_categories[$i]->state = ['opened' => true];
            $super_categories[$i]->level = $level;
            $super_categories[$i]->image = get_image_url($super_categories[$i]->image, 'thumb', 'md');
            $super_categories[$i]->banner = get_image_url($super_categories[$i]->banner, 'thumb', 'md');
            $i++;
        }
        return $super_categories;
    }


    public function delete_super_category($id)
    {
        $this->db->trans_start();
        $id = escape_array($id);
        $this->db->set('status', NULL)->where('id', $id)->update('super_categories');
        $this->db->trans_complete();
        $response = FALSE;
        if ($this->db->trans_status() === TRUE) {
            $response = TRUE;
            $this->db->set('super_category_id', '1')->where('super_category_id', $id)->update('products');
        }
        return $response;
    }


    public function get_super_category_list($seller_id = NULL)
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
        if (isset($seller_id) && $seller_id != "") {
            $this->db->select('super_category_ids');
            $where1 = 'user_id = ' . $seller_id;
            $this->db->where($where1);
            $result = $this->db->get('seller_data')->result_array();
            $cat_ids = explode(',', $result[0]['super_category_ids']);
        }

        $count_res = $this->db->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_like($multipleWhere);
        }

        if (isset($seller_id) && $seller_id != "") {
            $count_res->where_in('id', $cat_ids);
        }

        $cat_count = $count_res->get('super_categories')->result_array();
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

        if (isset($seller_id) && $seller_id != "") {
            $count_res->where_in('id', $cat_ids);
        }

        $cat_search_res = $search_res->order_by($sort, "asc")->limit($limit, $offset)->get('super_categories')->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($cat_search_res as $row) {

            if (!$this->ion_auth->is_seller()) {
                $operate = '<a href="' . base_url('admin/super_category/create_super_category' . '?edit_id=' . $row['id']) . '" class=" btn btn-success btn-xs mr-1 mb-1" title="Edit" data-id="' . $row['id'] . '" data-url="admin/super_category/create_super_category"><i class="fa fa-pen"></i></a>';
                $operate .= '<a class="delete-categoty btn btn-danger btn-xs mr-1 mb-1" title="Delete" href="javascript:void(0)" data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></a>';
            }
            if ($row['status'] == '1') {
                $tempRow['status'] = '<a class="badge badge-success text-white" >Active</a>';
                if (!$this->ion_auth->is_seller()) {
                    $operate .= '<a class="btn btn-warning btn-xs update_active_status mr-1" data-table="super_categories" title="Deactivate" href="javascript:void(0)" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye-slash"></i></a>';
                }
            } else {
                $tempRow['status'] = '<a class="badge badge-danger text-white" >Inactive</a>';
                if (!$this->ion_auth->is_seller()) {
                    $operate .= '<a class="btn btn-primary mr-1 btn-xs update_active_status" data-table="super_categories" href="javascript:void(0)" title="Active" data-id="' . $row['id'] . '" data-status="' . $row['status'] . '" ><i class="fa fa-eye"></i></a>';
                }
            }

            $tempRow['id'] = $row['id'];
            $tempRow['name'] = '<a href="' . base_url() . 'admin/super_category?id=' . $row['id'] . '">' . output_escaping($row['name']) . '</a>';

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

    public function add_super_category($data)
    {
        $data = escape_array($data);

        $cat_data = [
            'name' => $data['super_category_input_name'],
            'parent_id' => ($data['super_category_parent'] == NULL && isset($data['super_category_parent'])) ? '0' : $data['super_category_parent'],
            'slug' => create_unique_slug($data['super_category_input_name'], 'super_categories'),
            'status' => '1',
        ];

        if (isset($data['edit_super_category'])) {
            unset($cat_data['status']);
            if (isset($data['super_category_input_image'])) {
                $cat_data['image'] = $data['super_category_input_image'];
            }

            $cat_data['banner'] = (isset($data['banner'])) ? $data['banner'] : '';

            $this->db->set($cat_data)->where('id', $data['edit_super_category'])->update('super_categories');
        } else {
            if (isset($data['super_category_input_image'])) {
                $cat_data['image'] = $data['super_category_input_image'];
            }
            if (isset($data['banner'])) {
                $cat_data['banner'] = (isset($data['banner']) && !empty($data['banner'])) ? $data['banner'] : '';
            }
            $this->db->insert('super_categories', $cat_data);
        }
    }
}
