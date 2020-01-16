<?

	SetValue($_IPS['VARIABLE'], $_IPS['VALUE']);

	$parentid = IPS_GetParent($_IPS['VARIABLE']);
	$helligkeitid = IPS_GetObjectIDByName('Helligkeit', $parentid);
	$modeid = IPS_GetObjectIDByName('Helligkeit Auto Modus', $parentid);
	$mode = GetValueBoolean($modeid);
	$helligkeit = GetValueInteger($helligkeitid);
	$screensaver = GetValueBoolean($screensaverid);

	LM_display($parentid, $helligkeit, $mode, $screensaver);

?>
