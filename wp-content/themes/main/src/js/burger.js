document.addEventListener("DOMContentLoaded", () => {
    const header = document.querySelector(".header");
    const burger = document.querySelector(".burger");
    const mobileMenu = document.querySelector('.mobile-menu');
    const body = document.body;

    const updateMenuPosition = () => {
        mobileMenu.style.top = header.offsetHeight + "px";
    };

    updateMenuPosition();
    window.addEventListener("resize", updateMenuPosition);

    burger.addEventListener("click", () => {
        const isActive = burger.classList.toggle("active");

        mobileMenu.classList.toggle("active");
        header.classList.toggle("active");

        body.classList.toggle("no-scroll", isActive);
    });
});
