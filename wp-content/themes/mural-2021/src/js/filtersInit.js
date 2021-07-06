const functionsInit = function() {

    $(function(){
        var search_artiste = -1
    	if ($('.program-filters select[name="filtre-artiste"] option').length >= 5){
    		var search_artiste = $('.program-filters select[name="filtre-artiste"] option').length;
    	}
    	$('.program-filters select[name="filtre-artiste"]').select2({
    		allowClear: true,
    		minimumResultsForSearch: search_artiste,
    		placeholder: $('.program-filters select[name="filtre-artiste"]').attr("placeholder")
    	})

    	var search_category = -1
    	if ($('.program-filters select[name="category"] option').length >= 5) {
    		var search_category= $('.program-filters select[name="category"] option').length;
    	}
    	$('.program-filters select[name="category"]').select2({
    			allowClear: (true),
    			minimumResultsForSearch: search_category,
    			placeholder: $('.program-filters select[name="category"]').attr("placeholder")
    	})

    	var search_year = -1
    	if ($('.program-filters select[name="years"] option').length >= 5) {
    		var search_year = $('.program-filters select[name="years"] option').length;
    	}
    	$('.program-filters select[name="years"]').select2({
    		allowClear: true,
    		minimumResultsForSearch: search_year,
    		placeholder: $('.program-filters select[name="years"]').attr("placeholder")
    	})

        if ($.urlParam('category') || $.urlParam('filtre-artiste') || $.urlParam('years') ||  $.urlParam('date') ){
            $('html, body').animate({
                scrollTop: $(".filters").offset().top
            }, 0);
        }
    });

    $.urlParam = function (name) {
    	var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    	if (results){
    		return results[1] || null;
    	}else{
    		return null;
    	}
    }
};

export default functionsInit;
