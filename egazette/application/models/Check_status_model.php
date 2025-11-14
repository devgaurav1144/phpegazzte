<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Check_status_model extends CI_Model {

    // gender status model start
        public function get_total_change_of_gender_count_applicant() {
            // echo 'test';exit;
            return $this->db->select('id')
                            ->from('gz_change_of_gender_master')
                            ->where('status', 1)
                            ->where('deleted', 0)
                            ->where('user_id', $this->session->userdata('user_id'))
                            ->count_all_results();
        }
        
        public function get_total_change_of_gender_applicant($limit, $offset) {
            // echo 'total';exit;
            return $this->db->select('m.gazette_type_id, m.file_no, s.status_name, m.id, m.created_at')
                            ->from('gz_change_of_gender_master m')
                            ->join('gz_change_of_gender_status_master s', 's.id = m.current_status')
                            ->where('m.status', 1)
                            ->where('m.deleted', 0)
                            ->where('m.user_id', $this->session->userdata('user_id'))
                            ->order_by('m.id', 'DESC')
                            ->limit($limit, $offset)
                            ->get()->result();
        }

        public function get_total_change_of_gender_count_applicant_filter($file_no, $status_id) {
            $this->db->select('id');
            $this->db->from('gz_change_of_gender_master');

            if ( $file_no != '' ) {
                $this->db->like('file_no', $file_no);
            }
            if ( $status_id != 0 ) {
                $this->db->where('current_status', $status_id);
            }

            $this->db->where('status', 1);
            $this->db->where('deleted', 0);
            $this->db->where('user_id', $this->session->userdata('user_id'));
            $result = $this->db->count_all_results();
            return $result;
        }

        public function get_total_change_of_genders_applicant_filter($limit, $offset, $file_no, $status_id) {
            $this->db->select('m.gazette_type_id, m.file_no, s.status_name, m.id, m.created_at');
            $this->db->from('gz_change_of_gender_master m');
            $this->db->join('gz_change_of_gender_status_master s', 's.id = m.current_status');
            if ($file_no != '') {
                $this->db->like('m.file_no', $file_no);
            }
            if ($status_id != '') {
                $this->db->where('m.current_status', $status_id);
            }
            $this->db->where('m.status', 1);
            $this->db->where('m.deleted', 0);
            $this->db->where('m.user_id', $this->session->userdata('user_id'));
            $this->db->order_by('m.id', 'DESC');
            $this->db->limit($limit, $offset);
            $result = $this->db->get()->result();
            return $result;
        }
    // gender status model end



    public function get_total_change_name_count_applicant() {
        return $this->db->select('id')
                        ->from('gz_change_of_name_surname_master')
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->where('user_id', $this->session->userdata('user_id'))
                        ->count_all_results();
    }
    
    public function get_total_change_name_count_applicant_filter ($file_no, $status_id) {
        
        $this->db->select('id');
        $this->db->from('gz_change_of_name_surname_master');
        if ($file_no != '') {
            $this->db->like('file_no', $file_no);
        }
        if ($status_id != '') {
            $this->db->where('current_status', $status_id);
        }
        $this->db->where('status', 1);
        $this->db->where('deleted', 0);
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $result = $this->db->count_all_results();
        return $result;
    }
    
    public function get_total_change_of_names_applicant($limit, $offset) {
        return $this->db->select('m.gazette_type_id, m.file_no, s.status_name, m.id, m.created_at')
                        ->from('gz_change_of_name_surname_master m')
                        ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                        ->where('m.status', 1)
                        ->where('m.deleted', 0)
                        ->where('m.user_id', $this->session->userdata('user_id'))
                        ->order_by('m.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }
    
    public function get_total_change_of_names_applicant_filter($limit, $offset, $file_no, $status_id) {
        $this->db->select('m.gazette_type_id, m.file_no, s.status_name, m.id, m.created_at');
        $this->db->from('gz_change_of_name_surname_master m');
        $this->db->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status');
        if ($file_no != '') {
            $this->db->like('m.file_no', $file_no);
        }
        if ($status_id != '') {
            $this->db->where('m.current_status', $status_id);
        }
        $this->db->where('m.status', 1);
        $this->db->where('m.deleted', 0);
        $this->db->where('m.user_id', $this->session->userdata('user_id'));
        $this->db->order_by('m.id', 'DESC');
        $this->db->limit($limit, $offset);
        $result = $this->db->get()->result();
        return $result;
    }
    
    public function get_total_change_partnership_count_applicant() {

        return $this->db->select('*')
                        ->from('gz_change_of_partnership_master')
                        ->where('deleted', 0)
                        ->where('user_id', $this->session->userdata('user_id'))
                        ->where('gazette_type_id', 1)
                        ->count_all_results();
    }
    
    public function get_total_change_partnership_count_applicant_filter($file_no, $f_date, $t_date, $status_id) {

        $this->db->select('*');
        $this->db->from('gz_change_of_partnership_master');
        if ($file_no != '') {
            $this->db->like('file_no', $file_no);
        }

        if (!empty($f_date) || !empty($t_date)) {
            $this->db->where('DATE(created_at)>=', date('Y-m-d', strtotime($f_date)));
            $this->db->where('DATE(created_at)<=', date('Y-m-d', strtotime($t_date)));
        } else {
            if (!empty($f_date) && !empty($t_date)) {
                $this->db->where('DATE(created_at)>=', date('Y-m-d', strtotime($t_date)));
                $this->db->where('DATE(created_at)<=', date('Y-m-d', strtotime($t_date)));
            } elseif (!empty($f_date) && !empty($t_date)) {
                $this->db->where('DATE(created_at)>=', date('Y-m-d', strtotime($f_date)));
                $this->db->where('DATE(created_at)<=', date('Y-m-d', strtotime($f_date)));
            }
        }

        // if ($f_date != '' && $t_date != '') {

        //     $this->db->where('date_format(created_at, "%Y-%m-%d") BETWEEN "' . date('Y-m-d', strtotime($f_date)) . '" and "' . date('Y-m-d', strtotime($t_date)) . '"');
        // }
        if ($status_id != '') {
            $this->db->where('cur_status', $status_id);
        }
        $this->db->where('deleted', 0);
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->where('gazette_type_id', 1);
       
       return $this->db->count_all_results();
    }
    
    public function get_total_change_of_partnership_applicant($limit, $offset) {

        $this->db->select('p.*,g.gazette_type,h.status_det');
        $this->db->from('gz_change_of_partnership_master p');
        $this->db->join('gz_gazette_type g', 'p.gazette_type_id = g.id');
        $this->db->join('gz_par_sur_status_master h', 'p.cur_status = h.id');
        $this->db->where('p.deleted', 0);
        $this->db->where('p.gazette_type_id', 1);
        $this->db->where('p.user_id', $this->session->userdata('user_id'));
        $this->db->order_by('p.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }
    
    public function get_total_change_of_partnership_applicant_filter($limit, $offset, $file_no, $f_date, $t_date, $status_id) {

        $this->db->select('p.*,g.gazette_type,h.status_det');
        $this->db->from('gz_change_of_partnership_master p');
        $this->db->join('gz_gazette_type g', 'p.gazette_type_id = g.id');
        $this->db->join('gz_par_sur_status_master h', 'p.cur_status = h.id');
        if ($file_no != '') {
            $this->db->like('p.file_no', $file_no);
        }
        if ($f_date != '' && $t_date != '') {

            $this->db->where('date_format(p.created_at, "%Y-%m-%d") BETWEEN "' . date('Y-m-d', strtotime($f_date)) . '" and "' . date('Y-m-d', strtotime($t_date)) . '"');
        }
        if ($status_id != '') {
            $this->db->where('p.cur_status', $status_id);
        }
        $this->db->where('p.deleted', 0);
        $this->db->where('p.gazette_type_id', 1);
        $this->db->where('p.user_id', $this->session->userdata('user_id'));
        $this->db->order_by('p.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }
    
    public function get_gazette_status ($check) {
        if ($check == 'name') {
            $this->db->select('id, status_name');
            $this->db->from('gz_change_of_name_surname_status_master');
        } else if ($check == 'partnership') {
            $this->db->select('id, status_det');
            $this->db->from('gz_par_sur_status_master');
        } else if ($check == 'gender') {
            // echo 'gazette-status';exit;
            $this->db->select('id, status_name');
            $this->db->from('gz_change_of_gender_status_master');
        }
        $this->db->where('status', 1);
        $this->db->where('deleted', 0);
        
        return $this->db->get()->result();
    }

    // public function get_gazette_status_gender() {
    //     return $this->db->select('id, status_name')
    //                 ->from('gz_change_of_gender_status_master')
    //                 ->where('status', 1)
    //                 ->where('deleted', 0)
    //                 ->get()->result();
    // }

    public function exists ($id, $table) {
        $result = $this->db->select('*')->from($table)
                        ->where('id', $id)
                        ->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}

?>