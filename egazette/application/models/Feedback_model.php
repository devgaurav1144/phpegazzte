<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php 
class Feedback_model extends CI_Model {
    
    public function get_feedback_list($limit, $offset) {
        return $this->db->select('*')
                ->from('gz_feedback')
                ->order_by('id', 'DESC')
                ->limit($limit, $offset)
                ->get()->result();
    }
    
    public function get_feedback_details($id) {
        return $this->db->select('*')->from('gz_feedback')->where('id', $id)->get()->row();
    }
    
    public function exists($id) {
        $query = $this->db->select('*')->from('gz_feedback')->where('id', $id)->get();
        return ($query->num_rows() > 0) ? TRUE : FALSE;
    }
    
    public function get_total_feedbacks() {
        return $this->db->select('*')->from('gz_feedback')->count_all_results();
    }
    
}