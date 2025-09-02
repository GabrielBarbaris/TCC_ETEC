const form = document.getElementById("form");
const produto = document.getElementById("produto");
const quantidade = document.getElementById("quantidade");
const corte = document.getElementById("corte");
const horario = document.getElementById("horario");
const cliente = document.getElementById("cliente");
const recebimento = document.getElementById("recebimento");



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
function checa_produto() {
    const valor_produto = produto.value;
    if (valor_produto === "") {
        error_imput(produto, "Preencha qual é o produto");
    } else {
        const form_item = nome.parentElement;
        form_item.className = "form_content";
    }
}

function checa_quantidade() {
    const valor_quantidade = quantidade.value;
    if (valor_quantidade === "") {
        error_imput(quantidade, "Preencha a quantidade");
    } else {
        const form_item = sobrenome.parentElement;
        form_item.className = "form_content";
    }
}

function checa_corte() {
    const valor_corte = corte.value;
    if (valor_corte === "") {
        error_imput(corte, "Preencha qual é o tipo de corte");
    } else {
        const form_item = corte.parentElement;
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
                        $('#mensagem').html("Cadastrado com sucesso");
                        $('#mensagem').fadeIn(300).delay(2000).fadeOut(400);
                        setTimeout(function(){
                            $('#form')[0].reset(); // Limpa o formulário após 2.5 segundos
                        }, 2500);
                        
                    } else {
                        console.log("Erro no servidor ao cadastrar.");
                         $('#mensagem').html("essa conta ja existe");
                        $('#mensagem').fadeIn(300).delay(2000).fadeOut(400);
                        setTimeout(function(){
                            $('#form')[0].reset(); // Limpa o formulário após 2.5 segundos
                        }, 2500);
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

//Campo endereço------------------------------------------------------
const radios = document.querySelectorAll('input[name="recebimento"]');
  const campoEndereco = document.getElementById('endereco');

  radios.forEach(radio => {
    radio.addEventListener('change', () => {
      if (document.getElementById('entrega').checked) {
        campoEndereco.classList.remove('hidden');
      } else {
        campoEndereco.classList.add('hidden');
      }
    });
  });