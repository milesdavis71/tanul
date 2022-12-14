<?php 
	global $post,$product;
	$background_image='';
	$image_html = "";
	if ( has_post_thumbnail() ) {
			$image_html = wp_get_attachment_image( get_post_thumbnail_id(), 'shop_catalog' );					
	} else if ( wc_placeholder_img_src() ) {
			$image_html = wc_placeholder_img( 'shop_catalog' );
	}
	$product_id=get_the_ID();
	$data_attr='';
	if($layout=='metro'){
		if((!empty($display_thumbnail) && $display_thumbnail=='yes') && !empty($thumbnail)){
			if ( has_post_thumbnail() ) {
				$src=get_the_post_thumbnail_url($product_id,$thumbnail);
				$data_attr='style="background:url('.$src.') #f7f7f7;"';				
			}else{
				$data_attr = theplus_loading_image_grid($product_id,'background');				
			}
		}else{
			if ( has_post_thumbnail() ) {
				$data_attr=theplus_loading_bg_image($product_id);
			}else{
				$data_attr = theplus_loading_image_grid($product_id,'background');
			}
		}
	}
	$catalog_mode='';
	remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="product-list-content">
	
		<?php if($layout!='metro'){ ?>
			<div class="product-content-image">
			<?php
				$out_of_stock_val='';
				if(!empty($out_of_stock)){
					$out_of_stock_val=$out_of_stock;
				}
				if(!empty($b_dis_badge_switch) && $b_dis_badge_switch=='yes'){
					do_action('theplus_product_badge',$out_of_stock_val);
				}
				$attachment_ids = $product->get_gallery_image_ids();
				if ($attachment_ids) {
					if($layout!='metro'){ ?>
						<a href="<?php echo esc_url(get_the_permalink()); ?>" title="<?php the_title_attribute(array('echo' => 0)); ?>" class="product-image">
						<?php if ( ! get_post_meta( $attachment_ids[0], '_woocommerce_exclude_image', true ) ) { 
								if(!empty($hover_image_on_off) && $hover_image_on_off=='yes'){
							?>
								<span class="product-image hover-image"><?php echo wp_get_attachment_image( $attachment_ids[0], 'shop_catalog' ); ?></span>
							<?php }
						} ?>
						<?php echo $image_html; ?>
						</a>
					<?php }
				}else{
					if($layout!='metro'){
						if ( has_post_thumbnail() ) { ?>
							<a href="<?php echo esc_url(get_the_permalink()); ?>" class="product-image" title="<?php the_title_attribute(array('echo' => 0)); ?>">
								<?php include THEPLUS_INCLUDES_URL. 'product/format-image.php'; ?>
							</a>
						<?php }else{ ?>
							<div class="product-image">
								<a href="<?php echo esc_url(get_the_permalink()); ?>" class="product-image" title="<?php the_title_attribute(array('echo' => 0)); ?>"><?php echo theplus_loading_image_grid($product_id); ?></a>
							</div>
						<?php }
					}
				} ?>
					
				<?php if ($layout!='metro'  && (!empty($display_cart_button) && $display_cart_button=='yes')) { ?>
					<div class="wrapper-cart-hover-hidden add-cart-btn">
						<?php $_product = wc_get_product( $product_id );
						if( $_product->is_type( 'simple' ) ) { ?>
							<div class="product-add-to-cart" ><a title="<?php echo esc_attr__($dcb_single_product); ?>" href="?add-to-cart=<?php echo esc_attr($product_id); ?>" rel="nofollow" data-product_id="<?php echo esc_attr($product_id); ?>" data-product_sku="" class="add_to_cart add_to_cart_button product_type_simple ajax_add_to_cart"><span class="text"><span><?php echo esc_html__($dcb_single_product); ?></span></span><span class="icon"><span class="sr-loader-icon"></span><span class="check"></span></span></a></div>
						<?php }else{ ?>
							<div class="product-add-to-cart" ><a rel="nofollow" href="<?php echo esc_url(get_the_permalink()); ?>" data-quantity="1" data-product_id="<?php echo esc_attr($product_id); ?>" data-product_sku="" class="add_to_cart add_to_cart_button product_type_simple " data-added-text=""><span class="text"><span><?php echo esc_html__($dcb_variation_product); ?></span></span><span class="icon"><span class="sr-loader-icon"></span><span class="check"></span></span></a></div>
						<?php } ?>
					</div>
				<?php }	
				
				/*yith*/
				if(!empty($display_yith_list) && $display_yith_list=='yes'){
					if($layout!='metro'){ ?>
						<div class="tp-yith-wrapper">
						<?php
							/*yith compare start*/
							if(!empty($display_yith_compare) && $display_yith_compare=='yes'){
								if( is_plugin_active('yith-woocommerce-compare/init.php') ){ ?>
									<div class="tp-yith-inner post-yith-wc-compare">							
												<a href="<?php echo home_url(); ?>?action=yith-woocompare-add-product&id=<?php echo $product_id; ?>" class="compare button" data-product_id="<?php echo $product_id; ?>" rel="nofollow"><i aria-hidden="true" class="fas fa-exchange-alt"></i></a>
									</div>
								<?php }
							}
							/*yith compare end*/
							
							/*yith add to wishlist start*/
							if(!empty($display_yith_wishlist) && $display_yith_wishlist=='yes'){
								if( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ){ ?>
										<div class="tp-yith-inner  post-yith-wc-wishlist">
											<?php echo do_shortcode('[yith_wcwl_add_to_wishlist icon="fas fa-heart" label="" already_in_wishslist_text="" browse_wishlist_text=""]' ); ?>
										</div>
							   <?php } 
							}
							/*yith add to wishlist end*/
							
							/*yith quickview start*/
							if(!empty($display_yith_quickview) && $display_yith_quickview=='yes'){
								if( defined( 'YITH_WCQV_VERSION' ) && is_plugin_active('yith-woocommerce-quick-view/init.php') ){ ?>
									<div class="tp-yith-inner post-yith-wc-quickview" style="opacity:0;">
											<?php echo do_shortcode('[yith_quick_view product_id=$product_id icons="fas fa-eye"]'); ?>
									</div>
								 <?php 
								 }
							}
							/*yith quickview end*/					
						?> </div><?php
					}
				}
				/*yith*/
				?>
			</div>
		<?php } ?>
		
		<div class="post-content-bottom">
			<?php 	if(!empty($display_catagory) && $display_catagory=='yes'){
						include THEPLUS_INCLUDES_URL. 'product/post-meta-catagory.php'; 
					} ?>
			<?php include THEPLUS_INCLUDES_URL. 'product/post-meta-title.php'; ?>
			
			<?php 	if(!empty($display_rating) && $display_rating=='yes'){
						include THEPLUS_INCLUDES_URL. 'product/post-rating.php';
					} ?>
					
			<?php  if ($product->is_in_stock() ){
				include THEPLUS_INCLUDES_URL. 'product/product-price.php'; 
			 } ?>
			<?php if ($layout=='metro'  && (!empty($display_cart_button) && $display_cart_button=='yes') && $product->is_in_stock()) { ?>
				<div class="wrapper-cart-hover-hidden add-cart-btn">
					<?php $_product = wc_get_product( $product_id );
					if( $_product->is_type( 'simple' ) ) { ?>
						<div class="product-add-to-cart" ><a title="<?php echo esc_attr__($dcb_single_product); ?>" href="?add-to-cart=<?php echo esc_attr($product_id); ?>" rel="nofollow" data-product_id="<?php echo esc_attr($product_id); ?>" data-product_sku="" class="add_to_cart add_to_cart_button product_type_simple ajax_add_to_cart"><span class="text"><span><?php echo esc_html__($dcb_single_product); ?></span></span><span class="icon"><span class="sr-loader-icon"></span><span class="check"></span></span></a></div>
					<?php }else{ ?>
						<div class="product-add-to-cart" ><a rel="nofollow" href="<?php echo esc_url(get_the_permalink()); ?>" data-quantity="1" data-product_id="<?php echo esc_attr($product_id); ?>" data-product_sku="" class="add_to_cart add_to_cart_button product_type_simple " data-added-text=""><span class="text"><span><?php echo esc_html__($dcb_variation_product); ?></span></span><span class="icon"><span class="sr-loader-icon"></span><span class="check"></span></span></a></div>
					<?php } ?>
				</div>
			<?php }	
			/*yith*/
			if(!empty($display_yith_list) && $display_yith_list=='yes'){
				if($layout=='metro'){ ?>
					<div class="tp-yith-wrapper">
					<?php
						/*yith compare start*/
						if(!empty($display_yith_compare) && $display_yith_compare=='yes'){
							if( is_plugin_active('yith-woocommerce-compare/init.php') ){ ?>
								<div class="tp-yith-inner post-yith-wc-compare">							
											<a href="<?php echo home_url(); ?>?action=yith-woocompare-add-product&id=<?php echo $product_id; ?>" class="compare button" data-product_id="<?php echo $product_id; ?>" rel="nofollow"><i aria-hidden="true" class="fas fa-exchange-alt"></i></a>
								</div>
							<?php }
						}
						/*yith compare end*/
						
						/*yith add to wishlist start*/
						if(!empty($display_yith_wishlist) && $display_yith_wishlist=='yes'){
							if( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ){ ?>
									<div class="tp-yith-inner  post-yith-wc-wishlist">
										<?php echo do_shortcode('[yith_wcwl_add_to_wishlist icon="fas fa-heart" label="" already_in_wishslist_text="" browse_wishlist_text=""]' ); ?>
									</div>
						   <?php } 
						}
						/*yith add to wishlist end*/
						
						/*yith quickview start*/
						if(!empty($display_yith_quickview) && $display_yith_quickview=='yes'){
							if( defined( 'YITH_WCQV_VERSION' ) && is_plugin_active('yith-woocommerce-quick-view/init.php') ){ ?>
								<div class="tp-yith-inner post-yith-wc-quickview" style="opacity:0;">
										<?php echo do_shortcode('[yith_quick_view product_id=$product_id icons="fas fa-eye"]'); ?>
								</div>
							 <?php 
							 }
						}
						/*yith quickview end*/				
					?> </div><?php				
				}
			}
				/*yith*/
			?>
		</div>
		
		<?php if($layout=='metro'){ ?>
			<div class="product-bg-image-metro" <?php echo $data_attr; ?>><?php 
				$out_of_stock_val='';
				if(!empty($out_of_stock)){
					$out_of_stock_val=$out_of_stock;
				}
				if(!empty($b_dis_badge_switch) && $b_dis_badge_switch=='yes'){
						do_action('theplus_product_badge',$out_of_stock_val); 
				} ?></div>
		<?php } ?>
		
	</div>
</article>