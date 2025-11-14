<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php

/*
 * Reports Controller
 */

class Reports extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'form_validation', 'pagination', 'my_pagination'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom'));
        $this->load->model(array('gazette_model', 'user_model'));
    }
   
    public function published() {
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
                redirect('applicants_login/index');
            }
            $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
            redirect('user/login');
        }
        
        $data['title'] = "Gazette Reports";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "reports/published";
        $config["total_rows"] = $this->gazette_model->count_total_published_gazettes($this->session->userdata('user_id'));

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

        if ($this->session->userdata('is_admin')) {
            $data['gazettes_published'] = $this->gazette_model->getGazettePublishedList($config["per_page"], $offset);
        } else {
            $data['gazettes_unpublished'] = $this->gazette_model->getDeptGazetteUnpublishedList($this->session->userdata('user_id'), $config["per_page"], $offset);
        }


        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('reports/published.php', $data);
        $this->load->view('template/footer.php');
    }

    public function unpublished() {
        
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
                redirect('applicants_login/index');
            }
            $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
            redirect('user/login');
        }
        
        $data['title'] = "Unpublished Gazette Reports";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "reports/unpublished";
        $config["total_rows"] = $this->gazette_model->count_total_unpublished_gazettes($this->session->userdata('user_id'));

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

        if ($this->session->userdata('is_admin')) {
            $data['gazettes_unpublished'] = $this->gazette_model->getGazetteUnpublishedList($config["per_page"], $offset);
        } else {

            $data['gazettes_unpublished'] = $this->gazette_model->getDeptGazetteUnpublishedList($this->session->userdata('user_id'), $config["per_page"], $offset);
        }


        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('reports/unpublished.php', $data);
        $this->load->view('template/footer.php');
    }

    /*     * ************************* */

    public function extraordinary_report() {
        
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
                redirect('applicants_login/index');
            }
            $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
            redirect('user/login');
        }
        
        if (!$this->session->userdata('force_password')) {
            // If the user hasn't changed their password, redirect to the change password page
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }
        
        
        $data['title'] = "Notification Reports";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "reports/extraordinary_report";

        $config["total_rows"] = $this->gazette_model->count_total_record_extraordinary();
        
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

        $data['gazette_type'] = $this->gazette_model->get_gazette_types();
        $data['notification_type'] = $this->gazette_model->get_notification_types();
        $data['department_type'] = $this->gazette_model->get_department_types();
        $data['gz_status'] = $this->gazette_model->get_status();
        $data['notification_report_details'] = $this->gazette_model->notification_default_report($config["per_page"], $offset);

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('reports/notification_report.php', $data);
        $this->load->view('template/footer.php');
    }

    public function extraordinary_report_result() {
        
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
                redirect('applicants_login/index');
            }
            $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
            redirect('user/login');
        }
        
        $data['title'] = "Notification Report Result";

        $config = array();
        $config["base_url"] = base_url() . "reports/extraordinary_report_result";

        $inputs = $this->input->post();
        
        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        } else{
            if($page == 0){
            $array_items = array('fdate', 'tdate', 'subline', 'dept', 'odrNo', 'nType', 'statusType');
            $this->session->unset_userdata($array_items);
            $inputs = array();
            } else {
            $inputs = $this->session->userdata();
            }
        }
        
        $config["total_rows"] = $this->gazette_model->count_total_record_extraordinary_search($inputs);

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

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        // Pagination Configuration
        $data['gazette_type'] = $this->gazette_model->get_gazette_types();
        $data['notification_type'] = $this->gazette_model->get_notification_types();
        $data['department_type'] = $this->gazette_model->get_department_types();
        $data["inputs"] = $inputs;
        $data['gz_status'] = $this->gazette_model->get_status();
        $data['notification_report_details'] = $this->gazette_model->notification_report( $config["per_page"],$offset, $inputs);

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('reports/notification_report_result.php', $data);
        $this->load->view('template/footer.php');
    }

    public function weekly_report_result() {
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
                redirect('applicants_login/index');
            }
            $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
            redirect('user/login');
        }
        
        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }

        $data['title'] = "Notification Report Result";

        $inputs = $this->input->post();
        
        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        } else{
            if($page == 0){
            $array_items = array('fdate', 'tdate', 'dept', 'partType', 'monthName', 'weekTime', 'statusType');
            $this->session->unset_userdata($array_items);
            $inputs = array();
            } else {
            $inputs = $this->session->userdata();
            }
        }

        $config = array();
        $config["base_url"] = base_url() . "reports/weekly_report_result";
        $config["total_rows"] = $this->gazette_model->count_total_record_weekly($inputs);

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

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        // Pagination Configuration
        $data['gazette_type'] = $this->gazette_model->get_gazette_types();
        $data['notification_type'] = $this->gazette_model->get_notification_types();
        $data['department_type'] = $this->gazette_model->get_department_types();
        $data['inputs'] = $inputs;
        $data['gz_parts'] = $this->gazette_model->get_parts();
        $data['gz_status'] = $this->gazette_model->get_status();
        $data['weekly_report_details'] = $this->gazette_model->weekly_report($config["per_page"], $offset, $inputs);
        
        //print_r($data['notification_report_details']);
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('reports/weekly_report.php', $data);
        $this->load->view('template/footer.php');
    }

}

?>