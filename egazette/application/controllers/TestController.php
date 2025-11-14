<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TestController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('string'); // Load the string helper
    }

    public function index()
    {
        echo "test controller" . '<br>';
        echo "======================<br><br>";

        echo date("l jS \of F Y h:i:s A");
        echo '<br><br><br>';
        echo date("Y-m-d h:i:s A");
        echo '<br><br><br>';

        // $randomPass = random_string('alnum', 8);
        $password = "Nic@12345";
        echo '<br><br>====<br>' . $password . '<br>=====<br><br>';
        $hashedPassword1 = password_hash($password, PASSWORD_BCRYPT);
        // $hashedPassword1 = '$2y$10$IpT8.onPjEnbRaXcJFjKJehY99lnT15lE0QJjVFPfFZdEGvefIiDC';
        echo $hashedPassword1 . '<br>';
        // $hashedPassword = '$2y$10$cI8zz9E7G5oZy1BMWXJ1tOogMvpuXiygiOD0l5eGdvy0vVdn7nG4C';
        if (password_verify($password, $hashedPassword1)) {
            // Password is correct
            echo "<br><br>Password is correct. <br><br><br>";
        } else {
            // Password is incorrect
            echo "<br><br>Password is incorrect. <br><br><br>";
        }
        echo $hashedPassword;
    }
}