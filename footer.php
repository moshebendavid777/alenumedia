<?php
/**
 * Theme footer.
 *
 * @package AlenuMedia
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
    <footer class="site-footer">
        <div class="site-footer__inner">
            <p><?php echo esc_html(get_bloginfo('name')); ?></p>
            <p><?php echo esc_html__('Built for bold brands. Powered by WordPress.', 'alenumedia'); ?></p>
        </div>
    </footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
