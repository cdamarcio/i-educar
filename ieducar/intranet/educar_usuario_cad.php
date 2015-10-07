<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	*																	     *
	*	@author Prefeitura Municipal de Itaja�								 *
	*	@updated 29/03/2007													 *
	*   Pacote: i-PLB Software P�blico Livre e Brasileiro					 *
	*																		 *
	*	Copyright (C) 2006	PMI - Prefeitura Municipal de Itaja�			 *
	*						ctima@itajai.sc.gov.br					    	 *
	*																		 *
	*	Este  programa  �  software livre, voc� pode redistribu�-lo e/ou	 *
	*	modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme	 *
	*	publicada pela Free  Software  Foundation,  tanto  a vers�o 2 da	 *
	*	Licen�a   como  (a  seu  crit�rio)  qualquer  vers�o  mais  nova.	 *
	*																		 *
	*	Este programa  � distribu�do na expectativa de ser �til, mas SEM	 *
	*	QUALQUER GARANTIA. Sem mesmo a garantia impl�cita de COMERCIALI-	 *
	*	ZA��O  ou  de ADEQUA��O A QUALQUER PROP�SITO EM PARTICULAR. Con-	 *
	*	sulte  a  Licen�a  P�blica  Geral  GNU para obter mais detalhes.	 *
	*																		 *
	*	Voc�  deve  ter  recebido uma c�pia da Licen�a P�blica Geral GNU	 *
	*	junto  com  este  programa. Se n�o, escreva para a Free Software	 *
	*	Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA	 *
	*	02111-1307, USA.													 *
	*																		 *
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
require_once ("include/clsBase.inc.php");
require_once ("include/clsCadastro.inc.php");
require_once ("include/clsBanco.inc.php");
require_once ("include/pmieducar/clsPmieducarUsuario.inc.php");

class clsIndexBase extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Cadastro de usu&aacute;rios" );
		$this->processoAp = "555";
		$this->addEstilo('localizacaoSistema');
	}
}

class indice extends clsCadastro
{

	var $pessoa_logada;

	var $ref_pessoa;
	var $ref_cod_setor_new;

	//dados do funcionario
	var $nome;
	var $matricula;
	var $_senha;
	var $ativo;
	var $ref_cod_funcionario_vinculo;
	var $tempo_expira_conta;
	var $ramal;
	var $super;
	var $proibido;
	var $matricula_permanente;
	var $matricula_interna;

	//senha carregada do banco (controle de criptografia)
	var $confere_senha;

	//setor e subsetores
	var $setor_0;
	var $setor_1;
	var $setor_2;
	var $setor_3;
	var $setor_4;

	function Inicializar()
	{
		$retorno = "Novo";
		@session_start();
		$this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();

		$this->ref_pessoa = $_POST["ref_pessoa"];
		if( $_GET["ref_pessoa"] )
		{
			$this->ref_pessoa = $_GET["ref_pessoa"];
		}


		if( is_numeric( $this->ref_pessoa ) )
		{

			$obj_funcionario = new clsPortalFuncionario($this->ref_pessoa);
			$det_funcionario = $obj_funcionario->detalhe();
			if( $det_funcionario )
			{
				foreach ($det_funcionario as $campo => $valor) {
					$this->$campo = $valor;
				}
				$this->_senha = $this->senha;
				$this->confere_senha = $this->_senha;
				$this->fexcluir = true;
				$retorno = "Editar";
			}

			$obj_menu_funcionario = new clsPortalMenuFuncionario($this->ref_pessoa, null, null, 0);
			$det_menu_funcionario = $obj_menu_funcionario->detalhe();
			if( $det_menu_funcionario )
			{
				$this->super = true;
			}
			$this->status = $this->ativo;
			$obj = new clsPmieducarUsuario( $this->ref_pessoa);
			$registro  = $obj->detalhe();
			if( $registro )
			{
				foreach( $registro AS $campo => $val )	// passa todos os valores obtidos no registro para atributos do objeto
					$this->$campo = $val;

				$obj_permissoes = new clsPermissoes();
				$this->fexcluir = $obj_permissoes->permissao_excluir( 555, $this->pessoa_logada,7, "educar_usuario_lst.php", true );
				$retorno = "Editar";
			}
		}
		$this->url_cancelar = ($retorno == "Editar") ? "educar_usuario_det.php?ref_pessoa={$this->ref_pessoa}" : "educar_usuario_lst.php";
		$this->nome_url_cancelar = "Cancelar";

    $nomeMenu = $retorno == "Editar" ? $retorno : "Cadastrar";
    $localizacao = new LocalizacaoSistema();
    $localizacao->entradaCaminhos( array(
         $_SERVER['SERVER_NAME']."/intranet" => "In&iacute;cio",
         ""        => "{$nomeMenu} usu&aacute;rio"             
    ));
    $this->enviaLocalizacao($localizacao->montar());		

		return $retorno;
	}

