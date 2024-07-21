<?php

/*
======================================
 1.0  Enqueue Child Styles
======================================
*/
/**
 * Enqueue the child theme styles.
 */

function my_theme_child_enqueue_styles() {
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('hello-elementor'));
}
add_action('wp_enqueue_scripts', 'my_theme_child_enqueue_styles');


/*
======================================
1.1  Preload styles and scripts(If any)
======================================
*/
/**
 * Preload specified styles.
 *
 * @param string $tag    The `<link>` tag for the enqueued style.
 * @param string $handle The handle for the enqueued style.
 * @param string $href   The stylesheet's URL.
 * @param string $media  The media attribute.
 * @return string Modified `<link>` tag.
 */

// Preload styles
add_filter('style_loader_tag', 'async_styles', 10, 4);

function async_styles($tag, $handle, $href, $media)
{
    // The handles of the enqueued styles we want to preload
    $defer_scripts = array(
        'child-style',
    );
    if (in_array($handle, $defer_scripts)) {
        return '<link rel="preload" href="' . $href . '" as="style" media="' . $media . '" type="text/css" onload="this.onload = null;this.rel=\'stylesheet\'">' . "\n";
    }
    return $tag;
}


/*
======================================
 1.2  Dequeue Unnecessary styles
======================================
*/

/**
 * Dequeue unnecessary core block styles.
 */

function prefix_remove_core_block_styles()
{
    wp_dequeue_style('wp-block-columns');
    wp_dequeue_style('wp-block-column');
    wp_dequeue_style('classic-theme-styles');
}
add_action('wp_enqueue_scripts', 'prefix_remove_core_block_styles');

/**
 * Dequeue global styles.
 */
function prefix_remove_global_styles(){
    wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'prefix_remove_global_styles', 100);


/**
 * Dequeue WP block library styles.
 */
function smartwp_remove_wp_block_library_css(){
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    //wp_dequeue_style('wc-blocks-style');
}
add_action('wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100);


/*
======================================
 1.2  Customizer API settings
======================================
*/

/**
 * Add customizer settings and controls for the header.
 *
 * @param WP_Customize_Manager $wp_customize The WP_Customize_Manager object.
 */

// Header custom field for contact from customizer
function hello_theme_customize_register($wp_customize) {
    // Add a section for the header settings if it doesn't exist
    // $wp_customize->add_section('header_section', array(
    //     'title'    => __('Header Settings', 'hello-elementor'),
    //     'priority' => 30,
    // ));

    // Add the setting for the custom header text
    $wp_customize->add_setting('header_custom_text', array(
        'default'   => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'wp_kses_post', // Allow HTML content
    ));

    // Add the control for the custom header text
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'header_custom_text',
        array(
            'label'    => __('Header Contact Info', 'hello-elementor'),
            'section'  => 'hello-options', // Default customizer option key
            'settings' => 'header_custom_text',
            'type'     => 'textarea',
        )
    ));
}
add_action('customize_register', 'hello_theme_customize_register');


/*
======================================
 1.3  Custom menu item for Nav Menu
======================================
*/

/**
 * Append custom HTML from the customizer to the primary navigation menu.
 *
 * @param string   $items HTML string of the menu items.
 * @param stdClass $args  An object containing wp_nav_menu() arguments.
 * @return string Modified HTML string of the menu items.
 */

// Appending custom item from customizer to existing nav menu
function add_custom_html_to_menu($items, $args) {
    // Check if this is the primary menu (or change 'primary' to your theme location)
    if ($args->theme_location == 'menu-1') {
        $header_custom_html = get_theme_mod('header_custom_text', '');
        if ($header_custom_html) {
            $items .= '<li class="menu-item custom-header-item"><span>' . $header_custom_html . '</span></li>';
        }
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'add_custom_html_to_menu', 10, 2);


?>
