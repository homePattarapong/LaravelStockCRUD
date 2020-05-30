ClassicEditor.create(document.querySelector('#product_detail'))
    .catch(error => {
        console.error(error);
    });

$('.alert').fadeIn().delay(5000).fadeOut();
