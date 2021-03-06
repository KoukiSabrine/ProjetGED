
require('/plugins/js/zTree/jquery.ztree.core.js');
require('/plugins/js/zTree/jquery.ztree.excheck.js');
require('/plugins/js/zTree/jquery.ztree.exedit.js');
require('/plugins/css/zTree/zTreeStyle.css');


function reloadzNodes() {
    $.ajax({
        type: "POST",
        url: $('#directoryList').attr("data-reloadDirectory"),
        data: {},
        success: function (returnData) {
            $("#directoryList").val(returnData);
            var zNodes = JSON.parse($("#directoryList").val());
            var t = $("#tree");
            t = $.fn.zTree.init(t, setting, zNodes);
            console.log('reload>>>>' + returnData);           
        }
    });
};

function myOnRename(treeId, treeNode, newName, isCancel) {
    console.log(newName);
    try {
        $.ajax({
            type: "POST",
            url: $('#directoryList').attr("data-renameDirectory"),
            data: {
                directoryNode: newName.pId.split('_')[1],
                directoryName: newName.name,
                directoryNodeOld: newName.id.split('_')[1]
            },
            success: function (returnData) {
                console.log('test>>>>' + returnData);
                reloadzNodes();
                return newName.length > 5;
            }
        });
    }
    catch (error) {
        return newName.length > 5;
    }
};

var newCount = 1;
function addHoverDom(treeId, treeNode) {
    var aObj = $("#" + treeNode.tId + "_span");
    if ($("#diyBtn_" + treeNode.id).length > 0)
        return;
    var editStr = "<span class='button add' id='addBtn_" + treeNode.id
            + "' title='add node' onfocus='this.blur();'></span>" +
            "<span id='diyBtn_space_" + treeNode.id + "' style='display:none;' > </span>"
            + "<button type='button' class='diyBtn1' id='diyBtn_" + treeNode.id
            + "' title='" + treeNode.name + "' onfocus='this.blur();' style='display:none;' ></button>";
    aObj.append(editStr);
    var btn = $("#diyBtn_" + treeNode.id);
    if (btn)
        btn.bind("click", function () {
            //alert("diy Button for " + treeNode.name);
        });
    var btnAdd = $("#addBtn_" + treeNode.id);
    if (btnAdd)
        btnAdd.bind("click", function () {
            var modal = prompt("Veuillez saisir le nom du nouveau r??pertoire :", "");
            if (modal == null || modal == "") {
                return false;
            } else {
                //console.log('treeNode.id' + treeNode.id.split('_')[1] + ' > ' + $('#directoryList').attr("data-createDirectory"));
                $.ajax({
                    type: "POST",
                    url: $('#directoryList').attr("data-createDirectory"),
                    data: {
                        directoryNode: treeNode.id.split('_')[1],
                        directoryName: modal
                    },
                    success: function (returnData) {
                        console.log('test>>>>' + returnData);
                        reloadzNodes();
                        var zTree = $.fn.zTree.getZTreeObj("tree");
                        zTree.addNodes(treeNode, {id: (100 + newCount), pId: treeNode.id, name: modal, isParent: true});
                        return false;
                    }
                });
            }
        });
}
;

function removeHoverDom(treeId, treeNode) {
    $("#addBtn_" + treeNode.id).unbind().remove();
    $("#diyBtn_" + treeNode.id).unbind().remove();
    $("#diyBtn_space_" + treeNode.id).unbind().remove();
}
;

var log, className = "dark";
function beforeEditName(treeId, treeNode) {
    className = (className === "dark" ? "" : "dark");
    var zTree = $.fn.zTree.getZTreeObj("tree");
    zTree.selectNode(treeNode);
    setTimeout(function () {
        if (confirm("Voulez-vous renommer le r??pertoire '" + treeNode.name + "' ?")) {
            setTimeout(function () {
                zTree.editName(treeNode);
            }, 0);
        }
    }, 0);
    return false;
}
;

