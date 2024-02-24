window.addEventListener('DOMContentLoaded', () => {
	// Dialog Boxes
	const cardActionsBtns = document.querySelectorAll('#cardActionsBtn')
	const dropdownBtns = document.querySelectorAll('#dropdownBtn')

	const openDialogBox = e => {
		const dialog = e.target.parentElement.querySelector('div[class*=dialog-box]')
		dialog.classList.toggle('u-visible')
	}

	for (const btn of [...cardActionsBtns, ...dropdownBtns]) {
		btn.addEventListener('click', openDialogBox)
	}

	const ifcloseDialogBox = e => {
		const target = e.target

		const closeDialogBox = () => {
			const dialog = document.querySelector('div[class*=dialog-box].u-visible')
			dialog !== null ? dialog.classList.remove('u-visible') : false
		}

		if (target.tagName === 'svg' || target.tagName === 'path') {
			closeDialogBox()
			return
		}

		const notCloseWhen = [!target.className.includes('u-visible'), !target.parentElement.id.includes('dialogButton')]
		notCloseWhen.every(condition => condition === true) ? closeDialogBox() : false
	}

	document.body.addEventListener('click', ifcloseDialogBox)

	// Data Table
	$(document).ready(function () {
		$('#userTable').DataTable({
			paging: false,
			info: false,
			scrollY: '48rem',
		})
	})

	// Inputs Suffixes
	const inputs = document.querySelectorAll('div[class*=--suffix] input')

	for (const input of inputs) {
		input.addEventListener('input', resizeInput)
		resizeInput.call(input)
	}

	function resizeInput() {
		let extraWidth = 0
		this.value.length === 0 ? (extraWidth = 1) : (extraWidth = 0)
		this.style.width = this.value.length + extraWidth + 'ch'
	}

	// Pillboxe's
	$(document).ready(function () {
		$('div[class*=--pillbox] select').select2()
	})
})
