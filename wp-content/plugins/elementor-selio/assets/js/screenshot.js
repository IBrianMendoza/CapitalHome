( function( $ ) {
	/**
 	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */ 
	var WidgetHelloWorldHandler = function( $scope, $ ) {
		console.log( $scope );
	};
	
	// Make sure you run this code under Elementor.
	$( window ).on( 'elementor/frontend/init', function() {
		//elementorFrontend.hooks.addAction( 'frontend/element_ready/hello-world.default', WidgetHelloWorldHandler );



		/*
		elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $scope ) {
			if ( $scope.data( 'shake' ) ){
				$scope.shake();
			}

			console.log($('div.elementor-element-wrapper'));

			$('div.elementor-element-wrapper').hover(function(){
				alert('tralala');
	
			});
		} );
*/

/*
		myVar = setInterval(function(){ 

			console.log($('.eicon-device-desktop'));

			
			if($('.elementor-element-wrapper .elementor-element').length  > 0)
				clearInterval(myVar);

		 }, 3000);
*/

	} );
} )( jQuery );


/*

jQuery(window).load(function($) {
	myVar = setInterval(function(){ 
		
	console.log(jQuery('.elementor-element-wrapper'));

	
	if(jQuery('.elementor-element-wrapper').length  > 0)
		clearInterval(myVar);

	}, 3000);
 });

 */