<?php
	/**
	 * Created by PhpStorm.
	 * User: dnajar
	 * Date: 1/30/2018
	 * Time: 3:57 PM
	 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/personal/dataBase/includes/library.php";

	class _con	{
		public $d_base = null;
		public $host = null;
		public $user_name = null;
		public $pass = null;
		public $db = null;
		public $res = null;

		function __construct($d_base = null,$host = null,$user_name= null,$pass= null, $db= null){
			$this->d_base = D_BASE;
			$this->host = DB_HOST;
			$this->user_name = DB_USER_NAME;
			$this->pass = DB_PASS;
			$this->db = new mysqli($this->host, $this->user_name, $this->pass,$this->d_base);
			if ($this->db->connect_error) {
				die("Connection failed: " .$this->db->connect_error);
			}
			$this->res = new stdClass();
		}

		public function select($sql){
				$result = null;
				$row = null;
			if(gettype($sql) == "string"){
				$result = $this->db->query($sql);

				if($result->num_rows == 1){
					while($rows = $result->fetch_assoc()){
						$row = $rows;
					}
				}else{

					while($rows = $result->fetch_array(MYSQLI_ASSOC)){
						$row[] = $rows;
					}
				}
			}else{
				$row = null;
			}
			return $row;
			$this->db->close();
		}

		public function insert($sql){

			if(gettype($sql)=="string"){

				if($this->db->query($sql)===true){
					$this->res->message = "New record created successfully";
					$this->res->row_id = $last_id = $this->db->insert_id;
					$this->res->status = "success";
				}else{
					$this->res->message = "Error" . $sql ."<br>" . $this->db->error;
					$this->res->status = "failed";
				}
			}
			return $this->res;
			$this->db->close();
		}

		public function update($sql){

			if(gettype($sql) == "string"){

				if($this->db->query($sql) === true){
					$this->res->message = "Record Updated successfully";
					$this->res->status = "success";
				}else{
					$this->res->message = "Error" . $sql ."<br>" . $this->db->error;
					$this->res->status = "failed";
				}
			}
			return $this->res;
			$this->db->close();
		}
	}