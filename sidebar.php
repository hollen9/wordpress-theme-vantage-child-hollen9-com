<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package vantage hollen9
 */

if ( ! in_array( siteorigin_page_setting( 'layout', 'default' ), array( 'default','full-width-sidebar' ), true ) ) return;
?>
<div id="secondary" class="widget-area" role="complementary">
	<?php do_action( 'before_sidebar' ); ?>
	<div>
		<a class="twitter-timeline" data-tweet-limit="1" data-theme="dark" href="https://twitter.com/hollen9_tw?ref_src=twsrc%5Etfw">My Twitter</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
	</div><br/>
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
	<?php do_action( 'after_sidebar' ); ?>
</div><!-- #secondary .widget-area -->
