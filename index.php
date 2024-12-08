<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event</title>

     <?php 
     require('C:\xampp\htdocs\Newfolder\inc\links.php');
     require('admin/inc/db_config.php');
     require('admin/inc/essentials.php');
     date_default_timezone_set("Asia/Kolkata");


     ?>
<link  rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>


</head>
<style>
.availability-form{
margin-top: -50px;
z-index: 11;
position: relative;
}

@media screen and (max-width: 575px) {
  .availability-form{
margin-top: 25px;
padding: 0 35px;
}
}


</style>
<class="bg-light">
<?php require('inc\header.php')?>

<div class="container-fluid px-lg-4 mt-4">
    <div class="swiper swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="images/carousel/1.jpg" class="w-100 d-block rounded img-fluid" alt="Slide 1"/>
            </div>
            <div class="swiper-slide">
                <img src="images/carousel/2.jpg" class="w-100 d-block rounded img-fluid" alt="Slide 2"/>
            </div>
            <div class="swiper-slide">
                <img src="images/carousel/3.jpg" class="w-100 d-block rounded img-fluid" alt="Slide 3"/>
            </div>
            <div class="swiper-slide">
                <img src="images/carousel/4.jpg" class="w-100 d-block rounded img-fluid" alt="Slide 4"/>
            </div>
        </div>
    </div>
</div>

<div class="container availability-form">
  <div class="row">
    <div class="col-lg-12 bg-white shadow p-4 rounded" >
      <h5 class="mb-4">Check Place for Hosting</h5>
      <form action="Host.php">
        <div class="row align-items-end">
          <div class="col-lg-8 mb-3">
            <label class="form-label " style="font-weight: 500;">Start Date</label>
            <input type="date" class="form-control shadow-none" name="checkin" required>
          </div>
          <div class="col-lg-8 mb-3">
            <label class="form-label" style="font-weight: 500;">End Date</label>
            <input type="date" class="form-control shadow-none" name="checkout" required>
          </div>
          <input type="hidden" name="check_availability">
          <div class="col-lg-8 mb-3">
            <button type="submit"class="btn text-white shadow-none custom-bg">Check</button>
          </div>

        </div>
      </form>
    </div>
  </div>
</div>


<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Some Events</h2>

