<?php

namespace Alexa\Response;

class AudioItem {
	public $type = 'AudioPlayer.Play';
	public $playBehavior = 'REPLACE_ALL';
	public $url = '';
	public $content = '';
	public $title = '';

	public function render() {
		return array(
			'type' => $this->type,
			'playBehavior' => $this->playBehavior,
			'audioItem' => array(
			  'stream' => array(
          'url' => $this->url,
          'token' => 'token',
          'offsetInMilliseconds' => 0
        ),
        'metadata' => array(
          'title' => $this->title,
          'subtitle' => $this->title
        )
      )
		);
	}
}