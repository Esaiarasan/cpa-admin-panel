<?php

require_once '../config.php';

/* -----------------------------------------
   🔒 SESSION PROTECTION
------------------------------------------ */

// BLOCK access if session not set
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// PREVENT browser back/forward caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

/* -----------------------------------------
   🔚 END OF PROTECTION
------------------------------------------ */

 $pageTitle = "Dashboard";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

?>

<div class="main-content">
  <div class="card">
    <div class="card-body">
      <div class="chart-container">
        <canvas id="doughnutChart"></canvas>
      </div>
      <div class="barchart-container">
        <canvas id="barChart"></canvas>
      </div>
    </div>
  </div>
</div>

<?php 
 $customScripts = '<script>initCharts();</script>';
require_once '../includes/footer.php'; 
?>

<script>
    function initCharts() {
  const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
  const barCtx = document.getElementById('barChart').getContext('2d');

  new Chart(doughnutCtx, {
    type: 'doughnut',
    data: {
      labels: ['A', 'B', 'C'],
      datasets: [{
        data: [40, 30, 30],
        backgroundColor: ['#16ace5', '#ff6384', '#ffce56']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom' }
      }
    }
  });

  new Chart(barCtx, {
    type: 'bar',
    data: {
      labels: ['Q1', 'Q2', 'Q3', 'Q4'],
      datasets: [{
        label: 'Performance',
        data: [12, 19, 8, 15],
        backgroundColor: '#16ace5',
        borderRadius: 5
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: { beginAtZero: true }
      },
      plugins: {
        legend: { display: false }
      }
    }
  });
}

</script>


<style>
    
</style>