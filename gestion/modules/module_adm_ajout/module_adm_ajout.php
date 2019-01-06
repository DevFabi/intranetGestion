<?php
/*  --------------------------------------------------------------------------------------------

                        MODULE AJOUT MANUEL D'UNE CANDIDATURE
            
  --------------------------------------------------------------------------------------------  */
  
  
    
  //  --- initialisation de la session
  session_start();
  
  
  
  
  //  --- Initialisation des variables.
  $module =$baseClass->auto_variable('module');
  $mode = $baseClass->auto_variable('mode');
  $formvars = $baseClass->auto_variable('formvars');

  //  --- Debug
  
  //  -----------------------------------------------------------------------------------------------------
  


 /* -------------------------------------------- DEBUT MODE AJOUT MANUEL  -------------------------------------------- */


 if(!$mode) {    

 
  /* ----------------------           INITIALISATION DES VARIABLES             ---------------------*/

    $tb['formation'] = $baseClass->infos_formation();
    $tb['niveau'] = $baseClass->infos_niveau();
    $tb['provenance'] = $baseClass->infos_provenance();
    $tb['pays'] = $baseClass->infos_pays();
    $tb['rythme'] = $baseClass->infos_rythme();
    //$tb['centre'] = $baseClass->tableau_centre();
    $tb['type_demande'] = $baseClass->tableau_type_demande();

     /* ----------------------            FIN INITIALISATION DES VARIABLES            ---------------------*/

    /* Creation de l'url pour l'action du formulaire */
    $params = array('module'=>$module,
          'mode'=>'insertion');
    $url_insertion = $baseClass->creation_adm_url($params);       
  $contenu .='<br>
            <div class="panel">
              <div class="panel heading-border">
              ';

                  /* On dit que l'action se fera dans le mode insertion */
  $contenu .='   
   <form action="index.php" method="post" enctype="multipart/form-data">
    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="insertion">


                  <div class="panel-body bg-light">
                   <h3 style="text-align:center;">Ajout manuel d\'un candidat</h3>
                    <div class="section-divider mt20 mb40">
                      
                    </div>
                   ';


            /* ___________      FORMULAIRE :: CIVILITE      ___________  */

    $contenu .='   <!-- .section-divider -->
             <br><div class="section row" id="spy1">
                     <div class="section">
                      <div class="col-md-2"></div> 
                      <div class="col-md-2">
                      <label> Civilité</label> <br>
                      <label class="field select">
                        <select id="civilite" name="civilite" required="required" class="form-control">
                          <option value="">Civilite</option>
                          <option value="1">Mr</option>
                          <option value="2">Mme</option>
                        </select>
                        <i class="arrow double"></i>
                      </label>
                    </div> </div>';
            /* ___________      FORMULAIRE :: PRENOM      ___________  */

$contenu.='         
                      <div class="col-md-3">
                      <label> Prénom </label> <br>
                        <label for="firstname" class="field prepend-icon">
                          <input id="inputDefault" class="form-control" name="prenom" placeholder="Prenom*" type="text" required="required">
                         
                        </label>
                      </div>
                      <!-- end section -->';

             /* ___________      FORMULAIRE :: NOM      ___________  */         

$contenu.='            <div class="col-md-3">
                        <label> Nom </label> <br>
                        <label for="lastname" class="field prepend-icon">
                          <input id="inputDefault" class="form-control" name="nom" placeholder="Nom *" type="text" required="required">
                        </label>
                      </div>
                      <!-- end section -->
                    </div>
                    <!-- end .section row section -->';
              /* ___________      FORMULAIRE :: LIEU DE NAISSANCE     ___________  */
$contenu.='      <br><div class="row">
<div class="col-md-2"></div> 
                  <div class="col-md-3">
                  <div class="section">
                  <label> Lieu de naissance</label> <br>
                      <label class="field select">
                        <select class="form-control" id="lieu_de_naissance" name="lieu_de_naissance" required="required">
                          <option value="">Lieu de naissance</option>
                          <option value="France">France</option>
                          <option value="Europe">Europe</option>
                          <option value="Autre">Autre</option>
                        </select>
                        <i class="arrow double"></i>
                      </label>
                    </div> 
                  </div>';
              /* ___________      FORMULAIRE :: DATE DE NAISSANCE      ___________  */
              /* A ajouter : formatage de la date */
$contenu.='       <div class="col-md-2">
                <label> Date de naissance</label> <br>
                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </span>
                        <label for="date_de_naissance" class="field prepend-icon">
                        <input type="date" name="date_de_naissance" id="date_de_naissance" class="gui-input" placeholder="Date de naissance*" required="required">
                        </label>
                      </div>
                    </div>
                    <!-- end section -->
                ';

                 /* ___________      FORMULAIRE :: PAYS      ___________  */

/* Remplissage avec la base pays*/


 $contenu.='         <!-- .section-divider -->
 <div class="col-md-1"></div> 
                     <div class="col-md-2">
                     <label>Pays</label><br>
                      <label class="field select">
                      
                   <select id="pays" name="pays" class="form-control">';
                   while (list($info,$value)= each($tb['pays'])) {
         $contenu.='    <option value="'.$value['id_pays'].'">'.$value['nom_pays'].'</option>';
                         } /* Fin while*/
 $contenu.='            </select> 
                  <i class="arrow double"></i>
                      </label>
                    </div>
                    </div>
                <!-- end .section row section -->';


              /* ___________      FORMULAIRE :: ADRESSE POSTALE 1 & 2    ___________  */

$contenu.='     <br> <div class="row">
 <div class="col-md-2"></div> 
                  <div class="col-md-4">
                   <label>Adresse 1</label><br>
                    <div class="section">
                      <label for="adresse_postale" class="field prepend-icon">
                        <input id="inputDefault" class="form-control" type="text" name="adresse_postale" placeholder="Adresse postale*" required="required">
                      </label>
                    </div> </div>

                      <div class="col-md-4">
                       <label>Adresse 2 </label><br>
                      <label for="adresse_postale2" class="field prepend-icon">
                        <input id="inputDefault" class="form-control" type="text" name="adresse_postale2" placeholder="Adresse postale 2">
                      </label>
                      </div>
                      <!-- end section -->
                    </div>
                    <!-- end .section row section -->';

              /* ___________      FORMULAIRE :: CODE POSTAL      ___________  */

$contenu.='      <br> <div class="row">
<div class="col-md-2"></div> 
                  <div class="col-md-4">
                  <label>Code postal</label><br>
                    <div class="section">
                      <label for="code_postal" class="field prepend-icon">
                        <input id="inputDefault" class="form-control" type="text" name="code_postal"  placeholder="Code postal*" required="required">
                      </label>
                    </div> </div>';

              /* ___________      FORMULAIRE :: VILLE      ___________  */
              
$contenu.='        <div class="col-md-4">
<label>Ville</label><br>
                      <label for="ville" class="field prepend-icon">
                        <input id="inputDefault" class="form-control" type="text" name="ville" placeholder="Ville*" required="required">
                      </label>
                      </div>
                      <!-- end section -->
                    </div>
                    <!-- end .section row section -->';


             

              /* ___________      FORMULAIRE :: TELEPHONE & PORTABLE      ___________  */
              /* A ajouter : gestion des caracteres / espaces */
  $contenu.='                  
              <br> <div class="row">
               <div class="col-md-2"></div> 

                  <div class="col-md-4">
                  <label>Telephone</label><br>
                    <div class="section">
                      <label for="telephone" class="field prepend-icon">
                        <input id="inputDefault" class="form-control" type="text" name="telephone" placeholder="Telephone">
                      </label>
                    </div> </div>

                      <div class="col-md-4">
                      <label>Portable</label><br>
                      <label for="portable" class="field prepend-icon">
                        <input id="inputDefault" class="form-control" type="text" name="portable" placeholder="Portable">
                      </label>
                      </div>
                      <!-- end section -->
                    </div>
                    <!-- end .section row section -->';

              /* ___________      FORMULAIRE :: DERNIER ETABLISSEMENT FREQUENTE      ___________  */
$contenu.='       <br> <div class="row">
                    <div class="col-md-2"></div> 
                  <div class="col-md-4">
                  <label>Dernier établissement fréquenté</label><br>
                  
                  <div class="section">
                      <label for="dernier_etablissement_frequente" class="field prepend-icon">
                        <input id="inputDefault" class="form-control" type="text" name="dernier_etablissement_frequente" placeholder="Dernier établissement fréquenté">
                      </label>
                    </div> </div>
                    
                  <div class="col-md-4">
                  <label>E-mail</label><br>
                    <div class="section">
                      <label for="email" class="field prepend-icon">
                        <input id="inputDefault" class="form-control" type="text" name="email" placeholder="Adresse e-mail">
                      </label>
                    </div>
                     
                    </div>
                      <!-- end section -->';

              /* ___________      FORMULAIRE :: NIVEAU ACTUEL     ___________  */
    /* Remplissage des select avec la bdd niveau*/

 $contenu.='        <br><div class="section row">
                     <div class="col-md-3"></div> 
                      <div class="col-md-4">
                      <label>Niveau actuel</label> <br>
                        <label class="field select">
                        <select id="niveau_actuel" name="niveau_actuel" class="form-control">';
                        while (list($info,$value)=each($tb['niveau'])) {
$contenu.='              
                          <option value="'.$value['id_niveau'].'">'.$value['libelle_niveau'].'</option>
                        '; }/* Fin while*/

$contenu.='              </select>
                     <i class="arrow double"></i>
                      </label>
                      </div>
                      <!-- end section -->';

              /* ___________      FORMULAIRE :: NIVEAU ACTUEL OBTENU      ___________  */
 $contenu.='                     
                      <div class="col-md-4">
                      <label>Obtenu ?</label><br>
                        <label class="field select">
                        <select id="niveau_actuel_obtenu" name="niveau_actuel_obtenu" class="form-control">
                          <option value="1">Oui</option>
                          <option value="0">Non</option>
                        </select>
                        <i class="arrow double"></i>
                      </label>
                      </div>
                      <!-- end section -->';

              /* ___________      FORMULAIRE :: FORMATION DEMANDEE      ___________  */
/* Remplissage des select avec la bdd formation */


$contenu.='          </div>
                    <!-- end .section row section -->
                    <div class="section row">
                    <div class="col-md-2"></div> 
                      <div class="col-md-4">
                      <label>Formation demandée*</label><br>
                        <label class="field select">
                         <select id="formation_demande" name="formation_demande" required="required" class="form-control">';
                        while (list($info,$value)=each($tb['formation'])) {
if ($value['id_centre'] == 1) {
  $lieu = 'Evry';
} else {
$lieu = 'Paris';
}
 $contenu.='            <option value="'.$value['id_formation'].'">'.$value['libelle_formation'].' - '.$lieu.'</option>
                        '; }/* Fin while*/
 $contenu.='            </select> 
                       <i class="arrow double"></i>
                      </label>
                      </div>
                      <!-- end section -->';


              /* ___________      FORMULAIRE :: RYTHME      ___________  */

  /* Remplissage des select avec la bdd rythme*/ 
                 
 $contenu.='             
                      <div class="col-md-4">
                      <label>Rythme*</label><br>
                        <label class="field select">
                        <select id="rythme" name="rythme" class="form-control">';
                        while (list($info,$value)=each($tb['rythme'])) {  
$contenu.='              <option value="'.$value['id_rythme'].'">'.$value['libelle_rythme'].'</option>';
                        } /* Fin while*/
$contenu.='             </select>
                        <i class="arrow double"></i>
                      </label>
                      </div>
                      <!-- end section -->
                    </div>
                    <!-- end .section row section -->';

              /* ___________      FORMULAIRE :: PROVENANCE      ___________  */

 /* Remplissage des select avec la bdd provenance */                   
 
$contenu.='       <br><div class="section row">
                    <div class="col-md-3"></div> 
                      <div class="col-md-4">
                      <label>Comment avez-vous connu notre ecole ?*</label>
                        <label class="field select">
                        <select id="provenance" name="provenance" class="form-control">';
                        while (list($info,$value)=each($tb['provenance'])) { 
                     
$contenu.='                <option value="'.$value['id_provenance'].'">'.$value['libelle_provenance'].'</option>';
}/* Fin while */
$contenu.='              </select>
                        <i class="arrow double"></i>
                      </label></div></div>
                      <!-- end section -->';


$contenu.='          </div>
                  <!-- end .form-body section -->
                  <div class="panel-footer text-right">';

 $contenu .='<input hidden="hidden" name="type_demande" value="2">
            <button type="submit" class="button btn-primary" value="submit" name="add"> Valider </button>';

 $contenu .='       <button type="reset" class="button"> Annuler </button>';
 $contenu .='                  
                  </div>
                  <!-- end .form-footer section -->
                </form>

              </div> 

            </div>
            <!-- end: .admin-form -->

        </div>
        <!-- end: .tray-center -->

 ';


} /* Fin du mode affichage */




/* MODE INSERTION */

if ($mode == "insertion") {

 if (isset($_POST['add'])) {      /* Si le formulaire a ete envoyé */

// --- Calcul de la session correspondante
// --- Changement de session au 1er decembre
$today = date("Y-m-d"); // Date du jour a l'envois du formulaire
$params= array();
$session = $baseClass->infos_session($params); // Recuperation des sessions qui contiennent leur date de debut et de fin

    foreach ($session as $key => $value) {
        if ($today > $value['debut_session'] && $today < $value['fin_session']) { // Si today est dans une des sessions
          $session_candidat = $value['id_session']; // On attribut cette session au candidat
        }
    }

// --- Recuperation des données et liaison avec la bdd 
    $tb_demande_externe = array(
                              'timestamp' => date('Y-m-d H:i:s'),
                              'civilite' => $_POST['civilite'],
                              'nom' => strtoupper($_POST['nom']),
                              'prenom' => ucfirst(strtolower($_POST['prenom'])),
                              'lieu_de_naissance'=> $_POST['lieu_de_naissance'],
                              'date_de_naissance'=> $_POST['date_de_naissance'],
                              'adresse_postale' => $_POST['adresse_postale'],
                              'adresse_postale2' => $_POST['adresse_postale2'],
                              'code_postal' => $_POST['code_postal'],
                              'ville' => $_POST['ville'],
                              'telephone' => $_POST['telephone'],
                              'portable' => $_POST['portable'],
                              'email' => $_POST['email'],
                              'dernier_etablissement_frequente' => $_POST['dernier_etablissement_frequente'],
                              'id_niveau' => $_POST['niveau_actuel'],
                              'niveau_actuel_obtenu' => $_POST['niveau_actuel_obtenu'],
                              'id_formation' => $_POST['formation_demande'],
                              'id_pays' => $_POST['pays'],
                             /* 'id_centre' => $_POST['centre'],*/
                              'id_rythme' => $_POST['rythme'],
                              'id_provenance' => $_POST['provenance'],
                              'id_session' => 4,
                              'id_type_demande' => 8
                            );

      // --- Recherche des DOUBLONS 
      $params = array('nom' => $_POST['nom'],
                      'prenom' => $_POST['prenom']);
      $doublon = $baseClass->doublon_nom_prenom($params);

  if ($doublon <= 0) {
  // --- Tables
  $tbl_demande_doc = $baseClass->nom_table('demande_documentation');
  $tbl_current_phase = $baseClass->nom_table('current_phase');
  $tbl_phase = $baseClass->nom_table('phase_par_eleve');

  // --- Ajout du candidat dans dc_demande_documentation
  $params = array('cle_primaire'=>'id_demande');
  $requete = $baseClass->creation_requete_insertion($tbl_demande_doc,$tb_demande_externe,$params);
  $ok = $baseClass->requete_sql($db, $requete);

  // --- Tableau pour l'historique des phases dans dc_phase_par_eleve
  $tb_nouvelle_phase = array(
                        'id_demande_doc'=> $baseClass->lastInsertId(), // Recupère le dernier id inseré
                        'id_phase'=> 1, // La phase est "demande de documentation envoyee"
                        'id_formation' => $_POST['formation_demande'],
                        'date_attribution'=> date('Y-m-d')
                      );

  // --- Tableau pour la phase actuelle dans dc_current_phase
  $tbCurrentPhase = array('id_phase'=> 1,
                          'id_demande' => $baseClass->lastInsertId());

  // --- Ajout dans dc_current_phase  
  $params = array('cle_primaire'=>'id_current_phase');
  $requete = $baseClass->creation_requete_insertion($tbl_current_phase, $tbCurrentPhase, $params);
  $ok = $baseClass->requete_sql($db, $requete);

  // --- Ajout dans dc_phase_par_eleve
  $params = array('cle_primaire'=>'id');
  $requete = $baseClass->creation_requete_insertion($tbl_phase,$tb_nouvelle_phase,$params);
  $ok = $baseClass->requete_sql($db, $requete);
  header("Refresh:0; url="); 
    } else {  
      echo "Le candidat n'a pas ete ajoute car nom et le prenom existent déjà";   
  }
 
  
}   /* Fin du mode insertion */

}
 /* -------------------------------------------- FIN MODE AJOUT MANUEL  -------------------------------------------- */



         
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


