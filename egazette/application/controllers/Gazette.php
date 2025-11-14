<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/vendor/autoload.php';

use DocxMerge\DocxMerge;

class Gazette extends MY_Controller {

    private $doc_file = '';
    private $pdf_file = '';
    private $press_doc_file = '';
    private $press_pdf_file = '';
    private $press_pdf_file_size = '';

    /**
     * __construct function.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'form_validation', 'email', 'pagination', 'my_pagination'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom'));
        $this->load->model(array('gazette_model', 'user_model', 'gazette_type_model'));
        
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Session has been expired');
            redirect('user/login');
        }
        
    }
    
    public function generate_qr () {
        $this->load->library('phpqrcode/qrlib');
        $text = '19-FEB-2021';
        $qr = QRcode::png($text);
    }

    /*
     * Dept. Extraordinary gazette list
     * Default listing & Filter option
     */

    public function index() {
        // if ($this->session->userdata('logged_in')) {
        //     if ($this->session->userdata('is_c&t') || $this->session->userdata('is_igr') || $this->session->userdata('is_applicant')) {
        //         if ($this->session->userdata('is_c&t')) {
        //             $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
        //             redirect('commerce_transport_department/login_ct');
        //         } else if ($this->session->userdata('is_igr')) {
        //             $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
        //             redirect('igr_user/login');
        //         } else if ($this->session->userdata('is_applicant')) {
        //             $this->session->set_flashdata('error', 'You are not authorized! Please contact System Administrator to access the page.');
        //             redirect('applicants_login/index');
        //         }
        //     } 
        // }
        // else{
        //     redirect('user/login');
        // }

        // if (!$this->session->userdata('force_password')) {
        //     $this->session->set_flashdata('error', 'You must change your password after first Login!');
        //     redirect('user/change_password');
        // }
        $data['title'] = "Extraordinary Gazette";

        $inputs = $this->input->post();
        
        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        } else{
            if($page == 0){
              $array_items = array('statusType', 'tdate', 'fdate', 'dept', 'subline', 'nType', 'odrNo');
              $this->session->unset_userdata($array_items);
              $inputs = array();
            } else {
              $inputs = $this->session->userdata();
            }
        }

        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "gazette/index";
        
        if ($this->session->userdata('is_admin')) {
            $config["total_rows"] = $this->gazette_model->count_total_gazettes($inputs);
        } else {
            $config["total_rows"] = $this->gazette_model->count_total_department_gazettes($this->session->userdata('user_id'), $inputs);
        }
        
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

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();
        
