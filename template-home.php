<?php
/*
 * Template Name: Hollen9 Home Template
 * Home Template
 * @author Hollen9
 * @link http://hollen9.com
 */

// ===================== Script Parts ===============================

// Configs
$wordsToTrimmed = 100;



$htmlMainBodyContent;
$htmlPaginationLinks;

while (have_posts()) {
    if ( is_page() ) {
        the_post();
        // the_content();
        $htmlMainBodyContent .= get_the_content();
    }
    $htmlMainBodyContent .= '<div class="the_loop">';
    if (get_query_var('paged')) {
        $paged = get_query_var('paged');
        //$htmlMainBodyContent .= 'paged=' . $paged; //TEST
    } elseif (get_query_var('page')) { // 'page' is used instead of 'paged' on Static Front Page
        $paged = get_query_var('page');
        //$htmlMainBodyContent .= 'page=' . $page; //TEST
    } else {
        $paged = 1;
        //$htmlMainBodyContent .= 'page force=1'; //TEST
    }

    //$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $loop = new WP_Query(
        array(
            'post_type' => 'post',
            'posts_per_page' => $page_size,
            'paged' => $paged,
            'post_status' => 'publish',
            'orderby' => 'desc',
            'orderby' => 'date' // modified | title | name | ID | rand
        )
    );

    $page_size = $loop->query_vars['posts_per_page'];
    if ($page_size == false) {
        $page_size = get_option('posts_per_page');
    }
    echo '<script>console.log("$page_size=' . $page_size . '")</script>';

    if ($loop->have_posts()) {
        while ($loop->have_posts()) {
            $loop->the_post();

            //$htmlMainBodyContent .= '<a href="' . get_the_permalink() . '" style="display: block;">';
            $htmlMainBodyContent .= '<article class="post"><a class="d-block post-block-link" href="' . get_the_permalink() . '">';
            
            $htmlMainBodyContent .= '<header class="entry-header"><h1 class="entry-title">' . get_the_title() . '</h1></header>';
            // echo the_content(); 
            
            $moreLink = '...... 恕刪，點選繼續閱讀。';

            $my_content = apply_filters('the_content', get_the_content());
            $my_content = wp_strip_all_tags($my_content);
            $htmlMainBodyContent .= wp_trim_words($my_content, $wordsToTrimmed, $moreLink);

            $htmlMainBodyContent .= '</a></article>';
        }

        if ($loop->max_num_pages > 1) { // custom pagination    
            $orig_query = $wp_query; // fix for pagination to work
            $wp_query = $loop;
            $big = 999999999;
            $htmlPaginationLinks .= paginate_links(array(
                'base' => str_replace($big, '%#%', get_pagenum_link($big)),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $wp_query->max_num_pages,
                'prev_text' => '上一頁',
                'next_text' => '下一頁'
            ));
            $wp_query = $orig_query; // fix for pagination to work
        }

        wp_reset_postdata();
    } else {
        $htmlMainBodyContent .= '<p>' . __('Sorry, no posts matched your criteria.') . '</p>';
    }
    //$htmlMainBodyContent .= $paged;
    $htmlMainBodyContent .= '</div>';

}
?>

<?php
// ===================== HTML Parts ===============================
?>

<?php get_header(); ?>
<div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">
        <div class="entry-content">
            <?=$htmlMainBodyContent;?>
        </div>
    </div>
    <div class="posts-pagination">
        <?=$htmlPaginationLinks; ?>
        <select>
            <option value="5">5</option>
            <option value="10">10</option>
        </select>
    </div>
</div>
<?php get_sidebar(); ?>
<?php get_template_part('footer'); ?>