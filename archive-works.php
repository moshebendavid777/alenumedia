<?php
/**
 * Works archive template.
 *
 * @package AlenuMedia
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main class="site-main inner-main">
    <header class="floating-nav floating-nav--inner floating-nav--centered reveal reveal--up">
        <button type="button" class="floating-nav__toggle" aria-expanded="false">
            <span><?php esc_html_e('Menu', 'alenumedia'); ?></span>
        </button>
        <a class="brand-link" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Back to homepage', 'alenumedia'); ?>">
            <?php echo alenumedia_get_logo_markup(); ?>
        </a>
        <div class="floating-nav__menu">
            <nav class="floating-nav__side floating-nav__side--left" aria-label="<?php esc_attr_e('Archive navigation left', 'alenumedia'); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'alenumedia'); ?></a>
                <a href="<?php echo esc_url(home_url('/#works')); ?>"><?php esc_html_e('Selected Work', 'alenumedia'); ?></a>
            </nav>
            <nav class="floating-nav__side floating-nav__side--right" aria-label="<?php esc_attr_e('Archive navigation right', 'alenumedia'); ?>">
                <a href="<?php echo esc_url(home_url('/#pricing')); ?>"><?php esc_html_e('Pricing', 'alenumedia'); ?></a>
                <a href="<?php echo esc_url(home_url('/#quote')); ?>"><?php esc_html_e('Contact', 'alenumedia'); ?></a>
            </nav>
        </div>
    </header>
    <section class="section-shell section-grid works-archive-shell">
        <div class="works-archive-hero reveal reveal--up">
            <div class="section-heading works-archive-hero__copy">
                <span class="eyebrow"><?php esc_html_e('Archive', 'alenumedia'); ?></span>
                <h1><?php post_type_archive_title(); ?></h1>
                <p><?php esc_html_e('A living index of launch experiences, websites, systems, and branded digital worlds built for clients who want a sharper presence than the market norm.', 'alenumedia'); ?></p>
            </div>
            <div class="works-archive-hero__panel">
                <span><?php esc_html_e('Browse By Category', 'alenumedia'); ?></span>
                <strong><?php esc_html_e('Six curated tabs, editorial cards, and a single-work experience built to feel more like a digital feature than a standard project page.', 'alenumedia'); ?></strong>
            </div>
        </div>
        <?php echo wp_kses_post(alenumedia_render_work_browser('archive')); ?>
    </section>
</main>
<?php
get_footer();
