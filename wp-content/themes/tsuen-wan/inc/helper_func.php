<?php 
function get_phase(){
	$phase_levels = get_phase_levels();
	$all_levels = array();
	foreach($phase_levels as $phase_level_key => $phase_level){
		$phase_level_item = explode("_",$phase_level_key);
		if(!key_exists($phase_level_item[0], $all_levels)){
			$all_levels[$phase_level_item[0]] = array();
			$name = explode(" - ",$phase_level);
			$all_levels[$phase_level_item[0]]["name"] = $name[0];
		}
	}
	$all_phases = array();
	foreach($all_levels as $key => $all_level){
		$all_phases["phase_".$key] = $all_level["name"];
	}
	return $all_phases;
}

function get_levels($phase){
	$phase_levels = get_phase_levels();
	$all_levels = array();
	foreach($phase_levels as $phase_level_key => $phase_level){
		$phase_level_item = explode("_",$phase_level_key);
		if(!key_exists($phase_level_item[0], $all_levels)){
			$all_levels[$phase_level_item[0]] = array();
		}
		$all_levels[$phase_level_item[0]][] = $phase_level_item[1];
	}
	return key_exists($phase, $all_levels) ? $all_levels[$phase] : array();
}

function acf_load_phase_level_field_choices( $field ) {
	$all_phases_level = get_phase_levels();
	$field['choices'] = $all_phases_level;
	return $field;
}
add_filter('acf/load_field/name=shop_phase_level', 'acf_load_phase_level_field_choices');

function get_phase_levels(){
	$lang        = pll_current_language();
	if($lang == "zh-hant"){
		$all_levels = array(
			"1_LB" => "第一期 - LB",
			"1_UB" => "第一期 - UB",
			"1_L1" => "第一期 - L1",
			"1_L2" => "第一期 - L2",
			"1_L3" => "第一期 - L3",
			"1_L4" => "第一期 - L4",
			"1_L5" => "第一期 - L5",
			"1_L6" => "第一期 - L6",
			"1_L7" => "第一期 - L7",
			"1_L8" => "第一期 - L8",
			"3_L1" => "第三期 - L1",
			"3_L2" => "第三期 - L2",
			"3_L3" => "第三期 - L3",
		);
	}elseif ($lang == "zh-hans"){
		$all_levels = array(
			"1_LB" => "第一期 - LB",
			"1_UB" => "第一期 - UB",
			"1_L1" => "第一期 - L1",
			"1_L2" => "第一期 - L2",
			"1_L3" => "第一期 - L3",
			"1_L4" => "第一期 - L4",
			"1_L5" => "第一期 - L5",
			"1_L6" => "第一期 - L6",
			"1_L7" => "第一期 - L7",
			"1_L8" => "第一期 - L8",
			"3_L1" => "第三期 - L1",
			"3_L2" => "第三期 - L2",
			"3_L3" => "第三期 - L3",
		);
	}else{
		$all_levels = array(
			"1_LB" => "Phase I - LB",
			"1_UB" => "Phase I - UB",
			"1_L1" => "Phase I - L1",
			"1_L2" => "Phase I - L2",
			"1_L3" => "Phase I - L3",
			"1_L4" => "Phase I - L4",
			"1_L5" => "Phase I - L5",
			"1_L6" => "Phase I - L6",
			"1_L7" => "Phase I - L7",
			"1_L8" => "Phase I - L8",
			"3_L1" => "Phase III - L1",
			"3_L2" => "Phase III - L2",
			"3_L3" => "Phase III - L3",
		);
	}

	return $all_levels;
}

function get_others_gifts(){
	$shop_others_gifts            = array();
	$shop_others_gifts["other_1"] = pll__( "Point Dollar" );
	//$shop_others_gifts["other_2"] = pll__( "SHKP E-gift Cert" );
	//$shop_others_gifts["other_3"] = pll__( "SHKP Gift Cert" );
	$shop_others_gifts["other_4"] = pll__( "Mall Gifting Card/ SHKP Malls Gift Card" );
	return $shop_others_gifts;
}

