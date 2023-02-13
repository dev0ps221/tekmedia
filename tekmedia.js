const ajaxpath = `` 
function refreshuploadlist(){
    const formdata = new FormData()
    formdata.append('tmaction','getrender')
    formdata.append('render','uploadlist')
    const req = new XMLHttpRequest()
    req.open('post',`${ajaxpath}`);
    req.onload = function (event){
        if(document.querySelector('#tmuploads')){
            document.querySelector('#tmuploads').innerHTML = req.responseText
        }
    }
    req.send(formdata)

}

function initupload(e,form){
    e.preventDefault()
    const formdata = new FormData(form)
    const req = new XMLHttpRequest()
    req.open('post',`<?php echo $ajaxpath?>`);
    req.onload = function (event){
        if(req.response.match('success')){
            const uploadlist = document.querySelector("#tmuploads")
            if(uploadlist){
                refreshuploadlist()
            }
        }
    }
    req.send(formdata)
}