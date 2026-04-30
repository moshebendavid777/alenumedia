<?php
/**
 * Fallback template.
 *
 * @package AlenuMedia
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main class="site-main inner-main">
    <section class="section-shell section-grid">
        <div class="section-heading reveal reveal--up">
            <span class="eyebrow"><?php esc_html_e('Content', 'alenumedia'); ?></span>
            <h1><?php bloginfo('name'); ?></h1>
        </div>
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('content-panel reveal reveal--up'); ?>>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="content-panel__body"><?php the_excerpt(); ?></div>
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
    </section>
</main>
<?php
get_footer();
