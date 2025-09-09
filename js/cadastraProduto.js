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
let lastPreviewUrl = null;

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

function clearPreviewBox(){
  if (lastPreviewUrl){
    try { URL.revokeObjectURL(lastPreviewUrl); } catch(e){}
    lastPreviewUrl = null;
  }
  if (previewBox){
    previewBox.style.backgroundImage = "";
    previewBox.style.backgroundSize = "";
    previewBox.style.backgroundRepeat = "";
    previewBox.style.backgroundPosition = "";
    previewBox.style.borderStyle = "";
  }
}

function previewImagem(file){
  const allowed = ["image/jpeg","image/png","image/webp","image/jpg"];
  const maxSize = 5 * 1024 * 1024; // 5MB
  if (!file){
    clearPreviewBox();
    setImagemError("selecione uma imagem.");
    if (imagemInput) imagemInput.setCustomValidity("selecione uma imagem.");
    return false;
  }
  if (!allowed.includes(file.type)){
    clearPreviewBox();
    setImagemError("Formato inválido. Use JPG, PNG ou WEBP.");
    if (imagemInput) imagemInput.setCustomValidity("Formato inválido. Use JPG, PNG ou WEBP.");
    return false;
  }
  if (file.size > maxSize){
    clearPreviewBox();
    setImagemError("Imagem muito grande (máx. 5MB).");
    if (imagemInput) imagemInput.setCustomValidity("Imagem muito grande (máx. 5MB).");
    return false;
  }
  if (lastPreviewUrl){
    try { URL.revokeObjectURL(lastPreviewUrl); } catch(e){}
  }
  const url = URL.createObjectURL(file);
  if (previewBox){
    previewBox.style.backgroundImage = `url('${url}')`;
    previewBox.style.backgroundSize = "contain";
    previewBox.style.backgroundRepeat = "no-repeat";
    previewBox.style.backgroundPosition = "center";
    previewBox.style.borderStyle = "dashed";
  }
  lastPreviewUrl = url;
  clearImagemError();
  if (imagemInput) imagemInput.setCustomValidity("");
  return true;
}

