# Excerpt Thumbnail

Excerpt Thumbnail generates thumbnails wherever you show excerpts (archive page, feed...).

## Contents

Excerpt Thumbnail includes the following files:

* `.gitignore`. Used to exclude certain files from the repository.
* `CHANGELOG.md`. The list of changes to the core project.
* `README.md`. The file that you’re currently reading.
* A `plugin-name` directory that contains the source code - a fully executable WordPress plugin.

## Features

* Excerpt Thumbnail is based on the [Plugin API](http://codex.wordpress.org/Plugin_API), [Coding Standards](http://codex.wordpress.org/WordPress_Coding_Standards), and [Documentation Standards](https://make.wordpress.org/core/handbook/best-practices/inline-documentation-standards/php/).
* All classes, functions, and variables are well documented.
* Excerpt Thumbnail uses a strict file organization scheme from The Boilerplate that corresponds both to the WordPress Plugin Repository structure, and that makes it easy to organize the files.
* The project includes a `.pot` file as a starting point for internationalization.

## Installation

Excerpt Thumbnail can be installed directly into your plugins folder.

### i18n Tools

Excerpt Thumbnail uses a variable to store the text domain used when internationalizing strings throughout Excerpt Thumbnail. To take advantage of this method, there are tools that are recommended for providing correct, translatable files:

* [Poedit](http://www.poedit.net/)
* [makepot](http://i18n.svn.wordpress.org/tools/trunk/)
* [i18n](https://github.com/grappler/i18n)

Any of the above tools should provide you with the proper tooling to internationalize the plugin.

## License

Excerpt Thumbnail is licensed under the GPL v2 or later.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

> You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

A copy of the license is included in the root of the plugin’s directory. The file is named `LICENSE`.

## Important Notes

### Licensing

Excerpt Thumbnail is licensed under the GPL v2 or later; however, if you opt to use third-party code that is not compatible with v2, then you may need to switch to using code that is GPL v3 compatible.

For reference, [here's a discussion](http://make.wordpress.org/themes/2013/03/04/licensing-note-apache-and-gpl/) that covers the Apache 2.0 License used by [Bootstrap](http://twitter.github.io/bootstrap/).

### Includes

Note that if you include your own classes, or third-party libraries, there are three locations in which said files may go:

* `excerpt-thumbnail/includes` is where functionality shared between the admin area and the public-facing parts of the site reside
* `excerpt-thumbnail/admin` is for all admin-specific functionality
* `excerpt-thumbnail/public` is for all public-facing functionality

Note that previous versions of Excerpt Thumbnail did not include `Plugin_Name_Loader` but this class is used to register all filters and actions with WordPress.

# Credits

TBA

## Documentation, FAQs, and More

TBA.
