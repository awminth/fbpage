<?php
include('../config.php');

$action = $_POST["action"];

if($action == 'show'){ 
    $limit_per_page=""; 
    if($_POST['entryvalue']==""){
        $limit_per_page=10; 
    } else{
        $limit_per_page=$_POST['entryvalue']; 
    }
    
    $page="";
    if(isset($_POST["page_no"])){
        $page=$_POST["page_no"];
    }
    else{
        $page=1;
    }

    $offset = ($page-1) * $limit_per_page;                                               
   
    $search = $_POST['search'];
    $a = "";
    if($search != ''){ 
        $a = " where r.CodeNo like '%$search%' or r.ItemName like '%$search%' ";
    }        
    $sql = "select r.* from tblremain r ".$a." 
    order by AID desc limit $offset,$limit_per_page";    
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table  class="table table-bordered table-striped responsive nowrap">
            <thead>
                <tr>
                    <th width="7%;">စဉ်</th>
                    <th>CodeNo</th>
                    <th>ItemName</th>
                    <th>Quatity</th>
                    <th>PurchasePrice</th>
                    <th>SellPrice</th>                                                                            
                    <th class="text-center">Option</th>         
                </tr>
            </thead>
            <tbody>
        ';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $color = "";
            if($row["Qty"] <= 0){
                $color = "class='bg-danger'";
            }
            $out.="<tr {$color} >
                <td>{$no}</td>
                <td>{$row["CodeNo"]}</td>
                <td>{$row["ItemName"]}</td>
                <td>{$row["Qty"]}</td>
                <td >".number_format($row["PurchasePrice"])."</td>  
                <td>".number_format($row["SellPrice"])."</td>
                               
                <td class='text-right py-0 align-middle'>
                    <div class='btn-group btn-group-sm'>
                        <a href='#' id='btnview' data-toggle='tooltip' data-placement='bottom'
                            title='ပုံကြည့်ရန်' data-img='{$row['Img']}'                                                   
                            class='btn btn-success btn-sm'><i class='fas fa-camera'></i></a>
                        <a href='#' id='btndetail' data-toggle='tooltip' data-placement='bottom'
                            title='For Order Insert Data' data-aid='{$row['AID']}'                                                   
                            class='btn btn-success btn-sm'><i class='fas fa-edit'></i></a>
                        <a href='#' id='btnbarcode' data-toggle='tooltip' data-placement='bottom'
                            title='ဘားကုဒ်ထုတ်ရန်' data-aid='{$row['AID']}'
                            data-codeno='{$row['CodeNo']}'
                            data-itemname='{$row['ItemName']}'
                            data-sellprice='{$row['SellPrice']}' class='btn btn-info btn-sm'><i
                            class='fas fa-print'></i></a>                                                
                    </div>
                </td>
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="select r.AID from tblremain r ".$a." 
        order by AID desc";
        $record = mysqli_query($con,$sql_total) or die("fail query");
        $total_record = mysqli_num_rows($record);
        $total_links = ceil($total_record/$limit_per_page);

        $out.='<div class="float-left"><p>Total Records -  ';
        $out.=$total_record;
        $out.='</p></div>';

        $out.='<div class="float-right">
                <ul class="pagination">
            ';      
        
        $previous_link = '';
        $next_link = '';
        $page_link = '';

        if($total_links > 4){
            if($page < 5){
                for($count = 1; $count <= 5; $count++)
                {
                    $page_array[] = $count;
                }
                $page_array[] = '...';
                $page_array[] = $total_links;
            }else{
                $end_limit = $total_links - 5;
                if($page > $end_limit){
                    $page_array[] = 1;
                    $page_array[] = '...';
                    for($count = $end_limit; $count <= $total_links; $count++)
                    {
                        $page_array[] = $count;
                    }
                }else{
                    $page_array[] = 1;
                    $page_array[] = '...';
                    for($count = $page - 1; $count <= $page + 1; $count++)
                    {
                        $page_array[] = $count;
                    }
                    $page_array[] = '...';
                    $page_array[] = $total_links;
                }
            }            

        }else{
            for($count = 1; $count <= $total_links; $count++)
            {
                $page_array[] = $count;
            }
        }

        for($count = 0; $count < count($page_array); $count++){
            if($page == $page_array[$count]){
                $page_link .= '<li class="page-item active">
                                    <a class="page-link" href="#">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
                                </li>';

                $previous_id = $page_array[$count] - 1;
                if($previous_id > 0){
                    $previous_link = '<li class="page-item">
                                            <a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a>
                                    </li>';
                }
                else{
                    $previous_link = '<li class="page-item disabled">
                                            <a class="page-link" href="#">Previous</a>
                                    </li>';
                }

                $next_id = $page_array[$count] + 1;
                if($next_id > $total_links){
                    $next_link = '<li class="page-item disabled">
                                        <a class="page-link" href="#">Next</a>
                                </li>';
                }else{
                    $next_link = '<li class="page-item">
                                    <a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a>
                                </li>';
                }
            }else{
                if($page_array[$count] == '...')
                {
                    $page_link .= '<li class="page-item disabled">
                                        <a class="page-link" href="#">...</a>
                                    </li> ';
                }else{
                    $page_link .= '<li class="page-item">
                                        <a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a>
                                    </li> ';
                }
            }
        }
        $out .= $previous_link . $page_link . $next_link;
        $out .= '</ul></div>';
        echo $out; 
    }else{
        $out.='
        <table  class="table table-bordered table-striped responsive nowrap">
            <thead>
                <tr>
                    <th width="7%;">စဉ်</th>
                    <th>CodeNo</th>
                    <th>ItemName</th>
                    <th>Quatity</th>
                    <th>PurchasePrice</th>
                    <th>SellPrice</th>                                                                            
                    <th class="text-center">Option</th>         
                </tr>
            </thead>
            <tbody>
            <tr>
                    <td colspan="7" class="text-center">No data</td>
                </tr>
            </tbody>
        </table>
        ';
        echo $out;
    }

}