if (imagemInput){
  imagemInput.addEventListener("change", (e) => {
    const file = e.target.files && e.target.files[0];
    const ok = previewImagem(file);
    if (!ok) {
      // mantém o estado inválido para o HTML5 validation
    }
  });
  imagemInput.addEventListener("invalid", (e) => {
    setImagemError(e.target.validationMessage || "Imagem obrigatória.");
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
        if (imagemInput) imagemInput.setCustomValidity("Imagem obrigatória.");
        valido = false;
    } else {
        const file = imagemInput.files[0];
        if (!previewImagem(file)) {
            valido = false;
        }
    }

    // se houver qualquer erro marcado
    const temErro = document.querySelector(".form_content.error") || document.querySelector(".radio-group.error")  ;
    if (temErro) valido = false;

    if (!valido){
        event.preventDefault();
        event.stopPropagation();

        // dar foco e mostrar mensagens nos campos específicos
        const tipoGroup = document.querySelector(".radio-group input[type='checkbox']")?.closest(".radio-group");
        const refTipo = tipoGroup ? tipoGroup.querySelector("input[type='checkbox']") : null;
        if (refTipo && refTipo.validity && (refTipo.validity.customError)) {
            if (typeof refTipo.reportValidity === "function") refTipo.reportValidity();
        } else if (preco && (!preco.value.trim() || (preco.validity && preco.validity.customError))) {
            if (typeof preco.reportValidity === "function") preco.reportValidity();
        } else if (descricao && (!descricao.value.trim() || (descricao.validity && descricao.validity.customError))) {
            if (typeof descricao.reportValidity === "function") descricao.reportValidity();
        } else if (typeof form.reportValidity === "function") {
            form.reportValidity();
        }
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

// invalid handlers para exibir erro nativo + classe visual
nome.addEventListener("invalid", () => {
    checa_nome();
});
preco.addEventListener("invalid", () => {
    checa_preco();
});
categoria.addEventListener("invalid", () => {
    checa_categoria();
});
peso_min.addEventListener("invalid", () => {
    if (peso_min.disabled) {
        // se estiver desabilitado, não deve permanecer inválido
        peso_min.setCustomValidity("");
        const fi = peso_min.parentElement; if (fi) fi.classList.remove("error");
        const a = peso_min.parentElement?.querySelector("a"); if (a) a.textContent = "";
        return;
    }
    checa_peso();
});
intervalo.addEventListener("invalid", () => {
    if (intervalo.disabled) {
        intervalo.setCustomValidity("");
        const fi = intervalo.parentElement; if (fi) fi.classList.remove("error");
        const a = intervalo.parentElement?.querySelector("a"); if (a) a.textContent = "";
        return;
    }
    checa_intervalo();
});
descricao.addEventListener("invalid", () => {
    checa_descricao();
});

document.addEventListener("DOMContentLoaded", () => {
    const tipo = document.querySelector(".radio-group input[type='checkbox']")?.closest(".radio-group");
    if (tipo) {
        const checkboxes = tipo.querySelectorAll("input[type='checkbox']");
        const ref = tipo.querySelector("input[type='checkbox']");
        
        // Adiciona o evento 'change' a cada checkbox
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener("change", () => {
                checa_tipo(); // Valida sempre que um checkbox for clicado
                if (ref && typeof ref.reportValidity === 'function') ref.reportValidity();
            });
        });
        // Exibe erro nativo quando inválido
        if (ref) {
            ref.addEventListener("invalid", () => {
                checa_tipo();
            });
        }
    } else {
        console.error("Grupo de checkboxes de 'tipo' não encontrado.");
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

    // Garantir required para validação nativa
    if (preco) preco.required = true;
    if (descricao) descricao.required = true;
});


//checagens--------------------------------------------------------
function checa_nome() {
    const valor_nome = nome.value.trim();
    if (valor_nome === "") {
        if (typeof nome.setCustomValidity === "function") nome.setCustomValidity("preencha o nome do produto");
        error_imput(nome, "preencha o nome do produto");
    } else {
        if (typeof nome.setCustomValidity === "function") nome.setCustomValidity("");
        const form_item = nome.parentElement;
        form_item.className = "form_content";
        const a = form_item.querySelector("a"); if (a) a.textContent = "";
    }
}

function checa_preco() {
    const valor_preco = preco.value.trim();
    if (valor_preco === "") {
        if (typeof preco.setCustomValidity === "function") preco.setCustomValidity("preencha o valor do produto");
        error_imput(preco, "preencha o valor do produto");
    } else {
        if (typeof preco.setCustomValidity === "function") preco.setCustomValidity("");
        const form_item = preco.parentElement;
        form_item.className = "form_content";
        const a = form_item.querySelector("a"); if (a) a.textContent = "";
    }
}

function checa_categoria() {
    const valor_categoria = categoria.value;
    if (valor_categoria === "") {
        if (typeof categoria.setCustomValidity === "function") categoria.setCustomValidity("selecione uma categoria");
        error_imput(categoria, "selecione uma categoria");
    } else {
        if (typeof categoria.setCustomValidity === "function") categoria.setCustomValidity("");
        const form_item = categoria.parentElement;
        form_item.className = "form_content";
        const a = form_item.querySelector("a"); if (a) a.textContent = "";
    }
}

function checa_peso() {
    // Ignora validação se o campo estiver desabilitado (quando medida for UNIDADE)
    if (peso_min.disabled) {
        if (typeof peso_min.setCustomValidity === "function") peso_min.setCustomValidity("");
        const form_item = peso_min.parentElement;
        form_item.className = "form_content";
        const a = form_item.querySelector("a"); if (a) a.textContent = "";
        return;
    }

    const valor_peso = peso_min.value.trim();
    if (valor_peso === "") {
        if (typeof peso_min.setCustomValidity === "function") peso_min.setCustomValidity("preencha o peso mínimo");
        error_imput(peso_min, "preencha o peso mínimo");
    } else {
        if (typeof peso_min.setCustomValidity === "function") peso_min.setCustomValidity("");
        const form_item = peso_min.parentElement;
        form_item.className = "form_content";
        const a = form_item.querySelector("a"); if (a) a.textContent = "";
    }
}

function checa_intervalo() {
    // Ignora validação se o campo estiver desabilitado (quando medida for UNIDADE)
    if (intervalo.disabled) {
        if (typeof intervalo.setCustomValidity === "function") intervalo.setCustomValidity("");
        const form_item = intervalo.parentElement;
        form_item.className = "form_content";
        const a = form_item.querySelector("a"); if (a) a.textContent = "";
        return;
    }

    const valor_intervalo = intervalo.value.trim();
    if (valor_intervalo === "") {
        if (typeof intervalo.setCustomValidity === "function") intervalo.setCustomValidity("preencha o intervalo");
        error_imput(intervalo, "preencha o intervalo");
    } else {
        if (typeof intervalo.setCustomValidity === "function") intervalo.setCustomValidity("");
        const form_item = intervalo.parentElement;
        form_item.className = "form_content";
        const a = form_item.querySelector("a"); if (a) a.textContent = "";
    }
}

function checa_descricao() {
    const valor_descricao = descricao.value.trim();
    if (valor_descricao === "") {
        if (typeof descricao.setCustomValidity === "function") descricao.setCustomValidity("preencha a descrição do produto");
        error_imput(descricao, "preencha a descrição do produto");
    } else {
        if (typeof descricao.setCustomValidity === "function") descricao.setCustomValidity("");
        const form_item = descricao.parentElement;
        form_item.className = "form_content";
        const a = form_item.querySelector("a"); if (a) a.textContent = "";
    }
}
function checa_tipo() {
    const tipo = document.querySelector(".radio-group input[type='checkbox']")?.closest(".radio-group");
    if (!tipo) return;
    const checkboxes = tipo.querySelectorAll("input[type='checkbox']");
    const errorMessage = tipo.querySelector("a"); // Seleciona o elemento para exibir a mensagem de erro
    const ref = checkboxes[0]; // referência para integrarmos com a validade nativa

    // Verifica se pelo menos um checkbox está selecionado
    const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

    if (!isChecked) {
        if (errorMessage) errorMessage.innerText = "Selecione pelo menos uma opção.";
        tipo.classList.add("error"); // Adiciona a classe de erro
        if (ref && typeof ref.setCustomValidity === 'function') ref.setCustomValidity("Selecione pelo menos uma opção.");
    } else {
        if (errorMessage) errorMessage.innerText = ""; // Limpa a mensagem de erro
        tipo.classList.remove("error"); // Remove a classe de erro
        if (ref && typeof ref.setCustomValidity === 'function') ref.setCustomValidity("");

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