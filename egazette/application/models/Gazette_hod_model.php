<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php

class Gazette_hod_model extends CI_Model {

    /*
     * liast of hod 
     */
    public function get_hod_list($limit, $offset) {
        return $this->db->select('h.*,d.department_name')
                        ->from('gz_hod h')
                        ->join('gz_department  d','h.dept_id = d.id')
                        ->order_by('h.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    /*
     * add hod 
     */
    public function add_hod_officers($data = array()) {
        $array_data = array(
            'name' => $data['user_name'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'dept_id' => $data['dept_id'],
            'created_by' => $data['created_by'],
            'created_at' => $data['created_at'],
            'status' => 1,
            'deleted' => 0
        );

        $this->db->insert('gz_hod', $array_data);
        return ($this->db->affected_rows() == 1) ? true : false;
    }



/*
 * count all hod for pagination
 * 
 */
    public function get_total_hods() {
        return $this->db->select('*')->from('gz_hod')->count_all_results();
    }
    
   /*
    * load of list of deparment
    */
  
    public function getDepartmentList() {
        return $this->db->select('*')
                        ->from('gz_department')
                        ->order_by('department_name', 'ASC')
                        ->get()->result();
    }
   /*
    * get hod details on edit
    */
    public function getHodDetails($id) {
        return $this->db->select('*')
                        ->from('gz_hod h')
                        ->where('id',$id)
                        ->get()->row();
    }
    
    /*
     * update hod 
     */
    public function update_hod_officers($data = array()) {
        $array_data = array(
            'name' => $data['user_name'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'dept_id' => $data['dept_id'],
            'created_by' => $data['modified_by'],
            'created_at' => $data['modified_at'],
            'status' => 1,
            'deleted' => 0
        );
        $this->db->where('id', $data['hod_id']);
        $this->db->update('gz_hod', $array_data);
      
        return ($this->db->affected_rows() == 1) ? true : false;
    }
    
    /*
     * check hod exits or not 
     */
    public function exists($id) {
        $result = $this->db->select('*')->from('gz_hod')->where('id', $id)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    /*
     * delete hod
     */
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('gz_hod');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }
    /*
     * Change status of hod
     */
    public function hod_status($id, $status) {
        
        $upd_arr = array(
                'status' => 0,
                'modified_by' => $this->session->userdata('user_id'),
                'modified_at' => date('Y-m-d H:i:s', time())
        ); 
        // update the users table
        $this->db->where('id', $id);
        $this->db->update('gz_hod', $upd_arr);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


 
}

?>
