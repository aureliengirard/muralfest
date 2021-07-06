import fonts from '../fonts/fonts.js';

import "jquery";
import 'lazysizes';
import 'lazysizes/plugins/parent-fit/ls.parent-fit';
import 'select2';
import 'magnific-popup';

import functionsInit from './functionsInit.js';
import sliderInit from './sliderInit.js';
import filtersInit from './filtersInit.js';

//Legacy JS
import datepickerFrCA from './vendor/datepicker-fr-CA.js';
import daterangeCalendar from './vendor/daterange-calendar.js';
//import colorbox from './vendor/jquery.colorbox-min.js';
//import map from './vendor/map.js';
//import mapArts from './vendor/map-arts.js';
//import script from './vendor/script.js';

//import ink from './vendor/ink.js';
//import AOS from 'aos';
/*
AOS.init({
    delay:100,
    duration:400,
    easing:'ease-in',
});
$(window).on("scroll", function () {
    AOS.refresh();
});
*/

function init() {
    functionsInit();
    sliderInit();
    filtersInit();
}
init();