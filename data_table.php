<?php
if (!empty($_GET['Search'])) {
    include "connection_database.php";
    $result = $myPDO->query("SELECT * FROM `db_spu926`.`animals` WHERE (CONVERT(`id` USING utf8) LIKE '%{$_GET['Search']}}%' OR CONVERT(`name` USING utf8) LIKE '%{$_GET['Search']}%')")->fetchAll();
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

<div class="input-group mb-3">
    <input id="searchInput" type="search" class="form-control rounded-start" placeholder="Пошук" aria-label="Search"
           aria-describedby="search-addon"/>
    <button type="button" class="btn btn-warning" onclick="search()">Пошук</button>
</div>

<?php
$page = $_GET["page"];

$show_item = 3;
$sql = "SELECT COUNT(*) as count FROM `animals`";

$command = $myPDO->prepare($sql);
$command->execute();
$row = $command->fetch(PDO::FETCH_ASSOC);
$count_items = $row["count"];
$count_pages = ceil($count_items / $show_item);

if (!isset($page) || !is_numeric($page) || $page < 1 || $page > $count_pages) {
    $page = 1;
}


$result = $myPDO->query("SELECT `id`,`name`,`image` FROM `animals` LIMIT " . ($page - 1) * $show_item . "," . $show_item)
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
            <a class="page-link btn <?php if (1 == $page) echo 'disabled'; ?>" href="?page=<?php echo $page - 1; ?>"
               aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?php
        $max_page = 10;
        if ($count_pages > 0) {
            if ($count_pages < $max_page) {
                for ($i = 1; $i <= $count_pages; $i++) {
                    echo "<li class='page-item " ?><?php if ($i == $page) echo "active"; ?><?php echo "'><a class='page-link' href='?page={$i}'>$i</a></li>";
                }
            } elseif ($page < $max_page) {
                for ($i = 1; $i <= $max_page; $i++) {
                    echo "<li class='page-item " ?><?php if ($i == $page) echo "active"; ?><?php echo "'><a class='page-link' href='?page={$i}'>$i</a></li>";
                }
                echo "<li class='page-item disabled'><a class='page-link' href='#'>...</a></li>";
                echo "<li class='page-item " ?><?php if ($count_pages == $page) echo "active"; ?><?php echo "'><a class='page-link' href='?page={$count_pages}'>$count_pages</a></li>";
            } elseif ($page >= 10 && $page < $count_pages - $max_page) {
                echo "<li class='page-item'><a class='page-link' href='?page=1'>1</a></li>";
                echo "<li class='page-item disabled'><a class='page-link' href='#'>...</a></li>";
                $from = floor($page / $max_page) * $max_page;
                for ($i = $from - 1; $i <= $from + $max_page; $i++) {
                    echo "<li class='page-item " ?><?php if ($i == $page) echo "active"; ?><?php echo "'><a class='page-link' href='?page={$i}'>$i</a></li>";
                }
                echo "<li class='page-item disabled'><a class='page-link' href='#'>...</a></li>";
                echo "<li class='page-item " ?><?php if ($count_pages == $page) echo "active"; ?><?php echo "'><a class='page-link' href='?page={$count_pages}'>$count_pages</a></li>";
            } else {
                echo "<li class='page-item'><a class='page-link' href='?page=1'>1</a></li>";
                echo "<li class='page-item disabled'><a class='page-link' href='#'>...</a></li>";
                for ($i = $count_pages - $max_page - 1; $i <= $count_pages; $i++) {
                    echo "<li class='page-item " ?><?php if ($i == $page) echo "active"; ?><?php echo "'><a class='page-link' href='?page={$i}'>$i</a></li>";
                }
            }
        }
        ?>
        <li class="page-item">
            <a class="page-link btn <?php if ($count_pages == $page) echo 'disabled'; ?>"
               href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

