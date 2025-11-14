<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php

class User_model extends CI_Model {


    public function getUserList() {
        return $this->db->select('*')
                        ->from('gz_gazette')
                        ->order_by('id', 'DESC');
    }

    public function check_mobile_login($mobile, $password) {
        $this->db->select('password')
                ->from('gz_users')
                ->where('username', $mobile)
                ->where('status', 1)
                ->where('is_verified', 1);

                //var_dump($mobile, $password);exit;

        $hash = $this->db->get()->row('password');
        return $this->verify_password_hash($password, $hash);
    }

    public function update_session_id($user_id, $session_id) {
        $this->db->where('id', $user_id);
        $this->db->update('gz_users', array('session_id' => $session_id));
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
     * Verify Password Hash
     * @access private
     * @param mixed $password
     * @param mixed $hash
     * @return bool
     */

    public function verify_password_hash($password, $hash) {
        return password_verify($password, $hash);
    }

    public function get_user_data($mobile) {
        return $this->db->select('*')
                        ->from('gz_users')
                        ->where('username', $mobile)
                        ->get()->row();
    }

    public function get_users_list($limit, $offset, $data = array()) {
        $this->db->select('usr.*, dpt.department_name')
                        ->from('gz_users usr')
                        ->join('gz_department dpt', 'usr.dept_id = dpt.id');

        if (!empty($data['dept'])) {
            $this->db->where('usr.dept_id', $data['dept']);
        }

        $this->db->order_by('usr.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function update_password($data = array()) {
        // store the password data into users table
        $password_array = array(
            'password' => $data['password'],
            'modified_at' => $data['modified_at'],
            'force_password' => $data['force_password']
        );

        // UPDATE into user otp table
        $this->db->where('id', $data['user_id']);
        $this->db->update('gz_users', $password_array);

        if ($this->db->affected_rows()) {

            // INSERT into password history table
            $this->db->insert('gz_user_password_history', array(
                'user_id' => $data['user_id'],
                'password' => $data['password'],
                'created_at' => $data['modified_at'],
                'force_password' => $data['force_password']
                    )
            );

            return true;
        } else {
            return false;
        }
    }

    public function add_dept_user($data = array()) {
        $array_data = array(
            'login_ID' => $data['login_ID'],
            'name' => $data['name'],
            'designation' => $data['designation'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => $data['password'],
            'dept_id' => $data['dept_id'],
            'is_admin' => 0,
            'created_at' => date('Y-m-d H:i:s', time()),
            'status' => 1,
            'is_verified' => 1,
            'force_password' => 0
        );

        // Store into the User table
        $this->db->insert('gz_users', $array_data);

        $user_id = $this->db->insert_id();

        $pwd_hst_array = array(
            'user_id' => $user_id,
            'password' => $data['password'],
            'created_by' => $data['created_by'],
            'created_at' => date('Y-m-d H:i:s', time())
        );

        // store the password in password history table
        $this->db->insert('gz_user_password_history', $pwd_hst_array);
    }

    public function edit_dept_user($data = array()) {
        $array_data = array(
            'name' => $data['name'],
            'designation' => $data['designation'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'username' => $data['username'],
            'dept_id' => $data['dept_id'],
            'gpf_no' => $data['gpf_no'],
            'modified_at' => $data['modified_at']
        );

        $this->db->where('id', $data['id']);
        $this->db->update('gz_users', $array_data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function check_valid_otp($otp, $user_id) {
        // current time
        $current_time = date('Y-m-d H:i:s', time());
        // get the otp from table 
        $otp_result = $this->db->select('*')
                ->from('gz_user_otp')
                ->where('user_id', $user_id)
                ->get();

        if ($otp_result->num_rows() > 0) {
            // get the otp from table 
            $otp_data = $this->db->select('*')
                            ->from('gz_user_otp')
                            ->where('user_id', $user_id)
                            ->get()->row();

            if (($otp_data->otp === $otp) && ($current_time < $otp_data->expired_at)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('gz_users');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function exists($id) {
        $result = $this->db->select('*')->from('gz_users')->where('id', $id)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function linked_with_user($id) {
        $result = $this->db->select('*')->from('gz_users')->where('dept_id', $id)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_total_dept_users($data = array()) {
        $this->db->select('usr.*, dpt.department_name')
                        ->from('gz_users usr')
                        ->join('gz_department dpt', 'usr.dept_id = dpt.id')
                        ->where('usr.is_admin', 0);

        if (!empty($data['dept'])) {
            $this->db->where('usr.dept_id', $data['dept']);
        }
        return $this->db->count_all_results();
        
    }

    public function get_dept_name($user_id) {
        //die($user_id);
        return $this->db->select('dept.department_name, usr.name, usr.designation')
                        ->from('gz_users usr')
                        ->join('gz_department dept', 'usr.dept_id = dept.id', 'LEFT')
                        ->where('usr.id', $user_id)
                        ->get()->row();
    }

    public function get_user_details($user_id) {
        return $this->db->select('usr.*, dept.department_name')
                        ->from('gz_users usr')
                        ->join('gz_department dept', 'usr.dept_id = dept.id', 'LEFT')
                        ->where('usr.id', $user_id)->get()->row();
    }

    public function update_user_profile($data) {
        $update_data = array(
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile']
        );

        $this->db->where('id', $data['user_id']);
        $this->db->update('gz_users', $update_data);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function check_email_exists($email) {
        $result = $this->db->select('*')->from('gz_users')->where('email', $email)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_user_email_data($email) {
        return $this->db->select('*')->from('gz_users')->where('email', $email)->get()->row();
    }

    public function get_user_details_data($id) {
        return $this->db->select('*')->from('gz_users')->where('id', $id)->get()->row();
    }

    public function register_dept_user($data = array()) {
        $array_data = array(
            'login_ID' => $data['login_ID'],
            'name' => $data['name'],
            'designation' => $data['designation'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => $data['password'],
            'dept_id' => $data['dept_id'],
            'is_admin' => 0,
            'gpf_no' => $data['gpf_no'],
            'created_at' => date('Y-m-d H:i:s', time()),
            'status' => 0,
            'is_verified' => 0
        );

        $this->db->insert('gz_users', $array_data);
        $user_id = $this->db->insert_id();

        $admins = $this->db->from('gz_users')
                                ->where('is_admin', '1')   
                                ->where('status', '1')   
                                ->get()->row();

                $array_data_no = array(
                    'master_id' => $par_id,
                    'module_id' => 1,
                    'user_id' => 0,
                    'responsible_user_id' => $admins->id,
                    'text' => 'Nodal officer registered successfully',
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'deleted' => 0,
                    'status' => 1
                );

                $this->db->insert('gz_notification_govt', $array_data_no);

        // INSERT into password history table
        $this->db->insert('gz_user_password_history', array(
            'user_id' => $user_id,
            'password' => $data['password'],
            'created_at' => date('Y-m-d H:i:s', time())
                )
        );

        return $user_id;
    }

    public function check_dept_user_exists($dept_id) {
        $result = $this->db->select('*')->from('gz_users')->where('dept_id', $dept_id)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function account_approval($id, $status) {
        $user_stat = 0;
        $verified = 0;
        if ($status == 1) {
            $user_stat = 0;
            $verified = 0;
        } else {
            $user_stat = 1;
            $verified = 1;
        }

        // update the users table
        $this->db->where('id', $id);
        $this->db->update('gz_users', array('status' => $user_stat, 'is_verified' => $verified));

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function account_rejected($data) {
        // update the users table
        $this->db->where('id', $data['id']);
        $this->db->update('gz_users', array('status' => 0, 'is_verified' => 0, 'reject_remarks' => $data['reject_remarks']));

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    
    public function update_mail_password($data = array()) {
        // update the users table
        $this->db->where('id', $data['user_id']);
        $this->db->update('gz_users', array(
                'password' => $data['password'],
                'modified_at' => $data['modified_at']
            )
        );
        
        // INSERT into password history table
        $this->db->insert('gz_user_password_history', array(
            'user_id' => $data['user_id'],
            'password' => $data['password'],
            'created_at' => date('Y-m-d H:i:s', time())
                )
        );

        return ($this->db->affected_rows() > 0) ? true : false;
    }

    public function get_department_types() {
        return $this->db->select('*')->from('gz_department')
                        ->order_by('department_name', 'ASC')
                        ->get()->result();
    }

    // SMS count, user block, reset sms section start
        public function increment_sms_request_count($user_id) {
            $current_count = $this->db->select('sms_request_count')
                                    ->where('id', $user_id)
                                    ->get('gz_users')
                                    ->row();
                                    //    echo $current_count->sms_request_count."----".$user_id;
            if ($current_count) {
                $new_count = $current_count->sms_request_count + 1;
                $this->db->where('id', $user_id)
                        ->update('gz_users', array('sms_request_count' => $new_count, 'last_sms_request_time' => date('Y-m-d H:i:s')));
            } else {
                return false;
            }
        }
        // Model method to get the blocked user
        public function get_blocked_user($user_id) {
            return $this->db->select('blocked_until')->where('id', $user_id)->get('gz_users')->row();
        }

        // Model method to reset SMS request count
        public function reset_sms_request_count($user_id) {
            $this->db->where('id', $user_id)->update('gz_users', array('sms_request_count' => 1));
        }


        public function get_sms_request_count($user_id) {
            $this->db->select('sms_request_count');
            $this->db->where('id', $user_id);
            $query = $this->db->get('gz_users');
            $result = $query->row();
            return ($result) ? $result->sms_request_count : 0;
        }

        public function is_user_blocked($user_id) {
            $this->db->select('blocked_until');
            $this->db->where('id', $user_id);
            $query = $this->db->get('gz_users');
            $result = $query->row();
            return ($result && strtotime($result->blocked_until) > time());
        }

        public function block_user($user_id, $blocked_until) {
            $data = array(
                'blocked_until' => $blocked_until
            );
            $this->db->where('id', $user_id);
            $this->db->update('gz_users', $data);
        }
    // SMS count, user block, reset sms section end

    /*
     * Check whether user exists or not
     */
   // working on it ...........
    // public function existing_user($id) {
    //     if (!is_numeric($id)) {
    //         log_message('error', 'Invalid ID: ' . $id);
    //         return false;
    //     }
    //     $result = $this->db->select('*')->from('gz_users')->where('id', $id)->limit(1)->get();
    //     log_message('debug', 'User exists query: ' . $this->db->last_query());
    //     return $result->num_rows() > 0;
    // }

}

?>