document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");

  form.addEventListener("submit", function (e) {
    e.preventDefault(); 

    const name = form.querySelector("input[placeholder='Enter your name']").value.trim();
    const number = form.querySelector("input[placeholder='Enter your number']").value.trim();
    const email = form.querySelector("input[placeholder='Enter your email']").value.trim();
    const emergency = form.querySelector("input[placeholder='Enter your emergency contact']").value.trim();
    const username = form.querySelector("input[placeholder='Enter your username']").value.trim();
    const password = form.querySelector("input[placeholder='Enter your password']").value.trim();
    const gender = form.querySelector("input[name='gender']:checked");

    if (!name || !number || !email || !emergency || !username || !password || !gender) {
      alert("⚠️ Please fill out all fields before signing up.");
      return;
    }

    if (!validateEmail(email)) {
      alert("📧 Please enter a valid email address.");
      return;
    }

    if (password.length < 6) {
      alert("🔐 Password must be at least 6 characters long.");
      return;
    }

    alert(` Welcome to Dental+, ${name}!\nYour account has been created successfully.`);
    form.reset(); 
  });

  function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
  }
});
