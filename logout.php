<?php
session_start();

// Destroy the session
session_destroy();

// Redirect to the index.html page
header("Location: index.html");
exit();
?>
