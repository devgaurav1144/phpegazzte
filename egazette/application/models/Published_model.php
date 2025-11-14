<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Published_model extends CI_Model {
    
    public function get_total_change_name_count_applicant() {
        return $this->db->select('id')
                        ->from('gz_change_of_name_surname_master')
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->where('user_id', $this->session->userdata('user_id'))
                        ->where('current_status', 11)
                        ->where('is_published', 1)
                        ->count_all_results();
    }
    
    public function get_total_change_of_names_applicant($limit, $offset) {
        return $this->db->select('m.gazette_type_id, m.file_no, m.id, m.created_at, m.press_signed_pdf')
                        ->from('gz_change_of_name_surname_master m')
                        ->where('m.status', 1)
                        ->where('m.deleted', 0)
                        ->where('m.user_id', $this->session->userdata('user_id'))
                        ->where('m.current_status', 11)
                        ->where('m.is_published', 1)
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
                        ->where('press_publish', 1)
                        ->where('cur_status', 17)
                        ->count_all_results();
    }
    
    public function get_total_change_of_partnership_applicant($limit, $offset) {

        $this->db->select('p.*');
        $this->db->from('gz_change_of_partnership_master p');
        $this->db->where('p.deleted', 0);
        $this->db->where('p.gazette_type_id', 1);
        $this->db->where('p.user_id', $this->session->userdata('user_id'));
        $this->db->where('p.cur_status', 17);
        $this->db->where('p.press_publish', 1);
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

    public function get_total_parter_count_search($data = array()){

        $this->db->select('m.*, s.status_det, app.name');
        $this->db->from('gz_change_of_partnership_master m');
        $this->db->join('gz_par_sur_status_master s', 's.id = m.cur_status');
        $this->db->join('gz_applicants_details app', 'm.user_id = app.id');
        $this->db->where('m.cur_status', 17);
        $this->db->where('m.press_publish', 1);
        $this->db->where('m.deleted', 0);
        $this->db->where('m.status', 1);

        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('m.file_no', $data['file_no']);
        }

        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(m.created_at)>=', $data['notice_date_form']);
            $this->db->where('DATE(m.created_at)<=', $data['notice_date_to']);
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', $data['notice_date_to']);
                $this->db->where('DATE(m.created_at)<=', $data['notice_date_to']);
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', $data['notice_date_form']);
                $this->db->where('DATE(m.created_at)<=', $data['notice_date_form']);
            }
        }
        //print_r($this->db->last_query()); exit;
       
        $this->db->order_by('id', 'DESC');
        return $this->db->count_all_results();

    }

    public function get_total_change_of_published_partnership_applicant($limit, $offset, $data = array()){

        $this->db->select('m.*, s.status_det, app.name');
        $this->db->from('gz_change_of_partnership_master m');
        $this->db->join('gz_par_sur_status_master s', 's.id = m.cur_status');
        $this->db->join('gz_applicants_details app', 'm.user_id = app.id');
        $this->db->where('m.cur_status =', 17);
        $this->db->where('m.press_publish', 1);
        $this->db->where('m.deleted', 0);
        $this->db->where('m.status', 1);
        //print_r($data);
        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('m.file_no', $data['file_no']);
        }
        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(m.created_at)>=', $data['notice_date_form']);
            $this->db->where('DATE(m.created_at)<=', $data['notice_date_to']);
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', $data['notice_date_to']);
                $this->db->where('DATE(m.created_at)<=', $data['notice_date_to']);
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', $data['notice_date_form']);
                $this->db->where('DATE(m.created_at)<=', $data['notice_date_form']);
            }
        }
        //print_r($this->db->last_query()); exit;
       
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();

    }

    public function get_total_name_surname_count_search($data = array()){
        $this->db->select('m.*, app.name AS applicant_name')
                ->from('gz_change_of_name_surname_master m')
                ->join('gz_applicants_details app', 'm.user_id = app.id')
                ->where('m.deleted', 0)
                ->where('m.user_id', $this->session->userdata('user_id'))
                ->where('m.current_status', 11)
                ->where('m.is_published', 1);

        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('m.file_no', $data['file_no']);
        }

        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(m.created_at)>=', $data['notice_date_form']);
            $this->db->where('DATE(m.created_at)<=', $data['notice_date_to']);
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', $data['notice_date_to']);
                $this->db->where('DATE(m.created_at)<=', $data['notice_date_to']);
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', $data['notice_date_form']);
                $this->db->where('DATE(m.created_at)<=', $data['notice_date_form']);
            }
        }
        //print_r($this->db->last_query()); exit;
        
        $this->db->order_by('id', 'DESC');
        return $this->db->count_all_results(); 
    }

    public function get_total_change_of_published_name_surname_applicant($limit, $offset, $data = array()){
        $this->db->select('m.*, app.name AS applicant_name')
                ->from('gz_change_of_name_surname_master m')
                ->join('gz_applicants_details app', 'm.user_id = app.id')
                ->where('m.deleted', 0)
                ->where('m.user_id', $this->session->userdata('user_id'))
                ->where('m.current_status', 11)
                ->where('m.is_published', 1);

        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('m.file_no', $data['file_no']);
        }
        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(m.created_at)>=', $data['notice_date_form']);
            $this->db->where('DATE(m.created_at)<=', $data['notice_date_to']);
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', $data['notice_date_to']);
                $this->db->where('DATE(m.created_at)<=', $data['notice_date_to']);
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', $data['notice_date_form']);
                $this->db->where('DATE(m.created_at)<=', $data['notice_date_form']);
            }
        }
        // print_r($this->db->last_query()); exit;
        
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();

    }

    // Gender Work
    public function get_total_change_gender_count_applicant() {
        return $this->db->select('id')
                        ->from('gz_change_of_gender_master')
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->where('user_id', $this->session->userdata('user_id'))
                        ->where('current_status', 11)
                        ->where('is_published', 1)
                        ->count_all_results();
    }

    public function get_total_change_of_genders_applicant($limit, $offset) {
        return $this->db->select('m.gazette_type_id, m.file_no, m.id, m.created_at, m.press_signed_pdf')
                        ->from('gz_change_of_gender_master m')
                        ->where('m.status', 1)
                        ->where('m.deleted', 0)
                        ->where('m.user_id', $this->session->userdata('user_id'))
                        ->where('m.current_status', 11)
                        ->where('m.is_published', 1)
                        ->order_by('m.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    public function get_total_gender_count_search($data = array()){
        $this->db->select('m.*, app.name AS applicant_name')
                ->from('gz_change_of_gender_master m')
                ->join('gz_applicants_details app', 'm.user_id = app.id')
                ->where('m.deleted', 0)
                ->where('m.user_id', $this->session->userdata('user_id'))
                ->where('m.current_status', 11)
                ->where('m.is_published', 1);

        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('m.file_no', $data['file_no']);
        }

        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(m.created_at)>=', $data['notice_date_form']);
            $this->db->where('DATE(m.created_at)<=', $data['notice_date_to']);
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', $data['notice_date_to']);
                $this->db->where('DATE(m.created_at)<=', $data['notice_date_to']);
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', $data['notice_date_form']);
                $this->db->where('DATE(m.created_at)<=', $data['notice_date_form']);
            }
        }
        //print_r($this->db->last_query()); exit;
        
        $this->db->order_by('id', 'DESC');
        return $this->db->count_all_results(); 
    }

    public function get_total_change_of_published_gender_applicant($limit, $offset, $data = array()){
        $this->db->select('m.*, app.name AS applicant_name')
                ->from('gz_change_of_gender_master m')
                ->join('gz_applicants_details app', 'm.user_id = app.id')
                ->where('m.deleted', 0)
                ->where('m.user_id', $this->session->userdata('user_id'))
                ->where('m.current_status', 11)
                ->where('m.is_published', 1);

        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('m.file_no', $data['file_no']);
        }
        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(m.created_at)>=', $data['notice_date_form']);
            $this->db->where('DATE(m.created_at)<=', $data['notice_date_to']);
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', $data['notice_date_to']);
                $this->db->where('DATE(m.created_at)<=', $data['notice_date_to']);
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', $data['notice_date_form']);
                $this->db->where('DATE(m.created_at)<=', $data['notice_date_form']);
            }
        }
        //print_r($this->db->last_query()); exit;
        
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();

    }
}

?>