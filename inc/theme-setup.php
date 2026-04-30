<?php
/**
 * Theme setup and front-end registration.
 *
 * @package AlenuMedia
 */

if (! defined('ABSPATH')) {
    exit;
}

function alenumedia_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support(
        'custom-logo',
        [
            'height'      => 64,
            'width'       => 64,
            'flex-height' => true,
            'flex-width'  => true,
        ]
    );
    add_theme_support(
        'html5',
        [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]
    );

    register_nav_menus(
        [
            'primary' => __('Primary Navigation', 'alenumedia'),
        ]
    );

    add_image_size('alenumedia-hero-device', 420, 820, false);
    add_image_size('alenumedia-portrait', 900, 1100, false);
    add_image_size('alenumedia-mobile-screen', 420, 900, false);
    add_image_size('alenumedia-card', 900, 900, false);
}
add_action('after_setup_theme', 'alenumedia_setup');

function alenumedia_get_asset_details(string $relative_path): array
{
    $version = wp_get_theme()->get('Version') ?: '1.0.0';
    $path    = get_template_directory() . '/' . ltrim($relative_path, '/');
    $uri     = get_template_directory_uri() . '/' . ltrim($relative_path, '/');

    if (file_exists($path)) {
        $version = (string) filemtime($path);
    }

    return [
        'path'    => $path,
        'uri'     => $uri,
        'version' => $version,
    ];
}

function alenumedia_enqueue_assets(): void
{
    $version = wp_get_theme()->get('Version') ?: '1.0.0';
    $style_version = file_exists(get_stylesheet_directory() . '/style.css') ? (string) filemtime(get_stylesheet_directory() . '/style.css') : $version;
    $main_css = alenumedia_get_asset_details('assets/css/main.min.css');
    $main_js  = alenumedia_get_asset_details('assets/js/main.min.js');
    $is_spa_experience = is_front_page() || alenumedia_is_spa_view_request();

    wp_enqueue_style(
        'alenumedia-fonts',
        'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Oxanium:wght@400;500;600;700&display=swap',
        [],
        null
    );
    wp_enqueue_style('alenumedia-style', get_stylesheet_uri(), [], $style_version);
    wp_enqueue_style(
        'alenumedia-main',
        $main_css['uri'],
        ['alenumedia-style'],
        $main_css['version']
    );

    if ($is_spa_experience) {
        wp_enqueue_script(
            'alenumedia-animejs',
            'https://cdn.jsdelivr.net/npm/animejs/dist/bundles/anime.umd.min.js',
            [],
            null,
            true
        );
        wp_script_add_data('alenumedia-animejs', 'defer', true);
    }

    wp_enqueue_script(
        'alenumedia-main',
        $main_js['uri'],
        $is_spa_experience ? ['alenumedia-animejs'] : [],
        $main_js['version'],
        true
    );
    wp_script_add_data('alenumedia-main', 'defer', true);

    wp_localize_script(
        'alenumedia-main',
        'alenuTheme',
        [
            'restUrl'      => esc_url_raw(rest_url('alenumedia/v1/quote')),
            'worksUrl'     => esc_url_raw(rest_url('alenumedia/v1/works')),
            'postsUrl'     => esc_url_raw(rest_url('alenumedia/v1/posts')),
            'homeUrl'      => esc_url_raw(home_url('/')),
            'worksPerPage' => alenumedia_get_works_per_page(),
            'nonce'        => wp_create_nonce('wp_rest'),
            'performance'  => [
                'isFrontPage'    => $is_spa_experience,
            ],
            'spa'          => [
                'currentView' => alenumedia_get_current_spa_view(),
                'routes'      => alenumedia_get_spa_view_routes(),
            ],
            'strings'      => [
                'sending'          => __('Launching request...', 'alenumedia'),
                'success'          => __('Request received. We will contact you shortly.', 'alenumedia'),
                'error'            => __('Something glitched. Please try again.', 'alenumedia'),
                'loadMore'         => __('Load More Works', 'alenumedia'),
                'loadingWorks'     => __('Loading more works...', 'alenumedia'),
                'loadWorksError'   => __('Could not load more works right now.', 'alenumedia'),
                'noWorksFound'     => __('No works found in this category yet.', 'alenumedia'),
                'loadMorePosts'    => __('Load More Articles', 'alenumedia'),
                'loadPostsError'   => __('Could not load more articles right now.', 'alenumedia'),
            ],
        ]
    );
}
add_action('wp_enqueue_scripts', 'alenumedia_enqueue_assets');

function alenumedia_filter_style_loader_tag(string $html, string $handle, string $href, string $media): string
{
    if ('alenumedia-fonts' !== $handle) {
        return $html;
    }

    return sprintf(
        '<link rel="preload" href="%1$s" as="style" onload="this.onload=null;this.rel=\'stylesheet\'" media="%2$s"><noscript><link rel="stylesheet" href="%1$s" media="%2$s"></noscript>',
        esc_url($href),
        esc_attr($media ?: 'all')
    );
}
add_filter('style_loader_tag', 'alenumedia_filter_style_loader_tag', 10, 4);

function alenumedia_add_resource_hints(array $urls, string $relation_type): array
{
    if ('preconnect' === $relation_type) {
        $urls[] = 'https://fonts.googleapis.com';
        $urls[] = [
            'href'        => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        ];
    }

    return $urls;
}
add_filter('wp_resource_hints', 'alenumedia_add_resource_hints', 10, 2);

function alenumedia_handle_legacy_asset_requests(): void
{
    $sitemap = get_query_var('alenumedia_sitemap');
    if (is_string($sitemap) && '' !== $sitemap) {
        alenumedia_render_sitemap($sitemap);
        exit;
    }

    $request_uri = $_SERVER['REQUEST_URI'] ?? '';

    if (! is_string($request_uri) || '' === $request_uri) {
        return;
    }

    $request_path = wp_parse_url($request_uri, PHP_URL_PATH);

    if (! is_string($request_path) || '' === $request_path) {
        return;
    }

    if ('/favicon.ico' === $request_path) {
        $favicon = alenumedia_get_favicon_url();

        if ($favicon) {
            wp_safe_redirect($favicon, 301);
            exit;
        }

        status_header(204);
        exit;
    }

    if (str_ends_with($request_path, '/three.min.js') || '/three.min.js' === $request_path) {
        status_header(200);
        header('Content-Type: application/javascript; charset=' . get_bloginfo('charset'));
        header('Cache-Control: public, max-age=600');
        echo 'window.THREE=window.THREE||undefined;';
        exit;
    }
}
add_action('template_redirect', 'alenumedia_handle_legacy_asset_requests', 0);

