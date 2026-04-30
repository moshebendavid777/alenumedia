<?php
/**
 * Front page template.
 *
 * @package AlenuMedia
 */

if (! defined('ABSPATH')) {
    exit;
}

$home      = alenumedia_get_home_options();
$about_image_url = $home['about_image_url'] ?: 'http://alenumedia.local/wp-content/uploads/2026/04/7cb5a9e7-9677-4b61-9178-21437f597712.png';
$ai_bot_image_url = get_template_directory_uri() . '/assets/images/ai-bot.png';
$metrics   = [
    [
        'label'  => $home['hero_metric_1_label'],
        'value'  => $home['hero_metric_1_value'],
        'change' => $home['hero_metric_1_change'],
    ],
    [
        'label'  => $home['hero_metric_2_label'],
        'value'  => $home['hero_metric_2_value'],
        'change' => $home['hero_metric_2_change'],
    ],
    [
        'label'  => $home['hero_metric_3_label'],
        'value'  => $home['hero_metric_3_value'],
        'change' => $home['hero_metric_3_change'],
    ],
];
$steps        = [
    ['title' => $home['how_step_1_title'], 'text' => $home['how_step_1_text']],
    ['title' => $home['how_step_2_title'], 'text' => $home['how_step_2_text']],
    ['title' => $home['how_step_3_title'], 'text' => $home['how_step_3_text']],
];
$plans        = [
    ['name' => $home['pricing_plan_1_name'], 'price' => $home['pricing_plan_1_price'], 'features' => explode("\n", $home['pricing_plan_1_features'])],
    ['name' => $home['pricing_plan_2_name'], 'price' => $home['pricing_plan_2_price'], 'features' => explode("\n", $home['pricing_plan_2_features'])],
    ['name' => $home['pricing_plan_3_name'], 'price' => $home['pricing_plan_3_price'], 'features' => explode("\n", $home['pricing_plan_3_features'])],
];
$testimonials = [
    ['quote' => $home['testimonial_1_quote'], 'name' => $home['testimonial_1_name'], 'role' => $home['testimonial_1_role']],
    ['quote' => $home['testimonial_2_quote'], 'name' => $home['testimonial_2_name'], 'role' => $home['testimonial_2_role']],
    ['quote' => $home['testimonial_3_quote'], 'name' => $home['testimonial_3_name'], 'role' => $home['testimonial_3_role']],
];
$faqs         = [
    ['q' => $home['faq_1_question'], 'a' => $home['faq_1_answer']],
    ['q' => $home['faq_2_question'], 'a' => $home['faq_2_answer']],
    ['q' => $home['faq_3_question'], 'a' => $home['faq_3_answer']],
    ['q' => $home['faq_4_question'], 'a' => $home['faq_4_answer']],
];
$mobile_ios_image_url = $home['mobile_ios_image_url'] ?? '';
$mobile_android_image_url = $home['mobile_android_image_url'] ?? '';
$has_works_posts = (bool) wp_count_posts('works')->publish;
$about_stats  = [
    ['label' => $home['about_stat_1_label'], 'value' => $home['about_stat_1_value']],
    ['label' => $home['about_stat_2_label'], 'value' => $home['about_stat_2_value']],
    ['label' => $home['about_stat_3_label'], 'value' => $home['about_stat_3_value']],
];
$about_paragraphs = array_values(
    array_filter(
        array_map(
            'trim',
            preg_split("/\n\s*\n/", trim($home['about_body'])) ?: []
        )
    )
);
$ai_services  = [
    ['title' => $home['ai_service_1_title'], 'text' => $home['ai_service_1_text']],
    ['title' => $home['ai_service_2_title'], 'text' => $home['ai_service_2_text']],
    ['title' => $home['ai_service_3_title'], 'text' => $home['ai_service_3_text']],
    ['title' => $home['ai_service_4_title'], 'text' => $home['ai_service_4_text']],
];
$nav_views    = [
    'home'         => __('Home', 'alenumedia'),
    'about'        => __('About Us', 'alenumedia'),
    'ai'           => __('AI Integrations', 'alenumedia'),
    'works'        => __('Works', 'alenumedia'),
    'pricing'      => __('Websites', 'alenumedia'),
    'faq'          => __('Mobile Apps', 'alenumedia'),
    'quote'        => __('Contact Us', 'alenumedia'),
];
$nav_left     = array_values(array_filter(['home', 'about', 'ai', $has_works_posts ? 'works' : null]));
$nav_right    = ['pricing', 'faq', 'quote'];
$current_view = function_exists('alenumedia_get_current_spa_view') ? alenumedia_get_current_spa_view() : 'home';

