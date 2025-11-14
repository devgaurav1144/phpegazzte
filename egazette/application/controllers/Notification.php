<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends MY_Controller {

    /**
     * __construct function.
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'pagination', 'my_pagination'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom'));
        $this->load->model(array('notification_model'));
    }

    public function index() {
        if (!$this->session->userdata('logged_in')) {
            redirect('user/login');
        }

        $data['title'] = "Notification";
        $config["base_url"] = base_url() . "notification/index";
        if ($this->session->userdata('is_admin')) {
            $config['total_rows'] = $this->notification_model->get_admin_notifications_count($this->session->userdata('user_id'));
            
        } else {
            $config['total_rows'] = $this->notification_model->get_user_notifications_count($this->session->userdata('user_id'));
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
       
        if ($this->session->userdata('is_admin')) {
            $data['notifications'] = $this->notification_model->get_admin_notifications($this->session->userdata('user_id'), $config['per_page'], $offset);
            
        } else {
            $data['notifications'] = $this->notification_model->get_user_notifications($this->session->userdata('user_id'),$config['per_page'], $offset);
        }

        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('notification/index.php', $data);
        $this->load->view('template/footer.php');
    }

    public function all_notification(){
       
        $data['title'] = "Notification";
        $config["base_url"] = base_url() . "notification/all_notification";

        if ($this->session->userdata('is_applicant')) {
            $config['total_rows'] = $this->notification_model->get_applicant_notifications_count($this->session->userdata('user_id'));
        } elseif ($this->session->userdata('is_igr')) {
            $config['total_rows'] = $this->notification_model->get_igr_notifications_count($this->session->userdata('is_verifier_approver'));
        } else if($this->session->userdata('is_c&t')) {
            $config['total_rows'] = $this->notification_model->get_cnt_notifications_count($this->session->userdata('is_verifier_approver'));
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
            $data['notifications'] = $this->notification_model->get_applicant_notifications($this->session->userdata('user_id'), $config['per_page'], $offset);
        } elseif ($this->session->userdata('is_igr')) {
            $data['notifications'] = $this->notification_model->get_igr_notifications($this->session->userdata('is_verifier_approver'), $config['per_page'], $offset);
        } else if($this->session->userdata('is_c&t')) {
            $data['notifications'] = $this->notification_model->get_cnt_notifications($this->session->userdata('is_verifier_approver'), $config['per_page'], $offset);
        }

        $this->load->view('template/header_applicant.php', $data);
        $this->load->view('template/sidebar_applicant.php');
        $this->load->view('notification/user_index.php', $data);
        $this->load->view('template/footer_applicant.php');
    }

    public function get_notification_list() {
        if ($this->session->userdata('is_admin')) {
            $comments = $this->notification_model->get_admin_notifications_latest($this->session->userdata('user_id'));
        } else {
            $comments = $this->notification_model->get_user_notifications_latest($this->session->userdata('user_id'));
        }
        $output = "";
        if (!empty($comments)) {
            foreach ($comments as $comment) {
                $output .= "<li class=\"list-group-item\">
                    <a role=\"button\" tabindex=\"0\" class=\"media notif_click\" id=\"" . $comment->id . "\">
                        <input type=\"hidden\" name=\"gazette_id\" class=\"gazetee_id_val\" value=\"" . $comment->gazette_id . "\"/>
                        <div class=\"media-body\">
                            <span class=\"block\">" . character_limiter($comment->text, 20) . "</span>
                            <small class=\"text-muted\">" . get_formatted_datetime($comment->created_at) . "</small>
                        </div>
                    </a>
                </li>";
            }
        } else {
            $output .= "<div class=\"panel-footer\">
                <a role=\"button\" tabindex=\"0\">No Notifications
                </a>
            </div>";
        }
        echo $output;
    }

    public function set_read() {
        $json = array();
        if (!$this->session->userdata('logged_in')) {
            redirect('user/login');
        }

        $id = $this->input->post('id');

        if (!is_numeric($id) || !$this->notification_model->exists($id)) {
            $this->session->set_flashdata('error', 'Notification does notcc exists');
            redirect('notification/index');
        }

        if ($this->notification_model->set_read($id)) {
            $json['success'] = true;
        } else {
            $json['error'] = true;
        }

        echo json_encode($json);
    }

    public function set_viewed() {
        $json = array();
        if (!$this->session->userdata('logged_in')) {
            redirect('user/login');
        }

        $id = $this->input->post('id');
        //echo($id);die();
        // if (!is_numeric($id) || !$this->notification_model->exists_applicant($id)) {
        //     $this->session->set_flashdata('error', 'Notification does not exists');
        //     redirect('notification/user_index');
        // }
        if (!is_numeric($id)) {
            $this->session->set_flashdata('error', 'Notification does not exists');
            redirect('notification/user_index');
        }

        if ($this->notification_model->set_viewed($id)) {
            $json['success'] = true;
        } else {
            $json['error'] = true;
        }

        echo json_encode($json);
    }

}

?>