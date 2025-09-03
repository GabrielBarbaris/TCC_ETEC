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

produto.addEventListener("blur",() =>{
    checa_produto();
})
quantidade.addEventListener("blur",() =>{
    checa_quantidade();
})
corte.addEventListener("blur",() =>{
    checa_corte();
})
horario.addEventListener("blur",() =>{
    checa_horario();
})
cliente.addEventListener("blur",() =>{
    checa_cliente();
})
recebimento.addEventListener("blur",() =>{
    checa_recebimento();
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

function checa_horario() {
    const valor_horario = horario.value;
    if (valor_horario === "") {
        error_imput(horario, "Preencha o horário");
    } else {
        const form_item = horario.parentElement;
        form_item.className = "form_content";
    }
}

function checa_cliente() {
    const valor_cliente = cliente.value;
    if (valor_cliente === "") {
        error_imput(cliente, "Preencha o nome do cliente");
    } else {
        const form_item = cliente.parentElement;
        form_item.className = "form_content";
    }
}

//validação final------------------------------------------------------------
/*if (validado) {
    $.ajax({
        url: "cadastraPedido.php", // destino do PHP
        type: "POST",
        data: {
            produto: produto.value,
            quantidade: quantidade.value,
            corte: corte.value,
            horario: horario.value,
            cliente: cliente.value,
            recebimento: recebimento.value,
            endereco: endereco.value
        },
        success: function (response) {
            response = response.trim();
            if (response != "erro") {
                $("#mensagem").html("Pedido cadastrado com sucesso!");
                $("#mensagem").fadeIn(300).delay(2000).fadeOut(400);
                setTimeout(function () {
                    $("#form")[0].reset();
                }, 2500);
            } else {
                $("#mensagem").html("Erro: esse pedido já existe!");
                $("#mensagem").fadeIn(300).delay(2000).fadeOut(400);
                setTimeout(function () {
                    $("#form")[0].reset();
                }, 2500);
            }
        },
        error: function (xhr, status, error) {
            console.log("Erro na requisição: ", error);
        }
    });
}*/
if (validado) {
    

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