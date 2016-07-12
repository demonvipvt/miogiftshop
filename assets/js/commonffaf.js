////////////////////////////////////////////////////////////
// VARIABLES
var language;

////////////////////////////////////////////////////////////
// AJAX
function ShowLoadingIndicator() {
    if (typeof (disableLoadingIndicator) != 'undefined' && disableLoadingIndicator) {
        return;
    }
    var windowWidth = $j(window).width();
    var scrollTop;
    if (self.pageYOffset) {
        scrollTop = self.pageYOffset;
    }
    else if (document.documentElement && document.documentElement.scrollTop) {
        scrollTop = document.documentElement.scrollTop;
    }
    else if (document.body) {
        scrollTop = document.body.scrollTop;
    }
    $j('#AjaxLoading').css('position', 'absolute');
    $j('#AjaxLoading').css('top', scrollTop + 'px');
    $j('#AjaxLoading').css('left', parseInt((windowWidth - 150) / 2) + "px");
    $j('#AjaxLoading').show();
}
function HideLoadingIndicator() {
    $j('#AjaxLoading').delay(800).slideUp(300);
}

////////////////////////////////////////////////////////////
// PRODUCT ADD PURCHASE
function PopupCartAmount(id, amount) {
    var isQuickPayment = false;
    var optionVars = "0";
    if ($j(".CartOptionId").length > 0) { optionVars = $j(".CartOptionId").val(); }
    $j.ajax({
        beforeSend: function () { },
        type: "POST",
        url: "/WebServices/ProductService.asmx/ViewProductCart",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: "{'productId':'" + id + "', 'productAmount':'" + amount + "', 'optionVars':'" + optionVars + "'}",
        success: function (response) {
            $j('#inline1').html(response.d);
        },
        failure: function (msg) {
            console.log(msg);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status + ': ' + thrownError);
        },
        complete: function (msg) {
            ReloadCart();
            $j("a.ProductAddButton").trigger("click");
        },
        afterClose: function () {
            $j('#inline1').html("");
        }
    });
    
    return false;
}
function PopupCart(id) {
    var isQuickPayment = false;
    var displayPopup = true;
    var popupcontent = $j("#inline1").html();
    if (popupcontent != "") {
        var productId = Number($j(".popup-cart").find(".fastCartTop").attr("productid"));
        if (productId == id) { displayPopup = false; }
    }
    if (displayPopup == true) {
        $j.ajax({
            beforeSend: function () { },
            type: "POST",
            url: "/WebServices/ProductService.asmx/ViewProductCart",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: "{'productId':'" + id + "', 'productAmount':'1', 'optionVars':''}",
            success: function (response) {
                $j('#inline1').html(response.d);
            },
            failure: function (msg) {
                console.log(msg);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status + ': ' + thrownError);
            },
            complete: function (msg) {
                ReloadCart();
                $j("a.ProductAddButton").trigger("click");
            }
        });
    }
    else {
        $j.ajax({
            beforeSend: function () { },
            type: "POST",
            url: "/WebServices/ProductService.asmx/UpdateProductAmount",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: "{'productId':'" + id + "'}",
            success: function (response) { },
            failure: function (msg) { console.log(msg); },
            error: function (xhr, ajaxOptions, thrownError) { console.log(xhr.status + ': ' + thrownError); },
            complete: function (msg) { ReloadCart(); }
        });

        var quantity = Number($j(".quantity").find("span").html());
        quantity = quantity + 1;
        $j(".quantity").find("span").html(quantity);

        var sum = Number($j("#fastCartNumItemsTxt").html());
        sum = sum + 1;
        $j("#fastCartNumItemsTxt").html(sum);
    }
    return false;
}
function ReloadCart() {
    $j.ajax({
        type: "POST",
        url: "/WebServices/ProductService.asmx/LoadCart",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (response) {
            if ($j("#plist").length > 0) {
                $j('#plist').html(response.d);
            }
            else {
                var parentTemp = $j("#inline1").parent();
                $j(parentTemp).append("<div id=\"parentTemp\">" + response.d + "</div>");
            }
            $j("#pInTop").html(' (' + $j("#pInCart").html() + ')');
            if ($j("#parentTemp").length > 0) $j("#parentTemp").remove();
        },
        failure: function (msg) {
            console.log(msg);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status + ': ' + thrownError);
        },
        complete: function (msg) {
            console.log(msg);
        }
    });
}
function RemoveProductCart(position) {
    $j.ajax({
        beforeSend: function () { },
        type: "POST",
        url: "/WebServices/ProductService.asmx/RemoveProductInCart",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: "{'position':'" + position + "'}",
        success: function (response) {
            if ($j("#plist").length > 0) {
                $j('#plist').html(response.d);
            }
            else {
                var parentTemp = $j("#inline1").parent();
                $j(parentTemp).append("<div id=\"parentTemp\">" + response.d + "</div>");
            }
            $j("#pInTop").html(' (' + $j("#pInCart").html() + ')');
            if ($j("#parentTemp").length > 0) $j("#parentTemp").remove();
            if ($j(".CartContents").html() != null) {
                window.location.reload();
            }
        },
        failure: function (msg) {
            console.log(msg);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status + ': ' + thrownError);
        },
        complete: function (msg) {
            console.log(msg);
        }
    });

    return false;
}
function PopupCartDetail() {
    var inventory = $j('.VariationProductInventory').html();
    var amount = $j("[id$='qty_']").val();
    if (Number(amount) > Number(inventory)) {
        alert('Số lượng đặt mua không được lớn hơn số lượng còn trong kho !');
    }
    else {
        if (amount <= 0) {
            alert('Số lượng đặt mua không được nhỏ hơn 0 !');
        }
        else {
            $j(".BulkDiscount").hide();
            PopupCartAmount(ProductId, amount);
            $j(".BulkDiscount").show();
        }
    }
}
function check_add_to_cart(form, required) {
    // validate the attributes
    var attributesValidated = $j('form').validate().form();
    if (attributesValidated)
    {
        attributesValidated = validate_dropdownlist();
    }
    if (!attributesValidated) {
        return false;
    }

    // Popup cart
    PopupCartDetail();
    return false;
}
function validate_dropdownlist() {
    var isOK = true;
    $j(".ProductOptionValue option:selected").each(function () {
        var selected = $j(this).text();
        if ((selected == "") || (selected == "- Tùy chọn -") || (selected == "- Option -"))
        {
            var controlName = $j(this).parent().parent().parent().children(".ProductOptionTitle").children(".name").text();
            isOK = false;
        }
    });
    if (!isOK)
    {
        if (language == "vi-VN") {
            alert("Bạn phải chọn đủ các tùy chọn sản phẩm!");
        } else {
            alert("Please select all required option!")
        }
    }
    return isOK;
}
////////////////////////////////////////////////////////////
// FUNCTION FOR UPDATE SHOPPING CART
function money2number(money) {
    //check number after "."
    var num = money.substring(money.lastIndexOf(".") + 1, money.length);
    //alert(num+" / "+ num.length);
    if (num.length == 3) {
        return money.replace(".", "", "g");
    } else {
        return money.replace(",", "", "g");
    }
}
function checkRegion() {
    var number = $j("#grandTotal").html();
    var num = number.substring(number.lastIndexOf(".") + 1, number.length);
    if (num.length == 3) {
        return "vi-VN";
    } else {
        return "en-US";
    }
}
function FormatCurrency(num) {
    var culture = checkRegion();
    if (culture == "vi-VN") {
        num = num.toString().replace(/\$|\,/g, '');
        if (isNaN(num)) num = "0";
        sign = (num == (num = Math.abs(num)));
        num = Math.floor(num * 100 + 0.50000000001);
        cents = num % 100;
        num = Math.floor(num / 100).toString();
        if (cents < 10) cents = "0" + cents;
        for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3) ; i++)
            num = num.substring(0, num.length - (4 * i + 3)) + '.' + num.substring(num.length - (4 * i + 3));
        return (((sign) ? '' : '-') + num);
    } else {
        //format USD - EUR 
        return num.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
    }

}
function keypress(e) {
    var keypressed = null;
    if (window.event) {
        keypressed = window.event.keyCode; //IE
    }
    else {
        keypressed = e.which; //NON-IE, Standard
    }
    if (keypressed < 48 || keypressed > 57) {
        if (keypressed == 8 || keypressed == 127 || keypressed == 0) { return; }
        return false;
    }
}
function keypressPhoneNumber(e) {
    var keypressed = null;
    if (window.event) {
        keypressed = window.event.keyCode; //IE
    }
    else {
        keypressed = e.which; //NON-IE, Standard
    }
    if ((String.fromCharCode(keypressed) == "(") || (String.fromCharCode(keypressed) == ")") || (String.fromCharCode(keypressed) == "+"))
    {
        return;
    }
    if (keypressed < 48 || keypressed > 57) {
        if (keypressed == 8 || keypressed == 127 || keypressed == 0) { return; }
        return false;
    }
}

