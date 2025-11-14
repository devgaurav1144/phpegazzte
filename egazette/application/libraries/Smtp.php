<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * SMTP
 */

class Smtp {

    private $protocol, $smtp_host, $smtp_user, $smtp_pass, $smtp_port;

    public function __construct() {
        $CI = & get_instance();
        $CI->load->database();
        $CI->load->library('email');
    }

    public function initialize_data($to_address, $email_content) {

        $query = $this->db->select('*')
                ->from('gz_settings')
                ->where('setting_key', 'smtp')
                ->get();

        if ($query->num_rows() > 0) {

            $results = $this->db->select('*')
                            ->from('gz_settings')
                            ->where('setting_key', 'smtp')
                            ->get()->result();

            foreach ($results as $result) {
                $this->protocol = $result->action_key['protocol'];
                $this->smtp_host = $result->action_key['host'];
                $this->smtp_user = $result->action_key['username'];
                $this->smtp_pass = $result->action_key['password'];
                $this->smtp_port = $result->action_key['port'];
            }
        }

        $config['useragent'] = 'Gazette';
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = $this->protocol . '://' . $this->smtp_host;
        $config['smtp_user'] = $this->smtp_user;
        $config['smtp_pass'] = $this->smtp_pass;
        $config['smtp_port'] = $this->smtp_port;

        $config['smtp_timeout'] = 5;
        $config['wordwrap'] = TRUE;
        $config['wrapchars'] = 76;
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['validate'] = FALSE;
        $config['proiority'] = 3;
        $config['crlf'] = '\r\n';
        $config['newline'] = '\r\n';
        $config['bcc_batch_mode'] = FALSE;
        $config['bcc_batch_size'] = 200;

        $this->email->initialize($config);

        $this->email->from($this->smtp_user, '(StateName) Press E-Gazette System');
        $this->email->to($to_address);
        $this->email->subject('Forgot Password Request for (StateName) Press E-Gazette System');
        $this->email->message($email_content);
        $this->email->set_newline("\r\n");
        $this->email->send();
    }

}

?>