<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Графік чергових</title>
</head>
<body>
    <?php
        $groupPCB1107Weeks = ["18:00", "17:00", "16:00", "15:00", "14:00"];
        $groupBO11Weeks = ["12:00", "11:00", "13:00", "18:00", "08:00"];
        $group11EyWeeks = ["09:00", "10:00", "11:00", "12:00", "13:00"];
        $chergovi = [
            "11-EY" => ["Петро", "Діана", "Валентина",],
            "ПЦБ-11-07" => ["Данііл", "Оксана", "Жанна",],
            "БО-11" => ["Назар","Андрій","Олексій","Людмила",],
        ];
        if(isset($_POST["delete"])){
            $nameKey = $_POST["nameKey"];
            $groupDelete = $_POST["groupDelete"];
            unset($chergovi[$groupDelete][$nameKey]);
        }
        else if(isset($_POST["add"])){// добавляєм імена із форми у таблицю
            $studentName = $_POST["studentName"];
            $studentGroup = $_POST["studentGroup"];
            $chergovi["$studentGroup"][] = $studentName;
        }
    ?>
    <table>
        <thead>
            <tr><th rowspan="2">Ім'я</th><th rowspan="2">Група</th><th colspan="5">Графік</th></tr>
            <tr><th>понеділок</th><th>вівторок</th><th>середа</th><th>четвер</th><th>п'ятниця</th></tr>
        </thead>
        <tbody>
            <?php
                foreach($chergovi as $group => $value){
                    foreach($value as $index => $name){ 
                        echo "<tr><td>$name</td><td>$group</td>";
                        for($i = 0; $i <= 4; $i++){ //перебір часу днів чергування кожної групи для графіку
                            if($group == "11-EY"){
                                echo "<td>$group11EyWeeks[$i]</td>";
                            }else if($group == "ПЦБ-11-07"){
                                echo "<td>$groupPCB1107Weeks[$i]</td>";  
                            }else if($group == "БО-11"){
                                echo "<td>$groupBO11Weeks[$i]</td>";  
                            }
                        }
                        echo "<form action='index.php' method='post'><input name='nameKey' value='$index' type='hidden'><input name='groupDelete' value='$group' type='hidden'><td><button type='submit' name='delete'>-</button></td></form>";
                        echo "</tr>";
                    }
                }
            ?>
        </tbody>
    </table>
    <form action="index.php" method="post">
        <input name="studentName" type="text">
        <select name="studentGroup">
            <option value="11-EY">11-EY</option>
            <option value="ПЦБ-11-07">ПЦБ-11-07</option>
            <option value="БО-11">БО-11</option>
        </select>
        <button type="submit" name="add">Додати у таблицю</button>
    </form>
</body>
</html>