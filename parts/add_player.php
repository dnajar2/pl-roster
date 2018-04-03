<div class="container">
	<h2>Add New Player</h2>
	<hr>
	<div id="playerStepOne">
		<div id="player" class="pull-right third" style="display: none">
			<div>
				<strong>Review Player's Information before submitting it.</strong>
			</div>
			<hr>
			<div class="fullname"><strong>Player's Full Name:</strong> <span class="f_name"></span> <span class="l_name"></span></div>
			<div class="dob"><span><strong>Player's DOB:</strong> </span></div>
			<div class="grad_year" ><span><strong>Player's Grad Year:</strong> </span></div>
			<div class="player_no"><span><strong>Player's Number:</strong> </span></div>
			<div class="position"><span><strong>players's Position:</strong> </span></div>
			<div class="parents"><span><strong>Players's Position:</strong></span></div>
			<br>
			<button class="btn" id="submitBtn" style="display: none">Submit</button>
		</div>
		<div  class="pull-left two-thirds">
			<form id="playerForm" action="api/createPlayer.php">
				<input type="hidden" name="step">
				<div class="form-elem">
					<label for="f_name">First Name:</label>
					<input type="text" id="f_name" name="f_name" placeholder="Enter player's first name">
				</div>
				<div class="form-elem">
					<label for="l_name">Last Name:</label>
					<input type="text" id="l_name" name="l_name" placeholder="Enter player's last name">
				</div>
				<div class="form-elem">
					<label for="dob">Date of Birth:</label>
					<input type="text" id="dob" name="dob" placeholder="Enter player's Date of Birth 'mm/dd/yyyy'">
				</div>
				<div class="form-elem">
					<label for="grad_year">Grad Year:</label>
					<input type="number" id="grad_year" name="grad_year" placeholder="Enter player's grad year 'yyyy'">
				</div>
				<div class="form-elem">
					<label for="player_no">Player Number:</label>
					<input type="number" id="player_no" name="player_no" placeholder="Enter player's number">
				</div>
				<div class="form-elem">
					<label for="position">Player Position(s):</label>
					<input type="text" id="position" name="position" placeholder="Enter player's position(s)">
				</div>
				<div class="form-elem">
					<label for="parents">Player Parents:</label>
					<input type="text" id="parents" name="parents" placeholder="Enter player's parents">
				</div>
			</form>
		</div>
	</div>
	<div style="clear: both;"></div>
	<div id="playerStepTwo" style="display: none;">
		<h2>Complete Player Profile for <span class="f_name"></span> <span class="l_name"></span></h2>
		<hr>
		<div>
			<form id="stepTwo" action="">
				<input type="hidden" name="step" value="step2">
				<input type="hidden" id="pl_id" name="pl_id">
			<h3>Add player profile image</h3>
			<input type="file" id="profile_image" name="profile_image">
			<h3>Player Contact Info</h3>
			<div class="form-elem">
				<label for="phone">
					Phone:
				</label>
				<input type="text" id="phone" name="phone">
				<label for="email">
					Email:
				</label>
				<input type="text" id="email" name="email">
				<label for="address">
					Address:
				</label>
				<input type="text" id="address" name="address">
			</div>
			<h3>Additional Player Information</h3>
			<div class="form-elem">
				<label for="height">
					Height:
				</label>
				<input type="text" id="height" name="height">
				<label for="bats">
					Bats left or right:
				</label>
				<input type="text" id="bats" name="height">
				<label for="throws">
					Throws left or right:
				</label>
				<input type="text" id="throws" name="height">
			</div>
			</form>
		</div>
	</div>

</div>
<script type="text/javascript">
	var isValid = null;
	$('#playerForm input').on('change', function(){
		$('#player').show()

		$("#playerForm  input").each(function() {
			var element = $(this);
			if (element.val() == "") {
				isValid = false;
			}else{
				isValid = true
			}
		});

		var sectionClass = $(this).attr('id');
		var value = $(this).val();

		if(sectionClass == "f_name" || sectionClass == "l_name"){
			$('.'+sectionClass).text(value);
		}else{
			$('.'+sectionClass +' span').after(value);
		}
		if(isValid){
			$('#submitBtn').show()
		}

		console.log($(this).val(),$(this).attr('id'))
	});

	$('#submitBtn').on('click', function(){
		var actionUrl = $('#playerForm').attr('action');
		var data = $('#playerForm').serialize();

		$.ajax({
			url:actionUrl,
			data:data,
			method:'post',
			cache:false,
			success:function(data){
				var res = JSON.parse(data);
				console.log(res);

				if(res.status == "success"){
					$('#pl_id').val(res.row_id);
					$('#playerStepOne').slideUp('slow', function(){
						$('#playerStepTwo').slideDown()
					});
				}else{

				}
			}
		})
	})

	$('#playerStepTwo input').on('change', function () {

		var actionUrl = $('#playerForm').attr('action');

		var id = $(this).attr('id');
		var data;
		var pl_id = $('#pl_id').val();
		data = new FormData();
		//add properties to PL object
		data.append('step','step2');
		data.append('pl_id',pl_id);

		if(id == 'profile_image'){
			var file_data = $('#profile_image').prop('files')[0];
			data.append('profile_image', file_data);

		}else{
			var dataVal = $(this).val();
			var attr = $(this).attr('name');

			data.append('field', attr);
			data.append('field2', dataVal);
		}

		$.ajax({
			url:actionUrl,
			type:'POST',
			data:data,
			cache:false,
			contentType:false,
			processData:false,
			success:function(data){
				var res = JSON.parse(data)
				console.log(res)
			}

		})
	})
</script>