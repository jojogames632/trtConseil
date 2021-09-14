// fade out success message after posting a job ad 
setTimeout(() => {
	let messageContainer = document.getElementById('successMessageContainer');
	messageContainer.classList.add('fade');
	setTimeout(() => {
		messageContainer.remove();
	}, 1000);
}, 5000);