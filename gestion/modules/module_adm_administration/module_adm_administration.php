<?php
/*  --------------------------------------------------------------------------------------------

                        MODULE ADMINISTRATION DE LA BDD 
            
  --------------------------------------------------------------------------------------------  */
  
  
    
  //  --- initialisation de la session
  session_start();
  
  
  
  
  //  --- Initialisation des variables.
  $module =$classBase->auto_variable('module');
  $mode = $classBase->auto_variable('mode');
  $formvars = $classBase->auto_variable('formvars');

  //  --- Debug
  
  //  -----------------------------------------------------------------------------------------------------
  


  /*  --- BEGIN: Contenu  -------------------------------------------------------------------
  ------------------------------------------------------------------------------------------- */    
if ($mode == 'pays') {
   
    $params = array('module' => $module,
                    'mode' => 'ajout_pays');
    $url_ajout_pays = $classBase->creation_adm_url($params);

  
  $contenu .='
  <div class="row">
   <div class="col-md-5"></div>
    <div class="col-md-6"><br>
  <a href="'.$url_ajout_pays.'" class="btn btn-info">
  <input name="module" type=hidden value="'.$module.'">
<input name="mode" type=hidden value="ajout_pays">
  Ajouter un pays</a>
  </div>
    </div>

  <br>
   <div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
 <table class="table table-hover">
  <thead>
    <tr>
      <th>id</th>
      <th>Nom du pays</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>';
   $tb_pays = $classBase->infos_pays();
   foreach ($tb_pays as $key => $value) {
   
  //  -- Création de l'url pour la suppression.
  $params = array('module'=>$module,
          'mode'=>'suppression_pays',
          'edit_id'=>$value['id_pays']);
  $url_suppression_pays = $classBase->creation_adm_url($params); 
  //  -- Création de l'url pour la modification.
  $params = array('module'=>$module,
          'mode'=>'modification_pays',
          'edit_id'=>$value['id_pays']);
  $url_modification_pays = $classBase->creation_adm_url($params);
    
    if($value['deleted'] < 1 ){ /* On affiche seulement les pays actifs */
  $contenu .='    <tr style="background-color : white;">
      <td>'.$value['id_pays'].'</td>
      <td>'.$value['nom_pays'].'</td>
      <td><a href="'.$url_modification_pays.'" class="btn btn-success btn-sm light fw600 ml10">
            Modifier</a>         
      </td>
      <td><a href="'.$url_suppression_pays.'" class="btn btn-danger btn-sm light fw600 ml10">
            <span class="fa fa-times pr5"></span> Supprimer</a>         
      </td>
    </tr>';
  }}
  $contenu .=' </tbody>
</table>
<h3>Informations sur les pays</h3>
<p>Ce sont les pays qui sont affichés dans les formulaires d\'inscription</p>
<p>Les candidats qui selectionnent AUTRE ne sont pas enregistrés dans la base</p>
<p>Changement a signaler au service SI !</p>

</div>
</div>
<br><br>

';
  }

  if($mode == 'ajout_pays') { 
    $params = array('module' => $module,
                    'mode' => 'validation_pays');
    $url_validation_pays = $classBase->creation_adm_url($params);

 $contenu .='
<div class="row">
   <div class="col-md-5"></div>
    <div class="col-md-6"><br>
    <form action="index.php" method="post" enctype="multipart/form-data">
    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="validation_pays">
                    <div class="section">
                      <label for="pays" class="field prepend-icon">
                      <p>Nom du pays : </p> 
                        <input type="text" class="form-control" id="formvars[nom_pays]" name="formvars[nom_pays]" placeholder="Nouveau pays" required="required">
                        <button class="btn btn-primary" type="submit" name="submit_new" >Valider</button>
                      </label>
</form>
                    </div> 
    </div>
</div>
    ';
  }

if($mode == 'validation_pays') {  
$formvars = $classBase->auto_variable('formvars');
$tbl_pays = $classBase->nom_table('pays');
$params = array('cle_primaire'=>'id_pays');
 // -- Requête d'insertion
    $requete = $classBase->creation_requete_insertion($tbl_pays,
        $formvars);
    $ok = $classBase->requete_sql($db, $requete);
    /* Renvoi sur la page des pays de l'ancienne appli */
    header("Refresh:0; url=supr");
    
    echo $requete;


}
 
 if ($mode == 'suppression_pays') {
      $edit_id = $classBase->auto_variable('edit_id');
      $tbl_pays = $classBase->nom_table('pays');
      $params = array('cle_primaire'=>'id_pays');
      if ($edit_id  > 0) {
        $formvars['deleted'] = 1;
        $requete = $classBase->creation_requete_modification($tbl_pays,
        $formvars, $edit_id, $params);
        
        $ok = $classBase->requete_sql($db, $requete);
        header("Refresh:0; url=supr");
       /* echo $requete;*/
      }
 }

