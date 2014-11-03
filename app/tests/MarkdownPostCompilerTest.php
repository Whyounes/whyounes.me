<?php

use AdamWathan\Blog\MarkdownPostCompiler;

class MarkdownPostCompilerTest extends TestCase
{
    public function test_can_extract_frontmatter()
    {
        $raw_post = <<<EOT
---
title: Example Post
author: Adam Wathan
slug: example-post
date: 2014-10-19
---

An example post for testing purposes.
EOT;

        $compiler = new MarkdownPostCompiler;
        $post = $compiler->compile($raw_post);
        $this->assertEquals('Example Post', $post->title);
        $this->assertEquals('Adam Wathan', $post->author);
        $this->assertEquals('example-post', $post->slug);
        $this->assertEquals('2014-10-19', $post->date);
    }

    public function test_frontmatter_can_contain_colons()
    {
        $raw_post = <<<EOT
---
title: Example: An example post
author: Adam Wathan
slug: example-post
date: 2014-10-19
---

An example post for testing purposes.
EOT;

        $compiler = new MarkdownPostCompiler;
        $post = $compiler->compile($raw_post);
        $this->assertEquals('Example: An example post', $post->title);
        $this->assertEquals('Adam Wathan', $post->author);
        $this->assertEquals('example-post', $post->slug);
        $this->assertEquals('2014-10-19', $post->date);
    }

    public function test_can_compile_post_body()
    {
        $raw_post = <<<EOT
---
author: Adam Wathan
---

An example post for testing purposes.
EOT;

        $compiler = new MarkdownPostCompiler;
        $post = $compiler->compile($raw_post);
        $this->assertEquals('<p>An example post for testing purposes.</p>', trim($post->html));
    }
}
