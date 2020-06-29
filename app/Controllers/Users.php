<?php

namespace App\Controllers;

use Inf\Router\RouterRequest;

class Users extends Controller{

	function __construct(){
		parent::__construct();
	}
	
	public function listUsers($order = 'desc'){
		$page = RouterRequest::getData('page');
		$limit = RouterRequest::getData('limit');

		$data =  $this->helper->getRecordsFrom('users',true,$page,$limit);
		

		// Perform Ordering if Descending
		$data = array_values($data);
		if(\strtolower($order)!=='asc'){
			krsort($data);
			$data = array_values($data);
		}

		if($data){
			return $this->process($data)->send();
		}else{
			return $this->process($data,204,'text')->send();
		}
	}

	
	public function getUser($id){
		$data =  $this->helper->getRecordById('users',$id);
		if($data){
			return $this->process($data)->send();
		}else{
			return $this->process($data,204,'text')->send();
		}
	}
	
	public function getUserByEmail($email){
		$data =  $this->helper->getRecordBy('users','email',$email);
		if($data){
			return $this->process($data[0])->send();
		}else{
			return $this->process($data,204,'text')->send();
		}
	}
	
	public function getUserByUsername($username){
		$data =  $this->helper->getRecordBy('users','username',$username);
		if($data){
			return $this->process($data[0])->send();
		}else{
			return $this->process($data,204,'text')->send();
		}
	}


	public function addUser(){
		$name = RouterRequest::postData('name');
		$username = RouterRequest::postData('username');
		$email = RouterRequest::postData('email');
		// Address
		// ========
		$street = RouterRequest::postData('street');
		$suite = RouterRequest::postData('suite');
		$city = RouterRequest::postData('city');
		$zipcode = RouterRequest::postData('zipcode');
		// Geo
		// ----
		$lat = RouterRequest::postData('lat');
		$lng = RouterRequest::postData('lng');
		$phone = RouterRequest::postData('phone');
		$website = RouterRequest::postData('website');
		// Company
		// ========
		$catchPhrase = RouterRequest::postData('catchPhrase');
		$company_name = RouterRequest::postData('company_name');
		$bs = RouterRequest::postData('bs');

		$data = [
			'name'=>$name,
			'username'=>$username,
			'email'=>$email,
			'address'=>[
				'street'=>$street,
				'suite'=>$suite,
				'city'=>$city,
				'zipcode'=>$zipcode,
				'geo'=>[
					'lat'=>$lat,
					'lng'=>$lng,
				]
			],
			'phone'=>$phone,
			'website'=>$website,
			'company'=>[
				'name'=>$company_name,
				'catchPhrase'=>$catchPhrase,
				'bs'=>$bs
			],
			'createdAt'=>date('Y-m-d\TH:i:s\Z', time()),
			'updatedAt'=>date('Y-m-d\TH:i:s\Z', time()),
		];
		$save = $this->helper->addRecordTo('users',$data);		
		if(False != $save){			
			return $this->process(['status' => 1, 'msg' => 'A new User added','data'=>$save])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to add user at the moment'],203)->send();
		}
	}
	
	public function editUser(){
		
		$id = RouterRequest::reqData('id');
		$name = RouterRequest::reqData('name');
		$username = RouterRequest::reqData('username');
		$email = RouterRequest::reqData('email');
		// Address
		// ========
		$street = RouterRequest::reqData('street');
		$suite = RouterRequest::reqData('suite');
		$city = RouterRequest::reqData('city');
		$zipcode = RouterRequest::reqData('zipcode');
		// geo
		// ----
		$lat = RouterRequest::reqData('lat');
		$lng = RouterRequest::reqData('lng');
		$phone = RouterRequest::reqData('phone');
		$website = RouterRequest::reqData('website');
		// Company
		// ========
		$catchPhrase = RouterRequest::reqData('catchPhrase');
		$company_name = RouterRequest::reqData('company_name');
		$bs = RouterRequest::reqData('bs');

		$data = [
			'name'=>$name,
			'username'=>$username,
			'email'=>$email,
			'address'=>[
				'street'=>$street,
				'suite'=>$suite,
				'city'=>$city,
				'zipcode'=>$zipcode,
				'geo'=>[
					'lat'=>$lat,
					'lng'=>$lng,
				]
			],
			'phone'=>$phone,
			'website'=>$website,
			'company'=>[
				'name'=>$company_name,
				'catchPhrase'=>$catchPhrase,
				'bs'=>$bs
			],
			'updatedAt'=>date('Y-m-d\TH:i:s\Z', time()),
		];
		$save = $this->helper->editRecordIn('users',$data,$id);		
		if(False != $save){				
			return $this->process(['status' => 1, 'msg' => 'User updated','data'=>$save])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to update user at the moment'],203)->send();
		}
	}
	
	public function deleteUser($id){
		$save = $this->helper->deleteRecordFrom('users',$id);		
		if($save){			
			return $this->process(['status' => 1, 'msg' => 'User deleted'])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to delete user at the moment'],203)->send();
		}
	}


}