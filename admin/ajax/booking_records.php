<?php

require('../inc/db_config.php');
require('../inc/essentials.php');

adminLogin();

/*
if(isset($_POST['get_bookings']))
{    
    $frm_data = filteration($_POST);
    $limit = 20;
    $page = $frm_data['page'];
    $start = ($page-1) * $limit;

    $query = "SELECT bo.* , bd.*  FROM `booking_order` bo 
    INNER JOIN `booking_details` bd ON bd.user_id = bo.user_id
    WHERE((bo.booking_status='Credit' AND bo.arrival=1)
    OR (bo.booking_status='Failed'))
    AND (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?)
    ORDER BY bo.booking_id DESC";

    $res = select($query,["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%"],'sss');
    $limit_query = $query ." LIMIT $start,$limit"; 

    $limit_res = select($limit_query,["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%"],'sss');




    $i = 1;
    $table_data = "";

    $total_rows = mysqli_num_rows($res);
    if($total_rows==0)
    {
        $output = json_encode(['table_data'=>"<b>No Data found</b>", "pagination"=>'']);
        echo $output;
        exit;
    }

    while($data = mysqli_fetch_assoc($limit_res)){

        $date = date("d-m-Y",strtotime($data['datentime']));
        $checkin = date("d-m-Y",strtotime($data['check_in']));
        $checkout = date("d-m-Y",strtotime($data['check_out']));

        if($data['booking_status']=='Credit'){

            $status_bg = 'bg-success';
        }
        else{
            $status_bg = 'bg-danger';
        }

       
      $table_data.="
      <tr>
          <td>$i</td>
          <td>
            <span class='badge bg-primary'>
                Order ID: $data[order_id]
            </span>
            <br>
            <b>Name:</b> $data[user_name]
            <br>
            <b>Phone No:</b> $data[phonenum]
          </td>
            <td>
            <b>Event:</b> $data[event_name]
            <br>
            <b>Price:</b> ₹$data[price]
          </td>
          <td>
           <b>Amount:</b> ₹$data[trans_amt]
           <br>
           <b>Date:</b> $date
          </td>
          <td>
           <span class='badge $status_bg'>$data[booking_status]</span>
          </td>
          <td>
            <button type='button' class='mt-2 btn btn-success btn-sm fw-bold shadow-none'>
               <i class='bi bi-printer'></i>
            </button>
        </td>             
      <tr>
      ";

      $i++;

    }

    $output = json_encode(["table_data" => $table_data]);

    echo $output;

}
*/



