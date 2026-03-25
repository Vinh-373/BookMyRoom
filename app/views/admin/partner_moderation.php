
<!-- Page Header -->
<div class="flex justify-between items-end mb-10">
<div>
<nav class="flex text-xs font-medium text-outline mb-2 gap-2">
<span>Hệ thống</span>
<span>/</span>
<span class="text-primary-container">Phê duyệt</span>
</nav>
<h1 class="text-3xl font-extrabold text-primary tracking-tight">Phê duyệt đối tác mới</h1>
</div>
<div class="flex gap-3">
<button class="bg-surface-container-high text-primary px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2">
<span class="material-symbols-outlined text-lg">history</span>
                    Lịch sử duyệt
                </button>
<button class="bg-gradient-to-br from-primary to-primary-container text-white px-6 py-2 rounded-xl text-sm font-bold shadow-lg shadow-primary/20">
                    Xuất báo cáo
                </button>
</div>
</div>
<!-- Quick Stats Bento Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
<!-- Stat 1 -->
<div class="bg-surface-container-lowest p-6 rounded-full shadow-sm flex items-center justify-between group transition-all hover:scale-[1.02]">
<div>
<p class="text-outline text-xs font-bold uppercase tracking-widest mb-1">Tổng hồ sơ chờ duyệt</p>
<h3 class="text-4xl font-extrabold text-primary">124</h3>
<p class="text-[10px] mt-2 flex items-center gap-1 text-emerald-600 font-semibold">
<span class="material-symbols-outlined text-xs text-emerald-600">trending_up</span>
                        +12% so với tuần trước
                    </p>
</div>
<div class="bg-primary/5 p-4 rounded-2xl text-primary group-hover:bg-primary group-hover:text-white transition-colors">
<span class="material-symbols-outlined text-3xl">pending_actions</span>
</div>
</div>
<!-- Stat 2 -->
<div class="bg-surface-container-lowest p-6 rounded-full shadow-sm flex items-center justify-between group transition-all hover:scale-[1.02]">
<div>
<p class="text-outline text-xs font-bold uppercase tracking-widest mb-1">Đã duyệt hôm nay</p>
<h3 class="text-4xl font-extrabold text-primary">48</h3>
<p class="text-[10px] mt-2 flex items-center gap-1 text-emerald-600 font-semibold">
<span class="material-symbols-outlined text-xs text-emerald-600">check_circle</span>
                        Đạt 80% KPI ngày
                    </p>
