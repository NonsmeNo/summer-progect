<?php 
include('functions.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['btnjournal']) && $_POST['btnjournal'] == 'true')
    {
        if (empty($_POST['assets']) || empty($_POST['categor']) || empty($_POST['pers'])) {
            $messages[] = '<div>Пожалуйста, выберите все категории!</div>';
        }
        else {
            
            $stmt = $db->prepare("SELECT * FROM fixed_assets where name=?");
            $stmt -> execute([$_POST['assets']]);
            $result4 = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $db->prepare("SELECT * FROM categories where name=?");
            $stmt -> execute([$_POST['categor']]);
            $result5 = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $db->prepare("SELECT * FROM persons where name=?");
            $stmt -> execute([$_POST['pers']]);
            $result6 = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $db->prepare("INSERT INTO accounting_journal (id_fixed_assets, id_categories, id_persons) VALUES (:id_fixed_assets, :id_categories, :id_persons)");
            $stmt->bindParam(':id_fixed_assets', $result4[0]['id'] );
            $stmt->bindParam(':id_categories', $result5[0]['id']);
            $stmt->bindParam(':id_persons', $result6[0]['id']);
            $stmt->execute();

        }
    }
}

$stmt = $db->prepare("SELECT * FROM fixed_assets");
$stmt -> execute();
$result1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM categories");
$stmt -> execute();
$result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM persons");
$stmt -> execute();
$result3 = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM accounting_journal");
$stmt -> execute();
$result7 = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST["row"])) {
    

        $ids = $_POST["id"];
        $rows = $_POST["row"];
        

        
        
        if (isset($_POST['delete'])) {
            

            foreach ($rows as $row) {
                
            $stmt = $db->prepare("DELETE FROM accounting_journal WHERE id=?");
            $stmt -> execute([$ids[$row]]);    
            }

            header('Location: accounting_journal.php');

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
    <title>Журнал учета состояния основных средств</title>
</head>
<body>
    <header>
        <div class="block">
            <div class="title"><a href="fixed_assets.php">Cписок основных <br> средств</a></div>
            <div class="title"><a href="categories.php">Cписок категорий <br> основных средств</a></div>
            <div class="title"><a href="persons.php">Cписок материально <br> ответственных лиц</a></div>
            <div class="title highlight"><a href="accounting_journal.php">Журнал учета состояния <br> основных средств</a></div>
        </div>
    </header>

    <div class="content">
        <form class="form1" action="" method="POST">
            <div class="form_title">
                Журнал основных средств
            </div>
            <div class="item2">
                Основное средство: <br>
                <select name="assets">
                    <option value="">--Выберите основное средство--</option>
                    <?php foreach ($result1 as $res1): ?>
                        <option value="<?= strip_tags($res1["name"]) ?>"><?php print strip_tags($res1["name"]);?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="item2"> 
                Категория основного средства: <br>
                <select name="categor">
                    <option value="">--Выберите категорию--</option>
                    <?php foreach ($result2 as $res2): ?>
                        <option value="<?= strip_tags($res2["name"]) ?>"><?php print strip_tags($res2["name"]);?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="item2">
                Ответственное лицо: <br>
                <select name="pers">
                    <option value="">--Выберите ответственное лицо--</option>
                    <?php foreach ($result3 as $res3): ?>
                        <option value="<?= strip_tags($res3["name"]) ?>"><?php print strip_tags($res3["name"]);?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button name="btnjournal" value="true" type="submit">ДОБАВИТЬ</button>
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
                    <th>ОСНОВНОЕ СРЕДСТВО</th>
                    <th>КАТЕГОРИЯ</th>
                    <th>ОТВЕТСТВЕННОЕ ЛИЦО</th>
                    <th>ДАТА ПРИНЯТИЯ К <br> БУХГАЛТЕРСКОМУ УЧЕТУ</th>
                    <th>СРОК ПОЛЕЗНОГО ИСПОЛЬЗОВАНИЯ (КОЛ-ВО ЛЕТ)</th>
                    <th class="min">ВЫБРАТЬ</th>
                </tr>

                <?php $counter = 0; 
                foreach ($result7 as $res): ?>
                <tr>
                    <td><?= strip_tags($res["id"]) ?></td>
                    <input name="id[]" value="<?= strip_tags($res["id"]) ?>" type="hidden">
                    
                    <?php
                    $stmt = $db->prepare("SELECT * FROM accounting_journal where id=?");
                    $stmt -> execute([$res["id"]]);
                    $id_str = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    $stmt = $db->prepare("SELECT * FROM fixed_assets where id=?");
                    $stmt -> execute([$id_str[0]["id_fixed_assets"]]);
                    $res1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $stmt = $db->prepare("SELECT * FROM categories where id=?");
                    $stmt -> execute([$id_str[0]["id_categories"]]);
                    $res2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    $stmt = $db->prepare("SELECT * FROM persons where id=?");
                    $stmt -> execute([$id_str[0]["id_persons"]]);
                    $res3 = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                    ?>


                    <td><input type="text" value="<?= strip_tags($res1[0]["name"]) ?>"  name="name_assets[]"> </td>
                    <td><input type="text" value="<?= strip_tags($res2[0]["name"]) ?>" name="name_categories[]">  </td>
                    <td><input type="text" value="<?= strip_tags($res3[0]["name"]) ?>" name="name_persons[]">  </td>
                    <td><input type="date" value="<?= strip_tags($res1[0]["date"]) ?>" name="date[]">  </td>
                    <td><input type="number" value="<?= strip_tags($res2[0]["life"]) ?>" name="life[]">  </td>
                    <td><input type="checkbox" name="row[]" value="<?= $counter ?>"></td>
                </tr>
                <?php $counter++ ?>
                <?php endforeach; ?>
                </table>
                <div class="updelbtn">
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