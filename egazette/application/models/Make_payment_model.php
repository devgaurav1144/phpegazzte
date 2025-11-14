<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Make_payment_model extends CI_Model {
    
    public function get_total_change_name_count_applicant() {
        return $this->db->select('id')
                        ->from('gz_change_of_name_surname_master')
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->where('user_id', $this->session->userdata('user_id'))
                        ->where('current_status', 9)
                        ->count_all_results();
    }
    
    public function get_total_change_of_names_applicant($limit, $offset) {
        return $this->db->select('m.gazette_type_id, m.file_no, s.status_name, m.id, m.created_at')
                        ->from('gz_change_of_name_surname_master m')
                        ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                        ->where('m.status', 1)
                        ->where('m.deleted', 0)
                        ->where('m.user_id', $this->session->userdata('user_id'))
                        ->where('current_status', 9)
                        ->order_by('m.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    // Gender Work
    public function get_total_change_gender_count_applicant() {
        return $this->db->select('id')
                        ->from('gz_change_of_gender_master')
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->where('user_id', $this->session->userdata('user_id'))
                        ->where('current_status', 9)
                        ->count_all_results();
    }

    public function get_total_change_of_genders_applicant($limit, $offset) {
        return $this->db->select('m.gazette_type_id, m.file_no, s.status_name, m.id, m.created_at')
                        ->from('gz_change_of_gender_master m')
                        ->join('gz_change_of_gender_status_master s', 's.id = m.current_status')
                        ->where('m.status', 1)
                        ->where('m.deleted', 0)
                        ->where('m.user_id', $this->session->userdata('user_id'))
                        ->where('current_status', 9)
                        ->order_by('m.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    
    public function get_total_change_partnership_count_applicant() {

        return $this->db->select('*')
                        ->from('gz_change_of_partnership_master')
                        ->where('deleted', 0)
                        ->where('user_id', $this->session->userdata('user_id'))
                        ->where('gazette_type_id', 1)
                        ->where('cur_status', 6)
                        ->count_all_results();
    }
    
    public function get_total_change_of_partnership_applicant($limit, $offset) {

        $this->db->select('p.*,g.gazette_type,h.status_det');
        $this->db->from('gz_change_of_partnership_master p');
        $this->db->join('gz_gazette_type g', 'p.gazette_type_id = g.id');
        $this->db->join('gz_par_sur_status_master h', 'p.cur_status = h.id');
        $this->db->where('p.deleted', 0);
        $this->db->where('p.gazette_type_id', 1);
        $this->db->where('p.user_id', $this->session->userdata('user_id'));
        $this->db->where('p.cur_status', 6);
        $this->db->order_by('p.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }
    
    public function exists ($id, $table, $check, $value) {
        $result = $this->db->select('*')->from($table)
                        ->where('id', $id)
                        ->where($check, $value)
                        ->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}

?>