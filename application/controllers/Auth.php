<?php

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url', 'form']);
    }
    public function register() {
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('register');
            } else {
                $data = [
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
                ];
                $this->db->insert('users', $data);
                $this->session->set_flashdata('success', 'Registration successful!');
                redirect('auth/login');
            }
        } else {
            $this->load->view('register');
        }
    }



    public function login() {
 
        if ($this->session->userdata('logged_in')) {
            redirect('item/index');
        }

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == TRUE) {
                $email = $this->input->post('email');
                $password = $this->input->post('password');

                $user = $this->Auth_model->get_user_by_email($email);

                if ($user && password_verify($password, $user->password)) {
                    $this->session->set_userdata([
                        'user_id'   => $user->id,
                        'name'      => $user->name,
                        'email'     => $user->email,
                        'logged_in' => TRUE
                    ]);

                    redirect('item/index');
                } else {
                    $this->session->set_flashdata('error', 'Invalid email or password');
                    redirect('auth/login');
                }
            }
        }

        $this->load->view('login');
    }


    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }

    public function dashboard()
    {
        $this->load->view('dashboard');
    }
}