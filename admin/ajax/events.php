<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();



if(isset($_POST['get_all_rooms']))
{    
    $res = selectAll('booking_event');
    $i = 0;
    $data = "";

    while($row = mysqli_fetch_assoc($res)){

        if($row['is_verified']==1)
        {
           $status = "<button onclick='toggle_status($row[booking_id],0)' class='btn btn-success btn-sm shadow-none'>Allowed</button>";
        }
        else{
            $status = "<button onclick='toggle_status($row[booking_id],1)' class='btn btn-danger btn-sm shadow-none' >Not Allowed</button>"; 
        }
        

      $data.="
      <tr class='align-middle'>
        <td>$i</td>
        <td>$row[event_name]</td>
        <td>$status</td>
        <td>

        <button type='button' onclick='edit_details($row[booking_id])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-room'>
            <i class='bi bi-pencil-square'></i> 
        </button>

        <button type='button' onclick=\"edit_image($row[booking_id])\" class='btn btn-info shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#room-images'>
            <i class='bi bi-images'></i> 
        </button>

        
        
        </td>
      </tr>
      ";
      $i++;

    }
    echo $data;

}

if (isset($_POST['toggle_status']) ) {
    // Sanitize and validate input data
    $status_id = intval($_POST['toggle_status']);
    $status_value = intval($_POST['value']);

    // Prepare SQL query
    $q = "UPDATE `booking_event` SET `is_verified` = ? WHERE `booking_id` = ?";
    $v = [$status_value, $status_id];
       
    // Execute update query
    if (update($q, $v, 'ii')) {
        echo 1; // Success
    } else {
        echo 0; // Failure
    }
} 


if(isset($_POST['get_room']))
{
   $frm_data = filteration($_POST);
    $res1 = select("SELECT * FROM `booking_event` WHERE `booking_id`=?",[$frm_data['get_room']],'i');
    $roomdata = mysqli_fetch_assoc($res1);

    $data = ["roomdata" => $roomdata];

    $data = json_encode($data);

    echo $data ;

}

if(isset($_POST['get_event']))
{
   $frm_data = filteration($_POST);
    $res1 = select("SELECT * FROM `booking_event` WHERE `booking_id`=?",[$frm_data['get_event']],'i');
    $roomdata = mysqli_fetch_assoc($res1);

    $data = ["roomdata" => $roomdata];

    $data = json_encode($data);

    echo $data ;

}


/*
if(isset($_POST['edit_room']))
{
    
    $frm_data = filteration($_POST);
    $flag = 0;
    $q1 = "UPDATE `booking_event` SET `event_name`= ? ,`price`= ? ,`event_desc`= ?,`check_in`= ?,`check_out`= ? WHERE `booking_id` = ?";
    $values = [$frm_data['name'],$frm_data['price'],$frm_data['desc'],$frm_data['checkin'],$frm_data['checkout'],$frm_data['booking_id']];


    $tb_query = "SELECT COUNT(*) AS `total` FROM `booking_event` WHERE room_id=? AND check_out > ? AND check_in < ?";
    $valu = [$frm_data['room_id'],$frm_data['checkin'],$frm_data['checkout']];

    $tb_fetch = mysqli_fetch_assoc(select($tb_query,$valu,'iss'));

    if(update($q1,$values,'sisssi'))
    {

      $flag = 1;
    }

    if ($tb_fetch['total'] > 0) 
    {

        
        $flag = 0;
            
    }




    
    if($flag)
    {
        echo 1;
    }
    else
    {
        echo 0;
    }

}
*/
/*
if (isset($_POST['edit_room'])) {
    $frm_data = filteration($_POST);
    $flag = 0;
    
    // Check for date clashes
    $tb_query = "SELECT COUNT(*) AS `total` FROM `booking_event` WHERE room_id=? AND check_out > ? AND check_in < ?";
    $valu = [$frm_data['room_id'], $frm_data['checkin'], $frm_data['checkout']];
    
    $tb_fetch = mysqli_fetch_assoc(select($tb_query, $valu, 'iss'));
    
    // If no date clashes, proceed with update
    if ($tb_fetch['total'] == 0) {
        $q1 = "UPDATE `booking_event` SET `event_name`= ?, `price`= ?, `event_desc`= ?, `check_in`= ?, `check_out`= ? WHERE `booking_id` = ?";
        $values = [$frm_data['name'], $frm_data['price'], $frm_data['desc'], $frm_data['checkin'], $frm_data['checkout'], $frm_data['booking_id']];
        
        if (update($q1, $values, 'sisssi')) {
            $flag = 1;
        }
    }
    
    // Output result
    echo $flag ? 1 : 0;
}
*/
/*
if (isset($_POST['edit_room'])) {
    $frm_data = filteration($_POST);
    $flag = 0;

    // Get today's date for validation
    $today = date('Y-m-d');

    // Extract input data
    $checkin = $frm_data['checkin'];
    $checkout = $frm_data['checkout'];

    // Validation checks
    if ($checkin >= $checkout) {
        // Check-out date must be after check-in date
        echo 0;
        exit;
    }
    if ($checkin == $checkout) {
        // Check-in and check-out dates should not be the same
        echo 0;
        exit;
    }
    if ($checkin < $today || $checkout < $today) {
        // Check-in and check-out dates must not be before today
        echo 0;
        exit;
    }

    // Check for date clashes
    $tb_query = "SELECT COUNT(*) AS `total` FROM `booking_event` WHERE room_id=? AND check_out > ? AND check_in < ?";
    $valu = [$frm_data['room_id'], $checkin, $checkout];
    
    $tb_fetch = mysqli_fetch_assoc(select($tb_query, $valu, 'iss'));

    // If no date clashes, proceed with update
    if ($tb_fetch['total'] == 0) {
        $q1 = "UPDATE `booking_event` SET `event_name`= ?, `price`= ?, `event_desc`= ?, `check_in`= ?, `check_out`= ? WHERE `booking_id` = ?";
        $values = [$frm_data['name'], $frm_data['price'], $frm_data['desc'], $checkin, $checkout, $frm_data['booking_id']];
        
        if (update($q1, $values, 'sisssi')) {
            $flag = 1;
        }
    }

    // Output result
    echo $flag ? 1 : 0;
}
*/

