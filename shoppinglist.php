<?php
session_start();
$database = new PDO('sqlite:groceries.sqlite');
$statement = $database->query('SELECT * from shoppingList WHERE userID IS ' . $_SESSION['id']);
$shoppingList = $statement->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['addItem']) && !empty($_POST['addItem'])) {
    $newEntry = htmlspecialchars($_POST['addItem'], ENT_QUOTES);

    //Prepares the statment prior to insertion into the database, prevents SQL-injections.
    $prepareEntry = $database->prepare("INSERT INTO shoppingList (item, userID) VALUES (:item, :userID)");
    $prepareEntry->bindParam(':item', $newEntry);
    $prepareEntry->bindParam(':userID', $_SESSION['id']);
    $prepareEntry->execute();

    //Quick and dirty solution to update the list with the newly added item. Maybe use AJAX instead?
    header("Location: /shoppinglist.php");
}

if (isset($_POST['removeItem'])) {
    $deleteEntry = htmlspecialchars($_POST['removeItem'], ENT_QUOTES);

    $prepareEntry = $database->prepare("DELETE FROM shoppingList WHERE id = :id");
    $prepareEntry->bindParam(':id', $deleteEntry);
    $prepareEntry->execute();
    header("Location: /shoppinglist.php");
}

if (isset($_POST['logout'])) {
    unset($_SESSION['id']);
    session_destroy();
    header("Location: /index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping List</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Here's your personal <span class="greenText">shopping list!</span></h1>
    </header>
    <main>
        <div class="listContainer">
            <h3>Current items on shopping list</h3>
            <ul>
                <?php
                foreach ($shoppingList as $grocery) : ?>
                    <form action="/shoppinglist.php" method="post">
                        <li><?= $grocery['item']; ?>
                            <input type="hidden" name="removeItem" value="<?= $grocery['id'] ?>">
                            <button type="submit" class="btn removeBtn">Remove</button>
                        </li>
                    </form>

                <?php endforeach; ?>
            </ul>
            <form action="/shoppinglist.php" method="post" class="formAdd">
                <label for="addItem">Add item to list:</label><br>
                <input type="text" id="addItem" name="addItem" placeholder=" Add item here" class="addItemField">
                <button type="submit" class="btn addBtn">Add</button>
            </form>
        </div>
        <form method="post">
            <button type="submit" class="btn removeBtn btnLogout" name="logout">Log Out</button>
        </form>
    </main>
    <footer>
        <p>Henrik Andersen - 2023 - It's a bit of a picnic, in a tree. In a thunderstorm.</p>
    </footer>
</body>

</html>