(function () {
	var ready = function () {
		var toggle          = document.querySelector( '[data-vt-nav-toggle]' );
		var navigation      = document.querySelector( '[data-vt-navigation]' );
		var expandedClass   = 'is-navigation-open';
		var submenuToggles  = [];
		var submenuSequence = 0;

		document.documentElement.classList.add( 'variable-thema-navigation-ready' );

		if ( ! toggle || ! navigation ) {
			return;
		}

		var header             = toggle.closest( '.site-header' );
		var submenuToggleLabel = navigation.getAttribute( 'data-vt-submenu-toggle-label' ) || 'Toggle submenu';
		var isDesktopNavigation = function () {
			return 'none' === window.getComputedStyle( toggle ).display;
		};
		var wasDesktop = isDesktopNavigation();

		if ( header ) {
			header.classList.toggle( 'is-navigation-desktop', wasDesktop );
		}

		var setExpanded = function ( isExpanded ) {
			toggle.setAttribute( 'aria-expanded', isExpanded ? 'true' : 'false' );
			document.body.classList.toggle( expandedClass, isExpanded );

			if ( header ) {
				header.classList.toggle( expandedClass, isExpanded );
			}
		};

		var closeDescendantSubmenus = function ( menuItem ) {
			menuItem.querySelectorAll( '.site-nav__submenu-toggle' ).forEach(
				function ( submenuToggle ) {
					var submenu = document.getElementById( submenuToggle.getAttribute( 'aria-controls' ) );

					submenuToggle.setAttribute( 'aria-expanded', 'false' );

					if ( submenu ) {
						submenu.hidden = true;
					}
				}
			);
		};

		var setSubmenuExpanded = function ( submenuToggle, isExpanded ) {
			var submenu = document.getElementById( submenuToggle.getAttribute( 'aria-controls' ) );

			if ( ! submenu ) {
				return;
			}

			submenuToggle.setAttribute( 'aria-expanded', isExpanded ? 'true' : 'false' );
			submenu.hidden = ! isExpanded;

			if ( ! isExpanded ) {
				closeDescendantSubmenus( submenu );
			}
		};

		var closeAllSubmenus = function () {
			submenuToggles.forEach(
				function ( submenuToggle ) {
					setSubmenuExpanded( submenuToggle, false );
				}
			);
		};

		navigation.querySelectorAll( '.menu-item-has-children' ).forEach(
			function ( menuItem ) {
				var parentLink = menuItem.querySelector( ':scope > a' );
				var submenu    = menuItem.querySelector( ':scope > .sub-menu' );

				if ( ! parentLink || ! submenu ) {
					return;
				}

				submenuSequence += 1;

				if ( ! submenu.id ) {
					submenu.id = 'site-primary-submenu-' + submenuSequence;
				}

				var submenuToggle = document.createElement( 'button' );
				var parentLabel   = parentLink.textContent.trim();

				submenuToggle.className = 'site-nav__submenu-toggle';
				submenuToggle.type = 'button';
				submenuToggle.setAttribute( 'aria-expanded', 'false' );
				submenuToggle.setAttribute( 'aria-controls', submenu.id );
				submenuToggle.setAttribute( 'aria-label', submenuToggleLabel + ': ' + parentLabel );
				submenu.hidden = true;

				parentLink.insertAdjacentElement( 'afterend', submenuToggle );
				submenuToggles.push( submenuToggle );

				submenuToggle.addEventListener(
					'click',
					function () {
						setSubmenuExpanded( submenuToggle, 'true' !== submenuToggle.getAttribute( 'aria-expanded' ) );
					}
				);

				menuItem.addEventListener(
					'pointerenter',
					function ( event ) {
						if ( isDesktopNavigation() && 'touch' !== event.pointerType ) {
							setSubmenuExpanded( submenuToggle, true );
						}
					}
				);

				menuItem.addEventListener(
					'pointerleave',
					function ( event ) {
						if ( isDesktopNavigation() && 'touch' !== event.pointerType && ! menuItem.contains( document.activeElement ) ) {
							setSubmenuExpanded( submenuToggle, false );
						}
					}
				);

				menuItem.addEventListener(
					'focusout',
					function ( event ) {
						if ( isDesktopNavigation() && ! menuItem.contains( event.relatedTarget ) ) {
							setSubmenuExpanded( submenuToggle, false );
						}
					}
				);
			}
		);

		toggle.addEventListener(
			'click',
			function () {
				setExpanded( toggle.getAttribute( 'aria-expanded' ) !== 'true' );
			}
		);

		navigation.addEventListener(
			'click',
			function (event) {
				if ( event.target.closest( 'a' ) ) {
					setExpanded( false );
					closeAllSubmenus();
				}
			}
		);

		document.addEventListener(
			'keydown',
			function (event) {
				if ( 'Escape' !== event.key ) {
					return;
				}

				var activeSubmenu = document.activeElement ? document.activeElement.closest( '.sub-menu' ) : null;

				if ( activeSubmenu ) {
					var ownerItem     = activeSubmenu.parentElement;
					var submenuToggle = ownerItem ? ownerItem.querySelector( ':scope > .site-nav__submenu-toggle' ) : null;

					if ( submenuToggle ) {
						setSubmenuExpanded( submenuToggle, false );
						submenuToggle.focus();
						event.preventDefault();
						return;
					}
				}

				if ( 'true' === toggle.getAttribute( 'aria-expanded' ) ) {
					setExpanded( false );
					closeAllSubmenus();
					toggle.focus();
					event.preventDefault();
				}
			}
		);

		var resetNavigation = function () {
			setExpanded( false );
			closeAllSubmenus();
		};

		var handleViewportChange = function () {
			var isDesktop = isDesktopNavigation();

			if ( wasDesktop === isDesktop ) {
				return;
			}

			wasDesktop = isDesktop;

			if ( header ) {
				header.classList.toggle( 'is-navigation-desktop', isDesktop );
			}

			resetNavigation();
		};

		window.addEventListener( 'resize', handleViewportChange );
	};

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', ready );
	} else {
		ready();
	}
}());
