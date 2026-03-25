<div class="p-8 max-w-7xl mx-auto space-y-10">
<!-- Section Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
<div class="space-y-1">
<h1 class="text-4xl font-extrabold tracking-tight text-primary headline-md">Quản lý khách sạn</h1>
<p class="text-on-secondary-container body-md">Điều hành và giám sát hệ thống đối tác khách sạn cao cấp toàn cầu.</p>
</div>
<button class="flex items-center gap-2 bg-gradient-to-br from-primary to-primary-container text-white py-3 px-8 rounded-full text-sm font-bold shadow-lg hover:shadow-xl hover:translate-y-[-2px] transition-all active:scale-95 uppercase tracking-widest">
<span class="material-symbols-outlined" data-icon="add_business">add_business</span>
                    Thêm đối tác mới
                </button>
</div>
<!-- KPI Cards - Bento Grid Inspired -->
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
<div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-transparent hover:border-primary/5 transition-all group">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-secondary-container/30 rounded-lg group-hover:bg-secondary-container/50 transition-colors">
<span class="material-symbols-outlined text-primary" data-icon="group">group</span>
</div>
<span class="text-on-tertiary-container text-xs font-bold bg-on-tertiary-container/10 px-2 py-1 rounded-full">+4%</span>
</div>
<p class="text-secondary-container font-semibold text-xs uppercase tracking-wider mb-1">Tổng đối tác</p>
<h3 class="text-3xl font-extrabold text-primary font-headline">842</h3>
</div>
<div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-transparent hover:border-primary/5 transition-all group">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-on-tertiary-container/10 rounded-lg group-hover:bg-on-tertiary-container/20 transition-colors">
<span class="material-symbols-outlined text-on-tertiary-container" data-icon="check_circle">check_circle</span>
</div>
<span class="text-on-tertiary-container text-xs font-bold bg-on-tertiary-container/10 px-2 py-1 rounded-full">Ổn định</span>
</div>
<p class="text-secondary-container font-semibold text-xs uppercase tracking-wider mb-1">Đang hoạt động</p>
<h3 class="text-3xl font-extrabold text-primary font-headline">780</h3>
</div>
<div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border border-transparent hover:border-primary/5 transition-all group">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-amber-100 rounded-lg group-hover:bg-amber-200 transition-colors text-amber-600">
<span class="material-symbols-outlined" data-icon="pending_actions">pending_actions</span>
</div>
<span class="text-amber-600 text-xs font-bold bg-amber-50 px-2 py-1 rounded-full">Khẩn cấp</span>
</div>
<p class="text-secondary-container font-semibold text-xs uppercase tracking-wider mb-1">Chờ phê duyệt</p>
<h3 class="text-3xl font-extrabold text-primary font-headline">42</h3>
</div>
<div class="bg-primary text-white p-6 rounded-xl shadow-xl border border-transparent hover:translate-y-[-4px] transition-all relative overflow-hidden">
<!-- Subtle background decoration -->
<div class="absolute top-[-20%] right-[-10%] w-32 h-32 bg-primary-container rounded-full opacity-50 blur-2xl"></div>
<div class="relative z-10">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-white/10 rounded-lg">
<span class="material-symbols-outlined" data-icon="payments">payments</span>
</div>
</div>
<p class="text-white/60 font-semibold text-xs uppercase tracking-wider mb-1">Doanh thu đối tác</p>
<h3 class="text-3xl font-extrabold font-headline">15.2 <span class="text-lg font-medium opacity-80">tỷ VNĐ</span></h3>
</div>
</div>
</section>
<!-- Filter Bar -->
<section class="bg-surface-container-low p-6 rounded-xl space-y-4">
<div class="flex flex-wrap items-center gap-4">
<div class="flex-1 min-w-[300px] relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline" data-icon="search">search</span>
<input class="w-full bg-surface-container-lowest border-none rounded-full py-3 pl-12 pr-6 text-sm focus:ring-2 focus:ring-primary/20 transition-all shadow-sm" placeholder="Tìm kiếm tên khách sạn, mã đối tác..." type="text"/>
</div>
<div class="flex flex-wrap items-center gap-3">
<select class="bg-surface-container-lowest border-none rounded-full py-2.5 px-6 text-sm font-medium shadow-sm focus:ring-2 focus:ring-primary/20 cursor-pointer">
<option value="">Thành phố</option>
<option>Đà Nẵng</option>
<option>Hà Nội</option>
<option>TP. HCM</option>
</select>
<select class="bg-surface-container-lowest border-none rounded-full py-2.5 px-6 text-sm font-medium shadow-sm focus:ring-2 focus:ring-primary/20 cursor-pointer">
<option value="">Trạng thái</option>
<option>Hoạt động</option>
<option>Tạm dừng</option>
<option>Chờ duyệt</option>
</select>
<select class="bg-surface-container-lowest border-none rounded-full py-2.5 px-6 text-sm font-medium shadow-sm focus:ring-2 focus:ring-primary/20 cursor-pointer">
<option value="">Hạng sao</option>
<option>5* Premium</option>
<option>4* Luxury</option>
<option>3* Standard</option>
</select>
<button class="bg-surface-container-highest p-2.5 rounded-full hover:bg-surface-container-high transition-colors text-primary">
<span class="material-symbols-outlined" data-icon="filter_list">filter_list</span>
</button>
</div>
</div>
</section>
<!-- Data Table Container -->
<section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden mb-12">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low/50">
<th class="px-6 py-5 text-xs font-bold text-on-secondary-container uppercase tracking-wider">Tên khách sạn</th>
<th class="px-6 py-5 text-xs font-bold text-on-secondary-container uppercase tracking-wider">Địa điểm</th>
<th class="px-6 py-5 text-xs font-bold text-on-secondary-container uppercase tracking-wider">Phòng</th>
<th class="px-6 py-5 text-xs font-bold text-on-secondary-container uppercase tracking-wider">Tỷ lệ lấp đầy</th>
<th class="px-6 py-5 text-xs font-bold text-on-secondary-container uppercase tracking-wider">Trạng thái</th>
<th class="px-6 py-5 text-xs font-bold text-on-secondary-container uppercase tracking-wider text-right">Hành động</th>
</tr>
</thead>
<tbody class="divide-y divide-surface-container">
<!-- Row 1 -->
<tr class="hover:bg-surface-container-low transition-colors group">
<td class="px-6 py-5">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-lg bg-primary/5 flex items-center justify-center text-primary overflow-hidden">
<img alt="Azure Bay Resort" class="w-full h-full object-cover" data-alt="Modern luxury beach resort exterior with blue pool and palm trees under a clear sky, architectural photography" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBLzoDwRLTOiSB9fBA0oec638Xvu6E_SewOA-YN57cdfI0tpbZEAfaN0fAWs6-d-BBCu-Qf0iW6d5Zz0n62cXdVRT9Hx38yeh85LPJUAVDwykBXTUlC_Um-NnJw1ZpM23XbL03RYZAekRF7h7nOUZFDI1hBbIVGbn-peLsRgZyNu3l7tbLkUT6huk1wWZ7ql5N7MFu-Hi5lIwAPyKq8CmJtxLP-624wu4PNGh0slk_nr1dUvJpACcIWZvF2KZZKuI5yXFmq86jxzA"/>
</div>
<div>
<p class="font-bold text-primary text-sm">Azure Bay Resort</p>
<p class="text-[10px] text-on-tertiary-container font-bold flex items-center gap-1">
<span class="material-symbols-outlined text-[12px]" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
                                                5* Premium Partner
                                            </p>
