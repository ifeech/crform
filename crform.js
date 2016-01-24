function crForm(path){

	this.pathCrForm = path||"/cr-form/";

	this.start = function(){
		var pathCrForm = this.pathCrForm;
		$('[crform-name]').each(function(ind, el){
			var name = $(el).attr('crform-name');
			var url = pathCrForm + 'template/form/' + name + '.form.crForm.html';

	  	    $.ajax({
		      	type: 'POST',
		      	url: url,
		      	success: function(data) {
		      		$(el).append(data);
		      	},
		      	error: function(xhr, str){
		          	console.log('Возникла ошибка: ' + xhr.responseCode);
		        }
		    });
		});
	}

	this.submit = function(event){
		var pathCrForm = this.pathCrForm;
		var url = pathCrForm + 'Core.crForm.php';
	  	var msg = $(event.target).serialize();

	    $.ajax({
	      	type: 'POST',
	      	url: url,
	      	data: msg,
	      	success: function(data) {
	      		
	      	},
	      	error: function(xhr, str){
	          	console.log('Возникла ошибка: ' + xhr.responseCode);
	        }
	    });

		return false;
	}




    this.modal = "#popup";
    this.name = "none";
	this.close = function(){$(this.modal).hide();}
	
	this.open = function(){	
		$(this.modal).show();
		
 		var popupContent = $(this.modal + " .content");
		var leftContent = ($(document).width() - $(popupContent).outerWidth())/2
		var topContent = ($(document).height()+$(document).scrollTop() - $(popupContent).outerHeight())/2;
		
		$(popupContent).animate({ 
				left: leftContent,         
				top: topContent,
		}, 300);  		
		
		$(document).scrollTop(topContent-50); 
	}
	
	this.addErrorPopup = function(error){
		$(this.modal+" .error").empty().html(error).show();
	}
	
	this.createPopup = function(content,title,button){
		var popupContent = $(this.modal + " .content .body");
		var body = '<a href="javascript:void(0)" onclick="popup.close(); return false;" class="exit">X</a>';
	   
		if(title) body += '<div class="title"><h3>' + title + '</h3></div>';
	   
		body += '<div class="error"></div>';
		body += content;
	   
		$(popupContent).empty().html(body);
		this.open();		   
	}
	
	this.zoom = function(el,replaceText){
		var popupContent = $(this.modal + " .content .body");
		var body = '<a href="javascript:void(0)" onclick="popup.close(); return false;" class="exit">X</a>';
		
		var img = $(el).attr('src');
		var title = $(el).attr('title');
		
		newImg = img.replace(replaceText[0],replaceText[1]);		
		if(title) body += '<div class="title"><h3>' + title + '</h3></div>';	   
		body += '<img src="'+newImg+'" style="display:block; margin:0 auto;" onload="popup.open();return false;">';
		
		$(popupContent).empty().html(body);		   
	}
}