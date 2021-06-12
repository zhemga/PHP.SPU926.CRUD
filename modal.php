<div class="modal" id="croppedModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-100 w-50">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between">
                    <div class="w-75">
                        <img id="image-modal" class="w-100">
                    </div>
                    <div id="previewContainer" class="w-25 p-3">
                        <center>
                            <h3>Preview</h3>
                            <div class="preview rounded-circle border"></div>
                            <button class="btn btn-primary mt-3" onclick="rotate()">Rotate</button>
                        </center>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="btnCropped" class="btn btn-primary">Ok</button>
            </div>
        </div>
    </div>
</div>