	function Gerar()
	{

		$obj_permissao = new clsPermissoes();

		$this->campoOculto("ref_pessoa", $this->ref_pessoa);

		if( is_numeric($this->ref_pessoa) )
		{
			$this->campoOculto("confere_senha", $this->confere_senha);
		}

		//--------------------------------------------------------------------
		if( $_POST )
		{
			foreach( $_POST AS $campo => $val )
			{
				$this->$campo = ( $this->$campo ) ? $this->$campo : $val;
			}
		}

		 //--------------------------------------------------------------------
		$this->ref_cod_setor_new = 0;
		if( ! $this->ref_cod_setor_new && is_numeric( $this->ref_pessoa ) )
		{
			$objFuncionario = new clsPortalFuncionario( $this->ref_pessoa );
			$detFunc = $objFuncionario->detalhe();
			$this->ref_cod_setor_new = $detFunc["ref_cod_setor_new"];
		}

		if( $this->ref_cod_setor_new )
		{
			$objSetor = new clsSetor();
			$parentes = $objSetor->getNiveis( $this->ref_cod_setor_new );
			for( $i = 0; $i < 5; $i++ )
			{
				if( isset( $parentes[$i] ) && $parentes[$i] )
				{
					$nmvar = "setor_{$i}";
					$this->$nmvar = $parentes[$i];
				}
			}
		}
		 //--------------------------------------------------------------------
		if( $_GET["ref_pessoa"] )
		{
			$obj_funcionario = new clsPessoaFj($this->ref_pessoa);
			$det_funcionario = $obj_funcionario->detalhe();

			$this->nome = $det_funcionario["nome"];

			$this->campoRotulo("nome", "Nome", $this->nome);
		}
		else
		{
			$parametros = new clsParametrosPesquisas();
			$parametros->setSubmit( 1 );
			$parametros->setPessoa( "F" );
			$parametros->setPessoaNovo( 'S' );
			$parametros->setPessoaEditar( 'N' );
			$parametros->setPessoaTela( "frame" );
			$parametros->setPessoaCPF('N');
			$parametros->adicionaCampoTexto("nome", "nome");
			$parametros->adicionaCampoTexto("nome_busca", "nome");
			$parametros->adicionaCampoTexto("ref_pessoa", "idpes");
			$this->campoTextoPesquisa("nome_busca", "Nome", $this->nome, 30, 255, true, "pesquisa_pessoa_lst.php", false, false, "", "", $parametros->serializaCampos()."&busca=S", true );
			$this->campoOculto("nome", $this->nome);
			$this->campoOculto("ref_pessoa", $this->ref_pessoa);
		}

		$this->campoTexto("matricula", "Matr&iacute;cula", $this->matricula, 12, 12, true);
		$this->campoSenha("_senha", "Senha", $this->_senha, true);
		$this->campoEmail("email", "E-mail usu�rio", $this->email, 50, 50, false, false, false, 'Utilizado para redefinir a senha, caso o us�ario esque�a<br />Este campo pode ser gravado em branco, neste caso ser� solicitado um e-mail ao usu�rio, ap�s entrar no sistema.');

		$this->campoTexto('matricula_interna', 'Matr&iacute;cula interna', $this->matricula_interna, 30, 30, false, false, false , 'Utilizado somente para registro, caso a institui&ccedil;&atilde;o deseje que a matr&iacute;cula interna deste funcion&aacute;rio seja registrada no sistema.');

		$obj_setor = new clsSetor();
		$lst_setor = $obj_setor->lista(null, null, null, null, null, null, null, null, null, 1, 0);

		$opcoes = array("" => "Selecione");

		if( is_array($lst_setor) && count($lst_setor) )
		{
			foreach ($lst_setor as $setor) {
				$opcoes[$setor["cod_setor"]] = $setor["sgl_setor"];
			}
		}
		$this->campoLista("setor_0", "Setor", $opcoes, $this->setor_0, "oproDocumentoNextLvl( this.value, '1' )", NULL, NULL, NULL, NULL, FALSE);

		$lst_setor = $obj_setor->lista($this->setor_0);

		$opcoes = array("" => "Selecione");

		if( is_array($lst_setor) && count($lst_setor) )
		{
			foreach($lst_setor as $setor)
			{
				$opcoes[$setor["cod_setor"]] = $setor["sgl_setor"];
			}
		}
		else
		{
			$opcoes[""] = "---------";
		}
		$this->campoLista("setor_1", "Subsetor 1", $opcoes, $this->setor_1, "oproDocumentoNextLvl(this.value, '2')", false, "", "", $this->setor_0 == "" ? true : false, false);

		$opcoes = array("" => "Selecione");

		$lst_setor = $obj_setor->lista($this->setor_1);

		if( is_array($lst_setor) && count($lst_setor) )
		{
			foreach ($lst_setor as $setor)
			{
				$opcoes[$setor["cod_setor"]] = $setor["sgl_setor"];
			}
		}
		else
		{
			$opcoes[""] = "---------";
		}
		$this->campoLista("setor_2", "Subsetor 2", $opcoes, $this->setor_2, "oproDocumentoNextLvl(this.value, '3')", false, "", "", $this->setor_1 == "" ? true : false, false);

		$opcoes = array("" => "Selecione");

		$lst_setor = $obj_setor->lista($this->setor_2);

		if( is_array($lst_setor) && count($lst_setor) )
		{
			foreach ($lst_setor as $setor)
			{
				$opcoes[$setor["cod_setor"]] = $setor["sgl_setor"];
			}
		}
		else
		{
			$opcoes[""] = "---------";
		}
		$this->campoLista("setor_3", "Subsetor 3", $opcoes, $this->setor_3, "oproDocumentoNextLvl(this.value, '4')", false, "", "", $this->setor_2 == "" ? true : false, false);

		$opcoes = array("" => "Selecione");

		$lst_setor = $obj_setor->lista($this->setor_3);

		if( is_array($lst_setor) && count($lst_setor) )
		{
			foreach ($lst_setor as $setor)
			{
				$opcoes[$setor["cod_setor"]] = $setor["sgl_setor"];
			}
		}
		else
		{
			$opcoes[""] = "---------";
		}
		$this->campoLista("setor_4", "Subsetor 4", $opcoes, $this->setor_4, "oproDocumentoNextLvl(this.value, '5')", false, "", "", $this->setor_3 == "" ? true : false, false);

		$opcoes = array(0 => "Inativo", 1 => "Ativo");
		if (!$this->ref_cod_pessoa_fj == '')
			$this->campoLista("ativo", "Status", $opcoes, $this->status);
		else
			$this->campoLista("ativo", "Status", $opcoes, 1);

		$opcoes = array("" => "Selecione", 5 => "Comissionado", 4 => "Contratado", 3 => "Efetivo", 6 => "Estagi&aacute;rio");
		$this->campoLista("ref_cod_funcionario_vinculo", "V&iacute;nculo", $opcoes, $this->ref_cod_funcionario_vinculo);

		$opcoes = array("" => "Selecione",
						 5 => "5",
						 6 => "6",
						 7 => "7",
						 10 => "10",
						 14 => "14",
						 20 => "20",
						 21 => "21",
						 28 => "28",
						 30 => "30",
						 35 => "35",
						 60 => "60",
						 90 => "90",
						120 => "120",
						150 => "150",
						180 => "180",
						210 => "210",
						240 => "240",
						270 => "270",
						300 => "300",
						365 => "365"
						);

		$this->campoLista("tempo_expira_conta", "Dias p/ expirar a conta", $opcoes, $this->tempo_expira_conta);

		$tempoExpiraSenha = $GLOBALS['coreExt']['Config']->app->user_accounts->default_password_expiration_period;

		if (is_numeric($tempoExpiraSenha))
			$this->campoOculto("tempo_expira_senha", $tempoExpiraSenha);
		else {
			$opcoes = array('' => 'Selecione', 5 => '5', 30 => '30', 60 => '60', 90 => '90', 120 => '120', 180 => '180');
			$this->campoLista("tempo_expira_senha", "Dias p/ expirar a senha", $opcoes, $this->tempo_expira_senha);
		}

		$this->campoTexto("ramal", "Ramal", $this->ramal, 11, 30);

		$opcoes = array(null => "N�o", 'S' => "Sim");
		$this->campoLista("super", "Super usu&aacute;rio", $opcoes, $this->super, '',false,'','',false,false);

		$opcoes = array(null => "N�o", 1 => "Sim");
		$this->campoLista("proibido", "Banido", $opcoes, $this->proibido, '',false,'','',false,false);

		$opcoes = array(null => "N�o", 1 => "Sim");
		$this->campoLista("matricula_permanente", "Matr&iacute;cula permanente", $opcoes, $this->matricula_permanente, '',false,'','',false,false);

		$opcoes = array( "" => "Selecione" );
		if( class_exists( "clsPmieducarTipoUsuario" ) )
		{
			$objTemp = new clsPmieducarTipoUsuario();
			$objTemp->setOrderby('nm_tipo ASC');

			$obj_libera_menu = new clsMenuFuncionario($this->pessoa_logada,false,false,0);
			$obj_super_usuario = $obj_libera_menu->detalhe();

			// verifica se pessoa logada � super-usuario
			if ($obj_super_usuario) {
				$lista = $objTemp->lista(null,null,null,null,null,null,null,null,1);
			}else{
				$lista = $objTemp->lista(null,null,null,null,null,null,null,null,1,$obj_permissao->nivel_acesso($this->pessoa_logada));
			}

			if ( is_array( $lista ) && count( $lista ) )
			{
				foreach ( $lista as $registro )
				{
					$opcoes["{$registro['cod_tipo_usuario']}"] = "{$registro['nm_tipo']}";
					$opcoes_["{$registro['cod_tipo_usuario']}"] = "{$registro['nivel']}";
				}
			}
		}
		else
		{
			echo "<!--\nErro\nClasse clsPmieducarTipoUsuario n&atilde;o encontrada\n-->";
			$opcoes = array( "" => "Erro na gera��o" );
		}
		$tamanho = sizeof($opcoes_);
		echo "<script>\nvar cod_tipo_usuario = new Array({$tamanho});\n";
		foreach ($opcoes_ as $key => $valor)
			echo "cod_tipo_usuario[{$key}] = {$valor};\n";
		echo "</script>";

		$this->campoLista( "ref_cod_tipo_usuario", "Tipo Usu&aacute;rio", $opcoes, $this->ref_cod_tipo_usuario,"",null,null,null,null,true );

		$nivel = $obj_permissao->nivel_acesso($this->ref_pessoa);

		$this->campoOculto("nivel_usuario_",$nivel);

		$get_biblioteca			= false;
		$get_escola 			= true;

		$cad_usuario = true;
		include( "include/pmieducar/educar_campo_lista.php" );

		$this->acao_enviar = "valida()";			

	}

