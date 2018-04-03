<?php
	include"../includes/classes/class.users.php";

	function addNewPlayer($post){
		$player = new users();

		$resp = $player->createNewPl($post);

		return $resp;

	}

	function addPlayerImage($post,$file){
		$image = new users();
		$resp = $image->updatePlayerImage($post, $file);
		return $resp;
	}

	if(isset($_POST) && !empty($_POST) && !empty($_SESSION['log_in']) && empty($_REQUEST['step']) ){
		$pl = addNewPlayer($_POST);
		echo json_encode($pl);
	}elseif (!empty($_REQUEST['step']) && !empty($_SESSION['log_in']) && !empty($_FILES) ){
	//		update player's image
		$newImage = addPlayerImage($_POST, $_FILES);
		echo json_encode($newImage);


	}
	elseif (!empty($_REQUEST['step']) && !empty($_SESSION['log_in']) && !empty($_POST['field']) ){
		$generalInfo = [0 => 'phone', 1 => 'email', 2=> 'address', 3 => 'height', 4 => 'weight', 5 => 'bats', 6=> 'throws'];
		$key = array_search($_POST['field'], $generalInfo);
		if($key ){
			echo "Key =>" . $key;
		}else{
			print_r($_POST);
		}

	}else{
		echo "else";
	}