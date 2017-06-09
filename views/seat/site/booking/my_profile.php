<section class="hil">
<div class="container">
<div class="row">
<div class="col-lg-10 col-lg-offset-1">
<div class="login other-form my">
<ul>
<li>
 
 <?php echo $message;?> 
 <div class="form-feilds">
	<?php
	$attributes = array("name" => 'register_form',"id" => 'register_form');
	echo form_open('', $attributes);
	?>
	<div class="form-group">
	<?php 
	$value = '';
	if(isset($_POST['update']))
		$value = $this->form_validation->set_value('first_name');
	else
		$value = $details->first_name;
	$first_name = array(
				'name' => 'first_name',
				'placeholder' => (isset($this->phrases["first name"])) ? $this->phrases["first name"] : "First Name" ,
				'id' => 'first_name',
				'type' => 'text',
				'value' =>  $value,
			);
	echo form_input($first_name);?>
	<?php echo form_error('first_name'); ?>
	</div>

	<div class="form-group">
	<?php 
	$value = '';
	if(isset($_POST['update']))
		$value = $this->form_validation->set_value('last_name');
	else
		$value = $details->last_name;
	$last_name = array(
				'name' => 'last_name',
				'placeholder' => (isset($this->phrases["last name"])) ? $this->phrases["last name"] : "Last Name" ,
				'id' => 'last_name',
				'type' => 'text',
				'value' => $value,
			);
	echo form_input($last_name);?>
	<?php echo form_error('last_name'); ?>
	</div>
	
	<div class="form-group clearfix">
	<?php 
	$value = 'Male';
	if(isset($_POST['update']))
		$value = $this->form_validation->set_value('gender');
	else
		$value = $details->gender;
	?>
	<span for="gender_male">
	<input type="radio" name="gender" value="Male" id="gender_male" <?php if($value == 'Male') echo 'checked';?>>
	<label for="gender_male" class="side-label">Male</label>
	</span>
	<span for="gender_female">
	<input type="radio" name="gender" value="Female" id="gender_female" <?php if($value == 'Female') echo 'checked';?>>
	<label for="gender_female" class="side-label">Female</label>
	</span>
	<?php echo form_error('gender'); ?>
	</div>

	<div class="form-group">
	<?php
		$value = $details->email;
	$email = array(
				'name' => 'email',
				'placeholder' => (isset($this->phrases["email"])) ? $this->phrases["email"] : "Email" ,
				'id' => 'email',
				'type' => 'text',
				'value' => $value,
				'readonly' => TRUE,
				'disabled' => TRUE,
			);
	echo form_input($email); ?>
	<?php echo form_error('email'); ?>
	</div>

	<div class="form-group">
	<?php 
	$value = '';
	if(isset($_POST['update']))
		$value = $this->form_validation->set_value('phone_code');
	else
		$value = $details->phone_code;
	$phone_code = array(
				'name' => 'phone_code',
				'placeholder' => (isset($this->phrases["phone code"])) ? $this->phrases["phone code"] : "Phone Code",
				'id' => 'phone_code',
				'type' => 'text',
				'value' => $value,
			);
	echo form_input($phone_code); ?>
	<?php echo form_error('phone_code'); ?>
	<?php 
	$value = '';
	if(isset($_POST['update']))
		$value = $this->form_validation->set_value('phone');
	else
		$value = $details->phone;
	$phone = array(
				'name' => 'phone',
				'placeholder' => (isset($this->phrases["phone"])) ? $this->phrases["phone"] : "Phone",
				'id' => 'phone',
				'type' => 'text',
				'value' => $value ,
			);
	echo form_input($phone); ?>
	<?php echo form_error('phone'); ?>
	</div>
	
	<div class="form-group">
	<?php 
	$value = '';
	if(isset($_POST['update']))
		$value = $this->form_validation->set_value('dob');
	else
		$value = $details->dob;
	$dob = array(
				'name' => 'dob',
				'placeholder' => (isset($this->phrases["date of birth"])) ? $this->phrases["date of birth"] : "Date of Birth" ,
				'id' => 'dob',
				'type' => 'text',
				'value' =>  $value,
			);
	echo form_input($dob);?>
	<?php echo form_error('dob'); ?>
	</div>
	
	<div class="form-group">
	<?php 
	$value = '';
	if(isset($_POST['update']))
		$value = $this->form_validation->set_value('address');
	else
		$value = $details->address;
	$address = array(
				'name' => 'address',
				'placeholder' => (isset($this->phrases["address"])) ? $this->phrases["address"] : "Address" ,
				'id' => 'address',
				'type' => 'text',
				'value' =>  $value,
			);
	echo form_textarea($address);?>
	<?php echo form_error('address'); ?>
	</div>



	<div class="form-group">
	<button class="btn btn-default site-buttos" type="submit" name="update"><?php if(isset($this->phrases["create"])) echo $this->phrases["create"]; else echo "Create";?></button>
	</div>
	</form>
 

 

<div class="clearfix"></div></div>
</li>
 
</ul>
</div>
</div>
</div>
</div>
</section>