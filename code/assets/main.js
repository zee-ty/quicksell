// QuickSell front-end JS (jQuery)

$(document).ready(function () {

    // live search filter on home page
    $('#searchBox').on('keyup', function () {
        var term = $(this).val().toLowerCase();
        $('.listing-card').each(function () {
            var text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(term) > -1);
        });
    });

    // confirm before deleting (admin)
    $('.btn-delete').on('click', function (e) {
        if (!confirm('Are you sure?')) e.preventDefault();
    });

    // basic form validation feedback
    $('form').on('submit', function () {
        $(this).find('button[type=submit]').prop('disabled', true).text('Processing...');
    });

});
