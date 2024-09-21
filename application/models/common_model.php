
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class common_model extends CI_Model
{
    public function getRecords($table, $fields = '', $condition = '', $order_by = '', $limit = '', $debug = 0, $group_by = '')
    {
        // print_r($table);
        $str_sql = '';
        if (is_array($fields)) {  #$fields passed as array
            $str_sql .= implode(",", $fields);
        } elseif ($fields != "") {   #$fields passed as string
            $str_sql .= $fields;
        } else {
            $str_sql .= '*';  #$fields passed blank
        }
        $this->db->select($str_sql, FALSE);
        if (is_array($condition)) {  #$condition passed as array
            if (count($condition) > 0) {
                foreach ($condition as $field_name => $field_value) {
                    if ($field_name != '' && $field_value != '') {
                        $this->db->where($field_name, $field_value);
                    }
                }
            }
        } else if ($condition != "") { #$condition passed as string
            $this->db->where($condition);
        }
        if ($limit != "")
            $this->db->limit($limit); #limit is not blank

        if (is_array($order_by)) {
            $this->db->order_by($order_by[0], $order_by[1]);  #$order_by is not blank 
        } else if ($order_by != "") {
            $this->db->order_by($order_by);  #$order_by is not blank
        }
        $this->db->from($table);
        $query = $this->db->get();
        if ($debug) {
            die($this->db->last_query());
        }

        return $query->result_array();
    }
}