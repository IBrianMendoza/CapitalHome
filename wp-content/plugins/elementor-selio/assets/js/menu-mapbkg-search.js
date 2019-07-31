( function( $ ) {
	/**
 	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */ 
	var WidgetMenu_Mapbkg_SearchHandler = function( $scope, $ ) {
        console.log( $scope );
        
        console.log('|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||');
	};
	
	// Make sure you run this code under Elementor.
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/menu-mapbkg-search.default', WidgetMenu_Mapbkg_SearchHandler );
	} );
} )( jQuery );
