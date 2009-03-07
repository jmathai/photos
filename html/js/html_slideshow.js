function hotSpot(id)
{
  if($(id).style.display == 'block')
  {
    $(id).style.display = 'none';
  }
  else
  {
    $(id).style.display = 'block';
  }
}

function hotSpots(ids, display)
{
  for(i = 0; i < ids.length; i++)
  {
    $(ids[i]).style.display = display;
  }
}