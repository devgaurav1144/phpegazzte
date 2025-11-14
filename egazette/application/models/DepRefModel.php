<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DepRefModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        // Load the database library if not autoloaded
        $this->load->database();
    }

    public function insertCosDeptRefData($data = array()) {
        $array_data = array(
            'hoa' => $data['hoa'],
            'dept_code' => $data['dept_code'],
            'dep_ref_id' => $data['dep_ref_id'],
            'user_id' => $data['user_id'],
            'mobile' => $data['mobile'],
            'file_number' => $data['file_number'],
            'gazette_id' => $data['gazette_id'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'transaction_type' => 'COS',
            'created_at' => date('Y-m-d H:i:s', time())

        );
        // Insert data into gz_dep_data_table
        return $this->db->insert('gz_dep_data_table', $array_data);
        // return 'Hello';
    }

    public function insertCogDeptRefData($data = array()) {
        $array_data = array(
            'hoa' => $data['hoa'],
            'dept_code' => $data['dept_code'],
            'dep_ref_id' => $data['dep_ref_id'],
            'user_id' => $data['user_id'],
            'mobile' => $data['mobile'],
            'file_number' => $data['file_number'],
            'gazette_id' => $data['gazette_id'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'transaction_type' => 'COG',
            'created_at' => date('Y-m-d H:i:s', time())

        );
        // Insert data into gz_dep_data_table
        return $this->db->insert('gz_dep_data_table', $array_data);
        // return 'Hello';
    }

    public function insertCopDeptRefData($data = array()) {
        $array_data = array(
            'hoa' => $data['hoa'],
            'dept_code' => $data['dept_code'],
            'dep_ref_id' => $data['dep_ref_id'],
            'user_id' => $data['user_id'],
            'mobile' => $data['mobile'],
            'file_number' => $data['file_number'],
            'gazette_id' => $data['gazette_id'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'transaction_type' => 'COP',
            'created_at' => date('Y-m-d H:i:s', time())

        );
        // Insert data into gz_dep_data_table
        return $this->db->insert('gz_dep_data_table', $array_data);
        // return 'Hello';
    }

    public function insertExtGzDeptRefData($data = array()) {
        $array_data = array(
            'dept_code' => $data['dept_code'],
            'dep_ref_id' => $data['dep_ref_id'],
            'user_id' => $data['user_id'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'transaction_type' => 'EXTGZ',
            'created_at' => date('Y-m-d H:i:s', time())

        );
        // Insert data into gz_dep_data_table
        return $this->db->insert('gz_dep_data_table', $array_data);
        // return 'Hello';
    }
}
