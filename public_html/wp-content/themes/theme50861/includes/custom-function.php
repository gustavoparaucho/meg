<?php
	// Loads child theme textdomain
	load_child_theme_textdomain( CURRENT_THEME, CHILD_DIR . '/languages' );

	// Loads custom scripts.
	require_once( 'custom-js.php' );










/**
 * Service Box
 *
 */
if (!function_exists('service_box_shortcode')) {

	function service_box_shortcode( $atts, $content = null, $shortcodename = '' ) {
		extract(shortcode_atts(
			array(
				'title'        => '',
				'subtitle'     => '',
				'icon'         => '',
				'text'         => '',
				'btn_text'     => '',
				'btn_link'     => '',
				'btn_size'     => '',
				'target'       => '',
				'custom_class' => ''
		), $atts));

		$output =  '<div class="service-box '.$custom_class.'">';

		if($icon != 'no'){
			$icon_url = CHERRY_PLUGIN_URL . 'includes/images/' . strtolower($icon) . '.png' ;
			if( defined ('CHILD_DIR') ) {
				if(file_exists(CHILD_DIR.'/images/'.strtolower($icon).'.png')){
					$icon_url = CHILD_URL.'/images/'.strtolower($icon).'.png';
				}
			}
			$output .= '<figure class="icon"><img src="'.$icon_url.'" alt="" /></figure>';
		}

		$output .= '<div class="service-box_body">';

		if ($btn_link!="") {
			if ($title!="") {
				$output .= '<h2 class="title"><a href="'.$btn_link.'" title="'.$btn_text.'">';
				$output .= $title;
				$output .= '</a></h2>';
			}
		} else {
			if ($title!="") {
				$output .= '<h2 class="title">';
				$output .= $title;
				$output .= '</h2>';
			}
		}
		
		if ($btn_link!="") {
			if ($subtitle!="") {
				$output .= '<h5 class="sub-title"><a href="'.$btn_link.'" title="'.$btn_text.'">';
				$output .= $subtitle;
				$output .= '</a></h5>';
			}
		} else {
			if ($subtitle!="") {
				$output .= '<h5 class="sub-title">';
				$output .= $subtitle;
				$output .= '</h5>';
			}
		}
				
		if ($text!="") {
			$output .= '<div class="service-box_txt">';
			$output .= $text;
			$output .= '</div>';
		}
		if ($btn_text!="") {
			$output .=  '<div class="btn-align"><a href="'.$btn_link.'" title="'.$btn_text.'" class="btn btn-inverse btn-'.$btn_size.' btn-primary " target="'.$target.'">';
			$output .= $btn_text;
			$output .= '</a></div>';
		}
		$output .= '</div>';
		$output .= '</div><!-- /Service Box -->';

		$output = apply_filters( 'cherry_plugin_shortcode_output', $output, $atts, $shortcodename );

		return $output;
	}
	add_shortcode('service_box', 'service_box_shortcode');

}












