
  const menuToggle = document.getElementById('menu-toggle');
  const navLinks = document.getElementById('nav-links');
  const navRight = document.querySelector('.nav-right');

  menuToggle.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    navRight.classList.toggle('active');
  });

