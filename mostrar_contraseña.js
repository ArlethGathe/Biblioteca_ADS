document.addEventListener("DOMContentLoaded", function () {
    const mostrarPass = document.getElementById('mostrarPass');
    const campoPass = document.getElementById('clave');

    if (mostrarPass && campoPass) {
        mostrarPass.addEventListener('change', function () {
            campoPass.type = this.checked ? 'text' : 'password';
        });
    }
});
