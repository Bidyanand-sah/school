<?php

    $conn=mysqli_connect("localhost","root","","sms_db","3307") or die("connection failed");
    $db=mysqli_select_db($conn,"sms_db") or die("database not selected".mysqli_connect_error());

    // echo "<script> alert('database connected') </script>";

    // if(!$conn1){
    //     die("connection failed due to".mysqli_connect_error());
    // }
    // echo "successfully connected database";

?>