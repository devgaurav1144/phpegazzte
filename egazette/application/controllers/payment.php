<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {

    public function index() {
        $this->load->view('applicants_login/fees_view');
    }

    public function gateway() {
        // Here you can integrate Razorpay / Paytm / Stripe / etc.
        echo "<h2>Redirecting to Payment Gateway...</h2>";
    }
}
