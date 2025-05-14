<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_model extends CI_Model {

    public function get_all_items()
    {
        $this->db->select('items.*, units.name as unit_name');
        $this->db->from('items');
        $this->db->join('units', 'units.id = items.unit_id', 'left');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_item($id)
    {
        return $this->db->get_where('items', ['id' => $id])->row();
    }

    public function insert_item($data)
    {
        return $this->db->insert('items', $data);
    }

    public function update_item($id, $data)
    {
        return $this->db->update('items', $data, ['id' => $id]);
    }

    public function delete_item($id)
    {
        return $this->db->delete('items', ['id' => $id]);
    }

}
