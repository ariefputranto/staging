<div class="container">
  <div class="row">
    <?php echo $message;?>
	<div class="col-lg-12">
       
      
     <div class="tabbable tabs-left after-login">
        <ul class="nav nav-tabs">
		<?php
		$active_a = 'active';
		$active_b = '';
		$active_c = '';
		$active_d = '';
		switch($tab)
		{
			case 'b':
				$active_a = '';
				$active_b = 'active';
				$active_c = '';
				$active_d = '';
			break;
			case 'c':
				$active_a = '';
				$active_b = '';
				$active_c = 'active';
				$active_d = '';
			break;
			case 'd':
				$active_a = '';
				$active_b = '';
				$active_c = '';
				$active_d = 'active';
			break;
		}
		?>
		  <li class="<?php echo $active_a;?>"><a href="#a" data-toggle="tab"><i class="flaticon-gear"></i> <?php echo getPhrase('My Settings');?></a></li>
          <li class="<?php echo $active_b;?>"><a href="#b" data-toggle="tab"><i class="flaticon-business"></i> <?php echo getPhrase('Bookings');?></a></li>
          <!--<li class="<?php echo $active_c;?>"><a href="#c" data-toggle="tab"><i class="flaticon-close"></i> <?php echo getPhrase('Cancel Bookings');?></a></li>-->
	      <li class="<?php echo $active_d;?>"><a href="#d" data-toggle="tab"><i class="flaticon-commerce"></i> <?php echo getPhrase('Offers');?></a></li>
        </ul>
        <div class="tab-content">
        
         <div class="tab-pane <?php echo $active_a;?>" id="a">
 
 
<?php echo form_open('bookingseat/my_bookings', "id='my_bookings' name='my_bookings' enctype='multipart/form-data'");?>

<div class="form-group">
<label><?php echo getPhrase('First Name');?></label>
<?php
$val = '';
if(isset($_POST['buttProfile']))
	$val = $_POST['first_name'];
elseif(isset(getUserRec()->first_name))
	$val = getUserRec()->first_name;
?>
<input type="text" placeholder="<?php echo getPhrase('Enter Your First Name');?>" name="first_name" id="first_name" value="<?php echo $val;?>">
 
</div>

<div class="form-group">
<label><?php echo getPhrase('Last Name');?></label>
<?php
$val = '';
if(isset($_POST['buttProfile']))
	$val = $_POST['last_name'];
elseif(isset(getUserRec()->last_name))
	$val = getUserRec()->last_name;
?>
<input type="text" placeholder="<?php echo getPhrase('Enter Your Last Name');?>" name="last_name" id="last_name" value="<?php echo $val;?>">
 
</div>

<div class="form-group">
<label><?php echo getPhrase('Email Address');?>  </label>
<input type="text" placeholder="<?php echo getPhrase('Your Email');?>" value="<?php echo (isset(getUserRec()->email)) ? getUserRec()->email : '';?>" disabled>
 
</div>

<div class="form-group">
<label><?php echo getPhrase('Phone Number');?></label>
<?php
$val = '';
if(isset($_POST['buttProfile']))
	$val = $_POST['phone_code'];
elseif(isset(getUserRec()->phone_code))
	$val = getUserRec()->phone_code;
?>
<input type="text" placeholder="<?php echo getPhrase('Enter Your Phone Code');?>" name="phone_code" id="phone_code" value="<?php echo $val;?>">&nbsp;
<?php
$val = '';
if(isset($_POST['buttProfile']))
	$val = $_POST['phone'];
elseif(isset(getUserRec()->phone))
	$val = getUserRec()->phone;
?>
<input type="text" placeholder="<?php echo getPhrase('Enter Your Phone Number');?>" name="phone" id="phone" value="<?php echo $val;?>">
 
</div>

<div class="form-group">
<label><?php echo getPhrase('Date of Birth');?></label>
<?php
$val = '';
if(isset($_POST['buttProfile']))
	$val = $_POST['dob'];
elseif(isset(getUserRec()->dob))
	$val = getUserRec()->dob;
?>
<input type="text" placeholder="<?php echo getPhrase('Enter Your Date of Birth');?>" name="dob" id="dob" value="<?php echo $val;?>" class="calendar"> 
</div>

