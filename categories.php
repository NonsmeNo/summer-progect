<?php 
include('functions.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['btncateg']) && $_POST['btncateg'] == 'true')
    {
        if (empty($_POST['name']) || empty($_POST['life'])) {
            $messages[] = '<div>Пожалуйста, заполните все поля!</div>';
        }
        else {
            $stmt = $db->prepare("INSERT INTO categories (name, life) VALUES (:name, :life)");
            $stmt->bindParam(':name', $_POST['name']);
            $stmt->bindParam(':life', $_POST['life']);
            $stmt->execute();
        }
    }
}

$stmt = $db->prepare("SELECT * FROM categories");
$stmt -> execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST["row"])) {
        
            $ids = $_POST["id"];
            $rows = $_POST["row"];

            if (isset($_POST['delete'])) {
                $flag = true;
                foreach ($rows as $row) {
     
                    $stmt = $db->prepare("SELECT * FROM accounting_journal where id_categories = ?");
                    $stmt -> execute([$ids[$row]]);
                    $result_journal = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
    
                    if (!empty($result_journal))
                    {
                        $messages[] = sprintf('<div>Вы не можете ИЗМЕНИТЬ данные c ID <strong>%s</strong>, 
                        <br> так как они присутствуют в журнале учета состояния ОС <br> <br></div>',
                        $ids[$row]);
                        $flag = false;
                    } else {
                        $stmt = $db->prepare("DELETE FROM categories WHERE id=?");
                        $stmt -> execute([$ids[$row]]);
                    }
                     
                }
                if ($flag) {
                    header('Location: categories.php');
                }
            }
            

            if (isset($_POST['update'])) {

                $flag = false;
                foreach ($rows as $row) {
    
                    $stmt = $db->prepare("SELECT * FROM accounting_journal where id_categories = ?");
                    $stmt -> execute([$ids[$row]]);
                    $result_journal = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
        
                    if (!empty($result_journal))
                    {
                        $messages[] = sprintf('<div>Вы не можете ИЗМЕНИТЬ данные c ID <strong>%s</strong>, 
                        <br> так как они присутствуют в журнале учета состояния ОС <br> <br></div>',
                        $ids[$row]);
                    } else {
                        $stmt = $db->prepare("UPDATE categories SET name = ?, life = ? WHERE id = ?");
                        $stmt -> execute([$_POST['name'][$row], $_POST['life'][$row], $ids[$row]]); 
                        $flag = true;  
                    }
                    if ($flag) {
                        header('Location: categories.php');
                    }
                }
            }


        
        }

        if (isset($_POST['exit']))
        {
            header('Location: index.php');
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
    <title>Список категорий основных средств</title>
</head>
<body>
   
    <header>
        <div class="block">
            <div class="title"><a href="fixed_assets.php">Cписок основных <br> средств</a></div>
            <div class="title highlight"><a href="categories.php">Cписок категорий <br> основных средств</a></div>
            <div class="title"><a href="persons.php">Cписок материально <br> ответственных лиц</a></div>
            <div class="title"><a href="accounting_journal.php">Журнал учета состояния <br> основных средств</a></div>
        </div>
    </header>

    <div class="content">
        <form class="form1" action="" method="POST">
            <div class="form_title">
                Добавить категорию
            </div>
            <div class="item">
                <label for="name">Категория</label><br>
                <input type="text" name="name" placeholder="Введите категорию">
            </div>
            <div class="item">
                <label for="name">Срок полезного использования</label><br>
                <input type="number" name="life" placeholder="Введите количество лет">
            </div>
            <button type="submit" value="true" name="btncateg">ДОБАВИТЬ</button>
        </form>

        <?php
            if (!empty($messages)) {
                print('<div class="messages">');
                foreach ($messages as $message) {
                    print($message);
                }
                print('</div>');
            }
        ?>

        <form class="form2" action="" method="POST">
            <table>
                <tr>
                    <th class="min">ID</th>
                    <th>КАТЕГОРИЯ</th>
                    <th>СРОК ПОЛЕЗНОГО ИСПОЛЬЗОВАНИЯ (КОЛ-ВО ЛЕТ)</th>
                    <th class="min">ВЫБРАТЬ</th>
                </tr>

                <?php $counter = 0; 
                foreach ($result as $res): ?>
                <tr>
                    <td><?= strip_tags($res["id"]) ?></td>
                    <input name="id[]" value="<?= strip_tags($res["id"]) ?>" type="hidden">
                    <td><input type="text" value="<?= strip_tags($res["name"]) ?>"  name="name[]"> </td>
                    <td><input type="number" value="<?= strip_tags($res["life"]) ?>"  name="life[]"> </td>
                    <td><input type="checkbox" name="row[]" value="<?= $counter ?>"></td>
                </tr>
                <?php $counter++ ?>
                <?php endforeach; ?>
                </table>
                <div class="updelbtn">
                    <button type="submit" name="update" value="upd"><img src="" alt="">СОХРАНИТЬ</button>
                    <button type="submit" name="delete" value="del">УДАЛИТЬ</button>
                </div>
            </table>
        </form>
    </div>    
    
    <form class="form4" action="" method="POST">
        <button type="submit" name="exit">ВЫЙТИ</button>
    </form>
</body>
</html>