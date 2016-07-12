var siteId, product_id, product_name, option_text;
var baokim_payment_url, nganluong_payment_url, payment_success_url;
var $response;

(function ($) {

    $.fn.updateProductDetails = function (options) {
        return this.each(function () {
            var $this = $(this),
				showAddCartButton = options.Price !== undefined && options.Purchasable;

            if (showAddCartButton == true)
                $('#bao_kim_payment, #ngan_luong_payment').show();
            else
                $('#bao_kim_payment, #ngan_luong_payment').hide();

            // hide/show the add to cart button based on price and stock
            $this.find('.AddCartButton').closest('.DetailRow').toggle(showAddCartButton);

            // out of stock message
            $this.find('.OutOfStockMessage').toggle(!showAddCartButton);

            if (options.PurchasingMessage !== undefined && options.PurchasingMessage !== null) {
                $this.find('.OutOfStockMessage').text(options.PurchasingMessage);
            }

            // hide/show price - slightly more complex code due to control panel sharing
            $this.find('.VariationProductPrice').each(function () {
                var $this = $(this);

                if ($this.is('input')) {
                    // if the price is an input then set its raw value
                    $this.val(options.Price);
                    return;
                }
                // otherwise use regular hide/show formatted behaviour
                // Unit
                $this.html(options.Price == undefined ? '' : options.Price + (options.Unit == '' ? '' : ' / ' + options.Unit))
					.closest('.DetailRow')
						.toggle(options.Price !== undefined);
            });

            // use price label
            if (options.UsePriceLabel == true) {
                $this.find(".PriceLabel").hide();
                $this.find(".PriceByRuleLabel").show();
            }
            else {
                $this.find(".PriceLabel").show();
                $this.find(".PriceByRuleLabel").hide();
            }

            var zoom, thumb;

            if (options.Image !== undefined && options.Image !== null && options.Imagethumb !== undefined && options.Imagethumb !== null) {
                // image was supplied and is different from the base image
                zoom = options.Image;
                thumb = options.Imagethumb;
                //ShowVariationThumb = options.imageRuleId; // hack to re-use existing lightbox code
            } else if (options.BaseImage !== undefined && options.BaseImage !== null && options.BaseImagethumb !== undefined && options.BaseImagethumb !== null) {
                // show the base image
                zoom = options.BaseImage;
                thumb = options.BaseImagethumb;
                //ShowVariationThumb = false;
            } else {
                // no image provided at all?
                //ShowVariationThumb = false;
            }

            if (zoom && thumb) {
                if (DetectMobile() == true) {
                    // MobileVersion
                    //$('.FirstProductImage').attr('href', thumb);
                    //$('.FirstProductImage img').attr('src', thumb);

                    //New detail page
                    //if ($.cookie("mobileViewFullSite")) {
                    //    if ($.cookie("mobileViewFullSite") == "1") {
                    //        //Do like WebVersion
                    //        removeTinyImageHighlight();
                    //        replaceProductImageInZoom(zoom, thumb);
                    //    } else {
                    //        //Do normal mobile version
                    //        $('.ProductThumbImage .swiper-slide-active img').attr('src', thumb);
                    //    }
                    //} else {
                    //    //Do normal mobile version
                    //    $('.ProductThumbImage .swiper-slide-active img').attr('src', thumb);
                    //}

                    $('.ProductThumbImage .swiper-slide-active img').attr('src', thumb);
                    //$('.ProductThumbImage .zoomPad img').attr('src', thumb);

                    removeTinyImageHighlight();
                    replaceProductImageInZoom(zoom, thumb);
                }
                else {
                    // WebVersion
                    removeTinyImageHighlight();
                    replaceProductImageInZoom(zoom, thumb);
                }
            } else {
                $this.find('.ProductThumbImage').hide();
            }

            //Product options value return
            if (options.OptionValueIds !== undefined && options.OptionValueIds !== null) {
                $this.find('.CartOptionId')
					.val(options.OptionValueIds);
            }
        });
    };

    /**
    * This plugin implements a generic product options behaviour which marks a parent element of the currently selected
    * change with a 'selectedValue' class for easy css-based highlighting.
    **/
    $.fn.productOptionChangedValue = function (options) {
        console.log(options);
        return this.each(function () {
            $(this).change(function () {
                $(this).find('option').removeClass('selectedValue')
                $(this).find('option:selected').addClass('selectedValue');
                //if ($("#checkOptionRule").val() == "1")
                //{
                var cartOptionId = '';
                $('.productAttributeRuleCondition .selectedValue input').each(function () {
                    cartOptionId = cartOptionId + $(this).val() + ",";
                });
                $('.productAttributeRuleCondition option.selectedValue').each(function () {
                    cartOptionId = cartOptionId + $(this).attr('value') + ",";
                });
                if (cartOptionId != "") cartOptionId = cartOptionId.substring(0, cartOptionId.length - 1);
                $(".CartOptionId").val(cartOptionId);
                if (cartOptionId != '0' && cartOptionId != '')
                    $j('#bao_kim_container, #ngan_luong_container').show();
                else
                    $j('#bao_kim_container, #ngan_luong_container').hide();

                CheckProductOptionSelected();
                //}
            });
        });
    }

    /**
    * This plugin implements a generic product options behaviour which marks a parent element of the currently selected
    * choice with a 'selectedValue' class for easy css-based highlighting.
    */
    $.fn.productOptionSelectedValue = function (options) {
        options = $.extend({ container: 'li' }, options || {});

        return this.each(function () {
            var $this = $(this);

            // when selecting an input, apply a css class to it's parent list item
            $this.delegate('input', 'click', function (event) {
                $this.find(options.container).removeClass('selectedValue');
                $(this).closest(options.container).addClass('selectedValue');

                // TuanTM: Chọn 1 option, có rule thì check luôn
                // 14/02/2014: truyền tham số lên link baokim & nganluong
                //if ($("#checkOptionRule").val() == "1") {
                var cartOptionId = "";
                $(".productAttributeRuleCondition .selectedValue input").each(function () {
                    cartOptionId = cartOptionId + $(this).val() + ",";
                });
                if (cartOptionId != "") cartOptionId = cartOptionId.substring(0, cartOptionId.length - 1);
                $(".CartOptionId").val(cartOptionId);
                if (cartOptionId != '0' && cartOptionId != '')
                    $j('#bao_kim_container, #ngan_luong_container').show();
                else
                    $j('#bao_kim_container, #ngan_luong_container').hide();

                CheckProductOptionSelected();
                //}
            });

            // apply on page load too incase something is pre-selected
            $this.find(':checked').closest(options.container).addClass('selectedValue');
        });
    };

    /**
    * This plugin implements behaviours applicable to all option types which can trigger sku / rule effects (change of
    * price, weight, image, etc.)
    */
    $.fn.productOptionRuleCondition = function (options) {
        return this.each(function () {
            $(this)
				.addClass('productAttributeRuleCondition')
				.find(':input')
				.change(function () {
				    // ask the server for any updated product information based on current options - can't use
				    // ajaxSubmit here because it will try to send files too so use serializeArray and put our custom
				    // 'w' parameter into it
				    var data = $('#ProductOptionList :input').serializeArray();
				    data.push({ name: 'action', value: 'getProductAttributeDetails' });

				    if (productAttributeCount + 4 == data.length) {
				        console.log($response);
				        data = $.param(data);

				        if ($response != null) {
				            Temp($response);
				        }
				        else {
				            $.ajax({
				                url: '/Handlers/ProductOptions.ashx',
				                type: 'post',
				                dataType: 'json',
				                data: data,
				                success: function (response) {
				                    $response = response;
				                    Temp(response);
				                }
				            });
				        }
				    }

				});
        });
    };

    /**
    * This plugin implements behaviours applicable to all configurable option types (validation, etc.).
    */
    $.fn.productOptionConfigurable = function (options) {
        if (options.condition) {
            this.productOptionRuleCondition(options);
        }

        return this.each(function () {
            var target = $(this).find('.validation').eq(0); // only select the first matching target (for radios)
            console.log(target);
            //alert(target.attr('id'));
            if (!target.length) {
                // could not find validation target - validate plugin doesn't like being passed an empty jquery result
                return;
            }

            if (options.required) {
                target.rules('add', {
                    required: true,
                    messages: {
                        required: options.validation.required
                    }
                });
            }
        });
    };

    /**
    * This plugin implements behaviours for pick-list types rendering as radio inputs.
    */
    $.fn.productOptionViewRadio = function (options) {
        this.productOptionConfigurable(options);
        this.productOptionSelectedValue(options);
        return this;
    };

    /**
    * This plugin implements behaviours for pick-list types rendering as rectangle inputs.
    */
    $.fn.productOptionViewRectangle = function (options) {
        this.productOptionConfigurable(options);
        this.productOptionSelectedValue(options);
        return this.each(function () {
            // deselect the radio element when clicking on rectangles as the radio element itself isn't visible
            $(this).delegate('input', 'click', function () {
                $(this).blur();
            });
        });
    };

    /**
    * This plugin implements behaviours for pick-list types rendering as a select input.
    */
    $.fn.productOptionViewSelect = function (options) {
        // nothing to do
        this.productOptionConfigurable(options);
        this.productOptionChangedValue(options); // 14/02/2014: Thêm vào để bắt sự kiện change của selectbox
        return this;
    };

    /**
    * This plugin implements behaviours for pick-list swatch types.
    */
    $.fn.productOptionConfigurablePickListSwatch = function (options) {
        this.productOptionConfigurable(options); // inherit base configurable behaviour
        this.productOptionSelectedValue(options);
        //this.productOptionPreviewDisplay(options);

        return this.each(function () {
            // the radio input is hidden when js is enabled so don't try to focus it
            $(this).delegate('input', 'click', function () {
                $(this).blur();
            });
        });
    };


})(jQuery);

