<?php
include 'classes/Database.php';
include 'classes/Task.php';
include 'classes/StatusHandler.php';

$database = new Database();
$taskObj = new Task($database);
$statusHandler = new StatusHandler();

if (!isset($orderBy)) {
    $orderBy = "0";
}

$status = $statusHandler->getStatus();

function humanReadableTimestamp($timestamp) {
    return date('F j, Y, g:i a', strtotime($timestamp));  // Example: "September 11, 2024, 10:12 am"
}
// can be called whenever order is updated
function renderTaskList($tasks) {
    echo "<ul>";
    foreach ($tasks as $task):
        echo "<li>";
        switch ($task['priority']) {
        case 1:
            $priority_text = "Low";
            break;
        case 2:
            $priority_text = "Normal";
            break;
        case 3:
            $priority_text = "High";
            break;
        case 4:
            $priority_text = "Urgent";
            break;
        case 5:
            $priority_text = "Critical";
            break;
        }
        echo "<body>[" . $priority_text . "] </body>";
        echo "<strong>" . htmlspecialchars($task['description']) . "</strong> ";
        if (!empty($task['updated_at'])):
            echo "<em>Last updated at: " . humanReadableTimestamp($task['updated_at']) . "</em> ";
        else:
            echo "<em>Created at: " . humanReadableTimestamp($task['created_at']) . "</em> ";
        endif;
        echo "<a href='actions/delete_task.php?id=" . $task["id"] . "'>Delete</a> ";
        echo "<a href='edit.php?id=" . $task["id"] . "'>Edit</a>";
        echo "</li>";
    endforeach;
    echo "</ul>";
}

// load tasks with correct order whenever page loaded
if (isset($_POST["orderBy"])) {
    $orderBy = $_POST["orderBy"];
    $tasks = $taskObj->getTasks($orderBy);
} else {
    $tasks = $taskObj->getTasks($orderBy);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
</head>
<body>
<h1>To-Do List</h1>

<!-- General Status Messages -->
<?php if ($status): ?>
    <p style="color: <?php echo ($status['type'] == 'success') ? 'green' : 'red'; ?>;">
        <?php echo htmlspecialchars($status['message']); ?>
    </p>
<?php endif; ?>

<!-- priority dropdown - 'Normal' (2) is default -->
<form action="actions/add_task.php" method="POST">
    <input type="text" name="task" placeholder="New Task" required>
    <select name="priority" id="priority">
        <option value="1" >Low</option>
        <option value="2" selected>Normal</option>
        <option value="3" >High</option>
        <option value="4" >Urgent</option>
        <option value="5" >Critical</option>
    </select>
    <button type="submit">Add Task</button>
</form>
<br>

<!-- order dropdown -->
<form method="POST" action="">
    <select name="orderBy" id="orderBy" onchange="this.form.submit()">
        <option value="0" <?php if ($orderBy == 0) echo 'selected'; ?>>Order tasks by priority</option>
        <option value="1" <?php if ($orderBy == 1) echo 'selected'; ?>>Order tasks by date created</option>
    </select>
</form>

<?php renderTaskList($tasks) ?>

</body>
</html>
