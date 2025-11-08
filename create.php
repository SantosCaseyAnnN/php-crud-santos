<?php
include 'config/db.php'; // ✅ database connection

$popupType = '';
$popupMessage = '';

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
        // ✅ Insert data if valid
        $query = "INSERT INTO students (student_no, fullname, branch, email, contact)
                  VALUES ('$student_no', '$fullname', '$branch', '$email', '$contact')";

        if (mysqli_query($conn, $query)) {
            $popupType = 'success';
            $popupMessage = '✅ Student added successfully!';
        } else {
            $popupType = 'error';
            $popupMessage = '❌ Error adding student: ' . mysqli_error($conn);
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Student</title>

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
        background: #2ecc71;
        border: none;
        color: white;
        font-weight: bold;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover { background: #27ae60; }

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
        <h2>Add New Student</h2>

        <form method="POST" action="">
            <input type="text" name="student_no" placeholder="Student Number" required><br>
            <input type="text" name="fullname" placeholder="Full Name" required><br>
            <select name="branch" required>
                <option value="" disabled selected>Select Branch</option>
                <option value="Manila">Manila</option>
                <option value="Cebu">Cebu</option>
                <option value="Davao">Davao</option>
            </select><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="text" name="contact" placeholder="Contact" required><br>

            <button type="submit">Add Student</button>
        </form>

        <a class="back-link" href="index.php">🏠 Back to Home</a>
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

        // Auto close after 3 seconds
        setTimeout(() => {
            popup.style.display = 'none';
        }, 3000);
    }
</script>
</body>
</html>
