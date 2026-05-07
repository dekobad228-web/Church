document.addEventListener("DOMContentLoaded", () => {
    const header = document.querySelector(".header");
    const firstSection = document.querySelector("section");

    if (!header || !firstSection) return;

    if (firstSection.classList.contains("banner")) {
        const updateBannerOffset = () => {
            firstSection.style.marginTop = `${header.offsetHeight + 50}px`;
        };

        updateBannerOffset();

        window.addEventListener("resize", updateBannerOffset);
    }
});