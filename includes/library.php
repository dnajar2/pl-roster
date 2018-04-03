<?php
	session_start();

	date_default_timezone_set("America/Los_Angeles");
	define("DB_HOST","localhost");
	define("DB_USER_NAME", "root");
	define("DB_PASS","Admin123.!");
	define("D_BASE","user_db");
	define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
	define("INCLUDES", $_SERVER['DOCUMENT_ROOT'] . '/includes');