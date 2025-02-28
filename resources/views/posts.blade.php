<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-3">Blog Posts</h2>

        <!-- Post Form -->
        <form id="postForm" class="mb-4">
            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" required>
                <span id="titleError" class="text-danger"></span>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                <span id="contentError" class="text-danger"></span>
            </div>
            <button type="submit" class="btn btn-primary">Add Post</button>
        </form>

        <!-- Posts Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Created At</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="postTable">

            </tbody>
        </table>
    </div>

    <!-- Modal for Viewing a Single Post -->
    <div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl custom-modal-width">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="postModalLabel">Post Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>ID:</strong> <span id="postId"></span></p>
                    <p><strong>Title:</strong> <span id="postTitle"></span></p>
                    <p><strong>Content:</strong> <span id="postContent"></span></p>
                    <p><strong>Created At:</strong> <span id="postCreatedAt"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchPosts() {
                $.get("{{ url('/api/posts') }}", function(data) {
                    let postHtml = "";
                    data.forEach(post => {
                        postHtml += `<tr>
                            <td>${post.id}</td>
                            <td>${post.title}</td>
                            <td>${post.content}</td>
                            <td>${new Date(post.created_at).toLocaleString()}</td>
                            <td class="text-end"><button class="btn btn-info btn-sm view-post" data-id="${post.id}">View</button></td>
                        </tr>`;
                    });
                    $("#postTable").html(postHtml);
                });
            }
            fetchPosts();

            $("#postForm").submit(function(e) {
                e.preventDefault();
                let title = $("#title").val();
                let content = $("#content").val();

                $("#titleError").text("");
                $("#contentError").text("");

                $.ajax({
                    url: "{{ url('/api/posts') }}",
                    method: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({
                        title,
                        content
                    }),
                    success: function(response) {
                        alert("Post added successfully!");
                        $("#postForm")[0].reset();
                        fetchPosts();
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON.error && xhr.responseJSON.error.includes(
                                "title")) {
                            $("#titleError").text(xhr.responseJSON
                                .error);
                        } else if (xhr.responseJSON.error && xhr.responseJSON.error.includes(
                                "content")) {
                            $("#contentError").text(xhr.responseJSON
                                .error);
                        } else {
                            alert("Error: " + xhr.responseJSON.error);
                        }
                    }
                });
            });

            $(document).on("click", ".view-post", function() {
                let postId = $(this).data("id");

                $.get("{{ url('/api/posts') }}/" + postId, function(post) {
                    $("#postId").text(post.id);
                    $("#postTitle").text(post.title);
                    $("#postContent").text(post.content);
                    $("#postCreatedAt").text(new Date(post.created_at).toLocaleString());

                    let postModal = new bootstrap.Modal(document.getElementById("postModal"));
                    postModal.show();
                }).fail(function() {
                    alert("Post not found!");
                });
            });
        });
    </script>
</body>

</html>
