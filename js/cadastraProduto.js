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

// imagem (input escondido) e preview (div .exemplo)
const imagemInput = document.getElementById("imagem");
const previewBox = document.querySelector(".exemplo");
let imagemErroEl = null;

(function initImagemUI(){
  const imagemContainer = document.querySelector(".imagem");
  if (imagemContainer && !imagemErroEl){
    imagemErroEl = document.createElement("span");
    imagemErroEl.className = "imagem-erro";
    imagemContainer.appendChild(imagemErroEl);
  }

  if (previewBox){
    previewBox.style.cursor = "pointer";
    previewBox.addEventListener("click", () => imagemInput && imagemInput.click());
  }
})();

function clearImagemError(){
  if (imagemErroEl) imagemErroEl.textContent = "";
  const imagemContainer = document.querySelector(".imagem");
  if (imagemContainer){ imagemContainer.classList.remove("error"); }
}
function setImagemError(msg){
  if (imagemErroEl) imagemErroEl.textContent = msg;
  const imagemContainer = document.querySelector(".imagem");
  if (imagemContainer){ imagemContainer.classList.add("error"); }
}

function previewImagem(file){
  const allowed = ["image/jpeg","image/png","image/webp","image/jpg"];
  const maxSize = 5 * 1024 * 1024; // 5MB
  if (!file){ setImagemError("selecione uma imagem."); return false; }
  if (!allowed.includes(file.type)){
    setImagemError("Formato inválido. Use JPG, PNG ou WEBP.");
    return false;
  }
  if (file.size > maxSize){
    setImagemError("Imagem muito grande (máx. 5MB).");
    return false;
  }
  const url = URL.createObjectURL(file);
  if (previewBox){
    previewBox.style.backgroundImage = `url('${url}')`;
    previewBox.style.backgroundSize = "contain";
    previewBox.style.backgroundRepeat = "no-repeat";
    previewBox.style.backgroundPosition = "center";
    previewBox.style.borderStyle = "dashed";
  }
  clearImagemError();
  return true;
}

if (imagemInput){
  imagemInput.addEventListener("change", (e) => {
    const file = e.target.files && e.target.files[0];
    previewImagem(file);
  });
}




form.addEventListener("submit", (event) => {
    let valido = true;

    // validações existentes
    checa_nome();
    checa_preco();
    checa_categoria();
    checa_peso();
    checa_intervalo();
    checa_descricao();
    checa_tipo();

    // validação da imagem obrigatória
    if (!imagemInput || !imagemInput.files || imagemInput.files.length === 0){
        setImagemError("Imagem obrigatória.");
        valido = false;
    } else {
        const file = imagemInput.files[0];
        if (!previewImagem(file)) {
            valido = false;
        }
    }

    // se houver qualquer erro marcado
    const temErro = document.querySelector(".form_content.error") || document.querySelector(".radio-group.error");
    if (temErro) valido = false;

    if (!valido){
        event.preventDefault();
        event.stopPropagation();
    }
})

//mascaras---------------------------------------------------------

//preços
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

