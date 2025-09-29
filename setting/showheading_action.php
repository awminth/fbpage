<?php
include('../config.php');

$action = $_POST["action"];

if($action=='savedetail'){
    $aid = $_POST['aid'];
    $heading1=$_POST['heading1'];
    $subheading1=$_POST['subheading1'];
    $heading2=$_POST['heading2'];
    $subheading2=$_POST['subheading2'];
    $heading3=$_POST['heading3'];
    $subheading3=$_POST['subheading3'];
    $txt1=$_POST['txt1'];
    $txt2=$_POST['txt2'];
    $txt3=$_POST['txt3'];
    $txt4=$_POST['txt4'];
    $img1="";
    $img2="";
    $img3="";
    $img4="";   
    if($_FILES['img1']['name'] != ''){
        $filename = $_FILES['img1']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['img1']['tmp_name'];
        $valid_extension = array("jpg","jpeg","png");
        if(in_array($extension,$valid_extension)){
            $new_filename = rand() .".". $extension;
            $new_path = root."upload/heading/". $new_filename;

            if($txt1!=''){
                unlink(root."upload/heading/".$txt1);
            }
            if(move_uploaded_file($file,$new_path)){
                $img1= $new_filename;
            }
        }
    }else{
        $img1=$txt1;
    }

    if($_FILES['img2']['name'] != ''){
        $filename = $_FILES['img2']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['img2']['tmp_name'];
        $valid_extension = array("jpg","jpeg","png");
        if(in_array($extension,$valid_extension)){
            $new_filename = rand() .".". $extension;
            $new_path = root."upload/heading/". $new_filename;

            if($txt2!=''){
                unlink(root."upload/heading/".$txt2);
            }
            if(move_uploaded_file($file,$new_path)){
                $img2= $new_filename;
            }
        }
    }else{
        $img2=$txt2;
    }

    if($_FILES['img3']['name'] != ''){
        $filename = $_FILES['img3']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['img3']['tmp_name'];
        $valid_extension = array("jpg","jpeg","png");
        if(in_array($extension,$valid_extension)){
            $new_filename = rand() .".". $extension;
            $new_path = root."upload/heading/". $new_filename;
            if($txt3!=''){
                unlink(root."upload/heading/".$txt3);
            }
            if(move_uploaded_file($file,$new_path)){
                $img3= $new_filename;
            }
        }
    }else{
        $img3=$txt3;
    }    
    $userid = $_SESSION["eadmin_userid"];
    $dt = date("Y-m-d");
    $sqlupd = 'update tblheading set Img1="'.$img1.'",Img2="'.$img2.'",Img3="'.$img3.'",
    Heading1="'.$heading1.'",Heading2="'.$heading2.'",Heading3="'.$heading3.'",SubHeading1="'.$subheading1.'",
    SubHeading2="'.$subheading2.'",SubHeading3="'.$subheading3.'",UserID="'.$userid.'",Date="'.$dt.'" 
    where AID="'.$aid.'"';                
    if(mysqli_query($con,$sqlupd)){
        save_log($_SESSION["eadmin_username"]."သည် show header အား update လုပ်သွားသည်။");
        echo "success";
    }
    else{
        echo "fail";
    } 
}



?>