/*
if (isset($_POST['get_bookings'])) {
    $frm_data = filteration($_POST);
    $limit = 10;
    $page = isset($frm_data['page']) ? $frm_data['page'] : 1;
    $start = ($page - 1) * $limit;
   
    $query = "SELECT bo.*, bd.* FROM `booking_order` bo 
        INNER JOIN `booking_details` bd ON bd.booking_id= bo.room_id
        WHERE ((bo.booking_status = 'Credit' AND bo.arrival = 1)
        OR (bo.booking_status = 'Failed'))
        AND (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?)
        ORDER BY bo.booking_id DESC";
    

    // Get all matching rows
    $res = select($query, ["%{$frm_data['search']}%", "%{$frm_data['search']}%", "%{$frm_data['search']}%"], 'sss');

    // Apply LIMIT for pagination
    $limit_query = $query . " LIMIT $start, $limit";
    $limit_res = select($limit_query, ["%{$frm_data['search']}%", "%{$frm_data['search']}%", "%{$frm_data['search']}%"], 'sss');

    $i = $start+1;
    $table_data = "";
    
    $total_rows = mysqli_num_rows($res);
    if ($total_rows == 0) {
        $output = json_encode(['table_data' => "<b>No Data found</b>", "pagination" => '']);
        echo $output;
        exit;
    }

    while ($data = mysqli_fetch_assoc($limit_res)) {
        $date = date("d-m-Y", strtotime($data['datentime']));
        $checkin = date("d-m-Y", strtotime($data['check_in']));
        $checkout = date("d-m-Y", strtotime($data['check_out']));
        
        $status_bg = $data['booking_status'] == 'Credit' ? 'bg-success' : 'bg-danger';

        $table_data .= "
        <tr>
            <td>$i</td>
            <td>
                <span class='badge bg-primary'>Order ID: $data[order_id]</span>
                <br>
                <b>Name:</b> $data[user_name]
                <br>
                <b>Phone No:</b> $data[phonenum]
            </td>
            <td>
                <b>Event:</b> $data[event_name]
                <br>
                <b>Price:</b> ₹$data[price]
            </td>
            <td>
                <b>Amount:</b> ₹$data[trans_amt]
                <br>
                <b>Date:</b> $date
            </td>
            <td>
                <span class='badge $status_bg'>$data[booking_status]$data[booking_id]</span>
            </td>
            <td>
                <button type='button' onclick='download($data[booking_id])' class='btn btn-outline-success btn-sm fw-bold shadow-none'>
                    <i class='bi bi-file-earmark-arrow-down-fill'></i>
                </button>
            </td>
        </tr>";

        $i++;
    }
 $pagination = "";

 if($total_rows>$limit)
 {
       $total_pages = ceil($total_rows/$limit);
         
       if($page!=1){
        $pagination .="<li class='page-item'><button onclick='change_page(1)' 
        class='page-link'>First</button></li>";
       }

       $disabled = ($page==1)? "disabled" : "";
       $prev= $page-1;
       $pagination .="<li class='page-item $disabled'><button onclick='change_page($prev)' class='page-link'>Prev</button></li>";
       

       $disabled = ($page==$total_pages)? "disabled" : "";
       $next = $page+1;
       $pagination .="<li class='page-item $disabled'><button onclick='change_page($next)' class='page-link'>Next</button></li>";
        
       if($page!=$total_pages){
        $pagination .="<li class='page-item'><button onclick='change_page($total_pages)' 
        class='page-link'>Last</button></li>";
       }
}
    $output = json_encode(["table_data" => $table_data,"pagination"=>$pagination]);
    header('Content-Type: application/json'); // Ensure content type is JSON
    echo $output;

     
}

*/



