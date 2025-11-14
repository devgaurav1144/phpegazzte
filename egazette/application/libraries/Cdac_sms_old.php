<?php

class Cdac_sms {

    public $username;
    public $password;
    public $senderID;
    public $message;
    public $numbers;
    public $Api_key;
    public $messages;
    public $hashValue;
    public $templateID;
    protected $sms_URL = "http://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT";

    public function __construct() {
        $this->username = 'opscsms2012-EGazette';
        $this->password = '(StateName)@2020';
        $this->Api_key = '59573da3-edcc-4b83-abfd-f70f4646f9f8';
        $this->senderID = 'ODIGOV';
        $this->hashValue = sha1(trim($this->password));
    }

    //function to send sms using by making http connection
    private function post_to_url($url, $data) {
        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= $key . '=' . urlencode($value) . '&';
        }
        rtrim($fields, '&');
        $post = curl_init();
        //curl_setopt($post, CURLOPT_SSLVERSION, 5); // uncomment for systems supporting TLSv1.1 only
        curl_setopt($post, CURLOPT_SSLVERSION, 6); // use for systems supporting TLSv1.2 or comment the line
        curl_setopt($post, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, count($data));
        curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($post); //result from mobile seva server
        curl_close($post);
		//echo $result; exit;
    }

    //function to send unicode sms by making http connection
    private function post_to_url_unicode($url, $data) {
        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= $key . '=' . urlencode($value) . '&';
        }
        rtrim($fields, '&');

        $post = curl_init();
        //curl_setopt($post, CURLOPT_SSLVERSION, 5); // uncomment for systems supporting TLSv1.1 only
        curl_setopt($post, CURLOPT_SSLVERSION, 6); // use for systems supporting TLSv1.2 or comment the line
        curl_setopt($post, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, count($data));
        curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($post, CURLOPT_HTTPHEADER, array("Content-Type:application/x-www-form-urlencoded"));
        curl_setopt($post, CURLOPT_HTTPHEADER, array("Content-length:"
            . strlen($fields)));
        curl_setopt($post, CURLOPT_HTTPHEADER, array("User-Agent:Mozilla/4.0 (compatible; MSIE 5.0; Windows 98; DigExt)"));
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($post); //result from mobile seva server
        curl_close($post);
    }

    //function to convert unicode text in UTF-8 format
    private function string_to_finalmessage($message) {
        $finalmessage = "";
        $sss = "";
        for ($i = 0; $i < mb_strlen($message, "UTF-8"); $i++) {
            $sss = mb_substr($message, $i, 1, "utf-8");
            $a = 0;
            $abc = "&#" . $this->ordutf8($sss, $a) . ";";
            $finalmessage .= $abc;
        }
        return $finalmessage;
    }

    //function to convet utf8 to html entity
    private function ordutf8($string, &$offset) {
        $code = ord(substr($string, $offset, 1));
        if ($code >= 128) { //otherwise 0xxxxxxx
            if ($code < 224) {
                $bytesnumber = 2; //110xxxxx
            } else if ($code < 240) {
                $bytesnumber = 3; //1110xxxx
            } else if ($code < 248) {
                $bytesnumber = 4; //11110xxx
            }
            $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) -
                    ($bytesnumber > 3 ? 16 : 0);
            for ($i = 2; $i <= $bytesnumber; $i++) {
                $offset ++;
                $code2 = ord(substr($string, $offset, 1)) - 128; //10xxxxxx
                $codetemp = $codetemp * 64 + $code2;
            }
            $code = $codetemp;
        }
        return $code;
    }

    //Function to send single sms
    public function sendSingleSMS($message, $mobileno) {
        $key = hash('sha512', trim($this->username) . trim($this->senderID) . trim($message) . trim($this->Api_key));

        $data = array(
            "username" => trim($this->username),
            "password" => trim($this->hashValue),
            "senderid" => trim($this->senderID),
            "content" => trim($message),
            "smsservicetype" => "singlemsg",
            "mobileno" => trim($mobileno),
            "key" => trim($key)
        );

        //calling post_to_url to send sms
        $this->post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data);
    }

    //Function to send otp sms
    public function sendOtpSMS($message, $mobileno,$templateID) {
        $key = hash('sha512', trim($this->username) . trim($this->senderID) . trim($message) . trim($this->Api_key));

        $data = array(
            "username" => trim($this->username),
            "password" => trim($this->hashValue),
            "senderid" => trim($this->senderID),
            "content" => trim($message),
            "smsservicetype" => "otpmsg",
            "mobileno" => trim($mobileno),
            "key" => trim($key),
            "templateid" => $templateID
        );

        //echo'<pre>';print_r($data);exit();
        //calling post_to_url to send otp sms
        $this->post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data);
    }

    //function to send bulk sms
    public function sendBulkSMS($message, $mobileNos) {
        $key = hash('sha512', trim($this->username) . trim($this->senderID) . trim($message) . trim($this->Api_key));

        $data = array(
            "username" => trim($this->username),
            "password" => trim($this->hashValue),
            "senderid" => trim($this->senderID),
            "content" => trim($message),
            "smsservicetype" => "bulkmsg",
            "bulkmobno" => trim($mobileNos),
            "key" => trim($key)
        );

        //calling post_to_url to send bulk sms
        $this->post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data);
    }

    //function to send single unicode sms
    public function sendSingleUnicode($messageUnicode, $mobileno) {
        $finalmessage = $this->string_to_finalmessage(trim($messageUnicode));
        $key = hash('sha512', trim($this->username) . trim($this->senderID) . trim($finalmessage) . trim($this->Api_key));

        $data = array(
            "username" => trim($this->username),
            "password" => trim($this->hashValue),
            "senderid" => trim($this->senderID),
            "content" => trim($finalmessage),
            "smsservicetype" => "unicodemsg",
            "mobileno" => trim($mobileno),
            "key" => trim($key)
        );

        //calling post_to_url_unicode to send single unicode sms
        $this->post_to_url_unicode("https://msdgweb.mgov.gov.in/esms/sendsmsrequest", $data);
    }

    //function to send bulk unicode sms
    public function sendBulkUnicode($messageUnicode, $mobileNos) {
        $finalmessage = $this->string_to_finalmessage(trim($messageUnicode));
        $key = hash('sha512', trim($this->username) . trim($this->senderID) . trim($finalmessage) . trim($this->Api_key));

        $data = array(
            "username" => trim($this->username),
            "password" => trim($this->hashValue),
            "senderid" => trim($this->senderID),
            "content" => trim($finalmessage),
            "smsservicetype" => "unicodemsg",
            "bulkmobno" => trim($mobileNos),
            "key" => trim($key)
        );

        //calling post_to_url_unicode to send bulk unicode sms
        $this->post_to_url_unicode("https://msdgweb.mgov.gov.in/esms/sendsmsrequest", $data);
    }

    //function to send single unicode otp sms
    public function sendUnicodeOtpSMS($messageUnicode, $mobileno) {
        $finalmessage = $this->string_to_finalmessage(trim($messageUnicode));
        $key = hash('sha512', trim($this->username) . trim($this->senderID) . trim($finalmessage) . trim($this->Api_key));

        $data = array(
            "username" => trim($this->username),
            "password" => trim($this->hashValue),
            "senderid" => trim($this->senderID),
            "content" => trim($finalmessage),
            "smsservicetype" => "unicodeotpmsg",
            "mobileno" => trim($mobileno),
            "key" => trim($key)
        );

        //calling post_to_url_unicode to send single unicode sms
        $this->post_to_url_unicode("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data);
    }

}

?>