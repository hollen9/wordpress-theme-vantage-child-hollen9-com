<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package vantage
 * @since vantage 1.0
 * @license GPL 2.0
 */

get_header(); ?>

<div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">

		<article id="post-0" class="post error404 not-found">

			<div class="entry-main">

				<?php do_action( 'vantage_entry_main_top' ); ?>

				<header class="entry-header">
					<?php if ( siteorigin_page_setting( 'page_title' ) ) : ?>
						<h1 class="entry-title"><?php echo apply_filters( 'vantage_404_title', __( "That page can't be found.", 'vantage' ) ); ?></h1>
					<?php endif; ?>
				</header><!-- .entry-header -->

				<div class="entry-content">					
					<p><?php echo apply_filters( 'vantage_404_message', __( 'Found nothing but a random meme gif, fill you with determination.', 'vantage' ) ); ?></p>

					<?php get_search_form(); ?>
					<div>
						<img id="imgMeme" src="" />
						<!-- <video id="video" width="320" height="240" controls>
							<source id="videosrcMeme" type="video/mp4" src="">						
							Your browser does not support the video tag.
						</video> -->
					</div>
					<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

					<div class="widget">
						<h2 class="widgettitle"><?php _e( 'Most Used Categories', 'vantage' ); ?></h2>
						<ul>
						<?php wp_list_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'show_count' => 1, 'title_li' => '', 'number' => 10 ) ); ?>
						</ul>
					</div><!-- .widget -->

					<?php
					$archive_content = '<p>' . __( 'Try looking in the monthly archives.', 'vantage' ) . '</p>';
					the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );
					?>

					<?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>

				</div><!-- .entry-content -->

				<?php do_action( 'vantage_entry_main_bottom' ); ?>

			</div><!-- .entry-main -->

		</article><!-- #post-0 .post .error404 .not-found -->

	</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

<?php get_footer(); ?>

<script defer>
	// /**@type HTMLVideoElement */
	// let video = document.getElementById("video");
	// /**@type HTMLSourceElement */
	// let vidsrcMeme = document.getElementById("videosrcMeme");
	
	/**@type HTMLImageElement */
	let imgMeme = document.getElementById("imgMeme");
	
	// Initiate gifLoop for set interval
	var refresh;
	// Duration count in seconds
	const duration = 1000 * 10;
	// Giphy API defaults
	const giphy = {
		baseURL: "https://api.giphy.com/v1/gifs/",
		apiKey: "0UTRbFtkMxAplrohufYco5IY74U8hOes",
		tag: "fail",
		type: "random",
		rating: "pg-13"
	};
	// Target gif-wrap container
	//const $gif_wrap = $("#gif-wrap");
	// Giphy API URL
	let giphyURL = encodeURI(
		giphy.baseURL +
			giphy.type +
			"?api_key=" +
			giphy.apiKey +
			"&tag=" +
			giphy.tag +
			"&rating=" +
			giphy.rating
	);

	// Call Giphy API and render data
	// var newGif = () => $.getJSON(giphyURL, json => renderGif(json.data));

	const getGifFromGiphy = async () => {
		const response = await fetch(giphyURL);
		const json = await response.json(); //extract JSON from the http response
		console.log("gif: " + JSON.stringify(json));
		// do something with myJson
		
		imgMeme.src = json.data.images.original.url;
		imgMeme.width = json.data.images.original.width;
		imgMeme.height = json.data.images.original.height;
		// debugger;
		// video.height = json.data.images.original_mp4.height;
		// video.width = json.data.images.original_mp4.width;
		// debugger;
		// vidsrcMeme.src = json.data.images.original_mp4.mp4;
		// console.log("MP4: " + json.data.images.original_mp4.mp4);
		// debugger;
		
	}

	// Display Gif in gif wrap container
	// let renderGif = _giphy => {
	// 	console.log(_giphy);
	// 	// Set gif as bg image
	// 	// $gif_wrap.css({
	// 	// 	"background-image": 'url("' + _giphy.images.original.url + '")'
	// 	// });
	// 	gifImg.src = _giphy.images.original.url;

	// 	// Start duration countdown
	// 	// refreshRate();
	// };

	// Call for new gif after duration
	// var refreshRate = () => {
	// 	// Reset set intervals
	// 	clearInterval(refresh);
	// 	refresh = setInterval(function() {
	// 		// Call Giphy API for new gif
	// 		newGif();
	// 	}, duration);
	// };

	// Call Giphy API for new gif
	//newGif();

	getGifFromGiphy();
	
	//const newGifButton = $('#new-gif');
	
	//newGifButton.click(newGif)


</script>