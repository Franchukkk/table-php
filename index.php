<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Графік чергових</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        // Початок сесії для збереження даних між запитами
        session_start();
        // Масиви, які містять розклад для кожної групи
        $groupPCB1107Weeks = ["18:00", "17:00", "16:00", "15:00", "14:00"];
        $groupBO11Weeks = ["12:00", "11:00", "13:00", "18:00", "08:00"];
        $group11EyWeeks = ["09:00", "10:00", "11:00", "12:00", "13:00"];
        // Якщо є дані сесії, завантажте їх, інакше встановіть значення за замовчуванням для розкладу та кураторів
        if(isset($_SESSION)){
            $chergovi = $_SESSION["chergovi"];
            $curators = $_SESSION["curators"];
        }else{
            $curators = [
                "11-EY" => "Ігор",
                "ПЦБ-11-07" => "Марія",
                "БО-11" => "Віктор",
            ];
            $chergovi = [
                "11-EY" => ["Петро", "Діана", "Валентина",],
                "ПЦБ-11-07" => ["Данііл", "Оксана", "Жанна",],
                "БО-11" => ["Назар","Андрій","Олексій","Людмила",],
            ];
        }
        // Якщо була натиснута кнопка видалення, видалити ім'я студента з розкладу
        if(isset($_POST["delete"])){
            $nameKey = $_POST["nameKey"];
            $groupDelete = $_POST["groupDelete"];
            unset($chergovi[$groupDelete][$nameKey]);
            $_SESSION["chergovi"] = $chergovi;
        }
        // Якщо була натиснута кнопка «Додати», додати ім’я студента до розкладу
        else if(isset($_POST["add"])){
            $studentName = $_POST["studentName"];
            $studentGroup = $_POST["studentGroup"];
            $chergovi["$studentGroup"][] = $studentName;
            $_SESSION["chergovi"] = $chergovi;
        }
        // Якщо була натиснута кнопка редагування, оновити ім'я куратора
        if(isset($_POST['editSubm'])) { 
            $group = $_POST['curatorGroup'];
            $newCuratorName = $_POST['newCurname'];
            $curators[$group] = $newCuratorName;
            $_SESSION['curators'] = $curators;
        }
    ?>
    <table>
        <thead>
            <tr><th rowspan="2">Ім'я</th><th rowspan="2">Група</th><th class="th1" colspan="5">Графік</th></tr>
            <tr><th>понеділок</th><th>вівторок</th><th>середа</th><th>четвер</th><th>п'ятниця</th></tr>
        </thead>
        <tbody>
            <?php
                // код, який перебирає масив кураторів і виводить рядок HTML-таблиці для кожного з них.
                foreach($curators as $group => $curator){
                    echo "<tr class='curator'><td>$curator (куратор)</td><td><form action='index.php' method='POST'><button class='showBTN' id='$group' name='$group' type='submit'>$group</button></form></td>";
                    // виведення годин чергування відносно групи
                    for($i = 0; $i <= 4; $i++){
                        if($group == "11-EY"){
                            echo "<td>$group11EyWeeks[$i]</td>";
                        }else if($group == "ПЦБ-11-07"){
                            echo "<td>$groupPCB1107Weeks[$i]</td>";  
                        }else if($group == "БО-11"){
                            echo "<td>$groupBO11Weeks[$i]</td>";  
                        }
                    }
                    // форма для зміни імені куратора
                    echo "<td id='edit' class='editTR'><form action='index.php' method='POST'><input type='hidden' name='curatorGroup' value='$group'><input class='edit' name='newCurname' type='text'><button type='submit' class='send' name='editSubm'>edit</button></form></td>";
                    echo "</tr>";
                }
                // застосування стилів CSS до рядків таблиці залежно від того, кнопку якої групи було натиснуто
                // також додавання вмісту після кнопки, щоб показати, що її натиснули 
                if(isset($_POST["11-EY"])){
                    echo "<style>.tr11-EY{background-color:#557ebb9e;transition:0.3s;}</style>";
                    echo "<style>.tr11-EY:hover{background-color:#556bbb78}</style>";
                    echo "<style>.curator:first-child .showBTN:after{content: 'shown';}</style>";
                } elseif(isset($_POST["ПЦБ-11-07"])){
                    echo "<style>.trПЦБ-11-07{background-color:#b555bb9e;transition:0.3s;}</style>";
                    echo "<style>.trПЦБ-11-07:hover{background-color:#b555bb80;}</style>";
                    echo "<style>.curator:nth-child(2n) .showBTN:after{content: 'shown';}</style>";
                } elseif(isset($_POST["БО-11"])){
                    echo "<style>.trБО-11{background-color:#55bb7e9e;transition:0.3s;}</style>";
                    echo "<style>.trБО-11:hover{background-color:#55bb7e61;}</style>";
                    echo "<style>.curator:nth-child(3n) .showBTN:after{content: 'shown';}</style>";
                }       
                // перебір студентів масиву $chergovi
                foreach($chergovi as $group => $value){
                    foreach($value as $index => $name){ 
                        //Для кожної групи виводяться дані у вигляді таблиці, де для кожного студента зазначається назва групи, а також дні чергування кожної групи.
                        echo "<tr class='tr$group' class='chergovii'><td>$name</td><td>$group</td>";
                        for($i = 0; $i <= 4; $i++){ //перебір часу днів чергування кожної групи для графіку
                            if($group == "11-EY"){
                                echo "<td>$group11EyWeeks[$i]</td>";
                            }else if($group == "ПЦБ-11-07"){
                                echo "<td>$groupPCB1107Weeks[$i]</td>";  
                            }else if($group == "БО-11"){
                                echo "<td>$groupBO11Weeks[$i]</td>";  
                            }
                        }
                        // для кожного запису таблиці є кнопка видалення -, яка викликає форму, щоб видалити запис.
                        echo "<form action='index.php' method='post'><input name='nameKey' value='$index' type='hidden'><input name='groupDelete' value='$group' type='hidden'><td><button class='delete' type='submit' name='delete'>-</button></td></form>";
                        echo "</tr>";
                    }
                }
            ?>
        </tbody>
    </table>
    <!-- форма для додавання студентів у масив чергових -->
    <form action="index.php" method="post">
        <input name="studentName" type="text">
        <select name="studentGroup">
            <option value="11-EY">11-EY</option>
            <option value="ПЦБ-11-07">ПЦБ-11-07</option>
            <option value="БО-11">БО-11</option>
        </select>
        <button class="add" type="submit" name="add">+</button>
    </form>
</body>
</html>