<?php
// echo '<pre>';
// print_r($data);
// print_r($_SESSION['booking']);
// echo '</pre>';
?>

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Xác nhận đặt phòng | Lumière Stays</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@300;400;500;600&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .editorial-shadow {
            box-shadow: 0 10px 40px -10px rgba(3, 22, 50, 0.04);
        }
    </style>
</head>

<body class="bg-surface-container-low font-body text-on-surface antialiased">
    <main class="pt-12 pb-20 px-6 md:px-12 max-w-7xl mx-auto">
        <!-- Progress Stepper -->
        <nav class="mb-16">
            <div class="flex items-center justify-between max-w-3xl mx-auto relative">
                <div class="absolute top-1/2 left-0 w-full h-0.5 bg-outline-variant/30 -translate-y-1/2 z-0"></div>
                <div class="relative z-10 flex flex-col items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center border-4 border-surface-container-low">
                        <span class="material-symbols-outlined text-xl">check</span>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-primary">Chọn phòng</span>
                </div>
                <div class="relative z-10 flex flex-col items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center border-4 border-surface-container-low ring-2 ring-primary">
                        <span class="font-bold text-sm">2</span>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-primary">Kiểm tra thông tin</span>
                </div>
                <div class="relative z-10 flex flex-col items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-outline-variant text-white flex items-center justify-center border-4 border-surface-container-low">
                        <span class="font-bold text-sm">3</span>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-outline">Thanh toán</span>
                </div>
                <div class="relative z-10 flex flex-col items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-outline-variant text-white flex items-center justify-center border-4 border-surface-container-low">
                        <span class="font-bold text-sm">4</span>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-outline">Xuất biên lai</span>
                </div>
            </div>
        </nav>
        <div class="mb-10 text-center md:text-left">
            <h1 class="text-4xl md:text-5xl font-headline font-extrabold tracking-tight text-primary mb-2">Xác nhận đặt phòng</h1>
            <p class="text-on-surface-variant">Vui lòng kiểm tra lại thông tin chi tiết trước khi hoàn tất thanh toán tiền cọc.</p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
            <!-- Main Content Area (Left 2/3) -->
            <div class="lg:col-span-2 space-y-10">
                <!-- Section 1: Customer Info -->
                <section class="bg-surface-container-low/50 p-6 md:p-8 rounded-2xl border border-outline-variant/20">
                    <h2 class="text-2xl font-headline font-bold text-primary flex items-center gap-3 mb-8">
                        <span class="material-symbols-outlined">person</span>
                        Thông tin khách hàng
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- NAME -->
                        <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/20 editorial-shadow group">
                            <div class="flex justify-between items-start mb-2">
                                <label class="text-xs font-semibold uppercase text-on-surface-variant">Họ và tên</label>
                                <button onclick="enableEdit('name')" class="text-secondary text-sm hover:underline">
                                    Thay đổi
                                </button>
                            </div>

                            <p id="view-name" class="text-lg font-semibold text-primary customerName">
                                Nguyễn Văn A
                            </p>

                            <input id="input-name" type="text"
                                class="hidden w-full mt-2 p-2 rounded-lg border border-outline-variant/30 focus:ring-2 focus:ring-primary outline-none"
                                placeholder="Nhập họ tên">
                        </div>


                        <!-- EMAIL -->
                        <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/20 editorial-shadow group">
                            <div class="flex justify-between items-start mb-2">
                                <label class="text-xs font-semibold uppercase text-on-surface-variant">Email</label>
                                <button onclick="enableEdit('email')" class="text-secondary text-sm hover:underline">
                                    Thay đổi
                                </button>
                            </div>

                            <p id="view-email" class="text-lg font-semibold text-primary customerEmail">
                                vana.nguyen@example.com
                            </p>

                            <input id="input-email" type="email"
                                class="hidden w-full mt-2 p-2 rounded-lg border border-outline-variant/30 focus:ring-2 focus:ring-primary outline-none"
                                placeholder="Nhập email">
                        </div>


                        <!-- PHONE -->
                        <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/20 editorial-shadow group">
                            <div class="flex justify-between items-start mb-2">
                                <label class="text-xs font-semibold uppercase text-on-surface-variant">Số điện thoại</label>
                                <button onclick="enableEdit('phone')" class="text-secondary text-sm hover:underline">
                                    Thay đổi
                                </button>
                            </div>

                            <p id="view-phone" class="text-lg font-semibold text-primary customerPhone">
                                +84 901 234 567
                            </p>

                            <input id="input-phone" type="text"
                                class="hidden w-full mt-2 p-2 rounded-lg border border-outline-variant/30 focus:ring-2 focus:ring-primary outline-none"
                                placeholder="Nhập số điện thoại">
                        </div>


                        <!-- NOTE -->
                        <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/20 editorial-shadow md:col-span-2">
                            <label class="text-xs font-semibold uppercase text-on-surface-variant block mb-3">
                                Yêu cầu đặc biệt
                            </label>

                            <textarea id="special-note"
                                class="w-full bg-surface-container-high border-none rounded-xl p-4 
            focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest 
            transition-all min-h-[100px] text-on-surface"
                                placeholder="Ghi chú thêm về yêu cầu của bạn (ví dụ: phòng không hút thuốc, tầng cao...)">
        </textarea>
                        </div>

                    </div>
                </section>
                <!-- Section 2: Selected Rooms -->
                <section class="space-y-6">
                    <h2 class="text-2xl font-headline font-bold text-primary flex items-center gap-3 px-2">
                        <span class="material-symbols-outlined">bed</span>
                        Chi tiết phòng đã chọn
                    </h2>
                    <div class="space-y-8">
                        <?php if (!empty($bookingData)): ?>
                            <?php foreach ($bookingData as $item): ?>
                                <div class="bg-surface-container-lowest rounded-2xl overflow-hidden editorial-shadow border border-outline-variant/10 mb-6 relative">

                                    <!-- ✅ Badge số lượng phòng -->
                                    <div class="absolute bottom-4 right-4 flex items-center bg-white shadow-lg rounded-full px-2 py-1 space-x-2">
                                        <!-- Nút giảm --><?php $uniqueId = md5($item['checkIn'] . $item['checkOut']); ?>
                                        <button onclick="changeQty(<?= $item['roomConfigId'] ?>, -1, <?= $item['availableRooms'] ?>,'<?= $item['checkIn'] ?>', '<?= $item['checkOut'] ?>', '<?= $uniqueId ?>')"
                                            class="bg-red-500 text-white w-6 h-6 flex items-center justify-center rounded-full">-</button>

                                        <!-- Hiển thị số lượng -->


                                        <span id="qty-<?= $item['roomConfigId'] ?>-<?= $uniqueId ?>">
                                            <?= $item['quantity'] ?>
                                        </span>

                                        <!-- Nút tăng -->
                                        <button onclick="changeQty(<?= $item['roomConfigId'] ?>, 1, <?= $item['availableRooms'] ?>,'<?= $item['checkIn'] ?>', '<?= $item['checkOut'] ?>', '<?= $uniqueId ?>')"
                                            class="bg-green-500 text-white w-6 h-6 flex items-center justify-center rounded-full">+</button>
                                    </div>

                                    <div class="flex flex-col md:flex-row">

                                        <!-- IMAGE -->
                                        <div class="md:w-2/5 relative">
                                            <img
                                                class="h-full w-full object-cover min-h-[220px]"
                                                src="<?= $item['images'][0]['imageUrl'] ?? 'default.jpg' ?>"
                                                alt="Room Image" />
                                            <div class="absolute top-4 left-4 bg-primary/80 backdrop-blur text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full">
                                                <?= $item['roomConfig']['roomTypeName'] ?? 'Phòng' ?>
                                            </div>
                                        </div>

                                        <!-- INFO -->
                                        <div class="p-8 md:w-3/5 flex flex-col justify-between">
                                            <div>

                                                <!-- Tên khách sạn -->
                                                <h3 class="text-2xl font-headline font-extrabold text-primary mb-2">
                                                    <?= $item['roomConfig']['hotelName'] ?? 'Room Name' ?>
                                                </h3>
                                                <!-- Địa chỉ -->
                                                <p class="text-sm text-on-surface-variant mb-4">
                                                    <?= $item['roomConfig']['address'] ?? 'Địa chỉ chưa cập nhật' ?>, <?= $item['roomConfig']['wardName'] ?? '' ?>, <?= $item['roomConfig']['cityName'] ?? '' ?>
                                                </p>

                                                <!-- Thông tin -->
                                                <div class="flex flex-wrap gap-4 text-sm text-on-surface-variant mb-6">

                                                    <!-- Số người -->
                                                    <span class="flex items-center gap-1.5">
                                                        <span class="material-symbols-outlined text-lg">group</span>
                                                        <?php
                                                        if (!empty($item['bed'])) {
                                                            echo array_sum(array_column($item['bed'], 'maxPeople'));
                                                        } else {
                                                            echo 'Chưa cập nhật';
                                                        }
                                                        ?> người
                                                    </span>

                                                    <!-- Ngày -->
                                                    <span class="flex items-center gap-1.5">
                                                        <span class="material-symbols-outlined text-lg">calendar_today</span>
                                                        <?= $item['checkIn'] ?> - <?= $item['checkOut'] ?>
                                                    </span>

                                                    <!-- Số đêm -->
                                                    <span class="flex items-center gap-1.5">
                                                        <span class="material-symbols-outlined text-lg">nightlight</span>
                                                        <?= $item['nights'] ?> Đêm
                                                    </span>

                                                    <!-- Kiểu giường -->
                                                    <span class="flex items-center gap-1.5">
                                                        <span class="material-symbols-outlined text-lg">bed</span>
                                                        <?php
                                                        if (!empty($item['bed'])) {
                                                            foreach ($item['bed'] as $b) {
                                                                echo $b['name'] . " x" . $b['quantity'] . " ";
                                                            }
                                                        } else {
                                                            echo 'Giường đôi';
                                                        }
                                                        ?>
                                                    </span>

                                                </div>

                                                <!-- Giá -->
                                                <div class="text-primary font-headline font-bold text-xl">
                                                    <?= number_format($item['roomConfig']['basePrice'], 0, ',', '.') ?> ₫
                                                    <span class="text-sm font-normal text-on-surface-variant">/ đêm</span>
                                                </div>

                                                <!-- Tổng -->
                                                <div class="text-lg font-bold text-secondary mt-2">
                                                    Tổng: <?= number_format($item['total'], 0, ',', '.') ?> ₫
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Hiển thị khi không có dữ liệu -->
                            <div class="bg-surface-container-lowest border border-outline-variant/20 rounded-2xl p-10 text-center editorial-shadow">
                                <span class="material-symbols-outlined text-5xl text-outline mb-4">inventory_2</span>
                                <h3 class="text-xl font-bold text-primary mb-2">Chưa có dữ liệu</h3>
                                <p class="text-sm text-on-surface-variant mb-6">
                                    Bạn chưa chọn phòng nào. Hãy quay lại để chọn phòng phù hợp.
                                </p>

                            </div>
                        <?php endif; ?>
                        <a href="<?= BASE_URL ?>"
                            class="inline-block bg-primary text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition">
                            Đặt thêm phòng
                        </a>

                        <!-- Section 3: Policy & Tips (Integrated before final action) -->
                        <section class="space-y-6 pt-4">
                            <div class="bg-blue-50/50 border border-blue-100 p-8 rounded-2xl editorial-shadow">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="bg-blue-600/10 p-2 rounded-lg">
                                        <span class="material-symbols-outlined text-blue-600">lightbulb</span>
                                    </div>
                                    <h3 class="text-xl font-headline font-bold text-primary">Mách nhỏ từ Lumière</h3>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                                    <div class="flex gap-3">
                                        <span class="material-symbols-outlined text-blue-600 text-xl shrink-0">schedule</span>
                                        <div>
                                            <p class="font-bold text-sm text-primary mb-0.5">Giờ nhận/trả phòng</p>
                                            <p class="text-xs text-on-surface-variant">Check-in: 14:00 | Check-out: 12:00.</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-3">
                                        <span class="material-symbols-outlined text-blue-600 text-xl shrink-0">payments</span>
                                        <div>
                                            <p class="font-bold text-sm text-primary mb-0.5">Thanh toán linh hoạt</p>
                                            <p class="text-xs text-on-surface-variant">Chỉ cần đặt cọc 30%. 70% còn lại thanh toán tại quầy lễ tân.</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-3">
                                        <span class="material-symbols-outlined text-blue-600 text-xl shrink-0">account_balance_wallet</span>
                                        <div>
                                            <p class="font-bold text-sm text-primary mb-0.5">Đa dạng hình thức</p>
                                            <p class="text-xs text-on-surface-variant">Chấp nhận chuyển khoản, ví điện tử hoặc thẻ nội địa.</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-3">
                                        <span class="material-symbols-outlined text-blue-600 text-xl shrink-0">event_busy</span>
                                        <div>
                                            <p class="font-bold text-sm text-primary mb-0.5">Hủy phòng linh hoạt</p>
                                            <p class="text-xs text-on-surface-variant">Hoàn 100% tiền cọc nếu hủy trước 48h khi nhận phòng.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-surface-container-low border border-outline-variant/20 p-6 rounded-2xl flex gap-4">
                                <span class="material-symbols-outlined text-primary shrink-0">info</span>
                                <div class="text-sm text-on-surface-variant leading-relaxed">
                                    <p class="font-bold text-primary mb-1">Chính sách đặt cọc &amp; Thanh toán</p>
                                    Lumière Stays chỉ thực hiện thu trước <strong>30% tổng giá trị đặt phòng</strong> để đảm bảo giữ chỗ. Khoản tiền còn lại bạn sẽ thanh toán trực tiếp tại khách sạn khi làm thủ tục nhận phòng.
                                </div>
                            </div>
                        </section>
                    </div>
            </div>
            <!-- Sidebar Summary (Right 1/3) -->
            <aside class="space-y-6 lg:sticky lg:top-8 order-last lg:order-none">
                <!-- Booking Summary Card -->
                <div class="bg-primary text-white rounded-2xl editorial-shadow overflow-hidden">
                    <div class="p-8 border-b border-white/10 relative">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                        <h2 class="text-xl font-headline font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined">receipt_long</span>
                            Tóm tắt đặt phòng
                        </h2>
                    </div>
                    <div class="p-8 space-y-4">
                        <div class="space-y-3">
                            <?php foreach ($bookingData as $room): ?>
                                <div class="flex justify-between text-sm text-white/70">
                                    <span><?php echo htmlspecialchars($room['roomConfig']['hotelName'] . ' - ' .   $room['roomConfig']['roomTypeName']); ?> (<?php echo $room['nights']; ?> đêm)</span>
                                    <span><?php echo number_format($room['total'], 0, ',', '.'); ?> VND</span>
                                </div>
                            <?php endforeach; ?>
                            <div class="flex justify-between text-sm text-white/70">
                                <span>Thuế VAT (1%)</span>
                                <span><?= number_format($data['totalAll'] * 0.01, 0, ',', '.') ?> VND</span>
                            </div>
                            <div onclick="openVoucherModal()" class="flex justify-between items-center p-3 bg-white/10 rounded-xl cursor-pointer hover:bg-white/20 transition-all border border-dashed border-white/30 mt-2">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-tertiary-fixed-dim">confirmation_number</span>
                                    <span class="text-sm font-medium">Chọn hoặc nhập mã</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span id="selected-voucher-name" class="text-white/50">Ưu đãi</span>
                                    <span class="material-symbols-outlined text-sm" onclick="event.stopPropagation(); removeVoucher()" style="cursor: pointer;">close</span>
                                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-white/10 flex justify-between items-center">
                            <span class="text-sm font-medium text-white/80">Tổng cộng</span>
                            <span class="text-lg font-bold"><?= number_format($data['totalAll'] + $data['totalAll'] * 0.01, 0, ',', '.') ?> VND</span>
                        </div>
                        <!-- Highlighted Deposit Section -->
                        <div class="mt-8 p-6 bg-white/10 rounded-xl border border-white/10 text-center">
                            <p class="text-xs uppercase tracking-widest text-white/70 mb-2 font-bold">Tiền đặt cọc cần trả (30%)</p>
                            <div class="text-3xl font-headline font-extrabold text-tertiary-fixed-dim"><?= number_format(($data['totalAll'] + $data['totalAll'] * 0.01) * 0.3, 0, ',', '.') ?> VND</div>
                            <p class="text-[11px] text-white/50 mt-2">Còn lại <?= number_format(($data['totalAll'] + $data['totalAll'] * 0.01) * 0.7, 0, ',', '.') ?> VND trả tại khách sạn</p>
                        </div>
                        <div class="bg-white/5 p-8 border-t border-white/10 space-y-6">

                            <h3 class="text-sm font-bold text-white uppercase tracking-wider">
                                Chọn phương thức thanh toán
                            </h3>

                            <!-- MOMO -->
                            <label class="flex items-center gap-4 p-4 rounded-xl cursor-pointer border border-white/10 hover:bg-white/10 transition peer-checked:border-tertiary-fixed-dim">
                                <input type="radio" name="paymentMethod" value="momo" class="hidden peer" checked>

                                <div class="bg-pink-500/20 text-pink-400 p-2 rounded-lg">
                                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">account_balance_wallet</span>
                                </div>

                                <div class="flex-1">
                                    <h4 class="font-bold text-sm text-white">Ví MoMo</h4>
                                    <p class="text-[11px] text-white/50">Thanh toán nhanh qua ví điện tử MoMo</p>
                                </div>

                                <!-- CHECK -->
                                <div class="w-5 h-5 rounded-full border-2 border-white/30 flex items-center justify-center peer-checked:border-tertiary-fixed-dim">
                                    <div class="w-2.5 h-2.5 bg-tertiary-fixed-dim rounded-full hidden peer-checked:block"></div>
                                </div>
                            </label>

                            <!-- VNPAY -->
                            <label class="flex items-center gap-4 p-4 rounded-xl cursor-pointer border border-white/10 hover:bg-white/10 transition peer-checked:border-tertiary-fixed-dim">
                                <input type="radio" name="paymentMethod" value="vnpay" class="hidden peer">

                                <div class="bg-blue-500/20 text-blue-400 p-2 rounded-lg">
                                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">credit_card</span>
                                </div>

                                <div class="flex-1">
                                    <h4 class="font-bold text-sm text-white">VNPay</h4>
                                    <p class="text-[11px] text-white/50">Thanh toán qua thẻ ATM / QR VNPay</p>
                                </div>

                                <!-- CHECK -->
                                <div class="w-5 h-5 rounded-full border-2 border-white/30 flex items-center justify-center peer-checked:border-tertiary-fixed-dim">
                                    <div class="w-2.5 h-2.5 bg-tertiary-fixed-dim rounded-full hidden peer-checked:block"></div>
                                </div>
                            </label>

                        </div>
                        <!-- CTA Button -->
                        <button onclick="submitBooking()" class="w-full bg-tertiary-fixed-dim hover:bg-tertiary-fixed text-on-tertiary-fixed font-bold py-4 rounded-xl shadow-lg transition-all active:scale-95 text-lg mt-6">
                            Tiến hành thanh toán
                        </button>
                        <button onclick="cancelBooking()" class="w-full bg-transparent text-white/50 hover:bg-tertiary-fixed hover:text-black text-on-tertiary-fixed font-bold py-4 rounded-xl shadow-lg transition-all active:scale-95 text-lg mt-6">
                            Hủy đặt, chọn lại phòng
                        </button>
                        <p class="text-[10px] text-center text-white/40 leading-tight px-4">
                            Hệ thống sẽ chuyển bạn đến cổng thanh toán bảo mật SSL 256-bit.
                        </p>
                    </div>


                </div>
            </aside>
        </div>
    </main>
    <div id="voucherModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
        <section class="relative w-full max-w-2xl bg-[#faf8ff] rounded-[2rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] mx-4">

            <header class="sticky top-0 z-50 flex justify-between items-center px-8 py-6 w-full border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <h1 class="font-headline font-bold text-lg tracking-tight text-gray-900">Chọn Voucher</h1>
                </div>
                <button onclick="closeVoucherModal()" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors text-gray-500">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </header>

            <div class="flex-1 overflow-y-auto px-8 pb-12 pt-6 custom-scrollbar">
                <div class="mb-10">
                    <label class="block text-sm font-semibold text-gray-600 mb-3 px-1">Nhập mã khuyến mãi</label>
                    <div class="flex gap-3 p-2 bg-gray-100 rounded-2xl border border-gray-200">
                        <div class="flex-1 flex items-center px-4 text-gray-700">
                            <span class="material-symbols-outlined mr-3">confirmation_number</span>
                            <input class="w-full bg-transparent border-none focus:ring-0 text-gray-900 placeholder:text-gray-400 font-medium" placeholder="Ví dụ: HELLO2024" type="text" />
                        </div>
                        <button class="bg-primary text-white px-8 py-3 rounded-xl font-bold transition-transform active:scale-95">
                            Áp dụng
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-headline font-extrabold text-xl text-gray-900">Voucher dành cho bạn</h2>
                </div>

                <div class="grid gap-4" id="voucher-list">
                    <?php if (!empty($data['vouchers'])): ?>
                        <?php foreach ($data['vouchers'] as $v):
                            // 1. Phân loại loại Voucher
                            $isPercent = ($v['type'] === 'PERCENT');

                            // 2. Định dạng hiển thị con số bên trái
                            // Nếu là PERCENT: hiển thị 10%, nếu là FIXED: hiển thị 50k (chia 1000)
                            $discountValue = $isPercent
                                ? (int)$v['amount'] . '%'
                                : number_format($v['amount'] / 1000, 0) . 'k';

                            // 3. Thiết lập màu sắc theo loại
                            $bgLeft = $isPercent ? 'bg-blue-50 text-blue-600' : 'bg-orange-50 text-orange-600';
                            $labelSub = $isPercent ? 'GIẢM TỈ LỆ' : 'GIẢM TIỀN';

                            // 4. Định dạng điều kiện tối thiểu
                            $conditionText = ($v['condition'] > 0)
                                ? 'Đơn tối thiểu ' . number_format($v['condition'], 0, ',', '.') . 'đ'
                                : 'Mọi đơn hàng';
                        ?>
                            <div class="voucher-item relative group bg-white border-2 border-gray-100 hover:border-primary/30 transition-all rounded-[1.5rem] overflow-hidden flex cursor-pointer shadow-sm"
                                onclick="selectVoucher(this, '<?= $v['code'] ?>', '<?= $v['type'] ?>', <?= $v['amount'] ?>)">

                                <div class="selected-badge hidden absolute top-0 right-0 bg-primary text-white px-4 py-1 rounded-bl-2xl text-[10px] font-bold z-10">
                                    ĐANG CHỌN
                                </div>

                                <div class="w-32 <?= $bgLeft ?> flex flex-col items-center justify-center p-4 border-r border-dashed border-gray-200">
                                    <span class="font-black text-2xl"><?= $discountValue ?></span>
                                    <span class="text-[10px] font-bold uppercase tracking-tighter"><?= $labelSub ?></span>
                                </div>

                                <div class="flex-1 p-5 flex justify-between items-center">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-bold text-gray-900 tracking-tight"><?= $v['code'] ?></h3>
                                            <?php if ($v['quantity'] <= 5): ?>
                                                <span class="text-[9px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold">Chỉ còn <?= $v['quantity'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-xs text-gray-600 font-medium"><?= $conditionText ?></p>
                                        <p class="text-[10px] text-gray-400 uppercase tracking-wider">HSD: <?= date('d/m/Y', strtotime($v['endDate'])) ?></p>
                                    </div>

                                    <div class="status-circle w-6 h-6 rounded-full border-2 border-gray-200 flex items-center justify-center group-hover:border-primary transition-colors">
                                        <div class="w-3 h-3 bg-primary rounded-full hidden inner-dot"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-10">
                            <span class="material-symbols-outlined text-gray-300 text-5xl">Confirmation_Number</span>
                            <p class="text-gray-500 mt-2">Hiện chưa có voucher nào dành cho bạn.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <footer class="p-8 bg-white border-t border-gray-100 flex justify-between items-center">
                <div class="flex flex-col">
                    <span class="text-xs text-gray-500 font-medium">Đã chọn 1 voucher</span>
                    <span class="font-bold text-primary text-lg">-20.000 VND</span>
                </div>
                <div class="flex gap-4">
                    <button onclick="closeVoucherModal()" class="px-6 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-100 transition-colors">Đóng</button>
                    <button onclick="closeVoucherModal()" class="bg-primary text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-primary/20">Xác nhận</button>
                </div>
            </footer>
        </section>
    </div>
</body>
<script src="<?= BASE_URL ?>public/js/customer/booking/confirm.js"></script>
<script>
    const user = JSON.parse(localStorage.getItem("user"));
    const customerNameElements = document.querySelectorAll(".customerName");
    const customerEmailElements = document.querySelectorAll(".customerEmail");
    const customerPhoneElements = document.querySelectorAll(".customerPhone");

    if (user && user.fullName) {
        customerNameElements.forEach(el => el.textContent = user.fullName);
    }
    if (user && user.email) {
        customerEmailElements.forEach(el => el.textContent = user.email);
    }
    if (user && user.phone) {
        customerPhoneElements.forEach(el => el.textContent = user.phone);
    }
    async function changeQty(roomConfigId, delta, availableRooms, checkIn, checkOut, uniqueId) {
        // alert(`Change qty for roomConfigId: ${roomConfigId}, delta: ${delta}, availableRooms: ${availableRooms}, checkIn: ${checkIn}, checkOut: ${checkOut}`);
        const action = delta > 0 ? 'add' : 'minus';
        if (action === 'add') {
            Swal.fire({
                title: 'Chức năng đang được phát triển',
                text: 'Bạn có thể bấm vào nút đặt thêm phòng rồi tìm lại phòng này để đặt thêm nếu muốn tăng số lượng.',
                icon: 'warning',


            });
            return
        }



        if (!checkIn || !checkOut) {
            Swal.fire('Lỗi', 'Không tìm thấy thông tin ngày nhận/trả phòng', 'error');
            return;
        }
        // alert(`qty-${roomConfigId}-${uniqueId}`);
        const qtyInput = document.getElementById(`qty-${roomConfigId}-${uniqueId}`);
        let currentQty = parseInt(qtyInput?.textContent || 1);
        let newQty = currentQty + delta;

        // Validate
        if (newQty < 1) {
            if (delta < 0 && currentQty === 1) {
                // Nếu bấm trừ và chỉ còn 1 → hỏi xác nhận xóa
                const confirmDelete = await Swal.fire({
                    title: 'Xóa phòng này?',
                    text: 'Số lượng sẽ về 0 và phòng sẽ bị xóa khỏi giỏ hàng',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                });
                if (!confirmDelete.isConfirmed) return;
                newQty = 0;
            } else {
                Swal.fire('Thông báo', 'Số lượng tối thiểu là 1', 'warning');
                return;
            }
        }

        if (newQty > availableRooms && delta > 0) {
            Swal.fire('Không đủ phòng!', `Chỉ còn ${availableRooms} phòng khả dụng`, 'warning');
            return;
        }

        const token = localStorage.getItem("token");
        if (!token) return handleNotLogin();

        Swal.fire({
            title: 'Đang cập nhật...',
            didOpen: () => Swal.showLoading(),
            allowOutsideClick: false
        });

        try {
            const response = await fetch('<?= BASE_URL ?>booking/create', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    roomConfigId,
                    checkIn,
                    checkOut,
                    quantity: newQty, // Gửi số lượng mới
                    availableRooms,
                    action
                })
            });

            const result = await response.json().catch(() => ({}));

            if (response.ok && result.status === 'success') {
                Swal.close();
                window.location.reload(); // Reload để cập nhật lại giao diện giỏ hàng
            } else {
                Swal.close();
                Swal.fire({
                    title: 'Lỗi',
                    text: result.message || 'Không thể cập nhật số lượng',
                    icon: 'error'
                });
            }
        } catch (error) {
            Swal.close();
            console.error('Change quantity error:', error);
            Swal.fire('Lỗi kết nối', 'Vui lòng thử lại sau', 'error');
        }
    }

    function enableEdit(field) {
        document.getElementById(`view-${field}`).classList.add('hidden');
        document.getElementById(`input-${field}`).classList.remove('hidden');

        // auto fill dữ liệu cũ
        document.getElementById(`input-${field}`).value =
            document.getElementById(`view-${field}`).textContent.trim();
    }

    function validateForm() {
        // ========== 1. LẤY DỮ LIỆU TỪ FORM ==========

        // Lấy thông tin user từ localStorage
        const user = JSON.parse(localStorage.getItem("user"));
        const userId = user?.id || null;

        // Lấy các input fields
        const nameInput = document.getElementById("input-name");
        const emailInput = document.getElementById("input-email");
        const phoneInput = document.getElementById("input-phone");

        // Lấy giá trị Họ tên (ưu tiên input đang edit, nếu không thì lấy view)
        let name = "";
        if (nameInput && !nameInput.classList.contains("hidden")) {
            name = nameInput.value.trim();
        } else {
            name = document.getElementById("view-name")?.textContent.trim() || "";
        }

        // Lấy giá trị Email
        let email = "";
        if (emailInput && !emailInput.classList.contains("hidden")) {
            email = emailInput.value.trim();
        } else {
            email = document.getElementById("view-email")?.textContent.trim() || "";
        }

        // Lấy giá trị Số điện thoại
        let phone = "";
        if (phoneInput && !phoneInput.classList.contains("hidden")) {
            phone = phoneInput.value.trim();
        } else {
            phone = document.getElementById("view-phone")?.textContent.trim() || "";
        }

        // Lấy phương thức thanh toán được chọn
        const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');
        const payment = paymentMethod ? paymentMethod.value : null;

        // Lấy ghi chú đặc biệt
        const specialNote = document.getElementById("special-note")?.value.trim() || "";

        // Lấy dữ liệu booking từ PHP
        const bookingData = <?= json_encode($bookingData) ?>;

        // Lấy tổng tiền từ PHP (chưa bao gồm thuế và voucher)
        const totalAll = <?= $data['totalAll'] ?>;

        // ========== 2. TÍNH TOÁN TIỀN ==========

        // Tính thuế VAT (1%)
        const tax = totalAll * 0.01;

        // Tổng tiền trước khi áp dụng voucher
        const totalBeforeVoucher = totalAll + tax;

        // Khởi tạo biến giảm giá
        let discountAmount = 0;
        let finalTotal = totalBeforeVoucher;

        // Áp dụng voucher nếu có
        if (currentSelectedVoucher) {
            const voucher = currentSelectedVoucher;

            if (voucher.type === 'PERCENT') {
                // Giảm theo phần trăm
                discountAmount = totalBeforeVoucher * (voucher.amount / 100);
            } else if (voucher.type === 'FIXED') {
                // Giảm theo số tiền cố định
                discountAmount = voucher.amount;
            }

            // Đảm bảo số tiền giảm không vượt quá tổng tiền
            discountAmount = Math.min(discountAmount, totalBeforeVoucher);

            // Tổng tiền sau giảm giá
            finalTotal = totalBeforeVoucher - discountAmount;
        }

        // Tính tiền đặt cọc (30% của tổng tiền sau giảm)
        const deposit = finalTotal * 0.3;

        // ========== 3. VALIDATE CÁC TRƯỜNG ==========

        // Validate Họ tên
        if (!name || name.length === 0) {
            Swal.fire({
                title: "Lỗi",
                text: "Vui lòng nhập họ và tên",
                icon: "warning",
                confirmButtonText: "Đã hiểu"
            });
            if (nameInput) nameInput.focus();
            return false;
        }

        // Validate độ dài họ tên (tối thiểu 2 ký tự, tối đa 100 ký tự)
        if (name.length < 2) {
            Swal.fire({
                title: "Lỗi",
                text: "Họ và tên phải có ít nhất 2 ký tự",
                icon: "warning"
            });
            if (nameInput) nameInput.focus();
            return false;
        }

        if (name.length > 100) {
            Swal.fire({
                title: "Lỗi",
                text: "Họ và tên không được vượt quá 100 ký tự",
                icon: "warning"
            });
            if (nameInput) nameInput.focus();
            return false;
        }

        // Validate Email
        if (!email || email.length === 0) {
            Swal.fire({
                title: "Lỗi",
                text: "Vui lòng nhập địa chỉ email",
                icon: "warning"
            });
            if (emailInput) emailInput.focus();
            return false;
        }

        // Regex kiểm tra email hợp lệ
        const emailRegex = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
        if (!emailRegex.test(email)) {
            Swal.fire({
                title: "Lỗi",
                text: "Email không đúng định dạng (ví dụ: ten@example.com)",
                icon: "warning"
            });
            if (emailInput) emailInput.focus();
            return false;
        }

        // Validate Số điện thoại
        if (!phone || phone.length === 0) {
            Swal.fire({
                title: "Lỗi",
                text: "Vui lòng nhập số điện thoại",
                icon: "warning"
            });
            if (phoneInput) phoneInput.focus();
            return false;
        }

        // Loại bỏ các ký tự đặc biệt, chỉ giữ số
        const phoneClean = phone.replace(/[^0-9]/g, '');

        // Kiểm tra số điện thoại Việt Nam (9-11 số, bắt đầu bằng 0 hoặc +84)
        const phoneRegex = /^(0[3|5|7|8|9]|(\+84)[3|5|7|8|9])[0-9]{8}$/;
        if (!phoneRegex.test(phoneClean) && !phoneRegex.test(phone)) {
            Swal.fire({
                title: "Lỗi",
                text: "Số điện thoại không hợp lệ (VD: 0912345678 hoặc +84912345678)",
                icon: "warning"
            });
            if (phoneInput) phoneInput.focus();
            return false;
        }

        // Validate phương thức thanh toán
        if (!payment) {
            Swal.fire({
                title: "Lỗi",
                text: "Vui lòng chọn phương thức thanh toán",
                icon: "warning"
            });
            return false;
        }

        // Validate booking data (kiểm tra có phòng nào không)
        if (!bookingData || bookingData.length === 0) {
            Swal.fire({
                title: "Lỗi",
                text: "Không có phòng nào trong giỏ hàng. Vui lòng chọn phòng trước khi đặt.",
                icon: "warning"
            });
            return false;
        }

        // Validate số lượng phòng (kiểm tra từng phòng)
        for (let i = 0; i < bookingData.length; i++) {
            const room = bookingData[i];
            if (!room.quantity || room.quantity < 1) {
                Swal.fire({
                    title: "Lỗi",
                    text: `Phòng "${room.roomConfig?.hotelName || 'Không xác định'}" có số lượng không hợp lệ`,
                    icon: "warning"
                });
                return false;
            }

            if (room.quantity > room.availableRooms) {
                Swal.fire({
                    title: "Lỗi",
                    text: `Phòng "${room.roomConfig?.hotelName || 'Không xác định'}" chỉ còn ${room.availableRooms} phòng trống`,
                    icon: "warning"
                });
                return false;
            }
        }

        // Validate tiền đặt cọc
        if (deposit <= 0) {
            Swal.fire({
                title: "Lỗi",
                text: "Tổng giá trị đặt phòng quá thấp, không đủ để đặt cọc (tối thiểu 1,000đ)",
                icon: "warning"
            });
            return false;
        }

        if (deposit > 50000000) { // Giới hạn đặt cọc tối đa 50 triệu
            Swal.fire({
                title: "Cảnh báo",
                text: "Số tiền đặt cọc vượt quá giới hạn cho phép. Vui lòng liên hệ hỗ trợ.",
                icon: "warning"
            });
            return false;
        }

        // Validate ghi chú đặc biệt
        if (specialNote.length > 500) {
            Swal.fire({
                title: "Lỗi",
                text: "Yêu cầu đặc biệt không được vượt quá 500 ký tự",
                icon: "warning"
            });
            document.getElementById("special-note")?.focus();
            return false;
        }

        // Validate user đã đăng nhập
        if (!userId) {
            Swal.fire({
                title: "Chưa đăng nhập",
                text: "Vui lòng đăng nhập để tiếp tục đặt phòng",
                icon: "info",
                confirmButtonText: "Đăng nhập ngay"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= BASE_URL ?>login";
                }
            });
            return false;
        }

        // ========== 4. LOG THÔNG TIN (Debug) ==========

        console.log("===== VALIDATION SUCCESS =====");
        console.log("User ID:", userId);
        console.log("Họ tên:", name);
        console.log("Email:", email);
        console.log("SĐT:", phone);
        console.log("Phương thức TT:", payment);
        console.log("Ghi chú:", specialNote || "(không có)");
        console.log("Số lượng phòng:", bookingData.length);
        console.log("Tổng tiền gốc:", totalAll);
        console.log("Thuế (1%):", tax);
        console.log("Tổng trước voucher:", totalBeforeVoucher);

        if (currentSelectedVoucher) {
            console.log("Voucher áp dụng:", currentSelectedVoucher.code);
            console.log("Loại voucher:", currentSelectedVoucher.type);
            console.log("Giá trị giảm:", currentSelectedVoucher.amount);
            console.log("Số tiền giảm:", discountAmount);
        } else {
            console.log("Không áp dụng voucher");
        }

        console.log("Tổng sau giảm:", finalTotal);
        console.log("Tiền đặt cọc (30%):", deposit);
        console.log("Tiền thanh toán sau (70%):", finalTotal * 0.7);
        console.log("==============================");

        // ========== 5. TRẢ VỀ DỮ LIỆU ==========

        return {
            // Thông tin user
            userId: userId,

            // Thông tin khách hàng
            name: name,
            email: email,
            phone: phone,
            specialNote: specialNote,

            // Thông tin thanh toán
            payment: payment,

            // Thông tin booking
            bookingData: bookingData,

            // Thông tin tiền
            totalAll: totalAll, // Tổng tiền gốc
            tax: tax, // Thuế
            totalBeforeVoucher: totalBeforeVoucher, // Tổng trước voucher
            discountAmount: discountAmount, // Số tiền được giảm
            finalTotal: finalTotal, // Tổng sau giảm
            deposit: deposit, // Tiền cọc 30%
            remainingAtHotel: finalTotal * 0.7, // Tiền thanh toán tại khách sạn

            // Thông tin voucher
            voucherCode: currentSelectedVoucher?.code || null,
            voucherType: currentSelectedVoucher?.type || null,
            voucherAmount: currentSelectedVoucher?.amount || null
        };
    }
    // validateForm()
    async function submitBooking() {
        const formData = validateForm();
        if (!formData) return;

        const token = localStorage.getItem("token");
        if (!token) return handleNotLogin();

        Swal.fire({
            title: 'Đang xử lý...',
            didOpen: () => Swal.showLoading(),
            allowOutsideClick: false
        });

        try {
            const response = await fetch('<?= BASE_URL ?>booking/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (result.status === 'success') {
                const paymentRes = await fetch('<?= BASE_URL ?>payment/createPayment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        bookingId: result.bookingId,
                        amount: formData.deposit, // Đã là tiền sau voucher
                        method: formData.payment,
                        voucherCode: formData.voucherCode,
                        discountAmount: formData.discountAmount
                    })
                });

                const paymentData = await paymentRes.json();
                Swal.close();

                if (paymentData.payUrl) {
                    window.location.href = paymentData.payUrl;
                } else {
                    Swal.fire("Lỗi", "Không tạo được link thanh toán", "error");
                }
            } else {
                Swal.close();
                Swal.fire("Lỗi", result.message, "error");
            }
        } catch (e) {
            Swal.close();
            Swal.fire("Lỗi", "Không thể kết nối server", "error");
        }
    }

    function removeVoucher() {
        currentSelectedVoucher = null;

        // Reset hiển thị
        const voucherNameTag = document.getElementById('selected-voucher-name');
        if (voucherNameTag) {
            voucherNameTag.innerHTML = 'Ưu đãi';
            voucherNameTag.classList.replace('text-white', 'text-white/50');
        }

        // Reset các item trong modal
        document.querySelectorAll('.voucher-item').forEach(item => {
            item.classList.remove('border-primary', 'bg-primary/5');
            const badge = item.querySelector('.selected-badge');
            const dot = item.querySelector('.inner-dot');
            if (badge) badge.classList.add('hidden');
            if (dot) dot.classList.add('hidden');
        });

        updateTotalWithVoucher();
    }

    function cancelBooking() {
        Swal.fire({
            title: 'Xác nhận hủy?',
            text: 'Bạn có chắc muốn hủy đặt phòng không?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Có, hủy',
            cancelButtonText: 'Không'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= BASE_URL ?>booking/cancel', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            action: 'clear_booking'
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '<?= BASE_URL ?>';
                        }
                    });
            }
        });
    }

    /**
     * Quản lý logic Voucher và Modal
     */

    // Biến toàn cục lưu trữ voucher đang được chọn
    let currentSelectedVoucher = null;

    /**
     * Mở Popup chọn Voucher
     */
    function openVoucherModal() {
        const modal = document.getElementById('voucherModal');
        if (modal) {
            modal.classList.remove('hidden');
            // Chặn cuộn trang web khi đang mở modal
            document.body.style.overflow = 'hidden';
        }
    }

    /**
     * Đóng Popup chọn Voucher
     */
    function closeVoucherModal() {
        const modal = document.getElementById('voucherModal');
        if (modal) {
            modal.classList.add('hidden');
            // Trả lại trạng thái cuộn trang
            document.body.style.overflow = 'auto';
        }
    }

    /**
     * Logic khi người dùng nhấn chọn một Voucher từ danh sách
     * @param {HTMLElement} element - Thẻ div voucher được click
     * @param {string} code - Mã voucher (VD: 'SUMMER')
     * @param {string} type - Loại voucher ('PERCENT' hoặc 'FIXED')
     * @param {number} amount - Giá trị giảm
     */
    function selectVoucher(element, code, type, amount) {
        // Reset tất cả voucher
        document.querySelectorAll('.voucher-item').forEach(item => {
            item.classList.remove('border-primary', 'bg-primary/5');
            const badge = item.querySelector('.selected-badge');
            const dot = item.querySelector('.inner-dot');
            if (badge) badge.classList.add('hidden');
            if (dot) dot.classList.add('hidden');
        });

        // Kích hoạt voucher được chọn
        element.classList.add('border-primary', 'bg-primary/5');
        const currentBadge = element.querySelector('.selected-badge');
        const currentDot = element.querySelector('.inner-dot');
        if (currentBadge) currentBadge.classList.remove('hidden');
        if (currentDot) currentDot.classList.remove('hidden');

        // Lưu voucher
        currentSelectedVoucher = {
            code,
            type,
            amount: parseInt(amount)
        };

        // Cập nhật hiển thị tên voucher
        const formattedAmount = type === 'PERCENT' ? `${parseInt(amount)}%` : new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
        const voucherNameTag = document.getElementById('selected-voucher-name');
        if (voucherNameTag) {
            voucherNameTag.innerHTML = `<span class="text-tertiary-fixed-dim font-bold">${code}</span> (Giảm ${formattedAmount})`;
            voucherNameTag.classList.replace('text-white/50', 'text-white');
        }

        // Cập nhật lại tổng tiền
        updateTotalWithVoucher();

        setTimeout(closeVoucherModal, 300);
    }
    // Thêm hàm này vào trong thẻ <script>
    function calculateTotalWithVoucher(totalBeforeVoucher, voucher) {
        if (!voucher) return totalBeforeVoucher;

        let discount = 0;
        if (voucher.type === 'PERCENT') {
            discount = totalBeforeVoucher * (voucher.amount / 100);
        } else if (voucher.type === 'FIXED') {
            discount = voucher.amount;
        }

        // Giới hạn discount không vượt quá tổng tiền
        discount = Math.min(discount, totalBeforeVoucher);

        return {
            totalAfterDiscount: totalBeforeVoucher - discount,
            discountAmount: discount
        };
    }

    function updateTotalWithVoucher() {
        const totalAll = <?= $data['totalAll'] ?>;
        const tax = totalAll * 0.01;
        const totalBeforeVoucher = totalAll + tax;

        // Tính giảm giá
        let discountAmount = 0;
        if (currentSelectedVoucher) {
            if (currentSelectedVoucher.type === 'PERCENT') {
                discountAmount = totalBeforeVoucher * (currentSelectedVoucher.amount / 100);
            } else if (currentSelectedVoucher.type === 'FIXED') {
                discountAmount = currentSelectedVoucher.amount;
            }
            discountAmount = Math.min(discountAmount, totalBeforeVoucher);
        }

        const finalTotal = totalBeforeVoucher - discountAmount;
        const deposit = finalTotal * 0.3;
        const remainingAtHotel = finalTotal * 0.7;

        // Cập nhật DOM
        const totalElement = document.querySelector('.pt-4.border-t.border-white\\/10 .text-lg.font-bold');
        const depositElement = document.querySelector('.mt-8 .text-3xl.font-headline');
        const remainingElement = document.querySelector('.mt-8 .text-\\[11px\\].text-white\\/50');

        if (totalElement) {
            totalElement.textContent = new Intl.NumberFormat('vi-VN').format(finalTotal) + ' VND';
        }

        if (depositElement) {
            depositElement.textContent = new Intl.NumberFormat('vi-VN').format(deposit) + ' VND';
        }

        if (remainingElement && remainingElement.nextSibling) {
            remainingElement.textContent = `Còn lại ${new Intl.NumberFormat('vi-VN').format(remainingAtHotel)} VND trả tại khách sạn`;
        }

        // Lưu lại để submit
        window.currentFinalTotal = finalTotal;
        window.currentDeposit = deposit;
    }

    /**
     * Xử lý đóng modal khi click vào vùng tối bên ngoài (Overlay)
     */
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('voucherModal');
        // Nếu click đúng vào vùng overlay (không phải nội dung bên trong) thì đóng
        if (event.target === modal) {
            closeVoucherModal();
        }
    });
</script>

</html>