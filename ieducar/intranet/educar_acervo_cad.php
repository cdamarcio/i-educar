<?php
#error_reporting(E_ALL);
#ini_set("display_errors", 1);
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
require_once "include/clsBase.inc.php";
require_once "include/clsCadastro.inc.php";
require_once "include/clsBanco.inc.php";
require_once "include/pmieducar/geral.inc.php";

class clsIndexBase extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} i-Educar - Obras" );
		$this->processoAp = "598";
		$this->addEstilo('localizacaoSistema');
	}
}

class indice extends clsCadastro
{
	/**
	 * Referencia pega da session para o idpes do usuario atual
	 *
	 * @var int
	 */
	var $pessoa_logada;

	var $cod_acervo;
	var $ref_cod_exemplar_tipo;
	var $ref_cod_acervo;
	var $ref_usuario_exc;
	var $ref_usuario_cad;
	var $ref_cod_acervo_colecao;
	var $ref_cod_acervo_idioma;
	var $ref_cod_acervo_editora;
	var $titulo_livro;
	var $sub_titulo;
	var $cdu;
	var $cutter;
	var $volume;
	var $num_edicao;
	var $ano;
	var $num_paginas;
	var $isbn;
	var $data_cadastro;
	var $data_exclusao;
	var $ativo;
	var $ref_cod_biblioteca;
	var $dimencao;
	var $ref_cod_tipo_autor;
	var $tipo_autor;
	var $material_ilustrativo;
	var $dimencao_ilustrativo;
	var $local;
	var $ref_cod_instituicao;
	var $ref_cod_escola;

	var $checked;

	var $acervo_autor;
	var $ref_cod_acervo_autor;
	var $principal;
	var $incluir_autor;
	var $excluir_autor;

	var $colecao;
	var $editora;
	var $idioma;
	var $autor;

  protected function setSelectionFields()
  {

  }

	function Inicializar()
	{
		$retorno = "Novo";
		@session_start();
		$this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();

		$this->cod_acervo=$_GET["cod_acervo"];

		$obj_permissoes = new clsPermissoes();
		$obj_permissoes->permissao_cadastra( 598, $this->pessoa_logada, 11,  "educar_acervo_lst.php" );

		if( is_numeric( $this->cod_acervo ) )
		{

			$obj = new clsPmieducarAcervo( $this->cod_acervo );
			$registro  = $obj->detalhe();
			if( $registro )
			{
				foreach( $registro AS $campo => $val )	// passa todos os valores obtidos no registro para atributos do objeto
					$this->$campo = $val;

				$obj_biblioteca = new clsPmieducarBiblioteca($this->ref_cod_biblioteca);
				$obj_det = $obj_biblioteca->detalhe();

				$this->ref_cod_instituicao = $obj_det["ref_cod_instituicao"];
				$this->ref_cod_escola = $obj_det["ref_cod_escola"];


				$obj_permissoes = new clsPermissoes();
				if( $obj_permissoes->permissao_excluir( 598, $this->pessoa_logada, 11 ) )
				{
					$this->fexcluir = true;
				}

				$retorno = "Editar";
			}
		}
		$this->url_cancelar = ($retorno == "Editar") ? "educar_acervo_det.php?cod_acervo={$registro["cod_acervo"]}" : "educar_acervo_lst.php";
		$this->nome_url_cancelar = "Cancelar";

    $nomeMenu = $retorno == "Editar" ? $retorno : "Cadastrar";
    $localizacao = new LocalizacaoSistema();
    $localizacao->entradaCaminhos( array(
         $_SERVER['SERVER_NAME']."/intranet" => "In&iacute;cio",
         "educar_biblioteca_index.php"                  => "i-Educar - Biblioteca",
         ""        => "{$nomeMenu} obra"             
    ));
    $this->enviaLocalizacao($localizacao->montar());

		return $retorno;
	}

