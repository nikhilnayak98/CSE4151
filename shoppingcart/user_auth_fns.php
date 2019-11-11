<?php
require_once('db_fns.php');

function login($username, $password) {
    // check username and password with db
    // if yes, return true
    // else return false
    $conn = db_connect();
    if(!$conn) {
        return 0;
    }
    // check if username is unique
    $result = $conn->query("select * from admin WHERE 
username = '".$username."' and password = sha1('".$password."')");
    if(!$result) {
        return 0;
    }

    if($result->num_rows > 0) {
        return 1;
    } else {
        return 0;
    }

}

function check_admin_user() {
    // see if somebody is logged in and notify them if not
    if(isset($_SESSION['admin_user'])) {
        return true;
    } else {
        return false;
    }
}

function change_password($username, $old_password, $new_password) {
    if(login($username, $old_password)) {
        if(!($conn = db_connect())) {
            return false;
        }

        $result = $conn->query("update
        admin set password = sha1('".$new_password."') where username = '".$username."'");

        if(!$result) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

