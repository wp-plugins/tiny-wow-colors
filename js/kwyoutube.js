// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.KwYouTube', {
		// creates control instances based on the control's id.
		// our button's id is "wowhead_button"
		createControl : function(id, controlManager) {
			if (id == 'youtubeborder') {
				// creates the button
				var button = controlManager.createButton('youtubeborder', {
					title : 'Youtube video Border edition',
					image : '../wp-content/plugins/tinywowcolor/js/kwyoutube.png',
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Youtube border', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=kwyoutube-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('youtubeborder', tinymce.plugins.KwYouTube);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form = jQuery('<div id="kwyoutube-form"><table id="kwyoutube-table" class="form-table">\
			<tr>\
				<th><label for="kwyoutube-id">ID or url</label></th>\
				<td><input type="text" id="kwyoutube-id" name="id" value="" /><br />\
				<small>Video ID or short url or long url from adress bar.</small></td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="kwyoutube-submit" class="button-primary" value="Insert video" name="submit" />\
		</p>\
		</div>');
		
		var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#kwyoutube-submit').click(function(){
			// defines the options and their default values
			// again, this is not the most elegant way to do this
			// but well, this gets the job done nonetheless
			var options = { 
				'id'     : ''
				};
			
			var shortcode = '[youtubeborder';
			
			for( var index in options) {
				
				idPattern = /(?:(?:[^v]+)+v.)?([^&=]{11})(?=&|$)/;
				var value = table.find('#kwyoutube-' + index).val();
				var m = idPattern.exec(value);
				
				if (m != null && m != 'undefined')
				// attaches the attribute to the shortcode only if it's different from the default value
				if ( value !== options[index] )
					shortcode += ' ' + index + '="' + m[1] + '"';
			}
			
			shortcode += ']';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})()