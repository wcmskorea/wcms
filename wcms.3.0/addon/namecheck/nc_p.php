<?php
/**
 * Configration
 */
require_once "../../_config.php";

//리퍼러 체크
$func->checkRefer("POST");

//넘어온 값과 변수 동기화 및 validCheck
foreach($_POST AS $key=>$val)
{
	${$key} = trim($val);
	//$func->validCheck(체크할 값, 항목제목, 항목명, 체크타입, 필수항목
	//if($key == "name") { $func->vaildCheck($val, "이름", "korean", "M"); }	//실명인증시 한글로 이름을 입력했는데도 한글로 입력하라는 메시지가 나타남(청화메디파워)->20120427 임시주석처리
	if($key == "jumin1") { $func->vaildCheck($val, "주민번호 앞자리", "jumin", "M"); }
	if($key == "jumin2") { $func->vaildCheck($val, "주민번호 뒷자리", "jumin", "M"); }
}

$sSiteID = "M614";  				// 나신평에서 부여받은 사이트아이디(사이트코드)를 수정한다.
$sSitePW = "52788943";			// 나신평에서 부여받은 비밀번호 수정한다.

$cb_encode_path = "./cb_namecheck";			// cb_namecheck 모듈이 설치된 위치의 절대경로와 cb_namecheck 모듈명까지 입력한다.


	$strJumin= $_POST["jumin1"].$_POST["jumin2"];		// 주민번호
	$strName = $_POST["name"];		//이름
	
	$iReturnCode  = "";	

								// shell_exec() 와 같은 실행함수 호출부 입니다. 홑따옴표가 아니오니 이점 참고해 주세요.
	$iReturnCode = `$cb_encode_path $sSiteID $sSitePW $strJumin $strName`;	//실행함수 호출하여 iReturnCode 의 변수에 값을 담는다.		
								
								//iReturnCode 변수값에 따라 아래 참고하셔서 처리해주세요.(결과값의 자세한 사항은 리턴코드.txt 파일을 참고해 주세요~)
								//iReturnCode :	1 이면 --> 실명인증 성공 : XXX.php 로 페이지 이동. 
								//							2 이면 --> 실명인증 실패 : 주민과 이름이 일치하지 않음. 사용자가 직접 www.namecheck.co.kr 로 접속하여 등록 or 1600-1522 콜센터로 접수요청.
								//												아래와 같이 나신평에서 제공한 자바스크립트 이용하셔도 됩니다.		
								//							3 이면 --> 나신평 해당자료 없음 : 사용자가 직접 www.namecheck.co.kr 로 접속하여 등록 or 1600-1522 콜센터로 접수요청.
								//												아래와 같이 나신평에서 제공한 자바스크립트 이용하셔도 됩니다.
								//							5 이면 --> 체크썸오류(주민번호생성규칙에 어긋난 경우: 임의로 생성한 값입니다.)
								//							50이면 --> 크레딧뱅크의 명의도용차단 서비스 가입자임 : 직접 명의도용차단 해제 후 실명인증 재시도.
								//												아래와 같이 나신평에서 제공한 자바스크립트 이용하셔도 됩니다.
								//							그밖에 --> 30번대, 60번대 : 통신오류 ip: 203.234.219.72 port: 81~85(5개) 방화벽 관련 오픈등록해준다. 
								//												(결과값의 자세한 사항은 리턴코드.txt 파일을 참고해 주세요~) 

			switch($iReturnCode)
			{
				//실명인증 성공입니다. 업체에 맞게 페이지 처리 하시면 됩니다.
				case 1:

					//회원성별 변수할당
					$age							= substr(strJumin, 0, 4);
					$centry						= (substr(strJumin, 6, 1) > 2) ? 2000 : 1900;
					$age							= substr(strJumin, 0, 2) + $centry;
					//회원나이 변수할당
					$age							= date("Y") - $age;
					if($age < 19)
					{
						$func->err("('".$strName."')님은 19세 미만으로 본 서비스를 이용할 수 없습니다.", "window.self.close();");
					} else {
						$_SESSION['uage'] = $age;
						$func->err("(".iconv("CP949","UTF-8",$strName).")님 성인 인증이 완료 되었습니다.", "window.opener.location.replace('".$cfg['droot']."?cate=".$_POST['cated']."'); window.self.close();");
					}

			break;

			//리턴값 2인 사용자의 경우, www.namecheck.co.kr 의 실명등록확인 또는 02-1600-1522 콜센터로 문의주시기 바랍니다.   			
			case 2:
?>
            <script language="javascript">
               history.go(-1); 
               var URL ="http://www.creditbank.co.kr/its/its.cb?m=namecheckMismatch"; 
               //var status = "toolbar=no,directories=no,scrollbars=no,resizable=no,status=no,menubar=no, width= 640, height= 480, top=0,left=20"; 
               window.location(URL); 
            </script> 

<?
			break;
			//'리턴값 3인 사용자의 경우, www.namecheck.co.kr 의 실명등록확인 또는 02-1600-1522 콜센터로 문의주시기 바랍니다.   			
		case 3:
?>
            <script language="javascript">
               history.go(-1); 
               var URL ="http://www.creditbank.co.kr/its/its.cb?m=namecheckMismatch"; 
              window.location(URL); 
            </script> 

<?
			break;
			//리턴값 50 명의도용차단 서비스 가입자의 경우, www.creditbank.co.kr 에서 명의도용차단해제 후 재시도 해주시면 됩니다. 
			// 또는 02-1600-1533 콜센터로문의주세요.                                                                             
		case 50;
?>
            <script language="javascript">
               history.go(-1); 
               var URL ="http://www.creditbank.co.kr/its/itsProtect.cb?m=namecheckProtected"; 
               window.location(URL); 
            </script> 

<?
			break;
		default:
		//인증에 실패한 경우는 리턴코드.txt 를 참고하여 리턴값을 확인해 주세요~
?>
		   <script language='javascript'>
				alert("인증에 실패 하였습니다. 리턴코드:[<?=$iReturnCode?>]");
				history.go(-1);
		   </script>
<?
			break;
 }
?>
 
