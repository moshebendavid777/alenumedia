<?php
/**
 * Blog archive template.
 *
 * @package AlenuMedia
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main class="site-main inner-main blog-archive">
    <header class="floating-nav floating-nav--inner floating-nav--centered reveal reveal--up">
        <button type="button" class="floating-nav__toggle" aria-expanded="false">
            <span><?php esc_html_e('Menu', 'alenumedia'); ?></span>
        </button>
        <a class="brand-link" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Back to homepage', 'alenumedia'); ?>">
            <?php echo alenumedia_get_logo_markup(); ?>
        </a>
        <div class="floating-nav__menu">
            <nav class="floating-nav__side floating-nav__side--left" aria-label="<?php esc_attr_e('Blog navigation left', 'alenumedia'); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'alenumedia'); ?></a>
                <a href="<?php echo esc_url(get_post_type_archive_link('works')); ?>"><?php esc_html_e('Works', 'alenumedia'); ?></a>
            </nav>
            <nav class="floating-nav__side floating-nav__side--right" aria-label="<?php esc_attr_e('Blog navigation right', 'alenumedia'); ?>">
                <a href="<?php echo esc_url(home_url('/#pricing')); ?>"><?php esc_html_e('Services', 'alenumedia'); ?></a>
                <a href="<?php echo esc_url(home_url('/#quote')); ?>"><?php esc_html_e('Contact', 'alenumedia'); ?></a>
            </nav>
        </div>
    </header>

    <section class="section-shell section-grid blog-shell">
        <div class="blog-hero reveal reveal--up">
            <div class="blog-hero__copy">
                <span class="eyebrow"><?php esc_html_e('Insights', 'alenumedia'); ?></span>
                <h1><?php esc_html_e('Strategic articles for search visibility and real growth.', 'alenumedia'); ?></h1>
                <p><?php esc_html_e('A cinematic archive of ideas on SEO, websites, mobile products, AI systems, and the decisions that shape stronger digital performance.', 'alenumedia'); ?></p>
            </div>
            <div class="blog-hero__panel">
                <span><?php esc_html_e('Editorial Index', 'alenumedia'); ?></span>
                <strong><?php echo esc_html(number_format_i18n((int) wp_count_posts('post')->publish)); ?></strong>
                <p><?php esc_html_e('Articles designed to rank, educate, and move the right audience closer to action.', 'alenumedia'); ?></p>
            </div>
        </div>

        <div class="blog-browser" data-blog-browser>
            <div class="blog-grid" data-blog-grid>
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <?php echo wp_kses_post(alenumedia_get_blog_post_card_markup(get_the_ID())); ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <article class="blog-card blog-card--empty reveal reveal--up">
                        <div class="blog-card__body">
                            <span><?php esc_html_e('Blog', 'alenumedia'); ?></span>
                            <h2><?php esc_html_e('Publish articles to build out your archive.', 'alenumedia'); ?></h2>
                            <p><?php esc_html_e('New posts will appear here automatically with the creative archive layout and load-more flow.', 'alenumedia'); ?></p>
                        </div>
                    </article>
                <?php endif; ?>
            </div>

            <?php if ((int) $wp_query->max_num_pages > 1) : ?>
                <div class="blog-load-more reveal reveal--up">
                    <button type="button" class="button button--solid" data-blog-load-more data-next-page="2">
                        <?php esc_html_e('Load More Articles', 'alenumedia'); ?>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php
get_footer();
