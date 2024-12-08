<?php
/*
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();
*/
?>


<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();

// Initialize variables for storing total values
$total_commissioned_money = 0;
$total_remaining_money = 0;
$total_revenue = 0;
$hoster_revenue = 0;
$event_count = 0;
$tickets_booked = 0;
$total_users = 0;
$active_users = 0;
$inactive_users = 0;
$unverified_users = 0;

// SQL query to select all trans_amt where booking_status is 'Credit'
$sql = "SELECT trans_amt FROM booking_order WHERE booking_status = 'Credit'";
$result = mysqli_query($con, $sql);

if ($result) {
    // Check if query returns rows
    if (mysqli_num_rows($result) > 0) {
        // Process each row
        while ($row = mysqli_fetch_assoc($result)) {
            $trans_amt = $row['trans_amt'];
            $commission = $trans_amt * 0.40; // 40% commission
            $remaining = $trans_amt - $commission;

            // Accumulate totals
            $total_revenue += $trans_amt;
            $total_commissioned_money += $commission;
            $hoster_revenue += $remaining;
        }

        // Format the numbers as currency
        $formatted_commissioned_money = number_format($total_commissioned_money, 2);
        $formatted_hoster_revenue = number_format($hoster_revenue, 2);
        $formatted_total_revenue = number_format($total_revenue, 2);
    } else {
        // Handle case where no records are found
        $formatted_commissioned_money = number_format(0, 2);
        $formatted_hoster_revenue = number_format(0, 2);
        $formatted_total_revenue = number_format(0, 2);
    }
} else {
    // Handle query execution error
    die("Error executing query: " . mysqli_error($con));
}

// SQL query to count number of rows in booking_event table
$sql_events = "SELECT COUNT(*) as event_count FROM booking_event";
$result_events = mysqli_query($con, $sql_events);

if ($result_events) {
    // Fetch the row and get the count
    $row_events = mysqli_fetch_assoc($result_events);
    $event_count = $row_events['event_count'];
} else {
    // Handle query execution error
    die("Error executing query: " . mysqli_error($con));
}

// SQL query to count number of rows where booking_status is 'Credit'
$sql_tickets = "SELECT COUNT(*) as tickets_booked FROM booking_order WHERE booking_status = 'Credit'";
$result_tickets = mysqli_query($con, $sql_tickets);

if ($result_tickets) {
    // Fetch the row and get the count
    $row_tickets = mysqli_fetch_assoc($result_tickets);
    $tickets_booked = $row_tickets['tickets_booked'];
} else {
    // Handle query execution error
    die("Error executing query: " . mysqli_error($con));
}

// SQL query to count number of rows in user_cred table
$sql_users = "SELECT COUNT(*) as total_users FROM user_cred";
$result_users = mysqli_query($con, $sql_users);

if ($result_users) {
    // Fetch the row and get the count
    $row_users = mysqli_fetch_assoc($result_users);
    $total_users = $row_users['total_users'];
} else {
    // Handle query execution error
    die("Error executing query: " . mysqli_error($con));
}

// SQL query to count number of active users (status = 1) in user_cred table
$sql_active_users = "SELECT COUNT(*) as active_users FROM user_cred WHERE status = 1";
$result_active_users = mysqli_query($con, $sql_active_users);

if ($result_active_users) {
    // Fetch the row and get the count
    $row_active_users = mysqli_fetch_assoc($result_active_users);
    $active_users = $row_active_users['active_users'];
} else {
    // Handle query execution error
    die("Error executing query: " . mysqli_error($con));
}

// SQL query to count number of inactive users (status != 1) in user_cred table
$sql_inactive_users = "SELECT COUNT(*) as inactive_users FROM user_cred WHERE status != 1";
$result_inactive_users = mysqli_query($con, $sql_inactive_users);

if ($result_inactive_users) {
    // Fetch the row and get the count
    $row_inactive_users = mysqli_fetch_assoc($result_inactive_users);
    $inactive_users = $row_inactive_users['inactive_users'];
} else {
    // Handle query execution error
    die("Error executing query: " . mysqli_error($con));
}

// SQL query to count number of unverified users (is_verified != 1) in user_cred table
$sql_unverified_users = "SELECT COUNT(*) as unverified_users FROM user_cred WHERE is_verified != 1";
$result_unverified_users = mysqli_query($con, $sql_unverified_users);

if ($result_unverified_users) {
    // Fetch the row and get the count
    $row_unverified_users = mysqli_fetch_assoc($result_unverified_users);
    $unverified_users = $row_unverified_users['unverified_users'];
} else {
    // Handle query execution error
    die("Error executing query: " . mysqli_error($con));
}

