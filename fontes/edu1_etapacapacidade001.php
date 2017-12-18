<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta_plugin.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_serieidade_classe.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("dbforms/db_classesgenericas.php"));

db_postmemory($HTTP_POST_VARS);

$oIframeAlterarExcluir = new cl_iframe_alterar_excluir;

$oEtapaCapacidade = new cl_etapacapacidade;

$db_opcao = 1;
$db_opcao1 = 1;
$db_botao = true;

try {
	
  if (isset($incluir)) {
  
   if ($minima > $maxima) {
   	  throw new Exception("Capacidade minima não pode ser maior que a capacidade maxima");
   }
   db_inicio_transacao();
   
   $oEtapaCapacidade->etapa  = $etapa;
   $oEtapaCapacidade->minima = $minima;
   $oEtapaCapacidade->maxima = $maxima;
   $oEtapaCapacidade->incluir($etapa);
   
   db_fim_transacao();
   
  }
  
  if (isset($alterar)) {
  	
   $db_opcao = 2;
   $db_opcao1 = 3;

   if ($minima > $maxima) {
   	throw new Exception("Capacidade minima não pode ser maior que a capacidade maxima");
   }
    
   db_inicio_transacao();
   
   $oEtapaCapacidade->minima = $minima;
   $oEtapaCapacidade->maxima = $maxima;
   $oEtapaCapacidade->alterar($etapa);
   
   db_fim_transacao();
   
  }
  
  if (isset($excluir)) {
   
   $db_opcao = 3;
   $db_opcao1 = 3;
   
   db_inicio_transacao();
   
   $oEtapaCapacidade->excluir($etapa);
   
   db_fim_transacao();
   
  }
  
} catch (Exception $oErro) {
	
	db_fim_transacao(true);
	
	$oEtapaCapacidade->erro_status = "0";
	$oEtapaCapacidade->erro_msg = $oErro->getMessage();
	
}

