<?php
class upLoad
{
	var $dir;
	var $table;
	var $seq;
	var $date;
	var $upCount;
	var $count;
	var $insert;
	var $temp;
	var $upLoaded;
	var $insertFile;
	var $insertDb;
	var $filterFile;
	var $filterImage;
	var $filterMedia;
	var $contentWidth;

	/**
	 * 객체생성
	 * @param $dir : 파일이 저장될 절대경로
	 * @param $temp : 저장된 파일들의 TEMP정보
	 * @param $array : 생성될 썸네일의 이미지 크기와 비율 정보
	 */
	function upLoad($dir=null, $temp=null, $ratio=null)
	{
		//디렉토리 생성
		if($dir && !is_dir($dir)) { mkdir($dir, 0707); }
		if($dir && !is_dir($dir.date("Y"))) { mkdir($dir.date("Y"), 0707); }
		if($dir && !is_dir($dir.date("Y")."/".date("m"))) { mkdir($dir.date("Y")."/".date("m"), 0707); }

		$this->dir        = $dir.date("Y")."/".date("m");
		$this->table      = "mdDocument__file";
		$this->seq        = null;
		$this->date       = time();
		$this->upCount    = 0;
		$this->thumbCount = 0;
		$this->count      = 1;
		$this->insert     = null;
		$this->temp       = $temp;
		$this->upLoaded   = null;

		$this->thumbIsFix   = "R"; //썸네일 비율 고정 선택 [R:비율형,F:고정형]

		//이미지의 높이는 초기값을 0으로한다.
		$this->small		= $GLOBALS['cfg']['module']['thumbSsize'];
		$this->middle		= $GLOBALS['cfg']['module']['thumbMsize'];
		$this->large		= $GLOBALS['cfg']['module']['thumbBsize'];
		$this->smallHeight  = 0;
		$this->middleHeight = 0;
		$this->largeHeight  = 0;

		//환경설정의 손톱이미지 비율이 비율형일때만 설정
		if($this->thumbIsFix != "F"){ list($this->ratioWidth, $this->ratioHeight) = $ratio;}

		$this->filterFile 	= array('asp','php','php3','php4','html','htm','inc','js','class','cgi','jsp','exe','sh','htaccess');
		$this->filterImage	= array('jpg','jpeg','gif','png');
		$this->filterMedia	= array('swf','flv','wmv','asf','avi');
		$this->resizeOriginImage = 1024; //원본 사이즈 조정
		$this->contentWidth	= 600; //에디터 삽입시 기본사이즈
	}

	/**
	 * 파입 업로드 실행함수
	 */
	function upFiles($reName=null)
	{
		$this->count = ($this->count < 1) ? count($this->upLoaded) : $this->count;
		//echo($this->count);
		for($n=0; $n < $this->count; $n++)
		{
			if($this->upFileCheck($n, $reName))
			{

				//이미지인지 체크하여 첫번째 이미지만 썸네일 생성
				if(in_array(strtolower($this->fileExt), $this->filterImage))
				{
					$this->basicSize = @getimagesize($this->dir."/".$this->fileRename);
					//2012-10-31 만들고자 하는 썸네일만 생성(사이즈가 있을 경우만) //오혜진 
					if($this->small)
					{
						$this->makeThumbNail("s-", $this->thumbIsFix,$this->small,$this->smallHeight, $quality=100);
						$this->thumbCount++;
					}
					if($this->middle)
					{
						$this->makeThumbNail("m-", $this->thumbIsFix,$this->middle,$this->middleHeight, $quality=100);
						$this->thumbCount++;
					}
					if($this->large)
					{
						$this->makeThumbNail("l-", $this->thumbIsFix,$this->large,$this->largeHeight, $quality=100);
						$this->thumbCount++;
					}
					//원본 이미지 사이즈 조정
					if($this->basicSize['0'] > $this->resizeOriginImage && $this->thumbIsFix == "R") { $this->makeThumbNail("", "R", $this->resizeOriginImage, 0, 100); }
				}
				//본문 삽입의 경우
				if($this->insertFile == 'Y' && $this->fileRename)
				{
					if(in_array(strtolower($this->fileExt), $this->filterMedia))
					{
						$this->getTagsFlash();
					}
					else
					{
						$this->getTagsImage();
					}
				}
				//DB등록
				$this->upDataBase();
			}
		}
	}

