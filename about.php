<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <?php require('inc\links.php')?>
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>


</head>

<?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        require('inc\header.php');
    ?>

    
<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form >
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center"><i class="bi bi-person-circle fs-3 me-2"></i> User Login</h5>
        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="mb-3">
       <label class="form-label">Email address</label>
       <input type="email" class="form-control shadow-none" >
      </div>
      <div class="mb-4">
    <label class="form-label">Password</label>
    <input type="password" class="form-control shadow-none" >
       </div>
      <div class="d-flex align-items-center justify-content-between">
        <button type="submit" class="btn btn-dark shadow-none">LOGIN</button>
         <a href="javascript: void(0)" class="text-seconday text-decoration-none">Forgot Password?</a>
      </div>
      
      
      
      </div>
      </form>
      
    </div>
  </div>
</div>

<div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form >
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center"><i class="bi bi-person-lines-fill fs-3 me-2"></i> User Registration</h5>
        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <span class="badge rounded-pill bg-light text-dark mb-3 text-wrap lh-base">
        Note: It is better to use college Email
      </span>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 ps-0 mb-3">
          <label class="form-label">Name</label>
          <input type="text" class="form-control shadow-none" >
          </div>
          <div class="col-md-6 p-0 mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control shadow-none" >
          </div>
          <div class="col-md-6 ps-0 mb-3">
          <label class="form-label">Phone Number</label>
          <input type="number" class="form-control shadow-none" >
          </div>
          <div class="col-md-12 ps-0 mb-3">
          <label class="form-label">Address</label>
          <textarea class="form-control shadow-none" rows="1"></textarea>
          </div>
          <div class="col-md-6 ps-0 mb-3">
          <label class="form-label">Password</label>
          <input type="Password" class="form-control shadow-none" >
          </div>
          <div class="col-md-6 ps-0 mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="Password" class="form-control shadow-none" >
          </div>
        </div>
      </div>
      <div class="text-center my-1">
      <button type="submit" class="btn btn-dark shadow-none">REGISTER</button>

      </div>
      
      
      </div>
      </form>
    </div>
  </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    

<div class="my-5 px-4">
<h2 class="fw-bold h-font text-center">ABOUT US</h2>
<div class="h-line bg-dark"></div>
<p class="text-center mt-3">
Welcome to Event Manager, the ultimate platform designed to simplify the process of hosting and joining events. <br>
Our mission is to bridge the gap between event organizers and attendees by offering a user-friendly platform that handles all the details,<br> 
so you can focus on what truly matters—creating memorable experiences.
</p>
</div>

<div class="container">
    <div class="row justify-content-between align-items-center">
        <div class="col-lg-6 col-md-5 mb-4">
            <h3 class="mb-3">About the Creater</h3>
            <p>
            Gaurav is a third-year student with a passion for technology and event management, and the innovative mind behind Event Manager. 
            Combining his academic background with a keen interest in creating impactful solutions, 
            Gaurav embarked on developing this platform to streamline the process of hosting and participating in events.
            </p>
        </div>
        <div class="col-lg-5 col-md-5 mb-4">
            <img src="images\about\about.png"class="w-100">

        </div>
    </div>

    <div class="col-lg-5 col-md-5 mb-4">

    </div>
</div>

<div class="container mt-5">
  <div class="row">
    <div class="col-lg-4 col-md-6 mb-4 px-4">
<div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
  <img src="images\about\customers.svg" >
</div>
    </div>
    <div class="col-lg-4 col-md-6 mb-4 px-4">
<div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
  <img src="images\about\rating.svg" >
  
</div>
    </div>
    <div class="col-lg-4 col-md-6 mb-4 px-4">
<div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
  <img src="images\about\staff.svg" >
</div>
    </div>
  </div>
</div>

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