/*

if (isset($_POST['get_bookings'])) {
    $frm_data = filteration($_POST);
    $limit = 10;
    $page = isset($frm_data['page']) ? (int)$frm_data['page'] : 1;
    $start = ($page - 1) * $limit;

    // SQL query to select data with join and filtering
    $query = "SELECT 
                bo.booking_id, 
                bo.order_id, 
                bo.trans_amt, 
                bo.booking_status, 
                bo.arrival, 
                bd.event_name, 
                bd.phonenum, 
                bd.user_name, 
                bd.price, 
                bo.datentime, 
                bo.check_in, 
                bo.check_out
              FROM 
                booking_order bo
              INNER JOIN 
                booking_details bd 
              ON 
                bd.booking_id = bo.room_id
              WHERE 
                ((bo.booking_status = 'Credit' AND bo.arrival = 1)
                OR (bo.booking_status = 'Failed'))
                AND (bo.order_id LIKE ? 
                OR bd.phonenum LIKE ? 
                OR bd.user_name LIKE ?)
              ORDER BY 
                bo.booking_id DESC";

    // Get all matching rows
    $res = select($query, ["%{$frm_data['search']}%", "%{$frm_data['search']}%", "%{$frm_data['search']}%"], 'sss');

    // Apply LIMIT for pagination
    $limit_query = $query . " LIMIT $start, $limit";
    $limit_res = select($limit_query, ["%{$frm_data['search']}%", "%{$frm_data['search']}%", "%{$frm_data['search']}%"], 'sss');

    $i = $start + 1;
    $table_data = "";

    // Get the total number of rows
    $total_rows = mysqli_num_rows($res);

    if ($total_rows == 0) {
        $output = json_encode(['table_data' => "<b>No Data found</b>", "pagination" => '']);
        echo $output;
        exit;
    }

    // Process rows for table data
    while ($data = mysqli_fetch_assoc($limit_res)) {
        $date = date("d-m-Y", strtotime($data['datentime']));
        $checkin = date("d-m-Y", strtotime($data['check_in']));
        $checkout = date("d-m-Y", strtotime($data['check_out']));
        
        $status_bg = $data['booking_status'] == 'Credit' ? 'bg-success' : 'bg-danger';

        $table_data .= "
        <tr>
            <td>$i</td>
            <td>
                <span class='badge bg-primary'>Order ID: $data[order_id]</span>
                <br>
                <b>Name:</b> $data[user_name]
                <br>
                <b>Phone No:</b> $data[phonenum]
            </td>
            <td>
                <b>Event:</b> $data[event_name]
                <br>
                <b>Price:</b> ₹$data[price]
            </td>
            <td>
                <b>Amount:</b> ₹$data[trans_amt]
                <br>
                <b>Date:</b> $date
            </td>
            <td>
                <span class='badge $status_bg'>$data[booking_status]</span>
            </td>
            <td>
                <button type='button' onclick='download($data[booking_id])' class='btn btn-outline-success btn-sm fw-bold shadow-none'>
                    <i class='bi bi-file-earmark-arrow-down-fill'></i>
                </button>
            </td>
        </tr>";

        $i++;
    }

    // Pagination logic
    $pagination = "";
    if ($total_rows > $limit) {
        $total_pages = ceil($total_rows / $limit);

        if ($page != 1) {
            $pagination .= "<li class='page-item'><button onclick='change_page(1)' class='page-link'>First</button></li>";
        }

        $disabled = ($page == 1) ? "disabled" : "";
        $prev = $page - 1;
        $pagination .= "<li class='page-item $disabled'><button onclick='change_page($prev)' class='page-link'>Prev</button></li>";

        $disabled = ($page == $total_pages) ? "disabled" : "";
        $next = $page + 1;
        $pagination .= "<li class='page-item $disabled'><button onclick='change_page($next)' class='page-link'>Next</button></li>";

        if ($page != $total_pages) {
            $pagination .= "<li class='page-item'><button onclick='change_page($total_pages)' class='page-link'>Last</button></li>";
        }
    }

    $output = json_encode(["table_data" => $table_data, "pagination" => $pagination]);
    header('Content-Type: application/json'); // Ensure content type is JSON
    echo $output;
}

*/