if($mode == 'modification_pays') { 

  $edit_id = $classBase->auto_variable('edit_id');
  $params = array('id_pays' => $edit_id);
  $edit_pays = $classBase->infos_pays($params);
    $params = array('module' => $module,
                    'mode' => 'confirmation_modification_pays',
                   'edit_id'=> $edit_id );
    $url_confirmation_modification_pays = $classBase->creation_adm_url($params);
 $contenu .='
<div class="row">
   <div class="col-md-3"></div>
    <div class="col-md-8"><br>
    <form action="index.php" method="post" enctype="multipart/form-data">
    <input name="edit_id" type=hidden value="'.$edit_id.'">

    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="confirmation_modification_pays">

                    <div class="section">
                      <label for="pays" class="field prepend-icon">
                      <p>Nom du pays : </p> 
                        <input type="text" class="form-control" id="formvars[nom_pays]" name="formvars[nom_pays]" placeholder="" required="required" value="'.$edit_pays[0]['nom_pays'].'">
                        <button class="btn btn-primary" type="submit">Valider</button>
                      </label>
                    </div> 
    </form></div>
</div>
    ';
  }

  if ($mode == 'confirmation_modification_pays') {
      $edit_id = $classBase->auto_variable('edit_id');
      $formvars = $classBase->auto_variable('formvars');
      $tbl_pays = $classBase->nom_table('pays');
      $params = array('cle_primaire'=>'id_pays');

      if ($edit_id  > 0) {
        $requete = $classBase->creation_requete_modification($tbl_pays,
        $formvars, $edit_id, $params);
        $ok = $classBase->requete_sql($db, $requete);
        header("Refresh:0; url=supr");
       
      }
}
   //  --- Edition des niveau
  if($mode == 'niveaux') { 
   
    $params = array('module' => $module,
                    'mode' => 'ajout_niveau');
    $url_ajout_niveau = $classBase->creation_adm_url($params);

  
  $contenu .='
  <div class="row">
   <div class="col-md-5"></div>
    <div class="col-md-6"><br>
  <a href="'.$url_ajout_niveau.'" class="btn btn-info">
  <input name="module" type=hidden value="'.$module.'">
<input name="mode" type=hidden value="ajout_niveau">
  Ajouter un niveau</a>
  </div>
    </div>

  <br>
   <div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
 <table class="table table-hover">
  <thead>
    <tr>
      <th>id</th>
      <th>Nom du niveau</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>';
  
   $tb_niveau = $classBase->infos_niveau();
   foreach ($tb_niveau as $key => $value) {
   
  //  -- Création de l'url pour la suppression.
  $params = array('module'=>$module,
          'mode'=>'suppression_niveau',
          'edit_id'=>$value['id_niveau']);
  $url_suppression_niveau = $classBase->creation_adm_url($params); 
  //  -- Création de l'url pour la modification.
  $params = array('module'=>$module,
          'mode'=>'modification_niveau',
          'edit_id'=>$value['id_niveau']);
  $url_modification_niveau = $classBase->creation_adm_url($params);
    
    if($value['deleted'] < 1 ){ /* On affiche seulement les niveaux actifs */
  $contenu .='    <tr style="background-color : white;">
      <td>'.$value['id_niveau'].'</td>
      <td>'.$value['libelle_niveau'].'</td>
      <td><a href="'.$url_modification_niveau.'" class="btn btn-success btn-sm light fw600 ml10">
            Modifier</a>         
      </td>
      <td><a href="'.$url_suppression_niveau.'" class="btn btn-danger btn-sm light fw600 ml10">
            <span class="fa fa-times pr5"></span> Supprimer</a>         
      </td>
    </tr>';
  }}
  $contenu .=' </tbody>
</table>
</div>
</div>
<br><br>

';
  }

  if($mode == 'ajout_niveau') { 
    $params = array('module' => $module,
                    'mode' => 'validation_niveau');
    $url_validation_niveau = $classBase->creation_adm_url($params);

 $contenu .='
<div class="row">
   <div class="col-md-5"></div>
    <div class="col-md-6"><br>
    <form action="index.php" method="post" enctype="multipart/form-data">
    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="validation_niveau">

                    <div class="section">
                      <label for="niveau" class="field prepend-icon">
                      <p>Nom de la niveau : </p> 
                        <input type="text" class="form-control" id="formvars[libelle_niveau]" name="formvars[libelle_niveau]" placeholder="Nouveau niveau" required="required">
                        <button class="btn btn-primary" type="submit" name="submit_new" >Valider</button>
                      </label>
</form>
                    </div> 
    </div>
</div>
    ';
  }

if($mode == 'validation_niveau') {  
$formvars = $classBase->auto_variable('formvars');
$tbl_niveau = $classBase->nom_table('niveau');
$params = array('cle_primaire'=>'id_niveau');
 // -- Requête d'insertion
    $requete = $classBase->creation_requete_insertion($tbl_niveau,
        $formvars);
    $ok = $classBase->requete_sql($db, $requete);
    /* Renvoi sur la page des niveaux de l'ancienne appli */
    header("Refresh:0; url=http://supr");
    
    echo $requete;
}
 
 if ($mode == 'suppression_niveau') {
      $edit_id = $classBase->auto_variable('edit_id');
      $tbl_niveau = $classBase->nom_table('niveau');
      $params = array('cle_primaire'=>'id_niveau');
      if ($edit_id  > 0) {
        $formvars['deleted'] = 1;
        $requete = $classBase->creation_requete_modification($tbl_niveau,
        $formvars, $edit_id, $params);
        
        $ok = $classBase->requete_sql($db, $requete);
        header("Refresh:0; url=http://supr");
       /* echo $requete;*/
      }
 }

