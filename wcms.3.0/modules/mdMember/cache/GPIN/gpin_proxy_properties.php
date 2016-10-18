<?php


/*************************************************
  G-PIN_CLIENT 연결 정보
**************************************************/

// HOST
$GPIN_CLIENT_HOST="127.0.0.1";

// PORT
$GPIN_CLIENT_PORT="9901";

// TOMCAT_CONTEXT_명
$GPIN_CLIENT_CONTEXT="/G-PIN";

// HTTP연결 타임아웃 (초)
$GPIN_CLIENT_TIMEOUT=15;

// GPIN 한글셋 변환 ( 송신,수신정보동시 적용), "DEFAULT" 변환없음, "UTF-8" 송신,수신 내용 변환함.
$GPIN_CLIENT_CHARSET="UTF-8";
//$GPIN_CLIENT_CHARSET="EUC-KR";

// GPIN 리턴값 구분자
$GPIN_SPLIT_STR=chr(0x16);

?>


