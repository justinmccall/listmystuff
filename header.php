<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>MyStuff | <?php echo $pageTitle; ?></title>
	<meta property="og:url" content="https://<?php echo $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; ?>" />


	<meta property="og:title" content="MyStuff | <?php echo $pageTitle; ?>" />
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

	<link href="https://cdn.datatables.net/v/se/jq-3.7.0/dt-2.1.0/b-3.1.0/date-1.5.2/sb-1.7.1/datatables.min.css" rel="stylesheet">
	<script src="https://cdn.datatables.net/v/se/jq-3.7.0/dt-2.1.0/b-3.1.0/date-1.5.2/sb-1.7.1/datatables.min.js"></script>

	<link rel="stylesheet" type="text/css" href="/semantic/dist/semantic.min.css">
	<script src="/semantic/dist/semantic.min.js"></script>
	<link rel="icon" href="/assets/img/rocket.svg">

	<link rel="stylesheet" type="text/css" href="/stylesheet.css">

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-3PDLRT7ENB"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'G-3PDLRT7ENB');
	</script>

</head>
<body style="background-color: #efefef;">

	<div class="ui container" style="margin-top: 20px; width: 90%;">

		<header style="background-color: #efefef;">
			<div class="ui two column middle aligned grid">
				<div class="column"><img src="/assets/img/list-my-stuff-logo.svg" class="ui medium image"></div>
				<div class="right aligned column">
					<?php if(isLoggedIn()){ ?>
						<div class="ui secondary right floated menu">
							<?php

							$results = $conn->query("SELECT * FROM navigation ORDER BY orderNumber ASC");
							while($row = $results->fetch_object()){
								($_SERVER['REQUEST_URI'] == $row->link) ? $active = "active" : $active = "";
								echo '<a href="'.str_replace("{{username}}",$_SESSION['username'],$row->link).'" class="item '.$active.'">'.$row->label.'</a>';
							}

							?>
						</div>
					<?php }else{ ?>
						<h3><?php echo getUserInfo($uri[2],"first_name").' '.getUserInfo($uri[2],"last_name");?>'s Items</h3>
					<?php } ?>
				</div>
			</div>
			<div class="ui divider"></div>
		</header>
