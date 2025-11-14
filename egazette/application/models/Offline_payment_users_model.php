<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php

class Offline_payment_users_model extends CI_Model {

public function get_total_offline_approver_users(){
        return $this->db->select('*')
				->from('gz_offline_approver_users')
				->where('deleted', 0)
				->count_all_results();
    }
	
	public function check_mobile_login($mobile, $password) {
        $this->db->select('password')
                ->from('gz_offline_approver_users')
                ->where('login_id', $mobile)
                ->where('status', 1);

        $hash = $this->db->get()->row('password');
		//echo $this->db->last_query(); exit;
        return $this->verify_password_hash($password, $hash);
    }
	
	public function get_user_data($mobile) {
        return $this->db->select('*')
                        ->from('gz_offline_approver_users')
                        ->where('login_id', $mobile)
                        ->get()->row();
    }
	
	public function verify_password_hash($password, $hash) {
        return password_verify($password, $hash);
    }
	
	public function get_total_offline_pay_count_applicant() {
        return $this->db->select('id')
                        ->from('gz_change_of_name_surname_master')
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->where('current_status', 9)
                        ->where('offline_pay_status', 1)
                        ->count_all_results();
    }
	
	public function get_total_offline_payment_applicant($limit, $offset) {
        return $this->db->select('m.gazette_type_id, m.file_no, s.status_name, m.id, m.created_at')
                        ->from('gz_change_of_name_surname_master m')
                        ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                        ->where('m.status', 1)
                        ->where('m.deleted', 0)
                        ->where('current_status', 9)
						->where('offline_pay_status', 1)
                        ->order_by('m.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }
	
	public function view_details_offline_payment($id) {

        $query1 = $this->db->select('p.*,g.gazette_type,d.id as district_id, u.name, u.father_name, s.status_name, u.mobile, u.email, u.address, st.state_name,st.id as state_id, d.district_name, po.block_name as block_name')
                        ->from('gz_change_of_name_surname_master p')
                        ->join('gz_gazette_type g', 'p.gazette_type_id = g.id')
                        ->join('gz_applicants_details u', 'p.user_id = u.id')
                        ->join('gz_change_of_name_surname_status_master s', 's.id = p.current_status')
                        ->join('gz_states_master st', 'st.id = p.state_id')
                        ->join('gz_district_master d', 'd.id = p.district_id')
                        ->join('gz_block_master po', 'po.id = p.block_ulb_id','left')
                        ->where('p.deleted', '0')
                        ->where('p.type', 'block')
                        ->where('p.id', $id)
                        ->get()->row();
        $query2 = $this->db->select('p.*, g.gazette_type, u.name, u.father_name, s.status_name, u.mobile, u.email, u.address, st.state_name, d.district_name, po.ulb_name  as block_name')
                        ->from('gz_change_of_name_surname_master p')
                        ->join('gz_gazette_type g', 'p.gazette_type_id = g.id')
                        ->join('gz_applicants_details u', 'p.user_id = u.id')
                        ->join('gz_change_of_name_surname_status_master s', 's.id = p.current_status')
                        ->join('gz_states_master st', 'st.id = p.state_id')
                        ->join('gz_district_master d', 'd.id = p.district_id')
                        ->join('gz_ulb_master po', 'po.id = p.block_ulb_id','left')
                        ->where('p.deleted', '0')
                        ->where('p.type', 'ulb')
                        ->where('p.id', $id)
                        ->get()->row();
         //print_r($this->db->last_query()); exit;
        if (!empty($query1) && empty($query2)) {
            return $query1;
        } else if (empty($query1) && !empty($query2)) {
            return $query2;
        } else if (!empty($query1) && !empty($query2)) {
            $query3 = array_merge($query1, $query2);
            return array_unique($query3);
        }
    }
	
	public function save_change_name_surname_offline_trans_status($hid_record_id,$hid_file_num,$pay_mode,$ref_no,$amount) {
		
		try {
			$this->db->trans_begin();
			
			$transaction_data = array(
                'change_name_surname_id' => $hid_record_id,
                'file_number' => $hid_file_num,
                'dept_ref_id' => 'N/A',
                'challan_ref_id' => $ref_no,
                'amount' => $amount,
                'pay_mode' => $pay_mode,
                'bank_trans_id' => $ref_no,
                'bank_name' => 'N/A',
                'bank_trans_msg' => 'Successful Transaction',
                'bank_trans_time' => date('Y-m-d H:i:s', time()),
                'trans_status' => 'S',
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_change_of_name_surname_payment_details', $transaction_data);
            $last_id = $this->db->insert_id();
			
			$insert_stat = array(
                'payment_id' => $last_id,
                // Change of Surname
                'payment_type' => 'COS',
                'payment_status' => 'S',
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_payment_status_history', $insert_stat);
			
			
			$admin = $this->db->from('gz_users')
                                    ->where('is_admin', '1')
                                    ->where('status', '1')
                                    ->get()->row();
                
                $notification_data_ct = array(
                    'master_id' => $hid_record_id,
                    'module_id' => 2,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $admin->id,
                    'text' => "Payment Successful",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_govt', $notification_data_ct);
				
				 $master_data = array(
                'current_status' => 10,
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $this->session->userdata('user_id'),
				'offline_pay_status' =>2
				);


            $this->db->where('id', $hid_record_id);
            $this->db->update('gz_change_of_name_surname_master', $master_data);
			
			$status_history = array(
                    'gz_master_id' => $hid_record_id,
                    'change_of_name_surname_status' =>10,
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'created_by' => $this->session->userdata('user_id')
                );
                $this->db->insert('gz_change_of_name_surname_status_his', $status_history);
			
			if ($this->db->trans_status() == FALSE) {
                $this->db->trans_rollback();
                return FALSE;
            } else {
                $this->db->trans_commit();
                return TRUE;
            }
		}
		catch (Exception $e) {
            return FALSE;
        }
	}

public function get_total_parter_count() {

        $this->db->select('p.*,g.gazette_type,h.status_det');
        $this->db->from('gz_change_of_partnership_master p');
        $this->db->join('gz_gazette_type g', 'p.gazette_type_id = g.id');
        $this->db->join('gz_par_sur_status_master h', 'p.cur_status = h.id');
        $this->db->where('p.deleted', '0');
        $this->db->where('p.gazette_type_id', '1');
        $this->db->where('p.offline_pay_status', '1');

        return $this->db->count_all_results();
    }

public function get_total_parter($limit, $offset) {

        $this->db->select('p.*,u.name,h.status_det');
        $this->db->from('gz_change_of_partnership_master p');
        $this->db->join('gz_applicants_details u', 'p.user_id = u.id');
        $this->db->join('gz_par_sur_status_master h', 'p.cur_status = h.id');
        $this->db->where('p.deleted', '0');
        $this->db->where('p.gazette_type_id', '1');
        $this->db->where('p.offline_pay_status', '1');

        /*if ($this->session->userdata('is_applicant')) {
            $this->db->where('p.user_id', $this->session->userdata('user_id'));
        } else if ($this->session->userdata('is_c&t')) {
            if ($this->session->userdata('is_verifier_approver') == 'Verifier') {
                // $arr = array();
                // array_push($arr, "1", "10", "12", "15");
                // $this->db->where_in('p.cur_status', $arr);
            } else if ($this->session->userdata('is_verifier_approver') == 'Approver') {
                $arr = array();
                array_push($arr, "2", "9", "5");
                $this->db->where('p.cur_status >=', 1);
            }
        } else if ($this->session->userdata('is_igr')) {
            if ($this->session->userdata('is_verifier_approver') == 'Verifier') {
                $arr = array();
                array_push($arr, "3", "14");
                $this->db->where('p.cur_status >=', 3);
            } else if ($this->session->userdata('is_verifier_approver') == 'Approver') {
                $arr = array();
                array_push($arr, "4", "11");
                $this->db->where('p.cur_status >=', 4);
            }
        }*/

        $this->db->order_by('p.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function View_details_partnership_offline($id) {

        return $this->db->select('p.*,d.press_pdf,g.gazette_type,u.name,u.father_name,ps.status_det,u.mobile,u.address,u.email,ps.status_det, st.state_name, di.district_name, po.police_station_name')
                        ->from('gz_change_of_partnership_master p')
                        ->join('gz_change_of_partnetship_doument_det d', 'd.gz_mas_type_id = p.id')
                        ->join('gz_par_sur_status_master ps', 'p.cur_status = ps.id')
                        ->join('gz_gazette_type g', 'p.gazette_type_id = g.id')
                        ->join('gz_applicants_details u', 'p.user_id = u.id')
                        ->join('gz_states_master st', 'st.id = p.state_id')
                        ->join('gz_district_master di', 'di.id = p.district_id')
                        ->join('gz_police_station_master po', 'po.id = p.police_station_id')
                        ->where('p.deleted', '0')
                        ->where('p.id', $id)
                        ->get()->row();
                        echo $this->db->last_query(); exit();
    }

    public function save_change_partnership_offline_trans_status($hid_record_id,$hid_file_num,$pay_mode,$ref_no,$amount)
    {
        try {

            $this->db->trans_begin();

            $transaction_data = array(
                'par_id' => $hid_record_id,
                'file_no' => $hid_file_num,
                'deptRefId' => 'N/A',
                'challanRefId' => $ref_no,
                'amount' => $amount,
                'pay_mode' => $pay_mode,
                'bank_trans_id' => $ref_no,
                'bank_name' => 'N/A',
                'bank_trans_msg' => 'Successful Transaction',
                'bank_trans_time' => date('Y-m-d H:i:s', time()),
                'bankTransactionStatus' => 'S',
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => 1,
                'deleted' => 0
            );

            $this->db->insert('gz_change_of_partnership_make_pay', $transaction_data);
            $last_id = $this->db->insert_id();

                $status = array(
                    'gz_mas_type_id' => $hid_record_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'par_status' => 7,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_change_of_par_status_his', $status);

                $array_data = array(
                    'cur_status' => 7,
                    'is_paid' => 1,
                    'offline_pay_status' => 2
                );

                $this->db->where('id', $hid_record_id);
                $this->db->update('gz_change_of_partnership_master', $array_data);

            $insert_stat = array(
                'payment_id' => $last_id,
                // Change of Surname
                'payment_type' => 'COP',
                'payment_status' => 'S',
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_payment_status_history', $insert_stat);


            $admin = $this->db->from('gz_users')
                                    ->where('is_admin', '1')
                                    ->where('status', '1')
                                    ->get()->row();
                $notification_data_ct = array(
                    'master_id' => $hid_record_id,
                    'module_id' => 1,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => 1,
                    'text' => "Payment Successful",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_govt', $notification_data_ct);

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

    public function count_total_gazettes_offline_pay($status) {
        return $this->db->select('*')
                        ->from('gz_gazette')
                        ->where_not_in('status_id', $status)
                        ->where('is_paid', 1)
                        ->where('offline_pay_status', 1)
                        ->count_all_results();
    }

    public function get_total_gazettes_unpublished_offline_pay($status, $limit, $offset) {
        return $this->db->select('gz.*, gt.gazette_type, gs.status_name, doc.dept_word_file_path, doc.dept_pdf_file_path, doc.press_pdf_file_path, doc.dept_signed_pdf_path, doc.press_signed_pdf_path, dept.department_name')
                        ->from('gz_gazette gz')
                        ->join('gz_gazette_type gt', 'gz.gazette_type_id = gt.id')
                        ->join('gz_status gs', 'gz.status_id = gs.id')
                        ->join('gz_documents doc', 'gz.id = doc.gazette_id')
                        ->join('gz_department dept', 'dept.id = gz.dept_id')
                        ->where_not_in('gz.status_id', $status)
                        ->order_by('gz.id', 'DESC')
                        ->where('gz.offline_pay_status', 1)
                        ->where('is_paid', 1)
                        ->limit($limit, $offset)
                        ->get()->result();
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

    public function save_departmental_offline_payment($hid_record_id,$hid_dept_id,$hid_gazette_id,$pay_mode,$ref_no,$amount) {
        try {
            
            $this->db->trans_begin();
            
            $transaction_data = array(
                'gazette_id' => $hid_record_id,
                'gazette_type_id' => 1,
                'dept_ref_id' => $hid_dept_id,
                'challan_ref_id' => 'N/A',
                'amount' => $amount,
                'pay_mode' => $pay_mode,
                'bank_trans_id' => $ref_no,
                'bank_name' => 'N/A',
                'bank_trans_msg' => 'Successful Transaction',
                'bank_trans_time' => date('Y-m-d H:i:s', time()),
                'trans_status' => 'S',
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_payment_of_cost_payment_details', $transaction_data);
            $payment_id = $this->db->insert_id();
            
            // INSERT INTO the status history Table
            $insert_stat = array(
                'payment_id' => $payment_id,
                // Change of Surname
                'payment_status' => 'S',
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_payment_of_cost_payment_status_history', $insert_stat);
        
                $status = 18;

                $gazette_details_data = $this->db->select('*')
                                                ->from('gz_gazette')
                                                ->where('id', $hid_record_id)
                                                ->get()->row();
                $admin = $this->db->from('gz_users')
                                    ->where('is_admin', '1')
                                    ->where('status', '1')
                                    ->get()->row();
                                    
                $notification_data_ct = array(
                    'gazette_id' => $hid_record_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $admin->id,
                    'text' => "Extraordinary gazette Payment Successful",
                    'is_read' => 0,
                    'created_at' => date("Y-m-d H:i:s", time()),
                );

                $this->db->insert('gz_notification', $notification_data_ct);
                 
            $master_data = array(
                'status_id' => $status,
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $this->session->userdata('user_id'),
                'offline_pay_status' => 2
            );
            $this->db->where('id', $hid_record_id);
            $this->db->update('gz_gazette', $master_data);
            
            $dept_id = $this->db->select('dept_id')
                                ->from('gz_gazette')
                                ->where('id', $hid_record_id)
                                ->get()->row()->dept_id;

            if ($dept_id == '') {
                $dept_id = 0;
            }
            
            $status_history = array(
                'gazette_id' => $hid_record_id,
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