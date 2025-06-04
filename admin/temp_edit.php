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


  <body>
    <?php include 'header.php';?>


    <?php
    if ($_POST) {
     if (empty($_POST['date']) || empty($_POST['vr_no']) || empty($_POST['supplier_id']) || empty($_POST['item_id']) || empty($_POST['price']) || empty($_POST['qty'])) {
       if (empty($_POST['date'])) {
         $dateError = 'Date is required';
       }
       if (empty($_POST['vr_no'])) {
         $vr_noError = 'Vr_No is required';
       }
       if (empty($_POST['supplier_id'])) {
         $supplier_idError = 'Supplier is required';
       }
       if (empty($_POST['item_id'])) {
         $item_idError = 'Item_Id is required';
       }
       if (empty($_POST['price'])) {
         $priceError = 'Price is required';
       }
       if (empty($_POST['qty'])) {
         $qtyError = 'Qty is required';
       }
     }else {
       $date = $_POST['date'];
       $vr_no = $_POST['vr_no'];
       $supplier_id = $_POST['supplier_id'];
       $item_id = $_POST['item_id'];
       $price = $_POST['price'];
       $qty = $_POST['qty'];
       $foc = $_POST['foc'];
       $id = $_GET['id'];

       if (!empty($_POST['discount'])) {
         $discount_percentage = $_POST['discount'];
         $amount = $price * $qty;

         $percentsge_amount = ($amount/100) * $discount_percentage;
         $amount = $amount - $percentsge_amount;
       }else {
         $amount = $price * $qty;
       }

       $stmt = $pdo->prepare("UPDATE temp_purchase SET date=:date, vr_no=:vr_no, supplier_id=:supplier_id, item_id=:item_id, price=:price, qty=:qty, percentsge=:discount_percentage, percentsge_amount=:percentsge_amount, stock_foc=:foc,amount=:amount WHERE id='$id'");
       $result = $stmt->execute(
         array(':date'=>$date,':vr_no'=>$vr_no,':supplier_id'=>$supplier_id,':item_id'=>$item_id,':price'=>$price,':qty'=>$qty, ':discount_percentage'=>$discount_percentage, ':percentsge_amount'=>$percentsge_amount, ':foc'=>$foc, ':amount'=>$amount)
       );
       if ($result) {
         echo "<script>alert('Your Update is Successfull');window.location.href='purchase.php';</script>";
       }
     }
    }

    $stmt = $pdo->prepare("SELECT * FROM temp_purchase WHERE id=".$_GET['id']);
    $stmt->execute();
    $result = $stmt->fetchAll();
    ?>

    <div class="" style="margin-left:300px; margin-top:100px;">
      <div class="card" style="width:600px;">
        <div class="card-body">
          <form class="" action="" method="post">
            <div class="row">
              <div class="col-6">
                <label for="" class="mt-4"><b>Date</b></label>
                <input type="date" class="form-control" placeholder="Date" name="date" value="<?php echo $result[0]['date']; ?>">
                <p style="color:red;"><?php echo empty($dateError) ? '' : '*'.$dateError;?></p>
              </div>
              <div class="col-6">
                <label for="" class="mt-4"><b>Vr_No</b></label>
                <input type="text" class="form-control" placeholder="Vr_No" name="" value="<?php echo $result[0]['vr_no']?>" disabled>
                <input type="hidden" class="form-control" placeholder="Vr_No" name="vr_no" value="<?php echo $result[0]['vr_no']?>">
                <p style="color:red;"><?php echo empty($vr_noError) ? '' : '*'.$vr_noError;?></p>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <label for="" class="mt-4"><b>Supplier_Id</b></label>
                <input type="number" class="form-control" placeholder="Supplier_Id" name="supplier_id" value="<?php echo $result[0]['supplier_id']; ?>">
                <p style="color:red;"><?php echo empty($supplier_idError) ? '' : '*'.$supplier_idError;?></p>
              </div>
              <div class="col-6">
                <label for="" class="mt-4"><b>Item_Id</b></label>
                <input type="text" class="form-control" placeholder="Item_Id" name="item_id" value="<?php echo $result[0]['item_id']; ?>">
                <p style="color:red;"><?php echo empty($item_idError) ? '' : '*'.$item_idError;?></p>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <label for="" class="mt-4"><b>Price</b></label>
                <input type="number" class="form-control" placeholder="Price" name="price" value="<?php echo $result[0]['price']; ?>">
                <p style="color:red;"><?php echo empty($priceError) ? '' : '*'.$priceError;?></p>
              </div>
              <div class="col-6">
                <label for="" class="mt-4"><b>Qty</b></label>
                <input type="number" class="form-control" placeholder="Qty" name="qty" value="<?php echo $result[0]['qty']; ?>">
                <p style="color:red;"><?php echo empty($qtyError) ? '' : '*'.$qtyError;?></p>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <label for="" class="mt-4"><b>Discount</b></label>
                <input type="number" class="form-control" placeholder="Discount" name="discount" value="<?php echo $result[0]['percentsge']; ?>">
                <p style="color:red;"><?php echo empty($discountError) ? '' : '*'.$discountError;?></p>
              </div>
              <div class="col-6">
                <label for="" class="mt-4"><b>Foc</b></label>
                <input type="number" class="form-control" placeholder="Foc" name="foc" value="<?php echo $result[0]['stock_foc']; ?>">
                <p style="color:red;"><?php echo empty($focError) ? '' : '*'.$focError;?></p>
              </div>
            </div>

            <div class="row">
              <div class="col-6">
                <button type="submit" name="button" class="add_btn form-control mt-3">Update</button>
              </div>
              <div class="col-6">
                <a href="purchase.php" style="width:450px;"><button type="button" name="button" class="add_btn form-control mt-3">Back</button></a>
              </div>
            </div>
            </div>
          </div>
        </form>
        </div>


    <?php include 'footer.html'; ?>
  </body>
</html>