function beforeRemove(treeId, treeNode) {
    var zTree = $.fn.zTree.getZTreeObj("tree");
    zTree.selectNode(treeNode);
    console.log(treeNode);
    var modal = confirm("Voulez vous supprimer le r??pertoire '" + treeNode.name + "' ?");
    if (modal == true) {
        try {
            $.ajax({
                type: "POST",
                url: $('#directoryList').attr("data-removeDirectory"),
                data: {
                    directoryNode: treeNode.pId.split('_')[1],
                    directoryName: treeNode.name
                },
                success: function (returnData) {
                    console.log('test>>>>' + returnData);
                    reloadzNodes();
                    return true;
                }
            });
        }
        catch (error) {
            return true;
        }
    } else {
        return false;
    }
}
;

var setting = {
    edit: {
        enable: true,
        showRemoveBtn: true
    },
    view: {
        dblClickExpand: false,
        showLine: true,
        selectedMulti: false,
        addHoverDom: addHoverDom,
        removeHoverDom: removeHoverDom,
    },
    data: {
        simpleData: {
            enable: true,
            idKey: "id",
            pIdKey: "pId",
            rootPId: ""
        }
    },
    callback: {
        onRename: myOnRename,
        beforeEditName: beforeEditName,
        beforeRemove: beforeRemove,
        onClick:myOnClick
    }
};

$(document).ready(function () {
    var zNodes = JSON.parse($("#directoryList").val());
    var t = $("#tree");
    t = $.fn.zTree.init(t, setting, zNodes);
});

function myOnClick(event, treeId, treeNode) {
    $('#content #objectContainer').show();
    $.ajax({
        type: "POST",
        url: $('#directoryList').attr("data-showDirectoryContent"),
        data: {
            directoryNode: treeNode.pId.split('_')[1],
            directoryName: treeNode.name
        },
        beforeSend: function(){
            $('#content #objectContainerDocs').hide();
            $('#treeDoc').find('.curSelectedNode').find("span[id$='_ico']").removeClass('icon_open').addClass('icon_close');
            $('#treeDoc').find('.curSelectedNode').removeClass('curSelectedNode');
            $('#content #objectContainer').html('<div style="text-align:center;margin:10px 0"><img src="/images/ajax-loader.gif" style="width:40px" /></div>');
        },
        success: function (returnData) {
            $('#content #objectContainer').html(returnData);
        }
    });
};

var settingdoc = {
    edit: {
        enable: false,
        showRemoveBtn: false
    },
    view: {
        dblClickExpand: false,
        showLine: true,
        selectedMulti: false,
        addHoverDom: false,
        removeHoverDom: false
    },
    data: {
        simpleData: {
            enable: true,
            idKey: "id",
            pIdKey: "pId",
            rootPId: ""
        }
    },
    callback: {
        onRename: {},
        beforeEditName: {},
        beforeRemove: {},
        onClick:docOnClick
    }
};

function docOnClick(event, treeId, treeNode) {
    $('#content #objectContainerDocs').show();
    $.ajax({
        type: "POST",
        url: $('#directoryDoc').attr("data-showDirectoryDocContent"),
        data: {
            directoryNode: treeNode.pId.split('_')[1],
            directoryName: treeNode.name
        },
        beforeSend: function(){
            $('#content #objectContainer').hide();
            $('#tree').find('.curSelectedNode').find('span.edit,span.remove').remove();
            $('#tree').find('.curSelectedNode').find('.node_name').find('span,button').unbind().remove();
            $('#tree').find('.curSelectedNode').find("span[id$='_ico']").removeClass('icon_open').addClass('icon_close');
            $('#tree').find('.curSelectedNode').removeClass('curSelectedNode');
            $('#content #objectContainerDocs').html('<div style="text-align:center;margin:10px 0"><img src="/images/ajax-loader.gif" style="width:40px" /></div>');
        },
        success: function (returnData){
            $('#content #objectContainerDocs').html(returnData);
        }
    });
};

