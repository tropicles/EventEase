<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();
/*
error code
if(isset($_POST['add_feature']))
{
    $frm_data = filteration($_POST);
    $q = "INSERT INTO `features`(`name`) VALUE (?) " ;
    $values = [$frm_data['name']];
    $ex='ss';
    $res = insert($q,$values,$ex);
    echo $res;
}
*/

if (isset($_POST['add_feature'])) {
    // Assuming filteration is a function that sanitizes the input
    $frm_data = filteration($_POST);

    // SQL query for insertion
    $q = "INSERT INTO `features` (`name`) VALUES (?)"; // Corrected to VALUES

    // Values to bind
    $values = [$frm_data['name']];
    
    // Data types string
    $datatypes = 's'; // 's' for string
    
    // Execute insert function and output result
    $res = insert($q, $values, $datatypes);
    echo $res;
}



if (isset($_POST['get_features'])) {
    $res = selectAll('features');
    $i = 1; // Added missing semicolon

    while ($row = mysqli_fetch_assoc($res)) {
        // Used htmlspecialchars to escape data and fixed the unclosed <td> tag
        echo <<<data
         <tr>
           <td>$i</td>
           <td>{$row['name']}</td> <!-- Fixed unclosed <td> tag -->
           <td>
             <button type="submit" onclick="rem_feature($row[id])" class="btn btn-danger btn-sm shadow-none">
               <i class="bi bi-trash"></i>Delete
             </button>
           </td>
         </tr>
        data;
        $i++;
    }
}

if (isset($_POST['rem_feature'])) {
    // Assuming filteration() sanitizes and returns the data
    $frm_data = filteration($_POST);
    $values = [$frm_data['rem_feature']];
    $datatypes = 'i'; // 'i' for integer type

    $sql = "DELETE FROM `features` WHERE `id`=?";
    
    // Call the delete function with the correct parameters
    $res = deletei($sql, $values, $datatypes);
    
    echo $res; // Output the number of affected rows
}




