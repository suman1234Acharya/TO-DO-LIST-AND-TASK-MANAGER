// ===============================
// TO-DO LIST & TASK MANAGER
// script.js
// ===============================

// Confirm Delete
function confirmDelete() {
    return confirm("Are you sure you want to delete this task?");
}

// Show Success Message
function showMessage(message) {
    alert(message);
}

// Validate Task Form
function validateTaskForm() {

    let title = document.getElementById("title").value.trim();
    let dueDate = document.getElementById("due_date").value;

    if (title === "") {
        alert("Task title cannot be empty.");
        return false;
    }

    if (dueDate !== "") {

        let today = new Date();
        today.setHours(0, 0, 0, 0);

        let selectedDate = new Date(dueDate);

        if (selectedDate < today) {
            alert("Due date cannot be in the past.");
            return false;
        }
    }

    return true;
}

// Search Tasks
function searchTask() {

    let input = document.getElementById("searchTask");
    let filter = input.value.toUpperCase();

    let table = document.getElementById("taskTable");
    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {

        let found = false;

        let td = tr[i].getElementsByTagName("td");

        for (let j = 0; j < td.length - 1; j++) {

            if (td[j]) {

                let txtValue = td[j].textContent || td[j].innerText;

                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                }
            }
        }

        tr[i].style.display = found ? "" : "none";
    }
}

// Live Clock
function updateClock() {

    let clock = document.getElementById("clock");

    if (clock) {

        let now = new Date();

        clock.innerHTML = now.toLocaleString();
    }
}

setInterval(updateClock, 1000);

// Auto Close Alerts
setTimeout(function () {

    let alerts = document.querySelectorAll(".alert");

    alerts.forEach(function (alert) {

        alert.style.transition = "0.5s";
        alert.style.opacity = "0";

        setTimeout(function () {
            alert.remove();
        }, 500);

    });

}, 3000);
