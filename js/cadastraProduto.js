//puxando os IDs do html
const form = document.getElementById("form");
const nome = document.getElementById("nome");
const preco = document.getElementById("preco");
const categoria = document.getElementById("categoria");
const tipo = document.getElementById("tipo");
const medida = document.getElementById("medida");
const peso_min = document.getElementById("peso");
const intervalo = document.getElementById("intervalo");
const descricao = document.getElementById("descricao");
//----------------------------------------------------------------------




form.addEventListener("submit", (event) => {
    
})

//mascaras---------------------------------------------------------
preco.addEventListener("input", function () {
    let valor = preco.value;

    // Remove tudo que não for número
    valor = valor.replace(/[^0-9.]/g, "");

    // garante que só tenha UM ponto
    let partes = valor.split(".");
    if (partes.length > 2) {
        valor = partes[0] + "." + partes.slice(1).join("");
    }

    // limita a 2 casas decimais
    if (partes[1] && partes[1].length > 2) {
        partes[1] = partes[1].substring(0, 2);
        valor = partes[0] + "." + partes[1];
    }
    if (partes[0] && partes[0].length > 3) {
        partes[0] = partes[0].substring(0, 3);
    }

    // monta o valor de forma segura
    if (partes.length > 1) {
        valor = partes[0] + "." + partes[1];
    } else {
        valor = partes[0]; // só a parte inteira
    }


    preco.value = valor;
});


//blurs-------------------------------------------------------------

nome.addEventListener("blur", () => {
    checa_nome();

})
preco.addEventListener("blur", () => {
    checa_preco();

})
categoria.addEventListener("blur", () => {
    checa_categoria();

})

peso_min.addEventListener("blur", () => {
    checa_peso();

})
intervalo.addEventListener("blur", () => {
    checa_intervalo();

})

descricao.addEventListener("blur", () => {
    checa_descricao();

})

//checagens--------------------------------------------------------
function checa_nome() {
    const valor_nome = nome.value;
    if (valor_nome === "") {
        error_imput(nome, "preencha o nome do produto");
    } else {
        const form_item = nome.parentElement;
        form_item.className = "form_content";
    }
}

function checa_preco() {
    const valor_preco = preco.value;
    if (valor_preco === "") {
        error_imput(preco, "preencha o valor do produto");
    } else {
        const form_item = preco.parentElement;
        form_item.className = "form_content";
    }
}

function checa_categoria() {
    const valor_categoria = categoria.value;
    if (valor_categoria === "") {
        error_imput(categoria, "selecione uma categoria");
    } else {
        const form_item = categoria.parentElement;
        form_item.className = "form_content";
    }
}

function checa_peso() {
    const valor_peso = peso_min.value;
    if (valor_peso === "") {
        error_imput(peso_min, "selecione umm peso");
    } else {
        const form_item = peso_min.parentElement;
        form_item.className = "form_content";
    }
}

function checa_intervalo() {
    const valor_intervalo = intervalo.value;
    if (valor_intervalo === "") {
        error_imput(intervalo, "selecione um intervalo");
    } else {
        const form_item = intervalo.parentElement;
        form_item.className = "form_content";
    }
}

function checa_descricao() {
    const valor_descricao = descricao.value;
    if (valor_descricao === "") {
        error_imput(descricao, "selecione um intervalo");
    } else {
        const form_item = descricao.parentElement;
        form_item.className = "form_content";
    }
}


//mensagemde erro------------------------------------------------------
function error_imput(input, message) {
    const form_item = input.parentElement;
    const text_message = form_item.querySelector("a");

    text_message.innerText = message;
    form_item.className = "form_content error";
}
function error_radio(radio, message) {
    const form_item = radio.parentElement;
    const text_message = form_item.querySelector("a");

    text_message.innerText = message;
    form_item.className = "radio-group error";
}