////////////////////////////////////////////////////////////
// MENU
function toggleMenu(el1, el2, over) {
    var p; var prefix = "nav-";
    if (el2 == -1) { p = document.getElementById(prefix + el1) }
    else { p = document.getElementById(prefix + el1 + "-" + el2) }
    var newClassName;
    if (over == 1) {
        newClassName = p.className + " over"; p.className = newClassName
    }
    else {
        newClassName = p.className.replace(" over", ""); p.className = newClassName
    }
}

////////////////////////////////////////////////////////////
// AFFILIATE
function SetCookie(c_name, value) {
    $j.cookie(c_name, value, { path: '/', expires: 30 });
}
function RemoveCookie(c_name) {
    document.cookie = c_name + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
}
function GetCookie(c_name) {
    var i, x, y, ARRcookies = document.cookie.split(";");
    for (i = 0; i < ARRcookies.length; i++) {
        x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x == c_name) { return unescape(y); }
    }
}

////////////////////////////////////////////////////////////
// QUICKPAYMENTPOPUP
function QuickPayment(productId) {
    var quickPaymentUrl = $j("#quickPaymentPopupTrigger").attr('original-href');
    if (quickPaymentUrl.indexOf('?productId=') == -1) {
        quickPaymentUrl = quickPaymentUrl + "?productId=" + productId;
    }
    $j("#quickPaymentPopupTrigger")
        .attr("href", quickPaymentUrl)
        .trigger("click");
    return false;
}

