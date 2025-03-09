
document.querySelectorAll(".box").forEach(box => {
    box.addEventListener("mouseover", () => {
        box.style.transform = "scale(1.05)";
        box.style.transition = "0.3s";
    });

    box.addEventListener("mouseout", () => {
        box.style.transform = "scale(1)";
    });
});