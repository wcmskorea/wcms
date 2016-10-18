<?php


/*************************************************
  GPIN SP서버 연결 정보
**************************************************/

include "gpin_proxy_properties.php";

$GPIN_SERVER_URL  = "http://".$GPIN_CLIENT_HOST.":".$GPIN_CLIENT_PORT.$GPIN_CLIENT_CONTEXT."/SPStub";
$GPIN_SERVER_URL2 = "http://".$GPIN_CLIENT_HOST.":".$GPIN_CLIENT_PORT.$GPIN_CLIENT_CONTEXT."/SPStub";

// 에러 공통 변수
$GPIN_ERRCODE=0;	// 통신 리턴 에러코드   0:성공 이외는 에러임.
$GPIN_ERRMSG="";    // 에러발생 메세지
$GPIN_DEBUG="";     // 디버그용
$GPIN_LOG="";       // 디버그용 로그변수




/*************************************************
   GPIN API

   2007.11.29
*************************************************/

function  InitGpin( $address, $sockettimeout )
{
	Global $GPIN_SERVER_URL;
	Global $GPIN_CLIENT_TIMEOUT;

	
	if ( $sockettimeout > 30) $sockettimeout=30;   // 최대 30초

	$GPIN_SERVER_URL=$address;
	$GPIN_CLIENT_TIMEOUT = $sockettimeout;

}

function version($attributes){

	$ret = GPIN_HTTP("versionRequest", (string)$attributes );

	$ret= GPIN_STR_HAN_DECODE($ret);

	return $ret;
}


function  makeAuthRequest($attributes) {

	$ret = GPIN_HTTP("requestAuth", (string)$attributes );

	$ret= GPIN_STR_HAN_DECODE($ret);

	return $ret;
}



function  makeAttributeRequest($vidn,  $attributes ) {


   // 한글 Encode
   $attributes= GPIN_STR_HAN_ENCODE($attributes);


	$temp = array($vidn);

	// 인수배열 병합
	$value = array_merge( $temp, $attributes );
	
	// HTTP통신
	$ret = GPIN_HTTP("requestAttr", $value );

	// 한글 Decode
	$ret= GPIN_STR_HAN_DECODE($ret);

	return $ret;
}

function getUserDupValue($regNo, $siteId)
{	
	$value = array($regNo);
	$temp = array($siteId);

	$value = array_merge( $value, $temp );

	$ret = GPIN_HTTP( "getUserDupValue", $value);

	return $ret;
}


function  getUserAttributes( $artifact, $attributeNames ){

   Global  $GPIN_SPLIT_STR;


	// 인수배열 병합
	$temp = array($artifact);
	$value = array_merge( $temp, $attributeNames );


	$ret = GPIN_HTTP( "getUserAttributes", $value);


	// 입력 값을 배열로 리턴
    if ( strstr ( $ret , $GPIN_SPLIT_STR ) ) {
		$arr_ret = explode($GPIN_SPLIT_STR, $ret);
	}
	else {
		$arr_ret[]=$ret;
	}

	// 리턴값 한글변환
	$arr_ret= GPIN_STR_HAN_DECODE($arr_ret);

	return $arr_ret;
}



function  parseSAMLResponse( $samlResponse, $attributeNames ){

   Global  $GPIN_SPLIT_STR;


	// 인수배열 병합
	$temp = array($samlResponse);
	$value = array_merge( $temp, $attributeNames );


	$ret = GPIN_HTTP( "parseSAMLResponse", $value);


	// 입력 값을 배열로 리턴
    if ( strstr ( $ret , $GPIN_SPLIT_STR ) ) {
		$arr_ret = explode($GPIN_SPLIT_STR, $ret);
	}
	else {
		$arr_ret[]=$ret;
	}

	// 리턴값 한글변환
	$arr_ret= GPIN_STR_HAN_DECODE($arr_ret);

	return $arr_ret;
}



function  sendAuthResult( $result, $id) {

	$value = array( $result, $id );

	$ret = GPIN_HTTP("sendAuthResult", $value );

}



function decryptGpinSync( $recvMessage)
{
	Global  $GPIN_SPLIT_STR;	//081211 mjsee

	$value =  $recvMessage;
	
	//$v = GPIN_HTTP("decrypteGpinSync", $value);
	//$ret= explode($GPIN_SPLIT_STR, GPIN_STR_HAN_DECODE($v[0]) );

	//081211 mjsee
	$v = GPIN_HTTP("decryptGpinSync", $value);
	$ret = explode($GPIN_SPLIT_STR, $v);	
	
	return $ret;
}


function makeResponseSyncResponse( $vidn, $respons)
{
	$value = array(  $vidn, $respons );
	
	$ret = GPIN_HTTP("makeResponseSyncResponse", $value);	

	return $ret;	//081211 mjsee
}






/*************************************************
   GPIN UTIL

   2007.11.29
*************************************************/

