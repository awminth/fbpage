<?php
include('../config.php');
$userid=$_SESSION["eadmin_userid"];

if($_POST["action"] == 'showmain'){ 
   $out="";
   $supplier=$_POST['supplier'];
   $name=$_POST['name'];
   $sql1="select Sum(Amt) from tblsupplierpay where SupplierID={$supplier}";
   $totalpay=GetInt($sql1);

   $sql2="select Sum(Amt) from tblsupplierdetail where SupplierID={$supplier}";
   $totaldetail=GetInt($sql2);

   $totalremain=$totalpay-$totaldetail;
   
   $out.='
    <div class="form-group row">
        <label class="col-sm-4" for="usr">Supplier name :</label>
        <input type="text" class="col-sm-8 form-control border-primary"
             value="'.$name.'" readonly >
    </div>
    <div class="form-group row">
        <label class="col-sm-4" for="usr">Total Amount :</label>
        <input type="text" class="col-sm-8 form-control text-right border-primary"
             value="'.number_format($totaldetail).'" readonly >
    </div>
    <div class="form-group row">
        <label class="col-sm-4" for="usr">Total Pay :</label>
        <input type="text" class="col-sm-8 form-control text-right border-primary"
             value="'.number_format($totalpay).'"  readonly>
    </div>
    <div class="form-group row">
        <label class="col-sm-4" for="usr">Total Remain :</label>
        <input type="text" class="col-sm-8 form-control text-right border-primary"
            value="'.number_format($totalremain).'" readonly >
    </div>

   ';

   echo $out;
}

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
        $a = " and ( s.Name like '%$search%' or p.ItemName like '%$search%') ";
    }  
    
    $from=$_POST['from'];
    $to=$_POST['to'];
    $c="";
    if($from!='' || $to!=''){
        $c=" and sp.Date>='{$from}' and sp.Date<='{$to}' ";
    }
    $supplier=$_POST['supplier'];
    $d="";
    if($supplier!=""){
        $d=" and sp.SupplierID={$supplier} ";
    }
    $sql="select sp.*,s.Supplier as sname  
    from tblsupplierpay sp,tblsupplier s where 
     sp.SupplierID=s.AID  ".$c.$d.$a." 
    order by sp.AID desc limit $offset,$limit_per_page";    
    
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
            <thead>
                <tr>
                    <th width="7%;">စဉ်</th>
                    <th>Supplier Name</th>
                    <th>Total Amount</th>  
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
                <td>{$row["sname"]}</td>
                <td>".number_format($row["Amt"])."</td>  
               
                <td>".enDate($row["Date"])."</td>
                <td class='text-center'>
                    <div class='dropdown dropleft'>
                    <a data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fas fa-ellipsis-h text-primary' style='font-size:22px;cursor:pointer;'></i>
                    </a>
                        <div class='dropdown-menu'>
                            <a href='#' id='btneditprepare' class='dropdown-item'
                                data-aid='{$row['AID']}' data-toggle='modal'
                                data-target='#editmodal'><i class='fas fa-edit text-primary'
                                style='font-size:13px;'></i>
                                Edit</a>
                            <div class='dropdown-divider'></div>
                            <a href='#' id='btndelete' class='dropdown-item'
                                data-aid='{$row['AID']}' ><i
                                class='fas fa-trash text-danger' style='font-size:13px;'></i>
                                Delete</a>                           
                        </div>
                    </div>
                </td>
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="select sp.AID  
        from tblsupplierpay sp,tblsupplier s where 
         sp.SupplierID=s.AID  ".$c.$d.$a." 
        order by sp.AID desc ";
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
                    <th>Supplier Name</th>
                    <th>Total Amount</th>  
                    <th>Date</th>
                    <th width="10%;" class="text-center">Action</th>           
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="text-center">No data</td>
                </tr>
            </tbody>
        </table>
        ';
        echo $out;
    }
}

if($_POST["action"] == 'save'){
    $amt=$_POST['amt'];
    $supplier = $_POST["supplier"];
    $date = $_POST["date"];
  
   $sql="insert into tblsupplierpay (SupplierID,Amt,Date,UserID) values 
   ('{$supplier}','{$amt}','{$date}','{$userid}')";
   if(mysqli_query($con,$sql)){
    save_log($_SESSION["eadmin_username"]."သည် Supplier Pay အား Savae လုပ်သွားသည်။");
    echo 1;
   }else{
    echo 0;
   } 
    
}

