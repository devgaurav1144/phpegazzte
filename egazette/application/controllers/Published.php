<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/vendor/autoload.php';

use DocxMerge\DocxMerge;

class Published extends MY_Controller {

    /**
     * __construct function.
    */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'pagination', 'smtp', 'my_pagination', 'form_validation', 'encryption'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom', 'captcha'));
        $this->load->model(array('Published_model'));

        if (!$this->session->userdata('is_applicant')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }
    }

    public function published_change_name () {
        $data['title'] = "Published Change of Name/Surname";

        $config["base_url"] = base_url() . "published/published_change_name";
        $config["total_rows"] = $this->Published_model->get_total_change_name_count_applicant();

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

        $data['change_of_names'] = $this->Published_model->get_total_change_of_names_applicant($config['per_page'], $offset);

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('published/change_name.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
    
    public function published_change_partnership () {
        $data['title'] = "Published Change of Name/Surname";

        $config["base_url"] = base_url() . "published/published_change_partnership";
        $config["total_rows"] = $this->Published_model->get_total_change_partnership_count_applicant();

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

        $data['partners'] = $this->Published_model->get_total_change_of_partnership_applicant($config['per_page'], $offset);

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('published/change_partnership.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    public function filter_for_published_partnership(){
        $data['title'] = "Published Change of Partnership";

        $config["base_url"] = base_url() . "published/filter_for_published_partnership";

        $searchValue = array(
            'app_name' => trim($this->input->post('name')),
            'file_no' => trim($this->input->post('file_no')),
            'fdate' => trim($this->input->post('notice_date_form')),
            'tdate' => trim($this->input->post('notice_date_to')),
        );

        $inputs = $this->input->post();
        //print_r($inputs);die();
		$page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        }
        else{
            if($page==0){
              $array_items = array('app_name', 'file_no', 'notice_date_form', 'notice_date_to');
              $this->session->unset_userdata($array_items);
              $inputs =array();
            }else{
              $inputs = $this->session->userdata();
            }
        }

        $config["total_rows"] = $this->Published_model->get_total_parter_count_search($inputs);

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

        $data['partners'] = $this->Published_model->get_total_change_of_published_partnership_applicant($config['per_page'], $offset,$inputs);

        $data['inputs'] = $inputs;
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('published/change_partnership.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    public function filter_for_change_name_surname(){
        $data['title'] = "Published Change of Name/Surname";

        $config["base_url"] = base_url() . "published/filter_for_change_name_surname";

        $searchValue = array(
            'app_name' => trim($this->input->post('name')),
            'file_no' => trim($this->input->post('file_no')),
            'fdate' => trim($this->input->post('notice_date_form')),
            'tdate' => trim($this->input->post('notice_date_to')),
        );

        $inputs = $this->input->post();
        //print_r($inputs);die();
		$page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        }
        else{
            if($page==0){
              $array_items = array('app_name', 'file_no', 'notice_date_form', 'notice_date_to');
              $this->session->unset_userdata($array_items);
              $inputs =array();
            }else{
              $inputs = $this->session->userdata();
            }
        }

        $config["total_rows"] = $this->Published_model->get_total_name_surname_count_search($inputs);

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

        $data['partners'] = $this->Published_model->get_total_change_of_published_name_surname_applicant($config['per_page'], $offset,$inputs);
// print_r($data['partners']);exit();
        $data['inputs'] = $inputs;
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('published/change_name.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    // Gender Work
    public function published_change_gender () {
        
        $data['title'] = "Published Change of Gender";

        $config["base_url"] = base_url() . "published/published_change_gender";
        $config["total_rows"] = $this->Published_model->get_total_change_gender_count_applicant();

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

        $data['change_of_genders'] = $this->Published_model->get_total_change_of_genders_applicant($config['per_page'], $offset);

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('published/change_gender.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
    
    public function filter_for_change_gender(){
        $data['title'] = "Published Change of Gender";

        $config["base_url"] = base_url() . "published/filter_for_change_gender";

        $searchValue = array(
            'app_name' => trim($this->input->post('name')),
            'file_no' => trim($this->input->post('file_no')),
            'fdate' => trim($this->input->post('notice_date_form')),
            'tdate' => trim($this->input->post('notice_date_to')),
        );

        $inputs = $this->input->post();
        //print_r($inputs);die();
		$page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        }
        else{
            if($page==0){
              $array_items = array('app_name', 'file_no', 'notice_date_form', 'notice_date_to');
              $this->session->unset_userdata($array_items);
              $inputs =array();
            }else{
              $inputs = $this->session->userdata();
            }
        }

        $config["total_rows"] = $this->Published_model->get_total_gender_count_search($inputs);

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

        $data['partners'] = $this->Published_model->get_total_change_of_published_gender_applicant($config['per_page'], $offset,$inputs);

        $data['inputs'] = $inputs;
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('published/change_gender.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
}
?>