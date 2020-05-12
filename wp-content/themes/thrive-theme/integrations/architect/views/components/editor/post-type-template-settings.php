<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
$post_type = get_post_type();
$object    = get_post_type_object( $post_type );
$name      = $object->labels->singular_name;
?>

<div id="tve-post-type-template-settings-component" class="tve-component" data-view="PostTypeTemplateSettings">
	<div class="dropdown-header" data-prop="docked">
		<div class="group-description">
			<?php echo Thrive_Utils::get_post_type_name() . ' ' . __( 'Template Settings', THEME_DOMAIN ) ?>
		</div>
		<i></i>
	</div>
	<div class="dropdown-content mb-10">
		<div class="thrive-page-template-p mb-10">
			<?php echo sprintf( __( 'Which templates should this %s use?', THEME_DOMAIN ), $name ); ?>
		</div>
		<ul class="thrive-page-template-list mb-10"></ul>
		<?php if ( $post_type === 'post' ) { ?>
			<div class="tve-control mt-10" data-view="PostFormat"></div>
			<?php echo Thrive_Utils::return_part( '/integrations/architect/views/backbone/theme-main/template-editor-notice.php' ); ?>
			<div class="tve-control tve-format-control mt-10" data-key="TemplateAudio" data-initializer="audio"></div>
			<div class="tve-control tve-format-control mt-10" data-key="TemplateVideo" data-initializer="video"></div>
		<?php } ?>
		<div class="thrive-no-post-type-templates" style="display: none">
			<div class="center-lg mb-5"><strong><?php echo sprintf( __( 'No %s Templates Found', THEME_DOMAIN ), $name ); ?></strong></div>
			<div class="mb-5"><?php echo sprintf( __( "We're displaying a list of post templates above because no '%s' templates have been found.", THEME_DOMAIN ), $name ); ?></div>
			<div><?php echo sprintf( __( "If you'd like to design a specific template for your '%s' content, you can do so from %s.", THEME_DOMAIN ),
					$name,
					'<a target="_blank" href="' . admin_url( 'admin.php?page=' . THRIVE_MENU_SLUG . '#templates' ) . '">template editor</a>.'
				); ?></div>
		</div>
	</div>
</div>
