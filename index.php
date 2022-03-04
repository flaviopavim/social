<html>
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Chat">
        <meta name="keywords" content="Chat">
        <meta name="author" content="WhiteHats">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- Estilos -->
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/color.css">
        <!-- Esqueleto -->
        <link rel="stylesheet" href="css/body.css">
        <link rel="stylesheet" href="css/button.css">
        <link rel="stylesheet" href="css/top.css">
        <link rel="stylesheet" href="css/left.css">
        <!-- Eventos -->
        <link rel="stylesheet" href="css/modal.css">
        <link rel="stylesheet" href="css/notification.css">
        <link rel="stylesheet" href="css/post.css">
        <!-- Páginas -->
        <link rel="stylesheet" href="css/feed.css">
        <link rel="stylesheet" href="css/profile.css">
        <link rel="stylesheet" href="css/chat.css">
        <title>Chat</title>
    </head>
    <body>
        <?php 
        include 'view/top.php'; 
        include 'view/center.php'; 
        include 'view/left.php'; 
        include 'view/right.php'; 
        include 'view/modal.php'; 
        //include 'view/post.php'; 
        //include 'view/notification.php'; 
        include 'view/chat.php'; 
        ?>
        <script>
            var action='nick';
        </script>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Bem-vindo(a) ao Chat!</h4>
                </div>
                <div class="modal-body">
                    Digite um nick ou faça login. Se ainda não possui conta, cadastre-se grátis!
                    <br>
                    <br>
                    <ul class="nav nav-tabs">
                        <li id="tab-nick" role="presentation" class="tab active"><a onclick="tab('nick')">Nick</a></li>
                        <li id="tab-login" role="presentation" class="tab"><a onclick="tab('login')">Entrar</a></li>
                        <li id="tab-signup" role="presentation" class="tab"><a onclick="tab('signup')">Cadastrar</a></li>
                    </ul>
                    <br>
                    <div class="form-hide form-group form-nick">
                        <label>Nick</label>
                        <input type="text" class="form-control" id="nick" placeholder="Nick">
                    </div>
                    <div class="form-hide form-group form-login form-signup">
                        <label>Email</label>
                        <input type="email" class="form-control" id="login" placeholder="Email">
                    </div>
                    <div class="form-hide form-group form-login form-signup">
                        <label>Senha</label>
                        <input type="password" class="form-control" id="pass" placeholder="Senha">
                    </div>
                    <div class="form-hide form-group form-signup">
                        <label>Redigite a senha</label>
                        <input type="password" class="form-control" id="pass-2" placeholder="Redigite a senha">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button id="bt-enter" type="button" class="btn btn-primary" onclick="enter();">Entrar</button>
                </div>
                </div>
            </div>
        </div>

        <!-- Libs -->
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="js/phpjs.js"></script>
        <!-- Funções -->
        <script src="js/crypt.js"></script>
        <!-- Elementos -->
        <script src="js/layout.js"></script>
        <script src="js/loading.js"></script>
        <script src="js/notification.js"></script>
        <script src="js/modal.js"></script>
        <!-- Dados, chat -->
        <script src="js/refresh.js"></script>
        <script src="js/chat.js"></script>

        <script>
            function tab(id) {
                action=id;
                $('.form-hide').hide();
                $('.form-'+action).show();
                $('.tab').removeClass('active');
                $('#tab-'+action).addClass('active');
                if (action=='nick') {
                    $('#bt-enter').html('Atualizar');
                    $('#nick').focus();
                } else if (action=='login') {
                    $('#bt-enter').html('Entrar');
                    $('#login').focus();
                } else if (action=='signup') {
                    $('#bt-enter').html('Cadastrar');
                    $('#login').focus();
                }
            }
            
            $(document).ready(function(){
                $('#myModal').modal('show');
                tab('nick');
                setTimeout(function(){
                    $('#nick').focus();
                },1000);
                $('#nick,#login,#pass,#pass-2').keyup(function(e){
                    if (e.keyCode == 13) {
                        enter();
                    }
                });
            });
            
            function enter() {
                if (action=='nick') {
                    data.data.action.push({
                        action: action,
                        nick: $('#nick').val()
                    });
                } else if (action=='login') {
                    data.data.action.push({
                        action: action,
                        login: $('#login').val()
                    });
                } else if (action=='signup') {
                    data.data.action.push({
                        action: action,
                        login: $('#login').val(),
                        pass: $('#pass').val()
                    });
                }
                $('#bt-enter').html('Carregando...');
                $('#myModal').modal('hide');
            }
        </script>

    </body>
</html>