if($mode == 'modification_niveau') { 

  $edit_id = $classBase->auto_variable('edit_id');
  $params = array('id_niveau' => $edit_id);
  $edit_niveau = $classBase->infos_niveau($params);
    $params = array('module' => $module,
                    'mode' => 'confirmation_modification_niveau',
                   'edit_id'=> $edit_id );
    $url_confirmation_modification_niveau = $classBase->creation_adm_url($params);
 $contenu .='
<div class="row">
   <div class="col-md-3"></div>
    <div class="col-md-8"><br>
    <form action="index.php" method="post" enctype="multipart/form-data">
    <input name="edit_id" type=hidden value="'.$edit_id.'">

    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="confirmation_modification_niveau">

                    <div class="section">
                      <label for="niveau" class="field prepend-icon">
                      <p>Nom de la niveau : </p> 
                        <input type="text" class="form-control" id="formvars[libelle_niveau]" name="formvars[libelle_niveau]" placeholder="" required="required" value="'.$edit_niveau[0]['libelle_niveau'].'">
                        <button class="btn btn-primary" type="submit">Valider</button>
                      </label>
                    </div> 
    </form></div>
</div>
    ';
  }

  if ($mode == 'confirmation_modification_niveau') {
      $edit_id = $classBase->auto_variable('edit_id');
      $formvars = $classBase->auto_variable('formvars');
      $tbl_niveau = $classBase->nom_table('niveau');
      $params = array('cle_primaire'=>'id_niveau');

      if ($edit_id  > 0) {
        $requete = $classBase->creation_requete_modification($tbl_niveau,
        $formvars, $edit_id, $params);
        $ok = $classBase->requete_sql($db, $requete);
        header("Refresh:0; url=supr");
       
      }
 }
  //  --- Edition des formations
  if($mode == 'formations') { 
    $tb_formation = $classBase->infos_formation();
    $params = array('module' => $module,
                    'mode' => 'ajout_formation');
    $url_ajout_formation = $classBase->creation_adm_url($params);

  
  $contenu .='
  <div class="row">
   <div class="col-md-5"></div>
    <div class="col-md-6"><br>
  <a href="'.$url_ajout_formation.'" class="btn btn-info">
  <input name="module" type=hidden value="'.$module.'">
<input name="mode" type=hidden value="ajout_formation">
  Ajouter une formation</a>
  </div>
    </div>

  <br>
   <div class="row">
  <div class="col-md-2"></div>
  <div class="col-md-8">
 <table class="table table-hover">
  <thead>
    <tr>
      <th>Code</th>
      <th>Nom</th>
      <th>Lieu</th>
      <th>Formulaire ?</th>
      <th>Formulaire  ?</th>
      <th></th>
    </tr>
  </thead>
  <tbody>';
   foreach ($tb_formation as $key => $value) {

  //  -- Création de l'url pour la suppression.
  $params = array('module'=>$module,
          'mode'=>'suppression',
          'edit_id'=>$value['id_formation']);
  $url_suppression = $classBase->creation_adm_url($params); 
  //  -- Création de l'url pour la modification.
  $params = array('module'=>$module,
          'mode'=>'modification',
          'edit_id'=>$value['id_formation']);
  $url_modification = $classBase->creation_adm_url($params);

  if ($value['id_centre'] == 1) {
    $centre = 'Supr';
  }else{
  $centre = 'Paris';}
    if ($value['formI'] == 1) {
    $ig = 'Oui';
  }else{
  $ig = 'Non';}
     if ($value['form'] == 1) {
    $it = 'Oui';
  }else{
  $it = 'Non';}
    
    if($value['deleted'] < 1 ){ /* On affiche seulement les formations actives */
  $contenu .='    <tr style="background-color : white;">
      <td>'.$value['id_formation'].'</td>
      <td>'.$value['libelle_formation'].'</td>
      <td>'.$centre.'</td>
      <td>'.$i.'</td>
      <td>'.$it.'</td>
      <td><a href="'.$url_modification.'" class="btn btn-success btn-sm light fw600 ml10">
            Modifier</a>         
      </td>
      <td><a href="'.$url_suppression.'" class="btn btn-danger btn-sm light fw600 ml10">
            <span class="fa fa-times pr5"></span> Supprimer</a>         
      </td> 
    </tr>';
  }}
  $contenu .=' </tbody>
</table>
</div>
</div>
<br><br>

';
  }

  if($mode == 'ajout_formation') { 
    $params = array('module' => $module,
                    'mode' => 'validation_formation');
    $url_validation = $classBase->creation_adm_url($params);

 $contenu .='
<div class="row">
   <div class="col-md-2"></div>
    <div class="col-md-8"><br>
    <form action="index.php" method="post" enctype="multipart/form-data">
    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="validation_formation">
      <div class="panel">
              <div class="panel-heading">
                <span class="panel-title">Ajouter une formation</span>
              </div>
              <div class="panel-body">
                  <div class="form-group">
                  <div class="row">
                    <label for="inputStandard" class="col-lg-3 control-label">Nom de la formation</label>
                    <div class="col-lg-8">          
                        <input type="text" id="inputStandard" class="form-control" name="formvars[libelle_formation]" required>
                    </div>
                  </div></div>
                  <div class="form-group">
                  <div class="row">
                    <label for="inputSelect" class="col-lg-3 control-label">Lieu</label>
                    <div class="col-lg-2">
                        <select class="form-control" name="formvars[id_centre]">
                          <option value="1">Evry</option>
                          <option value="2">Paris</option>
                        </select>                    
                  </div></div>
                </div>
                <br/>
                  <div class="form-group">
                  <div class="row">
                  <div class="col-lg-2"></div>
                    <h4 class="col-lg-8 control-label">La formation doit être affichée sur les formulaires des sites:</h4>
                  </div></div>
                  <div class="form-group">
                  <div class="row">
                  <div class="col-lg-2"></div>
                    <p class="col-lg-1" style="font-size: 25px;">It</p>
                    <div class="col-lg-2">              
                        <select class="form-control" name="formvars[formit]">
                          <option value="1">Oui</option>
                          <option value="0">Non</option>
                        </select>                  
                  </div>
                  <div class="col-lg-1"></div>
                  <p class="col-lg-2" style="font-size: 25px;">In</p>
                    <div class="col-lg-2">              
                        <select class="form-control" name="formvars[formig]">
                          <option value="1">Oui</option>
                          <option value="0">Non</option>
                        </select>                   
                  </div></div>
                </div>
                <br/>
                <div class="form-group">
                <div class="row">
                <div class="col-lg-1"></div>
                    <h4 class="col-lg-11 control-label">Les candidats a cette formation doivent-ils recevoir une convocation par e-mail ?</h4>
                </div></div>
                <div class="row"><div class="col-lg-4"></div>
                    <div class="col-lg-3">            
                        <select class="form-control" name="formvars[bts]">
                          <option value="1">Oui</option>
                          <option value="0">Non</option>
                        </select>                    
                  </div>
                </div> <br/>
               <div class="col-lg-10"></div>
                <button class="btn btn-primary" type="submit" name="submit_new" >Valider</button>
              </div>
            </div>


                    
</form>
<p>Si vous voulez ajouter une meme formation pour DEUX etablissements : <br/>Creer deux fois la formation : La premiere en mettant le lieu supr et la deuxieme avec le lieu r</p>
     </div> 
    </div>
</div>
    ';
  }

