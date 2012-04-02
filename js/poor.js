// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.Poor', {
		// creates control instances based on the control's id.
		// our button's id is "poor_button"
		createControl : function(id, controlManager) {
			if (id == 'poor') {
				// creates the button
				var button = controlManager.createButton('poor', {
					title : 'poor item',
    				image : '../wp-content/plugins/tinywowcolor/js/poor.png',
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Poor link', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=poor-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('poor', tinymce.plugins.Poor);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form = jQuery('<div id="poor-form"><table id="poor-table" class="form-table">\
			<tr>\
				<th><label for="poor-name">Name</label></th>\
				<td><input type="text" id="poor-name" name="name" value="" /><br />\
				<small>Item name.</small></td>\
			</tr>\
			<tr>\
				<th><label for="poor-url">Url</label></th>\
				<td><input type="text" id="poor-url" name="url" value="" /><br />\
				<small>Item url.</small>\
			</tr>\
			<tr>\
				<th><label for="poor-url">&nbsp;</label></th>\
				<td><input type="hidden" id="poor-class" name="class" value="poor" />\
				<small>&nbsp;</small>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="poor-submit" class="button-primary" value="Insert poor item" name="submit" />\
		</p>\
		</div>');
		
		var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#poor-submit').click(function(){
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
				var value = table.find('#poor-' + index).val();
				
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