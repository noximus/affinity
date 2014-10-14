<?php header('Content-Type: text/html; charset=utf-8'); 
//--------------------------Set these paramaters--------------------------

// Subject of email sent to you.
$subject = 'Email from affinitycycles.com';

// Your email address. This is where the form information will be sent.
$emailadd = 'info@affinitycycles.com';

// Where to redirect after form is processed.
$url = 'contact.html';

// Makes all fields required. If set to '1' no field can not be empty. If set to '0' any or all fields can be empty.
$req = '1';

// --------------------------Do not edit below this line--------------------------
$text = "Email from affinitycycles.com/\n\n";
$space = ' ';
$line = '
';
foreach ($_POST as $key => $value)
{
if ($req == '1')
{
if ($value == '')
{echo "$key is empty";die;}
}
$j = strlen($key);
if ($j >= 20)
{echo "Name of form element $key cannot be longer than 40 characters";die;}
$j = 20 - $j;
for ($i = 1; $i <= $j; $i++)
{$space .= ' ';}
$value = str_replace('\n', "$line", $value);
$conc = "{$key}:$space{$value}$line";
$text .= $conc;
$space = ' ';
}
mail($emailadd, $subject, $text, 'From: '.$emailadd.'');
echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL='.$url.'">';
?>