if($_POST["action"] == 'editprepare'){
    $aid = $_POST["aid"];
    $sql = "select p.*,s.Supplier as sname from tblsupplierpay p,tblsupplier s where p.AID=$aid";
    $result = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_array($result);
            $out.='
            <input type="hidden" name="aid" value="'.$aid.'"/>
                <div class="modal-body" data-spy="scroll" data-offset="50">
                        <div class="form-group">
                            <label for="usr">Supplier Name:</label>
                            <select class="form-control border-success" name="esupplierpay1">
                                <option value="'.$row['SupplierID'].'">'.$row['sname'].'</option>
                                                '.load_supplier().'>
            </select>
            </div>
            <div class="form-group">
                <label for="usr">Amount:</label>
                <input type="number" value="'.$row['Amt'].'" class="form-control border-success" name="eamt" placeholder="Amount">
            </div>
            <div class="form-group">
                <label for="usr">Date:</label>
                <input type="date" class="form-control border-success" name="edate" value="'.$row['Date'].'">
            </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btnedit" class="btn btn-'.$color.'"><i class="fas fa-save"></i>
                    ပြင်ဆင်မည်</button>
            </div>

            ';

            }
    echo $out;
}

if($_POST["action"] == 'edit'){
    $amt=$_POST['amt'];
    $supplier = $_POST["supplier"];
    $date = $_POST["date"];
    $aid=$_POST['aid'];
  
   $sql="update tblsupplierpay set SupplierID='{$supplier}',Amt='{$amt}',Date='{$date}',
   UserID='{$userid}' where AID='{$aid}'";
  
   if(mysqli_query($con,$sql)){
    save_log($_SESSION["eadmin_username"]."သည် Supplier Pay အား Update လုပ်သွားသည်။");
    echo 1;
   }else{
    echo 0;
   } 
    
}

if($_POST["action"] == 'delete'){    
    $aid=$_POST['aid'];
  
   $sql="delete from tblsupplierpay where AID='{$aid}'";
  
   if(mysqli_query($con,$sql)){
    save_log($_SESSION["eadmin_username"]."သည် Supplier Pay အား Delete လုပ်သွားသည်။");
    echo 1;
   }else{
    echo 0;
   } 
    
}


if($_POST["action"] == 'excel'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){ 
        $a = " and ( s.Name like '%$search%' or p.ItemName like '%$search%') ";
    }  
    
    $from=$_POST['hfrom'];
    $to=$_POST['hto'];
    $c="";
    if($from!='' || $to!=''){
        $c=" and sp.Date>='{$from}' and sp.Date<='{$to}' ";
    }
    $supplier=$_POST['hsupplier1'];
    $d="";
    if($supplier!=""){
        $d=" and sp.SupplierID={$supplier} ";
    }
    $sql="select sp.*,s.Supplier as sname  
    from tblsupplierpay sp,tblsupplier s where 
     sp.SupplierID=s.AID  ".$c.$d.$a." 
    order by sp.AID desc "; 
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "SuplierPayReport-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
    $out .= '

    <head>
        <meta charset="utf-8">
    </head>
    <table>
        <tr>
            <td colspan="4" align="center">
                <h3>Supplier Pay</h3>
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <td>
        </tr>
        <tr>
            <th style="border: 1px solid ;">စဉ်</th>
            <th style="border: 1px solid ;">Supplier Name</th>
            <th style="border: 1px solid ;">Amount</th>
            <th style="border: 1px solid ;">Date</th>
        </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result)){
        $no = $no + 1;
        $out .= '
        <tr>
            <td style="border: 1px solid ;">'.$no.'</td>
            <td style="border: 1px solid ;">'.$row["sname"].'</td>
            <td style="border: 1px solid ;">'.number_format($row["Amt"]).'</td>
            <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>
        </tr>';
        }
        $out .= '
    </table>';
    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename='.$fileName);
    echo $out;
    }else{
    $out .= '

    <head>
        <meta charset="utf-8">
    </head>
    <table>
        <tr>
            <td colspan="4" align="center">
                <h3>အဝယ်စာရင်း</h3>
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <td>
        </tr>
        <tr>
        <th style="border: 1px solid ;">စဉ်</th>
        <th style="border: 1px solid ;">Supplier Name</th>
        <th style="border: 1px solid ;">Amount</th>
        <th style="border: 1px solid ;">Date</th>
        </tr>
        <tr>
            <td colspan="4" style="border: 1px solid ;" align="center">No data</td>
        </tr>';
        $out .= '
    </table>';
    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename='.$fileName);
    echo $out;
    }

}

