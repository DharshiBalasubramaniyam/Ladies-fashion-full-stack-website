const sidebar = document.querySelector('.side-bar');
function handleNav() {
    if (sidebar.classList.contains("open")) {
        sidebar.classList.remove("open");
    }else {
       sidebar.classList.add("open");
    }
}
const pop_up = document.querySelector('.pop-up-container');
const color_form = document.querySelector('.add-color');
const size_form = document.querySelector('.add-size');


function openColor() {
    pop_up.style.display = 'flex';
    color_form.style.display = 'flex';
    size_form.style.display = 'none';
}
function openSize() {
    pop_up.style.display = 'flex';
    size_form.style.display = 'flex';
    color_form.style.display = 'none';
}
pop_up.addEventListener('click', event => {
    if (event.target.classList.contains('fa-times')) {
        pop_up.style.display = 'none';
    }
});