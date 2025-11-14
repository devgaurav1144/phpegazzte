<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller {

    /**
     * __construct function.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'form_validation', 'pagination', 'my_pagination'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom'));
        $this->load->model(array('settings_model'));
    }

    public function smtp() {

        if (!$this->session->userdata('logged_in') || !$this->session->userdata('is_admin')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('user/login');
        }

        $data['title'] = "SMTP Settings";
        $smtpDetails = $this->settings_model->get_smtp();

        foreach ($smtpDetails as $smtpValue) {
            $data[$smtpValue->action_key] = $smtpValue->action_value;
        }

        // set form validation rules
        $this->form_validation->set_rules('host', 'Host Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('protocol', 'Protocol Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('port', 'Port Number', 'trim|required|min_length[2]|max_length[100]');

        if ($this->form_validation->run() == false) {
            //$this->load->view('designations/add', $data);
        } else {

            $array_data = array(
                'host' => $this->input->post('host'),
                'protocol' => $this->input->post('protocol'),
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
                'port' => $this->input->post('port'),
            );
            // insert into DB
            $result = $this->settings_model->set_smtp($array_data);
            if ($result) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'SMTP', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'SMTP updated successfully');
                redirect('settings/smtp');
            } else {
                $this->session->set_flashdata('error', 'SMTP is not updated');
                redirect('settings/smtp');
            }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('settings/smtp.php', $data);
        $this->load->view('template/footer.php');
    }

    public function sms_gateway() {
        if (!$this->session->userdata('logged_in') || !$this->session->userdata('is_admin')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('user/login');
        }

        $data['title'] = "SMS Gateway";

        $smsDetails = $this->settings_model->get_sms();

        foreach ($smsDetails as $smsValue) {
            $data[$smsValue->action_key] = $smsValue->action_value;
        }

        // set form validation rules
        $this->form_validation->set_rules('api_key', 'API Key', 'trim|required|min_length[6]|max_length[30]');
        $this->form_validation->set_rules('endpoint_url', 'Endpoint URL', 'trim|required|valid_url|min_length[8]|max_length[100]');
        $this->form_validation->set_rules('sender_id', 'Sender ID', 'trim|required|min_length[4]|max_length[8]');


        if ($this->form_validation->run() == false) {
            //$this->load->view('designations/add', $data);
        } else {
            $array_data = array(
                'api_key' => $this->input->post('api_key'),
                'endpoint_url' => $this->input->post('endpoint_url'),
                'sender_id' => $this->input->post('sender_id'),
            );
            // insert into DB
            $result = $this->settings_model->set_sms($array_data);
            if ($result) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'SMS', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'SMS updated successfully');
                redirect('settings/sms_gateway');
            } else {
                $this->session->set_flashdata('error', 'SMS is not updated');
                redirect('settings/sms_gateway');
            }
        }


        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('settings/sms_gateway.php', $data);
        $this->load->view('template/footer.php');
    }

    public function payment_gateway() {

        if (!$this->session->userdata('logged_in') || !$this->session->userdata('is_admin')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('user/login');
        }

        $data['title'] = "Payment Gateway";

        $payDetails = $this->settings_model->get_paygat();

        foreach ($payDetails as $payValue) {
            $data[$payValue->action_key] = $payValue->action_value;
        }

        // set form validation rules
        $this->form_validation->set_rules('api_key', 'API Key', 'trim|required|min_length[8]|max_length[80]');
        $this->form_validation->set_rules('pay_token', 'Token', 'trim|required|min_length[8]|max_length[80]');
        $this->form_validation->set_rules('pay_salt', 'Salt', 'trim|required|min_length[8]|max_length[80]');


        if ($this->form_validation->run() == false) {
            //$this->load->view('designations/add', $data);
        } else {
            $array_data = array(
                'api_key' => $this->input->post('api_key'),
                'pay_token' => $this->input->post('pay_token'),
                'pay_salt' => $this->input->post('pay_salt'),
            );
            // insert into DB
            $result = $this->settings_model->set_paygat($array_data);
            if ($result) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Payment Gateway', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'Payment Gateway updated successfully');
                redirect('settings/payment_gateway');
            } else {
                $this->session->set_flashdata('error', 'Payment Gateway is not updated');
                redirect('settings/payment_gateway');
            }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('settings/payment_gateway.php', $data);
        $this->load->view('template/footer.php');
    }

}

?>