if($mode == 'validation_formation') {  
$formvars = $classBase->auto_variable('formvars');
$tbl_formation = $classBase->nom_table('formation');
$params = array('cle_primaire'=>'id_formation');

// A COMPLETER --- CREATION DU CODE DE FORMATION 

// -- Recherche de la derniere entree code formation
// -- Creation du CODE de la formation
//$formvars['code'] = $formvars['id_centre'] + $formvars['categorie'];
//print_r($formvars['code']); exit;

 // -- Requête d'insertion
    $requete = $classBase->creation_requete_insertion($tbl_formation,
        $formvars);
    print_r($requete);
    $ok = $classBase->requete_sql($db, $requete);
    /* Renvoi sur la page des formations de l'ancienne appli */
    header("Refresh:0; url=http://supr");


}
 
 if ($mode == 'suppression') {
      $edit_id = $classBase->auto_variable('edit_id');
      $tbl_formation = $classBase->nom_table('formation');
      $params = array('cle_primaire'=>'id_formation');
      if ($edit_id  > 0) {
        $formvars['deleted'] = 1;
        $requete = $classBase->creation_requete_modification($tbl_formation,
        $formvars, $edit_id, $params);
        
        $ok = $classBase->requete_sql($db, $requete);
        header("Refresh:0; url=http://supr");
      }
 }

