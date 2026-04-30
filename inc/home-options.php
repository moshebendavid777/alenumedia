<?php
/**
 * Homepage options UI.
 *
 * @package AlenuMedia
 */

if (! defined('ABSPATH')) {
    exit;
}

function alenumedia_get_home_defaults(): array
{
    return [
        'logo_text'                  => 'AL',
        'favicon_url'                => '',
        'hero_badge'                 => 'Future-ready digital systems',
        'hero_title_line_1'          => 'REAL PERFORMANCE',
        'hero_title_line_2'          => 'ENGINEERED',
        'hero_title_line_3'          => 'FOR GROWTH',
        'hero_description'           => 'We design and build high-impact websites, mobile applications, and AI-powered solutions, transforming ideas into scalable, conversion-focused digital products for ambitious brands.',
        'hero_primary_label'         => 'Request A Quote',
        'hero_primary_url'           => '#quote',
        'hero_secondary_label'       => 'Explore Works',
        'hero_secondary_url'         => '#works',
        'hero_mockup_title'          => 'Orbit Campaign',
        'hero_mockup_subtitle'       => 'Live performance pulse',
        'hero_metric_1_label'        => 'Engagement Lift',
        'hero_metric_1_value'        => '142%',
        'hero_metric_1_change'       => '+32%',
        'hero_metric_2_label'        => 'Qualified Leads',
        'hero_metric_2_value'        => '350+',
        'hero_metric_2_change'       => '+88',
        'hero_metric_3_label'        => 'Launch Speed',
        'hero_metric_3_value'        => '9 days',
        'hero_metric_3_change'       => '-41%',
        'hero_media_url'             => '',
        'how_title'                  => 'How We Build Momentum',
        'how_intro'                  => 'A compact process for turning brand direction into an immersive digital machine.',
        'how_step_1_title'           => 'Signal Mapping',
        'how_step_1_text'            => 'We align your positioning, audience, offer, and funnel into one sharp narrative.',
        'how_step_2_title'           => 'Experience Design',
        'how_step_2_text'            => 'Interfaces, motion systems, and conversion surfaces are crafted into a futuristic branded world.',
        'how_step_3_title'           => 'Launch Velocity',
        'how_step_3_text'            => 'We deploy fast, optimize the lead path, and give your team a system that is easy to update.',
        'works_title'                => 'Selected Works',
        'works_intro'                => 'Case studies, launches, and digital products built for brands that want something beyond the usual template look.',
        'about_title'                => 'About Us',
        'about_intro'                => 'We design future-facing websites, systems, and launch experiences for founders and brands that refuse to look like everyone else.',
        'about_body'                 => "Our work blends elevated visual direction, conversion-driven structure, and immersive motion, creating digital experiences that feel cinematic, yet guide users with precision toward action.\n\nLed by Moshe Ben-David, every project is built with a clear purpose: to turn bold ideas into high-performing, tangible realities.",
        'about_image_url'            => 'http://alenumedia.local/wp-content/uploads/2026/04/7cb5a9e7-9677-4b61-9178-21437f597712.png',
        'about_stat_1_label'         => 'Projects Launched',
        'about_stat_1_value'         => '40+',
        'about_stat_2_label'         => 'Build Velocity',
        'about_stat_2_value'         => 'Fast',
        'about_stat_3_label'         => 'Design Language',
        'about_stat_3_value'         => 'Futuristic',
        'ai_title'                   => 'AI Integrations',
        'ai_intro'                   => 'AI-powered layers that make the website smarter, faster, and more helpful without making the experience feel robotic.',
        'ai_service_1_title'         => 'AI Quote Qualification',
        'ai_service_1_text'          => 'Guide visitors through project scoping and deliver cleaner lead data into your quote flow.',
        'ai_service_2_title'         => 'Conversational Site Assistants',
        'ai_service_2_text'          => 'On-brand assistants that answer questions, route users, and surface relevant offers or case studies.',
        'ai_service_3_title'         => 'Automation Workflows',
        'ai_service_3_text'          => 'Trigger email, CRM, summaries, and internal follow-up actions automatically after key interactions.',
        'ai_service_4_title'         => 'Content Intelligence',
        'ai_service_4_text'          => 'Generate drafts, restructure messaging, and personalize blocks of content for different visitor intent.',
        'pricing_title'              => 'Custom Web Development Built Around Your Needs',
        'pricing_intro'              => 'We build custom websites from scratch with unique design, the right platform and language for the job, and the integrations your business actually needs.',
        'pricing_plan_1_name'        => 'Custom Web Development',
        'pricing_plan_1_price'       => 'From Scratch',
        'pricing_plan_1_features'    => "Custom web development from scratch\nUnique design tailored to your brand\nPlatform and architecture chosen for your needs\nClean, scalable implementation",
        'pricing_plan_2_name'        => 'Platforms & Frameworks',
        'pricing_plan_2_price'       => 'WordPress • Laravel • React',
        'pricing_plan_2_features'    => "WordPress builds and custom themes\nLaravel applications and custom backend systems\nReact interfaces and interactive frontends\nDifferent stacks depending on project needs",
        'pricing_plan_3_name'        => 'Plugins, APIs & Custom Solutions',
        'pricing_plan_3_price'       => 'Tailored Integrations',
        'pricing_plan_3_features'    => "Custom plugins and business-specific solutions\nAPI integrations and external service connections\nAutomation and advanced feature implementation\nEverything needed to support your workflow",
        'testimonials_title'         => 'What Clients Feel',
        'testimonial_1_quote'        => 'The site finally looks like the future-facing brand we knew we were building.',
        'testimonial_1_name'         => 'Maya Chen',
        'testimonial_1_role'         => 'Founder, Volt House',
        'testimonial_2_quote'        => 'Fast process, huge visual lift, and the admin side is actually enjoyable to update.',
        'testimonial_2_name'         => 'Andre Silva',
        'testimonial_2_role'         => 'Creative Director, Frame Shift',
        'testimonial_3_quote'        => 'The motion feels premium, but the conversion flow stays clear and useful.',
        'testimonial_3_name'         => 'Leah Morgan',
        'testimonial_3_role'         => 'Marketing Lead, Ember Labs',
        'faq_title'                  => 'Mobile App Development Expertise',
        'faq_1_question'             => 'iOS App Development',
        'faq_1_answer'               => 'We design and build custom iOS applications with polished user experiences, strong performance, and features tailored to your business goals.',
        'faq_2_question'             => 'Android App Development',
        'faq_2_answer'               => 'We develop Android apps that are scalable, user-friendly, and built to work reliably across a wide range of devices and screen sizes.',
        'faq_3_question'             => 'Cross-Platform Planning',
        'faq_3_answer'               => 'When your product needs to serve both platforms, we help define the right architecture, feature set, and rollout plan for iOS and Android.',
        'faq_4_question'             => 'Custom Features & Integrations',
        'faq_4_answer'               => 'We can connect mobile apps to APIs, business tools, custom backends, and the other systems needed to support real-world product workflows.',
        'mobile_ios_image_url'       => '',
        'mobile_android_image_url'   => '',
        'quote_title'                => 'Request A Quote',
        'quote_intro'                => 'Tell us what you want to launch and we will map out a sharp, futuristic web experience around it.',
        'recipient_email'            => 'moshebendavid84@gmail.com',
    ];
}

