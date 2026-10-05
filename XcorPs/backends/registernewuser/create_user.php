<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create New User</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #101820;
      color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    .card {
      background-color: #1c1c2e;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.4);
      width: 100%;
      max-width: 450px;
    }

    .card h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 22px;
      color: #00bcd4;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 500;
      font-size: 14px;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 15px;
      border-radius: 6px;
      border: none;
      background-color: #2b2b3d;
      color: #fff;
      font-size: 15px;
    }

    input[type="submit"] {
      width: 100%;
      padding: 12px;
      background-color: #00bcd4;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    input[type="submit"]:hover {
      background-color: #019bab;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 20px;
      color: #bbb;
      font-size: 14px;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }

    @media (max-width: 500px) {
      .card {
        padding: 20px;
      }

      .card h2 {
        font-size: 20px;
      }

      input[type="text"],
      input[type="email"],
      input[type="password"] {
        font-size: 14px;
        padding: 10px;
      }

      input[type="submit"] {
        font-size: 15px;
        padding: 10px;
      }

      label {
        font-size: 13px;
      }
    }
  </style>
</head>
<body>

  <div class="card">
    <h2>Enroll New User</h2>
    <form action="create_user_process.php" method="POST">
      <label>First Name</label>
      <input type="text" name="first_name" required>

      <label>Last Name</label>
      <input type="text" name="last_name" required>

      <label>Username</label>
      <input type="text" name="user_name" required>

      <label>Email</label>
      <input type="email" name="email" required>

      <label>Password</label>
      <input type="text" name="password" required>

      <input type="submit" value="Create User">
    </form>

    <a class="back-link" href="../admin_dashboard.php">← Back to Dashboard</a>
  </div>

</body>
</html>