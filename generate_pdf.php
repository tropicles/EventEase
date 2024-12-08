<?php
require('admin/inc/essentials.php');
require('admin/inc/db_config.php');
require('admin/inc/mpdf/vendor/autoload.php');


if(!(isset($_SESSION['login']) && $_SESSION['login']==true))
 {
    redirect('index.php');
 }



/*
if(isset($_GET['gen_pdf']) && isset($_GET['id']))
{
$frm_data = filteration($_GET);

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
                AND bo.booking_id = '$frm_data[id]' ";


  $res = mysqli_query($con,$query);
  $total_rows = mysqli_num_rows($res);
  if($total_rows==0)
  {
    header('location: dashboard.php');
    exit;
  }
   $data = mysqli_fetch_assoc($res);
   $date = date("d-m-Y", strtotime($data['datentime']));
   $checkin = date("d-m-Y", strtotime($data['check_in']));
   $checkout = date("d-m-Y", strtotime($data['check_out']));

   $table_data = "
    <style>
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-family: Arial, sans-serif;
        }
        .receipt-table h2 {
            text-align: center;
            color: #333;
        }
        .receipt-table table {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            margin: 20px 0;
        }
        .receipt-table th, .receipt-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .receipt-table th {
            background-color: #f4f4f4;
            color: #333;
        }
        .receipt-table td {
            background-color: #fff;
        }
        .receipt-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .receipt-table tr:hover {
            background-color: #f1f1f1;
        }
        .receipt-table td[colspan='2'] {
            font-weight: bold;
            background-color: #f4f4f4;
        }
    </style>

    <div class='receipt-table'>
        <h2>BOOKING RECEIPT</h2>
        <table>
            <tr>
                <td colspan='2'>Order ID: $data[order_id]</td>
            </tr>
            <tr>
                <td>Booking Date: $date</td>
            </tr>
            <tr>
                <td colspan='2'>Status (Credit Means Successful): $data[booking_status]</td>
            </tr>
            <tr>
                <td>Name: $data[user_name]</td>
            </tr>
            <tr>
                <td>Phone No.: $data[phonenum]</td>
            </tr>
            <tr>
                <td>Event Name: $data[event_name]</td>
            </tr>
            <tr>
                <td>Event Start Date: $checkin</td>
            </tr>
            <tr>
                <td>Event End Date: $checkout</td>
            </tr>
            <tr>
                <td>Price: ₹$data[price]</td>
            </tr>
        </table>
    </div>
";



        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($table_data);
        $mpdf->Output($data['order_id'],'D');

}
*/


if (isset($_GET['gen_pdf']) && isset($_GET['id'])) {


    $frm_data = filteration($_GET);

    // SQL query updated to use booking_details_id
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
                AND bo.booking_id = ?";

    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $frm_data['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        header('Location: index.php');
        exit;
    }
    
    $data = $result->fetch_assoc();
    $date = date("d-m-Y", strtotime($data['datentime']));
    $checkin = date("d-m-Y", strtotime($data['check_in']));
    $checkout = date("d-m-Y", strtotime($data['check_out']));

    $table_data = "
    <style>
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-family: Arial, sans-serif;
        }
        .receipt-table h2 {
            text-align: center;
            color: #333;
        }
        .receipt-table table {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            margin: 20px 0;
        }
        .receipt-table th, .receipt-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .receipt-table th {
            background-color: #f4f4f4;
            color: #333;
        }
        .receipt-table td {
            background-color: #fff;
        }
        .receipt-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .receipt-table tr:hover {
            background-color: #f1f1f1;
        }
        .receipt-table td[colspan='2'] {
            font-weight: bold;
            background-color: #f4f4f4;
        }
    </style>

    <div class='receipt-table'>
        <h2>BOOKING RECEIPT</h2>
        <table>
            <tr>
                <td colspan='2'>Order ID: $data[order_id]</td>
            </tr>
            <tr>
                <td>Booking Date: $date</td>
            </tr>
            <tr>
                <td colspan='2'>Status (Credit Means Successful): $data[booking_status]</td>
            </tr>
            <tr>
                <td>Name: $data[user_name]</td>
            </tr>
            <tr>
                <td>Phone No.: $data[phonenum]</td>
            </tr>
            <tr>
                <td>Event Name: $data[event_name]</td>
            </tr>
            <tr>
                <td>Event Start Date: $checkin</td>
            </tr>
            <tr>
                <td>Event End Date: $checkout</td>
            </tr>
            <tr>
                <td>Price: ₹$data[price]</td>
            </tr>
        </table>
    </div>
    ";

    
    $mpdf = new \Mpdf\Mpdf();

    $mpdf->WriteHTML($table_data);

    $mpdf->Output($data['order_id'] . '.pdf', 'D');
}

else{
    header('location: index.php');
}

