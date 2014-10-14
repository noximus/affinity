<?php
// Version: 1.1 
// Date: 30/10/2007
function product_display_grid($product_list, $group_type, $group_sql = '', $search_sql = '') {
  global $wpdb;
  $siteurl = get_option('siteurl');
  if(get_option('permalink_structure') != '') {
    $seperator ="?";
	} else {
		$seperator ="&amp;";
	}
   
  $product_listing_data = wpsc_get_product_listing($product_list, $group_type, $group_sql, $search_sql);
  
  $product_list = $product_listing_data['product_list'];
  $output .= $product_listing_data['page_listing'];
  
  
  
  if($product_listing_data['category_id']) {
		$category_nice_name = $wpdb->get_var("SELECT `nice-name` FROM `".$wpdb->prefix."product_categories` WHERE `id` ='".(int)$product_listing_data['category_id']."' LIMIT 1");
  } else {
    $category_nice_name = '';
  }
  
  if($product_list != null) {
    if ((get_option("wpsc_selected_theme") == 'market3') && is_numeric($product_listing_data['category_id'])) {
			$output .= "<div class='breadcrumb'>";
			$output .= "<a href='".get_option('siteurl')."'>".get_option('blogname')."</a> &raquo; ";
			
			$category = $product_listing_data['category_id'];
			
			$category_info =  $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_categories WHERE id='".$category."'",ARRAY_A);
			$category_name=  $wpdb->get_var("SELECT name FROM {$wpdb->prefix}product_categories WHERE id='".$category."'");
			while ($category_info[0]['category_parent']!=0) {
				$category_info =  $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_categories WHERE id='".$category_info[0]['category_parent']."'",ARRAY_A);
			
				$output .= "<a href='".wpsc_category_url($category_info[0]['id'])."'>".$category_info[0]['name']."</a> &raquo; ";
			}
			$output .= "".$category_name."";
// 			$output .= $product_list[0]['name'];
			$output .= "</div>";
		}
  
    $output .= "<div class='productdisplay $category_nice_name'>\n\r";
    $output .= "<div class='product_grid_display'>\n\r";
    foreach($product_list as $product) {
      $num++;
      if($product['image'] !=null) {
				$image_size = @getimagesize(WPSC_THUMBNAIL_DIR.$product['image']);
				$width = $image_size[0];
			} else {
				$width = get_option('product_image_width');
				if($width < 1) {
					$width = 120;
				}
			}
      $output .= "<div class='product_grid_item product_view_{$product['id']}' style='width: ".$width."px;'>\n\r";
      if($category_data[0]['fee'] == 0) {
        if(get_option('show_thumbnails') == 1) {
					$output.="<div class='item_image'>";
          if($product['image'] !=null) {
            $image_size = @getimagesize(WPSC_IMAGE_DIR.$product['image']);
            $image_link = "index.php?productid=".$product['id']."&width=".$image_size[0]."&height=".$image_size[1]."";
            $image_link = WPSC_IMAGE_URL.$product['image']."";
            
            $output .= "<a href='".wpsc_product_url($product['id'])."'>";
            //$output .= "<a id='preview_link' href='".$image_link."' class='thickbox'  rel='".str_replace(" ", "_",$product['name'])."'>";
            
            if($product['thumbnail_image'] != null) {
              $image_file_name = $product['thumbnail_image'];
						} else {
              $image_file_name = $product['image'];
						}
                  
            $output .= "<img src='".WPSC_THUMBNAIL_URL.$image_file_name."' title='".$product['name']."' alt='".$product['name']."' id='product_image_".$product['id']."' class='product_image'/>";
            //}.pe
            $output .= "</a>";
            
            
            if(function_exists("gold_shpcrt_display_extra_images")) {
              $output .= gold_shpcrt_display_extra_images($product['id'],$product['name']);
						}
					} else {
						$output .= "<a href='".wpsc_product_url($product['id'])."'>";
						if(get_option('product_image_width') != '') {
							$output .= "<img src='$siteurl/wp-content/plugins/wp-shopping-cart/no-image-uploaded.gif' title='".$product['name']."' alt='".$product['name']."' width='".get_option('product_image_width')."' height='".get_option('product_image_height')."' id='product_image_".$product['id']."' class='product_image' />";
						} else {
							$output .= "<img src='$siteurl/wp-content/plugins/wp-shopping-cart/no-image-uploaded.gif' title='".$product['name']."' alt='".$product['name']."' id='product_image_".$product['id']."' class='product_image' />";
						}
						$output .= "</a>";
					}
          
					$output.="</div>";
          if(function_exists('drag_and_drop_items') && (get_option('show_images_only') != 1)) {
            $output .= drag_and_drop_items("product_image_".$product['id']);
					}          
				}
			}
      if(get_option('show_images_only') != 1) {
        $output .= "<div class='grid_product_info'>\n\r";
        $output .= "<div class='product_text'>";
        if(function_exists('wpsc_grid_title_and_price')) {
          $output .= wpsc_grid_title_and_price($product);
        }  else {
					$output .= "<strong>". stripslashes($product['name']) . "</strong><br />\n\r";
					if(($product['description'] != '') && (get_option('display_description') == 1)) {
						$output .= "<p class='griddescription'>".wpautop(stripslashes($product['description'])) . "</p>\n\r";
					} 

					if($product['special']==1) {
						$output .= TXT_WPSC_PRICE.": " . nzshpcrt_currency_display(($product['price'] - $product['special_price']), $product['notax'],false,$product['id']) . "\n\r";
					} else {
						$output .= TXT_WPSC_PRICE.": " . nzshpcrt_currency_display($product['price'], $product['notax']) . "\n\r";
					}
				}
        $output .= "</div>\n\r";
        $output .= "</div>\n\r";
        if(get_option('display_variations') == 1) {
					$variations_procesor = new nzshpcrt_variations;    
					$variations_output = $variations_procesor->display_product_variations($product['id'],false, false, true);
        }
        $output .= "<div class='grid_more_info'>\n\r";
        //$output .= "<a href='".get_option('product_list_url').$seperator."product_id=".$product['id']."' />";
    
        $output .= "<form id='product_".$product['id']."' name='product_".$product['id']."' method='post' action='".get_option('product_list_url').$seperator."category=".$_GET['category']."' onsubmit='submitform(this);return false;' >";
        $output .= $variations_output[0];
        $output .= "<input type='hidden' name='prodid' value='".$product['id']."' />";
        $output .= "<input type='hidden' name='item' value='".$product['id']."' />";
  		
					if(get_option('display_addtocart') == 1) {
								if(function_exists('wpsc_theme_html')) {
							$wpsc_theme = wpsc_theme_html($product);
						}
					$output .= "<input type='hidden' name='item' value='".$product['id']."' />";
					//AND (`quantity_limited` = '1' AND `quantity` > '0' OR `quantity_limited` = '0' )
					if(($product['quantity_limited'] == 1) && ($product['quantity'] < 1) && ($variations_output[1] === null)) {
						if (get_option("wpsc_selected_theme")!='market3') {
								$output .= "<p class='soldout'>".TXT_WPSC_PRODUCTSOLDOUT."</p>";
							}
						} else {
							if ((get_option('hide_addtocart_button') != 1) && (get_option('addtocart_or_buynow') == 0)) {
								if(isset($wpsc_theme) && is_array($wpsc_theme) && ($wpsc_theme['html'] !='')) {
									$output .= $wpsc_theme['html'];
								} else {
									$output .= "<input type='submit' id='product_".$product['id']."_submit_button' class='wpsc_buy_button' name='Buy' value='".TXT_WPSC_ADDTOCART."'  />";
								}
							}
						}
				}

        $output .= "</form>";
        
				if(get_option('display_moredetails') == 1) {
							$output .= "<form method='post' action='".wpsc_product_url($product['id'])."'>";
					$output .= "<button type='submit' class='wpsc_details_button' id='more_".$product['id']."_submit_button'>More Details</button>";
					$output .= "</form>";
				}

        //$output .= "<img src='$siteurl/wp-content/plugins/wp-shopping-cart/images/grid_plus.png' alt='' title='' />\n\r";
        //$output .= "</a>";
        $output .= "</div>\n\r";
			}
      
      ob_start();
      do_action('wpsc_product_addons', $product['id']);
      $output .= ob_get_contents();
      ob_end_clean();
      $output .= "</div>\n\r";
      if(function_exists('drag_and_drop_items'))
        {
        $output .= drag_and_drop_items("product_image_".$product['id']);
        }           
      }
    $output .= "</div>\n\r";
    if ((get_option('wpsc_page_number_position')==2) || (get_option('wpsc_page_number_position')==3))
    	$output .= $product_listing_data['page_listing'];
    $output .= "</div>\n\r";
    }
    else
      {
      if($_GET['product_search'] != null)
        {
        $output .= "<br /><strong class='cattitles'>".TXT_WPSC_YOUR_SEARCH_FOR." \"".$_GET['product_search']."\" ".TXT_WPSC_RETURNED_NO_RESULTS."</strong>";
        }
        else
          {
          $output .= "<p>".TXT_WPSC_NOITEMSINTHIS." ".$group_type.".</p>";
          }
      }
  return $output;
  }
  
