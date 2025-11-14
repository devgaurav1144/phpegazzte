<?php 

defined('BASEPATH') OR exit('No direct script access allowed'); 


class Activity_Log_model extends CI_Model {

    public function get_activity_log_list($limit, $offset) {
        return $this->db->select('log.*, usr.name AS user_name')
                        ->from('gz_activity_log log')
                        ->join('gz_users usr', 'log.created_by = usr.id')
                        ->order_by('log.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    public function add($data = array()) {
        $array_data = array(
            'log' => $data['log'],
            'action' => $data['action'],
            'created_by' => $data['user_id'],
            'created_at' => $data['created_at']
        );

        return $this->db->insert('gz_activity_log', $array_data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('gz_activity_log');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function get_activity_log_details($id) {
        return $this->db->select('*')->from('gz_activity_log')->where('id', $id)->get()->row();
    }

    public function get_total_activity_logs() {
        return $this->db->select('*')->from('gz_activity_log')->count_all_results();
    }

}

