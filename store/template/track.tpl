<link href="css/affinity.css" rel="stylesheet" type="text/css" />

<div id="top_navi">
   <div id="head"><h1><a href="index.php?action=index">Home</a> &gt; <a href="index.php?action=products">Store</a> &gt; <a href="index.php?action=trackorder">Order Tracking</a></h1></div>
</div>
<h2>Order Tracking</h2><br />
<h5 class="status">Current status: {$status}</h5>
<div id="cart">
   {$table}
</div>
<h5 class="status">Additional data from merchant: </h5>
<div class="text">{$info}</div>
