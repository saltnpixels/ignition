# ignition

![alt text](https://ignition.press/wp-content/themes/github-logo.png "ignition")

## The Starter Theme that Could

Ignition is an amazing WordPress starter theme that aims to make your life easier and your development faster!

Note: The site below has a splash page that is a bit old and mentions one day incorporating Gutenberg... We are long past that!
Luckily the documentation has been updated. Please stand by while we find time to update the splash page.

### https://ignition.press

## Ignition 4.0
Ignition 4.0 is out as of Jun 16, 2020



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
You should also install Advanced Custom Fields Pro as Ignition works best with it.
Then run the following in the terminal (make sure the terminal is pointing to the theme directory)

 ```shell script
npm run setup
```
 This will ask you for the new name of your theme. You must also give a slug which will be used as your text domain and in some functions.
 Example: Theme Name: My Amazing Theme, Slug: amazing-theme
 If you are using a local environment with something like flywheel, make sure to enter the local url. The default is ignition.local

 Answer all the questions and your theme will be all set up and ready to use!

 ### Theme Config
 There is now a theme config file. It has some nifty quick settings for you without letting the client mess around. Some of these settings use to be in the WP customizer. We have moved them here. Note the name and slug here were created by running the setup. If you run the setup again, it will do a replacement based on whats here. So to change the theme name again, run the setup, don't just change those here!
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
    "logo_position": "logo-left", //logo position with menu
    "site_top_container": "container", //contain the menu and logo takes container, container-fluid or nothing
    "default_acf_header_block" : ["post", "page"] //create default header block for these post types (assumes they use Gutenberg)
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
 Lastly, all main styles and scripts have been enqueued for you.

 ### Adding to functions
 When adding your own filters and actions you can stick them in functions.php OR keep it clean and make a new file, probably in the inc folder. This file can be added to function.php with an include or require... but you don't even need to do that!<br>
 **You can add php files to functions.php by simply giving the file a name that starts with an underscore**. <br>

 Any php file that starts with an underscore inside the "inc", "blocks", and "parts" folders will automatically be included to functions.php
 A similar ability exists with js files and scss files that start with an underscore and are found in one of those folders. This allows you to bundle your php, scss, and js files together without explicitly importing or enqueuing them. See the blocks example below.

 With all these files setup your ready to start making a theme!!


## Developing
To get development rolling, open the terminal and run
```shell script
npm run start
```
This will watch your files and reload the browser using browsersync.

### Creating Template files and Folders
Most of your development will take place in the src folder. This is where everything you make will go. From template files, to js files to scss.
What's really cool is you can group your sass, js, and php files together! They don't have to be separated into separate folders.
Not only that but underscored files are automatically imported. No need to enqueue those files!

This is because of special functionality ignition has.
Let's look at the blocks folder for a good example:
```
 - blocks/
 -- section-menu/
 ---- _section-menu.scss
 ---- _section-menu-block.php
 ---- section-menu-block.php
```
This is a block that spits out a custom menu section. Here, we can see there is an underscore php file _section-menu-block.php. This means it will automatically be included in the functions.php file. This file is responsible for setting up and registering the block.
The scss file is also underscored so it will automatically be added to both the front and back end bundle css files. The last file there is a php file which is the actual template file for showing the block and wont be imported anywhere.
The ability to add underscore files into the system means you can drag and drop folders with functionality straight into your theme. Drag a block you made in another project right in and its ready to use. (The only piece you might need to put elsewhere is the acf-json file if your block is using that,=. that must go in the acf-json folder)

This will greatly organize your files so you can work in one folder without having to fly around folders.

### Non-underscored files need to be imported manually
If your js or scss file is not underscored it will not be imported. You will have to add it yourself, if you choose to. This allows you to choose if it should be enqueued or put somewhere specific etc...
js files can be imported into the index.js or admin-index.js files in the src folder. Admin-index is for the back end.<br>
SASS files can be imported via @use or @forward within the SASS folder in one of the files there. If the file requires sass variables or mixins, make sure to use `@use "resources" as *;` at the top of the file.



## Routing and Template Parts
When a single post is shown it uses the single.php file in the root. This is standard WordPress templating. <br>This file in turn will check which current post type is being shown and find the appropriate content file. This is ignition templating.<br> By default it will go to src/parts/post-type/ folder and get a file there.
This is all done using a special template function Ignition comes with. It's similar to say, get_template_part() and actually uses locate_template() under the hood, except it's faster and you can pass variables to it.

```php
ign_template('somePrefix'); //gets somePrefix-{post-type}.php
```
See the difference below:

```php
 //Assumes your in a loop

//The following is the non ignition way to get a template part based on dynamic post type
locate_template( 'src/parts/' . get_post_type() . '/content-'  . get_post_type() . '.php');

//The following is the ignition way. It also has fallbacks that eventually go to the post folder
ign_template('content');
//This little line will search for a file in this order, stops after it finds one:
//looks for src/parts/{post-type}/content-{post-type}.php
//looks for src/parts/{post-type}/content.php
//looks for src/parts/post/content-{post-type}.php
//looks for src/parts/post/content.php
```

Therefore it's best to work and divide your content into post type folders and give your files name-{post-type}.php

Furthermore you can pass a second parameter of variables like so:
```php

$var = 'hello'; //will not be available inside the file below using WP function locate_template()
locate_template( 'src/parts/' . get_post_type() . '/content-'  . get_post_type() . '.php');

$var = 'hello'; //WILL be available using Ignition function ign_template()
ign_template('content', array('var'=> $var)); //pass an array of variables $var will now work
```

You can also use ign_template outside the loop by giving it a full path from the root of the theme
```php
ign_template('src/some-folder/some-file.php'); //you can also pass variables if wanted here too
```

With ign_template(), you don't need to make a single-portfolio.php for a portfolio post-type. You just make a new post-type folder with all the different views and template files that exist for that post type.
Your post type folders should have a content-{post-type}.php and a card-{post-type}.php file to begin with. If the header is to look different, it should also have a header-{post-type}.php file.<br> A good place to start is to duplicate and rename the post folder. Rename the files inside too.
The content one is for full view and the card one is usually for archive listings. The card view is used by default in index.php

Remember you can and should also add scss files as well as js files into your post-type folders. This keeps your post types and everything they need together.

## Header Block/Template
Every post and page has a header. Sometimes you need the same header. Sometimes you want a different one per post type. Sometimes you want three for one post type and one for another. It can get confusing, but Ignition has finally made this part somewhat easier!
Using the function above, a header template can be shown easily with:
```php
ign_template('header'); //outputs header-{post-type}.php
//if your post type does not have this file it will try and load header-post.php as default
```
But what if you create a header block for say a carousel? And you would like to use that block instead? Ignition comes with another function that will check for a header block and if it exists, it will not output the header template.
This function is used as follows and can be seen in content-post.php

```php
//checks if there is a header block and if not outputs a header-{post-type}.php
ign_header_block();

//usually this comes next.
the_content();
```
The ign_header_block(); will check for a header block that has a name starting with `header-`. If you create a block with a name like `header-carousel`, it will be considered a header and if it's used, the header template file will not be shown. If it does not find a header block on the current post, it will use ign_template() to load a file. By default it loads header-{post-type}.php
You can change this by adding a string inside so a different file is loaded.

## Default Header Templates
Default headers per post type are great, but they are not blocks... They wont show up on the back end when your client is creating posts and pages with Gutenberg. This does not result in a smooth experience!
While the template part would load on the front end it will not show up on the back. However, wouldn't it be nice if this file can be seen in the back end as well and act like a Gutenberg block so the client gets a smooth experience when using the default header?

Ignition to the rescue! In the themes.config file there is a setting for `default_acf_header_block`. Add your post type here and automatically your header-{post-type}.php template file will be added as a choosable block for that post type. Whats more it will be automatically loaded when the client goes to make a new post!<br>
This gives the client a smooth experience when creating a page. It even updates when the client changes the title! The header template file can continue being used as a template file and works the same. You can use `the_title()` or any in-the-loop template tags, that you normally might not have access to in a block. It's magic.

You can add an ACF group and connect it to your default header block...er... template hybrid.
Just make sure you understand that when the default header-{post-type}.php file is used as a block, get_field() will get fields from the block unless you specify the second parameter.

```php
//inside some portfolio/header-portfolio.php

the_title(); //works even when used as a block!

//if you want a custom field from the post
get_field('some_post_meta', get_the_ID()); //make sure to get the meta from the post NOT the block

//if you want a custom field from the block
get_field('some_post_meta'); //note: if this header is loaded as a template this might yield nothing.
```

So now your header template file is showing up in Gutenberg and 'pretending' to be a block! This feature is enabled by default for posts and pages. go try and make a post and see the header template file show up.


----
This documentation is not complete! It will be finished soon!



## Learn More
[Visit the documentation](https://ignition.press/documentation/getting-started/) to learn how to use the starter theme. It's quite simple and reading the documentation should take less than 25 minutes.
So download ignition and give it a try!

## View ChangeLog
[View the changelog](https://github.com/saltnpixels/ignition/blob/master/changelog.md) For helpful info.

### Is it Ignition or ignition without a capital?
I dunno. Who cares!? Why do people ask these questions!? Just download it and see how easy your theming will become!!


