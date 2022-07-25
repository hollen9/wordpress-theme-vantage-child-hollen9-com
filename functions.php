<?php

require_once('php/includes/basic-functions.php');

/**
 * Enqueue the parent theme stylesheet.
 */
function vantage_child_enqueue_parent_style() {
    wp_enqueue_style( 'vantage-parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'vantage_child_enqueue_parent_style', 8 );

function vantage_child_enqueue_child_sub_assets() {
	wp_enqueue_style('hollen-scss-main', get_stylesheet_directory_uri() .'/css/main.css');
    //wp_enqueue_style('md-keyboard-scss-main', get_stylesheet_directory_uri() .'/css/style-md-keyboard-main.css');
	//wp_enqueue_script('js-darkmode', 'https://unpkg.com/dark-mode-toggle');
	//wp_enqueue_script('js-darkmode', 'https://unpkg.com/darken');
	wp_enqueue_script('hollen-js-main', get_stylesheet_directory_uri() .'/js/main.js');

    //wp_enqueue_style('font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/fontawesome.min.css');
}
add_action('wp_enqueue_scripts', 'vantage_child_enqueue_child_sub_assets', 9);	


function add_favicon(){ ?>
    <!-- Custom Favicons -->
    <link rel="shortcut icon" href="<?php echo get_site_url();?>/favicon.ico"/>
    <!--<link rel="apple-touch-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon.png">-->
    <?php }
add_action('wp_head','add_favicon');

function code_pre_theme(){
    if (is_singular('post')) {

        wp_enqueue_script('prism-js', 'https://cdn.jsdelivr.net/npm/prismjs@1.28.0/prism.min.js');
        wp_enqueue_script('prism-core-js', 'https://cdn.jsdelivr.net/npm/prismjs@1.28.0/components/prism-core.min.js');
        wp_enqueue_style('prism-css', 'https://cdn.jsdelivr.net/npm/prismjs@1.28.0/themes/prism.min.css');
        wp_enqueue_script('prism-autoloader-js', 'https://cdn.jsdelivr.net/npm/prismjs@1.28.0/plugins/autoloader/prism-autoloader.min.js');
        
        
        // if (has_tag('csharp')) {
        //     wp_enqueue_script('prism-js-ext-csharp', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.28.0/components/prism-csharp.min.js');
        // }
        // if (has_tag('css')) {
        //     wp_enqueue_script('prism-js-ext-css', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.28.0/components/prism-css.min.js');
        // }
        // if (has_tag('php')) {
        //     wp_enqueue_script('prism-js-ext-php', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.28.0/components/prism-php.min.js');
        // }
    }
}
add_action('wp_enqueue_scripts', 'code_pre_theme', 10);

/*
function add_type_attribute($tag, $handle, $src) {
    // if not your script, do nothing and return original $tag
    if ( 'js-darkmode' !== $handle ) {
        return $tag;
    }
    // change the script tag by adding type="module" and return it.
    $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
    return $tag;
}
add_filter('script_loader_tag', 'add_type_attribute' , 10, 3);
*/


//Hollen Appened on 2022

get_template_part('php/shortcodes');





// function registering_custom_query_vars($query_vars)
// {
//     $query_vars[] = 'page_size'; //push
//     //$query_vars[] = 'page_size'; //push
//     return $query_vars;
// }
// add_filter('query_vars', 'registering_custom_query_vars');
add_filter('query_vars', 'parameter_queryvars' ); // Let WP accept the query argument we will use
function parameter_queryvars( $qvars )
{
    $qvars[] = 'posts_per_page';
    return $qvars;
}
add_action( 'pre_get_posts', 'change_post_per_page' ); // Filter posts based on passed query variable if set
function change_post_per_page( $query ) {
    global $wp_query;
    if ( !empty($wp_query->query['posts_per_page']) && is_numeric($wp_query->query['posts_per_page'])) {
        $query->set( 'posts_per_page', $wp_query->query['posts_per_page'] );
    }
}

add_action( 'init', 'exclude_sensitive_doc_from_stranger_search', 99 );
function exclude_sensitive_doc_from_stranger_search() {
    global $wp_post_types;

	$user = wp_get_current_user();
	if ( in_array( 'author', (array) $user->roles ) ) {
    	//The user has the "author" role
    	return;
	}
	
    if ( post_type_exists( 'documentation' ) ) {

        // exclude from search results
        $wp_post_types['documentation']->exclude_from_search = true;
    }
}

// Add search scope
function namespace_add_custom_types( $query ) {
  if( (is_category() || is_tag()) && $query->is_archive() && empty( $query->query_vars['suppress_filters'] ) ) {
    $query->set( 'post_type', array(
     'post', 'documentation'
        ));
    }
}
add_action( 'pre_get_posts', 'namespace_add_custom_types' );