<?

	SetValue($_IPS['VARIABLE'], $_IPS['VALUE']);

	$parentid = IPS_GetParent($_IPS['VARIABLE']);
	$bluetoothnameid = IPS_GetObjectIDByName('Bluetooth Name', $parentid);
	$btid = IPS_GetObjectIDByName('Bluetooth', $parentid);
	$mode = GetValueBoolean($btid);
	$bluetoothname = GetValueInteger($bluetoothnameid);

	LM_bluetooth($parentid, $bluetoothname, $mode);

?>
