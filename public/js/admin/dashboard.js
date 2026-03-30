const lineCtx = document.getElementById('dashboard-line-chart').getContext('2d');
const lineChart = new Chart(lineCtx, {
  type: 'line',
  data: {
    labels: ['T1','T2','T3','T4','T5','T6','T7','T8','T9','T10','T11','T12'],
    datasets: [{
      label: 'Doanh thu (VNĐ)',
      data: [85, 92, 78, 115, 140, 158, 190, 175, 148, 165, 178, 200],
      borderColor: '#2ecc71',
      backgroundColor: 'rgba(46, 204, 113, 0.2)',
      fill: true,
      tension: 0.3
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } }
  }
});

const donutCtx = document.getElementById('dashboard-donut-chart').getContext('2d');
const donutChart = new Chart(donutCtx, {
  type: 'doughnut',
  data: {
    labels: ['Hoàn thành','Đã xác nhận','Chờ xử lý','Đã hủy'],
    datasets: [{
      data: [2580, 420, 165, 235],
      backgroundColor: ['#2ecc71','#3498db','#f1c40f','#e74c3c']
    }]
  },
  options: { responsive: true, plugins: { legend: { display: false } } }
});




const sourceCtx = document.getElementById('dashboard-source-bar-chart').getContext('2d');
const sourceChart = new Chart(sourceCtx, {
  type: 'bar',
  data: {
    labels: ['Website','Booking.com','Expedia','Trực tiếp'],
    datasets: [{
      data: [1200, 850, 400, 700],
      backgroundColor: ['#3498db','#2ecc71','#f1c40f','#9b59b6']
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
      y: { beginAtZero: true }
    }
  }
});


