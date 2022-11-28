function togglePasswordVisibility(visibilityButton) {
    console.log("Here")
    password=document.getElementsByName("user_password")[0];
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


function validateName(name)	{
	if(!/^[A-z0-9 ]{1,100}$/.test(name.value))	{
		name.previousElementSibling.style.display="flex";
		return false;
	}
	name.previousElementSibling.style.display="none";
	return true;
}

function validateEmail(email)	{
	if(!/^[A-z0-9!#$%&+-._*]{1,100}@[A-z]{2,20}\.[A-z0-9]{1,5}$/.test(email.value))	{
		email.previousElementSibling.style.display="flex";
		return false;
	}
	email.previousElementSibling.style.display="none";
	return true;
}

function validatePassword(password, index) {
    if(!/^[A-z0-9_@$]{5,30}$/.test(password.value))	{
        document.getElementsByClassName("form-error")[index].style.display="flex";
		return false;
	}
    document.getElementsByClassName("form-error")[index].style.display="none";
	return true;
}