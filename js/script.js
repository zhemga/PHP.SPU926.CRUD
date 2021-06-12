const image = document.getElementById('image-modal');
const cropper = new Cropper(image, {
    aspectRatio: 1 / 1,
    preview: ".preview",
    rotatable: true
});

$('.preview').css({
    overflow: 'hidden',
    width: '200px',
    height: '200px',
    maxWidth:  "100%",
    maxHeight: "100%",
});

$("#btnCropped").on("click", function () {
   var dataCropper = cropper.getCroppedCanvas().toDataURL();
   $("#imgSelect").attr("src", dataCropper);
   $("#image").attr("value", dataCropper);
   $("#croppedModal").modal("hide");
});

function deleteAnimal(id) {
    if (confirm("Are you sure?")) {
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                location.reload();
            }
        };
        xhr.open("POST", "index.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("delete=" + id);
    }
}

function search() {
    let search = document.querySelector("#searchInput").value;
    if (search != null && search != "") {
        window.location.href = "index.php?Search=" + search;
    } else {
        window.location.href = "index.php";
    }
}

function getPage(number) {
    window.location.href = "index.php?Page=" + number;
}

function selectImage() {
    let uploader;
    uploader = $('<input type="file" accept="image/*" class="d-none"/>')
    uploader.click();
    uploader.on("change", function () {
        const  [file] = uploader[0].files;

        if (file){
            var reader = new FileReader();
            reader.onload = function (event)
            {
                var data = event.target.result;

                console.log(data);
                cropper.replace(data);
                $("#croppedModal").modal("show");
            }

            reader.readAsDataURL(uploader[0].files[0]);
        }

    });
}

function rotate() {
    cropper.rotate(90);
}
