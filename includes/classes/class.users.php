<?php
	/**
	 * Created by PhpStorm.
	 * User: dnajar
	 * Date: 1/31/2018
	 * Time: 9:42 AM
	 */
	require_once "class.con.php";

	class users extends _con{
		public $LOGGED_IN = null;
		public $member = null;
		public $dbb = null;

		function __construct(){
			parent::__construct();
			$this->dbb = new _con();
		}
		public function checkUser($user_name,$pass){
			$this->res->message = "user name or password are not valid!";
			$this->res->class = "error";

			if( !empty($user_name)  || !empty($pass) ){
				$user_name = trim($user_name);
				$pass = trim($pass);



				$user = $this->dbb->select("SELECT * FROM `user` WHERE user_name = '$user_name' AND password = MD5('$pass')");

				if(gettype($user) == "array"){
					$_SESSION['page'] = "home page";
					$_SESSION['log_in'] = true;
					$_SESSION['user'] = $user;
					$this->LOGGED_IN = $_SESSION['log_in'];
					$this->res->message = "$user_name has logged in successfully";
					$this->res->class = "success";
					$this->res->user_name = $user['user_name'];
				}
			}
			return $this->res;

		}

		public function pl_counts(){
			$pl_count = null;
			$total_players = $this->dbb->select("SELECT COUNT(pl_id) AS PLAYERS FROM roster WHERE active = 1");

			foreach ($total_players as $key=>$val  ){
				$pl_count = $val;
			}
			return $pl_count;
		}

		public function player_list(){
			$players = null;
			$sql = $this->dbb->select("SELECT first_name, last_name, player_no FROM roster WHERE active = 1 ORDER by last_name ASC");

			return $sql;
		}
		public function get_player_info($player_id){
			$sql = $this->dbb->select("SELECT * FROM `roster` WHERE pl_id = $player_id");
			return $sql;
		}

		public function get_videos($player_id){
			$videos = null;
			$sql = $this->dbb->select("SELECT * FROM `videos` where pl_id = $player_id");

			return $sql;
		}

		public function createNewPl($post){
			$first_name = trim($post['f_name']);
			$last_name = trim($post['l_name']);
			$dob = trim($post['dob']);
			$grad_year = trim($post['grad_year']);
			$player_no = trim($post['player_no']);
			$position = trim($post['position']);
			$parents = trim($post['parents']);


			$sql = $this->dbb->insert("INSERT INTO `roster` 
											(`first_name`, `last_name`, `dob`, `profile_image`, `grad_year`, `commited`, `about`, `player_no`, `position`, `parents`, `active`) 
											VALUES ('$first_name', '$last_name', '$dob', NULL, '$grad_year', '0', NULL, '$player_no', '$position', '$parents', '1') 
											");

			return $sql;
		}
		public function createNewPlStep2($post){
			$pl_id = $post['pl_id'];
			$field = $post['field'];
			$fieldVal = $post['field2'];
//			update roster

			$sql = $this->dbb->update('UPDATE `roster` SET `$field` = "$fieldVal" WHERE `roster`.`pl_id` = $pl_id');


		}
		public function createPlGeneralInfo($post){

	}
		public function updatePlayerImage($post,$file){
			$target_dir = "../assets/img/players/";
			$temp = explode(".", $file["profile_image"]["name"] );

			$newFileName = round(microtime(true).'.'.end($temp));

			$target_file = $target_dir . $newFileName.'.'.$temp[1];
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($file["profile_image"]["name"], PATHINFO_EXTENSION));

			$check = getimagesize($file['profile_image']['tmp_name']);
			if($check !== false){
				$this->res->imageMessage = "File is an image " . $check["mime"] . ".";
				$this->res->upLoadSts = $uploadOk = 1;
			}else{
				$this->res->imageMessage = "File not an image";
				$this->res->upLoadSts =  $uploadOk = 0;
			}
			//Check if file alreay exists
			if(file_exists($target_file)){
				$this->res->file_exists = "Sorry file already exists";
				$uploadOk = 0;
			}
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"){
				$this->res->fileType = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				$uploadOk = 0;
			}
			if($uploadOk == 0){
				$this->res->uploadStsMsg = "Sorry, your file was not uploaded.";
			}else{

				if(move_uploaded_file($file["profile_image"]["tmp_name"], $target_file )){
					$this->res->uploadStsMsg =  "The file ". basename( $target_file). " has been uploaded.";
					$this->res->post = $post;
				// SQL call here
					$image = $newFileName.'.'.$temp[1];
					$pl_id = $post['pl_id'];
					$sql = $this->dbb->update("UPDATE `roster` SET `profile_image` = '$image' WHERE `roster`.`pl_id` = '$pl_id'");
					$this->res->sqlMsg = $sql;
				}else{
					$this->res->uploadStsMsg = "Sorry, there was an error uploading your file.";
				}
			}

			return $this->res;
		}
	}