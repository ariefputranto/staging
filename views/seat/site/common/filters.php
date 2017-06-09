<section class="filter">
<div class="container">
<div class="row">
<div class="col-lg-12">
<table width="200" border="1">
  <tbody>
    <tr>
      <td><span class="name"> Filter by </span> </td>
      <td> 
     <div class="button-group">
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><span class="flaticon-transport-3"></span> Shuttle Types <span class="fa fa-angle-down"></span></button>
<ul class="dropdown-menu">
  <?php foreach($shuttle_types as $shuttle_type) { ?>
  <li><a href="javascript:void(0);" class="small" data-value="<?php echo $shuttle_type->id;?>" tabIndex="-1"> <input id="checkbox-<?php echo $shuttle_type->id;?>" class="checkbox-custom shuttle_type_checkbox" name="shuttle_type" type="checkbox" value="<?php echo $shuttle_type->category;?>" onclick="filter_records(this.value, 'shuttle_type')">
	<label for="checkbox-<?php echo $shuttle_type->id;?>" class="checkbox-custom-label searchCheck blueCheckbox"> </label>&nbsp;<?php echo $shuttle_type->category;?></a></li>
  <?php } ?>
</ul>
  </div>
 </td>
      <td>
     <div class="button-group">
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><span class="flaticon-location"></span>  Boarding <span class="fa fa-angle-down"></span></button>
<ul class="dropdown-menu">
  <li><a href="#" class="small" data-value="option1" tabIndex="-1"> <input id="checkbox-5" class="checkbox-custom" name="checkbox-5" type="checkbox">
	<label for="checkbox-5" class="checkbox-custom-label searchCheck blueCheckbox"> </label>&nbsp;Option 1</a></li>
  <li><a href="#" class="small" data-value="option2" tabIndex="-1"> <input id="checkbox-6" class="checkbox-custom" name="checkbox-6" type="checkbox">
	<label for="checkbox-6" class="checkbox-custom-label searchCheck blueCheckbox"> </label>&nbsp;Option 2</a></li>
  <li><a href="#" class="small" data-value="option3" tabIndex="-1"> <input id="checkbox-7" class="checkbox-custom" name="checkbox-7" type="checkbox">
	<label for="checkbox-7" class="checkbox-custom-label searchCheck blueCheckbox"> </label>&nbsp;Option 3</a></li> 
</ul>
  </div>
      </td>
      <td>
      <div class="button-group">
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><span class="flaticon-location"></span> Dropping <span class="fa fa-angle-down"></span></button>
<ul class="dropdown-menu">
  <li><a href="#" class="small" data-value="option1" tabIndex="-1"> <input id="checkbox-8" class="checkbox-custom" name="checkbox-8" type="checkbox">
	<label for="checkbox-8" class="checkbox-custom-label searchCheck blueCheckbox"> </label>&nbsp;Option 1</a></li>
  <li><a href="#" class="small" data-value="option2" tabIndex="-1"> <input id="checkbox-9" class="checkbox-custom" name="checkbox-9" type="checkbox">
	<label for="checkbox-9" class="checkbox-custom-label searchCheck blueCheckbox"> </label>&nbsp;Option 2</a></li>
  <li><a href="#" class="small" data-value="option3" tabIndex="-1"> <input id="checkbox-10" class="checkbox-custom" name="checkbox-10" type="checkbox">
	<label for="checkbox-10" class="checkbox-custom-label searchCheck blueCheckbox"> </label>&nbsp;Option 3</a></li> 
</ul>
  </div>
      </td>
	  <td>
 <div class="button-group">
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><span class="flaticon-favorite"></span> Ratings <span class="fa fa-angle-down"></span></button>
<ul class="dropdown-menu">
  <li><a href="#" class="small" data-value="option1" tabIndex="-1"> <input id="checkbox-11" class="checkbox-custom" name="checkbox-11" type="checkbox">
	<label for="checkbox-11" class="checkbox-custom-label searchCheck blueCheckbox"> </label>&nbsp;Option 1</a></li>
  <li><a href="#" class="small" data-value="option2" tabIndex="-1"> <input id="checkbox-12" class="checkbox-custom" name="checkbox-12" type="checkbox">
	<label for="checkbox-12" class="checkbox-custom-label searchCheck blueCheckbox"> </label>&nbsp;Option 2</a></li>
  <li><a href="#" class="small" data-value="option3" tabIndex="-1"> <input id="checkbox-13" class="checkbox-custom" name="checkbox-13" type="checkbox">
	<label for="checkbox-13" class="checkbox-custom-label searchCheck blueCheckbox"> </label>&nbsp;Option 3</a></li> 
</ul>
  </div>
      </td>
    </tr>
 
  </tbody>
</table>

</div>
</div>
</div>
</section>

<section class="after-filter">
<div class="container">
<div class="row">
<div class="col-lg-12">
<span class="name">Active Filters</span> <div class="btn btn-default">Non AC <i class="flaticon-close-1"></i></div> <div class="btn btn-default">AC No <i class="flaticon-close-1"></i></div>
</div>
</div>
</div>
</section>

<script>
function filter_records(val, type)
{
	if(type == 'shuttle_type')
	{
		var values = [];
		var ind = 0;
		$( ".shuttle_type_checkbox" ).each(function( index ) {
			if($(this).is(':checked')) { values[ind++] = $(this).val(); }				
		});
		$('.vehicle').hide();
		$( ".shuttle_type" ).each(function( index, shuttle_type ) {		  
			for(var i = 0; i < values.length; i++)
			{
				if($( this ).text().toLowerCase().contains(values[i].toLowerCase())) {
					  $( this ).closest('li').show();
				  }
			}
		});
	}
}
</script>