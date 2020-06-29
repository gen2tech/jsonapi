<?php

namespace App\Controllers;

use Inf\Router\RouterRequest;

class Photos extends Controller{

	function __construct(){
		parent::__construct();
	}
	
	public function listPhotos($order = 'desc'){
		$page = RouterRequest::getData('page');
		$limit = RouterRequest::getData('limit');

		$data =  $this->helper->getRecordsFrom('photos',true,$page,$limit);
		

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
	
	public function listPhotosByAlbum($id,$order = 'desc'){
		$page = RouterRequest::getData('page');
		$limit = RouterRequest::getData('limit');

		$data =  $this->helper->getRecordBy('photos','albumId',$id);
		$data =  $this->helper->paginate($data,$page,$limit);		

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

	
	public function getPhoto($id){
		$data =  $this->helper->getRecordById('photos',$id);
		if($data){
			return $this->process($data)->send();
		}else{
			return $this->process($data,204,'text')->send();
		}
	}


	

	public function addPhoto(){
		$title = RouterRequest::postData('title');
		$url = RouterRequest::postData('url');
		$thumbnail = RouterRequest::postData('thumbnail');
		$data = [
			'albumId'=>1,
			'title'=>$title,
			'url'=>$url,
			'thumbnail'=>$thumbnail,
			'createdAt'=>date('Y-m-d\TH:i:s\Z', time()),
			'updatedAt'=>date('Y-m-d\TH:i:s\Z', time()),
		];
		$save = $this->helper->addRecordTo('photos',$data);		
		if(False != $save){			
			return $this->process(['status' => 1, 'msg' => 'A new Photo added','data'=>$save])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to add photo at the moment'],203)->send();
		}
	}
	
	public function editPhoto(){
		
		$id = RouterRequest::reqData('id');
		$title = RouterRequest::reqData('title');
		$url = RouterRequest::reqData('url');
		$thumbnail = RouterRequest::reqData('thumbnail');

		$data = [
			'title'=>$title,
			'url'=>$url,
			'thumbnail'=>$thumbnail,
			'updatedAt'=>date('Y-m-d\TH:i:s\Z', time()),
		];
		$save = $this->helper->editRecordIn('photos',$data,$id);		
		if(False != $save){				
			return $this->process(['status' => 1, 'msg' => 'Photo updated','data'=>$save])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to update photo at the moment'],203)->send();
		}
	}
	
	public function deletePhoto($id){
		$save = $this->helper->deleteRecordFrom('photos',$id);		
		if($save){			
			return $this->process(['status' => 1, 'msg' => 'Photo deleted'])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to delete photo at the moment'],203)->send();
		}
	}

}