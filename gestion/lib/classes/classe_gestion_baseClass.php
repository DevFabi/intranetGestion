<?php
/*	-------------------------------------------------------------------------------------------
	-------------------------------------------------------------------------------------------
	---									CLASSE GESTION baseClass									---
	---											BASE										---
	-------------------------------------------------------------------------------------------	
	-------------------------------------------------------------------------------------------	*/

// --- inclusion de la classe baseClass
$app_dir = realpath(dirname(__FILE__)) . '/../../../' ;
require_once($app_dir . '/lib/classes/classe_baseClass.php');


class GestionbaseClass extends baseClass {

	var $tb_langues = array('fr');
     // --- préfixe pour les tables
    var $tablestart = 'dc_';

  // --- fonction retournant le nom d'une table
  // --- avec son préfixe
  function nom_table($nom) {
    $nom_complet = $this->tablestart . $nom;
    return($nom_complet);
  }
	
  // --- fonction permettant de récupérer
  // --- les infrms de menu
  function tableau_adm_menu($params='') {
    // --- iisation du tableau dans lequel
    // --- on va récupérer les lignes de menu
    $tb = array();
    // --- nom de la table
    $tbl_adm_menus = $this->nom_table('adm_menus');
    // --- valeurs par défaut
    if (!is_array($params)) $params = array();
    // --- valeurs par défaut : langue
    if (!isset($params['langue'])) {
    	$params['langue'] = $this->langue;
    }
    // --- valeurs par défaut : ordre de tri
    if (!isset($params['order'])) {
    	$params['order'] = 'code';
    }
    // --- valeurs par défaut : champs à sélectionner
    if (!isset($params['champs'])) {
    	$params['champs'] = '*';
    }
    // --- valeurs par défaut : tables utilisées
    if (!isset($params['tables'])) {
    	$params['tables'] = $tbl_adm_menus;
    }
    // --- conditions
    $conditions = array();
    // --- conditions : langue
    if (strlen($params['langue']) == 2) {
    	$c = $params['langue'];
    	$conditions[] = "and langue like '$c'";
    }
    // --- conditions : code
    if (strlen($params['code']) > 0) {
    	$c = $params['code'];
    	$conditions[] = "and code like '$c'";
    }
    // --- requête SQL : champs et tables utilisés
    $cde = 'select ' . $params['champs'];
    $cde .= ' from ' . $params['tables'];
    $cde .= ' where 1';
    // --- requête SQL : ajout des conditions
    for ($i=0; $i<count($conditions); $i++) {
    	$cde .= ' ' . $conditions[$i];
    }
    // --- requête SQL : ordre de tri
    if (strlen($params['order']) > 0) {
    	$cde .= ' order by ' . $params['order'];
    }
    // --- requête SQL : recherche des enregistrements
    // --- et stockage dans un tableau
    $result = $this->requete_sql($this->db, $cde);
	while($row = $result->fetch(PDO::FETCH_ASSOC)){
      $key = $row['code'];
      $tb["$key"] = $row;
    }
    return($tb);
  }
  
  // --- fonction permettant de connaitre le niveau
  // --- d'une catégorie dans le menu
  function niveau_menu_adm($code) {
    $longueur = strlen($code);
    $niveau = intval($longueur / 2);
    return($niveau);
  }

  // --- récupère le libellé d'une ligne de menu
  function adm_menu_libel($code) {
    // --- nom de la table
    $tbl = $this->nom_table('adm_menus');
    $cde = "select libel from $tbl";
    $cde .= " where code like '$code'";
    $cde .= " and langue like '$this->langue'";
    $result = $this->requete_sql($this->db, $cde);
    #while($row = mysql_fetch_object($result)) {
	while($row = $result->fetch(PDO::FETCH_OBJ)){
    	return($row->libel);
    }
    return('');
  }

  // --- fonction de création d'une url dans l'administration
  function creation_adm_url($params) {
    // --- valeurs par défaut
    if (!is_array($params)) $params = array();
    // --- composition de l'url
    $url = $GLOBALS['cf_url_adm_base'] . 'index.php';
    $i = 0;
    while (list($cle, $valeur) = each($params)) {
      if ($i == 0) $url .= '?';
      else $url .= '&';
      $i++;
      $url .= "$cle=$valeur";
    }
    return($url);
  }

 // --- tableau des champs disponibles dans une table PDO
  function tb_champs($base, $table) {
     $result = $this->requete_sql($this->db, "DESCRIBE $table");
    //Fetch our result.
    $table_fields = $result->fetchAll(PDO::FETCH_COLUMN);
        while (list($cle, $valeur) = each($table_fields)) {
            $tb["$valeur"] = 1;
        }
            if ($this->debug_mode)  print_r($tb);
    return($tb);
  }
  
	// --- fonction permettant de récupérer
  // --- un tableau de paramètres
  function tableau_parametres($params='') {
    // --- iisation du tableau dans lequel
    // --- on va récupérer les infos
    $tb = array();
    // --- nom de la table
    $tbl_parametres = $this->nom_table('parametres');
    // --- valeurs par défaut
    if (!is_array($params)) $params = array();
    // --- valeurs par défaut : langue
    if (!isset($params['langue'])) {
    	$params['langue'] = $this->langue;
    }
    // --- valeurs par défaut : ordre de tri
    if (!isset($params['order'])) {
    	$params['order'] = 'code';
    }
    // --- valeurs par défaut : champs à sélectionner
    if (!isset($params['champs'])) {
    	$params['champs'] = '*';
    }
    // --- valeurs par défaut : tables utilisées
    if (!isset($params['tables'])) {
    	$params['tables'] = $tbl_parametres;
    }
    // --- conditions
    $conditions = array();
    // --- conditions : langue
    if (strlen($params['langue']) == 2) {
    	$c = $params['langue'];
    	$conditions[] = "and langue like '$c'";
    }
    // --- conditions : code
    if (strlen($params['code']) > 0) {
    	$c = $params['code'];
    	$conditions[] = "and code like '$c'";
    }
    // --- requête SQL : champs et tables utilisés
    $cde = 'select ' . $params['champs'];
    $cde .= ' from ' . $params['tables'];
    $cde .= ' where 1';
    // --- requête SQL : ajout des conditions
    for ($i=0; $i<count($conditions); $i++) {
    	$cde .= ' ' . $conditions[$i];
    }
    // --- requête SQL : ordre de tri
    if (strlen($params['order']) > 0) {
    	$cde .= ' order by ' . $params['order'];
    }
    // --- requête SQL : recherche des enregistrements
    // --- et stockage dans un tableau
    $result = $this->requete_sql($this->db, $cde);
	while($row = $result->fetch(PDO::FETCH_ASSOC)){
      $key = $row['id'];
      $tb["$key"] = $row;
    }
    return($tb);
  }
  