if($_POST["action"] == 'show1'){ 
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
        $a = " and ( s.Name like '%$search%' or p.ItemName like '%$search%') ";
    }  
    
    $from=$_POST['from'];
    $to=$_POST['to'];
    $c="";
    if($from!='' || $to!=''){
        $c=" and sp.Date>='{$from}' and sp.Date<='{$to}' ";
    }
    $supplier=$_POST['supplier'];
    $d="";
    if($supplier!=""){
        $d=" and sp.SupplierID={$supplier} ";
    }
    $sql="select sp.*,s.Supplier as sname,p.ItemName as pname  
    from tblsupplierdetail sp,tblsupplier s,tblpurchase p where 
     p.AID=sp.PurchaseID and sp.SupplierID=s.AID  ".$c.$d.$a." 
    order by sp.AID desc limit $offset,$limit_per_page";    
   
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
            <thead>
                <tr>
                    <th width="7%;">စဉ်</th>
                    <th>Supplier Name</th>
                    <th>Item Name</th>
                    <th>Total Amount</th>  
                    <th>Date</th>         
                </tr>
            </thead>
            <tbody>
        ';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["sname"]}</td>
                <td>{$row["pname"]}</td>
                <td>".number_format($row["Amt"])."</td>  
               
                <td>".enDate($row["Date"])."</td>
                
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="select sp.AID  
        from tblsupplierdetail sp,tblsupplier s,tblpurchase p where 
         p.AID=sp.PurchaseID and sp.SupplierID=s.AID  ".$c.$d.$a." 
        order by sp.AID desc ";
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
                    <th>Supplier Name</th>
                    <th>Total Amount</th>  
                    <th>Date</th>          
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" class="text-center">No data</td>
                </tr>
            </tbody>
        </table>
        ';
        echo $out;
    }
}

if($_POST["action"] == 'excel1'){
    $search = $_POST['ser2'];
    $a = "";
    if($search != ''){ 
        $a = " and ( s.Name like '%$search%' or p.ItemName like '%$search%') ";
    }  
    
    $from=$_POST['hfrom2'];
    $to=$_POST['hto2'];
    $c="";
    if($from!='' || $to!=''){
        $c=" and sp.Date>='{$from}' and sp.Date<='{$to}' ";
    }
    $supplier=$_POST['hsupplier2'];
    $d="";
    if($supplier!=""){
        $d=" and sp.SupplierID={$supplier} ";
    }
    $sql="select sp.*,s.Supplier as sname,p.ItemName as pname  
    from tblsupplierdetail sp,tblsupplier s,tblpurchase p where 
     p.AID=sp.PurchaseID and sp.SupplierID=s.AID  ".$c.$d.$a." 
    order by sp.AID desc "; 
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "SuplierDetailReport-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
    $out .= '

    <head>
        <meta charset="utf-8">
    </head>
    <table>
        <tr>
            <td colspan="4" align="center">
                <h3>Supplier Detail</h3>
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <td>
        </tr>
        <tr>
            <th style="border: 1px solid ;">စဉ်</th>
            <th style="border: 1px solid ;">Supplier Name</th>
            <th style="border: 1px solid ;">Item Name</th>
            <th style="border: 1px solid ;">Amount</th>
            <th style="border: 1px solid ;">Date</th>
        </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result)){
        $no = $no + 1;
        $out .= '
        <tr>
            <td style="border: 1px solid ;">'.$no.'</td>
            <td style="border: 1px solid ;">'.$row["sname"].'</td>
            <td style="border: 1px solid ;">'.$row["pname"].'</td>
            <td style="border: 1px solid ;">'.number_format($row["Amt"]).'</td>
            <td style="border: 1px solid ;">'.enDate($row["Date"]).'</td>
        </tr>';
        }
        $out .= '
    </table>';
    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename='.$fileName);
    echo $out;
    }else{
    $out .= '

    <head>
        <meta charset="utf-8">
    </head>
    <table>
        <tr>
            <td colspan="4" align="center">
                <h3>Supplier Detail</h3>
            </td>
        </tr>
        <tr>
            <td colspan="5">
            <td>
        </tr>
        <tr>
        <th style="border: 1px solid ;">စဉ်</th>
        <th style="border: 1px solid ;">Supplier Name</th>
        <th style="border: 1px solid ;">Item Name</th>
        <th style="border: 1px solid ;">Amount</th>
        <th style="border: 1px solid ;">Date</th>
        </tr>
        <tr>
            <td colspan="5" style="border: 1px solid ;" align="center">No data</td>
        </tr>';
        $out .= '
    </table>';
    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename='.$fileName);
    echo $out;
    }

}



?>