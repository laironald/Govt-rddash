<? if (1==0) { ?>
<form action="http://www.projectreporter.nih.gov/searchtemppage.cfm" method="post">
	<input id="p_projectnum" name="p_projectnum" value="<?=$_GET['award'].'%'?>">
	<input id="p_ac" name="p_ac" value="All">
	<input id="p_ac_codes" name="p_ac_codes" value="All">
	<input id="p_at" name="p_at" value="All">
	<input id="p_at_codes" name="p_at_codes" value="All">
	<input id="p_awrd_nd" name="p_awrd_nd" value="mm/dd/yyyy">
	<input id="p_awrd_opr" name="p_awrd_opr" value="GT">
	<input id="p_country" name="p_country" value="All">
	<input id="p_country_codes" name="p_country_codes" value="All">
	<input id="p_fm" name="p_fm" value="All">
	<input id="p_fm_codes" name="p_fm_codes" value="All">
	<input id="p_fy" name="p_fy" value="CP">
	<input id="p_fy_text" name="p_fy_text" value="Active Projects">
	<input id="p_ic" name="p_ic" value="All">
	<input id="p_ic_codes" name="p_ic_codes" value="All">
	<input id="p_ic_type" name="p_ic_type" value="Admin">
	<input id="p_irg" name="p_irg" value="All">
	<input id="p_irg_codes" name="p_irg_codes" value="All">
	<input id="p_opr" name="p_opr" value="and">
	<input id="p_proj_ed" name="p_proj_ed" value="mm/dd/yyyy">
	<input id="p_proj_sd" name="p_proj_sd" value="mm/dd/yyyy">
	<input name="p_PHR" value="">
	<input name="p_RFA" value="">
	<input name="p_cd" value="">
	<input name="p_cd_codes" value="">
	<input name="p_city" value="">
	<input name="p_dc" value="">
	<input name="p_dc_codes" value="">
	<input name="p_dept_codes" value="">
	<input name="p_dept_list" value="">
	<input name="p_dunsnum" value="">
	<input name="p_keywords" value="">
	<input name="p_mcc_codes" value="">
	<input name="p_mcc_list" value="">
	<input name="p_org" value="">
	<input name="p_pi_first" value="">
	<input name="p_pi_last" value="">
	<input name="p_pt" value="">
	<input name="p_sr" value="">
	<input name="p_sr_codes" value="">
	<input name="p_state" value="">
	<input name="p_state_codes" value="">

	<input type="submit">
<? } ?>

<style>
	font-family: Tahoma;
	font-size: 8pt;
</style>

	Set project number to <input type="text" value="<?=$_GET['award'].'%'?>">:
	<hr />

	<iframe src="http://www.projectreporter.nih.gov" width="100%" height="90%">
	</iframe>

</form>
