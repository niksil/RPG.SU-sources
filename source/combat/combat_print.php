<?
//Функция print_boy из class_combat.php
$this->print_header();
?>
<script language=javascript>
var MS_KULAK=<?=$this->char['MS_KULAK'];?>;
var MS_WEAPON=<?=$this->char['MS_WEAPON'];?>;
<?
//</script>
//<SCRIPT language=javascript src="js/battle.js"></script>


//***********************************************************************************
//***********************************************************************************
?>
var otprav='';
var attack='';
var zash='';
var lech='';
var radi=1;
var kol_th=0;
var kol_svitok=0;

function bg_black(el)
{
el.style.background='black';
}
function bg_none(el)
{
el.style.background='';
}
function on_mouse1()
{
tr = document.getElementById("tr_attackkulak");
bg_black(tr);
img = document.getElementById("img_attackkulak");
img.src = "http://<?=IMG_DOMAIN;?>/combat/kulak_11_2.gif";
}
function out_mouse1()
{
tr = document.getElementById("tr_attackkulak");
bg_none(tr);
img = document.getElementById("img_attackkulak");
img.src = "http://<?=IMG_DOMAIN;?>/combat/kulak_11_1.gif";
}
function on_mouse2()
{
tr = document.getElementById("tr_attack");
tr.style.background='black';
img = document.getElementById("img_attack");
img.src = "http://<?=IMG_DOMAIN;?>/combat/udar18_2.gif";
}
function out_mouse2()
{
tr = document.getElementById("tr_attack");
tr.style.background='';
img = document.getElementById("img_attack");
img.src = "http://<?=IMG_DOMAIN;?>/combat/udar18_1.gif";
}
function on_mouse3()
{
tr = document.getElementById("tr_defense");
tr.style.background='black';
img = document.getElementById("img_defense");
img.src = "http://<?=IMG_DOMAIN;?>/combat/zashita_13_2.gif";
}
function out_mouse3()
{
tr = document.getElementById("tr_defense");
tr.style.background='';
img = document.getElementById("img_defense");
img.src = "http://<?=IMG_DOMAIN;?>/combat/zashita_13_1.gif";
}
function on_mouse4()
{
tr = document.getElementById("tr_lech");
tr.style.background='black';
img = document.getElementById("img_lech");
img.src = "http://<?=IMG_DOMAIN;?>/combat/lechenie15_2.gif";
}
function out_mouse4()
{
tr = document.getElementById("tr_lech");
tr.style.background='';
img = document.getElementById("img_lech");
img.src = "http://<?=IMG_DOMAIN;?>/combat/lechenie15_1.gif";
}
function on_mouse5()
{
tr = document.getElementById("tr_elik");
tr.style.background='black';
img = document.getElementById("img_elik");
img.src = "http://<?=IMG_DOMAIN;?>/combat/elik.gif";
}
function out_mouse5()
{
tr = document.getElementById("tr_elik");
tr.style.background='';
img = document.getElementById("img_elik");
img.src = "http://<?=IMG_DOMAIN;?>/combat/elik.gif";
}
function on_mouse6()
{
tr = document.getElementById("tr_luk");
tr.style.background='black';
img = document.getElementById("img_luk");
img.src = img_luk;
}
function out_mouse6()
{
tr = document.getElementById("tr_luk");
tr.style.background='';
img = document.getElementById("img_luk");
img.src = img_luk;
}
function on_mouse7()
{
tr = document.getElementById("tr_throw");
tr.style.background='black';
img = document.getElementById("img_throw");
img.src = "http://<?=IMG_DOMAIN;?>/combat/knife.gif";
}
function out_mouse7()
{
tr = document.getElementById("tr_throw");
tr.style.background='';
img = document.getElementById("img_throw");
img.src = "http://<?=IMG_DOMAIN;?>/combat/knife.gif";
}
function on_mouse8()
{
tr = document.getElementById("tr_svitok");
tr.style.background='black';
img = document.getElementById("img_svitok");
img.src = "http://<?=IMG_DOMAIN;?>/combat/svitok.gif";
}
function out_mouse8()
{
tr = document.getElementById("tr_svitok");
tr.style.background='';
img = document.getElementById("img_svitok");
img.src = "http://<?=IMG_DOMAIN;?>/combat/svitok.gif";
}

function attack_vkogo()
{
if (prot_id==0)
{
	return vkogo;
}
return prot_id;
}


