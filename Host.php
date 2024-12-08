<?php
require('admin\inc\db_config.php');
require('admin\inc\essentials.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Host</title>
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

            $checkin_default = "";
            $checkout_default = "";

        if(isset($_GET['check_availability']))
        {
          $frm_data = filteration($_GET);
          $checkin_default = $frm_data['checkin'];
          $checkout_default = $frm_data['checkout'];

        }
    ?>






    

<div class="my-5 px-4">
<h2 class="fw-bold h-font text-center">Host An Event</h2>
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
          
          <span>CHECK AVAILIBILITY</span>
          <button id="chk_avail_btn" onclick="chk_avail_clear()" class="align-items-center shadow-none justify-content-between btn btn-sm text-secondary d-none">Reset</button>
        
        </h5>
        <label class="form-label " style="font-weight: 500;">Start Date</label>
            <input type="date" class="form-control shadow-none mb-3" value="<?php echo $checkin_default ?>" id="checkin" onchange="chk_avail_filter()">
        <label class="form-label" style="font-weight: 500;">End Date</label>
            <input type="date" class="form-control shadow-none" value="<?php echo $checkout_default ?>" id="checkout" onchange="chk_avail_filter()">
    </div>

       


    <div class="mb-3">

          <h5 class="mb-3" style="font-size:18px;">
                
                <span>Capacity</span>
                <button id="guests_btn" onclick="guests_clear()" class="align-items-center shadow-none justify-content-between btn btn-sm text-secondary d-none">Reset</button>
              
          </h5>

          <label for="capacity" class="shadow-none form-label">Select Event Capacity:</label>
          <input type="number" oninput="guests_filter()" id="capacity" min="1" class="form-control shadow-none" >
          
      </div>


  
    </div>
  </div>
    </nav>
  </div>
   
  

<div class="col-lg-9 col-md-12 px-4" id="rooms-data">

<?php
   
  ?>


</div>


  </div>
</div>

<br>

<br>

<script>

let room_data = document.getElementById('rooms-data');
let checkin =document.getElementById('checkin');
let checkout =document.getElementById('checkout');
let chk_avail_btn =document.getElementById('chk_avail_btn');


let capacity =document.getElementById('capacity');
let guests_btn =document.getElementById('guests_btn');




function fetch_rooms()
{
  let chk_avail =JSON.stringify({
    checkin: checkin.value,
    checkout: checkout.value
  });
   let guests =JSON.stringify({
     capacity:capacity.value
   })

  let xhr = new XMLHttpRequest();
  xhr.open("GET","ajax/rooms.php?fetch_rooms&chk_avail="+chk_avail+"&guests="+guests,true);

  xhr.onprogress =function(){
     

  }

  xhr.onload =function(){
     
    room_data.innerHTML = this.responseText;
  }
  xhr.send();
}

function chk_avail_filter(){

  if(checkin.value!='' && checkout.value!=''){
    fetch_rooms();
    chk_avail_btn.classList.remove('d-none');
  }

}

function chk_avail_clear(){

checkin.value ='' ;
checkout.value ='';
chk_avail_btn.classList.add('d-none');
fetch_rooms();
  


}


function guests_filter(){

  if(capacity.value > 0){
    fetch_rooms();
    guests_btn.classList.remove('d-none');

  }
 
}

function guests_clear(){

capacity.value = '';
fetch_rooms();
guests_btn.classList.add('d-none');


}
window.onload =function(){
fetch_rooms();
}
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