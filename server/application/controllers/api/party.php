<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class party extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		//Do your magic here

		// all data here should be json formatted
		header("Content-type: application/json");

		// load party model
		$this->load->model('party_model');
		// load user model
		$this->load->model('user_model');
		// load spotify cahce model
		$this->load->model('spotify_model');

		$this->load->helper('external_spotify');
	}

	/**
	 * Returns the currently played song in spotify from database
	 */
	public function get_playing_song()
	{
		//FIXME: skall detta hämtas via post istället?
		//get session data
		$partyID = $this->session->userdata('partyid');
		//What track do we think spotify is playing?
		$expectedTrack = $this->session->userdata('trackuri');

		$data = array();

		if(!$partyID || !$expectedTrack)
		{
			$data['status'] = 'error';
			$data['response'] = 'Unable to get partyid or expected track for party';
		}
		else
		{
			//What track is spotify playing?
			$track = $this->party_model->get_current_track_at_party($partyID)['trackuri'];
			if (!$track)
			{
				$data['status'] = 'error';
				$data['response'] = 'Unable to get current track at party';
			}
			else
			{
				//continue querying database while both spotify and us say the same track is playing
				$startTime = time();
				$currentTime = time();
				while(($currentTime - $startTime) < 280 && $track == $expectedTrack)
				{
					$track = $this->party_model->get_current_track_at_party($partyID)['trackuri'];
					$currentTime = time();
					sleep(1);
				}

				//store new track in session data and return the answer
				$this->session->set_userdata('trackuri', $track);

				$status = array();
				$status['track'] = $track;
				$status['trackname'] = get_track_name($track);
				$status['artistname'] = get_artist_name($track);
				$status['albumart']	= get_album_art($track);

				$data['status'] = 'success';
				$data['result'] = $status;
			}
		}

		echo json_encode($data);
	}

	public function get_party_info()
	{
		$data = array();

		$partyid = $this->input->post('partyid');
		if(!$partyid)
		{
			$data['status'] = 'error';
			$data['response'] = 'partyid was not given';
		}
		else
		{
			$party = $this->party_model->get_party_from_id($partyid);
			if($party)
			{
				$data['status'] = 'success';
				$data['result'] = $party;
			}
			else
			{
				$data['status'] = 'error';
				$data['response'] = 'party not found!';
			}
		}
		echo json_encode($data);
	}

	/**
	 * Get list of parties register to the user
	 * @return list of parties own by user
	 */
	public function get_user_parties()
	{
		$data = array();

		$uid = $this->input->post('uid');
		if(!$uid)
		{
			$data['status'] = 'error';
			$data['response'] = 'user ID (uid) was not given';
		}
		else
		{
			$parties = $this->party_model->get_all_parties($uid);
			if($parties)
			{
				$data['status'] = 'success';
				$data['result'] = $parties;
			}
			else
			{
				$data['status'] = 'error';
				$data['response'] = 'No parties found!';
			}
		}

		echo json_encode($data);
	}

	/**
	 * create party
	 */
	public function create_party()
	{
		// prepare data for output
		$data = array();

		//get postdata
		$name = $this->input->post('name');
		$uid = $this->input->post('uid');
		$locale = $this->input->post('locale');

		// test what was sent using post
		if(!$name || !$uid || !$locale) // somethings is missing!
		{
			// set fail data!
			$data['status'] = 'error';
			$data['response'] = 'Missing post data, Not all needed fields were sent';
		}
		else // everyting is as it should
		{
			// create party
			$partyid = $this->party_model->create_party($uid, $name, $locale);
			// success?
			if($partyid)
			{
				// set success data
				$data['status'] = 'success';
				$data['result'] = $this->party_model->get_party_from_id($partyid);
			}
			else
			{
				$data['status'] = 'error';
				$data['response'] = 'Could not create party';
			}
		}
		// write array json encoded
		echo json_encode($data);
	}


	function load_party()
	{
		$time = $this->input->post('time');
		$partyhash = $this->input->post('partyhash');

		$data = array();

		if($time && $partyhash)
		{
			$partyid = $this->party_model->get_party_id_from_hash($partyhash);

			$counter = 0;
			// wait for update!
			while($this->party_model->check_if_latest($partyid, $time))
			{
				sleep(1);

				$counter++;

				if($counter > 30)
				{
					$data['status'] = 'error';
					$data['response'] = 'Party timed out.';
					echo json_encode($data);
					die();
				}
			}

			sleep(1);

			$partydata = $this->party_model->get_party_queue_from_hash($partyhash);
			if($partydata)
			{
				// loop through result and save voters
				for($i = 0; $i < count($partydata); $i++)
				{
					// prepare voters array
					$partydata[$i]['voters'] = array();

					// explode result strings into arrays
					$votename = explode(',', $partydata[$i]['votersname']);
					$votemail = explode(',', $partydata[$i]['votersmail']);
					$votetime = explode(',', $partydata[$i]['voterstime']);

					for($j = 0; $j < $partydata[$i]['vote_count']; $j ++)
					{
						// add array with email and name to array with voters.
						$partydata[$i]['voters'][] = array(
														'time' => $votetime[$j],
														'name' => $votename[$j],
														'mail' => $votemail[$j],
														'mailhash' => md5(strtolower($votemail[$j]))
												   );
					}

					// remove not needed elements from array
					unset($partydata[$i]['votersname']);
					unset($partydata[$i]['votersmail']);
				}

				$data['status'] = 'success';
				$data['response'] = array(
										'time' => date('Y-m-d H:i:s', $time),
										'partyhash' => $partyhash,
										'result' => $partydata
									);
			}
			elseif($this->party_model->hash_exists($partyhash))
			{
				$data['status'] = 'empty';
				$data['response'] = 'No party data found.';
			}
			else
			{
				$data['status'] = 'error';
				$data['response'] = 'Party load failed.';
			}
		}
		else
		{
			$data['status'] = 'error';
			$data['response'] = 'Missing post data.';
		}

		echo json_encode($data);
	}

	/**
	 * Post playing song in spotify at the party
	 */
	public function spotify_song()
	{
		//prepare data
		$data = array();

		//get Post data
		$partyid = $this->input->post('partyid');
		$trackuri = $this->input->post('trackuri');

		if(!$partyid || !$trackuri)
		{
			$data['status'] = 'error';
			$data['response'] = 'Missing post data, Not all needed fields were sent';
		}
		elseif(!$this->party_model->party_exists($partyid))
		{
			$data['status'] = 'error';
			$data['response'] = 'Party was not found';
		}
		else
		{
			$data = array('partyid' => $partyid, 'trackuri' => $trackuri);
			$query = $this->db->insert('nowplaying', $data);

			$data['status'] = 'success';
			$data['result'] = $query;

			$data['vote'] = $this->party_model->set_track_as_played($partyid, $trackuri);
		}

		echo json_encode($data);
	}

	/**
	 * get the playlist for the party
	 */
	public function get_party_list()
	{
		$partyid = $this->input->post('partyid');
		$partyqueuehash = $this->input->post('partyqueuehash');
		$data = array();

		if(!$partyid)
		{
			$data['status'] = 'error';
			$data['response'] = 'Missing post data, Not all needed fields were sent';
		}
		elseif(!$this->party_model->party_exists($partyid))
		{
			$data['status'] = 'error';
			$data['response'] = 'Party not found';
		}
		else
		{
			$queue = $this->party_model->get_party_queue($partyid);
			$hashdata = array();
			$hashdata['partyid'] = $partyid;
			$hashdata['songcount'] = sizeof($queue);
			$hashdata['votecount'] = 0;
			for($i = 0; $i < sizeof($queue); $i++)
			{
				$hashdata['votecount'] += sizeof($this->party_model->get_voters_from_song($queue[$i]['songid']));
				if($queue[$i]['played'] == 1)
				{
					array_splice($queue, $i, 1);
					$i--;
				}
			}
			for($i = 0; $i < sizeof($queue); $i++)
			{
				$voters = $this->party_model->get_voters_from_song($queue[$i]['songid']);
				for($j = 0; $j < sizeof($voters); $j++)
				{
					//we md5hash it here since we cant do it easy in javascript
					$voters[$j]['email'] = md5(strtolower($voters[$j]['email']));
				}
				$queue[$i]['voter'] = $voters;
			}

			$data['hashdata'] = $hashdata;
			$queuehash = md5(serialize($hashdata));
			// $queuehash = md5(serialize($queue));

			if($queuehash == $partyqueuehash)
			{
				$data['status'] = 'error';
				$data['result'] = 'No need to update the queue, its the same!';
			}
			else
			{
				$data['status'] = 'success';
				$data['result'] = $queue;
			}
			$data['hash'] = $queuehash;

		}

		// write array json encoded
		echo json_encode($data);
	}

	public function add_song()
	{
		// prepare data array
		$data = array();

		// get spotify song uri sent via post
		$trackuri = $this->input->post("spotifyuri");
		// get song info
		$album = $this->input->post("album");
		$artist = $this->input->post("artist");
		$songname = $this->input->post("songname");
		// get user id sent via post
		$uid = $this->login->get_id();
		// get party id sent via post
		$partyid = $this->input->post("partyid");

		if($trackuri && $this->user_model->user_exist($uid) && $this->party_model->party_exists($partyid))
		{
			$songid = $this->party_model->add_song($uid, $partyid, $trackuri);

			if($songid)
			{
				$data['status'] = 'success';
				$data['response'] = $songid;

				$albumart = get_album_art($trackuri);

				// add this song to the cache. it won't create a dublicate.
				$this->spotify_model->insert_cache($trackuri, $songname, $artist, $album, $albumart);

				$voters = $this->party_model->get_voters_from_song($trackuri);

				$user = $this->user_model->get_all_info($uid);
				$gravatarMd5 = md5(strtolower($user['email']));

				$html ='<div>';
				$html .='<img src="'. $albumart .'" alt="" width="50">';
				$html .= $artist . ' - ' . $songname . ', 1 votes ';
				$html .= '<a href="#" class="vote" data-songid="' . (isset($songid['songid']) ? $songid['songid'] : '') . '">vote!</a>';
				$html .= '<img class="voteavatar" src="http://www.gravatar.com/avatar/' . $gravatarMd5 . '?s=25&d=mm"
					alt="'. $user['name'] . '" title="'. $user['name'] . '">';
				$html .='</div>';
				$data['html'] = $html;
			}
			else
			{
				$data['status'] = 'error';
				$data['response'] = 'Could not add song.';
			}
		}
		else
		{
			$data['status'] = 'error';
			$data['response'] = 'Missing song post data or user/party id is invalid.';
			$data['uid'] = $uid;
			$data['partyid'] = $partyid;
		}
		echo json_encode($data);
	}

	public function get_spotify_img_url()
	{
		$data = array();

		$uri = $this->input->post('uri');
		if(!$uri)
		{
			$data['status'] = "error";
			$data['response'] = "uri for song not given";
		}
		else
		{
			$albumurl = get_album_art($uri);
			if($albumurl)
			{
				$data['status'] = "success";
				$data['result'] = $albumurl;
			}
			else
			{
				$data['status'] = "error";
				$data['response'] = "Error retrieving album cover";
			}
		}

		echo json_encode($data);
	}

	public function add_vote()
	{
		$data = array();

		$songid = $this->input->post('songid');
		// get user id sent via post
		$uid = $this->login->get_id();
		if(!$songid || !$this->user_model->user_exist($uid))
		{
			$data['status'] = 'error';
			$data['response'] = 'songid or uid not defined!';
		}
		else
		{
			$vote = $this->party_model->add_vote($songid, $uid);
			if($vote)
			{
				if($vote['voteid'] == 'vote already exists')
				{
					$data['status'] = 'error';
					$data['response'] = "You've already voted on this song!";
				}
				else
				{
					$data['status'] = 'success';
					$data['result'] = $vote;
				}
			}
			else
			{
				$data['status'] = 'error';
				$data['response'] = 'Vote failed for some reason!';
			}
		}
		echo json_encode($data);
	}

	/**
	 * Flips all the 'played' values in the quesong database
	 * @param  int $partyid partyid in database
	 */
	public function reset_playlist()
	{
		$data = array();
		$partyid = $this->input->post('partyid');

		if(!$partyid)
		{
			$data['status'] = 'error';
			$data['response'] = 'partyid was not given';
		}
		else
		{
			$reset = $this->party_model->reset_playlist($partyid);
			if($reset)
			{
				$data['status'] = 'success';
				$data['result'] = $reset;
			}
			else
			{
				$data['status'] = 'error';
				$data['response'] = 'Error resetting playlist';
			}
		}
		echo json_encode($data);
	}
}