	function Novo()
	{
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();

		//setor recebe o id do ultimo subsetor selecionado
		$this->ref_cod_setor_new = 0;
		for( $i = 0; $i < 5; $i++ )
		{
			$nmvar = "setor_{$i}";
			if( is_numeric( $this->$nmvar ) && $this->$nmvar )
			{
				$this->ref_cod_setor_new = $this->$nmvar;
			}
		}

    if (! $this->validatesUniquenessOfMatricula($this->ref_pessoa, $this->matricula))
      return false;

    if (! $this->validatesPassword($this->matricula, $this->_senha))
      return false;

		$obj_funcionario = new clsPortalFuncionario($this->ref_pessoa, $this->matricula, md5($this->_senha), $this->ativo, null, $this->ramal, null, null, null, null, null, null, null, null, $this->ref_cod_funcionario_vinculo, $this->tempo_expira_senha, $this->tempo_expira_conta, "NOW()", "NOW()", $this->pessoa_logada, empty($this->proibido) ? 0 : 1, $this->ref_cod_setor_new, null, empty($this->matricula_permanente)? 0 : 1, 1, $this->email, $this->matricula_interna);
		if( $obj_funcionario->cadastra() )
		{

			if ($this->ref_cod_instituicao && $this->ref_cod_escola)
			{
				$obj = new clsPmieducarUsuario( $this->ref_pessoa, $this->ref_cod_escola, $this->ref_cod_instituicao, $this->pessoa_logada,  $this->pessoa_logada, $this->ref_cod_tipo_usuario,null,null,1 );
			} // verifica se usuario � institucional
			else if ($this->ref_cod_instituicao && !$this->ref_cod_escola)
			{
				$obj = new clsPmieducarUsuario( $this->ref_pessoa, null, $this->ref_cod_instituicao, $this->pessoa_logada,  $this->pessoa_logada, $this->ref_cod_tipo_usuario,null,null,1 );
			} // verifica se usuario � poli-institucional
			else if (!$this->ref_cod_instituicao && !$this->ref_cod_escola)
			{
				$obj = new clsPmieducarUsuario( $this->ref_pessoa, null, null, $this->pessoa_logada,  $this->pessoa_logada, $this->ref_cod_tipo_usuario,null,null,1 );
			}
			if($obj->existe())
				$cadastrou = $obj->edita();
			else
				$cadastrou = $obj->cadastra();	

			if( $cadastrou )
			{
				$this->mensagem .= "Cadastro efetuado com sucesso.<br>";
				header( "Location: educar_usuario_lst.php" );
				die();
				return true;
			}
		}
		$this->mensagem = "Cadastro n&atilde;o realizado.<br>";
		echo "<!--\nErro ao cadastrar -->";
		return false;
	}


