<?php
require('C:\xampp\htdocs\Newfolder\inc\links.php');
require('admin/inc/db_config.php');
require('admin/inc/essentials.php');
require('inc\header.php');
require_once('vendor/autoload.php');

use Instamojo\Instamojo;

 // Ensure session is started

if (isset($_GET["id"]) && isset($_GET["payment_request_id"])) {
    $API_KEY = "test_7d6a5f63046bc2869092f26082f";
    $AUTH_TOKEN = "test_d1f2f1611975543fc3188320975";
    $URL = "https://test.instamojo.com/api/1.1/";
    $id = $_GET["id"];
    $payid = $_GET["payment_request_id"];
    $api = new Instamojo($API_KEY, $AUTH_TOKEN, $URL);

    // Check if this payment request has already been processed
    $check_query = "SELECT booking_id FROM booking_order WHERE trans_id = ?";
    $stmt = $con->prepare($check_query);
    $stmt->bind_param('s', $payid);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Payment already processed
        echo <<<HTML
            <div class="container">
                <div class="row">
                    <div class="col-12 px-4">
                        <p class="fw-bold alert alert-info"><i class="bi bi-info-circle-fill"></i> Payment already processed. You will be redirected shortly. <br><br></p>
                    </div>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = 'events.php';
                }, 3000); // Redirect after 3 seconds
            </script>
        HTML;
        exit;
    }

    // Insert into booking_order table
    $q1 = "INSERT INTO `booking_order`(`booking_details_id`, `user_id`, `room_id`, `check_in`, `check_out`, `trans_amt`, `trans_id`) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $value = [$id, $_SESSION['uId'], $_SESSION['room']['id'], $_SESSION['room']['checkin'], $_SESSION['room']['checkout'], $_SESSION['room']['price'], $payid];
    insert($q1, $value, 'iiissis');

    $booking_id = mysqli_insert_id($con);

    try {
        $response = $api->paymentRequestStatus($payid);

        // Check the response
        if (isset($response['payments'][0])) {
            $payment = $response['payments'][0];

            // Update booking order with payment status
            $q2 = "UPDATE `booking_order` SET `arrival` = ?, `booking_status` = ?, `order_id` = ?, `trans_id` = ?, `trans_status` = ? WHERE `booking_id` = ?";
            $val = [1, $payment['status'], $payment['payment_id'], $payid, $payment['status'], $booking_id];
            update($q2, $val, 'issssi');

            if ($payment['status'] == 'Credit') {
                echo <<<HTML
                    <div class="container">
                        <div class="row">
                            <div class="col-12 px-4">
                                <p class="fw-bold alert alert-success"><i class="bi bi-check-circle-fill"></i> Payment Done! You will be redirected shortly. <br><br></p>
                            </div>
                        </div>
                    </div>
                    <script>
                        setTimeout(function() {
                            window.location.href = 'events.php';
                        }, 3000); // Redirect after 3 seconds
                    </script>
                HTML;
            } else {
                echo <<<HTML
                    <div class="container">
                        <div class="row">
                            <div class="col-12 px-4">
                                <p class="fw-bold alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> Payment Failed. You will be redirected shortly. <br><br></p>
                            </div>
                        </div>
                    </div>
                    <script>
                        setTimeout(function() {
                            window.location.href = 'events.php';
                        }, 3000); // Redirect after 3 seconds
                    </script>
                HTML;
            }
        } else {
            echo '<div class="container"><div class="row"><div class="col-12 px-4"><p class="fw-bold alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> Error: Invalid payment response.</p></div></div></div>';
        }

    } catch (Exception $e) {
        echo '<div class="container"><div class="row"><div class="col-12 px-4"><p class="fw-bold alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> Error: ' . $e->getMessage() . '</p></div></div></div>';
    }
} else {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Instamojo Thank You</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .alert-info {
            background-color: #d9edf7;
            color: #31708f;
        }
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .alert-danger {
            background-color: #f2dede;
            color: #a94442;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row">
            <!-- Content inserted here by PHP -->
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
