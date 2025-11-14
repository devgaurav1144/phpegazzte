<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Error Controller class
 */

class Error404 extends CI_Controller {
    /*
     * Constructor
     */

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url_helper'));
    }

    /*
     * index action for catyegories to display all categories
     */

    public function error_404() {
        $this->output->set_status_header('404');
        $data['title'] = "404 Page Not Found";
        // View name
        $data['content'] = 'error404';
        $this->load->view('template/error404', $data);
    }


}

?>