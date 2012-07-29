<?php
/*
Plugin Name: tiny WoW item colors
Version: 1.0.3
Description: This is a simple plugin to add colorisation items from WoW / wowhead with buttons in TinyMCE (epic, rare, legendary, poor, commun, normal, artifact) + two buttons for youtube (youtube version 3 Black and youtube version 2 with color border and hd mode)
Author: Laurent (KwarK) Bertrand
Author URI: http://kwark.allwebtuts.net
Plugin URI: http://kwark.allwebtuts.net
*/

/*  
	Copyright 2012  Laurent (KwarK) Bertrand  (email : kwark@allwebtuts.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// disallow direct access to file
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
	wp_die(__('Sorry, but you cannot access this page directly.', 'livetv'));
}

//constants
define("TINYWOW_DIR", WP_PLUGIN_URL . '/tiny-wow-colors/' );
$irc_dir = dirname( plugin_basename( __FILE__ ) );

// Enable internationalisation
load_plugin_textdomain( 'tinywow', true, $irc_dir . '/languages/' );

//Enqueue style for class a + color
if(!is_admin())
{
	wp_enqueue_style( 'wowcolor', TINYWOW_DIR . 'css/style.css' );
}

//Init button tinyMCE
add_action('init','kw_button_action_admin_init');

function kw_button_action_admin_init()
{
	// only hook up these filters if we're in the admin panel, and the current user has permission to edit posts and pages
	if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
		add_filter( 'mce_buttons', 'kw_filter_mce_button' );
		add_filter( 'mce_external_plugins', 'kw_filter_mce_plugin' );
	}
}

//Enqueue script wowhead or magelo
global $locale;

$lang = explode('_', $locale);

$temp = get_option("kwenqueuebubble");

if($temp == 'magelo' && !is_admin())
{
	wp_enqueue_script('twc-magelo', 'http://www.magelocdn.com/pack/wow/'.$lang[0].'/magelo-bar.js#1rdq');
}
if($temp == 'wowhead' && !is_admin())
{
	wp_enqueue_script('twc-wowhead', 'http://static.wowhead.com/widgets/power.js');
}

//Some buttons
$temp = get_option('kwsomebutton');
if($temp == 'yes')
{
	function kw_add_more_buttons($buttons)
	{
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

function kw_filter_mce_plugin( $plugins )
{
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


//add the shortcode handler for YouTube videos version 3
function YouTube($atts, $content = null)
{
	$height = get_option("kwheight");
	$width = get_option("kwwidth");
	extract(shortcode_atts(array( "id" => '' ), $atts));
	return '<div id="video"><iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$id.'" frameborder="0" allowfullscreen></iframe></div>';
}
add_shortcode('youtubeblack', 'YouTube');


//add the shortcode handler for YouTube videos version 2
function KwYouTube($atts, $content = null)
{
	global $locale;
	$height = get_option("kwheight");
	$width = get_option("kwwidth");
	$colorborder1 = get_option("kwcolorborder1");
	$colorborder2 = get_option("kwcolorborder2");
	extract(shortcode_atts(array( "id" => '' ), $atts));
	return '<div id="video" style="paddind:5px;"><object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://www.youtube.com/v/'.$id.'?version=2&color1=0x'.$colorborder1.'&color2=0x'.$colorborder2.'&border=1&fs=1&hl='.$locale.'&rel=0&hd=1" /><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="bgcolor" value="#000000"><embed type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/v/'.$id.'?version=2&color1=0x'.$colorborder1.'&color2=0x'.$colorborder2.'&border=1&fs=1&hl='.$locale.'&rel=0&hd=1" bgcolor="#000000" allowscriptaccess="always" allowfullscreen="true"></embed></object></div>';
}
add_shortcode('youtubeborder', 'KwYouTube');


//add the shortcode handler for items
function KwWowHead($atts, $content = null)
{
	extract(shortcode_atts(array(
	"name" => '', 
	"url" => '', 
	"class" => ''
	), $atts));
	return '<a class="'.$class.'" href="'.$url.'">'.$name.'</a>';
}
add_shortcode('item', 'KwWowHead');


/**Add menu admin in the Apparence menu **/
add_action('admin_menu', 'kw_tinywow_menu');

function kw_tinywow_menu()
{
	add_menu_page('Tiny WoW', 'Tiny WoW', 'manage_options', 'tinywowcolor', 'kwtinywow');
	add_submenu_page('tinywowcolor.php', 'Tiny WoW', 'Tiny WoW', 'manage_options', 'tinywowcolor.php', 'kwtinywow');
}


// Enqueue admin css
if(is_admin())
{
    wp_register_style('tinywow_admin_css', plugins_url('css/admin.css', __FILE__));
    wp_enqueue_style('tinywow_admin_css');
}


// Remove all settings on uninstall hook
function tiny_page_delete_defaut_settings()
{
	global $wpdb;
	
	$settings = array(
		'kwwidth',
		'kwheight',
		'kwenqueuebubble',
		'kwsomebutton',
		'kwcolorborder1',
		'kwcolorborder2'
	);

	foreach ($settings as $v)
	{
		delete_option( ''.$v.'' );
	}
}
register_uninstall_hook(__FILE__, 'tiny_page_delete_defaut_settings');


