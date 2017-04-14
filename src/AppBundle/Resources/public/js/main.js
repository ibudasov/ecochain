$(document).ready(function () {
    let searchResultsSelector = '#search-results';

    $("#search-input").keyup(function (event) {
        let url = "/post/liveSearch.json?query=" + $('#search-input').val();
        $.get(url)
            .success(function(result){
                updateResults(result)
            })
            .fail(function () {
                console.log('Sorry, this does not work');
            });
    });

    function updateResults(result) {
        $(searchResultsSelector).empty();
        $(searchResultsSelector).append('<ul>');
        $.each(result.posts, function (i, val) {
            let htmlToAppend = '<li><a href="/read/' + val.id + '">' + val.title + '</a></li>';
            $(searchResultsSelector).append(htmlToAppend);
        });
        $(searchResultsSelector).append('</ul>');
    }
});