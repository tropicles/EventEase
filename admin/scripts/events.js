

function get_all_rooms(){
        let xhr = new XMLHttpRequest();
          xhr.open("POST","ajax/events.php", true);
          xhr.setRequestHeader('Content-Type' , 'application/x-www-form-urlencoded');
            
           xhr.onload =function()
           {
            document.getElementById('room-data').innerHTML = this.responseText;

             }
       xhr.send('get_all_rooms');
    }
/*
    function toggle_status(id,val){
        let xhr = new XMLHttpRequest();
          xhr.open("POST","ajax/rooms.php", true);
          xhr.setRequestHeader('Content-Type' , 'application/x-www-form-urlencoded');
            
           xhr.onload =function()
           {
             if(this.responseText==1)
             {
                showAlert('success','Status toggled');
             }

             else
             {
                showAlert('Error','toggled failed');
             }

             }
       xhr.send('toggle_status=' + id +'$value='+ val);
    }
*/

let edit_room_form = document.getElementById('edit_room_form');

function edit_details(id)
{
  let xhr = new XMLHttpRequest();
          xhr.open("POST","ajax/events.php", true);
          xhr.setRequestHeader('Content-Type' , 'application/x-www-form-urlencoded');

           xhr.onload =function()
           {
             let data =JSON.parse(this.responseText);
             edit_room_form.elements['name'].value = data.roomdata.event_name ;
             edit_room_form.elements['price'].value = data.roomdata.price ;
             edit_room_form.elements['checkin'].value = data.roomdata.check_in;
             edit_room_form.elements['checkout'].value = data.roomdata.check_out;
             edit_room_form.elements['desc'].value = data.roomdata.event_desc ;
             edit_room_form.elements['booking_id'].value = data.roomdata.booking_id ;
             edit_room_form.elements['room_id'].value = data.roomdata.room_id;
             edit_room_form.elements['current_checkin'].value = data.roomdata.check_in;
             edit_room_form.elements['current_checkout'].value = data.roomdata.check_out;




              }
       xhr.send('get_room='+id);
       
}

function submit_edit_room()
    {
        let data = new FormData();
        data.append('edit_room','');
        data.append('booking_id',edit_room_form.elements['booking_id'].value);
        data.append('room_id',edit_room_form.elements['room_id'].value);
        data.append('name',edit_room_form.elements['name'].value);
        data.append('price',edit_room_form.elements['price'].value);
        data.append('checkin',edit_room_form.elements['checkin'].value);
        data.append('checkout',edit_room_form.elements['checkout'].value);
        data.append('desc',edit_room_form.elements['desc'].value);

        data.append('current_checkin', edit_room_form.elements['current_checkin'].value);
        data.append('current_checkout', edit_room_form.elements['current_checkout'].value);

       

         
         let xhr = new XMLHttpRequest();
          xhr.open("POST","ajax/events.php", true);
            
           xhr.onload =function()
           {
            var myModal = document.getElementById('edit-room');
           var modal = bootstrap.Modal.getInstance(myModal);
           modal.hide();

            if(this.responseText == 1){
            showAlert('success','Event  edited');
            edit_room_form.reset();
            get_all_rooms();
            

            
            }

          else{
            showAlert('Error' , 'Failed Check dates if clashing or an SQL error');
            
          }
    
    
    }
       xhr.send(data);
     }

edit_room_form.addEventListener('submit',function(e)
    {
        e.preventDefault();
        submit_edit_room();
    });








let add_image_form = document.getElementById('add_image_form');

function edit_image(id)
{
  let xhr = new XMLHttpRequest();
          xhr.open("POST","ajax/events.php", true);
          xhr.setRequestHeader('Content-Type' , 'application/x-www-form-urlencoded');

           xhr.onload =function()
           {
             let data =JSON.parse(this.responseText);
             
             add_image_form.elements['booking_id'].value = data.roomdata.booking_id ;
             add_image_form.elements['room_id'].value = data.roomdata.room_id;
             add_image_form.elements['image'].files[0] = data.roomdata.event_image;
             





              }
       xhr.send('get_event='+id);
       
}


add_image_form.addEventListener('submit',function(e)
{
  e.preventDefault();
  add_image();
});

function add_image(){
  let data = new FormData();
  data.append('image',add_image_form.elements['image'].files[0]);

  data.append('room_id',add_image_form.elements['room_id'].value);
  data.append('booking_id',add_image_form.elements['booking_id'].value);

  data.append('add_image','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/events.php",true);

  xhr.onload =function()
  {
    var myModal = document.getElementById('room-images');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    if(this.responseText == 'inv_img'){
      showAlert('error','Only jpg and png allowed');

    }
    else if(this.responseText == 'inv_size')
    {
      showAlert('error' , 'image should be less than 2MB!');

    }
    else if (this.responseText == 'upd_failed'){
      showAlert('error' , 'image upload failed');
    }
    else{
      showAlert('success','New image added');
      add_image_form.reset();
      
    }
  }
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








window.onload = function()
    {
        
        get_all_rooms();
        
        
    }