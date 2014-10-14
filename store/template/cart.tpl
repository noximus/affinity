<div id="top_navi">
   <div id="head"><h1><a href="index.php?action=index">Home</a> &gt; <a href="index.php?action=products">Store</a> &gt; <a href="index.php?action=viewcart">Cart</a></h1></div>
</div>
<h2>Shopping cart</h2>
{if $show ne 0}
<div id="cart">
   <h5>Use your shopping cart to store the items you wish to purchase.<br />You can remove items at any time.<br />Click Checkout when you're ready to complete your purchase.</h5><br />
   {$table}
</div>
<div style="text-align:right; width:100%;">
   <form style="margin-right:60px;" action='index.php?action=checkout' method='post'>
      <input type='image' name='submit' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif' border='0' align='top' alt='PayPal'/>
   </form>
</div>
{else}
<div id="cart">
   <h5>Your shoping cart is empty. Please add some wares.</h5>
</div>
{/if}