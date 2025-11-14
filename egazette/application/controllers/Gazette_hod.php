<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gazette_hod extends MY_Controller {

    /**
     * __construct function.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'pagination', 'my_pagination','form_validation'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom'));
        $this->load->model(array('gazette_hod_model'));
    }

    /*
     * function for HOD list
     */
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

        $data['title'] = "HOD Registration";

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "gazette_hod/index";

        $config["total_rows"] = $this->gazette_hod_model->get_total_hods();

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

        $data['hods'] = $this->gazette_hod_model->get_hod_list($config['per_page'], $offset);

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('hod/index.php', $data);
        $this->load->view('template/footer.php');
    }
    
    /*
     * load add hod page
     */
    public function hod_registration() {
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
        $data['title'] = 'Add HOD Officer';

        $data['departments'] = $this->gazette_hod_model->getDepartmentList();
       
        
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('hod/add_hod_for_dept.php', $data);
        $this->load->view('template/footer.php');
    }
    
        /*
     * Add hod officer
     * @access public (Only Admin can add this)
     * @type POST
     * @param 
     * @return void
     */

    public function add_hod_officers() {
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
         $data['title'] = 'Add HOD Officer';
         
         $json = array();

        
        $this->form_validation->set_rules('dob', 'DOB', 'trim|required|min_length[4]|max_length[40]');
       
        
        $this->form_validation->set_rules('dept_id', 'Department', 'trim|required');

        $this->form_validation->set_rules('user_name', 'User name', 'trim|required|min_length[4]|max_length[20]|is_unique[gz_hod.name]', array('is_unique' => 'Name already exists for another user'));
        
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[6]|max_length[96]|valid_email|is_unique[gz_hod.email]', array('is_unique' => 'Email already exists for another user'));
        
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[10]|max_length[10]|numeric|is_unique[gz_hod.mobile]', array('is_unique' => 'Mobile number already exists for another user'));

        if ($this->form_validation->run() == false) {
            
            // store all the error data in error array
            $json['error'] = $this->form_validation->error_array();
            
        } else {


            // set variables from the form
            $dob = $this->input->post('dob');
            $dept_id = $this->input->post('dept_id');
            $user_name = $this->input->post('user_name');
            $email = $this->input->post('email');
            $mobile = $this->input->post('mobile');


         

            $array_data = array(
                'dob' => $dob,
                'dept_id' => $dept_id,
                'user_name' => $user_name,
                'email' => $email,
                'mobile' => $mobile,
                 'created_at' => date('Y-m-d H:i:s', time()),
                'created_by' => $this->session->userdata('user_id')
               
            );

            // get the userdata from database using model
            $result = $this->gazette_hod_model->add_hod_officers($array_data);
            
            if($result) {
                //Put the message data in a session   
                
                audit_action_log($this->session->userdata('user_id'), 'HOD', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                          
                $json['success'] = "HOD officer added successfully.";
                $json['redirect'] = base_url() . "Gazette_hod/index";
               
            } else {
                 $json['error'] = "HOD officer added not successfully.";

                $json['redirect'] = base_url() . "Gazette_hod/hod_registration";
                
                
            }
        }
        echo json_encode($json);
        
    }
    /*
     * load hod edit view
     */
    public function hod_edit($id) {
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
        $data['title'] = 'Add HOD Officer';

        $data['departments'] = $this->gazette_hod_model->getDepartmentList();
        
        $data['hod'] = $this->gazette_hod_model->getHodDetails($id);

        $data['hod_id'] = $id;
        
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('hod/edit.php', $data);
        $this->load->view('template/footer.php');
    }
    
           /*
     * edit nodal officer
     * @access public (Only Admin can add this)
     * @type POST
     * @param 
     * @return void
     */

    public function update_hod_officers() {
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
         $data['title'] = 'update HOD Officer';
         
         $json = array();

        
        $this->form_validation->set_rules('dob', 'DOB', 'trim|required|min_length[4]|max_length[40]');
       
        
        $this->form_validation->set_rules('dept_id', 'Department', 'trim|required');

        $this->form_validation->set_rules('user_name', 'User name', 'trim|required|min_length[4]|max_length[20]|is_unique[gz_hod.name]', array('is_unique' => 'Name already exists for another user'));
        
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[6]|max_length[96]|valid_email|is_unique[gz_hod.email]', array('is_unique' => 'Email already exists for another user'));
        
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|min_length[10]|max_length[10]|numeric|is_unique[gz_hod.mobile]', array('is_unique' => 'Mobile number already exists for another user'));

        if ($this->form_validation->run() == false) {
            
            // store all the error data in error array
            $json['error'] = $this->form_validation->error_array();
            
        } else {


            // set variables from the form
            $dob = $this->input->post('dob');
            $dept_id = $this->input->post('dept_id');
            $user_name = $this->input->post('user_name');
            $email = $this->input->post('email');
            $mobile = $this->input->post('mobile');
            $hod_id = $this->input->post('hod_id');


         

            $array_data = array(
                'dob' => $dob,
                'dept_id' => $dept_id,
                'user_name' => $user_name,
                'email' => $email,
                'mobile' => $mobile,
                 'modified_at' => date('Y-m-d H:i:s', time()),
                'modified_by' => $this->session->userdata('user_id'),
                'hod_id' => $hod_id
               
            );

            // get the userdata from database using model
            $result = $this->gazette_hod_model->update_hod_officers($array_data);
            
            if($result) {
                //Put the message data in a session      
                
                audit_action_log($this->session->userdata('user_id'), 'HOD', 'Update', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                 
                 
                $json['success'] = "HOD officer update successfully.";
                $json['redirect'] = base_url() . "Gazette_hod/index";
               
            } else {
                 $json['error'] = "HOD officer update not successfully.";

                $json['redirect'] = base_url() . "Gazette_hod/hod_edit";
                
                
            }
        }
        echo json_encode($json);
        
    }
    
     /*
     * Delete hod officer
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
                redirect('gazette_hod/index');
            }
            $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
            redirect('gazette_hod/index');
        }
        
        $id = $this->input->post('id');
        //echo $id;exit();
        if (!is_numeric($id) || !$this->gazette_hod_model->exists($id)) {
            $this->session->set_flashdata('error', 'HOD officer does not exists');
            redirect('gazette_hod/index');
        }


        if ($this->gazette_hod_model->delete($id)) {
           // echo "ok";
            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'HOD', 'Delete', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            
            $this->session->set_flashdata('success', 'HOD officer deleted successfully');
            redirect('gazette_hod/index');
        } else {
            $this->session->set_flashdata('error', 'HOD officer not deleted');
            redirect('gazette_hod/index');
        }
    }
    
    public function hod_status() {
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
                redirect('gazette_hod/index');
            }
            $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
            redirect('gazette_hod/index');
        }
        // user ID
        $id = $this->input->post('user_id');
        $status = $this->input->post('status');

        if (!is_numeric($id) || !$this->gazette_hod_model->exists($id)) {
            $this->session->set_flashdata('error', 'HOD officer does not exists');
            redirect('gazette_hod/index');
        }

        if ($this->gazette_hod_model->hod_status($id, $status)) {

                       // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'HOD', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            
            return true;
            
       } else {
            
            return false;
            
        }
    }

    

}

?>