function alenumedia_force_spa_template(string $template): string
{
    if (! alenumedia_is_spa_view_request()) {
        return $template;
    }

    $spa_template = locate_template('front-page.php');

    return $spa_template ?: $template;
}
add_filter('template_include', 'alenumedia_force_spa_template', 99);

function alenumedia_get_sitemap_url(string $name = 'index'): string
{
    return 'index' === $name
        ? home_url('/sitemap.xml')
        : home_url(sprintf('/sitemap-%s.xml', rawurlencode($name)));
}

function alenumedia_get_sitemap_collections(): array
{
    return [
        'pages' => [
            'label' => 'Pages',
            'query' => [
                'post_type'              => ['page'],
                'post_status'            => 'publish',
                'posts_per_page'         => 1000,
                'orderby'                => 'modified',
                'order'                  => 'DESC',
                'ignore_sticky_posts'    => true,
                'no_found_rows'          => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
            ],
        ],
        'posts' => [
            'label' => 'Posts',
            'query' => [
                'post_type'              => ['post'],
                'post_status'            => 'publish',
                'posts_per_page'         => 1000,
                'orderby'                => 'modified',
                'order'                  => 'DESC',
                'ignore_sticky_posts'    => true,
                'no_found_rows'          => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
            ],
        ],
        'works' => [
            'label' => 'Works',
            'query' => [
                'post_type'              => ['works'],
                'post_status'            => 'publish',
                'posts_per_page'         => 1000,
                'orderby'                => 'modified',
                'order'                  => 'DESC',
                'ignore_sticky_posts'    => true,
                'no_found_rows'          => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
            ],
        ],
    ];
}

function alenumedia_send_xml_headers(): void
{
    status_header(200);
    header('Content-Type: application/xml; charset=' . get_bloginfo('charset'));
    header('X-Robots-Tag: noindex, follow', true);
}

function alenumedia_render_sitemap(string $name): void
{
    $name = sanitize_key($name);

    alenumedia_send_xml_headers();

    if ('index' === $name) {
        $collections = alenumedia_get_sitemap_collections();
        $items       = [];

        foreach (array_keys($collections) as $collection_name) {
            $query = new WP_Query($collections[$collection_name]['query']);
            if ($query->have_posts()) {
                $lastmod = '';
                foreach ($query->posts as $post) {
                    $modified = get_post_modified_time(DATE_W3C, true, $post);
                    if ($modified > $lastmod) {
                        $lastmod = $modified;
                    }
                }
                $items[] = [
                    'loc'     => alenumedia_get_sitemap_url($collection_name),
                    'lastmod' => $lastmod ?: gmdate(DATE_W3C),
                ];
            }
            wp_reset_postdata();
        }

        echo '<?xml version="1.0" encoding="' . esc_attr(get_bloginfo('charset')) . '"?>';
        echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($items as $item) {
            echo '<sitemap>';
            echo '<loc>' . esc_url($item['loc']) . '</loc>';
            echo '<lastmod>' . esc_html($item['lastmod']) . '</lastmod>';
            echo '</sitemap>';
        }
        echo '</sitemapindex>';
        return;
    }

    $collections = alenumedia_get_sitemap_collections();
    if (! isset($collections[$name])) {
        status_header(404);
        echo '<?xml version="1.0" encoding="' . esc_attr(get_bloginfo('charset')) . '"?><error>Not found</error>';
        return;
    }

    $query = new WP_Query($collections[$name]['query']);
    $urls  = [];

    foreach ($query->posts as $post) {
        $permalink = get_permalink($post);
        if (! $permalink) {
            continue;
        }

        $priority = '0.60';
        $changefreq = 'monthly';

        if ((int) get_option('page_on_front') === (int) $post->ID) {
            $priority = '1.00';
            $changefreq = 'weekly';
        } elseif ('page' === $post->post_type) {
            $priority = '0.80';
        } elseif ('works' === $post->post_type) {
            $priority = '0.75';
        }

        $urls[] = [
            'loc'        => $permalink,
            'lastmod'    => get_post_modified_time(DATE_W3C, true, $post),
            'changefreq' => $changefreq,
            'priority'   => $priority,
        ];
    }
    wp_reset_postdata();

    echo '<?xml version="1.0" encoding="' . esc_attr(get_bloginfo('charset')) . '"?>';
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($urls as $url) {
        echo '<url>';
        echo '<loc>' . esc_url($url['loc']) . '</loc>';
        echo '<lastmod>' . esc_html($url['lastmod']) . '</lastmod>';
        echo '<changefreq>' . esc_html($url['changefreq']) . '</changefreq>';
        echo '<priority>' . esc_html($url['priority']) . '</priority>';
        echo '</url>';
    }
    echo '</urlset>';
}

function alenumedia_filter_robots_txt(string $output, bool $public): string
{
    $lines = [];

    if ('' !== trim($output)) {
        $lines[] = trim($output);
    } else {
        $lines[] = 'User-agent: *';
        $lines[] = $public ? 'Allow: /' : 'Disallow: /';
    }

    $lines[] = 'Sitemap: ' . alenumedia_get_sitemap_url('index');

    return implode("\n", $lines) . "\n";
}
add_filter('robots_txt', 'alenumedia_filter_robots_txt', 10, 2);

function alenumedia_output_favicon_fallback(): void
{
    $custom_favicon = '';

    if (function_exists('alenumedia_get_home_options')) {
        $options         = alenumedia_get_home_options();
        $custom_favicon  = is_string($options['favicon_url'] ?? '') ? $options['favicon_url'] : '';
    }

    if ('' === $custom_favicon && has_site_icon()) {
        return;
    }

    $favicon = alenumedia_get_favicon_url();

    if (! $favicon) {
        return;
    }
    ?>
    <link rel="icon" href="<?php echo esc_url($favicon); ?>" sizes="32x32">
    <link rel="apple-touch-icon" href="<?php echo esc_url($favicon); ?>">
    <?php
}
add_action('wp_head', 'alenumedia_output_favicon_fallback', 2);

