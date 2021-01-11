<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

	public function getByUsername($username){
         $this->db->where('username',$username);
         $admin = $this->db->get('admins')->row_array();
		// Select * FROM admins WHERE username = {$username}
         return $admin;





	}

} 

/* End of file Admin_model.php */
/* Location: ./application/models/Admin_model.php */ 