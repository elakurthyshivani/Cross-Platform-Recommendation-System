function goToShow(showID)  {
    form=document.forms["search"];
    document.getElementsByName("show_id")[0].setAttribute("value", showID);
    form.submit();
}