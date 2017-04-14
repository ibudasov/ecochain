$(document).ready(function () {
    $("#search-input").keyup(function (val) {

        console.log($('#search-input').val());
        // link: http://api.jquery.com/jquery.ajax/
        $.get("/post/liveSearch.json?query=" + $('#search-input').val()).success(function (result) {

            $('#search-results').append('<ul>');
            $.each(result.posts, function (i, val) {
                $('#search-results').append('<li><a href="/read/' + val.id + '">' + val.title + '</a></li>');
            });
            $('#search-results').append('</ul>');

        }).fail(function () {
            console.log('Sorry, this does not work');
        }).before(function () {
            $('#search-results').empty();
        });
    });
});