//puxando os IDs do html
const form = document.getElementById("form");
const nome = document.getElementById("nome");
const preco = document.getElementById("preco");
const categoria = document.getElementById("categoria");
const tipo = document.getElementById("tipo");
const medida = document.getElementById("medida");
const peso_min = document.getElementById("peso_min");
const intervalo = document.getElementById("intervalo");
const descricao = document.getElementById("descricao");
//----------------------------------------------------------------------




form.addEventListener("submit", (event) => {
    event.preventDefault();

    checa_nome();


    const form_item = form.querySelectorAll(".form_content");

    const validado = [...form_item].every((item) => {
        return item.className === "form_content"
    });

    if (validado) {
        alert("valido");
    } else {
        alert("invalido");
    }
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



//mensagemde erro------------------------------------------------------
function error_imput(input, message) {
    const form_item = input.parentElement;
    const text_message = form_item.querySelector("a");

    text_message.innerText = message;
    form_item.className = "form_content error";
}