</div>
</div>
</td>
<td class="px-6 py-5">
<div class="flex items-center gap-2 text-on-secondary-container text-sm">
<span class="material-symbols-outlined text-sm" data-icon="location_on">location_on</span>
                                        Đà Nẵng
                                    </div>
</td>
<td class="px-6 py-5 text-sm font-medium text-primary">120</td>
<td class="px-6 py-5">
<div class="flex items-center gap-3">
<div class="flex-1 h-1.5 w-16 bg-surface-container rounded-full overflow-hidden">
<div class="h-full bg-emerald-500 rounded-full" style="width: 85%"></div>
</div>
<span class="text-sm font-bold text-emerald-700">85%</span>
    </div>
  </td>
  
  <td class="px-6 py-5">
    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 uppercase tracking-wider ring-1 ring-emerald-600/10">
      <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
      Hoạt động
    </span>
  </td>
<td class="px-6 py-5 text-right">
<div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Xem chi tiết">
<span class="material-symbols-outlined text-sm" data-icon="visibility">visibility</span>
</button>
<button class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Chỉnh sửa">
<span class="material-symbols-outlined text-sm" data-icon="edit">edit</span>
</button>
<button class="p-2 text-error hover:bg-error/10 rounded-lg transition-colors" title="Ngừng hợp tác">
<span class="material-symbols-outlined text-sm" data-icon="block">block</span>
</button>
</div>
</td>
</tr>
<!-- Row 2 -->
<tr class="hover:bg-surface-container-low transition-colors group">
<td class="px-6 py-5">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-lg bg-primary/5 flex items-center justify-center text-primary overflow-hidden">
<img alt="Grand Plaza" class="w-full h-full object-cover" data-alt="Grand luxury hotel facade with gold accents and marble entrance, nighttime with warm elegant lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC76w6qBsWbZ5YqHOfN4Cvz9neUWSwU3lnzMd6dcy-QaWsHnRcd2B8xmS9zwVTRqK7S0YPqch6m-eQgPr6-etYrSdF_t8tecwYpf52Eb0SIpy7Vo0DPrYxUlH9WcWhPppWiCMCSMpXo3OdIb1cxEE8oR5RHj9-hxWNDr1d1Sjg5-xg_ytwZ8WBXmO5D6yyGYUmKEljoadNbaAUFueW-DkootheMAoQ4qZdej_kuKRnB_8Kr8uAWmE0Lt8F_JRodwgLV_ZOH_3EpOQ"/>
</div>
<div>
<p class="font-bold text-primary text-sm">Grand Plaza</p>
<p class="text-[10px] text-on-tertiary-container font-bold flex items-center gap-1">
<span class="material-symbols-outlined text-[12px]" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
                                                5* Premium Partner
                                            </p>
