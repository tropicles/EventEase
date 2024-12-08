<?php
require('admin\inc\db_config.php');
require('admin\inc\essentials.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <?php require('inc\links.php')?>
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>


</head>
<body class="bg-light">
<?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        require('inc\header.php');
            if(!(isset($_SESSION['login']) && $_SESSION['login']==true))
            {
                redirect('index.php');
            }
        $u_exist=select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",[$_SESSION['uId']],'i');
        if(mysqli_num_rows($u_exist)==0)
        {
            redirect('index.php');
        }
        $u_fetch = mysqli_fetch_assoc($u_exist);


    ?>








    





<div class="container">
  <div class="row">

    <div class="col-12 my-5 px-4">
        <h2 class="fw-bold h-font">Profile</h2>
        <div class="h-line bg-dark"></div>
    </div>
     
   <div class="col-12 mb-5 px-4">
        <div class="bg-white p-3 p-md-4 rounded shadow-sm">
         <form id="info-form">
            <h5 class="mb-3 fw-bold">Basic Information</h5>
             <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Name</label>
                    <input name="name" type="text" value="<?php echo $u_fetch['name']?>"  class="form-control shadow-none" required>
                
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Phone Number</label>
                    <input 
                                name="phonenum" 
                                type="text" 
                                value="<?php echo $u_fetch['phonenum']?>"
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

                <div class="col-md-8 mb-4">

                    <label class="form-label">Address</label>
                    <textarea name="address"   class="form-control shadow-none" rows="4" required><?php echo $u_fetch['address']?></textarea>
                
                </div>
                
             </div>

             <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
         
        </form>

        </div>
    </div>
    
    <div class="col-md-8 mb-5 px-4">
        <div class="bg-white p-3 p-md-4 rounded shadow-sm">
            <form id="pass-form">
                <h5 class="mb-3 fw-bold">Change Password</h5>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">New Password</label>
                        <input name="new_pass" type="password" class="form-control shadow-none" required>
                    
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Confirm Password</label>
                        <input name="confirm_pass" type="password" class="form-control shadow-none" required>
                    
                    </div>

                </div>
                <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>

            </form>
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
<br>

<?php require('inc\footer.php')?>

<script>
    let info_form =document.getElementById('info-form');
    info_form.addEventListener('submit',function(e){
        e.preventDefault();

        let data = new FormData();
        data.append('info_form','');
        data.append('name',info_form.elements['name'].value);
        data.append('phonenum',info_form.elements['phonenum'].value);
        data.append('address',info_form.elements['address'].value);
        
        let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/profile.php", true);




                xhr.onload = function() {
                    if (this.responseText == 'phone_already') {
                        showAlert('error', "Phone number already exists");
                    } else if (this.responseText == 0) {
                        showAlert('error', "No changes made");
                    } else {
                        showAlert('success', 'Changes saved');
                        
                        // Delay before reloading the page
                        setTimeout(function() {
                            location.reload();
                        }, 2000); // Delay of 2000 milliseconds (2 seconds)
                    }
                };

                xhr.send(data);



       
    });

    let pass_form =document.getElementById('pass-form');

    pass_form.addEventListener('submit',function(e){
        e.preventDefault();

       let new_pass = pass_form.elements['new_pass'].value;
       let confirm_pass = pass_form.elements['confirm_pass'].value;

       if(new_pass!=confirm_pass)
       {
        showAlert('Error','Password do not match');
        return false;
       }



        let data = new FormData();
        data.append('pass_form','');
        data.append('new_pass',pass_form.elements['new_pass'].value);
        data.append('confirm_pass',pass_form.elements['confirm_pass'].value);
        
        
        let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/profile.php", true);




                xhr.onload = function() {

                    if (this.responseText == 'mismatch') {
                        showAlert('error', "Password do not match");

                    } else if (this.responseText == 0) {
                        showAlert('error', "Updation failed");
                    } else {
                        showAlert('success', 'Password changed');
                        
                        // Delay before reloading the page
                        setTimeout(function() {
                            location.reload();
                        }, 2000);// Delay of 2000 milliseconds (2 seconds)
                    }
                };

                xhr.send(data);



       
    });


    function showAlert(type, msg) {
    // Determine the Bootstrap class based on the alert type
    var bsClass = (type === "success") ? "alert-success" : "alert-danger";
    
    // Create a new div element for the alert
    var alertDiv = document.createElement('div');
    
    // Set the inner HTML of the div to the alert content
    alertDiv.innerHTML = `
        <div class="alert ${bsClass} alert-dismissible fade show custom-alert" role="alert">
            <strong>${msg}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Apply styling to ensure the alert is on top of other content
    alertDiv.style.position = 'fixed';
    alertDiv.style.top = '10px';
    alertDiv.style.right = '10px';
    alertDiv.style.zIndex = '1060'; // Higher than Bootstrap modals
    
    // Insert the alert at the beginning of the body
    document.body.insertAdjacentElement('afterbegin', alertDiv);
    
    // Optionally, you could add a timeout to remove the alert after a few seconds
    setTimeout(() => {
        var alertElement = alertDiv.querySelector('.alert');
        if (alertElement) {
            alertElement.classList.remove('show');
            alertElement.classList.add('fade');
            setTimeout(() => {
                alertDiv.remove();
            }, 150); // Match with Bootstrap fade transition duration
        }
    }, 5000); // Alert will be visible for 5 seconds
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