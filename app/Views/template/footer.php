<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?= base_url('assets/js/script.js') ?>"></script>
<script src="<?= base_url('assets/js/modal.js') ?>"></script>
<script src="<?= base_url('assets/js/notify_ai.js') ?>"></script>
<script>
    const baseUrl = "<?= base_url() ?>";
</script>
<?php if (isset($menu) && $menu === 'settings_profile') : ?>
    <script>
        $(document).ready(function() {
            console.log("Settings profile script loaded");
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
    </script>
<?php endif; ?>
</body>

</html>