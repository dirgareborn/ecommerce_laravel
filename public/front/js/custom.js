document.querySelectorAll('.dropdown-submenu').forEach(function (el) {
    // Hentikan JS template agar tidak override submenu
    el.addEventListener('mouseenter', function (e) {
        e.stopPropagation();
    });
    el.addEventListener('mouseleave', function (e) {
        e.stopPropagation();
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll(".hero-parallax-slider .bg-layer");
    let index = 0;

    function nextSlide() {
        slides[index].classList.remove("show");
        index = (index + 1) % slides.length;
        slides[index].classList.add("show");
    }

    setInterval(nextSlide, 6000); // ganti slide tiap 6 detik

    // Parallax saat scroll
    window.addEventListener("scroll", function () {
        const scrolled = window.pageYOffset;
        slides.forEach(slide => {
            slide.style.transform = `translateY(${scrolled * 0.25}px)`;
        });
    });
});

let lastScroll = 0;
const topHeader = document.querySelector('.top-header');
const navbar = document.querySelector('.nav-bar');

window.addEventListener('scroll', function () {
    const currentScroll = window.pageYOffset;

    if (currentScroll <= 0) {
        // posisi paling atas
        topHeader.classList.remove('hide');
        navbar.classList.remove('compact');
        return;
    }

    // Scroll down → sembunyikan top header
    if (currentScroll > lastScroll) {
        topHeader.classList.add('hide');
        navbar.classList.add('compact');
    }
    // Scroll up → tampilkan lagi
    else {
        topHeader.classList.remove('hide');
        navbar.classList.remove('compact');
    }

    lastScroll = currentScroll;
});
// Navbar bottom hide on scroll
let prevScrollpos = window.pageYOffset;
window.addEventListener('scroll', () => {
    const navbar = document.getElementById("navbarBottom");
    if (!navbar) return;
    const currentScrollPos = window.pageYOffset;
    navbar.style.bottom = prevScrollpos > currentScrollPos ? "0" : "-70px";
    prevScrollpos = currentScrollPos;
});

// Search Overlay + Hamburger
document.addEventListener("DOMContentLoaded", () => {
    const overlay = document.getElementById("searchOverlay");
    const openBtns = [document.getElementById("desktopSearchBtn"), document.getElementById("mobileSearchBtn")];
    const closeBtn = document.getElementById("closeSearch");
    const toggler = document.querySelector(".custom-toggler");

    openBtns.forEach(btn => btn?.addEventListener("click", () => overlay?.classList.add("active")));
    closeBtn?.addEventListener("click", () => overlay?.classList.remove("active"));
    toggler?.addEventListener("click", () => toggler.classList.toggle("active"));
});

$(function () {
    // ===============================
    // 🔒 Password Verification
    // ===============================
    $("#current_pwd").on("keyup", function () {
        const currentPwd = $(this).val();
        $.ajax({
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            type: "POST",
            url: "/check-current-password",
            data: { current_pwd: currentPwd },
            success: function (resp) {
                const message = resp === "true"
                    ? "Password yang anda masukkan benar!"
                    : "Password yang anda masukkan salah";
                $("#verifyCurrentPwd").text(message);
            },
            error: function () {
                $("#verifyCurrentPwd").text("Error!");
            }
        });
    });

    // ===============================
    // 🔽 Sorting
    // ===============================
    $("#sort").on("change", function () {
        this.form.submit();
    });

    // ===============================
    // 🛒 Add to Cart (Realtime Cart Update + Validasi Tanggal)
    // ===============================
    $("#addCart").on("submit", function (e) {
        e.preventDefault();

        const start = $("#start").val();
        const end = $("#end").val();

        // 🔧 Alert helper (auto-hide)
        const showAlert = (type, message) => {
            const alertClass = type === "success" ? "alert-success" : "alert-danger";
            const target = type === "success" ? ".print-success-msg" : ".print-error-msg";

            $(".print-error-msg, .print-success-msg").hide();
            $(target)
                .html(`
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `)
                .fadeIn(200);

            setTimeout(() => {
                $(target).fadeOut(400, () => $(target).empty());
            }, 3000);
        };

        // 🔎 Validasi tanggal input
        if (!start || !end) {
            return showAlert("error", "Silakan pilih tanggal mulai dan tanggal selesai.");
        }

        const today = new Date();
        today.setHours(0, 0, 0, 0); // Abaikan waktu (jam:menit)
        const startDate = new Date(start);
        const endDate = new Date(end);

        if (startDate < today) {
            return showAlert("error", "Tanggal mulai tidak boleh sebelum hari ini.");
        }

        if (endDate < startDate) {
            return showAlert("error", "Tanggal selesai tidak boleh lebih awal dari tanggal mulai.");
        }

        // 🧩 Kirim data ke server
        $.ajax({
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            type: "POST",
            url: "/addCart",
            data: $(this).serialize(),
            beforeSend: function () {
                $(".print-error-msg, .print-success-msg").hide();
            },
            success: function (resp) {
                if (resp.status) {
                    showAlert("success", resp.message);

                    // 🧺 Update tampilan keranjang secara realtime tanpa refresh
                    if (resp.totalCartItems !== undefined) {
                        $("#totalCartItems").html(resp.totalCartItems);
                    }
                    if (resp.view) {
                        $("#appendCartItems").html(resp.view);
                    }
                    if (resp.minicartview) {
                        $("#appendMiniCartItems").html(resp.minicartview);
                    }

                    // Reset form setelah sukses tambah ke cart
                    $("#addCart")[0].reset();

                } else {
                    showAlert("error", resp.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
                showAlert("error", "Terjadi kesalahan saat mengirim data. Silakan coba lagi.");
            }
        });
    });

    $(document).on("click", ".confirmDelete", function () {
        var record = $(this).attr('record');
        var recordid = $(this).attr('recordid');
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Anda tidak dapat mengembalikan data yang sudah dihapus!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Terhapus",
                    text: "Data sudah dihapus",
                    icon: "success"
                });
                window.location.href = "/cart/delete-" + record + "/" + recordid;
            }
        });
    });
    //
    // ===============================
    // 🧹 Delete Cart Item (No Message)
    // ===============================
    $(document).on("click", ".deleteCartItem", function () {
        const cartId = $(this).data("cartid");
        if (!confirm("Apakah anda ingin menghapus dari keranjang?")) return;

        $.ajax({
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            type: "POST",
            url: "/cart/delete-cart-item",
            data: { cartid: cartId },
            success: function (resp) {
                // Update tampilan keranjang tanpa pesan
                $("#totalCartItems").html(resp.totalCartItems);
                $("#appendCartItems").html(resp.view);
                $("#appendMiniCartItems").html(resp.minicartview);
            },
            error: function () {
                // Tidak ada pesan error
                console.error("Terjadi kesalahan saat menghapus item.");
            }
        });
    });

    // ===============================
    // 🎟️ Apply Coupon (Refactor)
    // ===============================
    $(document).on("click", "#ApplyCoupon", function (e) {
        e.preventDefault();

        const userLoggedIn = $(this).data("user") == 1; // perhatikan: pakai data-user
        if (!userLoggedIn) {
            alert("Silakan login untuk menggunakan kupon.");
            return false;
        }

        const code = $("#code").val().trim();
        if (code === "") {
            alert("Silakan masukkan kode kupon terlebih dahulu.");
            return false;
        }

        $.ajax({
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            type: "POST",
            url: "/apply-coupon",
            data: { code },
            beforeSend: function () {
                $("#ApplyCoupon").prop("disabled", true).text("Memproses...");
            },
            success: function (resp) {
                const success = resp.status === true;

                const alertClass = success ? "alert-success" : "alert-danger";
                const $alertBox = $(`.${alertClass}`);

                $alertBox.html(`
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <strong>${resp.message}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `).fadeIn().delay(4000).fadeOut("slow");

                if (success) {
                    // Update tampilan summary
                    $(".couponAmount").text(resp.couponAmountFormatted ?? "Rp 0");
                    $(".grandTotal").text(resp.grandTotalFormatted ?? "Rp 0");

                    // Kalau kamu punya mini-cart dan cart list yang ingin direfresh
                    if (resp.view) $("#appendCartItems").html(resp.view);
                    if (resp.minicartview) $("#appendMiniCartItems").html(resp.minicartview);
                    if (resp.totalCartItems) $("#totalCartItems").html(resp.totalCartItems);
                }
            },
            error: function () {
                alert("Terjadi kesalahan saat memproses kupon.");
            },
            complete: function () {
                $("#ApplyCoupon").prop("disabled", false).text("Pakai");
            }
        });
    });

    // ===============================
    // 💳 Coupon Field Toggle
    // ===============================
    $("#ManualCoupon").on("click", () => $("#couponField").show());
    $("#AutomaticCoupon").on("click", () => $("#couponField").hide());
});

$("#newsletterForm").on("submit", function (e) {
    e.preventDefault();
    var form = $(this);
    var email = $("#newsletterEmail").val();
    var token = form.find('input[name="_token"]').val();

    $.ajax({
        url: "/newsletter/store",
        type: "POST",
        data: { email: email, _token: token },
        success: function (res) {
            showToast(res.message, res.success ? 'bg-success' : 'bg-danger');
            if (res.success) form[0].reset();
        },
        error: function (xhr) {
            let msg = xhr.responseJSON?.errors?.email?.[0] || 'Terjadi kesalahan. Coba lagi.';
            showToast(msg, 'bg-danger');
        }
    });
});

function showToast(message, bgClass) {
    let toastEl = $("#centerToast");
    let toastMessage = $("#centerToastBody");
    toastMessage.text(message);
    toastEl.removeClass("bg-success bg-danger").addClass(bgClass);
    toastEl.show();
    var toast = new bootstrap.Toast(toastEl[0], { delay: 3000 });
    toast.show();
}