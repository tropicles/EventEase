<?php
require('admin\inc\db_config.php');
require('admin\inc\essentials.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking history</title>
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
if(!(isset($_SESSION['login']) && $_SESSION['login']==true))
 {
    redirect('index.php');
 }
    ?>








    





<div class="container">
  <div class="row">

    <div class="col-12 my-5 px-4">
        <h2 class="fw-bold h-font">Check event receipts</h2>
        <div class="h-line bg-dark"></div>
    </div>
     <?php

          $query = "SELECT 
          bo.booking_id, 
          bo.order_id, 
          bo.trans_amt, 
          bo.booking_status, 
          bo.arrival, 
          bd.event_name, 
          bd.phonenum, 
          bd.user_name, 
          bd.price, 
          bo.datentime, 
          bo.check_in, 
          bo.check_out
        FROM 
          booking_order bo
        INNER JOIN 
          booking_details bd 
        ON 
          bd.sr_no = bo.booking_details_id
        WHERE 
          ((bo.booking_status = 'Credit')
          OR (bo.booking_status = 'Failed'))
          AND(bo.user_id=?)
        ORDER BY 
          bo.booking_id DESC";

          $result = select($query,[$_SESSION['uId']],'i');

          while($data=mysqli_fetch_assoc($result))
          {
            $date = date("d-m-Y", strtotime($data['datentime']));
            $checkin = date("d-m-Y", strtotime($data['check_in']));
            $checkout = date("d-m-Y", strtotime($data['check_out']));

            $status_bg="";
            $btn = "";
            
            if($data['booking_status']=='Credit')
            {
                $status_bg = "bg-success";
                
                if($data['arrival']==1)
                {
                    $btn ="<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm  shadow-none'>
                    Download PDF
                </a>";

                }
                
            }

            else{
                $status_bg = "bg-danger";
                $btn ="<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm  shadow-none'>
                    Download PDF
                </a>";
                

            }

            echo<<<bookings
                 <div class='col-md-4 px-4 mb-4'>
                     <div class='bg-white p-3 rounded shadow-sm'>
                      <h5 class='fw-bold'>$data[event_name]</h5>
                      <p>₹$data[price]</p>
                      <p>
                        <b>Start date: </b> $checkin <br>
                        <b>End Date: </b> $checkout
                      </p>

                      <p>
                        <b>Amount: </b> $data[trans_amt] <br>
                        <b>Order ID: </b> $data[order_id]<br>
                        <b>Date of booking: </b> $date
                      </p>

                      <p>
                       <span class='badge $status_bg'>$data[booking_status]</span>
                       
                      </p>
                      <p>
                      $btn
                      </p>


                     </div>
                     
                 </div>
            bookings;

          }



     ?>
 
   
  




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