function sidebar_list_categories($category_id = null)
  {
  global $wpdb;  
  if(!is_numeric($category_id))
    {
    if(is_numeric($_GET['category']))
      {
      $category_id = $_GET['category'];
      }
      else
        {
        $category_id = get_option('default_category');
        }
    }
    else
      {
      $GLOBALS['wpshpcrt_category_tag'] = $category_id;
      }
  $category_parent_sql = "SELECT `category_parent` FROM `".$wpdb->prefix."product_categories` WHERE `active`='1' AND `id` = '".$category_id."' LIMIT 1";
  $parent_category_id = $wpdb->get_var($category_parent_sql);
  if($parent_category_id > 0)
    {
    $category_id = $parent_category_id;
    }
  $category_name = $wpdb->get_var("SELECT `name` FROM `".$wpdb->prefix."product_categories` WHERE `active`='1' AND `id` = '".$category_id."' LIMIT 1");
  $output .= "
      <li><h2>".$category_name."</h2>
        <ul>\n\r";  
  $category_sql = "SELECT * FROM `".$wpdb->prefix."product_categories` WHERE `active`='1' AND `category_parent` = '".$category_id."' ORDER BY `id`";
  $category_list = $wpdb->get_results($category_sql,ARRAY_A);
  foreach((array)$category_list as $category)
    {
    $output .= "      <li> <a href='?category=".$category['id']."'>".$category['name']."</a> </li>\n\r";
    }  
  $output .= "
        </ul>
      </li>\n\r";
  return $output;
  }  
?>