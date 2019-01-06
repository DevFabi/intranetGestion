<?php
/*	--------------------------------------------------------------------------------------------

										MODULE dc - ESPACE CANDIDATS
						
	--------------------------------------------------------------------------------------------	*/
	
	// 	---	initialisation de la session
	session_start();
	
	//	--- Tableau des candidats.
	$params = array();
	$tb_candidats = $baseClass->tableau_candidats($params);
	
	//	--- Initialisation des variables.
	$module =$baseClass->auto_variable('module');
	$mode = $baseClass->auto_variable('mode');
	$formvars = $baseClass->auto_variable('formvars');

	//	--- Debug
	
	//	-----------------------------------------------------------------------------------------------------
	


	/*	--- BEGIN: Contenu	-------------------------------------------------------------------
	-------------------------------------------------------------------------------------------	*/	 

// ---- SCRIPT AJOUT DE LA PHASE ACTUELLE 

/*if ($mode == 'currentPhase') {  
$PhaseActuelleCandidat = array();
foreach ($tb_candidats as $key => $value) {
 $params = array('id_candidat' => $value['id_demande'],
                 'current_phase' => 1 );
 $PhaseActuelleCandidat[] = $baseClass->check_phase($params);
 } 
  foreach ($PhaseActuelleCandidat as $key => $value) {
    // --- Parametres
    $tbl_current_phase = $baseClass->nom_table('current_phase');
    $valeursCurrent = array('id_phase'=> $value[0]['id_phase'],
                            'id_demande' => $value[0]['id_demande']);
    $params = array('cle_primaire'=>'id_current_phase');
    $requete = $baseClass->creation_requete_insertion($tbl_current_phase, $valeursCurrent, $params);
    $ok = $baseClass->requete_sql($db, $requete);
}  

// ---- FIN SCRIPT AJOUT DE LA PHASE ACTUELLE 

} // Fin mode currentPhase*/
	
	//	--- Mode affichage.
	if(!$mode || $mode == 'liste') {	

	$contenu .='
	  <!-- Begin: Content -->
      <section id="content" class="table-layout animated fadeIn">

        <!-- begin: .tray-center -->
        <div class="tray tray-center">';
	//	---
	$contenu .='
            <!-- Begin: Content Header -->
            <div class="content-header">
              <h2> <b>Demande de dc</b></h2>
              <p class="lead">Cet espace permet de gérer les demandes de dc</p>


        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <!--  ////////////////////////////       BOUTON AJOUT MANUEL     ///////////////////////// -->
<a href="/index.php?module=ajout"><button type="button" class="btn btn-rounded btn-system btn-block">AJOUTER UN CANDIDAT</button></a>
            </div></div></div>
     
  Les différentes phases :';
 $phases = $baseClass->infos_phase();
foreach ($phases as $nom_phase) {
    $contenu .=' <span class="'.$nom_phase["couleur"].'">'.$nom_phase["libelle_phase"].'</span> ';
  }
		
	$contenu .='
          <!-- recent orders table -->
          <div class="panel">';


		/* CREATION DE L'URL POUR LE MODE IMPORTATION */
		 $params = array('mode'=>'importation',
		         		     'module'=>$module);
		 $url_importation = $baseClass->creation_adm_url($params);

     /* CREATION DE L'URL POUR LE MODE AJOUT MANUEL */
     $params = array('mode'=>'ajout_manuel',
                     'module'=>'ajout');
     $url_ajout_manuel = $baseClass->creation_adm_url($params);


	$contenu.='

            <div class="panel">
			  <div class="panel-heading panel-visible">
				<span class="panel-title">          <span class="glyphicon glyphicon-tasks"></span>Demandes de doc</span>
				<div class="widget-menu pull-right mr10">
				  <div class="btn-group">
  <!--  ////////////////////////////       BOUTON IMPORT dil     ///////////////////////// -->
         <a href="/index.php?module=dc&mode=dil" class="btn btn-xs btn-alert btn-sm fw600 ml10">
      <span class="fa fa-plus pr5"></span> dil </a>
          </div>
      
    
			<div class="btn-group">

	<!--  //////////////////////////// 			BOUTON IMPORTER 			/////////////////////////-->

		   <a href="'.$url_importation.'" class="btn btn-xs btn-success btn-sm fw600 ml10">
           <!-- ON DONNE LE MODE IMPORTATION AU BOUTON -->
           <input name="mode" type=hidden value="importation"> 
           <input name="module" type=hidden value="'. $module. '"> 
			<span class="fa fa-plus pr5"></span> Import ancienne appli </a>
				  </div>
				</div>
			  </div>
				
                <div class="panel-body pn">
                  <table class="table table-striped table-hover display admin-form theme-warning tc-checkbox-1 fs13" id="datatable5" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>Phase</th>
                        <th>Demande</th>
                        <th>Nom</th>
                        <th>Pays</th>
                        <th>Classe</th>
						<!-- <th>Email</th>-->
						<th>Conseiller</th>
						<th>supp</th>
                      </tr>
                    </thead>
                    <tbody>';


					
	reset($tb_candidats);
	while (list($key, $val) = each($tb_candidats)) {

  $id_candidat = $key;
  /* Phases du candidat */
              /* Recherche des differentes phases && recuperation de la phase la plus RECENTE*/
              $phase_candidat = $baseClass->infos_phase_candidat($id_candidat);
              /* Recherche du libelle de la phase */
              $nom_phase = $baseClass->infos_phase_libelle($phase_candidat[0]['id_phase']);
              $phase_candidat = $nom_phase[0]["libelle_phase"];
              $params = array('id_formation' => $val['id_formation']);
			        $formation = $baseClass->infos_formation($params);
			        $conseiller = $baseClass->infos_conseiller_candidat($val['id_conseiller']);
 
			

	  $centre = $formation[0]['id_centre'];
	  if ($centre == 1) {
	    $centre = 'Evry';
	  }else{
	    $centre = 'Paris';
	  }

	//	-- Création de l'url d'édition.
	$params = array('edit_id'=>$key,
				        	'mode'=>'affiche_candidat',
				        	'module'=>$module);
	$url_edit = $baseClass->creation_adm_url($params);

	// 	-- Création de l'url pour la suppression.
	$params0 = array('module'=>$module,
					         'mode'=>'confirmation_suppression',
					         'id'=>$val['id_demande'],
					         'edit_id'=>$key);
	$url_suppression = $baseClass->creation_adm_url($params0);	

//  -- Recuperation des informations
  $params = array('id_pays' => $val['id_pays']);
  $pays = $baseClass->infos_pays($params);
//  -- CENTRE + (Renseignement OU Candidature) 
  $infos_type_demande = $baseClass->infos_type_demande($val['id_type_demande']);
 

		$contenu .='
                      <tr>
					    <td class="">
                        <label class="option block mn">
                        <span class="'.$nom_phase[0]["couleur"].'">'.$nom_phase[0]["libelle_court"].'</span>                    
                        </label>
				        </td>
                <td>'.$infos_type_demande[0]['type_demande'].'</td>
                        <td>
                        <a href="'.$url_edit.'">'.$val['nom'].' '.$val['prenom'].'</a>
                        </td>
                        <td>'.$pays[0]['nom_pays'].'</td>
                        <td>'.$formation[0]['libelle_formation'].' - '.$centre.'</td>
					   <!-- <td>'.$val['email'].'</td> -->
					    <td>'.$conseiller[0]['nom_conseiller'] .' '.$conseiller[0]['prenom_conseiller'].'</td>
                      
					             	<td class="text-right w50"> 
            						<a class="btn btn-danger btn-xs light fw600 ml10" onClick="DelCandidat('.$val['id_demande'].')">
            						<span class="fa fa-times pr5"></span> Suppression</a>						
            			</td>	 </tr>';

	}
		
		$contenu .='
 
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
        <!-- end: .tray-center -->';
	}
	
	//	--- Mode affichage des details sur un candidat
	if($mode == 'affiche_candidat'){
		$edit_id = $baseClass->auto_variable('edit_id');
    $params= array('id_demande' => $edit_id);
		$infos_candidat = $baseClass->infos_candidat($params);


                    /* On recupere les donnees du candidat */
                   
                    foreach ($infos_candidat as $key => $value) {

            /*--------------------- RECUPERATION DES LIBELLES CORRESPONDANT AUX ID ---------------------*/
                      $params = array('id_session' => $value['id_session']);
                      $session_candidat = $baseClass->infos_session($params);
                      /* Contient le type de la demande et le nom du centre*/
                      $infos_type_demande = $baseClass->infos_type_demande($value['id_type_demande']);
                      /* Contient le libelle de la formation */
                      $params = array('id_formation' => $value['id_formation']);
                      $infos_formation = $baseClass->infos_formation($params);
                      $formation_liste = $baseClass->infos_formation();
                      /* Contient le libelle du pays */
                      $params = array('id_pays' => $value['id_pays']);
                      $infos_pays = $baseClass->infos_pays($params);
                      /* Contient le libelle de la provenance */
                      $params = array('id_provenance' => $value['id_provenance']);
                      $infos_provenance = $baseClass->infos_provenance($params);
                      /* Contient le libelle du rythme ( alternance / initial ) */
                      $params= array('id_rythme' => $value['id_rythme']);
                      $infos_rythme = $baseClass->infos_rythme($params);
                      $rythme_liste = $baseClass->infos_rythme();
                      /* Contient le libelle du niveau actuel */
                      $params = array('id_niveau' => $value['id_niveau']);
                      $infos_niveau = $baseClass->infos_niveau($params);

                      $centre = $infos_formation[0]['id_centre'];
                      if ($centre == 1) {
                        $centre = 'Evry';
                      }else{
                        $centre = 'Paris';
                      }

            /*--------------------- AFFICHAGE DES INFORMATIONS DU CANDIDAT ---------------------*/
                      /* Affiche oui ou non pour le niveau obtenu */
                      if ($value['niveau_actuel_obtenu'] == 1) {
                        $obtenu = 'oui';
                      }else{
                        $obtenu = 'non';
                      }

                      /* Changement du format de la date de naissance */
                      $date = $value['date_de_naissance'];
                        if ($date != null ) {
                         $dt = DateTime::createFromFormat('Y-m-d', $date);
                         $dt = $dt->format('d/m/Y');
                      } else { $dt = null;}

             /* Phases du candidat */

              /* Recherche des differentes phases et recuperation de la phase la plus recente*/
               $phase_candidat = $baseClass->infos_phase_candidat($edit_id);
              /* Recherche du libelle de la phase */
              $nom_phase = $baseClass->infos_phase_libelle($phase_candidat[0]['id_phase']);
              $phase_candidat = $nom_phase[0]["libelle_phase"];



 $contenu .=' <section id="content" class="">

        <!-- Begin .page-heading -->
        <div class="page-heading">
            <div class="media clearfix">
              <div class="media-left pr30">';
              /* Si le candidat a une photo, l'afficher. Sinon mettre la provisoire */
               print_r($value['photo']);
               if ($value['photo'] != null ) {
                print_r("photo ok");
       $contenu .='          <img src="images/photo_profil/'.$value['photo'].'"  width=190 height=190>';
               }else{
        $contenu .='          <img src="images/photo_profil/profil_avatar.jpg"  width=190 height=190>  ';
               }
               

               /* --------------------------- FORMULAIRE PHOTO DE PROFIL ELEVE --------------------------- */
        //  -- Création de l'url pour la modification.
        $params = array('edit_id'=>$edit_id,
                        'module'=>$module,
                        'mode'=>'modification_img'
                );
  $url_modification_img = $baseClass->creation_adm_url($params);
  $contenu .=' <form action="'.$url_modification_img.'" method="post" enctype="multipart/form-data">
   <input name="edit_id" type=hidden value="'.$edit_id.'">
    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="modification_img">
                      <input type="file" class="gui-file" name="file" id="file">
                     <button type="submit">Envoyer</button>
               </form>';
               /* ------------------------- FIN FORMULAIRE PHOTO DE PROFIL ELEVE ------------------------- */

  $conseiller = $baseClass->infos_conseiller_candidat($value['id_conseiller']);
  $contenu .='  </div>                      
              <div class="media-body va-m">
                <h2 class="media-heading">'.$value['nom'].'  '.$value['prenom'].'
                  <small> - '.$infos_formation[0]['libelle_formation'].' - '.$centre.' </small> 
                </h2><br>
                <h4 class="media-heading" style="text-align:center;">'.$session_candidat[0]['libelle'].'</h4><br> 
                 <p>Conseiller : '.$conseiller[0]['nom_conseiller'].' '.$conseiller[0]['prenom_conseiller'].'</p>

                <h3><span class="'.$nom_phase[0]["couleur"].'">'.$nom_phase[0]["libelle_court"].'</span></h3>
              </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
             
              <div class="panel">
                <div class="panel-heading">
                  <span class="panel-icon">
                    <i class="fa fa-trophy"></i>
                  </span>
                  <span class="panel-title">Historique des phases</span>
                </div>
                <div class="panel-body pb5">';
                $formation = $infos_formation[0]['id_formation'];
}

   // -- Affichage de l'historique des phases d'un candidat
   $phase = $baseClass->historique_phase_candidat($edit_id);

foreach ($phase as $key => $value) {

  //  -- Création de l'url pour la suppression des phases attribuées
  $params = array('module'=>$module,
                  'mode'=>'confirmation_suppression_phase',
                  'id'=>$value['id'],
                  'edit_id' => $edit_id);
  $url_suppression_phase = $baseClass->creation_adm_url($params); 

   $date = $value['date_attribution']; // La date où la phase a été donnée
   $phase = $nom_phase[0]['libelle_phase'];
   $nom_phase = $baseClass->infos_phase_libelle($value['id_phase']); // Récuperation du libelle de la phase
  

  
   $contenu .='
   <a class="btn btn-danger btn-xs light fw600 ml10" data-toggle="modal" data-target="#exampleModal" >
                        x</a>   '.date('d/m/Y H:i', strtotime($date)).' <span class="'.$nom_phase[0]["couleur"].'">'.$nom_phase[0]["libelle_court"].'</span> <br>Commentaire :'.$value['commentaire'].'<br>';
  $contenu.='   <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Suppression d\'une phase</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Souhaitez vous vraiment supprimer cette phase ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
        <a href="'.$url_suppression_phase.'"><button type="button" class="btn btn-primary">Oui</button></a>
      </div>
    </div>
  </div>
</div>';                       

  }/* Fin foreach */




      /* -------------------------------------------- VUE : CHANGEMENT PHASE  -------------------------------------------- */

/* Creation de l'url pour l'action du formulaire */
    $params = array('module'=>$module,
                    'mode'=>'insertion_phase',
                    'edit_id' => $edit_id,
                    'formation' => $formation);
    $url_insertion_phase = $baseClass->creation_adm_url($params);

    /* Appel des differentes phases*/
    $tb['phases'] = $baseClass->infos_phase();

$contenu.='<form enctype="multipart/form-data" action="'.$url_insertion_phase.'" method="POST">
           <input type="text" hidden="hidden" value="'.$edit_id.'">
           <input type="text" hidden="hidden" value="'.$formation.'">';

              /* Affichage d'un select qui affiche toutes les phases */
$contenu.='  <br>    <div class="section row">
                      <div class="col-md-10">
                      <label>Nouvelle phase</label>
                        <label class="field select">
                        <select id="phase" name="phase" class="form-control">
                        ';


                        while (list($info,$value)=each($tb['phases'])) {
                         
$contenu.='             
                          <option value="'.$value['id_phase'].'">'.$value['libelle_phase'].'</option>
                        '; }/* Fin while*/

$contenu.='              </select>
                  <label>Date :</label>
                  <input type="date" name="date_attribution" class="form-control" required=true>
                  <label>Commentaire :</label>
                  <input type="text" name="commentaire" class="form-control">
                     <i class="arrow double"></i>
                      </label><!-- end section -->';
              /* Bouton validation */
$contenu .='       <button class="btn btn-primary" value="submit" name="submit"> Valider </button>
                  </form>
                  </div></div>';
  /* -------------------------------------------- FIN VUE : CHANGEMENT PHASE  -------------------------------------------- */

  /* -------------------------------------------- VUE : CHANGEMENT CONSEILLER  -------------------------------------------- */

 foreach ($infos_candidat as $key => $value) {
  $conseiller = $baseClass->infos_conseiller_candidat($value['id_conseiller']);

  // --- Affichage du conseiller actuel 
$contenu .='  </div>
              </div>
              <div class="panel">
                <div class="panel-heading">
                  <span class="panel-icon">
                    <i class="fa fa-pencil"></i>
                  </span>
                  <span class="panel-title">Attribution conseiller</span>
                </div>
                <div class="panel-body pb5">
                <div class="alert alert-default alert-dismissable">
  Conseiller : '.$conseiller[0]['nom_conseiller'].' '.$conseiller[0]['prenom_conseiller'].'
</div>
';

            // --- Creation de l'url pour l'action du formulaire 
              $params = array('module'=>$module,
                              'mode'=>'insertion_conseiller',
                              'edit_id' => $edit_id);
              $url_insertion_conseiller = $baseClass->creation_adm_url($params);

            // --- Appel des differentes phases
              $tb['conseillers'] = $baseClass->infos_conseillers();

$contenu.='<form enctype="multipart/form-data" action="'.$url_insertion_conseiller.'" method="POST">
<input type="text" hidden="hidden" value="'.$edit_id.'">';

              /* Affichage d'un select qui affiche tous les conseillers */
$contenu.='        <div class="section row">
                      <div class="col-md-8">
                      <label>Changement conseiller</label>
                        <label class="field select">
                        <select id="conseiller" name="conseiller" class="form-control">';
                        while (list($info,$value)=each($tb['conseillers'])) {                    
$contenu.='             
                          <option value="'.$value['id_conseiller'].'">'.$value['nom_conseiller'].' '.$value['prenom_conseiller'].'</option>
                        '; }/* Fin while*/

$contenu.='              </select>
                     <i class="arrow double"></i>
                      </label>';

                     /* Bouton validation */
$contenu .='       <button class="btn btn-primary" value="submit" name="submit"> Valider </button>
                  </form>
                  </div></div>
                </div>
              </div>
            </div>';
/* -------------------------------------------- FIN VUE : CHANGEMENT CONSEILLER  -------------------------------------------- */

  /* -------------------------------------------- INFORMATIONS CANDIDAT -------------------------------------------- */
  // --- Creation de l'url pour l'action modifier d'un candidat 
              $params = array('module' => $module,
                              'mode' => 'modification_candidat',
                              'edit_id' => $edit_id);
              $url_modification_candidat = $baseClass->creation_adm_url($params);
  $contenu .='
            <div class="col-md-8">
              <div class="tab-block">
                <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="#tab1" data-toggle="tab" aria-expanded="true">Informations du candidat </a>
                  </li>
                  <li>
                    <a data-toggle="modal" data-target="#modification_candidat" data-target=".bd-example-modal-lg">Modifier</a>
                  </li>
                </ul>
                <div class="tab-content p30" style="">
                  <div id="tab1" class="tab-pane active">'; }
                
         foreach ($infos_candidat as $key => $value) {
                     $contenu .=' 
 <table class="table table-striped">
  <thead>
    <tr class="dark">
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><b>Lieu de naissance :</b> </b></td>
      <td>'.$value['lieu_de_naissance'].'</td>
       <td><b>Date naissance </b></td>
      <td>'.$dt.'</td>
    </tr>
    <tr>
      <td> <b>Adresse :</b></td>
      <td>'.$value['adresse_postale'].' '.$value['adresse_postale2'].'</td>
      <td> <b>Ville de naissance :</b></td>
      <td>'. $value['ville_naissance'].'</td>
     
    </tr>
    <tr>
      <td><b>Code postal:</b> </td>
      <td>'.$value['code_postal'].'</td>
      <td><b>Ville :</b></td>
      <td>'.$value['ville'].'</td>
    </tr>
     <tr>
      <td><b>Telephone : </b></td>
      <td>'.$value['telephone'].'</td>
      <td><b>Portable :</b></td>
      <td>'.$value['portable'].'</td>
    </tr>
    <tr>
      <td> <b>E-mail : </b> </td>
      <td> '.$value['email'].'</td>
      <td><b> Pays :</b> </td>
      <td>'.$infos_pays[0]['nom_pays'].'</td>
    </tr>
    <tr>
      <td><b>Dernier etablissement :</b> </td>
      <td>'.$value['dernier_etablissement_frequente'].'</td>
      <td> <b> Provenance : </b></td>
      <td>'.$infos_provenance[0]['libelle_provenance'].'</td>
    </tr>
    <tr>
      <td><b> Niveau actuel :</b> </td>
      <td> '.$infos_niveau[0]['libelle_niveau'].'</td>
      <td> <b>Obtenu :</b>  </td>
      <td>'.$obtenu.'</td>
    </tr>
    <tr>
      <td><b>Formation demandée:</b> </td>
      <td> '.$infos_formation[0]['libelle_formation'].'</td>
      <td> <b>Rythme :  </b> </td>
      <td>'.$infos_rythme[0]['libelle_rythme'].'</td>
    </tr>
      <tr>
      <td> <b>Type de demande :</b> </td>
      <td> '.$infos_type_demande[0]['type_demande'].'</td>
     
    </tr>
  </tbody>
</table>';
             /* -------------------------------------------- FIN INFORMATIONS CANDIDAT -------------------------------------------- */

  $contenu .='        </div>
                    </div>
                  </div>
                </div>
              </div>
          
      </section>';


      // MODAL modification du candidat

       $contenu .=' <!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="modification_candidat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modification du candidat</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        

<form action="index.php" method="post" enctype="multipart/form-data">
<input name="edit_id" type=hidden value="'.$edit_id.'">
    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="modification_candidat">
    



 <table class="table table-striped">
  <thead>
    <tr class="dark">
    </tr>
  </thead>
  <tbody>
   <tr>
       <td><b>Nom</b></td>
      <td><input type="text" class="form-control" id="formvars[nom]" name="formvars[nom]" placeholder=""value="'.$value['nom'].'"></td>

      <td><b>Prenom :</b> </b></td>
      <td><input type="text" class="form-control" id="formvars[prenom]" name="formvars[prenom]" placeholder=""value="'.$value['prenom'].'"></td>
    </tr>

<tr>
<td><b>Date naissance </b></td>
      <td><input type="text" class="form-control" id="formvars[date_de_naissance]" name="formvars[date_de_naissance]" placeholder=""value="'.$dt.'"></td>
      </tr>

    <tr>
      <td><b>Lieu de naissance :</b> </b></td>
      <td>'.$value['lieu_de_naissance'].'</td>
      <td><b>Ville de naissance :</b> </b></td>
      <td><input type="text" class="form-control" id="formvars[ville_naissance]" name="formvars[ville_naissance]" placeholder=""value="'.$value['ville_naissance'].'"></td>
    </tr>
    <tr>
      <td> <b>Adresse :</b></td>
      <td><input type="text" class="form-control" id="formvars[adresse_postale]" name="formvars[adresse_postale]" placeholder=""  value="'.$value['adresse_postale'].'"></td>
      <td> <b>Adresse 2:</b></td>
     <td> <input type="text" class="form-control" id="formvars[adresse_postale2]" name="formvars[adresse_postale2]" placeholder=""  value="'.$value['adresse_postale2'].'"></td>
    </tr>
    <tr>
      <td><b>Code postal:</b> </td>
      <td><input type="text" class="form-control" id="formvars[code_postal]" name="formvars[code_postal]" placeholder=""  value="'.$value['code_postal'].'"></td>
      <td><b>Ville :</b></td>
      <td><input type="text" class="form-control" id="formvars[ville]" name="formvars[ville]" placeholder=""  value="'.$value['ville'].'"></td>
    </tr>
     <tr>
      <td><b>Telephone : </b></td>
      <td><input type="text" class="form-control" id="formvars[telephone]" name="formvars[telephone]" placeholder=""  value="'.$value['telephone'].'"></td>
      <td><b>Portable :</b></td>
      <td><input type="text" class="form-control" id="formvars[portable]" name="formvars[portable]" placeholder="" value="'.$value['portable'].'"></td>
    </tr>
    <tr>
      <td> <b>E-mail : </b> </td>
      <td> <input type="text" class="form-control" id="formvars[email]" name="formvars[email]" placeholder="" value="'.$value['email'].'"></td>
      <td><b> Pays :</b> </td>
      <td>'.$infos_pays[0]['nom_pays'].'</td>
    </tr>
    <tr>
      <td><b>Formation demandée:</b> </td>
      <td>
         <select class="form-control" id="formvars[id_formation]" name="formvars[id_formation]" required="required">';
                    
$contenu.='   <option value="'.$infos_formation[0]['id_formation'].'">'.$infos_formation[0]['libelle_formation'].'</option>';
    while (list($info,$value)=each($formation_liste)) {
if ($value['id_centre'] == 1) {
  $lieu = 'Evry';
} else {
$lieu = 'Paris';
}
 $contenu.='        
 <option value="'.$value['id_formation'].'">'.$value['libelle_formation'].' - '.$lieu.'</option>
                        '; }/* Fin while*/
 $contenu.='            </select> 
  </td>


      <td> <b>Rythme :  </b> </td>
      <td> <select class="form-control" id="formvars[id_rythme]" name="formvars[id_rythme]" >';
      $contenu.='   <option value="'.$infos_rythme[0]['id_rythme'].'">'.$infos_rythme[0]['libelle_rythme'].'</option>';

                        while (list($info,$value)=each($rythme_liste)) {  
$contenu.='              <option value="'.$value['id_rythme'].'">'.$value['libelle_rythme'].'</option>';
                        } /* Fin while*/
$contenu.='             </select>
</td>
    </tr>
  </tbody>
</table>

<button class="btn btn-primary" type="submit">Valider</button>



</form>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
  
      </div>
    </div>
  </div>
</div>';

}
            



	} /* Fin mode affichage eleve */

if ($mode == 'modification_candidat') {
 
      $edit_id = $baseClass->auto_variable('edit_id');
      $formvars = $baseClass->auto_variable('formvars');
     
      $formvars['date_de_naissance'] =  \DateTime::createFromFormat('d/m/Y', $formvars['date_de_naissance']);
      $formvars['date_de_naissance'] = $formvars['date_de_naissance']->format('Y-m-d');
      $formvars['nom'] = strtoupper($formvars['nom']);
      $formvars['prenom'] = ucfirst(strtolower($formvars['prenom']));
      $tbl_demande_dc = $baseClass->nom_table('demande_dc');
      $params = array('cle_primaire'=>'id_demande');
    
      if ($edit_id  > 0) {
        $requete = $baseClass->creation_requete_modification($tbl_demande_dc,
        $formvars, $edit_id, $params);
        
        $ok = $baseClass->requete_sql($db, $requete);
        header("Refresh:0; url=/index.php?edit_id=$edit_id&mode=affiche_candidat&module=dc"); 
      }
    }
   

/* -------------------------------------------- CHANGEMENT PHOTO PROFIL-------------------------------------------- */
    if ($mode == 'modification_img') {
      $edit_id = $baseClass->auto_variable('edit_id');
      $formvars = $baseClass->auto_variable('formvars');
      $tbl_ddd = $baseClass->nom_table('demande_dc');
      $params = array('cle_primaire'=>'id_demande');

      if ($edit_id  > 0) {
        print_r("if ($edit_id  > 0");
        $dirlink = "profile_avatar.jpg";
$folder = 'images/photo_profil/'; // chemin du répertoire destination pour les fichiers uploadés (important  : le slash à la fin de la chaine de caractère).
$maxSize = 1000000 * 5; // 5Mo

if(!empty($_FILES) && isset($_FILES['file'])) {
   

    if ($_FILES['file']['error'] == UPLOAD_ERR_OK AND $_FILES['file']['size'] <= $maxSize) {

        $nomFichier = $_FILES['file']['name']; // récupère le nom de mon fichier au sein de la superglobale $_FILES (tableau multi-dimentionnel)
        $tmpFichier = $_FILES['file']['tmp_name']; // Stockage temporaire du fichier au sein de la superglobale $_FILES (tableau multi-dimentionnel)
        
        $file = new finfo(); // Classe FileInfo
        $mimeType = $file->file($_FILES['file']['tmp_name'], FILEINFO_MIME_TYPE); // Retourne le VRAI mimeType

        $mimTypeOK = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif'); // Attention, image n'indique pas le répertoire, mais le typemime !

        if (in_array($mimeType, $mimTypeOK)) { // in_array() permet de tester si la valeur de $mimeType est contenue dans le tableau $mimTypeOK
                    

            $newFileName = explode('.', $nomFichier);
            $fileExtension = end($newFileName); // Récupère la dernière entrée du tableau (créé avec explode) soit l'extension du fichier

            // nom du fichier link au format : crea-id-timestamp.jpg
            $finalFileName = 'cand-'.time().'.'.$fileExtension; // Le nom du fichier sera donc cand-id-timestamp.jpg (time() retourne un timestamp à la seconde)


                if(move_uploaded_file($tmpFichier, $folder.$finalFileName)) { // move_uploaded_file()  retourne un booleen (true si le fichier a été envoyé et false si il y a une erreur)
                    // Ici je suis sûr que mon image est au bon endroit
                    $dirlink = $finalFileName;
                    
                }
                else {
                    // Permet d'assigner un link par défaut
                    $dirlink = "profile_avatar.jpg";
                }
        } // if (in_array($mimeType, $mimTypeOK))

        else {
            $error[] = 'Ce type de fichier est interdit, mime type incorrect !';
        } 


    } // end if ($_FILES['picture']['error'] == UPLOAD_ERR_OK AND $_FILES['picture']['size'] <= $maxSize)
    else {
        $error[] = 'Merci de choisir un fichier image (uniquement au format jpg) à uploader et ne dépassant pas 5Mo !';
    }
} // end if (!empty($_FILES) AND isset($_FILES['picture'])

else {
    // Permet d'assigner l link par défaut si la crea n'en a aucun
    $dirlink = "profile_avatar.jpg";
}
//print_r($finalFileName);

/*  Parametre ??? */
        $formvars['photo'] = $finalFileName;
        $requete = $baseClass->creation_requete_modification($tbl_ddd,
        $formvars, $edit_id, $params);
       // print_r($requete);
        $ok = $baseClass->requete_sql($db, $requete);
        header("Refresh:0; url=/index.php?edit_id=$edit_id&mode=affiche_candidat&module=dc");
       
      }
}
      /* -------------------------------------------- FIN CHANGEMENT PHOTO PROFIL------------------------------------------- */
                                                      /////////////////////////
      /* -------------------------------------------- CHANGEMENT PHASE CANDIDAT -------------------------------------------- */
 /* Mode changement de phase */ 
if ($mode == "insertion_phase") {
 if (isset($_POST['submit'])) { 
  /* On recupere l'id du candidat */
  $edit_id = $baseClass->auto_variable('edit_id');
  $formation = $baseClass->auto_variable('formation');
  // --- Tables
  $tbl_phase = $baseClass->nom_table('phase_par_eleve');
  $tbl_current_phase = $baseClass->nom_table('current_phase');
  
  // Heure pour la phase 
  $heure = date('H:i:s');
  
  // --- Tableau avec la nouvelle phase
  $tb_phase = array('id_phase' => $_POST['phase'],
                    'commentaire' =>$_POST['commentaire'],
                    'date_attribution' => $_POST['date_attribution'].' '.$heure,
                    'id_demande_doc' => $edit_id,
                    'id_formation' => $formation);

  // --- Tableau pour la phase actuelle dans 'dc_current_phase'
  $tbCurrentPhase = array('id_phase'=> $_POST['phase'],
                            'id_demande' => $edit_id);

  // Recherche de l'id du candidat dans la table 'dc_current_phase'
  $params = array('id_demande' => $edit_id);
  $ifCandidatExist = $baseClass->infos_current_phase($params);
  
  // Si il existe :: La supprimer puis ajouter la nouvelle
  if (count($ifCandidatExist) > 0) {
    // --- On supprime 
    $params = array('cle_primaire'=>'id_current_phase');
    $requete = $baseClass->creation_requete_suppression($tbl_current_phase, $ifCandidatExist[0]['id_current_phase'], $params);
    $ok = $baseClass->requete_sql($db, $requete);

    // --- On ajoute la nouvelle phase actuelle
    $params = array('cle_primaire'=>'id_current_phase');
    $requete = $baseClass->creation_requete_insertion($tbl_current_phase, $tbCurrentPhase, $params);
    $ok = $baseClass->requete_sql($db, $requete);

  } else {  // Si il existe pas 
    // --- On ajoute la nouvelle phase actuelle
    $params = array('cle_primaire'=>'id_current_phase');
    $requete = $baseClass->creation_requete_insertion($tbl_current_phase, $tbCurrentPhase, $params);
    $ok = $baseClass->requete_sql($db, $requete);
  }
    
  // --- Requete ajout historique de phase dans 'dc_phase_par_eleve' 
     $params = array('cle_primaire'=>'id');
     $requete = $baseClass->creation_requete_insertion($tbl_phase,$tb_phase,$params);
     $ok = $baseClass->requete_sql($db, $requete);
     header("Refresh:0; url=/index.php?edit_id=$edit_id&mode=affiche_candidat&module=dc"); 
    
   }/* fin isset submit */
  }/* Fin if mode == insertion */

  /* -------------------------------------------- FIN CHANGEMENT PHASE CANDIDAT -------------------------------------------- */

  /* -------------------------------------------- CHANGEMENT CONSEILLER CANDIDAT -------------------------------------------- */
  /* Mode changement de conseiller */ 
if ($mode == "insertion_conseiller") {
 if (isset($_POST['submit'])) { 
  /* On recupere l'id du candidat */
  $edit_id = $baseClass->auto_variable('edit_id');

  $formvars['id_conseiller'] = $_POST['conseiller'];
  // --- Tables
  $tbl_ddd = 'dc_demande_dc';
  // --- Parametres
  $params = array('cle_primaire'=>'id_demande');
  // --- Requete
     $requete = $baseClass->creation_requete_modification($tbl_ddd,$formvars,$edit_id,$params);
     print_r($requete);
     $ok = $baseClass->requete_sql($db, $requete);
     header("Refresh:0; url=/index.php?edit_id=$edit_id&mode=affiche_candidat&module=dc"); 
   }/* fin isset add*/
  }/* Fin if mode == insertion*/

   /* -------------------------------------------- FIN CHANGEMENT CONSEILLER CANDIDAT -------------------------------------------- */

   /* -------------------------------------------- MODE SUPPRESSION PHASE  -------------------------------------------- */
   // --- Mode confirmation suppression
    if($mode == 'confirmation_suppression_phase') {


      $edit_id = $baseClass->auto_variable('edit_id');
      $id = $baseClass->auto_variable('id');
      $module = $baseClass->auto_variable('module');
      $tbl_phase_par_eleve = $baseClass->nom_table('phase_par_eleve');
      $params = array('cle_primaire'=>'id');
      if ($id  > 0) {
        $requete = $baseClass->creation_requete_suppression($tbl_phase_par_eleve, $id, $params);
        $ok = $baseClass->requete_sql($db, $requete);
        $params = array('module'=>$module,
                        'mode' =>'affiche_candidat',
                        'edit_id' =>$edit_id);       
        $url = $baseClass->creation_adm_url($params);       
        header("Refresh:0; url=$url");  
      } 
    }
   /* -------------------------------------------- FIN MODE SUPPRESSION PHASE  -------------------------------------------- */

   /* -------------------------------------------- MODE SUPPRESSION CANDIDAT  -------------------------------------------- */

	//	--- Mode confirmation suppression
		if($mode == 'confirmation_suppression') {
			$edit_id = $baseClass->auto_variable('edit_id');
			$id = $baseClass->auto_variable('id');
			$module = $baseClass->auto_variable('module');
			$tbl_demande_dc = $baseClass->nom_table('demande_dc');
			$params = array('cle_primaire'=>'id_demande');
		
			if ($id  > 0) {
				$formvars['deleted'] = 1;
				$requete = $baseClass->creation_requete_modification($tbl_demande_dc,
				$formvars, $id, $params);
				$ok = $baseClass->requete_sql($db, $requete);
				$params = array('module'=>$module);				
				$url = $baseClass->creation_adm_url($params);				
				header("Refresh:0; url=$url");  
			}	
		}
/* -------------------------------------------- FIN MODE SUPPRESSION CANDIDAT  -------------------------------------------- */


/* -------------------------------------------- MODE IMPORTATION  -------------------------------------------- */

// FORMULAIRE d'importation de l'application it vers appactuel
if($mode == 'importation') {

  // Création de l'URL pour le traitement de l'importation
   $params = array('mode'=>'valid_importation',
                     'module'=>$module);
   $url_valid_importation = $baseClass->creation_adm_url($params);

  $contenu .='
  <br><br>
  <br><br>
  <br>
<div class="col-md-4"></div>
<div class="col-md-5">
   <form action="'.$url_valid_importation.'" method="post" enctype="multipart/form-data">
    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="valid_importation">
                      <input type="file" class="gui-file" name="fichier" id="fichier">

                      //Action bloquée
                   <!--  <button type="submit" name="add">Envoyer</button> -->
               </form>

<br><br><br>
  <p><b>Importation d\'un fichier csv :</b> <br>
  Separateur point virgule ; <br>
  Ne doit contenir que des lignes avec les informations des candidats <br>
  <b>Ordre :</b><br> civilite, nom, prenom, adresse1, adresse2, cp, ville, formation, mail, tel, portable, idconseiller, idphase, datenaiss, idniveau, idprovenance, idsession. <br>
  -> Correspond à l\'export <b>vrai_selection.php</b>
  </p></div>';
 
 } /* Fin mode importation */

 // Mode de traitement de l'importation
 if($mode == 'valid_importation') {
  /* Effacer la premiere ligne du fichier csv*/

if (isset($_POST['add'])) {

      // Affiche le nom du fichier
     /* echo $filename=$_FILES["fichier"]["name"];*/
   //Ouvre le fichier depuis le repertoire tmp

      $fh = fopen($_FILES['fichier']['tmp_name'], 'r'); //Ouvre le fichier depuis le repertoire tmp
      $lines = array();

      while(($row = fgetcsv($fh, 10000, ";")) !== FALSE ) {
          
        $lines[]= $row; /* Affiche les lignes du fichier dans un tableau */
        $conseiller = $row[11];
        $formation = utf8_encode($row[7]);
        $phase = $row[12];
        $datenaiss = $row[13]; //timestamp
        $niveau = $row[14];
        $provenance = $row[15];
        $session = $row[16];

switch ($niveau) {
	case 1:
		$niveau = 1;
		break;
	case 2:
		$niveau = 2;
		break;
	case 4:
		$niveau = 3;
		break;
	case 3:
		$niveau = 4;
		break;
	case 8:
		$niveau = 5;
		break;
	case 27:
		$niveau = 6;
		break;
	case 10:
		$niveau = 7;
		break;
	case 26:
		$niveau = 11;
		break;
	case 15:
		$niveau = 12;
		break;
	case 16:
		$niveau = 13;
		break;
	case 17:
		$niveau = 14;
		break;
	case 18:
		$niveau = 15;
		break;
	case 19:
		$niveau = 16;
		break;
	case 25:
		$niveau = 17;
		break;
	default:
		$niveau = 3 ;
		break;
}
switch ($provenance) {
	case 503:
		$provenance= 7;
		break;
	case 513:
		$provenance= 4;
		break;
	case 199:
		$provenance= 8;
		break;
	case 104:
		$provenance= 8;
		break;
	case 102:
		$provenance= 8;
		break;
	case 305:
		$provenance= 6;
		break;
	default:
		$provenance= 9;
		break;
}
// ------------------ Correspondance des champs formation avec la nouvelle bdd ------------------

 if ($datenaiss != null || $datenaiss != 0) { // convertion timestamp en date 
 $dt = date('Y-m-d', $datenaiss);
 //$dt = DateTime::createFromFormat('d/m/Y', $datenaiss);
} else { $dt = '01/01/1900';}

        switch ($phase) {
        	case 10:
        		$phase = 1;
        		break;
        	case 3:
        		$phase = 5;
        		break;
        	case 4:
        		$phase = 5;
        		break;
        	case 5:
        		$phase = 5;
        		break;
        	case 6:
        		$phase = 7;
        		break;
        	case 7:
        		$phase = 8;
        		break;
        	case 12:
        		$phase = 8;
        		break;
        	case 22:
        		$phase = 8;
        		break;
        	case 23:
        		$phase = 5;
        		break;
        	case 26:
        		$phase = 2;
        		break;
        	case 27:
        		$phase = 2;
        		break;
        	case 29:
        		$phase = 2;
        		break;
        	case 30:
        		$phase = 2;
        		break;
        	case 34:
        		$phase = 5;
        		break;
        	default:
			$phase = 1;
			break;
        }
        switch ($conseiller) {
        	case 5:
        		$conseiller = 2;
        		break;
        	case 6:
        		$conseiller = 3;
        		break;
        	case 7:
        		$conseiller = 5;
        		break;
        	case 8:
        		$conseiller = 4;
        		break;
          default:
          $conseiller = 1;
            break;
        }

        switch ($session) {
        	case 10:
        		$session = 4;
        		break;
        	case 9:
        		$session = 3;
        		break;
          default:
          $session = 4;
            break;
        }



 if ($row[1] != null ) {
 
        //On relie les données à des variables
        $tb_demande_externe = array(
                              'civilite' => $row[0],
                              'nom' => utf8_encode(strtoupper($row[1])),
                              'prenom' => utf8_encode(ucfirst(strtolower($row[2]))),
                              'date_de_naissance' => $dt, 
                              'adresse_postale' => utf8_encode($row[3]),
                              'code_postal' => $row[5],
                              'ville' => utf8_encode($row[6]),
                              'portable' => $row[10],
                              'telephone' => $row[9],
                              'email' => utf8_encode($row[8]),
                              'id_niveau' => $niveau,
                              'id_pays' => 1,
                              'id_rythme' => 2, // Alternant
                              'id_provenance'=> $provenance,
                              'id_formation' => $id_formation,
                              'id_session' => $session,
                              'id_conseiller' => $conseiller,
                              'id_type_demande' => 7,
                              'ancien' => 1
            );
}


// $unique = array_map('unserialize', array_unique(array_map('serialize', $tb_demande_externe)));
 $params = array('nom' => utf8_encode(strtoupper($row[1])),
                 'prenom' => utf8_encode(ucfirst(strtolower($row[2]))) );
 $doublon = $baseClass->doublon_nom_prenom($params);

  if ($doublon <= 0) {

        /* REQUETE D'INSERTION */
        $tbl_demande_dc = $baseClass->nom_table('demande_dc'); 
        $params = array('cle_primaire'=>'id_demande');
        $requete = $baseClass->creation_requete_insertion($tbl_demande_dc,
        $tb_demande_externe);
        $ok = $baseClass->requete_sql($db, $requete);

if ($phase != 1) {
 $tb_nouvelle_phase2 = array(
                        'id_demande_doc'=> $baseClass->lastInsertId(), // Recupère le dernier id inseré
                        'id_phase'=> 1, // Demande de dc
                        'id_formation' => $id_formation,
                        'date_attribution'=> "2018-04-01 00:00:00"
                      );
}
/*$tb_nouvelle_phase3 = array(
                        'id_demande_doc'=> $baseClass->lastInsertId(), // Recupère le dernier id inseré
                        'id_phase'=> 2,// Venu RI
                        'id_formation' => $id_formation,
                        'date_attribution'=> "2018-04-01 00:00:00"
                      );*/
$tb_nouvelle_phase = array(
                        'id_demande_doc'=> $baseClass->lastInsertId(), // Recupère le dernier id inseré
                        'id_phase'=> $phase,
                        'id_formation' => $id_formation,
                        'date_attribution'=> "2018-04-01 00:00:00"
                      );
  // --- Tables
  $tbl_phase = 'dc_phase_par_eleve';
  // --- Parametres
  $params2 = array('cle_primaire'=>'id');
  $requete = $baseClass->creation_requete_insertion($tbl_phase,$tb_nouvelle_phase,$params2);
  $ok = $baseClass->requete_sql($db, $requete);
  $requete2 = $baseClass->creation_requete_insertion($tbl_phase,$tb_nouvelle_phase2,$params2);
  $ok = $baseClass->requete_sql($db, $requete2);
/*  $requete3 = $baseClass->creation_requete_insertion($tbl_phase,$tb_nouvelle_phase3,$params2);
  $ok = $baseClass->requete_sql($db, $requete3);*/
}
        //  -- Redirection vers l'accueil dc.
        $params = array('module'=>$module);       
        $url = $baseClass->creation_adm_url($params);       
        header("Refresh:0; url=$url"); }
        } /* Fin while*/

     
     } /* Fin POST */
  

/* Fin mode valid_importation */

/* -------------------------------------------- FIN MODE IMPORTATION DE DEMANDES  -------------------------------------------- */
/* -------------------------------------------- MODE dil  -------------------------------------------- */

// FORMULAIRE d'importation de l'application it vers appactuel
if($mode == 'dil') {

  // Création de l'URL pour le traitement de l'importation
   $params = array('mode'=>'valid_dil',
                     'module'=>$module);
   $url_valid_dil = $baseClass->creation_adm_url($params);

  $contenu .='

<div class="row">
<div class="col-md-2"></div>
<div class="col-md-8">
<br>
 <h3> <b>Règles d\'importation pour un fichier</b><b style="color:red;"> dil :</b> </h3><br>
 <p> - Le fichier doit etre en <b>.CSV</b> -
  Separateur point virgule ; <br>
  - Il ne doit contenir que des lignes avec les informations des candidats donc <b style="color:red;">il faut effacer la premiere ligne</b> <br>
  - AUCUNE donnée ne doit etre modifiée<br>  <br>  

  <b>Ordre des colonnes de gauche a droite :</b><br>  
  Diplôme Formations - Etablissements - Statut - Commentaire- Civilité - Nom -Prénom - Email -Année de naissance - Numéro de téléphone -Address -Code postal -Ville -Pays - Niveau - Série Terminale- Niveau de diplôme- Etudie dans -Niveau actuel 
<br><br>  
  -> Correspond à l\'export <b>dil</b> [ NON compatible avec d\'autres fichiers]
  </p>
  <p><b style="color:red;">TOUT changement doit etre indiqué au service informatique</b><br>
  Exemples : Changement d\'ordre du fichier, de nom de diplome ,...</p>
  </div></div>';


  $contenu .=' <br><br><div class="row">
<div class="col-md-4"></div>
<div class="col-md-5">
   <form action="'.$url_valid_dil.'" method="post" enctype="multipart/form-data">
    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="valid_dil">
                      <input type="file" class="gui-file" name="fichier" id="fichier">
                     <button type="submit" name="add">Envoyer</button>
               </form>
</div></div>';
 
 } /* Fin mode importation */

/* -------------------------------------------- DEBUT MODE IMPORTATION dil -------------------------------------------- */
if($mode == 'valid_dil') {

  // Si le formulaire a été posté
   if (isset($_POST['add'])) {
      // Affiche le nom du fichier
      echo $filename=$_FILES["fichier"]["name"];
//Ouvre le fichier depuis le repertoire tmp
      $fh = fopen($_FILES['fichier']['tmp_name'], 'r');
      $lines = array();

      while(($row = fgetcsv($fh, 10000, ";")) !== FALSE ) {
          /* Affiche les lignes du fichier dans un tableau */
          
          $lines[]= $row;
          /*iconv('ANSI', 'utf-8', $row);*/

        /* Supprime les 3 premiers caractères */
        $row[2] = substr($row[2], 2);
        $row[3] = substr($row[3], 2);
        $row[0] = substr($row[0], 0, -6);
        $row[4] = substr($row[4], 2);
        
        /* Comme la date est une annee : on met 01/01/ avant */
        if ($row[11] != null) {
         $dd = "01/01/";
        $date = $dd."".$row[11];
        $date = \DateTime::createFromFormat('d/m/Y', $date);
        $date = $date->format('Y-m-d');
        } else {
          $date = null;
        }
    
            if ($row[7] == "M.") {
              $row[7] =1;
            } else { $row[7] = 2;}

// -- L'ecole
        if ($row[4] == "it") {
             $row[4] =0;
            } else { $row[4] = 1;}
         
if ($row[9] != null && $row[8] != null) {
  
$formation = utf8_encode($row[2])." ".utf8_encode($row[3]);

switch ($formation) {
  default:
  $formation = 1;
    
    break;
}
        //On relie les données à des variables (ici dil)
        $tb_demande_externe = array(
                              'civilite' => $row[7],
                              'nom' => utf8_encode(strtoupper($row[8])),
                              'prenom' => utf8_encode(ucfirst(strtolower($row[9]))),
                              'date_de_naissance' => $date,
                              'adresse_postale' => utf8_encode($row[13]),
                              'code_postal' => $row[14],
                              'ville' => utf8_encode($row[15]),
                              'portable' => $row[12],
                              'email' => utf8_encode($row[10]),
                              'id_niveau' => 4, // Non defini
                              'id_pays' => 1,
                              'id_rythme' => 2, // Alternant
                              'id_provenance'=> 3, // dil
                              'id_formation' => $formation,
                              'id_session' => 4,
                              'id_type_demande' => 5 // dil                       
          );

 $params = array('nom' => utf8_encode(strtoupper($row[8])),
                 'prenom' => utf8_encode(ucfirst(strtolower($row[9]))) );
      $doublon = $baseClass->doublon_nom_prenom($params);
  if ($doublon <= 0) {

        /* REQUETE D'INSERTION */
        $tbl_current_phase = $baseClass->nom_table('current_phase');
        $tbl_demande_dc = $baseClass->nom_table('demande_dc');
        $params = array('cle_primaire'=>'id_demande');
        $requete = $baseClass->creation_requete_insertion($tbl_demande_dc,
        $tb_demande_externe); 
        $ok = $baseClass->requete_sql($db, $requete); 

         $tb_nouvelle_phase = array(
                        'id_demande_doc'=> $baseClass->lastInsertId(), // Recupère le dernier id inséré
                        'id_phase'=> 1, // demande doc envoyee
                        'id_formation' => $formation,
                        'date_attribution'=> date('Y-m-d')
                      );
  
   // --- Tableau pour la phase actuelle dans dc_current_phase
  $tbCurrentPhase = array('id_phase'=> 1,
                          'id_demande' => $baseClass->lastInsertId());

  // --- Ajout dans dc_current_phase  
  $params = array('cle_primaire'=>'id_current_phase');
  $requete = $baseClass->creation_requete_insertion($tbl_current_phase, $tbCurrentPhase, $params);
  $ok = $baseClass->requete_sql($db, $requete);
  // --- Tables
  $tbl_phase = 'dc_phase_par_eleve';
  // --- Parametres
  $params2 = array('cle_primaire'=>'id');
  $requete = $baseClass->creation_requete_insertion($tbl_phase,$tb_nouvelle_phase,$params2);

  $ok = $baseClass->requete_sql($db, $requete);

} // fin doublon
       }
        //  -- Redirection vers l'accueil dc.
        $params = array('module'=>$module);         
        header("Refresh:0; url=/index.php?module=dc&mode=dil"); 
        } /* Fin while*/
}  // fin ADD
} // fin importation dil

/* -------------------------------------------- FIN MODE IMPORTATION dil -------------------------------------------- */
				 
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


