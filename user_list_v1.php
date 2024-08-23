<?php

if (isLoggedIn()) {
    $showLoggedInBits = true;
}

$pageTitle = getUserInfo($uri[2],"first_name").'s stuff for sale';

include 'header.php'; 

if(isAdmin()){
    echo '
    <div class="ui two column grid">
        <div class="column"><h1 class="ui header">My Items ('.getNumItems().')</h1></div>
        <div class="right aligned column">
            <a class="ui primary labeled icon green button" id="getChatText"><i class="ui send icon"></i>Chat Text</a> 
            <a href="/add/item" class="ui primary labeled icon blue button"><i class="ui plus icon"></i>Add Item</a>
        </div>
    </div>
    <div class="ui divider"></div>';
}


if(isset($_GET['success'])){
    echo '<div class="ui success message">Item has been added successfully!</div>';
}
if(isset($_GET['mailerror'])){
    echo '<div class="ui error message">There was an error sending the notification email.</div>';
}


$sql = "SELECT * FROM items WHERE status IN('Available','Reserved','Taken') ORDER BY status ASC";
$result = $conn->query($sql);
while($row = $result->fetch_object()){
    
    if($row->status == 'Available'){
        $newContents .= "{{bl}} [".$row->status."] {{b}}".$row->title."{{b}} | AED".number_format($row->price,2)."\n";
    }

    if($row->status == 'Reserved'){
        $resContents .= "{{bl}} [".$row->status."] {{b}}".$row->title."{{b}} | AED".number_format($row->price,2)."\n";
    }

    if($row->status == 'Taken'){
        $takenContents .= "{{bl}} [".$row->status."] {{b}}".$row->title."{{b}} | AED".number_format($row->price,2)."\n";
    }
}
?>
<div id="showChatText" style="display: none;">
    <div class="ui top attached tiny tabular menu socialChats">
      <a class="active item" data-tab="whatsapp"><i class="ui large green whatsapp icon"></i>WhatsApp</a>
      <a class="item" data-tab="telegram"><i class="ui large teal telegram icon"></i>Telegram</a>
      <a class="item" data-tab="facebook"><i class="ui large blue facebook icon"></i>Facebook</a>
    </div>
    <div class="ui bottom attached active tab segment" data-tab="whatsapp">
        <div class="ui two column grid">
            <div class="column"><p>Copy and paste the following text into Telegram:</p></div>
            <div class="column"><span class="ui top right attached green label" onclick="copyTxt('whatsappTxt')" style="cursor: pointer;"><i class="ui copy icon"></i>Copy Text</span></div>
        </div>
<textarea style="width: 100%; height: 400px; border: 0; background-color: #f0f0f0; padding: 30px; border-radius: 10px; margin: 20px 0;" id="whatsappTxt">
*/ / /  RELOCATION SALE - UPDATED  \ \ \*
--------------------------------------

Click the link below to view the items:
https://mystuff.resonate.co.za/users/justinmccall/

--------------------------------------

The following items available for sale:

<?php echo str_replace("{{b}}","*",str_replace("{{bl}}","- ",$newContents)); ?>

While the following items have been reserved, but not yet taken.  If you are interested, let me know and I will add you to the reservation list.

<?php echo str_replace("{{b}}","*",str_replace("{{bl}}","- ",$resContents)); ?>

These items are no longer available for sale:

<?php echo str_replace("{{b}}","*",str_replace("{{bl}}","- ",$takenContents)); ?>

Click the link below to view the items:
https://mystuff.resonate.co.za/users/<?php echo $uri[2]; ?>/
</textarea>
    </div>
    <div class="ui bottom attached tab segment" data-tab="telegram">
        <div class="ui two column grid">
            <div class="column"><p>Copy and paste the following text into Telegram:</p></div>
            <div class="column"><span class="ui top right attached green label" onclick="copyTxt('telegramTxt')" style="cursor: pointer;"><i class="ui copy icon"></i>Copy Text</span></div>
        </div>
<textarea style="width: 100%; height: 400px; border: 0; background-color: #f0f0f0; padding: 30px; border-radius: 10px; margin: 20px 0;" id="telegramTxt";>
**/ / /  RELOCATION SALE - UPDATED  \ \ \**
--------------------------------------

Click the link below to view the items:
https://mystuff.resonate.co.za/users/justinmccall/

--------------------------------------

The following items available for sale:

<?php echo str_replace("{{b}}","**",str_replace("{{bl}}","• ",$newContents)); ?>

While the following items have been reserved, but not yet taken.  If you are interested, let me know and I will add you to the reservation list.

<?php echo str_replace("{{b}}","**",str_replace("{{bl}}","• ",$resContents)); ?>

These items are no longer available for sale:

