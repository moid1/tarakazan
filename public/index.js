

// ########################### Chemist Hamburger Menu ########################

function toggleHamburger(element) {
    element.classList.toggle("active");
}

function toggleclick() {
    document
        .querySelector(".hamburger-container")
        .addEventListener("click", function () {
            let menu = document.querySelector(".chemist-top-menubar");

            if (menu.style.display === "none" || menu.style.display === "") {
                menu.style.display = "block"; // Show menu
                document.querySelector(".small-screen-nav").style.paddingTop =
                    "0";
                document.querySelector(".small-screen-nav").style.paddingLeft =
                    "0";
                document.querySelector(
                    ".small-screen-nav"
                ).style.paddingBottom = "0";
                document.querySelector(".small-screen-nav").style.paddingRight =
                    "0.75rem";
                document.querySelector(".hamburger-container").style.margin =
                    "1.5rem 1rem";
            } else {
                menu.style.display = "none"; // Hide menu
                document.querySelector(".small-screen-nav").style.padding =
                    "1rem";
                document.querySelector(".hamburger-container").style.margin =
                    "0";
            }
        });
}
