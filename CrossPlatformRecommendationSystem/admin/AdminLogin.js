function validateLogin()  {
    form=document.forms["loginForm"];
    y=validateEmail(form["admin_email"]);
    z=validatePassword(form["admin_password"], 1);
    return y && z;   
}

function showErrorMessage(index)    {
    if(index>=0 && index<=2)
        document.getElementsByClassName("form-error")[index].style.display="flex";
}