	function Gerar()
	{
		if( $_POST )
		{
			foreach( $_POST AS $campo => $val )
				$this->$campo = ( $this->$campo ) ? $this->$campo : $val;
		}
		if(is_numeric($this->colecao))
		{
			$this->ref_cod_acervo_colecao = $this->colecao;
		}
		if(is_numeric($this->editora))
		{
			$this->ref_cod_acervo_editora = $this->editora;
		}
		if(is_numeric($this->idioma))
		{
			$this->ref_cod_acervo_idioma = $this->idioma;
		}
		if(is_numeric($this->autor))
		{
			$this->ref_cod_acervo_autor = $this->autor;
		}

		// primary keys
		$this->campoOculto( "cod_acervo", $this->cod_acervo );
		$this->campoOculto( "colecao", "" );
		$this->campoOculto( "editora", "" );
		$this->campoOculto( "idioma", "" );
		$this->campoOculto( "autor", "" );

    $this->inputsHelper()->dynamic(array('instituicao', 'escola', 'biblioteca', 'bibliotecaTipoExemplar'));

    // Obra refer�ncia
		$opcoes = array( "NULL" => "Selecione" );

		if( $this->ref_cod_acervo && $this->ref_cod_acervo != "NULL")
		{
			$objTemp = new clsPmieducarAcervo($this->ref_cod_acervo);
			$detalhe = $objTemp->detalhe();
			if ( $detalhe )
			{
				$opcoes["{$detalhe['cod_acervo']}"] = "{$detalhe['titulo']}";
			}
		}

		$this->campoLista("ref_cod_acervo","Obra Refer&ecirc;ncia",$opcoes,$this->ref_cod_acervo,"",false,"","<img border=\"0\" onclick=\"pesquisa();\" id=\"ref_cod_acervo_lupa\" name=\"ref_cod_acervo_lupa\" src=\"imagens/lupa.png\"\/>",false,false);

    // Cole��o
		$opcoes = array( "" => "Selecione" );
		if( class_exists( "clsPmieducarAcervoColecao" ) )
		{
			$objTemp = new clsPmieducarAcervoColecao();
			$lista = $objTemp->lista();
			if ( is_array( $lista ) && count( $lista ) )
			{
				foreach ( $lista as $registro )
				{
					$opcoes["{$registro['cod_acervo_colecao']}"] = "{$registro['nm_colecao']}";
				}
			}
		}
		else
		{
			echo "<!--\nErro\nClasse clsPmieducarAcervoColecao nao encontrada\n-->";
			$opcoes = array( "" => "Erro na geracao" );
		}
		$this->campoLista( "ref_cod_acervo_colecao", "Cole&ccedil;&atilde;o", $opcoes, $this->ref_cod_acervo_colecao,"",false,"","<img id='img_colecao' src='imagens/banco_imagens/escreve.gif' style='cursor:hand; cursor:pointer;' border='0' onclick=\"showExpansivelImprimir(500, 200,'educar_acervo_colecao_cad_pop.php',[], 'Cole��o')\" />",false,false );

    // Idioma
		$opcoes = array( "" => "Selecione" );
		if( class_exists( "clsPmieducarAcervoIdioma" ) )
		{
			$objTemp = new clsPmieducarAcervoIdioma();
			$lista = $objTemp->lista();
			if ( is_array( $lista ) && count( $lista ) )
			{
				foreach ( $lista as $registro )
				{
					$opcoes["{$registro['cod_acervo_idioma']}"] = "{$registro['nm_idioma']}";
				}
			}
		}
		else
		{
			echo "<!--\nErro\nClasse clsPmieducarAcervoIdioma nao encontrada\n-->";
			$opcoes = array( "" => "Erro na geracao" );
		}
		$this->campoLista( "ref_cod_acervo_idioma", "Idioma", $opcoes, $this->ref_cod_acervo_idioma, "", false, "", "<img id='img_idioma' src='imagens/banco_imagens/escreve.gif' style='cursor:hand; cursor:pointer;' border='0' onclick=\"showExpansivelImprimir(400, 150,'educar_acervo_idioma_cad_pop.php',[], 'Idioma')\" />" );

		$opcoes = array( "" => "Selecione" );
		if( class_exists( "clsPmieducarAcervoEditora" ) )
		{
			$objTemp = new clsPmieducarAcervoEditora();
			$lista = $objTemp->lista();
			if ( is_array( $lista ) && count( $lista ) )
			{
				foreach ( $lista as $registro )
				{
					$opcoes["{$registro['cod_acervo_editora']}"] = "{$registro['nm_editora']}";
				}
			}
		}
		else
		{
			echo "<!--\nErro\nClasse clsPmieducarAcervoEditora nao encontrada\n-->";
			$opcoes = array( "" => "Erro na geracao" );
		}
		$this->campoLista( "ref_cod_acervo_editora", "Editora", $opcoes, $this->ref_cod_acervo_editora, "", false, "", "<img id='img_editora' src='imagens/banco_imagens/escreve.gif' style='cursor:hand; cursor:pointer;' border='0' onclick=\"showExpansivelImprimir(400, 320,'educar_acervo_editora_cad_pop.php',[], 'Editora')\" />" );


		//-----------------------INCLUI AUTOR------------------------//

        $opcoes = array( "" => "Selecione", 1 => "Autor - Nome pessoal", 2 => "Autor - Evento", 3 => "Autor - Entidade coletiva", 4 => "Obra An�nimo");
		$this->campoLista( "ref_cod_tipo_autor", "Tipo de autor", $opcoes, $this->ref_cod_tipo_autor, false, true , false , false  , false, false  );
		$this->campoTexto( "tipo_autor", "", $this->tipo_autor, 40, 255, false);
		$helperOptions = array('objectName' => 'autores');
    	$options       = array('label' => 'Autores', 'size' => 50, 'required' => false, 'options' => array('value' => null));
		$this->inputsHelper()->multipleSearchAutores('', $options, $helperOptions);

		// text
		$this->campoTexto( "titulo", "T&iacute;tulo", $this->titulo, 40, 255, true );
		$this->campoTexto( "sub_titulo", "Subt&iacute;tulo", $this->sub_titulo, 40, 255, false );
		$this->campoTexto( "estante", "Estante", $this->estante, 20, 15, false );
		$this->campoTexto( "dimencao", "Dimens�o", $this->dimencao, 20, 255, false );
		$this->campoTexto( "material_ilustrativo", "Material ilustrativo", $this->material_ilustrativo, 20, 255, false );
		//$this->campoTexto( "dimencao_ilustrativo", "Dimens�o da ilustra��o", $this->dimencao_ilustrativo, 20, 255, false );
		$this->campoTexto( "local", "Local", $this->local, 20, 255, false );

 		$helperOptions = array('objectName' => 'assuntos');
  	    $options       = array('label' => 'Assuntos', 'size' => 50, 'required' => false, 'options' => array('value' => null));
 		$this->inputsHelper()->multipleSearchAssuntos('', $options, $helperOptions);

		$this->campoTexto( "cdd", "CDD", $this->cdd, 20, 15, false );
		$this->campoTexto( "cdu", "CDU", $this->cdu, 20, 15, false );
		$this->campoTexto( "cutter", "Cutter", $this->cutter, 20, 15, false );
		$this->campoNumero( "volume", "Volume", $this->volume, 20, 255, false );
		$this->campoNumero( "num_edicao", "N&uacute;mero Edic&atilde;o", $this->num_edicao, 20, 255, false );
		$this->campoNumero( "ano", "Ano", $this->ano, 5, 4, false );
		$this->campoNumero( "num_paginas", "N&uacute;mero P&aacute;ginas", $this->num_paginas, 5, 255, false );
		$this->campoTexto( "isbn", "ISBN", $this->isbn, 20, 13, false );

	}