/*
if (isset($_POST['get_bookings'])) {
    $frm_data = filteration($_POST);
    $limit = 10;
    $page = isset($frm_data['page']) ? (int)$frm_data['page'] : 1;
    $start = ($page - 1) * $limit;

    // Updated SQL query to reflect accurate join conditions
    $query = "SELECT DISTINCT
                bo.booking_id, 
                bo.order_id, 
                bo.trans_amt, 
                bo.booking_status, 
                bo.arrival, 
                bd.event_name, 
                bd.phonenum, 
                bd.user_name, 
                bd.price, 
                bo.datentime, 
                bo.check_in, 
                bo.check_out
              FROM 
                booking_order bo
              INNER JOIN 
                booking_details bd 
              ON 
                bd.booking_id = bo.room_id
              WHERE 
                ((bo.booking_status = 'Credit' AND bo.arrival = 1)
                OR (bo.booking_status = 'Failed'))
                AND (bo.order_id LIKE ? 
                OR bd.phonenum LIKE ? 
                OR bd.user_name LIKE ?)
              ORDER BY 
                bo.booking_id DESC";

    // Get all matching rows
    $res = select($query, ["%{$frm_data['search']}%", "%{$frm_data['search']}%", "%{$frm_data['search']}%"], 'sss');
    if (!$res) {
        echo json_encode(['error' => "Query failed: " . mysqli_error($conn)]);
        exit;
    }

    // Apply LIMIT for pagination
    $limit_query = $query . " LIMIT $start, $limit";
    $limit_res = select($limit_query, ["%{$frm_data['search']}%", "%{$frm_data['search']}%", "%{$frm_data['search']}%"], 'sss');
    if (!$limit_res) {
        echo json_encode(['error' => "Query failed: " . mysqli_error($conn)]);
        exit;
    }

    $i = $start + 1;
    $table_data = "";

    // Get the total number of rows
    $total_rows = mysqli_num_rows($res);

    if ($total_rows == 0) {
        $output = json_encode(['table_data' => "<b>No Data found</b>", "pagination" => '']);
        echo $output;
        exit;
    }

    while ($data = mysqli_fetch_assoc($limit_res)) {
        $date = date("d-m-Y", strtotime($data['datentime']));
        $checkin = date("d-m-Y", strtotime($data['check_in']));
        $checkout = date("d-m-Y", strtotime($data['check_out']));
        
        $status_bg = $data['booking_status'] == 'Credit' ? 'bg-success' : 'bg-danger';

        $table_data .= "
        <tr>
            <td>$i</td>
            <td>
                <span class='badge bg-primary'>Order ID: $data[order_id]</span>
                <br>
                <b>Name:</b> $data[user_name]
                <br>
                <b>Phone No:</b> $data[phonenum]
            </td>
            <td>
                <b>Event:</b> $data[event_name]
                <br>
                <b>Price:</b> ₹$data[price]
            </td>
            <td>
                <b>Amount:</b> ₹$data[trans_amt]
                <br>
                <b>Date:</b> $date
            </td>
            <td>
                <span class='badge $status_bg'>$data[booking_status]</span>
            </td>
            <td>
                <button type='button' onclick='download($data[booking_id])' class='btn btn-outline-success btn-sm fw-bold shadow-none'>
                    <i class='bi bi-file-earmark-arrow-down-fill'></i>
                </button>
            </td>
        </tr>";

        $i++;
    }

    // Pagination logic
    $pagination = "";
    if ($total_rows > $limit) {
        $total_pages = ceil($total_rows / $limit);

        if ($page != 1) {
            $pagination .= "<li class='page-item'><button onclick='change_page(1)' class='page-link'>First</button></li>";
        }

        $disabled = ($page == 1) ? "disabled" : "";
        $prev = $page - 1;
        $pagination .= "<li class='page-item $disabled'><button onclick='change_page($prev)' class='page-link'>Prev</button></li>";

        $disabled = ($page == $total_pages) ? "disabled" : "";
        $next = $page + 1;
        $pagination .= "<li class='page-item $disabled'><button onclick='change_page($next)' class='page-link'>Next</button></li>";

        if ($page != $total_pages) {
            $pagination .= "<li class='page-item'><button onclick='change_page($total_pages)' class='page-link'>Last</button></li>";
        }
    }

    $output = json_encode(["table_data" => $table_data, "pagination" => $pagination]);
    header('Content-Type: application/json'); // Ensure content type is JSON
    echo $output;
}

*/

