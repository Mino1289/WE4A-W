<?php
    session_start();
    session_destroy();

    // get the latest page
    $page = $_SERVER['HTTP_REFERER'];
    header("Location: $page");
?>