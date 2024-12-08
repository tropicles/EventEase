<?php

$hname = 'localhost';
$uname = 'root';
$pass = '';
$db = 'emwebsite';

$con = mysqli_connect($hname,$uname,$pass,$db);

if(!$con){
    die("Cannot connect to database".mysqli_connect_error());
}


function filteration($data)
{
    foreach($data as $key => $value)
    {
        $data[$key] = trim($value);
        $data[$key] = stripcslashes($value);
        $data[$key] = htmlspecialchars($value);
        $data[$key] = strip_tags($value);
    }
    return $data;
}

function select($sql,$values,$datatypes)
{
    $con = $GLOBALS['con'];
    if($stmt = mysqli_prepare($con,$sql))
    {
      mysqli_stmt_bind_param($stmt,$datatypes,...$values);
      if(mysqli_stmt_execute($stmt))
      {
        $res=mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $res;
      }
      else
      {
        mysqli_stmt_close($stmt);
        die("Query cannot be executed");
      }
      
    }
    else
    {
        die("Query cannot be prepared - Select");
    }

}

/*
function fetchFilteredBookings($con, $status, $removed)
{
    // Define the SQL query with placeholders for parameters
    $sql = "SELECT 
                    b.`booking_id`, 
                    b.`user_id`, 
                    b.`room_id`, 
                    b.`event_name`, 
                    b.`event_desc`, 
                    b.`event_image`, 
                    b.`check_in`, 
                    b.`check_out`, 
                    b.`datentime`, 
                    r.`status`, 
                    r.`removed`
                FROM 
                    `booking_event` b
                INNER JOIN 
                    `rooms` r 
                ON 
                    b.`room_id` = r.`id`
                WHERE 
                    r.`status` = ? 
                    AND r.`removed` = ?
    ";

    // Prepare and execute the query
    $values = [$status, $removed];
    $datatypes = 'ii'; // Assuming both parameters are integers

    return select1($sql, $values, $datatypes, $con);
}
*/


/*

function fetchFilteredBookingsindex($con, $status, $removed, $limit = 3)
{
    // Define the SQL query with placeholders for parameters and limit
    $sql = "
        SELECT 
            b.`booking_id`, 
            b.`user_id`, 
            b.`room_id`, 
            b.`event_name`, 
            b.`event_desc`, 
            b.`event_image`, 
            b.`check_in`, 
            b.`check_out`, 
            b.`datentime`, 
            r.`status`, 
            r.`removed`
        FROM 
            `booking_event` b
        INNER JOIN 
            `rooms` r 
        ON 
            b.`room_id` = r.`id`
        WHERE 
            r.`status` = ? 
            AND r.`removed` = ?
        LIMIT ?
    ";

    // Prepare and execute the query with the limit
    $values = [$status, $removed, $limit];
    $datatypes = 'iii'; // Two integers for status and removed, and one integer for limit

    return select1($sql, $values, $datatypes, $con);
}

*/
/*
function fetchFilteredBookingsindex($con, $status, $removed, $limit = 3)
{
    date_default_timezone_set("Asia/Kolkata");
    // Define the SQL query with placeholders for parameters and limit
    $sql = "
        SELECT 
            b.`booking_id`, 
            b.`user_id`, 
            b.`room_id`, 
            b.`event_name`, 
            b.`event_desc`, 
            b.`event_image`, 
            b.`check_in`, 
            b.`check_out`, 
            b.`datentime`, 
            r.`status`, 
            r.`removed`
        FROM 
            `booking_event` b
        INNER JOIN 
            `rooms` r 
        ON 
            b.`room_id` = r.`id`
        WHERE 
            r.`status` = ? 
            AND r.`removed` = ? 
            AND b.`check_out` >= ?  -- Ensure checkout date is not in the past
        ORDER BY 
            b.`check_in` ASC
        LIMIT ?
    ";

    // Get the current date
    $currentDate = date('Y-m-d');

    // Prepare and execute the query with the limit and the constraint
    $values = [$status, $removed, $currentDate, $limit];
    $datatypes = 'isis'; // Two integers for status and removed, one string for the current date, and one integer for limit

    return select1($sql, $values, $datatypes, $con);
}
*/





