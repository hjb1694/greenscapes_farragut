const navToggle = document.querySelector('.nav-toggle');
const headerPartRight = document.querySelector('.header__part--right');
const navClose = document.querySelector('.nav-close');

navToggle.addEventListener('click', () => {
    headerPartRight.classList.add('show');
});

navClose.addEventListener('click', () => {
    headerPartRight.classList.remove('show');
});

alert("This is only a demo!");