<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
  class Block_model extends CI_Model {
    
        public function getblockList() {
            return $this->db->select('*')
                        ->from('gz_block_master')
                        // ->join('test_ta d', 'pm.district_id = d.id')
                        ->get()->result();
        }

        public function get_block_List($limit, $offset) {
            return $this->db->select('b.id, b.block_name, b.status, d.district_name, s.state_name')
                            ->from('gz_block_master b')
                            ->join('gz_district_master d', 'b.district_unique_id  = d.id')
                            ->join('gz_states_master s', 'd.state_id = s.id')
                            ->order_by('b.id', 'DESC')
                            ->limit($limit, $offset)
                            ->get()->result();
                            
        }
        
        public function get_total_blocks() {
            return $this->db->select('b.id, b.block_name, b.status, d.district_name, s.state_name')
                            ->from('gz_block_master b')
                            ->join('gz_district_master d', 'b.district_unique_id  = d.id')
                            ->join('gz_states_master s', 'd.state_id = s.id')
                            ->count_all_results();
        }

        public function get_state_List() {
            return $this->db->select('*')->from('gz_states_master')->get()->result();
        }

        public function get_district_List($state_id) {
            return $this->db->select('*')->from('gz_district_master')
                                        ->where('state_id', $state_id)
                                        ->order_by('district_name', 'ASC')
                                        ->get()->result();
        }

        public function get_total_search_blocks($data = array()){
                $this->db->select('b.id, b.block_name, b.status, d.district_name, s.state_name')
                            ->from('gz_block_master b')
                            ->join('gz_district_master d', 'b.district_unique_id  = d.id')
                            ->join('gz_states_master s', 'd.state_id = s.id');
                            
                    if (!empty($data['state_name'])) {
                    $this->db->where('d.state_id', $data['state_name']);
                    }
                    if (!empty($data['district_name'])) {
                        $this->db->where('b.district_unique_id', $data['district_name']);
                    }
                    if (!empty($data['block_name'])) {
                        $this->db->like('b.block_name', $data['block_name']);
                    }
                    
                    $this->db->order_by('id', 'DESC');
                    return $this->db->count_all_results();
        }

        public function block_search_result($data = array(),$limit, $offset){

            $this->db->select('b.id, b.block_name, b.status, d.district_name, s.state_name')
                            ->from('gz_block_master b')
                            ->join('gz_district_master d', 'b.district_unique_id  = d.id')
                            ->join('gz_states_master s', 'd.state_id = s.id')
                            ->order_by('b.id', 'DESC');
            
                            if (!empty($data['state_name'])) {
                                $this->db->like('d.state_id', $data['state_name']);
                                }
                                if (!empty($data['district_name'])) {
                                $this->db->like('b.district_unique_id', $data['district_name']);
                                }
                                if (!empty($data['block_name'])) {
                                $this->db->like('b.block_name', $data['block_name']);
                                }
            $this->db->order_by('id', 'DESC');
            $this->db->limit($limit, $offset);
            return $this->db->get()->result(); 
            //print_r($this->db->last_query()); exit;  
        }

        public function add($data = array()){
            $array_data = array(
                'block_name' => $data['block_name'],
                'district_unique_id' => $data['district_name'],
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'status' => 1
            );

            return $this->db->insert('gz_block_master', $array_data);
        }

        public function get_block_details($id) {
            return $this->db->select('b.id, b.block_name, b.status, b.district_unique_id, d.district_name, s.state_name, s.id as state_id')
                            ->from('gz_block_master b')
                            ->join('gz_district_master d', 'b.district_unique_id = d.id')
                            ->join('gz_states_master s', 'd.state_id = s.id')
                            ->where('b.id', $id)
                            ->get()->row();
        }

        public function edit($data = array()) {
            $array_data = array(
                'block_name' => $data['block_name'],
                'district_unique_id' => $data['district_name'],
                'modified_by' => $this->session->userdata('user_id'),
                'modified_at' => date("Y-m-d H:i:s", time())
                // 'state_name' => $data['state_name']
            );
    
            $this->db->where('id', $data['id']);
            $this->db->update('gz_block_master', $array_data);
    
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        }

        public function exists($id) {
            $result = $this->db->select('*')->from('gz_block_master')->where('id', $id)->get();
            if ($result->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function delete($id) {
            $this->db->where('id', $id);
            $this->db->delete('gz_block_master');
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
                $this->db->update('gz_block_master', $update_array);
                if ($this->db->affected_rows()) {
                    return TRUE;
                } else {
                    return FALSE;
                }
        }
    }
?>
