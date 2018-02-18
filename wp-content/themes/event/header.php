<?php
/**
 * Displays the header content
 *
 * @package Theme Freesia
 * @subpackage Event
 * @since Event 1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<?php
$event_settings = event_get_theme_options(); ?>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter43486384 = new Ya.Metrika({
                    id:43486384,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/43486384" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-93779638-1', 'auto');
  ga('send', 'pageview');

</script>

<div id="page" class="site">
<!-- Masthead ============================================= -->
<header id="masthead" class="site-header" role="banner">
<?php 
if($event_settings['event_header_image_display'] =='top'){
	do_action('event_header_image');
}?>
		<div class="top-header">
			<div class="container clearfix">
				<?php
				if( is_active_sidebar( 'event_header_info' )) {
					dynamic_sidebar( 'event_header_info' );
				}
				if($event_settings['event_top_social_icons'] == 0):
					echo '<div class="header-social-block">';
						do_action('event_social_links');
					echo '</div>'.'<!-- end .header-social-block -->';
				endif; ?>
			</div> <!-- end .container -->
		</div> <!-- end .top-header -->
		<?php 
if($event_settings['event_header_image_display'] =='bottom'){
	do_action('event_header_image');
}?>
		<!-- Main Header============================================= -->
				<div id="sticky-header" class="clearfix">
					<div class="container clearfix">
					<?php
						do_action('event_site_branding'); ?>	
						<!-- Main Nav ============================================= -->
						<div class="menu-toggle">			
							<div class="line-one"></div>
							<div class="line-two"></div>
							<div class="line-three"></div>
						</div><!-- end .menu-toggle -->
						<?php
						if (has_nav_menu('primary')) { ?>
						<?php $args = array(
							'theme_location' => 'primary',
							'container'      => '',
							'items_wrap'     => '<ul class="menu">%3$s</ul>',
							); ?>
						<nav id="site-navigation" class="main-navigation clearfix">

							<?php wp_nav_menu($args);//extract the content from apperance-> nav menu ?>
						</nav> <!-- end #site-navigation -->
						<?php } else {// extract the content from page menu only ?>
						<nav id="site-navigation" class="main-navigation clearfix">
							<?php	wp_page_menu(array('menu_class' => 'menu')); ?>
						</nav> <!-- end #site-navigation -->
						<?php }
						$search_form = $event_settings['event_search_custom_header'];
						if (1 != $search_form) { ?>
							<div id="search-toggle" class="header-search"></div>
							<div id="search-box" class="clearfix">
								<?php get_search_form();?>
							</div>  <!-- end #search-box -->
						<?php } ?>
					</div> <!-- end .container -->
				</div> <!-- end #sticky-header -->
				<?php
				$enable_slider = $event_settings['event_enable_slider'];
						if ($enable_slider=='frontpage'|| $enable_slider=='enitresite'){
							 if(is_front_page() && ($enable_slider=='frontpage') ) {
								if($event_settings['event_slider_type'] == 'default_slider') {
										event_sticky_post_sliders();
								}else{
									if(class_exists('Event_Plus_Features')):
										do_action('event_image_sliders');
									endif;
								}
							}
							if($enable_slider=='enitresite'){
								if($event_settings['event_slider_type'] == 'default_slider') {
										event_sticky_post_sliders();
								}else{
									if(class_exists('Event_Plus_Features')):
										do_action('event_image_sliders');
									endif;
								}
							}
						} ?>
</header> <!-- end #masthead -->
<!-- Main Page Start ============================================= -->
<div id="content">
<?php if(!is_page_template('page-templates/event-corporate.php') ){ ?>
	<div class="container clearfix">
	<?php 
}
if(!(is_front_page() || is_page_template('page-templates/event-corporate.php') ) ){
	$custom_page_title = apply_filters( 'event_filter_title', '' ); ?>
		<div class="page-header">
		<?php if(is_home() ){ ?>
			<h2 class="page-title"><?php  echo event_title(); ?></h2>
		<?php }else{ ?>
			<h1 class="page-title"><?php  echo event_title(); ?></h1>
		<?php } ?>
			<!-- .page-title -->
			<?php event_breadcrumb(); ?>
			<!-- .breadcrumb -->
		</div>
		<!-- .page-header -->
<?php }
if(is_page_template('upcoming-event-template.php') || is_page_template('program-schedule-template.php') ){
 	echo '</div><!-- end .container -->';
}