<div class="form-group">
<label><?php echo getPhrase('Gender');?></label>
<?php
$val = '';
if(isset($_POST['buttProfile']))
	$val = $_POST['gender'];
elseif(isset(getUserRec()->dob))
	$val = getUserRec()->gender;
$gender_male = ' checked';
$gender_female = '';
if($val == 'Female')
{
	$gender_male = '';
	$gender_female = ' checked';
}
?>
<input type="radio" name="gender" id="gender_male" value="Male"<?php echo $gender_male;?>> <label for="gender_male" class="side-label"><?php echo getPhrase('Male');?></label>
<input type="radio" name="gender" id="gender_female" value="Female"<?php echo $gender_female;?>> <label for="gender_female" class="side-label"><?php echo getPhrase('Female');?></label>
</div>

<div class="form-group">
<label><?php echo getPhrase('Address');?></label>
<?php
$val = '';
if(isset($_POST['buttProfile']))
	$val = $_POST['address'];
elseif(isset(getUserRec()->address))
	$val = getUserRec()->address;
?>
<textarea name="address" id="address"><?php echo $val;?></textarea>
 
</div>

<div class="form-group">
<label><?php echo getPhrase('Profile Pic');?></label>
<input type="file" name="photo" id="photo">
<?php
if(isset(getUserRec()->photo) && getUserRec()->photo != '' && file_exists('uploads/user_profile_pics/thumbs/' . getUserRec()->photo))
{
	echo '<img src="'.base_url().'uploads/user_profile_pics/thumbs/'.getUserRec()->photo.'" width="50" height="50">';
}
?>
</div>
 
<div class="form-group">
<input type="submit" name="buttProfile" id="buttProfile" value="<?php echo getPhrase('Save');?>" class="btn btn-default site-buttos">
</div>

 
 

 

<div class="clearfix"></div>
 
</form>
         </div>
         
         
         
         <div class="tab-pane <?php echo $active_b;?>" id="b">
               <table width="200" border="1">
               <thead>
	<tr>
      <th><?php echo getPhrase('Ticket Number');?></th>
      <th><?php echo getPhrase('From & To Address');?></th>
      <th><?php echo getPhrase('Date');?></th>
      <th><?php echo getPhrase('Price');?></th>
	  <th><?php echo getPhrase('Status');?></th>
    </tr>   
	</thead>      
    <tbody>
   <?php if(count($mybookings) > 0) {
	   foreach($mybookings as $booking)
	   {
	   ?>
     <tr>
      <td><?php echo $booking->booking_ref;?></td>
      <td><b><?php echo getPhrase('From')?></b> <?php echo $booking->pick_point;?> <b><?php echo getPhrase('To')?></b> <?php echo $booking->drop_point;?></td>
      <td><?php echo $booking->pick_date;?></td>
      <td><?php echo $this->config->item('site_settings')->currency_symbol . ' ' . $booking->cost_of_journey;?></td>
	  <td><?php echo $booking->booking_status;?></td>
    </tr>
	   <?php }} ?>
    
    
  </tbody>
</table>
         </div>
         
		 
<div class="tab-pane <?php echo $active_c;?>" id="c">
<div class="form-feilds">
<?php
echo form_open('bookingseat/my-bookings', "id='print_ticket_form' name='print_ticket_form' class=''");
$value = '';
if(isset($_POST['buttSearch']))
	$value = $this->input->post('booking_ref');
?>
<div class="form-group">
<label><?php echo getPhrase('Ticket Numer');?></label>
<input type="text" placeholder="<?php echo getPhrase('Enter Ticket Number');?>" name="booking_ref" id="booking_ref" value="<?php echo $value;?>"  required>
</div>

<div class="form-group">
<input type="submit" name="buttSearch" id="buttSearch" value="<?php echo getPhrase('Seacrh');?>" class="btn btn-default site-buttos">
</div>
<input type="hidden" name="tab" value="c">
</form>

