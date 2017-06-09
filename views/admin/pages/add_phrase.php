  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
			<div class="col-md-6">
			<?php echo $this->session->flashdata('message');?>

				<?php $attributes = array('name'=>'add_phrase_form','id'=>'add_phrase_form');
                           echo form_open("settings/add_edit_Phrase",$attributes) ?>
                        <div class="form-group">
                           <label><?php if(isset($this->phrases["phrase"])) echo $this->phrases["phrase"]; else echo "Phrase";?></label>
                          <input type="text" name="phrase_name" id="phrase_name" value="<?php echo set_value('phrase_name', (isset($phrase_rec->text)) ? $phrase_rec->text : '');?>"/>
							<?php echo form_error('phrase_name'); ?>
                        </div>

                           <?php 
                              if(isset($phrase_rec->id) ) {?>
                           <input type="hidden" name="update_rec_id" value="<?php if(isset($phrase_rec->id)) echo $phrase_rec->id;?>"/>
                           <?php } ?>

                        <div class="form-group">	  
							<button type="submit" class="btn btn-success"><?php if(isset($phrase_rec->id)) echo (isset($this->phrases["update"])) ? $this->phrases["update"] : "Update"; else echo (isset($this->phrases["add"])) ? $this->phrases["add"] : "Add";?></button>
							<a onclick="window.location.href = '<?php echo base_url().'settings/languages';?>';" class="btn btn-info" title="<?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?>"> <?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></a>
						</div>
                        
                        <?php echo form_close(); ?>
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
              $("#add_phrase_form").validate({
                  rules: {
                phrase_name: {
                          required: true      
                      }
                  },

				messages: {
					phrase_name: {
							  required: "<?php if(isset($this->phrases["please enter phrase"])) echo $this->phrases["please enter phrase"]; else echo "Please enter Phrase";?>."
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
