function confirmCancel(apptId) {
    const penaltyWarning = `
⚠️ Cancellation Penalty Warning ⚠️

By proceeding with the cancellation, you acknowledge that a penalty fee of P500 (or equivalent based on service) may be charged if the cancellation is made less than 24 hours before the scheduled time.

Do you still want to proceed to the final cancellation confirmation?
(Click 'OK' to proceed, or 'Cancel' to keep the appointment)
`;

    // 1. Show Penalty Warning Prompt
    if (confirm(penaltyWarning)) {
        // 2. If user clicks OK (Yes, proceed), show the final confirmation
        if (confirm("Are you absolutely sure you want to cancel this appointment?")) {
            // If user confirms again, redirect to cancel script
            window.location.href = "cancel_appointment.php?id=" + apptId;
        } else {
            // User backed out at the final stage
            alert("Cancellation stopped. Your appointment remains active.");
        }
    } else {
        // User backed out at the penalty warning stage
        alert("Cancellation stopped. Your appointment remains active.");
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