function tak(what)
{
if (what=='') what="ex";
{
	oldEl           = document.getElementById("old");
	pisatEl         = document.getElementById("pisat");
	oldEl.style.display='none';
	pisatEl.style.display='block';
	procEl          = document.getElementById("proc");
	skokEl          = document.getElementById("skok");
	knopkEl         = document.getElementById("knopk");
	vybrEl          = document.getElementById("vybr");
	switch(what)
	{
		case 'a1':
			nazvan='Удар кулаком';
			nazv='Атака';
		break;

		case 'a2':
			nazvan='Удар оружием';
			nazv='Атака';
		break;

		case 'a3':
			nazvan='Удар магией';
			nazv='Атака';
		break;

		case 'a4':
			nazvan='Удар артефактом';
			nazv='Атака';
		break;

		case 'a5':
			nazvan='Выстрел из лука';
			nazv='Атака';
		break;

		case 'a6':
			nazvan='Бросок метат.предмета';
			nazv='Атака';
		break;

		case 'a7':
			nazvan='Исп.боевого свитка, ';
			nazv='Атака';
		break;

		case 'z1':
			nazvan='Защита щитом';
			nazv='Защита';
		break;

		case 'z2':
			nazvan='Защита магией';
			nazv='Защита';
		break;

		case 'z3':
			nazvan='Защита артефактом';
			nazv='Защита';
		break;

		case 'l1':
			nazvan='Лечение магией';
			nazv='Лечение';
		break;

		case 'l2':
			nazvan='Лечение артефактом';
			nazv='Лечение';
		break;

		case 'l3':
			nazvan='Лечение эликсиром';
			nazv='Лечение';
		break;

		case 'ex1':
			procEl.value=100;
			radi=1;
			tak("ex");
		break;

		case 'ex2':
			procEl.value=100;
			radi=2;
			tak("ex");
		break;

		case 'ex3':
			procEl.value=100;
			radi=3;
			tak("ex");
		break;

		case 'ex4':
			procEl.value=100;
			radi=4;
			tak("ex");
		break;

		case 'ex5':
			procEl.value=100;
			radi=5;
			tak("ex");
		break;

		case 'ex11':
			procEl.value=0;
			radi=1;
			kol_th++;
			tak("ex");
		break;

		case 'ex12':
			procEl.value=0;
			radi=2;
			kol_th++;
			tak("ex");
		break;

		case 'ex13':
			procEl.value=0;
			radi=3;
			kol_th++;
			tak("ex");
		break;

		case 'ex14':
			procEl.value=0;
			radi=4;
			kol_th++;
			tak("ex");
		break;

		case 'ex15':
			procEl.value=0;
			radi=5;
			kol_th++;
			tak("ex");
		break;

		case 'ex16':
			procEl.value=0;
			radi=0;
			kol_svitok++;
			tak("ex");
		break;

		case 'ex':
		re=new RegExp('^[0-9]+$');
		if (vkogo=="") vkogo=0;
		if
		( 
		((skokEl.innerHTML-procEl.value)>=0)//Если кол-во процентов не больше свободного кол-ва процентов
		&& //И 
		(re.test(procEl.value)) //кол-во процентов = число
		&&//И 
		(
			(re.test(vkogo)) //vkogo = число
			||//ИЛИ 
			(old1.indexOf("l")!=-1) //при лечении vkogo может быть==''. Это значит что лечим себя
			||//ИЛИ
			(old1.indexOf("z")!=-1) //при защите vkogo может быть==''. Это значит что защищаем себя
		)
		&&//И 
		(
			(re.test(chem)) // Чем ударил = число
		)
		&&//И 
		(
			(re.test(radi)) // Куда ударил = число
		)
		)
		{
			skokEl.innerHTML=skokEl.innerHTML-procEl.value;
			oldEl.style.display='block';
			pisatEl.style.display='none';
			if ((procEl.value>0)||(old1.indexOf("a5")!=-1)||(old1.indexOf("a6")!=-1)||(old1.indexOf("a7")!=-1))
			{
				attack = 1;
				if ((old1.indexOf("a1")!=-1)) 
				{
					el = document.getElementById('attack_ktype2');
					if (el)
						if (el.checked) attack = 2;
					el = document.getElementById('attack_ktype3');
					if (el)
						if (el.checked) attack = 3;
				}
				if ((old1.indexOf("a2")!=-1))
				{
					el = document.getElementById('attack_type2');
					if (el)
						if (el.checked) attack = 2;
					el = document.getElementById('attack_type3');
					if (el)
						if (el.checked) attack = 3;
					el = document.getElementById('attack_type4');
					if (el)
						if (el.checked) attack = 4;
					el = document.getElementById('attack_type5');
					if (el)
						if (el.checked) attack = 5;
				} 
				kuda = "";
				knopkEl.style.display='block';
				if (otprav=='')
					otprav=old1+':'+procEl.value+':'+radi+':'+vkogo+':'+chem+':'+attack;
				else
					otprav=otprav+';'+old1+':'+procEl.value+':'+radi+':'+vkogo+':'+chem+':'+attack;
					
				//alert(otprav);

				if (old1.indexOf("l")==-1)
				{
					if ((old1.indexOf("z1")==-1)&&(old1.indexOf("z3")==-1))
						switch(radi)
						{
							case 1:
							kuda='голова';
							break;

							case 2:
							kuda='тело';
							break;

							case 3:
							kuda='пах';
							break;

							case 4:
							kuda='плечо';
							break;

							case 5:
							kuda='ноги';
							break;
						}
				}
				if ((old1.indexOf("a3")!=-1)||(old1.indexOf("z2")!=-1)||(old1.indexOf("l")!=-1))
					vybrEl.innerHTML=vybrEl.innerHTML+nazvan+' ('+procEl.value+'%)<br>';
				else
				{
					if ((old1.indexOf("a1")!=-1))
					{
						if (attack==2) {nazvan = 'Прицельный удар кулаком'}
						if (attack==3) {nazvan = 'Мощный удар кулаком'}
					}
					if ((old1.indexOf("a2")!=-1))
					{
						if (attack==2) {nazvan = 'Прицельный удар оружием'}
						if (attack==3) {nazvan = 'Мощный удар оружием'}
						if (attack==4) {nazvan = 'Круговая защита оружием'}
						if (attack==5) {nazvan = 'Пробивающий удар оружием'}
					}
					if ((old1.indexOf("a7")!=-1))
					{
						vybrEl.innerHTML=vybrEl.innerHTML+nazvan;
					}
					else
					{
						if ((old1.indexOf("a5")!=-1)||(old1.indexOf("a6")!=-1))
						{
							vybrEl.innerHTML=vybrEl.innerHTML+nazvan+' ('+kuda+')<br>';
						}
						else
						{
							vybrEl.innerHTML=vybrEl.innerHTML+nazvan+' ('+procEl.value+'% - '+kuda+')<br>';
						}
					}
				}
			}
			else
			{
				alert('Не указаны проценты очков действий');
				oldEl.style.display='block';
				pisatEl.style.display='none';
			}
		}
		else
		{
			if  ((skokEl.innerHTML-procEl.value)<0)//Если кол-во процентов не больше свободного кол-ва процентов
			{
				alert('Нехватает процентов очков действий'); 
			}
			else
			{
				if ((re.test(procEl.value)) //кол-во процентов = число
					&&//И
					(procEl.value==""))
				{
					alert('Нехватает процентов очков действий'); 
				}
				else
				{
					if ((!re.test(vkogo))
					&&//И
					(old1.indexOf("l")!=-1) //при лечении vkogo может быть==''. Это значит что лечим себя
					&&//И
					(old1.indexOf("z")!=-1)) //при защите vkogo может быть==''. Это значит что защищаем себя)
					{
						alert('Не выбрана цель');
					}
					else
					{
						if (!(re.test(chem)))
						{
							alert('Не выбран предмет хода');
						}
						else
						{
							if (!re.test(radi))
							{
								alert('Не выбрана зона хода');
							}
						}
					}
				}
			}
			oldEl.style.display='block';
			pisatEl.style.display='none';
		}
		break;
	}
	//запрет на кол-во действий защиты щитом более 2 раз за ход
	if (otprav.indexOf("z1")!=-1)
	{
		var num_000 = -1;
		var pos_000 = 0;
		var i_000 = -1;
		what0 = otprav;
		while (pos_000 != -1)
		{
			pos_000 = what0.indexOf("z1", i_000 + 1); 
			num_000+=1;
			i_000 = pos_000;      
		}
		if (num_000>=2)
		{
			if (document.getElementById("defense1"))
			{
				document.getElementById("defense1").style.visibility="hidden";
			}
		}
	}
	if (kol_th>=1)
	{
		//броски/выстрела не более 1 раза за ход
		if (document.getElementById("tr_luk"))
		{
			document.getElementById("tr_luk").style.visibility="hidden";
		}
		if (document.getElementById("tr_throw"))
		{
			document.getElementById("tr_throw").style.visibility="hidden";
		}
	}
	
	if (kol_svitok>=1)
	{
		//свитки не более 1 раза за ход
		if (document.getElementById("tr_svitok"))
		{
			document.getElementById("tr_svitok").style.visibility="hidden";
		}
	}
	
	text='<table width="100%" height="100%"  border="1" cellpadding="0" cellspacing="1">';
	text=text+'<tr>';
	text=text+'<th height="10" bgcolor="#666666">'+nazv+'</td>';
	text=text+'</tr>';
	text=text+'<tr>';
	text=text+'<th height="237" valign="top" bgcolor="#888888" scope="col"><table width="100%"  border="0" cellspacing="3" cellpadding="0">';
	text=text+'<tr>';
	text=text+'<td width="18%"><div align="left" class="style2">'+nazvan+'</div></td>';
	text=text+'<td width="32%">';
	if ((what.indexOf("a")!=-1)&&(what.indexOf("a5")==-1)&&(what.indexOf("a6")==-1)&&(what.indexOf("a7")==-1))
	   text=text+'Ударить с силой ';
	else
	if (what.indexOf("z")!=-1)
	   text=text+'Защита ';
	else
	if ((what.indexOf("l")!=-1)&&(what.indexOf("l3")==-1))
	{
		text=text+'Лечение ';
	}
	if ((what.indexOf("a3")==-1)&&(what.indexOf("z2")==-1)&&(what.indexOf("l1")==-1))
	{
		attack=at_type;
		if (attack=='') attack=1;
								   //И
		if ((what.indexOf("a2")!=-1)&&(attack==4))
		{
			text=text+'<input id="proc" name="proc" type="text" value="100" readonly="true" size="5" maxlength="3">%';
			nazv = 'Круговая защита оружием';
		}
		else
		{
			if ((what.indexOf("l3")!=-1)||(what.indexOf("a5")!=-1)||(what.indexOf("a6")!=-1)||(what.indexOf("a7")!=-1))
			{
				text=text+'<input id="proc" name="proc" type="hidden" size="5" maxlength="3" value="100" readonly="true">';
			}
			else
			{
				text=text+'<input id="proc" name="proc" type="text" size="5" maxlength="3">%';
			}
		}
	}
	else
	{
		text=text+'<input id="proc" name="proc" type="text" size="5" maxlength="3">%';
	}
	text=text+'</td><td width="41%">';
	if ((what.indexOf("l3")==-1)&&(what.indexOf("a5")==-1)&&(what.indexOf("a6")==-1)&&(what.indexOf("a7")==-1))
	{
		text=text+'<input type="button" class="button" onClick=tak("ex") value="'+nazv+'">';
	}
	if (what.indexOf("z")!=-1)
	{
		text=text+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="button" onClick=vkogo=0;tak("ex") value="Защищать себя">';
	}
	if ((what.indexOf("l")!=-1)&&(what.indexOf("l3")==-1))
	{
		text=text+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="button" onClick=vkogo=0;tak("ex") value="Лечить себя">';
	}        
	text=text+'</td></tr>';
	text=text+'<tr>';
	text=text+'<td colspan=4>';
	if ((what.indexOf("a3")!=-1)||(what.indexOf("z2")!=-1)||(what.indexOf("l1")!=-1)||(what.indexOf("l3")!=-1))
		text=text+'<table><tr><td>';
	if ((what.indexOf("a5")!=-1)||(what.indexOf("a6")!=-1)||(what.indexOf("a7")!=-1))
		text=text+'<table width="100%"><tr><td width="180" align="left">';
	if ((what.indexOf("a")!=-1)&&(what.indexOf("a7")==-1)&&(what.indexOf("a3")==-1)&&(what.indexOf("z2")==-1)&&(what.indexOf("l1")==-1)&&(what.indexOf("l3")==-1))
	{
		radi=1;
		if ((what.indexOf("a5")!=-1)||(what.indexOf("a6")!=-1))
		{
			text_proc = "";
			text=text+'<table width="100%"><tr><td><table><tr><td style="width:150px;cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=1;document.getElementById(\'attack_kuda1\').checked=true;"><input id="attack_kuda1" name="radio" type="radio" value="1" checked="true" onClick="radi=1"> В голову </td><td><input type="button" class="button" onClick=tak("ex11") value="'+nazv+text_proc+'"></td></tr><tr><td style="cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=2;document.getElementById(\'attack_kuda2\').checked=true;"><input id="attack_kuda2" name="radio" type="radio" value="2" onClick="radi=2"> В тело </td><td><input type="button" class="button" onClick=tak("ex12") value="'+nazv+text_proc+'"></td></tr><tr><td style="cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=3;document.getElementById(\'attack_kuda3\').checked=true;"><input id="attack_kuda3" name="radio" type="radio" value="3" onClick="radi=3"> В пах </td><td><input type="button" class="button" onClick=tak("ex13") value="'+nazv+text_proc+'"></td></tr><tr><td style="cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=4;document.getElementById(\'attack_kuda4\').checked=true;"><input id="attack_kuda4" name="radio" type="radio" value="4" onClick="radi=4"> В плечо </td><td><input type="button" class="button" onClick=tak("ex14") value="'+nazv+text_proc+'"></td></tr><tr><td style="cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=5;document.getElementById(\'attack_kuda5\').checked=true;"><input id="attack_kuda5" name="radio" type="radio" value="5" onClick="radi=5"> В ноги </td><td><input type="button" class="button" onClick=tak("ex15") value="'+nazv+text_proc+'"></td></tr></table></td>';
		}
		else
		{
			text_proc = " 100%";
			text=text+'<table width="100%"><tr><td><table><tr><td style="width:150px;cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=1;document.getElementById(\'attack_kuda1\').checked=true;"><input id="attack_kuda1" name="radio" type="radio" value="1" checked="true" onClick="radi=1"> В голову </td><td><input type="button" class="button" onClick=tak("ex1") value="'+nazv+text_proc+'"></td></tr><tr><td style="cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=2;document.getElementById(\'attack_kuda2\').checked=true;"><input id="attack_kuda2" name="radio" type="radio" value="2" onClick="radi=2"> В тело </td><td><input type="button" class="button" onClick=tak("ex2") value="'+nazv+text_proc+'"></td></tr><tr><td style="cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=3;document.getElementById(\'attack_kuda3\').checked=true;"><input id="attack_kuda3" name="radio" type="radio" value="3" onClick="radi=3"> В пах </td><td><input type="button" class="button" onClick=tak("ex3") value="'+nazv+text_proc+'"></td></tr><tr><td style="cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=4;document.getElementById(\'attack_kuda4\').checked=true;"><input id="attack_kuda4" name="radio" type="radio" value="4" onClick="radi=4"> В плечо </td><td><input type="button" class="button" onClick=tak("ex4") value="'+nazv+text_proc+'"></td></tr><tr><td style="cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=5;document.getElementById(\'attack_kuda5\').checked=true;"><input id="attack_kuda5" name="radio" type="radio" value="5" onClick="radi=5"> В ноги </td><td><input type="button" class="button" onClick=tak("ex5") value="'+nazv+text_proc+'"></td></tr></table></td>';
		}
		if (what.indexOf("a1")!=-1)
		{
			text=text+'<td width="50%"><div style="width:100%;height:20px;cursor:url(\'/images/nav/hand.cur\'), pointer;" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="document.getElementById(\'attack_ktype\').checked=true;at_type=1;">            <input type="radio" name="attack_type" id="attack_ktype" value="1" checked onClick="at_type=1">Обычная атака</div>';
			if(MS_KULAK>=3)
			{
				text=text+'<div style="width:100%;height:20px;cursor:url(\'/images/nav/hand.cur\'), pointer;" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="document.getElementById(\'attack_ktype2\').checked=true;at_type=2;">            <input type="radio" name="attack_type" id="attack_ktype2" value="2" onClick="at_type=2">Прицельная атака (+25% точн., -20% повр.)</div>';
			}
			if(MS_KULAK>=6)
			{
				text=text+'<div style="width:100%;height:20px;cursor:url(\'/images/nav/hand.cur\'), pointer;" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="document.getElementById(\'attack_ktype3\').checked=true;at_type=3;">            <input type="radio" name="attack_type" id="attack_ktype3" value="3" onClick="at_type=3">Мощная атака (-20% точн., +25% повр.)</div>';
			}
			text=text+'</td>';
		}
		if (what.indexOf("a2")!=-1)
		{
			text=text+'<td width="50%"><div style="width:100%;height:20px;cursor:url(\'/images/nav/hand.cur\'), pointer;" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="document.getElementById(\'attack_type\').checked=true;at_type=1;">            <input type="radio" name="attack_type" id="attack_type" value="1" checked onClick="at_type=1">Обычная атака</div>';
			if(MS_WEAPON>=2)
			{
				text=text+'<div style="width:100%;height:20px;cursor:url(\'/images/nav/hand.cur\'), pointer;" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="document.getElementById(\'attack_type2\').checked=true;at_type=2;">            <input type="radio" name="attack_type" id="attack_type2" value="2" onClick="at_type=2">Прицельная атака (+25% точн., -20% повр.)</div>';
			}
			if(MS_WEAPON>=4)
			{
				text=text+'<div style="width:100%;height:35px;cursor:url(\'/images/nav/hand.cur\'), pointer;" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="document.getElementById(\'attack_type5\').checked=true;at_type=5;">            <input type="radio" name="attack_type" id="attack_type5" value="5" onClick="at_type=5">Пробивающий удар (50% урон,+15% точн.,-15% макс.энергии на удар, игнор. активной защиты)</div>';
			}
			if(MS_WEAPON>=6)
			{
				text=text+'<div style="width:100%;height:20px;cursor:url(\'/images/nav/hand.cur\'), pointer;" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="document.getElementById(\'attack_type3\').checked=true;at_type=3;">            <input type="radio" name="attack_type" id="attack_type3" value="3" onClick="at_type=3">Мощная атака (-20% точн., +25% повр.)</div>';
			}
			if(MS_WEAPON>=8)
			{
				text=text+'<div style="width:100%;height:35px;cursor:url(\'/images/nav/hand.cur\'), pointer;" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="document.getElementById(\'attack_type4\').checked=true;at_type=4;">            <input type="radio" name="attack_type" id="attack_type4" value="4" onClick="at_type=4">Круговая защита оружием (100% очки хода, 70% успех, 0 урона по тебе, -25% макс энергии/маны)</div></td>';
			}
		}
		text=text+'</tr></table>';
	}
	
	if (what.indexOf("z1")!=-1)
	{
		radi=1;
		text=text+'<table><tr><td style="width:150px;cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=1;document.getElementById(\'attack_kuda1\').checked=true;"><input name="radio" id="attack_kuda1" type="radio" value="1" checked="true" onClick="radi=1"> голову и плечо </td><td><input type="button" class="button" onClick=tak("ex1") value="'+nazv+' 100%"></td><td><input type="button" class="button" onClick=vkogo=0;tak("ex1") value="'+nazv+' себя 100%"></td></tr><tr><td style="cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=2;document.getElementById(\'attack_kuda2\').checked=true;"><input id="attack_kuda2" name="radio" type="radio" value="2" onClick="radi=2"> тело и пах </td><td><input type="button" class="button" onClick=tak("ex2") value="'+nazv+' 100%"></td><td><input type="button" class="button" onClick=vkogo=0;tak("ex2") value="'+nazv+' себя 100%"></td></tr><tr><td style="cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=3;document.getElementById(\'attack_kuda3\').checked=true;"><input id="attack_kuda3" name="radio" type="radio" value="3" onClick="radi=3"> пах и ноги </td><td><input type="button" class="button" onClick=tak("ex3") value="'+nazv+' 100%"></td><td><input type="button" class="button" onClick=vkogo=0;tak("ex3") value="'+nazv+' себя 100%"></td></tr></table>';
	}
	
	if (what.indexOf("a3")!=-1)
	{
		chem=spets_attack_radio;
		text=text+'</td><td>'+spets_attack+'</td></table>';
	}
	if (what.indexOf("z2")!=-1)
	{
		chem=spets_def_radio;
		text=text+'</td><td>'+spets_def+'</td></table>';
	}
	if (what.indexOf("l1")!=-1)
	{
		chem=spets_lech_radio;
		text=text+'</td><td>'+spets_lech+'</td></table>';
	}
	if (what.indexOf("l3")!=-1)
	{
		text=text+'</td><td>'+spets_elik+'</td></table>';
	}
	if (what.indexOf("a5")!=-1)
	{
		chem = radi_luk;
		text=text+'</td><td>&nbsp;&nbsp;&nbsp;</td><td>'+spets_luk+'</td></table>';
	}
	if (what.indexOf("a6")!=-1)
	{
		chem = radi_throw;
		text=text+'</td><td>&nbsp;&nbsp;&nbsp;</td><td>'+spets_throw+'</td></table>';
	}
	if (what.indexOf("a7")!=-1)
	{
		chem = radi_svitok;
		text=text+'</td><td>&nbsp;&nbsp;&nbsp;</td><td>'+spets_svitok+'</td></table>';
	}
	text=text+'</tr></table></td></tr></table>';
	pisatEl.innerHTML=text;
	old1=what;
}
}             
</script>
	<table style="width:100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td style="width:200px;" valign="top" bgcolor="#000000">
			<?
			$this->print_left();
			?>
			</td>
			<td valign="top" bgcolor="#000000">
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr bgcolor="#333333">
						<td height="10">
							<div align="center">До конца хода осталось: <font color=ff0000><b><span id="timerr1">
							<? echo $this->combat['time_last_hod']+$this->timeout-time();?></span></b></font> секунд</div>
							<script language="JavaScript" type="text/javascript">
							function tim()
							{
								timer = document.getElementById("timerr1");
								if (timer.innerHTML<=0)
								{
									location.replace("combat.php"); 
								}
								else
								{
									timer.innerHTML=timer.innerHTML-1;
									window.setTimeout("tim()",1000);
								}
							}
							tim();
							at_type = 1;
							</script>
						</td>
					</tr>
					<tr bgcolor="#333333">
						<td height="10">
							<div align="center"><B><font size=2><?=$this->combat['hod'];?></font></b> ход боя<br>
							Тип боя: <b><font color=#AEFFFF><?=$this->str_type_boy;?></font></b><br>
							<?
							if ($this->combat['turnir_type']==1)
							{
								echo 'Формат боя: <b><font color=#C0FFC0>Кулачный бой</font></b><br>';
							}
							if ($this->combat['turnir_type']==2)
							{
								echo 'Формат боя: <b><font color=#C0FFC0>Бой с оружием</font></b><br>';
							}
							if ($this->combat['turnir_type']==3)
							{
								echo 'Формат боя: <b><font color=#C0FFC0>Магический бой</font></b><br>';
							}
							if ($this->combat['turnir_type']==4)
							{
								echo 'Формат боя: <b><font color=#C0FFC0>Полный бой</font></b><br>';
							}
							?>
							У тебя <span id="skok">100</span>% очков действий!</div>
						</td>
					</tr>
					<tr bgcolor="#333333">
						<td height="10">
							<div align="center">
							Твоя позиция: <input id="posit1" type="radio" name="position" checked><span title="+20% к урону (атаке) по противнику, если противник в 'Оборонительной' позиции">Стандартная</span>&nbsp;<input id="posit2" type="radio" name="position"><span title="+40% к урону (атаке) по противнику если противник в 'Стандартной' либо 'Атакующей', но -75% к урону (атаке) по противнику, если противник в 'Оборонительной' позиции">Оборонительня</span>&nbsp;<input id="posit3" type="radio" name="position"><span title="снижает на -75% атаку (урон) от противника если он в 'Атакующей' позиции но усиливает на +20% атаку (урон) от противника если он в 'Стандартной'">Атакующая</span>&nbsp;
							</div>
						</td>
					</tr>
				</table>
				<span id="old">
				<table width="100%"  border="0" cellpadding="0" cellspacing="1">
					<?
					$povtor=0;
					if ($this->combat['hod']>1 AND chaos_war==0)
					{
						$selhod = myquery("SELECT COUNT(*) FROM combat_actions WHERE user_id='".$this->char['user_id']."' AND combat_id='".$this->combat['combat_id']."' AND hod='".($this->combat['hod']-1)."' AND action_type!=33");
						if (mysql_result($selhod,0,0)>0)
						{
							$povtor=1;
						}
					}
					?>
					<tr>
						<td bgcolor="#666666" height="1" valign="top" colspan="3" align="center">
							<span id="vybr"></span><span id="knopk" style="display:none">
							<script language="JavaScript" type="text/javascript">
							function SendForm()
							{
								document.forma.hod.disabled = true;
								<?php
								if ($povtor==1)
								{
									echo 'document.forma1.lasthod.disabled = true;';
								}
								?>
								posit = 1;
								el = document.getElementById('posit2');
								if (el)
									if (el.checked) posit = 2;
								el = document.getElementById('posit3');
								if (el)
									if (el.checked) posit = 3;
								href = '?otprav='+posit+';'+otprav+'';
								otprav='';
								//alert(href);
								location.href=href;
							}
							function SendFormLastHod(otpr)
							{
								document.forma.hod.disabled = true;
								<?php
								if ($povtor==1)
								{
									echo 'document.forma1.lasthod.disabled = true;';
								}
								?>
								posit = 1;
								el = document.getElementById('posit2');
								if (el)
									if (el.checked) posit = 2;
								el = document.getElementById('posit3');
								if (el)
									if (el.checked) posit = 3;
								href = '?otprav='+posit+';'+otpr+'';
								otprav='';
								location.href=href;
							}
							function CancelForm()
							{
								document.forma.hod.disabled = false;
								<?php
								if ($povtor==1)
								{
									echo 'document.forma1.lasthod.disabled = false;';
								}
								?>
								otprav='';
								at_type = 1;
								kol_th=0;
								knopk.style.display='none';
								document.getElementById("vybr").innerHTML='';
								document.getElementById("skok").innerHTML=100;
								if (document.getElementById("defense1"))
								{
									document.getElementById("defense1").style.visibility="visible";
								}
								if (document.getElementById("tr_luk"))
								{
									document.getElementById("tr_luk").style.visibility="visible";
								}
								if (document.getElementById("tr_throw"))
								{
									document.getElementById("tr_throw").style.visibility="visible";
								}
							}
							</script>

							<form name="forma" method="POST">
							<input name="hod"  onclick="SendForm()" type="button" class="button"  value="Ходить">
							<input name="cancel_hod"  onClick="CancelForm()" type="button" class="button" value="Отмена">
							</form>

							</span>
						</td>
					</tr>
