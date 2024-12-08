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
function send_mail($email,$token,$type)
{
    if($type == "email_confirmation")
    {
        $page = 'email_confirm.php';
        $subject = "Account verification";
        $content = "confirm your email";
    }

    else{
        $page = 'index.php';
        $subject = "Account recovery";
        $content = "reset your account";
    }
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    
    
    //Load Composer's autoloader
    
    
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'passigaurav974@gmail.com';                     //SMTP username
        $mail->Password   = 'kdmwktnflxbgbxiv';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('passigaurav974@gmail.com', 'admin');
        $mail->addAddress($email);     //Add a recipient
        
    
        
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = "Welcome to Event handler
           Click the link below to $content
           <a href='".SITE_URL."$page?$type&email=$email&token=$token"."'>Verify</a>
        ";
    
        $mail->send();
        return 1;
    } catch (Exception $e) {
        return 0;
    }
}



if(isset($_POST['register'])) {
    // Assuming filteration() sanitizes and validates input data
    $data = filteration($_POST);

    // Check if passwords match
    if ($data['pass'] != $data['cpass']) {
        echo 'pass_mismatch';
        exit;
    }

    // Check if email or phone number already exists
    $u_exist = select("SELECT * FROM `user_cred` WHERE `email`=? OR `phonenum`=? LIMIT 1", [$data['email'], $data['phonenum']], 'ss');

    if (!$u_exist) {
        echo 'db_error';
        exit;
    }

    if (mysqli_num_rows($u_exist) > 0) {
        $u_exist_fetch = mysqli_fetch_assoc($u_exist);
        echo ($u_exist_fetch['email'] == $data['email']) ? 'email_already' : 'phone_already';
        exit;
    }

    // Generate a token for email confirmation
    $token = bin2hex(random_bytes(16));

    // Send confirmation email
    if (!send_mail($data['email'], $token, "email_confirmation")) {
        echo 'mail_failed';
        exit;
    }

    // Hash the password
    $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);

    // Prepare and execute the query for insertion
    $query = "INSERT INTO `user_cred`(`name`, `email`, `address`, `phonenum`, `password`, `token`) VALUES (?, ?, ?, ?, ?, ?)";
    $values = [$data['name'], $data['email'], $data['address'], $data['phonenum'], $enc_pass, $token];

    if (!insert($query, $values, 'ssssss')) {
        echo 'ins_failed';
        exit;
    }

    echo 1; // Success
}

if(isset($_POST['login']))
{
$data = filteration($_POST);

$u_exist = select("SELECT * FROM `user_cred` WHERE `email`=? OR `phonenum`=? LIMIT 1",[$data['email_mob'],$data['email_mob']],'ss');
    if(mysqli_num_rows($u_exist)==0)
     {
        echo 'inv_email_mob';
        


     }
     else{
          $u_fetch = mysqli_fetch_assoc($u_exist);
          if($u_fetch['is_verified']==0)
          {
            echo 'not_verified';
          }
          else if($u_fetch['status']==0)
          {
            echo 'inactive';
          }
          else{
            if(!password_verify($data['pass'],$u_fetch['password']))
            {
                echo 'invalid_pass';
            }
            else{
                session_start();
                $_SESSION['login'] = true;
                $_SESSION['uId'] = $u_fetch['id'];
                $_SESSION['uName'] = $u_fetch['name'];
                $_SESSION['uPhone'] = $u_fetch['phonenum'];
                echo 1;

            }

          }

     }
        
 

    

}

if(isset($_POST['forgot_pass']))
{

    $data = filteration($_POST);
    $u_exist = select("SELECT * FROM `user_cred` WHERE `email`=? LIMIT 1",[$data['email']],'s');
    if(mysqli_num_rows($u_exist)==0)
    {
        echo 'inv_email';
    }
    else{

    $u_fetch = mysqli_fetch_assoc($u_exist);
    if($u_fetch['is_verified']==0)
    {
      echo 'not_verified';
    }

    else if($u_fetch['status']==0)
    {
      echo 'inactive';
    }
    else{
        //send reset link
        $token = bin2hex(random_bytes(16));

        if(!send_mail($data['email'],$token,"account_recovery"))
        {
            echo 'mail_failed';
        }
        else
        {
            
            $date = date("Y-m-d");
           $query = mysqli_query($con,"UPDATE `user_cred` SET 
            `token`='$token',`t_expire`='$date'
             WHERE `id`= '$u_fetch[id]'");
            if($query)
            {
                echo 1;
            }
            else{
                echo 'upd_failed';
            }
            
        }
    }
}


}

if(isset($_POST['recover_user']))
{
    $data = filteration($_POST);

    $enc_pass = password_hash($data['pass'],PASSWORD_BCRYPT);
    $query = "UPDATE `user_cred` SET `password` = ? , 
    `token`= ? ,`t_expire`= ?
     WHERE `email`=? AND `token`=?";

     $values = [$enc_pass,null,null,$data['email'],$data['token']];

     if(update($query,$values,'sssss'))
     {
        echo 1;
     }

     else
     {
        echo 'failed';
     }



}
