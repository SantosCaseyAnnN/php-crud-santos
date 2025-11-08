<?php
include 'config/db.php'; // ✅ include database connection

$popupType = '';
$popupMessage = '';
$redirect = false;

// ✅ Get student ID from URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM students WHERE id = $id");

    if (mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
    } else {
        die("⚠️ Student not found.");
    }
} else {
    die("⚠️ Invalid request. No student ID provided.");
}

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_no = trim($_POST['student_no']);
    $fullname   = trim($_POST['fullname']);
    $branch     = trim($_POST['branch']);
    $email      = trim($_POST['email']);
    $contact    = trim($_POST['contact']);

    // ✅ Validation checks
    if (empty($student_no) || empty($fullname) || empty($branch) || empty($email) || empty($contact)) {
        $popupType = 'error';
        $popupMessage = '⚠️ All fields are required!';
    } elseif (!is_numeric($contact)) {
        $popupType = 'error';
        $popupMessage = '⚠️ Contact number must be numeric only!';
    } elseif (strlen($contact) != 11 && strlen($contact) != 12) {
        $popupType = 'error';
        $popupMessage = '⚠️ Contact number must be 11 or 12 digits long!';
    } else {
        // ✅ If all valid, run UPDATE query
        $query = "UPDATE students 
                  SET student_no='$student_no', fullname='$fullname', branch='$branch', email='$email', contact='$contact'
                  WHERE id = $id";

        if (mysqli_query($conn, $query)) {
            $popupType = 'success';
            $popupMessage = '✅ Student record updated successfully!';
            $redirect = true;
        } else {
            $popupType = 'error';
            $popupMessage = '❌ Error updating record: ' . mysqli_error($conn);
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Student</title>

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
        max-width: 480px;
        margin: 100px auto;
        background: rgba(255,255,255,0.1);
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        text-align: center;
        backdrop-filter: blur(8px);
    }

    h2 { margin-bottom: 18px; }

    form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    input, select {
        width: 90%;
        padding: 10px;
        margin: 8px 0;
        border: none;
        border-radius: 6px;
        background: rgba(255,255,255,0.85);
        color: #000;
        font-size: 15px;
        box-sizing: border-box;
    }

    button {
        width: 95%;
        padding: 10px;
        background: #3498db;
        border: none;
        color: white;
        font-weight: bold;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover { background: #2980b9; }

    .back-link {
        display: block;
        margin-top: 15px;
        color: #fff;
        text-decoration: none;
    }

    /* ===== Popup Styles ===== */
    .popup {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        justify-content: center;
        align-items: center;
        z-index: 999;
        animation: fadeIn 0.3s ease-in-out;
    }

    .popup-content {
        background: rgba(255,255,255,0.95);
        color: #000;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 6px 20px rgba(0,0,0,0.4);
        max-width: 320px;
        animation: popUp 0.35s ease;
    }

    .popup-content img {
        width: 120px;
        height: auto;
        margin-bottom: 10px;
    }

    .popup-content h3 {
        margin: 0;
        font-size: 20px;
        color: #333;
    }

    @keyframes fadeIn {
        from {opacity: 0;} to {opacity: 1;}
    }
    @keyframes popUp {
        from {transform: scale(0.8); opacity: 0;}
        to {transform: scale(1); opacity: 1;}
    }
</style>
</head>
<body>
    <div class="container">
        <h2>Update Student Info</h2>

        <form method="POST" action="">
            <input type="text" name="student_no" value="<?php echo $student['student_no']; ?>" required>
            <input type="text" name="fullname" value="<?php echo $student['fullname']; ?>" required>
            <select name="branch" required>
                <option value="Manila" <?php echo ($student['branch'] == 'Manila') ? 'selected' : ''; ?>>Manila</option>
                <option value="Cebu" <?php echo ($student['branch'] == 'Cebu') ? 'selected' : ''; ?>>Cebu</option>
                <option value="Davao" <?php echo ($student['branch'] == 'Davao') ? 'selected' : ''; ?>>Davao</option>
            </select>
            <input type="email" name="email" value="<?php echo $student['email']; ?>" required>
            <input type="text" name="contact" value="<?php echo $student['contact']; ?>" required>

            <button type="submit">Update Student</button>
        </form>

        <a class="back-link" href="read.php">📄 Back to Records</a>
    </div>

    <!-- Wanderer Popup -->
    <div class="popup" id="popup">
        <div class="popup-content" id="popup-content">
            <img id="popup-img" src="" alt="">
            <h3 id="popup-text"></h3>
        </div>
    </div>

<script>
    const popupType = "<?php echo $popupType; ?>";
    const popupMessage = "<?php echo $popupMessage; ?>";
    const redirect = <?php echo $redirect ? 'true' : 'false'; ?>;

    if (popupType) {
        const popup = document.getElementById('popup');
        const img = document.getElementById('popup-img');
        const text = document.getElementById('popup-text');

        if (popupType === 'success') {
            img.src = "https://s3.getstickerpack.com/storage/uploads/sticker-pack/genshin-impact-wanderer/sticker_4.png?843fd7058a1f79b65b13d715c6132bed";
        } else {
            img.src = "https://s3.getstickerpack.com/storage/uploads/sticker-pack/genshin-impact-wanderer/sticker_3.png?843fd7058a1f79b65b13d715c6132bed";
        }

        text.innerHTML = popupMessage;
        popup.style.display = 'flex';

        setTimeout(() => {
            popup.style.display = 'none';
            if (redirect) {
                window.location.href = "read.php";
            }
        }, 3000);
    }
</script>
</body>
</html>
