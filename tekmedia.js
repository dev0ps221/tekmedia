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
    req.open('post',`${ajaxpath}`);
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

function manager_select_elem(elem){
    console.log(elem.id+" is the selected media id")
    request_tmrender('replaceform',elem.id,updateformraw=>{
        console.log(updateformraw)
    })
}

function manager_select_media_events(elems){
    elems.forEach(
        elem=>
            elem.addEventListener(
                'click',e=>manager_select_elem(elem)
            )
    )
}

function initEvents(){
    const managerbox = document.querySelector("#tmmanager")
    if(managerbox){
        const actionsbox = managerbox.querySelector("#tmactions")
        const viewsbox = managerbox.querySelector("#tmviews") 
        if(actionsbox && viewsbox){
            const replacebox = actionsbox.querySelector('#replaceaction') 
            const tekmedias  = viewsbox.querySelectorAll('.tekmedia')
            manager_select_media_events(tekmedias)
        }

    }
}
function request_tmrender(render,args=[],cb){
    args = JSON.stringify(args)
    const formdata = new FormData()
    formdata.append('tmaction','getrender')
    formdata.append('render',render)
    formdata.append('args',args)
    const req = new XMLHttpRequest()
    req.open('post',`${ajaxpath}`);
    req.onload = function (event){
        if(typeof cb === 'function'){
            cb(req.responseText)
        }
    }
}
initEvents()