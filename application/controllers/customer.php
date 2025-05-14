<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['customers'] = $this->Customer_model->get_all_customers();
        $this->load->view('customer/list', $data);
    }

    public function save()
    {
        $this->form_validation->set_rules('name', 'Customer Name', 'required');
        $this->form_validation->set_rules('code', 'Customer Code', 'required|is_unique[customers.code]');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required');
    
        $this->form_validation->set_message('is_unique', 'The code must be unique. This code already exists.');

        if ($this->form_validation->run() == FALSE) {
            $data['customers'] = $this->Customer_model->get_all_customers();
            $this->load->view('customer/index', $data);
        } else {
            $data = $this->input->post();
            $this->Customer_model->save($data);
            $this->session->set_flashdata('message', 'Customer created successfully.');
            redirect('customer/index');
        }
    }
    


    public function edit($id) {
        $data['customer'] = $this->Customer_model->get_by_id($id);
        $this->load->view('customer_edit', $data);
    }

    public function update($id) {
        $data = $this->input->post();
        $this->Customer_model->update($id, $data);
        redirect('customer');
    }

    public function delete($id) {
        $this->Customer_model->delete($id);
        redirect('customer');
    }
}

?>
