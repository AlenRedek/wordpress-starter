# UnderStrap WordPress Theme Framework

Website: [https://understrap.com](https://understrap.com)

Demo: [https://understrap.com/understrap](https://understrap.com/understrap)

Docs: [https://understrap.github.io](https://understrap.github.io)

Child Theme Project: [https://github.com/holger1411/understrap-child](https://github.com/holger1411/understrap-child)

Travis build: [![Build Status](https://travis-ci.org/understrap/understrap.svg?branch=master)](https://travis-ci.org/understrap/understrap)

## About

I’m a huge fan of Underscores, Bootstrap, and Sass. Why not combine these into a solid WordPress Theme Framework?
That’s what UnderStrap is.
You can use it as starter theme and build your own theme on top of it. Or you use it as parent theme and create your own child theme for UnderStrap.

## License
UnderStrap WordPress Theme, Copyright 2013-2017 Holger Koenemann
UnderStrap is distributed under the terms of the GNU GPL version 2

http://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html

## Changelog
See [changelog](CHANGELOG.md)

## Basic Features

- Combines Underscore’s PHP/JS files and Bootstrap’s HTML/CSS/JS.
- Comes with Bootstrap (v4) Sass source files and additional .scss files. Nicely sorted and ready to add your own variables and customize the Bootstrap variables.
- Uses a single and minified CSS file for all the basic stuff.
- Font Awesome integration (v4.7.0)
- Jetpack ready.
- WooCommerce support.
- Contact Form 7 support.
- Child Theme ready.
- Translation ready.

## Starter Theme + HTML Framework = WordPress Theme Framework

The _s theme is a good starting point to develop a WordPress theme. But it is “just” a raw starter theme. That means it outputs all the WordPress stuff correctly but without any layout or design.
Why not add a well known and supported layout framework to have a solid, clean and responsive foundation? That’s where Bootstrap comes in.

## Confused by All the CSS and Sass Files?

Some basics about the Sass and CSS files that come with UnderStrap:
- The theme itself uses the `/style.css` file just to identify the theme inside of WordPress. The file is not loaded by the theme and does not include any styles.
- The `/assets/css/theme.css` and its minified little brother `/assets/css/theme.min.css` files provides all the styles.
Don´t edit these files! They're composed of several different SCSS sets and one variable file defined at `/assets/scss/theme.scss`.
- Your design goes into `/assets/scss/theme/` folder:

    - Add other `_example.scss` files into `/assets/scss/theme/` folder. Hence the `_` prefix.
    - Add any additional imports into `/assets/scss/theme/_theme.scss` file by using `@import` keyword
    - Add your custom variables or variables to overwrite Bootstrap or UnderStrap variables into `/assets/scss/theme/_variables.scss` file.
    - Some basic WordPress stylings to combine Boostrap and Underscores
    `/assets/scss/understrap/understrap`

- Don’t edit the files within `src` filesets or you won’t be able to update it without overwriting your own work!

    - All the Bootstrap stuff
    `/assets/src/scss/bootstrap/bootstrap`
    - Font Awesome Icon styles
    `/assets/src/scss/font-awesome/font-awesome`

## Installation

### GIT
- Open your terminal and browse to your workplace direcotry
- Clone the theme from GitHub repository `$ git clone git@github.com:AlenRedek/wordpress-starter.git`

### Installing Dependencies
- Open your terminal and browse to the location of your theme folder
- Run: `$ npm install`

### Rsync
- Open your terminal and browse to the project root directory
- Download uploads folder: `$ rsync -Phvrt <user>@<host>:/path/to/wordpress/wp-content/uploads/ wp-content/uploads/`

### Environment constants

#### wp-config.php
- Loop through all the environment variables and define them as PHP constants
- Create an empty `wp-config.php` file in your project root directory
- Put the following contents in it:
```php
<?php

$table_prefix  = getenv('TABLE_PREFIX') ?: 'wp_';

foreach ($_ENV as $key => $value) {
  $capitalized = strtoupper($key);
  if (!defined($capitalized)) {
    define($capitalized, $value);
  }
}

if (!defined('ABSPATH'))
    define('ABSPATH', dirname(__FILE__) . '/');

require_once(ABSPATH . 'wp-settings.php');
```

#### .env
- Define environment variables
- Create an empty `.env` file in your project root directory
- Fill with these values:
```
# All of these constants are being read by wp-config.php
DB_NAME=wp
DB_USER=root
DB_PASSWORD=toor
DB_HOST=mysql
TABLE_PREFIX=em_
WP_SITEURL=http://localhost/
WP_HOME=http://localhost/
WP_DEBUG=0
WP_CACHE=1
WP_AUTO_UPDATE_CORE=0
FS_METHOD=direct
DISALLOW_FILE_EDIT=1
BLOG_PUBLIC=0

# Don't forget to update these salts: https://api.wordpress.org/secret-key/1.1/salt/
AUTH_KEY=your_auth_key
SECURE_AUTH_KEY=your_secure_auth_key
LOGGED_IN_KEY=your_logged_in_key
NONCE_KEY=your_nonce_key
AUTH_SALT=your_auth_salt
SECURE_AUTH_SALT=your_secure_auth_salt
LOGGED_IN_SALT=your_logged_in_salt
NONCE_SALT=your_nonce_salt
```

### Docker
- Open your terminal and browse to the project root directory
- Build image & Run Docker container: `$ docker-compose up`
- List all containers `$ docker container ls -a`
- Enter running container `$ docker container exec -it <container_name> bash`
- When you're done, shut down running containers with command `$ docker-compose down`.

### PHPMyAdmin
- Open your browser and navigate to [http://localhost:8080](http://localhost:8080)
- Log in with username `root` and password `toor`.
- Create new database named `wp` and import appropriate SQL dump file.

### Accessing the page
- Open your browser and navigate to [http://localhost](http://localhost)

### Install required plugins
- Navigate to WP admin area [http://localhost/wp-admin](http://localhost/wp-admin)
- Go to `Appearance -> Install plugins`
- Install & Activate required plugins

#### Purgatorio - Dev plugin
- WordPress plugin for faster theme development
- [https://github.com/AlenRedek/purgatorio](https://github.com/AlenRedek/purgatorio)

## Developing with npm, Gulp[1], SASS and Browser Sync[2]

### Installing Dependencies
- Make sure you have installed Node.js and Gulp on your computer globally
- Run: `$ npm install --global gulp`

### Running
To work and compile your Sass files on the fly open your terminal and browse to the location of your theme folder and start:
- `$ gulp watch`

Or, to run with Browser-Sync:

- First change the browser-sync options to reflect your environment in the file `/gulpconfig.json` in the beginning of the file:
```javascript
{
    "browserSyncOptions" : {
        "proxy": "localhost/theme_test/", // <----- CHANGE HERE
        "notify": false
    },
    ...
};
```
- then run: `$ gulp watch-bs`

[1] Visit [https://codeable.io/speed-up-your-theme-development-with-gulp](https://codeable.io/speed-up-your-theme-development-with-gulp) for more information on Theme development with Gulp, Bower & Livereload
[2] Visit [http://browsersync.io](http://browsersync.io) for more information on Browser Sync

## Page Templates

### Blank Template

The `blank.php` template is useful when working with various page builders and can be used as a starting blank canvas.

### Empty Template

The `empty.php` template displays a header and a footer only. A good starting point for landing pages.

### Full Width Template

The `fullwidthpage.php` template has full width layout without a sidebar.

Licenses & Credits
=
- Font Awesome: http://fontawesome.io/license (Font: SIL OFL 1.1, CSS: MIT License)
- Bootstrap: http://getbootstrap.com | https://github.com/twbs/bootstrap/blob/master/LICENSE (Code licensed under MIT documentation under CC BY 3.0.)
and of course
- jQuery: https://jquery.org | (Code licensed under MIT)
- WP Bootstrap Navwalker by Edward McIntyre: https://github.com/twittem/wp-bootstrap-navwalker | GNU GPL
- Bootstrap Gallery Script based on Roots Sage Gallery: https://github.com/roots/sage/blob/5b9786b8ceecfe717db55666efe5bcf0c9e1801c/lib/gallery.php
