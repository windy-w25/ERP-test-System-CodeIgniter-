<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {
    public function get_all_customers() {
        return $this->db->get('customers')->result();
    }

    public function save($data) {
        return $this->db->insert('customers', $data);
    }

    public function get_by_id($id) {
        return $this->db->get_where('customers', ['id' => $id])->row();
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update('customers', $data);
    }

    public function delete($id) {
        return $this->db->delete('customers', ['id' => $id]);
    }
}

