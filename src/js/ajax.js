    //aqui nós definimos uma função asíncrona que vai enviar os dados para a nossa services
Document.getElementById("FormBancoHoras").addEventListener("submit", async function e(){
    e.preventDefault();

let formData = new formdata(this);
//definimos o caminho do arquivo, o methodo como post e o body, que vai ser formData, que são os dados que serão enviados
let response = await fetch("src/services/RegistroHorasServices.php",{
    method:"POST",
    body: formData
});
// espera a resposta do json
let result = await response.json ();
//agora pegamos o json da controller, com a mensagem e o status e exibimos uma mensagem de sucesso se conseguiu gravar no banco, ou de erro, se não

if(result.status==="ok"){
    document.getElementById("resposta").textContent=result.mensagem;
} else if(result.status==="erro"){
    document.getElementById("resposta").textContent=result.erro;
}

});
    //aqui nós definimos uma função asíncrona que vai enviar os dados para a nossa services
Document.getElementById("FormBancoHoras").addEventListener("submit", async function e(){
    e.preventDefault();

let formData = new formdata(this);
//definimos o caminho do arquivo, o methodo como post e o body, que vai ser formData, que são os dados que serão enviados
let response = await fetch("src/services/RegistroHorasServices.php",{
    method:"POST",
    body: formData
});
// espera a resposta do json
let result = await response.json ();
//agora pegamos o json da controller, com a mensagem e o status e exibimos uma mensagem de sucesso se conseguiu gravar no banco, ou de erro, se não

if(result.status==="ok"){
    document.getElementById("resposta").textContent=result.mensagem;
} else if(result.status==="erro"){
    document.getElementById("resposta").textContent=result.erro;
}

});


    //aqui nós definimos uma função asíncrona que vai enviar os dados para a nossa services
Document.getElementById("FormBancoHoras").addEventListener("submit", async function e(){
    e.preventDefault();

let formData = new formdata(this);
//definimos o caminho do arquivo, o methodo como post e o body, que vai ser formData, que são os dados que serão enviados
let response = await fetch("src/services/RegistroHorasServices.php",{
    method:"POST",
    body: formData
});
// espera a resposta do json
let result = await response.json ();
//agora pegamos o json da controller, com a mensagem e o status e exibimos uma mensagem de sucesso se conseguiu gravar no banco, ou de erro, se não

if(result.status==="ok"){
    document.getElementById("resposta").textContent=result.mensagem;
} else if(result.status==="erro"){
    document.getElementById("resposta").textContent=result.erro;
}

});