function alenumedia_register_content_types(): void
{
    register_post_type(
        'works',
        [
            'labels'       => [
                'name'          => __('Works', 'alenumedia'),
                'singular_name' => __('Work', 'alenumedia'),
                'add_new_item'  => __('Add New Work', 'alenumedia'),
                'edit_item'     => __('Edit Work', 'alenumedia'),
            ],
            'public'       => true,
            'menu_icon'    => 'dashicons-superhero',
            'has_archive'  => true,
            'rewrite'      => ['slug' => 'works'],
            'show_in_rest' => true,
            'supports'     => ['title', 'editor', 'excerpt', 'thumbnail', 'revisions'],
        ]
    );

    register_taxonomy(
        'work_category',
        ['works'],
        [
            'labels'            => [
                'name'          => __('Work Categories', 'alenumedia'),
                'singular_name' => __('Work Category', 'alenumedia'),
            ],
            'public'            => true,
            'hierarchical'      => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'rewrite'           => ['slug' => 'work-category'],
        ]
    );
}
add_action('init', 'alenumedia_register_content_types');

function alenumedia_register_sitemap_routes(): void
{
    add_rewrite_rule('^sitemap\.xml$', 'index.php?alenumedia_sitemap=index', 'top');
    add_rewrite_rule('^sitemap-([a-z0-9_-]+)\.xml$', 'index.php?alenumedia_sitemap=$matches[1]', 'top');

    foreach (alenumedia_get_spa_view_routes() as $view => $path) {
        if ('home' === $view || '' === trim($path, '/')) {
            continue;
        }

        add_rewrite_rule('^' . preg_quote(trim($path, '/'), '/') . '/?$', 'index.php?alenumedia_spa_view=' . $view, 'top');
    }
}
add_action('init', 'alenumedia_register_sitemap_routes');

function alenumedia_register_sitemap_query_var(array $vars): array
{
    $vars[] = 'alenumedia_sitemap';
    $vars[] = 'alenumedia_spa_view';

    return $vars;
}
add_filter('query_vars', 'alenumedia_register_sitemap_query_var');

function alenumedia_flush_rewrite_rules(): void
{
    alenumedia_register_content_types();
    alenumedia_register_sitemap_routes();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'alenumedia_flush_rewrite_rules');

function alenumedia_maybe_flush_rewrite_rules(): void
{
    $version = 'alenumedia_rewrite_version_3';

    if (get_option($version)) {
        return;
    }

    alenumedia_register_content_types();
    alenumedia_register_sitemap_routes();
    flush_rewrite_rules(false);
    update_option($version, '1', false);
}
add_action('init', 'alenumedia_maybe_flush_rewrite_rules', 99);

function alenumedia_get_spa_view_routes(): array
{
    return [
        'home'         => '/',
        'about'        => '/about/',
        'ai'           => '/ai-integrations/',
        'works'        => '/portfolio/',
        'pricing'      => '/web-development/',
        'faq'          => '/mobile-app-development/',
        'quote'        => '/contact/',
        'testimonials' => '/client-testimonials/',
    ];
}

function alenumedia_get_spa_view_url(string $view): string
{
    $routes = alenumedia_get_spa_view_routes();

    return home_url($routes[$view] ?? '/');
}

function alenumedia_get_current_spa_view(): string
{
    $view = sanitize_key((string) get_query_var('alenumedia_spa_view'));
    $routes = alenumedia_get_spa_view_routes();

    return isset($routes[$view]) ? $view : 'home';
}

function alenumedia_is_spa_view_request(): bool
{
    return 'home' !== alenumedia_get_current_spa_view();
}

function alenumedia_seed_work_categories(): void
{
    $terms = get_terms(
        [
            'taxonomy'   => 'work_category',
            'hide_empty' => false,
            'number'     => 1,
            'fields'     => 'ids',
        ]
    );

    if (is_wp_error($terms) || ! empty($terms)) {
        return;
    }

    foreach (['Websites', 'Systems', 'Launches', 'Brands', 'Apps'] as $term_name) {
        wp_insert_term($term_name, 'work_category');
    }
}
add_action('init', 'alenumedia_seed_work_categories', 20);

function alenumedia_get_logo_markup(?string $label = null): string
{
    $label = 'ALENUMEDIA';

    return sprintf(
        '<span class="brand-mark" aria-hidden="true">
            <span class="brand-mark__icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 163.9 161.14" role="img" focusable="false" aria-hidden="true">
                    <path fill="currentColor" d="M56.21 125.73 79.26 115.05c18.19-8.43 46.48-24.56 47.9-45.19.36-5.17-3.02-8.69-8.24-9.24l-9.15-.96c-5.2-.55-8.11-5.26-8.51-10.3-.59-7.46 1.57-14.46 6.28-20.68 1.87 4.84 5.58 6.79 10.19 6.61 8.71-.35 16.07 4.07 19.36 12.17 2.39 5.88 3.18 12.1 2.87 18.71-1.15 24.03-13.5 45.2-32.97 59.18-10.84 7.79-22.48 13.57-34.82 18.71l-20.24 8.12 4.27-26.44Z"/>
                    <path fill="currentColor" d="M155.03 66.44c-5.3-30.36-28.97-53.07-58.35-59.41C63.86-.04 30.71 14.94 14.5 44.34c-20.05 36.36-4.57 81.91 32.04 101.01l-1.86 6.76C15.92 137.91-1.72 107.68.13 75.71c1.19-20.58 10.33-39.24 25.1-53.3C45.81 2.82 74.71-4.3 102.23 2.53c35.4 8.8 61.27 40 61.67 76.88.31 29.38-15.83 56.7-41.28 71.07-19.91 11.24-43.09 13.65-64.86 6.95l10.76-4.77c25.37 3.8 51.41-5.01 68.68-24.37 14.86-16.66 21.76-39.4 17.84-61.84Z"/>
                    <path fill="currentColor" d="m74.17 94.28 10.15 18.34-17.19 8.05-8.39-28.07c-3.13-10.47-4.5-22.38.63-31.84-11.38-.07-24.29-.7-24.33-12.73-.03-9.08 4.57-17.7 10.59-24.88 1.47 7.97 5.19 12.82 13.62 12.75l16.32-.13c6.47-.05 11.14 4.21 12.09 10.5.71 4.74-.08 9.92-2.03 14.03l-13.05-.24c-6.33 8.77-3.65 25.35 1.59 34.23Z"/>
                    <path fill="currentColor" d="m48.48 134.62-1.25 6.98c-20.99-11.79-34.84-32.31-36.88-56.18-1.58-18.4 3.93-36.6 16.32-50.16-3.28 5.68-6.37 10.78-8.35 16.98-10.35 31.11 1.62 65.35 30.16 82.39Z"/>
                    <path fill="currentColor" d="m74.86 149.8 7.35-2.98c28.62-1.19 52.77-17.79 65.12-43.57-9.43 30.05-41.44 49.61-72.47 46.55Z"/>
                </svg>
            </span>
            <span class="brand-mark__word">%1$s</span>
        </span><span class="screen-reader-text">%1$s</span>',
        esc_html($label)
    );
}

