<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mydb extends MY_Controller
{
	/*
	| -----------------------------------------------------
	| PRODUCT NAME: 	DIGI Point to Point Transfers
	| -----------------------------------------------------
	| AUTHOR:			DIGITAL VIDHYA TEAM
	| -----------------------------------------------------
	| EMAIL:			digitalvidhya4u@gmail.com
	| -----------------------------------------------------
	| COPYRIGHTS:		RESERVED BY DIGITAL VIDHYA
	| -----------------------------------------------------
	| WEBSITE:			http://digitalvidhya.com
	|                   http://codecanyon.net/user/digitalvidhya
	| -----------------------------------------------------
	|
	| MODULE: 			Booking
	| -----------------------------------------------------
	| This is Booking module controller file.
	| -----------------------------------------------------
	*/
	var $selected_db;
	public function __construct()
	{
		parent::__construct();

		// To use site_url and redirect on this controller.
		$this->load->helper('url');
		$this->load->library('session');
		$this->form_validation->set_error_delimiters(
		$this->config->item('error_start_delimiter', 'ion_auth'), 
		$this->config->item('error_end_delimiter', 'ion_auth')
		);
		$this->selected_db = $this->session->userdata('selected_db');
		if($this->selected_db == NULL) 
			$this->selected_db = $this->db->database;
		$this->db->query('USE '.$this->selected_db);
	}


	/****** Journey, Date & Time ******/
	function index($table = '', $column = '', $value = '')
	{
		
		$message = $this->session->flashdata('message');
		if($table == '')
		{
		$query = 'SHOW TABLES IN '.$this->selected_db;
		$result = $this->db->query($query)->result();
		echo $this->menu();
		echo '<pre>';
		//print_r($result);die();
		$html = '<table>';
		$html .= '<tr><td colspan="2" align="center">'.$message.'</td></tr>';
		
		$html .= '<tr><td>Name</td><td>Rows</td><td>Function</td></tr>';
		$colname = 'Tables_in_'.$this->selected_db;
		foreach($result as $key => $val)
		{
			$rcount = $this->db->query('SELECT count(*) as rcount FROM `'.$val->$colname.'`')->result();
			//print_r($rcount);
			$html.= '<tr>';
			$html .= '<td><a href="'.base_url().'mydb/index/'.$val->$colname.'" target="_target">'.$val->$colname.'</a></td>';
			$html .= '<td>'.$rcount[0]->rcount.'</td>';
			$html .= '<td><a href="'.base_url().'mydb/show_structure/'.$val->$colname.'" target="_target">Structure</a>
			
			&nbsp;<a href="'.base_url().'mydb/delete_table/'.$val->$colname.'" onclick="return confirm(\'Are you sure?\')">Del</a>
			
			&nbsp;<a href="'.base_url().'mydb/truncate_table/'.$val->$colname.'" onclick="return confirm(\'Are you sure?\')">Truncate</a>
			</td>';
			$html .= '</tr>';
		}
		$html .= '</table>';
		echo $html;
		}
		else
		{
			if($column != '' && $value != '')
			{
				$query = 'DELETE FROM '.$table.' WHERE '.$column.' = '.$value;
				$this->db->query($query);
				redirect('mydb/index/'.$table);
			}
			$query = 'SELECT * FROM '.$table.' ORDER BY 1 DESC LIMIT 10';
			$result = $this->db->query($query)->result();
			
			$html = '<table>';
			$html .= '<tr>';
			$html .= '<td>Function</td>';
			$fields = $this->db->list_fields($table);
			$id = ''; $i = 0;
			foreach ($fields as $field)
			{
			   if($i == 0) $id = $field;
			   $html .= '<td>'.$field.'</td>';
			   $i++;
			}
			$html .= '</tr>';
			
			$j = 0;
			foreach($result as $key => $val)
			{
				$html.= '<tr>';
				$html .= '<td><a href="'.base_url().'mydb/index/'.$table.'/'.$id.'/'.$val->$id.'" onclick="return confirm(\'Are you sure?\')">Del</a>&nbsp;<a href="'.base_url().'mydb/edit_record/'.$table.'/'.$id.'/'.$val->$id.'">Edit</a></td>';
				foreach ($fields as $field)
				{
				   $html .= '<td>'.$val->$field.'</td>';
				}
				$html.= '</tr>';
				$j++;
			}
			if($j == 0)
			{
				$html .= '<tr><td colspan="'.$i.'" align="center"><b>No Records Found</b></td></tr>';
			}
			$html .= '</table>';
			echo $this->menu();
			echo $html;
		}
	}
	
	function show_databases()
	{
		$result = $this->db->query('show databases')->result();
		echo $this->menu();
		$html = '<table>';
		$html .= '<tr><td><b>Name</b></td><td><b>Functions</b></td></tr>';
		foreach($result as $key => $val)
		{
			$html .= '<tr><td>'.$val->Database.'</td><td><a href="'.base_url().'mydb/delete_database/'.$val->Database.'" onclick="return confirm(\'Are you sure?\')" title="Delete">Del</a>
			
			&nbsp;<a href="'.base_url().'mydb/truncate_database/'.$val->Database.'" onclick="return confirm(\'Are you sure?\')" title="Truncate all data">Truncate</a>
			
			&nbsp;<a href="'.base_url().'mydb/empty_database/'.$val->Database.'" onclick="return confirm(\'Are you sure?\')" title="Delete all data">Empty</a>
			
			&nbsp;<a href="'.base_url().'mydb/show_tables/'.$val->Database.'" title="Show all tables">Show Tables</a>
			</td></tr>';
		}
		$html .= '</table>';
		//neatPrint($result);
		echo $html;
	}
	
	function show_tables($database_name)
	{
		if(empty($database_name))
		{
			$this->session->set_flashdata('message', 'Please select database');
			redirect('mydb/show_databases');
		}
		$this->session->set_userdata(array('selected_db' => $database_name));
		$this->session->set_flashdata('message', 'Database changed successfully');
		redirect('mydb/index');
	}
	
	function delete_database($database_name)
	{
		if(empty($database_name))
		{
			$this->session->set_flashdata('message', 'Please select database');
			redirect('mydb/show_databases');
		}
		$query = 'DROP DATABASE '.$database_name;
		$this->db->query($query);
		$this->session->set_flashdata('message', 'Database deleted successfully');
		redirect('mydb/show_databases');
	}
	
	function truncate_database($database_name)
	{
		if(empty($database_name))
		{
			$this->session->set_flashdata('message', 'Please select database');
			redirect('mydb/show_databases');
		}
		$query = 'SHOW TABLES IN '.$database_name;
		$result = $this->db->query($query)->result();
		foreach($result as $key => $val)
		{
			$this->db->query('TRUNCATE TABLE '.$val->$database_name);
		}
		$this->session->set_flashdata('message', 'Database truncated successfully');
		redirect('mydb/show_databases');
	}
	
	function empty_database($database_name)
	{
		if(empty($database_name))
		{
			$this->session->set_flashdata('message', 'Please select database');
			redirect('mydb/show_databases');
		}
		$query = 'SHOW TABLES IN '.$database_name;
		$result = $this->db->query($query)->result();
		foreach($result as $key => $val)
		{
			$this->db->query('DELETE TABLE FROM '.$val->$database_name);
		}
		$this->session->set_flashdata('message', 'Database tables deleted successfully');
		redirect('mydb/show_databases');
	}
	
	function delete_table($table)
	{
		if(empty($table))
		{
			$this->session->set_flashdata('message', 'Please select table');
			redirect('mydb/index');
		}
		$query = 'DELETE TABLE '.$table;
		$this->db->query($query);
		$this->session->set_flashdata('message', 'Table deleted successfully');
		redirect('mydb/index');
	}
	
	function truncate_table($table)
	{
		if(empty($table))
		{
			$this->session->set_flashdata('message', 'Please select table');
			redirect('mydb/index');
		}
		$query = 'TRUNCATE TABLE '.$table;
		$this->db->query($query);
		$this->session->set_flashdata('message', 'Table data cleared successfully');
		redirect('mydb/index');
	}
	
	function query_editor()
	{
		$this->data['message'] = $this->session->flashdata('message');
		$query = '';
		$result = array();
		if(isset($_POST['run']))
		{
			$this->form_validation->set_rules('query', 'Query','trim|required');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			if($this->form_validation->run() == TRUE)
			{
				$query = $this->input->post('query');
				$query2 = $query;
				$start = $this->input->post('start');
				$limit = $this->input->post('limit');
				if($start == 0 && $limit == 'last')
				{
					
				}
				else
				{
					$query2 = $query . ' LIMIT '.$start.','.$limit;
				}
				$result = $this->db->query($query2)->result();
				if(empty($result))
				{
					echo '<font color="red">No records found</font>';
				}
				else
				{
					
				}
			}
			else
			{
				$this->data['message'] = validation_errors();
			}
		}
		echo $this->menu();
		echo form_open('');
		echo '<table>';
		echo '<tr><td colspan="2"><font color="red">'.$this->data['message'].'</font></td></tr>';
		$start_opts = '<select name="start">';
		for($i = 0; $i<500; $i++)
			$start_opts .= '<option value="'.$i.'">'.$i.'</option>';
		$start_opts .= '</select>';
		
		$last_opts = '<select name="limit">';
		for($i = 10; $i<500; $i++)
			$last_opts .= '<option value="'.$i.'">'.$i.'</option>';
		$last_opts .= '<option value="last">Last</option>';
		$last_opts .= '</select>';
		echo '<tr><td>Start '.$start_opts.'</td><td>Limit '.$last_opts.'</td></tr>';
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
		echo '<tr><td colspan="2"><b>Query</b></td></tr>';
		echo '<tr><td colspan="2"><textarea name="query" rows="10" cols="100">'.$query.'</textarea></td></tr>';
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
		echo '<tr><td colspan="2"><input type="submit" name="run" value="Run Query"></td></tr>';
		echo '</table>';
		echo '</form>';
		
		if(!empty($result))
		{
			echo '<pre>';
			print_r($result);
		}		
	}
	
	function show_structure($table, $operation = '', $param = '')
	{
		if(empty($table))
		{
			$this->session->set_flashdata('message', 'Please select table');
			redirect('mydb/index');
		}
		$message = $this->session->flashdata('message');
		if($operation != '')
		{
			$message = '';
			switch($operation)
			{
				case 'del':
					$query = 'ALTER TABLE '.$table.' DROP '.$param;
					$this->db->query($query);
					$message = 'Field deleted successfully';
					break;
			}
			$this->session->set_flashdata('message', $message);
			redirect('mydb/show_structure/'.$table);
		}
		$query = 'DESCRIBE '.$table;
		$result = $this->db->query($query)->result();
		echo $this->menu();
		if(!empty($result))
		{
			if($message != '')
			{
				echo '<table><tr><td>'.$message.'</td></tr></table>';
			}
			echo '<table>';
			
				foreach($result as $key => $val)
				{
					if($key == 0)
					{
						echo '<tr>';
						foreach($val as $key2 => $val2)
						{
							echo '<td><b>'.$key2.'</b></td>';
						}
						echo '<td>Function</td>';
						echo '</tr>';
					}
					echo '<tr>';
					$field_name = '';
					foreach($val as $key2 => $val2)
					{
						if($key2 == 'Field')
							$field_name = $val2;
						echo '<td>'.$val2.'</td>';
					}
					echo '<td><a href="'.base_url().'mydb/show_structure/'.$table.'/del/'.$field_name.'" onclick="return confirm(\'Are you sure?\')">Del</a>&nbsp;|&nbsp;<a href="'.base_url().'mydb/add_field/'.$table.'/'.$field_name.'">Add</a></td>';
					echo '</tr>';
				}
			echo '</table>';
		}
	}
	
	function add_field($table, $param = '')
	{
		if(empty($table))
		{
			$this->session->set_flashdata('message', 'Please select table');
			redirect('mydb/index');
		}
		$message = $this->session->flashdata('message');
		if(isset($_POST['add']))
		{
			$this->form_validation->set_rules('field_name', 'Field Name','trim|required');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			if($this->form_validation->run() == TRUE)
			{
				$field_name = $this->input->post('field_name');
				$field_length = $this->input->post('field_length');
				$data_type = $this->input->post('data_type');
				$default_value = $this->input->post('default_value');
				$is_null = $this->input->post('is_null');
				
				if(in_array($data_type, array('VARCHAR', 'CHAR', 'INT')))
				{
					if($field_length == '')
					{
						if($data_type == 'VARCHAR')
							$field_length = '512';
						elseif($data_type == 'CHAR')
							$field_length = '256';
						elseif($data_type == 'INT')
							$field_length = '11';
					}
					$data_type = $data_type . '('.$field_length.')';
				}
				if($is_null == 'yes')
				{				
				$query = 'ALTER TABLE '.$table.' ADD `'.$field_name.'` '.$data_type.'  NULL AFTER `'.$param.'`;';
				}
				else
				{
				$query = 'ALTER TABLE '.$table.' ADD `'.$field_name.'` '.$data_type.' NOT NULL AFTER `'.$param.'`;';
				}
				$this->db->query($query);
				$this->session->set_flashdata('message', 'Field added successfully');
				redirect('mydb/show_structure/'.$table);
			}
		}
		echo $this->menu();
		echo form_open('');
		echo '<table>';
		echo '<tr><td colspan="2"><font color="red">'.$message.'</font></td></tr>';
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
		
		echo '<tr><td colspan="2"><b>Name</b></td></tr>';
		echo '<tr><td colspan="2"><input name="field_name" id="field_name" required></td></tr>';
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
		
		echo '<tr><td colspan="2"><b>Data Type</b></td></tr>';
		$data_types = array('TEXT' => 'Text', 'VARCHAR' => 'VARCHAR', 'CHAR' => 'Char', 'DATE' => 'Date', 'INT' => 'Int');
		echo '<tr><td colspan="2">';
		echo '<select name="data_type" id="data_type" required>';
			echo '<option value="">Please select data type</option>';
			foreach($data_types as $key => $val)
			echo '<option value="'.$key.'">'.$val.'</option>';
		echo '</select>';
		
		echo '&nbsp;';
		echo '<input name="field_length" id="field_length">';
		echo '</td></tr>';
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
		
		echo '<tr><td colspan="2"><b>Default</b></td></tr>';
		echo '<tr><td colspan="2"><input name="default_value" id="default_value"></td></tr>';
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
		
		echo '<tr><td colspan="2"><b>Is Null?</b></td></tr>';
		echo '<tr><td colspan="2">';
			echo '<select name="is_null" id="is_null" required>';
				echo '<option value="yes">Yes</option>';
				echo '<option value="no">No</option>';
			echo '</select>';
		echo '</td></tr>';
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
		
		echo '<tr><td colspan="2"><input type="submit" name="add" value="Add"></td></tr>';
		echo '</table>';
		echo '</form>';
	}
	
	function menu()
	{
		$html = '<table>';
			$html .= '<tr>';
			$html .= '<td>Selected DATABASE : <b>'.$this->selected_db.'</b></td>';
			$html .= '</tr>';
		$html .= '</table>';
		$html .= '<table>';
			$html .= '<tr>';
			$html .= '<td><a href="'.base_url().'mydb/show_databases">Databases</a></td>';
			$html .= '<td><a href="'.base_url().'mydb/index">Tables</a></td>';
			$html .= '<td><a href="'.base_url().'mydb/query_editor">Query Editor</a></td>';
			$html .= '</tr>';
		$html .= '</table>';
		return $html;
	}
	
	function edit_record($table, $pk = '', $id = '')
	{
		if(empty($table))
		{
			$this->session->set_flashdata('message', 'Please select table');
			redirect('mydb/index');
		}
		$message = $this->session->flashdata('message');
		$fields = $this->db->list_fields($table);
		
		if(isset($_POST['update']))
		{
			$data = array();
			foreach($fields as $field)
			{
				$data[$field] = $_POST[$field];
			}
			$message = '';
			if(!empty($pk) && !empty($id))
			{
				$this->db->where($pk, $id);
				$this->db->update($table, $data);
				$message = 'Record updated successfully';
			}
			else
			{
				$this->db->insert($table, $data);
				$message = 'Record inserted successfully';
			}
			$this->session->set_flashdata('message', $message);
			redirect('mydb/index/'.$table);
		}
		
		
		$record = array();
		if(!empty($pk) && !empty($id))
		{
			$query = 'SELECT * FROM '.$table.' WHERE '.$pk.' = '.$id;
			$result = $this->db->query($query)->result();
			if(!empty($result))
			$record = $result[0];
		}
		//print_r($fields);
		$html = '';
			foreach($fields as $field)
			{
				$html.= '<tr>';
				$html .= '<td>'.$field.'</td>';
				$val = '';
				if(isset($record->$field))
					$val = $record->$field;
				$html .= '<td><input type="text" name="'.$field.'" value="'.$val.'"></td>';
				$html.= '</tr>';
			}
			$html .= '<tr><td colspan="2" align="center"><input type="submit" name="update" value="Submit"></td></tr>';
		
		echo form_open('');
		echo '<table>';
		echo $html;
		echo '</table>';
		echo '</form>';
	}
}
/* End of file Booking.php */
/* Location: ./application/controllers/Booking.php */
