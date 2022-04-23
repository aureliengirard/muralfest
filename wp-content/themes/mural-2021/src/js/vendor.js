import "jquery";
//import "jquery-ui";
import 'lazysizes';
import 'lazysizes/plugins/parent-fit/ls.parent-fit';
import 'select2';
import 'magnific-popup';
import 'slick-carousel';

import functionsInit from './functionsInit.js';
import sliderInit from './sliderInit.js';
import filtersInit from './filtersInit.js';

function init() {
    functionsInit();
    sliderInit();
    filtersInit();
}
init();
