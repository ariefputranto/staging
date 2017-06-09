  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">

			<?php echo $this->session->flashdata('message');?>
				<?php 
				  $attributes = array('name' => 'formm', 'id' => 'formm');
				  echo form_open_multipart('admin/banks/'.$param,$attributes);?> 

				<?php if(count($records) > 0) $record = $records[0]; 
					?>
			<div class="col-md-6">
				
				<div class="form-group">
					<label><?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?>&nbsp;<font color="red">*</font></label>
					 <input type="text" name="name" id="name" placeholder="<?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?>" value="<?php echo set_value('name', (isset($record->name)) ? $record->name : '');?>"/>
					<?php echo form_error('name'); ?>
				</div>
				
				<div class="form-group">
					<label><?php if(isset($this->phrases["comments"])) echo $this->phrases["comments"]; else echo "Comments";?></label>
					 <textarea name="comments" id="comments"><?php echo set_value('comments', (isset($record->comments)) ? $record->comments : '');?></textarea>
					<?php echo form_error('comments'); ?>
				</div>
				
				<div class="form-group">
					<label><?php 
					//print_r($record);
					if(isset($this->phrases["browse image ( upload image )"])) echo $this->phrases["browse image ( upload image )"]; else echo "Browse Image ( Upload Image )";?>  </label>
					 <input type="file" name="userfile" id="image-input" onchange="previewImages(this);" style="width:80px;">
					  <?php echo form_error('userfile');?>
				</div>

				<input type="hidden" name="is_image_set" id="is_image_set" value="">
					<div class="preview-area">

						<?php if(isset($record->image) && $record->image != "" && file_exists('uploads/banks/'.$record->image)) { ?>
							<img height="100" src="<?php echo base_url();?>uploads/banks/<?php echo $record->image;?>" >
						<?php } ?>

					</div>
				
				<div class="form-group">
				 <label><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></label>			   
					<?php

						$selected = set_value('status', (isset($record->status)) ? $record->status : '');

						$first_opt = (isset($this->phrases["active"])) ? $this->phrases["active"] : "Active";
						$sec_opt   = (isset($this->phrases["inactive"])) ? $this->phrases["inactive"] : "Inactive";

						$opts = array(
									'Active' => $first_opt,
									'Inactive' => $sec_opt
									);

						echo form_dropdown('status', $opts, $selected, 'id="status" class="chzn-select" ');
					?>	  
			   </div>

				 <input name="current_img" id="current_img" type="hidden" value="<?php if(isset($record->image)) echo $record->image; ?>">	  
				 <input type="hidden" name="update_rec_id" value="<?php if(isset($record->id)) echo $record->id;?>" />

				<div class="form-group">	  
					<button type="submit" class="btn btn-success"><?php if(isset($record->id)) echo (isset($this->phrases["update"])) ? $this->phrases["update"] : "Update"; else echo (isset($this->phrases["add"])) ? $this->phrases["add"] : "Add";?></button>
					<a onclick="window.location.href = '<?php echo base_url().'admin/banks';?>';" class="btn btn-info" title="<?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?>"> <?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></a>
				</div>

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

			/* Additional Methods */
			$.validator.addMethod("proper_value", function(uid, element) {
					return (this.optional(element) || uid.match(/^((([0-9]*)[\.](([0-9]{1})|([0-9]{2})))|([0-9]*))$/));
				}, "<?php if(isset($this->phrases["please enter valid value"])) echo $this->phrases["please enter valid value"]; else echo "Please enter valid value";?>.");
			
			
			/* Form validation rules */
              $("#formm").validate({
                  rules: {
                
				name: {
                          required: true      
                      },
				designation: {
                          required: true      
                      },
				comments: {
                          required: true      
                      },
                image: {
						extension: "png|jpg|jpeg"
					}
                  },

				messages: {					
					name: {
							  required: "<?php if(isset($this->phrases["please enter name"])) echo $this->phrases["please enter name"]; else echo "Please enter Name";?>."
						  },
					designation: {
							  required: "<?php if(isset($this->phrases["please enter designation"])) echo $this->phrases["please enter designation"]; else echo "Please enter designation";?>." 
                      },
					comments: {
							  required: "<?php if(isset($this->phrases["please enter comments"])) echo $this->phrases["please enter comments"]; else echo "Please enter comments";?>."      
						  },					
					image: {
						extension: "<?php if(isset($this->phrases["please upload image with the extension jpg|jpeg|png"])) echo $this->phrases["please upload image with the extension jpg|jpeg|png"]; else echo "please upload image with the extension jpg|jpeg|png";?>."
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


   /* Preview Uploaded Images */
	function previewImages(input)
	{
		var fileList = input.files;

		var anyWindow = window.URL || window.webkitURL;
		input.style.width = '100%';
		$('.preview-area').html('');
			for(var i = 0; i < fileList.length; i++){
			 /* get a blob */
			  var objectUrl = anyWindow.createObjectURL(fileList[i]);
			   /* for the next line to work, you need something class="preview-area" in your html */
			  $('.preview-area').append('<img height="100" src="' + objectUrl + '" />');
			  /* get rid of the blob */
			  window.URL.revokeObjectURL(fileList[i]);
			  $('#is_image_set').val('yes');
			}
	}

   </script>
