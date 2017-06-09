  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
  <div class="inner-elements">
        <div class="col-md-12">
		<?php echo $this->session->flashdata('message');?>
		<a onclick="window.location.href = '<?php echo base_url().'admin/offers/create';?>';" class="btn btn-success" title="Create New"><?php if(!empty($this->phrases["create new"])) echo $this->phrases["create new"]; else echo "Create New";?> <i class="fa fa-plus"></i></a>

		<br/><br/>
		<table id="example" class="cell-border example" cellspacing="0" width="100%">
                   <thead>
                     <tr>
                          <th>#</th>
                          <th><?php if(!empty($this->phrases["title"])) echo $this->phrases["title"]; else echo "Title";?></th>
                          <th><?php if(!empty($this->phrases["offer type"])) echo $this->phrases["offer type"]; else echo "Offer Type";?></th>
                          <th><?php if(!empty($this->phrases["code"])) echo $this->phrases["code"]; else echo "Code";?></th>
                          <th><?php if(!empty($this->phrases["usage type"])) echo $this->phrases["usage type"]; else echo "Usage Type";?></th>
                          <th><?php if(!empty($this->phrases["expiry date"])) echo $this->phrases["expiry date"]; else echo "Expiry Date";?></th>
                           <th><?php if(!empty($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
                          <th><?php if(!empty($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </thead>
                  <tfoot>
                    <tr>
                          <th>#</th>
                          <th><?php if(!empty($this->phrases["title"])) echo $this->phrases["title"]; else echo "Title";?></th>
                          <th><?php if(!empty($this->phrases["offer type"])) echo $this->phrases["offer type"]; else echo "Offer Type";?></th>
                          <th><?php if(!empty($this->phrases["code"])) echo $this->phrases["code"]; else echo "Code";?></th>
                          <th><?php if(!empty($this->phrases["usage type"])) echo $this->phrases["usage type"]; else echo "Usage Type";?></th>
                          <th><?php if(!empty($this->phrases["expiry date"])) echo $this->phrases["expiry date"]; else echo "Expiry Date";?></th>
                           <th><?php if(!empty($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
                          <th><?php if(!empty($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
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
                          <td><?php echo $row->title;?></td>
                          <td><?php echo ucfirst($row->offer_type)." (".$row->offer_type_val.")";?></td>
                          <td><?php echo $row->code;?></td>
                          <td><?php echo humanize($row->usage_type)." (".$row->usage_type_val.")";?></td>
                          <td><?php echo $row->expiry_date;?></td>
                          <td><?php echo $row->status;?></td>

                          <td>
              							<a onclick="window.location.href = '<?php echo base_url().'admin/offers/edit/'.$row->offer_id;?>';" class="btn btn-success act-btn" title="<?php if(!empty($this->phrases["edit"])) echo $this->phrases["edit"]; else echo "Edit";?>"><i class="fa fa-edit"></i> </a>

              							<a data-toggle="modal" data-target="#delRecModal"  onclick="deleteMessage(<?php echo $row->offer_id;?>)" class="btn btn-danger act-btn" title="<?php if(!empty($this->phrases["delete"])) echo $this->phrases["delete"]; else echo "Delete";?>"><i class="fa fa-trash"></i> </a>
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
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php if(!empty($this->phrases["close"])) echo $this->phrases["close"]; else echo "Close";?></span></button>
            <h4 class="modal-title" id="myModalLabel"><?php if(!empty($this->phrases["delete record"])) echo $this->phrases["delete record"]; else echo "Delete Record";?></h4>
         </div>
         <div class="modal-body">
            <?php if(!empty($this->phrases["are you sure to delete the record"])) echo $this->phrases["are you sure to delete the record"]; else echo "Are you sure to delete the Record";?>?
         </div>
         <div class="modal-footer">
            <a type="button" class="btn btn-default modal-btn" id="delete_no" href=""><?php if(!empty($this->phrases["yes"])) echo $this->phrases["yes"]; else echo "Yes";?></a>
            <a type="button" class="btn btn-default modal-btn" data-dismiss="modal"><?php if(!empty($this->phrases["no"])) echo $this->phrases["no"]; else echo "No";?></a>
         </div>
      </div>
   </div>
</div>



<script>
/****** Delete Message ******/
   function deleteMessage(x){

	var str = "<?php echo base_url();?>admin/offers/delete/"+x;
	document.getElementById("delete_no").setAttribute("href", str);

   }
   
</script>
