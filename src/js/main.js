window.addEventListener('DOMContentLoaded', () => {
	const hamburger = document.querySelector('.c-hamburger')
	const navPanelMobile = document.querySelector('.c-nav__panel--mobile')

	hamburger.addEventListener('click', e => {
		navPanelMobile.classList.toggle('c-nav__panel--mobile--opened')

		const targetButton = e.target.closest('button')

		targetButton.classList.toggle('h-opened')
		targetButton.classList.toggle('h-gototop')
		// navMobile.setAttribute('aria-expanded', navMobile.classList.contains('nav-mobile--opened'))
	})
})
