


function get_bookings(search='',page=1){
    let xhr = new XMLHttpRequest();
      xhr.open("POST","ajax/booking_records.php", true);
      xhr.setRequestHeader('Content-Type' , 'application/x-www-form-urlencoded');
        
       xhr.onload =function()
       {

        let data = JSON.parse(this.responseText);
        document.getElementById('table-data').innerHTML = data.table_data;
        document.getElementById('table-pagination').innerHTML = data.pagination;

         }
   xhr.send('get_bookings&search='+search+'&page='+page);
}

function change_page(page)
{
 get_bookings(document.getElementById('search_input').value,page);
}

function download(id)
{
    window.location.href = 'generate_pdf.php?gen_pdf&id='+id;
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
    
    get_bookings();
    
    
}