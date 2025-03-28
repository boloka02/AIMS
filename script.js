console.log("AMS Dashboard Loaded");

// Sidebar Toggle for Mobile
function toggleSidebar() {
    document.querySelector(".sidebar").classList.toggle("active");
}

// Active Link Highlight
document.querySelectorAll(".nav-link").forEach(link => {
    link.addEventListener("click", function () {
        document.querySelectorAll(".nav-link").forEach(nav => nav.classList.remove("active"));
        this.classList.add("active");
    });
});


document.getElementById("searchEmployee").addEventListener("keyup", function () {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll("#employeeTable tr");
    
    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(value) ? "" : "none";
    });
});

console.log("script.js is loaded and working!");

