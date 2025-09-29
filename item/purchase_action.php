<?php
include('../config.php');

if($_POST["action"] == 'show'){ 
    $limit_per_page=""; 
    if($_POST['entryvalue']==""){
        $limit_per_page=10; 
    }else{
        $limit_per_page=$_POST['entryvalue']; 
    }
    
    $page="";
    if(isset($_POST["page_no"])){
        $page=$_POST["page_no"];
    }else{
        $page=1;
    }

    $offset = ($page-1) * $limit_per_page;                                               
   
    $search = $_POST['search'];
    $a = "";
    if($search != ''){ 
        $a = " and p.CodeNo like '%$search%' or p.ItemName like '%$search%' ";
    }   
    $dtfrom = $_POST['dtfrom'];
    $dtto = $_POST['dtto'];
    $b = "";
    if($dtfrom!='' || $dtto!=''){
        $b = " and p.Date>='{$dtfrom}' and p.Date<='{$dtto}' ";
    }
    $codeno = $_POST['codeno'];
    $c = "";
    if($codeno != ""){
        $c = " and p.CodeNo='{$codeno}' ";
    }     
    $sql="select p.* 
    from tblpurchase p 
    where AID is not null ".$a.$b.$c." 
    order by AID desc limit $offset,$limit_per_page";   
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
            <thead>
                <tr>
                    <th width="7%;">စဉ်</th>
                    <th>CodeNo</th>
                    <th>ItemName</th>
                    <th>Quatity</th>
                    <th>PurchasePrice</th>
                    <th>SellPrice</th>                                                    
                    <th width="7%">Image</th>
                    <th>Date</th>
                    <th width="10%;" class="text-center">Action</th>           
                </tr>
            </thead>
            <tbody>
        ';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["CodeNo"]}</td>
                <td>{$row["ItemName"]}</td>
                <td>{$row["Qty"]}</td>
                <td>".number_format($row["PurchasePrice"])."</td>  
                <td>".number_format($row["SellPrice"])."</td>                              
                <td>
                    <a href='#' id='btnview' class='dropdown-item'
                        data-aid='{$row['AID']}' data-toggle='modal'
                        data-target='#viewmodal'><i class='fas fa-camera text-primary'
                        style='font-size:15px;'></i>
                    </a>
                </td>
                <td>".enDate($row["Date"])."</td>
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                        <div class='dropdown-menu'>
                            <a href='#' id='btnedit' class='dropdown-item'
                                data-aid='{$row['AID']}' data-toggle='modal'
                                data-target='#editmodal'><i class='fas fa-edit text-primary'
                                style='font-size:13px;'></i>
                                Edit</a>
                            <div class='dropdown-divider'></div>
                            <a href='#' id='btndelete' class='dropdown-item'
                                data-aid='{$row['AID']}' 
                                data-codeno='{$row['CodeNo']}'
                                data-path='{$row['Img']}'><i
                                class='fas fa-trash text-danger' style='font-size:13px;'></i>
                                Delete</a>                           
                        </div>
                    </div>
                </td>
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="select p.AID 
        from tblpurchase p 
        where AID is not null ".$a.$b.$c." 
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
        <table class="table table-bordered table-striped responsive nowrap">
            <thead>
                <tr>
                    <th width="7%;">စဉ်</th>
                    <th>CodeNo</th>
                    <th>ItemName</th>
                    <th>Quatity</th>
                    <th>PurchasePrice</th>
                    <th>SellPrice</th>                                                    
                    <th width="7%">Image</th>
                    <th>Date</th>
                    <th width="10%;">Action</th>           
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="9" class="text-center">No data</td>
                </tr>
            </tbody>
        </table>
        ';
        echo $out;
    }
}

