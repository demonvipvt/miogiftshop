<script type="text/javascript">
    var searchCache = {}; //cache all result
    function OnSearchClick() {
        var content = $j("[id$='txtSearch']").val();
        if (content == "") return false;
        if (content.length < 2) return false;
        return true;
    }
    function SearchInstant() {
        var keyword = $j('#ctlSearch_ctl00_txtSearch').val();
        //check the input != " " and != "  "
        if ((keyword != " ")) {
            //check the keyword != null
            keyword = keyword.trim();
            if (keyword != null) {
                if (keyword.length >= 2) {
                    if (searchCache[keyword] != null) {
                        //Get result from cache
                        arrProducts = searchCache[keyword];
                        ShowResult(arrProducts);
                    } else {
                        //Search
                        if (keyword != "") {
                            var dataString = "{'KeyWord':'" + keyword + "','NumberSearch':'5'}";
                            var arrProducts = [];
                            $j.ajax({
                                type: "POST",
                                url: "/WebServices/ProductService.asmx/Products_SmartSearch",
                                contentType: "application/json; charset=utf-8",
                                dataType: "json",
                                data: dataString,
                                error: function (response) {
                                    
                                },
                                complete: function (response) {
                                    var d = response.responseText;
                                    d = d.substring(5, d.lastIndexOf("}"));
                                    arrProducts = JSON.parse(d);
                                    searchCache[keyword] = arrProducts; //cache results
                                    ShowResult(arrProducts);
                                }
                            });
                        } else {
                            $j("#SuggestPost").html("");
                            $j("#SearchSmart").hide();
                        }
                    }
                } else {
                    $j("#SuggestPost").html("");
                    $j("#SearchSmart").hide();
                }
            }
        }
    }

    function ShowResult(arrProducts) {
        if (arrProducts.length != 0) {
            $j("#SuggestPost").html("");
            var even = "";
            var lastBorder = "";
            for (i = 0; i < arrProducts.length; i++) {
                even = "";
                if (i % 2 == 0) {
                    even = " EvenDiv";
                }
                if (i == arrProducts.length - 1) {
                    lastBorder = " LiLastBorder";
                }
                var newItem = "";
                if (arrProducts[i].SalePrice != 0) {
                    newItem = "<li><a href='" + arrProducts[i].DetailURL + "'><div class='InsdeDiv" + even + lastBorder + "'>" +
                            "<img onerror=\"this.src='http://static.bizwebmedia.net/Thumbnail.ashx?img=images/noimage.gif&width=54&height=54';\" " +
                            "src='http://static.bizwebmedia.net/" + arrProducts[i].ImageUrl + "'/>" +
                            "<span class='InsideDivPName'>" + arrProducts[i].ProductName + "</span>" +
                            "<div class='InsideDivPPrice'>Giá : <span class='InsideDivOldPrice'><strike>" + arrProducts[i].PriceString + " đ</strike></span><br/>" +
                            "<span class='InsideDivSalePrice'>" + arrProducts[i].SalePriceString + " đ</span>" +
                            "</div></div></a></li>";
                } else {
                    newItem = "<li><a href='" + arrProducts[i].DetailURL + "'><div class='InsdeDiv" + even + lastBorder + "'>" +
                            "<img onerror=\"this.src='http://static.bizwebmedia.net/Thumbnail.ashx?img=images/noimage.gif&width=54&height=54';\" " +
                            "src='http://static.bizwebmedia.net/" + arrProducts[i].ImageUrl + "'/>" +
                            "<span class='InsideDivPName'>" + arrProducts[i].ProductName + "</span>" +
                            "<div class='InsideDivPPrice'>Giá : <span class='InsideDivOldPrice'>" + arrProducts[i].PriceString + " đ</span>" +
                            "</div></div></a></li>";
                }
                $j("#SuggestPost").append(newItem);
            }
            var height = $j("#ctlSearch_ctl00_txtSearch").height();
            $j("#SearchSmart").css("top", height+1);
            $j("#SearchSmart").show();
        } else {
            $j("#SearchSmart").hide();
        }
    }
    $j(document).ready(function () {
        //Mouse event for search box
        $j("#SearchSmart").mouseenter(function () {
            $j("#SearchSmart").show();
        });
        $j("#SearchFormContainer").mouseenter(function () {
            $j("#SearchSmart").show();
        }).mouseleave(function () {
            if ($j("#SearchSmart").is(":hover")) {
                $j("#SearchSmart").show();
            }
        });
        $j("html").click(function () {
            $j("#SearchSmart").hide();
        });
        $j("#SearchSmart").click(function (event) {
            event.stopPropagation();
        });
    });
</script>
<style type="text/css">
    #SearchSmart
    {
        width: 520px;
        max-height: 365px;
        position: absolute;
        display: none;
        background: #FFF;
        z-index: 99;
        right: 27px;
        border-right: 1px solid #d7d7d7;
        border-left: 1px solid #d7d7d7;
        display: inline-block;
    }
    #SearchSmart li
    {
        list-style-type: none;
        height: 71px;
    }
    #SearchSmart ul
    {
        margin: 0 !important;
    }
    #SearchSmart ul li:hover div
    {
        background: #FFFFDD;
    }
    .InsdeDiv
    {
        font-size: 12px;
        color: #3a3a3a;
        display: block;
        height: 71px;
    }
    .InsdeDiv img
    {
        width: 54px;
        height: 54px;
        margin-top: 7px;
        margin-left: 9px;
        float: left;
    }
    .EvenDiv
    {
        background: #f4f4f4;
        border-top: 1px solid #d7d7d7;
        border-bottom: 1px solid #d7d7d7;
    }
    .InsideDivPName
    {
        margin-left: 14px;
        margin-top: 18px;
        float: left;
        word-wrap: break-word;
        max-width: 275px;
        min-width: 275px;
        overflow: hidden;
        padding-right: 8px;
    }
    .InsideDivPPrice
    {
        float: left;
        margin-left: 2px;
        margin-top: 18px;
    }
    .InsideDivSalePrice
    {
        color: #d00000;
        float: right;
        font-weight: bold;
    }
    .InsideDivOldPrice
    {
        font-weight: bold;
    }
    .LiLastBorder
    {
        border-bottom: 1px solid #d7d7d7;
    }
</style>
<div id="SearchFormContainer" style="position: relative;z-index:10000;">
    <input name="ctl00$ctlSearch$ctl00$txtSearch" type="text" id="ctlSearch_ctl00_txtSearch" class="search-input" onkeyup="SearchInstant()" autocomplete="off" />
    <input type="submit" name="ctl00$ctlSearch$ctl00$btnSearch" value="" onclick="javascript:return OnSearchClick();" id="ctlSearch_ctl00_btnSearch" class="search-button" />
    <a href="searchb403.html?action=advance" class="search-adv" title="Tìm kiếm nâng cao">
        <span>+</span> </a> <!-- </label> -->
    <div id="SearchSmart">
        <ul id="SuggestPost">
        </ul>
    </div>
</div>