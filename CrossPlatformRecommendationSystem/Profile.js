function resizeProfilePhoto()   {
    profilePhoto=document.getElementsByClassName("profile-photo")[0];
    profilePhoto.style.height=profilePhoto.offsetWidth+"px";
}

function updateProfilePhoto(url)   {
    // console.log(url);
    document.getElementsByClassName("profile-photo")[0].style.backgroundImage="url("+url+")";
}

function closeDeleteMyAccount() {
    document.getElementsByClassName("delete-account-container")[0].style.display="none";
}

function openDeleteMyAccount() {
    document.getElementsByClassName("delete-account-container")[0].style.display="block";
}

function adjustPositionForDeleteContainer() {
    main=document.getElementsByTagName("main")[0];
    del=document.getElementsByClassName("delete-account-container")[0];
    bottom=main.scrollHeight-main.offsetHeight-45;
    del.style.bottom=-bottom+"px";
}

function choosePhoto(curr)  {
    curr.nextElementSibling.click();
}

function displayNewPhoto()  {
    photoContainer=document.getElementsByName("choose_photo")[0];
    photo=photoContainer.files[0];
    if(photo.type!="image/png" && photo.type!="image/jpeg" && photo.type!="image/jpg")  {
        photoContainer.nextElementSibling.style.display="flex";
        return;
    }
    url=URL.createObjectURL(photo);
    document.getElementsByClassName("profile-photo")[0].style.backgroundImage="url("+url+")";
	photoContainer.nextElementSibling.style.display="none";
}

function validateProfileForm()  {
    form=document.forms["profileForm"];
    x=validateName(form["user_name"]);
    y=validateEmail(form["user_email"]);
    z=validatePassword(form["user_password"], 3);
    a=validateLanguagesAndPlatforms(4, 5);
    b=validatePhoto();
    return x && y && z && a && b;
}

function validatePhoto()    {
    photoContainer=document.getElementsByName("choose_photo")[0];
    if(photoContainer.files.length==0)    
        return true;
    photo=photoContainer.files[0];
    if(photo.type!="image/png" && photo.type!="image/jpeg" && photo.type!="image/jpg")  {
        photoContainer.nextElementSibling.style.display="flex";
        return false;
    }
    photoContainer.nextElementSibling.style.display="none";
    return true;
}

function validateDeleteForm()  {
    form=document.forms["deleteForm"];
    return validatePassword(form["confirm_password"], 6);
}