$(document).ready(function(){

    $('#nome').click(function(){
        if($(this).val() == "nome completo"){
            $(this).val('');
        } // fim do if
    }); // fim do click no objeto id=nome

    $('#email').click(function(){
        if($(this).val() == "name@example.com"){
            $(this).val('');
        } // fim do if
    }); // fim do click no objeto id=email

    $('#senha').click(function(){
        if($(this).val() == "senha"){
            $(this).val('');
        } // fim do if
    }); // fim do click no objeto id=senha

    $('#botaoCadastrar').click(function(){

        if($('#nome').val() == '' || $('#nome').val() == "nome completo"){
            $('#mensagem').html("Nome inválido");
            $('#mensagem').fadeIn(300).delay(2000).fadeOut(400);
        } 
        else if($('#email').val() == '' || $('#email').val() == "name@example.com"){
            $('#mensagem').html("Email inválido");
            $('#mensagem').fadeIn(300).delay(2000).fadeOut(400);
        } 
        else if($('#senha').val() == '' || $('#senha').val() == "senha"){
            $('#mensagem').html("Senha inválida");
            $('#mensagem').fadeIn(300).delay(2000).fadeOut(400);
        } 
        else{
            no=$('#nome').val();
            em=$('#email').val();
            se=$('#senha').val();
        
            //$('#mensagem').html("Dados ok para cadastro");
            //$('#mensagem').fadeIn(300).delay(2000).fadeOut(400);
        
            $.ajax({
                url: 'cadUsuario.php',
                type: 'POST',
                data: {nome:no, email:em, senha:se},
        
                success: function(response) {
                    response=response.trim();
        
                    if(response != "erro"){
                        $('#mensagem').html("Cadastrado com sucesso");
                        $('#mensagem').fadeIn(300).delay(2000).fadeOut(400);
                        setTimeout(function(){
                            $('#formCadastro')[0].reset(); // Limpa o formulário após 2.5 segundos
                        }, 2500);
                    }
                    else{
                        $('#mensagem').html("Erro no cadastro");
                        $('#mensagem').fadeIn(300).delay(2000).fadeOut(400);
                    }
                },
        
                error: function(xhr, status, error){
                    console.log("Erro na requisição:", error);
                }
            });
        } //fim do else
        

    }); // fim do clique no botao Cadastrar

}); // fim
