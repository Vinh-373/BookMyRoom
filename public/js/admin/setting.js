document.addEventListener("DOMContentLoaded", function () {

    /* =========================
       SAVE SYSTEM INFORMATION
    ========================= */

    const saveSystemBtn = document.getElementById("save-system");

    if (saveSystemBtn) {
        saveSystemBtn.addEventListener("click", function () {

            const name = document.getElementById("system-name").value.trim();
            const email = document.getElementById("system-email").value.trim();
            const phone = document.getElementById("system-phone").value.trim();

            if (!name || !email) {
                showToast("Vui lòng nhập đầy đủ thông tin", "error");
                return;
            }

            fetch("/admin/settings/update_system", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    phone: phone
                })
            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {
                    showToast("Cập nhật thành công", "success");
                } else {
                    showToast("Cập nhật thất bại", "error");
                }

            })
            .catch(err => {
                console.error(err);
                showToast("Có lỗi xảy ra", "error");
            });

        });
    }



    /* =========================
       SAVE PAYMENT SETTINGS
    ========================= */

    const savePaymentBtn = document.getElementById("save-payment");

    if (savePaymentBtn) {

        savePaymentBtn.addEventListener("click", function () {

            const platformFee = document.getElementById("platform-fee").value;

            const momo = document.getElementById("payment-momo").checked ? 1 : 0;
            const vnpay = document.getElementById("payment-vnpay").checked ? 1 : 0;
            const visa = document.getElementById("payment-visa").checked ? 1 : 0;

            fetch("/admin/settings/update_payment", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    platformFee: platformFee,
                    momo: momo,
                    vnpay: vnpay,
                    visa: visa
                })
            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {
                    showToast("Cài đặt thanh toán đã lưu", "success");
                } else {
                    showToast("Lưu thất bại", "error");
                }

            });

        });

    }



    /* =========================
       CHANGE PASSWORD
    ========================= */

    const changePasswordBtn = document.getElementById("change-password");

    if (changePasswordBtn) {

        changePasswordBtn.addEventListener("click", function () {

            const currentPassword = document.getElementById("current-password").value;
            const newPassword = document.getElementById("new-password").value;

            if (!currentPassword || !newPassword) {
                showToast("Vui lòng nhập mật khẩu", "error");
                return;
            }

            fetch("/admin/settings/change_password", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    currentPassword: currentPassword,
                    newPassword: newPassword
                })
            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {

                    showToast("Đổi mật khẩu thành công", "success");

                    document.getElementById("current-password").value = "";
                    document.getElementById("new-password").value = "";

                } else {

                    showToast(data.message || "Đổi mật khẩu thất bại", "error");

                }

            });

        });

    }

});



/* =========================
   TOAST NOTIFICATION
========================= */

function showToast(message, type = "success") {

    const toast = document.createElement("div");

    toast.className = "setting-toast " + type;

    toast.innerText = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add("show");
    }, 100);

    setTimeout(() => {

        toast.classList.remove("show");

        setTimeout(() => {
            toast.remove();
        }, 300);

    }, 3000);

}