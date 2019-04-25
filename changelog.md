# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project tries to adhere to [Semantic Versioning](https://semver.org/spec/v2.0.0.html). :)

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