        $data['notification_type'] = $this->gazette_model->get_notification_types();
        $data['department_type'] = $this->gazette_model->get_department_types();
        $data['gz_status'] = $this->gazette_model->get_status();
        if ($this->session->userdata('is_admin')) {
            
            // $data['gazettes_published'] = $this->gazette_model->getGazettePublishedList($config["per_page"], $offset, $inputs);

            $data['gazettes_unpublished'] = $this->gazette_model->getGazetteUnpublishedList($config["per_page"], $offset, $inputs);
        } else {
            $data['dept_name'] = $this->user_model->get_dept_name($this->session->userdata('user_id'));
            // $data['gazettes_published'] = $this->gazette_model->getDeptGazettePublishedList($this->session->userdata('user_id'), $config["per_page"], $offset, $inputs);
            $data['gazettes_unpublished'] = $this->gazette_model->getDeptGazetteUnpublishedList($this->session->userdata('user_id'), $config["per_page"], $offset, $inputs);
        }
        $data["inputs"] = $inputs;
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('gazette/index.php', $data);
        $this->load->view('template/footer.php');
    }

    /**
    * Get Extraordinary published index data
    */

    public function ex_published_gazette() {
        if ($this->session->userdata('logged_in')) {
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
            } 
        }
        else{
            redirect('user/login');
        }
        
        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }
        //$this->output->enable_profiler(true);
        $data['title'] = "Published Extraordinary Gazette";

        $inputs = $this->input->post();

        $page = (int) ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if($this->input->post()){
            $page = 0;
            $this->session->set_userdata($inputs);
        } else{
            if($page == 0){
              $array_items = array('statusType', 'tdate', 'fdate', 'dept', 'subline', 'nType', 'odrNo');
              $this->session->unset_userdata($array_items);
              $inputs = array();
            } else {
              $inputs = $this->session->userdata();
            }
        }
        
        // Pagination Configuration
        $config = array();
        $config["base_url"] = base_url() . "gazette/ex_published_gazette";
        
        if ($this->session->userdata('is_admin')) {
            $config["total_rows"] = $this->gazette_model->getGazettePublishedListCount($inputs);
        } else {
            $config["total_rows"] = $this->gazette_model->getDeptGazettePublishedListCount($this->session->userdata('user_id'), $inputs);
            
        }
        
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

        if ($page > 0) {
            $offset = ($page - 1) * $config["per_page"];
        } else {
            $offset = $page;
        }

        $data["links"] = $this->my_pagination->create_links();
        $data['notification_type'] = $this->gazette_model->get_notification_types();
        $data['department_type'] = $this->gazette_model->get_department_types();
        $data['gz_status'] = $this->gazette_model->get_status();
       
        if ($this->session->userdata('is_admin')) {
            $data['gazettes_published'] = $this->gazette_model->getGazettePublishedList($config["per_page"], $offset, $inputs);
        } else {
            $data['dept_name'] = $this->user_model->get_dept_name($this->session->userdata('user_id'));
            $data['gazettes_published'] = $this->gazette_model->getDeptGazettePublishedList($this->session->userdata('user_id'), $config["per_page"], $offset, $inputs);
          
        }
        $data["inputs"] = $inputs;
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('gazette/published_extraordinary_gazette_index.php', $data);
        $this->load->view('template/footer.php');
    }

    /**
     * Filteration for Extraordinary Data
     * Old Code
     */

    public function extraordinary_filter() {
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
        
        $data['title'] = "Extraordinary Filter Result";

        $config = array();
        $config["base_url"] = base_url() . "gazette/index/extraordinary_filter";

        $searchValue = array(
            'fdate' => trim($this->input->post('fdate')),
            'tdate' => trim($this->input->post('tdate')),
            'subline' => trim($this->input->post('subline')),
            'dept' => trim($this->input->post('dept')),
            'odrNo' => trim($this->input->post('odrNo')),
            'nType' => trim($this->input->post('nType')),
            'statusType' => trim($this->input->post('statusType')),
        );
        
        //$config["total_rows"] = $this->gazette_model->count_total_record_extraordinary_search($searchValue);
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

        // Pagination Configuration
        $data['gazette_type'] = $this->gazette_model->get_gazette_types();
        $data['notification_type'] = $this->gazette_model->get_notification_types();
        $data['department_type'] = $this->gazette_model->get_department_types();

        if ($this->session->userdata('is_admin')) {
            $data['gazettes_published'] = $this->gazette_model->getGazettePublishedList_search($searchValue, $config["per_page"], $offset);

            $data['gazettes_unpublished'] = $this->gazette_model->getGazetteUnpublishedList_serach($searchValue, $config["per_page"], $offset);
        } else {

            $data['dept_name'] = $this->user_model->get_dept_name($this->session->userdata('user_id'));

             $data['gazettes_published'] = $this->gazette_model->getDeptGazettePublishedList_search($this->session->userdata('user_id'), $searchValue, $config["per_page"], $offset);

            // $data['gazettes_unpublished'] = $this->gazette_model->getDeptGazetteUnpublishedList_search($this->session->userdata('user_id'), $searchValue, $config["per_page"], $offset);
        }
        $data['gz_status'] = $this->gazette_model->get_status();
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('gazette/index.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Add for department user
     */

    public function add() {
        // echo "jhjg" .$this->session->userdata('is_dept');exit;

        if ($this->session->userdata('logged_in')) {
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
            } 
        }
        else{
            redirect('user/login');
        }

        if (!$this->session->userdata('force_password')) {
            $this->session->set_flashdata('error', 'You must change your password after first Login!');
            redirect('user/change_password');
        }

        $data['title'] = "Add Gazette";

        $data['sl_no'] = $this->gazette_model->get_sl_no();

        $user_data = $this->gazette_model->get_dept_info_user($this->session->userdata('user_id'));

        $data['dept_name'] = $user_data->dept_name;
        $data['dept_id'] = $user_data->dept_id;
        $data['gazette_types'] = $this->gazette_model->get_gazette_types();
        $data['notification_types'] = $this->gazette_type_model->get_notification_types();

        // set form validation rules
        $this->form_validation->set_rules('dept_id', 'Department ID', 'trim|required|numeric');
        // $this->form_validation->set_rules('dept_name', 'Department Name', 'trim|required');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required|min_length[5]|max_length[200]');
        $this->form_validation->set_rules('gazette_type_id', 'Gazette Type', 'trim|required');
        $this->form_validation->set_rules('notification_type_id', 'Notification Type', 'trim|required');
        $this->form_validation->set_rules('notification_number', 'Notification Number', 'trim|required|min_length[5]|max_length[50]');
        $this->form_validation->set_rules('keywords', 'Tags', 'trim|required|min_length[4]|max_length[100]');

        $this->form_validation->set_rules('sro_no_check', 'SRO Available', 'trim|required');

        $this->form_validation->set_rules('doc_files', 'Select Word/Docx File', 'callback_handle_gazette_doc_upload');

        if ($this->form_validation->run() == false) {
            //$this->load->view('gazette/add', $data);
        } else {
            // set variables from the form
            $sl_no = $this->gazette_model->get_sl_no();

            $dept_id = $this->input->post('dept_id');
            $subject = $this->input->post('subject');
            $gazette_type_id = $this->input->post('gazette_type_id');
            $notification_type_id = $this->input->post('notification_type_id');
            $notification_number = $this->input->post('notification_number');
            $is_paid = $this->input->post('payment');
            $content = '';
            $tags = $this->input->post('keywords');

            // SRO Available
            $sro_avl = $this->input->post('sro_no_check');

            $array_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'dept_id' => $dept_id,
                'subject' => $subject,
                'gazette_type_id' => $gazette_type_id,
                'notification_type' => $notification_type_id,
                'notification_number' => $notification_number,
                // SRO Available
                'sro_available' => $sro_avl,
                'issue_date' => '',
                'tags' => $tags,
                'content' => $content,
                'is_paid' => $is_paid
            );
            
            // get the userdata from database using model
            $result = $this->gazette_model->add_dept_gazette($array_data);

            $docs_array = array(
                'gazette_id' => $result,
                'dept_word_file_path' => $this->doc_file
            );

            // INSERT into gz_documents table
            $this->db->insert('gz_documents', $docs_array);
            $document_master_id = $this->db->insert_id();

            // Word file uploaded by Department
            $word_file = str_replace('\\', '/', FCPATH) . $this->doc_file;
            // Convert Word file To PDF
            $pdf_file_path = $this->convert_word_to_PDF($word_file, $result);

            // UPDATE gazette document table with Gazette ID
            $this->db->where('gazette_id', $result);
            $this->db->update('gz_documents', array('dept_pdf_file_path' => $pdf_file_path));

            // Data to submit in extraordinary documents history table 

            $docs_history = array(
                'gazette_id'      => $result,
                'gz_document_id'  => $document_master_id,
                'dept_id'         => $dept_id,
                'word_file_path'  => $this->doc_file,
                'pdf_file_path'   => $pdf_file_path,
                'created_at'      => date('Y-m-d H:i:s', time()),
                'created_by'      => $this->session->userdata('user_id')
            );

            // INSERT into gz_documents_history table
            $this->db->insert('gz_documents_history', $docs_history);            

            if (is_numeric($result)) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Extraordinary Gazette', 'Department Saved', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                //Put the array in a session            
                $this->session->set_flashdata('success', 'Gazette saved successfully');
                redirect('gazette/dept_preview/' . $result);
            }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('gazette/add.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Dept. preview before final submit to the press
     */

    public function dept_preview($gazette_id) {
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

        if (!$this->gazette_model->exists($gazette_id)) {
            $this->session->set_flashdata('error', 'The gazette does not exists');
            redirect('gazette/index');
        }

        $data['title'] = 'Preview Grazette';

        $data['details'] = $this->gazette_model->get_gazette_details($gazette_id);
        // user details to be passed 
        $data['signed_name'] = $this->session->userdata('name');
        $data['signed_designation'] = $this->session->userdata('designation');

        $this->session->set_userdata(
                array(
                    'gazette_id' => $gazette_id,
                    'gazette_type' => 'extra'
                )
        );

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('gazette/dept_preview.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Resubmit save for Dept. User after Gazette has been Rejected by the Govt. Press User 
     */

    public function resubmit_save_gazette() {
        
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

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data['title'] = 'Edit Gazette';

            //$this->form_validation->set_rules('content', 'Gazette Content', 'trim|required');
            $this->form_validation->set_rules('doc_files', 'Select Word/Docx File', 'callback_handle_dept_gazette_doc_resubmit');

            if ($this->form_validation->run() == false) {
                $this->load->view('gazette/press_view');
            } else {

                $gazette_id = $this->input->post('gazette_id');

                $array_data = array(
                    'gazette_id' => $gazette_id,
                    'user_id' => $this->session->userdata('user_id'),
                    // Resubmit Save
                    'status_id' => 7
                );

                // get the userdata from database using model
                $result = $this->gazette_model->resubmit_save_dept_gazette($array_data);

                // Word file uploaded by Department
                $word_file = str_replace('\\', '/', FCPATH) . $this->doc_file;

                // Convert Word file To PDF
                $pdf_file_path = $this->convert_word_to_PDF($word_file, $gazette_id);

                // UPDATE gazette document table with Gazette ID
                $this->db->where('gazette_id', $gazette_id);
                $this->db->update('gz_documents', array(
                    'dept_word_file_path' => $this->doc_file,
                    'dept_pdf_file_path' => $pdf_file_path,
                    'dept_signed_pdf_path' => ''
                        )
                );

                /*
                * Get gz_documents master id and dept id to insert in gz_documents_history table
                * 21/02/2023
                * Soudhankhi Dalai
                */

                $doc_updated_id = $this->db->select('id') 
                                           ->from('gz_documents')
                                           ->where('gazette_id', $gazette_id)
                                           ->get()->row()->id;

                $dept_id = $this->db->select('dept_id') 
                                    ->from('gz_gazette')
                                    ->where('id', $gazette_id)
                                    ->get()->row()->dept_id;

                // Data to submit in extraordinary documents history table 

                $docs_history = array(
                    'gazette_id'      => $gazette_id,
                    'gz_document_id'  => $doc_updated_id,
                    'dept_id'         => $dept_id,
                    'word_file_path'  => $this->doc_file,
                    'pdf_file_path'   => $pdf_file_path,
                    'created_at'      => date('Y-m-d H:i:s', time()),
                    'created_by'      => $this->session->userdata('user_id')
                );

                // INSERT into gz_documents_history table
                $this->db->insert('gz_documents_history', $docs_history);   

                if ($result) {
                    
                    // Store Audit Log
                    audit_action_log($this->session->userdata('user_id'), 'Extraordinary Gazette', 'Resubmitted', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                    
                    $this->session->set_flashdata('success', 'Gazette updated successfully');
                    redirect('gazette/resubmit_dept_gazette/' . $gazette_id);
                } else {
                    $this->session->set_flashdata('error', 'Gazette not updated');
                    redirect('gazette/index');
                }
            }
        } else {
            $this->session->set_flashdata('error', 'The request type is not allowed');
            redirect('gazette/index');
        }
    }

    /*
     * Resubmit save for Dept. User after Gazette has been Rejected by the Govt. Press User 
     */

    public function resubmit_dept_gazette($gazette_id) {
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

        if (!$this->gazette_model->exists($gazette_id)) {
            $this->session->set_flashdata('error', 'The gazette does not exists');
            redirect('gazette/index');
        }

        $data['title'] = 'Resubmit Grazette';
        $data['gazette_id'] = $gazette_id;

        $data['details'] = $this->gazette_model->get_gazette_details($gazette_id);

        // username & designation 
        $data['signed_name'] = $this->session->userdata('name');
        $data['signed_designation'] = $this->session->userdata('designation');

        $this->form_validation->set_rules('gazette_id', 'Gazette ID', 'trim|required');

        if ($this->form_validation->run() == false) {
            //$this->load->view('gazette/dept_preview_resubmit');
        } else {

            $gazette_id = $this->input->post('gazette_id');

            $array_data = array(
                'gazette_id' => $gazette_id,
                'user_id' => $this->session->userdata('user_id'),
                // Resubmit
                'status_id' => 4,
                'created_at' => date('Y-m-d H:i:s', time()),
                'modified_at' => date('Y-m-d H:i:s', time())
            );

            // get the userdata from database using model
            $result = $this->gazette_model->resubmit_dept_gazette($array_data);

            if ($result) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Extraordinary Gazette', 'Resubmitted Saved', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                /*
                 * Remove this line in real app
                 */
                $this->session->unset_userdata('demo_signed');
                
                $this->session->set_flashdata('success', 'Gazette resubmitted successfully');
                redirect('gazette/index');
            } else {
                $this->session->set_flashdata('error', 'Gazette not updated');
                redirect('gazette/index');
            }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('gazette/dept_preview_resubmit.php', $data);
        $this->load->view('template/footer.php');
    }

    /*
     * Add for Govt. Press User/Admin
     */

    public function press_add() {

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

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
            $gazette_id = $this->input->post('gazette_id');

            $data['title'] = 'Edit Grazette';
            $data['details'] = $this->gazette_model->get_gazette_documents($gazette_id);
            $data['gazette_id'] = $this->input->post('gazette_id');
            $data['sro_no'] = $this->gazette_model->get_sro_no($gazette_id);
            $data['sro_avl'] = $this->gazette_model->get_sro_available($gazette_id);
            $data['sl_no'] = $this->gazette_model->get_sl_no();

            $this->load->view('template/header.php', $data);
            $this->load->view('template/sidebar.php');
            $this->load->view('gazette/press_add.php', $data);
            $this->load->view('template/footer.php');
        } else {
            $this->session->set_flashdata('error', 'The request type is not allowed');
            redirect('gazette/index');
        }
    }

    /*
    * Re-upload in department login before e-Sign process
    * @author Shivaram Mahapatro
    * @date 9/8/2021
    */

    public function dept_re_upload($gazette_id) {
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

        if (!$this->gazette_model->exists($gazette_id)) {
            $this->session->set_flashdata('error', 'The gazette does not exists');
            redirect('gazette/index');
        }

        $data['title'] = 'Reupload Grazette';
        $data['gazette_id'] = $gazette_id;

        $data['details'] = $this->gazette_model->get_gazette_details($gazette_id);

        // username & designation 
        $data['signed_name'] = $this->session->userdata('name');
        $data['signed_designation'] = $this->session->userdata('designation');

        $this->form_validation->set_rules('gazette_id', 'Gazette ID', 'trim|required');

        if ($this->form_validation->run() == false) {
            //$this->load->view('gazette/dept_preview_resubmit');
        } else {

            $gazette_id = $this->input->post('gazette_id');

            $array_data = array(
                'gazette_id' => $gazette_id,
                'user_id' => $this->session->userdata('user_id'),
                // Resubmit
                'status_id' => 4,
                'created_at' => date('Y-m-d H:i:s', time()),
                'modified_at' => date('Y-m-d H:i:s', time())
            );

            // get the userdata from database using model
            $result = $this->gazette_model->resubmit_dept_gazette($array_data);

            if ($result) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Extraordinary Gazette', 'Reupload Saved', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata('success', 'Gazette resubmitted successfully');
                redirect('gazette/index');
            } else {
                $this->session->set_flashdata('error', 'Gazette not updated');
                redirect('gazette/index');
            }
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('gazette/dept_preview_reupload.php', $data);
        $this->load->view('template/footer.php');

    }


    /*
     * Department Re-upload option save before e-Sign
     *
     */

    public function reupload_save_gazette() {
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

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data['title'] = 'Reupload Grazette';

            //$this->form_validation->set_rules('content', 'Gazette Content', 'trim|required');
            $this->form_validation->set_rules('doc_files', 'Select Word/Docx File', 'callback_handle_dept_gazette_doc_resubmit');

            if ($this->form_validation->run() == false) {
                $this->load->view('gazette/press_view');
            } else {

                $gazette_id = $this->input->post('gazette_id');

                $array_data = array(
                    'gazette_id' => $gazette_id,
                    'user_id' => $this->session->userdata('user_id'),
                    // Reupload Save
                    'status_id' => 22
                );

                // get the userdata from database using model
                $result = $this->gazette_model->resubmit_save_dept_gazette($array_data);

                // Word file uploaded by Department
                $word_file = str_replace('\\', '/', FCPATH) . $this->doc_file;

                // Convert Word file To PDF
                $pdf_file_path = $this->convert_word_to_PDF($word_file, $gazette_id);

                // UPDATE gazette document table with Gazette ID
                $this->db->where('gazette_id', $gazette_id);
                $this->db->update('gz_documents', array(
                        'dept_word_file_path' => $this->doc_file,
                        'dept_pdf_file_path' => $pdf_file_path,
                        'dept_signed_pdf_path' => ''
                    )
                );

                $doc_updated_id = $this->db->select('id') 
                                           ->from('gz_documents')
                                           ->where('gazette_id', $gazette_id)
                                           ->get()->row()->id;

                $dept_id = $this->db->select('dept_id') 
                                    ->from('gz_gazette')
                                    ->where('id', $gazette_id)
                                    ->get()->row()->dept_id;

                // Data to submit in extraordinary documents history table 

                $docs_history = array(
                    'gazette_id'      => $gazette_id,
                    'gz_document_id'  => $doc_updated_id,
                    'dept_id'         => $dept_id,
                    'word_file_path'  => $this->doc_file,
                    'pdf_file_path'   => $pdf_file_path,
                    'created_at'      => date('Y-m-d H:i:s', time()),
                    'created_by'      => $this->session->userdata('user_id')
                );

                // INSERT into gz_documents_history table
                $this->db->insert('gz_documents_history', $docs_history);   

                if ($result) {
                    
                    // Store Audit Log
                    audit_action_log($this->session->userdata('user_id'), 'Extraordinary Gazette', 'Reuploaded', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                    
                    $this->session->set_flashdata('success', 'Gazette updated successfully');
                    redirect('gazette/dept_preview/' . $gazette_id);
                } else {
                    $this->session->set_flashdata('error', 'Gazette not updated');
                    redirect('gazette/index');
                }
            }
        } else {
            $this->session->set_flashdata('error', 'The request type is not allowed');
            redirect('gazette/index');
        }
    }

    /*
     * Press Published 
     */

    public function press_published() {
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
        $this->form_validation->set_rules('gazette_id', 'Gazette ID', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->load->view('gazette/press_publish_view', $data);
        } else {

            $gazette_id = $this->input->post('gazette_id');

            // get the file submitted by Dept. user and make the required changes in the PDF file
            $gazette_details = $this->gazette_model->get_gazette_user_details($gazette_id);

            // data needs to be updated
            $array_data = array(
                'gazette_id' => $gazette_id,
                'user_id' => $this->session->userdata('user_id'),
                // publish status
                'status_id' => 5,
                'created_at' => date('Y-m-d H:i:s', time())
            );

            // get the userdata from database using model
            $result = $this->gazette_model->publish_press_gazette($array_data);

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
                            <p style=\"color: #000 !important\">Hii {$gazette_details->name},</p>
                            <p style=\"color: #000 !important\">
                                Gazette has been published by Director of Printing, Stationary and Publication for (StateName) Press E-Gazette System.<br/>
                                Department : {$gazette_details->department_name}<br/>
                                Gazette Sl No.: {$gazette_details->sl_no}<br/>
                                Gazette Type : {$gazette_details->gazette_type}<br/>
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
            $this->email->to($gazette_details->email);
            $this->email->subject('Gazette Published By Directorate of Printing, Stationary and Publication');
            $this->email->message($email_content);
            $this->email->set_newline("\r\n");
            $this->email->send();

            if ($result) {
                
                // Store Audit Log
                audit_action_log($this->session->userdata('user_id'), 'Extraordinary Gazette', 'Press Published', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                /*
                 * Remove this line in real app
                 */
                //$this->session->unset_userdata('demo_signed');
                
                //Put the array in a session
                $this->session->set_flashdata('success', 'Gazette published successfully');
                redirect('gazette/index');
            } else {
                //Put the array in a session            
                $this->session->set_flashdata('error', 'Gazette not published');
                redirect('gazette/index');
            }
        }
    }

    /*
     * GET Dept. signed PDF file path after signing using e-Sign from CDAC.
     * For the time being we are redirecting to .NET code, again .NET code is providing the signed file
     */

    public function get_signed_pdf_path() {

        $pdf_file_name = $this->input->get('files');

        $gazette_id = $this->input->get('gazette_id');

        $type = $this->input->get('type');

        // signed PDF file path
        $signed_pdf_path = './uploads/dept_signed_pdf/' . $pdf_file_name;

        $data = array(
            'gazette_id' => $gazette_id,
            'dept_signed_pdf_path' => $signed_pdf_path
        );

        $result = $this->gazette_model->update_dept_signed_pdf_path($data);

        if ($result) {
            
            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'Extraordinary Gazette', 'Department Signed', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            
            $this->session->set_flashdata('success', 'Document signed successfully');
            redirect('gazette/dept_preview/' . $gazette_id);
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
        $signed_pdf_path = './uploads/press_signed_pdf/' . $pdf_file_name;

        $data = array(
            'gazette_id' => $gazette_id,
            'press_signed_pdf_path' => $signed_pdf_path,
            'press_signed_pdf_file_size' => 0
        );

        $result = $this->gazette_model->update_press_signed_pdf_path($data);

        if ($result) {
            
            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'Extraordinary Gazette', 'Press Signed', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            
            $this->session->set_flashdata('success', 'Document signed successfully');
            redirect('gazette/press_pdf_view/' . $gazette_id);
        }
    }

    /*
     * Callback function for Dept. User word file upload
     */

    public function handle_gazette_doc_upload() {

        $this->doc_file = '';

        if (!empty($_FILES['doc_files']['name']) && ($_FILES['doc_files']['size'] > 0)) {

            $upload_dir = "./uploads/dept_doc/";

            if (!is_dir($upload_dir) && !is_writable($upload_dir)) {
                mkdir($upload_dir);
            }

            $config['upload_path'] = $upload_dir;
            $config['allowed_types'] = array('docx');
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
                return true;
            }
        } else {
            $this->form_validation->set_message('handle_gazette_doc_upload', 'No file selected');
        }
    }

    /*
     * Convert Word file to PDF
     */

    public function convert_word_to_PDF($word_file, $result) {
        // Convert to PDF using MS Word using PHP COM object
        $word = new COM("word.application") or die("Could not initialise MS Word object.");
        $word->Documents->Open($word_file) or die($word_file);

        $pdf_file_db_path = './uploads/dept_pdf/' . time().'_'. $result . '.pdf';
        $pdf_file_path = FCPATH . $pdf_file_db_path;

        $word->ActiveDocument->ExportAsFixedFormat($pdf_file_path, 17, false, 0, 0, 0, 0, 7, true, true, 2, true, true, false);

        $word->Quit();
        $word = null;
        return $pdf_file_db_path;
    }

    /*
     * Convert Word file to PDF
     */

    public function convert_press_word_to_PDF($template_file, $word_file, $data) {

        // store in database
        $press_word_db_path = './uploads/press_doc/' . time() . '.docx';
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

        if (!empty($data['sro_no'])) {
            $templateProcessor->setValue('sro_no', "S.R.O No: " . $data['sro_no']);
        } else {
            $templateProcessor->setValue('sro_no', "");
        }

        $templateProcessor->saveAs($press_word_path);

        // UPDATE into documents table
        $this->db->where('gazette_id', $data['gazette_id']);
        $this->db->update('gz_documents', array('press_word_file_path' => $press_word_db_path));

        // Convert to PDF using MS Word using PHP COM object
        $word = new COM("word.application") or die("Could not initialise MS Word object.");
        $word->Documents->Open($press_word_path);

        $pdf_file_db_path = './uploads/press_pdf/' . time() . '.pdf';
        $pdf_file_path = FCPATH . $pdf_file_db_path;

        $word->ActiveDocument->ExportAsFixedFormat($pdf_file_path, 17, false, 0, 0, 0, 0, 7, true, true, 2, true, true, false);

        $word->Quit();
        $word = null;
        return $pdf_file_db_path;
    }

    /*
     * Callback function for Dept. User word file Resubmit upload
     */

    public function handle_dept_gazette_doc_resubmit() {

        $this->doc_file = '';

        if (!empty($_FILES['doc_files']['name']) && ($_FILES['doc_files']['size'] > 0)) {

            $upload_dir = "./uploads/dept_doc/";

            if (!is_dir($upload_dir) && !is_writable($upload_dir)) {
                mkdir($upload_dir);
            }

            $config['upload_path'] = $upload_dir;
            $config['allowed_types'] = array('doc', 'docx');
            $config['file_name'] = $_FILES['doc_files']['name'];
            $config['overwrite'] = true;
            $config['encrypt_name'] = TRUE;
            // 5 MB
            $config['max_size'] = '5242880';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('doc_files')) {
                $this->form_validation->set_message('handle_dept_gazette_doc_resubmit', $this->upload->display_errors('', ''));
                return false;
            } else {
                $this->upload_data['file'] = $this->upload->data();
                $this->doc_file = $upload_dir . $this->upload_data['file']['file_name'];
                return true;
            }
        } else {
            $this->form_validation->set_message('handle_dept_gazette_doc_resubmit', 'No file selected');
        }
    }

    /*
     * View for Department User
     */

    public function dept_view($gazette_id, $user_id) {

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

        if (!$this->gazette_model->exists($gazette_id)) {
            $this->session->set_flashdata('error', 'The gazette does not exists');
            redirect('gazette/index');
        }
        // Check the gazette of one dept. cannot view another dept. gazette
        if (!$this->gazette_model->check_gazette_permission_exists($gazette_id, $user_id)) {
            $this->session->set_flashdata('error', 'You do not have permision to view the document');
            redirect('gazette/index');
        }

        $data['title'] = "View Gazette";

        $data['details'] = $this->gazette_model->get_gazette_details($gazette_id);
        $data['status_list'] = $this->gazette_model->get_gazette_status_lists($gazette_id);
        $data['document_list'] = $this->gazette_model->get_gazette_document_lists($gazette_id);

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('gazette/dept_view.php', $data);
        $this->load->view('template/footer.php');
    }
    
    public function getNumPagesInPDF($pdftext) {
    
        if(!file_exists($pdftext))return null;
        if (!$fp = @fopen($pdftext,"r"))return null;
        $max=0;
        while(!feof($fp)) {
            $line = fgets($fp,255);
            if (preg_match('/\/Count [0-9]+/', $line, $matches)){
                    preg_match('/[0-9]+/',$matches[0], $matches2);
                    if ($max<$matches2[0]) $max=$matches2[0];
            }
        }
        fclose($fp);
        return (int)$max;
    }
    
    public function dept_view_paid($gazette_id, $user_id) {
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

        if (!$this->gazette_model->exists($gazette_id)) {
            $this->session->set_flashdata('error', 'The gazette does not exists');
            redirect('gazette/index');
        }
        // Check the gazette of one dept. cannot view another dept. gazette
        if (!$this->gazette_model->check_gazette_permission_exists($gazette_id, $user_id)) {
            $this->session->set_flashdata('error', 'You do not have permision to view the document');
            redirect('gazette/index');
        }

        $data['title'] = "View Gazette";
        
        $data['dept_details'] = $this->gazette_model->get_department_details($this->session->userdata('user_id'));
        $data['id'] = $gazette_id;
        $data['details'] = $this->gazette_model->get_gazette_details($gazette_id);
        $data['status_list'] = $this->gazette_model->get_gazette_status_lists($gazette_id);
        $data['document_list'] = $this->gazette_model->get_gazette_document_lists($gazette_id);
        $data['total_pages'] = $this->getNumPagesInPDF($data['details']->dept_signed_pdf_path);
        
        // Binary Key
        $data['binary_key'] = './binary_key/EGZ_binary_UAT.key';

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('gazette/dept_view_paid.php', $data);
        $this->load->view('template/footer.php');
    }
    
    public function payment_response () {
        // echo "hyy";
        // exit;
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
                'gazette_id' => $data[0],
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
                'user_id' => $this->session->userdata('user_id')
            );
        //    echo '<pre>';print_r($insert_array);exit();
            $result = $this->gazette_model->save_payment_response($insert_array);

            if ($result && $bnk_trans_stat == 'S') {
                $this->session->set_flashdata('success', 'Payment completed successfully');
                redirect('gazette');
            } else if ($result && $bnk_trans_stat == 'F') {
                $this->session->set_flashdata('error', 'Payment Failed');
                redirect('gazette');
            } else if ($result && $bnk_trans_stat == 'P') {
                $this->session->set_flashdata('error', 'Payment Pending');
                redirect('gazette');
            } else if ($result && $bnk_trans_stat == 'I') {
                $this->session->set_flashdata('error', 'Payment Initiated');
                redirect('gazette');
            } else if ($result && $bnk_trans_stat == 'X') {
                $this->session->set_flashdata('error', 'Transaction cancelled by Applicant');
                redirect('gazette');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong');
                redirect('gazette');
            }
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
     * View for Govt Press User
     */

    public function press_view($gazette_id) {
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

        if (!$this->gazette_model->exists($gazette_id)) {
            $this->session->set_flashdata('error', 'The gazette does not exists');
            redirect('gazette/index');
        }

        $data['title'] = "View Gazette";

        $data['details'] = $this->gazette_model->get_gazette_details($gazette_id);
        $data['status_list'] = $this->gazette_model->get_gazette_status_lists($gazette_id);

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('gazette/press_view.php', $data);
        $this->load->view('template/footer.php');
    }
    
    public function press_view_paid($gazette_id) {
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

        if (!$this->gazette_model->exists($gazette_id)) {
            $this->session->set_flashdata('error', 'The gazette does not exists');
            redirect('gazette/index');
        }

        $data['title'] = "View Gazette";

        $data['details'] = $this->gazette_model->get_gazette_details($gazette_id);
        $data['status_list'] = $this->gazette_model->get_gazette_status_lists($gazette_id);
        $data['total_pages'] = $this->getNumPagesInPDF($data['details']->dept_signed_pdf_path);

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('gazette/press_view_paid.php', $data);
        $this->load->view('template/footer.php');
    }
    
    public function forward_to_pay($id) {

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

        if (!$this->gazette_model->exists($id)) {
            $this->session->set_flashdata('error', 'The gazette does not exists');
            redirect('gazette/index');
        }
        
        $remarks = '';
        $curr_status = 10;
        $next_status = 17;
        $gazette_id = $id;

        $result = $this->gazette_model->forward_reject($remarks, $curr_status, $next_status, $gazette_id);

        if ($result) {
            audit_action_log($this->session->userdata('user_id'), 'Extraordinary Gazette', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());

            $this->session->set_flashdata("success", "Gazette forwarded to department");
            redirect("gazette");
        } else {
            $this->session->set_flashdata("error", "Something went wrong");
            redirect("gazette");
        }
    }

    /*
     * Reject gazette
     */

    public function reject_gazette() {

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

        $gazette_id = $this->input->post('gazette_id');

        if (!$this->gazette_model->exists($gazette_id)) {
            $this->session->set_flashdata('error', 'The gazette does not exists');
            redirect('gazette/index');
        }

        // Insert into status table
        $stat_array = array(
            'gazette_id' => $gazette_id,
            'user_id' => $this->session->userdata('user_id'),
            'dept_id' => 0,
            'status_id' => 3,
            'reject_remarks' => $this->input->post('reject_remarks'),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s', time())
        );

        if ($this->gazette_model->reject_gazette($stat_array)) {

            $gazette_details = $this->gazette_model->get_gazette_user_details($gazette_id);

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
                            <p style=\"color: #000 !important\">Hii {$gazette_details->name},</p>
                            <p style=\"color: #000 !important\">
                                Gazette has been rejected by Director of Printing, Stationary and Publication for (StateName) Press E-Gazette System.<br/>
                                Department : {$gazette_details->department_name}<br/>
                                Gazette Sl No.: {$gazette_details->sl_no}<br/>
                                Gazette Type : {$gazette_details->gazette_type}<br/>
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
            $this->email->to($gazette_details->email);
            $this->email->subject('Gazette Published By Directorate of Printing, Stationary and Publication');
            $this->email->message($email_content);
            $this->email->set_newline("\r\n");
            $this->email->send();

            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'Extraordinary Gazette', 'Press Returned', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
            $this->session->set_flashdata('success', 'Gazette has been returned to department successfully');
            redirect('gazette/press_view/' . $gazette_id);
        } else {
            $this->session->set_flashdata('error', 'Gazette not returned');
            redirect('gazette/press_view/' . $gazette_id);
        }
    }

    /*
     * Department submitted
     */

    public function dept_submitted() {

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

        $gazette_id = $this->input->post('gazette_id');
        // sined PDF file path
        $signed_file = './uploads/dept_signed_pdf/' . $gazette_id . time() . '.pdf';
        // decode the signed file data
        $decodedData = base64_decode($this->input->post('binaryfile'));
        // put the signed file data into path and physically store the signed PDF file
        file_put_contents($signed_file, $decodedData);

        // Insert into status table
        $array = array(
            'gazette_id' => $gazette_id,
            'user_id' => $this->session->userdata('user_id'),
            'dept_id' => 0,
            'status_id' => 1,
            'dept_signed_pdf_path' => $signed_file,
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s', time()),
            'modified_at' => date('Y-m-d H:i:s', time())
        );

        if ($this->gazette_model->dept_submit_gazette($array)) {
            
            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'Extraordinary Gazette', 'Department Submitted', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            
            /*
             * Remove this line in real app
             */
            $this->session->unset_userdata('demo_signed');
            
            $this->session->set_flashdata('success', 'Gazette submitted successfully');
            redirect('gazette/index');
        } else {
            $this->session->set_flashdata('error', 'Gazette not submitted');
            redirect('gazette/dept_preview/' . $gazette_id);
        }
    }

    /*
     * Press preview
     * @Author Shivaram Mahapatro
     * @Date 7/8/2021
     * Publishing process has been changed from Word template to PDF template processing
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

        $this->form_validation->set_rules('gazette_id', 'Gazette ID', 'trim|required');
        $this->form_validation->set_rules('sl_no', 'Sl No', 'trim|required|numeric');
        $this->form_validation->set_rules('issue_date', 'Issue Date', 'trim|required');
        $this->form_validation->set_rules('saka_month', 'Shakabda Month', 'trim|required');
        $this->form_validation->set_rules('saka_date', 'Shakabda Date', 'trim|required');
        $this->form_validation->set_rules('saka_year', 'Shakabda Year', 'trim|required');
        
        $gazette_id = $this->input->post('gazette_id');
        
        if ($this->form_validation->run() == false) {
           // echo validation_errors();
        } else {
            
            // Start Transaction
            try {

                //$this->db->trans_start();

                $sl_no = $this->input->post('sl_no');

                $sql_sl_no = "SELECT * FROM gz_gazette WHERE sl_no = '{$sl_no}' AND YEAR(created_at) = " . date('Y');
                
                $result = $this->db->query($sql_sl_no);

                if ($result->num_rows() > 0) {
                    //Put the array in a session            
                    $this->session->set_flashdata('error', 'Sl No. exists for another Gazette');
                    redirect('gazette/press_view/' . $gazette_id);

                } else {

                    // get the file submitted by Dept. user and make the required changes in the PDF file
                    $gazette_docs = $this->gazette_model->get_gazette_documents($gazette_id);
            
                    // SRO No
                    if (!empty($this->input->post('sro_no'))) {
                        $sro_no = $this->input->post('sro_no');
                    } else {
                        $sro_no = '';
                    }

                    // data needs to be updated
                    $array_data = array(
                        'gazette_id' => $gazette_id,
                        'user_id' => $this->session->userdata('user_id'),
                        'issue_date' => strtoupper($this->input->post('issue_date')),
                        'sl_no' => $this->input->post('sl_no'),
                        //'sakabda_date' => $this->input->post('sakabda_date'),
                        'saka_month' => $this->input->post('saka_month'),
                        'saka_date' => $this->input->post('saka_date'),
                        'saka_year' => $this->input->post('saka_year'),
                        'sro_no' => $this->input->post('sro_no'),
                        // press preview status
                        'status_id' => 6,
                            //'press_pdf_file_path' => $press_pdf,
                    );

                    $sl_no = $this->input->post('sl_no');
                    // get the userdata from database using model
                    $result = $this->gazette_model->save_preview_press_gazette($array_data);
                    
                    $usr_no = $this->db->select('*')
                        ->from('gz_gazette')
                        ->where('id', $gazette_id)
                        ->get()->row();
                     //echo $usr_no->user_id;
                    $mob_no =  $this->db->select('*')
                        ->from('gz_users')
                        ->where('id', $usr_no->user_id)
                        ->get()->row();
                
                    // load SMS library will activate once live
                    $this->load->library("cdac_sms");
                    
                    if(!empty($usr_no)) { 
                         if(!empty($usr_no->is_paid == '0')) { 

                            // message format
                            $message = "Extraordinary Gazette File No. {$sl_no} has been approved by the Govt. Press. Govt. of (StateName).";
                            $sms_api = new Cdac_sms();
                            // send SMS using API
                            $template_id = "1007938090042852633";
                            $sms_api->sendOtpSMS($message, $mob_no->mobile, $template_id);
                         }
                    }

                    $dept_pdf_file = FCPATH . $gazette_docs->dept_pdf_file_path;
                    
                    $this->load->library('phpqrcode/qrlib');
                    
                    $dept_name = $this->db->select('d.department_name')
                                        ->from('gz_gazette g')
                                        ->join('gz_department d', 'd.id = g.dept_id')
                                        ->where('g.id', $gazette_id)
                                        ->get()->row()->department_name;
                    
                    $qr_text = "Gazette Number:" . $this->input->post('sl_no') . " " . "Department Name:" . $dept_name . " " . "Published Date:" . $this->input->post('issue_date');
                    
                    $folder = 'uploads/qrcodes/';
                    $file = $gazette_id . "_" . md5(time()) . ".png";
                    $file_name = $folder . $file;
                    
                    QRcode::png($qr_text, $file_name);
                    
                    $dynamic_data = array(
                        'gazette_id' => $gazette_id,
                        'sl_no' => $this->input->post('sl_no'),
                        'issue_date' => $this->input->post('issue_date'),
                        'sakabda_date' => $this->input->post('sakabda_date'),
                        'sro_no' => $sro_no,
                        'qr_code' => "C:/xampp/htdocs/" . $file_name
                    );

                    // Load PDF library to generate the dynamic PDF
                    $this->load->library('pdf');

                    $header_image = "./assets/images/header.jpg";
                    $line_top_image = "./assets/images/line_top.png";
                    $line_bottom_image = "./assets/images/line_bottom.png";

                    // Create new PDF Object
                    $pdf = new PDF(); // Array sets the X, Y dimensions in mm

                    // Set the Parameters in PDF file
                    $pdf->set_parameters($header_image, $line_top_image, $line_bottom_image, $dynamic_data['sl_no'], $dynamic_data['sro_no'], $dynamic_data['issue_date'], $this->input->post('saka_month'), $this->input->post('saka_date'), $this->input->post('saka_year'), $dynamic_data['qr_code']);

                    $pdf->AliasNbPages();
                    // Is last page. Set to false initially
                    $pdf->isFinished = false;
                    // Set the source file
                    $pagecount = $pdf->setSourceFile($dept_pdf_file);
                    // iterate through all pages
                    for ($pageNo = 1; $pageNo <= $pagecount; $pageNo++) {
                        // import a page
                        $templateId = $pdf->importPage($pageNo);
                        // Add the pages
                        $pdf->AddPage();
                        
                        // use the imported page and adjust the page size
                        $pdf->useTemplate($templateId, ['adjustPageSize' => true]);

                    }

                    // Finished at the last page
                    $pdf->isFinished = true;

                    $press_pdf_file = './uploads/press_pdf/' . time() . '.pdf';

                    $pdf->Output($press_pdf_file, "F");

                    // UPDATE press PDF in documents table
                    $this->db->where('gazette_id', $gazette_id);
                    $this->db->update('gz_documents', array('press_pdf_file_path' => $press_pdf_file));

                    if ($result) {
                        redirect('gazette/press_publish_view/' . $gazette_id);
                    } else {
                        //Put the array in a session            
                        $this->session->set_flashdata('error', 'Something went wrong');
                        redirect('gazette/index');
                    }
                }

                //if ($this->db->trans_status() == FALSE) {
                //    $this->db->trans_rollback();
                //} else {
                //    $this->db->trans_commit();
                //}

            } catch (Exception $ex) {

            }
        }
    }


    /*
     * Press publish view
     */

    public function press_publish_view($gazette_id) {
        //$this->output->enable_profiler(true);
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

        if (!$this->gazette_model->exists($gazette_id)) {
            $this->session->set_flashdata('error', 'The gazette does not exists');
            redirect('gazette/index');
        }

        $data['title'] = "View Gazette";
        $data['gazette_id'] = $gazette_id;
        $data['details'] = $this->gazette_model->get_gazette_details($gazette_id);

        $this->form_validation->set_rules('gazette_id', 'Gazette ID', 'trim|required');

        if ($this->form_validation->run() == false) {
            //$this->load->view('gazette/press_add', $data);
        } else {

            $gaze_id = $this->input->post('gazette_id');
            redirect('gazette/press_pdf_view/' . $gaze_id);
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('gazette/press_publish_view.php', $data);
        $this->load->view('template/footer.php');
    }

    public function press_pdf_view($gazette_id) {
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

        $data['title'] = "View Gazette";
        $data['gazette_id'] = $gazette_id;
        $data['details'] = $this->gazette_model->get_gazette_details($gazette_id);

        // user details to be passed 
        $data['signed_name'] = $this->session->userdata('name');
        $data['signed_designation'] = $this->session->userdata('designation');

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('gazette/press_pdf_view.php', $data);
        $this->load->view('template/footer.php');
    }



    public function set_read_admin_dept(){

        $id = $this->security->xss_clean($this->input->post('id'));

        $update_notification = array(
            'is_read' => 1,
        );

        $this->db->where('id', $id);
        $this->db->update('gz_notification', $update_notification);
            

        return true;
    }

    public function set_read_admin_dept_weekly(){
        $id = $this->security->xss_clean($this->input->post('id'));

        $update_notification = array(
            'is_read' => 1,
        );

        $this->db->where('id', $id);
        $this->db->update('gz_weekly_gazette_notification', $update_notification);
            

        return true;
    }

}

?>