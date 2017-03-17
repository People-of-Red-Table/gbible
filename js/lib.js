	function resize()
	{
		var height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
		height *= 0.75;
		document.getElementById("BibleFrame").innerHeight = height;
		document.getElementById("BibleFrame").height = height;
	}

	function test_email(id, formGroupId, formId)
	{
		var email_pattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		var result = email_pattern.test(document.getElementById(id).value);
		if (!result)
		{
			$('#'+formGroupId).removeClass('has-success');
			$('#'+formGroupId).addClass('has-error');
			$('#'+formId+'IsCorrect').val(false);
		}
		else
		{
			$('#'+formGroupId).removeClass('has-error');
			$('#'+formGroupId).addClass('has-success');
			$('#'+formId+'IsCorrect').val(true);
		}
		return result;
	}