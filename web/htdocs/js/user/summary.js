"use strict";

window.addEventListener("DOMContentLoaded", event => {
	initRevealPasswordButton();
});

function initRevealPasswordButton() {
	document.getElementById("dionPasswordRevealButton").addEventListener("click", event => {
		const passwordInput = document.getElementById("dionPassword");
		event.target.remove();
		passwordInput.value = passwordInput.dataset["password"];
	});
}