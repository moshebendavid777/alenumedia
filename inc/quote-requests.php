<?php
/**
 * Quote request data model and submission route.
 *
 * @package AlenuMedia
 */

if (! defined('ABSPATH')) {
    exit;
}

function alenumedia_register_quote_request_type(): void
{
    register_post_type(
        'quote_request',
        [
            'labels'        => [
                'name'          => __('Quote Requests', 'alenumedia'),
                'singular_name' => __('Quote Request', 'alenumedia'),
            ],
            'public'        => false,
            'show_ui'       => true,
            'show_in_menu'  => true,
            'menu_icon'     => 'dashicons-email-alt2',
            'supports'      => ['title'],
            'capability_type' => 'post',
            'map_meta_cap'  => true,
        ]
    );
}
add_action('init', 'alenumedia_register_quote_request_type');

function alenumedia_register_quote_request_meta_box(): void
{
    add_meta_box(
        'alenumedia-quote-request-details',
        __('Request Details', 'alenumedia'),
        'alenumedia_render_quote_request_meta_box',
        'quote_request',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'alenumedia_register_quote_request_meta_box');

function alenumedia_render_quote_request_meta_box(WP_Post $post): void
{
    $fields = [
        'quote_name'         => __('Name', 'alenumedia'),
        'quote_email'        => __('Email', 'alenumedia'),
        'quote_phone'        => __('Phone', 'alenumedia'),
        'quote_company'      => __('Company', 'alenumedia'),
        'quote_budget'       => __('Budget', 'alenumedia'),
        'quote_project_type' => __('Project Type', 'alenumedia'),
        'quote_message'      => __('Message', 'alenumedia'),
    ];
    echo '<table class="widefat striped"><tbody>';
    foreach ($fields as $meta_key => $label) {
        $value = get_post_meta($post->ID, $meta_key, true);
        echo '<tr><th style="width:180px;">' . esc_html($label) . '</th><td>' . nl2br(esc_html((string) $value)) . '</td></tr>';
    }
    echo '</tbody></table>';
}

function alenumedia_set_quote_request_columns(array $columns): array
{
    return [
        'cb'         => $columns['cb'],
        'title'      => __('Request', 'alenumedia'),
        'email'      => __('Email', 'alenumedia'),
        'company'    => __('Company', 'alenumedia'),
        'budget'     => __('Budget', 'alenumedia'),
        'project'    => __('Project Type', 'alenumedia'),
        'date'       => __('Date', 'alenumedia'),
    ];
}
add_filter('manage_quote_request_posts_columns', 'alenumedia_set_quote_request_columns');

function alenumedia_render_quote_request_columns(string $column, int $post_id): void
{
    $map = [
        'email'   => 'quote_email',
        'company' => 'quote_company',
        'budget'  => 'quote_budget',
        'project' => 'quote_project_type',
    ];

    if (isset($map[$column])) {
        echo esc_html((string) get_post_meta($post_id, $map[$column], true));
    }
}
add_action('manage_quote_request_posts_custom_column', 'alenumedia_render_quote_request_columns', 10, 2);

function alenumedia_register_quote_route(): void
{
    register_rest_route(
        'alenumedia/v1',
        '/quote',
        [
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => 'alenumedia_handle_quote_submission',
            'permission_callback' => '__return_true',
        ]
    );
}
add_action('rest_api_init', 'alenumedia_register_quote_route');

function alenumedia_handle_quote_submission(WP_REST_Request $request): WP_REST_Response|WP_Error
{
    $params = $request->get_json_params();

    $name        = sanitize_text_field($params['name'] ?? '');
    $email       = sanitize_email($params['email'] ?? '');
    $phone       = sanitize_text_field($params['phone'] ?? '');
    $company     = sanitize_text_field($params['company'] ?? '');
    $budget      = sanitize_text_field($params['budget'] ?? '');
    $project     = sanitize_text_field($params['project_type'] ?? '');
    $message     = sanitize_textarea_field($params['message'] ?? '');
    $website     = sanitize_text_field($params['website'] ?? '');

    if ($website) {
        return new WP_REST_Response(['success' => true], 200);
    }

    if (! $name || ! is_email($email) || ! $message) {
        return new WP_Error(
            'invalid_request',
            __('Please complete the required fields.', 'alenumedia'),
            ['status' => 400]
        );
    }

    $request_ip = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $rate_key   = 'alenu_quote_' . md5($request_ip);

    if (get_transient($rate_key)) {
        return new WP_Error(
            'rate_limited',
            __('Please wait a few minutes before sending another request.', 'alenumedia'),
            ['status' => 429]
        );
    }

    $post_id = wp_insert_post(
        [
            'post_type'   => 'quote_request',
            'post_status' => 'publish',
            'post_title'  => sprintf(
                /* translators: 1: requester name, 2: date */
                __('Quote request from %1$s - %2$s', 'alenumedia'),
                $name,
                wp_date('Y-m-d H:i')
            ),
        ],
        true
    );

    if (is_wp_error($post_id)) {
        return $post_id;
    }

    $meta = [
        'quote_name'         => $name,
        'quote_email'        => $email,
        'quote_phone'        => $phone,
        'quote_company'      => $company,
        'quote_budget'       => $budget,
        'quote_project_type' => $project,
        'quote_message'      => $message,
    ];

    foreach ($meta as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }

    $options    = alenumedia_get_home_options();
    $recipient  = is_email($options['recipient_email']) ? $options['recipient_email'] : get_option('admin_email');
    $mail_title = sprintf(__('New quote request from %s', 'alenumedia'), $name);
    $mail_body  = implode(
        "\n\n",
        [
            'Name: ' . $name,
            'Email: ' . $email,
            'Phone: ' . $phone,
            'Company: ' . $company,
            'Budget: ' . $budget,
            'Project Type: ' . $project,
            'Message: ' . $message,
        ]
    );
    $headers    = ['Reply-To: ' . $name . ' <' . $email . '>'];

    wp_mail($recipient, $mail_title, $mail_body, $headers);
    set_transient($rate_key, true, 5 * MINUTE_IN_SECONDS);

    return new WP_REST_Response(
        [
            'success' => true,
            'message' => __('Request received.', 'alenumedia'),
        ],
        200
    );
}
