document.addEventListener("DOMContentLoaded", function(){
    getHabit();
    getCompletedHabit();
    document.getElementById("addModalForm").addEventListener("submit", function(){
        addHabit();
    });

    document.getElementById("editForm").addEventListener("submit", function(){
        updateHabit();
    });
});

function getHabit(){
    fetch("api/habit_api.php")
    .then(response => response.json())
    .then(data => {
        if(data.status === "success"){
            document.getElementById("habitList").innerHTML += "<h2>Your Habits</h2>";

            data.habits.forEach(habit => {
                document.getElementById("habitList").innerHTML += `
                    <div class="habit-item" data-id=${habit.habit_id}>
                        <div class="habit-info">
                            <h3>${habit.name}</h3>
                            <p>Frequency: ${habit.frequency}</p>
                            <p>Start Date: ${habit.start_date}</p>
                        </div>
                        <div class="habit-actions">
                            <button class="completeButtonStyle" data-id="${habit.habit_id}">
                                Complete
                            </button>
                            <button class="editButtonStyle" data-habit-id="${habit.habit_id}">Edit</button>
                            <button class="deleteButtonStyle" data-id="${habit.habit_id}">Delete</button>
                        </div>
                    </div>
                `;
            });

            document.querySelectorAll(".completeButtonStyle").forEach(button =>{
                button.addEventListener("click", function(){
                    const habitId = this.getAttribute("data-id");
                    const habitData = data.habits.find(h => h.habit_id == habitId);
                    if(habitData){
                        completeHabit(habitData);
                        location.reload();
                    }
                });
            });
            document.querySelectorAll(".editButtonStyle").forEach(button =>{
                button.addEventListener("click", function(){
                    const habitId = this.getAttribute("data-habit-id");
                    const habitData = data.habits.find(h => h.habit_id == habitId);
                    if(habitData){
                        showEditModal(habitData);
                    }
                });
            });

            document.querySelectorAll(".deleteButtonStyle").forEach(button =>{
                button.addEventListener("click", function(){
                    const habitId = this.getAttribute("data-id");
                    const habitData = data.habits.find(h => h.habit_id == habitId);
                    if(habitData){
                        deleteHabit(habitData);
                        location.reload();
                    }
                });
            });
        }
    })
    .catch(error => console.error(error));
}

function getCompletedHabit(){
    fetch("api/status_api.php")
    .then(response => response.json())
    .then(data => {
        if(data.status === "success"){
            document.getElementById("completedHabitList").innerHTML += "<h2>Your Completed Habits</h2>";

            data.habits.forEach(habit => {
                document.getElementById("completedHabitList").innerHTML += `
                    <div class="habit-item" data-id=${habit.habit_id}>
                        <div class="habit-info">
                            <h3 style="text-decoration: line-through;">${habit.name} </h3>
                            <p style="text-decoration: line-through;">Frequency: ${habit.frequency}</p>
                            <p style="text-decoration: line-through;">Start Date: ${habit.start_date}</p>
                        </div>
                        <div class="habit-actions">
                            <button class="editButtonStyle" data-habit-id="${habit.habit_id}" style="text-decoration: none;">Edit</button>
                            <button class="deleteButtonStyle" data-id="${habit.habit_id}" style="text-decoration: none;">Delete</button>
                        </div>
                    </div>
                `;
            });

            document.querySelectorAll(".editButtonStyle").forEach(button =>{
                button.addEventListener("click", function(){
                    const habitId = this.getAttribute("data-habit-id");
                    const habitData = data.habits.find(h => h.habit_id == habitId);
                    if(habitData){
                        showEditModal(habitData);
                    }
                });
            });

            document.querySelectorAll(".deleteButtonStyle").forEach(button =>{
                button.addEventListener("click", function(){
                    const habitId = this.getAttribute("data-id");
                    const habitData = data.habits.find(h => h.habit_id == habitId);
                    if(habitData){
                        deleteHabit(habitData);
                        location.reload();
                    }
                });
            });
        }
    })
    .catch(error => console.error(error));
}

function showEditModal(habitData){
    document.getElementById("edit-popup").classList.add("show");

    document.getElementById("cancelButton").addEventListener("click", function(event){
        event.preventDefault();
        document.getElementById("edit-popup").classList.remove("show");
    });

    document.getElementById("editHabitId").value = habitData.habit_id;
    document.getElementById("editName").value = habitData.name;
    document.getElementById("editFrequency").value = habitData.frequency;
    document.getElementById("editStartDate").value = habitData.start_date;
}

function addHabit(){
    const formData = {
        name: document.getElementById("name").value,
        frequency: document.getElementById("frequency").value,
        startDate: document.getElementById("startDate").value
    }
    fetch("api/habit_api.php", {
        method: "POST",
        body: JSON.stringify(formData),
        headers: {"Content-Type": "application/json"}
    })
    .then(response => response.json())
    .then(data => {

    })
    .catch(error => console.error(error));
}

function updateHabit(){
    const formData = {
        editHabitId: Number(document.getElementById("editHabitId").value),
        editName: document.getElementById("editName").value,
        editFrequency: document.getElementById("editFrequency").value,
        editStartDate: document.getElementById("editStartDate").value
    }
    fetch("api/habit_api.php", {
        method: "PUT",
        body: JSON.stringify(formData),
        headers: {"Content-Type": "application/json"}
    })
    .then(response => response.json())
    .then(data => {

    })
    .catch(error => console.error(error));
}

function deleteHabit(habit){
    const formData = {
        deleteHabitId: habit.habit_id
    }
    fetch("api/habit_api.php", {
        method: "DELETE",
        body: JSON.stringify(formData),
        headers: {"Content-Type": "application/json"}
    })
    .then(response => response.json())
    .then(data => {

    })
    .catch(error => console.error(error));
}

function completeHabit(habit){
    const formData = {
        id: habit.habit_id,
        status: "Completed"
    }
    fetch("api/status_api.php", {
        method: "POST",
        body: JSON.stringify(formData),
        headers: {"Content-Type": "application/json"}

    })
    .then(response => response.json())
    .then(data =>{

    })
    .catch(error => console.error(error));
}