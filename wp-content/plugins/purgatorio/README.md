=== Purgatorio by Alen Redek ===
Contributors: alenredek
Tags: dev, functions, hooks, seo, security, attachments, gmaps
Stable tag: 1.0
Requires at least: 4.2
Tested up to: 4.8.2

== Description ==

Various helper functions and classes for faster theme development.

== Third-Party Dependencies ==
* WP_Query Arguments Code Snippets https://www.billerickson.net/code/wp_query-arguments/
* WP Theme Customization https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
* Polylang Function Reference https://polylang.wordpress.com/documentation/documentation-for-developers/functions-reference/
* Theme development with Gulp, Bower & Livereload https://codeable.io/speed-up-your-theme-development-with-gulp/

= Shortcodes =

* [pg_gmaps id=gmaps-container cpt=page height=400px]
* [pg_attachments post_id=123 title=Downloads]

= Widgets & Sidebars =

widgets/widgets.php

* Sidebar Hero Static
* Sidebar Homepage Widgets (1/2)
* Sidebar Header Right
* Sidebar Footer Top Widget
* Sidebar Footer Widgets (1/4)
* Sidebar Footer Bottom Widget
* Widget Business card
* Widget Dynamic banner (for each post)
* Widget Featured item banner
* Widget Language switcher
* Widget Login form
* Widget Side navigation
* Widget Top banner item

= Hooks =

includes/security.php

* Clean up wp_head() from unused or unsecure stuff
* Removes the generator tag with WP version numbers
* Show less info to users on failed login for security (Will not let a valid username be known)

includes/global-init.php

* Update Site Address (URL) & Search Engine Visibility options
* Theme init actions - add excerpt to pages & increase var_dump display
* Add Meta Tags in Header - IE8 support for HTML5
* Enqueue theme scripts & styles - Remove Open Sans from frontend which is added by WP itself
* Sets up theme defaults and registers support for various WordPress features - post-thumbnails, post-formats
* Custom font sizes on WYSIWYG
* Modify admin menu - remove some items
* Remove Rev Slider Metabox
* Adds custom classes to body - environment, language
* Disable premium plugins update notification - Rev slider, Visual composer
* Add additional data to Yoast's SEO JSON-LD output - Author & Publisher ID for website
* Yoast SEO breadcrumbs modifications for single pages
* Resizing all image media files on upload to 1920
* Custom Page Columns - Display page template PHP file location

= Functions =

includes/global-functions.php

* Get plugin option value
* Get theme option value
* Determine environment - dev / prod
* Local var_dump - output visible only on dev IP & to administrators
* Get translated post using Polylang
* Get translated term using Polylang
* Get post type years archive
* Group posts by month
* Get page template for pre-selected CPT
* Get archive page template
* Get archive page template title
* Get archive page template content
* Is date format
* Get i18 formatted date
* Pretty URL
* Extract URL from string
* Generate URL with GET parameters
* Get title statically
* Get taxonomy title
* Get file size
* Get file ID by URL
* Check if file exists in upload folder

includes/seo.php

* Required hatom microdata - Author & modified date
* Outputs the JSON LD code in a valid JSON+LD wrapper
* Prepare JSON+LD schema for software application

= Queries =

includes/class-queries.php
object $pg_query

* Get latest posts
* Get posts by year
* Get posts from specific period
* Get posts by search parameter (title, content, excerpt)
* Get posts by taxonomy
* Get children posts
* Get posts filtered by meta key
* Get event based post
* Get date based post
* Get taxonomy term's posts
* Get posts sorted by term

= Google Maps =

gmaps/class-gmaps.php
object $pg_gmaps

* Get post geolocation - By coordinated or by address
* Get coordinates from address - Google API request
* Create array of locations with valid geo data
* Populate map with array of location - markers
* Marker clustering

= Youtube =

includes/class-youtube.php
object $pg_youtube

* Get video info - ID, thumbnails, duration

== Installation ==

= Manual Alternatives =
Upload the files manually to your server and follow the on-screen instructions.

== Changelog ==

= 1.0 =

* Release date: October 17, 2017

**Enhancements**
* Google maps shortcode
* Attachments shortcode

**Bug fixes**
* 
