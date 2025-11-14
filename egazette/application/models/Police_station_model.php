<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
  class Police_station_model extends CI_Model {
    
        public function getPoliceStationList() {
            return $this->db->select('*')
                        ->from('gz_police_station_master')
                        // ->join('gz_district_master d', 'pm.district_id = d.id')
                        ->get()->result();
        }

        public function get_police_station_List($limit, $offset) {
            return $this->db->select('pm.id, pm.police_station_name, pm.status, d.district_name, s.state_name')
                            ->from('gz_police_station_master pm')
                            ->join('gz_district_master d', 'pm.district_id = d.id')
                            ->join('gz_states_master s', 'd.state_id = s.id')
                            ->where('pm.deleted' ,0)
                            ->order_by('pm.id', 'DESC')
                            ->limit($limit, $offset)
                            ->get()->result();
                            
        }
        
        public function get_total_police_station($data = array()) {
             $this->db->select('pm.*')
                        ->from('gz_police_station_master pm')
                        ->join('gz_district_master d', 'pm.district_id = d.id')
                        ->join('gz_states_master s', 'd.state_id = s.id')
                        ->where('pm.deleted' ,0);
                        if (!empty($data['state_name'])) {
                            $this->db->like('d.state_id', $data['state_name']);
                        }
                        if (!empty($data['district_name'])) {
                            $this->db->like('pm.district_id', $data['district_name']);
                        }
                        if (!empty($data['police_station_name'])) {
                            $this->db->like('pm.police_station_name', $data['police_station_name']);
                        }
                    return $this->db->count_all_results();
        }

        public function get_state_List() {
            return $this->db->select('*')->from('gz_states_master')->get()->result();
        }

        public function get_district_List($state_id) {
            return $this->db->select('*')
                            ->from('gz_district_master')
                            ->where('state_id', $state_id)
                            ->order_by('district_name', 'ASC')
                            ->get()->result();
        }

        public function add($data = array()){
            $array_data = array(
                'police_station_name' => $data['police_station_name'],
                'district_id' => $data['district_name']
            );

            return $this->db->insert('gz_police_station_master', $array_data);
        }

        public function get_police_station_details($id) {
            return $this->db->select('pm.id, pm.police_station_name, pm.status, pm.district_id, d.district_name, s.state_name, s.id as state_id')
                            ->from('gz_police_station_master pm')
                            ->join('gz_district_master d', 'pm.district_id  = d.id')
                            ->join('gz_states_master s', 'd.state_id = s.id')
                            ->where('pm.id', $id)
                            ->get()->row();
        }

        public function ps_search_result($limit, $offset, $data = array()){
            $this->db->select('b.id, b.police_station_name, b.status, d.district_name, s.state_name')
                            ->from('gz_police_station_master b')
                            ->join('gz_district_master d', 'b.district_id  = d.id')
                            ->join('gz_states_master s', 'd.state_id = s.id')
                            ->where('b.deleted' ,0);
            
                            if (!empty($data['state_name'])) {
                                $this->db->like('d.state_id', $data['state_name']);
                            }
                            if (!empty($data['district_name'])) {
                                $this->db->like('b.district_id', $data['district_name']);
                            }
                            if (!empty($data['police_station_name'])) {
                                $this->db->like('b.police_station_name', $data['police_station_name']);
                            }
            $this->db->order_by('id', 'DESC');
            $this->db->limit($limit, $offset);
            return $this->db->get()->result();  
        }

        public function edit($data = array()) {
            $array_data = array(
                'police_station_name' => $data['police_station_name'],
                'district_id' => $data['district_name'],
                // 'state_name' => $data['state_name']
            );
    
            $this->db->where('id', $data['id']);
            $this->db->update('gz_police_station_master', $array_data);
    
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        }

        public function exists($id) {
            $result = $this->db->select('*')->from('gz_police_station_master')->where('id', $id)->get();
            if ($result->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function delete($id) {
            $update_array = array(
                'deleted' => 1
            );
            $this->db->where('id', $id);
            $this->db->update('gz_police_station_master', $update_array);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        }

        public function status_change($id, $status){
            if($status == 1){
                    $statu = 0;
                } else {
                  $statu = 1;  
                }
                
                $update_array = array(
                    'status' => $statu,
                    'modified_by' => $this->session->userdata('user_id'),
                    'modified_at' => date("Y-m-d H:i:s", time())
                );
                
                $this->db->where('id', $id);
                $this->db->update('gz_police_station_master', $update_array);
                if ($this->db->affected_rows()) {
                    return TRUE;
                } else {
                    return FALSE;
                }
        }
    }
?>