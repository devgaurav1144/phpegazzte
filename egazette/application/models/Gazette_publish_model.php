
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php 
class Gazette_publish_model extends CI_Model {
    
    public function get_publish_date() {
        return $this->db->select('*')
                ->from('gz_weekly_gazette_publish_date')
                ->get()->row();
    }

	public function update($data) {
		$array_data = array(
			'day_name'=> $data
		);
		$this->db->where('id', 1);
        $this->db->update('gz_weekly_gazette_publish_date', $array_data);
        return ($this->db->affected_rows() > 0) ? true : false;
    } 
}
?>