<?php if(isset($ticket_details['status']) && $ticket_details['status'] == 1) { ?>
<div class="form-group clearfix">
	<?php echo getPhrase('Ticket Details');
	echo getPhrase('Pick-up Location') . ' : '.$ticket_details['details']->pick_point.'<br>';
	echo getPhrase('Drop-off Location') . ' : '.$ticket_details['details']->drop_point.'<br>';
	echo getPhrase('Journey Date') . ' : '.$ticket_details['details']->pick_date.'<br>';
	echo getPhrase('Cost of journey') . ' : '.$ticket_details['details']->cost_of_journey.'<br>';
	echo getPhrase('Seat') . ' : '.$ticket_details['details']->seat.'<br>';
	echo getPhrase('Vehicle') . ' : '.$ticket_details['details']->name.' ('.$ticket_details['details']->number_plate.')';
	?>	
</div>

<div class="form-group clearfix">
	<?php echo getPhrase('Please enter OPT you received on registered mobile number');?>
</div>

<?php
echo form_open('bookingseat/my-bookings', "id='print_ticket_form' name='print_ticket_form' class=''");
$value = '';
if(isset($_POST['buttSearch']))
	$value = $this->input->post('booking_ref');
?>
<div class="form-group clearfix" id="email_address_div">
<input type="text" name="otp" id="opt" required>
</div>
<input type="hidden" name="booking_ref" value="<?php echo $value;?>">

<div class="form-group">
<input type="submit" name="buttCancel" id="buttCancel" value="<?php echo getPhrase('Go');?>" class="btn btn-default site-buttos">
</div>
</form>

<?php } ?>
</div> 
</div>
          
       <div class="tab-pane <?php echo $active_d;?>" id="d">
       <div class="coupnLst clearfix">
          <ul class="clearfix">
             
             
             <?php if(count($offers) > 0) {
				 foreach($offers as $row)
				 {
					 $img_txt = "Great Offer";
                    if($row->offer_type == "percentage")
                      $img_txt = $row->offer_type_val."%";
                    elseif($row->offer_type == "amount")
                      $img_txt = $site_settings->currency_symbol.$row->offer_type_val;
				 ?>
            <li>
              <div class="hOt">HOT</div>
              <img src="<?php echo base_url();?>seat/images/bus.png" alt="" title="" class="couplistImg">
 
              <div class="rC clearfix">
                <div class="contentCoupn">
                  <h3><?php echo $row->title;?></h3>
                  <p class="more"><?php echo $row->description;?> </p>
                  <span>Ending on: <?php echo date('M d, Y', strtotime($row->expiry_date));?></span>
                </div>
                <div class="contentBtn">
                  <div class="btn couponButton">  <a href="#" data-toggle="modal" data-target="#myModal" onclick="getCodeModal('<?php echo $row->title;?>','<?php echo $row->code;?>','<?php echo $row->description;?>','<?php echo date('M d, Y', strtotime($row->expiry_date));?>');" ><?php echo $row->code;?></a> </div>
                  <div class="addiCon"> <small>Additional</small> <span> <?php echo $img_txt;?> Off </span> </div>
                </div>
               
              </div>
               
            </li>
				 <?php } } ?>
            
          </ul>
        
        </div>
           </div>
        </div>
      </div>
      <!-- /tabs -->
      
    </div>
      
    </div>
     
  </div>
  <script>
   function getCodeModal(title, code, desc, exp_date)
   {
      if(!title || !code || !desc)
        return;

      $('#title_txt').text(title);
      $('#code_txt').text(code);
      $('#desc_txt').text(desc);
      $('#expiry_date_txt').text("Offer Ended On: "+exp_date);

   }

   function copyToClipboard(element) {
      $(element).select();
      document.execCommand("copy");
    }

 </script>
 <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       
     
   <div class="modal-body clearfix">
       <div class="coupon_section">		
                <p class="firs-hed" id="title_txt"> </p>			
				 
				<div class="coupon_box">
				<span class="cupon-code cco" id="code_txt"></span>
								<button class="cupon-code cco1" id="cpy_code" onclick="copyToClipboard('#code_txt');">Copy Code</button>
			                    </div>				

			</div>
      <div align="center" id="expiry_date_txt"> </div>
				<div class="fancy_detail_section">
			 <span class="description_section" id="desc_txt"> </span>						
 			</div>
			
			      </div>
      
    </div>
  </div>
</div>