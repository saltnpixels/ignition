# ignition

![alt text](https://ignition.press/wp-content/themes/github-logo.png "ignition")

## The Starter Theme that Could

Ignition is an amazing WordPress starter theme that aims to make your life easier. The website documentation is a bit behind! Please use the info below for 4.0:
### https://ignition.press

## Ignition 4.0
Ignition 4.0 is out as of Jun 16, 2020
The documentation on the site listed above, is a bit behind and needs time to be updated.
However the info below should get you started!

## Features
Ignition has a lot of features while remaining lightweight and bare bones.
It allows you to create your website without having to rebuild the most common things found in most projects.
Here are just some of the features found in ignition.

- NPM and Webpack ready so you can use the latest js, as well as sass, postcss, and more
- We use dart sass for the latest sass including @use and @forward
- Quick theme configuration via theme.config.json for settings you don't want clients touching
- A powerful CSS grid system that falls back on flexbox for older browsers.
- Upload svg logos in the customizer, and they will output as inline logos for easy css styling. Logo will also appear on login page
- A beautiful menu that works with submenus, dropdowns, and menu placement via the theme config.
- All your JS scripts will be minified and concatenated and set for output, so you wont need to enqueue any front end JS files. They automatically are included.
- Google Fonts at the ready. Easily changeable in the theme config file.
- ACF Blocks Included. Easily use and add your own in the blocks folder.
- Javascript events for scroll animations and click events and even moving items from around the page with simple data attributes.
- svg icons support with iconify.design
- An automatic header block that can be added when a new post is made automatically using the theme config file

## Setup
First things first! <br>
Download the theme into your WordPress theme directory.
Run the following in the terminal (make sure the terminal is pointing to the theme directory)

 ```shell script
npm run setup
```
 This will ask you for the new name of your theme. You must also give a slug which will be used as your text domain and in some functions.
 Example: Theme Name: My Amazing Theme, Slug: amazing-theme
 If you are using a local environment with something like flywheel, make sure to enter the local url. The default is ignition.local

 Answer all the questions and your theme will be all set up and ready to use!

 ### Theme Config
 There is now a theme config file. It has some nifty quick settings for you without letting the client mess around. Some of these settings use to be in the wP customizer. We have moved them here. Note the name and slug here were created by running the setup. If you run the setup again, it will do a replacement based on whats here. So to change the theme name again, run the setup, don't just change those here!
 ```json5
{
  "name": "Ignition", //Theme name. Created by the setup we ran before. No touchy!
  "slug": "ignition", //Theme slug. No touchy! If you want to change the theme name, run setup again
  "local": "ignition.local", //browsersync url proxy
  "google_fonts": [
    "Roboto:400,400i,700,700i", //change me to your liking!
    "Roboto Slab:400,700"
  ],
   "menu_icon": "", //svg icons can be added here and in the icon settings below
    "sidebar_icon": "",
    "submenu_arrow_icon": "",
    "comment_icon": "",
    "search_menu_item": false, //adds a search item to top menu
    "dev_admin_bar_color": "#156288", //color of local admin bar
    "admin_access_capability": "manage_options", //capability of who can access back end
    "load_custom_icons": false, //now that we use iconify.design you might not need to load any custom icons, although you can still add them
    "mobile_menu_type": "app-menu", //fancy mobile menu, leave blank for regular
    "logo-position": "logo-left", //logo position with menu
    "site-top-container": "container", //contain the menu and logo takes container, container-fluid or nothing
    "default-acf-header-block" : ["post", "page"] //create default header block for these post types (assumes they use Gutenberg)
}
```
By setting these up here you wont have to delve into files or through setting pages. It's fast and easy!

 ### Variables.scss
 This file resides in "src/sass". Here you can edit your CSS variables and colors and fonts. Add the google fonts you created in the theme config here so they are applied to the entire site.
 For adding or changing SASS variables, please use the resources.scss file. SASS variables are still being used for media queries and the like. Otherwise try and use CSS variables.

 ### Functions File
 If you have made a theme before, you know this one. Here you can set up your theme. However, the theme config has basically taken care of almost everything for you! The only thing you may find you need to do here is add image-sizes based on the site you are making.
 Remember, the web is responsive and pixel size matters less than ratio size. If the client has two image sizes of 500x200 and 1000x400, only make the bigger one. Users should upload images twice the size of the image-size so they look good on retina.
 I also recommend going to the back end of the site and setting the media sizes of medium and large to 0. This way disk space is saved and images uploaded don't create a plethora of image sizes.
 Lastly, all styles and scripts have been enqueued for you. There really is nothing more to do unless your adding some special functionality or need to add your own CDN's and scripts.


 ### Adding to functions
 When adding your own filters and actions you can stick them in functions.php OR keep it clean and make a new file, probably in the inc folder. This file can be added to function.php with an include or require... but you don't even need to do that!
 *You can add php files to functions.php by simply giving the file a name that starts with an underscore*. Any php file that starts with an underscore inside the "inc", "blocks", and "parts" folders will be automatically included to functions.php
 This ability extends to js files and scss files in those folders that also start with an underscore. This allows you to bundle your php, scss, and js files together without explicitly importing or enqueuing them. See the blocks folder for examples.

 With all these files setup your ready to start making a theme!!


## Developing
To get development rolling open the terminal and run
```shell script
npm run start
```
This will watch your files and reload the browser using browsersync.

### Creating Template files and Folders
Most of your development will take place in the src folder. This is where everything you make will go. From template files, to js files to scss.
What's really cool is you can group your sass, js, and php files together! They don't have to be separated into separate folders. Wouldn't it be nicer if that slideshow block you made was in the same folder as the js file.
With ignition you can. Not only that but underscored files are automatically imported. No need to enqueue those files!

This is because of special functionality ignition has.
Let's look at the blocks folder for a good example:
```
 - blocks/
 -- section-menu/
 ---- _section-menu.scss
 ---- _section-menu-block.php
 ---- section-menu-block.php
```
This is a block that spits out a menu section. Here we can see there is an underscore php file. This means it will automatically be included in the functions.php file. This file is responsible for setting up and registering the block.
The scss file is also underscored so it will automatically be added to both the front and back end css files. The last file there is a php file which is the actual template file for showing the block.
The ability to add underscore files into the system means you can drag and drop folders with functionality straight into your theme. Drag a block you made elsewhere right in and its ready to use. (The only piece you might need to put elsewhere is the acf-json file if your block is using that)

This will greatly organize your files so you can one in one folder without having to fly around folders.

### Non underscored files need to be imported manually
If you js or scss file is not underscored you will have to add it yourself. This allows you to choose if it should be enqueued or put somewhere specific.
js files can be imported into the index.js or admin-index.js files in the src folder. Admin-index is for the back end.<br>
SASS files can be imported via @use or @forward within the SASS folder in one of the files there. If the file requires sass variables, make sure to use `@use "resources" as *;` at the top of the file.



## Routing and Template Parts
When a single post is shown it uses the single.php file in the root. This file in turn will check which current post type is being shown and find the appropriate content file. By default it will go to src/parts/post and get a file there.
This is all done using a special template function Ignition comes with. It's similar to say, get_template_part() and actually uses locate_template(), except it's faster and you can pass variables to it.
```php
//The following is the non ignition way
locate_template( 'src/parts/' . get_post_type() . '/content-'  . get_post_type() . '.php');

//The following is the ignition way. It also has fallbacks that eventually go to the post folder
ign_template('content'); //looks for a content-{post-type}.php or a content.php file inside a post-type folder. Assume your in a loop
```

Furthermore you can pass a second parameter of variables like so:
```php

$var = 'hello'; //will not be available inside the file below
locate_template( 'src/parts/' . get_post_type() . '/content-'  . get_post_type() . '.php');

$var = 'hello'; //WILL be available
ign_template('content', array('var'=> $var)); //pass an array of variables
```
You can also use ign_template outside the loop by giving it a full path
```php
ign_template('src/some-folder/some-file.php');
```

With ign_template, you don't need to make a single-portfolio.php for a portfolio post-type. You just make a new post-type folder with all the different views that exist for the post type.
Your post type folders should have a content-{post-type}.php and a card-{post-type}.php file to begin with. A good place to start is to duplicate and rename the post folder. Rename the files inside too.
The content one is for full view and the card one is for archive listings.
----
This documentation is not complete! It will be finished soon!



## Learn More
[Visit the documentation](https://ignition.press/documentation/getting-started/) to learn how to use the starter theme. It's quite simple and reading the documentation should take less than 25 minutes.
So download ignition and give it a try!

## View ChangeLog
[View the changelog](https://github.com/saltnpixels/ignition/blob/master/changelog.md) For helpful info.

### Is it Ignition or ignition without a capital?
I dunno. Who cares!? Why do people ask these questions!? Just download it and see how easy your theming will become!!


