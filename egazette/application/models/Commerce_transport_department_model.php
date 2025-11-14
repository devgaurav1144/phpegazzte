<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php

class Commerce_transport_department_model extends CI_Model {

    /*
     * liast of hod 
     */
    public function get_c_t_list($limit, $offset) {
        return $this->db->select('c.*,m.module_name')
                        ->from('gz_c_and_t c')
                        ->join('gz_modules  m','c.module_id = m.id','LEFT')
                        ->order_by('c.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    public function update_session_id($user_id, $session_id) {
        $this->db->where('id', $user_id);
        $this->db->update('gz_c_and_t', array('session_id' => $session_id));
    }

    /*
     * add hod 
     */
    public function add_c_t_users($data = array()) {
        $user_det = $this->db->select('*')
                        ->from('gz_c_and_t ')
                        ->where('mobile', $data['mobile'])
                        ->where('email', $data['email'])
                        ->where('status', 1)
                        ->count_all_results();
        //echo $user_det;exit();
        
        
        if($user_det == 0)  { 
            $array_data = array(
                'name' => $data['name'],
                'user_name' => $data['user_name'],
                'emp_id' => $data['emp_id'],
                'mobile' => $data['mobile'],
                'email' => $data['email'],
                'dob' => '',
                'module_id' => $data['module_id'],
                'login_id' => $data['login_id'],
                'password' => $data['password'],
                'verify_approve' => $data['ver_app'],
                'created_by' => $data['created_by'],
                'created_at' => $data['created_at'],
                'status' => 1,
                'deleted' => 0
            );

            if($this->db->insert('gz_c_and_t', $array_data)) {
               return "C&T user added successfully."; 
            } else {
                 return "C&T user added not successfully."; 
            }
           
        } else {
            return "C&T already exits."; 
        }
    }



/*
 * count all hod for pagination
 * 
 */
    public function get_total_c_t() {
        return $this->db->select('*')
                        ->from('gz_c_and_t')
                        ->count_all_results();
    }
    


   /*
    * get hod details on edit
    */
    public function getc_tDetails($id) {
        return $this->db->select('*')
                        ->from('gz_c_and_t h')
                        ->where('id',$id)
                        ->get()->row();
    }
    
    /*
     * update hod 
     */
    public function update_candt_users($data = array()) {
        
        $array_data = array(
            'name' => $data['name'],
            'user_name' => $data['user_name'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'dob' => '',
            'module_id' => $data['module_id'],
            'verify_approve' => $data['ver_app'],
            'created_by' => $data['modified_by'],
            'created_at' => $data['modified_at'],
            'status' => 1,
            'deleted' => 0
        );
        $this->db->where('id', $data['c_t_id']);
        $this->db->update('gz_c_and_t', $array_data);
      
        return ($this->db->affected_rows() == 1) ? true : false;
        
    }
    
    /*
     * check hod exits or not 
     */
    public function exists($id) {
        $result = $this->db->select('*')->from('gz_c_and_t')->where('id', $id)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    /*
     * delete hod
     */
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('gz_c_and_t');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }
    /*
     * Change status of hod
     */
    public function c_t_status($id, $status) {
        
		if ($status == 1){
			$update_status = 0;
		} else {
			$update_status = 1;	
		}
		
        $upd_arr = array(
                'status' => $update_status,
                'modified_by' => $this->session->userdata('user_id'),
                'modified_at' => date('Y-m-d H:i:s', time())
        );
        // update the users table
        $this->db->where('id', $id);
        $this->db->update('gz_c_and_t', $upd_arr);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    
       /*
     * Check mobile unique
     */

    public function check_mobile_unique($mobile) {
        $result = $this->db->select('*')->from('gz_c_and_t')
                        ->where('mobile', $mobile)
                        ->where('deleted', 0)->get();
        return ($result->num_rows() > 0) ? true : false;
    }

    
         /*
     * Check email unique
     */

    public function check_email_unique($email) {
        $result = $this->db->select('*')->from('gz_c_and_t')
                        ->where('email', $email)
                        ->where('deleted', 0)->get();
        return ($result->num_rows() > 0) ? true : false;
    }


    /*
     * Check Module Exist For Edit
     */
    // public function check_module_exist($module_id,$email) {
    //     $result = $this->db->select('*')->from('gz_c_and_t')
    //                        ->where('module_id', $module_id)
    //                        ->where('email', $email)
    //                        ->where('deleted', 0)->get();
    //                        echo $this->db->last_query();
    //                          die;
    //     return ($result->num_rows() > 0) ? true : false;
    // }
    
       /*
    * load of list of module
    */
  
    public function get_module_List() {
        return $this->db->select('*')
                        ->from('gz_modules')
                        ->order_by('module_name', 'ASC')
                        ->get()->result();
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
     * CT user's Login
     */
    public function check_mobile_login($mobile, $password) {
       
    //    $this->db->select('password')
    //             ->from('gz_c_and_t')
    //             ->where('user_name', $mobile)
    //             ->where('status', 1);

    $this->db->select('password')
                ->from('gz_c_and_t')
                ->where('user_name', $mobile)
                ->where('status', 1);
         
        $hash = $this->db->get()->row('password');
        // echo $hash;
        // echo $password;
        //  echo $this->verify_password_hash($password, $hash);exit("Worked");
        return $this->verify_password_hash($password, $hash);
    }
    
        /*
     *  CT user's data
     */
    public function get_user_data($mobile) {
        return $this->db->select('*')
                        ->from('gz_c_and_t')
                        ->where('user_name', $mobile)
                        ->get()->row();
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
    
       
    
    /*
     * Get IGR user details for profile
     */
    public function get_user_details($user_id){
        return $this->db->select('*')
                        ->from('gz_c_and_t')
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->where('id', $user_id)
                        ->get()->row();
    }
    
    /*
     * IGR User change profile
     */
    public function update_user_profile($data) {
        $update_data = array(
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
        );

        $this->db->where('id', $data['user_id']);
        $this->db->update('gz_c_and_t', $update_data);
        
        //echo $this->db->last_query();exit();
        
        if ($this->db->affected_rows() >= 0) {
            return true;
        } else {
            return false;
        }
    }
    
    /*
     * Change password
     */
    public function update_password($data = array()) {
        // store the password data into users table
        $password_array = array(
            'password' => $data['password'],
            'modified_at' => $data['modified_at'],
            'force_password' => $data['force_password']
        );

        // UPDATE into user otp table
        $this->db->where('id', $data['user_id']);
        $this->db->update('gz_c_and_t', $password_array);

        if ($this->db->affected_rows()) {

            // INSERT into password history table
            $this->db->insert('gz_c_and_t_password_history', array(
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

	   /*
     * CT user mobile number exists
     */
    public function check_mobile_exists($mobile) {
        return $this->db->select('mobile')
						->from('gz_c_and_t')
						->where('mobile', $mobile)
						->where('status', 1)
						->where('deleted', 0)->get()->row();
	
    }

// SMS LIMIT MODEL METHOD START
    public function increment_sms_request_count($mobile) {
        $current_count = $this->db->select('sms_request_count')
                                ->where('mobile', $mobile)
                                ->get('gz_c_and_t')
                                ->row();
                                //    echo $current_count->sms_request_count."----".$mobile;
        if ($current_count) {
            $new_count = $current_count->sms_request_count + 1;
            $this->db->where('mobile', $mobile)
                    ->update('gz_c_and_t', array('sms_request_count' => $new_count, 'last_sms_request_time' => date('Y-m-d H:i:s')));
        } else {
            return false;
        }
    }
    // Model method to get the blocked user
    public function get_blocked_user($mobile) {
        return $this->db->select('blocked_until')->where('mobile', $mobile)->get('gz_c_and_t')->row();
    }

    // Model method to reset SMS request count
    public function reset_sms_request_count($mobile) {
        $this->db->where('mobile', $mobile)->update('gz_c_and_t', array('sms_request_count' => 1));
    }


    public function get_sms_request_count($mobile) {
        $this->db->select('sms_request_count');
        $this->db->where('mobile', $mobile);
        $query = $this->db->get('gz_c_and_t');
        $result = $query->row();
        return ($result) ? $result->sms_request_count : 0;
    }

    public function is_user_blocked($mobile) {
        $this->db->select('blocked_until');
        $this->db->where('mobile', $mobile);
        $query = $this->db->get('gz_c_and_t');
        $result = $query->row();
        return ($result && strtotime($result->blocked_until) > time());
    }

    public function block_user($mobile, $blocked_until) {
        $data = array(
            'blocked_until' => $blocked_until
        );
        $this->db->where('mobile', $mobile);
        $this->db->update('gz_c_and_t', $data);
    }
// SMS LIMIT MODEL METHOD END
 
}

?>
