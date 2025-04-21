<?php
include "../../db.php";

echo "Delete page";
if(isset($_GET['id'])){
    $id=$_GET['id'];
    echo $id;
    $sql="DELETE FROM ORDERS WHERE ID = $id";
    $result = $conn->query($sql);
    echo $result;
    if($result){
        echo " deleted successfully";
    }
     else {
        echo"delete does not successfull" . $conn->error;
    }   
    header("Location:orders.php");
    $conn->close();

    exit();
   
    }
    else{
        echo "id not found";
    }


?>
