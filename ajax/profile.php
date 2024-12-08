<?php

require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');
date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['info_form']))
{
    $frm_data = filteration($_POST);
    session_start();
    $u_exist = select("SELECT * FROM `user_cred` WHERE `phonenum`=? AND `id`!=? LIMIT 1", [$frm_data['phonenum'],$_SESSION['uId']], 'si');
    if(mysqli_num_rows($u_exist)!=0)
    {
     echo 'phone_already';
     exit;
    }
    $query = "UPDATE `user_cred` SET `name`= ?,
    `address`= ?,
    `phonenum`= ? WHERE `id`=?";

    $values = [$frm_data['name'],$frm_data['address'],$frm_data['phonenum'],$_SESSION['uId']];
    if(update($query,$values,'sssi'))
    {   
        $_SESSION['uName'] = $frm_data['name'];
        echo 1;
    }
    else{
        echo 0;
    }
    
}
/*
if(isset($_POST['pass_form']))
{
    $frm_data = filteration($_POST);
    session_start();
    if($frm_data['new_pass']!=$frm_data['confirm_pass'])
    {
        echo 'mismatch';
        exit;
    }
    $enc_pass = password_hash($frm_data['new_pass'], PASSWORD_BCRYPT);
    $query = "UPDATE `user_cred` SET `password`= ?,
     WHERE `id`=? LIMIT 1";

     $values=[$enc_pass,$_SESSION['uId']];
     
     if(update($query,$values,'si'))
     {
        echo 1;
     }
     else{
        echo 0;
     }


}*/


if (isset($_POST['pass_form'])) {
    // Assuming filteration function cleans and returns sanitized input
    $frm_data = filteration($_POST);
    session_start();

    // Check if new and confirm passwords match
    if ($frm_data['new_pass'] != $frm_data['confirm_pass']) {
        echo 'mismatch';
        exit;
    }

    // Hash the new password
    $enc_pass = password_hash($frm_data['new_pass'], PASSWORD_BCRYPT);

    // Prepare the SQL query with the correct syntax
    $query = "UPDATE `user_cred` SET `password` = ? WHERE `id` = ? LIMIT 1";

    // Values to bind to the query
    $values = [$enc_pass, $_SESSION['uId']];

    // Execute the query (assuming the update function is defined properly)
    if (update($query, $values, 'si')) {
        echo 1; // Success
    } else {
        echo 0; // Failure
    }
}
