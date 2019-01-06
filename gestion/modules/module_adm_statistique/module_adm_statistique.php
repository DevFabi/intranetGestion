<?php
/*	--------------------------------------------------------------------------------------------

										MODULE DOCUMENTATION
						
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
 //  --- Mode affichage.
 // Affichage du TOTAL des demandes et du TOTAL des demandes supprimées
  if(!$mode) {  
    $statut = 0;
$total_demande = $baseClass->total_dd($statut);

  $contenu .='
  <br>

  <div class="col-md-3"></div>
<div class="col-md-3">
  <div class="panel panel-tile text-success br-b bw5 br-success-light">
  <div class="panel-body pl20 p5">
    <i class="fa fa-envelope icon-bg"></i>
    <h2 class="mt15 lh15">
      <b>'.$total_demande[0]['total_doc'].'</b>
    </h2>
    <h5 class="text-muted">Demandes reçues </h5>
  </div> <!--  panel body -->
  </div> <!--  Panel tile-->
</div><!--  col md 3 -->';
$statut = 1;
$total_delete = $baseClass->total_dd($statut);
$contenu.=' 
  
  <div class="col-md-3">
    <div class="panel panel-tile text-danger br-b bw5 br-danger">
  <div class="panel-body pl20 p5">
    <i class="fa fa-bar-chart-o icon-bg"></i>
    <h2 class="mt15 lh15">
      <b>'.$total_delete[0]['total_doc'].'</b>
    </h2>
    <h5 class="text-muted">Demandes supprimées</h5>
  </div>
</div>
  </div>
  </div>
';
  }



	if($mode =='ph'){

// AFFICHAGE des phs par frms
 
  $phs = $baseClass->infos_ph();
  
  $contenu .='
  <div class="col-md-1"></div>
            <div class="col-md-10">


              <div class="panel panel-visible" id="spy1">
                <div class="panel-heading">
                  <div class="panel-title hidden-xs">
                    <span class="glyphicon glyphicon-tasks"></span>phs par frms </div>
                </div>
                <div class="panel-body pn">
                  <table class="table table-striped table-hover" id="datatable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>Section</th>';

                foreach ($phs as $key => $ph) {
                            $contenu .='<th>'.$ph['libelle_court'].'</th>'; 
                          }
                        
             $contenu .='
                  </tr>
                    </thead>
                    ';
 $frms_ = $baseClass->infos_frm_centre(1);

  $contenu .='      <tbody>
                      <tr>';
                    foreach ($frms_ as $key => $frm) {
                      $b = 0;
                      
              $contenu .='   <td><strong>'.$frm['libelle_frm'].'</strong></td>';
              foreach ($phs as $key => $ph) {
                /* $a correspond au nombre de fois ou une ph a ete affecte trié par frm*/
                  $a = $baseClass->total_ph($frm['id_frm'], $ph['id_ph']);
                        $contenu .='<td>'.$a[0]['total'].'</td>'; 
                        /* Total des elv par cn*/
                        
                        }

   $contenu .='
                      </tr>
                    </tbody>';
                  }
         $contenu .=' 
                  </table>
                </div></div>';

      $contenu .='
  <div class="col-md-1"></div>
            <div class="col-md-10">
              <div class="panel panel-visible" id="spy1">
                <div class="panel-heading">
                  <div class="panel-title hidden-xs">
                    <span class="glyphicon glyphicon-tasks"></span>phs par frms </div>
                </div>
                <div class="panel-body pn">
                  <table class="table table-striped table-hover" id="datatable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>Section</th>';

                foreach ($phs as $key => $ph) {
                            $contenu .='<th>'.$ph['libelle_court'].'</th>'; 
                          }
                        
             $contenu .='
                  </tr>
                    </thead>
                    ';
 $frms_ = $baseClass->infos_frm_centre(2);

  $contenu .='      <tbody>
                      <tr>';
                    foreach ($frms_ as $key => $frm) {
                      $b = 0;
                      
              $contenu .='   <td><strong>'.$frm['libelle_frm'].'</strong></td>';
              foreach ($phs as $key => $ph) {
                /* $a correspond au nombre de fois ou une ph a ete affecte trié par frm*/
                  $a = $baseClass->total_ph($frm['id_frm'], $ph['id_ph']);
                        $contenu .='<td>'.$a[0]['total'].'</td>'; 
                        /* Total des elv par cn*/
                        
                        }

   $contenu .=' 
                      </tr>
                    </tbody>';
                  }
         $contenu .=' 
                  </table>
                </div></div>';

  }


