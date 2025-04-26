

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habit Tracker</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    
    <h1>Habit Tracker</h1>

    <!-- Add Modal -->
    <div class="habit-form">
        <h2>Add New Habit</h2>
        <form method="POST" id="addModalForm">
            <div>
                <label for="name">Habit Name:</label>
                <input type="text" id="name" name="name" required autocomplete="off">
            </div>
            <div>
                <label for="frequency">Frequency:</label>
                <select id="frequency" name="frequency" required>
                    <option value="Daily">Daily</option>
                    <option value="Weekly">Weekly</option>
                </select>
            </div>
            <div>
                <label for="start_date">Start Date:</label>
                <input type="date" id="startDate" name="startDate" required>
            </div>
            <button type="submit" class="button">Add Habit</button>
        </form>
    </div>
    <!-- Edit Modal -->
    <div class="edit-popup" id="edit-popup">
        <div class="edit-popup-content">
            <form class="edit-form" method="PUT" id="editForm">
                <input type="hidden" name="editHabitId" id="editHabitId">
                <div>
                    <label>Habit Name:</label>
                    <input type="text" name="editName" id="editName" required>
                </div>
                <div>
                    <label>Frequency:</label>
                    <select name="editFrequency" id="editFrequency" required>
                        <option value="Daily">Daily</option>
                        <option value="Weekly">Weekly</option>
                    </select>
                </div>
                <div>
                    <label>Start Date:</label>
                    <input type="date" name="editStartDate" id="editStartDate" required>
                </div>
                <button type="submit" class="button">Save</button>
                <button type="button" class="button button-cancel" id="cancelButton">Cancel</button>
            </form>
        </div>
    </div>


  <!-- Display Habit -->
    <div class="habit-list" id="habitList">
    </div>

    <!-- Complete Habit -->
    <div class="habit-list" id="completedHabitList">
    </div>

    <script src="js/script.js"></script>
    
</body>
</html>