=== Hosting Utilities ===
Contributors: Russell, Scotty
Requires at least: 4.9.9
Tested up to: 5.2.4
Requires PHP: 7.0
License: WP Overwatch Software License
License URI: https://wp-overwatch.com/legal/software-license.html

# ow-plugin

A plugin that is installed on every website we manage.


## How the project is structured

The options form is in pages/options/index.php.
The associated code for these options are typically performed by code in the inc directory.

All other pages are registered in create_admin_pages.php, and the corresponding files can be found within the pages folder

## Deploying Updates

To update the plugin, upload a new stable version of the plugin to repo.wordpressoverwatch.com, and this will trigger every
installation of the plugin to find the available update. Make sure you increment the version number in ow-plugin.php when updating (There are two places in that file that needs to receive the updated version number).

## Deploying Updates for v2

To deploy, just run sendit.py, which internally does all of the following

1. Changes ow-plugin.php comments to use \<version-number>
2. `git add -a ow-plugin.php`
3. `git commit -m Releasing version <version-number>`
4. `git tag <version-number>`
5. `git push`
6. `git push --tags`

To deploy an individual plugin to the official WordPress repo, follow the instructions inside of that plugins readme file

## Adding New Pages

When adding new pages to the menu, there are two places that have to be updated.
1) wpoverwatch_theme/scss/base_for_all_themes/other/mixins.scss
and
2) the `is_ow_page` function of helper_functions.php

## Setup Process

Some pages, like the site health dashboard, will not work properly until the setup process if run. This can be done by activating the plugin and navigating to the "WP Overwatch" > options page.

There are some additional things that have to be done to make the site health dashboard work properly. This is currently documented in the dashboard's README within its repository.



== Changelog ==

= 2.1 beta =
Add search Bar

= 2.0 beta =
- splitting Hosting Utilities into multiple plugins. Each plugin is now keeping track of their own changelog. You can find these changelogs by going to plugins, and next to the 'all', 'active', etc. links click on the hosting utilities link. Hover over a plugin, and click view details.

= 1.5 =
- Allow remote authentication through the mission control center

= 1.4 =
- Updated Site Health Dashboard to work with v2 of the reporting service

= 1.3 =
- Theming

= 1.2 =
- Adds Site Health Dashboard

= 1.1 =
- Adds options and roadmap pages

= 1.0 =
- Initial Version