  // --- création d'une requête de modification dans une table
  function creation_requete_modification($tbl,
    $tbinfos, $id, $params='') {
  	$id = intval($id);
    if (!$tbl
      || !is_array($tbinfos)
      || count($tbinfos)==0
      || !$id) {
    	return('');
    }
    if (!is_array($params)) $params = array();
    // --- tableau des champs existants dans la table
    $tb_champs = $this->tb_champs($this->db, $tbl);
    // --- début de la requête
    $txt_requete = "update $tbl";    
    // --- création des tableaux de champs et de valeurs
    reset($tbinfos);
    $i = 0;
    while (list($cle, $valeur) = each($tbinfos)) {
      // --- ajout dans la requête si le champ existe
      if ($tb_champs["$cle"] == 1) {
        if ($i == 0) {
          $txt_requete .= ' set ';
        }
        else {
          $txt_requete .= ',';
        }
        $valeur = $this->quote_smart($valeur);
        $txt_requete .= "$cle=$valeur";
        $i++;
      }
    }
    // --- clé primaire
    if ((isset($params['cle_primaire'])) && (strlen($params['cle_primaire'])) > 0) {
    	$cle_primaire = $params['cle_primaire'];
    }
    else {
    	$cle_primaire = 'id';
    }
    // --- conditions
    $txt_requete .= " where $cle_primaire=$id";
    return($txt_requete);
  }

  // --- création d'une requête de suppression dans une table
  function creation_requete_suppression($tbl, $id, $params='') {
  	$id = intval($id);
    if (!$tbl || !$id) {
    	return('');
    }
    if (!is_array($params)) $params = array();
    // --- clé primaire
    if (strlen($params['cle_primaire']) > 0) {
    	$cle_primaire = $params['cle_primaire'];
    }
    else {
    	$cle_primaire = 'id';
    }
    // --- requête
    $txt_requete = "delete from $tbl where $cle_primaire=$id";
    return($txt_requete);
  }
  function requete_sql($db, $cde) {
    if (!$this->db_selected) {
    try { 
      $this->db_selected  = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db.';charset=utf8mb4', $this->db_user, $this->db_pass);
      $this->db_selected ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->db_selected ->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $this->db_selected ->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND,"SET NAMES utf8mb4");
    }
    catch(PDOException $e) {
      $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
      die($msg);
    }
  }
  $result = $this->db_selected->query($cde);
    if (!$result && $this->debug_mode) {
      echo "<b>requete_sql</b>(db:$db)(cde:$cde)";
      echo '(erreur:' . mysql_error() .")<br>\n";
    }
    return($result);
  }

 public function lastInsertId(){
        return $this->db_selected->lastInsertId();
    }
  

	// --- récupération des stats de trafic
  function tableau_stats_trafic($params='') {
    // --- tableau dans lequel on va récupérer les infos
    $tb = array();
    // --- nom de la table
    $tbl_stats = $this->nom_table('stats_trafic');
    // --- valeurs par défaut
    if (!is_array($params)) $params = array();
    // --- valeurs par défaut : champs à sélectionner
    if (!isset($params['champs'])) {
    	$params['champs'] = '*';
    }
    // --- valeurs par défaut : tables utilisées
    if (!isset($params['tables'])) {
    	$params['tables'] = $tbl_stats;
    }
    // --- conditions
    $conditions = array();
    // --- conditions : langue
    if (strlen($params['langue']) == 2) {
    	$c = $params['langue'];
    	$conditions[] = "and langue like '$c'";
    }
    // --- date minimale
    if (strlen($params['date_mini']) > 0) {
    	$conditions[] = "and TO_DAYS(dt)>=TO_DAYS('" . $params["date_mini"] . "')";
    }
    // --- date maximale
    if (strlen($params['date_maxi']) > 0) {
    	$conditions[] = "and TO_DAYS(dt)<=TO_DAYS('" . $params["date_maxi"] . "')";
    }
    // --- requête SQL : champs et tables utilisés
    $cde = 'select ' . $params['champs'];
    $cde .= ' from ' . $params['tables'];
    $cde .= ' where 1';
    // --- requête SQL : ajout des conditions
    for ($i=0; $i<count($conditions); $i++) {
    	$cde .= ' ' . $conditions[$i];
    }
    // --- requête SQL : optioon group by
    if (strlen($params['group']) > 0) {
    	$cde .= ' group by ' . $params['group'];
    }
    // --- requête SQL : ordre de tri
    if (strlen($params['order']) > 0) {
    	$cde .= ' order by ' . $params['order'];
    }
    // --- requête SQL : recherche des enregistrements
    $result = $this->requete_sql($this->db, $cde);
	while($row = $result->fetch(PDO::FETCH_ASSOC)){
      $key = $row['id'];
      $tb["$key"] = $row;
    }
    return($tb);
  }


