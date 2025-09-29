<?php
include('../config.php');

$action = $_POST['action'];

if($action == 'show'){ 
      $vtotal=0;
      $res_vtotal = 0;
      $stotal=0;
      $res_stotal = 0;
      $etotal=0;
      $profit = 0;
      $dtto=$_POST["dtto"];
      $dtfrom=$_POST["dtfrom"];
      $out="";
      // voucher
      $sql="select Sum(Total) as stotal 
      from tblvoucher 
      where Date(Date)>='{$dtto}' and Date(Date)<='{$dtfrom}'";      
      $vtotal = GetInt($sql);
      // order voucher
      $sql1 = "select Sum(Total) as stotal 
      from tblordervoucher where Status=1 and Date(Date)>='{$dtto}' 
      and Date(Date)<='{$dtfrom}'";
      $vtotal1 = GetInt($sql1);
      if($vtotal1 == NULL){
            $vtotal1 = 0;
      }
      $res_vtotal = $vtotal + $vtotal1;
      $out.="
      <tr>
            <td>အရောင်းစုစုပေါင်း</td>
            <td class='text-right'>".number_format($res_vtotal)." MMK</td>
      </tr>
      ";

      // sale
      $sql_s = "select Sum(s.Qty*r.PurchasePrice) as rtotal 
      from tblsale s,tblremain r where r.AID=s.RemainID 
      and Date(s.Date)>='{$dtto}' and Date(s.Date)<='{$dtfrom}'";
      $stotal = GetInt($sql_s);
      // order sale
      $sql_os = "select Sum(s.Qty*r.PurchasePrice) as rtotal 
      from tblordersale s,tblremain r where s.Status=1  
      and r.AID=s.RemainID and Date(s.Date)>='{$dtto}' and Date(s.Date)<='{$dtfrom}'";
      $stotal1 = GetInt($sql_os);
      if($stotal1 == NULL){
            $stotal1 = 0;
      }
      $res_stotal = $stotal + $stotal1;
      $out.="
      <tr>
            <td>အဝယ်စုစုပေါင်း</td>
            <td class='text-right'>".number_format($res_stotal)." MMK</td>
      </tr>";

      // expense
      $sql_e = "select Sum(Amount) as etotal from tblexpense 
      where Date(Date)>='{$dtto}' and Date(Date)<='{$dtfrom}'";
      $etotal = GetInt($sql_e);
      $out.="
      <tr>
            <td>အသုံးစရိတ်စုစုပေါင်း</td>
            <td class='text-right'>".number_format($etotal)." MMK</td>
      </tr>";      

      $profit = $res_vtotal - ($res_stotal + $etotal);     
      $out.="
            <tr>
                  <td>ခန့်မှန်းအမြတ်စုစုပေါင်း</td>
                  <td class='text-right'>".number_format($profit)." MMK</td>
            </tr>
      ";
      $_SESSION['vtotal'] = $res_vtotal;
      $_SESSION['ptotal'] = $res_stotal;
      $_SESSION['etotal'] = $etotal;
      $_SESSION['profit'] = $profit;

     echo $out;
}


if($action=='showcard'){
      $out="";
      $out.="
      <div class='col-12 col-sm-6 col-md-3'>
            <div class='info-box'>
                  <span class='info-box-icon bg-success elevation-1'><i
                              class='fas fa-shopping-cart'></i></span>

                  <div class='info-box-content'>
                        <span class='info-box-text'>အရောင်းစုစုပေါင်း</span>
                        <span class='info-box-number'>
                              ".number_format($_SESSION['vtotal'])." MMK                              
                        </span>
                  </div>
            </div>

      </div>
      <div class='col-12 col-sm-6 col-md-3'>
            <div class='info-box mb-3'>
                  <span class='info-box-icon bg-danger elevation-1'><i
                              class='fas fa-minus'></i></span>

                  <div class='info-box-content'>
                        <span class='info-box-text'>အဝယ်စုစုပေါင်း</span>
                        <span class='info-box-number'>".number_format($_SESSION['ptotal'])."
                              MMK</span>
                  </div>
            </div>
      </div>
      <div class='col-12 col-sm-6 col-md-3'>
            <div class='info-box mb-3'>
                  <span class='info-box-icon bg-danger elevation-1'><i
                              class='fas fa-minus'></i></span>

                  <div class='info-box-content'>
                        <span class='info-box-text'>အသုံးစရိတ်စုစုပေါင်း</span>
                        <span class='info-box-number'>".number_format($_SESSION['etotal'])."
                              MMK</span>
                  </div>
            </div>
      </div>
      <div class='col-12 col-sm-6 col-md-3'>
            <div class='info-box mb-3'>
                  <span class='info-box-icon bg-info elevation-1'><i
                              class='fas fa-dollar'></i></span>

                  <div class='info-box-content'>
                        <span class='info-box-text'>အမြတ်စုစုပေါင်း</span>
                        <span class='info-box-number'>".number_format($_SESSION['profit'])."
                              MMK</span>
                  </div>
            </div>
      </div>
      ";
      echo $out;     
}
