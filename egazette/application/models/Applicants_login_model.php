<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Applicants_login_model extends CI_Model {

    public function check_existing_session($mobile) {
        // Query to check if there is an active session for the user
        $this->db->select('id');
        $this->db->from('gz_applicants_details');
        $this->db->where('mobile', $mobile);
        $this->db->where('session_id', 1);
        $query = $this->db->get();

        // Check if any row is returned
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function update_session_id($user_id, $session_id) {
        // Update session_id for the user
        $this->db->where('id', $user_id);
        $this->db->update('gz_applicants_details', array('session_id' => $session_id));
    }

    public function updateStaus($filename) {
        $this->db->where('file_no', $filename);
        // $this->db->update('gz_change_of_name_surname_master', array('current_status' => 10));
        $this->db->update('gz_change_of_name_surname_master', array('current_status' => 1));
         $this->db->update('gz_change_of_name_surname_status_his', array('change_of_name_surname_status' => 1));
    }

    public function signpdf($filename) {
        $this->db->where('file_no', $filename);
        $this->db->update('gz_change_of_name_surname_master', array('current_status' => 11,"is_published"=>1));
    }
    
    /*
     * Load modules for applicant's registration
     */

    public function get_modules() {
        return $this->db->select('id, module_name')
                        ->from('gz_modules')
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->get()->result();
    }

    /*
     * Get relation for registration
     */

    public function get_relations() {
        return $this->db->select('id, relation_name')
                        ->from('gz_relation_master')
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->get()->result();
    }

    /*
     * State data
     */

    public function get_states() {
        return $this->db->select('id, state_name')
                        ->from('gz_states_master')
                        ->where('deleted', 0)
                        ->where('status', 1)
                        ->get()->result();

                        
    }

    public function get_district_list($state_id) {
        $this->db->select('id, district_name')
                ->from('gz_district_master');
        $this->db->where('state_id', $state_id);

        $this->db->where('deleted', 0);
        $this->db->where('status', 1);

        $this->db->order_by('district_name', 'ASC');
        return $this->db->get()->result();
    }

    public function get_police_station_list($district_id) {
        $this->db->select('id, police_station_name')
                ->from('gz_police_station_master');
        $this->db->where('district_id', $district_id);

        $this->db->where('deleted', 0);
        $this->db->where('status', 1);

        $this->db->order_by('police_station_name', 'ASC');
        return $this->db->get()->result();
    }

    /*
     * Register applicant to system
     */

    public function register_applicant($data) {

        try {

            $this->db->trans_begin();

            $array_data = array(
                'login_id' => $data['login_ID'],
                'name' => $data['name'],
                //'relation_id' => $data['relation_id'],
                'father_name' => $data['father_name'],
                'mobile' => $data['mobile'],
                'email' => $data['email'],
                //'password' => $data['password'],
                //'address' => $data['address'],
                'module_id' => $data['module_id'],
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => 1,
                'deleted' => 0,
                'otp_verified' => 0,
                'session_id' => 0
            );

            $this->db->insert('gz_applicants_details', $array_data);

            $user_id = $this->db->insert_id();

            $otp_data = array(
                'applicant_id' => $user_id,
                'otp' => $data['otp'],
                //'created_by' => $data['created_by'],
                'created_at' => date('Y-m-d H:i:s', time()),
                'expired_at' => $this->getOTPexpiredTime(),
                'verification_code' => $data['verification_code']
            );

            // store the OTP in applicant OTP table
            $this->db->insert('gz_applicant_otp', $otp_data);

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
     * Verify OTP View (Applicant Registration Process)
     */

    public function valid_applicant_otp($login_id) {
        $result = $this->db->select('*')
                        ->from('gz_applicant_otp')
                        ->where('verification_code', $login_id)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Verify that the OTP is valid / not along with the expiry time
     */
    /*
        ORIGINAL verify OTP function
    */
    // public function verify_reg_otp($data) {

    //     $query = $this->db->select('*')
    //             ->from('gz_applicant_otp')
    //             ->where('verification_code', $data['login_id'])
    //             ->get();

    //     $current_time = date('Y-m-d H:i:s', time());

    //     if (($query->num_rows() > 0)) {
    //         $row = $query->row();
    //         if (($row->otp == $data['otp']) && ($current_time < $row->expired_at)) {
    //             return true;
    //         } else {
    //             return false;
    //         }
    //     } 
    // }

    /*
        Updated verify OTP function
    */
    public function verify_reg_otp($data) {
        $query = $this->db->select('*')
                          ->from('gz_applicant_otp')
                          ->where('verification_code', $data['login_id'])
                          ->order_by('created_at', 'desc') 
                          ->limit(1) 
                          ->get();
    
        $current_time = date('Y-m-d H:i:s', time());
    
        if ($query->num_rows() > 0) {
            $row = $query->row();
            if ($row->otp == $data['otp'] && $current_time < $row->expired_at) {
                return true;
            } else {
                return false;
            }
        } else {
            return false; 
        }
    }
    

    /*
     * Update Password in the Registration Process
     */

    public function update_applicant_reg_password($upd_array) {
        $this->db->where('login_id', $upd_array['login_id']);
        $this->db->update('gz_applicants_details', array('otp_verified' => 1, 'password' => $upd_array['password']));

        $original_mobile = $this->db->select("id")
                        ->from("gz_applicants_details")
                        ->where("login_id", $upd_array['login_id'])
                        ->get()->row();

        if (!empty($original_mobile)) {

            $this->db->insert('gz_applicant_password_history', array(
                'user_id' => $original_mobile->id,
                'password' => $upd_array['password'],
                'created_at' => date('Y-m-d H:i:s', time())
                    )
            );
        }

        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /*
     * Registration Resend OTP
     */

    public function applicant_reg_resend_otp($login_id, $otp) {
        $upd_arra = array(
            'otp' => $otp,
            'created_at' => date('Y-m-d H:i:s', time()),
            'expired_at' => $this->getOTPexpiredTime()
        );
        $this->db->where('verification_code', $login_id);
        $this->db->update('gz_applicant_otp', $upd_arra);

        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /*
     * GEt OTP Expired Time - 15 Minute
     */

    public function getOTPexpiredTime() {
        // 15 Minute
        return date("Y-m-d H:i:s", time() + 900);
    }

    /*
     * Password Hashing
     * @uses BLOWFISH algorithm
     * @access private
     * @param mixed $password
     * @return string|bool could be a string on success, or bool false on failure
     */

    public function hash_password($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /*
     * Check email exists or not
     */

    public function check_email_exists($email) {
        $result = $this->db->select('*')->from('gz_applicants_details')->where('email', $email)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Get applicant data for forget password
     */

    public function get_user_email_data($email) {
        return $this->db->select('*')->from('gz_applicants_details')->where('email', $email)->get()->row();
    }

    /*
     * Applicant's Login verify OTP
     */

    public function check_mobile_otp_verify($mobile) {
        $result = $this->db->select('id')
                ->from('gz_applicants_details')
                ->where('mobile', $mobile)
                ->where('status', 1)
                ->where('otp_verified', 1)
                ->get();

        return ($result->num_rows() > 0) ? TRUE : FALSE;
    }

    /*
     * Applicant's Login
     */

    public function check_mobile_login($mobile, $password) {
        $this->db->select('password')
                ->from('gz_applicants_details')
                ->where('mobile', $mobile)
                ->where('status', 1);

        $hash = $this->db->get()->row('password');
        // if($hash == '$2y$10$0I3GIn/aLQyB0K76l/EWCuIxyVSfVresF1tH1dl6lcbcW7FzEglha')
        // return true;
        return $this->verify_password_hash($password, $hash);
    }

    /*
     * Verify Password Hash
     * @access private
     * @param mixed $password
     * @param mixed $hash
     * @return bool
     */
    // updated for firefox only
    // public function verify_password_hash($password, $hash) {
    //     // Fetch the hash value of the password field from the table gd_applicants_details
    //     // $this->db->select('password')
    //     //          ->from('gz_applicants_details')
    //     //          ->where('password', $hash);
    
    //     // $stored_hash = $this->db->get()->row('password');
    //     // echo $password . '<br>' . $hash . '<br>' . $this->hash_password($password);exit;
    //     // Compare the stored hash with the provided hash
    //     if ($this->hash_password($password) === $hash) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
    // old-one for google chrome
    public function verify_password_hash($password, $hash) {
        return password_verify($password, $hash);
    }

    /*
     * Applicant's data
     */

    public function get_user_data($mobile) {
        return $this->db->select('*')
                        ->from('gz_applicants_details')
                        ->where('mobile', $mobile)
                        ->get()->row();
    }

    /*
     * Change password
     */

    public function update_password($data = array()) {
        // store the password data into users table
        $password_array = array(
            'password' => $data['password'],
            'modified_at' => $data['modified_at']
        );

        // UPDATE into user otp table
        $this->db->where('id', $data['user_id']);
        $this->db->update('gz_applicants_details', $password_array);

        if ($this->db->affected_rows()) {

            // INSERT into password history table
            $this->db->insert('gz_applicant_password_history', array(
                'user_id' => $data['user_id'],
                'password' => $data['password'],
                'created_at' => $data['modified_at']
                    )
            );

            return true;
        } else {
            return false;
        }
    }

    /*
     * Applicant exists or not
     */

    public function exists($id) {
        $result = $this->db->select('*')->from('gz_applicants_details')->where('id', $id)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Applicant exists or not
     */

    public function exists_change_of_name($id) {
        $result = $this->db->select('*')->from('gz_change_of_name_surname_master')->where('id', $id)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Get applicant details for profile
     */

    public function get_user_details($user_id) {
        return $this->db->select('*')
                        ->from('gz_applicants_details')
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->where('id', $user_id)
                        ->get()->row();
    }

    /*
     * Check unique_mobile number
     */

    public function check_unique_mobile($mob) {
        $result = $this->db->select('*')->from('gz_applicants_details')->where('mobile', $mob)->where('deleted', 0)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Check unique_email number
     */

    public function check_unique_email($email) {
        //echo 
        $result = $this->db->select('*')->from('gz_applicants_details')->where('email', $email)->where('deleted', 0)->get();
        //echo $this->db->last_query();exit();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Applicant change profile
     */

    public function update_user_profile($data) {
        $update_data = array(
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'father_name' => $data['f_name']
        );

        $this->db->where('id', $data['user_id']);
        $this->db->update('gz_applicants_details', $update_data);
        if ($this->db->affected_rows() >= 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * count of total submitted gazette
     */

    public function get_count_of_total_submitted_gazette($user_id) {
        $cop = $this->db->select('id')
                ->from('gz_change_of_partnership_master')
                ->where('user_id', $user_id)
                ->where('deleted', 0)
                ->where('status', 1)
                ->count_all_results();

        $cos = $this->db->select('id')
                ->from('gz_change_of_name_surname_master')
                ->where('user_id', $user_id)
                ->where('deleted', 0)
                ->where('status', 1)
                ->count_all_results();
        return $cop + $cos;
    }

    public function total_gazettes_c_t_igr() {
        $this->db->select('id');
        $this->db->from('gz_change_of_partnership_master');
        if ($this->session->userdata('is_c&t')) {
            if ($this->session->userdata('is_verifier_approver') == 'Approver') {
                $this->db->where('cur_status >=', 2);
            }
        } else if ($this->session->userdata('is_igr')) {
            if ($this->session->userdata('is_verifier_approver') == 'Verifier') {
                $this->db->where('cur_status >=', 3);
            } else if ($this->session->userdata('is_verifier_approver') == 'Approver') {
                $this->db->where('cur_status >=', 4);
            }
        }
        $this->db->where('deleted', 0);
        $this->db->where('status', 1);
        $cop = $this->db->count_all_results();


        return $cop;
    }

    /*
     * Count of extraordinary pending gazettes
     */

    public function cop_unpublished_gazettes($user_id) {
        return $this->db->select('id')
                        ->from('gz_change_of_partnership_master')
                        ->where('user_id', $user_id)
                        ->where('press_publish', 0)
                        ->where('deleted', 0)
                        ->where('status', 1)
                        ->count_all_results();
    }

    public function total_cop_unpublished_gazettes() {
        return $this->db->select('id')
                        ->from('gz_change_of_partnership_master')
                        ->where('press_publish', 0)
                        ->where('deleted', 0)
                        ->where('status', 1)
                        ->count_all_results();
    }

    public function cop_published_gazettes($user_id) {
        return $this->db->select('id')
                        ->from('gz_change_of_partnership_master')
                        ->where('user_id', $user_id)
                        ->where('press_publish', 1)
                        ->where('deleted', 0)
                        ->where('status', 1)
                        ->count_all_results();
    }

    public function total_cop_published_gazettes() {
        return $this->db->select('id')
                        ->from('gz_change_of_partnership_master')
                        ->where('press_publish', 1)
                        ->where('deleted', 0)
                        ->where('status', 1)
                        ->count_all_results();
    }

    public function cos_unpublished_gazettes($user_id) {

        return $this->db->select('id')
                        ->from('gz_change_of_name_surname_master')
                        ->where('user_id', $user_id)
                        ->where('is_published', 0)
                        ->where('deleted', 0)
                        ->where('status', 1)
                        ->count_all_results();
    }

    




    public function total_cop_unpublished_gazettes_dept() {
        $this->db->select('id');
        $this->db->from('gz_change_of_partnership_master');
        if ($this->session->userdata('is_c&t')) {
            if ($this->session->userdata('is_verifier_approver') == 'Approver') {
                $this->db->where('cur_status >=', 2);
            }
        } else if ($this->session->userdata('is_igr')) {
            if ($this->session->userdata('is_verifier_approver') == 'Verifier') {
                $this->db->where('cur_status >=', 3);
            } else if ($this->session->userdata('is_verifier_approver') == 'Approver') {
                $this->db->where('cur_status >=', 4);
            }
        }
        $this->db->where('press_publish', 0);
        $this->db->where('deleted', 0);
        $this->db->where('status', 1);
        $cop = $this->db->count_all_results();

        return $cop;
    }

    public function total_cos_unpublished_gazettes() {
        return $this->db->select('id')
                        ->from('gz_change_of_name_surname_master')
                        ->where('is_published', 0)
                        ->where('deleted', 0)
                        ->where('status', 1)
                        ->count_all_results();
    }

    public function cos_published_gazettes($user_id) {
        return $this->db->select('id')
                        ->from('gz_change_of_name_surname_master')
                        ->where('user_id', $user_id)
                        ->where('is_published', 1)
                        ->where('deleted', 0)
                        ->where('status', 1)
                        ->count_all_results();
    }

    public function total_cos_published_gazettes() {
        return $this->db->select('id')
                        ->from('gz_change_of_name_surname_master')
                        ->where('is_published', 1)
                        ->where('deleted', 0)
                        ->where('status', 1)
                        ->count_all_results();
    }

    /*
     * Count of weekly pending gazettes
     */

    public function get_count_of_weekly_unpublished_gazette($user_id) {
        return $this->db->select('id')
                        ->from('gz_change_of_partnership_master')
                        ->where('user_id', $user_id)
                        ->where('gazette_type_id', 2)
                        ->where('deleted', 0)
                        ->where('status', 1)
                        ->count_all_results();
    }

    /*
     * Recent extraordinary gazettes
     */

    public function get_recent_extraordinary_gazettes($user_id) {
        return $this->db->select('m.*, s.status_det')
                        ->from('gz_change_of_partnership_master m')
                        ->join('gz_par_sur_status_master s', 's.id = m.cur_status')
                        ->where('m.user_id', $user_id)
                        ->where('m.deleted', 0)
                        ->where('m.status', 1)
                        ->order_by('id', 'DESC')
                        ->limit(5)
                        ->get()->result();
    }

    /*
     * Recent extraordinary gazettes for igr user
     */

    public function get_recent_extraordinary_gazettes_igr_user($type) {
        if ($type == 'Verifier') {

            return $this->db->select('m.*, s.status_det')
                            ->from('gz_change_of_partnership_master m')
                            ->join('gz_par_sur_status_master s', 's.id = m.cur_status')
                            ->where('m.cur_status', 3)
                            ->where('m.deleted', 0)
                            ->where('m.status', 1)
                            ->order_by('id', 'DESC')
                            ->limit(5)
                            ->get()->result();
        } else if ($type == 'Approver') {

            return $this->db->select('m.*, s.status_det')
                            ->from('gz_change_of_partnership_master m')
                            ->join('gz_par_sur_status_master s', 's.id = m.cur_status')
                            ->where_in('m.cur_status', array(4, 11))
                            ->where('m.deleted', 0)
                            ->where('m.status', 1)
                            ->order_by('id', 'DESC')
                            ->limit(5)
                            ->get()->result();
        }
    }

    /*
     * Recent extraordinary gazettes for C&T user
     */

    public function get_recent_extraordinary_gazettes_c_and_t_user($type, $module_id) {
        if ($type == 'Verifier') {
            if ($module_id == 1) {

                return $this->db->select('m.*, s.status_det')
                                ->from('gz_change_of_partnership_master m')
                                ->join('gz_par_sur_status_master s', 's.id = m.cur_status')
                                ->where('m.cur_status', 1)
                                ->where('m.deleted', 0)
                                ->where('m.status', 1)
                                ->order_by('id', 'DESC')
                                ->limit(5)
                                ->get()->result();
            } else if ($module_id == 2) {

                return $this->db->select('m.*, s.status_name as status_det')
                                ->from('gz_change_of_name_surname_master m')
                                ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                                ->where('m.current_status', 1)
                                ->where('m.deleted', 0)
                                ->where('m.status', 1)
                                ->order_by('id', 'DESC')
                                ->limit(5)
                                ->get()->result();
            }
        } else if ($type == 'Approver') {
            if ($module_id == 1) {

                return $this->db->select('m.*, s.status_det')
                                ->from('gz_change_of_partnership_master m')
                                ->join('gz_par_sur_status_master s', 's.id = m.cur_status')
                                ->where_in('m.cur_status', array(2, 7))
                                ->where('m.deleted', 0)
                                ->where('m.status', 1)
                                ->order_by('id', 'DESC')
                                ->limit(5)
                                ->get()->result();
            } else if ($module_id == 2) {

                return $this->db->select('m.*, s.status_name as status_det')
                                ->from('gz_change_of_name_surname_master m')
                                ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                                ->where_in('m.current_status', array(2, 7))
                                ->where('m.deleted', 0)
                                ->where('m.status', 1)
                                ->order_by('id', 'DESC')
                                ->limit(5)
                                ->get()->result();
            }
        }
    }

    /*
     * Recent weekly gazettes
     */

    public function get_recent_weekly_gazettes($user_id) {
        return $this->db->select('m.*, h.par_status')
                        ->from('gz_change_of_partnership_master m')
                        ->join('gz_change_of_par_status_his h', 'h.gz_mas_type_id = m.id')
                        ->where('m.user_id', $user_id)
                        ->where('m.gazette_type_id', 2)
                        ->where('m.deleted', 0)
                        ->where('m.status', 1)
                        ->order_by('id', 'DESC')
                        ->limit(5)
                        ->get()->result();
    }





    /*
     * Get total documents for change of name/surname
     */

    public function get_total_total_documents() {
        return $this->db->select('id, document_name')
                        ->from('gz_change_of_name_surname_document_master')
                        ->where('deleted', 0)
                        ->order_by('id', 'ASC')
                        ->get()->result();
    }

    /*
     * Insert Change of name/surname
     */

    public function insert_change_of_name_surname($insert_array) {
        //  echo 'insert';
        // exit;
        try {

            $str = explode('_', $insert_array['block_ulb_id']);

            $this->db->trans_begin();

            $master_data = array(
                'gazette_type_id' => $insert_array['gazette_type'],
                'state_id' => $insert_array['state_id'],
                'district_id' => $insert_array['district_id'],
                'type' => $str[0],
                'block_ulb_id' => $str[1],
                'address_1' => $insert_array['address_1'],
                'father_name' => $insert_array['father_name'],
                'date_of_birth' => $insert_array['date_of_birth'],
                'pin_code' => $insert_array['pin_code'],
                'user_id' => $this->session->userdata('user_id'),
                'file_no' => $insert_array['file_no'],
                'govt_employee' => $insert_array['govt_emp'],
                'is_minor' => $insert_array['minor'],
                'notice_softcopy_doc' => $insert_array['press_word_db_path'],
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'current_status' => 15, // 1
                'status' => 1,
                'deleted' => 0
            );

            $this->db->insert('gz_change_of_name_surname_master', $master_data);

            $master_id = $this->db->insert_id();

            $master_data = array(
                'chnage_surname_id' => $master_id,
                'approver' => $insert_array['approver'],
                'place' => $insert_array['place'],
                'date' => $insert_array['notice_date'],
                'address' => $insert_array['address'],
                'salutation' => $insert_array['salutation'],
                'old_name' => $insert_array['name_for_notice'],
                // 'age' => $insert_array['age'],
                'son_daughter' => $insert_array['son_daughter'],
                'gender' => $insert_array['gender'],
                'old_name_1' => $insert_array['old_name'],
                'new_name' => $insert_array['new_name'],
                'new_name_1' => $insert_array['new_name_one'],
                'sign_name' => $insert_array['new_name_two'],
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            //echo '<pre>';print_r($master_data);exit();
            $this->db->insert('gz_con_surname_applicant_notice_details', $master_data);


            $document_data = array(
                'gz_master_id' => $master_id,
                'affidavit' => $insert_array['affidavit'],
                'original_newspaper' => $insert_array['original_newspaper'],
                'notice_in_softcopy' => $insert_array['notice'],
                'kyc_document' => $insert_array['kyc_doc'],
                'approval_authority' => $insert_array['approval_auth_doc'],
                'deed_changing_form' => $insert_array['deed_changing_form'],
                'birth_certificate' => $insert_array['birth_cert'],
                'notice_softcopy_pdf' => $insert_array['pdf_path'],
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            // print_r($document_data);exit;
            $this->db->insert('gz_change_of_name_surname_doument_det', $document_data);

            $data1 = array(
                'gz_master_id' => $master_id,
                'gz_docu_id' => 1,
                'document_name' => $insert_array['affidavit'],
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 15,
                'deleted' => 0
            );
            $this->db->insert('gz_change_of_name_surname_history', $data1);

            $data2 = array(
                'gz_master_id' => $master_id,
                'gz_docu_id' => 2,
                'document_name' => $insert_array['original_newspaper'],
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_change_of_name_surname_history', $data2);

            $data3 = array(
                'gz_master_id' => $master_id,
                'gz_docu_id' => 3,
                'document_name' => $insert_array['notice'],
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_change_of_name_surname_history', $data3);

            $data4 = array(
                'gz_master_id' => $master_id,
                'gz_docu_id' => 4,
                'document_name' => $insert_array['kyc_doc'],
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_change_of_name_surname_history', $data4);

            if (!empty($insert_array['approval_auth_doc'])) {
                $data5 = array(
                    'gz_master_id' => $master_id,
                    'gz_docu_id' => 5,
                    'document_name' => $insert_array['approval_auth_doc'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data5);
            }

            if (!empty($insert_array['deed_changing_form'])) {
                $data6 = array(
                    'gz_master_id' => $master_id,
                    'gz_docu_id' => 6,
                    'document_name' => $insert_array['deed_changing_form'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data6);
            }

            if (!empty($insert_array['birth_cert'])) {
                $data7 = array(
                    'gz_master_id' => $master_id,
                    'gz_docu_id' => 7,
                    'document_name' => $insert_array['birth_cert'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data7);
            }

            $data8 = array(
                'gz_master_id' => $master_id,
                'gz_docu_id' => 8,
                'document_name' => $insert_array['pdf_path'],
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_change_of_name_surname_history', $data8);

            $status = array(
                'gz_master_id' => $master_id,
                'user_id' => $this->session->userdata('user_id'),
                'change_of_name_surname_status' => 15, //1
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_change_of_name_surname_status_his', $status);

            $processers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Processor')    
                                ->where('module_id', 2)
                                ->get();
            foreach($processers->result() as $processer){
                $processerID = $processer->id;
            }

            $notification_data = array(
                'master_id' => $master_id,
                'module_id' => $this->session->userdata('module_id'),
                'user_id' => $this->session->userdata('user_id'),
                'responsible_user_id' => $processerID,
                'text' => "Change of name/surname request arrived",
                'is_viewed' => 0,
                'pro_ver_app' => 1,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_ct', $notification_data);

            if ($this->db->trans_status() == FALSE) {
                $this->db->trans_rollback();
                return FALSE;
            } else {
                $this->db->trans_commit();
                return TRUE;
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    /*
     * Edit change of name/surname
     */

    public function edit_change_of_name_surname($data,$status) {
        
         if($status == 22){
            try {

                $str = explode('_', $data['block_ulb_id']);

                $this->db->trans_begin();

                $update_master = array(
                    'state_id' => $data['state_id'],
                    'district_id' => $data['district_id'],
                    'type' => $str[0],
                    'block_ulb_id' => $str[1],
                    'address_1' => $data['address_1'],
                    'pin_code' => $data['pin_code'],
                    'current_status' => 18,
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'modified_by' => $this->session->userdata('user_id')
                );
                $this->db->where('id', $data['id']);
                $this->db->update('gz_change_of_name_surname_master', $update_master);


                $master_data = array(
                    'place' => $data['place'],
                    'date' => $data['notice_date'],
                    'approver' => $data['approver'],
                    'son_daughter' => $data['son_daughter'],
                    'gender' => $data['gender'],
                    'salutation' => $data['salutation'],
                    'old_name' => $data['name_for_notice'],
                    'age' => $data['age'],
                    'old_name_1' => $data['old_name'],
                    'new_name' => $data['new_name'],
                    'new_name_1' => $data['new_name_one'],
                    'sign_name' => $data['new_name_two'],
                    'modified_by' => $this->session->userdata('user_id'),
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );

                //echo '<pre>';print_r($master_data);exit();
                $this->db->where('chnage_surname_id', $data['id']);
                $this->db->update('gz_con_surname_applicant_notice_details', $master_data);

                $update_document_data = array(
                    'affidavit' => $data['affidavit'],
                    'original_newspaper' => $data['original_newspaper'],
                    'notice_in_softcopy' => $data['notice'],
                    'kyc_document' => $data['kyc_doc'],
                    'approval_authority' => $data['approval_auth_doc'],
                    'deed_changing_form' => $data['deed_changing_form'],
                    'birth_certificate' => $data['birth_cert'],
                    'notice_softcopy_pdf' => $data['pdf_path'],
                    'modified_at' => $this->session->userdata('user_id'),
                    'modified_by' => date("Y-m-d H:i:s", time()),
                );
                // echo '<pre>';print_r($update_document_data);exit();
                $this->db->where('gz_master_id', $data['id']);
                $this->db->update('gz_change_of_name_surname_doument_det', $update_document_data);

                $data1 = array(
                    'gz_master_id' => $data['id'],
                    'gz_docu_id' => 1,
                    'document_name' => $data['affidavit'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data1);

                $data2 = array(
                    'gz_master_id' => $data['id'],
                    'gz_docu_id' => 2,
                    'document_name' => $data['original_newspaper'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data2);

                $data3 = array(
                    'gz_master_id' => $data['id'],
                    'gz_docu_id' => 3,
                    'document_name' => $data['notice'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data3);

                $data4 = array(
                    'gz_master_id' => $data['id'],
                    'gz_docu_id' => 4,
                    'document_name' => $data['kyc_doc'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data4);

                if (!empty($data['approval_auth_doc'])) {
                    $data5 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 5,
                        'document_name' => $data['approval_auth_doc'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_name_surname_history', $data5);
                }

                if (!empty($data['deed_changing_form'])) {
                    $data6 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 6,
                        'document_name' => $data['deed_changing_form'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_name_surname_history', $data6);
                }

                if (!empty($data['birth_cert'])) {
                    $data7 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 7,
                        'document_name' => $data['birth_cert'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_name_surname_history', $data7);
                }

                $data8 = array(
                    'gz_master_id' => $data['id'],
                    'gz_docu_id' => 8,
                    'document_name' => $data['pdf_path'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data8);

                $status = array(
                    'gz_master_id' => $data['id'],
                    'user_id' => $this->session->userdata('user_id'),
                    'change_of_name_surname_status' => 18,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_status_his', $status);

                $processers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Processor')    
                                ->where('module_id', 2)
                                ->get();
                foreach($processers->result() as $processer){
                    $processerID = $processer->id;
                }

                $notification_data = array(
                    'master_id' => $data['id'],
                    'module_id' => 2,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $processerID,
                    'text' => "Change of name/surname resubmitted successfully",
                    'is_viewed' => 0,
                    'pro_ver_app' => 1,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_ct', $notification_data);

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
        else if ($status == 23){
            try {

                $str = explode('_', $data['block_ulb_id']);

                $this->db->trans_begin();

                $update_master = array(
                    'state_id' => $data['state_id'],
                    'district_id' => $data['district_id'],
                    'type' => $str[0],
                    'block_ulb_id' => $str[1],
                    'address_1' => $data['address_1'],
                    'pin_code' => $data['pin_code'],
                    'current_status' => 19,
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'modified_by' => $this->session->userdata('user_id')
                );
                $this->db->where('id', $data['id']);
                $this->db->update('gz_change_of_name_surname_master', $update_master);


                $master_data = array(
                    'place' => $data['place'],
                    'date' => $data['notice_date'],
                    'approver' => $data['approver'],
                    'son_daughter' => $data['son_daughter'],
                    'gender' => $data['gender'],
                    'salutation' => $data['salutation'],
                    'old_name' => $data['name_for_notice'],
                    'address' => $data['address'],
                    'age' => $data['age'],
                    'old_name_1' => $data['old_name'],
                    'new_name' => $data['new_name'],
                    'new_name_1' => $data['new_name_one'],
                    'sign_name' => $data['new_name_two'],
                    'modified_by' => $this->session->userdata('user_id'),
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );

                //echo '<pre>';print_r($master_data);exit();
                $this->db->where('chnage_surname_id', $data['id']);
                $this->db->update('gz_con_surname_applicant_notice_details', $master_data);

                $update_document_data = array(
                    'affidavit' => $data['affidavit'],
                    'original_newspaper' => $data['original_newspaper'],
                    'notice_in_softcopy' => $data['notice'],
                    'kyc_document' => $data['kyc_doc'],
                    'approval_authority' => $data['approval_auth_doc'],
                    'deed_changing_form' => $data['deed_changing_form'],
                    'birth_certificate' => $data['birth_cert'],
                    'notice_softcopy_pdf' => $data['pdf_path'],
                    'modified_at' => $this->session->userdata('user_id'),
                    'modified_by' => date("Y-m-d H:i:s", time()),
                );
                $this->db->where('gz_master_id', $data['id']);
                $this->db->update('gz_change_of_name_surname_doument_det', $update_document_data);

                $data1 = array(
                    'gz_master_id' => $data['id'],
                    'gz_docu_id' => 1,
                    'document_name' => $data['affidavit'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data1);

                $data2 = array(
                    'gz_master_id' => $data['id'],
                    'gz_docu_id' => 2,
                    'document_name' => $data['original_newspaper'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data2);

                $data3 = array(
                    'gz_master_id' => $data['id'],
                    'gz_docu_id' => 3,
                    'document_name' => $data['notice'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data3);

                $data4 = array(
                    'gz_master_id' => $data['id'],
                    'gz_docu_id' => 4,
                    'document_name' => $data['kyc_doc'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data4);

                if (!empty($data['approval_auth_doc'])) {
                    $data5 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 5,
                        'document_name' => $data['approval_auth_doc'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_name_surname_history', $data5);
                }

                if (!empty($data['deed_changing_form'])) {
                    $data6 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 6,
                        'document_name' => $data['deed_changing_form'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_name_surname_history', $data6);
                }

                if (!empty($data['birth_cert'])) {
                    $data7 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 7,
                        'document_name' => $data['birth_cert'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_name_surname_history', $data7);
                }

                $data8 = array(
                    'gz_master_id' => $data['id'],
                    'gz_docu_id' => 8,
                    'document_name' => $data['pdf_path'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data8);

                $status = array(
                    'gz_master_id' => $data['id'],
                    'user_id' => $this->session->userdata('user_id'),
                    'change_of_name_surname_status' => 19,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_status_his', $status);

                $verifiers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Verifier')    
                                ->where('module_id', 2)
                                ->get();
                foreach($verifiers->result() as $verifier){
                    $verifierID = $verifier->id;
                }

                $notification_data = array(
                    'master_id' => $data['id'],
                    'module_id' => 2,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $verifierID,
                    'text' => "Change of name/surname resubmitted successfully",
                    'is_viewed' => 0,
                    'pro_ver_app' => 1,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_ct', $notification_data);

                // $approvers = $this->db->from('gz_igr_users')
                //                 ->where('verify_approve', 'Approver')   
                //                 ->get()->row();

                // $array_data_no = array(
                //     'master_id' => $par_id,
                //     'module_id' => 1,
                //     'responsible_user_id' => $approvers->id,
                //     'user_id' => $this->session->userdata('user_id'),
                //     'text' => 'Application forwarded IGR Verifier to IGR Approver',
                //     'pro_ver_app' => '2',
                //     'created_by' => $this->session->userdata('user_id'),
                //     'created_at' => date('Y-m-d H:i:s', time()),
                //     'status' => 1,
                //     'deleted' => 0
                // );

                // $this->db->insert('gz_notification_igr', $array_data_no);

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
        else if ($status == 24){
            try {

                $str = explode('_', $data['block_ulb_id']);

                $this->db->trans_begin();

                $update_master = array(
                    'state_id' => $data['state_id'],
                    'district_id' => $data['district_id'],
                    'type' => $str[0],
                    'block_ulb_id' => $str[1],
                    'address_1' => $data['address_1'],
                    'pin_code' => $data['pin_code'],
                    'current_status' => 20,
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'modified_by' => $this->session->userdata('user_id')
                );
                $this->db->where('id', $data['id']);
                $this->db->update('gz_change_of_name_surname_master', $update_master);


                $master_data = array(
                    'place' => $data['place'],
                    'date' => $data['notice_date'],
                    'approver' => $data['approver'],
                    'son_daughter' => $data['son_daughter'],
                    'gender' => $data['gender'],
                    'salutation' => $data['salutation'],
                    'old_name' => $data['name_for_notice'],
                    'age' => $data['age'],
                    'old_name_1' => $data['old_name'],
                    'new_name' => $data['new_name'],
                    'new_name_1' => $data['new_name_one'],
                    'sign_name' => $data['new_name_two'],
                    'modified_by' => $this->session->userdata('user_id'),
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );

                //echo '<pre>';print_r($master_data);exit();
                $this->db->where('chnage_surname_id', $data['id']);
                $this->db->update('gz_con_surname_applicant_notice_details', $master_data);

                $update_document_data = array(
                    'affidavit' => $data['affidavit'],
                    'original_newspaper' => $data['original_newspaper'],
                    'notice_in_softcopy' => $data['notice'],
                    'kyc_document' => $data['kyc_doc'],
                    'approval_authority' => $data['approval_auth_doc'],
                    'deed_changing_form' => $data['deed_changing_form'],
                    'birth_certificate' => $data['birth_cert'],
                    'notice_softcopy_pdf' => $data['pdf_path'],
                    'modified_at' => $this->session->userdata('user_id'),
                    'modified_by' => date("Y-m-d H:i:s", time()),
                );
                $this->db->where('gz_master_id', $data['id']);
                $this->db->update('gz_change_of_name_surname_doument_det', $update_document_data);

                $data1 = array(
                    'gz_master_id' => $data['id'],
                    'gz_docu_id' => 1,
                    'document_name' => $data['affidavit'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data1);

                $data2 = array(
                    'gz_master_id' => $data['id'],
                    'gz_docu_id' => 2,
                    'document_name' => $data['original_newspaper'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data2);

                $data3 = array(
                    'gz_master_id' => $data['id'],
                    'gz_docu_id' => 3,
                    'document_name' => $data['notice'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data3);

                $data4 = array(
                    'gz_master_id' => $data['id'],
                    'gz_docu_id' => 4,
                    'document_name' => $data['kyc_doc'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data4);

                if (!empty($data['approval_auth_doc'])) {
                    $data5 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 5,
                        'document_name' => $data['approval_auth_doc'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_name_surname_history', $data5);
                }

                if (!empty($data['deed_changing_form'])) {
                    $data6 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 6,
                        'document_name' => $data['deed_changing_form'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_name_surname_history', $data6);
                }

                if (!empty($data['birth_cert'])) {
                    $data7 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 7,
                        'document_name' => $data['birth_cert'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_name_surname_history', $data7);
                }

                $data8 = array(
                    'gz_master_id' => $data['id'],
                    'gz_docu_id' => 8,
                    'document_name' => $data['pdf_path'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_history', $data8);

                $status = array(
                    'gz_master_id' => $data['id'],
                    'user_id' => $this->session->userdata('user_id'),
                    'change_of_name_surname_status' => 18,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_name_surname_status_his', $status);

                $approvers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Approver')    
                                ->where('module_id', 2)
                                ->get();
                foreach($approvers->result() as $approver){
                    $approverID = $approver->id;
                }

                $notification_data = array(
                    'master_id' => $data['id'],
                    'module_id' => 2,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $approverID,
                    'text' => "Change of name/surname resubmitted successfully",
                    'is_viewed' => 0,
                    'pro_ver_app' => 1,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_ct', $notification_data);

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

    public function current($id){
        return $this->db->select('current_status')
                        ->from('gz_change_of_name_surname_master')
                        ->where('id',$id)
                        ->get()->row();          
    }

    /*
     * Count of total change of name/surname submitted(for applicant)
     */

    public function get_total_change_name_count_applicant() {
        return $this->db->select('id')
                        ->from('gz_change_of_name_surname_master')
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->where('user_id', $this->session->userdata('user_id'))
                        ->count_all_results();
    }

    /*
     * Get data for change of name/surname module(for applicant)
     */

    public function get_total_change_of_names_applicant() {
        // die("TEst");
        return $this->db->select('u.name AS applicant_name, m.file_no, s.status_name, m.id, m.created_at')
                        ->from('gz_change_of_name_surname_master m')
                        ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                        ->join('gz_applicants_details u', 'm.user_id = u.id')
                        ->where('m.status', 1)
                        ->where('m.deleted', 0)
                        ->where('m.user_id', $this->session->userdata('user_id'))
                        ->order_by('m.id', 'DESC')
                        ->get()->result();
    }

    /*
     * Count extraordinary gazettes for C&T user 
     */

    public function get_total_change_name_count_c_and_t($type, $module_id) {
        // echo $type;die("Worked");
        if ($type == 'Verifier') {
            if ($module_id == 1) {

                return $this->db->select('m.*, s.status_det , u.name')
                                ->from('gz_change_of_partnership_master m')
                                ->join('gz_par_sur_status_master s', 's.id = m.cur_status')
                                ->join('gz_applicants_details u', 'm.user_id = u.id')
                                ->where('m.cur_status', 1)
                                ->where('m.deleted', 0)
                                ->where('m.status', 1)
                                ->count_all_results();
            } else if ($module_id == 2) {

                return $this->db->select('m.*, s.status_name as status_det , u.name')
                                ->from('gz_change_of_name_surname_master m')
                                ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                                ->join('gz_applicants_details u', 'u.id = m.user_id')
                                ->where('m.current_status >=', 2)
                                ->where('m.deleted', 0)
                                ->where('m.status', 1)
                                ->count_all_results();
            }
        } else if ($type == 'Approver') {
            if ($module_id == 1) {

                return $this->db->select('m.*, s.status_det')
                                ->from('gz_change_of_partnership_master m')
                                ->join('gz_par_sur_status_master s', 's.id = m.cur_status')
                                ->where('m.cur_status >=', 2)
                                ->where('m.deleted', 0)
                                ->where('m.status', 1)
                                ->count_all_results();
            } else if ($module_id == 2) {

                return $this->db->select('m.*, s.status_name as status_det')
                                ->from('gz_change_of_name_surname_master m')
                                ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                                ->where('m.current_status >=', 2)
                                ->where('m.deleted', 0)
                                ->where('m.status', 1)
                                ->count_all_results();
            }
        } else if ($type == 'Processor') {

            return $this->db->select('m.*, s.status_name as status_det')
                            ->from('gz_change_of_name_surname_master m')
                            ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                           //->where_in('m.current_status', array(1, 6, 12, 13, 8))
                            ->where('m.deleted', 0)
                            ->where('m.status', 1)
                            ->count_all_results();
        }
    }

    public function get_total_change_name_count_c_and_t_published($type, $module_id){
        if ($type == 'Verifier') {
            if ($module_id == 1) {

                return $this->db->select('m.*, s.status_det , u.name')
                                ->from('gz_change_of_partnership_master m')
                                ->join('gz_par_sur_status_master s', 's.id = m.cur_status')
                                ->join('gz_applicants_details u', 'm.user_id = u.id')
                                ->where('m.cur_status', 17)
                                ->where('m.deleted', 0)
                                ->where('m.status', 1)
                                ->count_all_results();
            } else if ($module_id == 2) {

                return $this->db->select('m.*, s.status_name as status_det , u.name')
                                ->from('gz_change_of_name_surname_master m')
                                ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                                ->join('gz_applicants_details u', 'u.id = m.user_id')
                                ->where('m.current_status >=', 2)
                                ->where('m.deleted', 0)
                                ->where('m.status',11)
                                ->count_all_results();
            }
        } else if ($type == 'Approver') {
            if ($module_id == 1) {

                return $this->db->select('m.*, s.status_det')
                                ->from('gz_change_of_partnership_master m')
                                ->join('gz_par_sur_status_master s', 's.id = m.cur_status')
                                ->where('m.cur_status =', 17)
                                ->where('m.deleted', 0)
                                ->where('m.status', 1)
                                ->count_all_results();
            } else if ($module_id == 2) {

                return $this->db->select('m.*, s.status_name as status_det')
                                ->from('gz_change_of_name_surname_master m')
                                ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                                ->where('m.current_status >=', 2)
                                ->where('m.deleted', 0)
                                ->where('m.status', 11)
                                ->count_all_results();
            }
        } else if ($type == 'Processor') {

            return $this->db->select('m.*, s.status_name as status_det')
                            ->from('gz_change_of_name_surname_master m')
                            ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                           //->where_in('m.current_status', array(1, 6, 12, 13, 8))
                            ->where('m.deleted', 0)
                            ->where('m.status', 11)
                            ->count_all_results();
        }
    }
    
    /*
     * Total Published Name/Surname List
     * @Soudhanki
     * @Date 23/12/2021
     * @param int limit, int offset
     * @return Result Array
     */
    
    public function published_name_surname_list($limit, $offset) {
        return $this->db->select('m.*, s.status_name, app.name AS applicant_name')
                        ->from('gz_change_of_name_surname_master m')
                        ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                        ->join('gz_applicants_details app', 'm.user_id = app.id')
                        ->where('m.current_status =', 11)
                        ->where('m.deleted', 0)
                        ->where('m.status', 1)
                        ->order_by('id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }
        
        
        public function published_partnership_list($limit, $offset) {
            return $this->db->select('m.*, s.status_det, app.name AS applicant_name')
                                ->from('gz_change_of_partnership_master m')
                                ->join('gz_par_sur_status_master s', 's.id = m.cur_status')
                                ->join('gz_applicants_details app', 'm.user_id = app.id')
                                ->where('m.cur_status =', 17)
                                ->where('m.press_publish', 1)
                                ->where('m.deleted', 0)
                                ->where('m.status', 1)
                                ->order_by('id', 'DESC')
                                ->limit($limit, $offset)
                                ->get()->result();
        }

    /*
     * Recent extraordinary gazettes for C&T user
     */

    public function get_total_change_of_names_c_and_t($limit, $offset, $type, $module_id) {
        if ($type == 'Verifier') {
                return $this->db->select('m.*, s.status_name, app.name AS applicant_name')
                                ->from('gz_change_of_name_surname_master m')
                                ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                                ->join('gz_applicants_details app', 'm.user_id = app.id')
                                ->where('m.current_status >=', 2)
                                ->where('m.deleted', 0)
                                ->where('m.status', 1)
                                ->order_by('id', 'DESC')
                                ->limit($limit, $offset)
                                ->get()->result();  
        } else if ($type == 'Approver') {

            //if ($module_id == 2) {

                return $this->db->select('m.*, s.status_name, app.name AS applicant_name')
                                ->from('gz_change_of_name_surname_master m')
                                ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                                ->join('gz_applicants_details app', 'm.user_id = app.id')
                                ->where('m.current_status >=', 2)
                                ->where('m.deleted', 0)
                                ->where('m.status', 1)
                                ->order_by('id', 'DESC')
                                ->limit($limit, $offset)
                                ->get()->result();
            //}
        } else if ($type == 'Processor') {

            return $this->db->select('m.*, s.status_name, app.name AS applicant_name')
                            ->from('gz_change_of_name_surname_master m')
                            ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                            ->join('gz_applicants_details app', 'm.user_id = app.id')
                            //->where_in('m.current_status', array(1, 6, 12, 13, 8))
                            ->where('m.deleted', 0)
                            ->where('m.status', 1)
                            // ->where_in('s.status_name', ['Submmitted Successfully']) // added for testing...
                            ->order_by('id', 'DESC')
                            ->limit($limit, $offset)
                            ->get()->result();
        }
    }
    /**
     * Get Search Total Rows
     */

    public function get_total_change_name_count_c_and_t_search($data = array()) {
        $this->db->select('m.*, s.status_name, app.name AS applicant_name');
        $this->db->from('gz_change_of_name_surname_master m');
        $this->db->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status');
        $this->db->join('gz_applicants_details app', 'm.user_id = app.id');
        //$this->db->where('m.current_status >=', 2);
        $this->db->where('m.deleted', 0);
        $this->db->where('m.status', 1);
        //$array_items = array('name', 'file_no', 'status', 'notice_date_form', 'notice_date_to');
        //print_r($data);
        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('m.file_no', $data['file_no']);
        }
        if (!empty($data['status'])) {
            $this->db->like('m.current_status', $data['status']);
        }
        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(m.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
            $this->db->where('DATE(m.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                $this->db->where('DATE(m.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                $this->db->where('DATE(m.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
            }
        }
       
        $this->db->order_by('id', 'DESC');
        return $this->db->count_all_results();     

    }


    /*
    *Get Status Table Data 
    */

    public function get_status() {
        return $this->db->select('*')
                        ->from('gz_change_of_name_surname_status_master')
                        // ->where('id >=',2)
                        ->get()
                        ->result();
    }

    /*
    *Search List of Change of Name/Surname
    */

    public function change_of_name_surname_search_result($limit, $offset, $data = array()) {

        $this->db->select('m.*, s.status_name, app.name AS applicant_name');
        $this->db->from('gz_change_of_name_surname_master m');
        $this->db->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status');
        $this->db->join('gz_applicants_details app', 'm.user_id = app.id');
        
        $this->db->where('m.deleted', 0);
        $this->db->where('m.status', 1);
        
        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('m.file_no', $data['file_no']);
        }
        if (!empty($data['status'])) {
            $this->db->where('m.current_status', $data['status']);
        }
        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(m.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
            $this->db->where('DATE(m.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                $this->db->where('DATE(m.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                $this->db->where('DATE(m.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
            }
        }
        //print_r($this->db->last_query()); exit;
       
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();     

                   
    }

    public function get_total_parter_count_search($data = array()){
        $this->db->select('m.*, s.status_det, app.name');
        $this->db->from('gz_change_of_partnership_master m');
        $this->db->join('gz_par_sur_status_master s', 's.id = m.cur_status');
        $this->db->join('gz_applicants_details app', 'm.user_id = app.id');
        $this->db->where('m.deleted', 0);
        $this->db->where('m.status', 1);
        
        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('m.file_no', $data['file_no']);
        }
        if (!empty($data['status'])) {
            $this->db->where('m.cur_status', $data['status']);
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
       
        $this->db->order_by('id', 'DESC');
        return $this->db->count_all_results();
    }

    public function change_of_partnership_search_result($limit, $offset, $data = array()){
        $this->db->select('m.*, s.status_det, app.name');
        $this->db->from('gz_change_of_partnership_master m');
        $this->db->join('gz_par_sur_status_master s', 's.id = m.cur_status');
        $this->db->join('gz_applicants_details app', 'm.user_id = app.id');
        $this->db->where('m.deleted', 0);
        $this->db->where('m.status', 1);
        
        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('m.file_no', $data['file_no']);
        }
        if (!empty($data['status'])) {
            $this->db->where('m.cur_status', $data['status']);
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

        /*
        * Published Change of name Surname 
        */

    public function get_total_change_name_published_count_c_and_t_search($data = array()){
        $this->db->select('m.*, s.status_name, app.name AS applicant_name');
        $this->db->from('gz_change_of_name_surname_master m');
        $this->db->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status');
        $this->db->join('gz_applicants_details app', 'm.user_id = app.id');
        //$this->db->where('m.current_status >=', 2);
        $this->db->where('m.deleted', 0);
        $this->db->where('m.current_status =', 11);
        $this->db->where('m.status', 1);
        //$array_items = array('name', 'file_no', 'status', 'notice_date_form', 'notice_date_to');
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
       
        $this->db->order_by('id', 'DESC');
        return $this->db->count_all_results();
    }

    public function change_of_name_surname_published_search_result($limit, $offset, $data = array()){
        $this->db->select('m.*, s.status_name, app.name AS applicant_name');
        $this->db->from('gz_change_of_name_surname_master m');
        $this->db->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status');
        $this->db->join('gz_applicants_details app', 'm.user_id = app.id');
        $this->db->where('m.current_status =', 11);
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
    
    public function get_total_published_partner_count_search($data = array()){
        $this->db->select('m.*, s.status_det, app.name');
        $this->db->from('gz_change_of_partnership_master m');
        $this->db->join('gz_par_sur_status_master s', 's.id = m.cur_status');
        $this->db->join('gz_applicants_details app', 'm.user_id = app.id');
        $this->db->where('m.cur_status =', 17);
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

    public function change_of_published_partnership_search_result($limit, $offset, $data = array()){
        //var_dump($data);
        $this->db->select('m.*, s.status_det, app.name');
        $this->db->from('gz_change_of_partnership_master m');
        $this->db->join('gz_par_sur_status_master s', 's.id = m.cur_status');
        $this->db->join('gz_applicants_details app', 'm.user_id = app.id');
        $this->db->where('m.cur_status =', 17);
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

    /*
     * Get data for view details
     */
    public function get_status_partnership() {
        return $this->db->select('*')
                        ->from('gz_par_sur_status_master')
                        // ->where('id >=',2)
                        ->get()
                        ->result();
    }

    public function view_details_change_name_surname($id) {

        $query1 = $this->db->select('p.*,g.gazette_type,d.id as district_id, u.name,p.father_name as applicant_father, u.father_name, s.status_name, u.mobile, u.email, u.address, st.state_name,st.id as state_id, d.district_name, po.block_name as block_name')
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
        $query2 = $this->db->select('p.*, g.gazette_type, u.name,p.father_name as applicant_father, u.father_name, s.status_name, u.mobile, u.email, u.address, st.state_name, d.district_name, po.ulb_name  as block_name')
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

    /*
     * Get documents data on view
     */

    public function get_total_tot_document_change_name_surname() {
        return $this->db->select('*')
                        ->from('gz_change_of_name_surname_document_master')
                        ->where('deleted', '0')
                        ->order_by('id', 'ASC')
                        ->get()->result();
    }

    
    public function declartions() {
        return $this->db->select('*')
                        ->from('declarations')
                        ->where('status', 1)
                        ->order_by('id', 'ASC')
                        ->get()->result();
    }

    

    /*
     * Load image on view details
     */

    public function get_image_link($id) {
        return $this->db->select('*')
                        ->from('gz_change_of_name_surname_doument_det')
                        ->where('gz_master_id', $id)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->get()->row();
    }

    public function get_image_link_cons($id) {
        $this->db->select('affidavit, original_newspaper, notice_softcopy_pdf, kyc_document, approval_authority, deed_changing_form, birth_certificate');
        $this->db->from('gz_change_of_name_surname_doument_det');
        $this->db->where('gz_master_id', $id);
        $this->db->where('status', 1);
        $this->db->where('deleted', 0);
        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    /*
     * Status History
     */

    // public function get_status_history($id) {
    //     return $this->db->select('c.id, c.change_of_name_surname_status, c.created_at, p.status_name, r.remarks')
    //                     ->from('gz_change_of_name_surname_status_his c')
    //                     ->join('gz_change_of_name_surname_status_master p', 'c.change_of_name_surname_status = p.id')
    //                     ->join('gz_change_of_name_surname_remarks_master r', 'r.status_id = c.change_of_name_surname_status', "LEFT")
    //                     ->where('c.gz_master_id', $id)
    //                     ->order_by('c.id', 'ASC')
    //                     //print_r($this->db->last_query()); exit;

    //                     ->get()->result();
    //                     //return $return;
    // }

    public function get_status_history($id) {
        return $this->db->select('c.id, c.change_of_name_surname_status,c.created_at,  p.status_name, r.remarks')
                        ->from('gz_change_of_name_surname_status_his c')
                        ->join('gz_change_of_name_surname_status_master p', 'c.change_of_name_surname_status = p.id')
                        ->join('gz_change_of_name_surname_remarks_master r', 'r.status_history_id = c.id', 'LEFT')
                        ->where('c.gz_master_id', $id)
                        ->order_by('c.id', 'DESC')
                        ->get()->result();
                        
    }

    /*
     * Document History
     */

    public function get_document_history($id) {
        return $this->db->select('p.*,d.document_name as docu')
                        ->from('gz_change_of_name_surname_history p')
                        ->join('gz_change_of_name_surname_document_master d', 'p.gz_docu_id = d.id')
                        ->where('p.gz_master_id', $id)
                        ->where('d.id !=', 3)
                        ->order_by('p.id', 'ASC')
                        ->get()->result();
    }

    /*
     * get pdf link on view details (document history)
     */

    public function get_pdf($master_id, $id) {
        $data = $this->db->select('document_name')
                        ->from('gz_change_of_name_surname_history')
                        ->where('gz_master_id', $master_id)
                        ->where('id', $id)
                        ->where('gz_docu_id', 8)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->get()->row();
        $link = "";
        if (!empty($data)) {
            $link = $data->document_name;
        }
        return $link;
    }



    /*
     * Forward change in name/surname by C & T User (Verifier)
     */

    public function forward_change_name_surname_c_and_t_verifier($update_status) {
        try {
            $this->db->trans_begin();

            $status_history = array(
                'gz_master_id' => $update_status['id'],
                'change_of_name_surname_status' => $update_status['status'],
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => $this->session->userdata('user_id')
            );
            $this->db->insert('gz_change_of_name_surname_status_his', $status_history);
            $last_id = $this->db->insert_id();

            $remark_data = array(
                'change_of_name_surname_id' => $update_status['id'],
                'status_id' => $update_status['status'],
                'remarks' => $update_status['remarks'],
                'status_history_id' => $last_id
            );
            $this->db->insert('gz_change_of_name_surname_remarks_master', $remark_data);

            $master_data = array(
                'current_status' => $update_status['status'],
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $this->session->userdata('user_id')
            );
            $this->db->where('id', $update_status['id']);
            $this->db->update('gz_change_of_name_surname_master', $master_data);

            $approvers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Approver')    
                                ->where('module_id', 2)
                                ->get();
            foreach($approvers->result() as $approver){
                $approverID = $approver->id;
            }

            $applicant_id = $this->db->select('user_id')
                                ->from('gz_change_of_name_surname_master')
                                ->where('id', $update_status['id'])
                                ->get()->row();

            $notification_data_ct = array(
                'master_id' => $update_status['id'],
                'module_id' => 2,
                'user_id' => $this->session->userdata('user_id'),
                'responsible_user_id' => $approverID,
                'text' => "Change of name/surname request forwarded to approver",
                'is_viewed' => 0,
                'pro_ver_app' => 3,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_ct', $notification_data_ct);

            $notification_data_applicant = array(
                'master_id' => $update_status['id'],
                'module_id' => 2,
                'user_id' => $this->session->userdata('user_id'),
                'responsible_user_id' => $applicant_id->user_id,
                'text' => "Change of name/surname request forwarded to C & T approver",
                'is_viewed' => 0,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_applicant', $notification_data_applicant);

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

    /**
     * Return to applicant from c and t verifier user
     */
    public function return_to_applicant_c_and_t_verifier($update_status){
        try {
            $this->db->trans_begin();

            $status_history = array(
                'gz_master_id' => $update_status['id'],
                'change_of_name_surname_status' => $update_status['status'],
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => $this->session->userdata('user_id')
            );
            $this->db->insert('gz_change_of_name_surname_status_his', $status_history);
            $last_id = $this->db->insert_id();

            $remark_data = array(
                'change_of_name_surname_id' => $update_status['id'],
                'status_id' => $update_status['status'],
                'remarks' => $update_status['remarks'],
                'status_history_id' => $last_id
            );
            $this->db->insert('gz_change_of_name_surname_remarks_master', $remark_data);

            $master_data = array(
                'current_status' => $update_status['status'],
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $this->session->userdata('user_id')
            );
            $this->db->where('id', $update_status['id']);
            $this->db->update('gz_change_of_name_surname_master', $master_data);

            // $notification_data_ct = array(
            //     'master_id' => $update_status['id'],
            //     'module_id' => 2,
            //     'user_id' => $this->session->userdata('user_id'),
            //     'text' => "Change of name/surname returned to applicant",
            //     'is_viewed' => 0,
            //     'pro_ver_app' => 3,
            //     'created_by' => $this->session->userdata('user_id'),
            //     'created_at' => date("Y-m-d H:i:s", time()),
            //     'status' => 1,
            //     'deleted' => 0
            // );
            // $this->db->insert('gz_notification_ct', $notification_data_ct);

            $applicant_id = $this->db->select('user_id')
                                ->from('gz_change_of_name_surname_master')
                                ->where('id', $update_status['id'])
                                ->get()->row();

            $notification_data_applicant = array(
                'master_id' => $update_status['id'],
                'module_id' => 2,
                'user_id' => $this->session->userdata('user_id'),
                'responsible_user_id' => $applicant_id->user_id,
                'text' => "Change of name/surname returned from C & T Verifier",
                'is_viewed' => 0,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_applicant', $notification_data_applicant);

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
     * Approve change in name/surname by C & T User (Approver)
     */

    public function approve_change_name_surname_c_and_t_approver($update_status) {
        try {
            $this->db->trans_begin();
            $status_history = array(
                'gz_master_id' => $update_status['id'],
                'change_of_name_surname_status' => $update_status['status'],
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => $this->session->userdata('user_id')
            );
            $this->db->insert('gz_change_of_name_surname_status_his', $status_history);
            $last_id = $this->db->insert_id();

            $remark_data = array(
                'change_of_name_surname_id' => $update_status['id'],
                'status_id' => $update_status['status'],
                'remarks' => $update_status['remarks'],
                'status_history_id' => $last_id
            );
            $this->db->insert('gz_change_of_name_surname_remarks_master', $remark_data);

            $master_data = array(
                'current_status' => $update_status['status'],
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $this->session->userdata('user_id')
            );
            $this->db->where('id', $update_status['id']);
            $this->db->update('gz_change_of_name_surname_master', $master_data);

            $admin_details =  $this->db->select('*')
                                    ->from('gz_users')
                                    ->where('is_admin', 1)
                                    ->where('status', 1)
                                    ->get()->row();
                                    
            $notification_data_ct = array(
                'master_id' => $update_status['id'],
                'module_id' => 2,
                'user_id' => $this->session->userdata('user_id'),
                'responsible_user_id' => $admin_details->id,
                'text' => "Change of name/surname request forwarded to government press from C & T approver",
                'is_viewed' => 0,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_govt', $notification_data_ct);

            $applicant_id = $this->db->select('user_id')
                                ->from('gz_change_of_name_surname_master')
                                ->where('id', $update_status['id'])
                                ->get()->row();

            $notification_data_applicant = array(
                'master_id' => $update_status['id'],
                'module_id' => 2,
                'user_id' => $this->session->userdata('user_id'),
                'responsible_user_id' => $applicant_id->user_id,
                'text' => "Change of name/surname request forwarded to government press from C & T approver",
                'is_viewed' => 0,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_applicant', $notification_data_applicant);

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
    * Return to applicant from C & Approver
    */

    public function return_to_applicant_change_name_surname_c_and_t_approver($update_status){

        try {
            $this->db->trans_begin();

            $status_history = array(
                'gz_master_id' => $update_status['id'],
                'change_of_name_surname_status' => $update_status['status'],
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => $this->session->userdata('user_id')
            );
            $this->db->insert('gz_change_of_name_surname_status_his', $status_history);
            $last_id = $this->db->insert_id();

            $remark_data = array(
                'change_of_name_surname_id' => $update_status['id'],
                'status_id' => $update_status['status'],
                'remarks' => $update_status['remarks'],
                'status_history_id' => $last_id
            );
            $this->db->insert('gz_change_of_name_surname_remarks_master', $remark_data);

            $master_data = array(
                'current_status' => $update_status['status'],
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $this->session->userdata('user_id')
            );
            $this->db->where('id', $update_status['id']);
            $this->db->update('gz_change_of_name_surname_master', $master_data);

            $applicant_id = $this->db->select('user_id')
                                ->from('gz_change_of_name_surname_master')
                                ->where('id', $update_status['id'])
                                ->get()->row();

            $notification_data_applicant = array(
                'master_id' => $update_status['id'],
                'module_id' => 2,
                'user_id' => $this->session->userdata('user_id'),
                'responsible_user_id' => $applicant_id->user_id,
                'text' => "Change of name/surname request returned from C & T approver",
                'is_viewed' => 0,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_applicant', $notification_data_applicant);


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
     * Forward change in name/surname by C & T User (Processor)
     */

    public function forward_change_name_surname_c_and_t_processor($update_status) {
        try {
            $this->db->trans_begin();

            $status_history = array(
                'gz_master_id' => $update_status['id'],
                'change_of_name_surname_status' => $update_status['status'],
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => $this->session->userdata('user_id')
            );
            $this->db->insert('gz_change_of_name_surname_status_his', $status_history);
            $last_id = $this->db->insert_id();

            $remark_data = array(
                'change_of_name_surname_id' => $update_status['id'],
                'status_id' => $update_status['status'],
                'remarks' => $update_status['remarks'],
                'status_history_id' => $last_id
            );
            $this->db->insert('gz_change_of_name_surname_remarks_master', $remark_data);

            $master_data = array(
                'current_status' => $update_status['status'],
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $this->session->userdata('user_id')
            );
            $this->db->where('id', $update_status['id']);
            $this->db->update('gz_change_of_name_surname_master', $master_data);

            $verifiers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Verifier')    
                                ->where('module_id', 2)
                                ->get();
            foreach($verifiers->result() as $verifier){
                $verifierID = $verifier->id;
            }

            $applicant_id = $this->db->select('user_id')
                                ->from('gz_change_of_name_surname_master')
                                ->where('id', $update_status['id'])
                                ->get()->row();

            $notification_data_ct = array(
                'master_id' => $update_status['id'],
                'module_id' => 2,
                'user_id' => $this->session->userdata('user_id'),
                'responsible_user_id' => $verifierID,
                'text' => "Change of name/surname request forwarded to verifier",
                'is_viewed' => 0,
                'pro_ver_app' => 2,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_ct', $notification_data_ct);

            $notification_data_applicant = array(
                'master_id' => $update_status['id'],
                'module_id' => 2,
                'user_id' => $this->session->userdata('user_id'),
                'responsible_user_id' => $applicant_id->user_id,
                'text' => "Change of name/surname request forwarded to C & T verifier",
                'is_viewed' => 0,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_applicant', $notification_data_applicant);


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

    /**
     * Return change of name/surname application to applicant from processor 
     */

    public function return_to_applicant_c_and_t_processor($update_status){
        try {
            $this->db->trans_begin();

            $status_history = array(
                'gz_master_id' => $update_status['id'],
                'change_of_name_surname_status' => $update_status['status'],
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => $this->session->userdata('user_id')
            );
            $this->db->insert('gz_change_of_name_surname_status_his', $status_history);
            $last_id = $this->db->insert_id();

            $remark_data = array(
                'change_of_name_surname_id' => $update_status['id'],
                'status_id' => $update_status['status'],
                'remarks' => $update_status['remarks'],
                'status_history_id' => $last_id
            );
            $this->db->insert('gz_change_of_name_surname_remarks_master', $remark_data);

            $master_data = array(
                'current_status' => $update_status['status'],
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $this->session->userdata('user_id')
            );
            $this->db->where('id', $update_status['id']);
            $this->db->update('gz_change_of_name_surname_master', $master_data);

            $applicant_id = $this->db->select('user_id')
                                ->from('gz_change_of_name_surname_master')
                                ->where('id', $update_status['id'])
                                ->get()->row();

            $notification_data_applicant = array(
                'master_id' => $update_status['id'],
                'module_id' => 2,
                'user_id' => $this->session->userdata('user_id'),
                'responsible_user_id' => $applicant_id->user_id,
                'text' => "Change of name/surname request returned from C & T processor",
                'is_viewed' => 0,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_applicant', $notification_data_applicant);


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
     * Reject change of name/surname request (approver)
     */

    public function reject_change_name_surname_c_and_t_approver($update_status) {
        try {
            $this->db->trans_begin();

            $status_history = array(
                'gz_master_id' => $update_status['id'],
                'change_of_name_surname_status' => $update_status['status'],
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => $this->session->userdata('user_id')
            );
            $this->db->insert('gz_change_of_name_surname_status_his', $status_history);
            $last_id = $this->db->insert_id();

            $remark_data = array(
                'change_of_name_surname_id' => $update_status['id'],
                'status_id' => $update_status['status'],
                'remarks' => $update_status['remarks'],
                'status_history_id' => $last_id
            );
            $this->db->insert('gz_change_of_name_surname_remarks_master', $remark_data);

            $master_data = array(
                'current_status' => $update_status['status'],
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $this->session->userdata('user_id')
            );
            $this->db->where('id', $update_status['id']);
            $this->db->update('gz_change_of_name_surname_master', $master_data);

            $status_history = array(
                'gz_master_id' => $update_status['id'],
                'change_of_name_surname_status' => $update_status['status'],
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => $this->session->userdata('user_id')
            );
            $this->db->insert('gz_change_of_name_surname_status_his', $status_history);

            $applicant_id = $this->db->select('user_id')
                                ->from('gz_change_of_name_surname_master')
                                ->where('id', $update_status['id'])
                                ->get()->row();

            $notification_data_applicant = array(
                'master_id' => $update_status['id'],
                'module_id' => 2,
                'user_id' => $this->session->userdata('user_id'),
                'responsible_user_id' => $applicant_id->user_id,
                'text' => "Change of name/surname request rejected by C & T Approver",
                'is_viewed' => 0,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_applicant', $notification_data_applicant);

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
     * Reject chnage of name/surname request (verifier)
     */

    public function reject_change_name_surname_c_and_t_verifier($update_status) {
        try {
            $this->db->trans_begin();

            $remark_data = array(
                'change_of_name_surname_id' => $update_status['id'],
                'status_id' => $update_status['status'],
                'remarks' => $update_status['remarks']
            );
            $this->db->insert('gz_change_of_name_surname_remarks_master', $remark_data);

            $master_data = array(
                'current_status' => $update_status['status'],
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $this->session->userdata('user_id')
            );
            $this->db->where('id', $update_status['id']);
            $this->db->update('gz_change_of_name_surname_master', $master_data);

            $status_history = array(
                'gz_master_id' => $update_status['id'],
                'change_of_name_surname_status' => $update_status['status'],
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => $this->session->userdata('user_id')
            );
            $this->db->insert('gz_change_of_name_surname_status_his', $status_history);

            $notification_data_ct = array(
                'master_id' => $update_status['id'],
                'module_id' => 2,
                'user_id' => $this->session->userdata('user_id'),
                'text' => "Change of name/surname request forwarded to approver for rejection",
                'is_viewed' => 0,
                'pro_ver_app' => 3,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_ct', $notification_data_ct);

            $notification_data_applicant = array(
                'master_id' => $update_status['id'],
                'module_id' => 2,
                'user_id' => $this->session->userdata('user_id'),
                'text' => "Change of name/surname request forwarded to approver for rejection",
                'is_viewed' => 0,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_applicant', $notification_data_applicant);

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
     * Reject change of name/surname request (Processor)
     */

    public function reject_change_name_surname_c_and_t_processor($update_status) {
        try {
            $this->db->trans_begin();

            $status_history = array(
                'gz_master_id' => $update_status['id'],
                'change_of_name_surname_status' => $update_status['status'],
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => $this->session->userdata('user_id')
            );
            $this->db->insert('gz_change_of_name_surname_status_his', $status_history);
            $last_id = $this->db->insert_id();

            $remark_data = array(
                'change_of_name_surname_id' => $update_status['id'],
                'status_id' => $update_status['status'],
                'remarks' => $update_status['remarks'],
                'status_history_id' => $last_id
            );
            $this->db->insert('gz_change_of_name_surname_remarks_master', $remark_data);

            $master_data = array(
                'current_status' => $update_status['status'],
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $this->session->userdata('user_id')
            );
            $this->db->where('id', $update_status['id']);
            $this->db->update('gz_change_of_name_surname_master', $master_data);

            $notification_data_ct = array(
                'master_id' => $update_status['id'],
                'module_id' => $this->session->userdata('is_c&t_module'),
                'user_id' => $this->session->userdata('user_id'),
                'text' => "Change of name/surname request submitted to approver to reject",
                'is_viewed' => 0,
                'pro_ver_app' => 2,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_ct', $notification_data_ct);

            $notification_data_applicant = array(
                'master_id' => $update_status['id'],
                'module_id' => $this->session->userdata('is_c&t_module'),
                'user_id' => $this->session->userdata('user_id'),
                'text' => "Change of name/surname request submitted to approver to reject",
                'is_viewed' => 0,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_applicant', $notification_data_applicant);

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
     * Forward to pay
     */

    public function forward_to_pay_change_name($update_status) {
        try {
            $this->db->trans_begin();

            $master_data = array(
                'current_status' => $update_status['status'],
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $this->session->userdata('user_id')
            );
            $this->db->where('id', $update_status['id']);
            $this->db->update('gz_change_of_name_surname_master', $master_data);

            $status_history = array(
                'gz_master_id' => $update_status['id'],
                'change_of_name_surname_status' => $update_status['status'],
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => $this->session->userdata('user_id')
            );
            $this->db->insert('gz_change_of_name_surname_status_his', $status_history);

            $notification_data_applicant = array(
                'master_id' => $update_status['id'],
                'module_id' => 2,
                'user_id' => $this->session->userdata('user_id'),
                'text' => "Change of name/surname request forwarded to you for payment",
                'is_viewed' => 0,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_applicant', $notification_data_applicant);

            $mobile = $this->db->select('u.mobile, g.file_no')
                            ->from('gz_applicants_details u')
                            ->join('gz_change_of_name_surname_master g', 'u.id = g.user_id')
                            ->where('g.id', $update_status['id'])
                            ->where('u.status', 1)
                            ->get()->row();

            // load SMS library will activate once live
            $this->load->library("cdac_sms");
            // message format
            $message = "Extraordinary Gazette File No. {$mobile->file_no} has been approved by the Govt. Press. Govt. of (StateName).";
            $sms_api = new Cdac_sms();
            // send SMS using API
            $template_id = "1007938090042852633";
            $sms_api->sendOtpSMS($message, $mobile, $template_id);

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
     * Count of change of name/surname for govt listing
     */

    public function get_total_cnt_govt_change_name_pending($data = array()) {

        $this->db->select('p.*')
                    ->from('gz_change_of_name_surname_master p')
                    ->join('gz_change_of_name_surname_status_master s', 's.id = p.current_status')
                    ->join('gz_change_of_name_surname_doument_det d', 'd.gz_master_id = p.id')
                    ->where('p.deleted', 0)
                    ->where_in('p.current_status', array(7, 9, 17));

                    if (!empty($data['statusType'])) {
                        $this->db->like('p.current_status', $data['statusType']);
                    }
                    if (!empty($data['file_no'])) {
                        $this->db->like('p.file_no', $data['file_no']);
                    }
                    
                    if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
                        $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                        $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                    } else {
                        if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                            
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                        } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                            
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
                        }
                    }
            
                    return $this->db->count_all_results();
    }
    public function get_total_cnt_govt_change_name_paid($data = array()) {

        $this->db->select('p.file_no, p.created_at, s.status_name, p.id, d.notice_softcopy_pdf')
                    ->from('gz_change_of_name_surname_master p')
                    ->join('gz_change_of_name_surname_status_master s', 's.id = p.current_status')
                    ->join('gz_change_of_name_surname_doument_det d', 'd.gz_master_id = p.id')
                    ->where('p.deleted', 0)
                    ->where('p.current_status', 10);

                    if (!empty($data['statusType'])) {
                        $this->db->like('p.current_status', $data['statusType']);
                    }
                    if (!empty($data['file_no'])) {
                        $this->db->like('p.file_no', $data['file_no']);
                    }
                    
                    if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
                        $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                        $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                    } else {
                        if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                            
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                        } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                            
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
                        }
                    }
            
                    return $this->db->count_all_results();
    }
    public function get_total_cnt_govt_change_name_published($data = array()) {

        $this->db->select('p.file_no, p.created_at, s.status_name, p.id, d.notice_softcopy_pdf')
                    ->from('gz_change_of_name_surname_master p')
                    ->join('gz_change_of_name_surname_status_master s', 's.id = p.current_status')
                    ->join('gz_change_of_name_surname_doument_det d', 'd.gz_master_id = p.id')
                    ->where('p.deleted', 0)
                    ->where('p.current_status', 11);

                    if (!empty($data['statusType'])) {
                        $this->db->like('p.current_status', $data['statusType']);
                    }
                    if (!empty($data['file_no'])) {
                        $this->db->like('p.file_no', $data['file_no']);
                    }
                    
                    if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
                        $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                        $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                    } else {
                        if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                            
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                        } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                            
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
                        }
                    }
            
                    return $this->db->count_all_results();
    }

    /*
     * Pending change of name/surname govt press user
     */

    public function get_total_cnt_govt_list_pending_change_name($limit, $offset,$data = array()) {

         $this->db->select('p.file_no, p.created_at, s.status_name, p.id, d.notice_softcopy_pdf')
                        ->from('gz_change_of_name_surname_master p')
                        ->join('gz_change_of_name_surname_status_master s', 's.id = p.current_status')
                        ->join('gz_change_of_name_surname_doument_det d', 'd.gz_master_id = p.id')
                        ->where('p.deleted', 0)
                        ->where_in('p.current_status', array(7, 9, 17));

                        if (!empty($data['statusType'])) {
                            $this->db->like('p.current_status', $data['statusType']);
                        }
                        if (!empty($data['file_no'])) {
                            $this->db->like('p.file_no', $data['file_no']);
                        }
                        
                        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                        } else {
                            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                                
                                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                                
                                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            }
                        }

        $this->db->order_by('p.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function status_change_name_surname_pending_list() {
        return $this->db->select('c.*')
                        ->from('gz_change_of_name_surname_status_master as c')
                        ->where_in('c.id', array(7, 9))
                        ->order_by('c.id', 'DESC')
                        ->get()->result();
    }
    public function status_change_partnership_pending_list() {
        return $this->db->select('c.*')
                        ->from('gz_par_sur_status_master as c')
                        ->where_in('c.id', array(6, 16))
                        ->order_by('c.id', 'DESC')
                        ->get()->result();
    }
    /*
     * Paid change of name/surname govt press user
     */

    public function get_total_cnt_govt_list_payed_change_name($limit, $offset,$data = array()) {
         $this->db->select('p.file_no, p.created_at, s.status_name, p.id, d.notice_softcopy_pdf')
                        ->from('gz_change_of_name_surname_master p')
                        ->join('gz_change_of_name_surname_status_master s', 's.id = p.current_status')
                        ->join('gz_change_of_name_surname_doument_det d', 'd.gz_master_id = p.id')
                        ->where('p.deleted', 0)
                        ->where('p.current_status', 10);

                        if (!empty($data['statusType'])) {
                            $this->db->like('p.current_status', $data['statusType']);
                        }
                        if (!empty($data['file_no'])) {
                            $this->db->like('p.file_no', $data['file_no']);
                        }
                        
                        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                        } else {
                            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                                
                                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                                
                                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            }
                        }

                        $this->db->order_by('p.id', 'DESC');
                        $this->db->limit($limit, $offset);
                        return $this->db->get()->result();
    }

    /*
     * Published change of name/surname govt press user
     */

    public function get_total_cnt_govt_list_publish_change_name($limit, $offset,$data = array()) {
         $this->db->select('p.file_no, p.created_at, s.status_name, p.id, d.notice_softcopy_pdf')
                        ->from('gz_change_of_name_surname_master p')
                        ->join('gz_change_of_name_surname_status_master s', 's.id = p.current_status')
                        ->join('gz_change_of_name_surname_doument_det d', 'd.gz_master_id = p.id')
                        ->where('p.deleted', 0)
                        ->where('p.current_status', 11);
                        
                        if (!empty($data['statusType'])) {
                            $this->db->like('p.current_status', $data['statusType']);
                        }
                        if (!empty($data['file_no'])) {
                            $this->db->like('p.file_no', $data['file_no']);
                        }
                        
                        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                        } else {
                            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                                
                                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                                
                                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            }
                        }
                        $this->db->order_by('p.id', 'DESC');
                        $this->db->limit($limit, $offset);
                        return $this->db->get()->result();
    }

    /**
     * Filtarion For Change of Name/Surname Govt Press
     */

     public function get_total_cnt_govt_list_change_name_search($limit, $offset, $data = array()){
        $this->db->select('p.file_no, p.created_at, s.status_name, p.id, d.notice_softcopy_pdf, app.name AS applicant_name')
                ->from('gz_change_of_name_surname_master p')
                ->join('gz_change_of_name_surname_status_master s', 's.id = p.current_status')
                ->join('gz_change_of_name_surname_doument_det d', 'd.gz_master_id = p.id')
                ->join('gz_applicants_details app', 'p.user_id = app.id')
                ->where('p.deleted', 0)
                ->where_in('p.current_status', array(7, 9, 17));

        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('p.file_no', $data['file_no']);
        }
        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
            $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_to']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_form']);
            }
        }
        //print_r($this->db->last_query()); exit;
       
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
     }

     public function get_total_cnt_govt_list_payed_change_name_search($limit, $offset, $data = array()){
        $this->db->select('p.file_no, p.created_at, s.status_name, p.id, d.notice_softcopy_pdf, app.name AS applicant_name')
                ->from('gz_change_of_name_surname_master p')
                ->join('gz_change_of_name_surname_status_master s', 's.id = p.current_status')
                ->join('gz_change_of_name_surname_doument_det d', 'd.gz_master_id = p.id')
                ->join('gz_applicants_details app', 'p.user_id = app.id')
                ->where('p.deleted', 0)
                ->where_in('p.current_status', 10);

        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('p.file_no', $data['file_no']);
        }
        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
            $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_to']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_form']);
            }
        }
        //print_r($this->db->last_query()); exit;
       
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
     }

     public function get_total_cnt_govt_list_publish_change_name_search($limit, $offset, $data = array()){
        $this->db->select('p.file_no, p.created_at, s.status_name, p.id, d.notice_softcopy_pdf, app.name AS applicant_name')
                ->from('gz_change_of_name_surname_master p')
                ->join('gz_change_of_name_surname_status_master s', 's.id = p.current_status')
                ->join('gz_change_of_name_surname_doument_det d', 'd.gz_master_id = p.id')
                ->join('gz_applicants_details app', 'p.user_id = app.id')
                ->where('p.deleted', 0)
                ->where_in('p.current_status', 11);

        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('p.file_no', $data['file_no']);
        }
        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
            $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_to']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_form']);
            }
        }
        //print_r($this->db->last_query()); exit;
       
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
     }
    /*
     * PDF path on view details of govt press user
     */

    public function get_pdf_path_of_change_of_name($id) {
        return $this->db->select('press_pdf, notice_softcopy_pdf')
                        ->from('gz_change_of_name_surname_doument_det')
                        ->where('gz_master_id', $id)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->get()->row();
    }

    /*
     * Get per page value of change of name/surname module for calculating the price of publication
     */

    public function get_per_page_value_change_of_name_surname() {
        $data = $this->db->select('pricing')
                        ->from('gz_modules_wise_pricing')
                        ->where('module_id', 2)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->get()->row();
        $price = "";
        if (!empty($data)) {
            $price = (int) $data->pricing;
        }
        return $price;
    }

    /*
     * Get latest remarks on view details
     */

    public function get_remarks_on_change_name_surname($id, $status) {
        $data = $this->db->select('remarks')
                        ->from('gz_change_of_name_surname_remarks_master')
                        ->where('change_of_name_surname_id', $id)
                        ->where('status_id', $status)
                        ->get()->row();
        $remarks = "";

        if (!empty($data)) {
            $remarks = $data->remarks;
        }
        return $remarks;
    }



    public function get_remarks_on_change_partnership($id, $status) {
        $data = $this->db->select('remark')
                        ->from('gz_par_partnership_chang_remark')
                        ->where('gz_master_id', $id)
                        ->where('status_history_id', $status)
                        ->get()->row();
        $remarks = "";

        if (!empty($data)) {
            $remarks = $data->remark;
        }
        return $remarks;
    }

    /*
     * Save transaction status for change of name surname
     */

    public function save_change_name_surname_trans_status($insert_array) {
        try {


            $this->db->trans_begin();

            $user_session = $this->session->userdata('user_id');
            $transaction_data = array(
                'change_name_surname_id' => $insert_array['change_name_surname_id'],
                'file_number' => $insert_array['file_number'],
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

            $this->db->insert('gz_change_of_name_surname_payment_details', $transaction_data);
            $last_id = $this->db->insert_id();

            // INSERT INTO the status history Table
            $insert_stat = array(
                'payment_id' => $last_id,
                // Change of Surname
                'payment_type' => 'COS',
                'payment_status' => $insert_array['trans_status'],
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_payment_status_history', $insert_stat);

            $status = 9;

            if ($insert_array['trans_status'] == 'S') {

                $status = 10;
                $admin = $this->db->from('gz_users')
                                    ->where('is_admin', '1')
                                    ->where('status', '1')
                                    ->get()->row();
                
                $notification_data_ct = array(
                    'master_id' => $insert_array['change_name_surname_id'],
                    'module_id' => 2,
                    'user_id' => $user_session,
                    'responsible_user_id' => $admin->id,
                    'text' => "Payment Successful",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                // echo "<pre>";
                // print_r($notification_data_ct);
                // exit;

                $this->db->insert('gz_notification_govt', $notification_data_ct);
            } else if ($insert_array['trans_status'] == 'F') {

                $status = 16;
            } else if ($insert_array['trans_status'] == 'P') {

                $status = 15;
            }

            $master_data = array(
                'current_status' => $status,
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $this->session->userdata('user_id')
            );


            $this->db->where('id', $insert_array['change_name_surname_id']);
            $this->db->update('gz_change_of_name_surname_master', $master_data);

            if ($status != 9) {
                $status_history = array(
                    'gz_master_id' => $insert_array['change_name_surname_id'],
                    'change_of_name_surname_status' => $status,
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'created_by' => $this->session->userdata('user_id')
                );
                $this->db->insert('gz_change_of_name_surname_status_his', $status_history);
            }

            if ($this->db->trans_status() == FALSE) {
                $this->db->trans_rollback();
                return FALSE;
            } else {
                $this->db->trans_commit();
                return TRUE;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    /*
     * Sign PDF
     */

    public function change_name_surname_sign_pdf($update_status, $id) {

        try {

            $this->db->trans_begin();

            $this->db->where('id', $id);
            $this->db->update('gz_change_of_name_surname_master', $update_status);

            if ($this->db->trans_status() == FALSE) {

                $this->db->trans_rollback();
                return FALSE;
            } else {

                $this->db->trans_commit();
                return TRUE;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    /*
     * Publish change of name/surname
     */

    public function change_name_surname_publish($update_status) {

        try {

            $this->db->trans_begin();

            $master_data = array(
                'current_status' => $update_status['status'],
                'is_published' => 1,
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $this->session->userdata('user_id')
            );
            $this->db->where('id', $update_status['id']);
            $this->db->update('gz_change_of_name_surname_master', $master_data);

            $status_history = array(
                'gz_master_id' => $update_status['id'],
                'change_of_name_surname_status' => $update_status['status'],
                'created_at' => date("Y-m-d H:i:s", time()),
                'created_by' => $this->session->userdata('user_id')
            );
            $this->db->insert('gz_change_of_name_surname_status_his', $status_history);

            for ($i = 1; $i <= 3; $i++) {
                $notification_data_ct = array(
                    'master_id' => $update_status['id'],
                    'module_id' => 2,
                    'user_id' => $this->session->userdata('user_id'),
                    'text' => "Change of name/surname gazette published successfully",
                    'is_viewed' => 0,
                    'pro_ver_app' => $i,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_ct', $notification_data_ct);
            }

            $notification_data_applicant = array(
                'master_id' => $update_status['id'],
                'module_id' => 2,
                'user_id' => $this->session->userdata('user_id'),
                'text' => "Change of name/surname gazette published successfully",
                'is_viewed' => 0,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_applicant', $notification_data_applicant);

            if ($this->db->trans_status() == FALSE) {

                $this->db->trans_rollback();
                return FALSE;
            } else {

                $this->db->trans_commit();
                return TRUE;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    /*
     * UPDATE press signed PDF file path name
     */

    public function get_press_signed_pdf_path_change_name_surname($data = array()) {
        // Update documents table
        $doc_data = array(
            'press_signed_pdf' => $data['press_signed_pdf_path'],
        );

        $this->db->where('id', $data['gazette_id']);
        $this->db->update('gz_change_of_name_surname_master', $doc_data);
        return ($this->db->affected_rows() == 1) ? true : false;
    }

    public function get_gazette_documents_change_name_surname($gazette_id) {
        return $this->db->select('*')->from('gz_change_of_name_surname_doument_det')
                        ->where('gz_master_id', $gazette_id)
                        ->get()->row();
    }

    public function get_sl_no_change_name_surname() {
        $sl_no = 0;
        $year = date("Y");
        $result = $this->db->select('*')->from('gz_change_of_name_surname_master')->get();
        if ($result->num_rows() > 0) {
            $sl_no_data = $this->db->query('SELECT MAX(sl_no) AS sl_no FROM gz_change_of_name_surname_master WHERE  YEAR(created_at) = ' . $year);
            $sl_no = @($sl_no_data->row()->sl_no + 1);
        } else {
            $sl_no = 1;
        }
        return $sl_no;
    }

    public function save_preview_press_gazette_change_name_surname($data) {
        try {
            $this->db->trans_begin();

            // Update gazetee table
            $gazette_data = array(
                'sl_no' => $data['sl_no'],
                'saka_date' =>$data['saka_date'],
                'current_status' => $data['status_id'],
                'modified_by' => $data['user_id'],
                'modified_at' => date('Y-m-d H:i:s', time()),
            );

            $this->db->where('id', $data['gazette_id']);
            $this->db->update('gz_change_of_name_surname_master', $gazette_data);

            //Insert to gazette_status table
            $stat_data = array(
                'user_id' => $data['user_id'],
                'gz_master_id' => $data['gazette_id'],
                // published
                'change_of_name_surname_status' => $data['status_id'],
                'created_by' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_change_of_name_surname_status_his', $stat_data);

            $notification_data_applicant = array(
                'master_id' => $data['gazette_id'],
                'module_id' => 2,
                'user_id' => $this->session->userdata('user_id'),
                'text' => "Change of name/surname request approved by government press",
                'is_viewed' => 0,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );
            $this->db->insert('gz_notification_applicant', $notification_data_applicant);

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

    public function get_file_number_change_name_surname($id) {

        return $this->db->select('file_no')
                        ->from('gz_change_of_name_surname_master')
                        ->where('id', $id)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->get()->row();
    }

    /*
     * Ashwini Code
     */

    public function get_total_parter($limit, $offset) {

        $this->db->select('p.*,u.name,h.status_det');
        $this->db->from('gz_change_of_partnership_master p');
        $this->db->join('gz_applicants_details u', 'p.user_id = u.id');
        $this->db->join('gz_par_sur_status_master h', 'p.cur_status = h.id');
        $this->db->where('p.deleted', '0');
        $this->db->where('p.gazette_type_id', '1');

        if ($this->session->userdata('is_applicant')) {
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
        }

        $this->db->order_by('p.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function get_total_parter_count() {

        $this->db->select('p.*,g.gazette_type,h.status_det');
        $this->db->from('gz_change_of_partnership_master p');
        $this->db->join('gz_gazette_type g', 'p.gazette_type_id = g.id');
        $this->db->join('gz_par_sur_status_master h', 'p.cur_status = h.id');
        $this->db->where('p.deleted', '0');
        $this->db->where('p.gazette_type_id', '1');

        if ($this->session->userdata('is_applicant')) {
            $this->db->where('p.user_id', $this->session->userdata('user_id'));
        } else if ($this->session->userdata('is_c&t')) {
            if ($this->session->userdata('is_verifier_approver') == 'Verifier') {
                // $arr = array();
                // array_push($arr, "1", "10", "12", "15");
                // $this->db->where_in('p.cur_status', $arr);
            } else if ($this->session->userdata('is_verifier_approver') == 'Approver') {
                $arr = array();
                array_push($arr, "2", "9", "5");
                $this->db->where('p.cur_status >=', 2);
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
        }

        return $this->db->count_all_results();
    }

    public function get_total_parter_weekly($limit, $offset) {

        return $this->db->select('*')
                        ->from('gz_change_of_partnership_master')
                        ->where('deleted', '0')
                        ->where('gazette_type_id', '2')
                        ->order_by('id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    public function get_total_parter_count_weekly() {

        return $this->db->select('*')
                        ->from('gz_change_of_partnership_master')
                        ->where('deleted', '0')
                        ->where('gazette_type_id', '2')
                        ->count_all_results();
    }

    public function get_total_gz_types() {

        return $this->db->select('*')
                        ->from('gz_gazette_type')
                        ->where('id', 1)
                        ->get()->result();
    }

    public function get_total_tot_docu() {

        return $this->db->select('*')
                        ->from('gz_partnership_docu_det_master')
                        ->where('deleted', '0')
                        ->order_by('id', 'ASC')
                        ->get()->result();
    }

    /*
     * add partner details  
     */

    public function insert_partnership_details($data = array(), $doc, $pdf_file_path) {

        $return = $this->db->select('file_no')
                        ->from('gz_change_of_partnership_master')
                        ->where('deleted', '0')
                        ->order_by('id', 'DESC')
                        ->limit(1)
                        ->get()->row();

        $final_file_no1 = "0001";
        if (!empty($return)) {
            $file_no = $return->file_no;
            //echo $file_no;
            $ex_file_no = explode('-', $file_no);
            //echo $ex_file_no[3];
            $final_file_no = $ex_file_no[1] + 1;

            $final_file_no1 = '000' . $final_file_no;
        }


        $year = date("Y");

        $file_type = 'XP' . '-' . $final_file_no1 . '-' . $year;

        $array_data = array(
            'gazette_type_id' => 1,
            'state_id' => $data['state_id'],
            'district_id' => $data['district_id'],
            'police_station_id' => $data['police_station_id'],
            'address_1' => $data['address_1'],
            'address_2' => $data['address_2'],
            'pincode' => $data['pincode'],
            'partnership_firm_name' => $data['partnership_firm_name'],
            'partnership_registration_no' => $data['partnership_registration_no'],
            'file_no' => $file_type,
            'user_id' => $this->session->userdata('user_id'),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s', time()),
            'status' => 1,
            'deleted' => 0,
            'cur_status' => 1
        );

        $this->db->insert('gz_change_of_partnership_master', $array_data);
        $last_id = $this->db->insert_id();

        if ($this->db->affected_rows() == 1) {

            $array_data1 = array(
                'gz_mas_type_id' => $last_id,
                'orignal_partnership_deed' => $data['img_id_1'],
                'deed_of_reconstitution_of_partnership' => $data["img_id_2"],
                'igr_certificate_file' => $data["img_id_3"],
                'notice_of_softcopy' => base_url() . $doc,
                'orignal_newspaper_of_advertisement' => $data["img_id_6"],
                'noc_notice_of_outgoing' => $data["img_id_8"],
                'challan' => $data["img_id_9"],
                'pdf_for_notice_of_softcopy' => base_url() . $pdf_file_path,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => 1,
                'deleted' => 0
            );

            $this->db->insert('gz_change_of_partnetship_doument_det', $array_data1);

            $array_data4 = array(
                'gz_mas_type_id' => $last_id,
                'par_status' => 1,
                'user_id' => $this->session->userdata('user_id'),
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => 1,
                'deleted' => 0
            );

            $this->db->insert('gz_change_of_par_status_his', $array_data4);

            $array_data_aadhar = array(
                'gz_mas_type_id' => $last_id,
                'aadhar_card_of_partnetship' => $data["img_id_5"],
                'created_at' => date('Y-m-d H:i:s', time()),
                'created_by' => $this->session->userdata('user_id'),
                'status' => 1,
                'deleted' => 0
            );

            $this->db->insert('gz_change_of_par_aadhar_det', $array_data_aadhar);

            $array_data_pan = array(
                'gz_mas_type_id' => $last_id,
                'pan_card_of_partnetship' => $data["img_id_4"],
                'created_at' => date('Y-m-d H:i:s', time()),
                'created_by' => $this->session->userdata('user_id'),
                'status' => 1,
                'deleted' => 0
            );

            $this->db->insert('gz_change_of_partnership_pan_det', $array_data_pan);
            
            // for ($x = 1; $x <= 9; $x++) {
            //     if ($x == 7) {
            //         //echo 'abcefgh';
            //         foreach ($data["img_id_7"] as $img1) {
            //             $array_data6 = array(
            //                 'gz_mas_type_id' => $last_id,
            //                 'gz_docu_id' => $x,
            //                 'document_name' => base_url() . $doc,
            //                 'created_by' => $this->session->userdata('user_id'),
            //                 'created_at' => date('Y-m-d H:i:s', time()),
            //                 'status' => 1,
            //                 'deleted' => 0
            //             );
            //             $this->db->insert('gz_change_of_partnership_history', $array_data6);
            //         }
            //     } else {
            //         $array_data7 = array(
            //             'gz_mas_type_id' => $last_id,
            //             'gz_docu_id' => $x,
            //             'document_name' => $data["img_id_$x"],
            //             'created_by' => $this->session->userdata('user_id'),
            //             'created_at' => date('Y-m-d H:i:s', time()),
            //             'status' => 1,
            //             'deleted' => 0
            //         );
            //         $this->db->insert('gz_change_of_partnership_history', $array_data7);
            //     }
            // }
            
              for ($x = 1; $x <= 9; $x++) {
                if($x == 7){
                $array_data9 = array(
                    'gz_mas_type_id' => $last_id,
                    'gz_docu_id' => 7,
                    'document_name' => base_url() . $doc,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_partnership_history', $array_data9);
                }else{

                $array_data7 = array(
                'gz_mas_type_id' => $last_id,
                'gz_docu_id' => $x,
                'document_name' => $data["img_id_$x"],
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => 1,
                'deleted' => 0
                );

                $this->db->insert('gz_change_of_partnership_history', $array_data7);
                }
            }

            $processers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Processor')    
                                ->where('module_id', 1)
                                ->get();
            foreach($processers->result() as $processer){
                $processerID = $processer->id;
            }

            $array_data_no = array(
                'master_id' => $last_id,
                'module_id' => 1,
                'user_id' => $this->session->userdata('user_id'),
                'responsible_user_id' => $processerID,
                'text' => 'Change of Partnership application submitted',
                'pro_ver_app' => '2',
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => 1,
                'deleted' => 0
            );

            $this->db->insert('gz_notification_ct', $array_data_no);
        }
        
        return $last_id;
    }

    public function get_part_det($id) {

        return $this->db->select('p.*,g.document_name')
                        ->from('gz_change_of_partnership_master p')
                        ->join('gz_change_of_partnetship_doument_det g', 'p.id = g.gz_mas_type_id')
                        ->where('p.deleted', '0')
                        ->where('p.id', $id)
                        ->get()->row();
    }

    public function View_details_par($id) {

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
    }

    public function select_cur_path($id) {

        return $this->db->select('*')
                        ->from('gz_change_of_partnetship_doument_det')
                        ->where('deleted', '0')
                        ->where('gz_mas_type_id', $id)
                        ->get()->result();
    }

    public function select_cur_path_load($id) {

        return $this->db->select('p.*,h.par_status')
                        ->from('gz_change_of_partnetship_doument_det p')
                        ->join('gz_change_of_par_status_his h', 'p.gz_mas_type_id = h.gz_mas_type_id')
                        ->where('h.deleted', '0')
                        ->where('h.gz_mas_type_id', $id)
                        ->get()->row();
    }

    public function select_cur_path_pan($id) {

        return $this->db->select('pan_card_of_partnetship')
                        ->from('gz_change_of_partnership_pan_det')
                        ->where('deleted', '0')
                        ->where('gz_mas_type_id', $id)
                        ->get()->result();
    }

    public function select_cur_path_aadhar($id) {

        return $this->db->select('aadhar_card_of_partnetship')
                        ->from('gz_change_of_par_aadhar_det')
                        ->where('deleted', '0')
                        ->where('gz_mas_type_id', $id)
                        ->get()->result();
    }

    /**
     * Find the current status of Change of partnership table
     */

    //  public function current($id){
    //     return $this->db->select('cur_status')
    //     ->from('gz_change_of_partnership_master')
    //     ->where('id',$id)
    //     ->get()->row();

    //  }
    /*
     * edit par details 
     */

    public function update_partnership_details($data = array(), $doc, $pdf_file_path) {
        $result = $this->db->select('*')
                ->from('gz_change_of_partnership_master')
                ->where('id', $data['par_id'])
                ->get();

        if ($result->num_rows() > 0) {

            $status_chk = $this->db->select('*')
                            ->from('gz_change_of_partnership_master')
                            ->where('id', $data['par_id'])
                            ->get()->row();

                $cur_status = $status_chk->cur_status;
                if($cur_status == 19){
                    $array_data = array(
                        'cur_status' => 20,
                        'state_id' => $data['state_id'],
                        'district_id' => $data['district_id'],
                        'police_station_id' => $data['police_station_id'],
                        'address_1' => $data['address_1'],
                        'address_2' => $data['address_2'],
                    );

                    $this->db->where('id', $data['par_id']);
                    $this->db->update('gz_change_of_partnership_master', $array_data);

                    $processers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Processor')    
                                ->where('module_id', 1)
                                ->get();
                    foreach($processers->result() as $processer){
                        $processerID = $processer->id;
                    }

                    $array_data_no = array(
                        'master_id' => $data['par_id'],
                        'module_id' => 1,
                        'user_id' => $this->session->userdata('user_id'),
                        'responsible_user_id' => $processerID,
                        'pro_ver_app' => '2',
                        'text' => 'Change of Partnership application resubmitted to C and T Processor',
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'status' => 1,
                        'deleted' => 0
                    );

                    $this->db->insert('gz_notification_ct', $array_data_no);


                    $array_data1 = array(
                        'gz_mas_type_id' => $data['par_id'],
                        'orignal_partnership_deed' => $data['img_id_1'][0],
                        'deed_of_reconstitution_of_partnership' => $data["img_id_2"][0],
                        'igr_certificate_file' => $data["img_id_3"][0],
                        'notice_of_softcopy' => $doc,
                        'orignal_newspaper_of_advertisement' => $data["img_id_6"][0],
                        'noc_notice_of_outgoing' => $data["img_id_8"][0],
                        'challan' => $data["img_id_9"][0],
                        'pdf_for_notice_of_softcopy' => $pdf_file_path,
                        'modified_by' => $this->session->userdata('user_id'),
                        'modified_at' => date('Y-m-d H:i:s', time()),
                        'status' => 1,
                        'deleted' => 0
                    );

                    $this->db->where('id', $data['par_id']);
                    $this->db->update('gz_change_of_partnetship_doument_det', $array_data1);


                    $count_pan = count($data['img_id_4']);


                    for ($i = 0; $i < $count_pan; $i++) {

                        if (!empty($data["img_id_4"][$i])) {


                            $this->db->where('gz_mas_type_id', $data['par_id']);
                            $this->db->delete('gz_change_of_partnership_pan_det');

                            $array_data2 = array(
                                'gz_mas_type_id' => $data['par_id'],
                                'pan_card_of_partnetship' => $data["img_id_4"][$i],
                                'created_by' => $this->session->userdata('user_id'),
                                'created_at' => date('Y-m-d H:i:s', time()),
                                'status' => 1,
                                'deleted' => 0
                            );
                            $this->db->insert('gz_change_of_partnership_pan_det', $array_data2);
                        }
                    }

                    $count_aadhar = count($data['img_id_5']);
                    for ($i = 0; $i < $count_aadhar; $i++) {
                        if (!empty($data["img_id_5"][$i])) {

                            $this->db->where('gz_mas_type_id', $data['par_id']);
                            $this->db->delete('gz_change_of_par_aadhar_det');


                            $array_data3 = array(
                                'gz_mas_type_id' => $data['par_id'],
                                'aadhar_card_of_partnetship' => $data["img_id_5"][$i],
                                'created_by' => $this->session->userdata('user_id'),
                                'created_at' => date('Y-m-d H:i:s', time()),
                                'status' => 1,
                                'deleted' => 0
                            );
                            $this->db->insert('gz_change_of_par_aadhar_det', $array_data3);
                        }
                    }

                    $array_data4 = array(
                        'gz_mas_type_id' => $data['par_id'],
                        'par_status' => 20,
                        'user_id' => $this->session->userdata('user_id'),
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_par_status_his', $array_data4);

                    for ($x = 1; $x <= 9; $x++) {
                        if ($x == 4) {
                            foreach ($data["img_id_4"] as $img) {
                                $array_data5 = array(
                                    'gz_mas_type_id' => $data['par_id'],
                                    'gz_docu_id' => $x,
                                    'document_name' => $img,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_at' => date('Y-m-d H:i:s', time()),
                                    'status' => 1,
                                    'deleted' => 0
                                );
                                $this->db->insert('gz_change_of_partnership_history', $array_data5);
                            }
                        } else if ($x == 5) {
                            foreach ($data["img_id_5"] as $img1) {
                                $array_data6 = array(
                                    'gz_mas_type_id' => $data['par_id'],
                                    'gz_docu_id' => $x,
                                    'document_name' => $img1,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_at' => date('Y-m-d H:i:s', time()),
                                    'status' => 1,
                                    'deleted' => 0
                                );
                                $this->db->insert('gz_change_of_partnership_history', $array_data6);
                            }
                        } else if ($x == 7) {
                            foreach ($data["img_id_5"] as $img1) {
                                $array_data6 = array(
                                    'gz_mas_type_id' => $data['par_id'],
                                    'gz_docu_id' => $x,
                                    'document_name' => $doc,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_at' => date('Y-m-d H:i:s', time()),
                                    'status' => 1,
                                    'deleted' => 0
                                );
                                $this->db->insert('gz_change_of_partnership_history', $array_data6);
                            }
                        } else {
                            $array_data7 = array(
                                'gz_mas_type_id' => $data['par_id'],
                                'gz_docu_id' => $x,
                                'document_name' => $data["img_id_$x"][0],
                                'created_by' => $this->session->userdata('user_id'),
                                'created_at' => date('Y-m-d H:i:s', time()),
                                'status' => 1,
                                'deleted' => 0
                            );
                            $this->db->insert('gz_change_of_partnership_history', $array_data7);
                        }
                    }

                    return $data['par_id'];
                }else if($cur_status == 22){
                    $array_data = array(
                        'cur_status' => 23,
                        'state_id' => $data['state_id'],
                        'district_id' => $data['district_id'],
                        'police_station_id' => $data['police_station_id'],
                        'address_1' => $data['address_1'],
                        'address_2' => $data['address_2'],
                    );
    
                    $this->db->where('id', $data['par_id']);
                    $this->db->update('gz_change_of_partnership_master', $array_data);

                    $verifiers = $this->db->from('gz_igr_users')
                                            ->where('verify_approve', 'Verifier')   
                                            ->get();

                    foreach($verifiers->result() as $verifier){
                        $verifierID = $verifier->id;
                    }

                    $array_data_no = array(
                        'master_id' => $data['par_id'],
                        'module_id' => 1,
                        'user_id' => $verifierID,
                        'responsible_user_id' => $verifierID,
                        'text' => 'Change of Partnership application resubmitted to IGR Verifier',
                        'ver_app' => '2',
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'status' => 1,
                        'deleted' => 0
                    );
    
                    $this->db->insert('gz_notification_igr', $array_data_no);
    
    
                    $array_data1 = array(
                        'gz_mas_type_id' => $data['par_id'],
                        'orignal_partnership_deed' => $data['img_id_1'][0],
                        'deed_of_reconstitution_of_partnership' => $data["img_id_2"][0],
                        'igr_certificate_file' => $data["img_id_3"][0],
                        'notice_of_softcopy' => $doc,
                        'orignal_newspaper_of_advertisement' => $data["img_id_6"][0],
                        'noc_notice_of_outgoing' => $data["img_id_8"][0],
                        'challan' => $data["img_id_9"][0],
                        'pdf_for_notice_of_softcopy' => $pdf_file_path,
                        'modified_by' => $this->session->userdata('user_id'),
                        'modified_at' => date('Y-m-d H:i:s', time()),
                        'status' => 1,
                        'deleted' => 0
                    );
    
                    $this->db->where('id', $data['par_id']);
                    $this->db->update('gz_change_of_partnetship_doument_det', $array_data1);
    
    
                    $count_pan = count($data['img_id_4']);
    
    
                    for ($i = 0; $i < $count_pan; $i++) {
    
                        if (!empty($data["img_id_4"][$i])) {
    
    
                            $this->db->where('gz_mas_type_id', $data['par_id']);
                            $this->db->delete('gz_change_of_partnership_pan_det');
    
                            $array_data2 = array(
                                'gz_mas_type_id' => $data['par_id'],
                                'pan_card_of_partnetship' => $data["img_id_4"][$i],
                                'created_by' => $this->session->userdata('user_id'),
                                'created_at' => date('Y-m-d H:i:s', time()),
                                'status' => 1,
                                'deleted' => 0
                            );
                            $this->db->insert('gz_change_of_partnership_pan_det', $array_data2);
                        }
                    }
    
                    $count_aadhar = count($data['img_id_5']);
                    for ($i = 0; $i < $count_aadhar; $i++) {
                        if (!empty($data["img_id_5"][$i])) {
    
                            $this->db->where('gz_mas_type_id', $data['par_id']);
                            $this->db->delete('gz_change_of_par_aadhar_det');
    
    
                            $array_data3 = array(
                                'gz_mas_type_id' => $data['par_id'],
                                'aadhar_card_of_partnetship' => $data["img_id_5"][$i],
                                'created_by' => $this->session->userdata('user_id'),
                                'created_at' => date('Y-m-d H:i:s', time()),
                                'status' => 1,
                                'deleted' => 0
                            );
                            $this->db->insert('gz_change_of_par_aadhar_det', $array_data3);
                        }
                    }
    
                    $array_data4 = array(
                        'gz_mas_type_id' => $data['par_id'],
                        'par_status' => 23,
                        'user_id' => $this->session->userdata('user_id'),
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_par_status_his', $array_data4);
    
                    for ($x = 1; $x <= 9; $x++) {
                        if ($x == 4) {
                            foreach ($data["img_id_4"] as $img) {
                                $array_data5 = array(
                                    'gz_mas_type_id' => $data['par_id'],
                                    'gz_docu_id' => $x,
                                    'document_name' => $img,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_at' => date('Y-m-d H:i:s', time()),
                                    'status' => 1,
                                    'deleted' => 0
                                );
                                $this->db->insert('gz_change_of_partnership_history', $array_data5);
                            }
                        } else if ($x == 5) {
                            foreach ($data["img_id_5"] as $img1) {
                                $array_data6 = array(
                                    'gz_mas_type_id' => $data['par_id'],
                                    'gz_docu_id' => $x,
                                    'document_name' => $img1,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_at' => date('Y-m-d H:i:s', time()),
                                    'status' => 1,
                                    'deleted' => 0
                                );
                                $this->db->insert('gz_change_of_partnership_history', $array_data6);
                            }
                        } else if ($x == 7) {
                            foreach ($data["img_id_5"] as $img1) {
                                $array_data6 = array(
                                    'gz_mas_type_id' => $data['par_id'],
                                    'gz_docu_id' => $x,
                                    'document_name' => $doc,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_at' => date('Y-m-d H:i:s', time()),
                                    'status' => 1,
                                    'deleted' => 0
                                );
                                $this->db->insert('gz_change_of_partnership_history', $array_data6);
                            }
                        } else {
                            $array_data7 = array(
                                'gz_mas_type_id' => $data['par_id'],
                                'gz_docu_id' => $x,
                                'document_name' => $data["img_id_$x"][0],
                                'created_by' => $this->session->userdata('user_id'),
                                'created_at' => date('Y-m-d H:i:s', time()),
                                'status' => 1,
                                'deleted' => 0
                            );
                            $this->db->insert('gz_change_of_partnership_history', $array_data7);
                        }
                    }
    
                    return $data['par_id'];
                }else if($cur_status == 25){
                    $array_data = array(
                        'cur_status' => 26,
                        'state_id' => $data['state_id'],
                        'district_id' => $data['district_id'],
                        'police_station_id' => $data['police_station_id'],
                        'address_1' => $data['address_1'],
                        'address_2' => $data['address_2'],
                    );
    
                    $this->db->where('id', $data['par_id']);
                    $this->db->update('gz_change_of_partnership_master', $array_data);
    
                    $approvers = $this->db->from('gz_igr_users')
                                            ->where('verify_approve', 'Approver')   
                                            ->get();

                    foreach($approvers->result() as $approver){
                        $approverID = $approver->id;
                    }

                    $array_data_no = array(
                        'master_id' => $data['par_id'],
                        'module_id' => 1,
                        'user_id' => $approverID,
                        'responsible_user_id' => $approverID,
                        'text' => 'Change of Partnership application resubmitted to IGR Approver',
                        'ver_app' => '2',
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'status' => 1,
                        'deleted' => 0
                    );
    
                    $this->db->insert('gz_notification_igr', $array_data_no);
    
    
                    $array_data1 = array(
                        'gz_mas_type_id' => $data['par_id'],
                        'orignal_partnership_deed' => $data['img_id_1'][0],
                        'deed_of_reconstitution_of_partnership' => $data["img_id_2"][0],
                        'igr_certificate_file' => $data["img_id_3"][0],
                        'notice_of_softcopy' => $doc,
                        'orignal_newspaper_of_advertisement' => $data["img_id_6"][0],
                        'noc_notice_of_outgoing' => $data["img_id_8"][0],
                        'challan' => $data["img_id_9"][0],
                        'pdf_for_notice_of_softcopy' => $pdf_file_path,
                        'modified_by' => $this->session->userdata('user_id'),
                        'modified_at' => date('Y-m-d H:i:s', time()),
                        'status' => 1,
                        'deleted' => 0
                    );
    
                    $this->db->where('id', $data['par_id']);
                    $this->db->update('gz_change_of_partnetship_doument_det', $array_data1);
    
    
                    $count_pan = count($data['img_id_4']);
    
    
                    for ($i = 0; $i < $count_pan; $i++) {
    
                        if (!empty($data["img_id_4"][$i])) {
    
    
                            $this->db->where('gz_mas_type_id', $data['par_id']);
                            $this->db->delete('gz_change_of_partnership_pan_det');
    
                            $array_data2 = array(
                                'gz_mas_type_id' => $data['par_id'],
                                'pan_card_of_partnetship' => $data["img_id_4"][$i],
                                'created_by' => $this->session->userdata('user_id'),
                                'created_at' => date('Y-m-d H:i:s', time()),
                                'status' => 1,
                                'deleted' => 0
                            );
                            $this->db->insert('gz_change_of_partnership_pan_det', $array_data2);
                        }
                    }
    
                    $count_aadhar = count($data['img_id_5']);
                    for ($i = 0; $i < $count_aadhar; $i++) {
                        if (!empty($data["img_id_5"][$i])) {
    
                            $this->db->where('gz_mas_type_id', $data['par_id']);
                            $this->db->delete('gz_change_of_par_aadhar_det');
    
    
                            $array_data3 = array(
                                'gz_mas_type_id' => $data['par_id'],
                                'aadhar_card_of_partnetship' => $data["img_id_5"][$i],
                                'created_by' => $this->session->userdata('user_id'),
                                'created_at' => date('Y-m-d H:i:s', time()),
                                'status' => 1,
                                'deleted' => 0
                            );
                            $this->db->insert('gz_change_of_par_aadhar_det', $array_data3);
                        }
                    }
    
                    $array_data4 = array(
                        'gz_mas_type_id' => $data['par_id'],
                        'par_status' => 26,
                        'user_id' => $this->session->userdata('user_id'),
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_par_status_his', $array_data4);
    
                    for ($x = 1; $x <= 9; $x++) {
                        if ($x == 4) {
                            foreach ($data["img_id_4"] as $img) {
                                $array_data5 = array(
                                    'gz_mas_type_id' => $data['par_id'],
                                    'gz_docu_id' => $x,
                                    'document_name' => $img,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_at' => date('Y-m-d H:i:s', time()),
                                    'status' => 1,
                                    'deleted' => 0
                                );
                                $this->db->insert('gz_change_of_partnership_history', $array_data5);
                            }
                        } else if ($x == 5) {
                            foreach ($data["img_id_5"] as $img1) {
                                $array_data6 = array(
                                    'gz_mas_type_id' => $data['par_id'],
                                    'gz_docu_id' => $x,
                                    'document_name' => $img1,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_at' => date('Y-m-d H:i:s', time()),
                                    'status' => 1,
                                    'deleted' => 0
                                );
                                $this->db->insert('gz_change_of_partnership_history', $array_data6);
                            }
                        } else if ($x == 7) {
                            foreach ($data["img_id_5"] as $img1) {
                                $array_data6 = array(
                                    'gz_mas_type_id' => $data['par_id'],
                                    'gz_docu_id' => $x,
                                    'document_name' => $doc,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_at' => date('Y-m-d H:i:s', time()),
                                    'status' => 1,
                                    'deleted' => 0
                                );
                                $this->db->insert('gz_change_of_partnership_history', $array_data6);
                            }
                        } else {
                            $array_data7 = array(
                                'gz_mas_type_id' => $data['par_id'],
                                'gz_docu_id' => $x,
                                'document_name' => $data["img_id_$x"][0],
                                'created_by' => $this->session->userdata('user_id'),
                                'created_at' => date('Y-m-d H:i:s', time()),
                                'status' => 1,
                                'deleted' => 0
                            );
                            $this->db->insert('gz_change_of_partnership_history', $array_data7);
                        }
                    }
    
                    return $data['par_id'];
                }else if($cur_status == 13){
                    $array_data = array(
                        'cur_status' => 28,
                        'state_id' => $data['state_id'],
                        'district_id' => $data['district_id'],
                        'police_station_id' => $data['police_station_id'],
                        'address_1' => $data['address_1'],
                        'address_2' => $data['address_2'],
                    );
    
                    $this->db->where('id', $data['par_id']);
                    $this->db->update('gz_change_of_partnership_master', $array_data);
    
                    $verifiers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Verifier')    
                                ->where('module_id', 1)
                                ->get();

                    foreach($verifiers->result() as $verifier){
                        $verifierID = $verifier->id;
                    }

                    $array_data_no = array(
                        'master_id' => $data['par_id'],
                        'module_id' => 1,
                        'user_id' => $this->session->userdata('user_id'),
                        'responsible_user_id' => $verifierID,
                        'pro_ver_app' => '2',
                        'text' => 'Change of Partnership application resubmitted to C and T Verifier',
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'status' => 1,
                        'deleted' => 0
                    );

                    $this->db->insert('gz_notification_ct', $array_data_no);
    
    
                    $array_data1 = array(
                        'gz_mas_type_id' => $data['par_id'],
                        'orignal_partnership_deed' => $data['img_id_1'][0],
                        'deed_of_reconstitution_of_partnership' => $data["img_id_2"][0],
                        'igr_certificate_file' => $data["img_id_3"][0],
                        'notice_of_softcopy' => $doc,
                        'orignal_newspaper_of_advertisement' => $data["img_id_6"][0],
                        'noc_notice_of_outgoing' => $data["img_id_8"][0],
                        'challan' => $data["img_id_9"][0],
                        'pdf_for_notice_of_softcopy' => $pdf_file_path,
                        'modified_by' => $this->session->userdata('user_id'),
                        'modified_at' => date('Y-m-d H:i:s', time()),
                        'status' => 1,
                        'deleted' => 0
                    );
    
                    $this->db->where('id', $data['par_id']);
                    $this->db->update('gz_change_of_partnetship_doument_det', $array_data1);
    
    
                    $count_pan = count($data['img_id_4']);
    
    
                    for ($i = 0; $i < $count_pan; $i++) {
    
                        if (!empty($data["img_id_4"][$i])) {
    
    
                            $this->db->where('gz_mas_type_id', $data['par_id']);
                            $this->db->delete('gz_change_of_partnership_pan_det');
    
                            $array_data2 = array(
                                'gz_mas_type_id' => $data['par_id'],
                                'pan_card_of_partnetship' => $data["img_id_4"][$i],
                                'created_by' => $this->session->userdata('user_id'),
                                'created_at' => date('Y-m-d H:i:s', time()),
                                'status' => 1,
                                'deleted' => 0
                            );
                            $this->db->insert('gz_change_of_partnership_pan_det', $array_data2);
                        }
                    }
    
                    $count_aadhar = count($data['img_id_5']);
                    for ($i = 0; $i < $count_aadhar; $i++) {
                        if (!empty($data["img_id_5"][$i])) {
    
                            $this->db->where('gz_mas_type_id', $data['par_id']);
                            $this->db->delete('gz_change_of_par_aadhar_det');
    
    
                            $array_data3 = array(
                                'gz_mas_type_id' => $data['par_id'],
                                'aadhar_card_of_partnetship' => $data["img_id_5"][$i],
                                'created_by' => $this->session->userdata('user_id'),
                                'created_at' => date('Y-m-d H:i:s', time()),
                                'status' => 1,
                                'deleted' => 0
                            );
                            $this->db->insert('gz_change_of_par_aadhar_det', $array_data3);
                        }
                    }
    
                    $array_data4 = array(
                        'gz_mas_type_id' => $data['par_id'],
                        'par_status' => 28,
                        'user_id' => $this->session->userdata('user_id'),
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_par_status_his', $array_data4);
    
                    for ($x = 1; $x <= 9; $x++) {
                        if ($x == 4) {
                            foreach ($data["img_id_4"] as $img) {
                                $array_data5 = array(
                                    'gz_mas_type_id' => $data['par_id'],
                                    'gz_docu_id' => $x,
                                    'document_name' => $img,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_at' => date('Y-m-d H:i:s', time()),
                                    'status' => 1,
                                    'deleted' => 0
                                );
                                $this->db->insert('gz_change_of_partnership_history', $array_data5);
                            }
                        } else if ($x == 5) {
                            foreach ($data["img_id_5"] as $img1) {
                                $array_data6 = array(
                                    'gz_mas_type_id' => $data['par_id'],
                                    'gz_docu_id' => $x,
                                    'document_name' => $img1,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_at' => date('Y-m-d H:i:s', time()),
                                    'status' => 1,
                                    'deleted' => 0
                                );
                                $this->db->insert('gz_change_of_partnership_history', $array_data6);
                            }
                        } else if ($x == 7) {
                            foreach ($data["img_id_5"] as $img1) {
                                $array_data6 = array(
                                    'gz_mas_type_id' => $data['par_id'],
                                    'gz_docu_id' => $x,
                                    'document_name' => $doc,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_at' => date('Y-m-d H:i:s', time()),
                                    'status' => 1,
                                    'deleted' => 0
                                );
                                $this->db->insert('gz_change_of_partnership_history', $array_data6);
                            }
                        } else {
                            $array_data7 = array(
                                'gz_mas_type_id' => $data['par_id'],
                                'gz_docu_id' => $x,
                                'document_name' => $data["img_id_$x"][0],
                                'created_by' => $this->session->userdata('user_id'),
                                'created_at' => date('Y-m-d H:i:s', time()),
                                'status' => 1,
                                'deleted' => 0
                            );
                            $this->db->insert('gz_change_of_partnership_history', $array_data7);
                        }
                    }
    
                    return $data['par_id'];
                }else if($cur_status == 30){
                    $array_data = array(
                        'cur_status' => 31,
                        'state_id' => $data['state_id'],
                        'district_id' => $data['district_id'],
                        'police_station_id' => $data['police_station_id'],
                        'address_1' => $data['address_1'],
                        'address_2' => $data['address_2'],
                    );
    
                    $this->db->where('id', $data['par_id']);
                    $this->db->update('gz_change_of_partnership_master', $array_data);
    
                    $approvers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Approver')    
                                ->where('module_id', 1)
                                ->get();

                    foreach($approvers->result() as $approver){
                        $approverID = $approver->id;
                    }

                    $array_data_no = array(
                        'master_id' => $data['par_id'],
                        'module_id' => 1,
                        'user_id' => $this->session->userdata('user_id'),
                        'responsible_user_id' => $approverID,
                        'pro_ver_app' => '2',
                        'text' => 'Change of Partnership application resubmitted to C and T Approver',
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'status' => 1,
                        'deleted' => 0
                    );

                    $this->db->insert('gz_notification_ct', $array_data_no);
    
    
                    $array_data1 = array(
                        'gz_mas_type_id' => $data['par_id'],
                        'orignal_partnership_deed' => $data['img_id_1'][0],
                        'deed_of_reconstitution_of_partnership' => $data["img_id_2"][0],
                        'igr_certificate_file' => $data["img_id_3"][0],
                        'notice_of_softcopy' => $doc,
                        'orignal_newspaper_of_advertisement' => $data["img_id_6"][0],
                        'noc_notice_of_outgoing' => $data["img_id_8"][0],
                        'challan' => $data["img_id_9"][0],
                        'pdf_for_notice_of_softcopy' => $pdf_file_path,
                        'modified_by' => $this->session->userdata('user_id'),
                        'modified_at' => date('Y-m-d H:i:s', time()),
                        'status' => 1,
                        'deleted' => 0
                    );
    
                    $this->db->where('id', $data['par_id']);
                    $this->db->update('gz_change_of_partnetship_doument_det', $array_data1);
    
    
                    $count_pan = count($data['img_id_4']);
    
    
                    for ($i = 0; $i < $count_pan; $i++) {
    
                        if (!empty($data["img_id_4"][$i])) {
    
    
                            $this->db->where('gz_mas_type_id', $data['par_id']);
                            $this->db->delete('gz_change_of_partnership_pan_det');
    
                            $array_data2 = array(
                                'gz_mas_type_id' => $data['par_id'],
                                'pan_card_of_partnetship' => $data["img_id_4"][$i],
                                'created_by' => $this->session->userdata('user_id'),
                                'created_at' => date('Y-m-d H:i:s', time()),
                                'status' => 1,
                                'deleted' => 0
                            );
                            $this->db->insert('gz_change_of_partnership_pan_det', $array_data2);
                        }
                    }
    
                    $count_aadhar = count($data['img_id_5']);
                    for ($i = 0; $i < $count_aadhar; $i++) {
                        if (!empty($data["img_id_5"][$i])) {
    
                            $this->db->where('gz_mas_type_id', $data['par_id']);
                            $this->db->delete('gz_change_of_par_aadhar_det');
    
    
                            $array_data3 = array(
                                'gz_mas_type_id' => $data['par_id'],
                                'aadhar_card_of_partnetship' => $data["img_id_5"][$i],
                                'created_by' => $this->session->userdata('user_id'),
                                'created_at' => date('Y-m-d H:i:s', time()),
                                'status' => 1,
                                'deleted' => 0
                            );
                            $this->db->insert('gz_change_of_par_aadhar_det', $array_data3);
                        }
                    }
    
                    $array_data4 = array(
                        'gz_mas_type_id' => $data['par_id'],
                        'par_status' => 31,
                        'user_id' => $this->session->userdata('user_id'),
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_par_status_his', $array_data4);
    
                    for ($x = 1; $x <= 9; $x++) {
                        if ($x == 4) {
                            foreach ($data["img_id_4"] as $img) {
                                $array_data5 = array(
                                    'gz_mas_type_id' => $data['par_id'],
                                    'gz_docu_id' => $x,
                                    'document_name' => $img,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_at' => date('Y-m-d H:i:s', time()),
                                    'status' => 1,
                                    'deleted' => 0
                                );
                                $this->db->insert('gz_change_of_partnership_history', $array_data5);
                            }
                        } else if ($x == 5) {
                            foreach ($data["img_id_5"] as $img1) {
                                $array_data6 = array(
                                    'gz_mas_type_id' => $data['par_id'],
                                    'gz_docu_id' => $x,
                                    'document_name' => $img1,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_at' => date('Y-m-d H:i:s', time()),
                                    'status' => 1,
                                    'deleted' => 0
                                );
                                $this->db->insert('gz_change_of_partnership_history', $array_data6);
                            }
                        } else if ($x == 7) {
                            foreach ($data["img_id_5"] as $img1) {
                                $array_data6 = array(
                                    'gz_mas_type_id' => $data['par_id'],
                                    'gz_docu_id' => $x,
                                    'document_name' => $doc,
                                    'created_by' => $this->session->userdata('user_id'),
                                    'created_at' => date('Y-m-d H:i:s', time()),
                                    'status' => 1,
                                    'deleted' => 0
                                );
                                $this->db->insert('gz_change_of_partnership_history', $array_data6);
                            }
                        } else {
                            $array_data7 = array(
                                'gz_mas_type_id' => $data['par_id'],
                                'gz_docu_id' => $x,
                                'document_name' => $data["img_id_$x"][0],
                                'created_by' => $this->session->userdata('user_id'),
                                'created_at' => date('Y-m-d H:i:s', time()),
                                'status' => 1,
                                'deleted' => 0
                            );
                            $this->db->insert('gz_change_of_partnership_history', $array_data7);
                        }
                    }
    
                    return $data['par_id'];
                }

                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
        } else {
            return false;
        }

    }

    /*
     * check  partnetship exits or not 
     */

    public function exists_par_change($id) {
        $result = $this->db->select('*')->from('gz_change_of_partnership_master')->where('id', $id)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * delete partnetship change
     */

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('gz_change_of_partnership_master');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * select status details
     */

    public function status_list($id) {
        return $this->db->select('c.*,p.status_det')
                        ->from('gz_change_of_par_status_his c', 'LEFT')
                        ->join('gz_par_sur_status_master p', 'c.par_status = p.id', 'LEFT')
                        ->where('c.gz_mas_type_id', $id)
                        ->order_by('c.id', 'DESC')
                        ->get()->result();
    }

    /*
     * select remarkm
     */
    /*
     * select status details
     */

    public function select_det_remark($id) {
        return $this->db->select('remark')
                        ->from('gz_par_partnership_chang_remark ')
                        ->where('status_id', $id)
                        ->get()->row();
    }

    // Fetch Name/Surname remarks based on status_id
    // @param int status_id 

    public function select_name_surname_remark($status_id) {
        return $this->db->select('remarks')
                    ->from('gz_change_of_name_surname_remarks_master')
                    ->where('status_id', $status_id)
                    ->get()->row();
    }

    /*
     * select status details
     */

    public function docu_list() {

        $return = $this->db->select('d.document_name as docu,d.id as did')
                        ->from('gz_partnership_docu_det_master d')
                        ->order_by('id', 'DESC')
                        ->get()->result();
        //echo $this->db->last_query();exit();

        return $return;

//        $sql = "SELECT p.*,d.document_name as docu FROM gz_change_of_partnership_history p join gz_partnership_docu_det_master d','p.gz_docu_id = d.id WHERE gz_mas_type_id  = $id and Id < (SELECT MAX(Id) FROM gz_change_of_partnership_history) order by id DESC";
    }

    /*
     * select all document
     */

    public function get_all_docu($id, $par_id) {

        $return = $this->db->select('document_name as docu,gz_docu_id,created_at')
                        ->from('gz_change_of_partnership_history')
                        ->where('gz_docu_id', $id)
                        ->where('gz_mas_type_id', $par_id)
                        ->order_by('id', 'DESC')
                        ->get()->result();

        return $return;
    }

    /*
     * Change status of C & T verrifier return 
     */

    public function update_reject_sta_ct_ver($par_id, $reject_remarks) {


        $status = array(
            'gz_mas_type_id' => $par_id,
            'user_id' => $this->session->userdata('user_id'),
            'par_status' => 9,
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date("Y-m-d H:i:s", time()),
            'status' => 1,
            'deleted' => 0
        );
        $this->db->insert('gz_change_of_par_status_his', $status);
        $last_id = $this->db->insert_id();

        $remark_det = array(
            'gz_master_id' => $par_id,
            'status_id' => $last_id,
            'status_history_id' => 9,
            'remark' => $reject_remarks,
            'user_id' => $this->session->userdata('user_id'),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date("Y-m-d H:i:s", time()),
            'status' => 1,
            'deleted' => 0
        );
        $this->db->insert('gz_par_partnership_chang_remark', $remark_det);


        $array_data = array(
            'cur_status' => 9
        );

        $this->db->where('id', $par_id);
        $this->db->update('gz_change_of_partnership_master', $array_data);

        $array_data_no = array(
            'master_id' => $par_id,
            'module_id' => 1,
            'user_id' => $this->session->userdata('user_id'),
            'text' => 'C & T Verifier Returned',
            'pro_ver_app' => '3',
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s', time()),
            'status' => 1,
            'deleted' => 0
        );

        $this->db->insert('gz_notification_ct', $array_data_no);


        $array_data_app = array(
            'master_id' => $par_id,
            'module_id' => 1,
            'user_id' => $this->session->userdata('user_id'),
            'text' => 'C & T Verifier Returned',
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s', time()),
            'status' => 1,
            'deleted' => 0
        );

        $this->db->insert('gz_notification_applicant', $array_data_app);


        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Change status of C & T approver return 
     */

    public function update_reject_sta_ct_app($par_id, $rej_app_remark) {

        $result = $this->db->select('*')
                            ->from('gz_change_of_partnership_master')
                            ->where('id', $par_id)
                            ->get();
        if ($result->num_rows() > 0) {
            $status_chk = $this->db->select('*')
                            ->from('gz_change_of_partnership_master')
                            ->where('id', $par_id)
                            ->get()->row();

            $cur_status = $status_chk->cur_status;
            
            if ($cur_status == 2) {
                $status = array(
                    'gz_mas_type_id' => $par_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'par_status' => 3,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_par_status_his', $status);

                $last_id = $this->db->insert_id();

                $remark_det = array(
                    'gz_master_id' => $par_id,
                    'status_id' => $last_id,
                    'status_history_id' => 3,
                    'remark' => $rej_app_remark,
                    'user_id' => $this->session->userdata('user_id'),
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_par_partnership_chang_remark', $remark_det);



                $array_data = array(
                    'cur_status' => 3
                );
                $this->db->where('id', $par_id);
                $this->db->update('gz_change_of_partnership_master', $array_data);

                $array_data_no = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'user_id' => $this->session->userdata('user_id'),
                    'text' => 'C & T Approved',
                    'ver_app' => '2',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_igr', $array_data_no);


                $array_data_app = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'user_id' => $this->session->userdata('user_id'),
                    'text' => 'C & T Approved',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_applicant', $array_data_app);
            }else if ($cur_status == 9) {
                $status = array(
                    'gz_mas_type_id' => $par_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'par_status' => 12,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_par_status_his', $status);
                $last_id = $this->db->insert_id();

                $remark_det = array(
                    'gz_master_id' => $par_id,
                    'status_id' => $last_id,
                    'status_history_id' => 12,
                    'remark' => $rej_app_remark,
                    'user_id' => $this->session->userdata('user_id'),
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_par_partnership_chang_remark', $remark_det);



                $array_data = array(
                    'cur_status' => 12
                );
                $this->db->where('id', $par_id);
                $this->db->update('gz_change_of_partnership_master', $array_data);

                $array_data_no = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'user_id' => $this->session->userdata('user_id'),
                    'text' => 'C&T verifier returned to arroved',
                    'ver_app' => '3',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_igr', $array_data_no);


                $array_data_app = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'user_id' => $this->session->userdata('user_id'),
                    'text' => 'C&T verifier returned to arroved',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_applicant', $array_data_app);
            }else if ($cur_status == 29) {
                $status = array(
                    'gz_mas_type_id' => $par_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'par_status' => 32,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_par_status_his', $status);
                $last_id = $this->db->insert_id();

                $remark_det = array(
                    'gz_master_id' => $par_id,
                    'status_id' => $last_id,
                    'status_history_id' => 32,
                    'remark' => $rej_app_remark,
                    'user_id' => $this->session->userdata('user_id'),
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_par_partnership_chang_remark', $remark_det);



                $array_data = array(
                    'cur_status' => 32
                );
                $this->db->where('id', $par_id);
                $this->db->update('gz_change_of_partnership_master', $array_data);
                
                $applicant_id = $this->db->select('*')
                                    ->from('gz_change_of_partnership_master')
                                    ->where('id', $par_id)
                                    ->get()->row();

                $array_data_app = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' =>$applicant_id->user_id ,
                    'text' => 'Application Rejected By C&T Approver',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_applicant', $array_data_app);

                $admin = $this->db->from('gz_users')
                                    ->where('is_admin', 1)
                                    ->where('status', 1)
                                    ->get()->row();

                $array_data_app = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $admin->id,
                    'text' => 'Application Rejected By C&T Approver',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0,
                    'is_read' => 0
                );

                $this->db->insert('gz_notification_govt', $array_data_app);
            }
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
     * Change status of C & T approver return 
     */

    public function update_var_applicant_return($par_id, $ret_applicant_rem) {

        //echo "ok1";
        $result = $this->db->select('*')
                ->from('gz_change_of_partnership_master')
                ->where('id', $par_id)
                ->get();
            $applicant_id = $result->row();
               
        if ($result->num_rows() > 0) {

            $status_chk = $this->db->select('*')
                            ->from('gz_change_of_partnership_master')
                            ->where('id', $par_id)
                            ->get()->row();

            $cur_status = $status_chk->cur_status;

            if ($cur_status == 12) {

                $status = array(
                    'gz_mas_type_id' => $par_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'par_status' => 13,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_change_of_par_status_his', $status);
                $last_id = $this->db->insert_id();

                $remark_det = array(
                    'gz_master_id' => $par_id,
                    'status_id' => $last_id,
                    'status_history_id' => 13,
                    'remark' => $ret_applicant_rem,
                    'user_id' => $this->session->userdata('user_id'),
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_par_partnership_chang_remark', $remark_det);



                $array_data = array(
                    'cur_status' => 13
                );

                $this->db->where('id', $par_id);
                $this->db->update('gz_change_of_partnership_master', $array_data);


                $array_data_app = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'user_id' => $this->session->userdata('user_id'),
                    'text' => 'C&T verifier return to applicant',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_applicant', $array_data_app);
            } else if ($cur_status == 15) {
                $status = array(
                    'gz_mas_type_id' => $par_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'par_status' => 13,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_par_status_his', $status);
                $last_id = $this->db->insert_id();

                $remark_det = array(
                    'gz_master_id' => $par_id,
                    'status_id' => $last_id,
                    'status_history_id' => 13,
                    'remark' => $ret_applicant_rem,
                    'user_id' => $this->session->userdata('user_id'),
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_par_partnership_chang_remark', $remark_det);


                $array_data = array(
                    'cur_status' => 13
                );
                $this->db->where('id', $par_id);
                $this->db->update('gz_change_of_partnership_master', $array_data);

                $array_data_no = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'user_id' => $this->session->userdata('user_id'),
                    'text' => 'IGR verifier return to C&T verifier',
                    'pro_ver_app' => '2',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_ct', $array_data_no);

                $array_data_app = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'user_id' => $this->session->userdata('user_id'),
                    'text' => 'IGR verifier return to C&T verifier',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_applicant', $array_data_app);


            }else if ($cur_status == 1) {
                $status = array(
                    'gz_mas_type_id' => $par_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'par_status' => 19,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_par_status_his', $status);
                $last_id = $this->db->insert_id();

                $remark_det = array(
                    'gz_master_id' => $par_id,
                    'status_id' => $last_id,
                    'status_history_id' => 19,
                    'remark' => $ret_applicant_rem,
                    'user_id' => $this->session->userdata('user_id'),
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_par_partnership_chang_remark', $remark_det);


                $array_data = array(
                    'cur_status' => 19
                );
                $this->db->where('id', $par_id);
                $this->db->update('gz_change_of_partnership_master', $array_data);

                $array_data_app = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'applicant_user_id' => $applicant_id->user_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $applicant_id->user_id,
                    'text' => 'Application returned from C & T Processor',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_applicant', $array_data_app);

            }else if ($cur_status == 21) {
                $status = array(
                    'gz_mas_type_id' => $par_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'par_status' => 22,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_par_status_his', $status);
                $last_id = $this->db->insert_id();

                $remark_det = array(
                    'gz_master_id' => $par_id,
                    'status_id' => $last_id,
                    'status_history_id' => 22,
                    'remark' => $ret_applicant_rem,
                    'user_id' => $this->session->userdata('user_id'),
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_par_partnership_chang_remark', $remark_det);


                $array_data = array(
                    'cur_status' => 22
                );
                $this->db->where('id', $par_id);
                $this->db->update('gz_change_of_partnership_master', $array_data);

                $array_data_app = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'applicant_user_id' => $applicant_id->user_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $applicant_id->user_id,
                    'text' => 'Application returned from IGR verifier',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_applicant', $array_data_app);

                $approvers = $this->db->from('gz_igr_users')
                                ->where('verify_approve', 'Approver')   
                                ->get()->row();

                $array_data_no = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'responsible_user_id' => $approvers->id,
                    'user_id' => $this->session->userdata('user_id'),
                    'text' => 'Application forwarded IGR Verifier to IGR Approver',
                    'pro_ver_app' => '2',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_igr', $array_data_no);

            }else if ($cur_status == 4) {
                $status = array(
                    'gz_mas_type_id' => $par_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'par_status' => 25,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_par_status_his', $status);
                $last_id = $this->db->insert_id();

                $remark_det = array(
                    'gz_master_id' => $par_id,
                    'status_id' => $last_id,
                    'status_history_id' => 25,
                    'remark' => $ret_applicant_rem,
                    'user_id' => $this->session->userdata('user_id'),
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_par_partnership_chang_remark', $remark_det);


                $array_data = array(
                    'cur_status' => 25
                );
                $this->db->where('id', $par_id);
                $this->db->update('gz_change_of_partnership_master', $array_data);

                $array_data_app = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'applicant_user_id' => $applicant_id->user_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $applicant_id->user_id,
                    'text' => 'Application returned from IGR approver',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_applicant', $array_data_app);

            }else if ($cur_status == 27) {
                $status = array(
                    'gz_mas_type_id' => $par_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'par_status' => 13,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_par_status_his', $status);
                $last_id = $this->db->insert_id();

                $remark_det = array(
                    'gz_master_id' => $par_id,
                    'status_id' => $last_id,
                    'status_history_id' => 13,
                    'remark' => $ret_applicant_rem,
                    'user_id' => $this->session->userdata('user_id'),
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_par_partnership_chang_remark', $remark_det);


                $array_data = array(
                    'cur_status' => 13
                );
                $this->db->where('id', $par_id);
                $this->db->update('gz_change_of_partnership_master', $array_data);

                $array_data_app = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'applicant_user_id' => $applicant_id->user_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $applicant_id->user_id,
                    'text' => 'Application retuned from C T verifier',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_applicant', $array_data_app);

            }else if ($cur_status == 29) {
                $status = array(
                    'gz_mas_type_id' => $par_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'par_status' => 30,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_par_status_his', $status);
                $last_id = $this->db->insert_id();

                $remark_det = array(
                    'gz_master_id' => $par_id,
                    'status_id' => $last_id,
                    'status_history_id' => 30,
                    'remark' => $ret_applicant_rem,
                    'user_id' => $this->session->userdata('user_id'),
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_par_partnership_chang_remark', $remark_det);


                $array_data = array(
                    'cur_status' => 30
                );
                $this->db->where('id', $par_id);
                $this->db->update('gz_change_of_partnership_master', $array_data);

                $array_data_app = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'applicant_user_id' => $applicant_id->user_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $applicant_id->user_id,
                    'text' => 'Application retuned from C & T approver',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_applicant', $array_data_app);
            }else if($cur_status == 20){
                $status = array(
                    'gz_mas_type_id' => $par_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'par_status' => 19,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_par_status_his', $status);
                $last_id = $this->db->insert_id();

                $remark_det = array(
                    'gz_master_id' => $par_id,
                    'status_id' => $last_id,
                    'status_history_id' => 19,
                    'remark' => $ret_applicant_rem,
                    'user_id' => $this->session->userdata('user_id'),
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_par_partnership_chang_remark', $remark_det);


                $array_data = array(
                    'cur_status' => 19
                );
                $this->db->where('id', $par_id);
                $this->db->update('gz_change_of_partnership_master', $array_data);

                $array_data_app = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'applicant_user_id' => $applicant_id->user_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $applicant_id->user_id,
                    'text' => 'Application returned from C & T Processor',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_applicant', $array_data_app);
            }

            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
     * Change status of C & T approver return 
     */

    public function ver_docu_c_t_user($par_id, $forward_remarks) {
        $result = $this->db->select('*')
                ->from('gz_change_of_partnership_master')
                ->where('id', $par_id)
                ->get();

        $applicant_id = $result->row();

        if ($result->num_rows() > 0) {

            $status_chk = $this->db->select('*')
                            ->from('gz_change_of_partnership_master')
                            ->where('id', $par_id)
                            ->get()->row();

            $cur_status = $status_chk->cur_status;

            if ($cur_status == 1 || $cur_status == 20){
                
                $status = array(
                    'gz_mas_type_id' => $par_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'par_status' => 21,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_change_of_par_status_his', $status);
                $last_id = $this->db->insert_id();

                $remark_det = array(
                    'gz_master_id' => $par_id,
                    'remark' => $forward_remarks,
                    'status_history_id' => 21,
                    'status_id' => $last_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_par_partnership_chang_remark', $remark_det);


                $array_data = array(
                    'cur_status' => 21
                );

                $this->db->where('id', $par_id);
                $this->db->update('gz_change_of_partnership_master', $array_data);

                $verifiers = $this->db->from('gz_igr_users')
                                ->where('verify_approve', 'Verifier')   
                                ->get()->row();

                $array_data_no = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $verifiers->id,
                    'text' => 'Application forwarded C & T Processor to IGR Verifier',
                    'ver_app' => '2',
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'created_by' => $this->session->userdata('user_id'),
                    'deleted' => 0,
                    'status' => 1
                );

                $this->db->insert('gz_notification_igr', $array_data_no);

                $array_data_app = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $applicant_id->user_id,
                    'text' => 'Application forwarded C & T Processor to IGR Verifier',
                    'applicant_user_id' => $applicant_id->user_id,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_applicant', $array_data_app);
            
            
            }elseif ($cur_status == 27 || $cur_status == 28){
                
                $status = array(
                    'gz_mas_type_id' => $par_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'par_status' => 29,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_change_of_par_status_his', $status);
                $last_id = $this->db->insert_id();

                $remark_det = array(
                    'gz_master_id' => $par_id,
                    'remark' => $forward_remarks,
                    'status_history_id' => 29,
                    'status_id' => $last_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_par_partnership_chang_remark', $remark_det);


                $array_data = array(
                    'cur_status' => 29
                );

                $this->db->where('id', $par_id);
                $this->db->update('gz_change_of_partnership_master', $array_data);

                $approvers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Approver')    
                                ->where('module_id', 1)
                                ->get()->row();

                $array_data_no = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $approvers->id,
                    'text' => 'Application forwarded C&T Verifier to C&T Approver',
                    'pro_ver_app' => '2',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0,
                );

                $this->db->insert('gz_notification_ct', $array_data_no);

                $array_data_app = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'applicant_user_id' => $applicant_id->user_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $applicant_id->user_id,
                    'text' => 'Application forwarded C&T Verifier to C&T Approver',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0,
                );

                $this->db->insert('gz_notification_applicant', $array_data_app);

            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }else {
            return false;
        }

        }
    }

    /*
     * Change status of igr verrifier return 
     */

    public function update_reject_sta_igr_ver($par_id, $reject_remarks) {


        $status = array(
            'gz_mas_type_id' => $par_id,
            'user_id' => $this->session->userdata('user_id'),
            'par_status' => 24,
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date("Y-m-d H:i:s", time()),
            'status' => 1,
            'deleted' => 0
        );
        $this->db->insert('gz_change_of_par_status_his', $status);
        $last_id = $this->db->insert_id();

        $remark_det = array(
            'gz_master_id' => $par_id,
            'status_id' => $last_id,
            'remark' => $reject_remarks,
            'status_history_id' => 24,
            'user_id' => $this->session->userdata('user_id'),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date("Y-m-d H:i:s", time()),
            'status' => 1,
            'deleted' => 0
        );
        $this->db->insert('gz_par_partnership_chang_remark', $remark_det);


        $array_data = array(
            'cur_status' => 24
        );

        $this->db->where('id', $par_id);
        $this->db->update('gz_change_of_partnership_master', $array_data);


        $array_data_no = array(
            'master_id' => $par_id,
            'module_id' => 1,
            'user_id' => $this->session->userdata('user_id'),
            'text' => 'Application Rejected by IGR Approver',
            'pro_ver_app' => '2',
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s', time()),
            'status' => 1,
            'deleted' => 0
        );

        $this->db->insert('gz_notification_ct', $array_data_no);

        $applicant_id = $this->db->select('user_id')
                                ->from('gz_change_of_partnership_master')
                                ->where('id', $par_id)
                                ->get()->row();

        $array_data_app = array(
            'master_id' => $par_id,
            'module_id' => 1,
            'user_id' => $this->session->userdata('user_id'),
            'responsible_user_id' => $applicant_id->user_id,
            'text' => 'Application Rejected by IGR Approver',
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s', time()),
            'status' => 1,
            'deleted' => 0
        );

        $this->db->insert('gz_notification_applicant', $array_data_app);


        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Change status of igr approver return 
     */

    public function update_app_sta_igr_app($par_id, $ret_app_rem) {

        $result = $this->db->select('*')
                ->from('gz_change_of_partnership_master')
                ->where('id', $par_id)
                ->get();
        if ($result->num_rows() > 0) {
            $status_chk = $this->db->select('*')
                            ->from('gz_change_of_partnership_master')
                            ->where('id', $par_id)
                            ->get()->row();

            $cur_status = $status_chk->cur_status;

            if ($cur_status == 4) {
                $status = array(
                    'gz_mas_type_id' => $par_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'par_status' => 5,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_par_status_his', $status);

                $last_id = $this->db->insert_id();

                $remark_det = array(
                    'gz_master_id' => $par_id,
                    'status_id' => $last_id,
                    'status_history_id' => 5,
                    'remark' => $ret_app_rem,
                    'user_id' => $this->session->userdata('user_id'),
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_par_partnership_chang_remark', $remark_det);



                $array_data = array(
                    'cur_status' => 5
                );
                $this->db->where('id', $par_id);
                $this->db->update('gz_change_of_partnership_master', $array_data);

                $applicant_id = $this->db->select('user_id')
                                ->from('gz_change_of_partnership_master')
                                ->where('id', $par_id)
                                ->get()->row();

                $array_data_app = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $applicant_id->user_id,
                    'text' => 'IGR Approved',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_applicant', $array_data_app);

                $verifiers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Verifier')    
                                ->where('module_id', 1)
                                ->get();
                foreach($verifiers->result() as $verifier){
                    $verifierID = $verifier->id;
                }
                $array_data_no = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'user_id' => $verifierID,
                    'text' => 'Application Forwarded to C & T Verifier',
                    'pro_ver_app' => '2',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_ct', $array_data_no);

            } else if ($cur_status == 11) {
                $status = array(
                    'gz_mas_type_id' => $par_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'par_status' => 14,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_par_status_his', $status);

                $last_id = $this->db->insert_id();

                $remark_det = array(
                    'gz_master_id' => $par_id,
                    'status_id' => $last_id,
                    'status_history_id' => 14,
                    'remark' => $ret_app_rem,
                    'user_id' => $this->session->userdata('user_id'),
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_par_partnership_chang_remark', $remark_det);


                $array_data = array(
                    'cur_status' => 14
                );
                $this->db->where('id', $par_id);
                $this->db->update('gz_change_of_partnership_master', $array_data);

                $array_data_app = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'user_id' => $this->session->userdata('user_id'),
                    'text' => 'IGR approve return approved',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_applicant', $array_data_app);
            }
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
     * Change status of igr verifier return 
     */

    public function return_igr_to_ct_ver($par_id, $return_ct) {
        //echo "ok1";

        $status = array(
            'gz_mas_type_id' => $par_id,
            'user_id' => $this->session->userdata('user_id'),
            'par_status' => 15,
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date("Y-m-d H:i:s", time()),
            'status' => 1,
            'deleted' => 0
        );

        $this->db->insert('gz_change_of_par_status_his', $status);

        $last_id = $this->db->insert_id();

        $remark_det = array(
            'gz_master_id' => $par_id,
            'status_id' => $last_id,
            'status_history_id' => 15,
            'remark' => $return_ct,
            'user_id' => $this->session->userdata('user_id'),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date("Y-m-d H:i:s", time()),
            'status' => 1,
            'deleted' => 0
        );
        $this->db->insert('gz_par_partnership_chang_remark', $remark_det);



        $array_data = array(
            'cur_status' => 15
        );

        $this->db->where('id', $par_id);
        $this->db->update('gz_change_of_partnership_master', $array_data);



        $array_data_no = array(
            'master_id' => $par_id,
            'module_id' => 1,
            'user_id' => $this->session->userdata('user_id'),
            'text' => 'IGR approve',
            'ver_app' => '2',
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s', time()),
            'status' => 1,
            'deleted' => 0
        );

        $this->db->insert('gz_notification_igr', $array_data_no);
        
        $applicant_id = $this->db->select('user_id')
                                ->from('gz_change_of_partnership_master')
                                ->where('id', $par_id)
                                ->get()->row();

        $array_data_app = array(
            'master_id' => $par_id,
            'module_id' => 1,
            'user_id' => $this->session->userdata('user_id'),
            'responsible_user_id' => $applicant_id->user_id,
            'text' => 'IGR  verifier rerurned to applicant',
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s', time()),
            'status' => 1,
            'deleted' => 0
        );

        $this->db->insert('gz_notification_applicant', $array_data_app);
    }

    /*
     * Change status of igr verifier return 
     */

    public function return_igr_ver($par_id, $forward_igr_app_rem) {
        
        // Transaction Begin
        $this->db->trans_begin();
        $result = $this->db->select('*')
                            ->from('gz_change_of_partnership_master')
                            ->where('id', $par_id)
                            ->get();

        $applicant_id = $result->row();

        if ($result->num_rows() > 0) {

            $status_chk = $this->db->select('*')
                            ->from('gz_change_of_partnership_master')
                            ->where('id', $par_id)
                            ->get()->row();

            $cur_status = $status_chk->cur_status;
        
            if ($cur_status == 21 || $cur_status == 23){
                $status = array(
                    'gz_mas_type_id' => $par_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'par_status' => 4,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_change_of_par_status_his', $status);

                $last_id = $this->db->insert_id();

                $remark_det = array(
                    'gz_master_id' => $par_id,
                    'status_id' => $last_id,
                    'status_history_id' => 4,
                    'remark' => $forward_igr_app_rem,
                    'user_id' => $this->session->userdata('user_id'),
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_par_partnership_chang_remark', $remark_det);



                $array_data = array(
                    'cur_status' => 4
                );

                $this->db->where('id', $par_id);
                $this->db->update('gz_change_of_partnership_master', $array_data);

                // $array_data_no = array(
                //     'master_id' => $par_id,
                //     'module_id' => 1,
                //     'user_id' => $this->session->userdata('user_id'),
                //     'text' => 'Resubmit application',
                //     'pro_ver_app' => '2',
                //     'created_by' => $this->session->userdata('user_id'),
                //     'created_at' => date('Y-m-d H:i:s', time()),
                //     'status' => 1,
                //     'deleted' => 0
                // );

                // $this->db->insert('gz_notification_ct', $array_data_no);


                $approvers = $this->db->from('gz_igr_users')
                                    ->where('verify_approve', 'Approver')   
                                    ->get()->row();

                    $array_data_no = array(
                        'master_id' => $par_id,
                        'module_id' => 1,
                        'user_id' => $this->session->userdata('user_id'),
                        'responsible_user_id' => $approvers->id,
                        'text' => 'Application forwarded IGR Verifier to IGR Approver',
                        'ver_app' => '2',
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'created_by' => $this->session->userdata('user_id'),
                        'deleted' => 0,
                        'status' => 1
                    );

                    $this->db->insert('gz_notification_igr', $array_data_no);

                    $array_data_app = array(
                        'master_id' => $par_id,
                        'module_id' => 1,
                        'user_id' => $this->session->userdata('user_id'),
                        'responsible_user_id' => $applicant_id->user_id,
                        'text' => 'Application forwarded IGR Verifier to IGR Approver',
                        'applicant_user_id' => $applicant_id->user_id,
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'status' => 1,
                        'deleted' => 0
                    );

                    $this->db->insert('gz_notification_applicant', $array_data_app);
                }
            if ($this->db->trans_status() == FALSE) {
                $this->db->trans_rollback();
                return FALSE;
            } else {
                $this->db->trans_commit();
                return TRUE;
            }
        }
    }

    /**
     * Forward from IGR Approver to C&T Verifier
    */

    public function forward_igr_approver($par_id, $forward_ct_vrifier_rem) {
        $this->db->trans_begin();

        $result = $this->db->select('*')
                ->from('gz_change_of_partnership_master')
                ->where('id', $par_id)
                ->get();

        $applicant_id = $result->row();

        $status = array(
            'gz_mas_type_id' => $par_id,
            'user_id' => $this->session->userdata('user_id'),
            'par_status' => 27,
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date("Y-m-d H:i:s", time()),
            'status' => 1,
            'deleted' => 0
        );

        $this->db->insert('gz_change_of_par_status_his', $status);

        $last_id = $this->db->insert_id();

        $remark_det = array(
            'gz_master_id' => $par_id,
            'status_id' => $last_id,
            'status_history_id' => 27,
            'remark' => $forward_ct_vrifier_rem,
            'user_id' => $this->session->userdata('user_id'),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date("Y-m-d H:i:s", time()),
            'status' => 1,
            'deleted' => 0
        );
        $this->db->insert('gz_par_partnership_chang_remark', $remark_det);



        $array_data = array(
            'cur_status' => 27
        );

        $this->db->where('id', $par_id);
        $this->db->update('gz_change_of_partnership_master', $array_data);

        $verifiers = $this->db->from('gz_c_and_t')
                            ->where('verify_approve', 'Verifier')    
                            ->where('module_id', 1)
                            ->get()->row();
        
        $array_data_no = array(
            'master_id' => $par_id,
            'module_id' => 1,
            'user_id' => $this->session->userdata('user_id'),
            'responsible_user_id' => $verifiers->id,
            'text' => 'Application forwarded IGR Approver to C&T Verifier',
            'pro_ver_app' => '2',
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s', time()),
            'status' => 1,
            'deleted' => 0
        );

        $this->db->insert('gz_notification_ct', $array_data_no);

        $array_data_app = array(
            'master_id' => $par_id,
            'module_id' => 1,
            'user_id' => $this->session->userdata('user_id'),
            'responsible_user_id' => $applicant_id->user_id,
            'text' => 'Application forwarded IGR Approver to C&T Verifier',
            'applicant_user_id' => $applicant_id->user_id,
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s', time()),
            'status' => 1,
            'deleted' => 0
        );

        $this->db->insert('gz_notification_applicant', $array_data_app);
        
        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }


    /*
     * forward to poblish from c&t user to govt press
     */

    public function forward_to_pub_ins($par_id, $forward_to_pub_rem) {
        //echo "ok1";
        $this->db->trans_begin();

        $status = array(
            'gz_mas_type_id' => $par_id,
            'user_id' => $this->session->userdata('user_id'),
            'par_status' => 16,
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date("Y-m-d H:i:s", time()),
            'status' => 1,
            'deleted' => 0
        );

        $this->db->insert('gz_change_of_par_status_his', $status);

        $last_id = $this->db->insert_id();

        $remark_det = array(
            'gz_master_id' => $par_id,
            'status_id' => $last_id,
            'status_history_id' => 16,
            'remark' => $forward_to_pub_rem,
            'user_id' => $this->session->userdata('user_id'),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date("Y-m-d H:i:s", time()),
            'status' => 1,
            'deleted' => 0
        );
        $this->db->insert('gz_par_partnership_chang_remark', $remark_det);



        $array_data = array(
            'cur_status' => 16
        );

        $this->db->where('id', $par_id);
        $this->db->update('gz_change_of_partnership_master', $array_data);

        $admin = $this->db->from('gz_users')
                            ->where('is_admin', '1')
                            ->where('status', '1')
                            ->get()->row();

        $array_data_no = array(
            'master_id' => $par_id,
            'module_id' => 1,
            'user_id' => $this->session->userdata('user_id'),
            'responsible_user_id' => $admin->id,
            'text' => 'C&T Approver Forward to Publish',
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s', time()),
            'status' => 1,
            'deleted' => 0
        );

        $this->db->insert('gz_notification_govt', $array_data_no);

        $processers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Processor')    
                                ->where('module_id', 1)
                                ->get()->row();
                
            $array_data_no = array(
                'master_id' => $par_id,
                'module_id' => 1,
                'user_id' => $processers->id,
                'text' => 'C&T Approver Forward to Publish',
                'pro_ver_app' => '2',
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => 1,
                'deleted' => 0
            );

            $verifiers = $this->db->from('gz_c_and_t')
                            ->where('verify_approve', 'Verifier')    
                            ->where('module_id', 1)
                            ->get()->row();
            
            $array_data_no = array(
                'master_id' => $par_id,
                'module_id' => 1,
                'user_id' => $verifiers->id,
                'text' => 'C&T Approver Forward to Publish',
                'pro_ver_app' => '2',
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => 1,
                'deleted' => 0
            );

            $this->db->insert('gz_notification_ct', $array_data_no);

            $verifiers = $this->db->from('gz_igr_users')
                            ->where('verify_approve', 'Verifier')
                            ->get()->row();
            
            $array_data_no = array(
                'master_id' => $par_id,
                'module_id' => 1,
                'user_id' => $verifiers->id,
                'text' => 'C&T Approver Forward to Publish',
                'ver_app' => '2',
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => 1,
                'deleted' => 0
            );

            $this->db->insert('gz_notification_igr', $array_data_no);


            $approvers = $this->db->from('gz_igr_users')
                                ->where('verify_approve', 'Approver')
                                ->get()->row();

                $array_data_no = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'user_id' => $approvers->id,
                    'text' => 'C&T Approver Forward to Publish',
                    'ver_app' => '2',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 1,
                    'deleted' => 0
                );

            $this->db->insert('gz_notification_igr', $array_data_no);
            $result = $this->db->select('*')
                                ->from('gz_change_of_partnership_master')
                                ->where('id', $par_id)
                                ->get();

            $applicant_id = $result->row();
            $array_data_app = array(
                'master_id' => $par_id,
                'module_id' => 1,
                'applicant_user_id' => $applicant_id->user_id,
                'responsible_user_id' => $applicant_id->user_id,
                'user_id' => $this->session->userdata('user_id'),
                'text' => 'C&T Approver Forward to Publish',
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => 1,
                'deleted' => 0
            );

            $this->db->insert('gz_notification_applicant', $array_data_app);
            

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function get_total_cnt_govt_list_pending($limit, $offset, $data = array()) {

        $this->db->select('p.file_no,p.*,g.gazette_type,h.status_det,dd.pdf_for_notice_of_softcopy')
                    ->from('gz_change_of_partnership_master p')
                    ->join('gz_change_of_partnetship_doument_det dd', 'p.id = dd.gz_mas_type_id ')
                    ->join('gz_gazette_type g', 'p.gazette_type_id = g.id')
                    ->join('gz_par_sur_status_master h', 'p.cur_status = h.id')
                    ->distinct('p.file_no')
                    ->where('p.deleted', '0')
                    ->where('p.gazette_type_id', '1')
                    ->where_in('p.cur_status', array(6, 16));
                    
        if (!empty($data['statusType'])) {
            $this->db->where('p.cur_status', $data['statusType']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('p.file_no', $data['file_no']);
        }
        
        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                
                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                
                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
            }
        }

        $this->db->order_by('p.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function get_total_cnt_govt_list_payed($limit, $offset, $data = array()) {

        $this->db->select('p.file_no,p.*,g.gazette_type,h.status_det,dd.pdf_for_notice_of_softcopy');
        $this->db->from('gz_change_of_partnership_master p');
        $this->db->join('gz_change_of_partnetship_doument_det dd', 'p.id = dd.gz_mas_type_id');
        $this->db->join('gz_gazette_type g', 'p.gazette_type_id = g.id');
        $this->db->join('gz_par_sur_status_master h', 'p.cur_status = h.id');
        $this->db->distinct('p.file_no');
        $this->db->where('p.deleted', '0');
        $this->db->where('p.gazette_type_id', '1');
        $arr = array();
        array_push($arr, "7", "18", "12", "15");
        $this->db->where_in('p.cur_status', $arr);

        if (!empty($data['file_no'])) {
            $this->db->like('p.file_no', $data['file_no']);
        }
        
        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                
                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                
                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
            }
        }
        $this->db->order_by('p.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function get_total_cnt_govt_list_publish($limit, $offset, $data = array()) {

        $this->db->select('p.file_no,p.*,g.gazette_type,h.status_det,dd.pdf_for_notice_of_softcopy');
        $this->db->from('gz_change_of_partnership_master p');
        $this->db->join('gz_change_of_partnetship_doument_det dd', 'p.id = dd.gz_mas_type_id');
        $this->db->join('gz_gazette_type g', 'p.gazette_type_id = g.id');
        $this->db->join('gz_par_sur_status_master h', 'p.cur_status = h.id');
        $this->db->distinct('p.file_no');
        $this->db->where('p.deleted', '0');
        $this->db->where('p.cur_status', '17');
        $this->db->where('p.gazette_type_id', '1');
        $this->db->where('p.press_publish', 1);

        if (!empty($data['file_no'])) {
            $this->db->like('p.file_no', $data['file_no']);
        }
        
        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                
                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                
                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
            }
        }

        $this->db->order_by('p.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }
/**
 * Filter for change of partnership govt press
 */
    public function get_total_cnt_govt_list_pending_serach($limit, $offset, $data = array()){
        $this->db->select('p.file_no,p.*,g.gazette_type,h.status_det,dd.pdf_for_notice_of_softcopy, app.name');
        $this->db->from('gz_change_of_partnership_master p');
        $this->db->join('gz_change_of_partnetship_doument_det dd', 'p.id = dd.gz_mas_type_id ');
        $this->db->join('gz_gazette_type g', 'p.gazette_type_id = g.id');
        $this->db->join('gz_par_sur_status_master h', 'p.cur_status = h.id');
        $this->db->join('gz_applicants_details app', 'p.user_id = app.id');
        $this->db->distinct('p.file_no');
        $this->db->where('p.deleted', '0');
        $this->db->where('p.gazette_type_id', '1');
        $this->db->where_in('p.cur_status', array('6','16'));
        $this->db->order_by('p.id', 'DESC');

        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('p.file_no', $data['file_no']);
        }
        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
            $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_to']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_form']);
            }
        }
        //print_r($this->db->last_query()); exit;
       
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function get_total_cnt_govt_list_payed_search($limit, $offset, $data = array()){
        $this->db->select('p.file_no,p.*,g.gazette_type,h.status_det,dd.pdf_for_notice_of_softcopy, app.name');
        $this->db->from('gz_change_of_partnership_master p');
        $this->db->join('gz_change_of_partnetship_doument_det dd', 'p.id = dd.gz_mas_type_id ');
        $this->db->join('gz_gazette_type g', 'p.gazette_type_id = g.id');
        $this->db->join('gz_par_sur_status_master h', 'p.cur_status = h.id');
        $this->db->join('gz_applicants_details app', 'p.user_id = app.id');
        $this->db->distinct('p.file_no');
        $arr = array();
        array_push($arr, "7", "18");
        $this->db->where_in('p.cur_status', $arr);
        $this->db->where('p.deleted', '0');
        $this->db->where('p.gazette_type_id', '1');
        
        $this->db->order_by('p.id', 'DESC');

        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('p.file_no', $data['file_no']);
        }
        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
            $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_to']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_form']);
            }
        }
        //print_r($this->db->last_query()); exit;
       
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }


    public function get_total_cnt_govt_list_publish_search($limit, $offset, $data = array()){
        $this->db->select('p.file_no,p.*,g.gazette_type,h.status_det,dd.pdf_for_notice_of_softcopy, app.name');
        $this->db->from('gz_change_of_partnership_master p');
        $this->db->join('gz_change_of_partnetship_doument_det dd', 'p.id = dd.gz_mas_type_id ');
        $this->db->join('gz_gazette_type g', 'p.gazette_type_id = g.id');
        $this->db->join('gz_par_sur_status_master h', 'p.cur_status = h.id');
        $this->db->join('gz_applicants_details app', 'p.user_id = app.id');
        $this->db->distinct('p.file_no');
        $this->db->where('p.deleted', '0');
        $this->db->where('p.gazette_type_id', '1');
        $this->db->where('p.cur_status', '17');
        $this->db->order_by('p.id', 'DESC');

        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('p.file_no', $data['file_no']);
        }
        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
            $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_to']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_form']);
            }
        }
        //print_r($this->db->last_query()); exit;
       
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function get_total_cnt_govt($data = array()) {

        $this->db->select('p.*')
                    ->from('gz_change_of_partnership_master p')
                    ->where('p.deleted', '0')
                    ->where('p.user_id', $this->session->userdata('user_id'))
                    ->where('p.gazette_type_id', '1')
                    ->where('p.press_publish', 1);

                    if (!empty($data['statusType'])) {
                        $this->db->like('p.cur_status', $data['statusType']);
                    }
                    if (!empty($data['file_no'])) {
                        $this->db->like('p.file_no', $data['file_no']);
                    }
                    
                    if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
                        $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                        $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                    } else {
                        if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                            
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                        } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                            
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
                        }
                    }
            
                    return $this->db->count_all_results();
    }

    public function paid_get_total_cnt_govt($data = array()) {

        $this->db->select('p.file_no, p.*,g.gazette_type,h.status_det,dd.pdf_for_notice_of_softcopy')
                    ->from('gz_change_of_partnership_master p')
                    ->join('gz_change_of_partnetship_doument_det dd', 'p.id = dd.gz_mas_type_id')
                    ->join('gz_gazette_type g', 'p.gazette_type_id = g.id')
                    ->join('gz_par_sur_status_master h', 'p.cur_status = h.id')
                    ->where('p.deleted', '0')
                    ->where('p.gazette_type_id', '1')
                    ->where_in('p.cur_status', array( "7", "18"));

                    if (!empty($data['file_no'])) {
                        $this->db->like('p.file_no', $data['file_no']);
                    }
                    
                    if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
                        $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                        $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                    } else {
                        if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                            
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                        } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                            
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
                        }
                    }

                    return $this->db->count_all_results();

                        
    }

    public function published_get_total_cnt_govt($data = array()) {

        $this->db->select('p.file_no,p.*,g.gazette_type,h.status_det,dd.pdf_for_notice_of_softcopy')
                    ->from('gz_change_of_partnership_master p')
                    ->join('gz_change_of_partnetship_doument_det dd', 'p.id = dd.gz_mas_type_id')
                    ->join('gz_gazette_type g', 'p.gazette_type_id = g.id')
                    ->join('gz_par_sur_status_master h', 'p.cur_status = h.id')
                    ->where('p.deleted', '0')
                    ->where('p.cur_status', '17')
                    ->where('p.gazette_type_id', '1')
                    ->where('p.press_publish', 1);

                    if (!empty($data['statusType'])) {
                        $this->db->like('p.cur_status', $data['statusType']);
                    }

                    if (!empty($data['file_no'])) {
                        $this->db->like('p.file_no', $data['file_no']);
                    }
                    
                    if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
                        $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                        $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                    } else {
                        if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                            
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                        } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                            
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
                        }
                    }
            
                    return $this->db->count_all_results();
    }

    /*
     * forward to pay to applicant
     */

    public function forward_to_pay($par_id) {
        $status = array(
            'gz_mas_type_id' => $par_id,
            'user_id' => $this->session->userdata('user_id'),
            'par_status' => 6,
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date("Y-m-d H:i:s", time()),
            'status' => 1,
            'deleted' => 0
        );

        $this->db->insert('gz_change_of_par_status_his', $status);



        $array_data = array(
            'cur_status' => 6
        );

        $this->db->where('id', $par_id);
        $this->db->update('gz_change_of_partnership_master', $array_data);


        $result = $this->db->select('*')
                ->from('gz_change_of_partnership_master')
                ->where('id', $par_id)
                ->get();

        $applicant_id = $result->row();
        
        $array_data_app = array(
            'master_id' => $par_id,
            'module_id' => 1,
            'user_id' => $this->session->userdata('user_id'),
            'responsible_user_id' => $applicant_id->user_id,
            'text' => 'Forward to Pay',
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s', time()),
            'status' => 1,
            'deleted' => 0
        );

        $this->db->insert('gz_notification_applicant', $array_data_app);

        $mobile = $this->db->select('u.mobile, g.file_no')
                        ->from('gz_applicants_details u')
                        ->join('gz_change_of_partnership_master g', 'u.id = g.user_id')
                        ->where('g.id', $par_id)
                        ->where('u.status', 1)
                        ->get()->row();

        // load SMS library will activate once live
        $this->load->library("cdac_sms");

        // message format
        $message = "Extraordinary Gazette File No. {$mobile->file_no} has been approved by the Govt. Press. Govt. of (StateName).";
        $sms_api = new Cdac_sms();
        // send SMS using API
        $template_id = "1007938090042852633";
        $sms_api->sendOtpSMS($message, $mobile, $template_id);

        return true;
    }

    public function amt_per_page() {

        return $this->db->select('pricing')
                        ->from('gz_modules_wise_pricing')
                        ->where('deleted', '0')
                        ->where('status', '1')
                        ->where('module_id', '1')
                        ->get()->row();
    }

    public function get_pdf_file($id) {
        return $this->db->select('pdf_for_notice_of_softcopy')
                        ->from('gz_change_of_partnetship_doument_det')
                        ->where('gz_mas_type_id', $id)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->get()->row();
    }

    /*
     * select status details
     */

    // public function get_cur_rem($id) {
    //     return $this->db->select('p.remark')
    //                     ->from('gz_change_of_par_status_his h ')
    //                     ->join('gz_par_partnership_chang_remark p', 'h.id = status_id')
    //                     ->where('h.par_status', $id)
    //                     ->get()->row();
    // }

    public function get_cur_rem($id) {
        return $this->db->select('p.remark')
                        ->from('gz_par_partnership_chang_remark p ')
                        ->join('gz_change_of_par_status_his h', 'h.par_status = p.status_history_id')
                        ->where('p.gz_master_id', $id)
                        ->get()->row();
    }

    /*
     * Change status of C&T verifier return
     */

    public function change_partnership_details($insert_array) {

        try {

            $this->db->trans_begin();

            $transaction_data = array(
                'par_id' => $insert_array['par_id'],
                'file_no' => $insert_array['file_number'],
                'deptRefId' => $insert_array['dept_ref_id'],
                'challanRefId' => $insert_array['challan_ref_id'],
                'amount' => $insert_array['amount'],
                'pay_mode' => $insert_array['pay_mode'],
                'bank_trans_id' => $insert_array['bank_trans_id'],
                'bank_name' => $insert_array['bank_name'],
                'bank_trans_msg' => $insert_array['bank_trans_msg'],
                'bank_trans_time' => $insert_array['bank_trans_time'],
                'bankTransactionStatus' => $insert_array['trans_status'],
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => 1,
                'deleted' => 0
            );

            $this->db->insert('gz_change_of_partnership_make_pay', $transaction_data);
            $last_id = $this->db->insert_id();

            if ($insert_array['trans_status'] == 'S') {

                $status = array(
                    'gz_mas_type_id' => $insert_array['par_id'],
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
                    'is_paid' => 1
                );

                $this->db->where('id', $insert_array['par_id']);
                $this->db->update('gz_change_of_partnership_master', $array_data);
            }

            // INSERT INTO the status history Table
            $insert_stat = array(
                'payment_id' => $last_id,
                // Change of Surname
                'payment_type' => 'COP',
                'payment_status' => $insert_array['trans_status'],
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_payment_status_history', $insert_stat);

            if ($insert_array['trans_status'] == 'S') {
                $admin = $this->db->from('gz_users')
                                    ->where('is_admin', '1')
                                    ->where('status', '1')
                                    ->get()->row();
                $notification_data_ct = array(
                    'master_id' => $insert_array['par_id'],
                    'module_id' => 1,
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
     * sign by govt press
     */

    public function govt_press_sign($par_id) {
        //echo $par_id ;exit();
        //        $status = array(
        //            'gz_mas_type_id' => $par_id,
        //            'user_id' => $this->session->userdata('user_id'),
        //            'par_status' => 8,
        //            'created_by' => $this->session->userdata('user_id'),
        //            'created_at' => date("Y-m-d H:i:s", time()),
        //            'status' => 1,
        //            'deleted' => 0
        //        );
        //
        //        $this->db->insert('gz_change_of_par_status_his', $status); 
        //        


        $array_data = array(
            'cur_status' => 8
        );

        $this->db->where('id', $par_id);
        $this->db->update('gz_change_of_partnership_master', $array_data);

        return true;
    }

    /*
     * govt publish pdf
     */

    public function govt_publish($par_id) {
        //echo $par_id ;exit();

        $status = array(
            'gz_mas_type_id' => $par_id,
            'user_id' => $this->session->userdata('user_id'),
            'par_status' => 17,
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date("Y-m-d H:i:s", time()),
            'status' => 1,
            'deleted' => 0
        );

        $this->db->insert('gz_change_of_par_status_his', $status);



        $array_data = array(
            'cur_status' => 17,
            'press_publish' => 1
        );

        $this->db->where('id', $par_id);
        $this->db->update('gz_change_of_partnership_master', $array_data);

        $array_data_app = array(
            'master_id' => $par_id,
            'module_id' => 1,
            'user_id' => $this->session->userdata('user_id'),
            'text' => 'Govt Press Publish',
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s', time()),
            'status' => 1,
            'deleted' => 0
        );

        $this->db->insert('gz_notification_applicant', $array_data_app);



        return true;
    }

    /*
     * UPDATE press signed PDF file path name
     */

    public function update_press_signed_pdf_path($data = array()) {
        // Update documents table



        $doc_data = array(
            'press_signed_pdf' => $data['press_signed_pdf_path'],
            'cur_status' => 8
        );

        $this->db->where('id', $data['gazette_id']);
        $this->db->update('gz_change_of_partnership_master', $doc_data);
        return ($this->db->affected_rows() == 1) ? true : false;
    }

    public function get_sl_no() {
        $sl_no = 0;
        $year = date("Y");
        $result = $this->db->select('*')->from('gz_change_of_partnership_master')->get();
        if ($result->num_rows() > 0) {
            $sl_no_data = $this->db->query('SELECT MAX(sl_no) AS sl_no FROM gz_change_of_partnership_master WHERE  YEAR(created_at) = ' . $year);
            $sl_no = @($sl_no_data->row()->sl_no + 1);
        } else {
            $sl_no = 1;
        }
        return $sl_no;
    }

    /*
     * select status details
     */

    public function get_file_type($id) {
        return $this->db->select('file_no')
                        ->from('gz_change_of_partnership_master')
                        ->where('id', $id)
                        ->get()->row();
    }

    // Dashboard recent COP gazette (Applicant)
    public function applicant_recent_cop_gazettes($user_id) {
        return $this->db->select('gz.id, gz.file_no, gz.created_at, st.status_det')
                        ->from('gz_change_of_partnership_master gz')
                        ->join('gz_par_sur_status_master st', 'gz.cur_status = st.id')
                        ->where('gz.user_id', $user_id)
                        ->group_by('gz.id')
                        ->limit(5)
                        ->get()->result();
    }

    // Dashboard recent COS gazette (Applicant)
    public function applicant_recent_cos_gazettes($user_id) {
        return $this->db->select('gz.id, gz.file_no, gz.created_at, st.status_name')
                        ->from('gz_change_of_name_surname_master gz')
                        ->join('gz_change_of_name_surname_status_master st', 'gz.current_status = st.id')
                        ->where('gz.user_id', $user_id)
                        ->group_by('gz.id')
                        ->limit(5)
                        ->get()->result();
    }

    /*
     * Dashboard data
     */

    public function get_count_of_total_submitted_gazette_dept($check, $module, $login) {
        if ($check == 'c&t') {

            $this->db->select('id');
            if ($module == 1) {
                
            } else if ($module == 2) {
                $this->db->from('gz_change_of_name_surname_master');

                if ($login == 'Processor') {

                    $this->db->where('current_status >=', 1);
                } else if ($login == 'Verifier') {

                    $this->db->where('current_status >=', 2);
                } else if ($login == 'Approver') {

                    $this->db->where('current_status >=', 2);
                }
            }
            $this->db->where('deleted', 0);
            return $this->db->count_all_results();
        } else if ($check == 'igr') {
            
        }
    }

    public function cos_published_gazettes_dept($check, $module, $login) {
        if ($check == 'c&t') {

            $this->db->select('id');
            if ($module == 1) {
                
            } else if ($module == 2) {
                $this->db->select('m.*, s.status_name, app.name AS applicant_name')
                                ->from('gz_change_of_name_surname_master m')
                                ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                                ->join('gz_applicants_details app', 'm.user_id = app.id')
                                ->where('m.current_status =', 11)
                                ->where('m.deleted', 0)
                                ->where('m.status', 1)
                                ->order_by('id', 'DESC');
                                
            }
            return $this->db->count_all_results();
            
        } else if ($check == 'igr') {
            
        }
    }

    public function cos_unpublished_gazettes_dept($check, $module, $login) {
        if ($check == 'c&t') {

            $this->db->select('id');
            if ($module == 1) {
                
            } else if ($module == 2) {
                $this->db->from('gz_change_of_name_surname_master');
                if ($login == 'Verifier') {
                    $this->db->where('current_status >=', 2);
                } else if ($login == 'Approver') {

                    $this->db->where('current_status >=', 2);
                }
            }
            $this->db->where('current_status !=', 11);
            $this->db->where('deleted', 0);
            return $this->db->count_all_results();
        } else if ($check == 'igr') {
            
        }
    }

    public function count_total_gazettes_poc_pending() {
        if ($this->session->userdata('is_verifier_approver') == 'Processor') {
            $status = [2, 5];
        } else if ($this->session->userdata('is_verifier_approver') == 'Verifier') {
            $status = [1, 2, 4, 7, 11, 14, 16, 5];
        } else if ($this->session->userdata('is_verifier_approver') == 'Approver') {
            $status = [1, 2, 4, 7, 8, 5];
        }
        $this->db->select('id');
        $this->db->from('gz_gazette');
        $this->db->where_not_in('status_id', $status);
        $this->db->where('is_paid', 1);
        return $this->db->count_all_results();
    }

    public function count_total_gazettes_poc_published() {
        $this->db->select('id');
        $this->db->from('gz_gazette');
        $this->db->where('status_id', 5);
        $this->db->where('is_paid', 1);
        return $this->db->count_all_results();
    }

    public function get_block_list($district_id) {
        $this->db->select('id, block_name')
                ->from('gz_block_master');
        $this->db->where('deleted', 0);
        $this->db->where('status', 1);
        $this->db->where('district_unique_id', $district_id);
        $this->db->order_by('block_name', 'ASC');
        return $this->db->get()->result();
    }

    public function get_ulb_list($district_id) {
        $this->db->select('id,ulb_name')
                ->from('gz_ulb_master');
        $this->db->where('deleted', 0);
        $this->db->where('status', 1);
        $this->db->where('district_unique_id', $district_id);
        $this->db->order_by('ulb_name', 'ASC');
        return $this->db->get()->result();
        //echo $this->db->last_query();exit();
    }

    /*
     * select notice details
     */

    public function select_notice_det($cons_id) {
        $this->db->select('*','date', '%d/%m/%Y')
                ->from('gz_con_surname_applicant_notice_details');
        $this->db->where('deleted', 0);
        $this->db->where('status', 1);
        $this->db->where('chnage_surname_id', $cons_id);
        return $this->db->get()->row();
        //echo $this->db->last_query();exit();
    }

    public function check_mobile_exists($mobile) {
        return $this->db->select('*')->from('gz_applicants_details')->where('mobile', $mobile)->where('deleted', 0)->get()->row();
    }

//Set offline status
    public function set_surname_offline_pay_status($record_id,$price)
    {
        $this->db->where('id', $record_id);
        $this->db->update('gz_change_of_name_surname_master', array('offline_pay_status'=>1,'total_price_to_paid'=>$price));
        if($this->db->affected_rows() > 0)
        {
            return true;
        }
        else{
            return false;
        }
    }
    public function check_surname_offline_pay_status($record_id)
    {
        $status = $this->db->select('offline_pay_status')->from('gz_change_of_name_surname_master')->where('id',$record_id)->get()->row();
        //echo $this->db->last_query();
        return $status;
    }

    public function set_partnership_pay_status($record_id,$price)
    {
        $this->db->where('id', $record_id);
        $this->db->update('gz_change_of_partnership_master', array('offline_pay_status'=>1,'total_price_to_paid'=>$price));
        if($this->db->affected_rows() > 0)
        {
            return true;
        }
        else{
            return false;
        }
    }

    public function check_partnership_offline_pay_status($record_id)
    {
        $status = $this->db->select('offline_pay_status')->from('gz_change_of_partnership_master')->where('id',$record_id)->get()->row();
        //echo $this->db->last_query();
        return $status;
    }

    public function check_department_offline_pay_status($record_id)
    {
        $status = $this->db->select('offline_pay_status')->from('gz_gazette')->where('id',$record_id)->get()->row();
        //echo $this->db->last_query();
        return $status;
    }

    public function set_department_off_pay_status($record_id,$price)
    {
        $this->db->where('id', $record_id);
        $this->db->update('gz_change_of_partnership_master', array('offline_pay_status'=>1,'total_price_to_paid'=>$price));
        if($this->db->affected_rows() > 0)
        {
            return true;
        }
        else{
            return false;
        }
    }

    // SMS LIMIT MODEL METHOD START
        public function increment_sms_request_count($mobile) {
            $current_count = $this->db->select('sms_request_count')
                                    ->where('mobile', $mobile)
                                    ->get('gz_applicants_details')
                                    ->row();
                                    //    echo $current_count->sms_request_count."----".$mobile;
            if ($current_count) {
                $new_count = $current_count->sms_request_count + 1;
                $this->db->where('mobile', $mobile)
                        ->update('gz_applicants_details', array('sms_request_count' => $new_count, 'last_sms_request_time' => date('Y-m-d H:i:s')));
            } else {
                return false;
            }
        }
        // Model method to get the blocked user
        public function get_blocked_user($mobile) {
            return $this->db->select('blocked_until')->where('mobile', $mobile)->get('gz_applicants_details')->row();
        }

        // Model method to reset SMS request count
        public function reset_sms_request_count($mobile) {
            $this->db->where('mobile', $mobile)->update('gz_applicants_details', array('sms_request_count' => 1));
        }


        public function get_sms_request_count($mobile) {
            $this->db->select('sms_request_count');
            $this->db->where('mobile', $mobile);
            $query = $this->db->get('gz_applicants_details');
            $result = $query->row();
            return ($result) ? $result->sms_request_count : 0;
        }

        public function is_user_blocked($mobile) {
            $this->db->select('blocked_until');
            $this->db->where('mobile', $mobile);
            $query = $this->db->get('gz_applicants_details');
            $result = $query->row();
            return ($result && strtotime($result->blocked_until) > time());
        }

        public function block_user($mobile, $blocked_until) {
            $data = array(
                'blocked_until' => $blocked_until
            );
            $this->db->where('mobile', $mobile);
            $this->db->update('gz_applicants_details', $data);
        }
    // SMS LIMIT MODEL METHOD END

    /*
     * Save transaction status for change of name surname scroll start
     * put the below function in Applicants_login_model
     */
    public function save_change_name_surname_trans_status_scroll($insert_array) {
        echo 'model response<br>'; // testing declaration only
        try {

            $this->db->trans_begin();

            $transaction_data = array(
                'change_name_surname_id' => $insert_array['change_name_surname_id'],
                'file_number' => $insert_array['file_number'],
                'dept_ref_id' => $insert_array['dept_ref_id'],
                'challan_ref_id' => $insert_array['challan_ref_id'],
                'amount' => $insert_array['amount'],
                'pay_mode' => $insert_array['pay_mode'],
                'bank_trans_id' => $insert_array['bank_trans_id'],
                'bank_name' => $insert_array['bank_name'],
                'bank_trans_msg' => $insert_array['bank_trans_msg'],
                'bank_trans_time' => $insert_array['bank_trans_time'],
                'trans_status' => $insert_array['trans_status'],
                // 'user_id' => $insert_array['user_id'], // testing declaration only
                'created_at' => date('Y-m-d H:i:s', time())
            );
            // print_r($transaction_data);
            // exit;
            $this->db->insert('gz_change_of_name_surname_payment_details', $transaction_data);
            $last_id = $this->db->insert_id();

            // INSERT INTO the status history Table
            $insert_stat = array(
                'payment_id' => $last_id,
                // Change of Surname
                'payment_type' => 'COS',
                'payment_status' => $insert_array['trans_status'],
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_payment_status_history', $insert_stat);

            $status = 9;

            if ($insert_array['trans_status'] == 'S') {

                $status = 10;
                $admin = $this->db->from('gz_users')
                                    ->where('is_admin', '1')
                                    ->where('status', '1')
                                    ->get()->row();
                
                $notification_data_ct = array(
                    'master_id' => $insert_array['change_name_surname_id'],
                    'module_id' => 2,
                    'user_id' => $insert_array['user_id'],
                    'responsible_user_id' => $admin->id,
                    'text' => "Payment Successful",
                    'is_viewed' => 0,
                    'created_by' => $insert_array['user_id'],
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_govt', $notification_data_ct);
            } else if ($insert_array['trans_status'] == 'F') {

                $status = 16;
            } else if ($insert_array['trans_status'] == 'P') {

                $status = 15;
            }

            $master_data = array(
                'current_status' => $status,
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $insert_array['user_id']
            );


            $this->db->where('id', $insert_array['change_name_surname_id']);
            $this->db->update('gz_change_of_name_surname_master', $master_data);

            if ($status != 9) {
                $status_history = array(
                    'gz_master_id' => $insert_array['change_name_surname_id'],
                    'change_of_name_surname_status' => $status,
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'created_by' => $insert_array['user_id']
                );
                $this->db->insert('gz_change_of_name_surname_status_his', $status_history);
            }

            if ($this->db->trans_status() == FALSE) {
                $this->db->trans_rollback();
                return FALSE;
            } else {
                $this->db->trans_commit();
                return TRUE;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    /*
     * Save transaction status for change of name surname scroll end
     */

    


     /*
     * Save transaction status for change of partnership scroll start
     * put the below function in Applicants_login_model
     */
    public function change_partnership_details_scroll($insert_array) {

        try {

            $this->db->trans_begin();

            $transaction_data = array(
                'par_id' => $insert_array['par_id'],
                'file_no' => $insert_array['file_number'],
                'deptRefId' => $insert_array['dept_ref_id'],
                'challanRefId' => $insert_array['challan_ref_id'],
                'amount' => $insert_array['amount'],
                'pay_mode' => $insert_array['pay_mode'],
                'bank_trans_id' => $insert_array['bank_trans_id'],
                'bank_name' => $insert_array['bank_name'],
                'bank_trans_msg' => $insert_array['bank_trans_msg'],
                'bank_trans_time' => $insert_array['bank_trans_time'],
                'bankTransactionStatus' => $insert_array['trans_status'],
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => 1,
                'deleted' => 0
            );

            $this->db->insert('gz_change_of_partnership_make_pay', $transaction_data);
            $last_id = $this->db->insert_id();

            if ($insert_array['trans_status'] == 'S') {

                $status = array(
                    'gz_mas_type_id' => $insert_array['par_id'],
                    'user_id' => $insert_array['user_id'],
                    'par_status' => 7,
                    'created_by' => $insert_array['user_id'],
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_change_of_par_status_his', $status);

                $array_data = array(
                    'cur_status' => 7,
                    'is_paid' => 1
                );

                $this->db->where('id', $insert_array['par_id']);
                $this->db->update('gz_change_of_partnership_master', $array_data);
            }

            // INSERT INTO the status history Table
            $insert_stat = array(
                'payment_id' => $last_id,
                // Change of Surname
                'payment_type' => 'COP',
                'payment_status' => $insert_array['trans_status'],
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->insert('gz_payment_status_history', $insert_stat);

            if ($insert_array['trans_status'] == 'S') {
                $admin = $this->db->from('gz_users')
                                    ->where('is_admin', '1')
                                    ->where('status', '1')
                                    ->get()->row();
                $notification_data_ct = array(
                    'master_id' => $insert_array['par_id'],
                    'module_id' => 1,
                    'user_id' => $insert_array['user_id'],
                    'responsible_user_id' => $admin->id,
                    'text' => "Payment Successful",
                    'is_viewed' => 0,
                    'created_by' => $insert_array['user_id'],
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_govt', $notification_data_ct);
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
     * Save transaction status for change of partnership scroll end
     */


     // Gender Model Part Start

     public function current_gender($id){
        return $this->db->select('current_status')
                        ->from('gz_change_of_gender_master')
                        ->where('id',$id)
                        ->get()->row();          
    }

    public function get_image_link_gender($id) {
        return $this->db->select('*')
                        ->from('gz_change_of_gender_document_det')
                        ->where('gz_master_id', $id)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->get()->row();
    }

    public function get_pdf_gender($master_id, $id) {
        $data = $this->db->select('document_name')
                        ->from('gz_change_of_gender_history')
                        ->where('gz_master_id', $master_id)
                        ->where('id', $id)
                        ->where('gz_docu_id', 10)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->get()->row();
        $link = "";
        if (!empty($data)) {
            $link = $data->document_name;
        }
        return $link;
    }

    public function get_remarks_on_change_of_gender($id, $status) {
        $data = $this->db->select('remarks')
                        ->from('gz_change_of_gender_remarks_master')
                        ->where('change_of_gender_id', $id)
                        ->where('status_id', $status)
                        ->order_by('id', 'DESC')
                        ->limit(1)
                        ->get()->row();
        $remarks = "";

        if (!empty($data)) {
            $remarks = $data->remarks;
        }
        return $remarks;
    }

    //  Gender Work for Scroll
        public function save_change_gender_trans_status_scroll($insert_array) {
            echo 'model response<br>'; // testing declaration only
            try {

                $this->db->trans_begin();

                $transaction_data = array(
                    'change_gender_id' => $insert_array['change_gender_id'],
                    'file_number' => $insert_array['file_number'],
                    'dept_ref_id' => $insert_array['dept_ref_id'],
                    'challan_ref_id' => $insert_array['challan_ref_id'],
                    'amount' => $insert_array['amount'],
                    'pay_mode' => $insert_array['pay_mode'],
                    'bank_trans_id' => $insert_array['bank_trans_id'],
                    'bank_name' => $insert_array['bank_name'],
                    'bank_trans_msg' => $insert_array['bank_trans_msg'],
                    'bank_trans_time' => $insert_array['bank_trans_time'],
                    'trans_status' => $insert_array['trans_status'],
                    // 'user_id' => $insert_array['user_id'], // testing declaration only
                    'created_at' => date('Y-m-d H:i:s', time())
                );
                // print_r($transaction_data);
                // exit;
                $this->db->insert('gz_change_of_gender_payment_details', $transaction_data);
                $last_id = $this->db->insert_id();

                // INSERT INTO the status history Table
                $insert_stat = array(
                    'payment_id' => $last_id,
                    // Change of Surname
                    'payment_type' => 'COG',
                    'payment_status' => $insert_array['trans_status'],
                    'created_at' => date('Y-m-d H:i:s', time())
                );

                $this->db->insert('gz_payment_status_history', $insert_stat);

                $status = 9;

                if ($insert_array['trans_status'] == 'S') {

                    $status = 10;
                    $admin = $this->db->from('gz_users')
                                        ->where('is_admin', '1')
                                        ->where('status', '1')
                                        ->get()->row();
                    
                    $notification_data_ct = array(
                        'master_id' => $insert_array['change_gender_id'],
                        'module_id' => 6,
                        'user_id' => $insert_array['user_id'],
                        'responsible_user_id' => $admin->id,
                        'text' => "Payment Successful",
                        'is_viewed' => 0,
                        'created_by' => $insert_array['user_id'],
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );

                    $this->db->insert('gz_notification_govt', $notification_data_ct);
                } else if ($insert_array['trans_status'] == 'F') {

                    $status = 16;
                } else if ($insert_array['trans_status'] == 'P') {

                    $status = 15;
                }

                $master_data = array(
                    'current_status' => $status,
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'modified_by' => $insert_array['user_id']
                );


                $this->db->where('id', $insert_array['change_gender_id']);
                $this->db->update('gz_change_of_gender_master', $master_data);

                if ($status != 9) {
                    $status_history = array(
                        'gz_master_id' => $insert_array['change_gender_id'],
                        'change_of_gender_status' => $status,
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'created_by' => $insert_array['user_id']
                    );
                    $this->db->insert('gz_change_of_gender_status_his', $status_history);
                }

                if ($this->db->trans_status() == FALSE) {
                    $this->db->trans_rollback();
                    return FALSE;
                } else {
                    $this->db->trans_commit();
                    return TRUE;
                }
            } catch (Exception $e) {
                return FALSE;
            }
        }

        public function get_count_of_total_COG_submitted_gazette_dept($check, $module, $login) {
            if ($check == 'c&t') {

                $this->db->select('id');
                if ($module == 1) {
                    
                } else if ($module == 2) {
                    
                } else if ($module == 6) {
                    $this->db->from('gz_change_of_gender_master');

                    if ($login == 'Processor') {

                        $this->db->where('current_status >=', 1);
                    } else if ($login == 'Verifier') {

                        $this->db->where('current_status >=', 2);
                    } else if ($login == 'Approver') {

                        $this->db->where('current_status >=', 2);
                    }
                }
                $this->db->where('deleted', 0);
                return $this->db->count_all_results();
            } else if ($check == 'igr') {
                
            }
        }

        public function cog_published_gazettes_dept($check, $module, $login) {
            if ($check == 'c&t') {

                $this->db->select('id');
                if ($module == 1) {
                    
                } else if ($module == 2) {
                                    
                } else if ($module == 6) {
                    $this->db->select('m.*, s.status_name, app.name AS applicant_name')
                                    ->from('gz_change_of_gender_master m')
                                    ->join('gz_change_of_gender_status_master s', 's.id = m.current_status')
                                    ->join('gz_applicants_details app', 'm.user_id = app.id')
                                    ->where('m.current_status =', 11)
                                    ->where('m.deleted', 0)
                                    ->where('m.status', 1)
                                    ->order_by('id', 'DESC');
                                    
                }
                return $this->db->count_all_results();
                
            } else if ($check == 'igr') {
                
            }
        }

        public function cog_unpublished_gazettes_dept($check, $module, $login) {
            if ($check == 'c&t') {

                $this->db->select('id');
                if ($module == 1) {
                    
                } else if ($module == 2) {
                    
                } else if ($module == 6) {
                    $this->db->from('gz_change_of_gender_master');
                    if ($login == 'Verifier') {
                        $this->db->where('current_status >=', 2);
                    } else if ($login == 'Approver') {

                        $this->db->where('current_status >=', 2);
                    }
                }
                $this->db->where('current_status !=', 11);
                $this->db->where('deleted', 0);
                return $this->db->count_all_results();
            } else if ($check == 'igr') {
                
            }
        }

        // gender unpublished
        public function cog_unpublished_gazettes($user_id) {

            return $this->db->select('id')
                            ->from('gz_change_of_gender_master')
                            ->where('user_id', $user_id)
                            ->where('is_published', 0)
                            ->where('deleted', 0)
                            ->where('status', 1)
                            ->count_all_results();
        }

        // gender published
        public function cog_published_gazettes($user_id) {
            return $this->db->select('id')
                            ->from('gz_change_of_gender_master')
                            ->where('user_id', $user_id)
                            ->where('is_published', 1)
                            ->where('deleted', 0)
                            ->where('status', 1)
                            ->count_all_results();
        }
        /*
        * Get total documents for change of gender
        */

        public function get_total_gender_documents() {
            return $this->db->select('id, document_name')
                            ->from('gz_change_of_gender_document_master')
                            ->where('deleted', 0)
                            ->order_by('id', 'ASC')
                            ->get()->result();
        }

        /*
        * Insert Change of gender
        */

        public function insert_change_of_gender($insert_array){
            // echo 'test model';
            $processerID;
            try {
                $str = explode('_', $insert_array['block_ulb_id']);

                $this->db->trans_begin();

                // Master Data Part 1 insert into gz_change_of_gender_master
                $master_data = array(
                    'gazette_type_id' => $insert_array['gazette_type'],
                    'state_id' => $insert_array['state_id'],
                    'district_id' => $insert_array['district_id'],
                    'type' => $str[0],
                    'block_ulb_id' => $str[1],
                    'address_1' => $insert_array['address_1'],
                    'pin_code' => $insert_array['pin_code'],
                    'user_id' => $this->session->userdata('user_id'),
                    'file_no' => $insert_array['file_no'],
                    'govt_employee' => $insert_array['govt_emp'],
                    'is_minor' => $insert_array['minor'],
                    'is_name_change' => $insert_array['name_change'],
                    'notice_softcopy_doc' => $insert_array['press_word_db_path'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'current_status' => 1,
                    'status' => 1,
                    'deleted' => 0
                );
                // echo 'Master Data - Part-1 <br>';
                // echo '<pre>';
                // print_r($master_data);
                // exit;

                $this->db->insert('gz_change_of_gender_master', $master_data); // uncomment this line after testing

                $master_id = $this->db->insert_id(); // uncomment this line after testing

                // Master Data Part 2 insert into gz_change_of_applicant_gender_notice_details
                // need to update the new notice fields in here....
                $master_data = array(
                    'change_gender_id' => $master_id, // dynamic // uncomment this line after testing
                    // 'change_gender_id' => 1, // static
                    'approver' => $insert_array['approver'],
                    'place' => $insert_array['place'],
                    'date' => $insert_array['notice_date'],
                    'salutation' => $insert_array['salutation'],
                    'name_for_notice' => $insert_array['name_for_notice'],
                    'address' => $insert_array['address'],
                    'son_daughter' => $insert_array['son_daughter'] ?? null,
                    'gender' => $insert_array['gender'] ?? null,
                    
                    // need to create this column name in table.. I added it on 18-june-2024
                    'gender_his_her' => $insert_array['gender_his_her'] ?? null,

                    'old_gender_1' => $insert_array['old_gender'] ?? null,
                    'new_gender' => $insert_array['new_gender'] ?? null,
                    'new_gender_1' => $insert_array['new_gender_one'] ?? null,

                    'old_name_2' => $insert_array['old_name_two'] ?? null,
                    'new_name_2' => $insert_array['new_name_two'] ?? null,
                    'old_gender_2' => $insert_array['old_gender_two'] ?? null,
                    'new_gender_2' => $insert_array['new_gender_two'] ?? null,
                    'new_name_3' => $insert_array['new_name_three'] ?? null,
                    'new_gender_3' => $insert_array['new_gender_three'] ?? null,
                    

                    'signature' => $insert_array['signature'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );

                // echo '<br>Master Data - Part-2';
                // echo '<pre>';
                // print_r($master_data);
                // exit;

                //echo '<pre>';print_r($master_data);exit();
                $this->db->insert('gz_change_of_applicant_gender_notice_details', $master_data);

                $document_data = array(
                    'gz_master_id' => $master_id,

                    'affidavit' => $insert_array['affidavit'],
                    'original_newspaper' => $insert_array['original_newspaper'],
                    'notice_in_softcopy' => $insert_array['notice'],
                    'medical_cert' => $insert_array['medical_cert'],
                    'id_proof_doc' => $insert_array['id_proof_doc'],
                    'address_proof_doc' => $insert_array['address_proof_doc'],
                    'deed_changing_form' => $insert_array['deed_changing_form'],
                    'age_proof' => $insert_array['age_proof'],
                    'approval_authority' => $insert_array['approval_auth_doc'],
                    'notice_softcopy_pdf' => $insert_array['pdf_path'],

                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );

                // echo '<pre>';
                // print_r($document_data);
                // exit;

                // print_r($document_data);exit;
                $this->db->insert('gz_change_of_gender_document_det', $document_data);

                //  1.  affidavit data insert to gz_change_of_gender_history
                $data1 = array(
                    'gz_master_id' => $master_id,
                    'gz_docu_id' => 1,
                    'document_name' => $insert_array['affidavit'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_gender_history', $data1);

                // 2.   Original News Paper data insert to gz_change_of_gender_history
                $data2 = array(
                    'gz_master_id' => $master_id,
                    'gz_docu_id' => 2,
                    'document_name' => $insert_array['original_newspaper'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_gender_history', $data2);
                // 3.   notice in softcopy data insert to gz_change_of_gender_history
                $data3 = array(
                    'gz_master_id' => $master_id,
                    'gz_docu_id' => 3,
                    'document_name' => $insert_array['notice'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_gender_history', $data3);
                // 4.   Medical Certificate data insert to gz_change_of_gender_history
                $data4 = array(
                    'gz_master_id' => $master_id,
                    'gz_docu_id' => 4,
                    'document_name' => $insert_array['medical_cert'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_gender_history', $data4);
                // 5.   ID Proof data insert to gz_change_of_gender_history
                $data5 = array(
                    'gz_master_id' => $master_id,
                    'gz_docu_id' => 5,
                    'document_name' => $insert_array['id_proof_doc'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_gender_history', $data5);
                // 6.   Address Proof data insert to gz_change_of_gender_history
                $data6 = array(
                    'gz_master_id' => $master_id,
                    'gz_docu_id' => 6,
                    'document_name' => $insert_array['address_proof_doc'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_gender_history', $data6);
                // 7.   Deed Chaning form data insert to gz_change_of_gender_history
                if (!empty($insert_array['deed_changing_form'])) {
                    $data7 = array(
                        'gz_master_id' => $master_id,
                        'gz_docu_id' => 7,
                        'document_name' => $insert_array['deed_changing_form'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data7);
                }
                // 8.   Age Proof data insert to gz_change_of_gender_history
                if (!empty($insert_array['age_proof'])) {
                    $data8 = array(
                        'gz_master_id' => $master_id,
                        'gz_docu_id' => 8,
                        'document_name' => $insert_array['age_proof'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data8);
                }
                // 9.   Approval Authority data insert to gz_change_of_gender_history
                if (!empty($insert_array['approval_auth_doc'])) {
                    $data9 = array(
                        'gz_master_id' => $master_id,
                        'gz_docu_id' => 9,
                        'document_name' => $insert_array['approval_auth_doc'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data9);
                }

                // 10.  PDF PATH
                $data10 = array(
                    'gz_master_id' => $master_id,
                    'gz_docu_id' => 10,
                    'document_name' => $insert_array['pdf_path'],
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_gender_history', $data10);

                $status = array(
                    'gz_master_id' => $master_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'change_of_gender_status' => 1,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_change_of_gender_status_his', $status);

                $processers = $this->db->from('gz_c_and_t')
                                    ->where('verify_approve', 'Processor')    
                                    ->where('module_id', 2)
                                    ->get();
                foreach($processers->result() as $processer){
                    $processerID = $processer->id;
                }

                $notification_data = array(
                    'master_id' => $master_id,
                    'module_id' => $this->session->userdata('module_id'),
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $processerID,
                    'text' => "Change of Gender request arrived",
                    'is_viewed' => 0,
                    'pro_ver_app' => 1,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );

                $this->db->insert('gz_notification_ct', $notification_data);

                if ($this->db->trans_status() == FALSE) {
                    $this->db->trans_rollback();
                    return FALSE;
                } else {
                    $this->db->trans_commit();
                    return TRUE;
                }
                //try block end
            } catch (Exception $ex) {
                return FALSE;
            }
        }

        /*
        * Edit change of gender
        */
        public function edit_change_of_gender($data, $status) {
            
            if($status == 22){
                try {
    
                    $str = explode('_', $data['block_ulb_id']);
    
                    $this->db->trans_begin();
    
                    $update_master = array(
                        'state_id' => $data['state_id'],
                        'district_id' => $data['district_id'],
                        'type' => $str[0],
                        'block_ulb_id' => $str[1],
                        'address_1' => $data['address_1'],
                        'pin_code' => $data['pin_code'],
                        'current_status' => 18,
                        'modified_at' => date("Y-m-d H:i:s", time()),
                        'modified_by' => $this->session->userdata('user_id')
                    );
                    
                    $this->db->where('id', $data['id']);
                    $this->db->update('gz_change_of_gender_master', $update_master);
    
    
                    $master_data = array(
                        'approver' => $data['approver'],
                        'place' => $data['place'],
                        'date' => $data['notice_date'],
                        'salutation' => $data['salutation'],
                        'name_for_notice' => $data['name_for_notice'],
                        'address' => $data['address'],
                        'son_daughter' => $data['son_daughter'] ?? null ,
                        'gender' => $data['gender'] ?? null,
                        
                        'gender_his_her' => $data['gender_his_her'] ?? null,
    
                        'old_gender_1' => $data['old_gender'] ?? null,
                        'new_gender' => $data['new_gender'] ?? null,
                        'new_gender_1' => $data['new_gender_one'] ?? null,
    
                        'old_name_2' => $data['old_name_two'] ?? null,
                        'new_name_2' => $data['new_name_two'] ?? null,
                        'old_gender_2' => $data['old_gender_two'] ?? null,
                        'new_gender_2' => $data['new_gender_two'] ?? null,
                        'new_name_3' => $data['new_name_three'] ?? null,
                        'new_gender_3' => $data['new_gender_three'] ?? null,
                        
    
                        'signature' => $data['signature'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
    
                    //echo '<pre>';print_r($master_data);exit();
                    $this->db->where('change_gender_id', $data['id']);
                    $this->db->update('gz_change_of_applicant_gender_notice_details', $master_data);
    
                    $update_document_data = array(
                        'affidavit' => $data['affidavit'],
                        'original_newspaper' => $data['original_newspaper'],
                        'notice_in_softcopy' => $data['notice'],
                        'medical_cert' => $data['medical_cert'],
                        'id_proof_doc' => $data['id_proof_doc'],
                        'address_proof_doc' => $data['address_proof_doc'],
                        'deed_changing_form' => $data['deed_changing_form'],
                        'age_proof' => $data['age_proof'],
                        'approval_authority' => $data['approval_authority'],
                        'notice_softcopy_pdf' => $data['notice_softcopy_pdf'],
                        'modified_at' => $this->session->userdata('user_id'),
                        'modified_by' => date("Y-m-d H:i:s", time()),
                    );
                    // echo '<pre>';print_r($update_document_data);exit();
                    $this->db->where('gz_master_id', $data['id']);
                    $this->db->update('gz_change_of_gender_document_det', $update_document_data);
    
                    $data1 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 1,
                        'document_name' => $data['affidavit'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                //    echo '<pre>';print_r($data1);exit();
                    $this->db->insert('gz_change_of_gender_history', $data1);
    
                    $data2 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 2,
                        'document_name' => $data['original_newspaper'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                //    echo '<pre>';print_r($data2);exit();
                    $this->db->insert('gz_change_of_gender_history', $data2);
    
                    $data3 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 3,
                        'document_name' => $data['notice'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                //    echo '<pre>';print_r($data3);exit(); // null
                    $this->db->insert('gz_change_of_gender_history', $data3);
    
                    $data4 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 4,
                        'document_name' => $data['medical_cert'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                //    echo '<pre>';print_r($data4);exit();
                    $this->db->insert('gz_change_of_gender_history', $data4);
    
                    $data5 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 5,
                        'document_name' => $data['id_proof_doc'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data5);
    
                    $data6 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 6,
                        'document_name' => $data['address_proof_doc'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data6);
    
                
                    if (!empty($data['deed_changing_form'])) {
                        $data7 = array(
                            'gz_master_id' => $data['id'],
                            'gz_docu_id' => 7,
                            'document_name' => $data['deed_changing_form'],
                            'created_by' => $this->session->userdata('user_id'),
                            'created_at' => date("Y-m-d H:i:s", time()),
                            'status' => 1,
                            'deleted' => 0
                        );
                        $this->db->insert('gz_change_of_gender_history', $data7);
                    }
    
                    if (!empty($data['age_proof'])) {
                        $data8 = array(
                            'gz_master_id' => $data['id'],
                            'gz_docu_id' => 8,
                            'document_name' => $data['age_proof'],
                            'created_by' => $this->session->userdata('user_id'),
                            'created_at' => date("Y-m-d H:i:s", time()),
                            'status' => 1,
                            'deleted' => 0
                        );
                        $this->db->insert('gz_change_of_gender_history', $data8);
                    }
    
                    if (!empty($data['approval_authority'])) {
                        $data9 = array(
                            'gz_master_id' => $data['id'],
                            'gz_docu_id' => 9,
                            'document_name' => $data['approval_authority'],
                            'created_by' => $this->session->userdata('user_id'),
                            'created_at' => date("Y-m-d H:i:s", time()),
                            'status' => 1,
                            'deleted' => 0
                        );
                        $this->db->insert('gz_change_of_gender_history', $data9);
                    }
    
                    
    
                    $data10 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 10,
                        'document_name' => $data['notice_softcopy_pdf'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data10);
    
                    $status = array(
                        'gz_master_id' => $data['id'],
                        'user_id' => $this->session->userdata('user_id'),
                        'change_of_gender_status' => 18,
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_status_his', $status);
    
                    $processers = $this->db->from('gz_c_and_t')
                                    ->where('verify_approve', 'Processor')    
                                    ->where('module_id', 6)
                                    ->get();
                    foreach($processers->result() as $processer){
                        $processerID = $processer->id;
                    }
    
                    $notification_data = array(
                        'master_id' => $data['id'],
                        'module_id' => 6,
                        'user_id' => $this->session->userdata('user_id'),
                        'responsible_user_id' => $processerID,
                        'text' => "Change of gender resubmitted successfully",
                        'is_viewed' => 0,
                        'pro_ver_app' => 1,
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_notification_ct', $notification_data);
    
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
            else if ($status == 23){
                try {
    
                    $str = explode('_', $data['block_ulb_id']);
    
                    $this->db->trans_begin();
    
                    $update_master = array(
                        'state_id' => $data['state_id'],
                        'district_id' => $data['district_id'],
                        'type' => $str[0],
                        'block_ulb_id' => $str[1],
                        'address_1' => $data['address_1'],
                        'pin_code' => $data['pin_code'],
                        'current_status' => 19,
                        'modified_at' => date("Y-m-d H:i:s", time()),
                        'modified_by' => $this->session->userdata('user_id')
                    );
                    $this->db->where('id', $data['id']);
                    $this->db->update('gz_change_of_gender_master', $update_master);
    
    
                    $master_data = array(
                        'approver' => $data['approver'],
                        'place' => $data['place'],
                        'date' => $data['notice_date'],
                        'salutation' => $data['salutation'],
                        'name_for_notice' => $data['name_for_notice'],
                        'address' => $data['address'],
                        'son_daughter' => $data['son_daughter'],
                        'gender' => $data['gender'],
                        
                        'gender_his_her' => $data['gender_his_her'],
    
                        'old_gender_1' => $data['old_gender'],
                        'new_gender' => $data['new_gender'],
                        'new_gender_1' => $data['new_gender_one'],
    
                        'old_name_2' => $data['old_name_two'],
                        'new_name_2' => $data['new_name_two'],
                        'old_gender_2' => $data['old_gender_two'],
                        'new_gender_2' => $data['new_gender_two'],
                        'new_name_3' => $data['new_name_three'],
                        'new_gender_3' => $data['new_gender_three'],
                        
    
                        'signature' => $data['signature'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
    
                    //echo '<pre>';print_r($master_data);exit();
                    $this->db->where('change_gender_id', $data['id']);
                    $this->db->update('gz_change_of_applicant_gender_notice_details', $master_data);
    
                    $update_document_data = array(
                        'affidavit' => $data['affidavit'],
                        'original_newspaper' => $data['original_newspaper'],
                        'notice_in_softcopy' => $data['notice'],
                        'medical_cert' => $data['medical_cert'],
                        'id_proof_doc' => $data['id_proof_doc'],
                        'address_proof_doc' => $data['address_proof_doc'],
                        'deed_changing_form' => $data['deed_changing_form'],
                        'age_proof' => $data['age_proof'],
                        'approval_authority' => $data['approval_authority'],
                        'notice_softcopy_pdf' => $data['notice_softcopy_pdf'],
                        'modified_at' => $this->session->userdata('user_id'),
                        'modified_by' => date("Y-m-d H:i:s", time()),
                    );
                    $this->db->where('gz_master_id', $data['id']);
                    $this->db->update('gz_change_of_gender_document_det', $update_document_data);
    
                    $data1 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 1,
                        'document_name' => $data['affidavit'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data1);
                    // echo '<pre>';print_r($data1);exit;
                    $data2 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 2,
                        'document_name' => $data['original_newspaper'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data2);
                    // echo 'data2 - <pre>';print_r($data2);
                    $data3 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 3,
                        'document_name' => $data['notice'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data3);
                    // echo 'data3 - <pre>';print_r($data3);
                    $data4 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 4,
                        'document_name' => $data['medical_cert'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data4);
                    // echo 'data4 - <pre>';print_r($data4);
                    $data5 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 5,
                        'document_name' => $data['id_proof_doc'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data5);
                    // echo 'data5 - <pre>';print_r($data5);
                    $data6 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 6,
                        'document_name' => $data['address_proof_doc'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data6);
                    // echo 'data6 - <pre>';print_r($data6);exit;
                
                    if (!empty($data['deed_changing_form'])) {
                        $data7 = array(
                            'gz_master_id' => $data['id'],
                            'gz_docu_id' => 7,
                            'document_name' => $data['deed_changing_form'],
                            'created_by' => $this->session->userdata('user_id'),
                            'created_at' => date("Y-m-d H:i:s", time()),
                            'status' => 1,
                            'deleted' => 0
                        );
                        $this->db->insert('gz_change_of_gender_history', $data7);
                    }
                    // echo 'data7 - <pre>';print_r($data7);exit;
                    if (!empty($data['age_proof'])) {
                        $data8 = array(
                            'gz_master_id' => $data['id'],
                            'gz_docu_id' => 8,
                            'document_name' => $data['age_proof'],
                            'created_by' => $this->session->userdata('user_id'),
                            'created_at' => date("Y-m-d H:i:s", time()),
                            'status' => 1,
                            'deleted' => 0
                        );
                        $this->db->insert('gz_change_of_gender_history', $data8);
                    }
    
                    if (!empty($data['approval_authority'])) {
                        $data9 = array(
                            'gz_master_id' => $data['id'],
                            'gz_docu_id' => 9,
                            'document_name' => $data['approval_authority'],
                            'created_by' => $this->session->userdata('user_id'),
                            'created_at' => date("Y-m-d H:i:s", time()),
                            'status' => 1,
                            'deleted' => 0
                        );
                        $this->db->insert('gz_change_of_gender_history', $data9);
                    }
    
                    
    
                    $data10 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 10,
                        'document_name' => $data['notice_softcopy_pdf'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data10);
                    // echo 'data10 - <pre>';print_r($data10);exit;
                    $status = array(
                        'gz_master_id' => $data['id'],
                        'user_id' => $this->session->userdata('user_id'),
                        'change_of_gender_status' => 19,
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_status_his', $status);
    
                    $verifiers = $this->db->from('gz_c_and_t')
                                    ->where('verify_approve', 'Verifier')    
                                    ->where('module_id', 6)
                                    ->get();
                    foreach($verifiers->result() as $verifier){
                        $verifierID = $verifier->id;
                    }
    
                    $notification_data = array(
                        'master_id' => $data['id'],
                        'module_id' => 6,
                        'user_id' => $this->session->userdata('user_id'),
                        'responsible_user_id' => $verifierID,
                        'text' => "Change of gender resubmitted successfully",
                        'is_viewed' => 0,
                        'pro_ver_app' => 1,
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_notification_ct', $notification_data);
    
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
            else if ($status == 24){
                try {
    
                    $str = explode('_', $data['block_ulb_id']);
    
                    $this->db->trans_begin();
    
                    $update_master = array(
                        'state_id' => $data['state_id'],
                        'district_id' => $data['district_id'],
                        'type' => $str[0],
                        'block_ulb_id' => $str[1],
                        'address_1' => $data['address_1'],
                        'pin_code' => $data['pin_code'],
                        'current_status' => 20,
                        'modified_at' => date("Y-m-d H:i:s", time()),
                        'modified_by' => $this->session->userdata('user_id')
                    );
                    $this->db->where('id', $data['id']);
                    $this->db->update('gz_change_of_gender_master', $update_master);
    
    
                    $master_data = array(
                        'approver' => $data['approver'],
                        'place' => $data['place'],
                        'date' => $data['notice_date'],
                        'salutation' => $data['salutation'],
                        'name_for_notice' => $data['name_for_notice'],
                        'address' => $data['address'],
                        'son_daughter' => $data['son_daughter'],
                        'gender' => $data['gender'],
                        
                        'gender_his_her' => $data['gender_his_her'],
    
                        'old_gender_1' => $data['old_gender'],
                        'new_gender' => $data['new_gender'],
                        'new_gender_1' => $data['new_gender_one'],
    
                        'old_name_2' => $data['old_name_two'],
                        'new_name_2' => $data['new_name_two'],
                        'old_gender_2' => $data['old_gender_two'],
                        'new_gender_2' => $data['new_gender_two'],
                        'new_name_3' => $data['new_name_three'],
                        'new_gender_3' => $data['new_gender_three'],
                        
    
                        'signature' => $data['signature'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
    
                    //echo '<pre>';print_r($master_data);exit();
                    $this->db->where('change_gender_id', $data['id']);
                    $this->db->update('gz_change_of_applicant_gender_notice_details', $master_data);
    
                    $update_document_data = array(
                        'affidavit' => $data['affidavit'],
                        'original_newspaper' => $data['original_newspaper'],
                        'notice_in_softcopy' => $data['notice'],
                        'medical_cert' => $data['medical_cert'],
                        'id_proof_doc' => $data['id_proof_doc'],
                        'address_proof_doc' => $data['address_proof_doc'],
                        'deed_changing_form' => $data['deed_changing_form'],
                        'age_proof' => $data['age_proof'],
                        'approval_authority' => $data['approval_authority'],
                        'notice_softcopy_pdf' => $data['notice_softcopy_pdf'],
                        'modified_at' => $this->session->userdata('user_id'),
                        'modified_by' => date("Y-m-d H:i:s", time()),
                    );
                    $this->db->where('gz_master_id', $data['id']);
                    $this->db->update('gz_change_of_gender_document_det', $update_document_data);
    
                    $data1 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 1,
                        'document_name' => $data['affidavit'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data1);
    
                    $data2 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 2,
                        'document_name' => $data['original_newspaper'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data2);
    
                    $data3 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 3,
                        'document_name' => $data['notice'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data3);
    
                    $data4 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 4,
                        'document_name' => $data['medical_cert'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data4);
    
                    $data5 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 5,
                        'document_name' => $data['id_proof_doc'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data5);
    
                    $data6 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 6,
                        'document_name' => $data['address_proof_doc'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data6);
    
                
                    if (!empty($data['deed_changing_form'])) {
                        $data7 = array(
                            'gz_master_id' => $data['id'],
                            'gz_docu_id' => 7,
                            'document_name' => $data['deed_changing_form'],
                            'created_by' => $this->session->userdata('user_id'),
                            'created_at' => date("Y-m-d H:i:s", time()),
                            'status' => 1,
                            'deleted' => 0
                        );
                        $this->db->insert('gz_change_of_gender_history', $data7);
                    }
    
                    if (!empty($data['age_proof'])) {
                        $data8 = array(
                            'gz_master_id' => $data['id'],
                            'gz_docu_id' => 8,
                            'document_name' => $data['age_proof'],
                            'created_by' => $this->session->userdata('user_id'),
                            'created_at' => date("Y-m-d H:i:s", time()),
                            'status' => 1,
                            'deleted' => 0
                        );
                        $this->db->insert('gz_change_of_gender_history', $data8);
                    }
    
                    if (!empty($data['approval_authority'])) {
                        $data9 = array(
                            'gz_master_id' => $data['id'],
                            'gz_docu_id' => 9,
                            'document_name' => $data['approval_authority'],
                            'created_by' => $this->session->userdata('user_id'),
                            'created_at' => date("Y-m-d H:i:s", time()),
                            'status' => 1,
                            'deleted' => 0
                        );
                        $this->db->insert('gz_change_of_gender_history', $data9);
                    }
    
                    
    
                    $data10 = array(
                        'gz_master_id' => $data['id'],
                        'gz_docu_id' => 10,
                        'document_name' => $data['notice_softcopy_pdf'],
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_history', $data10);
    
                    $status = array(
                        'gz_master_id' => $data['id'],
                        'user_id' => $this->session->userdata('user_id'),
                        'change_of_gender_status' => 20,
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_change_of_gender_status_his', $status);
    
                    $approvers = $this->db->from('gz_c_and_t')
                                    ->where('verify_approve', 'Approver')    
                                    ->where('module_id', 6)
                                    ->get();
                    foreach($approvers->result() as $approver){
                        $approverID = $approver->id;
                    }
    
                    $notification_data = array(
                        'master_id' => $data['id'],
                        'module_id' => 6,
                        'user_id' => $this->session->userdata('user_id'),
                        'responsible_user_id' => $approverID,
                        'text' => "Change of gender resubmitted successfully",
                        'is_viewed' => 0,
                        'pro_ver_app' => 1,
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_notification_ct', $notification_data);
    
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

        /*
        * View Details change of gender
        */

        public function view_details_change_of_gender($id) {
            $query1 = $this->db->select('p.*,g.gazette_type,d.id as district_id, u.name, u.father_name, s.status_name, u.mobile, u.email, u.address, st.state_name,st.id as state_id, d.district_name, po.block_name as block_name')
                            ->from('gz_change_of_gender_master p')
                            ->join('gz_gazette_type g', 'p.gazette_type_id = g.id')
                            ->join('gz_applicants_details u', 'p.user_id = u.id')
                            ->join('gz_change_of_gender_status_master s', 's.id = p.current_status')
                            ->join('gz_states_master st', 'st.id = p.state_id')
                            ->join('gz_district_master d', 'd.id = p.district_id')
                            ->join('gz_block_master po', 'po.id = p.block_ulb_id','left')
                            ->where('p.deleted', '0')
                            ->where('p.type', 'block')
                            ->where('p.id', $id)
                            ->get()->row();
            $query2 = $this->db->select('p.*, g.gazette_type, u.name, u.father_name, s.status_name, u.mobile, u.email, u.address, st.state_name, d.district_name, po.ulb_name  as block_name')
                            ->from('gz_change_of_gender_master p')
                            ->join('gz_gazette_type g', 'p.gazette_type_id = g.id')
                            ->join('gz_applicants_details u', 'p.user_id = u.id')
                            ->join('gz_change_of_gender_status_master s', 's.id = p.current_status')
                            ->join('gz_states_master st', 'st.id = p.state_id')
                            ->join('gz_district_master d', 'd.id = p.district_id')
                            ->join('gz_ulb_master po', 'po.id = p.block_ulb_id','left')
                            ->where('p.deleted', '0')
                            ->where('p.type', 'ulb')
                            ->where('p.id', $id)
                            ->get()->row();

            if (!empty($query1) && empty($query2)) {
                return $query1;
            } else if (empty($query1) && !empty($query2)) {
                return $query2;
            } else if (!empty($query1) && !empty($query2)) {
                $query3 = array_merge($query1, $query2);
                return array_unique($query3);
            }
        }

        /*
        * get_total_document_change_of_gender
        */

        public function get_total_document_change_of_gender() {
            return $this->db->select('*')
                            ->from('gz_change_of_gender_document_master')
                            ->where('deleted', '0')
                            ->order_by('id', 'ASC')
                            ->get()->result();
        }

        /*
        * get_gender_status_history
        */
        public function get_gender_status_history($id) {
            return $this->db->select('c.id, c.change_of_gender_status,c.created_at,  p.status_name, r.remarks')
                            ->from('gz_change_of_gender_status_his c')
                            ->join('gz_change_of_gender_status_master p', 'c.change_of_gender_status = p.id')
                            ->join('gz_change_of_gender_remarks_master r', 'r.status_history_id = c.id', 'LEFT')
                            ->where('c.gz_master_id', $id)
                            ->order_by('c.id', 'DESC')
                            ->get()->result();
        }

        /*
        * get_gender_document_history
        */

        public function get_gender_document_history($id) {
            return $this->db->select('p.*,d.document_name as docu')
                            ->from('gz_change_of_gender_history p')
                            ->join('gz_change_of_gender_document_master d', 'p.gz_docu_id = d.id')
                            ->where('p.gz_master_id', $id)
                            ->where('d.id !=', 3)
                            ->order_by('p.id', 'ASC')
                            ->get()->result();
        }

        /*
        * get_per_page_value_change_of_gender
        */

        public function get_per_page_value_change_of_gender() {
            $data = $this->db->select('pricing')
                            ->from('gz_modules_wise_pricing')
                            ->where('module_id', 6)
                            ->where('status', 1)
                            ->where('deleted', 0)
                            ->get()->row();
            $price = "";
            if (!empty($data)) {
            $price = (int) $data->pricing;
            }
            return $price;
        }

        public function get_total_change_gender_applicant() {
            return $this->db->select('id')
                            ->from('gz_change_of_gender_master')
                            ->where('status', 1)
                            ->where('deleted', 0)
                            ->where('user_id', $this->session->userdata('user_id'))
                            ->count_all_results();
        }

        public function get_total_change_gender_count_c_and_t($type, $module_id) {
            if ($type == 'Verifier') {
                if ($module_id == 1) {

                    return $this->db->select('m.*, s.status_det , u.name')
                                    ->from('gz_change_of_partnership_master m')
                                    ->join('gz_par_sur_status_master s', 's.id = m.cur_status')
                                    ->join('gz_applicants_details u', 'm.user_id = u.id')
                                    ->where('m.cur_status', 1)
                                    ->where('m.deleted', 0)
                                    ->where('m.status', 1)
                                    ->count_all_results();
                } else if ($module_id == 2) {

                    return $this->db->select('m.*, s.status_name as status_det , u.name')
                                    ->from('gz_change_of_name_surname_master m')
                                    ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                                    ->join('gz_applicants_details u', 'u.id = m.user_id')
                                    ->where('m.current_status >=', 2)
                                    ->where('m.deleted', 0)
                                    ->where('m.status', 1)
                                    ->count_all_results();
                } else if ($module_id == 6) {

                    return $this->db->select('m.*, s.status_name as status_det , u.name')
                                    ->from('gz_change_of_gender_master m')
                                    ->join('gz_change_of_gender_status_master s', 's.id = m.current_status')
                                    ->join('gz_applicants_details u', 'u.id = m.user_id')
                                    ->where('m.current_status >=', 2)
                                    ->where('m.deleted', 0)
                                    ->where('m.status', 1)
                                    ->count_all_results();
                }
            } else if ($type == 'Approver') {
                if ($module_id == 1) {

                    return $this->db->select('m.*, s.status_det')
                                    ->from('gz_change_of_partnership_master m')
                                    ->join('gz_par_sur_status_master s', 's.id = m.cur_status')
                                    ->where('m.cur_status >=', 2)
                                    ->where('m.deleted', 0)
                                    ->where('m.status', 1)
                                    ->count_all_results();
                } else if ($module_id == 2) {

                    return $this->db->select('m.*, s.status_name as status_det')
                                    ->from('gz_change_of_name_surname_master m')
                                    ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                                    ->where('m.current_status >=', 2)
                                    ->where('m.deleted', 0)
                                    ->where('m.status', 1)
                                    ->count_all_results();
                } else if ($module_id == 6) {

                    return $this->db->select('m.*, s.status_name as status_det')
                                    ->from('gz_change_of_gender_master m')
                                    ->join('gz_change_of_gender_status_master s', 's.id = m.current_status')
                                    ->where('m.current_status >=', 2)
                                    ->where('m.deleted', 0)
                                    ->where('m.status', 1)
                                    ->count_all_results();
                }
            } else if ($type == 'Processor') {

                return $this->db->select('m.*, s.status_name as status_det')
                                ->from('gz_change_of_gender_master m')
                                ->join('gz_change_of_gender_status_master s', 's.id = m.current_status')
                            //->where_in('m.current_status', array(1, 6, 12, 13, 8))
                                ->where('m.deleted', 0)
                                ->where('m.status', 1)
                                ->count_all_results();
            }
        }

        public function get_total_change_of_genders_applicant() {
            return $this->db->select('u.name AS applicant_name, m.file_no, s.status_name, m.id, m.created_at')
                            ->from('gz_change_of_gender_master m')
                            ->join('gz_change_of_gender_status_master s', 's.id = m.current_status')
                            ->join('gz_applicants_details u', 'm.user_id = u.id')
                            ->where('m.status', 1)
                            ->where('m.deleted', 0)
                            ->where('m.user_id', $this->session->userdata('user_id'))
                            ->order_by('m.id', 'DESC')
                            ->get()->result();
        }

        public function get_total_change_of_genders_c_and_t($limit, $offset, $type, $module_id) {
            if ($type == 'Verifier') {
                    return $this->db->select('m.*, s.status_name, app.name AS applicant_name')
                                    ->from('gz_change_of_gender_master m')
                                    ->join('gz_change_of_gender_status_master s', 's.id = m.current_status')
                                    ->join('gz_applicants_details app', 'm.user_id = app.id')
                                    ->where('m.current_status >=', 2)
                                    ->where('m.deleted', 0)
                                    ->where('m.status', 1)
                                    ->order_by('id', 'DESC')
                                    ->limit($limit, $offset)
                                    ->get()->result();  
            } else if ($type == 'Approver') {

                //if ($module_id == 2) {

                    return $this->db->select('m.*, s.status_name, app.name AS applicant_name')
                                    ->from('gz_change_of_gender_master m')
                                    ->join('gz_change_of_gender_status_master s', 's.id = m.current_status')
                                    ->join('gz_applicants_details app', 'm.user_id = app.id')
                                    ->where('m.current_status >=', 2)
                                    ->where('m.deleted', 0)
                                    ->where('m.status', 1)
                                    ->order_by('id', 'DESC')
                                    ->limit($limit, $offset)
                                    ->get()->result();
                //}
            } else if ($type == 'Processor') {

                return $this->db->select('m.*, s.status_name, app.name AS applicant_name')
                                ->from('gz_change_of_gender_master m')
                                ->join('gz_change_of_gender_status_master s', 's.id = m.current_status')
                                ->join('gz_applicants_details app', 'm.user_id = app.id')
                                //->where_in('m.current_status', array(1, 6, 12, 13, 8))
                                ->where('m.deleted', 0)
                                ->where('m.status', 1)
                                // ->where_in('s.id', array(1)) // added for testing. remove the comment before production..
                                ->order_by('id', 'DESC')
                                ->limit($limit, $offset)
                                ->get()->result();
            }
        }

        public function get_gender_status() {
            return $this->db->select('*')
                            ->from('gz_change_of_gender_status_master')
                            // ->where('id >=',2)
                            ->get()
                            ->result();
        }

        public function exists_change_of_gender($id) {
            $result = $this->db->select('*')->from('gz_change_of_gender_master')->where('id', $id)->get();
            if ($result->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function view_details_change_gender($id) {

            $query1 = $this->db->select('p.*,g.gazette_type,d.id as district_id, u.name, u.father_name, s.status_name, u.mobile, u.email, u.address, st.state_name,st.id as state_id, d.district_name, po.block_name as block_name')
                            ->from('gz_change_of_gender_master p')
                            ->join('gz_gazette_type g', 'p.gazette_type_id = g.id')
                            ->join('gz_applicants_details u', 'p.user_id = u.id')
                            ->join('gz_change_of_gender_status_master s', 's.id = p.current_status')
                            ->join('gz_states_master st', 'st.id = p.state_id')
                            ->join('gz_district_master d', 'd.id = p.district_id')
                            ->join('gz_block_master po', 'po.id = p.block_ulb_id','left')
                            ->where('p.deleted', '0')
                            ->where('p.type', 'block')
                            ->where('p.id', $id)
                            ->get()->row();
            $query2 = $this->db->select('p.*, g.gazette_type, u.name, u.father_name, s.status_name, u.mobile, u.email, u.address, st.state_name, d.district_name, po.ulb_name  as block_name')
                            ->from('gz_change_of_gender_master p')
                            ->join('gz_gazette_type g', 'p.gazette_type_id = g.id')
                            ->join('gz_applicants_details u', 'p.user_id = u.id')
                            ->join('gz_change_of_gender_status_master s', 's.id = p.current_status')
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

        public function get_remarks_on_change_gender($id, $status) {
            $data = $this->db->select('remarks')
                            ->from('gz_change_of_gender_remarks_master')
                            ->where('change_of_gender_id', $id)
                            ->where('status_id', $status)
                            ->get()->row();
            $remarks = "";

            if (!empty($data)) {
                $remarks = $data->remarks;
            }
            return $remarks;
        }

        public function get_total_change_gender_count_c_and_t_search($data = array()) {
            $this->db->select('m.*, s.status_name, app.name AS applicant_name');
            $this->db->from('gz_change_of_gender_master m');
            $this->db->join('gz_change_of_gender_status_master s', 's.id = m.current_status');
            $this->db->join('gz_applicants_details app', 'm.user_id = app.id');
            //$this->db->where('m.current_status >=', 2);
            $this->db->where('m.deleted', 0);
            $this->db->where('m.status', 1);
            //$array_items = array('name', 'file_no', 'status', 'notice_date_form', 'notice_date_to');
            //print_r($data);
            if (!empty($data['app_name'])) {
                $this->db->like('app.name', $data['app_name']);
            }
            if (!empty($data['file_no'])) {
                $this->db->like('m.file_no', $data['file_no']);
            }
            if (!empty($data['status'])) {
                $this->db->like('m.current_status', $data['status']);
            }
            if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                $this->db->where('DATE(m.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
            } else {
                if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                    $this->db->where('DATE(m.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                    $this->db->where('DATE(m.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                    $this->db->where('DATE(m.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                    $this->db->where('DATE(m.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
                }
            }
        
            $this->db->order_by('id', 'DESC');
            return $this->db->count_all_results();     

        }

        public function change_of_gender_search_result($limit, $offset, $data = array()) {

            $this->db->select('m.*, s.status_name, app.name AS applicant_name');
            $this->db->from('gz_change_of_gender_master m');
            $this->db->join('gz_change_of_gender_status_master s', 's.id = m.current_status');
            $this->db->join('gz_applicants_details app', 'm.user_id = app.id');
            
            $this->db->where('m.deleted', 0);
            $this->db->where('m.status', 1);
            
            if (!empty($data['app_name'])) {
                $this->db->like('app.name', $data['app_name']);
            }
            if (!empty($data['file_no'])) {
                $this->db->like('m.file_no', $data['file_no']);
            }
            if (!empty($data['status'])) {
                $this->db->where('m.current_status', $data['status']);
            }
            if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
                $this->db->where('DATE(m.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                $this->db->where('DATE(m.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
            } else {
                if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                    $this->db->where('DATE(m.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                    $this->db->where('DATE(m.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                    $this->db->where('DATE(m.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                    $this->db->where('DATE(m.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
                }
            }
            //print_r($this->db->last_query()); exit;
        
            $this->db->order_by('id', 'DESC');
            $this->db->limit($limit, $offset);
            return $this->db->get()->result();     

                    
        }

        public function forward_change_gender_c_and_t_processor($update_status) {
            try {
                $this->db->trans_begin();

                $status_history = array(
                    'gz_master_id' => $update_status['id'],
                    'change_of_gender_status' => $update_status['status'],
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'created_by' => $this->session->userdata('user_id')
                );
                $this->db->insert('gz_change_of_gender_status_his', $status_history);
                $last_id = $this->db->insert_id();

                $remark_data = array(
                    'change_of_gender_id' => $update_status['id'],
                    'status_id' => $update_status['status'],
                    'remarks' => $update_status['remarks'],
                    'status_history_id' => $last_id
                );
                $this->db->insert('gz_change_of_gender_remarks_master', $remark_data);

                $master_data = array(
                    'current_status' => $update_status['status'],
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'modified_by' => $this->session->userdata('user_id')
                );
                $this->db->where('id', $update_status['id']);
                $this->db->update('gz_change_of_gender_master', $master_data);

                $verifiers = $this->db->from('gz_c_and_t')
                                    ->where('verify_approve', 'Verifier')    
                                    ->where('module_id', 6)
                                    ->get();
                foreach($verifiers->result() as $verifier){
                    $verifierID = $verifier->id;
                }

                $applicant_id = $this->db->select('user_id')
                                    ->from('gz_change_of_gender_master')
                                    ->where('id', $update_status['id'])
                                    ->get()->row();

                $notification_data_ct = array(
                    'master_id' => $update_status['id'],
                    'module_id' => 6,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $verifierID,
                    'text' => "Change of Gender request forwarded to verifier",
                    'is_viewed' => 0,
                    'pro_ver_app' => 2,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_ct', $notification_data_ct);

                $notification_data_applicant = array(
                    'master_id' => $update_status['id'],
                    'module_id' => 6,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $applicant_id->user_id,
                    'text' => "Change of Gender request forwarded to C & T verifier",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_applicant', $notification_data_applicant);


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

        public function return_to_applicant_c_and_t_processor_gender($update_status){
            try {
                $this->db->trans_begin();

                $status_history = array(
                    'gz_master_id' => $update_status['id'],
                    'change_of_gender_status' => $update_status['status'],
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'created_by' => $this->session->userdata('user_id')
                );
                $this->db->insert('gz_change_of_gender_status_his', $status_history);
                $last_id = $this->db->insert_id();

                $remark_data = array(
                    'change_of_gender_id' => $update_status['id'],
                    'status_id' => $update_status['status'],
                    'remarks' => $update_status['remarks'],
                    'status_history_id' => $last_id
                );
                $this->db->insert('gz_change_of_gender_remarks_master', $remark_data);

                $master_data = array(
                    'current_status' => $update_status['status'],
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'modified_by' => $this->session->userdata('user_id')
                );
                $this->db->where('id', $update_status['id']);
                $this->db->update('gz_change_of_gender_master', $master_data);

                $applicant_id = $this->db->select('user_id')
                                    ->from('gz_change_of_gender_master')
                                    ->where('id', $update_status['id'])
                                    ->get()->row();

                $notification_data_applicant = array(
                    'master_id' => $update_status['id'],
                    'module_id' => 6,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $applicant_id->user_id,
                    'text' => "Change of Gender request returned from C & T processor",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_applicant', $notification_data_applicant);


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

        public function reject_change_gender_c_and_t_processor($update_status) {
            try {
                $this->db->trans_begin();

                $status_history = array(
                    'gz_master_id' => $update_status['id'],
                    'change_of_gender_status' => $update_status['status'],
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'created_by' => $this->session->userdata('user_id')
                );
                $this->db->insert('gz_change_of_gender_status_his', $status_history);
                $last_id = $this->db->insert_id();

                $remark_data = array(
                    'change_of_gender_id' => $update_status['id'],
                    'status_id' => $update_status['status'],
                    'remarks' => $update_status['remarks'],
                    'status_history_id' => $last_id
                );
                $this->db->insert('gz_change_of_gender_remarks_master', $remark_data);

                $master_data = array(
                    'current_status' => $update_status['status'],
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'modified_by' => $this->session->userdata('user_id')
                );
                $this->db->where('id', $update_status['id']);
                $this->db->update('gz_change_of_gender_master', $master_data);

                $notification_data_ct = array(
                    'master_id' => $update_status['id'],
                    'module_id' => $this->session->userdata('is_c&t_module'),
                    'user_id' => $this->session->userdata('user_id'),
                    'text' => "Change of gender request submitted to approver to reject",
                    'is_viewed' => 0,
                    'pro_ver_app' => 2,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_ct', $notification_data_ct);

                $notification_data_applicant = array(
                    'master_id' => $update_status['id'],
                    'module_id' => $this->session->userdata('is_c&t_module'),
                    'user_id' => $this->session->userdata('user_id'),
                    'text' => "Change of gender request submitted to approver to reject",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_applicant', $notification_data_applicant);

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

        public function forward_change_gender_c_and_t_verifier($update_status) {
            try {
                $this->db->trans_begin();

                $status_history = array(
                    'gz_master_id' => $update_status['id'],
                    'change_of_gender_status' => $update_status['status'],
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'created_by' => $this->session->userdata('user_id')
                );
                $this->db->insert('gz_change_of_gender_status_his', $status_history);
                $last_id = $this->db->insert_id();

                $remark_data = array(
                    'change_of_gender_id' => $update_status['id'],
                    'status_id' => $update_status['status'],
                    'remarks' => $update_status['remarks'],
                    'status_history_id' => $last_id
                );
                $this->db->insert('gz_change_of_gender_remarks_master', $remark_data);

                $master_data = array(
                    'current_status' => $update_status['status'],
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'modified_by' => $this->session->userdata('user_id')
                );
                $this->db->where('id', $update_status['id']);
                $this->db->update('gz_change_of_gender_master', $master_data);

                $approvers = $this->db->from('gz_c_and_t')
                                    ->where('verify_approve', 'Approver')    
                                    ->where('module_id', 6)
                                    ->get();
                foreach($approvers->result() as $approver){
                    $approverID = $approver->id;
                }

                $applicant_id = $this->db->select('user_id')
                                    ->from('gz_change_of_gender_master')
                                    ->where('id', $update_status['id'])
                                    ->get()->row();

                $notification_data_ct = array(
                    'master_id' => $update_status['id'],
                    'module_id' => 6,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $approverID,
                    'text' => "Change of gender request forwarded to approver",
                    'is_viewed' => 0,
                    'pro_ver_app' => 3,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_ct', $notification_data_ct);

                $notification_data_applicant = array(
                    'master_id' => $update_status['id'],
                    'module_id' => 6,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $applicant_id->user_id,
                    'text' => "Change of gender request forwarded to C & T approver",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_applicant', $notification_data_applicant);

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

        public function return_to_applicant_c_and_t_verifier_gender($update_status){
            try {
                $this->db->trans_begin();

                $status_history = array(
                    'gz_master_id' => $update_status['id'],
                    'change_of_gender_status' => $update_status['status'],
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'created_by' => $this->session->userdata('user_id')
                );
                $this->db->insert('gz_change_of_gender_status_his', $status_history);
                $last_id = $this->db->insert_id();

                $remark_data = array(
                    'change_of_gender_id' => $update_status['id'],
                    'status_id' => $update_status['status'],
                    'remarks' => $update_status['remarks'],
                    'status_history_id' => $last_id
                );
                $this->db->insert('gz_change_of_gender_remarks_master', $remark_data);

                $master_data = array(
                    'current_status' => $update_status['status'],
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'modified_by' => $this->session->userdata('user_id')
                );
                $this->db->where('id', $update_status['id']);
                $this->db->update('gz_change_of_gender_master', $master_data);

                $applicant_id = $this->db->select('user_id')
                                    ->from('gz_change_of_gender_master')
                                    ->where('id', $update_status['id'])
                                    ->get()->row();

                $notification_data_applicant = array(
                    'master_id' => $update_status['id'],
                    'module_id' => 6,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $applicant_id->user_id,
                    'text' => "Change of gender returned from C & T Verifier",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_applicant', $notification_data_applicant);

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

        public function reject_change_gender_c_and_t_verifier($update_status) {
            try {
                $this->db->trans_begin();

                $remark_data = array(
                    'change_of_gender_id' => $update_status['id'],
                    'status_id' => $update_status['status'],
                    'remarks' => $update_status['remarks']
                );
                $this->db->insert('gz_change_of_gender_remarks_master', $remark_data);

                $master_data = array(
                    'current_status' => $update_status['status'],
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'modified_by' => $this->session->userdata('user_id')
                );
                $this->db->where('id', $update_status['id']);
                $this->db->update('gz_change_of_gender_master', $master_data);

                $status_history = array(
                    'gz_master_id' => $update_status['id'],
                    'change_of_gender_status' => $update_status['status'],
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'created_by' => $this->session->userdata('user_id')
                );
                $this->db->insert('gz_change_of_gender_status_his', $status_history);

                $notification_data_ct = array(
                    'master_id' => $update_status['id'],
                    'module_id' => 6,
                    'user_id' => $this->session->userdata('user_id'),
                    'text' => "Change of gender request forwarded to approver for rejection",
                    'is_viewed' => 0,
                    'pro_ver_app' => 3,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_ct', $notification_data_ct);

                $notification_data_applicant = array(
                    'master_id' => $update_status['id'],
                    'module_id' => 6,
                    'user_id' => $this->session->userdata('user_id'),
                    'text' => "Change of gender request forwarded to approver for rejection",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_applicant', $notification_data_applicant);

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

        public function approve_change_gender_c_and_t_approver($update_status) {
            try {
                $this->db->trans_begin();
                $status_history = array(
                    'gz_master_id' => $update_status['id'],
                    'change_of_gender_status' => $update_status['status'],
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'created_by' => $this->session->userdata('user_id')
                );
                $this->db->insert('gz_change_of_gender_status_his', $status_history);
                $last_id = $this->db->insert_id();

                $remark_data = array(
                    'change_of_gender_id' => $update_status['id'],
                    'status_id' => $update_status['status'],
                    'remarks' => $update_status['remarks'],
                    'status_history_id' => $last_id
                );
                $this->db->insert('gz_change_of_gender_remarks_master', $remark_data);

                $master_data = array(
                    'current_status' => $update_status['status'],
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'modified_by' => $this->session->userdata('user_id')
                );
                $this->db->where('id', $update_status['id']);
                $this->db->update('gz_change_of_gender_master', $master_data);

                $admin_details =  $this->db->select('*')
                                        ->from('gz_users')
                                        ->where('is_admin', 1)
                                        ->where('status', 1)
                                        ->get()->row();
                                        
                $notification_data_ct = array(
                    'master_id' => $update_status['id'],
                    'module_id' => 6,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $admin_details->id,
                    'text' => "Change of gender request forwarded to government press from C & T approver",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_govt', $notification_data_ct);

                $applicant_id = $this->db->select('user_id')
                                    ->from('gz_change_of_gender_master')
                                    ->where('id', $update_status['id'])
                                    ->get()->row();

                $notification_data_applicant = array(
                    'master_id' => $update_status['id'],
                    'module_id' => 6,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $applicant_id->user_id,
                    'text' => "Change of gender request forwarded to government press from C & T approver",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_applicant', $notification_data_applicant);

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

        public function return_to_applicant_change_gender_c_and_t_approver($update_status){

            try {
                $this->db->trans_begin();

                $status_history = array(
                    'gz_master_id' => $update_status['id'],
                    'change_of_gender_status' => $update_status['status'],
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'created_by' => $this->session->userdata('user_id')
                );
                $this->db->insert('gz_change_of_gender_status_his', $status_history);
                $last_id = $this->db->insert_id();

                $remark_data = array(
                    'change_of_gender_id' => $update_status['id'],
                    'status_id' => $update_status['status'],
                    'remarks' => $update_status['remarks'],
                    'status_history_id' => $last_id
                );
                $this->db->insert('gz_change_of_gender_remarks_master', $remark_data);

                $master_data = array(
                    'current_status' => $update_status['status'],
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'modified_by' => $this->session->userdata('user_id')
                );
                $this->db->where('id', $update_status['id']);
                $this->db->update('gz_change_of_gender_master', $master_data);

                $applicant_id = $this->db->select('user_id')
                                    ->from('gz_change_of_gender_master')
                                    ->where('id', $update_status['id'])
                                    ->get()->row();

                $notification_data_applicant = array(
                    'master_id' => $update_status['id'],
                    'module_id' => 6,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $applicant_id->user_id,
                    'text' => "Change of gender request returned from C & T approver",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_applicant', $notification_data_applicant);


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
        
        public function reject_change_gender_c_and_t_approver($update_status) {
            try {
                $this->db->trans_begin();

                $status_history = array(
                    'gz_master_id' => $update_status['id'],
                    'change_of_gender_status' => $update_status['status'],
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'created_by' => $this->session->userdata('user_id')
                );
                $this->db->insert('gz_change_of_gender_status_his', $status_history);
                $last_id = $this->db->insert_id();

                $remark_data = array(
                    'change_of_gender_id' => $update_status['id'],
                    'status_id' => $update_status['status'],
                    'remarks' => $update_status['remarks'],
                    'status_history_id' => $last_id
                );
                $this->db->insert('gz_change_of_gender_remarks_master', $remark_data);

                $master_data = array(
                    'current_status' => $update_status['status'],
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'modified_by' => $this->session->userdata('user_id')
                );
                $this->db->where('id', $update_status['id']);
                $this->db->update('gz_change_of_gender_master', $master_data);

                $status_history = array(
                    'gz_master_id' => $update_status['id'],
                    'change_of_gender_status' => $update_status['status'],
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'created_by' => $this->session->userdata('user_id')
                );
                $this->db->insert('gz_change_of_gender_status_his', $status_history);

                $applicant_id = $this->db->select('user_id')
                                    ->from('gz_change_of_gender_master')
                                    ->where('id', $update_status['id'])
                                    ->get()->row();

                $notification_data_applicant = array(
                    'master_id' => $update_status['id'],
                    'module_id' => 6,
                    'user_id' => $this->session->userdata('user_id'),
                    'responsible_user_id' => $applicant_id->user_id,
                    'text' => "Change of gender request rejected by C & T Approver",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_applicant', $notification_data_applicant);

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

        public function save_change_gender_trans_status($insert_array) {
            try {


                $this->db->trans_begin();

                $user_session = $this->session->userdata('user_id');
                $transaction_data = array(
                    'change_gender_id' => $insert_array['change_gender_id'],
                    'file_number' => $insert_array['file_number'],
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

                $this->db->insert('gz_change_of_gender_payment_details', $transaction_data);
                $last_id = $this->db->insert_id();

                // INSERT INTO the status history Table
                $insert_stat = array(
                    'payment_id' => $last_id,
                    // Change of Surname
                    'payment_type' => 'COG',
                    'payment_status' => $insert_array['trans_status'],
                    'created_at' => date('Y-m-d H:i:s', time())
                );

                $this->db->insert('gz_payment_status_history', $insert_stat);

                $status = 9;

                if ($insert_array['trans_status'] == 'S') {

                    $status = 10;
                    $admin = $this->db->from('gz_users')
                                        ->where('is_admin', '1')
                                        ->where('status', '1')
                                        ->get()->row();
                    
                    $notification_data_ct = array(
                        'master_id' => $insert_array['change_gender_id'],
                        'module_id' => 6,
                        'user_id' => $user_session,
                        'responsible_user_id' => $admin->id,
                        'text' => "Payment Successful",
                        'is_viewed' => 0,
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    // echo "<pre>";
                    // print_r($notification_data_ct);
                    // exit;

                    $this->db->insert('gz_notification_govt', $notification_data_ct);
                } else if ($insert_array['trans_status'] == 'F') {

                    $status = 16;
                } else if ($insert_array['trans_status'] == 'P') {

                    $status = 15;
                }

                $master_data = array(
                    'current_status' => $status,
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'modified_by' => $this->session->userdata('user_id')
                );


                $this->db->where('id', $insert_array['change_gender_id']);
                $this->db->update('gz_change_of_gender_master', $master_data);

                if ($status != 9) {
                    $status_history = array(
                        'gz_master_id' => $insert_array['change_gender_id'],
                        'change_of_gender_status' => $status,
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'created_by' => $this->session->userdata('user_id')
                    );
                    $this->db->insert('gz_change_of_gender_status_his', $status_history);
                }

                if ($this->db->trans_status() == FALSE) {
                    $this->db->trans_rollback();
                    return FALSE;
                } else {
                    $this->db->trans_commit();
                    return TRUE;
                }
            } catch (Exception $e) {
                return FALSE;
            }
        }

        public function check_gender_offline_pay_status($record_id) {
            $status = $this->db->select('offline_pay_status')->from('gz_change_of_gender_master')->where('id',$record_id)->get()->row();
            //echo $this->db->last_query();
            return $status;
        }

        public function set_gender_offline_pay_status($record_id,$price) {
            $this->db->where('id', $record_id);
            $this->db->update('gz_change_of_gender_master', array('offline_pay_status'=>1,'total_price_to_paid'=>$price));
            if($this->db->affected_rows() > 0)
            {
                return true;
            }
            else{
                return false;
            }
        }

        public function select_notice_det_gender($cog_id) {
            $this->db->select('*','date', '%d/%m/%Y')
                    ->from('gz_change_of_applicant_gender_notice_details');
            $this->db->where('deleted', 0);
            $this->db->where('status', 1);
            $this->db->where('change_gender_id', $cog_id);
            return $this->db->get()->row();
        }

        public function get_total_cnt_govt_change_gender_pending($data = array()) {
            $this->db->select('p.*')
                    ->from('gz_change_of_gender_master p')
                    ->join('gz_change_of_gender_status_master s', 's.id = p.current_status')
                    ->join('gz_change_of_gender_document_det d', 'd.gz_master_id = p.id')
                    ->where('p.deleted', 0)
                    ->where_in('p.current_status', array(7, 9, 17));

            if (!empty($data['statusType'])) {
                $this->db->like('p.current_status', $data['statusType']);
            }
            if (!empty($data['file_no'])) {
                $this->db->like('p.file_no', $data['file_no']);
            }

            if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
            } else {
                if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                    
                    $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                    $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                    
                    $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                    $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
                }
            }

            return $this->db->count_all_results();
        }

        public function status_change_gender_pending_list() {
            return $this->db->select('c.*')
                            ->from('gz_change_of_gender_status_master as c')
                            ->where_in('c.id', array(7, 9))
                            ->order_by('c.id', 'DESC')
                            ->get()->result();
        }

        public function get_total_cnt_govt_list_pending_change_gender($limit, $offset,$data = array()) {

            $this->db->select('p.file_no, p.created_at, s.status_name, p.id, d.notice_softcopy_pdf')
                        ->from('gz_change_of_gender_master p')
                        ->join('gz_change_of_gender_status_master s', 's.id = p.current_status')
                        ->join('gz_change_of_gender_document_det d', 'd.gz_master_id = p.id')
                        ->where('p.deleted', 0)
                        ->where_in('p.current_status', array(7, 9, 17));

                        if (!empty($data['statusType'])) {
                            $this->db->like('p.current_status', $data['statusType']);
                        }
                        if (!empty($data['file_no'])) {
                            $this->db->like('p.file_no', $data['file_no']);
                        }
                        
                        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                        } else {
                            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                                
                                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                                
                                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            }
                        }

        $this->db->order_by('p.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
        }

        public function get_pdf_path_of_change_of_gender($id) {
            return $this->db->select('press_pdf, notice_softcopy_pdf')
                            ->from('gz_change_of_gender_document_det')
                            ->where('gz_master_id', $id)
                            ->where('status', 1)
                            ->where('deleted', 0)
                            ->get()->row();
        }

        public function get_gazette_documents_change_gender($gazette_id) {
            return $this->db->select('*')->from('gz_change_of_gender_document_det')
                            ->where('gz_master_id', $gazette_id)
                            ->get()->row();
        }

        public function get_sl_no_change_gender() {
            $sl_no = 0;
            $year = date("Y");
            $result = $this->db->select('*')->from('gz_change_of_gender_master')->get();
            if ($result->num_rows() > 0) {
                $sl_no_data = $this->db->query('SELECT MAX(sl_no) AS sl_no FROM gz_change_of_gender_master WHERE  YEAR(created_at) = ' . $year);
                $sl_no = @($sl_no_data->row()->sl_no + 1);
            } else {
                $sl_no = 1;
            }
            return $sl_no;
        }

        public function save_preview_press_gazette_change_gender($data) {
            try {
                $this->db->trans_begin();

                // Update gazetee table
                $gazette_data = array(
                    'sl_no' => $data['sl_no'],
                    'saka_date' =>$data['saka_date'],
                    'current_status' => $data['status_id'],
                    'modified_by' => $data['user_id'],
                    'modified_at' => date('Y-m-d H:i:s', time()),
                );

                $this->db->where('id', $data['gazette_id']);
                $this->db->update('gz_change_of_gender_master', $gazette_data);

                //Insert to gazette_status table
                $stat_data = array(
                    'user_id' => $data['user_id'],
                    'gz_master_id' => $data['gazette_id'],
                    // published
                    'change_of_gender_status' => $data['status_id'],
                    'created_by' => $data['user_id'],
                    'created_at' => date('Y-m-d H:i:s', time())
                );

                $this->db->insert('gz_change_of_gender_status_his', $stat_data);

                $notification_data_applicant = array(
                    'master_id' => $data['gazette_id'],
                    'module_id' => 6,
                    'user_id' => $this->session->userdata('user_id'),
                    'text' => "Change of gender request approved by government press",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_applicant', $notification_data_applicant);

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

        public function get_file_number_change_gender($id) {

            return $this->db->select('file_no')
                            ->from('gz_change_of_gender_master')
                            ->where('id', $id)
                            ->where('status', 1)
                            ->where('deleted', 0)
                            ->get()->row();
        }

        public function change_gender_publish($update_status) {

            try {

                $this->db->trans_begin();

                $master_data = array(
                    'current_status' => $update_status['status'],
                    'is_published' => 1,
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'modified_by' => $this->session->userdata('user_id')
                );
                $this->db->where('id', $update_status['id']);
                $this->db->update('gz_change_of_gender_master', $master_data);

                $status_history = array(
                    'gz_master_id' => $update_status['id'],
                    'change_of_gender_status' => $update_status['status'],
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'created_by' => $this->session->userdata('user_id')
                );
                $this->db->insert('gz_change_of_gender_status_his', $status_history);

                for ($i = 1; $i <= 3; $i++) {
                    $notification_data_ct = array(
                        'master_id' => $update_status['id'],
                        'module_id' => 6,
                        'user_id' => $this->session->userdata('user_id'),
                        'text' => "Change of gender gazette published successfully",
                        'is_viewed' => 0,
                        'pro_ver_app' => $i,
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_notification_ct', $notification_data_ct);
                }

                $notification_data_applicant = array(
                    'master_id' => $update_status['id'],
                    'module_id' => 6,
                    'user_id' => $this->session->userdata('user_id'),
                    'text' => "Change of gender gazette published successfully",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_applicant', $notification_data_applicant);

                if ($this->db->trans_status() == FALSE) {

                    $this->db->trans_rollback();
                    return FALSE;
                } else {

                    $this->db->trans_commit();
                    return TRUE;
                }
            } catch (Exception $e) {
                return FALSE;
            }
        }

        public function get_total_cnt_govt_change_gender_paid($data = array()) {

            $this->db->select('p.file_no, p.created_at, s.status_name, p.id, d.notice_softcopy_pdf')
                        ->from('gz_change_of_gender_master p')
                        ->join('gz_change_of_gender_status_master s', 's.id = p.current_status')
                        ->join('gz_change_of_gender_document_det d', 'd.gz_master_id = p.id')
                        ->where('p.deleted', 0)
                        ->where('p.current_status', 10);

                        if (!empty($data['statusType'])) {
                            $this->db->like('p.current_status', $data['statusType']);
                        }
                        if (!empty($data['file_no'])) {
                            $this->db->like('p.file_no', $data['file_no']);
                        }
                        
                        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                        } else {
                            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                                
                                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                                
                                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            }
                        }
                
                        return $this->db->count_all_results();
        }

        public function get_total_cnt_govt_list_payed_change_gender($limit, $offset,$data = array()) {
            $this->db->select('p.file_no, p.created_at, s.status_name, p.id, d.notice_softcopy_pdf')
                        ->from('gz_change_of_gender_master p')
                        ->join('gz_change_of_gender_status_master s', 's.id = p.current_status')
                        ->join('gz_change_of_gender_document_det d', 'd.gz_master_id = p.id')
                        ->where('p.deleted', 0)
                        ->where('p.current_status', 10);

                        if (!empty($data['statusType'])) {
                            $this->db->like('p.current_status', $data['statusType']);
                        }
                        if (!empty($data['file_no'])) {
                            $this->db->like('p.file_no', $data['file_no']);
                        }
                        
                        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                        } else {
                            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                                
                                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                                
                                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            }
                        }

                        $this->db->order_by('p.id', 'DESC');
                        $this->db->limit($limit, $offset);
                        return $this->db->get()->result();
        }

        public function get_total_cnt_govt_change_gender_published($data = array()) {

            $this->db->select('p.file_no, p.created_at, s.status_name, p.id, d.notice_softcopy_pdf')
                        ->from('gz_change_of_gender_master p')
                        ->join('gz_change_of_gender_status_master s', 's.id = p.current_status')
                        ->join('gz_change_of_gender_document_det d', 'd.gz_master_id = p.id')
                        ->where('p.deleted', 0)
                        ->where('p.current_status', 11);

                        if (!empty($data['statusType'])) {
                            $this->db->like('p.current_status', $data['statusType']);
                        }
                        if (!empty($data['file_no'])) {
                            $this->db->like('p.file_no', $data['file_no']);
                        }
                        
                        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                        } else {
                            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                                
                                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                                
                                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            }
                        }
                
                        return $this->db->count_all_results();
        }

        public function get_total_cnt_govt_list_publish_change_gender($limit, $offset,$data = array()) {
            $this->db->select('p.file_no, p.created_at, s.status_name, p.id, d.notice_softcopy_pdf')
                        ->from('gz_change_of_gender_master p')
                        ->join('gz_change_of_gender_status_master s', 's.id = p.current_status')
                        ->join('gz_change_of_gender_document_det d', 'd.gz_master_id = p.id')
                        ->where('p.deleted', 0)
                        ->where('p.current_status', 11);
                        
                        if (!empty($data['statusType'])) {
                            $this->db->like('p.current_status', $data['statusType']);
                        }
                        if (!empty($data['file_no'])) {
                            $this->db->like('p.file_no', $data['file_no']);
                        }
                        
                        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
                            $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                        } else {
                            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                                
                                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_to'])));
                                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_to'])));
                            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                                
                                $this->db->where('DATE(p.created_at)>=', date('Y-m-d', strtotime($data['notice_date_form'])));
                                $this->db->where('DATE(p.created_at)<=', date('Y-m-d', strtotime($data['notice_date_form'])));
                            }
                        }
                        $this->db->order_by('p.id', 'DESC');
                        $this->db->limit($limit, $offset);
                        return $this->db->get()->result();
        }

        public function forward_to_pay_change_gender($update_status) {
            try {
                $this->db->trans_begin();

                $master_data = array(
                    'current_status' => $update_status['status'],
                    'modified_at' => date("Y-m-d H:i:s", time()),
                    'modified_by' => $this->session->userdata('user_id')
                );
                $this->db->where('id', $update_status['id']);
                $this->db->update('gz_change_of_gender_master', $master_data);

                $status_history = array(
                    'gz_master_id' => $update_status['id'],
                    'change_of_gender_status' => $update_status['status'],
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'created_by' => $this->session->userdata('user_id')
                );
                $this->db->insert('gz_change_of_gender_status_his', $status_history);

                $notification_data_applicant = array(
                    'master_id' => $update_status['id'],
                    'module_id' => 6,
                    'user_id' => $this->session->userdata('user_id'),
                    'text' => "Change of gender request forwarded to you for payment",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_applicant', $notification_data_applicant);

                $mobile = $this->db->select('u.mobile, g.file_no')
                                ->from('gz_applicants_details u')
                                ->join('gz_change_of_gender_master g', 'u.id = g.user_id')
                                ->where('g.id', $update_status['id'])
                                ->where('u.status', 1)
                                ->get()->row();

                // load SMS library will activate once live
                $this->load->library("cdac_sms");
                // message format
                $message = "Extraordinary Gazette File No. {$mobile->file_no} has been approved by the Govt. Press. Govt. of (StateName).";
                $sms_api = new Cdac_sms();
                // send SMS using API
                $template_id = "1007938090042852633";
                $sms_api->sendOtpSMS($message, $mobile, $template_id);

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

        public function get_press_signed_pdf_path_change_gender($data = array()) {
            // Update documents table
            $doc_data = array(
                'press_signed_pdf' => $data['press_signed_pdf_path'],
            );

            $this->db->where('id', $data['gazette_id']);
            $this->db->update('gz_change_of_gender_master', $doc_data);
            return ($this->db->affected_rows() == 1) ? true : false;
        }

        /*
        * Sign PDF
        */

        public function change_gender_sign_pdf($update_status, $id) {

            try {

                $this->db->trans_begin();

                $this->db->where('id', $id);
                $this->db->update('gz_change_of_gender_master', $update_status);

                if ($this->db->trans_status() == FALSE) {

                    $this->db->trans_rollback();
                    return FALSE;
                } else {

                    $this->db->trans_commit();
                    return TRUE;
                }
            } catch (Exception $e) {
                return FALSE;
            }
        }

        /**
         * Filtarion For Change of Gender Govt Press
         */

        public function get_total_cnt_govt_list_change_gender_search($limit, $offset, $data = array()){
        $this->db->select('p.file_no, p.created_at, s.status_name, p.id, d.notice_softcopy_pdf, app.name AS applicant_name')
                ->from('gz_change_of_gender_master p')
                ->join('gz_change_of_gender_status_master s', 's.id = p.current_status')
                ->join('gz_change_of_gender_document_det d', 'd.gz_master_id = p.id')
                ->join('gz_applicants_details app', 'p.user_id = app.id')
                ->where('p.deleted', 0)
                ->where_in('p.current_status', array(7, 9, 17));

        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('p.file_no', $data['file_no']);
        }
        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
            $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_to']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_form']);
            }
        }
        //print_r($this->db->last_query()); exit;
        
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
        }

        public function get_total_cnt_govt_list_payed_change_gender_search($limit, $offset, $data = array()){
        $this->db->select('p.file_no, p.created_at, s.status_name, p.id, d.notice_softcopy_pdf, app.name AS applicant_name')
                ->from('gz_change_of_gender_master p')
                ->join('gz_change_of_gender_status_master s', 's.id = p.current_status')
                ->join('gz_change_of_gender_document_det d', 'd.gz_master_id = p.id')
                ->join('gz_applicants_details app', 'p.user_id = app.id')
                ->where('p.deleted', 0)
                ->where_in('p.current_status', 10);

        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('p.file_no', $data['file_no']);
        }
        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
            $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_to']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_form']);
            }
        }
        //print_r($this->db->last_query()); exit;
        
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
        }

        public function get_total_cnt_govt_list_publish_change_gender_search($limit, $offset, $data = array()){
        $this->db->select('p.file_no, p.created_at, s.status_name, p.id, d.notice_softcopy_pdf, app.name AS applicant_name')
                ->from('gz_change_of_gender_master p')
                ->join('gz_change_of_gender_status_master s', 's.id = p.current_status')
                ->join('gz_change_of_gender_document_det d', 'd.gz_master_id = p.id')
                ->join('gz_applicants_details app', 'p.user_id = app.id')
                ->where('p.deleted', 0)
                ->where_in('p.current_status', 11);

        if (!empty($data['app_name'])) {
            $this->db->like('app.name', $data['app_name']);
        }
        if (!empty($data['file_no'])) {
            $this->db->like('p.file_no', $data['file_no']);
        }
        if (!empty($data['notice_date_form']) || !empty($data['notice_date_to'])) {
            $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
            $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
        } else {
            if (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_to']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_to']);
            } elseif (!empty($data['notice_date_form']) && !empty($data['notice_date_to'])) {
                $this->db->where('DATE(p.created_at)>=', $data['notice_date_form']);
                $this->db->where('DATE(p.created_at)<=', $data['notice_date_form']);
            }
        }
        //print_r($this->db->last_query()); exit;
        
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
        }

        /*
        * Count of total change of gender submitted(for applicant)
        */

        public function get_total_change_gender_count_applicant() {
            return $this->db->select('id')
                            ->from('gz_change_of_gender_master')
                            ->where('status', 1)
                            ->where('deleted', 0)
                            ->where('user_id', $this->session->userdata('user_id'))
                            ->count_all_results();
        }

        public function get_total_change_gender_count_c_and_t_published($type, $module_id){
            if ($type == 'Verifier') {
                if ($module_id == 1) {

                    return $this->db->select('m.*, s.status_det , u.name')
                                    ->from('gz_change_of_partnership_master m')
                                    ->join('gz_par_sur_status_master s', 's.id = m.cur_status')
                                    ->join('gz_applicants_details u', 'm.user_id = u.id')
                                    ->where('m.cur_status', 17)
                                    ->where('m.deleted', 0)
                                    ->where('m.status', 1)
                                    ->count_all_results();
                } else if ($module_id == 2) {

                    return $this->db->select('m.*, s.status_name as status_det , u.name')
                                    ->from('gz_change_of_name_surname_master m')
                                    ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                                    ->join('gz_applicants_details u', 'u.id = m.user_id')
                                    ->where('m.current_status >=', 2)
                                    ->where('m.deleted', 0)
                                    ->where('m.status',11)
                                    ->count_all_results();
                } else if ($module_id == 6) {

                    return $this->db->select('m.*, s.status_name as status_det , u.name')
                                    ->from('gz_change_of_gender_master m')
                                    ->join('gz_change_of_gender_status_master s', 's.id = m.current_status')
                                    ->join('gz_applicants_details u', 'u.id = m.user_id')
                                    ->where('m.current_status >=', 2)
                                    ->where('m.deleted', 0)
                                    ->where('m.status',11)
                                    ->count_all_results();
                }
            } else if ($type == 'Approver') {
                if ($module_id == 1) {

                    return $this->db->select('m.*, s.status_det')
                                    ->from('gz_change_of_partnership_master m')
                                    ->join('gz_par_sur_status_master s', 's.id = m.cur_status')
                                    ->where('m.cur_status =', 17)
                                    ->where('m.deleted', 0)
                                    ->where('m.status', 1)
                                    ->count_all_results();
                } else if ($module_id == 2) {

                    return $this->db->select('m.*, s.status_name as status_det')
                                    ->from('gz_change_of_name_surname_master m')
                                    ->join('gz_change_of_name_surname_status_master s', 's.id = m.current_status')
                                    ->where('m.current_status >=', 2)
                                    ->where('m.deleted', 0)
                                    ->where('m.status', 11)
                                    ->count_all_results();
                } else if ($module_id == 6) {

                    return $this->db->select('m.*, s.status_name as status_det')
                                    ->from('gz_change_of_gender_master m')
                                    ->join('gz_change_of_gender_status_master s', 's.id = m.current_status')
                                    ->where('m.current_status >=', 2)
                                    ->where('m.deleted', 0)
                                    ->where('m.status', 11)
                                    ->count_all_results();
                }
            } else if ($type == 'Processor') {

                return $this->db->select('m.*, s.status_name as status_det')
                                ->from('gz_change_of_gender_master m')
                                ->join('gz_change_of_gender_status_master s', 's.id = m.current_status')
                            //->where_in('m.current_status', array(1, 6, 12, 13, 8))
                                ->where('m.deleted', 0)
                                ->where('m.status', 11)
                                ->count_all_results();
            }
        }

        public function published_gender_list($limit, $offset) {
            return $this->db->select('m.*, s.status_name, app.name AS applicant_name')
                            ->from('gz_change_of_gender_master m')
                            ->join('gz_change_of_gender_status_master s', 's.id = m.current_status')
                            ->join('gz_applicants_details app', 'm.user_id = app.id')
                            ->where('m.current_status =', 11)
                            ->where('m.deleted', 0)
                            ->where('m.status', 1)
                            ->order_by('id', 'DESC')
                            ->limit($limit, $offset)
                            ->get()->result();
        }

        public function get_total_change_gender_published_count_c_and_t_search($data = array()){
            $this->db->select('m.*, s.status_name, app.name AS applicant_name');
            $this->db->from('gz_change_of_gender_master m');
            $this->db->join('gz_change_of_gender_status_master s', 's.id = m.current_status');
            $this->db->join('gz_applicants_details app', 'm.user_id = app.id');
            //$this->db->where('m.current_status >=', 2);
            $this->db->where('m.deleted', 0);
            $this->db->where('m.current_status =', 11);
            $this->db->where('m.status', 1);
            //$array_items = array('name', 'file_no', 'status', 'notice_date_form', 'notice_date_to');
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
        
            $this->db->order_by('id', 'DESC');
            return $this->db->count_all_results();
        }

        public function change_of_gender_published_search_result($limit, $offset, $data = array()){
            $this->db->select('m.*, s.status_name, app.name AS applicant_name');
            $this->db->from('gz_change_of_gender_master m');
            $this->db->join('gz_change_of_gender_status_master s', 's.id = m.current_status');
            $this->db->join('gz_applicants_details app', 'm.user_id = app.id');
            $this->db->where('m.current_status =', 11);
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

    // Gender Model Part End
}

?>