<?php
include 'classes/Database.php';
include 'classes/Task.php';
include 'classes/StatusHandler.php';

$database = new Database();
$taskObj = new Task($database);
$statusHandler = new StatusHandler();

if (is_null($_GET["page"])) {
    $page = 1;
} else {
    $page = $_GET["page"];
}

if (is_null($_GET["itemsPerPage"])) {
    $itemsPerPage = 10;
} else {
    $itemsPerPage= $_GET["itemsPerPage"];
}

$tasks = $taskObj->getTasks($itemsPerPage, $page);
$status = $statusHandler->getStatus();

function humanReadableTimestamp($timestamp) {
    return date('F j, Y, g:i a', strtotime($timestamp));  // Example: "September 11, 2024, 10:12 am"
}

if (isset($_POST["itemsPerPage"])) {
    $itemsPerPage = $_POST["itemsPerPage"];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>To-Do List</h1>

<!-- General Status Messages -->
<?php if ($status): ?>
    <p style="color: <?php echo ($status['type'] == 'success') ? 'green' : 'red'; ?>;">
        <?php echo htmlspecialchars($status['message']); ?>
    </p>
<?php endif; ?>

<form action="actions/add_task.php?<?php echo http_build_query($_GET); ?>" method="POST">
    <input type="text" name="task" placeholder="New Task" required>
    <button type="submit">Add Task</button>
</form>

<!-- tasks displayed per page with arbitrary fixed options -->
<form method="GET" action="">
    <select name="itemsPerPage" id="itemsPerPage" onchange="this.form.submit()">
        <option value="5" <?php if ($itemsPerPage == 5) echo 'selected'; ?>>5</option>
        <option value="10" <?php if ($itemsPerPage == 10) echo 'selected'; ?>>10</option>
        <option value="15" <?php if ($itemsPerPage == 15) echo 'selected'; ?>>15</option>
        <option value="20" <?php if ($itemsPerPage == 20) echo 'selected'; ?>>20</option>
    </select>
</form>

<ul>
    <?php foreach ($tasks as $task): ?>
        <li>
            <strong><?php echo htmlspecialchars($task['description']); ?></strong>
            <?php if (!empty($task['updated_at'])): ?>
                <em>Last updated at: <?php echo humanReadableTimestamp($task['updated_at']); ?></em>
            <?php else: ?>
                <em>Created at: <?php echo humanReadableTimestamp($task['created_at']); ?></em>
            <?php endif; ?>
            <a href="actions/delete_task.php?id=<?php echo $task['id']; ?>&<?php echo http_build_query($_GET); ?>">Delete</a>
            <a href="edit.php?id=<?php echo $task['id']; ?>&<?php echo http_build_query($_GET); ?>">Edit</a>
        </li>
    <?php endforeach; ?>
</ul>

<!-- navigation -->
<ul class="page-nav">
    <li><a href=<?php echo "'index.php?page=" . max($page - 1, 1) . "&itemsPerPage=" . $itemsPerPage . "'" ?>>&lt;Prev&gt;</a></li>
    <?php 
    $pageCount =$taskObj->getPageCount($itemsPerPage); 
    for ($i = 1; $i < $pageCount + 1; $i++) {
        echo "<li>";
        if ($i == $page) {
            echo "<u>" . $i . "</u>";
        } else {
            echo "<a href='index.php?page=" . $i . "&itemsPerPage=" . $itemsPerPage . "'>&lt;" . $i . "&gt;</a>";
        }
        echo "</li>";
    }
    ?>
    <li><a href=<?php echo "'index.php?page=" . min($page + 1, $pageCount) . "&itemsPerPage=" . $itemsPerPage . "'" ?>>&lt;Next&gt;</a></li>
</ul>

</body>
</html>