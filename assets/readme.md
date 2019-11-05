# How to use the asset folder
The asset folder expects you to be running npm so it can compile all changes.
NPM will compile the changes and run sass, js concatenation and much more. 

# JS
You can add scripts a few ways.
### Custom Folder
You can write scripts inside js/custom and it will automatically be compiled and built into the custom.js file which has been enqueued for you in functions.php.

### template Parts
In the template parts folder in the theme (outside of assets) you can put scripts with php files so they are together. These too will automatically be copied into the scripts folder where they will be compiled as above.

### Separately
You can add scripts directly into the js folder, they will not be compiled. You will need to add these to WordPress on your own.

# sass
In the sass folder, most of your styling should take place in global or elements folders. Or the styles should be placed inside template-parts folder if they are specific to a block or post type.

### Core Folder
You pretty much only want to touch one file in here. Variables.scss.
This is where you can add and remove and change variables for the site.

The only other file you might touch is mixins.scss if you know what your doing. The other files in the core folder set up the grid and basic layout of the site. Edit those with caution! It probably better to override them.


### global
Here is where you will add and change anything that appears globally across the site. Typography, default look for headers, footers and the sidebars. The GUI of the main site parts should be in here.

### elements
Here is where you can add all your elements. Any styles for items you might add to the site. Items that are not specific to a post type nor a block will go in here.

### WordPress
This folder holds all the wordpress specific styles for default blocks and stuff. Here you can edit how comments look or how default blocks are styled. You can also add a gravity forms style sheet if you want by uncommenting it out in _wordpress.scss.
The template-parts file is special and should not be touched. It pulls all sass files found in the template-parts folder and concatenates them into here.

### Libs
you can add css libraries here so that when you add some plugin and it comes with css you can put it in here and bundle it with the theme css.



