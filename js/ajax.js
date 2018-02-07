
$('#start-db').click(function() {

    $.ajax({
        type: "POST",
        url: "ajax-queries/ajax-create-db.php"
    }).done(function( msg ) {
        alert( msg );
    });

});

$('#start-table').click(function() {

    $.ajax({
        type: "POST",
        url: "ajax-queries/ajax-create-table.php"
    }).done(function( msg ) {
        alert( msg );
    });

});

$('#start-data').click(function() {

    $.ajax({
        type: "POST",
        url: "ajax-queries/ajax-create-data.php"
    }).done(function( msg ) {
        alert( msg );
        window.location.reload(true);
    });

});