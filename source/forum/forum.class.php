<?php
class Forum
{
	public $guest;
	public $admin;
	public $forum_admin;
	public $char;
	private $action;
	private $user_rights;
	public $setup;
	public $barier_id;
	
	//***************************************************************************************
	//****    ОПРЕДЕЛЕНИЕ ПРАВ
	//***************************************************************************************
	function detectRights()//определение общих прав доступа к клановым и админским разделам
	{
		$this->user_rights = array('category',);
		if ($this->char['clan_id']==0)
		{
			if ($this->admin)
			{
				//страж без клана
				$this->user_rights['category'] = 3;
			}
			else
			{
				//игрок без клана, гость
				$this->user_rights['category'] = 0;
			}
		}
		else
		{
			if ($this->char['clan_id']==1)
			{
				//админ
				$this->user_rights['category'] = 4;
			}
			else
			{
				if ($this->admin)
				{
					//страж в клане
					$this->user_rights['category'] = 2;
				}
				else
				{   
					//игрок в клане
					$this->user_rights['category'] = 1;
				}
			}
		}
	}
	
	function MakePermissionForCategory()
	{
		$str_where = '';
		switch ($this->user_rights['category'])
		{
			case 0: {$str_where= "and (forum_main.level='' or forum_main.level='priv') and forum_kat.clan=0 ";} break; //гость или игрок без клана
			case 1: {$str_where= "and forum_main.level!='adm' and (forum_kat.clan=0 OR forum_kat.clan=".$this->char['clan_id'].")"; } break; //игрок в клане, не страж, не админ
			case 2: {$str_where= "and (forum_kat.clan=0 OR (forum_kat.clan=1 and forum_main.level='adm') OR forum_kat.clan=".$this->char['clan_id'].")";} break; //игрок в клане, страж, не админ
			case 3: {$str_where= "and forum_main.level!='clan' and (forum_kat.clan=0 OR forum_kat.clan=1)";} break; //страж без клана
			case 4: {$str_where= "";} break; //админ
		}  
		return $str_where;
	}

	function MakePermissionForTopic()
	{
		$str_where = $this->MakePermissionForCategory();
		switch ($this->user_rights['category'])
		{
			case 0: {$str_where.= " and ((forum_topics.user_id=".$this->char['user_id']." AND forum_main.level='priv') OR forum_main.level!='priv') ";} break; //гость или игрок без клана
			case 1: {$str_where.= " and ((forum_topics.user_id=".$this->char['user_id']." AND forum_main.level='priv') OR forum_main.level!='priv') ";} break; //игрок в клане, не страж, не админ
			case 2: {$str_where.= " ";} break;  //игрок в клане, страж, не админ
			case 3: {$str_where.= " ";} break; //страж без клана
			case 4: {$str_where.= " ";} break; // админ - полное наследование категорийных параметров
		}  
		return $str_where;
	}
	
	function CheckCategoryRights($kat_id)
	{
		$kat_id = (int)$kat_id;
		$kat_rights = array();
		$kat_rights['view']     = false;
		$kat_rights['new']      = false;
		
		if ($kat_id==0) return $kat_rights;
		
		$kat = mysql_fetch_array(myquery("SELECT forum_main.level,forum_kat.clan FROM forum_kat,forum_main WHERE forum_kat.id=$kat_id AND forum_main.id=forum_kat.main_id"));
		switch ($this->user_rights['category'])
		{
			case 0: 
			{
				if (($kat['level']=='' or $kat['level']=='priv') and $kat['clan']==0)
				{
					$kat_rights['view'] = true; 
					$kat_rights['new'] = true;
				}
			} 
			break; //гость или игрок без клана
			
			case 1: 
			{
				if ($kat['level']!='adm' and ($kat['clan']==0 OR $kat['clan']==$this->char['clan_id']))
				{
					$kat_rights['view'] = true; 
					$kat_rights['new'] = true;
				}
			} 
			break; //игрок в клане, не страж, не админ
			
			case 2: 
			{
				if ($kat['clan']==0 OR ($kat['clan']==1 and $kat['level']=='adm') OR $kat['clan']==$this->char['clan_id'])
				{
					$kat_rights['view'] = true;
					$kat_rights['new'] = true; 
				}
			} 
			break; //игрок в клане, страж, не админ
			
			case 3: 
			{
				if ($kat['level']!='clan' and ($kat['clan']==0 OR $kat['clan']==1))
				{
					$kat_rights['view'] = true;
					$kat_rights['new'] = true; 
				}
			} 
			break; //страж без клана
			
			case 4: 
			{
				$kat_rights['view'] = true;
				$kat_rights['new'] = true;
			}
			break; //админ
		}
		
		if ($this->guest)
		{
			$kat_rights['new'] = false;
		}
		  
		return $kat_rights;
	}
	
