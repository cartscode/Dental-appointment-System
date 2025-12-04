// === MENU TOGGLE ===
const toggle = document.getElementById('menu-toggle');
const navLinks = document.getElementById('nav-links');
const navRight = document.querySelector('.nav-right');

toggle.addEventListener('click', () => {
  navLinks.classList.toggle('active');
  navRight.classList.toggle('active');
});

// === CHATBOT TOGGLE ===
const chatbotToggle = document.getElementById("chatbot-toggle");
const chatbotBox = document.getElementById("chatbot-box");
const closeChat = document.getElementById("close-chat");

chatbotToggle.addEventListener("click", () => {
  chatbotBox.classList.toggle("active");
});

closeChat.addEventListener("click", () => {
  chatbotBox.classList.remove("active");
});

// === CHATBOT MESSAGING ===
const chatInput = document.getElementById("chatbot-input");
const sendBtn = document.getElementById("chatbot-send");
const chatMessages = document.getElementById("chatbot-messages");

// ======================================================
// ⭐ PREDEFINED SUGGESTION ANSWERS (YOUR CUSTOM REPLIES)
// ======================================================
const predefinedAnswers = {
  "Toothache remedies": "For temporary toothache relief, try warm salt water, cold compress, or ibuprofen — but it's best to visit the clinic for proper examination.",
  "Dental cleaning price": "Dental cleaning usually costs ₱800–₱1,500 depending on the procedure needed.",
  "Clinic operating hours": "Our clinic is open Monday to Saturday, 9:00 AM to 6:00 PM.",
  "How to book appointment?": "You can book an appointment through your Dental+ account by selecting a schedule on the Appointment page."
};

// ======================================================
// ⭐ SUGGESTION BUTTON CLICK BEHAVIOR (AUTO SEND ANSWER)
// ======================================================
document.querySelectorAll(".suggest-btn").forEach(button => {
  button.addEventListener("click", () => {

    const question = button.innerText;
    const answer = predefinedAnswers[question];

    // Show user message
    const userMsg = document.createElement("div");
    userMsg.classList.add("user-message");
    userMsg.textContent = question;
    chatMessages.appendChild(userMsg);

    // Show bot reply (specific answer)
    const botMsg = document.createElement("div");
    botMsg.classList.add("bot-message");
    botMsg.textContent = answer || "I'm not sure about that, but feel free to ask anything dental-related!";
    chatMessages.appendChild(botMsg);

    chatMessages.scrollTop = chatMessages.scrollHeight;
  });
});

// 🦷 Local Dental Clinics Database
const dentalClinics = [
  {
    name: "Infiniteeth Dental Care Center - Quezon City Branch",
    address: "2nd Floor, FIMAR Arcade, Masaya St., Philcoa, Commonwealth, Diliman, Quezon City, Philippines 1101",
    lat: 14.6575,
    lng: 121.0587,
    open: "Mon–Sat: 9AM–6PM",
    contact: "+63 997 604 5391"
  },
  {
    name: "Infiniteeth Dental Care Center - Montalban Branch",
    address: "2nd Floor, Casa Carmelita Bldg., Unit H, E. Rodriguez Hwy, Rodriguez (Montalban), Rizal, Philippines 1860",
    lat: 14.7319,
    lng: 121.1415,
    open: "Daily: 9AM–4PM",
    contact: "0955 341 7799"
  },
  {
    name: "Infiniteeth Dental Care Center - Antipolo Branch",
    address: "COGEO Ave, Antipolo City, Rizal, Philippines",
    lat: 14.6231,
    lng: 121.1657,
    open: "Daily: 10AM–4PM",
    contact: "0997 604 5392"
  }
];

// 🦷 DENTAL ASSISTANT PERSONALITY PROMPT
const dentalAssistantPrompt = `
You are AI Denta Assistant , a friendly and knowledgeable dental assistant.
Your job is to:
- Help patients with dental concerns such as toothache, cleaning, braces, or whitening.
- Suggest possible treatments in simple, helpful terms.
- Encourage visiting the dentist for any serious pain or swelling.
- Only respond to dental-related questions or oral hygiene topics.
If the user asks about anything not dental-related, politely say:
"I'm here only for dental care and clinic assistance 😊"
`;

