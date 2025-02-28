<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-3">Task Management</h2>

        <!-- Task Form -->
        <form id="taskForm" class="mb-4">
            <div class="mb-3">
                <label for="title" class="form-label">Task Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" required>
                <span id="titleError" class="text-danger"></span>
            </div>
            <button type="submit" class="btn btn-primary">Add Task</button>
        </form>

        <!-- Tasks Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="taskTable">

            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch tasks from API and display them
            function fetchTasks() {
                $.get("{{ url('/api/tasks') }}", function(data) {
                    let taskHtml = "";
                    data.forEach(task => {
                        taskHtml += `<tr>
                            <td>${task.id}</td>
                            <td>${task.title}</td>
                            <td>${task.is_completed ? "Completed" : "Pending"}</td>
                            <td>
                                <button class="btn btn-success btn-sm complete-task" data-id="${task.id}">Mark as Completed</button>
                            </td>
                        </tr>`;
                    });
                    $("#taskTable").html(taskHtml);
                });
            }

            // Fetch tasks on page load
            fetchTasks();

            // Add a new task
            $("#taskForm").submit(function(e) {
                e.preventDefault();
                let title = $("#title").val();

                $("#titleError").text("");

                $.ajax({
                    url: "{{ url('/api/tasks') }}",
                    method: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({
                        title
                    }),
                    success: function(response) {
                        alert("Task added successfully!");
                        $("#taskForm")[0].reset();
                        fetchTasks();
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON.error && xhr.responseJSON.error.includes(
                                "title")) {
                            $("#titleError").text(xhr.responseJSON.error);
                        } else {
                            alert("Error: " + xhr.responseJSON.error);
                        }
                    }
                });
            });

            // Mark task as completed
            $(document).on("click", ".complete-task", function() {
                let taskId = $(this).data("id");

                $.ajax({
                    url: `api/tasks/${taskId}`,
                    method: "PATCH",
                    contentType: "application/json",
                    data: JSON.stringify({
                        is_completed: true
                    }),
                    success: function(response) {
                        alert("Task marked as completed!");
                        fetchTasks();
                    },
                    error: function(xhr) {
                        alert("Error: " + xhr.responseJSON.error);
                    }
                });
            });
        });
    </script>
</body>

</html>
