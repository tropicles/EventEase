<?php
require('admin\inc\db_config.php');
require('admin\inc\essentials.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book event</title>
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
    redirect('Host.php');
 }
 else if(!(isset($_SESSION['login']) && $_SESSION['login']==true))
 {
    redirect('Host.php');
 }



 $data = filteration($_GET);
 $room_res = select("SELECT * FROM `rooms` WHERE `id`=? AND `status`=? AND `removed`=?",[$data['id'],1,0],'iii');
 if(mysqli_num_rows($room_res)==0)
 {
    redirect('Host.php');
 }
 $room_data = mysqli_fetch_assoc($room_res);

 $_SESSION['room'] = [
    "id" => $room_data['id'],
    "name" => $room_data['name'],
    "available" => false,
 ];

 $user_res = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",
 [$_SESSION['uId']] ,'i');
 $user_data = mysqli_fetch_assoc($user_res);


 
 

?>






    





<div class="container">
  <div class="row">

    <div class="col-12 my-5 px-0">
        <h2 class="fw-bold h-font">Enter Event details</h2>
        <br>
        <h2 class="h-font">Now Booking <?php echo $room_data['name']?></h2>
        <div class="h-line bg-dark"></div>
    </div>

 
   
  

<div class="col-lg-9 col-md-12 px-0">
   <div class="card mb-4 border-0 shadow-sm rounded-3">
          <div class="card-body" >
            <form action="#" id="booking_form">
                <h6>Event Details</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input name="name" type="text" class="form-control shadow-none" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Price</label>
                        <input name="price" type="number" class="form-control shadow-none" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="desc" class="form-control shadow-none" rows="1" required></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Event Image</label>
                        <input name="event_image" type="file" accept=".jpg, .jpeg, .png, .webp" class="form-control shadow-none" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start</label>
                        <input name="checkin" type="date" class="form-control shadow-none" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">End</label>
                        <input name="checkout" type="date" class="form-control shadow-none" required>
                    </div>
                    
                    <div class="col-12">
                    <h6 class="mb-3 text-danger" id="pay_info">Provide Start and End date</h6>
                    <button name="pay_now" class="btn w-100 text-white custom-bg">Book Now</button>
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



<?php require('inc\footer.php')?>

<script>
 let booking_form =document.getElementById('booking_form');
 let pay_info = document.getElementById('pay_info');

 booking_form.addEventListener('submit',function(e)
{
    e.preventDefault();
    check_availability();
}
)


 function check_availability()
 {
   let checkin_val =booking_form.elements['checkin'].value;
   let checkout_val =booking_form.elements['checkout'].value;
   
   if(checkin_val!='' && checkout_val!='')
   {
    pay_info.classList.add('d-none');
    let data = new FormData();
   data.append('name',booking_form.elements['name'].value);
   data.append('price',booking_form.elements['price'].value);
   data.append('desc',booking_form.elements['desc'].value);
   data.append('event_image',booking_form.elements['event_image'].files[0]);
    data.append('check_in',checkin_val);
    data.append('check_out',checkout_val);
    data.append('check_availability','');

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/booking_event.php",true);
    
    xhr.onload = function()
    {
        let data = JSON.parse(this.responseText);
        if(data.status == "check_in_out_equal")
        {
            pay_info.innerText = "You cannot have have same day end date";
        }
        else if(data.status == "check_out_earlier")
        {
            pay_info.innerText = "End date is earlier than Start date";
        }
        else if(data.status == "check_in_earlier")
        {
            pay_info.innerText = "Start date is earlier than End date";
        }
        else if(data.status == "unavailable")
        {
            pay_info.innerText = "Place not available for given date";
        }
        else if (this.responseText == 'ins_failed') {
            showAlert('error', 'Booking failed');
            setTimeout(function() {
                window.location.reload();
            }, 2000); // 2000 milliseconds = 2 seconds
        }
        else {
                pay_info.innerHTML = "No of Days: " + data.days;
                pay_info.classList.replace('text-danger', 'text-dark');
                showAlert('success', 'Booking successful Please contact admin for approval');
                
                // Redirect to host.php after 2 seconds
                setTimeout(function() {
                    window.location.href = 'host.php'; // Redirect to host.php
                }, 2000); // 2000 milliseconds = 2 seconds
            }
        pay_info.classList.remove('d-none');




    }
    xhr.send(data);

   
   }


   
 }

</script>

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