if($_POST["action"] == 'save'){
    $codeno = $_POST["codeno"];
    $itemname = $_POST["itemname"];
    $qty = $_POST["qty"];
    $purprice = $_POST["purprice"];
    $selprice = $_POST["selprice"];
    $catid = $_POST["category"];
    $supid = $_POST["supplier"];
    $date = $_POST["date"];
    $totalpurprice=$qty*$purprice;

    if($_FILES['file']['name'] != ''){
        $filename = $_FILES['file']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['file']['tmp_name'];
        $valid_extension = array("jpg","jpeg","png");
        if(in_array($extension,$valid_extension)){
            $new_filename = rand() .".". $extension;
            $new_path = root."upload/purchase/". $new_filename;

            if(move_uploaded_file($file,$new_path)){

                $sql = "insert into tblpurchase (CodeNo,ItemName,Qty,PurchasePrice,SellPrice,CategoryID,SupplierID,Date,Img) values ('{$codeno}','{$itemname}','{$qty}','{$purprice}','{$selprice}','{$catid}','{$supid}','{$date}','{$new_filename}')";
                if(mysqli_query($con,$sql)){
                    $purchaseid = mysqli_insert_id($con);
                    save_supplier_detail($purchaseid,$supid,$totalpurprice,$date);
                    
                    save_log($_SESSION["eadmin_username"]." သည် purchase အားအသစ်သွင်းသွားသည်။");

                    $findsql="select AID,Qty from tblremain where CodeNo='{$codeno}'";
                    $result= mysqli_query($con,$findsql);
                    if(mysqli_num_rows($result) > 0){
                        $row = mysqli_fetch_array($result);
                        $totalqty=$row["Qty"] + $qty;

                        $sqlupd = "update tblremain set ItemName='{$itemname}',Qty='{$totalqty}',PurchasePrice='{$purprice}',SellPrice='{$selprice}',CategoryID='{$catid}',SupplierID='{$supid}' where CodeNo='{$codeno}'";                
                        if(mysqli_query($con,$sqlupd)){
                            echo "success";
                        }
                        else{
                            echo "fail";
                        }                        
                    }
                    else{
                        $sqlin = "insert into tblremain (CodeNo,ItemName,Qty,PurchasePrice,SellPrice,CategoryID,SupplierID,Img) values ('{$codeno}','{$itemname}','{$qty}','{$purprice}','{$selprice}','{$catid}','{$supid}','{$new_filename}')";
                        if(mysqli_query($con,$sqlin)){
                            echo "success";
                        }
                        else{
                            echo "fail";
                        } 
                    } 

                }

            }
        }
        else{
            echo "wrongtype";
        }
    }
    else{
        $sql = "insert into tblpurchase (CodeNo,ItemName,Qty,PurchasePrice,SellPrice,CategoryID,SupplierID,Date) values ('{$codeno}','{$itemname}','{$qty}','{$purprice}','{$selprice}','{$catid}','{$supid}','{$date}')";
        if(mysqli_query($con,$sql)){
            $purchaseid = mysqli_insert_id($con);
            save_supplier_detail($purchaseid,$supid,$totalpurprice,$date);

            save_log($_SESSION["eadmin_username"]." သည် purchase အားအသစ်သွင်းသွားသည်။");

            $findsql="select AID,Qty from tblremain where CodeNo='{$codeno}'";
            $result= mysqli_query($con,$findsql);
            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_array($result);
                $totalqty=$row["Qty"] + $qty;

                $sqlupd = "update tblremain set ItemName='{$itemname}',Qty='{$totalqty}',PurchasePrice='{$purprice}',SellPrice='{$selprice}',CategoryID='{$catid}',SupplierID='{$supid}' where CodeNo='{$codeno}'";                
                if(mysqli_query($con,$sqlupd)){
                    echo "success";
                }
                else{
                    echo "fail";
                }                        
            }
            else{
                $sqlin = "insert into tblremain (CodeNo,ItemName,Qty,PurchasePrice,SellPrice,CategoryID,SupplierID) values ('{$codeno}','{$itemname}','{$qty}','{$purprice}','{$selprice}','{$catid}','{$supid}')";
                if(mysqli_query($con,$sqlin)){
                    echo "success";
                }
                else{
                    echo "fail";
                } 
            } 
        
        }else{
            echo "fail";
        }
    }   
    
}