function alenumedia_maybe_upgrade_about_copy(): void
{
    $options = get_option('alenumedia_home_options', []);

    if (! is_array($options)) {
        return;
    }

    $legacy_intro = 'We design future-facing websites, systems, and launch experiences for founders and brands that want to feel unmistakably ahead.';
    $legacy_body  = 'Our approach blends premium visual direction, conversion-minded structure, and immersive motion. The result is a site that feels cinematic, but still moves users clearly toward the next action.';
    $updated      = false;

    if (empty($options['about_intro']) || $legacy_intro === $options['about_intro']) {
        $options['about_intro'] = 'We design future-facing websites, systems, and launch experiences for founders and brands that refuse to look like everyone else.';
        $updated                = true;
    }

    if (empty($options['about_body']) || $legacy_body === $options['about_body']) {
        $options['about_body'] = "Our work blends elevated visual direction, conversion-driven structure, and immersive motion, creating digital experiences that feel cinematic, yet guide users with precision toward action.\n\nLed by Moshe Ben-David, every project is built with a clear purpose: to turn bold ideas into high-performing, tangible realities.";
        $updated               = true;
    }

    if ($updated) {
        update_option('alenumedia_home_options', $options);
    }
}
add_action('init', 'alenumedia_maybe_upgrade_about_copy');

