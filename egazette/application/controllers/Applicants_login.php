<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/vendor/autoload.php';

use DocxMerge\DocxMerge;



class Applicants_login extends MY_Controller {

    private $doc_file = '';
    private $doc_file_for_name_surname = '';
    private $doc_file_for_name_surname_pdf = '';
    private $doc_file_for_gender = '';
    private $doc_file_for_gender_pdf = '';

    

    /**
     * __construct function.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'pagination', 'smtp', 'my_pagination', 'form_validation', 'encryption', 'user_agent'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom', 'captcha'));
        $this->load->model(array('Applicants_login_model'));

        
    }

    public function index() {
        $ip_address = $this->input->ip_address();
        if ($ip_address === '::1') {
            // If the IP address is "::1", set it to "127.0.0.1"
            $ip_address = '127.0.0.1';
        }
        $browser = $this->agent->browser();
        $platform = $this->agent->platform();
        $data['title'] = "Applicant's Login";
        // Captcha
        $data['captchaValidationMessage'] = "";
        $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
        $data['captchaImg'] = $this->botdetectcaptcha->Html();

        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[10]|max_length[10]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[96]');
        
        if ($this->form_validation->run() == false) {
            // Captcha
            $data['captchaValidationMessage'] = "";
            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
            $data['captchaImg'] = $this->botdetectcaptcha->Html();
            $this->load->view('applicants_login/login', $data);
        } else {
            
            $captcha = $this->security->xss_clean(trim($this->input->post('captcha')));
            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
            $result_captcha = $this->botdetectcaptcha->Validate($captcha);
            
            if ($result_captcha) {
                session_regenerate_id(true);
                // set variables from the form
                // echo 'test-01<br>';
                $mobile = $this->input->post('mobile');
                // $password = $this->input->post('password');
                // $nonce_value = 'lol';
                $encypt_psswrd = $this->input->post('enc_pwd');
                $nonce_value = $this->input->post('nonce');

                $Encryption = new Encryption();

                $decrypted = $Encryption->decrypt($encypt_psswrd, $nonce_value);
                // echo $decrypted;exit;
                
                if ($this->Applicants_login_model->check_mobile_otp_verify($mobile)) {
                    
                    $existing_session = $this->Applicants_login_model->check_existing_session($mobile);
                    // if ($existing_session) {
                    //     $this->session->set_flashdata('error', 'You are already logged in from another device.');
                    //     redirect('applicants_login/index');
                    // }

                    // get the userdata from database using model
                    $result = $this->Applicants_login_model->check_mobile_login($mobile, $decrypted);
                    // echo 'result is - ' . $result;exit;
                    if ($result) {
                        $row = $this->Applicants_login_model->get_user_data($mobile);
                        
                        $this->custom_logger->log( 'Applicant <-- ' . $row->name . ' --> - login successfully ' . ' IP -> ' . $ip_address . ' , ' . ' Broswer -> ' . $browser . ' , ' . ' OS -> ' . $platform, 'info');
                        // if ($query->num_rows() > 0) {
                            $data['captchaValidationMessage'] = "";
                            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
                            $data['captchaImg'] = $this->botdetectcaptcha->Html();
                            $this->load->view('applicants_login/login', $data);
                        //} else {
                    
                            audit_action_log($row->id, 'Applicant', 'Login', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                            $session_data = array(
                                'user_id' => $row->id,
                                'name' => $row->name,
                                'logged_in' => true,
                                'is_applicant' => true,
                                'module_id' => $row->module_id,
                                'session_id' => $row->session_id
                            );
                            $this->session->set_userdata($session_data);

                            $this->Applicants_login_model->update_session_id($row->id, 1); // update session_id to 1
                            // user login ok
                            redirect('applicants_login/dashboard');
                        //}
                    } else {
                        $data['captchaValidationMessage'] = "";
                        $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
                        $data['captchaImg'] = $this->botdetectcaptcha->Html();
                        //Put the message data in a session 
                        $this->custom_logger->log('Applicant login Unsuccessful, Incorrect mobile or password', 'error');           
                        $this->session->set_flashdata('error', 'Incorrect mobile or password');
                        $this->load->view('applicants_login/login', $data);
                    }
                } else {
                    $data['captchaValidationMessage'] = "";
                    $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
                    $data['captchaImg'] = $this->botdetectcaptcha->Html();
                    //Put the message data in a session   
                    $this->custom_logger->log('Applicants Mobile Number is Not Verified', 'error');         
                    $this->session->set_flashdata('error', 'Mobile number not verified');
                    $this->load->view('applicants_login/login', $data);
                }
            } else {
                $data['captchaValidationMessage'] = "Please enter correct captcha";
                $this->custom_logger->log('Please enter correct captcha', 'error');
                $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
                $data['captchaImg'] = $this->botdetectcaptcha->Html();
                $this->load->view('applicants_login/login', $data);
            }
        }

        //$this->load->view('applicants_login/login', $data);
    }

    
    // new optimized logout function for testing...
    public function logout() {
        if (!$this->session->userdata('logged_in')) {
            redirect('applicants_login/index');
        }
    
        $user_id = $this->session->userdata('user_id');
    
        $this->db->where('id', $user_id);
        $this->db->update('gz_applicants_details', array('is_logged' => 0, 'session_id' => 0));
    
        if ($this->db->affected_rows() > 0) {
            session_regenerate_id(true);
            $this->session->sess_destroy();
            $this->session->set_flashdata('logout_success', 'You are logged out successfully.');
        }

        // Log the logout action
        $this->custom_logger->log('Applicant Logout successfully', 'info');
    
        // Redirect to login page
        redirect('applicants_login/index');
    }

    public function logout_router() {
        if ($this->session->userdata('is_applicant')) {
            redirect('applicants_login/logout');
        } else if ($this->session->userdata('is_igr')) {
            redirect('Igr_user/logout');
        } else if ($this->session->userdata('is_c&t')) {
            redirect('Commerce_transport_department/logout');
        } else {
            redirect('user/logout');
        }
    }

    


    public function dashboard() {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }
        
        $data['title'] = "Dashboard";

        if ($this->session->userdata('is_applicant')) {

            $data['total_submitted'] = $this->Applicants_login_model->get_count_of_total_submitted_gazette($this->session->userdata('user_id'));

            $data['cop_published_gazettes'] = $this->Applicants_login_model->cop_published_gazettes($this->session->userdata('user_id'));

            $data['cop_unpublished_gazettes'] = $this->Applicants_login_model->cop_unpublished_gazettes($this->session->userdata('user_id'));

            $data['cos_published_gazettes'] = $this->Applicants_login_model->cos_published_gazettes($this->session->userdata('user_id'));

            $data['cos_unpublished_gazettes'] = $this->Applicants_login_model->cos_unpublished_gazettes($this->session->userdata('user_id'));

            $data['cog_published_gazettes'] = $this->Applicants_login_model->cog_published_gazettes($this->session->userdata('user_id'));
            $data['cog_unpublished_gazettes'] = $this->Applicants_login_model->cog_unpublished_gazettes($this->session->userdata('user_id'));
            
            $data['recent_cop_gazettes'] = $this->Applicants_login_model->applicant_recent_cop_gazettes($this->session->userdata('user_id'));
            $data['recent_cos_gazettes'] = $this->Applicants_login_model->applicant_recent_cos_gazettes($this->session->userdata('user_id'));
            
        } else {

            if ($this->session->userdata('is_c&t') == 1) {
                if (!$this->session->userdata('force_password') && $this->session->userdata('is_c&t') == true) {
                    $this->session->set_flashdata('error', 'You must change your password after first Login!');
                    redirect('Commerce_transport_department/change_password');
                }
                
                $data['poc_published_gazettes'] = $this->Applicants_login_model->count_total_gazettes_poc_published();

                $data['poc_unpublished_gazettes'] = $this->Applicants_login_model->count_total_gazettes_poc_pending();
                
                $total_poc = $data['poc_published_gazettes'] + $data['poc_unpublished_gazettes'];
                
                if ($this->session->userdata('is_c&t_module') == 1) {
                    
                    $data['total_submitted'] = $this->Applicants_login_model->total_gazettes_c_t_igr() + $total_poc;

                    $data['cop_published_gazettes'] = $this->Applicants_login_model->total_cop_published_gazettes();

                    $data['cop_unpublished_gazettes'] = $this->Applicants_login_model->total_cop_unpublished_gazettes_dept();
                    
                } else if ($this->session->userdata('is_c&t_module') == 2) {
                    
                    $data['total_submitted'] = $this->Applicants_login_model->get_count_of_total_submitted_gazette_dept('c&t', $this->session->userdata('is_c&t_module'), $this->session->userdata('is_verifier_approver')) + $total_poc;
                    
                    $data['cos_published_gazettes'] = $this->Applicants_login_model->cos_published_gazettes_dept('c&t', $this->session->userdata('is_c&t_module'), $this->session->userdata('is_verifier_approver'));

                    $data['cos_unpublished_gazettes'] = $this->Applicants_login_model->cos_unpublished_gazettes_dept('c&t', $this->session->userdata('is_c&t_module'), $this->session->userdata('is_verifier_approver'));
                    
                } else if ($this->session->userdata('is_c&t_module') == 6) {
                    
                    $data['total_submitted'] = $this->Applicants_login_model->get_count_of_total_COG_submitted_gazette_dept('c&t', $this->session->userdata('is_c&t_module'), $this->session->userdata('is_verifier_approver')) + $total_poc;
                    
                    $data['cog_published_gazettes'] = $this->Applicants_login_model->cog_published_gazettes_dept('c&t', $this->session->userdata('is_c&t_module'), $this->session->userdata('is_verifier_approver'));

                    $data['cog_unpublished_gazettes'] = $this->Applicants_login_model->cog_unpublished_gazettes_dept('c&t', $this->session->userdata('is_c&t_module'), $this->session->userdata('is_verifier_approver'));
                    
                }
                
            } else {
                if (!$this->session->userdata('force_password')) {
                    $this->session->set_flashdata('error', 'You must change your password after first Login!');
                    redirect('Igr_user/change_password');
                }
                $data['total_submitted'] = $this->Applicants_login_model->total_gazettes_c_t_igr();

                $data['cop_published_gazettes'] = $this->Applicants_login_model->total_cop_published_gazettes();

                $data['cop_unpublished_gazettes'] = $this->Applicants_login_model->total_cop_unpublished_gazettes_dept();
            }
        }

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php', $data);
        $this->load->view('applicants_login/dashboard.php', $data);
        $this->load->view('template/footer_applicant.php', $data);
    }

    /*
     * Register Applicant
     */

    public function registeration() {
       
        $data['title'] = 'Register Applicant';
        $data['captchaValidationMessage'] = "";
    
        $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
        $data['captchaImg'] = $this->botdetectcaptcha->Html();
    
        $data['modules'] = $this->Applicants_login_model->get_modules();
    
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[4]|max_length[40]');
        // Define rules for other form fields...
    
        if ($this->form_validation->run() == false) {
            $data['relations'] = $this->Applicants_login_model->get_relations();
            $data['modules'] = $this->Applicants_login_model->get_modules();
        } else {
            $captcha = $this->security->xss_clean(trim($this->input->post('captcha')));
            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
            $result_captcha = $this->botdetectcaptcha->Validate($captcha);
    
            if ($result_captcha) {

                // echo '<pre>';print_r($this->input->post());die("print list");
                $name = $this->security->xss_clean($this->input->post('name'));
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                $email = $this->security->xss_clean($this->input->post('email'));
                $f_name = $this->security->xss_clean($this->input->post('f_name'));
                $module_id = $this->security->xss_clean($this->input->post('module_id'));
                // $data = $this->input->post();

                $random_pwd = random_string('alnum', 8);
                $hash_password = $this->Applicants_login_model->hash_password($random_pwd);
    
                // $otp = $this->generate_otp(4);
    
                $this->load->library("cdac_sms");
                $message = "Your Password is {$otp}. Govt. of (StateName)";
                $sms_api = new Cdac_sms();
                $template_id = "1007279828767335328";
                $sms_api->sendOtpSMS($message, $mobile, $template_id);
    
                $array_data = array(
                    'login_ID' => $mobile,
                    'name' => $name,
                    'mobile' => $mobile,
                    'email' => $email,
                    'password' => $hash_password,
                    'module_id' => $module_id,
                    'father_name' => $f_name,
                    'otp' => 1234,
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 0,
                    'deleted' => 0,
                    'verification_code' => $mobile
                );
    
                $result = $this->Applicants_login_model->register_applicant($array_data);
    
                if ($result) {
                    $this->session->set_flashdata('success', 'OTP has been sent to your Mobile. Please verify your OTP to complete registration');
                    redirect('applicants_login/reg_otp/' . $mobile);
                } else {
                    $this->session->set_flashdata('error', 'Registration failed. Please try again later.');
                    redirect('applicants_login/registeration');
                }
            } else {
                $this->session->set_flashdata('error', 'Please enter correct captcha');
                redirect('applicants_login/registeration');
            }
        }
    
        $this->load->view('applicants_login/register', $data);
    }
    
    

    /*
     * OTP (Registration) View
     */

    public function reg_otp($login_ID) {
        $data['title'] = "Verify OTP";
        $data['login_id'] = $login_ID;
        // check if the sesison id exists in DB / not and is valid for 15 Minutes

        if ($this->Applicants_login_model->valid_applicant_otp($login_ID)) {
            $this->load->view('applicants_login/reg_otp', $data);
        } else {
            $this->session->set_flashdata('error', "Invalid applicant login ID");
            // user login ok
            redirect('applicants_login/index');
        }
    }

