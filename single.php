<?php
/**
 * Single blog post template.
 *
 * @package AlenuMedia
 */

if (! defined('ABSPATH')) {
    exit;
}

$blog_url = get_permalink((int) get_option('page_for_posts')) ?: home_url('/blog/');

get_header();
?>
<main class="site-main blog-single">
    <header class="floating-nav floating-nav--inner floating-nav--centered reveal reveal--up">
        <button type="button" class="floating-nav__toggle" aria-expanded="false">
            <span><?php esc_html_e('Menu', 'alenumedia'); ?></span>
        </button>
        <a class="brand-link" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Back to homepage', 'alenumedia'); ?>">
            <?php echo alenumedia_get_logo_markup(); ?>
        </a>
        <div class="floating-nav__menu">
            <nav class="floating-nav__side floating-nav__side--left" aria-label="<?php esc_attr_e('Article navigation left', 'alenumedia'); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'alenumedia'); ?></a>
                <a href="<?php echo esc_url($blog_url); ?>"><?php esc_html_e('Blog', 'alenumedia'); ?></a>
            </nav>
            <nav class="floating-nav__side floating-nav__side--right" aria-label="<?php esc_attr_e('Article navigation right', 'alenumedia'); ?>">
                <a href="<?php echo esc_url(get_post_type_archive_link('works')); ?>"><?php esc_html_e('Works', 'alenumedia'); ?></a>
                <a href="<?php echo esc_url(home_url('/#quote')); ?>"><?php esc_html_e('Contact', 'alenumedia'); ?></a>
            </nav>
        </div>
    </header>
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $categories   = get_the_category();
        $primary      = $categories[0]->name ?? __('Blog', 'alenumedia');
        $tags         = get_the_tags() ?: [];
        $prev_post    = get_previous_post();
        $next_post    = get_next_post();
        $word_count   = str_word_count(wp_strip_all_tags((string) get_the_content()));
        $read_minutes = max(1, (int) ceil($word_count / 220));
        ?>
        <article <?php post_class('blog-single__article'); ?>>
            <section class="blog-post-hero reveal reveal--up">
                <div class="blog-post-hero__inner">
                    <div class="blog-post-hero__copy">
                        <span class="eyebrow"><?php echo esc_html($primary); ?></span>
                        <div class="blog-post-hero__meta">
                            <span><?php echo esc_html(get_the_date('F j, Y')); ?></span>
                            <span><?php echo esc_html(sprintf(_n('%s min read', '%s min read', $read_minutes, 'alenumedia'), number_format_i18n($read_minutes))); ?></span>
                        </div>
                        <h1><?php the_title(); ?></h1>
                        <?php if (has_excerpt()) : ?>
                            <p class="blog-post-hero__summary"><?php echo esc_html(get_the_excerpt()); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="blog-post-hero__visual">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="blog-post-hero__media">
                                <?php
                                the_post_thumbnail(
                                    'large',
                                    [
                                        'loading'       => 'eager',
                                        'fetchpriority' => 'high',
                                        'decoding'      => 'async',
                                        'sizes'         => '(max-width: 960px) 100vw, 52vw',
                                    ]
                                );
                                ?>
                            </div>
                        <?php else : ?>
                            <div class="blog-post-hero__placeholder">
                                <span><?php echo esc_html($primary); ?></span>
                                <strong><?php the_title(); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <section class="blog-post-story">
                <div class="blog-post-story__inner">
                    <div class="blog-post-story__content reveal reveal--up">
                        <div class="blog-post-prose"><?php the_content(); ?></div>
                    </div>

                    <aside class="blog-post-story__sidebar reveal reveal--up">
                        <div class="blog-post-sidebar-card">
                            <span><?php esc_html_e('Article Snapshot', 'alenumedia'); ?></span>
                            <dl class="blog-post-sidebar-meta">
                                <div>
                                    <dt><?php esc_html_e('Category', 'alenumedia'); ?></dt>
                                    <dd><?php echo esc_html($primary); ?></dd>
                                </div>
                                <div>
                                    <dt><?php esc_html_e('Published', 'alenumedia'); ?></dt>
                                    <dd><?php echo esc_html(get_the_date('F j, Y')); ?></dd>
                                </div>
                                <div>
                                    <dt><?php esc_html_e('Updated', 'alenumedia'); ?></dt>
                                    <dd><?php echo esc_html(get_the_modified_date('F j, Y')); ?></dd>
                                </div>
                                <div>
                                    <dt><?php esc_html_e('Reading Time', 'alenumedia'); ?></dt>
                                    <dd><?php echo esc_html(sprintf(_n('%s min read', '%s min read', $read_minutes, 'alenumedia'), number_format_i18n($read_minutes))); ?></dd>
                                </div>
                            </dl>
                        </div>
                        <div class="blog-post-sidebar-card">
                            <span><?php esc_html_e('Need Help Implementing This?', 'alenumedia'); ?></span>
                            <p><?php esc_html_e('We turn strategy into high-performing digital systems, from SEO-focused content structures to custom websites and AI-supported user journeys.', 'alenumedia'); ?></p>
                            <a class="button button--solid" href="<?php echo esc_url(home_url('/#quote')); ?>"><?php esc_html_e('Start a Project', 'alenumedia'); ?></a>
                        </div>
                    </aside>
                </div>
            </section>

            <?php if (! empty($tags)) : ?>
                <section class="blog-post-tags">
                    <div class="blog-post-tags__inner reveal reveal--up">
                        <span><?php esc_html_e('Tags', 'alenumedia'); ?></span>
                        <div class="blog-post-tags__list">
                            <?php foreach ($tags as $tag) : ?>
                                <a href="<?php echo esc_url(get_tag_link($tag)); ?>"><?php echo esc_html($tag->name); ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($prev_post || $next_post) : ?>
                <nav class="blog-post-pagination reveal reveal--up" aria-label="<?php esc_attr_e('Article navigation', 'alenumedia'); ?>">
                    <?php if ($prev_post) : ?>
                        <a class="blog-post-pagination__item" href="<?php echo esc_url(get_permalink($prev_post)); ?>">
                            <span><?php esc_html_e('Previous Article', 'alenumedia'); ?></span>
                            <strong><?php echo esc_html(get_the_title($prev_post)); ?></strong>
                        </a>
                    <?php endif; ?>
                    <?php if ($next_post) : ?>
                        <a class="blog-post-pagination__item blog-post-pagination__item--next" href="<?php echo esc_url(get_permalink($next_post)); ?>">
                            <span><?php esc_html_e('Next Article', 'alenumedia'); ?></span>
                            <strong><?php echo esc_html(get_the_title($next_post)); ?></strong>
                        </a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        </article>
    <?php endwhile; ?>
</main>
<?php
get_footer();
