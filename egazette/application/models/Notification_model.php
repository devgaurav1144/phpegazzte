<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php 
class Notification_model extends CI_Model {
    
    public function get_admin_notifications_count($user_id) {
        $extra_or_notification = $this->db->select('gn.*, "1" as gazette_type, gd.is_paid')
                                ->from('gz_notification gn')
                                ->join('gz_gazette gd', 'gd.id = gn.gazette_id')
                                ->where('gn.responsible_user_id', $user_id)
                                ->order_by('gn.id', 'DESC')
                                ->get()->result();

        $name_surname_notification = $this->db->select('gn.*, "3" as gazette_type, gn.master_id as gazette_id, gd.is_paid')
                                ->from('gz_notification_govt gn')
                                ->join('gz_change_of_partnership_master gd', 'gd.id = gn.master_id')
                                ->where('gn.responsible_user_id', $user_id)
                                ->order_by('gn.id', 'DESC')
                                ->get()->result();
                                
        $weekly_gazette = $this->db->select('*, "2" as gazette_type')
                                ->from('gz_weekly_gazette_notification')
                                ->where('responsible_user_id', $user_id)
                                ->order_by('id', 'DESC')
                                ->get()->result();
        // print_r(array_merge($extra_or_notification, $weekly_gazette,$name_surname_notification));exit;
        return count(array_merge($extra_or_notification, $weekly_gazette,$name_surname_notification));
    }
    public function get_admin_notifications($user_id,$limit, $offset) {

        $extra_or_notification = $this->db->select('gn.*, "1" as gazette_type, gd.is_paid')
                                ->from('gz_notification gn')
                                ->join('gz_gazette gd', 'gd.id = gn.gazette_id')
                                ->where('gn.responsible_user_id', $user_id)
                                ->order_by('gn.id', 'DESC')
                                ->get()->result();

        $name_surname_notification = $this->db->select('gn.*, "3" as gazette_type, gn.master_id as gazette_id, gd.is_paid')
                                ->from('gz_notification_govt gn')
                                ->join('gz_change_of_partnership_master gd', 'gd.id = gn.master_id')
                                ->where('gn.responsible_user_id', $user_id)
                                ->order_by('gn.id', 'DESC')
                                ->get()->result();
                                
        $weekly_gazette = $this->db->select('*, "2" as gazette_type')
                                ->from('gz_weekly_gazette_notification')
                                ->where('responsible_user_id', $user_id)
                                ->order_by('id', 'DESC')
                                ->get()->result();
// echo $limit;
// echo $offset;
// exit;

        // print_r(array_merge($extra_or_notification, $weekly_gazette,$name_surname_notification));exit;
        $data = array_merge($extra_or_notification, $weekly_gazette,$name_surname_notification);
        return array_slice($data,$offset,$limit);
    }
    
    public function get_user_notifications_count($user_id) {
        $extra_or_notification = $this->db->select('gn.*, "1" as gazette_type, gd.is_paid, gd.user_id AS route_user_id')
                                    ->from('gz_notification gn')
                                    ->join('gz_gazette gd', 'gd.id = gn.gazette_id')
                                    ->where('gn.responsible_user_id', $user_id)
                                    ->order_by('gn.id', 'DESC')
                                    ->get()->result();

        $weekly_gazette = $this->db->select('*, "2" as gazette_type')
                                ->from('gz_weekly_gazette_notification')
                                // ->where('is_read', 0)
                                // ->where('user_id', $user_id)
                                ->where('responsible_user_id', $user_id)
                                ->order_by('id', 'DESC')
                                ->get()->result();

        return count(array_merge($extra_or_notification, $weekly_gazette));

        
    }
    public function get_user_notifications($user_id,$limit, $offset) {
        $extra_or_notification = $this->db->select('gn.*, "1" as gazette_type, gd.is_paid, gd.user_id AS route_user_id')
                                    ->from('gz_notification gn')
                                    ->join('gz_gazette gd', 'gd.id = gn.gazette_id')
                                    ->where('gn.responsible_user_id', $user_id)
                                    ->order_by('gn.id', 'DESC')
                                    ->get()->result();

        $weekly_gazette = $this->db->select('*, "2" as gazette_type')
                                ->from('gz_weekly_gazette_notification')
                                // ->where('is_read', 0)
                                // ->where('user_id', $user_id)
                                ->where('responsible_user_id', $user_id)
                                ->order_by('id', 'DESC')
                                ->get()->result();

        $data = array_merge($extra_or_notification, $weekly_gazette);
        return array_slice($data,$offset,$limit);
        
    }
    
    public function get_admin_notifications_latest($user_id) {
        $extra_or_notification =  $this->db->select('gn.*, "1" as gazette_type, gd.is_paid')
                                            ->from('gz_notification gn')
                                            ->join('gz_gazette gd', 'gd.id = gn.gazette_id')
                                            ->where('gn.is_read', 0)
                                            ->where('gn.responsible_user_id', $user_id)
                                            ->order_by('gn.id', 'DESC')
                                            ->limit(5)
                                            ->get()->result();
        $name_surname_notification = $this->db->select('gn.*, "3" as gazette_type, gn.master_id as gazette_id, gd.is_paid')
                                                ->from('gz_notification_govt gn')
                                                ->where('gn.is_read', 0)
                                                ->join('gz_change_of_partnership_master gd', 'gd.id = gn.master_id')
                                                ->where('gn.responsible_user_id', $user_id)
                                                ->order_by('gn.id', 'DESC')
                                                ->get()->result();
        $weekly_gazette = $this->db->select('*, "2" as gazette_type')
                                    ->from('gz_weekly_gazette_notification')
                                    ->where('is_read', 0)
                                    ->where('responsible_user_id', $user_id)
                                    ->order_by('id', 'DESC')
                                    ->limit(5)
                                    ->get()->result();

        $all_notification = array_merge($extra_or_notification, $weekly_gazette,$name_surname_notification);
        return array_slice($all_notification,0,5);
    }
    
