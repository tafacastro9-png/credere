$('.btn-del').on('click', function (e) {
    e.preventDefault();
    const href = $(this).attr('href')

    Swal.fire({
        title: "¿Estas seguro de eliminar este registro?",
        text: "!No podras revertir esta acccion!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!"
    }).then((result) => {
        if (result.value) {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Eliminado!",
                    text: "El registro fue eliminado",
                    icon: "success"
                });

            }
            document.location.href = href;
        }

    });
})