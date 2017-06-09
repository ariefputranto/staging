  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">

			<?php echo $this->session->flashdata('message');?>
				<?php  
			  $attributes = array('name' => 'tokenform', 'id' => 'tokenform','method' => 'post');
			  echo form_open( '',$attributes);
			  ?>
			<div class="col-md-6">
			 <div class="panel">
				<div class="panel-heading ele-hea"> <?php if(isset($this->phrases["add field values for"])) echo $this->phrases["add field values for"]; else echo "Add field values for";?> <?php echo (count($fields) > 0) ? $fields[0]->gateway_title : '';?> <i class="fa fa-plus"></i> </div>
				<div class="panel-body paddig">
				  <?php echo validation_errors();
				if( isset( $errors ) )
					echo $errors;
				?>
				 <?php if($this->session->flashdata('message') != '') { echo '<div class="flash_msg">'.$this->session->flashdata('message').'</div>'; }?>
				  <?php  
				  $attributes = array('name' => 'tokenform', 'id' => 'tokenform','method' => 'post');
				  echo form_open_multipart( '',$attributes);
				  ?>
				  <div class="inner-pages-forms">
				   <div class="col-md-12">               
				   <?php
				   if(count($fields) > 0) {
				   foreach( $fields as $row) { ?>
					   <div class="form-group">
					   <label> <?php echo $row->field_name;?> </label>
					   <?php			
						if($row->field_type == 'select') {
							$optionsarray = array('' => 'Please select');
							if($row->field_type_values != '') {
								$options = explode(',', $row->field_type_values);
								if(count($options) > 0) {
									$optionsarray = array();
									foreach($options as $key => $val)
									$optionsarray[$val] = ucfirst($val);
								}
							}
							$str = '';
							if($row->is_required == 'Yes') {
								$str = ' required';
							}
							$valu = (isset($row->gateway_field_value)) ? $row->gateway_field_value : '';
							echo form_dropdown('field['.$row->field_id.']', $optionsarray, $valu, $str);
							
						} elseif($row->field_type == 'file') {
							if($row->is_required == 'No') {
								$element = array(
									'type' => 'file',
									'name'	=>	'field['.$row->field_id.']',
									'id'	=>	'field['.$row->field_id.']',
									'value'	=>	(isset($row->gateway_field_value)) ? $row->gateway_field_value : '',
								);
							} else {
								$element = array(
									'type' => 'file',
									'name'	=>	'field['.$row->field_id.']',
									'id'	=>	'field['.$row->field_id.']',
									'value'	=>	(isset($row->gateway_field_value)) ? $row->gateway_field_value : '',
									'required' => 'required',
								);
							}
							echo form_input($element);
						}else {
							if($row->is_required == 'No') {
								$element = array(
									'name'	=>	'field['.$row->field_id.']',
									'id'	=>	'field['.$row->field_id.']',
									'value'	=>	(isset($row->gateway_field_value)) ? $row->gateway_field_value : '',
								);
							} else {
								$element = array(
								'name'	=>	'field['.$row->field_id.']',
								'id'	=>	'field['.$row->field_id.']',
								'value'	=>	(isset($row->gateway_field_value)) ? $row->gateway_field_value : '',
								'required' => 'required',
							);
							}
							echo form_input($element);
						}
						?>			   
					   </div>  
				   <?php } }
					else {
						echo 'No fields for this gateway. Click <a href="'.base_url().'admin/addsmsfields/'.$gid.'">here</a> to add';
					}
				   ?>

				   </div>
				   <?php
				   if(count($fields) > 0) { ?>
				   <div class="buttos">
				   <button type="submit" class="btn btn-success" name="submitword" value="submitword"><i class="fa fa-plus"></i> <?php if(isset($this->phrases["add"])) echo $this->phrases["add"]; else echo "Add";?></button>
				   <?php if($fields[0]->type == 'payment') {
					   ?>
					<button type="button" class="btn btn-info" onclick="window.location.href = '<?php echo base_url().'admin/paymentsettings';?>';"><i class="fa fa-times"></i> <?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></button>
					   <?php
				   } else { ?>
				   <button type="button" class="btn btn-info" onclick="window.location.href = '<?php echo base_url().'admin/sms_settings';?>';"><i class="fa fa-times"></i> <?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></button>
				   <?php } ?>
				   </div>
				   <?php } ?>
				  </div>
				  <input type="hidden" name="gid"  value="<?php echo $gid?>">
				  <?php echo form_close();?>			  
				</div>
			  </div>                               
				
			</div>
		  </div>
      </div>
    </div>
  </div>
</section>
