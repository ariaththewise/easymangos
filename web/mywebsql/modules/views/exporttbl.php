<link href='cache.php?css=theme,default,alerts' rel="stylesheet" />

<div id="popup_wrapper">
	<div id="popup_contets">
    <table border="0" cellpadding="5" cellspacing="4" style="width: 100%">
		<tr>
			<td align="left" valign="top" width="100%">

			<fieldset>
				<legend><?php echo __('Export As'); ?></legend>
				<table border="0" cellspacing="10" cellpadding="5" width="100%">
					<tr><td valign="top">
					<input type='radio' name='exptype' id='insert' value="insert" checked="checked" /><label class="right" for='insert'><?php echo __('Insert Statements'); ?></label>
					</td></tr>

					<tr><td valign="top" style="padding-left:20px">
					<input type='checkbox' name='fieldnames' id='fieldnames' checked="checked" /><label class="right" for='fieldnames'><?php echo __('Include field names in query'); ?></label>
					</td></tr>

					<tr><td valign="top">
					<input type='radio' name='exptype' id='xml' value="xml" /><label class="right" for='xml'><?php echo __('XML'); ?></label>
					</td></tr>

					<tr><td valign="top">
					<input type='radio' name='exptype' id='xhtml' value="xhtml" /><label class="right" for='xhtml'><?php echo __('XHTML'); ?></label>
					</td></tr>

					<tr><td valign="top">
					<input type='radio' name='exptype' id='text' value="text" /><label class="right" for='text'><?php echo __('Plain Text (One record per line)'); ?></label>
					</td></tr>

					<tr><td valign="top" style="padding-left:20px">
					<label for='text'><?php echo __('Fields separated by:'); ?></label><input type='text' size="4" name='separator' id='separator' value="\t" class="text" style="width:30px" />
					</td></tr>
				</table>
			</fieldset>
			</td>
		</tr>
		</table>
	</div>
	<div id="popup_footer">
		<div id="popup_buttons">
			<input type='button' id="btn_export" value='<?php echo __('Export'); ?>' />
		</div>
	</div>
</div>

<script type="text/javascript" language='javascript' src="cache.php?script=common,jquery,ui,query,options,alerts"></script>
<script type="text/javascript" language="javascript">
window.title = "<?php echo __('Export Table'); ?>";
var exportType = 'exporttbl';
var tableName = '{{TABLENAME}}';
$(function() {
	$('#popup_overlay').remove();  // we do not want to show the popup overlay when form is submitted
	$('#btn_export').button().click(function() { exportData('tbl', tableName); }); 
});
</script>
