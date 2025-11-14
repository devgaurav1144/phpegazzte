<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gazette_publish extends MY_Controller {

    /**
     * __construct function.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'form_validation'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom'));
        $this->load->model(array('gazette_publish_model'));
    }

    public function index() {

        if (!$this->session->userdata('logged_in') || !$this->session->userdata('is_admin')) {
            if ($this->session->userdata('is_c&t') || $this->session->userdata('is_igr') || $this->session->userdata('is_applicant')) {
                if ($this->session->userdata('is_c&t')) {
                    $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
                    redirect('commerce_transport_department/login_ct');
                } else if ($this->session->userdata('is_igr')) {
                    $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
                    redirect('igr_user/login');
                } else if ($this->session->userdata('is_applicant')) {
                    $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
                    redirect('applicants_login/index');
                }
            } else {
                $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
                redirect('user/login');
            }
            $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
            redirect('user/login');
        }
        $data['title'] = "Gazette Publish";

        $data['details'] = $this->gazette_publish_model->get_publish_date();

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('gazette_publish/index.php', $data);
        $this->load->view('template/footer.php');
    }

    public function update() {

        if (!$this->session->userdata('logged_in') || !$this->session->userdata('is_admin')) {
            if ($this->session->userdata('is_c&t') || $this->session->userdata('is_igr') || $this->session->userdata('is_applicant')) {
                if ($this->session->userdata('is_c&t')) {
                    $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
                    redirect('commerce_transport_department/login_ct');
                } else if ($this->session->userdata('is_igr')) {
                    $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
                    redirect('igr_user/login');
                } else if ($this->session->userdata('is_applicant')) {
                    $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
                    redirect('applicants_login/index');
                }
            } else {
                $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
                redirect('user/login');
            }
            $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
            redirect('user/login');
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data['details'] = $this->gazette_publish_model->get_publish_date();

            $this->form_validation->set_rules('day_name', 'Day Name', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('gazette_publish/index.php');
            } else {

                $day_name = $this->input->post('day_name');

                $result = $this->gazette_publish_model->update($day_name);

                if ($result) {

                    // Store Audit Log
                    audit_action_log($this->session->userdata('user_id'), 'Gazette Publish', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                    $this->session->set_flashdata('success', 'Gazette published day updated successfully');
                    redirect('Gazette_publish/index');
                } else {
                    $this->session->set_flashdata('error', 'Gazette published day not updated');
                    redirect('Gazette_publish/index');
                }
            }

            $this->load->view('template/header.php');
            $this->load->view('template/sidebar.php');
            $this->load->view('gazette_publish/index.php', $data);
            $this->load->view('template/footer.php');
        } else {

            $this->session->set_flashdata('error', 'The request type is not allowed');
            redirect('gazette_publish/index');
        }
    }

}

?>