<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class DepRefController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library(array('session', 'form_validation', 'smtp', 'pagination', 'my_pagination', 'encryption'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'captcha', 'custom'));
        $this->load->model('DepRefModel');
    }

    public function storeDeptRefData() {
        // Retrieve data from AJAX request
        // $deptCode = $this->input->post('deptCode');
        // $depRefId = $this->input->post('dep_ref_id');
        // $amount = $this->input->post('amount');
        $description = $this->input->post('description');
        // Retrieve user ID from session
        $encryptedMsg = $this->input->post('encryptedMsg');
        $userId = $this->session->userdata('user_id');

        // Get the secret key
        $binaryFilePath = './binary_key/EGZ_binary.key';
        $handle = fopen($binaryFilePath, "rb");
        $secretKey = fread($handle, filesize($binaryFilePath));

        // decrypt the encrypted message
        $decryptedMsg = $this->decrypt($encryptedMsg, $secretKey);

        $data_array = explode('|', $decryptedMsg);
        
        // echo json_encode(array("Dept Code" => $data_array[0],"Department Ref No" => $data_array[1], "HOA" => $data_array[2], "mobile" => $data_array[27],"Amount" => $data_array[20], "Name" => $data_array[21], "filenumber"=>explode('!~!', $data_array[29])[1],"gazette_id"=>explode('!~!', $data_array[29])[0]));
        // echo ('{"msg":"'.$decryptedMsg.'"}');
        // exit;

        $fileno = explode('!~!', $data_array[29])[1];
        $gazetteid = explode('!~!', $data_array[29])[0];
        // $gazetteId = json_encode($gazetteid);
        // $fileNo = json_encode($fileno);
        // echo $fileNo;
        // echo $gazetteId;

        // echo json_encode(array("fileno" => explode('!~!', $data_array[29])[1]));

        if ($description == 'Change of Surname Gazette Publication') {
            // Store data in database using model
            $data = array(
                'hoa' => $data_array[2],
                'dept_code' => $data_array[0],
                'dep_ref_id' => $data_array[1],
                'user_id' => $userId,
                'amount' => $data_array[20],
                'mobile' => $data_array[27],
                'file_number' => $fileno,
                'gazette_id' => $gazetteid,
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s', time()),
            );

            $result = $this->DepRefModel->insertCosDeptRefData($data);

            if ($result) {
                // Send success response
                echo json_encode(array('success' => true));
            } else {
                // Send error response
                echo json_encode(array('success' => false));
            }
        }   else if ($description == 'Change of Partnership Gazette Publication') {
            // Store data in database using model
            $data = array(
                'hoa' => $data_array[2],
                'dept_code' => $data_array[0],
                'dep_ref_id' => $data_array[1],
                'user_id' => $userId,
                'amount' => $data_array[20],
                'mobile' => $data_array[27],
                'file_number' => $fileno,
                'gazette_id' => $gazetteid,
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s', time()),
            );

            $result = $this->DepRefModel->insertCopDeptRefData($data);

            if ($result) {
                // Send success response
                echo json_encode(array('success' => true));
            } else {
                // Send error response
                echo json_encode(array('success' => false));
            }
        }   else if ($description == 'Extraordinary Gazette Publication') { // under construction...
            // Store data in database using model
            $data = array(
                'dept_code' => $deptCode,
                'dep_ref_id' => $depRefId,
                'user_id' => $userId,
                'amount' => $amount,
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s', time())
            );

            $result = $this->DepRefModel->insertExtGzDeptRefData($data);

            if ($result) {
                // Send success response
                echo json_encode(array('success' => true));
            } else {
                // Send error response
                echo json_encode(array('success' => false));
            }
        }   else if ( $description == 'Change of Gender Gazette Publication' ) {
                $data = array(
                    'hoa' => $data_array[2],
                    'dept_code' => $data_array[0],
                    'dep_ref_id' => $data_array[1],
                    'user_id' => $userId,
                    'amount' => $data_array[20],
                    'mobile' => $data_array[27],
                    'file_number' => $fileno,
                    'gazette_id' => $gazetteid,
                    'description' => $description,
                    'created_at' => date('Y-m-d H:i:s', time()),
                );

                $result = $this->DepRefModel->insertCogDeptRefData($data);

                if ($result) {
                    // Send success response
                    echo json_encode(array('success' => true));
                } else {
                    // Send error response
                    echo json_encode(array('success' => false));
                }
        }

    }


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