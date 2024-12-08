<?php
require('inc/links.php');
include 'session_login.php'
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<nav id="nav-bar" class="navbar navbar-expand-lg navbar-light  px-lg-3 py-lg-2 shadow-sm sticky-top bg-white" >
  <div class="container-fluid">
    <a class="navbar-brand me-5 fw-bold fs-3" href="index.php">Event Handler</a>
    <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link  me-2" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="events.php">Events</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="Host.php">Host</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="about.php">About</a>
        </li>
        
        
      </ul>
      <div class="d-flex">
        <?php
        
        if(isset($_SESSION['login']) && $_SESSION['login']==true)
        {
          

         echo<<<data

            <div class="btn-group">
              <button type="button" class="btn btn-outline-dark shadow-none dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                $_SESSION[uName]
              </button>
              <ul class="dropdown-menu dropdown-menu-lg-end" name="dropdown">
                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                <li><a class="dropdown-item" href="bookings.php">Event Bookings</a></li>
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
              </ul>
            </div>


         data;

        }

        else{
          echo <<<data
          <button type="button" class="btn btn-outline-dark shadow-none me-lg-3 me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
              Login
          </button>
          <button type="button" class="btn btn-outline-dark shadow-none " data-bs-toggle="modal" data-bs-target="#registerModal">
              Register
          </button>

          data;

        }
        
        
        ?>
        
        
      </div>
    </div>
  </div>
</nav>

<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="login-form">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center"><i class="bi bi-person-circle fs-3 me-2"></i> User Login</h5>
        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="mb-3">
       <label class="form-label">Email address or Mobile</label>
       <input type="text" name="email_mob" required class="form-control shadow-none" >
      </div>
      <div class="mb-4">
    <label class="form-label">Password</label>
    <input type="password" required name="pass" class="form-control shadow-none" >
       </div>
      <div class="d-flex align-items-center justify-content-between">
        <button type="submit" class="btn btn-dark shadow-none">LOGIN</button>
        <button type="button" class="btn text-secondary text-decoration-none shadow-none p-0 " data-bs-toggle="modal" data-bs-target="#ForgotModal" data-bs-dismiss="modal">
              Forgot Password?
        </button>      
        </div>
      
      
      
      </div>
      </form>
      
    </div>
  </div>
</div>

<div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="register-form">
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
          <input name="name" type="text" class="form-control shadow-none" required>
          </div>
          <div class="col-md-6 p-0 mb-3">
          <label class="form-label">Email</label>
          <input name="email" type="email" class="form-control shadow-none" required>
          </div>

          <div class="col-md-6 ps-0 mb-3">
              <label class="form-label">Phone Number</label>
              <input 
                  name="phonenum" 
                  type="text" 
                  class="form-control shadow-none" 
                  required
                  maxlength="10" 
                  pattern="\d{10}" 
                  title="Please enter a valid 10-digit phone number"
                  placeholder="Enter 10-digit phone number"
              >
              <small class="form-text text-muted">
                  Please enter exactly 10 digits.
              </small>
          </div>
          
          <div class="col-md-12 ps-0 mb-3">
          <label class="form-label">Address</label>
          <textarea name="address" class="form-control shadow-none" rows="1" required></textarea>
          </div>
          <div class="col-md-6 ps-0 mb-3">
          <label class="form-label">Password</label>
          <input name="pass" type="Password" class="form-control shadow-none" required >
          </div>
          <div class="col-md-6 ps-0 mb-3">
          <label class="form-label">Confirm Password</label>
          <input name="cpass" type="Password" class="form-control shadow-none" required >
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


<div class="modal fade" id="ForgotModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="forgot-form">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center"><i class="bi bi-person-circle fs-3 me-2"></i> Forgot password </h5>
      </div>
      <div class="modal-body">
      
      <span class="badge rounded-pill bg-light text-dark mb-3 text-wrap lh-base">
        Note: A link will be sent to your email for resetting 
      </span>

      <div class="mb-4">
       <label class="form-label">Email address</label>
       <input type="email" name="email" required class="form-control shadow-none" >
      </div>
      
      <div class="mb-2 text-end">
        <button type="submit" class="btn btn-dark shadow-none">Send Link</button>
        <button type="button" class="btn shadow-none p-0 me-2" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">
              Forgot Password?
        </button>
        </div>
      
      
      
      </div>
      </form>
      
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
