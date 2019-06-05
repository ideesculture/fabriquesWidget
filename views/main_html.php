<?php
/* ----------------------------------------------------------------------
 * app/widgets/links/views/main_html.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2010-2016 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */
 ?>
 <div class="dashboardWidgetContentContainer">
<?php
    $o_data = new Db();
 	$po_request			= $this->getVar('request');
 	if($this->request->user->get("user_id") != 1) {
	 	
	 	$fabrique = $this->request->user->get("sms_number");
	 	require_once(__CA_LIB_DIR__."/Search/ObjectSearch.php");
	 	$o_search = new ObjectSearch(); //instantiate a new search object
	 	$qr_hits = $o_search->search("idno:".$fabrique);
	 	print "<script>console.log('Requête = idno:".$fabrique."');</script>";
	 	$count=0;
		while($qr_hits->nextHit()) {
			if ($qr_hits->get('ca_objects.type_id') != 23 ) continue;
			print "<h3>Fabrique : ".$qr_hits->get('ca_objects.preferred_labels.name')." <a href=/gestion/index.php/editor/objects/ObjectEditor/Summary/object_id/".$qr_hits->get('ca_objects.object_id').">(".$qr_hits->get('ca_objects.idno').")</a></h3>";  //confirm the id number
			print "<hr/>";
			$object_id_fabrique =  $qr_hits->get('ca_objects.object_id');
			
			//REQUETE EGLISES
			 $qr_result = $o_data->query("SELECT ca_objects.object_id, idno, name, status FROM ca_objects LEFT JOIN ca_object_labels cal ON cal.object_id=ca_objects.object_id WHERE parent_id =".$object_id_fabrique." and deleted=0");
			 
			 while($qr_result->nextRow()) {
?>
                <form action="/gestion/index.php/editor/objects/ObjectEditor/Edit" method="post" id="NewChildForm" target="_top" enctype="multipart/form-data">
				<input type="hidden" name="_formName" value="NewChildForm">
				<input name="form_timestamp" value="1491396998" type="hidden">
				<input name="type_id" value="27" type="hidden"><!-- objet physique -->
				<input name="object_id" value="0" type="hidden">
				<input name="parent_id" value="<?php print $qr_result->get('object_id'); ?>" type="hidden">
                <?php print "Eglise <b><a href=".__CA_URL_ROOT__."/index.php/find/SearchObjects/Index/search/".$qr_result->get('idno')."> ".$qr_result->get('name')." ; <small>".$qr_result->get('idno')."</small></b></a> "; ?>
<?php
	
			    $qr_result1 = $o_data->query("select avancement from acf_suivi_avancement where eglise_id = ".$qr_result->get('object_id'));
              // print "</br>test avancement:";
             //   $testeglise = new ca_objects($qr_result->get('object_id'));
                
              //  print "</br> ------- </br>";
                $avancement = $qr_result1->getAllRows();
                $avancement = reset($avancement)["avancement"]*1;
?>
                    <br/>
                    <button type="submit">Ajouter un objet</button></form>
                 <span style="float:right;padding-right:12px;">
<?php
switch($qr_result->get('status')) {
    case 2:
        print "<small style='color:gray;'>DEMANDE DE VALIDATION TRANSMISE AU DIOCÈSE</small>";
        break;
    case 3:
        print "<small style='color:gray;'>INVENTAIRE VALIDÉ</small>";
        break;
    case 1:
    case 0:
    default:
?>
                <small style="color:gray;">RÉCOLEMENT IRPA</small>
                <?php print " <a style='background-color:#53B1C5;color:white;border-radius:4px;padding:3px 4px;' href='".__CA_URL_ROOT__."/index.php/suiviInventaireEglises/Statistics/Eglise/ID/".$qr_result->get('object_id')."'>".(real) $avancement." %</a>"; ?>
                <a style="border:1px solid #ccc;border-radius:4px;padding:2px 5px;color:#333;background-color: white" href="<?php print __CA_URL_ROOT__."/index.php/suiviInventaireEglises/Statistics/JaiFini/ID/".$qr_result->get('object_id'); ?>">J'ai fini</a>
<?php
        break;
}
?>
                </span>
                </P>
				

<?php
			 }
		}
	} else {
		print "Utilisateur admin logué, impossible d'afficher ici toutes les fabriques de toutes les églises.";
	}
 	
?>
</div>
