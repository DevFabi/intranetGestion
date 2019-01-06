<?php
/*  --------------------------------------------------------------------------------------------

                        MODULE EXPORT 
            
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
 



  // --------------------------------------------- BLOC EXPORT ACTUEL ----------------------------------------------
  // --------------------------------------------- BLOC EXPORT ACTUEL ----------------------------------------------

if (!$mode) {
 
  $tb_phases = $baseClass->infos_phase();
  $params = array();
  $tb_sessions = $baseClass->infos_session($params);
  $tb_cns = $baseClass->infos_cns();
  $tb_formations = $baseClass->infos_formation();
   
      $params = array('module' => $module,
                      'mode' => 'validation_export_test');
      $url_validation_export = $baseClass->creation_adm_url($params); 


 $contenu .='<div class="row">
 <div class="col-md-12">
  <h3> Export des demandes de dc</h3> <br>
 ';

 $contenu .='<form action="index.php" method="post" enctype="multipart/form-data">
    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="validation_export_test">
    <br>
    ';


 $contenu.='  <br><br><div class="col-md-3"></div>     <div class="col-md-3">
     <label>Debut :</label><input type="date" name="datedebut" id="datedebut" class="form-control" required ="true">  </div>
                     <div class="col-md-3"> <label>Fin :</label><input type="date" name="datefin" id="datefin" class="form-control" required ="true" > </div>
                     </div>

                     <div class="row">
                     <div class="col-md-5"></div> 
                      <div class="col-md-4">
                      <br><br>
                      
                        <label class="field select">
                        <select id="session" name="session" class="form-control">';
                        while (list($info,$value)=each($tb_sessions)) { 
                     
 $contenu.='                <option value="'.$value['id_session'].'">'.$value['libelle'].'</option>';
}/* Fin while */
 $contenu.='              </select>
                        <i class="arrow double"></i>
                      </label>  </div>
                      <!-- end section -->
                      </div> <!-- row -->';

 $contenu.='<br><div class="row">
              <div class="col-md-3"></div>
              <div class="col-md-3">
                      
                        <label class="field select">
                        <select multiple id="phase" name="phase[]" class="form-control" size="6">';
                        while (list($info,$value)=each($tb_phases)) { 
                     
$contenu.='                <option value="'.$value['id_phase'].'">'.$value['libelle_phase'].'</option>';
}/* Fin while */
$contenu.='              </select>
                        <i class="arrow double"></i>
                      </label></div>
                      <!-- end section -->';
$contenu.='<div class="col-md-4">
                      
                        <label class="field select">
                        <select id="cn" name="cn" class="form-control">';
                        $contenu.=' <option value="0">Tous les cns </option>';
                        while (list($info,$value)=each($tb_cns)) { 
                     
$contenu.='                <option value="'.$value['id_cn'].'">'.$value['nom_cn'].' '.$value['prenom_cn'].'</option>'; }/* Fin while */


$contenu.='              </select>
                        <i class="arrow double"></i>
                      </label></div>
                      </div> <!-- row -->';
$contenu.='<br><div class="row">
<div class="col-md-3"></div>
<div class="col-md-7">
                      
                        <label class="field select">
                        <select id="formation" name="formation" class="form-control">';
                         $contenu.=' <option value="0">Toutes les formations </option>';
                        while (list($info,$value)=each($tb_formations)) { 
                         $valueCentre= $baseClass->tableau_centre($value['id_centre']);
                
$contenu.='                <option value="'.$value['id_formation'].'">'.$value['libelle_formation'].' - '.$valueCentre[0]['lieu'].' </option>'; }/* Fin while */


$contenu.='              </select>
                        <i class="arrow double"></i>
                      </label>
                      <!-- end section --></div>
                      </div> <!-- row -->';

$contenu.='
<div class="row">
  <div class="col-md-6"></div>
    <div class="col-md-6">
      <button class="btn btn-primary" type="submit" name="add">Valider</button>
    </div>
