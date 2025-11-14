
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/vendor/autoload.php';

class TestGaurav extends MY_Controller {
    public function __construct() {
        parent::__construct();            
    }

     public function index() {
        $data =[];
        $this->load->view('gaurav/gaurav', $data);      
    }
}