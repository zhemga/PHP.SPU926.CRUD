<?php
$id = $_GET["id"];

if (!empty($id) && is_numeric($id)) {
    include "connection_database.php";
    $result = $myPDO->query("SELECT `name`,`image` FROM `animals` WHERE `animals`.`id` = {$id}")->fetchAll();

    $oldName = $result[0]['name'];
    $oldImage = $result[0]['image'];
}
?>

<?php
function base64_to_jpeg($base64_string, $output_file)
{
    // open the output file for writing
    $ifp = fopen($output_file, 'wb');

    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode(',', $base64_string);

    // we could add validation here with ensuring count( $data ) > 1
    fwrite($ifp, base64_decode($data[1]));

    // clean up the file resource
    fclose($ifp);

    return $output_file;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($id) && isset($oldName) && isset($oldImage)) {
    $validation = true;

    if (empty($_POST['name'])) {
        $errorName = "Please, enter the name!";
        $validation = false;
    }
    if (empty($_POST['image'])) {
        $errorImage = "Please, enter the image!";
        $validation = false;
    }

    if ($validation) {
        $name = $_POST['name'];
        $image = $_POST['image'];

        if($oldImage != $image) {
            unlink($oldImage);

            $dir = "img/";
            if (!is_dir($dir)) {
                mkdir($dir);
            }
            $imageName = $dir . uniqid(rand(), true) . ".jpeg";
            base64_to_jpeg($image, $imageName);
        }
        else{
            $imageName = $oldImage;
        }

        $myPDO = new PDO('mysql:host=localhost;dbname=db_spu926', 'root', '');
        $sql = "UPDATE `animals` SET `name` = '{$name}', `image` = '{$imageName}' WHERE `animals`.`id` = {$id};";
        $myPDO->prepare($sql)->execute();
        header('Location: /');
        exit;
    }
}
?>

<?php include 'head.php'; ?>

<h1 class="mt-3 mb-3">Редагувати тварину</h1>

<?php include 'modal.php'; ?>

<form class="col g-3" enctype="multipart/form-data" novalidate method="post">
    <div class="col-md-4 mb-2">
        <label for="name" class="form-label">Назва</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php
        if (isset($oldName) && !empty($oldName))
            echo $oldName;
        ?>">
        <?php
        if (isset($errorName) && !empty($errorName))
            echo "<label class = 'text-danger'>{
        $errorName
        }</label>";
        ?>
    </div>

    <div class="col-md-8 mb-4">
        <label for="image" class="form-label">Фото</label>
        <br>
        <img id="imgSelect" class="rounded-circle border h-200px" style="cursor: pointer" onclick="selectImage('<?php
        if (isset($oldImage))
            echo $oldImage;
        ?>')"
             src="<?php
             if (isset($oldImage))
                 echo $oldImage;
             ?>">
        <input type="hidden" id="image" name="image" value="<?php
        if (isset($oldImage))
            echo $oldImage;
        ?>">
        <?php
        if (isset($errorImage) && !empty($errorImage))
            echo "<label class = 'text-danger'>{
        $errorImage
        }</label>";
        ?>
    </div>

    <div class="col-12">
        <button class="btn btn-primary" type="submit">Редагувати</button>
    </div>

    <?php
    if (isset($errorUploading) && !empty($errorUploading))
        echo "<label class = 'text-danger'>{
    $errorUploading
    }</label>";
    ?>
</form>
</div>

<script src="/js/jquery-3.6.0.min.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/cropper.min.js"></script>
<script src="/js/script.js"></script>

</body>
</html>