/**
 * Marks the block editor canvas with the parent template layout of the
 * edited post, so assets/css/editor.css can match the front-end widths.
 *
 * @package Variable_Thema
 */

(function ( wp, settings ) {
	if ( ! wp || ! wp.data || ! settings ) {
		return;
	}

	var layoutClasses  = [ 'vt-editor-layout--default', 'vt-editor-layout--landing' ];
	var hookedCanvases = [];

	// Match the editor canvas to the parent template that renders the edited post.
	var getLayoutClass = function () {
		var editor   = wp.data.select( 'core/editor' );
		var template = editor && editor.getEditedPostAttribute ? editor.getEditedPostAttribute( 'template' ) : '';

		if ( settings.landingTemplate === template ) {
			return settings.landingIsParent ? layoutClasses[1] : '';
		}

		if ( ! template || 'default' === template ) {
			return settings.defaultIsParent ? layoutClasses[0] : '';
		}

		return '';
	};

	var applyLayout = function () {
		var layoutClass = getLayoutClass();
		var documents   = [ document ];
		var canvas      = document.querySelector( 'iframe[name="editor-canvas"]' );

		if ( canvas ) {
			if ( -1 === hookedCanvases.indexOf( canvas ) ) {
				hookedCanvases.push( canvas );
				canvas.addEventListener( 'load', applyLayout );
			}

			if ( canvas.contentDocument ) {
				documents.push( canvas.contentDocument );
			}
		}

		documents.forEach(
			function ( editorDocument ) {
				editorDocument.querySelectorAll( '.editor-styles-wrapper' ).forEach(
					function ( wrapper ) {
						layoutClasses.forEach(
							function ( className ) {
								wrapper.classList.toggle( className, className === layoutClass );
							}
						);
					}
				);
			}
		);
	};

	wp.data.subscribe( applyLayout );
	applyLayout();
}( window.wp, window.variableThemaEditorLayout ));
