<?php 

$sSiteID = "M614";  	// �����򿡼� �ο����� ����Ʈ���̵�(����Ʈ�ڵ�)�� �����Ѵ�.
$sSitePW = "52788943";    // �����򿡼� �ο����� ��й�ȣ �����Ѵ�.

$cb_encode_path = "./cb_namecheck";			// cb_namecheck ����� ��ġ�� ��ġ�� �����ο� cb_namecheck ������� �Է��Ѵ�.


	$strJumin= $_POST["jumin1"].$_POST["jumin2"];		// �ֹι�ȣ
	$strName = $_POST["name"];		//�̸�
	
	$iReturnCode  = "";	

								// shell_exec() �� ���� �����Լ� ȣ��� �Դϴ�. Ȭ����ǥ�� �ƴϿ��� ���� ������ �ּ���.
	$iReturnCode = `$cb_encode_path $sSiteID $sSitePW $strJumin $strName`;	//�����Լ� ȣ���Ͽ� iReturnCode �� ������ ���� ��´�.		
								
								//iReturnCode �������� ���� �Ʒ� �����ϼż� ó�����ּ���.(������� �ڼ��� ������ �����ڵ�.txt ������ ������ �ּ���~)
								//iReturnCode :	1 �̸� --> �Ǹ����� ���� : XXX.php �� ������ �̵�. 
								//							2 �̸� --> �Ǹ����� ���� : �ֹΰ� �̸��� ��ġ���� ����. ����ڰ� ���� www.namecheck.co.kr �� �����Ͽ� ��� or 1600-1522 �ݼ��ͷ� ������û.
								//												�Ʒ��� ���� �����򿡼� ������ �ڹٽ�ũ��Ʈ �̿��ϼŵ� �˴ϴ�.		
								//							3 �̸� --> ������ �ش��ڷ� ���� : ����ڰ� ���� www.namecheck.co.kr �� �����Ͽ� ��� or 1600-1522 �ݼ��ͷ� ������û.
								//												�Ʒ��� ���� �����򿡼� ������ �ڹٽ�ũ��Ʈ �̿��ϼŵ� �˴ϴ�.
								//							5 �̸� --> üũ�����(�ֹι�ȣ������Ģ�� ��߳� ���: ���Ƿ� ������ ���Դϴ�.)
								//							50�̸� --> ũ������ũ�� ���ǵ������� ���� �������� : ���� ���ǵ������� ���� �� �Ǹ����� ��õ�.
								//												�Ʒ��� ���� �����򿡼� ������ �ڹٽ�ũ��Ʈ �̿��ϼŵ� �˴ϴ�.
								//							�׹ۿ� --> 30����, 60���� : ��ſ��� ip: 203.234.219.72 port: 81~85(5��) ��ȭ�� ���� ���µ�����ش�. 
								//												(������� �ڼ��� ������ �����ڵ�.txt ������ ������ �ּ���~) 

        switch($iReturnCode){
        //�Ǹ����� �����Դϴ�. ��ü�� �°� ������ ó�� �Ͻø� �˴ϴ�.
    	case 1:
        echo $iReturnCode;
        
?>
			<script language='javascript'>     
      	alert("��������!! ^^");          
      </script>
      
                                
			<!-- ������ ó���� �ϽǶ����� �Ʒ��Ͱ��� ó���ϼŵ� �˴ϴ�. �̵��� �������� �����ؼ� ����Ͻø� �˴ϴ�. 
			<html>
				<head>
				</head>
				<body onLoad="document.form1.submit()">
					<form name="form1" method="post" action=XXX.php>
						<input type="hidden" name="jumin1" value="<?=$jumin1?>">
						<input type="hidden" name="jumin2" value="<?=$jumin2?>">
						<input type="hidden" name="name" value="<?=$strName?>">
					</form>
				</body>
			</html>
			-->
     
<?
			break;
			//���ϰ� 2�� ������� ���, www.namecheck.co.kr �� �Ǹ���Ȯ�� �Ǵ� 02-1600-1522 �ݼ��ͷ� �����ֽñ� �ٶ��ϴ�.   			
		case 2:   
?>
            <script language="javascript">
               history.go(-1); 
               var URL ="http://www.creditbank.co.kr/its/its.cb?m=namecheckMismatch"; 
               var status = "toolbar=no,directories=no,scrollbars=no,resizable=no,status=no,menubar=no, width= 640, height= 480, top=0,left=20"; 
               window.open(URL,"",status); 
            </script> 

<?
			break;
			//'���ϰ� 3�� ������� ���, www.namecheck.co.kr �� �Ǹ���Ȯ�� �Ǵ� 02-1600-1522 �ݼ��ͷ� �����ֽñ� �ٶ��ϴ�.   			
		case 3:
?>
            <script language="javascript">
               history.go(-1); 
               var URL ="http://www.creditbank.co.kr/its/its.cb?m=namecheckMismatch"; 
               var status = "toolbar=no,directories=no,scrollbars=no,resizable=no,status=no,menubar=no, width= 640, height= 480, top=0,left=20"; 
               window.open(URL,"",status); 
            </script> 

<?
			break;
			//���ϰ� 50 ���ǵ������� ���� �������� ���, www.creditbank.co.kr ���� ���ǵ����������� �� ��õ� ���ֽø� �˴ϴ�. 
			// �Ǵ� 02-1600-1533 �ݼ��ͷι����ּ���.                                                                             
		case 50;
?>
            <script language="javascript">
               history.go(-1); 
               var URL ="http://www.creditbank.co.kr/its/itsProtect.cb?m=namecheckProtected"; 
               var status = "toolbar=no,directories=no,scrollbars=no,resizable=no,status=no,menubar=no, width= 640, height= 480, top=0,left=20"; 
               window.open(URL,"",status); 
            </script> 

<?
			break;
		default:
		//������ ������ ���� �����ڵ�.txt �� �����Ͽ� ���ϰ��� Ȯ���� �ּ���~
?>
		   <script language='javascript'>
				alert("������ ���� �Ͽ����ϴ�. �����ڵ�:[<?=$iReturnCode?>]");
				history.go(-1);
		   </script>
<?
			break;
 }
?>
 
