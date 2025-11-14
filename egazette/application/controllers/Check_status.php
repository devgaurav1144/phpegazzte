<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/vendor/autoload.php';

use DocxMerge\DocxMerge;

class Check_status extends MY_Controller {

    /**
     * __construct function.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'pagination', 'smtp', 'my_pagination', 'form_validation', 'encryption'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom', 'captcha'));
        $this->load->model(array('Check_status_model', 'Applicants_login_model', 'Notification_model'));
    }

    /*
     * Gender Change methods start
    */

    public function change_gender_status() {
        if (!$this->session->userdata('is_applicant')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }
        // echo 'test';exit;
        $data['title'] = 'Check Status for Change of Gender';
        
        $config['base_url'] = base_url() . 'check_status/change_gender_status';
        $config['total_rows'] = $this->Check_status_model->get_total_change_of_gender_count_applicant(); // created
        // exit;
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config["num_links"] = 2;
        $config['use_page_numbers'] = TRUE;

        $config['full_tag_open'] = '<ul class="pagination pagination-primary">';
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

        $data['change_of_genders'] = $this->Check_status_model->get_total_change_of_gender_applicant($config['per_page'], $offset); // created

        $data['status'] = $this->Check_status_model->get_gazette_status('gender'); // updated
        // echo '<pre>';
        // print_r($data);exit;
        // $data['status'] = $this->Check_status_model->get_gazette_status_gender(); // updated
        
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('check_status/change_gender.php', $data); // created
        $this->load->view('template/footer_applicant.php');
    }


    public function change_gender_status_details($id) {
        if (!$this->session->userdata('is_applicant')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }
        // error_reporting(0);

        if ( !$this->Check_status_model->exists($id, 'gz_change_of_gender_master') ) {
            $this->session->set_flashdata('error', 'Change of Gender does not exist');
            redirect('check_status/change_gender_status');
        }

        $data['title'] = 'Change of Gender View Details';
        $data['gz_dets'] = $this->Applicants_login_model->view_details_change_of_gender($id);// completed

        $data['states'] = $this->Applicants_login_model->get_states();
        $district_id = $data['gz_dets']->district_id;
        $state_id = $data['gz_dets']->state_id;
        $data['districts'] = $this->Applicants_login_model->get_district_list($state_id);
        $data['block_ulb'] = $this->Applicants_login_model->get_block_list($district_id);
        $data['ulb'] = $this->Applicants_login_model->get_ulb_list($district_id);

        $data['tot_documents'] = $this->Applicants_login_model->get_total_document_change_of_gender(); // completed
        $data['status_list'] = $this->Applicants_login_model->get_gender_status_history($id); // created
        //var_dump($data['status_list']);
        $data['docu_list'] = $this->Applicants_login_model->get_gender_document_history($id); // created
        $data['id'] = $id;
        $data['file_no'] = $data['gz_dets']->file_no;
        $data['per_page_value'] = $this->Applicants_login_model->get_per_page_value_change_of_gender(); // created

        // code is pending from here 
        // Binary Key
        $data['binary_key'] = './binary_key/EGZ_binary_UAT.key';

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/view_details_change_of_gender.php', $data);// file created
        $this->load->view('template/footer_applicant.php');
    }


