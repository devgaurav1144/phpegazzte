<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Designation extends MY_Controller {

    /**
     * __construct function.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'form_validation', 'pagination', 'my_pagination'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom'));
        $this->load->model(array('designation_model'));
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

        $data['title'] = "Designation";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "designation/index";

        $config["total_rows"] = $this->designation_model->get_total_designations();

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

        $data['designations'] = $this->designation_model->get_designation_List($config['per_page'], $offset);

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('designations/index.php', $data);
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

        $data['title'] = "Add Designation";

        // set form validation rules
        $this->form_validation->set_rules('designation_name', 'Designation Name', 'trim|required|min_length[2]|max_length[100]');

        if ($this->form_validation->run() == false) {
            //$this->load->view('designations/add', $data);
        } else {
            // set variables from the form
            $desig_name = $this->input->post('designation_name');

            $array_data = array(
                'designation_name' => $desig_name
            );
            // insert into DB
            $result = $this->designation_model->add($array_data);

            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'Designation', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            
            $this->session->set_flashdata('success', 'Designation added successfully');
            redirect('designation/index');
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('designations/add.php', $data);
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

        $data['title'] = "Edit Designation";

        if (!is_numeric($id) || !$this->designation_model->exists($id)) {
            $this->session->set_flashdata('error', 'Designation does not exists');
            redirect('designation/index');
        }

        $data['designation_dtls'] = $this->designation_model->get_designation_details($id);

        // set form validation rules
        $this->form_validation->set_rules('designation_name', 'Designation Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('desig_id', 'Designation ID', 'trim|required|numeric');

        if ($this->form_validation->run() == false) {
            //$this->load->view('departments/edit', $data);
        } else {
            // set variables from the form
            $designation_id = $this->input->post('desig_id');
            $designation_name = $this->input->post('designation_name');

            $array_data = array(
                'id' => $designation_id,
                'name' => $designation_name
            );
            // insert into DB
            $result = $this->designation_model->edit($array_data);

            if ($result) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Designation', 'Edit', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'Designation updated successfully');
                redirect('designation/index');
            } else {
                $this->session->set_flashdata('error', 'Designation not updated');
                redirect('designation/index');
            }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('designations/edit.php', $data);
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

        if (!is_numeric($id) || !$this->designation_model->exists($id)) {
            $this->session->set_flashdata('error', 'Designation does not exists');
            redirect('designation/index');
        }

        if ($this->designation_model->linked_with_user($id)) {
            $this->session->set_flashdata('error', 'Designation associated with nodal officer. Cannot be deleted');
            redirect('designation/index');
        }

        if ($this->designation_model->delete($id)) {
            
            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'Designation', 'Delete', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            
            $this->session->set_flashdata('success', 'Designation deleted successfully');
            redirect('designation/index');
        } else {
            $this->session->set_flashdata('error', 'Designation not deleted');
            redirect('designation/index');
        }
    }

}

?>