<div class="max-w-7xl mx-auto space-y-12">
<!-- Section 1: Quản lý hồ sơ (Bento Grid Style) -->
<section>
<div class="flex items-center justify-between mb-8">
<h3 class="text-2xl font-bold font-headline text-primary">Quản lý hồ sơ</h3>
<button class="bg-primary text-white px-6 py-2 rounded-xl text-sm font-bold shadow-lg hover:shadow-primary/20 transition-all">Lưu thay đổi</button>
</div>
<div class="grid grid-cols-12 gap-6">
<!-- Profile Card -->
<div class="col-span-12 lg:col-span-4 bg-surface-container-lowest p-8 rounded-full shadow-sm flex flex-col items-center text-center">
<div class="relative group cursor-pointer mb-6">
<img alt="Large Admin Avatar" class="w-32 h-32 rounded-full object-cover border-4 border-surface-container-low p-1 group-hover:opacity-80 transition-opacity" data-alt="detailed close-up portrait of a professional man, soft bokeh background, sharp focus on facial features, bright daytime lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCtAlj_qNA2ZIi58BU3O2Z6lsBmHiSiA-aOfP-TRFPB6Rd7Kff9LJ53mlSHe6nvliXM7GOQWoLUJmen9dAg15jZiniMQyhWhxaR5lNgim0AsRh7M_3SF3BHUVWANBIG8w77t8WH5dtmUWX4UgWzOSdDOtG6iZzZeDY5zzyTiNIaOCyhh3k_57j_QMqaMygsS8KXlLzA5D8p5q3MlvcE7-xSYospb9l6igPBZWYerYZ38DA5NTB8BOfmwDXju4ihY3ycmJGps-gj_A"/>
<div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
<span class="material-symbols-outlined text-white text-3xl">photo_camera</span>
</div>
</div>
<h4 class="text-xl font-bold text-primary font-headline">Nguyễn Văn Quản Trị</h4>
<p class="text-slate-500 text-sm mb-6">Super Admin - Hoạt động từ 2023</p>
<div class="w-full space-y-3">
<div class="flex items-center justify-between p-4 bg-surface-container-low rounded-xl">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary">verified_user</span>
<span class="text-sm font-semibold">Xác thực 2FA</span>
</div>
<label class="relative inline-flex items-center cursor-pointer">
<input checked="" class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-on-tertiary-container"></div>
</label>
</div>
</div>
</div>
<!-- Details Card -->
<div class="col-span-12 lg:col-span-8 bg-surface-container-lowest p-8 rounded-full shadow-sm grid grid-cols-2 gap-8">
<div class="space-y-2">
<label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Họ và tên</label>
<input class="w-full bg-surface-container-low border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary transition-all" type="text" value="Nguyễn Văn Quản Trị"/>
</div>
<div class="space-y-2">
<label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Email liên hệ</label>
<input class="w-full bg-surface-container-low border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary transition-all" type="email" value="admin@theconcierge.com"/>
</div>
<div class="space-y-2">
<label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Mật khẩu mới</label>
<div class="relative">
<input class="w-full bg-surface-container-low border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary transition-all" placeholder="••••••••••••" type="password"/>
<span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 cursor-pointer">visibility</span>
</div>
</div>
<div class="space-y-2">
<label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Xác nhận mật khẩu</label>
<div class="relative">
<input class="w-full bg-surface-container-low border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary transition-all" placeholder="••••••••••••" type="password"/>
<span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 cursor-pointer">visibility</span>
</div>
</div>
<div class="col-span-2 pt-4">
<div class="p-4 bg-blue-50 border border-blue-100 rounded-xl flex items-start gap-4">
<span class="material-symbols-outlined text-primary mt-0.5">info</span>
<p class="text-xs text-[#1A365D] leading-relaxed">Mật khẩu nên chứa ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt để đảm bảo an toàn hệ thống cao nhất.</p>
</div>
</div>
</div>
</div>
</section>
<!-- Section 2: Cấu hình nền tảng -->
<section>
<h3 class="text-2xl font-bold font-headline text-primary mb-8">Cấu hình nền tảng</h3>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
<div class="bg-surface-container-lowest p-6 rounded-full shadow-sm hover:translate-y-[-4px] transition-all duration-300">
<div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-primary mb-4">
<span class="material-symbols-outlined">percent</span>
</div>
<label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Tỷ lệ hoa hồng</label>
<div class="flex items-end gap-2">
<input class="w-20 bg-surface-container-low border-none rounded-xl px-3 py-2 text-lg font-bold text-primary" type="number" value="12"/>
<span class="text-lg font-bold text-slate-400 mb-2">%</span>
</div>
</div>
<div class="bg-surface-container-lowest p-6 rounded-full shadow-sm hover:translate-y-[-4px] transition-all duration-300">
<div class="w-10 h-10 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 mb-4">
<span class="material-symbols-outlined">payments</span>
</div>
<label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Phí dịch vụ</label>
<div class="flex items-center gap-2">
<input class="w-full bg-surface-container-low border-none rounded-xl px-3 py-2 text-lg font-bold text-primary" type="text" value="50.000"/>
<span class="text-sm font-bold text-slate-400">VND</span>
</div>
</div>
<div class="bg-surface-container-lowest p-6 rounded-full shadow-sm hover:translate-y-[-4px] transition-all duration-300">
<div class="w-10 h-10 bg-amber-50 rounded-full flex items-center justify-center text-amber-600 mb-4">
<span class="material-symbols-outlined">currency_exchange</span>
</div>
<label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Tiền tệ mặc định</label>
<select class="w-full bg-surface-container-low border-none rounded-xl px-3 py-2 text-sm font-semibold text-primary focus:ring-0">
<option>VND - Việt Nam Đồng</option>
<option>USD - US Dollar</option>
<option>EUR - Euro</option>
</select>
</div>
<div class="bg-surface-container-lowest p-6 rounded-full shadow-sm hover:translate-y-[-4px] transition-all duration-300">
<div class="w-10 h-10 bg-purple-50 rounded-full flex items-center justify-center text-purple-600 mb-4">
<span class="material-symbols-outlined">language</span>
</div>
<label class="text-xs font-bold text-slate-500 uppercase mb-2 block">Ngôn ngữ hệ thống</label>
<select class="w-full bg-surface-container-low border-none rounded-xl px-3 py-2 text-sm font-semibold text-primary focus:ring-0">
<option>Tiếng Việt</option>
<option>English</option>
<option>日本語</option>
</select>
</div>
</div>
</section>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
<!-- Section 3: Vai trò người dùng -->
<section>
<div class="flex items-center justify-between mb-6">
<h3 class="text-2xl font-bold font-headline text-primary">Vai trò người dùng</h3>
<button class="text-primary text-sm font-bold flex items-center gap-1 hover:underline">
<span class="material-symbols-outlined text-sm">add_circle</span> Thêm thành viên
                        </button>
