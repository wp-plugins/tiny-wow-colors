// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.Commun', {
		// creates control instances based on the control's id.
		// our button's id is "commun_button"
		createControl : function(id, controlManager) {
			if (id == 'commun') {
				// creates the button
				var button = controlManager.createButton('commun', {
					title : 'commun item',
    				image : '../wp-content/plugins/tinywowcolor/js/commun.png',
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Commun link', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=commun-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('commun', tinymce.plugins.Commun);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form = jQuery('<div id="commun-form"><table id="commun-table" class="form-table">\
			<tr>\
				<th><label for="commun-name">Name</label></th>\
				<td><input type="text" id="commun-name" name="name" value="" /><br />\
				<small>Item name.</small></td>\
			</tr>\
			<tr>\
				<th><label for="commun-url">Url</label></th>\
				<td><input type="text" id="commun-url" name="url" value="" /><br />\
				<small>Item url.</small>\
			</tr>\
			<tr>\
				<th><label for="commun-url">&nbsp;</label></th>\
				<td><input type="hidden" id="commun-class" name="class" value="commun" />\
				<small>&nbsp;</small>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="commun-submit" class="button-primary" value="Insert commun item" name="submit" />\
		</p>\
		</div>');
		
		var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#commun-submit').click(function(){
			// defines the options and their default values
			// again, this is not the most elegant way to do this
			// but well, this gets the job done nonetheless
			var options = { 
				'name'    : '',
				'url'     : '',
				'class'   : ''
				};
			
			var shortcode = '[item';
			
			for( var index in options) {
				var value = table.find('#commun-' + index).val();
				
				// attaches the attribute to the shortcode only if it's different from the default value
				if ( value !== options[index] )
					shortcode += ' ' + index + '="' + value + '"';
			}
			
			shortcode += ']';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})()