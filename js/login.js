const form = document.getElementById("form");
const telefone = document.getElementById("telefone");
const senha = document.getElementById("senha");

$('#mensagem').fadeOut(0);

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


telefone.addEventListener("blur",() =>{
    checa_telefone();
})
senha.addEventListener("blur",() =>{
    checa_senha();
})


//checagens--------------------------------------------------------



function checa_telefone() {
    const valor_telefone = telefone.value;

    if (valor_telefone === "") {
        error_imput(telefone, "digite seu telefone");
    } else if (valor_telefone.length != 15) {
        error_imput(telefone, "seu telefone esta incompleto");
    }
    else {
        const form_item = telefone.parentElement;
        form_item.className = "form_content";
    }

}

function checa_senha() {
    const valor_senha = senha.value;

    if (valor_senha === "") {
        error_imput(senha, "senha invalida");
    } else {
        const form_item = senha.parentElement;
        form_item.className = "form_content";
    }

}



//validação final------------------------------------------------------------

function checa_form(){
    checa_telefone();
    checa_senha();

    const form_item= form.querySelectorAll(".form_content");
    
    const validado = [...form_item].every( (item) => {
        return item.className === "form_content"
    });

    if(validado){
            tel=$('#telefone').val();
            se=$('#senha').val();

            // $('#mensagem').html("Usuário ok");
            //$('#mensagem').fadeIn(300).delay(2000).fadeOut(400);
            $.ajax({
                url: 'efetuaLogin.php',
                type: 'POST',
                data: {telefone:tel,senha:se},

                success: function(response) {
                   
                    response=response.trim();

                    if(response !== "erro"){
                        $('#mensagem').html("Você sera relacionado");
                        $('#mensagem').fadeIn(300).delay(2000).fadeOut(400);

                        var dados = response.split("|");
                        var id = dados[0];
                        var nome = dados[1];
                        var tipo = dados[2];
                      
                        if(tipo == "admin"){
                            window.location.href = "cadastraPedido.php?id="+id;
                        }else if( tipo == "cliente"){
                        //window.location.href = "principal.php?id="+id+"&nome="+nome+"&tipo="+tipo;
                        try { localStorage.setItem('clienteId', id); } catch(e){}
                        window.location.href = "index.php";
                        }
                    }else{
                         $('#mensagem').html("Algun doscampos esta incorreto");
                        $('#mensagem').fadeIn(300).delay(2000).fadeOut(400);
                    }
                    
                    
                },
                    error: function(xhr, status, error) {
                        console.log("Erro na requisição: ", error);
                    }
                

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