function alenumedia_get_work_meta_value(int $post_id, array $keys): string
{
    foreach ($keys as $key) {
        $value = get_post_meta($post_id, $key, true);

        if (is_array($value)) {
            $value = implode(', ', array_filter(array_map('trim', $value)));
        }

        $value = trim(wp_strip_all_tags((string) $value));

        if ('' !== $value) {
            return $value;
        }
    }

    return '';
}

function alenumedia_get_attachment_image_from_url(
    string $url,
    string $size = 'large',
    array $attr = [],
    ?string $fallback_alt = null
): string {
    $url = trim($url);

    if ('' === $url) {
        return '';
    }

    $attachment_id = attachment_url_to_postid($url);

    if ($attachment_id) {
        if ($fallback_alt && empty($attr['alt'])) {
            $attr['alt'] = $fallback_alt;
        }

        return wp_get_attachment_image($attachment_id, $size, false, $attr) ?: '';
    }

    $fallback_size_map = [
        'alenumedia-hero-device'   => 420,
        'alenumedia-portrait'      => 900,
        'alenumedia-mobile-screen' => 420,
        'alenumedia-card'          => 900,
    ];

    $attributes = '';
    $attr       = array_merge(
        [
            'src'      => esc_url($url),
            'alt'      => $fallback_alt ? esc_attr($fallback_alt) : '',
            'loading'  => 'lazy',
            'decoding' => 'async',
        ],
        $attr
    );

    if (! isset($attr['width']) && isset($fallback_size_map[$size])) {
        $attr['width'] = (string) $fallback_size_map[$size];
    }

    foreach ($attr as $key => $value) {
        if (null === $value || '' === $value) {
            continue;
        }

        $attributes .= sprintf(' %s="%s"', esc_attr($key), esc_attr((string) $value));
    }

    return sprintf('<img%s>', $attributes);
}

function alenumedia_get_seo_default_image_url(): string
{
    $home = alenumedia_get_home_options();

    foreach (['hero_media_url', 'about_image_url', 'mobile_ios_image_url', 'mobile_android_image_url'] as $key) {
        if (! empty($home[$key])) {
            return esc_url_raw((string) $home[$key]);
        }
    }

    return '';
}

function alenumedia_get_current_canonical_url(): string
{
    if (is_front_page()) {
        return home_url('/');
    }

    if (is_paged()) {
        return get_pagenum_link(get_query_var('paged'));
    }

    if (is_home()) {
        return get_permalink((int) get_option('page_for_posts')) ?: home_url('/');
    }

    if (is_post_type_archive()) {
        $post_type = get_query_var('post_type');
        if (is_array($post_type)) {
            $post_type = reset($post_type);
        }
        $archive_link = $post_type ? get_post_type_archive_link((string) $post_type) : '';
        if ($archive_link) {
            return $archive_link;
        }
    }

    if (is_singular()) {
        return (string) get_permalink();
    }

    return home_url(add_query_arg([]));
}

function alenumedia_filter_document_title(string $title): string
{
    if (is_front_page()) {
        return 'ALENUMEDIA | Web Design, WordPress Development, Mobile Apps & AI Integrations';
    }

    if (alenumedia_is_spa_view_request()) {
        return match (alenumedia_get_current_spa_view()) {
            'about' => 'About ALENUMEDIA | Web Design, Development & AI Delivery',
            'ai' => 'AI Integrations | AI Automation, Assistants & Smart Website Systems',
            'works' => 'Portfolio | Web Design, WordPress & App Development Projects',
            'pricing' => 'Web Development Services | Custom Websites & WordPress Development',
            'faq' => 'Mobile App Development | iOS, Android & Cross-Platform Strategy',
            'quote' => 'Contact ALENUMEDIA | Request a Quote for Web, App or AI Projects',
            'testimonials' => 'Client Testimonials | ALENUMEDIA Digital Project Feedback',
            default => 'ALENUMEDIA | Web Design, WordPress Development, Mobile Apps & AI Integrations',
        };
    }

    if (is_home()) {
        return 'Blog | ALENUMEDIA Insights on Web, App & AI Growth';
    }

    if (is_post_type_archive('works')) {
        return 'Works | ALENUMEDIA Case Studies in Web, App & AI Development';
    }

    if (is_singular('works')) {
        return sprintf('%s | ALENUMEDIA Case Study', single_post_title('', false));
    }

    if (is_singular('post')) {
        return sprintf('%s | ALENUMEDIA Blog', single_post_title('', false));
    }

    if (is_page()) {
        return sprintf('%s | ALENUMEDIA', single_post_title('', false));
    }

    return $title;
}
add_filter('pre_get_document_title', 'alenumedia_filter_document_title');

function alenumedia_get_seo_meta_title(): string
{
    return wp_get_document_title();
}

function alenumedia_get_seo_meta_description(): string
{
    $home = alenumedia_get_home_options();

    if (is_front_page()) {
        return 'ALENUMEDIA delivers web design, web development, WordPress development, mobile app development, and AI integrations for brands that need fast, custom, conversion-focused digital products.';
    }

    if (alenumedia_is_spa_view_request()) {
        return match (alenumedia_get_current_spa_view()) {
            'about' => 'Learn how ALENUMEDIA approaches web design, web development, WordPress development, mobile apps, and AI integrations for growth-focused brands.',
            'ai' => 'Explore AI integrations including smart assistants, workflow automation, lead qualification, and AI-powered website systems.',
            'works' => 'View ALENUMEDIA portfolio work across web design, WordPress development, mobile app development, and digital product execution.',
            'pricing' => 'Custom web development and WordPress development services built around your goals, stack, integrations, and business workflow.',
            'faq' => 'Mobile app development services for iOS, Android, and cross-platform product planning with custom integrations and scalable delivery.',
            'quote' => 'Contact ALENUMEDIA to request a quote for web design, web development, WordPress development, mobile app development, or AI integrations.',
            'testimonials' => 'Read client feedback on ALENUMEDIA web design, development, mobile app, and AI integration projects.',
            default => 'ALENUMEDIA delivers web design, web development, WordPress development, mobile app development, and AI integrations for brands that need fast, custom, conversion-focused digital products.',
        };
    }

    if (is_home()) {
        return 'Explore ALENUMEDIA articles on SEO, high-converting websites, mobile apps, AI-powered systems, and digital growth strategy.';
    }

    if (is_singular('post')) {
        $excerpt = trim((string) get_the_excerpt());
        if ('' !== $excerpt) {
            return wp_strip_all_tags($excerpt);
        }

        return wp_trim_words(wp_strip_all_tags((string) get_post_field('post_content', get_queried_object_id())), 28);
    }

    if (is_singular('works')) {
        $excerpt = trim((string) get_the_excerpt());
        if ('' !== $excerpt) {
            return wp_strip_all_tags($excerpt);
        }

        return sprintf(
            'Explore this ALENUMEDIA case study in custom web development, app experiences, and digital product execution.'
        );
    }

    if (is_post_type_archive('works')) {
        return 'Browse ALENUMEDIA case studies in web design, web development, WordPress development, mobile app development, and AI-powered digital systems.';
    }

    if (is_page()) {
        $excerpt = trim((string) get_the_excerpt());
        if ('' !== $excerpt) {
            return wp_strip_all_tags($excerpt);
        }

        return wp_strip_all_tags((string) $home['hero_description']);
    }

    return wp_strip_all_tags((string) $home['hero_description']);
}

