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
        $a = " where Supplier like '%$search%' or Address like '%$search%' ";
    }
    $sql="select * from tblsupplier ".$a." 
    order by AID desc limit $offset,$limit_per_page";
    $result=mysqli_query($con,$sql) or die("SQL a Query");
    $out="";
    if(mysqli_num_rows($result) > 0){
        $out.='
        <table  class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">No</th>
            <th>အမည်</th>
            <th>လိပ်စာ</th>
            <th>အီးမေးလ်</th>
            <th>ဖုန်းနံပါတ်</th>
            <th width="10%;">Action</th>            
        </tr>
        </thead>
        <tbody>
        ';
        $no=0;
        while($row = mysqli_fetch_array($result)){
            $no = $no + 1;
            $out.="<tr>
                <td>{$no}</td>
                <td>{$row["Supplier"]}</td>
                <td>{$row["Address"]}</td>
                <td>{$row["Email"]}</td>
                <td>{$row["PhoneNo"]}</td>
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
                                data-aid='{$row['AID']}'><i
                                class='fas fa-trash text-danger'
                                style='font-size:13px;'></i>
                            Delete</a>                          
                        </div>
                    </div>
                </td>  
            </tr>";
        }
        $out.="</tbody>";
        $out.="</table>";

        $sql_total="select AID from tblsupplier ".$a." 
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
                if($next_id >= $total_links){
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
    }
    else{
        $out.='
        <table  class="table table-bordered table-striped responsive nowrap">
        <thead>
        <tr>
            <th width="7%;">No</th>
            <th>အမည်</th>
            <th>လိပ်စာ</th>
            <th>အီးမေးလ်</th>
            <th>ဖုန်းနံပါတ်</th>
            <th width="10%;">Action</th>            
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" class="text-center">No data</td>
            </tr>
        </tbody>
        </table>
        '; 
        echo $out;
    }

}


if($action == 'save'){
    $supplier = $_POST["supplier"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $phno = $_POST["phno"];
    $sql = "insert into tblsupplier (Supplier,Address,Email,PhoneNo) 
    values ('{$supplier}','{$address}','{$email}','{$phno}')";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["eadmin_username"]." သည် supplier အားအသစ်သွင်းသွားသည်။");
        echo 1;
    }else{
        echo 0;
    }
}


if($action == 'editprepare'){
    $aid = $_POST["aid"];
    $sql = "select * from tblsupplier where AID=$aid";
    $result = mysqli_query($con,$sql);
    $out = "";
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $out.="<div class='modal-body'>
                <input type='hidden' id='aid' name='aid' value='{$row['AID']}'/>                                             
                    <div class='form-group'>
                        <label for='usr'> အမည် :</label>
                        <input type='text' class='form-control border-success' id='supplier1' name='supplier1' value='{$row['Supplier']}'>
                    </div>
                    <div class='form-group'>
                        <label for='usr'> လိပ်စာ :</label>
                        <input type='text' class='form-control border-success' id='address1' name='address1' value='{$row['Address']}'>
                    </div> 
                    <div class='form-group'>
                        <label for='usr'> အီးမေးလ် :</label>
                        <input type='text' class='form-control border-success' id='email1' name='email1' value='{$row['Email']}'>
                    </div> 
                    <div class='form-group'>
                        <label for='usr'> ဖုန်းနံပါတ် :</label>
                        <input type='text' class='form-control border-success' id='phno1' name='phno1' value='{$row['PhoneNo']}'>
                    </div>                                
                </div>
                <div class='modal-footer'>
                    <button type='submit' id='btnupdate' class='btn btn-{$color}'><i class='fas fa-edit'></i>  ပြင်ဆင်မည်</button>
                </div>";
        }
        echo $out;
    }
}


if($action == 'update'){
    $aid = $_POST["aid"];
    $supplier = $_POST["supplier"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $phno = $_POST["phno"];
    $sql = "update tblsupplier set Supplier='{$supplier}',Address='{$address}',
    Email='{$email}',PhoneNo='{$phno}' where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["eadmin_username"]."သည် supplier အား update လုပ်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
}


if($action == 'delete'){

    $aid = $_POST["aid"];
    $sql = "delete from tblsupplier where AID=$aid";
    if(mysqli_query($con,$sql)){
        save_log($_SESSION["eadmin_username"]." သည် supplier အားဖျက်သွားသည်။");
        echo 1;
    }
    else{
        echo 0;
    }
    
}


if($action == 'excel'){
    $search = $_POST['ser'];
    $a = "";
    if($search != ''){ 
        $a = " where Supplier like '%$search%' or Address like '%$search%' ";
    }
    $sql="select * from tblsupplier ".$a." 
    order by AID desc";
    $result = mysqli_query($con,$sql);
    $out="";
    $fileName = "SupplierReport-".date('d-m-Y').".xls";
    if(mysqli_num_rows($result) > 0){
        $out .= '<head><meta charset="utf-8"></head>
        <table >  
            <tr>
                <td colspan="4" align="center"><h3>Supplier</h3></td>
            </tr>
            <tr><td colspan="4"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">Supplier</th>  
                <th style="border: 1px solid ;">Address</th>  
                <th style="border: 1px solid ;">PhoneNo</th>
            </tr>';
        $no=0;
        while($row = mysqli_fetch_array($result))
        {
            $no = $no + 1;
            $out .= '
                <tr>  
                    <td style="border: 1px solid ;">'.$no.'</td>  
                    <td style="border: 1px solid ;">'.$row["Supplier"].'</td>  
                    <td style="border: 1px solid ;">'.$row["Address"].'</td>  
                    <td style="border: 1px solid ;">'.$row["PhoneNo"].'</td>  
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
                <td colspan="4" align="center"><h3>Supplier</h3></td>
            </tr>
            <tr><td colspan="4"><td></tr>
            <tr>  
                <th style="border: 1px solid ;">No</th>  
                <th style="border: 1px solid ;">Supplier</th>  
                <th style="border: 1px solid ;">Address</th>  
                <th style="border: 1px solid ;">PhoneNo</th>
            </tr>
            <tr>
                <td colspan="4" style="border: 1px solid ;" align="center">No data</td>
            </tr>';
        $out .= '</table>'; 
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $out;
    }   
    
}






?>