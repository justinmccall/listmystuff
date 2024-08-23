<?php
if (!isLoggedIn()) {
    header('Location: /login');
    exit;
}

if (isset($uri[3])) {

    $id = $uri[3];

    if($uri[3] == 'deleteres'){
        $delete = "DELETE FROM reservations WHERE id = '".$_GET['id']."' AND item_id = '".$_GET['itemid']."'";
        $conn->query($delete);
        header('Location: /edit/item/'.$_GET['itemid']);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['edit_item'])) {
            // Update item details
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $new_price = $_POST['new_price'];
            $status = $_POST['status'];

            $stmt = $conn->prepare("UPDATE items SET title = ?, description = ?, price = ?, new_price = ?, status = ? WHERE id = ?");
            $stmt->bind_param("ssddsi", $title, $description, $price, $new_price, $status, $id);
            $stmt->execute();

            if($_FILES['images']['tmp_name'][0] != ''){
                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    $file_name = $_FILES['images']['name'][$key];
                    $file_tmp = $_FILES['images']['tmp_name'][$key];
                    $file_path = "uploads/" . $file_name;
                    move_uploaded_file($file_tmp, $file_path);

                    $stmt = $conn->prepare("INSERT INTO images (item_id, image_path) VALUES (?, ?)");
                    $stmt->bind_param("is", $id, $file_path);
                    $stmt->execute();
                }
            }

        } elseif (isset($_POST['add_reservation'])) {

            // Add a reservation
            $name = $_POST['name'];
            $email = $_POST['email'];

            $stmt = $conn->prepare("INSERT INTO reservations (item_id, name, email) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $id, $name, $email);
            $stmt->execute();

            $stmt = $conn->prepare("UPDATE items SET status = 'Reserved' WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();            

        } elseif (isset($_POST['mark_taken'])) {
            // Mark a reservation as taken
            $reservation_id = $_POST['reservation_id'];

            $stmt = $conn->prepare("UPDATE reservations SET status = 'Taken' WHERE id = ?");
            $stmt->bind_param("i", $reservation_id);
            $stmt->execute();

            $stmt = $conn->prepare("UPDATE items SET status = 'Taken' WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();            

        }
        
        if( sendNotification($id,"status",$status) ){
            header("Location: /edit/item/".$id."?success");
        }else{
            header("Location: /edit/item/".$id."?mailerror");
        }

    } else {
        $result = $conn->query("SELECT * FROM items WHERE id = $id");
        $item = $result->fetch_assoc();
    }

    // Fetch reservations for this item
    $reservations = $conn->query("SELECT * FROM reservations WHERE item_id = $id ORDER BY reserved_at ASC");
}

include 'header.php';

?>

<h1 class="ui dividing header">Edit Item</h1>

<?php

if(isset($_GET['success'])){
    echo '<div class="ui success message">Item has been updated successfully!</div>';
}
if(isset($_GET['mailerror'])){
    echo '<div class="ui error message">There was an error sending the notification email.</div>';
}

?>

<div class="ui stackable two column grid">
    <div class="column">
        <div class="ui blue very padded segment">
            <form class=" ui form" action="/edit/item/<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                <div class="field"><label>Title</label><input type="text" name="title" value="<?php echo $item['title']; ?>" required></div>
                <div class="field"><label>Description</label><textarea name="description" required><?php echo $item['description']; ?></textarea></div>
                <div class="field"><label>Price</label><input type="number" step="0.01" name="price" value="<?php echo $item['price']; ?>" required></div>
                <div class="field"><label>New Price</label><input type="number" step="0.01" name="new_price" value="<?php echo $item['new_price']; ?>"></div>
                <div class="field">
                    <label>Status</label> 
                    <select name="status">
                        <option value="Available" <?php if ($item['status'] == 'Available') echo 'selected'; ?>>Available</option>
                        <option value="Reserved" <?php if ($item['status'] == 'Reserved') echo 'selected'; ?>>Reserved</option>
                        <option value="Taken" <?php if ($item['status'] == 'Taken') echo 'selected'; ?>>Taken</option>
                    </select>
                </div>
                <div class="field"><label>Images</label></div>
                <div class="fields">

                    <div class="field">
                        <?php
                        $images = $conn->query("SELECT image_path FROM images WHERE item_id = ".$id);
                        while ($img = $images->fetch_assoc()) {
                            echo "<img src='/" . $img['image_path'] . "' width='80' class='ui images pic' style='border-radius: 10px; margin: 1px; cursor: pointer;'>";
                        }
                        ?>
                    </div>
                    <div class="field"><label>Upload New Images</label> <input type="file" name="images[]" multiple></div>
                </div>
                <div class="field"><button class="ui primary button" type="submit" name="edit_item">Update Item</button></div>
            </form>
        </div>        
    </div>
    <div class="column">
        <div class="ui blue very padded segment">
            <h3>Reservations</h3>
            <table class="ui table">
            <?php while ($res = $reservations->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $res['reserved_at']; ?></td>
                    <td><?php echo $res['name']; ?></td>
                    <td><?php echo $res['email']; ?></td>
                    <td><?php echo $res['status']; ?></td>
                    <td>
                        <?php if ($res['status'] == 'Reserved'): ?>
                            <form action="/edit/item/<?php echo $id; ?>" method="POST">
                                <input type="hidden" name="reservation_id" value="<?php echo $res['id']; ?>">
                                <button class="ui green button" type="submit" name="mark_taken">Mark as Taken</button>
                            </form>
                        <?php endif; ?>
                    </td>
                    <td><a href="/edit/item/deleteres?id=<?php echo $res['id']."&itemid=$id"; ?>" onclick="return confirm('Sure you want to delete this item?')"><i class="ui times red icon"></i></a></td>
                </tr>
            <?php endwhile; ?>
            </table>
            <h3>Add Reservation</h3>
            <form class=" ui form" action="/edit/item/<?php echo $id; ?>" method="POST">
                <div class="fields">
                    <div class="field"><input type="text" name="name" placeholder="Name" required></div>
                    <div class="field"><input type="text" name="email" placeholder="Email" required></div>
                    <div class="field"><button class="ui primary button" type="submit" name="add_reservation">Add Reservation</button></div>
                </div>
            </form>
        </div>        
    </div>
</div>
