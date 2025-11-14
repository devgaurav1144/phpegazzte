<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Error Controller class for e-Sign error
 */

class Esign_Error extends CI_Controller {
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

    public function index() {
        $data['title'] = "eSign Error";
        $data['msg'] = $this->input->get('msg');
        // View name
        $data['content'] = 'error404';
        $this->load->view('esign/error', $data);
    }

}

?>