<?php
	/**
	 * Created by PhpStorm.
	 * User: dnajar
	 * Date: 5/25/2018
	 * Time: 10:00 AM
	 */
	include "includes/classes/class.users.php";

	$pl_id = (int)$_GET['edit_player'];

	if(!isset($_SESSION['log_in'])){
		die();
	}

	$pl = new users();
	$player = $pl->get_player_info($pl_id);
	?>
<div class="container">
	<h2>Update <?php echo $player['first_name'] . " " . $player['last_name']?></h2>
	<hr>
	<div class="col-md-6">
		<form action="">
			<input type="hidden" name="pl_id" value="<?php echo $player['pl_id'] ?>" >
			<div class="form-group">
				<label for="first_name">First Name</label>
				<input type="text" name="first_name" value="<?php echo $player['first_name']; ?>" id="first_name" class="form-control">
			</div>
			<div class="form-group">
				<label for="first_name">Last Name</label>
				<input type="text" name="last_name" value="<?php echo $player['last_name']; ?>" class="form-control">
			</div>
			<div class="form-group">
				<label for="dob">Date of Birth</label>
				<input type="text" name="dob" value="<?php echo $player['dob']; ?>" class="form-control">
			</div>
			<div class="form-group">
				<label for="grad_year">Grad Year</label>
				<input type="text" name="grad_year" value="<?php echo $player['grad_year']; ?>" class="form-control">
			</div>
			<div class="form-group">
				<label for="commited">Committed</label>
				<input type="text" name="commited" value="<?php echo $player['commited'] ? "Yes":"No"; ?>" class="form-control">
			</div>
			<div class="form-group">
				<label for="player_no">Player No</label>
				<input type="text" name="player_no" value="<?php echo $player['player_no']; ?>" class="form-control">
			</div>
			<div class="form-group">
				<label for="position">Position</label>
				<input type="text" name="position" value="<?php echo $player['position']; ?>" class="form-control">
			</div>
			<div class="form-group">
				Player Status: <?php echo $player['active'] ? "Active":"Inactive" ;?>
				<br>
				<label for="active">Change Player Status</label>
				<select name="active" id="active" class="form-control">
					<option>Choose One</option>
					<option value="0">Inactive</option>
					<option value="1">Active</option>
				</select>
			</div>
		</form>
	</div>
	<div class="clearfix"></div>

</div>
