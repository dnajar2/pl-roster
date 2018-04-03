<?php

	include"../includes/classes/class.users.php";

	function login($post){
		$user = $post['user_name'];
		$pass = $post['pass'];

		$users = new users();

		$resp = $users->checkUser($user, $pass);

		echo json_encode($resp);
	}

	if($_POST){
		login($_POST);
	}

