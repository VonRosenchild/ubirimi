/*
 filedrag.js - HTML5 File Drag & Drop demonstration
 Featured on SitePoint.com
 Developed by Craig Buckler (@craigbuckler) of OptimalWorks.net
 */

var xhrFileUpload = null;
var tempCounterFilesUploaded = 0;

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

    $('.ui-dialog-buttonset button').first().addClass('disabled');
    $('.ui-dialog-buttonset button').first().children().addClass('disabled');

    // cancel event and hover styling
    FileDragHover(e);

    // fetch FileList object
    var files = e.target.files || e.dataTransfer.files;
    // get the latest index

    var max = 0;
    $("[id^='file_name_']").each(function(i, selected){
        var id_element = $(this).attr("id").replace('file_name_', '');
        if (id_element > max)
            max = id_element
    });

    max++;

    // process all File objects
    tempCounterFilesUploaded = 0;
    for (var i = 0; i < files.length; i++) {
        ParseFile(files[i], max + i);
        UploadFile(files[i], max + i, e.data.issue_id, files.length);
    }
}

function checkUploadFinished(filesToBeUploadedCounter) {
    tempCounterFilesUploaded++;
    if (tempCounterFilesUploaded == filesToBeUploadedCounter) {
        $('.ui-dialog-buttonset button').first().removeClass('disabled');
        $('.ui-dialog-buttonset button').first().children().removeClass('disabled')
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
function UploadFile(file, index, issueId, filesToBeUploadedCounter) {

    // following line is not necessary: prevents running on SitePoint servers
    if (location.host.indexOf("sitepointstatic") >= 0) return;

    xhrFileUpload = new XMLHttpRequest();
    if (xhrFileUpload.upload) {
        // create progress bar
        var o = $("#progress");
        o.append('<div id="file_name_' + index + '">' + file.name + '</div>');
        o.append('<p id="progress_' + index + '"></p>');

        // progress bar
        xhrFileUpload.upload.addEventListener("progress", function (e) {
            var pc = parseInt(100 - (e.loaded / e.total * 100));
            $('#progress_' + index).css('backgroundPosition', pc + "% 0");
        }, false);

        xhrFileUpload.upload.addEventListener("load", function (e) {
            $('#progress_' + index).remove();
            var html_filename = $('#file_name_' + index).html();
            $('#file_name_' + index).html('<input id="attach_' + xhrFileUpload.responseText + '" type="checkbox" value="1" checked="checked" />' + html_filename)

            checkUploadFinished(filesToBeUploadedCounter);
        }, false);

        // start upload
        if (!issueId) {
            xhrFileUpload.open("POST", '/yongo/upload-attachement', true);
        } else {
            xhrFileUpload.open("POST", '/yongo/upload-attachement?issue_id=' + issueId, true);
        }

        xhrFileUpload.setRequestHeader("X_FILENAME", encodeURIComponent(file.name));
        xhrFileUpload.send(file);
    }
}

// initialize
function initializaFileUpload(issueId) {

    var fileselect = $("#field_type_attachment");

    fileselect.bind("change", {issue_id: issueId}, FileSelectHandler);
}