//Admin page
function kwtinywow()
{
    if(isset($_POST['submitted']) && $_POST['submitted'] == "yes")
	{
		
        /**Get form data**/
		$kwwidth = stripslashes($_POST['kwwidth']);
		update_option("kwwidth", $kwwidth);
	
		$kwheight = stripslashes($_POST['kwheight']);
		update_option("kwheight", $kwheight);
		
		$kwenqueuebubble = stripslashes($_POST['kwenqueuebubble']);
		update_option("kwenqueuebubble", $kwenqueuebubble);
		
		$kwsomebutton = stripslashes($_POST['kwsomebutton']);
		update_option("kwsomebutton", $kwsomebutton);
		
		$kwcolorborder1 = stripslashes($_POST['kwcolorborder1']);
		update_option("kwcolorborder1", $kwcolorborder1);
		
		$kwcolorborder2 = stripslashes($_POST['kwcolorborder2']);
		update_option("kwcolorborder2", $kwcolorborder2);
		
        echo '<div id="message" class="updated fade"><p><strong>'; _e('Your settings have been saved.', 'tinywow'); echo '</strong></p></div>';
    }
		
?>
<?php /**Start Form for admin area**/?>
<div class="wrap">
<div id="theme-options-wrap">
  <div class="icon32" id="icon-tools"></div>
  <h2> Tiny buttons + WoW + Youtube</h2>
    <form method="post" name="tinywowcolor">
    <p class="submit">
      <input type="submit" name="options_submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />
    </p>
     <table class="widefat options" style="width: 650px">
     <th colspan="2" class="dashboard-widget-title"><?php _e("Default options for videos", "tinywow" ) ?></th>
       <tr valign="top">
       <td scope="row"><label>
            <?php _e('Define defaut width for video', 'tinywow'); ?>
          </label></td>
        <td><input type="number" style="width:125px;" maxlength="6" name="kwwidth" id="kwwidth" value="<?php echo get_option("kwwidth"); ?>" /> px
        </td>
        </tr>
      <tr valign="top">
      <td scope="row"><label>
            <?php _e('Define defaut height for video', 'tinywow'); ?>
          </label></td>
          <td><input type="number" style="width:125px;"  maxlength="6" name="kwheight" id="kwheight" value="<?php echo get_option("kwheight"); ?>" /> px</td>
        </tr>
          <tr valign="top">
          <td scope="row"><label>
            <?php _e('1st color for youtube Border Edition', 'tinywow'); ?>
          </label></td>
          <td>#<input type="text" style="width:125px;"  maxlength="6" name="kwcolorborder1" id="kwcolorborder1" value="<?php echo get_option("kwcolorborder1"); ?>" /><span class="tiny_help" title="<?php _e('define 2 different color border in the 2 case for a gradiant effect', 'tinywow' ); ?>"></span><br /></td>
          </tr>
          <tr valign="top">
          <td scope="row"><label>
            <?php _e('2nd color for youtube Border Edition', 'tinywow'); ?>
          </label></td>
          <td>#<input type="text" style="width:125px;"  maxlength="6" name="kwcolorborder2" id="kwcolorborder2" value="<?php echo get_option("kwcolorborder2"); ?>" /><span class="tiny_help" title="<?php _e('define the same of the 1st color border for non-gradiant effect', 'tinywow' ); ?>"></span><br /></td>
          </tr>
          </table>
          <br />
          <table class="widefat options" style="width: 650px">
          <th colspan="2" class="dashboard-widget-title"><?php _e("Enqueue script option", "tinywow" ) ?></th>
         <tr valign="top">
          <td scope="row"><label>
            <?php _e('Enqueue wowhead/magelo script ?', 'tinywow'); ?>
          </label></td>
          <?php $temp = get_option('kwenqueuebubble'); ?>
          <td><input type="radio" name="kwenqueuebubble" id="kwenqueuebubble" value="magelo" <?php if($temp == 'magelo'){echo 'checked="checked"';} ?> /> magelo <input type="radio" name="kwenqueuebubble" id="kwenqueuebubble" value="wowhead" <?php if($temp == 'wowhead'){echo 'checked="checked"';} ?> /> wowhead <input type="radio" name="kwenqueuebubble" id="kwenqueuebubble" value="off" <?php if($temp == 'off'){echo 'checked="checked"';} ?> /> no<br />
		</td>
        </tr>
        </table>
        <br />
        <table class="widefat options" style="width: 650px">
        <th colspan="2" class="dashboard-widget-title"><?php _e("Tiny buttons option", "tinywow" ) ?></th>
           <tr valign="top">
           <td scope="row"><label>
            <?php _e('Add some buttons ? (hr, sub, sup,...)', 'tinywow'); ?>
          </label></td>
           <?php $temp = get_option('kwsomebutton'); ?>
          <td><input type="radio" name="kwsomebutton" id="kwsomebutton" value="yes" <?php if($temp == 'yes'){echo 'checked="checked"';} ?> /> Yes <input type="radio" name="kwsomebutton" id="kwsomebutton" value="no"<?php if($temp == 'no'){echo 'checked="checked"';} ?> /> No
          <br />
            </td>
        </tr>
      </table>
      <p class="submit">
      <input name="submitted" type="hidden" value="yes" />
      <input type="submit" name="options_submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />
    </p>
    </form>
  </div>
</div>
<?php 
}
?>