<?php

require_once('vendor/autoload.php');
require('C:\xampp\htdocs\Newfolder\inc\links.php');
require('admin/inc/db_config.php');
require('admin/inc/essentials.php');

/*
$API_KEY = "test_d883b3a8d2bc1adc7a535506713";
$AUTH_TOKEN = "test_dc229039d2232a260a2df3f7502";
*/

$API_KEY = "test_7d6a5f63046bc2869092f26082f";
$AUTH_TOKEN = "test_d1f2f1611975543fc3188320975";

$frm_data = filteration($_POST);

$URL = "https://test.instamojo.com/api/1.1/";
$api = new Instamojo\Instamojo($API_KEY, $AUTH_TOKEN, $URL);

// Additional checks before making payment request
$user_id = $frm_data['id'];
$booking_id = $frm_data['rid'];

// Check if there's a pending booking with status 'credit'
$q_check_credit = "SELECT COUNT(*) as count FROM `booking_order` WHERE `user_id` = ? AND `room_id` = ? AND `booking_status` = 'credit'";
$stmt_check_credit = mysqli_prepare($con, $q_check_credit);
if ($stmt_check_credit === false) {
    die('Prepare failed: ' . mysqli_error($con));
}
mysqli_stmt_bind_param($stmt_check_credit, 'ii', $user_id, $booking_id);
mysqli_stmt_execute($stmt_check_credit);
$result_check_credit = mysqli_stmt_get_result($stmt_check_credit);
$row_check_credit = mysqli_fetch_assoc($result_check_credit);

if ($row_check_credit['count'] > 0) {
    // User has a pending booking with 'credit' status, redirect with an alert
    echo "<script>
        alert('You have a booking of this event already.');
        window.location.href = 'events.php';
    </script>";
    mysqli_stmt_close($stmt_check_credit);
    exit(); // Stop further execution
}

// Proceed with payment request only if no pending bookings with 'credit' status


// Insert new booking if payment request is successful and no pending bookings were found
$q1 = "INSERT INTO `booking_details`(`user_id`, `booking_id`, `event_name`, `price`, `total_pay`, `user_name`, `phonenum`) 
VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt_insert = mysqli_prepare($con, $q1);
if ($stmt_insert === false) {
    die('Prepare failed: ' . mysqli_error($con));
}
mysqli_stmt_bind_param($stmt_insert, 'iisssss', $user_id, $booking_id, $frm_data['ename'], $frm_data['amount'], $frm_data['amount'], $frm_data['name'], $frm_data['mobile_number']);
mysqli_stmt_execute($stmt_insert);
mysqli_stmt_close($stmt_insert);

$count = mysqli_insert_id($con);


try {
    // Create payment request
    $response = $api->paymentRequestCreate(array(
        "purpose" => $_POST["ename"],
        "amount" => $_POST["amount"],
        "buyer_name" => $_POST["name"],
        "send_email" => true,
        "email" => $_POST["email"],
        "phone" => $_POST["mobile_number"],
        "redirect_url" => SITE_URL . "payment-success.php?id=$count"
    ));
    
    // Redirect to payment URL
    header('Location: ' . $response['longurl']);
     exit();// Stop further execution
} catch (Exception $e) {
    // Handle error
    $error = $e->getMessage();
    $formatted_error = extractErrorValues($error);
    alerta('error', $formatted_error);

    // Redirect with delay
    echo "<script>
            setTimeout(function() {
                window.location.href = 'confirm_booking.php';
            }, 3000); // 3000 milliseconds = 3 seconds
        </script>";
    exit(); // Stop further execution
}


// Function to extract values from Instamojo error messages
function extractErrorValues($error) {
    // Decode JSON error message
    $error_array = json_decode($error, true);

    // Check if decoding was successful
    if (json_last_error() === JSON_ERROR_NONE) {
        $values = [];
        
        // Iterate through the array and extract only values
        foreach ($error_array as $errors) {
            if (is_array($errors)) {
                foreach ($errors as $error_message) {
                    $values[] = $error_message;
                }
            } else {
                $values[] = $errors;
            }
        }
        
        // Convert array of values to a single string with new lines
        return implode("\n", $values);
    } else {
        // If JSON decoding failed, return the raw error message
        return $error;
    }
}

?>
