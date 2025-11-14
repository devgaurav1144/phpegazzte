<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    /**
     * __construct function.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'form_validation', 'smtp', 'pagination', 'my_pagination', 'encryption', 'user_agent'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'captcha', 'custom'));
        $this->load->model(array('user_model', 'department_model', 'gazette_model', 'weekly_model'));
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {
            redirect('user/dashboard');
        } else {
            redirect('user/login');
        }
    }

    /*
     * Dashboard
     * @param 
     */

    public function dashboard() {
        //$this->output->enable_profiler(true);
        if (!$this->session->userdata('logged_in') ) {
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

        // if (!$this->session->userdata('force_password')) {
        //     $this->session->set_flashdata('error', 'You must change your password after first Login!');
        //     redirect('user/change_password');
        // }
        // page title
        $data['title'] = "Dashboard";

        // if the user is Admin
        if ($this->session->userdata('is_admin')) {
            $data['total_submitted'] = $this->gazette_model->count_total_dept_to_press_submitted_gazettes();
            $data['extra_published'] = $this->gazette_model->get_total_published_gazettes();
            $data['extra_pending'] = $this->gazette_model->get_total_unpublished_gazettes();
            $data['weekly_published'] = $this->weekly_model->get_total_published_gazettes();
            $data['weekly_pending'] = $this->weekly_model->get_total_unpublished_gazettes();

            $data['recent_extra_gazettes'] = $this->gazette_model->get_admin_recent_gazettes();
            $data['recent_weekly_gazettes'] = $this->weekly_model->get_admin_recent_weekly_gazettes();
        } else {

            $data['dept_name'] = $this->user_model->get_dept_name($this->session->userdata('user_id'));

            // statistical data
            $data['total_submitted'] = $this->gazette_model->count_total_dashboard_dept_gazettes($this->session->userdata('user_id'));
            $data['extra_published_gazettes'] = $this->gazette_model->count_total_extraordinary_published_gazettes($this->session->userdata('user_id'));
            $data['extra_unpublished_gazettes'] = $this->gazette_model->count_total_extraordinary_unpublished_gazettes($this->session->userdata('user_id'));

            $data['weekly_published'] = $this->weekly_model->get_dept_total_weekly_published_gazettes($this->session->userdata('user_id'));
            $data['weekly_pending'] = $this->weekly_model->get_dept_total_weekly_pending_gazettes($this->session->userdata('user_id'));
            // recent gazettes
            $data['recent_extra_gazettes'] = $this->gazette_model->get_dept_recent_gazettes($this->session->userdata('user_id'));
            $data['recent_weekly_gazettes'] = $this->weekly_model->get_dept_recent_weekly_gazettes($this->session->userdata('user_id'));
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('user/dashboard.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Login
     */

    public function login() {
      
        $ip_address = $this->input->ip_address();
        if ($ip_address === '::1') {
            // If the IP address is "::1", set it to "127.0.0.1"
            $ip_address = '127.0.0.1';
        }
        $browser = $this->agent->browser();
        $platform = $this->agent->platform();
        //$this->output->enable_profiler(true);
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $this->load->library('encryption');
        $data['captchaValidationMessage'] = "";
        $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
        $data['captchaImg'] = $this->botdetectcaptcha->Html();

        // set page title
        $data['title'] = "Login";
        
        // set form validation rules
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[5]|max_length[15]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[96]');

        if ($this->form_validation->run() == false) {
            $data['captchaValidationMessage'] = "";
            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
            $data['captchaImg'] = $this->botdetectcaptcha->Html();
            $this->load->view('user/login', $data);
        } else {
            
            $captcha = $this->security->xss_clean(trim($this->input->post('captcha')));
            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
            $result_captcha = $this->botdetectcaptcha->Validate($captcha);
            
            if ($result_captcha) {
                session_regenerate_id(true);
                // set variables from the form
                $mobile = $this->input->post('mobile');
                $encypt_psswrd = $this->input->post('enc_pwd');
                $nonce_value = $this->input->post('nonce');
                // $nonce_value = 'lol';

                $Encryption = new Encryption();

                $decrypted = $Encryption->decrypt($encypt_psswrd, $nonce_value);

                // get the userdata from database using model
                $result = $this->user_model->check_mobile_login($mobile, $decrypted);

                if ($result) {
                    $row = $this->user_model->get_user_data($mobile);

                    if ($row->session_id == 1) {
                        
                        $this->session->set_flashdata('error', 'User is already logged in.');
                        redirect('user/login');
                    }
                    $this->custom_logger->log('Department User <-- ' . $row->username . ' -->  logged in successfully, ' . ' IP -> ' . $ip_address . ' , ' . ' Broswer -> ' . $browser . ' , ' . ' OS -> ' . $platform, 'info');

                    if ($row->force_password == '0') {
                        $force_password = false;
                    } else {
                        $force_password = true;
                    }


                    $query = $this->db->select('*')->from('gz_users')
                                    ->where('id', $row->id)
                                    ->where('is_logged', 1)->get();

                    
                    // Store Audit Log In Database
                    audit_action_log($row->id, 'User', 'Login', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                    // set session user datas
                    $session_data = array(
                        'user_id' => $row->id,
                        'user_name' => $row->username,
                        'name' => $row->name,
                        'designation' => $row->designation,
                        'logged_in' => true,
                        'is_admin' => ($row->is_admin == 1) ? true : false,
                        'last_visited' => time(),
                        'force_password' => $force_password,
                        'is_dept' => true
                    );

                    // Update session ID in database
                    $this->user_model->update_session_id($row->id, 1);

                    //Put the array in a session            
                    $this->session->set_userdata($session_data);

                   
                    /* 
                        -> User Type Logic Implementation is Pending in here
                        -> 
                    */  
                    // user login ok
                        redirect('dashboard');
                       
                    //}
                } else {
                    $data['captchaValidationMessage'] = "Please enter correct captcha";
                    $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
                    $data['captchaImg'] = $this->botdetectcaptcha->Html();
                    //Put the message data in a session            
                    $this->session->set_flashdata('error', 'Incorrect login-ID or password');
                    $this->load->view('user/login', $data);
                }
            } else {
                $data['captchaValidationMessage'] = "Please enter correct captcha";
                $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
                $data['captchaImg'] = $this->botdetectcaptcha->Html();
                $this->load->view('user/login', $data);
            }
        }
    }

     /**
     * Logout function.
     * 
     * @access public
     * @return void
     */
    public function logout() {
       
        if (isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] === true)) {
            //Log
            $username = $this->session->userdata('user_name');
            $this->custom_logger->log("$username - User logged out", 'info');

            $this->db->where('id', $this->session->userdata('user_id'));
            $this->db->update('gz_users', array('is_logged' => 0, 'session_id' => 0));

            // $this->user_model->update_session_id($user_id, 0);

            if ($this->db->affected_rows() > 0) {

                // remove session datas
                foreach ($_SESSION as $key => $value) {
                    unset($_SESSION[$key]);
                }

                session_destroy();
                $this->session->sess_destroy();
                $this->session->set_flashdata('logout_success', 'You are logged out successfully.');
                redirect('user/login');
            } else {
                // remove session datas
                foreach ($_SESSION as $key => $value) {
                    unset($_SESSION[$key]);
                }

                session_destroy();
                $this->session->sess_destroy();
                $this->session->set_flashdata('logout_success', 'You are logged out successfully.');
                redirect('user/login');
            }
        } else {
            redirect(base_url());
        }
    }
    /*
     * Forgot Password
     */

    public function forgot_password() {
        // set page title
        $data['title'] = "Forgot Password";
        // set form validation rules
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[6]|max_length[96]|valid_email');

        if ($this->form_validation->run() == false) {
            $this->load->view('user/otp', $data);
        } else {
            // set variables from the form
            $email = $this->input->post('email');

            // check email exists or not
            $result = $this->user_model->check_email_exists($email);

            if ($result) {

                try {

                    $this->db->trans_begin();

                    $user_data = $this->user_model->get_user_email_data($email);
                    // Update password in users table
                    $random_pwd = random_string('alnum', 8);
                    $hash_password = $this->user_model->hash_password($random_pwd);
                    // Update 
                    $this->db->where('email', $email);
                    $this->db->update('gz_users', array('password' => $hash_password));
                    
                    // load SMS library will activate once live
                    $this->load->library("cdac_sms");
                    // message format
                    
                    $message = "Your Password is {$random_pwd}. Govt. of (StateName)";
                    $sms_api = new Cdac_sms();
                    $template_id = "1007279828767335328";
                    // send SMS using API
                    $sms_api->sendOtpSMS($message, $user_data->mobile, $template_id);
            
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
                            <p style=\"color: #000 !important\">Hii {$user_data->name},</p>
                            <p style=\"color: #000 !important\">
                                You have requested for forgot password request.<br/><br/> Your account password has been reset for (StateName) Press E-Gazette System.<br/>Please find the below details as the new password for (StateName) Press E-Gazette System account.<br/>
                                Email : {$email}<br/>
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

                    //$this->smtp->initialize_data($email, $email_content);
                    $this->email->from('egazette.(StateName)@gov.in', '(StateName) Press E-Gazette System');
                    $this->email->to($email);
                    $this->email->subject('Forgot Password Request for (StateName) Press E-Gazette System');
                    $this->email->message($email_content);
                    $this->email->set_newline("\r\n");
                    $this->email->send();
					
					//echo $this->email->print_debugger();exit;

                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('error', "Forgot password request not sent");
                        redirect('user/forgot_password');
                    } else {
                        $this->db->trans_commit();
                        $this->session->set_flashdata('success', 'Please check your email address for updated password.');
                        // user login ok
                        redirect('user/login');
                    }
                } catch (Exception $ex) {
                    $this->session->set_flashdata('error', "Forgot password request not sent");
                    redirect('user/forgot_password');
                }
            } else {
                $this->session->set_flashdata('error', "Invalid email");
                // user login ok
                redirect('user/forgot_password');
            }
        }
    }

    /*
     * Change Password
     * @access public
     * @param 
     * @return void
     */

    public function change_password() {

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

        // if (!$this->session->userdata('force_password')) {
        //     $this->session->set_flashdata('error', 'You must change your password after first Login!');
        //     redirect('user/change_password');
        // }

        $data['title'] = 'Change Password';

        // set form validation rules
        $this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|min_length[4]|max_length[16]');
        $this->form_validation->set_rules('password', 'New Password', 'trim|required|min_length[8]|max_length[16]|regex_match[/(^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!])(?=.*[^\w\d\s])[\w\d@#$%^&+=!]+$)/]');
        $this->form_validation->set_rules('match_password', 'Confirm Password', 'trim|required|min_length[8]|max_length[16]|matches[password]');

        if ($this->form_validation->run() == false) {
            // $this->load->view('user/change_password', $data);
        } else {

            $user_id = $this->input->post('user_id');
            $password = $this->input->post('password');
            $current_password = $this->input->post('old_password');

            $current_pwd_res = $this->db->select('password')->from('gz_users')
                            ->where('id', $user_id)
                            ->get()->row();

            if (password_verify($current_password, $current_pwd_res->password)) {

                $hash_password = $this->user_model->hash_password($password);

                // check user password is last 3 or not
                $results = $this->db->select('*')->from('gz_user_password_history')
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
                    redirect('user/change_password');
                } else {
                    // store the password data into users table
                    $password_array = array(
                        'user_id' => $user_id,
                        'password' => $hash_password,
                        'modified_at' => date('Y-m-d H:i:s', time()),
                        'force_password' => 1,
                    );

                    $result = $this->user_model->update_password($password_array);

                    if ($result) {

                        // Store Audit Log
                        audit_action_log($this->session->userdata('user_id'), 'User', 'Change Password', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                        $this->session->set_flashdata('success', "Password changed successfully");
                        redirect('user/logout');
                    } else {
                        $this->session->set_flashdata('error', "Password not changed");
                        redirect('user/change_password');
                    }
                }
            } else {
                $this->session->set_flashdata('error', 'Your current password is not matched');
                redirect('user/change_password');
            }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('user/change_password.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Display all users/nodal officers
     * @access public (Only to admin)
     * @param 
     * @return void
     */

    public function users() {
        
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

        $data['title'] = 'Nodal Officers';

        $inputs = $this->input->post();
        
        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        } else{
            if($page == 0){
              $array_items = array('dept');
              $this->session->unset_userdata($array_items);
              $inputs = array();
            } else {
              $inputs = $this->session->userdata();
            }
        }

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "user/users";
        $config["total_rows"] = $this->user_model->get_total_dept_users($inputs);

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
        $data['department_type'] = $this->gazette_model->get_department_types();
        $data['nodal_officers'] = $this->user_model->get_users_list($config["per_page"], $offset, $inputs);
        $data["inputs"] = $inputs;
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('user/nodal_officers.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Add nodal officer
     * @access public (Only Admin can add this)
     * @type POST
     * @param 
     * @return void
     */

    public function add_nodal_officers() {
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

        $data['title'] = 'Add Nodal Officer';

        // set form validation rules
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[4]|max_length[40]');
        $this->form_validation->set_rules('designation', 'Designation', 'trim|required|min_length[2]|max_length[40]');
        $this->form_validation->set_rules('dept_id', 'Department', 'trim|required|is_unique[gz_users.dept_id]', array('is_unique' => 'Another user already exists for the selected department.'));

        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[20]|is_unique[gz_users.username]', array('is_unique' => 'Username already exists for another user'));
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[6]|max_length[96]|valid_email|is_unique[gz_users.email]', array('is_unique' => 'Email already exists for another user'));
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[10]|max_length[10]|numeric|is_unique[gz_users.mobile]', array('is_unique' => 'Mobile number already exists for another user'));
        $this->form_validation->set_rules('gpf_no', 'Employee ID', 'trim|required|min_length[4]|max_length[20]|is_unique[gz_users.gpf_no]', array('is_unique' => 'HRMS ID already exists for another user'));

        $data['departments'] = $this->department_model->getDepartmentList();
        $data['designations'] = $this->department_model->get_designation_list_nodal_officer();

        if ($this->form_validation->run() == FALSE) {
            //$this->load->view('user/add_nodal_officers', $data);
        } else {
            // set variables from the form
            $name = $this->input->post('name');
            $designation = $this->input->post('designation');
            $mobile = $this->input->post('mobile');
            $email = $this->input->post('email');
            $username = $this->input->post('username');
            $dept_id = $this->input->post('dept_id');

            $random_pwd = random_string('alnum', 8);
            $hash_password = $this->user_model->hash_password($random_pwd);

            $login_ID = mt_rand(100000, 999999);

            $array_data = array(
                'login_ID' => $login_ID,
                'name' => $name,
                'designation' => $designation,
                'mobile' => $mobile,
                'email' => $email,
                'username' => $username,
                'password' => $hash_password,
                'dept_id' => $dept_id,
                'is_admin' => 0,
                'created_at' => date('Y-m-d H:i:s', time()),
                // status to active
                'status' => 1,
                'created_by' => $this->session->userdata('user_id'),
                // verified = 1
                'is_verified' => 1
            );

            // get the userdata from database using model
            $result = $this->user_model->add_dept_user($array_data);
            
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
                            <p style=\"color: #000 !important\">Hii {$name},</p>
                            <p style=\"color: #000 !important\">
                                Your account has been created for (StateName) Press E-Gazette System.<br/><br/> Please find your account details for (StateName) Press E-Gazette System.<br/>
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

                $this->email->from('egazette.(StateName)@gov.in', '(StateName) Press E-Gazette System');
                $this->email->to($email);
                $this->email->subject('User acount created for (StateName) Press E-Gazette System');
                $this->email->message($email_content);
                $this->email->set_newline("\r\n");
                $this->email->send();
                 // Store Audit Log In Database
                audit_action_log($this->session->userdata('user_id'), 'User', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                //Put the message data in a session            
                $this->session->set_flashdata('success', 'Nodal officer added successfully.');
                // user login ok
                redirect('user/users');
            }   else {
                     $json['redirect'] = base_url() . "user/add_nodal_officers";
               }
            
            // else {
            //     $this->session->set_flashdata('success', 'Nodal officer added successfully.');
            //     // user login ok
            //     redirect('user/users');
            // }
            
            
            
            // else {
            //     $json['redirect'] = base_url() . "user/add_nodal_officers";
            // }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('user/add_nodal_officers.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Registration in website frontend
     */

    public function register() {
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

        $data['title'] = 'Register Nodal Officer';

        $this->load->library('encryption');
        $data['captchaValidationMessage'] = "";
        $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
        $data['captchaImg'] = $this->botdetectcaptcha->Html();

        // set form validation rules
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[4]|max_length[40]');
        $this->form_validation->set_rules('designation', 'Designation', 'trim|required|min_length[2]|max_length[40]');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[20]|is_unique[gz_users.username]', array('is_unique' => 'Username already exists for another user'));
        $this->form_validation->set_rules('dept_id', 'Department', 'trim|required|is_unique[gz_users.dept_id]', array('is_unique' => 'Another user already exists for the department'));
        //$this->form_validation->set_rules('dept_id', 'Department', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[6]|max_length[96]|valid_email|is_unique[gz_users.email]', array('is_unique' => 'Email already exists for another user'));
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[10]|max_length[10]|numeric|is_unique[gz_users.mobile]', array('is_unique' => 'Mobile already exists for another user'));
        $this->form_validation->set_rules('gpf_no', 'HRMS ID', 'trim|required|min_length[4]|max_length[20]|is_unique[gz_users.gpf_no]', array('is_unique' => 'HRMS ID already exists for another user'));
        // $this->form_validation->set_rules('login_ID', 'Login ID', 'is_unique[gz_users.login_ID]');
        // Department list
        $data['departments'] = $this->department_model->getDepartmentList();
        // Designation list
        $data['designations'] = $this->department_model->get_designation_list_nodal_officer();

        if ($this->form_validation->run() == false) {

            // Captcha
            $data['captchaValidationMessage'] = "";
            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
            $data['captchaImg'] = $this->botdetectcaptcha->Html();

            // Department list
            $data['departments'] = $this->department_model->getDepartmentList();
            // Designation list
            $data['designations'] = $this->department_model->get_designation_list();

            //$this->load->view('user/register', $data);
        } else {

            $captcha = $this->security->xss_clean(trim($this->input->post('captcha')));
            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
            $result_captcha = $this->botdetectcaptcha->Validate($captcha);
            
            if ($result_captcha) {
                // set variables from the form
                $name = $this->input->post('name');
                $designation = $this->input->post('designation');
                $mobile = $this->input->post('mobile');
                $email = $this->input->post('email');
                $username = $this->input->post('username');
                $dept_id = $this->input->post('dept_id');
                $gpf_no = $this->input->post('gpf_no');

                $random_pwd = random_string('alnum', 8);
                $hash_password = $this->user_model->hash_password($random_pwd);
                
                // load SMS library will activate once live
                $this->load->library("cdac_sms");

                // message format
                $message = "Your Password is {$random_pwd}. Govt. of (StateName)";
                $sms_api = new Cdac_sms();
                $template_id = "1007279828767335328";
                // send SMS using API
                $sms_api->sendOtpSMS($message, $mobile, $template_id);

                $login_ID = mt_rand(100000, 999999);

                $array_data = array(
                    'login_ID' => $login_ID,
                    'name' => $name,
                    'designation' => $designation,
                    'mobile' => $mobile,
                    'email' => $email,
                    'username' => $username,
                    'password' => $hash_password,
                    'dept_id' => $dept_id,
                    'is_admin' => 0,
                    'gpf_no' => $gpf_no,
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'status' => 0,
                    'is_verified' => 0
                );

                // get the userdata from database using model
                $result = $this->user_model->register_dept_user($array_data);

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
                            <p style=\"color: #000 !important\">Hii {$name},</p>
                            <p style=\"color: #000 !important\">
                                Your account has been created for (StateName) Press E-Gazette System.<br/><br/> Please find your account details for (StateName) Press E-Gazette System and it should have been approved by the (StateName) Press.<br/>
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

                $this->email->from('egazette.(StateName)@gov.in', '(StateName) Press E-Gazette System');
                $this->email->to($email);
                $this->email->subject('User acount created for (StateName) Press E-Gazette System');
                $this->email->message($email_content);
                $this->email->set_newline("\r\n");
                $this->email->send();

                // Store Audit Log
                audit_action_log($result, 'User', 'Register', date('Y-m-d H:i:s'), $this->input->ip_address());

                //Put the message data in a session            
                $this->session->set_flashdata('success', 'You have been registered successfully. Govt. Press needs to approve your account.');
                // user login ok
                redirect('user/login');
            } else {
                $data['captchaValidationMessage'] = "Please enter correct captcha";
                $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
                $data['captchaImg'] = $this->botdetectcaptcha->Html();
                //$this->load->view('user/register', $data);
            }
        }

        // load view
        $this->load->view('user/register.php', $data);
    }

   

    /*
     * Generate OTP
     * 
     * @access private
     * @param $number_of_digit Integer
     * @return 4 digit OTP Integer
     */

    private function generate_otp($number_of_digits) {
        $generator = "1357902468";
        $result = "";
        for ($i = 1; $i <= $number_of_digits; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }
        return $result;
    }

    /*
     * Generate OTP expiry time
     * 
     * @access private
     * @param NULL
     * @return OTP expiry Datetime
     */

    private function get_otp_expiry_time() {
        // 15 minute duration
        return date("Y-m-d H:i:s", time() + 900);
    }

    /*
     * Edit nodal officer
     */

    public function edit($id) {
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

        if (!is_numeric($id) || !$this->user_model->exists($id)) {
            $this->session->set_flashdata('error', 'Nodal officer does not exists');
            redirect('user/users');
        }

        $data['title'] = 'Edit Nodal Officer';

        $data['departments'] = $this->department_model->getDepartmentList();
        $data['designations'] = $this->department_model->get_designation_list_nodal_officer();

        $data['user_details'] = $this->user_model->get_user_details($id);

        // set form validation rules
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[4]|max_length[40]');
        $this->form_validation->set_rules('designation', 'Designation', 'trim|required|min_length[2]|max_length[40]');
        $this->form_validation->set_rules('dept_id', 'Department', 'trim|required');

        $original_email = $this->db->select('email')->from('gz_users')->where('id', $id)->get()->row()->email;
        if ($this->input->post('email') != $original_email) {
            $is_email_unique = "|is_unique[gz_users.email]";
        } else {
            $is_email_unique = "";
        }

        $original_mobile = $this->db->select('mobile')->from('gz_users')->where('id', $id)->get()->row()->mobile;

        if ($this->input->post('mobile') != $original_mobile) {
            $is_mobile_unique = "|is_unique[gz_users.mobile]";
        } else {
            $is_mobile_unique = '';
        }

        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[6]|max_length[96]|valid_email' . $is_email_unique, array('is_unique' => 'Email already exists for another user.'));
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[10]|max_length[10]' . $is_mobile_unique, array('is_unique' => 'Mobile number already exists for another member.'));

        if ($this->form_validation->run() == false) {
            //$this->load->view('user/edit_nodal_officers', $data);
        } else {
            // set variables from the form
            $name = $this->input->post('name');
            $designation = $this->input->post('designation');
            $mobile = $this->input->post('mobile');
            $email = $this->input->post('email');
            $username = $this->input->post('username');
            $dept_id = $this->input->post('dept_id');
            $gpf_no = $this->input->post('gpf_no');

            $array_data = array(
                'id' => $id,
                'name' => $name,
                'designation' => $designation,
                'mobile' => $mobile,
                'email' => $email,
                'username' => $username,
                'gpf_no' => $gpf_no,
                'dept_id' => $dept_id,
                'modified_at' => date('Y-m-d H:i:s', time()),
            );

            // get the userdata from database using model
            $result = $this->user_model->edit_dept_user($array_data);

            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'User', 'Edit', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            if ($result) {
                $this->session->set_flashdata('success', 'User details updated successfully');
                redirect('user/users');
            } else {
                $this->session->set_flashdata('error', 'User details not updated');
                redirect('user/users');
            }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('user/edit_nodal_officers.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Delete nodal officer
     */

    public function delete() {
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

        $id = $this->input->post('id');

        if (!is_numeric($id) || !$this->user_model->exists($id)) {
            $this->session->set_flashdata('error', 'Nodal officer does not exists');
            redirect('user/users');
        }

        //        if ($this->user_model->linked_with_department($id)) {
        //            $this->session->set_flashdata('error', 'Nodal officer associated with department. Cannot be deleted');
        //            redirect('user/users');
        //        }

        if ($this->user_model->delete($id)) {

            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'User', 'Delete', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata('success', 'Nodal officer deleted successfully');
            redirect('user/users');
        } else {
            $this->session->set_flashdata('error', 'Nodal officer not deleted');
            redirect('user/users');
        }
    }

    public function profile() {
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
        $user_id = $this->session->userdata('user_id');

        if (!$this->user_model->exists($user_id)) {
            $this->session->set_flashdata('error', 'User does not exist');
            redirect('user/users');
        }

        $data['title'] = 'Profile';

        $data['user_data'] = $this->user_model->get_user_details($user_id);

        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[4]|max_length[40]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[8]|max_length[96]|valid_email');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[10]|max_length[10]|numeric');

        if ($this->form_validation->run() === FALSE) {
            //$this->load->view('user/profile.php', $data);
        } else {
            $update_array = array(
                'user_id' => $this->session->userdata('user_id'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'mobile' => $this->input->post('mobile')
            );
            $result = $this->user_model->update_user_profile($update_array);
            if ($result) {

                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'User Profile', 'Update', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                $this->session->set_flashdata('success', 'Profile updated successfully');
                redirect('user/profile');
            } else {
                $this->session->set_flashdata('error', 'Profile not updated');
                redirect('user/profile');
            }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('user/profile.php', $data);
        $this->load->view('template/footer.php');
    }

    public function account_approve() {

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
        // user ID
        $id = $this->input->post('user_id');
        $status = $this->input->post('status');
        $dept_id = $this->input->post('dept_id');

        if (!is_numeric($id) || !$this->user_model->exists($id)) {
            $this->session->set_flashdata('error', 'Nodal officer does not exists');
            redirect('user/users');
        }
        if($status == 0){
            $users = $this->db->from('gz_users')
                                ->where('dept_id', $dept_id)    
                                ->where('status', 1)
                                ->get()->row();
            if($users->id != ''){
                $this->session->set_flashdata('error', 'Another user already active for this department.'); 
                redirect('user/users');               
            }else{
                if ($this->user_model->account_approval($id, $status)) {
                    $user_details = $this->user_model->get_user_details_data($id);

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
                                <p style=\"color: #000 !important\">Hii {$user_details->name},</p>
                                <p style=\"color: #000 !important\">
                                    Your account has been approved for (StateName) Press E-Gazette System.<br/><br/> Now you can login to the system using the credentials shared with your registered email.<br/>
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

                    $this->email->from('egazette.(StateName)@gov.in', '(StateName) Press E-Gazette System');
                    $this->email->to($user_details->email);
                    $this->email->subject('User acount created for (StateName) Press E-Gazette System');
                    $this->email->message($email_content);
                    $this->email->set_newline("\r\n");
                    $this->email->send();

                    // Store Audit Log
                    audit_action_log($this->session->userdata('user_id'), 'User', 'Account Approval', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                    redirect('user/users'); 
                } else {
                    redirect('user/users'); 
                }
            }
        }else{
            $this->user_model->account_approval($id, $status);
            redirect('user/users');
        }
    }

    public function account_reject() {
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
        // user ID
        $id = $this->input->post('user_id');
        $remarks = $this->input->post('remarks');

        if (!is_numeric($id) || !$this->user_model->exists($id)) {
            $this->session->set_flashdata('error', 'Nodal officer does not exists');
            redirect('user/users');
        }

        $data = array(
            'id' => $id,
            'reject_remarks' => $remarks
        );

        if ($this->user_model->account_rejected($data)) {

            $user_details = $this->user_model->get_user_details_data($id);

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
                        <p style=\"color: #000 !important\">Hii {$user_details->name},</p>
                        <p style=\"color: #000 !important\">
                            Your account has been rejected for (StateName) Press E-Gazette System.<br/><br/> Please contact with Dirctorate of Printing, Stationery and Publication for any queries.<br/>
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

            $this->email->from('egazette.(StateName)@gov.in', '(StateName) Press E-Gazette System');
            $this->email->to($user_details->email);
            $this->email->subject('User acount created for (StateName) Press E-Gazette System');
            $this->email->message($email_content);
            $this->email->set_newline("\r\n");
            $this->email->send();

            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'User', 'Account Rejected', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata('success', 'Nodal officer account has been rejected');
            redirect('user/users');
        } else {
            $this->session->set_flashdata('error', 'Nodal officer account is not rejected');
            redirect('user/users');
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
     * Function to check for Automatically session timeout if the user inactivity is greater than 15 Minutes
     */

    public function check_session_expiry() {
        parent::logged();
    }

    public function mail_password($user_id) {
        // PENDING..... in increment of sms_request_count.
    //     $blocked_user = $this->user_model->get_blocked_user($user_id);
    //     // echo strtotime($blocked_user->blocked_until)."--------".time();exit;
    //     if ($blocked_user && strtotime($blocked_user->blocked_until) < time()) {
    //         $this->user_model->reset_sms_request_count($user_id);
    //     }

    //    echo $this->user_model->increment_sms_request_count($user_id) . '<br>';

    //     $request_count = $this->user_model->get_sms_request_count($user_id);
    //     echo $request_count . '<br>';
    //     if ($request_count > 3) {
    //         $is_blocked = $this->user_model->is_user_blocked($user_id);
    //         if (!$is_blocked) {
    //             // Block the user for 1 hour
    //             $this->user_model->block_user($user_id);
    //             $this->session->set_flashdata('error', 'You have exceeded the limit of SMS requests. Please try again after 1 hour.');
    //             redirect('user/users');
    //         } else {
    //             $this->session->set_flashdata('error', 'You have been blocked from sending SMS requests. Please try again after 1 hour.');
    //             redirect('user/users');
    //         }
    //     }


        $blocked_user = $this->user_model->get_blocked_user($user_id);
        $request_count = $this->user_model->get_sms_request_count($user_id);
        echo $request_count;
        $this->user_model->increment_sms_request_count($user_id);
        if ($blocked_user && strtotime($blocked_user->blocked_until) < time() && $request_count > 3) {
            $this->user_model->reset_sms_request_count($user_id);
        }
        else{
            $request_count = $this->user_model->get_sms_request_count($user_id);
            if($request_count >= 4){
                $blocked_until = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $this->user_model->block_user($user_id, $blocked_until);
                $this->session->set_flashdata('error', 'You have exceeded the limit of SMS requests. You are blocked until ' . $blocked_until . '. <br>Try after ' .$blocked_until . '.');
                redirect('user/users');
            } 

        }
        
        $user_details = $this->user_model->get_user_details_data($user_id);

        $random_pwd = random_string('alnum', 8);
        $hash_password = $this->user_model->hash_password($random_pwd);

        $upd_arr = array(
            'user_id' => $user_id,
            'password' => $hash_password,
            'modified_at' => date('Y-m-d H:i:s', time())
        );
        
        // load SMS library will activate once live
        $this->load->library("cdac_sms");
        // message format
        //$message = "Your Password is {$random_pwd}. Govt. of (StateName)";
        //$sms_api = new Cdac_sms();
        // send SMS using API
        //$sms_api->sendOtpSMS($message, $user_details->mobile);
		
		$message = "Your Password is {$random_pwd}. Govt. of (StateName)";
		$sms_api = new Cdac_sms();
		$template_id = "1007279828767335328";
		// send SMS using API
		$sms_api->sendOtpSMS($message, $user_details->mobile, $template_id);
        

        // update the user table
        $result_data = $this->user_model->update_mail_password($upd_arr);

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
                    <p style=\"color: #000 !important\">Hii {$user_details->name},</p>
                    <p style=\"color: #000 !important\">
                            Your account password has been regenerated for (StateName) Press E-Gazette System.<br/><br/>
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

        $this->email->from('egazette.(StateName)@gov.in', '(StateName) Press E-Gazette System');
        $this->email->to($user_details->email);
        $this->email->subject('User acount created for (StateName) Press E-Gazette System');
        $this->email->message($email_content);
        $this->email->set_newline("\r\n");
        $this->email->send();

        // Store Audit Log
        audit_action_log($this->session->userdata('user_id'), 'User', 'Password Regenerated', date('Y-m-d H:i:s', time()), $this->input->ip_address());

        if ($result_data) {
            $this->session->set_flashdata('success', 'Password updated successfully & SMS sent to department');
            redirect('user/users');
        } else {
            $this->session->set_flashdata('error', 'Password not updated');
            redirect('user/users');
        }
    }

}

?>