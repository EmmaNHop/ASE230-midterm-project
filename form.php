<?php 
include_once('header.php');
    if (count($_POST) > 0) {
        //read the file
        $content = file_get_contents(APP_PATH.'/data/posts/'.$_POST['id'].'.json');
        // convert the string to the php array
        $content = json_decode($content, true);
        // add to the array
        $content[] = $_POST;
        // encode the array into a JSON string
        $content = json_encode($content, JSON_PRETTY_PRINT);
        // save the file
        file_put_contents(APP_PATH.'/data/posts/'.$_POST['id'].'.json', $content);
        echo 'Post added successfully';
    }else{
?>
<!-- Page content-->
<div class="container">
    <div class="row">
        <!-- Blog entry-->
        <div class="col-lg-8">
            <div class="card mb-4">
                <form action = "<?= $_SERVER['PHP_SELF'] ?>" method = "post">
                    <div>
                        <label>Username</label><br/>
                        <input type = "text" name = "user_name"/>
                    </div>
                    <div>
                        <label>Date</label><br/>
                        <input type = "date" name = "date"/>
                    </div>
                    <div>
                        <label>Title</label><br/>
                        <input type = "text" name = "title"/>
                    </div>
                    <div>
                        <label>Content</label><br/>
                        <textarea name = "content"></textarea>
                    </div>
                    <button type = "submit">Post</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } 
include_once('footer.php');
?>