	function Novo()
	{
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();
		$obj_permissoes = new clsPermissoes();
		$obj_permissoes->permissao_cadastra( 598, $this->pessoa_logada, 11,  "educar_acervo_lst.php" );

		

		$obj = new clsPmieducarAcervo( null, $this->ref_cod_exemplar_tipo, $this->ref_cod_acervo, null, $this->pessoa_logada, $this->ref_cod_acervo_colecao, $this->ref_cod_acervo_idioma, $this->ref_cod_acervo_editora, $this->titulo, $this->sub_titulo, $this->cdu, $this->cutter, $this->volume, $this->num_edicao, $this->ano, $this->num_paginas, $this->isbn, null, null, 1, $this->ref_cod_biblioteca, $this->cdd, $this->estante, $this->dimencao, $this->material_ilustrativo, null ,$this->local , $this->ref_cod_tipo_autor , $this->tipo_autor );
		$cadastrou = $obj->cadastra();
		if( $cadastrou )
		{			
			#cadastra assuntos para a obra
			$this->gravaAssuntos($cadastrou);
			$this->gravaAutores($cadastrou);
			
			$this->mensagem .= "Cadastro efetuado com sucesso.<br>";
			header( "Location: educar_acervo_lst.php" );
			die();
			return true;
		}
		$this->mensagem = "Cadastro n&atilde;o realizado.<br>";
		echo "<!--\nErro ao cadastrar clsPmieducarAcervo\nvalores obrigatorios\nis_numeric( $this->ref_cod_exemplar_tipo ) && is_numeric( $this->ref_usuario_cad ) && is_numeric( $this->ref_cod_acervo_colecao ) && is_numeric( $this->ref_cod_acervo_idioma ) && is_numeric( $this->ref_cod_acervo_editora ) && is_string( $this->titulo ) && is_string( $this->isbn )\n-->";
		return false;
	}

