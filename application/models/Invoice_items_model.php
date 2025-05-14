<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_items_model extends CI_Model
{
    private $table = 'invoice_items';

    public function __construct()
    {
        parent::__construct();
    }

    // public function insert_items($items)
    // {
    //     return $this->db->insert_batch($this->table, $items);
    // }

    public function insert_item($data)
    {
        return $this->db->insert($this->table, $data);
    }
}
