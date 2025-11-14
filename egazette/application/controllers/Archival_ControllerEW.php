
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/vendor/autoload.php';

class Archival_ControllerEW extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('session', 'form_validation', 'email', 'pagination', 'my_pagination'));
        $this->load->helper(array('url', 'form', 'string', 'text', 'custom'));
        $this->load->model(array('Archival_model', 'Applicants_login_model'));
        
    }

    public function download_gazette($file_id) {
        
        $file_data = $this->Archival_model->get_gazette_file($file_id);
        // print_r($file_data);exit;

        if ($file_data) {
            // Get the full path to the file
            $file_path = $file_data->gazette_file;
            $filename = basename($file_path); // Extract the filename from the path
    
            // Check if the file exists
            if (file_exists($file_path)) {
                // Set headers to force download
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file_path));
    
                // Read the file and output it to the browser
                readfile($file_path);
                exit;
            } else {
                // If the file is not found, show an error message
                echo 'File not found.';
            }
        } else {
            // If the file data is not found, show an error message
            show_404();
        }
    }

    public function view_gazette($file_id) {
        $file_data = $this->Archival_model->get_gazette_file($file_id);
    
        if ($file_data) {
            $file_path = $file_data->gazette_file;
            $filename = basename($file_path);
    
            if (file_exists($file_path)) {
                // Set headers to display the PDF in a new tab
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="' . $filename . '"');
                header('Content-Length: ' . filesize($file_path));
                header('Accept-Ranges: bytes');
    
                // Read and output the file
                readfile($file_path);
                exit;
            } else {
                show_error('File not found.', 404);
            }
        } else {
            show_404();
        }
    }

    // public function view_docs($file_id) {
    //     $file_data = $this->Applicants_login_model->get_image_link_cons($file_id);
    
    //     if ($file_data) {
    //         $file_path = $file_data->gazette_file;
    //         $filename = basename($file_path);
    
    //         if (file_exists($file_path)) {
    //             // Set headers to display the PDF in a new tab
    //             header('Content-Type: application/pdf');
    //             header('Content-Disposition: inline; filename="' . $filename . '"');
    //             header('Content-Length: ' . filesize($file_path));
    //             header('Accept-Ranges: bytes');
    
    //             // Read and output the file
    //             readfile($file_path);
    //             exit;
    //         } else {
    //             show_error('File not found.', 404);
    //         }
    //     } else {
    //         show_404();
    //     }
    // }

    // public function view_docs($file_id) {
    //     $file_data = $this->Applicants_login_model->get_image_link_cons($file_id);
    
    //     if ($file_data) {
    //         $file_path = $file_data->gazette_file;
    //         $filename = basename($file_path);
    
    //         // Debugging: Check what path is being retrieved
    //         if (empty($file_path)) {
    //             show_error("Error: File path is empty.", 500);
    //         }
    
    //         if (!file_exists($file_path)) {
    //             show_error("Error: File not found at path: " . $file_path, 404);
    //         }
    
    //         // Set headers to display the PDF in a new tab
    //         header('Content-Type: application/pdf');
    //         header('Content-Disposition: inline; filename="' . $filename . '"');
    //         header('Content-Length: ' . filesize($file_path));
    //         header('Accept-Ranges: bytes');
    
    //         // Read and output the file
    //         readfile($file_path);
    //         exit;
    //     } else {
    //         show_404();
    //     }
    // }

    public function view_docs($file_id) {
        $file_data = $this->Applicants_login_model->get_image_link_cons($file_id);
    
        if (!$file_data) {
            show_error("Error: No record found for file_id: " . $file_id, 404);
        }
    
        echo "<pre>";
        print_r($file_data);
        echo "</pre>";
        exit;
    }
    
    
    
}

?>