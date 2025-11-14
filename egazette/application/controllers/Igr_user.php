<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Igr_user extends MY_Controller {

    /**
     * __construct function.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'pagination', 'my_pagination','form_validation', 'smtp', 'encryption', 'user_agent'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom', 'captcha'));
        $this->load->model(array('Igr_user_model'));
    }
    
    /*
     * index function
     */
    public function index(){
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
        
        $data['title'] = "IGR User Registration";
        
        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "igr_user/index";

        $config["total_rows"] = $this->Igr_user_model->get_total_igr_users();

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
        $data['users'] = $this->Igr_user_model->get_all_users($config["per_page"], $offset);
        
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('igr_user/index.php', $data);
        $this->load->view('template/footer.php');
    }
    
    /*
     * Delete User
     */
    public function delete(){
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
        
        $id = $this->input->post('id');
        
        if (!is_numeric($id) || !$this->Igr_user_model->exists($id)) {
            $this->session->set_flashdata('error', 'IGR user does not exists');
            redirect('igr_user/index');
        }


        if ($this->Igr_user_model->delete($id)) {
            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'IGR User', 'Delete', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            
            $this->session->set_flashdata('success', 'IGR user deleted successfully');
            redirect('igr_user/index');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong');
            redirect('igr_user/index');
        }
    }
    
    /*
     * IGR user registration
     */
    public function igr_user_registration(){
        if (!$this->session->userdata('logged_in')) {
            redirect('igr_user/login');
        }
        $data['title'] = 'Add IGR User';
        
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('igr_user/add_user.php', $data);
        $this->load->view('template/footer.php');
    }
    
    /*
     * Add IGR user in system
     */
    public function add_igr_user(){
        $json = array();
        
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
        
        $this->form_validation->set_rules('dob', 'Date of birth', 'trim|required');
        $this->form_validation->set_rules('user_name', 'User name', 'trim|required|min_length[4]|max_length[20]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[gz_igr_users.email]', array('is_unique' => 'Email already exists for another user'));
        
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[10]|max_length[10]|numeric|is_unique[gz_igr_users.mobile]', array('is_unique' => 'Mobile number already exists for another user'));
        
        if ($this->form_validation->run() == false) {
            
            $json['error'] = $this->form_validation->error_array();
            
        } else {
            $dob = $this->security->xss_clean($this->input->post('dob'));
            $user_name = $this->security->xss_clean($this->input->post('user_name'));
            $email = $this->security->xss_clean($this->input->post('email'));
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $veri = $this->security->xss_clean($this->input->post('module_id'));
            
            $random_pwd = random_string('alnum', 8);
            $hash_password = $this->Igr_user_model->hash_password($random_pwd);
            
            $login_ID = mt_rand(100000, 999999);
            
            $res = $this->db->select('login_id')
                            ->from('gz_igr_users')
                            ->where('login_id', $login_ID)
                            ->get()->row();
            if (!empty($res)) {
                $login_ID = mt_rand(100000, 999999);
            }
            
            $array_data = array(
                'dob' => $dob,
                'user_name' => $user_name,
                'email' => $email,
                'mobile' => $mobile,
                'veri' => $veri,
                'login_id' => $login_ID,
                'password' => $hash_password,
                'created_at' => date('Y-m-d H:i:s', time()),
                'created_by' => $this->session->userdata('user_id')
            );
            
            $result = $this->Igr_user_model->add_igr_user($array_data);
            
            if($result) {
                
                $this->load->library("cdac_sms");
                // message format
                $message = "Your Password is {$random_pwd}. Govt. of (StateName)";
                $sms_api = new Cdac_sms();
                $template_id = "1007279828767335328";
                // send SMS using API
                $sms_api->sendOtpSMS($message, $mobile, $template_id);
                
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
                        <p style=\"color: #000 !important\">Hii {$user_name},</p>
                        <p style=\"color: #000 !important\">
                            Your account has been created for (StateName) Press E-Gazette System as a IGR user({$veri})<br/><br/> Please find your account details for (StateName) Press E-Gazette System.<br/>
                            Email : {$email}<br/>
                            Mobile : {$mobile}<br/>
                            Login ID : {$login_ID}<br/>
                            Password : {$random_pwd}
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

                $this->email->from('egazette.(StateName)@nic.in', '(StateName) Press E-Gazette System');
                $this->email->to($email);
                $this->email->subject('User acount created for (StateName) Press E-Gazette System');
                $this->email->message($email_content);
                $this->email->set_newline("\r\n");
                $this->email->send();
                
                audit_action_log($this->session->userdata('user_id'), 'IGR User', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                          
                $this->session->set_flashdata('success', 'IGR user added successfully');
                $json['redirect'] = base_url() . "igr_user/index";
               
            } else {
                
                $this->session->set_flashdata('error', 'Something went wrong');
                $json['redirect'] = base_url() . "igr_user/hod_registration";
            }
        }
        echo json_encode($json);
    }
    
    /*
     * Check unique_mobile number
     */
    public function check_unique_mobile(){
        $mob = $this->security->xss_clean($this->input->post('mobile'));
        $result = $this->Igr_user_model->check_unique_mobile($mob);
        if ($result) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    
    /*
     * Check unique email
     */
    public function check_unique_email(){
        $email = $this->security->xss_clean($this->input->post('email'));
        $result = $this->Igr_user_model->check_unique_email($email);
        if ($result) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    
    /*
     * Edit IGR User(Load)
     */
    public function igr_user_edit($id){
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
        $data['title'] = 'Edit IGR User';
        
        if (!is_numeric($id) || !$this->Igr_user_model->exists($id)) {
            $this->session->set_flashdata('error', 'IGR user does not exists');
            redirect('igr_user/index');
        }
        
        $data['users'] = $this->Igr_user_model->get_igr_user_on_edit($id);
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('igr_user/edit.php', $data);
        $this->load->view('template/footer.php');
    }
    
    /*
     * Edit IGR user
     */
    public function edit_igr_user(){
        $json = array();
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
        $id = $this->security->xss_clean($this->input->post('user_id'));
        $original_mobile = $this->db->select("mobile")
                        ->from("gz_igr_users")
                        ->where("id", $id)
                        ->get()->row()->mobile;
        
        $original_email = $this->db->select("email")
                        ->from("gz_igr_users")
                        ->where("id", $id)
                        ->get()->row()->email;
        
        if ($this->security->xss_clean($this->input->post('mobile')) != $original_mobile) {
            $is_mobile_unique = "|callback_check_mobile_unique";
        } else {
            $is_mobile_unique = "";
        }
        
        if ($this->security->xss_clean($this->input->post('email')) != $original_email) {
            $is_email_unique = "|callback_check_email_unique";
        } else {
            $is_email_unique = "";
        }
        
        $this->form_validation->set_rules('dob', 'DOB', 'trim|required|min_length[4]|max_length[40]');

        $this->form_validation->set_rules('user_name', 'User name', 'trim|required|min_length[4]|max_length[20]');
        
        $this->form_validation->set_rules('email', 'Email', 'trim|required'. $is_email_unique);
        
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[10]|max_length[10]'. $is_mobile_unique);
        
        if ($this->form_validation->run() == false) {
            
            $json['error'] = $this->form_validation->error_array();
            
        } else {
            $dob = $this->security->xss_clean($this->input->post('dob'));
            $user_name = $this->security->xss_clean($this->input->post('user_name'));
            $email = $this->security->xss_clean($this->input->post('email'));
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $veri = $this->security->xss_clean($this->input->post('module_id'));
            
            
            $array_data = array(
                'dob' => $dob,
                'user_name' => $user_name,
                'email' => $email,
                'mobile' => $mobile,
                'modified_at' => date('Y-m-d H:i:s', time()),
                'modified_by' => $this->session->userdata('user_id'),
                'id' => $id,
                'veri' => $veri
            );
            
            $result = $this->Igr_user_model->edit_igr_user($array_data);
            
            if($result) {
                
                audit_action_log($this->session->userdata('user_id'), 'IGR User', 'Update', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                 
                $this->session->set_flashdata('success', 'IGR user updated successfully');
                $json['redirect'] = base_url() . "igr_user/index";
               
            } else {
                
                $this->session->set_flashdata('error', 'Something went wrong');
                $json['redirect'] = base_url() . "igr_user/index";
                
            }
        }
        echo json_encode($json);
    }
    
    /*
     * Is unique mobile
     */
    public function check_mobile_unique($mob) {
        $result = $this->Igr_user_model->check_unique_mobile($mob);
        if ($result) {
            $this->form_validation->set_message('check_mobile_unique', 'Mobile already exists');
            return false;
        } else {
            return true;
        }
    }
    
    /*
     * Is unique email
     */
    public function check_email_unique($mob) {
        $result = $this->Igr_user_model->check_unique_email($mob);
        if ($result) {
            $this->form_validation->set_message('check_email_unique', 'Email already exists');
            return false;
        } else {
            return true;
        }
    }
    
    /*
     * Status change
     */
    public function status_change(){
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
        
        $id = $this->security->xss_clean($this->input->post('user_id'));
        $status = $this->security->xss_clean($this->input->post('status'));
        
        if (!is_numeric($id) || !$this->Igr_user_model->exists($id)) {
            $this->session->set_flashdata('error', 'Nodal officer does not exists');
            redirect('igr_user/index');
        }
        
        if ($this->Igr_user_model->status_change($id, $status)) {
            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'IGR User', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            
            $this->session->set_flashdata('success', 'Pricing for module status updated successfully');
            redirect('igr_user/index');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong');
            redirect('igr_user/index');
        }
    }
    
    /*
     * IGR User login
     */
    public function login(){
        $ip_address = $this->input->ip_address();
        if ($ip_address === '::1') {
            // If the IP address is "::1", set it to "127.0.0.1"
            $ip_address = '127.0.0.1';
        }
        $browser = $this->agent->browser();
        $platform = $this->agent->platform();
        $data['title'] = "IGR User Login";
        
        $data['captchaValidationMessage'] = "";
        $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
        $data['captchaImg'] = $this->botdetectcaptcha->Html();
        
        // set form validation rules
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[5]|max_length[20]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[96]');
        
        if ($this->form_validation->run() == false) {
            
            // Captcha
            $data['captchaValidationMessage'] = "";
            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
            $data['captchaImg'] = $this->botdetectcaptcha->Html();
            $this->load->view('igr_user/login', $data);
            
        } else {
            
            $captcha = $this->security->xss_clean(trim($this->input->post('captcha')));
            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
            $result_captcha = $this->botdetectcaptcha->Validate($captcha);
            
            if ($result_captcha) {
                session_regenerate_id(true);
                // set variables from the form
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                // $password = $this->security->xss_clean($this->input->post('password'));

                $encypt_psswrd = $this->security->xss_clean($this->input->post('enc_pwd'));
                $nonce_value = $this->input->post('nonce');
                // $nonce_value = 'lol';

                $Encryption = new Encryption();

                $decrypted = $Encryption->decrypt($encypt_psswrd, $nonce_value);

                // get the userdata from database using model
                $result = $this->Igr_user_model->check_mobile_login($mobile, $decrypted);

                if ($result) {
                    $row = $this->Igr_user_model->get_user_data($mobile);

                    if ($row->session_id == 1) {
                        $this->session->set_flashdata('error', 'User is already logged in.');
                        redirect('Igr_user/login');
                    }


                    $this->custom_logger->log('IGR User <-- ' . $row->user_name . ' --> logged in successfully, ' . ' IP -> ' . $ip_address . ' , ' . ' Broswer -> ' . $browser . ' , ' . ' OS -> ' . $platform, 'info');
                    if ($row->force_password == '0') {
                        $force_password = false;
                    } else {
                        $force_password = true;
                    }

                    $query = $this->db->select('*')->from('gz_igr_users')
                                                    ->where('id', $row->id)
                                                    ->where('is_logged', 1)->get();
                    if ($query->num_rows() > 0) {
                            //Put the message data in a session            
                            $this->session->set_flashdata('error', 'Current user already logged in to another system.');
                            $this->load->view('igr_user/login', $data);
                    } else {
                        audit_action_log($row->id, 'IGR User', 'Login', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                        $session_data = array(
                            'user_id' => $row->id,
                            'name' => $row->user_name,
                            'logged_in' => true,
                            'is_igr' => true,
                            'is_verifier_approver' => $row->verify_approve,
                            'is_applicant' => false,
                            'is_c&t' => false,
                            'force_password' => $force_password
                        );

                        $this->Igr_user_model->update_session_id($row->id, 1);


                        $this->session->set_userdata($session_data);

                        // user login ok
                        redirect('applicants_login/dashboard');
                    }
                } else {
                    $data['captchaValidationMessage'] = "";
                    $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
                    $data['captchaImg'] = $this->botdetectcaptcha->Html();
                    //Put the message data in a session            
                    $this->session->set_flashdata('error', 'Incorrect login ID or password');
                    $this->load->view('igr_user/login', $data);
                } 
            } else {
                $data['captchaValidationMessage'] = "Please enter correct captcha";
                $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
                $data['captchaImg'] = $this->botdetectcaptcha->Html();
                $this->load->view('igr_user/login', $data);
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
    
    /*
     * IGR User profile
     */
    public function profile() {
        if (!$this->session->userdata('logged_in')) {
            redirect('igr_user/login');
        }

        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('Igr_user/change_password');
        }

        $user_id = $this->session->userdata('user_id');

        if (!$this->Igr_user_model->exists($user_id)) {
            $this->session->set_flashdata('error', 'User does not exist');
            redirect('igr_user/login');
        }

        $data['title'] = 'Profile';

        $data['user_data'] = $this->Igr_user_model->get_user_details($user_id);
        
        $original_mobile = $this->db->select("mobile")
                        ->from("gz_igr_users")
                        ->where("id", $user_id)
                        ->get()->row()->mobile;
        
        $original_email = $this->db->select("email")
                        ->from("gz_igr_users")
                        ->where("id", $user_id)
                        ->get()->row()->email;
        
        if ($this->security->xss_clean($this->input->post('mobile')) != $original_mobile) {
            $is_mobile_unique = "|callback_check_mobile_unique";
        } else {
            $is_mobile_unique = "";
        }
        
        if ($this->security->xss_clean($this->input->post('email')) != $original_email) {
            $is_email_unique = "|callback_check_email_unique";
        } else {
            $is_email_unique = "";
        }
        
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[4]|max_length[40]');

        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[8]|max_length[96]|valid_email' . $is_email_unique);
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[10]|max_length[10]|numeric' . $is_mobile_unique);

        if ($this->form_validation->run() === FALSE) {
            //$this->load->view('user/profile.php', $data);
        } else {
            $update_array = array(
                'user_id' => $this->session->userdata('user_id'),
                'name' => $this->security->xss_clean($this->input->post('name')),
                'email' => $this->security->xss_clean($this->input->post('email')),
                'mobile' => $this->security->xss_clean($this->input->post('mobile')),
            );

            $result = $this->Igr_user_model->update_user_profile($update_array);
            
            if ($result) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Applicant Profile', 'Update', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'Profile updated successfully');
                redirect('igr_user/profile');
            } else {
                $this->session->set_flashdata('error', 'Profile not updated');
                redirect('igr_user/profile');
            }
        }

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('igr_user/profile.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
    
    /*
     * Change Password
     * @access public
     * @param 
     * @return void
     */
    public function change_password() {

        if (!$this->session->userdata('logged_in')) {
            redirect('igr_user/login');
        }

        $data['title'] = 'Change Password';

        // set form validation rules
        $this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|min_length[4]|max_length[16]');
        $this->form_validation->set_rules('password', 'New Password', 'trim|required|min_length[8]|max_length[16]|regex_match[/(^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!])(?=.*[^\w\d\s])[\w\d@#$%^&+=!]+$)/]');
        $this->form_validation->set_rules('match_password', 'Confirm Password', 'trim|required|min_length[8]|max_length[16]|matches[password]');

        if ($this->form_validation->run() == false) {
            // $this->load->view('igr_user/change_password', $data);
        } else {
            
            $user_id = $this->input->post('user_id');
            $password = $this->input->post('password');
            $current_password = $this->input->post('old_password');
			
            $current_pwd_res = $this->db->select('password')->from('gz_igr_users')
                                        ->where('id', $user_id)
                                        ->get()->row();
			
            if (password_verify($current_password, $current_pwd_res->password)) {

                $hash_password = $this->Igr_user_model->hash_password($password);
                
                                // check user password is last 3 or not
                $results = $this->db->select('*')->from('gz_igr_password_history')
                                ->where('user_id', $user_id)
                                ->order_by('id', 'DESC')->limit(3)
                                ->get()->result();

                $cnt = 0;

                foreach ($results as $result) {
                    if (password_verify($password, $result->password)) {
                        $cnt++;
                    }
                }

                // check the hash of the current password is 3 times  
                if ($cnt > 0) {
                    $this->session->set_flashdata('error', 'Your new password cannot be same as last 3 passwords');
                    redirect('Igr_user/change_password');
                } else {

                    $password_array = array(
                        'user_id' => $user_id,
                        'password' => $hash_password,
                        'modified_at' => date('Y-m-d H:i:s', time()),
                        'force_password' => 1
                    );

                    $result = $this->Igr_user_model->update_password($password_array);

                    if ($result) {

                            // Store Audit Log
                            audit_action_log($this->session->userdata('user_id'), 'Applicant', 'Change Password', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                            $this->session->set_flashdata('success', "Password changed successfully");
                            redirect('igr_user/login');
                    } else {
                            $this->session->set_flashdata('error', "Password not changed");
                            redirect('igr_user/change_password');
                    }
                }
                
            } else {
                $this->session->set_flashdata('error', 'Your current password is not matched');
                redirect('igr_user/change_password');
            }
        }

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('igr_user/change_password.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
    
    /**
     * Logout function.
     * 
     * @access public
     * @return void
     */
    public function logout() {
        if (!$this->session->userdata('logged_in')) {
            redirect('igr_user/login');
        }
        // create the data object
        //$data = new stdClass();
        if (isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] === true)) {
			// Log
            $username = $this->session->userdata('name');
            $this->custom_logger->log("$username - IGR User logged out Sucessfully", 'info');

            $this->db->where('id', $this->session->userdata('user_id'));
            $this->db->update('gz_igr_users', array('is_logged' => 0, 'session_id' => 0));
			
            if ($this->db->affected_rows() > 0) {

                    // remove session datas
                    foreach ($_SESSION as $key => $value) {
                            unset($_SESSION[$key]);
                    }

                    session_destroy();
                    $this->session->sess_destroy();
                    $this->session->set_flashdata('logout_success', 'You are logged out successfully.');
                    redirect('igr_user/login');
            } else {
                // remove session datas
                foreach ($_SESSION as $key => $value) {
                        unset($_SESSION[$key]);
                }

                session_destroy();
                $this->session->sess_destroy();
                $this->session->set_flashdata('logout_success', 'You are logged out successfully.');
                redirect('igr_user/login');
            }
            
        } else {
            redirect(base_url());
        }
    }
	
	/*
     * Forgot Password
     */

    public function forgot_password() {

        $data['title'] = "Forgot Password";
        // set form validation rules
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[10]|max_length[10]|numeric');

        if ($this->form_validation->run() == false) {
            $this->load->view('igr_user/forget_password', $data);
        } else {
            // set variables from the form
            $mobile = $this->input->post('mobile');

            // check email exists or not
			$result = $this->Igr_user_model->check_mobile_exists($mobile);
			
            if (!empty($result)) {
                    // echo 'test sms limit strat!';
                    // exit;
                    $blocked_user = $this->Igr_user_model->get_blocked_user($mobile);
                    $request_count = $this->Igr_user_model->get_sms_request_count($mobile);
                    // echo $request_count;
                    $this->Igr_user_model->increment_sms_request_count($mobile);
                    if ($blocked_user && strtotime($blocked_user->blocked_until) < time() && $request_count > 3) {
                        $this->Igr_user_model->reset_sms_request_count($mobile);
                    }
                    else{
                        $request_count = $this->Igr_user_model->get_sms_request_count($mobile);
                        if($request_count >= 4){
                            $blocked_until = date('Y-m-d H:i:s', strtotime('+1 hour'));
                            $this->Igr_user_model->block_user($mobile, $blocked_until);
                            $this->session->set_flashdata('error', 'You have exceeded the limit of SMS requests. You are blocked until ' . $blocked_until . '. <br>Try after ' .$blocked_until . '.');
                            redirect('igr_user/forgot_password');
                        } 

                    }
                    // echo '<br>test sms limit end!';
                    // exit;
                try {

                    $this->db->trans_begin();

                    $user_data = $result;
                    // Update password in users table
                    $random_pwd = random_string('alnum', 8);
                    $hash_password = $this->Igr_user_model->hash_password($random_pwd);
					
					// load SMS library will activate once live
					$this->load->library("cdac_sms");
					// message format
					$message = "Your Password is {$random_pwd}. Govt. of (StateName)";
					$sms_api = new Cdac_sms();
					// send SMS using API
					$template_id = "1007279828767335328";
					// send SMS using API
					$sms_api->sendOtpSMS($message, $mobile, $template_id);
					
                    // Update 
                    $this->db->where('mobile', $mobile);
                    $this->db->update('gz_igr_users', array('password' => $hash_password));
					
                    $query = $this->db->select('user_name, email')
						->from('gz_igr_users')
						->where('deleted', 0)
						->where('mobile', $mobile)
						->get()->row();
                     
                    if(!empty($query)){
						
						$email = $query->email;
						if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            $this->session->set_flashdata('error', "Forgot password request not sent");
                            redirect('igr_user/forgot_password');
                        } else {
                            $this->db->trans_commit();
                            $this->session->set_flashdata('success', 'Please check your Mobile SMS for updated password.');
                            // user login ok
                            redirect('igr_user/login');
                        }

                        // EMAIL SEND CODES
                        // $email_content = "<div style=\"background-color:#e8e8e8;margin:0;padding:0\">
                            //     <center style=\"background-color:#e8e8e8\">
                            //     <table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"#E8E8E8\">
                            //         <tbody>
                            //             <tr>
                            //                 <td valign=\"middle\" align=\"center\" height=\"60\" style=\"border-collapse:collapse\"></td>
                            //             </tr>
                            //         </tbody>
                            //     </table>
                            //     <table cellspacing=\"0\" cellpadding=\"0\" width=\"90%\" bgcolor=\"#E8E8E8\">
                            //     <tbody>
                            //         <tr>
                            //         <td>
                            //             <table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" bgcolor=\"#FFFFFF\" style=\"border-style:solid;border-color:#b4bcbc;border-width:1px\">
                            //     <tbody>
                            //         <tr>
                            //         <td>
                            //     <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" bgcolor=\"#FFFFFF\" valign=\"center\" align=\"center\">
                            //     <tbody>
                            //     <tr>
                            //     <td style=\"padding:30px 0px 0px;color:#545d5e;font-weight:lighter;font-family:Helvetica;font-size:12px;line-height:180%;vertical-align:top;text-align:center\">
                            //     <span><a href=\"#\" style=\"color:#545d5e;text-decoration:none;outline:none\" data-saferedirecturl=\"#\"><img src=\"" . base_url() . "assets/images/logo_for_email.png" . "\" style=\"border:none;outline:none;width:250px;\" class=\"CToWUd\"></a><br></span></td>
                            //     </tr>
                            //     <tr>
                            //     <td class=\"m_8193269747688794827mktEditable\" id=\"m_8193269747688794827body\" valign=\"center\" cellpadding=\"0\" align=\"center\" bgcolor=\"#FFFFFF\" style=\"border-collapse:collapse;color:#545d5e;font-family:Arial,Tahoma,Verdana,sans-serif;font-size:14px;font-weight:lighter;margin:0;text-align:left;line-height:165%;letter-spacing:0;padding-top:20px;padding-bottom:60px;padding-left: 30px;padding-right: 30px;\">
                            //     <p style=\"color: #000 !important\">Hii {$query->user_name},</p>
                            //     <p style=\"color: #000 !important\">
                            //         You have requested for forgot password request.<br/><br/> Your account password has been reset for (StateName) Press E-Gazette System.<br/>Please find the below details as the new password for (StateName) Press E-Gazette System account.<br/>
                            //         Email : {$email}<br/>
                            //         Password : {$random_pwd}
                            //     </p>
                            //     <br/>
                            //     <p style=\"color: #000 !important\">
                            //     Regards,
                            //     <br/>
                            //     (StateName) Press E-Gazette System
                            //     </p>	                      
                            //     </td>
                            //     </tr>
                            //     </tbody>
                            //     </table>
                            //     </td>
                            //     </tr>
                            //     </tbody>
                            //     </table>
                            //     </td>
                            //     </tr>
                            //     </tbody>
                            //     </table>
                            //     <table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#E8E8E8\">
                            //     <tbody>
                            //     </tbody>
                            //     </table>
                            //     <table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"#E8E8E8\">
                            //     <tbody>
                            //     <tr>
                            //     <td valign=\"middle\" align=\"center\" height=\"70\" style=\"border-collapse:collapse\"></td>
                            //     </tr>
                            //     </tbody>
                            //     </table>
                            //     </center>
                            //     </div>";

						// //$this->smtp->initialize_data($email, $email_content);
						// $this->email->from('egazette.(StateName)@gov.in', '(StateName) Press E-Gazette System');
						// $this->email->to($email);
						// $this->email->subject('Forgot Password Request for (StateName) Press E-Gazette System');
						// $this->email->message($email_content);
						// $this->email->set_newline("\r\n");
						// $this->email->send();
						
                    }
                } catch (Exception $ex) {
                    $this->session->set_flashdata('error', "Forgot password request not sent");
                    redirect('igr_user/forgot_password');
                }
            } else {
                $this->session->set_flashdata('error', "Invalid Mobile");
                // user login ok
                redirect('igr_user/forgot_password');
            }
        }
    }
}
?>