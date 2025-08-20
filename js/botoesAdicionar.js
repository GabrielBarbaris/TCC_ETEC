const button = document.querySelector("button")
const modal = document.querySelector("#adicionar")
const login_modal = document.querySelector("#login")

button.onclick = function () {
  modal.showModal()
}

function cadastrar_cliente(){
   login_modal.showModal()
}