if($mode == 'modification') { 

  $edit_id = $classBase->auto_variable('edit_id');
  $params = array('id_formation' => $edit_id);
  $edit_formation = $classBase->infos_formation($params);

    $params = array('module' => $module,
                    'mode' => 'confirmation_modification',
                   'edit_id'=> $edit_id );
    $url_confirmation_modification = $classBase->creation_adm_url($params);

 $contenu .='
<div class="row">
   <div class="col-md-2"></div>
    <div class="col-md-8"><br>
    <form action="index.php" method="post" enctype="multipart/form-data">
    <input name="edit_id" type=hidden value="'.$edit_id.'">

    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="confirmation_modification">

          <div class="panel">
              <div class="panel-heading">
                <span class="panel-title">Ajouter une formation</span>
              </div>
              <div class="panel-body">
                  <div class="form-group">
                  <div class="row">
                    <label for="inputStandard" class="col-lg-3 control-label">Nom de la formation</label>
                    <div class="col-lg-8">          
                        <input type="text" id="inputStandard" class="form-control" name="formvars[libelle_formation]" required="required" value="'.$edit_formation[0]['libelle_formation'].'">
                    </div>';
 if ($edit_formation[0]['id_centre'] == 1) {$centre[0]['id_centre'] = 'E';}else{$centre[0]['id_centre'] = 'P';}                     
  $contenu.='                  
                  </div></div>
                  <div class="form-group">
                  <div class="row">
                    <label for="inputSelect" class="col-lg-3 control-label">Lieu</label>
                    <div class="col-lg-2">
                        <select class="form-control" name="formvars[id_centre]">
                          <option value="'.$edit_formation[0]['id_centre'].'">'.$centre[0]['id_centre'].'</option>
                          <option value="1">E</option>
                          <option value="2">P</option>
                        </select>                    
                  </div></div>
                </div>';
   if ($edit_formation[0]['form'] == 1) {$i[0]['formit'] = 'Oui';}else{$i[0]['forms'] = 'Non';}               
 $contenu .='               
                <br/>
                  <div class="form-group">
                  <div class="row">
                  <div class="col-lg-2"></div>
                    <h4 class="col-lg-8 control-label">La formation doit être affichée sur les formulaires des sites:</h4>
                  </div></div>
                  <div class="form-group">
                  <div class="row">
                  <div class="col-lg-2"></div>
                    <p class="col-lg-1" style="font-size: 25px;">I</p>
                    <div class="col-lg-2">              
                        <select class="form-control" name="formvars[formit]">
                           <option value="'.$edit_formation[0]['formit'].'">'.$it[0]['formit'].'</option>
                          <option value="1">Oui</option>
                          <option value="0">Non</option>
                        </select>                  
                  </div>';
  if ($edit_formation[0]['formig'] == 1) {$ig[0]['formig'] = 'Oui';}else{$ig[0]['formig'] = 'Non';}                  
$contenu.='                  
                  <div class="col-lg-1"></div>
                  <p class="col-lg-2" style="font-size: 25px;">ig</p>
                    <div class="col-lg-2">              
                        <select class="form-control" name="formvars[formig]">
                           <option value="'.$edit_formation[0]['formig'].'">'.$ig[0]['formig'].'</option>
                          <option value="1">Oui</option>
                          <option value="0">Non</option>
                        </select>                   
                  </div></div>
                </div>
                <br/>
                <div class="form-group">
                <div class="row">
                <div class="col-lg-1"></div>
                    <h4 class="col-lg-11 control-label">Les candidats a cette formation doivent-ils recevoir une convocation par e-mail ?</h4>
                </div></div>
                <div class="row"><div class="col-lg-4"></div>';
   if ($edit_formation[0]['bts'] == 1) {$bts[0]['bts'] = 'Oui';}else{$bts[0]['bts'] = 'Non';}         
 $contenu .='   <div class="col-lg-3">            
                        <select class="form-control" name="formvars[bts]">
                           <option value="'.$edit_formation[0]['bts'].'">'.$bts[0]['bts'].'</option>
                          <option value="1">Oui</option>
                          <option value="0">Non</option>
                        </select>                    
                  </div>
                </div> <br/>
               <div class="col-lg-10"></div>
               <button class="btn btn-primary" type="submit">Valider</button>
              </div>
            </div>
                        

    </form>            
    </div>
</div>
    ';
  }

  if ($mode == 'confirmation_modification') {
      $edit_id = $classBase->auto_variable('edit_id');
      $formvars = $classBase->auto_variable('formvars');
      $tbl_formation = $classBase->nom_table('formation');
      $params = array('cle_primaire'=>'id_formation');
    
      if ($edit_id  > 0) {
        $requete = $classBase->creation_requete_modification($tbl_formation,
        $formvars, $edit_id, $params);
        $ok = $classBase->requete_sql($db, $requete);
        header("Refresh:0; url=http://supr");
       
      }
 }


  //  --- Edition des provenances
  if($mode == 'provenances') { 
 $params = array('module' => $module,
                    'mode' => 'ajout_provenance');
    $url_ajout_provenance = $classBase->creation_adm_url($params);

  
  $contenu .='
  <div class="row">
   <div class="col-md-5"></div>
    <div class="col-md-6"><br>
  <a href="'.$url_ajout_provenance.'" class="btn btn-info">
  <input name="module" type=hidden value="'.$module.'">
  <input name="mode" type=hidden value="ajout_provenance">
  Ajouter un provenance</a>
  </div>
    </div>
<br>
  <div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
   <table class="table table-hover">
    <thead>
      <tr>
        <th>id</th>
        <th>Nom du provenance</th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>';
   $tb_provenance = $classBase->infos_provenance();
   foreach ($tb_provenance as $key => $value) {
   
  //  -- Création de l'url pour la suppression.
  $params = array('module'=>$module,
          'mode'=>'suppression_provenance',
          'edit_id'=>$value['id_provenance']);
  $url_suppression_provenance = $classBase->creation_adm_url($params); 
  //  -- Création de l'url pour la modification.
  $params = array('module'=>$module,
          'mode'=>'modification_provenance',
          'edit_id'=>$value['id_provenance']);
  $url_modification_provenance = $classBase->creation_adm_url($params);
    
    if($value['deleted'] < 1 ){ /* On affiche seulement les provenances actives */
  $contenu .='    <tr style="background-color : white;">
      <td>'.$value['id_provenance'].'</td>
      <td>'.$value['libelle_provenance'].'</td>
      <td><a href="'.$url_modification_provenance.'" class="btn btn-success btn-sm light fw600 ml10">
            Modifier</a>         
      </td>
      <td><a href="'.$url_suppression_provenance.'" class="btn btn-danger btn-sm light fw600 ml10">
            <span class="fa fa-times pr5"></span> Supprimer</a>         
      </td>
    </tr>';
  }}
  $contenu .=' </tbody>
</table>
</div>
</div>
<br><br>

';
  }

  if($mode == 'ajout_provenance') { 
    $params = array('module' => $module,
                    'mode' => 'validation_provenance');
    $url_validation_provenance = $classBase->creation_adm_url($params);

 $contenu .='
<div class="row">
   <div class="col-md-5"></div>
    <div class="col-md-6"><br>
    <form action="index.php" method="post" enctype="multipart/form-data">
    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="validation_provenance">

                    <div class="section">
                      <label for="provenance" class="field prepend-icon">
                      <p>Nom de la provenance : </p> 
                        <input type="text" class="form-control" id="formvars[libelle_provenance]" name="formvars[libelle_provenance]" placeholder="Nouveau provenance" required="required">
                        <button class="btn btn-primary" type="submit" name="submit_new" >Valider</button>
                      </label>
    </form>         </div> 
    </div>
</div>
    ';
  }

if($mode == 'validation_provenance') {  
$formvars = $classBase->auto_variable('formvars');
$tbl_provenance = $classBase->nom_table('provenance');
$params = array('cle_primaire'=>'id_provenance');
 // -- Requête d'insertion
    $requete = $classBase->creation_requete_insertion($tbl_provenance,
        $formvars);
    $ok = $classBase->requete_sql($db, $requete);
    /* Renvoi sur la page des provenances de l'ancienne appli */
    header("Refresh:0; url=supr");

}
 
 if ($mode == 'suppression_provenance') {
      $edit_id = $classBase->auto_variable('edit_id');
      $tbl_provenance = $classBase->nom_table('provenance');
      $params = array('cle_primaire'=>'id_provenance');
      if ($edit_id  > 0) {
        $formvars['deleted'] = 1;
        $requete = $classBase->creation_requete_modification($tbl_provenance,
        $formvars, $edit_id, $params);
        
        $ok = $classBase->requete_sql($db, $requete);
        header("Refresh:0; url=supr");
      }
 }

