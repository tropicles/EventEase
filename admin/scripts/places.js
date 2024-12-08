let add_room_form = document.getElementById('add_room_form');
    add_room_form.addEventListener('submit',function(e)
    {
        e.preventDefault();
        add_room();
    });
    function add_room()
    {
        let data = new FormData();
        data.append('add_room','');
        data.append('name',add_room_form.elements['name'].value);
        data.append('capacity',add_room_form.elements['capacity'].value);
        data.append('desc',add_room_form.elements['desc'].value);

        let features = [];
        add_room_form.elements['features'].forEach(el => {
            if(el.checked){
                features.push(el.value);
            }
        });

        data.append('features',JSON.stringify(features));

         
         let xhr = new XMLHttpRequest();
          xhr.open("POST","ajax/rooms.php", true);
            
           xhr.onload =function()
           {
            var myModal = document.getElementById('add-room');
           var modal = bootstrap.Modal.getInstance(myModal);
           modal.hide();
             if(this.responseText == 1){
            showAlert('success','New room added');
            add_room_form.reset();
            get_all_rooms();
            
            }

          else{
            showAlert('Error' , 'Failed');
            
          }
    
    
    }
       xhr.send(data);
     }

function get_all_rooms(){
        let xhr = new XMLHttpRequest();
          xhr.open("POST","ajax/rooms.php", true);
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
          xhr.open("POST","ajax/rooms.php", true);
          xhr.setRequestHeader('Content-Type' , 'application/x-www-form-urlencoded');

           xhr.onload =function()
           {
             let data =JSON.parse(this.responseText);
             edit_room_form.elements['name'].value = data.roomdata.name ;
             edit_room_form.elements['capacity'].value = data.roomdata.capacity ;
             edit_room_form.elements['desc'].value = data.roomdata.description ;
             edit_room_form.elements['room_id'].value = data.roomdata.id ;

             edit_room_form.elements['features'].forEach(el =>{
              if(data.features.includes(Number(el.value)))
             {
              el.checked = true;
             }
             });


              }
       xhr.send('get_room='+id);
       
}

function submit_edit_room()
    {
        let data = new FormData();
        data.append('edit_room','');
        data.append('room_id',edit_room_form.elements['room_id'].value);
        data.append('name',edit_room_form.elements['name'].value);
        data.append('capacity',edit_room_form.elements['capacity'].value);
        data.append('desc',edit_room_form.elements['desc'].value);

        let features = [];
        edit_room_form.elements['features'].forEach(el => {
            if(el.checked){
                features.push(el.value);
            }
        });

        data.append('features',JSON.stringify(features));

         
         let xhr = new XMLHttpRequest();
          xhr.open("POST","ajax/rooms.php", true);
            
           xhr.onload =function()
           {
            var myModal = document.getElementById('edit-room');
           var modal = bootstrap.Modal.getInstance(myModal);
           modal.hide();
             if(this.responseText == 1){
            showAlert('success','room edited');
            edit_room_form.reset();
            get_all_rooms();
            

            
            }

          else{
            showAlert('Error' , 'Failed');
            
          }
    
    
    }
       xhr.send(data);
     }

edit_room_form.addEventListener('submit',function(e)
    {
        e.preventDefault();
        submit_edit_room();
    });

function toggle_status(id, val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function () {
        if (this.responseText == 1) {
            showAlert('success', 'Status toggled');
            get_all_rooms();
        } else {
            showAlert('error', 'Toggle failed');
        }
    };

    // Properly format the data for URL encoding
    let data = `toggle_status=${encodeURIComponent(id)}&value=${encodeURIComponent(val)}`;
    xhr.send(data);
}

let add_image_form = document.getElementById('add_image_form');
add_image_form.addEventListener('submit',function(e)
{
  e.preventDefault();
  add_image();
});

function add_image(){
  let data = new FormData();
  data.append('image',add_image_form.elements['image'].files[0]);
  data.append('room_id',add_image_form.elements['room_id'].value);
  data.append('add_image','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/rooms.php",true);

  xhr.onload =function()
  {
    

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
      room_images(add_image_form.elements['room_id'].value,document.querySelector("#room-images .modal-title").innerText);
      add_image_form.reset();
      
    }
  }
  xhr.send(data);
}

function room_images(id,rname)
{
  document.querySelector("#room-images .modal-title").innerText = rname;
  add_image_form.elements['room_id'].value = id;
  add_image_form.elements['image'].value = '';

  let xhr = new XMLHttpRequest();
          xhr.open("POST","ajax/rooms.php", true);
          xhr.setRequestHeader('Content-Type' , 'application/x-www-form-urlencoded');
            
           xhr.onload =function()
           {
             document.getElementById('room-image-data').innerHTML = this.responseText;

             }
       xhr.send('get_room_images='+id);

}

function rem_image(img_id,room_id)
{
  let data = new FormData();
  data.append('image_id',img_id);
  data.append('room_id',room_id);
  data.append('rem_image','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/rooms.php",true);

  xhr.onload =function()
  {
    

    if(this.responseText == 1){
      showAlert('success','Image removed');
      room_images(room_id,document.querySelector("#room-images .modal-title").innerText);


    }
    
    else{
      showAlert('error','deletion failed');
      
      
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

function thumb_image(img_id,room_id){
  let data = new FormData();
  data.append('image_id',img_id);
  data.append('room_id',room_id);
  data.append('thumb_image','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/rooms.php",true);

  xhr.onload =function()
  {
    

    if(this.responseText == 1){
      showAlert('success','thumbnail changed');
      room_images(room_id,document.querySelector("#room-images .modal-title").innerText);


    }
    
    else{
      showAlert('error','thumbnail update failed');
      
      
    }
  }
  xhr.send(data);
}

function remove_room(room_id){
  if(confirm("Are you sure?"))
  {
    let data = new FormData();
    data.append('room_id',room_id);
    data.append('remove_room','');

  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/rooms.php",true);

  xhr.onload =function()
  {
    

    if(this.responseText == 1){
      showAlert('success','Place removed!');
      get_all_rooms();

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
        
        get_all_rooms();
        
        
    }