	function Editar()
	{
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();
		
		$obj_permissoes = new clsPermissoes();
		$obj_permissoes->permissao_cadastra( 598, $this->pessoa_logada, 11,  "educar_acervo_lst.php" );

		

		$obj = new clsPmieducarAcervo($this->cod_acervo, $this->ref_cod_exemplar_tipo, $this->ref_cod_acervo, $this->pessoa_logada, null, $this->ref_cod_acervo_colecao, $this->ref_cod_acervo_idioma, $this->ref_cod_acervo_editora, $this->titulo, $this->sub_titulo, $this->cdu, $this->cutter, $this->volume, $this->num_edicao, $this->ano, $this->num_paginas, $this->isbn, null, null, 1, $this->ref_cod_biblioteca, $this->cdd, $this->estante, $this->dimencao, $this->material_ilustrativo, null, $this->local, $this->ref_cod_tipo_autor , $this->tipo_autor);
		$editou = $obj->edita();
		if( $editou )
		{

			#cadastra assuntos para a obra
			$this->gravaAssuntos($this->cod_acervo);
			$this->gravaAutores($this->cod_acervo);

			$this->mensagem .= "Edi&ccedil;&atilde;o efetuada com sucesso.<br>";
			header( "Location: educar_acervo_lst.php" );
			die();
			return true;
		}
		$this->mensagem = "Edi&ccedil;&atilde;o n&atilde;o realizada.<br>";
		echo "<!--\nErro ao editar clsPmieducarAcervo\nvalores obrigatorios\nif( is_numeric( $this->cod_acervo ) && is_numeric( $this->ref_usuario_exc ) )\n-->";
		return false;
	}

