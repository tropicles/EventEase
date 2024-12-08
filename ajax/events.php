<?php
/*
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

date_default_timezone_set("Asia/Kolkata");

session_start();

function fetchFilteredBookings($con, $status, $removed, $checkin = '', $checkout = '') {
    $currentDate = date('Y-m-d'); // Get the current date in 'YYYY-MM-DD' format

    $sql = "SELECT 
                b.`booking_id`, 
                b.`user_id`, 
                b.`room_id`, 
                b.`event_name`, 
                b.`event_desc`, 
                b.`event_image`, 
                b.`check_in`, 
                b.`check_out`, 
                b.`datentime`, 
                r.`status`, 
                r.`removed`
            FROM 
                `booking_event` b
            INNER JOIN 
                `rooms` r 
            ON 
                b.`room_id` = r.`id`
            WHERE 
                r.`status` = ? 
                AND r.`removed` = ? 
                AND b.`check_out` >= ? "; // Ensure checkout date is not in the past

    $values = [$status, $removed, $currentDate];
    $datatypes = 'iis';

    // Add date filtering if dates are provided
    if ($checkin && $checkout) {
        $sql .= " AND (
            (b.`check_in` <= ? AND b.`check_out` >= ?) 
            OR 
            (b.`check_in` >= ? AND b.`check_in` < ?) 
            OR 
            (b.`check_out` > ? AND b.`check_out` <= ?)
        )";
        array_push($values, $checkout, $checkin, $checkin, $checkout, $checkin, $checkout);
        $datatypes .= 'ssssss';
    }

    // Prepare and execute the query
    $stmt = $con->prepare($sql);
    $stmt->bind_param($datatypes, ...$values);
    $stmt->execute();
    return $stmt->get_result();
}

// Check if 'fetch_events' is set

if (isset($_GET['fetch_events'])) {
    
    // Decode the 'chk_avail' JSON parameter
    $chk_avail = json_decode($_GET['chk_avail'], true);

    // Fetch dates from the decoded object
    $checkin = isset($chk_avail['checkin']) ? $chk_avail['checkin'] : '';
    $checkout = isset($chk_avail['checkout']) ? $chk_avail['checkout'] : '';

    // Default values for status and removed
    $status = 1;  // Example status
    $removed = 0; // Example removed

    // Fetch filtered bookings
    $room_res = fetchFilteredBookings($con, $status, $removed, $checkin, $checkout);

    $count_events = 0;
    $output = "";

    while ($room_data = mysqli_fetch_assoc($room_res)) {
        // Prepare queries to fetch event details
        $desc_query = "SELECT `event_desc` FROM `booking_event` WHERE `booking_id` = ?";
        $stmt_desc = $con->prepare($desc_query);
        $stmt_desc->bind_param('i', $room_data['booking_id']);
        $stmt_desc->execute();
        $desc_result = $stmt_desc->get_result();
        $desc_data = "";
        while ($fdesc = $desc_result->fetch_assoc()) {
            $desc_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>{$fdesc['event_desc']}</span>";
        }

        $venue_query = "SELECT r.name FROM `rooms` r INNER JOIN `booking_event` b ON b.room_id = r.id WHERE b.booking_id = ?";
        $stmt_venue = $con->prepare($venue_query);
        $stmt_venue->bind_param('i', $room_data['booking_id']);
        $stmt_venue->execute();
        $venue_result = $stmt_venue->get_result();
        $ven_data = "";
        while ($fven = $venue_result->fetch_assoc()) {
            $ven_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>{$fven['name']}</span>";
        }

        $startd_query = "SELECT `check_in` FROM `booking_event` WHERE `booking_id` = ?";
        $stmt_startd = $con->prepare($startd_query);
        $stmt_startd->bind_param('i', $room_data['booking_id']);
        $stmt_startd->execute();
        $startd_result = $stmt_startd->get_result();
        $start_data = "";
        while ($fstartd = $startd_result->fetch_assoc()) {
            $check_in_date = new DateTime($fstartd['check_in']);
            $formatted_date = $check_in_date->format('l, F j, Y');
            $start_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>$formatted_date</span>";
        }

        $endd_query = "SELECT `check_out` FROM `booking_event` WHERE `booking_id` = ?";
        $stmt_endd = $con->prepare($endd_query);
        $stmt_endd->bind_param('i', $room_data['booking_id']);
        $stmt_endd->execute();
        $endd_result = $stmt_endd->get_result();
        $end_data = "";
        while ($fendd = $endd_result->fetch_assoc()) {
            $check_out_date = new DateTime($fendd['check_out']);
            $formatted_date_end = $check_out_date->format('l, F j, Y');
            $end_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>$formatted_date_end</span>";
        }

        $price_query = "SELECT `price` FROM `booking_event` WHERE `booking_id` = ?";
        $stmt_price = $con->prepare($price_query);
        $stmt_price->bind_param('i', $room_data['booking_id']);
        $stmt_price->execute();
        $price_result = $stmt_price->get_result();
        $price_data = "";
        while ($fprice = $price_result->fetch_assoc()) {
            $price_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>₹{$fprice['price']}</span>";
        }

        $room_thumb = EVENT_IMG_PATH . "thumbnail.jpg";
        $thumb_query = "SELECT `event_image` FROM `booking_event` WHERE `booking_id` = ?";
        $stmt_thumb = $con->prepare($thumb_query);
        $stmt_thumb->bind_param('i', $room_data['booking_id']);
        $stmt_thumb->execute();
        $thumb_result = $stmt_thumb->get_result();
        if ($thumb_result->num_rows > 0) {
            $thumb_res = $thumb_result->fetch_assoc();
            $room_thumb = EVENT_IMG_PATH . $thumb_res['event_image'];
        }

        $login = isset($_SESSION['login']) && $_SESSION['login'] ? 1 : 0;
        $book_btn = "<button onclick='checkLogin($login, {$room_data['booking_id']})' class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2'>Book Now</button>";

        $output .= "
            <div class='col-lg-4 col-md-6 my-3'>
                <div class='card border-0 shadow' style='width: 350px; margin: auto;'>
                    <img src='$room_thumb' class='card-img-top' alt='Event Image'>
                    <div class='card-body'>
                        <h5>{$room_data['event_name']}</h5>
                        <p>$desc_data</p>
                        <h5>Venue</h5>
                        <p>$ven_data</p>
                        <h5>Date</h5>
                        <p>$start_data</p>
                        <div class='mb-3 my-3'>To</div>
                        <p>$end_data</p>
                        <h5>Price</h5>
                        <p>$price_data</p>
                        $book_btn
                    </div>
                </div>
            </div>
        ";

        $count_events++;
    }

    if ($count_events > 0) {
        echo $output;
    } else {
        echo "<h3 class='text-center text-danger'>No Events to show</h3>";
    }
}

*/