function alenumedia_get_seo_meta_image_url(): string
{
    if (is_singular() && has_post_thumbnail()) {
        $image = get_the_post_thumbnail_url(get_queried_object_id(), 'full');
        if ($image) {
            return esc_url_raw($image);
        }
    }

    return alenumedia_get_seo_default_image_url();
}

function alenumedia_get_schema_logo_url(): string
{
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo = wp_get_attachment_image_url((int) $custom_logo_id, 'full');
        if ($logo) {
            return esc_url_raw($logo);
        }
    }

    $site_icon = get_site_icon_url(512);
    if ($site_icon) {
        return esc_url_raw($site_icon);
    }

    return alenumedia_get_seo_default_image_url();
}

function alenumedia_get_favicon_url(): string
{
    if (function_exists('alenumedia_get_home_options')) {
        $options = alenumedia_get_home_options();
        $favicon = $options['favicon_url'] ?? '';

        if (is_string($favicon) && '' !== $favicon) {
            return esc_url_raw($favicon);
        }
    }

    $site_icon = get_site_icon_url(512);
    if ($site_icon) {
        return esc_url_raw($site_icon);
    }

    return alenumedia_get_schema_logo_url();
}

function alenumedia_get_breadcrumb_schema_items(string $site_url): array
{
    $items   = [];
    $items[] = [
        '@type'    => 'ListItem',
        'position' => 1,
        'name'     => 'Home',
        'item'     => $site_url,
    ];

    if (is_front_page()) {
        return $items;
    }

    $position = 2;

    if (is_post_type_archive('works')) {
        $items[] = [
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => 'Works',
            'item'     => get_post_type_archive_link('works'),
        ];

        return $items;
    }

    if (is_home()) {
        $items[] = [
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => 'Blog',
            'item'     => alenumedia_get_current_canonical_url(),
        ];

        return $items;
    }

    if (is_singular('post')) {
        $posts_page_id = (int) get_option('page_for_posts');
        $blog_url      = $posts_page_id ? get_permalink($posts_page_id) : home_url('/blog/');

        $items[] = [
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => 'Blog',
            'item'     => $blog_url,
        ];
        $position++;

        $items[] = [
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => get_the_title(),
            'item'     => get_permalink(),
        ];

        return $items;
    }

    if (is_singular('works')) {
        $archive_link = get_post_type_archive_link('works');
        if ($archive_link) {
            $items[] = [
                '@type'    => 'ListItem',
                'position' => $position,
                'name'     => 'Works',
                'item'     => $archive_link,
            ];
            $position++;
        }

        $items[] = [
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => get_the_title(),
            'item'     => get_permalink(),
        ];

        return $items;
    }

    if (is_page()) {
        $ancestors = array_reverse(get_post_ancestors(get_queried_object_id()));
        foreach ($ancestors as $ancestor_id) {
            $items[] = [
                '@type'    => 'ListItem',
                'position' => $position,
                'name'     => get_the_title($ancestor_id),
                'item'     => get_permalink($ancestor_id),
            ];
            $position++;
        }

        $items[] = [
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => get_the_title(),
            'item'     => get_permalink(),
        ];
    }

    return $items;
}

function alenumedia_output_seo_meta(): void
{
    if (is_admin() || is_feed() || is_robots() || is_trackback()) {
        return;
    }

    $title       = alenumedia_get_seo_meta_title();
    $description = alenumedia_get_seo_meta_description();
    $image       = alenumedia_get_seo_meta_image_url();
    $url         = alenumedia_get_current_canonical_url();
    $type        = (is_singular('works') || is_singular('post')) ? 'article' : 'website';
    $robots      = (is_search() || is_404()) ? 'noindex, follow' : 'index, follow, max-image-preview:large';
    ?>
    <link rel="canonical" href="<?php echo esc_url($url); ?>">
    <meta name="description" content="<?php echo esc_attr($description); ?>">
    <meta name="robots" content="<?php echo esc_attr($robots); ?>">
    <meta property="og:locale" content="<?php echo esc_attr(str_replace('_', '-', get_locale())); ?>">
    <meta property="og:type" content="<?php echo esc_attr($type); ?>">
    <meta property="og:title" content="<?php echo esc_attr($title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($description); ?>">
    <meta property="og:url" content="<?php echo esc_url($url); ?>">
    <meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
    <meta name="twitter:card" content="<?php echo esc_attr($image ? 'summary_large_image' : 'summary'); ?>">
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
    <?php if ($image) : ?>
        <meta property="og:image" content="<?php echo esc_url($image); ?>">
        <meta property="og:image:alt" content="<?php echo esc_attr($title); ?>">
        <meta name="twitter:image" content="<?php echo esc_url($image); ?>">
    <?php endif; ?>
    <?php
}
add_action('wp_head', 'alenumedia_output_seo_meta', 1);

