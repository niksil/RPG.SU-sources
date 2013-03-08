<?php
$id_item_ring_of_fire = 213;//'Кольцо огня' в энциклопедии
$id_item_monstr_balden = 376;//Монстрячья балдень
$id_item_part_monster = 377;//Кусок монстра
$id_item_letter = 379;//письмо
$id_item_posylka = 381;//посылка
$id_item_letter_complete = 380;//Письмо с подтверждением

function kill_quest_npc($npc_id)
{
	$npcid=(int)$npc_id;
	myquery("DELETE FROM game_npc WHERE id=$npcid");
}
?>