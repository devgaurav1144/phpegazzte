<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Archival_model extends CI_Model {
    
    public function get_notification_types() {
        return $this->db->select('id, notification_type')->from('gz_notification_type')->get()->result();
    }
    
    public function get_gazette_types() {
        return $this->db->select('id, gazette_type')->from('gz_gazette_type')->get()->result();
    }

    public function get_departments() {
        return $this->db->select('*')->from('gz_department')
                        ->order_by('department_name', 'ASC')
                        ->get()->result();
    }

    public function get_department_by_id($deptID) {
        return $this->db->select('*')->from('gz_department')
                        ->where('id', $deptID)
                        ->get()->row();
        // $this->db->where('id', $deptID);
        // $query = $this->db->get('gz_department');  
        // return $query->row();
    }
    
    public function insert($insert_array, $gazette_type_id) {
        if ($gazette_type_id == 1) {
            $this->db->insert('gz_archival_extraordinary_gazettes', $insert_array);
        } else {
            $this->db->insert('gz_archival_weekly_gazettes', $insert_array);
        }
        return $this->db->insert_id();
    }
    
    public function edit($insert_array, $gazette_type_id, $gz_id) {
        $this->db->where('id', $gz_id);
        if ($gazette_type_id == 1) {
            $this->db->update('gz_archival_extraordinary_gazettes', $insert_array);
        } else {
            $this->db->update('gz_archival_weekly_gazettes', $insert_array);
        }
        return $this->db->affected_rows();
    }
    
    public function count_total_gazettes ($check) {
        $this->db->select('id');
        if ($check == 1) {
            $this->db->from('gz_archival_extraordinary_gazettes');
        } else {
            $this->db->from('gz_archival_weekly_gazettes');
        }
        $this->db->where('deleted', 0);
        return $this->db->count_all_results();
    }
    
    public function count_total_gazettes_filter($check, $dept_id, $notif_type, $subject, $notif_number, $gz_no, $keywords, $f_date, $t_date, $week, $year) {
        
        $this->db->select('id');
        if ($check == 1) {
            $this->db->from('gz_archival_extraordinary_gazettes');
            if ($dept_id != '') {
                $this->db->where('department_id', $dept_id);
            }
            if ($subject != '') {
                $this->db->where('subject', $subject);
            }
            if ($notif_type != '') {
                $this->db->where('notification_type_id', $notif_type);
            }
        } else if ($check == 2) {
            $this->db->from('gz_archival_weekly_gazettes');
            if ($week != '') {
                $this->db->where('week_id', $week);
            }
        }
        
        if ($notif_number != '') {
            $this->db->like('notification_number', $notif_number);
        }
        if ($gz_no != '') {
            $this->db->like('gazette_number', $gz_no);
        }
        if ($keywords != '') {
            $this->db->like('keywords', $keywords);
        }
        if ($f_date != '' && $t_date != '') {
            $f_date = date("Y-m-d", strtotime($f_date));
            $t_date = date("Y-m-d", strtotime($t_date));
            $this->db->where('published_date BETWEEN "' . $f_date . '" and "' . $t_date . '"');
        }

        if ( $year != '' ) {
            $this->db->where('YEAR(published_date)', $year);
        }

        //        $this->db->where('deleted', 0);
        return $this->db->count_all_results();
    }
    
    public function get_archived_gazette ($limit, $offset, $check) {
        if ($check == 1) {
            $this->db->select('m.id, d.department_name, m.notification_number, m.gazette_number, m.published_date, m.gazette_file, m.subject');
            $this->db->from('gz_archival_extraordinary_gazettes m');
            $this->db->join('gz_department d', 'd.id = m.department_id');
            
        } else {
            
            $this->db->select('m.id, m.week_id, m.notification_number, m.gazette_number, m.published_date, m.gazette_file');
            $this->db->from('gz_archival_weekly_gazettes m');
            
        }
        $this->db->where('m.deleted', 0);
        $this->db->order_by('m.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
        
    }
    
    public function get_archived_gazette_filter ($limit, $offset, $check, $dept_id, $notif_type, $subject, $notif_number, $gz_no, $keywords, $f_date, $t_date, $week, $year) {
        if ($check == 1) {
            $this->db->select('m.id, d.department_name, m.notification_number, m.gazette_number, m.published_date, m.gazette_file, m.subject');
            $this->db->from('gz_archival_extraordinary_gazettes m');
            $this->db->join('gz_department d', 'd.id = m.department_id');
            if ($dept_id != '') {
                $this->db->where('m.department_id', $dept_id);
            }
            if ($subject != '') {
                $this->db->where('m.subject', $subject);
            }
            if ($notif_type != '') {
                $this->db->where('m.notification_type_id', $notif_type);
            }
        } else if ($check == 2) {
            
            $this->db->select('m.id, m.week_id, m.notification_number, m.gazette_number, m.published_date, m.gazette_file');
            $this->db->from('gz_archival_weekly_gazettes m');
            if ($week != '') {
                $this->db->where('m.week_id', $week);
            }
        }
        
        if ($notif_number != '') {
            $this->db->like('m.notification_number', $notif_number);
        }
        if ($gz_no != '') {
            $this->db->like('m.gazette_number', $gz_no);
        }
        if ($keywords != '') {
            $this->db->like('m.keywords', $keywords);
        }
        if ($f_date != '' && $t_date != '') {
            $f_date = date("Y-m-d", strtotime($f_date));
            $t_date = date("Y-m-d", strtotime($t_date));
            $this->db->where('m.published_date BETWEEN "' . $f_date . '" and "' . $t_date . '"');
        }
        if ($year != '') {
            $this->db->where('YEAR(m.published_date)', $year);
        }
        $this->db->where('m.deleted', 0);
        $this->db->order_by('m.id', 'DESC');
        $this->db->limit($limit, $offset);

        // $sql = $this->db->get_compiled_select();
        // echo $sql; 
        // exit;


        return $this->db->get()->result();
        
    }
    
    public function exists ($table, $id) {
        $data = $this->db->select('id')
                        ->from($table)
                        ->where('id', $id)
                        ->where('deleted', 0)
                        ->get();
        
        if ($data->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function view_details ($check, $id) {
        if ($check == 1) {
            $this->db->select('m.id, d.department_name, m.notification_number, m.gazette_number, m.published_date, m.gazette_file, n.notification_type, m.subject, m.keywords, m.department_id, m.notification_type_id');
            $this->db->from('gz_archival_extraordinary_gazettes m');
            $this->db->join('gz_department d', 'd.id = m.department_id');
            $this->db->join('gz_notification_type n', 'n.id = m.notification_type_id');
            
        } else if ($check == 2) {
            
            $this->db->select('m.id, m.week_id, m.notification_number, m.gazette_number, m.published_date, m.gazette_file, m.keywords');
            $this->db->from('gz_archival_weekly_gazettes m');
            
        }
        $this->db->where('m.id', $id);
        $this->db->where('m.deleted', 0);
        $result = $this->db->get()->row();
        return $result;
    }
    
    public function delete($update_array, $table, $gz_id) {
        $this->db->where('id', $gz_id);
        $this->db->update($table, $update_array);
        
        return $this->db->affected_rows();
    }
    
    public function unique_gz_no_add ($gz_no, $table, $date) {
        $date = explode("-", $date);
        $year = $date[2];
        $result = $this->db->select('id')->from($table)
                        ->where('gazette_number', $gz_no)
                        ->where('date_format(published_date, "%Y") =', $year)
                        ->where('deleted', 0)->get();
        return ($result->num_rows() > 0) ? true : false;
    }
    
    public function unique_gz_no_edit($gz_no, $table, $date, $gz_id) {
        $date = explode("-", $date);
        $year = $date[2];
        $result = $this->db->select('id')->from($table)
                        ->where('gazette_number', $gz_no)
                        ->where('date_format(published_date, "%Y") =', $year)
                        ->where('id !=', $gz_id)
                        ->where('deleted', 0)->get();
        //        echo '<pre>';echo $this->db->last_query();exit();
        return ($result->num_rows() > 0) ? true : false;
    }

    public function get_gazette_file($file_id) {
        $this->db->select('gazette_file');
        $this->db->from('gz_archival_extraordinary_gazettes');
        $this->db->where('id', $file_id);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Department Ext Gazette works new

    public function count_total_gazettes_dept ($check, $deptID = null) {
        $this->db->select('id');
        if ($check == 1) {
            $this->db->from('gz_archival_extraordinary_gazettes');
        } else {
            $this->db->from('gz_archival_weekly_gazettes');
        }
        $this->db->where('deleted', 0);

        if (!empty($deptID)) {
            $this->db->where('department_id', $deptID);
        }

        return $this->db->count_all_results();
    }


    public function get_archived_gazette_dept($limit, $offset, $check, $deptID = null) {
        if ($check == 1) {
            // Select the necessary columns and join the department table
            $this->db->select('m.id, d.department_name, m.notification_number, m.gazette_number, m.published_date, m.gazette_file, m.subject');
            $this->db->from('gz_archival_extraordinary_gazettes m');
            $this->db->join('gz_department d', 'd.id = m.department_id');
        } else {
            // Select the necessary columns for the weekly gazettes
            $this->db->select('m.id, m.week_id, m.notification_number, m.gazette_number, m.published_date, m.gazette_file');
            $this->db->from('gz_archival_weekly_gazettes m');
        }
    
        // Ensure only non-deleted records are fetched
        $this->db->where('m.deleted', 0);
        // If a department_id is provided, add it to the query
        if (!empty($deptID)) {
           $this->db->where('m.department_id', $deptID);
        }
    
        // Set order and limit for pagination
        $this->db->order_by('m.id', 'DESC');
        $this->db->limit($limit, $offset);


        return $this->db->get()->result();
    }
    
}
?>