<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TestIFMSController extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->database();
        $this->load->helper('ifms_scroll_api');
        $this->load->model(array('Applicants_login_model'));
    }

    public function index() {
        $currentDate = date('Y-m-d'); // dynamic date
        // $currentDate = date('Y-m-d', strtotime('2024-05-08'));// static date
        // for loop start for date
        for ($i = 0; $i < 6; $i++) {
            $date = date('Y-m-d', strtotime("-$i days", strtotime($currentDate)));

            echo "<br>" . $date . "<br>------------------<br>";
            // continue;
            // exit;

            // Api endpoint URL with the given date parameter (2024-02-12)
            $apiUrl = "https://www.(StateName)treasury.gov.in/echallanservices/v0.2/depts2sscroll/EGZ/$date";

            // The below code is for Dynamic Date -- we are loading the api from <ifms_scroll_api_helper.php>
            // $apiUrl = construct_api_url($date);

            //Fetch data from static date Api
            $apiResponse = file_get_contents($apiUrl);
            // echo $apiResponse . '<br>====================<br>';

            // Parse the JSON response
            $jsonData = json_decode($apiResponse, true);

            // checking the status object data in $jsonData response
            if ($jsonData['status'] == 1) {
                // Access the data object within the response
                $dataObject = json_decode($jsonData['data'], true);
                // echo "<pre>";
                // print_r($dataObject);
                // continue;
                // exit;
                echo '<br>=======================================<br>';

                // $deptCode = $dataObject['deptCode'];

                foreach ( $dataObject['scrollData'] as $scrollItem ) {
                    // Accessing each scroll index data
                        $departmentRefId = $scrollItem['departmentRefId'];
                        $challanRefId = $scrollItem['challanRefId'];
                        $totalAmt = $scrollItem['totalAmt'];
                        $bankTransId = $scrollItem['bankTransId'];
                        $bankTransStatus = $scrollItem['bankTransStatus'];
                        $bankTransTime = $scrollItem['bankTransTime'];

                        // Accessing 'challanDtls' array
                            $challanDtls = $scrollItem['challanDtls'];
                            $challanDetailsArray = array();

                    // Loop through each item in 'challanDtls' array
                        foreach ( $challanDtls as $challan ) {
                            $hoa = $challan['hoa'];
                            $challanNo = $challan['challanNo'];
                            $challanDt = $challan['challanDt'];
                            $challanAmt = $challan['challanAmt'];

                            // We have to add challan details in an Array
                            $challanDetailsArray[] = array(
                                'hoa' => $hoa,
                                'challanNo' => $challanNo,
                                'challanDt' => $challanDt,
                                'challanAmt' => $challanAmt
                            );
                        }
                
                    // Inserting data into your database table
                        $data = array(
                            'departmentRefId' => $departmentRefId,
                            'challanRefId' => $challanRefId,
                            'totalAmt' => $totalAmt,
                            'bankTransId' => $bankTransId,
                            'bankTransStatus' => $bankTransStatus,
                            'bankTransTime' => $bankTransTime,
                            'challanDetails' => $challanDetailsArray[0] // here we include challan details in the data array
                        );

                        // echo "<pre>";
                        // print_r($data);
                        // exit;
                        // echo '<br>=============================<br>';

                        //get all data from dep_data_table
                        $query = $this->db->where('dep_ref_id', $data['departmentRefId'])->get('gz_dep_data_table');
                        // $depData = $query->result_array();
                        if ($query->num_rows() > 0) {
                            // Records found, fetch the data
                            $depData = $query->result_array();
                            // print_r($depData);
                            // exit;
                            // echo 'gz_dep_data_table for given dep_ref_id Data!!!!!!!!!!<br>';
                            // print_r($depData);
                            // Iterate through the result set
                            foreach ($depData as $row) {
                                // Check if the departmentRefId matches
                                if ($row['dep_ref_id'] === $data['departmentRefId']) {
                                    $userId = $row['user_id'];
                                    $gazetteId = $row['gazette_id'];
                                    $fileNumber = $row['file_number'];
                                    $transactionType = $row['transaction_type'];
                                    $bnk_name = 'Bill Desk';
                                    $bnk_trans_msg = 'Successful';

                                    // echo "<br>---------------<br>User ID: $userId<br> Gazette ID: $gazetteId<br> File Number: $fileNumber<br> Transaction Type: $transactionType<br>---------------<br>";
                                    // exit;
                                    // Perform different actions based on the transaction type
                                    if ($transactionType === 'COS') {
                                        $existingTransactionCOS = $this->db->get_where('gz_change_of_name_surname_payment_details', array('dept_ref_id' => $departmentRefId))->row();
                                        if (!$existingTransactionCOS || $existingTransactionCOS->trans_status !== 'S') {
                                            // function calling with dynamic data
                                            echo 'COS Transaction Updated!!';
                                            $this->change_name_surname_payment_response_update($userId, $gazetteId, $fileNumber, $departmentRefId, $totalAmt, $challanRefId, $bankTransId, $bnk_name, $bnk_trans_msg, $bankTransTime, $bankTransStatus);
                                        } else {
                                            echo 'COS Transaction already present!!';
                                            echo "<br>Dept Ref ID: $departmentRefId and Transaction Status: S are already present.<br>";
                                        }
                                    } elseif ($transactionType === 'COG') {
                                        $existingTransactionCOG = $this->db->get_where('gz_change_of_gender_payment_details', array('dept_ref_id' => $departmentRefId))->row();
                                        if (!$existingTransactionCOG || $existingTransactionCOG->trans_status !== 'S') {
                                            // function calling with dynamic data
                                            echo 'COG Transaction Updated!!';
                                            $this->change_gender_payment_response_update($userId, $gazetteId, $fileNumber, $departmentRefId, $totalAmt, $challanRefId, $bankTransId, $bnk_name, $bnk_trans_msg, $bankTransTime, $bankTransStatus);
                                        } else {
                                            echo 'COG Transaction already present!!';
                                            echo "<br>Dept Ref ID: $departmentRefId and Transaction Status: S are already present.<br>";
                                        }
                                    } elseif ($transactionType === 'COP') {
                                        $existingTransactionCOP = $this->db->get_where('gz_change_of_partnership_make_pay', array('deptRefId' => $departmentRefId))->row();
                                        if( !$existingTransactionCOP || $existingTransactionCOP->bankTransactionStatus !== 'S' ) {
                                            $this->change_partnership_details_update($userId, $gazetteId, $fileNumber, $departmentRefId, $totalAmt, $challanRefId, $bankTransId, $bnk_name, $bnk_trans_msg, $bankTransTime, $bankTransStatus);
                                        }
                                        echo 'COP Transaction already present!!';
                                        echo "<br>Dept Ref ID: $departmentRefId and Transaction Status: S are already present.<br>"; // Pending.....
                                    } else if ($transactionType == 'EXTGZ') {
                                        $existingTransactionEXTGZ = $this->db->get_where( 'gz_payment_of_cost_payment_details', array('dept_ref_id' => $departmentRefId))->row();
                                                        if (!$existingTransactionEXTGZ || $existingTransactionEXTGZ->trans_status !== 'S') {
                                                            echo 'EXTGZ Transaction Updated!!';
                                                            $this->department_payment_response($userId, $gazetteId, $departmentRefId, $totalAmt, $challanRefId, $bankTransId, $bnk_name, $bnk_trans_msg, $bankTransTime, $bankTransStatus);
                                                        }
                                                        echo 'EXTGZ Transaction<br>';
                                    }
                                    // If you found a match, you can break out of the loop
                                    break;
                                }
                            }
                        } else {
                            // No records found for the departmentRefId
                            echo "No data found for departmentRefId: {$data['departmentRefId']}<br>";
                        }
                        
                }
            } elseif ($jsonData['status'] == 0) {
                if (!empty($jsonData['msg'])) {
                    echo '<h1>Error: ' . $jsonData['msg'] . '!</h1>';
                } else {
                    echo '<h1>Error: No data found or an error occurred.</h1>';
                }
            } else {
                echo '<h1>Error: Unknown error occurred.</h1>';
            }
        
            // echo "<br><br><br>" . $jsonData;
        }
        // for loop end for date
    }


    public function change_name_surname_payment_response_update($user_id, $change_name_surname_id, $file_number, $dept_ref_no, $total_amnt, $chln_ref_no, $bnk_trans_id, $bnk_name, $bnk_trans_msg, $bnk_trans_time, $bnk_trans_stat) {
        // INSERT INTO the main Table
        $insert_array = array(
            'change_name_surname_id' => $change_name_surname_id,
            'file_number' => $file_number,
            'dept_ref_id' => $dept_ref_no,
            'challan_ref_id' => $chln_ref_no,
            'amount' => $total_amnt,
            'pay_mode' => 'U',
            'bank_trans_id' => $bnk_trans_id,
            'bank_name' => $bnk_name,
            'bank_trans_msg' => $bnk_trans_msg,
            'bank_trans_time' => $bnk_trans_time,
            'trans_status' => $bnk_trans_stat,
            'created_at' => date('Y-m-d H:i:s', time()),
            'user_id' => $user_id
        );
        // echo "<pre>";
        // print_r($insert_array);
        // exit;
        // echo '<br>-------------------------<br>';
        $result = $this->Applicants_login_model->save_change_name_surname_trans_status_scroll($insert_array);
        // echo $result;
    }

    // Gender Work
    public function change_gender_payment_response_update($user_id, $change_gender_id, $file_number, $dept_ref_no, $total_amnt, $chln_ref_no, $bnk_trans_id, $bnk_name, $bnk_trans_msg, $bnk_trans_time, $bnk_trans_stat) {
        // INSERT INTO the main Table
        $insert_array = array(
            'change_gender_id' => $change_gender_id,
            'file_number' => $file_number,
            'dept_ref_id' => $dept_ref_no,
            'challan_ref_id' => $chln_ref_no,
            'amount' => $total_amnt,
            'pay_mode' => 'U',
            'bank_trans_id' => $bnk_trans_id,
            'bank_name' => $bnk_name,
            'bank_trans_msg' => $bnk_trans_msg,
            'bank_trans_time' => $bnk_trans_time,
            'trans_status' => $bnk_trans_stat,
            'created_at' => date('Y-m-d H:i:s', time()),
            'user_id' => $user_id
        );
        // echo "<pre>";
        // print_r($insert_array);
        // exit;
        // echo '<br>-------------------------<br>';
        $result = $this->Applicants_login_model->save_change_gender_trans_status_scroll($insert_array);
        // echo $result;
    }
    public function change_partnership_details_update($user_id, $par_id, $file_number, $dept_ref_no, $total_amnt, $chln_ref_no, $bnk_trans_id, $bnk_name, $bnk_trans_msg, $bnk_trans_time, $bnk_trans_stat) { 
        // echo 'test';exit;
         // INSERT INTO the main Table
        $insert_array = array(
            'par_id' => $par_id,
            'file_number' => $file_number,
            'dept_ref_id' => $dept_ref_no,
            'challan_ref_id' => $chln_ref_no,
            'amount' => $total_amnt,
            'pay_mode' => 'U',
            'bank_trans_id' => $bnk_trans_id,
            'bank_name' => $bnk_name,
            'bank_trans_msg' => $bnk_trans_msg,
            'bank_trans_time' => $bnk_trans_time,
            'trans_status' => $bnk_trans_stat,
            'created_at' => date('Y-m-d H:i:s', time()),
            'user_id' => $user_id
        );
        // echo "<pre>";
        // print_r($insert_array);exit;

        $result = $this->Applicants_login_model->change_partnership_details_scroll($insert_array);
        // echo "<pre>";
        // print_r($result);
        // exit;
            
        
    }

    public function department_payment_response($user_id, $gazette_id, $dept_ref_no, $total_amnt, $chln_ref_no, $bnk_trans_id, $bnk_name, $bnk_trans_msg, $bnk_trans_time, $bnk_trans_stat) {
        $insert_array = array(
            'gazette_id' => $gazette_id,
            'dept_ref_id' => $dept_ref_no,
            'challan_ref_id' => $chln_ref_no,
            'amount' => $total_amnt,
            'pay_mode' => 'U',
            'bank_trans_id' => $bnk_trans_id,
            'bank_name' => $bnk_name,
            'bank_trans_msg' => $bnk_trans_msg,
            'bank_trans_time' => $bnk_trans_time,
            'trans_status' => $bnk_trans_stat,
            'created_at' => date('Y-m-d H:i:s', time()),
            'user_id' => $user_id
        );

        $result = $this->gazette_model->save_payment_response_scroll($insert_array);
    }
}