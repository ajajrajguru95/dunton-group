<?php
// Enqueue styles from the parent theme
function my_theme_child_enqueue_styles() {
    // wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('hello-elementor'));
}
add_action('wp_enqueue_scripts', 'my_theme_child_enqueue_styles');



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