?>

<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

date_default_timezone_set("Asia/Kolkata");

session_start();

function fetchFilteredBookings($con, $status, $removed, $checkin = '', $checkout = '') {
    $currentDate = date('Y-m-d'); // Get the current date in 'YYYY-MM-DD' format
    $is_verfied = 1;
    $sql = "SELECT 
                b.`booking_id`, 
                b.`user_id`, 
                b.`room_id`, 
                b.`event_name`, 
                b.`event_desc`, 
                b.`event_image`, 
                b.`check_in`, 
                b.`check_out`,
                b.`is_verified`, 
                b.`datentime`, 
                r.`status`, 
                r.`removed`
            FROM 
                `booking_event` b
            INNER JOIN 
                `rooms` r 
            ON 
                b.`room_id` = r.`id`
            WHERE 
                b.`is_verified` = ?
                AND
                r.`status` = ? 
                AND r.`removed` = ? 
                AND b.`check_out` >= ?"; // Ensure checkout date is not in the past

    $values = [$is_verfied,$status, $removed, $currentDate];
    $datatypes = 'iiis';

    // Add date filtering if dates are provided
    if ($checkin && $checkout) {
        $sql .= " AND (
            (b.`check_in` <= ? AND b.`check_out` >= ?) 
            OR 
            (b.`check_in` >= ? AND b.`check_in` < ?) 
            OR 
            (b.`check_out` > ? AND b.`check_out` <= ?)
        )";
        array_push($values, $checkout, $checkin, $checkin, $checkout, $checkin, $checkout);
        $datatypes .= 'ssssss';
    }

    // Prepare and execute the query
    $stmt = $con->prepare($sql);
    $stmt->bind_param($datatypes, ...$values);
    $stmt->execute();
    return $stmt->get_result();
}

