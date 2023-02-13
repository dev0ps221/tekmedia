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
            initEvents()
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

function manager_select_elem(elem,actionsbox){

    const replacebox = actionsbox.querySelector('#replaceaction')
    const deletebox = actionsbox.querySelector('#deleteaction')
    request_tmrender('replaceform',elem.id,updateformraw=>{
        replacebox.innerHTML = updateformraw
    })
    request_tmrender('deleteform',elem.id,deleteformraw=>{
        deletebox.innerHTML = deleteformraw
    })
}

function manager_select_media_events(elems,replacebox,actionsbox,viewsbox){
    elems.forEach(
        elem=>{
            elem.removeEventListener(
                'click',e=>manager_select_elem(elem,actionsbox)
            )
            elem.addEventListener(
                'click',e=>manager_select_elem(elem,actionsbox)
            )
        }
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
            manager_select_media_events(tekmedias,replacebox,actionsbox,viewsbox)
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
    req.send(formdata)
}
initEvents()