function alenumedia_maybe_upgrade_hero_copy(): void
{
    $options = get_option('alenumedia_home_options', []);

    if (! is_array($options)) {
        return;
    }

    $legacy_values = [
        'hero_badge'        => 'Future-ready growth systems',
        'hero_title_line_1' => 'REAL SIGNALS',
        'hero_title_line_2' => 'BLUEPRINTED',
        'hero_title_line_3' => 'FOR IMPACT',
        'hero_description'  => 'We build bold digital experiences, turn attention into trust, and give ambitious brands a futuristic website that converts like a sales machine.',
    ];
    $updated_values = [
        'hero_badge'        => 'Future-ready digital systems',
        'hero_title_line_1' => 'REAL PERFORMANCE',
        'hero_title_line_2' => 'ENGINEERED',
        'hero_title_line_3' => 'FOR GROWTH',
        'hero_description'  => 'We design and build high-impact websites, mobile applications, and AI-powered solutions, transforming ideas into scalable, conversion-focused digital products for ambitious brands.',
    ];
    $updated = false;

    foreach ($updated_values as $key => $value) {
        if (empty($options[$key]) || (($legacy_values[$key] ?? null) === $options[$key])) {
            $options[$key] = $value;
            $updated       = true;
        }
    }

    if ($updated) {
        update_option('alenumedia_home_options', $options);
    }
}
add_action('init', 'alenumedia_maybe_upgrade_hero_copy');