	function Excluir()
	{
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();

		$obj_permissoes = new clsPermissoes();
		$obj_permissoes->permissao_excluir( 598, $this->pessoa_logada, 11,  "educar_acervo_lst.php" );


		$obj = new clsPmieducarAcervo($this->cod_acervo, null, null, $this->pessoa_logada, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 0, $this->ref_cod_biblioteca);
		$excluiu = $obj->excluir();
		if( $excluiu )
		{
			$this->mensagem .= "Exclus&atilde;o efetuada com sucesso.<br>";
			header( "Location: educar_acervo_lst.php" );
			die();
			return true;
		}

		$this->mensagem = "Exclus&atilde;o n&atilde;o realizada.<br>";
		echo "<!--\nErro ao excluir clsPmieducarAcervo\nvalores obrigatorios\nif( is_numeric( $this->cod_acervo ) && is_numeric( $this->pessoa_logada ) )\n-->";
		return false;
	}

	function gravaAssuntos($cod_acervo){
		$objAssunto = new clsPmieducarAcervoAssunto();
		$objAssunto->deletaAssuntosDaObra($cod_acervo);
		foreach ($this->getRequest()->assuntos as $assuntoId) {
			if (! empty($assuntoId)) {
				$objAssunto = new clsPmieducarAcervoAssunto();
				$objAssunto->cadastraAssuntoParaObra($cod_acervo, $assuntoId);
			}
		}
	}

