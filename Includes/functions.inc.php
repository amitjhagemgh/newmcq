<?php
    if (!function_exists('pre_post')) {
        function pre_post() { 
            echo "<pre>"; 
            print_r($_POST); 
            echo "</pre>"; 
            die; 
        } 
    }
    if(!function_exists('get_safe_value')) {
        function get_safe_value($conn, $value) {
            if (is_array($value)) {
                // Handle array input
                return array_map(function($v) use ($conn) {
                    return mysqli_real_escape_string($conn, trim($v));
                }, $value);
            }
            // Handle string input
            return mysqli_real_escape_string($conn, trim($value));
        }
    }
    if(!function_exists('array_output')) {
        function array_output($arr) {
            echo "<pre>";
            print_r($arr);
            echo "</pre>";
        }
    }
    if(!function_exists('array_output_die')) {
        function array_output_die($arr) {
            echo "<pre>";
            print_r($arr);
            echo "</pre>";
            die;
        }
    }
?>