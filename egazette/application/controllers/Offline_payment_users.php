<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Offline_payment_users extends MY_Controller {

    /**
     * __construct function.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'pagination', 'my_pagination','form_validation', 'smtp', 'encryption'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom', 'captcha'));
        $this->load->model(array('Offline_payment_users_model'));
    }
    
    /*
     * index function
     */
	 
        public function index() {
            if (!$this->session->userdata('logged_in') || !$this->session->userdata('is_admin')) {
                $this->session->set_flashdata('error', 'You are not authorized to access the page');
                redirect('user/login');
            }
    
            if (!$this->session->userdata('force_password')) {
                $this->session->set_flashdata('error', 'You must change your password after first Login!');
                redirect('user/change_password');
            }

            $this->load->view('offline_approver/login');

            
        }
		
		public function login_offline(){
        $data['title'] = "Offline Approver Login";
        
        $data['captchaValidationMessage'] = "";
        $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
        $data['captchaImg'] = $this->botdetectcaptcha->Html();
        
        // set form validation rules
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[5]|max_length[10]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[96]');
        
        if ($this->form_validation->run() == false) {
            
            // Captcha
            $data['captchaValidationMessage'] = "";
            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
            $data['captchaImg'] = $this->botdetectcaptcha->Html();
            $this->load->view('offline_approver/login', $data);
            
        } else {
            
            $captcha = $this->security->xss_clean(trim($this->input->post('captcha')));
            $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
            $result_captcha = $this->botdetectcaptcha->Validate($captcha);
            
            if ($result_captcha) { 
                // set variables from the form
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                $password = $this->security->xss_clean($this->input->post('password'));

                $encypt_psswrd = $this->security->xss_clean($this->input->post('enc_pwd'));
                $nonce_value = 'lol';

                $Encryption = new Encryption();

                $decrypted = $Encryption->decrypt($encypt_psswrd, $nonce_value);
				
                // get the userdata from database using model
                $result = $this->Offline_payment_users_model->check_mobile_login($mobile, $decrypted);

                if ($result) { 
                    $row = $this->Offline_payment_users_model->get_user_data($mobile);
                    $query = $this->db->select('*')->from('gz_offline_approver_users')
                                                    ->where('id', $row->id)
                                                    ->where('is_logged', 1)->get();
                    if ($query->num_rows() > 0) {
                            //Put the message data in a session            
                            $this->session->set_flashdata('error', 'Current user already logged in to another system.');
                            $this->load->view('offline_approver/login', $data);
                    } else {
                        audit_action_log($row->id, 'Offline Approver User', 'Login', date('Y-m-d H:i:s', time()), $this->input->ip_address());

                        $session_data = array(
                            'user_id' => $row->id,
                            'name' => $row->user_name,
                            'logged_in' => true,
                            'is_offline_approver' => true,
                            'is_verifier_approver' => $row->verify_approve,
							'is_igr' => false,
                            'is_applicant' => false,
                            'is_c&t' => false
                        );
                        $this->session->set_userdata($session_data);
                        // Update the user table to if the user is admin user 
                        // and has already logged in to the system.
    //                    $this->db->where('id', $row->id);
    //                    $this->db->update('gz_igr_users', array('is_logged' => 1));

                        // user login ok
                        redirect('applicants_login/dashboard');
                    }
                } else {
                    $data['captchaValidationMessage'] = "";
                    $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
                    $data['captchaImg'] = $this->botdetectcaptcha->Html();
                    //Put the message data in a session            
                    $this->session->set_flashdata('error', 'Incorrect login ID or password');
                    $this->load->view('offline_approver/login', $data);
                } 
            } else {
                $data['captchaValidationMessage'] = "Please enter correct captcha";
                $this->load->library('botdetect/BotDetectCaptcha', array('captchaConfig' => 'LoginCaptcha'));
                $data['captchaImg'] = $this->botdetectcaptcha->Html();
                $this->load->view('offline_approver/login', $data);
            }
        }
    }
	
	public function offline_payment_list()
	{
		$data['title'] = 'Offline Payments List';
		
		$config["base_url"] = base_url() . "make_payment/change_name";
        $config["total_rows"] = $this->Offline_payment_users_model->get_total_offline_pay_count_applicant();

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
		
		$data['offline_pay_list'] = $this->Offline_payment_users_model->get_total_offline_payment_applicant($config['per_page'], $offset);
		
		if($this->input->post('btn_get_details'))
		{
			$id = $this->input->post('btn_hid_id');
			$data['user_details'] = $this->Offline_payment_users_model->view_details_offline_payment($id);
			$data['record_id'] = $id;
			$data['file_no'] = $hid_file_num;
			
		}
		
		if($this->input->post('btn_off_pay_accept'))
		{
			$this->form_validation->set_rules('pay_mode', 'Payment Mode', 'trim|required');
			$this->form_validation->set_rules('ref_no', 'Ref. No', 'trim|required');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			$this->form_validation->set_rules('hid_record_id', 'record id', 'trim|required');
			$this->form_validation->set_rules('hid_file_num', 'file no', 'trim|required');
			
			if ($this->form_validation->run() == false)
			{
				
			}
			else{
				$hid_record_id = $this->input->post('hid_record_id');
				$hid_file_num = $this->input->post('hid_file_num');
				$pay_mode = $this->input->post('pay_mode');
				$ref_no = $this->input->post('ref_no');
				$amount = $this->input->post('amount');
				
				$payment_entry = $this->Offline_payment_users_model->save_change_name_surname_offline_trans_status($hid_record_id,$hid_file_num,$pay_mode,$ref_no,$amount);
				if($payment_entry == TRUE)
				{
					$this->session->set_flashdata("success", "payment received successfully");
					redirect('offline_payment_users/offline_payment_list'); 
				}
				else if($payment_entry == FALSE)
				{
					$this->session->set_flashdata("error", "Something went wrong on payment receive");
					redirect('offline_payment_users/offline_payment_list'); 
				}
			}
		}
		
		$this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('offline_approver/offline_pay_list.php', $data);
        $this->load->view('template/footer_applicant.php');
	}

    public function partnership_offline_payment_list()
    {
        $data['title'] = 'Offline Payments List';

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "make_payment/change_partnership";

        $config["total_rows"] = $this->Offline_payment_users_model->get_total_parter_count();

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

        $data['partners'] = $this->Offline_payment_users_model->get_total_parter($config['per_page'], $offset);

        if($this->input->post('btn_get_details'))
        {
            $id = $this->input->post('btn_hid_id');
            $data['user_details'] = $this->Offline_payment_users_model->View_details_partnership_offline($id);
            $data['record_id'] = $id;
            $data['file_no'] = $hid_file_num;
            
        }

        if($this->input->post('btn_off_pay_accept'))
        {
            $this->form_validation->set_rules('pay_mode', 'Payment Mode', 'trim|required');
            $this->form_validation->set_rules('ref_no', 'Ref. No', 'trim|required');
            $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
            $this->form_validation->set_rules('hid_record_id', 'record id', 'trim|required');
            $this->form_validation->set_rules('hid_file_num', 'file no', 'trim|required');
            
            if ($this->form_validation->run() == false)
            {
                
            }
            else{
                $hid_record_id = $this->input->post('hid_record_id');
                $hid_file_num = $this->input->post('hid_file_num');
                $pay_mode = $this->input->post('pay_mode');
                $ref_no = $this->input->post('ref_no');
                $amount = $this->input->post('amount');
                
                $payment_entry = $this->Offline_payment_users_model->save_change_partnership_offline_trans_status($hid_record_id,$hid_file_num,$pay_mode,$ref_no,$amount);
                if($payment_entry == TRUE)
                {
                    $this->session->set_flashdata("success", "payment received successfully");
                    redirect('offline_payment_users/partnership_offline_payment_list'); 
                }
                else if($payment_entry == FALSE)
                {
                    $this->session->set_flashdata("error", "Something went wrong on payment receive");
                    redirect('offline_payment_users/partnership_offline_payment_list'); 
                }
            }
        }

        //$data['total_status'] = $this->Applicants_login_model->get_status_partnership();
        // echo "<pre>";print_r($data['partners']);echo "</pre>";exit();
        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('offline_approver/partnership_offline_pay_list.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    public function departmental_offline_payment_list()
    {
        $data['title'] = "Extraordinary Gazette";
        
        $status = [1, 2, 4, 7, 8];
        
        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "make_payment/make_payment_department_offline";
        
        $config["total_rows"] = $this->Offline_payment_users_model->count_total_gazettes_offline_pay($status);

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

        //$data['gazettes_published'] = $this->Extraordinary_poc_model->get_total_gazettes_published($config["per_page"], $offset);

        $data['gazettes_unpublished'] = $this->Offline_payment_users_model->get_total_gazettes_unpublished_offline_pay($status, $config["per_page"], $offset);
//echo "<pre>"; print_r($data['gazettes_unpublished']); echo "</pre>"; exit;

        if($this->input->post('btn_get_details'))
        {
            $id = $this->input->post('btn_hid_id');
            $data['user_details'] = $this->Offline_payment_users_model->get_gazette_details($id);
            //echo "<pre>"; print_r($data['user_details']); echo "</pre>"; exit;
            $data['record_id'] = $id;
            $data['file_no'] = $hid_file_num;
            
        }

        if($this->input->post('btn_off_pay_accept'))
        {
            $this->form_validation->set_rules('pay_mode', 'Payment Mode', 'trim|required');
            $this->form_validation->set_rules('ref_no', 'Ref. No', 'trim|required');
            $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
            $this->form_validation->set_rules('hid_record_id', 'record id', 'trim|required');
            $this->form_validation->set_rules('hid_dept_id', 'Dept ID', 'trim|required');
            $this->form_validation->set_rules('hid_gazette_id', 'Gazette ID', 'trim|required');
            
            if ($this->form_validation->run() == false)
            {
                
            }
            else{
                $hid_record_id = $this->input->post('hid_record_id');
                $hid_dept_id = $this->input->post('hid_dept_id');
                $hid_gazette_id = $this->input->post('hid_gazette_id');
                $pay_mode = $this->input->post('pay_mode');
                $ref_no = $this->input->post('ref_no');
                $amount = $this->input->post('amount');
                
                $payment_entry = $this->Offline_payment_users_model->save_departmental_offline_payment($hid_record_id,$hid_dept_id,$hid_gazette_id,$pay_mode,$ref_no,$amount);
                if($payment_entry == TRUE)
                {
                    $this->session->set_flashdata("success", "payment received successfully");
                    redirect('offline_payment_users/departmental_offline_payment_list'); 
                }
                else if($payment_entry == FALSE)
                {
                    $this->session->set_flashdata("error", "Something went wrong on payment receive");
                    redirect('offline_payment_users/departmental_offline_payment_list'); 
                }
            }
        }

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('offline_approver/departmental_offline_pay_list.php', $data);
        $this->load->view('template/footer_applicant.php');
    }
	
	 public function logout() {
        if (!$this->session->userdata('logged_in')) {
            redirect('igr_user/login');
        }
        // create the data object
        //$data = new stdClass();
        if (isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] === true)) {
			
            $this->db->where('id', $this->session->userdata('user_id'));
            $this->db->update('gz_offline_approver_users', array('is_logged' => 0));
			
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
                redirect('applicants_login/index');
            }
            
        } else {
            redirect(base_url());
        }
    }
	 
}
?>