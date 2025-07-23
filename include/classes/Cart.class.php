<?php

	/*
	 * Cart.class.php
	 * v1 - adds product to cart and saves cart to session,
			updates (increment/decrement) quantity cart in session,
			remove product from cart in session
	 */

	$rootDir = dirname(dirname(__FILE__));
	include_once $rootDir.'/functions.php';


	class Cart extends Functions{

		/*===================== CART DETAILS BEGINS =====================*/
		/** * Function to get coupon details by coupon code */
		function getCartProductVariantCountByUserId($user_id){
			$user_id = $this->escape_string($this->strip_all($user_id));
			$cartDetails = $this->getUserCartDetailsByUserId($user_id);
			return $this->num_rows($cartDetails);
		}
		/** * Function to get coupon details by coupon code */
		function getCartProductVariantCountByToken($token){
			$token = $this->escape_string($this->strip_all($token));
			$cartDetails = $this->getUserCartDetailsByToken($token);
			return $this->num_rows($cartDetails);
		}

		/** * Function to get coupon details by coupon code */
		function getCartTotal($user_id){
			$user_id = $this->escape_string($this->strip_all($user_id));
			$total = 0;

			$cartDetails = $this->getUserCartDetailsByUserId($user_id);

			if($this->num_rows($cartDetails) > 0) {
				while($oneVariant = $this->fetch($cartDetails)) {
					$variantPrice = $this->getProductPricingById($oneVariant['price_id']);
					if(!empty($variantPrice['discounted_price'])) {
						$discountedPrice = $variantPrice['discounted_price'];
						$price = $discountedPrice * $oneVariant['quantity'];
						unset($discountedPrice);
					} else {
						$price = $variantPrice['price'] * $oneVariant['quantity'];
					}
					$total += $price;
				}
			}
			return $total;
		}

		function getUniqueCartDetails($variantId, $loggedInUserId){
			$userId = $this->escape_string($this->strip_all($loggedInUserId));
			$variantId = $this->escape_string($this->strip_all($variantId));

			$sql = "select cd.* from ".PREFIX."cart_details as cd left join ".PREFIX."users as u on cd.user_id = u.id left join ".PREFIX."product_variants as pv on cd.variant_id = pv.id left join ".PREFIX."product_details as pd on pd.id = pv.product_details_id left join ".PREFIX."sub_sub_category_master as sscm on sscm.id = pv.sub_sub_category_id left join ".PREFIX."sub_category_master as scm on scm.id = pv.sub_category_id left join ".PREFIX."category_master as cm on cm.id = pv.category_id left join ".PREFIX."seller_product_pricing as spp on spp.variant_id = pv.id left join ".PREFIX."seller_master as sm on sm.id = spp.seller_id where cd.user_id = '".$userId."' and cd.variant_id = '".$variantId."' and u.active = '1' and u.is_deleted = '0' and pv.active = '1' and pv.is_deleted = '0' and pd.active = '1' and pd.is_deleted = '0' and cm.active = '1' and cm.is_deleted = '0' and sm.active = '1' and sm.is_deleted = '0'";
			return $this->query($sql);
		}

		/** * Function to get cart details by user id */
		function getUserCartDetailsByUserId($userId){
			$userId = $this->escape_string($this->strip_all($userId));
			$sql = "select cd.* from ".PREFIX."cart_details as cd left join ".PREFIX."users as u on cd.user_id = u.id left join ".PREFIX."product_variants as pv on cd.variant_id = pv.id left join ".PREFIX."product_details as pd on pd.id = pv.product_details_id left join ".PREFIX."sub_sub_category_master as sscm on sscm.id = pv.sub_sub_category_id left join ".PREFIX."sub_category_master as scm on scm.id = pv.sub_category_id left join ".PREFIX."category_master as cm on cm.id = pv.category_id where cd.user_id = '".$userId."' and u.active = '1' and u.is_deleted = '0' and pv.active = '1' and pv.is_deleted = '0' and pd.active = '1' and pd.is_deleted = '0' and cm.active = '1' and cm.is_deleted = '0' and ((select count(spp.id) from ".PREFIX."seller_product_pricing as spp left join ".PREFIX."seller_master as sm on sm.id = spp.seller_id where spp.variant_id = pv.id and sm.active = '1' and sm.is_deleted = '0') > 0)";
			return $this->query($sql);
		}

		/** * Function to get cart details by token */
		function getUserCartDetailsByToken($token){
			$token = $this->escape_string($this->strip_all($token));
			$sql = "select cd.* from ".PREFIX."cart_details as cd left join ".PREFIX."users as u on cd.user_id = u.id left join ".PREFIX."product_variants as pv on cd.variant_id = pv.id left join ".PREFIX."product_details as pd on pd.id = pv.product_details_id left join ".PREFIX."sub_sub_category_master as sscm on sscm.id = pv.sub_sub_category_id left join ".PREFIX."sub_category_master as scm on scm.id = pv.sub_category_id left join ".PREFIX."category_master as cm on cm.id = pv.category_id where cd.token = '".$token."' and pv.active = '1' and pv.is_deleted = '0' and pd.active = '1' and pd.is_deleted = '0' and cm.active = '1' and cm.is_deleted = '0' and ((select count(spp.id) from ".PREFIX."seller_product_pricing as spp left join ".PREFIX."seller_master as sm on sm.id = spp.seller_id where spp.variant_id = pv.id and sm.active = '1' and sm.is_deleted = '0') > 0)";
			return $this->query($sql);
		}

		/** * Function to check whether product exists against user id in cart details */
		function checkProductExistsInCartForUser($variantId, $priceId, $userId){
			$userId = $this->escape_string($this->strip_all($userId));
			$variantId = $this->escape_string($this->strip_all($variantId));
			$priceId = $this->escape_string($this->strip_all($priceId));
			$sql = $this->query("select cd.* from ".PREFIX."cart_details as cd left join ".PREFIX."users as u on cd.user_id = u.id left join ".PREFIX."product_variants as pv on cd.variant_id = pv.id left join ".PREFIX."product_details as pd on pd.id = pv.product_details_id left join ".PREFIX."sub_sub_category_master as sscm on sscm.id = pv.sub_sub_category_id left join ".PREFIX."sub_category_master as scm on scm.id = pv.sub_category_id left join ".PREFIX."category_master as cm on cm.id = pv.category_id left join ".PREFIX."seller_product_pricing as spp on spp.variant_id = pv.id left join ".PREFIX."seller_master as sm on sm.id = spp.seller_id where cd.user_id = '".$userId."' and cd.variant_id = '".$variantId."' and cd.price_id = '".$priceId."' and u.active = '1' and u.is_deleted = '0' and pv.active = '1' and pv.is_deleted = '0' and pd.active = '1' and pd.is_deleted = '0' and cm.active = '1' and cm.is_deleted = '0' and sm.active = '1' and sm.is_deleted = '0'");
			if($this->num_rows($sql) > 0){
				return true;
			}else{
				return false;
			}
		}

		/** * Function to check whether product exists against user id in cart details */
		function checkProductExistsInCartByToken($variantId, $priceId, $token){
			$token = $this->escape_string($this->strip_all($token));
			$variantId = $this->escape_string($this->strip_all($variantId));
			$priceId = $this->escape_string($this->strip_all($priceId));
			$sql = $this->query("select cd.* from ".PREFIX."cart_details as cd left join ".PREFIX."users as u on cd.user_id = u.id left join ".PREFIX."product_variants as pv on cd.variant_id = pv.id left join ".PREFIX."product_details as pd on pd.id = pv.product_details_id left join ".PREFIX."sub_sub_category_master as sscm on sscm.id = pv.sub_sub_category_id left join ".PREFIX."sub_category_master as scm on scm.id = pv.sub_category_id left join ".PREFIX."category_master as cm on cm.id = pv.category_id left join ".PREFIX."seller_product_pricing as spp on spp.variant_id = pv.id left join ".PREFIX."seller_master as sm on sm.id = spp.seller_id where cd.token = '".$token."' and cd.variant_id = '".$variantId."' and cd.price_id = '".$priceId."' and pv.active = '1' and pv.is_deleted = '0' and pd.active = '1' and pd.is_deleted = '0' and cm.active = '1' and cm.is_deleted = '0' and sm.active = '1' and sm.is_deleted = '0'");
			if($this->num_rows($sql) > 0){
				return true;
			}else{
				return false;
			}
		}

		/** * Function to add/update cart details */
		function updateAVariantInCart($variantId, $priceId, $quantity, $token = null, $userId = '0'){
			$userId = $this->escape_string($this->strip_all($userId));
			$variantId = $this->escape_string($this->strip_all($variantId));
			$priceId = $this->escape_string($this->strip_all($priceId));
			$quantity = $this->escape_string($this->strip_all($quantity));
			$last_updated = date("Y-m-d H:i:s");


			$errorArr = array();
			if(isset($variantId) && !empty($variantId)){
				$variantId = strip_tags($variantId);
			} else {
				$errorArr[] = "ENTERPRODUCTID";
			}
			if(isset($quantity) && !empty($quantity)){
				$quantity = strip_tags($quantity);
			} else {
				$errorArr[] = "ENTERQUANTITY";
			}
			if(isset($priceId) && !empty($priceId)){
				$priceId = strip_tags($priceId);
			} else {
				$errorArr[] = "ENTERPRICEID";
			}


			$statusMessage = '';
			if(count($errorArr)>0){
				$errorStr = implode("|", $errorArr);
				return array(
						"response" => false,
						"responseMsg" => "An error occurred while updating cart",
						"error" => $errorStr
						);
			} else {

				$productArr = array(
					"variantId" => $variantId,
					"quantity" => $quantity,
					"price_id" => $priceId,
				);

				$isProductExistsInCart = false;
				if($loggedInUserDetailsArr = $this->sessionExists()){
					$isProductExistsInCart = $this->checkProductExistsInCartForUser($variantId, $priceId, $userId);
				}else if(isset($_COOKIE['guest_cart']) && !empty($_COOKIE['guest_cart'])){
					$isProductExistsInCart = $this->checkProductExistsInCartByToken($variantId, $priceId, $userId);
				}

				if($isProductExistsInCart && !empty($token)){
					$sql = "update ".PREFIX."cart_details set quantity = '".$quantity."', last_updated = '".$last_updated."' where user_id = '".$userId."' and variant_id = '".$variantId."' and price_id = '".$priceId."' and token = '".$token."'";
					$statusMessage = "Product quantity updated in cart";
				}else{
					if(!$loggedInUserDetailsArr = $this->sessionExists()){
						if(!$token || empty($token)){
							$token = md5('szcarttoken'.time().rand(0,999));
							$cookie_name 	= 'guest_cart';
							$cookie_value 	= $token;
							setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); //cookie setting for 30 days
						}
					}
					
					if($loggedInUserDetailsArr = $this->sessionExists()){
						$this->removeAVariantFromCartByUserId($loggedInUserDetailsArr['id'], $variantId, $priceId);
					}else if(isset($_COOKIE['guest_cart']) && !empty($_COOKIE['guest_cart'])){
						$this->removeAVariantFromCartByToken($token, $variantId, $priceId);
					}else{

					}

					$sql = "insert into ".PREFIX."cart_details (token, user_id, variant_id, price_id, quantity, last_updated) values ('".$token."', '".$userId."', '".$variantId."', '".$priceId."', '".$quantity."', '".$last_updated."')";
					$statusMessage = "Product added to cart";
				}
				$result = $this->query($sql);

				$cartDetailsArr = array();
				if($loggedInUserDetailsArr = $this->sessionExists()){
					$cartDetails = $this->getUserCartDetailsByUserId($loggedInUserDetailsArr['id']);
					while($carts = $this->fetch($cartDetails)){
						$cartDetailsArr[] = array('variantId' => $carts['variant_id'], 'quantity' => $carts['variant_id'], 'price_id' => $carts['variant_id']);
					}
				}else if(isset($_COOKIE['guest_cart']) && !empty($_COOKIE['guest_cart'])){
					$cartDetails = $this->getUserCartDetailsByToken($token);
					while($carts = $this->fetch($cartDetails)){
						$cartDetailsArr[] = array('variantId' => $carts['variant_id'], 'quantity' => $carts['variant_id'], 'price_id' => $carts['variant_id']);
					}
				}

				return array(
						"response" => true,
						"responseMsg" => $statusMessage,
						"productArr" => $cartDetailsArr,
						);
			}
		}

		/** * Function to remove a variant against a user from cart details by user id  */
		function removeAVariantFromCartByUserId($userId, $variantId, $priceId){
			$userId = $this->escape_string($this->strip_all($userId));
			$variantId = $this->escape_string($this->strip_all($variantId));
			$priceId = $this->escape_string($this->strip_all($priceId));
			$sql = "delete from ".PREFIX."cart_details where user_id = '".$userId."' and variant_id = '".$variantId."' and price_id = '".$priceId."'";
			$this->query($sql);
			$statusMessage = "Product removed from cart";
			return array(
					"response" => true,
					"responseMsg" => $statusMessage
					// "productArr" => $_SESSION[SITE_NAME]['cart'],
					);
		}

		/** * Function to remove a variant against a user from cart details by user id  */
		function clearCartByUserId($userId){
			$userId = $this->escape_string($this->strip_all($userId));
			$sql = "delete from ".PREFIX."cart_details where user_id = '".$userId."'";
			$this->query($sql);
			$statusMessage = "Products removed from cart";
			return array(
					"response" => true,
					"responseMsg" => $statusMessage
					// "productArr" => $_SESSION[SITE_NAME]['cart'],
					);
		}

		/** * Function to remove a variant from cart details by token */
		function removeAVariantFromCartByToken($token, $variantId, $priceId){
			$token = $this->escape_string($this->strip_all($token));
			$variantId = $this->escape_string($this->strip_all($variantId));
			$priceId = $this->escape_string($this->strip_all($priceId));
			$sql = "delete from ".PREFIX."cart_details where token = '".$token."' and variant_id = '".$variantId."' and price_id = '".$priceId."'";
			$this->query($sql);
			$statusMessage = "Product removed from cart";
			return array(
					"response" => true,
					"responseMsg" => $statusMessage
					// "productArr" => $_SESSION[SITE_NAME]['cart'],
					);
		}

		/** * Function to remove all variants against a user from cart details */
		function removeAllVariantsFromCart($userId){
			$userId = $this->escape_string($this->strip_all($userId));
			if($isProductExistsInCart){
				$sql = "delete from ".PREFIX."cart_details where user_id = '".$userId."'";
				return $this->query($sql);
			}else{
				return false;
			}
		}

		/** * Function to get new subtotal after applying coupon code */
		function getNewSubtotalAfterCouponCode($subTotal, $loggedInUserDetailsArr){
			if(isset($loggedInUserDetailsArr) && !empty($loggedInUserDetailsArr) && count($loggedInUserDetailsArr)>0 &&
				isset($_SESSION[SITE_NAME]['couponCode']) && !empty($_SESSION[SITE_NAME]['couponCode'])){
				$loggedInUserId = $loggedInUserDetailsArr['id'];
				$couponDiscount = 0;
				$couponDiscountValue = 0;
				$couponDiscountType = '';
				foreach($_SESSION[SITE_NAME]['couponCode'] as $oneCoupon){
					$couponCode = $this->escape_string($this->strip_all($oneCoupon['couponCode']));
					$variantId = $this->escape_string($this->strip_all($oneCoupon['variantId']));

					$couponVerificationResult = $this->verifyCouponCode($couponCode, $variantId, $loggedInUserId);
					// print_r($couponVerificationResult); // TEST
					// $couponDiscount += $couponVerificationResult['couponDiscount'];

					if($couponVerificationResult['couponStatus'] == 'success'){
						$couponDiscountValue = $couponVerificationResult['discountCouponDetails']['coupon_value'];
						$couponDiscountType = $couponVerificationResult['discountCouponDetails']['coupon_type'];
					}
				}

				if(!empty($couponDiscountType) && !empty($couponDiscountValue)){
					if($couponDiscountType == 'percent'){
						$couponDiscountAmount = 0;
						foreach($_SESSION[SITE_NAME]['couponCode'] as $oneCoupon){
							$couponCode = $this->escape_string($this->strip_all($oneCoupon['couponCode']));
							$variantId = $this->escape_string($this->strip_all($oneCoupon['variantId']));

							$query = "select * from ".PREFIX."discount_coupon_master where coupon_code='".$couponCode."' and active='1' and (curdate() between valid_from and valid_to)";
							$couponDetailsRS = $this->query($query);
							if($couponDetailsRS->num_rows>0){ // coupon is valid
								$couponDetails = $this->fetch($couponDetailsRS);
								if($couponDetails['minimum_purchase_amount'] <= $subTotal){
									// check if user has used the coupon code, only for single use coupon, not multiple use coupon
									if($couponDetails['coupon_usage']=="single"){
										// if($this->isCouponApplicableForUser($couponDetails['id'], $loggedInUserId)){ // DEPRECATED
										if($this->isCouponApplicableForUser($couponDetails['id'], $loggedInUserId, $orderId)){
											// coupon is used in past transaction
											$couponDiscountAmount = $this->getOneCouponCodeAmount($variantId, $loggedInUserId, $couponDetails);
										}
									} else if($couponDetails['coupon_usage']=="multiple"){
										$couponDiscountAmount = $this->getOneCouponCodeAmount($variantId, $loggedInUserId, $couponDetails);
									}
								}else{
									$this->removeCouponCodesForVariantId($variantId);
								}
							}

							$couponDiscount += $couponDiscountAmount;
						}
					}else{
						$couponDiscountAmount = 0;
						foreach($_SESSION[SITE_NAME]['couponCode'] as $oneCoupon){
							$couponCode = $this->escape_string($this->strip_all($oneCoupon['couponCode']));
							$variantId = $this->escape_string($this->strip_all($oneCoupon['variantId']));

							$query = "select * from ".PREFIX."discount_coupon_master where coupon_code='".$couponCode."' and active='1' and (curdate() between valid_from and valid_to)";
							$couponDetailsRS = $this->query($query);
							if($couponDetailsRS->num_rows>0){ // coupon is valid
								$couponDetails = $this->fetch($couponDetailsRS);
								if($couponDetails['minimum_purchase_amount'] > $subTotal){
									$this->removeCouponCodesForVariantId($variantId);
								}else{
									$couponDiscountAmount = $couponDetails['coupon_value'];
								}
							}
						}
						$couponDiscount = $couponDiscountAmount;
					}
				}
			} else {
				$couponDiscount = 0;
			}
			$subTotal = $subTotal - $couponDiscount;

			/* if(($subTotal - $couponDiscount)>0){
				$subTotal = $subTotal - $couponDiscount;
			} else {
				$couponDiscount = 0;
				$this->removeCouponCodesForVariantId($variantId);
			} */
			return array(
				"subTotal" => $subTotal,
				"couponDiscount" => $couponDiscount
				);
		}

		/** * Function to get total amount and quantity of cart products by user id */
		function getCartAmountAndQuantityByUserId($userId){
			$cartDetails = $this->getUserCartDetailsByUserId($userId);
			if($this->num_rows($cartDetails) > 0){
				$subTotal = 0;
				$finalTotal = 0;
				while($oneVariant = $this->fetch($cartDetails)){
					$cartProductDetail = $this->getUniqueProductVariantById($oneVariant['variant_id']);
					$variantPrice = $this->getProductPricingById($oneVariant['price_id']);

					$price = $variantPrice['price'];
					if(!empty($variantPrice['discounted_price'])) {
						$discountedPrice = $variantPrice['discounted_price'];
					}
					if(isset($discountedPrice)) {
						$subTotal += ($discountedPrice * $oneVariant['quantity']);
						unset($discountedPrice); // clear variable for use in loop
					} else { 
						$subTotal += ($price * $oneVariant['quantity']);
					}
				}

				// CHECK IF DISCOUNT COUPON IS VALID FOR THIS USER
				if($loggedInUserDetailsArr = $this->sessionExists()){ // user is logged in, apply discount
					$subTotalArr = $this->getNewSubtotalAfterCouponCode($subTotal, $loggedInUserDetailsArr);
					$couponDiscount = $subTotalArr['couponDiscount'];
					$subTotal = $subTotalArr['subTotal'];
				} else {
					$couponDiscount = 0;
				}
				// CHECK IF DISCOUNT COUPON IS VALID FOR THIS USER

				// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL
				// $shippingCharges = $this->getShippingCharge($subTotal);
				// $finalTotal = $subTotal + $shippingCharges;
				// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL

				return array(
					"items" => $this->num_rows($cartDetails),
					"couponDiscount" => $couponDiscount,
					"subTotalAfterCouponDiscount" => $subTotal,
					// "shippingCharges" => $shippingCharges,
					// "loyalty_points" => $loyalty_points,
					"finalTotal" => $finalTotal
				);
			} else { 
				return array(
					"items" => 0,
					"couponDiscount" => 0,
					"subTotalAfterCouponDiscount" => 0,
					// "shippingCharges" => 0,
					"finalTotal" => 0
					);
			}
		}

		/** * Function to get total amount and quantity of cart products */
		function getCartAmountAndQuantityByToken($token){
			$cartDetails = $this->getUserCartDetailsByToken($token);
			if($this->num_rows($cartDetails) > 0){
				$subTotal = 0;
				$finalTotal = 0;
				while($oneVariant = $this->fetch($cartDetails)){
					$cartProductDetail = $this->getUniqueProductVariantById($oneVariant['variant_id']);
					$variantPrice = $this->getProductPricingById($oneVariant['price_id']);

					$price = $variantPrice['price'];
					if(!empty($variantPrice['discounted_price'])) {
						$discountedPrice = $variantPrice['discounted_price'];
					}
					if(isset($discountedPrice)) {
						$subTotal += ($discountedPrice * $oneVariant['quantity']);
						unset($discountedPrice); // clear variable for use in loop
					} else { 
						$subTotal += ($price * $oneVariant['quantity']);
					}
				}

				// CHECK IF DISCOUNT COUPON IS VALID FOR THIS USER
				if($loggedInUserDetailsArr = $this->sessionExists()){ // user is logged in, apply discount
					$subTotalArr = $this->getNewSubtotalAfterCouponCode($subTotal, $loggedInUserDetailsArr);
					$couponDiscount = $subTotalArr['couponDiscount'];
					$subTotal = $subTotalArr['subTotal'];
				} else {
					$couponDiscount = 0;
				}
				// CHECK IF DISCOUNT COUPON IS VALID FOR THIS USER

				// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL
				// $shippingCharges = $this->getShippingCharge($subTotal);
				// $finalTotal = $subTotal + $shippingCharges;
				// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL

				return array(
					"items" => $this->num_rows($cartDetails),
					"couponDiscount" => $couponDiscount,
					"subTotalAfterCouponDiscount" => $subTotal,
					// "shippingCharges" => $shippingCharges,
					// "loyalty_points" => $loyalty_points,
					"finalTotal" => $finalTotal
				);
			} else { 
				return array(
					"items" => 0,
					"couponDiscount" => 0,
					"subTotalAfterCouponDiscount" => 0,
					// "shippingCharges" => 0,
					"finalTotal" => 0
					);
			}
		}
		/*===================== CART DETAILS ENDS =====================*/



		/*===================== COUPON CODE BEGINS =====================*/
		/** * Function to get coupon details by coupon code */
		function getCouponDetailsByCouponCode($couponCode){
			$couponCode = $this->escape_string($this->strip_all($couponCode));
			$query = "select * from ".PREFIX."discount_coupon_master where coupon_code='".$couponCode."' and active='1' and (curdate() between valid_from and valid_to)";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}

		/** * Function to get applicable product variants for selected coupon code */
		function getApplicableVariantIdsForCouponByCouponCode($couponCode){
			$couponCode = $this->escape_string($this->strip_all($couponCode));
			$couponDetails = $this->getCouponDetailsByCouponCode($couponCode);
			if(count($couponDetails)>0){
				$query = "select * from ".PREFIX."products_discount_coupons where discount_coupon_id='".$couponDetails['id']."'";
				return $this->query($query);
			} else {
				return false;
			}
		}

		/** * Function to check whether coupon is applicable for user or not depend on its usage type */
		function isCouponApplicableForUser($couponId, $loggedInUserId, $orderId = 0){
			$query = "select * from ".PREFIX."discount_coupon_master where id='".$couponId."' and active='1' and (curdate() between valid_from and valid_to)";
			$masterCouponRS = $this->query($query);
			if($masterCouponRS->num_rows>0){
				$masterCouponDetails = $this->fetch($masterCouponRS);

				if($masterCouponDetails['coupon_usage']=="multiple"){ // anyone can use coupon
					return true;
				} else { // check if coupon is used at least once
					if(empty($orderId)){ // coupon code is being applied
						$query = "select * from ".PREFIX."order_discount_coupons where discount_coupon_id='".$couponId."' and user_id='".$loggedInUserId."'";
					} else { // user is at payment gateway, allow single coupon code for same transaction
						$query = "select * from ".PREFIX."order_discount_coupons where discount_coupon_id='".$couponId."' and user_id='".$loggedInUserId."' and order_id!='".$orderId."'";
					}
					$couponUseRS = $this->query($query);
					if($couponUseRS->num_rows>0){
						return false;
					} else {
						return true;
					}
				}
			} else {
				return false;
			}
		}


		/** * Function to get  coupon code */
		function getOneCouponCodeAmount($variantId, $loggedInUserId, $couponDetails){
			$couponDiscountAmount = 0;

			if($couponDetails['special_coupon']=='Yes') {
				$cartAmount = $this->getCartTotal($loggedInUserId);
				$discountOnThisPrice = $cartAmount;
			} else {
				$discountOnThisPrice = 0;
				$cartDetails = $this->getUniqueCartDetails($variantId, $loggedInUserId);
				while($carts = $this->fetch($cartDetails)){
					$isExists = $this->isProductVariantAvailable($carts['variant_id'], $carts['price_id']);
					if($isExists){
						$quantityInCart = $carts['quantity'];
						$variantPrice = $this->getProductPricingById($carts['price_id']);
						
						$price = $variantPrice['price'];
						if(!empty($variantPrice['discounted_price'])) {
							$discountedPrice = $variantPrice['discounted_price'];
							$price = $discountedPrice;
						}
						$discountOnThisPrice += ($price * $quantityInCart);
					}
				}
			}
			$precision = 2;
			if($couponDetails['coupon_type']=="percent"){
				$couponDiscountAmount = round((($couponDetails['coupon_value'] * $discountOnThisPrice) / 100), $precision);
			} else if($couponDetails['coupon_type']=="amount"){
				$couponDiscountAmount = round($couponDetails['coupon_value'], $precision);
			}
			return $couponDiscountAmount;
		}

		/** * Function to verify coupon code */
		function verifyCouponCode($couponCode, $variantId, $loggedInUserId, $orderId = 0){
			if(isset($_SESSION[SITE_NAME]['couponCode'])){ // check if session exists
				$couponDiscountAmount = 0;
				// == TEST ==
					// echo "<hr/>";
					// echo $orderId;
					// echo "<hr/>";
				// == TEST ==

				// check if coupon code is within time range and active
				$couponCode = $this->escape_string($this->strip_all($couponCode));
				$query = "select * from ".PREFIX."discount_coupon_master where coupon_code='".$couponCode."' and active='1' and (curdate() between valid_from and valid_to)";
				$couponDetailsRS = $this->query($query);
				if($couponDetailsRS->num_rows>0){ // coupon is valid
					$couponDetails = $this->fetch($couponDetailsRS);
					
					// check if user has used the coupon code, only for single use coupon, not multiple use coupon
					if($couponDetails['coupon_usage']=="single"){
						// if($this->isCouponApplicableForUser($couponDetails['id'], $loggedInUserId)){ // DEPRECATED
						if($this->isCouponApplicableForUser($couponDetails['id'], $loggedInUserId, $orderId)){
							// coupon is used in past transaction
							// /**/ $couponDiscountAmount = $this->getOneCouponCodeAmount($variantId, $loggedInUserId, $couponDetails);
						} else {
							$this->removeAllCouponCodes($couponCode);
							return array(
								"couponStatus" => "coupon_removed",
								"couponDiscount" => 0
							);
						}
					} else if($couponDetails['coupon_usage']=="multiple"){
						// /**/ $couponDiscountAmount = $this->getOneCouponCodeAmount($variantId, $loggedInUserId, $couponDetails);
					}

					return array(
						"couponStatus" => "success",
						"discountCouponDetails" => $couponDetails/*,
						"couponDiscount" => floatval($couponDiscountAmount)*/
					);

				} else { // coupon invalid
					return array(
						"couponStatus" => "invalid_coupon"/*,
						"couponDiscount" => 0*/
					);
				}

			} else { // no coupon code applied
				return array(
					"couponStatus" => "no_coupon_entered"/*,
					"couponDiscount" => 0*/
				);
			}
		}

		/** * Function to apply coupon code */
		function applyCouponCode($couponCode, $loggedInUserDetailsArr){
			$errorArr = array();
			if(isset($couponCode) && !empty($couponCode)){
				$couponCode = strip_tags($couponCode);
			} else {
				$errorArr[] = "ENTERCOUPONCODE";
			}

			$statusMessage = '';
			if(count($errorArr)>0){
				$errorStr = implode("|", $errorArr);
				return array(
						"response" => true,
						"responseMsg" => "Please enter coupon code",
						"couponCodeMsg" => "Please enter a coupon code",
						"error" => $errorStr
						);
			} else {
				// get cart details
				$cartDetails = $this->getUserCartDetailsByUserId($loggedInUserDetailsArr['id']);

				// get coupon details
				$couponDetails = $this->getCouponDetailsByCouponCode($couponCode);

				if(count($couponDetails)>0){
					$variantIdsRS = $this->getApplicableVariantIdsForCouponByCouponCode($couponCode);
					
					// check if that product is in cart
					if($variantIdsRS->num_rows>0){ // apply coupon code
						$variantIdsInCart = array();
						if($this->num_rows($cartDetails) > 0){
							$cartArr = array();
							while($carts = $this->fetch($cartDetails)){
								$isExists = $this->isProductVariantAvailable($carts['variant_id'], 0);

								if($isExists){
									$variantIdsInCart[] = $carts['variant_id'];
								}
							}
							// $variantIdsInCart = array_column($cartDetails, 'variant_id');
						}
						// print_r($variantIdsInCart); // TEST

						$isCouponApplicable = false;
						// $isCouponApplicable = $this->isCouponApplicableForUser($couponDetails['id'], $loggedInUserDetailsArr['id']); // DEPRECATED
						$isCouponApplicable = $this->isCouponApplicableForUser($couponDetails['id'], $loggedInUserDetailsArr['id'], 0);
						if($isCouponApplicable){ // user has not used this coupon code yet

							$couponApplied = false;
							$price = $this->getCartTotal($loggedInUserDetailsArr['id']);
							while($oneVariant = $this->fetch($variantIdsRS)){
								if(in_array($oneVariant['variant_id'], $variantIdsInCart)){ // product variant is in cart
									// prepare product variant to add in session
									$couponCodeArr = array(
											"variantId" => $oneVariant['variant_id'],
											"couponCode" => $couponCode,
										);

									if(isset($_SESSION[SITE_NAME]['couponCode'])){ // check if session exists
										$variantIdsInCouponSession = array_column($_SESSION[SITE_NAME]['couponCode'], 'variantId');
										if(in_array($oneVariant['variant_id'], $variantIdsInCouponSession)){ // coupon code already applied
											$statusMessage = "Coupon code already applied";
										} else {
											if($couponDetails['minimum_purchase_amount']>0 && $price < $couponDetails['minimum_purchase_amount']) {
												$statusMessage = "Coupon Code not valid";
											} else {
												$_SESSION[SITE_NAME]['couponCode'][] = $couponCodeArr;
												$statusMessage = "Coupon code applied";
												$couponApplied = true;
											}
										}

									} else { // create session, add coupon code for that product variant
										if($couponDetails['minimum_purchase_amount']>0 && $price < $couponDetails['minimum_purchase_amount']) {
											$statusMessage = "Coupon Code not valid";
										} else {
											$_SESSION[SITE_NAME]['couponCode'] = array($couponCodeArr);
											$statusMessage = "Coupon code applied";
											$couponApplied = true;
										}
									}
								}
							}
							if($couponApplied){ // coupon applied to at least one product
								return array(
									"response" => true,
									"responseMsg" => $statusMessage,
									"couponCodeMsg" => $statusMessage,
									"couponCodeArr" => $_SESSION[SITE_NAME]['couponCode'],
								);
							} else { // coupon code applied to 0 product, reject coupon code, product not in cart
								return array(
									"response" => true,
									"responseMsg" => "This coupon is not valid for any product in cart",
									"couponCodeMsg" => "This coupon is not valid for any product in cart",
									"error" => "INVALIDCOUPON"
								); 
							}
						} else {
							return array(
								"response" => true,
								"responseMsg" => "You have already used this coupon",
								"couponCodeMsg" => "You have already used this coupon",
								"error" => "COUPONUSED"
							);
						}
					} else { // reject coupon code, coupon not in database
						return array(
							"response" => true,
							"responseMsg" => "Please enter a valid coupon",
							"couponCodeMsg" => "Please enter a valid coupon",
							"error" => "INVALIDCOUPON"
						);
					}
				} else {
					return array(
						"response" => true,
						"responseMsg" => "Please enter a valid coupon",
						"couponCodeMsg" => "Please enter a valid coupon",
						"error" => "INVALIDCOUPON"
					);
				}
			}
		}

		/** * Function to remove all coupon codes set in session */
		function removeAllCouponCodes(){
			if(isset($_SESSION[SITE_NAME]['couponCode'])){
				unset($_SESSION[SITE_NAME]['couponCode']);
			}
		}

		/** * Function to remove a coupon code for a variant set in session */
		function removeCouponCodesForVariantId($variantId){
			if(isset($_SESSION[SITE_NAME]['couponCode'])){
				foreach($_SESSION[SITE_NAME]['couponCode'] as $index => $oneCoupon){
					// $couponCode = $this->escape_string($this->strip_all($oneCoupon['couponCode']));
					$inSessionProductId = $this->escape_string($this->strip_all($oneCoupon['variantId']));
					if($variantId == $inSessionProductId){
						unset($_SESSION[SITE_NAME]['couponCode'][$index]);
					}
				}
				if(count($_SESSION[SITE_NAME]['couponCode']) == 0){
					unset($_SESSION[SITE_NAME]['couponCode']);
				}
				return true;
			}
			return false;
		}

		/** * Function to get cart amount and quantity */
		function getCartAmountAndQuantity($loggedInUserDetailsArr){
			$cartDetails = $this->getUserCartDetailsByUserId($loggedInUserDetailsArr['id']);
	  		while($carts = $this->fetch($cartDetails)){
				$cartArr[] = array('variantId' => $carts['variant_id'], 'quantity' => $carts['quantity'], 'price_id' => $carts['price_id']);
			}
			if($cartArr){
				$subTotal = 0;
				$finalTotal = 0;
				foreach($cartArr as $oneProduct){
					$cartProductDetail = $this->getUniqueProductVariantById($oneProduct['variantId']);
					$productPrice = $this->getProductPricingById($oneProduct['price_id']);

					$price = $productPrice['price'];
					if(!empty($productPrice['discounted_price'])) {
						$discountedPrice = $productPrice['discounted_price'];
					}
					if(isset($discountedPrice)) {
						$subTotal += ($discountedPrice * $oneProduct['quantity']);
						unset($discountedPrice); // clear variable for use in loop
					} else { 
						$subTotal += ($price * $oneProduct['quantity']);
					}
				}

				// CHECK IF DISCOUNT COUPON IS VALID FOR THIS USER
				if(isset($loggedInUserDetailsArr) && !empty($loggedInUserDetailsArr)){ // user is logged in, apply discount
					$subTotalArr = $this->getNewSubtotalAfterCouponCode($subTotal, $loggedInUserDetailsArr);
					$couponDiscount = $subTotalArr['couponDiscount'];
					$subTotal = $subTotalArr['subTotal'];
				} else {
					$couponDiscount = 0;
				}
				// CHECK IF DISCOUNT COUPON IS VALID FOR THIS USER

				/*if(isset($_SESSION[SITE_NAME]['loyaltyCash'])) {
					$loyalty_points = $_SESSION[SITE_NAME]['loyaltyCash'];
				} else {
					$loyalty_points = 0;
				}
				$subTotal = $subTotal-$loyalty_points;
				if($subTotal<=0) {
					$loyalty_points = $subTotal;
					$subTotal = 0;
				}*/

				// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL
				$shippingCharges = $this->getShippingCharge($subTotal);
				$finalTotal = $subTotal + $shippingCharges;
				// APPLY SHIPPING CHARGE ON UPDATED SUBTOTAL

				return array(
					"items" => count($cartArr),
					"couponDiscount" => $couponDiscount,
					"subTotalAfterCouponDiscount" => $subTotal,
					"shippingCharges" => $shippingCharges,
					// "loyalty_points" => $loyalty_points,
					"finalTotal" => $finalTotal
				);
			} else { 
				return array(
					"items" => 0,
					"couponDiscount" => 0,
					"subTotalAfterCouponDiscount" => 0,
					"shippingCharges" => 0,
					"finalTotal" => 0
					);
			}
		}

		/** * Function to add order details. */
		function processTransaction($userDetails, $data){

			$prefix				= 'SZ';				
			// $txn_id 			= str_shuffle('1234567890123456789012345678901234567890');
			// $txn_id 			= substr($txn_id,0,8);
			// $txn_id 			= $this->generate_id($prefix, $txn_id, 'order', 'txn_id');
			$txn_id 			= $prefix.date("YmdHis");

			$payment_status 	= 0;
			$order_status 		= 0;
			$payment_mode 		= '0';
			$user_id			= $userDetails['id'];

			$billing_address_id = $this->escape_string($this->strip_all($data['billing_address_id']));
			$billingDetails 	= $this->getUniqueUserAddressById($billing_address_id, $user_id);

			$billing_email		= $billingDetails['user_email'];
			$billing_contact	= $billingDetails['user_contact'];
			$billing_name		= $billingDetails['user_name'];
			$billing_address	= $billingDetails['address'];
			$billing_state 		= $billingDetails['state_id'];
			$billing_city 		= $billingDetails['city_id'];
			$billing_pincode	= $billingDetails['pincode'];

			$shipping_address_id = $this->escape_string($this->strip_all($data['shipping_address_id']));
			$shippingDetails 	= $this->getUniqueUserAddressById($shipping_address_id, $user_id);

			$shipping_email		= $shippingDetails['user_email'];
			$shipping_contact	= $shippingDetails['user_contact'];
			$shipping_name		= $shippingDetails['user_name'];
			$shipping_address	= $shippingDetails['address'];
			$shipping_state 	= $shippingDetails['state_id'];
			$shipping_city 		= $shippingDetails['city_id'];
			$shipping_pincode	= $shippingDetails['pincode'];

			$amtArr = $this->getCartAmountAndQuantity($userDetails);
			$shipping_charges = $amtArr['shippingCharges'];

			$query = "insert into ".PREFIX."order (txn_id, payment_status, order_status, payment_mode, user_id, billing_email, billing_contact, billing_name, billing_address, billing_state, billing_city, billing_pincode, shipping_email, shipping_contact, shipping_name, shipping_address, shipping_state, shipping_city, shipping_pincode, shipping_charges, invoice_no, invoice_date) values ('".$txn_id."', '".$payment_status."', '".$order_status."', '".$payment_mode."', '".$user_id."', '".$billing_email."', '".$billing_contact."', '".$billing_name."', '".$billing_address."', '".$billing_state."', '".$billing_city."', '".$billing_pincode."', '".$shipping_email."', '".$shipping_contact."', '".$shipping_name."', '".$shipping_address."', '".$shipping_state."', '".$shipping_city."', '".$shipping_pincode."', '".$shipping_charges."', null, null)";
			
			$this->query($query);

			$order_id = $this->last_insert_id();

			$cartDetails = $this->getUserCartDetailsByUserId($user_id);

			$amtArr = $this->getCartAmountAndQuantityByUserId($user_id);
			$processedProduct = 0;
			while($carts = $this->fetch($cartDetails)){
				$variant_id = $carts['variant_id'];
				$price_id = $carts['price_id'];
				$quantity = $carts['quantity'];

				$priceDetails = $this->getProductPricingById($price_id);

				if($priceDetails['available_quantity'] >= $quantity) {
					$unit_price = $priceDetails['price'];			                		
	            	if(!empty($priceDetails['discounted_price'])){
	            		$unit_price = $priceDetails['discounted_price'];
	            	}

	            	$query = "insert into ".PREFIX."order_details (order_id, variant_id, quantity, unit_price) values ('".$order_id."', '".$variant_id."', '".$quantity."', '".$unit_price."')";
	            	$this->query($query);
	            	$processedProduct = $processedProduct+1;

	            	// $query = "delete from ".PREFIX."cart_details where id = '".$carts['id']."'";
	            	// $this->query($query);
				} else {
					$productArr = array(
						"variant_id" => $variant_id,
						"price_id" => $price_id
					);
					$_SESSION[SITE_NAME]['outOfStock'][] = $productArr;
					$removeCart = $this->removeAVariantFromCartByUserId($user_id, $variant_id, $price_id);
				}
			}
			if($processedProduct==0) {
				$this->query("delete from ".PREFIX."order where id='".$order_id."'");
			}


			/*if(isset($_SESSION[SITE_NAME]['couponCode'])){
				foreach($_SESSION[SITE_NAME]['couponCode'] as $index => $couponCodeArr){
					$variant_id = $couponCodeArr['variantId'];
					$coupon_code = $couponCodeArr['couponCode'];

					$couponCodeDetails = $this->fetch($this->query("select dcm.* from ".PREFIX."products_discount_coupons as pdc left join ".PREFIX."discount_coupon_master as dcm on pdc.discount_coupon_id = dcm.id where pdc.variant_id = '".$variant_id."' and dcm.coupon_code = '".$coupon_code."'"));

					$discount_coupon_id = $couponCodeDetails['id'];
					$coupon_type 		= $couponCodeDetails['coupon_type'];
					$coupon_value 		= $couponCodeDetails['coupon_value'];
					$valid_from 		= $couponCodeDetails['valid_from'];
					$valid_to 			= $couponCodeDetails['valid_to'];
					$coupon_usage 		= $couponCodeDetails['coupon_usage'];

					$query = "insert into ".PREFIX."order_discount_coupons (order_id, variant_id, discount_coupon_id, user_id, coupon_code, coupon_type, coupon_value, valid_from, valid_to, coupon_usage) values ('".$order_id."', '".$variant_id."', '".$discount_coupon_id."', '".$user_id."', '".$coupon_code."', '".$coupon_type."', '".$coupon_value."', '".$valid_from."', '".$valid_to."', '".$coupon_usage."')";
					$this->query($query);
				}
				$this->removeAllCouponCodes();
			}*/

			return array("orderId" => $order_id, "txnId" => $txn_id, "cartPriceDetails" => $amtArr, "status" => "success", "processedProduct" => $processedProduct);
		}

		/** * Function to complete purchase order process. */
		function completePurchaseOfProductOrder($loggedInUserDetailsArr, $txnId){
			$loggedInUserId = $loggedInUserDetailsArr['id'];
			$query = "select * from ".PREFIX."order where txn_id='".$txnId."' and user_id='".$loggedInUserId."'";
			$orderRS = $this->query($query);
			if($orderRS->num_rows>0){ // order with txn_id for that user found
				$orderDetails = $this->fetch($orderRS);

				if(date("n") <= 3){
					$invYear = (date("y") - 1).date("y");
				}else{
					$invYear = date("y").(date("y") + 1);
				}

				$prevOrderRS = $this->query("select invoice_no from ".PREFIX."order where invoice_no LIKE 'SZINV-".$invYear."-%' order by id DESC LIMIT 0,1");
				if($this->num_rows($prevOrderRS)>0) {
					$prevOrder = $this->fetch($prevOrderRS);
					$prevInvoiceNo = $prevOrder['invoice_no'];
					$prevInvoiceExp = explode('-',$prevInvoiceNo);
					//print_r($prevInvoiceExp);
					if(isset($prevInvoiceExp[0])) {
						$newInvoiceNo = $prevInvoiceExp[2] + 1;
						$newInvoiceNo = str_pad($newInvoiceNo, 11, 0, STR_PAD_LEFT);
						$invoice_no = 'SZINV-'.$invYear.'-'.$newInvoiceNo;					
					} else {
						$invoice_no = 'SZINV-'.$invYear.'-00000000001';						
					}
				} else {
					$invoice_no = 'SZINV-'.$invYear.'-00000000001';
				} 
				$invoice_date = date('Y-m-d H:i:s');

				if($orderDetails['payment_mode']=='0') {
					// update payment status of order
					$query = "update ".PREFIX."order set payment_status = '1', order_status = '0', invoice_no = '".$invoice_no."', invoice_date = '".$invoice_date."' where id='".$orderDetails['id']."'";
					$this->query($query);
				} else if($orderDetails['payment_mode']=='1') {
					$query = "update ".PREFIX."order set order_status = '0' where id='".$orderDetails['id']."'";
					$this->query($query);
				}/* else if($orderDetails['payment_mode']=='2') {
					$query = "update ".PREFIX."order set payment_status = '1', order_status = '0' where id='".$orderDetails['id']."'";
					$this->query($query);
				}*/
				// $loyaltyPointsUsed = $orderDetails['loyalty_points'];
				// $userDetails = $this->getUniqueUserById($orderDetails['user_id']);
				// $points = $userDetails['loyalty_points'];
				// $newPoints = $points - $loyaltyPointsUsed;
				// if($newPoints<=0) {
				// 	$newPoints = 0;
				// }
				// $this->query("update ".PREFIX."users set loyalty_points='".$newPoints."' where id='".$userDetails['id']."'");

				// UPDATE PRODUCT AVAILABLE QUANTITY
				$cartArr = array();
				$cartDetails = $this->getUserCartDetailsByUserId($loggedInUserDetailsArr['id']);
                while($carts = $this->fetch($cartDetails)){
                    $cartArr[] = array('variant_id' => $carts['variant_id'], 'quantity' => $carts['quantity'], 'price_id' => $carts['price_id']);
                }
				$productCancelled = 0;
				$processedProduct = 0;
				$_SESSION[SITE_NAME]['outOfStock'] = array();

				if($cartArr){
					foreach($cartArr as $oneProduct){
						$cartProductDetail = $this->getUniqueProductVariantById($oneProduct['variant_id']);
						$productPrice = $this->getProductPricingById($oneProduct['price_id']);
						$quantity = $this->escape_string($this->strip_all($oneProduct['quantity']));
						$productPrice['available_quantity']." ".$quantity;
						if($productPrice['available_quantity'] >= $quantity) {
							$newQuantity = $productPrice['available_quantity'] - $quantity;

							$query = "update ".PREFIX."seller_product_pricing set available_quantity='".$newQuantity."' where id='".$productPrice['id']."'";
							$this->query($query);							

			            	for($i = 0; $i < $quantity; $i++){
			            		$query = "insert into ".PREFIX."product_model_details (order_id, variant_id) values ('".$orderDetails['id']."', '".$oneProduct['variant_id']."')";
			            		$this->query($query);
			            	}

							$processedProduct = $processedProduct+1;
						} else {							
							$productArr = array(
								"variant_id" => $oneProduct['variant_id'],
								"price_id" => $oneProduct['price_id'],
							);
							$_SESSION[SITE_NAME]['outOfStock'][] = $productArr;
							$productCancelled = $productCancelled+1;
							$removeCart = $this->$this->removeAVariantFromCartByUserId($loggedInUserId, $oneProduct['variant_id'], $oneProduct['price_id']);
						}
					}
					if($processedProduct==0) {
						$this->query("delete from ".PREFIX."order where id='".$orderDetails['id']."'");
						header("location: payment-error.php?OUTOFSTOCK&payment");
						exit;
					}
				}
				// UPDATE PRODUCT AVAILABLE QUANTITY

				// CHECK IF COUPON CODE USED AND DISCOUNT COUPON IS VALID FOR THIS USER
				if(isset($_SESSION[SITE_NAME]['couponCode']) && !empty($_SESSION[SITE_NAME]['couponCode'])){
					
					$curCouponCode = '';
					$preCouponCode = '';
					foreach($_SESSION[SITE_NAME]['couponCode'] as $oneCoupon){
						$couponCode = $this->escape_string($this->strip_all($oneCoupon['couponCode']));
						$variantId = $this->escape_string($this->strip_all($oneCoupon['variantId']));

						$curCouponCode = $couponCode;

						// $couponVerificationResult = $this->verifyCouponCode($couponCode, $variantId, $loggedInUserId, $this); // DEPRECATED
						// echo $orderDetails['id']; // TEST
						$couponVerificationResult = $this->verifyCouponCode($couponCode, $variantId, $loggedInUserId, $orderDetails['id']);

						if($couponVerificationResult['couponStatus']=="success"){
							
							$couponDetails = $couponVerificationResult['discountCouponDetails'];
							$couponDiscountAmount = $couponVerificationResult['couponDiscount'];

							if($curCouponCode != $preCouponCode || $couponDetails['coupon_type']=='percent'){

								$query = "INSERT INTO `".PREFIX."order_discount_coupons` (`order_id`, `variant_id`, `discount_coupon_id`, `user_id`, `coupon_code`, `coupon_type`, `coupon_value`, `valid_from`, `valid_to`, `coupon_usage`) VALUES ('".$orderDetails['id']."', '".$variantId."', '".$couponDetails['id']."', '".$loggedInUserId."', '".$couponDetails['coupon_code']."', '".$couponDetails['coupon_type']."', '".$couponDetails['coupon_value']."', '".$couponDetails['valid_from']."', '".$couponDetails['valid_to']."', '".$couponDetails['coupon_usage']."');";
								
								$this->query($query);
							
								$preCouponCode = $couponCode;
							}
						}
						
						// == TEST ==
							// echo "<pre>";
							// print_r($couponVerificationResult);
							// echo "</pre><hr/>";
						// == TEST ==
					}
					// exit; // TEST

					$this->removeAllCouponCodes();
				}
				// CHECK IF COUPON CODE USED AND DISCOUNT COUPON IS VALID FOR THIS USER

				// if(isset($_SESSION[SITE_NAME]['loyaltyCash'])) {
				// 	unset($_SESSION[SITE_NAME]['loyaltyCash']);
				// }
				// CLEAR CART SESSION
				// $this->clearEntireCart();
				$removeCart = $this->clearCartByUserId($loggedInUserId);

				return true;
			} else {
				// ERROR
				return false;
			}
		}

		/** * Function to get pruchased product order details */
		/*function getPurchasedProductOrderDetails($loggedInUserId, $txnId){
			$query = "select * from ".PREFIX."order where txn_id='".$txnId."' and user_id='".$loggedInUserId."'";
			$orderRS = $this->query($query);
			if($orderRS->num_rows>0){ // order with txn_id for that user found
				$transactionArr = array();
				$orderDetails = $this->fetch($orderRS);

				$transactionArr['order'] = $orderDetails;
				$transactionArr['orderDetails'] = array();

				$query = "select * from ".PREFIX."order_details where order_id='".$orderDetails['id']."'";
				$orderDetailsRS = $this->query($query);

				while($row = $this->fetch($orderDetailsRS)){
					$transactionArr['orderDetails'][] = $row;
				}
				return $transactionArr;
			} else {
				// error
				return false;
			}
		}*/
		/*===================== COUPON CODE ENDS =====================*/



		/*===================== SHIPPING CHARGES =====================*/
		/** * Function to get shipping charges */
		function getShippingCharge($total){
			$result = $this->fetch($this->query("select * from ".PREFIX."shipping_charge"));
			if($total <= $result['free_shipping_above']){
				return $result['shipping_charges'];
			} else {
				return 0;
			}
		}
		/*===================== SHIPPING CHARGES =====================*/
	}
?>