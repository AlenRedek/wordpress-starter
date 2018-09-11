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
- Open your terminal and browse to the projects workplace folder.
- Clone the theme from GitHub repository `$ git clone git@github.com:EmigmaLab/<project-name>.git`

### Installing Dependencies
- Then open your terminal and browse to the location of your theme folder
- Run: `$ npm install`

### Rsync
- Open your terminal and browse to the root folder of project.
- Download uploads folder: `$ rsync -Phvrt <project-name>@dev.razvija.se:/var/www/<project-name>.razvija.se/web/wp-content/uploads/ wp-content/uploads/`

### Docker
- Open your terminal and browse to the location of your theme folder
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

#### Premium plugins
- Premium plugins are available at [http://hub.razvija.se/wp-plugins/](http://hub.razvija.se/wp-plugins/)
- In order to update repository, upload a newer version of plugin
- Run: `$ scp -i ~/.ssh/emigma_dev_rsa plugin-name.zip hub@dev.razvija.se:/var/www/hub.razvija.se/web/wp-plugins`

## Developing with npm, Gulp, SASS and Browser Sync[1]

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

[1] Visit [http://browsersync.io](http://browsersync.io) for more information on Browser Sync

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