	function gravaAutores($cod_acervo){
		$objAutor = new clsPmieducarAcervoAcervoAutor();
		$objAutor->deletaAutoresDaObra($cod_acervo);
		foreach ($this->getRequest()->autores as $autorId) {
			if (! empty($autorId)) {
				$objAutor = new clsPmieducarAcervoAcervoAutor();
				$objAutor->cadastraAutorParaObra($cod_acervo, $autorId);
			}
		}
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
if($j('#ref_cod_tipo_autor').val() == 2 || $j('#ref_cod_tipo_autor').val() == 3){

$j('#tipo_autor').show();
$j('#autores').closest('tr').hide();
$j('#autores').val("");

}else if($j('#ref_cod_tipo_autor').val() == 1){

$j('#tipo_autor').hide();
$j('#tipo_autor').val("");
$j('#autores').closest('tr').show();
	
}else{
$j('#tipo_autor').hide();
$j('#tipo_autor').val("");
$j('#autores').closest('tr').hide();
$j('#autores').val("");
}
$j('#ref_cod_tipo_autor').click(abriCampo);




function abriCampo(){
if($j('#ref_cod_tipo_autor').val() == 2 || $j('#ref_cod_tipo_autor').val() == 3){

$j('#tipo_autor').show();
$j('#autores').closest('tr').hide();
$j('#autores').val("");

}else if($j('#ref_cod_tipo_autor').val() == 1){

$j('#tipo_autor').hide();
$j('#tipo_autor').val("");
$j('#autores').closest('tr').show();
	
}else{
$j('#tipo_autor').hide();
$j('#tipo_autor').val("");
$j('#autores').closest('tr').hide();
$j('#autores').val("");
}
}






document.getElementById('ref_cod_acervo_colecao').disabled = true;
document.getElementById('ref_cod_acervo_colecao').options[0].text = 'Selecione uma biblioteca';

document.getElementById('ref_cod_acervo_editora').disabled = true;
document.getElementById('ref_cod_acervo_editora').options[0].text = 'Selecione uma biblioteca';

document.getElementById('ref_cod_acervo_idioma').disabled = true;
document.getElementById('ref_cod_acervo_idioma').options[0].text = 'Selecione uma biblioteca';

var tempExemplarTipo;
var tempColecao;
var tempIdioma;
var tempEditora;

if(document.getElementById('ref_cod_biblioteca').value == "")
{
	setVisibility(document.getElementById('img_colecao'), false);
	setVisibility(document.getElementById('img_editora'), false);
	setVisibility(document.getElementById('img_idioma'), false);
	//tempExemplarTipo = null;
	tempColecao = null;
	tempIdioma = null;
	tempEditora = null;
}
else
{
	ajaxBiblioteca('novo');
}

function getColecao( xml_acervo_colecao )
{
	var campoColecao = document.getElementById('ref_cod_acervo_colecao');
	var DOM_array = xml_acervo_colecao.getElementsByTagName( "acervo_colecao" );

	if(DOM_array.length)
	{
		campoColecao.length = 1;
		campoColecao.options[0].text = 'Selecione uma cole��o';
		campoColecao.disabled = false;

		for( var i=0; i<DOM_array.length; i++)
		{
			campoColecao.options[campoColecao.options.length] = new Option(DOM_array[i].firstChild.data, DOM_array[i].getAttribute("cod_colecao"), false, false);
		}
		setVisibility(document.getElementById('img_colecao'), true);
		if(tempColecao != null)
			campoColecao.value = tempColecao;
	}
	else
	{
		if(document.getElementById('ref_cod_biblioteca').value == "")
		{
			campoColecao.options[0].text = 'Selecione uma biblioteca';
			setVisibility(document.getElementById('img_colecao'), false);
		}
		else
		{
			campoColecao.options[0].text = 'A biblioteca n�o possui cole��es';
			setVisibility(document.getElementById('img_colecao'), true);
		}
	}
}

function getEditora( xml_acervo_editora )
{
	var campoEditora = document.getElementById('ref_cod_acervo_editora');
	var DOM_array = xml_acervo_editora.getElementsByTagName( "acervo_editora" );

	if(DOM_array.length)
	{
		campoEditora.length = 1;
		campoEditora.options[0].text = 'Selecione uma editora';
		campoEditora.disabled = false;

		for( var i=0; i<DOM_array.length; i++)
		{
			campoEditora.options[campoEditora.options.length] = new Option(DOM_array[i].firstChild.data, DOM_array[i].getAttribute("cod_editora"), false, false);
		}
		setVisibility(document.getElementById('img_editora'), true);
		if(tempEditora != null)
			campoEditora.value = tempEditora;
	}
	else
	{
		if(document.getElementById('ref_cod_biblioteca').value == "")
		{
			campoEditora.options[0].text = 'Selecione uma biblioteca';
			setVisibility(document.getElementById('img_editora'), false);
		}
		else
		{
			campoEditora.options[0].text = 'A biblioteca n�o possui editoras';
			setVisibility(document.getElementById('img_editora'), true);
		}
	}
}

function getIdioma( xml_acervo_idioma )
{
	var campoIdioma = document.getElementById('ref_cod_acervo_idioma');
	var DOM_array = xml_acervo_idioma.getElementsByTagName( "acervo_idioma" );

	if(DOM_array.length)
	{
		campoIdioma.length = 1;
		campoIdioma.options[0].text = 'Selecione uma idioma';
		campoIdioma.disabled = false;

		for( var i=0; i<DOM_array.length; i++)
		{
			campoIdioma.options[campoIdioma.options.length] = new Option(DOM_array[i].firstChild.data, DOM_array[i].getAttribute("cod_idioma"), false, false);
		}
		setVisibility(document.getElementById('img_idioma'), true);
		if(tempIdioma != null)
			campoIdioma.value = tempIdioma;
	}
	else
	{
		if(document.getElementById('ref_cod_biblioteca').value == "")
		{
			campoIdioma.options[0].text = 'Selecione uma biblioteca';
			setVisibility(document.getElementById('img_idioma'), false);
		}
		else
		{
			campoIdioma.options[0].text = 'A biblioteca n�o possui idiomas';
			setVisibility(document.getElementById('img_idioma'), true);
		}
	}
}

document.getElementById('ref_cod_biblioteca').onchange = function()
{
	ajaxBiblioteca();
};

function ajaxBiblioteca(acao)
{
	var campoBiblioteca = document.getElementById('ref_cod_biblioteca').value;

	var campoExemplarTipo = document.getElementById('ref_cod_exemplar_tipo');

	var campoColecao = document.getElementById('ref_cod_acervo_colecao');
	if(acao == 'novo')
	{
		tempColecao = campoColecao.value;
	}
	campoColecao.length = 1;
	campoColecao.disabled = true;
	campoColecao.options[0].text = 'Carregando cole��es';

	var xml_colecao = new ajax( getColecao );
	xml_colecao.envia( "educar_colecao_xml.php?bib="+campoBiblioteca );

	var campoEditora = document.getElementById('ref_cod_acervo_editora');
	if(acao == 'novo')
	{
		tempEditora = campoEditora.value;
	}
	campoEditora.length = 1;
	campoEditora.disabled = true;
	campoEditora.options[0].text = 'Carregando editoras';

	var xml_editora = new ajax( getEditora );
	xml_editora.envia( "educar_editora_xml.php?bib="+campoBiblioteca );

	var campoIdioma = document.getElementById('ref_cod_acervo_idioma');
	if(acao == 'novo')
	{
		tempIdioma = campoIdioma.value;
	}
	campoIdioma.length = 1;
	campoIdioma.disabled = true;
	campoIdioma.options[0].text = 'Carregando idiomas';

	var xml_idioma = new ajax( getIdioma );
	xml_idioma.envia( "educar_idioma_xml.php?bib="+campoBiblioteca );

}

function pesquisa()
{
	var biblioteca = document.getElementById('ref_cod_biblioteca').value;
	if(!biblioteca)
	{
		alert('Por favor,\nselecione uma biblioteca!');
		return;
	}
	pesquisa_valores_popless('educar_pesquisa_acervo_lst.php?campo1=ref_cod_acervo&ref_cod_biblioteca=' + biblioteca , 'ref_cod_acervo')
}


function fixupPrincipalCheckboxes() {
  $j('#principal').hide();

  var $checkboxes = $j("input[type='checkbox']").filter("input[id^='principal_']");

  $checkboxes.change(function(){
    $checkboxes.not(this).removeAttr('checked');
  });
}

fixupPrincipalCheckboxes();

function fixupAssuntosSize(){

	$j('#assuntos_chzn ul').css('width', '307px');	
	
}

fixupAssuntosSize();

function fixupAutoresSize(){

	$j('#autores_chzn ul').css('width', '307px');	
	
}

fixupAutoresSize();

$assuntos = $j('#assuntos');

$assuntos.trigger('chosen:updated');

var handleGetAssuntos = function(dataResponse) {

  $j.each(dataResponse['assuntos'], function(id, value) {
  	
    $assuntos.children("[value=" + value + "]").attr('selected', '');
  });

  $assuntos.trigger('chosen:updated');
}

var getAssuntos = function() {
	    
  var $cod_acervo = $j('#cod_acervo').val();
  
  if ($j('#cod_acervo').val()!='') {    

    var additionalVars = {
      id : $j('#cod_acervo').val(),
    };

    var options = {
      url      : getResourceUrlBuilder.buildUrl('/module/Api/assunto', 'assunto', additionalVars),
      dataType : 'json',
      data     : {},
      success  : handleGetAssuntos,
    };

    getResource(options);
  }
}

getAssuntos();

$autores = $j('#autores');

$autores.trigger('chosen:updated');
var testezin;

var handleGetAutores = function(dataResponse) {
  testezin = dataResponse['autores'];
  
  $j.each(dataResponse['autores'], function(id, value) {
  	
    $autores.children("[value=" + value + "]").attr('selected', '');
  });

  $autores.trigger('chosen:updated');
}

var getAutores = function() {
	    
  var $cod_acervo = $j('#cod_acervo').val();
  
  if ($j('#cod_acervo').val()!='') {    

    var additionalVars = {
      id : $j('#cod_acervo').val(),
    };

    var options = {
      url      : getResourceUrlBuilder.buildUrl('/module/Api/autor', 'autor', additionalVars),
      dataType : 'json',
      data     : {},
      success  : handleGetAutores,
    };

    getResource(options);
  }
}

getAutores();
// Para parecer como campo obrigat�rio, j� que o required => true n�o est� funcionando corretamente

</script>