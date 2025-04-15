<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "student_db";
$conn = new mysqli($host, $user, $pass, $db);

// Handle registration
if (isset($_POST['register'])) {
    $stmt = $conn->prepare("INSERT INTO students (student_id, name, email, phone, course, address, date_of_join) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $_POST['student_id'], $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['course'], $_POST['address'], $_POST['date_of_join']);
    $stmt->execute();
    echo "<p>Student Registered Successfully.</p>";
}

// Handle search
$search_result = null;
if (isset($_POST['search'])) {
    $keyword = $_POST['keyword'];
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ? OR name LIKE ?");
    $like_keyword = "%$keyword%";
    $stmt->bind_param("ss", $keyword, $like_keyword);
    $stmt->execute();
    $search_result = $stmt->get_result();
}

// Handle delete
if (isset($_POST['delete'])) {
    $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $_POST['delete_id']);
    $stmt->execute();
    echo "<p>Student Deleted Successfully.</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration System</title>
    <style>
        body { font-family: Arial; margin: 30px; }
        form { margin-bottom: 20px; }
        input, textarea { display: block; margin-top: 5px; margin-bottom: 10px; width: 300px; }
    </style>
</head>
<body>
    <h2>Student Registration</h2>
    <form method="post">
        <label>Student ID:</label><input type="text" name="student_id" required>
        <label>Name:</label><input type="text" name="name" required>
        <label>Email:</label><input type="email" name="email" required>
        <label>Phone Number:</label><input type="text" name="phone" required>
        <label>Course:</label><input type="text" name="course" required>
        <label>Address:</label><textarea name="address" required></textarea>
        <label>Date of Join:</label><input type="date" name="date_of_join" required>
        <input type="submit" name="register" value="Register">
    </form>

    <h2>Search Student</h2>
    <form method="post">
        <label>Enter Student ID or Name:</label>
        <input type="text" name="keyword" required>
        <input type="submit" name="search" value="Search">
    </form>

    <?php if ($search_result && $search_result->num_rows > 0): ?>
        <h3>Search Results:</h3>
        <table border="1" cellpadding="10">
            <tr>
                <th>Student ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Course</th><th>Address</th><th>Date of Join</th><th>Action</th>
            </tr>
            <?php while($row = $search_result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['student_id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['phone'] ?></td>
                <td><?= $row['course'] ?></td>
                <td><?= $row['address'] ?></td>
                <td><?= $row['date_of_join'] ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?= $row['student_id'] ?>">
                        <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure?')">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php elseif (isset($_POST['search'])): ?>
        <p>No student found.</p>
    <?php endif; ?>

</body>
</html>
