/**
 * Created by Nathan on 9/28/2015.
 */
/*alert("hello for Dashboard.js");*/

/*alert("Here is the URL:" + url);*/
$.ajax({
    url: url + "/DashboardController/AjaxTestData",
    async: true


})
    .done(function (json) {
        /*$('#AjaxTestData-1').append(json);
        var parsejson = JSON.parse(json);
        $('#AjaxTestData-2').append(parsejson[0]['test_id']);*/

        Morris.Line({
            element: 'morris-area-chart',
            data: JSON.parse(json),
            xkey: 'test_id',
            ykeys: ['total_post', 'total_non_gibberish','total_gibberish' ],
            labels: ['Post','Non Gibberish', 'Gibberish'],
            pointSize: 2,
            resize: true,
            fillOpacity:.2,
            behaveLikeLine: true,
            parseTime:false
        });
    })
    .fail(function () {
        // this will be executed if the ajax-call had failed
        alert("Ajax failed");
    })
    .always(function () {
        // this will ALWAYS be executed, regardless if the ajax-call was success or not
    });


$.ajax({
    url: url + "/DashboardController/AjaxGetAverages",

})
    .done(function (json) {
        /*$('#AjaxGetAverages-1').append(json);
        var parsejson = JSON.parse(json);
        $('#AjaxGetAverages-2').append(parsejson[0]['test_id']);*/

        Morris.Bar({
            element: 'morris-bar-chart',
            data: JSON.parse(json),
            xkey: 'test_id',
            ykeys: ['Average_Gibberish_Score','Average_Total_Words','Average_English_Words','Average_Unique_Words','Average_English_Words','Average_Average_Word_Length'],
            labels: ['Average Gibberish Score','Average Total Words','Average English Words','Average Unique Words','Average English Words','Average Average Word Length'],
            hideHover: 'auto',
            resize: true
        });
    })
    .fail(function () {
        // this will be executed if the ajax-call had failed
        alert("Ajax failed");
    })
    .always(function () {
        // this will ALWAYS be executed, regardless if the ajax-call was success or not
    });
