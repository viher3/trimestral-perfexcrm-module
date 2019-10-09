<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Trimestral_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getExpenses()
    {
        $sql = 'SELECT id, date FROM tblexpenses';

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function getInvoices()
    {
        $sql = 'SELECT id, date FROM tblinvoices';

        $query = $this->db->query($sql);

        return $query->result_array();
    }
}