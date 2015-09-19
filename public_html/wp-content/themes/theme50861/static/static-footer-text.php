<?php /* Static Name: Footer text */ ?>
<div id="footer-text" class="footer-text">
	<?php $myfooter_text = apply_filters( 'cherry_text_translate', of_get_option('footer_text'), 'footer_text' ); ?>

	<?php if($myfooter_text){?>
		<?php echo $myfooter_text; ?>
	<?php } else { ?>
		
		
	<?php } ?>
	<?php if( is_front_page() ) { ?>
		More University WordPress Themes at <a rel="nofollow" href="http://www.templatemonster.com/category/university-wordpress-themes/" target="_blank">TemplateMonster.com</a>
	<?php } ?>
</div>
