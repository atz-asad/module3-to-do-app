<?php

//ToDo difine a constant for task file with task.json file


define("TASKs_FILE", "tasks.json");


//TODO: Create a function to load task from the task json file

function loadTasks(): array{
    if(!file_exists(TASKs_FILE)){
        return [];
    }

    $data = file_get_contents(TASKs_FILE);

    return $data ? json_decode($data, true) : [];

}

$tasks = loadTasks();


function saveTasks(array $tasks): void{
    file_put_contents(TASKs_FILE, json_encode($tasks, JSON_PRETTY_PRINT));
}



//ToDo: From has been submitted using post request

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    if(isset($_POST['task']) && !empty(trim($_POST['task'])) ){
        $tasks[] =[
            'task' => htmlspecialchars(trim($_POST['task'])),
            'done' => false,
        ];
        saveTasks($tasks);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;
    }elseif(isset($_POST['delete'])){
        //Delate Task
        unset($tasks[$_POST['delete']]);
        $tasks = array_values($tasks);
        saveTasks($tasks);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;
    }elseif(isset($_POST['toggle'])){
        //toggle task as complete 
        $tasks[$_POST['toggle']]['done'] = !$tasks[$_POST['toggle']]['done'];

        saveTasks($tasks);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

}







?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-control {
            box-shadow: none !important;
        }
        .task-done {
            text-decoration: line-through;
            color: #888;
        }
    </style>
</head>

<body>


    <div class="todo-wrapper-area" style="padding: 100px 0;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="todo-card border shadow p-5">
                        <h2 class="mb-3">To Do App</h2>
                        <form method="POST">
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-group mb-3">
                                        <input type="text" name="task" class="form-control" placeholder="Enter a new task" required aria-describedby="button-addon1">
                                        <button class="btn btn-primary px-5" type="submit" id="button-addon1">Button</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Task List -->
                        <h2 class="mt-4">Task List</h2>
                        <ul class="list-unstyled">
                            <?php if(empty($tasks)): ?>
                            <li class="task-item">No task yet. Add one Above!</li>
                            <?php else: ?>
                                <?php foreach($tasks as $index => $task): ?>
                                    <li class="d-flex mb-2 justify-content-between w-100">
                                        <form method="POST" class="w-100">
                                            
                                            <input type="hidden" name="toggle" value="<?= $index ?>">
                                        
                                            <button class="btn bg-none border text-start" type="submit" style="width:90%">
                                                <span class="task <?= $task['done'] ? 'task-done' : '' ?>">
                                                    <?= htmlspecialchars($task['task'])?>
                                                </span>
                                            </button>
                                        </form>

                                        <!-- delate task from -->
                                        <form method="POST">
                                            <input type="hidden" name="delete" value="<?= $index ?>">
                                            <button type="submit" class="btn btn-outline-primary">Delete</button>
                                        </form>
                                        
                                    </li>
                                <?php endforeach; ?>
                            <?php endif;?>
                                
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>