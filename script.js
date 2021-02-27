function showActivity(...manyArgs) {
    manyArgs.forEach(function (item, array) {
        let activity;
        activity = document.getElementById(item);
        activity.disabled = false;
    });
}


function hideActivity(...manyArgs) {
    manyArgs.forEach(function (item, array) {
        let activity;
        activity = document.getElementById(item);
        activity.disabled = true;
    });
}
///////////


document.getElementById("librarian_add").addEventListener('change', function () { showActivity('id-librarian_pass', 'id-librarian_login'); });
document.getElementById("librarian_remove").addEventListener('change', function () { hideActivity('id-librarian_pass'); });
document.getElementById("librarian_update").addEventListener('change', function () { showActivity('id-librarian_pass', 'id-librarian_login'); });

document.getElementById("machine_add").addEventListener('change', function () {
    showActivity('id-machine_name', 'id-machine_address', 'id-machine_size', 'id-machine_column');
    hideActivity('id-machine_id');
});
document.getElementById("machine_remove").addEventListener('change', function () {
    showActivity('id-machine_id');
    hideActivity('id-machine_name', 'id-machine_address', 'id-machine_size', 'id-machine_column');
});
document.getElementById("machine_update").addEventListener('change', function () {
    showActivity('id-machine_id', 'id-machine_name', 'id-machine_address', 'id-machine_size', 'id-machine_column');
});
