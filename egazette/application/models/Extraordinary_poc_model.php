<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php

class Extraordinary_poc_model extends CI_Model {

    public function count_total_gazettes($status) {
        return $this->db->select('*')
                        ->from('gz_gazette')
                        ->where_not_in('status_id', $status)
                        ->where('is_paid', 1)
                        ->count_all_results();
    }
    
    public function get_total_gazettes_unpublished($status, $limit, $offset) {
        return $this->db->select('gz.*, gt.gazette_type, gs.status_name, doc.dept_word_file_path, doc.dept_pdf_file_path, doc.press_pdf_file_path, doc.dept_signed_pdf_path, doc.press_signed_pdf_path, dept.department_name')
                        ->from('gz_gazette gz')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_documents doc', 'gz.id = doc.gazette_id')
                        ->join('gz_department dept', 'dept.id = gz.dept_id')
                        ->where_not_in('gz.status_id', $status)
                        ->order_by('gz.id', 'DESC')
                        ->where('is_paid', 1)
                        ->limit($limit, $offset)
                        ->get()->result();
    }
    
    public function get_total_gazettes_published($limit, $offset) {
        return $this->db->select('gz.*, gt.gazette_type, gs.status_name, doc.dept_word_file_path, doc.dept_pdf_file_path, doc.press_pdf_file_path, doc.dept_signed_pdf_path, doc.press_signed_pdf_path, dept.department_name')
                        ->from('gz_gazette gz')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_documents doc', 'gz.id = doc.gazette_id')
                        ->join('gz_department dept', 'dept.id = gz.dept_id')
                        ->where('gz.status_id', 5)
                        ->where('is_paid', 1)
                        ->order_by('gz.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }
    
    public function exists($id) {
        $result = $this->db->select('*')->from('gz_gazette')->where('id', $id)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function get_gazette_details($id) {
        return $this->db->select('gz.*, dept.department_name, stat.status_name,
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
    }
    
    /*
     * Get single gazette status lists
     */
    public function get_gazette_status_lists($gazette_id) {
        return $this->db->select('gzs.*, gs.status_name')
                        ->from('gz_status gs')
                        ->join('gz_gazette_status gzs', 'gzs.status_id = gs.id')
                        ->where('gzs.gazette_id', $gazette_id)
                        ->order_by('gzs.id','ASC')
                        ->get()->result();
    }
    
    public function forward_reject($remarks, $curr_status, $next_status, $gazette_id) {
        try {
            //  echo $curr_status;
            //  echo $next_status;
            //  exit;
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
                        
            if($next_status == 8){
                //Notification to C&T User
                    $verifiers = $this->db->from('gz_c_and_t')
                                    ->where('verify_approve', 'Verifier')    
                                    ->where('module_id', 2)
                                    ->where('status', 1)
                                    ->where('deleted', 0)
                                    ->get();
                        foreach($verifiers->result() as $verifier){
                        $verifierID = $verifier->id;
                        }

                        $notification_data = array(
                        'master_id' => $gazette_id,
                        'module_id' => 3,
                        'user_id' => $this->session->userdata('user_id'),
                        'responsible_user_id' => $verifierID,
                        'text' => "Extraordinary gazette forwarded from c & t processor",
                        'is_viewed' => 0,
                        'pro_ver_app' => 1,
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                        );
                        $this->db->insert('gz_notification_ct', $notification_data);
            }else if($next_status == 9){

                $approvers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Approver')    
                                ->where('module_id', 2)
                                ->where('status', 1)
                                ->where('deleted', 0)
                                ->get();
                    foreach($approvers->result() as $approver){
                        $approverID = $approver->id;
                    }

                    $notification_data = array(
                    'master_id' => $gazette_id,
                    'module_id' => 3,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $approverID,
                    'text' => "Extraordinary gazette forwarded from c & t verifier",
                    'is_viewed' => 0,
                    'pro_ver_app' => 1,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                    );
                    $this->db->insert('gz_notification_ct', $notification_data);

            }else if($next_status == 10){

                $admin = $this->db->from('gz_users')
                ->where('is_admin', '1')
                ->where('status', '1')
                ->get();
                
                foreach($admin->result() as $admin_user){
                    $userID = $admin_user->id;
                }

                //Govt. Press Notification
                $notification_data = array(
                'gazette_id' => $gazette_id,
                'user_id' => $this->session->userdata('user_id'),
                'responsible_user_id' => $userID,
                'dept_type' => "Department",
                'text' => "Extraordinary gazette forwarded from c & t approver",
                'created_at' => date("Y-m-d H:i:s", time()),
                'is_read' => 0,
                );
                $this->db->insert('gz_notification', $notification_data);

            }else if($next_status == 11){
                //return to dept. from processor
                $dept = $this->db->from('gz_users')
                                    ->where('dept_id', '5')
                                    ->where('status', '1')
                                    ->get()->row();
                
                //Govt. Press Notification
                $notification_data = array(
                'gazette_id' => $gazette_id,
                'user_id' => $this->session->userdata('user_id'),
                'responsible_user_id' => $dept->id,
                'dept_type' => "Department",
                'text' => "Extraordinary gazette returned from c & t processor",
                'created_at' => date("Y-m-d H:i:s", time()),
                'is_read' => 0,
                );
                $this->db->insert('gz_notification', $notification_data);
            }else if($next_status == 12){
                //return to dept. from verifier
                $dept = $this->db->from('gz_users')
                                    ->where('dept_id', '5')
                                    ->where('status', '1')
                                    ->get()->row();
                
                //Govt. Press Notification
                $notification_data = array(
                'gazette_id' => $gazette_id,
                'user_id' => $this->session->userdata('user_id'),
                'responsible_user_id' => $dept->id,
                'dept_type' => "Department",
                'text' => "Extraordinary gazette returned from c & t verifier",
                'created_at' => date("Y-m-d H:i:s", time()),
                'is_read' => 0,
                );
                $this->db->insert('gz_notification', $notification_data);
            }else if($next_status == 13){
                //return to dept. from verifier
                $dept = $this->db->from('gz_users')
                                    ->where('dept_id', '5')
                                    ->where('status', '1')
                                    ->get()->row();
                
                //Govt. Press Notification
                $notification_data = array(
                'gazette_id' => $gazette_id,
                'user_id' => $this->session->userdata('user_id'),
                'responsible_user_id' => $dept->id,
                'dept_type' => "Department",
                'text' => "Extraordinary gazette returned from c & t approver",
                'created_at' => date("Y-m-d H:i:s", time()),
                'is_read' => 0,
                );
                $this->db->insert('gz_notification', $notification_data);
            }else if($next_status == 14){
                //return to dept. from verifier
                $dept = $this->db->from('gz_users')
                                    ->where('dept_id', '5')
                                    ->where('status', '1')
                                    ->get()->row();
                
                //Govt. Press Notification
                $notification_data = array(
                'gazette_id' => $gazette_id,
                'user_id' => $this->session->userdata('user_id'),
                'responsible_user_id' => $dept->id,
                'dept_type' => "Department",
                'text' => "Extraordinary gazette rejected from c & t approver",
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
}

?>
