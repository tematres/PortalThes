/* Bootstrap Contact Form
 ***************************************************************************/
$(document).ready(function(){
	// validate signup form on keyup and submit

	var validator = $("#contactform").validate({
		errorClass:'has-error',
		validClass:'has-success',
		errorElement:'div',
		highlight: function (element, errorClass, validClass) {
			$(element).closest('.form-control').addClass(errorClass).removeClass(validClass);
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).parents(".has-error").removeClass(errorClass).addClass(validClass);
		},
		rules: {
			contactname: {
				required: true,
				minlength: 2
			},
			email: {
				required: true,
				email: true
			},
			weburl: {
				required: true,
				url: true
			},
			phone: {
				required: true,
				phoneUS: true
			},
			subject: {
				required: true
			},
			message: {
				required: true,
				minlength: 10
			}
		},
		messages: {
			contactname: {
				required: '<span class="help-block">Por favor, ingrese su nombre y apellido.</span>',
				minlength: jQuery.format('<span class="help-block">Al menos debe tener {0} letras.</span>')
			},
			email: {
				required: '<span class="help-block">Por favor ingrese una dirección de correo electrónico.</span>',
				minlength: '<span class="help-block">Por favor ingrese una dirección de correo electrónico.</span>'
			},
			weburl: {
				required: '<span class="help-block">You need to enter the address to your website.</span>',
				url: jQuery.format('<span class="help-block">You need to enter a valid URL.</span>')
			},
			phone: {
				required: '<span class="help-block">You need to enter your phone number.</span>',
				phoneUS: jQuery.format('<span class="help-block">You need to enter a valid phone number.</span>')
			},
			subject: {
				required: '<span class="help-block">You need to enter a subject.</span>'
			},
			message: {
				required: '<span class="help-block">Debe incluir algún mensaje.</span>',
				minlength: jQuery.format('<span class="help-block">Debe tener al menos {0} caracteres.</span>')
			}
		}
	});
});
