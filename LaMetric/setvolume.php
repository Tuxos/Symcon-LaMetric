<?

	SetValue($_IPS['VARIABLE'], $_IPS['VALUE']);

	$parentid = IPS_GetParent($_IPS['VARIABLE']);
	$volumeid = IPS_GetObjectIDByName('Volume', $parentid);
	$volume = GetValueInteger($volumeid);

	LM_volume($parentid, $volume);

?>