	function CheckReplyRights($reply_id) //проверка прав на ответы (операции с forum_otv)
	{
		//todo проверку делать с учетом прав на топик, категорию и раздел
		$reply_rights = array();
		$reply_rights['edit']       = false;
		$reply_rights['delete']     = false;
		$reply_rights['fulldelete'] = false;
		$reply_rights['thanks'] = false;
		
		if ($this->guest)
		{
			return $reply_rights;
		}
		else
		{
			$reply_user_id = mysqlresult(myquery("SELECT user_id FROM forum_otv WHERE id=$reply_id"),0,0);
			if ($reply_user_id!=$this->char['user_id'])
			{
				$sel = mysqlresult(myquery("SELECT COUNT(*) FROM forum_thanks WHERE post_id=$reply_id AND user_id=".$this->char['user_id'].""),0,0);
				if ($sel==0)
				{
					$reply_rights['thanks'] = true;   
				}
			}
		}
		
		$reply_id = (int)$reply_id;
		if ($this->user_rights['category']==4)
		{
			$reply_rights['edit']       = true;
			$reply_rights['delete']     = true;
			$reply_rights['fulldelete'] = true;
		}
		else
		{
			$str_where = $this->MakePermissionForTopic();
			$sel = myquery("
			SELECT 
			forum_otv.user_id AS reply_user_id,
			forum_topics.kat_id AS kat_id
			FROM forum_kat,forum_otv,forum_topics,forum_main 
			WHERE 
			forum_topics.kat_id=forum_kat.id AND 
			forum_otv.topics_id=forum_topics.id AND 
			forum_otv.id=$reply_id AND 
			forum_main.id=forum_kat.main_id AND
			forum_topics.stat='open'  
			$str_where"); 
			
			if ($sel!=false AND mysql_num_rows($sel)>0)
			{
				$reply = mysql_fetch_array($sel);
				if ($this->forum_admin)
				{
			// Пользователь является администратором форума
					$reply_rights['edit']       = true;
					$reply_rights['delete']     = true;
					$reply_rights['fulldelete'] = true;
				}
				else
				{
					if ($reply['reply_user_id']==$this->char['user_id'])
					{
				// Пользователь является владельцом данного комментария
						$reply_rights['edit']       = true;
						$reply_rights['delete']     = true;
					}
					else
					{
						$moder = mysql_result(myquery("SELECT COUNT(*) FROM forum_kat_moder WHERE user_id=".$this->char['user_id']." AND kat_id=".$reply['kat_id'].""),0,0);
						if ($moder>0)
						{
				// Пользователь является модератором
							$reply_rights['edit']       = true;
							$reply_rights['delete']     = true;
						}
					}
				}
			}
		}
		
		return $reply_rights;
	}
	
	function CheckTopicRights($topic_id) //проверка прав на весь топик (операции с forum_topics)
	{
		//todo проверку делать с учетом категории и раздела к которому принадлежит топик
		$topic_id = (int)$topic_id;

		$topic_rights = array();
		$topic_rights['edit']       = false;
		$topic_rights['delete']     = false;
		$topic_rights['hide']       = false;
		$topic_rights['move']       = false;
		$topic_rights['pin']        = false;
		$topic_rights['openclose']  = false; 
		$topic_rights['view']       = false; 
		$topic_rights['reply']      = false;
		$topic_rights['showdelete'] = false;
		$topic_rights['thanks']     = false; 
		
		if (!$this->guest)
		{
			$topic_user_id = mysqlresult(myquery("SELECT user_id FROM forum_topics WHERE id=$topic_id"),0,0);
			if ($topic_user_id!=$this->char['user_id'])
			{
				$sel = mysqlresult(myquery("SELECT COUNT(*) FROM forum_thanks WHERE topic_id=$topic_id AND user_id=".$this->char['user_id'].""),0,0);
				if ($sel==0)
				{
					$topic_rights['thanks'] = true;   
				}
			}
		}
		
		if ($this->user_rights['category']==4)
		{
			$topic_rights['edit']       = true;
			$topic_rights['delete']     = true;
			$topic_rights['hide']       = true;
			$topic_rights['move']       = true;
			$topic_rights['pin']        = true;
			$topic_rights['openclose']  = true; 
			$topic_rights['view']       = true; 
			$topic_rights['reply']      = true;
			$topic_rights['showdelete'] = true;
		}
		elseif ($this->guest)
		{
			$topic_rights['view']       = true; 
		}
		else
		{
			$str_where = $this->MakePermissionForTopic();
			$selreply = myquery("SELECT 
			forum_topics.kat_id AS kat_id,forum_topics.user_id AS user_id,forum_topics.to_user_id AS to_user_id,forum_main.level as level,forum_topics.stat 
			FROM forum_topics,forum_kat,forum_main 
			WHERE 
			forum_topics.id=$topic_id AND 
			forum_topics.kat_id=forum_kat.id AND 
			forum_main.id=forum_kat.main_id   
			$str_where"); 
			if (mysql_num_rows($selreply)>0)
			{
			// Если у пользователя есть доступ в данную категорию, то идем дальше
				$reply = mysql_fetch_array($selreply);
				$moder = mysql_result(myquery("SELECT COUNT(*) FROM forum_kat_moder WHERE user_id=".$this->char['user_id']." AND kat_id=".$reply['kat_id'].""),0,0);
				if ($moder>0 OR $this->forum_admin)
				{
			// Если пользователь админ форума, либо же модератор данной категории
					$topic_rights['edit']       = true;
					$topic_rights['delete']     = true;
					$topic_rights['hide']       = true;
					$topic_rights['move']       = true;
					$topic_rights['pin']        = true;
					$topic_rights['openclose']  = true;
					$topic_rights['showdelete'] = true;
				}
				elseif ($reply['user_id']==$this->char['user_id'])
				{
			// Пользователем является топик-стартером
					$topic_rights['edit']       = true;
					$topic_rights['openclose']  = true;
				}
				if ($reply['stat']=='open' AND !$this->guest)
				{
			// Топик открыт и пользователь не гость
					$topic_rights['reply']      = true;
				}
				$userban=mysql_result(myquery("select count(*) from game_ban where user_id='".$this->char['user_id']."' and type=2 and time>'".time()."'"),0,0);
				if ($userban>0)
				{
			// Пользователь забанен - у него нет возможности отвечать
					$topic_rights['reply']      = false;
				}
			// Просмотр всем. если только не в спец. категории
				if ($reply['user_id']==$this->char['user_id'] AND $reply['level']=='priv')
				{
			// Пользователем является топик-стартером + это спец. категория
					$topic_rights['view']      = true;
				}
				elseif ($reply['level']!='priv')
				{
					$topic_rights['view']      = true;
				}
				if ($reply['to_user_id']>0 AND $topic_rights['reply']==true)
				{
					if ($this->char['user_id']!=$reply['to_user_id'] AND $this->char['user_id']!=$reply['user_id'])
					{
						$topic_rights['reply']      = false;
					} 
				}
			}
		}
		
		return $topic_rights;
	}
	
	function CheckPollRights($topic_id) //проверка прав на голосование
	{
		$topic_id = (int)$topic_id;
		
		$poll_rights = array();
		
		$poll_rights['readpoll']    = true;
		$poll_rights['replypoll']   = false;
		$poll_rights['closepoll']   = false;
		$poll_rights['editpoll']    = false;
		$poll_rights['openpoll']    = false;
		
		if ($this->guest)
		{
			return $poll_rights;
		}
	   
		$selpoll = myquery("SELECT status FROM forum_poll WHERE topic_id=$topic_id");
		if (!mysql_num_rows($selpoll))
		{
			return $poll_rights;
		}
	   
		if ($this->user_rights['category']==4)
		{
			$poll_rights['readpoll']    = true;
			$poll_rights['replypoll']   = true;
			$poll_rights['closepoll']   = true;
			$poll_rights['editpoll']    = true;
			$poll_rights['openpoll']    = true;
		}
		else
		{
			$str_where = $this->MakePermissionForTopic();
			$sel_reply = myquery("SELECT 
			forum_topics.kat_id AS kat_id,forum_topics.id AS topic_id,forum_topics.user_id AS user_id,forum_topics.to_user_id AS to_user_id,forum_topics.stat 
			FROM forum_topics,forum_main,forum_kat 
			WHERE 
			forum_topics.id=$topic_id AND 
			forum_topics.kat_id=forum_kat.id AND 
			forum_main.id=forum_kat.main_id 
			$str_where"); 
			if (mysql_num_rows($sel_reply)>0)
			{
				$reply = mysql_fetch_array($sel_reply);
				$moder = mysql_result(myquery("SELECT COUNT(*) FROM forum_kat_moder WHERE user_id=".$this->char['user_id']." AND kat_id=".$reply['kat_id'].""),0,0);
				list($stat_poll) = mysql_fetch_array($selpoll);
				$poll_rights['readpoll']    = true;
				if ($stat_poll==1 AND $reply['stat']=='open')
				{
					//если голосование открыто и открыт топик
					$poll_rights['replypoll']   = true;
				}
				if ($moder>0 OR $this->forum_admin OR $reply['user_id']==$this->char['user_id'])
				{
					if ($stat_poll==1)
					{
						$poll_rights['closepoll'] = true;
						$poll_rights['editpoll']  = true;
					}
					else
					{
						$poll_rights['openpoll']  = true;
					}
				}
				$userban=mysql_result(myquery("select count(*) from game_ban where user_id=".$this->char['user_id']." and type=2 and time>".time().""),0,0);
				if ($userban>0)
				{
					$poll_rights['readpoll']    = false;
					$poll_rights['replypoll']   = false;
					$poll_rights['closepoll']   = false;
					$poll_rights['editpoll']    = false;
					$poll_rights['openpoll']    = false;
				}
				if ($reply['to_user_id']>0 AND $poll_rights['replypoll']==true)
				{
					if ($reply['to_user_id']!=$this->char['user_id'])
					{
						$poll_rights['replypoll']      = false;
					} 
				}
			}
		}
		
		return $poll_rights;
	}
	
	//***************************************************************************************
	//****    СЛУЖЕБНЫЕ ФУНКЦИИ
	//***************************************************************************************
	function MakeRequestURL($str)
	{
		$req = '';
		if (strpos($_SERVER["REQUEST_URI"],"?".$str)===FALSE AND strpos($_SERVER["REQUEST_URI"],"&".$str)===FALSE)
		{
			$req.='?';
			if ($_SERVER["QUERY_STRING"]!='')
			{
				$req.=$_SERVER["QUERY_STRING"]."&";
			}
			$req.=$str;
		}
		else
		{
			$req.='?'.$_SERVER["QUERY_STRING"];
		}
		return $req;
	}
	
	function Del($id,$par) //Тело для "удаления" ответа или топика
	{
		//определяем что надо сделать - удалить топик, удалить/восстановить ответ
		//и вызываем соответствующий метод
		if ($par==1)
		{
			//работаем с топиком
			//топик можно только удалить
			//для восстановления топика можно воспользоваться перемещением темы из раздела Удаленных тем форума
			$this->DeleteTopic($id);
		}
		else
		{
			list($cur_del) = mysql_fetch_array(myquery("SELECT del FROM forum_otv WHERE id=$id"));
			$cur_del = (int)$cur_del;
			if ($cur_del==0)
			{
				$this->DeleteReply($id);
			}
			else
			{
				if ($par==2)
				{
					$this->DeleteEndReply($id);
				}
				else
				{
					$this->RestoreReply($id);
				}
			}
		}
	}
	
    function convert_bbcode_tags($text, $post_id = -1) //перевод BBCode в теги для отображения на экране
	{
        if ($post_id == -1)
          $post_id = mt_rand(0,999999);
		$preg = array(
			'/(?<!\\\\)\[color(?::\w+)?=(.*?)\](.*?)\[\/color(?::\w+)?\]/si'   => "<span style=\"color:\\1\">\\2</span>",
			'/(?<!\\\\)\[size(?::\w+)?=(.*?)\](.*?)\[\/size(?::\w+)?\]/si'     => "<span style=\"font-size:\\1\">\\2</span>",
			'/(?<!\\\\)\[font(?::\w+)?=(.*?)\](.*?)\[\/font(?::\w+)?\]/si'     => "<span style=\"font-family:\\1\">\\2</span>",
			'/(?<!\\\\)\[align(?::\w+)?=(.*?)\](.*?)\[\/align(?::\w+)?\]/si'   => "<div style=\"text-align:\\1\">\\2</div>",
			'/(?<!\\\\)\[b(?::\w+)?\](.*?)\[\/b(?::\w+)?\]/si'                 => "<span style=\"font-weight:bold\">\\1</span>",
			'/(?<!\\\\)\[i(?::\w+)?\](.*?)\[\/i(?::\w+)?\]/si'                 => "<span style=\"font-style:italic\">\\1</span>",
			'/(?<!\\\\)\[u(?::\w+)?\](.*?)\[\/u(?::\w+)?\]/si'                 => "<span style=\"text-decoration:underline\">\\1</span>",
            '/(?<!\\\\)\[s(?::\w+)?\](.*?)\[\/s(?::\w+)?\]/si'                 => "<span style=\"text-decoration:line-through\">\\1</span>",

            '/(?<!\\\\)\[hr(?::\w+)?\](.*?)/si'                                => "<hr/>\\1",
            '/(?<!\\\\)\[br(?::\w+)?\](.*?)/si'                                => "<br/>\\1",

            '/(?<!\\\\)\[sub(?::\w+)?\](.*?)\[\/sub(?::\w+)?\]/si'             => "<sub>\\1</sub>",
            '/(?<!\\\\)\[sup(?::\w+)?\](.*?)\[\/sup(?::\w+)?\]/si'             => "<sup>\\1</sup>",
            
			// [code]
			'/(?<!\\\\)\[code(?::\w+)?\](.*?)\[\/code(?::\w+)?\]/si'           => "<div class=\"bb-code\">\\1</div>",
			// [email]
			'/(?<!\\\\)\[email(?::\w+)?\](.*?)\[\/email(?::\w+)?\]/si'         => "<a href=\"mailto:\\1\" class=\"bb-email\">\\1</a>",
			'/(?<!\\\\)\[email(?::\w+)?=(.*?)\](.*?)\[\/email(?::\w+)?\]/si'   => "<a href=\"mailto:\\1\" class=\"bb-email\">\\2</a>",
			// [url]
			'/(?<!\\\\)\[url(?::\w+)?\]www\.(.*?)\[\/url(?::\w+)?\]/si'        => "<a href=\"http://www.\\1\" target=\"_blank\" class=\"bb-url\">\\1</a>",
			'/(?<!\\\\)\[url(?::\w+)?\](.*?)\[\/url(?::\w+)?\]/si'             => "<a href=\"\\1\" target=\"_blank\" class=\"bb-url\">\\1</a>",
			'/(?<!\\\\)\[url(?::\w+)?=(.*?)?\](.*?)\[\/url(?::\w+)?\]/si'      => "<a href=\"\\1\" target=\"_blank\" class=\"bb-url\">\\2</a>",
			// [img]
			'/(?<!\\\\)\[img(?::\w+)?\]((http|ftp|https|ftps):\/\/)([^\s\?&=\#\"<>]+?(\.(jpg|jpeg|gif|png)))\[\/img(?::\w+)?\]/si'             
                                                                               => "<img src=\"\\1\\3\" alt=\"\\1\" class=\"bb-image\" />",
            '/(?<!\\\\)\[img=(left|right|center)(?::\w+)?\]((http|ftp|https|ftps):\/\/)([^\s\?&=\#\"<>]+?(\.(jpg|jpeg|gif|png)))\[\/img(?::\w+)?\]/si'             
                                                                               => "<img align=\"\\1\" src=\"\\2\\4\" alt=\"\\1\" class=\"bb-image\" />",
			// [quote]
			'/(?<!\\\\)\[quote(?::\w+)?\](.*?)\[\/quote(?::\w+)?\]/si'         => "</p><table cellspacing=\"0\" cellpadding=\"6\" border=\"1\" style=\"border-color:#444444\" bgcolor=\"#000000\"><tr><td valign=\"top\">\\1</td></tr></table><p align=\"justify\">",
			'/(?<!\\\\)\[quote(?::\w+)?=(?:&quot;|"|\')?(.*?)["\']?(?:&quot;|"|\')?\](.*?)\[\/quote\]/si'   => "</p><table cellspacing=\"3\" cellpadding=\"3\" border=\"1\" style=\"border-color:#444444;\" bgcolor=\"#000000\"><tr><td valign=\"top\">\\2</td></tr></table><p align=\"justify\">",
			// [list]
			'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\*(?::\w+)?\](.*?)(?=(?:\s*<br\s*\/?>\s*)?\[\*|(?:\s*<br\s*\/?>\s*)?\[\/?list)/si' => "\n<li class=\"bb-listitem\">\\1</li>",
			'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\/list(:(?!u|o)\w+)?\](?:<br\s*\/?>)?/si'    => "\n</ul>",
			'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\/list:u(:\w+)?\](?:<br\s*\/?>)?/si'         => "\n</ul>",
			'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\/list:o(:\w+)?\](?:<br\s*\/?>)?/si'         => "\n</ol>",
			'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(:(?!u|o)\w+)?\]\s*(?:<br\s*\/?>)?/si'   => "\n<ul class=\"bb-list-unordered\">",
			'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list:u(:\w+)?\]\s*(?:<br\s*\/?>)?/si'        => "\n<ul class=\"bb-list-unordered\">",
			'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list:o(:\w+)?\]\s*(?:<br\s*\/?>)?/si'        => "\n<ol class=\"bb-list-ordered\">",
			'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=1\]\s*(?:<br\s*\/?>)?/si' => "\n<ol class=\"bb-list-ordered,bb-list-ordered-d\">",
			'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=i\]\s*(?:<br\s*\/?>)?/s'  => "\n<ol class=\"bb-list-ordered,bb-list-ordered-lr\">",
			'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=I\]\s*(?:<br\s*\/?>)?/s'  => "\n<ol class=\"bb-list-ordered,bb-list-ordered-ur\">",
			'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=a\]\s*(?:<br\s*\/?>)?/s'  => "\n<ol class=\"bb-list-ordered,bb-list-ordered-la\">",
			'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=A\]\s*(?:<br\s*\/?>)?/s'  => "\n<ol class=\"bb-list-ordered,bb-list-ordered-ua\">",
			// escaped tags like \[b], \[color], \[url], ...
			'/\\\\(\[\/?\w+(?::\w+)*\])/'                                      => "\\1"
		);
		$text = preg_replace(array_keys($preg), array_values($preg), $text);


        // Spoilers. Each must have unique id
        $sn = 0;
        $next = 0;
        do
        {
          $sn++;
          $preg = array(
            '/(?<!\\\\)\[spoiler(?::\w+)?=(.*?)\](.*?)\[\/spoiler(?::\w+)?\]/si'   
                => "<div style=\"padding:5px; width:99%; border: 2px solid #D0D0D0; cursor:pointer\" onClick=\"toggleSpoiler(\'spoiler".$post_id."s".$sn."\')\">\\1</div><div style=\"padding:5px; width:99%; border: 2px solid #D0D0D0; display:none\" id=\"spoiler".$post_id."s".$sn."\">\\2</div>",
            '/(?<!\\\\)\[spoiler(?::\w+)?\](.*?)\[\/spoiler(?::\w+)?\]/si'   
                => "<div style=\"padding:5px; width:99%; border: 2px solid #D0D0D0; cursor:pointer\" onClick=\"toggleSpoiler(\'spoiler".$post_id."s".$sn."\')\">Скрытый текст</div><div style=\"padding:5px; width:99%; border: 2px solid #D0D0D0; display:none\" id=\"spoiler".$post_id."s".$sn."\">\\1</div>",
                  );

          $t = preg_replace(array_keys($preg), array_values($preg), $text, 1, $next);
          if ($next)
            $text = $t;
        } while ($next != 0);

        /*
        $t = NULL;
        while (($t = preg_replace(array_keys($preg), array_values($preg), $text, 1)) != NULL)
        {
          $text = $t;
          $sn++;
          $preg = array(
            '/(?<!\\\\)\[spoiler(?::\w+)?=(.*?)\](.*?)\[\/spoiler(?::\w+)?\]/si'   
                => "<div style=\"padding:5px; width:99%; border: 2px solid #D0D0D0; cursor:pointer\" onClick=\"toggleSpoiler(\'spoiler".$post_id."n".$sn."\')\">\\1</div><div style=\"padding:5px; width:99%; border: 2px solid #D0D0D0; display:none\" id=\"spoiler".$post_id."n".$sn."\">\\2</div>",
            '/(?<!\\\\)\[spoiler(?::\w+)?\](.*?)\[\/spoiler(?::\w+)?\]/si'   
                => "<div style=\"padding:5px; width:99%; border: 2px solid #D0D0D0; cursor:pointer\" onClick=\"toggleSpoiler(\'spoiler".$post_id."n".$sn."\')\">Скрытый текст</div><div style=\"padding:5px; width:99%; border: 2px solid #D0D0D0; display:none\" id=\"spoiler".$post_id."n".$sn."\">\\1</div>",
             );
        }
        */

		if (strpos($text,':')!==false)
		{
			$dh = opendir('smile/');
			while($file = readdir($dh))
			{
				if ($file=='.') continue;
				if ($file=='..') continue;
				$len=strlen($file)-4;
				$filesmile = ':'.substr($file,0,$len).':';
				$text = str_replace($filesmile,"<img img alt=\"\" src=smile/$file border=0>",$text);
			}
		}
		return $text;
	}
	
	function Online($topic_id=0)
	{
		$topic_id=(int)$topic_id;
		myquery("INSERT INTO forum_online (user_id,topic_id,last_active) VALUES (".$this->char['user_id'].",$topic_id,".time().") ON DUPLICATE KEY UPDATE topic_id=$topic_id, last_active=".time()."");
	}
	
	function SaveSetup()
	{
		$sel = myquery("SELECT COUNT(*) FROM forum_setup WHERE user_id=".$this->char['user_id']."");
		$show_avatar = 0;
		if (isset($_POST['show_avatar']))
		{
			$show_avatar = 1;
		}
		$show_podpis = 0;
		if (isset($_POST['show_podpis']))
		{
			$show_podpis = 1;
		}
		if (mysql_result($sel,0,0)==0)
		{
			myquery("INSERT INTO forum_setup (user_id,show_avatar,show_podpis,reply,podpis) VALUES (".$this->char['user_id'].",$show_avatar,$show_podpis,".$_POST['reply'].",'".mysql_escape_string(htmlspecialchars($_POST['podpis']))."')");
		}
		else
		{
			myquery("UPDATE forum_setup SET show_avatar=$show_avatar,show_podpis=$show_podpis,reply=".$_POST['reply'].",podpis='".mysql_escape_string(htmlspecialchars($_POST['podpis']))."' WHERE user_id=".$this->char['user_id']."");
		}
		setLocation("index.php");
	}
	
	//***************************************************************************************
	//****    ИНТЕРФЕЙС ФОРУМА
	//***************************************************************************************
	function action($act) //тело форума
	{
		$this->action = $act;
		$this->PrintHeader();
		$this->PrintLeftTable();
		$this->Online();
		if (!isset($_GET['id']))
		{
			$_GET['id']='';
		}
		switch($this->action)
		{
			case 'kat':
			{
				$this->PrintCategory($_GET['id']);
				$this->action = $act;
			}
			break; 
			
			case 'allcategory':
			{
				$this->PrintAllCategory();
				$this->action = $act;
			}
			break; 
			
			case 'rules':
			{
				$this->PrintRules();
				$this->action = $act;
			}
			break; 
			
			case 'search':
			{
				$this->PrintSearch();
				$this->action = $act;
			}
			break; 
			
			case 'searchuser':
			{
				$this->PrintSearchUser();
				$this->action = $act;
			}
			break; 
			
			case 'topic':
			{
				$this->PrintTopic($_GET['id']);
				$this->action = $act;
				$this->Online($_GET['id']);
			}
			break; 
			
			case 'newtopic':
			{
				$this->PrintNewTopic($_GET['id']);
				$this->action = $act;
			}
			break; 
			
			case 'edittopic':
			{
				$this->PrintEditTopic($_GET['id']);
				$this->action = $act;
				$this->Online($_GET['id']);
			}
			break; 
			
			case 'edit':
			{
				$this->PrintEditReply($_GET['id']);
				$this->action = $act;
			}
			break; 
			
			case 'show_unread':
			{
				$this->PrintUnread();
				$this->action = $act;
			}
			break; 
			
			case 'sel_move_topic':
			{
				$id = (int)$_GET['id'];
				$this->PrintMoveTopic($id);
				$this->action = $act;
			}
			break;   
			
			case 'setup':
			{
				$this->PrintSetup();
				$this->action = $act;
			}
			break;
			
			default:
			{
				$this->PrintMain();
				$this->action = 'main';
			}
		}
		$this->PrintRightTable();
		$this->PrintFooter();
	} 
	
	function GoToFirstUnread($gotounread_id)
	{
		$last_read = myquery("select last_read_timestamp from forum_read where user_id = '".$this->char['user_id']."' and topic_id = '".$gotounread_id."' limit 1");
		if ($last_read==false OR mysql_num_rows($last_read) == 0)
		{
			//$d = myquery("insert into forum_read (user_id,topic_id,last_read_timestamp) values (".$this->char['user_id'].",".$gotounread_id.",".time().")");
			$inf = array("list" => "1", "f_unread" => "0");
			return $inf;
		}
		else
		{
			$row = mysql_fetch_array($last_read);
			$l_read = $row['last_read_timestamp'];
			$first_unread = myquery("select id from forum_otv where timepost > '".$l_read."' and topics_id = '".$gotounread_id."' order by timepost asc limit 1");
			if ($first_unread==false OR mysql_num_rows($first_unread) == 0)
			{
				//myquery("insert into forum_read (user_id,topic_id,last_read_timestamp) values (".$this->char['user_id'].",".$gotounread_id.",".time().") on duplicate key update last_read_timestamp=".time()."");
				$inf = array("list" => "n", "f_unread" => "0");
				return $inf;
			}
			else
			{
				$row = mysql_fetch_array($first_unread);
				$f_unread = $row['id'];
				$rights = $this->CheckTopicRights($gotounread_id);
				if ($rights['showdelete'])
				{
					$all = mysql_result(myquery("SELECT COUNT(*) FROM forum_otv where topics_id='".$gotounread_id."' and timepost < '".$l_read."' "),0,0);
				}
				else
				{
					$all = mysql_result(myquery("SELECT COUNT(*) FROM forum_otv where topics_id='".$gotounread_id."' and timepost < '".$l_read."' and del<>'1'"),0,0);
				};
				$n = $all + 1;
				$line=15;
				$list = ceil($n/$line) + 1;
				$inf = array("list" => $list, "f_unread" => $f_unread);
				return $inf;
			};
		};

	}                           
	
	function PrintIcon($sel_topic)
	{
		$stat = $sel_topic['stat'];
		if (isset($sel_topic['autor']))
			$autor_id = $sel_topic['autor'];
		else
			$autor_id = $sel_topic['user_id'];
		$otvetov = $sel_topic['otv'];
		if (isset($sel_topic['topic_id']))
			$topic_id = $sel_topic['topic_id'];
		else
			$topic_id = $sel_topic['id'];
		//list($stat,$autor_id,$otvetov) = mysql_fetch_array(myquery("SELECT stat,user_id,otv FROM forum_topics WHERE id=$topic_id"));
		//$selvote = myquery("SELECT status,poll_id FROM forum_poll WHERE topic_id=$topic_id");
		//if ($selvote!=false AND mysql_num_rows($selvote)>0)
		if (isset($sel_topic['poll_id']) AND $sel_topic['poll_id']!=NULL)
		{
			$status = $sel_topic['poll_status'];
			$poll_id = $sel_topic['poll_id'];
			if ($this->guest)
			{
				$al = 0;
			}
			else
			{
				$al = mysql_result(myquery("SELECT COUNT(*) FROM forum_poll_users WHERE poll_id=$poll_id AND user_id=".$this->char['user_id'].""),0,0);
			}
			if ($status==1)
			{
				if ($al==0)
				{
					echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_poll.gif">';
				}
				else
				{
					echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_poll_dot.gif">';
				}
			}
			else
			{
				if ($al==0)
				{
					echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_poll_no.gif">';
				}
				else
				{
					echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_poll_no_dot.gif">';
				}
			}
		}
		else
		{
			if ($otvetov>30)
			{
				if ($stat=='open')
				{
					if ($this->guest)
					{
						 echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_hot.gif">';
					}
					else
					{
						if ($autor_id==$this->char['user_id'])
						{
							echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_hot_dot.gif">';
						}
						else
						{
							$est = mysql_result(myquery("SELECT COUNT(*) FROM forum_otv WHERE topics_id=$topic_id AND user_id=".$this->char['user_id'].""),0,0);
							if ($est==0)
							{
								echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_hot.gif">';
							}
							else
							{
								echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_hot_dot.gif">';
							}
						}
					}
				}
				else
				{
					if ($this->guest)
					{
						 echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_hot_no.gif">';
					}
					else
					{
						if ($autor_id==$this->char['user_id'])
						{
							echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_hot_no_dot.gif">';
						}
						else
						{
							$est = mysql_result(myquery("SELECT COUNT(*) FROM forum_otv WHERE topics_id=$topic_id AND user_id=".$this->char['user_id'].""),0,0);
							if ($est==0)
							{
								echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_hot_no.gif">';
							}
							else
							{
								echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_hot_no_dot.gif">';
							}
						}
					}
				}
			}
			else
			{
				if ($stat=='open')
				{
					if ($this->guest)
					{
						 echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_norm.gif">'; 
					}
					else
					{
						if ($autor_id==$this->char['user_id'])
						{
							echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_norm_dot.gif">';
						}
						else
						{
							$est = mysql_result(myquery("SELECT COUNT(*) FROM forum_otv WHERE topics_id=$topic_id AND user_id=".$this->char['user_id'].""),0,0);
							if ($est==0)
							{
								echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_norm.gif">';
							}
							else
							{                   
								echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_norm_dot.gif">';
							}
						}
					}
				}
				else
				{
					if ($this->guest)
					{
						 echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_norm_no.gif">';
					}
					else
					{
						if ($autor_id==$this->char['user_id'])
						{
							echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_norm_no_dot.gif">';
						}
						else
						{
							$est = mysql_result(myquery("SELECT COUNT(*) FROM forum_otv WHERE topics_id=$topic_id AND user_id=".$this->char['user_id'].""),0,0);
							if ($est==0)
							{
								echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_norm_no.gif">';
							}
							else
							{
								echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_norm_no_dot.gif">';
							}
						}
					}
				}
			}
		}
	}
	
	function PrintCategory($kat_id) //вывод на экран определенной категории форума
	{
		$id = (int)$kat_id;
		$str_where = $this->MakePermissionForCategory();
		$topic_str_where = $this->MakePermissionForTopic();
		$prov = myquery("SELECT forum_kat.name FROM forum_kat,forum_main WHERE forum_main.id=forum_kat.main_id and forum_kat.id=$id $str_where");
		if ($prov!=false AND mysql_num_rows($prov))
		{
			list($kat_name) = mysql_fetch_array($prov);
		}
		else
		{
			return;
		} 
		?>
		<table style="width:100%;height:48px;background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/bg1.gif)" border="0" cellpadding="0" cellspacing="0">
			<tr>
			<td width="1%" style="height:48px"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/9.gif"></td>
			<td width="97%"><a href="index.php">Зал Палантиров</a>  > <?=$kat_name;?></td>
			<td width="2%"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/8.gif"></td>
			</tr>
		</table>
		<?
		$str_query_select = "SELECT 
		forum_topics.*,
		forum_name_last.name AS last_name, forum_name_user.name AS autor_name,forum_poll.status AS poll_status,forum_poll.poll_id AS poll_id  ";
		$str_query_count = "SELECT COUNT(*)";
		$str_query_from = " from (forum_topics,forum_main,forum_kat) left join (forum_name AS forum_name_last) ON (forum_name_last.user_id=forum_topics.last_user) left join (forum_name AS forum_name_user) ON (forum_name_user.user_id=forum_topics.user_id) LEFT JOIN (forum_poll) ON (forum_poll.topic_id=forum_topics.id)
		where forum_topics.kat_id=forum_kat.id AND forum_kat.main_id=forum_main.id AND forum_kat.id=$id $topic_str_where";
		if (!isset($_GET['page'])) $page=1;
		else $page=(int)$_GET['page'];
		if ($page<1) $page=1;
		$line=25;
		$sel = myquery($str_query_count.$str_query_from);
		$allpage=ceil(mysql_result($sel,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;
		$selpage = myquery($str_query_select.$str_query_from." ORDER BY forum_topics.priznak DESC, forum_topics.last_date DESC limit ".(($page-1)*$line).", $line");
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="29">
			<img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/1.gif">
			</td>
			<td valign="middle" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)">
			<?
			echo $kat_name;
			if (!$this->guest)
			{
				echo' | <a style="" href="?act=newtopic&amp;id='.$id.'">Создать&nbsp;тему</a>';
			}
			?>
			</td>
			<td width="40" height="25" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			<td width="20" height="25" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)" align="center">Отв.</td>
			<td width="40" height="25" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			<td width="100" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)" align="center">Начато</td>
			<td width="40" height="25" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			<td width="20" height="25" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)" align="center">Прсм.</td>
			<td width="40" height="25" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			<td width="100" height="25" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)" align="center">Посл.ответ</td>
			<td width="40" height="25" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			</tr>
		</table>
		<?
		if (!mysql_num_rows($selpage))
		{
			?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="4" height="6" background="http://<?=IMG_DOMAIN;?>/forum/menu/4.gif"></td><td width="771" align="center">
			<br>В данной категории нет тем<br><br>
			</td><td width="4" height="0" background="http://<?=IMG_DOMAIN;?>/forum/menu/4.gif"></td></tr></table>
			<?
		}
		else
		{
			while ($top=mysql_fetch_array($selpage))
			{
				$rights = $this->CheckTopicRights($top['id']);
				if (!$rights['view']) continue;
				?>
				<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tablesurround">
					<tr>
					<td>
					<table width="100%" border="0" cellspacing="2" cellpadding="2">
						<tr>
						<td style="width:20px;"> 
						<?
						$this->PrintIcon($top);
						?>
						</td>
						<td>
						<?
						if ($top['priznak']==1) echo '<img alt="" src="http://'.IMG_DOMAIN.'/forum/img/f_pinned.gif">&nbsp;';
						echo '<a href="?act=topic&amp;id='.$top['id'].'&amp;page=n"';
						if (!$this->guest)
						{
							$read = mysql_result(myquery("SELECT COUNT(*) FROM forum_read WHERE user_id=".$this->char['user_id']." AND topic_id=".$top['id'].""),0,0);
							if ($read>0) echo ' style="font-weight:100;color:#aaaaff;"';
						}
						if ($top['last_name']!=NULL)
						{
							$last_name = $top['last_name'];
						}
						else
						{
							$last_name = '';
						}
						if ($top['autor_name']!=NULL)
						{
							$autor_name = $top['autor_name'];
						}
						else
						{
							$autor_name = '';
						}
						echo '>';
						if ($top['priznak']==1) echo '<b>';
						echo stripslashes($top['top']);
						if ($top['priznak']==1) echo '</b>';
						echo '</a>';
						?>
						</td>
						<td width="50" align="center"><?=$top['otv'];?></td>
						<td width="140" align="center"><?=date("H:i, d-m-y",$top['timepost']);?><br><?=$autor_name;?></td>
						<td width="70" align="center"><?=$top['view'];?></td>
						<td width="140" align="center"><?=date("H:i, d-m-y",$top['last_date']);?><br><?=$last_name;?></td>
						</tr>
					</table>
					</td>
					</tr>
				</table>
				<?
			}

			?>
			<table style="width:100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td height="25" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/bg2.gif)" align="center">
				<?
				$href = '?act=kat&amp;id='.$id.'';
				echo'Страница: ';
				show_page($page,$allpage,$href);
				?>
				</td>
				</tr>
			</table>
			<?
		}
	}
	
