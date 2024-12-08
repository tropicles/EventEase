<?php
require('admin\inc\db_config.php');
require('admin\inc\essentials.php');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Instamojo Payment Gateway Integrate in PHP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <style>
        .mt40{
            margin-top: 40px;
        }
    </style>
    <?php require('inc\links.php')?>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
</head>
<body>

<?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        require('inc\header.php');
?>



<?php 
 if(!isset($_GET['id']))
 {
    redirect('events.php');
 }
 else if(!(isset($_SESSION['login']) && $_SESSION['login']==true))
 {
    redirect('events.php');
 }



 $data = filteration($_GET);

 $room_res = select("SELECT * FROM `booking_event` WHERE `booking_id`=?",[$data['id']],'i');

 if(mysqli_num_rows($room_res)==0)
 {
    redirect('events.php');
 }

 $room_data = mysqli_fetch_assoc($room_res);

 $_SESSION['room'] = [
    "id" => $room_data['booking_id'],
    "name" => $room_data['event_name'],
    "price" => $room_data['price'],
    "checkin" => $room_data['check_in'],
    "checkout" => $room_data['check_out'],
    "payment" => null,
 ];

 $user_res = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",
 [$_SESSION['uId']] ,'i');
 $user_data = mysqli_fetch_assoc($user_res);
 
  
 
 

?>

<div class="container">
 
<div class="row">

    <div class="col-12 my-5 px-0">
        <h2 class="h-font">Now Booking <?php echo $room_data['event_name']?></h2>
        <div class="h-line bg-dark"></div>
    </div>



 
    <!--Prev 
<div class="col-lg-9 col-md-12 px-0">   
<form action="payment-proccess.php" method="POST" name="instamojo_payment">
   
     <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <strong>Name</strong>
                <input type="text" name="name" class="form-control" placeholder="Enter Name" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <strong>Mobile Number</strong>
                <input type="text" name="mobile_number" class="form-control" placeholder="Enter Mobile Number" maxlength="10" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <strong>Email Id</strong>
                <input type="text" name="email" class="form-control" placeholder="Enter Email id" maxlength="50" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <strong>Event Fees</strong>
                <input type="text" name="amount" class="form-control" placeholder="" value="100" readonly="">
            </div>
        </div>
        <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
    
</form>
</div>
--> 



<div class="col-lg-9 col-md-12 px-0">
   <div class="card mb-4 border-0 shadow-sm rounded-3">
          <div class="card-body" >
            <form action="payment-proccess.php" method="POST" name="instamojo_payment" id="booking_form">
            <input type="hidden" name="id" value="<?php echo $user_data['id']; ?>">
            <input type="hidden" name="rid" value="<?php echo $room_data['booking_id']; ?>">
             <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input name="name"  value="<?php echo $user_data['name']?>" type="text" class="form-control shadow-none" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Number</label>
                        <input 
                            name="mobile_number"  
                            value="<?php echo htmlspecialchars($user_data['phonenum']); ?>" 
                            type="text" 
                            class="form-control shadow-none" 
                            required 
                            pattern="\d{10}" 
                            maxlength="10" 
                            title="Please enter a valid 10-digit phone number"
                            placeholder="Enter 10-digit phone number"
                        >
                        <small class="form-text text-muted">
                            Please enter exactly 10 digits.
                        </small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Email</label>
                        <input name="email"  value="<?php echo $user_data['email']?>" type="text" class="form-control shadow-none" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Price</label>
                        <input type="text" name="amount" class="form-control" placeholder="" value= "<?php echo $room_data['price']?>" readonly="">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Event</label>
                        <input type="text" name="ename" class="form-control" placeholder="" value= "<?php echo $room_data['event_name']?>" readonly="">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Date</label>
                        <input name="checkin" value= "<?php echo $room_data['check_in']?>" type="date" class="form-control shadow-none"  readonly="">
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">End Date</label>
                        <input name="checkout" value= "<?php echo $room_data['check_out']?>" type="date" class="form-control shadow-none"  readonly="">
                    </div>
                    
                    <div class="col-12">
                    <button name="pay_now" type="submit" class="btn w-100 text-white custom-bg">Book Now</button>
                    </div>
                </div>
            </form>
          </div>

   </div>
    


</div>
  

</div>
</div>

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>





<?php require('inc\footer.php')?>  



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
    var swiper = new Swiper(".swiper-container", {
      spaceBetween: 30,
      centeredSlides: true,
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
      
      
    });
  </script>

</body>
</html>