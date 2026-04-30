<?php
/**
 * Theme header.
 *
 * @package AlenuMedia
 */

if (! defined('ABSPATH')) {
    exit;
}

$home_options = alenumedia_get_home_options();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="site-shell">
    <div class="site-loader" aria-hidden="true">
        <div class="site-loader__core">
            <div class="site-loader__pulse" aria-hidden="true">
                <span class="site-loader__ring"></span>
                <span class="site-loader__ring"></span>
                <span class="site-loader__ring"></span>
            </div>
            <?php echo alenumedia_get_logo_markup($home_options['logo_text']); ?>
            <span class="site-loader__label"><?php esc_html_e('Initializing experience', 'alenumedia'); ?></span>
        </div>
    </div>
