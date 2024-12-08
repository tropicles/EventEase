<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Features</title>
    <?php
    require('inc/links.php');
    ?>
</head>
<body class="bg-light">

<div class="container-fluid bg-dark text-light p-3 d-flex align-items-center justify-content-between sticky-top">
    <h3 class="mb-0 h-font">ADMIN PANEL</h3>
    <a href="logout.php" class="btn btn-light btn-sm">LOG OUT</a>
</div>

<?php
require('inc/header.php');

?>


<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto p-4 overflow-hidden">
            <h3 class="mb-4">Features</h3>
              <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="card-title m-0">Features</h5>
            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#feature-s">
             <i class="bi bi-plus-square"></i> ADD
             </button>
                  </div>
                    <div class="table-responsive-md " style="height: 350px; overflow-y: scroll;">
                    <table class="table table-hover border">
                        <thead class="sticky-top">
    <tr class="bg-dark text-light">
      <th scope="col">#</th>
      <th scope="col">Name</th>
      
      <th scope="col">Action</th>
    </tr>
                        </thead>

                        <tbody id="features-data">
                          
                        </tbody>
                    </table>
                    </div>

                </div>
              </div>

        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="feature-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="feature_s_form"> 
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Add Feature</h5>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label class="form-label fw-bold">Name</label>
            <input type="text" name="feature_name"  class="form control shadow-none" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="reset" class="btn text-secondary" data-bs-dismiss="modal">CANCEL</button>
        <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
      </div>
    </div>
    </form>
    
  </div>
</div>

<?php require('inc/script.php');?>  


<script>
    
    function add_feature()
{
    let data = new FormData();
    data.append('name',feature_s_form.elements['feature_name'].value);
    data.append('add_feature','');

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/feature.php", true);

    xhr.onload =function()
    {
        var myModal = document.getElementById('feature-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
        if(this.responseText == 1){
            showAlert('success','New feature added');
            feature_s_form.elements['feature_name'].value = '';
            get_features();
        }

        else{
            showAlert('Error' , 'Failed');
            
        }
    
    
    }
    xhr.send(data);

}
    let feature_s_form = document.getElementById('feature_s_form');
    feature_s_form.addEventListener('submit',function(e)
{
    e.preventDefault();
    add_feature();

})
     
     function get_features()
     {
        let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/feature.php",true);
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.onload =function(){
            document.getElementById('features-data').innerHTML = this.responseText;

        }
        xhr.send('get_features');
     }
     /*
     function rem_feature(val)
     {
        let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/feature.php",true);
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

        xhr.onload = function(){
            if(this.responseText==1){
              showAlert('success','Feature Removed');
               get_features();
            }
            else
            {
              showAlert('error','Error occured')
            }

            xhr.send('rem_feature='+val);
        }

     }
       */

       

function rem_feature(id) {
    if (confirm('Are you sure you want to delete this item?')) {
        $.ajax({
            url: 'delete.php', // Path to your PHP script that handles deletion
            type: 'POST',
            data: {
                rem_feature: id // Send the ID to the PHP script
            },
            success: function(response) {
                // Handle the response from the PHP script
                if (response == 1) {
                    alert('Item deleted successfully.');
                    // Optionally, you could remove the row from the UI or reload the table
                    // For example, find the row by ID and remove it:
                    $('button[onclick="rem_feature(' + id + ')"]').closest('tr').remove();
                } else {
                    alert('Delete failed or item not found.');
                }
            },
            error: function(xhr, status, error) {
                // Handle any errors
                console.error('AJAX Error:', status, error);
            }
        });
    }
}


     window.onload = function()
     {
        
        get_features();
        
        
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


</script>

</body>
</html>