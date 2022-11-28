function showCard(num)  {
    var cards=[document.getElementsByClassName("fp-card-1")[0],
                document.getElementsByClassName("fp-card-2")[0],
                document.getElementsByClassName("fp-card-3")[0]];
    for(var i=0; i<cards.length; i++)   
        cards[i].style.display="none";
    cards[num-1].style.display="block";
}

function validateForgotPasswordCard1()  {
    form=document.forms["fpForm1"];
    return validateEmail(form["user_email"]); 
}

function validateForgotPasswordCard3()  {
    form=document.forms["fpForm3"];
    return validatePassword(form["user_password"], 2); 
}