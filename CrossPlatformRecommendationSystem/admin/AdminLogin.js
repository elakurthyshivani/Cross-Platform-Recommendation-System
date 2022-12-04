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

function togglePasswordVisibility(visibilityButton) {
    console.log("Here")
    password=document.getElementsByName("admin_password")[0];
    if(password.getAttribute("type")=="password")    {
        password.setAttribute("type", "text");
        visibilityButton.innerHTML="visibility_off";
        visibilityButton.setAttribute("title", "Hide Password");
    }
    else    {
        password.setAttribute("type", "password");
        visibilityButton.innerHTML="visibility";
        visibilityButton.setAttribute("title", "Show Password");
    }
}