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


    const form_item= form.querySelectorAll(".form_content");
    
    const validado = [...form_item].every( (item) => {
        return item.className === "form_content"
    });

    if(validado){
        alert("valido");
    }else{
        alert("invalido");
    }
})


nome.addEventListener("blur",() =>{
    checa_nome();
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


//mensagemde erro------------------------------------------------------
function error_imput(input, message) {
    const form_item = input.parentElement;
    const text_message = form_item.querySelector("a");

    text_message.innerText = message;
    form_item.className = "form_content error";
}