<?php

return [

	'ffmpeg' => [
		'hq_bitrate' => env('FFMPEG_HQ_BITRATE', 320),
		'lq_bitrate' => env('FFMPEG_LQ_BITRATE', 128),
	],
	'magnatune' => [
		'username' => env('MAGNATUNE_USERNAME', null),
		'password' => env('MAGNATUNE_PASSWORD', null),
	],

];