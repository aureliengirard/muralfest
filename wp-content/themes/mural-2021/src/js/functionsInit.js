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

    function onScroll() {
      var Heightcalculate = $('#nav').height();
      var scroll = $(window).scrollTop();
      if (scroll >= 1 ) {
        $header.classList.add('is_sticky');
        $('#phantom').show();
        $('#phantom').css('height', Heightcalculate+'px');
      } else {
        $header.classList.remove('is_sticky');
        $('#phantom').hide();
      }
    }
    window.addEventListener('scroll', throttle(onScroll, 25));

    $(".menu-item-has-children").on('click', function (e){

      var nextEl = this.nextElementSibling;

      if(nextEl.classList.contains('dropdown-menu')){
          $("dropdown-menu").each(function(){
              let dropdown = $("dropdown-menu");
              if(dropdown.hasClass('show')){
                dropdown.removeClass('show');
              }
          });
          e.stopPropagation();
          if(nextEl.classList.contains('show')){
              nextEl.classList.remove('show');
          } else{
              nextEl.classList.add('show');
          }
      }
    });    

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