    public function filter_change_gender() {
        if (!$this->session->userdata('is_applicant')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }
        // echo 'test filter change of gender!';
        if (!$this->session->userdata('is_applicant')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }


        $data['title'] = "Check Status Change of Gender";

        $file_no = '';
        $state_id = '';

        if (
            $this->security->xss_clean($this->input->post('file_no')) != NULL ||
            $this->security->xss_clean($this->input->post('status_id')) != NULL
        ) {
            
            $file_no = $this->security->xss_clean($this->input->post('file_no'));
            $status_id = $this->security->xss_clean($this->input->post('status_id'));
            
            $this->session->set_userdata(
                array(
                    'file_no' => $file_no,
                    'status_id' => $status_id
                )
            );
        } else {
            
            if (
                $this->session->userdata ('file_no') != NULL ||
                $this->session->userdata ('status_id') != NULL
            ) {
                
                $file_no = $this->session->userdata ('file_no');
                $status_id = $this->session->userdata ('status_id');
                
            }
            
        }

        $config["base_url"] = base_url() . "check_status/filter_change_gender";
        $config["total_rows"] = $this->Check_status_model->get_total_change_of_gender_count_applicant_filter($file_no, $status_id);

        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 2;
        $config['use_page_numbers'] = TRUE;

        $config['full_tag_open'] = '<ul class="pagination pagination-primary">';
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

        $data['change_of_names'] = $this->Check_status_model->get_total_change_of_genders_applicant_filter($config['per_page'], $offset, $file_no, $status_id);
        
        $data['status'] = $this->Check_status_model->get_gazette_status('gender');

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('check_status/filter_change_gender.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
    /*
     * Gender Change methods end
    */

    public function change_name_status () {
        
        if (!$this->session->userdata('is_applicant')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }
        $data['title'] = "Check Status Change of Name/Surname";
        
        $config["base_url"] = base_url() . "check_status/change_name_status";
        $config["total_rows"] = $this->Check_status_model->get_total_change_name_count_applicant();

        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 2;
        $config['use_page_numbers'] = TRUE;

        $config['full_tag_open'] = '<ul class="pagination pagination-primary">';
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

        $data['change_of_names'] = $this->Check_status_model->get_total_change_of_names_applicant($config['per_page'], $offset);
        
        $data['status'] = $this->Check_status_model->get_gazette_status('name');
        
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('check_status/change_name.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
    
    public function filter_change_name () {
        if (!$this->session->userdata('is_applicant')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }
        $data['title'] = "Check Status Change of Name/Surname";
        
        $file_no = '';
        $status_id = '';
        
        if (
            $this->security->xss_clean($this->input->post('file_no')) != NULL ||
            $this->security->xss_clean($this->input->post('status_id')) != NULL
        ) {
            
            $file_no = $this->security->xss_clean($this->input->post('file_no'));
            $status_id = $this->security->xss_clean($this->input->post('status_id'));
            
            $this->session->set_userdata(
                array(
                    'file_no' => $file_no,
                    'status_id' => $status_id
                )
            );
        } else {
            
            if (
                $this->session->userdata ('file_no') != NULL ||
                $this->session->userdata ('status_id') != NULL
            ) {
                
                $file_no = $this->session->userdata ('file_no');
                $status_id = $this->session->userdata ('status_id');
                
            }
            
        }
        
        $config["base_url"] = base_url() . "check_status/filter_change_name";
        $config["total_rows"] = $this->Check_status_model->get_total_change_name_count_applicant_filter($file_no, $status_id);

        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 2;
        $config['use_page_numbers'] = TRUE;

        $config['full_tag_open'] = '<ul class="pagination pagination-primary">';
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

        $data['change_of_names'] = $this->Check_status_model->get_total_change_of_names_applicant_filter($config['per_page'], $offset, $file_no, $status_id);
        
        $data['status'] = $this->Check_status_model->get_gazette_status('name');

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('check_status/filter_change_name.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
    
    public function change_name_status_details($id) {
        if (!$this->session->userdata('is_applicant')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }
        error_reporting(0);
        if (!$this->Check_status_model->exists($id, 'gz_change_of_name_surname_master')) {
            $this->session->set_flashdata('error', 'Change of Name/Surname does not exist');
            redirect('check_status/change_name_status');
        }

        $data['title'] = 'Change of Name/Surname View details';
        $data['gz_dets'] = $this->Applicants_login_model->view_details_change_name_surname($id);
        $data['states'] = $this->Applicants_login_model->get_states();
        $district_id = $data['gz_dets']->district_id;
        $state_id = $data['gz_dets']->state_id;
        $data['districts'] = $this->Applicants_login_model->get_district_list($state_id);
        $data['block_ulb'] = $this->Applicants_login_model->get_block_list($district_id);
        $data['ulb'] = $this->Applicants_login_model->get_ulb_list($district_id);
        $data['tot_documents'] = $this->Applicants_login_model->get_total_tot_document_change_name_surname();
        $data['declartion'] = $this->Applicants_login_model->declartions();
        $data['status_list'] = $this->Applicants_login_model->get_status_history($id);
        // print_r($data['status_list']);die("Test");
        //var_dump($data['status_list']);
        $data['docu_list'] = $this->Applicants_login_model->get_document_history($id);
        $data['id'] = $id;
        $data['file_no'] = $data['gz_dets']->file_no;
        $data['per_page_value'] = $this->Applicants_login_model->get_per_page_value_change_of_name_surname();

        //die('testing');
        // Binary Key
        $data['binary_key'] = './binary_key/EGZ_binary_UAT.key';

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/view_details_name_surname.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    public function check_transaction_status() {
        if (!$this->session->userdata('is_applicant')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }
        $data['title'] = "Check Transaction Status";
        
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('check_status/check_transaction_status.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
    
    public function change_partnership_status () {
        if (!$this->session->userdata('is_applicant')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }
        $data['title'] = "Check Status Change of Name/Surname";

        $config["base_url"] = base_url() . "check_status/change_partnership_status";
        $config["total_rows"] = $this->Check_status_model->get_total_change_partnership_count_applicant();

        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 2;
        $config['use_page_numbers'] = TRUE;

        $config['full_tag_open'] = '<ul class="pagination pagination-primary">';
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

        $data['change_of_names'] = $this->Check_status_model->get_total_change_of_partnership_applicant($config['per_page'], $offset);
        
        $data['status'] = $this->Check_status_model->get_gazette_status('partnership');

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('check_status/change_partnership.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    public function filter_change_partnership () {
        if (!$this->session->userdata('is_applicant')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }
        $data['title'] = "Check Status Change of Name/Surname";
        
        $file_no = '';
        $f_date = '';
        $t_date = '';
        $status_id = '';
        
        if (
            $this->security->xss_clean($this->input->post('file_no')) != NULL ||
            $this->security->xss_clean($this->input->post('f_date')) != NULL ||
            $this->security->xss_clean($this->input->post('t_date')) != NULL ||
            $this->security->xss_clean($this->input->post('status_id')) != NULL
        ) {
            
            $file_no = $this->security->xss_clean($this->input->post('file_no'));
            $f_date = $this->security->xss_clean($this->input->post('f_date'));
            $t_date = $this->security->xss_clean($this->input->post('t_date'));
            $status_id = $this->security->xss_clean($this->input->post('status_id'));
            
            $this->session->set_userdata(
                array(
                    'file_no' => $file_no,
                    'f_date' => $f_date,
                    't_date' => $t_date,
                    'status_id' => $status_id
                )
            );
        } else {
            
            if (
                $this->session->userdata ('file_no') != NULL ||
                $this->session->userdata ('f_date') != NULL ||
                $this->session->userdata ('t_date') != NULL ||
                $this->session->userdata ('status_id') != NULL
            ) {
                
                $file_no = $this->session->userdata ('file_no');
                $f_date = $this->session->userdata ('f_date');
                $t_date = $this->session->userdata ('t_date');
                $status_id = $this->session->userdata ('status_id');
                
            }
            
        }
        
        $config["base_url"] = base_url() . "check_status/filter_change_partnership";
        $config["total_rows"] = $this->Check_status_model->get_total_change_partnership_count_applicant_filter($file_no, $f_date, $t_date, $status_id);

        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 2;
        $config['use_page_numbers'] = TRUE;

        $config['full_tag_open'] = '<ul class="pagination pagination-primary">';
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

        $data['change_of_names'] = $this->Check_status_model->get_total_change_of_partnership_applicant_filter($config['per_page'], $offset, $file_no, $f_date, $t_date, $status_id);
        
        $data['status'] = $this->Check_status_model->get_gazette_status('partnership');

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('check_status/filter_change_partnership.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
        
    public function change_partnership_status_details($id) {
        if (!$this->session->userdata('is_applicant')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }
        if (!$this->Check_status_model->exists($id, 'gz_change_of_partnership_master')) {
            $this->session->set_flashdata('error', 'Change of Name/Surname does not exist');
            redirect('make_payment/change_name');
        }
        
        $data['title'] = 'Partnership View details';

        $data['gz_dets'] = $this->Applicants_login_model->View_details_par($id);

        $data['tot_docus'] = $this->Applicants_login_model->get_total_tot_docu();

        $data['details'] = $this->Applicants_login_model->select_cur_path_load($id);

        $data['count'] = count($data['tot_docus']);

        $data['status_list'] = $this->Applicants_login_model->status_list($id);

        $data['docu_list'] = $this->Applicants_login_model->docu_list();

        $data['amt_per_page'] = $this->Applicants_login_model->amt_per_page();

        $data['states'] = $this->Applicants_login_model->get_states();

        $state_id = $data['gz_dets']->state_id;

        $district_id = $data['gz_dets']->district_id;

        $data['districts'] = $this->Applicants_login_model->get_district_list($state_id);

        $data['police_stations'] = $this->Applicants_login_model->get_police_station_list($district_id);

        $data['binary_key'] = './binary_key/EGZ_binary_UAT.key';

        $data['par_id'] = $id;
        
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/applicant_det_edit.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
}
?>