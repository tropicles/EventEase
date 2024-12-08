
<?php
require('inc/links.php');

?>

<h6 class="text-center bg-dark text-white p-3  m-0">2024 Event Handler, Inc. All rights reserved.</h6>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function setActive()
        {
                let navbar= document.getElementById('nav-bar');
                let a_tags = navbar.getElementsByTagName('a');
                for(i=0;i<a_tags.length;i++){
                    let file = a_tags[i].href.split('/').pop();
                    let file_name = file.split('.')[0];
                    if(document.location.href.indexOf(file_name)>=0)
                    {
                        a_tags[i].classList.add('active');
                    }
                }
        }


        /*
        let register_form = document.getElementById('register-form');
        register_form.addEventListener('submit',(e)=>{
            e.preventDefault();
            let data = new FormData();

            data.append('name',register_form.elements['name'].value);
            data.append('email',register_form.elements['email'].value);
            data.append('phonenum',register_form.elements['phonenum'].value);
            data.append('address',register_form.elements['address'].value);
            data.append('pass',register_form.elements['pass'].value);
            data.append('cpass',register_form.elements['cpass'].value);
            data.append('register','');
            var myModal = document.getElementById('registerModal');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            let xhr = new XMLHttpRequest();
            xhr.open("POST","ajax/login_register.php", true);
            
            xhr.onload =function()
            {
            if(this.responseText == 'pass_mismatch')
            {
                showAlert('error','password not matched');
            }

            else if(this.responseText == 'email_already'){

                showAlert('error','Email already used');


            }

            else if(this.responseText == 'phone_already'){
                showAlert('error','Phone number already used');
            }

            else if(this.responseText == 'mail_failed'){
                showAlert('error','Unable to send verification email');
            }

            else if(this.responseText == 'ins_failed')
            {
                showAlert('error','database error');
            }

            else
            {
                showAlert('success','verification mail sent , registration succesfull');
                register_form.reload();
            }

        



               

            }
            xhr.send(data);
            

        });
*/

