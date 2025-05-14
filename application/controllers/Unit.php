<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Unit_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['units'] = $this->Unit_model->get_all_units();
        $this->load->view('unit/index', $data);
    }
    
    public function save()
    {
        $this->form_validation->set_rules('name', 'Unit Name', 'required');
        $this->form_validation->set_rules(
            'code', 
            'Unit Code', 
            'required|is_unique[units.code]',
        );    

        $this->form_validation->set_message('is_unique', 'The code must be unique. This code already exists.');

        if ($this->form_validation->run() == FALSE) {
            $data['units'] = $this->Unit_model->get_all_units();
            $this->load->view('unit/index', $data);
        } else {
            $data = $this->input->post();
            $this->Unit_model->save($data);
            $this->session->set_flashdata('message', 'Unit saved successfully.');
            redirect('unit/index');
        }
    

    }
    
    

    // public function save() {
    //     $data = $this->input->post();
    //     $this->Unit_model->save($data);
    //     redirect('unit');
    // }

    // public function edit($id) {
    //     $data['unit'] = $this->Unit_model->get_by_id($id);
    //     $this->load->view('unit_edit', $data);
    // }

    // public function update($id) {
    //     $data = $this->input->post();
    //     $this->Unit_model->update($id, $data);
    //     redirect('unit');
    // }

    // public function delete($id) {
    //     $this->Unit_model->delete($id);
    //     redirect('unit');
    // }
}

?>