<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Item_model');
        $this->load->model('Unit_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['items'] = $this->Item_model->get_all_items();
       
        $this->load->view('items/index', $data);
    }

    public function create()
    {
        $data['units'] = $this->Unit_model->get_all_units();
        
        $this->form_validation->set_rules('name', 'Item Name', 'required');
        $this->form_validation->set_rules('price', 'Item Price', 'required|numeric');
        $this->form_validation->set_rules('description', 'Item Description', 'required');

        if ($this->form_validation->run() == TRUE) {
            $data = $this->input->post();
            $this->Item_model->insert_item($data);
            redirect('item');
        }

        $this->load->view('items/create',$data);
    }

    public function edit($id)
    {
        $data['item'] = $this->Item_model->get_item($id);
        $data['units'] = $this->Unit_model->get_all_units();

        $this->form_validation->set_rules('name', 'Item Name', 'required');
        $this->form_validation->set_rules('price', 'Item Price', 'required|numeric');
        $this->form_validation->set_rules('description', 'Item Description', 'required');

        if ($this->form_validation->run() == TRUE) {
            $post = $this->input->post();
            $this->Item_model->update_item($id, $post);
            redirect('item');
        }

        $this->load->view('items/edit', $data);
    }

    public function delete($id)
    {
        $this->Item_model->delete_item($id);
        redirect('item');
    }
}
