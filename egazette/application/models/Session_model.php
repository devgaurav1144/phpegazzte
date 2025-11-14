<?php
class Session_model extends CI_Model {
    
    public function updateSessionId($mobile) {
        // Update session_id to 1 for the user
        $this->db->where('mobile', $mobile);
        $this->db->update('gz_applicants_details', array('session_id' => 1));
    }

    public function checkAlreadyLoggedIn($mobile) {
        // Check if the user is already logged in from another device
        $this->db->where('mobile', $mobile);
        $this->db->where('session_id', 1);
        $query = $this->db->get('gz_applicants_details');

        return ($query->num_rows() > 0);
    }

    public function logoutOtherDevices($mobile) {
        // Log out the user from their previous device
        $this->db->where('mobile', $mobile);
        $this->db->where('session_id', 1);
        $this->db->update('gz_applicants_details', array('session_id' => 0));
    }
}