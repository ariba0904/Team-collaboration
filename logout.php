<?php

session_start();

/* Destroy All Session */

session_unset();

session_destroy();

/* Redirect Login Page */

header("Location: login.php");

exit();

?>