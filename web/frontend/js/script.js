var JS_SECURE_URL = "localhost";

//Polyfill for IE so str.includes will work
if (!String.prototype.includes) {
    String.prototype.includes = function (search, start) {
        'use strict';
        if (typeof start !== 'number') {
            start = 0;
        }

        if (start + search.length > this.length) {
            return false;
        } else {
            return this.indexOf(search, start) !== -1;
        }
    };
}

///keep IE from complaining about console.log
var alertFallback = false;
if (typeof console === "undefined" || typeof console.log === "undefined") {
    console = {};
    if (alertFallback) {
        console.log = function (msg) {
            alert(msg);
        };
    } else {
        console.log = function () {
        };
    }
}

$(function () {
    function pageUnload(e) {
        if (document.getElementById("rsIframe")) {
            return 'The form has not been completed are you sure you want to leave?';
        }
        ;
    };
    $(window).on('mouseover', function () {
        window.onbeforeunload = null;
    });
    $(window).on('mouseout', function () {
        window.onbeforeunload = pageUnload;
    });
});

(function () {
    //*******************
    var interval = 2000;
    //*******************
    var originalTitle = document.title;
    var changeTitle = false;
    var timeoutClear;

    function changeTitleName() {
        if (changeTitle) {
            var array = ['Come back', 'Submit Your Loan Request', 'Get money as fast as tomorrow'];
            var titlePromise = Promise.resolve();
            array.forEach(function (el) {
                titlePromise = titlePromise.then(function () {
                    document.title = el;
                    return new Promise(function (resolve) {
                        timeoutClear = setTimeout(resolve, interval);
                    });
                });
            });
            titlePromise.then(function () {
                changeTitleName();
            });
        }
    }

    var hidden = "hidden";

    // Standards:
    if (hidden in document)
        document.addEventListener("visibilitychange", onchange);
    else if ((hidden = "mozHidden") in document)
        document.addEventListener("mozvisibilitychange", onchange);
    else if ((hidden = "webkitHidden") in document)
        document.addEventListener("webkitvisibilitychange", onchange);
    else if ((hidden = "msHidden") in document)
        document.addEventListener("msvisibilitychange", onchange);
    // IE 9 and lower:
    else if ("onfocusin" in document)
        document.onfocusin = document.onfocusout = onchange;
    // All others:
    else
        window.onpageshow = window.onpagehide
            = window.onfocus = window.onblur = onchange;

    function onchange(evt) {
        var v = "visible", h = "hidden",
            evtMap = {
                focus: v, focusin: v, pageshow: v, blur: h, focusout: h, pagehide: h
            };

        evt = evt || window.event;
        if (evt.type in evtMap) {
            // console.log(evtMap[evt.type]);
            if (evtMap[evt.type] == "hidden") {
                changeTitle = true;
                changeTitleName();
            } else {
                changeTitle = false;
                clearTimeout(timeoutClear);
                document.title = originalTitle;
            }
        } else {
            // console.log(this[hidden] ? "hidden" : "visible");
            if (this[hidden]) {
                changeTitle = true;
                changeTitleName();
            } else {
                changeTitle = false;
                clearTimeout(timeoutClear);
                document.title = originalTitle;
            }
        }
    }

    // set the initial state (but only if browser supports the Page Visibility API)
    if (document[hidden] !== undefined)
        onchange({type: document[hidden] ? "blur" : "focus"});
})();

$( document ).ready(function() {
    $('.open-nav').click(function() {
        $('.header-nav-wrapper').addClass("show-nav");
        $('body').addClass("overflow");
    });

    $('.close-nav').click(function() {
        $('.header-nav-wrapper').removeClass("show-nav");
        $('body').removeClass("overflow");
    });
});

function showLoading(message) {
    $("#loading-zone").find(".content").html(message);
    $("#loading-zone").show();
}

function hideLoading() {
    $("#loading-zone").find(".content").html('');
    $("#loading-zone").hide();
}


function goNextForm(mainParent){
    var errors = mainParent.find(".has-error");
    let nextBtn = mainParent.find("button.next-page");
    if(nextBtn!=null){
        nextBtn.html("Continue");
    }
    if(errors==null || errors.length<=0) {
        let nextElem = mainParent.next();
        if (nextElem != null && nextElem.length > 0) {
            $(".page-step").removeClass("active");
            nextElem.addClass("active");

            let totalStep = $(".page-step").length;
            let indexNu = nextElem.index();
            indexNu = indexNu*1;
            indexNu++;
            indexNu = indexNu*100;
            let percentage = indexNu/totalStep;
            percentage = percentage.toFixed(2);
            percentage = percentage.toString().replace(".00","");
            $("#progressbar").find(".ui-progressbar-value").attr("style", "width: "+percentage+"%");
            $("#progresstext").html("PROGRESS: "+percentage+"%");
        }
    }
}

function adjustMergeElements(mainParent){
    mainParent.find(".merger-values").each(function () {
       let mergeGroupElem = $(this);
       let mergeElemVal = "";
       let mergeTargetVal = mergeGroupElem.attr("data-target");
       let mergeTargetElem = $("#"+mergeTargetVal);
       if(mergeTargetElem!=null && mergeTargetElem.length>0) {
           mergeGroupElem.find(".merge-item").each(function () {
               let mergeElem = $(this);
               let nearestInputGroup = mergeElem.closest(".input-group");
               let mergeItemVal = mergeElem.val().toString().trim();
               let mergeElemId = mergeElem.attr("id");
               if (mergeItemVal === '' && nearestInputGroup != null && nearestInputGroup.length > 0) {
                   if (!nearestInputGroup.hasClass("has-error")) {
                       nearestInputGroup.addClass("has-error");
                   }
               } else {
                   if (nearestInputGroup != null && nearestInputGroup.length > 0) {
                       nearestInputGroup.removeClass("has-error");
                   }

                   switch (mergeElemId) {
                       case 'birthdate_month':
                           mergeItemVal = mergeItemVal+"/";
                           break;
                       case 'date_month':
                           mergeItemVal = mergeItemVal+" ";
                           break;
                       case 'birthdate_day':
                           mergeItemVal = mergeItemVal+"/";
                           break;
                   }

                   mergeElemVal = mergeElemVal+mergeItemVal;

               }
           });
           //console.log(mergeElemVal);
           mergeTargetElem.val(mergeElemVal);
       }
    });
}

