<section class="p-8 flex-1">
<div class="max-w-7xl mx-auto space-y-8">
<!-- Page Header & Filters -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
<div>
<h2 class="text-3xl font-extrabold text-primary tracking-tight headline">Quản lý đặt phòng</h2>
<p class="text-secondary mt-1">Quản lý và theo dõi tất cả các đơn đặt phòng trên nền tảng.</p>
</div>
<div class="flex items-center bg-surface-container-low p-1 rounded-xl">
<button class="px-5 py-2 text-sm font-bold bg-white text-primary rounded-lg shadow-sm">Tất cả</button>
<button class="px-5 py-2 text-sm font-medium text-secondary hover:text-primary transition-colors">Hôm nay</button>
<button class="px-5 py-2 text-sm font-medium text-secondary hover:text-primary transition-colors">Tuần này</button>
<button class="px-5 py-2 text-sm font-medium text-secondary hover:text-primary transition-colors">Chờ xử lý</button>
</div>
</div>
<!-- Stats Overview (Asymmetric Bento Grid) -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
<div class="col-span-1 md:col-span-2 bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/5">
<div class="flex justify-between items-start">
<div>
<p class="text-xs font-bold text-secondary uppercase tracking-widest">Tổng lượt đặt phòng</p>
<h3 class="text-4xl font-extrabold text-primary mt-2">1,284</h3>
<div class="flex items-center gap-1 mt-2 text-on-tertiary-container font-bold text-sm">
<span class="material-symbols-outlined text-sm" data-icon="trending_up">trending_up</span>
<span>+12.5% so với tháng trước</span>
</div>
</div>
<div class="p-3 bg-secondary-container/30 text-primary rounded-xl">
<span class="material-symbols-outlined" data-icon="receipt_long">receipt_long</span>
</div>
</div>
</div>
<div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/5">
<p class="text-xs font-bold text-secondary uppercase tracking-widest">Đã xác nhận</p>
<h3 class="text-3xl font-extrabold text-primary mt-2">942</h3>
<div class="w-full bg-surface-container-high h-1.5 rounded-full mt-4 overflow-hidden">
<div class="bg-on-tertiary-container h-full w-[73%]"></div>
</div>
</div>
<div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-outline-variant/5">
<p class="text-xs font-bold text-secondary uppercase tracking-widest">Đã hủy</p>
<h3 class="text-3xl font-extrabold text-primary mt-2">56</h3>
<div class="w-full bg-surface-container-high h-1.5 rounded-full mt-4 overflow-hidden">
<div class="bg-error h-full w-[4%]"></div>
</div>
</div>
</div>
<!-- Main Data Table -->
<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-outline-variant/5">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low/50 text-secondary text-[11px] font-bold uppercase tracking-widest">
<th class="px-6 py-4">Mã Đơn</th>
<th class="px-6 py-4">Tên khách hàng</th>
<th class="px-6 py-4">Khách sạn đối tác</th>
<th class="px-6 py-4">Loại phòng</th>
<th class="px-6 py-4">Ngày lưu trú</th>
<th class="px-6 py-4">Tổng tiền</th>
<th class="px-6 py-4">Trạng thái</th>
<th class="px-6 py-4 text-right">Hành động</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/10">
<!-- Row 1 -->
<tr class="hover:bg-surface-container-low transition-colors group">
<td class="px-6 py-5 text-sm font-bold text-primary">#BK-9021</td>
<td class="px-6 py-5">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-primary-container text-white flex items-center justify-center text-xs font-bold">JD</div>
<span class="text-sm font-semibold text-on-surface">Johnathan Doe</span>
</div>
</td>
<td class="px-6 py-5 text-sm text-secondary">Azure Bay Resort</td>
<td class="px-6 py-5">
<span class="text-xs px-2 py-1 bg-secondary-container text-on-secondary-fixed-variant rounded font-medium">Deluxe Suite</span>
</td>
<td class="px-6 py-5"><div class="text-xs text-on-surface font-medium">12 Th10 - 15 Th10</div><div class="text-[10px] text-outline">3 Đêm</div></td>
<td class="px-6 py-5 text-sm font-bold text-primary">$1,450.00</td>
<td class="px-6 py-5">
<div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant text-[11px] font-bold"><span class="w-1.5 h-1.5 rounded-full bg-on-tertiary-container"></span> Đã xác nhận</div>
</td>
<td class="px-6 py-5 text-right">
<div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-2 text-primary hover:bg-primary/10 rounded-lg" title="View Details">
<span class="material-symbols-outlined text-lg" data-icon="visibility">visibility</span>
</button>
<button class="p-2 text-secondary hover:bg-secondary/10 rounded-lg" title="Edit">
<span class="material-symbols-outlined text-lg" data-icon="edit">edit</span>
</button>
<button class="p-2 text-error hover:bg-error/10 rounded-lg" title="Cancel">
<span class="material-symbols-outlined text-lg" data-icon="cancel">cancel</span>
</button>
</div>
</td>
</tr>
<!-- Row 2 -->
<tr class="hover:bg-surface-container-low transition-colors group">
<td class="px-6 py-5 text-sm font-bold text-primary">#BK-9022</td>
<td class="px-6 py-5">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-secondary-fixed text-primary flex items-center justify-center text-xs font-bold">SM</div>
<span class="text-sm font-semibold text-on-surface">Sarah Miller</span>
</div>
</td>
<td class="px-6 py-5 text-sm text-secondary">The Heritage Boutique</td>
<td class="px-6 py-5">
<span class="text-xs px-2 py-1 bg-secondary-container text-on-secondary-fixed-variant rounded font-medium">Standard King</span>
</td>
<td class="px-6 py-5"><div class="text-xs text-on-surface font-medium">14 Th10 - 18 Th10</div><div class="text-[10px] text-outline">4 Đêm</div></td>
<td class="px-6 py-5 text-sm font-bold text-primary">$890.00</td>
<td class="px-6 py-5">
<div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed-variant text-[11px] font-bold"><span class="w-1.5 h-1.5 rounded-full bg-secondary"></span> Chờ thanh toán</div>
</td>
<td class="px-6 py-5 text-right">
<div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-2 text-primary hover:bg-primary/10 rounded-lg"><span class="material-symbols-outlined text-lg" data-icon="visibility">visibility</span></button>
<button class="p-2 text-secondary hover:bg-secondary/10 rounded-lg"><span class="material-symbols-outlined text-lg" data-icon="edit">edit</span></button>
<button class="p-2 text-error hover:bg-error/10 rounded-lg"><span class="material-symbols-outlined text-lg" data-icon="cancel">cancel</span></button>
</div>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-surface-container-low transition-colors group">
<td class="px-6 py-5 text-sm font-bold text-primary">#BK-8995</td>
<td class="px-6 py-5">
<div class="flex items-center gap-3">
<img class="w-8 h-8 rounded-full object-cover" data-alt="close-up portrait of a cheerful female traveler with a friendly and warm expression" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBhNTK9x2E2nFTj5atyx-74Nv7-sUuyyrVjF8CsY5Z8eP9iyGvG5ez41clHTOgIq88lh_Y2NX6lAlDFtTarnLgTZe-TQqvHCF4-I3YaHHZE2a-ymuKGrrtZ6zkufwfHj_89l42MlY6PJpUyaldAGnXEfrALYU2Uqgs-_HPhq0uDPgX6alym5jzniUDDpMmjpPpkPpFPRbS3PHyDXkgKD3by2GiraAFoGov9HxYY_JKhMgeVJiIyam8KYAUo2fpNhsnDU4BIz-nYTw"/>
<span class="text-sm font-semibold text-on-surface">Elena Rodriguez</span>
</div>
</td>
<td class="px-6 py-5 text-sm text-secondary">Alpine Lodge</td>
<td class="px-6 py-5">
<span class="text-xs px-2 py-1 bg-secondary-container text-on-secondary-fixed-variant rounded font-medium">Family Villa</span>
</td>
<td class="px-6 py-5"><div class="text-xs text-on-surface font-medium">05 Th10 - 07 Th10</div><div class="text-[10px] text-outline">2 Đêm</div></td>
<td class="px-6 py-5 text-sm font-bold text-primary">$2,100.00</td>
<td class="px-6 py-5">
<div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-error-container text-on-error-container text-[11px] font-bold"><span class="w-1.5 h-1.5 rounded-full bg-error"></span> Đã hủy</div>
</td>
<td class="px-6 py-5 text-right">
<div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-2 text-primary hover:bg-primary/10 rounded-lg"><span class="material-symbols-outlined text-lg" data-icon="visibility">visibility</span></button>
<button class="p-2 text-secondary hover:bg-secondary/10 rounded-lg"><span class="material-symbols-outlined text-lg" data-icon="edit">edit</span></button>
<button class="p-2 text-error hover:bg-error/10 rounded-lg"><span class="material-symbols-outlined text-lg" data-icon="cancel">cancel</span></button>
</div>
</td>
</tr>
<!-- Row 4 -->
<tr class="hover:bg-surface-container-low transition-colors group">
<td class="px-6 py-5 text-sm font-bold text-primary">#BK-9025</td>
<td class="px-6 py-5">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-xs font-bold">MW</div>
<span class="text-sm font-semibold text-on-surface">Marcus Wong</span>
</div>
</td>
<td class="px-6 py-5 text-sm text-secondary">Metropolitan Grand</td>
<td class="px-6 py-5">
<span class="text-xs px-2 py-1 bg-secondary-container text-on-secondary-fixed-variant rounded font-medium">Executive Room</span>
</td>
<td class="px-6 py-5"><div class="text-xs text-on-surface font-medium">20 Th10 - 22 Th10</div><div class="text-[10px] text-outline">2 Đêm</div></td>
<td class="px-6 py-5 text-sm font-bold text-primary">$1,120.00</td>
<td class="px-6 py-5">
<div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant text-[11px] font-bold"><span class="w-1.5 h-1.5 rounded-full bg-on-tertiary-container"></span> Đã xác nhận</div>
</td>
<td class="px-6 py-5 text-right">
<div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-2 text-primary hover:bg-primary/10 rounded-lg"><span class="material-symbols-outlined text-lg" data-icon="visibility">visibility</span></button>
<button class="p-2 text-secondary hover:bg-secondary/10 rounded-lg"><span class="material-symbols-outlined text-lg" data-icon="edit">edit</span></button>
<button class="p-2 text-error hover:bg-error/10 rounded-lg"><span class="material-symbols-outlined text-lg" data-icon="cancel">cancel</span></button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="px-6 py-4 bg-surface-container-low/30 border-t border-outline-variant/10 flex items-center justify-between">
<span class="text-xs text-secondary font-medium">Hiển thị 1 đến 4 trong số 1.284 đơn đặt phòng</span>
<div class="flex items-center gap-2">
<button class="p-1 text-secondary hover:text-primary transition-colors disabled:opacity-30" disabled="">
<span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span>
</button>
<div class="flex items-center gap-1">
<button class="w-8 h-8 rounded-lg bg-primary text-white text-xs font-bold">1</button>
<button class="w-8 h-8 rounded-lg hover:bg-surface-container-high text-secondary text-xs font-bold transition-colors">2</button>
<button class="w-8 h-8 rounded-lg hover:bg-surface-container-high text-secondary text-xs font-bold transition-colors">3</button>
<span class="text-secondary px-1">...</span>
<button class="w-8 h-8 rounded-lg hover:bg-surface-container-high text-secondary text-xs font-bold transition-colors">321</button>
</div>
<button class="p-1 text-secondary hover:text-primary transition-colors">
<span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span>
</button>
</div>
</div>
</div>
</div>
</section>