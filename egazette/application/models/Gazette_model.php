<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php

class Gazette_model extends CI_Model {

    public function getGazetteUnpublishedList($limit, $offset, $data = array()) {
       
       $this->db->select('gz.*, gt.gazette_type, gs.status_name, gd.department_name, doc.dept_word_file_path, doc.dept_pdf_file_path, doc.dept_signed_pdf_path, doc.press_pdf_file_path, doc.press_signed_pdf_path')
                        ->from('gz_gazette gz')
                        ->join('gz_department gd', 'gz.dept_id = gd.id')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_documents doc', 'gz.id = doc.gazette_id')
                        ->where_in('gz.status_id', [1, 3, 4, 6, 10, 17, 18, 19, 20, 21]);

        if (!empty($data['subline'])) {
            $this->db->like('gz.subject', $data['subline']);
        }
        if (!empty($data['odrNo'])) {
            $this->db->like('gz.notification_number', $data['odrNo']);
        }

        if (!empty($data['statusType'])) {
            $this->db->like('gz.status_id', $data['statusType']);
        }
        if (!empty($data['dept'])) {
            $this->db->like('gz.dept_id', $data['dept']);
        }

        if (!empty($data['nType'])) {
            $this->db->like('gz.notification_type', $data['nType']);
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

        $this->db->order_by('gz.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function getGazettePublishedList($limit, $offset, $data = array()) {
        $this->db->select('gz.*, gt.gazette_type, gs.status_name, gd.department_name, doc.dept_word_file_path, doc.dept_pdf_file_path, doc.dept_signed_pdf_path, doc.press_pdf_file_path, doc.press_signed_pdf_path')
                    ->from('gz_gazette gz')
                    ->join('gz_department gd', 'gz.dept_id = gd.id')
                    ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                    ->join('gz_status gs', 'gz.status_id = gs.id')
                    ->join('gz_documents doc', 'gz.id = doc.gazette_id')
                    ->where('gz.status_id', 5);

        if (!empty($data['subline'])) {
            $this->db->like('gz.subject', $data['subline']);
        }
        if (!empty($data['odrNo'])) {
            $this->db->like('gz.notification_number', $data['odrNo']);
        }
        if (!empty($data['statusType'])) {
            $this->db->like('gz.status_id', $data['statusType']);
        }
        if (!empty($data['dept'])) {
            $this->db->like('gz.dept_id', $data['dept']);
        }

        if (!empty($data['nType'])) {
            $this->db->like('gz.notification_type', $data['nType']);
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

        $this->db->order_by('gz.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function getGazettePublishedListCount($data = array()){
        $this->db->select('gz.*')
                        ->from('gz_gazette gz')
                        ->where('gz.status_id', 5);

        if (!empty($data['subline'])) {
            $this->db->like('gz.subject', $data['subline']);
        }
        if (!empty($data['odrNo'])) {
            $this->db->like('gz.notification_number', $data['odrNo']);
        }
        if (!empty($data['statusType'])) {
            $this->db->where('gz.status_id', $data['statusType']);
        }
        if (!empty($data['dept'])) {
            $this->db->like('gz.dept_id', $data['dept']);
        }

        if (!empty($data['nType'])) {
            $this->db->like('gz.notification_type', $data['nType']);
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

        return $this->db->count_all_results();
    }   

    /**
     * Search For Gazette
     */

     public function getGazettePublishedList_search($data, $limit, $offset){

        $this->db->select('gz.*, gs.status_name, gd.department_name, doc.dept_word_file_path, doc.dept_pdf_file_path, doc.dept_signed_pdf_path, doc.press_pdf_file_path, doc.press_signed_pdf_path')
        ->from('gz_gazette gz')
        ->join('gz_department gd', 'gz.dept_id = gd.id')
        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
        ->join('gz_status gs', 'gz.status_id = gs.id')
        ->join('gz_documents doc', 'gz.id = doc.gazette_id')
        ->where('gz.status_id', 5);
         
        if ($data['dept'] != "") {
            $this->db->where('gz.dept_id', $data['dept']);
        }
        if ($data['statusType'] != "") {
            $this->db->where('gz.status_id', $data['statusType']);
        }
        if ($data['odrNo'] != "") {
            $this->db->where('gz.notification_number', $data['odrNo']);
        }
        if ($data['nType'] != "") {
            $this->db->where('gz.notification_type', $data['nType']);
        }
        if ($data['subline'] != "") {
            $this->db->where('gz.subject', $data['subline']);
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
        $this->db->group_by('gz.id');
        $this->db->order_by('gz.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function getGazetteUnpublishedList_serach($data, $limit, $offset){
        $this->db->select('gz.*, gt.gazette_type, gs.status_name, gd.department_name, doc.dept_word_file_path, doc.dept_pdf_file_path, doc.dept_signed_pdf_path, doc.press_pdf_file_path, doc.press_signed_pdf_path')
                        ->from('gz_gazette gz')
                        ->join('gz_department gd', 'gz.dept_id = gd.id')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_documents doc', 'gz.id = doc.gazette_id')
                        ->where_in('gz.status_id', [1, 3, 4, 6, 10, 17, 18, 19, 20, 21]);
        if ($data['dept'] != "") {
            $this->db->where('gz.dept_id', $data['dept']);
        }
        if ($data['statusType'] != "") {
            $this->db->where('gz.status_id', $data['statusType']);
        }
        if ($data['odrNo'] != "") {
            $this->db->where('gz.notification_number', $data['odrNo']);
        }
        if ($data['nType'] != "") {
            $this->db->where('gz.notification_type', $data['nType']);
        }
        if ($data['subline'] != "") {
            $this->db->where('gz.subject', $data['subline']);
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
        $this->db->group_by('gz.id');
        $this->db->order_by('gz.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
        
    }

    public function getDeptGazettePublishedList_search($user_id,$data, $limit, $offset){
        $this->db->select('gz.*, gt.gazette_type, gs.status_name, doc.dept_word_file_path, doc.dept_pdf_file_path, doc.press_pdf_file_path, doc.dept_signed_pdf_path, doc.press_signed_pdf_path')
                        ->from('gz_gazette gz')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_documents doc', 'gz.id = doc.gazette_id')
                        ->where('gz.user_id', $user_id)
                        ->where('gz.status_id', 5);
        if ($data['dept'] != "") {
            $this->db->where('gz.dept_id', $data['dept']);
        }
        if ($data['statusType'] != "") {
            $this->db->where('gz.status_id', $data['statusType']);
        }
        if ($data['odrNo'] != "") {
            $this->db->where('gz.notification_number', $data['odrNo']);
        }
        if ($data['nType'] != "") {
            $this->db->where('gz.notification_type', $data['nType']);
        }
        if ($data['subline'] != "") {
            $this->db->where('gz.subject', $data['subline']);
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
        $this->db->group_by('gz.id');
        $this->db->order_by('gz.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();                
    }

    public function getDeptGazetteUnpublishedList_search($user_id,$data, $limit, $offset){
        $this->db->select('gz.*, gt.gazette_type, gs.status_name, doc.dept_word_file_path, doc.dept_pdf_file_path, doc.press_pdf_file_path, doc.dept_signed_pdf_path, doc.press_signed_pdf_path')
                ->from('gz_gazette gz')
                ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                ->join('gz_status gs', 'gz.status_id = gs.id')
                ->join('gz_documents doc', 'gz.id = doc.gazette_id')
                ->where('gz.user_id', $user_id)
                ->where('gz.status_id != 5');
        if ($data['dept'] != "") {
            $this->db->where('gz.dept_id', $data['dept']);
        }
        if ($data['statusType'] != "") {
            $this->db->where('gz.status_id', $data['statusType']);
        }
        if ($data['odrNo'] != "") {
            $this->db->where('gz.notification_number', $data['odrNo']);
        }
        if ($data['nType'] != "") {
            $this->db->where('gz.notification_type', $data['nType']);
        }
        if ($data['subline'] != "") {
            $this->db->where('gz.subject', $data['subline']);
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
        $this->db->group_by('gz.id');
        $this->db->order_by('gz.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();        
    }

    public function getDeptGazetteUnpublishedList($user_id, $limit, $offset, $data = array()) {
        
        $this->db->select('gz.*, gt.gazette_type, gs.status_name, doc.dept_word_file_path, doc.dept_pdf_file_path, doc.press_pdf_file_path, doc.dept_signed_pdf_path, doc.press_signed_pdf_path')
                        ->from('gz_gazette gz')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_documents doc', 'gz.id = doc.gazette_id')
                        ->where('gz.user_id', $user_id)
                        ->where('gz.status_id != 5');
                        
        if (!empty($data['subline'])) {
            $this->db->like('gz.subject', $data['subline']);
        }
        if (!empty($data['odrNo'])) {
            $this->db->like('gz.notification_number', $data['odrNo']);
        }
        if (!empty($data['statusType'])) {
            $this->db->where('gz.status_id', $data['statusType']);
        }
        if (!empty($data['dept'])) {
            $this->db->like('gz.dept_id', $data['dept']);
        }

        if (!empty($data['nType'])) {
            $this->db->like('gz.notification_type', $data['nType']);
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

        $this->db->order_by('gz.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
        //$this->db->get()->result();
        //die($this->db->last_query());

    }

    public function getDeptGazettePublishedList($user_id, $limit, $offset, $data = array()) {
       
       $this->db->select('gz.*, gt.gazette_type, gs.status_name, doc.dept_word_file_path, doc.dept_pdf_file_path, doc.press_pdf_file_path, doc.dept_signed_pdf_path, doc.press_signed_pdf_path')
                        ->from('gz_gazette gz')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_documents doc', 'gz.id = doc.gazette_id')
                        ->where('gz.user_id', $user_id)
                        ->where('gz.status_id', 5);
                        

        if (!empty($data['subline'])) {
            $this->db->like('gz.subject', $data['subline']);
        }
        if (!empty($data['odrNo'])) {
            $this->db->like('gz.notification_number', $data['odrNo']);
        }
        if (!empty($data['statusType'])) {
            $this->db->like('gz.status_id', $data['statusType']);
        }
        if (!empty($data['dept'])) {
            $this->db->like('gz.dept_id', $data['dept']);
        }
        if (!empty($data['nType'])) {
            $this->db->like('gz.notification_type', $data['nType']);
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

        $this->db->order_by('gz.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function getDeptGazettePublishedListCount($user_id, $data = array()){
        $this->db->select('gz.*')
                        ->from('gz_gazette gz')
                        ->where('user_id', $user_id)
                        ->where('gz.status_id', 5);

        if (!empty($data['subline'])) {
            $this->db->like('gz.subject', $data['subline']);
        }
        if (!empty($data['odrNo'])) {
            $this->db->like('gz.notification_number', $data['odrNo']);
        }
        if (!empty($data['statusType'])) {
            $this->db->where('gz.status_id', $data['statusType']);
        }
        if (!empty($data['dept'])) {
            $this->db->like('gz.dept_id', $data['dept']);
        }

        if (!empty($data['nType'])) {
            $this->db->like('gz.notification_type', $data['nType']);
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

        return $this->db->count_all_results();
    }

    public function count_total_gazettes($data = array()) {
        $this->db->select('gz.*')
                        ->from('gz_gazette gz')
                        ->where_in('gz.status_id', [1, 3, 4, 6, 10, 17, 18, 19, 20, 21]);

        if (!empty($data['subline'])) {
            $this->db->like('gz.subject', $data['subline']);
        }
        if (!empty($data['odrNo'])) {
            $this->db->like('gz.notification_number', $data['odrNo']);
        }

        if (!empty($data['statusType'])) {
            $this->db->like('gz.status_id', $data['statusType']);
        }
        if (!empty($data['dept'])) {
            $this->db->where('gz.dept_id', $data['dept']);
        }

        if (!empty($data['nType'])) {
            $this->db->where('gz.notification_type', $data['nType']);
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

        return $this->db->count_all_results();
    }
    
    public function count_total_department_gazettes($user_id, $data = array()) {
        $this->db->select('gz.*')
                        ->from('gz_gazette gz')
                        ->where('user_id', $user_id)
                        ->where('gz.status_id != 5');

        if (!empty($data['subline'])) {
            $this->db->like('gz.subject', $data['subline']);
        }
        if (!empty($data['odrNo'])) {
            $this->db->like('gz.notification_number', $data['odrNo']);
        }
        if (!empty($data['statusType'])) {
            $this->db->where('gz.status_id', $data['statusType']);
        }
        if (!empty($data['dept'])) {
            $this->db->like('gz.dept_id', $data['dept']);
        }

        if (!empty($data['nType'])) {
            $this->db->like('gz.notification_type', $data['nType']);
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

        return $this->db->count_all_results();
    }
    
    // Dept. dashboard
    public function count_total_dashboard_dept_gazettes($user_id) {
        // $sql = "SELECT (SELECT COUNT(*) FROM gz_gazette gz WHERE user_id = '{$user_id}') AS extra,
        //         (SELECT COUNT(*) FROM gz_weekly_gazette gz JOIN gz_weekly_gazette_dept_parts dp 
        //         ON gz.id = dp.gazette_id WHERE dp.user_id = '{$user_id}') AS weekly";
        // $row = $this->db->query($sql)->row();

        $extra_published = $this->db->select('*')
                                    ->from('gz_gazette')
                                    ->where('user_id', $user_id)
                                    ->where('status_id', 5)
                                    ->count_all_results();


        $extra_unpublished = $this->db->select('*')
                                    ->from('gz_gazette')
                                    ->where('user_id', $user_id)
                                    ->where('status_id != 5')
                                    ->count_all_results();

        $weekly_published = $this->db->select('fwg.*')
                                    ->from('gz_final_weekly_gazette fwg')
                                    ->join('gz_weekly_gazette_dept_parts parts', 'fwg.week = parts.week AND fwg.year = parts.year')
                                    ->where('parts.user_id', $user_id)
                                    ->where('fwg.published', 1)
                                    ->group_by('fwg.id')
                                    ->count_all_results();
                                    
        $weekly_unpublished = $this->db->select('gz.id')
                                    ->from('gz_weekly_gazette gz')
                                    ->join('gz_weekly_gazette_dept_parts dp', 'gz.id = dp.gazette_id')
                                    ->where('dp.user_id', $user_id)->where('gz.status_id !=', 5)
                                    ->count_all_results(); 
                                    
                                    
        return ($extra_published + $extra_unpublished + $weekly_published + $weekly_unpublished);
    }
    
    // Govt. press dashboard
    public function count_total_dept_to_press_submitted_gazettes() {
        // $sql = "SELECT (SELECT COUNT(*) FROM gz_gazette) AS extra,
        //         (SELECT COUNT(*) FROM gz_weekly_gazette gz JOIN gz_weekly_gazette_dept_parts dp 
        //         ON gz.id = dp.gazette_id) AS weekly";
        // $row = $this->db->query($sql)->row();

       $extra_published_gazette = $this->db->select('gz.*')
                                            ->from('gz_gazette gz')
                                            ->where('gz.status_id', 5)
                                            ->count_all_results();

        $extra_unpublished = $this->db->select('gz.*')
                                    ->from('gz_gazette gz')
                                    ->where_in('gz.status_id', [1, 3, 4, 6, 10, 17, 18, 19, 20, 21])
                                    ->count_all_results();  
                                    
        $weekly_published = $this->db->select('gz.*')
                                    ->from('gz_final_weekly_gazette gz')
                                    ->where('gz.published', 1)
                                    ->count_all_results();      
                                    
        $weekly_pending = $this->db->select('gz.*')
                                    ->from('gz_weekly_gazette gz')
                                    ->where('gz.status_id != 2')
                                    ->where('gz.status_id != 5')
                                    ->where('gz.status_id != 6')
                                    ->count_all_results();   

        return ($extra_published_gazette + $extra_unpublished + $weekly_published + $weekly_pending);
    }

    public function count_total_extraordinary_published_gazettes($user_id) {
        if ($this->session->userdata('is_dept_user')) {
            return $this->db->select('*')
                            ->from('gz_gazette')
                            ->where('status_id', 5)
                            ->count_all_results();
        } else {
            return $this->db->select('*')
                            ->from('gz_gazette')
                            ->where('user_id', $user_id)
                            ->where('status_id', 5)
                            ->count_all_results();
        }
    }

    public function count_total_extraordinary_unpublished_gazettes($user_id) {
        if ($this->session->userdata('is_admin')) {
            return $this->db->select('*')
                            ->from('gz_gazette')
                            ->where('status_id != 5')
                            ->count_all_results();
        } else {
            return $this->db->select('*')
                            ->from('gz_gazette')
                            ->where('user_id', $user_id)
                            ->where('status_id != 5')
                            ->count_all_results();
        }
    }

    public function count_total_extraordinary_submitted_gazettes($user_id) {
        if ($this->session->userdata('is_admin')) {
            return $this->db->select('*')
                            ->from('gz_gazette')
                            ->where('status_id = 1 OR status_id >= 2')
                            ->count_all_results();
        } else {
            return $this->db->select('*')
                            ->from('gz_gazette')
                            ->where('user_id', $user_id)
                            ->where('status_id = 1 OR status_id >= 2')
                            ->count_all_results();
        }
    }

    public function add_dept_gazette($data = array()) {
        try {
            // Start transaction
            $this->db->trans_begin();

            if ($data['sro_available'] == 1 && $data['is_paid'] == 0) {
                $sro_no = $this->get_generated_sro_no();
            } else {
                $sro_no = '';
            }
            
            $array_data = array(
                'user_id' => $data['user_id'],
                'dept_id' => $data['dept_id'],
                //'sl_no' => $data['sl_no'],
                'sro_available' => $data['sro_available'],
                'letter_no' => $sro_no,
                'gazette_type_id' => $data['gazette_type_id'],
                'subject' => $data['subject'],
                'notification_type' => $data['notification_type'],
                'notification_number' => $data['notification_number'],
                'status_id' => 2,
                'created_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s', time()),
                'tags' => $data['tags'],
                'is_paid' => $data['is_paid']
            );

            $this->db->insert('gz_gazette', $array_data);
            $gazette_id = $this->db->insert_id();

            // insert into gazette status table
            $stat_data = array(
                'gazette_id' => $gazette_id,
                'user_id' => $data['user_id'],
                'dept_id' => $data['dept_id'],
                // dept. Saved
                'status_id' => 2,
                'created_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s', time())
            );
            $this->db->insert('gz_gazette_status', $stat_data);

            // $processers = $this->db->from('gz_c_and_t')
            //                     ->where('verify_approve', 'Processor')    
            //                     ->where('module_id', 2)
            //                     ->get();
            // foreach($processers->result() as $processer){
            //     $processerID = $processer->id;
            // }

            // $notification_data_ct = array(
            //     'master_id' => $gazette_id,
            //     'user_id' => $processerID,
            //     'text' => "Extraordinary gazette submitted by department",
            //     'is_viewed' => 0,
            //     'pro_ver_app' => 3,
            //     'created_by' => $this->session->userdata('user_id'),
            //     'created_at' => date("Y-m-d H:i:s", time()),
            //     'status' => 1,
            //     'deleted' => 0
            // );
            // $this->db->insert('gz_notification_ct', $notification_data_ct);

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

            // UPATE in gazette table
            $gazette_array = array(
                // publish status
                'status_id' => $data['status_id'],
                'modified_by' => $data['user_id'],
                'modified_at' => $data['modified_at']
            );
            $this->db->where('id', $data['gazette_id']);
            $this->db->update('gz_gazette', $gazette_array);

            // INSERT in gazette_status table
            $stat_array = array(
                'gazette_id' => $data['gazette_id'],
                'user_id' => $data['user_id'],
                // publish status
                'status_id' => $data['status_id'],
                'created_at' => $data['created_at']
            );
            $this->db->insert('gz_gazette_status', $stat_array);

            // INSERT into notification table

            // $admin_details =  $this->db->select('*')
            //             ->from('gz_users')
            //             ->where('is_admin', 1)
            //             ->get()->row();

            // $notif_data = array(
            //     'gazette_id' => $data['gazette_id'],
            //     'user_id' => $data['user_id'],
            //     'responsible_user_id' => $admin_details->id,
            //     'text' => 'Extraordinary gazette resubmitted by department',
            //     'created_at' => $data['created_at'],
            //     'is_read' => 0
            // );

            // $this->db->insert('gz_notification', $notif_data);

            $gazette = $this->db->from('gz_gazette')
            ->where('id', $data['gazette_id'])
            ->get()->row_array();
            
            if($gazette['is_paid'] == 0){

                $admin = $this->db->from('gz_users')
                ->where('is_admin', '1')
                ->where('status', '1')
                ->get();
                // var_dump($admin);exit;
                foreach($admin->result() as $admin_user){
                $userID = $admin_user->id;
                }
                //Govt. Press Notification
                $notification_data = array(
                'gazette_id' => $data['gazette_id'],
                'user_id' => $data['user_id'],
                'responsible_user_id' => $userID,
                'dept_type' => "Department",
                'text' => "Extraordinary gazette resubmitted by department",
                'created_at' => date("Y-m-d H:i:s", time()),
                'is_read' => 0,
                );
                $this->db->insert('gz_notification', $notification_data);

            }else{
                    //Notification C&T User
                    $processors = $this->db->from('gz_c_and_t')
                                            ->where('verify_approve', 'Processor')    
                                            ->where('module_id', 2)
                                            ->where('status', 1)
                                            ->where('deleted', 0)
                                            ->get();
                    foreach($processors->result() as $processor){
                    $processorID = $processor->id;
                    }

                    $notification_data = array(
                    'master_id' => $data['gazette_id'],
                    'module_id' => 3,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $processorID,
                    'text' => "Extraordinary gazette resubmitted by department",
                    'is_viewed' => 0,
                    'pro_ver_app' => 1,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                    );
                    $this->db->insert('gz_notification_ct', $notification_data);
            }
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

    public function dept_submit_gazette($data = array()) {
        try {
            // Start transaction
            $this->db->trans_begin();

            // UPATE in gazette table
            $gazette_array = array(
                // publish status
                'status_id' => $data['status_id'],
                'modified_by' => $data['modified_by'],
                'modified_at' => $data['modified_at']
            );
            $this->db->where('id', $data['gazette_id']);
            $this->db->update('gz_gazette', $gazette_array);
            
            // UPATE in gazette documents table
            // INSERT in gazette_status table
            $stat_array = array(
                'gazette_id' => $data['gazette_id'],
                'user_id' => $data['user_id'],
                // publish status
                'status_id' => $data['status_id'],
                'created_at' => $data['created_at']
            );
            $this->db->insert('gz_gazette_status', $stat_array);

            // INSERT into notification table

            // $admin_details =  $this->db->select('*')
            //             ->from('gz_users')
            //             ->where('is_admin', 1)
            //             ->get()->row();

            // $notif_data = array(
            //     'gazette_id' => $data['gazette_id'],
            //     'user_id' => $data['user_id'],
            //     'responsible_user_id' => $admin_details->id,
            //     'text' => 'Extraordinary gazette submitted by department',
            //     'created_at' => $data['created_at'],
            //     'is_read' => 0
            // );
            // $this->db->insert('gz_notification', $notif_data);

            $gazette = $this->db->from('gz_gazette')
            ->where('id', $data['gazette_id'])
            ->get()->row_array();

            if($gazette['is_paid'] == 0){
            
                $admin = $this->db->from('gz_users')
                ->where('is_admin', '1')
                ->where('status', '1')
                ->get();
                // var_dump($admin);exit;
                foreach($admin->result() as $admin_user){
                $userID = $admin_user->id;
                }
                //Govt. Press Notification
                $notification_data = array(
                'gazette_id' => $data['gazette_id'],
                'user_id' => $data['user_id'],
                'responsible_user_id' => $userID,
                'dept_type' => "Department",
                'text' => "Extraordinary gazette submitted by department",
                'created_at' => date("Y-m-d H:i:s", time()),
                'is_read' => 0,
                );
                $this->db->insert('gz_notification', $notification_data);

            }else{
                //Notification to C&T User
                    $processors = $this->db->from('gz_c_and_t')
                                            ->where('verify_approve', 'Processor')    
                                            ->where('module_id', 2)
                                            ->where('status', 1)
                                            ->where('deleted', 0)
                                            ->get();
                    foreach($processors->result() as $processor){
                    $processorID = $processor->id;
                    }

                    $notification_data = array(
                    'master_id' => $data['gazette_id'],
                    'module_id' => 3,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $processorID,
                    'text' => "Extraordinary gazette submitted by department",
                    'is_viewed' => 0,
                    'pro_ver_app' => 1,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                    );
                    $this->db->insert('gz_notification_ct', $notification_data);
            }
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

    public function get_gazette_html_content($gazette_id) {
        return $this->db->select('*')->from('gz_gazette_text')->where('gazette_id', $gazette_id)->get()->row();
    }

    public function get_dept_info_user($user_id = 0) {
        return $this->db->select('dpt.department_name AS dept_name, dpt.id AS dept_id, '
                                . 'usr.username AS username, usr.name')->from('gz_department dpt')
                        ->join('gz_users usr', 'dpt.id = usr.dept_id')
                        ->where('usr.id', $user_id)->get()->row();
    }

    public function get_sl_no() {
        $sl_no = 0;
        $year = date("Y");
        $result = $this->db->select('id')->from('gz_gazette')->get();
        if ($result->num_rows() > 0) {
            $sl_no_data = $this->db->query('SELECT MAX(sl_no) AS sl_no FROM gz_gazette WHERE YEAR(created_at) = ' . $year);
            $sl_no = @($sl_no_data->row()->sl_no + 1);
            
        } else {
            $sl_no = 1;
        }
        return $sl_no;
    }

    public function get_gazette_types() {
        return $this->db->select('*')->from('gz_gazette_type')->get()->result();
    }

    public function get_admin_recent_gazettes() {
        return $this->db->select('gz.*, gt.gazette_type, gs.status_name, gd.department_name, doc.dept_pdf_file_path')
                        ->from('gz_gazette gz')
                        ->join('gz_department gd', 'gz.dept_id = gd.id')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_documents doc', 'gz.id = doc.gazette_id')
                        ->where_in('gz.status_id', [1, 3, 4, 6, 10, 17, 18, 19, 20, 21])
                        ->order_by('gz.id', 'DESC')
                        ->limit(5)
                        ->get()->result();
    }

    public function get_dept_recent_gazettes($user_id) {
        return $this->db->select('gz.*, gt.gazette_type, gs.status_name, doc.dept_pdf_file_path')
                        ->from('gz_gazette gz')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_documents doc', 'gz.id = doc.gazette_id')
                        ->where('gz.user_id', $user_id)
                        ->where('gz.status_id != 5')
                        ->order_by('gz.id', 'DESC')
                        ->limit(5)
                        ->get()->result();
    }

    public function get_total_published_gazettes() {
        return $this->db->select('gz.*')
                        ->from('gz_gazette gz')
                        ->where('gz.status_id', 5)
                        ->count_all_results();
    }

    public function get_total_unpublished_gazettes() {
        return $this->db->select('gz.*')
                        ->from('gz_gazette gz')
                        ->where_in('gz.status_id', [1, 3, 4, 6, 10, 17, 18, 19, 20, 21])
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
                        ->from('gz_gazette')
                        ->where('user_id', $user_id)
                        // 2 for weekly
                        ->where('gazette_type_id', 2)
                        ->count_all_results();
    }

    public function exists($id) {
        $result = $this->db->select('*')->from('gz_gazette')->where('id', $id)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function check_gazette_permission_exists($gazette_id, $user_id) {
        $result = $this->db->select('*')
                ->from('gz_gazette')
                ->where('id', $gazette_id)
                ->where('user_id', $user_id)
                ->get();

        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_gazette_details($id) {

        $data = $this->db->select('gz.*, dept.department_name, stat.status_name,
                                doc.*, gt.gazette_type, usr.username, usr.name, txt.dept_text')
                        ->from('gz_gazette gz')
                        ->join('gz_status stat', 'gz.status_id = stat.id')
                        ->join('gz_gazette_text txt', 'gz.id = txt.gazette_id', 'LEFT')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_department dept', 'gz.dept_id = dept.id')
                        ->join('gz_documents doc', 'gz.id = doc.gazette_id', 'LEFT')
                        ->join('gz_users usr', 'gz.user_id = usr.id')
                        ->where('gz.id', $id)
                        ->get()->row();

        
        return $data;
    }

    public function get_gazette_user_details($id) {
        return $this->db->select('gz.*, dept.department_name, gt.gazette_type, usr.username, usr.name, usr.email')
                        ->from('gz_gazette gz')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_department dept', 'gz.dept_id = dept.id')
                        ->join('gz_users usr', 'gz.user_id = usr.id')
                        ->where('gz.id', $id)
                        ->get()->row();
    }

    /*
     * Get single gazette status lists
     */

    public function get_gazette_status_lists($gazette_id) {
        return $this->db->select('gzs.*, gs.status_name')
                        ->from('gz_status gs')
                        ->join('gz_gazette_status gzs', 'gzs.status_id = gs.id')
                        ->where('gzs.gazette_id', $gazette_id)
                        ->order_by('id','ASC')
                        ->get()->result();
    }

    public function get_gazette_document_lists($gazette_id) {
        return $this->db->select('gz_documents_history.*')
                        ->from('gz_documents_history')
                        ->where('gazette_id', $gazette_id)
                        ->order_by('id', 'DESC')
                        ->get()->result();
    }

    public function resubmit_save_dept_gazette($data) {
        try {
            $this->db->trans_begin();

            // Update gazetee table
            $gazette_data = array(
                'status_id' => $data['status_id'],
                'modified_by' => $data['user_id'],
                'modified_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->where('id', $data['gazette_id']);
            $this->db->update('gz_gazette', $gazette_data);

            //Insert to gazette_status table
            $stat_data = array(
                'dept_id' => 0,
                'user_id' => $data['user_id'],
                'gazette_id' => $data['gazette_id'],
                // dept. resubmit save status
                'status_id' => $data['status_id'],
                'created_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_gazette_status', $stat_data);

            
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
     *
     *  Department reupload the gazette before e-Sign process
     */

    public function reupload_save_dept_gazette($data) {
        try {
            $this->db->trans_begin();

            // Update gazetee table
            $gazette_data = array(
                'status_id' => $data['status_id'],
                'modified_by' => $data['user_id'],
                'modified_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->where('id', $data['gazette_id']);
            $this->db->update('gz_gazette', $gazette_data);

            //Insert to gazette_status table
            $stat_data = array(
                'dept_id' => 0,
                'user_id' => $data['user_id'],
                'gazette_id' => $data['gazette_id'],
                // dept. resubmit save status
                'status_id' => $data['status_id'],
                'created_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_gazette_status', $stat_data);

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

            // Update into gazette table
            $this->db->where('id', $data['gazette_id']);
            $this->db->update('gz_gazette', array('status_id' => $data['status_id'], 'reject_remarks' => $data['reject_remarks']));

            $gazette_data = $this->db->select('*')->from('gz_gazette')->where('id', $data['gazette_id'])->get()->row();

            // Insert into status table
            $stat_array = array(
                'gazette_id' => $data['gazette_id'],
                'user_id' => $data['user_id'],
                'dept_id' => $data['dept_id'],
                'status_id' => $data['status_id'],
                'created_by' => $data['created_by'],
                'created_at' => $data['created_at']
            );

            $this->db->insert('gz_gazette_status', $stat_array);

            // insert into notification table
            $notif_array = array(
                'gazette_id' => $data['gazette_id'],
                'user_id' => $gazette_data->user_id,
                'responsible_user_id' => $gazette_data->user_id,
                'text' => 'Extraordinary gazette returned from the govt press',
                'is_read' => 0,
                'created_at' => $data['created_at']
            );

            $this->db->insert('gz_notification', $notif_array);

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
        return $this->db->select('*')->from('gz_documents')
                        ->where('gazette_id', $gazette_id)
                        ->get()->row();
    }

    public function save_preview_press_gazette($data) {
        try {
            $this->db->trans_begin();

            // Update gazetee table
            $gazette_data = array(
                'sl_no' => $data['sl_no'],
                'status_id' => $data['status_id'],
                'issue_date' => $data['issue_date'],
                //'sakabda_date' => $data['sakabda_date'],
                // Start Changed on 7/8/2021
                'saka_month' => $data['saka_month'],
                'saka_date' => $data['saka_date'],
                'saka_year' => $data['saka_year'],
                // Close Changed on 7/8/2021

                // letter no / SRO no
                'letter_no' => $data['sro_no'],
                'modified_by' => $data['user_id'],
                'modified_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->where('id', $data['gazette_id']);
            $this->db->update('gz_gazette', $gazette_data);

            //Insert to gazette_status table
            $stat_data = array(
                'dept_id' => 0,
                'user_id' => $data['user_id'],
                'gazette_id' => $data['gazette_id'],
                // published
                'status_id' => $data['status_id'],
                'created_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_gazette_status', $stat_data);

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
     * Publish press gazette
     */

    public function publish_press_gazette($data = array()) {
        try {
            $this->db->trans_begin();

            // Update gazetee table
            $gazette_data = array(
                'status_id' => $data['status_id'],
                'modified_by' => $data['user_id'],
                'modified_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->where('id', $data['gazette_id']);
            $this->db->update('gz_gazette', $gazette_data);

            //Insert to gazette_status table
            $stat_data = array(
                'dept_id' => 0,
                'user_id' => $data['user_id'],
                'gazette_id' => $data['gazette_id'],
                // published
                'status_id' => $data['status_id'],
                'created_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_gazette_status', $stat_data);

            $gazette_details_data = $this->db->select('*')->from('gz_gazette')->where('id', $data['gazette_id'])->get()->row();

            // insert into notification table
            $notif_array = array(
                'gazette_id' => $data['gazette_id'],
                'user_id' => $data['user_id'],
                'responsible_user_id' => $gazette_details_data->user_id,
                'text' => 'Extraordinary gazette published by Govt. Press',
                'is_read' => 0,
                'created_at' => $data['created_at']
            );

            $this->db->insert('gz_notification', $notif_array);

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
     * UPDATE department signed PDF file path name
     */

    public function update_dept_signed_pdf_path($data = array()) {
        // Update documents table
        $doc_data = array(
            'dept_signed_pdf_path' => $data['dept_signed_pdf_path']
        );

        $this->db->where('gazette_id', $data['gazette_id']);
        $this->db->update('gz_documents', $doc_data);
        return ($this->db->affected_rows() == 1) ? true : false;
    }

    /*
     * UPDATE press signed PDF file path name
     */

    public function update_press_signed_pdf_path($data = array()) {
        // Update documents table
        $doc_data = array(
            'press_signed_pdf_path' => $data['press_signed_pdf_path'],
            'press_signed_pdf_file_size' => 0
        );

        $this->db->where('gazette_id', $data['gazette_id']);
        $this->db->update('gz_documents', $doc_data);
        return ($this->db->affected_rows() == 1) ? true : false;
    }

    public function get_gazette_parts() {
        return $this->db->select('*')->from('gz_weekly_gazette_parts')->get()->result();
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

    /*     * ************ */

    public function get_notification_types() {
        return $this->db->select('*')->from('gz_notification_type')->get()->result();
    }

    public function get_department_types() {
        return $this->db->select('*')->from('gz_department')
                        ->order_by('department_name', 'ASC')
                        ->get()->result();
    }

    public function get_doc_year() {
        return $this->db->select('YEAR(created_at) AS created_date')->distinct()->from('gz_gazette')->get()->result();
    }

    public function get_status() {
        return $this->db->select('*')->from('gz_status')->get()->result();
    }

    public function get_parts() {
        return $this->db->select('*')->from('gz_gazette_parts')->get()->result();
    }

    public function count_total_record_extraordinary() {
        //return $this->db->select('*')->from('gz_gazette')->where('gazette_type_id', 1)->get()->num_rows();
        return $this->db->select('*')
                        ->from('gz_gazette')
                        ->where('gazette_type_id', 1)
                        ->where('status_id > ', 1)
                        ->count_all_results();
    }
    
    public function count_total_record_extraordinary_search($data = array()) {

        $this->db->select('gg.id,gg.subject,gg.created_at,gd.dept_word_file_path,gd.dept_pdf_file_path,gdp.department_name,gs.status_name');
        $this->db->from('gz_gazette gg');
        $this->db->join('gz_documents gd', 'gg.id=gd.id');
        $this->db->join('gz_department gdp', 'gg.dept_id=gdp.id');
        $this->db->join('gz_gazette_status ggs', 'gg.id=ggs.gazette_id');
        $this->db->join('gz_status gs', 'gg.status_id=gs.id');
        $this->db->where('gg.gazette_type_id', 1);

        if (!empty($data['dept'])) {
            $this->db->where('gg.dept_id', $data['dept']);
        }
        if (!empty($data['statusType'])) {
            $this->db->where('gg.status_id', $data['statusType']);
        }
        if (!empty($data['odrNo'])) {
            $this->db->where('gg.notification_number', $data['odrNo']);
        }
        if (!empty($data['nType'])) {
            $this->db->where('gg.notification_type', $data['nType']);
        }
        if (!empty($data['subline'])) {
            $this->db->where('gg.subject', $data['subline']);
        }
        if (!empty($data['fdate']) || !empty($data['tdate'])) {
            $this->db->where('DATE(gg.created_at)>=', date('Y-m-d', strtotime($data['fdate'])));
            $this->db->where('DATE(gg.created_at)<=', date('Y-m-d', strtotime($data['tdate'])));
        } else {
            if (!empty($data['fdate']) && !empty($data['tdate'])) {
                $this->db->where('DATE(gg.created_at)>=', date('Y-m-d', strtotime($data['tdate'])));
                $this->db->where('DATE(gg.created_at)<=', date('Y-m-d', strtotime($data['tdate'])));
            } elseif (!empty($data['fdate']) && !empty($data['tdate'])) {
                $this->db->where('DATE(gg.created_at)>=', date('Y-m-d', strtotime($data['fdate'])));
                $this->db->where('DATE(gg.created_at)<=', date('Y-m-d', strtotime($data['fdate'])));
            }
        }
        $this->db->group_by('gg.id');
        return $this->db->count_all_results();
    }

    public function count_total_record_weekly($data = array()) {
        //return $this->db->select('*')->from('gz_weekly_gazette')->where('gazette_type_id', 2)->get()->num_rows();
        
        $this->db->select('gg.id,gp.subject,gp.created_at,gd.dept_pdf_file_path,gs.status_name,gzd.department_name,ggp.part_name');
        $this->db->from('gz_weekly_gazette as gg');
        $this->db->join('gz_weekly_gazette_dept_parts as gp', 'gg.id=gp.gazette_id');
        $this->db->join('gz_weekly_gazette_documents as gd', 'gd.gazette_id=gp.gazette_id');
        $this->db->join('gz_gazette_parts as ggp', 'ggp.id=gd.part_id');
        $this->db->join('gz_status as gs', 'gs.id=gg.status_id');
        $this->db->join('gz_department as gzd', 'gzd.id=gp.dept_id');
        $this->db->where('gg.gazette_type_id', 2);

        if (!empty($data['dept'])) {
            $this->db->where('gp.dept_id', $data['dept']);
        }
        if (!empty($data['statusType'])) {
            $this->db->where('gg.status_id', $data['statusType']);
        }
        if (!empty($data['partType'])) {
            $this->db->where('gd.part_id', $data['partType']);
        }

        if (!empty($data['monthName'])) {
            $this->db->where('MONTH(gp.created_at)', $data['monthName']);
        }
        if (!empty($data['weekTime'])) {
            $this->db->where('gp.week', $data['weekTime']);
        }

        
        if (!empty($data['fdate']) || !empty($data['tdate'])) {
            $this->db->where('DATE(gp.created_at)>=', date('Y-m-d', strtotime($data['fdate'])));
            $this->db->where('DATE(gp.created_at)<=', date('Y-m-d', strtotime($data['tdate'])));
        } else {
            if (!empty($data['fdate']) && !empty($data['tdate'])) {
                $this->db->where('DATE(gp.created_at)>=', date('Y-m-d', strtotime($data['tdate'])));
                $this->db->where('DATE(gp.created_at)<=', date('Y-m-d', strtotime($data['tdate'])));
            } elseif (!empty($data['fdate']) && !empty($data['tdate'])) {
                $this->db->where('DATE(gp.created_at)>=', date('Y-m-d', strtotime($data['fdate'])));
                $this->db->where('DATE(gp.created_at)<=', date('Y-m-d', strtotime($data['fdate'])));
            }
        }
        $this->db->group_by('gg.id');
        return $this->db->count_all_results();
        
    }

    /* Default Result Report */

    public function notification_default_report($limit, $offset) {
        return $this->db->select('gg.id, gg.notification_type, gg.subject,gg.created_at,gd.dept_word_file_path,gd.dept_pdf_file_path,gdp.department_name,gs.status_name')
                        ->from('gz_gazette gg')
                        ->join('gz_documents gd', 'gg.id=gd.id')
                        ->join('gz_department gdp', 'gg.dept_id=gdp.id')
                        ->join('gz_gazette_status ggs', 'gg.id=ggs.gazette_id')
                        ->join('gz_status gs', 'gg.status_id=gs.id')
                        ->where('gg.status_id > ', 1)
                        ->group_by('gg.id', 'DESC')
                        ->order_by('gg.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    /* ./.Default Result Report */

    public function notification_report($limit, $offset, $data = array()) {
        $this->db->select('gg.id,gg.notification_type, gg.subject,gg.created_at,gd.dept_word_file_path,gd.dept_pdf_file_path,gdp.department_name,gs.status_name');
        $this->db->from('gz_gazette gg');
        $this->db->join('gz_documents gd', 'gg.id=gd.id');
        $this->db->join('gz_department gdp', 'gg.dept_id=gdp.id');
        $this->db->join('gz_gazette_status ggs', 'gg.id=ggs.gazette_id');
        $this->db->join('gz_status gs', 'gg.status_id=gs.id');
        $this->db->where('gg.gazette_type_id', 1);

        if (!empty($data['dept'])) {
            $this->db->where('gg.dept_id', $data['dept']);
        }
        if (!empty($data['statusType'])) {
            $this->db->where('gg.status_id', $data['statusType']);
        }
        if (!empty($data['odrNo'])) {
            $this->db->where('gg.notification_number', $data['odrNo']);
        }
        if (!empty($data['nType'])) {
            $this->db->where('gg.notification_type', $data['nType']);
        }
        if (!empty($data['subline'])) {
            $this->db->where('gg.subject', $data['subline']);
        }
        if (!empty($data['fdate']) || !empty($data['tdate'])) {
            $this->db->where('DATE(gg.created_at)>=', date('Y-m-d', strtotime($data['fdate'])));
            $this->db->where('DATE(gg.created_at)<=', date('Y-m-d', strtotime($data['tdate'])));
        } else {
            if ($data['fdate'] == "" && $data['tdate'] != "") {
                $this->db->where('DATE(gg.created_at)>=', date('Y-m-d', strtotime($data['tdate'])));
                $this->db->where('DATE(gg.created_at)<=', date('Y-m-d', strtotime($data['tdate'])));
            } elseif ($data['fdate'] != "" && $data['tdate'] == "") {
                $this->db->where('DATE(gg.created_at)>=', date('Y-m-d', strtotime($data['fdate'])));
                $this->db->where('DATE(gg.created_at)<=', date('Y-m-d', strtotime($data['fdate'])));
            }
        }
        $this->db->group_by('gg.id');
        $this->db->order_by('gg.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function weekly_report($limit, $offset, $data = array()) {
        $this->db->select('gg.id,gp.subject,gp.created_at,gd.dept_pdf_file_path,gs.status_name,gzd.department_name,ggp.part_name');
        $this->db->from('gz_weekly_gazette as gg');
        $this->db->join('gz_weekly_gazette_dept_parts as gp', 'gg.id=gp.gazette_id');
        $this->db->join('gz_weekly_gazette_documents as gd', 'gd.gazette_id=gp.gazette_id');
        $this->db->join('gz_gazette_parts as ggp', 'ggp.id=gd.part_id');
        $this->db->join('gz_status as gs', 'gs.id=gg.status_id');
        $this->db->join('gz_department as gzd', 'gzd.id=gp.dept_id');
        $this->db->where('gg.gazette_type_id', 2);

        if (!empty($data['dept'])) {
            $this->db->where('gp.dept_id', $data['dept']);
        }
        if (!empty($data['statusType'])) {
            $this->db->where('gg.status_id', $data['statusType']);
        }
        if (!empty($data['partType'])) {
            $this->db->where('gd.part_id', $data['partType']);
        }

        if (!empty($data['monthName'])) {
            $this->db->where('MONTH(gp.created_at)', $data['monthName']);
        }
        if (!empty($data['weekTime'])) {
            $this->db->where('gp.week', $data['weekTime']);
        }

        if (!empty($data['fdate']) || !empty($data['tdate'])) {
            $this->db->where('DATE(gp.created_at)>=', date('Y-m-d', strtotime($data['fdate'])));
            $this->db->where('DATE(gp.created_at)<=', date('Y-m-d', strtotime($data['tdate'])));
        } else {
            if (!empty($data['fdate']) && !empty($data['tdate'])) {
                $this->db->where('DATE(gp.created_at)>=', date('Y-m-d', strtotime($data['tdate'])));
                $this->db->where('DATE(gp.created_at)<=', date('Y-m-d', strtotime($data['tdate'])));
            } elseif (!empty($data['fdate']) && !empty($data['tdate'])) {
                $this->db->where('DATE(gp.created_at)>=', date('Y-m-d', strtotime($data['fdate'])));
                $this->db->where('DATE(gp.created_at)<=', date('Y-m-d', strtotime($data['fdate'])));
            }
        }
        $this->db->group_by('gg.id');
        $this->db->order_by('gg.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function default_weekly_report($limit, $offset) {
        $this->db->select('gg.id,gp.subject,gp.created_at,gd.dept_pdf_file_path,gs.status_name,gzd.department_name, ggp.part_name');
        $this->db->from('gz_weekly_gazette as gg');
        $this->db->join('gz_weekly_gazette_dept_parts as gp', 'gg.id=gp.gazette_id');
        $this->db->join('gz_weekly_gazette_documents as gd', 'gd.gazette_id=gp.gazette_id');
        $this->db->join('gz_gazette_parts as ggp', 'ggp.id=gd.part_id');
        $this->db->join('gz_status as gs', 'gs.id=gg.status_id');
        $this->db->join('gz_department as gzd', 'gzd.id=gp.dept_id');

        $this->db->order_by('gg.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    /* Frontend Gazette Search */

    public function count_total_extra_search_gazettes($data) {
        $this->db->select('gg.subject, gg.created_at, gd.dept_word_file_path AS pdf_file_path, gd.dept_pdf_file_path, gd.press_signed_pdf_path, gdp.department_name, gs.status_name');
        $this->db->from('gz_gazette gg');
        $this->db->join('gz_documents gd', 'gg.id=gd.id');
        $this->db->join('gz_department gdp', 'gg.dept_id=gdp.id');
        $this->db->join('gz_gazette_status ggs', 'gg.id=ggs.gazette_id');
        $this->db->join('gz_status gs', 'gg.status_id=gs.id');
        $this->db->where('gg.gazette_type_id', $data['gType']);
        // Status 5 for Press Approved
        $this->db->where('gg.status_id', 5);

        if ($data['sName'] != "") {
            $this->db->where('gg.subject', $data['sName']);
        }
        if ($data['keyword'] != "") {
            $this->db->like('gg.tags', $data['keyword']);
        }
        if ($data['notNum'] != "") {
            $this->db->where('gg.notification_number', $data['notNum']);
        }
        if ($data['deptID'] != "") {
            $this->db->where('gg.dept_id', $data['deptID']);
        }
        if ($data['cYear'] != "") {
            $this->db->where('YEAR(gg.created_at)', $data['cYear']);
        }
        if ($data['byDate'] != "") {
            $this->db->where('DATE(gg.created_at)<=', $data['byDate']);
        }

        if ($data['sByFdate'] != "" || $data['sByTdate'] != "") {
            $this->db->where('DATE(gg.created_at)>=', $data['sByFdate']);
            $this->db->where('DATE(gg.created_at)<=', $data['sByTdate']);
        } else {
            if ($data['sByFdate'] == "" && $data['sByTdate'] != "") {
                $this->db->where('DATE(gg.created_at)>=', $data['sByTdate']);
                $this->db->where('DATE(gg.created_at)<=', $data['sByTdate']);
            } elseif ($data['sByFdate'] != "" && $data['sByTdate'] == "") {
                $this->db->where('DATE(gg.created_at)>=', $data['sByFdate']);
                $this->db->where('DATE(gg.created_at)<=', $data['sByFdate']);
            }
        }
		
	$this->db->group_by('gg.id');
        $this->db->order_by('gg.id', 'DESC');
        return $this->db->count_all_results();
    }
    
    public function gazetta_search_report($data, $limit, $offset) {
        $this->db->select('gg.subject, gg.created_at, gd.dept_word_file_path AS pdf_file_path, gd.dept_pdf_file_path, gd.press_signed_pdf_path, gdp.department_name, gs.status_name,gg.issue_date, gg.sl_no, gg.issue_date');
        $this->db->from('gz_gazette gg');
        $this->db->join('gz_documents gd', 'gg.id=gd.id');
        $this->db->join('gz_department gdp', 'gg.dept_id=gdp.id');
        $this->db->join('gz_gazette_status ggs', 'gg.id=ggs.gazette_id');
        $this->db->join('gz_status gs', 'gg.status_id=gs.id');
        $this->db->where('gg.gazette_type_id', $data['gType']);
        // Status 5 for Press Approved
        $this->db->where('gg.status_id', 5);
        // var_dump($data['published_date']);
        // exit;
        if($data['gazette_no'] != ""){
            $this->db->where('gg.sl_no', $data['gazette_no']);
        }

        if ($data['sName'] != "") {
            $this->db->where('gg.subject', $data['sName']);
        }
        if ($data['keyword'] != "") {
            $this->db->like('gg.tags', $data['keyword']);
        }
        if ($data['notNum'] != "") {
            $this->db->where('gg.notification_number', $data['notNum']);
        }
        if ($data['deptID'] != "") {
            $this->db->where('gg.dept_id', $data['deptID']);
        }
        if ($data['cYear'] != "") {
            $this->db->where('YEAR(gg.created_at)', $data['cYear']);
        }
        if ($data['byDate'] != "") {
            $this->db->where('DATE(gg.created_at)<=', $data['byDate']);
        }

        if ($data['published_date'] != "") {
            $this->db->where('gg.issue_date' , date('l, F d, Y',strtotime($data["published_date"])));
        }

        if (!empty($data['sByFdate']) || !empty($data['sByTdate'])) {
            $this->db->where('DATE(gg.created_at)>=', date('Y-m-d', strtotime($data['sByFdate'])));
            $this->db->where('DATE(gg.created_at)<=', date('Y-m-d', strtotime($data['sByTdate'])));
        } else {
            if (!empty($data['fdate']) && !empty($data['sByTdate'])) {
                
                $this->db->where('DATE(gg.created_at)>=', date('Y-m-d', strtotime($data['sByTdate'])));
                $this->db->where('DATE(gg.created_at)<=', date('Y-m-d', strtotime($data['sByTdate'])));
            } elseif (!empty($data['sByFdate']) && !empty($data['sByTdate'])) {
                
                $this->db->where('DATE(gg.created_at)>=', date('Y-m-d', strtotime($data['sByFdate'])));
                $this->db->where('DATE(gg.created_at)<=', date('Y-m-d', strtotime($data['sByFdate'])));
            }
        }
		
	    $this->db->group_by('gg.id');
        $this->db->order_by('gg.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();

        // var_dump(date('Y-m-d',strtotime($filtered_data->issue_date)));
        // exit;
    }

    public function gazetta_search_report_count($data){

        $this->db->select('gg.subject, gg.created_at, gd.dept_word_file_path AS pdf_file_path, gd.dept_pdf_file_path, gd.press_signed_pdf_path, gdp.department_name, gs.status_name');
        $this->db->from('gz_gazette gg');
        $this->db->join('gz_documents gd', 'gg.id=gd.id');
        $this->db->join('gz_department gdp', 'gg.dept_id=gdp.id');
        $this->db->join('gz_gazette_status ggs', 'gg.id=ggs.gazette_id');
        $this->db->join('gz_status gs', 'gg.status_id=gs.id');
        $this->db->where('gg.gazette_type_id', $data['gType']);
        $this->db->where('gg.status_id', 5);

        if ($data['sName'] != "") {
            $this->db->where('gg.subject', $data['sName']);
        }
        if ($data['keyword'] != "") {
            $this->db->like('gg.tags', $data['keyword']);
        }
        if ($data['notNum'] != "") {
            $this->db->where('gg.notification_number', $data['notNum']);
        }
        if ($data['deptID'] != "") {
            $this->db->where('gg.dept_id', $data['deptID']);
        }
        if ($data['cYear'] != "") {
            $this->db->where('YEAR(gg.created_at)', $data['cYear']);
        }
        if ($data['byDate'] != "") {
            $this->db->where('DATE(gg.created_at)<=', $data['byDate']);
        }
        if ($data['published_date'] != "") {
            $this->db->where('gg.issue_date' , date('l, F d, Y',strtotime($data["published_date"])));
        }
        if ($data['sByFdate'] != "" || $data['sByTdate'] != "") {
            $this->db->where('DATE(gg.created_at)>=', $data['sByFdate']);
            $this->db->where('DATE(gg.created_at)<=', $data['sByTdate']);
        } else {
            if ($data['sByFdate'] == "" && $data['sByTdate'] != "") {
                $this->db->where('DATE(gg.created_at)>=', $data['sByTdate']);
                $this->db->where('DATE(gg.created_at)<=', $data['sByTdate']);
            } elseif ($data['sByFdate'] != "" && $data['sByTdate'] == "") {
                $this->db->where('DATE(gg.created_at)>=', $data['sByFdate']);
                $this->db->where('DATE(gg.created_at)<=', $data['sByFdate']);
            }
        }
		
	    $this->db->group_by('gg.id');
        return $this->db->count_all_results();

    }

    public function gazetta_weekly_search_report($data) {

        $this->db->select("*")->from("gz_final_weekly_gazette");

        if ($data['cYear'] != "") {
            $this->db->where("year", $data['cYear']);
        }
        if ($data['monthName'] != "") {
            $this->db->where("month", $data['monthName']);
        }

        // PDF content search in PDF files
        if ($data['keyword'] != "") {
            $this->db->like("year", $data['keyword']);
        }

        if ($data['weekTime'] != "") {
            $this->db->where("week", $data['weekTime']);
        }

        if ($data['sByFdate'] != "" || $data['sByTdate'] != "") {
            $this->db->where('DATE(created_at)>=', $data['sByFdate']);
            $this->db->where('DATE(created_at)<=', $data['sByTdate']);
        } else {
            if ($data['sByFdate'] == "" && $data['sByTdate'] != "") {
                $this->db->where('DATE(created_at)>=', $data['sByTdate']);
                $this->db->where('DATE(created_at)<=', $data['sByTdate']);
            } elseif ($data['sByFdate'] != "" && $data['sByTdate'] == "") {
                $this->db->where('DATE(created_at)>=', $data['sByFdate']);
                $this->db->where('DATE(created_at)<=', $data['sByFdate']);
            }
        }
        $this->db->order_by('id', 'DESC');
        $this->db->limit(50);
        return $this->db->get()->result();
    }


    public function gazetta_weekly_search_report_count($data){
        $this->db->select("*")->from("gz_final_weekly_gazette");

        if ($data['cYear'] != "") {
            $this->db->where("year", $data['cYear']);
        }
        if ($data['monthName'] != "") {
            $this->db->where("month", $data['monthName']);
        }

        // PDF content search in PDF files
        if ($data['keyword'] != "") {
            $this->db->like("year", $data['keyword']);
        }

        if ($data['weekTime'] != "") {
            $this->db->where("week", $data['weekTime']);
        }

        if ($data['sByFdate'] != "" || $data['sByTdate'] != "") {
            $this->db->where('DATE(created_at)>=', $data['sByFdate']);
            $this->db->where('DATE(created_at)<=', $data['sByTdate']);
        } else {
            if ($data['sByFdate'] == "" && $data['sByTdate'] != "") {
                $this->db->where('DATE(created_at)>=', $data['sByTdate']);
                $this->db->where('DATE(created_at)<=', $data['sByTdate']);
            } elseif ($data['sByFdate'] != "" && $data['sByTdate'] == "") {
                $this->db->where('DATE(created_at)>=', $data['sByFdate']);
                $this->db->where('DATE(created_at)<=', $data['sByFdate']);
            }
        }

        return $this->db->count_all_results();
    }
    /* ./.Frontend Gazette Search */

    public function get_sro_no($gazette_id) {
        $query = $this->db->select('*')
                        ->from('gz_gazette')
                        ->where('id', $gazette_id)
                        ->get()->row();

        return $query->letter_no;
    }
    
    
    /*
     * Get SRO availability is there or not in Extraordinary Gazette
     */
    public function get_sro_available($gazette_id) {
        return $this->db->select('sro_available')->from('gz_gazette')->where('id', $gazette_id)->get()->row();
    }
    
    /*
     * Generate SRO No sytem generated
     * Format XX/YYYY (01,02,03 / 2020)
     */
    
    public function get_generated_sro_no() {
        
        $query = $this->db->select('letter_no')->from('gz_gazette')->where('sro_available', 1)->get();
        
        if ($query->num_rows() > 0) {
            $query = $this->db->query("SELECT SUBSTRING_INDEX(letter_no, '/', 1) AS letter_no FROM gz_gazette 
                                    WHERE id = (SELECT MAX(id) FROM gz_gazette WHERE sro_available = 1)");
            $array1 = $query->row()->letter_no;
            $sro_no = $array1 + 1 . "/" . date('Y', time());
        } else {
            $sro_no = "01" . "/" . date('Y', time());
        }
        
        return $sro_no;
    }
    
    public function forward_reject($remarks, $curr_status, $next_status, $gazette_id) {
        try {
            
            $this->db->trans_begin();
            
            $update_array = array(
                'status_id' => $next_status,
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $this->session->userdata('user_id')
            );
            $this->db->where('id', $gazette_id);
            $this->db->update('gz_gazette', $update_array);
            
            $dept_id = $this->db->select('dept_id')
                                ->from('gz_gazette')
                                ->where('id', $gazette_id)
                                ->get()->row()->dept_id;

            if ($dept_id == '') {
                $dept_id = 0;
            }
            
            $insert_array = array(
                'gazette_id' => $gazette_id,
                'user_id' => $this->session->userdata('user_id'),
                'dept_id' => $dept_id,
                'status_id' => $next_status,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'remarks' => $remarks
            );
            $this->db->insert('gz_gazette_status', $insert_array);
            
            //SMS to user when status change to forward to pay
            if ($next_status == 17) {
                $mobile = $this->db->select('u.mobile')
                                    ->from('gz_users u')
                                    ->join('gz_gazette g', 'u.id = g.user_id')
                                    ->where('g.id', $gazette_id)
                                    ->where('u.status', 1)
                                    ->get()->row()->mobile;
                
                // load SMS library will activate once live
                $this->load->library("cdac_sms");
                // message format
                $message = "Govt. press approved the Payment of Cost Extraordinary Gazette No. {$gazette_id}. Govt. of (StateName).";
                $sms_api = new Cdac_sms();
                $template_id = "1007626132783864752";
                // send SMS using API
                $sms_api->sendOtpSMS($message, $mobile, $template_id);

                //Notification to Dept. from Govt. Press
                $dept_users = $this->db->from('gz_users')
                    ->where('dept_id', '5')
                    ->where('status', '1')
                    ->get();

                    foreach($dept_users->result() as $dept_user){
                    $deptID = $dept_user->id;
                    }

                    //Govt. Press Notification
                    $notification_data = array(
                    'gazette_id' => $gazette_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $deptID,
                    'dept_type' => "Department",
                    'text' => "Extraordinary gazette forwarded to complete the payment process",
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'is_read' => 0,
                    );
                    $this->db->insert('gz_notification', $notification_data);
            }
            
            if ($this->db->trans_status() == FALSE) {
                $this->db->trans_rollback();
                return FALSE;
            } else {
                $this->db->trans_commit();
                return TRUE;
            }
            
        } catch (Exception $ex) {
            return FALSE;
        }
    }
    
    /*
     * Department details for processing payment
     */
    public function get_department_details ($id) {
        return $this->db->select('u.name, d.department_name, u.mobile, u.email')
                    ->from('gz_users u')
                    ->join('gz_department d', 'd.id = u.dept_id')
                    ->where('u.id', $id)
                    ->get()->row();
    }
    
    public function save_payment_response ($insert_array) {
        try {
            
            $this->db->trans_begin();
            
            $transaction_data = array(
                'gazette_id' => $insert_array['gazette_id'],
                'gazette_type_id' => 1,
                'dept_ref_id' => $insert_array['dept_ref_id'],
                'challan_ref_id' => $insert_array['challan_ref_id'],
                'amount' => $insert_array['amount'],
                'pay_mode' => $insert_array['pay_mode'],
                'bank_trans_id' => $insert_array['bank_trans_id'],
                'bank_name' => $insert_array['bank_name'],
                'bank_trans_msg' => $insert_array['bank_trans_msg'],
                'bank_trans_time' => $insert_array['bank_trans_time'],
                'trans_status' => $insert_array['trans_status'],
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_payment_of_cost_payment_details', $transaction_data);
            $payment_id = $this->db->insert_id();
            
            // INSERT INTO the status history Table
            $insert_stat = array(
                'payment_id' => $payment_id,
                // Change of Surname
                'payment_status' => $insert_array['trans_status'],
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_payment_of_cost_payment_status_history', $insert_stat);
            
            if ($insert_array['trans_status'] == 'S') {
                $status = 18;

                $gazette_details_data = $this->db->select('*')
                                                ->from('gz_gazette')
                                                ->where('id', $insert_array['gazette_id'])
                                                ->get()->row();
                $admin = $this->db->from('gz_users')
                                    ->where('is_admin', '1')
                                    ->where('status', '1')
                                    ->get()->row();
                                    

                $notification_data_ct = array(
                    'gazette_id' => $insert_array['gazette_id'],
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $admin->id,
                    'text' => "Extraordinary gazette Payment Successful",
                    'is_read' => 0,
                    'created_at' => date("Y-m-d H:i:s", time()),
                );

                $this->db->insert('gz_notification', $notification_data_ct);
                
            } else if ($insert_array['trans_status'] == 'F') {
                $status = 20;
            } else if ($insert_array['trans_status'] == 'P') {
                $status = 19;
            } else if ($insert_array['trans_status'] == 'I') {
                $status = 19;
            } else if ($insert_array['trans_status'] == 'X') {
                $status = 21;
            }
            
            $master_data = array(
                'status_id' => $status,
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $this->session->userdata('user_id')
            );
            $this->db->where('id', $insert_array['gazette_id']);
            $this->db->update('gz_gazette', $master_data);
            
            $dept_id = $this->db->select('dept_id')
                                ->from('gz_gazette')
                                ->where('id', $insert_array['gazette_id'])
                                ->get()->row()->dept_id;

            if ($dept_id == '') {
                $dept_id = 0;
            }
            
            $status_history = array(
                'gazette_id' => $insert_array['gazette_id'],
                'user_id' => $status,
                'dept_id' => $dept_id,
                'status_id' => $status,
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => $this->session->userdata('user_id')
            );
            $this->db->insert('gz_gazette_status', $status_history);
            
            if ($this->db->trans_status() == FALSE) {
                $this->db->trans_rollback();
                return FALSE;
            } else {
                $this->db->trans_commit();
                return TRUE;
            }
        } catch (Exception $ex) {
            return FALSE;
        }
    }
}

?>