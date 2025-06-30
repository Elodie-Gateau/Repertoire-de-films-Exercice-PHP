const confirmMessage = document.querySelector('.confirm');

if (confirmMessage.textContent !== '') {
  confirmMessage.classList.add('message');
  setTimeout(() => {
    confirmMessage.textContent = '';
    confirmMessage.classList.remove('message');
  }, 5000);
}

const errors = document.querySelector('.error');
const errorsItem = document.querySelectorAll('.error > ul > li');

if (errorsItem.length !== 0) {
  errors.classList.add('err-message');
}