$db_botao1 = false;
if (isset($opcao)) {
	
	$sSqlDadosEtapaCapacidade = "select etapa,
 		                                ed11_c_descr,
 		                                ed10_c_descr,
 		                                minima,
 		                                maxima,
 		                                (select true from regencia where ed59_i_serie = ed11_i_codigo limit 1) as possui_regencia
 		                           from plugins.etapacapacidade
 		                                inner join serie on serie.ed11_i_codigo = etapacapacidade.etapa
 		                                inner join ensino on ensino.ed10_i_codigo = serie.ed11_i_ensino
 		                          where etapa = {$etapa}";
    $rsDadosEtapaCapacidade = db_query($sSqlDadosEtapaCapacidade);
    db_fieldsmemory($rsDadosEtapaCapacidade, 0);

	if ($opcao=="alterar") {
	  $db_opcao = 2;
	  $db_opcao1 = 3;
	  $db_botao1 = true;
    } else if ($opcao=="excluir" || isset($db_opcao) && $db_opcao==3) {
    
    	if ($possui_regencia) {
    		
    		db_msgbox("Etapa {$ed11_c_descr} possui vinculo com uma turma.\nExclusão não permitida");
    		echo "<script>location.href='".$oEtapaCapacidade->pagina_retorno."'</script>";
    		
    	} else {
	      $db_botao1 = true;
	      $db_opcao = 3;
	      $db_opcao1 = 3;
    	}
    }
    
    
} else {
	if (isset($alterar)) {
		$db_opcao = 2;
		$db_botao1 = true;
	} else {
		$db_opcao = 1;
	}
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<center>
<table width="800" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
   <br>
   <fieldset style="width:95%"><legend><b>Cadastro de capacidade para a Etapa</b></legend>
     <form name="form1" method="post" action="">
     <center>
     <table border="0">
      <tr>
       <td nowrap title="Etapa">
        <b><?db_ancora("Etapa","js_pesquisaed259_i_serie(true);",$db_opcao1);?></b>
       </td>
       <td>
        <?db_input('etapa',10,1,true,'text',$db_opcao1," onchange='js_pesquisaed259_i_serie(false);'")?>
        <?db_input('ed11_c_descr',15,0,true,'text',3,'')?>
        <?db_input('ed10_c_descr',40,0,true,'text',3,'')?>
       </td>
      </tr>
      <tr>
       <td nowrap title="Capacidade Mínima">
        <b>Capacidade Mínima: </b>
       </td>
       <td>
        <?db_input('minima',10,1,true,'text',$db_opcao,"")?>
       </td>
      </tr>
      <tr>
       <td nowrap title="Capacidade Maxima">
        <b>Capacidade Maxima</b>
       </td>
       <td>
        <?db_input('maxima',10,1,true,'text',$db_opcao,"")?>
        </td>
      </tr>
     </table>
     <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
     <input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
     <table width='100%'>
      <tr>
       <td valign="top">
       <?
        $chavepri= array("etapa"=>@$etapa);
        $oIframeAlterarExcluir->chavepri=$chavepri;
        
        $sSql = "select etapa,
 		                ed11_c_descr,
 		                ed10_c_descr,
 		                minima,
 		                maxima
 		           from plugins.etapacapacidade
 		                inner join serie  on serie.ed11_i_codigo = etapacapacidade.etapa
 		                inner join ensino on ensino.ed10_i_codigo = serie.ed11_i_ensino";
        if (isset($etapa)) {
        	$sSql .= "where etapa <> $etapa";
        }
        $sSql .= " order by ed10_ordem, ed11_i_sequencia";
        
        $oIframeAlterarExcluir->sql          = $sSql;
        $oIframeAlterarExcluir->campos        = "etapa, ed11_c_descr, ed10_c_descr, minima, maxima";
        $oIframeAlterarExcluir->legenda       = "Registros";
        $oIframeAlterarExcluir->msg_vazio     = "Não foram encontrados registros.";
        $oIframeAlterarExcluir->textocabec    = "#DEB887";
        $oIframeAlterarExcluir->textocorpo    = "#444444";
        $oIframeAlterarExcluir->fundocabec    = "#444444";
        $oIframeAlterarExcluir->fundocorpo    = "#eaeaea";
        $oIframeAlterarExcluir->iframe_height = "200";
        $oIframeAlterarExcluir->iframe_width  = "100%";
        $oIframeAlterarExcluir->tamfontecabec = 9;
        $oIframeAlterarExcluir->tamfontecorpo = 9;
        $oIframeAlterarExcluir->formulario = false;
        $oIframeAlterarExcluir->iframe_alterar_excluir($db_opcao);
       ?>
       </td>
      </tr>
     </table>
     </center>
     </form>
   </fieldset>
  </td>
 </tr>
</table>
</center>
<?db_menu();?>
</body>
</html>

<script>
function js_pesquisaed259_i_serie(mostra) {
	
 if (mostra==true) {
  js_OpenJanelaIframe('','db_iframe_etapa','func_serieidade.php?plugin_controleturmas&funcao_js=parent.js_mostraserie1|ed11_i_codigo|ed11_c_descr|ed10_c_descr','Pesquisa',true);
 } else {
	 
  if (document.form1.etapa.value != '') {
   js_OpenJanelaIframe('','db_iframe_etapa','func_serieidade.php?plugin_controleturmas&pesquisa_chave='+document.form1.etapa.value+'&funcao_js=parent.js_mostraserie','Pesquisa',false);
  } else {
   document.form1.ed11_c_descr.value = '';
  }
  
 }
}

function js_mostraserie(chave1,codigo_ensino,descricao_ensino,erro){

 document.form1.ed11_c_descr.value = chave1;
 document.form1.ed10_c_descr.value = descricao_ensino;
 if (erro==true) {
  document.form1.etapa.focus();
  document.form1.etapa.value = '';
 }
 
}

function js_mostraserie1(chave1,chave2,chave3) {
 document.form1.etapa.value = chave1;
 document.form1.ed11_c_descr.value = chave2;
 document.form1.ed10_c_descr.value = chave3;
 db_iframe_etapa.hide();
}

</script>

<?
if (isset($incluir)) {
	
 if ($oEtapaCapacidade->erro_status=="0") {
 	
  $oEtapaCapacidade->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if ($oEtapaCapacidade->erro_campo!="") {
   echo "<script> document.form1.".$oEtapaCapacidade->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$oEtapaCapacidade->erro_campo.".focus();</script>";
  }
  
 } else {
  $oEtapaCapacidade->erro(true,true);
 }
 
}

if (isset($alterar)) {
	
 if ($oEtapaCapacidade->erro_status=="0") {
  
  $oEtapaCapacidade->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($oEtapaCapacidade->erro_campo!=""){
   echo "<script> document.form1.".$oEtapaCapacidade->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$oEtapaCapacidade->erro_campo.".focus();</script>";
  }
  
 } else {
  $oEtapaCapacidade->erro(true,true);
 }
 
}

if (isset($excluir)) {
	
 if ($oEtapaCapacidade->erro_status=="0") {
  $oEtapaCapacidade->erro(true,false);
 } else {
  $oEtapaCapacidade->erro(true,true);
 }
 
}

if (isset($cancelar)) {
 echo "<script>location.href='".$oEtapaCapacidade->pagina_retorno."'</script>";
}
?>