	function Editar()
	{
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();

		$this->ref_cod_setor_new = 0;
		for( $i = 0; $i < 5; $i++ )
		{
			$nmvar = "setor_{$i}";
			if( is_numeric( $this->$nmvar ) && $this->$nmvar )
			{
				$this->ref_cod_setor_new = $this->$nmvar;
			}
		}

    if (! $this->validatesUniquenessOfMatricula($this->ref_pessoa, $this->matricula))
      return false;

    if (! $this->validatesPassword($this->matricula, $this->_senha))
      return false;

		//verifica se a senha ja esta criptografada
		if($this->_senha != $this->confere_senha)
		{
			$this->_senha = md5($this->_senha);
		}

		$obj_funcionario = new clsPortalFuncionario($this->ref_pessoa, $this->matricula, $this->_senha, $this->ativo, null, $this->ramal, null, null, null, null, null, null, null, null, $this->ref_cod_funcionario_vinculo, $this->tempo_expira_senha, $this->tempo_expira_conta, "NOW()", "NOW()", $this->pessoa_logada, empty($this->proibido) ? 0 : 1, $this->ref_cod_setor_new, null, empty($this->matricula_permanente) ? 0 : 1, null, $this->email, $this->matricula_interna);
		if( $obj_funcionario->edita() )
		{

			if ($this->ref_cod_instituicao && $this->ref_cod_escola)
			{
				$obj = new clsPmieducarUsuario( $this->ref_pessoa, $this->ref_cod_escola, $this->ref_cod_instituicao, $this->pessoa_logada,  $this->pessoa_logada, $this->ref_cod_tipo_usuario,null,null,1 );
			} // verifica se usuario � institucional
			else if ($this->ref_cod_instituicao && !$this->ref_cod_escola)
			{
				$obj = new clsPmieducarUsuario( $this->ref_pessoa, null, $this->ref_cod_instituicao, $this->pessoa_logada,  $this->pessoa_logada, $this->ref_cod_tipo_usuario,null,null,1 );
			} // verifica se usuario � poli-institucional
			else if (!$this->ref_cod_instituicao && !$this->ref_cod_escola)
			{
				$obj = new clsPmieducarUsuario( $this->ref_pessoa, null, null, $this->pessoa_logada,  $this->pessoa_logada, $this->ref_cod_tipo_usuario,null,null,1 );
			}
			if($obj->existe())
				$editou = $obj->edita();
			else
				$editou = $obj->cadastra();

			if($this->nivel_usuario_ == 8)
			{
				$obj_tipo = new clsPmieducarTipoUsuario($this->ref_cod_tipo_usuario);
				$det_tipo = $obj_tipo->detalhe();
				if($det_tipo['nivel'] != 8){
					$obj_usuario_bib = new clsPmieducarBibliotecaUsuario();
					$lista_bibliotecas_usuario = $obj_usuario_bib->lista(null,$this->pessoa_logada);

					if ($lista_bibliotecas_usuario) {

						foreach ($lista_bibliotecas_usuario as $usuario)
						{
							$obj_usuario_bib = new clsPmieducarBibliotecaUsuario($usuario['ref_cod_biblioteca'],$this->pessoa_logada);
							if(!$obj_usuario_bib->excluir()){
								echo "<!--\nErro ao excluir usuarios biblioteca\n-->";
								return false;
							}
						}
					}
				}
			}

			if($this->ref_cod_instituicao != $this->ref_cod_instituicao_)
			{
				$obj_biblio = new clsPmieducarBiblioteca();
				$lista_biblio_inst = $obj_biblio->lista(null,$this->ref_cod_instituicao_);
				if($lista_biblio_inst)
				{
					foreach ($lista_biblio_inst as $biblioteca) {
						$obj_usuario_bib = new clsPmieducarBibliotecaUsuario($biblioteca['cod_biblioteca'],$this->pessoa_logada);
						$obj_usuario_bib->excluir();
					}
				}
			}

			if( $editou )
			{
				$this->mensagem .= "Edi&ccedil;&atilde;o efetuada com sucesso.<br>";
				header( "Location: educar_usuario_lst.php" );
				die();
				return true;
			}
		}

		$this->mensagem = "Edi&ccedil;&atilde;o n&atilde;o realizada.<br>";
		echo "<!--\nErro ao editar clsPortalFuncionario-->";
		return false;
	}

