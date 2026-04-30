<?php
/**
 * Single work template.
 *
 * @package AlenuMedia
 */

if (! defined('ABSPATH')) {
    exit;
}

$archive_url = get_post_type_archive_link('works') ?: home_url('/#works');
$home_url    = home_url('/');
$quote_url   = home_url('/#quote');

get_header();
?>
<main class="site-main work-single">
    <header class="floating-nav floating-nav--inner floating-nav--centered reveal reveal--up">
        <button type="button" class="floating-nav__toggle" aria-expanded="false">
            <span><?php esc_html_e('Menu', 'alenumedia'); ?></span>
        </button>
        <a class="brand-link" href="<?php echo esc_url($home_url); ?>" aria-label="<?php esc_attr_e('Back to homepage', 'alenumedia'); ?>">
            <?php echo alenumedia_get_logo_markup(); ?>
        </a>
        <div class="floating-nav__menu">
            <nav class="floating-nav__side floating-nav__side--left" aria-label="<?php esc_attr_e('Work page navigation left', 'alenumedia'); ?>">
                <a href="<?php echo esc_url($home_url); ?>"><?php esc_html_e('Home', 'alenumedia'); ?></a>
                <a href="<?php echo esc_url(home_url('/#works')); ?>"><?php esc_html_e('Selected Work', 'alenumedia'); ?></a>
            </nav>
            <nav class="floating-nav__side floating-nav__side--right" aria-label="<?php esc_attr_e('Work page navigation right', 'alenumedia'); ?>">
                <a href="<?php echo esc_url($archive_url); ?>"><?php esc_html_e('All Works', 'alenumedia'); ?></a>
                <a href="<?php echo esc_url($quote_url); ?>"><?php esc_html_e('Start a Project', 'alenumedia'); ?></a>
            </nav>
        </div>
    </header>
    <?php
    while (have_posts()) :
        the_post();

        $post_id      = get_the_ID();
        $case_study   = alenumedia_get_work_case_study_data($post_id);
        $content_html = apply_filters('the_content', get_the_content());
        $previous     = get_previous_post();
        $next         = get_next_post();
        $gallery      = $case_study['gallery'];
        ?>
        <article <?php post_class('work-single__article'); ?>>
            <section class="work-hero reveal reveal--up">
                <div class="work-hero__inner">
                    <div class="work-hero__copy">
                        <span class="eyebrow"><?php esc_html_e('Case Study', 'alenumedia'); ?></span>
                        <?php if (! empty($case_study['taxonomy'])) : ?>
                            <div class="work-hero__taxonomy">
                                <?php foreach ($case_study['taxonomy'] as $taxonomy_label) : ?>
                                    <span><?php echo esc_html($taxonomy_label); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($case_study['client']) : ?>
                            <p class="work-hero__client"><?php echo esc_html($case_study['client']); ?></p>
                        <?php endif; ?>
                        <h1><?php the_title(); ?></h1>
                        <?php if ($case_study['summary']) : ?>
                            <p class="work-hero__summary"><?php echo esc_html($case_study['summary']); ?></p>
                        <?php endif; ?>
                        <div class="hero-actions work-hero__actions">
                            <?php if ($case_study['website_url']) : ?>
                                <a class="button button--solid" href="<?php echo esc_url($case_study['website_url']); ?>" target="_blank" rel="noreferrer noopener"><?php esc_html_e('Visit Live Website', 'alenumedia'); ?></a>
                            <?php endif; ?>
                            <a class="button button--ghost" href="<?php echo esc_url($quote_url); ?>"><?php esc_html_e('Request a Similar Build', 'alenumedia'); ?></a>
                        </div>
                    </div>
                    <div class="work-hero__visual">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="work-hero__media">
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
                            <div class="work-hero__placeholder">
                                <span><?php esc_html_e('Featured Project', 'alenumedia'); ?></span>
                                <strong><?php the_title(); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (! empty($case_study['meta_cards'])) : ?>
                    <div class="work-hero__meta">
                        <?php foreach ($case_study['meta_cards'] as $meta_card) : ?>
                            <article class="work-stat-card reveal reveal--up">
                                <span><?php echo esc_html($meta_card['label']); ?></span>
                                <strong><?php echo esc_html($meta_card['value']); ?></strong>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <?php if (! empty($gallery)) : ?>
                <section class="work-gallery reveal reveal--up">
                    <div class="work-gallery__intro">
                        <span class="eyebrow"><?php esc_html_e('Visual Story', 'alenumedia'); ?></span>
                        <h2><?php esc_html_e('A cinematic look at the build.', 'alenumedia'); ?></h2>
                    </div>
                    <div class="work-gallery__grid">
                        <?php foreach ($gallery as $index => $image) : ?>
                            <figure class="work-gallery__item work-gallery__item--<?php echo esc_attr(($index % 5) + 1); ?> reveal reveal--up">
                                <?php
                                echo wp_get_attachment_image(
                                    $image['id'],
                                    'large',
                                    false,
                                    [
                                        'alt'      => $image['alt'],
                                        'loading'  => 'lazy',
                                        'decoding' => 'async',
                                        'sizes'    => '(max-width: 820px) 100vw, 50vw',
                                    ]
                                );
                                ?>
                                <?php if ($image['caption']) : ?>
                                    <figcaption><?php echo esc_html($image['caption']); ?></figcaption>
                                <?php endif; ?>
                            </figure>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <section class="work-overview">
                <div class="work-overview__inner">
                    <article class="work-detail-card work-detail-card--lead reveal reveal--up">
                        <span><?php esc_html_e('Project Overview', 'alenumedia'); ?></span>
                        <p><?php echo esc_html($case_study['summary']); ?></p>
                    </article>

                    <?php if (! empty($case_study['services'])) : ?>
                        <article class="work-detail-card reveal reveal--up">
                            <span><?php esc_html_e('Services', 'alenumedia'); ?></span>
                            <ul class="work-detail-list">
                                <?php foreach ($case_study['services'] as $service) : ?>
                                    <li><?php echo esc_html($service); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </article>
                    <?php endif; ?>

                    <?php if (! empty($case_study['deliverables'])) : ?>
                        <article class="work-detail-card reveal reveal--up">
                            <span><?php esc_html_e('Deliverables', 'alenumedia'); ?></span>
                            <ul class="work-detail-list">
                                <?php foreach ($case_study['deliverables'] as $deliverable) : ?>
                                    <li><?php echo esc_html($deliverable); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </article>
                    <?php endif; ?>

                    <?php if (! empty($case_study['results'])) : ?>
                        <article class="work-detail-card reveal reveal--up">
                            <span><?php esc_html_e('Highlights', 'alenumedia'); ?></span>
                            <ul class="work-detail-list">
                                <?php foreach ($case_study['results'] as $result) : ?>
                                    <li><?php echo esc_html($result); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </article>
                    <?php endif; ?>
                </div>
            </section>

            <section class="work-story">
                <div class="work-story__inner">
                    <div class="work-story__content reveal reveal--up">
                        <span class="eyebrow"><?php esc_html_e('Build Story', 'alenumedia'); ?></span>
                        <div class="work-prose"><?php echo $content_html; ?></div>
                    </div>

                    <aside class="work-story__sidebar reveal reveal--up">
                        <div class="work-sidebar-card">
                            <span><?php esc_html_e('Client Snapshot', 'alenumedia'); ?></span>
                            <dl class="work-sidebar-meta">
                                <?php if ($case_study['client']) : ?>
                                    <div>
                                        <dt><?php esc_html_e('Client', 'alenumedia'); ?></dt>
                                        <dd><?php echo esc_html($case_study['client']); ?></dd>
                                    </div>
                                <?php endif; ?>
                                <?php if ($case_study['industry']) : ?>
                                    <div>
                                        <dt><?php esc_html_e('Industry', 'alenumedia'); ?></dt>
                                        <dd><?php echo esc_html($case_study['industry']); ?></dd>
                                    </div>
                                <?php endif; ?>
                                <?php if ($case_study['timeline']) : ?>
                                    <div>
                                        <dt><?php esc_html_e('Timeline', 'alenumedia'); ?></dt>
                                        <dd><?php echo esc_html($case_study['timeline']); ?></dd>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <dt><?php esc_html_e('Reading Time', 'alenumedia'); ?></dt>
                                    <dd><?php echo esc_html(sprintf(_n('%s min read', '%s min read', $case_study['read_minutes'], 'alenumedia'), number_format_i18n($case_study['read_minutes']))); ?></dd>
                                </div>
                                <div>
                                    <dt><?php esc_html_e('Published', 'alenumedia'); ?></dt>
                                    <dd><?php echo esc_html(get_the_date('F j, Y')); ?></dd>
                                </div>
                            </dl>
                        </div>

                        <div class="work-sidebar-card">
                            <span><?php esc_html_e('Need Something Like This?', 'alenumedia'); ?></span>
                            <p><?php esc_html_e('We design and build polished project pages that help clients understand the value before the first call even starts.', 'alenumedia'); ?></p>
                            <a class="button button--solid" href="<?php echo esc_url($quote_url); ?>"><?php esc_html_e('Book Your Project', 'alenumedia'); ?></a>
                        </div>
                    </aside>
                </div>
            </section>

            <section class="work-cta">
                <div class="work-cta__inner reveal reveal--up">
                    <div>
                        <span class="eyebrow"><?php esc_html_e('Next Step', 'alenumedia'); ?></span>
                        <h2><?php esc_html_e('Ready to turn your next project into a standout case study?', 'alenumedia'); ?></h2>
                    </div>
                    <div class="work-cta__actions">
                        <a class="button button--solid" href="<?php echo esc_url($quote_url); ?>"><?php esc_html_e('Start Your Project', 'alenumedia'); ?></a>
                        <a class="button button--ghost" href="<?php echo esc_url($archive_url); ?>"><?php esc_html_e('Browse More Work', 'alenumedia'); ?></a>
                    </div>
                </div>
            </section>

            <?php if ($previous || $next) : ?>
                <nav class="work-pagination reveal reveal--up" aria-label="<?php esc_attr_e('Project navigation', 'alenumedia'); ?>">
                    <?php if ($previous) : ?>
                        <a class="work-pagination__item" href="<?php echo esc_url(get_permalink($previous)); ?>">
                            <span><?php esc_html_e('Previous Project', 'alenumedia'); ?></span>
                            <strong><?php echo esc_html(get_the_title($previous)); ?></strong>
                        </a>
                    <?php endif; ?>

                    <?php if ($next) : ?>
                        <a class="work-pagination__item work-pagination__item--next" href="<?php echo esc_url(get_permalink($next)); ?>">
                            <span><?php esc_html_e('Next Project', 'alenumedia'); ?></span>
                            <strong><?php echo esc_html(get_the_title($next)); ?></strong>
                        </a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        </article>
    <?php endwhile; ?>
</main>
<?php
get_footer();
