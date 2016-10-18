<?php
session_start();

//**********************************************************************************************
//한국신용평가정보 공공기관용 안심실명확인 서비스
//작성일 : 2006.10.18
//**********************************************************************************************

$cb_decode_path = $_SERVER[DOCUMENT_ROOT]."/modules/mdMember/embed/GPIN/SafeNC.exe"; // SafeNC 모듈이 설치된 위치 (웹디렉토리에 올리셔도 무방합니다.)
                                      // 데이타를 암호화,복호화 하는 모듈입니다.
$enc_data       = $enc_data;					// 암호화한 데이타
$pRes			      = "res";						  // 값을 변경하시면 안됩니다.

//echo "리턴데이타 : $enc_data <br><br><br>";
if ($enc_data != "")
{
  //**********************************************************************************************
  // ReturnURL로부터 수신된 암호화 데이터(AES_CBC_PAD, SHA256, BASE64 Encoded Data)
  // SafeNC 파일을 실행하여 복호화된 값을 가져옵니다.
  //**********************************************************************************************
  $dec_data = `$cb_decode_path $pRes $enc_data`;

  if ($dec_data == -1){
    $returnMsg  = "암/복호화 시스템 오류";
  }else if ($dec_data == -4){
    $returnMsg  = "복호화 처리 오류";
  }else if ($dec_data == -5){
    $returnMsg  = "HASH값 불일치 - 복호화 데이터는 리턴됨";
  }else if ($dec_data == -6){
    $returnMsg  = "복호화 데이터 오류";
  }else if ($dec_data == -9){
    $returnMsg  = "입력값 오류";
  }
  else
  {
    // 복호화가 정상적으로 이루어졌을경우 데이타를 파싱합니다.(구분자는 ^ 입니다.)
    $arrData = split("\^",$dec_data);
    $iCount = count($arrData);
    $returnMsg = "";

    if ($iCount >= 5)
    {
      $rtnNameChk = $arrData[0];
      if($rtnNameChk != 1)
      {
        if($rtnNameChk == 2)
        {
          error("실명확인($rtnNameChk): 본인정보가 아닙니다.");
        } else if ($rtnNameChk == 3)
        {
          error("실명확인($rtnNameChk): 실명확인이 불가능한 정보입니다.\\n\\n한국신용평가정보(주) 콜센타(http://www.namecheck.co.kr/per_callcenter.asp)에서 확인 바랍니다.");
        } else
        {
          echo "실명확인($rtnNameChk): 시스템장애로 실명확인이 불가능합니다.<BR>$cb_encode_path $sSiteID $sSitePW $strJumin $strName";
          exit();
        }
      }
      // 실명확인처리결과코드^SafeID^이름^생년월일(YYYYMMDD)^성별구분(남:M 여:F 외국인남:A 외국인여:B)^고객사 요청 Sequence^Reserved1^Reserved2^Reserved3
      if ($arrData[0] == "1") {
        $virtualNo  = $arrData[1];
        $realName   = $arrData[2];
        session_register("virtualNo");
        session_register("realName");
      }else if ($arrData[0] == "2") {
          $sMsg = "본인아님";
      }else if ($arrData[0] == "3") {
          $sMsg = "자료없음";
      }else if ($arrData[0] == "4") {
          $sMsg = "시스템오류";
      }else if ($arrData[0] == "5") {
          $sMsg = "체크섬오류";
      }else if ($arrData[0] == "10") {
          $sMsg = "사이트코드오류";
      }else if ($arrData[0] == "11") {
          $sMsg = "중단된사이트";
      }else if ($arrData[0] == "12") {
          $sMsg = "비밀번호오류";
      }else{
        $sMsg = "리턴코드 : [$arrData[0]] 기타오류입니다.";
      }

      if($arrData[0] == "1")
      {
        print('<script type="text/javascript">
        <!--
          opener.location.replace("/?cate=000002002");
          self.close();
        //-->
        </script>');
      }
      else
      {
        print('<script type="text/javascript">
        <!--
          alert("'.$sMsg.'");
          self.close();
        //-->
        </script>');
      }
      // 실명확인 처리결과가 1(본인맞음)인 경우는 회원테이블에 데이타를 저장하시면 됩니다.
      /*echo "실명확인 처리결과 = [$arrData[0]] : $sMsg <BR>";   //실명확인 처리결과코드 혹은 오류코드(음수)
      echo "인증코드 = [$arrData[1]]<BR>";
      echo "성명 = [$arrData[2]]<BR>";
      echo "생년월일 = [$arrData[3]]<BR>";
      echo "성별 = [$arrData[4]]<BR>";
      echo "REQUEST_SEQ = [$arrData[5]]<BR>";
      echo "RESERVED1 = [$arrData[6]]<BR>";
      echo "RESERVED2 = [$arrData[7]]<BR>";
      echo "RESERVED3 = [$arrData[8]]<BR>";
      */
    } else
    {
      $returnMsg  = "복호화 오류";
    }
  }
} else
{
  echo "처리할 데이타가 없습니다.";
}
?>
