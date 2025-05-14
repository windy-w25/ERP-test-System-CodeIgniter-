<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function get_user_by_email($email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('users');

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    // public function register_user($name, $email, $password)
    // {
    //     $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    //     $data = array(
    //         'name'     => $name,
    //         'email'    => $email,
    //         'password' => $hashed_password
    //     );

    //     // Insert the new user into the database
    //     return $this->db->insert('users', $data);
    // }


    public function email_exists($email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->num_rows() > 0; // Returns true if email exists
    }
}
?>
