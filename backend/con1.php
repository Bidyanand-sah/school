<?php
$host = "sql110.infinityfree.com";  // ya jo bhi tumhara actual DB host hai
$user = "if0_42715687";              // tumhara cPanel username
$pass = "Bidyanandk506";    // MySQL Databases page pe set kiya tha
$dbname = "if0_42715687_sms_db";     // poora database naam

$conn=mysqli_connect($host,$user,$pass,$dbname) or die("connection failed");
    //$conn=mysqli_connect("localhost","root","","sms_db","3307") or die("connection failed");
    $db=mysqli_select_db($conn,$dbname) or die("database not selected".mysqli_connect_error());

    // echo "<script> alert('database connected') </script>";

    // if(!$conn1){
    //     die("connection failed due to".mysqli_connect_error());
    // }
    // echo "successfully connected database";

?>