/* BEGIN:Mode e -  */
	if($mode =='e'){



	//	-- Reformatage des dates.
	//	--- Date_debut
	$date_debut = new DateTime($formvars['date_debut']);
	$date_debut = $date_debut->format('Y-m-d');
		if(empty($formvars['date_debut'])) $date_debut='';
	//	--- Date_fin
	$date_fin = new DateTime($formvars['date_fin']);
	$date_fin = $date_fin->format('Y-m-d');
		if(empty($formvars['date_fin'])) $date_debut='';
	// AFFICHAGE des phs par frms
	$phs = $baseClass->infos_ph();
	$tb['phs'] = $baseClass->infos_ph();

	//	-- Calendriers
	#	- Début du formulaire de date.
	$contenu .= '
 <div class ="row"><div class="col-md-4"></div>
  <div class="col-md-3"><br><button type="button" class="btn btn-rounded btn-info btn-block" onclick="window.print();return false;">Imprimer</button></div></div>

  <br/><div class ="row">
  <div class ="col-md-4"></div>
  <div class ="col-md-4">
				<form action="index.php" method="post" enctype="multipart/form-data">
					<input name="module" type=hidden value="'
					. $module
					. '">
					<input name="mode" type=hidden value="'
					. $mode
					. '">				
					';			
	//	-- Date de début de la ph.
	$contenu .='
	<p>Date debut: <input type="text" id="date_debut" name="formvars[date_debut]"></p>';
	
	//	-- Date de fin de la ph.
	$contenu .='
	<p>Date fin: <input type="text" id="date_fin" name="formvars[date_fin]"></p>';
	
	//	-- Bouton d'envoi.
	$contenu .='
	      <button type="submit" class="button btn-primary"> Valider </button>';
	#	- Fin du formulaire de date.
	$contenu .='</form>
  </div></div><br/>

  ';
  /*
  // //	 . SUPPRESSION AU DESSOUS	
  
  
	$contenu .='
  <div class="col-md-1"></div>
            <div class="col-md-10">
              <div class="panel panel-visible" id="spy1">
                <div class="panel-heading">
                  <div class="panel-title hidden-xs">
                    <span class="glyphicon glyphicon-tasks"></span>phs par frms </div>
                </div>
                <div class="panel-body pn">
                  <table class="table table-striped table-hover" id="datatable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>Section</th>';

                foreach ($phs as $key => $ph) {
	                    $contenu .='<th>'.$ph['libelle_court'].'</th>';
				}
				                
             $contenu .='
                  </tr>
                    </thead>
                    ';
 $frms_ = $baseClass->infos_frm_centre(1);

  $contenu .='      <tbody>
                      <tr>';
                    foreach ($frms_ as $key => $frm) {
                      $b = 0;
                      
              $contenu .='   <td><strong>'.$frm['libelle_frm'].'</strong></td>';
              foreach ($phs as $key => $ph) {
					// $a correspond au nombre de fois ou une ph a ete affecte trié par frm
					$params = array('id_frm'=>$frm['id_frm'],
									'id_ph'=>$ph['id_ph'],
									'date_debut'=>$date_debut,
									'date_fin'=>$date_fin);
					$a = $baseClass->_total_ph( $params );
				
						switch ( $key )
						{
							//	-- Demande de doc.
							case 0:
								//	--- Enregistrement du nombre total.
								$total_demande_envoyee = $a[0]['total'];
								$contenu .='<td>'.$a[0]['total'].'</td>';
							break;
							
							//	-- Taux.
							case 1:
								//	-- Calcul du taux (nombre de venu * 100 / nombre total de demandes) .
								$taux = round(($a[0]['total'] * 100) / $total_demande_envoyee );
								$contenu.='
								<td style="text-align : center;">'. $taux .' %</td>
								<td style="text-align : center;">'.$a[0]['total'].' </td>';
							break;
							
							//	-- Affecté.
							case 3:
								$contenu .='<td>'.$a[0]['total'].'</td>';
							break;
							
							//	-- Abandon.
							case 4:
								$contenu .='<td>'.$a[0]['total'].'</td>';						
							break;

							//	-- Integré appli.
							case 5:
								$contenu .='<td>'.$a[0]['total'].'</td>';						
							break;
							
							//	-- ok .
							case 6:
								$contenu .='<td>'.$a[0]['total'].'</td>';						
							break;	
							
							//	-- Attente de décision.				
							case 7:
								$contenu .='<td>'.$a[0]['total'].'</td>';						
							break;
						}
					    
						
                        // Total des elv par cn
                        
                  }

   $contenu .='
                      </tr>
                    </tbody>';
                  }
         $contenu .=' 
                  </table>
                </div></div>';

      $contenu .='
  <div class="col-md-1"></div>
            <div class="col-md-10">
              <div class="panel panel-visible" id="spy1">
                <div class="panel-heading">
                  <div class="panel-title hidden-xs">
                    <span class="glyphicon glyphicon-tasks"></span>phs par frms </div>
                </div>
                <div class="panel-body pn">
                  <table class="table table-striped table-hover" id="datatable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>Section</th>';

                foreach ($phs as $key => $ph) {
                            $contenu .='<th>'.$ph['libelle_court'].'</th>'; 
                          }
                        
             $contenu .='
                  </tr>
                    </thead>
                    ';
 $frms_ = $baseClass->infos_frm_centre(2);

  $contenu .='      <tbody>
                      <tr>';
                    foreach ($frms_ as $key => $frm) {
                      $b = 0;
                      
              $contenu .='   <td><strong>'.$frm['libelle_frm'].'</strong></td>';
              foreach ($phs as $key => $ph) {
                // $a correspond au nombre de fois ou une ph a ete affecte trié par frm
				$params = array('id_frm'=>$frm['id_frm'],
								'id_ph'=>$ph['id_ph'],
								'date_debut'=>$date_debut,
								'date_fin'=>$date_fin);
                  $a = $baseClass->_total_ph( $params );
                        $contenu .='<td>'.$a[0]['total'].'</td>'; 
                        // Total des elv par cn
                        
                        }

   $contenu .=' 
                      </tr>
                    </tbody>';
                  }
         $contenu .=' 
                  </table>
                </div></div>';
//	 . SUPPRESSION AU DESSUS			
*/				
	//	-- BEGIN: Réecriture du tableau.
		//	-- Récupération des statistiques par frm.

	//	-- Statistiques - .
                
	$contenu .='<div class="container">
   

<div class="divimprime">
 <div class="row">
       <div class="col-md-1"></div>
  <div class="col-md-10">
        <div class="panel panel-visible" id="spy1">
          <div class="panel-heading" style="background-color : #52BE80  ;  font-weight: bold; color: white;">
            <div class="panel-title hidden-xs">
              <span class="glyphicon glyphicon-tasks"></span>Statistiques </div>
            </div>
            <div class="panel-body pn">

              <table class="table table-striped table-hover" id="datatable" cellspacing="0" width="100%">';
			  
	$params=array('id_centre'=>1,
				  'date_debut'=>$date_debut,
  				'date_fin'=>$date_fin);
    $table = $baseClass->_infrms_phs($params);
		$contenu .= $table;
		
	
	$contenu .='</table>
			</div>
		  </div>
		</div>
	  </div>  </div>
';
	
	//	-- Statistiques - .
	$contenu .='
	<div class="row">
    <div class="col-md-1"></div>
  <div class="col-md-10">
        <div class="panel panel-visible" id="spy1">
          <div class="panel-heading" style="background-color : #F08080  ;  font-weight: bold; color: white;">
            <div class="panel-title hidden-xs">
              <span class="glyphicon glyphicon-tasks"></span>Statistiques</div>
            </div>
            <div class="panel-body pn">
              <table class="table table-striped table-hover" id="datatable" cellspacing="0" width="100%">';
			  
	$params=array('id_centre'=>2,
				  'date_debut'=>$date_debut,
  				'date_fin'=>$date_fin);
    $table = $baseClass->_infrms_phs($params);
		$contenu .= $table;

	$contenu .='
  </table>
			</div>
		  </div>
		</div> </div>
	    
	';	

  //  -- Statistiques - GLOBAL.

 $contenu .='
  <div class="row">
    <div class="col-md-1"></div>
  <div class="col-md-10">
        <div class="panel panel-visible" id="spy1">
          <div class="panel-heading" style="background-color : #5DADE2;  font-weight: bold; color: white;">
            <div class="panel-title hidden-xs">
              <span class="glyphicon glyphicon-tasks"></span>Statistiques GLOBAL</div>
            </div>
            <div class="panel-body pn">
              <table class="table table-striped table-hover" id="datatable" cellspacing="0" width="100%">';
        
  $params=array(
          'date_debut'=>$date_debut,
          'date_fin'=>$date_fin);
    $table = $baseClass->_infrms_phs($params);
    $contenu .= $table;

  $contenu .='
  </table>
      </div>
      </div>
    </div> </div>
      </div>
  </div>
  </div>';  
					
	//	-- END: Réecriture du tableau.					
					
					
					
 $frms_ = $baseClass->infos_frm_centre(2);

  }


