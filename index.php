<?php

$uri = explode("/",parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

include 'config.php';

switch($uri[1]){

	case "":
		include 'dashboard.php';
		break;

	case "dashboard":
		include 'dashboard.php';
		break;
	
	case "login":
		include 'login.php';
		break;

	case "logout":
		include 'logout.php';
		break;
	
	case "user":
	case "users":
		include 'user_list.php';
		break;

	case "add":
		
		switch($uri[2]){
			case "item":
				include 'add_item.php';
				break;
		}

		break;

	case "edit":
		
		switch($uri[2]){
			
			case "item":
				
				include 'edit_item.php';
			 	break;

				break;
		}
		break;

	case "admin":
		
		switch($uri[2]){
			
			case "users":
				
				switch($uri[3]){
					case "":
						include 'admin_dashboard.php';
						break;
					case "add":
						include 'admin_add_user.php';
						break;
				}				
				break;

			case "edit":
				
				switch($uri[3]){

					case "user":
						include 'admin_edit_user.php';
					 	break;
				}

				break;
		}
		break;
}

include 'footer.php';

?>