<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php 
class Settings_model extends CI_Model {
    
    public function set_smtp($data = array()) { 
		
		$query = $this->db->select('*')->from('gz_settings')->where('setting_key', 'smtp')->get();
		
		if ($query->num_rows() > 0) {
			// UPDATE
			$this->db->where('setting_key', 'smtp');
			$this->db->where('action_key', 'host');
			$this->db->update('gz_settings', array('action_value' => $data['host']));
			$this->db->where('setting_key', 'smtp');
			$this->db->where('action_key', 'username');
			$this->db->update('gz_settings', array('action_value' => $data['username']));
			$this->db->where('setting_key', 'smtp');
			$this->db->where('action_key', 'password');
			$this->db->update('gz_settings', array('action_value' => $data['password']));
			$this->db->where('setting_key', 'smtp');
			$this->db->where('action_key', 'port');
			$this->db->update('gz_settings', array('action_value' => $data['port']));
			$this->db->where('setting_key', 'smtp');
			$this->db->where('action_key', 'protocol');
			$this->db->update('gz_settings', array('action_value' => $data['protocol']));

			if ($this->db->affected_rows()) {
				return true;
			} else {
				return false;
			}
			
		} else {
			$todayDate=date('Y-m-d H:i:s');
			$array_data = array(
				array(
					'setting_key'=>'smtp',
					'action_key'=>'host',
					'action_value'=>$data['host'],
					'created_at'=>$todayDate,
				),
				array(
					'setting_key'=>'smtp',
					'action_key'=>'username',
					'action_value'=>$data['protocol'],
					'created_at'=>$todayDate,
				),
				array(
					'setting_key'=>'smtp',
					'action_key'=>'password',
					'action_value'=>$data['username'],
					'created_at'=>$todayDate,
				),
				array(
					'setting_key'=>'smtp',
					'action_key'=>'port',
					'action_value'=>$data['password'],
					'created_at'=>$todayDate,
				),
				array(
					'setting_key'=>'smtp',
					'action_key'=>'protocol',
					'action_value'=>$data['port'],
					'created_at'=>$todayDate,
				),
	        );
			$this->db->insert_batch('gz_settings', $array_data);
			return true;
		}
        
    }

    public function get_smtp() {
        return $this->db->select('*')->from('gz_settings')->where('setting_key', 'smtp')->get()->result();
    }

    /*SMS part*/
    public function set_sms($data = array()) { 
		
		$query = $this->db->select('*')->from('gz_settings')->where('setting_key', 'sms')->get();
		
		if ($query->num_rows() > 0) {
			// UPDATE
			$this->db->where('setting_key', 'sms');
			$this->db->where('action_key', 'api_key');
			$this->db->update('gz_settings', array('action_value' => $data['api_key']));
			$this->db->where('setting_key', 'sms');
			$this->db->where('action_key', 'endpoint_url');
			$this->db->update('gz_settings', array('action_value' => $data['endpoint_url']));
			$this->db->where('setting_key', 'sms');
			$this->db->where('action_key', 'sender_id');
			$this->db->update('gz_settings', array('action_value' => $data['sender_id']));

			if ($this->db->affected_rows()) {
				return true;
			} else {
				return false;
			}
			
		} else {
			$todayDate=date('Y-m-d H:i:s');
			$array_data = array(
				array(
					'setting_key'=>'sms',
					'action_key'=>'api_key',
					'action_value'=>$data['api_key'],
					'created_at'=>$todayDate,
				),
				array(
					'setting_key'=>'sms',
					'action_key'=>'endpoint_url',
					'action_value'=>$data['endpoint_url'],
					'created_at'=>$todayDate,
				),
				array(
					'setting_key'=>'sms',
					'action_key'=>'sender_id',
					'action_value'=>$data['sender_id'],
					'created_at'=>$todayDate,
				),
	        );
			$this->db->insert_batch('gz_settings', $array_data);
			return true;
		}
    }

    public function get_sms() {
        return $this->db->select('*')->from('gz_settings')->where('setting_key', 'sms')->get()->result();
    }
    /* ./. SMS part*/

    /*Payment Gateway Model*/
    public function set_paygat($data = array()) { 
		
		$query = $this->db->select('*')->from('gz_settings')->where('setting_key', 'payget')->get();
		
		if ($query->num_rows() > 0) {
			// UPDATE
			$this->db->where('setting_key', 'payget');
			$this->db->where('action_key', 'api_key');
			$this->db->update('gz_settings', array('action_value' => $data['api_key']));
			$this->db->where('setting_key', 'payget');
			$this->db->where('action_key', 'pay_token');
			$this->db->update('gz_settings', array('action_value' => $data['pay_token']));
			$this->db->where('setting_key', 'payget');
			$this->db->where('action_key', 'pay_salt');
			$this->db->update('gz_settings', array('action_value' => $data['pay_salt']));


			if ($this->db->affected_rows()) {
				return true;
			} else {
				return false;
			}
			
		} else {
			$todayDate=date('Y-m-d H:i:s');
			$array_data = array(
				array(
					'setting_key'=>'payget',
					'action_key'=>'api_key',
					'action_value'=>$data['api_key'],
					'created_at'=>$todayDate,
				),
				array(
					'setting_key'=>'payget',
					'action_key'=>'pay_token',
					'action_value'=>$data['pay_token'],
					'created_at'=>$todayDate,
				),
				array(
					'setting_key'=>'payget',
					'action_key'=>'pay_salt',
					'action_value'=>$data['pay_salt'],
					'created_at'=>$todayDate,
				),
	        );
			$this->db->insert_batch('gz_settings', $array_data);
			return true;
		}
    }

    public function get_paygat() {
        return $this->db->select('*')->from('gz_settings')->where('setting_key', 'payget')->get()->result();
    }
    /*./.Payment Gateway Model*/
    
}