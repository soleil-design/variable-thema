/**
 * Latest posts section: tabs, slide navigation, dots, and responsive visible count.
 *
 * @package Variable_Thema
 */

(function () {
	var clamp = function (value, min, max) {
		return Math.max( min, Math.min( max, value ) );
	};

	var getVisibleCount = function (track) {
		var raw   = window.getComputedStyle( track ).getPropertyValue( '--vt-latest-posts-visible' );
		var count = parseInt( raw, 10 );

		if (Number.isFinite( count ) && 0 < count) {
			return count;
		}

		if (window.matchMedia( '(max-width: 599px)' ).matches) {
			return 1;
		}

		if (window.matchMedia( '(max-width: 899px)' ).matches) {
			return 2;
		}

		return 3;
	};

	var getSlideStep = function (track) {
		var slide = track.querySelector( '[data-vt-latest-slide]' );

		if ( ! slide) {
			return track.clientWidth || 1;
		}

		var gap      = window.getComputedStyle( track ).columnGap || window.getComputedStyle( track ).gap || '0';
		var gapValue = parseFloat( gap ) || 0;

		return slide.getBoundingClientRect().width + gapValue;
	};

	var initPanel = function (panel) {
		var slider   = panel.querySelector( '[data-vt-latest-slider]' );
		var track    = panel.querySelector( '[data-vt-latest-track]' );
		var slides   = Array.prototype.slice.call( panel.querySelectorAll( '[data-vt-latest-slide]' ) );
		var dotsWrap = panel.querySelector( '[data-vt-latest-dots]' );
		var prev     = panel.querySelector( '[data-vt-latest-prev]' );
		var next     = panel.querySelector( '[data-vt-latest-next]' );

		if ( ! slider || ! track || ! slides.length || ! dotsWrap || ! prev || ! next) {
			return null;
		}

		var state = {
			activeIndex: 0,
			maxIndex: 0,
			raf: 0,
			dots: [],
		};

		var renderDots = function () {
			var visibleCount   = getVisibleCount( track );
			state.maxIndex     = Math.max( 0, slides.length - visibleCount );
			dotsWrap.innerHTML = '';
			state.dots         = [];

			if (0 === state.maxIndex) {
				dotsWrap.hidden = true;
				prev.hidden     = true;
				next.hidden     = true;
				return;
			}

			dotsWrap.hidden = false;
			prev.hidden     = false;
			next.hidden     = false;

			for (var i = 0; i <= state.maxIndex; i += 1) {
				var dot       = document.createElement( 'button' );
				dot.type      = 'button';
				dot.className = 'vt-latest-posts__dot';
				dot.setAttribute( 'aria-label', String( i + 1 ) );
				dot.setAttribute( 'aria-selected', 'false' );
				dot.dataset.vtLatestDot = String( i );

				dot.addEventListener(
					'click',
					function (event) {
						var index = parseInt( event.currentTarget.dataset.vtLatestDot, 10 );
						scrollToIndex( index, true );
					}
				);

				dotsWrap.appendChild( dot );
				state.dots.push( dot );
			}
		};

		var syncControls = function () {
			var visibleCount  = getVisibleCount( track );
			state.maxIndex    = Math.max( 0, slides.length - visibleCount );
			state.activeIndex = clamp( Math.round( slider.scrollLeft / getSlideStep( track ) ), 0, state.maxIndex );

			prev.disabled = 0 >= state.activeIndex;
			next.disabled = state.activeIndex >= state.maxIndex;

			state.dots.forEach(
				function (dot, index) {
					var isActive = index === state.activeIndex;
					dot.classList.toggle( 'is-active', isActive );
					dot.setAttribute( 'aria-selected', isActive ? 'true' : 'false' );
				}
			);
		};

		var scrollToIndex = function (index, smooth) {
			state.activeIndex = clamp( index, 0, state.maxIndex );
			slider.scrollTo(
				{
					left: state.activeIndex * getSlideStep( track ),
					behavior: smooth ? 'smooth' : 'auto',
				}
			);
			syncControls();
		};

		var onScroll = function () {
			if (state.raf) {
				window.cancelAnimationFrame( state.raf );
			}

			state.raf = window.requestAnimationFrame( syncControls );
		};

		var rebuild = function () {
			renderDots();
			syncControls();
		};

		prev.addEventListener(
			'click',
			function () {
				scrollToIndex( state.activeIndex - 1, true );
			}
		);

		next.addEventListener(
			'click',
			function () {
				scrollToIndex( state.activeIndex + 1, true );
			}
		);

		slider.addEventListener( 'scroll', onScroll, { passive: true } );

		window.addEventListener(
			'resize',
			function () {
				rebuild();
			}
		);

		rebuild();

		return {
			rebuild: rebuild,
			reset: function () {
				slider.scrollLeft = 0;
				state.activeIndex = 0;
				syncControls();
			},
			scrollToIndex: scrollToIndex,
		};
	};

	var initRoot = function (root) {
		var tabs        = Array.prototype.slice.call( root.querySelectorAll( '[data-vt-latest-tab]' ) );
		var panels      = Array.prototype.slice.call( root.querySelectorAll( '[data-vt-latest-panel]' ) );
		var archiveLink = root.querySelector( '[data-vt-latest-archive]' );
		var panelStates = {};

		if ( ! tabs.length || ! panels.length) {
			return;
		}

		panels.forEach(
			function (panel) {
				var slug          = panel.getAttribute( 'data-vt-latest-panel' );
				panelStates[slug] = initPanel( panel );
			}
		);

		var updateArchiveLink = function (slug) {
			if ( ! archiveLink) {
				return;
			}

			var activeTab = root.querySelector( '[data-vt-latest-tab="' + slug + '"]' );

			if ( ! activeTab) {
				return;
			}

			var nextHref  = activeTab.getAttribute( 'data-vt-latest-archive-link' );
			var nextLabel = activeTab.getAttribute( 'data-vt-latest-archive-label' );

			if (nextHref) {
				archiveLink.setAttribute( 'href', nextHref );
			}

			if (nextLabel) {
				archiveLink.textContent = nextLabel;
			}
		};

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

			updateArchiveLink( slug );

			window.requestAnimationFrame(
				function () {
					var state = panelStates[slug];

					if (state) {
						state.rebuild();
						state.reset();
					}
				}
			);
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
		activate( activeTab ? activeTab.getAttribute( 'data-vt-latest-tab' ) : tabs[0].getAttribute( 'data-vt-latest-tab' ) );
	};

	var ready = function () {
		document.querySelectorAll( '[data-vt-latest-posts]' ).forEach( initRoot );
	};

	if ('loading' === document.readyState) {
		document.addEventListener( 'DOMContentLoaded', ready );
	} else {
		ready();
	}
}());
