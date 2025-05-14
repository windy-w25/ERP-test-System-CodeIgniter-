<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Item_model');
        $this->load->model('Invoice_model');
        $this->load->library('form_validation');
    }

    public function create()
    {
        $data['items'] = $this->Item_model->get_all_items();
        $data['invoices'] = $this->Invoice_model->get_all_invoices();
        $this->load->view('create_invoice', $data);
    }

    public function save_invoice()
    {
        $this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
        $this->form_validation->set_rules('item_id', 'Item', 'required');

        if ($this->form_validation->run() == TRUE) {
            $customer_name = $this->input->post('customer_name');
            $item_id = $this->input->post('item_id');
            $item = $this->Item_model->get_item($item_id);

            $data = array(
                'customer_name' => $customer_name,
                'item_id' => $item_id,
                //'price' => $item->price,
                'description' => $item->description
            );

            $this->Invoice_model->save_invoice($data);
            $this->session->set_flashdata('message', 'Invoice created successfully.');
            redirect('invoice/create');
        } else {
            $this->create();
        }
    }

    public function get_item_details()
    {
        $item_id = $this->input->post('item_id');
        $item = $this->Item_model->get_item($item_id);
        echo json_encode($item);
    }
}
?>