function alenumedia_home_field_groups(): array
{
    return [
        'brand'    => [
            'label'  => __('Brand & Hero', 'alenumedia'),
            'fields' => [
                ['key' => 'logo_text', 'label' => 'Logo Text', 'type' => 'text'],
                ['key' => 'favicon_url', 'label' => 'Favicon / Site Icon', 'type' => 'media'],
                ['key' => 'hero_badge', 'label' => 'Hero Badge', 'type' => 'text'],
                ['key' => 'hero_title_line_1', 'label' => 'Hero Title Line 1', 'type' => 'text'],
                ['key' => 'hero_title_line_2', 'label' => 'Hero Title Line 2', 'type' => 'text'],
                ['key' => 'hero_title_line_3', 'label' => 'Hero Title Line 3', 'type' => 'text'],
                ['key' => 'hero_description', 'label' => 'Hero Description', 'type' => 'textarea'],
                ['key' => 'hero_primary_label', 'label' => 'Primary CTA Label', 'type' => 'text'],
                ['key' => 'hero_primary_url', 'label' => 'Primary CTA URL', 'type' => 'url'],
                ['key' => 'hero_secondary_label', 'label' => 'Secondary CTA Label', 'type' => 'text'],
                ['key' => 'hero_secondary_url', 'label' => 'Secondary CTA URL', 'type' => 'url'],
                ['key' => 'hero_media_url', 'label' => 'Hero iPhone Screen Image', 'type' => 'media'],
                ['key' => 'hero_mockup_title', 'label' => 'Mockup Title', 'type' => 'text'],
                ['key' => 'hero_mockup_subtitle', 'label' => 'Mockup Subtitle', 'type' => 'text'],
                ['key' => 'hero_metric_1_label', 'label' => 'Metric 1 Label', 'type' => 'text'],
                ['key' => 'hero_metric_1_value', 'label' => 'Metric 1 Value', 'type' => 'text'],
                ['key' => 'hero_metric_1_change', 'label' => 'Metric 1 Change', 'type' => 'text'],
                ['key' => 'hero_metric_2_label', 'label' => 'Metric 2 Label', 'type' => 'text'],
                ['key' => 'hero_metric_2_value', 'label' => 'Metric 2 Value', 'type' => 'text'],
                ['key' => 'hero_metric_2_change', 'label' => 'Metric 2 Change', 'type' => 'text'],
                ['key' => 'hero_metric_3_label', 'label' => 'Metric 3 Label', 'type' => 'text'],
                ['key' => 'hero_metric_3_value', 'label' => 'Metric 3 Value', 'type' => 'text'],
                ['key' => 'hero_metric_3_change', 'label' => 'Metric 3 Change', 'type' => 'text'],
            ],
        ],
        'journey'  => [
            'label'  => __('About, Process & Works', 'alenumedia'),
            'fields' => [
                ['key' => 'how_title', 'label' => 'Process Title', 'type' => 'text'],
                ['key' => 'how_intro', 'label' => 'Process Intro', 'type' => 'textarea'],
                ['key' => 'how_step_1_title', 'label' => 'Step 1 Title', 'type' => 'text'],
                ['key' => 'how_step_1_text', 'label' => 'Step 1 Text', 'type' => 'textarea'],
                ['key' => 'how_step_2_title', 'label' => 'Step 2 Title', 'type' => 'text'],
                ['key' => 'how_step_2_text', 'label' => 'Step 2 Text', 'type' => 'textarea'],
                ['key' => 'how_step_3_title', 'label' => 'Step 3 Title', 'type' => 'text'],
                ['key' => 'how_step_3_text', 'label' => 'Step 3 Text', 'type' => 'textarea'],
                ['key' => 'works_title', 'label' => 'Works Section Title', 'type' => 'text'],
                ['key' => 'works_intro', 'label' => 'Works Section Intro', 'type' => 'textarea'],
                ['key' => 'about_title', 'label' => 'About Title', 'type' => 'text'],
                ['key' => 'about_intro', 'label' => 'About Intro', 'type' => 'textarea'],
                ['key' => 'about_body', 'label' => 'About Body', 'type' => 'textarea'],
                ['key' => 'about_image_url', 'label' => 'About Image', 'type' => 'media'],
                ['key' => 'about_stat_1_label', 'label' => 'About Stat 1 Label', 'type' => 'text'],
                ['key' => 'about_stat_1_value', 'label' => 'About Stat 1 Value', 'type' => 'text'],
                ['key' => 'about_stat_2_label', 'label' => 'About Stat 2 Label', 'type' => 'text'],
                ['key' => 'about_stat_2_value', 'label' => 'About Stat 2 Value', 'type' => 'text'],
                ['key' => 'about_stat_3_label', 'label' => 'About Stat 3 Label', 'type' => 'text'],
                ['key' => 'about_stat_3_value', 'label' => 'About Stat 3 Value', 'type' => 'text'],
                ['key' => 'ai_title', 'label' => 'AI Title', 'type' => 'text'],
                ['key' => 'ai_intro', 'label' => 'AI Intro', 'type' => 'textarea'],
                ['key' => 'ai_service_1_title', 'label' => 'AI Service 1 Title', 'type' => 'text'],
                ['key' => 'ai_service_1_text', 'label' => 'AI Service 1 Text', 'type' => 'textarea'],
                ['key' => 'ai_service_2_title', 'label' => 'AI Service 2 Title', 'type' => 'text'],
                ['key' => 'ai_service_2_text', 'label' => 'AI Service 2 Text', 'type' => 'textarea'],
                ['key' => 'ai_service_3_title', 'label' => 'AI Service 3 Title', 'type' => 'text'],
                ['key' => 'ai_service_3_text', 'label' => 'AI Service 3 Text', 'type' => 'textarea'],
                ['key' => 'ai_service_4_title', 'label' => 'AI Service 4 Title', 'type' => 'text'],
                ['key' => 'ai_service_4_text', 'label' => 'AI Service 4 Text', 'type' => 'textarea'],
            ],
        ],
        'proof'    => [
            'label'  => __('Pricing & Social Proof', 'alenumedia'),
            'fields' => [
                ['key' => 'pricing_title', 'label' => 'Pricing Title', 'type' => 'text'],
                ['key' => 'pricing_intro', 'label' => 'Pricing Intro', 'type' => 'textarea'],
                ['key' => 'pricing_plan_1_name', 'label' => 'Plan 1 Name', 'type' => 'text'],
                ['key' => 'pricing_plan_1_price', 'label' => 'Plan 1 Price', 'type' => 'text'],
                ['key' => 'pricing_plan_1_features', 'label' => 'Plan 1 Features', 'type' => 'textarea'],
                ['key' => 'pricing_plan_2_name', 'label' => 'Plan 2 Name', 'type' => 'text'],
                ['key' => 'pricing_plan_2_price', 'label' => 'Plan 2 Price', 'type' => 'text'],
                ['key' => 'pricing_plan_2_features', 'label' => 'Plan 2 Features', 'type' => 'textarea'],
                ['key' => 'pricing_plan_3_name', 'label' => 'Plan 3 Name', 'type' => 'text'],
                ['key' => 'pricing_plan_3_price', 'label' => 'Plan 3 Price', 'type' => 'text'],
                ['key' => 'pricing_plan_3_features', 'label' => 'Plan 3 Features', 'type' => 'textarea'],
                ['key' => 'testimonials_title', 'label' => 'Testimonials Title', 'type' => 'text'],
                ['key' => 'testimonial_1_quote', 'label' => 'Testimonial 1 Quote', 'type' => 'textarea'],
                ['key' => 'testimonial_1_name', 'label' => 'Testimonial 1 Name', 'type' => 'text'],
                ['key' => 'testimonial_1_role', 'label' => 'Testimonial 1 Role', 'type' => 'text'],
                ['key' => 'testimonial_2_quote', 'label' => 'Testimonial 2 Quote', 'type' => 'textarea'],
                ['key' => 'testimonial_2_name', 'label' => 'Testimonial 2 Name', 'type' => 'text'],
                ['key' => 'testimonial_2_role', 'label' => 'Testimonial 2 Role', 'type' => 'text'],
                ['key' => 'testimonial_3_quote', 'label' => 'Testimonial 3 Quote', 'type' => 'textarea'],
                ['key' => 'testimonial_3_name', 'label' => 'Testimonial 3 Name', 'type' => 'text'],
                ['key' => 'testimonial_3_role', 'label' => 'Testimonial 3 Role', 'type' => 'text'],
            ],
        ],
        'contact'  => [
            'label'  => __('Mobile Apps & Leads', 'alenumedia'),
            'fields' => [
                ['key' => 'faq_title', 'label' => 'Mobile Apps Title', 'type' => 'text'],
                ['key' => 'faq_1_question', 'label' => 'Service Card 1 Title', 'type' => 'text'],
                ['key' => 'faq_1_answer', 'label' => 'Service Card 1 Text', 'type' => 'textarea'],
                ['key' => 'faq_2_question', 'label' => 'Service Card 2 Title', 'type' => 'text'],
                ['key' => 'faq_2_answer', 'label' => 'Service Card 2 Text', 'type' => 'textarea'],
                ['key' => 'faq_3_question', 'label' => 'Service Card 3 Title', 'type' => 'text'],
                ['key' => 'faq_3_answer', 'label' => 'Service Card 3 Text', 'type' => 'textarea'],
                ['key' => 'faq_4_question', 'label' => 'Service Card 4 Title', 'type' => 'text'],
                ['key' => 'faq_4_answer', 'label' => 'Service Card 4 Text', 'type' => 'textarea'],
                ['key' => 'mobile_ios_image_url', 'label' => 'iPhone Screen Image', 'type' => 'media'],
                ['key' => 'mobile_android_image_url', 'label' => 'Android Screen Image', 'type' => 'media'],
                ['key' => 'quote_title', 'label' => 'Quote Section Title', 'type' => 'text'],
                ['key' => 'quote_intro', 'label' => 'Quote Section Intro', 'type' => 'textarea'],
                ['key' => 'recipient_email', 'label' => 'Notification Email', 'type' => 'email'],
            ],
        ],
    ];
}