<div class="container">
    <div class="row">

        <?php

        // Include required files and configurations
     
        
        
        // Function to fetch filtered bookings with a constraint to ensure checkout is not in the past
        function fetchFilteredBookingsindex($con, $status, $removed, $limit = 3) {
            // Get the current date
            $currentDate = date('Y-m-d');
            $is_verified = 1;
            // Define the SQL query with placeholders for parameters and limit
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
                    AND b.`check_out`>= ?  
                LIMIT ?";

            // Prepare and execute the query with the limit and the constraint
            if ($stmt = mysqli_prepare($con, $sql)) {
                $datatypes = 'iiisi'; // Two integers for status and removed, one string for the current date, and one integer for limit
                mysqli_stmt_bind_param($stmt, $datatypes,$is_verified, $status, $removed, $currentDate, $limit);
                if (mysqli_stmt_execute($stmt)) {
                    $res = mysqli_stmt_get_result($stmt);
                    if (!$res) {
                        die("Query result error: " . mysqli_error($con));
                    }
                    mysqli_stmt_close($stmt);
                    return $res;
                } else {
                    mysqli_stmt_close($stmt);
                    die("Query execution error: " . mysqli_error($con));
                }
            } else {
                die("Query preparation error: " . mysqli_error($con));
            }
        }

        // Main logic to fetch and display the bookings
        $status = 1;  // Example status
        $removed = 0;
        
        $room_res = fetchFilteredBookingsindex($con, $status, $removed);
        
        while ($room_data = mysqli_fetch_assoc($room_res)) {
            
            $desc_query = "SELECT `event_desc` FROM `booking_event` WHERE `booking_id`={$room_data['booking_id']}";
            $desc = mysqli_query($con, $desc_query);
            if (!$desc) {
                die("Description query error: " . mysqli_error($con));
            }


            $desc_data = "";
            while ($fdesc = mysqli_fetch_assoc($desc)) {
                $desc_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>{$fdesc['event_desc']}</span>";
            }

            $venue_query = "SELECT r.name FROM `rooms` r INNER JOIN `booking_event` b ON b.room_id = r.id WHERE b.booking_id = {$room_data['booking_id']}";
            $venue = mysqli_query($con, $venue_query);
            if (!$venue) {
                die("Venue query error: " . mysqli_error($con));
            }

            $ven_data = "";
            while ($fven = mysqli_fetch_assoc($venue)) {
                $ven_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>{$fven['name']}</span>";
            }

            $startd_query = "SELECT `check_in` FROM `booking_event` WHERE `booking_id` = {$room_data['booking_id']}";
            $startd = mysqli_query($con, $startd_query);
            if (!$startd) {
                die("Start date query error: " . mysqli_error($con));
            }

            $start_data = "";
            while ($fstartd = mysqli_fetch_assoc($startd)) {
                $check_in_date = $fstartd['check_in'];
                $datetime = new DateTime($check_in_date);
                $formatted_date = $datetime->format('l, F j, Y');
                $start_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>$formatted_date</span>";
            }

            $endd_query = "SELECT `check_out` FROM `booking_event` WHERE `booking_id` = {$room_data['booking_id']}";
            $endd = mysqli_query($con, $endd_query);
            if (!$endd) {
                die("End date query error: " . mysqli_error($con));
            }

            $end_data = "";
            while ($fendd = mysqli_fetch_assoc($endd)) {
                $check_out_date = $fendd['check_out'];
                $datetime_end = new DateTime($check_out_date);
                $formatted_date_end = $datetime_end->format('l, F j, Y');
                $end_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>$formatted_date_end</span>";
            }

            $price_query = "SELECT `price` FROM `booking_event` WHERE `booking_id`={$room_data['booking_id']}";
            $event_price = mysqli_query($con, $price_query);
            if (!$event_price) {
                die("Price query error: " . mysqli_error($con));
            }

            $price_data = "";
            while ($fprice = mysqli_fetch_assoc($event_price)) {
                $price_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>₹{$fprice['price']}</span>";
            }

            $room_thumb = EVENT_IMG_PATH . "thumbnail.jpg";
            $thumb_query = "SELECT `event_image` FROM `booking_event` WHERE `booking_id`={$room_data['booking_id']}";
            $thumb_q = mysqli_query($con, $thumb_query);
            if (!$thumb_q) {
                die("Thumbnail query error: " . mysqli_error($con));
            }

            if (mysqli_num_rows($thumb_q) > 0) {
                $thumb_res = mysqli_fetch_assoc($thumb_q);
                $room_thumb = EVENT_IMG_PATH . $thumb_res['event_image'];
            }

            $login = isset($_SESSION['login']) && $_SESSION['login'] ? 1 : 0;
            $book_btn = "<button onclick='checkLogin($login, {$room_data['booking_id']})' class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2'>Book Now</button>";
            
            echo <<<data
                <div class="col-lg-4 col-md-6 my-3">
                    <div class="card border-0 shadow" style="width: 350px; margin: auto;">
                        <img src="$room_thumb" class="card-img-top" alt="Event Image">
                        <div class="card-body">
                            <h5>{$room_data['event_name']}</h5>
                            <p>$desc_data</p>
                            <h5>Venue</h5>
                            <p>$ven_data</p>
                            <h5>Date</h5>
                            <p>$start_data</p>
                            <div class="mb-3 my-3">To</div>
                            <p>$end_data</p>
                            <h5>Price</h5>
                            <p>$price_data</p>
                            $book_btn
                        </div>
                    </div>
                </div>
            data;
        }
        ?>

    </div>
    <div class="col-lg-12 text-center mt-5">
        <a href="events.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More</a>
    </div>
</div>


<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Our location</h2>
<div class="container">
  <div class="row">
    <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
    <iframe class="w-100 rounded" height="320px"  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3771.4755498775085!2d73.0205030759357!3d19.042818053011956!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7c3db5e2c85cd%3A0xef26c52d7d73816e!2sSIES%20Graduate%20School%20of%20Technology!5e0!3m2!1sen!2sin!4v1724434809526!5m2!1sen!2sin"  loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <div class="col-lg-4 col-md-4">
