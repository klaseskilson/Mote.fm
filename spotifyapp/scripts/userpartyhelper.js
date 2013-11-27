var getUser = function(){
	if(localStorage.user)
	{
		return localStorage.user;
	}
	else
	{
		return -1;
	}
}

var setUser = function(user){
	localStorage.user = user;
}

var getParty = function(){

	if(localStorage.partyId)
	{
		return localStorage.partyId;
	}
	else
	{
		return -1;
	}	
}

var setParty = function(partyId){
	localStorage.partyId = partyId;	
}

var closeParty = function()
{
	localStorage.partyId = -1;
}

var getUserName = function(userId, field)
{
	postData = 
	{
		'userId' : userId
	};
	$.post('http://127.0.0.1/hathor/index.php/getUser/', postData , function(data, textStatus) {
					$(field).html( data );
				});

}