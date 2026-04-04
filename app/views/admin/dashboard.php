﻿<?php
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
?>
<!-- Page Canvas -->
<main class="p-8 space-y-8 max-w-[1600px] mx-auto">
  <!-- Header Section -->
  <div class="flex flex-col md:flex-row justify-between items-end gap-4">
    <div>
      <h1 class="text-4xl font-extrabold tracking-tighter text-on-surface font-manrope">Tổng quan Phân tích</h1>
    </div>
    <div class="flex gap-3">
      <button class="bg-surface-container-lowest text-primary px-5 py-2.5 rounded-xl font-bold font-manrope shadow-sm border border-outline-variant/10 hover:bg-surface-bright active:scale-95 transition-all">
        Tải báo cáo
      </button>
      <button class="bg-gradient-to-r from-primary to-primary-container text-black px-6 py-2.5 rounded-xl font-bold font-manrope shadow-md hover:opacity-90 active:scale-95 transition-all">
        Làm mới dữ liệu
      </button>
    </div>
  </div>

  <!-- Bento Metrics Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Card 1: Total Revenue -->
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 group hover:bg-surface-bright transition-colors">
      <div class="flex justify-between items-start mb-4">
        <div class="p-3 bg-primary-fixed/30 rounded-lg text-primary">
          <span class="material-symbols-outlined" data-icon="payments">payments</span>
        </div>
        <span class="text-xs font-bold py-1 px-2 bg-tertiary-container/10 text-tertiary rounded-full">+12.5%</span>
      </div>
      <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant font-label mb-1">TỔNG DOANH THU</p>
      <h3 class="text-3xl font-extrabold font-manrope text-on-surface"><?= number_format($data['totalPlatformFee'] ?? 0, 0, ',', '.') ?> VND</h3>
    </div>

    <!-- Card 2: Active Bookings -->
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 group hover:bg-surface-bright transition-colors">
      <div class="flex justify-between items-start mb-4">
        <div class="p-3 bg-secondary-fixed/30 rounded-lg text-secondary">
          <span class="material-symbols-outlined" data-icon="event_available">event_available</span>
        </div>
        <span class="text-xs font-bold py-1 px-2 bg-primary-fixed/20 text-primary rounded-full">Hiện tại</span>
      </div>
      <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant font-label mb-1">ĐƠN ĐẶT PHÒNG ĐANG HOẠT ĐỘNG</p>
      <h3 class="text-3xl font-extrabold font-manrope text-on-surface"><?= $data['totalActiveOrders'] ?? 0 ?></h3>
    </div>

    <!-- Card 3: Registered Hotels -->
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 group hover:bg-surface-bright transition-colors">
      <div class="flex justify-between items-start mb-4">
        <div class="p-3 bg-tertiary-fixed-dim/20 rounded-lg text-tertiary">
          <span class="material-symbols-outlined" data-icon="apartment">apartment</span>
        </div>
        <span class="text-xs font-bold py-1 px-2 bg-surface-container-highest text-on-surface-variant rounded-full">Toàn cầu</span>
      </div>
      <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant font-label mb-1">KHÁCH SẠN ĐÃ ĐĂNG KÝ</p>
      <h3 class="text-3xl font-extrabold font-manrope text-on-surface"><?= $data['totalHotels'] ?? 0 ?></h3>
    </div>

    <!-- Card 4: New Partners -->
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 group hover:bg-surface-bright transition-colors">
      <div class="flex justify-between items-start mb-4">
        <div class="p-3 bg-error-container/30 rounded-lg text-error">
          <span class="material-symbols-outlined" data-icon="handshake">handshake</span>
        </div>
        <span class="text-xs font-bold py-1 px-2 bg-error-container text-error rounded-full">Cần xử lý</span>
      </div>
      <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant font-label mb-1">YÊU CẦU ĐỐI TÁC</p>
      <h3 class="text-3xl font-extrabold font-manrope text-on-surface"><?= $data['totalPartners'] ?? 0 ?></h3>
    </div>
  </div>

  <!-- Central Charts Section -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Revenue Bar Chart (Main) - ĐÃ TÍCH HỢP CHART.JS -->
    <div class="lg:col-span-2 bg-surface-container-lowest p-8 rounded-xl border border-outline-variant/10">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h4 class="text-lg font-bold font-manrope text-on-surface">Doanh thu Đặt phòng Hàng tháng</h4>
          <p class="text-sm text-on-surface-variant font-body">Xu hướng doanh thu trong năm tài chính hiện tại</p>
        </div>
        <div class="flex gap-2">
          <div class="flex items-center gap-2 text-xs font-bold text-on-surface-variant">
            <span class="w-3 h-3 bg-primary rounded-full"></span> Năm nay
          </div>
          <div class="flex items-center gap-2 text-xs font-bold text-on-surface-variant">
            <span class="w-3 h-3 bg-secondary-fixed-dim rounded-full"></span> Năm ngoái
          </div>
        </div>
      </div>

      <!-- Biểu đồ Chart.js -->
      <div class="h-64">
        <canvas id="monthlyRevenueChart"></canvas>
      </div>
    </div>

    <!-- Daily Volume Line Chart -->
    <div class="bg-surface-container-lowest p-8 rounded-xl border border-outline-variant/10">
      <h4 class="text-lg font-bold font-manrope text-on-surface mb-1">Lượng đặt phòng Hàng ngày</h4>
      <p class="text-sm text-on-surface-variant font-body mb-8">Tần suất đặt phòng mỗi ngày</p>
      <div class="relative h-48 flex items-end overflow-hidden">
        <svg class="absolute inset-0 w-full h-full" preserveAspectRatio="none" viewBox="0 0 100 100">
          <path d="M0 80 Q10 70, 20 75 T40 60 T60 65 T80 40 T100 30" fill="none" stroke="#004d64" stroke-linecap="round" stroke-width="3"></path>
          <path d="M0 80 Q10 70, 20 75 T40 60 T60 65 T80 40 T100 30 V100 H0 Z" fill="url(#grad)" opacity="0.1"></path>
          <defs>
            <linearGradient id="grad" x1="0%" x2="0%" y1="0%" y2="100%">
              <stop offset="0%" style="stop-color:#004d64;stop-opacity:1"></stop>
              <stop offset="100%" style="stop-color:#004d64;stop-opacity:0"></stop>
            </linearGradient>
          </defs>
        </svg>
        <div class="flex justify-between w-full mt-auto pt-4 relative z-10">
          <span class="text-[9px] font-bold text-on-surface-variant">THỨ 2</span>
          <span class="text-[9px] font-bold text-on-surface-variant">THỨ 4</span>
          <span class="text-[9px] font-bold text-on-surface-variant">THỨ 6</span>
          <span class="text-[9px] font-bold text-on-surface-variant">CHỦ NHẬT</span>
        </div>
      </div>
      <div class="mt-8 space-y-4">
        <div class="flex justify-between items-center pb-2 border-b border-outline-variant/10">
          <span class="text-xs font-bold text-on-surface-variant">Ngày cao điểm</span>
          <span class="text-xs font-bold text-on-surface">Chủ nhật</span>
        </div>
        <div class="flex justify-between items-center pb-2 border-b border-outline-variant/10">
          <span class="text-xs font-bold text-on-surface-variant">Trung bình ngày</span>
          <span class="text-xs font-bold text-on-surface">142 Đơn đặt</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Activity Table -->
  <section class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 overflow-hidden">
    <div class="px-8 py-6 border-b border-outline-variant/10 flex justify-between items-center">
      <h4 class="text-xl font-extrabold  text-on-surface">Hoạt động Thanh toán Gần đây</h4>
      <button class="text-sm font-bold text-primary hover:underline">Xem tất cả đơn đặt</button>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead>
          <tr class="bg-surface-container-low">
            <th class="px-8 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary font-label">MÃ ĐƠN</th>
            <th class="px-8 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary font-label">KHÁCH HÀNG</th>
            <th class="px-8 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary font-label">PHƯƠNG THỨC</th>
            <th class="px-8 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary font-label">TRẠNG THÁI</th>
            <th class="px-8 py-4 text-[10px] font-bold uppercase tracking-widest text-secondary font-label text-right">SỐ TIỀN</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/10 font-inter">
          <?php foreach ($data['recentBookings'] as $booking): ?>
          <!-- Các dòng bảng giữ nguyên như cũ -->
          <tr class="hover:bg-surface-bright transition-colors">
            <td class="px-8 py-4 text-sm font-bold text-primary"># <?php echo $booking['id']; ?></td>
            <td class="px-8 py-4">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold text-xs"><img src="<?= $booking['avatarUrl'] ?>" alt="<?= $booking['fullName'] ?>"></div>
                <div>
                  <p class="text-sm font-bold text-on-surface"><?= $booking['fullName'] ?></p>
                  <p class="text-[10px] text-on-surface-variant"><?= $booking['email'] ?></p>
                </div>
              </div>
            </td>
            <td class="px-8 py-4">
              <p class="text-sm font-bold text-on-surface"> <?= $booking['paymentMethod'] ?></p>
              <p class="text-[10px] text-secondary font-medium uppercase tracking-tight"><?= $booking['paymentCreatedAt'] ?></p>
            </td>
            <td class="px-8 py-4">
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-tertiary/10 text-tertiary">
                <span class="w-1.5 h-1.5 rounded-full bg-tertiary"></span>
                <?= $booking['paymentStatus'] ?>
              </span>
            </td>
            <td class="px-8 py-4 text-right text-sm font-bold text-on-surface">$1,250.00</td>
          </tr>
          <!-- Các dòng khác giữ nguyên (để ngắn gọn mình giữ 4 dòng như file gốc) -->
          <!-- ... (copy 3 dòng còn lại từ file cũ của bạn) ... -->
           <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="px-8 py-4 bg-surface-container-low/50 border-t border-outline-variant/10 flex justify-between items-center">
      <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-tighter">HIỂN THỊ 4 TRÊN 1.284 MỤC</p>
      <div class="flex gap-1">
        <button class="p-1 hover:bg-white rounded transition-colors text-on-surface-variant">
          <span class="material-symbols-outlined text-sm" data-icon="chevron_left">chevron_left</span>
        </button>
        <button class="px-2 py-1 bg-primary text-white text-[10px] font-bold rounded">1</button>
        <button class="px-2 py-1 hover:bg-white text-[10px] font-bold rounded text-on-surface-variant">2</button>
        <button class="px-2 py-1 hover:bg-white text-[10px] font-bold rounded text-on-surface-variant">3</button>
        <button class="p-1 hover:bg-white rounded transition-colors text-on-surface-variant">
          <span class="material-symbols-outlined text-sm" data-icon="chevron_right">chevron_right</span>
        </button>
      </div>
    </div>
  </section>
</main>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Dữ liệu từ PHP
    const monthlyData = <?= json_encode($data['monthlyRevenue'] ?? []) ?>;

    const labels = monthlyData.map(item => 'Tháng ' + item.month);
    const revenues = monthlyData.map(item => item.revenue);

    new Chart(document.getElementById('monthlyRevenueChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu Năm nay',
                data: revenues,
                backgroundColor: '#2563EB',
                borderColor: '#1E40AF',
                borderWidth: 2,
                borderRadius: 8,
                barThickness: 32
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => ctx.raw.toLocaleString('vi-VN') + ' VND'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#e2e8f0' },
                    ticks: {
                        font: { size: 12 },
                        callback: (value) => value.toLocaleString('vi-VN') + 'đ'
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 12 } }
                }
            }
        }
    });
</script>