</div>
<div class="bg-emerald-100 p-4 rounded-2xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
<span class="material-symbols-outlined text-3xl">verified</span>
</div>
</div>
<!-- Stat 3 -->
<div class="bg-surface-container-lowest p-6 rounded-full shadow-sm flex items-center justify-between group transition-all hover:scale-[1.02]">
<div>
<p class="text-outline text-xs font-bold uppercase tracking-widest mb-1">Tỷ lệ chấp thuận</p>
<h3 class="text-4xl font-extrabold text-primary">92.4<span class="text-xl">%</span></h3>
<div class="w-24 h-1.5 bg-surface-container mt-3 rounded-full overflow-hidden">
<div class="bg-emerald-600 h-full w-[92%]"></div>
</div>
</div>
<div class="bg-emerald-100 p-4 rounded-2xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
<span class="material-symbols-outlined text-3xl">analytics</span>
</div>
</div>
</div>
<!-- Filter Shell -->
<div class="bg-surface-container-low p-5 rounded-full mb-6 flex flex-wrap items-center gap-4">
<div class="flex-1 min-w-[300px] flex items-center bg-surface-container-lowest px-4 py-2.5 rounded-xl gap-3 shadow-sm">
<span class="material-symbols-outlined text-outline">search</span>
<input class="bg-transparent border-none focus:ring-0 text-sm w-full" placeholder="Tìm tên khách sạn, mã đối tác..." type="text"/>
</div>
<div class="flex items-center gap-3">
<!-- Dropdowns using surface-container-lowest -->
<div class="relative">
<select class="appearance-none bg-surface-container-lowest border-none rounded-xl px-4 py-2.5 pr-10 text-xs font-bold text-primary focus:ring-0 shadow-sm cursor-pointer">
<option>Thành phố: Tất cả</option>
<option>Đà Nẵng</option>
<option>Nha Trang</option>
<option>Hội An</option>
</select>
<span class="material-symbols-outlined absolute right-3 top-2.5 text-outline pointer-events-none">expand_more</span>
</div>
<div class="relative">
<select class="appearance-none bg-surface-container-lowest border-none rounded-xl px-4 py-2.5 pr-10 text-xs font-bold text-primary focus:ring-0 shadow-sm cursor-pointer">
<option>Hạng sao: Tất cả</option>
<option>5 Sao</option>
<option>4 Sao</option>
<option>3 Sao</option>
</select>
<span class="material-symbols-outlined absolute right-3 top-2.5 text-outline pointer-events-none">expand_more</span>
</div>
<div class="relative">
<select class="appearance-none bg-surface-container-lowest border-none rounded-xl px-4 py-2.5 pr-10 text-xs font-bold text-primary focus:ring-0 shadow-sm cursor-pointer">
<option>Trạng thái: Đang chờ</option>
<option>Chờ duyệt</option>
<option>Cần bổ sung</option>
</select>
<span class="material-symbols-outlined absolute right-3 top-2.5 text-outline pointer-events-none">expand_more</span>
</div>
<button class="bg-primary text-white p-2.5 rounded-xl shadow-md">
<span class="material-symbols-outlined">filter_list</span>
</button>
</div>
</div>
<!-- Data Table Section -->
<div class="bg-surface-container-lowest rounded-full overflow-hidden shadow-sm">
<table class="w-full border-collapse">
<thead>
<tr class="bg-surface-container text-left">
<th class="px-8 py-4 text-[11px] font-extrabold text-outline uppercase tracking-widest">Tên khách sạn</th>
<th class="px-6 py-4 text-[11px] font-extrabold text-outline uppercase tracking-widest">Địa điểm</th>
<th class="px-6 py-4 text-[11px] font-extrabold text-outline uppercase tracking-widest">Hạng sao</th>
<th class="px-6 py-4 text-[11px] font-extrabold text-outline uppercase tracking-widest">Ngày yêu cầu</th>
<th class="px-6 py-4 text-[11px] font-extrabold text-outline uppercase tracking-widest">Trạng thái</th>
<th class="px-8 py-4 text-[11px] font-extrabold text-outline uppercase tracking-widest text-right">Hành động</th>
</tr>
</thead>
<tbody class="divide-y divide-surface-container">
<!-- Row 1 -->
<tr class="hover:bg-surface-container-high/40 transition-colors group">
<td class="px-8 py-5">
<div class="flex items-center gap-4">
<img alt="Grand Azure Resort" class="w-12 h-12 rounded-xl object-cover shadow-sm" data-alt="luxury beachfront hotel with infinity pool and palm trees at sunset" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBgiDgwuCnb2KcAIcMmQRqPkoy_eijuR_E3OevdEhstYJjf-BCqEph9YAEk8Zkw77O_2gl0HkhgojN1PuwRIe48xDIeIrx1zBYvuu-j0c3qkmgA86C0BJOxkUGmyPkej4wTKGWHGWlMFM6PDAU7GiAIm8bKWoXZC0g--o8Pqyc5Rv7mYmnoSGrf-aDiUPuO9NpS0sEoXOHYONZId0-KBnHIx7B00NZZSk0i_EBuCPedeaLzz5KLOmSbf6TsgQ2sJEHifselXQCWiw"/>
<div>
<p class="text-sm font-bold text-primary">Grand Azure Resort &amp; Spa</p>
<p class="text-[10px] text-outline font-medium">Partner ID: #AZ9901</p>
</div>
</div>
</td>
<td class="px-6 py-5 text-sm font-medium text-on-surface-variant">Đà Nẵng</td>
<td class="px-6 py-5">
<div class="flex text-amber-400 gap-0.5">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
</td>
<td class="px-6 py-5 text-sm font-medium text-on-surface-variant">12/10/2023</td>
<td class="px-6 py-5">
<span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-tight">Chờ duyệt</span>
</td>
<td class="px-8 py-5 text-right">
<div class="flex justify-end gap-2">
<button class="p-2 bg-surface-container text-outline hover:text-primary transition-colors rounded-lg" title="Xem hồ sơ">
<span class="material-symbols-outlined text-xl">visibility</span>
</button>
<button class="p-2 bg-emerald-100 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-colors rounded-lg" title="Phê duyệt nhanh">
  <span class="material-symbols-outlined text-xl">check</span>
