function handleUpdate(n, e) {

    if (e.value != n) {
        e.nextElementSibling.style.opacity = '1';
        e.nextElementSibling.style.pointerEvents = 'auto';
    }else {
        e.nextElementSibling.style.opacity = '0.2';
        e.nextElementSibling.style.pointerEvents = 'none';
    }
    
} 

function handlePaymentMethod(){
    const btn = document.getElementById('confirmPaymentMethod');
    console.log(btn);
    btn.style.opacity = '1';
    btn.style.pointerEvents = 'auto';
}

function handlePoints(e) {
    const points = document.querySelector(".points_display");
    points.textContent = "Rs. " + e.value;

}

const stars = document.querySelectorAll(".star");
console.log(stars);
stars.forEach(st => {
    st.addEventListener('click', ()=> {
        if (st.classList.contains("active")) {
            st.classList.remove("active");

        }else {
            st.classList.add("active");
        }
    })
});


function handlePopup() {
    console.log('click');
    let previewContainer = document.querySelector('.products-preview');
    let previewBox = previewContainer.querySelectorAll('.preview');

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

    previewContainer.addEventListener('click', event => {
        if (event.target.classList.contains('fa-times')) {
            previewBox.forEach(preview => {
                preview.classList.remove('active');
                previewContainer.style.display = 'none';
            });
        }
    });
}

function decrease(e) {
    if(e.nextElementSibling.value>0)
        e.nextElementSibling.value = parseInt(e.nextElementSibling.value) - 1;
}
function increase(e) {
    if(e.previousElementSibling.value<100)
        e.previousElementSibling.value  = parseInt(e.previousElementSibling.value) + 1;
}

handlePopup();
