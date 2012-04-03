<?php
/*
Plugin Name: tiny WoW item colors
Version: 1.0
Description: This is a simple plugin to add colorisation items from WoW / wowhead with buttons in TinyMCE (epic, rare, legendary, poor, commun, normal, artifact) + two buttons for youtube (youtube version 3 Black and youtube version 2 with color border and hd mode)
Author: Laurent (KwarK) Bertrand
Author URI: http://kwark.allwebtuts.net
Plugin URI: http://kwark.allwebtuts.net
License: GPL
*/

//constants
define("TINYWOW_DIR", WP_PLUGIN_URL . '/tinywowcolor/' );

if(!is_admin()){
//Enqueue style for class a + color
wp_enqueue_style( 'wowcolor', TINYWOW_DIR . 'style.css' );
}

//Init button tinyMCE
add_action('init','kw_button_action_admin_init');

function kw_button_action_admin_init() {
	// only hook up these filters if we're in the admin panel, and the current user has permission to edit posts and pages
	if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
		add_filter( 'mce_buttons', 'kw_filter_mce_button' );
		add_filter( 'mce_external_plugins', 'kw_filter_mce_plugin' );
	}
}


$temp = get_option("kwenqueuebubble");
//Pour EN GB
global $locale;
if($locale == 'fr_FR'){
$lang = 'fr';
	}
else if($locale == 'en_GB'){
$lang = 'en';
	}
//Pour EN US
else if($locale == 'en_US'){
$lang = 'en';
	}
//Pour ES
else if($locale == 'es_ES'){
$lang = 'es';
	}
//Pour DE
else if($locale == 'de_DE'){
$lang = 'de';
	}
//Pour RU
else if($locale == 'ru_RU'){
$lang = 'ru';
	}
//Pour autres pays
else {
$lang = 'en';
	}
if($temp == 'magelo' && !is_admin()){
echo '<script type="text/javascript" src="http://www.magelocdn.com/pack/wow/'.$lang.'/magelo-bar.js#1rdq"></script>';
 }
else if($temp == 'wowhead'  && !is_admin()) {
echo '<script type="text/javascript" src="http://static.wowhead.com/widgets/power.js"></script>';
 } 
else{echo '';}

$temp = get_option('kwsomebutton');
if($temp == 'yes'){
	function kw_add_more_buttons($buttons) {
		 $buttons[] = 'hr';
		 $buttons[] = 'sub';
		 $buttons[] = 'sup';
		 $buttons[] = 'fontselect';
		 $buttons[] = 'fontsizeselect';
		 return $buttons;
	}
	add_filter("mce_buttons_2", "kw_add_more_buttons");
}

function kw_filter_mce_button( $buttons ) {
	array_push( $buttons, '|', 'artefact' );
	array_push( $buttons, '', 'legend' );
	array_push( $buttons, '', 'epic' );
	array_push( $buttons, '', 'rare' );
	array_push( $buttons, '', 'commun' );
	array_push( $buttons, '', 'normal' );
	array_push( $buttons, '', 'poor' );
	array_push( $buttons, '|', 'youtubeblack');
	array_push( $buttons, '', 'youtubeborder');
	array_push( $buttons, '|', 'cleanup');
	return $buttons;
}

function kw_filter_mce_plugin( $plugins ) {
	// this plugin file will work the magic of our button
	$plugins['artefact'] = TINYWOW_DIR . 'js/artefact.js';
	$plugins['legend'] = TINYWOW_DIR . 'js/legend.js';
	$plugins['epic'] = TINYWOW_DIR . 'js/epic.js';
	$plugins['rare'] = TINYWOW_DIR . 'js/rare.js';
	$plugins['commun'] = TINYWOW_DIR . 'js/commun.js';
	$plugins['normal'] = TINYWOW_DIR . 'js/normal.js';
	$plugins['poor'] = TINYWOW_DIR . 'js/poor.js';
	$plugins['youtubeblack'] = TINYWOW_DIR . 'js/youtube.js';
	$plugins['youtubeborder'] = TINYWOW_DIR . 'js/kwyoutube.js';
	return $plugins;
}

