<?php

namespace App\Controllers;

use Inf\Router\RouterRequest;

class Albums extends Controller{

	function __construct(){
		parent::__construct();
	}
	
	public function listAlbums($order = 'desc'){
		$page = RouterRequest::getData('page');
		$limit = RouterRequest::getData('limit');

		$data =  $this->helper->getRecordsFrom('albums',true,$page,$limit);
		

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
	
	public function listAlbumsByUser($id,$order = 'desc'){
		$page = RouterRequest::getData('page');
		$limit = RouterRequest::getData('limit');

		$data =  $this->helper->getRecordBy('albums','userId',$id);
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

	
	public function getAlbum($id){
		$data =  $this->helper->getRecordById('albums',$id);
		if($data){
			return $this->process($data)->send();
		}else{
			return $this->process($data,204,'text')->send();
		}
	}

	

	public function addAlbum(){
		$title = RouterRequest::postData('title');
		$data = [
			'userId'=>1,
			'title'=>$title,
			'createdAt'=>date('Y-m-d\TH:i:s\Z', time()),
			'updatedAt'=>date('Y-m-d\TH:i:s\Z', time()),
		];
		$save = $this->helper->addRecordTo('albums',$data);		
		if(False != $save){			
			return $this->process(['status' => 1, 'msg' => 'A new Album added','data'=>$save])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to add album at the moment'],203)->send();
		}
	}
	
	public function editAlbum(){
		
		$id = RouterRequest::reqData('id');
		$title = RouterRequest::reqData('title');

		$data = [
			'title'=>$title,
			'updatedAt'=>date('Y-m-d\TH:i:s\Z', time()),
		];
		$save = $this->helper->editRecordIn('albums',$data,$id);		
		if(False != $save){				
			return $this->process(['status' => 1, 'msg' => 'Album updated','data'=>$save])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to update album at the moment'],203)->send();
		}
	}
	
	public function deleteAlbum($id){
		$save = $this->helper->deleteRecordFrom('albums',$id);		
		if($save){			
			return $this->process(['status' => 1, 'msg' => 'Album deleted'])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to delete album at the moment'],203)->send();
		}
	}

}