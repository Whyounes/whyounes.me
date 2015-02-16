<?php namespace AdamWathan\Blog;

use File;
use \Michelf\MarkdownExtra;
use AdamWathan\MetaMarkdown\MetaMarkdown;
use Carbon\Carbon;

class Post
{
	public $html;

	public function __toString()
	{
		return $this->render();
	}

	public function render()
	{
		return $this->html;
	}

    public function rssDate()
    {
        $dt = Carbon::createFromFormat('Y-m-d', $this->date);
        return $dt->toRSSString();
    }

    public function prettyDate()
    {
        $dt = Carbon::createFromFormat('Y-m-d', $this->date);
        return $dt->format('F j, Y');
    }
}
