<?php
$message = "";

if (isset($_POST['userName'], $_POST['userPassword'], $_POST['userPasswordRepeat'])) {
    $newUsername = htmlspecialchars($_POST['userName'], ENT_QUOTES);
    $newUserPW = htmlspecialchars($_POST['userPassword'], ENT_QUOTES);
    $newUserPWrepeat = htmlspecialchars($_POST['userPasswordRepeat']);
    if ($newUserPW === $newUserPWrepeat) {

        $hashedPW = password_hash($newUserPW, PASSWORD_DEFAULT);

        $db = new PDO('sqlite:groceries.sqlite');

        $prepare = $db->prepare('INSERT INTO credentials (username, userPassword)
        VALUES (:userName, :userPW)');

        $prepare->bindParam(':userName', $newUsername);
        $prepare->bindParam(':userPW', $hashedPW);
        $prepare->execute();

        $message = "New account created! Return to start page and log in.";
    } elseif ($newUserPW !== $newUserPWrepeat) {
        $message = "Incorrect password. Try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping List - Register new user</title>
    <link rel="stylesheet" href="/style.css">
</head>

<body>
    <header>
        <nav class="navNewuser"><a href="/index.php" class="navLink">My Shopping List</a></nav>
    </header>
    <main class="mainNewUser">
        <div class="mainDivNewUser">
            <form method="post" class="registerForm">
                <h3 class="gridHeader">Create new user:</h3>
                <label for="userName">Choose username:</label>
                <input type="text" name="userName" id="userName" autocomplete="name" required>
                <label for="userPassword">Choose password:</label>
                <input type="password" name="userPassword" id="userPassword" required>
                <label for="userPasswordRepeat">Repeat password:</label>
                <input type="password" name="userPasswordRepeat" id="userPasswordRepeat" required>
                <button type="submit" class="btn registerButton" name="register">Register</button>
            </form>
            <h3><?= $message; ?></h3>
            <a href="/index.php" class="aReturn">Return to login page</a>
        </div>
    </main>
    <footer>
        <p>Henrik Andersen - 2023 - It's a bit of a picnic, in a tree. In a thunderstorm.</p>
    </footer>
</body>

</html>