function alenumedia_get_home_options(): array
{
    $defaults = alenumedia_get_home_defaults();
    $options  = wp_parse_args(get_option('alenumedia_home_options', []), $defaults);

    if (empty($options['about_image_url'])) {
        $options['about_image_url'] = $defaults['about_image_url'];
    }

    return $options;
}

function alenumedia_sanitize_home_field(string $type, mixed $value): string
{
    return match ($type) {
        'url', 'media' => esc_url_raw((string) $value),
        'email'        => sanitize_email((string) $value),
        'textarea'     => sanitize_textarea_field((string) $value),
        default        => sanitize_text_field((string) $value),
    };
}

function alenumedia_register_home_menu(): void
{
    add_menu_page(
        __('Theme Experience', 'alenumedia'),
        __('Theme Experience', 'alenumedia'),
        'manage_options',
        'alenumedia-home',
        'alenumedia_render_home_options_page',
        'dashicons-admin-customizer',
        61
    );
}
add_action('admin_menu', 'alenumedia_register_home_menu');

function alenumedia_enqueue_home_admin_assets(string $hook): void
{
    if ('toplevel_page_alenumedia-home' !== $hook) {
        return;
    }

    $admin_css = function_exists('alenumedia_get_asset_details')
        ? alenumedia_get_asset_details('assets/css/admin.min.css')
        : [
            'uri'     => get_template_directory_uri() . '/assets/css/admin.min.css',
            'version' => wp_get_theme()->get('Version') ?: '1.0.0',
        ];
    $admin_js = function_exists('alenumedia_get_asset_details')
        ? alenumedia_get_asset_details('assets/js/admin.min.js')
        : [
            'uri'     => get_template_directory_uri() . '/assets/js/admin.min.js',
            'version' => wp_get_theme()->get('Version') ?: '1.0.0',
        ];

    wp_enqueue_media();
    wp_enqueue_style(
        'alenumedia-admin',
        $admin_css['uri'],
        [],
        $admin_css['version']
    );
    wp_enqueue_script(
        'alenumedia-admin',
        $admin_js['uri'],
        ['jquery'],
        $admin_js['version'],
        true
    );
}
add_action('admin_enqueue_scripts', 'alenumedia_enqueue_home_admin_assets');

