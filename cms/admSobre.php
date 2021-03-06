<?php 

// Ativar o uso de variaveis de sessão 
   session_start();
        
   // o botão ja vai iniciar com esse nome
   $botao = "SALVAR";

   //caminho do banco
   require_once('conexao.php');

   //conexão do banco para a pagina
    $conexao = conexaoDb();

    // verificando o click botão de inserir
    if(isset($_POST['btnSalvar'])){
        
        // variaveis
        $txtTitulo = $_POST['txtTitulo'];
        $txtDescricao = $_POST['txtDescricao'];
        $sltStatus = $_POST['sltStatus'];
        $foto = $_POST['txtFoto'];
        
        //usando a variável de sessão para saber qual a função que o botão deve fazer
        if($_POST['btnSalvar'] == "SALVAR"){
            //insert no banco: inseri dados novos
            $sql = "INSERT INTO tbl_sobre(titulo, descricao, fotoSobre, status) VALUES ('".$txtTitulo."','".$txtDescricao."','".$foto."','".$sltStatus."');";
        }else if($_POST['btnSalvar'] == "EDITAR"){
            //update no banco:  recebe novas atualizações
            $sql = "UPDATE tbl_sobre SET titulo='".$txtTitulo."', descricao='".$txtDescricao."', fotoSobre='".$foto."', status='".$sltStatus."' WHERE idSobre=".$_SESSION['id'];
        }
        
        //conectando com o banco
        mysqli_query($conexao,$sql);
        //impede que os dados sejam gravados várias vezes
        header('location:admSobre.php');
        
    }

    //pegando pela url o modo que foi clicado dentro das opções
    if(isset($_GET['modo'])){
        $modo = $_GET['modo'];
        
        //modo de edição dos dados
        if($modo == 'editar'){
            $botao = "EDITAR"; //variável de sessão
            $id = $_GET['id']; //pegando o dado da url
            $_SESSION['id']=$id; //startando a conexão
            
            //comando no ban
            $sql = "select * from tbl_sobre WHERE idSobre=".$id;
            
            //armazenando em uma variável
            $select = mysqli_query($conexao, $sql);
            
            //criando um while para trazer todos os dados
            while($rsConexao = mysqli_fetch_array($select)){
                $titulo = $rsConexao['titulo']; 
                $descricao = $rsConexao['descricao'];
                $sltStatus = $rsConexao['status'];
                $nomefoto = $rsConexao['fotoSobre'];
                $imagem = $rsConexao['fotoSobre'];
                $caminhoImg = "<img src='$imagem'>"; //inserindo a imagem que vem do banco de dados no html
            }
        // modo de exclusão
        }else if($modo = 'excluir'){
            $id = $_GET['id'];
            //comndo no banco
            $sql = "delete from tbl_sobre where idSobre=".$id;
            
            //conexão do banco
            mysqli_query($conexao,$sql);
            //impede de criar o mesmo registro varias vezes
            header('location:admSobre.php');
            
        }
    }

    //inicio de sessão do login
    if(!$_SESSION['nome']){
         header("location:../index.php");
    }
    //saindo da sessão do cms
    if(isset($_GET['logout'])){
        session_destroy(); //destroi os registros do cms
        header("location:../index.php");
    }


