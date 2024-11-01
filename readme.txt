
=== SimpleSchema Free ===
Contributors: sergiomico
Donate link: http://www.sergiomico.com/simpleschema-free/
Tags: microdata, microformat, schema, itemprop, itemtype, itemscope, shortcode, tinymce, editor
Requires at least: 3.0.1
Tested up to: 4.3.1
Stable tag: 1.7.6.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This microdata insertion shortcode tool prevents the WordPress built-in TinyMCE content editor removing microdata when you change post content editor view from code text to visual.

LocalBusiness Schema Widget available since 1.6 version.


== Description ==

EN: This microdata insertion shortcode tool prevents the WordPress built-in TinyMCE content editor removing microdata when you change post content editor view from code text to visual.

ES: Esta herramienta shortcode para inserción de microdatos evita que el editor de contenidos TinyMCE incorporado en WordPress elimine los microdatos cuando cambias la vista del contenido del post de vista de código a vista visual.

You should read *Changelog* and *Other notes* tabs.


== Examples ==


**Widgets availables:**

* SimpleSchema LocalBusiness Schema.
* SimpleSchema LocalBusiness Services Schema (admits Services and Areas served).


**Shortcode examples:**

Since version 1.2:


**Example with [meta_geo_coordinates]**

`[meta_geo_coordinates lat="39.321123" lon="0.654987" /]`

will return

`<div itemscope="" itemprop="geo" itemtype="http://schema.org/GeoCoordinates">
<meta itemprop="lat" content="39.321123">
<meta itemprop="lon" content="0.654987">
</div>`


**Example with [meta_blog_posting]**

`[meta_blog_posting /]`

will return

`<div itemscope="" itemtype="http://schema.org/blogPosting">
<meta itemprop="headline" content="$the_post_title">
<meta itemprop="datePublished" content="$the_post_date_Y_m_d">
<meta itemprop="image" content="$the_post_thumbnail_image_url">
</div>`


Since version 1.1:


**Example 1: [keywords][/keywords]**

`[keywords]Cars[/keywords]`

will return:

`<strong itemprop="keywords">Cars</strong>`


**Example 2: [keywords tag="*HTMLtag*"][/keywords]**

`[keywords tag=h2]Cars[/keywords]`

will return:

`<h2 itemprop="keywords">Cars</h2>`


**Example 3: [itemprop][/itemprop]**

`[itemprop name="name"]LocalBusiness Name[/itemprop]`

will return:

`<meta itemprop="name" content="LocalBusiness Name"/>`


**Example 4: [itemprop tag="*HTMLtag*"][/itemprop]**

`[itemprop name="name" tag="h1"]LocalBusiness Name[/itemprop]`

will return:

`<h1 itemprop="name">LocalBusiness Name</h1>`


**Example 4: [itemscope itemprop="*Its itemprop, if needed*" itemtype="*Its schema name*"][/itemscope]**

`[itemscope itemprop="owns" itemtype="Product"][itemprop name="name" tag="h2"][keywords]Cars[/keywords][/itemprop][/itemscope]`

will return:

`<h1 itemprop="name">LocalBusiness Name</h1>`


**Complete example: parent itemscope wrapping itemscope childs**

`[itemscope itemtype="LocalBusiness" tag="div" start /]
[itemprop name="name" tag="h1"][keywords]LocalBusiness Name[/keywords][/itemprop]
[itemscope itemprop="owns" itemtype="Product"][itemprop name="name" tag="h2"][keywords]Cars[/keywords][/itemprop][/itemscope]
[itemscope itemprop="owns" itemtype="Product"][itemprop name="name" tag="h2"][keywords]Motorcycles[/keywords][/itemprop][/itemscope]
Email: <a href="mailto:business@email.com">[itemprop name="email" tag="em"]business@email.com[/itemprop]</a>
Telephone: <a href="tel:+1-800-000-000">[itemprop name="telephone" tag="strong"]+1-800-000-000[/itemprop]</a>
[itemscope tag="div" end /]`

