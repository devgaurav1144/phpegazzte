<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/vendor/autoload.php';

use DocxMerge\DocxMerge;

class Archival extends MY_Controller {
    
    /**
     * __construct function.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'form_validation', 'email', 'pagination', 'my_pagination'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom'));
        $this->load->model(array('Archival_model'));
        
        if (!$this->session->userdata('logged_in') || !$this->session->userdata('is_admin')) {
            $this->session->set_flashdata('error', 'Session has been expired');
            redirect('user/login');
        }
        
    }
    
    private $doc_file = '';
    
    public function extraordinary () {
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
        $data['title'] = "Extraordinary Gazette Archival";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "archival/extraordinary";
        
        $config["total_rows"] = $this->Archival_model->count_total_gazettes(1);
        
        $data['dept'] = $this->Archival_model->get_departments();
        $data['notification_types'] = $this->Archival_model->get_notification_types();
        $data['gazette_types'] = $this->Archival_model->get_gazette_types();
        
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

        $data['gazettes'] = $this->Archival_model->get_archived_gazette($config["per_page"], $offset, '1');

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('archival/extraordinary_list.php', $data);
        $this->load->view('template/footer.php');
    }
    
    public function filter () {
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
        $data['title'] = "Extraordinary Gazette Archival";
        
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
        $config["base_url"] = base_url() . "archival/filter";
        
        $config["total_rows"] = $this->Archival_model->count_total_gazettes_filter($check, $dept_id, $notif_type, $subject, $notif_number, $gz_no, $keywords, $f_date, $t_date, $week, $year);
        
        $data['dept'] = $this->Archival_model->get_departments();
        $data['notification_types'] = $this->Archival_model->get_notification_types();
        $data['gazette_types'] = $this->Archival_model->get_gazette_types();
        
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

        $data['gazettes'] = $this->Archival_model->get_archived_gazette_filter($config["per_page"], $offset, $check, $dept_id, $notif_type, $subject, $notif_number, $gz_no, $keywords, $f_date, $t_date, $week, $year);
        // $data['inputs'] = ;
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        if ($check == 1) {
            $this->load->view('archival/extraordinary_list_filter.php', $data);
        } else if ($check == 2) {
            $this->load->view('archival/weekly_list_filter.php', $data);
        }
        
        $this->load->view('template/footer.php');
    }
    
    public function weekly () {
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
        $data['title'] = "Weekly Gazette Archival";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "archival/weekly";
        
        $config["total_rows"] = $this->Archival_model->count_total_gazettes(2);

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

        $data['gazettes'] = $this->Archival_model->get_archived_gazette($config["per_page"], $offset, '2');

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('archival/weekly_list.php', $data);
        $this->load->view('template/footer.php');
    }
    
    public function extraordinary_view ($id) {
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
        if ($this->Archival_model->exists('gz_archival_extraordinary_gazettes', $id)) {
            $data['title'] = "Extraordinary Gazette View Details";
            
            $data['gz_dets'] = $this->Archival_model->view_details('1', $id);

            $this->load->view('template/header.php', $data);
            $this->load->view('template/sidebar.php');
            $this->load->view('archival/view_ext.php', $data);
            $this->load->view('template/footer.php');
            
        } else {
            $this->session->set_flashdata("success", "Gazette you are looking for does not exists");
            redirect('archival/extraordinary');
        }
    }
    
    public function edit_extraordinary_view ($id) {
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
        if ($this->Archival_model->exists('gz_archival_extraordinary_gazettes', $id)) {
            $data['title'] = "Edit Extraordinary Gazette";
            
            $data['dept'] = $this->Archival_model->get_departments();
            $data['notification_types'] = $this->Archival_model->get_notification_types();
            $data['gz_dets'] = $this->Archival_model->view_details('1', $id);
            
            $data['gz_id'] = $id;

            $this->load->view('template/header.php', $data);
            $this->load->view('template/sidebar.php');
            $this->load->view('archival/edit_extraordinary_view.php', $data);
            $this->load->view('template/footer.php');
            
        } else {
            $this->session->set_flashdata("success", "Gazette you are looking for does not exists");
            redirect('archival/extraordinary');
        }
    }
    
    public function edit_weekly_view ($id) {
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
        if ($this->Archival_model->exists('gz_archival_weekly_gazettes', $id)) {
            $data['title'] = "Edit Weekly Gazette";
            
            $data['dept'] = $this->Archival_model->get_departments();
            $data['notification_types'] = $this->Archival_model->get_notification_types();
            $data['gz_dets'] = $this->Archival_model->view_details('2', $id);
            
            $data['gz_id'] = $id;
            
            $this->load->view('template/header.php', $data);
            $this->load->view('template/sidebar.php');
            $this->load->view('archival/edit_weekly_view.php', $data);
            $this->load->view('template/footer.php');
            
        } else {
            $this->session->set_flashdata("success", "Gazette you are looking for does not exists");
            redirect('archival/weekly');
        }
    }
    
    public function delete () {
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
        $gz_id = $this->security->xss_clean($this->input->post('gz_id'));
        $check = $this->security->xss_clean($this->input->post('check'));
        if ($check == 1) {
            $table = 'gz_archival_extraordinary_gazettes';
        } else if ($check == 2) {
            $table = 'gz_archival_weekly_gazettes';
        }
            
        if ($this->Archival_model->exists($table, $gz_id) && in_array($check, [1,2])) {
            
            $update_array = array(
                'deleted' => 1,
                'modified_at' => date("Y-m-d H:i:s", time()),
                'modified_by' => $this->session->userdata('user_id')
            );
                        
            $result = $this->Archival_model->delete($update_array, $table, $gz_id);
            if ($result) {
                
                if ($check == 1) {
                    $this->session->set_flashdata('success', "Extraordinary archival gazette deleted successfully");
                    redirect("archival/extraordinary");
                } else {
                    $this->session->set_flashdata('success', "Weekly archival gazette deleted successfully");
                    redirect("archival/weekly");
                }
                
            } else {
                $this->session->set_flashdata('error', "Archival gazette not deleted successfully");
                if ($gazette_type_id == 1) {
                    redirect("archival/extraordinary");
                } else {
                    redirect("archival/weekly");
                }
            }
        } else {
            $this->session->set_flashdata("success", "Gazette you are looking for does not exists");
            if ($check == 1) {
                redirect('archival/extraordinary');
            } else {
                redirect("archival/weekly");
            }
        }
    }
    
    public function weekly_view ($id) {
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
        if ($this->Archival_model->exists('gz_archival_weekly_gazettes', $id)) {
            $data['title'] = "Extraordinary Gazette View Details";
            $data['gz_dets'] = $this->Archival_model->view_details('2', $id);

            $this->load->view('template/header.php', $data);
            $this->load->view('template/sidebar.php');
            $this->load->view('archival/view_week.php', $data);
            $this->load->view('template/footer.php');
            
        } else {
            $this->session->set_flashdata("success", "Gazette you are looking for does not exists");
            redirect('archival/weekly');
        }
    }

    public function add () {
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
        $data['title'] = 'Add Archival';
        
        $data['dept'] = $this->Archival_model->get_departments();
        $data['notification_types'] = $this->Archival_model->get_notification_types();
        $data['gazette_types'] = $this->Archival_model->get_gazette_types();
        
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('archival/add.php', $data);
        $this->load->view('template/footer.php');
    }
    
    public function unique_gz_no_add($gz_no, $data) {
        $data = explode(",", $data);
        $table = $data[0];
        $date = $data[1];
        $result = $this->Archival_model->unique_gz_no_add($gz_no, $table, $date);
        if ($result) {
            $this->form_validation->set_message('unique_gz_no_add', 'Gazette number already exists');
            return false;
        } else {
            return true;
        }
    }
    
    public function unique_gz_no_edit($gz_no, $data) {
        $data = explode(",", $data);
        $table = $data[0];
        $date = $data[1];
        $gz_id = $data[2];
        $result = $this->Archival_model->unique_gz_no_edit($gz_no, $table, $date, $gz_id);
        if ($result) {
            $this->form_validation->set_message('unique_gz_no_edit', 'Gazette number already exists');
            return false;
        } else {
            return true;
        }
    }
    
    public function insert () {
        //        echo '<pre>';print_r($this->input->post());print_r($_FILES);
        
        $json = array();
        
        $gazette_type_id = $this->security->xss_clean($this->input->post('gazette_type_id'));
        
        $this->form_validation->set_rules('gazette_type_id', 'Gazette type', 'trim|required');
        if ($gazette_type_id == 1) {
            $this->form_validation->set_rules('dept_id', 'Department', 'trim|required');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
            $this->form_validation->set_rules('doc_files', 'Gazette (official copy)', 'callback_gazette_doc_upload_ext');
            $this->form_validation->set_rules('gazette_no', 'Gazette number', 'trim|required|callback_unique_gz_no_add[' . "gz_archival_extraordinary_gazettes," . $this->security->xss_clean($this->input->post("date")) . ']');
            $this->form_validation->set_rules('notification_type_id', 'Notification type', 'trim|required');
            $this->form_validation->set_rules('notification_number', 'Notification number', 'trim|required');
        } else {
            $this->form_validation->set_rules('week_id', 'Week', 'trim|required');
            $this->form_validation->set_rules('doc_files', 'Gazette (official copy)', 'callback_gazette_doc_upload_week');
            $this->form_validation->set_rules('gazette_no', 'Gazette number', 'trim|required|callback_unique_gz_no_add[' . "gz_archival_weekly_gazettes," . $this->security->xss_clean($this->input->post("date")) . ']');
        }
        
        // $this->form_validation->set_rules('notification_number', 'Notification number', 'trim|required');
        $this->form_validation->set_rules('keywords', 'Keywords', 'trim|required');
        $this->form_validation->set_rules('date', 'Published date', 'trim|required');
        
        if ($this->form_validation->run() == false) {
            $json['error'] = $this->form_validation->error_array();
        } else {            
            
            if ($gazette_type_id == 1) {
                
                $insert_array = array(
                    'department_id'         => $this->security->xss_clean($this->input->post('dept_id')),
                    'notification_type_id'  => $this->security->xss_clean($this->input->post('notification_type_id')),
                    'subject'               => $this->security->xss_clean($this->input->post('subject')),
                    'notification_number'   => $this->security->xss_clean($this->input->post('notification_number')),
                    'gazette_number'        => $this->security->xss_clean($this->input->post('gazette_no')),
                    'keywords'              => $this->security->xss_clean($this->input->post('keywords')),
                    'published_date'        => date("Y-m-d", strtotime($this->security->xss_clean($this->input->post('date')))),
                    'gazette_file'          => $this->doc_file,
                    'created_at'            => date("Y-m-d H:i:s", time()),
                    'created_by'            => $this->session->userdata('user_id'),
                );
                
            } else {
                
                $insert_array = array(
                    'week_id'               => $this->security->xss_clean($this->input->post('week_id')),
                    // 'notification_number'   => $this->security->xss_clean($this->input->post('notification_number')),
                    'gazette_number'        => $this->security->xss_clean($this->input->post('gazette_no')),
                    'keywords'              => $this->security->xss_clean($this->input->post('keywords')),
                    'published_date'        => date("Y-m-d", strtotime($this->security->xss_clean($this->input->post('date')))),
                    'gazette_file'          => $this->doc_file,
                    'created_at'            => date("Y-m-d H:i:s", time()),
                    'created_by'            => $this->session->userdata('user_id'),
                );
                
            }
            
            $result = $this->Archival_model->insert($insert_array, $gazette_type_id);
            
            if ($result) {
                
                if ($gazette_type_id == 1) {
                    $this->session->set_flashdata('success', "Extraordinary archival gazette added successfully");
                    $json['redirect'] = base_url() . "archival/extraordinary";
                } else {
                    $this->session->set_flashdata('success', "Weekly archival gazette added successfully");
                    $json['redirect'] = base_url() . "archival/weekly";
                }
                
            } else {
                $this->session->set_flashdata('error', "Archival gazette not added successfully");
                if ($gazette_type_id == 1) {
                    $json['redirect'] = base_url() . "archival/extraordinary";
                } else {
                    $json['redirect'] = base_url() . "archival/weekly";
                }
            }
        }
        
        echo json_encode($json);
    }
    
    public function edit () {
        //        echo '<pre>';print_r($this->input->post());print_r($_FILES);
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
        $json = array();
        
        $gazette_type_id = $this->security->xss_clean($this->input->post('gazette_type_id'));
        $gz_id = $this->security->xss_clean($this->input->post('gz_id'));
        
        $this->form_validation->set_rules('gazette_type_id', 'Gazette type', 'trim|required');
        if ($gazette_type_id == 1) {
            $this->form_validation->set_rules('dept_id', 'Department', 'trim|required');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
            if (!empty($_FILES['doc_files']['name']) && ($_FILES['doc_files']['size'] > 0)) {
                $this->form_validation->set_rules('doc_files', 'Gazette (official copy)', 'callback_gazette_doc_upload_ext');
            }
            $this->form_validation->set_rules('notification_type_id', 'Notification type', 'trim|required');
            $this->form_validation->set_rules('gazette_no', 'Gazette number', 'trim|required|callback_unique_gz_no_edit[' . "gz_archival_extraordinary_gazettes," . $this->security->xss_clean($this->input->post("date")) . "," . $gz_id . ']');
        } else {
            $this->form_validation->set_rules('week_id', 'Week', 'trim|required');
            if (!empty($_FILES['doc_files']['name']) && ($_FILES['doc_files']['size'] > 0)) {
                $this->form_validation->set_rules('doc_files', 'Gazette (official copy)', 'callback_gazette_doc_upload_week');
            }
            $this->form_validation->set_rules('gazette_no', 'Gazette number', 'trim|required|callback_unique_gz_no_edit[' . "gz_archival_weekly_gazettes," . $this->security->xss_clean($this->input->post("date")) . "," . $gz_id . ']');
        }
        
        $this->form_validation->set_rules('notification_number', 'Notification number', 'trim|required');
        $this->form_validation->set_rules('keywords', 'Keywords', 'trim|required');
        $this->form_validation->set_rules('date', 'Published date', 'trim|required');
        
        
        if ($this->form_validation->run() == false) {
            $json['error'] = $this->form_validation->error_array();
        } else {            
            
            if (!empty($_FILES['doc_files']['name']) && ($_FILES['doc_files']['size'] > 0)) {
                $file = $this->doc_file;
            } else {
                $file = $this->security->xss_clean($this->input->post('existing_gz'));
            }
            
            if ($gazette_type_id == 1) {
                
                $insert_array = array(
                    'department_id'         => $this->security->xss_clean($this->input->post('dept_id')),
                    'notification_type_id'  => $this->security->xss_clean($this->input->post('notification_type_id')),
                    'subject'               => $this->security->xss_clean($this->input->post('subject')),
                    'notification_number'   => $this->security->xss_clean($this->input->post('notification_number')),
                    'gazette_number'        => $this->security->xss_clean($this->input->post('gazette_no')),
                    'keywords'              => $this->security->xss_clean($this->input->post('keywords')),
                    'published_date'        => date("Y-m-d", strtotime($this->security->xss_clean($this->input->post('date')))),
                    'gazette_file'          => $file,
                    'modified_at'            => date("Y-m-d H:i:s", time()),
                    'modified_by'            => $this->session->userdata('user_id'),
                );
                
            } else {
                
                $insert_array = array(
                    'week_id'               => $this->security->xss_clean($this->input->post('week_id')),
                    
                    'notification_number'   => $this->security->xss_clean($this->input->post('notification_number')),
                    'gazette_number'        => $this->security->xss_clean($this->input->post('gazette_no')),
                    'keywords'              => $this->security->xss_clean($this->input->post('keywords')),
                    'published_date'        => date("Y-m-d", strtotime($this->security->xss_clean($this->input->post('date')))),
                    'gazette_file'          => $file,
                    'modified_at'            => date("Y-m-d H:i:s", time()),
                    'modified_by'            => $this->session->userdata('user_id')
                );
                
            }
            
            $result = $this->Archival_model->edit($insert_array, $gazette_type_id, $gz_id);
            
            if ($result) {
                
                if ($gazette_type_id == 1) {
                    $this->session->set_flashdata('success', "Extraordinary archival gazette updated successfully");
                    $json['redirect'] = base_url() . "archival/extraordinary";
                } else {
                    $this->session->set_flashdata('success', "Weekly archival gazette updated successfully");
                    $json['redirect'] = base_url() . "archival/weekly";
                }
                
            } else {
                $this->session->set_flashdata('error', "Archival gazette not added successfully");
                if ($gazette_type_id == 1) {
                    $json['redirect'] = base_url() . "archival/extraordinary";
                } else {
                    $json['redirect'] = base_url() . "archival/weekly";
                }
            }
        }
        
        echo json_encode($json);
    }
    
    public function gazette_doc_upload_ext () {
        
        if (!empty($_FILES['doc_files']['name']) && ($_FILES['doc_files']['size'] > 0)) {
            
            $upload_dir = "F:/uploads_archival/archival_gazettes/extraordinary/"; // Testing
            
            if (!is_dir($upload_dir) && !is_writable($upload_dir)) {
                mkdir($upload_dir, 0777, TRUE);
            }
            
            $config['upload_path'] = $upload_dir;
            $config['allowed_types'] = array('pdf');
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
                //echo $this->doc_file;exit();
                return true;
            }
        }
    }
    
    public function gazette_doc_upload_week () {
        
        if (!empty($_FILES['doc_files']['name']) && ($_FILES['doc_files']['size'] > 0)) {
            
            $upload_dir = "F:/uploads_archival/archival_gazettes/weekly/";
            
            if (!is_dir($upload_dir) && !is_writable($upload_dir)) {
                mkdir($upload_dir, 0777, TRUE);
            }
            
            $config['upload_path'] = $upload_dir;
            $config['allowed_types'] = array('pdf');
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
                //echo $this->doc_file;exit();
                return true;
            }
        }
    }

    public function download_gazette($file_id) {
        
        $file_data = $this->Archival_model->get_gazette_file($file_id);
        echo($file_id);exit;

        if ($file_data) {
            // Get the full path to the file
            $file_path = $file_data->gazette_file;
            $filename = basename($file_path); // Extract the filename from the path
    
            // Check if the file exists
            if (file_exists($file_path)) {
                // Set headers to force download
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file_path));
    
                // Read the file and output it to the browser
                readfile($file_path);
                exit;
            } else {
                // If the file is not found, show an error message
                echo 'File not found.';
            }
        } else {
            // If the file data is not found, show an error message
            show_404();
        }
    }


}

?>