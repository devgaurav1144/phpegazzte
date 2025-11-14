<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Website extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'form_validation', 'pagination', 'my_pagination'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'file', 'custom', 'captcha'));
        $this->load->model(array('website_model', 'Cms_model', 'gazette_model', 'Archival_model'));
    }

    public function index() {

        $data['total_extraordinary'] = $this->website_model->count_total_published_extra_gazettes_frontend();
        $data['total_weekly'] = $this->website_model->count_total_published_weekly_gazettes_frontend();
        $data['visitor_counter'] = $this->website_model->count_total_visitor_counter();

        // $data['extraordinary_gazettes'] = $this->website_model->get_published_extraordinary_gazette_list();
        $data['extraordinary_gazettes'] = $this->website_model->get_latest_published_data();
        $data['weekly_gazettes'] = $this->website_model->get_published_weekly_gazette_list();

        //$data['acknowledgementDetails'] = $this->Cms_model->get_acknowledgement();
        // echo json_encode($data);
        $this->load->view('index.php', $data);
    }

    public function about_us() {
        $data['aboutDetails'] = $this->Cms_model->get_about_us();
        $this->load->view('about-us.php', $data);
    }

    public function grievance() {
        $this->load-view('grievance');  // create a new file grievance.php in views folder
    }

    public function about_gazette() {
        $data['gazetteDetails'] = $this->Cms_model->get_gazette();
        $this->load->view('about-gazette.php', $data);
    }

    public function disclaimer() {
        $data['disclaimer'] = $this->Cms_model->get_disclaimer();
        $this->load->view('disclaimer.php', $data);
    }

    public function archive() {
        $this->load->view('archive.php');
    }

    public function name_surname() {
        $this->load->view('name_surname.php');
    }

    public function partnership() {
        $this->load->view('partnership.php');
    }

    public function terms_conditions() {
        $this->load->view('terms_conditions.php');
    }

    public function privacy_policy() {
        $data['myName'] = 'Saroj Sekhar';
        $this->load->view('privacy_policy.php', $data);
    }

    public function copyright_policy() {
        $this->load->view('copyright_policy.php');
    }

    public function contact() {
        $data['captchaValidationMessage'] = "";
        $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
        $data['captcha'] = $this->botdetectcaptcha->Html();
        
        $this->load->view('contact-us.php', $data);
    }

    public function contact_mail(){

        $data['title'] = 'contact mail';

        $data['captchaValidationMessage'] = "";
        $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
        $data['captchaImg'] = $this->botdetectcaptcha->Html();

        // set form validation rules
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[4]|max_length[40]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[6]|max_length[96]|valid_email');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[10]|max_length[10]|numeric');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required|min_length[5]|max_length[100]');
        $this->form_validation->set_rules('message', 'Message', 'trim|required|min_length[5]|max_length[200]');
        $this->form_validation->set_rules('captcha', 'Captcha', 'required');;

        if ($this->form_validation->run() == false) {

            // Captcha
            $data['captchaValidationMessage'] = "";
            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
            $data['captchaImg'] = $this->botdetectcaptcha->Html();

        } else {

            $captcha = $this->security->xss_clean(trim($this->input->post('captcha')));
            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
            $result_captcha = $this->botdetectcaptcha->Validate($captcha);

            if ($result_captcha) {
                $name = $this->input->post('name');
                $email = $this->input->post('email');
                $mobile = $this->input->post('mobile');
                $subject = $this->input->post('subject');
                $message = $this->input->post('message');
                
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
                            <p style=\"color: #000 !important\">Dear Govt. Press,</p>
                            <p style=\"color: #000 !important\">
                                Message from Contact Page.<br/>
                                Name : {$name}<br/>
                                Email : {$email}<br/>
                                Mobile : {$mobile}<br/>
                                Subject : {$subject}<br/>
                                Message : {$message}<br/>
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

                //Load email library
                $this->load->library('email');
                $this->email->from($email, '(StateName) Press E-Gazette System');
                $this->email->to('egazette.(StateName)@gov.in');
                // $this->email->to('mailtestt2022@gmail.com');
                $this->email->subject('Message from contact page');
                $this->email->message($email_content);
                $this->email->set_newline("\r\n");
                $this->email->send();

                //Put the message data in a session            
                $this->session->set_flashdata('success', 'Thank you for contacting us, we will reach you soon.');
                
                redirect('contact_us');

            } else {
                // echo "Hii";exit;
                $data['captchaValidationMessage'] = "Please enter correct captcha";
                $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
                $data['captchaImg'] = $this->botdetectcaptcha->Html();

                $this->session->set_flashdata('error', 'Something went wrong. Please try again after some time.');

                redirect('contact_us');
            }
        }
        // redirect('contact_us');
    }

    public function feedback() {
        
        $data['captchaValidationMessage'] = "";
        $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
        $data['captcha'] = $this->botdetectcaptcha->Html();
       
        $this->load->view('feedback_form.php', $data);
    }

    public function search_gazette() {
        $data['gazette_type'] = $this->gazette_model->get_gazette_types();
        $data['notification_type'] = $this->gazette_model->get_notification_types();
        $data['department_type'] = $this->gazette_model->get_department_types();
        $data['y1'] = $this->gazette_model->get_doc_year();
        $data['uniqueID'] = uniqid();

        $this->load->view('search-gazette.php', $data);
    }
	
	public function extraordinary_archival() {
        $data['notification_type'] = $this->Archival_model->get_notification_types();
        $data['dept'] = $this->Archival_model->get_departments();
        
        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "extraordinary_archival/";
        
        $config["total_rows"] = $this->Archival_model->count_total_gazettes('1');
        
        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
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

        $page = (int) ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        $data['gazettes'] = $this->Archival_model->get_archived_gazette($config["per_page"], $offset, '1');
        
        $this->load->view('extraordinary_archival.php', $data);
    }
    
    public function weekly_archival() {
        $data['notification_type'] = $this->Archival_model->get_notification_types();
        $data['dept'] = $this->Archival_model->get_departments();
        
        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "weekly_archival/";
        
        $config["total_rows"] = $this->Archival_model->count_total_gazettes('2');
        
        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
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

        $page = (int) ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        $data['gazettes'] = $this->Archival_model->get_archived_gazette($config["per_page"], $offset, '2');
        
        $this->load->view('weekly_archival.php', $data);
    }
    
    public function archival_filter_ext () {
        $check = "";
        $dept_id = "";
        $notif_type = "";
        $subject = "";
        $notif_number = "";
        $gz_no = "";
        $keywords = "";
        $f_date = "";
        $t_date = "";
        $week = "";
        $year = "";
        
        if (
            $this->security->xss_clean($this->input->post('check')) != NULL ||
            $this->security->xss_clean($this->input->post('dept_id')) != NULL ||
            $this->security->xss_clean($this->input->post('notification_type_id')) != NULL ||
            $this->security->xss_clean($this->input->post('subject')) != NULL ||
            $this->security->xss_clean($this->input->post('gazette_no')) != NULL ||
            $this->security->xss_clean($this->input->post('keywords')) != NULL ||
            $this->security->xss_clean($this->input->post('f_date')) != NULL ||
            $this->security->xss_clean($this->input->post('t_date')) != NULL ||
            $this->security->xss_clean($this->input->post('notification_number')) != NULL ||
            $this->security->xss_clean($this->input->post('week_id')) != NULL ||
            $this->security->xss_clean($this->input->post('year')) != NULL
                
        ) {
            
            $check = $this->security->xss_clean($this->input->post('check'));
            $dept_id = $this->security->xss_clean($this->input->post('dept_id'));
            $notif_type = $this->security->xss_clean($this->input->post('notification_type_id'));
            $subject = $this->security->xss_clean($this->input->post('subject'));
            $notif_number = $this->security->xss_clean($this->input->post('notification_number'));
            $gz_no = $this->security->xss_clean($this->input->post('gazette_no'));
            $keywords = $this->security->xss_clean($this->input->post('keywords'));
            $f_date = $this->security->xss_clean($this->input->post('f_date'));
            $t_date = $this->security->xss_clean($this->input->post('t_date'));
            $week = $this->security->xss_clean($this->input->post('week_id'));
            $year = $this->security->xss_clean($this->input->post('year'));
            
            $this->session->set_userdata(array(
                'check' => $check,
                'dept_id' => $dept_id,
                'notif_type' => $notif_type,
                'subject' => $subject,
                'notif_number' => $notif_number,
                'gz_no' => $gz_no,
                'keywords' => $keywords,
                'f_date' => $f_date,
                't_date' => $t_date,
                'week' => $week,
                'year' => $year
            ));
        } else {
            
            if (
                $this->session->userdata('check') != NULL ||
                $this->session->userdata('dept_id') != NULL ||
                $this->session->userdata('notif_type') != NULL ||
                $this->session->userdata('subject') != NULL ||
                $this->session->userdata('gz_no') != NULL ||
                $this->session->userdata('keywords') != NULL ||
                $this->session->userdata('f_date') != NULL ||
                $this->session->userdata('t_date') != NULL ||
                $this->session->userdata('notif_number') != NULL ||
                $this->session->userdata('week') != NULL ||
                $this->session->userdata('year') != NULL

            ) {
                
                $check = $this->session->userdata('check');
                $dept_id = $this->session->userdata('dept_id');
                $notif_type = $this->session->userdata('notif_type');
                $subject = $this->session->userdata('subject');
                $notif_number = $this->session->userdata('notif_number');
                $gz_no = $this->session->userdata('gz_no');
                $keywords = $this->session->userdata('keywords');
                $f_date = $this->session->userdata('f_date');
                $t_date = $this->session->userdata('t_date');
                $week = $this->session->userdata('week');
                $year = $this->session->userdata('year');
            }
            
        }
        
        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "extraordinary_archival_search/";
        
        $config["total_rows"] = $this->Archival_model->count_total_gazettes_filter($check, $dept_id, $notif_type, $subject, $notif_number, $gz_no, $keywords, $f_date, $t_date, $week, $year);
        
        $data['dept'] = $this->Archival_model->get_departments();
        $data['notification_types'] = $this->Archival_model->get_notification_types();
        $data['gazette_types'] = $this->Archival_model->get_gazette_types();
        
        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
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

        $page = (int) ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        $data['gazettes'] = $this->Archival_model->get_archived_gazette_filter($config["per_page"], $offset, $check, $dept_id, $notif_type, $subject, $notif_number, $gz_no, $keywords, $f_date, $t_date, $week, $year);

        $this->load->view('search_archival_result_ext.php', $data);
    }
    
    public function archival_filter_week () {
        $check = "";
        $dept_id = "";
        $notif_type = "";
        $subject = "";
        $notif_number = "";
        $gz_no = "";
        $keywords = "";
        $f_date = "";
        $t_date = "";
        $week = "";
        $year = "";
        
        if (
            $this->security->xss_clean($this->input->post('check')) != NULL ||
            $this->security->xss_clean($this->input->post('dept_id')) != NULL ||
            $this->security->xss_clean($this->input->post('notification_type_id')) != NULL ||
            $this->security->xss_clean($this->input->post('subject')) != NULL ||
            $this->security->xss_clean($this->input->post('gazette_no')) != NULL ||
            $this->security->xss_clean($this->input->post('keywords')) != NULL ||
            $this->security->xss_clean($this->input->post('f_date')) != NULL ||
            $this->security->xss_clean($this->input->post('t_date')) != NULL ||
            $this->security->xss_clean($this->input->post('notification_number')) != NULL ||
            $this->security->xss_clean($this->input->post('week_id')) != NULL ||
            $this->security->xss_clean($this->input->post('year')) != NULL
        ) {
            
            $check = $this->security->xss_clean($this->input->post('check'));
            $dept_id = $this->security->xss_clean($this->input->post('dept_id'));
            $notif_type = $this->security->xss_clean($this->input->post('notification_type_id'));
            $subject = $this->security->xss_clean($this->input->post('subject'));
            $notif_number = $this->security->xss_clean($this->input->post('notification_number'));
            $gz_no = $this->security->xss_clean($this->input->post('gazette_no'));
            $keywords = $this->security->xss_clean($this->input->post('keywords'));
            $f_date = $this->security->xss_clean($this->input->post('f_date'));
            $t_date = $this->security->xss_clean($this->input->post('t_date'));
            $week = $this->security->xss_clean($this->input->post('week_id'));
            $year = $this->security->xss_clean($this->input->post('year'));
            
            $this->session->set_userdata(array(
                'check' => $check,
                'dept_id' => $dept_id,
                'notif_type' => $notif_type,
                'subject' => $subject,
                'notif_number' => $notif_number,
                'gz_no' => $gz_no,
                'keywords' => $keywords,
                'f_date' => $f_date,
                't_date' => $t_date,
                'week' => $week,
                'year' => $year,
            ));
        } else {
            
            if (
                $this->session->userdata('check') != NULL ||
                $this->session->userdata('dept_id') != NULL ||
                $this->session->userdata('notif_type') != NULL ||
                $this->session->userdata('subject') != NULL ||
                $this->session->userdata('gz_no') != NULL ||
                $this->session->userdata('keywords') != NULL ||
                $this->session->userdata('f_date') != NULL ||
                $this->session->userdata('t_date') != NULL ||
                $this->session->userdata('notif_number') != NULL ||
                $this->session->userdata('week') != NULL ||
                $this->session->userdata('year') != NULL

            ) {
                
                $check = $this->session->userdata('check');
                $dept_id = $this->session->userdata('dept_id');
                $notif_type = $this->session->userdata('notif_type');
                $subject = $this->session->userdata('subject');
                $notif_number = $this->session->userdata('notif_number');
                $gz_no = $this->session->userdata('gz_no');
                $keywords = $this->session->userdata('keywords');
                $f_date = $this->session->userdata('f_date');
                $t_date = $this->session->userdata('t_date');
                $week = $this->session->userdata('week');
                $year = $this->session->userdata('year');
            }
            
        }
        
        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "weekly_archival_search/";
        
        $config["total_rows"] = $this->Archival_model->count_total_gazettes_filter($check, $dept_id, $notif_type, $subject, $notif_number, $gz_no, $keywords, $f_date, $t_date, $week, $year);
        
        $data['dept'] = $this->Archival_model->get_departments();
        $data['notification_types'] = $this->Archival_model->get_notification_types();
        $data['gazette_types'] = $this->Archival_model->get_gazette_types();
        
        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
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

        $page = (int) ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        $data['gazettes'] = $this->Archival_model->get_archived_gazette_filter($config["per_page"], $offset, $check, $dept_id, $notif_type, $subject, $notif_number, $gz_no, $keywords, $f_date, $t_date, $week, $year);

        $this->load->view('search_archival_result_week.php', $data);
    }
	
    public function search_gazette_result() {
        
        if($this->input->post('gType')){
            $gType = trim($this->input->post('gType'));
            $cYear = trim($this->input->post('cYear'));
            $deptID = trim($this->input->post('deptID'));
            $notNum = trim($this->input->post('notNum'));
            $byDate = trim($this->input->post('byDate'));
            $sByFdate = trim($this->input->post('sByFdate'));
            $sByTdate = trim($this->input->post('sByTdate'));
            $sName = trim($this->input->post('sName'));
            $keyword = trim($this->input->post('keywords'));
            $monthName = trim($this->input->post('monthName'));
            $weekTime = trim($this->input->post('weekTime'));
            $gazette_no = trim($this->input->post('gazette_no'));
            $published_date = trim($this->input->post('published_date'));

            $this->session->set_userdata($this->input->post());
        }else{
            $gType = $this->session->userdata('gType');
            $cYear = $this->session->userdata('cYear');
            $deptID = $this->session->userdata('deptID');
            $notNum = $this->session->userdata('notNum');
            $byDate = $this->session->userdata('byDate');
            $sByFdate = $this->session->userdata('sByFdate');
            $sByTdate = $this->session->userdata('sByTdate');
            $sName = $this->session->userdata('sName');
            $keyword = $this->session->userdata('keywords');
            $monthName = $this->session->userdata('monthName');
            $weekTime = $this->session->userdata('weekTime');
            $gazette_no = $this->session->userdata('gazette_no');
            $published_date = $this->session->userdata('published_date');
        }
        //die($gType);
        $config = array();
        $config["base_url"] = base_url() . "search_gazette_result/";

        if($gType == 1){

            $dataArray = array(
                'gType' => $gType,
                'cYear' => $cYear,
                'deptID' => $deptID,
                'notNum' => $notNum,
                'byDate' => $byDate,
                'sByFdate' => $sByFdate,
                'sByTdate' => $sByTdate,
                'sName' => $sName,
                'keyword' => $keyword,
                'gazette_no' => $gazette_no,
                'published_date' => $published_date
            );

            $config['total_rows'] = $this->gazette_model->gazetta_search_report_count($dataArray);

        } else {

            $dataArray = array(
                'gType' => $gType,
                'cYear' => $cYear,
                'sByFdate' => $sByFdate,
                'sByTdate' => $sByTdate,
                'monthName' => $monthName,
                'weekTime' => $weekTime,
                'keyword' => $keyword,
                'gazette_no' => $gazette_no,
                'published_date' => $published_date
            );
            

            $config['total_rows'] = $this->gazette_model->gazetta_weekly_search_report_count($dataArray);

        }
        //print_r($dataArray);

        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
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

        $page = (int) ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        
        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }
        
        $data["links"] = $this->my_pagination->create_links();

        if ($gType == 1) {            
            
            $data['search_result'] = $this->gazette_model->gazetta_search_report($dataArray, $config["per_page"], $offset);

            $data['gazetteTypeValue'] = "extra";

        } else {
            
            $data['search_result'] = $this->gazette_model->gazetta_weekly_search_report($dataArray, $config["per_page"], $offset);

            $data['gazetteTypeValue'] = "weekly";
        }        
        //print_r($data['gazetteTypeValue']);
        $this->load->view('search-gazette_result.php', $data);
    }

    public function screen_reader() {
        $this->load->view('screen_reader.php');
    }

    public function view_extraordinary() {
        $data['title'] = "Extraordinary Gazette";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "extraordinary";
        $config["total_rows"] = $this->website_model->count_total_extra_published_gazettes();

        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
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

        $page = (int) ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }
        $data['acknowledgementDetails'] = $this->Cms_model->get_acknowledgement();

        $data["links"] = $this->my_pagination->create_links();

        $data['gazette_list'] = $this->website_model->get_all_published_extraordinary_gazette_list($config['per_page'], $offset);

        $this->load->view('extraordinary_list.php', $data);
    }

    public function view_weekly() {
        $data['title'] = "Weekly Gazette";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "weekly";
        $config["total_rows"] = $this->website_model->count_total_published_weekly_gazettes();
        // echo "<pre>";
        // print_r($config["total_rows"]);
        // echo "<pre>";exit;
        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
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

        $page = (int) ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data['acknowledgementDetails'] = $this->Cms_model->get_acknowledgement();

        $data["links"] = $this->my_pagination->create_links();

        $data['gazette_list'] = $this->website_model->get_all_published_weekly_gazette_list($config['per_page'], $offset);

        $this->load->view('weekly_list.php', $data);
    }

    public function submit_feedback() {

        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[4]|max_length[96]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|min_length[6]|max_length[96]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|max_length[10]');
        $this->form_validation->set_rules('occupation', 'Occupation', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|min_length[4]|max_length[50]');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required|min_length[4]|max_length[100]');
        $this->form_validation->set_rules('feedback', 'Feedback', 'trim|required|min_length[10]|max_length[400]');

        if ($this->form_validation->run() == FALSE) {
            
            $data['captchaValidationMessage'] = "";
            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
            $data['captcha'] = $this->botdetectcaptcha->Html();

            $this->load->view('feedback_form.php', $data);
        } else {

            $captcha = $this->security->xss_clean(trim($this->input->post('captcha')));
            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
            $result_captcha = $this->botdetectcaptcha->Validate($captcha);
            
            if ($result_captcha) {

                $ins_array = array(
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'mobile' => $this->input->post('mobile'),
                    'occupation' => $this->input->post('occupation'),
                    'address' => $this->input->post('address'),
                    'subject' => $this->input->post('subject'),
                    'feedback' => $this->input->post('feedback')
                );

                $result = $this->website_model->insert_feedback_method($ins_array);

                if ($result) {
                    $this->session->set_flashdata('success', 'Feedback submitted successfully');
                    redirect('feedback_us');
                }
            } else {
                $data['captchaValidationMessage'] = "Please enter correct captcha";
                $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
                $data['captcha'] = $this->botdetectcaptcha->Html();
                $this->load->view('feedback_form.php', $data);
            }
        }
    }

    /*
     * Callback function for checking captcha in feedback form
     */

    public function check_captcha($string) {
        if ($string != $this->session->userdata('captcha_answer')) {
            $this->form_validation->set_message('check_captcha', 'Incorrect captcha.');
            return false;
        } else {
            return true;
        }
    }

    public function bills_acts() {
        $data['title'] = "Bill's & Acts Extraordinary Gazette";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "bills_acts";
        $config["total_rows"] = $this->website_model->count_total_bills_acts_published_gazettes();

        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
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

        $page = (int) ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }
        $data['acknowledgementDetails'] = $this->Cms_model->get_acknowledgement();

        $data["links"] = $this->my_pagination->create_links();

        $data['gazette_list'] = $this->website_model->get_all_published_bills_acts_gazette_list($config['per_page'], $offset);

        $this->load->view('billsnacts_list.php', $data);
    }

    public function land_acquisition() {
        $data['title'] = "Land Acquisition Extraordinary Gazette";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "land_acquisition";
        $config["total_rows"] = $this->website_model->count_total_extra_land_acquisition_published_gazettes();

        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
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

        $page = (int) ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        $data['gazette_list'] = $this->website_model->get_all_published_land_acquisition_gazette_list($config['per_page'], $offset);

        $this->load->view('land_acquisition_list.php', $data);
    }

    public function surname_partner_change() {
        $data['title'] = "Surname Change Extraordinary Gazette";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "surname_partner_change";
        $config["total_rows"] = $this->website_model->count_total_extra_surname_change_published_gazettes();

        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
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

        $page = (int) ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        $data['gazette_list'] = $this->website_model->get_all_published_surname_gazette_list($config['per_page'], $offset);

        $this->load->view('surchange_partnership_list.php', $data);
    }

    public function other_gazette() {
        $data['title'] = "Other Extraordinary Gazette";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "other_gazette";
        $config["total_rows"] = $this->website_model->count_total_extra_other_published_gazettes();

        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
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

        $page = (int) ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        $data['gazette_list'] = $this->website_model->get_all_published_other_gazette_list($config['per_page'], $offset);

        $this->load->view('other_gazette_list.php', $data);
    }
    
    // SITE launched
    public function site_launched() {
        $json = array();
        
        $result = $this->website_model->site_launch();
        if ($result) {
           $json['success'] = true;
        } else {
           $json['success'] = false; 
        }
        echo json_encode($json);
    }
    
    // SITE un-launched
    public function site_unlaunched() {
        $json = array();
        
        $result = $this->website_model->site_unlaunched();
        if ($result) {
           $json['success'] = true;
        } else {
           $json['success'] = false;
        }
        echo json_encode($json);
    }

    public function esign_sample() {
        $this->load->view('esign_sample/index');
    }
    
    
    /*
     * display published Applicant change of partnership data
     */

    public function change_of_partnership_details() {
        
        //$this->output->enable_profiler(true);
        $data['title'] = "Change of Partnetship Details";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "website/change_of_partnership_details";
        $config["total_rows"] = $this->website_model->count_total_cop();

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


        $data['cops'] = $this->website_model->count_total_cop_details($config["per_page"], $offset);
            
        
        $this->load->view('change_of_partnership.php', $data);
     
    }
    
    /*
     * Display published Applicant Change of Surname list
     */
    
    public function change_name_surname () {
        $data['title'] = "Change of Name/Surname Extraordinary Gazette";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "change_name_surname";
        $config["total_rows"] = $this->website_model->count_total_change_name_surname_published_gazettes();

        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
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

        $page = (int) ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        $data['gazette_list'] = $this->website_model->get_all_published_change_name_surnames_gazette_list($config['per_page'], $offset);

        $this->load->view('change_name_surname.php', $data);
    }

    public function session_clear(){
       
        $this->session->unset_userdata($dept_id);
    }


    //  New functions start for department wise archival gazette display in the E-gazette portal
    public function extraordinary_archival_dept($deptID) {
        $data['notification_type'] = $this->Archival_model->get_notification_types();
        $data['department'] = $this->Archival_model->get_department_by_id($deptID);


        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "extraordinary_archival_dept/" . $deptID;
        
        $config["total_rows"] = $this->Archival_model->count_total_gazettes_dept('1', $deptID);

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

        $data['gazettes'] = $this->Archival_model->get_archived_gazette_dept($config["per_page"], $offset, '1', $deptID);


        $this->load->view('extraordinary_archival_dept.php', $data);
    }

    public function extraordinary_archival_dept_search() {
        $check = "";
        $dept_id = "";
        $notif_type = "";
        $subject = "";
        $notif_number = "";
        $gz_no = "";
        $keywords = "";
        $f_date = "";
        $t_date = "";
        $week = "";
        $year = "";
        
        if (
            $this->security->xss_clean($this->input->post('check')) != NULL ||
            $this->security->xss_clean($this->input->post('dept_id')) != NULL ||
            $this->security->xss_clean($this->input->post('notification_type_id')) != NULL ||
            $this->security->xss_clean($this->input->post('subject')) != NULL ||
            $this->security->xss_clean($this->input->post('gazette_no')) != NULL ||
            $this->security->xss_clean($this->input->post('keywords')) != NULL ||
            $this->security->xss_clean($this->input->post('f_date')) != NULL ||
            $this->security->xss_clean($this->input->post('t_date')) != NULL ||
            $this->security->xss_clean($this->input->post('notification_number')) != NULL ||
            $this->security->xss_clean($this->input->post('week_id')) != NULL ||
            $this->security->xss_clean($this->input->post('year')) != NULL
                
        ) {
            
            $check = $this->security->xss_clean($this->input->post('check'));
            $dept_id = $this->security->xss_clean($this->input->post('dept_id'));
            $notif_type = $this->security->xss_clean($this->input->post('notification_type_id'));
            $subject = $this->security->xss_clean($this->input->post('subject'));
            $notif_number = $this->security->xss_clean($this->input->post('notification_number'));
            $gz_no = $this->security->xss_clean($this->input->post('gazette_no'));
            $keywords = $this->security->xss_clean($this->input->post('keywords'));
            $f_date = $this->security->xss_clean($this->input->post('f_date'));
            $t_date = $this->security->xss_clean($this->input->post('t_date'));
            $week = $this->security->xss_clean($this->input->post('week_id'));
            $year = $this->security->xss_clean($this->input->post('year'));
            
            $this->session->set_userdata(array(
                'check' => $check,
                'dept_id' => $dept_id,
                'notif_type' => $notif_type,
                'subject' => $subject,
                'notif_number' => $notif_number,
                'gz_no' => $gz_no,
                'keywords' => $keywords,
                'f_date' => $f_date,
                't_date' => $t_date,
                'week' => $week,
                'year' => $year
            ));
        } else {
            
            if (
                $this->session->userdata('check') != NULL ||
                $this->session->userdata('dept_id') != NULL ||
                $this->session->userdata('notif_type') != NULL ||
                $this->session->userdata('subject') != NULL ||
                $this->session->userdata('gz_no') != NULL ||
                $this->session->userdata('keywords') != NULL ||
                $this->session->userdata('f_date') != NULL ||
                $this->session->userdata('t_date') != NULL ||
                $this->session->userdata('notif_number') != NULL ||
                $this->session->userdata('week') != NULL ||
                $this->session->userdata('year') != NULL

            ) {
                
                $check = $this->session->userdata('check');
                $dept_id = $this->session->userdata('dept_id');
                $notif_type = $this->session->userdata('notif_type');
                $subject = $this->session->userdata('subject');
                $notif_number = $this->session->userdata('notif_number');
                $gz_no = $this->session->userdata('gz_no');
                $keywords = $this->session->userdata('keywords');
                $f_date = $this->session->userdata('f_date');
                $t_date = $this->session->userdata('t_date');
                $week = $this->session->userdata('week');
                $year = $this->session->userdata('year');
            }
            
        }
        
        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "extraordinary_archival_dept_search/";
        
        $config["total_rows"] = $this->Archival_model->count_total_gazettes_filter($check, $dept_id, $notif_type, $subject, $notif_number, $gz_no, $keywords, $f_date, $t_date, $week, $year);
        
        $data['dept'] = $this->Archival_model->get_departments();
        $data['notification_types'] = $this->Archival_model->get_notification_types();
        $data['gazette_types'] = $this->Archival_model->get_gazette_types();
        
        $config["per_page"] = 10;
        $config["uri_segment"] = 2;
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

        $page = (int) ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        $data['gazettes'] = $this->Archival_model->get_archived_gazette_filter($config["per_page"], $offset, $check, $dept_id, $notif_type, $subject, $notif_number, $gz_no, $keywords, $f_date, $t_date, $week, $year);

        $this->load->view('search_archival_result_ext.php', $data);
    }
    
    
}

?>
