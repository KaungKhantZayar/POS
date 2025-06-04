<?php
session_start();
require '../config/config.php';
require '../config/common.php';
  ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>

  <style media="screen">
  .add_btn{
    background-color:#1c1c1c;
    color:white;
    transition:0.5s;
    border-radius:10px;
    padding:7px;
  }
  .add_btn:hover{
    border:2px solid #1c1c1c;
    background:none;
    color:#1c1c1c;
    transition:0.5s;
    border-radius:10px;
    box-shadow:2px 8px 16px gray;
  }
  .crd{
    width:500px;
  }
  </style>
  <body>

    <?php include 'header.php'; ?>

      <?php
        if ($_POST) {
            $id = $_GET['id'];
            $date = $_POST['date'];
            $vr_no = $_POST['vr_no'];
            $customer_id = $_POST['customer_id'];
            $item_id = $_POST['item_id'];
            $qty = $_POST['qty'];

            $stmt = $pdo->prepare("UPDATE temp_sale SET date=:date, vr_no=:vr_no, customer_id=:customer_id, item_id=:item_id, qty=:qty WHERE id='$id'");
            $result = $stmt->execute(
              array(':date'=>$date,':vr_no'=>$vr_no,':customer_id'=>$customer_id,':item_id'=>$item_id,':qty'=>$qty,)
            );
            if ($result) {
              echo "<script>alert('Your Update is Successfull');window.location.href='sale.php';</script>";
            }
          }

        $temp_salestmt = $pdo->prepare("SELECT * FROM  temp_sale WHERE id=".$_GET['id']);
        $temp_salestmt->execute();
        $temp_saleResult = $temp_salestmt->fetchAll();
       ?>


    <div class="" style="margin-left:300px; margin-top:100px;">
      <div class="card" style="width:600px;">
        <div class="card-body">
          <form class="" action="" method="post">
            <div class="row">
              <div class="col-6">
                <label for="" class="mt-4"><b>Date</b></label>
                <input type="date" class="form-control" placeholder="Date" name="date" value="<?php echo $temp_saleResult[0]['date']; ?>">
                <p style="color:red;"><?php echo empty($dateError) ? '' : '*'.$dateError;?></p>
              </div>
              <div class="col-6">
                <label for="" class="mt-4"><b>Vr_No</b></label>
                <input type="text" class="form-control" placeholder="Vr_No" name="" value="<?php echo $temp_saleResult[0]['vr_no']?>" disabled>
                <input type="hidden" class="form-control" placeholder="Vr_No" name="vr_no" value="<?php echo $temp_saleResult[0]['vr_no']?>">
                <p style="color:red;"><?php echo empty($vr_noError) ? '' : '*'.$vr_noError;?></p>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <label for="" class="mt-4"><b>Customer_Id</b></label>
                <input type="number" class="form-control" placeholder="Customer_Id" name="customer_id" value="<?php echo $temp_saleResult[0]['customer_id']; ?>">
                <p style="color:red;"><?php echo empty($customer_idError) ? '' : '*'.$customer_idError;?></p>
              </div>
              <div class="col-6">
                <label for="" class="mt-4"><b>Item_Id</b></label>
                <input type="text" class="form-control" placeholder="Item_Id" name="item_id" value="<?php echo $temp_saleResult[0]['item_id']; ?>">
                <p style="color:red;"><?php echo empty($item_idError) ? '' : '*'.$item_idError;?></p>
              </div>
            </div>
            <div class="row">
              <div class="col-4" style="margin-left:200px;margin-top:-20px;">
                <label for="" class="mt-4"><b>Qty</b></label>
                <input type="number" class="form-control" placeholder="Qty" name="qty" value="<?php echo $temp_saleResult[0]['qty']; ?>">
                <p style="color:red;"><?php echo empty($qtyError) ? '' : '*'.$qtyError;?></p>
              </div>
            </div>

            <div class="row">
              <div class="col-6">
                <button type="submit" name="button" class="form-control mt-3 update_btn">Update</button>
              </div>
              <div class="col-6">
                <a href="sale.php" style="width:450px;"><button type="button" name="button" class="add_btn form-control mt-3">Back</button></a>
              </div>
            </div>
            </div>
          </div>
        </form>
        </div>

        <?php include 'footer.html'; ?>

  </body>
</html>
