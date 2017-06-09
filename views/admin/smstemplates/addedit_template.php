  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
		  <?php echo $this->session->flashdata('message');?>
				<?php 
				  $attributes = array('name' => 'formm', 'id' => 'formm');
				  echo form_open('admin/smstemplates/'.$param,$attributes);?> 

				<?php if(count($records) > 0) $record = $records[0]; 
					?>
			<div class="col-md-6">
				<div class="form-group">
					<label><?php if(!empty($this->phrases["title"])) echo $this->phrases["title"]; else echo "Title";?></label>
					 <input type="text" name="template_key" id="template_key" value="<?php echo set_value('template_key', (isset($record->template_key)) ? $record->template_key : '');?>"/>
					<?php echo form_error('template_key'); ?>
				</div>
				
				<div class="form-group">
					<label><?php if(!empty($this->phrases["description"])) echo $this->phrases["description"]; else echo "Description";?></label>
					 <textarea class="editor" name="template_content" id="template_content"><?php echo set_value('template_content', (isset($record->template_content)) ? $record->template_content : '');?></textarea>
					<?php echo form_error('template_content'); ?>
				</div>
				
				<div class="form-group">
					<label><?php if(!empty($this->phrases["allowed variables"])) echo $this->phrases["allowed variables"]; else echo "Allowed Variables";?></label>
					 <?php echo (isset($record->template_variables)) ? $record->template_variables : '';?>
				</div>
								
				<div class="form-group">
				 <label><?php if(!empty($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></label>			   
					<?php

						$selected = set_value('status', (isset($record->status)) ? $record->status : '');

						$first_opt = (!empty($this->phrases["active"])) ? $this->phrases["active"] : "Active";
						$sec_opt   = (!empty($this->phrases["inactive"])) ? $this->phrases["inactive"] : "Inactive";

						$opts = array(
									'Active'   => $first_opt,
									'In-Active' => $sec_opt
									);

						echo form_dropdown('template_status', $opts, $selected, 'id="status" ');
					?>
			   </div>

			   <input type="hidden" name="update_rec_id" value="<?php if(isset($record->template_id)) echo $record->template_id;?>" />

				<div class="form-group">	  
					<button type="submit" class="btn btn-success"><?php if(isset($record->template_id)) echo (!empty($this->phrases["update"])) ? $this->phrases["update"] : "Update"; else echo (!empty($this->phrases["add"])) ? $this->phrases["add"] : "Add";?></button>
					<a onclick="window.location.href = '<?php echo base_url().'admin/smstemplates';?>';" class="btn btn-info" title="<?php if(!empty($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?>"> <?php if(!empty($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></a>
				</div>
				</form>
				
			</div>
			
		  </div>
      </div>
    </div>
  </div>
</section>


<script type= "text/javascript" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.validate.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/additional-methods.js" ></script>
<script>
	(function($,W,D)
   {
      var JQUERY4U = {};

      JQUERY4U.UTIL =
      {
          setupFormValidation: function()
          {

			/* Form validation rules */
              $("#formm").validate({
                  rules: {
                template_key: {
                          required: true      
                      },
                template_content: {
                          required: true      
                      }
				},

				messages: {
					template_key: {
							  required: "<?php if(!empty($this->phrases["please enter title"])) echo $this->phrases["please enter title"]; else echo "Please enter Title";?>."
						  },
					template_content: {
	                          required: "<?php if(!empty($this->phrases["please enter description"])) echo $this->phrases["please enter description"]; else echo "Please enter Description";?>."
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
   

   function toggleUsageTypeValDiv()
   {
   		if($('#usage_type option:selected').val() == "multiple") {
   			$('#div_usage_type_val').slideDown();
   		} else {
   			$('#div_usage_type_val').slideUp();
   			$('#usage_type_val').val(1);
   		}
   }

   </script>
<script>
	$('document').ready(function() {
		toggleUsageTypeValDiv();
	});
</script>