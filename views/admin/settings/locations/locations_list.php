  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
  <div class="inner-elements">
        <div class="col-md-12">
		<?php echo $this->session->flashdata('message');?>
		<a onclick="window.location.href = '<?php echo base_url().'settings/locations/add';?>';" class="btn btn-success" title="Add New"><?php if(isset($this->phrases["add new"])) echo $this->phrases["add new"]; else echo "Add New";?> <i class="fa fa-plus"></i></a>

		<a class="btn btn-success up-excel"><?php 
            $attributes = array('name' => 'excel_form', 'id' => 'excel_form');
            echo form_open_multipart('settings/readExcel/locations',$attributes);?>  
            <input type="file" name="userfile" class="excel-file-up"/> 
            <?php echo form_error('userfile');?>
			<button class="btn btn-danger excel-btn" type="submit" ><?php if(isset($this->phrases["add multiple by excel"])) echo $this->phrases["add multiple by excel"]; else echo "Add Multiple By Excel";?> <i class="fa fa-upload"></i></button>
         <?php echo form_close();?>
			</a>
		<a onclick="window.location.href = '<?php echo base_url().'uploads/sample_excel_files/locations.xls';?>';" class="btn btn-success" title="<?php if(isset($this->phrases["download sample excel file"])) echo $this->phrases["download sample excel file"]; else echo "Download Sample Excel File";?>"><?php if(isset($this->phrases["download sample file"])) echo $this->phrases["download sample file"]; else echo "Download Sample File";?> <i class="fa fa-download"></i></a>

		<br/><br/>
		<table id="example" class="cell-border example" cellspacing="0" width="100%">
                   <thead>
                     <tr>
                          <th>#</th>
                          <th><?php if(isset($this->phrases["location name (id)"])) echo $this->phrases["location name (id)"]; else echo "Location Name (Id)";?></th>
						  <th><?php echo getPhrase('Time Zone')?></th>
                          <th><?php if(isset($this->phrases["location visibility type"])) echo $this->phrases["location visibility type"]; else echo "Location Visibility Type";?></th>
                          <th><?php if(isset($this->phrases["is airport"])) echo $this->phrases["is airport"]; else echo "Is Airport";?></th>
                          <th><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
                          <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </thead>
                  <tfoot>
                    <tr>
                          <th>#</th>
                          <th><?php if(isset($this->phrases["location name (id)"])) echo $this->phrases["location name (id)"]; else echo "Location Name (Id)";?></th>
						  <th><?php echo getPhrase('Time Zone')?></th>
                          <th><?php if(isset($this->phrases["location visibility type"])) echo $this->phrases["location visibility type"]; else echo "Location Visibility Type";?></th>
                          <th><?php if(isset($this->phrases["is airport"])) echo $this->phrases["is airport"]; else echo "Is Airport";?></th>
                          <th><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
                          <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </tfoot>
                      <tbody>

						<?php

							if(count($records) > 0) {

							  $i=1;
							  foreach($records as $row) {

						   ?>

						<tr>
						  <td><?php echo $i++;?></td>
                          <td><?php echo $row->location." (".$row->id.")";?></td>
						  <td><?php echo $row->location_time_zone;?></td>
                          <td><?php echo ucwords($row->location_visibility_type);?></td>
                          <td>
							  <?php if($row->is_airport == 0) echo (isset($this->phrases['no'])) ? $this->phrases['no'] : "No"; elseif($row->is_airport == 1) echo (isset($this->phrases['yes'])) ? $this->phrases['yes'] : "Yes";?>
						  </td>
                          <td><?php echo (isset($this->phrases[$row->status])) ? $this->phrases[$row->status] : $row->status;?></td>

                          <td>
							<a onclick="window.location.href = '<?php echo base_url().'settings/locations/edit/'.$row->id;?>';" class="btn btn-success act-btn" title="<?php if(isset($this->phrases["edit"])) echo $this->phrases["edit"]; else echo "Edit";?>"><i class="fa fa-edit"></i> </a>

							<a data-toggle="modal" data-target="#delRecModal"  onclick="deleteMessage(<?php echo $row->id;?>)" class="btn btn-danger act-btn" title="<?php if(isset($this->phrases["delete"])) echo $this->phrases["delete"]; else echo "Delete";?>"><i class="fa fa-trash"></i> </a>
							</td>
                        </tr>

						<?php } } ?>

                  </tbody>
                    </table>
        </div>

        
      </div>
      </div>
    </div>
  </div>
</section>


<!-- modal fade -->
<div class="modal fade" id="delRecModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php if(isset($this->phrases["close"])) echo $this->phrases["close"]; else echo "Close";?></span></button>
            <h4 class="modal-title" id="myModalLabel"><?php if(isset($this->phrases["delete record"])) echo $this->phrases["delete record"]; else echo "Delete Record";?></h4>
         </div>
         <div class="modal-body">
            <?php if(isset($this->phrases["travel locations and costs associated with this location will be deleted. are you sure to delete the record"])) echo $this->phrases["travel locations and costs associated with this location will be deleted. are you sure to delete the record"]; else echo "Travel Locations and Costs associated with this Location will be deleted. Are you sure to delete the Record";?>?
         </div>
         <div class="modal-footer">
            <a type="button" class="btn btn-default modal-btn" id="delete_no" href=""><?php if(isset($this->phrases["yes"])) echo $this->phrases["yes"]; else echo "Yes";?></a>
            <a type="button" class="btn btn-default modal-btn" data-dismiss="modal"><?php if(isset($this->phrases["no"])) echo $this->phrases["no"]; else echo "No";?></a>
         </div>
      </div>
   </div>
</div>


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


<script>
/****** Delete Message ******/
   function deleteMessage(x){

	var str = "<?php echo base_url();?>settings/locations/delete/"+x;
	document.getElementById("delete_no").setAttribute("href", str);

   }
   
</script>
