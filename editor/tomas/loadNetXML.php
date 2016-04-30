<?php
session_start();

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $netID = test($_POST['netID']);
	
    $conn = mysqli_connect("mariadb101.websupport.sk", "workflow", "workflow", "workflow", 3312);
    if(! $conn ){
        die('Could not connect: ' . mysql_error());
    }
    
    $sql = "SELECT xml_file FROM petri_net WHERE id='$netID'";
    mysqli_query($conn, "SET CHARACTER SET utf8");
    $query = mysqli_query($conn, $sql);
    
    $row = mysqli_fetch_assoc($query);
    $netXML = $row['xml_file'];

    echo $netXML;
}

function test($data){
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}