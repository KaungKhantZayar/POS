<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';
  ?>

  <?php include 'header.php'; ?>

<style media="screen">
.outer {
overflow-y: auto;
height: 300px;
}

.outer{
width: 100%;
-layout: fixed;
}

.outer th {
text-align: left;
top: 0;
position: sticky;
background-color: white;
}
.search_btn{
  background-color:#1c1c1c;
  color:white;
  transition:0.5s;
  border-radius:10px;
  padding:7px;
  padding:-29px;
  font-size:13px;
}
.search_btn:hover{
  border:2px solid #1c1c1c;
  background:none;
  color:#1c1c1c;
  transition:0.5s;
  border-radius:10px;
  box-shadow:2px 8px 16px gray;
}
</style>


<?php
    $item_id = $_GET['item_id'];
    $stockstmt = $pdo->prepare("SELECT * FROM stock WHERE item_id='$item_id'");
    $stockstmt->execute();
    $stockdata = $stockstmt->fetchAll();

    // Item Name
    $itemstmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
    $itemstmt->execute();
    $item = $itemstmt->fetch(PDO::FETCH_ASSOC);
 ?>

 <!-- <form class="" action="" method="post">
   <div class="d-flex" style="margin-left:950px; margin-top:-15px;">
     <input type="date" name="" value="" class="form-control" placeholder="Search Supplier_Name" style="width:200px;">
     <button type="submit" name="search" class="search_btn ms-3">Search</button>
  </div>
 </form> -->

<div class="container">
  <div class="d-flex" style="margin-top:-17px;">
    <h4 class="col-11"><b>Account Receivable ( <?php echo $item['item_name']; ?> )</b></h4>
    <a href="stock_control.php"><button class="">Back</button></a>
  </div>
  <div class="outer" style="margin-top:-10px;">
    <table class="table table-bordered mt-4 table-hover">
      <thead>
        <tr>
          <th style="width: 10px">No</th>
          <th>Date</th>
          <th>To / From</th>
          <th>Vr_No</th>
          <th class="text-center">In</th>
          <th class="text-center">Out</th>
          <th class="text-center">Balance</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if ($stockdata) {
            $id = 1;
            foreach ($stockdata as $value) {
         ?>
        <tr>
          <td><?php echo $id; ?></td>
          <td><?php echo $value['date'];?></td>
          <td><?php echo $value['to_from'];?></td>
          <td><?php echo $value['vr_no'];?></td>
          <td class="text-center">
            <?php 
             if(!empty($value['in_qty'])){
                if($value['foc_qty'] != 0){
                    echo $value['in_qty'];
                    ?>
                    <span class="badge badge-success">foc +2</span>
                    <?php 
                }else{ 
                    echo $value['in_qty'];              
                }
              }else{
                  echo '-';
             }
            ?>
          </td>
          <td class="text-center">
            <?php 
              if(!empty($value['out_qty'])){
                  if($value['foc_qty'] != 0){
                      echo $value['out_qty'];
                      ?>
                      <span class="badge badge-success">foc +2</span>
                      <?php 
                  }else{ 
                      echo $value['out_qty'];              
                  }
                }else{
                    echo '-';
              }
              ?>  
          </td>
          <td class="text-center"><?php echo $value['balance'];?></td>
        </tr>
        <?php
          $id++;
            }
          }
         ?>
      </tbody>
    </table>
  </div>
</div>


  <?php include 'footer.html'; ?>
