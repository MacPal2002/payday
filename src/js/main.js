window.addEventListener('DOMContentLoaded', () => {
	const hamburger = document.querySelector('.c-hamburger')
	const navPanelMobile = document.querySelector('.c-nav__panel')
	const body = document.querySelector('body')

	hamburger.addEventListener('click', e => {
		navPanelMobile.classList.toggle('c-nav__panel--opened')
		body.classList.toggle('u-overflow-hidden')

		const targetButton = e.target.closest('button')

		targetButton.classList.toggle('h-opened')
		targetButton.classList.toggle('h-gototop')
		// navMobile.setAttribute('aria-expanded', navMobile.classList.contains('nav-mobile--opened'))
	})
})
