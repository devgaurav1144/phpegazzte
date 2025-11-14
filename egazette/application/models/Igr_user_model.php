<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php

class Igr_user_model extends CI_Model {

    /*
     * Count of IGR users
     */
    public function get_total_igr_users(){
        return $this->db->select('*')
                        ->from('gz_igr_users')
                        ->where('deleted', 0)
                        ->count_all_results();
    }

    public function update_session_id($user_id, $session_id) {
        $this->db->where('id', $user_id);
        $this->db->update('gz_igr_users', array('session_id' => $session_id));
    }   
    
    /*
     * Get all users in listing
     */
    public function get_all_users($limit, $offset){
        return $this->db->select('*')
                        ->from('gz_igr_users')
                        ->where('deleted', 0)
                        ->order_by('id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }
    
    /*
     * Check whether user exists or not
     */
    public function exists($id) {
        $result = $this->db->select('*')->from('gz_igr_users')->where('id', $id)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    /*
     * delete IGR User
     */
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('gz_igr_users');
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }
    
    /*
     * Check unique_mobile number
     */
    public function check_unique_mobile($mob){
        $result = $this->db->select('*')->from('gz_igr_users')->where('mobile', $mob)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    /*
     * Check unique_email number
     */
    public function check_unique_email($email){
        //echo 
        $result = $this->db->select('*')->from('gz_igr_users')->where('email', $email)->get();
        //echo $this->db->last_query();exit();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    /*
     * Add IGR user to system
     */
    public function add_igr_user($data){
        $array_data = array(
            'user_name' => $data['user_name'],
            'date_of_birth' => $data['dob'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'verify_approve' => $data['veri'],
            'login_id' => $data['login_id'],
            'password' => $data['password'],
            'created_by' => $data['created_by'],
            'created_at' => $data['created_at'],
            'status' => 1,
            'deleted' => 0
        );
        //print_r($array_data); exit();
        $this->db->insert('gz_igr_users', $array_data);
        return $this->db->insert_id();
    }
    
    /*
     * Edit igr user(Load)
     */
    public function get_igr_user_on_edit($id){
        return $this->db->select('*')
                        ->from('gz_igr_users')
                        ->where('id', $id)
                        ->where('deleted', 0)
                        ->get()->row();
    }
    
    /*
     * Edit IGR USER
     */
    public function edit_igr_user($data){
        $array_data = array(
            'user_name' => $data['user_name'],
            'mobile' => $data['mobile'],
            'date_of_birth' => $data['dob'],
            'email' => $data['email'],
            'verify_approve' => $data['veri'],
            'modified_by' => $data['modified_by'],
            'modified_at' => $data['modified_at'],
        );
        $this->db->where('id', $data['id']);
        $this->db->update('gz_igr_users', $array_data);
      
        return ($this->db->affected_rows() == 1) ? true : false;
    }
    
    /*
     * Status Change
     */
    public function status_change($id, $status){
        if($status == 1){
                $stat = 0;
            } else {
              $stat = 1;  
            }
            
            $update_array = array(
                'status' => $stat,
                'modified_by' => $this->session->userdata('user_id'),
                'modified_at' => date("Y-m-d H:i:s", time())
            );
            
            $this->db->where('id', $id);
            $this->db->update('gz_igr_users', $update_array);
            if ( $this->db->affected_rows() ) {
                return TRUE;
            } else {
                return FALSE;
            }
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
     * IGR User data
     */
    public function get_user_data($mobile) {
        return $this->db->select('*')
                        ->from('gz_igr_users')
                        ->where('user_name', $mobile)
                        ->get()->row();
    }
    
    /*
     * IGR User Login
     */
    public function check_mobile_login($mobile, $password) {
        $this->db->select('password')
                ->from('gz_igr_users')
                ->where('user_name', $mobile)
                ->where('status', 1);

        $hash = $this->db->get()->row('password');
        return $this->verify_password_hash($password, $hash);
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
                        ->from('gz_igr_users')
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
            'user_name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
        );

        $this->db->where('id', $data['user_id']);
        $this->db->update('gz_igr_users', $update_data);
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
        $this->db->update('gz_igr_users', $password_array);

        if ($this->db->affected_rows()) {

            // INSERT into password history table
            $this->db->insert('gz_igr_password_history', array(
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
						->from('gz_igr_users')
						->where('mobile', $mobile)
						->where('status', 1)
						->where('deleted', 0)->get()->row();
	
    }

// SMS LIMIT MODEL METHOD START
    public function increment_sms_request_count($mobile) {
        $current_count = $this->db->select('sms_request_count')
                                ->where('mobile', $mobile)
                                ->get('gz_igr_users')
                                ->row();
                                //    echo $current_count->sms_request_count."----".$mobile;
        if ($current_count) {
            $new_count = $current_count->sms_request_count + 1;
            $this->db->where('mobile', $mobile)
                    ->update('gz_igr_users', array('sms_request_count' => $new_count, 'last_sms_request_time' => date('Y-m-d H:i:s')));
        } else {
            return false;
        }
    }
    // Model method to get the blocked user
    public function get_blocked_user($mobile) {
        return $this->db->select('blocked_until')->where('mobile', $mobile)->get('gz_igr_users')->row();
    }

    // Model method to reset SMS request count
    public function reset_sms_request_count($mobile) {
        $this->db->where('mobile', $mobile)->update('gz_igr_users', array('sms_request_count' => 1));
    }


    public function get_sms_request_count($mobile) {
        $this->db->select('sms_request_count');
        $this->db->where('mobile', $mobile);
        $query = $this->db->get('gz_igr_users');
        $result = $query->row();
        return ($result) ? $result->sms_request_count : 0;
    }

    public function is_user_blocked($mobile) {
        $this->db->select('blocked_until');
        $this->db->where('mobile', $mobile);
        $query = $this->db->get('gz_igr_users');
        $result = $query->row();
        return ($result && strtotime($result->blocked_until) > time());
    }

    public function block_user($mobile, $blocked_until) {
        $data = array(
            'blocked_until' => $blocked_until
        );
        $this->db->where('mobile', $mobile);
        $this->db->update('gz_igr_users', $data);
    }
// SMS LIMIT MODEL METHOD END
}

?>