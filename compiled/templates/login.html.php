<?php
echo '<br /><br />
<form name="frmlogin" method="post" action="'.$this->_tpl_vars['url_login'].'">
 <table class="grid" width="250" align="center">
   <caption>�û���¼</caption>
    <tr align="center">
      <td class="odd"><table width="100%" class="hide" border="0" cellspacing="0" cellpadding="5">
        <tr>
          <td width="37%" align="right" valign="middle">�û�����</td>
          <td width="63%"><input type="text" class="text" size="20" maxlength="30" style="width:120px" name="username" onKeyPress="javascript: if (event.keyCode==32) return false;" ></td>
        </tr>
        <tr>
          <td align="right" valign="middle">�ܡ��룺</td>
          <td><input type="password" class="text" size="20" maxlength="30" style="width:120px" name="password"></td>
        </tr>
        ';
if($this->_tpl_vars['show_checkcode'] == 1){
echo '
        <tr>
          <td align="right" valign="middle">��֤�룺</td>
          <td><input type="text" class="text" size="8" maxlength="8" name="checkcode">&nbsp;<img src="'.$this->_tpl_vars['url_checkcode'].'" style="cursor:pointer;" onclick="this.src=\''.$this->_tpl_vars['url_checkcode'].'?rand=\'+Math.random();"></td>
        </tr>
        ';
}
echo '
		<tr>
          <td align="right" valign="middle">��Ч�ڣ�</td>
          <td><select name="usecookie" class="select">
              <option value="0">���������</option>
              <option value="86400">����һ��</option>
			  <option value="2592000">����һ��</option>
			  <option value="315360000">����һ��</option>
            </select>
          </td>
        </tr>
        <tr>
		  <td><input type="hidden" name="action" value="login"></td>
          <td><input type="submit" class="button" value="&nbsp;��&nbsp;&nbsp;¼&nbsp;" name="submit"></td>
          </tr>
      </table></td>
    </tr>
	<tr align="center"> 
      <td class="foot"> <a href="'.$this->_tpl_vars['url_register'].'">���û�ע��</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.$this->_tpl_vars['url_getpass'].'">ȡ������</a> 
      </td>
    </tr>
  </table>
</form><br /><br />';
?>