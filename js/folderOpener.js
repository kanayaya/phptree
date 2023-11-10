function openFolder(folder, folderId) {
    if (folder.getAttribute("opened") === 'true') {
        folder.setAttribute("opened", false)

        const name = folder.firstElementChild;
        folder.innerHTML = '';
        folder.append(name)


    } else {
        folder.setAttribute("opened", true)

        fetch('?method=get&parentId='+folderId, {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
            },
        })
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                console.log(data);
                try {
                    for (let i = 0; i < data.folders.length; i++) {
                        folder.innerHTML +=
                            '<div class="folder' + (data.folders[i].IS_EMPTY!=='0'? '' : '-full') + '">' +
                            '<p class="folder-name" onclick="openFolder(this.parentElement, ' + data.folders[i].ID + ')">' + data.folders[i]._NAME + '</p>' +
                            '</div>';
                    }
                    for (let i = 0; i < data.files.length; i++) {
                        folder.innerHTML +=
                            '<div class="folder">' +
                            '<p class="file">' + data.files[i]._NAME + '</p>' +
                            '</div>';
                    }
                } catch (e) {
                    console.log(e)
                }

                folder.innerHTML = folder.innerHTML + '<div class="form-add">Создать' +
                    '<form class="create-form" target="/phptree" method="post">' +
                    '<input type="hidden" name="method" value="create">' +
                    '<input type="hidden" name="parentId" value="'+ folderId +'">' +
                    '<select name="which">' +
                    '<option selected value="folder">Папку</option>' +
                    '<option value="file">Файл</option>' +
                    '</select>' +
                    '<input type="text" placeholder="имя" name="name">' +
                    '' +
                    '<button type="button" onclick="addNew(this)">Создать</button>' +
                    '</form>' +
                    '</div>';
        });

    }
}




function addNew(button) {
    const fd = new FormData(button.parentElement);
    const folderId = fd.get('parentId');
    let req = '';
    fd.forEach(function(value, key){
        req = req +  (key + '=' + value + '&')
    });
    req = req.substring(0, req.length - 1)
    fetch('?' + req, {
        method: 'POST',
    }).then(r => r.blob()).then(r => r.text()).then( t => {
        let folder = button.parentElement.parentElement.parentElement;
        if (folderId > 0) {
            openFolder(folder, folderId);
            openFolder(folder, folderId);
        }
    })


}