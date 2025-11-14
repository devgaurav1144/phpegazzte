<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cms_System extends MY_Controller {

    /**
     * __construct function.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'form_validation'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom'));
        $this->load->model(array('Cms_model'));
    }

    public function about_us_content() {
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
        $data['title'] = "About Us";

        $data['aboutDetails'] = $this->Cms_model->get_about_us();
        $this->form_validation->set_rules('about_desc', 'Description', 'trim|required');
        if ($this->form_validation->run() == false) {
            
        } else {
            $todayDate = date('Y-m-d H:i:s');
            $array_data = array(
                'cms_type' => 'about_us',
                'cms_desc' => $this->input->post('about_desc'),
                'cdate' => $todayDate
            );
            $result = $this->Cms_model->set_about_us($array_data);
            if ($result) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'About Us', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'About Us updated successfully');
                redirect('Cms_System/about_us_content');
            } else {
                $this->session->set_flashdata('error', 'About Us is not updated');
                redirect('Cms_System/about_us_content');
            }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('cms/about_us.php', $data);
        $this->load->view('template/footer.php');
    }

    public function about_gazette() {
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
        $data['title'] = "About Gazette";

        $data['gazetteDetails'] = $this->Cms_model->get_gazette();
        $this->form_validation->set_rules('gazette_desc', 'Description', 'trim|required');
        if ($this->form_validation->run() == false) {
            
        } else {
            $todayDate = date('Y-m-d H:i:s');
            $array_data = array(
                'cms_type' => 'gazette',
                'cms_desc' => $this->input->post('gazette_desc'),
                'cdate' => $todayDate
            );
            $result = $this->Cms_model->set_gazette($array_data);
            if ($result) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'About Gazette', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'About Gazette updated successfully');
                redirect('Cms_System/about_gazette');
            } else {
                $this->session->set_flashdata('error', 'About Gazette is not updated');
                redirect('Cms_System/about_gazette');
            }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('cms/about_gazette.php', $data);
        $this->load->view('template/footer.php');
    }

    public function disclaimer() {
        // var_dump($this->session->userdata());
        // exit;
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
        $data['title'] = "Disclaimer";

        $data['disclaimer'] = $this->Cms_model->get_disclaimer();
        $this->form_validation->set_rules('disclaimer_desc', 'Description', 'trim|required');
        if ($this->form_validation->run() == false) {
            
        } else {
            $todayDate = date('Y-m-d H:i:s');
            $array_data = array(
                'cms_type' => 'disclaimer',
                'cms_desc' => $this->input->post('disclaimer_desc'),
                'cdate' => $todayDate
            );
            $result = $this->Cms_model->set_disclaimer($array_data);
            if ($result) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Disclaimer', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'Disclaimer updated successfully');
                redirect('Cms_System/disclaimer');
            } else {
                $this->session->set_flashdata('error', 'Disclaimer is not updated');
                redirect('Cms_System/disclaimer');
            }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('cms/disclaimer.php', $data);
        $this->load->view('template/footer.php');
    }

    public function acknowledgement() {
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
        $data['title'] = "Acknowledgement";

        $data['acknowledgementDetails'] = $this->Cms_model->get_acknowledgement();
        $this->form_validation->set_rules('acknowledgement_desc', 'Description', 'trim|required');
        if ($this->form_validation->run() == false) {
            
        } else {
            $todayDate = date('Y-m-d H:i:s');
            $array_data = array(
                'cms_type' => 'acknowledgement',
                'cms_desc' => $this->input->post('acknowledgement_desc'),
                'cdate' => $todayDate
            );
            $result = $this->Cms_model->set_acknowledgement($array_data);
            if ($result) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Acknowledgement', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'Acknowledgement updated successfully');
                redirect('Cms_System/acknowledgement');
            } else {
                $this->session->set_flashdata('error', 'Acknowledgement is not updated');
                redirect('Cms_System/acknowledgement');
            }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('cms/acknowledgement.php', $data);
        $this->load->view('template/footer.php');
    }

}

?>