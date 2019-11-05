# Template-Parts
Ignition routes all content to this folder. So whether a post, page, archive or new post type is showing, somehow some content from template-parts is showing up.
You can also add sass and js files in here and they will be compiled along with what is found in the assets folder.

## Adding new post types
Add a new post type with code or with a plugin like pods. By default the folder post will be used to display the content.
You can duplicate the post folder and change the content so your new post type has a different look and feel. 

You will also need to change the sass file in the folder for your new post type.

### Template Tags
There are some functions to help you out while writing content.
You can check them out in inc/template-tags



## Adding ACF-Blocks
You can add a new acf block via the file found in inc/acf-blocks.php
Then you can add a folder in template-parts/acf-blocks so your content will show up when the block is used.
All your styles will show up automatically for the new block on both front and back ends!
Make sure to add your ACF group in ACf settings. For convention add the word Block at the end of your group so its easy to differentiate from regular custom fields.

## Adding a header block
If you want to make a different header, use the header/header block file. This file is special and is checked when outputting the blocks. If this block is not found it outputs the default header.
We do not want to check for every kind of header block as it can slow down the site.
So if you want to add multiple headers, use this header block rather than making more blocks. The block has a dropdown and you can add new "header types" instead of multiple header blocks.