/*	-------------------------------------------------------------

						TEMPLATES FONCTIONS

	-------------------------------------------------------------	*/
	
	// --- Création du menu administration - Génère le code du menu
	function menu_adm_creation($params='') {
		
		//	-- Récupération du tableau du menu admininstrateur
		$tb_adm_menu = $this -> tableau_adm_menu ( $params );
		
		//	-- Parcours des catégories de tête pour récupération des sous-menus
		
	}
 
/*  --- Création url suppression.
    */
      function creation_url_suppression($params) {
        // --- valeurs par défaut
        if (!is_array($params)) $params = array();
        // --- composition de l'url
        $url = $GLOBALS['cf_url_base'] . '/index.php';
        $i = 0;
        while (list($cle, $valeur) = each($params)) {
          if ($i == 0) $url .= '?';
          else $url .= '&';
          $i++;
          $url .= "$cle=$valeur";
        }
        return($url);
      } 
    
// --- creation d'une requete d'insertion dans une table
  function creation_requete_insertion(
    $tbl, $tbinfos, $params='') {
    if (!$tbl || !is_array($tbinfos) || count($tbinfos)==0) {
      return('');
    }
    if (!is_array($params)) $params = array();
    // --- tableau des champs existants dans la table
    $tb_champs = $this->tb_champs($this->db, $tbl);
    // --- creation des tableaux des champs et des valeurs
    reset($tbinfos);
    $txt_champs = '';
    $txt_valeurs = '';
    $i = 0;
    while (list($cle, $valeur) = each($tbinfos)) {
      // --- ajout dans la requete si le champ existe
      if ($tb_champs["$cle"] == 1) {
        if ($i >0) {
          $txt_champs .= ',';
          $txt_valeurs .= ',';
        }
        $txt_champs .= $cle;
        $c = $this->quote_smart($valeur);
        if (!is_numeric($valeur)) {
          $txt_valeurs .= "$c";
        }
        else {
          $txt_valeurs .= $c;
        }
        $i++;
      }
    }
    // --- creation de la requete
    if (isset($params['replace']) && $params['replace'] == 1) {
      $requete = "replace into $tbl ($txt_champs)";
      $requete .= " values ($txt_valeurs)";
    }
    else {
      $requete = "insert into $tbl ($txt_champs)";
      $requete .= " values ($txt_valeurs)";
    }
    return($requete);
  }

  // ------------------------------------------- APP - dd -------------------------------------------
  // ------------------------------------------- APP - dd -------------------------------------------
  // ------------------------------------------- APP - dd -------------------------------------------

  // ------- RECUPERATIONS D'INFOS SUR LES DIFFERENTES TABLES

  // --- fonction permettant de récupérer
  // --- un tableau d'utilisateurs
  function tableau_candidats($params='') {

    $tb = array();
    // --- nom des tables
    $tbl_dd = $this->nom_table('demande_dd');

    // --- valeurs par défaut
    if (!is_array($params)) $params = array();
    // --- valeurs par défaut : tous les candidats.
    if (!isset($params['actif'])) {
    }
    // --- valeurs par défaut : candidats pas supprimés.
    if (!isset($params['deleted'])) {
        $params['deleted'] = 0;
    }   
    // --- valeurs par défaut : ordre de tri
    if (!isset($params['order'])) {
        $params['order'] = "$tbl_dd.id_demande asc";
    }
    // --- valeurs par défaut : champs à sélectionner
    if (!isset($params['champs'])) {
        $params['champs'] = '*';
    }
    // --- valeurs par défaut : tables utilisées
    if (!isset($params['tables'])) {
        $params['tables'] = "$tbl_dd";
    }

    // --- conditions
    $conditions = array();
    // --- requête de comptage si demande
    if ($params['comptage'] == 1) {
      $cde = 'select count(*) as nb from ';
      $cde .= $params['tables'];
      $cde .= ' where 1';
    } 
    // --- conditions : code
    if (intval($params['id_demande']) > 0) {
        $c = $params['id_demande'];
        $conditions[] = "and id_demande = $c";
    }
    if (strlen($params['deleted']) > 0) {
        $c = $params['deleted'];
        $conditions[] = "and deleted like '$c'";
    }       
    
    // --- creation de la commande
    $cde = $this->sql_auto_cde($params, $conditions);

    // --- requête SQL : recherche des enregistrements
    // --- et stockage dans un tableau
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode || $params['debug']==1) {
        echo "<b>tableau_candidats</b> $cde (";
        echo $this->rowCount($result) . ")<br>";
    }
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
      $key = $row['id_demande'];
      $tb["$key"] = $row;
    }
    return($tb);
  }


  // --- Récupère les infrms sur un candidat.
  // Peut etre remplacer par la fonction du dessus
  function infos_candidat($params = '') {
    
    if (!is_array($params)) $params = array();
    $tb = array();
    // --- tables utilisees
    $tbl_dd = $this->nom_table('demande_dd');
    // --- requête SQL
    $cde = "select * from $tbl_dd";
    $cde .= " where 1";
    // --- conditions
    $conditions = array();
     // --- conditions : demande.
    if (intval($params['id_demande']) > 0) {
        $c = $params['id_demande'];
        $conditions[] = "and id_demande = $c";
    }
    // --- requête SQL : ajout des conditions
        for ($i=0; $i<count($conditions); $i++) {
            $cde .= ' ' . $conditions[$i];
        }
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>infos_candidat</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return($tb);
  } 


  //  --- frmS
  function tableau_frm( $params=''){
  if(!isset($params)) $params = array();
    $tb = array();
    if(isset($params['id_centre'])) $id_centre = $params['id_centre'];
  //  -- Tables utilisées.
  $tbl = $this->nom_table('frm');
    // --- requête SQL
    $cde = "select * from $tbl";
    $cde .= " where 1";
  //  -- Condition: id centre.
  if(isset($id_centre))
    $cde .=" and $tbl.id_centre=$id_centre";
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>frms</b> $cde (erreur:";
     print_r($result->errorInfo());
    }
  $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return($tb);
  } 
  

    //  --- CENTRE
    // -- Mettre params
    function tableau_centre($id){
    $tb = array();
     if (!$id) {
        $cde = "select * from dc_centre ";}
         else{
    // --- requête SQL
    $cde = "select * from dc_centre where id=$id"; }
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>centre</b> $cde (erreur:";
     print_r($result->errorInfo());
    }
  $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return($tb);
  } 

    //  --- PAYS
    // -- Mettre params
    function tableau_type_demande(){
    $tb = array();
    // --- requête SQL
    $cde = "select * from dc_type_demande";
    $cde .= " where 1";
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>type_demande</b> $cde (erreur:";
     print_r($result->errorInfo());
    }
  $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return($tb);
  } 
 
  // -- Mettre params
  // -- Informe sur le type de la demande 
  function infos_type_demande($id){
  $tb = array();
    $id = intval($id);
    if (!$id) return($tb);
    // ---- table utilisée
    $tbl = $this->nom_table('type_demande');
    // --- requête SQL
    $cde = "select * from $tbl where id_type_demande=$id";
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>infos_type_demande</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }


