window.addEventListener('DOMContentLoaded', () => {
	const cardActionsBtn = document.querySelectorAll('#cardActionsBtn')

	const openPopup = e => {
		const dialog = e.target.parentElement.querySelector('div[class*=dialog-box]')
		dialog.classList.toggle('u-visible')
	}

	cardActionsBtn.forEach(btn => btn.addEventListener('click', openPopup))

	const ifClosePopup = e => {
		const target = e.target

		const closePopup = () => {
			const dialog = document.querySelector('div[class*=dialog-box].u-visible')
			dialog !== null ? dialog.classList.remove('u-visible') : false
		}

		if (target.tagName === 'svg' || target.tagName === 'path') {
			closePopup()
			return
		}

		const notCloseWhen = [!target.className.includes('u-visible'), !target.parentElement.className.includes('__button')]
		notCloseWhen.every(condition => condition === true) ? closePopup() : false
	}

	document.body.addEventListener('click', ifClosePopup)

	$(document).ready(function () {
		$('#myTable').DataTable()
	})
})
