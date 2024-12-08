<?php

require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require ('C:\xampp\htdocs\Newfolder\PHPMailer\Exception.php');
require ('C:\xampp\htdocs\Newfolder\PHPMailer\PHPMailer.php');
require ('C:\xampp\htdocs\Newfolder\PHPMailer\SMTP.php');

date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['check_availability']))
{
    $frm_data = filteration($_POST);
    $status = "";
    $result = "";
    $img = uploadEventImage($_FILES['event_image'],EVENTS_FOLDER);

    if($img == 'inv_img')
    {
        $status = 'inv_img';
        $result = json_encode(["status"=>$status]);
    }
    else if($img == 'upd_failed')
    {
        $status = 'upd_failed';
        $result = json_encode(["status"=>$status]);
    }

    $today_date = new DateTime(date("Y-m-d"));
    $checkin_date = new DateTime($frm_data['check_in']);
    $checkout_date = new DateTime($frm_data['check_out']);

    if($checkin_date == $checkout_date)
    {
        $status = 'check_in_out_equal';
        $result = json_encode(["status"=>$status]);

    }

    else if($checkout_date < $checkin_date)
    {
        $status = 'check_out_earlier';
        $result = json_encode(["status"=>$status]);
    }

    else if($checkin_date < $today_date)
    {
        $status = 'check_in_earlier';
        $result = json_encode(["status"=>$status]);
    }

    if($status!='')
    {
        echo $result;
    }

    else{
         session_start();
         $tb_query = "SELECT COUNT(*) AS `total` FROM `booking_event` WHERE room_id=? AND check_out > ? AND check_in < ?";
         $values = [$_SESSION['room']['id'],$frm_data['check_in'],$frm_data['check_out']];

        $tb_fetch = mysqli_fetch_assoc(select($tb_query,$values,'iss'));

        if ($tb_fetch['total'] > 0) {

        $status = 'unavailable';
        $result = json_encode(["status"=>$status]);
        echo $result;
        exit;
            
        }


         
         
        $count_days = date_diff($checkin_date,$checkout_date)->days;
        $_SESSION['room']['available'] = true ;

        $result = json_encode(["status"=>'available',"days"=>$count_days]);
        $query = "INSERT INTO `booking_event`(`user_id`, `room_id`, `event_name`, `price`, `event_desc`, `event_image`, `check_in`, `check_out`) 
        VALUES (?,?,?,?,?,?,?,?)";

        $values = [$_SESSION['uId'],$_SESSION['room']['id'],$frm_data['name'],$frm_data['price'],$frm_data['desc'],$img,$frm_data['check_in'],$frm_data['check_out']];
        
        if(insert($query,$values,'iisissss'))
        {

            


        echo $result;
        
        }

        else
        {
            echo 'ins_failed';
        }









    }




    


    






}