if($mode =='ph_cn'){

/* % venu à ok par cn  */
  $venuRI = $baseClass->infos_ph_libelle(2);
  $ok = $baseClass->infos_ph_libelle(3);
  $okA = $baseClass->infos_ph_libelle(7);

 
  $contenu .='
  <div class="col-md-3"></div>
            <div class="col-md-6">
              <div class="panel panel-visible" id="spy1">
                <div class="panel-heading">
                  <div class="panel-title hidden-xs">
                    <span class="glyphicon glyphicon-tasks"></span>% venu en RI à ok par cn (ok = initial + alternance)</div>
                </div>
                <div class="panel-body pn">
                  <table class="table table-striped table-hover" id="datatable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>ph</th>';

                $contenu .='<th style="text-align : center;">'.$venuRI[0]['libelle_court'].'</th>
                            <th style="text-align : center;">% ok</th>
                            <th style="text-align : center;">ok</th>'; 
                          
                        
             $contenu .='
                  </tr>
                    </thead>
                    ';
  $cns = $baseClass->infos_cns();



  $contenu .='      <tbody>
                      <tr>';
                    foreach ($cns as $key => $cn) {
                      $b = 0;
                      
              $contenu .='   <td><strong>'.$cn['prenom_cn'].' '.$cn['nom_cn'].'</strong></td>';
            
                
                  $venuriT = $baseClass->total_ph_cn($cn['id_cn'], $venuRI[0]['id_ph']);
                   $okT = $baseClass->total_ph_cn($cn['id_cn'], $ok[0]['id_ph']);
                    $okaT = $baseClass->total_ph_cn($cn['id_cn'], $okA[0]['id_ph']);
                    /* Total ok initial et alternance */
                    $ok_total =  $okaT[0]['total'] + $okT[0]['total'];

                        $contenu .='<td style="text-align : center;">'.$venuriT[0]['total'].'</td>
                        <td style="text-align : center;">'.round($ok_total / $venuriT[0]['total']  * 100, 1).' %</td>
                        <td style="text-align : center;">'.$ok_total.'</td>'; 
                        /* Total des elv par cn*/
                        
                        

   $contenu .='
                      </tr>
                    </tbody>';
                  }
         $contenu .=' 
                  </table>
                </div></div></div>';

/*  STATISTIQUES DES phS :: ph actuelle de tous les candidats */
// Nombre d'elv par phS pour chaques cns
   $cns = $baseClass->infos_cns();
   $phs = $baseClass->infos_ph();

 
  $contenu .='
  <div class="col-md-1"></div>
            <div class="col-md-12">
              <div class="panel panel-visible" id="spy1">
                <div class="panel-heading">
                  <div class="panel-title hidden-xs">
                    <span class="glyphicon glyphicon-tasks"></span>phs par cns</div>
                </div>
                <div class="panel-body pn">
                  <table class="table table-striped table-hover" id="datatable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>Section</th>';

                foreach ($phs as $key => $ph) {
                            $contenu .='<th style="text-align : center;">'.$ph['libelle_court'].'</th>'; 
                          }
                        
             $contenu .='
                  </tr>
                    </thead>
                    ';
 $cns = $baseClass->infos_cns();



  $contenu .='      <tbody>
                      <tr>';
                    foreach ($cns as $key => $cn) {
                      $b = 0;
                      
              $contenu .='   <td><strong>'.$cn['prenom_cn'].' '.$cn['nom_cn'].'</strong></td>';
              foreach ($phs as $key => $ph) {
                /* $a correspond au nombre de fois ou une ph a ete affecte trié par cn*/
                  $a = $baseClass->total_ph_cn($cn['id_cn'], $ph['id_ph']);
                        $contenu .='<td style="text-align : center;">'.$a[0]['total'].'</td>'; 
                        /* Total des elv par cn*/
                        
                        }

   $contenu .='
                      </tr>
                    </tbody>';
                  }
         $contenu .=' 
                  </table>
                </div></div>';
}

