function validateSignUpCard1()  {
    form=document.forms["signUpForm1"];
    //console.log(form["user_name"]);
    x=validateName(form["user_name"]);
    y=validateEmail(form["user_email"]);
    z=validatePassword(form["user_password"], 3);
    return x && y && z;   
}


function showCard(num)  {
    var cards=[document.getElementsByClassName("signup-card-1")[0],
                document.getElementsByClassName("signup-card-2")[0],
                document.getElementsByClassName("signup-card-3")[0]];
    for(var i=0; i<cards.length; i++)   
        cards[i].style.display="none";
    cards[num-1].style.display="block";
}