?>
<!DOCTYPE HMTL5>
<html lang="pt-br">
    <head>
        <title>Sistema de Gerenciamento</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery.form.js"></script>
       
        <script>
            $(document).ready(function(){
            
            $('#foto').live('change',function(){
   
                $('#visualizar').html("<img src='image/ajax-loader.gif'>");
                
                setTimeout(function(){
                 $('#frmFoto').ajaxForm({
                     // passando um parametro para o ajax, (quero que vc mostre dentro da div)
                     target:'.visualizar'
                 }).submit(); 
                    
                },2000);   
            });
        });
            
        </script>
    </head>
    <body>
        <!-- CONTAINER PRICIPAL -->
      <div class="principal">
          <!-- CABEÇALHO -->
        <header id="header">
            <div id="header_logo">
                <!-- TITULO DO CABEÇALHO-->
                <div id="header_titulo">
                    Sistema de Gerenciamento
                </div>
                <!-- IMAGEM DO CABEÇALHO -->
                <div id="header_imagem">
                    <img src="image/logo2.png" alt="logo" width="160" height="150">
                </div>
            </div> 
            <!-- MENU COM ICONES -->
            <div id="header_menu">
                <!-- ICONES -->
                <div id="menus">
                    <div class="item">
                        <a href="admConteudo.php">
                        <img src="image/cont.png" width="100" heigth="100" alt="icone">
                        <p>Adm. de Conteúdo</p>
                        </a>
                    </div>

                    <div class="item">
                        <img src="image/livro.png" width="100" heigth="100" alt="icone">
                        <p>Adm. dos Produtos</p>
                    </div>

                    <a href="faleconosco.php">
                    <div class="item">
                        <img src="image/fale.png" width="100" heigth="100" alt="icone">
                        <p>Adm. Fale Conosco</p>
                    </div>
                    </a>

                    <a href="admUsuariosInicio.php">
                    <div class="item">
                        <img src="image/user.png" width="100" heigth="100" alt="icone">
                        <p>Adm. de Usuarios</p>
                    </div>
                    </a>
                </div>
                <!-- LOGIN E SAIR -->
                <div id="login">
                    <div id="nome">
                        <h1>Bem vindo, <?php echo($_SESSION['nome']) ?></h1>
                    </div>
                    <div id="sair">
                        <h1><a href="admSobre.php?logout">Logout</a></h1>
                    </div>
                </div>
            </div>
        </header>
        <!-- TODO O CONTEUDO -->
        <div id="content">
            <div id="cad_caixa_sobre">
                <div class="cad_sobre">
                    <h1 class="h1_sobre">Cadastre uma informação:</h1>
                    <form name="frmFoto" method="post" action="upload.php" id="frmFoto" enctype="multipart/form-data">
                        <p><input class="file" type="file" name="filefoto" id="foto" > </p>
                        
                    </form>
                   <form name="frmConteudo" method="post" action="admSobre.php">
                    <div class="item_cad_loja"><p class="titulo_p">Título: </p> <input type="text" name="txtTitulo" value="<?php echo(@$titulo)?>"></div>
                       <input type="text" name="txtFoto" hidden="hidden" value="<?php echo(@$nomefoto)?>" >
                    <div  class="item_cad_loja"> <p class="titulo_p">Descrição: </p> <textarea class="text" name="txtDescricao" ><?php echo(@$descricao)?></textarea> </div>
                        <div  class="item_cad_loja">Ativação:
                    <select name="sltStatus">
                                <option value="0" >Desativado</option>
                                <option value="1">Ativo</option>
                    </select>
                    </div>
                    <br><input type="submit" class="btn_loja" name="btnSalvar" value="<?php echo($botao)?>">
                        </form>
                </div>
                <div class="visualizar">
                <?php echo@($caminhoImg)?>
                </div>  
                <div class="resp_sobre">
                <h1  class="h1_sobre">Informações cadastradas:</h1>
                    <div class="item_loja">Titulo:</div>
                    <div class="item_loja">Opções: </div>
                    <?php 
                             $sql = "SELECT * FROM tbl_sobre;";

                             $select = mysqli_query($conexao, $sql);

                             while($rsConexao = mysqli_fetch_array($select)){
                     ?>
                    <div class="scroll_sobre">
                    <div class="item_loja"><?php echo($rsConexao['titulo'])?></div>
                    <div class="item_loja">
                        <a href="admSobre.php?modo=editar&id=<?php echo($rsConexao['idSobre']) ?>"><img class="vizualizar" src="image/edit.png" width="20" heigth="20"></a>
                        <a href="admSobre.php?modo=excluir&id=<?php echo($rsConexao['idSobre']) ?>"><img class="vizualizar" src="image/file.png" width="20" heigth="20"></a>
                        <?php 
                            if($rsConexao['status']==1){
                        ?>
                        <img class="vizualizar" src="image/checked.png" width="20" heigth="20">
                        <?php 
                            }else{
                        ?>
                        <img class="vizualizar" src="image/cancel.png" width="20" heigth="20">
                         <?php } ?> 
                    </div>
                    
                 <?php } ?> 
                    </div>
                </div>
            </div>
        </div>
        <!-- RODAPÉ -->
        <footer id="footer">
            Desenvolvido por Emanuelly Marinho ❤  
        </footer>
      </div>  
    </body>
</html>