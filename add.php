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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        $dir = "img/";
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        $imageName = $dir . uniqid(rand(), true) . ".jpeg";
        base64_to_jpeg($image, $imageName);

        include "connection_database.php";
        $sql = "INSERT INTO `animals` (`name`, `image`) VALUES (?, ?);";
        $myPDO->prepare($sql)->execute([$name, $imageName]);
        header('Location: /');
        exit;
    }
}
?>

<?php include 'head.php'; ?>

<h1 class="mt-3 mb-3">Додати тварину</h1>

<?php include 'modal.php'; ?>

<form class="col g-3" enctype="multipart/form-data" novalidate method="post">
    <div class="col-md-4 mb-2">
        <label for="name" class="form-label">Назва</label>
        <input type="text" class="form-control" id="name" name="name">
        <?php
        if (isset($errorName) && !empty($errorName))
            echo "<label class = 'text-danger'>{$errorName}</label>";
        ?>
    </div>
    <div class="col-md-8 mb-4">
        <label for="image" class="form-label">Фото</label>
        <br>
        <img id="imgSelect" class="rounded-circle border h-200px" style="cursor: pointer" onclick="selectImage()"
             src="img/no-image.gif">
        <input type="hidden" id="image" name="image">
        <?php
        if (isset($errorImage) && !empty($errorImage))
            echo "<label class = 'text-danger'>{$errorImage}</label>";
        ?>
    </div>

    <div class="col-12">
        <button class="btn btn-primary" type="submit">Додати</button>
    </div>

    <?php
    if (isset($errorUploading) && !empty($errorUploading))
        echo "<label class = 'text-danger'>{$errorUploading}</label>";
    ?>
</form>

</div>

<script src="/js/jquery-3.6.0.min.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/cropper.min.js"></script>
<script src="/js/script.js"></script>

</body>

</html>