<?php
    include_once("../backend/terminal_scripts.php");
    include_once("../backend/session.php");

    $a = serial_read();
    $b = json_decode($a, true);
    $_SESSION['serial_received'] = $a;

    if($b != null || $b != ""){
        echo "true";
    } else {
        echo "false";
    }