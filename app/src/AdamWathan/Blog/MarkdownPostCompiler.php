<?php namespace AdamWathan\Blog;

use Michelf\MarkdownExtra;

class MarkdownPostCompiler implements PostCompilerInterface
{
	private $source;
	private $post;

	public function compile($source)
	{
		$this->post = new Post;
		$this->source = $source;
		$this->extractMetadata();
		return $this->post;
	}

	private function extractMetadata()
	{
		$pattern = '/^(.+:\s+.+\n)+([\s\S]*)/';
		preg_match($pattern, $this->source, $matches);
		
		$metaData = explode("\n", $matches[0]);
		$this->parseMetaData($metaData);

		$this->post->html = $this->compileHtml($matches[2]);
	}

	private function parseMetaData($metaData)
	{
		foreach($metaData as $data) {
			$this->parseKeyValue($data);
		}
	}

	private function parseKeyValue($pair)
	{
		$matches = array();
		$pattern = '/^(.+):\s+(.+)$/';
		preg_match($pattern, $pair, $matches);

		if(count($matches)) {
			$this->post->{strtolower($matches[1])} = $matches[2];
		}
	}

	private function compileHtml($source)
	{
		return MarkdownExtra::defaultTransform($source);
	}
}