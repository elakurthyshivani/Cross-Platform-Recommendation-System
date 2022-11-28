function goToNextVCDigitElement(node)   {
    node=node.nextElementSibling;
    if(node!=null)
        node.focus();
    else
        return;
}

function showErrorMessage(index)    {
    if(index>=0 && index<=3)
        document.getElementsByClassName("form-error")[index].style.display="flex";
}

