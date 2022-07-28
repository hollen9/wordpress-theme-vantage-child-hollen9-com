<?php
require_once('includes/helpers/string-helper.php');

function normalize_empty_atts ($atts) {
    foreach ($atts as $attribute => $value) {
        if (is_int($attribute)) {
            unset($atts[$attribute]);
            if ($value === null || $value === "") {
            } else {
                $atts[strtolower($value)] = true;
            }   
        }
    }
    return $atts;
}
// if (!function_exists('normalize_empty_atts')) {
//     function normalize_empty_atts ($atts) {
//         foreach ($atts as $attribute => $value) {
//             if (is_int($attribute)) {
//                 $atts[strtolower($value)] = true;
//                 unset($atts[$attribute]);
//             }
//         }
//         return $atts;
//     }
// }

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

function langbranch_html__subfunc($potentialLocaleStr, $atts, $stack_num = 0) {
    
    if ($stack_num > 20) {
        // Prevent Inf Loop
        return '[LangBranch Error] Stacks of langbranch_html__subfunc are over 20.';
    }

    if ( array_key_exists( $potentialLocaleStr, $atts ) ) {
        $matched_contents = $atts[$potentialLocaleStr];
        $matched_contents_strlen = strlen($matched_contents);
        if ($matched_contents_strlen === 2 || ($matched_contents_strlen === 5 && substr($matched_contents, 2, 1) === '_' ) ) {
            // It could be a locale key, like 'zh_TW' (5 + '_') or 'en' (2)
            return langbranch_html__subfunc($matched_contents, $atts, $stack_num + 1);
        }

        $potentialLocaleStr = $matched_contents;
    }
    // It's just normal content, or you can say this is the final result.
    return $potentialLocaleStr . $stack_num;
}

/**@example <caption>
 * [langbranch zh_TW]
 * 僅在中文顯示
 * [/langbranch][langbranch en_US]
 * 僅在英文顯示
 * [/langbranch][langbranch en_US zh_TW]
 * 僅在中 或 英文顯示
 * [/langbranch]
 * </caption>
 * */
function langbranch_function($atts, $content, $tag) {
	//$debug_log = '';
	$atts = normalize_empty_atts($atts);
    // foreach($atts as $key => $value) {
    //     $debug_log .= "===========<br/>[$key]=$value<br/><br/>";
    // }
    // return '<div class="langbranch">' . $debug_log . "</div>";
    
    $html = null;
    $locale = strtolower(get_locale());
    $aio_flag = $atts['aio'];
    // if (array_key_exists('aio', $atts)) {
    //     return "AIO IS SET";
    // } else {
    //     return "AIO IS NOT SET";
    // }
    
    if ($aio_flag || $content === null || $content === "") {
        // ==[Branch Mode]==
        $doneOrAborted = false;
        $matched_locale = strtolower($locale);
        $deep_limit = 5;
        $current_deep = -1;
        //return 'matched_locale = ' . $matched_locale;
        while (!$doneOrAborted) {
            
            if ($current_deep > $deep_limit) {
                if ($html === null) {
                    $html = "null";
                }
                $html .= "[LangBranch][ERR] Too many loops! ($current_deep)";
                $doneOrAborted = true;
                break;
            }
            $current_deep++;
            foreach($atts as $key => $value) {
                $lower_key = strtolower($key);
                // $debug_log .= "$key is at $value <br\>";
                if ($matched_locale == $lower_key) {
                    $html = $value;
                    $strlen_value = strlen($value);
                    if ($strlen_value === 2 || ($strlen_value === 5 && substr($value, 2, 1) === '_')) {
                        $matched_locale = strtolower($value);
                        continue;
                    } else {
                        $doneOrAborted = true;
                    }
                }
            }
        }
    } else if($atts[$locale]) {
        $html = $content;
    } else {
        $html = '';
    }

    $html = TrimHtmlBrTag($html);

    if ($html !== '') {
        return '<div class="langbranch">' . $html . '</div>';
    }
}
add_shortcode('langbranch', 'langbranch_function');