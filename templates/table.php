<?php

	/*
	 * These two classes generate tables. First, rows must be created, sorted in a relational array according to
	 * which column they will be rendered in. Then all arguments are sent to the Table class, with the col_array
	 * being the column names and then supplying the objects of instance Row that will be rendered in the table.
	 *
	 * NOTE: '\n' and '\t' correspond to new line and a tab. This is only use to make the generated HTML more readable
	 */
	class Table
	{
		private $_Table;
		protected $_ColumnArray;

		public function __construct($col_array, $entry_objects) {
			if (!isset($col_array)) {
				die ("Must enter column array");
			}

			$this->_ColumnArray = $col_array;

			$this->_Table = "<table>\n<tr class=\"col_heading\">";

			foreach($col_array as $column) {
				$this->_Table .= "\t<td>" . $column . "</td>\n";
			}

			$this->_Table .= "\n</tr>";

			$this->_Table .= $this->render_row($entry_objects);

			$this->_Table .= "\n</table>\n";

		}

		public function render() {
			return $this->_Table;
		}

		private function render_row($object) {

			$str = ' ';

			// Check if array of objects
			if (is_array($object)) {
				foreach($object as $row) {
					if ($row instanceof Row) {
						$str .= $row->render();
					}
				}
			} elseif ($object instanceof Row) {
				// Check if single object
				$str = $object->render();
			} else {
				die ("Invalid values supplied for object");
			}


			return $str;
		}

	}


	class Row {

		private $_Row;

		public function __construct($data, $id, $location, $restore=false) {
			$this->_Row = "\n<tr class=\"row\">";

			ksort($data); // Sort relational array according to key

			foreach($data as $key => $value) {
				$this->_Row .=  "\n\t<td>" . $value . "</td>";
			}
			$this->_Row .= '<td><a href="' . $location . '/info.php?id=' . $id . '"> Info</a></td>';
			$this->_Row .= '<td><a href="' . $location . '/edit.php?id=' . $id .'"> Edit</a></td>';

			if($restore)
				$this->_Row .= '<td><a href="' . $location . '/delete.php?id=' . $id .'">Restore</a></td>';
			else
				$this->_Row .= '<td><a href="' . $location . '/delete.php?id=' . $id . '">Delete</a></td>';

			$this->_Row .= '</tr>';
		}

		public function render() {
			return $this->_Row;
		}

	}

