const sidebar = document.querySelector('.side-bar');
function handleNav() {
    if (sidebar.classList.contains("open")) {
        sidebar.classList.remove("open");
    }else {
       sidebar.classList.add("open");
    }
}