//Recent Testimonials
if (!function_exists('shortcode_recenttesti')) {

	function shortcode_recenttesti( $atts, $content = null, $shortcodename = '' ) {
		extract(shortcode_atts(array(
				'num'           => '5',
				'thumb'         => 'true',
				'excerpt_count' => '30',
				'custom_class'  => '',
		), $atts));

		// WPML filter
		$suppress_filters = get_option('suppress_filters');

		$args = array(
				'post_type'        => 'testi',
				'numberposts'      => $num,
				'orderby'          => 'post_date',
				'suppress_filters' => $suppress_filters
			);
		$testi = get_posts($args);

		$itemcounter = 0;

		$output = '<div class="testimonials '.$custom_class.'">';

		global $post;
		global $my_string_limit_words;

		foreach ($testi as $k => $post) {
			//Check if WPML is activated
			if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
				global $sitepress;

				$post_lang = $sitepress->get_language_for_element($post->ID, 'post_testi');
				$curr_lang = $sitepress->get_current_language();
				// Unset not translated posts
				if ( $post_lang != $curr_lang ) {
					unset( $testi[$k] );
				}
				// Post ID is different in a second language Solution
				if ( function_exists( 'icl_object_id' ) ) {
					$post = get_post( icl_object_id( $post->ID, 'testi', true ) );
				}
			}
			setup_postdata( $post );
			$post_id = $post->ID;
			$excerpt = get_the_content();

			// Get custom metabox value.
			$testiname  = get_post_meta( $post_id, 'my_testi_caption', true );
			$testiurl   = esc_url( get_post_meta( $post_id, 'my_testi_url', true ) );
			$testiinfo  = get_post_meta( $post_id, 'my_testi_info', true );
			$testiemail = sanitize_email( get_post_meta( $post_id, 'my_testi_email', true ) );

			$attachment_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
			$url            = $attachment_url['0'];
			$image          = aq_resize($url, 390, 282, true);

			$output .= '<div class="testi-item list-item-'.$itemcounter.'">';
			
				$output .= '<blockquote class="testi-item_blockquote">';
					if ($thumb == 'true') {
						if ( has_post_thumbnail( $post_id ) ){
							$output .= '<figure class="featured-thumbnail"><a href="'.get_permalink( $post_id ).'">';
							$output .= '<img src="'.$image.'" alt="" />';
							$output .= '</a></figure>';
						}
					}
					
					$output .= '<small class="testi-meta">';
						if ( !empty( $testiname ) ) {
							$output .= '<span class="user"><a href="'.get_permalink( $post_id ).'">';
								$output .= $testiname;
							$output .= '</a></span>';
						}
					$output .= '</small>';
					
					$output .= '<p>';
						$output .= my_string_limit_words($excerpt,$excerpt_count);
					$output .= '</p><div class="clear"></div>';

				$output .= '</blockquote>';

			$output .= '</div>';
			$itemcounter++;

		}
		wp_reset_postdata(); // restore the global $post variable
		$output .= '</div>';

		$output = apply_filters( 'cherry_plugin_shortcode_output', $output, $atts, $shortcodename );

		return $output;
	}
	add_shortcode('recenttesti', 'shortcode_recenttesti');

}












