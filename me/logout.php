<?php
require_once '../classes/Session.php';

$session = new Session();
$session->logout();
header("Location: /index.php");
exit();
?>
