var frameProportion = 1.78, //png frame aspect ratio
    frames = 25, //number of png frames
    resize = false;

//set transitionBackground dimentions
setLayerDimensions();
$(window).on('resize', function(){
   if( !resize ) {
      resize = true;
      (!window.requestAnimationFrame) ? setTimeout(setLayerDimensions, 300) : window.requestAnimationFrame(setLayerDimensions);
   }
});

function setLayerDimensions() {
   var windowWidth = $(window).width(),
       windowHeight = $(window).height(),
       layerHeight, layerWidth;

   if( windowWidth/windowHeight > frameProportion ) {
      layerWidth = windowWidth;
      layerHeight = layerWidth/frameProportion;
   } else {
      layerHeight = windowHeight;
      layerWidth = layerHeight*frameProportion;
   }

   $('#ink-transition .bg-layer').css({
      'width': layerWidth*frames+'px',
      'height': layerHeight+'px',
   });

   resize = false;
}