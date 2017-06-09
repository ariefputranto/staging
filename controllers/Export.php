<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends MY_Controller

{
	/*
	| -----------------------------------------------------
	| PRODUCT NAME: 	Point to Point System (DJ)
	| -----------------------------------------------------
	| AUTHER:			DIGITAL VIDHYA TEAM
	| -----------------------------------------------------
	| EMAIL:			digitalvidhya4u@gmail.com
	| -----------------------------------------------------
	| COPYRIGHTS:		RESERVED BY DIGITAL VIDHYA
	| -----------------------------------------------------
	| WEBSITE:			http://digitalvidhya.com
	|                   http://codecanyon.net/user/digitalvidhya
	| -----------------------------------------------------
	|
	| MODULE: 			Export
	| -----------------------------------------------------
	| This is export module controller file.
	| -----------------------------------------------------
	*/
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		redirect('admin', 'refresh');
	}


	// This is to Export Table
	function exportExcel($param = '')
	{
		$selected_cols 							= "*";

		$param = ($this->input->post('table_name')) ? $this->input->post('table_name') : $param;

		if($param != "" && in_array($param, array('users', 'locations', 'travel_locations', 'travel_location_costs', 'bookings'))) {

		$table_name 							= DBPREFIX.$param;

		$columns = $this->base_model->run_query("SELECT COLUMN_NAME 
											FROM INFORMATION_SCHEMA.COLUMNS
											WHERE table_schema = '".$this->db->database."'
											  AND table_name = '".$table_name."'
											");

		include (FCPATH .$this->data['site_theme'].'/'. '/assets/excelassets/PHPExcel.php');

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator('Maarten Balliauw')->setLastModifiedBy('Maarten Balliauw')->setTitle('
		PHPExcel Test Document')->setSubject('PHPExcel Test Document')->setDescription('
		Test document for PHPExcel, generated using PHP classes.')->setKeywords('
		office PHPExcel php')->setCategory('Test result file');

		// Create the worksheet
		// echo date('H:i:s').' Add data'.EOL;

		$objPHPExcel->setActiveSheetIndex(0);

		$i 										= 0;
		$row_no									= 1;
		$dta_index = array();

		if(count($columns) > 0) {

			$min = ord("A"); // ord returns the ASCII value of the first character of string.
			$max = $min + count($columns);
			$firstChar = ""; // Initialize the First Character
			$abc = $min;   // Initialize our alphabetical counter
			for($j = $min; $j<= $max; ++$j)
			{
				$col = $firstChar.chr($abc);   // This is the Column Label.
				$last_char = substr($col, -1);
				if ($last_char > "Z") // At the end of the alphabet. Time to Increment the first column letter.
				{
				$abc = $min; // Start Over
				if ($firstChar == "") // Deal with the first time.
				$firstChar = "A";
				else
				{
				$fchrOrd = ord($firstChar);// Get the value of the first character
				$fchrOrd++; // Move to the next one.
				$firstChar = chr($fchrOrd); // Reset the first character.
				}
				$col = $firstChar.chr($abc); // This is the column identifier
				}
				/*
				Use the $col here.
				*/
				$dta_index[]=$col.$row_no;

				$abc++; // Move on to the next letter
			}

		foreach($columns as $column) {

			$objPHPExcel->getActiveSheet()->setCellValue($dta_index[$i++] , ucwords(humanize($column->COLUMN_NAME)));

		}

		$this->db->select($selected_cols);
		$dta 									= $this->db->get($table_name)->result_array();
		$dataArray 								= $dta;

		$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');

		// Set title row bold
		// echo date('H:i:s').' Set title row bold'.EOL;

		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$dta_index[count($dta_index)-1])->getFont()->setBold(true);

		// Always include the complete filter range!
		// Excel does support setting only the caption
		// row, but that's not a best practise...

		$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet

		$objPHPExcel->setActiveSheetIndex(0);

		// Save Excel 2007 file
		// echo date('H:i:s') , " Write to Excel2007 format" , EOL;

		$callStartTime 							= microtime(true);
		$fname 									= $table_name . ".xls";
		$name 									= FCPATH .$this->data['site_theme'].'/'. "assets/exceldownloads/" . $fname;

		$objWriter 								= PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		// $objWriter->save(str_replace('.php', '.xlsx', __FILE__));

		$objWriter->save(str_replace('.php', '.xls', $name));

		// DOWNLOAD CREATED FILE

		$this->load->helper('download');
		$data 									= file_get_contents($name);; // Read the file's contents
		$name 									= $fname;
		force_download($name, $data);
		echo "done " . $name;

		} else {

			$this->prepare_flashmessage((isset($this->phrases["invalid request."])) ? $this->phrases["invalid request."] : "Invalid Request.", 1);
			redirect('admin/siteBackup');
		}
	  } else {

			$this->prepare_flashmessage((isset($this->phrases["no table found."])) ? $this->phrases["no table found."] : "No Table Found.", 1);
			redirect('admin/siteBackup');
	  }

	}


	
	
	
	
}
