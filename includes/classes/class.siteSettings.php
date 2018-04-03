<?php
	require_once 'class.users.php';
	class siteSettings extends users{

		function recruitingMode(){
			$sql = $this->dbb->select('SELECT * FROM `site_settings`');
			$_SESSION['site_id'] = $sql['site_id'];
			return $sql;
		}

		function recruitingModeSwitch($val){

			$site_id = $_SESSION['site_id'];

			$sql = $this->dbb->update("UPDATE `site_settings` SET `recruiting_mode` = $val WHERE `site_settings`.`site_id` = $site_id");

			return $sql;
		}

		function getRecruitingMessage(){
			$sql = $this->dbb->select("SELECT recruiting_message FROM `site_settings`");

			return $sql;
		}

		function updateRecruitingMessage($post){
			$sql = $this->dbb->update("UPDATE `site_settings` SET `recruiting_message` = '$post' WHERE `site_settings`.`site_id` = 2");

			return $sql;
		}
	}