<?php
include('../config.php');
$userid=$_SESSION["eadmin_userid"];

if($_POST["action"] == 'showmain'){ 
    $out = "";
    $supplier = $_POST['supplier'];
    $name = $_POST['name'];
    $sql1 = "select Sum(Amt) from tblcreditpay where CustomerID={$supplier}";
    $totalpay = GetInt($sql1);

    $sql2 = "select Sum(Amt) from tblcreditdetail where CustomerID={$supplier}";
    $totaldetail = GetInt($sql2);

    $totalremain = $totalpay - $totaldetail;
    $out.='
    <div class="form-group row">
        <label class="col-sm-4" for="usr">Customer name :</label>
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

// customer pay
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
        $a = " and ( c.Name like '%$search%') ";
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
        $d=" and sp.CustomerID={$supplier} ";
    }
    $sql="select sp.*,c.Name as cname   
    from tblcreditpay sp,tblcustomer c where 
    sp.CustomerID=c.AID  ".$c.$d.$a." 
    order by sp.AID desc limit $offset,$limit_per_page";    
    
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
            <thead>
                <tr>
                    <th width="7%;">No</th>
                    <th>Customer Name</th>
                    <th class="text-right">Total Amount</th>  
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
                <td>{$row["cname"]}</td>
                <td class='text-right'>".number_format($row["Amt"])."</td>  
               
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
        from tblcreditpay sp,tblcustomer c where 
        sp.CustomerID=c.AID  ".$c.$d.$a." 
        order by sp.AID desc";
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
                                    <a class="pagin1 page-link" href="#">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
                                </li>';

                $previous_id = $page_array[$count] - 1;
                if($previous_id > 0){
                    $previous_link = '<li class="page-item">
                                            <a class="pagin1 page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a>
                                    </li>';
                }
                else{
                    $previous_link = '<li class="page-item disabled">
                                            <a class="pagin1 page-link" href="#">Previous</a>
                                    </li>';
                }

                $next_id = $page_array[$count] + 1;
                if($next_id > $total_links){
                    $next_link = '<li class="page-item disabled">
                                        <a class="pagin1 page-link" href="#">Next</a>
                                </li>';
                }else{
                    $next_link = '<li class="page-item">
                                    <a class="pagin1 page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a>
                                </li>';
                }
            }else{
                if($page_array[$count] == '...')
                {
                    $page_link .= '<li class="page-item disabled">
                                        <a class="pagin1 page-link" href="#">...</a>
                                    </li> ';
                }else{
                    $page_link .= '<li class="page-item">
                                        <a class="pagin1 page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a>
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
                    <th width="7%;">No</th>
                    <th>Customer Name</th>
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
  
    $sql="insert into tblcreditpay (CustomerID,Amt,Date,UserID) values 
    ('{$supplier}','{$amt}','{$date}','{$userid}')";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["eadmin_username"]."သည် Customer Credit Pay အား Savae လုပ်သွားသည်။");
        echo 1;
    }else{
        echo 0;
    }     
}

if($_POST["action"] == 'editprepare'){
    $aid = $_POST["aid"];
    $sql = "select p.*,s.Name as sname from tblcreditpay p,tblcustomer s where p.AID={$aid}";
    $result = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_array($result);
            $out.='
            <input type="hidden" name="aid" value="'.$aid.'"/>
                <div class="modal-body" data-spy="scroll" data-offset="50">
                        <div class="form-group">
                            <label for="usr">Customer Name:</label>
                            <select class="form-control border-success" name="esupplierpay1">
                                <option value="'.$row['CustomerID'].'">'.$row['sname'].'</option>
                                                '.load_customer().'>
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
  
    $sql="update tblcreditpay set CustomerID='{$supplier}',Amt='{$amt}',Date='{$date}',
    UserID='{$userid}' where AID='{$aid}'";
    
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["eadmin_username"]."သည် Customer Credit Pay အား Update လုပ်သွားသည်။");
        echo 1;
    }else{
        echo 0;
    } 
    
}

