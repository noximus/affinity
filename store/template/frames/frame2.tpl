<div id="top_navi">
   <h1><a href="index.php">Home</a> &gt; <a href="index.php?action=products">Store</a> &gt; <a href="index.php?action=frame&view={$view}&ar={$ar}">Kissena</a></h1>
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
   <h2>“Kissena”</h2>
   <h1>Price: $1000.00, msrp, frame/fork</h1>
   <div id="description">
      <ul>
         <li>Aero Track 7005 aircraft grade Aluminum tubing</li>
         <li>1 1/8th integrated headset, track specific carbon fork, alloy steerer</li>
         <li>Integrated seat mast with down tube cut out</li>
         <li>“A” Clamp for integrated seat tube set up</li>
         <li>27.2 Wedge clamp option for non-integrated option</li>
         <li>Smooth welds, replaceable dropouts, candy clear paint</li>
         <li>Stars and Stripes award winning frame design</li>
         <li>Affinity team racer Andrew Lacorte won the 2009 US National sprint title on his Affinity Kissena</li>
         <li>World Champion candy clear, red, black chrome, teal, gold and green 2009/2010 colors</li>
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