/* Ondřej Fojt, xfojto00 */
/*
$.getJSON("../stats/services.php", function(){
    //success
})
.done(function(){
    //success 2
})
.fail(function(jqhxr, text, err){
    //err
    console.log("REQ FAIL" + text + " - " + err);
})
.always(function(){
    //fin
}); */

var prevPercentages = [];
var search_filter = "";
var onHints = false;

/* tohle je poměrně užitečná funkce a tohle vypadá, že je uděláno správně
https://www.sitepoint.com/delay-sleep-pause-wait/ */
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function reset_plot(plot=$(".bar-plot>tbody")) {
    plot.empty()
}

function add_plot_record(legend, percentage, prevPercentage, plot=$(".bar-plot>tbody")) {
    var record = $("<tr></tr>");
    record.addClass("bar-row");
    record.append($("<td></td>").text(legend).addClass("bar-legend").addClass("text-primary").addClass("clickable"));
    var bar = $("<span></span>").html("&nbsp;").css("display", "block").addClass("clr-primary").css("width", prevPercentage.toString()+"%");
    record.append($("<td></td>").addClass("bar-bar").append(bar));
    plot.append(record);
    $(bar).animate({width: percentage.toString()+"%"});
}

function load_plot(index=-1) {
    let data_in = {request: "load_plot", date_index: index};
    if(search_filter != "") data_in.search = search_filter;
    $.getJSON("../stats/services_cap.php", data_in, function(json){
        //success
        set_current_date(json.date, json.date_index, json.today, json.last);
        reset_plot();
        var i = 0;
        $.each(json.capacity, function(key, val) {
            if(prevPercentages[i] == undefined) prevPercentages[i] = 0;
            add_plot_record(key, val, prevPercentages[i]);
            prevPercentages[i] = val;
            ++i;
        });
        for(; i < prevPercentages.length; ++i) {
            prevPercentages[i] = 0;
        }
    })
    .fail(function(jqhxr, text, err){
        //err
        console.log("REQ FAIL" + text + " - " + err);
    })
    .always(function(){
        //fin
    });
}

function load_plot_service() {

}

function set_current_date(date, index=-2, first=false, last=false) {
    $("#date-scroller-text").val(date);
    $("#date-scroller-text").data("index", index);
    if(first) $("#date-scroller-left-btn").addClass("hidden");
    else {
        $("#date-scroller-left-btn").removeClass("hidden");
        $("#date-scroller-left-btn").data("index", index-1);
    }
    if(last) $("#date-scroller-right-btn").addClass("hidden");
    else {
        $("#date-scroller-right-btn").removeClass("hidden");
        $("#date-scroller-right-btn").data("index", index+1);
    }
}

var already_focused = false;
var start_date = "";
function change_date_manual() {
    if(already_focused) return;
    already_focused = true;
    $("#date-scroller-text").blur();
    //nalezení limitů
    let data_in = {request: "change_date_manual"};
    $.getJSON("../stats/services_cap.php", data_in, function(json) {
        $("#date-scroller-text").focus();
        $("#date-scroller-text").attr("min", json.from).attr("max", json.to);
        start_date = new Date(json.from);
    })
    .fail(function(jqhxr, text, err){
        //err
        console.log("REQ FAIL" + text + " - " + err);
    })
    .always(function(){
        //fin
        already_focused = false;
    });

}

function load_main_page() {
    hide_search_hints();
    load_plot();
}

function remove_hints(hints=$("#search-results")) {
    hints.empty();
}

function add_hint(text, hints=$("#search-results")) {
    let hint = $("<span></span").addClass("clickable").addClass("search-hint").text(text);
    hint.click(function(){
        hide_search_hints();
        $("#search").val($(this).html());
        $("#search-btn").click();
    });
    hints.append(hint);
}

var searching_hints = false;
var search_queue = false;
function show_search_hints(search_val, hints=$("#search-results")) {
    if(searching_hints) return;
    searching_hints = true;
    hints.removeClass("hidden");
    $("#people-btn").addClass("hidden");
    $("#calendar-btn").addClass("hidden");

    remove_hints();

    //získání nápovědy
    let data_in = {request: "search_hints"};
    data_in.search = search_val;
    $.getJSON("../stats/search.php", data_in, function(json) {
        $.each(json, function(key, val) {
            add_hint(val);
        });
    })
    .fail(function(jqhxr, text, err){
        //err
        console.log("REQ FAIL" + text + " - " + err);
    })
    .always(function(){
        //fin
        searching_hints = false;
    });
}

function hide_search_hints(hints=$("#search-results")) {
    hints.addClass("hidden");
    $("#people-btn").removeClass("hidden");
    $("#calendar-btn").removeClass("hidden");
}

function load_workers() {

}

function load_calendar() {

}

$(document).ready(function(){
    $("#home-btn").click(load_main_page);

    $("#date-scroller-left-btn").click(function(){
        load_plot($(this).data("index"));
    });
    $("#date-scroller-right-btn").click(function(){
        load_plot($(this).data("index"));
    });
    $("#date-scroller-text").focus(change_date_manual);
    $("#date-scroller-text").change(function(){
        let index = (new Date($(this).val()) - start_date)/(24*60*60*1000);
        load_plot(index);
    });

    $("#search").focus(function(){
        show_search_hints($(this).val());
    });
    $("#search").keyup(async function(event){
        if(event.which == 13) return;
        if(searching_hints) {
            if(search_queue) return
            search_queue = true;
            await sleep(500);  
            search_queue = false;
        }
        show_search_hints($(this).val());
    });
    $("#search").on("keypress", function(event) {
        //13 je kód klávesy enter 
        if(event.which == 13) $("#search-btn").click();
    });
    $("#search").blur(function() {
        if(onHints) return;
        hide_search_hints();
        $("#search-btn").click();
    });

    $("#search-btn").click(function(){
        search_filter = $("#search").val();
        load_plot($("#date-scroller-text").data("index"));
    });

    $("#search-results").mouseenter(function(){
        onHints = true;
    });

    $("#search-results").mouseleave(function(){
        onHints = false;
    });

    $("#people-btn").click();

    $("#calendar-btn").click();

    load_main_page();
});
