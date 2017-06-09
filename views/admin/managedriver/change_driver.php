    <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
			<?php echo $this->session->flashdata('message');?>

			<?php
				$attributes = array("name" => 'register_form',"id" => 'register_form');
				echo form_open('managedriver/change_driver', $attributes);
				?>
		  <div class="col-md-6">
			<div class="form-group">
			   <?php
			   $val = '';
			   if(isset($_POST['show']) || isset($_POST['change']))
				  $val = $_POST['present_driver'];
			  if(isset($_POST['change']))
			   ?>
			   <?php echo form_dropdown('present_driver', $other_drivers, $val, 'class="chzn-select"');?> Changes with 
               <?php echo form_error('present_driver'); ?>
			</div>
			
			<div class="form-group">
			   <?php
			   $val = date('Y-m-d');
			   if(isset($_POST['show']) || isset($_POST['change']))
				  $val = $_POST['special_start'];
			   ?>
			   <input type="text" name="special_start" id="special_start" value="<?php echo $val;?>" class="calendar" placeholder="Sepecial Start"  readonly>
               <?php echo form_error('special_start'); ?>
			</div>
		 </div>
		 
		 <div class="col-md-6">
			<div class="form-group">
			   <?php
			   $val = '';
			   if(isset($_POST['show']) || isset($_POST['change']))
				  $val = $_POST['new_driver'];
			   ?>
			   <?php echo form_dropdown('new_driver', $other_drivers, $val, 'class="chzn-select"');?>
               <?php echo form_error('new_driver'); ?>
			</div>

			<div class="form-group">
			   <?php
			   $val = date('Y-m-d', strtotime(date('Y-m-d')." +1 day"));
			   if(isset($_POST['show']) || isset($_POST['change']))
				  $val = $_POST['special_end'];
			   ?>
			   <input type="text" name="special_end" id="special_end" placeholder="Sepecial End" value="<?php echo $val;?>" class="calendar" readonly>
               <?php echo form_error('special_end'); ?>
			</div>
			
			<div class="form-group">
			<button class="btn btn-success" type="submit" name="show"><?php if(isset($this->phrases["show"])) echo $this->phrases["show"]; else echo "Show";?></button>
			</div>
			
		 </div>
		 
		 </form>
		 
		<?php
		if((isset($_POST['show']) && validation_errors() == '') || isset($_POST['change']))
		{
		$attributes = array("name" => 'register_form2',"id" => 'register_form2');
		echo form_open('managedriver/change_driver', $attributes);
		?>
		 <div class="col-md-12">
			<div class="form-group">
			   <table width="100%">
			   <?php
			   if(isset($details) && !empty($details))
			   {
			   ?>
			   <tr><td colspan="5">Shuttles of <b><?php echo $details[0]->first_name.' '.$details[0]->last_name;?></b></td></tr>
			   
			   <tr><td>#</td><td>Shuttle</td><td>Vehicle</td><td>Schedule</td><td>Action</td></tr>
			   <?php
				$i = 0;
				foreach($details as $d)
				{
					$checked = '';
					if(in_array($d->id, $assigned_shuttles))
						$checked = ' checked';
					echo '<tr><td>'.++$i.'</td><td>'.$d->shuttle_no.'</td><td>'.$d->vehicle_name.' ('.$d->vehicle_model.')-'.$d->number_plate.'</td><td>'.$d->start_time.' to '.$d->destination_time.'</td><td><input type="checkbox" name="shuttles[]" value="'.$d->id.'_'.$d->vehicle_id.'"'.$checked.'>'.form_error('shuttles[]').'</td></tr>';
				}				
			   }
			   else
			   {
				   echo '<tr><td colspna="5">No Shuttle found</td></tr>';
			   }
			   ?>
			   </table>
			</div>
			<?php 
			$val = '';
			if(isset($_POST['show']))
				  $val = $_POST['present_driver'];
			if(isset($_POST['change']))
				  $val = $_POST['present_driver'];
			?>
			<input type="hidden" name="present_driver" value="<?php echo $val;?>">
			
			<?php 
			$val = '';
			if(isset($_POST['show']))
				  $val = $_POST['new_driver'];
			if(isset($_POST['change']))
				  $val = $_POST['new_driver'];
			?>
			<input type="hidden" name="new_driver" value="<?php echo $val;?>">
			
			<?php 
			$val = '';
			if(isset($_POST['show']))
				  $val = $_POST['special_start'];
			if(isset($_POST['change'])) 
				$val = $_POST['special_start'];
			?>
			<input type="hidden" name="special_start" value="<?php echo $val;?>">
			
			<?php 
			$val = '';
			if(isset($_POST['show']))
				  $val = $_POST['special_end'];
			if(isset($_POST['change'])) 
				$val = $_POST['special_end'];
			?>
			<input type="hidden" name="special_end" value="<?php echo $val;?>">
					
			<div class="form-group">
			<button class="btn btn-success" type="submit" name="change"><?php if(isset($this->phrases["change"])) echo $this->phrases["change"]; else echo "Change";?></button>
			</div>
						
		 </div>
		 
		 </form>
		<?php } ?>
		  </div>
      </div>
    </div>
  </div>
</section>

<script type= "text/javascript" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.validate.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/additional-methods.js" ></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
  /*
  $(function() {
    $( ".calendar" ).datepicker({dateFormat: "yy-mm-dd"});
  });
  */
  $(function() {	
	$( "#special_start" ).datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
		onClose: function( selectedDate ) {
		$( "#special_end" ).datepicker( "option", "minDate", selectedDate );
      }
		});
		
	$( "#special_end" ).datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
		onClose: function( selectedDate ) {
        $( "#special_start" ).datepicker( "option", "maxDate", selectedDate );
		}
		});
  });
  </script>
<script>
	(function($,W,D)
   {
      var JQUERY4U = {};

      JQUERY4U.UTIL =
      {
          setupFormValidation: function()
          {
			/* Create Account form validation rules */
              $("#register_form").validate({
                  rules: {
					new_driver: {
							  required: true
						  },
					special_start: {
							  required: true
						  },
					special_end: {
							  required: true
						  },
					shuttles[]: {
							  required: true
						  }
                  },

				messages: {
					new_driver: {
							  required: "<?php if(!empty($this->phrases["please select driver"])) echo $this->phrases["please select driver"]; else echo "Please select driver";?>."
						  },
					special_start: {
							  required: "<?php if(!empty($this->phrases["please select start date"])) echo $this->phrases["please select start date"]; else echo "Please select start date";?>."
						  },
					special_end: {
							  required: "<?php if(!empty($this->phrases["please select end date"])) echo $this->phrases["please select end date"]; else echo "Please select end date";?>."
						  }
				},

                  submitHandler: function(form) {
                      form.submit();
                  }
              });

          }
      }

      //when the dom has loaded setup form validation rules
      $(D).ready(function($) {
          JQUERY4U.UTIL.setupFormValidation();
      });
   
   })(jQuery, window, document);
   </script>
