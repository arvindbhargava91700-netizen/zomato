/*=====================
    loader js
   ==========================*/

const hideLoader = () => {
  const loader = document.querySelector(".skeleton-loader");
  if (!loader) return;
  loader.classList.add("hidden");
};

// Hide the skeleton once the page (including images) has fully loaded
window.addEventListener("load", hideLoader);

// Fallback in case the load event is delayed or never fires
setTimeout(hideLoader, 6000);
