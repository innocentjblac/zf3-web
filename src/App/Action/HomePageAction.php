<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template;
use App\Model;

class HomePageAction
{
    const NUM_POSTS = 5;

    const NUM_ADVISORIES = 4;

    private $template;

    private $posts;

    private $advisories;

    public function __construct(Model\Post $posts, Model\Advisory $advisories, Template\TemplateRendererInterface $template = null)
    {
        $this->posts      = $posts;
        $this->advisories = $advisories;
        $this->template   = $template;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $data = [
            'projects'   => require 'data/projects.php',
            'posts'      => array_slice($this->posts->getAll(), 0, self::NUM_POSTS),
            'advisories' =>  array_slice($this->advisories->getAll(), 0, self::NUM_ADVISORIES),
            'repository' => json_decode(file_get_contents('http://zendframework.github.io/zf-mkdoc-theme/scripts/zf-component-list.json'))
        ];
        $data['layout'] = 'layout::default';
        return new HtmlResponse($this->template->render('app::home-page', $data));
    }
}