<?php
class anti_mate {
	//latin equivalents for russian letters
	var $let_matches = array (
	"a" => "а",
	"c" => "с",
	"e" => "е",
	"k" => "к",
	"m" => "м",
	"o" => "о",
	"x" => "х",
	"y" => "у",
	"Є" => "е");
	//bad words array. Regexp's symbols are readable !
	var $bad_words = array (".*ху(й|и|€|е|л(и|е)).*", ".*пи(з|с)д.*", "бл€(*|.)", "бл€(д|т|ц).*", "(с|сц)ук(а|о|и).*", "еб.*", ".*уеб.*", "заеб.*", ".*еб(а|и)(н|с|щ|ц|т).*", ".*ебу(ч|щ).*", ".*пид(о|е)р.*", ".*хер.*", "г(а|о)ндон", ".*залуп.*",".*занах.*",".*затрах.*",".*долбоеб.*","бл€",".*мудак.*", ".*ERRNO.*");

	function rand_replace (){
		$output = " [color=red][b][censored][/b][/color] ";
		return $output;
	}
	function filter ($string){
		$counter = 0;
		@setlocale(LC_ALL, array ('ru_RU.CP1251', 'rus_RUS.1251'));

		//$pattern = "/\w{0,5}[хx]([хx\s\!@#\$%\^&*+-\|\/]{0,6})[уy]([уy\s\!@#\$%\^&*+-\|\/]{0,6})[Єiлeеюий€]\w{0,7}|\w{0,6}[пp]([пp\s\!@#\$%\^&*+-\|\/]{0,6})[iие]([iие\s\!@#\$%\^&*+-\|\/]{0,6})[3зс]([3зс\s\!@#\$%\^&*+-\|\/]{0,6})[дd]\w{0,10}|[сcs][уy]([уy\!@#\$%\^&*+-\|\/]{0,6})[4чkк]\w{1,3}|\w{0,4}[bб]([bб\s\!@#\$%\^&*+-\|\/]{0,6})[lл]([lл\s\!@#\$%\^&*+-\|\/]{0,6})[y€]\w{0,10}|\w{0,8}[еЄ][bб][лске@eыиаa][наи@йвл]\w{0,8}|\w{0,4}[еe]([еe\s\!@#\$%\^&*+-\|\/]{0,6})[бb]([бb\s\!@#\$%\^&*+-\|\/]{0,6})[uу]([uу\s\!@#\$%\^&*+-\|\/]{0,6})[н4ч]\w{0,4}|\w{0,4}[еeЄ]([еeЄ\s\!@#\$%\^&*+-\|\/]{0,6})[бb]([бb\s\!@#\$%\^&*+-\|\/]{0,6})[нn]([нn\s\!@#\$%\^&*+-\|\/]{0,6})[уy]\w{0,4}|\w{0,4}[еe]([еe\s\!@#\$%\^&*+-\|\/]{0,6})[бb]([бb\s\!@#\$%\^&*+-\|\/]{0,6})[оoаa@]([оoаa@\s\!@#\$%\^&*+-\|\/]{0,6})[тnнt]\w{0,4}|\w{0,10}[Є]([Є\!@#\$%\^&*+-\|\/]{0,6})[б]\w{0,6}|\w{0,4}[pп]([pп\s\!@#\$%\^&*+-\|\/]{0,6})[иeеi]([иeеi\s\!@#\$%\^&*+-\|\/]{0,6})[дd]([дd\s\!@#\$%\^&*+-\|\/]{0,6})[oоаa@еeиi]([oоаa@еeиi\s\!@#\$%\^&*+-\|\/]{0,6})[рr]\w{0,12}/i";

		$replacement = " [color=red][b][censored][/b][/color] ";

		//$string = preg_replace($pattern, $replacement, $string);   
		
		//return $string;
		 
		$elems = explode (" ", $string); //here we explode string to words
		$count_elems = count($elems);
		for ($i=0; $i<$count_elems; $i++)
		{
		$blocked = 0;
		/*formating word...*/
		$str_rep = preg_replace ("/[^a-zA-Zа-€ј-яЄ]/", "", strtolower($elems[$i]));
			for ($j=0; $j<strlen($str_rep); $j++)
			{
				foreach ($this->let_matches as $key => $value)
				{
					if ($str_rep[$j] == $key)
					$str_rep[$j] = $value;

				}
			}
		/*done*/

		/*here we are trying to find bad word*/
		/*match in the special array*/
			for ($k=0; $k<count($this->bad_words); $k++)
			{
				if (preg_match("/\*$/", $this->bad_words[$k]))
				{
					if (preg_match("/^".$this->bad_words[$k]."/", $str_rep))
					{
						$elems[$i] = $this->rand_replace();
						$blocked = 1;
						$counter++;
						break;
					}
				
				}
				if ($str_rep == $this->bad_words[$k])
				{
					$elems[$i] = $this->rand_replace();
					$blocked = 1;
					$counter++;
					break;
				}

			}
		}
		if ($counter != 0)
		$string = implode (" ", $elems); //here we implode words in the whole string
		return $string;
	}
}
?>
