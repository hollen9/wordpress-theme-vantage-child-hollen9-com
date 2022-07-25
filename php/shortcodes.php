<?php
require_once('includes/basic-functions.php');

function write_server_time(){
    date_default_timezone_set('Asia/Taipei'); // CDT
    //Y-m-d H:i:s
    $date = date('m-d H:i:s') . ' (GMT+8, Taiwan)';
    return $date;
}
add_shortcode('hollen9_server_time', 'write_server_time');

function permalink_thingy($atts) {
	extract(shortcode_atts(array(
		'id' => 1,
		'text' => "",  // default value if none supplied,
        'new' => false
    ), $atts));
    
    $target = ' ';
    if ($new) {
        $target = ' target="_BLANK" ';
    } 
    if ($text) {
        $url = get_permalink($id);
        return '<a href="' . $url . '"' . $target.'>' . $text . '</a>';
    } else {
	   return get_permalink($id);
	}
}
add_shortcode('permalink', 'permalink_thingy');

// function gist_embed($atts) {
    
//     extract(shortcode_atts(array(
//         'id' => '',
//         'url' => '',
//     ), $atts));
//     $isNothing = false;
//     $result = '<script src="';
//     if ($id != '') {
//         $result .= 'https://gist.github.com/' . $id;
//     } else if ($url != '') {
//         $result .= $url;
//     } else {
//         $isNothing = true;
//     }
//     $result .= '"></script>';
//     if ($isNothing) {
//         $result = '';
//     }
//     return $result;
// }
// add_shortcode('gist', 'gist_embed');

function gist_embed($atts) {
    
    $url_or_id = $atts[0];
    // if (IsNullOrEmptyString($url_or_id)) {
    //     return 'EMPTY';
    // }
    $url = '';
    if (substr( $url_or_id, 0, 4 ) === "http") {
        // it's url
        $url = $url_or_id . '.js';
    } else {
        // it's id
        $url = 'https://gist.github.com/' . $url_or_id . '.js';
    }
    
    if ($url == '') {
        return 'NO GIST URL';
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);


    return '<script>' . $data . '</script>';
    // $assoc = json_decode($data, true);
    // //return $json;
    // // if (isset($atts['nometa'])) {
	// //    $assoc['div'] = preg_replace('/<div class="gist\-meta">.*?(<\/div>)/is', '', $assoc['div']);
	// // }
        
    // return '```\n' . $assoc['div'] . '```';
}
add_shortcode('gist', 'gist_embed');