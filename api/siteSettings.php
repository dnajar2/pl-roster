<?php
	include"../includes/classes/class.siteSettings.php";

	$siteSettings = New siteSettings();

	if( isset($_POST['toggle']) ){

		$mode = $_POST['mode'];

		$settings = $siteSettings->recruitingModeSwitch($mode);
		$siteSettings->res->mode = $mode;
		$siteSettings->res->message = $settings;
		echo json_encode($siteSettings->res);
	}elseif ($_POST['recMessage']){
		$message = trim($_POST['recMessage']);

		$siteSettings->res->req = $siteSettings->updateRecruitingMessage($message);
		echo json_encode($siteSettings->res);
	}
	else{
		die("404 nothing found");
	}

