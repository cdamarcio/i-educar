<?php
/**
 * i-Educar - Sistema de gestão escolar
 *
 * Copyright (C) 2006  Prefeitura Municipal de Itajaí
 *                     <ctima@itajai.sc.gov.br>
 *
 * Este programa é software livre; você pode redistribuí-lo e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU conforme publicada pela Free
 * Software Foundation; tanto a versão 2 da Licença, como (a seu critério)
 * qualquer versão posterior.
 *
 * Este programa é distribuí­do na expectativa de que seja útil, porém, SEM
 * NENHUMA GARANTIA; nem mesmo a garantia implí­cita de COMERCIABILIDADE OU
 * ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA. Consulte a Licença Pública Geral
 * do GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Pública Geral do GNU junto
 * com este programa; se não, escreva para a Free Software Foundation, Inc., no
 * endereço 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
 *
 * @author    Prefeitura Municipal de Itajaí <ctima@itajai.sc.gov.br>
 * @category  i-Educar
 * @license   @@license@@
 * @package   iEd_Pmieducar
 * @since     Arquivo disponível desde a versão 1.0.0
 * @version   $Id$
 */
require_once 'include/pmieducar/geral.inc.php';
/**
 * clsPmieducarServidor class.
 *
 * @author    Prefeitura Municipal de Itajaí <ctima@itajai.sc.gov.br>
 * @category  i-Educar
 * @license   @@license@@
 * @package   iEd_Pmieducar
 * @since     Classe disponível desde a versão 1.0.0
 * @version   @@package_version@@
 */
