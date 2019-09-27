# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project tries to adhere to [Semantic Versioning](https://semver.org/spec/v2.0.0.html). :)

## [3.2.0] - Sep 26, 2019
Gutenberg Headers!
<br> You can now create a header block to override the default header. 

## [3.1.0] - Sep 12, 2019
Gutenberg is here.
Make Gutenberg blocks easily with ACF Pro
 - Gutenberg styles are automatically applied for you on the front and back end
 - IE11 fixes. Now using polyfill.io
 - Changed the default icons. Sidebar icon is now pure css.
 - Fixed ign template tags to work with Gutenberg and added some new ones for acf links


## [3] - 04-25-2019
HUGE update!
- Fixed all js files. The file, setup.js is now separate and runs before custom.js so there are no issues in ie11
- Most JS files now use vanilla js with ESNext
- Renamed ACF Blocks to classic blocks to prepare for gutenberg blocks with acf
- Streamlined font-sizing. use 1.6rem to mean 16px or 1.2rem to mean 12px... 
- Fonts using rem get bigger by around 4 pixels on large screens. Use px if you dont want this
- JS Events fixed and updated. 
- Now you can use data-radio to force data-toggles to act like radio buttons
- data-slide will slide open and closed an item that has been toggled (need data-toggle, data-target on toggle item and data-slide on target item that your toggling.)
- Fixed grid and ie11 grid in small ways
- Now you can add your own spans to the grid at custom breakpoints in variables.scss. see bottom of file.
- Fixed ign image functions. You can now pass the field name directly to the function.
- Ign image functions now work with taxonomy fields
- Added touch to gulp so files get modified date
 - Moves some sass files around
 - Added new sass folder for WordPress related stuff
 - Added admin.scss for css wanted on back end

## [2.2.6] - 04-25-2019
 - Added ability to swap inline background image for a 2x version by adding data-high-res to element
 - Added some helpful notes to some template files
 - Added ability to change the date format when using ign_posted_on() template tag

## [2.2.5] - 04-16-2019
 - Added a new menu icon of hamburger for mobile menu
 - ACF Sections now have an auto Collapse feature so the sections are not overwhelming
 - Sections can also be identified by titles
    
## [2.2.4] - 03-29-2019

### Added

- ACF Page Sections now automatically collapse on load for nicer viewing with JS (inc/acf_extras/acf_scripts.js)
- Titles of ACF Page Sections now take their label from any field inside called section_title, title, or heading if found available and in that order. (inc/acf_extras/acf_extras.php)

- This changelog :)

## [2.0.0] - 2018

### Added 

- Ability to dynamically add classes to the acf layout in the admin area when using the repater field.
- Header section can be added via checkbox to change the default header for each page or post type.
- Extra header image ACF field added to differentiate between thumbnail and large hero image if the thumbnail is not big enough.
- CSS variables and postCSS  now added and powering all major variables in the CSS files except for media query variables.
- Easily add ability to pull header out of page when a sidebar is used. This will place the header above content and sidebar. To do this add class "header-above" to the sidebar-template element inside sidebar template files.

### Changed 

- Ign Image functions have been changed to take the acf_image as a first parameter.
- Restructured some css files and folders for easier finding.

## [1.0.0] - 2018

Created initial version of ignition. Create custom WordPress websites with ease.

