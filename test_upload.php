<!DOCTYPE html>
<head>
    
    <title>Document</title>
</head>
<body>
    
    <form action="#" class="" method="POST" enctype="multipart/form-data">
    
        <input required type="file" name="avatar" class="form-control">
        <input type="submit" name='submit' value='upload'>
    </form>

</body>
</html>

<?php

    if(isset($_POST['submit'])){
        $file = $_FILES['avatar'];
        $fileName = $file['name'];
        $fileTmp = $file['tmp_name'];

        try{
            move_uploaded_file($fileTmp, 'uploads/avatars/lol.jpg');
            echo 'fileUploads';
        }catch (Exception $e) {
            die ('File did not upload: ' . $e->getMessage());
        }
    }


?>
