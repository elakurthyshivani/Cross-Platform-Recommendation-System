function toggleEditVsSave(clicked) {
    userShowButtons=document.getElementsByClassName("user-show-button");
    if(clicked!="Edit") {
        userShowButtons[0].style.display="inline";
        userShowButtons[1].style.display="none";
        userShowButtons[2].style.display="none";

        document.getElementsByClassName("input-rating")[0].style.display="none";
        document.getElementsByClassName("input-status")[0].style.display="none";
        
        document.getElementsByClassName("saved-rating")[0].style.display="block";
        document.getElementsByClassName("saved-status")[0].style.display="block";
    }
    else    {
        userShowButtons[0].style.display="none";
        userShowButtons[1].style.display="inline";
        userShowButtons[2].style.display="inline";

        document.getElementsByClassName("input-rating")[0].style.display="block";
        document.getElementsByClassName("input-status")[0].style.display="inline";
        
        document.getElementsByClassName("saved-rating")[0].style.display="none";
        document.getElementsByClassName("saved-status")[0].style.display="none";
    }
}

function validateRatingAndStatus()  {
    form=document.forms["searchForm"];
    if(form["rating"].value=="" && form["status"].value=="Not Yet Started") {
        toggleEditVsSave('Save');
        return false;
    }
    rating=parseFloat(form["rating"].value);
    if(rating<0 || rating>10)
        return false;
    return true;
}

function showHideRating()   {
    form=document.forms["searchForm"];
    selectStatus=document.getElementsByName("status")[0];
    inputStatus=document.getElementsByTagName("option")[selectStatus.selectedIndex].innerHTML;
    selectedStatus=document.getElementsByClassName("saved-status")[0].innerHTML;    
    if(inputStatus.search("Not Yet Started")!=-1 && selectedStatus.search("Not Yet Started")!=-1)
        document.getElementsByClassName("set")[1].style.visibility="hidden";
    else
        document.getElementsByClassName("set")[1].style.visibility="visible";
}