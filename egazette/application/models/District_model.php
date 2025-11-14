<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
  class District_model extends CI_Model {
    
        public function getdistrictList() {
            return $this->db->select('*')
                        ->from('gz_district_master')
                        // ->join('test_ta d', 'pm.district_id = d.id')
                        ->get()->result();
        }

        public function get_district_List($limit, $offset) {
            return $this->db->select('d.id, d.district_name, d.status, s.state_name, s.id AS state_id')
                            ->from('gz_district_master d')
                            ->join('gz_states_master s', 'd.state_id = s.id')
                            ->order_by('d.id', 'DESC')
                            ->limit($limit, $offset)
                            ->get()->result();
                            
        }
        
        public function get_total_districts() {
            return $this->db->select('*')->from('gz_district_master')->count_all_results();
        }

        public function get_total_districts_search($data = array()){
            $this->db->select('*')->from('gz_district_master ds');

            if (!empty($data['state_name'])) {
                $this->db->where('ds.state_id', $data['state_name']);
            }
            if (!empty($data['district_name'])) {
                $this->db->like('ds.district_name', $data['district_name']);
            }
            
            return $this->db->count_all_results();
        }

        public function get_state_List() {
            return $this->db->select('*')->from('gz_states_master')->get()->result();
        }

        public function get_districts($state_id) {
            return $this->db->select('*')->from('gz_district_master')
                                         ->where('state_id', $state_id)
                                         ->order_by('district_name','ASC')
                                         ->get()->result();
        }


        /**
         * Search Result for Districts
         */
        public function district_search_result ($limit, $offset, $data = array()){
            $this->db->select('d.id, d.district_name, d.status, s.state_name, s.id AS state_id')
                            ->from('gz_district_master d')
                            ->join('gz_states_master s', 'd.state_id = s.id');

            if ($data['district_name'] != "") {
                $this->db->like('d.district_name', $data['district_name']);
            }

            if ($data['state_name'] != "") {
                $this->db->where('d.state_id', $data['state_name']);
            }

            $this->db->order_by('id', 'DESC');
            $this->db->limit($limit, $offset);
            return $this->db->get()->result();
            
        }


        public function add($data = array()){
            $array_data = array(
                'state_id' => $data['state_name'],
                'district_name' => $data['district_name'],
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1
            );

            return $this->db->insert('gz_district_master', $array_data);
        }

        public function get_district_details($id) {
            return $this->db->select('d.id, d.district_name,s.state_name')
                            ->from('gz_district_master d')
                            ->join('gz_states_master s','d.state_id = s.id')
                            ->where('d.id', $id)
                            ->get()->row();
        }

        public function edit($data = array()) {
            $array_data = array(
                'district_name' => $data['district_name'],
                'modified_by' => $this->session->userdata('user_id'),
                'modified_at' => date("Y-m-d H:i:s", time()),
                //'state_name' => $data['state_name']
            );
    
            $this->db->where('id', $data['id']);
            $this->db->update('gz_district_master', $array_data);
    
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        }

        public function exists($id) {
            $result = $this->db->select('*')->from('gz_district_master')->where('id', $id)->get();
            if ($result->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function delete($id) {
            $this->db->where('id', $id);
            $this->db->delete('gz_district_master');
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
                $this->db->update('gz_district_master', $update_array);
                if ($this->db->affected_rows()) {
                    return TRUE;
                } else {
                    return FALSE;
                }
        }
    }
?>