	function Excluir()
	{
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();

		$obj_funcionario = new clsPortalFuncionario($this->ref_pessoa);
		if($obj_funcionario->excluir())
		{
			$this->mensagem .= "Exclus&atilde;o efetuada com sucesso.<br>";
			header( "Location: educar_usuario_lst.php" );
			return true;
		}
		$this->mensagem = "Exclus&atilde;o n&atilde;o realizada.<br>";
		echo "<!--\nErro ao excluir clsPortalFuncionario\n-->";
		return false;
	}


  function validatesUniquenessOfMatricula($pessoaId, $matricula) {
    $sql = "select 1 from portal.funcionario where lower(matricula) = lower('$matricula') and ref_cod_pessoa_fj != $pessoaId";
    $db = new clsBanco();

		if ($db->CampoUnico($sql) == '1') {
      $this->mensagem = "A matr�cula '$matricula' j� foi usada, por favor, informe outra.";
      return false;
    }
    return true;
  }

  function validatesPassword($matricula, $password) {
    $msg = '';

		if ($password == $matricula)
      $msg = 'Informe uma senha diferente da matricula.';
    elseif (strlen($password) < 8)
      $msg = 'Por favor informe uma senha segura, com pelo menos 8 caracteres.';

    if ($msg) {
      $this->mensagem = $msg;
      return false;
    }
    return true;
  }
}

