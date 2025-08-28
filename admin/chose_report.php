<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';

  ?>
<?php include 'header.php'; ?>
<!-- <style media="screen">
  .parchase_report{
    text-decoration:none;
    color:black;
  }
  .parchase_report:hover{
    color:black;
  }
  .purchase_reoprt2{
    border:none;
    margin-left:20px;
    padding-left:120px;
    padding-right:120px;
    padding-top:10px;
    padding-bottom:10px;
    border-top-left-radius:5px;
    border-top-right-radius:5px;
    border-top:1px solid black;
    border-left:1px solid black;
    border-right:1px solid black;
    border-bottom:1px solid black;
    /* box-shadow:5px 5px 5px gray; */

  }
  .cabody{
    margin-left:20px;
    width:390px;
    margin-top:px;
    border-top:1px solid black;
    padding:10px;
    box-shadow:0px 5px 5px gray;
    border-bottom-left-radius:5px;
    border-bottom-right-radius:5px;
  }

  .cabody2{
    margin-left:20px;
    width:347px;
    margin-top:px;
    border-top:1px solid black;
    padding:10px;
    box-shadow:0px 5px 5px gray;
    border-bottom-left-radius:5px;
    border-bottom-right-radius:5px;
  }
  .cabody3{
    margin-left:20px;
    width:360px;
    margin-top:px;
    border-top:1px solid black;
    padding:10px;
    box-shadow:0px 5px 5px gray;
    border-bottom-left-radius:5px;
    border-bottom-right-radius:5px;
  }
</style> -->

  <!-- <div class="container">
    <div class="row">
      <div class="col-4">
        <div class="">
          <button class="purchase_reoprt2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
            <h5>Parchase_Report</h5>
          </button>
        </div>

        <div style="min-height: 120px;">
          <div class="collapse collapse-horizontal" id="collapseWidthExample">
            <div class=" cabody">
              <div class="">
                <h5>Cash_Purchase</h5>
                <div class="mt-3">
                  <ul>
                    <li><a href="report.php?report_name=date&type=cash" class="parchase_report"><p>Date အလိုက်ကြည့်ရန်</p></a></li>
                    <li><a href="report.php?report_name=vr_no&type=cash" class="parchase_report" style="margin-top:-10px;"><p>Vouecher No အလိုက်ကြည့်ရန်</p></a></li>
                    <li><a href="report.php?report_name=item&type=cash" class="parchase_report"><p>Item အလိုက်ကြည့်ရန်</p></a></li>
                  </ul>
                </div>
              </div>
              <hr>
              <div class="">
                <h5>Credit_Purchase</h5>
                <div class="mt-3">
                  <ul>
                    <li><a href="report.php?report_name=date&type=credit" class="parchase_report"><p>Date အလိုက်ကြည့်ရန်</p></a></li>
                    <li><a href="report.php?report_name=vr_no&type=credit" class="parchase_report" style="margin-top:-10px;"><p>Vouecher No အလိုက်ကြည့်ရန်</p></a></li>
                    <li><a href="report.php?report_name=item&type=credit" class="parchase_report"><p>Item အလိုက်ကြည့်ရန်</p></a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="">
          <button class="purchase_reoprt2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWidthExample2" aria-expanded="false" aria-controls="collapseWidthExample2">
            <h5>Sale_Report</h5>
          </button>
        </div>

        <div style="min-height: 120px;">
          <div class="collapse collapse-horizontal" id="collapseWidthExample2">
            <div class="cabody2">
              <div class=""> -->
                <!-- <a href="report.php?report_name=date" class="parchase_report"><p>Date အလိုက်ကြည့်ရန် (Date Between)</p></a>
                <a href="report.php?report_name=vr_no" class="parchase_report" style="margin-top:-10px;"><p>Vouecher No အလိုက်ကြည့်ရန်</p></a>
                <a href="report.php?report_name=item" class="parchase_report"><p>Item အလိုက်ကြည့်ရန်</p></a> -->
              <!-- </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="">
          <button class="purchase_reoprt2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWidthExample3" aria-expanded="false" aria-controls="collapseWidthExample3">
            <h5>Stock_Report</h5>
          </button>
        </div>

        <div style="min-height: 120px;">
          <div class="collapse collapse-horizontal" id="collapseWidthExample3">
            <div class="cabody3">
              <div class=""> -->
                <!-- <a href="report.php?report_name=date" class="parchase_report"><p>Date အလိုက်ကြည့်ရန် (Date Between)</p></a>
                <a href="report.php?report_name=vr_no" class="parchase_report" style="margin-top:-10px;"><p>Vouecher No အလိုက်ကြည့်ရန်</p></a>
                <a href="report.php?report_name=item" class="parchase_report"><p>Item အလိုက်ကြည့်ရန်</p></a> -->
              <!-- </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <br><br><br><br><br><br><br>
  <br><br><br><br><br><br><br>
  <br><br><br><br><br><br><br>
  <br><br><br><br><br><br><br> -->

<?php include 'footer.html'; ?>
