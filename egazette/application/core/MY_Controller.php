<?php

/*
 * Check the loggin time is valid or not
 * if the user has inactivity for sometime
 */

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->database();
        $this->load->library(array('session'));
        $this->load->helper(array('url'));
        $this->session->set_userdata(array(
            'last_visited' => time()
        ));

        $this->not_logged_in();
    }

    private function not_logged_in() {

        $is_logged_in = $this->session->userdata('logged_in');

        // The cookie was not found or has expired
        if (!isset($is_logged_in) || $is_logged_in != true) {
            /* AJAX request check 
             * If it is not a AJAX request then redirects to login page
             */
            if (!$this->input->is_ajax_request()) {
                base_url('user/login');
            } else { // send and JSON message to redirect
                echo json_encode(array(
                    'status' => FALSE,
                    'message' => 'Your session expired. Please, login.',
                    'redirect' => base_url('user/login')
                ));
                exit();
            }
        }
    }

    public function logged() {
        // Request coming from AJAX call
        if ($this->input->is_ajax_request()) {

            //Below last_visited should be updated everytime a page is accessed.
            $lastVisitTime = $this->session->userdata("last_visited");
			
			// SESSION timeout 15 Minutes
            $session_timeout = 15 * 60;

            $secondsInactive = (time() - $lastVisitTime);
            
            if ($secondsInactive >= $session_timeout) {
				// UPDATE the user table data
				$this->db->where('id', $this->session->userdata('user_id'));
				$this->db->update('gz_users', array('is_logged' => 0));
					
				$this->session->sess_destroy();
				
				echo json_encode(array(
					'status' => FALSE,
					'message' => 'Your session expired. Please, login.',
					'redirect' => base_url('user/login')
				));
				
            } else {
				echo json_encode(array(
					'status' => TRUE, 
					'message' => 'You are still logged in.',
					'last' => $lastVisitTime,
					'Inactive' => $secondsInactive,
					'Timeout' => $session_timeout
				));
				
            }
        } else {
            show_404();
        }
    }

}

?>