</button>
<button class="p-2 bg-error/10 text-error hover:bg-error hover:text-white transition-colors rounded-lg" title="Từ chối">
<span class="material-symbols-outlined text-xl">close</span>
</button>
</div>
</td>
</tr>
<!-- Row 2 -->
<tr class="hover:bg-surface-container-high/40 transition-colors group">
<td class="px-8 py-5">
<div class="flex items-center gap-4">
<img alt="Heritage Hoi An" class="w-12 h-12 rounded-xl object-cover shadow-sm" data-alt="charming traditional boutique hotel in Hoi An with lanterns and wooden architecture" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC61HJRQBibE3yN5NLVkWlsQ7yL25fAQBIrvZJJJXoafFtfYBz_4i9FsaxAC1C0cjetGs2fEna93mX2lN1aPWtSLy2HkRupCsbkaxZqLEtkURX9Xwi61TZIyegKvlMyUUlwgZRYPWfQmP5hJ3bw5LzHtnhfhK89jONVOijtYzVV1oK092SNyJpjc2pgOSOzGttPDK-zMSGE2KJ-t9DXoghM-ydbRDPF30NOzB2tOsUYw6U09kCGjfTAYYRb9PO5lUjQC5eZj-MRgw"/>
<div>
<p class="text-sm font-bold text-primary">Heritage Hoi An Boutique</p>
<p class="text-[10px] text-outline font-medium">Partner ID: #AZ9842</p>
</div>
</div>
</td>
<td class="px-6 py-5 text-sm font-medium text-on-surface-variant">Hội An</td>
<td class="px-6 py-5">
<div class="flex text-amber-400 gap-0.5">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
</td>
<td class="px-6 py-5 text-sm font-medium text-on-surface-variant">14/10/2023</td>
<td class="px-6 py-5">
<span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-tight">Chờ duyệt</span>
</td>
<td class="px-8 py-5 text-right">
<div class="flex justify-end gap-2">
<button class="p-2 bg-surface-container text-outline hover:text-primary transition-colors rounded-lg">
<span class="material-symbols-outlined text-xl">visibility</span>
</button>
<button class="p-2 bg-emerald-100 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-colors rounded-lg" title="Phê duyệt nhanh">
  <span class="material-symbols-outlined text-xl">check</span>
</button>
<button class="p-2 bg-error/10 text-error hover:bg-error hover:text-white transition-colors rounded-lg">
<span class="material-symbols-outlined text-xl">close</span>
</button>
</div>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-surface-container-high/40 transition-colors group">
<td class="px-8 py-5">
<div class="flex items-center gap-4">
<img alt="Nha Trang Bay Suites" class="w-12 h-12 rounded-xl object-cover shadow-sm" data-alt="modern high-rise hotel tower overlooking blue ocean bay under clear sky" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDozFQOgx7Ja2OneIj4tzGpeOOCsKjxTpYz2Tf5nThYJBsUSkA4YAKpxACO-xGdGE1m-cPCcWaIHv_Cq2d4X_nEKccw2Yh2rVwrm_mPEYs4FI6N19krvdWtLaNhTjmM2zZwGD6f7erhJ6sjnWOoifDLU_Bv-DqS3_o1wzJ1Myd3pRYhfSjLOuCfjxvv1ToYwvX-IX-qRvmlklu6IZurRAbtLP3vjFHatQheWpJxxKO1HJCne-An9nivyTzoXR_WlYXm2NF5QxrIFw"/>
<div>
<p class="text-sm font-bold text-primary">Nha Trang Bay Suites</p>
<p class="text-[10px] text-outline font-medium">Partner ID: #AZ9788</p>
</div>
</div>
</td>
<td class="px-6 py-5 text-sm font-medium text-on-surface-variant">Nha Trang</td>
<td class="px-6 py-5">
<div class="flex text-amber-400 gap-0.5">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
</td>
<td class="px-6 py-5 text-sm font-medium text-on-surface-variant">15/10/2023</td>
<td class="px-6 py-5">
<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-tight">Cần bổ sung</span>
</td>
<td class="px-8 py-5 text-right">
<div class="flex justify-end gap-2">
<button class="p-2 bg-surface-container text-outline hover:text-primary transition-colors rounded-lg">
<span class="material-symbols-outlined text-xl">visibility</span>
</button>
<button class="p-2 bg-emerald-100 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-colors rounded-lg" title="Phê duyệt nhanh">
  <span class="material-symbols-outlined text-xl">check</span>