/*
if (isset($_POST['get_bookings'])) {
    $frm_data = filteration($_POST);
    $limit = 10;
    $page = isset($frm_data['page']) ? (int)$frm_data['page'] : 1;
    $start = ($page - 1) * $limit;

    // Updated SQL query to group by order_id
    $query = "SELECT DISTINCT
                bo.booking_id, 
                bo.order_id, 
                bo.trans_amt, 
                bo.booking_status, 
                bo.arrival, 
                bd.event_name, 
                bd.phonenum, 
                bd.user_name, 
                bd.price, 
                bo.datentime, 
                bo.check_in, 
                bo.check_out
              FROM 
                booking_order bo
              INNER JOIN 
                booking_details bd 
              ON 
                bd.booking_id = bo.room_id
              WHERE 
                ((bo.booking_status = 'Credit' AND bo.arrival = 1)
                OR (bo.booking_status = 'Failed'))
                AND (bo.order_id LIKE ? 
                OR bd.phonenum LIKE ? 
                OR bd.user_name LIKE ?)
              GROUP BY 
                bo.order_id
              ORDER BY 
                bo.booking_id DESC";

    // Get all matching rows
    $res = select($query, ["%{$frm_data['search']}%", "%{$frm_data['search']}%", "%{$frm_data['search']}%"], 'sss');
    if (!$res) {
        echo json_encode(['error' => "Query failed: " . mysqli_error($conn)]);
        exit;
    }

    // Apply LIMIT for pagination
    $limit_query = $query . " LIMIT $start, $limit";
    $limit_res = select($limit_query, ["%{$frm_data['search']}%", "%{$frm_data['search']}%", "%{$frm_data['search']}%"], 'sss');
    if (!$limit_res) {
        echo json_encode(['error' => "Query failed: " . mysqli_error($conn)]);
        exit;
    }

    $i = $start + 1;
    $table_data = "";

    // Get the total number of rows
    $total_rows = mysqli_num_rows($res);

    if ($total_rows == 0) {
        $output = json_encode(['table_data' => "<b>No Data found</b>", "pagination" => '']);
        echo $output;
        exit;
    }

    while ($data = mysqli_fetch_assoc($limit_res)) {
        $date = date("d-m-Y", strtotime($data['datentime']));
        $checkin = date("d-m-Y", strtotime($data['check_in']));
        $checkout = date("d-m-Y", strtotime($data['check_out']));
        
        $status_bg = $data['booking_status'] == 'Credit' ? 'bg-success' : 'bg-danger';

        $table_data .= "
        <tr>
            <td>$i</td>
            <td>
                <span class='badge bg-primary'>Order ID: $data[order_id]</span>
                <br>
                <b>Name:</b> $data[user_name]
                <br>
                <b>Phone No:</b> $data[phonenum]
            </td>
            <td>
                <b>Event:</b> $data[event_name]
                <br>
                <b>Price:</b> ₹$data[price]
            </td>
            <td>
                <b>Amount:</b> ₹$data[trans_amt]
                <br>
                <b>Date:</b> $date
            </td>
            <td>
                <span class='badge $status_bg'>$data[booking_status]</span>
            </td>
            <td>
                <button type='button' onclick='download($data[booking_id])' class='btn btn-outline-success btn-sm fw-bold shadow-none'>
                    <i class='bi bi-file-earmark-arrow-down-fill'></i>
                </button>
            </td>
        </tr>";

        $i++;
    }

    // Pagination logic
    $pagination = "";
    if ($total_rows > $limit) {
        $total_pages = ceil($total_rows / $limit);

        if ($page != 1) {
            $pagination .= "<li class='page-item'><button onclick='change_page(1)' class='page-link'>First</button></li>";
        }

        $disabled = ($page == 1) ? "disabled" : "";
        $prev = $page - 1;
        $pagination .= "<li class='page-item $disabled'><button onclick='change_page($prev)' class='page-link'>Prev</button></li>";

        $disabled = ($page == $total_pages) ? "disabled" : "";
        $next = $page + 1;
        $pagination .= "<li class='page-item $disabled'><button onclick='change_page($next)' class='page-link'>Next</button></li>";

        if ($page != $total_pages) {
            $pagination .= "<li class='page-item'><button onclick='change_page($total_pages)' class='page-link'>Last</button></li>";
        }
    }

    $output = json_encode(["table_data" => $table_data, "pagination" => $pagination]);
    header('Content-Type: application/json'); // Ensure content type is JSON
    echo $output;
}

*/