if($mode == 'modification_provenance') { 

  $edit_id = $classBase->auto_variable('edit_id');
  $params= array('id_provenance' =>$edit_id );
  $edit_provenance = $classBase->infos_provenance($params);

    $params = array('module' => $module,
                    'mode' => 'confirmation_modification_provenance',
                   'edit_id'=> $edit_id);
    $url_confirmation_modification_provenance = $classBase->creation_adm_url($params);

 $contenu .='
<div class="row">
   <div class="col-md-3"></div>
    <div class="col-md-8"><br>
    <form action="index.php" method="post" enctype="multipart/form-data">
    <input name="edit_id" type=hidden value="'.$edit_id.'">

    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="confirmation_modification_provenance">

                    <div class="section">
                      <label for="provenance" class="field prepend-icon">
                      <p>Nom de la provenance : </p> 
                        <input type="text" class="form-control" id="formvars[libelle_provenance]" name="formvars[libelle_provenance]" placeholder="" required="required" value="'.$edit_provenance[0]['libelle_provenance'].'">
                        <button class="btn btn-primary" type="submit">Valider</button>
                      </label>
                    </div> 
    </form>                
    </div>
</div>
    ';
  }

  if ($mode == 'confirmation_modification_provenance') {

      $edit_id = $classBase->auto_variable('edit_id');
      $formvars = $classBase->auto_variable('formvars');
      $tbl_provenance = $classBase->nom_table('provenance');
      $params = array('cle_primaire'=>'id_provenance');
     
        $requete = $classBase->creation_requete_modification($tbl_provenance,
        $formvars, $edit_id, $params);
        print_r($requete);
        $ok = $classBase->requete_sql($db, $requete);
        header("Refresh:0; url=supr");
  }


  //  --- Edition des sessions
  if($mode == 'sessions') { 
    $params = array('module' => $module,
                    'mode' => 'ajout_session');
    $url_ajout_session = $classBase->creation_adm_url($params);

  $contenu .='
  <div class="row">
   <div class="col-md-5"></div>
    <div class="col-md-6"><br>
  <a href="'.$url_ajout_session.'" class="btn btn-info">
    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="ajout_session">
    Ajouter un session</a>
  </div>
    </div>
  <br>
   <div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
  <table class="table table-hover">
  <thead>
    <tr>
      <th>id</th>
      <th>Nom de la session</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>';
   $params = array();
   $tb_session = $classBase->infos_session($params);
   foreach ($tb_session as $key => $value) {
   
  //  -- Création de l'url pour la suppression.
  $params = array('module'=>$module,
          'mode'=>'suppression_session',
          'edit_id'=>$value['id_session']);
  $url_suppression_session = $classBase->creation_adm_url($params); 
  //  -- Création de l'url pour la modification.
  $params = array('module'=>$module,
          'mode'=>'modification_session',
          'edit_id'=>$value['id_session']);
  $url_modification_session = $classBase->creation_adm_url($params);
    
    if($value['deleted'] < 1 ){ /* On affiche seulement les sessions actives */
$contenu .='    <tr style="background-color : white;">
      <td>'.$value['id_session'].'</td>
      <td>'.$value['libelle'].'</td>
      <td><a href="'.$url_modification_session.'" class="btn btn-success btn-sm light fw600 ml10">
            Modifier</a>         
      </td>
      <td><a href="'.$url_suppression_session.'" class="btn btn-danger btn-sm light fw600 ml10">
            <span class="fa fa-times pr5"></span> Supprimer</a>         
      </td>
    </tr>';
  }}
  $contenu .=' </tbody>
</table>

<h3>Informations sur les sessions</h3>
<p>Les sessions ont une date de debut et une date de fin : </p>
<p>Un candidat qui s\'inscrit sur le site est attribué à une session par rapport à sa date d\'inscription</p>
<p>Exemple : La session 2017/2018 ; Date début : 01/01/2017 Date fin 01/12/2017</p>
<p>Le candidat s\'inscrit le 01/10/2017 : il sera dans la session 2017/2018</p>
<p>Le candidat s\'inscrit le 01/02/2018 : il sera dans la session d\'après, donc 2018/2019</p>
<p>A vous d\'adapter les dates de sessions : pensez toujours à en avoir une supplémentaire sinon il y aura des erreurs lors des inscriptions.</p>

</div>
</div>
<br><br>

';
  }

  if($mode == 'ajout_session') { 
    $params = array('module' => $module,
                    'mode' => 'validation_session');
    $url_validation_session = $classBase->creation_adm_url($params);

 $contenu .='
<div class="row">
   <div class="col-md-3"></div>
    <div class="col-md-6"><br>
    <form action="index.php" method="post" enctype="multipart/form-data">
    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="validation_session">

                    <div class="section">
                      <label for="session" class="field prepend-icon">
                      <p>Nom de la session : </p> 
                        <input type="text" class="form-control" id="formvars[libelle]" name="formvars[libelle]" placeholder="Nouvelle session" required="required">
                       <p>Date debut de la session : FORMAT : 2000-12-01 [annee-mois-jour]</p> 
                        <input type="text" class="form-control" id="formvars[debut_session]" name="formvars[debut_session]" placeholder="" required="required">
                        <p>Date fin de la session : FORMAT : 2000-12-01 [annee-mois-jour]</p> 
                        <input type="text" class="form-control" id="formvars[fin_session]" name="formvars[fin_session]" placeholder="" required="required">
                        <button class="btn btn-primary" type="submit" name="submit_new" >Valider</button>
                      </label>
    </form>
                    </div> 
    </div>
</div>
    ';
  }