if($action == 'editprepare'){
    $aid = $_POST["aid"];
    $sql = "select r.*,s.Supplier,c.Category 
    from tblremain r,tblsupplier s,tblcategory c 
    where r.SupplierID=s.AID and r.CategoryID=c.AID and r.AID=$aid";
    $result = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $out.="<div class='modal-body'>
                    <input type='hidden' id='aid' name='aid' value='{$row['AID']}'/>                    
                    <input type='hidden' id='action' name='action' value='update'/>
                    <div class='row'>  
                        <div class='col-6'>                                            
                            <div class='form-group'>
                                <label for='usr'> Code No :</label>
                                <input type='text' class='form-control border-success' id='codeno' name='codeno' value='{$row['CodeNo']}'>
                            </div>
                        </div>
                        <div class='col-6'>
                            <div class='form-group'>
                                <label for='usr'>Item Name :</label>
                                <input type='text' value='{$row['ItemName']}' required class='form-control border-success' name='itemname'
                                    id='itemname'>
                            </div>
                        </div>
                    </div> 
                    <div class='row'>  
                        <div class='col-6'>                                            
                            <div class='form-group'>
                                <label for='usr'> Quatity :</label>
                                <input type='text' class='form-control border-success' id='qty' name='qty' value='{$row['Qty']}'>
                            </div>
                        </div>
                        <div class='col-6'>
                            <div class='form-group'>
                                <label for='usr'>Purchase Price :</label>
                                <input type='text' value='{$row['PurchasePrice']}' required class='form-control border-success' name='purprice'
                                    id='purprice'>
                            </div>
                        </div>
                    </div>
                    <div class='row'>  
                        <div class='col-6'>                                            
                            <div class='form-group'>
                                <label for='usr'> Sell Price :</label>
                                <input type='text' class='form-control border-success' id='selprice' name='selprice' value='{$row['SellPrice']}'>
                            </div>
                        </div>
                        <div class='col-6'>
                            <div class='form-group'>
                                <label for='usr'>Category :</label>
                                <select class='form-control border-success' id='catid' name='catid'> 
                                    <option value='{$row["CategoryID"]}'>{$row["Category"]}</option> ";                                    
                            $out.= load_category();
                 $out.="       </select>
                            </div>
                        </div>
                    </div>
                    <div class='row'>  
                        <div class='col-6'>                                            
                            <div class='form-group'>
                                <label for='usr'> Supplier :</label>
                                <select class='form-control border-success' id='supid' name='supid'> 
                                    <option value='{$row["SupplierID"]}'>{$row["Supplier"]}</option> ";                                    
                            $out.= load_supplier();
                 $out.="       </select>
                            </div>
                        </div>
                        <div class='col-6'>
                            <div class='form-group'>
                            <label for='usr'> Image :</label>
                            <div class='border border-success p-1'>
                                <input type='file' name='file' id='file'>
                            </div>
                    </div>
                        </div>
                    </div>                                                                     
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnupdate' class='btn btn-success'><i class='fas fa-edit'></i>  ပြင်ဆင်မည်</button>
                </div>";
        }
        echo $out;
    }
}

if($action == 'update'){
    $aid = $_POST["aid"];    
    $codeno = $_POST["codeno"];
    $itemname = $_POST["itemname"];
    $qty = $_POST["qty"];
    $purprice = $_POST["purprice"];
    $selprice = $_POST["selprice"];
    $catid = $_POST["catid"];
    $supid = $_POST["supid"];       
    $sql="update tblremain set CodeNo='{$codeno}',ItemName='{$itemname}',
    Qty='{$qty}',PurchasePrice='{$purprice}',SellPrice='{$selprice}',
    CategoryID='{$catid}',SupplierID='{$supid}' where AID=$aid";    
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["eadmin_username"]."သည် လက်ကျန်စာရင်း အား update လုပ်သွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}

if($action == 'delete'){
    $aid = $_POST["aid"];
    $path = $_POST["path"];
    unlink(root.'upload/purchase/'.$path);
    $sql = "delete from tblremain where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["eadmin_username"]." သည် လက်ကျန်စာရင်း အားဖျက်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }    
}

