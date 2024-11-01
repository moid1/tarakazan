// ##################### Add to Cart #################

// ################ cart1 ###############

let count1 = 0;

function updateCartCount1() {
    const cartCount1 = document.getElementById("cart-count1");
    cartCount1.textContent = count1 < 10 ? "0" + count1 : count1;
}

function increment1() {
    count1++;
    updateCartCount1();
}

function decrement1() {
    if (count1 > 0) {
        count1--;
        updateCartCount1();
    }
}

// ################### cart2 ##################

let count2 = 0;

function updateCartCount2() {
    const cartCount2 = document.getElementById("cart-count2");
    cartCount2.textContent = count2 < 10 ? "0" + count2 : count2;
}

function increment2() {
    count2++;
    updateCartCount2();
}

function decrement2() {
    if (count2 > 0) {
        count2--;
        updateCartCount2();
    }
}

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

//  ###################### Upload Product Form #######################

// ##################### CHARTS #######################
document.addEventListener("DOMContentLoaded", function () {
    // Create gradient for the chart background
    const revenueCtx = document.getElementById("revenueChart").getContext("2d");
    const gradient = revenueCtx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, "#ff9600"); // Top color
    gradient.addColorStop(1, "#ff960000"); // Bottom color

    // Revenue Statistics (Line Chart)
    const revenueChart = new Chart(revenueCtx, {
        type: "line",
        data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            datasets: [
                {
                    label: "Revenue",
                    data: [900, 400, 2000, 1100, 2100, 4500, 4500],
                    borderColor: "#ff9600",
                    borderWidth: 3,
                    fill: true,
                    backgroundColor: gradient,
                    tension: 0.4,
                    pointRadius: function (context) {
                        return context.raw === 2100 ? 6 : 0; // Show dot only for $2100
                    },
                    pointBackgroundColor: "#ffffff",
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    backgroundColor: "#209DBD",
                    displayColors: false, // Remove square box inside tooltip
                    callbacks: {
                        label: function (tooltipItem) {
                            return `$${tooltipItem.raw}`; // Show only the value
                        },
                        title: function () {
                            return ""; // Hide the title
                        },
                    },
                    titleFont: {
                        size: 16,
                        weight: 500,
                    },
                    bodyFont: {
                        size: 16,
                        weight: 500,
                    },
                },
                legend: {
                    display: false,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5000,
                    ticks: {
                        stepSize: 1000,
                        callback: function (value) {
                            return "$" + value;
                        },
                        font: {
                            family: "Lexend",
                            size: 14,
                            color: "#110D06",
                            weight: 300,
                        },
                    },
                    grid: {
                        color: "rgba(223, 223, 223, 0.5)",
                    },
                    border: {
                        dash: [3, 2],
                    },
                },
                x: {
                    grid: {
                        display: false, // Ensure no vertical grid lines are drawn
                    },
                    ticks: {
                        font: {
                            family: "Lexend",
                            size: 14,
                            color: "#110D06",
                            weight: 300,
                        },
                    },
                },
            },
        },
    });

    // Order Statistics (Bar Chart)
    const orderCtx = document.getElementById("orderChart").getContext("2d");
    const orderChart = new Chart(orderCtx, {
        type: "bar",
        data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            datasets: [
                {
                    label: "Orders",
                    data: [950, 4000, 5000, 2700, 2100, 3600, 5000],
                    backgroundColor: "#ff9600",
                    borderRadius: 10,
                    borderWidth: 1,
                    barThickness: 20,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    backgroundColor: "#ff9600",
                    displayColors: false, // Remove square box inside tooltip
                    callbacks: {
                        label: function (tooltipItem) {
                            return `${tooltipItem.raw}`; // Show only the value
                        },
                        title: function () {
                            return ""; // Hide the title
                        },
                    },
                    titleFont: {
                        size: 16,
                        weight: 500,
                    },
                    bodyFont: {
                        size: 16,
                        weight: 500,
                    },
                },
                legend: {
                    display: false,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    max: 5000,
                    ticks: {
                        stepSize: 1000,
                        callback: function (value) {
                            return value;
                        },
                        font: {
                            family: "Lexend",
                            size: 14,
                            color: "#ff9600",
                            weight: 300,
                        },
                    },
                    grid: {
                        color: "rgba(223, 223, 223, 0.5)",
                    },
                    border: {
                        dash: [3, 2],
                    },
                },
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        font: {
                            family: "Lexend",
                            size: 14,
                            color: "#110D06",
                            weight: 300,
                        },
                    },
                },
            },
        },
    });

    revenueChart.update("none");
    const ctx = revenueChart.ctx;
    ctx.save();

    revenueChart.data.datasets[0].data.forEach((value, index) => {
        if (value === 2100) {
            const x = revenueChart.getDatasetMeta(0).data[index].x;
            const y = revenueChart.getDatasetMeta(0).data[index].y - 10;
            ctx.fillStyle = "#110D06";
            ctx.font = "14px Lexend";
            ctx.fillText(`$${value}`, x, y);
        }
    });

    ctx.restore();
});

// document.addEventListener("DOMContentLoaded", function () {
//     //tarakazan
//     document
//         .getElementById("toggle-sidebar")
//         .addEventListener("click", function () {
//             const sidebar = document.querySelector(".chemist-sidebar");
//             sidebar.classList.toggle("collapsed");
//         });
// });
