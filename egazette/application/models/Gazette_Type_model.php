<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php 
class Gazette_Type_model extends CI_Model {
    
    public function getGazetteTypeList() {
        return $this->db->select('*')
                ->from('gz_gazette_type')
                ->order_by('id', 'DESC')
                ->get()->result();
    }
    
    public function addGazetteType() {
        
    }
    
    public function get_notification_types() {
        return $this->db->select('*')
                ->from('gz_notification_type')
                ->order_by('notification_type', 'ASC')
                ->get()->result();
    }
    
}