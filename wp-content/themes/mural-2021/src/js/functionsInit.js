const functionsInit = function() {

    document.addEventListener('lazybeforeunveil', function(e){
        var bg = e.target.getAttribute('data-bg');
        if(bg){
            e.target.style.backgroundImage = 'url(' + bg + ')';
        }
    });

    $(document).ready(function(){
      $(".dropdown a").click(function(e){
          e.stopPropagation();
          let nextEl = this.nextElementSibling;
          if(nextEl.classList.contains('show')){
              nextEl.classList.remove('show');
          } else{
              nextEl.classList.add('show');
          }
      });
    });

};

export default functionsInit;
