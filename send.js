const formOnSubmit = function (e)
{
	e.preventDefault();
	const form = e.target;
	const url = form.action;
	const formData  = new FormData(form);
	const targetParent = form.parentNode;

	fetch(url, {method: 'POST', body: formData}).then(function (response)
	{
		if(response.status === 200)
		{
			targetParent.replaceChild(successMessage, form);
		}
	});
};