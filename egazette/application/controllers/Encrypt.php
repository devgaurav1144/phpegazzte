<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Encrypt extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $serial_number = $this->input->post('sr');
        $sr = $this->crypto('encrypt', $serial_number);
        echo $sr;
    }

    public function crypto($action, $string) {
        $key = substr('!5663a#KN', 0, 8);
        $ivArray = array(38, 55, 206, 48, 28, 64, 20, 16);
        $iv = null;
        $result = null;
        foreach ($ivArray as $element) {
            $iv.=CHR($element);
        }

        if ($action == 'encrypt') {
            $method = 'AES-128-CBC';
            $i_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
            $enc = $string;
            //$block = mcrypt_get_block_size('des', 'cbc');
            /*
              $block = strlen(openssl_encrypt('', $method, '', OPENSSL_RAW_DATA, $iv));
              $pad = $block - (strlen($enc) % $block);
              $enc .= str_repeat(chr($pad), $pad);
             */
            //$encrypted_string = mcrypt_encrypt(MCRYPT_DES, $key, $enc, MCRYPT_MODE_CBC, $iv);
            $encrypted_string = openssl_encrypt($enc, $method, $key, 0, $i_iv);
            $result = base64_encode($encrypted_string);
        } elseif ($action == 'decrypt') {
            $decod = base64_decode($string);
            $dec = mcrypt_decrypt(MCRYPT_DES, $key, $decod, MCRYPT_MODE_CBC, $iv);
            $result = trim($dec, "\0..\32");
        }

        return $result;
    }

}

?>