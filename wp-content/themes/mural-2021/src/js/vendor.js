import "jquery";
import 'lazysizes';
import 'lazysizes/plugins/parent-fit/ls.parent-fit';
import SVG from '../fonts/fonts.js';
/* Bootstrap */
import functionsInit from './functionsInit.js';
import sliderInit from './sliderInit.js';
import AOS from 'aos';

AOS.init({
    delay:100,
    duration:400,
    easing:'ease-in',
});

$(window).on("scroll", function () {
    AOS.refresh();
 });

function init() {
    functionsInit();
    sliderInit();
}
init();
