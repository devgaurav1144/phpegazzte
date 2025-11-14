<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Custom_logger {
    
    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        // Load any resources you need
        $this->CI->load->library('session');
        // You can also load other libraries, models, or helpers here
        $this->CI->load->helper('url'); // For base_url() function (optional)
    }

    public function log($message) {
        // Log the message using CodeIgniter's logging system
        log_message('info', $message);
        // This will log the message with the 'info' severity level
    }
}
