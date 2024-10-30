<?php
/*
Plugin Name: C4D Mega Menu
Plugin URI: http://coffee4dev.com/
Description: C4D Mega Menu will automatically convert your existing menu or menus into a mega menu. You can then add any WordPress widget to your menu. Please install and active C4D Plugin Manager and Redux FrameWork to enable options page.
Author: Coffee4dev.com
Author URI: http://coffee4dev.com/
Text Domain: c4d-mega-menu
Version: 2.0.2
*/

define('C4DMEGAMENU_PLUGIN_URI', plugins_url('', __FILE__));
define('C4DMEGAMENU_LOCATION', 'primary');
add_action( 'admin_enqueue_scripts', 'c4d_mega_menu_load_scripts_admin' );
add_action( 'wp_enqueue_scripts', 'c4d_mega_menu_load_scripts_site');
add_action( 'widgets_init', 'c4d_mega_menu_widget' );
add_filter( 'walker_nav_menu_start_el', 'c4d_mega_menu_walker_nav_menu_start_el', 10, 4 );
add_filter( 'nav_menu_submenu_css_class', 'c4d_mega_menu_nav_menu_submenu_css_class', 10, 3);
add_action( 'c4d-plugin-manager-section', 'c4d_mega_menu_section_options');
add_filter( 'plugin_row_meta', 'c4d_mega_menu_plugin_row_meta', 10, 2 );

function c4d_mega_menu_nav_menu_submenu_css_class( $classes, $args, $depth) {
    $a = array();
    foreach($classes as $class) {
        $a[] = 'c4d-mega-menu-'.$class;
    }
    return $classes;
}
function c4d_mega_menu_plugin_row_meta( $links, $file ) {
    if ( strpos( $file, basename(__FILE__) ) !== false ) {
        $new_links = array(
            'visit' => '<a href="http://coffee4dev.com">Visit Plugin Site</<a>',
            'forum' => '<a href="http://coffee4dev.com/forums/">Forum</<a>',
            'redux' => '<a href="https://wordpress.org/plugins/redux-framework/">Redux Framework</<a>',
            'c4dpluginmanager' => '<a href="https://wordpress.org/plugins/c4d-plugin-manager/">C4D Plugin Manager</a>'
        );
        
        $links = array_merge( $links, $new_links );
    }
    
    return $links;
}

function c4d_mega_menu_load_scripts_site() {
    if(!defined('C4DPLUGINMANAGER_OFF_JS_CSS')) {
	   wp_enqueue_script( 'c4d-mega-menu-site-js', C4DMEGAMENU_PLUGIN_URI . '/assets/default.js', array( 'jquery' ), false, true );    
	   wp_enqueue_style( 'c4d-mega-menu-site-style', C4DMEGAMENU_PLUGIN_URI.'/assets/default.css' );
    }
}
function c4d_mega_menu_load_scripts_admin($hook) {
	if ( 'nav-menus.php' == $hook ) {
    	wp_enqueue_script( 'c4d-mega-menu-admin-js', C4DMEGAMENU_PLUGIN_URI . '/assets/admin.js' );    
    	wp_enqueue_style( 'c4d-mega-menu-admin-style', C4DMEGAMENU_PLUGIN_URI.'/assets/admin.css' );
    }
}

function c4d_mega_menu_widget() {
    $locations = get_nav_menu_locations();
    foreach ($locations as $key => $location) {
        $menu = get_term( $location , 'nav_menu' );
        if (is_wp_error($menu)) return;
        if ( $items = wp_get_nav_menu_items( $menu->name ) ) {
            foreach ( $items as $item ) {
                if ( in_array( 'c4d-mega-menu', $item->classes ) ) {
                    register_sidebar( array(
                        'id'   => 'c4d-mega-menu-widget-area-' . $item->ID,
                        'name' => $item->title . ' - Mega Menu',
                        'description' => '',
                        'class' => '',
                        'before_widget' => '<div id="%1$s" class="widget %2$s">',
                        'after_widget' => "</div>\n",
                        'before_title' => '<h3 class="widgettitle">',
                        'after_title' => "</h3>\n",
                    ) );
                }
            }
        }
    }
}

