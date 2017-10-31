<!DOCTYPE HTML>
<html lang="en">
<head>
    <!-- Force latest IE rendering engine or ChromeFrame if installed -->
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <meta charset="utf-8">
    <title>Upload</title>
    <meta name="description" content="File Upload widget with multiple file selection, drag&amp;drop support, progress bars, validation and preview images, audio and video for jQuery. Supports cross-domain, chunked and resumable file uploads and client-side image resizing. Works with any server-side platform (PHP, Python, Ruby on Rails, Java, Node.js, Go etc.) that supports standard HTML form file uploads.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap styles -->
    <link rel="stylesheet" href="{{url('multiUpload/assets/bootstrap/core/dist/css/bootstrap.min.css')}}"/>
    <!-- blueimp Gallery styles -->
    <link rel="stylesheet" href="{{url('multiUpload/assets/jQueryFileUpload/css/blueimp-gallery.min.css')}}">
    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" href="{{url('multiUpload/assets/jQueryFileUpload/css/jquery.fileupload.css')}}">
    <link rel="stylesheet" href="{{url('multiUpload/assets/jQueryFileUpload/css/jquery.fileupload-ui.css')}}">
    <!-- CSS adjustments for browsers with JavaScript disabled -->
    <noscript><link rel="stylesheet" href="{{url('multiUpload/assets/jQueryFileUpload/css/jquery.fileupload-noscript.css')}}"></noscript>
    <noscript><link rel="stylesheet" href="{{url('multiUpload/assets/jQueryFileUpload/css/jquery.fileupload-ui-noscript.css')}}"></noscript>
    <style>
        .template-upload td{
            vertical-align: middle!important;
        }
        .template-download td{
            vertical-align: middle!important;
        }
        .progress{
            margin: 15px 0!important;
        }
        .progress-extended{
            margin-bottom: 15px;
        }
        .table{
            min-height: 190px;
            background-color: #fff;
        }
        .table-striped p{
            margin-bottom: 0!important;
        }
        .progress{
            min-width: 100px;
        }
    </style>
</head>
<body>

<div style="overflow-x: hidden;background: url({{url('multiUpload/assets/images/develop/bg.png')}}) center 130px no-repeat;min-height: 400px;">
    <!-- The file upload form used as target for the file upload widget -->
    <form id="fileupload" action="{{url(config('multi_upload.upload_prefix').'/upload')}}" method="POST" enctype="multipart/form-data">
        {{csrf_field()}}
                <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                    <span class="btn btn-success fileinput-button">
                        <i class="glyphicon glyphicon-plus"></i>
                        <span>添加</span>
                        <input type="file" name="files[]" multiple>
                    </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>开始</span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>取消</span>
                </button>
                <button type="button" class="btn btn-default" onclick="javascript:window.location.reload();">
                    <i class="glyphicon glyphicon-refresh"></i>
                    <span>刷新</span>
                </button>
                <button type="button" class="btn btn-default" onclick="javascript:window.top.location.reload();">
                    <i class="glyphicon glyphicon-chevron-left"></i>
                    <span>返回</span>
                </button>
                <!-- The global file processing state -->
                <span class="fileupload-process"></span>
            </div>
            <!-- The global progress state -->
            <div class="col-lg-5 fileupload-progress">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0;"></div>
                </div>
                <!-- The extended global progress state -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
    </form>
