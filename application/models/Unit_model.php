<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_model extends CI_Model {
    public function get_all_units() {
        return $this->db->get('units')->result();
    }

    public function save($data) {
        return $this->db->insert('units', $data);
    }

    public function get_by_id($id) {
        return $this->db->get_where('units', ['id' => $id])->row();
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update('units', $data);
    }

    public function delete($id) {
        return $this->db->delete('units', ['id' => $id]);
    }
}