if($action == 'photoshow'){
    $aid = $_POST["aid"]; 
    $sql = "select Img from tblremain where AID=$aid";
    $result = mysqli_query($con,$sql);
    $out = "";
    
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $url=roothtml.'upload/purchase/'.$row["Img"];
            $out.="<div class='modal-body'> 
                    <div class='form-group'>
                        <label for='usr'> Image :</label><br>
                        <img src='$url' style='width:100%;height:220px;' />
                    </div>                                                    
                </div>";
        }
        echo $out;
    }
    
}

if($action == 'excel'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){ 
        $a = " where r.CodeNo like '%$search%' or r.ItemName like '%$search%' ";
    }        
    $sql = "select r.* from tblremain r ".$a." 
    order by AID desc";  
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "RemainReport-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0)
    {
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="6" align="center"><h3>လက်ကျန်စာရင်း</h3></td>
            </tr>
            <tr><td colspan="6"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">CodeNo</th>  
                <th style="border: 1px solid ;">ItemName</th>  
                <th style="border: 1px solid ;">Qty</th>
                <th style="border: 1px solid ;">PurchasePrice</th>  
                <th style="border: 1px solid ;">SellPrice</th>  
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["CodeNo"].'</td>  
                    <td style="border: 1px solid ;">'.$row["ItemName"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Qty"].'</td>
                    <td style="border: 1px solid ;">'.number_format($row["PurchasePrice"]).'</td>  
                    <td style="border: 1px solid ;">'.number_format($row["SellPrice"]).'</td>
                </tr>';
        }
        $out .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }else{
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="6" align="center"><h3>လက်ကျန်စာရင်း</h3></td>
            </tr>
            <tr><td colspan="6"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">CodeNo</th>  
                <th style="border: 1px solid ;">ItemName</th>  
                <th style="border: 1px solid ;">Qty</th>
                <th style="border: 1px solid ;">PurchasePrice</th>  
                <th style="border: 1px solid ;">SellPrice</th>  
            </tr>
            <tr>
                <td colspan="6" style="border: 1px solid ;" align="center">No data</td>
            </tr>';
        $out .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }   
    
}

if($action=='savedetail'){
    $remainid=$_POST['remainid'];
    $desc=$_POST['desc'];
    $spec=$_POST['spec'];
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
            $new_path = root."upload/purchase/". $new_filename;

            if($txt1!=''){
                unlink(root."upload/purchase/".$txt1);
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
            $new_path = root."upload/purchase/". $new_filename;

            if($txt2!=''){
                unlink(root."upload/purchase/".$txt2);
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
            $new_path = root."upload/purchase/". $new_filename;
            if($txt3!=''){
                unlink(root."upload/purchase/".$txt3);
            }
            if(move_uploaded_file($file,$new_path)){
                $img3= $new_filename;
            }
        }
    }else{
        $img3=$txt3;
    }

    if($_FILES['img4']['name'] != ''){
        $filename = $_FILES['img4']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['img4']['tmp_name'];
        $valid_extension = array("jpg","jpeg","png");
        if(in_array($extension,$valid_extension)){
            $new_filename = rand() .".". $extension;
            $new_path = root."upload/purchase/". $new_filename;
            if($txt4!=''){
                unlink(root."upload/purchase/".$txt4);
            }
            if(move_uploaded_file($file,$new_path)){
                $img4= $new_filename;
            }
        }
    }else{
        $img4=$txt4;
    }

    $findsql="select AID from tblremaindetail where RemainID='{$remainid}'";
    $result= mysqli_query($con,$findsql);
    if(mysqli_num_rows($result) > 0){
        $sqlupd = 'update tblremaindetail set Img1="'.$img1.'",Img2="'.$img2.'",
        Img3="'.$img3.'",Img4="'.$img4.'",Description="'.$desc.'",Specification="'.$spec.'"  
        where RemainID="'.$remainid.'"';                
        if(mysqli_query($con,$sqlupd)){
            save_log($_SESSION["eadmin_username"]."သည် purchase detail အား update လုပ်သွားသည်။");
            echo "success";
        }
        else{
            echo "fail";
        } 

    }else{
        $sqlin = 'insert into tblremaindetail (Img1,Img2,Img3,Img4,Description,
        Specification,RemainID) values ("'.$img1.'","'.$img2.'","'.$img3.'",
        "'.$img4.'","'.$desc.'","'.$spec.'","'.$remainid.'")';
        if(mysqli_query($con,$sqlin)){
            save_log($_SESSION["eadmin_username"]."သည် purchase detail အား အသစ်သွင်းသွားသည်။");
            echo "success";
        }
        else{
            echo "fail";
        } 
    }
}



?>