</div>
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
        </td>
        <td>
           <strong class="error text-danger"></strong>
        </td>
        <td>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            <p class="size">Processing...</p>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>开始</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>取消</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">

 {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <span style="color:#428bca;" title="{%=file.srcName%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></span>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.error) { %}
                    <span style="color:#428bca;">{%=file.srcName%}</span>
                {% } else { %}
                    <span style="color:#428bca;" title="{%=file.srcName%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.srcName%}</span>
                {% } %}
            </p>
        </td>
        <td>

            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>

        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>删除</span>
                </button>
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>取消</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<script src="{{url('multiUpload/assets/js/libs/jquery-1.11.0.min.js')}}"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="{{url('multiUpload/assets/jQueryFileUpload/js/vendor/jquery.ui.widget.js')}}"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="{{url('multiUpload/assets/jQueryFileUpload/js/tmpl.min.js')}}"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="{{url('multiUpload/assets/jQueryFileUpload/js/load-image.all.min.js')}}"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="{{url('multiUpload/assets/jQueryFileUpload/js/canvas-to-blob.min.js')}}"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="{{url('multiUpload/assets/bootstrap/core/dist/js/bootstrap.min.js')}}"></script>
<!-- blueimp Gallery script -->
<script src="{{url('multiUpload/assets/jQueryFileUpload/js/jquery.blueimp-gallery.min.js')}}"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="{{url('multiUpload/assets/jQueryFileUpload/js/jquery.iframe-transport.js')}}"></script>
<!-- The basic File Upload plugin -->
<script src="{{url('multiUpload/assets/jQueryFileUpload/js/jquery.fileupload.js')}}"></script>
<!-- The File Upload processing plugin -->
<script src="{{url('multiUpload/assets/jQueryFileUpload/js/jquery.fileupload-process.js')}}"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="{{url('multiUpload/assets/jQueryFileUpload/js/jquery.fileupload-image.js')}}"></script>
<!-- The File Upload audio preview plugin -->
<script src="{{url('multiUpload/assets/jQueryFileUpload/js/jquery.fileupload-audio.js')}}"></script>
<!-- The File Upload video preview plugin -->
<script src="{{url('multiUpload/assets/jQueryFileUpload/js/jquery.fileupload-video.js')}}"></script>
<!-- The File Upload validation plugin -->
<script src="{{url('multiUpload/assets/jQueryFileUpload/js/jquery.fileupload-validate.js')}}"></script>
<!-- The File Upload user interface plugin -->
<script src="{{url('multiUpload/assets/jQueryFileUpload/js/jquery.fileupload-ui.js')}}"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="{{url('multiUpload/assets/jQueryFileUpload/js/cors/jquery.xdr-transport.js')}}"></script>
<![endif]-->

<script>
    var fileNameData = [];
    var fileNameObject = {};
    $('#fileupload').fileupload({
        url: '{{url(config('multi_upload.upload_prefix').'/upload')}}',
        maxChunkSize: 5000000,    //分块上传的大小 5M
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png|pdf|doc|docx|txt|xls|xlsx|ppt|pptx|mp4|rmvb|mkv|avi|wmv|flv|mp3)$/i,
        formData: {fileUid: $.now(), _token: '{{ csrf_token() }}'},
        send:function (e,data) {
            //上传文件列表中文件已存在
            if(fileNameData.indexOf(data.files[0].name) >= 0){
                data.files[0].error = '文件已存在';
                data.files[0].srcName = data.files[0].name;
                return false;
            }
            fileNameData.push(data.files[0].name);
        },
        {{--chunkdone:function (e, data) {--}}
            {{--var srcName = data.result.files[0].srcName;--}}
            {{--var name = data.result.files[0].name;--}}
            {{--if (fileNameObject[srcName]){--}}
                {{--return false;--}}
            {{--}--}}
            {{--fileNameObject[srcName] = name;--}}
        {{--},--}}
        {{--chunkfail: function (e, data) {--}}
            {{--var srcName = data.files[0].name;--}}
            {{--var file = fileNameObject[srcName];--}}
            {{--$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{!! csrf_token() !!}' }});--}}
            {{--$.ajax({--}}
                {{--url: '', //上传失败删除原文件--}}
                {{--dataType: 'json',--}}
                {{--data: {file: file},--}}
                {{--type: 'DELETE'--}}
            {{--});--}}
        {{--},--}}
        //Callback to retrieve the list of files from the server response.
        //Is given the data argument of the done callback, which contains the result property.
        getFilesFromResponse:function (data) {
            if(!data.result.files[0].error){
                //上传成功后续操作
            }
            return data.result.files;
        }
    });
</script>
</body>
</html>
