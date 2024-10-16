const carousel = document.querySelector(".carousel");
const arrowButtons = document.querySelectorAll(".wrapper i");
const firstCardWidth = carousel.querySelector(".card").offsetWidth;

arrowButtons.forEach( btn => {
    btn.addEventListener("click", () => {
        carousel.scrollLeft += btn.id == "left" ? -(firstCardWidth + 12) : firstCardWidth + 12; 
    })
});

let isClicking = false;
let startX;
let startScrollLeft;

const dragStarted = (e) => {
    e.preventDefault(); // Evita comportamentos indesejados ao arrastar
    isClicking = true;
    carousel.classList.add("dragging");

    // Verifica se está sendo usado um evento de toque ou clique
    startX = e.pageX || e.touches[0].pageX;
    startScrollLeft = carousel.scrollLeft;
}

const dragEnded = () => {
    isClicking = false;
    carousel.classList.remove("dragging");
}

const dragging = (e) => {
    if (!isClicking) return;

    // Captura as coordenadas do cursor durante o arrasto
    const x = e.pageX || e.touches[0].pageX;
    carousel.scrollLeft = startScrollLeft - (x - startX);
}

// Eventos para mouse
carousel.addEventListener("mousedown", dragStarted);
carousel.addEventListener("mousemove", dragging);
document.addEventListener("mouseup", dragEnded);

// Suporte para toque
carousel.addEventListener("touchstart", dragStarted);
carousel.addEventListener("touchmove", dragging);
carousel.addEventListener("touchend", dragEnded);