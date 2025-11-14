<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Extraordinary_poc extends MY_Controller {

    /**
     * __construct function.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'pagination', 'my_pagination'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom'));
        $this->load->model(array('Extraordinary_poc_model','Commerce_transport_department_model'));
        
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('commerce_transport_department/login_ct');
        }
    }
    
    public function processor_index() {
        if (!$this->session->userdata('logged_in') && !$this->session->userdata('is_c&t')) {
            redirect('commerce_transport_department/login_ct');
        } else if (!$this->session->userdata('force_password')) {
            redirect('commerce_transport_department/change_password');
        }

        $user_id = $this->session->userdata('user_id');

        if (!$this->Commerce_transport_department_model->exists($user_id)) {
            $this->session->set_flashdata('error', 'User does not exist');
            redirect('commerce_transport_department/login_ct');
        }
        //$this->output->enable_profiler(true);
        $data['title'] = "Extraordinary Gazette";
        
        $status = [2];
        
        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "extraordinary_poc/processor_index";
        
        $config["total_rows"] = $this->Extraordinary_poc_model->count_total_gazettes($status);

        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 2;
        $config['use_page_numbers'] = TRUE;

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="firstlink">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="lastlink">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li class="nextlink">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = 'Prev';
        $config['prev_tag_open'] = '<li class="prevlink">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="curlink active">';
        $config['cur_tag_close'] = '</li>';

        $config['num_tag_open'] = '<li class="numlink">';
        $config['num_tag_close'] = '</li>';

        $this->my_pagination->initialize($config);

        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        $data['gazettes_published'] = $this->Extraordinary_poc_model->get_total_gazettes_published($config["per_page"], $offset);

        $data['gazettes_unpublished'] = $this->Extraordinary_poc_model->get_total_gazettes_unpublished($status, $config["per_page"], $offset);

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php', $data);
        $this->load->view('extraordinary_poc/index.php', $data);
        $this->load->view('template/footer_applicant.php', $data);
    }
    
    public function verifier_index () {
        if (!$this->session->userdata('logged_in') && !$this->session->userdata('is_c&t')) {
            redirect('commerce_transport_department/login_ct');
        } else if (!$this->session->userdata('force_password')) {
            redirect('commerce_transport_department/change_password');
        }

        $user_id = $this->session->userdata('user_id');

        if (!$this->Commerce_transport_department_model->exists($user_id)) {
            $this->session->set_flashdata('error', 'User does not exist');
            redirect('commerce_transport_department/login_ct');
        }
        $data['title'] = "Extraordinary Gazette";
        
        $status = [1, 2, 4, 7, 11, 14, 16];
        
        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "extraordinary_poc/processor_index";
        
        $config["total_rows"] = $this->Extraordinary_poc_model->count_total_gazettes($status);

        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 2;
        $config['use_page_numbers'] = TRUE;

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="firstlink">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="lastlink">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li class="nextlink">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = 'Prev';
        $config['prev_tag_open'] = '<li class="prevlink">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="curlink active">';
        $config['cur_tag_close'] = '</li>';

        $config['num_tag_open'] = '<li class="numlink">';
        $config['num_tag_close'] = '</li>';

        $this->my_pagination->initialize($config);

        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        $data['gazettes_published'] = $this->Extraordinary_poc_model->get_total_gazettes_published($config["per_page"], $offset);

        $data['gazettes_unpublished'] = $this->Extraordinary_poc_model->get_total_gazettes_unpublished($status, $config["per_page"], $offset);

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php', $data);
        $this->load->view('extraordinary_poc/index.php', $data);
        $this->load->view('template/footer_applicant.php', $data);
    }
    
    public function approver_index () {
        if (!$this->session->userdata('logged_in') && !$this->session->userdata('is_c&t')) {
            redirect('commerce_transport_department/login_ct');
        } else if (!$this->session->userdata('force_password')) {
            redirect('commerce_transport_department/change_password');
        }

        $user_id = $this->session->userdata('user_id');

        if (!$this->Commerce_transport_department_model->exists($user_id)) {
            $this->session->set_flashdata('error', 'User does not exist');
            redirect('commerce_transport_department/login_ct');
        }
        $data['title'] = "Extraordinary Gazette";
        
        $status = [1, 2, 4, 7, 8];
        
        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "extraordinary_poc/processor_index";
        
        $config["total_rows"] = $this->Extraordinary_poc_model->count_total_gazettes($status);

        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 2;
        $config['use_page_numbers'] = TRUE;

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="firstlink">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="lastlink">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li class="nextlink">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = 'Prev';
        $config['prev_tag_open'] = '<li class="prevlink">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="curlink active">';
        $config['cur_tag_close'] = '</li>';

        $config['num_tag_open'] = '<li class="numlink">';
        $config['num_tag_close'] = '</li>';

        $this->my_pagination->initialize($config);

        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        $data['gazettes_published'] = $this->Extraordinary_poc_model->get_total_gazettes_published($config["per_page"], $offset);

        $data['gazettes_unpublished'] = $this->Extraordinary_poc_model->get_total_gazettes_unpublished($status, $config["per_page"], $offset);

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php', $data);
        $this->load->view('extraordinary_poc/index.php', $data);
        $this->load->view('template/footer_applicant.php', $data);
    }

    public function view ($gazette_id) {
        if (!$this->session->userdata('logged_in') && !$this->session->userdata('is_c&t')) {
            redirect('commerce_transport_department/login_ct');
        } else if (!$this->session->userdata('force_password')) {
            redirect('commerce_transport_department/change_password');
        }

        $user_id = $this->session->userdata('user_id');

        if (!$this->Commerce_transport_department_model->exists($user_id)) {
            $this->session->set_flashdata('error', 'User does not exist');
            redirect('commerce_transport_department/login_ct');
        }
        if (!$this->Extraordinary_poc_model->exists($gazette_id)) {
            $this->session->set_flashdata('error', 'The gazette does not exists');
            redirect('extraordinary_poc/processor_index');
        }

        $data['title'] = "View Gazette";

        $data['details'] = $this->Extraordinary_poc_model->get_gazette_details($gazette_id);
        $data['status_list'] = $this->Extraordinary_poc_model->get_gazette_status_lists($gazette_id);
        // echo "<pre>";
        // print_r($data['status_list']);
        // echo "</pre>";
        // exit;
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php', $data);
        $this->load->view('extraordinary_poc/view.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
    
    public function forward_reject () {
        if (!$this->session->userdata('logged_in') && !$this->session->userdata('is_c&t')) {
            redirect('commerce_transport_department/login_ct');
        } else if (!$this->session->userdata('force_password')) {
            redirect('commerce_transport_department/change_password');
        }

        $user_id = $this->session->userdata('user_id');

        if (!$this->Commerce_transport_department_model->exists($user_id)) {
            $this->session->set_flashdata('error', 'User does not exist');
            redirect('commerce_transport_department/login_ct');
        }
        $remarks = $this->security->xss_clean($this->input->post('remarks'));
        $curr_status = $this->security->xss_clean($this->input->post('curr_status'));
        $next_status = $this->security->xss_clean($this->input->post('next_status'));
        $gazette_id = $this->security->xss_clean($this->input->post('gazette_id'));
        // var_dump($this->session->userdata('user_id'));exit;
        $result = $this->Extraordinary_poc_model->forward_reject($remarks, $curr_status, $next_status, $gazette_id);
        
        if ($next_status == 8) {
            $msg = 'Gazette forwarded successfully';
            $redirect = 'extraordinary_poc/processor_index';
        } else if ($next_status == 11) {
            $msg = 'Gazette rejected successfully';
            $redirect = 'extraordinary_poc/processor_index';
        } else if ($next_status == 9) {
            $msg = 'Gazette forwarded successfully';
            $redirect = 'extraordinary_poc/verifier_index';
        } else if ($next_status == 12) {
            $msg = 'Gazette rejected successfully';
            $redirect = 'extraordinary_poc/verifier_index';
        } else if ($next_status == 10) {
            $msg = 'Gazette forwarded successfully';
            $redirect = 'extraordinary_poc/verifier_index';
        } else if ($next_status == 13) {
            $msg = 'Gazette rejected successfully';
            $redirect = 'extraordinary_poc/verifier_index';
        } else if ($next_status == 14 || $next_status == 15) {
            $msg = 'Reject request approved successfully';
            $redirect = 'extraordinary_poc/approver_index';
        } else if ($next_status == 16) {
            $msg = 'Gazette returned to department successfully';
            $redirect = 'extraordinary_poc/processor_index';
        }
        
        if ($result) {
            audit_action_log($this->session->userdata('user_id'), 'Extraordinary Gazette', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            
            $this->session->set_flashdata('success', $msg);
            redirect($redirect);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong');
            redirect($redirect);
        }
    }
}

?>