</button>
<button class="p-2 bg-error/10 text-error hover:bg-error hover:text-white transition-colors rounded-lg">
<span class="material-symbols-outlined text-xl">close</span>
</button>
</div>
</td>
</tr>
<!-- Row 4 -->
<tr class="hover:bg-surface-container-high/40 transition-colors group">
<td class="px-8 py-5">
<div class="flex items-center gap-4">
<img alt="Bana Hills Retreat" class="w-12 h-12 rounded-xl object-cover shadow-sm" data-alt="foggy mountain top castle-style hotel with lush gardens and cobblestone paths" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBa6UIBA6iqDKHaErntSiDbIz021vd2yKI_ft7-D2Yk93LdqNZu5XDrw0b4qzSZK65exrONx7MMLxRpw8L5vvGK_Qq0R1V2lCxjFV4EAN2yIgm3OSyuYn1pCBI3NkgMe0ai183_WT4H-ZRd0xeckUI9jTm_gfBwVgzhLaEabdJTYzh8mVuTMfq9L3dW3IohdEG5Rmd0R_emtglulHvPo4F8VuCtDxEI-lLh_EDBNd-oKrwGIV6dlHsEv2LwuZPS13QzUMYHhcg1FA"/>
<div>
<p class="text-sm font-bold text-primary">Bana Hills Retreat</p>
<p class="text-[10px] text-outline font-medium">Partner ID: #AZ9655</p>
</div>
</div>
</td>
<td class="px-6 py-5 text-sm font-medium text-on-surface-variant">Đà Nẵng</td>
<td class="px-6 py-5">
<div class="flex text-amber-400 gap-0.5">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
</td>
<td class="px-6 py-5 text-sm font-medium text-on-surface-variant">16/10/2023</td>
<td class="px-6 py-5">
<span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-tight">Chờ duyệt</span>
</td>
<td class="px-8 py-5 text-right">
<div class="flex justify-end gap-2">
<button class="p-2 bg-surface-container text-outline hover:text-primary transition-colors rounded-lg">
<span class="material-symbols-outlined text-xl">visibility</span>
</button>
<button class="p-2 bg-emerald-100 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-colors rounded-lg" title="Phê duyệt nhanh">
  <span class="material-symbols-outlined text-xl">check</span>
</button>
<button class="p-2 bg-error/10 text-error hover:bg-error hover:text-white transition-colors rounded-lg">
<span class="material-symbols-outlined text-xl">close</span>
</button>
</div>
</td>
</tr>
</tbody>
</table>
<!-- Pagination Shell -->
<div class="bg-surface-container-low px-8 py-4 flex justify-between items-center">
<p class="text-xs text-outline font-medium">Hiển thị 1 - 4 trong tổng số 124 hồ sơ</p>
<div class="flex gap-1">
<button class="w-8 h-8 flex items-center justify-center rounded-lg bg-white shadow-sm text-primary">
<span class="material-symbols-outlined text-sm">chevron_left</span>
</button>
<button class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary text-white shadow-sm text-xs font-bold">1</button>
<button class="w-8 h-8 flex items-center justify-center rounded-lg bg-white hover:bg-surface-container transition-colors text-primary text-xs font-bold">2</button>
<button class="w-8 h-8 flex items-center justify-center rounded-lg bg-white hover:bg-surface-container transition-colors text-primary text-xs font-bold">3</button>
<button class="w-8 h-8 flex items-center justify-center rounded-lg bg-white shadow-sm text-primary">
<span class="material-symbols-outlined text-sm">chevron_right</span>
</button>
</div>
</div>
</div>
<!-- Notification Banner/Alert Area -->
<div class="mt-8 bg-on-tertiary-container/5 border border-on-tertiary-container/10 rounded-full p-4 flex items-center gap-4">
<div class="bg-on-tertiary-container text-white p-2 rounded-lg">
<span class="material-symbols-outlined">lightbulb</span>
</div>
<div>
<p class="text-sm font-bold text-primary">Gợi ý quản trị</p>
<p class="text-xs text-on-surface-variant">Bạn có 5 hồ sơ đối tác 5 sao tại Nha Trang đang chờ duyệt gấp để kịp tiến độ mùa du lịch cuối năm.</p>
</div>
<button class="ml-auto text-xs font-extrabold  uppercase tracking-widest hover:underline">
                Xem ngay
            </button>
</div>