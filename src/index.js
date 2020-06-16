import 'normalize.css'

//ADDING SASS
//add your sass files easilt by starting them with an underscore inside the inc or parts folders
// You can also manually add a regular file to the front end bundle so you have access to all scss variables and classes
//adding a separate scss here will work, but you wont have access to scss variables or @use, or @extend
import './sass/front-end-bunde.scss'

//js from src
import "./js/events"
import "./js/objectfitFallback"
import "./js/sidebar"
import "./js/navigation"
import "./js/panel-left"
import "./js/smooth-scroll"
import "./js/icons"
import "./js/responsive-iframe"


//add all underscored js files from inc and parts
import "../inc/**/_?*.js";
import "./parts/**/_?*.js";


