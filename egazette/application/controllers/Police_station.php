<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class Police_station extends MY_Controller{
        public function __construct() {
            parent::__construct();
            $this->load->database();
            $this->load->library(array('session', 'form_validation', 'pagination', 'my_pagination'));
            $this->load->helper(array('url', 'form'));
            $this->load->model(array('police_station_model'));
         }
        
        public function index() {

            //$this->output->enable_profiler(TRUE);

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

            $data['title'] = "Police Station Details";
            $config = array();
            $config["base_url"] = base_url() . "police_station/index";

            $config["total_rows"] = $this->police_station_model->get_total_police_station();

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
            $data['state_names'] = $this->police_station_model->get_state_List();
            $data['police_stations'] = $this->police_station_model->get_police_station_List($config['per_page'], $offset);


            $this->load->view('template/header.php', $data);
            $this->load->view('template/sidebar.php');
            $this->load->view('police_station/index.php', $data);
            $this->load->view('template/footer.php');
        
        }


        public function search_ps() {

            //$this->output->enable_profiler(TRUE);

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
            $data['title'] = "Search Police Station";
            $config["base_url"] = base_url() . "police_station/search_ps";
            
            $inputs = $this->input->post();
        
            $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
    
            if($this->input->post()){
                $page = 0;
                $this->session->set_userdata($inputs);
            } else{
                if($page == 0){
                $array_items = array('state_name', 'district_name', 'police_station_name');
                $this->session->unset_userdata($array_items);
                $inputs = array();
                } else {
                $inputs = $this->session->userdata();
                }
            }

                $config["total_rows"] = $this->police_station_model->get_total_police_station($inputs);
                
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

                //$state_id = $this->block_model->get_state_List();
                $data["links"] = $this->my_pagination->create_links();
                $data["inputs"] = $inputs;
                $data['state_names'] = $this->police_station_model->get_state_List();
                //$data['district_names'] = $this->block_model->get_district_List($state_id);
                $data['police_stations'] = $this->police_station_model->ps_search_result($config["per_page"],$offset, $inputs);
                
            $this->load->view('template/header.php', $data);
            $this->load->view('template/sidebar.php');
            $this->load->view('police_station/index.php', $data);
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
                    redirect('applicants_login/index');
                }
                $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
                redirect('user/login');
            }
    
            $data['title'] = "Add Police Station";
    
            // set form validation rules
            $this->form_validation->set_rules('state_name', 'State Name', 'trim|required');
            $this->form_validation->set_rules('district_name', 'District Name', 'trim|required');
            $this->form_validation->set_rules('police_station_name', 'Police Station Name', 'trim|required|max_length[100]');
    
            $data['state_names'] = $this->police_station_model->get_state_List();
            //$data['district_names'] = $this->police_station_model->get_district_List();

            if ($this->form_validation->run() == false) {
                
            } else {
                // set variables from the form
                $state_name = $this->input->post('state_name');
                $district_name = $this->input->post('district_name');
                $police_station_name = $this->input->post('police_station_name');
                
    
                $array_data = array(
                    'state_name' => $state_name,
                    'district_name' => $district_name,
                    'police_station_name' => $police_station_name
                    
                );

               
                // insert into DB
                $result = $this->police_station_model->add($array_data);
    
                // Store Audit Log
                // audit_action_log($this->session->userdata('user_id'), 'Police Station', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'Police Station added successfully');
                redirect('police_station/index');
            }
    
            $this->load->view('template/header.php', $data);
            $this->load->view('template/sidebar.php');
            $this->load->view('police_station/add.php', $data);
            $this->load->view('template/footer.php');
        }

        public function get_district() {
            $state_id = $this->input->post('state_id');
            $distircts = $this->police_station_model->get_district_List($state_id);
            $output = "";
            foreach ($distircts as $district) { ?>
                <option value="<?php echo $district->id; ?>"><?php echo $district->district_name; ?></option>
            <?php }
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
                    redirect('applicants_login/index');
                }
                $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
                redirect('user/login');
            }
    
            $data['title'] = "Edit Police Station";
    
            if (!is_numeric($id) || !$this->police_station_model->exists($id)) {
                $this->session->set_flashdata('error', 'Police Station does not exists');
                redirect('police_station/index');
            }
    
            $data['ps_dtls'] = $this->police_station_model->get_police_station_details($id);
            $state_id = $data['ps_dtls']->state_id;
           
            $data['state_names'] = $this->police_station_model->get_state_List();

            $data['district_names'] = $this->police_station_model->get_district_List($state_id);
            
            // set form validation rules
            // $this->form_validation->set_rules('state_name', 'State Name', 'trim|required');
            $this->form_validation->set_rules('district_name', 'District Name', 'trim|required');
            $this->form_validation->set_rules('police_station_name', 'Police Station Name', 'trim|required|max_length[100]');
    
            if ($this->form_validation->run() == false) {
               
            } else {
                // set variables from the form
                $state_name = $this->input->post('state_name');
                $district_name = $this->input->post('district_name');
                $police_station_name = $this->input->post('police_station_name');
                
                $array_data = array(
                    'id' => $id,
                    'state_name' => $state_name,
                    'district_name' => $district_name,
                    'police_station_name' => $police_station_name
                    
                );
                // insert into DB
                $result = $this->police_station_model->edit($array_data);
                if ($result) {
                    
                    // Store Audit Log
                    // audit_action_log($this->session->userdata('user_id'), 'Police Station', 'Edit', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                    
                    $this->session->set_flashdata('success', 'Police Station updated successfully');
                    redirect('police_station/index');
                } else {
                    $this->session->set_flashdata('error', 'Police Station not updated');
                    redirect('police_station/index');
                }
            }
    
            $this->load->view('template/header.php', $data);
            $this->load->view('template/sidebar.php');
            $this->load->view('police_station/edit.php', $data);
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
                    redirect('applicants_login/index');
                }
                $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
                redirect('user/login');
            }
            
            $id = $this->input->post('id');
    
            if (!is_numeric($id) || !$this->police_station_model->exists($id)) {
                $this->session->set_flashdata('error', 'Police Station does not exists');
                redirect('police_station/index');
            }

            if ($this->police_station_model->delete($id)) {
                
                // Store Audit Log
                // audit_action_log($this->session->userdata('user_id'), 'Police Station', 'Delete', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'Police Station deleted successfully');
                redirect('police_station/index');
            } else {
                $this->session->set_flashdata('error', 'Police Station not deleted');
                redirect('police_station/index');
            }
        }

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
            
            $id = $this->security->xss_clean($this->input->post('id'));
            $status = $this->security->xss_clean($this->input->post('status'));
            
            if (!is_numeric($id) || !$this->police_station_model->exists($id)) {
                $this->session->set_flashdata('error', 'Police Station does not exists');
                redirect('police_station/index');
            }
            
            $result = $this->police_station_model->status_change($id, $status);

            var_dump($result);exit;
            if($result) {
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Police Station', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'Police Station status updated successfully');
                redirect('police_station/index');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong');
                redirect('police_station/index');
            }
        }
    }
?>