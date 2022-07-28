<?php

use function TranslatePress\file_get_html;

require_once('php/includes/helpers/string-helper.php');

// function hollen9_child_theme_setup() {
//     load_child_theme_textdomain('vantage', get_stylesheet_directory() . '/languages');
// }
// add_action('after_setup_theme', 'hollen9_child_theme_setup');

/**
 * Enqueue the parent theme stylesheet.
 */
function vantage_child_enqueue_parent_style() {

    wp_dequeue_script('vantage-main');
    wp_enqueue_script('vantage-main', get_stylesheet_directory_uri() . '/js/vantage-hollen-main.js', array( 'jquery' ) );

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


function get_text_from_dom($node) {
    $text = '';
    if (!is_null($node->childNodes)) {
        foreach ($node->childNodes as $node) {
        $text = get_text_from_dom($node, $text);
        }
    }
    else {
        return $text . $node->textContent . ' ';
    }
    return $text;
}
function strip_innertext_from_trp($text){
    $doc = new DOMDocument();
    $doc->loadHTML('<?xml encoding="utf-8" ?>' . trp_translate($text));
    return $doc->documentElement->lastChild->firstChild->textContent;
    $result = get_text_from_dom($doc->documentElement);
    return $result;
}

function hollen9_locale_string($translated_text, $text, $domain) {
    $locale = get_locale();
    $locale_json_string = file_get_contents(get_stylesheet_directory() . '/hollen9-locale.json');
    $locale_json_object = json_decode($locale_json_string, true);
    $locale_json_objectProp_orderBindings = $locale_json_object['orderBindings'];
    $locale_json_objectProp_strings = $locale_json_object['strings'];

    $current_locale_idx = 0;
    for($i = 0; $i < count($locale_json_objectProp_orderBindings); $i++) {
        if ($locale_json_objectProp_orderBindings[$i] === $locale) {
            $current_locale_idx = $i;
            $i = count($locale_json_objectProp_orderBindings) + 1; //EXIT FOR LOOP
        }
    }

    switch($domain) {
        case 'hollen9-locale-string':
            $innerKeyWithoutBracket = substr($translated_text, 1, strlen($translated_text)-2);
            
            if ($locale_json_objectProp_strings[$innerKeyWithoutBracket] != null) {
                return $locale_json_objectProp_strings[$innerKeyWithoutBracket][$current_locale_idx];
            }
    }
    return $translated_text;
}
add_filter('gettext', 'hollen9_locale_string', 11, 3);
//I am Hollen9 aka 好冷酒/好冷九, running a Youtube channel named “好冷酒XD領域” that primary offer translated version of online videos or animations. I also build softwares like websites with tool like ASP.NET and/or react.js, cross platform mobile apps, chatbots, and Unity games.

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