document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.mostrarsenha');
    buttons.forEach(function(toggleButton){
        toggleButton.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordField = document.getElementById(targetId);
            if (!passwordField) return;
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                this.textContent = 'Ocultar senha';
                passwordField.setAttribute('aria-label', 'Senha visível');
                this.setAttribute('aria-pressed', 'true');
                this.setAttribute('aria-label', 'Ocultar senha');
            } else {
                passwordField.type = 'password';
                this.textContent = 'Mostrar senha';
                passwordField.setAttribute('aria-label', 'Senha oculta');
                this.setAttribute('aria-pressed', 'false');
                this.setAttribute('aria-label', 'Mostrar senha');
            }
        });
    });
});
