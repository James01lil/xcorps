<?php
$modeFile = 'mode.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newMode = ($_POST['mode'] === 'B') ? 'B' : 'A';
    file_put_contents($modeFile, $newMode);
    echo "<div class='alert'>✅ Mode updated to <strong>$newMode</strong></div>";
}

$currentMode = trim(file_get_contents($modeFile));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Toggle Panel</title>
  <style>
    :root {
      --bg-dark: #121212;
      --card: #1f1f1f;
      --text: #eaeaea;
      --accent: #00bcd4;
    }

    body {
      margin: 0;
      padding: 0;
      background-color: var(--bg-dark);
      font-family: 'Segoe UI', sans-serif;
      color: var(--text);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .panel {
      background-color: var(--card);
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 0 20px rgba(0, 188, 212, 0.1);
      max-width: 400px;
      width: 90%;
      text-align: center;
    }

    .panel h2 {
      color: var(--accent);
      margin-bottom: 1.5rem;
    }

    label {
      display: block;
      margin: 1rem 0;
      font-size: 0.95rem;
    }

    input[type="radio"] {
      margin-right: 10px;
      transform: scale(1.2);
    }

    button {
      margin-top: 1rem;
      padding: 0.6rem 1.5rem;
      background-color: var(--accent);
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #0097a7;
    }

    .alert {
      background-color: #1e3a3a;
      color: #00ffc8;
      padding: 0.8rem;
      margin-bottom: 1rem;
      border-radius: 8px;
      text-align: center;
    }

    @media (max-width: 500px) {
      .panel {
        padding: 1.2rem;
      }

      label {
        font-size: 0.85rem;
      }

      button {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="panel">
    <h2>Admin Toggle Panel</h2>
    <form method="post">
      <label>
        <input type="radio" name="mode" value="A" <?= $currentMode === 'A' ? 'checked' : '' ?>>
        sucessful withdrawal info billing <strong>[A]</strong>
      </label>
      <label>
        <input type="radio" name="mode" value="B" <?= $currentMode === 'B' ? 'checked' : '' ?>>
        Withdrawal with low trade signal cycle billing <strong>[B]</strong>
      </label>
      <button type="submit">Save</button>
    </form>
  </div>
</body>
</html>