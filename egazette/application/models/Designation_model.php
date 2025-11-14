<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php 
class Designation_model extends CI_Model {
    
    public function getDesignationList() {
        return $this->db->select('*')
                ->from('gz_designation')
                ->order_by('name', 'ASC')
                ->get()->result();
    }

    public function get_designation_List($limit, $offset) {
        return $this->db->select('*')
                ->from('gz_designation')
                ->order_by('id', 'DESC')
                ->limit($limit, $offset)
                ->get()->result();
    }
    
    public function add($data = array()) {
        $array_data = array(
            'name' => $data['designation_name']
        );

        return $this->db->insert('gz_designation', $array_data);
    }

    public function edit($data = array()) {
        $array_data = array(
            'name' => $data['name'],
        );

        $this->db->where('id', $data['id']);
        $this->db->update('gz_designation', $array_data);

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('gz_designation');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
        
    }
    
    public function exists($id) {
        $result = $this->db->select('*')->from('gz_designation')->where('id', $id)->get();
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
    
    public function get_designation_details($id) {
        return $this->db->select('*')->from('gz_designation')->where('id', $id)->get()->row();
    }
    
    public function get_total_designations() {
        return $this->db->select('*')->from('gz_designation')->count_all_results();
    }
    
}