function alenumedia_output_schema_markup(): void
{
    if (is_admin() || is_feed() || is_robots() || is_trackback()) {
        return;
    }

    $home        = alenumedia_get_home_options();
    $site_name   = get_bloginfo('name');
    $site_url    = home_url('/');
    $current_url = alenumedia_get_current_canonical_url();
    $image       = alenumedia_get_seo_meta_image_url();
    $logo        = alenumedia_get_schema_logo_url();
    $description = alenumedia_get_seo_meta_description();

    $graph = [
        [
            '@context'    => 'https://schema.org',
            '@type'       => 'Organization',
            '@id'         => trailingslashit($site_url) . '#organization',
            'name'        => 'ALENUMEDIA',
            'url'         => $site_url,
            'description' => 'ALENUMEDIA is a web design, web development, WordPress development, mobile app development, and AI integrations company.',
            'knowsAbout'  => [
                'Web Design',
                'Custom Web Development',
                'WordPress Development',
                'Laravel Development',
                'React Development',
                'iOS App Development',
                'Android App Development',
                'AI Integrations',
                'API Integrations',
                'Custom Plugins',
            ],
        ],
        [
            '@context'    => 'https://schema.org',
            '@type'       => 'WebSite',
            '@id'         => trailingslashit($site_url) . '#website',
            'url'         => $site_url,
            'name'        => $site_name,
            'description' => $description,
            'publisher'   => [
                '@id' => trailingslashit($site_url) . '#organization',
            ],
        ],
    ];

    if ($image) {
        $graph[0]['image'] = $image;
        $graph[1]['image'] = $image;
    }

    if ($logo) {
        $graph[0]['logo'] = [
            '@type' => 'ImageObject',
            'url'   => $logo,
        ];
    }

    if (is_front_page()) {
        $graph[] = [
            '@context'      => 'https://schema.org',
            '@type'         => 'Service',
            '@id'           => trailingslashit($site_url) . '#primary-service',
            'serviceType'   => 'Web design, web development, WordPress development, mobile app development, and AI integrations',
            'name'          => 'Web Design, WordPress Development, Mobile Apps & AI Integrations',
            'provider'      => [
                '@id' => trailingslashit($site_url) . '#organization',
            ],
            'areaServed'    => 'Worldwide',
            'description'   => 'Custom web design, web development, WordPress development, iOS and Android app development, AI integrations, custom plugins, and API-connected digital systems.',
            'offers'        => [
                '@type'           => 'OfferCatalog',
                'name'            => 'Core Services',
                'itemListElement' => [
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name'  => 'Web Design',
                        ],
                    ],
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name'  => 'Custom Web Development',
                        ],
                    ],
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name'  => 'WordPress Development',
                        ],
                    ],
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name'  => 'Mobile App Development',
                        ],
                    ],
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name'  => 'AI Integrations',
                        ],
                    ],
                ],
            ],
        ];

        $graph[] = [
            '@context'    => 'https://schema.org',
            '@type'       => 'WebPage',
            '@id'         => trailingslashit($site_url) . '#webpage',
            'url'         => $current_url,
            'name'        => alenumedia_get_seo_meta_title(),
            'description' => $description,
            'isPartOf'    => [
                '@id' => trailingslashit($site_url) . '#website',
            ],
            'about'       => [
                '@id' => trailingslashit($site_url) . '#organization',
            ],
        ];
    } elseif (is_singular('works')) {
        $graph[] = [
            '@context'         => 'https://schema.org',
            '@type'            => 'Article',
            '@id'              => trailingslashit($current_url) . '#creativework',
            'url'              => $current_url,
            'name'             => get_the_title(),
            'description'      => $description,
            'headline'         => get_the_title(),
            'datePublished'    => get_the_date(DATE_W3C),
            'dateModified'     => get_the_modified_date(DATE_W3C),
            'publisher'        => [
                '@id' => trailingslashit($site_url) . '#organization',
            ],
            'mainEntityOfPage' => $current_url,
        ];
    } elseif (is_home()) {
        $graph[] = [
            '@context'    => 'https://schema.org',
            '@type'       => 'CollectionPage',
            '@id'         => trailingslashit($current_url) . '#blog',
            'url'         => $current_url,
            'name'        => alenumedia_get_seo_meta_title(),
            'description' => $description,
            'isPartOf'    => [
                '@id' => trailingslashit($site_url) . '#website',
            ],
            'about'       => [
                '@id' => trailingslashit($site_url) . '#organization',
            ],
        ];
    } elseif (is_singular('post')) {
        $tag_names = wp_get_post_tags(get_the_ID(), ['fields' => 'names']);
        $graph[]   = [
            '@context'         => 'https://schema.org',
            '@type'            => 'BlogPosting',
            '@id'              => trailingslashit($current_url) . '#blogposting',
            'url'              => $current_url,
            'headline'         => get_the_title(),
            'name'             => get_the_title(),
            'description'      => $description,
            'datePublished'    => get_the_date(DATE_W3C),
            'dateModified'     => get_the_modified_date(DATE_W3C),
            'publisher'        => [
                '@id' => trailingslashit($site_url) . '#organization',
            ],
            'mainEntityOfPage' => $current_url,
            'articleSection'   => 'Blog',
        ];

        if ($image) {
            $graph[array_key_last($graph)]['image'] = [
                '@type' => 'ImageObject',
                'url'   => $image,
            ];
        }

        if (! empty($tag_names)) {
            $graph[array_key_last($graph)]['keywords'] = implode(', ', array_map('sanitize_text_field', $tag_names));
        }
    } else {
        $graph[] = [
            '@context'    => 'https://schema.org',
            '@type'       => 'WebPage',
            '@id'         => trailingslashit($current_url) . '#webpage',
            'url'         => $current_url,
            'name'        => alenumedia_get_seo_meta_title(),
            'description' => $description,
            'isPartOf'    => [
                '@id' => trailingslashit($site_url) . '#website',
            ],
        ];
    }

    $breadcrumb_items = alenumedia_get_breadcrumb_schema_items($site_url);
    if (count($breadcrumb_items) > 1) {
        $graph[] = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            '@id'             => trailingslashit($current_url) . '#breadcrumb',
            'itemListElement' => $breadcrumb_items,
        ];
    }

    echo '<script type="application/ld+json">' . wp_json_encode(
        ['@graph' => $graph],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    ) . '</script>';
}
add_action('wp_head', 'alenumedia_output_schema_markup', 20);

function alenumedia_parse_work_meta_list(string $value): array
{
    if ('' === trim($value)) {
        return [];
    }

    $items = preg_split('/[\r\n,]+/', $value) ?: [];

    return array_values(
        array_filter(
            array_map(
                static fn(string $item): string => sanitize_text_field(trim($item)),
                $items
            )
        )
    );
}

function alenumedia_get_works_per_page(): int
{
    return 6;
}

function alenumedia_get_posts_per_page(): int
{
    return 6;
}

function alenumedia_get_work_browser_tabs(): array
{
    $tabs = [
        [
            'label' => __('All', 'alenumedia'),
            'slug'  => '',
        ],
    ];

    $terms = get_terms(
        [
            'taxonomy'   => 'work_category',
            'hide_empty' => false,
            'number'     => 5,
            'orderby'    => 'count',
            'order'      => 'DESC',
        ]
    );

    if (is_wp_error($terms)) {
        return $tabs;
    }

    foreach ($terms as $term) {
        $tabs[] = [
            'label' => $term->name,
            'slug'  => $term->slug,
        ];
    }

    return array_slice($tabs, 0, 6);
}

