<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');

class Externalaccount_model extends CI_Model
{
    public function get_external_purchasebill_ist($user_id)
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
        $this->db->from('external_purchase_bill e');
        if (!empty($user_id)) {
            $this->db->where('e.user_id', $user_id);
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
            $this->db->select_sum('amount');  
            $this->db->from('external_products');  
            $this->db->where('purchase_id', $row["id"]); 
            $query = $this->db->get();  
            
            $total_amount = $query->row()->amount;
            $amount=0;
            $response[] = array(
                'id' => $key + 1,
                'purchase_id' => $row["id"],
                'invoice_number' => $row['invoice_number'],
                'party_name' => $row['party_name'],
                'date' => date('d-m-Y', strtotime($row['date'])),
                'amount' => get_settings('currency')." ".$total_amount,
                'invoice_receipt' => $invoice_receipt,

            );
        }


        print_r(json_encode(array('total' => $total, 'rows' => $response)));
    }
    public function get_external_purchaseout_list($user_id)
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
        $this->db->from('external_payment_out e');
        if (!empty($user_id)) {
            $this->db->where('e.user_id', $user_id);
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
                'amount' => get_settings('currency')." ".$row['received'],
                'payment_receipt' => $invoice_receipt,

            );
        }


        print_r(json_encode(array('total' => $total, 'rows' => $response)));
    }
}