function infos_frm($params= ''){
    if(!isset($params)) $params = array();
    $tb = array();
    // ---- table utilisée
    $tbl = $this->nom_table('frm');
    $cde = "select * from $tbl ";
    $cde .= "where 1 ";
    $conditions = array();
    if (intval($params['id_frm']) > 0) {
        $c = $params['id_frm'];
        $conditions[] = "and id_frm = $c";
    }
  // --- requête SQL : ajout des conditions
        for ($i=0; $i<count($conditions); $i++) {
            $cde .= ' ' . $conditions[$i];
        }      
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>infos_frm</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }

     // -- Ajouter une condition dans infos_frm
  function infos_frm_centre($centre){
    
    $tb = array();
    $tbl = $this->nom_table('frm');
    $cde = "select * from $tbl where id_centre=$centre";
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>infos_frm</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }
  
  function infos_frm_centre1($params=''){
    if(isset($params['id_centre'])) $id_centre = $params['id_centre'];
    $tb = array();
    // ---- table utilisée.
    $tbl_frm = $this->nom_table('frm');
    $tbl_phase_par_el = $this->nom_table('phase_par_el');
      // --- conditions
    $conditions = array();
    // --- conditions : langue
    if (strlen($params['phase']) > 0) {
      $c = $params['langue'];
    $conditions[] = "and langue like '$c'";
    }
    if (strlen($params['date_debut']) > 0) {
      $c = $params['date_debut'];
    $conditions[] = "and date_attribution >= '$c'";
    }
    if (strlen($params['date_fin']) > 0) {
      $c = $params['date_fin'];
    $conditions[] = "and date_attribution <= '$c'";
    }
    
    $cde = "select * from $tbl_frm, $tbl_phase_par_el where $tbl_frm.id_centre=$id_centre";
    $cde .=" and $tbl_frm.id_frm= $tbl_phase_par_el.id_frm";
    //$cde .=" group by $tbl_phase_par_el.id_frm";
     
      // --- requête SQL : ajout des conditions
    for ($i=0; $i<count($conditions); $i++) {
      $cde .= ' ' . $conditions[$i];
    }

    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>infos_frm</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    } 

// Retourne une liste de frms avec un id ou sans
// A enlever de mode = phase_cn et remplacer par infos_frm_centre1 ou infos_frm
  function infos_frm_stat_($id){
    $tb = array();
    $id = intval($id);
    $tbl = $this->nom_table('frm');
    if (!$id) {
        $cde = "select * from $tbl where id_centre=1";} else{
    $cde = "select * from $tbl where id_frm=$id and id_centre=1"; }
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>infos_frm</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }

  // A enlever de mode = phase_cn et remplacer par infos_frm_centre1 ou infos_frm
  function infos_frm_stat_($id){
  $tb = array();
    $id = intval($id);
    $tbl = $this->nom_table('frm');
    if (!$id) {
        $cde = "select * from $tbl where id_centre=2 ";} else{
    $cde = "select * from $tbl where id_frm=$id and id_centre=2 "; }
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>infos_frm</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }
    
function infos_pays($params= ''){
    if(!isset($params)) $params = array();
    $tb = array(); 
    // ---- table utilisée
    $tbl = $this->nom_table('pays');
    $cde = "select * from $tbl ";
    $cde .= "where 1 ";
    $conditions = array();
    // --- conditions : cn.
    if (intval($params['id_pays']) > 0) {
        $c = $params['id_pays'];
        $conditions[] = "and id_pays = $c";
    }
    for ($i=0; $i<count($conditions); $i++) {
        $cde .= ' ' . $conditions[$i];
    }   
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>infos_pays</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }

function infos_provenance($params= ''){
    $tb = array();
    if(!isset($params)) $params = array();
    // ---- table utilisée
    $tbl = $this->nom_table('provenance');
    $cde = "select * from $tbl "; 
    $cde .= "where 1 ";
    $conditions = array();
    if (intval($params['id_provenance']) > 0) {
        $c = $params['id_provenance'];
        $conditions[] = "and id_provenance = $c";
    }
    for ($i=0; $i<count($conditions); $i++) {
            $cde .= ' ' . $conditions[$i];
    } 
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>infos_provenance</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }

function infos_rythme($params= ''){
  $tb = array();
  if(!isset($params)) $params = array();
     $tbl = $this->nom_table('rythme');
     $cde = "select * from $tbl "; 
     $cde .= "where 1 ";
    $conditions = array();
    if (intval($params['id_rythme']) > 0) {
        $c = $params['id_rythme'];
        $conditions[] = "and id_rythme = $c";
    }
    for ($i=0; $i<count($conditions); $i++) {
            $cde .= ' ' . $conditions[$i];
    } 
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>infos_rythme</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }
    