if($_POST["action"] == 'delete'){    
    $aid=$_POST['aid'];
  
    $sql="delete from tblcreditpay where AID='{$aid}'";
    
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
        $a = " and ( c.Name like '%$search%') ";
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
        $d=" and sp.CustomerID={$supplier} ";
    }
    $sql="select sp.*,c.Name as cname   
    from tblcreditpay sp,tblcustomer c where 
    sp.CustomerID=c.AID  ".$c.$d.$a." 
    order by sp.AID desc"; 
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "CustomerCreditPayReport-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
    $out .= '

    <head>
        <meta charset="utf-8">
    </head>
    <table>
        <tr>
            <td colspan="4" align="center">
                <h3>Customer Credit Pay</h3>
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <td>
        </tr>
        <tr>
            <th style="border: 1px solid ;">No</th>
            <th style="border: 1px solid ;">Customer Name</th>
            <th style="border: 1px solid ;">Amount</th>
            <th style="border: 1px solid ;">Date</th>
        </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result)){
        $no = $no + 1;
        $out .= '
        <tr>
            <td style="border: 1px solid ;">'.$no.'</td>
            <td style="border: 1px solid ;">'.$row["cname"].'</td>
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
                <h3>Customer Credit Pay</h3>
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <td>
        </tr>
        <tr>
            <th style="border: 1px solid ;">No</th>
            <th style="border: 1px solid ;">Customer Name</th>
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

// customer detail
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
        $a = " and ( c.Name like '%$search%' or d.VNO like '%$search%') ";
    }  
    
    $from=$_POST['from'];
    $to=$_POST['to'];
    $c="";
    if($from!='' || $to!=''){
        $c=" and v.Date>='{$from}' and v.Date<='{$to}' ";
    }
    $supplier=$_POST['supplier'];
    $d="";
    if($supplier!=""){
        $d=" and v.CustomerID={$supplier} ";
    }
    $sql="select v.*,c.Name as cname   
    from tblvoucher v,tblcustomer c where v.Chk='Credit' and 
    v.CustomerID=c.AID  ".$c.$d.$a." 
    order by v.AID desc limit $offset,$limit_per_page";    

    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table class="table table-bordered table-striped responsive nowrap">
            <thead>
                <tr>
                    <th width="7%;">No</th>
                    <th>VNO</th>
                    <th>Customer Name</th>
                    <th class="text-right">Total Amount</th>  
                    <th class="text-right">Total Pay</th>  
                    <th class="text-right">Total Credit</th>  
                    <th>Date</th> 
                    <th>Action</th>   
                </tr>
            </thead>
            <tbody>
        ';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["VNO"]}</td>
                <td>{$row["cname"]}</td>
                <td class='text-right'>".number_format($row["Total"])."</td>  
                <td class='text-right'>".number_format($row["Cash"])."</td>  
                <td class='text-right'>".number_format($row["Credit"])."</td>  
                <td>".enDate($row["Date"])."</td>
                <td>
                    <button class='btn btn-sm btn-'.$color.' text-white' id='editpayprepare'
                        data-vno='{$row['VNO']}' data-customerid='{$row['CustomerID']}'
                        data-toggle='modal' data-target='#editpayprepare'><i class='fas fa-money-bill-wave'></i>
                        View</button>
                </td>
                
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="select v.AID    
        from tblvoucher v,tblcustomer c where v.Chk='credit' and 
        v.CustomerID=c.AID  ".$c.$d.$a." 
        order by v.AID desc";
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
                                    <a class="pagin2 page-link" href="#">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
                                </li>';

                $previous_id = $page_array[$count] - 1;
                if($previous_id > 0){
                    $previous_link = '<li class="page-item">
                                            <a class="pagin2 page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a>
                                    </li>';
                }
                else{
                    $previous_link = '<li class="page-item disabled">
                                            <a class="pagin2 page-link" href="#">Previous</a>
                                    </li>';
                }

                $next_id = $page_array[$count] + 1;
                if($next_id > $total_links){
                    $next_link = '<li class="page-item disabled">
                                        <a class="pagin2 page-link" href="#">Next</a>
                                </li>';
                }else{
                    $next_link = '<li class="page-item">
                                    <a class="pagin2 page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a>
                                </li>';
                }
            }else{
                if($page_array[$count] == '...')
                {
                    $page_link .= '<li class="page-item disabled">
                                        <a class="pagin2 page-link" href="#">...</a>
                                    </li> ';
                }else{
                    $page_link .= '<li class="page-item">
                                        <a class="pagin2 page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a>
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
                    <th width="7%;">No</th>
                    <th>VNO</th>
                    <th>Customer Name</th>
                    <th>Total Amount</th>  
                    <th>Date</th>          
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

if($_POST["action"] == 'excel1'){
    $search = $_POST['ser2'];
    $a = "";
    if($search != ''){ 
        $a = " and ( c.Name like '%$search%' or d.VNO like '%$search%') ";
    }  
    
    $from=$_POST['hfrom2'];
    $to=$_POST['hto2'];
    $c="";
    if($from!='' || $to!=''){
        $c=" and d.Date>='{$from}' and d.Date<='{$to}' ";
    }
    $supplier=$_POST['hsupplier2'];
    $d="";
    if($supplier!=""){
        $d=" and d.CustomerID={$supplier} ";
    }
    $sql="select d.*,c.Name as cname   
    from tblcreditdetail d,tblcustomer c where 
    d.CustomerID=c.AID  ".$c.$d.$a." 
    order by d.AID desc";   
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "CustomerDetailReport-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
    $out .= '

    <head>
        <meta charset="utf-8">
    </head>
    <table>
        <tr>
            <td colspan="5" align="center">
                <h3>Customer Detail</h3>
            </td>
        </tr>
        <tr>
            <td colspan="5"><td>
        </tr>
        <tr>
            <th style="border: 1px solid ;">No</th>
            <th style="border: 1px solid ;">VNO</th>
            <th style="border: 1px solid ;">Customer Name</th>
            <th style="border: 1px solid ;">Amount</th>
            <th style="border: 1px solid ;">Date</th>
        </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result)){
        $no = $no + 1;
        $out .= '
        <tr>
            <td style="border: 1px solid ;">'.$no.'</td>
            <td style="border: 1px solid ;">'.$row["VNO"].'</td>
            <td style="border: 1px solid ;">'.$row["cname"].'</td>
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
            <td colspan="5" align="center">
                <h3>Customer Detail</h3>
            </td>
        </tr>
        <tr>
            <td colspan="5"><td>
        </tr>
        <tr>
            <th style="border: 1px solid ;">No</th>
            <th style="border: 1px solid ;">VNO</th>
            <th style="border: 1px solid ;">Customer Name</th>
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