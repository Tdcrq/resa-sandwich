function groupeForm(){ 
    var $groupeForm =  document.getElementById('groupeForm'); 
    $groupeForm.disabled = !$groupeForm.disabled; 
}

function groupeBouton(){ 
    var $groupeBouton =  document.getElementById('groupeBouton'); 
    $groupeBouton.disabled = !$groupeBouton.disabled; 
}

function groupeFormMdp(){ 
    var $groupeFormMdp =  document.getElementById('groupeFormMdp'); 
    $groupeFormMdp.disabled = !$groupeFormMdp.disabled; 
}

function groupeFormId(){ 
    var $groupeFormId =  document.getElementById('groupeFormId');
    $groupeFormId.disabled = !$groupeFormId.disabled;  
}

function message_delete() {
    var $msg="Message sur la ligne 1.nMessage sur la ligne 2.n...";
    alert($msg);
}