    public function verify_reg_otp() {
        $data['title'] = 'Verify Applicant';
        // set form validation rules
        $this->form_validation->set_rules('login_id', 'Login ID', 'trim|required|numeric|min_length[10]|max_length[10]');
        $this->form_validation->set_rules('otp', 'OTP', 'trim|required|numeric|min_length[4]|max_length[4]');
        $this->form_validation->set_rules('password', "Password", 'trim|required|min_length[6]|max_length[16]|regex_match[/(^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!])(?=.*[^\w\d\s])[\w\d@#$%^&+=!]+$)/]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[6]|max_length[16]|matches[password]');

        if ($this->form_validation->run() == false) {
            $data['login_id'] = $this->security->xss_clean($this->input->post('login_id'));
            redirect('applicants_login/reg_otp/' . $this->security->xss_clean($this->input->post('login_id')));
        } else {
            
            $login_id = $this->security->xss_clean($this->input->post('login_id'));
            $otp = $this->security->xss_clean($this->input->post('otp'));
            $encryptedPassword = $this->input->post('encoded_password');
            // echo $encryptedPassword . '<br>';
            $nonce_value = 'lol';

            $Encryption = new Encryption();

            $decryptedPass = $Encryption->decrypt($encryptedPassword, $nonce_value);
            // $password = $this->security->xss_clean($this->input->post('password'));
            $password = $decryptedPass;
            // Check if the OTP is valid and expiry time is also valid 
            $data = array(
                'login_id' => $login_id,
                 'otp' => $otp
            );
            // if (true) { 
            if ($this->Applicants_login_model->verify_reg_otp($data)) { 
                // Insert into applicant details table and update the password and verify_otp = 1
                $hash_password = $this->Applicants_login_model->hash_password($password);

                $udp_arr = array(
                    'password' => $hash_password,
                    'verify_otp' => 1,
                    'login_id' => $login_id
                );
                
                if ($this->Applicants_login_model->update_applicant_reg_password($udp_arr)) {
                    $this->session->set_flashdata('success', "Password set successfully for Applicant");
                    // user login
                    redirect('applicants_login/index');
                }
            } else {
                $this->session->set_flashdata('error', "Invalid OTP");
                // user login
                redirect('applicants_login/reg_otp/' . $login_id);
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
     * Resend Registration OTP
     */
    
    public function resend_reg_otp() {
        $json = array();
        // set form validation rules
        $this->form_validation->set_rules('login_id', 'Login ID', 'trim|required|min_length[6]|max_length[96]|numeric');

        if ($this->form_validation->run() == false) {
            //$json['error'] = $this->form_validation->error_array();
        } else {
            // set variables from the form
            $login_id = trim($this->input->post('login_id'));
            $otp = $this->generate_otp(4);
            //$otp = '1234'; 
            //echo $login_id;
            // Fetch the Mobile No
            $mobile_dtls = $this->db->select('mobile')->from('gz_applicants_details')
                                    ->where('login_id', $login_id)->get();
            
            if ($mobile_dtls->num_rows() > 0) {
                
                $mobile = $mobile_dtls->row()->mobile;
                //echo $mobile;exit();
                if ($this->Applicants_login_model->applicant_reg_resend_otp($login_id, $otp)) {
                    // load SMS library will activate once live
                    $this->load->library("cdac_sms");
                    // message format
                    $message = "Your OTP is {$otp}. Govt. of (StateName)";
                    $sms_api = new Cdac_sms();
                    // send SMS using API
                    $template_id = "1007279828767335328";
                    
                    $sms_api->sendOtpSMS($message, $mobile, $template_id);
                    $this->custom_logger->log('OTP sent successfully', 'info');
                    $this->session->set_flashdata('success', "OTP sent successfully");
                    redirect('applicants_login/reg_otp/' . $login_id);
                }
            } else {
                $this->custom_logger->log('OTP Not Sent successfully', 'error');
                $this->session->set_flashdata('error', "OTP not sent");
                redirect('applicants_login/reg_otp/' . $login_id);
            }
        }
    }

    /*
     * Forgot Password
     */

    
    public function forgot_password() {
        $data['title'] = "Forgot Password";
        $data['captchaValidationMessage'] = "";
    
        // set form validation rules
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[10]|max_length[10]|numeric');
        $this->form_validation->set_rules('captcha', 'Captcha', 'required');
    
        if ($this->form_validation->run() == false) {
            // Captcha
            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'ForgotCaptcha'));
            $data['captchaImg'] = $this->botdetectcaptcha->Html();
            $this->load->view('applicants_login/forget_password', $data);
        } else {
            $captcha = $this->security->xss_clean(trim($this->input->post('captcha')));
            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'ForgotCaptcha'));
            $result_captcha = $this->botdetectcaptcha->Validate($captcha);
    
            if ($result_captcha) {
                $mobile = $this->input->post('mobile');
                $result = $this->Applicants_login_model->check_mobile_exists($mobile);
                if (!empty($result)) {

                    // echo 'test sms limit strat!';
                    // exit;

                    $blocked_user = $this->Applicants_login_model->get_blocked_user($mobile);
                    $request_count = $this->Applicants_login_model->get_sms_request_count($mobile);
                    // echo $request_count;
                    $this->Applicants_login_model->increment_sms_request_count($mobile);
                    if ($blocked_user && strtotime($blocked_user->blocked_until) < time() && $request_count > 3) {
                        $this->Applicants_login_model->reset_sms_request_count($mobile);
                    }
                    else{
                        $request_count = $this->Applicants_login_model->get_sms_request_count($mobile);
                        if($request_count >= 4){
                            $blocked_until = date('Y-m-d H:i:s', strtotime('+1 hour'));
                            $this->Applicants_login_model->block_user($mobile, $blocked_until);
                            $this->session->set_flashdata('error', 'You have exceeded the limit of SMS requests. You are blocked until ' . $blocked_until . '. <br>Try after ' .$blocked_until . '.');
                            redirect('applicants_login/forgot_password');
                        } 

                    }
                    // echo '<br>test sms limit end!';
                    // exit;
                    try {
                        $this->db->trans_begin();
    
                        $user_data = $result;
                        $random_pwd = random_string('alnum', 8);
                        $hash_password = $this->Applicants_login_model->hash_password($random_pwd);
    
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
                        $this->db->update('gz_applicants_details', array('password' => $hash_password));
    
                        // Get email associated with the mobile
                        $query = $this->db->select('email')
                            ->from('gz_applicants_details')
                            ->where('deleted', 0)
                            ->where('mobile', $mobile)
                            ->get()->row();
    
                        if (!empty($query)) {
                            $email = $query->email;
    
                            // Send email with new password
                            // Email sending code goes here
                            // ...
    
                            if ($this->db->trans_status() === FALSE) {
                                $this->db->trans_rollback();
                                $this->custom_logger->log('Forgot password request not sent', 'error');
                                $this->session->set_flashdata('error', "Forgot password request not sent");
                                redirect('applicants_login/forgot_password');
                            } else {
                                $this->db->trans_commit();
                                $this->custom_logger->log('Please check your mobile for updated password.', 'info');
                                $this->session->set_flashdata('success', 'Please check your mobile for updated password.');
                                // user login ok
                                redirect('applicants_login/index');
                            }
                        }
                    } catch (Exception $ex) {
                        $this->custom_logger->log('Forgot password request not sent', 'error');
                        $this->session->set_flashdata('error', "Forgot password request not sent");
                        redirect('applicants_login/forgot_password');
                    }
                } else {
                    $data['captchaValidationMessage'] = "Please enter correct captcha";
                    $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'ForgotCaptcha'));
                    $data['captchaImg'] = $this->botdetectcaptcha->Html();
                    $this->session->set_flashdata('error', "Invalid Mobile");
                    // user login ok
                    redirect('applicants_login/forgot_password');
                }
            } else {
                $data['captchaValidationMessage'] = "Please enter correct captcha";
                $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'ForgotCaptcha'));
                $data['captchaImg'] = $this->botdetectcaptcha->Html();
                $this->session->set_flashdata('error', "Invalid Captcha");
                // user login ok
                redirect('applicants_login/forgot_password');
            }
        }
    }
    

    
    /*
     * Change Password
     * @access public
     * @param 
     * @return void
     */
        // original change_password method
    public function change_password() {

        if (!$this->session->userdata('is_applicant')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }

        $data['title'] = 'Change Password';

        // set form validation rules
        $this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|min_length[4]|max_length[16]');
        $this->form_validation->set_rules('password', 'New Password', 'trim|required|min_length[6]|max_length[16]|regex_match[/(^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!])(?=.*[^\w\d\s])[\w\d@#$%^&+=!]+$)/]');
        $this->form_validation->set_rules('match_password', 'Confirm Password', 'trim|required|min_length[6]|max_length[16]|matches[password]');

        if ($this->form_validation->run() == false) {
            //$this->load->view('user/change_password', $data);
        } else {

            $user_id = $this->input->post('user_id');

            $nonce_value = 'lol';
            $encryptNewPass = $this->input->post('encoded_password'); // New Password
            $encryptOldPass = $this->input->post('encoded_old_password'); // Old Password

            $Encryption = new Encryption();

            $decryptedNewPass = $Encryption->decrypt($encryptNewPass, $nonce_value);
            $decryptedOldPass = $Encryption->decrypt($encryptOldPass, $nonce_value);

            $password = $decryptedNewPass;
            $current_password = $decryptedOldPass;

            $current_pwd_res = $this->db->select('password')->from('gz_applicants_details')
                            ->where('id', $user_id)
                            ->get()->row();

            if ($this->Applicants_login_model->verify_password_hash($current_password, $current_pwd_res->password)) {

                $hash_password = $this->Applicants_login_model->hash_password($password);

                // check user password is last 3 or not
                $results = $this->db->select('*')->from('gz_applicant_password_history')
                                ->where('user_id', $user_id)
                                ->order_by('id', 'DESC')->limit(3)
                                ->get()->result();

                $cnt = 0;

                foreach ($results as $result) {
                    if ($this->Applicants_login_model->verify_password_hash($password, $result->password)) {
                        $cnt++;
                    }
                }

                // check the hash of the current password is 3 times  
                if ($cnt > 0) {
                    $this->custom_logger->log('New Password cannot be same as last 3 passwords', 'error');  
                    $this->session->set_flashdata('error', 'Your new password cannot be same as last 3 passwords');
                    redirect('applicants_login/change_password');
                } else {
                    // store the password data into users table
                    $password_array = array(
                        'user_id' => $user_id,
                        'password' => $hash_password,
                        'modified_at' => date('Y-m-d H:i:s', time()),
                    );

                    $result = $this->Applicants_login_model->update_password($password_array);

                    if ($result) {

                        // Store Audit Log
                        audit_action_log($this->session->userdata('user_id'), 'Applicant', 'Change Password', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                        $this->custom_logger->log('Password changed successfully', 'info');
                        $this->session->set_flashdata('success', "Password changed successfully");
                        redirect('applicants_login/change_password');
                    } else {
                        $this->custom_logger->log('Password not changed', 'error');
                        $this->session->set_flashdata('error', "Password not changed");
                        redirect('applicants_login/change_password');
                    }
                }
            } else {
                $this->custom_logger->log('Your current password is not matched', 'error');
                $this->session->set_flashdata('error', 'Your current password is not matched');
                redirect('applicants_login/change_password');
            }
        }

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/change_password.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    

    /*
     * Applicant profile
     */

    public function profile() {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }

        $user_id = $this->session->userdata('user_id');

        if (!$this->Applicants_login_model->exists($user_id)) {
            $this->session->set_flashdata('error', 'Applicant does not exist');
            redirect('applicants_login/index');
        }

        $data['title'] = 'Profile';

        $data['user_data'] = $this->Applicants_login_model->get_user_details($user_id);
        $data['modules'] = $this->Applicants_login_model->get_modules();

        $original_mobile = $this->db->select("mobile")
                        ->from("gz_applicants_details")
                        ->where("id", $user_id)
                        ->get()->row()->mobile;

        $original_email = $this->db->select("email")
                        ->from("gz_applicants_details")
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
        $this->form_validation->set_rules('f_name', "Father's Name", 'trim|required|min_length[4]|max_length[40]');

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
                'f_name' => $this->security->xss_clean($this->input->post('f_name'))
            );
            
            $result = $this->Applicants_login_model->update_user_profile($update_array);
            //echo $result; exit();
            if ($result) {

                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Applicant Profile', 'Update', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                $this->session->set_flashdata('success', 'Profile updated successfully');
                redirect('applicants_login/profile');
            } else {
                $this->session->set_flashdata('error', 'Profile not updated');
                redirect('applicants_login/profile');
            }
        }

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/profile.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    /*
     * Is unique mobile
     */

    public function check_mobile_unique($mob) {
        $result = $this->Applicants_login_model->check_unique_mobile($mob);
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
        $result = $this->Applicants_login_model->check_unique_email($mob);
        if ($result) {
            $this->form_validation->set_message('check_email_unique', 'Email already exists');
            return false;
        } else {
            return true;
        }
    }

    /*
     * Index for change of name/surname
     */

    public function index_for_change_of_name_surname() {
       
        // Check if the user is authorized
        // if (!$this->session->userdata('is_c&t')) {
        //     $this->session->set_flashdata('error', 'You are not authorized!');
        //     redirect('commerce_transport_department/login_ct');
        // }
    
        // // Check if the user is required to change the password
        // if (!$this->session->userdata('force_password') && $this->session->userdata('is_c&t') == true) {
        //     $this->session->set_flashdata('error', 'You must change your password after first Login!');
        //     redirect('commerce_transport_department/change_password');
        // }
    
        $data['title'] = "Change of Name/Surname";
    
        // Pagination configuration
        $config["base_url"] = base_url() . "applicants_login/index_for_change_of_name_surname";
        if ($this->session->userdata('is_applicant')) {
            $config["total_rows"] = $this->Applicants_login_model->get_total_change_name_count_applicant();
        } else if ($this->session->userdata('is_c&t')) {
            $config["total_rows"] = $this->Applicants_login_model->get_total_change_name_count_c_and_t(
                $this->session->userdata('is_verifier_approver'),
                $this->session->userdata('is_c&t_module')
            );
            // echo $config["total_rows"];exit();
        }
    
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config["num_links"] = 2;
        $config['use_page_numbers'] = TRUE;
    
        // Pagination HTML customization
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
    
        // Determine the current page
        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
    
        // Validate the page number
        $total_pages = ceil($config["total_rows"] / $config["per_page"]);
        $page = ($page > 0 && $page <= $total_pages) ? $page : 1;
    
        // Calculate the offset
        $offset = ($page - 1) * $config["per_page"];
    
        $data["links"] = $this->my_pagination->create_links();
    
        // Fetch data based on user type
        if ($this->session->userdata('is_applicant')) {
            $data['change_of_names'] = $this->Applicants_login_model->get_total_change_of_names_applicant($config['per_page'], $offset);
        } else if ($this->session->userdata('is_c&t')) {
            $data['change_of_names'] = $this->Applicants_login_model->get_total_change_of_names_c_and_t(
                $config['per_page'],
                $offset,
                $this->session->userdata('is_verifier_approver'),
                $this->session->userdata('is_c&t_module')
            );
            $data['total_status'] = $this->Applicants_login_model->get_status();
        }
    
        // Load views
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/index_change_of_name.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
    

    /*
    * Search Change of Name/Surname
    */

    public function search_for_change_of_name_surname() {
       
        if (!$this->session->userdata('is_c&t')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('commerce_transport_department/login_ct');
        }
        $data['title'] = "Change of Name/Surname";
        $config["base_url"] = base_url() . "applicants_login/search_for_change_of_name_surname";

        $searchValue = array(
            'app_name' => trim($this->input->post('name')),
            'file_no' => trim($this->input->post('file_no')),
            'status' => trim($this->input->post('status')),
            'fdate' => trim($this->input->post('notice_date_form')),
            'tdate' => trim($this->input->post('notice_date_to')),
        );
        
        $inputs = $this->input->post();
		$page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        }
        else{
            if($page==0){
              $array_items = array('app_name', 'file_no', 'status', 'notice_date_form', 'notice_date_to');
              $this->session->unset_userdata($array_items);
              $inputs =array();
            }else{
              $inputs = $this->session->userdata();
            }
        }



        if ($this->session->userdata('is_applicant')) {

            $config["total_rows"] = $this->Applicants_login_model->get_total_change_name_count_applicant();
            
        } else if ($this->session->userdata('is_c&t')) {
            
            $config["total_rows"] = $this->Applicants_login_model->get_total_change_name_count_c_and_t_search($inputs,$this->session->userdata('is_verifier_approver'), $this->session->userdata('is_c&t_module'));
        }

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

        //$page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        if ($this->session->userdata('is_applicant')) {
            $data['change_of_names'] = $this->Applicants_login_model->get_total_change_of_names_applicant($config['per_page'], $offset);
        } else if ($this->session->userdata('is_c&t')) {


            //$data['change_of_names'] = $this->Applicants_login_model->get_total_change_of_names_c_and_t($config['per_page'], $offset, $this->session->userdata('is_verifier_approver'), $this->session->userdata('is_c&t_module'));
            //$data['change_of_names'] = $this->Applicants_login_model->change_of_name_surname_search_result( $config["per_page"], $offset, $this->session->userdata('is_verifier_approver'), $this->session->userdata('is_c&t_module'), $inputs);
            $data['change_of_names'] = $this->Applicants_login_model->change_of_name_surname_search_result( $config["per_page"], $offset, $inputs);

        }
        //echo $this->db->last_query();
        
        $data['total_status'] = $this->Applicants_login_model->get_status();
        $data['inputs'] = $inputs;
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/index_change_of_name.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
    
    /*
     * List of all published Name/Surname
     */
    public function published_name_surname() {

        if (!$this->session->userdata('is_c&t')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('commerce_transport_department/login_ct');
        }

        if (!$this->session->userdata('force_password') && $this->session->userdata('is_c&t') == true) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('commerce_transport_department/change_password');
        }
        $data['title'] = "Published Applicant";
        $config["base_url"] = base_url() . "applicants_login/published_name_surname";
        if ($this->session->userdata('is_applicant')) {

            $config["total_rows"] = $this->Applicants_login_model->get_total_change_name_count_applicant();
        } else if ($this->session->userdata('is_c&t')) {
            
            $config["total_rows"] = $this->Applicants_login_model->get_total_change_name_count_c_and_t_published($this->session->userdata('is_verifier_approver'), $this->session->userdata('is_c&t_module'));
        }

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

        if ($this->session->userdata('is_applicant')) {

            $data['change_of_names'] = $this->Applicants_login_model->get_total_change_of_names_applicant($config['per_page'], $offset);
        } else if ($this->session->userdata('is_c&t')) {
            $data['change_of_names'] = $this->Applicants_login_model->published_name_surname_list($config['per_page'], $offset, $this->session->userdata('is_verifier_approver'), $this->session->userdata('is_c&t_module'));
        }


        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/published_name_surname.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    /*
     * Filter data of Published Partnrship
     */

    public function search_for_published_change_of_name_surname() {

        //$this->output->enable_profiler(TRUE);

        if (!$this->session->userdata('logged_in')) {
            redirect('applicants_login/index');
        }
        $data['title'] = "Change of Name/Surname";
        $config["base_url"] = base_url() . "applicants_login/search_for_change_of_published_name_surname";

        $searchValue = array(
            'app_name' => trim($this->input->post('name')),
            'file_no' => trim($this->input->post('file_no')),
            'fdate' => trim($this->input->post('notice_date_form')),
            'tdate' => trim($this->input->post('notice_date_to')),
        );
        
        $inputs = $this->input->post();
        //print_r($inputs);exit;
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



        if ($this->session->userdata('is_applicant')) {

            $config["total_rows"] = $this->Applicants_login_model->get_total_change_name_count_applicant();
        } else if ($this->session->userdata('is_c&t')) {
            
            $config["total_rows"] = $this->Applicants_login_model->get_total_change_name_published_count_c_and_t_search($inputs,$this->session->userdata('is_verifier_approver'), $this->session->userdata('is_c&t_module'));
        }
        //echo $this->db->last_query();
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

        //$page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        if ($this->session->userdata('is_applicant')) {
            $data['change_of_names'] = $this->Applicants_login_model->get_total_change_of_names_applicant($config['per_page'], $offset);
        } else if ($this->session->userdata('is_c&t')) {
            $data['change_of_names'] = $this->Applicants_login_model->change_of_name_surname_published_search_result( $config["per_page"], $offset, $inputs);

        }
        //echo $this->db->last_query();
        
        $data['total_status'] = $this->Applicants_login_model->get_status();
        $data['inputs'] = $inputs;
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/published_name_surname.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
    
    /*
     * List of Published Partnesdhip
     */
    
    public function published_partnership() {

        if (!$this->session->userdata('is_c&t')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('commerce_transport_department/login_ct');
        } 

        if (!$this->session->userdata('force_password') && $this->session->userdata('is_c&t') == true) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('commerce_transport_department/change_password');
        }
        $data['title'] = "Published Applicant";
        $config["base_url"] = base_url() . "applicants_login/published_partnership";
        if ($this->session->userdata('is_applicant')) {

            $config["total_rows"] = $this->Applicants_login_model->get_total_parter_count();
        } 

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

        if ($this->session->userdata('is_applicant')) {

            $data['change_of_names'] = $this->Applicants_login_model->get_total_change_of_names_applicant($config['per_page'], $offset);
        } else if ($this->session->userdata('is_c&t')) {
            $data['change_of_names'] = $this->Applicants_login_model->published_partnership_list($config['per_page'], $offset, $this->session->userdata('is_verifier_approver'), $this->session->userdata('is_c&t_module'));
        }


        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/published_partnership.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
    
    
    public function get_districts_list () {
        $state_id = $this->security->xss_clean($this->input->post('id'));
        
        $result = $this->Applicants_login_model->get_district_list($state_id); ?>
      
        <option value="">Select District</option><?php
        if (!empty($result)) {
            foreach ($result as $i) { ?>
                <option value="<?php echo $i->id; ?>"><?php echo $i->district_name; ?></option>
            <?php
            }
        }
    }
    
    public function get_police_station_list () {
        $district_id = $this->security->xss_clean($this->input->post('id'));
        
        $result = $this->Applicants_login_model->get_police_station_list($district_id); ?>
        <option value="">Select Police Station</option><?php
        if (!empty($result)) {
            foreach ($result as $i) { ?>
                <option value="<?php echo $i->id; ?>"><?php echo $i->police_station_name; ?></option>
            <?php
            }
        }
    }
    
    /*
     * select
     */
    public function get_block_ulb () {
        $val = "";
        
        $district_id = $this->security->xss_clean($this->input->post('id'));
        //exit;
        $block_ulb = $this->security->xss_clean($this->input->post('block_ulb'));
        if(!empty($block_ulb)) { 
            $val = $block_ulb;
        } ?>
        <option value="">Select Block/ULB</option>
            <optgroup label="Block"><?php
            //echo $district_id;
            //exit;
            $result_block = $this->Applicants_login_model->get_block_list($district_id);
            //echo $this->db->last_query();
             //var_dump($result_block);
            // exit();
            if (!empty($result_block)) {
                foreach ($result_block as $i) { ?>
                    <option value="<?php echo "block_".$i->id ?>" <?php if($val == $i->id) { echo "selected"; } ?> ><?php echo $i->block_name; ?></option>
                <?php
            } } ?>            
            </optgroup>
            <optgroup label="ULB">
                <?php $result_ulb  = $this->Applicants_login_model->get_ulb_list($district_id);
                if (!empty($result_ulb)) { 
                foreach ($result_ulb as $i) { ?>
                    <option value="<?php echo "ulb_".$i->id ?>"><?php echo $i->ulb_name; ?></option>
                <?php
                }  } ?>            
            </optgroup>
    <?php }
    

    
    /*
     * Change of name/surname form
     */

    public function add_change_of_name_surname() {
        if (!$this->session->userdata('is_applicant')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }
    
        $data['title'] = "Change of Name/Surname Add";
        $data['gz_types'] = $this->Applicants_login_model->get_total_gz_types();
        $data['states'] = $this->Applicants_login_model->get_states();
        // echo '<pre>'; print_r($data['states']);die("Worked");
        $data['tot_documents'] = $this->Applicants_login_model->get_total_total_documents();
        $data['count'] = count($data['tot_documents']);

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/add_change_of_name.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    /*
     * Insert Change of name/surname
     */

    public function insert_change_of_name_surname() {
       // $this->output->enable_profiler(TRUE);

        $json = array();
        
        if($this->security->xss_clean($this->input->post('minor')) == 0) {
            $this->form_validation->set_rules('state_id', 'State', 'trim|required');
            $this->form_validation->set_rules('district_id', 'District', 'trim|required');
            $this->form_validation->set_rules('block_ulb_id', 'Block/ULB', 'trim|required');
            $this->form_validation->set_rules('address_1', 'Address ', 'trim|required|min_length[5]|max_length[200]');
            $this->form_validation->set_rules('father_name', 'Father Name ', 'trim|required');
            $this->form_validation->set_rules('date_of_birth', 'Date of Birth ', 'trim|required');
            $this->form_validation->set_rules('govt_emp', 'Government Employee', 'trim|required');
            $this->form_validation->set_rules('minor', 'Minor', 'trim|required');
            $this->form_validation->set_rules('docu_3', 'Select Word/Docx File', 'callback_gazette_doc_upload_for_name_surname');
            $this->form_validation->set_rules('approver', 'Approver', 'trim|required');
            $this->form_validation->set_rules('place', 'Place', 'trim|required');
            $this->form_validation->set_rules('notice_date', 'Date', 'trim|required');
            $this->form_validation->set_rules('salutation', 'Salutation', 'trim|required');
            $this->form_validation->set_rules('name_for_notice', 'Name', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('old_name', 'Name', 'trim|required');
            $this->form_validation->set_rules('new_name', 'Name', 'trim|required');
            $this->form_validation->set_rules('new_name_one', 'Name', 'trim|required');
            $this->form_validation->set_rules('new_name_two', 'Name', 'trim|required');
        } else {

            $this->form_validation->set_rules('notice_date_minor', 'Date', 'trim|required');
            $this->form_validation->set_rules('son_daughter', 'Relation', 'trim');
            $this->form_validation->set_rules('gender', 'Gender', 'trim');
            $this->form_validation->set_rules('notice_date_minor', 'Date', 'trim|required');
            $this->form_validation->set_rules('salutation_minor', 'Salutation', 'trim|required');
            $this->form_validation->set_rules('name_for_notice_minor', 'Name', 'trim|required');
            $this->form_validation->set_rules('address_minor', 'Address', 'trim|required');
            $this->form_validation->set_rules('old_name_minor', 'Name', 'trim|required');
            $this->form_validation->set_rules('new_name_minor', 'Name', 'trim|required');
            $this->form_validation->set_rules('new_name_one_minor', 'Name', 'trim|required');
            $this->form_validation->set_rules('new_name_two_minor', 'Name', 'trim|required');
        } 
        
          
        if ($this->form_validation->run() == false) {
            $json['error'] = $this->form_validation->error_array();
            
        } else {
            $return = $this->db->select('id')
                        ->from('gz_change_of_name_surname_master')
                        ->where('deleted', '0')
                        ->order_by('id', 'DESC')
                        ->limit(1)
                        ->get()->row();


            $final_file_no1 = "00001";
            if (!empty($return)) {
                $id = $return->id + 1;
                $code = (string) $id;
                $len = strlen($code);
                if ($len == 1) {
                    $final_file_no1 = '0000' . $id;
                } else if ($len == 2) {
                    $final_file_no1 = '000' . $id;
                } else if ($len == 3) {
                    $final_file_no1 = '00' . $id;
                } else if ($len == 4) {
                    $final_file_no1 = '0' . $id;
                } else if ($len == 5) {
                    $final_file_no1 = $id;
                }
            }
            $year = date("Y");
            $file_no = 'XN-' . $final_file_no1 . '-' . $year;

            $press_word_db_path_raw = 'uploads/chang_of_name_surname/notice_updated_doc_file/' . time() . '.docx';
            $path  = str_replace('\\', '/', FCPATH);
            $new_path = base_url() . $press_word_db_path_raw;
            $press_word_db_path = $path . $press_word_db_path_raw;
            
            if($this->security->xss_clean($this->input->post('minor')) == 0){

                $template_file = FCPATH . "uploads/sample/cos_adult_notice_sample.docx";
               
                $insert_array = array( 
                    'approver' => $this->security->xss_clean($this->input->post('approver')),
                    'place' => $this->security->xss_clean($this->input->post('place')),
                    'notice_date' => $this->security->xss_clean($this->input->post('notice_date')),
                    //'notice_date_minor' => $this->security->xss_clean($this->input->post('notice_date_minor')),
                    'salutation' => $this->security->xss_clean($this->input->post('salutation')),
                    'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice')),
                    // 'son_daughter' => $this->security->xss_clean($this->input->post('son_daughter')),
                    // 'gender' => $this->security->xss_clean($this->input->post('gender')),
                    'address' => $this->security->xss_clean($this->input->post('address')),
                    'old_name' => $this->security->xss_clean($this->input->post('old_name')),
                    'new_name' => $this->security->xss_clean($this->input->post('new_name')),
                    'new_name_one' => $this->security->xss_clean($this->input->post('new_name_one')),
                    'new_name_two' => $this->security->xss_clean($this->input->post('new_name_two')),
                    'file_no' => $file_no,
                    'press_word_db_path' => $new_path,
                    // 'pdf_path' => $this->doc_file_for_name_surname_pdf,
                    
                );

                
            } else {
                $template_file = FCPATH . "uploads/sample/cos_minor_notice_sample_dummy.docx";

                $insert_array = array( 
                    'approver' => $this->security->xss_clean($this->input->post('approver_minor')),
                    'place' => $this->security->xss_clean($this->input->post('place_minor')),
                    'notice_date' => $this->security->xss_clean($this->input->post('notice_date_minor')),
                    // //'notice_date_minor' => $this->security->xss_clean($this->input->post('notice_date_minor')),
                    'salutation' => $this->security->xss_clean($this->input->post('salutation_minor')),
                    'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice_minor')),
                    'son_daughter' => $this->security->xss_clean($this->input->post('son_daughter')),
                    'gender' => $this->security->xss_clean($this->input->post('gender')),
                    'address' => $this->security->xss_clean($this->input->post('address_minor')),
                    'old_name' => $this->security->xss_clean($this->input->post('old_name_minor')),
                    'new_name' => $this->security->xss_clean($this->input->post('new_name_minor')),
                    'new_name_one' => $this->security->xss_clean($this->input->post('new_name_one_minor')),
                    'new_name_two' => $this->security->xss_clean($this->input->post('new_name_two_minor')),
                    'file_no' => $file_no,
                    'press_word_db_path' => $new_path,
                    // 'pdf_path' => $this->doc_file_for_name_surname_pdf,
                );
            }
            
            $timestamp = strtotime($insert_array['notice_date']);

            // Creating new date format from that timestamp
            $new_date = date("jS F Y", $timestamp);


            // load from template processor
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($template_file);
            // var_dump($insert_array);exit();
            // set dynamic values provided by Govt. Press
            if($this->security->xss_clean($this->input->post('minor')) == 0){

                $templateProcessor->setValue('approver', $insert_array['approver']);
                $templateProcessor->setValue('place', $insert_array['place']);
                $templateProcessor->setValue('notice_date', $new_date);
                $templateProcessor->setValue('salutation', $insert_array['salutation']);
                $templateProcessor->setValue('name_for_notice', $insert_array['name_for_notice']);
                $templateProcessor->setValue('address', $insert_array['address']);
                $templateProcessor->setValue('old_name', $insert_array['old_name']);
                $templateProcessor->setValue('new_name', $insert_array['new_name']);
                $templateProcessor->setValue('new_name_one', $insert_array['new_name_one']);
                $templateProcessor->setValue('new_name_two', $insert_array['new_name_two']); 
            
            } else {   
                $templateProcessor->setValue('approver', $insert_array['approver']);
                $templateProcessor->setValue('place', $insert_array['place']);
                $templateProcessor->setValue('notice_date', $new_date);
                $templateProcessor->setValue('salutation', $insert_array['salutation']);
                $templateProcessor->setValue('name_for_notice', $insert_array['name_for_notice']);
                $templateProcessor->setValue('address', $insert_array['address']);
                $templateProcessor->setValue('old_name', $insert_array['old_name']);
                $templateProcessor->setValue('new_name', $insert_array['new_name']);
                $templateProcessor->setValue('new_name_one', $insert_array['new_name_one']);
                $templateProcessor->setValue('new_name_two', $insert_array['new_name_two']);
                $templateProcessor->setValue('son_daughter', $insert_array['son_daughter']);
                $templateProcessor->setValue('gender', $insert_array['gender']);
            }
            $templateProcessor->saveAs($press_word_db_path);
            

            $pdf_file_path = $this->convert_word_to_PDF_for_name_surname($press_word_db_path,$file_no);
        
            if($this->security->xss_clean($this->input->post('minor')) == 0) {
                $insert_array_final = array(
                    'gazette_type' => 1,
                    'state_id' => $this->security->xss_clean($this->input->post('state_id')),
                    'district_id' => $this->security->xss_clean($this->input->post('district_id')),
                    'block_ulb_id' => $this->security->xss_clean($this->input->post('block_ulb_id')),
                    'address_1' => $this->security->xss_clean($this->input->post('address_1')),
                    'father_name' => $this->security->xss_clean($this->input->post('father_name')),
                    'date_of_birth' => $this->security->xss_clean($this->input->post('date_of_birth')),
                    'pin_code' => $this->security->xss_clean($this->input->post('pin_code')),
                    'govt_emp' => $this->security->xss_clean($this->input->post('govt_emp')),
                    'minor' => $this->security->xss_clean($this->input->post('minor')),
                    'affidavit' => $this->security->xss_clean($this->input->post('document_1')),
                    'original_newspaper' => $this->security->xss_clean($this->input->post('document_2')),
                    'notice' => $new_path,
                    'kyc_doc' => $this->security->xss_clean($this->input->post('document_4')),
                    'approval_auth_doc' => $this->security->xss_clean($this->input->post('document_5')),
                    'deed_changing_form' => $this->security->xss_clean($this->input->post('document_6')),
                    'birth_cert' => $this->security->xss_clean($this->input->post('document_7')),
                            
                    'approver' => $this->security->xss_clean($this->input->post('approver')),
                    'place' => $this->security->xss_clean($this->input->post('place')),
                    'notice_date' => $this->security->xss_clean($this->input->post('notice_date')),
                    //'notice_date_minor' => $this->security->xss_clean($this->input->post('notice_date_minor')),
                    'salutation' => $this->security->xss_clean($this->input->post('salutation')),
                    'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice')),
                    'address' => $this->security->xss_clean($this->input->post('address')),
                    'son_daughter' => $this->security->xss_clean($this->input->post('son_daughter')),
                    'gender' => $this->security->xss_clean($this->input->post('gender')),
                    'old_name' => $this->security->xss_clean($this->input->post('old_name')),
                    'new_name' => $this->security->xss_clean($this->input->post('new_name')),
                    'new_name_one' => $this->security->xss_clean($this->input->post('new_name_one')),
                    'new_name_two' => $this->security->xss_clean($this->input->post('new_name_two')),
                    'file_no' => $file_no,
                    'press_word_db_path' => $new_path,
                    'pdf_path' => $this->doc_file_for_name_surname_pdf,
                );
            } else {
                $insert_array_final = array(
                    'gazette_type' => 1,
                    'state_id' => $this->security->xss_clean($this->input->post('state_id')),
                    'district_id' => $this->security->xss_clean($this->input->post('district_id')),
                    'block_ulb_id' => $this->security->xss_clean($this->input->post('block_ulb_id')),
                    'address_1' => $this->security->xss_clean($this->input->post('address_1')),
                    'father_name' => $this->security->xss_clean($this->input->post('father_name')),
                    'date_of_birth' => $this->security->xss_clean($this->input->post('date_of_birth')),
                    'pin_code' => $this->security->xss_clean($this->input->post('pin_code')),
                    'govt_emp' => $this->security->xss_clean($this->input->post('govt_emp')),
                    'minor' => $this->security->xss_clean($this->input->post('minor')),
                    'affidavit' => $this->security->xss_clean($this->input->post('document_1')),
                    'original_newspaper' => $this->security->xss_clean($this->input->post('document_2')),
                    'notice' => $new_path,
                    'kyc_doc' => $this->security->xss_clean($this->input->post('document_4')),
                    'approval_auth_doc' => $this->security->xss_clean($this->input->post('document_5')),
                    'deed_changing_form' => $this->security->xss_clean($this->input->post('document_6')),
                    'birth_cert' => $this->security->xss_clean($this->input->post('document_7')),
                            
                    'approver' => $this->security->xss_clean($this->input->post('approver_minor')),
                    'place' => $this->security->xss_clean($this->input->post('place_minor')),
                    'notice_date' => $this->security->xss_clean($this->input->post('notice_date_minor')),
                    'salutation' => $this->security->xss_clean($this->input->post('salutation_minor')),
                    'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice_minor')),
                    'address' => $this->security->xss_clean($this->input->post('address_minor')),
                    'son_daughter' => $this->security->xss_clean($this->input->post('son_daughter')),
                    'gender' => $this->security->xss_clean($this->input->post('gender')),
                    'old_name' => $this->security->xss_clean($this->input->post('old_name_minor')),
                    'new_name' => $this->security->xss_clean($this->input->post('new_name_minor')),
                    'new_name_one' => $this->security->xss_clean($this->input->post('new_name_one_minor')),
                    'new_name_two' => $this->security->xss_clean($this->input->post('new_name_two_minor')),
                    'file_no' => $file_no,
                    'press_word_db_path' => $new_path,
                    'pdf_path' => $this->doc_file_for_name_surname_pdf,
                );
            }
            $result = $this->Applicants_login_model->insert_change_of_name_surname($insert_array_final);

            

            if ($result) {
                audit_action_log($this->session->userdata('user_id'), 'Change of Name/Surname', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                $this->session->set_flashdata("success", "Change of name/surname added successfully");
                $json['redirect'] = base_url() . "check_status/change_name_status";
            } else {
                $this->session->set_flashdata("error", "Something went wrong");
                $json['redirect'] = base_url() . "check_status/change_name_status";
            }
        }
        echo json_encode($json);
    }

    /*
     * Convert word file to PDF
     */

    public function convert_word_to_PDF_for_name_surname($word_file,$file_no) {

        $word = new COM("word.application") or die("Could not initialise MS Word object.");
        $word->Documents->Open($word_file) or die($word_file);

        
        $upload_dir = 'uploads/chang_of_name_surname/name_surname_pdf/' . $file_no . "/";
        // check whether upload directory is writable
        if (!is_dir($upload_dir) && !is_writable($upload_dir)) {
            mkdir($upload_dir, 0777, TRUE);
        }

        $pdf_file_db_path = 'uploads/chang_of_name_surname/name_surname_pdf/' . $file_no . "/" . time() . '.pdf';
        $var = str_replace('\\', '/', FCPATH);
        $pdf_file_path = $var . $pdf_file_db_path;

        $word->ActiveDocument->ExportAsFixedFormat($pdf_file_path, 17, false, 0, 0, 0, 0, 7, true, true, 2, true, true, false);

        $word->Quit();
        $word = null;
        //echo $pdf_file_db_path;
        return $this->doc_file_for_name_surname_pdf = base_url() . $pdf_file_db_path;
    }

    /*
     * Upload word document 
     */

    public function gazette_doc_upload_for_name_surname() {
        if (!empty($_FILES['docu_3']['name']) && ($_FILES['docu_3']['size'] > 0)) {

            $return = $this->db->select('id')
                            ->from('gz_change_of_name_surname_master')
                            ->where('deleted', '0')
                            ->order_by('id', 'DESC')
                            ->limit(1)
                            ->get()->row();

            $final_file_no1 = "00001";
            if (!empty($return)) {
                $id = $return->id + 1;
                $code = (string) $id;
                $len = strlen($code);
                if ($len == 1) {
                    $final_file_no1 = '0000' . $id;
                } else if ($len == 2) {
                    $final_file_no1 = '000' . $id;
                } else if ($len == 3) {
                    $final_file_no1 = '00' . $id;
                } else if ($len == 4) {
                    $final_file_no1 =  '0' . $id;
                } else if ($len == 5) {
                    $final_file_no1 = $id;
                }
            }

            $year = date("Y");
            $file_no = 'COM-PUB-X-' . $final_file_no1 . '-' . $year;

            $upload_dir = 'uploads/chang_of_name_surname/notice_in_softcopy/' . $file_no . "/";
            // check whether upload directory is writable
            if (!is_dir($upload_dir) && !is_writable($upload_dir)) {
                mkdir($upload_dir, 0777, TRUE);
            }

            $config['upload_path'] = $upload_dir;
            $config['allowed_types'] = array('docx');
            $config['file_name'] = $_FILES['docu_3']['name'];
            $config['overwrite'] = true;
            $config['encrypt_name'] = TRUE;
            // 5 MB
            $config['max_size'] = '5242880';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('docu_3')) {
                $this->form_validation->set_message('handle_gazette_doc_upload_for_name_surname', $this->upload->display_errors('', ''));
                return false;
            } else {
                $this->upload_data['file'] = $this->upload->data();
                $this->doc_file_for_name_surname = $upload_dir . $this->upload_data['file']['file_name'];
                //echo $this->doc_file;exit();
                return true;
            }
        } else {
            $this->form_validation->set_message('handle_gazette_doc_upload', 'No file selected');
        }
    }

    /*
     * Upload document for change name/surname
     */

    // Recent Function need to be uncomment if new one is not working properly
    public function upload_document_for_change_name_surname() {
       
        $data = array();
        $document_id = $this->security->xss_clean($this->input->post('id'));
        $file_number = $this->security->xss_clean($this->input->post('file_no'));
        if (isset($_FILES['file'])) {
            if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["file"]["tmp_name"];
                $name = $_FILES["file"]["name"];
                //MIME Type check
                $allowed_mime_types = array('image/jpeg', 'image/png', 'application/pdf');
                $file_mime_type = mime_content_type($tmp_name);
                if (!in_array($file_mime_type, $allowed_mime_types)) {
                    $data = array('error' => "Invalid file type. Only JPG, PNG, and PDF files are allowed.");
                    echo json_encode($data);
                    return;
                }
                
                $return = $this->db->select('id')
                                ->from('gz_change_of_name_surname_master')
                                ->where('deleted', '0')
                                ->order_by('id', 'DESC')
                                ->limit(1)
                                ->get()->row();

                $final_file_no1 = "00001";
                if (!empty($return)) {
                    $id = $return->id + 1;
                    $code = (string) $id;
                    $len = strlen($code);
                    if ($len == 1) {
                        $final_file_no1 = '0000' . $id;
                    } else if ($len == 2) {
                        $final_file_no1 = '000' . $id;
                    } else if ($len == 3) {
                        $final_file_no1 = '00' . $id;
                    } else if ($len == 4) {
                        $final_file_no1 = '0' . $id;
                    } else if ($len == 5) {
                        $final_file_no1 = $id;
                    }
                }

                $year = date("Y");
                if ($file_number == "") {
                    $file_no = 'XN-' . $final_file_no1 . '-' . $year;
                } else {
                    $file_no = $file_number;
                }

                if ($document_id == 1) {
                    $folder = 'affidavit';
                } else if ($document_id == 2) {
                    $folder = 'original_newspaper';
                } else if ($document_id == 3) {
                    $folder = 'notice_in_softcopy';
                } else if ($document_id == 4) {
                    $folder = 'kyc_document';
                } else if ($document_id == 5) {
                    $folder = 'approval_authority_document';
                } else if ($document_id == 6) {
                    $folder = 'deed_changing_form';
                } else if ($document_id == 7) {
                    $folder = 'age_proof';
                }

                $upload_dir = 'uploads/chang_of_name_surname/' . $folder . "/" . $file_no . "/";
                if (!is_dir($upload_dir) && !is_writeable($upload_dir)) {
                    mkdir($upload_dir);
                }
                $upd_file = time() . "_" . str_replace(" ", "", basename($name));
                $file_path = $upload_dir . $upd_file;
                // database path to store and find the incident images
                $DB_Image_Path = base_url() . $file_path;

                if (!file_exists($file_path)) {
                    if (move_uploaded_file($tmp_name, $file_path)) {
                        $data = array('success' => $DB_Image_Path);
                    } else {
                        $data = array('error' => "File not uploaded");
                    }
                } else {
                    $data = array('error' => "File already exists");
                }
            } else {
                $data = array('error' => $_FILES['file']['error']);
            }
        } else {
            $data = array('error' => 'File is not attached');
        }
        echo json_encode($data);
    }

    /*
     * Edit of change of name/surname
     */

    public function edit_change_of_name_surname() {

        $file_no = $this->security->xss_clean($this->input->post('file_no'));
        $file_word = $this->security->xss_clean($this->input->post('word_file'));
        $file_pdf = $this->security->xss_clean($this->input->post('pdf_file'));
        $id = $this->security->xss_clean($this->input->post('change_name_id'));
        $val_chk_updated = $this->security->xss_clean($this->input->post('val_chk_updated'));

        // echo $file_no . '-<br>' . $file_word . '-<br>' . $file_pdf . '-<br>' . $id;
        // exit;

        $data['gz_dets'] = $this->Applicants_login_model->view_details_change_name_surname($id);
        //var_dump($data['gz_dets']);die();
        //if (!empty($val_chk_updated)) {
        // echo $this->security->xss_clean($this->input->post('approver'));exit;
            //echo "Hii1"; exit;
                 if ($data['gz_dets']->is_minor == 0){
                    $press_word_db_path_row = 'uploads/chang_of_name_surname/notice_updated_doc_file/' . time() . '.docx';
                    $path  = str_replace('\\', '/', FCPATH);
                    $press_word_db_path = $path . $press_word_db_path_row;

                    $template_file = FCPATH . "uploads/sample/cos_adult_notice_sample.docx";
                    
                    $word_file = base_url().$press_word_db_path_row;
                 }
                 else{
                    $press_word_db_path_row = 'uploads/chang_of_name_surname/notice_updated_doc_file/' . time() . '.docx';
                    $path  = str_replace('\\', '/', FCPATH);
                    $press_word_db_path = $path . $press_word_db_path_row;

                    $template_file = FCPATH . "uploads/sample/cos_minor_notice_sample_dummy.docx";
                    
                    $word_file = base_url().$press_word_db_path_row;
                }
                
                if ($data['gz_dets']->is_minor == 0){
                    $insert_array = array(
                        'approver' => $this->security->xss_clean($this->input->post('approver')),
                        'son_daughter' => $this->security->xss_clean($this->input->post('son_daughter')),
                        'gender' => $this->security->xss_clean($this->input->post('gender')),
                        'address' => $this->security->xss_clean($this->input->post('address')),
                        'place' => $this->security->xss_clean($this->input->post('place')),
                        'notice_date' => $this->security->xss_clean($this->input->post('notice_date')),
                        'salutation' => $this->security->xss_clean($this->input->post('salutation')),
                        'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice')),
                        // 'age' => $this->security->xss_clean($this->input->post('age')),
                        'old_name' => $this->security->xss_clean($this->input->post('old_name')),
                        'new_name' => $this->security->xss_clean($this->input->post('new_name')),
                        'new_name_one' => $this->security->xss_clean($this->input->post('new_name_one')),
                        'new_name_two' => $this->security->xss_clean($this->input->post('new_name_two')),
                        'file_no' => $file_no,
                        'press_word_db_path' => $word_file,
                        //'pdf_path' => $this->doc_file_for_name_surname_pdf,
                    );
                }else {
                    $insert_array = array( 
                        'approver' => $this->security->xss_clean($this->input->post('approver_minor')),
                        'place' => $this->security->xss_clean($this->input->post('place_minor')),
                        'notice_date' => $this->security->xss_clean($this->input->post('notice_date_minor')),
                        // //'notice_date_minor' => $this->security->xss_clean($this->input->post('notice_date_minor')),
                        'salutation' => $this->security->xss_clean($this->input->post('salutation_minor')),
                        'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice_minor')),
                        'son_daughter' => $this->security->xss_clean($this->input->post('son_daughter')),
                        'gender' => $this->security->xss_clean($this->input->post('gender')),
                        'address' => $this->security->xss_clean($this->input->post('address_minor')),
                        'old_name' => $this->security->xss_clean($this->input->post('old_name_minor')),
                        'new_name' => $this->security->xss_clean($this->input->post('new_name_minor')),
                        'new_name_one' => $this->security->xss_clean($this->input->post('new_name_one_minor')),
                        'new_name_two' => $this->security->xss_clean($this->input->post('new_name_two_minor')),
                        'file_no' => $file_no,
                        'press_word_db_path' => $new_path,
                        //'pdf_path' => $this->doc_file_for_name_surname_pdf,
                    );
                }
                // echo '<pre>';
                // print_r($insert_array);
                // exit;
                $timestamp = strtotime($insert_array['notice_date']);

                // Creating new date format from that timestamp
                $new_date = date("jS F Y", $timestamp);

                // load from template processor
                $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($template_file);
                //var_dump($insert_array);exit();
                // set dynamic values provided by Govt. Press
                $templateProcessor->setValue('place', $insert_array['place']);
                $templateProcessor ->setValue('approver',$insert_array['approver']);
                $templateProcessor ->setValue('son_daughter',$insert_array['son_daughter']);
                $templateProcessor ->setValue('gender',$insert_array['gender']);
                $templateProcessor ->setValue('address',$insert_array['address']);
                $templateProcessor->setValue('notice_date', $new_date);
                $templateProcessor->setValue('salutation', $insert_array['salutation']);
                $templateProcessor->setValue('name_for_notice', $insert_array['name_for_notice']);
                $templateProcessor->setValue('age', $insert_array['age']);
                $templateProcessor->setValue('old_name', $insert_array['old_name']);
                $templateProcessor->setValue('new_name', $insert_array['new_name']);
                $templateProcessor->setValue('new_name_one', $insert_array['new_name_one']);
                $templateProcessor->setValue('new_name_two', $insert_array['new_name_two']); 

                $templateProcessor->saveAs($press_word_db_path);//exit();

                $pdf_file_path_raw = $this->convert_word_to_PDF_for_name_surname($press_word_db_path, $file_no);
                $pdf_file_path = $this->doc_file_for_name_surname_pdf;

        //} else {
            //echo "HI2"; exit;
        //    $word_file = $file_word;
        //    $pdf_file_path = $file_pdf;
        //}

        //var_dump($val_chk_updated); exit;

        $insert_array = array(
            'id' => $id,
            'affidavit' => $this->security->xss_clean($this->input->post('document_1')),
            'original_newspaper' => $this->security->xss_clean($this->input->post('document_2')),
            'notice' => $word_file,
            'kyc_doc' => $this->security->xss_clean($this->input->post('document_4')),
            'approval_auth_doc' => $this->security->xss_clean($this->input->post('document_5')),
            'deed_changing_form' => $this->security->xss_clean($this->input->post('document_6')),
            'birth_cert' => $this->security->xss_clean($this->input->post('document_7')),
            'pdf_path' => $pdf_file_path,
            'state_id' => $this->security->xss_clean($this->input->post('state_id')),
            'district_id' => $this->security->xss_clean($this->input->post('district_id')),
            'block_ulb_id' => $this->security->xss_clean($this->input->post('block_ulb_id')),
            'address_1' => $this->security->xss_clean($this->input->post('address_1')),
            'place' => $this->security->xss_clean($this->input->post('place')),
            'approver' => $this->security->xss_clean($this->input->post('approver')),
            'son_daughter' => $this->security->xss_clean($this->input->post('son_daughtre')),
            'gender' => $this->security->xss_clean($this->input->post('gender')),
            'pin_code' => $this->security->xss_clean($this->input->post('pin_code')),
            'notice_date' => $this->security->xss_clean($this->input->post('notice_date')),
            'salutation' => $this->security->xss_clean($this->input->post('salutation')),
            'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice')),
            'age' => $this->security->xss_clean($this->input->post('age')),
            'old_name' => $this->security->xss_clean($this->input->post('old_name')),
            'new_name' => $this->security->xss_clean($this->input->post('new_name')),
            'new_name_one' => $this->security->xss_clean($this->input->post('new_name_one')),
            'new_name_two' => $this->security->xss_clean($this->input->post('new_name_two')),
            'file_no' => $file_no,
            'press_word_db_path' => $word_file,
        );
        // echo '<pre>';print_r($insert_array);exit();
        // echo 'abcddfg';
        $current_status = $this->Applicants_login_model->current($id);
        // var_dump($current_status);
        // exit();
        $status = $current_status->current_status;
        //print($current_status->current_status);
        //exit();
        if ( $data['gz_dets']->is_minor == 1 ) {
            $insert_array['approver'] = $this->security->xss_clean($this->input->post('approver_minor'));
        } else {
            $insert_array['approver'] = $this->security->xss_clean($this->input->post('approver'));
        }

        // print_r($insert_array);
        // exit;
        
        $result = $this->Applicants_login_model->edit_change_of_name_surname($insert_array,$status);

        if ($result) {
            audit_action_log($this->session->userdata('user_id'), 'Change of Name/Surname', 'Edit', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata("success", "Change of name/surname updated successfully");
            redirect("applicants_login/index_for_change_of_name_surname");
        } else {
            $this->session->set_flashdata("error", "Something went wrong");
            redirect("applicants_login/index_for_change_of_name_surname");
        }
    }

    /*
     * Convert word file to PDF (EDIT)
     */

    public function convert_word_to_PDF_for_name_surname_edit($word_file, $file_no) {
        $word = new COM("word.application") or die("Could not initialise MS Word object.");
        $word->Documents->Open($word_file) or die($word_file);


        $upload_dir = 'uploads/chang_of_name_surname/name_surname_pdf/' . $file_no . "/";
        // check whether upload directory is writable
        if (!is_dir($upload_dir) && !is_writable($upload_dir)) {
            mkdir($upload_dir, 0777, TRUE);
        }

        $pdf_file_db_path = 'uploads/chang_of_name_surname/name_surname_pdf/' . $file_no . "/" . time() . '.pdf';
        $var = str_replace('\\', '/', FCPATH);
        $pdf_file_path = $var . $pdf_file_db_path;

        $word->ActiveDocument->ExportAsFixedFormat($pdf_file_path, 17, false, 0, 0, 0, 0, 7, true, true, 2, true, true, false);

        $word->Quit();
        $word = null;
        return base_url() . $pdf_file_db_path;
    }

    /*
     * Upload word document 
     */

    public function gazette_doc_upload_for_name_surname_edit($data, $file_no) {
        //echo $file_no; exit();
        if (!empty($_FILES['docu_3']['name']) && ($_FILES['docu_3']['size'] > 0)) {

            $upload_dir = 'uploads/chang_of_name_surname/notice_in_softcopy/' . $file_no . "/";
            // check whether upload directory is writable
            if (!is_dir($upload_dir) && !is_writable($upload_dir)) {
                mkdir($upload_dir, 0777, TRUE);
            }

            $config['upload_path'] = $upload_dir;
            $config['allowed_types'] = array('docx');
            $config['file_name'] = $_FILES['docu_3']['name'];
            $config['overwrite'] = true;
            $config['encrypt_name'] = TRUE;
            // 5 MB
            $config['max_size'] = '5242880';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('docu_3')) {
                $this->form_validation->set_message('handle_gazette_doc_upload_for_name_surname', $this->upload->display_errors('', ''));
                return false;
            } else {
                $this->upload_data['file'] = $this->upload->data();
                $this->doc_file_for_name_surname = base_url() . $upload_dir . $this->upload_data['file']['file_name'];
                //echo $this->doc_file;exit();
                return true;
            }
        } else {
            $this->form_validation->set_message('handle_gazette_doc_upload', 'No file selected');
        }
    }

    /*
     * Change of name/surname view details
     */

    public function view_details_name_surname($id) {
        if (!$this->session->userdata('is_c&t')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('commerce_transport_department/login_ct');
        }
        if (!$this->Applicants_login_model->exists_change_of_name($id)) {
            $this->session->set_flashdata('error', 'Change of Name/Surname does not exist');
            redirect('applicants_login/index_for_change_of_name_surname');
        }
        $data['states'] = $this->Applicants_login_model->get_states();
        $data['title'] = 'Change of Name/Surname View details';
        $data['gz_dets'] = $this->Applicants_login_model->view_details_change_name_surname($id);
        // echo '<pre>';print_r($data['gz_dets']);die("Woreked");
        $state_id = $data['gz_dets']->state_id;
        $data['districts'] = $this->Applicants_login_model->get_district_list($state_id);
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

    /*
     * Forward change in name/surname by C & T User (Verifier)
     */

    public function forward_change_name_surname_c_and_t_verifier() {

        $change_name_id = $this->security->xss_clean($this->input->post('change_name_id'));
        $remarks = $this->security->xss_clean($this->input->post('remarks'));

        if (!$this->Applicants_login_model->exists_change_of_name($change_name_id)) {
            $this->session->set_flashdata('error', 'Change of Name/Surname does not exist');
            redirect('applicants_login/index_for_change_of_name_surname');
        }
        $status = "";

        if($this->input->post('button_type_veri') == 'Forward'){
            $status = 4;
        }else {
            $status = 23;
        }

        $update_status = array(
            'id' => $change_name_id,
            'status' => $status,
            'remarks' => $remarks
        );
 
        if($this->input->post('button_type_veri') == 'Forward'){

            $result = $this->Applicants_login_model->forward_change_name_surname_c_and_t_verifier($update_status);

            if ($result) {
                audit_action_log($this->session->userdata('user_id'), 'Change of Name/Surname', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                // Notification
                $processers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Processor')    
                                ->where('module_id', 2)
                                ->get();
                foreach($processers->result() as $processer){
                    $processerID = $processer->id;
                   // echo $processerID;
                }    
                //die;
                $approvers = $this->db->from('gz_c_and_t')
                                        ->where('verify_approve', 'Approver')    
                                        ->where('module_id', 2)
                                        ->get()->row();
                // Processer
                $notification_data_ct = array(
                    'master_id' => $change_name_id,
                    'module_id' => 2,
                    'user_id' => $processerID,
                    'responsible_user_id' => $approvers->id,
                    'text' => "Change of name/surname request forwarded to approver",
                    'is_viewed' => 0,
                    'pro_ver_app' => 3,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_ct', $notification_data_ct);
                // Applicant
                $applicantDetails = $this->db->from('gz_change_of_name_surname_master')
                                ->where('id', $change_name_id)
                                ->get()->row();
                                //print_r($applicantDetails);
                $applicantID = $applicantDetails->user_id;
                $notification_data_applicant = array(
                    'master_id' => $change_name_id,
                    'module_id' => 2,
                    'user_id' => $this->session->userdata('user_id'),
                    'applicant_user_id' => $applicantID,
                    'text' => "Change of name/surname request forwarded to approver",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_applicant', $notification_data_applicant);



                $this->session->set_flashdata("success", "Change of name/surname forwarded successfully");
                redirect("applicants_login/index_for_change_of_name_surname");
            } 
            else {
            $this->session->set_flashdata("error", "Something went wrong");
            redirect("applicants_login/index_for_change_of_name_surname");
            } 
        }else{
            $result = $this->Applicants_login_model->return_to_applicant_c_and_t_verifier($update_status);

            if ($result) {
                audit_action_log($this->session->userdata('user_id'), 'Change of Name/Surname', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                // Notification
                $processers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Processor')    
                                ->where('module_id', 2)
                                ->get();
                foreach($processers->result() as $processer){
                    $processerID = $processer->id;
                    //echo $processerID;
                    // Processer
                    $notification_data_ct = array(
                        'master_id' => $change_name_id,
                        'module_id' => 2,
                        'user_id' => $processerID,
                        'text' => "Change of name/surname request retrurned to applicant",
                        'is_viewed' => 0,
                        'pro_ver_app' => 3,
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_notification_ct', $notification_data_ct);
                }    
                //die;
                
                // Applicant
                $applicantDetails = $this->db->from('gz_change_of_name_surname_master')
                                ->where('id', $change_name_id)
                                ->get()->row();
                                //print_r($applicantDetails);
                $applicantID = $applicantDetails->user_id;
                $notification_data_applicant = array(
                    'master_id' => $change_name_id,
                    'module_id' => 2,
                    'user_id' => $this->session->userdata('user_id'),
                    'applicant_user_id' => $applicantID,
                    'text' => "Change of name/surname request retrurned to applicant",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_applicant', $notification_data_applicant);


                $this->session->set_flashdata("success", "Change of name/surname returned to applicant successfully");
                redirect("applicants_login/index_for_change_of_name_surname");
            } 
            else {
            $this->session->set_flashdata("error", "Something went wrong");
            redirect("applicants_login/index_for_change_of_name_surname");
           }
        }
    }

    /*
     * Approve change in name/surname by C & T User (Approver)
     */

    public function approve_change_name_surname_c_and_t_approver() {

        $change_name_id = $this->security->xss_clean($this->input->post('change_name_id'));
        $current_status = $this->security->xss_clean($this->input->post('status'));
        $remarks = $this->security->xss_clean($this->input->post('remarks'));

        if (!$this->Applicants_login_model->exists_change_of_name($change_name_id)) {
            $this->session->set_flashdata('error', 'Change of Name/Surname does not exist');
            redirect('applicants_login/index_for_change_of_name_surname');
        }

        $status = "";
        // if ($current_status == 4) {
        //     $status = 7;
        // } else if ($current_status == 3) {
        //     $status = 12;
        // } else if ($current_status == 5) {
        //     $status = 13;
        // }

        if($this->input->post('button_type_approver') == 'Approve'){
         $status=10;  // $status = 7; 
        }else {
            $status = 24;
        }

        $update_status = array(
            'id' => $change_name_id,
            'status' => $status,
            'remarks' => $remarks
        );

        if($this->input->post('button_type_approver') == 'Approve'){

            $result = $this->Applicants_login_model->approve_change_name_surname_c_and_t_approver($update_status);

            if ($result) {
                audit_action_log($this->session->userdata('user_id'), 'Change of Name/Surname', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                $this->session->set_flashdata("success", "Change of name/surname approved successfully");
                redirect("applicants_login/index_for_change_of_name_surname");
            } else {
                $this->session->set_flashdata("error", "Something went wrong");
                redirect("applicants_login/index_for_change_of_name_surname");
            }
        } else {

            $result = $this->Applicants_login_model->return_to_applicant_change_name_surname_c_and_t_approver($update_status);

            if ($result) {
                audit_action_log($this->session->userdata('user_id'), 'Change of Name/Surname', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                $this->session->set_flashdata("success", "Change of name/surname returned to applicant successfully");
                redirect("applicants_login/index_for_change_of_name_surname");
            } else {
                $this->session->set_flashdata("error", "Something went wrong");
                redirect("applicants_login/index_for_change_of_name_surname");
            }

        }
    }

    /*
     * Forward change in name/surname by C & T User (Processor)
     */

    public function forward_change_name_surname_c_and_t_processor() {

        $change_name_id = $this->security->xss_clean($this->input->post('change_name_id'));
        $current_status = $this->security->xss_clean($this->input->post('status'));
        $remarks = $this->security->xss_clean($this->input->post('remarks'));

        // echo $this->input->post('button_type');exit;

        if (!$this->Applicants_login_model->exists_change_of_name($change_name_id)) {
            $this->session->set_flashdata('error', 'Change of Name/Surname does not exist');
            redirect('applicants_login/index_for_change_of_name_surname');
        }

        $status = "";

        if($this->input->post('button_type') == 'Forward'){
            $status = 2;
        }else {
            $status = 22;
        }

        $update_status = array(
            'id' => $change_name_id,
            'status' => $status,
            'remarks' => $remarks
        );
        
        if ($this->input->post('button_type') == 'Forward'){

            $result = $this->Applicants_login_model->forward_change_name_surname_c_and_t_processor($update_status);

            if ($result) {
                audit_action_log($this->session->userdata('user_id'), 'Change of Name/Surname', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                $this->session->set_flashdata("success", "Change of name/surname forwarded successfully");
                redirect("applicants_login/index_for_change_of_name_surname");
            } else {
                $this->session->set_flashdata("error", "Something went wrong");
                redirect("applicants_login/index_for_change_of_name_surname");
            }
        } else {
        $result = $this->Applicants_login_model->return_to_applicant_c_and_t_processor($update_status);
        if ($result) {
            audit_action_log($this->session->userdata('user_id'), 'Change of Name/Surname', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata("success", "Change of name/surname application returned to applicant");
            redirect("applicants_login/index_for_change_of_name_surname");
        } else {
            $this->session->set_flashdata("error", "Something went wrong");
            redirect("applicants_login/index_for_change_of_name_surname");
        }
        }
    }

    /*
     * Reject change of name/surname request (Approver)
     */

    public function reject_change_name_surname_c_and_t_approver() {

        $change_name_id = $this->security->xss_clean($this->input->post('change_name_id'));
        $remarks = $this->security->xss_clean($this->input->post('remarks'));

        if (!$this->Applicants_login_model->exists_change_of_name($change_name_id)) {
            $this->session->set_flashdata('error', 'Change of Name/Surname does not exist');
            redirect('applicants_login/index_for_change_of_name_surname');
        }

        $update_status = array(
            'id' => $change_name_id,
            'status' => 8,
            'remarks' => $remarks
        );

        $result = $this->Applicants_login_model->reject_change_name_surname_c_and_t_approver($update_status);

        if ($result) {
            audit_action_log($this->session->userdata('user_id'), 'Change of Name/Surname', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata("success", "Change of name/surname request rejected successfully");
            redirect("applicants_login/index_for_change_of_name_surname");
        } else {
            $this->session->set_flashdata("error", "Something went wrong");
            redirect("applicants_login/index_for_change_of_name_surname");
        }
    }

    /*
     * Reject change of name/surname (verifier)
     */

    public function reject_change_name_surname_c_and_t_verifier() {

        $change_name_id = $this->security->xss_clean($this->input->post('change_name_id'));
        $remarks = $this->security->xss_clean($this->input->post('remarks'));

        if (!$this->Applicants_login_model->exists_change_of_name($change_name_id)) {
            $this->session->set_flashdata('error', 'Change of Name/Surname does not exist');
            redirect('applicants_login/index_for_change_of_name_surname');
        }

        $update_status = array(
            'id' => $change_name_id,
            'status' => 5,
            'remarks' => $remarks
        );

        $result = $this->Applicants_login_model->reject_change_name_surname_c_and_t_verifier($update_status);

        if ($result) {
            audit_action_log($this->session->userdata('user_id'), 'Change of Name/Surname', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata("success", "Change of name/surname request rejected successfully");
            redirect("applicants_login/index_for_change_of_name_surname");
        } else {
            $this->session->set_flashdata("error", "Something went wrong");
            redirect("applicants_login/index_for_change_of_name_surname");
        }
    }

    /*
     * Reject change of name/surname (Processor)
     */

    public function reject_change_name_surname_c_and_t_processor() {
        $change_name_id = $this->security->xss_clean($this->input->post('change_name_id'));
        $remarks = $this->security->xss_clean($this->input->post('remarks'));

        if (!$this->Applicants_login_model->exists_change_of_name($change_name_id)) {
            $this->session->set_flashdata('error', 'Change of Name/Surname does not exist');
            redirect('applicants_login/index_for_change_of_name_surname');
        }

        $update_status = array(
            'id' => $change_name_id,
            'status' => 3,
            'remarks' => $remarks
        );

        $result = $this->Applicants_login_model->reject_change_name_surname_c_and_t_processor($update_status);

        if ($result) {
            audit_action_log($this->session->userdata('user_id'), 'Change of Name/Surname', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata("success", "Change of name/surname request rejected successfully");
            redirect("applicants_login/index_for_change_of_name_surname");
        } else {
            $this->session->set_flashdata("error", "Something went wrong");
            redirect("applicants_login/index_for_change_of_name_surname");
        }
    }

    /*
     * Forward to pay
     */

    public function forward_to_pay_change_name($id) {

        if (!$this->Applicants_login_model->exists_change_of_name($id)) {
            $this->session->set_flashdata('error', 'Change of Name/Surname does not exist');
            redirect('applicants_login/change_of_name_surname_govt_list');
        }

        $update_status = array(
            'id' => $id,
            'status' => 9,
        );

        $result = $this->Applicants_login_model->forward_to_pay_change_name($update_status);

        if ($result) {
            audit_action_log($this->session->userdata('user_id'), 'Change of Name/Surname', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata("success", "Change of name/surname request sent back to applicant for payment successfully");
            redirect("applicants_login/change_of_name_surname_govt_list");
        } else {
            $this->session->set_flashdata("error", "Something went wrong");
            redirect("applicants_login/change_of_name_surname_govt_list");
        }
    }

    /*
     * Government press user change of name/surname list
     */

    public function change_of_name_surname_govt_list() {
        if (!$this->session->userdata('logged_in') || !$this->session->userdata('is_admin')) {
                if ($this->session->userdata('is_c&t')) {
                    $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
                    redirect('commerce_transport_department/login_ct');
                } else if ($this->session->userdata('is_igr')) {
                    $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
                    redirect('igr_user/login');
                } else if ($this->session->userdata('is_applicant')) {
                    $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
                    redirect('applicants_login/index');
                } else {
                $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
                redirect('user/login');
            }
        }
        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }
        $data['title'] = 'Change of Name/Surname';
        $inputs = $this->input->post();

        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        } else{
            if($page == 0){
              $array_items = array('statusType', 'notice_date_form', 'notice_date_to', 'file_no');
              $this->session->unset_userdata($array_items);
              $inputs = array();
            } else {
              $inputs = $this->session->userdata();
            }
        }

        $config = array();
        $config["base_url"] = base_url() . "applicants_login/change_of_name_surname_govt_list";

        $config["total_rows"] = $this->Applicants_login_model->get_total_cnt_govt_change_name_pending($inputs);

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


        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        $data["status_list"] = $this->Applicants_login_model->status_change_name_surname_pending_list();

        $data['partners'] = $this->Applicants_login_model->get_total_cnt_govt_list_pending_change_name($config['per_page'], $offset, $inputs);

        $data["inputs"] = $inputs;
    
        //echo '<pre>'; print_r($data['partner_publish']);
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('change_of_name_surname/pending.php', $data);
        $this->load->view('template/footer.php');
    }
    //paid chnage name surname name list
    public function paid_change_of_name_surname_govt_list() {
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
        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }

        $data['title'] = 'Change of Name/Surname';
        $inputs = $this->input->post();

        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        } else{
            if($page == 0){
              $array_items = array('statusType', 'notice_date_form', 'notice_date_to', 'file_no');
              $this->session->unset_userdata($array_items);
              $inputs = array();
            } else {
              $inputs = $this->session->userdata();
            }
        }

        $config = array();
        $config["base_url"] = base_url() . "applicants_login/paid_change_of_name_surname_govt_list";

        $config["total_rows"] = $this->Applicants_login_model->get_total_cnt_govt_change_name_paid($inputs);

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

        $data["status_list"] = $this->Applicants_login_model->status_change_name_surname_pending_list();

        $data["inputs"] = $inputs;
        
        $data['partner_pay'] = $this->Applicants_login_model->get_total_cnt_govt_list_payed_change_name($config['per_page'], $offset,$inputs);

        //echo '<pre>'; print_r($data['partner_publish']);
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('change_of_name_surname/paid.php', $data);
        $this->load->view('template/footer.php');
    }
    public function published_change_of_name_surname_govt_list() {
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

        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }
        $data['title'] = 'Change of Name/Surname';
        $inputs = $this->input->post();

        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        } else{
            if($page == 0){
              $array_items = array('statusType', 'notice_date_form', 'notice_date_to', 'file_no');
              $this->session->unset_userdata($array_items);
              $inputs = array();
            } else {
              $inputs = $this->session->userdata();
            }
        }

        $config = array();
        $config["base_url"] = base_url() . "applicants_login/published_change_of_name_surname_govt_list";

        $config["total_rows"] = $this->Applicants_login_model->get_total_cnt_govt_change_name_published($inputs);

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

        $data["status_list"] = $this->Applicants_login_model->status_change_name_surname_pending_list();

        $data["inputs"] = $inputs;

        $data['partner_publish'] = $this->Applicants_login_model->get_total_cnt_govt_list_publish_change_name($config['per_page'], $offset,$inputs);

        //echo '<pre>'; print_r($data['partner_publish']);
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('change_of_name_surname/published.php', $data);
        $this->load->view('template/footer.php');
    }

    /**
     * Filter for Change of name surname govt list
     */

     public function search_change_of_name_surname_govt_list(){

        $data['title'] = 'Change of Name/Surname';

        $config = array();
        $config["base_url"] = base_url() . "applicants_login/change_of_name_surname_govt_list";

        $searchValue = array(
            'app_name' => trim($this->input->post('name')),
            'file_no' => trim($this->input->post('file_no')),
            'fdate' => trim($this->input->post('notice_date_form')),
            'tdate' => trim($this->input->post('notice_date_to')),
        );
        
        $inputs = $this->input->post();
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

        //$config["total_rows"] = $this->Applicants_login_model->get_total_cnt_govt_change_name();

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

        $data['partners'] = $this->Applicants_login_model->get_total_cnt_govt_list_change_name_search($config["per_page"], $offset, $inputs);
        $data['partner_pay'] = $this->Applicants_login_model->get_total_cnt_govt_list_payed_change_name_search($config["per_page"], $offset, $inputs);
        $data['partner_publish'] = $this->Applicants_login_model->get_total_cnt_govt_list_publish_change_name_search($config["per_page"], $offset, $inputs);

        //echo '<pre>'; print_r($data['partner_publish']);
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('change_of_name_surname/index.php', $data);
        $this->load->view('template/footer.php');

     }

    /*
     * View Details for change of name/surname of govt press user
     */

    public function view_details_change_name_govt($id) {

        //$this->output->enable_profiler(TRUE);

        if (!$this->Applicants_login_model->exists_change_of_name($id)) {
            $this->session->set_flashdata('error', 'Change of Name/Surname does not exist');
            redirect('applicants_login/change_of_name_surname_govt_list');
        }

        $data['title'] = 'Change of Name/Surname View details';
        $data['id'] = $id;
        $data['pdf'] = $this->Applicants_login_model->get_pdf_path_of_change_of_name($id);
        $data['details'] = $this->Applicants_login_model->view_details_change_name_surname($id);
        $data['gz_dets'] = $this->Applicants_login_model->view_details_change_name_surname($id);
        $data['signed_name'] = $this->session->userdata('name');
        $data['designation'] = $this->session->userdata('designation');

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('change_of_name_surname/view_details.php', $data);
        $this->load->view('template/footer.php');
    }

    public function updatepaymentstatus(){      
        $data['details'] = $this->Applicants_login_model->updateStaus($this->input->post('filenum'));
        //  redirect('make_payment/change_name');
         redirect('check_status/change_name_status');
    }

    public function signpdf(){
        $data['details'] = $this->Applicants_login_model->signpdf($this->input->post('filenum'));
        redirect('applicants_login/published_change_of_name_surname_govt_list');
    }

    /*
     * Payment response
     */

    public function change_name_surname_payment_response() {
        // session_regenerate_id(true);
        $user_id = $this->session->userdata('user_id');
         header('Set-Cookie: ' . session_name() . '=' . session_id() . '; SameSite=None; Secure', false);

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            // Binary File
            $binary_file_path = './binary_key/EGZ_binary_UAT.key';

            $handle = fopen($binary_file_path, "rb");
            $secret_key = fread($handle, filesize($binary_file_path));
            // Get the message string in Response from IFMS
            $message = $this->decrypt($this->input->post('msg'), $secret_key);
            // explode the data string separated by |
            $data_array = explode("|", $message);
            // print_r($data_array);
            // exit;

            // echo '----------------<br>';
            // assign variables
            $dept_ref_no = $data_array[1];
            $total_amnt = $data_array[20];
            $data = explode('!~!', $data_array[29]);

            $chln_ref_no = $data_array[36];
            $pay_mode = $data_array[37];
            $bnk_name = $data_array[38];
            $bnk_trans_id = $data_array[39];
            $bnk_trans_stat = $data_array[40];
            $bnk_trans_msg = $data_array[41];
            $bnk_trans_time = $data_array[42];

            // INSERT INTO the main Table
            $insert_array = array(
                'change_name_surname_id' => $data[0],
                'file_number' => $data[1],
                'dept_ref_id' => $dept_ref_no,
                'challan_ref_id' => $chln_ref_no,
                'amount' => $total_amnt,
                'pay_mode' => $pay_mode,
                'bank_trans_id' => $bnk_trans_id,
                'bank_name' => $bnk_name,
                'bank_trans_msg' => $bnk_trans_msg,
                'bank_trans_time' => $bnk_trans_time,
                'trans_status' => $bnk_trans_stat,
                'created_at' => date('Y-m-d H:i:s', time()),
                'user_id' => $user_id
            );

            // echo "<pre>";
            // print_r($insert_array);
            // exit;
            $result = $this->Applicants_login_model->save_change_name_surname_trans_status($insert_array);

            if ($result && $bnk_trans_stat == 'S') {
                $this->session->set_flashdata('success', 'Payment completed successfully');
                redirect('applicants_login/index_for_change_of_name_surname');
            } else if ($result && $bnk_trans_stat == 'F') {
                $this->session->set_flashdata('error', 'Payment Failed');
                redirect('applicants_login/index_for_change_of_name_surname');
            } else if ($result && $bnk_trans_stat == 'P') {
                $this->session->set_flashdata('error', 'Payment Pending');
                redirect('applicants_login/index_for_change_of_name_surname');
            } else if ($result && $bnk_trans_stat == 'I') {
                $this->session->set_flashdata('error', 'Payment Initiated');
                redirect('applicants_login/index_for_change_of_name_surname');
            } else if ($result && $bnk_trans_stat == 'X') {
                $this->session->set_flashdata('error', 'Transaction cancelled by Applicant');
                redirect('applicants_login/index_for_change_of_name_surname');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong');
                redirect('applicants_login/index_for_change_of_name_surname');
            }
        }
    }

    /*
     * Sign PDF
     */

    public function change_name_surname_sign_pdf($id) {

        if (!$this->Applicants_login_model->exists_change_of_name($id)) {
            $this->session->set_flashdata('error', 'Change of Name/Surname does not exist');
            redirect('applicants_login/change_of_name_surname_govt_list');
        }

        $update_status = array(
            'press_signed_pdf' => 1,
            'modified_at' => date("Y-m-d H:i:s", time()),
            'modified_by' => $this->session->userdata('user_id')
        );

        $result = $this->Applicants_login_model->change_name_surname_sign_pdf($update_status, $id);

        if ($result) {

            $this->session->set_flashdata('success', 'PDF signed successfully');
            redirect('applicants_login/view_details_change_name_govt/' . $id);
        } else {

            $this->session->set_flashdata('error', 'Something went wrong');
            redirect('applicants_login/view_details_change_name_govt/' . $id);
        }
    }

    /*
     * Publish change of name/surname
     */

    public function change_name_surname_publish($id) {

        if (!$this->Applicants_login_model->exists_change_of_name($id)) {
            $this->session->set_flashdata('error', 'Change of Name/Surname does not exist');
            redirect('applicants_login/change_of_name_surname_govt_list');
        }

        $update_status = array(
            'is_published' => 1,
            'id' => $id,
            'status' => 11
        );

        $result = $this->Applicants_login_model->change_name_surname_publish($update_status);

        if ($result) {

            $this->session->set_flashdata('success', 'Change of name/surname gazette published successfully');
            redirect('applicants_login/change_of_name_surname_govt_list');
        } else {

            $this->session->set_flashdata('error', 'Something went wrong');
            redirect('applicants_login/change_of_name_surname_govt_list/');
        }
    }

    /*
     * GET Govt. Press signed PDF file path after signing using e-Sign from CDAC.
     * For the time being we are redirecting to .NET code, again .NET code is providing the signed file
     */

    public function get_press_signed_pdf_path_change_name_surname() {

        $pdf_file_name = $this->input->get('files');

        $gazette_id = $this->input->get('gazette_id');

        $type = $this->input->get('type');

        // signed PDF file path
        $signed_pdf_path = './uploads/chang_of_name_surname/press_signed_pdf/' . $pdf_file_name;

        $data = array(
            'gazette_id' => $gazette_id,
            'press_signed_pdf_path' => $signed_pdf_path,
        );

        $result = $this->Applicants_login_model->get_press_signed_pdf_path_change_name_surname($data);

        if ($result) {

            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'Extraordinary Gazette', 'Press Signed', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata('success', 'Document signed successfully');
            redirect('applicants_login/view_details_change_name_govt/' . $gazette_id);
        }
    }

    public function press_add_change_name_surname($id) {

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

        $gazette_id = $id;

        $data['title'] = 'Press Preview';
        $data['details'] = $this->Applicants_login_model->get_gazette_documents_change_name_surname($gazette_id);
        $data['gazette_id'] = $gazette_id;
        $data['sl_no'] = $this->Applicants_login_model->get_sl_no_change_name_surname();

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('applicants_login/press_add_change_name_surname.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Press preview for adding docket
     */

    public function press_preview_change_name_surname() {

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

        // $this->form_validation->set_rules('gazette_id', 'Gazette ID', 'trim|required');
        // $this->form_validation->set_rules('sl_no', 'Sl No', 'trim|required');
        $this->form_validation->set_rules('issue_date', 'Issue Date', 'trim|required');
        $this->form_validation->set_rules('saka_month', 'Shakabda Month', 'trim|required');
        $this->form_validation->set_rules('saka_date', 'Shakabda Date', 'trim|required');
        $this->form_validation->set_rules('saka_year', 'Shakabda Year', 'trim|required');

        if ($this->form_validation->run() == false) {
            //$this->load->view('gazette/press_add', $data);
        } else {

            $gazette_id = $this->security->xss_clean($this->input->post('gazette_id'));

            // get the file submitted by Dept. user and make the required changes in the PDF file
            $gazette_docs = $this->Applicants_login_model->get_gazette_documents_change_name_surname($gazette_id);

            // data needs to be updated
            $array_data = array(
                'gazette_id' => $gazette_id,
                'user_id' => $this->session->userdata('user_id'),
                'sl_no' => $this->input->post('sl_no'),
                'status_id' => 17,
                'saka_date' => $this->input->post('saka_month').', '.$this->input->post('saka_date').', '.$this->input->post('saka_year'),
            );

            // get the userdata from database using model
            $result = $this->Applicants_login_model->save_preview_press_gazette_change_name_surname($array_data);
            $this->load->library('phpqrcode/qrlib');
            
            $qr_text = "Gazette Number:" . $this->input->post('sl_no') . " " . "Change of Name/Surname" . " " . "Published Date:" . $this->input->post('issue_date');
            
            $folder = 'uploads/qrcodes/';
            $file = $gazette_id . "_" . md5(time()) . ".jpeg";
            $file_name = $folder . $file;
            
            QRcode::png($qr_text, $file_name);

            $word_file = $gazette_docs->notice_in_softcopy;

            $dynamic_data = array(
                'gazette_id' => $gazette_id,
                'sl_no' => $this->security->xss_clean($this->input->post('sl_no')),
                'issue_date' => $this->security->xss_clean($this->input->post('issue_date')),
                'sakabda_date' => $this->security->xss_clean($this->input->post('saka_month').', '.$this->input->post('saka_date').', '.$this->input->post('saka_year')),
                'qr_code' => base_url().$file_name
            );

            $template_file = FCPATH . './uploads/sample/cos_cop_extraordinary_sample.docx';

            // Generate Press PDF with updated values
            $press_pdf_file = $this->convert_press_word_to_PDF_change_name_surname($template_file, $word_file, $dynamic_data);


            // UPDATE press PDF in documents table
            $this->db->where('gz_master_id', $gazette_id);
            $this->db->update('gz_change_of_name_surname_doument_det', array('press_pdf' => $press_pdf_file));

            if ($result) {
                redirect('applicants_login/view_details_change_name_govt/' . $gazette_id);
            } else {
                //Put the array in a session            
                $this->session->set_flashdata('error', 'Something went wrong');
                redirect('applicants_login/change_of_name_surname_govt_list');
            }
        }
    }

    /*
     * Convert Word file to PDF
     */

    public function convert_press_word_to_PDF_change_name_surname($template_file, $word_file, $data) {

        // store in database
        $file_number = $this->Applicants_login_model->get_file_number_change_name_surname($data['gazette_id']);
        $press_word_db_path = './uploads/chang_of_name_surname/press_doc/' . $file_number->file_no . '.docx';
        $press_word_path = FCPATH . $press_word_db_path;

        // Merge 2 documents
        $dm = new DocxMerge();
        $dm->merge([
            $template_file,
            $word_file
                ], $press_word_path);

        // load from template processor
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($press_word_path);
        // set dynamic values provided by Govt. Press
        $templateProcessor->setValue('sl_no', $data['sl_no']);
        $templateProcessor->setValue('issue_date', strtoupper($data['issue_date']));
        $templateProcessor->setValue('sakabda_date', $data['sakabda_date']);
        $templateProcessor->setImageValue('qr_code', array('path' => $data['qr_code'], 'width' => 50, 'height' => 50, 'ratio' => TRUE));


        $templateProcessor->saveAs($press_word_path);

        // UPDATE into documents table
        $this->db->where('gz_master_id', $data['gazette_id']);
        $this->db->update('gz_change_of_name_surname_doument_det', array('press_notice_in_softcopy_word' => $press_word_db_path));

        // Convert to PDF using MS Word using PHP COM object
        $word = new COM("word.application") or die("Could not initialise MS Word object.");
        $word->Documents->Open($press_word_path);

        $pdf_file_db_path = './uploads/chang_of_name_surname/press_pdf/' . $file_number->file_no . '.pdf';
        $pdf_file_path = FCPATH . $pdf_file_db_path;

        $word->ActiveDocument->ExportAsFixedFormat($pdf_file_path, 17, false, 0, 0, 0, 0, 7, true, true, 2, true, true, false);

        $word->Quit();
        $word = null;
        return $pdf_file_db_path;
    }

    /*
     * Ashwini Code
     */
    /*
     * Ashwini Code
     */

    public function partnership_details_list() {
        if (!$this->session->userdata('logged_in') || (!$this->session->userdata('is_c&t') && !$this->session->userdata('is_igr'))) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            if ($this->session->userdata('is_c&t')) {
                redirect('commerce_transport_department/login_ct');
            } else if ($this->session->userdata('is_igr')) {
                redirect('igr_user/login');
            } else if ($this->session->userdata('is_admin')) {
                redirect('user/login');
            } else if ($this->session->userdata('is_applicant')) {
                redirect('applicants_login/index');
            } else {
                redirect('commerce_transport_department/login_ct');
            }
        }
        // if ($this->session->userdata('logged_in')) {
        //     // redirect('applicants_login/index');
        //     if (!($this->session->userdata('is_c&t') || $this->session->userdata('is_igr'))) {
        //         $this->session->set_flashdata('error', 'You are not authorized!');
        //         if($this->session->userdata('is_applicant')){
        //             redirect('applicants_login/index');
        //         } else if ($this->session->userdata('is_admin')) {
        //             redirect('user/login');
        //         } 
        //     }
        // }
        // else{
        //     redirect('applicants_login/index');
        // }
        if (!$this->session->userdata('force_password')) {
            if ($this->session->userdata('is_igr') == true) {
                $this->session->set_flashdata('error', 'You must change your password after first Login!');
                redirect('Igr_user/change_password');
            } else if ($this->session->userdata('is_c&t') == true) {
                $this->session->set_flashdata('error', 'You must change your password after first Login!');
                redirect('commerce_transport_department/change_password');
            }

            // $this->session->set_flashdata('error', 'You must change your password after first Login!');
            // redirect('commerce_transport_department/change_password');
        }

        // if (!$this->session->userdata('is_c&t')) {
        //     $this->session->set_flashdata('error', 'You are not authorized!');
        //     redirect('commerce_transport_department/login_ct');
        // } 
        // else if (!$this->session->userdata('is_igr')) {
        //     $this->session->set_flashdata('error', 'You are not authorized!');
        //     redirect('igr_user/login');
        // }

        $data['title'] = 'Partnership details list';

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "Applicants_login/partnership_details_list";

        $config["total_rows"] = $this->Applicants_login_model->get_total_parter_count();

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

        $data['partners'] = $this->Applicants_login_model->get_total_parter($config['per_page'], $offset);
        $data['total_status'] = $this->Applicants_login_model->get_status_partnership();
        // print_r($data['partners']);exit();
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/index.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    /*
    * Search Operation In Change Of Partnership Details
    */
    public function search_for_partnership() {

        if (!$this->session->userdata('logged_in')) {
            redirect('applicants_login/index');
        }
        $data['title'] = "Change of Partnership";
        $config["base_url"] = base_url() . "applicants_login/search_for_partnership";

        $searchValue = array(
            'app_name' => trim($this->input->post('name')),
            'file_no' => trim($this->input->post('file_no')),
            'status' => trim($this->input->post('status')),
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
              $array_items = array('app_name', 'file_no', 'status', 'notice_date_form', 'notice_date_to');
              $this->session->unset_userdata($array_items);
              $inputs =array();
            }else{
              $inputs = $this->session->userdata();
            }
        }

        $config["total_rows"] = $this->Applicants_login_model->get_total_parter_count_search($inputs);

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

        //$page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

            $data['partners'] = $this->Applicants_login_model->change_of_partnership_search_result($config["per_page"], $offset, $inputs);
            //echo $this->db->last_query();
            $data['total_status'] = $this->Applicants_login_model->get_status_partnership();
            $data['inputs'] = $inputs;

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/index.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    /*
     * Search for Published partnership
     */
    public function search_for_published_partnership() {
 
         if (!$this->session->userdata('logged_in')) {
             redirect('applicants_login/index');
         }

         

         $data['title'] = "Change of Partnership";
         $config["base_url"] = base_url() . "applicants_login/search_for_published_partnership";
 
         $searchValue = array(
             'app_name' => trim($this->input->post('name')),
             'file_no' => trim($this->input->post('file_no')),
             'fdate' => trim($this->input->post('notice_date_form')),
             'tdate' => trim($this->input->post('notice_date_to')),
         );
 
         $inputs = $this->input->post();
         $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
         if($this->input->post()){
             $page = 0;
             $this->session->set_userdata($inputs);
         }
         else{
             if($page==0){
               $array_items = array('app_name', 'file_no','notice_date_form', 'notice_date_to');
               $this->session->unset_userdata($array_items);
               $inputs =array();
             }else{
               $inputs = $this->session->userdata();
             }
         }

         $config["total_rows"] = $this->Applicants_login_model->get_total_published_partner_count_search($inputs);
 
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
 
         //$page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
 
         if ($page > 0) {
             $offset = ($page - 1) * $config["per_page"];
         } else {
             $offset = $page;
         }
 
         $data["links"] = $this->my_pagination->create_links();
 
         $data['partners'] = $this->Applicants_login_model->change_of_published_partnership_search_result($config["per_page"], $offset, $inputs);
         $data['total_status'] = $this->Applicants_login_model->get_status_partnership();
 
         $this->load->view('template/header_applicant.php', $data);
         $this->load->view('template/sidebar_applicant.php');
         $this->load->view('applicants_login/published_partnership.php', $data);
         $this->load->view('template/footer_applicant.php');
    }

    public function partnership_details_list_weekly() {

        $data['title'] = 'Partnership details list';

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "Applicants_login/partnership_details_list";

        $config["total_rows"] = $this->Applicants_login_model->get_total_parter_count_weekly();

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

        $data['partners'] = $this->Applicants_login_model->get_total_parter_weekly($config['per_page'], $offset);
        // print_r($data['partners']);exit();
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/index.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    public function add_partnership_details() {
        if (!$this->session->userdata('is_applicant')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }
        $data['title'] = 'Add partnership details';
        
        $data['gz_types'] = $this->Applicants_login_model->get_total_gz_types();
        $data['states'] = $this->Applicants_login_model->get_states();
        $data['tot_docus'] = $this->Applicants_login_model->get_total_tot_docu();
        $data['count'] = count($data['tot_docus']);


        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/add_partnership_details.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    public function insert_partnership_details() {
        //        echo '<pre>';
        //        //print_r($_FILES);exit();
        //        //print_r($this->input ->post());exit();

        $data['title'] = 'Add Partnership Details';

        $json = array();

        //print_r($this->input->post());exit();
        if (!$this->session->userdata('logged_in')) {
            //$this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('applicants_login/index');
        }
        $var = "";
        
        $this->form_validation->set_rules('partnership_firm_name', 'Partnership Firm Name', 'trim|required|min_length[5]|max_length[30]');
        $this->form_validation->set_rules('partnership_registration_no', 'Partnership Registration No', 'trim|required|min_length[5]|max_length[20]');
        $this->form_validation->set_rules('state_id', 'State', 'trim|required');
        $this->form_validation->set_rules('district_id', 'District', 'trim|required');
        $this->form_validation->set_rules('police_station_id', 'Police Station', 'trim|required');
        $this->form_validation->set_rules('address_1', 'Address 1', 'trim|required|min_length[5]|max_length[50]');
        $this->form_validation->set_rules('address_2', 'Address 2', 'trim|required|min_length[5]|max_length[50]');
        $this->form_validation->set_rules('pincode', 'pincode', 'trim|required|min_length[6]|max_length[6]|numeric');

        $this->form_validation->set_rules('upload_7', 'Select Word/Docx File', 'callback_handle_gazette_doc_upload[' . $var . ']');

        if ($this->form_validation->run() == false) {

            // store all the error data in error array
            $json['error'] = $this->form_validation->error_array();
        } else {

            // $type = "XP";

            $return = $this->db->select('file_no')
                            ->from('gz_change_of_partnership_master')
                            ->where('deleted', '0')
                            ->order_by('id', 'DESC')
                            ->limit(1)
                            ->get()->row();

            $final_file_no1 = "0001";

            if (!empty($return)) {
                $file_no = $return->file_no;
                $ex_file_no = explode('-', $file_no);
                $final_file_no = $ex_file_no[1] + 1;
                $len = strlen($final_file_no);
                if ($len == 1) {
                    $final_file_no1 = '000' . $final_file_no;
                } else if ($len == 2) {
                    $final_file_no1 = '00' . $final_file_no;
                } else if ($len == 3) {
                    $final_file_no1 = '0' . $final_file_no;
                } else if ($len == 4) {
                    $final_file_no1 = $final_file_no;
                }
            }

            $year = date("Y");

            $file_type = 'XP-' . $final_file_no1 . '-' . $year;


            //echo ;exit();uploads/Notice in Softcopy Doc/'.
            $word_file = FCPATH . $this->doc_file;
            // Convert Word file To PDF
            $pdf_file_path = $this->convert_word_to_PDF($word_file, "");
            // get the userdata from database using model
            $result = $this->Applicants_login_model->insert_partnership_details($this->input->post(), $this->doc_file, $pdf_file_path);

            if ($result) {
                //Put the message data in a session   

                audit_action_log($this->session->userdata('user_id'), 'Partnership details', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                $this->session->set_flashdata("success", "Change of partnership added successfully");
                //$json['success'] = "Partship details added successfully.";
                $json['redirect'] = base_url() . "check_status/change_partnership_status";
            } else {
                $json['error'] = "Partship details added not successfully.";

                $json['redirect'] = base_url() . "check_status/change_partnership_status";
            }
        }
        echo json_encode($json);
    }

    // partnership document upload function
    public function upload_document() {
        

        $file_no_par = $this->input->post('file_no_par');

        //echo $file_no_par;exit();

        if ($file_no_par == '') {

            $type = "XP";
            //        if($data['gazette_id'] == '1') {
            //          $type = 'X';  
            //        } else {
            //          $type = 'W';  
            //        }

            $return = $this->db->select('file_no')
                            ->from('gz_change_of_partnership_master')
                            ->where('deleted', '0')
                            ->order_by('id', 'DESC')
                            ->limit(1)
                            ->get()->row();



            $final_file_no1 = "0001";
            if (!empty($return)) {
                $file_no = $return->file_no;
                $ex_file_no = explode('-', $file_no);
                $final_file_no = $ex_file_no[1] + 1;
                $len = strlen($final_file_no);
                if ($len == 1) {
                    $final_file_no1 = '000' . $final_file_no;
                } else if ($len == 2) {
                    $final_file_no1 = '00' . $final_file_no;
                } else if ($len == 3) {
                    $final_file_no1 = '0' . $final_file_no;
                } else if ($len == 4) {
                    $final_file_no1 = $final_file_no;
                }
            }

            $year = date("Y");

            $file_type = $type . '-' . $final_file_no1 . '-' . $year;
        } else {

            $file_type = $file_no_par;
        }

        $doc_id = $this->input->post('id');

        $doc_type = $this->db->select('document_name')
                        ->from('gz_partnership_docu_det_master')
                        ->where('deleted', '0')
                        ->where('id', $doc_id)
                        ->get()->row();

        // echo $doc_type->document_name;exit();

        $data = array();

        if (isset($_FILES['file'])) {

            if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {

                $tmp_name = $_FILES["file"]["tmp_name"];
                $name = $_FILES["file"]["name"];

                $upload_dir = 'uploads/partnership_change/' . trim($doc_type->document_name) . '/' . $file_type . "/";
                //echo $upload_dir;  

                if (!is_dir($upload_dir) && !is_writeable($upload_dir)) {
                    mkdir($upload_dir, 0777, TRUE);
                }
                $upd_file = time() . "_" . str_replace(" ", "", basename($name));
                $file_path = $upload_dir . $upd_file;
                // database path to store and find the incident images
                $DB_Image_Path = base_url() . $file_path;

                if (!file_exists($file_path)) {
                    if (move_uploaded_file($tmp_name, $file_path)) {
                        $data = array('success' => $DB_Image_Path);
                    } else {
                        $data = array('error' => "File not uploaded");
                    }
                } else {
                    $data = array('error' => "File already exists");
                }
            } else {

                $data = array('error' => $_FILES['file']['error']);
            }
        } else {
            $data = array('error' => 'File is not attached');
        }
        echo json_encode($data);
    }

    public function add_pan() {
        $id = $this->input->post('id');
        $pan_text = $this->input->post('pan_text');
        ?>

        <div class="form-group  col-md-6">
            <label for="email">PAN Card of Partners<span class="asterisk">*</span>
                <span class="file_icons_add">

                </span>
            </label>
            <div class="row fileupload-buttonbar clearfix">
                <div class="col-lg-6">
                    <span class="btn btn-raised btn-success fileinput-button">
                        <i class="glyphicon glyphicon-plus"></i>               
                        <span>Select File</span>
                        <input type="file" name = "upload_<?php echo $id; ?>" id = "upload_<?php echo $id . '_' . $pan_text; ?>" >
                        <input type="hidden" id="img_id_<?php echo $id . '_' . $pan_text; ?>" name ="img_id_<?php echo $id; ?>[]" />


                    </span>
                    <span class="files"></span>
                </div>

            </div>
            <span class="help-block mb-0">Maximum 1 MB allowed.</span>
            <div class="clearfix"></div>
        <?php if (form_error('doc_files')) { ?>
                <span class="error"><?php echo form_error('doc_files'); ?></span>
        <?php } ?>
        </div> 

        <?php
    }
    
    public function add_pan_edit() {
        $id = $this->input->post('id');
        $pan_text = $this->input->post('pan_text');
        ?>

        <div class="form-group  col-md-4">
            <label for="email">PAN Card of Partners <span class="asterisk">*</span>
                <span class="file_icons_add">
                    <i class="fa fa-file-image-o"></i>
                </span>
            </label>
            <div class="row fileupload-buttonbar clearfix">
                <div class="col-lg-4">
                    <span class="btn btn-raised btn-success fileinput-button">
                        <i class="glyphicon glyphicon-plus"></i>               
                        <span>Choose File</span>
                        <input type="file" name = "upload_<?php echo $id; ?>" id = "upload_<?php echo $id . '_' . $pan_text; ?>" class="upload" data-key="<?php echo $id . '_' . $pan_text; ?>" accept="image/*">
                        <input type="hidden" id="img_id_<?php echo $id . '_' . $pan_text; ?>" name ="img_id_<?php echo $id; ?>[]" />


                    </span>
                    <span class="files_4_<?php echo $pan_text ?>"></span>
                </div>

            </div>
            <span class="help-block mb-0">Maximum 1 MB allowed.</span>
            <span class="error_message_image_4_<?php echo $pan_text; ?> error"></span>
            <div class="clearfix"></div>
        <?php if (form_error('doc_files')) { ?>
                <span class="error"><?php echo form_error('doc_files'); ?></span>
        <?php } ?>
        </div> 

        <?php
    }

    public function add_aadhar() {
        $id = $this->input->post('id');
        $aadhar_text = $this->input->post('aadhar_text');
        ?>

        <div class="form-group col-md-6">
            <label for="email">Aadhar Card of Partners<span class="asterisk">*</span>
                <span class="file_icons_add">
                    <i class="fa fa-file-image-o"></i>
                </span>
            </label>
            <div class="row fileupload-buttonbar clearfix">
                <div class="col-lg-6">
                    <span class="btn btn-raised btn-success fileinput-button">
                        <i class="glyphicon glyphicon-plus"></i>               
                        <span>Select File</span> 
                        <input type="file" name = "upload_<?php echo $id; ?>" id = "upload_<?php echo $id . '_' . $aadhar_text; ?>" >
                        <input type="hidden" id="img_id_<?php echo $id . '_' . $aadhar_text; ?>" name ="img_id_<?php echo $id; ?>[]" />
                    </span>
                    <span class="files"></span>
                </div>

            </div>
            <span class="help-block mb-0">Maximum 1 MB allowed.</span>
            <div class="clearfix"></div>
        <?php if (form_error('doc_files')) { ?>
                <span class="error"><?php echo form_error('doc_files'); ?></span>
        <?php } ?>
        </div> 

        <?php
    }
    
    public function add_aadhar_edit() {
        $id = $this->input->post('id');
        $aadhar_text = $this->input->post('aadhar_text');
        ?>

        <div class="form-group col-md-4">
            <label for="email">Aadhar Card of Partners <span class="asterisk">*</span>
                <span class="file_icons_add">
                    <i class="fa fa-file-image-o"></i>
                </span>
            </label>
            <div class="row fileupload-buttonbar clearfix">
                <div class="col-lg-4">
                    <span class="btn btn-raised btn-success fileinput-button">
                        <i class="glyphicon glyphicon-plus"></i>               
                        <span>Choose File</span> 
                        <input type="file" name = "upload_<?php echo $id; ?>" id = "upload_<?php echo $id . '_' . $aadhar_text; ?>" class="upload" data-key="<?php echo $id . '_' . $aadhar_text; ?>" accept="image/*">
                        <input type="hidden" id="img_id_<?php echo $id . '_' . $aadhar_text; ?>" name ="img_id_<?php echo $id; ?>[]" />
                    </span>
                    <span class="files_5_<?php echo $aadhar_text; ?>"></span>
                </div>

            </div>
            <span class="help-block mb-0">Maximum 1 MB allowed.</span>
            <span class="error_message_image_5_<?php echo $aadhar_text; ?> error"></span>
            <div class="clearfix"></div>
        <?php if (form_error('doc_files')) { ?>
                <span class="error"><?php echo form_error('doc_files'); ?></span>
        <?php } ?>
        </div> 

        <?php
    }

    public function View_details_par($id) {

        $data['title'] = 'Partnership View details';

        $data['gz_dets'] = $this->Applicants_login_model->View_details_par($id);

        $data['tot_docus'] = $this->Applicants_login_model->get_total_tot_docu();

        $data['details'] = $this->Applicants_login_model->select_cur_path_load($id);
        //print_r($data['details']  );exit();
        $data['id'] = $id;

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/applicant_det_preview.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    /*
     * Convert Word file to PDF
     */

    public function convert_word_to_PDF($word_file, $file_no) {

        // Convert to PDF using MS Word using PHP COM object
        $word = new COM("word.application") or die("Could not initialise MS Word object.");
        //echo $word_file;exit();
        $word->Documents->Open($word_file) or die($word_file);

        if ($file_no != '') {

            $file_type = $file_no;
        } else {

            $type = "X";

            $return = $this->db->select('file_no')
                            ->from('gz_change_of_partnership_master')
                            ->where('deleted', '0')
                            ->order_by('id', 'DESC')
                            ->limit(1)
                            ->get()->row();

            $final_file_no1 = "0001";


            if (!empty($return)) {
                $file_no = $return->file_no;
                $ex_file_no = explode('-', $file_no);
                $final_file_no = $ex_file_no[1] + 1;
                $len = strlen($final_file_no);
                if ($len == 1) {
                    $final_file_no1 = '000' . $final_file_no;
                } else if ($len == 2) {
                    $final_file_no1 = '00' . $final_file_no;
                } else if ($len == 3) {
                    $final_file_no1 = '0' . $final_file_no;
                } else if ($len == 4) {
                    $final_file_no1 = $final_file_no;
                }
            }

            $year = date("Y");

            $file_type = 'COM-PUB-' . $type . '-' . $final_file_no1 . '-' . $year;
        }


        $pdf_file_db_path1 = './uploads/partnership_change/Notice_in_Softcopy_Pdf/' . $file_type . '/';

        // check whether upload directory is writable
        if (!is_dir($pdf_file_db_path1) && !is_writable($pdf_file_db_path1)) {
            mkdir($pdf_file_db_path1, 0777, TRUE);
        }

        $pdf_file_db_path = 'uploads/partnership_change/Notice_in_Softcopy_Pdf/' . $file_type . '/' . time() . '.pdf';
        ///echo $pdf_file_db_path;exit();

        $var = FCPATH;
        $pdf_file_path = $var . $pdf_file_db_path;

        //echo $pdf_file_path;


        $word->ActiveDocument->ExportAsFixedFormat($pdf_file_path, 17, false, 0, 0, 0, 0, 7, true, true, 2, true, true, false);

        $word->Quit();
        $word = null;
        return $pdf_file_db_path;
    }

    /*
     * 
     */

    public function edit_partnership_details($id) {
        if (!$this->session->userdata('logged_in') || (!$this->session->userdata('is_c&t') && !$this->session->userdata('is_igr'))) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            if ($this->session->userdata('is_c&t')) {
                redirect('commerce_transport_department/login_ct');
            } else if ($this->session->userdata('is_igr')) {
                redirect('igr_user/login');
            } else if ($this->session->userdata('is_admin')) {
                redirect('user/login');
            } else if ($this->session->userdata('is_applicant')) {
                redirect('applicants_login/index');
            } else {
                redirect('commerce_transport_department/login_ct');
            }
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

        $data['binary_key'] = './binary_key/EGZ_binary_UAT.key';

        $data['par_id'] = $id;

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/applicant_det_edit.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    /*
     * update partnership details
     */

    public function update_partnership_details() {
        if (!$this->session->userdata('is_applicant')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }
        $data['title'] = 'Edit Partnership Details';

        $json = array();

        //print_r($this->input->post());exit();
        if (!$this->session->userdata('logged_in')) {
            //$this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('applicants_login/index');
        }
        $file_no_par = $this->input->post('file_no_par');

        //echo "ok";exit();     
        if (!empty($_FILES['upload_7']['name']) && ($_FILES['upload_7']['size'] > 0)) {
            $this->form_validation->set_rules('upload_7', 'Select Word/Docx File', 'callback_handle_gazette_doc_upload[' . $file_no_par . ']');
        }

        $this->form_validation->set_rules('state_id', 'State', 'trim|required');
        $this->form_validation->set_rules('district_id', 'District', 'trim|required');
        $this->form_validation->set_rules('police_station_id', 'Police Station', 'trim|required');
        $this->form_validation->set_rules('address_1', 'Address 1', 'trim|required|min_length[5]|max_length[50]');
        $this->form_validation->set_rules('address_2', 'Address 2', 'trim|required|min_length[5]|max_length[50]');

        if ($this->form_validation->run() == false) {

            // store all the error data in error array
            $json['error'] = $this->form_validation->error_array();
        } else {
            if (!empty($_FILES['upload_7']['name']) && ($_FILES['upload_7']['size'] > 0)) {
                if (!empty($this->doc_file)) {
                    //echo $this->doc_file;exit();
                    $word_file = FCPATH . $this->doc_file;
                    //echo $word_file;exit();
                    // Convert Word file To PDF
                    $pdf_file_path = $this->convert_word_to_PDF($word_file, $file_no_par);
                }
            } else {
                $path = $this->input->post('img_id_7');
                $pdf_file_path = $path[0];
                $this->doc_file = $this->input->post('img_id_7_doc');
            }
                

            //var_dump($this->doc_file);exit();

            // get the userdata from database using model
            $result = $this->Applicants_login_model->update_partnership_details($this->input->post(), $this->doc_file, $pdf_file_path);

            if ($result) {

                //Put the message data in a session  
                audit_action_log($this->session->userdata('user_id'), 'Partnership details', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                $this->session->set_flashdata('success', 'Partnership details updated successfully');
                //$json['success'] = "Partship details updated successfully.";
                $json['redirect'] = base_url() . "applicants_login/partnership_details_list";
            } else {
                $this->session->set_flashdata('error', 'Partship details updated not successfully');
                //$json['error'] = "Partship details updated not successfully.";
                $json['redirect'] = base_url() . "applicants_login/add_partnership_details";
            }
        }
        echo json_encode($json);
    }

    /*
     * Delete partnership change officer
     */

    public function delete() {

        //echo "ok";exit();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('applicants_login/login');
        }

        $id = $this->input->post('id');
        //echo $id;exit();
        if (!is_numeric($id) || !$this->Applicants_login_model->exists_par_change($id)) {
            $this->session->set_flashdata('error', 'Partnership Change user does not exists');
            redirect('applicants_login/index');
        }

        //echo $id;exit();
        if ($this->Applicants_login_model->delete($id)) {
            // echo "ok";
            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'Partnership Change', 'Delete', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata('success', 'Partnership Change officer deleted successfully');
            redirect('applicants_login/index');
        } else {
            $this->session->set_flashdata('error', 'Partnership Change officer not deleted');
            redirect('applicants_login/index');
        }
    }

    /*
     * Callback function for Dept. User word file upload
     */

    public function handle_gazette_doc_upload($file_no) {

        // echo "ok";exit();
        if ($file_no != '') {

            $file_type = $file_no;
        } else {

            $type = "X";

            $return = $this->db->select('file_no')
                            ->from('gz_change_of_partnership_master')
                            ->where('deleted', '0')
                            ->order_by('id', 'DESC')
                            ->limit(1)
                            ->get()->row();

            $final_file_no1 = "0001";


            if (!empty($return)) {
                $file_no = $return->file_no;
                $ex_file_no = explode('-', $file_no);
                $final_file_no = $ex_file_no[1] + 1;
                $len = strlen($final_file_no);
                if ($len == 1) {
                    $final_file_no1 = '000' . $final_file_no;
                } else if ($len == 2) {
                    $final_file_no1 = '00' . $final_file_no;
                } else if ($len == 3) {
                    $final_file_no1 = '0' . $final_file_no;
                } else if ($len == 4) {
                    $final_file_no1 = $final_file_no;
                }
            }

            $year = date("Y");

            $file_type = 'XP' . $type . '-' . $final_file_no1 . '-' . $year;
        }


        $this->doc_file = '';

        if (!empty($_FILES['upload_7']['name']) && ($_FILES['upload_7']['size'] > 0)) {
            //echo "ok";exit();

            $upload_dir = "./uploads/partnership_change/Notice_in_Softcopy_Doc/" . $file_type . "/";
            $upload_dir1 = "uploads/partnership_change/Notice_in_Softcopy_Doc/" . $file_type . "/";

            // check whether upload directory is writable
            if (!is_dir($upload_dir1) && !is_writable($upload_dir1)) {
                mkdir($upload_dir1, 0777, TRUE);
            }

            $config['upload_path'] = $upload_dir;
            $config['allowed_types'] = array('docx');
            $config['file_name'] = $_FILES['upload_7']['name'];
            $config['overwrite'] = true;
            $config['encrypt_name'] = TRUE;
            // 5 MB
            $config['max_size'] = '5242880';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            //echo "ok";exit();
            if (!$this->upload->do_upload('upload_7')) {

                $this->form_validation->set_message('handle_gazette_doc_upload', $this->upload->display_errors('', ''));
                return false;
            } else {

                $this->upload_data['file'] = $this->upload->data();
                $this->doc_file = $upload_dir1 . $this->upload_data['file']['file_name'];
                //echo $this->doc_file;exit();
                return true;
            }
        } else {

            $this->form_validation->set_message('handle_gazette_doc_upload', 'No file selected');
        }
    }

    /*
     * reject status from verifier C&T user 
     */

    public function update_reject_sta_ct_ver() {
        //echo "ok";exit();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('applicants_login/index');
        }
        // user ID
        $par_id = $this->security->xss_clean($this->input->post('par_sta_id'));
        $reject_remarks = $this->security->xss_clean($this->input->post('reject_remarks'));

        if ($this->Applicants_login_model->update_reject_sta_ct_ver($par_id, $reject_remarks)) {
            audit_action_log($this->session->userdata('user_id'), 'Reject verifier', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            return true;
        } else {
            return false;
        }
    }

    /*
     * reject status from reject C&T user 
     */

    public function update_reject_sta_ct_app() {
        //echo "ok";exit();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('applicants_login/index');
        }
        // user ID
        $par_id = $this->security->xss_clean($this->input->post('par_id'));

        $rej_app_remark = $this->security->xss_clean($this->input->post('rej_app_remark'));

        if ($this->Applicants_login_model->update_reject_sta_ct_app($par_id, $rej_app_remark)) {
            audit_action_log($this->session->userdata('user_id'), 'C & T User Approve', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            $this->session->set_flashdata('success', 'Change of partnership rejected');
            return true;
        } else {
            return false;
            $this->session->set_flashdata('error', 'Something went wrong');
        }
    }

    /*
     * reject status from C&T Approver user 
     */

    public function reject_ct_app() {
        // echo "ok";exit();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('applicants_login/index');
        }
        // user ID
        $par_id = $this->security->xss_clean($this->input->post('par_sta_id'));

        $rej_app_remark = $this->security->xss_clean($this->input->post('ct_reject_remarks'));

        if ($this->Applicants_login_model->update_reject_sta_ct_app($par_id, $rej_app_remark)) {
            audit_action_log($this->session->userdata('user_id'), 'C & T User Approve', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            $this->session->set_flashdata('success', 'Change of partnership rejected by C&T Approver');
            return true;
        } else {
            return false;
            $this->session->set_flashdata('error', 'Something went wrong');
        }
    }

    /*
     * return document to applicant
     */

    public function update_var_applicant_return() {

        //echo "ok";exit();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('applicants_login/index');
        }

        // user ID
        $par_id = $this->security->xss_clean($this->input->post('par_id'));
        $ret_applicant_rem = $this->security->xss_clean($this->input->post('ret_applicant_rem'));

        if ($this->Applicants_login_model->update_var_applicant_return($par_id, $ret_applicant_rem)) {
            audit_action_log($this->session->userdata('user_id'), 'C & T User Verifier return', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            
            
            $this->session->set_flashdata("success", "Change of partnership returned to applicant successfully");
            return true;
        } else {
            $this->session->set_flashdata("error", "Change of partnership not returned to applicant");
            return false;
        }
    }

    /*
     * verify document from C&T user
     */

    public function ver_docu_c_t_user() {

        //echo "ok";exit();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('applicants_login/index');
        }

        // user ID
        $par_id = $this->security->xss_clean($this->input->post('par_id'));
        $forward_remarks = $this->security->xss_clean($this->input->post('forward_remarks'));
        //echo $forward_remarks;exit();   
        if ($this->Applicants_login_model->ver_docu_c_t_user($par_id, $forward_remarks)) {

            audit_action_log($this->session->userdata('user_id'), 'C & T User Forward', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata("success", "Change of partnership forwarded successfully");
            return true;
        } else {
            // $this->session->set_flashdata("error", "Application not forwarded");
            return false;
        }
    }

    /*
     * reject status from verifier C&T user 
     */

    public function update_reject_sta_igr_ver() {
        // echo "ok";exit();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('applicants_login/index');
        }
        // user ID
        $par_id = $this->security->xss_clean($this->input->post('igr_par_id'));
        $reject_remarks = $this->security->xss_clean($this->input->post('reject_remarks'));

        if ($this->Applicants_login_model->update_reject_sta_igr_ver($par_id, $reject_remarks)) {
            audit_action_log($this->session->userdata('user_id'), 'IGR Approver reject', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            return true;
        } else {
            return false;
        }
    }

    /*
     * reject status from reject C&T user 
     */

    public function update_app_sta_igr_app() {
        //echo "ok";exit();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('applicants_login/index');
        }
        // user ID
        $par_id = $this->security->xss_clean($this->input->post('par_id'));
        $ret_app_rem = $this->security->xss_clean($this->input->post('ret_app_rem'));

        if ($this->Applicants_login_model->update_app_sta_igr_app($par_id, $ret_app_rem)) {
            audit_action_log($this->session->userdata('user_id'), 'IGR User Approve', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            return true;
        } else {
            return false;
        }
    }

    /*
     * reject status from reject C&T user 
     */

    public function return_igr_to_ct_ver() {
        //echo "ok";exit();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('applicants_login/index');
        }
        // user ID
        $par_id = $this->security->xss_clean($this->input->post('par_id'));
        $return_ct = $this->security->xss_clean($this->input->post('return_ct'));

        if ($this->Applicants_login_model->return_igr_to_ct_ver($par_id, $return_ct)) {
            audit_action_log($this->session->userdata('user_id'), 'IGR verifier return to CT verifier', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            return true;
        } else {
            return false;
        }
    }

    /*
     * reject status from reject C&T user 
     */

    public function return_igr_ver() {
        //echo "ok";exit();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('applicants_login/index');
        }
        // user ID
        $par_id = $this->security->xss_clean($this->input->post('par_id'));
        $forward_igr_app_rem = $this->security->xss_clean($this->input->post('forward_igr_app_rem'));

        if ($this->Applicants_login_model->return_igr_ver($par_id, $forward_igr_app_rem)) {

            audit_action_log($this->session->userdata('user_id'), 'forward igr verifier', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata('success', 'Change of partnership forwarded sucessfully');
            return true;

        } else {

            $this->session->set_flashdata('error', 'Something went wrong');
            return false;
        }
    }

    /*
    *Forward From IGR Approver to CT Verifier
     */

    public function forward_igr_approver() {
        
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('applicants_login/index');
        }
        // user ID
        $par_id = $this->security->xss_clean($this->input->post('par_id'));
        $forward_ct_vrifier_rem = $this->security->xss_clean($this->input->post('forward_ct_vrifier_rem'));
       
        if ($this->Applicants_login_model->forward_igr_approver($par_id, $forward_ct_vrifier_rem)) {
            audit_action_log($this->session->userdata('user_id'), 'Forward From IGR Approver to C&T Verifier', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata('success', 'Change of partnership forwarded sucessfully');
            return true;
        } else {
            $this->session->set_flashdata('error', 'Something went wrong');
            return false;
        }
    }

    /*
     * forward to publish
     */

    public function forward_to_pub_ins() {
        //echo "ok";exit();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('applicants_login/index');
        }
        // user ID
        $par_id = $this->security->xss_clean($this->input->post('par_id'));
        $forward_to_pub_rem = $this->security->xss_clean($this->input->post('forward_to_pub_rem'));

        if ($this->Applicants_login_model->forward_to_pub_ins($par_id, $forward_to_pub_rem)) {
            audit_action_log($this->session->userdata('user_id'), 'forward to publish', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            return true;
        } else {
            return false;
        }
    }

    public function partnership_change_list_govt() {
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
        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }
        $data['title'] = 'Change partnership list govt';
        $inputs = $this->input->post();

        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        } else{
            if($page == 0){
              $array_items = array('statusType', 'notice_date_form', 'notice_date_to', 'file_no');
              $this->session->unset_userdata($array_items);
              $inputs = array();
            } else {
              $inputs = $this->session->userdata();
            }
        }
        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "Applicants_login/partnership_change_list_govt";

        $config["total_rows"] = $this->Applicants_login_model->get_total_cnt_govt($inputs);

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

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();
        $data["status_list"] = $this->Applicants_login_model->status_change_partnership_pending_list();
        $data["inputs"] = $inputs;
        $data['partners'] = $this->Applicants_login_model->get_total_cnt_govt_list_pending($config['per_page'], $offset,$inputs);
        
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('change_of_partnership/pending.php', $data);
        $this->load->view('template/footer.php');
    }

    public function paid_partnership_change_list_govt() {
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
        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }
        // $this->output->enable_profiler(TRUE);

        $data['title'] = 'Change partnership list govt';
        $inputs = $this->input->post();

        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        } else{
            if($page == 0){
              $array_items = array('statusType', 'notice_date_form', 'notice_date_to', 'file_no');
              $this->session->unset_userdata($array_items);
              $inputs = array();
            } else {
              $inputs = $this->session->userdata();
            }
        }
        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "Applicants_login/paid_partnership_change_list_govt";

        $config["total_rows"] = $this->Applicants_login_model->paid_get_total_cnt_govt($inputs);

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

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();
        $data["status_list"] = $this->Applicants_login_model->status_change_partnership_pending_list();
        $data["inputs"] = $inputs;
        $data['partner_pay'] = $this->Applicants_login_model->get_total_cnt_govt_list_payed($config['per_page'], $offset, $inputs);
        
        // echo "<pre>";
        // print_r($data['partner_pay']);
        // echo "</pre>";
        // exit;
        
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('change_of_partnership/paid.php', $data);
        $this->load->view('template/footer.php');
    }

    public function published_partnership_change_list_govt() {
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
        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }
        // $this->output->enable_profiler(TRUE);

        $data['title'] = 'Change partnership list govt';
        $inputs = $this->input->post();

        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        } else{
            if($page == 0){
              $array_items = array('statusType', 'notice_date_form', 'notice_date_to', 'file_no');
              $this->session->unset_userdata($array_items);
              $inputs = array();
            } else {
              $inputs = $this->session->userdata();
            }
        }
        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "Applicants_login/published_partnership_change_list_govt";

        $config["total_rows"] = $this->Applicants_login_model->published_get_total_cnt_govt($inputs);
        
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

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();
        // $data["status_list"] = $this->Applicants_login_model->status_change_partnership_pending_list();
        $data["inputs"] = $inputs;
        $data['partner_publish'] = $this->Applicants_login_model->get_total_cnt_govt_list_publish($config['per_page'], $offset, $inputs);
        
        // echo "<pre>";
        // print_r($inputs);
        // echo "</pre>";
        // exit;
        
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('change_of_partnership/published.php', $data);
        $this->load->view('template/footer.php');
    }

    /**
     * Filteration for Change of Partnership Govt Press
     */

    public function search_partnership_change_list_govt() {
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
        $data['title'] = 'Search of change partnership list govt';

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "Applicants_login/partnership_change_list_govt";

        $searchValue = array(
            'app_name' => trim($this->input->post('name')),
            'file_no' => trim($this->input->post('file_no')),
            'status' => trim($this->input->post('status')),
            'fdate' => trim($this->input->post('notice_date_form')),
            'tdate' => trim($this->input->post('notice_date_to')),
        );
        
        $inputs = $this->input->post();
		$page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        }
        else{
            if($page==0){
              $array_items = array('app_name', 'file_no', 'status', 'notice_date_form', 'notice_date_to');
              $this->session->unset_userdata($array_items);
              $inputs =array();
            }else{
              $inputs = $this->session->userdata();
            }
        }


        //$config["total_rows"] = $this->Applicants_login_model->get_total_cnt_govt();

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

        $data['partners'] = $this->Applicants_login_model->get_total_cnt_govt_list_pending_serach($config["per_page"], $offset, $inputs);
        $data['partner_pay'] = $this->Applicants_login_model->get_total_cnt_govt_list_payed_search($config["per_page"], $offset, $inputs);
        $data['partner_publish'] = $this->Applicants_login_model->get_total_cnt_govt_list_publish_search($config["per_page"], $offset, $inputs);

        //print_r($data['partner_publish']);exit();
        // print_r($data['partners']);exit();
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('change_of_partnership/index.php', $data);
        $this->load->view('template/footer.php');
    }

    public function View_details_par_gove($id) {

        $data['title'] = 'Partnership View details';

        $data['gz_dets'] = $this->Applicants_login_model->View_details_par($id);

        $data['tot_docus'] = $this->Applicants_login_model->get_total_tot_docu();
        $data['amt_per_page'] = $this->Applicants_login_model->amt_per_page();

        $data['signed_name'] = $this->session->userdata('name');
        $data['designation'] = $this->session->userdata('designation');


        $data['details'] = $this->Applicants_login_model->select_cur_path_load($id);

        //print_r($data['details']);exit();

        $data['par_id'] = $id;
        $data['id'] = $id;
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('change_of_partnership/change_par_details.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * reject status from reject C&T user 
     */

    public function forward_to_pay() {
        //echo "ok";exit();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('applicants_login/index');
        }
        // user ID
        $par_id = $this->security->xss_clean($this->input->post('par_id'));


        if ($this->Applicants_login_model->forward_to_pay($par_id)) {
            audit_action_log($this->session->userdata('user_id'), 'govt press forward to pay', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            $this->session->set_flashdata('success', 'Forwarded to applicant for payment');
            redirect('applicants_login/partnership_change_list_govt');
        } else {
            $this->session->set_flashdata('error', 'Not forwarded to applicant for payment');
            redirect('applicants_login/View_details_par_gove/' . $par_id);
        }
    }

    public function change_partnership_details() {
        $user_sid = $this->session->userdata('user_id');
        echo 'Session ID: ' . $user_sid . '<br>';
        
        // Set a same-site cookie for first-party contexts
        // header('Set-Cookie: cookie1=value1; SameSite=Lax', false);
        // // Set a cross-site cookie for third-party contexts
        // header('Set-Cookie: cookie2=value2; SameSite=None; Secure', false);
        header('Set-Cookie: ' . session_name() . '=' . session_id() . '; SameSite=None; Secure', false);
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            // Binary File
            $binary_file_path = './binary_key/EGZ_binary_UAT.key';

            $handle = fopen($binary_file_path, "rb");
            $secret_key = fread($handle, filesize($binary_file_path));
            // Get the message string in Response from IFMS
            $message = $this->decrypt($this->input->post('msg'), $secret_key);
            // explode the data string separated by |
            $data_array = explode("|", $message);

            // assign variables
            $dept_ref_no = $data_array[1];
            $total_amnt = $data_array[20];
            $data = explode('!~!', $data_array[29]);

            $chln_ref_no = $data_array[36];
            $pay_mode = $data_array[37];
            $bnk_name = $data_array[38];
            $bnk_trans_id = $data_array[39];
            $bnk_trans_stat = $data_array[40];
            $bnk_trans_msg = $data_array[41];
            $bnk_trans_time = $data_array[42];

            // INSERT INTO the main Table
            $insert_array = array(
                'par_id' => $data[0],
                'file_number' => $data[1],
                'dept_ref_id' => $dept_ref_no,
                'challan_ref_id' => $chln_ref_no,
                'amount' => $total_amnt,
                'pay_mode' => $pay_mode,
                'bank_trans_id' => $bnk_trans_id,
                'bank_name' => $bnk_name,
                'bank_trans_msg' => $bnk_trans_msg,
                'bank_trans_time' => $bnk_trans_time,
                'trans_status' => $bnk_trans_stat,
                'created_at' => date('Y-m-d H:i:s', time()),
                'user_id' => $user_sid
            );

            // echo "<pre>";
            // print_r($insert_array);
            // exit;

            $result = $this->Applicants_login_model->change_partnership_details($insert_array);

            if ($result && $bnk_trans_stat == 'S') {
                //audit_action_log($this->session->userdata('user_id'), 'Make Payment', 'Edit', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                $this->session->set_flashdata("success", "Payment status updated successfully");
                redirect("applicants_login/partnership_details_list");
            } else if ($result && $bnk_trans_stat == 'F') {
                $this->session->set_flashdata('error', 'Payment Failed');
                redirect('applicants_login/partnership_details_list');
            } else if ($result && $bnk_trans_stat == 'P') {
                $this->session->set_flashdata('error', 'Payment Pending');
                redirect('applicants_login/partnership_details_list');
            } else if ($result && $bnk_trans_stat == 'I') {
                $this->session->set_flashdata('error', 'Payment Initiated');
                redirect('applicants_login/partnership_details_list');
            } else if ($result && $bnk_trans_stat == 'X') {
                $this->session->set_flashdata('error', 'Transaction cancelled by the Applicant');
                redirect('applicants_login/partnership_details_list');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong');
                redirect('applicants_login/partnership_details_list');
            }
        } else {
            $this->session->set_flashdata("error", "Payment status not updated");
            redirect("applicants_login/partnership_details_list");
        }
    }

    /*
     * sign by govt press
     */

    public function govt_press_sign() {
        //echo "ok";exit();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('applicants_login/index');
        }
        // user ID
        $par_id = $this->security->xss_clean($this->input->post('par_id'));
        //echo $par_id

        if ($this->Applicants_login_model->govt_press_sign($par_id)) {
            audit_action_log($this->session->userdata('user_id'), 'Govt Press Sign', 'Change Status', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            return true;
        } else {
            return false;
        }
    }

    /*
     * govt publish pdf
     */

    public function govt_publish() {
        //echo "ok";exit();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('applicants_login/index');
        }
        // user ID
        $par_id = $this->security->xss_clean($this->input->post('par_id'));
        //echo $par_id

        if ($this->Applicants_login_model->govt_publish($par_id)) {
            audit_action_log($this->session->userdata('user_id'), 'Govt Press Sign', 'Change Status', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            $this->session->set_flashdata('success', 'Govt. Press published the gazette successfully');
            return true;
        } else {
            return false;
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
        $signed_pdf_path = './uploads/partnership_change/press_signed_pdf/' . $pdf_file_name;

        $data = array(
            'gazette_id' => $gazette_id,
            'press_signed_pdf_path' => $signed_pdf_path,
            'press_signed_pdf_file_size' => 0
        );

        $result = $this->Applicants_login_model->update_press_signed_pdf_path($data);

        if ($result) {

            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'Extraordinary Gazette', 'Press Signed', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata('success', 'Document signed successfully');
            redirect('applicants_login/view_details_par_gove/' . $gazette_id);
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
                redirect('user/login');
            }
            $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
            redirect('user/login');
        }

        // $this->form_validation->set_rules('gazette_id', 'Gazette ID', 'trim|required');
        // $this->form_validation->set_rules('sl_no', 'Sl No', 'trim|required');
        $this->form_validation->set_rules('issue_date', 'Issue Date', 'trim|required');
        $this->form_validation->set_rules('saka_month', 'Shakabda Month', 'trim|required');
        $this->form_validation->set_rules('saka_date', 'Shakabda Date', 'trim|required');
        $this->form_validation->set_rules('saka_year', 'Shakabda Year', 'trim|required');

        if ($this->form_validation->run() == false) {
            //$this->load->view('gazette/press_add', $data);
        } else {

            $par_id = $this->input->post('gazette_id');
            //echo $par_id;
            $details = $this->Applicants_login_model->select_cur_path_load($par_id);
            //print_r($details);exit();
            // $str = explode('egazette', $details->notice_of_softcopy);
            /// echo ;exit();
            //$word_file = str_replace('\\', '/', ) . $str[1];
            $word_file = $details->notice_of_softcopy;

            $template_file = FCPATH . './uploads/sample/cos_cop_extraordinary_sample.docx';
            
            $this->load->library('phpqrcode/qrlib');
            $qr_text = "Gazette Number:" . $this->input->post('sl_no') . " " . "Change of Partnership" . " " . "Published Date:" . $this->input->post('issue_date');
            
            $folder = 'uploads/qrcodes/';
            $file = $par_id . "_" . md5(time()) . ".jpeg";
            $file_name = $folder . $file;

            QRcode::png($qr_text, $file_name);
            
            $dynamic_data = array(
                'gazette_id' => $par_id,
                'sl_no' => $this->input->post('sl_no'),
                'issue_date' => $this->input->post('issue_date'),
                'sakabda_date' => $this->input->post('saka_month').', '.$this->input->post('saka_date').', '.$this->input->post('saka_year'),
                'qr_code' => base_url().$file_name
            );
            
            
            // Generate Press PDF with updated values
            $press_pdf_file = $this->convert_press_word_to_PDF_cop($template_file, $word_file, $dynamic_data, $par_id);

            //echo $press_pdf_file;exit();
            // UPDATE press PDF in documents table
            $this->db->where('gz_mas_type_id', $par_id);
            $this->db->update('gz_change_of_partnetship_doument_det', array('press_pdf' => $press_pdf_file));

            $status = array(
                'gz_mas_type_id' => $par_id,
                'user_id' => $this->session->userdata('user_id'),
                'par_status' => 7,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1,
                'deleted' => 0
            );

            $this->db->insert('gz_change_of_par_status_his', $status);



            $array_data = array(
                'cur_status' => 7,
                'sl_no' => $this->input->post('sl_no'),
            );
            $this->db->where('id', $par_id);
            $result = $this->db->update('gz_change_of_partnership_master', $array_data);

            if ($result) {
                $this->session->set_flashdata('success', 'Docket added to notice successfully');
                redirect('applicants_login/view_details_par_gove/' . $par_id);
            } else {
                //Put the array in a session            
                $this->session->set_flashdata('error', 'Something went wrong');
                redirect('applicants_login/partnership_change_list_govt');
            }
        }
    }

    /*
     * Add for Govt. Press User/Admin
     */

    public function press_add($gazette_id) {

        if (!$this->session->userdata('logged_in')) {
            redirect('user/login');
        }


        $data['title'] = 'Edit Grazette';
        $data['details'] = $this->Applicants_login_model->select_cur_path_load($gazette_id);
        //print_r($data['details']);exit();
        $data['gazette_id'] = $gazette_id;


        $data['sl_no'] = $this->Applicants_login_model->get_sl_no();


        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('applicants_login/press_add_cop.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Convert Word file to PDF
     */

    public function convert_press_word_to_PDF_cop($template_file, $word_file, $data, $par_id) {

        $details = $this->Applicants_login_model->select_cur_path_load($par_id);

        $file_type = $this->Applicants_login_model->get_file_type($par_id);

        //print_r($file_type);exit();
        //print_r($str1);exit();
        $upload_dir = './uploads/partnership_change/press_signed_word/' . $file_type->file_no;
        if (!is_dir($upload_dir) && !is_writable($upload_dir)) {
            mkdir($upload_dir, 0777, TRUE);
        }
        //echo $str[1];exit();
        // store in database
        $press_word_db_path = './uploads/partnership_change/press_signed_word/' . $file_type->file_no . '/' . time() . '.docx';

        $press_word_path = FCPATH . $press_word_db_path;

        // Merge 2 documents
        $dm = new DocxMerge();
        $dm->merge([
            $template_file,
            $word_file
                ], $press_word_path);

        // load from template processor
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($press_word_path);
        // set dynamic values provided by Govt. Press
        $templateProcessor->setValue('sl_no', $data['sl_no']);
        $templateProcessor->setValue('issue_date', strtoupper($data['issue_date']));
        $templateProcessor->setValue('sakabda_date', $data['sakabda_date']);
        $templateProcessor->setImageValue('qr_code', array('path' => $data['qr_code'], 'width' => 50, 'height' => 50, 'ratio' => TRUE));


        $templateProcessor->saveAs($press_word_path);

        // UPDATE into documents table
        $this->db->where('gz_mas_type_id', $par_id);
        $this->db->update('gz_change_of_partnetship_doument_det', array('press_word' => $press_word_db_path));

        // Convert to PDF using MS Word using PHP COM object
        $word = new COM("word.application") or die("Could not initialise MS Word object.");
        $word->Documents->Open($press_word_path);

        $upload_dir1 = './uploads/partnership_change/press_pdf/' . $file_type->file_no;
        if (!is_dir($upload_dir1) && !is_writable($upload_dir1)) {
            mkdir($upload_dir1, 0777, TRUE);
        }
        $pdf_file_db_path = './uploads/partnership_change/press_pdf/' . $file_type->file_no . '/' . time() . '.pdf';
        $pdf_file_path = FCPATH . $pdf_file_db_path;

        $word->ActiveDocument->ExportAsFixedFormat($pdf_file_path, 17, false, 0, 0, 0, 0, 7, true, true, 2, true, true, false);

        $word->Quit();
        $word = null;
        //echo $pdf_file_db_path;exit();
        return $pdf_file_db_path;
    }
    
    /*
     * Encrypt the binary key - IFMS
     */
    public function encrypt($data = '', $key = NULL) {
        if($key != NULL && $data != ""){
                $method = "AES-256-ECB";
                $encrypted = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA);
                $result = base64_encode($encrypted);
                return $result;
        }else{
                return "String to encrypt, Key is required.";
        }
    }

    /*
     * Decrypt function to be used for IFMS Integration
     */

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

    /*
     * 	Function to generate OTP 
     */

     // ORIGINAL function
    // public function generate_otp($n) {
    //     // all numeric digits 
    //     $generator = "1357902468";
    //     // Iterate for n-times and pick a single character 
    //     // from generator and append it to $result
    //     $result = "";
    //     for ($i = 1; $i <= $n; $i++) {
    //         $result .= substr($generator, (rand() % (strlen($generator))), 1);
    //     }
    //     // Return result
    //     return $result;
    // }

    // NEW DEMO FUNCTION
    public function generate_otp($n) {
        // Validate input: ensure $n is a positive integer
        if (!is_int($n) || $n <= 0) {
            throw new InvalidArgumentException("Length of OTP must be a positive integer.");
        }
    
        // Generate OTP using random_bytes for better randomness
        $generator = "1357902468";
        $result = "";
        $max = strlen($generator) - 1;
        
        for ($i = 0; $i < $n; $i++) {
            $result .= $generator[random_int(0, $max)];
        }
        
        return $result;
    }
    
    /*
        -> Check the Transation Status using department_ref_id
        -> Here we create a new function to fetch the transaction details using department_ref_id
        -> We just show the Transaction details.
    */

    public function fetch_transaction_details() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Dynamic Data
            $transaction_type = $this->input->post('transaction_type');
            $dept_ref_id = $this->input->post('dept_ref_id');
            
            // Static Data for testing
            // $dept_ref_id = '170894983365dc8149a7212';
            // $transaction_type = 'COS';
        
            if ($transaction_type === 'COS') {
                
                $results = $this->db->select('*')->from('gz_dep_data_table')
                                    ->where('dep_ref_id', $dept_ref_id)
                                    ->limit(1)
                                    ->get();
                
                if ($results->num_rows() > 0) {
                    $query_row = $results->row();
                    
                    $dept_code = 'EGZ';
                    $dept_ref_no = $query_row->dept_ref_id;
                    // $tot_amnt = $query_row->amount . ".00";
        
                    $msg_format = $dept_code . "|" . $dept_ref_no . "|" . '1.00';
                    $binary_file_path = './binary_key/EGZ_binary.key';
                    $handle = fopen($binary_file_path, "rb");
                    $secret_key = fread($handle, filesize($binary_file_path));
                    $checksum = hash_hmac('sha256', $msg_format, $secret_key, true);
                    $chksum_msg = $msg_format . "|" . base64_encode($checksum);
                    $encrypted_msg = $this->encrypt($chksum_msg, $secret_key);
        
                    $post = [
                        'deptCode' => trim($dept_code),
                        'msg' => trim($encrypted_msg)
                    ];
                    // print_r($post);exit;
                    $url = "https://www.(StateName)treasury.gov.in/echallanservices/depts2sresponse";
                    $ch = curl_init();
                    $timeout = 0;
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
                   echo $rawdata = curl_exec($ch);exit;
                    curl_close($ch);
        
                    $message = $this->decrypt($rawdata, $secret_key);
                    $data_array = explode("|", $message);
                    // print_r($data_array);                   
                } else {
                    // No data found
                    $error_message = "No data found for the provided Department Reference ID";
                    $data['error_message'] = $error_message;
                }
            } elseif ($transaction_type === 'COP') {
                $results = $this->db->select('*')->from('gz_dep_data_table')
                                                ->where('dep_ref_id', $dept_ref_id)
                                                ->limit(1)
                                                ->get();

                // Check if the query returned any rows
                if ($results->num_rows() > 0) {
                    // Retrieve the first row
                    $qry_row = $results->row();

                    // Set department code
                    $dept_code = "EGZ";

                    // Retrieve department reference ID and total amount from the query result
                    $dept_ref_no = $qry_row->dept_ref_id;
                    $tot_amnt = $qry_row->amount . ".00";

                    // Construct the message format
                    $msg_format = $dept_code . "|" . $dept_ref_no . "|" . $tot_amnt;

                    // Load the binary key file
                    $binary_file_path = './binary_key/EGZ_binary.key';
                    $handle = fopen($binary_file_path, "rb");
                    $secret_key = fread($handle, filesize($binary_file_path));

                    // Calculate checksum
                    $checksum = hash_hmac('sha256', $msg_format, $secret_key, true);

                    // Construct the checksum message
                    $chksum_msg = $msg_format . "|" . base64_encode($checksum);

                    // Encrypt the message
                    $encrypted_msg = $this->encrypt($chksum_msg, $secret_key);

                    // Prepare post data
                    $post = [
                    'deptCode' => trim($dept_code),
                    'msg' => trim($encrypted_msg)
                    ];

                    // URL to get the status
                    $url = "https://www.(StateName)treasury.gov.in/echallanservices/depts2sresponse";

                    // Initialize cURL session
                    $ch = curl_init();
                    $timeout = 0;

                    // Set cURL options
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

                    // Execute cURL request and retrieve raw data
                    $rawdata = curl_exec($ch);

                    // Close cURL session
                    curl_close($ch);

                    // Decrypt the raw data
                    $message = $this->decrypt($rawdata, $secret_key);

                    // Explode the decrypted message into an array
                    $data_array = explode("|", $message);
                    
                } else {
                    // No data found
                    $error_message = "No data found for the provided Department Reference ID";
                    $data['error_message'] = $error_message;
                }

            } elseif ($transaction_type === 'COG') {
                
                $results = $this->db->select('*')->from('gz_dep_data_table')
                                    ->where('dep_ref_id', $dept_ref_id)
                                    ->limit(1)
                                    ->get();
                
                if ($results->num_rows() > 0) {
                    $query_row = $results->row();
                    
                    $dept_code = 'EGZ';
                    $dept_ref_no = $query_row->dept_ref_id;
                    // $tot_amnt = $query_row->amount . ".00";
        
                    $msg_format = $dept_code . "|" . $dept_ref_no . "|" . '1.00';
                    $binary_file_path = './binary_key/EGZ_binary.key';
                    $handle = fopen($binary_file_path, "rb");
                    $secret_key = fread($handle, filesize($binary_file_path));
                    $checksum = hash_hmac('sha256', $msg_format, $secret_key, true);
                    $chksum_msg = $msg_format . "|" . base64_encode($checksum);
                    $encrypted_msg = $this->encrypt($chksum_msg, $secret_key);
        
                    $post = [
                        'deptCode' => trim($dept_code),
                        'msg' => trim($encrypted_msg)
                    ];
                    // print_r($post);exit;
                    $url = "https://www.(StateName)treasury.gov.in/echallanservices/depts2sresponse";
                    $ch = curl_init();
                    $timeout = 0;
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
                   echo $rawdata = curl_exec($ch);exit;
                    curl_close($ch);
        
                    $message = $this->decrypt($rawdata, $secret_key);
                    $data_array = explode("|", $message);
                    // print_r($data_array);

                   
                } else {
                    // No data found
                    $error_message = "No data found for the provided Department Reference ID";
                    $data['error_message'] = $error_message;
                }
            }

            // Check if the Department Reference ID is incorrect
            if (!isset($query_row)) {
                $error_message = "Invalid Department Reference ID. Please provide a valid ID.";
                $data['error_message'] = $error_message;
            } else if (isset($query_row)) {
                $success_message = "Your Transaction Successfully fetched!";
                $data['success_message'] = $success_message;
            }
            
            // loading the view
            $this->load->view('template/header_applicant.php', $data);
            $this->load->view('template/sidebar_applicant.php');
            $data['data_array'] = $data_array; 
            $this->load->view('check_status/check_transaction_status.php', $data); 
            $this->load->view('template/footer_applicant.php');
        }
        
    }
    

    public function fetch_cos_ifms_transaction_status() {
        $results = $this->db->select('*')->from('gz_change_of_name_surname_payment_details');
                                        // ->limit(1)
                                        // ->get();
        if ( $results) {
            // $query_row = $results->row();

            // Department Code. -> It must be static
            $dept_code = 'EGZ';

            // Department Reference Number. -> This need to be dynamic
            // $dept_ref_no = $qry_row->dept_ref_id;
            $dept_ref_no = '17136830386624ba5e3563f';

            // Total Amount
            // $tot_amnt = $qry_row->amount . ".00";
           $tot_amnt = "529.00";

            // Message Format
            $msg_format = $dept_code . "|" . $dept_ref_no . "|" . $tot_amnt;

            // Binary File
            $binary_file_path = './binary_key/EGZ_binary.key';
            $handle = fopen($binary_file_path, "rb");
            $secret_key = fread($handle, filesize($binary_file_path));

            // Checksum
            $checksum = hash_hmac('sha256', $msg_format, $secret_key, true);
           
            // Encrypted Message
            $chksum_msg = $msg_format . "|" . base64_encode($checksum);
          
            //OPENSSL_PKCS5_PADDING
            $encrypted_msg = $this->encrypt($chksum_msg, $secret_key);
           
            // post format data
            $post = [
                'deptCode' => trim($dept_code),
                'msg' => trim($encrypted_msg)
            ];

            // URL to get the status
            $url = "https://www.(StateName)treasury.gov.in/echallanservices/depts2sresponse";
            $ch = curl_init();
            $timeout = 0;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
            $rawdata = curl_exec($ch);
          
            //show information regarding the request
            // print_r(curl_getinfo($ch));
            // echo curl_errno($ch) . '-' . curl_error($ch);
            // echo curl_errno($ch) . '-' . curl_error($ch);
            curl_close($ch);
            // echo "response:".$rawdata."<br>".$message;
            // exit;
            $message = $this->decrypt($rawdata, $secret_key);
            // echo "<br>Decrypted Message: ".$message .'<br>';
            // explode the data string separated by |
            $data_array = explode("|", $message);
            print_r($data_array);exit;
        }

    }
    
    /*
     * Check for the Transaction status of the transactions containing trans_status = P
     * Change of Surname Double Verification
     */
    public function check_cos_ifms_transaction_status() {
        // Get the Transaction status = P
        $results = $this->db->select('*')->from('gz_change_of_name_surname_payment_details')
                            ->where('trans_status', 'P')
                            ->limit(1)
                            ->get();
                            
        // Check if the trans_status == P
        if ($results->num_rows() > 0) {
          
            $qry_row = $results->row();
            
            // Dept. Code
            $dept_code = "EGZ";
            // Dept. Refefence No
            // $dept_ref_no = $qry_row->dept_ref_id;
            $dept_ref_no = '170746824265c5e5d2b8b5e';
            // Total Amount
            // $tot_amnt = $qry_row->amount . ".00";
            $tot_amnt = "1.00";
            // Message Format
            $msg_format = $dept_code . "|" . $dept_ref_no . "|" . $tot_amnt;

            // Binary File
            $binary_file_path = './binary_key/EGZ_binary.key';
            $handle = fopen($binary_file_path, "rb");
            $secret_key = fread($handle, filesize($binary_file_path));
          
            // Checksum
            $checksum = hash_hmac('sha256', $msg_format, $secret_key, true);
           
            // Encrypted Message
            $chksum_msg = $msg_format . "|" . base64_encode($checksum);
          
            //OPENSSL_PKCS5_PADDING
            $encrypted_msg = $this->encrypt($chksum_msg, $secret_key);
           
            // post format data
            $post = [
                'deptCode' => trim($dept_code),
                'msg' => trim($encrypted_msg)
            ];
            // print_r($post);
            // URL to get the status
            $url = "https://www.(StateName)treasury.gov.in/echallanservices/depts2sresponse";
            $ch = curl_init();
            $timeout = 0;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
            $rawdata = curl_exec($ch);
          
            //show information regarding the request
            // print_r(curl_getinfo($ch));
            // echo curl_errno($ch) . '-' . curl_error($ch);
            // echo curl_errno($ch) . '-' . curl_error($ch);
            curl_close($ch);
            // echo "response:".$rawdata."<br>".$message;
            // exit;
            $message = $this->decrypt($rawdata, $secret_key);
            echo "<br>Decrypted Message: ".$message .'<br>';
            // explode the data string separated by |
            $data_array = explode("|", $message);
            print_r($data_array);exit;
            /*
                $dept_code = $data_array[0] . "<br/>";
                $dept_ref_no = $data_array[1] . "<br/>";
                $tot_amnt = $data_array[2] . "<br/>";
                $chln_ref_no = $data_array[3] . "<br/>";
                $bnk_trns_id = $data_array[4] . "<br/>";
                $bnk_trns_stat = $data_array[5] . "<br/>";
                $bnk_trns_msg = $data_array[6] . "<br/>";
                $bnk_trns_time = $data_array[7] . "<br/>";
                $chksum = $data_array[8];
            */
            // echo ($message); exit;
            // If transaction status == S then UPDATE the \DB 
            if ($data_array[5] == 'S') {
                
                try {

                    $this->db->trans_begin();

                    // UPDATE gz_change_of_name_surname_payment_details Table
                    $gz_pmnt_upd = array(
                        'challan_ref_id' => $data_array[3],
                        'bank_trans_id' => $data_array[4],
                        'trans_status' => $data_array[5],
                        'bank_trans_msg' => $data_array[6],
                        'bank_trans_time' => $data_array[7],
                        'modified_at' => date('Y-m-d H:i:s', time())
                    );

                    $this->db->where('id', $qry_row->id);
                    $this->db->where('dept_ref_id', $qry_row->dept_ref_id);
                    $this->db->update('gz_change_of_name_surname_payment_details', $gz_pmnt_upd);

                    // INSERT the payment status history (gz_payment_status_history) table
                    $ins_arr = array(
                        'payment_id' => $qry_row->id,
                        'payment_type' => 'COS',
                        'payment_status' => $data_array[5],
                        'created_at' => date('Y-m-d H:i:s', time())
                    );

                    $this->db->insert('gz_payment_status_history', $ins_arr);

                    if ($this->db->trans_status() == FALSE) {
                        $this->db->trans_rollback();
                    } else {
                        $this->db->trans_commit();
                    }
                } catch (Exception $ex) {

                }
            }
        }
    }
       
    /*
     * Check for the Transaction status of the transactions containing trans_status = P
     * Change of Partnership Double Verification
     */
    public function check_cop_ifms_transaction_status() {
        // echo 'test';
        // Get the Transaction status = P
        $results =  1; //$this->db->select('*')->from('gz_change_of_partnership_make_pay');
                            // ->where('bankTransactionStatus', 'P')->limit(1)->get();
        // Check if the trans_status == P
        if ($results == 1) {
            // $qry_row = $results->row();
            
            // Dept. Code
            $dept_code = "EGZ";
            // Dept. Refefence No
            // $dept_ref_no = $qry_row->deptRefId; // live data
            $dept_ref_no = '17136830386624ba5e3563f'; // static data
            // Total Amount
            // $tot_amnt = $qry_row->amount . ".00"; // live data
            $tot_amnt = '529.00'; // static data
            // Message Format
            $msg_format = $dept_code . "|" . $dept_ref_no . "|" . $tot_amnt;

            // Binary File
            $binary_file_path = './binary_key/EGZ_binary.key';
            $handle = fopen($binary_file_path, "rb");
            $secret_key = fread($handle, filesize($binary_file_path));

            // Checksum
            $checksum = hash_hmac('sha256', $msg_format, $secret_key, true);
            // Encrypted Message
            $chksum_msg = $msg_format . "|" . base64_encode($checksum);
            //OPENSSL_PKCS5_PADDING
            $encrypted_msg = $this->encrypt($chksum_msg, $secret_key);
            
            // post format data
            $post = [
		'deptCode' => trim($dept_code),
		'msg' => trim($encrypted_msg)
            ];
            
            // URL to get the status
            $url = "https://www.(StateName)treasury.gov.in/echallanservices/depts2sresponse";
            
            $ch = curl_init();
            $timeout = 0;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
            $rawdata = curl_exec($ch);

           
            //show information regarding the request
            //print_r(curl_getinfo($ch));
            //echo curl_errno($ch) . '-' . curl_error($ch);
            //echo curl_errno($ch) . '-' . curl_error($ch);
            curl_close($ch);

            $message = $this->decrypt($rawdata, $secret_key);
            // explode the data string separated by |
            
            $data_array = explode("|", $message);
            echo '<pre>';
            print_r($data_array);
            exit;
            
            /*
                $dept_code = $data_array[0] . "<br/>";
                $dept_ref_no = $data_array[1] . "<br/>";
                $tot_amnt = $data_array[2] . "<br/>";
                $chln_ref_no = $data_array[3] . "<br/>";
                $bnk_trns_id = $data_array[4] . "<br/>";
                $bnk_trns_stat = $data_array[5] . "<br/>";
                $bnk_trns_msg = $data_array[6] . "<br/>";
                $bnk_trns_time = $data_array[7] . "<br/>";
                $chksum = $data_array[8];
            */
            
            // If transaction status == S then UPDATE the \DB 
            if ($data_array[5] == 'S') {
                
                try {

                    $this->db->trans_begin();

                    // UPDATE gz_change_of_name_surname_payment_details Table
                    $gz_pmnt_upd = array(
                        'challanRefId' => $data_array[3],
                        'bank_trans_id' => $data_array[4],
                        'bankTransactionStatus' => $data_array[5],
                        'bank_trans_msg' => $data_array[6],
                        'bank_trans_time' => $data_array[7],
                        'modified_at' => date('Y-m-d H:i:s', time())
                    );

                    $this->db->where('id', $qry_row->id);
                    $this->db->where('deptRefId', $dept_ref_no);
                    $this->db->update('gz_change_of_partnership_make_pay', $gz_pmnt_upd);

                    // INSERT the payment status history (gz_payment_status_history) table
                    $ins_arr = array(
                        'payment_id' => $qry_row->id,
                        'payment_type' => 'COP',
                        'payment_status' => $data_array[5],
                        'created_at' => date('Y-m-d H:i:s', time())
                    );

                    $this->db->insert('gz_payment_status_history', $ins_arr);

                    if ($this->db->trans_status() == FALSE) {
                        $this->db->trans_rollback();
                    } else {
                        $this->db->trans_commit();
                    }

                } catch (Exception $ex) {

                }
            }
        }
    }
    
    public function set_read_applicant(){

        $id = $this->security->xss_clean($this->input->post('id'));

        $update_notification = array(
            'is_viewed' => 1,
        );

        $this->db->where('id', $id);
        $this->db->update('gz_notification_applicant', $update_notification);
            

        return true;
    }

    public function set_read_cnt(){
        $id = $this->security->xss_clean($this->input->post('id'));
        
        $update_notification = array(
            'is_viewed' => 1,
        );

        $this->db->where('id', $id);
        $this->db->update('gz_notification_ct', $update_notification);
            

        return true;
    }

    public function set_read_igr(){
        $id = $this->security->xss_clean($this->input->post('id'));
        
        $update_notification = array(
            'is_viewed' => 1,
        );

        $this->db->where('id', $id);
        $this->db->update('gz_notification_igr', $update_notification);
            

        return true;
    }
    public function set_read_admin(){
        $id = $this->security->xss_clean($this->input->post('id'));

        $update_notification = array(
            'is_read' => 1,
        );

        $this->db->where('id', $id);
        $this->db->update('gz_notification_govt', $update_notification);
            

        return true;
    }
    public function set_read_partnership_admin(){
        $id = $this->security->xss_clean($this->input->post('id'));

        $update_notification = array(
            'is_read' => 1,
        );

        $this->db->where('id', $id);
        $this->db->update('gz_notification_govt', $update_notification);
            

        return true;
    }

    // Gender Work Start    
    /*
    * Change of gender form
    */

    public function add_change_of_gender() {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('applicants_login/index');
        }

        $data['title'] = "Change of Gender Add";
        $data['gz_types'] = $this->Applicants_login_model->get_total_gz_types();
        $data['states'] = $this->Applicants_login_model->get_states();
        $data['tot_documents_gender'] = $this->Applicants_login_model->get_total_gender_documents();
        $data['count'] = count($data['tot_documents_gender']);

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/add_change_of_gender.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    /*
     * Insert Change of name/surname
     */

    //  public function insert_change_of_gender() {
    //     // echo 'test';exit;
    //     // $json = array('test1', 'test2');
    //     $json = array();

    //     $minor = $this->security->xss_clean($this->input->post('minor'));
    //     $name_change = $this->security->xss_clean($this->input->post('name_change'));

    //     if ( $minor == 0 && $name_change == 0 ) {
    //         $this->form_validation->set_rules('state_id', 'State', 'trim|required');
    //         $this->form_validation->set_rules('district_id', 'District', 'trim|required');
    //         $this->form_validation->set_rules('block_ulb_id', 'Block/ULB', 'trim|required');
    //         $this->form_validation->set_rules('address_1', 'Address ', 'trim|required|min_length[5]|max_length[200]');
    //         $this->form_validation->set_rules('govt_emp', 'Government Employee', 'trim|required');
    //         $this->form_validation->set_rules('minor', 'Minor', 'trim|required');
    //         $this->form_validation->set_rules('docu_3', 'Select Word/Docx File', 'callback_gazette_doc_upload_for_gender');

    //         $this->form_validation->set_rules('approver', 'Approver', 'trim|required');
    //         $this->form_validation->set_rules('place', 'Place', 'trim|required');
    //         $this->form_validation->set_rules('notice_date', 'Date', 'trim|required');
    //         $this->form_validation->set_rules('salutation', 'Salutation', 'trim|required');
    //         $this->form_validation->set_rules('name_for_notice', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('address', 'Address', 'trim|required');
    //         $this->form_validation->set_rules('old_gender', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('new_gender', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('new_gender_one', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('signature', 'Name', 'trim|required');

    //     } else if ( $minor == 1 && $name_change == 0 ) {
    //         $this->form_validation->set_rules('state_id', 'State', 'trim|required');
    //         $this->form_validation->set_rules('district_id', 'District', 'trim|required');
    //         $this->form_validation->set_rules('block_ulb_id', 'Block/ULB', 'trim|required');
    //         $this->form_validation->set_rules('address_1', 'Address ', 'trim|required|min_length[5]|max_length[200]');
    //         $this->form_validation->set_rules('govt_emp', 'Government Employee', 'trim|required');
    //         $this->form_validation->set_rules('minor', 'Minor', 'trim|required');
    //         $this->form_validation->set_rules('docu_3', 'Select Word/Docx File', 'callback_gazette_doc_upload_for_gender');
    //         //need to add before notice rules
    //         $this->form_validation->set_rules('approver_minor', 'Approver', 'trim|required');
    //         $this->form_validation->set_rules('place_minor', 'Place', 'trim|required');
    //         $this->form_validation->set_rules('notice_date_minor', 'Date', 'trim|required');
    //         $this->form_validation->set_rules('salutation_minor', 'Salutation', 'trim|required');
    //         $this->form_validation->set_rules('name_for_notice_minor', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('address_minor', 'Address', 'trim|required');
    //         $this->form_validation->set_rules('son_daughter', 'Relation', 'trim');
    //         $this->form_validation->set_rules('old_gender_minor', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('new_gender_minor', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('gender', 'Gender', 'trim');
    //         $this->form_validation->set_rules('gender_his_her', 'Gender', 'trim');

    //         $this->form_validation->set_rules('new_gender_one_minor', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('signature_minor', 'Name', 'trim|required');

    //     } else if ( $name_change == 1 && $minor == 0 ) {
    //         $this->form_validation->set_rules('state_id', 'State', 'trim|required');
    //         $this->form_validation->set_rules('district_id', 'District', 'trim|required');
    //         $this->form_validation->set_rules('block_ulb_id', 'Block/ULB', 'trim|required');
    //         $this->form_validation->set_rules('address_1', 'Address ', 'trim|required|min_length[5]|max_length[200]');
    //         $this->form_validation->set_rules('govt_emp', 'Government Employee', 'trim|required');
    //         $this->form_validation->set_rules('minor', 'Minor', 'trim|required');
    //         $this->form_validation->set_rules('docu_3', 'Select Word/Docx File', 'callback_gazette_doc_upload_for_gender');

    //         $this->form_validation->set_rules('name_gender_approver', 'Approver', 'trim|required');
    //         $this->form_validation->set_rules('name_gender_place', 'Place', 'trim|required');
    //         $this->form_validation->set_rules('name_gender_notice_date', 'Date', 'trim|required');
    //         $this->form_validation->set_rules('name_gender_salutation', 'Salutation', 'trim|required');
    //         $this->form_validation->set_rules('name_gender_notice', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('name_gender_address', 'Address', 'trim|required');

    //         $this->form_validation->set_rules('old_name_two', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('new_name_two', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('old_gender_two', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('new_gender_two', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('new_name_three', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('new_gender_three', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('name_gender_notice_signature', 'Name', 'trim|required');

    //     } else if ( $name_change == 1 && $minor == 1 ) {
    //         $this->form_validation->set_rules('state_id', 'State', 'trim|required');
    //         $this->form_validation->set_rules('district_id', 'District', 'trim|required');
    //         $this->form_validation->set_rules('block_ulb_id', 'Block/ULB', 'trim|required');
    //         $this->form_validation->set_rules('address_1', 'Address ', 'trim|required|min_length[5]|max_length[200]');
    //         $this->form_validation->set_rules('govt_emp', 'Government Employee', 'trim|required');
    //         $this->form_validation->set_rules('minor', 'Minor', 'trim|required');
    //         $this->form_validation->set_rules('docu_3', 'Select Word/Docx File', 'callback_gazette_doc_upload_for_gender');

    //         $this->form_validation->set_rules('name_gender_minor_yes_approver', 'Approver', 'trim|required');
    //         $this->form_validation->set_rules('name_gender_minor_yes_place', 'Place', 'trim|required');
    //         $this->form_validation->set_rules('name_gender_minor_yes_date', 'Date', 'trim|required');
    //         $this->form_validation->set_rules('name_gender_minor_yes_salutation', 'Salutation', 'trim|required');
    //         $this->form_validation->set_rules('name_gender_minor_yes_notice', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('name_gender_minor_yes_address', 'Address', 'trim|required');
    //         $this->form_validation->set_rules('name_gender_minor_yes_son_daughter', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('he_she_gender', 'Gender', 'trim|required');
    //         $this->form_validation->set_rules('his_her_gender', 'Gender', 'trim|required');

    //         $this->form_validation->set_rules('old_minor_gender_three', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('new_minor_gender_three', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('old_minor_name_three', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('new_minor_name_three', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('new_minor_name_four', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('new_minor_gender_four', 'Name', 'trim|required');
    //         $this->form_validation->set_rules('name_gender_minor_signature', 'Name', 'trim|required');

    //     }

    //     // check form validation
    //     if ($this->form_validation->run() == false) {
    //         $json['error'] = $this->form_validation->error_array();
    //     } else {
    //         // $json = array('test1', 'hello-test!');
    //         $return = $this->db->select('id')
    //                            ->from('gz_change_of_gender_master')
    //                            ->where('deleted', '0')
    //                            ->order_by('id', 'DESC')
    //                            ->limit(1)
    //                            ->get()->row();

    //         $final_file_no1 = '0001';
    //         if (!empty($return)) {
    //             $id = $return->id + 1;
    //             $code = (string) $id;
    //             $len = strlen($code);

    //             if ($len == 1) {
    //                 $final_file_no1 = '000' . $id;
    //             } else if ($len == 2) {
    //                 $final_file_no1 = '00' . $id;
    //             } else if ($len == 3) {
    //                 $final_file_no1 = '0' . $id;
    //             } else if ($len == 4) {
    //                 $final_file_no1 = $id;
    //             }
    //         }
    //         $year = date('Y');
    //         $file_no = 'XG-' . $final_file_no1 . '-' . $year;

    //         $press_word_db_path_raw = 'uploads/change_of_gender/notice_updated_doc_file/' . time() . '.docx';
    //         $path = str_replace('\\', '/', FCPATH);
    //         $new_path = base_url() . $press_word_db_path_raw;
    //         $press_word_db_path = $path . $press_word_db_path_raw;

    //         if ( $minor == 0 && $name_change == 0 ) {
    //             $template_file = FCPATH . 'uploads/sample/cog_adult_notice_sample.docx';

    //             $insert_array = array(
    //                 'approver' => $this->security->xss_clean($this->input->post('approver')),
    //                 'place' => $this->security->xss_clean($this->input->post('place')),
    //                 'notice_date' => $this->security->xss_clean($this->input->post('notice_date')),
    //                 //'notice_date_minor' => $this->security->xss_clean($this->input->post('notice_date_minor')),
    //                 'salutation' => $this->security->xss_clean($this->input->post('salutation')),
    //                 'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice')),
    //                 // 'son_daughter' => $this->security->xss_clean($this->input->post('son_daughter')),
    //                 // 'gender' => $this->security->xss_clean($this->input->post('gender')),
    //                 'address' => $this->security->xss_clean($this->input->post('address')),
    //                 'old_gender' => $this->security->xss_clean($this->input->post('old_gender')),
    //                 'new_gender' => $this->security->xss_clean($this->input->post('new_gender')),
    //                 'new_gender_one' => $this->security->xss_clean($this->input->post('new_gender_one')),
    //                 'signature' => $this->security->xss_clean($this->input->post('signature')),
    //                 'file_no' => $file_no,
    //                 'press_word_db_path' => $new_path,
    //                 // 'pdf_path' => $this->doc_file_for_name_surname_pdf,
    //             );
    //         } else if ( $minor == 1 && $name_change == 0 ) {
    //             $template_file = FCPATH . "uploads/sample/cog_minor_notice_sample.docx";
    //             // ms word file updated.....
    //             $insert_array = array( 
    //                 'approver' => $this->security->xss_clean($this->input->post('approver_minor')),
    //                 'place' => $this->security->xss_clean($this->input->post('place_minor')),
    //                 'notice_date' => $this->security->xss_clean($this->input->post('notice_date_minor')),
    //                 'salutation' => $this->security->xss_clean($this->input->post('salutation_minor')),
    //                 'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice_minor')),
    //                 'address' => $this->security->xss_clean($this->input->post('address_minor')),
    //                 'son_daughter' => $this->security->xss_clean($this->input->post('son_daughter')),
    //                 'gender' => $this->security->xss_clean($this->input->post('gender')),
    //                 'gender_his_her' => $this->security->xss_clean($this->input->post('gender_his_her')),

    //                 'old_gender' => $this->security->xss_clean($this->input->post('old_gender_minor')),
    //                 'new_gender' => $this->security->xss_clean($this->input->post('new_gender_minor')),
    //                 'new_gender_one' => $this->security->xss_clean($this->input->post('new_gender_one_minor')),
    //                 'signature' => $this->security->xss_clean($this->input->post('signature_minor')),
    //                 'file_no' => $file_no,
    //                 'press_word_db_path' => $new_path,
    //                 // 'pdf_path' => $this->doc_file_for_name_surname_pdf,
    //             );
    //         } 
    //         else if ( $name_change == 1 && $minor == 0 ) { 
    //             $template_file = FCPATH . "uploads/sample/cog_adult_name_gender_change_notice_sample.docx";
    //             //  ms word file updated
    //             $insert_array = array(
    //                 'approver' => $this->security->xss_clean($this->input->post('name_gender_approver')),
    //                 'place' => $this->security->xss_clean($this->input->post('name_gender_place')),
    //                 'notice_date' => $this->security->xss_clean($this->input->post('name_gender_notice_date')),
    //                 'salutation' => $this->security->xss_clean($this->input->post('name_gender_salutation')),
    //                 'name_for_notice' => $this->security->xss_clean($this->input->post('name_gender_notice')),
    //                 'address' => $this->security->xss_clean($this->input->post('name_gender_address')),
                    
    //                 'old_name_two' => $this->security->xss_clean($this->input->post('old_name_two')),
    //                 'new_name_two' => $this->security->xss_clean($this->input->post('new_name_two')),
    //                 'old_gender_two' => $this->security->xss_clean($this->input->post('old_gender_two')),
    //                 'new_gender_two' => $this->security->xss_clean($this->input->post('new_gender_two')),
    //                 'new_name_three' => $this->security->xss_clean($this->input->post('new_name_three')),
    //                 'new_gender_three' => $this->security->xss_clean($this->input->post('new_gender_three')),
    //                 'name_gender_notice_signature' => $this->security->xss_clean($this->input->post('name_gender_notice_signature')),
    //             );
    //         } 
    //         else if ( $name_change == 1 && $minor == 1 ) {
    //             $template_file = FCPATH . "uploads/sample/cog_minor_name_gender_change_notice_sample.docx";
    //             // Word file edit complete of this logic
    //             $insert_array = array(
    //                 'approver' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_approver')),
    //                 'place' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_place')),
    //                 'notice_date' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_date')),
    //                 'salutation' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_salutation')),
    //                 'name_for_notice' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_notice')),
    //                 'address' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_address')),
                    
    //                 'son_daughter' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_son_daughter')),
    //                 'he_she_gender' => $this->security->xss_clean($this->input->post('he_she_gender')),
    //                 'his_her_gender' => $this->security->xss_clean($this->input->post('his_her_gender')),

    //                 'old_minor_gender_three' => $this->security->xss_clean($this->input->post('old_minor_gender_three')),
    //                 'new_minor_gender_three' => $this->security->xss_clean($this->input->post('new_minor_gender_three')),
    //                 'old_minor_name_three' => $this->security->xss_clean($this->input->post('old_minor_name_three')),
    //                 'new_minor_name_three' => $this->security->xss_clean($this->input->post('new_minor_name_three')),
    //                 'new_minor_name_four' => $this->security->xss_clean($this->input->post('new_minor_name_four')),
    //                 'new_minor_gender_four' => $this->security->xss_clean($this->input->post('new_minor_gender_four')),
    //                 'name_gender_minor_signature' => $this->security->xss_clean($this->input->post('name_gender_minor_signature')),
                    
    //             );
    //         }

    //         $timestamp = strtotime($insert_array['notice_date']);

    //         // Creating new date format from that timestamp
    //         $new_date = date('jS F Y', $timestamp);

    //         // load from template processor
    //         $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($template_file);

    //         // var_dump($insert_array);exit();

    //         // set dynamic values provided by Govt. Press
    //         if ( $minor == 0 && $name_change == 0 ) {
    //             $templateProcessor->setValue('approver', $insert_array['approver']);
    //             $templateProcessor->setValue('place', $insert_array['place']);
    //             $templateProcessor->setValue('notice_date', $new_date);
    //             $templateProcessor->setValue('salutation', $insert_array['salutation']);
    //             $templateProcessor->setValue('name_for_notice', $insert_array['name_for_notice']);
    //             $templateProcessor->setValue('address', $insert_array['address']);

    //             $templateProcessor->setValue('old_gender', $insert_array['old_gender']);
    //             $templateProcessor->setValue('new_gender', $insert_array['new_gender']);
    //             $templateProcessor->setValue('new_gender_one', $insert_array['new_gender_one']);
    //             $templateProcessor->setValue('signature', $insert_array['signature']); 
    //         }
    //         // Minor == 1
    //         else if ( $minor == 1 && $name_change == 0 ) {
    //             $templateProcessor->setValue('approver', $insert_array['approver']);
    //             $templateProcessor->setValue('place', $insert_array['place']);
    //             $templateProcessor->setValue('notice_date', $new_date);
    //             $templateProcessor->setValue('salutation', $insert_array['salutation']);
    //             $templateProcessor->setValue('name_for_notice', $insert_array['name_for_notice']);
    //             $templateProcessor->setValue('address', $insert_array['address']);
    //             $templateProcessor->setValue('son_daughter', $insert_array['son_daughter']);
    //             $templateProcessor->setValue('gender', $insert_array['gender']);
    //             $templateProcessor->setValue('gender_his_her', $insert_array['gender_his_her']);

    //             $templateProcessor->setValue('old_gender', $insert_array['old_gender']);
    //             $templateProcessor->setValue('new_gender', $insert_array['new_gender']);
    //             $templateProcessor->setValue('new_gender_one', $insert_array['new_gender_one']);
    //             $templateProcessor->setValue('signature', $insert_array['signature']);
    //         }
    //         // Name and Gender change == 1
    //         else if ( $name_change == 1 && $minor == 0 ) {
    //             $templateProcessor->setValue('approver', $insert_array['approver']);
    //             $templateProcessor->setValue('place', $insert_array['place']);
    //             $templateProcessor->setValue('notice_date', $new_date);
    //             $templateProcessor->setValue('salutation', $insert_array['salutation']);
    //             $templateProcessor->setValue('name_for_notice', $insert_array['name_for_notice']);
    //             $templateProcessor->setValue('address', $insert_array['address']);

    //             $templateProcessor->setValue('old_name_two', $insert_array['old_name_two']);
    //             $templateProcessor->setValue('new_name_two', $insert_array['new_name_two']);
    //             $templateProcessor->setValue('old_gender_two', $insert_array['old_gender_two']);
    //             $templateProcessor->setValue('new_gender_two', $insert_array['new_gender_two']);
    //             $templateProcessor->setValue('new_name_three', $insert_array['new_name_three']);
    //             $templateProcessor->setValue('new_gender_three', $insert_array['new_gender_three']);
    //             $templateProcessor->setValue('name_gender_notice_signature', $insert_array['name_gender_notice_signature']);
    //         }
    //         // Name and Gender change == 1 &&  Minor == 1
    //         else if ( $name_change == 1 && $minor == 1 ) {
    //             $templateProcessor->setValue('approver', $insert_array['approver']);
    //             $templateProcessor->setValue('place', $insert_array['place']);
    //             $templateProcessor->setValue('notice_date', $new_date);
    //             $templateProcessor->setValue('salutation', $insert_array['salutation']);
    //             $templateProcessor->setValue('name_for_notice', $insert_array['name_for_notice']);
    //             $templateProcessor->setValue('address', $insert_array['address']);

    //             $templateProcessor->setValue('son_daughter', $insert_array['son_daughter']);
    //             $templateProcessor->setValue('he_she_gender', $insert_array['he_she_gender']);
    //             $templateProcessor->setValue('his_her_gender', $insert_array['his_her_gender']);

    //             $templateProcessor->setValue('old_minor_gender_three', $insert_array['old_minor_gender_three']);
    //             $templateProcessor->setValue('new_minor_gender_three', $insert_array['new_minor_gender_three']);
    //             $templateProcessor->setValue('old_minor_name_three', $insert_array['old_minor_name_three']);
    //             $templateProcessor->setValue('new_minor_name_three', $insert_array['new_minor_name_three']);
    //             $templateProcessor->setValue('new_minor_name_four', $insert_array['new_minor_name_four']);
    //             $templateProcessor->setValue('new_minor_gender_four', $insert_array['new_minor_gender_four']);
    //             $templateProcessor->setValue('name_gender_minor_signature', $insert_array['name_gender_minor_signature']);
    //         }

    //         $templateProcessor->saveAs($press_word_db_path);
    //         $pdf_file_path = $this->convert_word_to_PDF_for_gender($press_word_db_path,$file_no);

    //         // the below logic is final data to insert in the database
    //         if ( $minor == 0 && $name_change == 0 ) {
    //             $insert_array_final = array(
    //                 'gazette_type' => 1,
    //                 'state_id' => $this->security->xss_clean($this->input->post('state_id')),
    //                 'district_id' => $this->security->xss_clean($this->input->post('district_id')),
    //                 'block_ulb_id' => $this->security->xss_clean($this->input->post('block_ulb_id')),
    //                 'address_1' => $this->security->xss_clean($this->input->post('address_1')),
    //                 'pin_code' => $this->security->xss_clean($this->input->post('pin_code')),
    //                 'govt_emp' => $this->security->xss_clean($this->input->post('govt_emp')),
    //                 'minor' => $this->security->xss_clean($this->input->post('minor')),

    //                 // need to create a column like name_change
    //                 'name_change' => $this->security->xss_clean($this->input->post('name_change')),

    //                 // document details start
    //                 'affidavit' => $this->security->xss_clean($this->input->post('document_1')),
    //                 'original_newspaper' => $this->security->xss_clean($this->input->post('document_2')),
    //                 'notice' => $new_path,
    //                 'medical_cert' => $this->security->xss_clean($this->input->post('document_4')),
    //                 'id_proof_doc' => $this->security->xss_clean($this->input->post('document_5')),
    //                 'address_proof_doc' => $this->security->xss_clean($this->input->post('document_6')),
    //                 'deed_changing_form' => $this->security->xss_clean($this->input->post('document_7')),
    //                 'age_proof' => $this->security->xss_clean($this->input->post('document_8')),
    //                 'approval_auth_doc' => $this->security->xss_clean($this->input->post('document_9')),
                    
    //                 // document details end    

    //                 // notice part start
    //                 'approver' => $this->security->xss_clean($this->input->post('approver')),
    //                 'place' => $this->security->xss_clean($this->input->post('place')),
    //                 'notice_date' => $this->security->xss_clean($this->input->post('notice_date')), 
    //                 'salutation' => $this->security->xss_clean($this->input->post('salutation')),
    //                 'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice')),
    //                 'address' => $this->security->xss_clean($this->input->post('address')),
    //                 // 'son_daughter' => $this->security->xss_clean($this->input->post('son_daughter')),
    //                 // 'gender' => $this->security->xss_clean($this->input->post('gender')),

    //                 'old_gender' => $this->security->xss_clean($this->input->post('old_gender')),
    //                 'new_gender' => $this->security->xss_clean($this->input->post('new_gender')),
    //                 'new_gender_one' => $this->security->xss_clean($this->input->post('new_gender_one')),
    //                 'signature' => $this->security->xss_clean($this->input->post('signature')),
    //                 // notice part end

    //                 'file_no' => $file_no,
    //                 'press_word_db_path' => $new_path,
    //                 'pdf_path' => $this->doc_file_for_gender_pdf,
    //             );
    //         } else if ( $minor == 1 && $name_change == 0 ) {
    //             $insert_array_final = array(
    //                 'gazette_type' => 1,
    //                 'state_id' => $this->security->xss_clean($this->input->post('state_id')),
    //                 'district_id' => $this->security->xss_clean($this->input->post('district_id')),
    //                 'block_ulb_id' => $this->security->xss_clean($this->input->post('block_ulb_id')),
    //                 'address_1' => $this->security->xss_clean($this->input->post('address_1')),
    //                 'pin_code' => $this->security->xss_clean($this->input->post('pin_code')),
    //                 'govt_emp' => $this->security->xss_clean($this->input->post('govt_emp')),
    //                 'minor' => $this->security->xss_clean($this->input->post('minor')),
    //                 'name_change' => $this->security->xss_clean($this->input->post('name_change')),

    //                 // document details start
    //                 'affidavit' => $this->security->xss_clean($this->input->post('document_1')),
    //                 'original_newspaper' => $this->security->xss_clean($this->input->post('document_2')),
    //                 'notice' => $new_path,
    //                 'medical_cert' => $this->security->xss_clean($this->input->post('document_4')),
    //                 'id_proof_doc' => $this->security->xss_clean($this->input->post('document_5')),
    //                 'address_proof_doc' => $this->security->xss_clean($this->input->post('document_6')),
    //                 'deed_changing_form' => $this->security->xss_clean($this->input->post('document_7')),
    //                 'age_proof' => $this->security->xss_clean($this->input->post('document_8')),
    //                 'approval_auth_doc' => $this->security->xss_clean($this->input->post('document_9')),
    //                 // document details end

    //                 // notice part start
    //                 'approver' => $this->security->xss_clean($this->input->post('approver_minor')),
    //                 'place' => $this->security->xss_clean($this->input->post('place_minor')),
    //                 'notice_date' => $this->security->xss_clean($this->input->post('notice_date_minor')),
    //                 'salutation' => $this->security->xss_clean($this->input->post('salutation_minor')),
    //                 'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice_minor')),
    //                 'address' => $this->security->xss_clean($this->input->post('address_minor')),
    //                 'son_daughter' => $this->security->xss_clean($this->input->post('son_daughter')),
    //                 'gender' => $this->security->xss_clean($this->input->post('gender')),

    //                 // new column need to create like gender_his_her
    //                 'gender_his_her' => $this->security->xss_clean($this->input->post('gender_his_her')), 

    //                 'old_gender' => $this->security->xss_clean($this->input->post('old_gender_minor')),
    //                 'new_gender' => $this->security->xss_clean($this->input->post('new_gender_minor')),
    //                 'new_gender_one' => $this->security->xss_clean($this->input->post('new_gender_one_minor')),
    //                 'signature' => $this->security->xss_clean($this->input->post('signature_minor')),
    //                 // notice part end

    //                 'file_no' => $file_no,
    //                 'press_word_db_path' => $new_path,
    //                 'pdf_path' => $this->doc_file_for_gender_pdf,
    //             );
    //         } else if ( $name_change == 1 && $minor == 0 ) {
    //             $insert_array_final = array(
    //                 'gazette_type' => 1,
    //                 'state_id' => $this->security->xss_clean($this->input->post('state_id')),
    //                 'district_id' => $this->security->xss_clean($this->input->post('district_id')),
    //                 'block_ulb_id' => $this->security->xss_clean($this->input->post('block_ulb_id')),
    //                 'address_1' => $this->security->xss_clean($this->input->post('address_1')),
    //                 'pin_code' => $this->security->xss_clean($this->input->post('pin_code')),
    //                 'govt_emp' => $this->security->xss_clean($this->input->post('govt_emp')),
    //                 'minor' => $this->security->xss_clean($this->input->post('minor')),
    //                 'name_change' => $this->security->xss_clean($this->input->post('name_change')),

    //                 // document details start
    //                 'affidavit' => $this->security->xss_clean($this->input->post('document_1')),
    //                 'original_newspaper' => $this->security->xss_clean($this->input->post('document_2')),
    //                 'notice' => $new_path,
    //                 'medical_cert' => $this->security->xss_clean($this->input->post('document_4')),
    //                 'id_proof_doc' => $this->security->xss_clean($this->input->post('document_5')),
    //                 'address_proof_doc' => $this->security->xss_clean($this->input->post('document_6')),
    //                 'deed_changing_form' => $this->security->xss_clean($this->input->post('document_7')),
    //                 'age_proof' => $this->security->xss_clean($this->input->post('document_8')),
    //                 'approval_auth_doc' => $this->security->xss_clean($this->input->post('document_9')),
    //                 // document details end

    //                 // notice part start
    //                 'approver' => $this->security->xss_clean($this->input->post('name_gender_approver')),
    //                 'place' => $this->security->xss_clean($this->input->post('name_gender_place')),
    //                 'notice_date' => $this->security->xss_clean($this->input->post('name_gender_notice_date')),
    //                 'salutation' => $this->security->xss_clean($this->input->post('name_gender_salutation')),
    //                 'name_for_notice' => $this->security->xss_clean($this->input->post('name_gender_notice')),
    //                 'address' => $this->security->xss_clean($this->input->post('name_gender_address')),

    //                 'old_name_two' => $this->security->xss_clean($this->input->post('old_name_two')),
    //                 'new_name_two' => $this->security->xss_clean($this->input->post('new_name_two')),
    //                 'old_gender_two' => $this->security->xss_clean($this->input->post('old_gender_two')),
    //                 'new_gender_two' => $this->security->xss_clean($this->input->post('new_gender_two')),
    //                 'new_name_three' => $this->security->xss_clean($this->input->post('new_name_three')),
    //                 'new_gender_three' => $this->security->xss_clean($this->input->post('new_gender_three')),
    //                 'signature' => $this->security->xss_clean($this->input->post('name_gender_notice_signature')),
    //                 // notice part end

    //                 'file_no' => $file_no,
    //                 'press_word_db_path' => $new_path,
    //                 'pdf_path' => $this->doc_file_for_gender_pdf,
    //             );
    //         } else if ( $name_change == 1 && $minor == 1 ) {
    //             $insert_array_final = array(
    //                 'gazette_type' => 1,
    //                 'state_id' => $this->security->xss_clean($this->input->post('state_id')),
    //                 'district_id' => $this->security->xss_clean($this->input->post('district_id')),
    //                 'block_ulb_id' => $this->security->xss_clean($this->input->post('block_ulb_id')),
    //                 'address_1' => $this->security->xss_clean($this->input->post('address_1')),
    //                 'pin_code' => $this->security->xss_clean($this->input->post('pin_code')),
    //                 'govt_emp' => $this->security->xss_clean($this->input->post('govt_emp')),
    //                 'minor' => $this->security->xss_clean($this->input->post('minor')),
    //                 'name_change' => $this->security->xss_clean($this->input->post('name_change')),

    //                 // document details start
    //                 'affidavit' => $this->security->xss_clean($this->input->post('document_1')),
    //                 'original_newspaper' => $this->security->xss_clean($this->input->post('document_2')),
    //                 'notice' => $new_path,
    //                 'medical_cert' => $this->security->xss_clean($this->input->post('document_4')),
    //                 'id_proof_doc' => $this->security->xss_clean($this->input->post('document_5')),
    //                 'address_proof_doc' => $this->security->xss_clean($this->input->post('document_6')),
    //                 'deed_changing_form' => $this->security->xss_clean($this->input->post('document_7')),
    //                 'age_proof' => $this->security->xss_clean($this->input->post('document_8')),
    //                 'approval_auth_doc' => $this->security->xss_clean($this->input->post('document_9')),
    //                 // document details end

    //                 // notice part start
    //                 'approver' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_approver')),
    //                 'place' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_place')),
    //                 'notice_date' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_date')),
    //                 'salutation' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_salutation')),
    //                 'name_for_notice' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_notice')),
    //                 'address' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_address')),
    //                 'son_daughter' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_son_daughter')),
                    
    //                 // new column need to create like 
    //                 'old_name_two' => $this->security->xss_clean($this->input->post('old_minor_gender_three')),
    //                 'new_name_two' => $this->security->xss_clean($this->input->post('new_minor_gender_three')),
    //                 'old_gender_two' => $this->security->xss_clean($this->input->post('old_minor_name_three')),
    //                 'new_gender_two' => $this->security->xss_clean($this->input->post('new_minor_name_three')),
                    
    //                 'gender' => $this->security->xss_clean($this->input->post('he_she_gender')),

    //                 // new column need to create like new_minor_name_four
    //                 'new_name_three' => $this->security->xss_clean($this->input->post('new_minor_name_four')),

    //                 'gender_his_her' => $this->security->xss_clean($this->input->post('his_her_gender')),
                    
    //                 // new column need to create like new_minor_gender_four
    //                 'new_gender_three' => $this->security->xss_clean($this->input->post('new_minor_gender_four')),

    //                 'signature' => $this->security->xss_clean($this->input->post('name_gender_minor_signature')),

    //                 'file_no' => $file_no,
    //                 'press_word_db_path' => $new_path,
    //                 'pdf_path' => $this->doc_file_for_gender_pdf,
    //             );
    //         }

    //         $result = $this->Applicants_login_model->insert_change_of_gender($insert_array_final);

    //         if ($result) {
    //             audit_action_log($this->session->userdata('user_id'), 'Change of Gender', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());

    //             $this->session->set_flashdata("success", "Change of name/surname added successfully");
    //             $json['redirect'] = base_url() . "check_status/change_gender_status";
    //         } else {
    //             $this->session->set_flashdata("error", "Something went wrong");
    //             $json['redirect'] = base_url() . "check_status/change_gender_status";
    //         }

    //         // Now working on check_status/change_gender_status part.

    //     }

    //     echo json_encode($json);
    // }

    public function insert_change_of_gender() {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        // echo 'test';exit;
        // $json = array('test1', 'test2');
        $json = array();

        

        $minor = (int) $this->security->xss_clean($this->input->post('minor') ?? 0);
        $name_change = (int) $this->security->xss_clean($this->input->post('name_change') ?? 0);
        $govt_emp = (int) $this->security->xss_clean($this->input->post('govt_emp') ?? 0);

        log_message('debug', 'POST DATA: ' . print_r($_POST, true));
        log_message('debug', 'Cleaned DATA -> minor: ' . $minor . ', name_change: ' . $name_change . ', govt_emp: ' . $govt_emp);


        // Common validation rules
        $this->form_validation->set_rules('state_id', 'State', 'trim|required');
        $this->form_validation->set_rules('district_id', 'District', 'trim|required');
        $this->form_validation->set_rules('block_ulb_id', 'Block/ULB', 'trim|required');
        $this->form_validation->set_rules('address_1', 'Address', 'trim|required|min_length[5]|max_length[200]');
        $this->form_validation->set_rules('govt_emp', 'Government Employee', 'trim|required');
        $this->form_validation->set_rules('minor', 'Minor', 'trim|required');
        $this->form_validation->set_rules('docu_3', 'Select Word/Docx File', 'callback_gazette_doc_upload_for_gender');

        // Start condition checks
        if ($name_change == 0 && $minor == 0 && $govt_emp == 0) {
            // Case 1: Only Gender Change
            $this->form_validation->set_rules('approver', 'Approver', 'trim|required');
            $this->form_validation->set_rules('place', 'Place', 'trim|required');
            $this->form_validation->set_rules('notice_date', 'Date', 'trim|required');
            $this->form_validation->set_rules('salutation', 'Salutation', 'trim|required');
            $this->form_validation->set_rules('name_for_notice', 'Name', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('old_gender', 'Old Gender', 'trim|required');
            $this->form_validation->set_rules('new_gender', 'New Gender', 'trim|required');
            $this->form_validation->set_rules('new_gender_one', 'New Gender One', 'trim|required');
            $this->form_validation->set_rules('signature', 'Signature', 'trim|required');

        } elseif ($name_change == 0 && $minor == 1) {
            // Case 2: Gender Change for Minor
            $this->form_validation->set_rules('approver_minor', 'Approver', 'trim|required');
            $this->form_validation->set_rules('place_minor', 'Place', 'trim|required');
            $this->form_validation->set_rules('notice_date_minor', 'Date', 'trim|required');
            $this->form_validation->set_rules('salutation_minor', 'Salutation', 'trim|required');
            $this->form_validation->set_rules('name_for_notice_minor', 'Name', 'trim|required');
            $this->form_validation->set_rules('address_minor', 'Address', 'trim|required');
            $this->form_validation->set_rules('son_daughter', 'Relation', 'trim|required');
            $this->form_validation->set_rules('old_gender_minor', 'Old Gender', 'trim|required');
            $this->form_validation->set_rules('new_gender_minor', 'New Gender', 'trim|required');
            $this->form_validation->set_rules('gender', 'Gender', 'trim');
            $this->form_validation->set_rules('gender_his_her', 'Gender Pronoun', 'trim');
            $this->form_validation->set_rules('new_gender_one_minor', 'New Gender One', 'trim|required');
            $this->form_validation->set_rules('signature_minor', 'Signature', 'trim|required');

        } elseif ($name_change == 1 && $minor == 0 && $govt_emp == 0) {
            // Case 3: Name + Gender Change
            $this->form_validation->set_rules('name_gender_approver', 'Approver', 'trim|required');
            $this->form_validation->set_rules('name_gender_place', 'Place', 'trim|required');
            $this->form_validation->set_rules('name_gender_notice_date', 'Date', 'trim|required');
            $this->form_validation->set_rules('name_gender_salutation', 'Salutation', 'trim|required');
            $this->form_validation->set_rules('name_gender_notice', 'Name', 'trim|required');
            $this->form_validation->set_rules('name_gender_address', 'Address', 'trim|required');
            $this->form_validation->set_rules('old_name_two', 'Old Name', 'trim|required');
            $this->form_validation->set_rules('new_name_two', 'New Name', 'trim|required');
            $this->form_validation->set_rules('old_gender_two', 'Old Gender', 'trim|required');
            $this->form_validation->set_rules('new_gender_two', 'New Gender', 'trim|required');
            $this->form_validation->set_rules('new_name_three', 'New Name 3', 'trim|required');
            $this->form_validation->set_rules('new_gender_three', 'New Gender 3', 'trim|required');
            $this->form_validation->set_rules('name_gender_notice_signature', 'Signature', 'trim|required');

        } elseif ($name_change == 1 && $minor == 1) {
            // Case 4: Name + Gender Change for Minor
            $this->form_validation->set_rules('name_gender_minor_yes_approver', 'Approver', 'trim|required');
            $this->form_validation->set_rules('name_gender_minor_yes_place', 'Place', 'trim|required');
            $this->form_validation->set_rules('name_gender_minor_yes_date', 'Date', 'trim|required');
            $this->form_validation->set_rules('name_gender_minor_yes_salutation', 'Salutation', 'trim|required');
            $this->form_validation->set_rules('name_gender_minor_yes_notice', 'Name', 'trim|required');
            $this->form_validation->set_rules('name_gender_minor_yes_address', 'Address', 'trim|required');
            $this->form_validation->set_rules('name_gender_minor_yes_son_daughter', 'Relation', 'trim|required');
            $this->form_validation->set_rules('he_she_gender', 'Pronoun', 'trim|required');
            $this->form_validation->set_rules('his_her_gender', 'Pronoun', 'trim|required');
            $this->form_validation->set_rules('old_minor_gender_three', 'Old Gender', 'trim|required');
            $this->form_validation->set_rules('new_minor_gender_three', 'New Gender', 'trim|required');
            $this->form_validation->set_rules('old_minor_name_three', 'Old Name', 'trim|required');
            $this->form_validation->set_rules('new_minor_name_three', 'New Name', 'trim|required');
            $this->form_validation->set_rules('new_minor_name_four', 'New Name 4', 'trim|required');
            $this->form_validation->set_rules('new_minor_gender_four', 'New Gender 4', 'trim|required');
            $this->form_validation->set_rules('name_gender_minor_signature', 'Signature', 'trim|required');

        } elseif ($name_change == 1 && $govt_emp == 1 && $minor == 0) {
            // Case 5: Name + Gender Change for Government Employee
            $this->form_validation->set_rules('name_gender_approver', 'Approver', 'trim|required');
            $this->form_validation->set_rules('name_gender_place', 'Place', 'trim|required');
            $this->form_validation->set_rules('name_gender_notice_date', 'Date', 'trim|required');
            $this->form_validation->set_rules('name_gender_salutation', 'Salutation', 'trim|required');
            $this->form_validation->set_rules('name_gender_notice', 'Name', 'trim|required');
            $this->form_validation->set_rules('name_gender_address', 'Address', 'trim|required');
            $this->form_validation->set_rules('old_name_two', 'Old Name', 'trim|required');
            $this->form_validation->set_rules('new_name_two', 'New Name', 'trim|required');
            $this->form_validation->set_rules('old_gender_two', 'Old Gender', 'trim|required');
            $this->form_validation->set_rules('new_gender_two', 'New Gender', 'trim|required');
            $this->form_validation->set_rules('new_name_three', 'New Name 3', 'trim|required');
            $this->form_validation->set_rules('new_gender_three', 'New Gender 3', 'trim|required');
            $this->form_validation->set_rules('name_gender_notice_signature', 'Signature', 'trim|required');
        }


        // check form validation
        if ($this->form_validation->run() == false) {
            $json['error'] = $this->form_validation->error_array();
        } else {
            // $json = array('test1', 'hello-test!');
            $return = $this->db->select('id')
                               ->from('gz_change_of_gender_master')
                               ->where('deleted', '0')
                               ->order_by('id', 'DESC')
                               ->limit(1)
                               ->get()->row();

            $final_file_no1 = '0001';
            if (!empty($return)) {
                $id = $return->id + 1;
                $code = (string) $id;
                $len = strlen($code);

                if ($len == 1) {
                    $final_file_no1 = '000' . $id;
                } else if ($len == 2) {
                    $final_file_no1 = '00' . $id;
                } else if ($len == 3) {
                    $final_file_no1 = '0' . $id;
                } else if ($len == 4) {
                    $final_file_no1 = $id;
                }
            }
            $year = date('Y');
            $file_no = 'XG-' . $final_file_no1 . '-' . $year;

            $press_word_db_path_raw = 'uploads/change_of_gender/notice_updated_doc_file/' . time() . '.docx';
            $path = str_replace('\\', '/', FCPATH);
            $new_path = base_url() . $press_word_db_path_raw;
            $press_word_db_path = $path . $press_word_db_path_raw;

            if ( $name_change == 0 && $minor == 0 && $govt_emp == 0 ) {
                $template_file = FCPATH . 'uploads/sample/cog_adult_notice_sample.docx';

                $insert_array = array(
                    'approver' => $this->security->xss_clean($this->input->post('approver')),
                    'place' => $this->security->xss_clean($this->input->post('place')),
                    'notice_date' => $this->security->xss_clean($this->input->post('notice_date')),
                    //'notice_date_minor' => $this->security->xss_clean($this->input->post('notice_date_minor')),
                    'salutation' => $this->security->xss_clean($this->input->post('salutation')),
                    'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice')),
                    // 'son_daughter' => $this->security->xss_clean($this->input->post('son_daughter')),
                    // 'gender' => $this->security->xss_clean($this->input->post('gender')),
                    'address' => $this->security->xss_clean($this->input->post('address')),
                    'old_gender' => $this->security->xss_clean($this->input->post('old_gender')),
                    'new_gender' => $this->security->xss_clean($this->input->post('new_gender')),
                    'new_gender_one' => $this->security->xss_clean($this->input->post('new_gender_one')),
                    'signature' => $this->security->xss_clean($this->input->post('signature')),
                    'file_no' => $file_no,
                    'press_word_db_path' => $new_path,
                    // 'pdf_path' => $this->doc_file_for_name_surname_pdf,
                );
            } else if ( $name_change == 0 && $minor == 1 ) {
                $template_file = FCPATH . "uploads/sample/cog_minor_notice_sample.docx";
                // ms word file updated.....
                $insert_array = array( 
                    'approver' => $this->security->xss_clean($this->input->post('approver_minor')),
                    'place' => $this->security->xss_clean($this->input->post('place_minor')),
                    'notice_date' => $this->security->xss_clean($this->input->post('notice_date_minor')),
                    'salutation' => $this->security->xss_clean($this->input->post('salutation_minor')),
                    'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice_minor')),
                    'address' => $this->security->xss_clean($this->input->post('address_minor')),
                    'son_daughter' => $this->security->xss_clean($this->input->post('son_daughter')),
                    'gender' => $this->security->xss_clean($this->input->post('gender')),
                    'gender_his_her' => $this->security->xss_clean($this->input->post('gender_his_her')),

                    'old_gender' => $this->security->xss_clean($this->input->post('old_gender_minor')),
                    'new_gender' => $this->security->xss_clean($this->input->post('new_gender_minor')),
                    'new_gender_one' => $this->security->xss_clean($this->input->post('new_gender_one_minor')),
                    'signature' => $this->security->xss_clean($this->input->post('signature_minor')),
                    'file_no' => $file_no,
                    'press_word_db_path' => $new_path,
                    // 'pdf_path' => $this->doc_file_for_name_surname_pdf,
                );
            } else if ( $name_change == 1 && $minor == 0 && $govt_emp == 0 ) { 
                $template_file = FCPATH . "uploads/sample/cog_adult_name_gender_change_notice_sample.docx";
                //  ms word file updated
                $insert_array = array(
                    'approver' => $this->security->xss_clean($this->input->post('name_gender_approver')),
                    'place' => $this->security->xss_clean($this->input->post('name_gender_place')),
                    'notice_date' => $this->security->xss_clean($this->input->post('name_gender_notice_date')),
                    'salutation' => $this->security->xss_clean($this->input->post('name_gender_salutation')),
                    'name_for_notice' => $this->security->xss_clean($this->input->post('name_gender_notice')),
                    'address' => $this->security->xss_clean($this->input->post('name_gender_address')),
                    
                    'old_name_two' => $this->security->xss_clean($this->input->post('old_name_two')),
                    'new_name_two' => $this->security->xss_clean($this->input->post('new_name_two')),
                    'old_gender_two' => $this->security->xss_clean($this->input->post('old_gender_two')),
                    'new_gender_two' => $this->security->xss_clean($this->input->post('new_gender_two')),
                    'new_name_three' => $this->security->xss_clean($this->input->post('new_name_three')),
                    'new_gender_three' => $this->security->xss_clean($this->input->post('new_gender_three')),
                    'name_gender_notice_signature' => $this->security->xss_clean($this->input->post('name_gender_notice_signature')),
                );
            } else if ( $name_change == 1 && $minor == 1 ) {
                $template_file = FCPATH . "uploads/sample/cog_minor_name_gender_change_notice_sample.docx";
                // Word file edit complete of this logic
                $insert_array = array(
                    'approver' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_approver')),
                    'place' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_place')),
                    'notice_date' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_date')),
                    'salutation' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_salutation')),
                    'name_for_notice' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_notice')),
                    'address' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_address')),
                    
                    'son_daughter' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_son_daughter')),
                    'he_she_gender' => $this->security->xss_clean($this->input->post('he_she_gender')),
                    'his_her_gender' => $this->security->xss_clean($this->input->post('his_her_gender')),

                    'old_minor_gender_three' => $this->security->xss_clean($this->input->post('old_minor_gender_three')),
                    'new_minor_gender_three' => $this->security->xss_clean($this->input->post('new_minor_gender_three')),
                    'old_minor_name_three' => $this->security->xss_clean($this->input->post('old_minor_name_three')),
                    'new_minor_name_three' => $this->security->xss_clean($this->input->post('new_minor_name_three')),
                    'new_minor_name_four' => $this->security->xss_clean($this->input->post('new_minor_name_four')),
                    'new_minor_gender_four' => $this->security->xss_clean($this->input->post('new_minor_gender_four')),
                    'name_gender_minor_signature' => $this->security->xss_clean($this->input->post('name_gender_minor_signature')),
                    
                );
            } else if ( $name_change == 1 && $govt_emp == 1 && $minor == 0) { 
                $template_file = FCPATH . "uploads/sample/cog_adult_name_gender_change_notice_sample.docx";
                //  ms word file updated
                $insert_array = array(
                    'approver' => $this->security->xss_clean($this->input->post('name_gender_approver')),
                    'place' => $this->security->xss_clean($this->input->post('name_gender_place')),
                    'notice_date' => $this->security->xss_clean($this->input->post('name_gender_notice_date')),
                    'salutation' => $this->security->xss_clean($this->input->post('name_gender_salutation')),
                    'name_for_notice' => $this->security->xss_clean($this->input->post('name_gender_notice')),
                    'address' => $this->security->xss_clean($this->input->post('name_gender_address')),
                    
                    'old_name_two' => $this->security->xss_clean($this->input->post('old_name_two')),
                    'new_name_two' => $this->security->xss_clean($this->input->post('new_name_two')),
                    'old_gender_two' => $this->security->xss_clean($this->input->post('old_gender_two')),
                    'new_gender_two' => $this->security->xss_clean($this->input->post('new_gender_two')),
                    'new_name_three' => $this->security->xss_clean($this->input->post('new_name_three')),
                    'new_gender_three' => $this->security->xss_clean($this->input->post('new_gender_three')),
                    'name_gender_notice_signature' => $this->security->xss_clean($this->input->post('name_gender_notice_signature')),
                );
            }

            $timestamp = strtotime($insert_array['notice_date']);

            // Creating new date format from that timestamp
            $new_date = date('jS F Y', $timestamp);

            // load from template processor
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($template_file);

            // var_dump($insert_array);exit();

            // set dynamic values provided by Govt. Press
            if ( $name_change == 0 && $minor == 0 && $govt_emp == 0 ) {
                $templateProcessor->setValue('approver', $insert_array['approver']);
                $templateProcessor->setValue('place', $insert_array['place']);
                $templateProcessor->setValue('notice_date', $new_date);
                $templateProcessor->setValue('salutation', $insert_array['salutation']);
                $templateProcessor->setValue('name_for_notice', $insert_array['name_for_notice']);
                $templateProcessor->setValue('address', $insert_array['address']);

                $templateProcessor->setValue('old_gender', $insert_array['old_gender']);
                $templateProcessor->setValue('new_gender', $insert_array['new_gender']);
                $templateProcessor->setValue('new_gender_one', $insert_array['new_gender_one']);
                $templateProcessor->setValue('signature', $insert_array['signature']); 
            }
            // Minor == 1
            else if ( $name_change == 0 && $minor == 1 ) {
                $templateProcessor->setValue('approver', $insert_array['approver']);
                $templateProcessor->setValue('place', $insert_array['place']);
                $templateProcessor->setValue('notice_date', $new_date);
                $templateProcessor->setValue('salutation', $insert_array['salutation']);
                $templateProcessor->setValue('name_for_notice', $insert_array['name_for_notice']);
                $templateProcessor->setValue('address', $insert_array['address']);
                $templateProcessor->setValue('son_daughter', $insert_array['son_daughter']);
                $templateProcessor->setValue('gender', $insert_array['gender']);
                $templateProcessor->setValue('gender_his_her', $insert_array['gender_his_her']);

                $templateProcessor->setValue('old_gender', $insert_array['old_gender']);
                $templateProcessor->setValue('new_gender', $insert_array['new_gender']);
                $templateProcessor->setValue('new_gender_one', $insert_array['new_gender_one']);
                $templateProcessor->setValue('signature', $insert_array['signature']);
            }
            // Name and Gender change == 1
            else if ( $name_change == 1 && $minor == 0 && $govt_emp == 0 ) {
                $templateProcessor->setValue('approver', $insert_array['approver']);
                $templateProcessor->setValue('place', $insert_array['place']);
                $templateProcessor->setValue('notice_date', $new_date);
                $templateProcessor->setValue('salutation', $insert_array['salutation']);
                $templateProcessor->setValue('name_for_notice', $insert_array['name_for_notice']);
                $templateProcessor->setValue('address', $insert_array['address']);

                $templateProcessor->setValue('old_name_two', $insert_array['old_name_two']);
                $templateProcessor->setValue('new_name_two', $insert_array['new_name_two']);
                $templateProcessor->setValue('old_gender_two', $insert_array['old_gender_two']);
                $templateProcessor->setValue('new_gender_two', $insert_array['new_gender_two']);
                $templateProcessor->setValue('new_name_three', $insert_array['new_name_three']);
                $templateProcessor->setValue('new_gender_three', $insert_array['new_gender_three']);
                $templateProcessor->setValue('name_gender_notice_signature', $insert_array['name_gender_notice_signature']);
            }
            // Name and Gender change == 1 &&  Minor == 1
            else if ( $name_change == 1 && $minor == 1 ) {
                $templateProcessor->setValue('approver', $insert_array['approver']);
                $templateProcessor->setValue('place', $insert_array['place']);
                $templateProcessor->setValue('notice_date', $new_date);
                $templateProcessor->setValue('salutation', $insert_array['salutation']);
                $templateProcessor->setValue('name_for_notice', $insert_array['name_for_notice']);
                $templateProcessor->setValue('address', $insert_array['address']);

                $templateProcessor->setValue('son_daughter', $insert_array['son_daughter']);
                $templateProcessor->setValue('he_she_gender', $insert_array['he_she_gender']);
                $templateProcessor->setValue('his_her_gender', $insert_array['his_her_gender']);

                $templateProcessor->setValue('old_minor_gender_three', $insert_array['old_minor_gender_three']);
                $templateProcessor->setValue('new_minor_gender_three', $insert_array['new_minor_gender_three']);
                $templateProcessor->setValue('old_minor_name_three', $insert_array['old_minor_name_three']);
                $templateProcessor->setValue('new_minor_name_three', $insert_array['new_minor_name_three']);
                $templateProcessor->setValue('new_minor_name_four', $insert_array['new_minor_name_four']);
                $templateProcessor->setValue('new_minor_gender_four', $insert_array['new_minor_gender_four']);
                $templateProcessor->setValue('name_gender_minor_signature', $insert_array['name_gender_minor_signature']);
            }

            // Govt official Name and Gender Both change == 1
            else if ( $name_change == 1 && $govt_emp == 1 && $minor == 0) {
                $templateProcessor->setValue('approver', $insert_array['approver']);
                $templateProcessor->setValue('place', $insert_array['place']);
                $templateProcessor->setValue('notice_date', $new_date);
                $templateProcessor->setValue('salutation', $insert_array['salutation']);
                $templateProcessor->setValue('name_for_notice', $insert_array['name_for_notice']);
                $templateProcessor->setValue('address', $insert_array['address']);

                $templateProcessor->setValue('old_name_two', $insert_array['old_name_two']);
                $templateProcessor->setValue('new_name_two', $insert_array['new_name_two']);
                $templateProcessor->setValue('old_gender_two', $insert_array['old_gender_two']);
                $templateProcessor->setValue('new_gender_two', $insert_array['new_gender_two']);
                $templateProcessor->setValue('new_name_three', $insert_array['new_name_three']);
                $templateProcessor->setValue('new_gender_three', $insert_array['new_gender_three']);
                $templateProcessor->setValue('name_gender_notice_signature', $insert_array['name_gender_notice_signature']);
            }


            $templateProcessor->saveAs($press_word_db_path);
            $pdf_file_path = $this->convert_word_to_PDF_for_gender($press_word_db_path,$file_no);

            // the below logic is final data to insert in the database
            if ( $name_change == 0 && $minor == 0 && $govt_emp == 0 ) {
                $insert_array_final = array(
                    'gazette_type' => 1,
                    'state_id' => $this->security->xss_clean($this->input->post('state_id')),
                    'district_id' => $this->security->xss_clean($this->input->post('district_id')),
                    'block_ulb_id' => $this->security->xss_clean($this->input->post('block_ulb_id')),
                    'address_1' => $this->security->xss_clean($this->input->post('address_1')),
                    'pin_code' => $this->security->xss_clean($this->input->post('pin_code')),
                    'govt_emp' => $this->security->xss_clean($this->input->post('govt_emp')),
                    'minor' => $this->security->xss_clean($this->input->post('minor')),

                    // need to create a column like name_change
                    'name_change' => $this->security->xss_clean($this->input->post('name_change')),

                    // document details start
                    'affidavit' => $this->security->xss_clean($this->input->post('document_1')),
                    'original_newspaper' => $this->security->xss_clean($this->input->post('document_2')),
                    'notice' => $new_path,
                    'medical_cert' => $this->security->xss_clean($this->input->post('document_4')),
                    'id_proof_doc' => $this->security->xss_clean($this->input->post('document_5')),
                    'address_proof_doc' => $this->security->xss_clean($this->input->post('document_6')),
                    'deed_changing_form' => $this->security->xss_clean($this->input->post('document_7')),
                    'age_proof' => $this->security->xss_clean($this->input->post('document_8')),
                    'approval_auth_doc' => $this->security->xss_clean($this->input->post('document_9')),
                    
                    // document details end    

                    // notice part start
                    'approver' => $this->security->xss_clean($this->input->post('approver')),
                    'place' => $this->security->xss_clean($this->input->post('place')),
                    'notice_date' => $this->security->xss_clean($this->input->post('notice_date')), 
                    'salutation' => $this->security->xss_clean($this->input->post('salutation')),
                    'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice')),
                    'address' => $this->security->xss_clean($this->input->post('address')),
                    // 'son_daughter' => $this->security->xss_clean($this->input->post('son_daughter')),
                    // 'gender' => $this->security->xss_clean($this->input->post('gender')),

                    'old_gender' => $this->security->xss_clean($this->input->post('old_gender')),
                    'new_gender' => $this->security->xss_clean($this->input->post('new_gender')),
                    'new_gender_one' => $this->security->xss_clean($this->input->post('new_gender_one')),
                    'signature' => $this->security->xss_clean($this->input->post('signature')),
                    // notice part end

                    'file_no' => $file_no,
                    'press_word_db_path' => $new_path,
                    'pdf_path' => $this->doc_file_for_gender_pdf,
                );
            } else if ( $name_change == 0 && $minor == 1 ) {
                $insert_array_final = array(
                    'gazette_type' => 1,
                    'state_id' => $this->security->xss_clean($this->input->post('state_id')),
                    'district_id' => $this->security->xss_clean($this->input->post('district_id')),
                    'block_ulb_id' => $this->security->xss_clean($this->input->post('block_ulb_id')),
                    'address_1' => $this->security->xss_clean($this->input->post('address_1')),
                    'pin_code' => $this->security->xss_clean($this->input->post('pin_code')),
                    'govt_emp' => $this->security->xss_clean($this->input->post('govt_emp')),
                    'minor' => $this->security->xss_clean($this->input->post('minor')),
                    'name_change' => $this->security->xss_clean($this->input->post('name_change')),

                    // document details start
                    'affidavit' => $this->security->xss_clean($this->input->post('document_1')),
                    'original_newspaper' => $this->security->xss_clean($this->input->post('document_2')),
                    'notice' => $new_path,
                    'medical_cert' => $this->security->xss_clean($this->input->post('document_4')),
                    'id_proof_doc' => $this->security->xss_clean($this->input->post('document_5')),
                    'address_proof_doc' => $this->security->xss_clean($this->input->post('document_6')),
                    'deed_changing_form' => $this->security->xss_clean($this->input->post('document_7')),
                    'age_proof' => $this->security->xss_clean($this->input->post('document_8')),
                    'approval_auth_doc' => $this->security->xss_clean($this->input->post('document_9')),
                    // document details end

                    // notice part start
                    'approver' => $this->security->xss_clean($this->input->post('approver_minor')),
                    'place' => $this->security->xss_clean($this->input->post('place_minor')),
                    'notice_date' => $this->security->xss_clean($this->input->post('notice_date_minor')),
                    'salutation' => $this->security->xss_clean($this->input->post('salutation_minor')),
                    'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice_minor')),
                    'address' => $this->security->xss_clean($this->input->post('address_minor')),
                    'son_daughter' => $this->security->xss_clean($this->input->post('son_daughter')),
                    'gender' => $this->security->xss_clean($this->input->post('gender')),

                    // new column need to create like gender_his_her
                    'gender_his_her' => $this->security->xss_clean($this->input->post('gender_his_her')), 

                    'old_gender' => $this->security->xss_clean($this->input->post('old_gender_minor')),
                    'new_gender' => $this->security->xss_clean($this->input->post('new_gender_minor')),
                    'new_gender_one' => $this->security->xss_clean($this->input->post('new_gender_one_minor')),
                    'signature' => $this->security->xss_clean($this->input->post('signature_minor')),
                    // notice part end

                    'file_no' => $file_no,
                    'press_word_db_path' => $new_path,
                    'pdf_path' => $this->doc_file_for_gender_pdf,
                );
            } else if ( $name_change == 1 && $minor == 0 && $govt_emp == 0 ) {
                $insert_array_final = array(
                    'gazette_type' => 1,
                    'state_id' => $this->security->xss_clean($this->input->post('state_id')),
                    'district_id' => $this->security->xss_clean($this->input->post('district_id')),
                    'block_ulb_id' => $this->security->xss_clean($this->input->post('block_ulb_id')),
                    'address_1' => $this->security->xss_clean($this->input->post('address_1')),
                    'pin_code' => $this->security->xss_clean($this->input->post('pin_code')),
                    'govt_emp' => $this->security->xss_clean($this->input->post('govt_emp')),
                    'minor' => $this->security->xss_clean($this->input->post('minor')),
                    'name_change' => $this->security->xss_clean($this->input->post('name_change')),

                    // document details start
                    'affidavit' => $this->security->xss_clean($this->input->post('document_1')),
                    'original_newspaper' => $this->security->xss_clean($this->input->post('document_2')),
                    'notice' => $new_path,
                    'medical_cert' => $this->security->xss_clean($this->input->post('document_4')),
                    'id_proof_doc' => $this->security->xss_clean($this->input->post('document_5')),
                    'address_proof_doc' => $this->security->xss_clean($this->input->post('document_6')),
                    'deed_changing_form' => $this->security->xss_clean($this->input->post('document_7')),
                    'age_proof' => $this->security->xss_clean($this->input->post('document_8')),
                    'approval_auth_doc' => $this->security->xss_clean($this->input->post('document_9')),
                    // document details end

                    // notice part start
                    'approver' => $this->security->xss_clean($this->input->post('name_gender_approver')),
                    'place' => $this->security->xss_clean($this->input->post('name_gender_place')),
                    'notice_date' => $this->security->xss_clean($this->input->post('name_gender_notice_date')),
                    'salutation' => $this->security->xss_clean($this->input->post('name_gender_salutation')),
                    'name_for_notice' => $this->security->xss_clean($this->input->post('name_gender_notice')),
                    'address' => $this->security->xss_clean($this->input->post('name_gender_address')),

                    'old_name_two' => $this->security->xss_clean($this->input->post('old_name_two')),
                    'new_name_two' => $this->security->xss_clean($this->input->post('new_name_two')),
                    'old_gender_two' => $this->security->xss_clean($this->input->post('old_gender_two')),
                    'new_gender_two' => $this->security->xss_clean($this->input->post('new_gender_two')),
                    'new_name_three' => $this->security->xss_clean($this->input->post('new_name_three')),
                    'new_gender_three' => $this->security->xss_clean($this->input->post('new_gender_three')),
                    'signature' => $this->security->xss_clean($this->input->post('name_gender_notice_signature')),
                    // notice part end

                    'file_no' => $file_no,
                    'press_word_db_path' => $new_path,
                    'pdf_path' => $this->doc_file_for_gender_pdf,
                );
            } else if ( $name_change == 1 && $minor == 1 ) {
                $insert_array_final = array(
                    'gazette_type' => 1,
                    'state_id' => $this->security->xss_clean($this->input->post('state_id')),
                    'district_id' => $this->security->xss_clean($this->input->post('district_id')),
                    'block_ulb_id' => $this->security->xss_clean($this->input->post('block_ulb_id')),
                    'address_1' => $this->security->xss_clean($this->input->post('address_1')),
                    'pin_code' => $this->security->xss_clean($this->input->post('pin_code')),
                    'govt_emp' => $this->security->xss_clean($this->input->post('govt_emp')),
                    'minor' => $this->security->xss_clean($this->input->post('minor')),
                    'name_change' => $this->security->xss_clean($this->input->post('name_change')),

                    // document details start
                    'affidavit' => $this->security->xss_clean($this->input->post('document_1')),
                    'original_newspaper' => $this->security->xss_clean($this->input->post('document_2')),
                    'notice' => $new_path,
                    'medical_cert' => $this->security->xss_clean($this->input->post('document_4')),
                    'id_proof_doc' => $this->security->xss_clean($this->input->post('document_5')),
                    'address_proof_doc' => $this->security->xss_clean($this->input->post('document_6')),
                    'deed_changing_form' => $this->security->xss_clean($this->input->post('document_7')),
                    'age_proof' => $this->security->xss_clean($this->input->post('document_8')),
                    'approval_auth_doc' => $this->security->xss_clean($this->input->post('document_9')),
                    // document details end

                    // notice part start
                    'approver' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_approver')),
                    'place' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_place')),
                    'notice_date' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_date')),
                    'salutation' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_salutation')),
                    'name_for_notice' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_notice')),
                    'address' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_address')),
                    'son_daughter' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_son_daughter')),
                    
                    // new column need to create like 
                    'old_name_two' => $this->security->xss_clean($this->input->post('old_minor_gender_three')),
                    'new_name_two' => $this->security->xss_clean($this->input->post('new_minor_gender_three')),
                    'old_gender_two' => $this->security->xss_clean($this->input->post('old_minor_name_three')),
                    'new_gender_two' => $this->security->xss_clean($this->input->post('new_minor_name_three')),
                    
                    'gender' => $this->security->xss_clean($this->input->post('he_she_gender')),

                    // new column need to create like new_minor_name_four
                    'new_name_three' => $this->security->xss_clean($this->input->post('new_minor_name_four')),

                    'gender_his_her' => $this->security->xss_clean($this->input->post('his_her_gender')),
                    
                    // new column need to create like new_minor_gender_four
                    'new_gender_three' => $this->security->xss_clean($this->input->post('new_minor_gender_four')),

                    'signature' => $this->security->xss_clean($this->input->post('name_gender_minor_signature')),

                    'file_no' => $file_no,
                    'press_word_db_path' => $new_path,
                    'pdf_path' => $this->doc_file_for_gender_pdf,
                );
            } else if ( $name_change == 1 && $govt_emp == 1 && $minor == 0) {
                $insert_array_final = array(
                    'gazette_type' => 1,
                    'state_id' => $this->security->xss_clean($this->input->post('state_id')),
                    'district_id' => $this->security->xss_clean($this->input->post('district_id')),
                    'block_ulb_id' => $this->security->xss_clean($this->input->post('block_ulb_id')),
                    'address_1' => $this->security->xss_clean($this->input->post('address_1')),
                    'pin_code' => $this->security->xss_clean($this->input->post('pin_code')),
                    'govt_emp' => $this->security->xss_clean($this->input->post('govt_emp')),
                    'minor' => $this->security->xss_clean($this->input->post('minor')),
                    'name_change' => $this->security->xss_clean($this->input->post('name_change')),

                    // document details start
                    'affidavit' => $this->security->xss_clean($this->input->post('document_1')),
                    'original_newspaper' => $this->security->xss_clean($this->input->post('document_2')),
                    'notice' => $new_path,
                    'medical_cert' => $this->security->xss_clean($this->input->post('document_4')),
                    'id_proof_doc' => $this->security->xss_clean($this->input->post('document_5')),
                    'address_proof_doc' => $this->security->xss_clean($this->input->post('document_6')),
                    'deed_changing_form' => $this->security->xss_clean($this->input->post('document_7')),
                    'age_proof' => $this->security->xss_clean($this->input->post('document_8')),
                    'approval_auth_doc' => $this->security->xss_clean($this->input->post('document_9')),
                    // document details end

                    // notice part start
                    'approver' => $this->security->xss_clean($this->input->post('name_gender_approver')),
                    'place' => $this->security->xss_clean($this->input->post('name_gender_place')),
                    'notice_date' => $this->security->xss_clean($this->input->post('name_gender_notice_date')),
                    'salutation' => $this->security->xss_clean($this->input->post('name_gender_salutation')),
                    'name_for_notice' => $this->security->xss_clean($this->input->post('name_gender_notice')),
                    'address' => $this->security->xss_clean($this->input->post('name_gender_address')),

                    'old_name_two' => $this->security->xss_clean($this->input->post('old_name_two')),
                    'new_name_two' => $this->security->xss_clean($this->input->post('new_name_two')),
                    'old_gender_two' => $this->security->xss_clean($this->input->post('old_gender_two')),
                    'new_gender_two' => $this->security->xss_clean($this->input->post('new_gender_two')),
                    'new_name_three' => $this->security->xss_clean($this->input->post('new_name_three')),
                    'new_gender_three' => $this->security->xss_clean($this->input->post('new_gender_three')),
                    'signature' => $this->security->xss_clean($this->input->post('name_gender_notice_signature')),
                    // notice part end

                    'file_no' => $file_no,
                    'press_word_db_path' => $new_path,
                    'pdf_path' => $this->doc_file_for_gender_pdf,
                );
            }

            // echo '<br>=============================<br>';
            // echo '<pre>';
            // print_r($insert_array_final);
            // exit;

            $result = $this->Applicants_login_model->insert_change_of_gender($insert_array_final);

            if ($result) {
                audit_action_log($this->session->userdata('user_id'), 'Change of Gender', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                $this->session->set_flashdata("success", "Change of name/surname added successfully");
                $json['redirect'] = base_url() . "check_status/change_gender_status";
            } else {
                $this->session->set_flashdata("error", "Something went wrong");
                $json['redirect'] = base_url() . "check_status/change_gender_status";
            }

            // Now working on check_status/change_gender_status part.

        }

        echo json_encode($json);
        // exit;
    }

    /*
     * Convert word file to PDF GENDER
    */

    public function convert_word_to_PDF_for_gender($word_file, $file_no) {
        $word = new COM('word.application') or die('Could not initialise MS Word object.');
        $word->Documents->Open($word_file) or die($word_file);

        $upload_dir = 'uploads/change_of_gender/gender_pdf/' . $file_no . '/';

        // check whether upload directory is writable
        if ( !is_dir($upload_dir) && !is_writable($upload_dir) ) {
            mkdir($upload_dir, 0777, TRUE);
        }

        $pdf_file_db_path = 'uploads/change_of_gender/gender_pdf/' . $file_no . '/' . time() . '.pdf';
        $var = str_replace('\\', '/', FCPATH);
        $pdf_file_path = $var . $pdf_file_db_path;

        $word->ActiveDocument->ExportAsFixedFormat($pdf_file_path, 17, false, 0, 0, 0, 0, 7, true, true, 2, true, true, false);

        $word->Quit();
        $word = null;
        //echo $pdf_file_db_path; doc_file_for_gender
        return $this->doc_file_for_gender_pdf = base_url().$pdf_file_db_path;
    }

    /*
     * Upload word document Gender
     */

    public function gazette_doc_upload_for_gender() {
        if ( !empty( $_FILES['docu_3']['name'] ) && ( $_FILES['docu_3']['size'] > 0 ) ) {
            $return = $this->db->select('id')
                               ->from('gz_change_of_gender_master')
                               ->where('deleted', '0')
                               ->order_by('id', 'DESC')
                               ->limit(1)
                               ->get()->row();

            $final_file_no1 = "0001";
            if(!empty($return)) {
                $id = $return->id +1;
                $code = (string) $id;
                $len = strlen($id);

                if ($len == 1) {
                    $final_file_no1 = '000' . $id;
                } else if ($len == 2) {
                    $final_file_no1 = '00' . $id;
                } else if ($len == 3) {
                    $final_file_no1 = '0' . $id;
                } else if ($len == 4) {
                    $final_file_no1 = $id;
                }
            }

            $year = date('Y');
            $file_no = 'COM-PUB-X-' . $final_file_no1 . '-' . $year;

            $upload_dir = 'uploads/chang_of_gender/notice_in_softcopy/' . $file_no . "/";

            // check whether upload directory is writable
            if (!is_dir($upload_dir) && !is_writable($upload_dir)) {
                mkdir($upload_dir, 077, TRUE);
            }

            $config['upload_path'] = $upload_dir;
            $config['allowed_types'] = array('docx');
            $config['file_name'] = $_FILES['docu_3']['name'];
            $config['overwrite'] = true;
            $config['encrypt_name'] = TRUE;
            // 5 MB
            $config['max_size'] = '5242880';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('docu_3')) {
                $this->form_validation->set_message('handle_gazette_doc_upload_for_gender', $this->upload->display_errors('', ''));
                return false;
            } else {
                $this->upload_data['file'] = $this->upload->data();
                $this->doc_file_for_gender = $upload_dir . $this->upload_data['file']['file_name'];
                //echo $this->doc_file;exit();
                return true;
            }
        } else {
            $this->form_validation->set_message('handle_gazette_doc_upload', 'No file selected');
        }
    }

    public function upload_document_for_change_of_gender() {
        $data = array();
        $document_id = $this->security->xss_clean($this->input->post('id'));
        $file_number = $this->security->xss_clean($this->input->post('file_no'));
        if ( isset($_FILES['file']) ) {
            if ( $_FILES['file']['error'] == UPLOAD_ERR_OK ) {
                $tmp_name = $_FILES["file"]["tmp_name"];
                $name = $_FILES["file"]["name"];

                //MIME type check
                $allowed_mime_types = array('image/jpeg', 'image/png', 'application/pdf');

                $file_mime_type = mime_content_type($tmp_name);
                if ( !in_array($file_mime_type, $allowed_mime_types) ) {
                    $data = array('error' => 'Invalid file type. Only JPEG, PNG, and PDF files are allowed.');
                    echo json_encode($data);
                    return;
                }

                $return = $this->db->select('id')
                                   ->from('gz_change_of_gender_master')
                                   ->where('deleted', '0')
                                   ->order_by('id', 'DESC')
                                   ->limit(1)
                                   ->get()->row();
                $final_file_no = '0001';
                if ( !empty($return) ) {
                    $id = $return->id + 1;
                    $code = (string) $id;
                    $len = strlen($code);

                    if ( $len == 1 ) {
                        $final_file_no1 = '000' . $id;
                    } else if ( $len == 2 ) {
                        $final_file_no1 = '00' .$id;
                    } else if ( $len == 3 ) {
                        $final_file_no1 = '0' .$id;
                    } else if ( $len == 4 ) {
                        $final_file_no1 = $id;
                    }
                }

                $year = date("Y");
                

                if ( $file_number == "" ) {
                    $file_no = 'XG-' . $final_file_no1 . '-' . $year;
                } else {
                    $file_no = $file_number;
                }

                if ($document_id == 1) {
                    $folder = 'affidavit';
                } else if ($document_id == 2) {
                    $folder = 'original_newspaper';
                } else if ($document_id == 3) {
                    $folder = 'notice_in_softcopy';
                } else if ($document_id == 4) {
                    $folder = 'medical_certificate';
                } else if ($document_id == 6) {
                    $folder = 'address_proof_document';
                } else if ($document_id == 7) {
                    $folder = 'deed_changing_form';
                } else if ($document_id == 8) {
                    $folder = 'age_proof';
                } else if ($document_id == 9) {
                    $folder = 'approval_authority_document';
                }

                $upload_dir = 'uploads/change_of_gender/' . $folder . '/' . $file_no . '/';

                if (!is_dir($upload_dir) && !is_writeable($upload_dir)) {
                    mkdir($upload_dir);
                }

                $upd_file = time() . '_' . str_replace(' ', '', basename($name));
                $file_path = $upload_dir . $upd_file;

                // db path to store and find the incident images
                $DB_Image_Path = base_url() . $file_path;

                if (!file_exists($file_path)) {
                    if (move_uploaded_file($tmp_name, $file_path)) {
                        $data = array('success' => $DB_Image_Path);
                    } else {
                        $data = array('error' => 'File not uploaded');
                    }
                } else {
                    $data = array('error' => 'File already exists');
                }

            } else {
                $data = array('error' => $_FILES['file']['error']);
            }
        } else {
            $data = array('error' => 'File not attached');
        }
        echo json_encode($data);
    }

    /*
     * List of all published Gender
     */
    public function published_gender() {

        if (!$this->session->userdata('is_c&t')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('commerce_transport_department/login_ct');
        }

        if (!$this->session->userdata('force_password') && $this->session->userdata('is_c&t') == true) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('commerce_transport_department/change_password');
        }
        $data['title'] = "Published Applicant";
        $config["base_url"] = base_url() . "applicants_login/published_gender";
        if ($this->session->userdata('is_applicant')) {

            $config["total_rows"] = $this->Applicants_login_model->get_total_change_gender_count_applicant();
        } else if ($this->session->userdata('is_c&t')) {
            
            $config["total_rows"] = $this->Applicants_login_model->get_total_change_gender_count_c_and_t_published($this->session->userdata('is_verifier_approver'), $this->session->userdata('is_c&t_module'));
        }

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

        if ($this->session->userdata('is_applicant')) {

            $data['change_of_genders'] = $this->Applicants_login_model->get_total_change_of_genders_applicant($config['per_page'], $offset);
        } else if ($this->session->userdata('is_c&t')) {
            $data['change_of_genders'] = $this->Applicants_login_model->published_gender_list($config['per_page'], $offset, $this->session->userdata('is_verifier_approver'), $this->session->userdata('is_c&t_module'));
        }


        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/published_gender.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    /*
     * Filter data of Published Gender - search_for_change_of_published_gender
     */

    public function search_for_published_change_of_gender() {

        //$this->output->enable_profiler(TRUE);

        if (!$this->session->userdata('logged_in')) {
            redirect('applicants_login/index');
        }
        $data['title'] = "Change of Gender";
        $config["base_url"] = base_url() . "applicants_login/search_for_change_of_published_gender";

        $searchValue = array(
            'app_name' => trim($this->input->post('name')),
            'file_no' => trim($this->input->post('file_no')),
            'fdate' => trim($this->input->post('notice_date_form')),
            'tdate' => trim($this->input->post('notice_date_to')),
        );
        
        $inputs = $this->input->post();
        //print_r($inputs);exit;
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

        if ($this->session->userdata('is_applicant')) {

            $config["total_rows"] = $this->Applicants_login_model->get_total_change_gender_count_applicant();
        } else if ($this->session->userdata('is_c&t')) {
            
            $config["total_rows"] = $this->Applicants_login_model->get_total_change_gender_published_count_c_and_t_search($inputs,$this->session->userdata('is_verifier_approver'), $this->session->userdata('is_c&t_module'));
        }
        //echo $this->db->last_query();
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

        //$page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        if ($this->session->userdata('is_applicant')) {
            $data['change_of_genders'] = $this->Applicants_login_model->get_total_change_of_genders_applicant($config['per_page'], $offset);
        } else if ($this->session->userdata('is_c&t')) {
            $data['change_of_genders'] = $this->Applicants_login_model->change_of_gender_published_search_result( $config["per_page"], $offset, $inputs);

        }
        //echo $this->db->last_query();
        
        $data['total_status'] = $this->Applicants_login_model->get_gender_status();
        $data['inputs'] = $inputs;
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/published_gender.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    /*
     *  Gender work for C and T user start
     */

    // if you get localhost 500 error then comment the below function
    public function index_for_change_of_gender() {
        // if (!$this->session->userdata('is_c&t')) {
        //     $this->session->set_flashdata('error', 'You are not authorized!');
        //     redirect('commerce_transport_department/login_ct');
        // }

        // if (!$this->session->userdata('force_password') && $this->session->userdata('is_c&t') == true) {
        //     $this->session->set_flashdata('error', 'You must change your password after first Login!');
        //     redirect('commerce_transport_department/change_password');
        // }

        $data['title'] = "Change of Name/Surname";
        $config["base_url"] = base_url() . "applicants_login/index_for_change_of_gender";

        if ($this->session->userdata('is_applicant')) {

            $config["total_rows"] = $this->Applicants_login_model->get_total_change_gender_applicant(); // this method is completed
        } else if ($this->session->userdata('is_c&t')) {
            //die('123');
            $config["total_rows"] = $this->Applicants_login_model->get_total_change_gender_count_c_and_t($this->session->userdata('is_verifier_approver'), $this->session->userdata('is_c&t_module')); // completed
        }

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

        if ($this->session->userdata('is_applicant')) {

            $data['change_of_genders'] = $this->Applicants_login_model->get_total_change_of_genders_applicant($config['per_page'], $offset);
        } else if ($this->session->userdata('is_c&t')) {
            $data['change_of_genders'] = $this->Applicants_login_model->get_total_change_of_genders_c_and_t($config['per_page'], $offset, $this->session->userdata('is_verifier_approver'), $this->session->userdata('is_c&t_module'));
            $data['total_status'] = $this->Applicants_login_model->get_gender_status();

        }

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/index_change_of_gender.php', $data);
        $this->load->view('template/footer_applicant.php');

    }

    public function view_details_change_of_gender($id) {
        // if (!$this->session->userdata('is_c&t')) {
        //     $this->session->set_flashdata('error', 'You are not authorized!');
        //     redirect('commerce_transport_department/login_ct');
        // }
        if (!$this->Applicants_login_model->exists_change_of_gender($id)) {
            $this->session->set_flashdata('error', 'Change of Name/Surname does not exist');
            redirect('applicants_login/index_for_change_of_gender');
        }
        $data['states'] = $this->Applicants_login_model->get_states();
        $data['title'] = 'Change of Gender View details';
        // working on the below model method - completed
        $data['gz_dets'] = $this->Applicants_login_model->view_details_change_gender($id);
        $state_id = $data['gz_dets']->state_id;
        $data['districts'] = $this->Applicants_login_model->get_district_list($state_id);
        $data['tot_documents'] = $this->Applicants_login_model->get_total_document_change_of_gender();
        $data['status_list'] = $this->Applicants_login_model->get_gender_status_history($id);
        $data['docu_list'] = $this->Applicants_login_model->get_gender_document_history($id);
        $data['id'] = $id;
        $data['file_no'] = $data['gz_dets']->file_no;
        $data['per_page_value'] = $this->Applicants_login_model->get_per_page_value_change_of_gender();

        // Binary Key
        $data['binary_key'] = './binary_key/EGZ_binary.key';

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/view_details_change_of_gender.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    public function search_for_change_of_gender() {
       
        if (!$this->session->userdata('is_c&t')) {
            $this->session->set_flashdata('error', 'You are not authorized!');
            redirect('commerce_transport_department/login_ct');
        }
        $data['title'] = "Change of Gender";
        $config["base_url"] = base_url() . "applicants_login/search_for_change_of_gender";

        $searchValue = array(
            'app_name' => trim($this->input->post('name')),
            'file_no' => trim($this->input->post('file_no')),
            'status' => trim($this->input->post('status')),
            'fdate' => trim($this->input->post('notice_date_form')),
            'tdate' => trim($this->input->post('notice_date_to')),
        );
        
        $inputs = $this->input->post();
		$page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        }
        else{
            if($page==0){
              $array_items = array('app_name', 'file_no', 'status', 'notice_date_form', 'notice_date_to');
              $this->session->unset_userdata($array_items);
              $inputs =array();
            }else{
              $inputs = $this->session->userdata();
            }
        }

        if ($this->session->userdata('is_applicant')) {
            $config["total_rows"] = $this->Applicants_login_model->get_total_change_gender_applicant();
        } else if ($this->session->userdata('is_c&t')) {
            $config["total_rows"] = $this->Applicants_login_model->get_total_change_gender_count_c_and_t_search($inputs,$this->session->userdata('is_verifier_approver'), $this->session->userdata('is_c&t_module'));
        }

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

        //$page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        if ($this->session->userdata('is_applicant')) {
            $data['change_of_genders'] = $this->Applicants_login_model->get_total_change_of_genders_applicant($config['per_page'], $offset);
        } else if ($this->session->userdata('is_c&t')) {
            $data['change_of_genders'] = $this->Applicants_login_model->change_of_gender_search_result( $config["per_page"], $offset, $inputs);
        }

        $data['total_status'] = $this->Applicants_login_model->get_gender_status();
        $data['inputs'] = $inputs;
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('applicants_login/index_change_of_gender.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
    
    public function forward_change_gender_c_and_t_processor() {

        $change_gender_id = $this->security->xss_clean($this->input->post('change_gender_id'));
        $current_status = $this->security->xss_clean($this->input->post('status'));
        $remarks = $this->security->xss_clean($this->input->post('remarks'));

        if (!$this->Applicants_login_model->exists_change_of_gender($change_gender_id)) {
            $this->session->set_flashdata('error', 'Change of Gender does not exist');
            redirect('applicants_login/index_for_change_of_gender');
        }

        $status = "";

        if($this->input->post('button_type') == 'Forward'){
            $status = 2;
        }else {
            $status = 22;
        }

        // echo 'status value - ' . $status;exit;

        $update_status = array(
            'id' => $change_gender_id,
            'status' => $status,
            'remarks' => $remarks
        );
        
        if ($this->input->post('button_type') == 'Forward'){

            $result = $this->Applicants_login_model->forward_change_gender_c_and_t_processor($update_status);

            if ($result) {
                audit_action_log($this->session->userdata('user_id'), 'Change of Gender', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                $this->session->set_flashdata("success", "Change of Gender forwarded to Verifier successfully");
                redirect("applicants_login/index_for_change_of_gender");
            } else {
                $this->session->set_flashdata("error", "Something went wrong");
                redirect("applicants_login/index_for_change_of_gender");
            }
        } else {
            $result = $this->Applicants_login_model->return_to_applicant_c_and_t_processor_gender($update_status);
            if ($result) {
                audit_action_log($this->session->userdata('user_id'), 'Change of Gender', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                $this->session->set_flashdata("success", "Change of Gender application returned to applicant");
                redirect("applicants_login/index_for_change_of_gender");
            } else {
                $this->session->set_flashdata("error", "Something went wrong");
                redirect("applicants_login/index_for_change_of_gender");
            }
        }
    }

    public function reject_change_gender_c_and_t_processor() {
        $change_gender_id = $this->security->xss_clean($this->input->post('change_gender_id'));
        $remarks = $this->security->xss_clean($this->input->post('remarks'));

        if (!$this->Applicants_login_model->exists_change_of_gender($change_gender_id)) {
            $this->session->set_flashdata('error', 'Change of Gender does not exist');
            redirect('applicants_login/index_for_change_of_gender');
        }

        $update_status = array(
            'id' => $change_gender_id,
            'status' => 3,
            'remarks' => $remarks
        );

        $result = $this->Applicants_login_model->reject_change_gender_c_and_t_processor($update_status);

        if ($result) {
            audit_action_log($this->session->userdata('user_id'), 'Change of Gender', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata("success", "Change of Gender request rejected successfully");
            redirect("applicants_login/index_for_change_of_gender");
        } else {
            $this->session->set_flashdata("error", "Something went wrong");
            redirect("applicants_login/index_for_change_of_gender");
        }
    }

    public function forward_change_gender_c_and_t_verifier() {

        $change_gender_id = $this->security->xss_clean($this->input->post('change_gender_id'));
        $remarks = $this->security->xss_clean($this->input->post('remarks'));
        // echo $change_gender_id . ' - ' . $remarks;exit;
        if (!$this->Applicants_login_model->exists_change_of_gender($change_gender_id)) {
            $this->session->set_flashdata('error', 'Change of Gender does not exist');
            redirect('applicants_login/index_for_change_of_gender');
        }
        $status = "";

        if($this->input->post('button_type_veri') == 'Forward'){
            $status = 4;
        }else {
            $status = 23;
        }

        $update_status = array(
            'id' => $change_gender_id,
            'status' => $status,
            'remarks' => $remarks
        );

        // echo 'status value - ' . $status;exit;
 
        if($this->input->post('button_type_veri') == 'Forward'){

            $result = $this->Applicants_login_model->forward_change_gender_c_and_t_verifier($update_status);

            if ($result) {
                audit_action_log($this->session->userdata('user_id'), 'Change of Gender', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                // Notification
                $processers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Processor')    
                                ->where('module_id', 6)
                                ->get();
                foreach($processers->result() as $processer){
                    $processerID = $processer->id;
                   // echo $processerID;
                }    
                //die;
                $approvers = $this->db->from('gz_c_and_t')
                                        ->where('verify_approve', 'Approver')    
                                        ->where('module_id', 6)
                                        ->get()->row();
                // Processer
                $notification_data_ct = array(
                    'master_id' => $change_gender_id,
                    'module_id' => 6,
                    'user_id' => $processerID,
                    'responsible_user_id' => $approvers->id,
                    'text' => "Change of gender request forwarded to approver",
                    'is_viewed' => 0,
                    'pro_ver_app' => 3,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_ct', $notification_data_ct);
                // Applicant
                $applicantDetails = $this->db->from('gz_change_of_gender_master')
                                ->where('id', $change_gender_id)
                                ->get()->row();
                                //print_r($applicantDetails);
                $applicantID = $applicantDetails->user_id;
                $notification_data_applicant = array(
                    'master_id' => $change_gender_id,
                    'module_id' => 6,
                    'user_id' => $this->session->userdata('user_id'),
                    'applicant_user_id' => $applicantID,
                    'text' => "Change of gender request forwarded to approver",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_applicant', $notification_data_applicant);



                $this->session->set_flashdata("success", "Change of gender forwarded successfully");
                redirect("applicants_login/index_for_change_of_gender");
            } 
            else {
            $this->session->set_flashdata("error", "Something went wrong");
            redirect("applicants_login/index_for_change_of_gender");
            } 
        }else{
            $result = $this->Applicants_login_model->return_to_applicant_c_and_t_verifier_gender($update_status);

            if ($result) {
                audit_action_log($this->session->userdata('user_id'), 'Change of Gender', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                // Notification
                $processers = $this->db->from('gz_c_and_t')
                                ->where('verify_approve', 'Processor')    
                                ->where('module_id', 6)
                                ->get();
                foreach($processers->result() as $processer){
                    $processerID = $processer->id;
                    //echo $processerID;
                    // Processer
                    $notification_data_ct = array(
                        'master_id' => $change_gender_id,
                        'module_id' => 6,
                        'user_id' => $processerID,
                        'text' => "Change of gender request retrurned to applicant",
                        'is_viewed' => 0,
                        'pro_ver_app' => 3,
                        'created_by' => $this->session->userdata('user_id'),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'status' => 1,
                        'deleted' => 0
                    );
                    $this->db->insert('gz_notification_ct', $notification_data_ct);
                }    
                //die;
                
                // Applicant
                $applicantDetails = $this->db->from('gz_change_of_gender_master')
                                ->where('id', $change_gender_id)
                                ->get()->row();
                                //print_r($applicantDetails);
                $applicantID = $applicantDetails->user_id;
                $notification_data_applicant = array(
                    'master_id' => $change_gender_id,
                    'module_id' => 6,
                    'user_id' => $this->session->userdata('user_id'),
                    'applicant_user_id' => $applicantID,
                    'text' => "Change of gender request retrurned to applicant",
                    'is_viewed' => 0,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->insert('gz_notification_applicant', $notification_data_applicant);


                $this->session->set_flashdata("success", "Change of gender returned to applicant successfully");
                redirect("applicants_login/index_for_change_of_gender");
            } 
            else {
            $this->session->set_flashdata("error", "Something went wrong");
            redirect("applicants_login/index_for_change_of_gender");
           }
        }
    }

    public function reject_change_gender_c_and_t_verifier() {

        $change_gender_id = $this->security->xss_clean($this->input->post('change_gender_id'));
        $remarks = $this->security->xss_clean($this->input->post('remarks'));

        if (!$this->Applicants_login_model->exists_change_of_gender($change_gender_id)) {
            $this->session->set_flashdata('error', 'Change of Gender does not exist');
            redirect('applicants_login/index_for_change_of_gender');
        }

        $update_status = array(
            'id' => $change_gender_id,
            'status' => 5,
            'remarks' => $remarks
        );

        $result = $this->Applicants_login_model->reject_change_gender_c_and_t_verifier($update_status);

        if ($result) {
            audit_action_log($this->session->userdata('user_id'), 'Change of Gender', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata("success", "Change of gender request rejected successfully");
            redirect("applicants_login/index_for_change_of_gender");
        } else {
            $this->session->set_flashdata("error", "Something went wrong");
            redirect("applicants_login/index_for_change_of_gender");
        }
    }

    public function approve_change_gender_c_and_t_approver() {

        $change_gender_id = $this->security->xss_clean($this->input->post('change_gender_id'));
        $current_status = $this->security->xss_clean($this->input->post('status'));
        $remarks = $this->security->xss_clean($this->input->post('remarks'));

        if (!$this->Applicants_login_model->exists_change_of_gender($change_gender_id)) {
            $this->session->set_flashdata('error', 'Change of Gender does not exist');
            redirect('applicants_login/index_for_change_of_gender');
        }

        $status = "";

        if($this->input->post('button_type_approver') == 'Approve'){
            $status = 7;
        }else {
            $status = 24;
        }

        $update_status = array(
            'id' => $change_gender_id,
            'status' => $status,
            'remarks' => $remarks
        );

        // echo 'status value - ' . $status;exit;

        if($this->input->post('button_type_approver') == 'Approve'){

            $result = $this->Applicants_login_model->approve_change_gender_c_and_t_approver($update_status);

            if ($result) {
                audit_action_log($this->session->userdata('user_id'), 'Change of Gender', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                $this->session->set_flashdata("success", "Change of gender approved successfully");
                redirect("applicants_login/index_for_change_of_gender");
            } else {
                $this->session->set_flashdata("error", "Something went wrong");
                redirect("applicants_login/index_for_change_of_gender");
            }
        } else {

            $result = $this->Applicants_login_model->return_to_applicant_change_gender_c_and_t_approver($update_status);

            if ($result) {
                audit_action_log($this->session->userdata('user_id'), 'Change of Gender', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                $this->session->set_flashdata("success", "Change of gender returned to applicant successfully");
                redirect("applicants_login/index_for_change_of_gender");
            } else {
                $this->session->set_flashdata("error", "Something went wrong");
                redirect("applicants_login/index_for_change_of_gender");
            }

        }
    }

    public function reject_change_gender_c_and_t_approver() {

        $change_gender_id = $this->security->xss_clean($this->input->post('change_gender_id'));
        $remarks = $this->security->xss_clean($this->input->post('remarks'));

        if (!$this->Applicants_login_model->exists_change_of_gender($change_gender_id)) {
            $this->session->set_flashdata('error', 'Change of Gender does not exist');
            redirect('applicants_login/index_for_change_of_gender');
        }

        $update_status = array(
            'id' => $change_gender_id,
            'status' => 8,
            'remarks' => $remarks
        );
        // echo '<pre>';
        // print_r($update_status);
        // exit;
        $result = $this->Applicants_login_model->reject_change_gender_c_and_t_approver($update_status);

        if ($result) {
            audit_action_log($this->session->userdata('user_id'), 'Change of Gender', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata("success", "Change of gender request rejected successfully");
            redirect("applicants_login/index_for_change_of_gender");
        } else {
            $this->session->set_flashdata("error", "Something went wrong");
            redirect("applicants_login/index_for_change_of_gender");
        }
    }

    public function change_gender_payment_response() {
        // session_regenerate_id(true);
        $user_id = $this->session->userdata('user_id');
         header('Set-Cookie: ' . session_name() . '=' . session_id() . '; SameSite=None; Secure', false);

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            // Binary File
            $binary_file_path = './binary_key/EGZ_binary_UAT.key';

            $handle = fopen($binary_file_path, "rb");
            $secret_key = fread($handle, filesize($binary_file_path));
            // Get the message string in Response from IFMS
            $message = $this->decrypt($this->input->post('msg'), $secret_key);
            // explode the data string separated by |
            $data_array = explode("|", $message);
            // print_r($data_array);
            // exit;

            // echo '----------------<br>';
            // assign variables
            $dept_ref_no = $data_array[1];
            $total_amnt = $data_array[20];
            $data = explode('!~!', $data_array[29]);

            $chln_ref_no = $data_array[36];
            $pay_mode = $data_array[37];
            $bnk_name = $data_array[38];
            $bnk_trans_id = $data_array[39];
            $bnk_trans_stat = $data_array[40];
            $bnk_trans_msg = $data_array[41];
            $bnk_trans_time = $data_array[42];

            // INSERT INTO the main Table
            $insert_array = array(
                'change_gender_id' => $data[0],
                'file_number' => $data[1],
                'dept_ref_id' => $dept_ref_no,
                'challan_ref_id' => $chln_ref_no,
                'amount' => $total_amnt,
                'pay_mode' => $pay_mode,
                'bank_trans_id' => $bnk_trans_id,
                'bank_name' => $bnk_name,
                'bank_trans_msg' => $bnk_trans_msg,
                'bank_trans_time' => $bnk_trans_time,
                'trans_status' => $bnk_trans_stat,
                'created_at' => date('Y-m-d H:i:s', time()),
                'user_id' => $user_id
            );

            // echo "<pre>";
            // print_r($insert_array);
            // exit;
            $result = $this->Applicants_login_model->save_change_gender_trans_status($insert_array);

            if ($result && $bnk_trans_stat == 'S') {
                $this->session->set_flashdata('success', 'Payment completed successfully');
                redirect('applicants_login/index_for_change_of_gender');
            } else if ($result && $bnk_trans_stat == 'F') {
                $this->session->set_flashdata('error', 'Payment Failed');
                redirect('applicants_login/index_for_change_of_gender');
            } else if ($result && $bnk_trans_stat == 'P') {
                $this->session->set_flashdata('error', 'Payment Pending');
                redirect('applicants_login/index_for_change_of_gender');
            } else if ($result && $bnk_trans_stat == 'I') {
                $this->session->set_flashdata('error', 'Payment Initiated');
                redirect('applicants_login/index_for_change_of_gender');
            } else if ($result && $bnk_trans_stat == 'X') {
                $this->session->set_flashdata('error', 'Transaction cancelled by Applicant');
                redirect('applicants_login/index_for_change_of_gender');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong');
                redirect('applicants_login/index_for_change_of_gender');
            }
        }
    }
    
    public function edit_change_of_gender() { 

        $file_no = $this->security->xss_clean($this->input->post('file_no'));
        $file_word = $this->security->xss_clean($this->input->post('word_file'));
        $file_pdf = $this->security->xss_clean($this->input->post('pdf_file'));
        $id = $this->security->xss_clean($this->input->post('change_gender_id'));
        $val_chk_updated = $this->security->xss_clean($this->input->post('val_chk_updated'));
        log_message('debug', ' step-1 - file received'. $file_no. $file_word. $file_pdf. $id);

        $data['gz_dets'] = $this->Applicants_login_model->view_details_change_gender($id);

        if ( $data['gz_dets']->is_minor == 0 && $data['gz_dets']->is_name_change == 0 && $data['gz_dets']->govt_employee == 0 ){
            $press_word_db_path_row = 'uploads/change_of_gender/notice_updated_doc_file/' . time() . '.docx';
            $path  = str_replace('\\', '/', FCPATH);
            $press_word_db_path = $path . $press_word_db_path_row;

            $template_file = FCPATH . "uploads/sample/cog_adult_notice_sample.docx";
            
            $word_file = base_url().$press_word_db_path_row;
        } else if( $data['gz_dets']->is_minor == 1 && $data['gz_dets']->is_name_change == 0 ){
            $press_word_db_path_row = 'uploads/change_of_gender/notice_updated_doc_file/' . time() . '.docx';
            $path  = str_replace('\\', '/', FCPATH);
            $press_word_db_path = $path . $press_word_db_path_row;

            $template_file = FCPATH . "uploads/sample/cog_minor_notice_sample.docx";
            
            $word_file = base_url().$press_word_db_path_row;
        } else if( $data['gz_dets']->is_minor == 0 && $data['gz_dets']->is_name_change == 1 && $data['gz_dets']->govt_employee == 0 ){
            $press_word_db_path_row = 'uploads/change_of_gender/notice_updated_doc_file/' . time() . '.docx';
            $path  = str_replace('\\', '/', FCPATH);
            $press_word_db_path = $path . $press_word_db_path_row;

            $template_file = FCPATH . "uploads/sample/cog_adult_name_gender_change_notice_sample.docx";
            
            $word_file = base_url().$press_word_db_path_row;
        } else if( $data['gz_dets']->is_minor == 1 && $data['gz_dets']->is_name_change == 1 ){
            $press_word_db_path_row = 'uploads/change_of_gender/notice_updated_doc_file/' . time() . '.docx';
            $path  = str_replace('\\', '/', FCPATH);
            $press_word_db_path = $path . $press_word_db_path_row;

            $template_file = FCPATH . "uploads/sample/cog_minor_name_gender_change_notice_sample.docx";
            
            $word_file = base_url().$press_word_db_path_row;
        } else if( $data['gz_dets']->is_minor == 0 && $data['gz_dets']->is_name_change == 1 && $data['gz_dets']->govt_employee == 1 ){
            $press_word_db_path_row = 'uploads/change_of_gender/notice_updated_doc_file/' . time() . '.docx';
            $path  = str_replace('\\', '/', FCPATH);
            $press_word_db_path = $path . $press_word_db_path_row;

            $template_file = FCPATH . "uploads/sample/cog_adult_name_gender_change_notice_sample.docx";
            
            $word_file = base_url().$press_word_db_path_row;
        }
        
        if ( $data['gz_dets']->is_minor == 0 && $data['gz_dets']->is_name_change == 0 && $data['gz_dets']->govt_employee == 0 ){
            $insert_array = array(
                'approver' => $this->security->xss_clean($this->input->post('approver')),
                'place' => $this->security->xss_clean($this->input->post('place')),
                'notice_date' => $this->security->xss_clean($this->input->post('notice_date')),
                'salutation' => $this->security->xss_clean($this->input->post('salutation')),
                'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice')),
                'address' => $this->security->xss_clean($this->input->post('address')),
                'old_gender' => $this->security->xss_clean($this->input->post('old_gender')),
                'new_gender' => $this->security->xss_clean($this->input->post('new_gender')),
                'new_gender_one' => $this->security->xss_clean($this->input->post('new_gender_one')),
                'signature' => $this->security->xss_clean($this->input->post('signature')),
                'file_no' => $file_no,
                'press_word_db_path' => $word_file,
            );
        } else if ( $data['gz_dets']->is_minor == 1 && $data['gz_dets']->is_name_change == 0 ) {
            $insert_array = array( 
                'approver' => $this->security->xss_clean($this->input->post('approver_minor')),
                'place' => $this->security->xss_clean($this->input->post('place_minor')),
                'notice_date' => $this->security->xss_clean($this->input->post('notice_date_minor')),
                'salutation' => $this->security->xss_clean($this->input->post('salutation_minor')),
                'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice_minor')),
                'address' => $this->security->xss_clean($this->input->post('address_minor')),
                'son_daughter' => $this->security->xss_clean($this->input->post('son_daughter')),
                'gender' => $this->security->xss_clean($this->input->post('gender')),
                'gender_his_her' => $this->security->xss_clean($this->input->post('gender_his_her')),

                'old_gender' => $this->security->xss_clean($this->input->post('old_gender_minor')),
                'new_gender' => $this->security->xss_clean($this->input->post('new_gender_minor')),
                'new_gender_one' => $this->security->xss_clean($this->input->post('new_gender_one_minor')),
                'signature' => $this->security->xss_clean($this->input->post('signature_minor')),
                'file_no' => $file_no,
                'press_word_db_path' => $word_file,
            );
        } else if ( $data['gz_dets']->is_minor == 0 && $data['gz_dets']->is_name_change == 1 && $data['gz_dets']->govt_employee == 0 ) {
            $insert_array = array(
                'approver' => $this->security->xss_clean($this->input->post('name_gender_approver')),
                'place' => $this->security->xss_clean($this->input->post('name_gender_place')),
                'notice_date' => $this->security->xss_clean($this->input->post('name_gender_notice_date')),
                'salutation' => $this->security->xss_clean($this->input->post('name_gender_salutation')),
                'name_for_notice' => $this->security->xss_clean($this->input->post('name_gender_notice')),
                'address' => $this->security->xss_clean($this->input->post('name_gender_address')),
                
                'old_name_two' => $this->security->xss_clean($this->input->post('old_name_two')),
                'new_name_two' => $this->security->xss_clean($this->input->post('new_name_two')),
                'old_gender_two' => $this->security->xss_clean($this->input->post('old_gender_two')),
                'new_gender_two' => $this->security->xss_clean($this->input->post('new_gender_two')),
                'new_name_three' => $this->security->xss_clean($this->input->post('new_name_three')),
                'new_gender_three' => $this->security->xss_clean($this->input->post('new_gender_three')),
                'name_gender_notice_signature' => $this->security->xss_clean($this->input->post('name_gender_notice_signature')),
                'file_no' => $file_no,
                'press_word_db_path' => $word_file,
            );
        } else if ( $data['gz_dets']->is_minor == 1 && $data['gz_dets']->is_name_change == 1 ) {
            $insert_array = array(
                'approver' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_approver')),
                'place' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_place')),
                'notice_date' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_date')),
                'salutation' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_salutation')),
                'name_for_notice' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_notice')),
                'address' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_address')),
                
                'son_daughter' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_son_daughter')),
                'he_she_gender' => $this->security->xss_clean($this->input->post('he_she_gender')),
                'his_her_gender' => $this->security->xss_clean($this->input->post('his_her_gender')),

                'old_minor_gender_three' => $this->security->xss_clean($this->input->post('old_minor_gender_three')),
                'new_minor_gender_three' => $this->security->xss_clean($this->input->post('new_minor_gender_three')),
                'old_minor_name_three' => $this->security->xss_clean($this->input->post('old_minor_name_three')),
                'new_minor_name_three' => $this->security->xss_clean($this->input->post('new_minor_name_three')),
                'new_minor_name_four' => $this->security->xss_clean($this->input->post('new_minor_name_four')),
                'new_minor_gender_four' => $this->security->xss_clean($this->input->post('new_minor_gender_four')),
                'name_gender_minor_signature' => $this->security->xss_clean($this->input->post('name_gender_minor_signature')),
                'file_no' => $file_no,
                'press_word_db_path' => $word_file,
            );
        } else if ( $data['gz_dets']->is_minor == 0 && $data['gz_dets']->is_name_change == 1 && $data['gz_dets']->govt_employee == 1 ) {
            $insert_array = array(
                'approver' => $this->security->xss_clean($this->input->post('name_gender_approver')),
                'place' => $this->security->xss_clean($this->input->post('name_gender_place')),
                'notice_date' => $this->security->xss_clean($this->input->post('name_gender_notice_date')),
                'salutation' => $this->security->xss_clean($this->input->post('name_gender_salutation')),
                'name_for_notice' => $this->security->xss_clean($this->input->post('name_gender_notice')),
                'address' => $this->security->xss_clean($this->input->post('name_gender_address')),
                
                'old_name_two' => $this->security->xss_clean($this->input->post('old_name_two')),
                'new_name_two' => $this->security->xss_clean($this->input->post('new_name_two')),
                'old_gender_two' => $this->security->xss_clean($this->input->post('old_gender_two')),
                'new_gender_two' => $this->security->xss_clean($this->input->post('new_gender_two')),
                'new_name_three' => $this->security->xss_clean($this->input->post('new_name_three')),
                'new_gender_three' => $this->security->xss_clean($this->input->post('new_gender_three')),
                'name_gender_notice_signature' => $this->security->xss_clean($this->input->post('name_gender_notice_signature')),
                'file_no' => $file_no,
                'press_word_db_path' => $word_file,
            );
        }


        // echo '<pre>';
        // print_r($insert_array);
        // exit;
        $timestamp = strtotime($insert_array['notice_date']);

        // Creating new date format from that timestamp
        $new_date = date("jS F Y", $timestamp);

        // load from template processor
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($template_file);
        //var_dump($insert_array);exit();
        // set dynamic values provided by Govt. Press
        if ( $data['gz_dets']->is_minor == 0 && $data['gz_dets']->is_name_change == 0 && $data['gz_dets']->govt_employee == 0 ) {
            $templateProcessor->setValue('approver', $insert_array['approver']);
            $templateProcessor->setValue('place', $insert_array['place']);
            $templateProcessor->setValue('notice_date', $new_date);
            $templateProcessor->setValue('salutation', $insert_array['salutation']);
            $templateProcessor->setValue('name_for_notice', $insert_array['name_for_notice']);
            $templateProcessor->setValue('address', $insert_array['address']);

            $templateProcessor->setValue('old_gender', $insert_array['old_gender']);
            $templateProcessor->setValue('new_gender', $insert_array['new_gender']);
            $templateProcessor->setValue('new_gender_one', $insert_array['new_gender_one']);
            $templateProcessor->setValue('signature', $insert_array['signature']); 
        }
        // Minor == 1
        else if ( $data['gz_dets']->is_minor == 1 && $data['gz_dets']->is_name_change == 0 ) {
            $templateProcessor->setValue('approver', $insert_array['approver']);
            $templateProcessor->setValue('place', $insert_array['place']);
            $templateProcessor->setValue('notice_date', $new_date);
            $templateProcessor->setValue('salutation', $insert_array['salutation']);
            $templateProcessor->setValue('name_for_notice', $insert_array['name_for_notice']);
            $templateProcessor->setValue('address', $insert_array['address']);
            $templateProcessor->setValue('son_daughter', $insert_array['son_daughter']);
            $templateProcessor->setValue('gender', $insert_array['gender']);
            $templateProcessor->setValue('gender_his_her', $insert_array['gender_his_her']);

            $templateProcessor->setValue('old_gender', $insert_array['old_gender']);
            $templateProcessor->setValue('new_gender', $insert_array['new_gender']);
            $templateProcessor->setValue('new_gender_one', $insert_array['new_gender_one']);
            $templateProcessor->setValue('signature', $insert_array['signature']);
        }
        // Name and Gender change == 1
        else if ( $data['gz_dets']->is_minor == 0 && $data['gz_dets']->is_name_change == 1 && $data['gz_dets']->govt_employee == 0 ) {
            $templateProcessor->setValue('approver', $insert_array['approver']);
            $templateProcessor->setValue('place', $insert_array['place']);
            $templateProcessor->setValue('notice_date', $new_date);
            $templateProcessor->setValue('salutation', $insert_array['salutation']);
            $templateProcessor->setValue('name_for_notice', $insert_array['name_for_notice']);
            $templateProcessor->setValue('address', $insert_array['address']);

            $templateProcessor->setValue('old_name_two', $insert_array['old_name_two']);
            $templateProcessor->setValue('new_name_two', $insert_array['new_name_two']);
            $templateProcessor->setValue('old_gender_two', $insert_array['old_gender_two']);
            $templateProcessor->setValue('new_gender_two', $insert_array['new_gender_two']);
            $templateProcessor->setValue('new_name_three', $insert_array['new_name_three']);
            $templateProcessor->setValue('new_gender_three', $insert_array['new_gender_three']);
            $templateProcessor->setValue('name_gender_notice_signature', $insert_array['name_gender_notice_signature']);
        }
        // Name and Gender change == 1 &&  Minor == 1
        else if ( $data['gz_dets']->is_minor == 1 && $data['gz_dets']->is_name_change == 1 ) {
            $templateProcessor->setValue('approver', $insert_array['approver']);
            $templateProcessor->setValue('place', $insert_array['place']);
            $templateProcessor->setValue('notice_date', $new_date);
            $templateProcessor->setValue('salutation', $insert_array['salutation']);
            $templateProcessor->setValue('name_for_notice', $insert_array['name_for_notice']);
            $templateProcessor->setValue('address', $insert_array['address']);

            $templateProcessor->setValue('son_daughter', $insert_array['son_daughter']);
            $templateProcessor->setValue('he_she_gender', $insert_array['he_she_gender']);
            $templateProcessor->setValue('his_her_gender', $insert_array['his_her_gender']);

            $templateProcessor->setValue('old_minor_gender_three', $insert_array['old_minor_gender_three']);
            $templateProcessor->setValue('new_minor_gender_three', $insert_array['new_minor_gender_three']);
            $templateProcessor->setValue('old_minor_name_three', $insert_array['old_minor_name_three']);
            $templateProcessor->setValue('new_minor_name_three', $insert_array['new_minor_name_three']);
            $templateProcessor->setValue('new_minor_name_four', $insert_array['new_minor_name_four']);
            $templateProcessor->setValue('new_minor_gender_four', $insert_array['new_minor_gender_four']);
            $templateProcessor->setValue('name_gender_minor_signature', $insert_array['name_gender_minor_signature']);
        }

        // Name and Gender change == 1 && Govt employee == 1
        else if ( $data['gz_dets']->is_minor == 0 && $data['gz_dets']->is_name_change == 1 && $data['gz_dets']->govt_employee == 1 ) {
            $templateProcessor->setValue('approver', $insert_array['approver']);
            $templateProcessor->setValue('place', $insert_array['place']);
            $templateProcessor->setValue('notice_date', $new_date);
            $templateProcessor->setValue('salutation', $insert_array['salutation']);
            $templateProcessor->setValue('name_for_notice', $insert_array['name_for_notice']);
            $templateProcessor->setValue('address', $insert_array['address']);

            $templateProcessor->setValue('old_name_two', $insert_array['old_name_two']);
            $templateProcessor->setValue('new_name_two', $insert_array['new_name_two']);
            $templateProcessor->setValue('old_gender_two', $insert_array['old_gender_two']);
            $templateProcessor->setValue('new_gender_two', $insert_array['new_gender_two']);
            $templateProcessor->setValue('new_name_three', $insert_array['new_name_three']);
            $templateProcessor->setValue('new_gender_three', $insert_array['new_gender_three']);
            $templateProcessor->setValue('name_gender_notice_signature', $insert_array['name_gender_notice_signature']);
        }

        $templateProcessor->saveAs($press_word_db_path);//exit();

        $pdf_file_path_raw = $this->convert_word_to_PDF_for_gender($press_word_db_path, $file_no);
        $pdf_file_path = $this->doc_file_for_gender_pdf;

        // the below logic is final data to insert in the database

        if ( $data['gz_dets']->is_minor == 0 && $data['gz_dets']->is_name_change == 0 && $data['gz_dets']->govt_employee == 0 ) {
            // echo 'minor == 0 && name_change == 0<br>';
            $insert_array_final = array(
                'id' => $id,
                'state_id' => $this->security->xss_clean($this->input->post('state_id')),
                'district_id' => $this->security->xss_clean($this->input->post('district_id')),
                'block_ulb_id' => $this->security->xss_clean($this->input->post('block_ulb_id')),
                'address_1' => $this->security->xss_clean($this->input->post('address_1')),
                'pin_code' => $this->security->xss_clean($this->input->post('pin_code')),
                'govt_emp' => $this->security->xss_clean($this->input->post('govt_emp')),
                'minor' => $this->security->xss_clean($this->input->post('minor')),

                // need to create a column like name_change
                'name_change' => $this->security->xss_clean($this->input->post('name_change')),

                // document details start
                'affidavit' => $this->security->xss_clean($this->input->post('document_1')),
                'original_newspaper' => $this->security->xss_clean($this->input->post('document_2')),
                'notice' =>  $word_file,
                'medical_cert' => $this->security->xss_clean($this->input->post('document_4')),
                'id_proof_doc' => $this->security->xss_clean($this->input->post('document_5')),
                'address_proof_doc' => $this->security->xss_clean($this->input->post('document_6')),
                'deed_changing_form' => $this->security->xss_clean($this->input->post('document_7')),
                'age_proof' => $this->security->xss_clean($this->input->post('document_8')),
                'approval_authority' => $this->security->xss_clean($this->input->post('document_9')),
                'notice_softcopy_pdf' => $pdf_file_path,
                // document details end    

                // notice part start
                'approver' => $this->security->xss_clean($this->input->post('approver')),
                'place' => $this->security->xss_clean($this->input->post('place')),
                'notice_date' => $this->security->xss_clean($this->input->post('notice_date')), 
                'salutation' => $this->security->xss_clean($this->input->post('salutation')),
                'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice')),
                'address' => $this->security->xss_clean($this->input->post('address')),

                'old_gender' => $this->security->xss_clean($this->input->post('old_gender')),
                'new_gender' => $this->security->xss_clean($this->input->post('new_gender')),
                'new_gender_one' => $this->security->xss_clean($this->input->post('new_gender_one')),
                'signature' => $this->security->xss_clean($this->input->post('signature')),
                // notice part end

                'file_no' => $file_no,
                'press_word_db_path' => $word_file,
            );
        }  else if ( $data['gz_dets']->is_minor == 1 && $data['gz_dets']->is_name_change == 0 ) {
            // echo 'minor == 1 && name_change == 0<br>';
            $insert_array_final = array(
                'id' => $id,
                'state_id' => $this->security->xss_clean($this->input->post('state_id')),
                'district_id' => $this->security->xss_clean($this->input->post('district_id')),
                'block_ulb_id' => $this->security->xss_clean($this->input->post('block_ulb_id')),
                'address_1' => $this->security->xss_clean($this->input->post('address_1')),
                'pin_code' => $this->security->xss_clean($this->input->post('pin_code')),
                'govt_emp' => $this->security->xss_clean($this->input->post('govt_emp')),
                'minor' => $this->security->xss_clean($this->input->post('minor')),
                'name_change' => $this->security->xss_clean($this->input->post('name_change')),

                // document details start
                'affidavit' => $this->security->xss_clean($this->input->post('document_1')),
                'original_newspaper' => $this->security->xss_clean($this->input->post('document_2')),
                'notice' =>  $word_file,
                'medical_cert' => $this->security->xss_clean($this->input->post('document_4')),
                'id_proof_doc' => $this->security->xss_clean($this->input->post('document_5')),
                'address_proof_doc' => $this->security->xss_clean($this->input->post('document_6')),
                'deed_changing_form' => $this->security->xss_clean($this->input->post('document_7')),
                'age_proof' => $this->security->xss_clean($this->input->post('document_8')),
                'approval_authority' => $this->security->xss_clean($this->input->post('document_9')),
                'notice_softcopy_pdf' => $pdf_file_path,
                // document details end

                // notice part start
                'approver' => $this->security->xss_clean($this->input->post('approver_minor')),
                'place' => $this->security->xss_clean($this->input->post('place_minor')),
                'notice_date' => $this->security->xss_clean($this->input->post('notice_date_minor')),
                'salutation' => $this->security->xss_clean($this->input->post('salutation_minor')),
                'name_for_notice' => $this->security->xss_clean($this->input->post('name_for_notice_minor')),
                'address' => $this->security->xss_clean($this->input->post('address_minor')),
                'son_daughter' => $this->security->xss_clean($this->input->post('son_daughter')),
                'gender' => $this->security->xss_clean($this->input->post('gender')),

                // new column need to create like gender_his_her
                'gender_his_her' => $this->security->xss_clean($this->input->post('gender_his_her')), 

                'old_gender' => $this->security->xss_clean($this->input->post('old_gender_minor')),
                'new_gender' => $this->security->xss_clean($this->input->post('new_gender_minor')),
                'new_gender_one' => $this->security->xss_clean($this->input->post('new_gender_one_minor')),
                'signature' => $this->security->xss_clean($this->input->post('signature_minor')),
                // notice part end

                'file_no' => $file_no,
                'press_word_db_path' => $word_file,
            );
        }  else if ( $data['gz_dets']->is_minor == 0 && $data['gz_dets']->is_name_change == 1 && $data['gz_dets']->govt_employee == 0 ) {
            // echo 'minor == 0 && name_change == 1<br>';
            $insert_array_final = array(
                'id' => $id,
                'state_id' => $this->security->xss_clean($this->input->post('state_id')),
                'district_id' => $this->security->xss_clean($this->input->post('district_id')),
                'block_ulb_id' => $this->security->xss_clean($this->input->post('block_ulb_id')),
                'address_1' => $this->security->xss_clean($this->input->post('address_1')),
                'pin_code' => $this->security->xss_clean($this->input->post('pin_code')),
                'govt_emp' => $this->security->xss_clean($this->input->post('govt_emp')),
                'minor' => $this->security->xss_clean($this->input->post('minor')),
                'name_change' => $this->security->xss_clean($this->input->post('name_change')),

                // document details start
                'affidavit' => $this->security->xss_clean($this->input->post('document_1')),
                'original_newspaper' => $this->security->xss_clean($this->input->post('document_2')),
                'notice' =>  $word_file,
                'medical_cert' => $this->security->xss_clean($this->input->post('document_4')),
                'id_proof_doc' => $this->security->xss_clean($this->input->post('document_5')),
                'address_proof_doc' => $this->security->xss_clean($this->input->post('document_6')),
                'deed_changing_form' => $this->security->xss_clean($this->input->post('document_7')),
                'age_proof' => $this->security->xss_clean($this->input->post('document_8')),
                'approval_authority' => $this->security->xss_clean($this->input->post('document_9')),
                'notice_softcopy_pdf' => $pdf_file_path,
                // document details end

                // notice part start
                'approver' => $this->security->xss_clean($this->input->post('name_gender_approver')),
                'place' => $this->security->xss_clean($this->input->post('name_gender_place')),
                'notice_date' => $this->security->xss_clean($this->input->post('name_gender_notice_date')),
                'salutation' => $this->security->xss_clean($this->input->post('name_gender_salutation')),
                'name_for_notice' => $this->security->xss_clean($this->input->post('name_gender_notice')),
                'address' => $this->security->xss_clean($this->input->post('name_gender_address')),

                'old_name_two' => $this->security->xss_clean($this->input->post('old_name_two')),
                'new_name_two' => $this->security->xss_clean($this->input->post('new_name_two')),
                'old_gender_two' => $this->security->xss_clean($this->input->post('old_gender_two')),
                'new_gender_two' => $this->security->xss_clean($this->input->post('new_gender_two')),
                'new_name_three' => $this->security->xss_clean($this->input->post('new_name_three')),
                'new_gender_three' => $this->security->xss_clean($this->input->post('new_gender_three')),
                'signature' => $this->security->xss_clean($this->input->post('name_gender_notice_signature')),
                // notice part end

                'file_no' => $file_no,
                'press_word_db_path' => $word_file,
            );
        } else if ( $data['gz_dets']->is_minor == 1 && $data['gz_dets']->is_name_change == 1 ) {
            // echo 'minor == 1 && name_change == 1<br>';
            $insert_array_final = array(
                'id' => $id,
                'state_id' => $this->security->xss_clean($this->input->post('state_id')),
                'district_id' => $this->security->xss_clean($this->input->post('district_id')),
                'block_ulb_id' => $this->security->xss_clean($this->input->post('block_ulb_id')),
                'address_1' => $this->security->xss_clean($this->input->post('address_1')),
                'pin_code' => $this->security->xss_clean($this->input->post('pin_code')),
                'govt_emp' => $this->security->xss_clean($this->input->post('govt_emp')),
                'minor' => $this->security->xss_clean($this->input->post('minor')),
                'name_change' => $this->security->xss_clean($this->input->post('name_change')),

                // document details start
                'affidavit' => $this->security->xss_clean($this->input->post('document_1')),
                'original_newspaper' => $this->security->xss_clean($this->input->post('document_2')),
                'notice' =>  $word_file,
                'medical_cert' => $this->security->xss_clean($this->input->post('document_4')),
                'id_proof_doc' => $this->security->xss_clean($this->input->post('document_5')),
                'address_proof_doc' => $this->security->xss_clean($this->input->post('document_6')),
                'deed_changing_form' => $this->security->xss_clean($this->input->post('document_7')),
                'age_proof' => $this->security->xss_clean($this->input->post('document_8')),
                'approval_authority' => $this->security->xss_clean($this->input->post('document_9')),
                'notice_softcopy_pdf' => $pdf_file_path,
                // document details end

                // notice part start
                'approver' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_approver')),
                'place' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_place')),
                'notice_date' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_date')),
                'salutation' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_salutation')),
                'name_for_notice' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_notice')),
                'address' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_address')),
                'son_daughter' => $this->security->xss_clean($this->input->post('name_gender_minor_yes_son_daughter')),
                
                'old_name_two' => $this->security->xss_clean($this->input->post('old_minor_gender_three')),
                'new_name_two' => $this->security->xss_clean($this->input->post('new_minor_gender_three')),
                'old_gender_two' => $this->security->xss_clean($this->input->post('old_minor_name_three')),
                'new_gender_two' => $this->security->xss_clean($this->input->post('new_minor_name_three')),
                
                'gender' => $this->security->xss_clean($this->input->post('he_she_gender')),

                'new_name_three' => $this->security->xss_clean($this->input->post('new_minor_name_four')),

                'gender_his_her' => $this->security->xss_clean($this->input->post('his_her_gender')),
                
                'new_gender_three' => $this->security->xss_clean($this->input->post('new_minor_gender_four')),

                'signature' => $this->security->xss_clean($this->input->post('name_gender_minor_signature')),

                'file_no' => $file_no,
                'press_word_db_path' => $word_file,
            );
        } else if ( $data['gz_dets']->is_minor == 0 && $data['gz_dets']->is_name_change == 1 && $data['gz_dets']->govt_employee == 1 ) {
            // echo 'minor == 0 && name_change == 1<br>';
            $insert_array_final = array(
                'id' => $id,
                'state_id' => $this->security->xss_clean($this->input->post('state_id')),
                'district_id' => $this->security->xss_clean($this->input->post('district_id')),
                'block_ulb_id' => $this->security->xss_clean($this->input->post('block_ulb_id')),
                'address_1' => $this->security->xss_clean($this->input->post('address_1')),
                'pin_code' => $this->security->xss_clean($this->input->post('pin_code')),
                'govt_emp' => $this->security->xss_clean($this->input->post('govt_emp')),
                'minor' => $this->security->xss_clean($this->input->post('minor')),
                'name_change' => $this->security->xss_clean($this->input->post('name_change')),

                // document details start
                'affidavit' => $this->security->xss_clean($this->input->post('document_1')),
                'original_newspaper' => $this->security->xss_clean($this->input->post('document_2')),
                'notice' =>  $word_file,
                'medical_cert' => $this->security->xss_clean($this->input->post('document_4')),
                'id_proof_doc' => $this->security->xss_clean($this->input->post('document_5')),
                'address_proof_doc' => $this->security->xss_clean($this->input->post('document_6')),
                'deed_changing_form' => $this->security->xss_clean($this->input->post('document_7')),
                'age_proof' => $this->security->xss_clean($this->input->post('document_8')),
                'approval_authority' => $this->security->xss_clean($this->input->post('document_9')),
                'notice_softcopy_pdf' => $pdf_file_path,
                // document details end

                // notice part start
                'approver' => $this->security->xss_clean($this->input->post('name_gender_approver')),
                'place' => $this->security->xss_clean($this->input->post('name_gender_place')),
                'notice_date' => $this->security->xss_clean($this->input->post('name_gender_notice_date')),
                'salutation' => $this->security->xss_clean($this->input->post('name_gender_salutation')),
                'name_for_notice' => $this->security->xss_clean($this->input->post('name_gender_notice')),
                'address' => $this->security->xss_clean($this->input->post('name_gender_address')),

                'old_name_two' => $this->security->xss_clean($this->input->post('old_name_two')),
                'new_name_two' => $this->security->xss_clean($this->input->post('new_name_two')),
                'old_gender_two' => $this->security->xss_clean($this->input->post('old_gender_two')),
                'new_gender_two' => $this->security->xss_clean($this->input->post('new_gender_two')),
                'new_name_three' => $this->security->xss_clean($this->input->post('new_name_three')),
                'new_gender_three' => $this->security->xss_clean($this->input->post('new_gender_three')),
                'signature' => $this->security->xss_clean($this->input->post('name_gender_notice_signature')),
                // notice part end

                'file_no' => $file_no,
                'press_word_db_path' => $word_file,
            );
        }
        // echo '<pre>';print_r($insert_array_final);
        log_message('DEBUG', ' $insert_array_final values ' .  $insert_array_final);
        // exit;
       // echo 'abcddfg';
        $current_status = $this->Applicants_login_model->current_gender($id);
        // var_dump($current_status);
        // exit();
        $status = $current_status->current_status;
        //print($current_status->current_status);
        //exit();
        $result = $this->Applicants_login_model->edit_change_of_gender($insert_array_final, $status);

        if ($result) {
            audit_action_log($this->session->userdata('user_id'), 'Change of Gender', 'Edit', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata("success", "Change of gender updated and resubmitted successfully");
            redirect("applicants_login/index_for_change_of_gender");
        } else {
            $this->session->set_flashdata("error", "Something went wrong");
            redirect("applicants_login/index_for_change_of_gender");
        }
    }

    /*
     *  Gender work for C and T user end
     */


    /*
     *  Gender work for Admin USer Start
    */

    public function change_of_gender_govt_list() {
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
        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }
        $data['title'] = 'Change of Gender';
        $inputs = $this->input->post();

        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        } else{
            if($page == 0){
              $array_items = array('statusType', 'notice_date_form', 'notice_date_to', 'file_no');
              $this->session->unset_userdata($array_items);
              $inputs = array();
            } else {
              $inputs = $this->session->userdata();
            }
        }

        $config = array();
        $config["base_url"] = base_url() . "applicants_login/change_of_gender_govt_list";

        // working on the below model method completed
        $config["total_rows"] = $this->Applicants_login_model->get_total_cnt_govt_change_gender_pending($inputs);

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


        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();

        // working on the below model method completed
        $data["status_list"] = $this->Applicants_login_model->status_change_gender_pending_list();

        // working on the below model method
        $data['partners'] = $this->Applicants_login_model->get_total_cnt_govt_list_pending_change_gender($config['per_page'], $offset, $inputs);

        $data["inputs"] = $inputs;
    
        //echo '<pre>'; print_r($data['partner_publish']);
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('change_of_gender/pending.php', $data); // folder and the file name in view volder created
        //Continue from the Pending.php file
        $this->load->view('template/footer.php');
    }

    public function view_details_change_gender_govt($id) {

        //$this->output->enable_profiler(TRUE);

        if (!$this->Applicants_login_model->exists_change_of_gender($id)) {
            $this->session->set_flashdata('error', 'Change of Gender does not exist');
            redirect('applicants_login/change_of_gender_govt_list');
        }

        $data['title'] = 'Change of Gender View details';
        $data['id'] = $id;
        $data['pdf'] = $this->Applicants_login_model->get_pdf_path_of_change_of_gender($id);
        $data['details'] = $this->Applicants_login_model->view_details_change_gender($id);
        $data['gz_dets'] = $this->Applicants_login_model->view_details_change_gender($id);
        $data['signed_name'] = $this->session->userdata('name');
        $data['designation'] = $this->session->userdata('designation');

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('change_of_gender/view_details.php', $data); // created the view_details.php file
        $this->load->view('template/footer.php');
    }

    public function press_add_change_gender($id) {

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

        $gazette_id = $id;

        $data['title'] = 'Press Preview';
        $data['details'] = $this->Applicants_login_model->get_gazette_documents_change_gender($gazette_id);
        $data['gazette_id'] = $gazette_id;
        $data['sl_no'] = $this->Applicants_login_model->get_sl_no_change_gender();

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('applicants_login/press_add_change_gender.php', $data); // created press_add_change_gender.php view file
        $this->load->view('template/footer.php');
    }

    public function press_preview_change_gender() {

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

        // $this->form_validation->set_rules('gazette_id', 'Gazette ID', 'trim|required');
        // $this->form_validation->set_rules('sl_no', 'Sl No', 'trim|required');
        $this->form_validation->set_rules('issue_date', 'Issue Date', 'trim|required');
        $this->form_validation->set_rules('saka_month', 'Shakabda Month', 'trim|required');
        $this->form_validation->set_rules('saka_date', 'Shakabda Date', 'trim|required');
        $this->form_validation->set_rules('saka_year', 'Shakabda Year', 'trim|required');

        if ($this->form_validation->run() == false) {
            //$this->load->view('gazette/press_add', $data);
        } else {

            $gazette_id = $this->security->xss_clean($this->input->post('gazette_id'));

            // get the file submitted by Dept. user and make the required changes in the PDF file
            $gazette_docs = $this->Applicants_login_model->get_gazette_documents_change_gender($gazette_id);

            // data needs to be updated
            $array_data = array(
                'gazette_id' => $gazette_id,
                'user_id' => $this->session->userdata('user_id'),
                'sl_no' => $this->input->post('sl_no'),
                'status_id' => 17,
                'saka_date' => $this->input->post('saka_month').', '.$this->input->post('saka_date').', '.$this->input->post('saka_year'),
            );

            // get the userdata from database using model
            $result = $this->Applicants_login_model->save_preview_press_gazette_change_gender($array_data);
            $this->load->library('phpqrcode/qrlib');
            
            $qr_text = "Gazette Number:" . $this->input->post('sl_no') . " " . "Change of Gender" . " " . "Published Date:" . $this->input->post('issue_date');
            
            $folder = 'uploads/qrcodes/';
            $file = $gazette_id . "_" . md5(time()) . ".jpeg";
            $file_name = $folder . $file;
            
            QRcode::png($qr_text, $file_name);

            $word_file = $gazette_docs->notice_in_softcopy;

            $dynamic_data = array(
                'gazette_id' => $gazette_id,
                'sl_no' => $this->security->xss_clean($this->input->post('sl_no')),
                'issue_date' => $this->security->xss_clean($this->input->post('issue_date')),
                'sakabda_date' => $this->security->xss_clean($this->input->post('saka_month').', '.$this->input->post('saka_date').', '.$this->input->post('saka_year')),
                'qr_code' => base_url().$file_name
            );

            $template_file = FCPATH . './uploads/sample/cos_cop_extraordinary_sample.docx';

            // Generate Press PDF with updated values
            $press_pdf_file = $this->convert_press_word_to_PDF_change_gender($template_file, $word_file, $dynamic_data);


            // UPDATE press PDF in documents table
            $this->db->where('gz_master_id', $gazette_id);
            $this->db->update('gz_change_of_gender_document_det', array('press_pdf' => $press_pdf_file));

            if ($result) {
                redirect('applicants_login/view_details_change_gender_govt/' . $gazette_id);
            } else {
                //Put the array in a session            
                $this->session->set_flashdata('error', 'Something went wrong');
                redirect('applicants_login/change_of_gender_govt_list');
            }
        }
    }

    public function convert_press_word_to_PDF_change_gender($template_file, $word_file, $data) {

        // store in database
        $file_number = $this->Applicants_login_model->get_file_number_change_gender($data['gazette_id']);
        $press_word_db_path = './uploads/change_of_gender/press_doc/' . $file_number->file_no . '.docx';
        $press_word_path = FCPATH . $press_word_db_path;

        // Merge 2 documents
        $dm = new DocxMerge();
        $dm->merge([
            $template_file,
            $word_file
                ], $press_word_path);

        // load from template processor
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($press_word_path);
        // set dynamic values provided by Govt. Press
        $templateProcessor->setValue('sl_no', $data['sl_no']);
        $templateProcessor->setValue('issue_date', strtoupper($data['issue_date']));
        $templateProcessor->setValue('sakabda_date', $data['sakabda_date']);
        $templateProcessor->setImageValue('qr_code', array('path' => $data['qr_code'], 'width' => 50, 'height' => 50, 'ratio' => TRUE));


        $templateProcessor->saveAs($press_word_path);

        // UPDATE into documents table
        $this->db->where('gz_master_id', $data['gazette_id']);
        $this->db->update('gz_change_of_gender_document_det', array('press_notice_in_softcopy_word' => $press_word_db_path));

        // Convert to PDF using MS Word using PHP COM object
        $word = new COM("word.application") or die("Could not initialise MS Word object.");
        $word->Documents->Open($press_word_path);

        $pdf_file_db_path = './uploads/change_of_gender/press_pdf/' . $file_number->file_no . '.pdf';
        $pdf_file_path = FCPATH . $pdf_file_db_path;

        $word->ActiveDocument->ExportAsFixedFormat($pdf_file_path, 17, false, 0, 0, 0, 0, 7, true, true, 2, true, true, false);

        $word->Quit();
        $word = null;
        return $pdf_file_db_path;
    }

    public function change_gender_publish($id) {

        if (!$this->Applicants_login_model->exists_change_of_gender($id)) {
            $this->session->set_flashdata('error', 'Change of Gender does not exist');
            redirect('applicants_login/change_of_gender_govt_list');
        }

        $update_status = array(
            'is_published' => 1,
            'id' => $id,
            'status' => 11
        );

        $result = $this->Applicants_login_model->change_gender_publish($update_status);

        if ($result) {

            $this->session->set_flashdata('success', 'Change of gender gazette published successfully');
            redirect('applicants_login/change_of_gender_govt_list');
        } else {

            $this->session->set_flashdata('error', 'Something went wrong');
            redirect('applicants_login/change_of_gender_govt_list/');
        }
    }

    public function paid_change_of_gender_govt_list() {
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
        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }

        $data['title'] = 'Change of Gender';
        $inputs = $this->input->post();

        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        } else{
            if($page == 0){
              $array_items = array('statusType', 'notice_date_form', 'notice_date_to', 'file_no');
              $this->session->unset_userdata($array_items);
              $inputs = array();
            } else {
              $inputs = $this->session->userdata();
            }
        }

        $config = array();
        $config["base_url"] = base_url() . "applicants_login/paid_change_of_gender_govt_list";

        $config["total_rows"] = $this->Applicants_login_model->get_total_cnt_govt_change_gender_paid($inputs);

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

        $data["status_list"] = $this->Applicants_login_model->status_change_gender_pending_list();

        $data["inputs"] = $inputs;
        
        $data['partner_pay'] = $this->Applicants_login_model->get_total_cnt_govt_list_payed_change_gender($config['per_page'], $offset,$inputs);

        //echo '<pre>'; print_r($data['partner_publish']);
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('change_of_gender/paid.php', $data); // created paid.php view file and working on it..... completed
        $this->load->view('template/footer.php');
    }

    public function published_change_of_gender_govt_list() {
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

        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }
        $data['title'] = 'Change of Gender';
        $inputs = $this->input->post();

        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        } else{
            if($page == 0){
              $array_items = array('statusType', 'notice_date_form', 'notice_date_to', 'file_no');
              $this->session->unset_userdata($array_items);
              $inputs = array();
            } else {
              $inputs = $this->session->userdata();
            }
        }

        $config = array();
        $config["base_url"] = base_url() . "applicants_login/published_change_of_gender_govt_list";

        $config["total_rows"] = $this->Applicants_login_model->get_total_cnt_govt_change_gender_published($inputs);

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

        $data["status_list"] = $this->Applicants_login_model->status_change_gender_pending_list();

        $data["inputs"] = $inputs;

        $data['partner_publish'] = $this->Applicants_login_model->get_total_cnt_govt_list_publish_change_gender($config['per_page'], $offset,$inputs);

        //echo '<pre>'; print_r($data['partner_publish']);
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('change_of_gender/published.php', $data); // create published.php view file completed
        $this->load->view('template/footer.php');
    }

    public function forward_to_pay_change_gender($id) {

        if (!$this->Applicants_login_model->exists_change_of_gender($id)) {
            $this->session->set_flashdata('error', 'Change of Gender does not exist');
            redirect('applicants_login/change_of_gender_govt_list');
        }

        $update_status = array(
            'id' => $id,
            'status' => 9,
        );

        $result = $this->Applicants_login_model->forward_to_pay_change_gender($update_status);

        if ($result) {
            audit_action_log($this->session->userdata('user_id'), 'Change of Gender', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata("success", "Change of Gender request sent back to applicant for payment successfully");
            redirect("applicants_login/change_of_gender_govt_list");
        } else {
            $this->session->set_flashdata("error", "Something went wrong");
            redirect("applicants_login/change_of_gender_govt_list");
        }
    }

    public function get_press_signed_pdf_path_change_gender() {

        $pdf_file_name = $this->input->get('files');

        $gazette_id = $this->input->get('gazette_id');

        $type = $this->input->get('type');

        // signed PDF file path
        $signed_pdf_path = './uploads/change_of_gender/press_signed_pdf/' . $pdf_file_name;

        $data = array(
            'gazette_id' => $gazette_id,
            'press_signed_pdf_path' => $signed_pdf_path,
        );

        $result = $this->Applicants_login_model->get_press_signed_pdf_path_change_gender($data);

        if ($result) {

            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'Extraordinary Gazette', 'Press Signed', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata('success', 'Document signed successfully');
            redirect('applicants_login/view_details_change_gender_govt/' . $gazette_id);
        }
    }

    /*
     * Sign PDF
     */

     public function change_gender_sign_pdf($id) {

        if (!$this->Applicants_login_model->exists_change_of_gender($id)) {
            $this->session->set_flashdata('error', 'Change of Gender does not exist');
            redirect('applicants_login/change_of_gender_govt_list');
        }

        $update_status = array(
            'press_signed_pdf' => 1,
            'modified_at' => date("Y-m-d H:i:s", time()),
            'modified_by' => $this->session->userdata('user_id')
        );

        $result = $this->Applicants_login_model->change_gender_sign_pdf($update_status, $id);

        if ($result) {

            $this->session->set_flashdata('success', 'PDF signed successfully');
            redirect('applicants_login/view_details_change_name_govt/' . $id);
        } else {

            $this->session->set_flashdata('error', 'Something went wrong');
            redirect('applicants_login/view_details_change_name_govt/' . $id);
        }
    }

    /**
     * Filter for Change of gender govt list
     */

     public function search_change_of_gender_govt_list(){

        $data['title'] = 'Change of Gender';

        $config = array();
        $config["base_url"] = base_url() . "applicants_login/change_of_gender_govt_list";

        $searchValue = array(
            'app_name' => trim($this->input->post('name')),
            'file_no' => trim($this->input->post('file_no')),
            'fdate' => trim($this->input->post('notice_date_form')),
            'tdate' => trim($this->input->post('notice_date_to')),
        );
        
        $inputs = $this->input->post();
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

        $data['partners'] = $this->Applicants_login_model->get_total_cnt_govt_list_change_gender_search($config["per_page"], $offset, $inputs);
        $data['partner_pay'] = $this->Applicants_login_model->get_total_cnt_govt_list_payed_change_gender_search($config["per_page"], $offset, $inputs);
        $data['partner_publish'] = $this->Applicants_login_model->get_total_cnt_govt_list_publish_change_gender_search($config["per_page"], $offset, $inputs);

        //echo '<pre>'; print_r($data['partner_publish']);
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('change_of_gender/index.php', $data);
        $this->load->view('template/footer.php');

     }


    /*
     *  Gender work for  Admin USer End
    */

}
?>