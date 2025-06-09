<div class="card">
      <div class="card-body">
        <h4>Purchase Order</h4>
        <form class="" action="" method="post" style="margin-top:-20px;">
          <div class="row">
            <div class="col-3">
              <label for="" class="mt-4"><b>Order Date</b></label>
              <input type="order_date" class="form-control" placeholder="Date" name="date">
              <p style="color:red;"><?php echo empty($dateError) ? '' : '*'.$dateError;?></p>
            </div>
            <div class="col-3">
              <label for="" class="mt-4"><b>Order No</b></label>
              <input type="text" class="form-control" placeholder="Vr_No" name="" value="<?php echo 25 . rand(1,999999) ?>" disabled>
              <input type="hidden" class="form-control" placeholder="Vr_No" name="vr_no" value="<?php echo 25 . rand(1,999999) ?>">
              <p style="color:red;"><?php echo empty($vr_noError) ? '' : '*'.$vr_noError;?></p>
            </div>
            <div class="col-3">
              <label for="" class="mt-4"><b>Supplier_Id</b></label>
              <input type="text" id="supplier_id" oninput="fetchSupplierNameFromId()" class="form-control" placeholder="Supplier_Id" name="supplier_id" >
              <p style="color:red;"><?php echo empty($supplier_idError) ? '' : '*'.$supplier_idError;?></p>
            </div>
            <div class="col-3">
              <label for="" class="mt-4"><b>Supplier_Name</b></label>
              <input type="text" id="supplier_name" class="form-control" placeholder="Supplier_Name" name="supplier_name" oninput="fetchSupplierIdFromName()">
            </div>
          </div>
            <!-- Second Row -->
          <div class="row">
            <div class="col-5 d-flex">
                <div class="col" >
                  <label for=""><b>Item_Id</b></label>
                  <input type="text" id="item_id" class="form-control" placeholder="Item_Id" name="item_id" oninput="fetchitemNameFromId()">
                  <p style="color:red;"><?php echo empty($item_idError) ? '' : '*'.$item_idError;?></p>
                </div>
                <div class="col" >
                  <label for=""><b>Item_Name</b></label>
                  <input type="text" id="item_name" class="form-control" placeholder="Item_Name" name="item_name" oninput="fetchitemIdFromName()">
                </div>
                <div class="col" >
                  <label for=""><b>Qty</b></label>
                  <input type="number" class="form-control" placeholder="Qty" name="qty">
                  <p style="color:red;"><?php echo empty($qtyError) ? '' : '*'.$qtyError;?></p>
                </div>
            </div>

            <div class="col-5 d-flex">
              <div class="col" >
                <label for=""><b>Discount</b></label>
                <input type="number" class="form-control" placeholder="Discount" name="discount">
                <p style="color:red;"></p>
              </div>

              <div class="col" >
                <label for=""><b>Foc</b></label>
                <input type="number" class="form-control" placeholder="Foc" name="foc">
                <p style="color:red;"></p>
              </div>

              <div class="col">
                <label for=""><b>Payment</b></label>
                <select name="type" class="form-control" >
                    <option value="cash">Cash</option>
                    <option value="credit">Credit</option>
                  </select>
              </div>
            </div>

            <div class="col-2 mt-4">
                <button type="submit" name="add_btn" class="form-control btn btn-primary mt-2">Add</button>
            </div>
          </div>
      </form>
      </div>
    </div>