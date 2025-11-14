<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/*
 * Email configuration
 */

$config['useragent'] = 'Gazette';
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'relay.emailgov.in';
$config['smtp_user'] = 'email id';// enter your email id
$config['smtp_pass'] = ''; // enter smtp password
$config['smtp_port'] = 465;
$config['smtp_crypto'] = 'ssl';

$config['smtp_timeout'] = 5;
$config['wordwrap'] = TRUE;
$config['wrapchars'] = 76;
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['validate'] = FALSE;
$config['priority'] = 3;
$config['crlf'] = '\r\n';
$config['newline'] = '\r\n';
$config['bcc_batch_mode'] = FALSE;
$config['bcc_batch_size'] = 200;
$config['smtp_crypto'] = 'null';