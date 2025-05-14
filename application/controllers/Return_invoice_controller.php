<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Return_invoice_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Invoice_nw_model');
        $this->load->model('Return_invoice_model');
        $this->load->model('Return_invoice_items_model');
    }

    public function index() {

        $data['customers'] = $this->Invoice_nw_model->get_customers();
        $data['items'] = $this->Invoice_nw_model->get_items();
        $data['invoice_no'] = $this->Return_invoice_model->get_invoice_no();
        $data['invoices'] = $this->Return_invoice_model->get_invoice_details();
    
        $this->load->view('invoice/return_invoice_index', $data);
    }

    public function save_invoice() {
        $customer_id = $this->input->post('customer_id');
        $return_date = $this->input->post('invoice_date');
        $invoice_id = $this->input->post('invoice_id') ?: null;

        $items = $this->input->post('items'); 
    
        if(empty($customer_id) || empty($return_date) || empty($items)){
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
    
        $return_no = $this->Return_invoice_model->get_invoice_no();
    
        $invoice_data = [
            'return_no' => $return_no,
            'customer_id' => $customer_id,
            'invoice_id' => $invoice_id,
            'return_date' => $return_date,
            'total_qty' => $total_qty,
            'total_amount' => $total_amount
        ];

        $this->Return_invoice_model->insert_invoice($invoice_data);
        $return_invoice_id = $this->db->insert_id();

        // Save Return items
        foreach($items as $item){
            $item_data = [
                'return_invoice_id' => $return_invoice_id,
                'item_id' => $item['item_id'],
                'description' => $item['description'],
                'unit' => $item['unit'],
                'price' => $item['price'],
                'qty' => $item['qty'],
                'amount' => $item['amount']
            ];
       
            $this->Return_invoice_items_model->insert_item($item_data);
        }
    
        echo json_encode(['status' => 'success', 'message' => 'Invoice saved successfully']);
    }
    
    public function edit_invoice($invoice_id) {
     
        $return_invoice = $this->Return_invoice_model->get_invoice($invoice_id);
        $return_invoice_items = $this->Return_invoice_model->get_invoice_items($invoice_id);
    
        // Load customers + items (for dropdowns)
        $customers = $this->Invoice_nw_model->get_customers();
        $items = $this->Invoice_nw_model->get_items();
    
        // Pass to view
        $data = [
            'invoice' => $return_invoice,
            'invoice_items' => $return_invoice_items,
            'customers' => $customers,
            'items' => $items,
            'return_no' => $return_invoice->return_no
        ];
    
        $this->load->view('invoice/return_invoice_edit', $data);
    }

    public function update_invoice($invoice_id) {
    
        $customer_id = $this->input->post('customer_id');
        $return_date = $this->input->post('return_date');
        $items = $this->input->post('items');
    
        if(empty($customer_id) || empty($return_date) || empty($items)) {
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
            'return_date' => $return_date,
            'total_qty' => $total_qty,
            'total_amount' => $total_amount,
        ];
        
        $this->db->where('id', $invoice_id);
        $this->db->update('return_invoices', $invoice_data);
    
        $this->db->where('return_invoice_id', $invoice_id);
        $this->db->delete('return_invoice_items');
    
        foreach($items as $item) {
            $item_data = [
                'return_invoice_id' => $invoice_id,
                'item_id' => $item['item_id'],
                'description' => $item['description'],
                'unit' => $item['unit'],
                'price' => $item['price'],
                'qty' => $item['qty'],
                'amount' => $item['amount']
            ];
            $this->db->insert('return_invoice_items', $item_data);
        }
    
        echo json_encode(['status' => 'success', 'message' => 'Invoice Return updated successfully']);
    }

    public function delete_invoice($invoice_id) {

        $this->db->where('return_invoice_id', $invoice_id);
        $this->db->delete('return_invoice_items');
    
        $this->db->where('id', $invoice_id);
        $this->db->delete('return_invoices');
    
        $this->session->set_flashdata('message', 'Invoice Return deleted successfully.');
        redirect('return_invoice');
    }

    public function get_customer_invoices() {
        $customer_id = $this->input->post('customer_id');

        $invoices = $this->Invoice_nw_model->get_invoice_by_customer_id($customer_id);
    
        echo json_encode($invoices);
    }
    
    public function get_invoice_items_by_invoice_id(){
        $invoice_id = $this->input->post('invoice_id');
        $items = $this->Invoice_nw_model->get_invoice_items_fr_popup($invoice_id);
    
        echo json_encode([
            'status' => 'success',
            'items' => $items
        ]);

    }
}
