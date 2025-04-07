<?php
// Start the session to manage user login state
session_start();

// Redirect to the index page if the user is already logged in
if (isset($_SESSION['useremail'])) {
    header("Location: ./index.php");
    exit;
}

// Check if the login form has been submitted
if (isset($_POST['login'])) {
    // Include the database configuration file
    require_once '../common/config.php';

    // Retrieve and sanitize user input
    $useremail = $_POST['useremail'];
    $password = $_POST['password'];

    // Prevent SQL injection by escaping special characters
    $useremail = mysqli_real_escape_string($conn, $useremail);
    $password = mysqli_real_escape_string($conn, $password);

    // Query the database for the user with the provided email
    $sql = "SELECT * FROM admin WHERE Admin_email = '$useremail'";
    $result = mysqli_query($conn, $sql);

    // Check if a user with the provided email exists
    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Verify the provided password against the hashed password in the database
        if (password_verify($password, $user['Admin_password'])) {
            // Set session variables for the logged-in user
            $_SESSION['useremail'] = $user['Admin_email'];
            $_SESSION['user_id'] = $user['Admin_id'];
            $_SESSION['user_name'] = $user['Admin_name'];

            // Redirect to the index page after successful login
            header("Location: ./index.php");
            exit;
        } else {
            // Set an error message for invalid credentials
            $error = "Invalid email or password.";
        }
    } else {
        // Set an error message if no matching user is found
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <style>
        /* General box-sizing for all elements */
        * {
            box-sizing: border-box;
        }

        /* Styling for the body */
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #83a4d4, #b6fbff);
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Styling for the login box */
        .login-box {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        /* Center the heading */
        .login-box h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        /* Styling for form groups */
        .form-group {
            margin-bottom: 20px;
        }

        /* Styling for labels */
        label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
        }

        /* Styling for input fields */
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: 0.3s;
        }

        /* Highlight input fields on focus */
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #007BFF;
            outline: none;
        }

        /* Styling for the submit button */
        button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        /* Change button color on hover */
        button:hover {
            background-color: #0056b3;
        }

        /* Styling for error messages */
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <h2>Login</h2>

        <!-- Display error message if login fails -->
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Login form -->
        <form method="post">
            <div class="form-group">
                <label for="useremail">Email:</label>
                <input type="email" id="useremail" name="useremail" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login">Login</button>
        </form>
    </div>

</body>

</html>