function select1($sql, $values, $datatypes, $con)
{
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            mysqli_stmt_close($stmt);
            die("Query cannot be executed");
        }
    } else {
        die("Query cannot be prepared - Select");
    }
}




function update($sql, $values, $datatypes)
{
    $con = $GLOBALS['con'];

    // Ensure the number of placeholders matches the number of values and types
    $numPlaceholders = substr_count($sql, '?');
   
    if (count($values) !== $numPlaceholders || strlen($datatypes) !== count($values)) {
        die("Mismatch between placeholders, values, and data types");
    }
       

    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);

        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            mysqli_stmt_close($stmt);
            die("Query cannot be executed - Update: " . mysqli_error($con));
        }
    } else {
        die("Query cannot be prepared - Update: " . mysqli_error($con));
    }
}

















function selectAll($table)
{
  $con = $GLOBALS['con'];
  $res = mysqli_query($con,"SELECT * FROM $table");
  return $res;
}


function deletei($sql, $values, $datatypes)
{
    $con = $GLOBALS['con'];

    // Ensure the number of placeholders matches the number of values and types
    $numPlaceholders = substr_count($sql, '?');
    if (count($values) !== $numPlaceholders || strlen($datatypes) !== count($values)) {
        die("Mismatch between placeholders, values, and data types");
    }

    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);

        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            mysqli_stmt_close($stmt);
            die("Query cannot be executed - Delete: " . mysqli_error($con));
        }
    } else {
        die("Query cannot be prepared - Delete: " . mysqli_error($con));
    }
}











/*
error code
function insert($sql, $values, $datatypes)
{    
  
    $con = $GLOBALS['con'];
    if ($stmt = mysqli_prepare($con, $sql)) {
        // Bind parameters
        mysqli_stmt_bind_param ($stmt , $datatypes, ...$values)  ;
        

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            mysqli_stmt_close($stmt);
            die("Query cannot be executed - Insert");
        }
    } else {
        die("Query cannot be prepared - Insert");
    }
}

*/
/*
function insert($sql, $values, $datatypes) {
  $con = $GLOBALS['con'];

  // Ensure the number of placeholders matches the number of values and types
  $numPlaceholders = substr_count($sql, '?');
  if (count($values) !== $numPlaceholders || strlen($datatypes) !== count($values)) {
      die("Mismatch between placeholders, values, and data types");
  }

  if ($stmt = mysqli_prepare($con, $sql)) {
      // Bind parameters
      $bindParams = array_merge([$stmt, $datatypes], $values);
      call_user_func_array('mysqli_stmt_bind_param', $bindParams);

      // Execute the statement
      if (mysqli_stmt_execute($stmt)) {
          $res = mysqli_stmt_affected_rows($stmt);
          mysqli_stmt_close($stmt);
          return $res;
      } else {
          mysqli_stmt_close($stmt);
          die("Query cannot be executed - Insert: " . mysqli_error($con));
      }
  } else {
      die("Query cannot be prepared - Insert: " . mysqli_error($con));
  }
}
*/
function insert($sql, $values, $datatypes) {
  $con = $GLOBALS['con'];

  // Ensure the number of placeholders matches the number of values and types
  $numPlaceholders = substr_count($sql, '?');
  if (count($values) !== $numPlaceholders || strlen($datatypes) !== count($values)) {
      die("Mismatch between placeholders, values, and data types");
  }

  if ($stmt = mysqli_prepare($con, $sql)) {
      // Bind parameters
      mysqli_stmt_bind_param($stmt, $datatypes, ...$values);

      // Execute the statement
      if (mysqli_stmt_execute($stmt)) {
          $res = mysqli_stmt_affected_rows($stmt);
          mysqli_stmt_close($stmt);
          return $res;
      } else {
          mysqli_stmt_close($stmt);
          die("Query cannot be executed - Insert: " . mysqli_error($con));
      }
  } else {
      die("Query cannot be prepared - Insert: " . mysqli_error($con));
  }
}



