const form = document.getElementById("form");
const produto = document.getElementById("produto");
const quantidade = document.getElementById("quantidade");
const corte = document.getElementById("corte");
const horario = document.getElementById("horario");
const cliente = document.getElementById("cliente");
const enderecoInput = document.getElementById('campo_endereco');
const cepInput = document.getElementById('cep');
const numeroInput = document.getElementById('numero_endereco');
const complementoInput = document.getElementById('complemento_endereco');



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
// validação do recebimento é tratada no submit e na alteração dos rádios


//checagens--------------------------------------------------------
function checa_produto() {
    const valor = produto.value.trim();
    if (!valor) {
        error_imput(produto, "Preencha qual é o produto");
        return false;
    }
    const form_item = produto.parentElement;
    form_item.className = "form_content";
    return true;
}

function checa_quantidade() {
    const valor = quantidade.value.trim();
    const numero = Number(valor);
    if (!valor || !Number.isFinite(numero) || numero <= 0) {
        error_imput(quantidade, "Informe uma quantidade válida (número maior que zero)");
        return false;
    }
    const form_item = quantidade.parentElement;
    form_item.className = "form_content";
    return true;
}

function checa_corte() {
    const valor = corte.value.trim();
    if (!valor) {
        error_imput(corte, "Preencha qual é o tipo de corte");
        return false;
    }
    const form_item = corte.parentElement;
    form_item.className = "form_content";
    return true;
}

function checa_horario() {
    const valor = horario.value.trim();
    if (!valor) {
        error_imput(horario, "Preencha o horário");
        return false;
    }
    const form_item = horario.parentElement;
    form_item.className = "form_content";
    return true;
}

function checa_cliente() {
    const valor = cliente.value.trim();
    if (!valor) {
        error_imput(cliente, "Preencha o nome do cliente");
        return false;
    }
    const form_item = cliente.parentElement;
    form_item.className = "form_content";
    return true;
}

function checa_recebimento() {
    const selecionado = document.querySelector('input[name="recebimento"]:checked');
    if (!selecionado) {
        $('#mensagem').html('Selecione uma opção de recebimento.').fadeIn(300).delay(2000).fadeOut(400);
        return false;
    }
    if (selecionado.value === 'entrega') {
        const valorEnd = enderecoInput ? enderecoInput.value.trim() : '';
        if (!valorEnd) {
            $('#mensagem').html('Informe o endereço para entrega.').fadeIn(300).delay(2000).fadeOut(400);
            return false;
        }
        const valorNumero = numeroInput ? String(numeroInput.value).trim() : '';
        const numeroDigits = valorNumero.replace(/\D/g, '');
        if (!numeroDigits || Number(numeroDigits) <= 0) {
            $('#mensagem').html('Informe o número da casa (apenas dígitos).').fadeIn(300).delay(2000).fadeOut(400);
            return false;
        }
    }
    return true;
}

//validação final------------------------------------------------------------
function checa_form() {
    let validado = true;

    if (!checa_produto()) validado = false;
    if (!checa_quantidade()) validado = false;
    if (!checa_corte()) validado = false;
    if (!checa_horario()) validado = false;
    if (!checa_cliente()) validado = false;
    if (!checa_recebimento()) validado = false;

    if (!validado) return;

    const selecionado = document.querySelector('input[name="recebimento"]:checked');
    const recebimentoValue = selecionado ? selecionado.value : '';
    const enderecoValue = (recebimentoValue === 'entrega' && enderecoInput) ? enderecoInput.value.trim() : '';

    $.ajax({
        url: 'cadastraPedido.php',
        type: 'POST',
        data: {
            produto: produto.value.trim(),
            quantidade: quantidade.value.trim(),
            corte: corte.value.trim(),
            horario: horario.value.trim(),
            cliente: cliente.value.trim(),
            recebimento: recebimentoValue,
            endereco: enderecoValue,
            cep: cepInput ? cepInput.value.trim() : '',
            numero: numeroInput ? String(numeroInput.value).trim() : '',
            complemento: complementoInput ? String(complementoInput.value).trim() : ''
        },
        success: function (response) {
            response = (response || '').trim();
            if (response != 'erro') {
                $('#mensagem').html('Pedido cadastrado com sucesso!');
                $('#mensagem').fadeIn(300).delay(2000).fadeOut(400);
                setTimeout(function () {
                    $('#form')[0].reset();
                    atualizarVisibilidadeEndereco();
                }, 2500);
            } else {
                $('#mensagem').html('Erro: esse pedido já existe!');
                $('#mensagem').fadeIn(300).delay(2000).fadeOut(400);
                setTimeout(function () {
                    $('#form')[0].reset();
                    atualizarVisibilidadeEndereco();
                }, 2500);
            }
        },
        error: function (xhr, status, error) {
            console.log('Erro na requisição: ', error);
            $('#mensagem').html('Ocorreu um erro ao enviar o pedido. Tente novamente.');
            $('#mensagem').fadeIn(300).delay(2000).fadeOut(400);
        }
    });
}

//mensagem de erro------------------------------------------------------
function error_imput(input, message) {
    const form_item = input.parentElement;
    const text_message = form_item.querySelector("a");

    text_message.innerText = message;
    form_item.className = "form_content error";
}

//Campo endereço------------------------------------------------------
const radios = document.querySelectorAll('input[name="recebimento"]');
const campoEndereco = document.getElementById('endereco');
const entregaRadio = document.getElementById('entrega');

function atualizarVisibilidadeEndereco() {
  if (!campoEndereco || !entregaRadio) return;
  if (entregaRadio.checked) {
    campoEndereco.classList.remove('hidden');
  } else {
    campoEndereco.classList.add('hidden');
  }
}

// Inicializa estado ao carregar
atualizarVisibilidadeEndereco();

// Observa mudanças nos rádios
radios.forEach(radio => radio.addEventListener('change', atualizarVisibilidadeEndereco));

// Integração ViaCEP -------------------------------------------------
function limparCEP(cep) {
  return (cep || '').replace(/\D/g, '');
}

async function buscarCEP(cep) {
  const limpo = limparCEP(cep);
  if (limpo.length !== 8) {
    $('#mensagem').html('CEP inválido. Informe 8 dígitos.').fadeIn(300).delay(2000).fadeOut(400);
    return null;
  }
  try {
    const resp = await fetch(`https://viacep.com.br/ws/${limpo}/json/`);
    if (!resp.ok) throw new Error('Falha ao consultar CEP');
    const data = await resp.json();
    if (data.erro) {
      $('#mensagem').html('CEP não encontrado.').fadeIn(300).delay(2000).fadeOut(400);
      return null;
    }
    return data; // {logradouro, bairro, localidade, uf, ...}
  } catch (e) {
    console.error(e);
    $('#mensagem').html('Erro ao consultar o CEP. Tente novamente.').fadeIn(300).delay(2000).fadeOut(400);
    return null;
  }
}

function montarEndereco(data) {
  const parts = [];
  if (data.logradouro) parts.push(data.logradouro);
  if (data.bairro) parts.push(data.bairro);
  const cidadeUF = [data.localidade, data.uf].filter(Boolean).join(' - ');
  if (cidadeUF) parts.push(cidadeUF);
  return parts.join(', ');
}

if (cepInput) {
  cepInput.addEventListener('blur', async () => {
    const data = await buscarCEP(cepInput.value);
    if (data && enderecoInput) {
      const end = montarEndereco(data);
      if (end) {
        enderecoInput.value = end; // usuário pode complementar número/complemento
        // Garantir que o campo endereço esteja visível quando há entrega
        atualizarVisibilidadeEndereco();
      }
    }
  });
}