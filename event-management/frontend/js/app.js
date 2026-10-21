document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.querySelector("[data-toggle-password]");
  const password = document.querySelector("#password");
  if (toggle && password) {
    toggle.addEventListener("click", () => {
      const show = password.type === "password";
      password.type = show ? "text" : "password";
      toggle.textContent = show ? "Hide" : "Show";
      toggle.setAttribute("aria-label", show ? "Sembunyikan password" : "Tampilkan password");
    });
  }
});
