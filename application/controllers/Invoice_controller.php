<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Invoice_nw_model');
        $this->load->model('Invoice_items_model');
    }

    public function index() {
        $data['customers'] = $this->Invoice_nw_model->get_customers();
        $data['items'] = $this->Invoice_nw_model->get_items();
        $data['invoice_no'] = $this->Invoice_nw_model->get_invoice_no();
        $data['invoices'] = $this->Invoice_nw_model->get_invoice_details();

        // $this->db->select('invoice.*, customers.name as customer_name');
        // $this->db->from('invoice');
        // $this->db->join('customers', 'customers.id = invoice.customer_id', 'left');
        // $this->db->order_by('invoice.id', 'DESC');
        // $invoices = $this->db->get()->result();
    
        // // invoice, get items
        // foreach($invoices as $inv){
        //     $this->db->select('invoice_items.*, items.name as item_name');
        //     $this->db->from('invoice_items');
        //     $this->db->join('items', 'items.id = invoice_items.item_id', 'left');
        //     $this->db->where('invoice_items.invoice_id', $inv->id);
        //     $inv->items = $this->db->get()->result();
        // }
    
        // $data['invoices'] = $invoices;

        $this->load->view('invoice/index', $data);
    }

    public function get_customer_address() {
        $customer_id = $this->input->post('customer_id');
        $address = $this->Invoice_nw_model->get_customer_address($customer_id);
        echo json_encode($address);
    }

    public function get_item_details() {
        $item_id = $this->input->post('item_id');
        $item = $this->Invoice_nw_model->get_item_details($item_id);
        echo json_encode($item);
    }

    public function save_invoice() {
        $customer_id = $this->input->post('customer_id');
        $invoice_date = $this->input->post('invoice_date');
        $items = $this->input->post('items'); 
    
        if(empty($customer_id) || empty($invoice_date) || empty($items)){
            echo json_encode(['status' => 'error', 'message' => 'Please fill all required fields']);
            return;
        }
    
        // Calculate totals
        $total_qty = 0;
        $total_amount = 0;
        foreach($items as $item){
            $total_qty += (int)$item['qty'];
            $total_amount += (float)$item['amount'];
        }
    
        $invoice_no = $this->Invoice_nw_model->get_invoice_no();
    
        $invoice_data = [
            'invoice_no' => $invoice_no,
            'customer_id' => $customer_id,
            'invoice_date' => $invoice_date,
            'total_qty' => $total_qty,
            'total_amount' => $total_amount
        ];
     //   $this->db->insert('invoice', $invoice_data);

        $this->Invoice_nw_model->insert_invoice($invoice_data);
        $invoice_id = $this->db->insert_id();
        // Save items
        foreach($items as $item){
            $item_data = [
                'invoice_id' => $invoice_id,
                'item_id' => $item['item_id'],
                'description' => $item['description'],
                'unit' => $item['unit'],
                'price' => $item['price'],
                'qty' => $item['qty'],
                'amount' => $item['amount']
            ];
            //$this->db->insert('invoice_items', $item_data);
            $this->Invoice_items_model->insert_item($item_data);
        }
    
        echo json_encode(['status' => 'success', 'message' => 'Invoice saved successfully']);
    }
    
    public function edit_invoice($invoice_id) {
    
        $invoice = $this->Invoice_nw_model->get_invoice($invoice_id);
        $invoice_items = $this->Invoice_nw_model->get_invoice_items($invoice_id);
    
        // Load customers + items (for dropdowns)
        $customers = $this->Invoice_nw_model->get_customers();
        $items = $this->Invoice_nw_model->get_items();
    
        // Pass to view
        $data = [
            'invoice' => $invoice,
            'invoice_items' => $invoice_items,
            'customers' => $customers,
            'items' => $items,
            'invoice_no' => $invoice->invoice_no  
        ];
    
        $this->load->view('invoice/edit', $data);
    }

    public function update_invoice($invoice_id) {
        $this->load->model('Invoice_nw_model');
    
        $customer_id = $this->input->post('customer_id');
        $invoice_date = $this->input->post('invoice_date');
        $items = $this->input->post('items');
    
        if(empty($customer_id) || empty($invoice_date) || empty($items)) {
            echo json_encode(['status' => 'error', 'message' => 'Please fill all required fields']);
            return;
        }
    
        // Calculate totals
        $total_qty = 0;
        $total_amount = 0;
        foreach($items as $item) {
            $total_qty += $item['qty'];
            $total_amount += $item['amount'];
        }
    
        // Update invoice table
        $invoice_data = [
            'customer_id' => $customer_id,
            'invoice_date' => $invoice_date,
            'total_qty' => $total_qty,
            'total_amount' => $total_amount,
            'modify_date_time' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $invoice_id);
        $this->db->update('invoice', $invoice_data);
    
        // Delete old invoice items
        $this->db->where('invoice_id', $invoice_id);
        $this->db->delete('invoice_items');
    
        foreach($items as $item) {
            $item_data = [
                'invoice_id' => $invoice_id,
                'item_id' => $item['item_id'],
                'description' => $item['description'],
                'unit' => $item['unit'],
                'price' => $item['price'],
                'qty' => $item['qty'],
                'amount' => $item['amount']
            ];
            $this->db->insert('invoice_items', $item_data);
        }
    
        echo json_encode(['status' => 'success', 'message' => 'Invoice updated successfully']);
    }

    public function delete_invoice($invoice_id) {

        $this->db->where('invoice_id', $invoice_id);
        $this->db->delete('invoice_items');
    
        $this->db->where('id', $invoice_id);
        $this->db->delete('invoice');
    
        $this->session->set_flashdata('message', 'Invoice deleted successfully.');
        redirect('invoice');
    }
}
