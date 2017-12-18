<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta_plugin.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));

$oPost = db_utils::postMemory($_POST);

if (isset($oPost->dados)) {
	
   try {
     
   	 $oEscolaInsuficienciaEspaco = new cl_escolainsuficienciaespaco();
   	 
     //montamos um array com os dados das escolas para serem percorridos posteriormente
   	 $aDados = explode("|",$oPost->dados);
   	 
   	 for ($iInd = 0; $iInd < count($aDados); $iInd++) {
   	 
   	   $aDadosEscola = explode(":",$aDados[$iInd]);
   	   $iEscola = $aDadosEscola[0];
   	   $sInsuficiencia = $aDadosEscola[1];
   	   
   	   //excluimos todos os dados da tabela
   	   $oEscolaInsuficienciaEspaco->excluir($iEscola);
       if ($oEscolaInsuficienciaEspaco->erro_status == "0") {
       	$sMsg = "Erro excluindo dados da insuficiencia de espaço para as escolas.\n";
       	$sMsg .= $oEscolaInsuficienciaEspaco->erro_msg."\n";
       	$sMsg .= pg_last_error();
       	throw new Exception($sMsg);
       }
       
       //incluimos os dados
       $oEscolaInsuficienciaEspaco->escola = $iEscola;
       $oEscolaInsuficienciaEspaco->insuficiencia = $sInsuficiencia;
       $oEscolaInsuficienciaEspaco->incluir(null);
       if ($oEscolaInsuficienciaEspaco->erro_status == "0") {
       	$sMsg = "Erro incluindo dados da insuficiencia de espaço para as escolas.\n";
       	$sMsg .= $oEscolaInsuficienciaEspaco->erro_msg."\n";
       	$sMsg .= pg_last_error();
       	throw new Exception($sMsg);
       }
       
   	 }
     db_fim_transacao(false);
     db_msgbox("Operação realizada com sucesso");
     
   } catch (Exception $oErro) {
   	
   	db_fim_transacao(true);
   	db_msgbox($oErro->getMessage());
   	
   }
	
}
?>
<html>
<head>
<style type=""></style>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js");
  db_app::load("dbtextField.widget.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("dbcomboBox.widget.js");
?>

</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC"  >
<br><br>
<center>
<form name="form1" method="post">
  <input type="hidden" value="" name="dados" id="dados">
  <fieldset style="width:600px;">
  <legend>
    <strong>
      Escolas
    </strong>
  </legend>
    <table border="0">
      <tr>
        <td>
          <div id='ctnGridEscolas' ></div>
        </td>
      </tr>
      <tr>
        <td align="center">
          <input type="button" name="atualizar" value="Atualizar" onclick="js_atualizar()">
        </td>
      </tr>
    </table>
  </fieldset>

</form>
</center>
<?
db_menu();
?>
</body>
</html>

<script>

function montaGrid() {
oGridEscolas = new DBGrid('oGridEscolas');
oGridEscolas.nameInstance = 'oGridEscolas';

oGridEscolas.setCellWidth(new Array('400px','120px'));
oGridEscolas.setCellAlign(new Array('left','center'));
oGridEscolas.setHeader(new Array('Escola','Espaço Insuficiente'));

oGridEscolas.setHeight(400);
oGridEscolas.show($('ctnGridEscolas'));
oGridEscolas.clearAll(true);

<?
$sSqlEscolas = "select ed18_i_codigo, 
	                   ed18_c_nome,
 		               insuficiencia
	              from escola 
 		               left join plugins.escolainsuficienciaespaco on escola = ed18_i_codigo
	             order by ed18_c_nome";
$rsEscolas = db_query($sSqlEscolas);
$iQtdEscolas = pg_num_rows($rsEscolas);

for ($iInd = 0; $iInd < $iQtdEscolas; $iInd++) {
	
 $oDadosEscola = db_utils::fieldsMemory($rsEscolas, $iInd);
	
 $sSelected = (($oDadosEscola->insuficiencia == "t")?"selected":"");
 
 $sHtmlSelect = "<select id='escola_{$oDadosEscola->ed18_i_codigo}' name='escola_{$oDadosEscola->ed18_i_codigo}' style='width: 60px;'>";
 $sHtmlSelect .= "<option value='f'>Não</option>";
 $sHtmlSelect .= "<option value='t' {$sSelected}>Sim</option>";
 $sHtmlSelect .= "</select>";
 
 $sJsScript  = "var aRow   = new Array();";
 $sJsScript .= "aRow[0] = '$oDadosEscola->ed18_i_codigo - {$oDadosEscola->ed18_c_nome}';";                                                                        
 $sJsScript .= "aRow[1] = \"{$sHtmlSelect}\";";                                                                           
 $sJsScript .= "oGridEscolas.addRow(aRow);";
 
 echo $sJsScript;
	
}
?>
		
oGridEscolas.renderRows();

}

montaGrid();

function js_atualizar() {

  //verificamos todos os objetos do formulario do tipo 'select'
  var aSelect = document.getElementsByTagName("select");
  
  var sSeparador = '';
  var sDados = '';

  //percorremos os objetos
  for (i=0;i < aSelect.length; i++) {

	  //verificamos o nome do objeto e manipulamos para utilizar apenas o codigo sempre o padrao sera escola_[codigo da escola]
	  //abaixo verificamos o valor selecionado nas opcoes do select
	  sEscola = aSelect[i].name.substr(aSelect[i].name.indexOf('_')+1);
	  sValor  = document.getElementById(aSelect[i].name).options[aSelect[i].selectedIndex].value;

	  //criamos uma sting com os dados selecionados
	  sDados += sSeparador+sEscola+':'+sValor;
	  
	  sSeparador = '|';
	  
  }

  document.getElementById("dados").value = sDados;
    
  document.form1.submit();
	 
}

</script>