function alenumedia_render_home_options_page(): void
{
    if (! current_user_can('manage_options')) {
        return;
    }

    $groups  = alenumedia_home_field_groups();
    $options = alenumedia_get_home_options();
    ?>
    <div class="wrap alenu-admin">
        <?php if (isset($_GET['updated']) && '1' === sanitize_text_field(wp_unslash($_GET['updated']))) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e('Theme experience updated.', 'alenumedia'); ?></p></div>
        <?php endif; ?>
        <div class="alenu-admin__hero">
            <div>
                <p class="alenu-admin__eyebrow"><?php esc_html_e('ALENU MEDIA SYSTEM', 'alenumedia'); ?></p>
                <h1><?php esc_html_e('Theme Experience', 'alenumedia'); ?></h1>
                <p><?php esc_html_e('Update the homepage copy, About section, AI services, pricing, social proof, FAQ, and quote notification settings from one clean control room.', 'alenumedia'); ?></p>
            </div>
            <div class="alenu-admin__badge">
                <span><?php esc_html_e('Live Site Flow', 'alenumedia'); ?></span>
                <strong><?php esc_html_e('Hero > About > AI > Works > Pricing > Proof > Quote', 'alenumedia'); ?></strong>
            </div>
        </div>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="alenu-admin__form" novalidate>
            <input type="hidden" name="action" value="alenumedia_save_home_options">
            <?php wp_nonce_field('alenumedia_save_home_options', 'alenumedia_home_nonce'); ?>
            <div class="alenu-admin__shell">
                <aside class="alenu-admin__tabs" aria-label="<?php esc_attr_e('Experience sections', 'alenumedia'); ?>">
                    <?php $index = 0; ?>
                    <?php foreach ($groups as $slug => $group) : ?>
                        <button
                            type="button"
                            class="alenu-admin__tab <?php echo 0 === $index ? 'is-active' : ''; ?>"
                            data-tab-target="<?php echo esc_attr($slug); ?>"
                        >
                            <?php echo esc_html($group['label']); ?>
                        </button>
                        <?php $index++; ?>
                    <?php endforeach; ?>
                </aside>

                <div class="alenu-admin__panels">
                    <?php $panel_index = 0; ?>
                    <?php foreach ($groups as $slug => $group) : ?>
                        <section
                            class="alenu-admin__panel <?php echo 0 === $panel_index ? 'is-active' : ''; ?>"
                            data-tab-panel="<?php echo esc_attr($slug); ?>"
                        >
                            <div class="alenu-admin__panel-head">
                                <h2><?php echo esc_html($group['label']); ?></h2>
                                <p><?php esc_html_e('Each field updates the homepage instantly after saving.', 'alenumedia'); ?></p>
                            </div>
                            <div class="alenu-admin__grid">
                                <?php foreach ($group['fields'] as $field) : ?>
                                    <label class="alenu-admin__field alenu-admin__field--<?php echo esc_attr($field['type']); ?>">
                                        <span><?php echo esc_html($field['label']); ?></span>
                                        <?php $value = $options[$field['key']] ?? ''; ?>
                                        <?php if ('textarea' === $field['type']) : ?>
                                            <textarea name="<?php echo esc_attr($field['key']); ?>" rows="4"><?php echo esc_textarea($value); ?></textarea>
                                        <?php elseif ('media' === $field['type']) : ?>
                                            <div class="alenu-admin__media">
                                                <input type="url" name="<?php echo esc_attr($field['key']); ?>" value="<?php echo esc_attr($value); ?>" />
                                                <button type="button" class="button button-secondary alenu-media-picker"><?php esc_html_e('Choose Image', 'alenumedia'); ?></button>
                                            </div>
                                            <?php if ('favicon_url' === $field['key']) : ?>
                                                <small class="alenu-admin__hint"><?php esc_html_e('Best results: upload an SVG or a small square PNG/WebP icon. Very large images may fail on limited hosting.', 'alenumedia'); ?></small>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <input
                                                type="<?php echo esc_attr('url' === $field['type'] ? 'text' : $field['type']); ?>"
                                                name="<?php echo esc_attr($field['key']); ?>"
                                                value="<?php echo esc_attr($value); ?>"
                                            />
                                        <?php endif; ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </section>
                        <?php $panel_index++; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="alenu-admin__actions">
                <button type="submit" class="button button-primary button-hero"><?php esc_html_e('Save Theme Experience', 'alenumedia'); ?></button>
            </div>
        </form>
    </div>
    <?php
}

function alenumedia_handle_home_options_save(): void
{
    if (! current_user_can('manage_options')) {
        wp_die(esc_html__('You are not allowed to do that.', 'alenumedia'));
    }

    check_admin_referer('alenumedia_save_home_options', 'alenumedia_home_nonce');

    $groups  = alenumedia_home_field_groups();
    $updated = [];

    foreach ($groups as $group) {
        foreach ($group['fields'] as $field) {
            $raw                    = $_POST[$field['key']] ?? '';
            $updated[$field['key']] = alenumedia_sanitize_home_field($field['type'], wp_unslash($raw));
        }
    }

    update_option('alenumedia_home_options', $updated);

    wp_safe_redirect(
        add_query_arg(
            [
                'page'    => 'alenumedia-home',
                'updated' => '1',
            ],
            admin_url('admin.php')
        )
    );
    exit;
}
add_action('admin_post_alenumedia_save_home_options', 'alenumedia_handle_home_options_save');
