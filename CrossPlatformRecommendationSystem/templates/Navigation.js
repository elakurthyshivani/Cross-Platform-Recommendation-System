function toggleSearchBar(searchForm)  {
    searchBar=searchForm.children[0];
    if(searchBar.style.display!="block") {
        searchBar.style.width="250px";
        searchBar.style.display="block";
    }
    else    {
        searchBar.style.width="0px";
        searchBar.style.display="none";
        return validateSearch(searchBar);
    }
    return false;
}

function validateSearch(search)   {
    // Remove special characters.
    search.value=search.value.replace(/[^\w\s]/gi, ' ');
	return true;
}