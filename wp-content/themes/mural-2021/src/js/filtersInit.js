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

    	var search_year = -1
    	if ($('.program-filters select[name="years"] option').length >= 5) {
    		var search_year = $('.program-filters select[name="years"] option').length;
    	}
    	$('.program-filters select[name="years"]').select2({
    		allowClear: true,
    		minimumResultsForSearch: search_year,
    		placeholder: $('.program-filters select[name="years"]').attr("placeholder")
    	})
    });
};

export default functionsInit;
