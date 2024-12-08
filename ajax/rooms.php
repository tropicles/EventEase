<?php

require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');
date_default_timezone_set("Asia/Kolkata");
session_start();

if(isset($_GET['fetch_rooms'])){

    $chk_avail = json_decode($_GET['chk_avail'],true);
     $guests = json_decode($_GET['guests'],true);
     $capacity = ($guests['capacity']!='') ? $guests['capacity'] : 0;




    if($chk_avail['checkin']!='' && $chk_avail['checkout']!=''){

        $today_date = new DateTime(date("Y-m-d"));
        $checkin_date = new DateTime($chk_avail['checkin']);
        $checkout_date = new DateTime($chk_avail['checkout']);

    
        if($checkin_date == $checkout_date)
        {
        echo"<h3 class='text-center text-danger'>Invalid Dates</h3>";
         exit;
        }
    
        else if($checkout_date < $checkin_date)
        {
            echo"<h3 class='text-center text-danger'>Invalid Dates</h3>";
            exit;
        }
    
        else if($checkin_date < $today_date)
        {
        echo"<h3 class='text-center text-danger'>Invalid Dates</h3>";
         exit;
        }
    }

    $count_rooms = 0;
    $output = "";
    $room_res = select("SELECT * FROM `rooms` WHERE `capacity`>=? AND `status`=? AND `removed`=?",[$capacity,1,0],'iii');
    while($room_data = mysqli_fetch_assoc($room_res))
    {
        if($chk_avail['checkin']!='' && $chk_avail['checkout']!='')
        {
            $tb_query = "SELECT COUNT(*) AS `total` FROM `booking_event` 
            WHERE room_id=? AND check_out > ? AND check_in < ?";
            $values = [$room_data['id'],$chk_avail['checkin'],$chk_avail['checkout']];

            $tb_fetch = mysqli_fetch_assoc(select($tb_query,$values,'iss'));

            if ($tb_fetch['total'] > 0)
            {

            continue;
            
        }

        }

      $fea_q = mysqli_query($con,"SELECT f.name FROM `features` f INNER JOIN `room_features` rfea ON f.id = rfea.features_id WHERE rfea.room_id = '$room_data[id]' ");
      $features_data = "";
      while($fea_row = mysqli_fetch_assoc($fea_q))
      {
           $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>
                                 $fea_row[name]
                              </span>";
          
               
      }
         // get thumbnail
         $room_thumb = ROOMS_IMG_PATH."thumbnail.jpg";
         $thumb_q = mysqli_query($con,"SELECT * FROM `room_image` WHERE `room_id` = '$room_data[id]' AND 
         `thumb` = '1' ");
         
         if(mysqli_num_rows($thumb_q)>0)
         {
             $thumb_res = mysqli_fetch_assoc($thumb_q);
             $room_thumb = ROOMS_IMG_PATH.$thumb_res['image'];
         }
         $book_btn = "";
         $login = 0;
         if(isset($_SESSION['login']) && $_SESSION['login']==true)
         {
          $login = 1;
         }
         $book_btn = "<button onclick='checkLoginToBook($login,$room_data[id])' class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2'>Book Now</button>";

         $output.= "<div class='card mb-4 border-0 shadow'>
                          <div class='row g-0 p-3 align-items-center'>
                            <div class='col-md-5 mb-lg-0 mb-md-0 mb-3'>
                              <img src='$room_thumb' class='img-fluid rounded'>
                            </div>
                            <div class='col-md-5 px-lg-3 px-md-3 px-0'>
                              <h5 class='mb-3'>$room_data[name]</h5>
                              <br>
                              <div class='features mb-4'>
                                <h6 class='mb-1'>Features</h6>
                                <span class='badge rounded-pill bg-light text-dark text-wrap'>$room_data[capacity] occupancy</span>
                                $features_data
                                <br><br>
                                <h6 class='mb-1'>Description:</h6>
                                  <div class='badge rounded-pill bg-light text-dark text-wrap'>
                                  <div class='description-text'>
                                    $room_data[description]
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class='col-md-2 text-align-center'>
                              $book_btn
                            </div>
                          </div>
                       </div>";
              $count_rooms++;
    }
    if($count_rooms>0){
       
        echo $output;
    }
    else{
        echo"<h3 class='text-center text-danger'>No Places to show</h3>";
    }
}