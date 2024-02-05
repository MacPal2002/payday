window.addEventListener('DOMContentLoaded', () => {
	// Declarations - mobile nav
	const hamburger = document.querySelector('.c-hamburger')
	const navPanelMobile = document.querySelector('.c-nav__panel')
	const body = document.querySelector('body')

	// Mobile nav

	hamburger.addEventListener('click', e => {
		navPanelMobile.classList.toggle('c-nav__panel--opened')
		body.classList.toggle('u-overflow-hidden')

		const targetButton = e.target.closest('button')

		targetButton.classList.toggle('h-opened')
		targetButton.classList.toggle('h-gototop')
		// navMobile.setAttribute('aria-expanded', navMobile.classList.contains('nav-mobile--opened'))
	})

	// Slick
	const cards = $('.l-cards')
	const mediaQuery = window.matchMedia('(max-width: 992px)')

	const handleSwitchSlick = e => {
		if (e.matches) {
			cards.slick({
				infinite: false,
				slidesToShow: 1,
				slidesToScroll: 1,
				// centerMode: true,
				arrows: false,
				variableWidth: true,
				focusOnSelect: true,
				centerPadding: 0,
			})
		} else if (cards.hasClass('slick-initialized')) {
			cards.slick('unslick')
		}
	}

	mediaQuery.addEventListener('change', handleSwitchSlick)
	handleSwitchSlick(mediaQuery)
})
