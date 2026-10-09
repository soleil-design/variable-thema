(function () {
	var init = function (root) {
		var tabs        = root.querySelectorAll( '[data-vt-latest-tab]' );
		var panels      = root.querySelectorAll( '[data-vt-latest-panel]' );
		var archiveLink = root.querySelector( '[data-vt-latest-archive]' );

		if ( ! tabs.length || ! panels.length) {
			return;
		}

		var activate = function (slug) {
			tabs.forEach(
				function (tab) {
					var isActive = tab.getAttribute( 'data-vt-latest-tab' ) === slug;

					tab.classList.toggle( 'is-active', isActive );
					tab.setAttribute( 'aria-selected', isActive ? 'true' : 'false' );
				}
			);

			panels.forEach(
				function (panel) {
					var isActive = panel.getAttribute( 'data-vt-latest-panel' ) === slug;

					panel.classList.toggle( 'is-active', isActive );

					if (isActive) {
						panel.removeAttribute( 'hidden' );
					} else {
						panel.setAttribute( 'hidden', '' );
					}
				}
			);

			if (archiveLink) {
				var activeTab = root.querySelector( '[data-vt-latest-tab="' + slug + '"]' );

				if (activeTab) {
					var nextHref  = activeTab.getAttribute( 'data-vt-latest-archive-link' );
					var nextLabel = activeTab.getAttribute( 'data-vt-latest-archive-label' );

					if (nextHref) {
						archiveLink.setAttribute( 'href', nextHref );
					}

					if (nextLabel) {
						archiveLink.textContent = nextLabel;
					}
				}
			}
		};

		tabs.forEach(
			function (tab) {
				tab.addEventListener(
					'click',
					function () {
						activate( tab.getAttribute( 'data-vt-latest-tab' ) );
					}
				);
			}
		);

		var activeTab = root.querySelector( '[data-vt-latest-tab].is-active' );
		if (activeTab) {
			activate( activeTab.getAttribute( 'data-vt-latest-tab' ) );
		} else {
			activate( tabs[0].getAttribute( 'data-vt-latest-tab' ) );
		}
	};

	var ready = function () {
		document.querySelectorAll( '[data-vt-latest-posts]' ).forEach( init );
	};

	if ('loading' === document.readyState) {
		document.addEventListener( 'DOMContentLoaded', ready );
	} else {
		ready();
	}
}());