if($_POST["action"] == 'editprepare'){
    $aid = $_POST["aid"];
    $sql = "select p.* from tblpurchase p where p.AID=$aid";
    $result = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $out.="<div class='modal-body'>
                    <input type='hidden' id='aid' name='aid' value='{$row['AID']}'/>
                    <input type='hidden' id='hidcodeno' name='hidcodeno' value='{$row['CodeNo']}'/>                    
                    <div class='row'>  
                        <div class='col-sm-6'>                                            
                            <div class='form-group'>
                                <label for='usr'> Code No :</label>
                                <input type='text' class='form-control border-success' readonly id='codeno' name='codeno' value='{$row['CodeNo']}'>
                            </div>
                        </div>
                        <div class='col-sm-6'>
                            <div class='form-group'>
                                <label for='usr'>Item Name :</label>
                                <input type='text' value='{$row['ItemName']}' required class='form-control border-success' name='itemname'
                                    id='itemname'>
                            </div>
                        </div>
                    </div> 
                    <div class='row'>  
                        <div class='col-sm-6'>                                            
                            <div class='form-group'>
                                <label for='usr'> Quatity :</label>
                                <input type='number' class='form-control border-success' id='qty' name='qty' value='{$row['Qty']}'>
                            </div>
                        </div>
                        <div class='col-sm-6'>
                            <div class='form-group'>
                                <label for='usr'>Purchase Price :</label>
                                <input type='number' value='{$row['PurchasePrice']}' required class='form-control border-success' name='purprice'
                                    id='purprice'>
                            </div>
                        </div>
                    </div>
                    <div class='row'>  
                        <div class='col-sm-6'>                                            
                            <div class='form-group'>
                                <label for='usr'> Sell Price :</label>
                                <input type='number' class='form-control border-success' id='selprice' name='selprice' value='{$row['SellPrice']}'>
                            </div>
                        </div>
                        <div class='col-sm-6'>
                            <div class='form-group'>
                                <label for='usr'>Category :</label>
                                <select class='form-control border-success' id='catid' name='catid'> 
                                    <option value='{$row["CategoryID"]}'>".GetString("select Category from tblcategory where AID={$row["CategoryID"]}")."</option> ";                                    
                            $out.= load_category();
                 $out.="       </select>
                            </div>
                        </div>
                    </div>
                    <div class='row'>  
                        <div class='col-sm-6'>                                            
                            <div class='form-group'>
                                <label for='usr'> Supplier :</label>
                                <select class='form-control border-success' id='supid' name='supid'> 
                                    <option value='{$row["SupplierID"]}'>".GetString("select Supplier from tblsupplier where AID={$row["SupplierID"]}")."</option> ";                                    
                            $out.= load_supplier();
                 $out.="       </select>
                            </div>
                        </div>
                        <div class='col-sm-6'>
                            <div class='form-group'>
                                <label for='usr'>Date :</label>
                                <input type='date' value='{$row['Date']}' required class='form-control border-success' name='date'
                                    id='date'>
                            </div>
                        </div>
                    </div>                                                                    
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnupdate' class='btn btn-{$color}'><i class='fas fa-edit'></i>  ပြင်ဆင်မည်</button>
                </div>";
        }
        echo $out;
    }
}

if($_POST["action"] == 'update'){
    $aid = $_POST["aid"];    
    $codeno = $_POST["codeno"];
    $itemname = $_POST["itemname"];
    $qty = $_POST["qty"];
    $purprice = $_POST["purprice"];
    $selprice = $_POST["selprice"];
    $catid = $_POST["catid"];
    $supid = $_POST["supid"];
    $date = $_POST["date"];
    $totalpurprice=$qty*$purprice;

    $sql = "update tblpurchase set ItemName='{$itemname}',Qty='{$qty}',PurchasePrice='{$purprice}',SellPrice='{$selprice}',CategoryID='{$catid}',SupplierID='{$supid}',Date='{$date}' where AID=$aid";
    if(mysqli_query($con,$sql)){
        $purchaseid = $aid;
        save_supplier_detail($purchaseid,$supid,$totalpurprice,$date);
        
        save_log($_SESSION["eadmin_username"]."သည် purchase အား update လုပ်သွားသည်။");

        $totalqty=GetString("select sum(Qty) from tblpurchase where CodeNo='{$codeno}'");
        $sqlupd = "update tblremain set ItemName='{$itemname}',Qty='{$totalqty}',PurchasePrice='{$purprice}',SellPrice='{$selprice}',CategoryID='{$catid}',SupplierID='{$supid}' where CodeNo='{$codeno}'";
        
        if(mysqli_query($con,$sqlupd)){
            echo "success"; 
        }
        else{
            echo "fail";
        }
    }
    else{
        echo "fail";
    }   
    
}

if($_POST["action"] == 'delete'){
    $aid = $_POST["aid"];
    $codeno = $_POST["codeno"];
    $path = $_POST["path"];
   
    $cnt = GetString("select count(CodeNo) from tblpurchase where CodeNo='{$codeno}'"); 
    if($cnt == 1){
        $sqlremain = "delete from tblremain where CodeNo='{$codeno}'";
        if(mysqli_query($con,$sqlremain)){
           
            $sqlpurchase = "delete from tblpurchase where AID=$aid";
            if(mysqli_query($con,$sqlpurchase)){
                delete_supplier_detail($aid);
                save_log($_SESSION["eadmin_username"]." သည် purchase အားဖျက်သွားသည်။");
                try {
                    //code...
                    unlink(root.'upload/purchase/'.$path);
                } catch (\Throwable $th) {
                    //throw $th;
                    echo 1;
                }
               
                echo 1;
            }else{
                echo 0;
            }
        }
        else{
            echo 0;
        }
    }
    else{
        $qtyPur = "select Qty from tblpurchase where AID=$aid";
        $resPur= mysqli_query($con,$qtyPur);
        if(mysqli_num_rows($resPur) > 0){
            $rowPur = mysqli_fetch_array($resPur);

            $sqlRem = "select Qty,CodeNo from tblremain where CodeNo='{$codeno}'";
            $resRem = mysqli_query($con,$sqlRem);
            if(mysqli_num_rows($resRem) > 0){
                $rowRem = mysqli_fetch_array($resRem);
                $totalqty = $rowRem["Qty"] - $rowPur["Qty"];

                $sqlUpdRem = "update tblremain set Qty='{$totalqty}' where CodeNo='{$codeno}'";
                if(mysqli_query($con,$sqlUpdRem)){
                   
                    $sqlDelPur = "delete from tblpurchase where AID=$aid";
                    if(mysqli_query($con,$sqlDelPur)){
                        delete_supplier_detail($aid);
                        save_log($_SESSION["eadmin_username"]." သည် purchase အားဖျက်သွားသည်။");        
                        echo 1;
                    }
                    else{
                        echo 0;
                    }
                }
            }
            
        }
    }  
    
}    