// cria uma extensao da classe base
$pagina = new clsIndexBase();
// cria o conteudo
$miolo = new indice();
// adiciona o conteudo na clsBase
$pagina->addForm( $miolo );
// gera o html
$pagina->MakeAll();
?>
<script>

//var campo_tipo_usuario = document.getElementById("ref_cod_tipo_usuario");
//var campo_instituicao = document.getElementById("ref_cod_instituicao");
//var campo_escola = document.getElementById("ref_cod_escola");
//var campo_biblioteca = document.getElementById("ref_cod_biblioteca");
//
//campo_instituicao.disabled = true;
//campo_escola.disabled = true;
//campo_biblioteca.disabled = true;

var campo_tipo_usuario = document.getElementById("ref_cod_tipo_usuario");
var campo_instituicao = document.getElementById("ref_cod_instituicao");
var campo_escola = document.getElementById("ref_cod_escola");
//var campo_biblioteca = document.getElementById("ref_cod_biblioteca");

if(  campo_tipo_usuario.value == "" )
{
	campo_instituicao.disabled = true;
	campo_escola.disabled = true;
	//campo_biblioteca.disabled = true;

}
else if( cod_tipo_usuario[campo_tipo_usuario.value] == 1 )
{
	campo_instituicao.disabled = true;
	campo_escola.disabled = true;
//	campo_biblioteca.disabled = true;
}
else if( cod_tipo_usuario[campo_tipo_usuario.value] == 2 )
{
	campo_instituicao.disabled = false;
	campo_escola.disabled = true;
//	campo_biblioteca.disabled = true;
}
else if( cod_tipo_usuario[campo_tipo_usuario.value] == 4 )
{
	campo_instituicao.disabled = false;
	campo_escola.disabled = false;
	//campo_biblioteca.disabled = true;
}
else if( cod_tipo_usuario[campo_tipo_usuario.value] == 8 )
{
	campo_instituicao.disabled = false;
	campo_escola.disabled = false;
	//campo_biblioteca.disabled = false;
}

