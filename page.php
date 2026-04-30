<?php
/**
 * Default page template.
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
            <nav class="floating-nav__side floating-nav__side--left" aria-label="<?php esc_attr_e('Page navigation left', 'alenumedia'); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'alenumedia'); ?></a>
                <a href="<?php echo esc_url(home_url('/#how-it-works')); ?>"><?php esc_html_e('How It Works', 'alenumedia'); ?></a>
            </nav>
            <nav class="floating-nav__side floating-nav__side--right" aria-label="<?php esc_attr_e('Page navigation right', 'alenumedia'); ?>">
                <a href="<?php echo esc_url(get_post_type_archive_link('works')); ?>"><?php esc_html_e('Works', 'alenumedia'); ?></a>
                <a href="<?php echo esc_url(home_url('/#quote')); ?>"><?php esc_html_e('Contact', 'alenumedia'); ?></a>
            </nav>
        </div>
    </header>
    <?php
    while (have_posts()) :
        the_post();
        ?>
        <article <?php post_class('content-panel reveal reveal--up'); ?>>
            <span class="eyebrow"><?php esc_html_e('Page', 'alenumedia'); ?></span>
            <h1><?php the_title(); ?></h1>
            <div class="content-panel__body"><?php the_content(); ?></div>
        </article>
    <?php endwhile; ?>
</main>
<?php
get_footer();