if($_POST["action"] == 'photoprepare'){
    $aid = $_POST["aid"]; 
    $sql = "select AID,Img,CodeNo from tblpurchase where AID=$aid";
    $result = mysqli_query($con,$sql);
    $out = "";
    $url=roothtml.'upload/purchase/';
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $out.="<div class='modal-body'>
                <input type='hidden' id='aid' name='aid' value='{$row['AID']}'/> 
                <input type='hidden' id='codeno1' name='codeno1' value='{$row['CodeNo']}'/> 
                <input type='hidden' id='action' name='action' value='photosave' />                              
                    <div class='form-group'>
                        <label for='usr'> Image :</label><br>
                        <img src='$url{$row['Img']}' style='width:100%;height:220px;' />
                    </div>
                    <div class='form-group'>
                        <label for='usr'> Change Image :</label><br>
                        <div class='border border-success p-1'>
                            <input type='file' name='pfile' id='pfile'>
                        </div>
                    </div>                                
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnphotoupdate' class='btn btn-success'><i class='fas fa-edit'></i>  ပြင်ဆင်မည်</button>
                </div>";
        }
        echo $out;
    }    
}

if($_POST["action"] == 'photosave'){
    $aid = $_POST["aid"];
    $codeno = $_POST["codeno1"];
    if($_FILES['pfile']['name'] != ''){
        $filename = $_FILES['pfile']['name'];        
        $extension = pathinfo($filename,PATHINFO_EXTENSION);
        $file = $_FILES['pfile']['tmp_name'];
        $valid_extension = array("jpg","jpeg","png");
        if(in_array($extension,$valid_extension)){
            $new_filename = rand() .".". $extension;
            $new_path = root."upload/purchase/". $new_filename;
            if(move_uploaded_file($file,$new_path)){
                $sql="update tblpurchase set Img='{$new_filename}' where AID=$aid";
                save_log($_SESSION["eadmin_username"]."သည် purchase photo အား update လုပ်သွားသည်။");

                if(mysqli_query($con,$sql)){
                    $sqlremain="update tblremain set Img='{$new_filename}' where CodeNo='{$codeno}'";
                    if(mysqli_query($con,$sqlremain)){
                        echo "success"; 
                    }else{
                        echo "fail";
                    } 
                }else{
                    echo "fail";
                }
            }
        }else{
            echo "wrongtype";
        }
    }else{
        echo "nofile";
    }
}

if($_POST["action"] == 'excel'){
    $search=$_POST['ser'];
    $a = "";
    if($search != ''){ 
        $a = " where p.CodeNo like '%$search%' or p.ItemName like '%$search%' ";
    }        
    $sql="select p.* 
    from tblpurchase p ".$a." 
    order by AID desc";  
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "PurchaseReport-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="7" align="center"><h3>အဝယ်စာရင်း</h3></td>
            </tr>
            <tr><td colspan="7"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">CodeNo</th>  
                <th style="border: 1px solid ;">ItemName</th>  
                <th style="border: 1px solid ;">Qty</th>
                <th style="border: 1px solid ;">PurchasePrice</th>  
                <th style="border: 1px solid ;">SellPrice</th>                
                <th style="border: 1px solid ;">Date</th>       
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
                    <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>                  
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
                <td colspan="7" align="center"><h3>အဝယ်စာရင်း</h3></td>
            </tr>
            <tr><td colspan="7"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">CodeNo</th>  
                <th style="border: 1px solid ;">ItemName</th>  
                <th style="border: 1px solid ;">Qty</th>
                <th style="border: 1px solid ;">PurchasePrice</th>  
                <th style="border: 1px solid ;">SellPrice</th>                
                <th style="border: 1px solid ;">Date</th>       
            </tr>
            <tr>
                <td colspan="7" style="border: 1px solid ;" align="center">No data</td>
            </tr>';
        $out .= '</table>'; 
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }  
    
}



?>