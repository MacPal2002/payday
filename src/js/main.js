window.addEventListener('DOMContentLoaded', () => {
	// Declarations
	// nav
	const hamburger = document.querySelector('.c-hamburger')
	const navPanelMobile = document.querySelector('.c-nav__panel')
	const main = document.querySelector('main')

	// slick
	const cards = $('.l-cards')
	const mediaQuery = window.matchMedia('(max-width: 880px)')

	// Mobile nav
	hamburger.addEventListener('click', e => {
		navPanelMobile.classList.toggle('c-nav__panel--opened')
		main.classList.toggle('u-display-none')

		const targetButton = e.target.closest('button')
		targetButton.classList.toggle('h-opened')
	})

	// Slick
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