</div>
</div>
</td>
<td class="px-6 py-5">
<div class="flex items-center gap-2 text-on-secondary-container text-sm">
<span class="material-symbols-outlined text-sm" data-icon="location_on">location_on</span>
                                        Hà Nội
                                    </div>
</td>
<td class="px-6 py-5 text-sm font-medium text-primary">250</td>
<td class="px-6 py-5">
<div class="flex items-center gap-3">
<div class="flex-1 h-1.5 w-16 bg-surface-container rounded-full overflow-hidden">
<div class="h-full bg-emerald-500 rounded-full" style="width: 92%"></div>
</div>
<span class="text-sm font-bold text-emerald-600">92%</span>
</div>
</td>
<td class="px-6 py-5">
<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600 uppercase tracking-wider">
<span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Chờ duyệt
                                    </span>
</td>
<td class="px-6 py-5 text-right">
<div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Xem chi tiết">
<span class="material-symbols-outlined text-sm" data-icon="visibility">visibility</span>
</button>
<button class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Chỉnh sửa">
<span class="material-symbols-outlined text-sm" data-icon="edit">edit</span>
</button>
<button class="p-2 text-error hover:bg-error/10 rounded-lg transition-colors" title="Ngừng hợp tác">
<span class="material-symbols-outlined text-sm" data-icon="block">block</span>
</button>
</div>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-surface-container-low transition-colors group">
<td class="px-6 py-5">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-lg bg-primary/5 flex items-center justify-center text-primary overflow-hidden">
<img alt="Skyline Boutique" class="w-full h-full object-cover" data-alt="Chic boutique hotel lobby with modern art deco furniture and warm ambient lighting, elegant interior design" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCF_d58kpruZuBsnqhxipCaHBqGL5mns6j2KF5tLKn7POSrxBYLO64TdrkS6d_3iTw9yI2L6gT-Ah6EoQ5aajz-Hvb-PwPsL2STIQtb8HfaeHbJEtZilUKD5v6kv9VHlScg8TyJ5k8yBaHSv4i75gw-Wyz3v5dzmOmeYYDW022oQyQGo4nnjajjQ8CG_jB4xBak0ELF7IDx3mVUW3upergXTm7DH0mhmAwVD0sKQ00y0DbE-7PMr0N6n9rsiKGC5U6M2Aqo79KW6A"/>
</div>
<div>
<p class="font-bold text-primary text-sm">Skyline Boutique</p>
<p class="text-[10px] text-slate-500 font-bold flex items-center gap-1">
<span class="material-symbols-outlined text-[12px]" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
                                                4* Luxury Partner
                                            </p>
