<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php

class Weekly_model extends CI_Model {

    public function getGazetteUnpublishedList($limit, $offset, $data = array()) {
        $this->db->select('gz.*, gt.gazette_type, parts.subject, parts.user_id, parts.week, gs.status_name,
                pts.part_name, gd.department_name, doc.dept_pdf_file_path, doc.press_pdf_file_path')
                        ->from('gz_weekly_gazette gz')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_weekly_gazette_documents doc', 'gz.id = doc.gazette_id')
                        ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                        ->join('gz_department gd', 'parts.dept_id = gd.id')
                        ->join('gz_gazette_parts pts', 'parts.part_id = pts.id')
                        ->where('gz.status_id != 2')
                        ->where('gz.status_id != 5')
                        ->where('gz.status_id != 6');

        if (!empty($data['statusType'])) {
            $this->db->where('gz.status_id', $data['statusType']);
        }
        if (!empty($data['weekTime'])) {
            $this->db->where('parts.week', $data['weekTime']);
        }

        if (!empty($data['year'])) {
            $this->db->where('parts.year', $data['year']);
        }

        if (!empty($data['dept'])) {
            $this->db->where('parts.dept_id', $data['dept']);
        }

        $this->db->group_by('gz.id');
        $this->db->order_by('gz.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function getGazetteUnpublishedList_search($data, $limit, $offset){
        $this->db->select('gz.*, gt.gazette_type, parts.subject, parts.user_id, parts.week, gs.status_name,
                pts.part_name, gd.department_name, doc.dept_pdf_file_path, doc.press_pdf_file_path')
                        ->from('gz_weekly_gazette gz')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_weekly_gazette_documents doc', 'gz.id = doc.gazette_id')
                        ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                        ->join('gz_department gd', 'parts.dept_id = gd.id')
                        ->join('gz_gazette_parts pts', 'parts.part_id = pts.id')
                        ->where('gz.status_id != 2')
                        ->where('gz.status_id != 5')
                        ->where('gz.status_id != 6');
            if ($data['statusType'] != "") {
                $this->db->where('gz.status_id', $data['statusType']);
            }
            if ($data['weekTime'] != "") {
                $this->db->where('parts.week', $data['weekTime']);
            }
    
            if ($data['fdate'] != "" || $data['tdate'] != "") {
                $this->db->where('DATE(gz.created_at)>=', $data['fdate']);
                $this->db->where('DATE(gz.created_at)<=', $data['tdate']);
            } else {
                if ($data['fdate'] == "" && $data['tdate'] != "") {
                    $this->db->where('DATE(gz.created_at)>=', $data['tdate']);
                    $this->db->where('DATE(gz.created_at)<=', $data['tdate']);
                } elseif ($data['fdate'] != "" && $data['tdate'] == "") {
                    $this->db->where('DATE(gz.created_at)>=', $data['fdate']);
                    $this->db->where('DATE(gz.created_at)<=', $data['fdate']);
                }
            }
            if ($data['dept'] != "") {
                $this->db->where('parts.dept_id', $data['dept']);
            }
            $this->db->group_by('gz.id');
            $this->db->order_by('gz.id', 'DESC');
            $this->db->limit($limit, $offset);
            return $this->db->get()->result();
    }

    public function getGazettePublishedList($limit, $offset) {
        $this->db->select('gz.*, gt.gazette_type, parts.week, gs.status_name, doc.dept_pdf_file_path, 
                                    pts.part_name, doc.press_pdf_file_path')
                            ->from('gz_weekly_gazette gz')
                            ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                            ->join('gz_status gs', 'gz.status_id = gs.id')
                            ->join('gz_weekly_gazette_documents doc', 'gz.id = doc.gazette_id')
                            ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                            ->join('gz_gazette_parts pts', 'parts.part_id = pts.id')
                            ->where('gz.status_id', 5);

        if (!empty($data['statusType'])) {
            $this->db->where('gz.status_id', $data['statusType']);
        }
        if (!empty($data['weekTime'])) {
            $this->db->where('parts.week', $data['weekTime']);
        }

        if (!empty($data['year'])) {
            $this->db->where('parts.year', $data['year']);
        }

        if (!empty($data['dept'])) {
            $this->db->where('parts.dept_id', $data['dept']);
        }

        $this->db->group_by('gz.id');
        $this->db->order_by('gz.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function getFinalPublishedGazette_List_Govt_Press($limit, $offset, $data) {
        $this->db->select('gz.*')
                ->from('gz_final_weekly_gazette gz')
                ->where('gz.published', 1);

        if (isset($data['year']) && $data['year'] != "") {
            $this->db->where('gz.year', $data['year']);
        }
        if (isset($data['weekTime']) && $data['weekTime'] != "") {
            $this->db->where('gz.week', $data['weekTime']);
        }

        $this->db->group_by('gz.id');
        $this->db->order_by('gz.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();

    }

    public function getGazettePublishedList_search($data, $limit, $offset){
        $this->db->select('gz.*, gt.gazette_type, parts.subject, parts.user_id, parts.week, gs.status_name,
                pts.part_name, gd.department_name, doc.dept_pdf_file_path, doc.press_pdf_file_path')
                        ->from('gz_weekly_gazette gz')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_weekly_gazette_documents doc', 'gz.id = doc.gazette_id')
                        ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                        ->join('gz_department gd', 'parts.dept_id = gd.id')
                        ->join('gz_gazette_parts pts', 'parts.part_id = pts.id')
                        ->where('gz.status_id = 5');

        
        if ($data['statusType'] != "") {
            $this->db->where('gz.status_id', $data['statusType']);
        }
        if ($data['weekTime'] != "") {
            $this->db->where('parts.week', $data['weekTime']);
        }

        if ($data['fdate'] != "" || $data['tdate'] != "") {
            $this->db->where('DATE(gz.created_at)>=', $data['fdate']);
            $this->db->where('DATE(gz.created_at)<=', $data['tdate']);
        } else {
            if ($data['fdate'] == "" && $data['tdate'] != "") {
                $this->db->where('DATE(gz.created_at)>=', $data['tdate']);
                $this->db->where('DATE(gz.created_at)<=', $data['tdate']);
            } elseif ($data['fdate'] != "" && $data['tdate'] == "") {
                $this->db->where('DATE(gz.created_at)>=', $data['fdate']);
                $this->db->where('DATE(gz.created_at)<=', $data['fdate']);
            }
        }
        if ($data['dept'] != "") {
            $this->db->where('parts.dept_id', $data['dept']);
        }
        $this->db->group_by('gz.id');
        $this->db->order_by('gz.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }
    
    public function getGazetteApprovedList($limit, $offset, $data = array()) {
        $this->db->select('gz.*, gt.gazette_type, parts.subject, parts.user_id, parts.week, gs.status_name,
                pts.part_name, gd.department_name, doc.dept_pdf_file_path, doc.press_pdf_file_path')
                        ->from('gz_weekly_gazette gz')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_weekly_gazette_documents doc', 'gz.id = doc.gazette_id')
                        ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                        ->join('gz_department gd', 'parts.dept_id = gd.id')
                        ->join('gz_gazette_parts pts', 'parts.part_id = pts.id')
                        ->where('gz.status_id = 6');

        if (!empty($data['statusType'])) {
            $this->db->where('gz.status_id', $data['statusType']);
        }
        if (!empty($data['weekTime'])) {
            $this->db->where('parts.week', $data['weekTime']);
        }

        if (!empty($data['fdate']) || !empty($data['tdate'])) {
            $this->db->where('DATE(gz.created_at)>=', date('Y-m-d', strtotime($data['fdate'])));
            $this->db->where('DATE(gz.created_at)<=', date('Y-m-d', strtotime($data['tdate'])));
        } else {
            if (!empty($data['fdate']) && !empty($data['tdate'])) {
                $this->db->where('DATE(gz.created_at)>=', date('Y-m-d', strtotime($data['tdate'])));
                $this->db->where('DATE(gz.created_at)<=', date('Y-m-d', strtotime($data['tdate'])));
            } elseif (!empty($data['fdate']) && !empty($data['tdate'])) {
                $this->db->where('DATE(gz.created_at)>=', date('Y-m-d', strtotime($data['fdate'])));
                $this->db->where('DATE(gz.created_at)<=', date('Y-m-d', strtotime($data['fdate'])));
            }
        }
        if (!empty($data['dept'])) {
            $this->db->where('parts.dept_id', $data['dept']);
        }
        $this->db->group_by('gz.id');
        $this->db->order_by('gz.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }


    public function getGazetteApprovedList_search($data, $limit, $offset){
        $this->db->select('gz.*, gt.gazette_type, parts.subject, parts.user_id, parts.week, gs.status_name,
        pts.part_name, gd.department_name, doc.dept_pdf_file_path, doc.press_pdf_file_path')
                ->from('gz_weekly_gazette gz')
                ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                ->join('gz_status gs', 'gz.status_id = gs.id')
                ->join('gz_weekly_gazette_documents doc', 'gz.id = doc.gazette_id')
                ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                ->join('gz_department gd', 'parts.dept_id = gd.id')
                ->join('gz_gazette_parts pts', 'parts.part_id = pts.id')
                ->where('gz.status_id = 6');

            if ($data['statusType'] != "") {
                $this->db->where('gz.status_id', $data['statusType']);
            }
            if ($data['weekTime'] != "") {
                $this->db->where('parts.week', $data['weekTime']);
            }
    
            if ($data['fdate'] != "" || $data['tdate'] != "") {
                $this->db->where('DATE(gz.created_at)>=', $data['fdate']);
                $this->db->where('DATE(gz.created_at)<=', $data['tdate']);
            } else {
                if ($data['fdate'] == "" && $data['tdate'] != "") {
                    $this->db->where('DATE(gz.created_at)>=', $data['tdate']);
                    $this->db->where('DATE(gz.created_at)<=', $data['tdate']);
                } elseif ($data['fdate'] != "" && $data['tdate'] == "") {
                    $this->db->where('DATE(gz.created_at)>=', $data['fdate']);
                    $this->db->where('DATE(gz.created_at)<=', $data['fdate']);
                }
            }
            if ($data['dept'] != "") {
                $this->db->where('parts.dept_id', $data['dept']);
            }
            $this->db->group_by('gz.id');
            $this->db->order_by('gz.id', 'DESC');
            $this->db->limit($limit, $offset);
            return $this->db->get()->result();
    }
    /*
     * Get PART Wise Merged gazette list
     */
    public function getGazetteMergedList($limit, $offset) {
        return $this->db->select('md.id, md.year, md.week, md.part_id, md.sl_no, md.issue_date, md.pdf_file_path,
                pts.part_name')
                        ->from('gz_weekly_part_wise_approved_merged_documents md')
                        ->join('gz_gazette_parts pts', 'md.part_id = pts.id')
                        ->order_by('md.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    public function getDeptGazetteUnpublishedList($user_id, $limit, $offset, $data = array()) {
        $this->db->select('gz.*, gt.gazette_type, parts.subject, parts.user_id, parts.week, gs.status_name, 
                        pts.part_name, doc.dept_pdf_file_path, doc.press_pdf_file_path')
                        ->from('gz_weekly_gazette gz')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_weekly_gazette_documents doc', 'gz.id = doc.gazette_id')
                        ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                        ->join('gz_gazette_parts pts', 'parts.part_id = pts.id')
                        ->where('parts.user_id', $user_id)
                        // ->where('gz.status_id != 2')
                        ->where('gz.status_id != 5')
                        ->where('gz.status_id != 6');
                        
        if (!empty($data['statusType'])) {
            $this->db->where('gz.status_id', $data['statusType']);
        }
        if (!empty($data['weekTime'])) {
            $this->db->where('parts.week', $data['weekTime']);
        }

        if (!empty($data['year'])) {
            $this->db->where('parts.year', $data['year']);
        }

        $this->db->group_by('gz.id');
        $this->db->order_by('gz.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }


    public function getDeptGazetteUnpublishedList_search($user_id,  $data, $limit, $offset) {
        return $this->db->select('gz.*, gt.gazette_type, parts.subject, parts.user_id, parts.week, gs.status_name, 
                        pts.part_name, doc.dept_pdf_file_path, doc.press_pdf_file_path')
                        ->from('gz_weekly_gazette gz')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_weekly_gazette_documents doc', 'gz.id = doc.gazette_id')
                        ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                        ->join('gz_gazette_parts pts', 'parts.part_id = pts.id')
                        ->where('parts.user_id', $user_id)
                        ->where('gz.status_id != 2')
                        ->where('gz.status_id != 5')
                        ->where('gz.status_id != 6');

        if ($data['statusType'] != "") {
            $this->db->where('gz.status_id', $data['statusType']);
        }
        if ($data['weekTime'] != "") {
            $this->db->where('parts.week', $data['weekTime']);
        }

        if ($data['fdate'] != "" || $data['tdate'] != "") {
            $this->db->where('DATE(gz.created_at)>=', $data['fdate']);
            $this->db->where('DATE(gz.created_at)<=', $data['tdate']);
        } else {
            if ($data['fdate'] == "" && $data['tdate'] != "") {
                $this->db->where('DATE(gz.created_at)>=', $data['tdate']);
                $this->db->where('DATE(gz.created_at)<=', $data['tdate']);
            } elseif ($data['fdate'] != "" && $data['tdate'] == "") {
                $this->db->where('DATE(gz.created_at)>=', $data['fdate']);
                $this->db->where('DATE(gz.created_at)<=', $data['fdate']);
            }
        }
        if ($data['dept'] != "") {
            $this->db->where('parts.dept_id', $data['dept']);
        }
        $this->db->group_by('gz.id');
        $this->db->order_by('gz.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();                
    }

    public function getDeptGazettePublishedList($user_id, $limit, $offset) {
       
        $this->db->select('gz.*, gt.gazette_type, parts.week, gs.status_name, doc.dept_pdf_file_path, 
                    pts.part_name, doc.press_pdf_file_path')
                        ->from('gz_weekly_gazette gz')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_weekly_gazette_documents doc', 'gz.id = doc.gazette_id')
                        ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                        ->join('gz_gazette_parts pts', 'parts.part_id = pts.id')
                        ->where('parts.user_id', $user_id)
                        ->where('gz.status_id', 5);


        if (!empty($data['statusType'])) {
            $this->db->where('gz.status_id', $data['statusType']);
        }
        if (!empty($data['weekTime'])) {
            $this->db->where('parts.week', $data['weekTime']);
        }

        if (!empty($data['year'])) {
            $this->db->where('parts.year', $data['year']);
        }

        $this->db->group_by('gz.id');
        $this->db->order_by('gz.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function getDeptGazettePublishedList_search($user_id, $data, $limit, $offset) {
        return $this->db->select('gz.*, gt.gazette_type, parts.week, gs.status_name, doc.dept_pdf_file_path, 
                    pts.part_name, doc.press_pdf_file_path')
                        ->from('gz_weekly_gazette gz')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_weekly_gazette_documents doc', 'gz.id = doc.gazette_id')
                        ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                        ->join('gz_gazette_parts pts', 'parts.part_id = pts.id')
                        ->where('parts.user_id', $user_id)
                        ->where('gz.status_id', 5);

        if ($data['statusType'] != "") {
            $this->db->where('gz.status_id', $data['statusType']);
        }
        if ($data['weekTime'] != "") {
            $this->db->where('parts.week', $data['weekTime']);
        }

        if ($data['fdate'] != "" || $data['tdate'] != "") {
            $this->db->where('DATE(gz.created_at)>=', $data['fdate']);
            $this->db->where('DATE(gz.created_at)<=', $data['tdate']);
        } else {
            if ($data['fdate'] == "" && $data['tdate'] != "") {
                $this->db->where('DATE(gz.created_at)>=', $data['tdate']);
                $this->db->where('DATE(gz.created_at)<=', $data['tdate']);
            } elseif ($data['fdate'] != "" && $data['tdate'] == "") {
                $this->db->where('DATE(gz.created_at)>=', $data['fdate']);
                $this->db->where('DATE(gz.created_at)<=', $data['fdate']);
            }
        }
        if ($data['dept'] != "") {
            $this->db->where('parts.dept_id', $data['dept']);
        }
        $this->db->group_by('gz.id');
        $this->db->order_by('gz.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();                
    }

    public function getYearList($user_id){
        if ($this->session->userdata('is_admin')) {
            
            return $this->db->select('year')
                        ->from('gz_weekly_gazette_dept_parts')
                        ->order_by('year', 'ASC')
                        ->group_by('year')
                        ->get()->result();
        } else {
            return $this->db->select('year')
                        ->from('gz_weekly_gazette_dept_parts')
                        ->where('user_id', $user_id)
                        ->order_by('year', 'ASC')
                        ->group_by('year')
                        ->get()->result();
        }
    }

    public function getWeekList($user_id){
        if ($this->session->userdata('is_admin')) {
            
            return $this->db->select('week')
                        ->from('gz_weekly_gazette_dept_parts')
                        ->order_by('week', 'ASC')
                        ->group_by('week')
                        ->get()->result();
        } else {
            return $this->db->select('week')
                        ->from('gz_weekly_gazette_dept_parts')
                        ->where('user_id', $user_id)
                        ->order_by('week', 'ASC')
                        ->group_by('week')
                        ->get()->result();
        }
    }

    public function get_department_types() {
        return $this->db->select('*')->from('gz_department')
                        ->order_by('department_name', 'ASC')
                        ->get()->result();
    }

    public function get_status() {
        return $this->db->select('*')->from('gz_status')->get()->result();
    }
    
    public function count_total_gazettes(){
        return $this->db->select('*')
                ->from('gz_weekly_gazette')
                ->count_all_results();
    }
    
    public function count_total_published_gazettes($user_id){
        if ($this->session->userdata('is_admin')) {
            return $this->db->select('*')
                ->from('gz_weekly_gazette')
                ->where('status_id', 5)
                ->count_all_results();
        } else {
            return $this->db->select('*')
                    ->from('gz_weekly_gazette gz')
                    ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                    ->where('parts.user_id', $user_id)
                    ->where('gz.status_id', 5)
                    ->count_all_results();
        }
    }
    
    public function count_total_unpublished_gazettes($user_id, $data = array()){
        if ($this->session->userdata('is_admin')) {
            
            $this->db->select('parts.*, gz.status_id')
                    ->from('gz_weekly_gazette gz')
                    ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                    ->where('status_id != 2')
                    ->where('status_id != 5')
                    ->where('status_id != 6');

            if (!empty($data['statusType'])) {
                $this->db->where('gz.status_id', $data['statusType']);
            }
            if (!empty($data['weekTime'])) {
                $this->db->where('parts.week', $data['weekTime']);
            }
    
            if (!empty($data['year'])) {
                $this->db->where('parts.year', $data['year']);
            }
    
            if (!empty($data['dept'])) {
                $this->db->where('parts.dept_id', $data['dept']);
            }

            return $this->db->count_all_results();

        } else {
           
            $this->db->select('parts.*, gz.status_id')
                    ->from('gz_weekly_gazette gz')
                    ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                    ->where('parts.user_id', $user_id)
                    ->where('gz.status_id != 5');

            if (!empty($data['statusType'])) {
                $this->db->where('gz.status_id', $data['statusType']);
            }
            if (!empty($data['weekTime'])) {
                $this->db->where('parts.week', $data['weekTime']);
            }
    
            if (!empty($data['year'])) {
                $this->db->where('parts.year', $data['year']);
            }
    
            if (!empty($data['dept'])) {
                $this->db->where('parts.dept_id', $data['dept']);
            }

            return $this->db->count_all_results();
        }
    }

    public function total_published_gazettes_counts($user_id, $data = array()){
        if ($this->session->userdata('is_admin')) {
            
            $this->db->select('parts.*, gz.status_id')
                    ->from('gz_weekly_gazette gz')
                    ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                    ->where('status_id = 5');

            if (!empty($data['statusType'])) {
                $this->db->where('gz.status_id', $data['statusType']);
            }
            if (!empty($data['weekTime'])) {
                $this->db->where('parts.week', $data['weekTime']);
            }
    
            if (!empty($data['year'])) {
                $this->db->where('parts.year', $data['year']);
            }
    
            if (!empty($data['dept'])) {
                $this->db->where('parts.dept_id', $data['dept']);
            }

            return $this->db->count_all_results();

        } else {
           
            $this->db->select('parts.*, gz.status_id')
                    ->from('gz_weekly_gazette gz')
                    ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                    ->where('parts.user_id', $user_id)
                    ->where('gz.status_id = 5');

            if (!empty($data['statusType'])) {
                $this->db->where('gz.status_id', $data['statusType']);
            }
            if (!empty($data['weekTime'])) {
                $this->db->where('parts.week', $data['weekTime']);
            }
    
            if (!empty($data['year'])) {
                $this->db->where('parts.year', $data['year']);
            }
    
            if (!empty($data['dept'])) {
                $this->db->where('parts.dept_id', $data['dept']);
            }

            return $this->db->count_all_results();
        }
    }

    public function total_count_published_weekly_gazette_Govt_Press() {

        $this->db->select('*')
                    ->from('gz_final_weekly_gazette gz')
                    ->where('gz.published = 1');

        if (!empty($data['weekTime'])) {
            $this->db->where('week', $data['weekTime']);
        }

        if (!empty($data['year'])) {
            $this->db->where('year', $data['year']);
        }

        return $this->db->count_all_results();
    }


    public function get_department_weekly_published_gazettes($user_id, $limit, $offset, $data) {
       
        $this->db->select('fwg.*')
                        ->from('gz_final_weekly_gazette fwg')
                        ->join('gz_weekly_gazette_dept_parts parts', 'fwg.week = parts.week AND fwg.year = parts.year')
                        ->where('parts.user_id', $user_id)
                        ->where('fwg.published', 1);

        if (isset($data['weekTime']) && $data['weekTime'] != "") {
            $this->db->where('fwg.week', $data['weekTime']);
        }

        if (isset($data['year']) && $data['year'] != "") {
            $this->db->where('fwg.year', $data['year']);
        }

        $this->db->group_by('fwg.id');
        $this->db->order_by('fwg.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }
    

    public function add_dept_gazette($data = array()) {
        try {
            // Start transaction
            $this->db->trans_begin();

            $array_data = array(
                'gazette_type_id' => $data['gazette_type_id'],
                // Dept. save status
                'status_id' => 2,
                'created_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s', time())
            );
            
            $this->db->insert('gz_weekly_gazette', $array_data);
            $gazette_id = $this->db->insert_id();

            // Add into dept_parts table
            $part_data = array(
                'gazette_id' => $gazette_id,
                'user_id' => $data['user_id'],
                'dept_id' => $data['dept_id'],
                'part_id' => $data['part_id'],
                'week' => $data['week'],
                'year' => date('Y'),
                'subject' => $data['subject'],
                'notification_type' => $data['notification_type'],
                'notification_number' => $data['notification_number'],
                'tags' => $data['tags'],
                'content' => $data['content'],
                'created_at' => date('Y-m-d H:i:s', time()),
            );
            $this->db->insert('gz_weekly_gazette_dept_parts', $part_data);
            
            // insert into weekly gazette status table
            $stat_data = array(
                'gazette_id' => $gazette_id,
                'user_id' => $data['user_id'],
                'dept_id' => $data['dept_id'],
                'part_id' => $data['part_id'],
                // dept. Saved
                'status_id' => 2,
                'created_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s', time())
            );
            $this->db->insert('gz_weekly_gazette_status', $stat_data);
            
            // Transaction
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return $gazette_id;
            }
        } catch (Exception $ex) {
            return false;
        }
    }
    
    
    public function resubmit_dept_gazette($data) {
        try {
            // Start transaction
            $this->db->trans_begin();
            
            // UPATE in weekly gazette table
            $gazette_array = array(
                // publish status
                'status_id' => $data['status_id'],
                'modified_by' => $data['user_id'],
                'modified_at' => $data['modified_at']
            );
            
            $this->db->where('id', $data['gazette_id']);
            $this->db->update('gz_weekly_gazette', $gazette_array);
            
            // UPATE in weekly gazette documents table
            $docs_array = array(
                // department signed PDF file path
                'dept_signed_pdf_path' => $data['dept_signed_pdf_path'],
            );
            
            $where = array(
                'gazette_id', $data['gazette_id'],
                'dept_id', $data['dept_id'],
                'part_id', $data['part_id']
            );
            
            $this->db->where($where);
            
            $this->db->update('gz_weekly_gazette_documents', $docs_array);
            
            // INSERT in weekly_gazette_status table
            $stat_array = array(
                'gazette_id' => $data['gazette_id'],
                'user_id' => $data['origin_by'],
                'part_id' => $data['part_id'],
                'dept_id' => $data['dept_id'],
                // publish status
                'status_id' => $data['status_id'],
                'created_by' => $data['user_id'],
                'created_at' => $data['created_at']
            );
            
            $this->db->insert('gz_weekly_gazette_status', $stat_array);
            
            // INSERT into notification table

            $admin_details =  $this->db->select('*')
                                        ->from('gz_users')
                                        ->where('is_admin', 1)
                                        ->get()->row();

            $notif_data = array(
                'gazette_id' => $data['gazette_id'],
                'user_id' => $data['origin_by'],
                'part_id' => $data['part_id'],
                'dept_id' => $data['dept_id'],
                'responsible_user_id' => $admin_details->id,
                'text' => 'Weekly gazette resubmitted by department',
                'created_at' => $data['created_at'],
                'is_read' => 0
            );
            
            $this->db->insert('gz_weekly_gazette_notification', $notif_data);
            
            // Transaction
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return true;
            }
            
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
    }
    
    public function dept_submit_gazette($data = array()) {
        try {
            // Start transaction
            $this->db->trans_begin();
            
            // UPATE in weekly gazette table
            $gazette_array = array(
                // publish status
                'status_id' => $data['status_id'],
                'modified_by' => $data['modified_by'],
                'modified_at' => $data['modified_at']
            );
            
            $this->db->where('id', $data['gazette_id']);
            $this->db->update('gz_weekly_gazette', $gazette_array);
            
            // UPATE in weekly gazette documents table
            // $docs_array = array(
            // department signed PDF file path
            // 'dept_signed_pdf_path' => $data['dept_signed_pdf_path'],);         
            // $this->db->where('gazette_id', $data['gazette_id']);
            // $this->db->update('gz_weekly_gazette_documents', $docs_array);
            
            // INSERT in gazette_status table
            $stat_array = array(
                'gazette_id' => $data['gazette_id'],
                'user_id' => $data['user_id'],
                'dept_id' => $data['dept_id'],
                'part_id' => $data['part_id'],
                // publish status
                'status_id' => $data['status_id'],
                'created_by' => $data['user_id'],
                'created_at' => $data['created_at']
            );
            
            $this->db->insert('gz_weekly_gazette_status', $stat_array);
            
            // INSERT into notification table

            $admin_details =  $this->db->select('*')
                                        ->from('gz_users')
                                        ->where('is_admin', 1)
                                        ->get()->row();

            $notif_data = array(
                'gazette_id' => $data['gazette_id'],
                'user_id' => $data['user_id'],
                'dept_id' => $data['dept_id'],
                'part_id' => $data['part_id'],
                'responsible_user_id' => $admin_details->id,
                'text' => 'Weekly gazette submitted by department',
                'created_at' => $data['created_at'],
                'is_read' => 0
            );
            
            $this->db->insert('gz_weekly_gazette_notification', $notif_data);
            
            // Transaction
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return true;
            }
            
        } catch (Exception $ex) {
            return false;
        }
    }

    public function get_gazette_html_content($gazette_id, $dept_id, $part_id) {
        return $this->db->select('*')->from('gz_weekly_gazette_dept_parts')
                        ->where('gazette_id', $gazette_id)
                        ->where('dept_id', $dept_id)
                        ->where('part_id', $part_id)
                        ->get()->row();
    }

    public function get_dept_info_user($user_id = 0) {
        return $this->db->select('dpt.department_name AS dept_name, dpt.id AS dept_id, '
                                . 'usr.username AS username')->from('gz_department dpt')
                        ->join('gz_users usr', 'dpt.id = usr.dept_id')
                        ->where('usr.id', $user_id)->get()->row();
    }

    public function get_sl_no($part_id, $year, $week) {
        $sl_no = 0;
        
        $result = $this->db->select('*')
                            ->from('gz_weekly_part_wise_approved_merged_documents')
                            ->where('part_id', $part_id)->where('year', $year)
                            ->get();

        if ($result->num_rows() > 0) {

            $sql_query = "SELECT MAX(sl_no) AS sl_no FROM gz_weekly_part_wise_approved_merged_documents WHERE part_id='{$part_id}' AND year='{$year}'";

            $sl_no_data = $this->db->query($sql_query)->row();

            $sl_no = ($sl_no_data->sl_no + 1);

        } else {
            $sl_no = 1;
        }

        return $sl_no;
    }

    public function get_gazette_types() {
        return $this->db->select('*')->from('gz_gazette_type')->get()->result();
    }
    
    public function get_admin_recent_weekly_gazettes() {
        return $this->db->select('gz.*, gt.gazette_type, gs.status_name, parts.subject, gd.department_name, doc.dept_pdf_file_path')
                        ->from('gz_weekly_gazette gz')
                        ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                        ->join('gz_department gd', 'parts.dept_id = gd.id')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_weekly_gazette_documents doc', 'gz.id = doc.gazette_id')
                        ->where('gz.status_id != 2')
                        ->where('gz.status_id != 5')
                        ->where('gz.status_id != 7')
                        ->order_by('gz.id', 'DESC')
                        ->limit(5)
                        ->get()->result();
    }

    public function get_dept_recent_weekly_gazettes($user_id) {
        return $this->db->select('gz.*, gs.status_name, doc.dept_pdf_file_path, parts.subject, parts.user_id')
                        ->from('gz_weekly_gazette gz')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_weekly_gazette_documents doc', 'gz.id = doc.gazette_id')
                        ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                        ->where('parts.user_id', $user_id)
                        ->where('gz.status_id != 5')
                        ->order_by('gz.id', 'DESC')
                        ->limit(5)
                        ->get()->result();
    }

    public function get_total_published_gazettes() {
        return $this->db->select('gz.*')
                        ->from('gz_final_weekly_gazette gz')
                        ->where('gz.published', 1)
                        ->count_all_results();
    }

    public function get_total_unpublished_gazettes() {
        return $this->db->select('gz.*')
                        ->from('gz_weekly_gazette gz')
                        ->where('gz.status_id != 2')
                        ->where('gz.status_id != 5')
                        ->where('gz.status_id != 6')
                        ->count_all_results();
    }

    public function get_dept_total_published_gazettes($user_id) {
        return $this->db->select('gz.*')
                        ->from('gz_gazette gz')
                        ->where('gz.status_id', 5)
                        ->where('gz.user_id', $user_id)
                        ->count_all_results();
    }

    public function get_dept_total_unpublished_gazettes($user_id) {
        return $this->db->select('gz.*')
                        ->from('gz_gazette gz')
                        ->where('gz.status_id != 5')
                        ->where('gz.user_id', $user_id)
                        ->count_all_results();
    }

    public function get_dept_total_extraordinary_gazettes($user_id) {
        return $this->db->select('*')
                        ->from('gz_gazette')
                        ->where('user_id', $user_id)
                        // 1 for extraordinary
                        ->where('gazette_type_id', 1)
                        ->count_all_results();
    }

    public function get_dept_total_weekly_gazettes($user_id) {
        return $this->db->select('*')
                        ->from('gz_weekly_gazette gz')
                        ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                        ->where('parts.user_id', $user_id)
                        // 2 for weekly
                        ->where('gz.gazette_type_id', 2)
                        ->count_all_results();
    }

    public function get_dept_total_weekly_published_gazettes($user_id) {
        return $this->db->select('fwg.*')
                        ->from('gz_final_weekly_gazette fwg')
                        ->join('gz_weekly_gazette_dept_parts parts', 'fwg.week = parts.week AND fwg.year = parts.year')
                        ->where('parts.user_id', $user_id)
                        ->where('fwg.published', 1)
                        ->group_by('fwg.id')
                        ->count_all_results();
    }
    
    public function get_dept_total_weekly_pending_gazettes($user_id) {
        return $this->db->select('gz.id')->from('gz_weekly_gazette gz')
                        ->join('gz_weekly_gazette_dept_parts dp', 'gz.id = dp.gazette_id')
                        ->where('dp.user_id', $user_id)->where('gz.status_id !=', 5)
                        ->count_all_results();
    }
    
    public function exists($id) {
        $result = $this->db->select('*')->from('gz_weekly_gazette')->where('id', $id)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function check_gazette_permission_exists($gazette_id, $user_id) {
        $result = $this->db->select('*')
                ->from('gz_weekly_gazette gz')
                ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                ->where('gz.id', $gazette_id)
                ->where('parts.user_id', $user_id)
                ->get();

        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_gazette_details($id) {

        return $this->db->select('gz.*, parts.id AS dept_part_id, parts.*, dept.department_name, status.status_name,
                                doc.*, gt.gazette_type, usr.username, usr.name, pts.part_name, pts.section_name')
                        ->from('gz_weekly_gazette gz')
                        ->join('gz_status status', 'gz.status_id = status.id')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                        ->join('gz_gazette_parts pts', 'parts.part_id = pts.id')
                        ->join('gz_department dept', 'parts.dept_id = dept.id')
                        ->join('gz_weekly_gazette_documents doc', 'gz.id = doc.gazette_id', 'LEFT')
                        ->join('gz_users usr', 'parts.user_id = usr.id')
                        ->where('gz.id', $id)
                        ->get()->row();
    }

    // Get merged gazette details
    public function getMergedGazetteDetails($id) {
        return $this->db->select('md.id, md.week, md.year, md.part_id, md.sl_no, md.issue_date, md.pdf_file_path, pts.part_name, pts.section_name')
                        ->from('gz_weekly_part_wise_approved_merged_documents md')
                        ->join('gz_gazette_parts pts', 'md.part_id = pts.id')
                        ->where('md.id', $id)
                        ->get()->row();
    }
    
    
    public function get_gazette_user_details($id) {
        return $this->db->select('gz.*, dept.department_name, gt.gazette_type, usr.username, usr.name, usr.email')
                        ->from('gz_weekly_gazette gz')
                        ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_department dept', 'parts.dept_id = dept.id')
                        ->join('gz_users usr', 'parts.user_id = usr.id')
                        ->where('gz.id', $id)
                        ->get()->row();
    }

    /*
     * Get single gazette status lists
     */

    public function get_gazette_status_lists($gazette_id) {
        return $this->db->select('gzs.*, gs.status_name')
                        ->from('gz_status gs')
                        ->join('gz_weekly_gazette_status gzs', 'gzs.status_id = gs.id')
                        ->where('gzs.gazette_id', $gazette_id)
                        ->get()->result();
    }
    
    
    /*
     * Get single gazette status lists
     */

    public function get_dept_gazette_status_lists($gazette_id, $user_id) {
        return $this->db->select('gzs.*, gs.status_name')
                        ->from('gz_status gs')
                        ->join('gz_weekly_gazette_status gzs', 'gzs.status_id = gs.id')
                        ->join('gz_weekly_gazette gz', 'gzs.gazette_id = gz.id')
                        ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                        ->where('gzs.gazette_id', $gazette_id)
                        ->where('parts.user_id', $user_id)
                        ->get()->result();
    }

    /*
     * Get single gazette document lists
     * 21/02/2023
     */

    public function get_dept_gazette_document_lists($gazette_id, $user_id) {
        return $this->db->select('gd.*')
                        ->from('gz_weekly_gazette_documents_history gd')
                        ->join('gz_weekly_gazette gz', 'gd.gazette_id = gz.id')
                        ->join('gz_weekly_gazette_dept_parts parts', 'gz.id = parts.gazette_id')
                        ->where('gd.gazette_id', $gazette_id)
                        ->where('parts.user_id', $user_id)
                        ->get()->result();
    }

    public function resubmit_save_dept_gazette($data) {
        try {
            $this->db->trans_begin();
            
            // Update gazetee table
            $gazette_data = array(
                'status_id' => $data['status_id'],
                'modified_by' => $data['origin_by'],
                'modified_at' => $data['modified_at']
            );

            $this->db->where('id', $data['gazette_id']);
            $this->db->update('gz_weekly_gazette', $gazette_data);

            //Insert to gazette_status table
            $stat_data = array(
                'dept_id' => $data['dept_id'],
                'user_id' => $data['origin_by'],
                'gazette_id' => $data['gazette_id'],
                'part_id' => $data['part_id'],
                // dept. resubmit save status
                'status_id' => $data['status_id'],
                'created_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_weekly_gazette_status', $stat_data);

            //Insert to notification table

            $admin_details =  $this->db->select('*')
                                        ->from('gz_users')
                                        ->where('is_admin', 1)
                                        ->get()->row();

            $notif_data = array(
                'is_read' => 0,
                'dept_id' => $data['dept_id'],
                'part_id' => $data['part_id'],
                'user_id' => $data['user_id'],
                'responsible_user_id' => $admin_details->id,
                'gazette_id' => $data['gazette_id'],
                'text' => 'Weekly gazette resubmitted by department',
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_weekly_gazette_notification', $notif_data);
            
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return true;
            }
        } catch (Exception $ex) {
            return false;
        }
    }
    
    /*
     * Press save preview for approval of gazette by part wise
     */
    public function save_part_wise_weekly_gazette($data = array()) {
        try {
            $this->db->trans_begin();
            
            // INSERT into Press approved table
            $array_data = array(
                'dept_part_id' => $data['dept_part_id'],
                'created_by' => $data['user_id'],
                'created_at' => $data['created_at']
            );

            $this->db->insert('gz_weekly_gazette_part_wise_press_approved', $array_data);
            
            // INSERT into weekly gazette status table
            $stat_array = array(
                'gazette_id' => $data['gazette_id'],
                'dept_id' => $data['dept_id'],
                'user_id' => $data['origin_user'],
                'status_id' => $data['status_id'],
                'part_id' => $data['part_id'],
                'created_by' => $data['user_id'],
                'created_at' => $data['created_at']
            );
            
            $this->db->insert('gz_weekly_gazette_status', $stat_array);
            
            // UPDATE gazette table status
            $this->db->where('id', $data['gazette_id']);
            $this->db->update('gz_weekly_gazette', array(
                    'status_id' => $data['status_id'],
                    'modified_by' => $data['user_id'],
                    'modified_at' => $data['created_at']
                )
            );
            
            // INSERT into weekly notification table

            $get_dept_details =  $this->db->select('user_id')
                                        ->from('gz_weekly_gazette_dept_parts')
                                        ->where('gazette_id', $data['gazette_id'])
                                        ->get()->row();

            $notif_array = array(
                'gazette_id' => $data['gazette_id'],
                'dept_id' => $data['dept_id'],
                'user_id' => $data['origin_user'],
                'part_id' => $data['part_id'],
                'responsible_user_id' => $get_dept_details->user_id,
                'created_at' => $data['created_at'],
                'text' => 'Weekly gazette approved by the Govt. Press',
                'is_read' => 0
            );
            
            $this->db->insert('gz_weekly_gazette_notification', $notif_array);
            
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return true;
            }
        } catch (Exception $ex) {
            return false;
        }
    }

    public function reject_gazette($data) {

        try {

            $this->db->trans_begin();

            $gazette_arr = array(
                'status_id' => $data['status_id'],
                'modified_by' => $data['created_by'],
                'modified_at' => $data['created_at']
            );
            
            // Update into weekly gazette table
            $this->db->where('id', $data['gazette_id']);
            $this->db->update('gz_weekly_gazette', $gazette_arr);
            
            $dept_part_arr = array(
                'reject_remarks' => $data['reject_remarks'],
            );
            
            $where = array(
                'gazette_id' => $data['gazette_id'],
                'dept_id' => $data['dept_id'],
                'part_id' => $data['part_id']
            );
            
            // Update into weekly gazette department parts table
            $this->db->where($where);
            $this->db->update('gz_weekly_gazette_dept_parts', $dept_part_arr);
            
            // Insert into status table
            $stat_array = array(
                'gazette_id' => $data['gazette_id'],
                'user_id' => $data['origin_user'],
                'dept_id' => $data['dept_id'],
                'part_id' => $data['part_id'],
                'status_id' => $data['status_id'],
                'created_by' => $data['created_by'],
                'created_at' => $data['created_at']
            );
            
            $this->db->insert('gz_weekly_gazette_status', $stat_array);

            // insert into notification table

            $gazette_data = $this->db->select('user_id')->from('gz_weekly_gazette_dept_parts')->where('gazette_id', $data['gazette_id'])->get()->row();

            $notif_array = array(
                'gazette_id' => $data['gazette_id'],
                'dept_id' => $data['dept_id'],
                'user_id' => $data['origin_user'],
                'part_id' => $data['part_id'],
                'responsible_user_id' => $gazette_data->user_id,
                'text' => 'Weekly Gazette returned from the Govt. press',
                'is_read' => 0,
                'created_at' => $data['created_at']
            );

            $this->db->insert('gz_weekly_gazette_notification', $notif_array);

            // Transaction
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return true;
            }
        } catch (Exception $ex) {
            return false;
        }
    }

    public function get_gazette_documents($gazette_id) {
        return $this->db->select('*')->from('gz_weekly_gazette_documents')
                        ->where('gazette_id', $gazette_id)
                        ->get()->row();
    }

    public function save_preview_press_gazette($data) {
        
        // INSERT into final weekly gazetee table
        $gazette_data = array(
            'sl_no' => $data['sl_no'],
            //'status_id' => $data['status_id'],
            'issue_date' => $data['issue_date'],
            'sakabda_date' => $data['sakabda_date'],
            'created_by' => $data['user_id'],
            'created_at' => date('Y-m-d H:i:s', time())
        );

        $this->db->insert('gz_final_weekly_gazette', $gazette_data);
        return $this->db->insert_id();
        
    }
    
    
    /*
     * Publish press gazette
     */
    
    public function publish_press_gazette($data = array()) {
        
        // Update Gazztte Table
        $gazette_data = array(
            'published' => $data['published']
        );
        
        $this->db->where('id', $data['gazette_id']);
        $this->db->update('gz_final_weekly_gazette', $gazette_data);

        $gazette_data = $this->db->select('*')->from('gz_weekly_gazette_dept_parts')->where('gazette_id', $data['gazette_id'])->get()->row();

        $notif_array = array(
            'gazette_id' => $data['gazette_id'],
            'dept_id' => $gazette_data->dept_id,
            'user_id' => $gazette_data->user_id,
            'part_id' => $gazette_data->part_id,
            'responsible_user_id' => $gazette_data->user_id,
            'text' => 'Weekly Gazette published by the Govt. press',
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s', time())
        );

        $this->db->insert('gz_weekly_gazette_notification', $notif_array);
        
        return ($this->db->affected_rows() > 0) ? true : false;
    }
    
    public function get_gazette_parts() {
        return $this->db->select('*')->from('gz_gazette_parts')->get()->result();
    }
    
    public function get_gazette_action_part_details($part_id) {
        return $this->db->select('*')
                ->from('gz_gazette_parts')
                ->where('id', $part_id)
                ->get()->row();
    }
    
    public function count_total_weekly_gazettes() {
        return $this->db->select('*')
                ->from('gz_weekly_gazette')
                ->count_all_results();
    }

    
    public function get_dept_wise_weekly_gazette_details($year, $week, $part_id) {
        return $this->db->select('dept_parts.*, parts.part_name, parts.section_name, dept.department_name, docs.*')
                ->from('gz_weekly_gazette gz')
                ->join('gz_weekly_gazette_dept_parts dept_parts', 'dept_parts.gazette_id = gz.id')
                ->join('gz_weekly_gazette_documents docs', 'docs.gazette_id = gz.id AND docs.part_id = dept_parts.part_id AND dept_parts.dept_id = docs.dept_id')
                ->join('gz_gazette_parts parts', 'dept_parts.part_id = parts.id')
                ->join('gz_department dept', 'dept_parts.dept_id = dept.id')
                ->where('dept_parts.part_id', $part_id)
                ->where('dept_parts.year', $year)
                ->where('dept_parts.week', $week)
                ->order_by('dept_parts.part_id, dept.department_name', 'ASC')
                ->get()->result();
        
    }
    
    
    public function get_part_wise_approved_gazette_details($year, $week, $part_id) {
        return $this->db->select('parts.*, pts.part_name, pts.section_name, docs.dept_pdf_file_path, 
                        docs.dept_word_file_path, dept.department_name')
                    ->from('gz_weekly_gazette_part_wise_press_approved appr')
                    ->join('gz_weekly_gazette_dept_parts parts', 'appr.dept_part_id = parts.id')
                    ->join('gz_weekly_gazette_documents docs', 'parts.part_id = docs.part_id 
                           AND parts.dept_id = docs.dept_id AND parts.gazette_id = docs.gazette_id')
                    ->join('gz_gazette_parts pts', 'parts.part_id = pts.id')
                    ->join('gz_department dept', 'parts.dept_id = dept.id')
                    ->where('parts.year', $year)
                    ->where('week', $week)
                    ->where('parts.part_id', $part_id)
                    ->order_by('parts.part_id, dept.department_name', 'ASC')
                    ->get()->result();
    }
    
    
    public function get_ready_to_publish_part_wise_gazette_details($year, $week) {
        return $this->db->select('docs.*, pts.part_name, pts.section_name')
                    ->from('gz_weekly_part_wise_approved_merged_documents docs')
                    ->join('gz_gazette_parts pts', 'docs.part_id = pts.id')
                    ->where('year', $year)
                    ->where('week', $week)
                    ->order_by('docs.part_id', 'ASC')
                    ->get()->result();
    }
    
    public function get_approved_part_gazette_details($year, $week, $part_id) {
        return $this->db->select('gz.*, parts.gazette_id')
                    ->from('gz_weekly_gazette_part_wise_press_approved appr')
                    ->join('gz_weekly_gazette_dept_parts parts', 'appr.dept_part_id = parts.id')
                    ->join('gz_weekly_gazette gz', 'parts.gazette_id = gz.id')
                    ->where('parts.year', $year)
                    ->where('parts.week', $week)
                    ->where('parts.part_id', $part_id)
                    ->get()->row();
    }
    
    
    public function get_approved_gazette_details($gazette_id) {
        return $this->db->select('parts.*, pts.part_name, pts.section_name')
                    ->from('gz_weekly_gazette_part_wise_press_approved appr')
                    ->join('gz_weekly_gazette_dept_parts parts', 'appr.dept_part_id = parts.id')
                    ->join('gz_gazette_parts pts', 'parts.part_id = pts.id')
                    ->join('gz_department dept', 'parts.dept_id = dept.id')
                    ->where('parts.gazette_id', $gazette_id)
                    ->order_by('parts.part_id, dept.department_name', 'ASC')
                    ->get()->result();
    }
    
    
    public function approved_gazette_details($gazette_id) {
        return $this->db->select('*')->from('gz_final_weekly_gazette')->where('id', $gazette_id)->get()->row();
    }
    
    public function insert_update_approved_part_wise_pdf($data = array()) {
        
        $query = $this->db->select('*')->from('gz_weekly_part_wise_approved_merged_documents')
                ->where('part_id', $data['part_id'])
                ->where('year', $data['year'])
                ->where('week', $data['week'])
                ->get();
        
        if ($query->num_rows() > 0) {
            
            $query_rows = $this->db->select('*')->from('gz_weekly_part_wise_approved_merged_documents')
                            ->where('part_id', $data['part_id'])
                            ->where('year', $data['year'])
                            ->where('week', $data['week'])->get()->row();
            
            $data_array = array(
                'sl_no' => $data['sl_no'],
                //'sakabda_date' => $data['sakabda_date'],
                'issue_date' => $data['issue_date'],
                'saka_month' => $data['saka_month'],
                'saka_date' => $data['saka_date'],
                'saka_year' => $data['saka_year'],
                'word_file_path' => $data['word_file_path'],
                'pdf_file_path' => $data['pdf_file_path'],
                'created_at' => $data['created_at']
            );
            
            // UPDATE into documents table
            $this->db->where('part_id', $data['part_id']);
            $this->db->where('year', $data['year']);
            $this->db->where('week', $data['week']);
            $this->db->update('gz_weekly_part_wise_approved_merged_documents', $data_array);
            
            // Delete the files physcially
            //unlink(FCPATH . $query_rows->word_file_path);
            //unlink(FCPATH . $query_rows->pdf_file_path);
            
        } else {
            
            $data_array = array(
                'part_id' => $data['part_id'],
                'year' => $data['year'],
                'week' => $data['week'],
                'sl_no' => $data['sl_no'],
                //'sakabda_date' => $data['sakabda_date'],
                'saka_month' => $data['saka_month'],
                'saka_date' => $data['saka_date'],
                'saka_year' => $data['saka_year'],
                'issue_date' => $data['issue_date'],
                'word_file_path' => $data['word_file_path'],
                'pdf_file_path' => $data['pdf_file_path'],
                'created_at' => $data['created_at']
            );
            
            $this->db->insert('gz_weekly_part_wise_approved_merged_documents', $data_array);
        }
        
    }
    
    
    public function insert_update_final_pdf($data = array()) {
        
        $final_pdf_id = "";

        $query = $this->db->select('*')->from('gz_final_weekly_gazette')
                ->where('week', $data['week'])
                ->where('year', $data['year'])
                ->get();
        
        if ($query->num_rows() > 0) {
            
            $query_rows = $this->db->select('*')->from('gz_final_weekly_gazette')
                            ->where('year', $data['year'])
                            ->where('week', $data['week'])->get()->row();
            
            $data_array = array(
                'week' => $data['week'],
                'year' => $data['year'],
                'word_file_path' => $data['word_file_path'],
                'pdf_file_path' => $data['pdf_file_path'],
                'signed_pdf_file_path' => '',
                'signed_pdf_file_size' => 0,
                'created_at' => $data['created_at']
            );
            
            // UPDATE into documents table
            $this->db->where('week', $data['week']);
            $this->db->where('year', $data['year']);
            $this->db->update('gz_final_weekly_gazette', $data_array);
            
            // Delete the files physcially
            //unlink(FCPATH . $query_rows->word_file_path);
            //unlink(FCPATH . $query_rows->pdf_file_path);
            
            $final_pdf_id = $query_rows->id;

        } else {
            
            $data_array = array(
                'year' => $data['year'],
                'week' => $data['week'],
                'word_file_path' => $data['word_file_path'],
                'pdf_file_path' => $data['pdf_file_path'],
                'signed_pdf_file_path' => '',
                'signed_pdf_file_size' => 0,
                'created_at' => $data['created_at']
            );

            $this->db->insert('gz_final_weekly_gazette', $data_array);
            $final_pdf_id = $this->db->insert_id();
        }

        return $final_pdf_id;
        
    }
	
	
	/*
	 * UPDATE department signed PDF file path name
	 */
    public function update_dept_signed_pdf_path($data = array()) {
        // Update documents table
        $doc_data = array(
            'dept_signed_pdf_path' => $data['dept_signed_pdf_path']
        );

        $this->db->where('gazette_id', $data['gazette_id']);
        $this->db->where('part_id', $data['part_id']);
        $this->db->where('dept_id', $data['dept_id']);
        $this->db->update('gz_weekly_gazette_documents', $doc_data);
        return ($this->db->affected_rows() == 1) ? true : false;
    }
	
	/*
	 * UPDATE press signed PDF file path name
	 */
	public function update_press_signed_pdf_path($data = array()) {
        // Update documents table
        $doc_data = array(
            'signed_pdf_file_path' => $data['press_signed_pdf_path'],
            'signed_pdf_file_size' => 0
        );

        $this->db->where('id', $data['gazette_id']);
        $this->db->update('gz_final_weekly_gazette', $doc_data);
        return ($this->db->affected_rows() == 1) ? true : false;
    }


    /*
     * Get the Years master form department wise part gazette
     */
    public function get_gazette_years() {
        return $this->db->select('year')->from('gz_weekly_gazette_dept_parts')
                        ->group_by('year')->get()->result();
    }

    /*
     * Get year wise weeks
     */
    public function get_year_wise_week($year) {
        return $this->db->select('week')->from('gz_weekly_gazette_dept_parts')->where('year', $year)
                        ->group_by('week')->get()->result();
    }

    /*
     * Get the years master from the merged documents table
     */
    public function get_gazette_merged_years() {
        return $this->db->select('year')->from('gz_weekly_part_wise_approved_merged_documents')
                        ->group_by('year')->get()->result();
    }

    /*
     * Get the year wise weeks from the merged table
     */
    public function get_year_wise_merged_weeks($year) {

        return $this->db->select('week')
                        ->from('gz_weekly_part_wise_approved_merged_documents')
                        ->where('year', $year)
                        ->group_by('week')
                        ->get()->result();

        // return $this->db->select('week','gz_final_weekly_gazette.week as weeek')
        //                 ->from('gz_weekly_part_wise_approved_merged_documents')
        //                 ->join('gz_final_weekly_gazette' ,'gz_final_weekly_gazette.year' ,'gz_weekly_part_wise_approved_merged_documents.year')
        //                 ->where('year', $year)
        //                 ->group_by('week')
        //                 ->get()->result();
    }

}
?>