    public function get_user_notifications_latest($user_id) {
        $extra_or_notification =  $this->db->select('gn.*, "1" as gazette_type, gd.is_paid')
                                    ->from('gz_notification gn')
                                    ->join('gz_gazette gd', 'gd.id = gn.gazette_id')
                                    ->where('gn.responsible_user_id', $user_id)
                                    ->where('is_read', 0)
                                    ->order_by('gn.id', 'DESC')
                                    ->limit(5)
                                    ->get()->result();

        $weekly_gazette = $this->db->select('*, "2" as gazette_type')
                                ->from('gz_weekly_gazette_notification')
                                ->where('is_read', 0)
                                // ->where('user_id', $user_id)
                                ->where('responsible_user_id', $user_id)
                                ->order_by('id', 'DESC')
                                ->limit(5)
                                ->get()->result();

        $all_notification = array_merge($extra_or_notification, $weekly_gazette);

        return array_slice($all_notification,0,5);
    }

    public function get_applicant_notifications_latest($user_id){
        return $this->db->select('*')
                ->from('gz_notification_applicant')
                ->where('is_viewed', 0)
                ->where('responsible_user_id', $user_id)
                ->order_by('id', 'DESC')
                ->limit(5)
                ->get()->result();
    }

    public function get_cnt_notifications_latest($user){
            return $this->db->select('*')
                    ->from('gz_notification_ct')
                    ->where('responsible_user_id', $this->session->userdata('user_id'))
                    ->where('is_viewed', 0)
                    ->order_by('id', 'DESC')
                    ->limit(5)
                    ->get()->result();
    }

    public function get_igr_notifications_latest($user){
                return $this->db->select('*')
                ->from('gz_notification_igr')
                ->where('responsible_user_id', $this->session->userdata('user_id'))
                ->where('is_viewed', 0)
                ->order_by('id', 'DESC')
                ->limit(5)
                ->get()->result();
    }

    public function get_applicant_notifications_count($user_id){
        return $this->db->select('*')
                ->from('gz_notification_applicant')
                // ->where('is_viewed', 0)
                ->where('responsible_user_id', $user_id)
                ->order_by('id', 'DESC')
                ->count_all_results();
    }

    public function get_cnt_notifications_count($user){
        return $this->db->select('*')
                ->from('gz_notification_ct')
                ->where('responsible_user_id', $this->session->userdata('user_id'))
                ->order_by('id', 'DESC')
                ->count_all_results();
    }

    public function get_igr_notifications_count($user){
        return $this->db->select('*')
        ->from('gz_notification_igr')
        ->where('responsible_user_id', $this->session->userdata('user_id'))
        // ->where('is_viewed', 0)
        ->order_by('id', 'DESC')
        ->count_all_results();
    }

    public function get_applicant_notifications($user_id,$limit, $offset){
        return $this->db->select('*')
                ->from('gz_notification_applicant')
                // ->where('is_viewed', 0)
                ->where('responsible_user_id', $user_id)
                ->order_by('id', 'DESC')
                ->limit($limit, $offset)
                ->get()->result();
    }    

    public function get_cnt_notifications($user,$limit, $offset){
        return $this->db->select('*')
                ->from('gz_notification_ct')
                ->where('responsible_user_id', $this->session->userdata('user_id'))
                ->order_by('id', 'DESC')
                ->limit($limit, $offset)
                ->get()->result();
    }
    
    public function get_igr_notifications($user,$limit, $offset){
        return $this->db->select('*')
        ->from('gz_notification_igr')
        ->where('responsible_user_id', $this->session->userdata('user_id'))
        // ->where('is_viewed', 0)
        ->order_by('id', 'DESC')
        ->limit($limit, $offset)
        ->get()->result();
    }
    
    public function exists($id) {
        $result = $this->db->select('*')->from('gz_department')->where('id', $id)->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function exists_applicant($id){
        $result = $this->db->select('*')
                           ->from('gz_notification_applicant')
                           ->where('id', $id)
                           ->get();
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function set_read($id) {
        $this->db->where('id', $id);
        $this->db->update('gz_notification', array('is_read' => 1));
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function set_viewed($id) {
        //var_dump($id);die();
        $this->db->where('id', $id);
        $this->db->update('gz_notification_applicant', array('is_viewed' => 1));
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function get_notification_details($id) {
        return $this->db->select('*')->from('gz_notification')->where('id', $id)->get()->row();
    }
    
    public function get_total_notifications() {
        return $this->db->select('*')->from('gz_notification')->count_all_results();
    }

    public function get_gazette_status($gazette_id, $gazette_type){
        if($gazette_type == "2"){

            $data = $this->db->select('*')->from('gz_weekly_gazette')->where('id', $gazette_id)->get()->row();
            
        }
    }
}