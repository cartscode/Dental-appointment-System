

  const toggle = document.getElementById('menu-toggle');
  const navLinks = document.getElementById('nav-links');
  const navRight = document.querySelector('.nav-right');

  toggle.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    navRight.classList.toggle('active');
  });

  // === Chatbot Toggle ===
const chatbotToggle = document.getElementById("chatbot-toggle");
const chatbotBox = document.getElementById("chatbot-box");
const closeChat = document.getElementById("close-chat");

chatbotToggle.addEventListener("click", () => {
  chatbotBox.classList.toggle("active");
});

closeChat.addEventListener("click", () => {
  chatbotBox.classList.remove("active");
});

// === Chatbot Messaging ===
const chatInput = document.getElementById("chatbot-input");
const sendBtn = document.getElementById("chatbot-send");
const chatMessages = document.getElementById("chatbot-messages");

sendBtn.addEventListener("click", async () => {
  const message = chatInput.value.trim();
  if (message === "") return;

  // Show user message
  const userMsg = document.createElement("div");
  userMsg.classList.add("user-message");
  userMsg.textContent = message;
  chatMessages.appendChild(userMsg);

  // Show bot "thinking..."
  const botMsg = document.createElement("div");
  botMsg.classList.add("bot-message");
  botMsg.textContent = "Thinking...";
  chatMessages.appendChild(botMsg);

  chatInput.value = "";
  chatMessages.scrollTop = chatMessages.scrollHeight;

  // === Send message to Gemini API backend ===
  try {
    const res = await fetch("/Project in IS104/homepage/gemini_api.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ message }),
    });

    const data = await res.json();
    const aiText =
      data.candidates?.[0]?.content?.parts?.[0]?.text ||
      "Sorry, I didn’t get that.";

    botMsg.textContent = aiText;
    chatMessages.scrollTop = chatMessages.scrollHeight;
  } catch (error) {
    botMsg.textContent = "Error: Unable to connect to AI server.";
    console.error(error);
  }
});