document.getElementById('ref_cod_tipo_usuario').onchange = function()
{
	habilitaCampos();
}

//function getEscola()
//{
//	var campoInstituicao = document.getElementById('ref_cod_instituicao').value;
//	var campoEscola = document.getElementById('ref_cod_escola');
//
//	campoEscola.length = 1;
//	for (var j = 0; j < escola.length; j++)
//	{
//		if (escola[j][2] == campoInstituicao)
//		{
//			campoEscola.options[campoEscola.options.length] = new Option( escola[j][1], escola[j][0],false,false);
//		}
//	}
//}

function habilitaCampos()
{
	if( cod_tipo_usuario[campo_tipo_usuario.value] == 1 )
	{
		campo_instituicao.disabled = true;
		campo_escola.disabled = true;
		//campo_biblioteca.disabled = true;
	}
	else if( cod_tipo_usuario[campo_tipo_usuario.value] == 2 )
	{
		campo_instituicao.disabled = false;
		campo_escola.disabled = true;
		//campo_biblioteca.disabled = true;
	}
	else if( cod_tipo_usuario[campo_tipo_usuario.value] == 4 )
	{
		campo_instituicao.disabled = false;
		campo_escola.disabled = false;
		//campo_biblioteca.disabled = true;
	}
	else if( cod_tipo_usuario[campo_tipo_usuario.value] == 8 )
	{
		campo_instituicao.disabled = false;
		campo_escola.disabled = false;
		//campo_biblioteca.disabled = false;
	}
//	else if( campo == "ref_cod_instituicao" &&
//			 cod_tipo_usuario[campo_tipo_usuario.options[campo_tipo_usuario.selectedIndex].value] == 4 )
//	{
//		campo_escola.disabled = false;
//		campo_biblioteca.disabled = true;
//		getEscola();
//	}
//	else if( campo == "ref_cod_instituicao" &&
//			 cod_tipo_usuario[campo_tipo_usuario.options[campo_tipo_usuario.selectedIndex].value] == 8 )
//	{
//		campo_escola.disabled = false;
//		campo_biblioteca.disabled = false;
//		getEscola();
//	}

}