//add the shortcode handler for YouTube videos
function YouTube($atts, $content = null) {
		$height = get_option("kwheight");
		$width = get_option("kwwidth");
        extract(shortcode_atts(array( "id" => '' ), $atts));
		return '<div id="video"><iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$id.'" frameborder="0" allowfullscreen></iframe></div>';}
add_shortcode('youtubeblack', 'YouTube');

//add the shortcode handler for YouTube videos
function KwYouTube($atts, $content = null) {
		$height = get_option("kwheight");
		$width = get_option("kwwidth");
		$colorborder1 = get_option("kwcolorborder1");
		$colorborder2 = get_option("kwcolorborder2");
        extract(shortcode_atts(array( "id" => '' ), $atts));
		return '<div id="video" style="paddind:5px;"><object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://www.youtube.com/v/'.$id.'?version=2&color1=0x'.$colorborder1.'&color2=0x'.$colorborder2.'&border=1&fs=1&hl=fr_FR&rel=0&hd=1" /><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="bgcolor" value="#000000"><embed type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/v/'.$id.'?version=2&color1=0x'.$colorborder1.'&color2=0x'.$colorborder2.'&border=1&fs=1&hl=fr_FR&rel=0&hd=1" bgcolor="#000000" allowscriptaccess="always" allowfullscreen="true"></embed></object></div>';}
add_shortcode('youtubeborder', 'KwYouTube');

//add the shortcode handler for items
function KwWowHead($atts, $content = null) {
        extract(shortcode_atts(array( "name" => '', "url" => '', "class" => ''), $atts));
		return '<a class="'.$class.'" href="'.$url.'">'.$name.'</a>';
}
add_shortcode('item', 'KwWowHead');


/**Add menu admin in the Apparence menu **/

add_action('admin_menu', 'kw_tinywow_menu');

function kw_tinywow_menu() {
	add_theme_page('Tiny WoW', 'Tiny WoW Youtube', 'manage_options', 'tinywowcolor', 'kwtinywow');
}

/**Updates "kw_advert Settings" Page Form **/

function kwtinywow(){
    if(isset($_POST['submitted']) && $_POST['submitted'] == "yes")
	{
		
        /**Get form data**/
		$kwwidth = stripslashes($_POST['kwwidth']);
		update_option("kwwidth", esc_html($kwwidth));
	
		$kwheight = stripslashes($_POST['kwheight']);
		update_option("kwheight", esc_html($kwheight));
		
		$kwenqueuebubble = stripslashes($_POST['kwenqueuebubble']);
		update_option("kwenqueuebubble", esc_html($kwenqueuebubble));
		
		$kwsomebutton = stripslashes($_POST['kwsomebutton']);
		update_option("kwsomebutton", esc_html($kwsomebutton));
		
		$kwcolorborder1 = stripslashes($_POST['kwcolorborder1']);
		update_option("kwcolorborder1", esc_html($kwcolorborder1));
		
		$kwcolorborder2 = stripslashes($_POST['kwcolorborder2']);
		update_option("kwcolorborder2", esc_html($kwcolorborder2));
		
        echo "<div id=\"message\" class=\"updated fade\"><p><strong>Your settings have been saved.</strong></p></div>";
    }
		
?>
<?php /**Start Form for admin area**/?>
<div class="wrap">
<div id="theme-options-wrap">
  <div class="icon32" id="icon-tools"></div>
  <h2> Tiny button + wow color + youtube 2 Border edition + youtube 3 Black edition</h2>
  <p>Take control of your theme with your own specific preferences. Admin page for multiple new buttons in tiny admin Editor.</p>
  <div class="postbox" style="position:absolute; top:100px; right:15px; max-width:200px !important; text-align:center !important;">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="Q56M572XU8XFG" />
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG_global.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online." />
<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1" />
</form>
</div>
    <form method="post" name="tinywowcolor" target="_self">
      <div style="Display: block; height: 15px; width: 100%;"></div>
      <table class="form-table">
       <tr valign="top">
          <th scope="row">Define defaut width for video</th>
        <td><input type="number" style="width:125px;" maxlength="6" name="kwwidth" id="kwwidth" value="<?php echo get_option("kwwidth"); ?>" /> px
        </td>
        </tr>
      <tr valign="top">
          <th scope="row">Define defaut height for video</th>
          <td><input type="number" style="width:125px;"  maxlength="6" name="kwheight" id="kwheight" value="<?php echo get_option("kwheight"); ?>" /> px</td>
        </tr>
          <tr valign="top">
          <th scope="row">1st color for youtube Border Edition</th>
          <td>#<input type="text" style="width:125px;"  maxlength="6" name="kwcolorborder1" id="kwcolorborder1" value="<?php echo get_option("kwcolorborder1"); ?>" /><em>(define 2 different color border in the 2 case for a gradiant effect)</em></td>
          </tr>
          <tr valign="top">
          <th scope="row">2nd color for youtube Border Edition</th>
          <td>#<input type="text" style="width:125px;"  maxlength="6" name="kwcolorborder2" id="kwcolorborder2" value="<?php echo get_option("kwcolorborder2"); ?>" /><em>(define the same of the 1st color border for non-gradiant effect)</em></td>
          </tr>
         <tr valign="top">
          <th scope="row">Enqueue wowhead script ?</th>
          <?php $temp = get_option('kwenqueuebubble'); ?>
          <td><input type="radio" name="kwenqueuebubble" id="kwenqueuebubble" value="magelo" <?php if($temp == 'magelo'){echo 'checked="checked"';} ?> /> magelo<br />
<input type="radio" name="kwenqueuebubble" id="kwenqueuebubble" value="wowhead" <?php if($temp == 'wowhead'){echo 'checked="checked"';} ?> /> wowhead<br />
<input type="radio" name="kwenqueuebubble" id="kwenqueuebubble" value="off" <?php if($temp == 'off'){echo 'checked="checked"';} ?> /> no<br />
		</td>
        </tr>
           <tr valign="top">
           <?php $temp = get_option('kwsomebutton'); ?>
          <th scope="row">Add some buttons to admin editor ?<br />(hr, sub, sup, fonts, cleanhtml, ...)</th>
          <td><input type="radio" name="kwsomebutton" id="kwsomebutton" value="yes" <?php if($temp == 'yes'){echo 'checked="checked"';} ?> /> Yes<br />
  			<input type="radio" name="kwsomebutton" id="kwsomebutton" value="no"<?php if($temp == 'no'){echo 'checked="checked"';} ?> /> No<br />
            </td>
        </tr>
      </table>
      <p class="submit">
        <input name="submitted" type="hidden" value="yes" />
        <input type="submit" name="Submit" value="Update &raquo;" />
      </p>
    </form>
  </div>
</div>
<?php 
}
?>