  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
  <div class="inner-elements">
        <div class="col-md-12">
		<?php echo $this->session->flashdata('message');
		$url = base_url().'settings/travelLocationCosts/add';
		if($site_theme == 'seat') $url = base_url().'settings/add_travel_location_costs';
		?>
		<a onclick="window.location.href = '<?php echo $url;?>';" class="btn btn-success" title="Add New"><?php if(isset($this->phrases["add new"])) echo $this->phrases["add new"]; else echo "Add New";?> <i class="fa fa-plus"></i></a>

		<?php if($site_theme == 'vehicle') { ?>
		<a class="btn btn-success up-excel"><?php 
            $attributes = array('name' => 'excel_form', 'id' => 'excel_form');
            echo form_open_multipart('settings/readExcel/travel_location_costs',$attributes);?>  
            <input type="file" name="userfile" class="excel-file-up"/> 
            <?php echo form_error('userfile');?>
			<button class="btn btn-danger excel-btn" type="submit" ><?php if(isset($this->phrases["add multiple by excel"])) echo $this->phrases["add multiple by excel"]; else echo "Add Multiple By Excel";?> <i class="fa fa-upload"></i></button>
         <?php echo form_close();?>
			</a>
		
		
		<a onclick="window.location.href = '<?php echo base_url().'uploads/sample_excel_files/travel_location_costs.xls';?>';" class="btn btn-success" title="<?php if(isset($this->phrases["download sample excel file"])) echo $this->phrases["download sample excel file"]; else echo "Download Sample Excel File";?>"><?php if(isset($this->phrases["download sample file"])) echo $this->phrases["download sample file"]; else echo "Download Sample File";?> <i class="fa fa-download"></i></a>
	<?php } ?>
		<br/><br/>
		<table id="example" class="cell-border " cellspacing="0" width="100%">
                   <thead>
                     <tr>
                          <th>#</th>
                          <th><?php if(isset($this->phrases["travel location"])) echo $this->phrases["travel location"]; else echo "Travel Location";?></th>
                          <th><?php if(isset($this->phrases["vehicle"])) echo $this->phrases["vehicle"]; else echo "Vehicle";?></th>
						  <th><?php if(isset($this->phrases["stop"])) echo $this->phrases["stop"]; else echo "Stop";?></th>
                          <th><?php if(isset($this->phrases["cost"])) echo $this->phrases["cost"]; else echo "Cost";?></th>
                          <th><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
                          <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </thead>
                  <tfoot>
                    <tr>
						  <th>#</th>
                          <th><?php if(isset($this->phrases["travel location"])) echo $this->phrases["travel location"]; else echo "Travel Location";?></th>
                          <th><?php if(isset($this->phrases["vehicle"])) echo $this->phrases["vehicle"]; else echo "Vehicle";?></th>
						  <th><?php if(isset($this->phrases["stop"])) echo $this->phrases["stop"]; else echo "Stop";?></th>
                          <th><?php if(isset($this->phrases["cost"])) echo $this->phrases["cost"]; else echo "Cost";?></th>
                          <th><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
                          <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </tfoot>
                      <tbody>

						<?php
//neatPrint($records);
							if(count($records) > 0) {

							  $i=1;
							  foreach($records as $row) {

						   ?>

						<tr>
						  <td><?php echo $i++;?></td>
						  <?php $to = (isset($this->phrases["to"])) ? $this->phrases["to"] : "To";?>
                          <td><?php echo $row->start_location." <code>".$to."</code> ".$row->end_location;?>&nbsp;&nbsp;&nbsp;
						  <?php echo $row->start_time." <code>".$to."</code> ".$row->destination_time;?>
						  </td>
						  
						  <td><?php echo $row->name;?> <?php echo $row->model;?><?php if($row->shuttle_no != '') { echo ' ('.$row->shuttle_no.')';}?></td>
						  
						  <td><?php echo $row->stop_over;?></td>						  
                          
                          <td>
						  <?php 
						  if($row->fare_details != '')
						  {
						  $fare_details = (array)json_decode($row->fare_details);
						  $prices = (isset($fare_details['fare'])) ? $fare_details['fare'] : array();
						  //print_r($prices);
						  echo min((array)$prices).' - ' . max((array)$prices);
						  } else {
						  echo $row->cost;
						  }
						  ?></td>
                         <td><?php echo (isset($this->phrases[$row->status])) ? $this->phrases[$row->status] : $row->status;?></td>

                          <td>
							<?php 
							$url = base_url().'settings/travelLocationCosts/edit/'.$row->id;
							if($site_theme == 'seat')
								$url = base_url().'settings/add_travel_location_costs/'.$row->id;
							?>
							<a onclick="window.location.href = '<?php echo $url;?>';" class="btn btn-success act-btn" title="<?php if(isset($this->phrases["edit"])) echo $this->phrases["edit"]; else echo "Edit";?>"><i class="fa fa-edit"></i> </a>

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
            <?php if(isset($this->phrases["are you sure to delete the record"])) echo $this->phrases["are you sure to delete the record"]; else echo "Are you sure to delete the Record";?>?
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

	var str = "<?php echo base_url();?>settings/travelLocationCosts/delete/"+x;
	document.getElementById("delete_no").setAttribute("href", str);

   }
   
</script>
