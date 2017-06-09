  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
  <div class="inner-elements">
        <div class="col-md-12">
		<?php echo $this->session->flashdata('message');?>
		<a onclick="window.location.href = '<?php echo base_url().'managedriver/create_account';?>';" class="btn btn-success" title="<?php if(isset($this->phrases["add new"])) echo $this->phrases["add new"]; else echo "Add New";?>"><?php if(isset($this->phrases["Add Driver"])) echo $this->phrases["Add Driver"]; else echo "Add Driver";?> <i class="fa fa-plus"></i></a>

		<br/><br/>
		<table id="example1" class="cell-border " cellspacing="0" width="100%">
                   <thead>
                     <tr> 
                          <th>#</th>
                          <th><?php if(isset($this->phrases["username"])) echo $this->phrases["username"]; else echo "Username";?></th>
                          <th><?php if(isset($this->phrases["email"])) echo $this->phrases["email"]; else echo "Email";?></th>
                          <th><?php if(isset($this->phrases["phone"])) echo $this->phrases["phone"]; else echo "Phone";?></th>
                          <th><?php if(isset($this->phrases["user type"])) echo $this->phrases["user type"]; else echo "User Type";?></th>
                          <th><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
                          <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </thead>
                  <tfoot>
                    <tr>
                          <th>#</th>
                          <th><?php if(isset($this->phrases["username"])) echo $this->phrases["username"]; else echo "Username";?></th>
                          <th><?php if(isset($this->phrases["email"])) echo $this->phrases["email"]; else echo "Email";?></th>
                          <th><?php if(isset($this->phrases["phone"])) echo $this->phrases["phone"]; else echo "Phone";?></th>
                          <th><?php if(isset($this->phrases["user type"])) echo $this->phrases["user type"]; else echo "User Type";?></th>
                          <th><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
                          <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </tfoot>
                      <tbody>

						<?php

							if(count($records) > 0) {
								if($page == 0)
							  $i = ($page*PER_PAGE)+1;
						  else
							  $i = (($page*PER_PAGE)-PER_PAGE)+1;
							  foreach($records as $row) {

						   ?>

						<tr>
						  <td><?php echo $i++;?></td>
                          <td><?php echo $row->username;?></td>
                          <td><?php echo $row->email;?></td>
                          <td><?php echo $row->phone;?></td>
                          <td><?php if($row->group_id == 2) echo (isset($this->phrases["client"])) ? $this->phrases["client"] : "Client"; elseif($row->group_id == 3) echo (isset($this->phrases["executive"])) ? $this->phrases["executive"] : "Executive"; elseif($row->group_id == 4) echo "B2B User"; elseif($row->group_id == 5) echo "Supplier"; elseif($row->group_id == 6) echo "Driver";?></td>

                          <?php

								$title1	 =  (isset($this->phrases["click to make inactive"])) ? $this->phrases["click to make inactive"] : "Click to make Inactive";
								$action1 =  (isset($this->phrases["active"])) ? $this->phrases["active"] : "Active";
								$title2  =  (isset($this->phrases["click to make active"])) ? $this->phrases["click to make active"] : "Click to make Inactive";
								$action2 =  (isset($this->phrases["inactive"])) ? $this->phrases["inactive"] : "Inactive";

                          ?>
                          
                          <td><?php echo ($row->active == 1) ? '<a data-toggle="modal" data-target="#statusModal"  onclick="changeStatusMessage(0, '.$row->id.')" title="'.$title1.'" class="btn btn-success stat-padd">'.$action1.'</a>' : '<a data-toggle="modal" data-target="#statusModal"  onclick="changeStatusMessage(1, '.$row->id.')" title="'.$title2.'" class="btn btn-danger stat-padd">'.$action2.'</a>';?></td>

                          <td>
							<a onclick="window.location.href = '<?php echo base_url().'managedriver/edit_driver/'.$row->id;?>';" class="btn btn-success act-btn" title="<?php if(isset($this->phrases["edit"])) echo $this->phrases["edit"]; else echo "Edit";?>"><i class="fa fa-edit"></i> </a>
							
							<!--<a onclick="window.location.href = '<?php echo base_url().'managedriver/change_driver/'.$row->id;?>';" class="btn btn-warning act-btn" title="<?php if(isset($this->phrases["Change Schedule"])) echo $this->phrases["Change Schedule"]; else echo "Change Schedule";?>"><i class="fa fa-cog"></i> </a>-->

							<a data-toggle="modal" data-target="#delRecModal"  onclick="deleteMessage(<?php echo $row->id;?>,<?php echo $page;?>)" class="btn btn-danger act-btn" title="<?php if(isset($this->phrases["delete"])) echo $this->phrases["delete"]; else echo "Delete";?>"><i class="fa fa-trash"></i> </a>
							</td>
                        </tr>

						<?php } } ?>
<tr><td colspan="7"><?php echo $page_links;?></td></tr>
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

<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php if(isset($this->phrases["close"])) echo $this->phrases["close"]; else echo "Close";?></span></button>
            <h4 class="modal-title" id="myModalLabel"><?php if(isset($this->phrases["change status"])) echo $this->phrases["change status"]; else echo "Change Status";?></h4>
         </div>
         <div class="modal-body">
            <?php if(isset($this->phrases["are you sure to change the status"])) echo $this->phrases["are you sure to change the status"]; else echo "Are you sure to change the status";?>?
         </div>
         <div class="modal-footer">
            <a type="button" class="btn btn-default modal-btn" id="stat" href=""><?php if(isset($this->phrases["yes"])) echo $this->phrases["yes"]; else echo "Yes";?></a>
            <a type="button" class="btn btn-default modal-btn" data-dismiss="modal"><?php if(isset($this->phrases["no"])) echo $this->phrases["no"]; else echo "No";?></a>
         </div>
      </div>
   </div>
</div>



<script>
	/****** Delete Message ******/
   function deleteMessage(x,page){

	var str = "<?php echo base_url();?>managedriver/index/delete/"+x+"/"+page;
	document.getElementById("delete_no").setAttribute("href", str);

   }
   
	/****** Change Status Message ******/
   function changeStatusMessage(x,y){

	var str = "<?php echo base_url();?>managedriver/index/"+x+"/"+y;
	document.getElementById("stat").setAttribute("href", str);

   }
   
</script>
