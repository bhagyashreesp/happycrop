<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');

class Account_model extends CI_Model
{
    public function get_expense_list($user_id){
        $offset = 0;
        $limit = 10;

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }

        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        $this->db->select('e.*');
        $this->db->from('expenses e');
        // $this->db->join('expenses_items ei', 'e.id = ei.expense_id', 'left');
       

        // Apply filters (e.g., user_id, order status)
        if (!empty($user_id)) {
            $this->db->where('e.user_id', $user_id);
        }

        
        if ($this->input->get('search_field')) {
            $search = trim($this->input->get('search_field', true));
            $this->db->group_start();
            $this->db->or_like('e.id', $search);
            $this->db->or_like('e.expense_category', $search);
            $this->db->or_like('e.expense_number', $search);
            $this->db->or_like('e.date', $search);
            $this->db->or_like('e.payment_type', $search);
            $this->db->or_like('e.description', $search); 
            $this->db->group_end(); 
        }
        
        // Group by product ID
        $this->db->group_by('e.id');
        $count_query = clone $this->db;
        $total = $count_query->get()->num_rows();


        // Sorting and limiting
        $this->db->order_by('e.date', 'DESC'); // Example: order by total quantity sold
        // $total = $this->db->get()->result_array();

        $this->db->limit($limit, $offset);

        $result = $this->db->get()->result_array();
      
        // Prepare response
        $response = array();
        foreach ($result as $key => $row) {
            $product_view_url = '<a target="_blank" href="' . base_url() . 'my-account/expense-details/' . $row['id'] . '">View</a>';
            if($row['image'] != "" && isFileExists($row['image'])){
                $image = '<a target="_blank" href="' . base_url() .  $row['image'] . '">View</a>';
            }else{
                $image ="-";
            }
            if($row['document'] != "" && isFileExists($row['document'])){
                $document = '<a target="_blank" href="' . base_url() .  $row['document'] . '">View</a>';
            }else{
                $document ="-";
            }

            $response[] = array(
                'id' => $key + 1,
                'expense_id' => $row["id"],
                'expense_category' => $row['expense_category'],
                'expense_number' => $row['expense_number'],
                'date' => date('d-m-Y', strtotime($row['date'])),
                'payment_type' => $row['payment_type'],
                'total' => $row['total'],
                'paid_amount' => $row["paid_amount"],
                'descritpion' => $row["description"],
                'product_view_url' => $product_view_url,
                'image' => $image,
                'document' => $document,

            );
        }
        
        
        print_r(json_encode(array('total' => $total, 'rows' => $response)));
    }

}