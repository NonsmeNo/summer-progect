<?php 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['enter'])) {
            header('Location: accounting_journal.php');
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Журнал учета ОС</title>
</head>
<body>
    <form class="form3" action="" method="POST">
        ФОНДЫ ПРЕДПРИЯТИЯ
        <div class="main">
            <button type="submit" name="enter">ВОЙТИ</button>
        </div>
    </form>
</body>
</html>


