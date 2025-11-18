$(document).ready(function() {
    // --- Ketika tombol Upload Bukti ditekan ---
    $(document).on('click', '.uploadProofBtn', function() {
        var orderId = $(this).data('orderid');
        $('#order_id').val(orderId);
        $('#payment_proof').val('');
        $('#previewImage').html('');
    });

    // --- Preview gambar sebelum upload ---
    $('#payment_proof').on('change', function() {
        var file = this.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB!');
                $(this).val('');
                return;
            }
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImage').html(
                    '<img src="' + e.target.result + '" class="img-fluid rounded mt-2 shadow-sm" style="max-height:200px;">'
                );
            };
            reader.readAsDataURL(file);
        }
    });

     // --- Submit upload bukti via AJAX ---
    $('#uploadProofForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            url: "/upload-payment-proof",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#uploadProofForm button[type="submit"]').prop('disabled', true).text('Mengunggah...');
            },
            success: function(response) {
                $('#uploadProofForm button[type="submit"]').prop('disabled', false).text('Upload');
                $('#uploadProofModal').modal('hide');

                // Notifikasi sukses
                alert(response.message || 'Bukti pembayaran berhasil diunggah!');
                location.reload(); // Refresh halaman agar status update
            },
            error: function(xhr) {
                $('#uploadProofForm button[type="submit"]').prop('disabled', false).text('Upload');
                alert(xhr.responseJSON?.message || 'Terjadi kesalahan. Coba lagi.');
            }
        });
    });

  // === Modal Lihat Bukti ===
    // --- Tampilkan bukti pembayaran di modal ---
// $(document).on('click', '.viewProofBtn', function () {
//     const proofUrl = $(this).data('proof');
//     const container = $('#proofImageContainer');

//     if (proofUrl) {
//         container.html(`
//             <img src="${proofUrl}" alt="Bukti Pembayaran" class="img-fluid rounded shadow-sm mb-3" style="max-height:500px;">
//             <br>
//             <a href="${proofUrl}" download class="btn btn-primary">
//                 <i class="fa fa-download"></i> Unduh Bukti
//             </a>
//         `);
//     } else {
//         container.html(`<div class="text-muted">Bukti pembayaran tidak ditemukan.</div>`);
//     }
// });

    // $(document).on('click', '.viewProofBtn', function() {
    //     var proofUrl = $(this).data('proof');
    //     var html = `
    //         <img src="${proofUrl}" alt="Bukti Pembayaran" class="img-fluid rounded shadow-sm">
    //         <div class="mt-3">
    //             <a href="${proofUrl}" download class="btn btn-outline-primary btn-sm">
    //                 <i class="fa fa-download"></i> Unduh Bukti
    //             </a>
    //         </div>`;
    //     $('#proofImageContainer').html(html);
    // });

    // // Hapus konten modal ketika ditutup agar tidak tumpuk
    // $('#viewProofModal').on('hidden.bs.modal', function() {
    //     $('#proofImageContainer').html('<div class="text-muted">Memuat bukti pembayaran...</div>');
    // });
$(document).on('click', '.viewProofBtn', function () {
    const proofUrl = $(this).data('proof');
    const container = $('#proofImageContainer');

    if (proofUrl) {
        container.html(`
            <img src="${proofUrl}" alt="Bukti Pembayaran" class="img-fluid rounded shadow-sm mb-3" style="max-height:500px;">
            <br>
            <a href="${proofUrl}" class="btn btn-primary" target="_blank">
                <i class="fa fa-download"></i> Unduh Bukti
            </a>
        `);
    } else {
        container.html(`<div class="text-muted">Bukti pembayaran tidak ditemukan.</div>`);
    }
});
});
