// This functions unpacks the elements inside the template_id.
function show(template_id)  {
    var element=document.getElementById(template_id);
    element=element.content.cloneNode(true);
    document.body.appendChild(element);
}