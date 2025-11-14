<?php

/*
 * Custom Helper Class
 */

/*
 * convert time to formatted time like 20-06-2035 12:23 AM/PM.
 * @param datetime
 * @return time
 */

function get_formatted_datetime($date = null) {
    return strftime('%d-%m-%Y %I:%M %p', strtotime($date));
}

/*
 * convert time to formatted time like 05 Nov 2019, 12:23 AM/PM.
 * @param datetime
 * @return time
 */
function get_nice_datetime($date = null) {
    return strftime('%d %b %Y, %I:%M %p', strtotime($date));
}


/*
 * Function to convert bytes to number format
 * @param int bytes
 */

function format_bytes($a_bytes) {
    if ($a_bytes < 1024) {
        return $a_bytes . ' B';
    } elseif ($a_bytes < 1048576) {
        return round($a_bytes / 1024, 2) . ' KB';
    } elseif ($a_bytes < 1073741824) {
        return round($a_bytes / 1048576, 2) . ' MB';
    } elseif ($a_bytes < 1099511627776) {
        return round($a_bytes / 1073741824, 2) . ' GB';
    } elseif ($a_bytes < 1125899906842624) {
        return round($a_bytes / 1099511627776, 2) . ' TB';
    } elseif ($a_bytes < 1152921504606846976) {
        return round($a_bytes / 1125899906842624, 2) . ' PB';
    } elseif ($a_bytes < 1180591620717411303424) {
        return round($a_bytes / 1152921504606846976, 2) . ' EB';
    } elseif ($a_bytes < 1208925819614629174706176) {
        return round($a_bytes / 1180591620717411303424, 2) . ' ZB';
    } else {
        return round($a_bytes / 1208925819614629174706176, 2) . ' YB';
    }
}

/*
 * Convert numbers count to K
 */

function number_to_ks($number) {
    if ($number >= 1000) {
        return number_format(($number / 1000), 1) . ' K';
    } else {
        return $number;
    }
}

// This function will return a random string of specified length 
function random_strings($length_of_string) {
    // String of all alphanumeric character
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    // Shufle the $str_result and returns substring of specified length
    return substr(str_shuffle($str_result), 0, $length_of_string);
}

/*
 * Function to store the audit log for the application
 */

function audit_action_log($user_id, $module, $action, $time_stamp, $ip_address) {
    $CI = &get_instance();

    $CI->load->database();

    $ins_arr = array(
        'user_id' => $user_id,
        'module' => $module,
        'action' => $action,
        'created_by' => $user_id,
        'created_at' => $time_stamp,
        'ip_address' => $ip_address
    );

    $CI->db->insert('gz_activity_log', $ins_arr);
}

?>