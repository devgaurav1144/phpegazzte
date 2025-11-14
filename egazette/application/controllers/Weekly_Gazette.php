<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/vendor/autoload.php';

use DocxMerge\DocxMerge;

class Weekly_Gazette extends MY_Controller {

    private $doc_file = '';
    private $pdf_file = '';
    private $press_doc_file = '';
    private $press_pdf_file = '';
    private $press_pdf_file_size = '';

    /**
     * __construct function.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'form_validation', 'email', 'pagination', 'my_pagination'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom'));
        $this->load->model(array('weekly_model', 'user_model', 'gazette_type_model'));
        
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Session has been expired');
            redirect('user/login');
        }
    }

    /*
     * Dept. Weekly gazette list
     */

    public function index() {
        // if (!$this->session->userdata('logged_in')) {
        //     $this->session->set_flashdata('error', 'Session has been expired');
        //     redirect('user/login');
        // }
        if ($this->session->userdata('logged_in')) {
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
            } 
        }
        else{
            redirect('user/login');
        }

        
        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }
        // echo 'test';
        // exit;
        // $id = $this->session->userdata('user_id');
        // if (!is_numeric($id) || !$this->user_model->existing_user($id)) {
        //     $this->session->set_flashdata('error', 'User does not exists');
        //     redirect('user/login');
        // }
        //$this->output->enable_profiler(true);
        $data['title'] = "Pending Weekly Gazette";

        $inputs = $this->input->post();
        
        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        } else{
            if($page == 0){
              $array_items = array('weekTime', 'statusType', 'dept', 'year');
              $this->session->unset_userdata($array_items);
              $inputs = array();
            } else {
              $inputs = $this->session->userdata();
            }
        }

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "weekly_gazette/index";
        $config["total_rows"] = $this->weekly_model->count_total_unpublished_gazettes($this->session->userdata('user_id'), $inputs);

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

        $data['department_type'] = $this->weekly_model->get_department_types();
        $data['gz_status'] = $this->weekly_model->get_status();
        $data['all_year'] = $this->weekly_model->getYearList($this->session->userdata('user_id'));
        $data['all_week'] = $this->weekly_model->getWeekList($this->session->userdata('user_id'));

        if ($this->session->userdata('is_admin')) {
            $data['gazettes_unpublished'] = $this->weekly_model->getGazetteUnpublishedList($config["per_page"], $offset, $inputs);
        } else {

            $data['dept_name'] = $this->user_model->get_dept_name($this->session->userdata('user_id'));
            
            $data['gazettes_unpublished'] = $this->weekly_model->getDeptGazetteUnpublishedList($this->session->userdata('user_id'), $config["per_page"], $offset, $inputs);
        }

        $data["inputs"] = $inputs;
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('weekly_gazette/index.php', $data);
        $this->load->view('template/footer.php');
    }

    public function published_wk_gz() {
        if ($this->session->userdata('logged_in')) {
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
            } 
        }
        else{
            redirect('user/login');
        }


        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }
        //$this->output->enable_profiler(true);
        $data['title'] = "Published Weekly Gazette";
        $inputs = $this->input->post();

        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        } else{
            if($page == 0) {
              $array_items = array('weekTime', 'statusType', 'dept', 'year');
              $this->session->unset_userdata($array_items);
              $inputs = array();
            } else {
              $inputs = $this->session->userdata();
            }
        }

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "weekly_gazette/published_wk_gz";

        if ($this->session->userdata('is_admin')) {
            $config["total_rows"] = $this->weekly_model->total_count_published_weekly_gazette_Govt_Press($this->session->userdata('user_id'), $inputs);
        } else {
            $config["total_rows"] = $this->weekly_model->total_count_published_weekly_gazette_Govt_Press($this->session->userdata('user_id'), $inputs);
        }
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

        $data['department_type'] = $this->weekly_model->get_department_types();
        $data['all_year'] = $this->weekly_model->getYearList($this->session->userdata('user_id'));
        $data['all_week'] = $this->weekly_model->getWeekList($this->session->userdata('user_id'));
        $data['gz_status'] = $this->weekly_model->get_status();

        if ($this->session->userdata('is_admin')) {
            $data['gazettes_published'] = $this->weekly_model->getFinalPublishedGazette_List_Govt_Press($config["per_page"], $offset, $inputs);
        } else {
            $data['dept_name'] = $this->user_model->get_dept_name($this->session->userdata('user_id'));

            $data['gazettes_published'] = $this->weekly_model->get_department_weekly_published_gazettes($this->session->userdata('user_id'), $config["per_page"], $offset, $inputs);
        }

        $data["inputs"] = $inputs;
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('weekly_gazette/pub_wk_gz.php', $data);
        $this->load->view('template/footer.php');
    }

    public function merged_wk_gz() {
        if ($this->session->userdata('logged_in')) {
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
            } 
        }
        else{
            redirect('user/login');
        }


        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }
        //$this->output->enable_profiler(true);
        $data['title'] = "Weekly Gazette";
        $inputs = $this->input->post();
        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "weekly_gazette/merged_wk_gz";
        $config["total_rows"] = $this->weekly_model->count_total_unpublished_gazettes($this->session->userdata('user_id'));

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

        $data['department_type'] = $this->weekly_model->get_department_types();

        //$data['gz_parts'] = $this->gazette_model->get_parts();
        $data['gz_status'] = $this->weekly_model->get_status();
            
            // $data['gazettes_approved'] = $this->weekly_model->getGazetteApprovedList($config["per_page"], $offset);

            $data['gazettes_merged'] = $this->weekly_model->getGazetteMergedList($config["per_page"], $offset, $inputs);


        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('weekly_gazette/merged_wk_gazette.php', $data);
        $this->load->view('template/footer.php');
    }

    public function approved_wk_gz() {
        if ($this->session->userdata('logged_in')) {
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
            } 
        }
        else{
            redirect('user/login');
        }


        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }
        //$this->output->enable_profiler(true);
        $data['title'] = "Weekly Gazette";
        $inputs = $this->input->post();
        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "weekly_gazette/approved_wk_gz";
        $config["total_rows"] = $this->weekly_model->count_total_unpublished_gazettes($this->session->userdata('user_id'));

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

        $data['department_type'] = $this->weekly_model->get_department_types();

        //$data['gz_parts'] = $this->gazette_model->get_parts();
        $data['gz_status'] = $this->weekly_model->get_status();
            
        $data['gazettes_approved'] = $this->weekly_model->getGazetteApprovedList($config["per_page"], $offset, $inputs);


        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('weekly_gazette/approved_wk_gazette.php', $data);
        $this->load->view('template/footer.php');
    }

    /**
     * Filter Weekly Gazette
     */
    public function weekly_gazette_filter() {
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
        //$this->output->enable_profiler(true);
        $data['title'] = "Weekly Gazette";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "weekly_gazette/weekly_gazette_filter";
        //$config["total_rows"] = $this->weekly_model->count_total_unpublished_gazettes($this->session->userdata('user_id'));

        if ($_POST) {
            $searchValue = array(
                'fdate' => trim($this->input->post('fdate')),
                'tdate' => trim($this->input->post('tdate')),
                'dept' => trim($this->input->post('dept')),
                'statusType' => trim($this->input->post('statusType')),
                'weekTime' => trim($this->input->post('weekTime')),
            );
        } else {
            $searchValue = array(
                'fdate' => '',
                'tdate' => '',
                'dept' => '',
                'statusType' => '',
                'weekTime' => '',
            );
        }

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

        $data['department_type'] = $this->weekly_model->get_department_types();

        //$data['gz_parts'] = $this->gazette_model->get_parts();
        $data['gz_status'] = $this->weekly_model->get_status();

        if ($this->session->userdata('is_admin')) {
            $data['gazettes_published'] = $this->weekly_model->getGazettePublishedList_search($searchValue, $config["per_page"], $offset);

            $data['gazettes_unpublished'] = $this->weekly_model->getGazetteUnpublishedList_search($searchValue, $config["per_page"], $offset);
            
            $data['gazettes_approved'] = $this->weekly_model->getGazetteApprovedList_search($searchValue, $config["per_page"], $offset);

            //$data['gazettes_merged'] = $this->weekly_model->getGazetteMergedList_search($searchValue, $config["per_page"], $offset);


        } else {

            $data['dept_name'] = $this->user_model->get_dept_name($this->session->userdata('user_id'));

            $data['gazettes_published'] = $this->weekly_model->getDeptGazettePublishedList_search($this->session->userdata('user_id'), $searchValue, $config["per_page"], $offset);

            $data['gazettes_unpublished'] = $this->weekly_model->getDeptGazetteUnpublishedList_saerch($this->session->userdata('user_id'), $searchValue, $config["per_page"], $offset);
        }


        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('weekly_gazette/index.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Press Merge View Gazette
     */
    public function press_merge_view($id) {
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
        $data['title'] = "View Weekly Merged Gazette";

        if ($this->session->userdata('is_admin')) {

            $data['gazette_details'] = $this->weekly_model->getMergedGazetteDetails($id);
            $this->load->view('template/header.php', $data);
            $this->load->view('template/sidebar.php');
            $this->load->view('weekly_gazette/merged_detail_view.php', $data);
            $this->load->view('template/footer.php');

        } else {
            $this->session->set_userdata('error', 'Merged Weekly Gazette not exists');
                redirect('weekly_gazette/index');
        }

    }

    /*
     * Published Gazette
     */

    public function published() {
        if ($this->session->userdata('logged_in')) {
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
            } 
        }
        else{
            redirect('user/login');
        }


        $data['title'] = "Published Gazette";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "weekly_gazette/index";
        $config["total_rows"] = $this->weekly_model->count_total_published_gazettes($this->session->userdata('user_id'));

        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 2;
        $config['use_page_numbers'] = TRUE;

        $config['full_tag_open'] = '<div class="pagination">';
        $config['full_tag_close'] = '</div>';

        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<span class="firstlink">';
        $config['first_tag_close'] = '</span>';

        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<span class="lastlink">';
        $config['last_tag_close'] = '</span>';

        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<span class="nextlink">';
        $config['next_tag_close'] = '</span>';

        $config['prev_link'] = 'Prev';
        $config['prev_tag_open'] = '<span class="prevlink">';
        $config['prev_tag_close'] = '</span>';

        $config['cur_tag_open'] = '<span class="curlink">';
        $config['cur_tag_close'] = '</span>';

        $config['num_tag_open'] = '<span class="numlink">';
        $config['num_tag_close'] = '</span>';

        $this->my_pagination->initialize($config);

        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        if ($this->session->userdata('is_admin')) {
            $data['gazettes'] = $this->weekly_model->getGazettePublishedList($config["per_page"], $offset);
        } else {
            $data['dept_name'] = $this->user_model->get_dept_name($this->session->userdata('user_id'));
            $data['gazettes'] = $this->weekly_model->getDeptGazettePublishedList($this->session->userdata('user_id'), $config["per_page"], $offset);
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('weekly_gazette/published.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Unpublished Gazettes
     */

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
        $data['title'] = "Unpublished Gazette";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "weekly_gazette/index";

        $config["total_rows"] = $this->weekly_model->count_total_unpublished_gazettes($this->session->userdata('user_id'));

        $config["per_page"] = 1;
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
            $data['gazettes'] = $this->weekly_model->getGazetteUnPublishedList($config["per_page"], $offset);
        } else {
            $data['dept_name'] = $this->user_model->get_dept_name($this->session->userdata('user_id'));
            $data['gazettes'] = $this->weekly_model->getDeptGazetteUnPublishedList($this->session->userdata('user_id'), $config["per_page"], $offset);
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('weekly_gazette/unpublished.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Add for department user
     */

    public function add() { 

        if ($this->session->userdata('logged_in')) {
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
            } 
        }
        else{
            redirect('user/login');
        }


        // if (!$this->session->userdata('logged_in') || $this->session->userdata('user_type') != 2) {
        //     $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
            
        //     if ($this->session->userdata('is_c&t')) {
        //         redirect('commerce_transport_department/login_ct');
        //     } else if ($this->session->userdata('is_igr')) {
        //         redirect('igr_user/login');
        //     } else if ($this->session->userdata('is_applicant')) {
        //         redirect('applicants_login/index');
        //     } else {
        //         redirect('user/login');
        //     }
        //     return;
        // }

        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }

        $data['title'] = "Add Weekly Gazette";

        $user_data = $this->weekly_model->get_dept_info_user($this->session->userdata('user_id'));

        $data['dept_name'] = $user_data->dept_name;
        $data['dept_id'] = $user_data->dept_id;

        $data['parts'] = $this->weekly_model->get_gazette_parts();
        $data['notification_types'] = $this->gazette_type_model->get_notification_types();

        // set form validation rules
        $this->form_validation->set_rules('dept_id', 'Department ID', 'trim|required|numeric');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required|min_length[5]|max_length[200]');
        $this->form_validation->set_rules('notification_type_id', 'Notification Type', 'trim|required');
        $this->form_validation->set_rules('notification_number', 'Notification Number', 'trim|required');
        $this->form_validation->set_rules('keywords', 'Tags', 'trim|required');
        $this->form_validation->set_rules('part_id', 'Part', 'trim|required');
        $this->form_validation->set_rules('doc_files', 'Select Word/Docx File', 'callback_handle_gazette_doc_upload');

        if ($this->form_validation->run() == false) {
            //$this->load->view('weekly_gazette/add', $data);
        } else {

            $dept_id = $this->input->post('dept_id');
            $subject = $this->input->post('subject');
            $gazette_type_id = $this->input->post('gazette_type_id');
            $notification_type_id = $this->input->post('notification_type_id');
            $notification_number = $this->input->post('notification_number');

            $content = '';

            /*
             * Fetch Week from database based on the current date
             * As the Week Starts with Sunday. So we have added extra 2 days time to the current time.
             * Week will starts from Friday 00:00:01 - Thursday 23:59:59
             */
            $week_data = $this->db->query("SELECT WEEK(NOW() + INTERVAL 2 DAY, 0) AS current_week")->row();

            $week = $week_data->current_week;

            $part_id = $this->input->post('part_id');
            $tags = $this->input->post('keywords');

            $result = $this->db->select('*')->from('gz_weekly_gazette_dept_parts')
                            ->where(array(
                                'dept_id' => $dept_id,
                                'part_id' => $part_id,
                                'week' => $week,
                                'year' => date('Y', time())
                            ))
                            ->get();

            if ($result->num_rows() > 0) {
                $this->session->set_flashdata('error', 'Weekly Gazette alerady exists for selected part for the week');
                redirect('weekly_gazette/add');
            } else {
                
                $array_data = array(
                    'user_id' => $this->session->userdata('user_id'),
                    'dept_id' => $dept_id,
                    'subject' => $subject,
                    'part_id' => $part_id,
                    'gazette_type_id' => $gazette_type_id,
                    'notification_type' => $notification_type_id,
                    'notification_number' => $notification_number,
                    'tags' => $tags,
                    'week' => $week,
                    'content' => $content
                );

                // get the userdata from database using model
                $result = $this->weekly_model->add_dept_gazette($array_data);

                $docs_array = array(
                    'gazette_id' => $result,
                    'part_id' => $part_id,
                    'dept_id' => $dept_id,
                    'dept_word_file_path' => $this->doc_file
                );

                // INSERT into gz_documents table
                $this->db->insert('gz_weekly_gazette_documents', $docs_array);

                $document_master_id = $this->db->insert_id();
                // Word file uploaded by Department
                $word_file = str_replace('\\', '/', FCPATH) . $this->doc_file;

                // WORD Template to add the Department Name in the Word File 
                $dept_name_template = FCPATH . './uploads/sample/weekly_dept_sample.docx';
                
                $weekly_dept_file = FCPATH . './uploads/weekly_dept_file/' . time() . '.docx';
                
                $dept_dtls = $this->db->select('*')->from('gz_department')->where('id', $dept_id)->get()->row();
                
                
                // load from template processor to Add department name to word file
                $templateProp = new \PhpOffice\PhpWord\TemplateProcessor($dept_name_template);
                // setting the PHPWord processor to escape characters
                PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
                // set dynamic values provided by Govt. Press
                $templateProp->setValue('department_name', strtoupper($dept_dtls->department_name));
                // SAVE the udpated Word file
                $templateProp->saveAs($weekly_dept_file);
                
                // Word file uploaded by Department
                $word_file = str_replace('\\', '/', FCPATH) . $this->doc_file;
                
                $weekly_dept_updated_db_file = './uploads/dept_weekly_doc/' . time() . '.docx';
                $weekly_dept_updated_file = FCPATH . $weekly_dept_updated_db_file;
                
                // Merge the dept. name new file and original dept. submitted file to generate the final one
                $dm = new DocxMerge();
                $dm->merge([
                    $weekly_dept_file,
                    $word_file
                ], $weekly_dept_updated_file);
                
                // UPDATE into gz_weekly_documents table
                $where = array(
                    'gazette_id' => $result,
                    'part_id' => $part_id,
                    'dept_id' => $dept_id
                );
                
                $this->db->where($where);
                $this->db->update('gz_weekly_gazette_documents', array('dept_word_file_path' => $weekly_dept_updated_db_file));
                
                // Convert Word file To PDF
                $pdf_file_path = $this->convert_word_to_PDF($weekly_dept_updated_file, $result);

                // UPDATE gazette document table with Gazette ID
                $this->db->where('gazette_id', $result);
                $this->db->where('part_id', $part_id);
                $this->db->where('dept_id', $dept_id);
                $this->db->update('gz_weekly_gazette_documents', array('dept_pdf_file_path' => $pdf_file_path));

                // Insert Into Document History Table
                // 21/02/2023

                $docs_history = array(
                    'part_id'                => $part_id,
                    'gazette_id'             => $result,
                    'gz_weekly_document_id'  => $document_master_id,
                    'dept_id'                => $dept_id,
                    'word_file_path'         => $this->doc_file,
                    'pdf_file_path'          => $pdf_file_path,
                    'created_at'             => date('Y-m-d H:i:s', time()),
                    'created_by'             => $this->session->userdata('user_id')
                );
    
                // INSERT into gz_weekly_gazette_documents_history table
                $this->db->insert('gz_weekly_gazette_documents_history', $docs_history);   

                if (is_numeric($result)) {
                    
                    // Store Audit Log
                    audit_action_log($this->session->userdata('user_id'), 'Weekly Gazette', 'Department Saved', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                    
                    //Put the array in a session            
                    $this->session->set_flashdata('success', 'Weekly Gazette saved successfully');
                    redirect('weekly_gazette/dept_preview/' . $result);
                }
            }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('weekly_gazette/dept_add_weekly.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Dept. preview before final submit to the press
     */

    public function dept_preview($gazette_id) {
        //$this->output->enable_profiler(true);
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

        if (!$this->weekly_model->exists($gazette_id)) {
            $this->session->set_flashdata('error', 'The gazette does not exists');
            redirect('weekly_gazette/index');
        }

        $data['title'] = 'Preview Weekly Grazette';

        $data['details'] = $this->weekly_model->get_gazette_details($gazette_id);
        // username & deisgnation for e-Sign
        $data['signed_name'] = $this->session->userdata('name');
        $data['signed_designation'] = $this->session->userdata('designation');

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('weekly_gazette/dept_preview.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * approved gazetee by press for weekly gazettes after department submitted
     */

    public function save_press_gazette_part() {
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

        $this->form_validation->set_rules('dept_part_id', 'Department Part ID', 'trim|required|numeric');

        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $array = array(
                'dept_part_id' => $this->input->post('dept_part_id'),
                'user_id' => $this->session->userdata('user_id'),
                'dept_id' => $this->input->post('dept_id'),
                'gazette_id' => $this->input->post('gazette_id'),
                'part_id' => $this->input->post('part_id'),
                'origin_user' => $this->input->post('user_id'),
                'status_id' => 6,
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $result = $this->weekly_model->save_part_wise_weekly_gazette($array);
            if ($result) {

                $gazette_details = $this->weekly_model->get_gazette_user_details($this->input->post('gazette_id'));

                /*
                $email_content = "<div style=\"background-color:#e8e8e8;margin:0;padding:0\">
                                <center style=\"background-color:#e8e8e8\">
                                <table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"#E8E8E8\">
                                    <tbody>
                                        <tr>
                                            <td valign=\"middle\" align=\"center\" height=\"60\" style=\"border-collapse:collapse\"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table cellspacing=\"0\" cellpadding=\"0\" width=\"90%\" bgcolor=\"#E8E8E8\">
                                <tbody>
                                    <tr>
                                    <td>
                                        <table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" bgcolor=\"#FFFFFF\" style=\"border-style:solid;border-color:#b4bcbc;border-width:1px\">
                                <tbody>
                                    <tr>
                                    <td>
                                <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" bgcolor=\"#FFFFFF\" valign=\"center\" align=\"center\">
                                <tbody>
                                <tr>
                                <td style=\"padding:30px 0px 0px;color:#545d5e;font-weight:lighter;font-family:Helvetica;font-size:12px;line-height:180%;vertical-align:top;text-align:center\">
                                <span><a href=\"#\" style=\"color:#545d5e;text-decoration:none;outline:none\" data-saferedirecturl=\"#\"><img src=\"" . base_url() . "assets/images/logo_for_email.png" . "\" style=\"border:none;outline:none;width:250px;\" class=\"CToWUd\"></a><br></span></td>
                                </tr>
                                <tr>
                                <td class=\"m_8193269747688794827mktEditable\" id=\"m_8193269747688794827body\" valign=\"center\" cellpadding=\"0\" align=\"center\" bgcolor=\"#FFFFFF\" style=\"border-collapse:collapse;color:#545d5e;font-family:Arial,Tahoma,Verdana,sans-serif;font-size:14px;font-weight:lighter;margin:0;text-align:left;line-height:165%;letter-spacing:0;padding-top:20px;padding-bottom:60px;padding-left: 30px;padding-right: 30px;\">
                                <p style=\"color: #000 !important\">Hii {$gazette_details->name},</p>
                                <p style=\"color: #000 !important\">
                                    Gazette has been approved by Director of Printing, Stationary and Publication for (StateName) Press E-Gazette System.<br/>
                                    Department : {$gazette_details->department_name}<br/>
                                    Gazette Type : {$gazette_details->gazette_type}<br/>
                                </p>
                                <br/>
                                <p style=\"color: #000 !important\">
                                Regards,
                                <br/>
                                (StateName) Press E-Gazette System
                                </p>	                      
                                </td>
                                </tr>
                                </tbody>
                                </table>
                                </td>
                                </tr>
                                </tbody>
                                </table>
                                </td>
                                </tr>
                                </tbody>
                                </table>
                                <table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#E8E8E8\">
                                <tbody>
                                </tbody>
                                </table>
                                <table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"#E8E8E8\">
                                <tbody>
                                <tr>
                                <td valign=\"middle\" align=\"center\" height=\"70\" style=\"border-collapse:collapse\"></td>
                                </tr>
                                </tbody>
                                </table>
                                </center>
                                </div>";

                $this->email->from('ntspl.demo5@gmail.com', '(StateName) Press E-Gazette System');
                $this->email->to($gazette_details->email);
                $this->email->subject('Gazette approved by Directorate of Printing, Stationary and Publication');
                $this->email->message($email_content);
                $this->email->set_newline("\r\n");
                $this->email->send();
                
                */
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Weekly Gazette', 'Press Approved Part Content', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                $this->session->set_flashdata('success', 'Part content successfully approved by Govt. Press');
                redirect('weekly_gazette/index');
            } else {
                $this->session->set_flashdata('error', 'Part content not saved');
                redirect('weekly_gazette/index');
            }
        }
    }

    /*
    * Re-upload in department login before e-Sign process
    * @author Soudhankhi Dalai
    * @date 22/02/2023
    */

    public function dept_re_upload($gazette_id) {
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

        if (!$this->weekly_model->exists($gazette_id)) {
            $this->session->set_flashdata('error', 'The gazette does not exists');
            redirect('weekly_gazette/index');
        }

        $data['title'] = 'Reupload Weekly Grazette';
        $data['gazette_id'] = $gazette_id;

        $data['details'] = $this->weekly_model->get_gazette_details($gazette_id);

        // username & designation 
        $data['signed_name'] = $this->session->userdata('name');
        $data['signed_designation'] = $this->session->userdata('designation');

        $user_id = $this->session->userdata('user_id');

        $data['status_list'] = $this->weekly_model->get_dept_gazette_status_lists($gazette_id, $user_id);

        $data['document_list'] = $this->weekly_model->get_dept_gazette_document_lists($gazette_id, $user_id);

        // $this->form_validation->set_rules('gazette_id', 'Gazette ID', 'trim|required');

        // if ($this->form_validation->run() == false) {
        //     //$this->load->view('gazette/dept_preview_resubmit');
        // } else {

        //     $gazette_id = $this->input->post('gazette_id');

        //     $array_data = array(
        //         'gazette_id' => $gazette_id,
        //         'user_id' => $this->session->userdata('user_id'),
        //         // Resubmit
        //         'status_id' => 4,
        //         'created_at' => date('Y-m-d H:i:s', time()),
        //         'modified_at' => date('Y-m-d H:i:s', time())
        //     );

        //     // get the userdata from database using model
        //     $result = $this->gazette_model->resubmit_dept_gazette($array_data);

        //     if ($result) {
                
        //         // Store Audit Log
        //         audit_action_log($this->session->userdata('user_id'), 'Extraordinary Gazette', 'Reupload Saved', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
        //         $this->session->set_flashdata('success', 'Gazette resubmitted successfully');
        //         redirect('gazette/index');
        //     } else {
        //         $this->session->set_flashdata('error', 'Gazette not updated');
        //         redirect('gazette/index');
        //     }
        // }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('weekly_gazette/dept_preview_reupload.php', $data);
        $this->load->view('template/footer.php');

    }

    public function reupload_save_gazette() {
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

        $data['title'] = 'Reupload Grazette';

        $this->form_validation->set_rules('dept_id', 'Department ID', 'trim|required');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('part_id', 'Part ID', 'trim|required');
        $this->form_validation->set_rules('doc_files', 'Select Word/Docx File', 'callback_handle_dept_gazette_doc_resubmit');

        if ($this->form_validation->run() == false) {
            //$this->load->view('weekly_gazette/press_view');
        } else {

            $gazette_id = $this->input->post('gazette_id');

            $array_data = array(
                'gazette_id' => $gazette_id,
                'user_id' => $this->session->userdata('user_id'),
                'dept_id' => $this->input->post('dept_id'),
                'part_id' => $this->input->post('part_id'),
                'origin_by' => $this->input->post('user_id'),
                // Resubmit Saved
                'status_id' => 22,
                'content' => '',
                'modified_at' => date('Y-m-d H:i:s', time())
            );

            // get the userdata from database using model
            $result = $this->weekly_model->resubmit_save_dept_gazette($array_data);
            
            $dept_name_template = FCPATH . './uploads/sample/weekly_dept_sample.docx';
            
            $weekly_dept_file = FCPATH . './uploads/weekly_dept_file/' . time() . '.docx';

            $dept_dtls = $this->db->select('*')->from('gz_department')->where('id', $this->input->post('dept_id'))->get()->row();


            // load from template processor to Add department name to word file
            $templateProp = new \PhpOffice\PhpWord\TemplateProcessor($dept_name_template);
            // setting the PHPWord processor to escape characters
            PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
            // set dynamic values provided by Govt. Press
            $templateProp->setValue('department_name', strtoupper($dept_dtls->department_name));
            // SAVE the udpated Word file
            $templateProp->saveAs($weekly_dept_file);
            
            // Word file uploaded by Department
            $word_file = str_replace('\\', '/', FCPATH) . $this->doc_file;

            //$weekly_dept_file = FCPATH . './uploads/sample/weekly_dept_sample.docx';
            
            $weekly_dept_updated_db_file = './uploads/dept_weekly_doc/' . time() . '.docx';
            $weekly_dept_updated_file = FCPATH . $weekly_dept_updated_db_file;
                
            // Merge the dept. name new file and original dept. submitted file to generate the final one
            $dm = new DocxMerge();
            $dm->merge([
                $weekly_dept_file,
                $word_file
            ], $weekly_dept_updated_file);
            
            // Convert Word file To PDF
            $pdf_file_path = $this->convert_word_to_PDF($weekly_dept_updated_file, $gazette_id);

            // UPDATE gazette document table with Gazette ID
            $this->db->where('gazette_id', $gazette_id);
            $this->db->where('part_id', $this->input->post('part_id'));
            $this->db->where('dept_id', $this->input->post('dept_id'));
            $this->db->update('gz_weekly_gazette_documents', array(
                'dept_word_file_path' => $weekly_dept_updated_db_file,
                'dept_pdf_file_path' => $pdf_file_path,
                'dept_signed_pdf_path' => ''
                    )
            );

            // Insert Into Document History Table
            // 22/02/2023

            $doc_updated_id = $this->db->select('id') 
                                        ->from('gz_weekly_gazette_documents')
                                        ->where('gazette_id', $gazette_id)
                                        ->get()->row()->id;

            $docs_history = array(
                'part_id'                => $this->input->post('part_id'),
                'gazette_id'             => $gazette_id,
                'gz_weekly_document_id'  => $doc_updated_id,
                'dept_id'                => $this->input->post('dept_id'),
                'word_file_path'         => $weekly_dept_updated_db_file,
                'pdf_file_path'          => $pdf_file_path,
                'created_at'             => date('Y-m-d H:i:s', time()),
                'created_by'             => $this->session->userdata('user_id')
            );

            // INSERT into gz_weekly_gazette_documents_history table
            $this->db->insert('gz_weekly_gazette_documents_history', $docs_history);  

            if ($result) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Weekly Gazette', 'Department Resubmit Saved', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'Gazette updated successfully');
                redirect('weekly_gazette/dept_preview/' . $gazette_id);
            } else {
                $this->session->set_flashdata('error', 'Gazette not updated');
                redirect('weekly_gazette/index');
            }
        }
    }

    /*
     * Resubmit save for Dept. User after Gazette has been Rejected by the Govt. Press User 
     * NOTE:: This functionality has been only stores the data before publish of the weekly gazette.
     * When Admin user submits for final publication, all the gazettes created on the same day
     * needs to be combine and make the contents every weekly gazette submitted by   
     */

    public function resubmit_save_gazette() {
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

        $data['title'] = 'Edit Grazette';

        $this->form_validation->set_rules('dept_id', 'Department ID', 'trim|required');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('part_id', 'Part ID', 'trim|required');
        $this->form_validation->set_rules('doc_files', 'Select Word/Docx File', 'callback_handle_dept_gazette_doc_resubmit');

        if ($this->form_validation->run() == false) {
            //$this->load->view('weekly_gazette/press_view');
        } else {

            $gazette_id = $this->input->post('gazette_id');

            $array_data = array(
                'gazette_id' => $gazette_id,
                'user_id' => $this->session->userdata('user_id'),
                'dept_id' => $this->input->post('dept_id'),
                'part_id' => $this->input->post('part_id'),
                'origin_by' => $this->input->post('user_id'),
                // Resubmit Saved
                'status_id' => 7,
                'content' => '',
                'modified_at' => date('Y-m-d H:i:s', time())
            );

            // get the userdata from database using model
            $result = $this->weekly_model->resubmit_save_dept_gazette($array_data);
            
            $dept_name_template = FCPATH . './uploads/sample/weekly_dept_sample.docx';
            
            $weekly_dept_file = FCPATH . './uploads/weekly_dept_file/' . time() . '.docx';

            $dept_dtls = $this->db->select('*')->from('gz_department')->where('id', $this->input->post('dept_id'))->get()->row();


            // load from template processor to Add department name to word file
            $templateProp = new \PhpOffice\PhpWord\TemplateProcessor($dept_name_template);
            // setting the PHPWord processor to escape characters
            PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
            // set dynamic values provided by Govt. Press
            $templateProp->setValue('department_name', strtoupper($dept_dtls->department_name));
            // SAVE the udpated Word file
            $templateProp->saveAs($weekly_dept_file);
            
            // Word file uploaded by Department
            $word_file = str_replace('\\', '/', FCPATH) . $this->doc_file;

            //$weekly_dept_file = FCPATH . './uploads/sample/weekly_dept_sample.docx';
            
            $weekly_dept_updated_db_file = './uploads/dept_weekly_doc/' . time() . '.docx';
            $weekly_dept_updated_file = FCPATH . $weekly_dept_updated_db_file;
                
            // Merge the dept. name new file and original dept. submitted file to generate the final one
            $dm = new DocxMerge();
            $dm->merge([
                $weekly_dept_file,
                $word_file
            ], $weekly_dept_updated_file);
            
            // Convert Word file To PDF
            $pdf_file_path = $this->convert_word_to_PDF($weekly_dept_updated_file, $gazette_id);

            // UPDATE gazette document table with Gazette ID
            $this->db->where('gazette_id', $gazette_id);
            $this->db->where('part_id', $this->input->post('part_id'));
            $this->db->where('dept_id', $this->input->post('dept_id'));
            $this->db->update('gz_weekly_gazette_documents', array(
                'dept_word_file_path' => $weekly_dept_updated_db_file,
                'dept_pdf_file_path' => $pdf_file_path,
                'dept_signed_pdf_path' => ''
                    )
            );

            // Insert Into Document History Table
            // 22/02/2023

            $doc_updated_id = $this->db->select('id') 
                                        ->from('gz_weekly_gazette_documents')
                                        ->where('gazette_id', $gazette_id)
                                        ->get()->row()->id;

            $docs_history = array(
                'part_id'                => $this->input->post('part_id'),
                'gazette_id'             => $gazette_id,
                'gz_weekly_document_id'  => $doc_updated_id,
                'dept_id'                => $this->input->post('dept_id'),
                'word_file_path'         => $weekly_dept_updated_db_file,
                'pdf_file_path'          => $pdf_file_path,
                'created_at'             => date('Y-m-d H:i:s', time()),
                'created_by'             => $this->session->userdata('user_id')
            );

            // INSERT into gz_weekly_gazette_documents_history table
            $this->db->insert('gz_weekly_gazette_documents_history', $docs_history);  

            if ($result) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Weekly Gazette', 'Department Resubmit Saved', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'Gazette updated successfully');
                redirect('weekly_gazette/resubmit_dept_gazette/' . $gazette_id);
            } else {
                $this->session->set_flashdata('error', 'Gazette not updated');
                redirect('weekly_gazette/index');
            }
        }
    }

    /*
     * Resubmit save for Dept. User after Gazette has been Rejected by the Govt. Press User 
     */

    public function resubmit_dept_gazette($gazette_id) {
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

        if (!$this->weekly_model->exists($gazette_id)) {
            $this->session->set_flashdata('error', 'The gazette does not exists');
            redirect('weekly_gazette/index');
        }

        $data['title'] = 'Resubmit Weekly Grazette';
        $data['gazette_id'] = $gazette_id;

        $data['details'] = $this->weekly_model->get_gazette_details($gazette_id);
        // username & deisgnation for e-Sign
        $data['signed_name'] = $this->session->userdata('name');
        $data['signed_designation'] = $this->session->userdata('designation');

        $this->form_validation->set_rules('gazette_id', 'Gazette ID', 'trim|required');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('part_id', 'Part ID', 'trim|required');
        $this->form_validation->set_rules('dept_id', 'Department ID', 'trim|required');

        if ($this->form_validation->run() == false) {
            //$this->load->view('weekly_gazette/dept_preview_resubmit');
        } else {

            $gazette_id = $this->input->post('gazette_id');

            // sined PDF file path
            $signed_file = './uploads/dept_signed_pdf/' . $gazette_id . time() . '.pdf';
            // decode the signed file data
            $decodedData = base64_decode($this->input->post('binaryfile'));
            // put the signed file data into path and physically store the signed PDF file
            file_put_contents($signed_file, $decodedData);

            $array_data = array(
                'gazette_id' => $gazette_id,
                'user_id' => $this->session->userdata('user_id'),
                'dept_id' => $this->input->post('dept_id'),
                'part_id' => $this->input->post('part_id'),
                'origin_by' => $this->input->post('user_id'),
                // Resubmit
                'status_id' => 4,
                'dept_signed_pdf_path' => $signed_file,
                'created_at' => date('Y-m-d H:i:s', time()),
                'modified_at' => date('Y-m-d H:i:s', time())
            );

            // get the userdata from database using model
            $result = $this->weekly_model->resubmit_dept_gazette($array_data);

            if ($result) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Weekly Gazette', 'Department Resubmitted', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'Gazette resubmitted successfully');
                redirect('weekly_gazette/index');
            } else {
                $this->session->set_flashdata('error', 'Gazette not updated');
                redirect('weekly_gazette/index');
            }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('weekly_gazette/dept_preview_resubmit.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Add for Govt. Press User/Admin for each part wise merge
     */

    public function press_add() {
        // $this->output->enable_profiler(true);
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

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $year = $this->input->post('year');
            $week = $this->input->post('week');
            $part = $this->input->post('part_id');

            $data['part_id'] = $part;
            $data['year'] = $year;
            $data['week'] = $week;

            $data['title'] = 'Press Add Docket Grazette';
            // Sl No. to be generated each Year, Week & part wise
            $data['sl_no'] = $this->weekly_model->get_sl_no($part, $year, $week);

            $data['gazette_details'] = $this->weekly_model->get_approved_part_gazette_details($year, $week, $part);
            
            // Fetch department data from database 
            $data['dept_data_list'] = $this->weekly_model->get_part_wise_approved_gazette_details($year, $week, $part);

            $this->load->view('template/header.php', $data);
            $this->load->view('template/sidebar.php');
            $this->load->view('weekly_gazette/press_add.php', $data);
            $this->load->view('template/footer.php');
            
        } else {
            redirect('weekly_gazette/index');
        }
        
    }

    public function press_published() {
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

        $this->form_validation->set_rules('gazette_id', 'Id', 'trim|required');
		
        if ($this->form_validation->run() == false) {
            //$this->load->view('weekly_gazette/press_publish_view', $data);
        } else {

            // data needs to be updated
            $array_data = array(
                'gazette_id' => $this->input->post('gazette_id'),
                'published' => 1
            );

            // get the userdata from database using model
            $result = $this->weekly_model->publish_press_gazette($array_data);

            if ($result) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Weekly Gazette', 'Press Published', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                //Put the array in a session            
                $this->session->set_flashdata('success', 'Gazette published successfully');
                redirect('weekly_gazette/published_wk_gz');
            } else {
                //Put the array in a session
                $this->session->set_flashdata('error', 'Gazette not published');
                redirect('weekly_gazette/published_wk_gz');
            }
        }
    }

    /*
     * Callback function for Dept. User word file upload
     */

    public function handle_gazette_doc_upload() {
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
        $this->doc_file = '';

        if (!empty($_FILES['doc_files']['name']) && ($_FILES['doc_files']['size'] > 0)) {

            $upload_dir = "./uploads/dept_weekly_doc/";

            if (!is_dir($upload_dir) && !is_writable($upload_dir)) {
                mkdir($upload_dir);
            }

            $config['upload_path'] = $upload_dir;
            $config['allowed_types'] = array('doc', 'docx');
            $config['file_name'] = $_FILES['doc_files']['name'];
            $config['overwrite'] = true;
            $config['encrypt_name'] = TRUE;
            // 5 MB
            $config['max_size'] = '5242880';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('doc_files')) {
                $this->form_validation->set_message('handle_gazette_doc_upload', $this->upload->display_errors('', ''));
                return false;
            } else {
                $this->upload_data['file'] = $this->upload->data();
                $this->doc_file = $upload_dir . $this->upload_data['file']['file_name'];
                return true;
            }
        } else {
            $this->form_validation->set_message('handle_gazette_doc_upload', 'No file selected');
        }
    }

    /*
     * Convert Word file to PDF
     */

    public function convert_word_to_PDF($word_file, $gazette_id) {
        // Convert to PDF using MS Word using PHP COM object
        $word = new COM("word.application") or die("Could not initialise MS Word object.");
        $word->Documents->Open($word_file);

        $pdf_file_db_path = './uploads/dept_weekly_pdf/' . time().'_'. $gazette_id . '.pdf';
        $pdf_file_path = FCPATH . $pdf_file_db_path;

        $word->ActiveDocument->ExportAsFixedFormat($pdf_file_path, 17, false, 0, 0, 0, 0, 7, true, true, 2, true, true, false);

        $word->Quit();
        $word = null;
        return $pdf_file_db_path;
    }

    public function convert_part_wise_doc_to_pdf() {

        if (!$this->session->userdata('logged_in') && !$this->session->userdata('is_admin')) {
            redirect('user/login');
        }
        
        $data['title'] = 'Preview Part Gazette';

        $this->form_validation->set_rules('gazette_id', 'Gazette ID', 'trim|required|numeric');
        $this->form_validation->set_rules('part_id', 'Part ID', 'trim|required');
        $this->form_validation->set_rules('year', 'Year', 'trim|required|numeric');
        $this->form_validation->set_rules('week', 'Week', 'trim|required|numeric');

        if ($this->form_validation->run() == false) {
            //$this->load->view('weekly_gazette/press_publish_view', $data);
        } else {

            $year = $this->input->post('year');
            $week = $this->input->post('week');
            $part_id = $this->input->post('part_id');
            $gazette_id = $this->input->post('gazette_id');

            $part_docs = $this->weekly_model->get_part_wise_approved_gazette_details($year, $week, $part_id);
            
            $word_files = array();

            foreach ($part_docs as $w_file) {
                $word_files[] = $w_file->dept_word_file_path;
            }

            $part_object = $this->weekly_model->get_gazette_action_part_details($part_id);

            $data_array = array(
                'sl_no' => $this->input->post('sl_no'),
                'issue_date' => $this->input->post('issue_date'),
                'saka_month' => $this->input->post('saka_month'),
                'saka_date' => $this->input->post('saka_date'),
                'saka_year' => $this->input->post('saka_year'),
                'part_name' => $part_object->part_name,
                'section_name' => $part_object->section_name
            );

            $post_data = array(
                'part_id' => $part_id,
                'year' => $year,
                'week' => $week
            );

            if ($part_id == 8) {
                // Blank Template file with Spacing from Top for PART - VII
                $template_file = FCPATH . './uploads/sample/weekly_part_7_sample.docx';
            } else if ($part_id == 1) {
                // Blank Template file with Spacing from Top for PART - I
                $template_file = FCPATH . './uploads/sample/weekly_part_1_sample.docx';
            } else {
                // Blank Template file with Spacing from Top for all other PART 
                $template_file = FCPATH . './uploads/sample/weekly_part_sample.docx';
            }

            
            $pdf_path = $this->convert_press_part_wise_word_to_PDF($template_file, $word_files, $data_array, $post_data);
            
        }

        $data['part_name'] = $part_object->part_name;
        $data['section_name'] = $part_object->section_name;
        $data['pdf_file_path'] = $pdf_path;
        $data['year'] = $year;
        $data['week'] = $week;

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('weekly_gazette/press_part_wise_pdf_preview.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Start Part wise Merging of Contents
     * Convert Word file to PDF part wise for Govt. Press
     */

    public function convert_press_part_wise_word_to_PDF($template_file, $word_files = array(), $data = array(), $post_data = array()) {

        // store in database
        $press_word_db_path = './uploads/press_part_wise_merge_doc/' . time() . '.docx';
        $press_word_path = FCPATH . $press_word_db_path;
        
        // Assign the template file to the file array
        $file_items = array($template_file);

        foreach ($word_files as $individual_file) {
            $file_items[] = FCPATH . $individual_file;
        }

        // Merge Multiple Documents From Departments
        
        $dm = new DocxMerge();
        $dm->merge($file_items, $press_word_path);

        // Convert to PDF using MS Word using PHP COM Object
        $word = new COM("word.application") or die("Could not initialise MS Word object.");
        $word->Documents->Open($press_word_path);
        // PDF file path
        $pdf_file_db_path = './uploads/press_part_wise_merge_pdf/' . time() . '.pdf';
        $pdf_file_path = FCPATH . $pdf_file_db_path;
        // SAVE As PDF file
        $word->ActiveDocument->ExportAsFixedFormat($pdf_file_path, 17, false, 0, 0, 0, 0, 7, true, true, 2, true, true, false);

        $word->Quit();
        $word = null;

        $data_pdf_array = array(
            'part_id' => $post_data['part_id'],
            'year' => $post_data['year'],
            'week' => $post_data['week'],
            'sl_no' => $data['sl_no'],
            'issue_date' => $data['issue_date'],
            'saka_month' => $data['saka_month'],
            'saka_date' => $data['saka_date'],
            'saka_year' => $data['saka_year'],
            'word_file_path' => $press_word_db_path,
            'pdf_file_path' => $pdf_file_db_path,
            'created_at' => date('Y-m-d H:i:s', time())
        );

        // INSERT/UPDATE into database
        $this->weekly_model->insert_update_approved_part_wise_pdf($data_pdf_array);


        // Load PDF Weekly library
        $this->load->library('pdf_weekly');

        // Header, Line top, Line Bottom Image
        $header_image = "./assets/images/header.jpg";
        $line_top_image = "./assets/images/line_top.png";
        $line_bottom_image = "./assets/images/line_bottom.png";

        // Now Add the Header & Footer in the PDF file using FPDF & FPDI
        $pdf = new PDF_weekly();        

        // IF PART - I, QR Code Image to be incorporated
        if ($post_data['part_id'] == 1) {

            // Load QR Code Library
            $this->load->library('phpqrcode/qrlib');

            $qr_text = "Week: " . $post_data['week'] . "<br/>Year: " . $post_data['year'] . "<br/>Published Date: " . $data['issue_date'];
                    
            $folder = 'uploads/qrcodes/';
            $file = $post_data['week'] . "_" . $post_data['year'] . "_" . md5(time()) . ".png";
            $file_name = $folder . $file;
            
            QRcode::png($qr_text, $file_name);

            // Set the Parameters in PDF file
            $pdf->set_parameters($header_image, $line_top_image, $line_bottom_image, $data['sl_no'], $data['issue_date'], $data['saka_month'], $data['saka_date'], $data['saka_year'], $file_name, $data['part_name'], $data['section_name']);
        } else {
            // Set the Parameters in PDF file
            $pdf->set_parameters($header_image, $line_top_image, $line_bottom_image, $data['sl_no'], $data['issue_date'], $data['saka_month'], $data['saka_date'], $data['saka_year'], '', $data['part_name'], $data['section_name']);
        }

        $pdf->AliasNbPages();

        // Is last page. Set to false initially
        $pdf->isFinished = false;

        $page_db_no = 0;
        $start_page = 0;
        $end_page = 0;

        // Set the source file
        $pagecount = $pdf->setSourceFile($pdf_file_path);


        // Check if this Year, Part has data already exists, then don't insert
        $query_results = $this->db->select('*')->from('gz_weekly_part_wise_page')
                                ->where('year', $post_data['year'])
                                ->where('part_id', $post_data['part_id'])->get();

        if ($query_results->num_rows() > 0) {

            $sql_sel = "SELECT * FROM gz_weekly_part_wise_page WHERE week = (SELECT MAX(week) FROM gz_weekly_part_wise_page WHERE year = '{$post_data["year"]}' AND part_id = '{$post_data["part_id"]}') AND year = '{$post_data["year"]}' AND part_id = '{$post_data["part_id"]}'";

            $result_2 = $this->db->query($sql_sel);

            $row_2 = $result_2->row();

            // Will start from the Next Page of the last week Part Merged document
            $start_page = $row_2->page_end + 1;
            $page_db_no = $start_page;

            // iterate through all pages
            for ($pageNo = 1; $pageNo <= $pagecount; $pageNo++) {

                // import a page
                $templateId = $pdf->importPage($pageNo);
                // Add the pages
                $pdf->AddPage();

                // SET Header
                if ($pdf->PageNo() == 1) {
                
                    $pdf->Image($pdf->header_image, 20, 20, 175, 30); // X start, Y start, X width, Y width in mm
                    
                    $pdf->SetFont('Helvetica', 'B', 12);
                    $pdf->SetXY(80, 48);
                    $pdf->Write(12, 'PUBLISHED BY AUTHORITY');
                    
                    if (!empty($pdf->qr_image)) {
                        $pdf->SetXY(85, 50); // X start, Y start in mm
                        $pdf->Image($pdf->qr_image, 155, 40, 20, 20);
                    }
                    
                    $pdf->Image($pdf->line_top_image, 20, 60, 175, 0); // X start, Y start, X width, Y width in mm
                    
                    $pdf->SetFont('Helvetica', 'B', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB 
                    $pdf->SetXY(31, 67); // X start, Y start in mm
                    $pdf->Write(0, "No. " . $pdf->sl_no . ",");
                    
                    $pdf->SetFont('Helvetica', 'B', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB 
                    $pdf->SetXY(50, 67); // X start, Y start in mm
                    $pdf->Write(0, "CUTTACK,");
                    
                    $pdf->SetFont('Helvetica', 'B', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB 
                    $pdf->SetXY(75, 67); // X start, Y start in mm
                    $pdf->Write(0, strtoupper($pdf->issue_date) . " ");
                    
                    $pdf->SetFont('Helvetica', 'B', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB 
                    $pdf->SetXY(140, 67); // X start, Y start in mm
                    $pdf->Write(0, "/ " . strtoupper($pdf->saka_month) . "  " . $pdf->saka_date . ",  " . $pdf->saka_year);
                    
                    $pdf->Image($pdf->line_bottom_image, 20, 72, 175, 0); // X start, Y start, X width, Y width in mm
                    
                    $pdf->SetFont('Helvetica', '', 8); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB 
                    $pdf->SetXY(30, 78); // X start, Y start in mm
                    $pdf->Write(0, "SEPARATE PAGING IS GIVEN TO THIS PART IN ORDER THAT IT MAY BE FILED AS A SEPARATE COMPILATION");
                    
                    $pdf->SetXY(19, 70);
                    // Font Style
                    $pdf->SetFont('Arial', 'B', 9);
                    // Page Number
                    $pdf->Cell(0, 20, '___________________________________________________________________________________________________', 0, 0, '');

                    // IF PART is NOT PART - I 
                    if ($post_data['part_id'] != 1) {

                        $pdf->SetFont('Helvetica', 'B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                        //$pdf->SetTextColor(0,0,0); // RGB 
                        $pdf->SetXY(20, 90); // X start, Y start in mm
                        $pdf->Cell(0, 0, $pdf->part_no, 0, 0, 'C');

                        $pdf->Ln(5);
                        $pdf->SetX(15);
                        $pdf->SetFont('Helvetica', 'B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                        //$this->SetXY(20, 95); // X start, Y start
                        $pdf->MultiCell(0, 6, $pdf->section, 0, 'C', 0);
                        $pdf->Ln(0);

                        $pdf->SetXY(20, 110);
                        // Font Style
                        $pdf->SetFont('Helvetica', 'B', 10);
                        // Blank line
                        $pdf->Cell(0, 0, '__________', 0, 0, 'C');

                    }

                    /*
                     * If the PART - VII / part_id = 8 do this thing
                     */
                    if ($post_data['part_id'] == 8) {

                        $pdf->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                        $pdf->SetXY(30, 113);
                        $pdf->Cell(0, 10, 'THE (StateName) GAZETTE: NOTICE', 0, 0, 'L');
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(20, 121);
                        $col1 = "   Owing to rise in cost of labour and materials, the rates of the (StateName) Gazette have been revised with effect from the 1st January 1994 as follows:—";
                        $pdf->MultiCell(90, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col1), 0, 1);

                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(110, 116);
                        $col2 = "   Orders for supply of the (StateName) Gazette should be addressed to “The Publisher of the (StateName) Gazette, Directorate of Printing, Stationery & Publication, (StateName), Madhupatna, Cuttack-753 010” and must be accompanied by a remittance of the cost.";
                        $pdf->MultiCell(90, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col2), 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(20, 136);
                        $col3 = "   The subscribers will receive the (StateName) Gazette along with the Extraordinary issues of the (StateName) Gazette published from time to time in a year against their annual subscription. No subscription for any particular part of the (StateName) Gazette/Extraordinary issue of the (StateName) Gazette exclusively will be entertained.";
                        $pdf->MultiCell(90, 4, $col3, 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(110, 139);
                        $col4 = "     Applications for free supply of the (StateName) Gazette to a Government Office should be addressed to the Secretary to the Government of (StateName), Commerce & Transport (Commerce) Department, Bhubaneswar. Only on receipt of Government Order, can such supply be made.";
                        $pdf->MultiCell(90, 4, $col4, 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(20, 163);
                        $col5 = "    (A) The revised annual subscription of the entire (StateName) Gazette (all parts) including Extraordinary issues of the (StateName) Gazette has been fixed at Rs. 1,456.00 without postage and Rs. 2,122.00 from the 1st June 2002 with postage.";
                        $pdf->MultiCell(90, 4, $col5, 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(110, 166);
                        $col6 = "    Complaints regarding non-receipt of any number of the (StateName) Gazette should be forwarded within a week after the date on which it is due. All subscriptions are payable in advance and may be paid annually, half- yearly or quarterly on or before the 1st January, 1st April, 1st July or 1st October of each year.";
                        $pdf->MultiCell(90, 4, $col6, 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(20, 186);
                        $col6 = "    (B) The revised rates per issue of all the parts of the (StateName) Gazette is fixed at Rs. 28.00. The (StateName) Gazette will not be available for sale partwise.";
                        $pdf->MultiCell(90, 4, $col6, 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(110, 193);
                        $col6 = "    Subscribers will please note that supply of the (StateName) Gazette will be stopped at the expiry of the period subscribed for.";
                        $pdf->MultiCell(90, 4, $col6, 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(20, 201);
                        $col6 = "    (C) The cost of the Extraordinary issues of the (StateName) Gazette has been revised to Rs. 2.60 without postage per page and the postal charges will be added according to the weight, if ordered for by post.";
                        $pdf->MultiCell(90, 4, $col6, 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(110, 209);
                        $col6 = "    All notifications intended for publication in the (StateName) Gazette should reach the Publisher's Office not later than 4 P. M. on the preceding Wednesday and all advertisements must reach the Publisher’s Office by 12 NOON on Monday to ensure appearance in that week’s Gazette.";
                        $pdf->MultiCell(90, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col6), 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(45, 218);
                        $pdf->Cell(35, 10, 'REVISED RATES OF ADVERTISEMENT', 0, 0, 'C');
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(22, 224);
                        $pdf->Cell(35, 10, '(I) Full page per issue', 0, 0, 'L');
                        $pdf->Ln(1);
                        
                        $pdf->SetXY(72, 224);
                        $pdf->Cell(35, 10, '.. Rs. 529.00', 0, 1, 'R');
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(22, 229);
                        $pdf->Cell(35, 10, '(II) Half page per issue', 0, 0, 'L');
                        $pdf->Ln(1);
                        
                        $pdf->SetXY(72, 229);
                        $pdf->Cell(35, 10, '.. Rs. 265.00', 0, 1, 'R');
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(22, 234);
                        $pdf->Cell(35, 10, '(III) Line covering double column', 0, 0, 'L');
                        
                        $pdf->SetXY(22, 238);
                        $pdf->Cell(35, 10, 'measure per issue', 0, 0, 'L');
                        
                        $pdf->SetXY(72, 234);
                        $pdf->Cell(35, 10, '.. Rs. 8.80', 0, 1, 'R');
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(22, 243);
                        $pdf->Cell(35, 10, '(IV) Line in single column per issue', 0, 0, 'L');
                        $pdf->Ln(1);
                        
                        $pdf->SetXY(72, 243);
                        $pdf->Cell(35, 10, '.. Rs. 4.40', 0, 1, 'R');
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(145, 238);
                        $pdf->Cell(35, 10, 'Lalit Das', 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(145, 243);
                        $pdf->Cell(35, 10, 'Publisher, The (StateName) Gazette', 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', 'I', 10);
                        $pdf->SetXY(20, 252);
                        $pdf->Cell(35, 10, iconv("UTF-8", "CP1250//TRANSLIT", 'N. B.-'), 0, 1, 'L');
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(31, 255);
                        $col6 = 'Due to urgent and immediate nature of Government works, the subscribers are requested to well co-operate with the "Publisher" for delay in printing and timely supply of Gazettes.';
                        $pdf->MultiCell(180, 4, $col6, 0, 1);
                        $pdf->Ln(1);

                    }

                    /*
                     * If the PART - I / part_id = 1, do this thing
                     */
                    if ($post_data['part_id'] == 1) {
                        // IF PART - I 
                        $pdf->SetFont('Helvetica', 'B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                        //$pdf->SetTextColor(0,0,0); // RGB
                        $pdf->SetXY(20, 88); // X start, Y start in mm
                        $pdf->Cell(0, 0, "CONTENTS", 0, 0, 'C');
                        
                        // LEFT Column
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(95, 95);
                        $pdf->Cell(10, 4, 'PAGE', 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        // PART - I
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(20, 100);
                        $col1 = "PART I—Appointments, Confirmations, Postings, Transfers, Deputations, Powers, Leave, Programmes & Results of Departmental Examinations of Officers and other Personal Notices.";
                        $pdf->MultiCell(72, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col1), 0, 1);
                        
                        // Assign publication values
                        $result_1 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 1)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_1 = ($result_1->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(45, 122);
                        $pdf->Cell(10, 4, $publication_1, 0, 1, 'C');
                        $pdf->Ln(1);

                        // PART - I page nos to be added later. Though PART - I page nos are initially not stored in the database.
                        $part_1_end_page = (($pagecount + $start_page) - 1);

                        // IF Page PART - I, then include the start page & end page
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(92, 100);
                        if ($result_1->num_rows() > 0) {
                            $pdf->Cell(15, 4, $start_page . ' - ' . $part_1_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        // IF PART - I page start & page end should entered
                        
                        // PART - II
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(20, 130);
                        $col2 = "PART II—Educational Notices, Programmes and Results of School and College Examinations and other Examinations, etc.";
                        $pdf->MultiCell(72, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col2), 0, 1);
                        
                        // Assign publication values
                        $result_2 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 2)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_2 = ($result_2->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(45, 145);
                        $pdf->Cell(10, 4, $publication_2, 0, 1, 'C');
                        $pdf->Ln(1);

                        $page_results_2 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 2)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_2->num_rows() > 0) {
                            $page_results_2_row = $page_results_2->row();
                            $part_2_start_page = $page_results_2_row->page_start;
                            $part_2_end_page = $page_results_2_row->page_end;
                        } else {
                            $part_2_start_page = "";
                            $part_2_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(92, 130);
                        if ($result_2->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_2_start_page . ' - ' . $part_2_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - III
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(20, 152);
                        $col3 = "PART III—Statutory Rules, Orders, Notifications, Rules, etc., issued by the Governor, Heads of Departments and High Court.";
                        $pdf->MultiCell(72, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col3), 0, 1);
                        
                        // Assign publication values
                        $result_3 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 3)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_3 = ($result_3->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(45, 167);
                        $pdf->Cell(10, 4, $publication_3, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_3 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 3)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_3->num_rows() > 0) {
                            $page_results_3_row = $page_results_3->row();
                            $part_3_start_page = $page_results_3_row->page_start;
                            $part_3_end_page = $page_results_3_row->page_end;
                        } else {
                            $part_3_start_page = "";
                            $part_3_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(92, 152);
                        if ($result_3->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_3_start_page . ' - ' . $part_3_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - III A
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(20, 174);
                        $col3A = "PART III-A—Regulations, Orders, Notifications, Rules, etc., issued by the Governor, Heads of Departments and High Court.";
                        $pdf->MultiCell(72, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col3A), 0, 1);
                        
                        // Assign publication values
                        $result_3A = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 4)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_3A = ($result_3A->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(45, 189);
                        $pdf->Cell(10, 4, $publication_3A, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_3A = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 4)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_3A->num_rows() > 0) {
                            $page_results_3A_row = $page_results_3A->row();
                            $part_3A_start_page = $page_results_3A_row->page_start;
                            $part_3A_end_page = $page_results_3A_row->page_end;
                        } else {
                            $part_3_A_start_page = "";
                            $part_3_A_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(92, 174);
                        if ($result_3A->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_3_A_start_page . ' - ' . $part_3_A_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - IV
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(20, 196);
                        $col4 = "PART IV—Regulations, Orders, Notifications and Rules of the Government of India, Papers extracted from the Gazette of India and Gazettes of other States and Notifications, Orders, etc., in connection with Elections.";
                        $pdf->MultiCell(72, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col4), 0, 1);
                        
                        // Assign publication values
                        $result_4 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 5)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_4 = ($result_4->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(45, 219);
                        $pdf->Cell(10, 4, $publication_4, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_4 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 5)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_4->num_rows() > 0) {
                            $page_results_4_row = $page_results_4->row();
                            $part_4_start_page = $page_results_4_row->page_start;
                            $part_4_end_page = $page_results_4_row->page_end;
                        } else {
                            $part_4_start_page = "";
                            $part_4_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(92, 196);
                        if ($result_4->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_4_start_page . ' - ' . $part_4_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - V
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(20, 226);
                        $col5 = "PART V—Acts of the Parliament assented to by the President.";
                        $pdf->MultiCell(72, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col5), 0, 1);
                        
                        // Assign publication values
                        $result_5 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 6)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_5 = ($result_5->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(45, 237);
                        $pdf->Cell(10, 4, $publication_5, 0, 1, 'C');
                        $pdf->Ln(1);

                        $page_results_5 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 6)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_5->num_rows() > 0) {
                            $page_results_5_row = $page_results_5->row();
                            $part_5_start_page = $page_results_5_row->page_start;
                            $part_5_end_page = $page_results_5_row->page_end;
                        } else {
                            $part_5_start_page = "";
                            $part_5_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(92, 226);
                        if ($result_5->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_5_start_page . ' - ' . $part_5_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - VI
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(20, 244);
                        $col6 = "PART VI—Bills introduced into the Parliament and Bills published before introduction in the Parliament.";
                        $pdf->MultiCell(72, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col6), 0, 1);
                        
                        // Assign publication values
                        $result_6 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 7)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_6 = ($result_6->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(45, 259);
                        $pdf->Cell(10, 4, $publication_6, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_6 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 7)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_6->num_rows() > 0) {
                            $page_results_6_row = $page_results_6->row();
                            $part_6_start_page = $page_results_6_row->page_start;
                            $part_6_end_page = $page_results_6_row->page_end;
                        } else {
                            $part_6_start_page = "";
                            $part_6_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(92, 244);
                        if ($result_6->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_6_start_page . ' - ' . $part_6_end_page, 0, 1);
                        }
                        $pdf->Ln(1);

                        // Right Column
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(183, 95);
                        $pdf->Cell(10, 4, 'PAGE', 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        // PART - VII
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 100);
                        $col7 = "PART VII—Advertisements, Notices, Press Notes and Audit Reports and Awards on Industrial Disputes, etc.";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col7), 0, 1);
                        $pdf->Ln(1);
                        
                        // Assign publication values
                        $result_7 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 8)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_7 = ($result_7->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 114);
                        $pdf->Cell(10, 4, $publication_7, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_7 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 8)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_7->num_rows() > 0) {
                            $page_results_7_row = $page_results_7->row();
                            $part_7_start_page = $page_results_7_row->page_start;
                            $part_7_end_page = $page_results_7_row->page_end;
                        } else {
                            $part_7_start_page = "";
                            $part_7_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 100);
                        if ($result_7->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_7_start_page . ' - ' . $part_7_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - VIII
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 121);
                        $col8 = "PART VIII—Sale Notices of Forest Products, etc.";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col8), 0, 1);
                        $pdf->Ln(1);
                        
                        // Assign publication values
                        $result_8 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 9)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_8 = ($result_8->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 128);
                        $pdf->Cell(10, 4, $publication_8, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_8 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 9)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_8->num_rows() > 0) {
                            $page_results_8_row = $page_results_8->row();
                            $part_8_start_page = $page_results_8_row->page_start;
                            $part_8_end_page = $page_results_8_row->page_end;
                        } else {
                            $part_8_start_page = "";
                            $part_8_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 121);
                        if ($result_8->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_8_start_page . ' - ' . $part_8_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - IX
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 135);
                        $col9 = "PART IX—Circulars and General letters by the Accountant-General, (StateName)";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col9), 0, 1);
                        $pdf->Ln(1);
                        
                        // Assign publication values
                        $result_9 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 10)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_9 = ($result_9->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 146);
                        $pdf->Cell(10, 4, $publication_9, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_9 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 10)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_9->num_rows() > 0) {
                            $page_results_9_row = $page_results_9->row();
                            $part_9_start_page = $page_results_9_row->page_start;
                            $part_9_end_page = $page_results_9_row->page_end;
                        } else {
                            $part_9_start_page = "";
                            $part_9_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 135);
                        if ($result_9->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_9_start_page . ' - ' . $part_9_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - X
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 153);
                        $col10 = "PART X—Acts of the Legislative Assembly, (StateName).";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col10), 0, 1);
                        $pdf->Ln(1);
                        
                        // Assign publication values
                        $result_10 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 11)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_10 = ($result_10->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 162);
                        $pdf->Cell(10, 4, $publication_10, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_10 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 11)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_10->num_rows() > 0) {
                            $page_results_10_row = $page_results_10->row();
                            $part_10_start_page = $page_results_10_row->page_start;
                            $part_10_end_page = $page_results_10_row->page_end;
                        } else {
                            $part_10_start_page = "";
                            $part_10_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 153);
                        if ($result_10->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_10_start_page . ' - ' . $part_10_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - XI
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 170);
                        $col11 = "PART XI—Bills introduced into the Legislative Assembly of (StateName), Reports of the Select Committees presented or to be presented to that Assembly and Bills published before introduction in that Assembly.";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col11), 0, 1);
                        $pdf->Ln(1);
                        
                        // Assign publication values
                        $result_11 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 12)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_11 = ($result_11->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 193);
                        $pdf->Cell(10, 4, $publication_11, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_11 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 12)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_11->num_rows() > 0) {
                            $page_results_11_row = $page_results_11->row();
                            $part_11_start_page = $page_results_11_row->page_start;
                            $part_11_end_page = $page_results_11_row->page_end;
                        } else {
                            $part_11_start_page = "";
                            $part_11_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 170);
                        if ($result_11->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_11_start_page . ' - ' . $part_11_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - XII
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 200);
                        $col12 = "PART XII—Materials relating to Transport Organisations.";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col12), 0, 1);
                        $pdf->Ln(1);
                        
                        // Assign publication values
                        $result_12 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 13)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_12 = ($result_12->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 211);
                        $pdf->Cell(10, 4, $publication_12, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_12 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 13)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_12->num_rows() > 0) {
                            $page_results_12_row = $page_results_12->row();
                            $part_12_start_page = $page_results_12_row->page_start;
                            $part_12_end_page = $page_results_12_row->page_end;
                        } else {
                            $part_12_start_page = "";
                            $part_12_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 200);
                        if ($result_12->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_12_start_page . ' - ' . $part_12_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // Supplement
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 219);
                        $suplement = "SUPPLEMENT—Resolutions, Weather and Crop Reports and other Statistical Reports, etc.";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $suplement), 0, 1);
                        $pdf->Ln(1);
                        
                        // Assign publication values
                        $result_13 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 14)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_13 = ($result_13->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 230);
                        $pdf->Cell(10, 4, $publication_13, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_13 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 14)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_13->num_rows() > 0) {
                            $page_results_13_row = $page_results_13->row();
                            $suppl_start_page = $page_results_13_row->page_start;
                            $suppl_end_page = $page_results_13_row->page_end;
                        } else {
                            $suppl_start_page = "";
                            $suppl_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 219);
                        if ($result_13->num_rows() > 0) {
                            $pdf->Cell(15, 4, $suppl_start_page . ' - ' . $suppl_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // Supplement - A
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 238);
                        $supplement_A = "SUPPLEMENT-(A)—Register of persons dismissed from Government Service.";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $supplement_A), 0, 1);
                        $pdf->Ln(1);
                        
                        // Assign publication values
                        $result_14 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 15)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_14 = ($result_14->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 249);
                        $pdf->Cell(10, 4, $publication_14, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_14 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 15)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_14->num_rows() > 0) {
                            $page_results_14_row = $page_results_14->row();
                            $suppl_A_start_page = $page_results_14_row->page_start;
                            $suppl_A_end_page = $page_results_14_row->page_end;
                        } else {
                            $suppl_A_start_page = "";
                            $suppl_A_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 238);
                        if ($result_14->num_rows() > 0) {
                            $pdf->Cell(15, 4, $suppl_A_start_page . ' - ' . $suppl_A_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // Appendix
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 260);
                        $appendix = "APPENDIX—Catalogue of Books and Periodicals registered in (StateName)";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $appendix), 0, 1);
                        $pdf->Ln(1);
                        
                        // Assign publication values
                        $result_15 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 16)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_15 = ($result_15->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 270);
                        $pdf->Cell(10, 4, $publication_15, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_15 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 16)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_15->num_rows() > 0) {
                            $page_results_15_row = $page_results_15->row();
                            $appendix_start_page = $page_results_15_row->page_start;
                            $appendix_end_page = $page_results_15_row->page_end;
                        } else {
                            $appendix_start_page = "";
                            $appendix_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 260);
                        if ($result_15->num_rows() > 0) {
                            $pdf->Cell(15, 4, $appendix_start_page . ' - ' . $appendix_end_page, 0, 1);
                        }
                        $pdf->Ln(1);                    

                    }
                    
                } else {

                    $pdf->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB
                    $pdf->SetXY(20, 25); // X start, Y start in mm
                    $pdf->Write(0, $pdf->part_no);
                    
                    $pdf->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB
                    $pdf->SetXY(55, 25); // X start, Y start in mm
                    $pdf->Write(0, "THE (StateName) GAZETTE,");
                    
                    $pdf->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB 
                    $pdf->SetXY(100, 25); // X start, Y start in mm
                    $pdf->Write(0, strtoupper(substr($pdf->issue_date, strpos($pdf->issue_date, ',') + 1)));
                    
                    $pdf->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB 
                    $pdf->SetXY(140, 25); // X start, Y start in mm
                    $pdf->Write(0, "/" . strtoupper($pdf->saka_month) . ", " . $pdf->saka_date . ", " .  $pdf->saka_year);
                    
                    $pdf->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    $pdf->SetXY(185, 25); // X start, Y start in mm
                    $pdf->Write(0, $page_db_no);
                    
                    $pdf->Image($pdf->line_bottom_image, 20, 30, 175, 0); // X start, Y start, X width, Y width in mm
                        
                }

                // use the imported page and adjust the page size
                $pdf->useTemplate($templateId, ['adjustPageSize' => true]);

                // If Not last page, then only increment the value
                if ($pdf->isFinished == false){
                    // increment 
                    $page_db_no++;
                }
            }

            // last week page_end number + 1 will be the selected week's page_start_no for the selected part.
            // each year January 1, page_start_no will be started from 1 again.
            
            // Check if this Week, Year, Part has data already exists, then don't insert
            $qry_rslts = $this->db->select('*')->from('gz_weekly_part_wise_page')
                                ->where('year', $post_data['year'])
                                ->where('week', $post_data['week'])
                                ->where('part_id', $post_data['part_id'])->get();

            // If not found, then only INSERT the new row itno the Table
            if ($qry_rslts->num_rows() == 0) {
                // INSERT new row
                $page_wise_part_ins_aar = array(
                    'year' => $post_data['year'],
                    'week' => $post_data['week'],
                    'part_id' => $post_data['part_id'],
                    'page_start' => $start_page,
                    'page_end' => ($page_db_no - 1)
                );
                // INSERT INTO weekly_gazette_part_wise_page
                $this->db->insert('gz_weekly_part_wise_page', $page_wise_part_ins_aar);
            }

        } else {

            $page_db_no = 1;
            $start_page = 1;

            // iterate through all pages
            for ($pageNo = 1; $pageNo <= $pagecount; $pageNo++) {

                // import a page
                $templateId = $pdf->importPage($pageNo);
                // Add the pages
                $pdf->AddPage();

                // SET Header
                if ($pdf->PageNo() == 1) {
                    
                    $pdf->Image($pdf->header_image, 20, 20, 175, 30); // X start, Y start, X width, Y width in mm
                    
                    $pdf->SetFont('Helvetica', 'B', 12);
                    $pdf->SetXY(80, 48);
                    $pdf->Write(12, 'PUBLISHED BY AUTHORITY');
                    
                    if (!empty($pdf->qr_image)) {
                        $pdf->SetXY(85, 50); // X start, Y start in mm
                        $pdf->Image($pdf->qr_image, 155, 40, 20, 20);
                    }
                    
                    $pdf->Image($pdf->line_top_image, 20, 60, 175, 0); // X start, Y start, X width, Y width in mm
                    
                    $pdf->SetFont('Helvetica', 'B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB 
                    $pdf->SetXY(31, 67); // X start, Y start in mm
                    $pdf->Write(0, "No. " . $pdf->sl_no . ",");
                    
                    $pdf->SetFont('Helvetica', 'B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB 
                    $pdf->SetXY(50, 67); // X start, Y start in mm
                    $pdf->Write(0, "CUTTACK,");
                    
                    $pdf->SetFont('Helvetica', 'B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB 
                    $pdf->SetXY(75, 67); // X start, Y start in mm
                    $pdf->Write(0, strtoupper($pdf->issue_date) . " ");
                    
                    $pdf->SetFont('Helvetica', 'B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB 
                    $pdf->SetXY(140, 67); // X start, Y start in mm
                    $pdf->Write(0, "/ " . strtoupper($pdf->saka_month) . "  " . $pdf->saka_date . ",  " . $pdf->saka_year);
                    
                    $pdf->Image($pdf->line_bottom_image, 20, 72, 175, 0); // X start, Y start, X width, Y width in mm
                    
                    $pdf->SetFont('Helvetica', '', 8); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB 
                    $pdf->SetXY(30, 78); // X start, Y start in mm
                    $pdf->Write(0, "SEPARATE PAGING IS GIVEN TO THIS PART IN ORDER THAT IT MAY BE FILED AS A SEPARATE COMPILATION");
                    
                    $pdf->SetXY(19, 70);
                    // Font Style
                    $pdf->SetFont('Arial', 'B', 9);
                    // Page Number
                    $pdf->Cell(0, 20, '___________________________________________________________________________________________________', 0, 0, '');

                    // IF PART is NOT PART - I 

                    if ($post_data['part_id'] != 1) {

                        $pdf->SetFont('Helvetica', 'B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                        //$pdf->SetTextColor(0,0,0); // RGB 
                        $pdf->SetXY(20, 90); // X start, Y start in mm
                        $pdf->Cell(0, 0, $pdf->part_no, 0, 0, 'C');

                        $pdf->Ln(5);
                        $pdf->SetX(15);
                        $pdf->SetFont('Helvetica', 'B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                        //$this->SetXY(20, 95); // X start, Y start
                        $pdf->MultiCell(0, 6, $pdf->section, 0, 'C', 0);
                        $pdf->Ln(0);

                        $pdf->SetXY(20, 110);
                        // Font Style
                        $pdf->SetFont('Helvetica', 'B', 10);
                        // Blank line
                        $pdf->Cell(0, 0, '__________', 0, 0, 'C');
                    }


                    /*
                     * If the PART - VII / part_id = 8 do this thing
                     */
                    if ($post_data['part_id'] == 8) {

                        $pdf->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                        $pdf->SetXY(30, 113);
                        $pdf->Cell(0, 10, 'THE (StateName) GAZETTE: NOTICE', 0, 0, 'L');
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(20, 121);
                        $col1 = "   Owing to rise in cost of labour and materials, the rates of the (StateName) Gazette have been revised with effect from the 1st January 1994 as follows:—";
                        $pdf->MultiCell(90, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col1), 0, 1);

                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(110, 116);
                        $col2 = "   Orders for supply of the (StateName) Gazette should be addressed to “The Publisher of the (StateName) Gazette, Directorate of Printing, Stationery & Publication, (StateName), Madhupatna, Cuttack-753 010” and must be accompanied by a remittance of the cost.";
                        $pdf->MultiCell(90, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col2), 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(20, 136);
                        $col3 = "   The subscribers will receive the (StateName) Gazette along with the Extraordinary issues of the (StateName) Gazette published from time to time in a year against their annual subscription. No subscription for any particular part of the (StateName) Gazette/Extraordinary issue of the (StateName) Gazette exclusively will be entertained.";
                        $pdf->MultiCell(90, 4, $col3, 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(110, 139);
                        $col4 = "     Applications for free supply of the (StateName) Gazette to a Government Office should be addressed to the Secretary to the Government of (StateName), Commerce & Transport (Commerce) Department, Bhubaneswar. Only on receipt of Government Order, can such supply be made.";
                        $pdf->MultiCell(90, 4, $col4, 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(20, 163);
                        $col5 = "    (A) The revised annual subscription of the entire (StateName) Gazette (all parts) including Extraordinary issues of the (StateName) Gazette has been fixed at Rs. 1,456.00 without postage and Rs. 2,122.00 from the 1st June 2002 with postage.";
                        $pdf->MultiCell(90, 4, $col5, 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(110, 166);
                        $col6 = "    Complaints regarding non-receipt of any number of the (StateName) Gazette should be forwarded within a week after the date on which it is due. All subscriptions are payable in advance and may be paid annually, half- yearly or quarterly on or before the 1st January, 1st April, 1st July or 1st October of each year.";
                        $pdf->MultiCell(90, 4, $col6, 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(20, 186);
                        $col6 = "    (B) The revised rates per issue of all the parts of the (StateName) Gazette is fixed at Rs. 28.00. The (StateName) Gazette will not be available for sale partwise.";
                        $pdf->MultiCell(90, 4, $col6, 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(110, 193);
                        $col6 = "    Subscribers will please note that supply of the (StateName) Gazette will be stopped at the expiry of the period subscribed for.";
                        $pdf->MultiCell(90, 4, $col6, 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(20, 201);
                        $col6 = "    (C) The cost of the Extraordinary issues of the (StateName) Gazette has been revised to Rs. 2.60 without postage per page and the postal charges will be added according to the weight, if ordered for by post.";
                        $pdf->MultiCell(90, 4, $col6, 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(110, 209);
                        $col6 = "    All notifications intended for publication in the (StateName) Gazette should reach the Publisher's Office not later than 4 P. M. on the preceding Wednesday and all advertisements must reach the Publisher’s Office by 12 NOON on Monday to ensure appearance in that week’s Gazette.";
                        $pdf->MultiCell(90, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col6), 0, 1);
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(45, 218);
                        $pdf->Cell(35, 10, 'REVISED RATES OF ADVERTISEMENT', 0, 0, 'C');
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(22, 224);
                        $pdf->Cell(35, 10, '(I) Full page per issue', 0, 0, 'L');
                        $pdf->Ln(1);
                        
                        $pdf->SetXY(72, 224);
                        $pdf->Cell(35, 10, '.. Rs. 529.00', 0, 1, 'R');
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(22, 229);
                        $pdf->Cell(35, 10, '(II) Half page per issue', 0, 0, 'L');
                        $pdf->Ln(1);
                        
                        $pdf->SetXY(72, 229);
                        $pdf->Cell(35, 10, '.. Rs. 265.00', 0, 1, 'R');
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(22, 234);
                        $pdf->Cell(35, 10, '(III) Line covering double column', 0, 0, 'L');
                        
                        $pdf->SetXY(22, 238);
                        $pdf->Cell(35, 10, 'measure per issue', 0, 0, 'L');
                        
                        $pdf->SetXY(72, 234);
                        $pdf->Cell(35, 10, '.. Rs. 8.80', 0, 1, 'R');
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(22, 243);
                        $pdf->Cell(35, 10, '(IV) Line in single column per issue', 0, 0, 'L');
                        $pdf->Ln(1);
                        
                        $pdf->SetXY(72, 243);
                        $pdf->Cell(35, 10, '.. Rs. 4.40', 0, 1, 'R');
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(145, 238);
                        $pdf->Cell(35, 10, 'Lalit Das', 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(145, 243);
                        $pdf->Cell(35, 10, 'Publisher, The (StateName) Gazette', 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $pdf->SetFont('Helvetica', 'I', 10);
                        $pdf->SetXY(20, 252);
                        $pdf->Cell(35, 10, iconv("UTF-8", "CP1250//TRANSLIT", 'N. B.-'), 0, 1, 'L');
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(31, 255);
                        $col6 = 'Due to urgent and immediate nature of Government works, the subscribers are requested to well co-operate with the "Publisher" for delay in printing and timely supply of Gazettes.';
                        $pdf->MultiCell(180, 4, $col6, 0, 1);
                        $pdf->Ln(1);

                    }

                    // If PART is PART - I
                    if ($post_data['part_id'] == 1) {
                        // IF PART - I
                        $pdf->SetFont('Helvetica', 'B', 12); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                        //$pdf->SetTextColor(0,0,0); // RGB 
                        $pdf->SetXY(20, 88); // X start, Y start in mm
                        $pdf->Cell(0, 0, "CONTENTS", 0, 0, 'C');
                        
                        // LEFT Column
                        $pdf->SetFont('Helvetica', '', 10);
                        $pdf->SetXY(95, 95);
                        $pdf->Cell(10, 4, 'PAGE', 0, 1, 'C');
                        $pdf->Ln(1);

                        // PART - I
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(20, 100);
                        $col1 = "PART I—Appointments, Confirmations, Postings, Transfers, Deputations, Powers, Leave, Programmes & Results of Departmental Examinations of Officers and other Personal Notices.";
                        $pdf->MultiCell(72, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col1), 0, 1);
                        
                        // Assign publication values
                        $result_1 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 1)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_1 = ($result_1->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(45, 122);
                        $pdf->Cell(10, 4, $publication_1, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        // PART - I page start & page end content to be used after the PART has been created

                        // IF Page PART - I, then include the start page & end page
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(92, 100);
                        if ($result_1->num_rows() > 0) {
                            $pdf->Cell(15, 4, $start_page . ' - ' . $pagecount, 0, 1);
                        }
                        $pdf->Ln(1);
                        // IF PART - I page start & page end should entered
                        
                        // PART - II
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(20, 130);
                        $col2 = "PART II—Educational Notices, Programmes and Results of School and College Examinations and other Examinations, etc.";
                        $pdf->MultiCell(72, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col2), 0, 1);
                        
                        // Assign publication values
                        $result_2 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 2)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_2 = ($result_2->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(45, 145);
                        $pdf->Cell(10, 4, $publication_2, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_2 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 2)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_2->num_rows() > 0) {
                            $page_results_2_row = $page_results_2->row();
                            $part_2_start_page = $page_results_2_row->page_start;
                            $part_2_end_page = $page_results_2_row->page_end;
                        } else {
                            $part_2_start_page = "";
                            $part_2_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(92, 130);
                        if ($result_2->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_2_start_page . ' - ' . $part_2_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - III
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(20, 152);
                        $col3 = "PART III—Statutory Rules, Orders, Notifications, Rules, etc., issued by the Governor, Heads of Departments and High Court.";
                        $pdf->MultiCell(72, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col3), 0, 1);
                        
                        // Assign publication values
                        $result_3 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 3)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_3 = ($result_3->num_rows() > 0) ? '' : '(Nothing for Publication)';

                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(45, 167);
                        $pdf->Cell(10, 4, $publication_3, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_3 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 3)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_3->num_rows() > 0) {
                            $page_results_3_row = $page_results_3->row();
                            $part_3_start_page = $page_results_3_row->page_start;
                            $part_3_end_page = $page_results_3_row->page_end;
                        } else {
                            $part_3_start_page = "";
                            $part_3_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(92, 152);
                        if ($result_3->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_3_start_page . ' - ' . $part_3_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - III A
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(20, 174);
                        $col3A = "PART III-A—Regulations, Orders, Notifications, Rules, etc., issued by the Governor, Heads of Departments and High Court.";
                        $pdf->MultiCell(72, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col3A), 0, 1);
                        
                        // Assign publication values
                        $result_3A = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 4)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_3A = ($result_3A->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(45, 189);
                        $pdf->Cell(10, 4, $publication_3A, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_3A = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 4)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_3A->num_rows() > 0) {
                            $page_results_3A_row = $page_results_3A_row->row();
                            $part_3_A_start_page = $page_results_3A_row->page_start;
                            $part_3_A_end_page = $page_results_3A_row->page_end;
                        } else {
                            $part_3_A_start_page = "";
                            $part_3_A_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(92, 174);
                        if ($result_3A->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_3_A_start_page . ' - ' . $part_3_A_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - IV
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(20, 196);
                        $col4 = "PART IV—Regulations, Orders, Notifications and Rules of the Government of India, Papers extracted from the Gazette of India and Gazettes of other States and Notifications, Orders, etc., in connection with Elections.";
                        $pdf->MultiCell(72, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col4), 0, 1);
                        
                        // Assign publication values
                        $result_4 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 5)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_4 = ($result_4->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(45, 219);
                        $pdf->Cell(10, 4, $publication_4, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_4 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 5)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_4->num_rows() > 0) {
                            $page_results_4_row = $page_results_4->row();
                            $part_4_start_page = $page_results_4_row->page_start;
                            $part_4_end_page = $page_results_4_row->page_end;
                        } else {
                            $part_4_start_page = "";
                            $part_4_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(92, 196);
                        if ($result_4->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_4_start_page . ' - ' . $part_4_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - V
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(20, 226);
                        $col5 = "PART V—Acts of the Parliament assented to by the President.";
                        $pdf->MultiCell(72, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col5), 0, 1);
                        
                        // Assign publication values
                        $result_5 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 6)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_5 = ($result_5->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(45, 237);
                        $pdf->Cell(10, 4, $publication_5, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_5 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 6)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_5->num_rows() > 0) {
                            $page_results_5_row = $page_results_5->row();
                            $part_5_start_page = $page_results_5_row->page_start;
                            $part_5_end_page = $page_results_5_row->page_end;
                        } else {
                            $part_5_start_page = "";
                            $part_5_end_page = "";
                        }

                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(92, 226);
                        if ($result_5->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_5_start_page . ' - ' . $part_5_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - VI
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(20, 244);
                        $col6 = "PART VI—Bills introduced into the Parliament and Bills published before introduction in the Parliament.";
                        $pdf->MultiCell(72, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col6), 0, 1);
                        
                        // Assign publication values
                        $result_6 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 7)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_6 = ($result_6->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(45, 259);
                        $pdf->Cell(10, 4, $publication_6, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_6 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 7)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_6->num_rows() > 0) {
                            $page_results_6_row = $page_results_6->row();
                            $part_6_start_page = $page_results_6_row->page_start;
                            $part_6_end_page = $page_results_6_row->page_end;
                        } else {
                            $part_6_start_page = "";
                            $part_6_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(92, 244);
                        if ($result_6->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_6_start_page . ' - ' . $part_6_end_page, 0, 1);
                        }
                        $pdf->Ln(1);

                        // Right Column
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(183, 95);
                        $pdf->Cell(10, 4, 'PAGE', 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        // PART - VII
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 100);
                        $col7 = "PART VII—Advertisements, Notices, Press Notes and Audit Reports and Awards on Industrial Disputes, etc.";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col7), 0, 1);
                        $pdf->Ln(1);
                        
                        // Assign publication values
                        $result_7 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 8)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_7 = ($result_7->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 114);
                        $pdf->Cell(10, 4, $publication_7, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_7 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 8)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_7->num_rows() > 0) {
                            $page_results_7_row = $page_results_7->row();
                            $part_7_start_page = $page_results_7_row->page_start;
                            $part_7_end_page = $page_results_7_row->page_end;
                        } else {
                            $part_7_start_page = "";
                            $part_7_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 100);
                        if ($result_7->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_7_start_page . ' - ' . $part_7_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - VIII
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 121);
                        $col8 = "PART VIII—Sale Notices of Forest Products, etc.";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col8), 0, 1);
                        $pdf->Ln(1);
                        
                        // Assign publication values
                        $result_8 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 9)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_8 = ($result_8->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 128);
                        $pdf->Cell(10, 4, $publication_8, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_8 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 9)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_8->num_rows() > 0) {
                            $page_results_8_row = $page_results_8->row();
                            $part_8_start_page = $page_results_8_row->page_start;
                            $part_8_end_page = $page_results_8_row->page_end;
                        } else {
                            $part_8_start_page = "";
                            $part_8_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 121);
                        if ($result_8->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_8_start_page . ' - ' . $part_8_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - IX
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 135);
                        $col9 = "PART IX—Circulars and General letters by the Accountant-General, (StateName)";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col9), 0, 1);
                        $pdf->Ln(1);
                        
                        // Assign publication values
                        $result_9 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 10)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_9 = ($result_9->num_rows() > 0) ? '' : '(Nothing for Publication)';

                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 146);
                        $pdf->Cell(10, 4, $publication_9, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_9 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 10)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_9->num_rows() > 0) {
                            $page_results_9_row = $page_results_9->row();
                            $part_9_start_page = $page_results_9_row->page_start;
                            $part_9_end_page = $page_results_9_row->page_end;
                        } else {
                            $part_9_start_page = "";
                            $part_9_end_page = "";
                        }

                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 135);
                        if ($result_9->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_9_start_page . ' - ' . $part_9_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - X
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 153);
                        $col10 = "PART X—Acts of the Legislative Assembly, (StateName).";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col10), 0, 1);
                        $pdf->Ln(1);
                        
                        // Assign publication values
                        $result_10 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 11)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_10 = ($result_10->num_rows() > 0) ? '' : '(Nothing for Publication)';

                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 162);
                        $pdf->Cell(10, 4, $publication_10, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_10 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 11)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_10->num_rows() > 0) {
                            $page_results_10_row = $page_results_10->row();
                            $part_10_start_page = $page_results_10_row->page_start;
                            $part_10_end_page = $page_results_10_row->page_end;
                        } else {
                            $part_10_start_page = "";
                            $part_10_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 153);
                        if ($result_10->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_10_start_page . ' - ' . $part_10_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - XI
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 170);
                        $col11 = "PART XI—Bills introduced into the Legislative Assembly of (StateName), Reports of the Select Committees presented or to be presented to that Assembly and Bills published before introduction in that Assembly.";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col11), 0, 1);
                        $pdf->Ln(1);
                        
                        // Assign publication values
                        $result_11 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 12)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_11 = ($result_11->num_rows() > 0) ? '' : '(Nothing for Publication)';

                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 193);
                        $pdf->Cell(10, 4, $publication_11, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_11 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 12)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_11->num_rows() > 0) {
                            $page_results_11_row = $page_results_11->row();
                            $part_11_start_page = $page_results_11_row->page_start;
                            $part_11_end_page = $page_results_11_row->page_end;
                        } else {
                            $part_11_start_page = "";
                            $part_11_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 170);
                        if ($result_11->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_11_start_page . ' - ' . $part_11_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // PART - XII
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 200);
                        $col12 = "PART XII—Materials relating to Transport Organisations.";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $col12), 0, 1);
                        $pdf->Ln(1);

                        // Assign publication values
                        $result_12 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 13)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_12 = ($result_12->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 211);
                        $pdf->Cell(10, 4, $publication_12, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_12 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 13)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_12->num_rows() > 0) {
                            $page_results_12_row = $page_results_12->row();
                            $part_12_start_page = $page_results_12_row->page_start;
                            $part_12_end_page = $page_results_12_row->page_end;
                        } else {
                            $part_12_start_page = "";
                            $part_12_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 200);
                        if ($result_12->num_rows() > 0) {
                            $pdf->Cell(15, 4, $part_12_start_page . ' - ' . $part_12_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // Supplement
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 219);
                        $suplement = "SUPPLEMENT—Resolutions, Weather and Crop Reports and other Statistical Reports, etc.";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $suplement), 0, 1);
                        $pdf->Ln(1);
                        
                        // Assign publication values
                        $result_13 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 14)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_13 = ($result_13->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 230);
                        $pdf->Cell(10, 4, $publication_13, 0, 1, 'C');
                        $pdf->Ln(1);

                        $page_results_13 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 14)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_13->num_rows() > 0) {
                            $page_results_13_row = $page_results_13->row();
                            $suppl_start_page = $page_results_13_row->page_start;
                            $suppl_end_page = $page_results_13_row->page_end;
                        } else {
                            $suppl_start_page = "";
                            $suppl_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 219);
                        if ($result_13->num_rows() > 0) {
                            $pdf->Cell(15, 4, $suppl_start_page . ' - ' . $suppl_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // Supplement - A
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 238);
                        $supplement_A = "SUPPLEMENT-(A)—Register of persons dismissed from Government Service.";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $supplement_A), 0, 1);
                        $pdf->Ln(1);
                        
                        // Assign publication values
                        $result_14 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 15)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_14 = ($result_14->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 249);
                        $pdf->Cell(10, 4, $publication_14, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_14 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 15)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_14->num_rows() > 0) {
                            $page_results_14_row = $page_results_14->row();
                            $suppl_A_start_page = $page_results_14_row->page_start;
                            $suppl_A_end_page = $page_results_14_row->page_end;
                        } else {
                            $suppl_A_start_page = "";
                            $suppl_A_end_page = "";
                        }
                        
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 238);
                        if ($result_14->num_rows() > 0) {
                            $pdf->Cell(15, 4, $suppl_A_start_page . ' - ' . $suppl_A_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                        
                        // Appendix
                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(110, 260);
                        $appendix = "APPENDIX—Catalogue of Books and Periodicals registered in (StateName)";
                        $pdf->MultiCell(71, 4, iconv("UTF-8", "CP1250//TRANSLIT", $appendix), 0, 1);
                        $pdf->Ln(1);
                        
                        // Assign publication values
                        $result_15 = $this->db->select('id')
                                ->from('gz_weekly_part_wise_approved_merged_documents')
                                ->where('part_id', 16)
                                ->where('week', $post_data['week'])
                                ->where('year', $post_data['year'])
                                ->get();
                        
                        $publication_15 = ($result_15->num_rows() > 0) ? '' : '(Nothing for Publication)';
                        
                        $pdf->SetFont('Helvetica', 'I', 9);
                        $pdf->SetXY(140, 270);
                        $pdf->Cell(10, 4, $publication_15, 0, 1, 'C');
                        $pdf->Ln(1);
                        
                        $page_results_15 = $this->db->select('page_start, page_end')
                                                    ->from('gz_weekly_part_wise_page')
                                                    ->where('year', $post_data["year"])
                                                    ->where('part_id', 16)
                                                    ->where('week', $post_data['week'])
                                                    ->get();

                        if ($page_results_15->num_rows() > 0) {
                            $page_results_15_row = $page_results_15->row();
                            $appendix_start_page = $page_results_15_row->page_start;
                            $appendix_end_page = $page_results_15_row->page_end;
                        } else {
                            $appendix_start_page = "";
                            $appendix_end_page = "";
                        }

                        $pdf->SetFont('Helvetica', '', 9);
                        $pdf->SetXY(180, 260);
                        if ($result_15->num_rows() > 0) {
                            $pdf->Cell(15, 4, $appendix_start_page . ' - ' . $appendix_end_page, 0, 1);
                        }
                        $pdf->Ln(1);
                    }
                    
                } else {

                    $pdf->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB 
                    $pdf->SetXY(20, 25); // X start, Y start in mm
                    $pdf->Write(0, $pdf->part_no);
                    
                    $pdf->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB 
                    $pdf->SetXY(55, 25); // X start, Y start in mm
                    $pdf->Write(0, "THE (StateName) GAZETTE,");
                    
                    $pdf->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB 
                    $pdf->SetXY(100, 25); // X start, Y start in mm
                    $pdf->Write(0, strtoupper(substr($pdf->issue_date, strpos($pdf->issue_date, ',') + 1)));
                    
                    $pdf->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    //$pdf->SetTextColor(0,0,0); // RGB 
                    $pdf->SetXY(140, 25); // X start, Y start in mm
                    $pdf->Write(0, "/" . strtoupper($pdf->saka_month) . ", " . $pdf->saka_date . ", " .  $pdf->saka_year);
                    
                    $pdf->SetFont('Helvetica', '', 10); // Font Name, Font Style (eg. 'B' for Bold), Font Size
                    $pdf->SetXY(185, 25); // X start, Y start in mm
                    $pdf->Write(0, $page_db_no);
                    
                    $pdf->Image($pdf->line_bottom_image, 20, 30, 175, 0); // X start, Y start, X width, Y width in mm

                }

                // use the imported page and adjust the page size
                $pdf->useTemplate($templateId, ['adjustPageSize' => true]);
                // Increment
                $page_db_no++;
            }

            $page_wise_part_ins_aar = array(
                'year' => $post_data['year'],
                'week' => $post_data['week'],
                'part_id' => $post_data['part_id'],
                'page_start' => $start_page,
                'page_end' => ($page_db_no - 1)
            );

            // INSERT INTO weekly_gazette_part_wise_page
            $this->db->insert('gz_weekly_part_wise_page', $page_wise_part_ins_aar);

        }

        // Finished at the last page
        $pdf->isFinished = true;

        $press_pdf_file = './uploads/press_part_wise_merge_pdf/' . time() . '.pdf';

        $pdf->Output($press_pdf_file, "F");

        // UPDATE press PDF in part wise merged documents table
        $this->db->where('part_id', $post_data['part_id']);
        $this->db->where('year', $post_data['year']);
        $this->db->where('week', $post_data['week']);
        $this->db->update('gz_weekly_part_wise_approved_merged_documents', array('pdf_file_path' => $press_pdf_file, 'dept_merged_pdf' => $pdf_file_db_path));

        return $press_pdf_file;

    }

    /* End Part Merge for a Single Part */

    /*
     * View publish gazette form
     */
    
    public function publish_gazette_form() {
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

        $data['title'] = 'Preview Part Gazette';

        $data['year_list'] = $this->weekly_model->get_gazette_merged_years();

        $this->form_validation->set_rules('week', 'Week', 'trim|required');
        $this->form_validation->set_rules('year', 'Year', 'trim|required');

        if ($this->form_validation->run() == false) {
            //$this->load->view('weekly_gazette/press_publish_view', $data);
 
        } else {

            $week = $this->input->post('week');
            $year = $this->input->post('year');

            $data['result_lists'] = $this->weekly_model->get_ready_to_publish_part_wise_gazette_details($year, $week);
            
            
            $data['week'] = $week;
            $data['year'] = $year;
            
            $result_merged_gazette = $this->db->select('*')
                                            ->from('gz_final_weekly_gazette')
                                            ->where('week', $week)
                                            ->where('year', $year)
                                            ->get();

            if ($result_merged_gazette->num_rows() > 0) {
                // What if the user has signed the final document and not pubslished. These 2 conditions also need 
                // to be checked and taken to consideration.

                $data["result_lists"] = $this->weekly_model->get_ready_to_publish_part_wise_gazette_details($year, $week);
                

                // What if the user has merged the final document & opt out from the Sign PDF page. 
                // But later again comes & filter the Year & Week from the Publish option & not signed.
                $final_merged_gazette = $this->db->select('*')
                                            ->from('gz_final_weekly_gazette')
                                            ->where('week', $week)
                                            ->where('year', $year)
                                            ->get()->row();
                                           
                if ($result_merged_gazette->num_rows() > 0 && empty($final_merged_gazette->signed_pdf_path)) {
                    
                    $week = $this->input->post('week');
                    $year = $this->input->post('year');
                    
                    $data['week'] = $week;
                    $data['year'] = $year;
                    
                    $publish_part_doc_list = $this->weekly_model->get_ready_to_publish_part_wise_gazette_details($year, $week);
                    
                    $pdf_files = array();
                    
                    $dynamic_values = array();
                    
                    foreach ($publish_part_doc_list as $key => $doc_file) {
                        // PDF files to be merged
                        $pdf_files[] = $doc_file->pdf_file_path;
                    }
                    
                    $pdf_array = $this->convert_press_final_to_PDF($pdf_files, $data);

                    // Pass information for e-Sign purpose from controller to view
                    $data['final_pdf_id'] = $pdf_array['pdf_id'];

                    $data['pdf_details'] = $this->db->select('*')
                                                    ->from('gz_final_weekly_gazette')
                                                    ->where('id', $pdf_array['pdf_id'])
                                                    ->get()->row();

                    $data['pdf_file_path'] = $pdf_array['pdf_file_path'];
                    
                    // user details to be passed 
                    $data['signed_name'] = $this->session->userdata('name');
                    $data['designation'] = $this->session->userdata('designation');
                    
                    $this->load->view('template/header.php', $data);
                    $this->load->view('template/sidebar.php');
                    $this->load->view('weekly_gazette/publish_gazette_pdf_preview.php', $data);
                    $this->load->view('template/footer.php');
                }

            } else {
                $this->load->view('template/header.php', $data);
                $this->load->view('template/sidebar.php');
                $this->load->view('weekly_gazette/view_part_wise_publish_gazette', $data);
                $this->load->view('template/footer.php');
            }
            
        }
        
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php', $data);
        $this->load->view('weekly_gazette/publish_gazette_form.php', $data);
        $this->load->view('template/footer.php', $data);
        
    }
    
    /*
     * Final preview by Press before publishing a wekly gazette
     */
    
    public function press_final_preview() {
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

        $data['title'] = 'Preview Part Gazette';

        $this->form_validation->set_rules('week', 'Week', 'trim|required');
        $this->form_validation->set_rules('year', 'Year', 'trim|required');

        if ($this->form_validation->run() == false) {
            //$this->load->view('weekly_gazette/press_publish_view', $data);
        } else {
            
            $week = $this->input->post('week');
            $year = $this->input->post('year');
            
            $data['week'] = $week;
            $data['year'] = $year;
            
            $publish_part_doc_list = $this->weekly_model->get_ready_to_publish_part_wise_gazette_details($year, $week);
            
            $pdf_files = array();
            
            $dynamic_values = array();
            
            foreach ($publish_part_doc_list as $key => $doc_file) {
                // PDF files to be merged
                $pdf_files[] = $doc_file->pdf_file_path;
            }
            
            $pdf_array = $this->convert_press_final_to_PDF($pdf_files, $data);

            // Pass information for e-Sign purpose from controller to view
            $data['final_pdf_id'] = $pdf_array['pdf_id'];

            $data['pdf_details'] = $this->db->select('*')
                                            ->from('gz_final_weekly_gazette')
                                            ->where('id', $pdf_array['pdf_id'])
                                            ->get()->row();

            $data['pdf_file_path'] = $pdf_array['pdf_file_path'];
			
            // user details to be passed 
            $data['signed_name'] = $this->session->userdata('name');
            $data['designation'] = $this->session->userdata('designation');
			
        }
        
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php', $data);
        $this->load->view('weekly_gazette/publish_gazette_pdf_preview.php', $data);
        $this->load->view('template/footer.php');
		
    }
	
	
    /*
     * Press final PDF view after eSign process  
     */
     public function press_final_esign_pdf_view($final_pdf_id) {
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
        $data['title'] = "Press eSign View";

        $data['details'] = $this->db->select('*')
                                    ->from('gz_final_weekly_gazette')
                                    ->where('id', $final_pdf_id)
                                    ->get()->row();

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php', $data);
        $this->load->view('weekly_gazette/press_pdf_view.php', $data);
        $this->load->view('template/footer.php');
	
    }
    
    
    /*
     * Final convert the word to PDF
     */
    
    public function convert_press_final_to_PDF($pdf_files, $data) {

        // Assign the template file to the file array
        $file_items = array();

        foreach ($pdf_files as $individual_file) {
            $file_items[] = FCPATH . $individual_file;
        }
        
        // PDF file path
        $pdf_file_db_path = './uploads/press_weekly_pdf/' . time() . '.pdf';
        $pdf_file_path = FCPATH . $pdf_file_db_path;
        
        // and now we can use library
        $pdf = new \Jurosh\PDFMerge\PDFMerger;

        foreach ($file_items as $file_pdf) {
            // add as many pdfs as you want
            $pdf->addPDF($file_pdf, 'all');
        }
        // call merge, output format `file`
        $pdf->merge('file', $pdf_file_path);
        
        // Database data array
        $data_pdf_array = array(
            'week' => $data['week'],
            'year' => $data['year'],
            'word_file_path' => '',
            'pdf_file_path' => $pdf_file_db_path,
            'created_at' => date('Y-m-d H:i:s', time())
        );
        
        // INSERT/UPDATE into database
        $final_pdf_id = $this->weekly_model->insert_update_final_pdf($data_pdf_array);

        // Store Audit Log
        audit_action_log($this->session->userdata('user_id'), 'Weekly Gazette', 'Final PDF', date('Y-m-d H:i:s', time()), $this->input->ip_address());
        
        $pdf_data = array(
            'pdf_id' => $final_pdf_id,
            'pdf_file_path' => $pdf_file_db_path
        );

        return $pdf_data;
        
    }


    /*
     * Callback function for Dept. User word file Resubmit upload
     */

    public function handle_dept_gazette_doc_resubmit() {

        $this->doc_file = '';

        if (!empty($_FILES['doc_files']['name']) && ($_FILES['doc_files']['size'] > 0)) {

            $upload_dir = "./uploads/dept_doc/";

            if (!is_dir($upload_dir) && !is_writable($upload_dir)) {
                mkdir($upload_dir);
            }

            $config['upload_path'] = $upload_dir;
            $config['allowed_types'] = array('doc', 'docx');
            $config['file_name'] = $_FILES['doc_files']['name'];
            $config['overwrite'] = true;
            $config['encrypt_name'] = TRUE;
            // 5 MB
            $config['max_size'] = '5242880';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('doc_files')) {
                $this->form_validation->set_message('handle_dept_gazette_doc_resubmit', $this->upload->display_errors('', ''));
                return false;
            } else {
                $this->upload_data['file'] = $this->upload->data();
                $this->doc_file = $upload_dir . $this->upload_data['file']['file_name'];
                return true;
            }
        } else {
            $this->form_validation->set_message('handle_dept_gazette_doc_resubmit', 'No file selected');
        }
    }

    /*
     * View for Department User
     */

    public function dept_view($gazette_id, $user_id) {
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

        if (!$this->weekly_model->exists($gazette_id)) {
            $this->session->set_flashdata('error', 'The gazette does not exists');
            redirect('weekly_gazette/index');
        }
        // Check the gazette of one dept. cannot view another dept. gazette
        if (!$this->weekly_model->check_gazette_permission_exists($gazette_id, $user_id)) {
            $this->session->set_flashdata('error', 'You do not have permision to view the document');
            redirect('weekly_gazette/index');
        }

        $data['title'] = "View Weekly Gazette";

        $data['details'] = $this->weekly_model->get_gazette_details($gazette_id);
        // change the status as per dept user
        $data['status_list'] = $this->weekly_model->get_dept_gazette_status_lists($gazette_id, $user_id);

        $data['document_list'] = $this->weekly_model->get_dept_gazette_document_lists($gazette_id, $user_id);

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('weekly_gazette/dept_view.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * View for Govt Press User
     */

    public function press_view($gazette_id) {
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

        if (!$this->weekly_model->exists($gazette_id)) {
            $this->session->set_flashdata('error', 'The gazette does not exists');
            redirect('weekly_gazette/index');
        }

        $data['title'] = "View Gazette";

        $data['details'] = $this->weekly_model->get_gazette_details($gazette_id);
        $data['status_list'] = $this->weekly_model->get_gazette_status_lists($gazette_id);
         
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('weekly_gazette/press_view.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Reject gazette
     */

    public function reject_gazette() {
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

        $this->form_validation->set_rules('gazette_id', 'Gazette ID', 'trim|required|numeric');
        $this->form_validation->set_rules('dept_id', 'Department ID', 'trim|required|numeric');
        $this->form_validation->set_rules('part_id', 'Part ID', 'trim|required|numeric');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|numeric');
        $this->form_validation->set_rules('reject_remarks', 'Reject Remarks', 'trim|required|min_length[6]|max_length[200]');
        if ($this->form_validation->run() == FALSE) {
            //$this->load->view('weekly_gazette/press_view/');
        } else {

            $gazette_id = $this->input->post('gazette_id');
            $dept_id = $this->input->post('dept_id');
            $part_id = $this->input->post('part_id');
            $user_id = $this->input->post('user_id');
            $remarks = $this->input->post('reject_remarks');

            if (!$this->weekly_model->exists($gazette_id)) {
                $this->session->set_flashdata('error', 'The gazette does not exists');
                redirect('weekly_gazette/index');
            }

            // Insert into status table
            $stat_array = array(
                'gazette_id' => $gazette_id,
                'user_id' => $this->session->userdata('user_id'),
                'status_id' => 3,
                'origin_user' => $this->input->post('user_id'),
                'dept_id' => $this->input->post('dept_id'),
                'part_id' => $this->input->post('part_id'),
                'reject_remarks' => $this->input->post('reject_remarks'),
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s', time())
            );

            if ($this->weekly_model->reject_gazette($stat_array)) {

                $gazette_details = $this->weekly_model->get_gazette_user_details($gazette_id);

                /*
                $email_content = "<div style=\"background-color:#e8e8e8;margin:0;padding:0\">
                                <center style=\"background-color:#e8e8e8\">
                                <table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"#E8E8E8\">
                                    <tbody>
                                        <tr>
                                            <td valign=\"middle\" align=\"center\" height=\"60\" style=\"border-collapse:collapse\"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table cellspacing=\"0\" cellpadding=\"0\" width=\"90%\" bgcolor=\"#E8E8E8\">
                                <tbody>
                                    <tr>
                                    <td>
                                        <table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" bgcolor=\"#FFFFFF\" style=\"border-style:solid;border-color:#b4bcbc;border-width:1px\">
                                <tbody>
                                    <tr>
                                    <td>
                                <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" bgcolor=\"#FFFFFF\" valign=\"center\" align=\"center\">
                                <tbody>
                                <tr>
                                <td style=\"padding:30px 0px 0px;color:#545d5e;font-weight:lighter;font-family:Helvetica;font-size:12px;line-height:180%;vertical-align:top;text-align:center\">
                                <span><a href=\"#\" style=\"color:#545d5e;text-decoration:none;outline:none\" data-saferedirecturl=\"#\"><img src=\"" . base_url() . "assets/images/logo_for_email.png" . "\" style=\"border:none;outline:none;width:250px;\" class=\"CToWUd\"></a><br></span></td>
                                </tr>
                                <tr>
                                <td class=\"m_8193269747688794827mktEditable\" id=\"m_8193269747688794827body\" valign=\"center\" cellpadding=\"0\" align=\"center\" bgcolor=\"#FFFFFF\" style=\"border-collapse:collapse;color:#545d5e;font-family:Arial,Tahoma,Verdana,sans-serif;font-size:14px;font-weight:lighter;margin:0;text-align:left;line-height:165%;letter-spacing:0;padding-top:20px;padding-bottom:60px;padding-left: 30px;padding-right: 30px;\">
                                <p style=\"color: #000 !important\">Hii {$gazette_details->name},</p>
                                <p style=\"color: #000 !important\">
                                    Gazette has been returned by Director of Printing, Stationary and Publication for (StateName) Press E-Gazette System.<br/>
                                    Department : {$gazette_details->department_name}<br/>
                                    Gazette Type : {$gazette_details->gazette_type}<br/>
                                </p>
                                <br/>
                                <p style=\"color: #000 !important\">
                                Regards,
                                <br/>
                                (StateName) Press E-Gazette System
                                </p>	                      
                                </td>
                                </tr>
                                </tbody>
                                </table>
                                </td>
                                </tr>
                                </tbody>
                                </table>
                                </td>
                                </tr>
                                </tbody>
                                </table>
                                <table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#E8E8E8\">
                                <tbody>
                                </tbody>
                                </table>
                                <table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"#E8E8E8\">
                                <tbody>
                                <tr>
                                <td valign=\"middle\" align=\"center\" height=\"70\" style=\"border-collapse:collapse\"></td>
                                </tr>
                                </tbody>
                                </table>
                                </center>
                                </div>";

                $this->email->from('ntspl.demo5@gmail.com', '(StateName) Press E-Gazette System');
                $this->email->to($gazette_details->email);
                $this->email->subject('Gazette returned by Directorate of Printing, Stationary and Publication');
                $this->email->message($email_content);
                $this->email->set_newline("\r\n");
                $this->email->send();

                */
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Weekly Gazette', 'Returned', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'Gazette has been returned to department successfully');
                redirect('weekly_gazette/press_view/' . $gazette_id);
            } else {
                $this->session->set_flashdata('error', 'Gazette not returned');
                redirect('weekly_gazette/press_view/' . $gazette_id);
            }
        }
    }

    /*
     * Department submitted
     */

    public function dept_submitted() {
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

        $gazette_id = $this->input->post('gazette_id');

        // Insert into status table
        $array = array(
            'gazette_id' => $gazette_id,
            'user_id' => $this->session->userdata('user_id'),
            'dept_id' => $this->input->post('dept_id'),
            'part_id' => $this->input->post('part_id'),
            'status_id' => 1,
            //'dept_signed_pdf_path' => $signed_file,
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s', time()),
            'modified_at' => date('Y-m-d H:i:s', time())
        );

        if ($this->weekly_model->dept_submit_gazette($array)) {
            
            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'Weekly Gazette', 'Department Resubmitted', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            
            $this->session->set_flashdata('success', 'Gazette submitted successfully');
            redirect('weekly_gazette/index');
        } else {
            $this->session->set_flashdata('error', 'Gazette not submitted');
            redirect('weekly_gazette/dept_preview/' . $gazette_id);
        }
    }

    /*
     * Press preview 
     */

    public function press_preview() {
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

        $this->form_validation->set_rules('gazette_id', 'Gazette ID', 'trim|required');
        $this->form_validation->set_rules('sl_no', 'Sl No', 'trim|required');
        $this->form_validation->set_rules('issue_date', 'Issue Date', 'trim|required');
        $this->form_validation->set_rules('sakabda_date', 'Shakabda Date', 'trim|required');

        if ($this->form_validation->run() == false) {
            //$this->load->view('weekly_gazette/press_add', $data);
        } else {

            $gazette_id = $this->input->post('gazette_id');

            // data needs to be updated
            $array_data = array(
                'gazette_id' => $gazette_id,
                'user_id' => $this->session->userdata('user_id'),
                'issue_date' => $this->input->post('issue_date'),
                'sl_no' => $this->input->post('sl_no'),
                'sakabda_date' => $this->input->post('sakabda_date'),
                // press preview status
                'status_id' => 6
            );

            $data['month'] = $this->input->post('month');
            $data['week'] = $this->input->post('week');

            // get the userdata from database using model
            $fianl_gazette_id = $this->weekly_model->save_preview_press_gazette($array_data);

            if ($fianl_gazette_id) {

                $data['title'] = "View Gazette";
                $data['details'] = $this->weekly_model->approved_gazette_details($fianl_gazette_id);

                // Fetch department data from database 
                $data['dept_data_list'] = $this->weekly_model->get_part_wise_approved_gazette_details($this->input->post('month'), $this->input->post('week'));

                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Weekly Gazette', 'Press Approved Weekly', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->load->view('template/header.php', $data);
                $this->load->view('template/sidebar.php');
                $this->load->view('weekly_gazette/press_publish_view.php', $data);
                //$this->load->view('template/footer.php');
            } else {
                //Put the array in a session            
                $this->session->set_flashdata('error', 'Something went wrong');
                redirect('weekly_gazette/index');
            }
        }
    }

    public function press_pdf_view($gazette_id) {
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

        $data['title'] = "View Gazette";
        $data['gazette_id'] = $gazette_id;
        $data['details'] = $this->weekly_model->get_gazette_details($gazette_id);

        $this->form_validation->set_rules('month', 'Month', 'trim|required|numeric');
        $this->form_validation->set_rules('week', 'Week', 'trim|required|numeric');
        $this->form_validation->set_rules('gazette_id', 'Gazette ID', 'trim|required|numeric');

        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $month = $this->input->post('month');
            $week = $this->input->post('week');
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('weekly_gazette/press_pdf_view.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Get part section based on the part selected by department user in weekly gazette 
     */

    public function get_part_section() {
        $part_id = $this->input->post('part_id');
        $result = $this->weekly_model->get_gazette_action_part_details($part_id);
        echo $result->section_name;
    }

    public function view_weekly_gazette() {
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

        $data['title'] = "View Weekly Gazette";

        $data['parts'] = $this->weekly_model->get_gazette_parts();

        $data['years'] = $this->weekly_model->get_gazette_years();

        $this->form_validation->set_rules('part_id', 'Part ID', 'trim|required|numeric');
        $this->form_validation->set_rules('week', 'Week', 'trim|required|numeric');
        $this->form_validation->set_rules('year', 'Year', 'trim|required|numeric');
        $this->form_validation->set_rules('section', 'Section', 'trim|required');

        if ($this->form_validation->run() == false) {
            //$this->load->view('gazette/add', $data);
        } else {

            $year = $this->input->post('year');
            $week = $this->input->post('week');
            $part_id = $this->input->post('part_id');

            // Check if that year, week and part has approved contents / not
            $result_appr = $this->db->select('appr.*')
                                    ->from('gz_weekly_gazette_part_wise_press_approved appr')
                                    ->join('gz_weekly_gazette_dept_parts parts', 'appr.dept_part_id = parts.id')
                                    ->where('parts.year', $year)
                                    ->where('week', $week)
                                    ->where('parts.part_id', $part_id)->get();

            // Check if PART - I is merged at last
            // $this->db->select()->from()->where('year', $year)->where('week', $week);

            
            if ($result_appr->num_rows() > 0) {
                
                $data['year'] = $year;
                $data['week'] = $week;
                $data['part_id'] = $part_id;
                $data['dept_lists'] = $this->weekly_model->get_dept_wise_weekly_gazette_details($year, $week, $part_id);

                // Check if the selected month, part and week gazette has been merged / not
                $result_merged = $this->db->select('*')
                                    ->from('gz_weekly_part_wise_approved_merged_documents')
                                    ->where('year', $year)
                                    ->where('week', $week)
                                    ->where('part_id', $part_id)->get();
            
                if ($result_merged->num_rows() > 0) {
                    $data['merged_available'] = FALSE;
                } else {
                    $data['merged_available'] = TRUE;
                }
                
                $this->load->view('template/header.php', $data);
                $this->load->view('template/sidebar.php');
                $this->load->view('weekly_gazette/view_dept_wise_gazette.php', $data);
                //$this->load->view('template/footer.php');
                
            } else {
                $this->session->set_flashdata('error', 'Gazette has not been approvd by Govt. Press for the selected Year, Week and Part');
                redirect('weekly_gazette/view_weekly_gazette');
            }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('weekly_gazette/view_weekly_gazette.php', $data);
        $this->load->view('template/footer.php');
    }

    public function press_preview_dept_gazette() {
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

        $data['title'] = "View Weekly Gazette Department Wise";

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('weekly_gazette/view_weekly_gazette.php', $data);
        $this->load->view('template/footer.php');
    }

	
	/*
     * GET Dept. signed PDF file path after signing using e-Sign from CDAC.
     * For the time being we are redirecting to .NET code, again .NET code is providing the signed file
     */

    public function get_signed_pdf_path() {

        $pdf_file_name = $this->input->get('files');

        $gazette_id = $this->input->get('gazette_id');

        $type = $this->input->get('type');

        $dept_id = $this->input->get('dept_id');

        $part_id = $this->input->get('part_id');
		
        // signed PDF file path
        $signed_pdf_path = './uploads/dept_weekly_signed_pdf/' . $pdf_file_name;

        $data = array(
            'gazette_id' => $gazette_id,
            'part_id' => $part_id,
            'dept_id' => $dept_id,
            'dept_signed_pdf_path' => $signed_pdf_path
        );
        
        $result = $this->weekly_model->update_dept_signed_pdf_path($data);
        
        if ($result) {
            
            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'Weekly Gazette', 'Department Signed', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            
            $this->session->set_flashdata('success', 'Document signed successfully');
            redirect('weekly_gazette/dept_preview/' . $gazette_id);
        }
    }
	
	
    /*
     * GET Govt. Press signed PDF file path after signing using e-Sign from CDAC.
     * For the time being we are redirecting to .NET code, again .NET code is providing the signed file
     */

    public function get_press_signed_pdf_path() {

        $pdf_file_name = $this->input->get('files');

        $gazette_id = $this->input->get('gazette_id');

        $type = $this->input->get('type');
		
        // signed PDF file path
        $signed_pdf_path = './uploads/press_weekly_signed_pdf/' . $pdf_file_name;

        $data = array(
            'gazette_id' => $gazette_id,
            'press_signed_pdf_path' => $signed_pdf_path,
            'press_signed_pdf_file_size' => 0
        );
        
        $result = $this->weekly_model->update_press_signed_pdf_path($data);
        
        if ($result) {
            
            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'Weekly Gazette', 'Press Signed', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            
            $this->session->set_flashdata('success', 'Document signed successfully');
            redirect('weekly_gazette/press_final_esign_pdf_view/' . $gazette_id);
        }
    }


    /*
     * Get Year wise week details
     */

	public function get_year_wise_week () {
        if (!empty($this->input->post('year')) && is_numeric($this->input->post('year'))) {
            $results = $this->weekly_model->get_year_wise_week($this->input->post('year'));
            $output = "";
            if (!empty($results)) {
                foreach ($results as $result) {
                    $output .= '<option value="' . $result->week . '">' . $result->week . '</option>';
                }
            } else {
                $output = "";
            }

            echo $output;
        }
    }

    /*
     * Get Merged Year wise week details
     */

    public function get_merged_year_wise_week () {
        //$this->output->enable_profiler(true);
        if (!empty($this->input->post('year')) && is_numeric($this->input->post('year'))) {
            $results = $this->weekly_model->get_year_wise_merged_weeks($this->input->post('year'));
            var_dump($results);
            $output = "";
            if (!empty($results)) {
                foreach ($results as $result) {
                    $output .= '<option value="' . $result->week . '">' . $result->week . '</option>';
                }
            } else {
                $output = "";
            }

            echo $output;
        }
    }


}

?>