'use strict';
/* Choices Bundles Javascript namespace */
var $ = jQuery;
var CB = {
	
	/**
	 * Object contructer
	 */
	init: function(){
		this.bindEvents();
	},
	
	
	/**
	 * Add all the necessary event handler on ready
	 */
	bindEvents: function(){
		$('#add_bundle_choice').click(function(e){
			e.preventDefault();
			CB.addRow();
		});
		
		CB.updateRemoveEvents();
	},
	
	
	/**
	 * Ajoute un row pour une option de bundle
	 */
	addRow: function(){
		var clone = $('#choices_bundle_options .bundle_choice').first().clone();
		clone.find('select').prop('selectedIndex', 0);
		clone.find('input[type=number]').val( '1' );
		
		var count = $('#choices_bundle_options .bundle_choice').size();
		var newIndex = CB.getNextIndex();
		
		// Replace all attributes
		var attrToReplace = [
			'id',
			'class',
			'name',
			'for',
		];
		for(var i = 0; i<attrToReplace.length; i++){
			clone.find('['+attrToReplace[i]+'*="_bundle_cat_"], ['+attrToReplace[i]+'*="_bundle_max_"] ').each(function(){
				$(this).attr(attrToReplace[i], $(this).attr(attrToReplace[i]).replace('_1','_'+newIndex) );
			});
		}
		
		// Add remove button
		clone.find('.remove_bundle_row').remove();
		clone.prepend( '<a href="#" class="remove_bundle_row button">X</a>' );
		
		// Add
		$('#bundle_options_actions').before( clone );
		CB.updateRemoveEvents();
	},
	
	
	
	/**
	 * Trouve le plus gros index de row actuel et incrémente la valeur de retour
	 */
	getNextIndex: function(){
		var attr = $('#choices_bundle_options .bundle_choice').last().find('p.form-field').first().find('label').first().attr('for');
		
		attr = attr.split('_');
		var current = attr[ attr.length - 1 ];
		
		return (parseInt(current) + 1);
	},
	
	
	
	/**
	 * Applique correctement les events pour les boutons de suppression de row
	 */
	updateRemoveEvents: function(){
		$( "body" ).off( "click", ".remove_bundle_row" );
		$('.remove_bundle_row').each(function(){
			$(this).click(function(e){
				e.preventDefault();
				$(this).closest( '.bundle_choice' ).remove();
			});
		});
	},
	
};


jQuery(function($){
	CB.init($);
});