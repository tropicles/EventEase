


function get_users(){
        let xhr = new XMLHttpRequest();
          xhr.open("POST","ajax/users.php", true);
          xhr.setRequestHeader('Content-Type' , 'application/x-www-form-urlencoded');
            
           xhr.onload =function()
           {
            document.getElementById('users-data').innerHTML = this.responseText;

             }
       xhr.send('get_users');
    }


function search_user(username)
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/users.php", true);
    xhr.setRequestHeader('Content-Type' , 'application/x-www-form-urlencoded');
      
     xhr.onload =function()
     {
      document.getElementById('users-data').innerHTML = this.responseText;

       }
 xhr.send('search_user&name='+username);  
}


function toggle_status(id, val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/users.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function () {
        if (this.responseText == 1) {
            showAlert('success', 'Status toggled');
            get_users();
        } else {
            showAlert('error', 'Toggle failed');
        }
    };

    // Properly format the data for URL encoding
    let data = `toggle_status=${encodeURIComponent(id)}&value=${encodeURIComponent(val)}`;
    xhr.send(data);
}



     
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



function remove_user(user_id){
  if(confirm("Are you sure?"))
  {
    let data = new FormData();
    data.append('user_id',user_id);
    data.append('remove_user','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/users.php",true);

  xhr.onload =function()
  {
    

    if(this.responseText == 1){
      showAlert('success','user removed!');
      get_users();

    }
    
    else{
      showAlert('error','removal failed');
      
      
    }
  }
  xhr.send(data);

  }
  
  
  
}


window.onload = function()
    {
        
        get_users();
        
        
    }