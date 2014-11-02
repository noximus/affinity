<?php
$nzshpcrt_gateways[$num]['name'] = 'Paystation';
$nzshpcrt_gateways[$num]['internalname'] = 'paystation';
$nzshpcrt_gateways[$num]['function'] = 'gateway_paystation';
$nzshpcrt_gateways[$num]['form'] = "form_paystation";
$nzshpcrt_gateways[$num]['submit_function'] = "submit_paystation";

function gateway_paystation($seperator, $sessionid)
  {
  $url = "https://www.paystation.co.nz/dart/darthttp.dll?paystation&pi=".get_option('paystation_id')."&ms=".$sessionid."&am=".(nzshpcrt_overall_total_price($_SESSION['delivery_country'])*100)."";
  $_SESSION['checkoutdata'] = '';
  header("Location: $url");
  exit();
  }

function submit_paystation()
  {
  update_option('paystation_id', $_POST['paystation_id']);
  return true;
  }

function form_paystation()
  {
  return "<tr>
      <td>
      Paystation ID
      </td>
      <td>
      <input type='text' size='40' value='".get_option('paystation_id')."' name='paystation_id' />
      </td>
    </tr>";
  }
?>