	/**
	 * 일반첨부 형태의 업로드
	 * @param $n
	 */
	function upFileCheck($n, $reName=null)
	{
		$element = ($n) ? 'upfile'.$n : 'upfile';
		if($this->temp[$element]['name'])
		{
			$this->fileRealName  = $this->temp[$element]['name'];
			$this->fileExt       = array_reverse(explode(".", $this->fileRealName));
			list($this->fileExt) = $this->fileExt;
			$this->fileExt       = strtolower($this->fileExt);
			$this->fileRename    = ($reName) ? $reName.".".$this->fileExt : __CATE__."_".time().str_replace(" ", null, microtime()).$n.".".$this->fileExt;

			if(!in_array($this->fileExt, $this->filterFile))
			{
				if(!copy($this->temp[$element]['tmp_name'], $this->dir."/".$this->fileRename)) { Functions::setLog(__FILE__, "(".$this->dir."/".$this->fileRename.")파일전송 실패입니다."); }
				if(!unlink($this->temp[$element]['tmp_name'])) { Functions::setLog("임시파일 삭제 실패입니다."); }
				$this->upCount += 1;
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			if(is_array($this->upLoaded))
			{
				if($this->upLoaded[$n])
				{
						$fileName	= explode("/", $this->upLoaded[$n]);
		    		$this->fileRename	= $fileName['0'];
		    		$this->fileRealName	= $fileName['1'];
		    		$this->fileExt = array_reverse(explode(".", $this->fileRealName));
		    		list($this->fileExt) = $this->fileExt;
		    		$this->fileExt = strtolower($this->fileExt);
		    		if(!preg_match('/'.$this->fileExt.'/', $this->fileRename))
		    		{
		    			$this->fileRename = $this->fileRename.".".$this->fileExt;
		    		}
		    		$this->upCount += 1;
				}
				else
				{
					unset($this->fileRename, $this->fileRealName, $this->fileExt);
				}
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	/**
	 * 썸네일 생성
	 * @param $prefix  : 구분자
	 * @param $isFix   : 비율,고정 여부
	 * @param $resize  : 생성할 썸네일의 width값
	 * @param $resize  : 생성할 썸네일의 height값
	 * @param $quality : 이미지 퀄리티 (%)
	 */
	function makeThumbNail($prefix, $isFix, $resize, $height="", $quality=100)
	{
		$this->width  = $resize;
		$this->height = $height;

		if($isFix != "F"){ $this->basicSize = $this->getSize();}

		if($this->basicSize[2] == 1)
		{ //GIF
			$src = imageCreateFromGIF($this->dir."/".$this->fileRename);
		}
		elseif($this->basicSize[2] == 2)
		{ //JPG
			$src = imageCreateFromJPEG($this->dir."/".$this->fileRename);
		}
		elseif($this->basicSize[2] == 3)
		{ //PNG
			$src = imageCreateFromPNG($this->dir."/".$this->fileRename);
		}
		else
		{
			return false;
		}
		$dst = imagecreatetruecolor($this->width, $this->height);

		$dstX = 0;
		$dstY = 0;

		//비율형
		if($isFix == "R"){

			if($this->ratioWidth && $this->ratioHeight && $this->basicSize['w'] / $this->basicSize['h'] <= $this->basicSize[0] / $this->basicSize[1])
			{
				$srcX = ceil(($this->basicSize[0] - $this->basicSize[1] * ($this->basicSize['w'] / $this->basicSize['h'])) / 2);
				$srcY = 0;
				$srcW = $this->basicSize[1] * ($this->basicSize['w'] / $this->basicSize['h']);
				$srcH = $this->basicSize[1];
			}
			else if($this->ratioWidth && $this->ratioHeight)
			{
				$srcX = 0;
				$srcY = ceil(($this->basicSize[1] - $this->basicSize[0] * ($this->basicSize['h'] / $this->basicSize['w'])) / 2);
				$srcW = $this->basicSize[0];
				$srcH = $this->basicSize[0]*($this->basicSize['h'] / $this->basicSize['w']);
			}
			else
			{
				$srcX = 0;
				$srcY = 0;
				$dstX = ( $this->basicSize['w'] < $resize ) ? ceil(($resize - $this->basicSize['w'])/2) : 0;
				$dstY = ( $this->basicSize['h'] < $this->height ) ? ceil(($this->height - $this->basicSize['h'])/2) : 0;
				$srcW = ImageSX($src);
				$srcH = ImageSY($src);
			}

			imagefilledrectangle($dst, 0, 0, $resize, $this->height, imagecolorallocate($dst, 255, 255, 255));
			imagecopyresampled($dst, $src, $dstX, $dstY, $srcX, $srcY, $this->basicSize['w'], $this->basicSize['h'], $srcW, $srcH);
			imagejpeg($dst, $this->dir."/".$prefix.$this->fileRename, $quality);
		}
		else if($isFix == "A")
		{

			$srcX = 0;
			$srcY = 0;
			$dstX = ( $this->basicSize['w'] < $resize ) ? ceil(($resize - $this->basicSize['w'])/2) : 0;
			$dstY = ( $this->basicSize['h'] < $height ) ? ceil(($height - $this->basicSize['h'])/2) : 0;
			$srcW = ImageSX($src);
			$srcH = ImageSY($src);
			print_r($this->basicSize);
			$dst = imagecreatetruecolor($this->width, $height);
			imagefilledrectangle($dst, 0, 0, $resize, $height, imagecolorallocate($dst, 255, 255, 255));
			imagecopyresampled($dst, $src, $dstX, $dstY, $srcX, $srcY, $this->basicSize['w'], $this->basicSize['h'], $srcW, $srcH);
			imagejpeg($dst, $this->dir."/".$prefix.$this->fileRename, $quality);

		}
		else
		{
			//고정형
			$srcX = 0;
			$srcY = 0;
			$dstX = 0;
			$dstY = 0;

			$srcW = $this->basicSize['0'];
			$srcH = $this->basicSize['1'];

			imagefilledrectangle($dst, 0, 0, $this->width, $this->height, imagecolorallocate($dst, 255, 255, 255));
			imagecopyresampled($dst, $src, $dstX, $dstY, $srcX, $srcY,$this->width,$this->height, $srcW, $srcH);
			imagejpeg($dst, $this->dir."/".$prefix.$this->fileRename, $quality);
		}

		imagedestroy($src);
		imagedestroy($dst);
	}

	/**
	 * 저장된 TEMP 이미지의  크기 불러오기 및 리사이즈될 크기
	 * @param $resize :
	 */
	function getSize()
	{
		if(!$this->basicSize || $this->basicSize[2] < 1 || $this->basicSize[2] > 3)
		{
			$this->height = $this->basicSize[1];
			return $this->basicSize;
		}

		if($this->width > $this->basicSize[0] && $this->width > $this->basicSize[1])
		{
			$this->height = $this->basicSize[1];
			return array_merge($this->basicSize, array("w"=>$this->basicSize[0], "h"=>$this->basicSize[1]));
		}

		if($this->ratioWidth && $this->ratioHeight)
		{
			$this->height = ceil($this->width * intval(trim($this->ratioHeight)) / intval(trim($this->ratioWidth)));
			return array_merge($this->basicSize, array("w"=>$this->width, "h"=>$this->height));
		}
		else if($this->basicSize[0] > $this->basicSize[1])
		{
			$this->height	= ceil($this->width * 3 / 4);
			$temp			= ceil(($this->width / $this->basicSize[0]) * $this->basicSize[1]);
			return array_merge($this->basicSize, array("w"=>$this->width, "h"=>$temp));
		}
		else
		{
			$this->height	= ceil($this->width * 3 / 4);
			$temp			= ceil(($this->height / $this->basicSize[1]) * $this->basicSize[0]);
			return array_merge($this->basicSize, array("w"=>$temp, "h"=>$this->height));
		}
	}

	/**
	 * 파일의 DB처리
	 * @param $oldFile : 값(배열)이 존재할경우 해당 파일과 레코드 삭제
	 */
	function upDataBase($oldFile=null)
	{
		if($this->fileRename && $this->insertDb == 'Y' && !$oldFile)
		{
			@mysql_query(" INSERT INTO `".$this->table."` (parent,cate,fileName,realName,extName,regDate) VALUES ('".$this->seq."','".__CATE__."','".$this->fileRename."','".$this->fileRealName."','".$this->fileExt."','".$this->date."') ");

		}
		else if(is_array($oldFile))
		{
			foreach($oldFile AS $key=>$val)
			{
				$file = @mysql_fetch_array(@mysql_query(" SELECT * FROM `".$this->table."` WHERE seq='".$val."' "));
				@unlink($GLOBALS['cfg']['upload']['dir'].date("Y",$file['regDate'])."/".date("m",$file['regDate'])."/".$file['fileName']);
				@unlink($GLOBALS['cfg']['upload']['dir'].date("Y",$file['regDate'])."/".date("m",$file['regDate'])."/s-".$file['fileName']);
				@unlink($GLOBALS['cfg']['upload']['dir'].date("Y",$file['regDate'])."/".date("m",$file['regDate'])."/m-".$file['fileName']);
				@unlink($GLOBALS['cfg']['upload']['dir'].date("Y",$file['regDate'])."/".date("m",$file['regDate'])."/l-".$file['fileName']);
				@mysql_query(" DELETE FROM `".$this->table."` WHERE seq='".$val."' ");
				$this->upCount--;
				if($key == 0) { $autoNum = $file['seq']; }
			}
			if($autoNum) { @mysql_query(" ALTER TABLE `".$this->table."` AUTO_INCREMENT=".$autoNum ); }
			@mysql_query(" OPTIMIZE TABLE `".$this->table."` ");

		}
	}

	/**
	 * 본문에 삽일될 플래시 태그
	 */
	function getTagsFlash()
	{
		$path = str_replace(__PATH__, '/', $this->dir);
		$path = str_replace("/_Site/".__DB__, '/user', $path);
		$this->insert .= '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="'.$this->basicSize['0'].'" height="'.$this->basicSize['1'].'" align="middle"><param name="movie" value="'.$path.'/'.$this->fileRename.'" /><param name="allowscriptaccess" value="always" /><param name="wmode" value="transparent" /><param name="menu" value="false" /><param name="quality" value="high" /><embed wmode="transparent" menu="false" quality="high" src="'.str_replace(__PATH__."_Site/".__DB__."/data/", __DATA__, $this->dir).'/'.$this->fileRename.'"';
		$this->insert .= ($this->basicSize['0'] > 600) ? ' width="100%"' : ' width="'.$this->basicSize['0'].'"';
		$this->insert .= ' height="'.$this->basicSize['1'].'" /></object>';
	}

	/**
	 * 본문에 삽입될 이미지 태그
	 */
	function getTagsImage()
	{
		$path = str_replace(__PATH__, '/', $this->dir);
		$path = str_replace("/_Site/".__DB__, '/user', $path);
		$this->insert .= '<img src="'.$path.'/'.$this->fileRename.'"';
		$this->insert .= ($this->basicSize['0'] > $this->contentWidth) ? ' style="width:100%;"' : ' style="width:'.$this->basicSize['0'].'px;"';
		$this->insert .= 'alt="'.$this->fileRename.'" />';
	}
}
?>