// 🧩 Common Dental Treatment Suggestions
const dentalTreatments = {
  "toothache": "A toothache might be caused by decay or infection. Rinse with warm salt water, avoid very hot or cold food, and visit your dentist soon.",
  "braces": "Braces help align teeth. Even with missing teeth you can still get braces depending on evaluation.",
  "cleaning": "Dental cleaning removes plaque and tartar. Recommended every 6 months.",
  "whitening": "Teeth whitening lightens discoloration. Choose between in-office whitening or dentist-approved kits.",
  "bleeding gums": "Bleeding gums may indicate gingivitis. Brush gently, floss daily, and schedule a cleaning.",
  "missing teeth": "Missing teeth options include implants, bridges, or dentures."
};

// 🗺️ Get User Location
function getUserLocation(callback) {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => callback(position.coords.latitude, position.coords.longitude),
      () => callback(null, null)
    );
  } else {
    callback(null, null);
  }
}

// 📨 SEND BUTTON LOGIC
sendBtn.addEventListener("click", async () => {
  const message = chatInput.value.trim();
  if (message === "") return;

  // Show user message
  const userMsg = document.createElement("div");
  userMsg.classList.add("user-message");
  userMsg.textContent = message;
  chatMessages.appendChild(userMsg);

  // Bot placeholder
  const botMsg = document.createElement("div");
  botMsg.classList.add("bot-message");
  botMsg.textContent = "Thinking...";
  chatMessages.appendChild(botMsg);

  chatInput.value = "";
  chatMessages.scrollTop = chatMessages.scrollHeight;

  const lowerMsg = message.toLowerCase();

  // === Nearest Dentist Detection ===
  if (
    lowerMsg.includes("nearest dentist") ||
    lowerMsg.includes("dental clinic") ||
    lowerMsg.includes("dental near me")
  ) {
    getUserLocation((lat, lng) => {
      if (!lat || !lng) {
        botMsg.textContent = "⚠️ I can’t access your location. Please enable GPS.";
        return;
      }

      let nearest = null;
      let minDistance = Infinity;

      dentalClinics.forEach((clinic) => {
        const distance = Math.sqrt(
          Math.pow(lat - clinic.lat, 2) + Math.pow(lng - clinic.lng, 2)
        );

        if (distance < minDistance) {
          minDistance = distance;
          nearest = clinic;
        }
      });

      if (nearest) {
        const mapLink = `https://www.google.com/maps?q=${nearest.lat},${nearest.lng}`;
        botMsg.innerHTML = `🦷 <b>Nearest Dental Clinic:</b><br>
<b>${nearest.name}</b><br>
📍 ${nearest.address}<br>
🕒 ${nearest.open}<br>
📞 ${nearest.contact}<br><br>
<a href="${mapLink}" target="_blank">🗺️ View on Map</a>`;
      } else {
        botMsg.textContent = "No nearby clinics found.";
      }
    });
    return;
  }

  // === Dental Treatment Keyword Matching ===
  const foundKeyword = Object.keys(dentalTreatments).find(keyword =>
    lowerMsg.includes(keyword)
  );

  if (foundKeyword) {
    botMsg.textContent = dentalTreatments[foundKeyword];
    return;
  }

  // === Gemini API (Fallback) ===
  try {
    const res = await fetch("/Project in IS104/homepage/gemini_api.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        message: `${dentalAssistantPrompt}
User: ${message}
Please answer briefly in one helpful sentence related to dental care only.`
      }),
    });

    const data = await res.json();
    let aiText = data?.candidates?.[0]?.content?.parts?.[0]?.text;

    if (aiText) {
      aiText = aiText.trim();
      if (aiText.length > 180) {
        aiText = aiText.split(".")[0] + ".";
      }
      botMsg.textContent = aiText;
    } else {
      botMsg.textContent = "I'm not sure. You can ask anything about dental care.";
    }
  } catch (error) {
    botMsg.textContent = "⚠️ I can't reach my server right now.";
  }

  chatMessages.scrollTop = chatMessages.scrollHeight;
});

// =====🎤 Voice Dictation =====
const voiceBtn = document.getElementById("chatbot-voice");
const inputField = document.getElementById("chatbot-input");

if ("SpeechRecognition" in window || "webkitSpeechRecognition" in window) {
  const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
  recognition.lang = "en-US";
  recognition.interimResults = false;

  voiceBtn.addEventListener("click", () => {
    voiceBtn.classList.add("listening");
    recognition.start();
  });

  recognition.onresult = (event) => {
    const voiceText = event.results[0][0].transcript;
    inputField.value = voiceText;
    document.getElementById("chatbot-send").click();
  };

  recognition.onend = () => {
    voiceBtn.classList.remove("listening");
  };
} else {
  voiceBtn.style.display = "none";
  console.warn("Speech recognition not supported.");
}
