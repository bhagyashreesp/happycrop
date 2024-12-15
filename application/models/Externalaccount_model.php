<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');

class Externalaccount_model extends CI_Model
{
    public function get_external_purchasebill_ist($user_id,$status="")
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

        $this->db->select('e.*');
        $this->db->from('external_purchase_bill e');
        if (!empty($user_id)) {
            $this->db->where('e.user_id', $user_id);
        }
        if ($order_status !="") {
            $this->db->where('e.type', $order_status);
        }else{
            $this->db->where('e.type', "1");
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
            $delivery_challan = '<a href="' . base_url("my-account/ext-tax-invoice/") . $row['id'] . "/view/1" . '" target="_blank">View</a>';
            $purchase_order = '<a href="' . base_url("my-account/ext-purchase-order/") . $row['id'] . "/1" . '" target="_blank">View</a>';
            $sale_order = '<a href="' . base_url("my-account/ext-purchase-order/") . $row['id']  . '" target="_blank">View</a>';
            $this->db->select_sum('amount');  
            $this->db->from('external_products');  
            $this->db->where('purchase_id', $row["id"]); 
            $this->db->where('type',"1"); 
            $query = $this->db->get();  
            
            $total_amount = $query->row()->amount;
            $amount=0;
            $response[] = array(
                'id' => $key + 1,
                'purchase_id' => $row["id"],
                'invoice_number' => $row['invoice_number'],
                'order_number' => $row['order_number'],
                'party_name' => $row['party_name'],
                'date' => date('d-m-Y', strtotime($row['date'])),
                'amount' => get_settings('currency')." ".$total_amount,
                'invoice_receipt' => $invoice_receipt,
                'purchase_order' => $purchase_order,
                'delivery_challan' => $delivery_challan,
                'sale_order' => $sale_order,

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
    public function get_external_purchasereturn_list($user_id,$status="")
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
        $this->db->from('external_purchase_return e');
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
            $debit_note = '<a href="' . base_url("my-account/ext-debit-note/") . $row['id'] . "/view" . '" target="_blank">View</a>';
            $this->db->select_sum('amount');  
            $this->db->from('external_products');  
            $this->db->where('purchase_id', $row["id"]); 
            $this->db->where('type',"2"); 
            $query = $this->db->get();  
            
            $total_amount = $query->row()->amount;
            $amount=0;
            $response[] = array(
                'id' => $key + 1,
                'purchase_id' => $row["id"],
                'return_number' => $row['return_number'],
                'seller_name' => $row['seller_name'],
                'payment_type' => $row['payment_type'],
                'date' => date('d-m-Y', strtotime($row['date'])),
                'total' => get_settings('currency')." ".$total_amount,
                'debit_note' => $debit_note,

            );
        }


        print_r(json_encode(array('total' => $total, 'rows' => $response)));
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
            $this->db->where('e.user_id', $user_id);
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

            );
        }


        print_r(json_encode(array('total' => $total, 'rows' => $response)));
    }
}
