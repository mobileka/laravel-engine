function get_vars()
{
	var url = location.href,
		url_params,
		get_var,
		results = {};
	if(url.indexOf('?') == -1)
	{
		url += '?';
	}

	url = url.substr(url.indexOf('?')+1);
	url_params = url.split('&');

	for(var i=0; i<url_params.length; i++)
	{
		get_var = url_params[i].split('=');
		results[get_var[0]] = get_var[1];
	}
	return results;
}

function get_build(params)
{
	var results = [];
	for (var i in params){
		if(i)
			results.push(i + '=' + params[i]);
	}
	return results.join('&');
}

function set_val(params, key, value)
{
	var results = {};
	for (var i in params){
		if(i != key)
		{
			results[i] = params[i];
		}
	}
	results[key] = value;
	return results;
}

function get_val(params, key)
{
	for (var i in params){
		if(i == key)
		{
			return params[i];
		}
	}
}