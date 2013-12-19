require([
  '$api/models',
  'scripts/jquery.min'
], function(models, jquery) {
	$(document).ready(function() {
		var d = new Date();
		localStorage.logintime = d.getTime();
	});
	$("#user").html("Welcome back " + sessionStorage.username);
	function part_party_callback()
	{
		$(".pastParty").click(function(){
			var id = $(this).attr('id');
			var post = {
				'partyid' : id
			};

			$.post(constants.SERVER_URL + '/api/party/get_party_info', post, function(json){
				console.log(json);
				if(json.status = 'success')
				{
					sessionStorage.partyname = json.result.name;
					sessionStorage.partyhash = json.result.hash;
					sessionStorage.partyid = json.result.partyid;

					window.location.href = "party.html";
				}
			});
		});
	}
	//load list of previous parties
	var post= {
		'uid' : sessionStorage.uid
	};
	$.post(constants.SERVER_URL + '/api/party/get_user_parties', post, function(json){
		console.log(json);
		if(json.status == "success")
		{
			var html = "";
			var numOfParties = Math.min(5,json.result.length);
			for(var i = 0; i < numOfParties; i++)
			{
				var party = json.result[i];
				//add parties to the list
				html += '<li>';
                html += '   <span id="' + party.partyid + '" class="pastParty">';
                html += '      <div class="glyphicon glyphicon-chevron-right"></div>';
				html += ' ' + party.name;
                html += '   </span>';
                html += '</li>';
			}

			$('#pastParties').html(html);
			part_party_callback();
		}
		else
		{
			//show error
			var html = "";
			html += '<li>';
            html += '   <span class="pastParty" style="cursor:default;">';
            html += '      <div class="glyphicon glyphicon-chevron-right"></div>';
			html += ' ' + json.response;
            html += '   </span>';
            html += '</li>';
            $('#pastParties').html(html);
			console.log("ERROR YOLO");
		}
	});

	//Registration of party
	$('#submit').click(function() {
	    event.preventDefault();
		var $inputs = $('#partyNameForm :input');
		var values = {};
		$inputs.each(function() {
		    values[this.name] = $(this).val();
		});

		models.session.load('country').done(function(){
			var locale = models.session.country;
			values["uid"] = sessionStorage.uid;
			values["locale"] = locale;

			$.post(constants.SERVER_URL + '/api/party/create_party',values, function(data) {
				var json = data;
				if(json.status == "success")
				{
					sessionStorage.partyname = json.result.name;
					sessionStorage.partyhash = json.result.hash;
					sessionStorage.partyid = json.result.partyid;

					window.location.href = "party.html";
				}
				else
				{
					$('#createError').html(json.status + ": " + json.response);
				}
			});
		});
  });
});
