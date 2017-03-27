( function ( $, api ) {

	$( document ).on( 'click', '.wpcw-widgets a.post-edit-link', function() {

		api.WidgetCustomizerPreview.preview.send( 'focus-widget-control', $( this ).data( 'widget-id' ) );

	});

} )( jQuery, wp.customize );
