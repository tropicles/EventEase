<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function toggle_status(id, val) {
      let xhr = new XMLHttpRequest();
      xhr.open("POST", "ajax/events.php", true);
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
  }</script>
<script>
function setActive()
        {
            let navbar= document.getElementById('dashboard-menu');
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
setActive();
</script>