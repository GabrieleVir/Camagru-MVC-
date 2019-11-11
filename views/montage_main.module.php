<?php

    // Current working directory ("/Camagru-MVC-/")
    $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);

    // Upload handler script location
    $upload_handler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'upload';

    // max file size for the html upload form
    $max_file_size = 400000; // size in bytes

?>

<!-- <input type="submit" name="submit_create_post" value="Post" />
<input type="submit" name="save" value="Save" /> -->
<div id="main">
    <p id="choice">
        <button id="file_button">Upload file</button>
        or
        <button id="cam_button">Take a picture</button>
    </p>
    <form class="hidden" id="form_file" enctype="multipart/form-data" action="montage_two" method="post">
        <input type="hidden" name="MAX_FILE_SIZE" value="<?=$max_file_size;?>" />
        <input type="file" name="image" value="upload_pic" /><br />
        <input type="submit" name="upload_image" value="OK" />
    </form>
    <div class="hidden" id="cam_div">
        <video autoplay></video>
        <img id="screenshot-img">
        <canvas style="display: none;"></canvas>
        <p>
            <button id="screenshot-button">Take screenshot</button>
        </p>
    </div>
</div>
<script src="scripts/gallery.js"></script>