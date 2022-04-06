const sliderInit = function() {

    $(document).ready(function () {
        onresize();
        $(window).resize(function () {
            onresize();
        });
    });

    // Resize
    function onresize () {
        slider_prog();
    }

    function slider_prog(){
        var $sliderProg = $('.programs__slider'),
            $date_selector = $('#date-selector');
            //$slide_to_select = $date_selector.attr('slide');

            $sliderProg.slick({
                arrows: true,
                infinite: false,
                slidesToShow: 4,
                rows: 0, // Fix vor v1.8.0-1
                infinite: true,
                prevArrow:'<button class="slick-prev slick-arrow" aria-label="Previous" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="33" viewBox="0 0 18 33"><g><g><path fill="#fff" d="M.387 15.749L15.595.675a.915.915 0 0 1 1.29 0 .896.896 0 0 1 0 1.28L2.326 16.384l14.56 14.43a.896.896 0 0 1 0 1.279.921.921 0 0 1-.643.268.893.893 0 0 1-.641-.268L.394 17.02a.893.893 0 0 1-.007-1.272z"/></g></g></svg></button>',
                nextArrow:'<button class="slick-next slick-arrow" aria-label="Next" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="33" viewBox="0 0 18 33"><g><g><path fill="#fff" d="M16.861 15.749L1.654.675a.915.915 0 0 0-1.29 0 .896.896 0 0 0 0 1.28l14.558 14.43L.363 30.814a.895.895 0 0 0 0 1.279.921.921 0 0 0 .643.268c.229 0 .465-.087.641-.268L16.854 17.02a.893.893 0 0 0 .007-1.272z"/></g></g></svg></button>',
                //slidesToScroll: 1,
                //mobileFirst: true,
                responsive: [{
                    breakpoint: 1300,
                    settings: {
                        slidesToShow: 3,
                    }
                },
                {
                    breakpoint: 996,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 500,
                    settings: {
                        slidesToShow: 1,
                    }
                }
            ]
        });

        $date_selector.change(function() {
            var slide_to_select = $(this).val();
            $sliderProg.slick('slickGoTo',slide_to_select);
        });
    }
};

export default sliderInit;



