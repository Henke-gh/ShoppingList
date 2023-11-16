<?php
$loginDB = new PDO('sqlite:groceries.sqlite');
$stmntLogin = $loginDB->query('SELECT * from credentials');
$loginCredentials = $stmntLogin->fetchAll(PDO::FETCH_ASSOC);
$message = "";

//Test Testsson // test123

if (isset($_POST['userName'], $_POST['userPassword'])) {
    $userName = htmlspecialchars($_POST['userName'], ENT_QUOTES);
    $userPW = htmlspecialchars($_POST['userPassword'], ENT_QUOTES);

    foreach ($loginCredentials as $user) {
        $hashedPW = $user['userPassword'];
        if ($userName === $user['username'] && password_verify($userPW, $hashedPW)) {
            session_start();
            $_SESSION['id'] = $user['id'];
            header("Location: /shoppinglist.php");
        } elseif ($userName === $user['username'] && !password_verify($userPW, $hashedPW)) {
            $message =  "Sorry, wrong password.";
        } else {
            $message = "Sorry, something seems to have gone wrong. Have you forgotten your login credentials?";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping list - Wish you had a pen and paper instead of this, eh?</title>
    <link rel="stylesheet" href="/style.css">
</head>

<body>
    <header>
        <nav class="loginNav">
            <form method="post" class="loginForm">
                <label for="userName">Username:</label>
                <input type="text" name="userName">
                <label for="userPassword">Password:</label>
                <input type="password" name="userPassword">
                <button type="submit" class="btn">Log in</button>
            </form>
        </nav>
        <h1>Welcome to your personal <span class="greenText">shopping list!</span></h1>
    </header>
    <main>
        <div class="twoInRow">
            <h2>Here you can add, remove or review the items stored in your shopping list.<br>
                Much impress, <span class="greenText">such wow.</span></h2>
            <img class="groceriesImage" src="images/groceries_Large.png" title="amazing visuals here">
        </div>
        <form method="" action="/newuser.php">
            <button class="btn registerNew" name="registerNew">Register new user</button>
        </form>
        <p><?= $message; ?></p>
    </main>
    <footer>
        <p>Henrik Andersen - 2023 - It's a bit of a picnic, in a tree. In a thunderstorm.</p>
    </footer>
</body>

</html>