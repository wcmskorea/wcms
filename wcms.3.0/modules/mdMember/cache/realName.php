<?php
//**********************************************************************************************
//한국신용평가정보 공공기관용 안심실명확인 서비스
//req,사이트코드,사이트패스워드,return_url ,요청 seq ,Reserved 값을 암호화하여
//한국신용평가정보 안심실명확인 페이지호출시 암호화된 값을 넘겨준다.
//작성일 : 2006.10.18
//**********************************************************************************************

// @SITEID 및 @SITEPW 를 실제 부여 받은 사이트 id 및 패스워드로 로 바꾸기 바랍니다.
$pSite_flag 	= "J011";   					// 사이트 id
$pSite_pwd  	= "38457883";   			// 사이트 비밀번호
$pSeqid	   		= "1234567890";				// 고객사 요청 Sequence
$pReturn_url	= "http://gecs.gen.go.kr/modules/mdMember/embed/realNamePost.php";			// 결과값을 리턴 받으실 URL (풀로 적어주셔야합니다.)
$pReserved1		= "test1";						// 기타 Reserved data1
$pReserved2		= "test2";						// 기타 Reserved data2
$pReserved3		= "test3";						// 기타 Reserved data3
$pReq			= "req";						      // 값을 변경하시면 안됩니다.
$cb_encode_path = $_SERVER[DOCUMENT_ROOT]."/modules/mdMember/embed/GPIN/SafeNC.exe";	// SafeNC.exe 모듈이 설치된 위치 (웹디렉토리에 올립니다.)

// 데이타를 암호화,복호화 하는 모듈입니다.

//**********************************************************************************************
// SafeNC 파일을 실행하여 데이타를 암호화 합니다.
//**********************************************************************************************
$enc_data = `$cb_encode_path $pReq $pSite_flag $pSite_pwd $pSeqid $pReturn_url $pReserved1 $pReserved2 $pReserved3`;

if ($enc_data == -1){
  $returnMsg = "암/복호화 시스템 오류";
  $enc_data = "-1";
}else if ($enc_data == -2){
  $returnMsg = "암호화 처리 오류";
  $enc_data = "-2";
}else if ($enc_data == -3){
  $returnMsg = "암호화 데이터 오류";
  $enc_data = "-3";
}else if ($enc_data == -9){
  $returnMsg = "입력값 오류";
  $enc_data = "-9";
}
?>
