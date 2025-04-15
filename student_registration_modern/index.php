<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "student_db";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$message = "";

if (isset($_POST['register'])) {
    $id = $_POST['student_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course = $_POST['course'];
    $address = $_POST['address'];
    $doj = $_POST['date_of_join'];

    $sql = "INSERT INTO students (student_id, name, email, phone, course, address, date_of_join)
            VALUES ('$id', '$name', '$email', '$phone', '$course', '$address', '$doj')";
    $message = $conn->query($sql) ? "Student Registered ✅" : "Error: " . $conn->error;
}

$search_result = null;
if (isset($_POST['search'])) {
    $term = $_POST['search_term'];
    $search_result = $conn->query("SELECT * FROM students WHERE student_id='$term' OR name LIKE '%$term%'");
}

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM students WHERE student_id='" . $_GET['delete'] . "'");
    header("Location: index.php?deleted=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration - Clean UI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: #fff;
            padding-top: 40px;
        }
        .sidebar h4 {
            color: #ffc107;
        }
        .nav-link {
            color: #adb5bd;
        }
        .nav-link.active, .nav-link:hover {
            color: #fff;
            background: #495057;
        }
        .main {
            padding: 40px;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .form-label {
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 sidebar d-flex flex-column align-items-center">
            <h4>🎓 Student System</h4>
            <nav class="nav flex-column mt-4 w-100">
                <a class="nav-link active" href="#register">Register Student</a>
                <a class="nav-link" href="#search">Search Student</a>
            </nav>
        </div>

        <!-- Main Area -->
        <div class="col-md-9 main">
            <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?= $message ?></div>
            <?php elseif (isset($_GET['deleted'])): ?>
                <div class="alert alert-warning">Student deleted successfully 🗑️</div>
            <?php endif; ?>

            <!-- Register Section -->
            <section id="register" class="mb-5">
                <h3 class="mb-3">Register New Student</h3>
                <div class="card p-4">
                    <form method="post">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Student ID</label>
                                <input type="text" name="student_id" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Course</label>
                                <input type="text" name="course" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date of Join</label>
                                <input type="date" name="date_of_join" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="2" required></textarea>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" name="register" class="btn btn-success">Register Student</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            <!-- Search Section -->
            <section id="search">
                <h3 class="mb-3">Search Student</h3>
                <div class="card p-4">
                    <form method="post" class="d-flex mb-3">
                        <input type="text" name="search_term" class="form-control me-2" placeholder="Enter Student ID or Name" required>
                        <button type="submit" name="search" class="btn btn-primary">Search</button>
                    </form>

                    <?php if ($search_result): ?>
                        <?php if ($search_result->num_rows > 0): ?>
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Course</th><th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $search_result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $row['student_id'] ?></td>
                                            <td><?= $row['name'] ?></td>
                                            <td><?= $row['email'] ?></td>
                                            <td><?= $row['phone'] ?></td>
                                            <td><?= $row['course'] ?></td>
                                            <td>
                                                <a href="?delete=<?= $row['student_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this record?');">
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-info">No student found.</div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
</div>

</body>
</html>
