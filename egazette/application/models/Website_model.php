<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php

class Website_model extends CI_Model {

    public function get_published_extraordinary_gazette_list() {
        return $this->db->select('gz.*, dept.department_name, docs.press_pdf_file_path,
                    docs.press_signed_pdf_path, press_signed_pdf_file_size')
                        ->from('gz_gazette gz')
                        ->join('gz_department dept', 'gz.dept_id = dept.id')
                        ->join('gz_documents docs', 'gz.id = docs.gazette_id')
                        // 1 for extraordinary 
                        ->where('gz.gazette_type_id', 1)
                        ->where('gz.status_id', 2)
                        ->order_by('gz.id', 'DESC')
                        ->limit(5)
                        ->get()->result();
    }

    public function get_published_weekly_gazette_list() {
        return $this->db->select('*')
                        ->from('gz_final_weekly_gazette')
                        ->order_by('id', 'DESC')
                        ->limit(5)
                        ->get()->result();
    }

    public function get_latest_published_data($limit = 3, $offset = 0) {
        // Query for Change of Name/Surname Gazettes
        $name_surname_query = $this->db->select('m.*, u.name, u.father_name')
                        ->from('gz_change_of_name_surname_master m')
                        ->join('gz_applicants_details u', 'm.user_id = u.id')
                        ->where('m.is_published', 1)
                        ->where('m.status', 1)
                        ->where('m.deleted', 0)
                        ->order_by('m.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();

        // Query for Change of Gender
        $gender_query = $this->db->select('m.*, u.name, u.father_name')
                        ->from('gz_change_of_gender_master m')
                        ->join('gz_applicants_details u', 'm.user_id = u.id')
                        ->where('m.is_published', 1)
                        ->where('m.status', 1)
                        ->where('m.deleted', 0)
                        ->order_by('m.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    
        // Query for Change of Partnership Details
        $cop_query = $this->db->select('p.*, u.name, u.father_name')
                     ->from('gz_change_of_partnership_master p')
                     ->join('gz_applicants_details u', 'p.user_id = u.id')
                     ->where('p.press_publish', 1)
                     ->order_by('p.id', 'DESC')
                     ->limit($limit, $offset)
                     ->get()->result();
    
        // Combine results into a single array
        $combined_data = [
            'name_surname_data' => $name_surname_query,
            'cop_data' => $cop_query,
            'gender_data' => $gender_query
        ];
    
        return $combined_data;
    }

    public function get_all_published_extraordinary_gazette_list($limit, $offset) {
        return $this->db->select('gz.*, dept.department_name, docs.press_pdf_file_path,
                    docs.press_signed_pdf_path, press_signed_pdf_file_size')
                        ->from('gz_gazette gz')
                        ->join('gz_department dept', 'gz.dept_id = dept.id')
                        ->join('gz_documents docs', 'gz.id = docs.gazette_id')
                        ->where('gz.status_id', 5)
                        ->order_by('gz.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    public function count_total_extra_published_gazettes() {
        return $this->db->select('gz.*, dept.department_name, docs.press_pdf_file_path,
                    docs.press_signed_pdf_path, press_signed_pdf_file_size')
                        ->from('gz_gazette gz')
                        ->join('gz_department dept', 'gz.dept_id = dept.id')
                        ->join('gz_documents docs', 'gz.id = docs.gazette_id')
                        ->where('gz.status_id', 5)
                        ->count_all_results();
    }

    public function get_all_published_weekly_gazette_list($limit, $offset) {
        return $this->db->select('*')
                        ->from('gz_final_weekly_gazette')
                        ->order_by('id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    public function count_total_published_weekly_gazettes() {
        return $this->db->select('*')
                        ->from('gz_final_weekly_gazette')
                        ->order_by('id', 'DESC')
                        ->count_all_results();
    }

    /*
     * Coun total published weekly gazettes in website home page
     */

    public function count_total_published_extra_gazettes_frontend() {
        return $this->db->select('*')
                        ->from('gz_gazette')
                        ->where('status_id', 5)
                        ->count_all_results();
    }

    /*
     * Coun total published weekly gazettes in website home page
     */

    public function count_total_published_weekly_gazettes_frontend() {
        return $this->db->select('*')
                        ->from('gz_final_weekly_gazette')
                        ->count_all_results();
    }

    /*
     * Count website visitor counter
     * @param NULL
     * @return int Counter
     */

    public function count_total_visitor_counter() {

        $data = $this->db->select('*')->from('gz_visitor_counter')->where('id', 1)->get();

        $array = array();

        if ($data->num_rows() == 0) {
            $array = array(
                'visit_counter' => 1,
                'updated_at' => date('Y-m-d H:i:s', time())
            );
            $this->db->insert('gz_visitor_counter', $array);
        } else {
            $query = $this->db->select('*')
                            ->from('gz_visitor_counter')
                            ->where('id', 1)->get()->row();
            $array = array(
                'visit_counter' => $query->visit_counter + 1,
                'updated_at' => date('Y-m-d H:i:s', time())
            );

            $this->db->where('id', 1);
            $this->db->update('gz_visitor_counter', $array);
        }

        return $this->db->select('*')->from('gz_visitor_counter')->where('id', 1)->get()->row()->visit_counter;
    }

    public function insert_feedback_method($data = array()) {
        $ins_array = array(
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'occupation' => $data['occupation'],
            'address' => $data['address'],
            'subject' => $data['subject'],
            'feedback' => $data['feedback'],
            'created_at' => date('Y-m-d H:i:s', time())
        );

        $this->db->insert('gz_feedback', $ins_array);

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    public function get_all_published_land_acquisition_gazette_list($limit, $offset) {
        return $this->db->select('gz.*, dept.department_name, docs.press_pdf_file_path,
                    docs.press_signed_pdf_path, press_signed_pdf_file_size')
                        ->from('gz_gazette gz')
                        ->join('gz_department dept', 'gz.dept_id = dept.id')
                        ->join('gz_documents docs', 'gz.id = docs.gazette_id')
                        ->where('gz.status_id', 5)->where('gz.notification_type', 'Land Acquisition')
                        ->order_by('gz.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    public function count_total_extra_land_acquisition_published_gazettes() {
        return $this->db->select('gz.*, dept.department_name, docs.press_pdf_file_path,
                    docs.press_signed_pdf_path, press_signed_pdf_file_size')
                        ->from('gz_gazette gz')
                        ->join('gz_department dept', 'gz.dept_id = dept.id')
                        ->join('gz_documents docs', 'gz.id = docs.gazette_id')
                        ->where('gz.status_id', 5)->where('gz.notification_type', 'Land Acquisition')
                        ->count_all_results();
    }

    public function get_all_published_bills_acts_gazette_list($limit, $offset) {
        return $this->db->select('gz.*, dept.department_name, docs.press_pdf_file_path,
                    docs.press_signed_pdf_path, press_signed_pdf_file_size')
                        ->from('gz_gazette gz')
                        ->join('gz_department dept', 'gz.dept_id = dept.id')
                        ->join('gz_documents docs', 'gz.id = docs.gazette_id')
                        ->where('gz.status_id', 5)->where('gz.notification_type', 'Bills & Acts')
                        ->order_by('gz.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    public function count_total_bills_acts_published_gazettes() {
        return $this->db->select('gz.*, dept.department_name, docs.press_pdf_file_path,
                    docs.press_signed_pdf_path, press_signed_pdf_file_size')
                        ->from('gz_gazette gz')
                        ->join('gz_department dept', 'gz.dept_id = dept.id')
                        ->join('gz_documents docs', 'gz.id = docs.gazette_id')
                        ->where('gz.status_id', 5)->where('gz.notification_type', 'Bills & Acts')
                        ->count_all_results();
    }

    public function get_all_published_surname_gazette_list($limit, $offset) {
        return $this->db->select('gz.*, dept.department_name, docs.press_pdf_file_path,
                    docs.press_signed_pdf_path, press_signed_pdf_file_size')
                        ->from('gz_gazette gz')
                        ->join('gz_department dept', 'gz.dept_id = dept.id')
                        ->join('gz_documents docs', 'gz.id = docs.gazette_id')
                        ->where('gz.status_id', 5)->where('gz.notification_type', 'Surname Change')
                        ->or_where('gz.notification_type', 'Partnership Deed Change')
                        ->order_by('gz.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    public function count_total_extra_surname_change_published_gazettes() {
        return $this->db->select('gz.*, dept.department_name, docs.press_pdf_file_path,
                    docs.press_signed_pdf_path, press_signed_pdf_file_size')
                        ->from('gz_gazette gz')
                        ->join('gz_department dept', 'gz.dept_id = dept.id')
                        ->join('gz_documents docs', 'gz.id = docs.gazette_id')
                        ->where('gz.status_id', 5)->where('gz.notification_type', 'Surname Change')
                        ->or_where('gz.notification_type', 'Partnership Deed Change')
                        ->count_all_results();
    }

    public function get_all_published_other_gazette_list($limit, $offset) {
        return $this->db->select('gz.*, dept.department_name, docs.press_pdf_file_path,
                    docs.press_signed_pdf_path, press_signed_pdf_file_size')
                        ->from('gz_gazette gz')
                        ->join('gz_department dept', 'gz.dept_id = dept.id')
                        ->join('gz_documents docs', 'gz.id = docs.gazette_id')
                        ->where('gz.status_id', 5)->where('dept.department_name', 'Raj Bhawan')
                        ->or_where('dept.department_name', '(StateName) High Court')
                        ->or_where('dept.department_name', '(StateName) Legislative Assembly')
                        ->or_where('dept.department_name', 'State Election Commission')
                        ->or_where('dept.department_name', 'State Cooperative Election Commission')
                        ->or_where('dept.department_name', 'Universities')
                        ->or_where('dept.department_name', 'Urban Development Authorities')
                        ->order_by('gz.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    public function count_total_extra_other_published_gazettes() {
        return $this->db->select('gz.*, dept.department_name, docs.press_pdf_file_path,
                    docs.press_signed_pdf_path, press_signed_pdf_file_size')
                        ->from('gz_gazette gz')
                        ->join('gz_department dept', 'gz.dept_id = dept.id')
                        ->join('gz_documents docs', 'gz.id = docs.gazette_id')
                        ->where('gz.status_id', 5)->where('dept.department_name', 'Raj Bhawan')
                        ->or_where('dept.department_name', '(StateName) High Court')
                        ->or_where('dept.department_name', '(StateName) Legislative Assembly')
                        ->or_where('dept.department_name', 'State Election Commission')
                        ->or_where('dept.department_name', 'State Cooperative Election Commission')
                        ->or_where('dept.department_name', 'Universities')
                        ->or_where('dept.department_name', 'Urban Development Authorities')
                        ->count_all_results();
    }

    public function site_launch() {

        $this->db->where('id', 1);
        $this->db->update('gz_site_launch', array('site_active' => 1));

        return ($this->db->affected_rows() > 0) ? true : false;
    }

    public function site_unlaunched() {

        $this->db->where('id', 1);
        $this->db->update('gz_site_launch', array('site_active' => 0));

        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /*
     * Count Applicant Change of Surname published 
     */
    public function count_total_change_name_surname_published_gazettes() {
        return $this->db->select('id')
                        ->from('gz_change_of_name_surname_master')
                        ->where('is_published', 1)
                        ->where('status', 1)
                        ->where('deleted', 0)
                        ->count_all_results();
    }
    
    /*
     * Count Applicant Change of Partnership published 
     */
    public function get_all_published_change_name_surnames_gazette_list($limit, $offset) {
        return $this->db->select('m.*, u.name, u.father_name')
                        ->from('gz_change_of_name_surname_master m')
                        ->join('gz_applicants_details u', 'm.user_id = u.id')
                        ->where('m.is_published', 1)
                        ->where('m.status', 1)
                        ->order_by('id', 'DESC')
                        ->where('m.deleted', 0)
                        ->order_by('id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    /*
     * change of partnership details 
     */

    public function count_total_cop_details($limit, $offset) {
        return $this->db->select('p.*,u.name,u.father_name')
                        ->from('gz_change_of_partnership_master p ')
                        ->join('gz_applicants_details u', 'p.user_id = u.id')
						->where('p.press_publish', 1)
                        ->order_by('p.id', 'DESC')
                        ->limit($limit, $offset)
                        ->get()->result();
    }

    /*
     * count total chnage of partnership details
     */

    public function count_total_cop() {
        return $this->db->select('*')
                        ->from('gz_change_of_partnership_master')
						->where('press_publish', 1)
                        ->count_all_results();
    }

}

?>