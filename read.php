<?php
include 'config/db.php'; // ✅ Include database connection
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Students</title>

<style>
    body {
        font-family: Arial, sans-serif;
        background-image: url('https://images5.alphacoders.com/132/thumb-1920-1327658.jpeg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        background-attachment: fixed;
        color: #fff;
        margin: 0;
        padding: 0;
    }

    body::before {
        content: "";
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.55);
        z-index: 0;
    }

    .container {
        position: relative;
        z-index: 1;
        max-width: 90%;
        margin: 60px auto;
        background: rgba(255,255,255,0.1);
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        backdrop-filter: blur(8px);
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(255,255,255,0.85);
        color: #000;
        border-radius: 8px;
        overflow: hidden;
    }

    th, td {
        text-align: center;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #2ecc71;
        color: white;
    }

    tr:hover {
        background-color: rgba(46, 204, 113, 0.15);
    }

    a.action-btn {
        padding: 5px 10px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        color: white;
    }

    a.edit {
        background-color: #3498db;
    }

    a.delete {
        background-color: #e74c3c;
    }

    a.back-link {
        display: block;
        margin-top: 20px;
        text-align: center;
        color: #fff;
        text-decoration: none;
        font-weight: bold;
    }

    a.back-link:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Student Records</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Student No</th>
                <th>Full Name</th>
                <th>Branch</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Date Added</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // ✅ Fetch all students from DB
            $query = "SELECT * FROM students ORDER BY id DESC";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['student_no']}</td>
                            <td>{$row['fullname']}</td>
                            <td>{$row['branch']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['contact']}</td>
                            <td>{$row['date_added']}</td>
                            <td>
                                <a href='update.php?id={$row['id']}' class='action-btn edit'>Edit</a>
                                <a href='delete.php?id={$row['id']}' class='action-btn delete' onclick='return confirm(\"Are you sure you want to delete this student?\")'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No student records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="index.php" class="back-link">🏠 Back to Home</a>
</div>
</body>
</html>
