
const btn_clear = document.getElementById("clear_fields");
const box_title = document.getElementById("box_title");
const box_id = document.getElementById("box_id_user");
const box_end = document.getElementById("box_end_date");
const box_secret = document.getElementById("box_secret_code");
const btn_submit = document.getElementById("btn_submit");
const info = document.getElementById("box_info");
const cache_emptied = document.getElementById("cache_emptied").value;
const data_saved = document.getElementById("data_saved").value;
const fill_fields = document.getElementById("fill_fields").value;


btn_clear.addEventListener('click', function () {
    box_title.value = "";
    box_id.value = "";
    box_end.value = "";
    box_secret.value = "";
    info.innerHTML = cache_emptied;
});


btn_submit.addEventListener('click', function () {
    console.log(box_title.value + " box_title");
    console.log(box_id.value + " box_id");
    console.log(box_end.value + " box_end");
    console.log(box_secret.value + " box-secret");
    if (box_title.value == "" || box_id.value == "" || box_end.value == "" || box_secret.value == ""){
        info.innerHTML = fill_fields;
        btn_submit.disabled = true;
    }else{
        info.innerHTML = data_saved;
    }
});

box_title.addEventListener('click', function () {
    btn_submit.disabled = false;
    info.innerHTML = "";
});

box_secret.addEventListener('click', function () {
    btn_submit.disabled = false;
    info.innerHTML = "";
});

box_end.addEventListener('click', function () {
    btn_submit.disabled = false;
    info.innerHTML = "";
});

box_id.addEventListener('click', function () {
    btn_submit.disabled = false;
    info.innerHTML = "";
});
