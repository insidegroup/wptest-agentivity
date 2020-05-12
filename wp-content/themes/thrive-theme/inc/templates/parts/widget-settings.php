<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

?>

<div id="tcb-modal-widget-settings" class="tcb-modal">
	<div class="tcb-modal-content">
		<h2 class="tcb-modal-title"></h2>
		<div id="widgets-wrapper">
			<?php
			$widgets = tcb_elements()->get_external_widgets();
			$content = '';
			?>
			<?php foreach ( $widgets as $widget ) : ?>
				<form id="<?php echo 'widget_' . $widget->id_base; ?>" class="widget-form">
					<?php
					$data = [
						'widget'    => $widget,
						'form_data' => [],
					];
					echo tcb_template( 'widget-form.php', $data, true );
					?>
				</form>
			<?php endforeach; ?>
		</div>
		<div class="row padding-top-20">
			<div class="col col-xs-12">
				<button type="button" class="tcb-right tve-button medium green click" data-fn="update_widget">
					Update Widget
				</button>
			</div>
		</div>
	</div>
	<span data-fn="close" class="click tcb-modal-close"><svg class="tcb-icon tcb-icon-close2"><use xlink:href="#icon-close2"></use></svg></span>
</div>
