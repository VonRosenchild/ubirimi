<?php

namespace Ubirimi\FrontendCOM\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class BlogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $page = 'blog';

        $blogPage = $request->get('blog_page', 'current-roadmap');

        /* make sure no URL hijacking */
        if (!in_array($blogPage, array('revamping-our-image',
            'current-roadmap',
            'introducing-documentador',
            'feature-release',
            'introducing-svn-repositories',
            'keyboard-shortcuts',
            'new-features-in-1.9',
            'introducing-events'))) {

            $blogPage = 'introducing-events';
        }
        switch ($blogPage) {
            case 'current-roadmap':
                $blogArticle = '_currentRoadmap.php';
                break;
            case 'revamping-our-image':
                $blogArticle = '_revampingImage.php';
                break;
            case 'introducing-documentador':
                $blogArticle = '_introducingDocumentador.php';
                break;
            case 'feature-release':
                $blogArticle = '_featureRelease.php';
                break;
            case 'introducing-svn-repositories':
                $blogArticle = '_svnRepositories.php';
                break;
            case 'keyboard-shortcuts':
                $blogArticle = '_keyboardShortcuts.php';
                break;
            case 'new-features-in-1.9':
                $blogArticle = '_newFeatures.php';
                break;
            case 'introducing-events':
                $blogArticle = '_introducingEvents.php';
                break;
        }

        $content = 'Blog.php';

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}
