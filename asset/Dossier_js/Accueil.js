document.querySelectorAll(".car img").forEach(img => {
    img.addEventListener("mouseover", () => {
        img.style.transform = "scale(1.1)";
    });

    img.addEventListener("mouseleave", () => {
        img.style.transform = "scale(1)";
    });
});