function alenumedia_get_work_term_labels(int $post_id, int $limit = 2): array
{
    $terms = get_the_terms($post_id, 'work_category');

    if (empty($terms) || is_wp_error($terms)) {
        return [];
    }

    return array_slice(
        array_values(
            array_filter(
                array_map(
                    static fn(WP_Term $term): string => sanitize_text_field($term->name),
                    $terms
                )
            )
        ),
        0,
        $limit
    );
}

function alenumedia_get_work_gallery_images(int $post_id): array
{
    $featured_id  = (int) get_post_thumbnail_id($post_id);
    $attachments  = get_attached_media('image', $post_id);
    $gallery      = [];

    foreach ($attachments as $attachment) {
        if ($featured_id && $featured_id === (int) $attachment->ID) {
            continue;
        }

        $image_url = wp_get_attachment_image_url($attachment->ID, 'full');

        if (! $image_url) {
            continue;
        }

        $gallery[] = [
            'id'      => (int) $attachment->ID,
            'url'     => esc_url_raw($image_url),
            'alt'     => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true) ?: get_the_title($post_id),
            'caption' => wp_get_attachment_caption($attachment->ID) ?: get_the_title($post_id),
        ];
    }

    return $gallery;
}

function alenumedia_get_work_case_study_data(int $post_id): array
{
    $content_plain = trim(
        preg_replace(
            '/\s+/',
            ' ',
            wp_strip_all_tags((string) get_post_field('post_content', $post_id))
        )
    );
    $excerpt       = trim((string) get_the_excerpt($post_id));
    $word_count    = str_word_count($content_plain);
    $read_minutes  = max(1, (int) ceil($word_count / 220));
    $client        = alenumedia_get_work_meta_value($post_id, ['client', 'client_name', 'project_client']);
    $industry      = alenumedia_get_work_meta_value($post_id, ['industry', 'sector', 'project_industry']);
    $timeline      = alenumedia_get_work_meta_value($post_id, ['timeline', 'project_timeline', 'duration']);
    $services      = alenumedia_parse_work_meta_list(
        alenumedia_get_work_meta_value($post_id, ['services', 'service_list', 'project_services'])
    );
    $deliverables  = alenumedia_parse_work_meta_list(
        alenumedia_get_work_meta_value($post_id, ['deliverables', 'project_deliverables', 'outputs'])
    );
    $results       = alenumedia_parse_work_meta_list(
        alenumedia_get_work_meta_value($post_id, ['results', 'impact', 'project_results', 'highlights'])
    );
    $website_url   = alenumedia_get_work_meta_value($post_id, ['website_url', 'project_url', 'live_url']);
    $summary       = $excerpt ?: wp_trim_words($content_plain, 28);
    $meta_cards    = [];
    $gallery       = alenumedia_get_work_gallery_images($post_id);
    $taxonomy_tags = alenumedia_get_work_term_labels($post_id, 3);

    if ($client) {
        $meta_cards[] = [
            'label' => __('Client', 'alenumedia'),
            'value' => $client,
        ];
    }

    if ($industry) {
        $meta_cards[] = [
            'label' => __('Industry', 'alenumedia'),
            'value' => $industry,
        ];
    }

    if ($timeline) {
        $meta_cards[] = [
            'label' => __('Timeline', 'alenumedia'),
            'value' => $timeline,
        ];
    }

    $meta_cards[] = [
        'label' => __('Published', 'alenumedia'),
        'value' => get_the_date('F Y', $post_id),
    ];
    $meta_cards[] = [
        'label' => __('Reading Time', 'alenumedia'),
        'value' => sprintf(
            _n('%s min read', '%s min read', $read_minutes, 'alenumedia'),
            number_format_i18n($read_minutes)
        ),
    ];

    return [
        'client'       => $client,
        'industry'     => $industry,
        'timeline'     => $timeline,
        'services'     => $services,
        'deliverables' => $deliverables,
        'results'      => $results,
        'website_url'  => $website_url ? esc_url_raw($website_url) : '',
        'summary'      => $summary,
        'meta_cards'   => array_slice($meta_cards, 0, 4),
        'gallery'      => $gallery,
        'taxonomy'     => $taxonomy_tags,
        'read_minutes' => $read_minutes,
    ];
}

function alenumedia_get_work_card_markup(int $post_id): string
{
    $title     = get_the_title($post_id);
    $permalink = get_permalink($post_id);
    $excerpt   = get_the_excerpt($post_id);
    $case_study = alenumedia_get_work_case_study_data($post_id);
    $labels    = $case_study['taxonomy'];

    if (! $excerpt) {
        $excerpt = wp_trim_words(wp_strip_all_tags((string) get_post_field('post_content', $post_id)), 18);
    }

    ob_start();
    ?>
    <article class="work-card reveal reveal--up">
        <a href="<?php echo esc_url($permalink); ?>" class="work-card__image">
            <?php if (has_post_thumbnail($post_id)) : ?>
            <?php
            echo get_the_post_thumbnail(
                $post_id,
                'large',
                [
                    'loading'  => 'lazy',
                    'decoding' => 'async',
                    'sizes'    => '(max-width: 820px) 100vw, 33vw',
                ]
            );
            ?>
            <?php else : ?>
                <div class="work-card__placeholder">
                    <span><?php esc_html_e('Work System', 'alenumedia'); ?></span>
                </div>
            <?php endif; ?>
            <span class="work-card__orb"></span>
        </a>
        <div class="work-card__content">
            <div class="work-card__meta">
                <span><?php esc_html_e('Case Study', 'alenumedia'); ?></span>
                <?php foreach ($labels as $label) : ?>
                    <em><?php echo esc_html($label); ?></em>
                <?php endforeach; ?>
            </div>
            <h3><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a></h3>
            <p><?php echo esc_html($excerpt); ?></p>
            <div class="work-card__footer">
                <?php if ($case_study['client']) : ?>
                    <strong><?php echo esc_html($case_study['client']); ?></strong>
                <?php endif; ?>
                <span><?php esc_html_e('Open Case Study', 'alenumedia'); ?></span>
            </div>
        </div>
    </article>
    <?php

    return trim((string) ob_get_clean());
}

