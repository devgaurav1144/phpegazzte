<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Module_wise_pricing_model extends CI_Model {
        
        /*
         * Get modules data for declaring prizes
         */
        public function get_modules_for_pricing(){
            $data = $this->db->select('module_id')
                            ->from('gz_modules_wise_pricing')
                            ->where('deleted', 0)
                            ->get()->result();

            $mod_id = array();
            if(!empty($data)){
                foreach ($data as $i){
                    $mod_id[] = $i->module_id;
                }
            } else {
                $mod_id[] = 0;
            }
            return $this->db->select('*')
                            ->from('gz_modules')
                            ->where('status', 1)
                            ->where('deleted', 0)
                            ->where_not_in('id', $mod_id)
                            ->order_by('module_name', 'ASC')
                            ->get()->result();
        }
        
        public function get_modules_for_pricing_edit(){
            return $this->db->select('*')
                            ->from('gz_modules')
                            ->where('status', 1)
                            ->where('deleted', 0)
                            ->order_by('module_name', 'ASC')
                            ->get()->result();
        }


        /*
         * Add module wise pricing 
         */
        public function add($array_data){
            $insert_array = array(
                'module_id' => $array_data['module_id'],
                'pricing' => $array_data['pricing'],
                'status' => 1,
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date("Y-m-d H:i:s", time()),
                'deleted' => 0
            );
            $this->db->insert('gz_modules_wise_pricing', $insert_array);
            return $this->db->insert_id();
        }
        
        /*
         * Edit module wise pricing
         */
        public function edit_pricing($array_data){
            try{
                $this->db->trans_begin();
                
                $insert_array = array(
                    'module_id' => $array_data['module_id'],
                    'status' => 1,
                    'deleted' => 0
                );
                $this->db->delete('gz_modules_wise_pricing', $insert_array);
                
                $this->db->delete('gz_modules_wise_pricing', array('module_id' => $array_data['mod_id']));
                
                $insert_array1 = array(
                    'module_id' => $array_data['module_id'],
                    'pricing' => $array_data['pricing'],
                    'status' => 1,
                    'created_by' => $this->session->userdata('user_id'),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'deleted' => 0
                );
                $this->db->insert('gz_modules_wise_pricing', $insert_array1);
                
                if($this->db->trans_status() == FALSE){
                    $this->db->trans_rollback();
                    return FALSE;
                } else {
                    $this->db->trans_commit();
                    return TRUE;
                }
            } catch (Exception $ex) {
                return FALSE;
            }
            
        }


        /*
         * data of module wise pricing for table(count pagination)
         */
        public function get_total_module_wise_pricings(){
            return $this->db->select('m.id, mod.module_name, m.pricing')
                            ->from('gz_modules_wise_pricing m')
                            ->join('gz_modules mod', 'mod.id = m.module_id')
                            ->where('m.deleted', 0)
                            ->count_all_results();
        }


        /*
         * data of module wise pricing for table(data)
         */
        public function get_module_wise_pricings($limit, $offset){
            return $this->db->select('m.id, mod.module_name, m.pricing, m.status')
                            ->from('gz_modules_wise_pricing m')
                            ->join('gz_modules mod', 'mod.id = m.module_id')
                            ->where('m.deleted', 0)
                            ->order_by('m.id', 'DESC')
                            ->limit($limit, $offset)
                            ->get()->result();
        }
        
        /*
         * id exists in db or not
         */
        public function exists($id) {
            $result = $this->db->select('*')->from('gz_modules_wise_pricing')->where('id', $id)->get();
            if ($result->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
        
        /*
         * Delete pricing
         */
        public function delete($id) {
            $this->db->where('id', $id);
            $this->db->delete('gz_modules_wise_pricing');
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        }
        
        /*
         * Load data on edit page
         */
        public function get_selected_data($id){
           return $this->db->select('*')
                            ->from('gz_modules_wise_pricing')
                            ->where('status', 1)
                            ->where('deleted', 0)
                            ->where('id', $id)
                            ->get()->row();
        }
        
        /*
         * Status Change
         */
        public function status_change($id, $status){
            if($status == 1){
                $stat = 0;
            } else {
              $stat = 1;  
            }
            
            $update_array = array(
                'status' => $stat,
                'modified_by' => $this->session->userdata('user_id'),
                'modified_at' => date("Y-m-d H:i:s", time())
            );
            
            $this->db->where('id', $id);
            $this->db->update('gz_modules_wise_pricing', $update_array);
            if ( $this->db->affected_rows() ) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
?>
