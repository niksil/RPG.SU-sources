var otprav='';
var attack='';
var zash='';
var lech='';
var radi=1;

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
	img.src = "/images/combat/kulak_11_2.gif";
}
function out_mouse1()
{
	tr = document.getElementById("tr_attackkulak");
	bg_none(tr);
	img = document.getElementById("img_attackkulak");
	img.src = "/images/combat/kulak_11_1.gif";
}
function on_mouse2()
{
	tr = document.getElementById("tr_attack");
	tr.style.background='black';
	img = document.getElementById("img_attack");
	img.src = "/images/combat/udar18_2.gif";
}
function out_mouse2()
{
	tr = document.getElementById("tr_attack");
	tr.style.background='';
	img = document.getElementById("img_attack");
	img.src = "/images/combat/udar18_1.gif";
}
function on_mouse3()
{
	tr = document.getElementById("tr_defense");
	tr.style.background='black';
	img = document.getElementById("img_defense");
	img.src = "/images/combat/zashita_13_2.gif";
}
function out_mouse3()
{
	tr = document.getElementById("tr_defense");
	tr.style.background='';
	img = document.getElementById("img_defense");
	img.src = "/images/combat/zashita_13_1.gif";
}
function on_mouse4()
{
	tr = document.getElementById("tr_lech");
	tr.style.background='black';
	img = document.getElementById("img_lech");
	img.src = "/images/combat/lechenie15_2.gif";
}
function out_mouse4()
{
	tr = document.getElementById("tr_lech");
	tr.style.background='';
	img = document.getElementById("img_lech");
	img.src = "/images/combat/lechenie15_1.gif";
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

			case 'ex':
			re=new RegExp('^[0-9]+$');
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
				if (procEl.value>0)
				{
					attack=at_type;
					if (attack=='') attack=1;
					knopkEl.style.display='block';
					if (otprav=='')
						otprav=old1+':'+procEl.value+':'+radi+':'+vkogo+':'+chem+':'+attack;
					else
						otprav=otprav+';'+old1+':'+procEl.value+':'+radi+':'+vkogo+':'+chem+':'+attack;

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
					else
						switch(radi)
						{
							case 1:
							kuda='голова и плечо';
							break;
							case 2:
							kuda='тело и пах';
							break;
							case 3:
							kuda='пах и ноги';
							break;
						}
					if ((old1.indexOf("a3")!=-1)||(old1.indexOf("z2")!=-1)||(old1.indexOf("l1")!=-1)||(old1.indexOf("l3")!=-1))
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
							if (attack==4) {nazvan = 'Глухая защита оружием'}
						}
						vybrEl.innerHTML=vybrEl.innerHTML+nazvan+' ('+procEl.value+'% - '+kuda+')<br>';
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
		
		text='<table width="100%" height="100%"  border="1" cellpadding="0" cellspacing="1">';
		text=text+'<tr>';
		text=text+'<th height="10" bgcolor="#666666">'+nazv+'</td>';
		text=text+'</tr>';
		text=text+'<tr>';
		text=text+'<th height="237" valign="top" bgcolor="#888888" scope="col"><table width="100%"  border="0" cellspacing="3" cellpadding="0">';
		text=text+'<tr>';
		text=text+'<td width="18%"><div align="left" class="style2">'+nazvan+'</div></td>';
		text=text+'<td width="32%">';
		if (what.indexOf("a")!=-1)
		   text=text+'Ударить с силой ';
		else
		if (what.indexOf("z")!=-1)
		   text=text+'Защита ';
		else
		if (what.indexOf("l")!=-1)
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
				nazv = 'Глухая защита';
			}
			else
			{
				if (what.indexOf("l3")!=-1)
				{
					text=text+'<input id="proc" name="proc" type="text" value="100" readonly="true" size="5" maxlength="3">%';
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
		if (what.indexOf("l3")==-1)
		{
			text=text+'<input type="button" onClick=tak("ex") value="'+nazv+'">';
		}
		if (what.indexOf("z")!=-1)
		{
			text=text+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onClick=vkogo=0;tak("ex") value="Защищать себя">';
		}
		if (what.indexOf("l")!=-1)
		{
			text=text+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onClick=vkogo=0;tak("ex") value="Лечить себя">';
		}        
		text=text+'</td></tr>';
		text=text+'<tr>';
		text=text+'<td colspan=4>';
		if ((what.indexOf("a3")!=-1)||(what.indexOf("z2")!=-1)||(what.indexOf("l1")!=-1))
			text=text+'<table><tr><td>';
		if ((what.indexOf("a")!=-1)&&(what.indexOf("a3")==-1)&&(what.indexOf("z2")==-1)&&(what.indexOf("l1")==-1)&&(what.indexOf("l3")==-1))
		{
			radi=1;
			text=text+'<table width="100%"><tr><td><table><tr><td style="width:150px;cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=1;document.getElementById(\'attack_kuda1\').checked=true;"><input id="attack_kuda1" name="radio" type="radio" value="1" checked="true" onClick="radi=1"> В голову </td><td><input type="button" onClick=tak("ex1") value="'+nazv+' 100%"></td></tr><tr><td style="cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=2;document.getElementById(\'attack_kuda2\').checked=true;"><input id="attack_kuda2" name="radio" type="radio" value="2" onClick="radi=2"> В тело </td><td><input type="button" onClick=tak("ex2") value="'+nazv+' 100%"></td></tr><tr><td style="cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=3;document.getElementById(\'attack_kuda3\').checked=true;"><input id="attack_kuda3" name="radio" type="radio" value="3" onClick="radi=3"> В пах </td><td><input type="button" onClick=tak("ex3") value="'+nazv+' 100%"></td></tr><tr><td style="cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=4;document.getElementById(\'attack_kuda4\').checked=true;"><input id="attack_kuda4" name="radio" type="radio" value="4" onClick="radi=4"> В плечо </td><td><input type="button" onClick=tak("ex4") value="'+nazv+' 100%"></td></tr><tr><td style="cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=5;document.getElementById(\'attack_kuda5\').checked=true;"><input id="attack_kuda5" name="radio" type="radio" value="5" onClick="radi=5"> В ноги </td><td><input type="button" onClick=tak("ex5") value="'+nazv+' 100%"></td></tr></table></td>';
			if (what=='a1')
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
			if (what=='a2')
			{
				text=text+'<td width="50%"><div style="width:100%;height:20px;cursor:url(\'/images/nav/hand.cur\'), pointer;" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="document.getElementById(\'attack_type\').checked=true;at_type=1;">            <input type="radio" name="attack_type" id="attack_type" value="1" checked onClick="at_type=1">Обычная атака</div>';
				if(MS_WEAPON>=3)
				{
					text=text+'<div style="width:100%;height:20px;cursor:url(\'/images/nav/hand.cur\'), pointer;" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="document.getElementById(\'attack_type2\').checked=true;at_type=2;">            <input type="radio" name="attack_type" id="attack_type2" value="2" onClick="at_type=2">Прицельная атака (+25% точн., -20% повр.)</div>';
				}
				if(MS_WEAPON>=6)
				{
					text=text+'<div style="width:100%;height:20px;cursor:url(\'/images/nav/hand.cur\'), pointer;" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="document.getElementById(\'attack_type3\').checked=true;at_type=3;">            <input type="radio" name="attack_type" id="attack_type3" value="3" onClick="at_type=3">Мощная атака (-20% точн., +25% повр.)</div>';
				}
				text=text+'<div style="width:100%;height:20px;cursor:url(\'/images/nav/hand.cur\'), pointer;" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="document.getElementById(\'attack_type4\').checked=true;at_type=4;">            <input type="radio" name="attack_type" id="attack_type4" value="4" onClick="at_type=4">Глухая защита оружием (+урон к защите)</div></td>';
			}
			text=text+'</tr></table>';
		}
		if ((what.indexOf("z")!=-1)&&(what.indexOf("z2")==-1))
		{
			radi=1;
			text=text+'<table><tr><td style="width:150px;cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=1;document.getElementById(\'attack_kuda1\').checked=true;"><input name="radio" id="attack_kuda1" type="radio" value="1" checked="true" onClick="radi=1"> голову и плечо </td><td><input type="button" onClick=tak("ex1") value="'+nazv+' 100%"></td><td><input type="button" onClick=vkogo=0;tak("ex1") value="'+nazv+' себя 100%"></td></tr><tr><td style="cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=2;document.getElementById(\'attack_kuda2\').checked=true;"><input id="attack_kuda2" name="radio" type="radio" value="2" onClick="radi=2"> тело и пах </td><td><input type="button" onClick=tak("ex2") value="'+nazv+' 100%"></td><td><input type="button" onClick=vkogo=0;tak("ex2") value="'+nazv+' себя 100%"></td></tr><tr><td style="cursor:url(\'/images/nav/hand.cur\'), pointer" onmouseover="bg_black(this)" onmouseout="bg_none(this)" onclick="radi=3;document.getElementById(\'attack_kuda3\').checked=true;"><input id="attack_kuda3" name="radio" type="radio" value="3" onClick="radi=3"> пах и ноги </td><td><input type="button" onClick=tak("ex3") value="'+nazv+' 100%"></td><td><input type="button" onClick=vkogo=0;tak("ex3") value="'+nazv+' себя 100%"></td></tr></table>';
		}
		if (what.indexOf("a3")!=-1)
		{
			radi=spets_attack_radio;
			text=text+'</td><td>'+spets_attack+'</td></table>';
		}
		if (what.indexOf("z2")!=-1)
		{
			radi=spets_def_radio;
			text=text+'</td><td>'+spets_def+'</td></table>';
		}
		if (what.indexOf("l1")!=-1)
		{
			radi=spets_lech_radio;
			text=text+'</td><td>'+spets_lech+'</td></table>';
		}
		text=text+'</tr></table></td></tr></table>';
		pisatEl.innerHTML=text;
		old1=what;
	}
}