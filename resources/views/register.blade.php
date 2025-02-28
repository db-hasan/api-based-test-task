<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-3">User Register</h2>

        <!-- Register Form -->
        <form id="registerForm" class="mb-4">
            <div class="mb-3">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" required>
                <span id="nameError" class="text-danger"></span>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" required>
                <span id="emailError" class="text-danger"></span>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password" name="password" required>
                <span id="passwordError" class="text-danger"></span>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password <span
                        class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    required>
                <span id="password_confirmationError" class="text-danger"></span>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <!-- Users Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody id="registerTable">
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchUsers() {
                $.get("{{ url('/api/register') }}", function(data) {
                    let userHtml = "";
                    data.forEach(user => {
                        userHtml += `<tr>
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td>${new Date(user.created_at).toLocaleString()}</td>
                        </tr>`;
                    });
                    $("#registerTable").html(userHtml);
                });
            }
            fetchUsers();

            $("#registerForm").submit(function(e) {
                e.preventDefault();
                let name = $("#name").val();
                let email = $("#email").val();
                let password = $("#password").val();
                let password_confirmation = $("#password_confirmation").val();

                $(".text-danger").text("");

                $.ajax({
                    url: "{{ url('/api/register') }}",
                    method: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({
                        name,
                        email,
                        password,
                        password_confirmation
                    }),
                    success: function(response) {
                        alert("User registered successfully!");
                        $("#registerForm")[0].reset();
                        fetchUsers();
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.error;
                        if (errors.name) {
                            $("#nameError").text(errors.name[0]);
                        }
                        if (errors.email) {
                            $("#emailError").text(errors.email[0]);
                        }
                        if (errors.password) {
                            $("#passwordError").text(errors.password[0]);
                        }
                        if (errors.password_confirmation) {
                            $("#password_confirmationError").text(errors.password_confirmation[
                                0]);
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
