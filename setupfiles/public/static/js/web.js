var hostpath = location.protocol+'//'+location.host+'/';

var App = function(){
	this.init();
}

App.prototype = {
	init: function()
	{
		$(document).ready(function()
		{
			appOBJ.defaultForWeb();
			appOBJ.showHidePassword();
			appOBJ.notifyConfirmForDelete();
			appOBJ.validateNumber();
			appOBJ.validateMaxLength();
		});
	},
	defaultForWeb: function()
	{
		$('[data-toggle="tooltip"]').tooltip();
		if($("#scriptInitiater").length=='1')
		{
			let debugMethod = $("#scriptInitiater").data("method");
			if(typeof(debugMethod)!='undefined')
			{
				try {
					eval(debugMethod);
				} catch(err)
				{
					console.log( err );
				}
			}
		}
	},
	isEmail: function(email)
	{
    	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    	return re.test(email);
	},
	isNumber: function(number)
	{
    	return !isNaN(number);
	},
	validateNumber: function()
	{
		$(".validateNumber").on("keydown", function(event)
		{
			console.log( event.keyCode );
			let keyCollections = [8, 9, 17, 18, 37, 38, 39, 40, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57,96,97,98,99,100,101,102,103,104,105];
			if($.inArray(event.keyCode, keyCollections) < 0) return false;
		});
	},
	validateMaxLength: function()
	{
		$(".validateMaxlength").on('input', function()
		{
			// text = $('textarea').val();
			// $('div').html(text);
			if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);
		});
	},
	processing: function()
	{
		$(".webloader").show();
	},
	processed: function()
	{
		$(".webloader").hide();
	},
	post: function(ajaxurl, frmdata, sucessCallback, datatype, posttype, isProcessing, errorCallback, pdata, ctype)
	{
		var pdata = (typeof(pdata)=='undefined') ? true : pdata;
		var ctype = (typeof(ctype)=='undefined') ? 'application/x-www-form-urlencoded; charset=UTF-8' : ctype;
		var posttype = (typeof(posttype)=='undefined') ? 'POST' : posttype;
		var isProcessing = (typeof(isProcessing)=='undefined') ? true : Boolean(isProcessing);
		if(isProcessing) { appOBJ.processing(); }
		
		$.ajax({
			async: true,
			type: posttype,
			url: ajaxurl,
			data: frmdata,
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			success: eval(sucessCallback),
			error: eval(errorCallback),
			dataType: datatype,
			processData: pdata,
			contentType: ctype
		});
	},
	query: function(name)
	{
		var url = window.location.href;
		name = name.replace(/[\[\]]/g, "\\$&");
		var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"), results = regex.exec(url);
		if (!results) return null;
		if (!results[2]) return '';
		return decodeURIComponent(results[2].replace(/\+/g, " "));
	},
	popalert: function(type, btninfo, okCallback, cancelCallback)
	{
		var msgBoxTitle = (typeof(btninfo.title)!='undefined') ? btninfo.title : 'Alert';
		var msg = (typeof(btninfo.msg)!='undefined') ? btninfo.msg : '';
		
		var btnOkText = (typeof(btninfo.ok)!='undefined') ? btninfo.ok : 'Ok';
		var btnCancelText = (typeof(btninfo.cancel)!='undefined') ? btninfo.cancel : 'Cancel';

		var okurl = (typeof(btninfo.okurl)!='undefined' && btninfo.okurl!='') ? btninfo.okurl : 'javascript:void(0);';
		var cancelurl = (typeof(btninfo.cancelurl)!='undefined' && btninfo.cancelurl!='') ? btninfo.cancelurl : 'javascript:void(0);';
		
		var okCallback = (typeof(okCallback)!='undefined' && okCallback!='') ? okCallback : '';
		var cancelCallback = (typeof(cancelCallback)!='undefined' && cancelCallback!='') ? cancelCallback : '';
		
		
		if($("#alertDialogPopUp .messageline").length<1)
		{
			let modalHTML = '<div class="comn-popup-bx modal fade" id="alertDialogPopUp" role="dialog">';
				modalHTML += '<div class="modal-dialog">';
					modalHTML += '<div class="modal-content">';
						modalHTML += '<div class="modal-header">';
							modalHTML += '<h5 class="modal-title text-capitalize">'+msgBoxTitle+'</h5>';
						modalHTML += '</div>';
						modalHTML += '<div class="modal-body">';
							modalHTML += '<p class="messageline text-center"></p>';
						modalHTML += '</div>';
						modalHTML += '<div class="modal-footer d-block">';
							
							
						modalHTML += '</div>';
					modalHTML += '</div>';
				modalHTML += '</div>';
				$("body").append( modalHTML );
		}

		$("#alertDialogPopUp .messageline").html(msg);
		$("body").addClass("alertDialogPopUpOpen");
		switch(type)
		{
			case 'confirm':
				$("#alertDialogPopUp .modal-footer").html('<a type="button" class="btn btn-primary float-right" id="popalertconfirmok" href="'+okurl+'">'+btnOkText+'</a>');
				$("#alertDialogPopUp .modal-footer").append('<a type="button" class="btn btn-primary" id="popalertconfirmcancel" href="'+cancelurl+'">'+btnCancelText+'</a>');
				
				appOBJ.confirmOkCallback = okCallback;
				appOBJ.confirmCancelCallback = cancelCallback;
				setTimeout(function(){
					appOBJ.popalertConfirmOk();
					appOBJ.popalertConfirmCancel();
				}, 250);
			break;
			case 'message':
				$("#alertDialogPopUp .modal-footer").html('<a type="button" class="btn btn-primary float-right" id="popalertconfirmok" href="'+okurl+'">'+btnOkText+'</a>');
				appOBJ.confirmOkCallback = okCallback;
				setTimeout(function(){
					appOBJ.popalertConfirmOk();
				}, 250);
			break;
			default:
				$("#alertDialogPopUp .modal-footer").html('<button type="button" class="btn btn-primary float-right" data-dismiss="modal">'+btnOkText+'</button>');
			break;
		}
		$('#alertDialogPopUp').modal({
			"show": true,
			"backdrop": 'static',
			"keyboard": false
		});

		$("#alertDialogPopUp").on("hidden.bs.modal", function()
		{
			if($(".modal.show").length>0)
			{
				$("body").removeClass("alertDialogPopUpOpen");
				$('body').addClass("modal-open");
			}
		});
	},
	confirmOkCallback: false,
	confirmCancelCallback: false,
	popalertConfirmOk: function()
	{
		$("#popalertconfirmok").unbind("click");
		$("#popalertconfirmok").bind("click", function()
		{
			if(appOBJ.confirmOkCallback!='')
			{
				appOBJ.confirmOkCallback();
			}
			$("#alertDialogPopUp").modal("hide");
		});
	},
	popalertConfirmCancel: function()
	{
		$("#popalertconfirmcancel").unbind("click");
		$("#popalertconfirmcancel").bind("click", function()
		{
			if(appOBJ.confirmCancelCallback!='')
			{
				appOBJ.confirmCancelCallback();
			}
			$("#alertDialogPopUp").modal("hide");
		});
	},
	showHidePassword: function()
	{
		$(".showHidePasswordWrapper .passwordEventHandler").on("click", function(event)
		{
			event.preventDefault();
			let curInd = $(".showHidePasswordWrapper .passwordEventHandler").index($(this));
			$(".showHidePasswordWrapper .passwordEventHandler:eq("+curInd+") i").toggleClass("fa-eye-slash");
			if($(".showHidePasswordWrapper .passwordEventHandler:eq("+curInd+") i").hasClass("fa-eye-slash"))
			{
				$(".showHidePasswordWrapper input").attr("type", "text");
			} else
			{
				$(".showHidePasswordWrapper input").attr("type", "password");
			}
		});
	},
	notifyConfirmForDelete: function()
	{
		$("body").on("click", ".notifyConfirmForDelete", function()
		{
			let targetElementIndex = $(".notifyConfirmForDelete").index($(this));
			let fullMessage = $(".notifyConfirmForDelete:eq("+targetElementIndex+")").data("fullmessage");
			let customMsg = $(".notifyConfirmForDelete:eq("+targetElementIndex+")").data("message");
			customMsg = (typeof(customMsg)!='undefined' && customMsg!='') ? customMsg : '';
			fullMessage = (typeof(fullMessage)!='undefined' && fullMessage!='') ? fullMessage : '';
			let displayMessage = (fullMessage!='') ? fullMessage : "Are you sure? You are going to remove the selected record. It will not be revert once deleted from system. "+customMsg ;

			appOBJ.popalert("confirm", {"title": "Alert!", "ok": "Ok", "cancel": "Cancel", "msg": displayMessage}, function(){
				$(".notifyConfirmForDelete:eq("+targetElementIndex+")").parent().submit();
			});
		});
	},
	pushNotification: function(message, type)
	{
		notify(message, type);
	},
	subscribeCarkeys: function()
	{
		$("footer form[name=subscribeCarkeysFRM]").on("submit", function(event)
		{
			event.preventDefault();
			let email = $.trim( $("footer form[name=subscribeCarkeysFRM] input[name=email]").val() );
			$("footer form[name=subscribeCarkeysFRM] input[name=email]").removeClass('border-danger');
			if( appOBJ.isEmail(email) )
			{
				appOBJ.post($("footer form[name=subscribeCarkeysFRM]").attr("action"), $("footer form[name=subscribeCarkeysFRM]").serialize(), function(d)
				{
					if(d.status==='true')
					{
						$("footer form[name=subscribeCarkeysFRM] .fom-row").html( '<div class="thanks-cont">Subscribed <span>Successfully</span></div>' );
					}
					//appOBJ.processed();
				}, 'json', 'POST', true, function(error)
				{
					//appOBJ.processed();
				});
			} else
			{
				$("footer form[name=subscribeCarkeysFRM] input[name=email]").addClass('border-danger');
			}
		});
	},
	loadSelectData: function(target, ajaxURL, name, elementsEffected, type, elementsChanged)
	{
		$(target).on("change", function()
		{
			let curIndex = $(target).index($(this));
			let selectedValue = $.trim($(this).val());

			if(selectedValue!='')
			{
				appOBJ.triggerSelectData(ajaxURL, curIndex, name, selectedValue, elementsEffected);
			}
		});
	},
	triggerSelectData: function(ajaxURL, curIndex, field, selectedValue, elementsEffected, type, elementsChanged)
	{
		let postData = {};
		postData[field] = selectedValue;

		appOBJ.post(ajaxURL, postData, function(d)
		{
			if(d.status==='true')
			{
				$(elementsEffected+":eq("+curIndex+") option:not(:first-child)").remove();
				$.each(d.data, function(k, v)
				{
					if(type=='string')
					{
						$(elementsEffected+":eq("+curIndex+")").append( '<option value="'+v+'"> ' + v + ' </option>' );
					} else
					{
						$(elementsEffected+":eq("+curIndex+")").append( '<option value="'+k+'"> ' + v + ' </option>' );
					}
				});

				if(typeof(elementsChanged)!='undefined')
				{
					for(var ei=0; ei<elementsChanged.length; ei++)
					{
						$(elementsChanged[ei]+":eq("+curIndex+") option:not(:first-child)").remove();
					}
				} 
			}
		}, 'json', 'GET', true, function(error)
		{
			//appOBJ.processed();
		});
	}
}

var appOBJ = new App();