if($mode == 'validation_session') {  
$formvars = $classBase->auto_variable('formvars');
$tbl_session = $classBase->nom_table('session');
$params = array('cle_primaire'=>'id_session');
 // -- Requête d'insertion
    $requete = $classBase->creation_requete_insertion($tbl_session,
        $formvars);
    $ok = $classBase->requete_sql($db, $requete);
    /* Renvoi sur la page des sessions de l'ancienne appli */
    header("Refresh:0; url=supr");

}
 
 if ($mode == 'suppression_session') {
      $edit_id = $classBase->auto_variable('edit_id');
      $tbl_session = $classBase->nom_table('session');
      $params = array('cle_primaire'=>'id_session');
      if ($edit_id  > 0) {
        $formvars['deleted'] = 1;
        $requete = $classBase->creation_requete_modification($tbl_session,
        $formvars, $edit_id, $params);
        
        $ok = $classBase->requete_sql($db, $requete);
        header("Refresh:0; url=supr");
      }
 }

if($mode == 'modification_session') { 

  $edit_id = $classBase->auto_variable('edit_id');
  $params = array('id_session' => $edit_id);
  $edit_session = $classBase->infos_session($params);

    $params = array('module' => $module,
                    'mode' => 'confirmation_modification_session',
                   'edit_id'=> $edit_id);
    $url_confirmation_modification_session = $classBase->creation_adm_url($params);

 $contenu .='
<div class="row">
   <div class="col-md-3"></div>
    <div class="col-md-8"><br>
    <form action="index.php" method="post" enctype="multipart/form-data">
    <input name="edit_id" type=hidden value="'.$edit_id.'">

    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="confirmation_modification_session">

                    <div class="section">
                      <label for="session" class="field prepend-icon">
                      <p>Nom de la session : </p> 
                        <input type="text" class="form-control" id="formvars[libelle]" name="formvars[libelle]" placeholder="" required="required" value="'.$edit_session[0]['libelle'].'">
                        <p>Date debut de la session : </p> 
                        <input type="text" class="form-control" id="formvars[debut_session]" name="formvars[debut_session]" placeholder="" required="required" value="'.$edit_session[0]['debut_session'].'">
                        <p>Date fin de la session : </p> 
                        <input type="text" class="form-control" id="formvars[fin_session]" name="formvars[fin_session]" placeholder="" required="required" value="'.$edit_session[0]['fin_session'].'">
                        <button class="btn btn-primary" type="submit">Valider</button>
                      </label>
                    </div> 
    </form>             
    </div>
</div>
    ';
  }

  if ($mode == 'confirmation_modification_session') {

      $edit_id = $classBase->auto_variable('edit_id');
      $formvars = $classBase->auto_variable('formvars');
      $tbl_session = $classBase->nom_table('session');
      $params = array('cle_primaire'=>'id_session');
     
        $requete = $classBase->creation_requete_modification($tbl_session,
        $formvars, $edit_id, $params);
        print_r($requete);
        $ok = $classBase->requete_sql($db, $requete);
        header("Refresh:0; url=supr");   
  }

 //  --- Edition des phase
  if($mode == 'phases') { 
   
    $params = array('module' => $module,
                    'mode' => 'ajout_phase');
    $url_ajout_phase = $classBase->creation_adm_url($params);

  $contenu .='
  <div class="row">
   <div class="col-md-5"></div>
    <div class="col-md-6"><br>
  <a href="'.$url_ajout_phase.'" class="btn btn-info">
  <input name="module" type=hidden value="'.$module.'">
<input name="mode" type=hidden value="ajout_phase">
  Ajouter une phase</a>
  </div>
    </div><br>
   <div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6">
 <table class="table table-hover">
  <thead>
    <tr>
      <th>id</th>
      <th>Nom de la phase</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>';
   $tb_phase = $classBase->infos_phase();
   foreach ($tb_phase as $key => $value) {
   
  //  -- Création de l'url pour la suppression.
  $params = array('module'=>$module,
          'mode'=>'suppression_phase',
          'edit_id'=>$value['id_phase']);
  $url_suppression_phase = $classBase->creation_adm_url($params); 
  //  -- Création de l'url pour la modification.
  $params = array('module'=>$module,
          'mode'=>'modification_phase',
          'edit_id'=>$value['id_phase']);
  $url_modification_phase = $classBase->creation_adm_url($params);
    
    if($value['deleted'] < 1 ){ /* On affiche seulement les phases actifs */
  $contenu .='    <tr style="background-color : white;">
      <td>'.$value['id_phase'].'</td>
      <td>'.$value['libelle_phase'].'</td>
      <td><a href="'.$url_modification_phase.'" class="btn btn-success btn-sm light fw600 ml10">
            Modifier</a>         
      </td>
    <!--   <td><a href="'.$url_suppression_phase.'" class="btn btn-danger btn-sm light fw600 ml10">
            <span class="fa fa-times pr5"></span> Supprimer</a>       
      </td> -->
    </tr>';
  }}
  $contenu .=' </tbody>
</table>
<h3>Informations sur les phases</h3>
<p>Il est important de prevenir le support informatique pour l\'ajout d\'une phase car cela change le comportement de l\'application (Statistiques notamment). <br>
La fonction suppression est desactivée pour la meme raison</p>

</div>
</div>
<br><br>

';
  }

  if($mode == 'ajout_phase') { 
    $params = array('module' => $module,
                    'mode' => 'validation_phase');
    $url_validation_phase = $classBase->creation_adm_url($params);

 $contenu .='
<div class="row">
   <div class="col-md-5"></div>
    <div class="col-md-6"><br>
    <form action="index.php" method="post" enctype="multipart/form-data">
    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="validation_phase">

                    <div class="section">
                      <label for="phase" class="field prepend-icon">
                      <p>Nom complet de la phase : </p> 
                        <input type="text" class="form-control" id="formvars[libelle_phase]" name="formvars[libelle_phase]" placeholder="" required="required">
                      <p>Nom court de la phase : </p> 
                        <input type="text" class="form-control" id="formvars[libelle_court]" name="formvars[libelle_phase]" placeholder="" required="required">
                      <p>Couleur de la phase : </p> 
                        <label class="field select">
                        <select id="formvars[couleur]" name="formvars[couleur]" required="required">
                          <option value="label label-rounded label-info" class="label label-rounded label-info"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                          <option value="label label-rounded label-success" class="label label-rounded label-success"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                          <option value="label label-rounded label-primary" class="label label-rounded label-primary"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                          <option value="label label-rounded label-danger" class="label label-rounded label-danger"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                          <option value="label label-rounded label-warning" class="label label-rounded label-warning">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </option>
                          <option value="label label-rounded label-system" class="label label-rounded label-system">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </option>
                          <option value="label label-rounded label-alert" class="label label-rounded label-alert">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </option>
                          <option value="label label-rounded label-default" class="label label-rounded label-default">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </option>
                        </select>
                        <i class="arrow double"></i>
                      </label>


                        <button class="btn btn-primary" type="submit" name="submit_new" >Valider</button>
                      </label>
</form>
                    </div> 
    </div>
</div>
    ';
  }

