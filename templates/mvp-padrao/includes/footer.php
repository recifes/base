        </div> <!-- Fecha container-fluid -->

        <?php
        $baseCdnUrl = getenv('BASE_CDN_URL') ?: 'https://base.hsn.com.br';
        $argonVersion = getenv('BASE_ARGON_VERSION') ?: 'v1.0.0';
        ?>

        <!-- Footer -->
        <footer class="footer pt-3">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            © <?php echo date('Y'); ?> Grupo HSN - Todos os direitos reservados
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </main>

    <!-- Core JS -->
    <script src="<?php echo $baseCdnUrl; ?>/argon/versions/<?php echo $argonVersion; ?>/js/core/popper.min.js"></script>
    <script src="<?php echo $baseCdnUrl; ?>/argon/versions/<?php echo $argonVersion; ?>/js/core/bootstrap.min.js"></script>

    <!-- Plugins JS -->
    <script src="<?php echo $baseCdnUrl; ?>/argon/versions/<?php echo $argonVersion; ?>/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="<?php echo $baseCdnUrl; ?>/argon/versions/<?php echo $argonVersion; ?>/js/plugins/smooth-scrollbar.min.js"></script>

    <!-- Argon Dashboard JS -->
    <script src="<?php echo $baseCdnUrl; ?>/argon/versions/<?php echo $argonVersion; ?>/js/argon-dashboard.min.js"></script>

    <!-- Custom JS (opcional) -->
    <script src="/assets/js/custom.js"></script>

</body>
</html>