// GPIN용 HTTP함수
function GPIN_HTTP($remoteMethod, $value){

	Global $GPIN_ERRCODE;
	Global $GPIN_ERRMSG;
	Global $GPIN_SERVER_URL;
	Global $GPIN_SERVER_URL2;
	Global $GPIN_CLIENT_TIMEOUT;
	Global $GPIN_LOG;


	//제거대상
	if ($remoteMethod == "getUserDupValue") {
		$GPIN_SERVER_URL=$GPIN_SERVER_URL2;
	}


	$data["remoteMethod"]=$remoteMethod;


	if ( is_array($value) ){
		for ($i = 0;$i < count($value) ; $i++) {
			$data["arg".$i] = $value[$i];
		}
	 } else {
		$data["arg0"]=$value;
	 }
	 


	$result = HTTP_Post($GPIN_SERVER_URL, $data, $GPIN_CLIENT_TIMEOUT);



	// HTTP 성공 유무
	if ( strstr($result,"200 OK") ) {
	
		// 헤더삭제, 공백삭제
		$result=trim(strstr($result,"\n\r\n"));


		$GPIN_LOG = $GPIN_LOG."[RECV]:".$result."\n";



		// S인경우 성공
		if ( substr($result,0,1) == "S" ) {
			$result = substr($result,1);
			$GPIN_ERRCODE=0;
			$GPIN_ERRMSG="";
			
		}
		else if ( substr($result,0,1) == "E" ) {
			$GPIN_ERRCODE=200;
			$GPIN_ERRMSG=substr($result,1);
			$GPIN_ERRMSG=GPIN_STR_HAN_DECODE($GPIN_ERRMSG);

			$result="";
		} else {
			// "S""E"가 아닌경우
			$GPIN_ERRCODE=400;
			if ( $result =="") 
				$result="서버로 부터 정보를 받지 못했습니다.";
			$GPIN_ERRMSG=$result;
			$result="";

		}

	 }
	 else {
	  $GPIN_ERRCODE=500;
	  if ( $result =="") {
		  $GPIN_ERRMSG="서버에 연결하지 못했습니다.";
	  }
	  else {
          $GPIN_ERRMSG = "서버오류 ".substr( $result,0,strpos( $result,"\n")-1);
	  }

	  $GPIN_LOG = $GPIN_LOG."[ERRO]: ".$GPIN_ERRMSG.".\n";
	  $result="";
	 }


 return $result; 
}


//  HTTP_POST 프로토콜 구현
function HTTP_Post($URL, $data, $timeout ) {

	Global $GPIN_DEBUG;
	Global $GPIN_LOG;

	// parsing the given URL
	$URL_Info=parse_url($URL);

	$request="";
	$result="";


	// making string from $data
	foreach($data as $key=>$value)
	$values[]="$key=".urlencode($value);
	$data_string=implode("&",$values);


	// debug
	$GPIN_DEBUG = $data_string;
	$GPIN_LOG = $GPIN_LOG."[SEND]:".$data_string."\n";


	// Find out which port is needed - if not given use standard (=80)
	if(!isset($URL_Info["port"]))
	$URL_Info["port"]=80;
	if(!isset($URL_Info["path"]))
	$URL_Info["path"]="/";



	// building POST-request:
	$request.="POST ".$URL_Info["path"]." HTTP/1.0\n";
	$request.="Host: ".$URL_Info["host"]."\n";
	$request.="Content-type: application/x-www-form-urlencoded; charset=UTF-8\n";
	$request.="Content-length: ".strlen($data_string)."\n";
	$request.="Connection: close\n";
	$request.="\n";
	$request.=$data_string."\n";


	$fp = @fsockopen($URL_Info["host"],$URL_Info["port"],  $errno, $errstr,$timeout);

	if ($fp > 0) {
		fputs($fp, $request);
		while(!feof($fp)) {
			$result .= fgets($fp, 1024);
		}
	fclose($fp);
	}

	return $result;
}



function  HAN_DECODE( $in )
{

	Global $GPIN_CLIENT_CHARSET;


	if ( $GPIN_CLIENT_CHARSET=="DEFAULT" )
		return $in;

	if ( $GPIN_CLIENT_CHARSET=="UTF-8")
		return iconv("UTF-8", "euc-kr", $in);

}



function  HAN_ENCODE( $in )
{

	Global $GPIN_CLIENT_CHARSET;


	if ( $GPIN_CLIENT_CHARSET=="DEFAULT" )
		return $in;

	if ( $GPIN_CLIENT_CHARSET=="UTF-8")
		return iconv("euc-kr","UTF-8",  $in);

}




// gpin서버로부터 보낼 한글 변환
function  GPIN_STR_HAN_ENCODE($str)
{

	if ( is_array($str) == FALSE ) {
		$str=trim($str);
		$str=HAN_ENCODE($str);
	}
	else {
		for($key=0; $key<count($str); $key++) {

			$str[$key]=trim($str[$key]);
			$str[$key]=HAN_ENCODE($str[$key]);
		}
}	


	return $str;
}


// gpin서버로부터 받은 한글 변환
function  GPIN_STR_HAN_DECODE($str)
{

	if ( is_array($str) == FALSE ) {
		$str=trim($str);
		$str=HAN_DECODE($str);
	}
	else {
		for($key=0; $key<count($str); $key++) {

			$str[$key]=trim($str[$key]);
			$str[$key]=HAN_DECODE($str[$key]);
		}
}	


	return $str;
}


// HTTP 통신로그 출력
function  GPIN_LOG(){

	Global $GPIN_LOG;

	echo "<textarea style='border:solid 1px #999999;background-color:#eeffff;' cols=80 rows=10 >";
	echo $GPIN_LOG;
	echo "[END!]";
	echo "</textarea>";

}


// GPIN 에러 체크 후 메세지 출력 후 종료.
function  GPIN_ERROR_CHECK( $ext )
{

	Global $GPIN_ERRCODE;
	Global $GPIN_ERRMSG;
	Global $GPIN_LOG;


    // alert 출력을 위해 특수 문자 삭제
    $err_str = str_replace(array("\n","'","\"") , "", $GPIN_ERRMSG);

	// 에러체크
	if ( $GPIN_ERRCODE != 0 ) {

	  echo "<script>\n";
	  echo "	alert('".$err_str."[".$GPIN_ERRCODE."]');\n"; 
	  echo "</script>\n";

	  if ( $ext )
         exit;

	}

}
/**************************************************************************************/
?>