/*
if(isset($_POST['toggle_status']))
{
$frm_data = filteration($_POST);
$q="UPDATE `rooms` SET `status`= ? WHERE `id` = ?";
$v = [$frm_data['$value'],$frm_data['toggle_status']];
if(update($q,$v,'ii'))
{
    echo 1;
}
else
{
    echo 0;
}
}
*/

if (isset($_POST['edit_room'])) {
    $frm_data = filteration($_POST);
    $flag = 0;

    // Get today's date for validation
    $today = date('Y-m-d');

    // Extract input data
    $checkin = $frm_data['checkin'];
    $checkout = $frm_data['checkout'];
    $current_checkin = $frm_data['current_checkin'];
    $current_checkout = $frm_data['current_checkout'];

    // Check if dates have changed
    $dates_changed = ($checkin !== $current_checkin || $checkout !== $current_checkout);

    if ($dates_changed) {
        // Validation checks
        if ($checkin >= $checkout) {
            // Check-out date must be after check-in date
            echo 0;
            exit;
        }
        if ($checkin == $checkout) {
            // Check-in and check-out dates should not be the same
            echo 0;
            exit;
        }
        if ($checkin < $today || $checkout < $today) {
            // Check-in and check-out dates must not be before today
            echo 0;
            exit;
        }

        // Check for date clashes
        $tb_query = "SELECT COUNT(*) AS `total` FROM `booking_event` WHERE room_id=? AND check_out > ? AND check_in < ?";
        $valu = [$frm_data['room_id'], $checkin, $checkout];
        
        $tb_fetch = mysqli_fetch_assoc(select($tb_query, $valu, 'iss'));

        if ($tb_fetch['total'] > 0) {
            // Date clash found
            echo 0;
            exit;
        }
    }

    // Proceed with update regardless of date changes (if any)
    $q1 = "UPDATE `booking_event` SET `event_name`= ?, `price`= ?, `event_desc`= ?, `check_in`= ?, `check_out`= ? WHERE `booking_id` = ?";
    $values = [$frm_data['name'], $frm_data['price'], $frm_data['desc'], $checkin, $checkout, $frm_data['booking_id']];
    
    if (update($q1, $values, 'sisssi')) {
        $flag = 1;
    }

    // Output result
    echo $flag ? 1 : 0;
}





if(isset($_POST['add_image']))
{
    $frm_data = filteration($_POST);
    $img_r = uploadImage($_FILES['image'],EVENTS_FOLDER);

    if($img_r == 'inv_img'){
        echo $img_r;
    }
    else if($img_r == 'inv_size'){
        echo $img_r;
    }

    else if($img_r == 'upd_failed'){
        echo $img_r;
    }
    else{
        /*
        $q = "INSERT INTO `room_image`(`room_id`, `image`) VALUES (?,?)";
        */
        $q = "UPDATE `booking_event` SET `event_image`= ? WHERE `booking_id`=?";
        $values = [$img_r,$frm_data['booking_id']];
        $res = update($q , $values , 'si');
        echo $res;
    }

    
}














