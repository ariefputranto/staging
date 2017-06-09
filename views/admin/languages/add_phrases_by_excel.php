  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">

			<div class="col-md-6">
			<?php echo $this->session->flashdata('message');?>

			<a onclick="window.location.href = '<?php echo base_url().'uploads/sample_excel_files/phrases.xls';?>';" class="btn btn-success" title="<?php if(isset($this->phrases["download sample excel file"])) echo $this->phrases["download sample excel file"]; else echo "Download Sample Excel File";?>"><?php if(isset($this->phrases["download sample file"])) echo $this->phrases["download sample file"]; else echo "Download Sample File";?> <i class="fa fa-download"></i></a>

				<?php $attributes = array('name'=>'excel_form','id'=>'excel_form');
                           echo form_open_multipart('settings/addPhrasesByExcel',$attributes);?>  

                        <div class="form-group">
							<input type="file" name="userfile" class="excel-file-up"/> 
							<?php echo form_error('userfile');?>
                        </div>

                        <div class="form-group">	  
							<button type="submit" class="btn btn-success"> <i class='fa fa-upload'> <?php echo (isset($this->phrases["upload and save"])) ? $this->phrases["upload and save"] : "Upload and Save";?></i></button>
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
              $("#excel_form").validate({
                  rules: {
                userfile: {
                          required: true,
						  extension: 'xls'
                      }
                  },

				messages: {
					userfile: "<?php if(isset($this->phrases["please upload .xls file"])) echo $this->phrases["please upload .xls file"]; else echo "Please upload .xls file";?>."
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
