function validateShows()    {
    showBoxes=document.getElementsByClassName("show-user-rating");
    formError=document.getElementsByClassName('form-error')[2];
    formError.style.display="none";
    for(i=0; i<showBoxes.length; i++)  {
        rating=showBoxes[i].children[0];
        if(rating.value!='')
            /*continue;
        rating=parseFloat(rating);
        if(rating<0 || rating>10)   {
            document.getElementsByClassName('form-error')[2].style.display="flex";*/
            return true;
        //}
    }
    formError.style.display="flex";
    formError.scrollIntoView(true);
    return false;
}