// Check if 'fetch_events' is set
if (isset($_GET['fetch_events'])) {
    
    // Decode the 'chk_avail' JSON parameter
    $chk_avail = json_decode($_GET['chk_avail'], true);

    // Fetch dates from the decoded object
    $checkin = isset($chk_avail['checkin']) ? $chk_avail['checkin'] : '';
    $checkout = isset($chk_avail['checkout']) ? $chk_avail['checkout'] : '';

    // Validate dates
    if ($checkin && $checkout) {
        $currentDate = date('Y-m-d');
        
        // Convert dates to DateTime objects for comparison
        $checkinDate = new DateTime($checkin);
        $checkoutDate = new DateTime($checkout);

        // Check if start date is before current date
        /*
        if ($checkinDate < new DateTime($currentDate)) {
            echo "<h3 class='text-center text-danger'>Start date cannot be before today.</h3>";
            exit();
        }*/

        // Check if start date is after end date
        if ($checkinDate > $checkoutDate) {
            echo "<h3 class='text-center text-danger'>End date must be after the start date.</h3>";
            exit();
        }

        // Check if start date and end date are the same
        if ($checkinDate->format('Y-m-d') === $checkoutDate->format('Y-m-d')) {
            echo "<h3 class='text-center text-danger'>Start date and end date cannot be the same.</h3>";
            exit();
        }
    }

    // Default values for status and removed
    $status = 1;  // Example status
    $removed = 0; // Example removed

    // Fetch filtered bookings
    $room_res = fetchFilteredBookings($con, $status, $removed, $checkin, $checkout);

    $count_events = 0;
    $output = "";

    while ($room_data = mysqli_fetch_assoc($room_res)) {
        // Prepare queries to fetch event details
        $desc_query = "SELECT `event_desc` FROM `booking_event` WHERE `booking_id` = ?";
        $stmt_desc = $con->prepare($desc_query);
        $stmt_desc->bind_param('i', $room_data['booking_id']);
        $stmt_desc->execute();
        $desc_result = $stmt_desc->get_result();
        $desc_data = "";
        while ($fdesc = $desc_result->fetch_assoc()) {
            $desc_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>{$fdesc['event_desc']}</span>";
        }

        $venue_query = "SELECT r.name FROM `rooms` r INNER JOIN `booking_event` b ON b.room_id = r.id WHERE b.booking_id = ?";
        $stmt_venue = $con->prepare($venue_query);
        $stmt_venue->bind_param('i', $room_data['booking_id']);
        $stmt_venue->execute();
        $venue_result = $stmt_venue->get_result();
        $ven_data = "";
        while ($fven = $venue_result->fetch_assoc()) {
            $ven_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>{$fven['name']}</span>";
        }

        $startd_query = "SELECT `check_in` FROM `booking_event` WHERE `booking_id` = ?";
        $stmt_startd = $con->prepare($startd_query);
        $stmt_startd->bind_param('i', $room_data['booking_id']);
        $stmt_startd->execute();
        $startd_result = $stmt_startd->get_result();
        $start_data = "";
        while ($fstartd = $startd_result->fetch_assoc()) {
            $check_in_date = new DateTime($fstartd['check_in']);
            $formatted_date = $check_in_date->format('l, F j, Y');
            $start_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>$formatted_date</span>";
        }

        $endd_query = "SELECT `check_out` FROM `booking_event` WHERE `booking_id` = ?";
        $stmt_endd = $con->prepare($endd_query);
        $stmt_endd->bind_param('i', $room_data['booking_id']);
        $stmt_endd->execute();
        $endd_result = $stmt_endd->get_result();
        $end_data = "";
        while ($fendd = $endd_result->fetch_assoc()) {
            $check_out_date = new DateTime($fendd['check_out']);
            $formatted_date_end = $check_out_date->format('l, F j, Y');
            $end_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>$formatted_date_end</span>";
        }

        $price_query = "SELECT `price` FROM `booking_event` WHERE `booking_id` = ?";
        $stmt_price = $con->prepare($price_query);
        $stmt_price->bind_param('i', $room_data['booking_id']);
        $stmt_price->execute();
        $price_result = $stmt_price->get_result();
        $price_data = "";
        while ($fprice = $price_result->fetch_assoc()) {
            $price_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>₹{$fprice['price']}</span>";
        }

        $room_thumb = EVENT_IMG_PATH . "thumbnail.jpg";
        $thumb_query = "SELECT `event_image` FROM `booking_event` WHERE `booking_id` = ?";
        $stmt_thumb = $con->prepare($thumb_query);
        $stmt_thumb->bind_param('i', $room_data['booking_id']);
        $stmt_thumb->execute();
        $thumb_result = $stmt_thumb->get_result();
        if ($thumb_result->num_rows > 0) {
            $thumb_res = $thumb_result->fetch_assoc();
            $room_thumb = EVENT_IMG_PATH . $thumb_res['event_image'];
        }

        $login = isset($_SESSION['login']) && $_SESSION['login'] ? 1 : 0;
        $book_btn = "<button onclick='checkLogin($login, {$room_data['booking_id']})' class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2'>Book Now</button>";

        $output .= "
            <div class='col-lg-4 col-md-6 my-3'>
                <div class='card border-0 shadow' style='width: 350px; margin: auto;'>
                    <img src='$room_thumb' class='card-img-top' alt='Event Image'>
                    <div class='card-body'>
                        <h5>{$room_data['event_name']}</h5>
                        <p>$desc_data</p>
                        <h5>Venue</h5>
                        <p>$ven_data</p>
                        <h5>Date</h5>
                        <p>$start_data</p>
                        <div class='mb-3 my-3'>To</div>
                        <p>$end_data</p>
                        <h5>Price</h5>
                        <p>$price_data</p>
                        $book_btn
                    </div>
                </div>
            </div>
        ";

        $count_events++;
    }

    if ($count_events > 0) {
        echo $output;
    } else {
        echo "<h3 class='text-center text-danger'>No Events to show</h3>";
    }
}
?>
