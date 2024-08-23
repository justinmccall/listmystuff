<?php

if (!isLoggedIn()) {
    header('Location: /login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $new_price = $_POST['new_price'];
    $status = $_POST['status'];
    $user_id = $_SESSION['user_id'];

    // Insert item data into `items` table
    $stmt = $conn->prepare("INSERT INTO items (title, description, price, new_price, status, user_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddsd", $title, $description, $price, $new_price, $status, $user_id);
    $stmt->execute();
    $item_id = $stmt->insert_id;

    // Handle image uploads
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $file_name = $_FILES['images']['name'][$key];
        $file_tmp = $_FILES['images']['tmp_name'][$key];

        // Save image to server
        $file_path = "uploads/" . $file_name;
        move_uploaded_file($file_tmp, $file_path);

        // Insert image data into `images` table
        $stmt = $conn->prepare("INSERT INTO images (item_id, image_path) VALUES (?, ?)");
        $stmt->bind_param("is", $item_id, $file_path);
        $stmt->execute();
    }

    if( sendNotification($item_id,"status",$status) ){
        header("Location: /user/".$_SESSION['username']."?success");
    }else{
        header("Location: /user/".$_SESSION['username']."?mailerror");
    }    

    
}

include 'header.php'; 
?>

<h1 class="ui dividing header">Add Item</h1>
<div class="ui very padded blue segment">
    <form action="/add/item" class="ui form" method="POST" enctype="multipart/form-data">
        <div class="field"><label>Title</label> <input type="text" name="title" required></div>
        <div class="field"><label>Description</label> <textarea name="description" required></textarea></div>
        <div class="three fields">
            <div class="field"><label>Price</label> <input type="number" step="0.01" name="price" required></div>
            <div class="field"><label>New Price</label> <input type="number" step="0.01" name="new_price"></div>
            <div class="field">
                <label>Status</label> 
                <select name="status">
                    <option value="Available">Available</option>
                    <option value="Reserved">Reserved</option>
                    <option value="Taken">Taken</option>
                </select>
            </div>        
        </div>
        <div class="field"><label>Images</label> <input type="file" name="images[]" multiple required></div>
        <div class="fields">
            <div class="field"><button class="ui primary button" type="submit">Upload</button></div>
            <div class="field"><a class="ui button" href="/user/justinmccall">Cancel</a></div>
        </div>
    </form>
</div>