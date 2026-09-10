const toggleBars = document.querySelector('.nav-toggle-bars');
const navMenu = document.querySelector('#navSection nav');

toggleBars.addEventListener('click', () => {
  navMenu.classList.toggle('active');
});
