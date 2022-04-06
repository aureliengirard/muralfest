const functionsInit = function() {
    document.addEventListener('lazybeforeunveil', function(e){
        var bg = e.target.getAttribute('data-bg');
        if(bg){
            e.target.style.backgroundImage = 'url(' + bg + ')';
        }
    });

    function throttle(fn, delay) {
      var last = undefined;
      var timer = undefined;

      return function () {
        var now = +new Date();

        if (last && now < last + delay) {
          clearTimeout(timer);

          timer = setTimeout(function () {
            last = now;
            fn();
          }, delay);
        } else {
          last = now;
          fn();
        }
      };
    }

    var $header = document.querySelector('.navbar');
    /*
    function onScroll() {
      if (window.pageYOffset) {
        $header.classList.add('is_sticky');
      } else {
        $header.classList.remove('is_sticky');
      }
    }
    */
    window.addEventListener('scroll', throttle(onScroll, 25));

    var gallery_popup = $('.gallery');
    gallery_popup.magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
        },
        image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
            titleSrc: function(item) {
                return item.el.attr('title');
            }
        }
    });

};

export default functionsInit;
