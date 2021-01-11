<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Category extends CI_Controller {
	//This method will show category list page
	public function index()
	{
		$this->load->model('Category_model');
		$queryString = $this->input->get('q');
		$params['queryString'] = $queryString;

		$categories = $this->Category_model->getCategories($params);
		$data['categories'] = $categories;
		$data['queryString'] = $queryString;
		$this->load->view('admin/category/list',$data);
	}
	//This method will show create category page
	public function create(){
 
 		$this->load->helper('common_helper');

		$config['upload_path']          = './public/uploads/category/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg';
		$config['encrypt_name']        = true;

		$this->load->library('upload', $config);
 
		$this->load->model('Category_model');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<p class="invalid-feedback">','</p>');
		$this->form_validation->set_rules('name','Name','trim|required');

		if($this->form_validation->run() == TRUE){
			//print_r($_FILES['image']['name']); die;
		if(!empty($_FILES['image']['name'])){
			// Now user has selected a file so we will proceed.

			if($this->upload->do_upload('image')){
				// File upload successfully.
				$data = $this->upload->data();

				// Resizing part
			resizeImage($config['upload_path'].$data['file_name'],$config['upload_path'].'thumb/'.$data['file_name'],200,170);
				// We Will Crete category with image. [file_name]
			$formArray['image'] = $data['file_name']; 	
			$formArray['name'] = $this->input->post('name'); 
			$formArray['status'] = $this->input->post('status'); 
			$formArray['created_at'] = date('Y-m-d H:i:s'); 
			$this->Category_model->create($formArray);
			$this->session->set_flashdata('success','Category Added Succesfully.');
			redirect(base_url().'admin/category/index');
			/*	echo "<pre>";
				print_r($data);
				echo "</pre>";
				exit;*/

			}else{
				// We got some errors.
				$error = $this->upload->display_errors("<p class='invalid-feedback'>",'</p>' );
				$data['errorImageUpload'] = $error;
				$this->load->view('admin/category/create',$data);
			}

		}else{
		// We Will Crete category without image.
		$formArray['name'] = $this->input->post('name'); 
		$formArray['status'] = $this->input->post('status'); 
		$formArray['created_at'] = date('Y-m-d H:i:s'); 
		$this->Category_model->create($formArray);
		$this->session->set_flashdata('success','Category Added Succesfully.');
		redirect(base_url().'admin/category/index');
			
		}

		}else{
			// Will show Errors.
			$this->load->view('admin/category/create');
		}
			
 
	}

	//This method will show edit category page
	public function edit($id){
		//echo $id;
		$this->load->model('Category_model');
		$category = $this->Category_model->getCategory($id);
		//echo "<pre>";
		//print_r($category);
		if(empty($category)){
			$this->session->set_flashdata('error','Category not found');
			redirect(base_url().'admin/category/index');
		}

		$this->load->helper('common_helper');

		$config['upload_path']          = './public/uploads/category/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg';
		$config['encrypt_name']        = true;

		$this->load->library('upload', $config);
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<p class="invalid-feedback">','</p>');
		$this->form_validation->set_rules('name','Name','trim|required');

		if($this->form_validation->run() == TRUE){

			if(!empty($_FILES['image']['name'])){
			// Now user has selected a file so we will proceed.

			if($this->upload->do_upload('image')){
				// File upload successfully.
				$data = $this->upload->data();
				// Resizing part
			resizeImage($config['upload_path'].$data['file_name'],$config['upload_path'].'thumb/'.$data['file_name'],200,170);
				// We Will Create category with image. [file_name]
			$formArray['image'] = $data['file_name']; 	
			$formArray['name'] = $this->input->post('name'); 
			$formArray['status'] = $this->input->post('status'); 
			$formArray['updated_at'] = date('Y-m-d H:i:s'); 
			$this->Category_model->update($id,$formArray);

			if(file_exists('./public/uploads/category/'.$category['image'])){
			unlink('./public/uploads/category/'.$category['image']);
			}
			if(file_exists('./public/uploads/category/thumb/'.$category['image'])){
			unlink('./public/uploads/category/thumb/'.$category['image']);
			}

			$this->session->set_flashdata('success','Category Updated Succesfully.');
			redirect(base_url().'admin/category/index');
			
			}else{
				// We got some errors.
				$error = $this->upload->display_errors("<p class='invalid-feedback'>",'</p>' );
				$data['errorImageUpload'] = $error;
				$data['category'] = $category;
				$this->load->view('admin/category/edit',$data);
			}

			}else{
			// We Will Crete category without image.
			$formArray['name'] = $this->input->post('name'); 
			$formArray['status'] = $this->input->post('status'); 
			$formArray['updated_at'] = date('Y-m-d H:i:s'); 
			$this->Category_model->update($id,$formArray);
			$this->session->set_flashdata('success','Category Updated Succesfully.');
			redirect(base_url().'admin/category/index');
				
			}
		}else{
			$data['category'] = $category;
			$this->load->view('admin/category/edit',$data);
		}
	}

	//This method will Delete a category
	public function delete($id){
		$this->load->model('Category_model');
		$category = $this->Category_model->getCategory($id);
		//echo "<pre>";
		//print_r($category);
		if(empty($category)){
			$this->session->set_flashdata('error','Category not found');
			redirect(base_url().'admin/category/index');
		}

		if(file_exists('./public/uploads/category/'.$category['image'])){
		unlink('./public/uploads/category/'.$category['image']);
		}
		if(file_exists('./public/uploads/category/thumb/'.$category['image'])){
		unlink('./public/uploads/category/thumb/'.$category['image']);
		}

		$this->Category_model->delete($id);
		$this->session->set_flashdata('success','Category deleted successfully.');
		redirect(base_url().'admin/category/index');
	} 

} 

/* End of file Category.php */
/* Location: ./application/controllers/admin/Category.php */