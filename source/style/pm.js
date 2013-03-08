var ie  = document.all  ? 1 : 0;
function hl(cb)
{
	if (ie)
	{ 
		while (cb.tagName != "TR")
		{
			cb = cb.parentElement;
		}
	}
	else
	{
		 while (cb.tagName != "TD")
		 {
			 cb = cb.parentNode;
		 }
	}
		 
	cb.className = 'row1';
   
}


function dl(cb)
{
   if (ie)
   {
	   while (cb.tagName != "TR")
	   {
		   cb = cb.parentElement;
	   }
   }
   else
   {
	   while (cb.tagName != "TD")
	   {
		   cb = cb.parentNode;
	   }
   }
   cb.className = 'row2';
}


function InboxCheckAll(cb)
{
	var fmobj = document.mutliact;
	for (var i=0;i<fmobj.elements.length;i++)
	{
		var e = fmobj.elements[i];
		if ((e.name != 'allbox') && (e.type=='checkbox') && (!e.disabled))
		{
			e.checked = fmobj.allbox.checked;
			if (fmobj.allbox.checked)
			{
			   hl(e);
			}
			else
			{
			   dl(e);
			}
		}
	}
}


