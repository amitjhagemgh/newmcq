<?php
    function pre_post() {
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        die;
    }
    function get_safe_value($conn, $str) {
        return mysqli_real_escape_string($conn, str_replace("'", "&apos;", str_replace('"', '&quot;', trim($str))));
    }
    function array_output($arr) {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }
    function array_output_die($arr) {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
        die;
    }
?>