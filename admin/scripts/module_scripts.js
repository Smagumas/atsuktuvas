function update_ajax(table, pk, pk_val, field, val, klase) {
    $.post("/ajax/admin.php", { table: table, pk: pk, pk_val: pk_val, field: field, val: val, action: 'update', klase: klase},
        function (data) {
            $('.' + klase + '').html(data);
        });
}
function update_ajax_tvirtinu(table, pk, pk_val, field, val, klase) {

    var isGood = confirm('Ar tikrai patvirtinti?');
    if (isGood) {


        $.post("/ajax/admin_tvirtinu.php", { table: table, pk: pk, pk_val: pk_val, field: field, val: val, action: 'update', klase: klase},
            function (data) {
                $('.' + klase + '').html(data);
            });


    } else {
        return false;
    }
}
function ajax_delete(table, pk, pk_val) {
    var answer = confirm("Ar tikrai norite ištrinti?");
    if (answer) {
        $.post("/ajax/admin.php", { table: table, pk: pk, pk_val: pk_val, action: 'delete'},
            function (data) {
                $('#idas_' + pk_val + '').fadeOut();
            });
    }
    else {
        return false;
    }
}