//Recent Posts
if (!function_exists('shortcode_recent_posts')) {

	function shortcode_recent_posts( $atts, $content = null, $shortcodename = '' ) {
		extract(shortcode_atts(array(
				'type'             => 'post',
				'category'         => '',
				'custom_category'  => '',
				'tag'              => '',
				'post_format'      => 'standard',
				'num'              => '5',
				'meta'             => 'true',
				'thumb'            => 'true',
				'thumb_width'      => '120',
				'thumb_height'     => '120',
				'more_text_single' => '',
				'excerpt_count'    => '0',
				'custom_class'     => ''
		), $atts));

		$output = '<ul class="recent-posts '.$custom_class.' unstyled">';

		global $post;
		global $my_string_limit_words;
		$item_counter = 0;
		// WPML filter
		$suppress_filters = get_option('suppress_filters');

		if($post_format == 'standard') {

			$args = array(
						'post_type'         => $type,
						'category_name'     => $category,
						'tag'               => $tag,
						$type . '_category' => $custom_category,
						'numberposts'       => $num,
						'orderby'           => 'post_date',
						'order'             => 'DESC',
						'tax_query'         => array(
						'relation'          => 'AND',
							array(
								'taxonomy' => 'post_format',
								'field'    => 'slug',
								'terms'    => array('post-format-aside', 'post-format-gallery', 'post-format-link', 'post-format-image', 'post-format-quote', 'post-format-audio', 'post-format-video'),
								'operator' => 'NOT IN'
							)
						),
						'suppress_filters' => $suppress_filters
					);

		} else {

			$args = array(
				'post_type'         => $type,
				'category_name'     => $category,
				'tag'               => $tag,
				$type . '_category' => $custom_category,
				'numberposts'       => $num,
				'orderby'           => 'post_date',
				'order'             => 'DESC',
				'tax_query'         => array(
				'relation'          => 'AND',
					array(
						'taxonomy' => 'post_format',
						'field'    => 'slug',
						'terms'    => array('post-format-' . $post_format)
					)
				),
				'suppress_filters' => $suppress_filters
			);
		}

		$latest = get_posts($args);

		foreach($latest as $k => $post) {
				//Check if WPML is activated
				if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
					global $sitepress;

					$post_lang = $sitepress->get_language_for_element($post->ID, 'post_' . $type);
					$curr_lang = $sitepress->get_current_language();
					// Unset not translated posts
					if ( $post_lang != $curr_lang ) {
						unset( $latest[$k] );
					}
					// Post ID is different in a second language Solution
					if ( function_exists( 'icl_object_id' ) ) {
						$post = get_post( icl_object_id( $post->ID, $type, true ) );
					}
				}
				setup_postdata($post);
				$excerpt        = get_the_excerpt();
				$attachment_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
				$url            = $attachment_url['0'];
				$image          = aq_resize($url, $thumb_width, $thumb_height, true);

				$post_classes = get_post_class();
				foreach ($post_classes as $key => $value) {
					$pos = strripos($value, 'tag-');
					if ($pos !== false) {
						unset($post_classes[$key]);
					}
				}
				$post_classes = implode(' ', $post_classes);

				$output .= '<li class="recent-posts_li ' . $post_classes . '  list-item-' . $item_counter . ' clearfix">';

				//Aside
				if($post_format == "aside") {

					$output .= the_content($post->ID);

				} elseif ($post_format == "link") {

					$url =  get_post_meta(get_the_ID(), 'tz_link_url', true);

					$output .= '<a target="_blank" href="'. $url . '">';
					$output .= get_the_title($post->ID);
					$output .= '</a>';

				//Quote
				} elseif ($post_format == "quote") {

					$quote =  get_post_meta(get_the_ID(), 'tz_quote', true);

					$output .= '<div class="quote-wrap clearfix">';

							$output .= '<blockquote>';
								$output .= $quote;
							$output .= '</blockquote>';

					$output .= '</div>';

				//Image
				} elseif ($post_format == "image") {

				if (has_post_thumbnail() ) :

					// $lightbox = get_post_meta(get_the_ID(), 'tz_image_lightbox', TRUE);

					$src      = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), array( '9999','9999' ), false, '' );

					$thumb    = get_post_thumbnail_id();
					$img_url  = wp_get_attachment_url( $thumb,'full'); //get img URL
					$image    = aq_resize( $img_url, 200, 120, true ); //resize & crop img


					$output .= '<figure class="thumbnail featured-thumbnail large">';
						$output .= '<a class="image-wrap" rel="prettyPhoto" title="' . get_the_title($post->ID) . '" href="' . $src[0] . '">';
						$output .= '<img src="' . $image . '" alt="' . get_the_title($post->ID) .'" />';
						$output .= '<span class="zoom-icon"></span></a>';
					$output .= '</figure>';

				endif;


				//Audio
				} elseif ($post_format == "audio") {

					$template_url = get_template_directory_uri();
					$id           = $post->ID;

					// get audio attribute
					$audio_title  = get_post_meta(get_the_ID(), 'tz_audio_title', true);
					$audio_artist = get_post_meta(get_the_ID(), 'tz_audio_artist', true);
					$audio_format = get_post_meta(get_the_ID(), 'tz_audio_format', true);
					$audio_url    = get_post_meta(get_the_ID(), 'tz_audio_url', true);

					// Get the URL to the content area.
					$content_url = untrailingslashit( content_url() );

					// Find latest '/' in content URL.
					$last_slash_pos = strrpos( $content_url, '/' );

					// 'wp-content' or something else.
					$content_dir_name = substr( $content_url, $last_slash_pos - strlen( $content_url ) + 1 );

					$pos = strpos( $audio_url, $content_dir_name );

					if ( false === $pos ) {
						$file = $audio_url;
					} else {
						$audio_new = substr( $audio_url, $pos + strlen( $content_dir_name ), strlen( $audio_url ) - $pos );
						$file     = $content_url . $audio_new;
					}

					$output .= '<script type="text/javascript">
						jQuery(document).ready(function(){
							var myPlaylist_'. $id.'  = new jPlayerPlaylist({
							jPlayer: "#jquery_jplayer_'. $id .'",
							cssSelectorAncestor: "#jp_container_'. $id .'"
							}, [
							{
								title:"'. $audio_title .'",
								artist:"'. $audio_artist .'",
								'. $audio_format .' : "'. stripslashes(htmlspecialchars_decode($file)) .'"}
							], {
								playlistOptions: {enableRemoveControls: false},
								ready: function () {jQuery(this).jPlayer("setMedia", {'. $audio_format .' : "'. stripslashes(htmlspecialchars_decode($file)) .'", poster: "'. $image .'"});
							},
							swfPath: "'. $template_url .'/flash",
							supplied: "'. $audio_format .', all",
							wmode:"window"
							});
						});
						</script>';

					$output .= '<div id="jquery_jplayer_'.$id.'" class="jp-jplayer"></div>
								<div id="jp_container_'.$id.'" class="jp-audio">
									<div class="jp-type-single">
										<div class="jp-gui">
											<div class="jp-interface">
												<div class="jp-progress">
													<div class="jp-seek-bar">
														<div class="jp-play-bar"></div>
													</div>
												</div>
												<div class="jp-duration"></div>
												<div class="jp-time-sep"></div>
												<div class="jp-current-time"></div>
												<div class="jp-controls-holder">
													<ul class="jp-controls">
														<li><a href="javascript:;" class="jp-previous" tabindex="1" title="'.__('Previous', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Previous', CHERRY_PLUGIN_DOMAIN).'</span></a></li>
														<li><a href="javascript:;" class="jp-play" tabindex="1" title="'.__('Play', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Play', CHERRY_PLUGIN_DOMAIN).'</span></a></li>
														<li><a href="javascript:;" class="jp-pause" tabindex="1" title="'.__('Pause', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Pause', CHERRY_PLUGIN_DOMAIN).'</span></a></li>
														<li><a href="javascript:;" class="jp-next" tabindex="1" title="'.__('Next', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Next', CHERRY_PLUGIN_DOMAIN).'</span></a></li>
														<li><a href="javascript:;" class="jp-stop" tabindex="1" title="'.__('Stop', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Stop', CHERRY_PLUGIN_DOMAIN).'</span></a></li>
													</ul>
													<div class="jp-volume-bar">
														<div class="jp-volume-bar-value"></div>
													</div>
													<ul class="jp-toggles">
														<li><a href="javascript:;" class="jp-mute" tabindex="1" title="'.__('Mute', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Mute', CHERRY_PLUGIN_DOMAIN).'</span></a></li>
														<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="'.__('Unmute', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Unmute', CHERRY_PLUGIN_DOMAIN).'</span></a></li>
													</ul>
												</div>
											</div>
											<div class="jp-no-solution">
												<span>'.__('Update Required.', CHERRY_PLUGIN_DOMAIN).'</span>'.__('To play the media you will need to either update your browser to a recent version or update your ', CHERRY_PLUGIN_DOMAIN).'<a href="http://get.adobe.com/flashplayer/" target="_blank">'.__('Flash plugin', CHERRY_PLUGIN_DOMAIN).'</a>
											</div>
										</div>
									</div>
									<div class="jp-playlist">
										<ul>
											<li></li>
										</ul>
									</div>
								</div>';


				$output .= '<div class="entry-content">';
					$output .= get_the_content($post->ID);
				$output .= '</div>';

				//Video
				} elseif ($post_format == "video") {

					$template_url = get_template_directory_uri();
					$id           = $post->ID;

					// get video attribute
					$video_title  = get_post_meta(get_the_ID(), 'tz_video_title', true);
					$video_artist = get_post_meta(get_the_ID(), 'tz_video_artist', true);
					$embed        = get_post_meta(get_the_ID(), 'tz_video_embed', true);
					$m4v_url      = get_post_meta(get_the_ID(), 'tz_m4v_url', true);
					$ogv_url      = get_post_meta(get_the_ID(), 'tz_ogv_url', true);

					// Get the URL to the content area.
					$content_url = untrailingslashit( content_url() );

					// Find latest '/' in content URL.
					$last_slash_pos = strrpos( $content_url, '/' );

					// 'wp-content' or something else.
					$content_dir_name = substr( $content_url, $last_slash_pos - strlen( $content_url ) + 1 );

					$pos1     = strpos($m4v_url, $content_dir_name);
					if ($pos1 === false) {
						$file1 = $m4v_url;
					} else {
						$m4v_new  = substr($m4v_url, $pos1+strlen($content_dir_name), strlen($m4v_url) - $pos1);
						$file1    = $content_url.$m4v_new;
					}

					$pos2     = strpos($ogv_url, $content_dir_name);
					if ($pos2 === false) {
						$file2 = $ogv_url;
					} else {
						$ogv_new  = substr($ogv_url, $pos2+strlen($content_dir_name), strlen($ogv_url) - $pos2);
						$file2    = $content_url.$ogv_new;
					}

					// get thumb
					if(has_post_thumbnail()) {
						$thumb   = get_post_thumbnail_id();
						$img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
						$image   = aq_resize( $img_url, 770, 380, true ); //resize & crop img
					}

					if ($embed == '') {
						$output .= '<script type="text/javascript">
							jQuery(document).ready(function(){
								var
									jPlayerObj = jQuery("#jquery_jplayer_'. $id.'")
								,	jPlayerContainer = jQuery("#jp_container_'. $id.'")
								,	isPause = true	
								;
								jPlayerObj.jPlayer({
									ready: function () {
										jQuery(this).jPlayer("setMedia", {
											m4v: "'. stripslashes(htmlspecialchars_decode($file1)) .'",
											ogv: "'. stripslashes(htmlspecialchars_decode($file2)) .'",
											poster: "'. $image .'"
										});
									},
									swfPath: "'. $template_url .'/flash",
									solution: "flash, html",
									supplied: "ogv, m4v, all",
									cssSelectorAncestor: "#jp_container_'. $id.'",
									size: {
										width: "100%",
										height: "100%"
									}
								});
								jPlayerObj.on(jQuery.jPlayer.event.ready + ".jp-repeat", function(event) {
									jQuery("img", this).addClass("poster");
									jQuery("video", this).addClass("video");
									jQuery("object", this).addClass("flashObject");
									jQuery(".video", jPlayerContainer).on("click", function(){
										jPlayerObj.jPlayer("pause");
									})
								})
								jPlayerObj.on(jQuery.jPlayer.event.ended + ".jp-repeat", function(event) {
									isPause = true
									jQuery(".poster", jPlayerContainer).css({display:"inline"});
								    jQuery(".video", jPlayerContainer).css({width:"0%", height:"0%"});
								    jQuery(".flashObject", jPlayerContainer).css({width:"0%", height:"0%"});
								    jPlayerObj.siblings(".jp-gui").find(".jp-video-play").css({display:"block"});
								});
								jPlayerObj.on(jQuery.jPlayer.event.play + ".jp-repeat", function(event) {
								   isPause = false
								   emulSwitch(isPause);
								});
								jPlayerObj.on(jQuery.jPlayer.event.pause + ".jp-repeat", function(event) {
								   isPause = true
								   emulSwitch(isPause);
								});
								function emulSwitch(_pause){
									if(_pause){
										jQuery(".poster", jPlayerContainer).css({display:"none"});
								    	jQuery(".video", jPlayerContainer).css({width:"100%", height:"100%"});
								    	jQuery(".flashObject", jPlayerContainer).css({width:"100%", height:"100%"});
								    	jPlayerObj.siblings(".jp-gui").find(".jp-video-play").css({display:"block"});
									}else{
										jQuery(".poster", jPlayerContainer).css({display:"none"});
								    	jQuery(".video", jPlayerContainer).css({width:"100%", height:"100%"});
								    	jQuery(".flashObject", jPlayerContainer).css({width:"100%", height:"100%"});
								    	jPlayerObj.siblings(".jp-gui").find(".jp-video-play").css({display:"none"});
									}
								}
							});
							</script>';
							$output .= '<div id="jp_container_'. $id .'" class="jp-video fullwidth">';
							$output .= '<div class="jp-type-list-parent">';
							$output .= '<div class="jp-type-single">';
							$output .= '<div id="jquery_jplayer_'. $id .'" class="jp-jplayer"></div>';
							$output .= '<div class="jp-gui">';
							$output .= '<div class="jp-video-play">';
							$output .= '<a href="javascript:;" class="jp-video-play-icon" tabindex="1" title="'.__('Play', CHERRY_PLUGIN_DOMAIN).'">'.__('Play', CHERRY_PLUGIN_DOMAIN).'</a></div>';
							$output .= '<div class="jp-interface">';
							$output .= '<div class="jp-progress">';
							$output .= '<div class="jp-seek-bar">';
							$output .= '<div class="jp-play-bar">';
							$output .= '</div></div></div>';
							$output .= '<div class="jp-duration"></div>';
							$output .= '<div class="jp-time-sep">/</div>';
							$output .= '<div class="jp-current-time"></div>';
							$output .= '<div class="jp-controls-holder">';
							$output .= '<ul class="jp-controls">';
							$output .= '<li><a href="javascript:;" class="jp-play" tabindex="1" title="'.__('Play', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Play', CHERRY_PLUGIN_DOMAIN).'</span></a></li>';
							$output .= '<li><a href="javascript:;" class="jp-pause" tabindex="1" title="'.__('Pause', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Pause', CHERRY_PLUGIN_DOMAIN).'</span></a></li>';
							$output .= '<li class="li-jp-stop"><a href="javascript:;" class="jp-stop" tabindex="1" title="'.__('Stop', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Stop', CHERRY_PLUGIN_DOMAIN).'</span></a></li>';
							$output .= '</ul>';
							$output .= '<div class="jp-volume-bar">';
							$output .= '<div class="jp-volume-bar-value">';
							$output .= '</div></div>';
							$output .= '<ul class="jp-toggles">';
							$output .= '<li><a href="javascript:;" class="jp-mute" tabindex="1" title="'.__('Mute', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Mute', CHERRY_PLUGIN_DOMAIN).'</span></a></li>';
							$output .= '<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="'.__('Unmute', CHERRY_PLUGIN_DOMAIN).'"><span>'.__('Unmute', CHERRY_PLUGIN_DOMAIN).'</span></a></li>';
							$output .= '</ul>';
							$output .= '</div></div>';
							$output .= '<div class="jp-no-solution">';
							$output .= '<span>'.__('Update Required.', CHERRY_PLUGIN_DOMAIN).'</span>'.__('To play the media you will need to either update your browser to a recent version or update your ', CHERRY_PLUGIN_DOMAIN).'<a href="http://get.adobe.com/flashplayer/" target="_blank">'.__('Flash plugin', CHERRY_PLUGIN_DOMAIN).'</a>';
							$output .= '</div></div></div></div>';
							$output .= '</div>';
					} else {
						$output .= '<div class="video-wrap">' . stripslashes(htmlspecialchars_decode($embed)) . '</div>';
					}

					if($excerpt_count >= 1){
						$output .= '<div class="excerpt">';
							$output .= my_string_limit_words($excerpt,$excerpt_count);
						$output .= '</div>';
				}

				//Standard
				} else {
				
					$output .= '<div class="post_block"><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';

					if ($thumb == 'true') {
						if ( has_post_thumbnail($post->ID) ){
							$output .= '<figure class="thumbnail featured-thumbnail">';
							$output .= '<img src="'.$image.'" alt="' . get_the_title($post->ID) .'"/>';
							$output .= '</figure>';
						}
					}
					
					$output .= '<h5>';
							$output .= get_the_title($post->ID);
					$output .= '</h5>';
					
					$output .= '</a></div>';
					
					if ($meta == 'true') {
							$output .= '<span class="meta">';
									$output .= '<span class="post-date">';
										$output .= get_the_date();
									$output .= '</span>';
									$output .= '<span class="post-comments">';
										$output .= '<a href="'.get_comments_link($post->ID).'">';
											$output .= get_comments_number($post->ID);
										$output .= '</a>';
									$output .= '</span>';
							$output .= '</span>';
					}
					$output .= cherry_get_post_networks(array('post_id' => $post->ID, 'display_title' => false, 'output_type' => 'return'));
					if ($excerpt_count >= 1) {
						$output .= '<div class="excerpt">';
							$output .= my_string_limit_words($excerpt,$excerpt_count);
						$output .= '</div>';
					}
					if ($more_text_single!="") {
						$output .= '<a href="'.get_permalink($post->ID).'" class="btn btn-primary" title="'.get_the_title($post->ID).'">';
						$output .= $more_text_single;
						$output .= '</a>';
					}
				}
			$output .= '<div class="clear"></div>';
			$item_counter ++;
			$output .= '</li><!-- .entry (end) -->';
		}
		wp_reset_postdata(); // restore the global $post variable
		$output .= '</ul><!-- .recent-posts (end) -->';

		$output = apply_filters( 'cherry_plugin_shortcode_output', $output, $atts, $shortcodename );

		return $output;
	}
	add_shortcode('recent_posts', 'shortcode_recent_posts');
}













/**
 * Carousel Elastislide
 */
if ( !function_exists('shortcode_carousel') ) {
	function shortcode_carousel( $atts, $content = null, $shortcodename = '' ) {
		extract( shortcode_atts( array(
			'title'            => '',
			'num'              => 8,
			'type'             => 'post',
			'thumb'            => 'true',
			'thumb_width'      => 220,
			'thumb_height'     => 180,
			'more_text_single' => '',
			'category'         => '',
			'custom_category'  => '',
			'excerpt_count'    => 12,
			'date'             => '',
			'author'           => '',
			'comments'         => '',
			'min_items'        => 3,
			'spacer'           => 18,
			'custom_class'     => ''
		), $atts) );

		switch ( strtolower( str_replace(' ', '-', $type) ) ) {
			case 'blog':
				$type = 'post';
				break;
			case 'portfolio':
				$type = 'portfolio';
				break;
			case 'testimonial':
				$type = 'testi';
				break;
			case 'services':
				$type = 'services';
				break;
			case 'our-team':
				$type = 'team';
			break;
		}

		$carousel_uniqid = uniqid();
		$thumb_width     = absint( $thumb_width );
		$thumb_height    = absint( $thumb_height );
		$excerpt_count   = absint( $excerpt_count );
		$itemcount = 0;

		$output = '<div class="carousel-wrap ' . $custom_class . '">';
			if ( !empty( $title{0} ) ) {
				$output .= '<h2>' . esc_html( $title ) . '</h2>';
			}
			$output .= '<div id="carousel-' . $carousel_uniqid . '" class="es-carousel-wrapper">';
			$output .= '<div class="es-carousel">';
				$output .= '<ul class="es-carousel_list unstyled clearfix">';

					// WPML filter
					$suppress_filters = get_option( 'suppress_filters' );

					$args = array(
						'post_type'         => $type,
						'category_name'     => $category,
						$type . '_category' => $custom_category,
						'numberposts'       => $num,
						'orderby'           => 'post_date',
						'order'             => 'DESC',
						'suppress_filters'  => $suppress_filters
					);

					global $post; // very important
					$carousel_posts = get_posts( $args );

					foreach ( $carousel_posts as $key => $post ) {
						$post_id = $post->ID;

						//Check if WPML is activated
						if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
							global $sitepress;

							$post_lang = $sitepress->get_language_for_element( $post_id, 'post_' . $type );
							$curr_lang = $sitepress->get_current_language();
							// Unset not translated posts
							if ( $post_lang != $curr_lang ) {
								unset( $carousel_posts[$j] );
							}
							// Post ID is different in a second language Solution
							if ( function_exists( 'icl_object_id' ) ) {
								$post = get_post( icl_object_id( $post_id, $type, true ) );
							}
						}
						setup_postdata( $post ); // very important
						$post_title      = esc_html( get_the_title( $post_id ) );
						$post_title_attr = esc_attr( strip_tags( get_the_title( $post_id ) ) );
						$format          = get_post_format( $post_id );
						$format          = (empty( $format )) ? 'format-standart' : 'format-' . $format;
						if ( get_post_meta( $post_id, 'tz_link_url', true ) ) {
							$post_permalink = ( $format == 'format-link' ) ? esc_url( get_post_meta( $post_id, 'tz_link_url', true ) ) : get_permalink( $post_id );
						} else {
							$post_permalink = get_permalink( $post_id );
						}
						if ( has_excerpt( $post_id ) ) {
							$excerpt = wp_strip_all_tags( get_the_excerpt() );
						} else {
							$excerpt = wp_strip_all_tags( strip_shortcodes (get_the_content() ) );
						}

						$output .= '<li class="es-carousel_li ' . $format . ' clearfix list-item-'.$itemcount.'">';

							if ( $thumb == 'true' ) :

								if ( has_post_thumbnail( $post_id ) ) {
									$attachment_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
									$url            = $attachment_url['0'];
									$image          = aq_resize($url, $thumb_width, $thumb_height, true);

									$output .= '<figure class="featured-thumbnail">';
										$output .= '<a href="' . $post_permalink . '" title="' . $post_title . '">';
											$output .= '<img src="' . $image . '" alt="' . $post_title . '" />';
										$output .= '</a>';
									$output .= '</figure>';

								} else {

									$attachments = get_children( array(
										'orderby'        => 'menu_order',
										'order'          => 'ASC',
										'post_type'      => 'attachment',
										'post_parent'    => $post_id,
										'post_mime_type' => 'image',
										'post_status'    => null,
										'numberposts'    => 1
									) );
									if ( $attachments ) {
										foreach ( $attachments as $attachment_id => $attachment ) {
											$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' );
											$img              = aq_resize( $image_attributes[0], $thumb_width, $thumb_height, true );
											$alt              = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );

											$output .= '<figure class="featured-thumbnail">';
													$output .= '<a href="' . $post_permalink.'" title="' . $post_title . '">';
														$output .= '<img src="' . $img . '" alt="' . $alt . '" />';
												$output .= '</a>';
											$output .= '</figure>';
										}
									}
								}

							endif;

							$output .= '<div class="desc">';

								// post date
								if ( $date == 'yes' ) {
									$output .= '<time datetime="' . get_the_time( 'Y-m-d\TH:i:s', $post_id ) . '">' . get_the_date() . '</time>';
								}

								// post author
								if ( $author == 'yes' ) {
									$output .= '<em class="author">&nbsp;<span>' . __('by', CHERRY_PLUGIN_DOMAIN) . '</span>&nbsp;<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">' . get_the_author_meta( 'display_name' ) . '</a> </em>';
								}

								// post comment count
								if ( $comments == 'yes' ) {
									$comment_count = $post->comment_count;
									if ( $comment_count >= 1 ) :
										$comment_count = $comment_count . ' <span>' . __( 'Comments', CHERRY_PLUGIN_DOMAIN ) . '</span>';
									else :
										$comment_count = $comment_count . ' <span>' . __( 'Comment', CHERRY_PLUGIN_DOMAIN ) . '</span>';
									endif;
									$output .= '<a href="'. $post_permalink . '#comments" class="comments_link">' . $comment_count . '</a>';
								}

								// post title
								if ( !empty($post_title{0}) ) {
									$output .= '<h5><a href="' . $post_permalink . '" title="' . $post_title_attr . '">';
										$output .= $post_title;
									$output .= '</a></h5>';
								}

								// post excerpt
								if ( !empty($excerpt{0}) ) {
									$output .= $excerpt_count > 0 ? '<p class="excerpt">' . my_string_limit_words( $excerpt, $excerpt_count ) . '</p>' : '';
								}

								// post more button
								$more_text_single = esc_html( wp_kses_data( $more_text_single ) );
								if ( $more_text_single != '' ) {
									$output .= '<a href="' . get_permalink( $post_id ) . '" class="btn btn-primary" title="' . $post_title_attr . '">';
										$output .= __( $more_text_single, CHERRY_PLUGIN_DOMAIN );
									$output .= '</a>';
								}
							$output .= '</div>';
						$output .= '</li>';
						$itemcount++;
					}
					wp_reset_postdata(); // restore the global $post variable

				$output .= '</ul>';
			$output .= '</div></div>';
			$output .= '<script>
				jQuery(document).ready(function(){
					jQuery("#carousel-' . $carousel_uniqid . '").elastislide({
						imageW  : ' . $thumb_width . ',
						minItems: ' . $min_items . ',
						speed   : 1200,
						easing  : "easeOutQuart",
						margin  : ' . $spacer . ',
						border  : 0
					});
				})';
			$output .= '</script>';
		$output .= '</div>';

		$output = apply_filters( 'cherry_plugin_shortcode_output', $output, $atts, $shortcodename );

		return $output;
	}
	add_shortcode('carousel', 'shortcode_carousel');
}













?>