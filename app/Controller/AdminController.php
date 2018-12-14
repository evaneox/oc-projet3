<?php


namespace App\Controller;

use App\Model\Article;
use App\Core\Pagination;

class AdminController extends Controller{

    /**
     * Gestion de l'affichage de la page d'administration
     */
    public function index(){
        $this->checkPageAccess();

        echo $this->twig->render('admin/index.php', array(
            'page'            => 'index',
            'amount_items'    => $this->getAmountItems()
        ));

    }

    /**
     * Gestion de la page de connexion
     */
    public function login(){

        // Si l'utilisateur est déja connecté, il ne doit pas avoir accés à la page de connexion
        if($this->havePermission())
            header('Location: '.BASE_URL.'/admin');

        // Récupération des variables du formulaire
        $identifier = (!empty($_POST['identifier'])) ? strtolower($_POST['identifier']) : null;
        $password   = (!empty($_POST['password'])) ? $_POST['password'] : null;
        $remember   = (!isset($_POST['remember'])) ? false : true;
        $errors     = [];

        // Dans le cas d'une validation de formulaire
        if(isset($_POST['identifier'])){

            $user = $this->userManager->checkCredentials($identifier, $password);

            // Les identifiants ne sont pas valide
            if(!is_null($user)){

                // Le compte n'a pas les droits administrateur
                if($user->isAdmin()){

                    $this->session->setAuth($user->getId(), $remember);
                    header('Location: '.BASE_URL.'/admin');

                }else{
                    $errors['permission'] = true;
                }
            }else{
                $errors['credentials'] = true;
            }
        }

        echo $this->twig->render('admin/login.php', array(
            'errors'     => $errors,
        ));
    }


    /**
     * Gestion de la déconnexion d'un compte administrateur
     */
    public function logout(){
        $this->session->deleteAuth();
            header('Location: '.BASE_URL.'/');
    }

    /**
     * Gestion de l'affichage de la page de creation d'article
     */
    public function addArticle(){
        $this->checkPageAccess();

        echo $this->twig->render('admin/create-article.php', array(
            'page'            => 'article',
            'amount_items'    => $this->getAmountItems(),
        ));
    }

    /**
     * Gestion de l'affichage de la page d'édition de l'article
     *
     * @param $id
     */
    public function editArticle($id){
        $this->checkPageAccess();

        $article    = $this->articleManager->getByID($id);

        // Si l'article n'existe pas on retourne une page 404
        if(is_null($article)){
            header('Location: '.BASE_URL.'/404');
        }

        echo $this->twig->render('admin/edit-article.php', array(
            'page'            => 'article',
            'amount_items'    => $this->getAmountItems(),
            'article'         => $article
        ));
    }

    /**
     * Gestion de l'affichage de la liste des articles
     */
    public function listOfElement($element){
        $this->checkPageAccess();

        // Récupération des variables
        $filter     = (!empty($_GET['filter'])) ? strtoupper($_GET['filter']) : 'UNTRASH';
        $page       = !empty($_GET[PAG_KEY]) ? ((int) $_GET[PAG_KEY]) : 1;
        $offset     = ($page - 1) * PAG_ITEM_PER_PAGE;


        // On adapte le titre du tableau en fonction du filtage
        switch($filter){
            case 'UNTRASH':
                $filterTitle = 'non supprimé(s)';
                break;
            case 'TRASH':
                $filterTitle = 'dans la corbeille';
                break;
            case 'PUBLISHED':
                $filterTitle = 'publié(s)';
                break;
            case 'UNPUBLISHED':
                $filterTitle = 'non publié(s)';
                break;
            case 'REPORTED':
                $filterTitle = 'signalé(s)';
                break;
            default:
                $filterTitle = '';
                break;
        }

        if($element == 'articles'){

            $this->pagination   = new Pagination($page, $this->articleManager->getAllAmount($filter));

            echo $this->twig->render('admin/list-articles.php', array(
                'page'          => 'article',
                'amount_items'  => $this->getAmountItems(),
                'articles'      => $this->articleManager->getBetween($offset,PAG_ITEM_PER_PAGE, $filter),
                'pagination'    => $this->pagination,
                'filter'        => $filter,
                'filter_title'  => sprintf('Article(s) %s',$filterTitle)
            ));
        }
        elseif($element == 'comments'){

            $this->pagination   = new Pagination($page, $this->commentManager->getAllAmount($filter));

            echo $this->twig->render('admin/list-comments.php', array(
                'page'          => 'comment',
                'amount_items'  => $this->getAmountItems(),
                'comments'      => $this->commentManager->getBetween($offset,PAG_ITEM_PER_PAGE, $filter),
                'pagination'    => $this->pagination,
                'filter'        => $filter,
                'filter_title'  => sprintf('Commentaire(s) %s',$filterTitle)
            ));
        }
    }

    /**
     * Vérifie si l'utilisateur à acces à cette page
     * si ce n'est aps le cas, il est redirigé vers la page de connexion
     */
    private function checkPageAccess(){
        if(!$this->havePermission())
            header('Location: '.BASE_URL.'/admin/login');
    }
    /**
     * récupération du quota de commentaires et d'articles pour différents critéres
     *
     * @return array
     */
    private function getAmountItems(){
        return array(
            'article_untrash'       => $this->articleManager->getAllAmount('UNTRASH'),
            'article_published'     => $this->articleManager->getAllAmount('PUBLISHED'),
            'article_unpublished'   => $this->articleManager->getAllAmount('UNPUBLISHED'),
            'article_trash'         => $this->articleManager->getAllAmount('TRASH'),
            'comment_untrash'       => $this->commentManager->getAllAmount('UNTRASH'),
            'comment_report'        => $this->commentManager->getAllAmount('REPORTED'),
            'comment_trash'         => $this->commentManager->getAllAmount('TRASH'),
            'users'                 => $this->userManager->getAllAmount()
        );
    }
}