<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class CommonModel extends Model
{
    protected $db;
    protected $DBGroup = 'default';
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    // execute custom query
    public function exe_query($qry)
    {
        return $this->db->query($qry);
    }

    public function update_row($table, $data, $where)
    {
        return $this->db->table($table)
            ->where($where)
            ->update($data);
    }

    public function insert_data($table, $data)
    {
        return $this->db->table($table)->insert($data);
    }

    public function get_serial($table, $prefix)
    {
        $builder = $this->db->table($table);
        $codeColumn = '';

        if ($table == 'users') {
            $codeColumn = 'user_code';
        } elseif ($table == 'employees') {
            $codeColumn = 'employee_code';
        } else {
            $codeColumn = 'id';
        }

        // get last record
        $builder->select($codeColumn);
        $builder->like($codeColumn, $prefix, 'after');
        $builder->orderBy($codeColumn, 'DESC');
        $builder->limit(1);

        $query = $builder->get()->getRow();

        if ($query) {

            // extract number part
            $number = (int) preg_replace('/[^0-9]/', '', $query->$codeColumn);
            $number++;
        } else {
            $number = 1;
        }

        // format → 5 digits
        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }


    public function get_seria2($table, $prefix)
    {
        $builder = $this->db->table($table);
        $codeColumn = '';

        if ($table == 'chapters') {
            $codeColumn = 'chapter_code';
        } elseif ($table == 'employees') {
            $codeColumn = 'chapter_code';
        } else {
            $codeColumn = 'chapters_id';
        }

        // get last record
        $builder->select($codeColumn);
        $builder->like($codeColumn, $prefix, 'after');
        $builder->orderBy($codeColumn, 'DESC');
        $builder->limit(1);

        $query = $builder->get()->getRow();

        if ($query) {

            // extract number part
            $number = (int) preg_replace('/[^0-9]/', '', $query->$codeColumn);
            $number++;
        } else {
            $number = 1;
        }

        // format → 5 digits
        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }


    public function get_serial2($table, $prefix, $column = 'chapters_id')
    {
        $builder = $this->db->table($table);

        $row = $builder
            ->select($column)
            ->like($column, $prefix, 'after')
            ->orderBy($column, 'DESC')
            ->limit(1)
            ->get()
            ->getRow();

        if ($row) {
            $lastID = $row->$column;
            $num = (int) substr($lastID, strlen($prefix));
            $num++;
        } else {
            $num = 1;
        }

        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
    public function get_serial3($table, $prefix, $column)
    {
        $builder = $this->db->table($table);

        $row = $builder
            ->select("MAX(CAST(SUBSTRING($column, " . (strlen($prefix) + 1) . ") AS UNSIGNED)) as max_no")
            ->where("$column LIKE", $prefix . '%')
            ->get()
            ->getRow();

        $next = ($row && $row->max_no) ? $row->max_no + 1 : 1;

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
    public function deleteRow($table, $where)
    {
        return $this->db->table($table)->delete($where);
    }

    public function generateSerial($table, $column, $prefix, $pad = 4)
    {
        $builder = $this->db->table($table);

        // Prefix length
        $prefixLength = strlen($prefix) + 1;

        $row = $builder
            ->select("MAX(CAST(SUBSTRING($column, $prefixLength) AS UNSIGNED)) AS max_no")
            ->where("$column LIKE", $prefix . '%')
            ->get()
            ->getRow();

        $nextNumber = (!empty($row->max_no)) ? $row->max_no + 1 : 1;

        return $prefix . str_pad($nextNumber, $pad, '0', STR_PAD_LEFT);
    }

    public function exe_query2($sql, $params = [])
    {
        if (!empty($params)) {
            return $this->db->query($sql, $params); // supports ?
        } else {
            return $this->db->query($sql);
        }
    }


    
}
