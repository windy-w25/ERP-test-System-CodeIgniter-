<?php
class Return_invoice_model extends CI_Model {

    public function get_invoice_no() {

        $this->db->select_max('id');
        $query = $this->db->get('return_invoices');
        $row = $query->row();
        $next_id = ($row->id) ? $row->id + 1 : 1;
        return 'RTN_INV' . str_pad($next_id, 5, '0', STR_PAD_LEFT);
    }

    public function get_invoice_details() {
        $this->db->select('return_invoices.*, customers.name as customer_name');
        $this->db->from('return_invoices');
        $this->db->join('customers', 'customers.id = return_invoices.customer_id', 'left');
        $this->db->order_by('return_invoices.id', 'DESC');
        return $this->db->get()->result();
    }

    public function insert_invoice($invoice_data) {
        return $this->db->insert('return_invoices', $invoice_data);
    }

    
    public function get_invoice($invoice_id) {
        return $this->db->where('id', $invoice_id)->get('return_invoices')->row();
    }
    
    public function get_invoice_items($invoice_id) {
        return $this->db->where('return_invoice_id', $invoice_id)->get('return_invoice_items')->result();
    }
}
