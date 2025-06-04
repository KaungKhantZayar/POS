<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';

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
    <?php include 'header.php';?>

    <?php

    if ($_POST) {
      if (empty($_POST['item_id']) || empty($_POST['item_name']) || empty($_POST['categories_id'])  || empty($_POST['original_price']) || empty($_POST['selling_price'])) {
        if (empty($_POST['item_id'])) {
          $itemidError = 'Item_Id is required';
        }
        if (empty($_POST['item_name'])) {
          $itemnameError = 'Item_Name is required';
        }
        if (empty($_POST['categories_id'])) {
          $categoriesidError = 'Categories_Id is required';
        }
        if (empty($_POST['original_price'])) {
          $original_priceError = 'Original_Price is required';
        }
        if (empty($_POST['selling_price'])) {
          $selling_priceError = 'Selling_Price is required';
        }
      }else {
        $item_id = $_POST['item_id'];
        $item_name = $_POST['item_name'];
        $categories_id = $_POST['categories_id'];
        $original_price = $_POST['original_price'];
        $selling_price = $_POST['selling_price'];
        $id = $_GET['id'];

        $stmt = $pdo->prepare("UPDATE item SET item_id=:item_id,item_name=:item_name,categories_id=:categories_id,original_price=:original_price,selling_price=:selling_price  WHERE id='$id'");
        $result = $stmt->execute(
          array(':item_id'=>$item_id,':item_name'=>$item_name,':categories_id'=>$categories_id, ':original_price'=>$original_price, ':selling_price'=>$selling_price)
        );
        if ($result) {
          echo "<script>alert('Item is updated');window.location.href='item.php';</script>";
        }
      }
    }



     $stmt = $pdo->prepare("SELECT * FROM item WHERE id=".$_GET['id']);
     $stmt->execute();
     $result = $stmt->fetchAll();
     ?>

    <div class="" style="margin-left:350px; margin-top:100px;">
      <div class="card crd">
        <div class="card-body">
          <h2>Update Page</h2>
          <form class="" action="" method="post">
            <label for="" class="mt-4"><b>Item_id</b></label>
            <input type="text" class="form-control" placeholder="Item_id" name="item_id" value="<?php echo $result[0]['item_id']; ?>">
            <p style="color:red;"><?php echo empty($itemidError) ? '' : '*'.$itemidError;?></p>

            <label for="" class="mt-4"><b>Item_name</b></label>
            <input type="text" class="form-control" placeholder="Item_name" name="item_name" value="<?php echo $result[0]['item_name']; ?>">
            <p style="color:red;"><?php echo empty($itemnameError) ? '' : '*'.$itemnameError;?></p>

               <?php
                 $catStmt = $pdo->prepare("SELECT * FROM categories");
                 $catStmt->execute();
                 $catResult = $catStmt->fetchAll();
                ?>
              <label for="pwd" class="mt-4">Category_id</label>
              <select name="categories_id" class="form-control">
                <option value="">SELECT CATEGORY</option>
                <?php foreach ($catResult as $value) {?>
                  <option value="<?php echo $value['id']; ?>" <?php if($value['id'] == $result[0]['categories_id']){ echo "selected"; } ?>><?php echo $value['categories_name'] ?></option>

                <?php } ?>
              </select>
              <p style="color:red;"><?php echo empty($categoriesidError) ? '' : '*'.$categoriesidError;?></p>


              <div class="row">
                <div class="col-6">
                  <label for="" class="mt-4"><b>Original_Price</b></label>
                  <input type="number" class="form-control" placeholder="Original_Price" name="original_price" value="<?php echo $result[0]['original_price']; ?>">
                  <p style="color:red;"><?php echo empty($original_priceError) ? '' : '*'.$original_priceError;?></p>

                </div>
                <div class="col-6">
                  <label for="" class="mt-4"><b>Selling_Price</b></label>
                  <input type="number" class="form-control" placeholder="Selling_Price" name="selling_price" value="<?php echo $result[0]['selling_price']; ?>">
                  <p style="color:red;"><?php echo empty($selling_priceError) ? '' : '*'.$selling_priceError;?></p>
                </div>
              </div>

            <div class="row mt-3">
              <div class="col-6">
                <button type="submit" name="button" class="add_btn form-control mt-3">Update</button>
              </div>
              <div class="col-6">
                <a href="item.php" style="width:450px;"><button type="button" name="button" class="add_btn form-control mt-3">Back</button></a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <?php include 'footer.html'; ?>
  </body>
</html>
