=== Tiny WoW colors ===

Contributors: KwarK
Donate link: http://kwark.allwebtuts.net/
Tags: warcraft, tiny, admin, editor, buttons, wow, colors, youtube, video, shortcode
Tested up to: 3.4.1
Stable tag: 1.0.3

Add some buttons to tiny admin editor, buttons for item WoW (epic, poor, rare, ...) and Youtube buttons

== Description ==

* Add some buttons to tiny admin editor (sup, sub, hr, html cleaner, fonts selector). 
* Add buttons for colorisation item WoW (poor, normal, commun, rare, epic, legend, artefact)
* add Youtube buttons


Automatic generate some shortcodes for Youtube (version 2 and 3), automatic generates some shortcodes for colorisation items for Blizzard/wowhead/Magelo items links. Enqueue option for one of this Tooltips (Magelo or wowhead).

You may changes at any times option wowhead `<-->` magelo and all your tip-bubble effect already published works fine without changes.

You may use all classes with html tag

Color items

* poor
* normal
* commun
* rare
* epic
* legend
* artefact

Some others

* success
* info
* error
* blizzquote


You may use all classes outside the admin editor buttons (with html tag - text colorization)

e.g.

`<p class="epic">Your text here....</p>`


You may use this outside the admin editor (on a forum - manual shortcode)

e.g.

`[item name="Spiritwalker's Cuirass" url="http://www.wowhead.com/item=78724" class="epic"]`


== Installation ==

1. Upload 'tinywowcolor' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Define few attributs and selector on page option > Appearence > Tiny WoW Youtube.

== Screenshots ==

1. No screenshot actually

== Frequently Asked Questions ==

View forum support on Wordpress for more information


== Upgrade Notice ==

1. Use the Wordpress automatic upgrade notice or upgrade this plugin manually


== Changelog ==

= 1.0.3 =

* replace echo by wp_enqueue_script for wowhead and magelo

= 1.0.2 =

* Code review
* Add languages support

= 1.0.1 =

* Fix folder name

= 1.0 =

* Original review