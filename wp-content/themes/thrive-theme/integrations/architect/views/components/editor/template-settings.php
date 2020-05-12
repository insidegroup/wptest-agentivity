<div id="tve-template-settings-component" class="tve-component default-visible" data-view="TemplateSettings">
	<div class="action-group">
		<div class="dropdown-header" data-prop="docked">
			<div class="group-description">
				<?php echo __( 'Template Settings', THEME_DOMAIN ); ?>
			</div>
			<i></i>
		</div>
		<div class="dropdown-content">
			<div class="tve-control" data-view="PostFormat"></div>
			<?php echo Thrive_Utils::return_part( '/integrations/architect/views/backbone/theme-main/template-editor-notice.php' ); ?>
			<div class="tve-control tve-format-control mt-10" data-key="TemplateAudio" data-initializer="audio"></div>
			<div class="tve-control tve-format-control mt-10" data-key="TemplateVideo" data-initializer="video"></div>
		</div>
	</div>
</div>
