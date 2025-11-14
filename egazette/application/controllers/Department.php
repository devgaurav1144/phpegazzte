<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Department extends MY_Controller {

    /**
     * __construct function.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'form_validation', 'pagination', 'my_pagination'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom'));
        $this->load->model(array('department_model'));
    }

    public function index() {

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

        $data['title'] = "Department";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "department/index";

        $config["total_rows"] = $this->department_model->get_total_departments();

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

        $data['departments'] = $this->department_model->get_department_List($config['per_page'], $offset);

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('departments/index.php', $data);
        $this->load->view('template/footer.php');
    }

    public function add() {

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

        $data['title'] = "Add Department";

        // set form validation rules
        $this->form_validation->set_rules('department_name', 'Department Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('facebook_url', 'Facebook URL', 'trim|required|min_length[8]|max_length[200]');
        $this->form_validation->set_rules('twitter_url', 'Twitter URL', 'trim|required|min_length[8]|max_length[200]');

        if ($this->form_validation->run() == false) {
            //$this->load->view('departments/add', $data);
        } else {
            // set variables from the form
            $depat_name = $this->input->post('department_name');
            $facebook_url = $this->input->post('facebook_url');
            $twitter_url = $this->input->post('twitter_url');

            $array_data = array(
                'department_name' => $depat_name,
                'facebook_page' => $facebook_url,
                'twitter_page' => $twitter_url
            );
            // insert into DB
            $result = $this->department_model->add($array_data);

            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'Department', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            
            $this->session->set_flashdata('success', 'Department added successfully');
            redirect('department/index');
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('departments/add.php', $data);
        $this->load->view('template/footer.php');
    }

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

        $data['title'] = "Edit Department";

        if (!is_numeric($id) || !$this->department_model->exists($id)) {
            $this->session->set_flashdata('error', 'Department does not exists');
            redirect('department/index');
        }

        $data['dept_dtls'] = $this->department_model->get_department_details($id);

        // set form validation rules
        $this->form_validation->set_rules('department_name', 'Department Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('facebook_url', 'Facebook URL', 'trim|required|min_length[8]|max_length[200]');
        $this->form_validation->set_rules('twitter_url', 'Twitter URL', 'trim|required|min_length[8]|max_length[200]');
        $this->form_validation->set_rules('dept_id', 'Department ID', 'trim|required|numeric');

        if ($this->form_validation->run() == false) {
            //$this->load->view('departments/edit', $data);
        } else {
            // set variables from the form
            $dept_id = $this->input->post('dept_id');
            $depat_name = $this->input->post('department_name');
            $facebook_url = $this->input->post('facebook_url');
            $twitter_url = $this->input->post('twitter_url');

            $array_data = array(
                'id' => $dept_id,
                'department_name' => $depat_name,
                'facebook_page' => $facebook_url,
                'twitter_page' => $twitter_url
            );
            // insert into DB
            $result = $this->department_model->edit($array_data);
            if ($result) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Department', 'Edit', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'Department updated successfully');
                redirect('department/index');
            } else {
                $this->session->set_flashdata('error', 'Department not updated');
                redirect('department/index');
            }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('departments/edit.php', $data);
        $this->load->view('template/footer.php');
    }

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
        if (!$this->session->userdata('logged_in') || !$this->session->userdata('is_admin')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('user/login');
        }
        
        $id = $this->input->post('id');

        if (!is_numeric($id) || !$this->department_model->exists($id)) {
            $this->session->set_flashdata('error', 'Department does not exists');
            redirect('department/index');
        }

        if ($this->department_model->linked_with_user($id)) {
            $this->session->set_flashdata('error', 'Department associated with nodal officer. Cannot be deleted');
            redirect('department/index');
        }

        if ($this->department_model->delete($id)) {
            
            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'Department', 'Delete', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            
            $this->session->set_flashdata('success', 'Department deleted successfully');
            redirect('department/index');
        } else {
            $this->session->set_flashdata('error', 'Department not deleted');
            redirect('department/index');
        }
    }

}

?>