if (isset($_POST['get_bookings'])) {
    $frm_data = filteration($_POST);
    $limit = 10;
    $page = isset($frm_data['page']) ? (int)$frm_data['page'] : 1;
    $start = ($page - 1) * $limit;

    // Updated SQL query with correct JOIN using booking_details_id
    $query = "SELECT 
                bo.booking_id, 
                bo.order_id, 
                bo.trans_amt, 
                bo.booking_status, 
                bo.arrival, 
                bd.event_name, 
                bd.phonenum, 
                bd.user_name, 
                bd.price, 
                bo.datentime, 
                bo.check_in, 
                bo.check_out
              FROM 
                booking_order bo
              INNER JOIN 
                booking_details bd 
              ON 
                bd.sr_no = bo.booking_details_id
              WHERE 
                ((bo.booking_status = 'Credit' AND bo.arrival = 1)
                OR (bo.booking_status = 'Failed'))
                AND (bo.order_id LIKE ? 
                OR bd.phonenum LIKE ? 
                OR bd.user_name LIKE ?)
              GROUP BY 
                bo.order_id, 
                bo.trans_amt, 
                bo.booking_status, 
                bo.arrival, 
                bo.datentime, 
                bo.check_in, 
                bo.check_out
              ORDER BY 
                bo.booking_id DESC";

    // Get all matching rows
    $res = select($query, ["%{$frm_data['search']}%", "%{$frm_data['search']}%", "%{$frm_data['search']}%"], 'sss');
    if (!$res) {
        echo json_encode(['error' => "Query failed: " . mysqli_error($conn)]);
        exit;
    }

    // Apply LIMIT for pagination
    $limit_query = $query . " LIMIT $start, $limit";
    $limit_res = select($limit_query, ["%{$frm_data['search']}%", "%{$frm_data['search']}%", "%{$frm_data['search']}%"], 'sss');
    if (!$limit_res) {
        echo json_encode(['error' => "Query failed: " . mysqli_error($conn)]);
        exit;
    }

    $i = $start + 1;
    $table_data = "";

    // Get the total number of rows
    $total_rows = mysqli_num_rows($res);

    if ($total_rows == 0) {
        $output = json_encode(['table_data' => "<b>No Data found</b>", "pagination" => '']);
        echo $output;
        exit;
    }

    while ($data = mysqli_fetch_assoc($limit_res)) {
        $date = date("d-m-Y", strtotime($data['datentime']));
        $checkin = date("d-m-Y", strtotime($data['check_in']));
        $checkout = date("d-m-Y", strtotime($data['check_out']));
        
        $status_bg = $data['booking_status'] == 'Credit' ? 'bg-success' : 'bg-danger';

        $table_data .= "
        <tr>
            <td>$i</td>
            <td>
                <span class='badge bg-primary'>Order ID: $data[order_id]</span>
                <br>
                <b>Name:</b> $data[user_name]
                <br>
                <b>Phone No:</b> $data[phonenum]
            </td>
            <td>
                <b>Event:</b> $data[event_name]
                <br>
                <b>Price:</b> ₹$data[price]
            </td>
            <td>
                <b>Amount:</b> ₹$data[trans_amt]
                <br>
                <b>Date:</b> $date
            </td>
            <td>
                <span class='badge $status_bg'>$data[booking_status]</span>
            </td>
            <td>
                <button type='button' onclick='download($data[booking_id])' class='btn btn-outline-success btn-sm fw-bold shadow-none'>
                    <i class='bi bi-file-earmark-arrow-down-fill'></i>
                </button>
            </td>
        </tr>";

        $i++;
    }

    // Pagination logic
    $pagination = "";
    if ($total_rows > $limit) {
        $total_pages = ceil($total_rows / $limit);

        if ($page != 1) {
            $pagination .= "<li class='page-item'><button onclick='change_page(1)' class='page-link'>First</button></li>";
        }

        $disabled = ($page == 1) ? "disabled" : "";
        $prev = $page - 1;
        $pagination .= "<li class='page-item $disabled'><button onclick='change_page($prev)' class='page-link'>Prev</button></li>";

        $disabled = ($page == $total_pages) ? "disabled" : "";
        $next = $page + 1;
        $pagination .= "<li class='page-item $disabled'><button onclick='change_page($next)' class='page-link'>Next</button></li>";

        if ($page != $total_pages) {
            $pagination .= "<li class='page-item'><button onclick='change_page($total_pages)' class='page-link'>Last</button></li>";
        }
    }

    $output = json_encode(["table_data" => $table_data, "pagination" => $pagination]);
    header('Content-Type: application/json'); // Ensure content type is JSON
    echo $output;
}















