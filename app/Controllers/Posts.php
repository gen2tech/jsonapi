<?php

namespace App\Controllers;

use Inf\Router\RouterRequest;

class Posts extends Controller{

	function __construct(){
		parent::__construct();
	}
	
	public function listPosts($order = 'desc'){
		$page = RouterRequest::getData('page');
		$limit = RouterRequest::getData('limit');

		$data =  $this->helper->getRecordsFrom('posts',true,$page,$limit);
		

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
	
	public function listPostsByUser($id,$order = 'desc'){
		$page = RouterRequest::getData('page');
		$limit = RouterRequest::getData('limit');

		$data =  $this->helper->getRecordBy('posts','userId',$id);
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

	
	public function getPost($id){
		$data =  $this->helper->getRecordById('posts',$id);
		if($data){
			return $this->process($data)->send();
		}else{
			return $this->process($data,204,'text')->send();
		}
	}

	public function addPost(){
		$title = RouterRequest::postData('title');
		$body = RouterRequest::postData('body');
		$data = [
			'userId'=>1,
			'title'=>$title,
			'body'=>$body,
			'createdAt'=>date('Y-m-d\TH:i:s\Z', time()),
			'updatedAt'=>date('Y-m-d\TH:i:s\Z', time()),
		];
		$save = $this->helper->addRecordTo('posts',$data);		
		if(False != $save){			
			return $this->process(['status' => 1, 'msg' => 'A new Post added','data'=>$save])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to add post at the moment'],203)->send();
		}
	}
	
	public function editPost(){
		
		$id = RouterRequest::reqData('id');
		$title = RouterRequest::reqData('title');
		$body = RouterRequest::reqData('body');

		$data = [
			'title'=>$title,
			'body'=>$body,
			'updatedAt'=>date('Y-m-d\TH:i:s\Z', time()),
		];
		$save = $this->helper->editRecordIn('posts',$data,$id);		
		if(False != $save){				
			return $this->process(['status' => 1, 'msg' => 'Post updated','data'=>$save])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to update post at the moment'],203)->send();
		}
	}
	
	public function deletePost($id){
		$save = $this->helper->deleteRecordFrom('posts',$id);		
		if($save){			
			return $this->process(['status' => 1, 'msg' => 'Post deleted'])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to delete post at the moment'],203)->send();
		}
	}
}