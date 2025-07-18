const form = document.getElementById("form");
const nome = document.getElementById("nome");
const sobrenome = document.getElementById("sobrenome");
const telefone = document.getElementById("telefone");
const senha = document.getElementById("senha");
const senha_confirmacao = document.getElementById("senha_confirmacao");

form.addEventListener("submit", (event) => {
    event.preventDefault();

    checa_form();
})

telefone.addEventListener("input", function () {
    let valor = telefone.value;

    // Remove tudo que não for número
    valor = valor.replace(/\D/g, "");

    // Formata com máscara (11) 98765-4321
    if (valor.length > 0) {
        valor = "(" + valor;
    }
    if (valor.length > 3) {
        valor = valor.slice(0, 3) + ") " + valor.slice(3);
    }
    if (valor.length > 10) {
        valor = valor.slice(0, 10) + "-" + valor.slice(10);
    }
    if (valor.length > 15) {
        valor = valor.slice(0, 15);
    }

    telefone.value = valor;
});

nome.addEventListener("blur",() =>{
    checa_nome();
})
sobrenome.addEventListener("blur",() =>{
    checa_sobrenome();
})
telefone.addEventListener("blur",() =>{
    checa_telefone();
})
senha.addEventListener("blur",() =>{
    checa_senha();
})
senha_confirmacao.addEventListener("blur",() =>{
    checa_senhaConfimacao();
})


//checagens--------------------------------------------------------
function checa_nome() {
    const valor_nome = nome.value;
    if (valor_nome === "") {
        error_imput(nome, "preencha um nome de usuario");
    } else {
        const form_item = nome.parentElement;
        form_item.className = "form_content";
    }
}

function checa_sobrenome() {
    const valor_sobrenome = sobrenome.value;
    if (valor_sobrenome === "") {
        error_imput(sobrenome, "preencha seu sobrenome");
    } else {
        const form_item = sobrenome.parentElement;
        form_item.className = "form_content";
    }
}

function checa_telefone() {
    const valor_telefone = telefone.value;

    if (valor_telefone === "") {
        error_imput(telefone, "preencha seu telefone");
    } else if (valor_telefone.length != 15) {
        error_imput(telefone, "preencha seu numero completo");
    }
    else {
        const form_item = telefone.parentElement;
        form_item.className = "form_content";
    }

}

function checa_senha() {
    const valor_senha = senha.value;

    if (valor_senha === "") {
        error_imput(senha, "digite uma senha");
    } else if (valor_senha.length < 8) {
        error_imput(senha, "minimo de 8 caracteres");
    }
    else {
        const form_item = senha.parentElement;
        form_item.className = "form_content";
    }

}

function checa_senhaConfimacao() {
    const valor_senha = senha.value;
    const valor_senhaConfirmacao = senha_confirmacao.value;

    if (valor_senhaConfirmacao === "") {
        error_imput(senha_confirmacao, "repita sua senha");
    } else if (valor_senhaConfirmacao != valor_senha) {
        error_imput(senha_confirmacao, "sua senha não esta igual");
    }
    else {
        const form_item = senha_confirmacao.parentElement;
        form_item.className = "form_content";
    }

}

//validação final------------------------------------------------------------

function checa_form(){
    checa_nome();
    checa_sobrenome();
    checa_telefone();
    checa_senha();
    checa_senhaConfimacao();

    const form_item= form.querySelectorAll(".form_content");
    
    const validado = [...form_item].every( (item) => {
        return item.className === "form_content"
    });

    if(validado){
       $(document).ready(function () {
            nomes=$('#nome').val();
            sobrenomes=$('#sobrenome').val();
            telefones=$('#telefone').val();
            senhas=$('#senha').val();
            
            $.ajax({
                url: 'cadastraLogin.php',
                type: 'POST',
                data: { nome: nomes, sobrenome: sobrenomes, telefone: telefones, senha: senhas },
                success: function (response) {
                    response = response.trim();
                    if (response != "erro") {
                        alert("cadastro com sucesso")
                        
                    } else {
                        console.log("Erro no servidor ao cadastrar.");
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Erro na requisição: ", error);
                }
            });
        });
    }

}

//mensagemde erro------------------------------------------------------
function error_imput(input, message) {
    const form_item = input.parentElement;
    const text_message = form_item.querySelector("a");

    text_message.innerText = message;
    form_item.className = "form_content error";
}