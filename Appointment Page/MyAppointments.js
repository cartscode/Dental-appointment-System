
function confirmCancel(apptId) {
  if (confirm("Are you sure you want to cancel this appointment?")) {
    window.location.href = "cancel_appointment.php?id=" + apptId;
  }
}


    const toggle = document.getElementById("menu-toggle");
    const menu = document.getElementById("menu");
    toggle.addEventListener("click", () => menu.classList.toggle("active"));

    // Force reload on back navigation
  window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
      window.location.reload();
    }
  });