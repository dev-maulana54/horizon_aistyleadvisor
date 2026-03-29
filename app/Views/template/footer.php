<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
<script src="<?= base_url('assets/js/script.js') ?>"></script>
<script src="<?= base_url('assets/js/modal.js') ?>"></script>
<script src="<?= base_url('assets/js/notify_ai.js') ?>"></script>
<script src="<?= base_url('assets/js/previewimg.js') ?>"></script>
<script src="<?= base_url('assets/js/chat.js') ?>"></script>
<script>
    const baseUrl = "<?= base_url() ?>";
</script>
<?php if (isset($menu) && $menu === 'settings_profile') : ?>
    <script>
        function loadWardrobeByCategory(id) {
            console.log('Loading wardrobe for category ID:', id);
            $.ajax({
                url: `<?= base_url('getWardrobeUser') ?>/${id}`,
                method: 'GET',
                success: function(data) {
                    renderWardrobeCards(data);
                }
            });
        }

        function renderWardrobeCards(wardrobes) {
            const container = $('#wardrobe-container');
            container.empty();

            // Jika response berupa object dengan property message
            if (wardrobes.message) {
                container.append(`<p style="grid-column: 1 / -1;">${wardrobes.message}</p>`);
                return;
            }

            $.each(wardrobes, function(i, w) {
                container.append(`
            <div class="wc-card">
                <div class="wc-card-header">
                    <p class="wc-name">${w.nama_item}</p>
                </div>
                <div class="wc-card-inner">
                    <div class="wc-img-wrap">
                        <img src="<?= base_url('uploads/wardrobe/') ?>${w.file_name}" alt="shirt" data-preview>
                    </div>
                </div>
                <button class="wc-remove">×</button>
            </div>
        `);
            });
        }
        $(document).ready(function() {
            console.log("Settings profile script loaded");
            const activeBtn = $('.wardrobe-category-btn.active');
            console.log('activeBtn found:', activeBtn.length); // cek ketemu atau tidak
            console.log('activeBtn id:', activeBtn.data('id')); // cek id-nya

            if (activeBtn.length && activeBtn.data('id')) { // ← tambah pengecekan id
                const id_type_wardrobe = activeBtn.data('id');
                loadWardrobeByCategory(id_type_wardrobe);
            }
        });
        $('.updatePersonalData').click(function() {
            let bodyShape = $('input[name="body_shape"]:checked').val();
            console.log(bodyShape);
            let styles = [];

            $('.style-pref-input:checked').each(function() {
                styles.push($(this).val());
            });

            console.log(styles);
            $.ajax({
                url: baseUrl + 'settings/updatePersonalData',
                method: 'POST',
                data: {
                    body_shape: bodyShape,
                    styles: styles
                },
                success: function(response) {
                    if (response.status == "success") {
                        Notify.fire({
                            type: "success",
                            title: "Berhasil!",
                            text: response.message || "Data Berhasil diupdate!",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(); // reload page setelah klik OK
                            }
                        });

                    } else {
                        Notify.fire({
                            type: "error",
                            title: "Gagal!",
                            text: "Body Shape dan Style Preference wajib diisi. ",
                        });
                    }
                },
                error: function() {
                    Notify.fire({
                        type: "error",
                        title: "Gagal!",
                        text: response.message || "Terjadi kesalahan. Silakan coba lagi.",
                    });
                }
            });
        });
        // dari body
        $('body').on('click', '.wardrobe-category-btn', function() {
            console.log('Element HTML:', $(this)[0].outerHTML); // cek atribut lengkap
            console.log('All data:', $(this).data()); // cek semua data-*
            console.log('id_type_wardrobe:', $(this).data('id'));

            var id_type_wardrobe = $(this).data('id');
            if (!id_type_wardrobe) return; // guard biar ga hit undefined
            loadWardrobeByCategory(id_type_wardrobe);
        });
    </script>
<?php endif; ?>
</body>

</html>