$j(function () {
    // mark the add to cart form as being handled by jquery.validate
    $j('form').validate({
        onsubmit: false,
        ignoreTitle: true,
        showErrors: function (errorMap, errorList) {
            // nothing
        },
        invalidHandler: function (form, validator) {
            if (!validator.size()) return;
            alert(validator.errorList[0].message);
        }
    });
});

function CheckProductOptionSelected() {
    var data = $j('#ProductOptionList :input').serializeArray();
    data.push({ name: 'action', value: 'getProductAttributeDetails' });
    data = $j.param(data);
    $j.ajax({
        url: '/Handlers/ProductOptions.ashx',
        type: 'post',
        dataType: 'json',
        data: data,
        success: function (response) {
            $response = response;
            $j('#priceRule').val(response.dPrice);
            var hash = product_id + ":" + $j("[id$='qty_']").val();
            if ($j('.CartOptionId').length > 0) hash = hash + ':' + $j('.CartOptionId').val();
            hash = btoa(hash);
            if ($j('#bao_kim_payment').length > 0) {
                var bk_href = baokim_payment_url + '&product_quantity=' + $j("[id$='qty_']").val();
                bk_href = bk_href + '&total_amount=' + (response.dPrice * $j("[id$='qty_']").val());
                bk_href = bk_href + "&product_name=" + encodeURIComponent(product_name + " (" + option_text + ")");
                bk_href = bk_href + "&url_success=" + encodeURIComponent(payment_success_url + hash);
                $j('#bao_kim_payment').attr('href', bk_href);
            }
            if ($j('#ngan_luong_payment').length > 0) {
                var nl_href = nganluong_payment_url + '&quantity=' + $j("[id$='qty_']").val();
                nl_href = nl_href + '&price=' + (response.dPrice * $j("[id$='qty_']").val());
                nl_href = nl_href + '&return_url=' + encodeURIComponent(payment_success_url + hash);
                nl_href = nl_href + "&comments=" + encodeURIComponent("Sản phẩm bao gồm các thông tin sau: " + option_text);
                $j('#ngan_luong_payment').attr('href', nl_href);
            }
            $j('#ProductDetails').updateProductDetails(response);
        }
    });
}

