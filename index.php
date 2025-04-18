<?php
require_once __DIR__ . '/includes/db.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false];
    
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $stmt = $pdo->prepare("INSERT INTO habits (name, frequency, start_date) VALUES (?, ?, ?)");
                $stmt->execute([$_POST['name'], $_POST['frequency'], $_POST['start_date']]);
                header('Location: index.php');
                exit;
                break;

            case 'complete':
                $stmt = $pdo->prepare("UPDATE habits SET completed = NOT completed WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $response['success'] = true;
                echo json_encode($response);
                exit;
                break;

                case 'delete':
                    try {
                        $stmt = $pdo->prepare("DELETE FROM habits WHERE id = ?");
                        $stmt->execute([$_POST['id']]);
                        $response['success'] = true;
                        $response['message'] = 'Habit deleted successfully';
                    } catch (PDOException $e) {
                        $response['success'] = false;
                        $response['message'] = $e->getMessage();
                    }
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit;
                    break;

                    case 'edit':
                        try {
                            $stmt = $pdo->prepare("UPDATE habits SET name = ?, frequency = ?, start_date = ? WHERE id = ?");
                            $stmt->execute([
                                $_POST['name'],
                                $_POST['frequency'],
                                $_POST['start_date'],
                                $_POST['id']
                            ]);
                            $response['success'] = true;
                            $response['message'] = 'Habit updated successfully';
                        } catch (PDOException $e) {
                            $response['success'] = false;
                            $response['message'] = $e->getMessage();
                        }
                        header('Content-Type: application/json');
                        echo json_encode($response);
                        exit;
                        break;
        }
    }
}


$stmt = $pdo->query("SELECT * FROM habits ORDER BY created_at DESC");
$habits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habit Tracker</title>
    <style>
  
        :root {
            --primary-color: #1a73e8;
            --danger-color: #dc3545;
            --bg-color: #f0f2f5;
            --text-color: #202124;
            --shadow: 0 2px 10px rgba(0,0,0,0.1);
        }


        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        h1 {
            color: var(--primary-color);
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 3px solid var(--primary-color);
        }

        
        .habit-form {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .habit-form h2 {
            margin-top: 0;
            color: var(--text-color);
        }

        .habit-form div {
            margin-bottom: 15px;
        }

        .habit-form label {
            display: block;
            margin-bottom: 8px;
            color: #5f6368;
            font-weight: 500;
        }

        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(26,115,232,0.2);
        }


        .button {
            background: var(--primary-color);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .button:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .button-delete {
            background: var(--danger-color);
        }

        .button-delete:hover {
            background: #bb2d3b;
        }

     
        .habit-list {
            margin-top: 30px;
            
        }

        .habit-item {
            background: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s;
        }

        .habit-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .habit-item h3 {
            margin: 0 0 8px 0;
            color: var(--text-color);
        }

        .habit-item p {
            margin: 5px 0;
            color: #5f6368;
        }

        .habit-item div:last-child {
            display: flex;
            gap: 10px;
        }

        .edit-form {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-top: 10px;
    width: 100%;
}

.edit-form div {
    margin-bottom: 10px;
}

.button-edit {
    background: #ffc107;
    color: #000;
}

.button-edit:hover {
    background: #e0a800;
}

.button-cancel {
    background: #6c757d;
}

.button-cancel:hover {
    background: #5a6268;
}

.habit-item {
    background: white;
    padding: 20px;
    margin-bottom: 15px;
    border-radius: 12px;
    box-shadow: var(--shadow);
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.habit-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.habit-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.edit-popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    backdrop-filter: blur(3px);
}

.edit-popup-content {
    background: white;
    padding: 30px;
    border-radius: 12px;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    position: relative;
    animation: slideIn 0.3s ease-out;
}

.edit-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.edit-form div {
    margin-bottom: 15px;
}

.edit-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: var(--text-color);
}

.edit-form input,
.edit-form select {
    width: 100%;
    padding: 10px;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    font-size: 16px;
    width: 340px;
}

.edit-form .button {
    margin-top: 10px;
}




        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .habit-item {
                flex-direction: column;
                text-align: center;
            }

            .habit-item div:last-child {
                margin-top: 15px;
            }
        }
    </style>
</head>
<body>
    <h1>Habit Tracker</h1>


    <div class="habit-form">
        <h2>Add New Habit</h2>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <div>
                <label for="name">Habit Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="frequency">Frequency:</label>
                <select id="frequency" name="frequency" required>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                </select>
            </div>
            <div>
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required>
            </div>
            <button type="submit" class="button">Add Habit</button>
        </form>
    </div>


    <div class="habit-list">
    <h2>Your Habits</h2>
    <?php foreach ($habits as $habit): ?>
        <div class="habit-item" data-id="<?= $habit['id'] ?>">
            <div class="habit-info">
            <h3><?= htmlspecialchars($habit['name']) ?></h3>
            <p>Frequency: <?= htmlspecialchars($habit['frequency']) ?></p>
            <p>Start Date: <?= htmlspecialchars($habit['start_date']) ?></p>
            </div>
            <div class="habit-actions">
            <button class="button complete-habit" data-id="<?= $habit['id'] ?>">
                <?= $habit['completed'] ? 'Undo' : 'Complete' ?>
            </button>
            <button class="button button-edit" data-id="<?= $habit['id'] ?>" onclick="openEditPopup(<?= $habit['id'] ?>)">Edit</button>
            <button class="button button-delete delete-habit" data-id="<?= $habit['id'] ?>">Delete</button>
            </div>
        </div>

        <!-- Edit Popup -->
        <div class="edit-popup" id="edit-popup-<?= $habit['id'] ?>" style="display: none;">
            <div class="edit-popup-content">
            <form class="edit-form">
                <input type="hidden" name="id" value="<?= $habit['id'] ?>">
                <div>
                <label>Habit Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($habit['name']) ?>" required>
                </div>
                <div>
                <label>Frequency:</label>
                <select name="frequency" required>
                    <option value="daily" <?= $habit['frequency'] === 'daily' ? 'selected' : '' ?>>Daily</option>
                    <option value="weekly" <?= $habit['frequency'] === 'weekly' ? 'selected' : '' ?>>Weekly</option>
                </select>
                </div>
                <div>
                <label>Start Date:</label>
                <input type="date" name="start_date" value="<?= $habit['start_date'] ?>" required>
                </div>
                <button type="submit" class="button">Save</button>
                <button type="button" class="button button-cancel" onclick="closeEditPopup(<?= $habit['id'] ?>)">Cancel</button>
            </form>
            </div>
        </div>

        <script>
            function openEditPopup(id) {
            document.getElementById('edit-popup-' + id).style.display = 'flex';
            }

            function closeEditPopup(id) {
            document.getElementById('edit-popup-' + id).style.display = 'none';
            }
        </script>
    <?php endforeach; ?>
    
</div>

    <script src="js/display.js"></script>
    
</body>
</html>