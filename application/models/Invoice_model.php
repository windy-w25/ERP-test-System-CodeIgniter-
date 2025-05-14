<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends CI_Model {

    public function save_invoice($data)
    {
        return $this->db->insert('invoices', $data);
    }
    
    public function get_all_invoices()
    {

        $this->db->select('invoices.id, invoices.customer_name, items.name as item_name, items.price, invoices.description');
        $this->db->from('invoices');
        $this->db->join('items', 'items.id = invoices.item_id');
        $query = $this->db->get();
        return $query->result(); 
    }
}
?>