function Temp(response) {
    $j('#priceRule').val(response.dPrice);
    var hash = product_id + ":" + $j("[id$='qty_']").val();
    if ($j('.CartOptionId').length > 0) hash = hash + ':' + $j('.CartOptionId').val();
    hash = btoa(hash);
    if ($j('#bao_kim_payment').length > 0) {
        var bk_href = baokim_payment_url + '&product_quantity=' + $j("[id$='qty_']").val();
        bk_href = bk_href + '&total_amount=' + (response.dPrice * $j("[id$='qty_']").val());
        bk_href = bk_href + "&url_success=" + encodeURIComponent(payment_success_url + hash);
        if (option_text != '') {
            bk_href = bk_href + "&product_name=" + encodeURIComponent(product_name + " (" + option_text + ")");
            $j('#bao_kim_payment').attr('href', bk_href);
        }
        else {
            $j.ajax({
                type: "GET", dataType: "text",
                data: "siteid=" + siteId + "&optionvars=" + $j('.CartOptionId').val(),
                url: "/Handlers/OptionNames.ashx",
                success: function (text) {
                    option_text = text;
                    bk_href = bk_href + "&product_name=" + encodeURIComponent(product_name + " (" + option_text + ")");
                    $j('#bao_kim_payment').attr('href', bk_href);

                    if ($j('#ngan_luong_payment').length > 0) {
                        var nl_href = nganluong_payment_url + '&quantity=' + $j("[id$='qty_']").val();
                        nl_href = nl_href + '&price=' + (response.dPrice * $j("[id$='qty_']").val());
                        nl_href = nl_href + '&return_url=' + encodeURIComponent(payment_success_url + hash);
                        nl_href = nl_href + "&comments=" + encodeURIComponent("Sản phẩm bao gồm các thông tin sau: " + option_text);
                        $j('#ngan_luong_payment').attr('href', nl_href);
                    }
                },
                failure: function (msg) { console.log(msg); }
            });
        }
    }
    else {
        if ($j('#ngan_luong_payment').length > 0) {
            var nl_href = nganluong_payment_url + '&quantity=' + $j("[id$='qty_']").val();
            nl_href = nl_href + '&price=' + (response.dPrice * $j("[id$='qty_']").val());
            nl_href = nl_href + '&return_url=' + encodeURIComponent(payment_success_url + hash);
            if (option_text != '') {
                nl_href = nl_href + "&comments=" + encodeURIComponent("Sản phẩm bao gồm các thông tin sau: " + option_text);
                $j('#ngan_luong_payment').attr('href', nl_href);
            }
            else {
                $j.ajax({
                    type: "GET", dataType: "text",
                    data: "siteid=" + siteId + "&optionvars=" + $j('.CartOptionId').val(),
                    url: "/Handlers/OptionNames.ashx",
                    success: function (text) {
                        option_text = text;
                        nl_href = nl_href + "&comments=" + encodeURIComponent("Sản phẩm bao gồm các thông tin sau: " + option_text);
                        $j('#ngan_luong_payment').attr('href', nl_href);
                    },
                    failure: function (msg) { console.log(msg); }
                });
            }
        }
    }
    $j('#ProductDetails').updateProductDetails(response);
}

function DetectMobile() {
    var mobile = false;
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        mobile = true;
    }
    return mobile;
}
//function initiateImageZoomerMobile() {
//    // clone the A tag and save it for reuse later
//    if (!$j('.ProductThumbImage').data('originalAElement')) {
//        $j('.ProductThumbImage').data('originalAElement', $j('.ProductThumbImage').html());
//    }

//    var options = { zoomWidth: 480, zoomHeight: 400, xOffset: 10, position: "right", preloadImages: false, showPreload: false, title: false, cursor: 'pointer' };
//    $j('.ProductThumbImage a').jqzoom(options);
//}
//function replaceProductImageInZoomMobile(zoom, thumb) {
//    $j('.ProductThumbImage').find('img').remove().end().append($j('.ProductThumbImage').data('originalAElement')).find('a').attr('href', zoom).end().find('img').attr('src', thumb);
//    initiateImageZoomerMobile();
//}