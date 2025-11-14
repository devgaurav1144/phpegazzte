<?php

    class CspMiddleware {

        protected $CI;

        public function __construct() {
            $this->CI =& get_instance();
        }

        // public function setHeader() {
        //     $this->CI->output->set_header("Content-Security-Policy: script-src 'self';");
        // }

        public function setHeader($values = array()) {
            $header = "Content-Security-Policy: ";
            $header .= implode(' ', $values);
            $this->CI->output->set_header($header);
        }
    }