////////////////////////////////////////////////////////////
// INIT
$j(document).ready(function () {
    // Check mobile
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        $j('head').append("<script type=\"text/javascript\" src=\"/Extensions/Js/jquery.cookie.js\"></script>");
    }

    // AJAX
    if (language == "vi-VN")
        $j("#AjaxLoading").html("<img src=\"/images/ajax-load.gif\" />&nbsp; Đang tải... Vui lòng chờ...");
    else
        $j("#AjaxLoading").html("<img src=\"/images/ajax-load.gif\" />&nbsp; Loading... Please wait...");

    $j('html').ajaxStart(function () {
        ShowLoadingIndicator();
    });
    $j('html').ajaxComplete(function () {
        HideLoadingIndicator();
    });
    $j('.InitialFocus').focus();

    // AFFILIATE
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == "ref") {
            var refer = pair[1];
            SetCookie("ref", refer);
        }
    }

    // MAIN MENU
    var menus = $j("#nav ul.level0");
    menus.each(function (index, menu) {
        var childs = $j(menu).children().length;
        if (childs == 0) $j(menu).remove()
    });
    menus = $j("#nav ul.level1");
    menus.each(function (index, menu) {
        var childs = $j(menu).children().length;
        if (childs == 0) $j(menu).remove()
    });

    // PRODUCTADD POPUP 
    $j("a.ProductAddButton").fancybox({
        width: 800,
        height: 550,
        fitToView: false,
        autoSize: false,
        openEffect: "fade",
        closeEffect: "fade",
        helpers: {
            overlay: {
                closeClick: false
            } // prevents closing when clicking outsite fancybox
        },
        keys: {
            close: null // prevents closing when pressing ESC fancybox
        },
        afterClose: function () {
            $j("#inline1").html("");
        }
    });

    // QUICKPAYMENT
    $j("#quickPaymentPopupTrigger").fancybox({
        fitToView: false,
        width: "950px",
        height: "550px",
        autoSize: true,
        closeClick: false,
        openEffect: 'fade',
        closeEffect: 'fade',
        helpers: {
            overlay: {
                closeClick: false
            } // prevents closing when clicking outsite fancybox
        },
        keys: {
            close: null // prevents closing when pressing ESC fancybox
        }
    });

    // CHECK FB FOOTER
    if ($j('.form.form-fb iframe').length > 0) {
        var iframe = $j('.form.form-fb').find('iframe');
        var iframeSrcAttr = $j(iframe).attr('src');
        if (iframeSrcAttr.indexOf('ekip.ecommerce') != -1) {
            $j(iframe).attr('src', 'http://www.facebook.com/plugins/likebox.php?href=https://www.facebook.com/cuahangcuabanabc&amp;width=240&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false');
        }
    }

    // SET TIMEOUT BANNER FLASH
    setTimeout(function () {
        $j('#BannerFlashHolder').attr('style', 'overflow: hidden;');
    }, 1000);
});