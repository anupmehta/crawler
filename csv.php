<?php
	class CSV {
		protected $data;
 
		/*
		 * @params array $columns
		 * @returns void
		 */
		public function __construct($columns) {
			$this->data = '"' . implode('","', $columns) . '"' . "\n";
		}
		/*
		 * @params array $row
		 * @returns void
		 */
		public function addRow($row) {
			$this->data .= '"' . implode('","', $row) . '"' . "\n";
		}
		/*
		 * @returns void
		 */
		public function export($filename) {
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
 
			echo $this->data;
			die();
		}
		public function __toString() {
			return $this->data;
		}
	}
 
	
 
?>
