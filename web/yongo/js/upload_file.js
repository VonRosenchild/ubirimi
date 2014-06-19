/*
 filedrag.js - HTML5 File Drag & Drop demonstration
 Featured on SitePoint.com
 Developed by Craig Buckler (@craigbuckler) of OptimalWorks.net
 */

// getElementById
function $id(id) {
    return document.getElementById(id);
}
// output information
function Output(msg) {
    var m = $id("messages");
    m.innerHTML = msg + m.innerHTML;
}
// file drag hover
function FileDragHover(e) {
    e.stopPropagation();
    e.preventDefault();
    e.target.className = (e.type == "dragover" ? "hover" : "");
}
// file selection
function FileSelectHandler(e) {

    // cancel event and hover styling
    FileDragHover(e);

    // fetch FileList object
    var files = e.target.files || e.dataTransfer.files;

    // get the latest index

    var elements = $("[id^='file_name_']");
    var max = 0;
    $("[id^='file_name_']").each(function(i, selected){
        var id_element = $(this).attr("id").replace('file_name_', '')
        if (id_element > max)
            max = id_element
    });

    max++;
    // process all File objects
    for (var i = 0; i < files.length; i++) {
        ParseFile(files[i], max + i);

        UploadFile(files[i], max + i, e.data.issue_id);
    }

}
// output file information
function ParseFile(file, index) {

    // display an image
    if (file.type.indexOf("image") == 0) {
        var reader = new FileReader();
        reader.onload = function (e) {

        };
        reader.readAsDataURL(file);
    }

    // display text
    if (file.type.indexOf("text") == 0) {
        var reader = new FileReader();
        reader.onload = function (e) {
        };
        reader.readAsText(file);
    }

}

// upload JPEG files
function UploadFile(file, index, issueId) {

    // following line is not necessary: prevents running on SitePoint servers
    if (location.host.indexOf("sitepointstatic") >= 0) return;

    var xhr = new XMLHttpRequest();
    if (xhr.upload) {

        // create progress bar
        var o = $("#progress");
        o.append('<div id="file_name_' + index + '">' + file.name + '</div>');
        o.append('<p id="progress_' + index + '"></p>');

        // progress bar
        xhr.upload.addEventListener("progress", function (e) {
            var pc = parseInt(100 - (e.loaded / e.total * 100));
            $('#progress_' + index).css('backgroundPosition', pc + "% 0");
        }, false);

        // file received/failed
        xhr.onreadystatechange = function (e) {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    $('#progress_' + index).remove();
                    var html_filename = $('#file_name_' + index).html();

                    $('#file_name_' + index).html('<input id="attach_' + xhr.responseText + '" type="checkbox" value="1" checked="checked" />' + html_filename)
                }
            }
        };

        // start upload
        if (!issueId)
            xhr.open("POST", '/yongo/upload-attachement', true);
        else
            xhr.open("POST", '/yongo/upload-attachement?issue_id=' + issueId, true);

        xhr.setRequestHeader("X_FILENAME", file.name);

        xhr.send(file);
    }
}

// initialize
function initializaFileUpload(issueId) {

    var fileselect = $("#field_type_attachment");

    fileselect.bind("change", {issue_id: issueId}, FileSelectHandler);
}