or

`[is itemtype="LocalBusiness" tag="div" start /]
[ip name="name" tag="h1"][keywords]LocalBusiness Name[/keywords][/ip]
[is itemprop="owns" itemtype="Product"][ip name="name" tag="h2"][kw]Cars[/kw][/ip][/is]
[is itemprop="owns" itemtype="Product"][ip name="name" tag="h2"][kw]Motorcycles[/kw][/ip][/is]
Email: <a href="mailto:business@email.com">[ip name="email" tag="em"]business@email.com[/ip]</a>
Telephone: <a href="tel:+1-800-000-000">[ip name="telephone" tag="strong"]+1-800-000-000[/ip]</a>
[is tag="div" end /]`

will return:

`<div itemscope="" itemtype="http://schema.org/LocalBusiness">
<h1 itemprop="name"><strong itemprop="keywords">LocalBusiness Name</strong></h1>
<div itemscope="" itemprop="owns" itemtype="http://schema.org/Product"><h2 itemprop="name"><strong itemprop="keywords">Cars</strong></h2></div>
<div itemscope="" itemprop="owns" itemtype="http://schema.org/Product"><h2 itemprop="name"><strong itemprop="keywords">Motorcycles</strong></h2></div>
Email: <a href="mailto:business@email.com"><em itemprop="email">business@email.com</em></a><br>
Telephone: <a href="tel:+1-800-000-000"><strong itemprop="telephone">+1-800-000-000</strong></a>
</div>`



== Installation ==

1. Unzip the zip file to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place the right shortcodes in your post/page content editor



== Changelog ==

= 1.7.6.9 =

* Widget display fixing.

= 1.7.6 =

* Widget display fixing.

= 1.7 =

* IMPROVED! SimpleSchema LocalBusiness Schema improved.
* NEW FEATURE! SimpleSchema LocalBusiness Services Schema (admits Services and Areas served).

= 1.6 =
* NEW FEATURE! SimpleSchema LocalBusiness Schema widget.

= 1.5.1 =
* Some comments added to the code.

= 1.5 =
* IMPROVED! TinyMCE buttons now assign editor selected text as content of the generated code. Great!

= 1.4 =
* Bug fixed

= 1.3 =
* NEW FEATURE! New TinyMCE buttons! Now you can insert itemscopes, itemprop or keywords from TinyMCE panel.

= 1.2 =
* NEW FEATURE! New [meta_geo_coordinates] and [meta_blog_posting] shortcodes.
* `[meta_geo_coordinates lat="39.321123" lon="0.654987" /]` will return `<div itemscope="" itemprop="geo" itemtype="http://schema.org/GeoCoordinates"><meta itemprop="lat" content="39.321123"><meta itemprop="lon" content="0.654987"></div>`
* `[meta_blog_posting /]` will return `<div itemscope="" itemtype="http://schema.org/blogPosting"><meta itemprop="headline" content="$the_post_title"><meta itemprop="datePublished" content="$the_post_date_Y_m_d"><meta itemprop="image" content="$the_post_thumbnail_image_url"></div>`
* Both [meta_geo_coordinates] and [meta_blog_posting] will also admit content between [shortcode][/shortcode] marks.

= 1.1 =
* IMPROVED! You can use [kw], [ip] or [is]. They work like [keywords], [itemprop] and [itemscope]. Work with less characters!

= 1.0 =
* IMPROVED! Better version. See *Other notes* tab for examples.

= 0.5 =
* Very first stable version, only admits *blogPosting* and *GeoCoordinates* schemas.



== Upgrade Notice ==

= 1.7 =
New SimpleSchema widget added.

= 1.6 =
New SimpleSchema widget added.

= 1.3 =
New TinyMCE buttons added.

= 1.0 =
Sorry but, this version changes all (to a better version, of course). You might do revision to your SimpleSchema Free shortcodes.
