<?php

class Cdac_sms {
    
    public $sms_URL;

    public function __construct() {
        $this->sms_URL = "https://govtsms.(StateName).gov.in/api/api.php";
    }

    //Function to send otp sms
    public function sendOtpSMS($message, $mobileno, $templateID) {
	
		$post_fields = array(
			'action' => 'singleSMS',
            'source' => 'ODIGOV',
			'department_id' => 'D042001',
			'template_id' => $templateID,
			'sms_content' => $message,
			'phonenumber' => $mobileno
		);

		$ch = curl_init($this->sms_URL);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 2);
		$data = curl_exec($ch);
		
		if($data === false) {
			echo 'Curl error: ' . curl_error($ch);
		}
		
		curl_close($ch);
    }

}

?>
