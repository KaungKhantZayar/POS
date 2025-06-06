<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';
include 'header.php';
?>
<!-- Dashboard Styles -->
<style>
  /* Fade-in + Slide-up Animation */
  @keyframes fadeSlideUp {
    0% {
      opacity: 0;
      transform: translateY(30px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Card animation */
  .animated-card {
    opacity: 0;
    animation: fadeSlideUp 0.8s ease forwards;
  }

  /* Delay for staggered load effect */
  .animated-delay-1 { animation-delay: 0.1s; }
  .animated-delay-2 { animation-delay: 0.2s; }
  .animated-delay-3 { animation-delay: 0.3s; }
  .animated-delay-4 { animation-delay: 0.4s; }

  /* Hover effect */
  .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
  }
</style>

<!-- Begin Dashboard Container -->
<div class="container pb-5">
  <h2 class="fw-bold mb-5" style="margin-top: -15px;">📊 Business Dashboard</h2>

  <!-- Metric Cards Row -->
  <div class="row g-4">
    <div class="col-md-3 animated-card animated-delay-1">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body">
          <div class="text-muted small mb-1">Total Sales</div>
          <h4 class="fw-semibold text-primary">₹1,20,000</h4>
        </div>
      </div>
    </div>

    <div class="col-md-3 animated-card animated-delay-2">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body">
          <div class="text-muted small mb-1">Customers</div>
          <h4 class="fw-semibold text-success">320</h4>
        </div>
      </div>
    </div>

    <div class="col-md-3 animated-card animated-delay-3">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body">
          <div class="text-muted small mb-1">Pending Orders</div>
          <h4 class="fw-semibold text-warning">18</h4>
        </div>
      </div>
    </div>

    <div class="col-md-3 animated-card animated-delay-4">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body">
          <div class="text-muted small mb-1">Items in Stock</div>
          <h4 class="fw-semibold text-danger">145</h4>
        </div>
      </div>
    </div>
  </div>

  <!-- Insights Section -->
  <div class="row g-4 mt-4">
    <div class="col-md-3 animated-card animated-delay-1">
      <div class="card border-0 shadow-sm rounded-3 h-100 bg-light">
        <div class="card-body">
          <div class="text-muted small mb-1">Top Product</div>
          <div class="fw-semibold">A4 Paper</div>
        </div>
      </div>
    </div>

    <div class="col-md-3 animated-card animated-delay-2">
      <div class="card border-0 shadow-sm rounded-3 h-100 bg-light">
        <div class="card-body">
          <div class="text-muted small mb-1">Best Customer</div>
          <div class="fw-semibold">John D.</div>
        </div>
      </div>
    </div>

    <div class="col-md-3 animated-card animated-delay-3">
      <div class="card border-0 shadow-sm rounded-3 h-100 bg-light">
        <div class="card-body">
          <div class="text-muted small mb-1">Monthly Growth</div>
          <div class="fw-semibold text-success">+12.5%</div>
        </div>
      </div>
    </div>

    <div class="col-md-3 animated-card animated-delay-4">
      <div class="card border-0 shadow-sm rounded-3 h-100 bg-light">
        <div class="card-body">
          <div class="text-muted small mb-1">Total Profit</div>
          <div class="fw-semibold text-primary">₹35,000</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Links Row -->
  <div class="row g-4 mt-4">
    <div class="col-md-6 animated-card animated-delay-3">
      <div class="card border-0 shadow-sm rounded-3 p-3 d-flex justify-content-between align-items-center">
        <div>
          <h6 class="fw-semibold mb-1">📥 Add New Entry</h6>
          <div class="small text-muted">Purchases / Customers</div>
        </div>
        <a href="add_purchase.php" class="btn btn-sm btn-outline-primary rounded-pill">Add</a>
      </div>
    </div>
    <div class="col-md-6 animated-card animated-delay-4">
        <div class="card border-0 shadow-sm rounded-3 p-3 d-flex justify-content-between align-items-center">
            <div>
            <h6 class="fw-semibold mb-1">📄 View Reports</h6>
            <div class="small text-muted">Performance & stats</div>
            </div>
            <a href="reports.php" class="btn btn-sm btn-outline-success rounded-pill">Open</a>
        </div>
    </div>
  </div>

  <div class="row g-4 mt-2">
  <div class="col-md-6 animated-card animated-delay-1">
    <div class="card border-0 shadow-sm rounded-3 p-3 d-flex justify-content-between align-items-center">
      <div>
        <h6 class="fw-semibold mb-1">🛒 Manage Products</h6>
        <div class="small text-muted">Add, edit, or remove items</div>
      </div>
      <a href="products.php" class="btn btn-sm btn-outline-warning rounded-pill">Manage</a>
    </div>
  </div>

  <div class="col-md-6 animated-card animated-delay-2 mb-5">
    <div class="card border-0 shadow-sm rounded-3 p-3 d-flex justify-content-between align-items-center">
      <div>
        <h6 class="fw-semibold mb-1">⚙️ Settings</h6>
        <div class="small text-muted">Customize your system</div>
      </div>
      <a href="settings.php" class="btn btn-sm btn-outline-secondary rounded-pill">Open</a>
    </div>
  </div>
</div>

</div>

<?php
include 'footer.html';
?>