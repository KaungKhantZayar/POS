<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';

  ?>
<?php include 'header.php'; ?>
<style media="screen">
  .parchase_report{
    text-decoration:none;
    color:black;
  }
  .parchase_report:hover{
    color:black;
  }
</style>

  <div class="container" style="margin-top:;">
    <div class="row">
      <div class="col-4">
        <div class="card">
          <div class="card-header">
              <h5 style="margin-left:110px;">Parchase_Report</h5>
          </div>
          <div class="card-body">
            <a href="report.php?report_name=date" class="parchase_report"><p>Date အလိုက်ကြည့်ရန် (Date Between)</p></a>
            <a href="report.php?report_name=vr_no" class="parchase_report" style="margin-top:-10px;"><p>Vouecher No အလိုက်ကြည့်ရန်</p></a>
            <a href="report.php?report_name=item" class="parchase_report"><p>Item အလိုက်ကြည့်ရန်</p></a>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="card">
          <div class="card-body">
              <h5 style="margin-left:110px;">Sale_Report</h5>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="card">
          <div class="card-body">
              <h5 style="margin-left:110px;">Stock_Report</h5>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php include 'footer.html'; ?>
