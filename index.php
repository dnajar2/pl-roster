<?php
	require 'includes/library.php';
	?>
<html>
<head>
	<title>USA ELITE</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/main.css">
	<script	src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
</head>
<body>
<?php

	if(isset($_SESSION['log_in'])){
		include_once 'parts/components/nav.php';
	}

	if(!isset($_SESSION['log_in'])) {
		include_once ('parts/login.html');

	}
	elseif (isset($_GET) && isset($_GET['page']) && $_GET['page'] == 'add_player' && isset($_SESSION['log_in'])){
		include_once ('parts/add_player.php');
	}
	elseif (isset($_GET) && isset($_GET['edit_player']) && !empty($_GET['edit_player'])){
		include_once ('parts/update_player.php');
	}
	else{
		include_once ('parts/homepage.php');
	}

	if(isset($_GET) && isset($_SESSION['log_in']) && !empty($_GET['action']) && $_GET['action'] == 'logout'){
		session_unset();
	}

	if(!isset($_SESSION['log_in'])){
		//	LOGIN AJAX CALL
		?>
		<script>
			$('form').submit(function (e) {
				var data = $(this).serialize();
				var url = $(this).attr('action');

				$.ajax({
					url: url,
					data: data,
					method: 'POST',
					cache: false,
					success: function (data,) {
						console.log(data);
						var res = JSON.parse(data);
						$('#warning').addClass(res.class).text(res.message);

						if (res.class == "success") {
							setTimeout(function () {
								location.reload();
							}, 2500)

						}
					}
				})
				e.preventDefault();
			});
		</script>
		<?php
	}
?>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>


