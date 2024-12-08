<?php
require('admin\inc\db_config.php');
require('admin\inc\essentials.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
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






    

<div class="my-5 px-4">
<h2 class="fw-bold h-font text-center">Events</h2>
<div class="h-line bg-dark"></div>
</div>



<div class="container-fluid">
  <div class="row">

  <div class="col-lg-3 mb-4 col-md-12 mb-lg-0 ps-4 ">
  <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
  <div class="container-fluid flex-lg-column align-items-stretch">
    <h4 class="mt-2">Filters</h4>
    <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">
      <div class="border bg-light p-3 rouned mb-3">
        <h5 class="mb-3" style="font-size:18px;">
          <span>CHECK EVENTS</span>
          <button id="chk_avail_btn" onclick="chk_avail_clear()" class="btn btn-sm text-secondary d-none">Reset</button>


        </h5>

        <label class="form-label " style="font-weight: 500;">Start Date</label>
            <input type="date" class="form-control shadow-none mb-3" id="checkin" onchange="chk_avail_filter()">

        <label class="form-label" style="font-weight: 500;">End Date</label>
            <input type="date" class="form-control shadow-none" id="checkout" onchange="chk_avail_filter()">

    </div>
    


  
    </div>
  </div>
    </nav>
  </div>
   
  

<div class="col-lg-9 col-md-12 px-4">

    <div class="container">
        <div class="row" id="events-data">

        <?php

        

            ?>


            

            
            


                
        </div>
    </div>


</div>


  </div>
</div>

<br>

<br>

<script>
let events_data =document.getElementById('events-data');
let checkin =document.getElementById('checkin');
let checkout =document.getElementById('checkout');
let chk_avail_btn =document.getElementById('chk_avail_btn');

function fetch_events(){
  let chk_avail =JSON.stringify({
    checkin: checkin.value,
    checkout: checkout.value
  });

  let xhr = new XMLHttpRequest();
  xhr.open("GET","ajax/events.php?fetch_events&chk_avail="+chk_avail,true);

  xhr.onload =function(){
    events_data.innerHTML = this.responseText;
  }
  xhr.send();

}


function chk_avail_filter(){
if(checkin.value!='' && checkout.value!=''){
  fetch_events();
  chk_avail_btn.classList.remove('d-none');
}
}

function chk_avail_clear(){
  checkin.value ='';
  checkout.value ='';
  fetch_events();
  chk_avail_btn.classList.add('d-none');

}



fetch_events();
</script>

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