<div class="bg-white p-4 rounded mb-4">
  <h5>Call us</h5>
  <a href="tel: +911234567891"class="d-inline-block mb-2 text-decoration-none text-dark"><i class="bi bi-telephone-fill"></i> +911234567891</a><br>
  <a href="tel: +911234567820"class="d-inline-block mb-2 text-decoration-none text-dark"><i class="bi bi-telephone-fill"></i> +911234567820</a>
</div>
<div class="bg-white p-4 rounded mb-4">
  <h5>Follow us</h5>
  <a href="https://www.youtube.com/"class="d-inline-block mb-3 "><span class="badge bg-light text-dark fs-6 p-2"><i class="bi bi-youtube me-1"></i>Youtube</span></a><br>
  <a href="https://www.instagram.com/"class="d-inline-block mb-3 "><span class="badge bg-light text-dark fs-6 p-2"><i class="bi bi-instagram me-1"></i>Instagram</span></a><br>
</div>
    </div>
  </div>
</div>

<div class="container-fluid">
<div class="row">
  <div class="col-lg-4 p-4">

  </div>
  <div class="col-lg-4 p-4">

  </div>
</div>
</div>

<!-- Password reset -->

<div class="modal fade" id="recoveryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="recovery-form">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center"><i class="bi bi-shield-lock fs-3 me-2"></i>Set New Password</h5>
      </div>
      <div class="modal-body">
      
    

      <div class="mb-4">
       <label class="form-label">New Password</label>
       <input type="password" name="pass" required class="form-control shadow-none" >
       <input type="hidden" name="email">
       <input type="hidden" name="token">

      </div>
      
      <div class="mb-2 text-end">
        <button type="submit" class="btn btn-dark shadow-none">Submit</button>
        <button type="button" class="btn shadow-none  me-2" data-bs-dismiss="modal">
              Cancel
        </button>
        </div>
      
      
      
      </div>
      </form>
      
    </div>
  </div>
</div>


<br><br><br>
<br><br><br>

<?php 

require('inc\footer.php');

if(isset($_GET['account_recovery']))
{
  $data = filteration($_GET);
  $t_date = date("Y-m-d");

  $query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire`=? LIMIT 1",[$data['email'],$data['token'],$t_date],'sss');
 if(mysqli_num_rows($query)==1)
{
   echo<<<showModal
      <script>
        let myModal = document.getElementById('recoveryModal');
        myModal.querySelector("input[name='email']").value = '$data[email]';
        myModal.querySelector("input[name='token']").value = '$data[token]';

        let modal = bootstrap.Modal.getOrCreateInstance(myModal);
        modal.show();
      </script>


   showModal;
}
else
{
  alerta("error","Invalid Link or Expired link");
}

}

?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>

    var swiper = new Swiper(".swiper-container", {
      spaceBetween: 30,
      centeredSlides: true,
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
      
      
    });
    
    //pasword recovery

    document.addEventListener('DOMContentLoaded', function() {
    let recovery_form = document.getElementById('recovery-form');
    let myModal = document.getElementById('recoveryModal');

    if (!myModal) {
        console.error('Modal element with ID "recoveryModal" not found');
        return;
    }

    // Initialize the Bootstrap modal instance
    let modal = new bootstrap.Modal(myModal);
    

    recovery_form.addEventListener('submit', function(e) {
        e.preventDefault();

        let data = new FormData();
        data.append('email', recovery_form.elements['email'].value);
        data.append('token', recovery_form.elements['token'].value);
        data.append('pass', recovery_form.elements['pass'].value);
        data.append('recover_user', '');

        // Hide the modal
        if (modal) {
            modal.hide();
        } else {
            console.error('Bootstrap Modal instance not found');
        }

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/login_register.php", true);

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                let response = xhr.responseText;
                
                switch (response) {
                    case 'failed':
                        showAlert('error', 'Account reset failed');
                        setTimeout(function() {
                            window.location.href = 'index.php'; // Redirect to index.php
                        }, 3000);
                        break;
                    default:
                        showAlert('success', 'Account reset successful');
                        setTimeout(function() {
                            window.location.href = 'index.php'; // Redirect to index.php
                        }, 3000);
                        break;
                }
                        
            } else {
                showAlert('error', 'An error occurred: ' + xhr.statusText);
            }
        };

        xhr.onerror = function() {
            showAlert('error', 'Request failed');
        };

        xhr.send(data);
    });
});

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>