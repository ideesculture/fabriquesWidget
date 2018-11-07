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
 	$po_request			= $this->getVar('request');
 	if($this->request->user->get("user_id") != 1) {
	 	$fabrique = $this->request->user->get("sms_number");
	 	require_once(__CA_LIB_DIR__."/ca/Search/ObjectSearch.php");
	 	$o_search = new ObjectSearch(); //instantiate a new search object
	 	$qr_hits = $o_search->search("idno:".$fabrique." AND ca_objects.type_id:23");
	 	print "<script>console.log('Requête = idno:".$fabrique." AND ca_objects.type_id:23');</script>";
	 	$count=0;
		while($qr_hits->nextHit()) {
			print "<h3>Fabrique : ".$qr_hits->get('ca_objects.preferred_labels.name')." <a href=/gestion/index.php/editor/objects/ObjectEditor/Summary/object_id/".$qr_hits->get('ca_objects.object_id').">(".$qr_hits->get('ca_objects.idno').")</a></h3>";  //confirm the id number
			print "<hr/>";
			$object_id_fabrique =  $qr_hits->get('ca_objects.object_id');
			$o_data = new Db();
			 $qr_result = $o_data->query("SELECT ca_objects.object_id, idno, name FROM ca_objects LEFT JOIN ca_object_labels cal ON cal.object_id=ca_objects.object_id WHERE parent_id =".$object_id_fabrique." and deleted=0");
			 
			 while($qr_result->nextRow()) {
			       ?>
			      <form action="/gestion/index.php/editor/objects/ObjectEditor/Edit" method="post" id="NewChildForm" target="_top" enctype="multipart/form-data">
				<input type="hidden" name="_formName" value="NewChildForm">
				<input name="form_timestamp" value="1491396998" type="hidden">
				<input name="type_id" value="27" type="hidden"><!-- objet physique -->
				<input name="object_id" value="0" type="hidden">
				<input name="parent_id" value="<?php print $qr_result->get('object_id'); ?>" type="hidden">
				<p><?php print "Eglise <b><a href=http://acf.lescollections.be/gestion/index.php/find/SearchObjects/Index/search/".$qr_result->get('idno')."> ".$qr_result->get('name')." ; <small>".$qr_result->get('idno')."</small></b></a>"; ?>
				<button type="submit">Ajouter un objet</button></P>
				</form>

			      <?php
			      
			 }
 
 
				
		}
	} else {
		print "Utilisateur admin logué, impossible d'afficher ici toutes les fabriques de toutes les églises.";
	}
 	
?>
</div>
