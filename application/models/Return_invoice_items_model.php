<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Return_invoice_items_model extends CI_Model
{
    private $table = 'return_invoice_items';

    public function __construct()
    {
        parent::__construct();
    }

    public function insert_item($data)
    {
        return $this->db->insert($this->table, $data);
    }
}