$hero_primary_view   = '#' === substr($home['hero_primary_url'], 0, 1) ? ltrim($home['hero_primary_url'], '#') : '';
$hero_secondary_view = '#' === substr($home['hero_secondary_url'], 0, 1) ? ltrim($home['hero_secondary_url'], '#') : '';

get_header();
?>
<main class="site-main home-main home-main--app" data-spa-root>
    <header class="floating-nav reveal reveal--up">
        <button type="button" class="floating-nav__toggle" aria-expanded="false">
            <span><?php esc_html_e('Menu', 'alenumedia'); ?></span>
        </button>
        <a class="brand-link" href="<?php echo esc_url(alenumedia_get_spa_view_url('home')); ?>" data-spa-trigger="home" aria-label="<?php esc_attr_e('Go to home view', 'alenumedia'); ?>">
            <?php echo alenumedia_get_logo_markup($home['logo_text']); ?>
        </a>
        <div class="floating-nav__menu">
            <nav class="floating-nav__side floating-nav__side--left" aria-label="<?php esc_attr_e('Primary experience navigation left', 'alenumedia'); ?>">
                <?php foreach ($nav_left as $view_slug) : ?>
                    <a href="<?php echo esc_url(alenumedia_get_spa_view_url($view_slug)); ?>" data-spa-trigger="<?php echo esc_attr($view_slug); ?>">
                        <?php echo esc_html($nav_views[$view_slug]); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <nav class="floating-nav__side floating-nav__side--right" aria-label="<?php esc_attr_e('Primary experience navigation right', 'alenumedia'); ?>">
                <?php foreach ($nav_right as $view_slug) : ?>
                    <a
                        href="<?php echo esc_url(alenumedia_get_spa_view_url($view_slug)); ?>"
                        data-spa-trigger="<?php echo esc_attr($view_slug); ?>"
                        class="<?php echo 'quote' === $view_slug ? 'button button--ghost floating-nav__contact' : ''; ?>"
                    >
                        <?php echo esc_html($nav_views[$view_slug]); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </header>

    <section class="spa-app section-shell reveal reveal--up">
        <div class="spa-stage spa-stage--fullscreen" data-spa-stage data-spa-default="<?php echo esc_attr($current_view); ?>">
            <section class="spa-panel spa-panel--home section-grid <?php echo 'home' === $current_view ? 'is-active' : ''; ?>" data-spa-view="home" aria-hidden="<?php echo 'home' === $current_view ? 'false' : 'true'; ?>" <?php echo 'home' === $current_view ? '' : 'hidden'; ?>>
                <div class="hero-section hero-section--panel">
                    <div class="hero-copy">
                        <span class="eyebrow reveal reveal--up"><?php echo esc_html($home['hero_badge']); ?></span>
                        <h1 class="hero-title reveal reveal--up" data-hero-title>
                            <span class="hero-title__line"><span class="hero-title__text"><?php echo esc_html($home['hero_title_line_1']); ?></span></span>
                            <span class="hero-title__line is-accent"><span class="hero-title__text"><?php echo esc_html($home['hero_title_line_2']); ?></span></span>
                            <span class="hero-title__line"><span class="hero-title__text"><?php echo esc_html($home['hero_title_line_3']); ?></span></span>
                        </h1>
                        <p class="hero-description reveal reveal--up"><?php echo esc_html($home['hero_description']); ?></p>
                        <div class="hero-actions reveal reveal--up">
                            <?php if ($hero_primary_view) : ?>
                                <a class="button button--solid" href="<?php echo esc_url(alenumedia_get_spa_view_url($hero_primary_view)); ?>" data-spa-trigger="<?php echo esc_attr($hero_primary_view); ?>"><?php echo esc_html($home['hero_primary_label']); ?></a>
                            <?php else : ?>
                                <a class="button button--solid" href="<?php echo esc_url($home['hero_primary_url']); ?>"><?php echo esc_html($home['hero_primary_label']); ?></a>
                            <?php endif; ?>

                            <?php if ($has_works_posts) : ?>
                                <?php if ($hero_secondary_view) : ?>
                                    <a class="button button--outline" href="<?php echo esc_url(alenumedia_get_spa_view_url($hero_secondary_view)); ?>" data-spa-trigger="<?php echo esc_attr($hero_secondary_view); ?>"><?php echo esc_html($home['hero_secondary_label']); ?></a>
                                <?php else : ?>
                                    <a class="button button--outline" href="<?php echo esc_url($home['hero_secondary_url']); ?>"><?php echo esc_html($home['hero_secondary_label']); ?></a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="hero-device reveal reveal--scale" data-parallax>
                        <div class="hero-device__halo"></div>
                        <div class="hero-device__frame">
                            <div class="hero-device__screen">
                                <?php if ($home['hero_media_url']) : ?>
                                    <?php
                                    echo alenumedia_get_attachment_image_from_url(
                                        $home['hero_media_url'],
                                        'alenumedia-hero-device',
                                        [
                                            'class'         => 'hero-device__image',
                                            'loading'       => 'eager',
                                            'fetchpriority' => 'high',
                                            'decoding'      => 'async',
                                            'sizes'         => '(max-width: 820px) 72vw, 390px',
                                        ],
                                        $home['hero_mockup_title']
                                    );
                                    ?>
                                <?php else : ?>
                                    <div class="hero-device__fallback">
                                        <span><?php esc_html_e('iPhone Preview', 'alenumedia'); ?></span>
                                        <strong><?php esc_html_e('Add Your Screen Image', 'alenumedia'); ?></strong>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="spa-panel section-grid about-section <?php echo 'about' === $current_view ? 'is-active' : ''; ?>" data-spa-view="about" aria-hidden="<?php echo 'about' === $current_view ? 'false' : 'true'; ?>" <?php echo 'about' === $current_view ? '' : 'hidden'; ?>>
                <div class="about-layout">
                    <div class="about-portrait reveal reveal--scale">
                        <?php if ($about_image_url) : ?>
                            <?php
                            echo alenumedia_get_attachment_image_from_url(
                                $about_image_url,
                                'alenumedia-portrait',
                                [
                                    'loading'  => 'lazy',
                                    'decoding' => 'async',
                                    'sizes'    => '(max-width: 820px) 100vw, 40vw',
                                ],
                                __('Team portrait', 'alenumedia')
                            );
                            ?>
                        <?php else : ?>
                            <div class="about-portrait__placeholder">
                                <span><?php esc_html_e('Your Portrait', 'alenumedia'); ?></span>
                                <strong><?php esc_html_e('Replace This With Your Photo', 'alenumedia'); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="about-copy">
                        <div class="section-heading reveal reveal--up">
                            <span class="eyebrow"><?php esc_html_e('About Us', 'alenumedia'); ?></span>
                            <h2><?php echo esc_html($home['about_title']); ?></h2>
                            <p><?php echo esc_html($home['about_intro']); ?></p>
                        </div>
                        <div class="about-copy__body reveal reveal--up">
                            <?php foreach ($about_paragraphs as $about_paragraph) : ?>
                                <p><?php echo esc_html($about_paragraph); ?></p>
                            <?php endforeach; ?>
                        </div>
                        <div class="about-stats">
                            <?php foreach ($about_stats as $index => $stat) : ?>
                                <article class="about-stat reveal reveal--up" style="--delay: <?php echo esc_attr($index * 0.08); ?>s;">
                                    <strong><?php echo esc_html($stat['value']); ?></strong>
                                    <span><?php echo esc_html($stat['label']); ?></span>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <div class="panel-actions reveal reveal--up">
                            <a class="button button--solid" href="<?php echo esc_url(alenumedia_get_spa_view_url('quote')); ?>" data-spa-trigger="quote"><?php esc_html_e('Work With Us', 'alenumedia'); ?></a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="spa-panel section-grid ai-section <?php echo 'ai' === $current_view ? 'is-active' : ''; ?>" data-spa-view="ai" aria-hidden="<?php echo 'ai' === $current_view ? 'false' : 'true'; ?>" <?php echo 'ai' === $current_view ? '' : 'hidden'; ?>>
                <div class="ai-layout">
                    <div class="ai-layout__copy">
                        <div class="section-heading reveal reveal--up">
                            <span class="eyebrow"><?php esc_html_e('AI Services', 'alenumedia'); ?></span>
                            <h2><?php echo esc_html($home['ai_title']); ?></h2>
                            <p><?php echo esc_html($home['ai_intro']); ?></p>
                        </div>
                        <div class="ai-grid">
                            <?php foreach ($ai_services as $index => $service) : ?>
                                <article class="ai-card reveal reveal--up" style="--delay: <?php echo esc_attr($index * 0.08); ?>s;">
                                    <span class="ai-card__index"><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span>
                                    <h3><?php echo esc_html($service['title']); ?></h3>
                                    <p><?php echo esc_html($service['text']); ?></p>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <div class="panel-actions reveal reveal--up">
                            <a class="button button--outline" href="<?php echo esc_url(alenumedia_get_spa_view_url('quote')); ?>" data-spa-trigger="quote"><?php esc_html_e('Request AI Buildout', 'alenumedia'); ?></a>
                        </div>
                    </div>
                    <div class="ai-drone reveal reveal--scale">
                        <div class="ai-drone__shell">
                            <div class="ai-drone__glow"></div>
                            <img class="ai-drone__bot" src="<?php echo esc_url($ai_bot_image_url); ?>" alt="<?php esc_attr_e('Floating AI bot illustration', 'alenumedia'); ?>">
                        </div>
                    </div>
                </div>
            </section>

            <section class="spa-panel section-grid works-section <?php echo 'works' === $current_view ? 'is-active' : ''; ?>" data-spa-view="works" aria-hidden="<?php echo 'works' === $current_view ? 'false' : 'true'; ?>" <?php echo 'works' === $current_view ? '' : 'hidden'; ?>>
                <div class="section-heading reveal reveal--up">
                    <span class="eyebrow"><?php esc_html_e('Portfolio', 'alenumedia'); ?></span>
                    <h2><?php echo esc_html($home['works_title']); ?></h2>
                    <p><?php echo esc_html($home['works_intro']); ?></p>
                </div>
                <?php echo wp_kses_post(alenumedia_render_work_browser('front-page')); ?>
            </section>

            <section class="spa-panel section-grid <?php echo 'pricing' === $current_view ? 'is-active' : ''; ?>" data-spa-view="pricing" aria-hidden="<?php echo 'pricing' === $current_view ? 'false' : 'true'; ?>" <?php echo 'pricing' === $current_view ? '' : 'hidden'; ?>>
                <div class="section-heading reveal reveal--up">
                    <span class="eyebrow"><?php esc_html_e('Web Development', 'alenumedia'); ?></span>
                    <h2><?php echo esc_html($home['pricing_title']); ?></h2>
                    <p><?php echo esc_html($home['pricing_intro']); ?></p>
                </div>
                <div class="pricing-grid">
                    <?php foreach ($plans as $index => $plan) : ?>
                        <article class="pricing-card reveal reveal--up <?php echo 1 === $index ? 'is-featured' : ''; ?>">
                            <span><?php echo esc_html($plan['name']); ?></span>
                            <strong><?php echo esc_html($plan['price']); ?></strong>
                            <ul>
                                <?php foreach ($plan['features'] as $feature) : ?>
                                    <?php if (trim($feature)) : ?>
                                        <li><?php echo esc_html(trim($feature)); ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </article>
                    <?php endforeach; ?>
                </div>
                <div class="panel-actions reveal reveal--up">
                    <a class="button button--solid" href="<?php echo esc_url(alenumedia_get_spa_view_url('quote')); ?>" data-spa-trigger="quote"><?php esc_html_e('Discuss Your Project', 'alenumedia'); ?></a>
                </div>
            </section>

            <section class="spa-panel section-grid <?php echo 'testimonials' === $current_view ? 'is-active' : ''; ?>" data-spa-view="testimonials" aria-hidden="<?php echo 'testimonials' === $current_view ? 'false' : 'true'; ?>" <?php echo 'testimonials' === $current_view ? '' : 'hidden'; ?>>
                <div class="section-heading reveal reveal--up">
                    <span class="eyebrow"><?php esc_html_e('Testimonials', 'alenumedia'); ?></span>
                    <h2><?php echo esc_html($home['testimonials_title']); ?></h2>
                </div>
                <div class="testimonial-grid">
                    <?php foreach ($testimonials as $index => $item) : ?>
                        <article class="testimonial-card reveal reveal--up" style="--delay: <?php echo esc_attr($index * 0.08); ?>s;">
                            <p>"<?php echo esc_html($item['quote']); ?>"</p>
                            <strong><?php echo esc_html($item['name']); ?></strong>
                            <span><?php echo esc_html($item['role']); ?></span>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="spa-panel section-grid <?php echo 'faq' === $current_view ? 'is-active' : ''; ?>" data-spa-view="faq" aria-hidden="<?php echo 'faq' === $current_view ? 'false' : 'true'; ?>" <?php echo 'faq' === $current_view ? '' : 'hidden'; ?>>
                <div class="mobile-services">
                    <div class="mobile-services__copy">
                        <div class="section-heading reveal reveal--up">
                            <span class="eyebrow"><?php esc_html_e('Mobile Development', 'alenumedia'); ?></span>
                            <h2><?php echo esc_html($home['faq_title']); ?></h2>
                            <p><?php esc_html_e('We build mobile products that feel premium on both platforms, with dedicated design thinking for iPhone and Android experiences.', 'alenumedia'); ?></p>
                        </div>
                        <div class="mobile-services__grid">
                            <?php foreach ($faqs as $index => $faq) : ?>
                                <article class="mobile-service-card reveal reveal--up" style="--delay: <?php echo esc_attr($index * 0.08); ?>s;">
                                    <span><?php echo esc_html($faq['q']); ?></span>
                                    <p><?php echo esc_html($faq['a']); ?></p>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mobile-devices reveal reveal--scale">
                        <article class="mobile-phone-card mobile-phone-card--ios">
                            <canvas class="mobile-phone-card__fx" data-device-canvas="ios"></canvas>
                            <div class="mobile-phone mobile-phone--ios">
                                <div class="mobile-phone__screen">
                                    <?php if ($mobile_ios_image_url) : ?>
                                        <?php
                                        echo alenumedia_get_attachment_image_from_url(
                                            $mobile_ios_image_url,
                                            'alenumedia-mobile-screen',
                                            [
                                                'loading'  => 'lazy',
                                                'decoding' => 'async',
                                                'sizes'    => '(max-width: 820px) 82vw, 360px',
                                            ],
                                            __('iPhone app preview', 'alenumedia')
                                        );
                                        ?>
                                    <?php else : ?>
                                        <div class="mobile-phone__fallback">
                                            <span><?php esc_html_e('iPhone', 'alenumedia'); ?></span>
                                            <strong><?php esc_html_e('Premium iOS UX', 'alenumedia'); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                        <article class="mobile-phone-card mobile-phone-card--android">
                            <canvas class="mobile-phone-card__fx" data-device-canvas="android"></canvas>
                            <div class="mobile-phone mobile-phone--android">
                                <div class="mobile-phone__screen">
                                    <?php if ($mobile_android_image_url) : ?>
                                        <?php
                                        echo alenumedia_get_attachment_image_from_url(
                                            $mobile_android_image_url,
                                            'alenumedia-mobile-screen',
                                            [
                                                'loading'  => 'lazy',
                                                'decoding' => 'async',
                                                'sizes'    => '(max-width: 820px) 82vw, 360px',
                                            ],
                                            __('Android app preview', 'alenumedia')
                                        );
                                        ?>
                                    <?php else : ?>
                                        <div class="mobile-phone__fallback">
                                            <span><?php esc_html_e('Galaxy', 'alenumedia'); ?></span>
                                            <strong><?php esc_html_e('Android System Build', 'alenumedia'); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section class="spa-panel section-grid quote-view <?php echo 'quote' === $current_view ? 'is-active' : ''; ?>" data-spa-view="quote" aria-hidden="<?php echo 'quote' === $current_view ? 'false' : 'true'; ?>" <?php echo 'quote' === $current_view ? '' : 'hidden'; ?>>
                <div class="quote-shell quote-shell--in-panel">
                    <div class="quote-panel reveal reveal--up">
                        <span class="eyebrow"><?php esc_html_e('Get Started', 'alenumedia'); ?></span>
                        <h2><?php echo esc_html($home['quote_title']); ?></h2>
                        <p><?php echo esc_html($home['quote_intro']); ?></p>
                        <div class="quote-panel__orbs" aria-hidden="true">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <form class="quote-form reveal reveal--scale" data-quote-form>
                        <input type="text" name="website" class="quote-form__honeypot" tabindex="-1" autocomplete="off" />
                        <label>
                            <span><?php esc_html_e('Name', 'alenumedia'); ?></span>
                            <input type="text" name="name" required>
                        </label>
                        <label>
                            <span><?php esc_html_e('Email', 'alenumedia'); ?></span>
                            <input type="email" name="email" required>
                        </label>
                        <label>
                            <span><?php esc_html_e('Phone', 'alenumedia'); ?></span>
                            <input type="text" name="phone">
                        </label>
                        <label>
                            <span><?php esc_html_e('Company', 'alenumedia'); ?></span>
                            <input type="text" name="company">
                        </label>
                        <label>
                            <span><?php esc_html_e('Project Type', 'alenumedia'); ?></span>
                            <select name="project_type">
                                <option value="Website"><?php esc_html_e('Website', 'alenumedia'); ?></option>
                                <option value="Landing Page"><?php esc_html_e('Landing Page', 'alenumedia'); ?></option>
                                <option value="Brand Refresh"><?php esc_html_e('Brand Refresh', 'alenumedia'); ?></option>
                                <option value="AI Integration"><?php esc_html_e('AI Integration', 'alenumedia'); ?></option>
                                <option value="Funnel"><?php esc_html_e('Funnel', 'alenumedia'); ?></option>
                            </select>
                        </label>
                        <label>
                            <span><?php esc_html_e('Budget', 'alenumedia'); ?></span>
                            <select name="budget">
                                <option value="$15k-$20k">$15K-$20k</option>
                                <option value="$25k-$30k">$25k-$30k</option>
                                <option value="$35k+">$35k+</option>
                            </select>
                        </label>
                        <label class="quote-form__full">
                            <span><?php esc_html_e('Tell us about the project', 'alenumedia'); ?></span>
                            <textarea name="message" rows="5" required></textarea>
                        </label>
                        <div class="quote-form__footer">
                            <button type="submit" class="button button--solid"><?php esc_html_e('Launch Request', 'alenumedia'); ?></button>
                            <p class="quote-form__status" data-form-status><?php esc_html_e('Usually replies within one business day.', 'alenumedia'); ?></p>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </section>
</main>
<?php
get_footer();
