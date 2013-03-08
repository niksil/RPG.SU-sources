function cha(name)
{
    document.frm.text.focus();
    document.frm.text.value=document.frm.text.value+"[b][color=yellow]"+name+"[/color][/b], ";
}
function show_window_smile()
{
    smile_window = window.open("smiles.php","SmileWindow","width=400,height=400");
    smile_window.focus();
}
function insertsmile(name)
{
    document.forms["frm"].text.focus();
    document.forms["frm"].text.value=document.forms["frm"].text.value+" :"+name+": ";
}
function focusReply()
{
    textarea = document.getElementById("text");
    if (textarea)
    {
        textarea.focus();
    }
}