function alenumedia_render_work_browser(string $context = 'front-page'): string
{
    $query = new WP_Query(
        [
            'post_type'      => 'works',
            'posts_per_page' => alenumedia_get_works_per_page(),
        ]
    );

    $tabs = alenumedia_get_work_browser_tabs();

    ob_start();
    ?>
    <div class="works-browser works-browser--<?php echo esc_attr($context); ?>" data-works-browser data-context="<?php echo esc_attr($context); ?>">
        <div class="works-tabs reveal reveal--up" role="tablist" aria-label="<?php esc_attr_e('Work categories', 'alenumedia'); ?>">
            <?php foreach ($tabs as $index => $tab) : ?>
                <button
                    type="button"
                    class="works-tabs__button <?php echo 0 === $index ? 'is-active' : ''; ?>"
                    data-works-tab
                    data-term="<?php echo esc_attr($tab['slug']); ?>"
                    role="tab"
                    aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
                >
                    <?php echo esc_html($tab['label']); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="works-grid" data-works-grid>
            <?php if ($query->have_posts()) : ?>
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <?php echo wp_kses_post(alenumedia_get_work_card_markup(get_the_ID())); ?>
                <?php endwhile; ?>
            <?php else : ?>
                <article class="work-card work-card--empty reveal reveal--up">
                    <div class="work-card__content">
                        <span><?php esc_html_e('Works', 'alenumedia'); ?></span>
                        <h3><?php esc_html_e('Add case studies to populate this section.', 'alenumedia'); ?></h3>
                        <p><?php esc_html_e('Featured images, excerpts, categories, and project details will flow in automatically from the Works post type.', 'alenumedia'); ?></p>
                    </div>
                </article>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        </div>

        <div class="works-load-more reveal reveal--up">
            <button
                type="button"
                class="button button--solid works-load-more__button"
                data-works-load-more
                data-next-page="2"
                <?php disabled($query->max_num_pages <= 1); ?>
                <?php echo $query->max_num_pages <= 1 ? 'hidden' : ''; ?>
            >
                <?php esc_html_e('Load More Works', 'alenumedia'); ?>
            </button>
        </div>
    </div>
    <?php

    return trim((string) ob_get_clean());
}

function alenumedia_get_blog_post_card_markup(int $post_id): string
{
    $permalink  = get_permalink($post_id);
    $title      = get_the_title($post_id);
    $excerpt    = get_the_excerpt($post_id);
    $categories = get_the_category($post_id);
    $primary    = $categories[0]->name ?? __('Blog', 'alenumedia');
    $content    = wp_strip_all_tags((string) get_post_field('post_content', $post_id));

    if (! $excerpt) {
        $excerpt = wp_trim_words($content, 22);
    }

    ob_start();
    ?>
    <article class="blog-card reveal reveal--up">
        <a class="blog-card__media" href="<?php echo esc_url($permalink); ?>">
            <?php if (has_post_thumbnail($post_id)) : ?>
                <?php
                echo get_the_post_thumbnail(
                    $post_id,
                    'large',
                    [
                        'loading'  => 'lazy',
                        'decoding' => 'async',
                        'sizes'    => '(max-width: 820px) 100vw, 33vw',
                    ]
                );
                ?>
            <?php else : ?>
                <div class="blog-card__placeholder">
                    <span><?php echo esc_html($primary); ?></span>
                    <strong><?php echo esc_html($title); ?></strong>
                </div>
            <?php endif; ?>
        </a>
        <div class="blog-card__body">
            <div class="blog-card__meta">
                <span><?php echo esc_html($primary); ?></span>
                <em><?php echo esc_html(get_the_date('M j, Y', $post_id)); ?></em>
            </div>
            <h2><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a></h2>
            <p><?php echo esc_html($excerpt); ?></p>
            <a class="blog-card__link" href="<?php echo esc_url($permalink); ?>"><?php esc_html_e('Read Article', 'alenumedia'); ?></a>
        </div>
    </article>
    <?php

    return trim((string) ob_get_clean());
}

function alenumedia_register_works_route(): void
{
    register_rest_route(
        'alenumedia/v1',
        '/works',
        [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'alenumedia_handle_works_request',
            'permission_callback' => '__return_true',
            'args'                => [
                'page' => [
                    'sanitize_callback' => 'absint',
                    'default'           => 1,
                ],
                'term' => [
                    'sanitize_callback' => 'sanitize_title',
                    'default'           => '',
                ],
            ],
        ]
    );
}
add_action('rest_api_init', 'alenumedia_register_works_route');

function alenumedia_register_posts_route(): void
{
    register_rest_route(
        'alenumedia/v1',
        '/posts',
        [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'alenumedia_handle_posts_request',
            'permission_callback' => '__return_true',
            'args'                => [
                'page' => [
                    'sanitize_callback' => 'absint',
                    'default'           => 1,
                ],
            ],
        ]
    );
}
add_action('rest_api_init', 'alenumedia_register_posts_route');

function alenumedia_handle_works_request(WP_REST_Request $request): WP_REST_Response
{
    $page     = max(1, (int) $request->get_param('page'));
    $term     = sanitize_title((string) $request->get_param('term'));
    $per_page = alenumedia_get_works_per_page();
    $args     = [
        'post_type'      => 'works',
        'posts_per_page' => $per_page,
        'paged'          => $page,
    ];

    if ('' !== $term) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'work_category',
                'field'    => 'slug',
                'terms'    => $term,
            ],
        ];
    }

    $query    = new WP_Query(
        $args
    );

    $items = [];

    if ($query->have_posts()) {
        foreach ($query->posts as $post) {
            $items[] = alenumedia_get_work_card_markup($post->ID);
        }
    }

    wp_reset_postdata();

    return new WP_REST_Response(
        [
            'html'      => $items,
            'has_more'  => $page < (int) $query->max_num_pages,
            'next_page' => $page + 1,
            'count'     => count($items),
        ],
        200
    );
}

function alenumedia_handle_posts_request(WP_REST_Request $request): WP_REST_Response
{
    $page  = max(1, (int) $request->get_param('page'));
    $query = new WP_Query(
        [
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => alenumedia_get_posts_per_page(),
            'paged'               => $page,
            'ignore_sticky_posts' => true,
        ]
    );

    $items = [];

    if ($query->have_posts()) {
        foreach ($query->posts as $post) {
            $items[] = alenumedia_get_blog_post_card_markup($post->ID);
        }
    }

    wp_reset_postdata();

    return new WP_REST_Response(
        [
            'html'      => $items,
            'has_more'  => $page < (int) $query->max_num_pages,
            'next_page' => $page + 1,
            'count'     => count($items),
        ],
        200
    );
}
