<script src="assets/js/tinymce/tinymce.min.js"></script>
<script>
	tinymce.init({
		selector: "textarea",
		plugins: "code",
		toolbar: "code",
		height: "500"
	});
</script>
<style type="text/css">
	.mce-notification-inner{
		display: none !important;
		}
</style>
<div class="container">
	<div class="welcome">
		<h3>Welcome <?php echo $_SESSION['user']['f_name'] . "<br>"; ?></h3>
		<hr>
	</div>
<!-- if admin-->
<?php
	require_once "includes/classes/class.users.php";
	require_once "includes/classes/class.siteSettings.php";

	$user = new users();
	$siteSettings = new siteSettings();
	if($_SESSION['user']['access_level'] == 5){
		echo "Admin Stuff";

		$pl_counts = $user->pl_counts();
		$players = $user->player_list();
		?>
	<div id="status">
		<div class="cell">
			Total Players:
			<hr>
			<?php echo $pl_counts ?>
		</div>
		<div class="cell">
			Players:
			<hr>
			<?php
				foreach ($players as $key){
					echo $key['first_name'] . " " . $key['last_name'] ." - #".$key['player_no']."<br>";
				}
			?>
		</div>
		<div class="cell">
			Site settings
			<hr>
			<?php
				$mode = $siteSettings->recruitingMode();
					
				$r_mode = $mode['recruiting_mode'];
				$mode = "Current Recruiting mode";

				if($r_mode === '2'){
					$mode = $mode . '<br> OFF';
				}else{
					$mode = $mode . '<br> ON';
				}
			?>
			<div id="r_mode"><?php echo $mode?></div>
			<button type="button" id="toggleMode"  class="btn btn-primary" autocomplete="off" data-value="<?php echo $r_mode; ?>" onclick="toggleSettings(this)">Toggle Recruiting Mode</button>
			<div id="editRrecruiting">
				<?php

					if($r_mode === '1'){
						echo "<br><br>Modify Recruiting Message<br><button class='btn btn-success'>Edit</button>";
					}
				?>
			</div>

		</div>
		<div style="clear: both;"></div>
	</div>
	<?php
	}else {

		$pl_id = $_SESSION['user']['pl_id'];
		$player = $user->get_player_info($pl_id);

		echo "<div class='player'>";
		echo "<ul>";
		echo "<li>Player Number: ". $player['pl_id']."</li>";
		echo "<li>Player Name: ". $player['first_name']." ".$player['last_name']."</li>";
		echo "<li>Player DOB: ". $player['dob']."</li>";
		echo "<li>Player Grad Year: ". $player['grad_year']."</li>";
		echo "<li>Commited: ". $player['commited']."</li>";
		echo "<li>Position: ". $player['position']."</li>";
		echo "</ul>";
		echo "</div>";
		echo "<h2>Recruiting Videos</h2>";

		$videos = $user->get_videos($pl_id);
	if($videos){
		foreach ($videos as $key){
			echo '<div class="cell">';
			echo "<h3>video title: " . $key['caption'] .'</h3>';
			echo '<iframe width="320" height="180" src="https://www.youtube.com/embed/'.$key['link'].'" frameborder="0" 
			allow="autoplay; encrypted-media" allowfullscreen></iframe>';
			echo '</div>';
		}
	}else{
		echo "<h2>No recruiting videos found</h2>";
	}

	}


	?>
<!-- modals -->
	<div class="modal fade" tabindex="-1" role="dialog" id="edit_message">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Edit Recruiting Message</h4>
				</div>
				<div class="modal-body">
					<div id="modalSkirt" style="display: none">
						<img src="images/Loading.gif" alt="" class="loader">
						<div id="textAlert" class="loader" style="display: none"></div>
					</div>
					<form id="recruitingMessage">
						<textarea id="message" name="recMessage" class="form-control">
						<?php
							$message = $siteSettings->getRecruitingMessage();
							echo $message['recruiting_message'];
						?>
					</textarea>
					</form>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick="saveMessage('message')">Save changes</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>
<script>
	$('#editRrecruiting').on('click','button', function(){
		$('#edit_message').modal('toggle')
	})
	function toggleSettings(t){

		var currentMode = $(t).attr('data-value');
		var modeSwitch;

		if(currentMode === '2'){
			modeSwitch = '1'
		}else{
			modeSwitch = '2'
		}
		console.log( $(t).attr('data-value') + " New Val " + modeSwitch )
		var url = "api/siteSettings.php";

		$.ajax({
			url:url,
			method:'POST',
			cache:false,
			data:{'toggle':'toggle','mode':modeSwitch},
			success:function(data){
				var res = JSON.parse(data);
				console.log(res);
				var mode = res.mode;
				var current_mode
				var status = res.message.status;
				$('#toggleMode').attr('data-value',  mode);
				console.log("mode:"+mode+" Status:"+status);

				if(mode == 1){
					current_mode = "ON";
					$('#editRrecruiting').html('<br><br>Modify Recruiting Message<br><button class="btn btn-success">Edit</button>');
				}else{
					current_mode = "OFF"
					$('#editRrecruiting').html('');
				}
				$('#r_mode').html('Current Recruiting mode <br>' + current_mode)
			}
		})

	}
	function saveMessage(id) {
		$('#modalSkirt').slideToggle('slow');
		var message = $('#' + id).html( tinymce.get(id).getContent() ).val();
		console.log(message);
		var url = "api/siteSettings.php";
		var data = $('#recruitingMessage').serialize();
		$.ajax({
			url:url,
			method:"POST",
			cache:false,
			data:data,
			success:function(data){

				var res = JSON.parse(data);
				console.log(res.req);
				setTimeout(function () {
					$('#textAlert').text(res.req.message).addClass(res.req.status).show();
					setTimeout(function () {
						$('#modalSkirt').slideToggle('slow');
						$('#textAlert').text("").removeClass(res.req.status).hide();
					},2000)
				},2000)
			}
		})

	}

	$(document).on('focusin', function(e) {
		if ($(e.target).closest(".mce-window").length) {
			e.stopImmediatePropagation();
		}
	});
</script>