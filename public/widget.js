(function () {
  function findWidgetScript() {
    if (document.currentScript) return document.currentScript;

    const scripts = document.getElementsByTagName("script");
    for (let i = scripts.length - 1; i >= 0; i--) {
      const s = scripts[i];
      const src = s.getAttribute("src") || "";
      if (src.includes("widget.js")) return s;
    }
    return null;
  }

  function init() {
    const currentScript = findWidgetScript();
    if (!currentScript) {
      console.error("Chatbot widget: script tag not found.");
      return;
    }

    const SITE_KEY = currentScript.getAttribute("data-site-key") || "";
    if (!SITE_KEY) {
      console.error("Chatbot widget: missing data-site-key in script tag.");
      return;
    }

    const baseUrl = new URL(currentScript.src, window.location.href);
    const API_URL = `${baseUrl.origin}/api/chat/send`;

    const visitorId =
      localStorage.getItem("visitor_id") ||
      "visitor_" + Math.random().toString(36).substring(2);
    localStorage.setItem("visitor_id", visitorId);

    // ---------- Helpers ----------
    const WID = "cbw_" + Math.random().toString(36).slice(2);

    function el(tag, attrs = {}, children = []) {
      const e = document.createElement(tag);
      Object.keys(attrs).forEach((k) => {
        if (k === "style") e.setAttribute("style", attrs[k]);
        else if (k === "class") e.className = attrs[k];
        else if (k.startsWith("on") && typeof attrs[k] === "function")
          e.addEventListener(k.slice(2), attrs[k]);
        else e.setAttribute(k, attrs[k]);
      });
      children.forEach((c) => e.appendChild(typeof c === "string" ? document.createTextNode(c) : c));
      return e;
    }

    function escapeText(s) {
      return String(s ?? "");
    }

    // ---------- Inject scoped styles ----------
    const style = el("style", {}, [
      `
#${WID}_btn { all: initial; }
#${WID}_panel, #${WID}_panel * { box-sizing: border-box; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji"; }
@keyframes ${WID}_pop { 0%{transform: translateY(12px) scale(.96); opacity:0} 100%{transform: translateY(0) scale(1); opacity:1} }
@keyframes ${WID}_float { 0%,100%{transform: translateY(0)} 50%{transform: translateY(-3px)} }
@keyframes ${WID}_pulse { 0%,100%{opacity:.5} 50%{opacity:1} }
@keyframes ${WID}_msg { 0%{transform: translateY(6px); opacity:0} 100%{transform: translateY(0); opacity:1} }
@keyframes ${WID}_typing { 0%{transform: translateY(0); opacity:.4} 50%{transform: translateY(-3px); opacity:1} 100%{transform: translateY(0); opacity:.4} }
      `,
    ]);
    document.head.appendChild(style);

    // 1. The High-End "Drifting Pulse" Animation
const styleTag = document.createElement("style");
styleTag.textContent = `
  @keyframes ${WID}_float_impressionant {
    0% {
      transform: translateY(0) scale(1) rotate(0deg);
      filter: hue-rotate(0deg) brightness(1) drop-shadow(0 5px 15px rgba(79,70,229,0.4));
    }
    33% {
      transform: translateY(-20px) scale(1.08) rotate(2deg);
      filter: hue-rotate(20deg) brightness(1.2) drop-shadow(0 25px 35px rgba(6,182,212,0.6));
      border-radius: 24px; /* Morphing shape */
    }
    66% {
      transform: translateY(-10px) scale(0.95) rotate(-2deg);
      filter: hue-rotate(-20deg) brightness(1.3) drop-shadow(0 15px 25px rgba(109,40,217,0.7));
      border-radius: 12px; /* Morphing shape */
    }
    100% {
      transform: translateY(0) scale(1) rotate(0deg);
      filter: hue-rotate(0deg) brightness(1) drop-shadow(0 5px 15px rgba(79,70,229,0.4));
    }
  }
`;
document.head.appendChild(styleTag);

// 2. The Button Code
const btn = el("button", {
  id: `${WID}_btn`,
  "aria-label": "Open chat",
  style: `
    position: fixed;
    right: 18px;
    bottom: 18px;
    width: 62px;
    height: 62px;
    border: 0;
    border-radius: 18px;
    cursor: pointer;
    z-index: 2147483000;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    background: linear-gradient(135deg, #6D28D9, #4F46E5, #06B6D4);
    background-size: 200% 200%;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);

    /* THE ENGINE: Combines floating + warping */
    animation: ${WID}_float_impressionant 5s ease-in-out infinite;

    /* Smooths out the hardware rendering */
    will-change: transform, filter, border-radius;
    transition: all 0.3s ease;
  `,
});

    // Icon
    btn.innerHTML = `
      <div style="
        width:42px;height:42px;border-radius:14px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.22);
        display:flex;align-items:center;justify-content:center;
      ">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 512 512"><defs><linearGradient id="valibot__a" x1="0.414" y1="0.255" x2="0" y2="0.932" gradientUnits="objectBoundingBox"><stop offset="0" stop-color="#6648d0"/><stop offset="1" stop-color="#5e0576"/></linearGradient><linearGradient id="valibot__b" x1="0.338" y1="0.02" x2="0.664" y2="0.966" gradientUnits="objectBoundingBox"><stop offset="0" stop-color="#fde68a"/><stop offset="1" stop-color="#13889f"/></linearGradient><linearGradient id="valibot__c" y1="0.5" x2="1" y2="0.5" gradientUnits="objectBoundingBox"><stop offset="0" stop-color="#7dd3fc"/><stop offset="1" stop-color="#0ea5e9"/></linearGradient></defs><g transform="translate(-2056 -1899)"><rect width="512" height="512" transform="translate(2056 1899)" fill="none"/><path d="M742.271,987.024c-66.706,0-119.121,54.673-121.874,126.408l-2.551,95.471c-3.967,78.653,71.961,105.52,126.936,105.52Z" transform="translate(1463.458 1004.277)" fill="url(#valibot__a)"/><path d="M92.616.01H319.894c54.53-.8,95.624,40.1,98.381,93.335l6.144,135.76c.732,67.368-48.116,94.95-104.525,95.335L92.616,327.374C34.061,327.8-1.063,283.663.022,229.105l3.8-135.76C7.41,33.54,33.3,1.093,92.616.01Z" transform="translate(2118.42 1991.302)" fill="url(#valibot__b)"/><path d="M86.844.009H299.958c51.132-.746,89.665,37.307,92.25,86.824l5.761,126.29c.686,62.669-45.117,88.326-98.011,88.685L86.844,304.537C31.938,304.933-1,263.875.02,213.123L3.58,86.834C6.948,31.2,31.222,1.016,86.844.009Z" transform="translate(2136.977 2001.737)" fill="#111827"/><path d="M27.626,0A27.626,27.626,0,1,1,0,27.626,27.626,27.626,0,0,1,27.626,0Z" transform="translate(2421.148 2104.357)" fill="url(#valibot__c)"/><path d="M27.626,0A27.626,27.626,0,1,1,0,27.626,27.626,27.626,0,0,1,27.626,0Z" transform="translate(2208.034 2104.357)" fill="url(#valibot__c)"/></g></svg>
      </div>
      <span id="${WID}_badge" style="
        position:absolute;
        top:10px; right:10px;
        width:10px; height:10px;
        border-radius:999px;
        background:#22C55E;
        box-shadow: 0 0 0 3px rgba(255,255,255,.85);
      "></span>
    `;

    // ---------- Panel ----------
    const panel = el("div", {
      id: `${WID}_panel`,
      role: "dialog",
      "aria-label": "Chatbot",
      style: `
        position:fixed;
        right:18px;
        bottom:92px;
        width:360px;
        height:520px;
        max-height:75vh;
        display:none;
        z-index:2147483000;
        border-radius:22px;
        overflow:hidden;

        background: linear-gradient(180deg, rgba(255,255,255,.18), rgba(255,255,255,.10));
        border: 1px solid rgba(255,255,255,.22);
        box-shadow: 0 30px 80px rgba(0,0,0,.25);

        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
      `,
    });

    // Responsive width on small screens
    function applyResponsive() {
      const w = Math.min(360, Math.max(280, window.innerWidth - 28));
      panel.style.width = w + "px";
      panel.style.right = "14px";
      btn.style.right = "14px";
    }
    applyResponsive();
    window.addEventListener("resize", applyResponsive);

    // Header
    const header = el("div", {
      style: `
        height:74px;
        padding:12px 12px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        background: linear-gradient(135deg, rgba(79,70,229,.85), rgba(6,182,212,.55));
        border-bottom: 1px solid rgba(255,255,255,.18);
      `,
    });

    const leftHeader = el("div", { style: "display:flex; gap:10px; align-items:center;" });

    const avatar = el("div", {
      style: `
        width:44px;height:44px;border-radius:16px;
        background: radial-gradient(120% 120% at 20% 20%, rgba(255,255,255,.35), rgba(255,255,255,0) 60%),
                    rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.28);
        display:flex;align-items:center;justify-content:center;
        box-shadow: 0 10px 25px rgba(0,0,0,.15);
      `,
    });
    avatar.innerHTML = `<span style="font-size:18px">
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="40" height="40" viewBox="0 0 512 512"><defs><linearGradient id="valibot__a" x1="0.414" y1="0.255" x2="0" y2="0.932" gradientUnits="objectBoundingBox"><stop offset="0" stop-color="#6648d0"/><stop offset="1" stop-color="#5e0576"/></linearGradient><linearGradient id="valibot__b" x1="0.338" y1="0.02" x2="0.664" y2="0.966" gradientUnits="objectBoundingBox"><stop offset="0" stop-color="#fde68a"/><stop offset="1" stop-color="#13889f"/></linearGradient><linearGradient id="valibot__c" y1="0.5" x2="1" y2="0.5" gradientUnits="objectBoundingBox"><stop offset="0" stop-color="#7dd3fc"/><stop offset="1" stop-color="#0ea5e9"/></linearGradient></defs><g transform="translate(-2056 -1899)"><rect width="512" height="512" transform="translate(2056 1899)" fill="none"/><path d="M742.271,987.024c-66.706,0-119.121,54.673-121.874,126.408l-2.551,95.471c-3.967,78.653,71.961,105.52,126.936,105.52Z" transform="translate(1463.458 1004.277)" fill="url(#valibot__a)"/><path d="M92.616.01H319.894c54.53-.8,95.624,40.1,98.381,93.335l6.144,135.76c.732,67.368-48.116,94.95-104.525,95.335L92.616,327.374C34.061,327.8-1.063,283.663.022,229.105l3.8-135.76C7.41,33.54,33.3,1.093,92.616.01Z" transform="translate(2118.42 1991.302)" fill="url(#valibot__b)"/><path d="M86.844.009H299.958c51.132-.746,89.665,37.307,92.25,86.824l5.761,126.29c.686,62.669-45.117,88.326-98.011,88.685L86.844,304.537C31.938,304.933-1,263.875.02,213.123L3.58,86.834C6.948,31.2,31.222,1.016,86.844.009Z" transform="translate(2136.977 2001.737)" fill="#111827"/><path d="M27.626,0A27.626,27.626,0,1,1,0,27.626,27.626,27.626,0,0,1,27.626,0Z" transform="translate(2421.148 2104.357)" fill="url(#valibot__c)"/><path d="M27.626,0A27.626,27.626,0,1,1,0,27.626,27.626,27.626,0,0,1,27.626,0Z" transform="translate(2208.034 2104.357)" fill="url(#valibot__c)"/></g></svg></span>`;

    const titleBox = el("div", {});
    const title = el("div", { style: "color:white; font-weight:800; font-size:14px; letter-spacing:.2px;" }, ["Chat Support"]);
    const subtitle = el("div", { style: "color:rgba(255,255,255,.9); font-size:12px; display:flex; align-items:center; gap:8px;" });
    subtitle.innerHTML = `
      <span style="display:inline-flex; align-items:center; gap:6px;">
        <span style="width:8px;height:8px;border-radius:999px;background:#22C55E; box-shadow:0 0 0 3px rgba(255,255,255,.18)"></span>
        En ligne
      </span>
      <span style="opacity:.85">•</span>
      <span style="opacity:.95">Réponse rapide</span>
    `;
    titleBox.appendChild(title);
    titleBox.appendChild(subtitle);

    leftHeader.appendChild(avatar);
    leftHeader.appendChild(titleBox);

    const headerBtns = el("div", { style: "display:flex; gap:8px; align-items:center;" });

    const minimizeBtn = el("button", {
      "aria-label": "Minimize",
      style: `
        width:38px;height:38px;border-radius:14px;border:1px solid rgba(255,255,255,.25);
        background: rgba(255,255,255,.14);
        color:white; cursor:pointer;
        display:flex;align-items:center;justify-content:center;
      `,
      onclick: () => toggle(false),
    });
    minimizeBtn.innerHTML = `
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
        <path d="M6 12h12" stroke="rgba(255,255,255,.95)" stroke-width="2" stroke-linecap="round"/>
      </svg>
    `;

    headerBtns.appendChild(minimizeBtn);

    header.appendChild(leftHeader);
    header.appendChild(headerBtns);

    // Body (messages)
    const body = el("div", {
      style: `
        height: calc(100% - 74px - 70px);
        padding:14px 12px;
        overflow:auto;

        background:
          radial-gradient(120% 90% at 15% 10%, rgba(109,40,217,.16), rgba(0,0,0,0) 60%),
          radial-gradient(100% 80% at 85% 0%, rgba(6,182,212,.14), rgba(0,0,0,0) 60%),
          rgba(255,255,255,.08);
      `,
    });

    // Composer
    const composer = el("div", {
      style: `
        height:70px;
        padding:10px 10px;
        display:flex;
        gap:10px;
        align-items:center;

        background: linear-gradient(180deg, rgba(255,255,255,.10), rgba(255,255,255,.14));
        border-top: 1px solid rgba(255,255,255,.18);
      `,
    });

    const inputWrap = el("div", {
      style: `
        flex:1;
        height:46px;
        border-radius:16px;
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.24);
        display:flex; align-items:center;
        padding: 0 12px;
        box-shadow: inset 0 0 0 1px rgba(255,255,255,.06);
      `,
    });

    const input = el("input", {
      id: `${WID}_input`,
      placeholder: "Écrivez un message…",
      autocomplete: "off",
      style: `
        width:100%;
        border:0;
        outline:none;
        background: transparent;
        color: rgba(15,23,42,.95);
        font-size: 14px;
      `,
    });

    const send = el("button", {
      id: `${WID}_send`,
      "aria-label": "Send",
      style: `
        width:46px;height:46px;
        border-radius:16px;
        border:0;
        cursor:pointer;
        background: linear-gradient(135deg, #4F46E5, #06B6D4);
        box-shadow: 0 14px 30px rgba(79,70,229,.30);
        display:flex;align-items:center;justify-content:center;
      `,
    });
    send.innerHTML = `
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
        <path d="M22 2 11 13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M22 2 15 22l-4-9-9-4L22 2Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    `;

    inputWrap.appendChild(input);
    composer.appendChild(inputWrap);
    composer.appendChild(send);

    panel.appendChild(header);
    panel.appendChild(body);
    panel.appendChild(composer);

    document.body.appendChild(btn);
    document.body.appendChild(panel);

    // ---------- Messages ----------
    function addMessage(text, who = "bot") {
      const row = el("div", {
        style: `
          display:flex;
          margin: 10px 0;
          justify-content: ${who === "user" ? "flex-end" : "flex-start"};
          animation: ${WID}_msg .18s ease-out both;
        `,
      });

      const bubble = el("div", {
        style: `
          max-width: 78%;
          padding: 10px 12px;
          border-radius: 16px;
          line-height: 1.35;
          font-size: 13.5px;
          white-space: pre-wrap;
          word-wrap: break-word;

          ${who === "user"
            ? `
              color:white;
              background: linear-gradient(135deg, rgba(79,70,229,.95), rgba(6,182,212,.75));
              border: 1px solid rgba(255,255,255,.20);
              box-shadow: 0 10px 24px rgba(79,70,229,.25);
              border-bottom-right-radius: 6px;
            `
            : `
              color: rgba(15,23,42,.95);
              background: rgba(255,255,255,.65);
              border: 1px solid rgba(255,255,255,.35);
              box-shadow: 0 10px 22px rgba(0,0,0,.10);
              border-bottom-left-radius: 6px;
            `}
        `,
      });

      bubble.textContent = escapeText(text);
      row.appendChild(bubble);
      body.appendChild(row);
      body.scrollTop = body.scrollHeight;
    }

    let typingEl = null;
    function showTyping(show) {
      if (show) {
        if (typingEl) return;
        typingEl = el("div", { style: "display:flex; justify-content:flex-start; margin:10px 0;" });
        const bubble = el("div", {
          style: `
            padding:10px 12px;border-radius:16px;
            background: rgba(255,255,255,.55);
            border: 1px solid rgba(255,255,255,.35);
            box-shadow: 0 10px 22px rgba(0,0,0,.10);
            display:flex; gap:6px; align-items:center;
          `,
        });

        const dot = () =>
          el("span", {
            style: `
              width:7px;height:7px;border-radius:999px;
              background: rgba(15,23,42,.55);
              display:inline-block;
              animation: ${WID}_typing .9s ease-in-out infinite;
            `,
          });

        const d1 = dot();
        const d2 = dot();
        const d3 = dot();
        d2.style.animationDelay = ".12s";
        d3.style.animationDelay = ".24s";

        bubble.appendChild(d1);
        bubble.appendChild(d2);
        bubble.appendChild(d3);
        typingEl.appendChild(bubble);
        body.appendChild(typingEl);
        body.scrollTop = body.scrollHeight;
      } else {
        if (typingEl) typingEl.remove();
        typingEl = null;
      }
    }

    // ---------- Toggle ----------
    function toggle(open) {
      const willOpen = open ?? panel.style.display === "none";
      if (willOpen) {
        panel.style.display = "block";
        panel.style.animation = `${WID}_pop .18s ease-out both`;
        setTimeout(() => input.focus(), 60);
      } else {
        panel.style.display = "none";
      }
    }

    btn.addEventListener("click", () => toggle());
    // Close when pressing ESC
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") toggle(false);
    });

    // ---------- Send logic ----------
    async function sendMessage() {
      const message = input.value.trim();
      if (!message) return;

      addMessage(message, "user");
      input.value = "";
      showTyping(true);

      try {
        const res = await fetch(API_URL, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            site_key: SITE_KEY,
            visitor_id: visitorId,
            message,
          }),
        });

        if (!res.ok) throw new Error("HTTP " + res.status);
        const data = await res.json();

        showTyping(false);
        addMessage(data.reply ?? "", "bot");
      } catch (err) {
        console.error("Chatbot widget error:", err);
        showTyping(false);
        addMessage("❌ Error connecting to server. Please try again.", "bot");
      }
    }

    send.addEventListener("click", sendMessage);
    input.addEventListener("keydown", (e) => {
      if (e.key === "Enter") sendMessage();
    });

    // Welcome
    addMessage("Salam 👋 Ana Walid's bot. Kayn chi man9diw اليوم؟", "bot");

    // ---------- Polling ----------
    let lastMessageId = 0;
    const API_POLL = `${baseUrl.origin}/api/chat/poll`;

    function pollOnce() {
      const url = `${API_POLL}?site_key=${encodeURIComponent(SITE_KEY)}&visitor_id=${encodeURIComponent(visitorId)}&after_id=${lastMessageId}`;

      fetch(url)
        .then((res) => res.json())
        .then((data) => {
          (data.messages || []).forEach((m) => {
            lastMessageId = Math.max(lastMessageId, m.id);

            // Show only bot/support messages to client
            if (m.role === "bot" || m.role === "support") {
              addMessage(m.content, "bot");
            }
          });
        })
        .catch(() => {});
    }

    // ✅ Poll every 3 seconds
    setInterval(() => {
      // Poll even if closed (optional). If you prefer only when open, uncomment next line:
      // if (panel.style.display === "none") return;
      pollOnce();
    }, 3000);
  }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
