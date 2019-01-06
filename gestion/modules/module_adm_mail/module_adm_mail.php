<?php
/*  --------------------------------------------------------------------------------------------

                        MODULE ADMINISTRATION DES EMAILS 
            
  --------------------------------------------------------------------------------------------  */
  
  
    
  //  --- initialisation de la session
  session_start();
  
  
  
  
  //  --- Initialisation des variables.
  $module =$baseClass->auto_variable('module');
  $mode = $baseClass->auto_variable('mode');
  $formvars = $baseClass->auto_variable('formvars');

  //  --- Debug
  
  //  -----------------------------------------------------------------------------------------------------
  


  /*  --- BEGIN: Contenu  -------------------------------------------------------------------
  ------------------------------------------------------------------------------------------- */    
if (!$mode) {
$tbmail = $baseClass->tableau_email();
$contenu .='
<div class="row">
 <div class="col-md-3"></div>
  <div class="col-md-6">
    <h2 style="text-align:center;">Modification des e-mail</h2>

    <p>Il est possible de modifier le contenu et les e-mails reçevant chaque formulaire envoyé.</p>
    <p>Vous pouvez réduire chaque mail en cliquant sur le <b>-</b></p>
  </div>
</div>
';

$params = array('module' => $module,
                'mode' => 'modification_mail');
$url_modification_mail = $baseClass->creation_adm_url($params); 

foreach ($tbmail as $mail) {
 
  $edit = $mail['id_mail'];
$contenu .='
<div class="row">
<div class="col-md-2"> </div>
<div class="col-md-8">
 <div class="panel-group accordion" id="accordion">
  <div class="panel">
    <div class="panel-heading">
      <a class="accordion-toggle accordion-icon link-unstyled" data-toggle="collapse" data-parent="#accordion" href="#'.$mail['id_mail'].' " aria-expanded="false">
        '.$mail['sujet'].' 
      </a>
    </div>
    <div id="'.$mail['id_mail'].'" class="panel-collapse collapse in" style="" aria-expanded="false">
      <div class="panel-body">
      <a href="gestion/index.php?module=mail&mode=modification_mail&edit_id='.$edit.'"><button type="button" class="btn btn-sm btn-success">
          <span class="glyphicon glyphicon-trash fs11 mr5"> </span> Modifier</button></a>
      <p>Expediteur :'.$mail['exp_mail'].' - '.$mail['exp_nom'].'</p>
      <p>Message :</p> <br> '.$mail['message'].' 

      </div>
    </div>
  </div>
</div>
</div>
</div>';}
}


if ($mode == "modification_mail") {

  $edit_id =$baseClass->auto_variable('edit_id');
  $module =$baseClass->auto_variable('module');
  $mode = $baseClass->auto_variable('mode');

  $params = array('module' => $module,
                  'mode' => 'confirmation_modification_mail',
                  'edit_id'=> $edit_id );
  $url_confirmation_modification_mail = $baseClass->creation_adm_url($params);

  $tbmail = $baseClass->tableau_email($edit_id);


  $contenu .='
    <form action="index.php" method="post" enctype="multipart/form-data">
    <input name="edit_id" type=hidden value="'.$edit_id.'">
    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="confirmation_modification_mail">

              <div class="section">
                <div class="row">
                    <div class="col-md-2"></div>
                      <div class="col-md-8">
                        <h2>Modification - '.$tbmail[0]['sujet'].' </h2>
                        <p>Pour avoir un aperçu réel, appuyez sur le bouton violet.</p><br><br>
                      </div> 
                </div> 
           

               <div class="row">
                <div class="col-md-2"></div>
                    <div class="col-md-4">
                      <p>Adresse d\'expedition : </p>
                       <input type="text" class="form-control" name="formvars[exp_mail]" value="'.$tbmail[0]['exp_mail'].'"/>
                    </div> 
                    <div class="col-md-4">
                      <p>Nom expediteur : </p>
                      <input type="text" class="form-control" name="formvars[exp_nom]" value="'.$tbmail[0]['exp_nom'].'"/>
                  </div> 
                </div> 
                <div class="row">
                  <div class="col-md-2"></div>
                    <div class="col-md-8"><br>
                      <p>Message de l\'e-mail : </p> <button type="button" class="btn btn-alert btn-block" onclick="apercu();" data-toggle="modal" data-target="#apercuModal">Aperçu du mail</button>
                        <textarea class="form-control" id="textArea2" rows="39" name="formvars[message]" >'.htmlspecialchars($tbmail[0]['message']).'</textarea><br>
                        <button class="btn btn-primary" type="submit">Valider</button>
                  </div> 
                </div> 
              </div>
    </form>


<!-- Modal aperçu e-mail -->
<div class="modal fade" id="apercuModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Aperçu du mail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div id="apercu"></div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<!-- Fin Modal aperçu e-mail -->
    ';

}

if ($mode == "confirmation_modification_mail") {
  
      $edit_id = $baseClass->auto_variable('edit_id');
      $formvars = $baseClass->auto_variable('formvars');
      $tbl_mail = $baseClass->nom_table('email');
      $params = array('cle_primaire'=>'id_mail');

      if ($edit_id  > 0) {
        $requete = $baseClass->creation_requete_modification($tbl_mail,
        $formvars, $edit_id, $params);
        $ok = $baseClass->requete_sql($db, $requete);
        header("Refresh:0; url=gestion/index.php?module=mail");
       
      }

}

  

 


         
// --- ajout du contenu dans le bloc principal
$tb_blocs['bloc_adm_principal'] .= $contenu;

// --- meta tags par défaut
$tb_blocs['meta_title'] = 
  $baseClass->get_parametre('meta_title');
$tb_blocs['meta_description'] =
  $baseClass->get_parametre('meta_description');
$tb_blocs['meta_keywords'] =
  $baseClass->get_parametre('meta_keywords');

?>