</div>
</div>
</td>
<td class="px-6 py-5">
<div class="flex items-center gap-2 text-on-secondary-container text-sm">
<span class="material-symbols-outlined text-sm" data-icon="location_on">location_on</span>
                                        TP. HCM
                                    </div>
</td>
<td class="px-6 py-5 text-sm font-medium text-primary">80</td>
<td class="px-6 py-5">
<div class="flex items-center gap-3">
<div class="flex-1 h-1.5 w-16 bg-surface-container rounded-full overflow-hidden">
<div class="h-full bg-slate-400 rounded-full" style="width: 78%"></div>
</div>
<span class="text-sm font-bold text-slate-500">78%</span>
</div>
</td>
<td class="px-6 py-5">
<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 uppercase tracking-wider">
<span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Tạm dừng
                                    </span>
</td>
<td class="px-6 py-5 text-right">
<div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Xem chi tiết">
<span class="material-symbols-outlined text-sm" data-icon="visibility">visibility</span>
</button>
<button class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Chỉnh sửa">
<span class="material-symbols-outlined text-sm" data-icon="edit">edit</span>
</button>
<button class="p-2 text-error hover:bg-error/10 rounded-lg transition-colors" title="Ngừng hợp tác">
<span class="material-symbols-outlined text-sm" data-icon="block">block</span>
</button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="px-6 py-4 flex items-center justify-between bg-surface-container-low/30 border-t border-surface-container">
<p class="text-xs text-on-secondary-container font-medium">Hiển thị <span class="text-primary font-bold">1-10</span> trong số <span class="text-primary font-bold">842</span> đối tác</p>
<div class="flex items-center gap-2">
<button class="p-2 text-slate-400 hover:text-primary transition-colors disabled:opacity-30" disabled="">
<span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span>
</button>
<button class="w-8 h-8 flex items-center justify-center text-xs font-bold bg-primary text-white rounded-lg">1</button>
<button class="w-8 h-8 flex items-center justify-center text-xs font-bold text-on-secondary-container hover:bg-surface-container-high rounded-lg transition-colors">2</button>
<button class="w-8 h-8 flex items-center justify-center text-xs font-bold text-on-secondary-container hover:bg-surface-container-high rounded-lg transition-colors">3</button>
<span class="text-xs text-slate-400">...</span>
<button class="w-8 h-8 flex items-center justify-center text-xs font-bold text-on-secondary-container hover:bg-surface-container-high rounded-lg transition-colors">85</button>
<button class="p-2 text-slate-400 hover:text-primary transition-colors">
<span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span>
</button>
</div>
</div>
</section>
</div>