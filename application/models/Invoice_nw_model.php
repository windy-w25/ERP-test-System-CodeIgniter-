<?php
class Invoice_nw_model extends CI_Model {

    public function get_customers() {
        return $this->db->get('customers')->result();
    }

    public function get_items() {
        return $this->db->get('items')->result();
    }

    public function get_customer_address($id) {
        $this->db->select('address');
        $this->db->where('id', $id);
        return $this->db->get('customers')->row();
    }

    public function get_item_details($id) {

        $this->db->select('items.*, units.name as unit_name');
        $this->db->from('items');
        $this->db->join('units', 'units.id = items.unit_id', 'left');
        $this->db->where('items.id', $id);
        $query = $this->db->get();
        return $query->row(); 
    }

    public function get_invoice_no() {

        $this->db->select_max('id');
        $query = $this->db->get('invoice');
        $row = $query->row();
        $next_id = ($row->id) ? $row->id + 1 : 1;
        return 'INV' . str_pad($next_id, 5, '0', STR_PAD_LEFT);
    }

    public function get_invoice_details() {
        $this->db->select('invoice.*, customers.name as customer_name');
        $this->db->from('invoice');
        $this->db->join('customers', 'customers.id = invoice.customer_id', 'left');
        $this->db->order_by('invoice.id', 'DESC');
        return $this->db->get()->result();


    }

    public function get_invoice($invoice_id) {
        return $this->db->where('id', $invoice_id)->get('invoice')->row();
    }
    
    public function get_invoice_items($invoice_id) {
        return $this->db->where('invoice_id', $invoice_id)->get('invoice_items')->result();
    }

    public function insert_invoice($invoice_data) {
        return $this->db->insert('invoice', $invoice_data);
    }
    
    public function get_invoice_by_customer_id($customer_id) {
        $this->db->select('*');
        $this->db->from('invoice');
        $this->db->where('customer_id', $customer_id);
        $this->db->where('id NOT IN (SELECT invoice_id FROM return_invoices WHERE invoice_id IS NOT NULL)', null, false);
    
        $query = $this->db->get();
        return $query->result();

      //  return $this->db->where('customer_id', $customer_id)->get('invoice')->result();
    }

    public function get_invoice_items_fr_popup($invoice_id) {
        $this->db->select('
            invoice_items.item_id,
            items.name AS item_name,
            invoice_items.description,
            invoice_items.unit,
            invoice_items.price,
            invoice_items.qty,
            invoice_items.amount
        ');
        $this->db->from('invoice_items');
        $this->db->join('items', 'items.id = invoice_items.item_id', 'left');
        $this->db->where('invoice_items.invoice_id', $invoice_id);

        $query = $this->db->get();
        return $query->result();
    }
}