if($mode == 'validation_phase') {  
$formvars = $classBase->auto_variable('formvars');
$tbl_phase = $classBase->nom_table('phase');
$params = array('cle_primaire'=>'id_phase');
 // -- Requête d'insertion
    $requete = $classBase->creation_requete_insertion($tbl_phase,
        $formvars);
    $ok = $classBase->requete_sql($db, $requete);
    /* Renvoi sur la page des phases de l'ancienne appli */
    header("Refresh:0; url=supr");
}
 
 if ($mode == 'suppression_phase') {
      $edit_id = $classBase->auto_variable('edit_id');
      $tbl_phase = $classBase->nom_table('phase');
      $params = array('cle_primaire'=>'id_phase');
      if ($edit_id  > 0) {
        $formvars['deleted'] = 1;
        $requete = $classBase->creation_requete_modification($tbl_phase,
        $formvars, $edit_id, $params);
        
        $ok = $classBase->requete_sql($db, $requete);
        header("Refresh:0; url=supr");
      }
 }

if($mode == 'modification_phase') { 

  $edit_id = $classBase->auto_variable('edit_id');
  $edit_phase = $classBase->infos_phase($edit_id);
    $params = array('module' => $module,
                    'mode' => 'confirmation_modification_phase',
                   'edit_id'=> $edit_id );
    $url_confirmation_modification_phase = $classBase->creation_adm_url($params);
 $contenu .='
<div class="row">
   <div class="col-md-3"></div>
    <div class="col-md-8"><br>
    <form action="index.php" method="post" enctype="multipart/form-data">
    <input name="edit_id" type=hidden value="'.$edit_id.'">

    <input name="module" type=hidden value="'.$module.'">
    <input name="mode" type=hidden value="confirmation_modification_phase">

                    <div class="section">
                      <label for="phase" class="field prepend-icon">
                      <p>Nom de la phase : </p> 
                        <input type="text" class="form-control" id="formvars[libelle_phase]" name="formvars[libelle_phase]" placeholder="" required="required" value="'.$edit_phase[0]['libelle_phase'].'">
                        <button class="btn btn-primary" type="submit">Valider</button>
                      </label>
                    </div> 
    </form></div>
</div>
    ';
  }

  if ($mode == 'confirmation_modification_phase') {
      $edit_id = $classBase->auto_variable('edit_id');
      $formvars = $classBase->auto_variable('formvars');
      $tbl_phase = $classBase->nom_table('phase');
      $params = array('cle_primaire'=>'id_phase');

      if ($edit_id  > 0) {
        $requete = $classBase->creation_requete_modification($tbl_phase,
        $formvars, $edit_id, $params);
        $ok = $classBase->requete_sql($db, $requete);
        header("Refresh:0; url=supr");
       
      }
 }

	# 	- BEGIN: ADMINISTRATION DES FORMATIONS DANS LES CENTRES.
	if($mode == 'adm_formation_centre'){
		
		//	-- Récupération du tableau des formations.
		//	-- Récupération du tableau des centres.
		
		//	-- Formulaire d'édition.
		
	}
	# 	- END: ADMINISTRATION DES FORMATIONS DANS LES CENTRES.


         
// --- ajout du contenu dans le bloc principal
$tb_blocs['bloc_adm_principal'] .= $contenu;

// --- meta tags par défaut
$tb_blocs['meta_title'] = 
  $classBase->get_parametre('meta_title');
$tb_blocs['meta_description'] =
  $classBase->get_parametre('meta_description');
$tb_blocs['meta_keywords'] =
  $classBase->get_parametre('meta_keywords');

?>


