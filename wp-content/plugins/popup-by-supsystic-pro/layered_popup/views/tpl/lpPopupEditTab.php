<div class="ppsPopupOptRow">
	<label>
		<?php echo htmlPps::checkbox('params[tpl][enb_layered]', array(
			'checked' => htmlPps::checkedOpt($this->popup['params']['tpl'], 'enb_layered'),
			'attrs' => 'data-switch-block="layeredShell"',
		))?>
		<?php _e('Enable Layered PopUp Style', PPS_LANG_CODE)?>
	</label>
	<div class="description"><?php _e('By default all PopUps have modal style: it appears on user screen over the whole site. Layered style allows you to show your PopUp - on selected position: top, bottom, etc. and not over your site - but right near your content.', PPS_LANG_CODE)?></div>
</div>
<span data-block-to-switch="layeredShell">
	<div class="ppsPopupOptRow">
		<span class="ppsOptLabel"><?php _e('Select position for your PopUp', PPS_LANG_CODE)?></span>
		<br style="clear: both;" />
		<div id="ppsLayeredSelectPosShell">
			<div class="ppsLayeredPosCell" style="width: 30%;" data-pos="top_left"><span class="ppsLayeredPosCellContent"><?php _e('Top Left', PPS_LANG_CODE)?></span></div>
			<div class="ppsLayeredPosCell" style="width: 40%;" data-pos="top"><span class="ppsLayeredPosCellContent"><?php _e('Top', PPS_LANG_CODE)?></span></div>
			<div class="ppsLayeredPosCell" style="width: 30%;" data-pos="top_right"><span class="ppsLayeredPosCellContent"><?php _e('Top Right', PPS_LANG_CODE)?></span></div>
			<br style="clear: both;"/>
			<div class="ppsLayeredPosCell" style="width: 30%;" data-pos="center_left"><span class="ppsLayeredPosCellContent"><?php _e('Center Left', PPS_LANG_CODE)?></span></div>
			<div class="ppsLayeredPosCell" style="width: 40%;" data-pos="center"><span class="ppsLayeredPosCellContent"><?php _e('Center', PPS_LANG_CODE)?></span></div>
			<div class="ppsLayeredPosCell" style="width: 30%;" data-pos="center_right"><span class="ppsLayeredPosCellContent"><?php _e('Center Right', PPS_LANG_CODE)?></span></div>
			<br style="clear: both;"/>
			<div class="ppsLayeredPosCell" style="width: 30%;" data-pos="bottom_left"><span class="ppsLayeredPosCellContent"><?php _e('Bottom Left', PPS_LANG_CODE)?></span></div>
			<div class="ppsLayeredPosCell" style="width: 40%;" data-pos="bottom"><span class="ppsLayeredPosCellContent"><?php _e('Bottom', PPS_LANG_CODE)?></span></div>
			<div class="ppsLayeredPosCell" style="width: 30%;" data-pos="bottom_right"><span class="ppsLayeredPosCellContent"><?php _e('Bottom Right', PPS_LANG_CODE)?></span></div>
			<br style="clear: both;"/>
		</div>
		<?php echo htmlPps::hidden('params[tpl][layered_pos]')?>
	</div>
</span>
	
