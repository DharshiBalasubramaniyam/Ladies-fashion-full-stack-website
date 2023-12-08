document.addEventListener("DOMContentLoaded", function () {
    const menuBtn = document.getElementById("menu-btn");
    const categoriesForm = document.querySelector(".sub_header .items form");
    let previewContainer = document.querySelector('.products-preview');
    let previewBox = previewContainer.querySelectorAll('.preview');

    // menu button
    // menuBtn.addEventListener("click", function () {
    //     categoriesForm.classList.toggle("show");
    // });

    // pop up
    document.querySelectorAll('.cart-btn').forEach(product => {
        product.addEventListener('click', (event) => {
            event.preventDefault(); 
            previewContainer.style.display = 'flex';
            let name = product.getAttribute('name');
            previewBox.forEach(preview => {
                let target = preview.getAttribute('data-target');
                if (name == target) {
                    preview.classList.add('active');
                }
                console.log(name, target)

            });
            console.log(name)
        });
    });
    
    // pop up close
    previewContainer.addEventListener('click', event => {
        if (event.target.classList.contains('fa-times')) {
            previewBox.forEach(preview => {
                preview.classList.remove('active');
                previewContainer.style.display = 'none';
            });
        }
    });

});