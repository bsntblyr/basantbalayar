<?php
session_start(); // Start the session at the beginning

include "db_conn.php"; // Ensure this path is correct

if (isset($_POST['submit'])) // Use uppercase POST
{
  $username = $_POST['username']; // Use uppercase POST
  $password = $_POST['password']; // Use uppercase POST

  // Use prepared statements to prevent SQL injection
  $sql = "SELECT * FROM login WHERE username = ? AND password = ?";

  // Initialize prepared statement
  $stmt = mysqli_stmt_init($conn);

  // Prepare the SQL statement
  if (mysqli_stmt_prepare($stmt, $sql)) {
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Store result
    mysqli_stmt_store_result($stmt);

    // Check if there's a row that matches
    $count = mysqli_stmt_num_rows($stmt);

    if ($count == 1) {
      // Fetch the result row
      mysqli_stmt_bind_result($stmt, $id, $username, $password); 
      mysqli_stmt_fetch($stmt);

      // Store session variables
      $_SESSION['username'] = $username;
      $_SESSION['loggedin'] = true;

      // Redirect to dashboard upon successful login
      header('Location: Page/index.php');
      exit();
    } else {
      // Display error message if login fails
      echo '<script>
document.addEventListener("DOMContentLoaded", function() {
    const style = document.createElement("style");
    style.innerHTML = `
        .popup {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #ff0000;
            color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
        }
    `;
    document.head.appendChild(style);

    const popup = document.createElement("div");
    popup.className = "popup";
    popup.id = "login-failed-popup";
    popup.textContent = "Login failed.Please Check Username & Password";
    document.body.appendChild(popup);

    function showLoginFailedPopup() {
        popup.style.display = "block";
        setTimeout(() => {
            popup.style.display = "none";
        }, 3000);
    }

    showLoginFailedPopup();
});
</script>';
    }
  } else {
    // Error in preparing statement
    echo "SQL Error: " . mysqli_error($conn);
  }

  // Close statement
  mysqli_stmt_close($stmt);
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
  <div class="bg-img">
    <div class="content">
      <header>Login Form</header>
      <form action="#" method="POST">
        <div class="field">
          <span class="fa fa-user"></span>
          <input type="text" name="username" required placeholder="Username">
        </div>
        <div class="field space">
          <span class="fa fa-lock"></span>
          <input type="password" class="pass-key" name="password" required placeholder="Password">
          <span class="show">SHOW</span>
        </div>
        <div class="pass">
          <a> </a>
        </div>
        <div class="field">
          <input type="submit" value="LOGIN" name="submit">
        </div>
      </form>
    </div>
  </div>

  <script>
    const pass_field = document.querySelector('.pass-key');
    const showBtn = document.querySelector('.show');
    showBtn.addEventListener('click', function () {
      if (pass_field.type === "password") {
        pass_field.type = "text";
        showBtn.textContent = "HIDE";
        showBtn.style.color = "#3498db";
      } else {
        pass_field.type = "password";
        showBtn.textContent = "SHOW";
        showBtn.style.color = "#222";
      }
    });
  </script>
</body>
</html>
