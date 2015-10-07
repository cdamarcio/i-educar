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
require_once ("include/clsDetalhe.inc.php");
require_once ("include/clsBanco.inc.php");
require_once( "include/public/geral.inc.php" );

class clsIndexBase extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Uf" );
		$this->processoAp = "754";
		$this->addEstilo('localizacaoSistema');
	}
}

class indice extends clsDetalhe
{
	/**
	 * Titulo no topo da pagina
	 *
	 * @var int
	 */
	var $titulo;

	var $sigla_uf;
	var $nome;
	var $geom;
	var $idpais;

	function Gerar()
	{
		@session_start();
		$this->pessoa_logada = $_SESSION['id_pessoa'];
		session_write_close();

		$this->titulo = "Uf - Detalhe";
		

		$this->sigla_uf=$_GET["sigla_uf"];

		$tmp_obj = new clsPublicUf( $this->sigla_uf );
		$registro = $tmp_obj->detalhe();

		if( ! $registro )
		{
			header( "location: public_uf_lst.php" );
			die();
		}

		if( class_exists( "clsPais" ) )
		{
			$obj_idpais = new clsPais( $registro["idpais"] );
			$det_idpais = $obj_idpais->detalhe();
			$registro["idpais"] = $det_idpais["nome"];
		}
		else
		{
			$registro["idpais"] = "Erro na geracao";
			echo "<!--\nErro\nClasse nao existente: clsPais\n-->";
		}


		if( $registro["sigla_uf"] )
		{
			$this->addDetalhe( array( "Sigla Uf", "{$registro["sigla_uf"]}") );
		}
		if( $registro["nome"] )
		{
			$this->addDetalhe( array( "Nome", "{$registro["nome"]}") );
		}
		if( $registro["geom"] )
		{
			$this->addDetalhe( array( "Geom", "{$registro["geom"]}") );
		}
		if( $registro["idpais"] )
		{
			$this->addDetalhe( array( "Pais", "{$registro["idpais"]}") );
		}
		if( $registro["cod_ibge"] )
		{
			$this->addDetalhe( array( "C&oacute;digo INEP", "{$registro["cod_ibge"]}") );
		}

		$obj_permissao = new clsPermissoes();

		if($obj_permissao->permissao_cadastra(754, $this->pessoa_logada,7,null,true))
	    {
	        $this->url_novo = "public_uf_cad.php";
			$this->url_editar = "public_uf_cad.php?sigla_uf={$registro["sigla_uf"]}";
	    }		

		$this->url_cancelar = "public_uf_lst.php";
		$this->largura = "100%";

	    $localizacao = new LocalizacaoSistema();
	    $localizacao->entradaCaminhos( array(
	         $_SERVER['SERVER_NAME']."/intranet" => "In&iacute;cio",
	         ""                                  => "Detalhe da UF"
	    ));
	    $this->enviaLocalizacao($localizacao->montar());		
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