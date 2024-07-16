<?php
session_start();

return array(
    'account_id' =>  $_SESSION['account_id'],
    'account_email' => $_SESSION['account_email'],
    'account_role' => $_SESSION['account_role']
);

?>