class clsPmieducarServidor
{
  var $cod_servidor;
  var $ref_idesco;
  var $carga_horaria;
  var $data_cadastro;
  var $data_exclusao;
  var $ativo;
  var $ref_cod_instituicao;
  var $ref_cod_subnivel;
  var $situacao_curso_superior_1;
  var $formacao_complementacao_pedagogica_1;
  var $codigo_curso_superior_1;
  var $ano_inicio_curso_superior_1;
  var $ano_conclusao_curso_superior_1;
  var $tipo_instituicao_curso_superior_1;
  var $instituicao_curso_superior_1;
  var $situacao_curso_superior_2;
  var $formacao_complementacao_pedagogica_2;
  var $codigo_curso_superior_2;
  var $ano_inicio_curso_superior_2;
  var $ano_conclusao_curso_superior_2;
  var $tipo_instituicao_curso_superior_2;
  var $instituicao_curso_superior_2;
  var $situacao_curso_superior_3;
  var $formacao_complementacao_pedagogica_3;
  var $codigo_curso_superior_3;
  var $ano_inicio_curso_superior_3;
  var $ano_conclusao_curso_superior_3;
  var $tipo_instituicao_curso_superior_3;
  var $instituicao_curso_superior_3;
  var $pos_especializacao;
  var $pos_mestrado;
  var $pos_doutorado;
  var $pos_nenhuma;
  var $curso_creche;
  var $curso_pre_escola;
  var $curso_anos_iniciais;
  var $curso_anos_finais;
  var $curso_ensino_medio;
  var $curso_eja;
  var $curso_educacao_especial;
  var $curso_educacao_indigena;
  var $curso_educacao_campo;
  var $curso_educacao_ambiental;
  var $curso_educacao_direitos_humanos;
  var $curso_genero_diversidade_sexual;
  var $curso_direito_crianca_adolescente;
  var $curso_relacoes_etnicorraciais;
  var $curso_outros;
  var $curso_nenhum;
  var $multi_seriado;
    /**
   * Armazena o total de resultados obtidos na última chamada ao método lista().
   * @var int
   */
  var $_total;
  /**
   * Nome do schema.
   * @var string
   */
  var $_schema;
  /**
   * Nome da tabela.
   * @var string
   */
  var $_tabela;
  /**
   * Lista separada por vírgula, com os campos que devem ser selecionados na
   * próxima chamado ao método lista().
   * @var string
   */
  var $_campos_lista;
  var $_campos_lista2;
  /**
   * Lista com todos os campos da tabela separados por vírgula, padrão para
   * seleção no método lista.
   * @var string
   */
  var $_todos_campos;
  var $_todos_campos2;
  /**
   * Valor que define a quantidade de registros a ser retornada pelo método lista().
   * @var int
   */
  var $_limite_quantidade;
  /**
   * Define o valor de offset no retorno dos registros no método lista().
   * @var int
   */
  var $_limite_offset;
  /**
   * Define o campo para ser usado como padrão de ordenação no método lista().
   * @var string
   */
  var $_campo_order_by;
  /**
   * Construtor.
   */
  function clsPmieducarServidor(
    $cod_servidor = NULL,
    $ref_cod_deficiencia = NULL,
    $ref_idesco = NULL,
    $carga_horaria = NULL,
    $data_cadastro = NULL,
    $data_exclusao = NULL,
    $ativo = NULL,
    $ref_cod_instituicao = NULL,
    $ref_cod_subnivel = NULL)
  {
    $db = new clsBanco();
    $this->_schema = 'pmieducar.';
    $this->_tabela = $this->_schema . 'servidor';
    $this->_campos_lista = $this->_todos_campos = "cod_servidor, ref_idesco, carga_horaria, data_cadastro, data_exclusao, ativo, ref_cod_instituicao,ref_cod_subnivel,
    situacao_curso_superior_1, formacao_complementacao_pedagogica_1, codigo_curso_superior_1, ano_inicio_curso_superior_1, ano_conclusao_curso_superior_1, tipo_instituicao_curso_superior_1, instituicao_curso_superior_1,
    situacao_curso_superior_2, formacao_complementacao_pedagogica_2, codigo_curso_superior_2, ano_inicio_curso_superior_2, ano_conclusao_curso_superior_2, tipo_instituicao_curso_superior_2, instituicao_curso_superior_2,
    situacao_curso_superior_3, formacao_complementacao_pedagogica_3, codigo_curso_superior_3, ano_inicio_curso_superior_3, ano_conclusao_curso_superior_3, tipo_instituicao_curso_superior_3, instituicao_curso_superior_3,
    pos_especializacao, pos_mestrado, pos_doutorado, pos_nenhuma, curso_creche, curso_pre_escola, curso_anos_iniciais, curso_anos_finais, curso_ensino_medio, curso_eja, curso_educacao_especial, curso_educacao_indigena,
    curso_educacao_campo, curso_educacao_ambiental, curso_educacao_campo, curso_educacao_direitos_humanos, curso_genero_diversidade_sexual, curso_direito_crianca_adolescente, curso_relacoes_etnicorraciais, curso_outros, curso_nenhum,
    multi_seriado
    ";
    $this->_campos_lista2 = $this->_todos_campos2 = "s.cod_servidor, s.ref_idesco, s.carga_horaria, s.data_cadastro, s.data_exclusao, s.ativo, s.ref_cod_instituicao,s.ref_cod_subnivel,
    s.situacao_curso_superior_1, s.formacao_complementacao_pedagogica_1, s.codigo_curso_superior_1, s.ano_inicio_curso_superior_1, s.ano_conclusao_curso_superior_1, s.tipo_instituicao_curso_superior_1, s.instituicao_curso_superior_1,
    s.situacao_curso_superior_2, s.formacao_complementacao_pedagogica_2, s.codigo_curso_superior_2, s.ano_inicio_curso_superior_2, s.ano_conclusao_curso_superior_2, s.tipo_instituicao_curso_superior_2, s.instituicao_curso_superior_2,
    s.situacao_curso_superior_3, s.formacao_complementacao_pedagogica_3, s.codigo_curso_superior_3, s.ano_inicio_curso_superior_3, s.ano_conclusao_curso_superior_3, s.tipo_instituicao_curso_superior_3, s.instituicao_curso_superior_3,
    s.pos_especializacao, s.pos_mestrado, s.pos_doutorado, s.pos_nenhuma, s.curso_creche, s.curso_pre_escola, s.curso_anos_iniciais, s.curso_anos_finais, s.curso_ensino_medio, s.curso_eja, s.curso_educacao_especial, s.curso_educacao_indigena,
    s.curso_educacao_campo, s.curso_educacao_ambiental, s.curso_educacao_campo, s.curso_educacao_direitos_humanos, s.curso_genero_diversidade_sexual, s.curso_direito_crianca_adolescente, s.curso_relacoes_etnicorraciais, s.curso_outros, s.curso_nenhum,
    s.multi_seriado
    ";
    if (is_numeric( $ref_idesco)) {
      if (class_exists('clsCadastroEscolaridade')) {
        $tmp_obj = new clsCadastroEscolaridade( $ref_idesco);
        if (method_exists($tmp_obj, 'existe')) {
          if ($tmp_obj->existe()) {
            $this->ref_idesco = $ref_idesco;
          }
        }
        elseif (method_exists($tmp_obj, 'detalhe')) {
          if ($tmp_obj->detalhe()) {
            $this->ref_idesco = $ref_idesco;
          }
        }
      }
      elseif ($db->CampoUnico("SELECT 1 FROM cadastro.escolaridade WHERE idesco = '{$ref_idesco}'")) {
          $this->ref_idesco = $ref_idesco;
      }
    }
    /**
     * Filtrar cod_servidor
     */
    if (is_numeric($cod_servidor)) {
      if (class_exists('clsFuncionario')) {
        $tmp_obj = new clsFuncionario( $cod_servidor);
        if (method_exists($tmp_obj, 'existe')) {
          if ($tmp_obj->existe()) {
            $this->cod_servidor = $cod_servidor;
          }
        }
        elseif (method_exists( $tmp_obj, 'detalhe')) {
          // if ($tmp_obj->detalhe()) {
            $this->cod_servidor = $cod_servidor;
          // }
        }
      }
      elseif ($db->CampoUnico("SELECT 1 FROM funcionario WHERE ref_cod_pessoa_fj = '{$cod_servidor}'")) {
        $this->cod_servidor = $cod_servidor;
      }
    }
    if (is_numeric($carga_horaria)) {
      $this->carga_horaria = $carga_horaria;
    }
    if (is_string($data_cadastro)) {
      $this->data_cadastro = $data_cadastro;
    }
    if (is_string($data_exclusao)) {
      $this->data_exclusao = $data_exclusao;
    }
    if (is_numeric($ativo)) {
      $this->ativo = $ativo;
    }
    if (is_numeric($ref_cod_instituicao)) {
      if (class_exists('clsPmieducarInstituicao')) {
        $tmp_obj = new clsPmieducarInstituicao($ref_cod_instituicao);
        if (method_exists($tmp_obj, 'existe')) {
          if ($tmp_obj->existe()) {
            $this->ref_cod_instituicao = $ref_cod_instituicao;
          }
        }
        elseif (method_exists($tmp_obj, 'detalhe')) {
          if ($tmp_obj->detalhe()) {
            $this->ref_cod_instituicao = $ref_cod_instituicao;
          }
        }
      }
      elseif ($db->CampoUnico("SELECT 1 FROM pmieducar.instituicao WHERE cod_instituicao = '{$ref_cod_instituicao}'")) {
        $this->ref_cod_instituicao = $ref_cod_instituicao;
      }
    }
    if (is_numeric( $ref_cod_subnivel)) {
      if (class_exists( "clsPmieducarSubnivel")) {
        $tmp_obj = new clsPmieducarSubnivel($ref_cod_subnivel);
        if (method_exists($tmp_obj, 'existe')) {
          if ($tmp_obj->existe()) {
            $this->ref_cod_subnivel = $ref_cod_subnivel;
          }
        }
        else if (method_exists($tmp_obj, 'detalhe')) {
          if ($tmp_obj->detalhe()) {
            $this->ref_cod_subnivel = $ref_cod_subnivel;
          }
        }
      }
      elseif ($db->CampoUnico("SELECT 1 FROM pmieducar.subnivel WHERE cod_subnivel = '{$ref_cod_subnivel}'")) {
        $this->ref_cod_subnivel = $ref_cod_subnivel;
      }
    }
  }
  /**
   * Cria um novo registro.
   * @return bool
   */
  function cadastra()
  {
    if (is_numeric($this->cod_servidor) && is_numeric($this->carga_horaria) &&
      is_numeric($this->ref_cod_instituicao)
    ) {
      $db = new clsBanco();
      $campos = "";
      $valores = "";
      $gruda = "";
      if (is_numeric( $this->cod_servidor)) {
        $campos .= "{$gruda}cod_servidor";
        $valores .= "{$gruda}'{$this->cod_servidor}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->carga_horaria)) {
        $campos .= "{$gruda}carga_horaria";
        $valores .= "{$gruda}'{$this->carga_horaria}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->ref_cod_subnivel)) {
        $campos .= "{$gruda}ref_cod_subnivel";
        $valores .= "{$gruda}'{$this->ref_cod_subnivel}'";
        $gruda = ", ";
      }
      $campos .= "{$gruda}data_cadastro";
      $valores .= "{$gruda}NOW()";
      $gruda = ", ";
      $campos .= "{$gruda}ativo";
      $valores .= "{$gruda}'1'";
      $gruda = ", ";
      if (is_numeric( $this->ref_cod_instituicao)) {
        $campos .= "{$gruda}ref_cod_instituicao";
        $valores .= "{$gruda}'{$this->ref_cod_instituicao}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->situacao_curso_superior_1)) {
        $campos .= "{$gruda}situacao_curso_superior_1";
        $valores .= "{$gruda}'{$this->situacao_curso_superior_1}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->formacao_complementacao_pedagogica_1)) {
        $campos .= "{$gruda}formacao_complementacao_pedagogica_1";
        $valores .= "{$gruda}'{$this->formacao_complementacao_pedagogica_1}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->codigo_curso_superior_1)) {
        $campos .= "{$gruda}codigo_curso_superior_1";
        $valores .= "{$gruda}'{$this->codigo_curso_superior_1}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->ano_inicio_curso_superior_1)) {
        $campos .= "{$gruda}ano_inicio_curso_superior_1";
        $valores .= "{$gruda}'{$this->ano_inicio_curso_superior_1}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->ano_conclusao_curso_superior_1)) {
        $campos .= "{$gruda}ano_conclusao_curso_superior_1";
        $valores .= "{$gruda}'{$this->ano_conclusao_curso_superior_1}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->tipo_instituicao_curso_superior_1)) {
        $campos .= "{$gruda}tipo_instituicao_curso_superior_1";
        $valores .= "{$gruda}'{$this->tipo_instituicao_curso_superior_1}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->instituicao_curso_superior_1)) {
        $campos .= "{$gruda}instituicao_curso_superior_1";
        $valores .= "{$gruda}'{$this->instituicao_curso_superior_1}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->situacao_curso_superior_2)) {
        $campos .= "{$gruda}situacao_curso_superior_2";
        $valores .= "{$gruda}'{$this->situacao_curso_superior_2}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->formacao_complementacao_pedagogica_2)) {
        $campos .= "{$gruda}formacao_complementacao_pedagogica_2";
        $valores .= "{$gruda}'{$this->formacao_complementacao_pedagogica_2}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->codigo_curso_superior_2)) {
        $campos .= "{$gruda}codigo_curso_superior_2";
        $valores .= "{$gruda}'{$this->codigo_curso_superior_2}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->ano_inicio_curso_superior_2)) {
        $campos .= "{$gruda}ano_inicio_curso_superior_2";
        $valores .= "{$gruda}'{$this->ano_inicio_curso_superior_2}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->ano_conclusao_curso_superior_2)) {
        $campos .= "{$gruda}ano_conclusao_curso_superior_2";
        $valores .= "{$gruda}'{$this->ano_conclusao_curso_superior_2}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->tipo_instituicao_curso_superior_2)) {
        $campos .= "{$gruda}tipo_instituicao_curso_superior_2";
        $valores .= "{$gruda}'{$this->tipo_instituicao_curso_superior_2}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->instituicao_curso_superior_2)) {
        $campos .= "{$gruda}instituicao_curso_superior_2";
        $valores .= "{$gruda}'{$this->instituicao_curso_superior_2}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->situacao_curso_superior_3)) {
        $campos .= "{$gruda}situacao_curso_superior_3";
        $valores .= "{$gruda}'{$this->situacao_curso_superior_3}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->formacao_complementacao_pedagogica_3)) {
        $campos .= "{$gruda}formacao_complementacao_pedagogica_3";
        $valores .= "{$gruda}'{$this->formacao_complementacao_pedagogica_3}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->codigo_curso_superior_3)) {
        $campos .= "{$gruda}codigo_curso_superior_3";
        $valores .= "{$gruda}'{$this->codigo_curso_superior_3}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->ano_inicio_curso_superior_3)) {
        $campos .= "{$gruda}ano_inicio_curso_superior_3";
        $valores .= "{$gruda}'{$this->ano_inicio_curso_superior_3}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->ano_conclusao_curso_superior_3)) {
        $campos .= "{$gruda}ano_conclusao_curso_superior_3";
        $valores .= "{$gruda}'{$this->ano_conclusao_curso_superior_3}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->tipo_instituicao_curso_superior_3)) {
        $campos .= "{$gruda}tipo_instituicao_curso_superior_3";
        $valores .= "{$gruda}'{$this->tipo_instituicao_curso_superior_3}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->instituicao_curso_superior_3)) {
        $campos .= "{$gruda}instituicao_curso_superior_3";
        $valores .= "{$gruda}'{$this->instituicao_curso_superior_3}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->pos_especializacao)) {
        $campos .= "{$gruda}pos_especializacao";
        $valores .= "{$gruda}'{$this->pos_especializacao}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->pos_mestrado)) {
        $campos .= "{$gruda}pos_mestrado";
        $valores .= "{$gruda}'{$this->pos_mestrado}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->pos_doutorado)) {
        $campos .= "{$gruda}pos_doutorado";
        $valores .= "{$gruda}'{$this->pos_doutorado}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->pos_nenhuma)) {
        $campos .= "{$gruda}pos_nenhuma";
        $valores .= "{$gruda}'{$this->pos_nenhuma}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->curso_creche)) {
        $campos .= "{$gruda}curso_creche";
        $valores .= "{$gruda}'{$this->curso_creche}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->curso_pre_escola)) {
        $campos .= "{$gruda}curso_pre_escola";
        $valores .= "{$gruda}'{$this->curso_pre_escola}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->curso_anos_iniciais)) {
        $campos .= "{$gruda}curso_anos_iniciais";
        $valores .= "{$gruda}'{$this->curso_anos_iniciais}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->curso_anos_finais)) {
        $campos .= "{$gruda}curso_anos_finais";
        $valores .= "{$gruda}'{$this->curso_anos_finais}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->curso_ensino_medio)) {
        $campos .= "{$gruda}curso_ensino_medio";
        $valores .= "{$gruda}'{$this->curso_ensino_medio}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->curso_eja)) {
        $campos .= "{$gruda}curso_eja";
        $valores .= "{$gruda}'{$this->curso_eja}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->curso_educacao_especial)) {
        $campos .= "{$gruda}curso_educacao_especial";
        $valores .= "{$gruda}'{$this->curso_educacao_especial}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->curso_educacao_indigena)) {
        $campos .= "{$gruda}curso_educacao_indigena";
        $valores .= "{$gruda}'{$this->curso_educacao_indigena}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->curso_educacao_campo)) {
        $campos .= "{$gruda}curso_educacao_campo";
        $valores .= "{$gruda}'{$this->curso_educacao_campo}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->curso_educacao_ambiental)) {
        $campos .= "{$gruda}curso_educacao_ambiental";
        $valores .= "{$gruda}'{$this->curso_educacao_ambiental}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->curso_educacao_direitos_humanos)) {
        $campos .= "{$gruda}curso_educacao_direitos_humanos";
        $valores .= "{$gruda}'{$this->curso_educacao_direitos_humanos}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->curso_genero_diversidade_sexual)) {
        $campos .= "{$gruda}curso_genero_diversidade_sexual";
        $valores .= "{$gruda}'{$this->curso_genero_diversidade_sexual}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->curso_direito_crianca_adolescente)) {
        $campos .= "{$gruda}curso_direito_crianca_adolescente";
        $valores .= "{$gruda}'{$this->curso_direito_crianca_adolescente}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->curso_relacoes_etnicorraciais)) {
        $campos .= "{$gruda}curso_relacoes_etnicorraciais";
        $valores .= "{$gruda}'{$this->curso_relacoes_etnicorraciais}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->curso_outros)) {
        $campos .= "{$gruda}curso_outros";
        $valores .= "{$gruda}'{$this->curso_outros}'";
        $gruda = ", ";
      }
      if (is_numeric( $this->curso_nenhum)) {
        $campos .= "{$gruda}curso_nenhum";
        $valores .= "{$gruda}'{$this->curso_nenhum}'";
        $gruda = ", ";
      }
      if (dbBool( $this->multi_seriado)) {
        $campos .= "{$gruda}multi_seriado";
        $valores .= "{$gruda} TRUE ";
        $gruda = ", ";
      }else{
        $campos .= "{$gruda}multi_seriado";
        $valores .= "{$gruda} FALSE ";
        $gruda = ", ";
      }
      $db->Consulta("INSERT INTO {$this->_tabela} ($campos) VALUES ($valores)");
      return $this->cod_servidor;
    }
    return false;
  }
  /**
   * Edita os dados de um registro.
   * @return bool
   */
  function edita()
  {
    if (is_numeric($this->cod_servidor) && is_numeric($this->ref_cod_instituicao)) {
      $db = new clsBanco();
      $set = "";
      if (is_numeric($this->ref_idesco)) {
        $set .= "{$gruda}ref_idesco = '{$this->ref_idesco}'";
        $gruda = ", ";
      }
      if (is_numeric($this->carga_horaria)) {
        $set .= "{$gruda}carga_horaria = '{$this->carga_horaria}'";
        $gruda = ", ";
      }
      if (is_string($this->data_cadastro)) {
        $set .= "{$gruda}data_cadastro = '{$this->data_cadastro}'";
        $gruda = ", ";
      }
      $set .= "{$gruda}data_exclusao = NOW()";
      $gruda = ", ";
      if (is_numeric($this->ativo)) {
        $set .= "{$gruda}ativo = '{$this->ativo}'";
        $gruda = ", ";
      }
      if (is_numeric($this->ref_cod_subnivel)) {
        $set .= "{$gruda}ref_cod_subnivel = '{$this->ref_cod_subnivel}'";
        $gruda = ", ";
      }
      if (is_numeric($this->situacao_curso_superior_1)) {
        $set .= "{$gruda}situacao_curso_superior_1 = '{$this->situacao_curso_superior_1}'";
        $gruda = ", ";
      }
      if (is_numeric($this->formacao_complementacao_pedagogica_1)) {
        $set .= "{$gruda}formacao_complementacao_pedagogica_1 = '{$this->formacao_complementacao_pedagogica_1}'";
        $gruda = ", ";
      }
      if (is_numeric($this->codigo_curso_superior_1)) {
        $set .= "{$gruda}codigo_curso_superior_1 = '{$this->codigo_curso_superior_1}'";
        $gruda = ", ";
      }
      if (is_numeric($this->ano_inicio_curso_superior_1)) {
        $set .= "{$gruda}ano_inicio_curso_superior_1 = '{$this->ano_inicio_curso_superior_1}'";
        $gruda = ", ";
      }else{
        $set .= "{$gruda}ano_inicio_curso_superior_1 = NULL";
        $gruda = ", ";
      }
      if (is_numeric($this->ano_conclusao_curso_superior_1)) {
        $set .= "{$gruda}ano_conclusao_curso_superior_1 = '{$this->ano_conclusao_curso_superior_1}'";
        $gruda = ", ";
      }else{
        $set .= "{$gruda}ano_conclusao_curso_superior_1 = NULL";
        $gruda = ", ";
      }
      if (is_numeric($this->tipo_instituicao_curso_superior_1)) {
        $set .= "{$gruda}tipo_instituicao_curso_superior_1 = '{$this->tipo_instituicao_curso_superior_1}'";
        $gruda = ", ";
      }
      if (is_numeric($this->instituicao_curso_superior_1)) {
        $set .= "{$gruda}instituicao_curso_superior_1 = '{$this->instituicao_curso_superior_1}'";
        $gruda = ", ";
      }
      if (is_numeric($this->situacao_curso_superior_2)) {
        $set .= "{$gruda}situacao_curso_superior_2 = '{$this->situacao_curso_superior_2}'";
        $gruda = ", ";
      }
      if (is_numeric($this->formacao_complementacao_pedagogica_2)) {
        $set .= "{$gruda}formacao_complementacao_pedagogica_2 = '{$this->formacao_complementacao_pedagogica_2}'";
        $gruda = ", ";
      }
      if (is_numeric($this->codigo_curso_superior_2)) {
        $set .= "{$gruda}codigo_curso_superior_2 = '{$this->codigo_curso_superior_2}'";
        $gruda = ", ";
      }
      if (is_numeric($this->ano_inicio_curso_superior_2)) {
        $set .= "{$gruda}ano_inicio_curso_superior_2 = '{$this->ano_inicio_curso_superior_2}'";
        $gruda = ", ";
      }else{
        $set .= "{$gruda}ano_inicio_curso_superior_2 = NULL";
        $gruda = ", ";
      }
      if (is_numeric($this->ano_conclusao_curso_superior_2)) {
        $set .= "{$gruda}ano_conclusao_curso_superior_2 = '{$this->ano_conclusao_curso_superior_2}'";
        $gruda = ", ";
      }else{
        $set .= "{$gruda}ano_conclusao_curso_superior_2 = NULL";
        $gruda = ", ";
      }
      if (is_numeric($this->tipo_instituicao_curso_superior_2)) {
        $set .= "{$gruda}tipo_instituicao_curso_superior_2 = '{$this->tipo_instituicao_curso_superior_2}'";
        $gruda = ", ";
      }
      if (is_numeric($this->instituicao_curso_superior_2)) {
        $set .= "{$gruda}instituicao_curso_superior_2 = '{$this->instituicao_curso_superior_2}'";
        $gruda = ", ";
      }
      if (is_numeric($this->situacao_curso_superior_3)) {
        $set .= "{$gruda}situacao_curso_superior_3 = '{$this->situacao_curso_superior_3}'";
        $gruda = ", ";
      }
      if (is_numeric($this->formacao_complementacao_pedagogica_3)) {
        $set .= "{$gruda}formacao_complementacao_pedagogica_3 = '{$this->formacao_complementacao_pedagogica_3}'";
        $gruda = ", ";
      }
      if (is_numeric($this->codigo_curso_superior_3)) {
        $set .= "{$gruda}codigo_curso_superior_3 = '{$this->codigo_curso_superior_3}'";
        $gruda = ", ";
      }
      if (is_numeric($this->ano_inicio_curso_superior_3)) {
        $set .= "{$gruda}ano_inicio_curso_superior_3 = '{$this->ano_inicio_curso_superior_3}'";
        $gruda = ", ";
      }else{
        $set .= "{$gruda}ano_inicio_curso_superior_3 = NULL";
        $gruda = ", ";
      }
      if (is_numeric($this->ano_conclusao_curso_superior_3)) {
        $set .= "{$gruda}ano_conclusao_curso_superior_3 = '{$this->ano_conclusao_curso_superior_3}'";
        $gruda = ", ";
      }else{
        $set .= "{$gruda}ano_conclusao_curso_superior_3 = NULL";
        $gruda = ", ";
      }
      if (is_numeric($this->tipo_instituicao_curso_superior_3)) {
        $set .= "{$gruda}tipo_instituicao_curso_superior_3 = '{$this->tipo_instituicao_curso_superior_3}'";
        $gruda = ", ";
      }
      if (is_numeric($this->instituicao_curso_superior_3)) {
        $set .= "{$gruda}instituicao_curso_superior_3 = '{$this->instituicao_curso_superior_3}'";
        $gruda = ", ";
      }
      if (is_numeric($this->pos_especializacao)) {
        $set .= "{$gruda}pos_especializacao = '{$this->pos_especializacao}'";
        $gruda = ", ";
      }
      if (is_numeric($this->pos_mestrado)) {
        $set .= "{$gruda}pos_mestrado = '{$this->pos_mestrado}'";
        $gruda = ", ";
      }
      if (is_numeric($this->pos_doutorado)) {
        $set .= "{$gruda}pos_doutorado = '{$this->pos_doutorado}'";
        $gruda = ", ";
      }
      if (is_numeric($this->pos_nenhuma)) {
        $set .= "{$gruda}pos_nenhuma = '{$this->pos_nenhuma}'";
        $gruda = ", ";
      }
      if (is_numeric($this->curso_creche)) {
        $set .= "{$gruda}curso_creche = '{$this->curso_creche}'";
        $gruda = ", ";
      }
      if (is_numeric($this->curso_pre_escola)) {
        $set .= "{$gruda}curso_pre_escola = '{$this->curso_pre_escola}'";
        $gruda = ", ";
      }
      if (is_numeric($this->curso_anos_iniciais)) {
        $set .= "{$gruda}curso_anos_iniciais = '{$this->curso_anos_iniciais}'";
        $gruda = ", ";
      }
      if (is_numeric($this->curso_anos_finais)) {
        $set .= "{$gruda}curso_anos_finais = '{$this->curso_anos_finais}'";
        $gruda = ", ";
      }
      if (is_numeric($this->curso_ensino_medio)) {
        $set .= "{$gruda}curso_ensino_medio = '{$this->curso_ensino_medio}'";
        $gruda = ", ";
      }
      if (is_numeric($this->curso_eja)) {
        $set .= "{$gruda}curso_eja = '{$this->curso_eja}'";
        $gruda = ", ";
      }
      if (is_numeric($this->curso_educacao_especial)) {
        $set .= "{$gruda}curso_educacao_especial = '{$this->curso_educacao_especial}'";
        $gruda = ", ";
      }
      if (is_numeric($this->curso_educacao_indigena)) {
        $set .= "{$gruda}curso_educacao_indigena = '{$this->curso_educacao_indigena}'";
        $gruda = ", ";
      }
      if (is_numeric($this->curso_educacao_campo)) {
        $set .= "{$gruda}curso_educacao_campo = '{$this->curso_educacao_campo}'";
        $gruda = ", ";
      }
      if (is_numeric($this->curso_educacao_ambiental)) {
        $set .= "{$gruda}curso_educacao_ambiental = '{$this->curso_educacao_ambiental}'";
        $gruda = ", ";
      }
      if (is_numeric($this->curso_educacao_direitos_humanos)) {
        $set .= "{$gruda}curso_educacao_direitos_humanos = '{$this->curso_educacao_direitos_humanos}'";
        $gruda = ", ";
      }
      if (is_numeric($this->curso_genero_diversidade_sexual)) {
        $set .= "{$gruda}curso_genero_diversidade_sexual = '{$this->curso_genero_diversidade_sexual}'";
        $gruda = ", ";
      }
      if (is_numeric($this->curso_direito_crianca_adolescente)) {
        $set .= "{$gruda}curso_direito_crianca_adolescente = '{$this->curso_direito_crianca_adolescente}'";
        $gruda = ", ";
      }
      if (is_numeric($this->curso_relacoes_etnicorraciais)) {
        $set .= "{$gruda}curso_relacoes_etnicorraciais = '{$this->curso_relacoes_etnicorraciais}'";
        $gruda = ", ";
      }
      if (is_numeric($this->curso_outros)) {
        $set .= "{$gruda}curso_outros = '{$this->curso_outros}'";
        $gruda = ", ";
      }
      if (is_numeric($this->curso_nenhum)) {
        $set .= "{$gruda}curso_nenhum = '{$this->curso_nenhum}'";
        $gruda = ", ";
      }
      if (dbBool($this->multi_seriado)) {
        $set .= "{$gruda}multi_seriado = TRUE ";
        $gruda = ", ";
      }else{
        $set .= "{$gruda}multi_seriado = FALSE ";
        $gruda = ", ";
      }
      if ($set) {
        $db->Consulta("UPDATE {$this->_tabela} SET $set WHERE cod_servidor = '{$this->cod_servidor}' AND ref_cod_instituicao = '{$this->ref_cod_instituicao}'");
        return TRUE;
      }
    }
    return FALSE;
  }
  /**
   * Retorna um array com resultados de uma pesquisa parametrizada
   *
   * O array retornado contém em cada um de seus items um array associativo onde
   * as chaves correspondem aos campos da tabela indicados por
   * $this->_campos_lista.
   *
   * A pesquisa SELECT realizada é afetada por diversos parâmetros disponíveis.
   * Alguns dos parâmetros induzem a subqueries para a avaliação de diferentes
   * funcionalidades do sistema.
   *
   * @see intranet/educar_pesquisa_servidor_lst.php  Listagem de busca de
   *  servidores
   * @see intranet/educar_quadro_horario_horarios_cad.php  Cadastro de horário
   *  de aula para uma turma
   * @see intranet/educar_turma_cad.php  Cadastro de turma
   *
   * @param  int         $int_cod_servidor             Código do servidor
   * @param  int         $int_ref_cod_deficiencia      Código da deficiência do servidor
   * @param  int         $int_ref_idesco               Código da escolaridade do servidor
   * @param  int         $int_carga_horaria            Carga horária do servidor
   * @param  string      $date_data_cadastro_ini       Data de cadastro inicial (busca por intervalo >= ao valor)
   * @param  string      $date_data_cadastro_fim       Data de cadastro final (busca por intervalo <= ao valor)
   * @param  string      $date_data_exclusao_ini       Data da exclusão inicial (busca por intervalo >= ao valor)
   * @param  string      $date_data_exclusao_fim       Data da exclusão final (busca por intervalo <= ao valor)
   * @param  int         $int_ativo                    '1' para buscar apenas por servidores ativos
   * @param  int         $int_ref_cod_instituicao      Código da instituição do servidor
   * @param  string      $str_tipo                     'livre' para buscar apenas por servidores não alocados (subquery)
   * @param  array       $array_horario                Busca por horário de alocação do servidor (subquery)
   * @param  int         $str_not_in_servidor          Código de servidor a excluir
   * @param  string      $str_nome_servidor            Busca do tipo LIKE pelo padrão de nome do servidor (subquery)
   * @param  int|string  $boo_professor                Qualquer valor que avalie para TRUE para buscar por servidores professores (subquery)
   * @param  string      $str_horario                  'S' para buscar se o servidor está alocado em um dos horários (indicados $matutino, $vespertino ou $noturno) (subquery)
   * @param  bool        $bool_ordena_por_nome         TRUE para ordenar os resultados pelo campo nome por ordem alfabética crescente
   * @param  string      $lst_matriculas               Verifica se o servidor não está na lista de matriculas (string com inteiros separados por vírgula: 54, 55, 60).
   *                                                   Apenas verifica quando a buscar por horário de alocação é realizada
   * @param  bool        $matutino                     Busca por professores com horário livre no período matutino
   * @param  bool        $vespertino                   Busca por professores com horário livre no período vespertino
   * @param  bool        $noturno                      Busca por professores com horário livre no período noturno
   * @param  int         $int_ref_cod_escola           Código da escola para verificar se o servidor está alocado nela (usado em várias das subqueries)
   * @param  string      $str_hr_mat                   Duração da aula (formato HH:MM) para o período matutino
   * @param  string      $str_hr_ves                   Duração da aula (formato HH:MM) para o período vespertino
   * @param  string      $str_hr_not                   Duração da aula (formato HH:MM) para o período noturno
   * @param  int         $int_dia_semana               Inteiro para o dia da semana (1 = domingo, 7 = sábado)
   * @param  int         $alocacao_escola_instituicao  Código da instituição ao qual o servidor deve estar cadastrado (subquery)
   * @param  int         $int_identificador            Campo identificado para busca na tabela pmieducar.quadro_horario_horarios_aux (subquery)
   * @param  int         $int_ref_cod_curso            Código do curso que o professor deve estar cadastrado (subquery)
   * @param  int         $int_ref_cod_disciplina       Código da disciplina que o professor deve ser habilitado (subquery).
   *                                                   Somente verifica quando o curso passado por $int_ref_cod_curso não
   *                                                   possui sistema de falta globalizada
   * @param  int         $int_ref_cod_subnivel         Código de subnível que o servidor deve possuir
   * @return array|bool  Array com os resultados da query SELECT ou FALSE caso
   *                     nenhum registro tenha sido encontrado
   */
  function lista(
    $int_cod_servidor            = NULL,
    $int_ref_cod_deficiencia     = NULL,
    $int_ref_idesco              = NULL,
    $int_carga_horaria           = NULL,
    $date_data_cadastro_ini      = NULL,
    $date_data_cadastro_fim      = NULL,
    $date_data_exclusao_ini      = NULL,
    $date_data_exclusao_fim      = NULL,
    $int_ativo                   = NULL,
    $int_ref_cod_instituicao     = NULL,
    $str_tipo                    = NULL,
    $array_horario               = NULL,
    $str_not_in_servidor         = NULL,
    $str_nome_servidor           = NULL,
    $boo_professor               = FALSE,
    $str_horario                 = NULL,
    $bool_ordena_por_nome        = FALSE,
    $lst_matriculas              = NULL,
    $matutino                    = FALSE,
    $vespertino                  = FALSE,
    $noturno                     = FALSE,
    $int_ref_cod_escola          = NULL,
    $str_hr_mat                  = NULL,
    $str_hr_ves                  = NULL,
    $str_hr_not                  = NULL,
    $int_dia_semana              = NULL,
    $alocacao_escola_instituicao = NULL,
    $int_identificador           = NULL,
    $int_ref_cod_curso           = NULL,
    $int_ref_cod_disciplina      = NULL,
    $int_ref_cod_subnivel        = NULL,
    $bool_servidor_sem_alocacao  = FALSE
    ) {
    // Extrai as informações de hora inicial e hora final, para definir melhor
    // o lookup de carga horária de servidores alocados, para operações como
    // a alocação de docente em quadro de horário. Isso é necessário para que
    // não seja necessário alocar o docente em dois períodos diferentes apenas
    // porque o horário final de uma aula extrapola o limite de horário do
    // período.
    if (is_array($array_horario) && 3 >= count($array_horario)) {
      $horarioInicial = explode(':', $array_horario[1]);
      $horarioFinal   = explode(':', $array_horario[2]);
      $horarioInicial = $horarioInicial[0] * 60 + $horarioInicial[1];
      $horarioFinal   = $horarioFinal[0] * 60 + $horarioFinal[1];
      // Caso o horário definido inicie no período "matutino" e se encerre no
      // período "vespertino", irá considerar como "matutino" apenas.
      $matutinoLimite = 12 * 60;
      if ($horarioInicial < $matutinoLimite && $horarioFinal > $matutinoLimite) {
        $vespertino = false;
      }
      // Caso o horário definido inicie no período "vespertino" e se encerre
      // no período "noturno", irá considerar como "vespertino" apenas.
      $vespertinoLimite = 18 * 60;
      if ($horarioInicial < $vespertinoLimite && $horarioFinal > $vespertinoLimite) {
        $noturno = false;
      }
    }
    $whereAnd     = ' WHERE ';
    $filtros      = '';
    $tabela_compl = '';
    if (is_bool($bool_ordena_por_nome)) {
      $tabela_compl         .= ', cadastro.pessoa p';
      $this->_campos_lista2 .= ', p.nome';
      $filtros              .= $whereAnd . ' s.cod_servidor = p.idpes ';
      $whereAnd              = ' AND ';
      $this->setOrderby('nome');
    }
    else {
      $this->_campos_lista2 = $this->_todos_campos2;
      $this->setOrderby(' 1 ');
    }
    $sql = "SELECT {$this->_campos_lista2} FROM {$this->_schema}servidor s{$tabela_compl}";
    if (is_numeric($int_cod_servidor)) {
      $filtros .= "{$whereAnd} s.cod_servidor = '{$int_cod_servidor}'";
      $whereAnd = " AND ";
    }
    if (is_numeric($int_ref_idesco)) {
      $filtros .= "{$whereAnd} s.ref_idesco = '{$int_ref_idesco}'";
      $whereAnd = " AND ";
    }
    if (is_numeric($int_carga_horaria)) {
      $filtros .= "{$whereAnd} s.carga_horaria = '{$int_carga_horaria}'";
      $whereAnd = " AND ";
    }
    if (is_string($date_data_cadastro_ini)) {
      $filtros .= "{$whereAnd} s.data_cadastro >= '{$date_data_cadastro_ini}'";
      $whereAnd = " AND ";
    }
    if (is_string($date_data_cadastro_fim)) {
      $filtros .= "{$whereAnd} s.data_cadastro <= '{$date_data_cadastro_fim}'";
      $whereAnd = " AND ";
    }
    if (is_string($date_data_exclusao_ini)) {
      $filtros .= "{$whereAnd} s.data_exclusao >= '{$date_data_exclusao_ini}'";
      $whereAnd = " AND ";
    }
    if (is_string($date_data_exclusao_fim)) {
      $filtros .= "{$whereAnd} s.data_exclusao <= '{$date_data_exclusao_fim}'";
      $whereAnd = " AND ";
    }
    if (is_null($int_ativo) || $int_ativo) {
      $filtros .= "{$whereAnd} s.ativo = '1'";
      $whereAnd = " AND ";
    }
    else {
      $filtros .= "{$whereAnd} s.ativo = '0'";
      $whereAnd = " AND ";
    }
    if (is_numeric($int_ref_cod_instituicao)) {
      $filtros .= "{$whereAnd} s.ref_cod_instituicao = '{$int_ref_cod_instituicao}'";
      $whereAnd = " AND ";
    }
    // Busca tipo LIKE pelo nome do servidor
    if (is_string($str_nome_servidor)) {
      $filtros .= "{$whereAnd} EXISTS (SELECT 1
  FROM cadastro.pessoa p
  WHERE cod_servidor = p.idpes
  AND to_ascii(p.nome) LIKE to_ascii('%$str_nome_servidor%')) ";
      $whereAnd = " AND ";
    }
    // Seleciona apenas servidores que tenham a carga atual maior ou igual ao
    // do servidor atual
    if (is_string($str_tipo) && $str_tipo == 'livre') {
      if (is_numeric($int_ref_cod_instituicao)) {
        $where  = " AND s.ref_cod_instituicao      = '{$int_ref_cod_instituicao}' ";
        $where2 = " AND sa.ref_ref_cod_instituicao = '{$int_ref_cod_instituicao}' ";
      }
      $filtros .= "
  {$whereAnd} NOT EXISTS
    (SELECT 1
    FROM pmieducar.servidor_alocacao sa
    WHERE sa.ref_cod_servidor = s.cod_servidor $where2)";
      $filtros .= "
  {$whereAnd} (s.carga_horaria::text || ':00:00') >= COALESCE(
    (SELECT SUM(carga_horaria::time)::text
    FROM pmieducar.servidor_alocacao saa
    WHERE saa.ref_cod_servidor = {$str_not_in_servidor}),'00:00') $where";
      $whereAnd = " AND ";
    }
    else {
      if (is_numeric($int_ref_cod_instituicao) || is_numeric($int_ref_cod_escola))
     {
        $filtros .= "
    {$whereAnd} (s.cod_servidor IN
      (SELECT a.ref_cod_servidor
        FROM pmieducar.servidor_alocacao a
        WHERE ";
        if (is_numeric($int_ref_cod_instituicao)) {
          $filtros .= " a.ref_ref_cod_instituicao = '{$int_ref_cod_instituicao}'";
        }
        if (is_numeric($int_ref_cod_escola)) {
          if (is_numeric($int_ref_cod_instituicao)) {
            $filtros .= " " . $whereAnd;
          }
          $filtros .= " ref_cod_escola = '{$int_ref_cod_escola}' ";
        }
        if($bool_servidor_sem_alocacao){
          $filtros .= ') OR NOT EXISTS(SELECT 1 FROM pmieducar.servidor_alocacao where servidor_alocacao.ativo = 1 and servidor_alocacao.ref_cod_servidor = s.cod_servidor)) ';
        }else{
          $filtros .= ')) ';
        }
      }
      if (is_array($array_horario)) {
        $cond = "AND";
        if (is_numeric($int_ref_cod_instituicao)) {
          $where .= " {$cond} a.ref_ref_cod_instituicao = '{$int_ref_cod_instituicao}' ";
          $cond   = "AND";
        }
        if (is_numeric($int_ref_cod_escola)) {
          $where .= " {$cond} a.ref_cod_escola = '{$int_ref_cod_escola}' ";
          $cond   = "AND";
        }
        $where .= " {$cond} a.ativo = '1'";
        $cond   = "AND";
        $hora_ini = explode(":", $array_horario[1]);
        $hora_fim = explode(":", $array_horario[2]);
        $horas    = sprintf("%02d", (int) abs($hora_fim[0]) - abs($hora_ini[0]));
        $minutos  = sprintf("%02d", (int) abs($hora_fim[1]) - abs($hora_ini[1]));
        // Remove qualquer AND que esteja no início da cláusula SQL
        $wherePieces = explode(' ', trim($where));
        if ('AND' == $wherePieces[0]) {
          array_shift($wherePieces);
          $where = implode(' ', $wherePieces);
        }
        if ($matutino) {
          if (is_string($str_horario) && $str_horario == "S") {
            // A somatória retorna nulo
            $filtros .= "
    {$whereAnd} (s.cod_servidor IN (SELECT a.ref_cod_servidor
          FROM pmieducar.servidor_alocacao a
          WHERE $where
          AND a.periodo = 1
          AND a.carga_horaria >= COALESCE(
          (SELECT SUM(qhh.hora_final - qhh.hora_inicial)
            FROM pmieducar.quadro_horario_horarios qhh
            WHERE qhh.ref_cod_instituicao_servidor = '$int_ref_cod_instituicao'
            AND qhh.ref_cod_escola = '$int_ref_cod_escola'
            AND hora_inicial >= '06:00'
            AND hora_inicial <= '12:00'
            AND qhh.ativo = '1'
            AND qhh.dia_semana <> '$int_dia_semana'
            AND qhh.ref_servidor = a.ref_cod_servidor
            GROUP BY qhh.ref_servidor) ,'00:00')  + '$str_hr_mat' + COALESCE(
            (SELECT SUM( qhha.hora_final - qhha.hora_inicial )
              FROM pmieducar.quadro_horario_horarios_aux qhha
              WHERE qhha.ref_cod_instituicao_servidor = '$int_ref_cod_instituicao'
              AND qhha.ref_cod_escola = $int_ref_cod_escola
              AND hora_inicial >= '06:00'
              AND hora_inicial <= '12:00'
              AND qhha.ref_servidor = a.ref_cod_servidor
              AND identificador = '$int_identificador'
              GROUP BY qhha.ref_servidor),'00:00')) OR s.multi_seriado )";
          }
          else {
            $filtros .= "
      {$whereAnd} (s.cod_servidor NOT IN (SELECT a.ref_cod_servidor
              FROM pmieducar.servidor_alocacao a
              WHERE $where
              AND a.periodo = 1) OR s.multi_seriado )";
          }
        }
        if ($vespertino) {
          if (is_string($str_horario) && $str_horario == "S") {
            $filtros .= "
      {$whereAnd} (s.cod_servidor IN
              (SELECT a.ref_cod_servidor
                FROM pmieducar.servidor_alocacao a
                WHERE $where
                AND a.periodo = 2
                AND a.carga_horaria >= COALESCE(
                  (SELECT SUM( qhh.hora_final - qhh.hora_inicial )
                  FROM pmieducar.quadro_horario_horarios qhh
                  WHERE qhh.ref_cod_instituicao_servidor = '$int_ref_cod_instituicao'
                  AND qhh.ref_cod_escola = '$int_ref_cod_escola'
                  AND qhh.ativo = '1'
                  AND hora_inicial >= '12:00'
                  AND hora_inicial <= '18:00'
                  AND qhh.dia_semana <> '$int_dia_semana'
                  AND qhh.ref_servidor = a.ref_cod_servidor
                  GROUP BY qhh.ref_servidor ),'00:00') + '$str_hr_ves' +  COALESCE(
                  (SELECT SUM( qhha.hora_final - qhha.hora_inicial )
                    FROM pmieducar.quadro_horario_horarios_aux qhha
                    WHERE qhha.ref_cod_instituicao_servidor = '$int_ref_cod_instituicao'
                    AND qhha.ref_cod_escola = '$int_ref_cod_escola'
                    AND qhha.ref_servidor = a.ref_cod_servidor
                    AND hora_inicial >= '12:00'
                    AND hora_inicial <= '18:00'
                    AND identificador = '$int_identificador'
                    GROUP BY qhha.ref_servidor),'00:00') ) OR s.multi_seriado ) ";
          }
          else {
            $filtros .= "
      {$whereAnd} (s.cod_servidor NOT IN ( SELECT a.ref_cod_servidor
              FROM pmieducar.servidor_alocacao a
              WHERE $where
              AND a.periodo = 2 ) OR s.multi_seriado) ";
          }
        }
        if ($noturno) {
          if (is_string($str_horario) && $str_horario == "S") {
            $filtros .= "
      {$whereAnd} (s.cod_servidor IN ( SELECT a.ref_cod_servidor
              FROM pmieducar.servidor_alocacao a
              WHERE $where
              AND a.periodo = 3
              AND a.carga_horaria >= COALESCE(
              (SELECT SUM(qhh.hora_final - qhh.hora_inicial)
                FROM pmieducar.quadro_horario_horarios qhh
                WHERE qhh.ref_cod_instituicao_servidor = '$int_ref_cod_instituicao'
                AND qhh.ref_cod_escola = '$int_ref_cod_escola'
                AND qhh.ativo = '1'
                AND hora_inicial >= '18:00'
                AND hora_inicial <= '23:59'
                AND qhh.dia_semana <> '$int_dia_semana'
                AND qhh.ref_servidor = a.ref_cod_servidor
                GROUP BY qhh.ref_servidor ),'00:00')  + '$str_hr_not' +  COALESCE(
                  (SELECT SUM( qhha.hora_final - qhha.hora_inicial )
                  FROM pmieducar.quadro_horario_horarios_aux qhha
                  WHERE qhha.ref_cod_instituicao_servidor = '$int_ref_cod_instituicao'
                  AND qhha.ref_cod_escola = '$int_ref_cod_escola'
                  AND qhha.ref_servidor = a.ref_cod_servidor
                  AND hora_inicial >= '18:00'
                  AND hora_inicial <= '23:59'
                  AND identificador = '$int_identificador'
                  GROUP BY qhha.ref_servidor),'00:00') ) OR s.multi_seriado) ";
          }
          else {
            $filtros .= "
      {$whereAnd} (s.cod_servidor NOT IN (
            SELECT a.ref_cod_servidor
              FROM pmieducar.servidor_alocacao a
              WHERE $where
              AND a.periodo = 3 ) OR s.multi_seriado) ";
          }
        }
        if (is_string($str_horario) && $str_horario == "S") {
        }
        else {
          $filtros .= "
      {$whereAnd} ((s.carga_horaria >= COALESCE(
                    (SELECT sum(hora_final - hora_inicial) + '" . abs($horas) . ":" . abs($minutos)."'
                      FROM pmieducar.servidor_alocacao sa
                      WHERE sa.ref_cod_servidor = s.cod_servidor
                      AND sa.ref_ref_cod_instituicao ='{$int_ref_cod_instituicao}'),'00:00')) OR s.multi_seriado)";
        }
      }
    }
    if ((is_array($array_horario) && $str_not_in_servidor) || (is_string($str_tipo) && $str_not_in_servidor)) {
      $filtros .= "{$whereAnd} s.cod_servidor NOT IN ( {$str_not_in_servidor} )";
      $whereAnd = " AND ";
    }
    $obj_curso = new clsPmieducarCurso($int_ref_cod_curso);
    $det_curso = $obj_curso->detalhe();
    // Seleciona apenas servidor cuja uma de suas funções seja a de professor
    // @todo Extract method
    if ($boo_professor) {
      /*
       * Caso os códigos de disciplina e de curso não sejam informado, mas o de
       * servidor para não buscar sim, seleciona as disciplinas deste servidor
       * com o qual o professor candidato terá que lecionar para ser retornado
       * na query.
       */
      if (!$int_ref_cod_disciplina && !$int_ref_cod_curso) {
        $servidorDisciplina = new clsPmieducarServidorDisciplina();
        $disciplinas = $servidorDisciplina->lista(NULL, NULL, $str_not_in_servidor);
        $servidorDisciplinas = array();
        if (is_array($disciplinas)) {
          foreach ($disciplinas as $disciplina) {
            $servidorDisciplinas[] = sprintf(
              '(sd.ref_cod_disciplina = %d AND sd.ref_cod_curso = %d)',
              $disciplina['ref_cod_disciplina'], $disciplina['ref_cod_curso']);
          }
          $servidorDisciplinas = sprintf('AND (%s)', implode(' AND ', $servidorDisciplinas));
        }
        else {
          $servidorDisciplinas = '';
        }
      }
      else {
        $servidorDisciplinas = sprintf(
          'AND (case when %1$d = 0 then
                  sd.ref_cod_curso = %2$d
                else
                  (sd.ref_cod_disciplina = %1$d AND sd.ref_cod_curso = %2$d)
                end)',
          $int_ref_cod_disciplina, $int_ref_cod_curso);
      }
      $filtros .= "
    {$whereAnd} EXISTS
      (SELECT
         1
       FROM
         pmieducar.servidor_funcao sf, pmieducar.funcao f, pmieducar.servidor_disciplina sd
       WHERE
        f.cod_funcao = sf.ref_cod_funcao AND
        f.professor = 1 AND
        sf.ref_ref_cod_instituicao = s.ref_cod_instituicao AND
        s.cod_servidor = sf.ref_cod_servidor AND
        s.cod_servidor = sd.ref_cod_servidor AND
        s.ref_cod_instituicao = sd.ref_ref_cod_instituicao
        {$servidorDisciplinas})";
      $whereAnd = " AND ";
    }
    if (is_string($str_horario) && $str_horario == "S") {
      $filtros .= "
    {$whereAnd} (s.cod_servidor NOT IN
      (SELECT DISTINCT qhh.ref_servidor
        FROM pmieducar.quadro_horario_horarios qhh
        WHERE qhh.ref_servidor = s.cod_servidor
        AND qhh.ref_cod_instituicao_servidor = s.ref_cod_instituicao
        AND qhh.dia_semana = '{$array_horario[0]}'
        AND ((('{$array_horario[1]}' > qhh.hora_inicial AND '{$array_horario[1]}' < qhh.hora_final)
              OR ('{$array_horario[2]}' > qhh.hora_inicial AND '{$array_horario[2]}' < qhh.hora_final))
            OR ('{$array_horario[1]}' = qhh.hora_inicial AND '{$array_horario[2]}' = qhh.hora_final)
            OR ('{$array_horario[1]}' <= qhh.hora_inicial AND '{$array_horario[2]}' >= qhh.hora_final))
        AND qhh.ativo = '1' ";
      if (is_string($lst_matriculas)) {
        $filtros .= "AND qhh.ref_servidor NOT IN ({$lst_matriculas})";
      }
      $filtros .= " ) OR s.multi_seriado) ";
      $whereAnd = " AND ";
    }
    if (is_numeric($int_ref_cod_subnivel)) {
      $filtros .= "{$whereAnd} s.ref_cod_subnivel = '{$int_ref_cod_subnivel}'";
      $whereAnd = " AND ";
    }
    $countCampos = count(explode(',', $this->_campos_lista));
    $resultado = array();
    $db = new clsBanco();
    $sql = "SELECT {$this->_campos_lista2} FROM {$this->_schema}servidor s{$tabela_compl} {$filtros}" .
      $this->getOrderby() . $this->getLimite();
    $this->_total = $db->CampoUnico("SELECT COUNT(0) FROM {$this->_schema}servidor s{$tabela_compl} {$filtros}");
    // Executa a query
     // echo"<pre>";var_dump($sql);die;
    $db->Consulta($sql);
    if ($countCampos > 1) {
      while ($db->ProximoRegistro()) {
        $tupla = $db->Tupla();
        $tupla['_total'] = $this->_total;
        $resultado[] = $tupla;
      }
    }
    else {
      while ($db->ProximoRegistro()) {
        $tupla = $db->Tupla();
        $resultado[] = $tupla[$this->_campos_lista];
      }
    }
    if (count($resultado)) {
      return $resultado;
    }
    return FALSE;
  }
  /**
   * Retorna um array com os dados de um registro.
   * @return array
   */
  function detalhe()
  {
    if (is_numeric($this->cod_servidor) && is_numeric($this->ref_cod_instituicao)) {
      $db = new clsBanco();
      $db->Consulta("SELECT {$this->_todos_campos} FROM {$this->_tabela} WHERE cod_servidor = '{$this->cod_servidor}' AND ref_cod_instituicao = '{$this->ref_cod_instituicao}'");
      $db->ProximoRegistro();
      return $db->Tupla();
    }
    return false;
  }
  /**
   * Retorna um array com os dados de um registro.
   * @return array
   */
  function existe()
  {
    if (is_numeric($this->cod_servidor) && is_numeric($this->ref_cod_instituicao)) {
      $db = new clsBanco();
      $db->Consulta("SELECT 1 FROM {$this->_tabela} WHERE cod_servidor = '{$this->cod_servidor}' AND ref_cod_instituicao = '{$this->ref_cod_instituicao}'");
      $db->ProximoRegistro();
      return $db->Tupla();
    }
    return FALSE;
  }
  /**
   * Exclui um registro.
   * @return bool
   */
  function excluir()
  {
    if (is_numeric($this->cod_servidor) && is_numeric($this->ref_cod_instituicao)) {
      $this->ativo = 0;
      return $this->edita();
    }
    return FALSE;
  }
  /**
   * Retorna array com as funções do servidor
   *
   * Exemplo de array de retorno:
   * <code>
   * array(
   *   '2' => array(
   *     'cod_funcao' => 2,
   *     'nm_funcao' => 'Professor',
   *     'professor' => 1
   *   )
   * );
   * <code>
   *
   * @since   Método disponível desde a versão 1.0.2
   * @return  array  Array associativo com a primeira chave sendo o código da
   *   função. O array interno contém o nome da função e se a função desempenha
   *   um papel de professor
   */
  function getServidorFuncoes()
  {
    $db = new clsBanco();
    $sql  = 'SELECT t2.cod_funcao, t2.nm_funcao, t2.professor FROM pmieducar.servidor_funcao AS t1, pmieducar.funcao AS t2 ';
    $sql .= 'WHERE t1.ref_cod_servidor = \'%d\' AND t1.ref_ref_cod_instituicao = \'%d\' ';
    $sql .= 'AND t1.ref_cod_funcao = t2.cod_funcao';
    $sql = sprintf($sql, $this->cod_servidor, $this->ref_cod_instituicao);
    $db->Consulta($sql);
    $funcoes = array();
    while ($db->ProximoRegistro() != FALSE) {
      $row = $db->Tupla();
      $funcoes[$row['cod_funcao']] = array(
        'cod_funcao' => $row['cod_funcao'],
        'nm_funcao'  => $row['nm_funcao'],
        'professor'  => $row['professor'],
      );
    }
    return $funcoes;
  }
  /**
   * Retorna um array com as disciplinas alocadas ao servidor no quadro de
   * horários
   *
   * @since   Método disponível desde a versão 1.0.2
   * @param   int  $codServidor     Código do servidor, caso não seja informado,
   *   usa o código disponível no objeto atual
   * @param   int  $codInstituicao  Código da instituição, caso não seja
   *   informado, usa o código disponível no objeto atual
   * @return  array|bool  Array com códigos das disciplinas ordenados ou FALSE
   *   caso o servidor não tenha disciplinas
   */
  function getServidorDisciplinasQuadroHorarioHorarios($codServidor = NULL,
    $codInstituicao = NULL)
  {
    $codServidor = $codServidor != NULL ? $codServidor : $this->cod_servidor;
    $codInstituicao = $codInstituicao != NULL ? $codInstituicao : $this->ref_cod_instituicao;
    $sql  = 'SELECT DISTINCT(qhh.ref_cod_disciplina) AS ref_cod_disciplina ';
    $sql .= 'FROM pmieducar.quadro_horario_horarios qhh, pmieducar.servidor s ';
    $sql .= 'WHERE qhh.ref_servidor = s.cod_servidor AND ';
    $sql .= 'qhh.ref_servidor = \'%d\' AND qhh.ref_cod_instituicao_servidor = \'%d\'';
    $sql = sprintf($sql, $codServidor, $codInstituicao);
    $db = new clsBanco();
    $db->Consulta($sql);
    $disciplinas = array();
    while ($db->ProximoRegistro() != FALSE) {
      $row = $db->Tupla();
      $disciplinas[] = $row['ref_cod_disciplina'];
    }
    if (count($disciplinas)) {
      return asort($disciplinas);
    }
    return FALSE;
  }
   /**
    * Retorna um array com os códigos de servidor e instituição, usando os
    * valores dos parâmetros ou das propriedades da instância atual.
    *
    * @since   Método disponível desde a versão 1.2.0
    * @param   int  $codServidor     Código do servidor, caso não seja informado,
    *   usa o código disponível no objeto atual
    * @param   int  $codInstituicao  Código da instituição, caso não seja
    *   informado, usa o código disponível no objeto atual
    * @return  array|bool  (codServidor => (int), codInstituicao => (int))
    */
  function _getCodServidorInstituicao($codServidor = NULL, $codInstituicao = NULL)
  {
    $codServidor    = $codServidor != NULL ? $codServidor : $this->cod_servidor;
    $codInstituicao = $codInstituicao != NULL ? $codInstituicao : $this->ref_cod_instituicao;
    // Se códigos não forem fornecidos, nem pela classe nem pelo código cliente,
    // retorna FALSE
    if ($codServidor == NULL || $codInstituicao == NULL) {
      return FALSE;
    }
    return array(
      'codServidor'    => $codServidor,
      'codInstituicao' => $codInstituicao
    );
  }
  /**
   * Retorna um array com os códigos das disciplinas do servidor
   *
   * @since   Método disponível desde a versão 1.0.2
   * @param   int  $codServidor     Código do servidor, caso não seja informado,
   *   usa o código disponível no objeto atual
   * @param   int  $codInstituicao  Código da instituição, caso não seja
   *   informado, usa o código disponível no objeto atual
   * @return  array|bool  Array com códigos das disciplinas ordenados ou FALSE
   *   caso o servidor não tenha disciplinas
   */
  function getServidorDisciplinas($codServidor = NULL,
    $codInstituicao = NULL)
  {
    $codigos = $this->_getCodServidorInstituicao($codServidor, $codInstituicao);
    if (! $codigos) {
      return FALSE;
    }
    // Se códigos não forem fornecidos, nem pela classe nem pelo código cliente,
    // retorna FALSE
    if ($codServidor == NULL || $codInstituicao == NULL) {
      return FALSE;
    }
    $sql  = 'SELECT DISTINCT(sd.ref_cod_disciplina) AS ref_cod_disciplina ';
    $sql .= 'FROM pmieducar.servidor_disciplina sd, pmieducar.servidor s ';
    $sql .= 'WHERE sd.ref_cod_servidor = s.cod_servidor AND ';
    $sql .= 'sd.ref_cod_servidor = \'%d\' AND sd.ref_ref_cod_instituicao = \'%d\'';
    $sql = sprintf($sql, $codigos['codServidor'], $codigos['codInstituicao']);
    $db = new clsBanco();
    $db->Consulta($sql);
    $disciplinas = array();
    while ($db->ProximoRegistro() != FALSE) {
      $row = $db->Tupla();
      $disciplinas[] = $row['ref_cod_disciplina'];
    }
    if (count($disciplinas)) {
      return asort($disciplinas);
    }
    return FALSE;
  }
  /**
   * Retorna os horários de aula do servidor na instituição.
   *
   * @since   Método disponível desde a versão 1.0.2
   * @param   int  $codServidor     Código do servidor, caso não seja informado,
   *   usa o código disponível no objeto atual
   * @param   int  $codInstituicao  Código da instituição, caso não seja
   *   informado, usa o código disponível no objeto atual
   * @return  array|bool  Array associativo com os índices nm_escola, nm_curso,
   *   nm_serie, nm_turma, nome (componente curricular), dia_semana,
   *   hora_inicial e hora_final.
   */
  function getHorariosServidor($codServidor = NULL, $codInstituicao = NULL)
  {
    $codigos = $this->_getCodServidorInstituicao($codServidor, $codInstituicao);
    if (! $codigos) {
      return FALSE;
    }
    $sql = 'SELECT
              ec.nm_escola,
              c.nm_curso,
              s.nm_serie,
              t.nm_turma,
              cc.nome,
              qhh.dia_semana,
              qhh.hora_inicial,
              qhh.hora_final
            FROM
              pmieducar.quadro_horario_horarios qhh,
              pmieducar.quadro_horario qh,
              pmieducar.turma t,
              pmieducar.serie s,
              pmieducar.curso c,
              pmieducar.escola_complemento ec,
              modules.componente_curricular cc
            WHERE
              qh.cod_quadro_horario = qhh.ref_cod_quadro_horario
              AND qh.ref_cod_turma = t.cod_turma
              AND t.ref_ref_cod_serie = s.cod_serie
              AND s.ref_cod_curso = c.cod_curso
              AND qhh.ref_cod_escola = ec.ref_cod_escola
              AND qhh.ref_cod_disciplina = cc.id
              AND qh.ativo = 1
              AND qhh.ativo = 1
              AND t.ativo = 1
              AND qhh.ref_servidor = %d
              AND qhh.ref_cod_instituicao_servidor = %d
            ORDER BY
              nm_escola,
              dia_semana,
              hora_inicial';
      $sql = sprintf($sql, $codigos['codServidor'], $codigos['codInstituicao']);
      $db = new clsBanco();
      $db->Consulta($sql);
      $horarios = array();
      while ($db->ProximoRegistro() != FALSE) {
        $row = $db->Tupla();
        $horarios[] = $row;
      }
      if (count($horarios)) {
        return $horarios;
      }
      return FALSE;
  }
  /**
   * Verifica se um servidor desempenha a função de professor.
   *
   * Primeiro, recuperamos todas as funções do servidor e procuramos
   * por um dos itens que tenha o índice professor igual a 1.
   *
   * @since   Método disponível desde a versão 1.0.2
   * @return  bool  TRUE caso o servidor desempenhe a função de professor
   */
  function isProfessor()
  {
    $funcoes = $this->getServidorFuncoes();
    foreach ($funcoes as $funcao) {
      if (1 == $funcao['professor']) {
        return TRUE;
        break;
      }
    }
    return FALSE;
  }
  /**
   * Define quais campos da tabela serão selecionados no método Lista().
   */
  function setCamposLista($str_campos)
  {
    $this->_campos_lista = $str_campos;
  }
  /**
   * Define que o método Lista() deverpa retornar todos os campos da tabela.
   */
  function resetCamposLista()
  {
    $this->_campos_lista = $this->_todos_campos;
  }
  /**
   * Define limites de retorno para o método Lista().
   */
  function setLimite($intLimiteQtd, $intLimiteOffset = NULL)
  {
    $this->_limite_quantidade = $intLimiteQtd;
    $this->_limite_offset = $intLimiteOffset;
  }
  /**
   * Retorna a string com o trecho da query responsável pelo limite de
   * registros retornados/afetados.
   *
   * @return string
   */
  function getLimite()
  {
    if (is_numeric($this->_limite_quantidade)) {
      $retorno = " LIMIT {$this->_limite_quantidade}";
      if (is_numeric($this->_limite_offset)) {
        $retorno .= " OFFSET {$this->_limite_offset} ";
      }
      return $retorno;
    }
    return '';
  }
  /**
   * Define o campo para ser utilizado como ordenação no método Lista().
   */
  function setOrderby($strNomeCampo)
  {
    if (is_string($strNomeCampo) && $strNomeCampo ) {
      $this->_campo_order_by = $strNomeCampo;
    }
  }
  /**
   * Retorna a string com o trecho da query responsável pela Ordenação dos
   * registros.
   *
   * @return string
   */
  function getOrderby()
  {
    if (is_string($this->_campo_order_by)) {
      return " ORDER BY {$this->_campo_order_by} ";
    }
    return '';
  }
  /**
   * Retorna um array com os dados de um registro.
   * @return array
   */
  function qtdhoras($int_cod_servidor, $int_cod_escola, $int_ref_cod_instituicao,
    $dia_semana)
  {
    $db = new clsBanco();
    $db->Consulta("
      SELECT
        EXTRACT(HOUR FROM (SUM(hora_final - hora_inicial))) AS hora,
        EXTRACT(MINUTE FROM (SUM(hora_final - hora_inicial))) AS min
      FROM
        pmieducar.servidor_alocacao
      WHERE
        ref_cod_servidor = {$int_cod_servidor} AND
        ref_cod_escola = {$int_cod_escola} AND
        ref_ref_cod_instituicao = {$int_ref_cod_instituicao} AND
        dia_semana = {$dia_semana}"
    );
    $db->ProximoRegistro();
    return $db->Tupla();
  }
}