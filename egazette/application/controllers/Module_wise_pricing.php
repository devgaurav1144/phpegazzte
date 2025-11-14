<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    
class Module_wise_pricing extends MY_Controller {
    /**
    * __construct function.
    */
    public function __construct() {
       parent::__construct();
       $this->load->database();
       $this->load->library(array('session', 'form_validation', 'pagination', 'my_pagination'));
       $this->load->helper(array('url', 'form', 'string', 'text', 'custom'));
       $this->load->model(array('Module_wise_pricing_model'));
    }
    
    public function index() {
        
        if (!$this->session->userdata('logged_in') || !$this->session->userdata('is_admin')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('user/login');
        }

        $data['title'] = "Module wise Pricing";
        
        $config = array();
        $config["base_url"] = base_url() . "module_wise_pricing/index";

        $config["total_rows"] = $this->Module_wise_pricing_model->get_total_module_wise_pricings();

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
        $data['pricings'] = $this->Module_wise_pricing_model->get_module_wise_pricings($config['per_page'], $offset);
        
        $data['modules'] = $this->Module_wise_pricing_model->get_modules_for_pricing();
        
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('module_wise_pricing/index.php', $data);
        $this->load->view('template/footer.php');
    }
    
    public function add(){
        if (!$this->session->userdata('logged_in') || !$this->session->userdata('is_admin')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('user/login');
        }
        
        $json = array();
        
        $this->form_validation->set_rules('module_id', 'Module Name', 'required');
        $this->form_validation->set_rules('price_pp', 'Pricing Per Page', 'trim|required|min_length[2]|max_length[5]');
        if ($this->form_validation->run() == false) {
            $json['error'] = $this->form_validation->error_array();
        } else {
            $module_id = $this->security->xss_clean($this->input->post('module_id'));
            $pricing = $this->security->xss_clean($this->input->post('price_pp'));
            
            $array_data = array(
                'module_id' => $module_id,
                'pricing' => $pricing,
            );
            $result = $this->Module_wise_pricing_model->add($array_data);
            
            if($result){
                audit_action_log($this->session->userdata('user_id'), 'Module wise pricing', 'Add', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata("success", "Module wise pricing added successfully");
                $json['redirect'] = base_url() . "module_wise_pricing/index";
            } else {
                $this->session->set_flashdata("error", "Something went wrong");
                $json['redirect'] = base_url() . "module_wise_pricing/index";
            }
        }
        echo json_encode($json);
    }
    
    /*
     * Edit module wise pricing
     */
    public function edit_pricing(){
        if (!$this->session->userdata('logged_in') || !$this->session->userdata('is_admin')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('user/login');
        }
        
        $json = array();
        
        $this->form_validation->set_rules('module_id', 'Module Name', 'required');
        $this->form_validation->set_rules('price_pp', 'Pricing Per Page', 'trim|required|min_length[2]|max_length[5]');
        if ($this->form_validation->run() == false) {
            $json['error'] = $this->form_validation->error_array();
        } else {
            $module_id = $this->security->xss_clean($this->input->post('module_id'));
            $pricing = $this->security->xss_clean($this->input->post('price_pp'));
            $mod_id = $this->security->xss_clean($this->input->post('mod_id'));
            
            $array_data = array(
                'module_id' => $module_id,
                'pricing' => $pricing,
                'mod_id' => $mod_id
            );
            $result = $this->Module_wise_pricing_model->edit_pricing($array_data);
            
            if($result){
                audit_action_log($this->session->userdata('user_id'), 'Module wise pricing', 'Edit', date('Y-m-d H:i:s', time()), $this->input->ip_address());
                
                $this->session->set_flashdata("success", "Module wise pricing updated successfully");
                $json['redirect'] = base_url() . "module_wise_pricing/index";
            } else {
                $this->session->set_flashdata("error", "Something went wrong");
                $json['redirect'] = base_url() . "module_wise_pricing/index";
            }
        }
        echo json_encode($json);
    }
    
    /*
     * Delete pricing
     */
    public function delete(){
        if (!$this->session->userdata('logged_in') || !$this->session->userdata('is_admin')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('user/login');
        }
        
        $id = $this->security->xss_clean($this->input->post('id'));

        if (!is_numeric($id) || !$this->Module_wise_pricing_model->exists($id)) {
            $this->session->set_flashdata('error', 'Pricing does not exists');
            redirect('module_wise_pricing/index');
        }

        if ($this->Module_wise_pricing_model->delete($id)) {
            
            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'Module wise pricing', 'Delete', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            
            $this->session->set_flashdata('success', 'Pricing for module deleted successfully');
            redirect('module_wise_pricing/index');
        } else {
            $this->session->set_flashdata('error', 'Pricing for module not deleted');
            redirect('module_wise_pricing/index');
        }
    }
    
    /*
     * Edit module wise pricing
     */
    public function edit($id){
        if (!$this->session->userdata('logged_in') && !$this->session->userdata('is_admin')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('user/login');
        }
        
        if (!is_numeric($id) || !$this->Module_wise_pricing_model->exists($id)) {
            $this->session->set_flashdata('error', 'Module wise pricing does not exists');
            redirect('module_wise_pricing/index');
        }
        
        $data['title'] = "Edit Module wise pricing";
        
        $data['modules'] = $this->Module_wise_pricing_model->get_modules_for_pricing_edit();
        $data['sel_data'] = $this->Module_wise_pricing_model->get_selected_data($id);
        //print_r($data['modules']);exit();
        $this->load->view('template/header.php', $data);
        $this->load->view('template/sidebar.php');
        $this->load->view('module_wise_pricing/edit.php', $data);
        $this->load->view('template/footer.php');
    }
    
    /*
     * Status change
     */
    public function status_change(){
        if (!$this->session->userdata('logged_in') || !$this->session->userdata('is_admin')) {
            $this->session->set_flashdata('error', 'You are not authorized to access the page');
            redirect('user/login');
        }
        
        $id = $this->security->xss_clean($this->input->post('user_id'));
        $status = $this->security->xss_clean($this->input->post('status'));
        
        if (!is_numeric($id) || !$this->Module_wise_pricing_model->exists($id)) {
            $this->session->set_flashdata('error', 'Nodal officer does not exists');
            redirect('module_wise_pricing/index');
        }
        
        if ($this->Module_wise_pricing_model->status_change($id, $status)) {
            // Store Audit Log
            audit_action_log($this->session->userdata('user_id'), 'Module wise pricing', 'Status Change', date('Y-m-d H:i:s', time()), $this->input->ip_address());
            
            $this->session->set_flashdata('success', 'Pricing for module status updated successfully');
            redirect('module_wise_pricing/index');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong');
            redirect('module_wise_pricing/index');
        }
    }
}

?>
