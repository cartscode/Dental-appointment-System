
  const toggle = document.getElementById('menu-toggle');
  const navLinks = document.getElementById('nav-links');
  const navRight = document.querySelector('.nav-right');

  toggle.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    navRight.classList.toggle('active');
  });

