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
You are DentaBot, a friendly and knowledgeable dental assistant.
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
  "toothache": "A toothache might be caused by decay or infection. Rinse with warm salt water, avoid very hot or cold food, and visit your dentist soon for an examination.",
  "braces": "Braces help align crooked teeth. If you have missing teeth, you can still get braces depending on your case. It's best to consult an orthodontist for evaluation.",
  "cleaning": "Dental cleaning removes plaque and tartar buildup. It’s recommended every 6 months to maintain gum and tooth health.",
  "whitening": "Teeth whitening lightens discoloration. You can choose between professional in-office whitening or dentist-approved home kits.",
  "bleeding gums": "Bleeding gums may indicate gingivitis. Brush gently, floss daily, and schedule a professional cleaning soon.",
  "missing teeth": "If you have missing teeth, options include dental implants, bridges, or dentures. An orthodontist can plan braces with replacements if needed."
};

// 🗺️ Function to Get User Location
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

// === SEND BUTTON FUNCTIONALITY ===
sendBtn.addEventListener("click", async () => {
  const message = chatInput.value.trim();
  if (message === "") return;

  // Display user message
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

  // === Detect user asking about dental clinics ===
  if (
    lowerMsg.includes("nearest dentist") ||
    lowerMsg.includes("dental clinic") ||
    lowerMsg.includes("dental near me")
  ) {
    getUserLocation((lat, lng) => {
      if (!lat || !lng) {
        botMsg.textContent =
          "⚠️ I can’t access your location right now. Please enable GPS or allow location access.";
        chatMessages.scrollTop = chatMessages.scrollHeight;
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
        botMsg.textContent = "Sorry, I couldn’t find any nearby dental clinics.";
      }

      chatMessages.scrollTop = chatMessages.scrollHeight;
    });
    return;
  }

  // === Detect Dental Treatment Questions ===
  const foundKeyword = Object.keys(dentalTreatments).find(keyword =>
    lowerMsg.includes(keyword)
  );

  if (foundKeyword) {
    botMsg.textContent = dentalTreatments[foundKeyword];
    chatMessages.scrollTop = chatMessages.scrollHeight;
    return;
  }

  // === Otherwise: Send to Gemini API with one-sentence rule ===
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

    // ✅ Trim and shorten to one sentence if needed
    if (aiText) {
      aiText = aiText.trim();
      if (aiText.length > 180) {
        aiText = aiText.split(".")[0] + ".";
      }
    }

    // ✅ Show Gemini or fallback to Google
    if (aiText && aiText !== "") {
      botMsg.textContent = aiText;
    } else {
      const query = encodeURIComponent(message);
      const googleLink = `https://www.google.com/search?q=${query}+dental+care`;
      botMsg.innerHTML = `I'm not sure about that one 🤔. Try checking this: <a href="${googleLink}" target="_blank">Google Search Result 🔍</a>`;
    }
  } catch (error) {
    console.error(error);
    const query = encodeURIComponent(message);
    const googleLink = `https://www.google.com/search?q=${query}+dental+care`;
    botMsg.innerHTML = `⚠️ My connection is down right now, but you can check this: <a href="${googleLink}" target="_blank">Google Search Result 🔍</a>`;
  }

  chatMessages.scrollTop = chatMessages.scrollHeight;
});
