<div class="p-10 space-y-12 max-w-7xl mx-auto">
<!-- Header Section -->
<div class="space-y-2">
<h2 class="text-4xl font-extrabold text-primary font-headline tracking-tight">Quản lý phòng</h2>
<p class="text-secondary text-lg max-w-2xl">Theo dõi và cập nhật trạng thái phòng của các đối tác khách sạn trên toàn hệ thống Azure Concierge.</p>
</div>
<!-- KPI Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
<!-- KPI Card 1 -->
<div class="bg-surface-container-lowest p-6 rounded-full shadow-sm flex items-center justify-between">
<div>
<p class="text-secondary text-xs font-medium uppercase tracking-wider mb-1">Tổng số phòng</p>
<h3 class="text-3xl font-extrabold text-primary font-headline">1,284</h3>
</div>
<div class="w-12 h-12 rounded-xl bg-primary-fixed flex items-center justify-center text-primary">
<span class="material-symbols-outlined">apartment</span>
</div>
</div>
<!-- KPI Card 2 -->
<div class="bg-white p-6 rounded-2xl shadow-sm flex items-center justify-between border border-slate-100 hover:shadow-md transition-shadow">
  <div>
    <p class="text-indigo-900 text-xs font-bold uppercase tracking-widest mb-1.5 opacity-90">Phòng trống</p>
    <div class="flex items-baseline gap-2">
      <h3 class="text-4xl font-extrabold text-slate-950 font-headline tracking-tight">432</h3>
      
      <span class="text-[10px] font-bold text-emerald-950 bg-emerald-300/30 px-2.5 py-0.5 rounded-full ring-1 ring-emerald-400/30 shadow-inner">
        34%
      </span>
    </div>
  </div>

  <div class="w-14 h-14 rounded-xl bg-indigo-100/50 flex items-center justify-center text-indigo-900 border border-indigo-200/50 shadow-sm">
    <span class="material-symbols-outlined text-3xl">meeting_room</span>
  </div>
</div>
<!-- KPI Card 3 -->
<div class="bg-surface-container-lowest p-6 rounded-full shadow-sm flex items-center justify-between">
<div>
<p class="text-secondary text-xs font-medium uppercase tracking-wider mb-1">Đang có khách</p>
<h3 class="text-3xl font-extrabold text-primary font-headline">782</h3>
</div>
<div class="w-12 h-12 rounded-xl bg-secondary-container flex items-center justify-center text-secondary">
<span class="material-symbols-outlined">person</span>
</div>
</div>
<!-- KPI Card 4 -->
<div class="bg-surface-container-lowest p-6 rounded-full shadow-sm flex items-center justify-between">
<div>
<p class="text-secondary text-xs font-medium uppercase tracking-wider mb-1">Bảo trì</p>
<h3 class="text-3xl font-extrabold text-error font-headline">70</h3>
</div>
<div class="w-12 h-12 rounded-xl bg-error-container flex items-center justify-center text-error">
<span class="material-symbols-outlined">build</span>
</div>
</div>
</div>
<!-- Filters & Actions -->
<div class="flex flex-col md:flex-row gap-4 items-end justify-between bg-surface-container-low p-6 rounded-xl">
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 flex-1 w-full">
<div class="space-y-2">
<label class="text-xs font-bold text-primary uppercase">Tìm kiếm đối tác</label>
<input class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-primary/20" placeholder="Tên khách sạn..." type="text"/>
</div>
<div class="space-y-2">
<label class="text-xs font-bold text-primary uppercase">Hạng phòng</label>
<select class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-primary/20">
<option>Tất cả hạng phòng</option>
<option>Standard</option>
<option>Deluxe</option>
<option>Suite</option>
<option>Executive</option>
</select>
</div>
<div class="space-y-2">
<label class="text-xs font-bold text-primary uppercase">Trạng thái</label>
<select class="w-full bg-surface-container-lowest border-none rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-primary/20">
<option>Tất cả trạng thái</option>
<option>Sẵn sàng</option>
<option>Đang dọn dẹp</option>
<option>Đã đặt</option>
<option>Bảo trì</option>
</select>
</div>
</div>
<button class="bg-primary text-on-primary px-6 py-3 rounded-lg font-bold text-xs uppercase tracking-widest hover:bg-primary-container transition-colors shadow-lg shadow-primary/20 flex items-center gap-2">
<span class="material-symbols-outlined text-sm">filter_alt</span>
                    Lọc dữ liệu
                </button>
