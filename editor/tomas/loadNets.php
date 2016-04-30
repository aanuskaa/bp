<?php
session_start();

if($_SERVER['REQUEST_METHOD'] === "POST"){
    //$idUser = $_SESSION['User'];
    $idUser = 1;
    $netsToRet = [];
    
    class NetInfo{
        public $netID;
        public $name;
    }

    
    $conn = mysqli_connect("mariadb101.websupport.sk", "workflow", "workflow", "workflow", 3312);
    if(! $conn ){
        die('Could not connect: ' . mysql_error());
    }

    $sql = "SELECT id, name FROM petri_net WHERE created_by='$idUser'";
    mysqli_query($conn, "SET CHARACTER SET utf8");
    $query = mysqli_query($conn, $sql);
    
    while($row = mysqli_fetch_assoc($query)){
        $item = new NetInfo();
        $item->netID = $row['id'];
        $item->name = $row['name'];
        
        $netsToRet[] = $item;
    }
    
    echo json_encode($netsToRet);
}