//function habilitaCampos()
//{
////	var campo_tipo_usuario = document.getElementById("ref_cod_tipo_usuario");
////	var campo_instituicao = document.getElementById("ref_cod_instituicao");
////	var campo_escola = document.getElementById("ref_cod_escola");
////	var campo_biblioteca = document.getElementById("ref_cod_biblioteca");
//
//	if(  campo_tipo_usuario == "" )
//	{
//		campo_instituicao.disabled = true;
//		campo_escola.disabled = true;
//		campo_biblioteca.disabled = true;
//
//	}
//	else if( campo == "ref_cod_tipo_usuario" )
//	{
//		if( cod_tipo_usuario[campo_tipo_usuario.options[campo_tipo_usuario.selectedIndex].value] == 1 ||
//			cod_tipo_usuario[campo_tipo_usuario.options[campo_tipo_usuario.selectedIndex].value] == null )
//		{
//			campo_instituicao.disabled = true;
//			campo_escola.disabled = true;
//			campo_biblioteca.disabled = true;
//		}
//		else if( cod_tipo_usuario[campo_tipo_usuario.options[campo_tipo_usuario.selectedIndex].value] == 2 )
//		{
//			campo_instituicao.disabled = false;
//			campo_escola.disabled = true;
//			campo_biblioteca.disabled = true;
//		}
//		else if( cod_tipo_usuario[campo_tipo_usuario.options[campo_tipo_usuario.selectedIndex].value] == 4  )
//		{
//			campo_instituicao.disabled = false;
//			campo_escola.disabled = false;
//			campo_biblioteca.disabled = true;
//			getEscola();
//		}
//		else if( cod_tipo_usuario[campo_tipo_usuario.options[campo_tipo_usuario.selectedIndex].value] == 8 )
//		{
//			campo_instituicao.disabled = false;
//			campo_escola.disabled = false;
//			campo_biblioteca.disabled = false;
//			getEscola();
//		}
//	}
//	else if( campo == "ref_cod_instituicao" &&
//			 cod_tipo_usuario[campo_tipo_usuario.options[campo_tipo_usuario.selectedIndex].value] == 4 )
//	{
//		campo_escola.disabled = false;
//		campo_biblioteca.disabled = true;
//		getEscola();
//	}
//	else if( campo == "ref_cod_instituicao" &&
//			 cod_tipo_usuario[campo_tipo_usuario.options[campo_tipo_usuario.selectedIndex].value] == 8 )
//	{
//		campo_escola.disabled = false;
//		campo_biblioteca.disabled = false;
//		getEscola();
//	}
//
//}

function valida()
{
	var campo_tipo_usuario = document.getElementById("ref_cod_tipo_usuario");
	var campo_instituicao = document.getElementById("ref_cod_instituicao");
	var campo_escola = document.getElementById("ref_cod_escola");

	if( cod_tipo_usuario[campo_tipo_usuario.options[campo_tipo_usuario.selectedIndex].value] == 2)
	{
		if( campo_instituicao.options[campo_instituicao.selectedIndex].value == "" )
		{
			alert("� obrigat�rio a escolha de uma Institui��o!");
			return false;
		}
	}
	else if( cod_tipo_usuario[campo_tipo_usuario.options[campo_tipo_usuario.selectedIndex].value] == 4 || campo_tipo_usuario.value == 6)
	{
		if( campo_instituicao.options[campo_instituicao.selectedIndex].value == "" )
		{
			alert("� obrigat�rio a escolha de uma Institui��o!");
			return false;
		}
		else if( cod_tipo_usuario[campo_instituicao.options[campo_instituicao.selectedIndex].value] != "")
		{
			if( campo_escola.options[campo_escola.selectedIndex].value == "" && campo_tipo_usuario.value != 6)
			{
				alert("� obrigat�rio a escolha de uma Escola!");
				return false;
			}
		}
	}
	else if( cod_tipo_usuario[campo_tipo_usuario.options[campo_tipo_usuario.selectedIndex].value] == 8)
	{
		if( campo_instituicao.options[campo_instituicao.selectedIndex].value == "" )
		{
			alert("� obrigat�rio a escolha de uma Institui��o! ");
			return false;
		}
	}
	if(!acao())
		return;
	document.forms[0].submit();
}

</script>