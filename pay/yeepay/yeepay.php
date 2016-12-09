<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>无标题文档</title>
<style type="text/css">
<!--
body,td,th {
	font-size: 12px;
}
-->
</style></head>

<body>
<form method="post" action="req.php">
<table width="600" border="1" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse" bordercolor="#c6c6c6">
  <tr>
    <td height="25" colspan="2" align="center"><strong>订单确认</strong></td>
  </tr>
  <tr>
    <td width="137" height="25" align="right">订单编号：</td>
    <td width="457">
    <input name="ordernum" type="text" id="ordernum" value="<?=$_REQUEST[ordernum]?>" readonly="readonly" /></td>
  </tr>
  <tr>
    <td height="25" align="right">订购商品：</td>
    <td>
    <input name="pname" type="text" id="pname" value="<?=$_REQUEST[pname]?>" readonly="readonly" /></td>
  </tr>
  <tr>
    <td height="25" align="right">订单金额：</td>
    <td>
    <input name="price" type="text" id="price" value="<?=$_REQUEST[price]?>" readonly="readonly" />
    <input name="furl" type="hidden" id="furl" value="<?=$_REQUEST[furl]?>" /></td>
  </tr>
  <tr>
    <td height="25" colspan="2" align="center">请选择对应的网上银行进行支付</td>
  </tr>
  <tr>
    <td height="25" colspan="2" align="center"><table width="95%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="25%" height="20" align="left"><input name="pd_FrpId" type="radio" value="ICBC-NET-B2C" checked="checked" />
          中国工商银行</td>
        <td width="25%" align="left"><input type="radio" name="pd_FrpId" value="CMBCHINA-NET-B2C" />
          招商银行</td>
        <td width="25%" align="left"><input type="radio" name="pd_FrpId" value="ABC-NET-B2C" />
          中国农业银行</td>
        <td width="25%" align="left"><input type="radio" name="pd_FrpId" value="CCB-NET-B2C" />
          中国建设银行</td>
      </tr>
      <tr>
        <td height="20" align="left"><input type="radio" name="pd_FrpId" value="BCCB-NET-B2C" />
          北京银行</td>
        <td align="left"><input type="radio" name="pd_FrpId" value="BOCO-NET-B2C" />
          交通银行</td>
        <td align="left"><input type="radio" name="pd_FrpId" value="CIB-NET-B2C" />
          兴业银行</td>
        <td align="left"><input type="radio" name="pd_FrpId" value="NJCB-NET-B2C" />
          南京银行</td>
      </tr>
      <tr>
        <td height="20" align="left"><input type="radio" name="pd_FrpId" value="CMBC-NET-B2C" />
          中国民生银行</td>
        <td align="left"><input type="radio" name="pd_FrpId" value="CEB-NET-B2C" />
          中国广大银行</td>
        <td align="left"><input type="radio" name="pd_FrpId" value="BOC-NET-B2C" />
          中国银行</td>
        <td align="left"><input type="radio" name="pd_FrpId" value="PINGANBANK-NET" />
          平安银行</td>
      </tr>
      <tr>
        <td height="20" align="left"><input type="radio" name="pd_FrpId" value="CBHB-NET-B2C" />
          渤海银行</td>
        <td align="left"><input type="radio" name="pd_FrpId" value="HKBEA-NET-B2C" />
          BEA东亚银行</td>
        <td align="left"><input type="radio" name="pd_FrpId" value="NBCB-NET-B2C" />
          宁波银行</td>
        <td align="left"><input type="radio" name="pd_FrpId" value="ECITIC-NET-B2C" />
          中信银行</td>
      </tr>
      <tr>
        <td height="20" align="left"><input type="radio" name="pd_FrpId" value="SDB-NET-B2C" />
          深圳发展银行</td>
        <td align="left"><input type="radio" name="pd_FrpId" value="GDB-NET-B2C" />
          广发银行</td>
        <td align="left"><input type="radio" name="pd_FrpId" value="SHB-NET-B2C" />
          上海银行</td>
        <td align="left"><input type="radio" name="pd_FrpId" value="SPDB-NET-B2C" />
          上海浦东发展银行</td>
      </tr>
      <tr>
        <td height="20" align="left"><input type="radio" name="pd_FrpId" value="POST-NET-B2C" />
          中国邮政</td>
        <td align="left"><input type="radio" name="pd_FrpId" value="BJRCB-NET-B2C" />
          北京农村商业银行</td>
        <td align="left"><input type="radio" name="pd_FrpId" value="SCCB-NET-B2C" />
河北银行</td>
        <td align="left"><input type="radio" name="pd_FrpId" value="CZ-NET-B2C" />
          浙商银行</td>
      </tr>
      <tr>
        <td height="20" align="left"><input type="radio" name="pd_FrpId" value="HZBANK-NET-B2C" />
          杭州银行</td>
        <td align="left"><input type="radio" name="pd_FrpId" value="SHRCB-NET-B2C" />
          上海农村商业银行</td>
        <td align="left"><input type="radio" name="pd_FrpId" value="NCBBANK-NET-B2C" />
          南洋商业银行</td>
        <td align="left">&nbsp;</td>
      </tr>

    </table></td>
  </tr>
  <tr>
    <td height="25" colspan="2" align="center"><input type="submit" name="Submit" value="确认支付" /></td>
  </tr>
</table>
</form>
</body>
</html>