	function PrintListTopic($selpage) //вывод на экран списка топиков
	{
		?>
		<table style="width:100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="26" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/1.gif"></td>
			<td valign="middle" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)">Раздел</td>
			<td width="40" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			<td style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)" align="center">Тема</td>
			<td width="40" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			<td style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)" align="center">Ответов</td>
			<td width="40" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			<td style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)" align="center" colspan="2">Посл.ответ</td>
			<td width="40" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			<td style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)" align="center">Просм.</td>
			<td width="40" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			</tr>
			<?
			
			while ($w=mysql_fetch_array($selpage))
			{
				$rights = $this->CheckTopicRights($w['topic_id']);
				if (!$rights['view']) continue;
				$kat = $w['main_name'].' / '.$w['kat_name'];
				echo '<tr class="tablesurround" style="height:20px;" valign="middle"><td align="center">';
				
				$this->PrintIcon($w);
				echo '</td><td>'.$kat.'</td><td></td><td>';
				$style_echo = '';
				if (!$this->guest)
				{
					$selread = myquery("SELECT last_read_timestamp FROM forum_read WHERE user_id=".$this->char['user_id']." AND topic_id=".$w['topic_id']."");
					if ($selread!=false AND mysql_num_rows($selread)>0)
					{
						$read = mysql_result($selread,0,0);
						if ($read>=$w['last_date']) 
						{
							$style_echo = ' style="font-weight:100;color:#aaaaff;"';
						}
						else
						{
							echo '<a href="?act=topic&amp;id='.$w['topic_id'].'&amp;last_unread"><img title="Перейти к первому непрочитанному сообщению" border="0" src="http://'.IMG_DOMAIN.'/forum/img/newpost.gif"></a>';
						}
					}
					else
					{
						echo '<a href="?act=topic&amp;id='.$w['topic_id'].'&amp;last_unread"><img title="Перейти к первому непрочитанному сообщению" border="0" src="http://'.IMG_DOMAIN.'/forum/img/newpost.gif"></a>';
					}
				};
				echo '<a href="?act=topic&amp;id='.$w['topic_id'].'"> &#149;  </a>   <a href="?act=topic&amp;id='.$w['topic_id'].'&amp;page=n"';
				echo $style_echo;
				if ($w['last_name']!=NULL)
				{
					$last_name = $w['last_name'];
				}
				else
				{
					$last_name = '*****';
				}
				$last_name='<a href="?act=topic&amp;id='.$w['topic_id'].'&page=n#fast_reply">'.date("d-m-y H:i",$w['last_date']).'</a> <b><a href="http://'.DOMAIN.'/view/?userid='.$w['last_user'].'" target="_blank">'.$last_name.'</a></b>';
				echo '>'.stripslashes($w['topic_top']).'</a></td><td></td><td align="right">'.$w['otv'].'</td><td></td><td>'.$last_name.'</td><td></td><td align="right" colspan="2">'.$w['view'].'</td><td></td></tr><tr style="height:1px;" bgcolor="black"><td colspan="12"></td></tr>';
			}
		?>
		</table>
		<?
	}
	
	function PrintMain() //"первый" экран форума - со списком последних сообщений
	{        
		?>                                                                                                     
		<table style="width:100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td valign="middle" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/topbg.gif)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<form style="display:inline;" action="?act=allcategory" method="POST">
			<input type="button" value="Открыть список разделов для создания новой темы" onclick="location.replace('?act=allcategory')">
			</form>
			</td>
			</tr>
		</table>

		<?
		$i=1;

		$str_where = "and 0=1";
		$str_query_select = "select 
		forum_topics.last_user AS last_user, 
		forum_topics.last_date AS last_date, 
		forum_topics.otv AS otv, 
		forum_topics.user_id AS autor,
		forum_topics.id AS topic_id, 
		forum_topics.top AS topic_top, 
		forum_topics.stat AS stat,
		forum_topics.view AS view, 
		forum_topics.text AS text,
		forum_kat.name AS kat_name,
		forum_poll.status AS poll_status,
		forum_poll.poll_id AS poll_id, 
		forum_main.name AS main_name,
		forum_name.name AS last_name "; 
		$str_query_count = "SELECT COUNT(*) ";
		$str_query_from = "FROM (forum_topics,forum_kat,forum_main) LEFT JOIN (forum_name) ON (forum_name.user_id=forum_topics.last_user) LEFT JOIN (forum_poll) ON (forum_poll.topic_id=forum_topics.id) WHERE forum_main.id = forum_kat.main_id AND forum_kat.id = forum_topics.kat_id ";
		
		$str_where = $this->MakePermissionForTopic();

		$sel=myquery($str_query_count.$str_query_from.$str_where);
		if (!isset($_GET['page'])) $page=1;
		else $page=(int)$_GET['page'];
		$line=25;
		$allpage=ceil(mysql_result($sel,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;
		$selpage=myquery($str_query_select.$str_query_from.$str_where." order by forum_topics.last_date DESC limit ".(($page-1)*$line).", $line");

		$this->PrintListTopic($selpage);

		?>
		<table style="width:100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="25" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/bg2.gif)" align="center">
			<?
			$href = '?';
			echo'Страница: ';
			show_page($page,$allpage,$href);
			?>
			</td>
			</tr>
		</table>
		<?
	} 
	
	function PrintAllCategory() //Печать списка всех категорий для выбора и ввода новой темы
	{
		$str_where = '';
		switch ($this->user_rights['category'])
		{
			case 0: {$str_where= "where level='' or level='priv'";} break; //гость или игрок без клана
			case 1: {$str_where= "where level!='adm'"; } break; //игрок в клане, не страж, не админ
			case 2: {$str_where= "";} break; //игрок в клане, страж, не админ
			case 3: {$str_where= "where level!='clan'";} break; //страж без клана
			case 4: {$str_where= "";} break; //админ
		}  
		$select=myquery("select * from forum_main $str_where order by id");
		while ($row=mysql_fetch_array($select))
		{
			?>
			<table style="height:25px;width:100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td width="29">
				<img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/1.gif">
				</td>
				<td valign="middle" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif);">Раздел <b>"<?=$row['name'];?>"</b></td>
				<td width="40" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)">
				<img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif">
				</td>
				</tr>
			</table>
			<?
			//Разделы
			$str_where = $this->MakePermissionForCategory();
			$kateg=myquery("select forum_kat.* from forum_kat,forum_main where forum_kat.main_id=".$row['id']." AND forum_main.id=".$row['id']." $str_where order by forum_kat.id");
			if (mysql_num_rows($kateg))
			{
				$i = 0;
				$all = mysql_num_rows($kateg);
				echo '<table style="width:100%;" cellspacing="0" cellpadding="0" border="0"><tr>';
				while ($kat=mysql_fetch_array($kateg))
				{
					$i++;
					echo '<td style="height:100%;width:33%">';
					echo '
					<table style="width:100%;height:100%;" class="tablesurround" cellspacing="0" cellpadding="0">
					<tr>
					<td width="20"><img alt="" src="http://'.IMG_DOMAIN.'/forum/img/new.gif"></td>
					<td><a href="?act=kat&amp;id='.$kat['id'].'">'.$kat['name'].'</a><br><i>'.$kat['text'].'</i>
					</td></tr></table>
					</td>';
					if ($i%3==0) echo '</tr><tr>';
				}

				if ($i%3!=0)
				{
					while ($i%3!=0)
					{
						$i++;
						echo '<td class="tablesurround" style="height:100%;width:33%;">';
						echo '<table style="height:100%;width:100%;" cellspacing="0" cellpadding="0">
						<tr><td>&nbsp;</td></tr></table>';
						echo '</td>';
					}
				}
				else
				{
					echo '<td></td>';
				}
				echo '</tr></table>';
			}
		}
	} 
	
	function PrintOneReply($otv)
	{
		$str_return = '';
		$last_otv_id = $otv['id'];
		$str_return.='<tr id="otvet'.$otv['id'].'">
		<td style="border: 1px solid #282828" bgcolor="#4F4F4F" valign="top" rowspan="2"><a name="otvet'.$otv['id'].'">';
		$str_return.=$this->PrintUser($otv);
		$str_return.='</td><td style="border-top: 1px solid #282828;border-left: 1px solid #282828;border-right: 1px solid #282828;padding:3px;height:100%" valign="top"';
		if($otv['del']=='1')
		{
			$str_return.= ' BGCOLOR=darkred';
		}
		$str_return.='><b>';
		if($otv['del']=='1') $str_return.= ' <font face=Arial size=3> ЭТО УДАЛЕННЫЙ ОТВЕТ. ОН ВИДИМ ТОЛЬКО ДЛЯ МОДЕРАТОРОВ ФОРУМА<br></font>';
		$str_return.= '<a name="'.$otv['id'].'"></a><font color=#DDDDDD>Дата: ';
		$str_return.= date("H:i:s   d-m-Y",$otv['timepost']);
		$str_return.= '</font></b><br><br><p align="justify">'.stripslashes(nl2br($this->convert_bbcode_tags($otv['text']))).'</p></td></tr><tr valign="bottom" style="height:25px"><td style="border-bottom: 1px solid #282828;border-left: 1px solid #282828;border-right: 1px solid #282828" id="buttonotvet'.$otv['id'].'">&nbsp;';
		if (($this->setup['show_podpis']==1 OR $this->guest) AND !empty($otv['podpis']))
		{
			$str_return.= '----------------------------<div style="font-size:11px;font-style:italic;color:lightgrey;">'.stripslashes(nl2br($this->convert_bbcode_tags($otv['podpis']))).'</div>';
		}
		$str_return.=$this->PrintButton($otv);
		$str_return.='</td></tr>';    
		$sel_thanks = myquery("SELECT forum_thanks.user_id,forum_name.name FROM forum_thanks,forum_name WHERE forum_thanks.post_id=".$otv['id']." AND forum_thanks.user_id=forum_name.user_id");
		$kol_users = mysql_num_rows($sel_thanks);
		$str_return.='
		</td>
		</tr>';
		if ($kol_users!=0)
		{
			$str_return.='<tr style="height:30px;">
			<td style="vertical-align:middle;text-align:center;border: 1px solid #282828" width="20%" bgcolor="#4F4F4F" valign="top">';
			$str_return.=''.pluralForm($kol_users,'Этот','Эти','Эти').' '.$kol_users.' '.pluralForm($kol_users,'игрок сказал','игрока сказали','игроков сказало');
			$str_return.=' спасибо:
			</td>
			<td>';
			$i = 0;
			while (list($thanks_user_id,$thanks_name) = mysql_fetch_array($sel_thanks))
			{
				$i++;
				$str_return.='<a href="../view/?userid='.$thanks_user_id.'" target=_blank>'.$thanks_name.'</a>';
				if ($thanks_user_id == $this->char['user_id'])
				{
					$str_return.='  (<a href="?act=topic&id='.$otv['topics_id'];
                    if (isset($_GET['page'])) $str_return.='&page='.$_GET['page'];
                    $str_return.='&delthanks_post='.$otv['id'].'#otvet'.$otv['id'].'">Удалить свою благодарность</a>)  ';
				}
				if ($i<$kol_users)$str_return.=', ';
			}
			$str_return.='
			</td>
			</tr>';
		}
		return $str_return;
	}
	
	function PrintTopic($topic_id) //Вывод ответов топика
	{
		$id = (int)$topic_id;
		if ($id==0) {return;};
		
		$str_where = $this->MakePermissionForTopic();
		$str_query = "SELECT forum_kat.name,forum_topics.top FROM forum_kat,forum_main,forum_topics WHERE forum_main.id=forum_kat.main_id and forum_topics.kat_id=forum_kat.id AND forum_topics.id=$id $str_where";
		$prov = myquery($str_query);
		if ($prov!=false AND mysql_num_rows($prov))
		{
			list($kat_name,$topics_name) = mysql_fetch_array($prov);
		}
		else
		{
			return;
		} 
		
		$rights = $this->CheckTopicRights($id);
		 //Отображение содержимого топика
		if ($rights['view'])
		{
			$prov=myquery("select forum_topics.*,forum_kat.name AS kat_name from forum_topics,forum_kat where forum_topics.id=$id AND forum_topics.kat_id=forum_kat.id");
			if (mysql_num_rows($prov))
			{
				$topic=mysql_fetch_array($prov);
				?>
				<a name="top">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" style="height:48px;background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/bg1.gif)">
					<tr>
					<td width="1%" height="48"><img src="http://<?=IMG_DOMAIN;?>/forum/menu/9.gif" alt=""></td>
					<td width="97%"><a href="index.php">Зал Палантиров</a>  > <a href="?act=kat&amp;id=<?=$topic['kat_id'];?>"><?=$topic['kat_name'];?></a> > <?=stripslashes($topic['top']);?></td>
					<td width="2%"><img src="http://<?=IMG_DOMAIN;?>/forum/menu/8.gif" alt=""></td>
					</tr>
				</table>
				</a>

				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td width="29"><img src="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif" alt=""><img src="http://<?=IMG_DOMAIN;?>/forum/menu/1.gif" alt=""></td>
					<td valign="middle" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><?=stripslashes($topic['top']);?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?
					if ($rights['pin'])
					{
						if ($topic['priznak']==0) echo'  <input type="button" onclick="location.replace(\'?act=topic&amp;id='.$topic['id'].'&amp;markattention\')" value="Сделать &quot;Важно&quot;">';
						else echo '  <input type="button" onclick="location.replace(\'?act=topic&amp;id='.$topic['id'].'&amp;markattention\')" value="Отменить &quot;Важно&quot;">';
					}
					if ($rights['openclose'])
					{
						if ($topic['stat']=='open') 
							echo'  <input type="button" onclick="location.replace(\'?closetopic&amp;id='.$topic['id'].'\')" value="Закрыть тему">';
						else 
							echo'  <input type="button" onclick="location.replace(\'?closetopic&amp;id='.$topic['id'].'\')" value="Открыть тему">';
					}
					if ($rights['move'])
					{
						echo'  <input type="button" onclick="location.replace(\'?act=sel_move_topic&amp;id='.$topic['id'].'\')" value="Переместить">';
					}
					if ($rights['delete'] AND $topic['kat_id']!=0)
					{
						echo '  <input type="button" onclick="location.replace(\'?delete&amp;id='.$topic['id'].'\')" value="Удалить всю тему">';
					}
					echo '</td>
					<td align="right" valign="middle" style="background-image:url(http://'.IMG_DOMAIN.'/forum/menu/2.gif)">';
					
					if ($topic['stat']!='open') echo' <b><font color=ff0000>Закрыто</font></b>';
					?></td>
					<td width="40" height="25" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif" alt=""></td>
					</tr>
				</table>

				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td style="height:6px;width:4px;background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/4.gif)"></td>
					<td width="100%">
						<table id="new_otvet" width="100%" cellspacing="0" cellpadding="0">
							<tr>
							<td style="border: 1px solid #282828" width="20%" bgcolor="#4F4F4F" valign="top" rowspan="2">
							<?
							$autor=$topic['user_id'];
							$otv = mysql_fetch_array(myquery("(SELECT game_users.name AS name1,game_users.sklon AS sklon1,game_users.clevel AS clevel1,game_users.race AS race1,game_users.avatar AS avatar1,game_users.clan_id AS clan_id1,game_users.user_id,game_users_data.sex,forum_setup.thanks_count AS thanks_count,forum_setup.thanks_post AS thanks_post,forum_setup.say_thanks AS say_thanks,forum_setup.kol_posts AS kol_posts FROM game_users,game_users_data,forum_setup WHERE game_users.user_id=$autor AND forum_setup.user_id=$autor AND game_users_data.user_id=$autor) UNION (SELECT game_users_archive.name AS name1,game_users_archive.sklon AS sklon1,game_users_archive.clevel AS clevel1,game_users_archive.race AS race1,game_users_archive.avatar AS avatar1,game_users_archive.clan_id AS clan_id1,game_users_archive.user_id,game_users_data.sex,forum_setup.thanks_count AS thanks_count,forum_setup.thanks_post AS thanks_post,forum_setup.say_thanks AS say_thanks,forum_setup.kol_posts AS kol_posts FROM game_users_archive,game_users_data,forum_setup WHERE game_users_archive.user_id=$autor AND forum_setup.user_id=$autor AND game_users_data.user_id=$autor)"));
							echo $this->PrintUser($otv);
				
							$topic['text'] = stripslashes(nl2br($this->convert_bbcode_tags($topic['text'])));
							?>
							</td>
							<td  style="background-image:url(http://<?=IMG_DOMAIN;?>/nav/story-content-bg2.gif);border-top: 1px solid #282828;border-left: 1px solid #282828;border-right: 1px solid #282828;padding:3px;" width="80%" valign="top"><b><font color=#91FFFF>Дата: 
							<?=date("H:i:s   d-m-Y",$topic['timepost']);?>
							,   количество просмотров темы - <?=$topic['view'];?></font></b><br /><br /><?=$topic['text'];?>
							<?
							$this->PrintPoll($topic['id'],'read');
							?>
							</td>
							</tr>
							<tr valign="bottom" style="height:25px"><td style="border-bottom: 1px solid #282828;border-left: 1px solid #282828;border-right: 1px solid #282828">&nbsp;
							<?
							$this->PrintButtonTheme($topic,$rights);
							
							$sel_thanks = myquery("SELECT forum_thanks.user_id,forum_name.name FROM forum_thanks,forum_name WHERE forum_thanks.topic_id=".$topic['id']." AND forum_thanks.user_id=forum_name.user_id");
							$kol_users = mysql_num_rows($sel_thanks);
							?>
							</td>
							</tr>
							<?
							if ($kol_users!=0)
							{
								?>
								<tr style="height:30px;">
								<td style="vertical-align:middle;text-align:center;border: 1px solid #282828" width="20%" bgcolor="#4F4F4F" valign="top">
								<?
								echo pluralForm($kol_users,'Этот','Эти','Эти').' '.$kol_users.' '.pluralForm($kol_users,'игрок сказал ','игрока сказали ','игроков сказало ');
								?>спасибо:
								</td>
								<td>
								<?
								$i = 0;
								while (list($thanks_user_id,$thanks_name) = mysql_fetch_array($sel_thanks))
								{
									$i++;
									echo '<a href="../view/?userid='.$thanks_user_id.'" target=_blank>'.$thanks_name.'</a>';
									if ($thanks_user_id == $this->char['user_id'])
									{
										echo '  (<a href="?act=topic&id='.$topic['id'];
                                        if (isset($_GET['page']) and is_numeric($_GET['page'])) echo '&page='.$_GET['page'];
                                        echo '&delthanks_topic='.$topic['id'].'">Удалить свою благодарность</a>)  ';
									}
									if ($i<$kol_users) echo ', ';
								}
								?>
								</td>
								</tr>
								<?
							}
							if (!$this->guest)
							{
								myquery("insert into forum_read (user_id,topic_id,last_read_timestamp) values (".$this->char['user_id'].",".$topic['id'].",".time().") on duplicate key update last_read_timestamp=".time()."");
							}
							if (!isset($_GET['page'])) $page=1;
							else $page=$_GET['page'];
							if ($page=='n') $page=99;
							$line=$this->setup['reply'];
							if ($line<1)
							{
								$line=1;
							}
							if ($page=='all')
							{
								$page = 1;
								$line = 99999;
							}
							$page=(int)$page;
							if ($page<1) $page=1;
							if ($rights['showdelete'])
							{
								$pg=myquery("SELECT COUNT(*) FROM forum_otv where topics_id=".$topic['id']."");
							}
							else
							{
								$pg=myquery("SELECT COUNT(*) FROM forum_otv where topics_id=".$topic['id']." and del<>'1'");
							}
							$allpage=ceil(mysql_result($pg,0,0)/$line);
							if ($page>$allpage) $page=$allpage;
							if ($page<1) $page=1;

							if ($rights['showdelete'])
							{
								$select=myquery("
								(select forum_otv.*,
								
								game_users.name As name1,
								game_users.sklon AS sklon1,
								game_users.clevel AS clevel1,
								game_users.race AS race1,
								game_users.avatar As avatar1,
								game_users.clan_id AS clan_id1, 
							
								game_users_archive.name As name2,
								game_users_archive.sklon AS sklon2,
								game_users_archive.clevel AS clevel2,
								game_users_archive.race AS race2,
								game_users_archive.avatar As avatar2,
								game_users_archive.clan_id AS clan_id2,
								
								game_users_data.sex,
								
								forum_setup.podpis,forum_setup.thanks_count AS thanks_count,forum_setup.thanks_post AS thanks_post,forum_setup.say_thanks AS say_thanks,forum_setup.kol_posts AS kol_posts
								from
								(forum_otv)
								left join
								(game_users)
								ON (game_users.user_id=forum_otv.user_id)
								left join
								(game_users_archive)
								ON (game_users_archive.user_id=forum_otv.user_id)
								left join
								(forum_setup)
								ON (forum_setup.user_id=forum_otv.user_id)
								left join
								(game_users_data)
								ON (game_users_data.user_id=forum_otv.user_id)
								where forum_otv.topics_id=".$topic['id'].")  ORDER BY id ASC limit ".(($page-1)*$line).", $line");
							}
							else
							{
								$select=myquery("
								(select forum_otv.*,
								
								game_users.name As name1,
								game_users.sklon AS sklon1,
								game_users.clevel AS clevel1,
								game_users.race AS race1,
								game_users.avatar As avatar1,
								game_users.clan_id AS clan_id1,
								
								game_users_archive.name As name2,
								game_users_archive.sklon AS sklon2,
								game_users_archive.clevel AS clevel2,
								game_users_archive.race AS race2,
								game_users_archive.avatar As avatar2,
								game_users_archive.clan_id AS clan_id2,
								
								game_users_data.sex,
								
								forum_setup.podpis,forum_setup.thanks_count AS thanks_count,forum_setup.thanks_post AS thanks_post,forum_setup.say_thanks AS say_thanks,forum_setup.kol_posts AS kol_posts
								from
								(forum_otv)
								left join
								(game_users)
								ON (game_users.user_id=forum_otv.user_id)
								left join
								(game_users_archive)
								ON (game_users_archive.user_id=forum_otv.user_id)
								left join
								(forum_setup)
								ON (forum_setup.user_id=forum_otv.user_id)
								left join
								(game_users_data)
								ON (game_users_data.user_id=forum_otv.user_id)
								where forum_otv.topics_id=".$topic['id']." AND forum_otv.del<>'1')
								ORDER BY id ASC limit ".(($page-1)*$line).", $line");
							}

							$last_otv_id = 0;
							if ($select!=false AND mysql_num_rows($select))
							{
								while ($otv=mysql_fetch_array($select))
								{
									$last_otv_id = $otv['id'];
									echo $this->PrintOneReply($otv);
								}
							}
							echo '<tr><td colspan="2"><span name="reply_ajax"><input type="hidden" id="last_otv_id" value="'.$last_otv_id.'"></span></td></tr>';
						?>
						</table>
					</td>
					<td width="4" height="0" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/4.gif)"></td>
					</tr>
				</table>
				<table style="width:100%" border="0" cellspacing="0" cellpadding="0">
					<tr style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/bg2.gif)">
					<?
					if ($this->forum_admin OR $this->user_rights['category']==4)
					{
						echo '<td><input type="button" name="moder_select" onclick="action_moder()" id="moder_select" value="C отмеченными (0): ">
						<select id="select_moder" name="select_moder">
						<option value="1">Удалить</option>
						<option value="2">Восстановить</option>
						<option value="3">Удалить полностью</option>
						</select>
						</td>';
					}
					?>
					<td height="25" align="center">
					<?
					$href = "?act=topic&amp;id=".$topic['id']."";
					echo'Страница: ';
					if ($allpage==1) echo '1';
					show_page($page,$allpage,$href);
					if ($allpage>1)
						echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="?act=topic&amp;id='.$topic['id'].'&amp;page=all">Все страницы</a>';
					?>
					</td>
					<td style="width:230px;">
					<?
					if (!$this->guest)
					{
						?><a href="?mark_unread&amp;id=<?=$topic['id'];?>">Пометить тему как непрочитанная</a><?
					}
					?>
					</td>
					</tr>
				</table>
				<?
				if ($rights['reply'] AND $topic['stat']=='open')
				{
				   //Форма быстрого ответа
				   ?>
				   <a name="fast_reply">
				   <table width="100%" border="0" cellspacing="2" cellpadding="2">
						<tr>
						<td rowspan="3"></td><td valign="top" align="left">Ответ в тему: <?=stripslashes($topic['top']);?></td>
						</tr>
						<tr>
						<td><? $this->PrintReply('Ответить',0,$last_otv_id,$topic['id']); ?></td>
						</tr>
				   </table>
				   <?
				}
				if (!$this->guest) myquery("update forum_topics set view=view+1 where id=".$topic['id']."");
			}
		}
	}
	
	function PrintPoll($topic_id,$option_poll='read',$ajax=0)
	{
	   $rights = $this->CheckPollRights($topic_id);
	   if ($option_poll=='new')
	   {
			?>     
			<table width="90%" border=0 cellspacing=0 cellpadding=0>
				<tr><td colspan="3" vailgn=middle align=center> <b><font color=#FF0000>Создать голосование в теме</font></b></td></tr>
				<tr><td style="width:120px;">Название опроса:</td><td colspan="2"><input style="width:100%" type="text" maxsize="200" name="question"></td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
				<?
				for ($i=1;$i<=20;$i++)
				{
					$pri=$i;
					if ($i<10) $pri='0'.$i;
					echo'
					<tr><td align="right">'.$pri.')&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" style="width:100%" maxsize="200" name="option'.$i.'"></td><td style="width:60px;">
						<SELECT NAME="color'.$i.'">
						<OPTION VALUE="ffffff" style="background-color:black; color: ffffff;"';if ($i==1) echo ' SELECTED'; echo'>Белый</OPTION>
						<OPTION VALUE="00ff00" style="background-color:black; color: 00ff00;"';if ($i==2) echo ' SELECTED'; echo'>Зелёный</OPTION>
						<OPTION VALUE="666666" style="background-color:black; color: 666666;"';if ($i==3) echo ' SELECTED'; echo'>Серый</OPTION>
						<OPTION VALUE="ffff00" style="background-color:black; color: ffff00;"';if ($i==4) echo ' SELECTED'; echo'>Жёлтый</OPTION>
						<OPTION VALUE="0066ff" style="background-color:black; color: 0066ff;"';if ($i==5) echo ' SELECTED'; echo'>Голубой</OPTION>
						<OPTION VALUE="990099" style="background-color:black; color: 990099;"';if ($i==6) echo ' SELECTED'; echo'>Розовый</OPTION>
						<OPTION VALUE="660033" style="background-color:black; color: 660033;"';if ($i==7) echo ' SELECTED'; echo'>Бордовый</OPTION>
						<OPTION VALUE="ff0000" style="background-color:black; color: ff0000;"';if ($i==8) echo ' SELECTED'; echo'>Красный</OPTION>
						<OPTION VALUE="0000ff" style="background-color:black; color: 0000ff;"';if ($i==9) echo ' SELECTED'; echo'>Синий</OPTION>
						<OPTION VALUE="cyan" style="background-color:black; color: cyan;"';if ($i==10) echo ' SELECTED'; echo'>Циановый</OPTION>
						<OPTION VALUE="#C0C0FF" style="background-color:black; color: C0C0FF;"';if ($i==11) echo ' SELECTED'; echo'>#C0C0FF</OPTION>
						<OPTION VALUE="#C0FFFF" style="background-color:black; color: C0FFFF;"';if ($i==12) echo ' SELECTED'; echo'>#C0FFFF</OPTION>
						<OPTION VALUE="#C0FFC0" style="background-color:black; color: C0FFC0;"';if ($i==13) echo ' SELECTED'; echo'>#C0FFC0</OPTION>
						<OPTION VALUE="#FFFFC0" style="background-color:black; color: FFFFC0;"';if ($i==14) echo ' SELECTED'; echo'>#FFFFC0</OPTION>
						<OPTION VALUE="#FFC0C0" style="background-color:black; color: FFC0C0;"';if ($i==15) echo ' SELECTED'; echo'>#FFC0C0</OPTION>
						<OPTION VALUE="#8080FF" style="background-color:black; color: 8080FF;"';if ($i==16) echo ' SELECTED'; echo'>#8080FF</OPTION>
						<OPTION VALUE="#80FFFF" style="background-color:black; color: 80FFFF;"';if ($i==17) echo ' SELECTED'; echo'>#80FFFF</OPTION>
						<OPTION VALUE="#80FF80" style="background-color:black; color: 80FF80;"';if ($i==18) echo ' SELECTED'; echo'>#80FF80</OPTION>
						<OPTION VALUE="#FFFF80" style="background-color:black; color: FFFF80;"';if ($i==19) echo ' SELECTED'; echo'>#FFFF80</OPTION>
						<OPTION VALUE="#FF8080" style="background-color:black; color: FF8080;"';if ($i==20) echo ' SELECTED'; echo'>#FF8080</OPTION>
						</SELECT>
					</td></tr>
					';
				}
				?>
			</table>
			<input type="hidden" name="newvote">
			<?
	   }
	   elseif ($option_poll=='edit' AND $rights['editpoll'])
	   {
			$editpoll = true;
			$sel_poll = myquery("SELECT * FROM forum_poll WHERE topic_id='$topic_id'");
			if ($sel_poll!=false and mysql_num_rows($sel_poll)>0)
			{
				$poll = mysql_fetch_array($sel_poll);
				$vote = mysql_result(myquery("SELECT COUNT(*) FROM forum_poll_users WHERE poll_id=".$poll['poll_id'].""),0,0);
				if ($vote>0)
				{
					$editpoll=false;
				}
			}
			else
			{
			}
			if ($editpoll)
			{
				?>     
				<table width="90%" border=0 cellspacing=0 cellpadding=0>
					<tr><td colspan="3" vailgn="middle" align="center"> <b><font color=#FF0000>Создать голосование в теме</font></b></td></tr>
					<tr><td style="width:120px;">Название опроса:</td><td colspan="2"><input style="width:100%" type="text" maxsize="200" name="question" value="<?=$poll['question'];?>"></td></tr>
					<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
					<?
					$opt = array();
					if (isset($poll))
					{
						$poll_option = myquery("SELECT * FROM forum_poll_data WHERE poll_id=".$poll['poll_id']." ORDER BY option_id ASC");
						while ($option = mysql_fetch_array($poll_option))
						{
							$opt[$option['option_id']]['text']=$option['option_text'];
							$opt[$option['option_id']]['color']=$option['color'];
						}
					}
					for ($i=1;$i<=20;$i++)
					{
						unset($color);
						$pri=$i;
						if ($i<10) $pri='0'.$i;
						echo'
						<tr><td align="right">'.$pri.')&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input style="width:100%" type="text" maxsize="200" name="option'.$i.'"';
						if (isset($opt[$i]))
						{
							echo ' value="'.$opt[$i]['text'].'"';
							$color = $opt[$i]['color']; 
						}
						echo '></td><td style="width:60px;">
							<SELECT NAME="color'.$i.'">
							<OPTION VALUE="ffffff" style="background-color:black; color: ffffff;"';if (isset($color) AND $color=="ffffff") echo ' SELECTED'; elseif ($i==1) echo ' SELECTED'; echo'>Белый</OPTION>
							<OPTION VALUE="00ff00" style="background-color:black; color: 00ff00;"';if (isset($color) AND $color=="00ff00") echo ' SELECTED'; elseif ($i==2) echo ' SELECTED'; echo'>Зелёный</OPTION>
							<OPTION VALUE="666666" style="background-color:black; color: 666666;"';if (isset($color) AND $color=="666666") echo ' SELECTED'; elseif ($i==3) echo ' SELECTED'; echo'>Серый</OPTION>
							<OPTION VALUE="ffff00" style="background-color:black; color: ffff00;"';if (isset($color) AND $color=="ffff00") echo ' SELECTED'; elseif ($i==4) echo ' SELECTED'; echo'>Жёлтый</OPTION>
							<OPTION VALUE="0066ff" style="background-color:black; color: 0066ff;"';if (isset($color) AND $color=="0066ff") echo ' SELECTED'; elseif ($i==5) echo ' SELECTED'; echo'>Голубой</OPTION>
							<OPTION VALUE="990099" style="background-color:black; color: 990099;"';if (isset($color) AND $color=="990099") echo ' SELECTED'; elseif ($i==6) echo ' SELECTED'; echo'>Розовый</OPTION>
							<OPTION VALUE="660033" style="background-color:black; color: 660033;"';if (isset($color) AND $color=="660033") echo ' SELECTED'; elseif ($i==7) echo ' SELECTED'; echo'>Бордовый</OPTION>
							<OPTION VALUE="ff0000" style="background-color:black; color: ff0000;"';if (isset($color) AND $color=="ff0000") echo ' SELECTED'; elseif ($i==8) echo ' SELECTED'; echo'>Красный</OPTION>
							<OPTION VALUE="0000ff" style="background-color:black; color: 0000ff;"';if (isset($color) AND $color=="0000ff") echo ' SELECTED'; elseif ($i==9) echo ' SELECTED'; echo'>Синий</OPTION>
							<OPTION VALUE="cyan" style="background-color:black; color: cyan;"';if (isset($color) AND $color=="cyan") echo ' SELECTED'; elseif ($i==10) echo ' SELECTED'; echo'>Циановый</OPTION>
							<OPTION VALUE="#C0C0FF" style="background-color:black; color: #C0C0FF;"';if (isset($color) AND $color=="#C0C0FF") echo ' SELECTED'; elseif ($i==11) echo ' SELECTED'; echo'>#C0C0FF</OPTION>
							<OPTION VALUE="#C0FFFF" style="background-color:black; color: #C0FFFF;"';if (isset($color) AND $color=="#C0FFFF") echo ' SELECTED'; elseif ($i==12) echo ' SELECTED'; echo'>#C0FFFF</OPTION>
							<OPTION VALUE="#C0FFC0" style="background-color:black; color: #C0FFC0;"';if (isset($color) AND $color=="#C0FFC0") echo ' SELECTED'; elseif ($i==13) echo ' SELECTED'; echo'>#C0FFC0</OPTION>
							<OPTION VALUE="#FFFFC0" style="background-color:black; color: #FFFFC0;"';if (isset($color) AND $color=="#FFFFC0") echo ' SELECTED'; elseif ($i==14) echo ' SELECTED'; echo'>#FFFFC0</OPTION>
							<OPTION VALUE="#FFC0C0" style="background-color:black; color: #FFC0C0;"';if (isset($color) AND $color=="#FFC0C0") echo ' SELECTED'; elseif ($i==15) echo ' SELECTED'; echo'>#FFC0C0</OPTION>
							<OPTION VALUE="#8080FF" style="background-color:black; color: #8080FF;"';if (isset($color) AND $color=="#8080FF") echo ' SELECTED'; elseif ($i==16) echo ' SELECTED'; echo'>#8080FF</OPTION>
							<OPTION VALUE="#80FFFF" style="background-color:black; color: #80FFFF;"';if (isset($color) AND $color=="#80FFFF") echo ' SELECTED'; elseif ($i==17) echo ' SELECTED'; echo'>#80FFFF</OPTION>
							<OPTION VALUE="#80FF80" style="background-color:black; color: #80FF80;"';if (isset($color) AND $color=="#80FF80") echo ' SELECTED'; elseif ($i==18) echo ' SELECTED'; echo'>#80FF80</OPTION>
							<OPTION VALUE="#FFFF80" style="background-color:black; color: #FFFF80;"';if (isset($color) AND $color=="#FFFF80") echo ' SELECTED'; elseif ($i==19) echo ' SELECTED'; echo'>#FFFF80</OPTION>
							<OPTION VALUE="#FF8080" style="background-color:black; color: #FF8080;"';if (isset($color) AND $color=="#FF8080") echo ' SELECTED'; elseif ($i==20) echo ' SELECTED'; echo'>#FF8080</OPTION>
							</SELECT>
						</td></tr>
						';
					}
					?>
				</table>
				<?
			}
	   }
	   elseif ($option_poll=='read' AND $rights['readpoll'])
	   {
			$sel_poll = myquery("SELECT * FROM forum_poll WHERE topic_id='$topic_id'");
			if ($sel_poll!=false and mysql_num_rows($sel_poll)>0)
			{
				$poll = mysql_fetch_array($sel_poll);
				if ($ajax==0)
				{
					?>
					<center>
					<span id="ajax_read_poll">
					<?
				}
				$str_poll = '
				<form name="read_poll" action="" method="post" onsubmit="request_poll()">
				<input type="hidden" name="actionpoll">
				<table bgcolor="#3C3C3C" border="1" width="80%" cellspacing=6 cellpadding=6><tr><td bgcolor="#1B1B1B">
					<table width="100%" border=0 cellspacing=0 cellpadding=0>';
					if (!$this->guest)
					{
						$already_vote = @mysql_result(@myquery("SELECT COUNT(*) FROM forum_poll_users WHERE poll_id=".$poll['poll_id']." AND user_id=".$this->char['user_id'].""),0,0);
					}
					else
					{
						$already_vote = 0;
					}
					if ($poll['status']!=1)
					{
						$str_poll.='<tr><td colspan="2" vailgn=middle align=center> <b><font color=#80FF00>Голосование закрыто!</font></b></td></tr>';
					}
					elseif ($already_vote!=0)
					{
						$str_poll.='<tr><td colspan="2" vailgn=middle align=center> <b><font color=#80FF00>Ты УЖЕ '.echo_sex('голосовал','голосовала').'. Спасибо за твой голос!</font></b></td></tr>';
					}
					else
					{
						$str_poll.='<tr><td colspan="2" vailgn=middle align=center> <b><font color=#FF0000>В теме создано голосование:</font></b></td></tr>';
					}
					$str_poll.='<tr><td colspan="2" align="center" valign="middle"><font color=#FFFFFF size=2><b>'.$poll['question'].'</b></font></td></tr>
					<tr><td>&nbsp;</td></tr>';

					if (!$this->guest AND $already_vote==0 AND $poll['status']==1)
					{
						$enable_vote=1;
					}
					else
					{
						$enable_vote=0;
					}

					$all_vote = mysql_result(myquery("SELECT SUM(votes) FROM forum_poll_data WHERE poll_id=".$poll['poll_id'].""),0,0);
					$sel_option = myquery("SELECT * FROM forum_poll_data WHERE poll_id=".$poll['poll_id']." ORDER BY option_id ASC");
					while ($opt_poll  = mysql_fetch_array($sel_option))
					{
						$str_poll.='<tr><td valign="middle">';
						if ($enable_vote==1 AND $rights['replypoll'])
						{
							$str_poll.='<input type="radio" name="vote_id" value="'.$opt_poll['option_id'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
						}
						else
						{
							$str_poll.='<input type="radio" style="visibility:hidden">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
						}
						if ($all_vote==0)
						{
							$proc = 0;
						}
						else
						{
							$proc = round($opt_poll['votes']/$all_vote*100,2);
						}
						$str_poll.='<font color='.$opt_poll['color'].'>'.$opt_poll['option_id'].') '.$opt_poll['option_text'].'</font></td><td width="200">Голосов - <font color='.$opt_poll['color'].'><b>'.$opt_poll['votes'].'</b>&nbsp;&nbsp;['.$proc.'%]</font></td></tr>';
					}
					$str_poll.='<tr><td valign="middle"></td><td width="200"><br>Всего голосов - <font color=#FFFFFF><b>'.$all_vote.'</b></font></td></tr>';
					if (!$this->guest)
					{
						$str_poll.='<input type="hidden" name="poll_id" value="'.$poll['poll_id'].'">';
						//кнопки для открытия/закрытия голосования
						if ($poll['status']==1)
						{
							if ($rights['closepoll'])
							{
								$str_poll.='<tr><td colspan="2"><input type="submit" name="сlose_vote" value="Закрыть голосование"></td></tr>';
							}
						}
						else
						{
							if ($rights['openpoll'])
							{
								$str_poll.='<tr><td colspan="2"><input type="submit" name="open_vote" value="Открыть голосование"></td></tr>';
							}
						}
						if ($enable_vote==1 AND $rights['replypoll'])
						{
							$str_poll.='<tr><td colspan="2" align="right"><br><br><input type="submit" name="vote" value="Проголосовать"></td></tr>';
						}
					}
					$str_poll.='</table></td></tr>
				</table></form>';
				if ($ajax!=0)
				{
					return $str_poll;
				}
				else
				{
					echo $str_poll;
				}
				echo '</span>';

				if (!$this->guest)
				{
					if ($this->char['clan_id']==1)
					{
						if (isset($_POST['view_poll']))
						{
							echo'<table bgcolor="#3C3C3C" border="1" width="80%" cellspacing=6 cellpadding=6><tr>';
							$sel_vote = myquery("SELECT * FROM forum_poll_data WHERE poll_id=".$poll['poll_id']." ORDER BY option_id ASC");
							$nom = 0;
							while ($opt_poll = mysql_fetch_array($sel_vote))
							{
								$nom++;
								echo '<td width="33%"><font color='.$opt_poll['color'].'><center><b>'.$opt_poll['option_id'].') '.$opt_poll['option_text'].'</b></center></font><br />';
								$sel_users = myquery("SELECT * FROM forum_poll_users WHERE poll_id=".$poll['poll_id']." AND option_id=".$opt_poll['option_id']." ORDER BY user_id ASC");
								while ($opt_user = mysql_fetch_array($sel_users))
								{
									$usr = @mysql_fetch_array(@myquery("(SELECT name,clevel,clan_id FROM game_users WHERE user_id='".$opt_user['user_id']."') UNION (SELECT name,clevel,clan_id FROM game_users_archive WHERE user_id='".$opt_user['user_id']."')"));
									echo '<br />'.$usr['name'].' ['.$usr['clevel'].'] ';
									if ($usr['clan_id']!=0) echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$usr['clan_id'].'.gif">';
								}
								echo '</td>';
								if ($nom==3)
								{
									echo '</tr><tr>';
									$nom=0;
								}
							}
							echo'</tr></table>';
						}
						if (!isset($_GET['page'])) $page='n';
						else $page = $_GET['page'];
						echo '<form name="view_polls" action="?act=topic&id='.$_GET['id'].'&page='.$page.'" method="POST"><input type="submit" name="view_poll" value="Показать детальную информацию о голосовании"></form>';
					}
				}
			}
		}
		echo'
		<br />
		<br />
		';
	}
	
	function PrintSearchUser() //вывод результатов поиска сообщений по игроку
	{
		if (!isset($_GET["searchuser"]) OR $_GET["searchuser"]=='') 
		{
			$str_search='';
			$name_search = '';
		}
		else
		{
			$str_search = trim($_GET["searchuser"]);
			list($name_search) = mysql_fetch_array(myquery("SELECT name FROM forum_name WHERE user_id=".(int)$str_search.""));
		}
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="29"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/1.gif"></td>
			<td style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><span style="color:white;font-size:11px;">Поиск обсуждений в Зале Палантиров по игроку <span style="color:red;font-weight:900;font-size:12px;"><?=$name_search;?></span></span></td>
			<td width="40" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			</tr>
		</table>
		<?
		$str_search = (int)$str_search;
		if ($str_search<=0)
		{
			echo 'Надо указать id пользователя для поиска';
			return;
		}
		else
		{

			$str_where = $this->MakePermissionForTopic();
			$str_query_select = "SELECT
			forum_topics.view AS view,
			forum_topics.id AS topic_id,
			forum_topics.otv AS otv, 
			forum_topics.top AS topic_top,
			forum_topics.text AS text,
			forum_topics.stat AS stat,
			forum_topics.user_id AS user_id,
			forum_topics.last_user AS last_user, 
			forum_topics.last_date AS last_date,
			forum_name.name AS last_name,
			forum_kat.name AS kat_name,
			forum_main.name AS main_name";
			$str_query_count = "SELECT COUNT(*)";
			$str_query_from = " FROM forum_kat,forum_topics,forum_otv,forum_name,forum_main
			WHERE
				(forum_kat.clan='0' AND
				forum_kat.id = forum_topics.kat_id AND
				forum_name.user_id=forum_topics.last_user AND
				forum_topics.id = forum_otv.topics_id AND
				(       forum_topics.user_id=$str_search OR
						forum_otv.user_id=$str_search
				) $str_where
				)
			GROUP BY forum_topics.id";

			$sel=myquery($str_query_count.$str_query_from);
			if (!isset($_GET['page'])) $page=1;
			else $page=(int)$_GET['page'];
			$line=25;
			$allpage=ceil(mysql_result($sel,0,0)/$line);
			if ($page>$allpage) $page=$allpage;
			if ($page<1) $page=1;
			$selpage=myquery($str_query_select.$str_query_from." order by forum_topics.last_date DESC limit ".(($page-1)*$line).", $line");
			 
			$this->PrintListTopic($selpage);
			?>
			<table style="width:100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td height="25" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/bg2.gif)" align="center">
				<?
				$href = "?act=searchuser&amp;searchuser=$str_search";
				echo'Страница: ';
				show_page($page,$allpage,$href);
				?>
				</td>
				</tr>
			</table>
			<?
		}
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="29"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/1.gif"></td>
			<td style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)">&nbsp;</td>
			<td width="40" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			</tr>
		</table>
		<?
	}
	
	function PrintHeader() //вывод заголовка форума
	{
		?>
		<html>
		<head>
<? 
require_once('../inc/engine.inc.php');
echo"<title>".GAME_NAME." :: Зал Палантиров</title>";
?>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
		<meta name="Keywords" content="">
		<style type="text/css">@import url("forum.css");</style>
		<link title="Обсуждения в Зале Палантиров" href="/index.php?option=rss_forum" type="application/rss+xml" rel="alternate" />
		<script language="JavaScript" type="text/javascript" src="bbcodes.js.php"></script>
		<script language="JavaScript" type="text/javascript" src="forum.js"></script>
		<?
		if ($this->forum_admin OR $this->user_rights['category']==4)
		{
			?>
			<script language="JavaScript" type="text/javascript" src="forum_admin.js"></script>
			<?
		}
		if (!$this->guest)
		{
			?>
			<script language="JavaScript" type="text/javascript" src="ajax.js"></script>
			<?
		}
		?>
		</head>
		<body>
		<?
		if (!$this->guest)
		{
			$char = $this->char;
			$user_id = $this->char['user_id'];
			include ("../lib/menu.php");
		}
	}
	
	function PrintLeftTable() //вывод левой главной части форума
	{
		?>
		<table cellpadding="0" cellspacing="0">
			<tr>
			<td width="80%" valign="top">
			<?
				if ($this->action!='kat' and $this->action!='topic')
				{
					?>
					<table style="width:100%;height:48px;background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/bg1.gif)" border="0" cellpadding="0" cellspacing="0">
					<tr>
					<td width="1%" height="48"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/9.gif"></td>
					<td width="97%">
					<?
					switch ($this->action)
					{
						default:
						{
							echo'<b>Привет, <font color="#ff0000">'.$this->char['name'].'</font>!</b>';
						}
					}
					?>
					<br><a href="index.php">Зал Палантиров</a></td>
					<td width="2%"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/8.gif"></td>
					</tr>
					</table>
					<?
				}
	}
	
	function PrintOnline()
	{
		?>
		<td valign="middle" align="center" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><span class="online_title">
		<?
		if (isset($_GET['act']) AND ($_GET['act']=='topic' OR $_GET['act']=='edittopic') AND isset($_GET['id']))
		{
			$id = (int)$_GET['id'];
			$sel = myquery("SELECT view_active_users.name FROM view_active_users,forum_online WHERE forum_online.last_active>=".(time()-3*60)." AND forum_online.user_id=view_active_users.user_id AND forum_online.topic_id=$id ORDER BY view_active_users.user_id");
			echo 'Сейчас в этом обсуждении';
		}
		else
		{
			echo 'Сейчас на форуме';
			$sel = myquery("SELECT view_active_users.name FROM view_active_users,forum_online WHERE forum_online.last_active>=".(time()-3*60)." AND forum_online.user_id=view_active_users.user_id ORDER BY view_active_users.user_id");
		}
		?>
		</span>
		<span class="online">
		<?
		$i=0;
		while (list($us) = mysql_fetch_array($sel))
		{
			$i++;
			echo $us;
			if ($i==mysql_num_rows($sel))
			{
				echo '.';
			}
			else
			{
				echo ', ';
			}
		}
		?>
		</span>
		<?
	}
	
	function PrintRightTable() //вывод нижней части левой таблицы и правой части с важными сообщениями
	{
		//подвал левой таблицы
		if (!$this->guest)
		{
			?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="29">
			<img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/1.gif">
			</td>

			<td valign="middle" align="center" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)">
			<form style="display:inline" name="read_all" action="?act=read_all" method="POST"><input type="button" value="Отметить весь форум как прочитанный" onclick="location.replace('<? echo $this->MakeRequestURL("markreadall")?>')"></form>

			<form style="display:inline" name="show_unread" action="?act=show_unread" method="POST"><input type="button" value="Показать все непрочитанные темы" onclick="location.replace('?act=show_unread')"></form>
			</td>
			<td width="40" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)">
			<img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif">
			</td>
			</tr></table>
			<?
		}
		//список игроков на форуме
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="29">
			<img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/1.gif">
			</td>
			<?
			$this->PrintOnline();
			?>
			</td>
			<td width="40" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)">
			<img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif">
			</td>
		</tr></table>
		<?
		//покажем банеры
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td style="height:23px;background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/topbg.gif);text-align:center;">Банеры</td>
			</tr>
		</table>
		<table style="width:100%;height:48px;" border="0" cellpadding="0" cellspacing="0">
			<tr>
			<td style="text-align:center;">
			<?
			include('../lib/banners.php');
			?>
			</td>
			</tr>
		</table>
		<?
		//правая таблица
		?>
			</td>
			<td style="border-left:1px black solid;" width="20%" valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td height="23" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/topbg.gif)" align="center">Поиск обсуждений:</td>
					</tr>
				</table>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td style="border:1px solid #282828">
						<center>
						<form action="?act=search" method="get">
						<input style="font-size:10px;height:18px;"name="search" type="text" value="" size="29" maxlength="100"><input name="act" type="hidden" value="search"><input name="submit" type="submit" value="Найти">
						</form>
						<a href="?act=rules">Правила поведения<br>в Зале Палантиров</a>
						</center>
					</td>
					</tr>
				</table>
				<?
				$sel=myquery("
				SELECT forum_topics.top,forum_topics.id,forum_name.name 
				FROM forum_pinned,forum_topics,forum_name
				WHERE forum_topics.id=forum_pinned.topic_id AND forum_name.user_id=forum_topics.last_user
				ORDER BY forum_pinned.last_date DESC
				");
				if (mysql_num_rows($sel))
				{
					?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
						<td height="23" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/topbg.gif)" align="center">
							<span class="attention_header">Важные обсуждения:</span>
						</td>
						</tr>
					</table>

					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
						<td style="border:1px solid #282828">
							<table width="100%" border="0" cellpadding="2">
								<tr><td width="20%">Автор:</td><td>Тема:</td></tr>
								<?
								while ($pinned=mysql_fetch_array($sel))
								{
									$last_name = $pinned['name'];
									echo '<tr><td valign=top>'.$last_name.'</td><td><a href="?act=topic&amp;id='.$pinned['id'].'&amp;page=n">'.stripslashes($pinned['top']).'</a></td></tr>';
								}
								?>
							</table>
						</td>
						</tr>
					</table>
					<?
				}
				?>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td height="23" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/topbg.gif)" align="center"><? if (!$this->guest) {?><input type="button" value="Мои настройки" name="setup" onclick="location.replace('index.php?act=setup')"><?}?></td>
					</tr>
				</table>
				
			</td>
			</tr>
		</table>
		<?
	}
	
	function PrintFooter() //печать подвала форума
	{
		global $MyTimer,$numsql,$time_mysql_query;
		if (!$this->guest)
		{
			if ($this->char['clan_id']==1)
			{
				$exec_time = $MyTimer->GetTime(5);
				echo '<div class="debug">Время вып.скрипта: <span class="style2">' . $exec_time . '</span> сек   |   Количество запросов: <span class="style2">'.$numsql.'</span>   |   Время запросов: <span class="style2">' . $time_mysql_query . '</span> сек ('.round($time_mysql_query*100/$exec_time,2).'%)</div>';
			}
		}

		if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
		{
			show_debug();
		}
		?>
		</body>
		</html>
		<?
 
	}
	
	function PrintSetup()
	{
		list($rego_time) = mysql_fetch_array(myquery("SELECT rego_time FROM game_users_data WHERE user_id=".$this->char['user_id'].""));
		if ($rego_time==0) $rego_time = mktime(12,0,0,7,15,2004);
		$reg_date = date("d.m.Y",$rego_time);
		$kol_day = floor((time()-$rego_time)/(60*60*24));
		$kol_posts = mysqlresult(myquery("SELECT kol_posts FROM forum_setup WHERE user_id=".$this->char['user_id'].""),0,0);
		if ($kol_day==0) $kol_day=1;
		$avg = round($kol_posts/$kol_day,2);
		?>
		<fieldset style="margin-left:55px;width:450px;margin-bottom:30px;padding:15px;">
			<legend><b>Статистика твоего участия в Зале Палантиров</b></legend>
			<table width="100%" cellspacing="3">
				<tr>
					<td width="70%"><b>Ты <?=echo_sex('отвечал','отвечала');?>:</b></td>
					<td width="30%"><?=$kol_posts;?> раз</td>
				</tr>
				<tr>
					<td width="70%"><b>Ты <?=echo_sex('зарегистрирован','зарегистрирована');?></b></td>
					<td width="30%"><?=$reg_date;?></td>
				</tr>
				<tr>
					<td width="70%"><b>Среднее кол-во ответов в день:</b></td>
					<td width="30%"><?=$avg;?></td>
				</tr>
			</table>
		</fieldset>
		<br /><br /><br />
		<fieldset style="margin-left:55px;width:450px;margin-bottom:30px;padding:15px;">
			<legend><b>Настройка просмотра Зала Палантиров</b></legend>
			<form name="setup" method="POST">
			<input type="checkbox" name="show_avatar" <? if ($this->setup['show_avatar']==1) { echo ' checked';};?>>Показывать аватары игроков в обсуждениях Зала Палантиров<br /><br />
			<input type="checkbox" name="show_podpis" <? if ($this->setup['show_podpis']==1) { echo ' checked';};?>>Показывать подписи игроков в обсуждениях Зала Палантиров<br /><br />
			Показывать по <input type="text" name="reply" size="5" maxsize="2" value="<?=$this->setup['reply'];?>"> ответов на каждой странице обсуждения<br /><br />
			Твоя подпись в сообщениях (макс. 100 символов) <br /><input type="text" name="podpis" size="63" maxsize="100" value="<?=$this->setup['podpis'];?>"><br /><br /><br />
			<input type="submit" name="setup" value="Сохранить настройки">
			</form>
		</fieldset>
		<br /><br /><br />
		<?
	}
	
	function PrintEditReply($id)
	{
		$id=(int)$id;
		$rights_reply = $this->CheckReplyRights($id);
		if ($rights_reply['edit'])  
		{
			$reply = mysql_fetch_array(myquery("SELECT * FROM forum_otv WHERE id=$id"));
			$this->PrintReply('Сохранить',2,stripslashes(htmlspecialchars_decode($reply['text'])),$id); 
		}
	}
	
	function PrintReply($nazv_knopki='Ответить',$new_tema=0,$text='',$topic_id=0,$title='') //вывод на экран ответа в топике
	{
		if ($this->guest) return;
		
		if ($this->char['clevel']>0 OR (isset($_GET['act']) AND $_GET['act']=='topic' AND isset($_GET['id']) AND $_GET['id']=='1006')) {}
		else
		{
			echo '<span class="style2">Игрокам с 0 уровнем запрещено писать на форуме</span>';
			return;
		}
		?>
		<a name="anchor1">&nbsp;</a>
		<form name="frm" id="frm" action="index.php" method="post">
		<?
		if ($topic_id==$this->barier_id AND $new_tema==1)
		{
			?>
			<script type="text/javascript">
			/* URL to the PHP page called for receiving suggestions for a keyword*/
			var getFunctionsUrl = "../suggest/suggest.php?keyword=";
			</script>
			<center><link href="../suggest/suggest.css" rel="stylesheet" type="text/css">
			<script type="text/javascript" src="../suggest/suggest.js"></script>
			<br /><b><font color="yellow" size="2">Данный раздел форума предназначен для публичного общения с одним игроком.</font></b><br />
			<div id="content" onclick="hideSuggestions();">Введите имя игрока:
			&nbsp;<font size="1" face="Verdana" color="#ffffff"><input type="text" size="50" id="keyword" name="to_user_name" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
			</div><script>init();</script></center><br /><br />
			<?
		}
		?>

		<table width="100%">
			<tr>
			<td style="width:145px;">&nbsp;</td>
			<td><a style="cursor:url('/images/nav/hand.cur'), pointer;font-weight:800;" onmouseover="copyQ();" onclick="pasteQ();">Выделите текст и нажмите сюда для цитирования</a></td>
			</tr>
		</table>

		<table width="100%" border="0" cellspacing="0" cellpadding="2">
			<tr>
			<td style="width:145px;">&nbsp;</td>
			<td colspan="20">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td nowrap="nowrap"><span class="genmed">&nbsp;Шрифт
						<select name="addbbcode26" onChange="bbfontstyle('[font=' + this.form.addbbcode26.options[this.form.addbbcode26.selectedIndex].value + ']', '[/font]')">
						<option value="arial" class="genmed" style="font-family:arial;font-size:12px">Arial</option>
						<option value="tahoma" class="genmed" style="font-family:tahoma;font-size:12px">tahoma</option>
						<option value="verdana" selected class="genmed" style="font-family:verdana;font-size:12px">verdana</option>
						<option value="times" class="genmed" style="font-family:times;font-size:12px">times</option>
						<option value="courier" class="genmed" style="font-family:courier;font-size:12px">courier</option>
                        <option value="Courier New" class="genmed" style="font-family:Courier New;font-size:12px">Courier New</option>
                        <option value="monospace" class="genmed" style="font-family:monospace;font-size:12px">monospace</option>
                        <option value="Comic Sans" class="genmed" style="font-family:Comic Sans;font-size:12px">Comic Sans</option>
                        <option value="serif" class="genmed" style="font-family:serif;font-size:12px">serif</option>
                        <option value="sans-serif" class="genmed" style="font-family:sans-serif;font-size:12px">sans-serif</option>
                        <option value="cursive" class="genmed" style="font-family:cursive;font-size:12px">cursive</option>
						</select>
						&nbsp;Размер
						<select name="addbbcode24" onChange="bbfontstyle('[size=' + this.form.addbbcode24.options[this.form.addbbcode24.selectedIndex].value + ']', '[/size]')">
						<option value="9" style="background-color:#808080"><b>Маленький</b></option>
						<option value="10">size=10</option>
                        <option value="11">size=11</option>
                        <option value="12" style="background-color:#808080"><b>Обычный</b></option>
                        <option value="14">size=14</option>
                        <option value="16">size=16</option>
                        <option value="18" style="background-color:#808080"><b>Большой</b></option>
                        <option value="20">size=20</option>
                        <option value="22">size=22</option>
                        <option value="24" style="background-color:#808080"><b>Огромный</b></option>
						</select>
						&nbsp;Цвет
						<select name="addbbcode22" onChange="bbfontstyle('[color=' + this.form.addbbcode22.options[this.form.addbbcode22.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;">
						<option style="color:lightgrey; background-color: black" value="#444444" class="genmed">Default</option>
						<option style="color:darkred; background-color: black" value="darkred" class="genmed">Dark Red</option>
						<option style="color:red; background-color: black" value="red" class="genmed">Red</option>
						<option style="color:orange; background-color: black" value="orange" class="genmed">Orange</option>
						<option style="color:brown; background-color: black" value="brown" class="genmed">Brown</option>
						<option style="color:yellow; background-color: black" value="yellow" class="genmed">Yellow</option>
						<option style="color:green; background-color: black" value="green" class="genmed">Green</option>
						<option style="color:olive; background-color: black" value="olive" class="genmed">Olive</option>
						<option style="color:cyan; background-color: black" value="cyan" class="genmed">Cyan</option>
						<option style="color:blue; background-color: black" value="blue" class="genmed">Blue</option>
						<option style="color:darkblue; background-color: black" value="darkblue" class="genmed">Dark Blue</option>
						<option style="color:indigo; background-color: black" value="indigo" class="genmed">Indigo</option>
						<option style="color:violet; background-color: black" value="violet" class="genmed">Violet</option>
						<option style="color:white; background-color: black" value="white" class="genmed">White</option>
						</select>
						</span>
					</td>
					<td nowrap="nowrap" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="gensmall"><a href="javascript:bbstyle(-1)" class="genmed">Закрыть тэги</a></span></td>
					</tr>
				</table>
			</td>
			</tr>
            
            <tr>    
            <td style="width:145px;">&nbsp;</td>
            <td style="width:21px;"><input type="button" class="button" accesskey="b" name="addbbcode0" style="background-color:white; width: 22px; height:22px; background-image:url('http://<?=IMG_DOMAIN;?>/buttons/bold.gif')" onClick="bbstyle(0)" /></td>
            <td style="width:23px;"><span class="genmed"><input title="Жирный" type="button" class="button" accesskey="i" name="addbbcode2" style="background-color:white;width: 22px; height:22px; background-image:url('http://<?=IMG_DOMAIN;?>/buttons/italic.gif')" onClick="bbstyle(2)" /></span></td>
            <td style="width:23px;"><span class="genmed"><input title="Подчеркнутый" type="button" class="button" accesskey="u" name="addbbcode4" style="background-color:white; width: 22px; height:22px; background-image:url('http://<?=IMG_DOMAIN;?>/buttons/underline.gif')" onClick="bbstyle(4)" /></span></td>
            <td style="width:23px;"><span class="genmed"><input title="Зачеркнутый" type="button" class="button" accesskey="u" name="addbbcode46" style="background-color:white; width: 22px; height:22px; background-image:url('http://<?=IMG_DOMAIN;?>/buttons/s.gif')" onClick="bbstyle(46)" /></span></td>
            <td style="width:23px;"><span class="genmed"><input title="Выравнивание: лево" type="button" class="button" accesskey="u" name="addbbcode34" style="background-color:white; width: 22px; height:22px; background-image:url('http://<?=IMG_DOMAIN;?>/buttons/left.gif')" onClick="bbstyle(34)" /></span></td>
            <td style="width:23px;"><span class="genmed"><input title="Выравнивание: центр" type="button" class="button" accesskey="u" name="addbbcode36" style="background-color:white; width: 22px; height:22px; background-image:url('http://<?=IMG_DOMAIN;?>/buttons/center.gif')" onClick="bbstyle(36)" /></span></td>
            <td style="width:23px;"><span class="genmed"><input title="Выравнивание: право" type="button" class="button" accesskey="u" name="addbbcode38" style="background-color:white; width: 22px; height:22px; background-image:url('http://<?=IMG_DOMAIN;?>/buttons/right.gif')" onClick="bbstyle(38)" /></span></td>
            <td style="width:23px;"><span class="genmed"><input title="Выравнивание: по ширине" type="button" class="button" accesskey="u" name="addbbcode40" style="background-color:white; width: 22px; height:22px; background-image:url('http://<?=IMG_DOMAIN;?>/buttons/justify.gif')" onClick="bbstyle(40)" /></span></td>
            <td style="width:23px;"><span class="genmed"><input title="Горизонтальный разделитель" type="button" class="button" accesskey="h" name="addbbcode32" style="background-color:white; width: 22px; height:22px; background-image:url('http://<?=IMG_DOMAIN;?>/buttons/hr.gif')" onClick="bbstyle(32)" /></span></td>
            <td style="width:23px;"><span class="genmed"><input title="Нижний регистр" type="button" class="button" accesskey="h" name="addbbcode42" style="background-color:white; width: 22px; height:22px; background-image:url('http://<?=IMG_DOMAIN;?>/buttons/sub.gif')" onClick="bbstyle(42)" /></span></td>
            <td style="width:23px;"><span class="genmed"><input title="Верхний регистр" type="button" class="button" accesskey="h" name="addbbcode44" style="background-color:white; width: 22px; height:22px; background-image:url('http://<?=IMG_DOMAIN;?>/buttons/sup.gif')" onClick="bbstyle(44)" /></span></td>
            <td style="width:23px;"><span class="genmed"><input title="Цитата" type="button" class="button" accesskey="q" name="addbbcode6" style="background-color:white; width: 22px; height:22px; background-image:url('http://<?=IMG_DOMAIN;?>/buttons/quote.gif')" onClick="bbstyle(6)" /></span></td>
            <td style="width:23px;"><span class="genmed"><input title="Изображение" type="button" class="button" accesskey="p" name="addbbcode14"style="background-color:white; width: 22px; height:22px; background-image:url('http://<?=IMG_DOMAIN;?>/buttons/image.gif')"  onClick="bbstyle(14)" /></span></td>
            <td style="width:23px;"><span class="genmed"><input title="Код" type="button" class="button" accesskey="c" name="addbbcode8" style="background-color:white; width: 22px; height:22px; background-image:url('http://<?=IMG_DOMAIN;?>/buttons/code.gif')" onClick="bbstyle(8)" /></span></td>
            <td style="width:53px;"><span class="genmed"><input title="Список" type="button" class="button" accesskey="l" name="addbbcode10" value="List" style="background-color:white; width: 52px; height:22px; color: black" onClick="bbstyle(10)" /></span></td>
            <td style="width:43px;"><span class="genmed"><input title="Элемент списка" type="button" class="button" accesskey="o" name="addbbcode12" value="1." style="background-color:white; width: 42px; height:22px; color: black" onClick="bbstyle(12)" /></span></td>
            <td style="width:23px;"><span class="genmed"><input title="Ссылка" type="button" class="button" accesskey="u" name="addbbcode30" value="url" style="background-color:white; width: 22px; height:22px; background-image:url('http://<?=IMG_DOMAIN;?>/buttons/link.gif')" onClick="bbstyle(30)" /></span></td>
            <td style="width:23px;"><span class="genmed"><input title="Электроная почта" type="button" class="button" accesskey="e" name="addbbcode20" value="email" style="background-color:white; width: 22px; height:22px; background-image:url('http://<?=IMG_DOMAIN;?>/buttons/mail.gif')" onClick="bbstyle(20)" /></span></td>
            <td style="width:53px;"><span class="genmed"><input title="Скрытый" type="button" class="button" accesskey="l" name="addbbcode28" value="spoiler" style="background-color:white; width: 52px; height:22px; color: black" onClick="bbstyle(28)" /></span></td>
            <td>&nbsp;</td>
            </tr>
		</table>
        
		<table width="100%" border="0">
		<?php
		if ($new_tema==1 OR $new_tema==3)
		{
			echo '
			<tr>
			<td><span style="width:145px;"></span>Тема: <input tabindex="1" style="width:100%;" maxlength="255" name="top"
			';
			if ($new_tema==3)
			{
				echo ' value="'.$title.'"';
			}
			echo '
			></td>
			</tr>
			';
		}
		?>
		<tr>
			<td style="width:100%">
				<table style="width:100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td style="width:145px;">
					<table>
					<tr>
					<?php
					$dh = opendir('smile_press/');
					$i=0;
					while($file = readdir($dh))
					{
						if ($file=='.') continue;
						if ($file=='..') continue;
						$len=strlen($file)-4;
						$smile = substr($file,0,$len);
						$img = '<td height=35 align="center" valign="middle"><span style="cursor:url(\'/images/nav/hand.cur\'), pointer" onClick="insertsmile(\''.$smile.'\')"><img src=smile/'.$file.' border=0 alt='.$smile.'></span></td>';
						$i++;
						if ($i==3) { echo '</tr><tr>'; $i=0; };
						echo $img;
					}
					?>
					</tr>
					</table>
				</td>
				<td><textarea id="text" style="width:100%;height:expression(this.scrollHeight+4+'px');min-height:120px" name="text" rows="15" wrap="virtual" tabindex="2" class="post" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);"><?php if ($new_tema>=2) echo $text; ?></textarea></td>
				</tr>
				</table>
			</td>
			</tr>
			<tr>
			<td>
				
			<?
			//$new_tema:
			// 0 - форма быстрого ответа в топике
			// 1 - создание новой темы
			// 2 - редактирование ответа в топике
			// 3 - редактирование топика
			if ($new_tema==1 AND $topic_id!=$this->barier_id)
			{
				$this->PrintPoll($topic_id,'new');
			}
			elseif ($new_tema==3)
			{
				$this->PrintPoll($topic_id,'edit');
			}
			switch ($new_tema)
			{
				case 0:
				{
					echo '<input type="hidden" name="AddReply">';
					echo '<input type="hidden" name="topic_id" value="'.$topic_id.'">';
				}
				break;
				
				case 1:
				{
					echo '<input type="hidden" name="AddTopic">';
					echo '<input type="hidden" name="kat_id" value="'.$topic_id.'">';
				}
				break;
				
				case 2:
				{
					echo '<input type="hidden" name="EditReply"><script>focusReply();</script>';
					echo '<input type="hidden" name="reply_id" value="'.$topic_id.'">';
				}
				break;
				
				case 3:
				{
					echo '<input type="hidden" name="EditTopic"><script>focusReply();</script>';
					echo '<input type="hidden" name="topic_id" value="'.$topic_id.'">';
				}
				break;
			}
			?>
			<div align="right"><input style="width:150px;height:25px;" tabindex="3" type="submit" name="submit" value="<?php echo $nazv_knopki; ?>"></div>
			</td>
			</tr>
			<tr>
			<td>
			<br>
			<a style="float:left;" name="anchor" href="#anchor" onClick="show_window_smile();"><b>Открыть окно с доступными смайлами</b></a>
			<a style="float:right;" name="gotop" href="#top">Подняться вверх</a>
			</td>
			</tr>
		</table>
		</form>
		<?
	}
	
	function PrintRank($kol_posts)
	{
		$img = '<img src="http://'.IMG_DOMAIN.'/forum/img/pip.gif" alt="">';
		$str = '';
		
		if ($kol_posts<30)
		{
			$str='Новичок&nbsp;'.$img;
		}
		elseif ($kol_posts<150)
		{
			$str='Бывалый&nbsp;'.$img.$img;
		}
		elseif ($kol_posts<400)
		{
			$str='Завсегдатай&nbsp;'.$img.$img.$img;
		}
		elseif ($kol_posts<800)
		{
			$str='Постоялец&nbsp;'.$img.$img.$img.$img;
		}
		else
		{
			$str='Мудрец&nbsp;'.$img.$img.$img.$img.$img;
		}
		
		return '<br />'.$str.'<br />Сообщений: '.$kol_posts;
	}
	
	function PrintUser($otv) //Вывод сведений об авторе топика или ответа
	{
		$str_return='';
		if ($otv['name1']!=NULL OR $otv['name2']!=NULL)
		{
			if ($otv['name1']!=NULL)
			{
				$otv['name'] = $otv['name1'];
			}
			else
			{
				$otv['name'] = $otv['name2'];
			}
			if ($otv['sklon1']!=NULL)
			{
				$otv['sklon'] = $otv['sklon1'];
			}
			else
			{
				$otv['sklon'] = $otv['sklon2'];
			}
			if ($otv['clevel1']!=NULL)
			{
				$otv['clevel'] = $otv['clevel1'];
			}
			else
			{
				$otv['clevel'] = $otv['clevel2'];
			}
			if ($otv['race1']!=NULL)
			{
				$otv['race'] = $otv['race1'];
			}
			else
			{
				$otv['race'] = $otv['race2'];
			}
			if ($otv['avatar1']!=NULL)
			{
				$otv['avatar'] = $otv['avatar1'];
			}
			elseif (isset($otv['avatar2']))
			{
				$otv['avatar'] = $otv['avatar2'];
			}
			else
			{
				$otv['avatar'] = 'no_avatar.gif';
			}
			if ($otv['clan_id1']!=NULL)
			{
				$otv['clan_id'] = $otv['clan_id1'];
			}
			else
			{
				$otv['clan_id'] = $otv['clan_id2'];
			}
			$font_color = "#F0F0F0";
			if ($otv['sex']=='male') {$font_color = "#79FFFF";}
			elseif ($otv['sex']=='female') {$font_color = "#FF80FF";}
			$str_return.='<div style="text-align:center">
			<span style="cursor:url(\'/images/nav/hand.cur\'), pointer;font-weight:800;color:'.$font_color.'" onClick="cha(\''.$otv['name'].'\')">'.$otv['name'].' ['.$otv['clevel'].']</span><br>';
			if ($this->setup['show_avatar']==1)
			{
				$str_return.='<span style="cursor:url(\'/images/nav/hand.cur\'), pointer;"';
				if (!$this->guest) 
				{
					$str_return.=' onClick="cha(\''.$otv['name'].'\')"';
				}
				$str_return.='><img alt="" src="http://'.IMG_DOMAIN.'/avatar/'.$otv['avatar'].'"></span><br>';
			}
			$kol_posts = 0;
			//if (!$this->guest)
			//{
			//    $kol_posts = mysql_result(myquery("SELECT COUNT(*) FROM forum_otv WHERE user_id=".$otv['user_id'].""),0,0)+mysql_result(myquery("SELECT COUNT(*) FROM forum_topics WHERE user_id=".$otv['user_id'].""),0,0);
			//}
			$say_thanks = (int)$otv['say_thanks'];
			$thanks_count = (int)$otv['thanks_count'];
			$thanks_post = (int)$otv['thanks_post'];
			$str_return.="</div><div style=\"text-align:center;width:200px:border-width:1px;border-color:#C0C0C0;border-style:dotted\">Сказал спасибо: ".$say_thanks." ".pluralForm($say_thanks,"раз","раза","раз")."<br>Поблагодарили ".$thanks_count." ".pluralForm($thanks_count,"раз","раза","раз")." в ".$thanks_post." ".pluralForm($thanks_post,"сообщении","сообщениях","сообщениях");
			$str_return.='<br /><a href="http://'.DOMAIN.'/view/?userid='.$otv['user_id'].'" target="_blank"><img alt="Инфо" title="Инфо" src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0></a>';
			if ($otv['clan_id']!='0') $str_return.='  Клан: <a href="http://'.DOMAIN.'/view/?clan='.$otv['clan_id'].'" target="_blank"><img alt="" src="http://'.IMG_DOMAIN.'/clan/'.$otv['clan_id'].'.gif" border=0></a>';
			$str_return.=print_sklon($otv,0,1);
			if (!$this->guest)
			{
				$str_return.='    <a href="http://'.DOMAIN.'/act.php?func=pm&amp;pm=write&amp;new&amp;komu='.urlencode($otv['name']).'"><img title="Отправить личное письмо игроку" alt="Отправить личное письмо игроку" src="http://'.IMG_DOMAIN.'/pm/new_pm.gif" border=0></a>   <a href="?act=searchuser&amp;searchuser='.urlencode($otv['user_id']).'"><img alt="" title="Найти все сообщения игрока" src="http://'.IMG_DOMAIN.'/forum/img/icon1.gif" border="0"></a>';
			}
			$str_return.='&nbsp;&nbsp;&nbsp;<a name="gotop" href="#top"><img src="http://'.IMG_DOMAIN.'/forum/img/top.gif" title="Подняться вверх" alt="Подняться вверх" border="0"></a>';
			if ($otv['kol_posts']>0)
			{
				$str_return.=$this->PrintRank($otv['kol_posts']);
			}
			$str_return.='</div>';
		}
		else
		{
			$str_return.='<center><font color="CCCCCC"><b>Игрок не найден</b></font><br />';
			$str_return.='&nbsp;&nbsp;&nbsp;<a name="gotop" href="#top"><img src="http://'.IMG_DOMAIN.'/forum/img/top.gif" title="Подняться вверх" alt="Подняться вверх" border="0"></a>';
		}
		return $str_return;
	}
	
	function PrintButtonTheme($topic,$rights) //печать кнопок в заголовке топика
	{
		?>
		<table style="width:100%;border:0px" cellspacing="0" cellpadding="0">
			<tr>
			<td style="width:40px;background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			<td style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif);padding-top:2px;text-align:right;">&nbsp;
			<?
			if ($rights['thanks']) 
            {
                echo '  <input type="button" value="Сказать \'Спасибо\'" onclick="location.replace(\'?act=topic&id='.$_GET['id'];
                if (isset($_GET['page'])) echo '&page='.$_GET['page'];
                echo '&saythanks_topic='.$_GET['id'].'\')">';
            }
			if ($rights['edit'])
			{
				echo '  <input type="button" value="Редактировать" onclick="location.replace(\'?act=edittopic&id='.$topic['id'].'\')">';
			}
			?>
			</td>
			<td style="width:29px"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/1.gif"></td>
			</tr>
		</table>
		<?
	}
	
	function PrintButton($otv) //печать кнопок в ответах топика
	{
		$str_return = '';
		$rights_reply = $this->CheckReplyRights($otv['id']);
		if (isset($_GET['page']))
		{
			$page = $_GET['page'];
		}
		else
		{
			$page = 'n';
		}

		$str_return.='
		<table style="width:100%;border:0px" cellspacing="0" cellpadding="0">
			<tr>
			<td style="width:40;background-image:url(http://'.IMG_DOMAIN.'/forum/menu/2.gif)"><img alt="" src="http://'.IMG_DOMAIN.'/forum/menu/3.gif"></td>
			<td style="background-image:url(http://'.IMG_DOMAIN.'/forum/menu/2.gif);padding-top:2px;text-align:right;">&nbsp;';
			if ($rights_reply['thanks']) $str_return.='  <input type="button" value="Сказать \'Спасибо\'" onclick="location.replace(\'?act=topic&id='.$_GET['id'].'&page='.$page.'&saythanks_post='.$otv['id'].'#otvet'.$otv['id'].'\')">  ';			
			if ($rights_reply['edit'])
			{
				$str_return.='<input type="button" value="Редактировать" onclick="location.replace(\'?act=edit&amp;id='.$otv['id'].'\')">';
			}
			
			if ($rights_reply['delete'] OR $rights_reply['fulldelete'] )
			{
				if ($otv['del']=='1')
				{
					if ($rights_reply['fulldelete'])
					{ 
						$str_return.='  <input type="button" value="Удалить окончательно" onclick="location.replace(\'?fulldelete_reply&amp;id='.$otv['id'].'&page='.$page.'\')">';
					}
					$str_return.='  <input type="button" value="Восстановить" onclick="location.replace(\'?restore_reply&amp;id='.$otv['id'].'&page='.$page.'#otvet'.$otv['id'].'\')">';
				}
				else 
				{
					$str_return.='  <input type="button" value="Удалить" onclick="location.replace(\'?delete_reply&amp;id='.$otv['id'].'&page='.$page.'#otvet'.$otv['id'].'\')">';
				}
				if ($this->forum_admin OR $this->user_rights['category']==4)
				{
					$str_return.='&nbsp;<img id="imgsel'.$otv['id'].'" height=12 width=12 src="http://'.IMG_DOMAIN.'/forum/img/topic_unselected.gif" alt="" onclick="un_select_reply(\'imgsel'.$otv['id'].'\',\''.IMG_DOMAIN.'\')">';
				}
			}
			$str_return.='
			</td>
			<td style="width:29px"><img alt="" src="http://'.IMG_DOMAIN.'/forum/menu/2.gif"><img alt="" src="http://'.IMG_DOMAIN.'/forum/menu/1.gif"></td>
			</tr>
		</table>';
		return $str_return;
	}
	
	function PrintRules() //печать правил форума
	{
		?>
		<table style="width:100%;height:48px;border:0px;background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/bg1.gif)" cellpadding="0" cellspacing="0">
			<tr>
			<td width="1%" height="48"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/9.gif"></td>
			<td width="97%" align="center" style="color:white;font-size:13px;font-weight:900;">Правила поведения в Зале Палантиров!</td>
			<td width="2%"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/8.gif"></td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="29"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/1.gif"></td>
			<td valign="middle" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)">&nbsp;</td>
			<td width="40" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="4" height="6" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/4.gif)"></td>
			<td width="771">
				<table width="100%" border="0" cellspacing="2" cellpadding="2">
					<tr>
					<td>
					<?
						include('rules.html');
					?>
					</td>
					</tr>
				</table>
			</td>
			<td width="4" height="6" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/4.gif)"></td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="29"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/1.gif"></td>
			<td valign="middle" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)">&nbsp;</td>
			<td width="40" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			</tr>
		</table>
		<?            
	}
	
	function PrintSearch() //печать результатов поиска
	{
		if (!isset($_GET["search"]) OR $_GET["search"]=='') 
		{
			$str_search='';
		}
		else
		{
			$str_search = trim($_GET["search"]);
		}
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="29"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/1.gif"></td>
			<td background="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><span style="color:white;font-size:11px;">Поиск обсуждений в Зале Палантиров по фразе: <span style="color:red;font-weight:900;font-size:12px;">"<?=$str_search;?>"</span></span></td>
			<td width="40" background="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			</tr>
		</table>
		<?
		if (strlen($str_search)<3)
		{
			echo 'Фраза для поиска должна быть не менее 3 символов';
			return;
		}
		else
		{
			$str_where = $this->MakePermissionForTopic();
			$str_query_select = "SELECT
			forum_topics.view AS view,
			forum_topics.id AS topic_id,
			forum_topics.otv AS otv, 
			forum_topics.top AS topic_top,
			forum_topics.text AS text,
			forum_topics.stat AS stat,
			forum_topics.user_id AS user_id,
			forum_topics.last_user AS last_user, 
			forum_topics.last_date AS last_date,
			forum_name.name AS last_name,
			forum_kat.name AS kat_name,
			forum_main.name AS main_name";
			$str_query_count = "SELECT COUNT(*)";
			$str_query_from = " FROM forum_kat,forum_topics,forum_otv,forum_name,forum_main
			WHERE
				(
				forum_kat.id = forum_topics.kat_id AND
				forum_main.id = forum_kat.main_id AND
				forum_name.user_id=forum_topics.last_user AND
				forum_topics.id = forum_otv.topics_id AND
				(       forum_topics.user_id IN (SELECT user_id FROM forum_name WHERE name LIKE '%".$str_search."%') OR
						forum_topics.text LIKE '%".$str_search."%' OR
						forum_topics.top LIKE '%".$str_search."%' OR
						forum_otv.user_id IN (SELECT user_id FROM forum_name WHERE name LIKE '%".$str_search."%') OR
						forum_otv.text LIKE '%".$str_search."%'
				) $str_where
				)
			GROUP BY forum_topics.id";

			$sel=myquery($str_query_select.$str_query_from);
			if (mysql_num_rows($sel)>0)
			{
				if (!isset($_GET['page'])) $page=1;
				else $page=(int)$_GET['page'];
				$line=25;
				$allpage=ceil(mysql_result($sel,0,0)/$line);
				if ($page>$allpage) $page=$allpage;
				if ($page<1) $page=1;
				$selpage=myquery($str_query_select.$str_query_from." order by forum_topics.last_date DESC limit ".(($page-1)*$line).", $line");
				 
				$this->PrintListTopic($selpage);
				?>
				<table style="width:100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td height="25" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/bg2.gif)" align="center">
					<?
					$href = "?act=search&amp;search=$str_search";
					echo'Страница: ';
					show_page($page,$allpage,$href);
					?>
					</td>
					</tr>
				</table>
				<?
			}
		}
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="29"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/1.gif"></td>
			<td background="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif">&nbsp;</td>
			<td width="40" background="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			</tr>
		</table>
		<?
	}
	
	function PrintUnread()
	{
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="29"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/1.gif"></td>
			<td background="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><span style="color:white;font-size:11px;">Все непрочитанные тобой темы</span></td>
			<td width="40" background="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			</tr>
		</table>
		<?
		$str_where = $this->MakePermissionForTopic();
		$str_query_select = "SELECT
		forum_topics.view AS view,
		forum_topics.id AS topic_id,
		forum_topics.otv AS otv, 
		forum_topics.top AS topic_top,
		forum_topics.text AS text,
		forum_topics.last_user AS last_user, 
		forum_topics.last_date AS last_date,
		forum_topics.stat AS stat,
		forum_topics.user_id AS user_id,
		forum_name.name AS last_name,
		forum_kat.name AS kat_name,
		forum_main.name AS  main_name";
		$str_query_count = "SELECT COUNT(*)";
		$str_query_from = " FROM forum_kat,forum_topics,forum_otv,forum_name,forum_main
		WHERE
			(
			forum_kat.id = forum_topics.kat_id AND
			forum_main.id = forum_kat.main_id AND
			forum_name.user_id=forum_topics.last_user AND
			forum_topics.id = forum_otv.topics_id AND
			forum_topics.id NOT IN (SELECT topic_id FROM forum_read WHERE user_id=".$this->char['user_id'].")
			$str_where
			)
		GROUP BY forum_topics.id";

		$sel=myquery($str_query_select.$str_query_from);
		if (mysql_num_rows($sel)>0)
		{
			if (!isset($_GET['page'])) $page=1;
			else $page=(int)$_GET['page'];
			$line=25;
			$allpage=ceil(mysql_result($sel,0,0)/$line);
			if ($page>$allpage) $page=$allpage;
			if ($page<1) $page=1;
			$selpage=myquery($str_query_select.$str_query_from." order by forum_topics.last_date DESC limit ".(($page-1)*$line).", $line");
			 
			$this->PrintListTopic($selpage);
			?>
			<table style="width:100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td height="25" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/bg2.gif)" align="center">
				<?
				$href = "?act=show_unread&amp;";
				echo'Страница: ';
				show_page($page,$allpage,$href);
				?>
				</td>
				</tr>
			</table>
		<?
		}
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="29"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img alt="" src="http://<?=IMG_DOMAIN;?>/forum/menu/1.gif"></td>
			<td background="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif">&nbsp;</td>
			<td width="40" background="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
			</tr>
		</table>
		<?
	}
	
	function PrintMoveTopic($topic_id)
	{
		$rights = $this->CheckTopicRights($topic_id);
		if ($rights['move'])
		{
			$sele = myquery("SELECT * FROM forum_topics WHERE id=$topic_id");
			$topic = mysql_fetch_array($sele);
			?>
			<table style="width:100%;height:48px;background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/bg1.gif)" border="0" cellpadding="0" cellspacing="0">
				<tr>
				<td style="width:1%;height:48px;"><img src="http://<?=IMG_DOMAIN;?>/forum/menu/9.gif" alt=""></td>
				<td width="97%">
					<a href="index.php">Зал Палантиров</a>  > <b><u>ПЕРЕМЕЩЕНИЕ ОБСУЖДЕНИЯ</u></b> <a href="?act=topic&amp;id=<?=$topic_id;?>"><?=$topic['top'];?></a>
				</td>
				<td width="2%"><img src="http://<?=IMG_DOMAIN;?>/forum/menu/8.gif" alt=""></td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td width="29"><img src="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif" alt=""><img src="http://<?=IMG_DOMAIN;?>/forum/menu/1.gif" alt=""></td>
				<td valign="middle" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)">&nbsp;</td>
				<td width="40" style="background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/2.gif)"><img src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif" alt=""></td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td style="width:4px;height:6px;background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/4.gif)"></td>
				<td>
					<table width="100%" border="0" cellspacing="2" cellpadding="2">
					<tr>
					<th>Выберите раздел форума, в который хочешь перенести тему</th>
					</tr>
					<?
					$i='1';
					$sel=myquery("select * from forum_kat WHERE clan='0' order by main_id,id");
					while ($w=mysql_fetch_array($sel))
					{
						echo '<tr><td valign=top><a href="?movetopic&amp;id='.$topic_id.'&amp;kat='.$w['id'].'">'.$i.' '.$w['name'].'&nbsp;('.$w['text'].')</a></td></tr>';
						$i++;
					}
					?>
					</table>
				</td>
				<td style="width:4px;height:0px;background-image:url(http://<?=IMG_DOMAIN;?>/forum/menu/4.gif)"></td>
				</tr>
			</table>
			<?
		}
	}
	
	function PrintNewTopic($kat_id) //Создание нового топика
	{
		$rights = $this->CheckCategoryRights($kat_id);
		if ($rights['new'])
		{
			$kateg = mysql_fetch_array(myquery("SELECT * FROM forum_kat WHERE id=$kat_id"));
			?>
			<table width="100%" height="48" border="0" cellpadding="0" cellspacing="0" background="http://<?=IMG_DOMAIN;?>/forum/menu/bg1.gif">
				<tr>
				<td width="1%" height="48"><img src="http://<?=IMG_DOMAIN;?>/forum/menu/9.gif"></td>
				<td width="97%">
				<br><a href="index.php">Зал Палантиров</a>  > <a href="?act=kat&id=<?=$kateg['id'];?>"><?=$kateg['name'];?></a> > Новое обсуждение
				</td>
				<td width="2%"><img src="http://<?=IMG_DOMAIN;?>/forum/menu/8.gif"></td>
				</tr>
			</table>

			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td width="29"><img src="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img src="http://<?=IMG_DOMAIN;?>/forum/menu/1.gif"></td>
				<td valign="middle" background="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><?=$kateg['name'];?></td>
				<td width="40" background="http://<?=IMG_DOMAIN;?>/forum/menu/2.gif"><img src="http://<?=IMG_DOMAIN;?>/forum/menu/3.gif"></td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
				<td width="4" height="6" background="http://<?=IMG_DOMAIN;?>/forum/menu/4.gif"></td>
				<td>
				<table width="100%" border="0" cellspacing="2" cellpadding="2">
					<tr>
					<td rowspan="4"></td><td valign="top" align="left">Новое обсуждение в категории: <?=$kateg['name'];?></td>
					</tr>
					<tr>
					<td>
					<?
					$this->PrintReply('Создать тему',1,'',$kat_id);
					?>
					</td>
					</tr>
				</table>
				</td>
				<td width="4" height="0" background="http://<?=IMG_DOMAIN;?>/forum/menu/4.gif"></td>
				</tr>
			</table>
			<?
		}
	}
	
	function PrintEditTopic($topic_id) //Редактирование топика
	{
		$topic_id=(int)$topic_id;
		$rights_topic = $this->CheckTopicRights($topic_id);
		if ($rights_topic['edit'])  
		{            
			$topic = mysql_fetch_array(myquery("SELECT * FROM forum_topics WHERE id=$topic_id"));
			$this->PrintReply('Сохранить',3,stripslashes(htmlspecialchars_decode($topic['text'])),$topic_id,stripslashes(htmlspecialchars_decode($topic['top']))); 
		}
	}

	//***************************************************************************************
	//****    МЕТОДЫ ДЕЙСТВИЯ НА ФОРУМЕ
	//***************************************************************************************
	
	//***************************************************************************************
	//****    РАБОТА С ТОПИКАМИ
	//***************************************************************************************
	function MarkReadAll() //служебная - отметка всех тем форум прочитанными
	{
		if (!$this->guest)
		{
			myquery("INSERT INTO `forum_read` (`user_id`, `topic_id`, `last_read_timestamp`) SELECT ".$this->char['user_id'].", `id`, `last_date` FROM `forum_topics` ON DUPLICATE KEY UPDATE `last_read_timestamp` = `last_date`;");
		}
	}
	
	function MarkUnread($topic_id) //служебная - сделать топик непрочитанным
	{
		if (!$this->guest)
		{
			myquery("DELETE FROM forum_read WHERE user_id=".$this->char['user_id']." AND topic_id=$topic_id");
		}
	}
	
	function MarkAttention($topic_id) //Снятие/установка пометки "Важно"
	{
		$rights = $this->CheckTopicRights($topic_id);
		if ($rights['pin'])
		{
			list($cur_priznak,$lastdate) = mysql_fetch_array(myquery("SELECT priznak,last_date FROM forum_topics WHERE id=$topic_id"));
			$cur_priznak = (int)$cur_priznak;
			if ($cur_priznak==0)
			{
				myquery("UPDATE forum_topics SET priznak=1 WHERE id=$topic_id");
				myquery("INSERT INTO forum_pinned (topic_id,last_date) VALUES ($topic_id,$lastdate)");
			}
			else
			{
				myquery("UPDATE forum_topics SET priznak=0 WHERE id=$topic_id");
				myquery("DELETE FROM forum_pinned WHERE topic_id=$topic_id");
			}
		}
	}
	
	function OpenCloseTopic($topic_id) //Открытие/закрытие топика
	{
		$rights = $this->CheckTopicRights($topic_id);
		if ($rights['openclose'])
		{
			list($stat) = mysql_fetch_array(myquery("SELECT stat FROM forum_topics WHERE id=$topic_id"));
			if ($stat=='open') $st='close';
			else $st='open';
			$up=myquery("UPDATE forum_topics SET stat='".$st."' WHERE id=$topic_id");
			setLocation("index.php?act=topic&id=$topic_id");
		}
	}
	
	function MoveTopic($topic_id,$kategory) //Перенос топика в другую категорию
	{
		$rights = $this->CheckTopicRights($topic_id);
		if ($rights['move'])
		{
			myquery("UPDATE forum_topics SET kat_id=$kategory WHERE id=$topic_id");
			setLocation("index.php?act=kat&id=$kategory");
		}
	}
	
	function DeleteTopic($id) //Удаление топика
	{
		$rights = $this->CheckTopicRights($id);
		if ($rights['delete'])
		{
			myquery("UPDATE forum_topics SET kat_id=0 WHERE id=$id");
			list($topic_user_id) = mysql_fetch_array(myquery("SELECT user_id FROM forum_topics WHERE id=$id"));
			myquery("UPDATE forum_setup SET kol_posts=GREATEST(kol_posts-1,0) WHERE user_id=$topic_user_id");
			$selthanks = myquery("SELECT user_id FROM forum_thanks WHERE topic_id=$id");
			while (list($thanks_user_id) = mysql_fetch_array($selthanks))
			{
				$this->DelThanksTopic($id,$thanks_user_id);      
			}
			$sel_user_reply = myquery("SELECT id,user_id FROM forum_otv WHERE topics_id=$id");
			while (list($post_id,$reply_user_id)=mysql_fetch_array($sel_user_reply))
			{
				myquery("UPDATE forum_setup SET kol_posts=GREATEST(kol_posts-1,0) WHERE user_id=$reply_user_id"); 
				$selthanks = myquery("SELECT user_id FROM forum_thanks WHERE post_id=$post_id");
				while (list($thanks_user_id) = mysql_fetch_array($selthanks))
				{
					$this->DelThanksPost($id,$thanks_user_id);      
				}
			}
			setLocation("index.php?act=kat&id=0");
		}
	}
	
	function AddTopic()
	{
		$location = "index.php";
		if (isset($_POST['kat_id']))
		{
			$kat_rights = $this->CheckCategoryRights($_POST['kat_id']);
			if (isset($_POST['text']) AND isset($_POST['top']) AND $kat_rights['new'])
			{
				$text=mysql_real_escape_string(htmlspecialchars($_POST['text']));
				$top=mysql_real_escape_string(htmlspecialchars($_POST['top']));
				
				$to_user_id=0;
				if (isset($_POST['to_user_name']))
				{
					$to_user_id = get_user("user_id",mysql_real_escape_string(htmlspecialchars($_POST['to_user_name'])),1);
					if ($to_user_id>0)
					{
						$top = "Для ".$_POST['to_user_name'].": ".$top;
						$msg = "На форуме в разделе \"К барьеру\" создана тема \"$top\" с обращением к тебе. Только автор темы и ты сможете отвечать в этой теме!";
						myquery("INSERT DELAYED INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('".$to_user_id."', '0', 'На форуме создана тема с обращением к тебе', '$msg','0','".time()."')");
					}
					else
					{
						echo 'Игрок '.$_POST['to_user_name'].' не найден!';
						exit;
					}
				}
				
				$ins=myquery("INSERT INTO forum_topics (kat_id,top,text,last_user,last_date,user_id,timepost,to_user_id) VALUES (".$_POST['kat_id'].",'$top','$text',".$this->char['user_id'].",".time().",".$this->char['user_id'].",".time().",$to_user_id)");
				$topic_id=mysql_insert_id();
				
				myquery("INSERT INTO forum_name (user_id,name) VALUES (".$this->char['user_id'].",'".$this->char['name']."') ON DUPLICATE KEY UPDATE name='".$this->char['name']."'");
				myquery("INSERT INTO forum_read (topic_id,user_id) VALUES ($topic_id,".$this->char['user_id'].")");
				$this->AddPoll($topic_id);
				myquery("UPDATE forum_setup SET kol_posts=kol_posts+1 WHERE user_id=".$this->char['user_id']."");
				$location="index.php?act=topic&id=$topic_id";
			}
			else
			{
				$location="index.php?act=kat&id=".$_POST['kat_id']."";
			}
		}
		setLocation("$location");
	}
	
	function EditTopic() //редактирование ответа
	{
		$location = "index.php";
		if (isset($_POST['topic_id']) and is_numeric($_POST['topic_id']))
		{
			$rights_topic = $this->CheckTopicRights($_POST['topic_id']);
			$location = "index.php?act=topic&id=".$_POST['topic_id']."";
			if (isset($_POST['text']) AND $_POST['text']!='' AND isset($_POST['top']) AND $_POST['top']!='' AND isset($_POST['submit']) AND $rights_topic['edit'])
			{
				$text=mysql_real_escape_string(htmlspecialchars($_POST['text'])).'\n\n[size=10][font=arial][color=darkblue]Последнее изменение:  [b]'.$this->char['name'].'[/b]  в  '.date("H:i:s    d-m-Y",time()).'[/color][/font][/size]';
				$top=mysql_real_escape_string(htmlspecialchars($_POST['top']));
				$ins=myquery("UPDATE forum_topics SET text='$text',top='$top' WHERE id=".$_POST['topic_id']."");
				
				if (isset($_POST['question']) AND $_POST['question']!='')
				{
					$edit_poll=true;
					$sel_poll = myquery("SELECT * FROM forum_poll WHERE topic_id='".(int)$_POST['topic_id']."'");
					if ($sel_poll!=false AND mysql_num_rows($sel_poll)>0)
					{
						$poll = mysql_fetch_array($sel_poll);
						$vote = mysql_result(myquery("SELECT COUNT(*) FROM forum_poll_users WHERE poll_id=".$poll['poll_id'].""),0,0);
						if ($vote>0)
						{
							$edit_poll = false;
						}    
						else
						{
							myquery("DELETE FROM forum_poll_data WHERE poll_id=".$poll['poll_id']."");
							myquery("DELETE FROM forum_poll WHERE poll_id=".$poll['poll_id']."");
						}
					}
					if ($edit_poll)
					{
						$this->AddPoll($_POST['topic_id']);
					}
				}
			}
		}
		setLocation("$location");
	}

	//***************************************************************************************
	//****    РАБОТА С ГОЛОСОВАНИЕМ
	//***************************************************************************************
	function ActionPoll($request_array)
	{
		if (!isset($request_array['poll_id']))
		{
			return;
		}
		list($topic_id) = mysql_fetch_array(myquery("SELECT topic_id FROM forum_poll WHERE poll_id=".$request_array['poll_id'].""));
		$rights = $this->CheckPollRights($topic_id);
		//Ответ в голосование
		if (isset($request_array['vote']) AND isset($request_array['vote_id']))
		{
			if ($rights['replypoll'])
			{
				$already_vote = mysql_result(myquery("SELECT COUNT(*) FROM forum_poll_users WHERE poll_id=".$request_array['poll_id']." AND user_id=".$this->char['user_id'].""),0,0);
				if ($already_vote == 0)
				{
					myquery("INSERT INTO forum_poll_users (user_id,poll_id,option_id) VALUES (".$this->char['user_id'].",".$request_array['poll_id'].",".$request_array['vote_id'].")");
					myquery("UPDATE forum_poll_data SET votes=votes+1 WHERE poll_id=".$request_array['poll_id']." AND option_id=".$request_array['vote_id']."");
				}
			}
		}

		//Закрытие голосования
		if (isset($request_array['сlose_vote']))
		{
			if ($rights['closepoll'])
			{
				myquery("UPDATE forum_poll SET status=0 WHERE poll_id=".$request_array['poll_id']."");
			}
		}

		//Открытие голосования
		if (isset($request_array['open_vote']))
		{
			if ($rights['openpoll'])
			{
				myquery("UPDATE forum_poll SET status=1 WHERE poll_id=".$request_array['poll_id']."");
			}
		}
	}
	
	function AddPoll($topic_id)
	{
		if (isset($_POST['question']) AND $_POST['question']!='' AND (isset($_POST['option1']) OR isset($_POST['option2']) OR isset($_POST['option3']) OR isset($_POST['option4']) OR isset($_POST['option5']) OR isset($_POST['option6']) OR isset($_POST['option7']) OR isset($_POST['option8']) OR isset($_POST['option9']) OR isset($_POST['option10']) OR isset($_POST['option11']) OR isset($_POST['option12']) OR isset($_POST['option13']) OR isset($_POST['option14']) OR isset($_POST['option15']) OR isset($_POST['option16']) OR isset($_POST['option17']) OR isset($_POST['option18']) OR isset($_POST['option19']) OR isset($_POST['option20'])))
		{
			$ins=myquery("INSERT INTO forum_poll (topic_id,question,status) VALUES ($topic_id,'".$_POST['question']."',1)");
			$poll_id=mysql_insert_id();
			
			$option_id=0;
			if (isset($_POST['option1']) AND $_POST['option1']!='')
			{
				$option_id++;
				if (!isset($_POST['color1'])) $_POST['color1']='ffffff';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option1']."','".$_POST['color1']."')");
			}
			if (isset($_POST['option2']) AND $_POST['option2']!='')
			{
				$option_id++;
				if (!isset($_POST['color2'])) $_POST['color2']='00ff00';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option2']."','".$_POST['color2']."')") or die(mysql_error());
			}
			if (isset($_POST['option3']) AND $_POST['option3']!='')
			{
				$option_id++;
				if (!isset($_POST['color3'])) $_POST['color3']='666666';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option3']."','".$_POST['color3']."')");
			}
			if (isset($_POST['option4']) AND $_POST['option4']!='')
			{
				$option_id++;
				if (!isset($_POST['color4'])) $_POST['color4']='ffff00';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option4']."','".$_POST['color4']."')");
			}
			if (isset($_POST['option5']) AND $_POST['option5']!='')
			{
				$option_id++;
				if (!isset($_POST['color5'])) $_POST['color5']='0066ff';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option5']."','".$_POST['color5']."')");
			}
			if (isset($_POST['option6']) AND $_POST['option6']!='')
			{
				$option_id++;
				if (!isset($_POST['color6'])) $_POST['color6']='990099';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option6']."','".$_POST['color6']."')");
			}
			if (isset($_POST['option7']) AND $_POST['option7']!='')
			{
				$option_id++;
				if (!isset($_POST['color7'])) $_POST['color7']='660033';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option7']."','".$_POST['color7']."')");
			}
			if (isset($_POST['option8']) AND $_POST['option8']!='')
			{
				$option_id++;
				if (!isset($_POST['color8'])) $_POST['color8']='ff0000';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option8']."','".$_POST['color8']."')");
			}
			if (isset($_POST['option9']) AND $_POST['option9']!='')
			{
				$option_id++;
				if (!isset($_POST['color9'])) $_POST['color9']='0000ff';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option9']."','".$_POST['color9']."')");
			}
			if (isset($_POST['option10']) AND $_POST['option10']!='')
			{
				$option_id++;
				if (!isset($_POST['color10'])) $_POST['color10']='cyan';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option10']."','".$_POST['color10']."')");
			}
			if (isset($_POST['option11']) AND $_POST['option11']!='')
			{
				$option_id++;
				if (!isset($_POST['color11'])) $_POST['color11']='#C0C0FF';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option11']."','".$_POST['color11']."')");
			}
			if (isset($_POST['option12']) AND $_POST['option12']!='')
			{
				$option_id++;
				if (!isset($_POST['color12'])) $_POST['color12']='#C0FFFF';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option12']."','".$_POST['color12']."')");
			}
			if (isset($_POST['option13']) AND $_POST['option13']!='')
			{
				$option_id++;
				if (!isset($_POST['color11'])) $_POST['color13']='#C0FFC0';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option13']."','".$_POST['color13']."')");
			}
			if (isset($_POST['option11']) AND $_POST['option14']!='')
			{
				$option_id++;
				if (!isset($_POST['color14'])) $_POST['color14']='#FFFFC0';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option14']."','".$_POST['color14']."')");
			}
			if (isset($_POST['option15']) AND $_POST['option15']!='')
			{
				$option_id++;
				if (!isset($_POST['color15'])) $_POST['color15']='#FFC0C0';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option15']."','".$_POST['color15']."')");
			}
			if (isset($_POST['option16']) AND $_POST['option16']!='')
			{
				$option_id++;
				if (!isset($_POST['color16'])) $_POST['color16']='#8080FF';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option16']."','".$_POST['color16']."')");
			}
			if (isset($_POST['option17']) AND $_POST['option17']!='')
			{
				$option_id++;
				if (!isset($_POST['color17'])) $_POST['color17']='#80FFFF';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option17']."','".$_POST['color17']."')");
			}
			if (isset($_POST['option18']) AND $_POST['option18']!='')
			{
				$option_id++;
				if (!isset($_POST['color11'])) $_POST['color11']='#80FF80';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option18']."','".$_POST['color18']."')");
			}
			if (isset($_POST['option19']) AND $_POST['option19']!='')
			{
				$option_id++;
				if (!isset($_POST['color19'])) $_POST['color19']='#FFFF80';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option19']."','".$_POST['color19']."')");
			}
			if (isset($_POST['option20']) AND $_POST['option20']!='')
			{
				$option_id++;
				if (!isset($_POST['color20'])) $_POST['color20']='#FF8080';
				$ins=myquery("INSERT INTO forum_poll_data (poll_id,option_id,option_text,color) VALUES ($poll_id,$option_id,'".$_POST['option20']."','".$_POST['color20']."')");
			}
		}
	}
	
	//***************************************************************************************
	//****    РАБОТА С ОТВЕТАМИ
	//***************************************************************************************
	function DeleteEndReply($id) //Удаление ответа
	{
		$rights_reply = $this->CheckReplyRights($id);
		if (isset($_GET['page']))
		{
			$page = $_GET['page'];
		}
		else
		{
			$page = 'n';
		}

		if ($rights_reply['fulldelete'])
		{
			list($topic) = mysql_fetch_array(myquery("SELECT topics_id FROM forum_otv WHERE id=$id"));
			myquery("DELETE FROM forum_otv WHERE id=$id");    
			$sel1 = myquery("SELECT COUNT(*) FROM forum_otv WHERE topics_id=$topic AND del<>'1'");
			$otvetov = 0;
			if ($sel1!=false AND mysql_num_rows($sel1)>0)
			{
				$otvetov = mysql_result($sel1,0,0);
			}
			if ($otvetov!=0)
			{
				list($last_user) = mysql_fetch_array(myquery("SELECT user_id FROM forum_otv WHERE topics_id=$topic AND del<>'1' ORDER BY id DESC LIMIT 1"));           
				myquery("UPDATE forum_topics SET otv=$otvetov,last_user=$last_user WHERE id=$topic");
			}
			else
			{
				$last_user = 0;
				myquery("UPDATE forum_topics SET otv=$otvetov,last_user=user_id WHERE id=$topic");
			}
			setLocation("index.php?act=topic&id=$topic&page=".$page."#anchor1");
		}
	}
	
	function DeleteReply($id) //Удаление ответа
	{
		$rights_reply = $this->CheckReplyRights($id);
		if (isset($_GET['page']))
		{
			$page = $_GET['page'];
		}
		else
		{
			$page = 'n';
		}

		if ($rights_reply['delete'])
		{
			list($topic,$reply_user_id) = mysql_fetch_array(myquery("SELECT topics_id,user_id FROM forum_otv WHERE id=$id"));
			$rights_topic = $this->CheckTopicRights($topic);
			myquery("UPDATE forum_otv SET del='1' WHERE id=$id LIMIT 1"); 
			$otvetov = mysql_result(myquery("SELECT COUNT(*) FROM forum_otv WHERE topics_id=$topic AND del<>'1'"),0,0);
			if ($otvetov!=0)
			{
				list($last_user) = mysql_fetch_array(myquery("SELECT user_id FROM forum_otv WHERE topics_id=$topic AND del<>'1' ORDER BY id DESC LIMIT 1"));           
				myquery("UPDATE forum_topics SET otv=$otvetov,last_user=$last_user WHERE id=$topic");
			}
			else
			{
				$last_user = 0;
				myquery("UPDATE forum_topics SET otv=$otvetov,last_user=user_id WHERE id=$topic");
			}
			myquery("UPDATE forum_setup SET kol_posts=GREATEST(kol_posts-1,0) WHERE user_id=$reply_user_id");
			$selthanks = myquery("SELECT user_id FROM forum_thanks WHERE post_id=$id");
			while (list($thanks_user_id) = mysql_fetch_array($selthanks))
			{
				$this->DelThanksPost($id,$thanks_user_id);      
			}
			if ($rights_topic['showdelete'])
			{
				setLocation("index.php?act=topic&id=$topic&page=".$page."#otvet$id");
			}
			else
			{
				setLocation("index.php?act=topic&id=$topic&page=".$page."#anchor1");
			}
		}
	}
	
	function RestoreReply($id) //Восстановление ответа
	{
		$rights_reply = $this->CheckReplyRights($id);
		if ($rights_reply['delete'])
		{
			list($topic,$reply_user_id) = mysql_fetch_array(myquery("SELECT topics_id,user_id FROM forum_otv WHERE id=$id"));
			$del = myquery("UPDATE forum_otv SET del='0' WHERE id=$id LIMIT 1");
			list($last_name) = mysql_fetch_array(myquery("SELECT user_id FROM forum_otv WHERE topics_id=$topic AND del<>'1' ORDER BY id DESC LIMIT 1"));
			myquery("UPDATE forum_topics SET otv=otv+1,last_user=$last_name WHERE id=$topic");
			myquery("UPDATE forum_setup SET kol_posts=kol_posts+1 WHERE user_id=$reply_user_id");
			setLocation("index.php?act=topic&id=$topic&page=".$_GET['page']."#otvet$id");
		}
	}
	
	function AddReply()
	{
		$location = "index.php";
		if (isset($_POST['topic_id']))
		{
			$location = "index.php?act=topic&id=".$_POST['topic_id']."#anchor1";
			$top = mysql_fetch_array(myquery("SELECT * FROM forum_topics WHERE id=".$_POST['topic_id'].""));
			$topic_rights = $this->CheckTopicRights($top['id']);
			if (isset($_POST['text']) AND $_POST['text']!='' AND isset($_POST['submit']) AND $topic_rights['reply'])
			{
				$text=mysql_real_escape_string(htmlspecialchars($_POST['text']));
				$ins=myquery("INSERT INTO forum_otv (topics_id,timepost,text,user_id) VALUES (".$top['id'].",".time().",'$text',".$this->char['user_id'].")");
				myquery("INSERT INTO forum_name (user_id,name) VALUES (".$this->char['user_id'].",'".$this->char['name']."') ON DUPLICATE KEY UPDATE name='".$this->char['name']."'");
				$upd=myquery("UPDATE forum_topics SET otv=otv+1,last_user=".$this->char['user_id'].",last_date=".time()." where id=".$top['id']."");
				$sel = myquery("SELECT COUNT(*) FROM forum_pinned WHERE topic_id=".$top['id']."");
				if (mysql_result($sel,0,0)>0)
				{
					myquery("UPDATE forum_pinned SET last_date=".time()." WHERE topic_id=".$top['id']."");
				}
				//myquery("DELETE FROM forum_read WHERE user_id<>".$this->char['user_id']." AND topic_id=".$top['id']."");
				myquery("UPDATE forum_setup SET kol_posts=kol_posts+1 WHERE user_id=".$this->char['user_id']."");
				$location = "index.php?act=topic&id=".$top['id']."&page=n#anchor1";
			}
		}
		setLocation("$location");
	}
	
	function EditReply() //редактирование ответа
	{
		$location = "index.php";
		if (isset($_POST['reply_id']))
		{
			$rights_reply = $this->CheckReplyRights($_POST['reply_id']);
			$reply = mysql_fetch_array(myquery("SELECT topics_id FROM forum_otv WHERE id=".$_POST['reply_id'].""));
			$location = "index.php?act=topic&id=".$reply['topics_id']."#otvet".$_POST['reply_id']."";
			if (isset($_POST['text']) AND $_POST['text']!='' AND isset($_POST['submit']) AND $rights_reply['edit'])
			{
				$text=mysql_real_escape_string(htmlspecialchars($_POST['text'])).'\n\n[size=10][font=arial][color=darkblue]Последнее изменение:  [b]'.$this->char['name'].'[/b]  в  '.date("H:i:s    d-m-Y",time()).'[/color][/font][/size]';
				$ins=myquery("UPDATE forum_otv SET text='$text' WHERE id=".$_POST['reply_id']."");
				$location = "index.php?act=topic&id=".$reply['topics_id']."#otvet".$_POST['reply_id']."";
			}
		}
		setLocation("$location");
	}
	
	function Moder($action,$replys)
	{
		$location ="index.php";
		if ($this->forum_admin OR $this->user_rights['category']==4)
		{
			$ar = explode(",",$replys);
			if (sizeof($ar)>0)
			{
				//В ar номера обрабатываемых ответов
				$action = (int)$action;
				for ($i=0;$i<sizeof($ar);$i++)
				{
					if ($i==0)
					{
						list($topic_id) = mysql_fetch_array(myquery("SELECT topics_id FROM forum_otv WHERE id=".$ar[$i].""));
					}
					if ($action==1)
					{
						$this->DeleteReply($ar[$i]);
					}
					if ($action==2)
					{
						$this->RestoreReply($ar[$i]);
					}
					if ($action==3)
					{
						$this->DeleteEndReply($ar[$i]);
					}
				}
				$location="index.php?act=topic&id=$topic_id";
			}
		}
		setLocation("$location");        
	}
	
	function ThanksPost($post_id)
	{
		$reply = $this->CheckReplyRights($post_id);
		if ($reply['thanks'])
		{
			$reply_user_id = mysqlresult(myquery("SELECT user_id FROM forum_otv WHERE id=$post_id"),0,0);
			myquery("INSERT INTO forum_thanks (post_id,user_id) VALUES ('$post_id','".$this->char['user_id']."')");
			$sel = myquery("SELECT * FROM forum_setup WHERE user_id=".$this->char['user_id']."");
			if (mysql_num_rows($sel)==0) myquery("INSERT INTO forum_setup (user_id) VALUES ('".$this->char['user_id']."')");
			myquery("UPDATE forum_setup SET say_thanks=say_thanks+1 WHERE user_id=".$this->char['user_id']."");
			$sel = myquery("SELECT * FROM forum_setup WHERE user_id=$reply_user_id");
			if (mysql_num_rows($sel)==0) myquery("INSERT INTO forum_setup (user_id) VALUES ('$reply_user_id')");
			$est_say = mysqlresult(myquery("SELECT COUNT(*) FROM forum_thanks WHERE post_id=$post_id"),0,0);
			if ($est_say == 1)
			{
				$kol_mes = 1;
			}
			else
			{
				$kol_mes = 0;
			}
			myquery("UPDATE forum_setup SET thanks_count=thanks_count+1,thanks_post=thanks_post+$kol_mes WHERE user_id=$reply_user_id");
		}     
	}
	function ThanksTopic($topic_id)
	{
		$reply = $this->CheckTopicRights($topic_id);
		if ($reply['thanks'])
		{
			myquery("INSERT INTO forum_thanks (topic_id,user_id) VALUES ('$topic_id','".$this->char['user_id']."')");
			$sel = myquery("SELECT * FROM forum_setup WHERE user_id=".$this->char['user_id']."");
			if (mysql_num_rows($sel)==0) myquery("INSERT INTO forum_setup (user_id) VALUES (".$this->char['user_id'].")");
			myquery("UPDATE forum_setup SET say_thanks=say_thanks+1 WHERE user_id=".$this->char['user_id']."");
			$est_say = mysqlresult(myquery("SELECT COUNT(*) FROM forum_thanks WHERE topic_id=$topic_id"),0,0);
			if ($est_say == 1)
			{
				$kol_mes = 1;
			}
			else
			{
				$kol_mes = 0;
			}
            $reply_user_id = mysqlresult(myquery("SELECT user_id FROM forum_topics WHERE id=$topic_id"),0,0);
			$sel = myquery("SELECT * FROM forum_setup WHERE user_id=$reply_user_id");
			if (mysql_num_rows($sel)==0) myquery("INSERT INTO forum_setup (user_id) VALUES ($reply_user_id)");
			myquery("UPDATE forum_setup SET thanks_count=thanks_count+1,thanks_post=thanks_post+$kol_mes WHERE user_id=$reply_user_id");
		}     
	}
	function DelThanksPost($post_id,$userid)
	{
		if (!$this->guest)
		{
			$reply_user_id = mysqlresult(myquery("SELECT user_id FROM forum_otv WHERE id=$post_id"),0,0);
			if ($reply_user_id!=$userid)
			{
				myquery("DELETE FROM forum_thanks WHERE post_id=$post_id AND user_id=".$userid."");
				$sel = myquery("SELECT * FROM forum_setup WHERE user_id=".$userid."");
				if (mysql_num_rows($sel)==0) myquery("INSERT INTO forum_setup (user_id) VALUES (".$userid.")");
				myquery("UPDATE forum_setup SET say_thanks=GREATEST(0,say_thanks-1) WHERE user_id=".$userid."");
				$reply_user_id = mysqlresult(myquery("SELECT user_id FROM forum_otv WHERE id=$post_id"),0,0);
				$est_say = mysqlresult(myquery("SELECT COUNT(*) FROM forum_thanks WHERE post_id=$post_id"),0,0);
				if ($est_say == 0)
				{
					$kol_mes = 1;
				}
				else
				{
					$kol_mes = 0;
				}
				$sel = myquery("SELECT * FROM forum_setup WHERE user_id=$reply_user_id");
				if (mysql_num_rows($sel)==0) myquery("INSERT INTO forum_setup (user_id) VALUES ($reply_user_id)");
				myquery("UPDATE forum_setup SET thanks_count=GREATEST(0,thanks_count-1),thanks_post=GREATEST(0,thanks_post-$kol_mes) WHERE user_id=$reply_user_id");
			}
		}     
	}
	function DelThanksTopic($topic_id,$userid)
	{
		if (!$this->guest)
		{
			$reply_user_id = mysqlresult(myquery("SELECT user_id FROM forum_topics WHERE id=$topic_id"),0,0);
			if ($reply_user_id!=$userid)
			{
				myquery("DELETE FROM forum_thanks WHERE topic_id=$topic_id AND user_id=".$userid."");
				$sel = myquery("SELECT * FROM forum_setup WHERE user_id=".$userid."");
				if (mysql_num_rows($sel)==0) myquery("INSERT INTO forum_setup (user_id) VALUES (".$userid.")");
				myquery("UPDATE forum_setup SET say_thanks=GREATEST(0,say_thanks-1) WHERE user_id=".$userid."");
				$est_say = mysqlresult(myquery("SELECT COUNT(*) FROM forum_thanks WHERE topic_id=$topic_id"),0,0);
				if ($est_say == 0)
				{
					$kol_mes = 1;
				}
				else
				{
					$kol_mes = 0;
				}
				$sel = myquery("SELECT * FROM forum_setup WHERE user_id=$reply_user_id");
				if (mysql_num_rows($sel)==0) myquery("INSERT INTO forum_setup (user_id) VALUES ($reply_user_id)");
				myquery("UPDATE forum_setup SET thanks_count=GREATEST(0,thanks_count-1),thanks_post=GREATEST(0,thanks_post-$kol_mes) WHERE user_id=$reply_user_id");
			}
		}     
	}
}
?>
