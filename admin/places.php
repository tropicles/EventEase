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
    <title>Places</title>
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
            <h3 class="mb-4">Places</h3>
              <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">

        <div class="text-end mb-3">
            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-room">
             <i class="bi bi-plus-square"></i> ADD
             </button>
        </div>
                    <div class="table-responsive-lg " style="height: 450px; overflow-y: scroll;">
                    <table class="table table-hover border text-center">
                        <thead class="sticky-top">
    <tr class="bg-dark text-light">
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Capacity</th>
      <th scope="col">Status</th>
      <th scope="col">Action</th>
    </tr>
                        </thead>

                        <tbody id="room-data">
                          
                        </tbody>
                    </table>
                    </div>

                </div>
              </div>

        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="add-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="add_room_form" autocomplete="off"> 
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Add Place</h5>
      </div>
      <div class="modal-body">
        <div class="row">

        <div class=" col-md-6 mb-3">
            <label class="form-label fw-bold">Name</label>
            <input type="text" name="name"  class="form control shadow-none" required>
        </div>
         
        

        <div class=" col-md-6 mb-3">
            <label class="form-label fw-bold">Capacity (Max.)</label>
            <input type="number" name="capacity" min="10" class="form control shadow-none" required>
        
        
        </div>
        
        <div class="col-12 mb-3">
        <label class="form-label fw-bold">Features</label>
          <div class="row">
            <?php
            $res = selectAll('features');
            while($opt = mysqli_fetch_assoc($res)){
              echo "
              <div class='col-md-3 mb-1'>
                <label>
                   <input type='checkbox' name='features' value='$opt[id]' class='form-check-input shadow-none'>
                    $opt[name]

                <label>
               </div>
              ";
            }
            ?>
          </div>
        </div>
          <div class="col-12 mb-3">
            <label class="form-label fw-bold">Description</label>
            <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
          </div>
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

<!-- Edit Modal -->
<div class="modal fade" id="edit-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="edit_room_form" autocomplete="off"> 
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Edit Place</h5>
      </div>
      <div class="modal-body">
        <div class="row">

        <div class=" col-md-6 mb-3">
            <label class="form-label fw-bold">Name</label>
            <input type="text" name="name"  class="form control shadow-none" required>
        </div>
         
        

        <div class=" col-md-6 mb-3">
            <label class="form-label fw-bold">Capacity (Max.)</label>
            <input type="number" name="capacity" min="10" class="form control shadow-none" required>
        
        
        </div>
        
        <div class="col-12 mb-3">
        <label class="form-label fw-bold">Features</label>
          <div class="row">
            <?php
            $res = selectAll('features');
            while($opt = mysqli_fetch_assoc($res)){
              echo "
              <div class='col-md-3 mb-1'>
                <label>
                   <input type='checkbox' name='features' value='$opt[id]' class='form-check-input shadow-none'>
                    $opt[name]

                <label>
               </div>
              ";
            }
            ?>
          </div>
        </div>

          <div class="col-12 mb-3">
            <label class="form-label fw-bold">Description</label>
            <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
          </div>

          <input type="hidden" name="room_id">
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

<!-- Image Modal -->
<div class="modal fade" id="room-images" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Place Name</h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        <div class="border-bottom border-3 pb-3 mb-3">
          <form id="add_image_form">
            <label class="form-label fw-bold">Add Image</label>
            <input type="file" name="image" accept=".jpg, .png, .jpeg, .webp" class="form-control shadow-none mb-3" required>
            <button type="submit" class="btn custom-bg text-white shadow-none">ADD</button>
            <input type="hidden" name="room_id">

          </form>
          <div class="table-responsive-lg " style="height: 350px; overflow-y: scroll;">
                <table class="table table-hover border text-center">
                      <thead class="sticky-top">
                        <tr class="bg-dark text-light sticky-top">
                          <th scope="col" width="60%">Image</th>
                          <th scope="col">Thumb</th>
                          <th scope="col">Delete</th>
                        </tr>
                      </thead>

                        <tbody id="room-image-data">
                          
                        </tbody>
                  </table>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>


<?php require('inc/script.php');?>  


<script src="scripts/places.js">
    
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>