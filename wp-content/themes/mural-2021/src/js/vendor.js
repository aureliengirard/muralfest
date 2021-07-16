//import "jquery";
//import "jquery-ui";
import 'lazysizes';
import 'lazysizes/plugins/parent-fit/ls.parent-fit';
import 'select2';
import 'magnific-popup';
import 'slick-carousel';

import functionsInit from './functionsInit.js';
import sliderInit from './sliderInit.js';
import filtersInit from './filtersInit.js';

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