function c4d_mega_menu_walker_nav_menu_start_el($item_output, $item, $depth, $args ) {
	
    if ( in_array( 'c4d-mega-menu', $item->classes ) ) {
		ob_start();
		if ( is_active_sidebar( 'c4d-mega-menu-widget-area-' . $item->ID ) ) {
            echo '<div class="c4d-mega-menu-block">';
            dynamic_sidebar( 'c4d-mega-menu-widget-area-' . $item->ID );
            echo '</div>';
        }
        $html = ob_get_contents();
        ob_end_clean();
		$item_output = $item_output.$html;
	}
	
   	return $item_output;
}

function c4d_mega_menu_section_options(){
    $opt_name = 'c4d_plugin_manager';
    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Mega Menu', 'c4d-mega-menu' ),
        'id'               => 'section-mega-menu',
        'desc'             => '',
        'customizer_width' => '400px',
        'icon'             => 'el el-home',
        'fields'           => array(
            array(
                'id'       => 'c4d-mega-menu-max-width',
                'type'     => 'dimensions',
                'units'    => array('px', '%'),
                'title'    => esc_html__('Max Width', 'c4d-mega-menu'),
                'subtitle' => esc_html__('Set max width for mega menu. Insert Number Only', 'c4d-mega-menu'),
                'width'    => true,
                'height'   => false,
                'default'  => 1200,
                'output'   => array(
                    'width' => '.c4d-mega-menu > ul.sub-menu, .c4d-mega-menu-block'
                )
            ),
            array(
                'id'       => 'c4d-mega-menu-background-color',
                'type'     => 'color',
                'title'    => esc_html__( 'Background color', 'c4d-mega-menu' ),
                'subtitle' => esc_html__( 'Set the background color for Mega Menu', 'c4d-mega-menu' ),
                'default'  => '#ffffff',
                'output'   => array(
                    'background-color' => '.c4d-mega-menu > ul.sub-menu, .c4d-mega-menu-block'
                )
            ),
            array(
                'id'          => 'c4d-mega-menu-font-level-1',
                'class'       => 'c4d-pro-version',
                'type'        => 'typography',
                'title'       => esc_html__( 'Font', 'c4d-mega-menu' ),
                'subtitle'    => esc_html__( 'Set font style for menu item.', 'c4d-mega-menu' ),
                'google'      => true,
                'font-backup' => false,
                'text-align'  => false,
                'all_styles'  => true,
                'output'      => array( implode(',', array(
                    'ul .menu-item a'
                )) ),
                'units'       => 'px',
                'default'     => array(
                    'color'       => '#000',
                    'font-style'  => '700',
                    'font-family' => 'Abel',
                    'google'      => true,
                    'font-size'   => '16px',
                    'line-height' => '24px'
                ),
            ),
            array(
                'id'          => 'c4d-mega-menu-font-level-2',
                'class'       => 'c4d-pro-version',
                'type'        => 'typography',
                'title'       => esc_html__( 'Font Sub Menu', 'c4d-mega-menu' ),
                'google'      => true,
                'font-backup' => false,
                'text-align'  => false,
                'all_styles'  => true,
                'output'      => array( implode(',', array(
                    'ul ul .menu-item a'
                )) ),
                'units'       => 'px',
               'subtitle'    => esc_html__( 'Set font style for sub menu item.', 'c4d-mega-menu' ),
                'default'     => array(
                    'color'       => '#222',
                    'font-style'  => '700',
                    'font-family' => 'Abel',
                    'google'      => true,
                    'font-size'   => '14px',
                    'line-height' => '24px'
                ),
            ),
        )
    ));
}