function infos_niveau($params= ''){ 
    $tb = array();
    $id = intval($id);
  if(!isset($params)) $params = array();  
    // ---- table utilisée
    $tbl = $this->nom_table('niveau');
    $cde = "select * from $tbl "; 
    $cde .= "where 1 ";
    $conditions = array();
    if (intval($params['id_niveau']) > 0) {
        $c = $params['id_niveau'];
        $conditions[] = "and id_niveau = $c";
    }
    for ($i=0; $i<count($conditions); $i++) {
            $cde .= ' ' . $conditions[$i];
    } 
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>infos_niveau</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }

// Recupere la phase ACTUELLE d'un candidat
function infos_current_phase($params= ''){ 
  $tb = array();
  $id = intval($id);
  if(!isset($params)) $params = array();  
    // ---- table utilisée
    $tbl = $this->nom_table('current_phase');
    $cde = "select * from $tbl "; 
    $cde .= "where 1 ";
    $conditions = array();
    if (intval($params['id_demande']) > 0) {
        $c = $params['id_demande'];
        $conditions[] = "and id_demande = $c";
    }
    for ($i=0; $i<count($conditions); $i++) {
            $cde .= ' ' . $conditions[$i];
    } 
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>infos_current_phase</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }

// -- Mettre params et conditions
 function infos_phase($id){
  $tb = array();
    // ---- table utilisée
    $tbl = $this->nom_table('phase');
    if (!$id) {
        $cde = "select * from $tbl";} else{
    // --- requête SQL
    $cde = "select * from $tbl where id_phase=$id";}
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>infos_phase</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }

