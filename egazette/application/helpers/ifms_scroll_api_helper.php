<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function construct_api_url($date) {
    return "https://www.(StateName)treasury.gov.in/echallanservices/v0.2/depts2sscroll/EGZ/$date";
}