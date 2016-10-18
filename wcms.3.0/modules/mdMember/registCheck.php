<div class="cube">
  <p class="pd3 bg_gray small_gray"><span>-. 실명인증을 위해 이름과 주민번호를 입력해 주십시오. 입력된 주민번호는 저장되지 않습니다.</span></p>
  <p class="pd3 bg_gray small_gray"><span>-. 주민번호는 (-)없이 13자리를 입력해 주세요.</span> 예) 8411202856XXX</p>
  <?php if($func->checkModule('mdMileage') && $mileage > 0) { print('<p class="pd3 bg_gray small_orange">-. 회원가입 완료시 적립금 <strong>'.number_format($mileage).'포인트</strong>를 적립하여 드립니다!</p>'); } ?>
  <div class="line">
    <div class="realname" style="width:300px;">
      <fieldset>
        <legend>실명인증</legend>
        <table summary="회원가입을 위한 실명확인 입니다.">
        <caption>실명인증 서식</caption>
        <col width="100" />
        <col width="140" />
        <col width="60" />
        <tbody>
        <!--<tr>
          <th><p class="center"><label for="division">구　　분</label></p></th>
          <td><input type="radio" id="division1" name="memType" value="P" checked="checked" />&nbsp;<label for="division1">일반</label>&nbsp;&nbsp;&nbsp;<input type="radio" id="division2" name="memType" value="C" />&nbsp;<label for="division2">기업</label></td>
          <td rowspan="3"><span class="button bred strong"><button type="submint">실명확인</button></span></td>
        </tr>-->
        <tr>
          <th><p class="center"><label for="username">이　　름</label></p></th>
          <td colspan="2"><input type="text" id="username" name="username" class="input_blue wrap120" req="required" opt="korean" title="이름" /></td>
        </tr>
        <tr>
          <th><p class="center"><label for="usercode">주민번호</label></p></th>
          <td><input type="password" id="usercode" name="usercode" class="input_blue wrap120" req="required" opt="idcode" title="주민번호" /></td>
          <td><span class="button bred strong"><button type="submint">실명확인</button></span></td>
        </tr>
        </tbody>
        </table>
      </fieldset>
    </div><!-- .realname end -->
  </div><!-- .line end -->
</div><!-- .cube end -->
