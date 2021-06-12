<?php
if (isset($_GET["name"]))
    $name = $_GET["name"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['delete']) && is_numeric($_POST['delete'])) {
        "<script type='text/javascript'>alert('das');</script>";
        deleteAnimal($_POST['delete']);
    } else {
        echo "error";
    }
} else {
    include "connection_database.php";
    $result = $myPDO->query("SELECT `id`,`name`,`image` FROM `animals`")->fetchAll();
}

function deleteAnimal($id)
{
    include "connection_database.php";
    $result = $myPDO->query("SELECT `image` FROM `animals` WHERE `animals`.`id` = {$id}")->fetchAll();
    $imageToDelete = $result[0]['image'];
    unlink($imageToDelete);

    $myPDO->query("DELETE FROM `animals` WHERE `animals`.`id` = {$id}");
}

?>

<h2 class="mt-3 mb-3">Список тварин</h2>

<a href="/add.php" class="btn btn-danger mb-3 float-end">Додати</a>

<form class="mb-5" method="get">
    <div>
        <input type="text" name="name" class="form-control" placeholder="Пошук по назві" value="<?php if (isset($name)) echo $name; ?>">
    </div>
    <button type="submit" class="btn btn-primary float-end mt-2">Пошук</button>
</form>

<?php
$where = "";

if(isset($name))
    $where = " WHERE `name` LIKE '%{$name}%' ";

$show_item = 3;
$sql = "SELECT COUNT(*) as count FROM `animals`" . $where;

$command = $myPDO->prepare($sql);
$command->execute();
$row = $command->fetch(PDO::FETCH_ASSOC);
$count_items = $row["count"];
$count_pages = ceil($count_items / $show_item);

if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $count_pages) {
    $page = $_GET["page"];
}
else{
    $page = 1;
}


$result = $myPDO->query("SELECT `id`,`name`,`image` FROM `animals`" . $where . "LIMIT " . ($page - 1) * $show_item . "," . $show_item)
?>

<?php

echo "<h5 class='ms-2'>Сторінка: {$page}</h5>";

echo "
        <table class='table'>
    <thead>
    <tr>
        <th scope='col'>#</th>
        <th scope='col'>Назва</th>
        <th scope='col'>Картинка</th>
        <th scope='col'>Редагування</th>
        <th scope='col'>Видалення</th>
    </tr>
    </thead>
    <tbody>
        ";

foreach ($result as $row) {
    echo "
    <tr>
        <th scope='row'>{$row['id']}</th>
        <td>{$row['name']}</td>
        <td><img src='{$row['image']}' class='rounded-circle' width='100'></td>
        <td><a class='btn btn-warning' href='edit.php?id={$row['id']}'>Редагувати</a></td>
        <td><button class='btn btn-danger' onclick='deleteAnimal({$row['id']})'>Видалити</button></td>
    </tr>
    ";
}

echo "
        </tbody>
</table>
";
?>

<nav aria-label="Page navigation example">
    <ul class="pagination">
        <li class="page-item">
            <a class="page-link btn <?php if (1 == $page) echo 'disabled'; ?>" href="?page=<?php echo $page - 1; if (isset($name)) echo "&name={$name}"; ?>"
               aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?php
        $max_page = 10;
        if ($count_pages > 0) {
            if ($count_pages <= $max_page) {
                for ($i = 1; $i <= $count_pages; $i++) {
                    echo "<li class='page-item "; if ($i == $page) echo "active"; echo "'><a class='page-link' href='?page={$i}"; if (isset($name)) echo "&name={$name}"; echo "'>$i</a></li>";
                }
            } elseif ($page < $max_page) {
                for ($i = 1; $i <= $max_page; $i++) {
                    echo "<li class='page-item "; if ($i == $page) echo "active"; echo "'><a class='page-link' href='?page={$i}"; if (isset($name)) echo "&name={$name}"; echo "'>$i</a></li>";
                }
                echo "<li class='page-item disabled'><a class='page-link' href='#'>...</a></li>";
                echo "<li class='page-item "; if ($count_pages == $page) echo "active"; echo "'><a class='page-link' href='?page={$count_pages}"; if (isset($name)) echo "&name={$name}"; echo "'>$count_pages</a></li>";
            } elseif ($page >= 10 && $page < $count_pages - $max_page) {
                echo "<li class='page-item'><a class='page-link' href='?page=1'>1</a></li>";
                echo "<li class='page-item disabled'><a class='page-link' href='#'>...</a></li>";
                $from = floor($page / $max_page) * $max_page;
                for ($i = $from - 1; $i <= $from + $max_page; $i++) {
                    echo "<li class='page-item "; if ($i == $page) echo "active"; echo "'><a class='page-link' href='?page={$i}"; if (isset($name)) echo "&name={$name}"; echo "'>$i</a></li>";
                }
                echo "<li class='page-item disabled'><a class='page-link' href='#'>...</a></li>";
                echo "<li class='page-item "; if ($count_pages == $page) echo "active"; echo "'><a class='page-link' href='?page={$count_pages}"; if (isset($name)) echo "&name={$name}"; echo "'>$count_pages</a></li>";
            } else {
                echo "<li class='page-item'><a class='page-link' href='?page=1'>1</a></li>";
                echo "<li class='page-item disabled'><a class='page-link' href='#'>...</a></li>";
                for ($i = $count_pages - $max_page - 1; $i <= $count_pages; $i++) {
                    echo "<li class='page-item "; if ($i == $page) echo "active"; echo "'><a class='page-link' href='?page={$i}"; if (isset($name)) echo "&name={$name}"; echo "'>$i</a></li>";
                }
            }
        }
        ?>
        <li class="page-item">
            <a class="page-link btn <?php if ($count_pages == $page) echo 'disabled'; ?>"
               href="?page=<?php echo $page + 1; if (isset($name)) echo "&name={$name}";?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

