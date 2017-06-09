  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
			<div class="col-md-6">
			<?php echo $message;?>
				<?php 
				  $attributes = array('name' => 'formm', 'id' => 'formm');
				  //echo form_open('settings/travelLocationCosts/'.$param,$attributes);
				  echo form_open(base_url(uri_string()),$attributes);
				  ?> 



				<div class="form-group">
				 <label><?php echo getPhrase('Price Variations');?></label>			   
					<?php
						$variations = array();
						for($i = 1; $i <= 20; $i++)
							$variations[$i] = $i;
						echo form_dropdown('price_variations', $variations, '', 'id="price_variations" class="chzn-select"').form_error('price_variations');
					?>	  
			    </div>
			   
				<div class="form-group">
					<button type="submit" class="btn btn-success"><?php echo getPhrase('Go')?></button>
					<a onclick="window.location.href = '<?php echo base_url().'settings/travelLocationCosts/list';?>';" class="btn btn-info" title="<?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?>"> <?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></a>
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

			/* Additional Methods */
			$.validator.addMethod("proper_value", function(uid, element) {
					return (this.optional(element) || uid.match(/^((([0-9]*)[\.](([0-9]{1})|([0-9]{2})))|([0-9]*))$/));
				}, "<?php if(isset($this->phrases["please enter valid value"])) echo $this->phrases["please enter valid value"]; else echo "Please enter valid value";?>.");

			/* Form validation rules */
              $("#formm").validate({
                  rules: {
                travel_location_id: {
                          required: true
                      },
                vehicle_id: {
                          required: true
                      }
                  },

				messages: {
					travel_location_id: {
							  required: "<?php if(isset($this->phrases["please select travel location"])) echo $this->phrases["please select travel location"]; else echo "Please select Travel Location";?>."
						  },
					vehicle_id: {
							  required: "<?php if(isset($this->phrases["please select vehicle"])) echo $this->phrases["please select vehicle"]; else echo "Please select Vehicle";?>."
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