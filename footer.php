</main>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-inner">
        <div class="footer-logo">
            <span class="logo-mark">Ball Street</span>
            <span class="logo-text">Sports Journal</span>
        </div>

        <nav class="footer-links">
            <a href="<?php echo esc_url(
                home_url("/about"),
            ); ?>" class="footer-link">About</a>
            <a href="<?php echo esc_url(
                home_url("/careers"),
            ); ?>" class="footer-link">Careers</a>
            <a href="<?php echo esc_url(
                home_url("/advertise"),
            ); ?>" class="footer-link">Advertise</a>
            <a href="<?php echo esc_url(
                home_url("/contact"),
            ); ?>" class="footer-link">Contact</a>
            <a href="<?php echo esc_url(
                home_url("/privacy"),
            ); ?>" class="footer-link">Privacy</a>
            <a href="<?php echo esc_url(
                home_url("/terms"),
            ); ?>" class="footer-link">Terms</a>
        </nav>

        <span class="footer-copy">&copy; <?php echo date(
            "Y",
        ); ?> Ball Street Sports Journal</span>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
