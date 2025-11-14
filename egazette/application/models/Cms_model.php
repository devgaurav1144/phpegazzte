<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php

class Cms_model extends CI_Model {

  public function set_about_us($data = array()){
    $query = $this->db->select('*')->from('gz_cms_content')->where('cms_type', 'about_us')->get();
        
        if ($query->num_rows() > 0) {
            // UPDATE            
            $this->db->where('cms_type', 'about_us');
            $this->db->update('gz_cms_content', array('cms_desc' => $data['cms_desc']));

            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }            
        } else {

            $this->db->insert('gz_cms_content', $data);
            return true;
        }
  }
  
  public function get_about_us()
  {
    return $this->db->select('*')->from('gz_cms_content')->where('cms_type', 'about_us')->get()->result_array();
  }  
/*-----------About Gazette----------------*/
  public function set_gazette($data = array()){
    $query = $this->db->select('*')->from('gz_cms_content')->where('cms_type', 'gazette')->get();
        
        if ($query->num_rows() > 0) {
            // UPDATE            
            $this->db->where('cms_type', 'gazette');
            $this->db->update('gz_cms_content', array('cms_desc' => $data['cms_desc']));

            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }            
        } else {

            $this->db->insert('gz_cms_content', $data);
            return true;
        }
  }
  
  public function get_gazette()
  {
    return $this->db->select('*')->from('gz_cms_content')->where('cms_type', 'gazette')->get()->result_array();
  }  
  /*-----------./.About Gazette----------------*/

  /*-----------Disclaimer----------------*/
  public function set_disclaimer($data = array()){
    $query = $this->db->select('*')->from('gz_cms_content')->where('cms_type', 'disclaimer')->get();
        
        if ($query->num_rows() > 0) {
            // UPDATE            
            $this->db->where('cms_type', 'disclaimer');
            $this->db->update('gz_cms_content', array('cms_desc' => $data['cms_desc']));

            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }            
        } else {

            $this->db->insert('gz_cms_content', $data);
            return true;
        }
  }
  
  public function get_disclaimer()
  {
    return $this->db->select('*')->from('gz_cms_content')->where('cms_type', 'disclaimer')->get()->result_array();
  }  
  /*-----------./.Disclaimer----------------*/

  /*-----------Acknowledgement----------------*/
  public function set_acknowledgement($data = array()){
    $query = $this->db->select('*')->from('gz_cms_content')->where('cms_type', 'acknowledgement')->get();
        
        if ($query->num_rows() > 0) {
            // UPDATE            
            $this->db->where('cms_type', 'acknowledgement');
            $this->db->update('gz_cms_content', array('cms_desc' => $data['cms_desc']));

            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }            
        } else {

            $this->db->insert('gz_cms_content', $data);
            return true;
        }
  }
  
  public function get_acknowledgement()
  {
    return $this->db->select('*')->from('gz_cms_content')->where('cms_type', 'acknowledgement')->get()->result_array();
  }  
  /*-----------./. Acknowledgement----------------*/

}