// Close the connection
mysqli_close($con);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">
    <div class="container-fluid bg-dark text-light p-3 d-flex align-items-center justify-content-between sticky-top">
        <h3 class="mb-0 h-font">ADMIN PANEL</h3>
        <a href="logout.php" class="btn btn-light btn-sm">LOG OUT</a>
    </div>
    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3>DASHBOARD</h3>
                </div>

                <h5>Revenue Analytics</h5>
                <div class="row mb-3">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-3">
                            <h6>Total revenue</h6>
                            <h1>₹<?php echo $formatted_total_revenue; ?></h1>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-warning p-3">
                            <h6>Hoster's revenue</h6>
                            <h1>₹<?php echo $formatted_hoster_revenue; ?></h1>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6>ADMIN revenue</h6>
                            <h1>₹<?php echo $formatted_commissioned_money; ?></h1>
                        </div>
                    </div>
                </div>

                <h5>Event Analytics</h5>
                <div class="row mb-3">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center p-3">
                            <h6>Events Listed</h6>
                            <h1><?php echo $event_count; ?></h1>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-3">
                            <h6>Event Tickets booked</h6>
                            <h1><?php echo $tickets_booked; ?></h1>
                        </div>
                    </div>
                </div>

                <h5>Users</h5>
                <div class="row mb-3">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-info p-3">
                            <h6>Total Users</h6>
                            <h1 class="mt-2 mb-0"><?php echo $total_users; ?></h1>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-3">
                            <h6>Active Users</h6>
                            <h1 class="mt-2 mb-0"><?php echo $active_users; ?></h1>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-warning p-3">
                            <h6>Inactive Users</h6>
                            <h1 class="mt-2 mb-0"><?php echo $inactive_users; ?></h1>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-danger p-3">
                            <h6>Unverified Users</h6>
                            <h1 class="mt-2 mb-0"><?php echo $unverified_users; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/script.php'); ?>    
</body>
</html>








<!--
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DASHBOARD</title>
    <?php
    require('inc/links.php');
    ?>
</head>
<body class="bg-light">
<div class="container-fluid bg-dark text-light p-3 d-flex align-items-center justify-content-between sticky-top">
    <h3 class="mb-0 h-font">ADMIN PANEL</h3>
    <a href="logout.php" class="btn btn-light btn-sm">LOG OUT</a>
</div>
<?php
require('inc/header.php');





?>


<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto p-4 overflow-hidden">

          <div class="d-flex align-items-center justify-content-between mb-4">
                  <h3>DASHBOARD</h3>
          </div>

          
          <h5>Revenue Analytics</h5>
          <div class="row mb-3">

          
              <div class="col-md-3 mb-4">
            
                     <div class="card text-center text-success p-3">
                        <h6>Total revenue</h6>
                        <h1>₹0</h1>
                     </div>


              </div>

              <div class="col-md-3 mb-4">
            
                     <div class="card text-center text-warning  p-3">
                        <h6>Hoster's revenue</h6>
                        <h1>₹0</h1>
                     </div>


              </div>

              <div class="col-md-3 mb-4">
            
                     <div class="card text-center text-primary  p-3">
                        <h6>ADMIN revenue</h6>
                        <h1>₹0</h1>
                     </div>


              </div>

          </div>

          <h5>Event Analytics</h5>
          <div class="row mb-3">

                
                    <div class="col-md-3 mb-4">
                
                        <div class="card text-center p-3">
                            <h6>Events Listed</h6>
                            <h1>0</h1>
                        </div>


                    </div>

                    <div class="col-md-3 mb-4">
                
                        <div class="card text-center text-success  p-3">
                            <h6>Event Tickets booked</h6>
                            <h1>0</h1>
                        </div>


                    </div>


            </div>
            
      
                <h5>Users</h5>
                <div class="row mb-3">

                    

                        <div class="col-md-3 mb-4">

                            <div class="card text-center text-info  p-3">
                                <h6>Total Users</h6>
                                <h1 class="mt-2 mb-0">0</h1>
                            </div>


                        </div>

                        <div class="col-md-3 mb-4">

                            <div class="card text-center text-success  p-3">
                                <h6>Active Users</h6>
                                <h1 class="mt-2 mb-0">0</h1>
                            </div>


                        </div>

                        <div class="col-md-3 mb-4">

                            <div class="card text-center text-warning  p-3">
                                <h6>Inactive Users</h6>
                                <h1 class="mt-2 mb-0">0</h1>
                            </div>


                        </div>

                        <div class="col-md-3 mb-4">

                            <div class="card text-center text-danger  p-3">
                                <h6>Unverified Users</h6>
                                <h1 class="mt-2 mb-0">0</h1>
                            </div>


                        </div>


                </div>





        </div>
    </div>
</div>







<?php require('inc/script.php');?>    
</body>
</html>
-->