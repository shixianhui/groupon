<?php
class File extends CI_Controller {
	private $_title = '文件管理';
	private $_tool = '';
	private $_table = '';
	private $_template = 'file';
	public function __construct() {
		parent::__construct();
		$this->_tool = $this->load->view('element/file_tool', '', TRUE);
		$this->load->helper(array('url', 'my_fileoperate'));
	}

	public function index($filePath = ':uploads:') {
	    checkPermission("{$this->_template}_index");
	    
		$baseArray = array();
		$path = preg_replace('/\:/', '/', $filePath);
		$baseDirectory = '.' . $path;
		$handler = opendir( $baseDirectory );
		$i = 0;
		while(($fileName = readdir( $handler )) !== false){
			if($fileName != '.' && $fileName != '..' && !strpos($fileName, '_thumb')) {
			    $baseArray[$i]['fileName'] = $fileName;
			    $baseArray[$i]['fileSize'] = getFileSize(filesize($baseDirectory . $fileName));
			    if (file_exists('.'.$path . $fileName) && is_file('.'.$path . $fileName)) {
			    	//原图大小
			    	$artworkSize = @getimagesize('.'.$path . $fileName);
			    	if ($artworkSize) {
			    		$baseArray[$i]['artworkSize'] = "{$artworkSize[0]}x{$artworkSize[1]}";
			    	} else {
			    		$baseArray[$i]['artworkSize'] = '---';
			    	}
			    	//缩略图大小
			        $thumbnailSize = @getimagesize('.'.$path . preg_replace('/\./', '_thumb.', $fileName));
			    	if ($thumbnailSize) {
			    		$baseArray[$i]['thumbnailSize'] = "{$thumbnailSize[0]}x{$thumbnailSize[1]}";
			    	} else {
			    		$baseArray[$i]['thumbnailSize'] = '---';
			    	}
			    } else {
			        $baseArray[$i]['artworkSize'] = '---';
			        $baseArray[$i]['thumbnailSize'] = '---';
			    }
			    $baseArray[$i]['fileMTime'] = date('Y-m-d H:i', filemtime($baseDirectory . $fileName));
			    $baseArray[$i]['fileType'] = filetype($baseDirectory . $fileName);
			    $i++;
			}
		}
		closedir($handler);
		$preFilePath = $filePath;
		$files = explode(":", preg_replace(array('/^:/', '/:$/'), array('', ''), $filePath));
		if (count($files) > 1) {
			$preFilePath = ':';
			foreach ($files as $key=>$value) {
				if ($key+1 != count($files)) {
				    $preFilePath .= $value.':';	
				}	     
			}
		}
		$data = array(
		        'tool'=>$this->_tool,
				'files'=>$baseArray,
				'path'=>$path,
				'cusPath'=>$filePath,
		        'prePath'=>$preFilePath
		        );
	    $layout = array(
			      'title'=>$this->_title,
				  'content'=>$this->load->view('file/index', $data, TRUE)
			      );
	    $this->load->view('layout/default', $layout);
	}

	public function deleteFile() {
	    checkPermissionAjax("{$this->_template}_deleteFile");
	    
		$path = $this->input->post('path', TRUE);
	    if ($path) {
	    	if (@unlink('.'.preg_replace('/\:/', '/', $path)) || @unlink('.'.preg_replace(array('/\:/', '/\./'), array('/', '_thumb.'), $path))) {
	    		printAjaxSuccess('', '删除成功！');
	    	}
	    }

	    printAjaxError('fail', '删除失败！');
	}
}
/* End of file news.php */
/* Location: ./application/admin/controllers/news.php */