if($mode =='frm_cn'){

// elv par frms pour chaques cns
     $frms = $baseClass->infos_frm_stat_();
  
  $contenu .='
         
        
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="panel panel-visible" id="spy1">
                <div class="panel-heading">
                  <div class="panel-title hidden-xs">
                    <span class="glyphicon glyphicon-tasks"></span> // Attribution des els : Toutes les sessions</div>
                </div>
                <div class="panel-body pn">
                  <table class="table table-striped table-hover" id="datatable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>cn</th>';

                foreach ($frms as $key => $frm) {
                            $contenu .='<th>'.$frm['libelle_frm'].'</th>'; 
                          }
                        
             $contenu .='<th>Total</th>
                  </tr>
                    </thead>
                    ';
 $cns = $baseClass->infos_cn_();



  $contenu .='      <tbody>
                      <tr>';
                    foreach ($cns as $key => $cn) {
                      $b = 0;
              $contenu .='   <td><strong>'.$cn['prenom_cn'].' '.$cn['nom_cn'].' </strong></td>';
              foreach ($frms as $key => $frm) {
                  $a = $baseClass->cns_elv($cn['id_cn'], $frm['id_frm']);
                  /* Nb d'els par cn et frm */
                        $contenu .='<td style="text-align : center;">'.$a[0]['elv'].'</td>'; 
                        /* Total des elv par cn*/
                        $b = $b + $a[0]['elv'];
                        }
                        $contenu .='<td><span style="color: red;">'.$b.'<span></td>'; 


   $contenu .='   </tr>
                    </tbody>';
                  }
         $contenu .=' 
                  </table>
                </div></div></div>
                
              ';        

  // elv par frms pour chaques cns
     $frms = $baseClass->infos_frm_stat_();
  
  $contenu .='
         
       
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="panel panel-visible" id="spy1">
                <div class="panel-heading">
                  <div class="panel-title hidden-xs">
                 
                </div>
                <div class="panel-body pn">
                  <table class="table table-striped table-hover" id="datatable" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>cn</th>';

                foreach ($frms as $key => $frm) {
                            $contenu .='<th>'.$frm['libelle_frm'].'</th>'; 
                          }
                        
             $contenu .='<th>Total</th>
                  </tr>
                    </thead>
                    ';
    $params = array('id_cn' => 6);                 
 $cns = $baseClass->infos_cns($params); 



  $contenu .='      <tbody>
                      <tr>';
                    foreach ($cns as $key => $cn) {
                      $b = 0;
              $contenu .='   <td><strong>'.$cn['prenom_cn'].' '.$cn['nom_cn'].' </strong></td>';
              foreach ($frms as $key => $frm) {
                  $a = $baseClass->cns_elv($cn['id_cn'], $frm['id_frm']);
                  /* Nb d'els par cn et frm */
                        $contenu .='<td style="text-align : center;">'.$a[0]['elv'].'</td>'; 
                        /* Total des elv par cn*/
                        $b = $b + $a[0]['elv'];
                        }
                        $contenu .='<td><span style="color: red;">'.$b.'<span></td>'; 


   $contenu .='   </tr>
                    </tbody>';
                  }
         $contenu .=' 
                  </table>
                </div></div></div>
                  </div></div>
              ';        
            

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


