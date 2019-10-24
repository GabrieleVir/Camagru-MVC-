<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css?rnd=132" type="text/css">
</head> 
<body>
    <div id="container_title">
        <h1>GALLERY</h1>
        <a href="montage">Create a new post</a>
    </div>
    <div id="container_box">
    <?php

        if (isset($_SESSION["index_posts"])) {
            foreach($_SESSION["index_posts"] as $post) {
                ?>
                <div class="post_index">            
                    <img src="assets/post_imgs/<?= $post["image"]; ?>" alt="">
                    <div class="display_nb">
                        <p><?=$nb_comments?>X Comment(s)</p>
                        <p><?=$nb_likes?>X Like(s)</p>
                    </div>
                </div>
                <?php
            }
        }
        else {
            echo "There are no posts to show"; // faire une balise html !
        }

    ?>
    </div>
    <?php
        
    ?>
    <div class="pagination">
        <a href="#">&laquo;</a>
        <a href="#">1</a>
        <a href="#" class="active">2</a>
        <a href="#">3</a>
        <a href="#">4</a>
        <a href="#">5</a>
        <a href="#">6</a>
        <a href="#">&raquo;</a>
    </div>
        <!-- <div class="post_index">
			<img src="assets/post_imgs/aze.png" alt="">
            <div class="display_nb">
                <p><?=$nb_comments?>X Comment(s)</p>
                <p><?=$nb_likes?>X Like(s)</p>
            </div>
        </div> -->
        <!-- <div class="post_index">
			<img src="assets/post_imgs/aze.png" alt="">
            <div class="display_nb">
                <p><?=$nb_comments?>X Comment(s)</p>
                <p><?=$nb_likes?>X Like(s)</p>
            </div>
        </div>
        <div class="post_index">
            <img src="assets/post_imgs/aze.png" alt="">
            <div class="display_nb">
                <p><?=$nb_comments?>X Comment(s)</p>
                <p><?=$nb_likes?>X Like(s)</p>
            </div>
        </div>
        <div class="post_index">
            <img src="assets/post_imgs/aze.png" alt="">
            <div class="display_nb">
                <p><?=$nb_comments?>X Comment(s)</p>
                <p><?=$nb_likes?>X Like(s)</p>
            </div>
        </div>
        <div class="post_index">
			<img src="assets/post_imgs/aze.png" alt="">
            <div class="display_nb">
                <p><?=$nb_comments?>X Comment(s)</p>
                <p><?=$nb_likes?>X Like(s)</p>
            </div>
        </div>
        <div class="post_index">
			<img src="assets/post_imgs/aze.png" alt="">
            <div class="display_nb">
                <p><?=$nb_comments?>X Comment(s)</p>
                <p><?=$nb_likes?>X Like(s)</p>
            </div>
        </div> -->
</body>
</html>