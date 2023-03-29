<!DOCTYPE html>
<html lang="PT">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UploadParaServer</title>
</head>

<body>

    <?php
        if (isset($_POST['folder']) && isset($_FILES['image'])) {
            $folder = $_POST['folder'];
            if ($_FILES['image']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['image']['tmp_name'])) {
                $target_dir = $folder;
                $target_file = $target_dir . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
                echo 'Image uploaded successfully.';
            } else {
                echo 'Error uploading image.';
            }
        } else {
            echo 'Invalid request.';
        }
    ?>



</body>

</html>