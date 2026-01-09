document.addEventListener('DOMContentLoaded', function(){
    // login form
    const loginForm = document.querySelector('form[action="src/services/AuthenticationService.php"]');
    if(loginForm){
        loginForm.addEventListener('submit', function(e){
            const matricula = document.getElementById('matricula').value.trim();
            const senha = document.getElementById('senha').value;
            if (!matricula || !senha){
                e.preventDefault();
                alert('Informe matrícula e senha.');
                return false;
            }
        });
    }

    // setpassword form validation
    const setPassForm = document.querySelector('form[action="src/services/PassVerify.php"]');
    if(setPassForm){
        setPassForm.addEventListener('submit', function(e){
            const s1 = document.getElementById('senha1');
            const s2 = document.getElementById('senha2');
            if (!s1 || !s2) return true;
            if (s1.value.length < 8 || s1.value.length > 32){
                e.preventDefault();
                alert('A senha deve ter entre 8 e 32 caracteres.');
                return false;
            }
            if (s1.value !== s2.value){
                e.preventDefault();
                alert('As senhas não conferem.');
                return false;
            }
        });
    }
});
