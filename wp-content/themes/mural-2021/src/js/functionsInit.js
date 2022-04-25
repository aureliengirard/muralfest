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
      var LinkItem = this;
      var nextEl = this.nextElementSibling;
      
      if(nextEl.classList.contains('dropdown-menu')){
        e.stopPropagation();
        if(nextEl.classList.contains('show')){
          LinkItem.classList.remove('active');
          nextEl.classList.remove('show');
        } else{
          LinkItem.classList.add('active');
          nextEl.classList.add('show');
        }
      }
    });
    
    if( $(window).width() > 992){
      $(".menu-item-has-children").on('mouseover', function (e){
        var LinkItem = this;
        var nextEl = this.nextElementSibling;
  
        if(nextEl.classList.contains('dropdown-menu')){
            e.stopPropagation();
            if(nextEl.classList.contains('show')){
            } else{
                LinkItem.classList.add('active');
                nextEl.classList.add('show');
            }
        }
      });

      $("nav .nav-item:not(.dropdown)").on('mouseover', function (e){
        $(".menu-item-has-children").each(function(e){
            let menuItem = $(this);
            if(menuItem.hasClass('active')){
              menuItem.removeClass('active');
            }
        });
        $(".dropdown-menu").each(function(e){
          let dropdown = $(this);
          if(dropdown.hasClass('show')){
            dropdown.removeClass('show');
          }
        });
      });

      $("main").on('mouseover', function (e) {
        $(".menu-item-has-children").each(function(e){
            let menuItem = $(this);
            if(menuItem.hasClass('active')){
              menuItem.removeClass('active');
            }
        });
        $(".dropdown-menu").each(function(e){
            let dropdown = $(this);
            if(dropdown.hasClass('show')){
              dropdown.removeClass('show');
            }
        });
      });
    }

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

    $(document).ready(function() {
      // Load random Event
      var eventActive = eventArray[Math.floor(Math.random() * eventArray.length)];
      var target = '[data-target="' + eventActive + '"]';
      $(target).show("fast");
    
      //Update event onclick
      $(".hero__event").on('click mouseover', function(){
        var splash_event_imagery = $(".hero__event--imagery");
        var data_name = $(this).attr("data-name");
        var clickTarget = '[data-target="' + data_name + '"]';
        
        $(splash_event_imagery).each(function(e){
          if ($(this).attr('data-target') === data_name) {
              $(this).show('fast');
          } else {
            $(this).hide('fast');
          }
        });

      });
    });

};

export default functionsInit;
