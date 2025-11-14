<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ifms_Scroll extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        //$this->load->helper(array('url',));
        //$this->load->model(array('ifms_scroll_model'));
    }

    /*
     * web service provided to IFMS for data updation when transaction status == S
     */

    public function index() {
        
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            //var_dump($_GET);
            if (!empty($this->input->post('msg'))) {
                //DEPTCODE|DEPTREFNO|TOTAMT|CHLNREFNO|BKTRNID|BKTRNSTS|BKTRNMSG|BKTRNTIME|CHECKSUM 
                //in Encrypt Format
                $encrypted_msg = trim($this->input->post('msg'));

                // Binary File
                $binary_file_path = './binary_key/EGZ_binary_UAT.key';
                $handle = fopen($binary_file_path, "rb");
                $secret_key = fread($handle, filesize($binary_file_path));

                // Decrypted Message
                $decrypted_msg = $this->decrypt($encrypted_msg, $secret_key);

                // explode the data string separated by |
                $data_array = explode("|", $decrypted_msg);

                $dept_code = $data_array[0];
                $dept_ref_no = $data_array[1];
                $tot_amnt = $data_array[2];
                $chln_ref_no = $data_array[3];
                $bnk_trns_id = $data_array[4];
                $bnk_trns_stat = $data_array[5];
                $bnk_trns_time = $data_array[6];

                // Check deptRefNo, deptCode, totAmnt, chnlRefNo, bnkTrnsId matches with the
                // DB both in COS and COP Payment details table

                $cos_res = $this->db->select('*')->from('gz_change_of_name_surname_payment_details')
                        ->where('dept_ref_id', $dept_ref_no)
                        ->where('challan_ref_id', $chln_ref_no)
                        ->where('amount', $tot_amnt)
                        ->where('bank_trans_id', $bnk_trns_id)
                        ->get();

                if ($cos_res->num_rows() > 0) {
                    $cos_row = $cos_res->row();

                    try {

                        $this->db->trans_begin();

                        // UPDATE gz_change_of_name_surname_payment_details Table
                        $gz_pmnt_upd = array(
                            'challan_ref_id' => $chln_ref_no,
                            'bank_trans_id' => $bnk_trns_id,
                            'trans_status' => $bnk_trns_stat,
                            'bank_trans_time' => $bnk_trns_time,
                            'modified_at' => date('Y-m-d H:i:s', time())
                        );

                        $this->db->where('challan_ref_id', $chln_ref_no);
                        $this->db->where('dept_ref_id', $dept_ref_no);
                        $this->db->where('bank_trans_id', $bnk_trns_id);
                        $this->db->update('gz_change_of_name_surname_payment_details', $gz_pmnt_upd);

                        // INSERT the payment status history (gz_payment_status_history) table
                        $ins_arr = array(
                            'payment_id' => $cos_row->id,
                            'payment_type' => 'COS',
                            'payment_status' => $bnk_trns_stat,
                            'created_at' => date('Y-m-d H:i:s', time())
                        );

                        $this->db->insert('gz_payment_status_history', $ins_arr);

                        if ($this->db->trans_status() == FALSE) {
                            $this->db->trans_rollback();
                            //$this->output->set_header('HTTP/1.1 200 OK');
                            echo "data not updated";
                        } else {
                            $this->db->trans_commit();
                            $this->output->set_header('HTTP/1.1 200 OK');
                            echo "data updated";
                        }
                    } catch (Exception $ex) {
                        echo "data not updated";
                    }
                } else {
                    $cop_res = $this->db->select('*')->from('gz_change_of_partnership_make_pay')
                            ->where('deptRefId', $dept_ref_no)
                            ->where('challanRefId', $chln_ref_no)
                            ->where('amount', $tot_amnt)
                            ->where('bank_trans_id', $bnk_trns_id)
                            ->get();

                    if ($cop_res->num_rows() > 0) {

                        $cop_row = $cop_res->row();

                        try {

                            $this->db->trans_begin();

                            // UPDATE gz_change_of_name_surname_payment_details Table
                            $gz_pmnt_upd = array(
                                'challanRefId' => $chln_ref_no,
                                'bank_trans_id' => $bnk_trns_id,
                                'bankTransactionStatus' => $bnk_trns_stat,
                                'bank_trans_time' => $bnk_trns_time,
                                'modified_at' => date('Y-m-d H:i:s', time())
                            );

                            $this->db->where('challanRefId', $chln_ref_no);
                            $this->db->where('deptRefId', $dept_ref_no);
                            $this->db->where('bank_trans_id', $bnk_trns_id);
                            $this->db->update('gz_change_of_partnership_make_pay', $gz_pmnt_upd);

                            // INSERT the payment status history (gz_payment_status_history) table
                            $ins_arr = array(
                                'payment_id' => $cop_row->id,
                                'payment_type' => 'COP',
                                'payment_status' => $bnk_trns_stat,
                                'created_at' => date('Y-m-d H:i:s', time())
                            );

                            $this->db->insert('gz_payment_status_history', $ins_arr);

                            if ($this->db->trans_status() == FALSE) {
                                $this->db->trans_rollback();
                                $this->output->set_header('HTTP/1.1 200 OK');
                                echo "data not updated";
                            } else {
                                $this->db->trans_commit();
                                $this->output->set_header('HTTP/1.1 200 OK');
                                echo "data updated";
                            }
                        } catch (Exception $ex) {
                            echo "data not updated";
                        }
                    }
                }
                // Set Output Header value to 200
                $this->output->set_header('HTTP/1.1 200 OK');
            } else {
                echo "data must be set and in POST";
            }
        } else {
            echo "Request is not allowed and must be POST";
        }
    }

    /*
     * Decrypt function to be used for IFMS Integration
     */

    private function decrypt($data = '', $key = NULL) {
        if ($key != NULL && $data != "") {
            $method = "AES-256-ECB";
            $dataDecoded = base64_decode($data);
            $decrypted = openssl_decrypt($dataDecoded, $method, $key, OPENSSL_RAW_DATA);
            return $decrypted;
        } else {
            return "Encrypted String to decrypt, Key is required.";
        }
    }
    
}
