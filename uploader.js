$(function() {

	//---Solutionn uploader module
	var solutionData = new FormData();
	$('#inputerMotherFileSelect').change(function()
	{
		var filename = document.getElementById('inputerMotherFileSelect').files[0].name;
		//Check pdf file
		var allowedExtensions = Array('zip','ZIP','rar','RAR','7z','7Z');
		var filenameBroke = filename.split('.');
		var extensionFile = filenameBroke[filenameBroke.length - 1]
		if($.inArray(extensionFile, allowedExtensions)>-1)		//Allowed
		{
			//Update name
			$('#filenameSelectedUPMODULE').html(filename).css('font-weight', 'normal').css('color', '#000').css('display', 'inline');
			solutionData.append('solutionPending', document.getElementById('inputerMotherFileSelect').files[0]);
		}
		else 	//Not allowed extension
		{
			//Update name
			$('#filenameSelectedUPMODULE').html('The file selected must be: ZIP, RAR or 7z').css('font-weight', 'bold').css('color', 'red').css('display', 'inline');
			$('#inputerMotherFileSelect').val('');
			document.getElementById('inputerMotherFileSelect').files[0] = undefined;
		}
	});
	//Upload
	$('#bttnUploadSolution').click(function()
	{
		$(this).prop('disabled', true);
		if(solutionData.get('solutionPending') != undefined)	//Ok
		{
			$('#bttnUploadSolution').html('Uploading...');
			$('input').prop('disabled', true);
			///...
			solutionData.append('jobTODO', 'uploadThisPublicPaper');
			//--------------------------------------------

			var ajax = new XMLHttpRequest();
			  ajax.upload.addEventListener("progress", progressHandler, false);
			  ajax.addEventListener("load", completeHandler, false);
			  ajax.addEventListener("error", errorHandler, false);
			  ajax.addEventListener("abort", abortHandler, false);
			  ajax.addEventListener("readystatechange", function()
			  	{
			  		if (ajax.readyState==4 && ajax.status==200){
			          if(ajax.responseText=='success')	//Ok
			          {
			          	$('#bttnUploadSolution').html('Done, Thanks! ;)');
					 	$('#inputerMotherFileSelect').val('');
					 	$('#filenameSelectedUPMODULE').html('');
						document.getElementById('inputerMotherFileSelect').files[0] = undefined;
						setTimeout(function()
						{
							//$('#progressUploading').html(''); --- Set the progressBar to it's final state and reinitialize all the values
							//$('#closeUploaderModule').trigger('click'); --- auto close the uploader menu
						}, 2000);
			          }
			          else 	//Error
			          {
			          	$('#bttnUploadSolution').html(':( An error occured.');
						  //$('#progressUploading').html(''); --- Reinitialize all the values
						  setTimeout(function()
						  {
						  	//$('#closeUploaderModule').trigger('click'); --- auto close the uploader menu 
						  	window.location.assign(window.location);
						  }, 1000);
			          }
			       }
			  	});
			  ajax.open("POST", "assets/php/AppManager.php");
			  ajax.send(solutionData);


			function progressHandler(event) {
			  var percent = (event.loaded / event.total) * 100;
			  //Update the progressBar with [var] percent
			}

			function completeHandler(event) {
			 	//Done
			}

			function errorHandler(event) {
			  $('#bttnUploadSolution').html('An error occured.');
			  $('#inputerMotherFileSelect').val('');
			  $('#filenameSelectedUPMODULE').html('');
			  document.getElementById('inputerMotherFileSelect').files[0] = undefined;
			  setTimeout(function()
			  {
			  	$('#closeUploaderModule').trigger('click');
			  	window.location.assign(window.location);
			  }, 1000);
			}

			function abortHandler(event) {
			  $('#bttnUploadSolution').html('An error occured.');
			  $('#inputerMotherFileSelect').val('');
			  $('#filenameSelectedUPMODULE').html('');
			  document.getElementById('inputerMotherFileSelect').files[0] = undefined;
			  setTimeout(function()
			  {
			  	$('#closeUploaderModule').trigger('click');
			  	window.location.assign(window.location);
			  }, 1000);
			}
		}
		else
		{
			//Update log
			$('#filenameSelectedUPMODULE').html('Select the file first.').css('font-weight', 'bold').css('color', 'red').css('display', 'inline');
			$('#inputerMotherFileSelect').val('');
			document.getElementById('inputerMotherFileSelect').files[0] = undefined;
		}
	});

	//Drag and drop
	$('#uploadLandNode').on({
    'dragover dragenter': function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).css('border-color', '#97cbff');
    },
    'dragout': function()
    {
    	e.preventDefault();
        e.stopPropagation();
    	$(this).css('border-color', '#d0d0d0');
    },
    'drop': function(e) {
        //console.log(e.originalEvent instanceof DragEvent);
        var dataTransfer =  e.originalEvent.dataTransfer;
        if( dataTransfer && dataTransfer.files.length) {
            e.preventDefault();
            e.stopPropagation();
            $.each( dataTransfer.files, function(i, file) { 
              var filename = file.name;
				//Check pdf file
				var allowedExtensions = Array('zip','ZIP','rar','RAR','7z','7Z');
				var filenameBroke = filename.split('.');
				var extensionFile = filenameBroke[filenameBroke.length - 1]
				if($.inArray(extensionFile, allowedExtensions)>-1)		//Allowed
				{
					$('#uploadLandNode').css('border-color', '#d0d0d0');
					//Update name
					$('#filenameSelectedUPMODULE').html(filename).css('font-weight', 'normal').css('color', '#000').css('display', 'inline');
					solutionData.append('solutionPending', file);
					//alert(solutionData);
				}
				else 	//Not allowed extension
				{
					//Update name
					$('#filenameSelectedUPMODULE').html('The file selected must be: ZIP, RAR or 7z').css('font-weight', 'bold').css('color', 'red').css('display', 'inline');
					$('#inputerMotherFileSelect').val('');
					document.getElementById('inputerMotherFileSelect').files[0] = undefined;
				}
            });
        }
    }
});

});