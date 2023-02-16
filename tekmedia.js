let ajaxpath = `/core/modules/mediamanager` 
const acb = []
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
function refreshselectlist(){
    const formdata = new FormData()
    formdata.append('tmaction','getrender')
    formdata.append('render','selectbox')
    const req = new XMLHttpRequest()
    req.open('post',`${ajaxpath}`);
    req.onload = function (event){
        if(document.querySelector('#tmselectbox')){
            document.querySelector('#tmselectbox').outerHTML = req.responseText
            initEvents()
        }
    }
    req.send(formdata)

}
function refreshvideolist(){
    const formdata = new FormData()
    formdata.append('tmaction','getrender')
    formdata.append('render','videolist')
    const req = new XMLHttpRequest()
    req.open('post',`${ajaxpath}`);
    req.onload = function (event){
        if(document.querySelector('#tmuploads')){
            document.querySelector('#tmuploads').innerHTML = req.responseText
            initEvents()
        }else if(document.querySelector("#tmselectbox")){
            document.querySelector('#tmselectbox').innerHTML = req.responseText
            initEvents()
        }
    }
    req.send(formdata)

}
function refreshimagelist(){
    const formdata = new FormData()
    formdata.append('tmaction','getrender')
    formdata.append('render','imagelist')
    const req = new XMLHttpRequest()
    req.open('post',`${ajaxpath}`);
    req.onload = function (event){
        if(document.querySelector('#tmuploads')){
            document.querySelector('#tmuploads').innerHTML = req.responseText
            initEvents()
        }else if(document.querySelector("#tmselectbox")){
            document.querySelector('#tmselectbox').innerHTML = req.responseText
            initEvents()
        }
    }
    req.send(formdata)

}


function sendform(e,form,cb=null){
    e.preventDefault()
    const formdata = new FormData(form)
    
    if(formdata.has('apath')){
        ajaxpath = formdata.get('apath')
    }
    const req = new XMLHttpRequest()
    req.open('post',`${ajaxpath}`);
    req.onload = function (event){
        if(req.response.match('success')){
            const uploadlist = document.querySelector("#tmuploads")
            if(uploadlist){
                refreshuploadlist()
            }
            const selectlist = document.querySelector("#tmselectbox")
            if(selectlist){
                refreshselectlist()
            }
            
            if(formdata.has('id') && formdata.get('tmaction') != 'dodelete'){
                const actionsbox = document.querySelector("#tmactions")
                if(actionsbox){
                    const replacebox = actionsbox.querySelector('#replaceaction')
                    const deletebox = actionsbox.querySelector('#deleteaction')
                    request_tmrender('replaceform',formdata.get('id'),updateformraw=>{
                        replacebox.innerHTML = updateformraw
                    })
                    request_tmrender('deleteform',formdata.get('id'),deleteformraw=>{
                        deletebox.innerHTML = deleteformraw
                    })
                }

            }
            if(formdata.has('id') && formdata.get('tmaction')=='dodelete'){
                if(document.querySelector('#tmactions .preview')){
                    document.querySelector('#tmactions .preview').innerHTML = ""
                }
            }

        }else{
            console.log(req.response)
        }
        if(cb && typeof cb != 'undefined'){
            cb(req)
        } 
        acb.forEach(
            cb=>{
                cb(req)
            }
        )
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

function selectbox_select_elem(elem,actionsbox){

    const selectbox = actionsbox.querySelector('#selectaction') 
    const preview = actionsbox.querySelector('.preview') 
    console.log((typeof select_ajaxpath) != 'undefined' ? select_ajaxpath : '', 'is ajaxpath')
    request_tmrender('select_elem',[elem.id,(((typeof select_ajaxpath) != 'undefined')?select_ajaxpath:null)],selectelemraw=>{
        selectbox.innerHTML = selectelemraw
    })
    request_tmrender('preview_elem',[elem.id],previewelemraw=>{
        preview.innerHTML = previewelemraw
    })
   
}

function selectbox_select_media_events(elems,actionsbox,viewsbox){
    elems.forEach(
        elem=>{
            elem.removeEventListener(
                'click',e=>selectbox_select_elem(elem,actionsbox)
            )
            elem.addEventListener(
                'click',e=>selectbox_select_elem(elem,actionsbox)
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
    const selectbox = document.querySelector("#tmselectbox")
    if(selectbox){
        const actionsbox = selectbox.querySelector("#tmactions")
        const viewsbox = selectbox.querySelector("#tmviews") 
        if(actionsbox && viewsbox){
            const tekmedias  = viewsbox.querySelectorAll('.tekmedia')
            selectbox_select_media_events(tekmedias,actionsbox,viewsbox)
        }

    }
}
function request_tmrender(render,args,cb){
    args = JSON.stringify(Array.isArray(args) ? args : [args])

    console.log(render)
    console.log('render args ',args)
    const formdata = new FormData()
    formdata.append('tmaction','getrender')
    formdata.append('render',render)
    formdata.append('args',args)
    console.log('render args ',formdata.get('args'))
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

const uploadlist = document.querySelector("#tmuploads")
if(uploadlist){
    refreshuploadlist()
}