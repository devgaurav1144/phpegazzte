<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php

class Department_model extends CI_Model {

    public function getDepartmentList() {
        return $this->db->select('*')
                        ->from('gz_department')
                        ->order_by('department_name', 'ASC')
                        ->get()->result();
    }

    public function get_department_List($limit, $offset) {
        return $this->db->select('*')
                        ->from('gz_department')
                        ->order_by('id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    public function add($data = array()) {
        $array_data = array(
            'department_name' => $data['department_name'],
            'facebook_page' => $data['facebook_page'],
            'twitter_page' => $data['twitter_page'],
            'status' => 1,
            'datetime' => date('Y-m-d H:i:s', time())
        );

        return $this->db->insert('gz_department', $array_data);
    }

    public function edit($data = array()) {
        $array_data = array(
            'department_name' => $data['department_name'],
            'facebook_page' => $data['facebook_page'],
            'twitter_page' => $data['twitter_page'],
        );

        $this->db->where('id', $data['id']);
        $this->db->update('gz_department', $array_data);

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('gz_department');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function exists($id) {
        $result = $this->db->select('*')->from('gz_department')->where('id', $id)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function linked_with_user($id) {
        $result = $this->db->select('*')->from('gz_users')->where('dept_id', $id)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_department_details($id) {
        return $this->db->select('*')->from('gz_department')->where('id', $id)->get()->row();
    }

    public function get_total_departments() {
        return $this->db->select('*')->from('gz_department')->count_all_results();
    }

    public function get_designation_list() {
        return $this->db->select('*')->from('gz_designation')->get()->result();
    }

    public function get_designation_list_nodal_officer() {
        return $this->db->select('*')->from('gz_designation')->order_by('name', 'ASC')->get()->result();
    }

}

?>