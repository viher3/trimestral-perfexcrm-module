<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Trimestral_model
 */
class Trimestral_model extends App_Model
{
    /**
     * Trimestral_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param null $initDate
     * @param null $endDate
     * @return mixed
     */
    public function getExpenses($initDate=null, $endDate=null)
    {
        $sql = 'SELECT id, date FROM tblexpenses';

        if(null !== $initDate && null !== $endDate) {
            $sql .= ' WHERE date >= "%s" AND date <= "%s"';
            $sql = sprintf($sql, $initDate, $endDate);
        }

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    /**
     * @param null $initDate
     * @param null $endDate
     * @return mixed
     */
    public function getInvoices($initDate=null, $endDate=null)
    {
        $sql = 'SELECT id, date FROM tblinvoices';

        if(null !== $initDate && null !== $endDate) {
            $sql .= ' WHERE date >= "%s" AND date <= "%s"';
            $sql = sprintf($sql, $initDate, $endDate);
        }

        $query = $this->db->query($sql);

        return $query->result_array();
    }
}