function addError(element, errorMsg = "Invalid Data"){
    console.table({
        error: errorMsg
    });
    let closedGroup = element.closest(".input-group");
    //console.log(closedGroup);
    closedGroup.addClass("has-error");
    closedGroup.removeClass("has-success");
    closedGroup.find(".help-block").html(errorMsg);
}

function lastFourSSN(element) {
    let val = element.val().toString();
    if(val.length.toString() !== "4"){
        $('#form-lander').yiiActiveForm('updateAttribute', element.attr("id"), ["Last 4 digits of SSN should be 4 digits long only."]);
    }
}

function checkPinCode(element) {
    let val = element.val().toString();
    let isValidZip = /(^\d{5}$)|(^\d{5}-\d{4}$)/.test(val);
    if(!isValidZip){
        $('#form-lander').yiiActiveForm('updateAttribute', element.attr("id"), ["Pin code is not valid."]);
    }
}

function checkRoutingNumber(element) {
    let val = element.val().toString();
    aba = val;
    var a = "0123456789";
    var e = aba.length;
    var k = true;
    var j = parseInt(aba);
    var c = aba.toString();
    var f = 0;
    var b = false;
    var g;
    if (e != 0) {
        if (e != 9) {
            $('#form-lander').yiiActiveForm('updateAttribute', element.attr("id"), ["Invalid routing number."]);
        } else {
            for (var d = 0; d < e; d += 3) {
                f += parseInt(c.charAt(d), 10) * 3 + parseInt(c.charAt(d + 1), 10) * 7 + parseInt(c.charAt(d + 2), 10)
            }
            if (f != 0 && f % 10 == 0) {

            } else {
                $('#form-lander').yiiActiveForm('updateAttribute', element.attr("id"), ["The Routing Number entered is NOT a valid ABA Routing Number!"]);
            }
        }
    }
}

function runCustomConditions(formElement){
    var elementName = formElement.attr("name");
    switch (elementName) {
        case 'Submissions[last_four_ssn]':
            lastFourSSN(formElement);
            break;
        case 'Submissions[zip_code]':
            checkPinCode(formElement);
            break;
        case 'Submissions[bank_routing_number]':
            checkRoutingNumber(formElement);
            break;
    }
}

$(document).on("click", ".next-page", function () {
    let obj = $(this);
    let mainParent = obj.closest(".page-step");
    let nextBtn = mainParent.find("button.next-page");
    var formElements = mainParent.find(".form-control");

    if(nextBtn!=null){
        nextBtn.html("Loading..");
    }

    adjustMergeElements(mainParent);

    if(formElements!=null && formElements.length>0) {
        formElements.each(function () {
            var elem = $(this);
            $('#form-lander').yiiActiveForm('validateAttribute', elem.attr("id"));
            runCustomConditions(elem);
            setTimeout(e=>{
                goNextForm(mainParent);
            },500);
        });
    }else{
        goNextForm(mainParent);
    }


    /*setTimeout(e=>{
        var errors = mainParent.find(".has-error");
        console.log(errors);
        if(errors==null || errors.length<=0) {
            let nextElem = mainParent.next();
            if (nextElem != null && nextElem.length > 0) {
                $(".page-step").removeClass("active");
                nextElem.addClass("active");
            }
        }
    },250);*/

});


$(document).on("click", ".btn-back", function () {
    let obj = $(this);
    let mainParent = obj.closest(".page-step");
    let prevElem = mainParent.prev();
    if(prevElem!=null && prevElem.length>0){
        $(".page-step").removeClass("active");
        prevElem.addClass("active");
    }
});


$(document).on("click", ".hidden-elem-assign", function () {
    let elem = $(this);
    let val = elem.attr("data-value");
    let target = elem.attr("data-target");
    let targetElem = $("#"+target);
    let mainParent = elem.closest(".page-step");
    if(targetElem!=null && targetElem.length>0){
        targetElem.val(val);
        goNextForm(mainParent);
    }
});


$(document).on("submit", "#form-lander", function (e) {
    var form = $(this);
    var data = form.serialize();
    if($("#g-recaptcha-response").val().trim()==""){
        alert("Please fill the captcha");
    }else {
        showLoading(' <br>Please wait while we submit your information to lenders.');
        $.post(baseUrl + "site/lead-submit", data, function (r) {
            var data = $.parseJSON(r);
            if (data.url) {
                $("#mainLinkChange").attr("href", data.url);
                window.location.replace(data.url);
            }
        });
    }
    e.preventDefault();
    return false;
});


$(document).on("keyup", ".jump-next", function () {
   let elem = $(this);
   let elemMax = elem.attr("data-max");
   let elemMin = elem.attr("data-max");
   let elemNxtElemId = elem.attr("data-next");
   let val = elem.val().toString();
   if (val.length >= elemMax) {
        val = val.substring(0, elemMax);
        let nextElem = $("#" + elemNxtElemId);
        if (nextElem != null && nextElem.length > 0) {
            nextElem.focus();
        }
   }
   elem.val(val);
});