</div>
<div class="bg-surface-container-lowest rounded-full shadow-sm overflow-hidden">
<div class="p-6 space-y-4">
<div class="flex items-center justify-between p-4 bg-surface-container-low rounded-xl group hover:bg-surface-container-high transition-colors">
<div class="flex items-center gap-4">
<div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold">AN</div>
<div>
<p class="text-sm font-bold text-primary">An Nguyễn</p>
<p class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">an.nguyen@concierge.vn</p>
</div>
</div>
<span class="px-3 py-1 bg-primary text-white text-[10px] font-black rounded-full uppercase">Super Admin</span>
</div>
<div class="flex items-center justify-between p-4 bg-surface-container-low rounded-xl group hover:bg-surface-container-high transition-colors">
<div class="flex items-center gap-4">
<div class="w-10 h-10 bg-on-tertiary-container/10 rounded-full flex items-center justify-center text-on-tertiary-container font-bold">LH</div>
<div>
<p class="text-sm font-bold text-primary">Lê Huy</p>
<p class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">huy.le@concierge.vn</p>
</div>
</div>
<span class="px-3 py-1 bg-secondary-container text-on-secondary-container text-[10px] font-black rounded-full uppercase">Editor</span>
</div>
<div class="flex items-center justify-between p-4 bg-surface-container-low rounded-xl group hover:bg-surface-container-high transition-colors">
<div class="flex items-center gap-4">
<div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-700 font-bold">MT</div>
<div>
<p class="text-sm font-bold text-primary">Minh Tú</p>
<p class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">tu.minh@concierge.vn</p>
</div>
</div>
<span class="px-3 py-1 bg-surface-container-highest text-on-surface-variant text-[10px] font-black rounded-full uppercase">Viewer</span>
</div>
</div>
<div class="bg-slate-50 p-4 text-center">
<button class="text-xs font-bold text-slate-400 hover:text-primary transition-colors">Xem tất cả 12 nhân sự</button>
</div>
</div>
</section>
<!-- Section 4: Cài đặt thông báo -->
<section>
<h3 class="text-2xl font-bold font-headline text-primary mb-6">Cài đặt thông báo</h3>
<div class="bg-surface-container-lowest rounded-full shadow-sm p-6 overflow-x-auto">
<table class="w-full">
<thead>
<tr class="text-left">
<th class="pb-6 text-xs font-black text-slate-400 uppercase tracking-widest">Sự kiện</th>
<th class="pb-6 text-center text-xs font-black text-slate-400 uppercase tracking-widest">Email</th>
<th class="pb-6 text-center text-xs font-black text-slate-400 uppercase tracking-widest">SMS</th>
<th class="pb-6 text-center text-xs font-black text-slate-400 uppercase tracking-widest">Push</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-50">
<tr class="hover:bg-slate-50 transition-colors">
<td class="py-4 text-sm font-semibold text-primary">Đặt phòng mới</td>
<td class="py-4 text-center"><input checked="" class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4" type="checkbox"/></td>
<td class="py-4 text-center"><input class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4" type="checkbox"/></td>
<td class="py-4 text-center"><input checked="" class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4" type="checkbox"/></td>
</tr>
<tr class="hover:bg-slate-50 transition-colors">
<td class="py-4 text-sm font-semibold text-primary">Đăng ký đối tác</td>
<td class="py-4 text-center"><input checked="" class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4" type="checkbox"/></td>
<td class="py-4 text-center"><input checked="" class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4" type="checkbox"/></td>
<td class="py-4 text-center"><input class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4" type="checkbox"/></td>
</tr>
<tr class="hover:bg-slate-50 transition-colors">
<td class="py-4 text-sm font-semibold text-primary">Cảnh báo hệ thống</td>
<td class="py-4 text-center"><input checked="" class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4" type="checkbox"/></td>
<td class="py-4 text-center"><input checked="" class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4" type="checkbox"/></td>
<td class="py-4 text-center"><input checked="" class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4" type="checkbox"/></td>
</tr>
<tr class="hover:bg-slate-50 transition-colors">
<td class="py-4 text-sm font-semibold text-primary">Báo cáo doanh thu</td>
<td class="py-4 text-center"><input checked="" class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4" type="checkbox"/></td>
<td class="py-4 text-center"><input class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4" type="checkbox"/></td>
<td class="py-4 text-center"><input class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4" type="checkbox"/></td>
</tr>
</tbody>
</table>
<div class="mt-8 p-4 bg-tertiary-container/5 rounded-xl flex items-center gap-3">
<span class="material-symbols-outlined text-emerald-600">history_toggle_off</span>
<p class="text-xs text-emerald-600 font-medium">Lịch sử cấu hình được lưu tự động mỗi 30 ngày để quản trị viên có thể hoàn tác.</p>
</div>
</div>
</section>
</div>
</div>