function infos_session($params= ''){
        if(!isset($params)) $params = array();
        $tb = array();
    // ---- table utilisée
    $tbl = $this->nom_table('session');
    // --- requête SQL
    $cde = "select * from $tbl "; 
    $cde .= "where 1 ";
    // --- conditions
    $conditions = array();
  // --- conditions : cn.
    if (intval($params['id_session']) > 0) {
        $c = $params['id_session'];
        $conditions[] = "and id_session = $c";
    }
        for ($i=0; $i<count($conditions); $i++) {
            $cde .= ' ' . $conditions[$i];
        }   
    // --- requête SQL
    $result = $this->requete_sql($this->db, $cde); 
    if ($this->debug_mode && !$result) {
      echo "<b>infos_session</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }
  

    /*  --- END: INFO SUR LES TABLES   -----------------------------------------------------------
    ------------------------------------------------------------------------------------------- */ 

/* Recupere la phase actuelle d'un candidat 
Utilise dans module dd L157 et L287
Peut etre remplace par check_phase ? */
// -- A Changer dans le module dd :: Il faudrait recuperer la derniere phase dans dc_current_phase
 function infos_phase_candidat($id){
  $tb = array();
    $id = intval($id);
    if (!$id) return($tb);
// ---- table utilisée
    $tbl = $this->nom_table('phase_par_el');
    // --- requête SQL
    $cde = "select * from $tbl where id_d_d=$id";  
    $cde .= " order by date_attribution DESC LIMIT 1";  
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>infos_phase</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }


// A mettre dans une fonction tableau_phase ou info_phase
// Utilise dans module_dd pour l'affichage dans le pil d'un candidat
    function historique_phase_candidat($id){
  $tb = array();
    $id = intval($id);
    if (!$id) return($tb);
    // ---- table utilisée
    $tbl = $this->nom_table('phase_par_el');
    // --- requête SQL
    $cde = "select * from $tbl where id_d_d=$id";
    $cde .= " order by date_attribution desc";  
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>infos_phase</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }

// A mettre dans une fonction tableau_phase ou info_phase
/* Recupere le LIBELLE d'une phase avec son id */
 function infos_phase_libelle($id){
  $tb = array();
    $id = intval($id);
    if (!$id) return($tb);
    // ---- table utilisée
    $tbl = $this->nom_table('phase');
    // --- requête SQL
    $cde = "select * from $tbl where id_phase=$id"; 
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>infos_niveau</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }

 function infos_cns($params= ''){
  //  -- iisation des tableaux.
    if(!isset($params)) $params = array();
    $tb = array();
  // ---- table utilisée
    $tbl = $this->nom_table('cn');
  //  -- Requete sql.
    $cde = "select * from $tbl ";
    $cde .= "where 1 ";
  // --- conditions
    $conditions = array();
  // --- conditions : cn.
    if (intval($params['id_cn']) > 0) {
        $c = $params['id_cn'];
        $conditions[] = "and id_cn = $c";
    }
  // --- requête SQL : ajout des conditions
        for ($i=0; $i<count($conditions); $i++) {
            $cde .= ' ' . $conditions[$i];
        }   
    // --- requête SQL
    $result = $this->requete_sql($this->db, $cde); 
    if ($this->debug_mode && !$result) {
      echo "<b>infos_cn</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }

   // ---  /!\ Condition a mettre dans infos_cns
    function infos_cn_(){
  $tb = array();
    // ---- table utilisée
    $tbl = $this->nom_table('cn');
    $cde = "select * from $tbl where id_cn != 6";
    // --- requête SQL
    $result = $this->requete_sql($this->db, $cde); 
    if ($this->debug_mode && !$result) {
      echo "<b>infos_niveau</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }

// ---  /!\ Condition a mettre dans infos_cns
function infos_cn_candidat($id){
  $tb = array();
    $id = intval($id);
    if (!$id) return($tb);
    // ---- table utilisée
    $tbl = $this->nom_table('cn');
    // --- requête SQL
    $cde = "select * from $tbl where id_cn = $id "; 
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>infos_niveau</b> $cde (erreur:";
      echo mysql_error() . ")<br>";
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return( $tb );
    }

  // -- Infrms emails
  // -- Pour la gestion des emails. Module = email
    function tableau_email($id){
    $tb = array();
    if (!$id) {
         $cde = "select * from dc_email";
    }else{
    // --- requête SQL
    $cde = "select * from dc_email";
    $cde .= " where 1 and id_mail = $id";}
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>centre</b> $cde (erreur:";
     print_r($result->errorInfo());
    }
  $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return($tb);
  } 

                  /* ------------------------------------------------------------------------------------------ */
                  /* ---------------------------------- CALCULS STATISTIQUES ---------------------------------- */
                  /* ------------------------------------------------------------------------------------------ */

/* Statistiques des cns et attribution des els */
// Mode = 'frm_cn'
function cns_els($cn, $id_frm){
  $tb = array();
    $cde = "select count(*) els from dc_cn, dc_demande_dd";
    $cde .= " where 1";
    $cde .= " and dc_demande_dd.id_cn = dc_cn.id_cn";
    $cde .= " and dc_demande_dd.id_cn = $cn";
    $cde .= " and dc_demande_dd.id_frm = $id_frm";

 $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>recup_p</b> $cde (erreur:";
     print_r($result->errorInfo());
    }
  $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return($tb);
  }

  /* Count des demandes  TOTAL avec STATUT = 0 ou 1. EN COURS ou SUPPRIME */
  // Module = statistique
  function total_d_d($statut){
  $tb = array();
    $cde = "select count(*) total_doc from dc_demande_dd";
    $cde .= " where 1";
    $cde .= " and dc_demande_dd.deleted = $statut";

 $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>total_d_d</b> $cde (erreur:";
     print_r($result->errorInfo());
    }
  $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return($tb);
  }

 
	/*	-- Tableau statistiques des phases.
			Liste des phases.
	*/
    // -- Module = statistique   mode = 
	public function  _infrms_phases ( $params='' ){
		
		//	-- iisation des variables.
		if(!isset($params)) $params = array();
		$tb['frms'] = $this->tableau_frm( $params );
		
		//	-- Dates.
		if(isset($params['date_debut'])) $date_debut = $params['date_debut'];
			else $date_debut='';
		if(isset($params['date_fin'])) $date_fin = $params['date_fin'];
			else $date_fin = '';
		
		//	-- Création du tableau des statistiques.
		$html = '';
		
		//	-- En tête.
		$html .='
		<thead>
			<tr>	<th>Section</th>
					<th>D d d</th>
					<th>venu RI</th>
					<th>Taux RI</th>
					<th>ad i</th>
					<th>ad a</th>
					<th>Total ad </th>					
					<th>Taux adm</th>
					<th>Abandon</th>
			</tr>
		</thead>';
		//	-- Pour chaque frm.
		reset ( $tb['frms'] );
		while (list($c, $v) = each($tb['frms'])) {
			
		//	-- En tete tableau.
		$html .='<tr>';			
		
		#	- Titre.		
		$titre = $v['libelle_frm'];
		$html .='<td>'.$titre.'</td>';
		
		#	- Demande de doc.
		$params= array('id_phase'=>1,
					   'id_frm'=>$v['id_frm'],
					   'date_debut'=>$date_debut,
					   'date_fin'=>$date_fin);
		$tableau = $this->infos_stats_phase( $params );
		$d_d_d = count($tableau);
    $total_ddd[]= $d_d_d; // TB pour calcul du TOTAL
		$html .='<td>'.$d_d_d.'</td>';

		#	- Venu en RI.
		$params= array('id_phase'=>2,
					   'id_frm'=>$v['id_frm'],
					   'date_debut'=>$date_debut,
					   'date_fin'=>$date_fin);
		$tableau = $this->infos_stats_phase( $params );
		$venu_RI = count($tableau);
    $total_vri[]= $venu_RI;// TB pour calcul du TOTAL
		$html .='<td>'.$venu_RI.'</td>';		
	
		#	- Taux venu_RI / demandes.
		$taux_RI = round(($venu_RI / $d_d_d) * 100 ) . '%';
		$html .='<td>'.$taux_RI.'</td>';		
		
		#	- ad en i.
		$params= array('id_phase'=>3,
					   'id_frm'=>$v['id_frm'],
					   'date_debut'=>$date_debut,
					   'date_fin'=>$date_fin);
		$tableau = $this->infos_stats_phase( $params );
		$ad_i = count($tableau);
    $total_adI[]= $ad_i;// TB pour calcul du TOTAL
		$html .='<td>'.$ad_i.'</td>';	
		
		#	- ad en a.
		$params= array('id_phase'=>7,
					   'id_frm'=>$v['id_frm'],
					   'date_debut'=>$date_debut,
					   'date_fin'=>$date_fin);
		$tableau = $this->infos_stats_phase( $params );
		$ad_a = count($tableau);
    $total_aalt[]= $ad_a;// TB pour calcul du TOTAL
		$html .='<td>'.$ad_a.'</td>';	
	
		#	- ad en a.
		$total_adms = $ad_i + $ad_a;
    $total_ad[] = $total_adms;// TB pour calcul du TOTAL
		$html .='<td>'.$total_adms.'</td>';		
		
		#	- Taux adm.
		$taux_adm = round((($ad_i + $ad_a) / $venu_RI) * 100) . '%';
		$html .='<td>'.$taux_adm.'</td>';		
		
		#	- Abandon.
		$params= array('id_phase'=>5,
					   'id_frm'=>$v['id_frm'],
					   'date_debut'=>$date_debut,
					   'date_fin'=>$date_fin);
		$tableau = $this->infos_stats_phase( $params );
		$abandon = count($tableau);
    $total_ab[]= $abandon;// TB pour calcul du TOTAL
		$html .='<td>'.$abandon.'</td>';		
		
		
		//	-- Pied tableau.
		$html .=' </tr>';
		
		}

    // ---- CALCUL des TOTAUX par PHASES
// Les totaux sont calcules a l'aide de la fonction array_sum qui calcule le total des valeurs d'un TB

// AFFICHAGE DES TOTAUX A LA FIN DU TABLEAU
$html .='     <td style="background-color:#AAB7B8; font-weight: bold; color: white;">TOTAUX</td>
              <td style="background-color:#AAB7B8; font-weight: bold; color: white;">'.array_sum($total_ddd).'</td>
              <td style="background-color:#AAB7B8; font-weight: bold; color: white;">'.array_sum($total_vri).'</td>
              <td style="background-color:#AAB7B8; font-weight: bold; color: white;">/</td>
              <td style="background-color:#AAB7B8; font-weight: bold; color: white;">'.array_sum($total_adI).'</td>
              <td style="background-color:#AAB7B8; font-weight: bold; color: white;">'.array_sum($total_aalt).'</td>
              <td style="background-color:#AAB7B8; font-weight: bold; color: white;">'.array_sum($total_ad).'</td>
              <td style="background-color:#AAB7B8; font-weight: bold; color: white;">/</td>
              <td style="background-color:#AAB7B8; font-weight: bold; color: white;">'.array_sum($total_ab).'</td>';
	return $html;
	}
		
	
	
	//	-- Statistiques pour une phase.
  //  --  Utilise dans la fonction '_infrms_phases'
	public function infos_stats_phase( $params ){
		
		$tbl_phase_par_el = $this->nom_table('phase_par_el');
		
		//	-- Requete sql.
		$cde = "select * from $tbl_phase_par_el ";
		$cde .= "where 1 ";
		
		// --- conditions
		$conditions = array();
		// --- conditions : cn.
		if (strlen($params['id_phase']) > 0) {
			$c = $params['id_phase'];
			$conditions[] = "and id_phase = '$c'";
		}
		if (strlen($params['id_frm']) > 0) {
			$c = $params['id_frm'];
			$conditions[] = "and id_frm = '$c'";			
		}
		// --- conditions : cn.
		if (strlen($params['id_cn']) > 0) {
			$c = $params['id_cn'];
			$conditions[] = "and id_cn = '$c'";
		}
		// --- conditions : date_debut
		if (strlen($params['date_debut']) > 0) {
			$c = $params['date_debut'];
			$conditions[] = "and date_attribution >= '$c'";
		}
		// --- conditions : date_fin
		if (strlen($params['date_fin']) > 0) {
			$c = $params['date_fin'];
			$conditions[] = "and date_attribution <= '$c'";
		}

		// --- requête SQL : ajout des conditions
		for ($i=0; $i<count($conditions); $i++) {
			$cde .= ' ' . $conditions[$i];
		}	

		$result = $this->requete_sql($this->db, $cde);
			if ($this->debug_mode && !$result) {
			  echo "<b>total_phase</b> $cde (erreur:";
			 print_r($result->errorInfo());
			}
		  $tb = $result->fetchAll(PDO::FETCH_ASSOC);
			return($tb);
	   	
	}
		
	//	-- Tableau des phases.
	public function tableau_phases ( $params='') {	
		//	-- iisation des tableaux.
		if(!isset($params)) $params = array();
		$tb = array();
		
		if(isset($params['phases'])) $phases = $params['phases'];
		//	-- Nom des tables.
		$tbl_phases = $this->nom_table('phase');
		//	-- Requete.
		$cde = "select * from $tbl_phases ";
		$cde .= " where 1 ";
		$result = $this->requete_sql($this->db, $cde);
		if ($this->debug_mode && !$result) {
			echo "<b>total_phase</b> $cde (erreur:";
			print_r($result->errorInfo());
		}
		$tb = $result->fetchAll(PDO::FETCH_ASSOC);
	return($tb);
  	}
	
 
/* Tableau statistique des phases par frm */
// module = statistique  mode = 
  function _total_phase( $params='' ){
	$tb = array();
	$tbl = $this->nom_table('phase_par_el');
	if(!isset($params)) $params = array();
	if(isset($params['id_frm'])) $id_frm = $params['id_frm'];
	if(isset($params['id_phase'])) $id_phase = $params['id_phase'];
	
    $cde = "select count(*) total from $tbl ";
    $cde .= " where 1";
    $cde .= " and $tbl.id_frm = $id_frm";
    $cde .= " and $tbl.id_phase = $id_phase";

    // --- conditions
    $conditions = array();
    // --- conditions : date_debut
    if (strlen($params['date_debut']) > 0) {
    	$c = $params['date_debut'];
    	$conditions[] = "and date_attribution >= '$c'";
    }
    // --- conditions : date_fin
    if (strlen($params['date_fin']) > 0) {
    	$c = $params['date_fin'];
    	$conditions[] = "and date_attribution <= '$c'";
    }

    // --- requête SQL : ajout des conditions
    for ($i=0; $i<count($conditions); $i++) {
    	$cde .= ' ' . $conditions[$i];
    }	

 $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>total_phase</b> $cde (erreur:";
     print_r($result->errorInfo());
    }
  $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return($tb);
  } 
  

/* Tableau statistique des phases par frm */
// -- module = statistique   mode = phase_cn
  function total_phase_cn($id_cn, $id_phase){
  $tb = array();
    $cde = "select count(*) total from dc_phase_par_el, dc_demande_dd";
    $cde .= " where 1";
    $cde .= " and dc_phase_par_el.id_phase = $id_phase";
    $cde .= " and dc_phase_par_el.id_d_d = dc_demande_dd.id_demande";
    $cde .= " and dc_demande_dd.id_cn = $id_cn";

 $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>total_phase</b> $cde (erreur:";
     print_r($result->errorInfo());
    }
  $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return($tb);
  }


                  /* ------------------------------------------------------------------------------------------ */
                  /* ---------------------------------- FIN CALCULS STATISTIQUES ------------------------------ */
                  /* ------------------------------------------------------------------------------------------ */


                  /* ------------------------------------------------------------------------------------------ */
                  /* ---------------------------------------- EXPORTS ----------------------------------------- */
                  /* ------------------------------------------------------------------------------------------ */

// Fonction des EXPORTS
// -- Module = exports
function export($params = ''){
  $tb = array();
  $tbl_dd = $this->nom_table('demande_dd');
  $tbl_phase_par_el = $this->nom_table('phase_par_el');
  $tbl_current_phase = $this->nom_table('current_phase');
  if(!isset($params)) $params = array();

    $cde = "select * from $tbl_dd , $tbl_current_phase, $tbl_phase_par_el";
    $cde .= " where 1";
    $cde .= " and $tbl_dd.deleted = 0";
    $cde .= " and $tbl_current_phase.id_demande = $tbl_dd.id_demande ";
    $cde .= " and $tbl_current_phase.id_demande = $tbl_phase_par_el.id_d_d ";
    $cde .= " and $tbl_current_phase.id_phase = $tbl_phase_par_el.id_phase ";

    $conditions = array();
    // --- conditions : session
    if (intval($params['session']) > 0) {
      $c = $params['session'];
      $conditions[] = "and $tbl_dd.id_session = $c";
    }
    // --- conditions : phase
    if (intval($params['phase']) > 0) {
      $c = $params['phase'];
      $conditions[] = "and $tbl_current_phase.id_phase = $c";
    }
    // --- conditions : cn
    if (intval($params['cn']) > 0) {
      $c = $params['cn'];
      $conditions[] = "and $tbl_dd.id_cn = $c";
    }
    // --- conditions : frm
    if (intval($params['frm']) > 0) {
      $c = $params['frm'];
      $conditions[] = "and $tbl_dd.id_frm = $c";
    }
     if ($params['datedebut']) {
      $c = $params['datedebut'];
      $conditions[] = "and $tbl_phase_par_el.date_attribution >= '$c'";
    }
     if ($params['datefin']) {
      $c = $params['datefin'];
      $conditions[] = "and $tbl_phase_par_el.date_attribution <= '$c' ";
    }
    
/*    if (isset($tbphase)) {
foreach($tbphase as $key => $element) {
    reset($tbphase);
    if ($key === key($tbphase)){      
        $conditions[]="and $tbl_phase_par_el.id_phase = $element";}else{
    $conditions[] =" or $tbl_phase_par_el.id_phase = $element";}
}
    }*/

   for ($i=0; $i<count($conditions); $i++) {
        $cde .= ' ' . $conditions[$i];
      }  

    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>export</b> $cde (erreur:";
     print_r($result->errorInfo());
    }
  $tb = $result->fetchAll(PDO::FETCH_ASSOC);
    return($tb);

}

                  /* ------------------------------------------------------------------------------------------ */
                  /* ---------------------------------------- FIN EXPORTS ------------------------------------- */
                  /* ------------------------------------------------------------------------------------------ */


                  /* ------------------------------------------------------------------------------------------ */
                  /* ----------------------------------- GESTION DOUBLONS ------------------------------------- */
                  /* ------------------------------------------------------------------------------------------ */

// -- Verification si un candidat existe deja dans la bdd
// -- Modules : Ajout manuel, import (Diplomeo, ancienne appli )
 function doublon_nom_prenom( $tb ){
    //  -- Vérification du tableau
    if(!is_array($tb)) exit(); 
    
    if(empty($tb['nom'])) exit();
        else $nom = $tb['nom'];

    if(empty($tb['prenom'])) exit();
        else $prenom = $tb['prenom'];
    
    //  -- Tables utilisées
    $tbl_demande_dd = 'dc_demande_dd';
    
    // --- requête SQL
    $cde = "select * from dc_demande_dd";
    $cde .= " where 1 ";
    $cde .="and dc_demande_dd.nom like '$nom' ";
    $cde .="and dc_demande_dd.prenom like '$prenom' ";

    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>doc</b> $cde (erreur:";
      print_r($result->errorInfo());
    }
  $tb = $result->fetch(PDO::FETCH_ASSOC);
    return($tb);

  }

  

                  /* ------------------------------------------------------------------------------------------ */
                  /* -----------------------------------     SCRIPTS      ------------------------------------- */
                  /* ------------------------------------------------------------------------------------------ */

// -- Recuperer la phase actuelle d'un el : 
// -- a ete utilise pour le script de remplissage de la BDD dc_current_phase
   function check_phase($params= ''){

  $tb = array();
  $tbl_dd = $this->nom_table('demande_dd');
  $tbl_phase_par_el = $this->nom_table('phase_par_el');
  $tbl_current_phase = $this->nom_table('current_phase');

  if(!isset($params)) $params = array();

    $cde = "select id_phase, id_demande from $tbl_phase_par_el, $tbl_dd ";
    $cde .= "where 1 ";
    $cde .= "and $tbl_phase_par_el.id_d_d = $tbl_dd.id_demande ";
    
    // --- conditions
    $conditions = array();
    // --- conditions : id du candidat dans demande_dd
    if (intval($params['id_candidat']) > 0) {
      $c = $params['id_candidat'];
      $conditions[] = "and $tbl_phase_par_el.id_d_d = $c";
    }
    // Recupere la phase la plus recente si le param current_phase est sup a 0
    if (intval($params['current_phase']) > 0) {
      $conditions[] = " order by date_attribution DESC LIMIT 1";
    }

    for ($i=0; $i<count($conditions); $i++) {
            $cde .= ' ' . $conditions[$i];
          }  
//var_dump($cde); exit;
    $result = $this->requete_sql($this->db, $cde);
    if ($this->debug_mode && !$result) {
      echo "<b>check_phase</b> $cde (erreur:";
     print_r($result->errorInfo());
    }
    $tb = $result->fetchAll(PDO::FETCH_ASSOC);

    return($tb);

    }


} /* FIN DE LA CLASSE baseClass*/

?>