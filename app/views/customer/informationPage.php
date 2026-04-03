<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân - BookMyRoom.com</title>
    <link rel="stylesheet" href="<?= URLROOT ?>/public/css/customer/information.css">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
    <?php
    $information = $data['information'] ?? [];
    ?>
    <main>
        <div class="profile-header">
            <div class="profile-title">
                <h1>Thông tin cá nhân</h1>
                <p>Cập nhật thông tin của bạn và tìm hiểu các thông tin này được sử dụng ra sao.</p>
            </div>
            <div class="avatar-container">
                <div class="avatar">N</div>
                <div class="camera-icon">
                    <i data-lucide="camera" size="16"></i>
                </div>
            </div>
        </div>

        <div class="profile-section">
            <div class="profile-row" id="name-edit">
                <div class="row-content">
                    <div class="row-main">
                        <span class="label">Họ và tên</span>
                        <span class="value"
                            id="display-name"><?= $information[0]['fullName'] ?? 'Chưa cập nhật' ?></span>
                        <!-- vì $information là mảng các mảng nên phải chỉ định chỉ số 0 là mảng ở ngoài-->
                    </div>
                </div>
                <button class="btn-edit" onclick="toggleEditName(true)">Chỉnh sửa</button>
            </div>

            <!-- Số điện thoại -->
            <div class="profile-row" id="phone-edit">
                <div class="row-content">
                    <div class="row-main">
                        <span class="label">Số điện thoại</span>
                        <span class="value placeholder"><?= $information[0]['phone'] ?? 'Chưa cập nhật' ?></span>
                    </div>
                </div>
                <button class="btn-edit" onclick="toggleEditPhone(true)">Chỉnh sửa</button>
            </div>

            <!-- Ngày sinh -->
            <div class="profile-row" id="birthdate-edit">
                <div class="row-content">
                    <div class="row-main">
                        <span class="label">Ngày sinh</span>
                        <span class="value placeholder"><?= $information[0]['birthDate'] ?? 'Chưa cập nhật' ?></span>
                    </div>
                </div>
                <button class="btn-edit" onclick="toggleEditBirthDate(true)">Chỉnh sửa</button>
            </div>

            <!-- Giới tính -->
            <div class="profile-row" id="gender-edit">
                <div class="row-content">
                    <div class="row-main">
                        <span class="label">Giới tính</span>
                        <span class="value placeholder"><?= $information[0]['gender'] ?? 'Chưa cập nhật' ?></span>
                    </div>
                </div>
                <button class="btn-edit" onclick="toggleEditGender(true)">Chỉnh sửa</button>
            </div>

            <!-- Địa chỉ email -->
            <div class="profile-row" id="email-edit">
                <div class="row-content">
                    <div class="row-main">
                        <span class="label">Địa chỉ email</span>
                        <div style="display: flex; align-items: center;">
                            <span class="value"><?= $information[0]['email'] ?? 'Chưa cập nhật' ?></span>
                        </div>
                    </div>
                    <p class="description">Đây là địa chỉ email bạn dùng để đăng nhập. Chúng tôi cũng sẽ gửi các xác
                        nhận đặt chỗ tới địa chỉ này.</p>

                    <div class="email-box">
                        <p>Bạn không truy cập được email? Nếu đã thêm số điện thoại di động cho đợt lưu trú đã hoàn tất
                            trước đây, bạn có thể tiến hành xác thực số điện thoại di động để đổi địa chỉ email</p>
                    </div>
                </div>
                <button class="btn-edit" onclick="toggleEditEmail(true)">Chỉnh sửa</button>
            </div>

            <!-- Địa chỉ -->
            <div class="profile-row" id="address-edit">
                <div class="row-content">
                    <div class="row-main">
                        <span class="label">Địa chỉ</span>
                        <span class="value placeholder"><?= ($information[0]['address'] ?? '') . ', ' .
                            ($information[0]['wardName'] ?? '') . ', ' .
                            ($information[0]['cityName'] ?? 'Chưa cập nhật') ?></span>
                    </div>
                </div>
                <button class="btn-edit" onclick="toggleEditAddress(true)">Chỉnh sửa</button>
            </div>
        </div>
    </main>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        let currentEditing = null;

        // Data
        let fullName = "<?= $information[0]['fullName'] ?? '' ?>";
        let phone = "<?= $information[0]['phone'] ?? '' ?>";
        let gender = "<?= $information[0]['gender'] ?? '' ?>";
        let email = "<?= $information[0]['email'] ?? '' ?>";
        let birthDate = "<?= $information[0]['birthDate'] ?? '' ?>";
        let address = "<?= $information[0]['address'] ?? '' ?>";
        let wardName = "<?= $information[0]['wardName'] ?? '' ?>";
        let cityName = "<?= $information[0]['cityName'] ?? '' ?>";
        let cityId = "<?= $information[0]['cityId'] ?? '' ?>";
        let wardId = "<?= $information[0]['wardId'] ?? '' ?>";


        // hàm tiện ích để focus vào cuối chuỗi của input khi chuyển sang chế độ edit
        function focusToEnd(id) {
            setTimeout(() => {
                const input = document.getElementById(id);
                if (input) {
                    input.focus();

                    // đưa con trỏ về cuối
                    const length = input.value.length;
                    input.setSelectionRange(length, length);
                }
            }, 0);
        }

        function loadCitiesAndInit() {
            fetch('<?= URLROOT ?>/information/getCity')
                .then(res => res.json())
                .then(data => {
                    const citySelect = document.getElementById('input-city');

                    citySelect.innerHTML = '<option value="">-- Chọn tỉnh/thành --</option>';

                    data.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.id; // lấy id để gửi về server
                        option.textContent = city.name; // hiển thị tên thành phố cho người dùng

                        // 👉 set city ban đầu từ DB
                        if (city.id == cityId) {
                            option.selected = true;
                        }

                        citySelect.appendChild(option);
                    });

                    // 👉 TH1: load ward theo city ban đầu
                    if (cityId) {
                        loadWards(cityId);
                    }
                });
        }

        function loadWards(cityId) {
            const wardSelect = document.getElementById('input-ward');

            if (!cityId) {
                wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
                return;
            }

            fetch(`<?= URLROOT ?>/information/getWardByCityId?cityId=${cityId}`)
                .then(res => res.json())
                .then(data => {
                    wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';

                    data.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.id;
                        option.textContent = ward.name;

                        if (ward.id == wardId) {
                            option.selected = true;
                        }

                        wardSelect.appendChild(option);
                    });
                });
        }

        function toggleEditName(isEditing) {
            const container = document.getElementById('name-edit');

            // Nếu đang edit field khác → reset
            if (currentEditing && currentEditing !== 'name') {
                resetAll();
            }

            if (isEditing) {
                currentEditing = 'name';

                container.innerHTML = `
            <div class="edit-mode">
                <div class="edit-header">
                    <span class="label">Tên</span>
                    <div class="edit-inputs">
                        <div class="input-group">
                            <label>Họ và tên <span class="required">*</span></label>
                            <input type="text" id="input-fullName" class="input-field active" value="${fullName}">
                        </div>
                    </div>
                </div>
                <div class="action-buttons">
                    <button class="btn-cancel" onclick="toggleEditName(false)">Hủy</button>
                    <button type="button" class="btn-save" onclick="saveName()">Lưu</button>
                </div>
            </div>
        `;
                focusToEnd('input-fullName');
            } else {
                currentEditing = null;
                resetAll();
            }
        }

        function toggleEditPhone(isEditing) {
            const container = document.getElementById('phone-edit');

            if (currentEditing && currentEditing !== 'phone') {
                resetAll();
            }

            if (isEditing) {
                currentEditing = 'phone';

                container.innerHTML = `
            <div class="edit-mode">
                <div class="edit-header">
                    <span class="label">Số điện thoại</span>
                    <div class="edit-inputs">
                        <div class="input-group">
                            <label>Số điện thoại <span class="required">*</span></label>
                            <input type="text" id="input-phone" class="input-field active" value="${phone}">
                        </div>  
                    </div>
                </div>
                <div class="action-buttons">
                        <button class="btn-cancel" onclick="toggleEditPhone(false)">Hủy</button>
                        <button type="button" class="btn-save" onclick="savePhone()">Lưu</button>
                    </div>
            </div>
        `;
                focusToEnd('input-phone');
            } else {
                currentEditing = null;
                resetAll();
            }
        }

        function toggleEditBirthDate(isEditing) {
            const container = document.getElementById('birthdate-edit');

            if (currentEditing && currentEditing !== 'birthdate') {
                resetAll();
            }

            if (isEditing) {
                currentEditing = 'birthdate';

                container.innerHTML = `
            <div class="edit-mode">
                <div class="edit-header">
                    <span class="label">Ngày sinh</span>
                    <div class="edit-inputs">
                        <div class="input-group">
                            <label>Ngày sinh <span class="required">*</span></label>
                            <input type="date" id="input-birthdate" class="input-field active" value="${birthDate}">
                        </div>  
                    </div>
                </div>
                <div class="action-buttons">
                        <button class="btn-cancel" onclick="toggleEditBirthDate(false)">Hủy</button>
                        <button type="button" class="btn-save" onclick="saveBirthDate()">Lưu</button>
                    </div>
                
            </div>
        `;
            } else {
                currentEditing = null;
                resetAll();
            }
        }

        function toggleEditGender(isEditing) {
            const container = document.getElementById('gender-edit');

            if (currentEditing && currentEditing !== 'gender') {
                resetAll();
            }

            if (isEditing) {
                currentEditing = 'gender';

                container.innerHTML = `
            <div class="edit-mode">
                <div class="edit-header">
                    <span class="label">Giới tính</span>
                    <div class="edit-inputs">
                        <div class="input-group">
                            <label>Giới tính <span class="required">*</span></label>
                            <select id="input-gender" class="input-field active" value="${gender}">
                                <option value="Nam" ${gender === 'Nam' ? 'selected' : ''}>Nam</option>
                                <option value="Nữ" ${gender === 'Nữ' ? 'selected' : ''}>Nữ</option>
                                <option value="Khác" ${gender === 'Khác' ? 'selected' : ''}>Khác</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="action-buttons">
                        <button class="btn-cancel" onclick="toggleEditGender(false)">Hủy</button>
                        <button type="button" class="btn-save" onclick="saveGender()">Lưu</button>
                </div>
            </div>
        `;
            } else {
                currentEditing = null;
                resetAll();
            }
        }

        function toggleEditEmail(isEditing) {
            const container = document.getElementById('email-edit');

            if (currentEditing && currentEditing !== 'email') {
                resetAll();
            }

            if (isEditing) {
                currentEditing = 'email';

                container.innerHTML = `
                <div class="edit-mode">
                    <div class="edit-header">
                        <span class="label">Địa chỉ email</span>

                        <div class="edit-inputs">
                            <div class="input-group">
                                <label>Địa chỉ email <span class="required">*</span></label>
                                <input type="text" id="input-email" 
                                    class="input-field active" 
                                    value="${email}">
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button class="btn-cancel" onclick="toggleEditEmail(false)">Hủy</button>
                        <button type="button" class="btn-save" onclick="saveEmail()">Lưu</button>
                    </div> 
                </div>
                `;
                focusToEnd('input-email');
            } else {
                currentEditing = null;
                resetAll();
            }
        }

        function toggleEditAddress(isEditing) {
            const container = document.getElementById('address-edit');

            if (currentEditing && currentEditing !== 'address') {
                resetAll();
            }

            if (isEditing) {
                currentEditing = 'address';

                container.innerHTML = `
        <div class="edit-mode">
            <div class="edit-header">
                <span class="label">Địa chỉ</span>

                <div class="edit-inputs">
                    <div class="input-group">
                        <label>Tỉnh/Thành phố</label>
                        <select id="input-city" class="input-field active"></select>
                    </div>

                    <div class="input-group">
                        <label>Phường/Xã</label>
                        <select id="input-ward" class="input-field active"></select>
                    </div>

                    <div class="input-group">
                        <label>Số nhà, tên đường</label>
                        <input type="text" id="input-address" 
                            class="input-field active" 
                            value="${address}">
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <button class="btn-cancel" onclick="toggleEditAddress(false)">Hủy</button>
                <button class="btn-save" onclick="saveAddress()">Lưu</button>
            </div>
        </div>
        `;

                // load city + set selected + load ward luôn
                loadCitiesAndInit();

                // TH2: khi đổi city
                document.getElementById('input-city').addEventListener('change', function () {
                    cityIdupdate = this.value;   
                    loadWards(cityIdupdate);     // load ward theo city mới
                });

            } else {
                currentEditing = null;
                resetAll();
            }
        }

        function resetAll() {
            // Reset Name
            document.getElementById('name-edit').innerHTML = `
        <div class="row-content">
            <div class="row-main">
                <span class="label">Họ và tên</span>
                <span class="value">${fullName} </span>
            </div>
        </div>
        <button class="btn-edit" onclick="toggleEditName(true)">Chỉnh sửa</button>
    `;

            // Reset Phone
            document.getElementById('phone-edit').innerHTML = `
        <div class="row-content">
            <div class="row-main">
                <span class="label">Số điện thoại</span>
                <span class="value">${phone || "Thêm số điện thoại của bạn"}</span>
            </div>
        </div>
        <button class="btn-edit" onclick="toggleEditPhone(true)">Chỉnh sửa</button>
    `;

            // Reset BirthDate
            document.getElementById('birthdate-edit').innerHTML = `
        <div class="row-content">
            <div class="row-main">
                <span class="label">Ngày sinh</span>
                <span class="value">${birthDate || "Thêm ngày sinh của bạn"}</span>
            </div>
        </div>
        <button class="btn-edit" onclick="toggleEditBirthDate(true)">Chỉnh sửa</button>
    `;

            // Reset gender
            document.getElementById('gender-edit').innerHTML = `
        <div class="row-content">
            <div class="row-main">
                <span class="label">Giới tính</span>
                <span class="value">${gender || "Chọn giới tính của bạn"}</span>
            </div>
        </div>
        <button class="btn-edit" onclick="toggleEditGender(true)">Chỉnh sửa</button>
    `;

            // Reset Email
            document.getElementById('email-edit').innerHTML = `
        <div class="row-content">
            <div class="row-main">
                <span class="label">Địa chỉ email</span>
                <div style="display: flex; align-items: center;">
                    <span class="value">${email}</span>
                </div>
            </div>
            <p class="description">Đây là địa chỉ email bạn dùng để đăng nhập. Chúng tôi cũng sẽ gửi các xác
                nhận đặt chỗ tới địa chỉ này.</p>       
            <div class="email-box">
                <p>Bạn không truy cập được email? Nếu đã thêm số điện thoại di động cho đợt lưu trú đã hoàn tất
                    trước đây, bạn có thể tiến hành xác thực số điện thoại di động để đổi địa chỉ email</p>
            </div>
        </div>
        <button class="btn-edit" onclick="toggleEditEmail(true)">Chỉnh sửa</button>
    `;

            // Reset Address
            document.getElementById('address-edit').innerHTML = `
        <div class="row-content">
            <div class="row-main">
                <span class="label">Địa chỉ</span>
                <span class="value">${address || ''}, ${wardName || ''}, ${cityName || ''}</span>
            </div>
        </div>
        <button class="btn-edit" onclick="toggleEditAddress(true)">Chỉnh sửa</button>
    `;
        }

        function validateFullName(fullName) {
            if (!fullName || fullName.trim() === "") {
                return "Họ và tên không được để trống";
            }

            // (optional) kiểm tra ký tự hợp lệ (chỉ chữ và khoảng trắng)
            const regex = /^[A-Za-zÀ-ỹ\s]+$/;
            if (!regex.test(fullName)) {
                return "Họ và tên không hợp lệ";
            }

            return null; // hợp lệ
        }

        function validatePhone(phone) {
            if (!phone || phone.trim() === "") {
                return "Số điện thoại không được để trống";
            }

            const regex = /^0\d{9}$/;

            if (!regex.test(phone)) {
                return "Số điện thoại phải gồm 10 số và bắt đầu bằng 0";
            }

            return null;
        }

        function validateEmail(email) {
            if (!email || email.trim() === "") {
                return "Email không được để trống";
            }

            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!regex.test(email)) {
                return "Email không đúng định dạng";
            }

            return null;
        }

        function validateAddress(address) {
            if (!address || address.trim() === "") {
                return "Địa chỉ không được để trống";
            }

            return null;
        }

        function validateCity(cityId) {
            if (!cityId) {
                return "Vui lòng chọn tỉnh/thành phố";
            }

            return null;
        }

        function validateWard(wardName) {
            if (!wardName) {
                return "Vui lòng chọn phường/xã";
            }

            return null;
        }

        function validateGender(gender) {
            if (!gender) {
                return "Vui lòng chọn giới tính";
            }
            return null;
        }

        function saveName() {
            const value = document.getElementById('input-fullName').value;

            const error = validateFullName(value);
            if (error) {
                alert(error);
                document.getElementById('input-fullName').focus();
                return;
            }
            fetch('<?= URLROOT ?>/information/updateFullName', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    fullName: value
                })
            })
                .then(res => res.json())
                .then(data => {
                    console.log("Response từ server:", data);

                    if (data.success) {
                        alert("Cập nhật thành công");
                        fullName = value;

                        toggleEditName(false);
                    } else {
                        alert(data.message);
                    }
                });
        }

        function savePhone() {
            const value = document.getElementById('input-phone').value;

            const error = validatePhone(value);
            if (error) {
                alert(error);
                document.getElementById('input-phone').focus();
                return;
            }

            fetch('<?= URLROOT ?>/information/updatePhoneNumber', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    phone: value
                })
            })
                .then(res => res.json())
                .then(data => {
                    console.log("Response từ server:", data);

                    if (data.success) {
                        alert("Cập nhật thành công");
                        phone = value;

                        toggleEditPhone(false);
                    } else {
                        document.getElementById('input-phone').focus();
                        alert(data.message);
                    }
                });
        }

        function saveBirthDate() {
            birthDate = document.getElementById('input-birthdate').value;
            fetch('<?= URLROOT ?>/information/updateBirthDate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    birthDate: birthDate
                })
            })
                .then(res => res.json())
                .then(data => {
                    console.log("Response từ server:", data);

                    if (data.success) {
                        alert("Cập nhật thành công");
                        toggleEditBirthDate(false);
                    } else {
                        alert(data.message);
                    }
                });
        }

        function saveGender() {
            const value = document.getElementById('input-gender').value;

            const error = validateGender(value);
            if (error) {
                alert(error);
                document.getElementById('input-gender').focus();
                return;
            }

            fetch('<?= URLROOT ?>/information/updateGender', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    gender: value
                })
            })
                .then(res => res.json())
                .then(data => {
                    console.log("Response từ server:", data);

                    if (data.success) {
                        alert("Cập nhật thành công");
                        gender = value;

                        toggleEditGender(false);
                    } else {
                        alert(data.message);
                    }
                });
        }

        function saveEmail() {
            const value = document.getElementById('input-email').value;

            const error = validateEmail(value);
            if (error) {
                alert(error);
                document.getElementById('input-email').focus();
                return;
            }

            fetch('<?= URLROOT ?>/information/updateEmail', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    email: value
                })
            })
                .then(res => res.json())
                .then(data => {
                    console.log(data);

                    if (data.success) {
                        alert(data.message);
                        email = value;
                        toggleEditEmail(false);
                    } else {
                        alert(data.message);
                        document.getElementById('input-email').focus();
                    }
                });
        }

        function saveAddress() {
            const value = document.getElementById('input-address').value;
            const valuecCity = document.getElementById('input-city');
            const valueWard = document.getElementById('input-ward');

            const citySelect = valuecCity.value;
            const wardSelect = valueWard.value;

            const cityNameValue = valuecCity.options[valuecCity.selectedIndex].text;
            const wardNameValue = valueWard.options[valueWard.selectedIndex].text;


            const error = validateAddress(value);
            if (error) {
                alert(error);
                document.getElementById('input-address').focus();
                return;
            }
            const cityError = validateCity(citySelect);
            if (cityError) {
                alert(cityError);
                document.getElementById('input-city').focus();
                return;
            }

            const wardError = validateWard(wardSelect);
            if (wardError) {
                alert(wardError);
                wardSelect.focus();
                return;
            }

            fetch('<?= URLROOT ?>/information/updateAddress', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    address: value,
                    cityId: citySelect,
                    wardId: wardSelect
                })
            })
                .then(res => res.json())
                .then(data => {
                    console.log("Response từ server:", data);

                    if (data.success) {
                        alert("Cập nhật thành công");

                        address = value;
                        cityId = citySelect;
                        wardId = wardSelect;

                        cityName = cityNameValue;
                        wardName = wardNameValue;

                        toggleEditAddress(false);
                    } else {
                        alert(data.message);
                    }
                });
        }

    </script>
</body>

</html>