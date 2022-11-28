function toggleLanguage(element)    {
    checkbox=element.children[0];
    if(checkbox.checked==false) {
        checkbox.setAttribute("checked", "checked");
        element.style.backgroundColor="#FFA381";
        element.style.fontWeight="bold";
    }
    else    {
        checkbox.removeAttribute("checked");
        element.style.backgroundColor="";
        element.style.fontWeight="normal";
    }
}

function togglePlatform(element)    {
    checkbox=element.children[0];
    logo=element.children[1];
    if(checkbox.checked==false) {
        checkbox.setAttribute("checked", "checked");
        logo.style.backgroundColor="#FFA381";
    }
    else    {
        checkbox.removeAttribute("checked");
        logo.style.backgroundColor="";
    }
}

function validateLanguagesAndPlatforms(x, y)    {
    result=true;
    if(atleastOneChecked("checkbox-language")==false)  {
        console.log("Hey");
        document.getElementsByClassName("form-error")[x].style.display="flex";
        result=false;
    }
    else    {
        document.getElementsByClassName("form-error")[x].style.display="none";
    }
    if(atleastOneChecked("checkbox-platform")==false) {
        document.getElementsByClassName("form-error")[y].style.display="flex";
        result=false;
    }
    else    {
        document.getElementsByClassName("form-error")[y].style.display="none";
    }
    return result;
}

function atleastOneChecked(className)    {
    checkboxes=document.getElementsByClassName(className);
    for(i=0; i<checkboxes.length; i++)  {
        if(checkboxes[i].checked==true)
            return true;
    }
    return false;
}