//peso minimo
peso_min.addEventListener("input", function () {
    let valor = peso_min.value;

    // Remove tudo que não for número
    valor = valor.replace(/[^0-9.]/g, "");

    // garante que só tenha UM ponto
    let partes = valor.split(".");
    if (partes.length > 2) {
        valor = partes[0] + "." + partes.slice(1).join("");
    }

    // limita a 2 casas decimais
    if (partes[1] && partes[1].length > 2) {
        partes[1] = partes[1].substring(0, 3);
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


    peso_min.value = valor;
});

//intervalo
intervalo.addEventListener("input", function () {
    let valor = intervalo.value;

    // Remove tudo que não for número
    valor = valor.replace(/[^0-9.]/g, "");

    // garante que só tenha UM ponto
    let partes = valor.split(".");
    if (partes.length > 2) {
        valor = partes[0] + "." + partes.slice(1).join("");
    }

    // limita a 2 casas decimais
    if (partes[1] && partes[1].length > 2) {
        partes[1] = partes[1].substring(0, 3);
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


    intervalo.value = valor;
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

document.addEventListener("DOMContentLoaded", () => {
    const tipo = document.querySelector(".radio-group");
    if (tipo) {
        const checkboxes = tipo.querySelectorAll("input[type='checkbox']");
        
        // Adiciona o evento 'change' a cada checkbox
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener("change", () => {
                checa_tipo(); // Valida sempre que um checkbox for clicado
            });
        });
    } else {
        console.error("Elemento com a classe 'radio-group' não encontrado.");
    }

    // Alterna exibição dos campos (Peso mínimo e Intervalo) conforme a medida selecionada
    const radiosMedida = document.querySelectorAll("input[name='medida']");
    const pesoContainer = document.getElementById("peso") ? document.getElementById("peso").closest(".form_content") : null;
    const intervaloContainer = document.getElementById("intervalo") ? document.getElementById("intervalo").closest(".form_content") : null;

    function setPesoVisibility() {
        const isPeso = document.querySelector("input[name='medida'][value='PESO']")?.checked;
        if (!pesoContainer || !intervaloContainer) return;

        // Usa estilo inline para não depender de CSS adicional
        if (isPeso) {
            pesoContainer.style.display = "flex";
            intervaloContainer.style.display = "flex";
        } else {
            pesoContainer.style.display = "none";
            intervaloContainer.style.display = "none";
        }

        // Habilita/Desabilita e controla 'required'
        [peso_min, intervalo].forEach((input) => {
            if (!input) return;
            input.required = !!isPeso;
            input.disabled = !isPeso;
            if (!isPeso) {
                const parent = input.parentElement;
                parent.classList.remove("error");
                const a = parent.querySelector("a");
                if (a) a.textContent = "";
            }
        });
    }

    if (radiosMedida.length) {
        radiosMedida.forEach(r => r.addEventListener("change", setPesoVisibility));
        setPesoVisibility(); // estado inicial
    }
});


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
    // Ignora validação se o campo estiver desabilitado (quando medida for UNIDADE)
    if (peso_min.disabled) {
        const form_item = peso_min.parentElement;
        form_item.className = "form_content";
        return;
    }

    const valor_peso = peso_min.value;
    if (valor_peso === "") {
        error_imput(peso_min, "preencha o peso mínimo");
    } else {
        const form_item = peso_min.parentElement;
        form_item.className = "form_content";
    }
}

function checa_intervalo() {
    // Ignora validação se o campo estiver desabilitado (quando medida for UNIDADE)
    if (intervalo.disabled) {
        const form_item = intervalo.parentElement;
        form_item.className = "form_content";
        return;
    }

    const valor_intervalo = intervalo.value;
    if (valor_intervalo === "") {
        error_imput(intervalo, "preencha o intervalo");
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
function checa_tipo() {
    const tipo = document.querySelector(".radio-group");
    const checkboxes = tipo.querySelectorAll("input[type='checkbox']");
    const errorMessage = tipo.querySelector("a"); // Seleciona o elemento para exibir a mensagem de erro

    // Verifica se pelo menos um checkbox está selecionado
    const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

    if (!isChecked) {
        errorMessage.innerText = "Selecione pelo menos uma opção.";
        tipo.classList.add("error"); // Adiciona a classe de erro
    } else {
        errorMessage.innerText = ""; // Limpa a mensagem de erro
        tipo.classList.remove("error"); // Remove a classe de erro
    }
}


//mensagemde erro------------------------------------------------------
function error_imput(input, message) {
    const form_item = input.parentElement;
    const text_message = form_item.querySelector("a");

    text_message.innerText = message;
    form_item.className = "form_content error";
}
function error_checkbox(checkboxGroup, message) {
    const form_item = checkboxGroup.parentElement; // O contêiner do grupo de checkboxes
    const text_message = form_item.querySelector("a"); // Mensagem de erro

    // Verifica se pelo menos um checkbox está selecionado
    const isChecked = Array.from(checkboxGroup.querySelectorAll("input[type='checkbox']")).some(checkbox => checkbox.checked);

    if (!isChecked) {
        text_message.innerText = message;
        form_item.className = "checkbox-group error";
    } else {
        text_message.innerText = ""; // Limpa a mensagem de erro
        form_item.className = "checkbox-group"; // Remove a classe de erro
    }
}