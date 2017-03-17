<?php
	
	class Object
	{
		public $var1;
		public $var2;

		function Object()
		{
			$this -> var1 = 'variable 1';
			$this -> var2 = 'variable 2';
		}

		function showVariables()
		{
			echo json_encode($this) . '<br />';
		}

	}

	$obj1 = new Object();
	$obj1 -> showVariables();
?>