</div>
<!-- Room Bento Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
<!-- Room Card 1 -->
<div class="bg-surface-container-lowest rounded-full overflow-hidden flex flex-col group hover:translate-y-[-4px] transition-all duration-300">
<div class="relative h-64 overflow-hidden">
<img alt="Phòng Deluxe Suite" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" data-alt="luxury hotel suite with ocean view through large windows modern minimalist decor and warm ambient lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDWaA6ZEGWLEKUA4fPxZv4GEOmuFHIRRPR4n2eXYvTf5vl1bqz8Ieu5vtAeWgAdU05wH2hQuQFOGICcT8aXCRTb0Qi5qI3MCE5v1QLeJspVKE0tFlEcPHeULmv1Xox_oJ211_ybSOVqbzB4CAKx6nxr83NOVvqRLhjRs3VVPlQyby2JLooaKlSmEv14kjM69cJtFlZ4uzNvMm6fYx_XDGuPy9432DEUw5aIOShl83V8Fgg8DzuQM_-E5W9tbxiq_HLvDmDap6Tz-A"/>
<div class="absolute top-4 right-4 bg-on-tertiary-container text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Sẵn sàng</div>
</div>
<div class="p-8 space-y-4">
<div class="flex justify-between items-start">
<div>
<p class="text-[10px] font-bold text-on-secondary-container uppercase tracking-widest">Azure Bay Resort</p>
<h4 class="text-xl font-bold text-primary font-headline mt-1">Deluxe Suite 402</h4>
</div>
<div class="text-right">
<p class="text-lg font-extrabold text-primary font-headline">4.500.000₫</p>
<p class="text-[10px] text-slate-400">/đêm</p>
</div>
</div>
<div class="flex items-center gap-4 text-slate-500">
<div class="flex items-center gap-1.5 bg-surface-container py-1.5 px-3 rounded-full">
<span class="material-symbols-outlined text-lg">wifi</span>
<span class="text-[10px] font-medium">Free Wifi</span>
</div>
<div class="flex items-center gap-1.5 bg-surface-container py-1.5 px-3 rounded-full">
<span class="material-symbols-outlined text-lg">pool</span>
<span class="text-[10px] font-medium">Hồ bơi</span>
</div>
<div class="flex items-center gap-1.5 bg-surface-container py-1.5 px-3 rounded-full">
<span class="material-symbols-outlined text-lg">restaurant</span>
<span class="text-[10px] font-medium">Ăn sáng</span>
</div>
</div>
</div>
</div>
<!-- Room Card 2 -->
<div class="bg-surface-container-lowest rounded-full overflow-hidden flex flex-col group hover:translate-y-[-4px] transition-all duration-300">
<div class="relative h-64 overflow-hidden">
<img alt="Phòng Executive" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" data-alt="elegant executive hotel room with king size bed premium bedding and city skyline view through floor to ceiling windows" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAJMgJ1JhyujPlZFywB-Ko4Yhcd1WlgzQZYx6PA-qW-ZH-7U5M58whvUikCwXq4KOzGlrkT9O2tSYRsUJO-KS6gLayJbtWzq7qPT40Bhm4ATVblEX1UF1neFyhXs_rr8C1ZgJNPVioGzDI7BJJSj1NLGdabG_45uvR_LO_rNJUtty3n-dE4oE1ff12DZ0TPJfnEJFpEfT1-LmjGbaHs9zEnkzBLotnlwADwHMreY2aBvjboCeRa3our7s7JupRRaczJ9r40YI1_SQ"/>
<div class="absolute top-4 right-4 bg-[#facc15] text-on-surface px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Đang dọn dẹp</div>
</div>
<div class="p-8 space-y-4">
<div class="flex justify-between items-start">
<div>
<p class="text-[10px] font-bold text-on-secondary-container uppercase tracking-widest">Skyline Boutique Hotel</p>
<h4 class="text-xl font-bold text-primary font-headline mt-1">Executive King 1205</h4>
</div>
<div class="text-right">
<p class="text-lg font-extrabold text-primary font-headline">3.200.000₫</p>
<p class="text-[10px] text-slate-400">/đêm</p>
</div>
</div>
<div class="flex items-center gap-4 text-slate-500">
<div class="flex items-center gap-1.5 bg-surface-container py-1.5 px-3 rounded-full">
<span class="material-symbols-outlined text-lg">wifi</span>
<span class="text-[10px] font-medium">Free Wifi</span>
</div>
<div class="flex items-center gap-1.5 bg-surface-container py-1.5 px-3 rounded-full">
<span class="material-symbols-outlined text-lg">tv</span>
<span class="text-[10px] font-medium">Smart TV</span>
</div>
</div>
</div>
</div>
<!-- Room Card 3 -->
<div class="bg-surface-container-lowest rounded-full overflow-hidden flex flex-col group hover:translate-y-[-4px] transition-all duration-300">
<div class="relative h-64 overflow-hidden">
<img alt="Phòng Suite Luxury" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" data-alt="luxurious master bedroom in a penthouse suite with dark wood accents plush carpets and soft moody lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA7CElRUXvFwF5nCYWN6KuU7r0YAmiX2jJEBwvf9keQMuQ21_rsL94IZ4Rf3HQ4jo15kGsMsq2uCiiJizHInoZNMR6zY-zl-37HS5E2JVKzvukCBhtMZ3h5bCBNBc-Q_VDcw0Xfvo-28CA7gjv8NRIRqs4pVAMk1Ilsop6HL9Gu9AzD-N4Qj9A2ZFKnc2pDKN_gjiL9y_P_7KAFVeyX6H1tbYVe8LlnXfpgUtt9xdD_VB3DbKkAGpvZyv21IuPLu-3FyEqDeIXkvQ"/>
<div class="absolute top-4 right-4 bg-error text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Đã đặt</div>
</div>
<div class="p-8 space-y-4">
<div class="flex justify-between items-start">
<div>
<p class="text-[10px] font-bold text-on-secondary-container uppercase tracking-widest">Grand Emerald Palace</p>
<h4 class="text-xl font-bold text-primary font-headline mt-1">Presidential Suite</h4>
</div>
<div class="text-right">
<p class="text-lg font-extrabold text-primary font-headline">12.000.000₫</p>
<p class="text-[10px] text-slate-400">/đêm</p>
</div>
</div>
<div class="flex items-center gap-4 text-slate-500">
<div class="flex items-center gap-1.5 bg-surface-container py-1.5 px-3 rounded-full">
<span class="material-symbols-outlined text-lg">spa</span>
<span class="text-[10px] font-medium">Dịch vụ Spa</span>
</div>
<div class="flex items-center gap-1.5 bg-surface-container py-1.5 px-3 rounded-full">
<span class="material-symbols-outlined text-lg">local_bar</span>
<span class="text-[10px] font-medium">Mini Bar</span>
</div>
</div>
</div>
</div>
<!-- Room Card 4 -->
<div class="bg-surface-container-lowest rounded-full overflow-hidden flex flex-col group hover:translate-y-[-4px] transition-all duration-300">
<div class="relative h-64 overflow-hidden">
<img alt="Phòng Standard" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 grayscale opacity-80" data-alt="clean and functional standard hotel room with twin beds white linens and minimalist desk area" src="https://lh3.googleusercontent.com/aida-public/AB6AXuALBpuIEd2Ue_7QkeNDEpEMk_uYYIbp16zKe607nJfhrsgoYcXFNkDzZhZqMT3ckemsR_QgamOZpqPw5opj8Z_WpR7r6SyVA2TqxuCHlrAEIG38o3xfyn-xuyQrdsyzXmI0r5uDX6QnTCqsQrD8R5i1rjgulUkPL0y1UoBVtTI5gxWLPRV4bni3NFqRdEErwRH01vLhDqr_bMEXmDVnzFljkt2oYJ5AatTLP2u54yKAY4i3NNsNAyxVf4qEnAwXVS9cAgSuqUnEvA"/>
<div class="absolute top-4 right-4 bg-secondary text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Bảo trì</div>
</div>
<div class="p-8 space-y-4">
<div class="flex justify-between items-start">
<div>
<p class="text-[10px] font-bold text-on-secondary-container uppercase tracking-widest">City Central Inn</p>
<h4 class="text-xl font-bold text-primary font-headline mt-1">Standard Twin 301</h4>
</div>
<div class="text-right">
<p class="text-lg font-extrabold text-primary font-headline">1.200.000₫</p>
<p class="text-[10px] text-slate-400">/đêm</p>
</div>
</div>
<div class="flex items-center gap-4 text-slate-500">
<div class="flex items-center gap-1.5 bg-surface-container py-1.5 px-3 rounded-full">
<span class="material-symbols-outlined text-lg">wifi</span>
<span class="text-[10px] font-medium">Free Wifi</span>
</div>
</div>
</div>
</div>
<!-- Add more cards as needed -->
</div>
<!-- Pagination -->
<div class="flex items-center justify-between border-t border-outline-variant/20 pt-8 pb-12">
<p class="text-xs text-secondary">Hiển thị <span class="font-bold text-primary">1 - 4</span> của <span class="font-bold text-primary">1284</span> phòng</p>
<div class="flex items-center gap-2">
<button class="p-2 rounded-lg hover:bg-surface-container-high text-slate-400 transition-colors">
<span class="material-symbols-outlined">chevron_left</span>
</button>
<button class="w-10 h-10 rounded-lg bg-primary text-white font-bold text-xs shadow-md shadow-primary/20">1</button>
<button class="w-10 h-10 rounded-lg hover:bg-surface-container-high text-primary font-bold text-xs transition-colors">2</button>
<button class="w-10 h-10 rounded-lg hover:bg-surface-container-high text-primary font-bold text-xs transition-colors">3</button>
<span class="text-slate-400 px-1">...</span>
<button class="w-10 h-10 rounded-lg hover:bg-surface-container-high text-primary font-bold text-xs transition-colors">120</button>
<button class="p-2 rounded-lg hover:bg-surface-container-high text-slate-400 transition-colors">
<span class="material-symbols-outlined">chevron_right</span>
</button>
</div>
</div>
</div>