document.addEventListener('DOMContentLoaded', function() {
    let register_form = document.getElementById('register-form');

    register_form.addEventListener('submit', function(e) {
        e.preventDefault();

        let data = new FormData();
        data.append('name', register_form.elements['name'].value);
        data.append('email', register_form.elements['email'].value);
        data.append('phonenum', register_form.elements['phonenum'].value);
        data.append('address', register_form.elements['address'].value);
        data.append('pass', register_form.elements['pass'].value);
        data.append('cpass', register_form.elements['cpass'].value);
        data.append('register', '');

        let myModal = document.getElementById('registerModal');
        let modal = bootstrap.Modal.getInstance(myModal);
        if (modal) {
            modal.hide();
        }

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/login_register.php", true);

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                let response = xhr.responseText.trim();
                console.log('Server response:', response); // Log response for debugging
                switch (response) {
                    case 'pass_mismatch':
                        showAlert('error', 'Password does not match');
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                        break;
                        
                    case 'email_already':
                        showAlert('error', 'Email already used');
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                        break;
                        
                    case 'phone_already':
                        showAlert('error', 'Phone number already used');
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                        break;
                        
                    case 'mail_failed':
                        showAlert('error', 'Unable to send verification email');
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                        break;
                        
                    case 'ins_failed':
                        showAlert('error', 'Database error');
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                        break;
                        
                    default:
                        showAlert('success', 'Verification mail sent, registration successful');
                        setTimeout(function() {
                            location.reload();
                        }, 3000); // 3000 milliseconds = 3 seconds
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


/*
document.addEventListener('DOMContentLoaded', function() {
    let login_form = document.getElementById('login-form');

    login_form.addEventListener('submit', function(e) {
        e.preventDefault();

        let data = new FormData();
        data.append('email_mob', login_form.elements['email_mob'].value);
        data.append('pass', login_form.elements['pass'].value);
        data.append('login', '');

        let myModal = document.getElementById('loginModal');
        let modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
       

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/login_register.php", true);

        xhr.onload = function() {
           if (xhr.status >= 200 && xhr.status < 300) {
                let response = xhr.responseText;
                switch (response) {
                    case 'inv_email_mob':
                        showAlert('error', 'invalid mobile number/email');
                        break;
                    case 'not_verified':
                        showAlert('error', 'User not verified');
                        break;
                    case 'inactive':
                        showAlert('error', 'User inactive , please contact admin');
                        break;
                    case 'invalid_pass':
                        showAlert('error', 'invalid password');
                        break;
                    default:
                        showAlert('success', 'Logged In succesfully ');
                        // Delay the page reload by 2 seconds
                        setTimeout(function() {
                            location.reload();
                        }, 2000); // 2000 milliseconds = 2 seconds
                        break;
                }
            } else {
                showAlert('error', 'An error occurred: ' + xhr.statusText);
            }
           


        
        };

        

        xhr.send(data);
    });
});
*/

/*
document.addEventListener('DOMContentLoaded', function() {
    let login_form = document.getElementById('login-form');

    login_form.addEventListener('submit', async function(e) {
        e.preventDefault();

        let data = new FormData();
        data.append('email_mob', login_form.elements['email_mob'].value);
        data.append('pass', login_form.elements['pass'].value);
        data.append('login', '');

        let myModal = document.getElementById('loginModal');
        let modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        try {
            let response = await fetch('ajax/login_register.php', {
                method: 'POST',
                body: data
            });

            let result = await response.text();

            switch (result) {
                case 'inv_email_mob':
                    showAlert('error', 'Invalid mobile number/email');
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                    break;
                case 'not_verified':
                    showAlert('error', 'User not verified');
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                    break;
                case 'inactive':
                    showAlert('error', 'User inactive, please contact admin');
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                    break;
                case 'invalid_pass':
                    showAlert('error', 'Invalid password');
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                    break;
                default:
                    showAlert('success', 'Logged in successfully');
                    // Delay the page reload by 2 seconds
                    setTimeout(function() {
                        location.reload();
                    }, 3000); // 2000 milliseconds = 2 seconds
                    break;
            }
        } catch (error) {
            showAlert('error', 'An error occurred: ' + error.message);
        }
    });
});
*/

document.addEventListener('DOMContentLoaded', function() {
    let login_form = document.getElementById('login-form');

    login_form.addEventListener('submit', async function(e) {
        e.preventDefault();

        let data = new FormData();
        data.append('email_mob', login_form.elements['email_mob'].value);
        data.append('pass', login_form.elements['pass'].value);
        data.append('login', '');

        let myModal = document.getElementById('loginModal');
        let modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        try {
            let response = await fetch('ajax/login_register.php', {
                method: 'POST',
                body: data
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            let result = await response.text();
            result = result.trim(); // Trim any extraneous whitespace

            console.log('Server response:', result); // Log the trimmed response for debugging

            switch (result) {
                case 'inv_email_mob':
                    showAlert('error', 'Invalid mobile number/email');
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                    break;
                case 'not_verified':
                    showAlert('error', 'User not verified');
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                    break;
                case 'inactive':
                    showAlert('error', 'User inactive, please contact admin');
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                    break;
                case 'invalid_pass':
                    showAlert('error', 'Invalid password');
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                    break;
                default:
                    showAlert('success', 'Logged in successfully');
                    // Delay the page reload by 3 seconds
                    setTimeout(function() {
                        location.reload();
                    }, 3000); // 3000 milliseconds = 3 seconds
                    break;
            }
        } catch (error) {
            showAlert('error', 'An error occurred: ' + error.message);
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    let forgot_form = document.getElementById('forgot-form');

    forgot_form.addEventListener('submit', function(e) {
        e.preventDefault();

        let data = new FormData();
        data.append('email', forgot_form.elements['email'].value);
        data.append('forgot_pass', '');

        let myModal = document.getElementById('ForgotModal');
        let modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
       

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/login_register.php", true);

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                let response = xhr.responseText;
                
                switch (response) {
                    case 'inv_email':
                        showAlert('error', 'Invalid email');
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                        break;
                    case 'not_verified':
                        showAlert('error', 'User not verified');
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                        break;
                    case 'inactive':
                        showAlert('error', 'User inactive please contact admin');
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                        break;
                    case 'mail_failed':
                        showAlert('error', 'Unable to send email');
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                        break;
                    case 'upd_failed':
                        showAlert('error', 'Failed changing password');
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                        break;
                    default:
                        showAlert('success', 'Password reset link sent');
                        // Delay the page reload by 2 seconds
                        setTimeout(function() {
                            location.reload();
                        }, 3000); // 2000 milliseconds = 2 seconds
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





/*
function showAlert(type, msg) {
    // Determine the Bootstrap class based on the alert type
    var bsClass = (type === "success") ? "alert-success" : "alert-danger";
    
    // Create a new div element for the alert
    var alertDiv = document.createElement('div');
    
    // Set the inner HTML of the div to the alert content
    alertDiv.innerHTML = `
        <div class="alert ${bsClass} alert-dismissible fade show custom-alert" role="alert">
            <strong class="me-3">${msg}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Apply styling to ensure the alert is on top of other content
    alertDiv.style.position = 'fixed';
    alertDiv.style.top = '10px';
    alertDiv.style.right = '10px';
    alertDiv.style.zIndex = '1050'; // Bootstrap default for modals
    
    // Insert the alert at the beginning of the body
    document.body.insertAdjacentElement('afterbegin', alertDiv);
    
    // Optionally, you could add a timeout to remove the alert after a few seconds
    setTimeout(() => {
        alertDiv.querySelector('.alert').classList.remove('show');
        alertDiv.querySelector('.alert').classList.add('fade');
        setTimeout(() => {
            alertDiv.remove();
        }, 150); // Match with Bootstrap fade transition duration
    }, 5000); // Alert will be visible for 5 seconds
}
*/
/*
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
    alertDiv.style.zIndex = '1050'; // Bootstrap default for modals
    
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
*/



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


function checkLoginToBook(status,room_id)
{
 if(status)
 {
    window.location.href='confirm_booking_event.php?id='+room_id;

 }

 else{
    showAlert('error','Please login')
 }
}





function checkLogin(status,room_id)
{
 if(status)
 {
    window.location.href='confirm_booking.php?id='+room_id;
    

 }

 else{
    showAlert('error','Please login')
 }
}

setActive();

</script>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>