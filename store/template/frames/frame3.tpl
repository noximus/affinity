<div id="top_navi">
   <h1><a href="index.php">Home</a> &gt; <a href="index.php?action=products">Store</a> &gt; <a href="index.php?action=frame&view={$view}&ar={$ar}">538</a></h1>
   <div id="cart1" style="cursor:pointer;" onclick="location.href='index.php?action=viewcart'">
       <table width="65" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="15" valign="middle">{$item}</td>
           </tr>
           <tr>
            <td height="15" valign="middle">{$total}</td>
           </tr>
        </table>
    </div>
</div>
<div id="slideshow">
   <img src="{$path}images/slideshow.jpg" width="440" height="325" />
</div>
<div id="product_right">
   <h2>“538”</h2>
   <h1>Price: $500.00, msrp, frame/fork</h1>
   <div id="description">
      <ul>
         <li>4130 Cromo Tubing</li>
         <li>74/75 degree track geometry, horizontal top tube/ brazed rear dropout</li>
         <li>120mm rear spacing</li>
         <li>Affinity lugged 1 inch straight bladed fork chrome plated</li>
         <li>Fits 27.2 seat post</li>
         <li>Frame and Fork drilled for brakes</li>
         <li>Steel metallic blue, lazer blue metallic 2009/2010 colors</li>
      </ul>
   </div>
   <h3>Download spec sheet </h3>
   <form id="form1" name="form1" method="post" action="index.php?action=addtocart&view={$view}&ar={$ar}">
      <table width="135" border="0" cellspacing="0" cellpadding="0">
         <tr height="20px">
            <td>
            	<img style="cursor:pointer;" src="{$path}images/cart.gif" width="85" height="20" alt="Add to cart" onclick="document.form1.submit();" />
            </td>
            <td style="margin:0px; vertical-align:top;">
               <input name="ilosc" type="text"  id="ilosc" tabindex="1" value="1" />
            </td>
         </tr>
      </table>
   </form>
</div>