<?php echo str_replace("{{b}}","**",str_replace("{{bl}}","• ",$takenContents)); ?>

Click the link below to view the items:
https://mystuff.resonate.co.za/users/<?php echo $uri[2]; ?>/
</textarea>
    </div>
    <div class="ui bottom attached tab segment" data-tab="facebook">
        <div class="ui two column grid">
            <div class="column"><p>Copy and paste the following text into Facebook:</p></div>
        </div>
<div style="width: 100%; border: 0; background-color: #f0f0f0; padding: 30px; border-radius: 10px; margin: 20px 0;" id="facebookTxt";>
<b>/ / /  RELOCATION SALE - UPDATED  \ \ \</b><br>
--------------------------------------<br>
Click the link below to view the items:<br>
https://mystuff.resonate.co.za/users/justinmccall/<br>
--------------------------------------<br>
<br>
The following items available for sale:<br>
<br>
<?php echo str_replace("{{b}}","",str_replace("{{bl}}","",$newContents)); ?><br>
<br>
While the following items have been reserved, but not yet taken.  If you are interested, let me know and I will add you to the reservation list.<br>
<br>
<?php echo str_replace("{{b}}","",str_replace("{{bl}}","",$resContents)); ?><br>
<br>
These items are no longer available for sale:<br>
<br>
<?php echo str_replace("{{b}}","",str_replace("{{bl}}","",$takenContents)); ?><br>
<br>
Click the link below to view the items:<br>
https://mystuff.resonate.co.za/users/<?php echo $uri[2]; ?>/
</div>
    </div>
</div>

<div class="ui secondary large segment" style="margin-bottom: 30px;"><?php echo getUserInfo($uri[2],"first_name");?> has a bunch of stuff for sale that is listed below.  We don't operate an online payment facility as we believe that interaction directly with the seller is a far more beneficial way to but the previously loved items. So to get in touch, find what you want and click on the WhatsApp logo to make contact with <?php echo getUserInfo($uri[2],"first_name");?>.</div>

<?php

echo '<div class="ui link five stackable doubling cards">';

$bgColor = "#efefef";

$result = $conn->query("SELECT *,items.id AS itemID FROM items LEFT JOIN users ON users.id = items.user_id WHERE users.username = '".$uri[2]."' ORDER BY status ASC");

while ($row = $result->fetch_assoc()) {

    switch($row['status']){
        case "Available":
            $borderColor = "#efefef";
            $bgColor = "#efefef";
            $fontColor = "rgba(0,0,0,.81) !important";
            $statusLabel = '';
            break;
        case "Reserved":
            $borderColor = "#23AA47";
            $bgColor = "#efefef";
            $fontColor = "rgba(0,0,0,.81) !important";
            $statusLabel = '<a class="ui green ribbon label">RESERVED</a>';
            break;
        case "Taken":
            $borderColor = "#D20808";
            $opacity = "0.3";
            $fontColor = "#ffffff !important";
            $statusLabel = '<a class="ui red ribbon label">TAKEN</a>';
            $disableClass = 'disabled';
            break;
    }

    echo '
      <div class="ui '.$disableClass.' card">

        <div class="image">';

            $images = $conn->query("SELECT image_path FROM images WHERE item_id = ".$row['itemID']." LIMIT 1");
            while ($img = $images->fetch_assoc()) {
                echo "<img src='/" . $img['image_path'] . "' class='pic'>";
            }

        echo '
        </div>
        <div class="content">
        '.$statusLabel.'
          <div class="header" style="margin-top: 10px;">'.$row['title'].'</div>
          <div class="description">'.nl2br($row['description']).'</div>
        </div>';

        if ($row['status'] != 'Taken') {
            
            echo '
            <div class="extra content">
                <a href="https://wa.me/'.getUserInfo($uri[2],"whatsapp").'?text=Hi, I am interested in the '.$row['title'].', is it still available?" target="_blank" class="right floated"><i class="ui whatsapp big green icon"></i></a>
              <span style="font-size: 20px; font-weight: bold; color: #222;">';
                    if ($row['price'] != 0) {
                        echo "AED " . $row['price'];
                    }else{
                        echo "FREE";
                    }              

                    if ($row['price'] != 0 && $row['new_price'] != 0) {
                        echo "<div style='font-size: 14px; color: #888; font-weight: 100;'>New AED " . $row['new_price'] . "</div>";
                    }              
              echo '</span>
            </div>';

        }else{
            // echo '<div class="extra content"><div class="ui red fluid button">Taken</div></div>';
        }

        if (isLoggedIn()) {
            echo '<span class="extra content"><a class="ui primary tiny fluid button" href="/edit/item/'.$row['itemID'].'">Edit</a></span>';
        }

      echo '</div>';

}

echo '</div>';
?>