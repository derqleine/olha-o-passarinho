// Photo gallery: shows the photos in pages of 12 and opens them in a lightbox.

const PAGE_SIZE = 12;

document.addEventListener("DOMContentLoaded", () => {
  const gallery = document.getElementById("gallery");
  if (!gallery) {
    return; // No photos in this category
  }

  const photos = JSON.parse(document.getElementById("photos-data").textContent);
  const showMoreButton = document.getElementById("show-more");

  const lightbox = document.getElementById("lightbox");
  const lightboxImage = document.getElementById("lightbox-image");
  const lightboxDownload = document.getElementById("lightbox-download");
  const lightboxCounter = document.getElementById("lightbox-counter");
  const prevButton = document.getElementById("lightbox-prev");
  const nextButton = document.getElementById("lightbox-next");

  let shown = 0;
  let current = 0;

  // --- Grid ---

  function showMorePhotos() {
    const end = Math.min(shown + PAGE_SIZE, photos.length);

    for (let i = shown; i < end; i++) {
      const button = document.createElement("button");
      button.type = "button";
      button.className =
        "group block overflow-hidden rounded-xl bg-zinc-200 shadow-sm ring-1 ring-zinc-200 transition-shadow hover:shadow-lg focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand";
      button.setAttribute("aria-label", `Abrir foto ${i + 1}`);
      button.addEventListener("click", () => openLightbox(i));

      const img = document.createElement("img");
      img.src = photos[i];
      img.alt = "";
      img.loading = "lazy";
      img.width = 1280;
      img.height = 720;
      img.className = "aspect-video w-full object-cover transition-transform duration-300 group-hover:scale-105";

      button.appendChild(img);
      gallery.appendChild(button);
    }

    shown = end;
    showMoreButton.classList.toggle("hidden", shown >= photos.length);
  }

  // --- Lightbox ---

  function showPhoto() {
    lightboxImage.src = photos[current];
    lightboxDownload.href = photos[current];
    lightboxCounter.textContent = `${current + 1} / ${photos.length}`;
  }

  function openLightbox(index) {
    current = index;
    showPhoto();
    lightbox.classList.replace("hidden", "flex");
    document.body.classList.add("overflow-hidden");
  }

  function closeLightbox() {
    lightbox.classList.replace("flex", "hidden");
    document.body.classList.remove("overflow-hidden");
  }

  function move(step) {
    current = (current + step + photos.length) % photos.length;
    showPhoto();
  }

  showMoreButton.addEventListener("click", showMorePhotos);
  document.getElementById("lightbox-close").addEventListener("click", closeLightbox);
  prevButton.addEventListener("click", () => move(-1));
  nextButton.addEventListener("click", () => move(1));

  // Close when clicking the dark background
  lightbox.addEventListener("click", (event) => {
    if (event.target === lightbox) {
      closeLightbox();
    }
  });

  document.addEventListener("keydown", (event) => {
    if (lightbox.classList.contains("hidden")) {
      return;
    }
    if (event.key === "Escape") closeLightbox();
    if (event.key === "ArrowLeft") move(-1);
    if (event.key === "ArrowRight") move(1);
  });

  // No arrows when there is only one photo
  if (photos.length < 2) {
    prevButton.classList.add("hidden");
    nextButton.classList.add("hidden");
  }

  showMorePhotos();
});