<?
$used_array = array();
$selused = myquery("
SELECT game_items_factsheet.type,game_items.used, game_items.item_uselife, game_items.id, game_items_factsheet.name, 
game_items_factsheet.mode, game_items_factsheet.indx,game_items_factsheet.deviation, game_items_factsheet.img, 
game_items_factsheet.sv, game_items_factsheet.hp_p, game_items_factsheet.stm_p, game_items_factsheet.mp_p, 
game_items_factsheet.type_weapon, game_items_factsheet.type_weapon_need, game_items_factsheet.def_type, 
game_items_factsheet.def_index,game_items.kleymo,game_items.item_id,game_items.count_item
FROM game_items, game_items_factsheet 
WHERE game_items.user_id=".$this->char['user_id']." 
AND game_items.item_id=game_items_factsheet.id 
AND game_items.used>0 
AND game_items.item_uselife>0 
AND game_items.priznak=0");
while ($it = mysql_fetch_array($selused))
{
$used_array[$it['used']] = $it;
}
?>
					<tr>
						<td width="33%" height="19" bgcolor="#666666" align="center"><font size=2 face="Verdana,Tahoma,Arial"><b>Атака</b></font></td>
						<td width="34%" height="19" bgcolor="#666666" align="center"><font size=2 face="Verdana,Tahoma,Arial"><b>Защита</b></font></td>
						<td width="33%" height="19" bgcolor="#666666" align="center"><font size=2 face="Verdana,Tahoma,Arial"><b>Лечение</b></font></td>
					</tr>
					<tr>
					<td height="237" valign="top" bgcolor="#888888">
						<table width="100%"  border="0" cellspacing="3" cellpadding="0">
							<?
							if (($this->combat['turnir_type']==0)OR($this->combat['turnir_type']==1)OR($this->combat['turnir_type']==4))
							{
							?>
							<tr id="tr_attackkulak" onMouseOver="on_mouse1()" onMouseOut="out_mouse1()" onClick="vkogo=attack_vkogo();chem=0;tak('a1')" style="cursor:url('/images/nav/hand.cur'), pointer">
								<td width="13%" height="50"><img id="img_attackkulak" src="http://<?=IMG_DOMAIN;?>/combat/kulak_11_1.gif" width="50" height="50"></td>
								<?
								$k=0;
								if (!isset($used_array[1]))
								{
									//если в руках нет оружия
									$k=2;
								}
								$str = $this->char['STR']+$this->char['MS_KULAK']*$k;
								if($str<=0) 
								{
									echo'<td width="87%"><div align="left" class="style2">Укусить из последних сил<br>';
								}
								else
								{
									echo'<td width="87%"><div align="left" class="style2">Удар кулаком<br>Сила '.$this->char['STR'].'';
									if ($this->char['MS_KULAK']!='0') echo' <font color=#000066><b>(+'.($this->char['MS_KULAK']*$k).')</b></font>';
								}
								echo'
								</td>
							</tr>';
							}
							
							if (($this->combat['turnir_type']==0)OR($this->combat['turnir_type']==2)OR($this->combat['turnir_type']==4))
							{
								if (isset($used_array[1]) AND $used_array[1]['item_uselife']>0 AND $used_array[1]['type']!=18)
								{
									?>
									<tr onMouseOver="this.style.background='black'" onMouseOut="this.style.background=''" onClick="vkogo=attack_vkogo();chem=<?=$used_array[1]['id'];?>;tak('a2')" style="cursor:url('/images/nav/hand.cur'), pointer">
									<td height="50">
									<?
									ImageItem($used_array[1]['img'],0,$used_array[1]['kleymo']);
									echo'</td><td><span class="style2">Удар '.$used_array[1]['mode'].'<br>Атака '.$used_array[1]['indx'].'&plusmn;'.$used_array[1]['deviation'].'';
									$MS = 0;
									if($used_array[1]['type_weapon']==0) {
										$MS = $this->char['MS_WEAPON'];
									}
									if($used_array[1]['type_weapon']==1) {
										$MS = $this->char['MS_KULAK'];
									}
									if($used_array[1]['type_weapon']==2) {
										$MS = 0;//$this->char['MS_LUK'];
									}
									if($used_array[1]['type_weapon']==3) {
										$MS = $this->char['MS_SWORD'];
									}
									if($used_array[1]['type_weapon']==4) {
										$MS = $this->char['MS_AXE'];
									}
									if($used_array[1]['type_weapon']==5) {
										$MS = $this->char['MS_SPEAR'];
									}
									if($used_array[1]['type_weapon']==6) {
										$MS = 0;//$this->char['MS_THROW'];
									}
									if ($MS!=0) echo' <font color=ff0000><b>(+'.$MS.')</b></font>';
									echo'<br>Прочность: ';
									if ($used_array[1]['item_uselife']<=0) echo '<font color=ff0000><b>СЛОМАНО</b></font>';
									else echo ''.$used_array[1]['item_uselife'].'%';
									$stm = 8;              
									echo '<br />На удар '.$stm.' энергии';
									echo'</span></td>
									</tr>';
								}
							}

							if (($this->combat['turnir_type']==0)OR($this->combat['turnir_type']==3)OR($this->combat['turnir_type']==4))
							{
								$sele=myquery("select * from game_spets_item where user_id=".$this->char['user_id']." and img='Атака' ORDER BY indx ASC");
								if (mysql_num_rows($sele)>0)
								{
									?>
									<tr id="tr_attack" onMouseOver="on_mouse2()" onMouseOut="out_mouse2()" onClick="vkogo=attack_vkogo();chem=0;tak('a3')" style="cursor:url('/images/nav/hand.cur'), pointer">
									<td height="50"><img id="img_attack" src="http://<?=IMG_DOMAIN;?>/combat/udar18_1.gif" width="50" height="50">
									<?
									echo '</td><td><span class="style2">Удар магией<br>Интеллект '.$this->char['NTL'].'</span></td></tr>';
									echo '<script>spets_attack=\'<table cellpadding="0" cellspacing="0">';
									$i=0;
									$radi=0;
									while ($spets=mysql_fetch_array($sele))
									{
										if ($i==0) $radi=$spets['id'];
										echo '<tr><td style="cursor:url(\\\'/images/nav/hand.cur\\\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=0;chem='.$spets['id'].';document.getElementById(\\\'at_spets'.$spets['id'].'\\\').checked=true;"><input id="at_spets'.$spets['id'].'" type="radio" name="radio" value="'.$spets['id'].'" onClick="radi='.$spets['id'].'"'.(($i==0)?'checked="true"':'').'>&nbsp;&nbsp;'.$spets['name'].' - '.$spets['mode'].' (';
										if ($spets['indx']!=0) echo $spets['indx'].'&plusmn;'.$spets['deviation'].' жизни ';
										if ($spets['indx_mp']!=0) echo $spets['indx_mp'].'&plusmn;'.$spets['indx_mp_deviation'].' маны ';
										if ($spets['indx_stm']!=0) echo $spets['indx_stm'].'&plusmn;'.$spets['indx_stm_deviation'].' энергии ';
										echo') ';
										if ($spets['mana']!=0) echo '-'.ceil($spets['mana']*$this->decrease).' маны ';
										if ($spets['hp']!=0) echo '-'.ceil($spets['hp']*$this->decrease).' жизней ';
										if ($spets['stm']!=0) echo '-'.ceil($spets['stm']*$this->decrease).' энергии ';
										echo'&nbsp;&nbsp;&nbsp;</td><td><input type="button" class="button" value="100% Атака" onClick=chem='.$spets['id'].';radi='.$spets['id'].';document.getElementById("proc").value=100;tak("ex")></tr>';
										$i++;
									}
									echo '</table>\';spets_attack_radio='.$radi.';</script>';
								}
							}
							
							if (($this->combat['turnir_type']==0)OR($this->combat['turnir_type']==2)OR($this->combat['turnir_type']==4))
							{
								if (isset($used_array[3]) and $used_array[3]['sv']=='Атака' and $used_array[3]['count_item']>0)
								{
									?>
									<tr onMouseOver="this.style.background='black'" onMouseOut="this.style.background=''" onClick="vkogo=attack_vkogo();chem=<?=$used_array[3]['id'];?>;tak('a4')" style="cursor:url('/images/nav/hand.cur'), pointer">
									<td height="50">
									<?
									ImageItem($used_array[3]['img'],0,$used_array[3]['kleymo']);
									echo '</td>
									<td><span class="style2">Удар артефактом '.$used_array[3]['mode'].'<br>Атака '.$used_array[3]['indx'].'&plusmn;'.$used_array[3]['deviation'].'';
									if ($this->char['MS_ART']!='0') echo' <font color=ff0000><b>(+'.$this->char['MS_ART'].')</b></font>';
									echo'<br>Кол-во зарядов: ';
									echo ''.$used_array[3]['count_item'].'';
									echo'</span></td>
									</tr>';
								}
								if (isset($used_array[4]) AND $used_array[4]['item_uselife']>0 AND $used_array[4]['type']==18)
								{
									$sele=myquery("select game_items.count_item,game_items.id,game_items.kleymo,game_items_factsheet.name,game_items_factsheet.img,game_items_factsheet.mode,game_items_factsheet.indx,game_items_factsheet.deviation from game_items,game_items_factsheet where game_items.user_id=".$this->char['user_id']." and game_items.priznak=0 AND game_items_factsheet.quantity=".$used_array[4]['item_id']." AND game_items_factsheet.id=game_items.item_id");
									if (mysql_num_rows($sele)>0)
									{
										?>
										<tr id="tr_luk" onMouseOver="on_mouse6()" onMouseOut="out_mouse6()" onClick="vkogo=attack_vkogo();chem=0;tak('a5')" style="cursor:url('/images/nav/hand.cur'), pointer">
										<script>var img_luk='http://<?=IMG_DOMAIN;?>/item/<?=$used_array[4]['img'];?>.gif';</script>
										<td height="50"><img id="img_luk" src="http://<?=IMG_DOMAIN;?>/item/<?=$used_array[4]['img'];?>.gif" width="50" height="50">
										<?
										echo '</td><td><span class="style2">Выстрел из лука';
										echo'<br>Прочность: ';
										if ($used_array[4]['item_uselife']<=0) echo '<font color=ff0000><b>СЛОМАНО</b></font>';
										else echo ''.$used_array[4]['item_uselife'].'%';
										$stm = 10;              
										echo '<br />На выстрел '.$stm.' '.(($this->char['STM_MAX']>$this->char['MP_MAX'])?('энергии'):('маны')).'</span></td></tr>';
										echo '<script>spets_luk=\'<table width="100%" cellpadding="0" cellspacing="0">';
										$i=0;
										$radi=0;
										while ($spets=mysql_fetch_array($sele))
										{
											if ($i==0) $radi=$spets['id'];  
											echo '<tr><td style="cursor:url(\\\'/images/nav/hand.cur\\\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=0;chem='.$spets['id'].';document.getElementById(\\\'at_luk'.$spets['id'].'\\\').checked=true;">';
											ImageItem($spets['img'],0,$spets['kleymo'],"left");
											echo '<input id="at_luk'.$spets['id'].'" type="radio" name="radio_luk" value="'.$spets['id'].'" onClick="chem='.$spets['id'].'"'.(($i==0)?' checked="true"':'').'>&nbsp;&nbsp;Атака '.$spets['indx'].'&plusmn;'.$spets['deviation'].'<br />&nbsp;&nbsp;'.$spets['name'].'<br />&nbsp;&nbsp;Количество стрел: '.$spets['count_item'];
											echo'</td></tr>';
											$i++;
										}
										echo '</table>\';radi_luk='.$radi.';</script>';
									}
								}
								$sele=myquery("select game_items.*,game_items_factsheet.type_weapon,game_items_factsheet.type_weapon_need,game_items_factsheet.name,game_items_factsheet.img,game_items_factsheet.mode,game_items_factsheet.indx,game_items_factsheet.deviation from game_items,game_items_factsheet where game_items.user_id=".$this->char['user_id']." and game_items.priznak=0 AND game_items_factsheet.type=19 AND game_items_factsheet.id=game_items.item_id");
								$ar_throw = array();
								if (mysql_num_rows($sele)>0)
								{
									while ($spets=mysql_fetch_array($sele))
									{
										if ($spets['type_weapon']!=0)
										{
											$MS = 0;
											if($spets['type_weapon']==1) {
												$MS = $this->char['MS_KULAK'];
											}
											if($spets['type_weapon']==2) {
												$MS = $this->char['MS_LUK'];
											}
											if($spets['type_weapon']==3) {
												$MS = $this->char['MS_SWORD'];
											}
											if($spets['type_weapon']==4) {
												$MS = $this->char['MS_AXE'];
											}
											if($spets['type_weapon']==5) {
												$MS = $this->char['MS_SPEAR'];
											}
											if($spets['type_weapon']==6) {
												$MS = $this->char['MS_THROW'];
											}
											if ($MS<$spets['type_weapon_need']) continue;
										}
										$ar_throw[]=$spets;
									}
								}
								if (count($ar_throw)>0)
								{ 
									?>
									<tr id="tr_throw" onMouseOver="on_mouse7()" onMouseOut="out_mouse7()" onClick="vkogo=attack_vkogo();chem=0;tak('a6')" style="cursor:url('/images/nav/hand.cur'), pointer;">
									<td height="50"><img id="img_throw" src="http://<?=IMG_DOMAIN;?>/combat/knife.gif" width="50" height="50">
									<?
									echo '</td><td><span class="style2">Бросок метательного оружия';
									$stm = 10;              
									echo '<br />На бросок '.$stm.' '.(($this->char['STM_MAX']>$this->char['MP_MAX'])?('энергии'):('маны')).'</span></td></tr>';
									echo '<script>spets_throw=\'<table width="100%" cellpadding="0" cellspacing="0">';
									$i=0;
									$radi=0;
									foreach ($ar_throw as $key => $spets)
									{
										if ($i==0) $radi=$spets['id'];  
										echo '<tr><td style="cursor:url(\\\'/images/nav/hand.cur\\\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=0;chem='.$spets['id'].';document.getElementById(\\\'at_throw'.$spets['id'].'\\\').checked=true;">';
										ImageItem($spets['img'],0,$spets['kleymo'],"left");
										echo '<input id="at_throw'.$spets['id'].'" type="radio" name="radio_throw" value="'.$spets['id'].'" onClick="chem='.$spets['id'].'"'.(($i==0)?' checked="true"':'').'>&nbsp;&nbsp;Атака '.$spets['indx'].'&plusmn;'.$spets['deviation'].'<br />&nbsp;&nbsp;'.$spets['name'].'<br />&nbsp;&nbsp;Количество оружия: '.$spets['count_item'];
										echo'</td></tr>';
										$i++;
									}
									echo '</table>\';radi_throw='.$radi.';</script>';
								}
								//Свитки усиления и сопротивления
								$sele=myquery("select game_items.*,game_items_factsheet.name,game_items_factsheet.img,game_items_factsheet.mode,game_items_factsheet.indx,game_items_factsheet.deviation from game_items,game_items_factsheet where game_items.user_id=".$this->char['user_id']." and game_items.priznak=0 AND game_items.used>0 AND game_items_factsheet.id IN (".item_id_svitok_light_usil.",".item_id_svitok_medium_usil.",".item_id_svitok_hard_usil.",".item_id_svitok_absolut_usil.",".item_id_svitok_light_sopr.",".item_id_svitok_medium_sopr.",".item_id_svitok_hard_sopr.",".item_id_svitok_absolut_sopr.") AND  game_items_factsheet.id=game_items.item_id");
								$ar_throw = array();
								if (mysql_num_rows($sele)>0)
								{
									while ($spets=mysql_fetch_array($sele))
									{
										if ($spets['item_id']==item_id_svitok_light_usil) $spets['indx']=25;
										if ($spets['item_id']==item_id_svitok_medium_usil) $spets['indx']=50;
										if ($spets['item_id']==item_id_svitok_hard_usil) $spets['indx']=75;
										if ($spets['item_id']==item_id_svitok_absolut_usil) $spets['indx']=100;
										if ($spets['item_id']==item_id_svitok_light_sopr) $spets['indx']=25;
										if ($spets['item_id']==item_id_svitok_medium_sopr) $spets['indx']=50;
										if ($spets['item_id']==item_id_svitok_hard_sopr) $spets['indx']=75;
										if ($spets['item_id']==item_id_svitok_absolut_sopr) $spets['indx']=100;
										$ar_throw[]=$spets;
									}
								}
								if (count($ar_throw)>0)
								{ 
									?>
									<tr id="tr_svitok" onMouseOver="on_mouse8()" onMouseOut="out_mouse8()" onClick="vkogo=attack_vkogo();chem=0;tak('a7')" style="cursor:url('/images/nav/hand.cur'), pointer;">
									<td height="50"><img id="img_throw" src="http://<?=IMG_DOMAIN;?>/combat/svitok.gif" width="50" height="50">
									<?
									echo '</td><td><span class="style2">Использование боевого свитка';
									$stm = 10;              
									echo '<br />Затраты: '.$stm.' '.(($this->char['STM_MAX']>$this->char['MP_MAX'])?('энергии'):('маны')).'</span></td></tr>';
									echo '<script>spets_svitok=\'<table width="100%" cellpadding="0" cellspacing="0">';
									$i=0;
									$radi=0;
									foreach ($ar_throw as $key => $spets)
									{
										if ($i==0) $radi=$spets['id'];  
										echo '<tr style="cursor:url(\\\'/images/nav/hand.cur\\\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)"><td>';
										ImageItem($spets['img'],0,$spets['kleymo'],"left");
										$type_of_svit = 'Увеличение урона';
										if ($spets['item_id']==item_id_svitok_light_sopr OR $spets['item_id']==item_id_svitok_medium_sopr OR $spets['item_id']==item_id_svitok_hard_sopr OR $spets['item_id']==item_id_svitok_absolut_sopr)
										{
											$type_of_svit = 'Уменьшение повреждений';
										}
										echo '</td><td style="padding:5px;">&nbsp;&nbsp;'.$type_of_svit.' на '.$spets['indx'].'%<br />&nbsp;&nbsp;'.$spets['name'].'<br />&nbsp;&nbsp;Количество свитков: '.$spets['count_item'];
										echo'</td><td valign="middle" style="padding:5px;"><input type="button" class="button" onclick="document.getElementById(\\\'proc\\\').value=100;chem='.$spets['id'].';radi='.$spets['id'].';tak(\\\'ex16\\\')" value="Использовать свиток"></td></tr>';
										$i++;
									}
									echo '</table>\';radi_svitok='.$radi.';</script>';
								}
							}
							?>
						</table>
					</td>
					<td valign="top" bgcolor="#888888">
						<table width="100%"  border="0" cellspacing="3" cellpadding="0">
						<?
						if (($this->combat['turnir_type']==0)OR($this->combat['turnir_type']==2)OR($this->combat['turnir_type']==4))
						{
							if (isset($used_array[4]) AND $used_array[4]['type']!=18)
							{
								?>
								<tr id="defense1" onMouseOver="this.style.background='black'" onMouseOut="this.style.background=''" onClick="chem=<?=$used_array[4]['id'];?>;tak('z1')" style="cursor:url('/images/nav/hand.cur'), pointer" valign="middle"><td width="50" height="50" valign="middle"> 
								<?
								ImageItem($used_array[4]['img'],0,$used_array[4]['kleymo']);
								echo'</td><td width="87%"><div align="left" class="style2">'.$used_array[4]['name'].'<br>Защита '.$used_array[4]['indx'].'';
								if ($this->char['MS_PARIR']!=0) echo' <font color=ff0000><b>(+'.($this->char['MS_PARIR']*5).')</b></font>';
								echo'</div></td></tr>';
							}
						}
						if (($this->combat['turnir_type']==0)OR($this->combat['turnir_type']==3)OR($this->combat['turnir_type']==4))
						{
							$sele=myquery("select * from game_spets_item where user_id=".$this->char['user_id']." and img='Защита' ORDER BY indx ASC");
							if (mysql_num_rows($sele)>0)
							{
								?>
								<tr id="tr_defense" onMouseOver="on_mouse3()" onMouseOut="out_mouse3()" onClick="chem=0;tak('z2')" style="cursor:url('/images/nav/hand.cur'), pointer">
								<td height="50" width="50"><img id="img_defense" src="http://<?=IMG_DOMAIN;?>/combat/zashita_13_1.gif" width="50" height="50">
								<?
								echo'</td><td><span class="style2">Защита магией<br>Интеллект '.$this->char['NTL'].'</span></td></tr>';
								echo '<script>spets_def=\'<table cellpadding="0" cellspacing="0">';
								$i=0;
								$radi = 0;
								while ($spets=mysql_fetch_array($sele))
								{
									if ($i==0) $radi=$spets['id'];
									echo '<tr><td style="cursor:url(\\\'/images/nav/hand.cur\\\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=0;chem='.$spets['id'].';document.getElementById(\\\'def_spets'.$spets['id'].'\\\').checked=true;"><input id="def_spets'.$spets['id'].'" type="radio" name="radio" value="'.$spets['id'].'" onClick="radi='.$spets['id'].'"'.(($i==0)?'checked="true"':'').'>'.$spets['name'].' - '.$spets['mode'].' (Защита: '.$spets['indx'].') ';
									if ($spets['mana']!=0) echo '-'.ceil($spets['mana']*$this->decrease).' маны ';
									if ($spets['hp']!=0) echo '-'.ceil($spets['hp']*$this->decrease).' жизней ';
									if ($spets['stm']!=0) echo '-'.ceil($spets['stm']*$this->decrease).' энергии ';
									echo '&nbsp;&nbsp;&nbsp;</td><td>';
									if ($this->count_user()>1)
									{
										echo'<input type="button" class="button" value="100% Защита" onClick=chem='.$spets['id'].';radi='.$spets['id'].';document.getElementById("proc").value=100;tak("ex")></td><td>';
									}
									echo'<input type="button" class="button" value="100% Защита себя" onClick=chem='.$spets['id'].';vkogo=0;radi='.$spets['id'].';document.getElementById("proc").value=100;tak("ex")></td></tr>';
									$i++;
								}
								echo '</table>\';spets_def_radio='.$radi.';</script>';
							}
						}
						if (($this->combat['turnir_type']==0)OR($this->combat['turnir_type']==2)OR($this->combat['turnir_type']==4))
						{
							if (isset($used_array[3]) AND $used_array[3]['sv']=='Защита' and $used_array[3]['count_item']>0)
							{
								?>
								<tr onMouseOver="this.style.background='black'" onMouseOut="this.style.background=''" onClick="chem=<?=$used_array[3]['id'];?>;tak('z3')" style="cursor:url('/images/nav/hand.cur'), pointer">
								<td height="50" width="50">
								<?
								ImageItem($used_array[3]['img'],0,$used_array[3]['kleymo']);
								echo '</td><td><span class="style2">Защита артефактом '.$used_array[3]['name'].'<br>Защита '.$used_array[3]['indx'].'';
								if ($this->char['MS_ART']!='0') echo' <font color=ff0000><b>(+'.$this->char['MS_ART'].')</b></font>';
								echo'<br>Кол-во зарядов: ';
								echo ''.$used_array[3]['count_item'].'';
								echo'</span></td></tr>';
							}
						}
						?>
						</table>
					</td>
					<td valign="top" bgcolor="#888888">
						<table width="100%"  border="0" cellspacing="3" cellpadding="0">
						<?
						if (($this->combat['turnir_type']==0)OR($this->combat['turnir_type']==3)OR($this->combat['turnir_type']==4))
						{
							$sele=myquery("select * from game_spets_item where user_id=".$this->char['user_id']." and img='Лечение' ORDER BY indx ASC");
							if (mysql_num_rows($sele)>0)
							{
								?>
								<tr id="tr_lech" onMouseOver="on_mouse4()" onMouseOut="out_mouse4()" onClick="chem=0;tak('l1')" style="cursor:url('/images/nav/hand.cur'), pointer">
								<td width="13%" height="50"><img id="img_lech" src="http://<?=IMG_DOMAIN;?>/combat/lechenie15_1.gif" width="50" height="50">
								<?
								echo '</td><td width="87%"><span class="style2">Лечение магией<br>Интеллект '.$this->char['NTL'].'</span></td></tr>';
								echo '<script>spets_lech=\'<table cellpadding="0" cellspacing="0">';
								$i=0;
								$radi=0;
								while ($spets=mysql_fetch_array($sele))
								{
									if ($i==0) $radi=$spets['id'];
									echo '<tr><td style="cursor:url(\\\'/images/nav/hand.cur\\\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=0;chem='.$spets['id'].';document.getElementById(\\\'lech_spets'.$spets['id'].'\\\').checked=true;"><input id="lech_spets'.$spets['id'].'" type="radio" name="radio" value="'.$spets['id'].'" onClick="radi='.$spets['id'].';"'.(($i==0)?' checked="true"':'').'>'.$spets['name'].' - '.$spets['mode'].' (';
									if ($spets['indx']!=0) echo '+'.$spets['indx'].'&plusmn;'.$spets['deviation'].' жизни ';
									if ($spets['indx_mp']!=0) echo '+'.$spets['indx_mp'].'&plusmn;'.$spets['indx_mp_deviation'].' маны ';
									if ($spets['indx_stm']!=0) echo '+'.$spets['indx_stm'].'&plusmn;'.$spets['indx_stm_deviation'].' энергии ';
									echo') ';
									if ($spets['mana']!=0) echo '-'.ceil($spets['mana']*$this->decrease).' маны ';
									if ($spets['hp']!=0) echo '-'.ceil($spets['hp']*$this->decrease).' жизней ';
									if ($spets['stm']!=0) echo '-'.ceil($spets['stm']*$this->decrease).' энергии ';
									echo '&nbsp;&nbsp;&nbsp;</td><td>';
									if ($this->count_user()>1)
									{
										echo'<input type="button" class="button" value="100% Лечение" onClick=chem='.$spets['id'].';radi='.$spets['id'].';document.getElementById("proc").value=100;tak("ex")></td><td>';
									}
									echo'<input type="button" class="button" value="100% Лечение себя" onClick=chem='.$spets['id'].';vkogo=0;radi='.$spets['id'].';document.getElementById("proc").value=100;tak("ex")></td></tr>';
									$i++;
								}
								echo '</table>\';spets_lech_radio='.$radi.';</script>';
							}
						}
						if (($this->combat['turnir_type']==0)OR($this->combat['turnir_type']==2)OR($this->combat['turnir_type']==4))
						{
							if (isset($used_array[3]) AND $used_array[3]['sv']=='Лечение' and $used_array[3]['count_item']>0)
							{
								?>
								<tr onMouseOver="this.style.background='black'" onMouseOut="this.style.background=''" onClick="chem=<?=$used_array[3]['id'];?>;tak('l2')" style="cursor:url('/images/nav/hand.cur'), pointer">
								<td height="50">
								<?
								ImageItem($used_array[3]['img'],0,$used_array[3]['kleymo']);
								echo '</td><td><span class="style2">Лечение артефактом '.$used_array[3]['name'].'<br>Исцеляет '.$used_array[3]['indx'].' HP';
								if ($this->char['MS_ART']!=0) echo' <font color=ff0000><b>(+'.$this->char['MS_ART'].')</b></font>';
								echo'<br>Кол-во зарядов: ';
								echo ''.$used_array[3]['count_item'].'';
								echo'</span></td></tr>';
							}
						}
						if ($this->char['STM']>=6 AND $this->char['eliksir']==0 AND $this->combat['map_name']!=map_coliseum)
						{
							$eliksir=myquery("SELECT game_items.id,game_items_factsheet.hp_p,game_items_factsheet.mp_p,game_items_factsheet.stm_p,game_items_factsheet.img,game_items_factsheet.name AS ident,game_items.item_uselife,game_items.kleymo FROM game_items,game_items_factsheet WHERE game_items.item_id=game_items_factsheet.id AND game_items.user_id=".$this->char['user_id']." AND game_items.item_uselife>0 and game_items_factsheet.type=13 AND (game_items_factsheet.hp_p>0 OR game_items_factsheet.mp_p>0 OR game_items_factsheet.stm_p>0) AND game_items.priznak=0 AND game_items.item_id NOT IN (".zelye_glubin_item_id.",".zelye_glubin_medium_item_id.",".zelye_glubin_big_item_id.") AND game_items.used IN (12,13,14)");
							if (mysql_num_rows($eliksir) > 0)
							{
								?>
								<tr id="tr_elik" onMouseOver="on_mouse5()" onMouseOut="out_mouse5()" onClick="chem=0;tak('l3')" style="cursor:url('/images/nav/hand.cur'), pointer;">
								<td width="13%" height="50"><img id="img_elik" src="http://<?=IMG_DOMAIN;?>/combat/elik.gif" width="50" height="50">
								<?
								echo '</td><td width="87%"><span class="style2">Использование эликсира</span></td></tr>';
								echo '<script>spets_elik=\'<table cellpadding="0" cellspacing="0">';
								$lar = array();    
								$i=0;
								$radi=0;
								while ($la=mysql_fetch_array($eliksir))
								{
									if ($la['item_uselife']>0 AND !in_array($la['ident'],$lar))
									{
										echo '<tr style="cursor:url(\\\'/images/nav/hand.cur\\\'), pointer;" onmouseover="bg_black(this)" onmouseout="bg_none(this)"><td>';
										ImageItem($la['img'],0,$la['kleymo'],"middle");
										echo '&nbsp;';
										echo '<b>'.$la['ident'].'</b>&nbsp;&nbsp;&nbsp;';
										if ($la['hp_p']>0)
										{
											echo 'Увеличивает жизни на '.$la['hp_p'].'';
										}
										if ($la['hp_p']<0)
										{
											echo 'Уменьшает жизни на '.(-$la['hp_p']).'';
										}
										if ($la['mp_p']>0)
										{
											echo 'Увеличивает ману на '.$la['mp_p'].'';
										}
										if ($la['mp_p']<0)
										{
											echo 'Уменьшает ману на '.(-$la['mp_p']).'';
										}
										if ($la['stm_p']>0)
										{
											echo 'Увеличивает энергию на '.($la['stm_p']).'';
										}
										if ($la['stm_p']<0)
										{
											echo 'Уменьшает энергию на '.(-$la['stm_p']).'';
										}
										echo'&nbsp;&nbsp;&nbsp;</td><td><input style="height:30px;" type="button" class="button" value="100% Использовать" onClick=chem='.$la['id'].';vkogo=0;document.getElementById("proc").value=100;tak("ex")>&nbsp;&nbsp;&nbsp;</td></tr>'; 
										$lar[] = $la['ident'];
									}
								}
								echo '</table>\';</script>'; 
							}
						}
						?>
						</table>
					</td>
					</tr>
					<tr>
					<td colspan=3 valign=top align=center>
					<?
					if ($this->char['clan_id']!=0 AND $this->count_user()>1)
					{
						if ($this->char['call_clan']==0) 
						{
							?>
							<br /><input id="button_call_clan" type="button" class="button" value="Позвать игроков клана в бой" onclick="call_clan()">
							<?
						}
					}
					echo '
					</td>
					</tr>';

					if ($povtor==1)
					{
						//из повтора хода убираем эликсиры!
						$selhod = myquery("SELECT * FROM combat_actions WHERE user_id=".$this->char['user_id']." AND combat_id=".$this->combat['combat_id']." AND hod=".($this->combat['hod']-1)." AND action_type!=33");
						if (mysql_num_rows($selhod))
						{
							echo'<tr><td colspan=3 valign=top align=center><br><font face="Tahoma,Verdana"><font color=#FF0080>Повтор последнего удара:</font>';
							$otpr='';
							while ($lasthod = mysql_fetch_array($selhod))
							{
								if ($lasthod['action_kogo']==0) $lasthod['action_kogo']=$this->char['user_id'];
								if (!isset($this->all[$lasthod['action_kogo']])) continue;
								if ($lasthod['action_proc']>0)
								{
									if ($lasthod['action_type']>=11 AND $lasthod['action_type']<=14)
									{
										//АТАКА
										$type = "Атака";
										if ($lasthod['action_priem']==2) {$type='Прицельная атака';}
										elseif ($lasthod['action_priem']==3) {$type='Мощная атака';}
										elseif ($lasthod['action_priem']==5) {$type='Пробиваюший удар';};
									
										echo '<br>'.$type.': '.$lasthod['action_proc'].'%';
										if ($this->all[$lasthod['action_kogo']]['npc']==1)
										{
											echo ' по боту ';
										}
										else
										{
											echo ' по игроку ';
										}
										echo '<b>'.$this->all[$lasthod['action_kogo']]['name'].'</b>';
										$kuda='';
										switch ($lasthod['action_kuda'])
										{
											case 1:
											$kuda=' в <font color=ff0000><b>голову</b></font>';
											break;
											case 2:
											$kuda=' в <font color=ff0000><b>тело</b></font>';
											break;
											case 3:
											$kuda=' в <font color=ff0000><b>пах</b></font>';
											break;
											case 4:
											$kuda=' в <font color=ff0000><b>плечо</b></font>';
											break;
											case 5:
											$kuda=' в <font color=ff0000><b>ноги</b></font>';
											break;
										}
										switch ($lasthod['action_type'])
										{
											case 11:
												echo ' кулаком'.$kuda;
											break;

											case 12:
												$the=myquery("SELECT game_items_factsheet.mode FROM game_items_factsheet,game_items WHERE game_items.id='".$lasthod['action_chem']."' AND game_items.item_id=game_items_factsheet.id");
												list($mode)=mysql_fetch_row($the);
												echo ' оружием '.$mode.''.$kuda;
											break;

											case 13:
												$the=myquery("SELECT mode,name FROM game_spets_item WHERE id='".$lasthod['action_chem']."'");
												$weapon=mysql_fetch_array($the);
												echo ' атакующим заклинанием "'.$weapon['mode'].'" ("'.$weapon['name'].'")';
											break;

											case 14:
												$the=myquery("SELECT game_items_factsheet.mode FROM game_items_factsheet,game_items WHERE game_items.id='".$lasthod['action_chem']."' AND game_items.item_id=game_items_factsheet.id");
												list($mode)=mysql_fetch_row($the);
												echo ' артефактом '.$mode.''.$kuda;
											break;
										}

										if ($otpr=='')
											$otpr='a'.substr($lasthod['action_type'],1,1).':'.$lasthod['action_proc'].':'.$lasthod['action_kuda'].':'.$lasthod['action_kogo'].':'.$lasthod['action_chem'].':'.$lasthod['action_priem'].'';
										else
											$otpr.=';a'.substr($lasthod['action_type'],1,1).':'.$lasthod['action_proc'].':'.$lasthod['action_kuda'].':'.$lasthod['action_kogo'].':'.$lasthod['action_chem'].':'.$lasthod['action_priem'].'';
									}
									if ($lasthod['action_type']>=21 AND $lasthod['action_type']<=23)
									{
										//ЗАЩИТА
										$str_type="Защита";
										if ($lasthod['action_priem']==4) $str_type="Круговая защита ";
										echo '<br>'.$str_type.': '.$lasthod['action_proc'].'% на ';
										if ($this->all[$lasthod['action_kogo']]['npc']==1)
										{
											echo " бота ";
										}
										else
										{
											echo ' игрока ';
										}
										echo '<b>'.$this->all[$lasthod['action_kogo']]['name'].'</b>';
										$kuda='';
										if ($lasthod['action_priem']==4) 
										{
											$kuda=' <font color=ff0000><b>всего тела</b></font>';
										}
										else
										{
											switch ($lasthod['action_kuda'])
											{
												case 1:
												$kuda=' <font color=ff0000><b>голову и плечо</b></font>';
												break;
												case 2:
												$kuda=' <font color=ff0000><b>тело и пах</b></font>';
												break;
												case 3:
												$kuda=' <font color=ff0000><b>пах и ноги</b></font>';
												break;
											}
										}
										switch ($lasthod['action_type'])
										{
											case 21:
												$the=myquery("SELECT game_items_factsheet.mode FROM game_items_factsheet,game_items WHERE game_items.id='".$lasthod['action_chem']."' AND game_items.item_id=game_items_factsheet.id");
												list($mode)=mysql_fetch_row($the);
												if ($lasthod['action_priem']==4) 
												{
													echo ' оружием '.$mode.''.$kuda;
												}
												else
												{
													echo ' щитом '.$mode.''.$kuda;
												}
											break;

											case 22:
												$the=myquery("SELECT mode,name FROM game_spets_item WHERE id='".$lasthod['action_chem']."'");
												$weapon=mysql_fetch_array($the);
												echo ' защищающим заклинанием "'.$weapon['mode'].'" ("'.$weapon['name'].'")';
											break;

											case 23:
												$the=myquery("SELECT game_items_factsheet.mode FROM game_items_factsheet,game_items WHERE game_items.id='".$lasthod['action_chem']."' AND game_items.item_id=game_items_factsheet.id");
												list($mode)=mysql_fetch_row($the);
												echo ' артефактом '.$mode.''.$kuda;
											break;
										}
										if ($lasthod['action_priem']==4) 
										{
											if ($otpr=='')
											$otpr='a2:'.$lasthod['action_proc'].':'.$lasthod['action_kuda'].':'.$lasthod['action_kogo'].':'.$lasthod['action_chem'].':'.$lasthod['action_priem'].'';
										else
											$otpr.=';a2:'.$lasthod['action_proc'].':'.$lasthod['action_kuda'].':'.$lasthod['action_kogo'].':'.$lasthod['action_chem'].':'.$lasthod['action_priem'].'';
										}
										else
										{
											if ($otpr=='')
												$otpr='z'.substr($lasthod['action_type'],1,1).':'.$lasthod['action_proc'].':'.$lasthod['action_kuda'].':'.$lasthod['action_kogo'].':'.$lasthod['action_chem'].':0';
											else
												$otpr.=';z'.substr($lasthod['action_type'],1,1).':'.$lasthod['action_proc'].':'.$lasthod['action_kuda'].':'.$lasthod['action_kogo'].':'.$lasthod['action_chem'].':0';
										}
									}
									if ($lasthod['action_type']>=31 AND $lasthod['action_type']<=33)
									{
										//Лечение
										echo '<br>Лечение: '.$lasthod['action_proc'].'% ';
										if ($this->all[$lasthod['action_kogo']]['npc']==1)
										{
											echo " бота ";
										}
										else
										{
											echo ' игрока ';
										}
										echo '<b>'.$this->all[$lasthod['action_kogo']]['name'].'</b>';
										switch ($lasthod['action_type'])
										{
											case 31:
												$the=myquery("SELECT mode,name FROM game_spets_item WHERE id='".$lasthod['action_chem']."'");
												$weapon=mysql_fetch_array($the);
												echo ' лечащим заклинанием "'.$weapon['mode'].'" ("'.$weapon['name'].'")';
											break;

											case 32:
												$the=myquery("SELECT game_items_factsheet.mode FROM game_items_factsheet,game_items WHERE game_items.id='".$lasthod['action_chem']."' AND game_items.item_id=game_items_factsheet.id");
												list($mode)=mysql_fetch_row($the);
												echo ' артефактом '.$mode.'';
											break;
										}
										if ($otpr=='')
											$otpr='l'.substr($lasthod['action_type'],1,1).':'.$lasthod['action_proc'].':'.$lasthod['action_kuda'].':'.$lasthod['action_kogo'].':'.$lasthod['action_chem'].':0';
										else
											$otpr.=';l'.substr($lasthod['action_type'],1,1).':'.$lasthod['action_proc'].':'.$lasthod['action_kuda'].':'.$lasthod['action_kogo'].':'.$lasthod['action_chem'].':0';
									}
								}
							}
							echo '</font><br>
							<form name="forma1">
							<input type="button" class="button" name="lasthod" value="Повторить мой последний ход" onClick="SendFormLastHod(\''.$otpr.'\')">
							</form>
							</td></tr>';
						}
					}
?>
			</table>
			</span>
			<span id="pisat" style="display:none">
			<input id="proc" name="proc" type="text" size="5" value="0" maxlength="3">
			</span>
			<?
			$this->show_log();
			?>
		</td>
		<td style="width:200px;" valign="top" bgcolor="#000000">
		<? 
		$this->print_right();
		?>
		</td>
	</tr>
</table>