</div>
</form>';  
$contenu.='<div class="row">
  <div class="col-md-3"></div>
    <div class="col-md-6">
    </br></br>
        <h4><b>Aide pour l\'export</b></h4>
        <p>Pour selectionner la date de fin : mettez toujours un jour de plus.</br>
        EXEMPLE : Si vous voulez les candidats <b>du 01/06/2018 au 10/06/2018</b>, il faut rentrer en date de fin <b>11/06/2018</b></p>
        <p>Ne pas oublier de selectionner la bonne session</p>
        <p>Il est possible de selectionner plusieurs phases ( ctrl + clic )</p>
     
  </div>
</div>
';

 } // Fin mode affichage

 if ($mode == 'validation_export_test') {
  // Recuperer le resultat via les criteres
  $d = " 00:00:00";
  $datedebut = $_POST['datedebut'].$d;
  $datefin = $_POST['datefin'].$d;
  $tbphase = $_POST['phase'];
  $tbformation = $_POST['formation'];


   if(isset($_POST['add'])){ // Si le formulaire a bien ete envoye
    $ligneencours= array();

     //foreach ($tbformation as $key2 => $formation) {
      foreach ($tbphase as $key => $value) { 
          $params = array('session' => $_POST['session'],
                        'cn' => $_POST['cn'],
                        'formation' => $_POST['formation'],
                        'phase' => $value,
                        'datedebut' => $datedebut,
                        'datefin' => $datefin);

          $ligneencours[] = $baseClass->export($params); // Contient Un tableau avec des tableaux pour chaque phase
  }
//}
//print_r($ligneencours); exit;

  
      $export = array();
 
     foreach ($ligneencours as $ligne) {
      foreach ($ligne as $key => $value) {
    
        
     $params = array('id_cn' => $value["id_cn"]);
     $cn = $baseClass->infos_cns($params);

     $phase = $baseClass->infos_phase_libelle($value["id_phase"]); 

     $params = array('id_formation' => $value['id_formation']);
     $formation = $baseClass->infos_formation($params);

     $centre = $baseClass->tableau_centre($formation[0]['id_centre']);

     if ($value["civilite"] == 1 || $value["civilite"] ==  "Mr") {
  $civilite = "Mr";
}else{
  $civilite = "Mme";
}

//--- Format du numero de portable
  $premierChiffre=substr($value["portable"],0,1); // On prend le premier chiffre du numero
  $ligneencour["portable"]=str_replace(' ','',$value["portable"]); // On enleve les espaces

  if ($premierChiffre != 0) { // Si le premier numero du tel n'est pas 0 on l'ajoute
    $portable = chunk_split('0'.$ligneencour["portable"], 2, ' '); // Pour un format 06 00 00 00 00
  }else{
    $portable = chunk_split($ligneencour["portable"], 2, ' '); // Sinon on ajoute que les espaces
  }

//-- Les lignes de variables du tableau
    $export[] = array($civilite,$value["nom"], $value["prenom"],$value["adresse_postale"],$value["adresse_postale2"], $value["code_postal"], $value["ville"],$portable,$value["email"], $cn[0]['prenom_cn'].' '.$cn[0]['nom_cn'] ,$phase[0]['libelle_phase'], $formation[0]['libelle_formation'], $centre[0]['lieu']);

 }}


    // Nom du fichier et delimiteur entre chaque entrées
    $chemin = 'export.csv';
    $delimiteur = ';'; // Pour une tabulation, $delimiteur = "t";
    $fichier_csv = fopen($chemin, 'w+');
    fprintf($fichier_csv, chr(0xEF).chr(0xBB).chr(0xBF));

    // On affiche une fois l'entête sans boucle
    $entetes = array('Civilite', 'Nom', 'Prenom', 'adresse_postale','adresse_postale2', 'code_postal', 'ville', 'portable', 'email', 'cn', 'phase', 'formation', 'lieu');
    fputcsv($fichier_csv, $entetes, $delimiteur);

    foreach($export as $ligneaexporter){
        fputcsv($fichier_csv, $ligneaexporter, $delimiteur);
    }

    // fermeture du fichier csv
    fclose($fichier_csv);
$contenu.=' <br><br> <div class="col-md-4"></div><div class="col-md-6">
<a href="export.csv" target="_blank"><button class="btn btn-primary">Télécharger le fichier csv des exports</button></a></div>'; 

    }
 }

  // --------------------------------------------- FIN BLOC EXPORT ACTUEL ----------------------------------------------
  // --------------------------------------------- FIN BLOC EXPORT ACTUEL ----------------------------------------------





  // --------------------------------------------- ANCIEN EXPORT  ----------------------------------------------
  // --------------------------------------------- ANCIEN EXPORT  ----------------------------------------------

 if($mode == 'ancien_export') { 

  $tb_phases = $baseClass->infos_phase();
  $params = array();
  $tb_sessions = $baseClass->infos_session($params);
  $tb_cns = $baseClass->infos_cns();
  $tb_formations = $baseClass->infos_formation();
   
      $params = array('module' => $module,
                      'mode' => 'validation_export');
      $url_validation_export = $baseClass->creation_adm_url($params); 


 $contenu .='<div class="col-md-12">';

 $contenu .='<form action="index.php" method="post" enctype="multipart/form-data">
    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="validation_export">
    <h3> Export des demandes de dc</h3> <br>
    ';


 $contenu.='       <div class="col-md-1"></div><div class="col-md-3">
     <label>Debut :</label><input type="date" name="datedebut" id="datedebut" class="form-control" required ="true"> 
                      <label>Fin :</label><input type="date" name="datefin" id="datefin" class="form-control" required ="true" > </div>
                      <div class="col-md-2">
                      <label>Session : </label>
                        <label class="field select">
                        <select id="session" name="session" class="form-control">';
                        while (list($info,$value)=each($tb_sessions)) { 
                     
 $contenu.='                <option value="'.$value['id_session'].'">'.$value['libelle'].'</option>';
}/* Fin while */
 $contenu.='              </select>
                        <i class="arrow double"></i>
                      </label>  </div>
                      <!-- end section -->';

 $contenu.='<div class="col-md-2">
                      <label>Phase : </label>
                        <label class="field select">
                        <select id="phase" name="phase" class="form-control">';
                        while (list($info,$value)=each($tb_phases)) { 
                     
$contenu.='                <option value="'.$value['id_phase'].'">'.$value['libelle_phase'].'</option>';
}/* Fin while */
$contenu.='              </select>
                        <i class="arrow double"></i>
                      </label></div>
                      <!-- end section -->';
$contenu.='<div class="col-md-2">
                      <label>cn : </label>
                        <label class="field select">
                        <select id="cn" name="cn" class="form-control">';
                        $contenu.=' <option value="All">Tous les cns </option>';
                        while (list($info,$value)=each($tb_cns)) { 
                     
$contenu.='                <option value="'.$value['id_cn'].'">'.$value['nom_cn'].' '.$value['prenom_cn'].'</option>'; }/* Fin while */


$contenu.='              </select>
                        <i class="arrow double"></i>
                      </label></div>';
$contenu.='<div class="col-md-2">
                      <label>Formation : </label>
                        <label class="field select">
                        <select id="formation" name="formation" class="form-control">';
                           $contenu.=' <option value="All">Toutes les formations</option>';  
                        while (list($info,$value)=each($tb_formations)) { 
                         $valueCentre= $baseClass->tableau_centre($value['id_centre']);
                
$contenu.='                <option value="'.$value['id_formation'].'">'.$value['libelle_formation'].' - '.$valueCentre[0]['lieu'].' </option>'; }/* Fin while */


$contenu.='              </select>
                        <i class="arrow double"></i>
                      </label></div>
                      <!-- end section --></div>';

$contenu.='
<div class="row">
  <div class="col-md-6"></div>
    <div class="col-md-6">
      <button class="btn btn-primary" type="submit" name="add">Valider</button>
    </div>
</div>
</form>';  

$contenu.='</br></br>
        <h4><b>Aide pour l\'export</b></h4>
        <p>Pour selectionner la date de fin : mettez toujours un jour de plus.</br>
        EXEMPLE : Si vous voulez les candidats <b>du 01/06/2018 au 10/06/2018</b>, il faut rentrer en date de fin <b>11/06/2018</b></p>
        <p>Ne pas oublier de selectionner la bonne session</p>
     

';

 } // Fin mode affichage

 if ($mode == 'ancien_validation_export') {
  // Recuperer le resultat via les criteres
  $d = " 00:00:00";
  $datedebut = $_POST['datedebut'].$d;
  $datefin = $_POST['datefin'].$d;

$session = $_POST['session'];
$phase = $_POST['phase'];
$cn = $_POST['cn'];
$formation = $_POST['formation'];


   if(isset($_POST['add'])){
      
     // Traitement bdd   
      $ligneencours = $baseClass->export($session, $phase, $cn, $datedebut, $datefin, $formation);
      $export = array();
      

     foreach ($ligneencours as $ligneencour) {

     $params = array('id_cn' => $ligneencour["id_cn"]);
     $cn = $baseClass->infos_cns($params);
     $phase = $baseClass->infos_phase_libelle($_POST['phase']); // A changer pour phase multiple
     $params = array('id_formation' => $ligneencour['id_formation']);
     $formation = $baseClass->infos_formation($params);

     $centre = $baseClass->tableau_centre($formation[0]['id_centre']);
    //var_dump($centre['lieu']); exit;
     //$centre['lieu'];
if ($ligneencour["civilite"] == 1) {
  $civilite = "Mr";
}else{
  $civilite = "Mme";
}

//--- Format du numero de portable
  $premierChiffre=substr($ligneencour["portable"],0,1); // On prend le premier chiffre du numero
  $ligneencour["portable"]=str_replace(' ','',$ligneencour["portable"]); // On enleve les espaces

  if ($premierChiffre != 0) { // Si le premier numero du tel n'est pas 0 on l'ajoute
    $portable = chunk_split('0'.$ligneencour["portable"], 2, ' '); // Pour un format 06 00 00 00 00
  }else{
    $portable = chunk_split($ligneencour["portable"], 2, ' '); // Sinon on ajoute que les espaces
  }

    // Les lignes de variables du tableau
    $export[] = array($civilite,$ligneencour["nom"], $ligneencour["prenom"],$ligneencour["adresse_postale"],$ligneencour["adresse_postale2"], $ligneencour["code_postal"], $ligneencour["ville"],$portable,$ligneencour["email"], $cn[0]['prenom_cn'].' '.$cn[0]['nom_cn'] ,$phase[0]['libelle_phase'], $formation[0]['libelle_formation'], $centre[0]['lieu']);
}
    // Nom du fichier et delimiteur entre chaque entrées
    $chemin = 'export.csv';
    $delimiteur = ';'; // Pour une tabulation, $delimiteur = "t";
    $fichier_csv = fopen($chemin, 'w+');
    fprintf($fichier_csv, chr(0xEF).chr(0xBB).chr(0xBF));

    // On affiche une fois l'entête sans boucle
    $entetes = array('Civilite', 'Nom', 'Prenom', 'adresse_postale','adresse_postale2', 'code_postal', 'ville', 'portable', 'email', 'cn', 'phase', 'formation', 'lieu');
    fputcsv($fichier_csv, $entetes, $delimiteur);
    //print_r($entetes);

    foreach($export as $ligneaexporter){
        fputcsv($fichier_csv, $ligneaexporter, $delimiteur);
        //print_r($ligneaexporter);
    }

    // fermeture du fichier csv
    fclose($fichier_csv);
$contenu.=' <br><br> <div class="col-md-4"></div><div class="col-md-6">
<a href="export.csv" target="_blank"><button class="btn btn-primary">Télécharger le fichier csv des exports</button></a></div>'; 

    }
 }

   // --------------------------------------------- FIN ANCIEN EXPORT  ----------------------------------------------
   // --------------------------------------------- FIN ANCIEN EXPORT  ----------------------------------------------
 
         
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