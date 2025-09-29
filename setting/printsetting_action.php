<?php
include('../config.php');

$action = $_POST["action"];

if($action == 'edit'){
      $shopname = $_POST["shopname"];
      $shopaddress = $_POST["shopaddress"];
      $shopemail = $_POST["shopemail"];
      $shopphno = $_POST["shopphno"];

      $sql="update tblsetting set ShopName='{$shopname}',Address='{$shopaddress}',
      Email='{$shopemail}',PhoneNo='{$shopphno}'";
      if(mysqli_query($con,$sql)){
            $_SESSION['shopname']=$shopname;
            $_SESSION['shopaddress']=$shopaddress;
            $_SESSION['shopphno']=$shopphno;
            $_SESSION['shopemail']=$shopemail;
            save_log($_SESSION["eadmin_username"]." သည် Print setting ကိုပြန်လည်ပြင်ဆင်သွားသည်။");
            echo 1;
      }else{
            echo 0;
      }

}