$(document).ready(function () {
    var zNodesDoc = JSON.parse($("#directoryDoc").val());
    var tdoc = $("#treeDoc");
    tdoc = $.fn.zTree.init(tdoc, settingdoc, zNodesDoc);
    alert(zNodesDoc);
});

$(document).on("click", "button[id^='btnDeletePhotoV2_']", function (e) {
    var  key=$(this).attr("id");
    var index=key.indexOf("_")+1;
    key = key.slice(index,key.length);
    var filterKey=$(this).attr('data-filter');
    if(confirm("Voulez vous supprimer le r??pertoire")){
        $.ajax({
            type: "POST",
            url: $(this).attr("data-url"),
            dataType: "html",
            cache: false,
            async: true,
            data: {
                directoryNode: key,
                filterKey:filterKey
            },success: function ($data) {

                $("#objectContainer").html($data);
                $('#ClosedPopUpResponse').html("<div class='alert alert-success'>Suppression effectu??e avec succ??s.</div>").delay(5000).fadeOut();
                setTimeout(function() {
                    $('#ClosedPopUpResponse').html("");
                    $('#ClosedPopUpResponse').show("");
                    $('.alert-success').show();
                },5000);
            }
        });
    }
});



///// Photo V 2.1
function onBeforeSend(formData, file) {
    if (file.name.indexOf(".jpg") != -1 || file.name.indexOf(".jpeg") != -1 || file.name.indexOf(".gif") != -1 || file.name.indexOf(".png") != -1 || file.name.indexOf(".JPG") != -1 || file.name.indexOf(".JPEG") != -1 || file.name.indexOf(".GIF") != -1 || file.name.indexOf(".PNG") != -1) {
        console.log("Before Send");
        $("#photoLoader").html('<div class="text-center " style="margin-top: 15%"><img src="/images/ajax-loader.gif" width="30px" height="30px"></div>');
        return formData;
    }
    else {
        alert('Format de fichier non valide');
        return false;
    }
}
var i = 0;
function onFileComplete(e, file, response) {
    console.log("File Complete :" + response);
    $("#photoLoader").html('');
    $('#photoErrorMessage').hide();
    var param = response.split("|");
    var urlPhoto = param[0];
    var photoKey = param[1];
    $("div[id^='divImage_']").each(function () {
        i = $(this).attr("id").split('_')[1];
    });
    i = parseInt(i) + 1;
    var regAccentA = new RegExp('[^a-zA-Z0-9.-]', 'gi');
    var nomFichier = file.name;
    var nom = nomFichier.replace(regAccentA, '');
    var currentSrc = 'url(' + $.trim(urlPhoto) + ')';
    if (i < 5) {
        $("#multiUp").append("<div class='col-sm-5' id='divImage_" + i + "' style='float: left; margin-right: 5%; padding: 0px;'></div>");
        $("#divImage_" + i).append("<div id='image_" + i + "' style='height: 90px; background-size: cover;'></div>");
        $("#divImage_" + i).append("<a id='removeImage_" + i + "' style='cursor:pointer;'><i class='fa fa-trash'></i></a>");
        $("#divImage_" + i).append("<input type='Hidden' id='urlPhotoDetailImage_" + i + "' value='" + $.trim(urlPhoto) + "' >");
        $("#divImage_" + i).append("<input type='Hidden' id='typeImage_" + i + "' value='" + $.trim(urlPhoto) + "' >");
        $("#divImage_" + i).append("<input type='Hidden' id='nouveau_" + i + "' value='1' >");
        $("#divImage_" + i).append("<input type='Hidden' id='photoName_" + i + "' value='" + nom + "' >");
        $("#divImage_" + i).append("<input type='Hidden' id='photoKey_" + i + "' value='" + photoKey + "' >");
        $("#image_" + i).css("background-image", currentSrc);
    }
}
$(document).on("click", "#menuContent2_1 input[name='directoryPath']", function (e) {
    $("#photoContainer2_1").show();
    if($("#repositoryContainer_"+$(this).val()).is(":hidden")){
    $("#repositoryContainer_"+$(this).val()).show();
    $("#folder_"+$(this).val()).attr("class","fa fa-folder-open");
    }else{
        $("#repositoryContainer_"+$(this).val()).hide();
        $("#folder_"+$(this).val()).attr("class","fa fa-folder");
    }
    var directory=$(this).val();
    var directoryIndex = directory.indexOf("_")+1;
    directory=directory.slice(directoryIndex,directory.length);
    $(".upload").upload("destroy");
    $(".upload").upload({
        accept: '.jpg,.png,.gif,.jpeg',
        action: $('#btnUrlSavePhoto').attr("data-href"),
        maxSize: 173741824,
        postData: {'directoryNode': directory},
        beforeSend: onBeforeSend
    })
        .on("filecomplete.upload", onFileComplete);
});
$(document).on("click", "#photoAddV2_1frm a[id='btnUrlSavePhoto'],#photoAddV2_1frm button[id='btnUrlSavePhotoClose']", function () {
    if($("#multiUp").children().length==0){
        $('[id=btnUrlSavePhoto]').attr("disabled", false);
        $('#photoErrorMessage').show();
        $('#photoContainer2_1').show();
    }else{
    if ($("#formPhoto").valid()) {
        $('[id=btnUrlSavePhoto]').attr("disabled", true);
        var thisId = $(this).attr("id");
        //var event = $("#SavePhotoAction").val();
        //thisId = $.trim(thisId + event);
        $("input[id^='urlPhotoDetailImage_']").each(function () {
            $.ajax({
                type: "POST",
                url: $("#btnUrlSavePhoto").attr("data-saveToBDD"),
                data: {
                    urlPhotoDetail: $(this).val()
                    , event: thisId
                },
                success: function (data) {
                    if (thisId === "btnUrlSavePhotoClose" || thisId === "btnUrlSavePhotoContinue") {
                        // render response [index page]
                        $("#ClosedPopUpResponse").html(data);
                        // close pop up
                        $("#containerPopup").html("");
                        $("#closeModal").trigger("click");
                    } else if (thisId === "btnUrlSavePhotoNew") {
                        $("#photoPopUpResponse").html();
                        $('[id=btnUrlSavePhoto]').attr("disabled", false);
                    }
                    /*
                        if (thisId === "btnUrlSavePhotoClose" || thisId === "btnUrlSavePhotoContinue") {
                        // render response [index page]
                        $("#ClosedPopUpResponse").html(data);
                        // close pop up
                        $("#containerPopup").html("");
                        $("#closeModal").trigger("click");
                        } else if (thisId === "btnUrlSavePhotoNew") {
                        $("#photoPopUpResponse").html(data);
                        $('[id=btnUrlSavePhoto]').attr("disabled", false);
                        }
                        */
                }
            });
        });

    }
    }
});
$(document).on("click", "#photoAddV2_1frm [id^='removeImage_']", function () {
    var idIm = $(this).attr("id").split('_')[1];
    console.log($("#photoKey_" + idIm).val() + '  ' + $('#ajaxUrl').attr("data-deletePhoto"));
    if (confirm("Etes-vous s??r de vouloir supprimer cette objet ?")) {
        $.ajax({
            type: "POST",
            url: $('#ajaxUrl').attr("data-deletePhoto"),
            data: {
                directoryNode: $("#photoKey_" + idIm).val()
            },
            success: function (returnData) {
                console.log(returnData);
            }
        });
        $("#image_" + idIm).remove();
        $("#urlPhotoDetailImage_" + idIm).remove();
        $("#divImage_" + idIm).remove();
    }
});

$(document).on("click", "button[id^='imageDivSize']", function () {
    $('#imageShowView').attr('style','width:100%');
});
$(document).on("click", "button[id^='imageOriginalSize']", function () {
    $('#imageShowView').attr('style','width:unset');
});
//// end Photo 2.1