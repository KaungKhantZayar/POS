<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';
include 'header.php';
?>

<!-- Dashboard Styles -->
<style>
  body {
    background: #f5f7fa;
    font-family: 'Segoe UI', sans-serif;
  }

  /* Fade-in + Slide-up Animation */
  @keyframes fadeSlideUp {
    0% { opacity: 0; transform: translateY(-30px); }
    100% { opacity: 1; transform: translateY(0); }
  }

  .animated-card {
    opacity: 0;
    animation: fadeSlideUp 0.8s ease forwards;
  }

  .animated-delay-1 { animation-delay: 0.1s; }
  .animated-delay-2 { animation-delay: 0.2s; }
  .animated-delay-3 { animation-delay: 0.3s; }
  .animated-delay-4 { animation-delay: 0.4s; }

  /* Smart card design */
  .card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.12);
  }

  .card .card-body {
    padding: 1.5rem 1.5rem;
  }

  /* Metric headers */
  .card h4, .card h6 {
    font-weight: 600;
  }

  /* Clock */
  #current-time {
    display: flex;
    align-items: center;
    font-size: 1rem;
    color: #6c757d;
    gap: 0.4rem;
    font-weight: 500;
  }
  #current-time .clock-icon {
    font-size: 2rem;
    animation: pulseGlow 2.5s infinite ease-in-out;
  }
  #current-time .time-text {
    animation: bounceFade 1s infinite;
  }
  @keyframes pulseGlow {
    0%, 100% { opacity: 1; text-shadow: 0 0 6px rgba(0,123,255,0.7); }
    50% { opacity: 0.7; text-shadow: 0 0 16px rgba(0,123,255,1); }
  }
  @keyframes bounceFade {
    0%, 100% { transform: translateY(0); opacity: 1; }
    50% { transform: translateY(-3px); opacity: 0.75; }
  }

  /* Gradient cards for metrics */
  .gradient-primary { background: linear-gradient(135deg, #4e73df, #224abe); color: #fff; }
  .gradient-success { background: linear-gradient(135deg, #1cc88a, #17a673); color: #fff; }
  .gradient-warning { background: linear-gradient(135deg, #f6c23e, #dda20a); color: #fff; }
  .gradient-danger  { background: linear-gradient(135deg, #e74a3b, #be2617); color: #fff; }

  /* Quick link cards */
  .quick-link-card {
    transition: all 0.3s ease;
    cursor: pointer;
  }
  .quick-link-card:hover {
    transform: scale(1.03);
    box-shadow: 0 12px 28px rgba(0,0,0,0.12);
  }
</style>

<div class="container mt-3">
  <div class="d-flex mb-4 justify-content-between align-items-center">
    <h2 class="d-flex align-items-center">📊 Dashboard</h2>
    <div id="current-time">
      <span class="clock-icon">🕒</span>
      <span class="time-text">00:00:00</span>
    </div>
  </div>

  <script>
  function updateTime() {
    const now = new Date();
    const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute:'2-digit', second:'2-digit' });
    document.querySelector('#current-time .time-text').textContent = timeStr;
  }
  updateTime();
  setInterval(updateTime, 1000);
  </script>

<!-- Metrics Row -->
<div class="row">
  <div class="col-md-3 animated-card animated-delay-1">
    <div class="card gradient-primary">
      <div class="card-body">
        <div class="small mb-2">Total Sales</div>
        <h4>₹1,20,000</h4>
      </div>
    </div>
  </div>
  <div class="col-md-3 animated-card animated-delay-2">
    <div class="card gradient-success">
      <div class="card-body">
        <div class="small mb-2">Customers</div>
        <h4>320</h4>
      </div>
    </div>
  </div>
  <div class="col-md-3 animated-card animated-delay-3">
    <div class="card gradient-warning">
      <div class="card-body">
        <div class="small mb-2">Pending Orders</div>
        <h4>18</h4>
      </div>
    </div>
  </div>
  <div class="col-md-3 animated-card animated-delay-4">
    <div class="card gradient-danger">
      <div class="card-body">
        <div class="small mb-2">Items in Stock</div>
        <h4>145</h4>
      </div>
    </div>
  </div>
</div>

<!-- Insights Row -->
<div class="row g-4">
  <div class="col-md-3 animated-card animated-delay-1">
    <div class="card shadow-sm h-100 bg-white">
      <div class="card-body">
        <div class="small text-muted mb-1">Top Product</div>
        <div class="fw-semibold">A4 Paper</div>
      </div>
    </div>
  </div>
  <div class="col-md-3 animated-card animated-delay-2">
    <div class="card shadow-sm h-100 bg-white">
      <div class="card-body">
        <div class="small text-muted mb-1">Best Customer</div>
        <div class="fw-semibold">John D.</div>
      </div>
    </div>
  </div>
  <div class="col-md-3 animated-card animated-delay-3">
    <div class="card shadow-sm h-100 bg-white">
      <div class="card-body">
        <div class="small text-muted mb-1">Monthly Growth</div>
        <div class="fw-semibold text-success">+12.5%</div>
      </div>
    </div>
  </div>
  <div class="col-md-3 animated-card animated-delay-4">
    <div class="card shadow-sm h-100 bg-white">
      <div class="card-body">
        <div class="small text-muted mb-1">Total Profit</div>
        <div class="fw-semibold text-primary">₹35,000</div>
      </div>
    </div>
  </div>
</div>

<!-- Quick Links -->
<div class="row g-4 mt-1">
  <div class="col-md-6 animated-card animated-delay-3">
    <div class="card quick-link-card h-100 d-flex justify-content-between align-items-center p-3">
      <div>
        <h6 class="fw-semibold mb-1">📥 Add New Entry</h6>
        <div class="small text-muted">Purchases / Customers</div>
      </div>
      <a href="add_purchase.php" class="btn btn-outline-primary rounded-pill btn-sm">Add</a>
    </div>
  </div>
  <div class="col-md-6 animated-card animated-delay-4">
    <div class="card quick-link-card h-100 d-flex justify-content-between align-items-center p-3">
      <div>
        <h6 class="fw-semibold mb-1">📄 View Reports</h6>
        <div class="small text-muted">Performance & Stats</div>
      </div>
      <a href="reports.php" class="btn btn-outline-success rounded-pill btn-sm">Open</a>
    </div>
  </div>
</div>

<div class="row g-4 mt-1">
  <div class="col-md-6 animated-card animated-delay-1">
    <div class="card quick-link-card h-100 d-flex justify-content-between align-items-center p-3">
      <div>
        <h6 class="fw-semibold mb-1">🛒 Manage Products</h6>
        <div class="small text-muted">Add, edit, or remove items</div>
      </div>
      <a href="products.php" class="btn btn-outline-warning rounded-pill btn-sm">Manage</a>
    </div>
  </div>
  <div class="col-md-6 animated-card animated-delay-2">
    <div class="card quick-link-card h-100 d-flex justify-content-between align-items-center p-3">
      <div>
        <h6 class="fw-semibold mb-1">⚙️ Settings</h6>
        <div class="small text-muted">Customize your system</div>
      </div>
      <a href="settings.php" class="btn btn-outline-secondary rounded-pill btn-sm">Open</a>
    </div>
  </div>
</div>

</div>

<?php include 'footer.html'; ?>
