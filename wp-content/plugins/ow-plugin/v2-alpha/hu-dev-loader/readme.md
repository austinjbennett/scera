Add Plugin Directories to WordPress
===================================

Plugin from the repo mentioned at http://wordpress.stackexchange.com/questions/43262/add-multiple-plugin-directories

#Setup
1. define the HOSTING_UTILITIES_DEV_DIR in your config file to be the absolute path to a folder where all of the hosting utility SVN repos are stored.
The plugin will then take care of looking inside the trunk folder of the repose, and finding plugins to add to the website.
Make sure this folder is a folder Apache has access to. If in doubt, just put the hosting utilities inside a folder inside the WordPress root directory.

2. On the WordPress backend go to the plugins page. Where the 'all', 'active', '...' links are, a new link will appear called 'hosting utilities'.
Click it, and activate/deactivate the hosting utility plugins from the resulting page

#Add new hosting utility plugin
Need to add instructions here
