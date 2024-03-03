window.addEventListener('DOMContentLoaded', () => {
	const body = document.querySelector('body')
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

	// Remove element

	let removeElementButtons = document.querySelectorAll('button[class^=remove]')

	const removeElement = e => {
		const targetElement = e.target.closest('[data-removable="true"]')

		const potentialSlick = targetElement.parentElement.parentElement
		potentialSlick.classList.contains('slick-slide') ? potentialSlick.remove() : false

		targetElement.remove()
	}

	for (const button of removeElementButtons) {
		button.addEventListener('click', removeElement)
	}

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

	console.log($('div[class*=--pillbox] select').length)

	// Modals

	let modalsCloseButtons = document.querySelectorAll('.c-modal__close')
	let modalsSaveButtons = document.querySelectorAll('.c-modal__save')

	const closeModal = e => {
		const targetModal = e.target.closest('.c-modal')
		body.classList.remove('u-overflow-hidden')
		targetModal.classList.remove('u-visible--from-top')
	}

	const saveModal = e => {
		closeModal(e)
	}

	for (const button of modalsCloseButtons) {
		button.addEventListener('click', closeModal)
	}

	for (const button of modalsSaveButtons) {
		button.addEventListener('click', saveModal)
	}

	class modalInitializator {
		constructor(addButtonSelector, editButtonSelector, modalSelector) {
			this.addButton = document.querySelector(addButtonSelector)
			this.editButtons = document.querySelectorAll(editButtonSelector)
			this.modal = document.querySelector(modalSelector)
		}
	}

	const modals = [
		new modalInitializator('#addSubscription', '.editSubscription', '#subscriptionModal'),
		new modalInitializator('#addUser', '.editUser', '#userModal'),
		new modalInitializator('.addFunds', '.addFunds', '#addFundsModal'),
	]

	const showModal = ({modal}) => {
		body.classList.add('u-overflow-hidden')
		modal.classList.add('u-visible--from-top')
	}

	for (const modal of modals) {
		for (const button of [modal.addButton, ...modal.editButtons]) {
			button.addEventListener('click', () => {
				showModal(modal)
			})
		}
	}
})
