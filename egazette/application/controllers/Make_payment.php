<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/vendor/autoload.php';

use DocxMerge\DocxMerge;

class Make_payment extends MY_Controller {

    /**
     * __construct function.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'pagination', 'smtp', 'my_pagination', 'form_validation', 'encryption'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom', 'captcha'));
        $this->load->model(array('Make_payment_model', 'Applicants_login_model'));

    }

    public function change_name () {
        if (!$this->session->userdata('is_applicant')) {
            if ($this->session->userdata('is_c&t') || $this->session->userdata('is_igr')) {
                if ($this->session->userdata('is_c&t')) {
                    $this->session->set_flashdata('error', 'You are not authorized!');
                    redirect('commerce_transport_department/login_ct');
                } else if ($this->session->userdata('is_igr')) {
                    $this->session->set_flashdata('error', 'You are not authorized!');
                    redirect('igr_user/login');
                }
            } else {
                $this->session->set_flashdata('error', 'You are not authorized!');
                redirect('applicants_login/index');
            }
        }
        $data['title'] = "Make Payment Change of Name/Surname";
        
        $config["base_url"] = base_url() . "make_payment/change_name";
        $config["total_rows"] = $this->Make_payment_model->get_total_change_name_count_applicant();

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

        $data['change_of_names'] = $this->Make_payment_model->get_total_change_of_names_applicant($config['per_page'], $offset);

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('make_payment/change_name.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
    
    public function change_partnership () {
        if (!$this->session->userdata('is_applicant')) {
            if ($this->session->userdata('is_c&t') || $this->session->userdata('is_igr')) {
                if ($this->session->userdata('is_c&t')) {
                    $this->session->set_flashdata('error', 'You are not authorized!');
                    redirect('commerce_transport_department/login_ct');
                } else if ($this->session->userdata('is_igr')) {
                    $this->session->set_flashdata('error', 'You are not authorized!');
                    redirect('igr_user/login');
                }
            } else {
                $this->session->set_flashdata('error', 'You are not authorized!');
                redirect('applicants_login/index');
            }
        }
        $data['title'] = "Make Payment Change of Name/Surname";

        $config["base_url"] = base_url() . "make_payment/change_partnership";
        $config["total_rows"] = $this->Make_payment_model->get_total_change_partnership_count_applicant();

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

        $data['change_of_names'] = $this->Make_payment_model->get_total_change_of_partnership_applicant($config['per_page'], $offset);

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('make_payment/change_partnership.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
    
    public function make_payment_name_surname($id) {
        if (!$this->session->userdata('is_applicant')) {
            if ($this->session->userdata('is_c&t') || $this->session->userdata('is_igr')) {
                if ($this->session->userdata('is_c&t')) {
                    $this->session->set_flashdata('error', 'You are not authorized!');
                    redirect('commerce_transport_department/login_ct');
                } else if ($this->session->userdata('is_igr')) {
                    $this->session->set_flashdata('error', 'You are not authorized!');
                    redirect('igr_user/login');
                }
            } else {
                $this->session->set_flashdata('error', 'You are not authorized!');
                redirect('applicants_login/index');
            }
        }
        if (!$this->Make_payment_model->exists($id, 'gz_change_of_name_surname_master', 'current_status', 9)) {
            $this->session->set_flashdata('error', 'Change of Name/Surname does not exist');
            redirect('make_payment/change_name');
        }

        $data['title'] = 'Change of Name/Surname View details';
        $data['gz_dets'] = $this->Applicants_login_model->view_details_change_name_surname($id);

        $data['tot_documents'] = $this->Applicants_login_model->get_total_tot_document_change_name_surname();
        $data['status_list'] = $this->Applicants_login_model->get_status_history($id);
        $data['docu_list'] = $this->Applicants_login_model->get_document_history($id);
        $data['id'] = $id;
        $data['file_no'] = $data['gz_dets']->file_no;
        $data['per_page_value'] = $this->Applicants_login_model->get_per_page_value_change_of_name_surname();

        // Binary Key
        $data['binary_key'] = './binary_key/EGZ_binary_UAT.key';

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/view_details_name_surname.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    private function decrypt($data = '', $key = NULL) {
        if ($key != NULL && $data != "") {
            $method = "AES-256-ECB";
            $dataDecoded = base64_decode($data);
            $decrypted = openssl_decrypt($dataDecoded, $method, $key, OPENSSL_RAW_DATA);
            
            return $decrypted;
        } else {
            return "Encrypted String to decrypt, Key is required.";
        }
    }

    //new controller
    public function make_payment_name_surname_offline() { 
        // Binary File
        $binary_file_path = './binary_key/EGZ_binary_UAT.key';

        $handle = fopen($binary_file_path, "rb");
        $secret_key = fread($handle, filesize($binary_file_path));
        // Get the message string in Response from IFMS
        $message = $this->decrypt($this->input->post('msg'), $secret_key);
        // explode the data string separated by |
        $data_array = explode("|", $message);
        // 
        $record_id = $this->input->post('record_id'); 
        $data['tot_amnt'] = $data_array[20] ;
        $data['depositor_name'] = $this->input->post('depositor_name'); 
        $data['mobile'] = $this->input->post('mobile'); 
        $data['email'] = $this->input->post('email'); 
        $data['file_no'] = $this->input->post('file_no'); 
        $data['deptCode'] = $this->input->post('deptCode'); 
        $data['msg'] = $this->input->post('msg'); 

        $check_status = $this->Applicants_login_model->check_surname_offline_pay_status($record_id); 

        if($check_status->offline_pay_status == 1)
        {
            $data['status_set'] = '1';
        }else{
            $set_status = $this->Applicants_login_model->set_surname_offline_pay_status($record_id,$data['tot_amnt']);
            if($set_status == true)
            {
                $data['status_set'] = '1';
            }
            else{
                $data['status_set'] = '2';
            }
        }
        
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('change_of_name_surname/view_slip_name_surname_offline_pay.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
      
    public function make_payment_patnership_offline() { 
        // Binary File
        $binary_file_path = './binary_key/EGZ_binary_UAT.key';

        $handle = fopen($binary_file_path, "rb");
        $secret_key = fread($handle, filesize($binary_file_path));
        // Get the message string in Response from IFMS
        $message = $this->decrypt($this->input->post('msg'), $secret_key);
        // explode the data string separated by |
        $data_array = explode("|", $message);

        $record_id = $this->input->post('record_id'); 
        $data['tot_amnt'] = $data_array[20];
        $data['depositor_name'] = $this->input->post('depositor_name'); 
        $data['mobile'] = $this->input->post('mobile'); 
        $data['email'] = $this->input->post('email'); 
        $data['file_no'] = $this->input->post('file_no'); 
        $data['deptCode'] = $this->input->post('deptCode'); 
        $data['msg'] = $this->input->post('msg'); 
        $check_status = $this->Applicants_login_model->check_partnership_offline_pay_status($record_id); 
        
        if($check_status->offline_pay_status == 1)
        {
            $data['status_set'] = '1';
        }else{
            $set_status = $this->Applicants_login_model->set_partnership_pay_status($record_id,$data['tot_amnt']);
            if($set_status == true)
            {
                $data['status_set'] = '1';
            }
            else{
                $data['status_set'] = '2';
            }
        }
        
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('change_of_name_surname/view_slip_partnership_offline_pay.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    public function make_payment_department_offline() {
        $record_id = $this->input->post('record_id'); 
        $data['tot_amnt'] = $this->input->post('tot_amnt'); 
        $data['depositor_name'] = $this->input->post('depositor_name'); 
        $data['mobile'] = $this->input->post('mobile'); 
        $data['email'] = $this->input->post('email'); 
        $data['dept_name'] = $this->input->post('dept_name'); 
        $data['deptCode'] = $this->input->post('deptCode'); 
        $data['msg'] = $this->input->post('msg');  
        $check_status = $this->Applicants_login_model->check_department_offline_pay_status($record_id); 
        
        if($check_status->offline_pay_status == 1)
        {
            $data['status_set'] = '1';
        }else{
            $set_status = $this->Applicants_login_model->set_department_off_pay_status($record_id,$data['tot_amnt']);
            if($set_status == true)
            {
                $data['status_set'] = '1';
            }
            else{
                $data['status_set'] = '2';
            }
        }
        
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('change_of_name_surname/view_slip_department_offline_pay.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
    
    public function make_payment_partnership($id) {
        // Set a same-site cookie for first-party contexts
        // header('Set-Cookie: cookie1=value1; SameSite=Lax', false);
        // // Set a cross-site cookie for third-party contexts
        // header('Set-Cookie: cookie2=value2; SameSite=None; Secure', false);
        
        if (!$this->Make_payment_model->exists($id, 'gz_change_of_partnership_master', 'cur_status', 6)) {
            $this->session->set_flashdata('error', 'Change of Name/Surname does not exist');
            redirect('make_payment/change_name');
        }
        
        $data['title'] = 'Partnership View details';

        $data['gz_dets'] = $this->Applicants_login_model->View_details_par($id);

        $data['tot_docus'] = $this->Applicants_login_model->get_total_tot_docu();

        $data['details'] = $this->Applicants_login_model->select_cur_path_load($id);
        //print_r($data['details']);exit();

        $data['count'] = count($data['tot_docus']);
        $data['status_list'] = $this->Applicants_login_model->status_list($id);
        $data['docu_list'] = $this->Applicants_login_model->docu_list();
        $data['amt_per_page'] = $this->Applicants_login_model->amt_per_page();

        $data['binary_key'] = './binary_key/EGZ_binary_UAT.key';

        $data['par_id'] = $id;

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/applicant_det_edit.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    // Gender Work
    public function change_gender() {
        if (!$this->session->userdata('is_applicant')) {
            if ($this->session->userdata('is_c&t') || $this->session->userdata('is_igr')) {
                if ($this->session->userdata('is_c&t')) {
                    $this->session->set_flashdata('error', 'You are not authorized!');
                    redirect('commerce_transport_department/login_ct');
                } else if ($this->session->userdata('is_igr')) {
                    $this->session->set_flashdata('error', 'You are not authorized!');
                    redirect('igr_user/login');
                }
            } else {
                $this->session->set_flashdata('error', 'You are not authorized!');
                redirect('applicants_login/index');
            }
        }

        $data['title'] = "Make Payment Change of Gender";
        
        $config["base_url"] = base_url() . "make_payment/change_gender";
        $config["total_rows"] = $this->Make_payment_model->get_total_change_gender_count_applicant();

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

        $data['change_of_genders'] = $this->Make_payment_model->get_total_change_of_genders_applicant($config['per_page'], $offset);

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('make_payment/change_gender.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    public function make_payment_gender($id) {
        if (!$this->session->userdata('is_applicant')) {
            if ($this->session->userdata('is_c&t') || $this->session->userdata('is_igr')) {
                if ($this->session->userdata('is_c&t')) {
                    $this->session->set_flashdata('error', 'You are not authorized!');
                    redirect('commerce_transport_department/login_ct');
                } else if ($this->session->userdata('is_igr')) {
                    $this->session->set_flashdata('error', 'You are not authorized!');
                    redirect('igr_user/login');
                }
            } else {
                $this->session->set_flashdata('error', 'You are not authorized!');
                redirect('applicants_login/index');
            }
        }

        if (!$this->Make_payment_model->exists($id, 'gz_change_of_gender_master', 'current_status', 9)) {
            $this->session->set_flashdata('error', 'Change of Gender does not exist');
            redirect('make_payment/change_gender');
        }

        $data['title'] = 'Change of Gender View details';
        $data['gz_dets'] = $this->Applicants_login_model->view_details_change_gender($id);

        $data['tot_documents'] = $this->Applicants_login_model->get_total_document_change_of_gender();
        $data['status_list'] = $this->Applicants_login_model->get_gender_status_history($id);
        $data['docu_list'] = $this->Applicants_login_model->get_gender_document_history($id);
        $data['id'] = $id;
        $data['file_no'] = $data['gz_dets']->file_no;
        $data['per_page_value'] = $this->Applicants_login_model->get_per_page_value_change_of_gender();

        // Binary Key
        $data['binary_key'] = './binary_key/EGZ_binary_UAT.key';

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/view_details_change_of_gender.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    public function make_payment_gender_offline() { 
        // Binary File
        $binary_file_path = './binary_key/EGZ_binary_UAT.key';

        $handle = fopen($binary_file_path, "rb");
        $secret_key = fread($handle, filesize($binary_file_path));
        // Get the message string in Response from IFMS
        $message = $this->decrypt($this->input->post('msg'), $secret_key);
        // explode the data string separated by |
        $data_array = explode("|", $message);
        // 
        $record_id = $this->input->post('record_id'); 
        $data['tot_amnt'] = $data_array[20] ;
        $data['depositor_name'] = $this->input->post('depositor_name'); 
        $data['mobile'] = $this->input->post('mobile'); 
        $data['email'] = $this->input->post('email'); 
        $data['file_no'] = $this->input->post('file_no'); 
        $data['deptCode'] = $this->input->post('deptCode'); 
        $data['msg'] = $this->input->post('msg'); 

        $check_status = $this->Applicants_login_model->check_gender_offline_pay_status($record_id); 

        if($check_status->offline_pay_status == 1)
        {
            $data['status_set'] = '1';
        }else{
            $set_status = $this->Applicants_login_model->set_gender_offline_pay_status($record_id,$data['tot_amnt']);
            if($set_status == true)
            {
                $data['status_set'] = '1';
            }
            else{
                $data['status_set'] = '2';
            }
        }
        
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('change_of_gender/view_slip_gender_offline_pay.php', $data); // created
        $this->load->view('template/footer_applicant.php');
    }
}

    
    
?>